@foreach ($tickets['tickets_award'] as $award_id => $award_val)
    <section class="n-section n-tickets n-section__gray" id="ticketsPrice_{{ $award_id }}">

        @foreach ($award_val['tickets'] as $event_slug => $event_val)
            <div class="inner" id="tb_{{ $event_slug }}">
                <div class="n-section__title n-logos__title @if(!$loop->first) n-ticket_types__second_title @endif">
                    {{ $event_val['info']['name'] }}
                </div>
                <div>
                    <br />
                    {{ $event_val['info']['place'] }} <br /> {{ $event_val['info']['address'] }}
                </div>
            </div>

            <div class="inner">
                <div class="n-tickets__wrapper">

                    {{-- tickets --}}
                    @foreach($event_val['tickets'] as $ticket_cat_id => $ticket_types)
                        @foreach ($ticket_types as $ticket_type_id => $type)
                            @if($type->is_prePriced == 1)
                                @includeIf('tickets.includes.popup.feedback')
                            @endif

                            <div class="ticket-item" data-id="{{ $type->id }}">
                                <div class="ticket-item__wrapper">
                                    <div class="ticket-item__top">
                                        @if(!is_null($type->our_event_id) && isset($type->our_event))
                                            <div class="ticket-item__top-date">
                                                {{ \App\Libs\Strings::monthToRus($type->our_event->start->format('d M Y')) }}
                                            </div>
                                        @endif

                                        <div class="ticket-item__title">{{ $type->socr }}</div>

                                        {{-- <div class="ticket-item__sub-title">
                                            Цена действует до 20 июня
                                            @if(isset($type->summit->subtitle) && !empty($type->summit->subtitle))
                                                <br>{{ $type->summit->subtitle }}
                                            @endif
                                        </div> --}}

                                        <div class="ticket-item__total-wrapper">
                                            <div class="ticket-item__total">
                                                @if($type->is_prePriced)
                                                    по запросу
                                                @else
                                                    <span class="ticket-type-total-price"
                                                          data-id="{{ $type->id }}"
                                                          data-price="{{ $type->price }}">
                                                        {{ number_format($type->price, 0, '.', ' ') }}
                                                    </span>&nbsp;₽
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pricedynamics --}}
                                    <div class="ticket-item__price-up">
                                        @if(count($type->price_dynamics) > 0 && $type->is_prePriced != 1)
                                            <div class="ticket-item__price-up-title">Повышение цен:</div>
                                            <div class="ticket-item__price-up-list">
                                                @foreach($type->price_dynamics as $next_price)
                                                    <div class="ticket-item__price-up-item">
                                                        с {{ Carbon\Carbon::parse($next_price->from)->translatedFormat('d F') }}
                                                        <svg width="20" height="11" viewBox="0 0 20 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M19 0.5L11.2273 8.41667L7.13636 4.25L1 10.5M19 0.5H14.0909M19 0.5V5.5"
                                                                  stroke="#0015C8"
                                                                  stroke-linecap="round"
                                                                  stroke-linejoin="round" />
                                                        </svg>
                                                        {{ number_format($next_price->price, 0, ',', ' ') }} ₽
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    {{-- //Pricedynamics --}}

                                    <div class="ticket-item__form">
                                        @includeIf('tickets.includes.form_buy')
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                    {{-- //tickets --}}

                </div>

                @if(!empty($scene) && count($stoly) > 0 && is_null($event_val['info']['summit_id']))
                    <div class="n-tickets__bottom">
                        <button type="button"
                                class="n-tickets__table-big-btn n-popup__open-btn"
                                data-popup-btn="data-popup-table-static-scheme">
                            Показать схему зала
                        </button>
                    </div>
                @endif

            </div>

        @endforeach
    </section>
@endforeach