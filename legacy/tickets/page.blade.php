@extends('layouts.new.usercp')
@section('header')

@endsection
@section('content')
    <main class="page tickets_page tickets_page_new py-5">
        <div class="container">
            <h1 class="ticket-page-title ticket-page-title__select">Выберите мероприятие</h1>
            {{-- @includeIf('breadcrumbs') --}}

            {{-- //Awards--}}
            @if(count($tickets['tickets_award']) > 0)
                <div class="tickets-tabs__swiper-container">
                    <div class="tickets-tabs__swiper">
                        <div class="swiper-wrapper">
                            @foreach($tickets['tickets_award'] as $award_id => $aw_params)
                                <div class="swiper-slide tickets-tabs__swiper-slide" data-value="{{ $award_id }}">
                                    <img src="{{ Storage::url($aw_params['info']->ticket_page->image ?? '/images/tickets2024/71522a9fa0a65e9ca3b8581552fb188a.jpg') }}" alt="" class="tickets-tabs__pic" />
                                    <div class="tickets-tabs__info">
                                        <div class="tickets-tabs__title">{{ $aw_params['info']->name }}</div>
                                        <div class="tickets-tabs__date">{{ $aw_params['info']->date_string }}, {{ $aw_params['info']->city }}</div>
                                        <div class="tickets-tabs__arrow">
                                            <a href="#tickets-box" data-scroll-to-tickets>
                                                <svg width="46" height="54" viewBox="0 0 46 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M23.25 2V52M23.25 52L44.5 31.1667M23.25 52L2 31.1667" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>


                <div class="tickets-tabs__section">
                    <div class="tickets-tabs tickets-tabs__two">
                        @foreach($tickets['tickets_award'] as $award_id => $aw_params)
                            <label class="tickets-tabs__item">
                                <input type="radio" class="choose_app_id tickets-tabs__input" name="actual_aw" value="{{ $award_id }}" />
                                <img src="{{ Storage::url($aw_params['info']->ticket_page->image ?? '/images/tickets2024/71522a9fa0a65e9ca3b8581552fb188a.jpg') }}" alt="" class="tickets-tabs__pic" />
                                <div class="tickets-tabs__info">
                                    <div class="tickets-tabs__title">{{ $aw_params['info']->name }}</div>
                                    <div class="tickets-tabs__date">
                                        {{ $aw_params['info']->date_string }}, {{ $aw_params['info']->city }}@if(!empty($aw_params['info']->place)), {{ $aw_params['info']->place }}@endif
                                        @if(!empty($aw_params['info']->addr)) <br />{{ $aw_params['info']->addr }}@endif
                                    </div>
                                    <div class="tickets-tabs__arrow">
                                        <a href="#tickets-box" data-scroll-to-tickets>
                                            <svg width="46" height="54" viewBox="0 0 46 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M23.25 2V52M23.25 52L44.5 31.1667M23.25 52L2 31.1667" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </a>
                                        {{-- календарь сюда когда-нибудь --}}
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div id="tickets-box" class="tickets-box"></div>
                    @foreach($tickets['tickets_award'] as $award_id => $aw_params)
                        <div class="awards_tickets aw_tickets_{{ $award_id }}">

                            <div class="row mb-5 mt-5 justify-content-between">
                                <div class="col align-items-start text-center" style="max-width: 900px; margin: 0px auto;">
                                    @if(!empty($aw_params['scene']['img']))
                                        <div class="ticket-page-title ticket-page-title__second">Схема зала</div>

                                        <div class="aw_scheme_modal" id="scheme-premodal-{{ $award_id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-body text-danger">
                                                @include('tickets.sheme')
                                            </div>
                                        </div>
                                        {{--
                                        <button type="button" class="ticket-button--purple ticket-button" data-target="#scheme-modal-{{ $award_id }}" data-toggle="modal">
                                            Схема зала
                                            <svg width="7" height="13" viewBox="0 0 7 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1 11.5L6 6.5L1 1.5" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        --}}
                                    @endif
                                </div>
                            </div>

                            <div class="tickets-lists-wrapper tickets-box">
                                <div class="tickets-list">
                                    @foreach ($aw_params['tickets'] as $cat)
                                        @include('tickets.ticket_types')
                                    @endforeach
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            @endif
            {{-- //Awards--}}

        </div>


        <div class="container">



            <section class="plus-awards">
                <div class="ticket-page-title ticket-page-title__second">Премия URBAN-это</div>
                <div class="plus-awards__list">
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Знаковое событие рынка недвижимости</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Продвижение объектов среди профессионального сообщества</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Неформальное общение лидеров отрасли</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Аудитория Премии -первые лица девелоперских компаний</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Возможность стать частью сообщества профессионалов</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Авторитетное жюри Премии, ведущие российские эксперты</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Звание Победителя -знак качества объекта и высокий статус девелопера</div>
                    </div>
                    <div class="plus-awards__item">
                        <svg class="plus-awards__item-arrow" width="56" height="48" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 23.75L53 23.75M53 23.75L32.1667 2.5M53 23.75L32.1667 45" stroke="#171717" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="plus-awards__item-value">Участие в формировании приоритетов развития отрасли</div>
                    </div>
                </div>
            </section>

            <section class="awards-talk">
                <div class="awards-talk__bg"><img src="/images/tickets2024/9994dbfbd3fa5397455b25b369020a7f.png" alt=""></div>
                <div class="awards-talk__wrapper">
                    <div class="ticket-page-title awards-talk__title">О премии говорят</div>
                    <div class="awards-talk__content">
                        <div class="awards-talk__text">
                            <div class="awards-talk__text-title">ЯРКОЕ ДЕЛОВОЕ СОБЫТИЕ РЫНКА НЕДВИЖИМОСТИ</div>
                            <div class="awards-talk__text-value">Премия Urban это торжественное деловое событие, обозначающее основные достижения рынка жилой городской недвижимости. Объекты-лауреаты Премии Urban получают статус «Лучший объект года» в соответствующей номинации и тем самым подтверждают соблюдение самых строгих стандартов качества на рынке жилья.</div>
                        </div>
                        <div class="awards-talk__pics">
                            <div class="awards-talk__pic"><img src="/images/tickets2024/0e8ff2b2bef8fdb99841aa249200ed4d.jpg" alt=""></div>
                            <div class="awards-talk__pic"><img src="/images/tickets2024/adafa41d5f4af1b23dcc42e2fe815a1a.jpg" alt=""></div>
                            <div class="awards-talk__pic"><img src="/images/tickets2024/ebb92252050218dc1d65d73baa2819a5.jpg" alt=""></div>
                            <div class="awards-talk__pic"><img src="/images/tickets2024/59271c5a0df7e5faa8a27e7b8dad6bd7.jpg" alt=""></div>
                        </div>
                    </div>
                    <div class="awards-talk__partners">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/1.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/2.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/3.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/4.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/1.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/2.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/3.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/4.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/1.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/2.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/3.svg" alt=""></a>
                            </div>
                            <div class="swiper-slide">
                                <a href=""><img src="/images/tickets2024/logo/4.svg" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            @php
                /*
            @endphp

            @foreach($tickets as $action_slug => $actions)
                <div class="mt-5">
                    @foreach ($actions as $ticket_cats)
                        {{-- dd($ticket_cats) --}}

                        <div class="row">
                            <div class="col">
                                @if($action_slug == "awards" && isset($ticket_cats['info']->ticket_page) && !empty($ticket_cats['info']->ticket_page->image))
                                    <img src="{{Storage::url($ticket_cats['info']->ticket_page->image)}}">
                                @elseif($action_slug == "summit" && !empty($ticket_cats['info']->background))
                                    <img src="{{Storage::url($ticket_cats['info']->background)}}">
                                @endif
                            </div>
                        </div>
                        <div class="row {{--row-cols-1 row-cols-xl-2--}} mb-5 mt-5 justify-content-between">
                            <div class="col align-items-start text-center">
                                {{--
                                <div class="mb-4">
                                <h2 class="h1">{{ $ticket_cats['info']->ticket_page->name ?? $ticket_cats['info']->subtitle ?? $ticket_cats['info']->name ?? '' }}</h2>
                                <p>&nbsp;</p>
                                <div class="address"><strong>Дата:</strong> {{  $ticket_cats['info']->date->translatedFormat($ticket_cats['info']->date_format_header ?? 'd F Y') }} года</div>
                                <div class="address">
                                    <strong>Место проведения:</strong> {{ $ticket_cats['info']->ticket_page->place ?? $ticket_cats['info']->place }}
                                    @if(isset($ticket_cats['info']->ticket_page) && !empty($ticket_cats['info']->ticket_page->addr))
                                        ({{ $ticket_cats['info']->ticket_page->addr }})
                                    @elseif(!empty($ticket_cats['info']->addr))
                                        ({{ $ticket_cats['info']->addr }})
                                    @endif
                                </div>
                                {!! $ticket_cats['info']->ticket_page->precontent ?? '' !!}
                                <br />
                                </div>
                                --}}
                                @if($action_slug == 'awards' && !empty($ticket_cats['scene']['img']))
                                    <button type="button" class="scheme-btn mb-4 mt-2" data-target="#scheme-modal" data-toggle="modal">Схема зала ></button>
                                @endif
                            </div>
                        </div>

                        <div>
                            @foreach ($ticket_cats['tickets'] as $cat)
                                <div class="ticket-types-wrapper d-flex justify-content-between flex-row flex-wrap @if($loop->last) mb-4 @endif" style="max-width: 1200px; margin: 0px auto;">
                                    @foreach ($cat as $type)
                                        @php
                                            //dump($type);
                                        @endphp
                                        <div class="ticket-type col" data-id="{{$type->id}}">
                                            <div class="ticket-type-title">{{$type->socr}}{{--@if(isset($type->summit->subtitle) && !empty($type->summit->subtitle))<br>{{$type->summit->subtitle}} @endif--}}</div>
                                            <div class="ticket-type-price">{{number_format($type->price, 0, '.', ' ')}} ₽

                                                {{--
                                                @if($_SERVER['REMOTE_ADDR'] == '89.17.38.125' && $type->old_price > 0 )
                                                    <s class="text-muted fw-normal">{{number_format($type->old_price, 0, '.', ' ')}} ₽</s>
                                                @endif
                                                --}}
                                            </div>

                                            <hr class="ticket-type-hr"/>
                                            @if(count($type->options) > 0)
                                                <ul>
                                                    @foreach($type->options as $option)
                                                        @if(!empty($option->name))
                                                            <li>{{$option->name}}</li>
                                                        @else
                                                            <br class="d-none d-lg-block">
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                            @if(!empty($type->comment))
                                                <div class="mb-4 ticket-type-comment"><small><i>* {{$type->comment}}</i></small></div>
                                            @endif
                                            @if($type->cat_id != 2)
                                                <div class="ticket-type-vybor">Выберите количество билетов</div>
                                                <div class="align-items-baseline">
                                                    <div class="ticket-type-kolvo">
                                                        <input class="ticket-type-kolvo-input" type="number" min="1" max="10" value="1" data-id="{{$type->id}}" data-price="{{$type->price}}">
                                                    </div>
                                                    <div class="ticket-type-total"><span class="d-none">&mdash;&nbsp;</span><span class="ticket-type-total-price" data-id="{{$type->id}}" data-price="{{$type->price}}">{{number_format($type->price, 0, '.', ' ')}}</span>&nbsp;₽</div>
                                                </div>
                                                <button class="red-btn w-100 ticket-buy-button" type="button" data-id="{{$type->id}}" data-kolvo="1">Купить</button>
                                            @else
                                                <div class="d-flex justify-content-between">
                                                    <button class="outline-btn table-select-button" type="button" data-id="{{$type->id}}" data-zone="{{$type->zone_id}}" >Выбрать стол</button>
                                                    <button class="red-btn table-buy-button" type="button" data-id="{{$type->id}}" data-kolvo="1" data-table="">Купить</button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                {{--$cat--}}
                            @endforeach
                        </div>

                        @if(isset($ticket_cats['info']->ticket_page) && !empty($ticket_cats['info']->ticket_page->content))
                            <p>&nbsp;</p>
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <div class="mt-4 news-content mb-2">
                                            {!! $ticket_cats['info']->ticket_page->content !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p>&nbsp;</p>
                        @endif

                        {{--$ticket_cats--}}
                    @endforeach
                </div>
            @endforeach

            @php
                */
            @endphp



            {{--@foreach ($types as $group)

            <div class="ticket-types-wrapper d-flex justify-content-between flex-row flex-wrap @if($loop->last) mb-4 @endif" style="max-width: 1200px; margin: 0px auto;">
            @foreach ($group as $type)
            <div class="ticket-type col" data-id="{{$type->id}}">
            <div class="ticket-type-title">{{$type->socr}}
            @if(isset($type->summit->subtitle) && !empty($type->summit->subtitle))<br>{{$type->summit->subtitle}} @endif
            </div>
            <div class="ticket-type-price">{{number_format($type->price, 0, '.', ' ')}} ₽</div>

            <hr class="ticket-type-hr"/>
            @if(count($type->options) > 0)
            <ul>
            @foreach($type->options as $option)
                @if(!empty($option->name))
                    <li>{{$option->name}}</li>
                @else
                    <br class="d-none d-lg-block">
                @endif
            @endforeach
            </ul>
            @endif
            @if(!empty($type->comment))
            <div class="mb-4 ticket-type-comment"><small><i>* {{$type->comment}}</i></small></div>
            @endif
            @if($type->cat_id != 2)
            <div class="ticket-type-vybor">Выберите количество билетов</div>
            <div class="align-items-baseline">
            <div class="ticket-type-kolvo">
                <input class="ticket-type-kolvo-input" type="number" min="1" max="10" value="1" data-id="{{$type->id}}" data-price="{{$type->price}}">
            </div>
            <div class="ticket-type-total"><span class="d-none">&mdash;&nbsp;</span><span class="ticket-type-total-price" data-id="{{$type->id}}" data-price="{{$type->price}}">{{number_format($type->price, 0, '.', ' ')}}</span>&nbsp;₽</div>
            </div>
            <button class="red-btn w-100 ticket-buy-button" type="button" data-id="{{$type->id}}" data-kolvo="1">Купить</button>
            @else
            <div class="d-flex justify-content-between">
            <button class="outline-btn table-select-button" type="button" data-id="{{$type->id}}" data-zone="{{$type->zone_id}}">Выбрать стол</button>
            <button class="red-btn table-buy-button" type="button" data-id="{{$type->id}}" data-kolvo="1" data-table="">Купить</button>
            </div>
            @endif
            </div>
            @endforeach
            </div>
            @endforeach--}}
            @if(isset($award))
            <div>
                {!!optional(\App\Block::where('name', 'ticket-wait')->where('award_id', $award->id)->first())->content!!}
            </div>
            @endif
        </div>

        <div class="modal fade" id="err-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ошибка</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-danger" id="err-text"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>


        @foreach($tickets['tickets_award'] as $award_id => $aw_params)
            {{--modal sheme--}}
            <div class="modal fade aw_scheme_modal" id="scheme-modal-{{ $award_id }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Схема зала</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-danger">
                            @include('tickets.sheme')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" id="close-modal">Выбрать</button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- //modal sheme --}}
        @endforeach
    </main>
@endsection
@section('styles')
    <link rel="stylesheet" href="/css/svg.css?{{time()}}">
    <style>

        .tickets_page * {
            font-family: Manrope !important;
        }
        .red {
            color: #ea1831;
        }
        hr {
            border-top-style: dashed;
        }
        p a{
            color: #2c9dd7;
        }
        div#err-modal {
            z-index: 10000;
            background: rgb(0 0 0 / 52%);
        }
        .date {
            font-family: 'Open Sans';
            font-weight: bold;
            font-size: 17px;
        }
        .scheme-btn {
            background-color: #5B5B5B;
            border-color: #5B5B5B;
            border-radius: 2px;
            height: 50px;
            color: white;
            text-transform: uppercase;
            font-family: 'GothamPro';
            font-size: 15px;
            text-align: center;
            padding: 0 43px;
        }
        .ticket-types-wrapper {
            column-gap: 30px;
        }
        .ticket-type {
            background: #FFFFFF;
            border: 1px solid #CCCCCC;
            box-sizing: border-box;
            border-radius: 20px;
            padding: 30px;
            /*width: calc(50% - 17px);*/
            margin-bottom: 30px;
        }
        .ticket-type-title {
            font-family: 'GothamPro';
            font-size: 17px;
            font-weight: 700;
            line-height: 22px;
            letter-spacing: 0em;
            text-align: left;
            margin-bottom: 16px;
        }
        .ticket-type-price {
            font-family: 'GothamPro';
            font-size: 34px;
            font-weight: 700;
            line-height: 22px;
            letter-spacing: 0em;
            text-align: left;
            margin-bottom: 30px;
        }
        .ticket-type-price s {
            font-weight: 300;
            font-size: 25px;
        }
        .ticket-type-price-total {
            font-weight: bold;
        }
        .ticket-type-hr {
            border: 1px solid #CCCCCC;
        }
        .ticket-type-vybor {
            margin-bottom: 24px;
        }
        .ticket-type-kolvo {
            margin-bottom: 24px;
        }
        .ticket-type-kolvo button  {
            font-size: 15px;
        }
        .ticket-type-kolvo-input {
            font-weight: bold;
            font-size: 15px;
        }
        .ticket-type-total {
            font-weight: bold;
        }
        .red-btn {
            background: #F2003A;
            height: 50px;
            width: calc(50% - 5px);
            font-size: 15px;
            text-transform: none;
            border-radius: 2px;
        }
        .outline-btn {
            height: 50px;
            width: calc(50% - 5px);
            font-size: 15px;
            text-transform: none;
            color: black;
            background: white;
            border-radius: 2px;
            border: 2px solid black;
            font-family: 'GothamPro';
        }
        .address {
            font-size: 17px;
        }
        @media (max-width: 991px) {
            .ticket-type {
                min-width: 100%;
            }
        }
        @media (min-width: 992px) {
            .ticket-type-kolvo {
                max-width: 200px;
                margin-right: 10px;
            }
            .ticket-type {
                max-width: calc(50% - 15px);
                min-width: calc(25% - 30px);
            }
        }
    </style>
@endsection
@section('scripts')
    <script src="/libs/bootstrap-input-spinner.js"></script>
    <script src="/templates/spb2020/srcjs/vendors/swiper.min.js"></script>
    <script>
        $(document).ready(function() {
            document.querySelector(`[name="actual_aw"]`).closest('label').click()
        });

        var swiperOptions = {
            loop: true,
            freeMode: true,
            spaceBetween: 140,
            grabCursor: true,
            slidesPerView: "auto",
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: true
            },
            freeMode: true,
            speed: 5000,
            freeModeMomentum: false
        };

        var swiper = new Swiper(".awards-talk__partners", swiperOptions);

        function slideSelect() {
            const activeSlideIndex = +document.querySelector('.swiper-pagination-bullet-active').getAttribute('aria-label').replace(/\D/g, "")-1
            if(document.querySelector('.tickets-tabs__swiper-slide.active')) {
                document.querySelector('.tickets-tabs__swiper-slide.active').classList.remove('active')
            }
            const activeSlideEl =  document.querySelectorAll('.tickets-tabs__swiper-slide')[activeSlideIndex]
            activeSlideEl.classList.add('active')
            activeSlideEl.closest('.tickets-tabs__swiper-container').classList.add('active')
            document.querySelector(`[name="actual_aw"][value="${activeSlideEl.getAttribute('data-value')}"]`).closest('label').click()
        }

        const swiperTabs = new Swiper(".tickets-tabs__swiper", {
            slidesPerView: "auto",
            spaceBetween: 7,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            on: {
                transitionEnd() {
                    slideSelect()
                },
                click() {
                    if(this.clickedIndex !== undefined) {
                        this.slideTo(this.clickedIndex)
                        slideSelect()
                    }

                },
            }

        })


        /* tables scripts */

        var kolvo = 1;
        var table_id = null;
        var type = null;
        var zone = null;
        $(document).on('hidden.bs.modal', '.modal', function () {
            $('.modal.show').length && $(document.body).addClass('modal-open');
        });
        function selectTable (id) {
            if (type == null) return;
            var circle = $('.svg_g[data-id="'+id+'"]');
            if (circle.attr('data-status') != 1) {
                $('#err-text').text('Стол занят или забронирован! Обратитесь к менеджеру за подробностями.');
                $('#err-modal').modal();
                return false;
            }
            if (circle.attr('data-zone') != zone) {
                $('#err-text').text('Выберите стол нужной категории!');
                $('#err-modal').modal();
                return false;
            }
            var selected = circle.attr('data-selected');
            $('.svg_g[data-id!="'+id+'"]>circle').css('fill', '');
            $('.svg_g[data-id!="'+id+'"]').attr('data-selected', 0);

            if (selected == 0) {
                $(circle).attr('data-selected', 1);
                $('.svg_g[data-id="'+id+'"]>circle').css('fill', 'black');
                table_id = id;
                $('#close-modal').attr('data-tablename', $(circle).attr('data-name'));
            }
            /*else if (selected == 1){
            $(circle).attr('data-selected', 0);
            $('.svg_g[data-id="'+id+'"]>circle').css('fill', '');
            table_id = null;
            }*/
        }

        @foreach($tickets['tickets_award'] as $award_id => $aw_params)
        $('#scheme-modal-{{ $award_id }}').on('shown.bs.modal',function(){
            var width=$('#scheme-modal-{{ $award_id }} .modal-body').width();
            var scene_width = {{ $aw_params['info']->scene_width ?? 575 }};
            var scene_height = {{ $aw_params['info']->scene_height ?? 300 }};
            var ratio = width/scene_width;
            if (ratio>1.5)ratio=1.5;
            $('.svgwrapper').css('zoom',ratio);
            $('.svgwrapper').css('-moz-transform','scale('+ratio+')');
            if(navigator.userAgent.toLowerCase().indexOf('firefox')>-1){
                $('.svgkostyl').css('height',scene_height*ratio+'px');
            }
            //$('.svg_g>circle').css('fill', '');
            //$('.svg_g').attr('data-selected', 0);
        });

        $('#scheme-premodal-{{ $award_id }}').on('shown.bs.modal2',function(){
            var width=$('#scheme-premodal-{{ $award_id }} .modal-body').width();
            var scene_width = {{ $aw_params['info']->scene_width ?? 575 }};
            var scene_height = {{ $aw_params['info']->scene_height ?? 300 }};
            var ratio = width/scene_width;
            if (ratio>1.5)ratio=1.5;
            $('.svgwrapper').css('zoom',ratio);
            $('.svgwrapper').css('-moz-transform','scale('+ratio+')');
            if(navigator.userAgent.toLowerCase().indexOf('firefox')>-1){
                $('.svgkostyl').css('height',scene_height*ratio+'px');
            }
            //$('.svg_g>circle').css('fill', '');
            //$('.svg_g').attr('data-selected', 0);
        });
        @endforeach

        $(window).on('resize',function () {
            $('.aw_scheme_modal').trigger('shown.bs.modal');
        });
        $(document).ready(function() {
            $('.aw_scheme_modal').trigger('shown.bs.modal2');
        });

        /* tickets scripts */

        $(function() {
            $("input[type='number']").inputSpinner();
            $('.stolname, .marking').each(function(){
                this.parentNode.appendChild(this);
            });

            /*
            $('.svg_g').hover(function () {
                $('.stolname, .marking').hide();
                $(this).children('.stolname, .marking').show();
            });
            */

            $('.ticket-buy-button:disabled, .table-buy-button:disabled').css({"opacity":"0.5"}).removeClass('ticket-button--purple');
        });
        $('.ticket-type-kolvo-input').on('change', function () {
            var val = parseInt($(this).val());
            kolvo = val;
            var price = parseFloat($(this).data('price'));
            var id = $(this).data('id');
            type = id;
            $('.ticket-buy-button[data-id='+id+']').attr('data-kolvo', val);
            var text = new Intl.NumberFormat('ru-RU', {maximumFractionDigits: 0}).format(val * price);
            $('.ticket-type-total-price[data-id=' + id +']').text(text);
        });
        $('.ticket-buy-button:not(:disabled)').on('click', function () {
            var id = $(this).data('id');
            var kolvo_ = $(this).attr('data-kolvo');
            window.open('/tickets/pay?type=' + id + '&count=' + kolvo_, '_self');
        });
        $('.table-buy-button').on('click', function () {
            @if ($_SERVER['REMOTE_ADDR'] == '95.24.82.118')
            var avail = $(this).data('avail');
            console.log(avail);
            if (avail === 0) {
                return;
            }
            @endif
            var id = $(this).data('id');
            var table = $(this).attr('data-table');
            /*if (table == null || table.length == 0) {
            $('#err-text').text('Сначала выберите стол!');
            $('#err-modal').modal();
            return false;
            }*/
            window.open('/tickets/pay?type=' + id + '&count=1&table=' + table, '_self');
        });
        $('.table-select-button').on('click', function () {
            type = $(this).data('id');
            zone = $(this).attr('data-zone');
            aw_id = $(this).attr('data-awardid');
            //$('.table-buy-button[data-id=' + type + ']').attr('data-table', null);
            $('#close-modal').attr('data-type', type);
            $('#scheme-modal-'+aw_id).modal();
        });
        $('#close-modal').on('click', function() {
            type = $(this).attr('data-type');
            name = $(this).attr('data-tablename');
            $('.table-buy-button[data-id=' + type + ']').attr('data-table', table_id);
            $('.table-select-button[data-id=' + type + ']').text('Выбран стол: ' + name);
            $('.table-select-button[data-id!=' + type + ']').text('Выбрать стол');
        });

        $('.awards_tickets').hide();
        $('.choose_app_id').on('ifChecked',function(){
            this.closest('.tickets-tabs').classList.add('active')
            $('.tickets-tabs__item.active').removeClass('active')

            this.closest('label').classList.add('active')
            $app_st_id = $(this).val();
            $('.awards_tickets').hide();
            $('.aw_tickets_' + $app_st_id).show();
            /*
            $('.tickets_sheme').each(function() {
                var sheme_award = $(this).data('sheme_aw_id');
               if($(this).hasClass('hidden') && $this.hasClass('tickets_sheme_'+sheme_award)) {
                   $(this).removeClass('hidden');
               } elseif(!$(this).hasClass('hidden')) {
                    $(this).addClass('hidden');
               }
            });
            */
        });

        $('[data-scroll-to-tickets]').on('click', function(event) {
            event.preventDefault()
            document.getElementById('tickets-box').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            })
        })

    </script>
@endsection
