# Arendator Awards — лендинг продажи билетов

Новая версия страницы `aawards.ru/tickets`. Статичные HTML + CSS + JS, **без сборки и без зависимостей**.
Всё, что подключается к бэкенду (ID билетов, адреса форм, дата таймера, цели Метрики), собрано в одном файле — `assets/js/config.js`.

```
index.html              ← вся разметка лендинга (блоки 01–11 подписаны комментариями)
assets/css/style.css    ← стили (все классы с префиксом aa-, конфликтов с Bootstrap нет)
assets/js/config.js     ← НАСТРОЙКИ: ID билетов, эндпоинты, дата, Метрика, demo-режим
assets/js/seating.js    ← СХЕМА РАССАДКИ: столы, зоны, вместимость, брони, координаты
assets/js/main.js       ← логика: попапы, таймер, выбор стола на схеме, анимации
assets/img/             ← сюда кладутся фото и логотипы (список имён — в assets/img/README.md)
legacy/tickets/         ← исходники старой страницы (Blade) — только для сверки
```

## Как посмотреть

```bash
python3 -m http.server 8000   # из корня репозитория → http://localhost:8000
```
Можно открыть и просто `index.html` в браузере. В демо-режиме вместо формы оплаты открывается заглушка, а заявка «Запрос» не отправляется (данные пишутся в консоль).

---

## Блоки страницы (по ТЗ)

| № | Блок | id якоря | Что внутри / откуда данные |
|---|------|----------|----------------------------|
| 01 | Шапка (hero) | `#top` | Фото `assets/img/hero.jpg`. **Стилистику шапки ждём от Саши** |
| 02 | О премии + фото | `#about` | Цифры 11+ / 85+ / 300+ (из старого `facts.blade.php`), 3 фото |
| 03 | Три причины быть на Arendator Awards | — | Тексты взяты из `benefits.blade.php`. Сверить с живой страницей |
| 04 | С кем вы проведёте этот вечер | `#guests` | Бегущая строка: ESVE, STONE, LEVEL GROUP, Лемана Про, Space 1, FORMA, VALTARI, Донстрой, MR |
| 05 | Тайминг | `#program` | 17:00 сбор гостей · 18:00 основная программа · 19:00 церемония и вечерняя программа |
| 06 | Схема зала | `#scheme` | «Выберите, где вы хотите быть». Утверждённая схема рассадки 1:1 с легендой, выбор стола |
| 07 | Выберите формат участия + таблица сравнения | `#packages` | VIP 140 000 ₽ · Бизнес 115 500 ₽ · Стол — по запросу · Персональный 49 000 ₽ |
| 08 | Как проходит Arendator Awards | `#gallery` | Видео + 6 фото |
| 09 | Что говорят участники | `#reviews` | **Перенести отзывы с главной** (сейчас шаблонные карточки) |
| 10 | До церемонии осталось | `#countdown` | Таймер до `event.startsAt` из config.js |
| 11 | Оформление билета | `#order` | 3 шага + «Остались вопросы?» → попап «Запрос» |

---

## Как подключены кнопки

### «Купить билет» (VIP, Бизнес, Персональный)

```html
<button data-buy="vip">Купить билет</button>        <!-- ключ из LANDING_CONFIG.tickets -->
```

1. Клик → открывается попап `#buyModal`, внутри контейнер `#popupBuyForm` (id как в старом `includes/popup/buy.blade.php`).
2. AJAX `GET /tickets/pay?type={ticketId}&count=1&table=`. Это **тот же роут, что и на старой странице**, он отдаёт `payment.blade.php`.
3. HTML формы вставляется в попап. Если на странице есть jQuery, то через `$.html()`, чтобы выполнились скрипты формы (dadata, промокод, спиннер количества).
4. Дальше всё работает как раньше: форма шлёт `POST /tickets/buy`.
   - `pay_type=2` (карта): JSON `redirect_url` → переход на оплату.
   - `pay_type=1` (счёт): HTML-ответ заменяет форму.
   - Цель Метрики `ticket` отправляет сама форма.

**Что сделать:** в `config.js` проставить `ticketId` для `vip`, `business`, `personal` (ID из таблицы типов билетов в админке, тот же, что раньше уходил в `?type=`) и выставить `demo: false`.

### «Запрос» (пакет «Стол») и «Задать вопрос»

```html
<button data-request="table">Запрос</button>
<button data-request="question">Задать вопрос</button>
```

Открывается попап с формой (по образцу Commercial Talks): ФИО, компания, телефон (с маской), email, комментарий, согласие на обработку ПД, скрытое антиспам-поле `website`.

Отправка: `POST` на `endpoints.request` (FormData, заголовки `X-CSRF-TOKEN` + `X-Requested-With`, плюс поле `_token`).
Поля: `name, company, phone, email, comment, pol_agree, package (table|question), type_id, source=tickets-landing, website`.
Успех — любой ответ 2xx. Ошибка — любой другой статус, тогда пользователь видит сообщение и может отправить ещё раз.

**Что сделать:** завести роут и указать его в `config.js → endpoints.request`. Минимальный пример:

```php
// routes/web.php
Route::post('/tickets/request', [\App\Http\Controllers\TicketRequestController::class, 'store'])
    ->middleware('throttle:10,1')->name('tickets.request');

// app/Http/Controllers/TicketRequestController.php
public function store(Request $request)
{
    if ($request->filled('website')) return response()->json(['ok' => true]); // бот
    $data = $request->validate([
        'name' => 'required|string|max:255', 'company' => 'nullable|string|max:255',
        'phone' => 'required|string|max:32', 'email' => 'required|email',
        'comment' => 'nullable|string|max:2000', 'package' => 'required|in:table,question',
        'type_id' => 'nullable|integer',
    ]);
    // TODO: сохранить в CRM/БД и/или отправить письмо менеджеру
    // Mail::to(config('mail.tickets_manager'))->send(new TicketRequestMail($data));
    return response()->json(['ok' => true]);
}
```

> Если на сайте уже есть обработчик заявок (в старом `includes/popup/feedback.blade.php` форма грузилась в `#feedback_ajax_{id}`), можно указать его адрес. Главное, чтобы он принимал поля выше и отвечал 2xx.

### Схема зала и выбор стола
План перенесён 1:1 с утверждённой картинки: стены, welcome-зона, бар, сцена, вход, колонна, 72 столов с номерами, подписи «до N чел» с выносками, легенда.
- Статичная часть плана (стены, бар, сцена) лежит в `index.html`, блок 06.
- Столы и подписи задаются в `assets/js/seating.js`: номер, зона (`vip` / `business` / `personal`), вместимость, координаты, `booked`. Стол можно добавить, перенести или забронировать без правки кода.
- Цвета зон взяты с картинки: **ВИП — зелёный, Бизнес — розово-красный, Персональные — жёлтый, забронировано — белый с обводкой.**

Как это работает у гостя:
1. Фильтр зон над схемой показывает, сколько свободных столов в каждой зоне.
2. При наведении на стол появляется подсказка: номер, зона, вместимость. Кнопки «+» и «−» меняют масштаб, увеличенную схему можно перетаскивать мышью, на телефоне листать пальцем.
3. Клик по свободному столу открывает карточку справа (на телефоне внизу появляется панель): зона, «до N чел», формат и цена.
4. **«Купить билет за этот стол»** открывает оформление билета зоны: `GET /tickets/pay?type={ticketId зоны}&count=1&table={ID стола}`. Это тот же параметр `table`, что был в старой схеме.
5. **«Забронировать стол целиком»** открывает форму запроса с полем `table` = номер стола.

**Что сделать:**
- `config.js → seating.tableIds`: сопоставить номер стола на схеме с ID стола в БД, чтобы бэкенд понял `table=`. Без сопоставления уходит номер стола.
- Брони: либо вести `booked: true` в `seating.js`, либо указать `config.js → seating.statusUrl`. Это адрес, отдающий JSON `{"booked": ["702","703", …]}`, и тогда схема подтянет занятые столы при загрузке. Пример на Laravel:

```php
Route::get('/tickets/seating-status', function () {
    // TODO: взять реальные занятые столы текущей премии
    $booked = \App\Stol::where('award_id', $awardId)->where('status', '!=', 1)->pluck('name');
    return response()->json(['booked' => $booked]);
});
```

### Прямые ссылки
- `…/tickets#buy-vip`, `#buy-business`, `#buy-personal` — сразу открывают оформление билета
- `…/tickets#request-table`, `#request-question` — сразу открывают форму запроса

Удобно для рассылок и рекламы.

### Метрика
Счётчик `47656444` подключается layout-ом сайта, лендинг сам его **не** грузит. Лендинг только отправляет цели, если `ym` есть на странице: `tickets_buy_click`, `tickets_request_open`, `tickets_request_sent`. Имена целей меняются в `config.js → analytics.goals`. Цели нужно завести в интерфейсе Метрики.

---

## Перенос в Laravel (Blade)

### Вариант А (рекомендуемый): внутри layout сайта
Так форма оплаты гарантированно получит всё, к чему привыкла: jQuery, iCheck (события `ifChecked` в `payment.blade.php`), стили полей.

1. Скопировать `assets/` в `public/landing/tickets/`.
2. Создать `resources/views/tickets/landing.blade.php`:

```blade
@extends('layouts.new.usercp') {{-- тот же layout, что у текущей страницы /tickets --}}

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('landing/tickets/css/style.css') }}?v=1">
    <script>
        {{-- подмена отсутствующих картинок + флаг для анимаций; скопировать из <head> index.html --}}
    </script>
@endsection

@section('content')
    {{-- вставить всё от <div class="aa"> до </div><!-- /.aa --> из index.html --}}
@endsection

@section('scripts')
    <script src="{{ asset('landing/tickets/js/config.js') }}?v=1"></script>
    <script src="{{ asset('landing/tickets/js/main.js') }}?v=1" defer></script>
@endsection
```

3. Пути картинок `assets/img/...` заменить на `{{ asset('landing/tickets/img/...') }}` (или сделать поиск-замену `assets/` → `/landing/tickets/`).
4. В layout должен быть `<meta name="csrf-token" content="{{ csrf_token() }}">`. Если его нет, добавить.
5. Если у layout своя шапка сайта, блок `<header class="aa-nav">` можно удалить.

Все классы лендинга начинаются с `aa-`, сбросы стилей действуют только внутри `.aa`. Стили сайта и Bootstrap лендинг не ломают, и сам лендинг на остальной сайт не влияет.

### Вариант Б: отдельная страница
`index.html` работает как самостоятельная страница. В этом случае для формы оплаты нужно подключить то же, что есть в layout сайта: jQuery, iCheck, `/libs/bootstrap-input-spinner.js`, CSS полей формы. Иначе переключение «счёт / карта» не заработает.

### (Опционально) Цены и наличие из админки
Сейчас цены прописаны в HTML по ТЗ. Если хочется брать их из БД, как раньше (`$tickets['tickets_award'][...]`), то в Blade:

```blade
<p class="aa-card__price"><span>{{ number_format($vip->price, 0, '.', ' ') }}</span>&nbsp;₽</p>
<button class="aa-btn aa-btn--primary aa-btn--block" data-buy="vip"
        @if($vip->total_quantity === 0) disabled @endif>
    {{ $vip->total_quantity === 0 ? 'Нет в наличии' : 'Купить билет' }}
</button>
```

А `ticketId` отдать в JS прямо из шаблона, тогда `config.js` для ID не нужен:

```blade
<script>
  window.LANDING_CONFIG.tickets.vip.ticketId      = {{ $vip->id }};
  window.LANDING_CONFIG.tickets.business.ticketId = {{ $business->id }};
  window.LANDING_CONFIG.tickets.personal.ticketId = {{ $personal->id }};
  window.LANDING_CONFIG.demo = false;
</script>
```

---

## Чек-лист перед запуском

- [ ] `config.js`: проставить `ticketId` для VIP / Бизнес / Персональный и выставить `demo: false`
- [ ] `config.js`: заполнить `endpoints.request` (роут заявки на стол / вопрос)
- [ ] `config.js → seating.tableIds`: номера столов → ID столов в БД
- [ ] Брони столов: `seating.js` (`booked`) или `config.js → seating.statusUrl`
- [ ] `config.js`: проверить `event.startsAt` (сейчас 8 октября 2026, 17:00 МСК)
- [ ] Картинки: положить в `assets/img/` по списку из `assets/img/README.md`
- [ ] Логотипы 9 компаний: `assets/img/logos/*.svg`
- [ ] Логотип премии: `assets/img/logo.svg` (белый)
- [ ] Отзывы: перенести с главной в блок 09
- [ ] Шапка: применить стилистику от Саши
- [ ] Место проведения: добавить в hero и блок «Оформление», если нужно (сейчас указано только «Москва»)
- [ ] Метрика: завести цели `tickets_buy_click`, `tickets_request_open`, `tickets_request_sent`
- [ ] Проверить покупку по счёту и картой на тестовом типе билета

## Вопросы по контенту

1. **Блок 2.** В ТЗ он назван «Блок 2 причины (актуальные фотографии)». Сделан как «О премии» с цифрами и тремя фото. Если имелось в виду другое, поправим.
2. **Персональный билет, алкоголь.** В ТЗ не указан, в карточке и таблице стоит «—».
3. **Орфография.** В ТЗ написано «Выберете, где вы хотите быть» и «Выберете формат участия». На странице стоит грамматически верное «Выберите…». Если нужно ровно как в ТЗ, это правка в двух местах `index.html`.
4. **Цвета секторов.** В ТЗ было «ВИП — красный, Бизнес — зелёный», а на утверждённой схеме рассадки наоборот: ВИП зелёный, Бизнес розовый. Сделано **по схеме**, в карточках и таблице сравнения тоже.
5. **Вместимость столов 106 и 406** на схеме не подписана, поэтому показывается «уточняйте у менеджера».
6. **Забронированные столы** (702–708, 401–404, 301–304) на схеме белые, их зона неизвестна. Если бронь снимут, в `seating.js` нужно указать зону.

## Замечания по старому коду (`legacy/`), на что обратить внимание

- `payment.blade.php`, строка 7: `$type->price_discounts{0}`. Доступ к элементу через фигурные скобки **удалён в PHP 8**, это fatal error. На PHP 7.4 работает с deprecation. При обновлении PHP заменить на `[0]`.
- `page.blade.php`: захардкоженные проверки IP (`$_SERVER['REMOTE_ADDR'] == '95.24.82.118'`) и остаток текста от другой премии («Премия URBAN — это»).
- `includes/popup/scheme.blade.php`: захардкоженные `data-id="30"` и `data-id="29"` у зон схемы.
- `sheme.blade.php`: подписи столов смещаются через `rand()`, поэтому при каждой загрузке схема выглядит по-разному.
