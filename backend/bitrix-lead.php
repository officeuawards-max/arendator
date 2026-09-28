<?php
/**
 * Заявка с лендинга билетов → лид в Битрикс24.
 * Отдельный файл без фреймворка (PHP 7.4+ с curl), если лендинг живёт не внутри Laravel.
 *
 * Установка:
 *   1. Положить файл на сервер, например в /tickets/lead.php
 *   2. Задать адрес вебхука: переменная окружения BITRIX24_WEBHOOK
 *      или константа ниже (файл тогда не должен попадать в публичный репозиторий).
 *   3. assets/js/config.js → endpoints.lead = '/tickets/lead.php'
 *   4. Журнал согласий (152-ФЗ, ч. 3 ст. 9): переменная окружения PD_CONSENT_LOG — путь к файлу
 *      ВНЕ публичной папки сайта, например /var/lib/aawards/pd-consents.log (папка должна быть доступна на запись).
 *
 * Вебхук: Битрикс24 → Разработчикам → Другое → Входящий вебхук, права — только «CRM (crm)».
 */

const BITRIX24_WEBHOOK_FALLBACK = '';   // 'https://ваш-портал.bitrix24.ru/rest/1/xxxxxxxxxxxxxxxx/'
const BITRIX24_ASSIGNED_ID = null;      // ID ответственного менеджера или null
const EVENT_NAME = 'Arendator Awards 2026';
const PACKAGES = [
    'vip'      => ['title' => 'VIP билет',          'price' => 140000],
    'business' => ['title' => 'Бизнес',             'price' => 115500],
    'personal' => ['title' => 'Персональный билет', 'price' => 49000],
    'table'    => ['title' => 'Стол',               'price' => null],
];

date_default_timezone_set('Europe/Moscow');
header('Content-Type: application/json; charset=utf-8');

function reply(int $code, array $body): void
{
    http_response_code($code);
    echo json_encode($body, JSON_UNESCAPED_UNICODE);
    exit;
}

function field_raw(string $serverKey, int $max = 500): string
{
    return mb_substr((string) ($_SERVER[$serverKey] ?? ''), 0, $max);
}

function field(string $key, int $max = 255): string
{
    $v = isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
    return mb_substr($v, 0, $max);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') reply(405, ['ok' => false]);
if (field('website') !== '') reply(200, ['ok' => true]);                 // бот

// простая защита от спама: не чаще 1 заявки в 5 секунд с одного IP
$lock = sys_get_temp_dir() . '/aa_lead_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
if (is_file($lock) && time() - filemtime($lock) < 5) reply(429, ['ok' => false]);
@touch($lock);

$intent  = field('intent') === 'question' ? 'question' : 'booking';
$package = field('package');
$name    = field('name');
$phone   = field('phone', 32);
$email   = field('email');

$errors = [];
if ($name === '') $errors[] = 'name';
if (strlen(preg_replace('/\D/', '', $phone)) < 10) $errors[] = 'phone';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'email';
if (field('consent_pd') !== '1') $errors[] = 'consent_pd';          // 152-ФЗ: согласие на обработку ПД обязательно
if ($intent === 'booking' && !isset(PACKAGES[$package])) $errors[] = 'package';
if ($errors) reply(422, ['ok' => false, 'errors' => $errors]);

$isQuestion = $intent === 'question';
$pack   = $isQuestion ? null : PACKAGES[$package];
$guests = $isQuestion ? null : max(1, min(50, (int) field('guests', 3)));
$sum    = ($pack && $pack['price']) ? $pack['price'] * $guests : null;

// Доказательство согласия (ч. 3 ст. 9 152-ФЗ): время, IP, браузер, версия документов.
// Журнал — JSON-строки в файле ВНЕ публичной папки сайта (путь можно задать переменной окружения).
$consent = [
    'at'          => date('c'),
    'client_at'   => field('consent_at', 40),
    'ip'          => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent'  => field_raw('HTTP_USER_AGENT'),
    'version'     => field('consent_version', 20),
    'consent_pd'  => true,
    'consent_ads' => field('consent_ads') === '1',
    'email'       => $email,
    'phone'       => $phone,
    'page'        => field('page', 1000),
];
$logPath = getenv('PD_CONSENT_LOG') ?: __DIR__ . '/../storage/pd-consents.log';
@file_put_contents($logPath, json_encode($consent, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);

$comments = array_filter([
    $isQuestion ? 'Тип: вопрос' : 'Формат: ' . $pack['title'],
    $isQuestion ? null : 'Гостей: ' . $guests,
    $sum ? 'Ориентировочная сумма: ' . number_format($sum, 0, ',', ' ') . ' ₽' : null,
    field('comment', 3000) !== '' ? 'Комментарий: ' . field('comment', 3000) : null,
    field('page', 1000) !== '' ? 'Страница: ' . field('page', 1000) : null,
    field('referrer', 1000) !== '' ? 'Откуда пришёл: ' . field('referrer', 1000) : null,
    field('ym_client_id', 64) !== '' ? 'Метрика ClientID: ' . field('ym_client_id', 64) : null,
    'Согласие на обработку ПД: да, ред. ' . ($consent['version'] ?: '—') . ', ' . $consent['at'] . ', IP ' . $consent['ip'],
    'Согласие на рассылки: ' . ($consent['consent_ads'] ? 'да' : 'нет'),
]);

$fields = [
    'TITLE'              => $isQuestion ? EVENT_NAME . ' — вопрос с сайта' : EVENT_NAME . ' — ' . $pack['title'] . ', гостей: ' . $guests,
    'NAME'               => $name,
    'COMPANY_TITLE'      => field('company'),
    'POST'               => field('position'),
    'PHONE'              => [['VALUE' => $phone, 'VALUE_TYPE' => 'WORK']],
    'EMAIL'              => [['VALUE' => $email, 'VALUE_TYPE' => 'WORK']],
    'SOURCE_ID'          => 'WEB',
    'SOURCE_DESCRIPTION' => 'Лендинг билетов aawards.ru/tickets',
    'COMMENTS'           => implode("<br>\n", array_map(function ($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }, $comments)),
    'UTM_SOURCE'         => field('utm_source'),
    'UTM_MEDIUM'         => field('utm_medium'),
    'UTM_CAMPAIGN'       => field('utm_campaign'),
    'UTM_CONTENT'        => field('utm_content'),
    'UTM_TERM'           => field('utm_term'),
];
if ($sum) { $fields['OPPORTUNITY'] = $sum; $fields['CURRENCY_ID'] = 'RUB'; }
if (BITRIX24_ASSIGNED_ID) $fields['ASSIGNED_BY_ID'] = (int) BITRIX24_ASSIGNED_ID;

$webhook = rtrim(getenv('BITRIX24_WEBHOOK') ?: BITRIX24_WEBHOOK_FALLBACK, '/');
if ($webhook === '') {
    error_log('Bitrix24: вебхук не задан, лид не создан');
    reply(500, ['ok' => false]);
}

$ch = curl_init($webhook . '/crm.lead.add.json');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode(['fields' => $fields, 'params' => ['REGISTER_SONET_EVENT' => 'Y']], JSON_UNESCAPED_UNICODE),
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
]);
$raw  = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

$res = json_decode((string) $raw, true);
if ($code !== 200 || empty($res['result'])) {
    error_log('Bitrix24: лид не создан. HTTP ' . $code . ' ' . $err . ' ' . $raw);
    reply(502, ['ok' => false]);
}

reply(200, ['ok' => true, 'lead_id' => $res['result']]);
