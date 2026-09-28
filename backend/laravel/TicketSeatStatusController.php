<?php

/**
 * Занятые столы для схемы зала на лендинге → из базы сайта.
 *
 * Зачем: на сайте уже есть таблица столов со статусами — по ним старая страница билетов
 * (legacy/tickets/sheme.blade.php) и urbanweek.blade.php красят столы: $stol->status2 ?? $stol->status,
 * подписи — массив $busy. Этот контроллер отдаёт лендингу список ЗАНЯТЫХ столов,
 * и они становятся фиолетовыми «Места закончились» без правки seating.js.
 *
 * Ответ:  GET /tickets/seats  →  {"booked": ["702", "703", "401"]}
 * Номера — как на схеме (поле name стола в базе = поле n в assets/js/seating.js).
 *
 * Установка:
 *   1. Скопировать в app/Http/Controllers/TicketSeatStatusController.php
 *   2. routes/web.php:
 *        Route::get('/tickets/seats', [\App\Http\Controllers\TicketSeatStatusController::class, 'index'])
 *            ->name('tickets.seats');
 *   3. Заполнить два TODO ниже (запрос столов и какие статусы считать «занято»).
 *   4. assets/js/config.js → seating.statusUrl = '/tickets/seats'
 *
 * ВАЖНО:
 *   - Список ПОЛНОСТЬЮ заменяет флаги booked из seating.js: столы из списка — фиолетовые,
 *     остальные — свободные (кроме столов с zone: null — они фиолетовые всегда).
 *   - «Частично занятый» стол — НЕ отдавать как занятый: на лендинге бронируется зона, а не стол,
 *     и в частично занятом столе места ещё есть.
 *   - Отдаём только номера. Никаких имён гостей, компаний и т. п. — ответ видит любой посетитель.
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TicketSeatStatusController extends Controller
{
    /**
     * TODO(Игорь): статусы, которые значат «стол занят целиком».
     * Это ключи массива $busy, которым старая страница красит столы (класс «obvodka» = «ЗАНЯТО»).
     * «Частично занято» (класс «partobvodka») сюда НЕ добавлять.
     */
    private const BUSY_STATUSES = [/* например: 2 */];

    public function index()
    {
        // пока TODO не заполнены — честно говорим «нет данных», и лендинг берёт статусы из seating.js
        if (!self::BUSY_STATUSES) {
            return response()->json(['error' => 'not configured'], 503);
        }

        // кэш на минуту: страницу могут открыть тысячи раз, базу дёргаем редко
        $booked = Cache::remember('tickets:seats:booked', 60, function () {
            try {
                $tables = $this->tables();
                if ($tables->isEmpty()) {
                    return null;   // столов нет — значит, запрос не настроен; не обнуляем схему
                }
                return $tables
                    ->filter(function ($stol) {
                        $status = $stol->status2 ?? $stol->status;
                        return in_array($status, self::BUSY_STATUSES, false);
                    })
                    ->pluck('name')
                    ->map(function ($name) { return (string) $name; })
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                Log::error('tickets/seats: не удалось получить столы', ['error' => $e->getMessage()]);
                return null;
            }
        });

        if ($booked === null) {
            // лендинг тогда возьмёт статусы из seating.js
            return response()->json(['error' => 'unavailable'], 503);
        }

        return response()->json(['booked' => $booked])
            ->header('Cache-Control', 'public, max-age=60');
    }

    /**
     * TODO(Игорь): вернуть столы Arendator Awards 2026 — тем же запросом, которым контроллер старой
     * страницы заполняет $aw_params['scene']['tables'] (у каждого стола нужны поля name, status, status2).
     *
     * @return \Illuminate\Support\Collection
     */
    private function tables()
    {
        // Пример (названия модели и полей — угадать нельзя, подставьте настоящие):
        // return \App\Models\Stol::where('award_id', AWARD_ID)->get(['name', 'status', 'status2']);
        return collect();
    }
}
