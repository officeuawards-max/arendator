<?php

/**
 * Заявка с лендинга билетов → лид в Битрикс24.
 *
 * Установка (Laravel):
 *   1. Скопировать файл в app/Http/Controllers/TicketLeadController.php
 *   2. routes/web.php:
 *        Route::post('/tickets/lead', [\App\Http\Controllers\TicketLeadController::class, 'store'])
 *            ->middleware('throttle:10,1')->name('tickets.lead');
 *   3. .env:
 *        BITRIX24_WEBHOOK=https://ваш-портал.bitrix24.ru/rest/1/xxxxxxxxxxxxxxxx/
 *        BITRIX24_ASSIGNED_ID=        # необязательно: ID ответственного менеджера
 *   4. config/services.php → в массив добавить:
 *        'bitrix24' => [
 *            'webhook'     => env('BITRIX24_WEBHOOK'),
 *            'assigned_id' => env('BITRIX24_ASSIGNED_ID'),
 *        ],
 *   5. assets/js/config.js → endpoints.lead = '/tickets/lead'
 *
 * Вебхук создаётся в Битрикс24: Разработчикам → Другое → Входящий вебхук,
 * права — только «CRM (crm)». Адрес вебхука — секрет, в браузер он не попадает.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TicketLeadController extends Controller
{
    /** Цены за гостя — считаются на сервере, клиенту не доверяем. */
    private const PACKAGES = [
        'vip'      => ['title' => 'VIP билет',          'price' => 140000],
        'business' => ['title' => 'Бизнес',             'price' => 115500],
        'personal' => ['title' => 'Персональный билет', 'price' => 49000],
        'table'    => ['title' => 'Стол',               'price' => null],
    ];

    private const EVENT = 'Arendator Awards 2026';

    public function store(Request $request)
    {
        // антиспам: скрытое поле должен заполнить только бот
        if ($request->filled('website')) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'intent'       => 'required|in:booking,question',
            'package'      => 'nullable|required_if:intent,booking|in:vip,business,personal,table',
            'guests'       => 'nullable|integer|min:1|max:50',
            'name'         => 'required|string|max:255',
            'phone'        => 'required|string|max:32',
            'email'        => 'required|email|max:255',
            'company'      => 'nullable|string|max:255',
            'position'     => 'nullable|string|max:255',
            'comment'      => 'nullable|string|max:3000',
            // 152-ФЗ: согласие на обработку ПД обязательно (отдельная галочка), на рассылки — по желанию
            'consent_pd'      => 'accepted',
            'consent_ads'     => 'nullable|in:0,1',
            'consent_version' => 'nullable|string|max:20',
            'consent_at'      => 'nullable|string|max:40',
            'utm_source'   => 'nullable|string|max:255',
            'utm_medium'   => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_content'  => 'nullable|string|max:255',
            'utm_term'     => 'nullable|string|max:255',
            'page'         => 'nullable|string|max:1000',
            'referrer'     => 'nullable|string|max:1000',
            'ym_client_id' => 'nullable|string|max:64',
        ]);

        $isQuestion = $data['intent'] === 'question';
        $pack   = $isQuestion ? null : self::PACKAGES[$data['package']];
        $guests = $isQuestion ? null : (int) ($data['guests'] ?? 1);
        $sum    = ($pack && $pack['price']) ? $pack['price'] * $guests : null;

        $title = $isQuestion
            ? self::EVENT . ' — вопрос с сайта'
            : self::EVENT . ' — ' . $pack['title'] . ', гостей: ' . $guests;

        // Доказательство согласия (ч. 3 ст. 9 152-ФЗ): время по серверу, IP, браузер, версия документов.
        // Пишется в отдельный журнал и в лид. Журнал храните на сервере в РФ весь срок обработки данных.
        $consent = [
            'at'          => now()->toIso8601String(),
            'client_at'   => $data['consent_at'] ?? null,
            'ip'          => $request->ip(),
            'user_agent'  => mb_substr((string) $request->userAgent(), 0, 500),
            'version'     => $data['consent_version'] ?? null,
            'consent_pd'  => true,
            'consent_ads' => ($data['consent_ads'] ?? '0') === '1',
            'email'       => $data['email'],
            'phone'       => $data['phone'],
            'page'        => $data['page'] ?? null,
        ];
        Log::build(['driver' => 'daily', 'path' => storage_path('logs/pd-consents.log'), 'days' => 1100])
            ->info('pd_consent', $consent);

        $comments = array_filter([
            $isQuestion ? 'Тип: вопрос' : 'Формат: ' . $pack['title'],
            $isQuestion ? null : 'Гостей: ' . $guests,
            $sum ? 'Ориентировочная сумма: ' . number_format($sum, 0, ',', ' ') . ' ₽' : null,
            !empty($data['comment']) ? 'Комментарий: ' . $data['comment'] : null,
            !empty($data['page']) ? 'Страница: ' . $data['page'] : null,
            !empty($data['referrer']) ? 'Откуда пришёл: ' . $data['referrer'] : null,
            !empty($data['ym_client_id']) ? 'Метрика ClientID: ' . $data['ym_client_id'] : null,
            'Согласие на обработку ПД: да, ред. ' . ($consent['version'] ?: '—') . ', ' . $consent['at'] . ', IP ' . $consent['ip'],
            'Согласие на рассылки: ' . ($consent['consent_ads'] ? 'да' : 'нет'),
        ]);

        $fields = [
            'TITLE'              => $title,
            'NAME'               => $data['name'],
            'COMPANY_TITLE'      => $data['company'] ?? '',
            'POST'               => $data['position'] ?? '',
            'PHONE'              => [['VALUE' => $data['phone'], 'VALUE_TYPE' => 'WORK']],
            'EMAIL'              => [['VALUE' => $data['email'], 'VALUE_TYPE' => 'WORK']],
            'SOURCE_ID'          => 'WEB',
            'SOURCE_DESCRIPTION' => 'Лендинг билетов aawards.ru/tickets',
            'COMMENTS'           => implode("<br>\n", array_map('e', $comments)),
            'UTM_SOURCE'         => $data['utm_source'] ?? '',
            'UTM_MEDIUM'         => $data['utm_medium'] ?? '',
            'UTM_CAMPAIGN'       => $data['utm_campaign'] ?? '',
            'UTM_CONTENT'        => $data['utm_content'] ?? '',
            'UTM_TERM'           => $data['utm_term'] ?? '',
        ];
        if ($sum) {
            $fields['OPPORTUNITY'] = $sum;
            $fields['CURRENCY_ID'] = 'RUB';
        }
        if ($assigned = config('services.bitrix24.assigned_id')) {
            $fields['ASSIGNED_BY_ID'] = (int) $assigned;
        }

        $webhook = rtrim((string) config('services.bitrix24.webhook'), '/');
        if ($webhook === '') {
            Log::error('Bitrix24: BITRIX24_WEBHOOK не задан, лид не создан', ['fields' => $fields]);
            return response()->json(['ok' => false], 500);
        }

        try {
            $response = Http::timeout(10)->asJson()->post($webhook . '/crm.lead.add.json', [
                'fields' => $fields,
                'params' => ['REGISTER_SONET_EVENT' => 'Y'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Bitrix24: ошибка запроса', ['error' => $e->getMessage(), 'fields' => $fields]);
            return response()->json(['ok' => false], 502);
        }

        if (!$response->ok() || !$response->json('result')) {
            Log::error('Bitrix24: лид не создан', ['status' => $response->status(), 'body' => $response->body(), 'fields' => $fields]);
            return response()->json(['ok' => false], 502);
        }

        return response()->json(['ok' => true, 'lead_id' => $response->json('result')]);
    }
}
