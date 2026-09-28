@php
    //dump($cat);
@endphp
    @foreach ($cat as $type)
        <div class="ticket-item" data-id="{{ $type->id }}">
        <div class="ticket-item__wrapper">
            @if(!is_null($type->our_event_id) && isset($type->our_event))
                <div class="ticket-item__top-date">{{ \App\Libs\Strings::monthToRus($type->our_event->start->format('d M Y')) }}</div>
            @endif

            <div class="ticket-item__title">{{ $type->socr }}</div>



            {{--<div class="ticket-item__sub-title">Цена действует до 20 июня @if(isset($type->summit->subtitle) && !empty($type->summit->subtitle)<br>{{$type->summit->subtitle}} @endif</div>--}}
            <div class="ticket-item__info">
                @if(count($type->options) > 0)
                    <ul class="ticket-item__info-list">
                        @foreach($type->options as $option)
                            @if(!empty($option->name))
                                <li class="ticket-item__info-item">{{ $option->name }}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>

            @if($type->cat_id != 2)
                <div class="ticket-item__form">
                    <div class="ticket-item__total-wrapper">
                        <div class="ticket-item__total">
                            <span class="ticket-type-total-price" data-id="{{ $type->id }}" data-price="{{ $type->price }}">{{ number_format($type->price, 0, '.', ' ') }}</span>&nbsp;₽
                        </div>
                    </div>

                    @if(!is_null($type->price_availto) || !is_null($type->total_quantity))
                        <div class="mb-3">
                            @if(!is_null($type->price_availto))
                                <div>* Цена действительна до {{ \Carbon\Carbon::parse($type->price_availto)->translatedFormat('d F Y') }}</div>
                            @endif
                            @if(!is_null($type->total_quantity))
                                <div>* Осталось всего {{ $type->total_quantity}} билетов</div>
                            @endif
                        </div>
                    @endif

                    @if(count($type->price_dynamics) > 0)
                        <div class="mt-3 mb-3">
                            @foreach($type->price_dynamics as $next_price)
                                <div>с {{ Carbon\Carbon::parse($next_price->from)->translatedFormat('d F Y') }} — {{ number_format($next_price->price, 0, ',', ' ') }} ₽</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="ticket-item__qt">
                        @if($type->total_quantity !== 0)
                         <div class="ticket-type-kolvo">
                            <input class="ticket-type-kolvo-input" type="number" min="1" max="10" value="1" data-id="{{ $type->id }}" data-price="{{ $type->price }}" data-avail="{{ $type->total_quantity ?? '-' }}">
                        </div>
                        @endif
                        <button class="red-btn w-100 ticket-buy-button ticket-item__buy-btn ticket-button--purple ticket-button" type="button" data-id="{{ $type->id }}" data-kolvo="1" @if($type->total_quantity === 0) disabled @endif>@if($type->total_quantity === 0) Нет в наличии @else Купить @endif</button>
                    </div>
                </div>


            @else
            <div class="ticket-item__form">
                <div class="ticket-item__total-wrapper">
                    <div class="ticket-item__total">
                        <span class="ticket-type-total-price" data-id="{{ $type->id }}" data-price="{{ $type->price }}">{{ number_format($type->price, 0, '.', ' ') }}</span>&nbsp;₽
                    </div>
                    @if($type->total_quantity !== 0)
                        <button class="ticket-item__table-link table-select-button" type="button" data-id="{{ $type->id }}" data-zone="{{ $type->zone_id }}" data-awardid="{{ $award_id }}">
                            Выбрать стол
                            <svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 11.5L6 6.5L1 1.5" stroke="#171717" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                        </button>
                    @endif
                </div>

                @if(!is_null($type->price_availto) || !is_null($type->total_quantity))
                <div class="mb-3">
                    @if(!is_null($type->price_availto))
                    <div>* Цена действительна до {{ \Carbon\Carbon::parse($type->price_availto)->translatedFormat('d F Y') }}</div>
                    @endif
                    @if(!is_null($type->total_quantity))
                    <div>* Осталось всего {{ $type->total_quantity}} столов</div>
                    @endif
                </div>
                @endif

                <div class="ticket-item__qt">
                    {{--
                    <div class="ticket-type-kolvo">
                        <input class="ticket-type-kolvo-input" type="number" min="1" max="10" value="1" data-id="{{ $type->id }}" data-price="{{ $type->price }}" data-avail="{{ $type->total_quantity ?? '-' }}">
                    </div>
                    --}}
                    <button class="red-btn table-buy-button ticket-item__buy-btn ticket-button--purple ticket-button" type="button" data-id="{{ $type->id }}" data-kolvo="1" data-table="" @if($type->total_quantity === 0) disabled @endif>@if($type->total_quantity === 0) Нет в наличии @else Купить @endif</button>
                </div>
            </div>
            @endif
        </div>
        </div>
    @endforeach
{{--$cat--}}
