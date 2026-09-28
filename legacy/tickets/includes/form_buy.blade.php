@php
    $isSoldOut  = $type->total_quantity === 0;
    $hasTables  = $type->total_quantity !== 0 && !empty($scene) && count($stoly) > 0;
    $isTableCat = $type->cat_id == 2;
@endphp

@if(!is_null($type->price_availto) || !is_null($type->total_quantity))
    <div class="mb-3">
        @if(!is_null($type->price_availto))
            <div>
                * Цена действительна до {{ \Carbon\Carbon::parse($type->price_availto)->translatedFormat('d F Y') }}
            </div>
        @endif

        @if(!is_null($type->total_quantity))
            <div>
                * Осталось всего {{ $type->total_quantity }} билетов
            </div>
        @endif
    </div>
@endif

<div class="ticket-item__qt">
    {{-- Кнопка «Что входит» / «Выбрать стол» --}}
    @if($isTableCat && $hasTables && $type->is_prePriced != 1)
        <button class="ticket-item__table-link table-select-button"
                type="button"
                data-id="{{ $type->id }}"
                data-zone="{{ $type->zone_id }}"
                data-awardid="{{ $award->id }}"
                data-popup-btn="data-popup-table">
            Выбрать стол
        </button>
    @elseif($type->is_prePriced != 1)
        <button class="w-100 ticket-buy-button ticket-item__what-btn"
                data-popup-btn="data-buy-form"
                type="button"
                data-id="{{ $type->id }}"
                data-kolvo="1">
            Что входит
        </button>
    @endif

    {{-- Основная кнопка покупки --}}
    @if($type->is_prePriced != 1)
        <button class="red-btn ticket-item__buy-btn ticket-button--purple ticket-button
                    {{ $isTableCat ? 'table-buy-button' : 'w-100 ticket-buy-button' }}"
                data-popup-btn="data-buy-form"
                type="button"
                data-id="{{ $type->id }}"
                data-kolvo="1"
                @if($isTableCat) data-table="" @endif
                @if($isSoldOut) disabled @endif>
            {{ $isSoldOut ? 'Нет в наличии' : 'Купить' }}
        </button>
    @else
        <button class="ticket-item__table-link"
                data-popup-btn="data-feedback-form-{{ $type->id }}"
                type="button"
                data-id="{{ $type->id }}"
                data-kolvo="1"
                @if($isSoldOut) disabled @endif>
            Оставить заявку
        </button>
    @endif

</div>
