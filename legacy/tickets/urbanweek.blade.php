@extends('layouts.ua-spb-2020')
@section('content')
@include('subheader')
<main class="page pt-4 ua-week">
    <div class="container">
        @includeIf('breadcrumbs')
    </div>
    <ul class="nav nav-tabs d-none" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="page1-tab" data-toggle="tab" href="#page1" role="tab" aria-controls="page1" aria-selected="true"></a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="page2-tab" data-toggle="tab" href="#page2" role="tab" aria-controls="page2" aria-selected="false"></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="page1" role="tabpanel">
            <div class="container">
                <h1>Неделя с Премией Urban</h1>
                <div class="ua-week__intro">Масштабное мероприятие для лидеров рынка недвижимости, включающее в себя
                        разнообразные форматы и площадки для обмена опытом и непрерывного нетворкинга!
                </div>
                <div class="ua-week-adv">
                    <div class="ua-week-adv__item">
                        <div class="ua-week-adv__num">3</div>
                        <div class="ua-week-adv__text">дня активного нетворкинга и обмена опытом</div>
                    </div>
                    <div class="ua-week-adv__item">
                        <div class="ua-week-adv__num">200+</div>
                        <div class="ua-week-adv__text">участников форума</div>
                    </div>
                    <div class="ua-week-adv__item">
                        <div class="ua-week-adv__num">50</div>
                        <div class="ua-week-adv__text">экспертов рынка недвижимости</div>
                    </div>
                    <div class="ua-week-adv__item">
                        <div class="ua-week-adv__num">30+</div>
                        <div class="ua-week-adv__text">регионов</div>
                    </div>
                </div>
                <div class="ua-week-sq">
                    <a href="javascript:void(0)" class="ua-week-sq__item ua-week-sq__w100" data-class="modal1">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Проперти тур</div>
                            {{--<div class="ua-week-sq__date">22-23 июня</div>--}}
                            <div class="ua-week-sq__date">
                                22 июня с 9:00 до 18:00
                            </div>
                            <div class="ua-week-sq__text mb-2">Тур по жилым комплексам Санкт-Петербурга</div>
                            <div class="ua-week-sq__date">
                                23 июня с 16:30 до 19:00
                            </div>
                            <div class="ua-week-sq__text">Lifestyle с элементами коливинга We&I by Vertical, Becar Asset Management</div>
                        </div>
                        <img class="ua-week-sq__img" src="/img/urbanweek/605066505ec9e8e814443a5661d21948.jpg" alt="">
                    </a>
                    <a href="javascript:void(0)" class="ua-week-sq__item ua-week-sq__w50" data-class="modal2">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Welcome-коктейль</div>
                            <div class="ua-week-sq__date">22 июня, начало в 19:00</div>
                            <div class="ua-week-sq__text">Нетворкинг «без пиджаков»</div>
                        </div>
                        <img class="ua-week-sq__img" src="/img/urbanweek/3bc650d5dba6d24e3ee8f61453c003f7.jpg" alt="">

                    </a>
                    <a href="javascript:void(0)" class="ua-week-sq__item ua-week-sq__w50" data-class="modal5">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Urban Space</div>
                            <div class="ua-week-sq__date">23 июня, 10:00-16:00</div>
                            <div class="ua-week-sq__text">Встреча лидеров рынка недвижимости</div>
                        </div>
                        <img class="ua-week-sq__img" src="/img/urbanweek/73d23eab35e8ea0ca3ca1584dd2363f3.jpg" alt="">
                    </a>
                    <a href="javascript:void(0)" class="ua-week-sq__item ua-week-sq__w50" data-class="modal3">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Тур по городским общественным пространствам</div>
                            <div class="ua-week-sq__date">24 июня, 10:00-13:30</div>
                            <div class="ua-week-sq__text">Знаковые общественные пространства Санкт-Петербурга</div>
                        </div>
                        <img class="ua-week-sq__img" src="/img/urbanweek/719f51dbcc5a0c00f45ce5932c773147.jpg" alt="">
                    </a>
                    <a href="javascript:void(0)" class="ua-week-sq__item ua-week-sq__w50" data-class="modal4">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Премия Urban</div>
                            <div class="ua-week-sq__date">24 июня, 17:00</div>
                            <div class="ua-week-sq__text">Церемония награждения федеральной премии</div>
                            <div class="ua-week-sq__text"><b>Адрес:</b> Санкт-Петербург, Петропавловская крепость, 2,<br>
                            Двор Инженерного дома</div>
                        </div>
                        <img class="ua-week-sq__img" src="/img/urbanweek/7b6806a3080cfd0c788ab625f81bd21b.jpg" alt="">
                    </a>
                </div>
                <div class="ua-week__intro-bottom">
                    Вас ждет увлекательная культурная программа, тур по жилым комплексам и современным общественным
                    Санкт-Петербурга, а также деловое мероприятие — 3 ДНЯ полного погружения в деловую среду рынка
                    недвижимости!
                </div>
                <div class="svgkostyl">
                    <div class="svgwrapper">
                        <div class="svgscene"></div>
                        <div class="svgstoly">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                @foreach($stoly as $stol)
                                    @php
                                        $stollength=mb_strlen($stol->mark);
                                        $rectwidth=pow($stollength*6,0.7)*2;
                                    @endphp
                                    <g class="svg_g " data-id="{{$stol->id}}">
                                        <title>{{$stol->name}}</title>
                                        <circle class="svgcircle {{$busy[$stol->status2??$stol->status]}}" fill="{{$stol->zone->color}}" cx="{{$stol->pos_x+$award->scene_offset_x}}" cy="{{$stol->pos_y+$award->scene_offset_y}}" r="15"></circle>
                                        <text class="stolnum" x="{{$stol->pos_x+$award->scene_offset_x}}" y="{{$stol->pos_y+$award->scene_offset_y}}" text-anchor="middle" alignment-baseline="middle">{{$stol->name}}</text>
                                        <rect class="svgrect" x="{{$stol->pos_x+$award->scene_offset_x-($rectwidth/2)}}" y="{{$stol->pos_y+$award->scene_offset_y+6}}" width="{{$rectwidth}}" height="14" rx="2" ry="2"></rect>
                                        <text class="stolname" x="{{$stol->pos_x+$award->scene_offset_x}}" y="{{$stol->pos_y+$award->scene_offset_y+13}}" text-anchor="middle" alignment-baseline="middle">{{$stol->mark}}</text>
                                    </g>
                                @endforeach
                            </svg>
                        </div>
                        <div class="svglegend">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                @foreach($zones as $zone)
                                    <g>
                                        <circle fill="{{$zone->color}}" cx="{{20+($loop->index*80)}}" cy="15" r="6"></circle>
                                        <text class="svglegendtext" x="{{35+($loop->index*80)}}" y="15" alignment-baseline="middle">- {{$zone->name}}</text>
                                    </g>
                                @endforeach
                                <g>
                                    <circle class="obvodka" fill="#fff" cx="200" cy="15" r="6"></circle>
                                    <text class="svglegendtext" x="215" y="15" alignment-baseline="middle">- ЗАНЯТО</text>
                                </g>
                                <g>
                                    <circle class="partobvodka" fill="#fff" cx="315" cy="15" r="6"></circle>
                                    <text class="svglegendtext" x="330" y="15" alignment-baseline="middle">- ЧАСТИЧНО ЗАНЯТО</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ua-week__section ua-week-format">
                <div class="container">
                    <div class="ua-week__section-title">Форматы участия</div>
                    <div class="ua-week-format__list">
                        @foreach ($packages as $package)
                            <div class="ua-week-format__item">
                                @if($package->flag)
                                <div class="ua-week-format__item-flag">
                                    <svg width="25" height="50" viewBox="0 0 25 50" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 0H25V50L12.5 42.3661L0 50V0Z" fill="#2F0E89" />
                                    </svg>
                                </div>
                                @endif
                                <div class="ua-week-format__item-in">
                                    <div class="ua-week-format__item-name">{{$package->socr??$package->name}}</div>
                                    <div class="ua-week-format__item-price">{{number_format($package->type?$package->type->price:$package->price,0,',',' ')}} <i class="fas fa-ruble-sign"></i>
                                    </div>
                                    <div class="ua-week-format__event-list">
                                        @php
                                            $mindate=$package->options->min('date');
                                            $maxdate=$package->options->max('date');
                                        @endphp
                                        <div class="ua-week-format__event">
                                            <div class="ua-week-format__event-date">{{$maxdate->gt($mindate)?($mindate->translatedFormat('d F')).' - '.($maxdate->translatedFormat('d F')):($mindate->translatedFormat('d F'))}}</div>
                                            <div class="ua-week-format__event-text">{{$package->options->where('pivot.price',0)->where('visible',1)->sortBy('date')->pluck('name')->join(' + ')}}</div>
                                        </div>
                                    </div>
                                    <div class="ua-week-format__item-bottom">
                                        <div class="ua-week-format__event-small-title">Количество билетов</div>
                                        <div class="ua-week-format__event-calc" data-price="{{$package->type?$package->type->price:$package->price}}">
                                            <div class="ua-week-format__event-input-wr">
                                                <input class="ua-week-format__event-input" type="number" min="1" max="10" value="1" data-id="{{$package->id}}">
                                            </div>
                                            <div class="ua-week-format__event-total">&mdash; <span>{{number_format($package->type?$package->type->price:$package->price,0,',',' ')}}</span> руб.</div>
                                        </div>
                                        <div class="ua-week-format__item-btn">
                                            <a href="javascript:void(0)" class="button button-pink-white js-package-btn" data-id="{{$package->id}}">Выбрать</a>
                                            <input type="checkbox" style="display:none" class="package-checkbox" data-id="{{$package->id}}" name="packages[{{$package->id}}]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <h2 class="mb-5">Полезная информация для гостей</h2>
                    <p><b>Специально для участников церемонии награждения и участников Urban Week была разработана система скидок в отелях, расположенных рядом с местом проведения:</b></p>
                    <div class="d-flex flex-row flex-wrap row">
                        @foreach ($hotels['nogroup'] as $hotel)
                            <div class="col-12 col-lg-4 d-flex flex-column hotel mb-4">
                                <span class="hotel-discount"><b>
                                @if(isset($hotel->discount_from))
                                Скидка {{$hotel->discount_from}}%@if($hotel->discount_to>$hotel->discount_from) - {{$hotel->discount_to}}%@endif
                                @else
                                Специальная цена
                                @endif
                                </b></span>
                                <span class="hotel-name"><a href="{{$hotel->site}}" target="_blank">{{$hotel->name}}</a></span>
                                <span class="hotel-promocode">Промокод: {{$hotel->promocode}}</span>
                            </div>
                        @endforeach
                        @foreach ($hotels as $group=>$hotels_)
                            @continue($group=='nogroup')
                            <div class="col-12 col-lg-4 d-flex flex-column hotel mb-4">
                                <span class="hotel-discount"><b>Скидка {{$hotels_->first()->discount_from}}%</b></span>
                                @foreach ($hotels_ as $hotel)
                                    <span class="hotel-name"><a href="{{$hotel->site}}" target="_blank">{{$hotel->name}}</a></span>
                                @endforeach
                                <span class="hotel-promocode">Промокод: {{$hotels_->first()->promocode}}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="page2" role="tabpanel">
            <div class="container">
                <div class="h1"><span id="package-name"></span></div>
                <div class="ua-week-sostav">
                    <div class="ua-week-sostav__title">Состав заказа</div>
                    <div class="ua-week-sostav__list-wr">
                        <div class="ua-week-sostav__list"></div>
                    </div>
                    {{--<div class="ua-week-sostav__dop-info">Бронирование доступно только для участников из регионов России (кроме СПБ и ЛО)</div>--}}
                    <div class="ua-week-sostav__itogo">Итого <span id="totalsum2">0</span> <i class="fas fa-ruble-sign"></i></div>
                </div>
                <div class="mesto-grid mb-40">
                    <div class="ua-week-sq__item ua-week-sq__w100">
                        <div class="ua-week-sq__info">
                            <div class="ua-week-sq__title">Дата и место</div>
                            <div class="ua-week-sq__date">24 июня</div>
                            <div class="ua-week-sq__text">
                            Санкт-Петербург, Петропавловская крепость, 2,<br>
                            Двор Инженерного дома
                            </div>
                        </div>
                        @if(isset($award->ticket_page) && !empty($award->ticket_page->image))
                            <img class="ua-week-sq__img" src="{{Storage::url($award->ticket_page->image)}}" alt="Место проведения">
                        @endif
                    </div>
                    <div class="mesto-map">
                    {!!$award->map!!}
                    </div>
                </div>
                <h2 class="mb-4">Полезная информация для гостей</h2>
                <p class="mt-0"><b>Специально для участников церемонии награждения и участников Urban Week была разработана система скидок в отелях, расположенных рядом с местом проведения.</b></p>
                <hr class="mt-40 mb-40">
                <h2 class="mb-1">Схема зала</h2>
                <div class="svgkostyl">
                    <div class="svgwrapper">
                        <div class="svgscene"></div>
                        <div class="svgstoly">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                @foreach($stoly as $stol)
                                    @php
                                        $stollength=mb_strlen($stol->mark);
                                        $rectwidth=pow($stollength*6,0.7)*2;
                                    @endphp
                                    <g class="svg_g " data-id="{{$stol->id}}">
                                        <title>{{$stol->name}}</title>
                                        <circle class="svgcircle {{$busy[$stol->status2??$stol->status]}}" fill="{{$stol->zone->color}}" cx="{{$stol->pos_x+$award->scene_offset_x}}" cy="{{$stol->pos_y+$award->scene_offset_y}}" r="15"></circle>
                                    <text class="stolnum" x="{{$stol->pos_x+$award->scene_offset_x}}" y="{{$stol->pos_y+$award->scene_offset_y}}" text-anchor="middle" alignment-baseline="middle">{{$stol->name}}</text>
                                    <rect class="svgrect" x="{{$stol->pos_x+$award->scene_offset_x-($rectwidth/2)}}" y="{{$stol->pos_y+$award->scene_offset_y+6}}" width="{{$rectwidth}}" height="14" rx="2" ry="2"></rect>
                                    <text class="stolname" x="{{$stol->pos_x+$award->scene_offset_x}}" y="{{$stol->pos_y+$award->scene_offset_y+13}}" text-anchor="middle" alignment-baseline="middle">{{$stol->mark}}</text>
                                    </g>
                                @endforeach
                            </svg>
                        </div>
                        <div class="svglegend">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                @foreach($zones as $zone)
                                    <g>
                                        <circle fill="{{$zone->color}}" cx="{{20+($loop->index*80)}}" cy="15" r="6"></circle>
                                        <text class="svglegendtext" x="{{35+($loop->index*80)}}" y="15" alignment-baseline="middle">- {{$zone->name}}</text>
                                    </g>
                                @endforeach
                                <g>
                                    <circle class="obvodka" fill="#fff" cx="200" cy="15" r="6"></circle>
                                    <text class="svglegendtext" x="215" y="15" alignment-baseline="middle">- ЗАНЯТО</text>
                                </g>
                                <g>
                                    <circle class="partobvodka" fill="#fff" cx="315" cy="15" r="6"></circle>
                                    <text class="svglegendtext" x="330" y="15" alignment-baseline="middle">- ЧАСТИЧНО ЗАНЯТО</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="text-danger"><i>* При покупке отдельных билетов нет возможности выбора стола</i></p>
                <div class="mb-3">
                    <b>В стоимость билета (ов) на церемонию включено</b><br>
                    <ul>
                        <li>Посещение церемонии награждения</li>
                        <li>Презентация новинок отрасли — премьер года</li>
                        <li>Фуршет во время welcome-части</li>
                        <li>Банкет во время церемонии</li>
                        <li>Яркая и запоминающаяся развлекательная программа</li>
                        <li>Участие в финальном этапе голосования за Девелопера года, Персону года и Grand Prix (для всех посетителей церемонии)</li>
                        <li>Каталог Премии Urban с финалистами премии</li>
                    </ul>
                </div>
                <hr class="mb-40 mt-40">
                <div class="ua-order-form mb-4">
                    <h2 class="mb-3">Форма заказа</h2>
                    <form method="post" autocomplete="off" id="zakaz-form" action="/ticket-order">
                        @csrf
                        <div class="opl-box">
                            <label class="redstar">Выберите тип оплаты</label>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="w-radio">
                                            <label>
                                                <input type="radio" name="pay_type" class="pay_type" value="2">
                                                <span></span>
                                                <div class="w-radio__text">
                                                Оплата картой (+5%)
                                                <svg width="27" height="17" viewBox="0 0 27 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M26.7047 8.4C26.7047 10.0667 26.2454 11.7333 25.3269 13.1333C24.4083 14.5333 23.1617 15.6667 21.6526 16.2667C20.1435 16.9333 18.5032 17.1333 16.8629 16.8C15.2883 16.4667 13.7792 15.6667 12.5982 14.5333C11.4172 13.3333 10.6298 11.8667 10.3018 10.2C9.97371 8.53333 10.1049 6.86667 10.7611 5.26667C11.4172 3.73333 12.467 2.4 13.7792 1.46667C15.157 0.533333 16.7317 0 18.372 0H18.4376C19.4874 0 20.6028 0.2 21.587 0.6C22.5712 1 23.4897 1.66667 24.2771 2.4C25.0644 3.2 25.6549 4.13333 26.0486 5.13333C26.5079 6.2 26.7047 7.33333 26.7047 8.4Z" fill="#F59D1A"/>
                                                    <path d="M16.5338 8.4C16.5338 10.0667 16.0745 11.7333 15.156 13.1333C14.2374 14.5333 12.9908 15.6667 11.4817 16.2667C9.97263 16.9333 8.33234 17.1333 6.69205 16.8C5.11737 16.4667 3.6083 15.6667 2.42728 14.5333C1.24627 13.3333 0.458932 11.8667 0.130874 10.2C-0.197185 8.53333 -0.0659617 6.86667 0.590156 5.26667C1.18066 3.73333 2.23045 2.4 3.6083 1.4C4.98614 0.533333 6.56082 0 8.20112 0H8.26673C9.31652 0 10.4319 0.2 11.4161 0.6C12.4003 1 13.3188 1.66667 14.1062 2.4C14.8935 3.2 15.484 4.13333 15.8777 5.13333C16.2714 6.2 16.5338 7.33333 16.5338 8.4Z" fill="#E7001B"/>
                                                    <path d="M13.3849 1.80002C12.4007 2.60002 11.6134 3.60002 11.0229 4.73335C10.498 5.86668 10.1699 7.13335 10.1699 8.40002C10.1699 9.66669 10.4324 10.9334 11.0229 12.0667C11.5478 13.2 12.4007 14.2 13.3849 15C14.3691 14.2 15.1564 13.2 15.7469 12.0667C16.2718 10.9334 16.5999 9.66669 16.5999 8.40002C16.5999 7.13335 16.3374 5.86668 15.7469 4.73335C15.1564 3.60002 14.3691 2.60002 13.3849 1.80002Z" fill="#FC5F01"/>
                                                </svg>
                                                <svg width="40" height="13" viewBox="0 0 40 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.3584 0.333331L10.3063 12.6667H7.02572L4.6637 2.86666C4.59809 2.4 4.33564 2 3.94197 1.8C2.95779 1.33333 1.97362 0.999998 0.923828 0.799998L0.989441 0.466665H6.30399C6.63205 0.466665 6.96011 0.599998 7.22256 0.799998C7.485 0.999998 7.68184 1.33333 7.74745 1.66666L9.05968 8.8L12.2747 0.399998L15.3584 0.333331ZM28.1527 8.73333C28.1527 5.46667 23.8223 5.26666 23.8223 3.8C23.8223 3.33333 24.2816 2.86666 25.1346 2.8C26.1844 2.73333 27.2341 2.86666 28.1527 3.33333L28.6776 0.666665C27.759 0.333331 26.7749 0.133331 25.7251 0.133331C22.6413 0.133331 20.4761 1.86666 20.4761 4.26666C20.4761 6.06666 22.0508 7 23.2318 7.66667C24.4128 8.33333 24.9377 8.66667 24.9377 9.13333C24.9377 9.93333 23.9536 10.3333 23.1006 10.3333C21.9852 10.3333 20.9354 10.0667 19.8856 9.6L19.2951 12.2667C20.4105 12.7333 21.5915 12.9333 22.7725 12.9333C26.0531 12.9333 28.1527 11.2 28.1527 8.73333ZM36.2886 12.7333H39.1099L36.551 0.399998H33.8609C33.5985 0.399998 33.336 0.533331 33.0736 0.666665C32.8111 0.799998 32.6799 1.06666 32.5487 1.33333L27.9559 12.8667H31.2365L31.827 11.0667H35.8293L36.2886 12.7333ZM32.8768 8.4L34.5827 3.86666L35.5012 8.4H32.8768ZM19.7544 0.333331L17.1299 12.6667H14.0462L16.605 0.333331H19.7544Z" fill="#1976D2"/>
                                                </svg>
                                                <svg width="27" height="17" viewBox="0 0 27 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M17.095 1.86667H9.94336V15.0667H17.095V1.86667Z" fill="#7673C0"/>
                                                            <path d="M10.4021 8.53334C10.4021 7.26667 10.6645 6.00001 11.255 4.86667C11.7799 3.73334 12.5672 2.73334 13.5514 1.93334C12.3704 0.933341 10.9269 0.333341 9.35226 0.200008C7.84319 7.7039e-06 6.26852 0.333341 4.89067 1.00001C3.51283 1.66667 2.33181 2.73334 1.54447 4.13334C0.75713 5.46667 0.297852 7.00001 0.297852 8.60001C0.297852 10.2 0.75713 11.7333 1.54447 13.0667C2.33181 14.4 3.51283 15.4667 4.89067 16.2C6.26852 16.8667 7.77758 17.1333 9.35226 17C10.8613 16.8 12.3048 16.2 13.5514 15.2667C12.5672 14.4667 11.7799 13.4667 11.255 12.3333C10.6645 11.0667 10.4021 9.80001 10.4021 8.53334Z" fill="#EB001B"/>
                                                            <path d="M25.9525 13.7334V13.4667H26.0837V13.4H25.8213V13.4667H25.9525V13.7334ZM26.4774 13.7334V13.4H26.4118L26.3462 13.6L26.2806 13.4H26.215V13.7334H26.2806V13.4667L26.3462 13.6667H26.4118L26.4774 13.4667V13.7334Z" fill="#00A1DF"/>
                                                            <path d="M26.7389 8.53335C26.7389 10.1334 26.2797 11.6667 25.4923 13C24.705 14.3334 23.524 15.4 22.1461 16.1334C20.7683 16.8 19.2592 17.0667 17.6845 16.9334C16.1754 16.7334 14.732 16.1334 13.4854 15.2C14.4695 14.4 15.2569 13.4 15.7818 12.2667C16.3067 11.1334 16.6347 9.86669 16.6347 8.60002C16.6347 7.33335 16.3723 6.06669 15.7818 4.93335C15.2569 3.80002 14.4695 2.80002 13.4854 2.00002C14.6664 1.00002 16.1098 0.400022 17.6845 0.266689C19.1936 0.0666886 20.7683 0.400022 22.1461 1.06669C23.524 1.73336 24.705 2.80002 25.4923 4.20002C26.3453 5.40002 26.7389 6.93336 26.7389 8.53335Z" fill="#00A1DF"/>
                                                </svg>
                                                <svg width="42" height="13" viewBox="0 0 42 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M23.8248 1.80002L21.2004 7.60002H20.9379V0.533356H17.2637V12.4667H20.413C20.8067 12.4667 21.2004 12.3334 21.5284 12.1334C21.8565 11.9334 22.1189 11.6 22.3158 11.2L24.9403 5.40002H25.2027V12.4667H28.877V0.533356H25.7276C25.3339 0.533356 24.9403 0.666689 24.6122 0.866689C24.2185 1.13336 23.9561 1.46669 23.8248 1.80002Z" fill="#24A84D"/>
                                                            <path d="M9.98098 2.06667L8.47191 7.53334H8.20947L6.7004 2.06667C6.56918 1.60001 6.30673 1.20001 5.91306 0.933342C5.51939 0.666675 5.12572 0.466675 4.66643 0.466675H0.992188V12.4H4.66643V5.40001H4.92889L7.02846 12.4667H9.65292L11.7525 5.40001H12.0149V12.4667H15.6892V0.533341H12.0149C11.5557 0.533341 11.0964 0.666675 10.7683 0.933342C10.3747 1.26667 10.1122 1.66667 9.98098 2.06667Z" fill="#24A84D"/>
                                                            <path d="M30.4521 6V12.5333H34.1264V8.66667H38.0631C38.8505 8.66667 39.7034 8.4 40.3595 7.93333C41.0156 7.46667 41.5405 6.73333 41.803 6H30.4521Z" fill="#24A84D"/>
                                                            <path d="M38.0628 0.533356H29.8613C30.1238 1.93336 30.8455 3.13336 31.8297 4.00002C32.8795 4.86669 34.1917 5.40002 35.5039 5.40002H41.8683C41.9339 5.13336 41.9339 4.86669 41.9995 4.60002C41.9995 3.53336 41.6058 2.46669 40.8185 1.73336C40.1624 1.00002 39.1126 0.533356 38.0628 0.533356Z" fill="url(#paint0_linear)"/>
                                                            <defs>
                                                            <linearGradient id="paint0_linear" x1="29.8927" y1="2.98689" x2="42.0118" y2="2.98689" gradientUnits="userSpaceOnUse">
                                                            <stop offset="0.01" stop-color="#0FA5E1"/>
                                                            <stop offset="0.35" stop-color="#0C9CDA"/>
                                                            <stop offset="0.91" stop-color="#0483C6"/>
                                                            <stop offset="1" stop-color="#037EC2"/>
                                                            </linearGradient>
                                                            </defs>
                                                </svg>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="w-radio">
                                            <label class="radio">
                                                <input type="radio" name="pay_type" checked  class="pay_type" value="1">
                                                <span></span>
                                                <div class="radio__text">Оплата банковским переводом</div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            {{--<div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">Выберите тип оплаты</label>
                                    <select class="form-control" required id="pay_type" name="pay_type">
                                        <option value="1">Безналичная оплата по счёту</option>
                                        <!--option value="2">Оплата картой (комиссия 5%)</option--->
                                    </select>
                                </div>
                            </div>--}}
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">ФИО посетителя</label>
                                    <input name="fio" class="form-control" required value="{{$user->name??''}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">Email</label>
                                    <input name="email" class="form-control" required type="email" value="{{$user->email??''}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">Контактный телефон</label>
                                    <input name="phone" class="form-control" required type="tel" value="{{$user->phone??''}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">Компания (брендовое название)</label>
                                    <input name="company" class="form-control" value="{{$user->company??''}}">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="form-group">
                                    <label class="redstar">Должность</label>
                                    <input name="doljn" class="form-control" value="{{$user->doljn??''}}">
                                </div>
                            </div>
                        </div>
                        <div id="yur" class="mt-3">
                            <h2>Реквизиты для счёта</h2>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Юр.лицо</label>
                                        <input name="yur[company]" class="form-control" placeholder="Введите название для автозаполнения реквизитов" id="company" required value="{{$req->company??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">ИНН</label>
                                        <input name="yur[inn]" class="form-control" minlength="10" maxlength="12" placeholder="10-12 цифр" id="inn" required value="{{$req->inn??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>КПП</label>
                                        <input name="yur[kpp]" class="form-control noreq" minlength="9" maxlength="9" placeholder="9 цифр (ИП не заполняют)" id="kpp" value="{{$req->kpp??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">ОГРН</label>
                                        <input name="yur[ogrn]" class="form-control" minlength="13" maxlength="15" placeholder="13-15 цифр" id="ogrn" required value="{{$req->ogrn??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Юр. адрес</label>
                                        <div class="input-group">
                                            <input class="form-control addrfield" name="yur[addr]" maxlength="750" id="addr" required value="{{$req->addr??''}}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <a href="javascript:void(0)" title="Скопировать в почтовый адрес" onclick="copy_addr();" style="font-size:14px">
                                                        <i class="fa fa-clipboard"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Почт. адрес</label>
                                        <input name="yur[postaddr]" class="form-control addrfield" id="postaddr" required value="{{$req->postaddr??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Телефон компании</label>
                                        <input name="yur[phone]" class="form-control" id="phone" required type="tel" value="{{$req->phone??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Банк</label>
                                        <input name="yur[bank]" class="form-control" id="bank" required value="{{$req->bank??''}}" placeholder="Введите название для автозаполнения БИК и к/с">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">БИК</label>
                                        <input name="yur[bik]" class="form-control" minlength="9" maxlength="9" placeholder="9 цифр" id="bik" required value="{{$req->bik??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">р/с</label>
                                        <input name="yur[rs]" class="form-control"  minlength="20" maxlength="20" placeholder="20 цифр" id="rs" required value="{{$req->rs??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">к/с</label>
                                        <input name="yur[ks]" class="form-control"  minlength="20" maxlength="20" placeholder="20 цифр" id="ks" required value="{{$req->ks??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Подписант</label>
                                        <input name="yur[gendir]" class="form-control" placeholder="ФИО руководителя организации" id="gendir" required value="{{$req->gendir??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Должность подписанта</label>
                                        <input name="yur[doljn]" class="form-control" placeholder="Например, Генеральный директор" id="doljn" required value="{{$req->doljn??''}}">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">Действует на основании</label>
                                        <input name="yur[osnovanie]" class="form-control" placeholder="Устава/доверенности" id="osnovanie" required value="{{$req->osnovanie??''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="custom-control custom-checkbox mb-3">
                                <input type="checkbox" class="custom-control-input" id="agree" required>
                                <label class="custom-control-label" for="agree">Я согласен с положением об обработке персональных данных
                                (<a class="text-primary" href="/docs/privacy_policy.pdf" target="_blank">Читать</a>)</label>
                                <div class="invalid-feedback">Вы должны согласиться с условиями</div>
                            </div>
                        </div>
                        <hr class="mt-40 mb-4">
                        <div class="ua-week-sostav__itogo mb-5">Итого <span id="totalsum1">0</span> <i class="fas fa-ruble-sign"></i></div>
                        <div class="ua-order-form__btn mt-4">
                            <button class="button button-gray-line-gray" type="button" id="return-btn">Вернуться назад</button>
                            <button class="button button-pink-white" id="buybtn">Купить сейчас</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="ua-week-modal modal1">
        <div class="ua-week-modal__in ua-week-modal__in_970">
            <div class="ua-week-modal__info">
                <div class="ua-week-modal__close"></div>
                <div class="ua-week-modal__title">Проперти тур</div>
                <div class="ua-week-modal__date">22-23 июня</div>
                <div class="ua-week-modal__text">
                    <p>Тур по жилым комплексам и знаковым общественным пространствам Санкт-Петербурга.</p>

                    <p>Аналогия, конечно, подчеркивает гравитационный парадокс, tertium nоn datur. Представляется
                        логичным, что любовь трансформирует напряженный дуализм, однако Зигварт считал критерием
                        истинности необходимость и общезначимость, для которых нет никакой опоры в объективном мире.
                        Позитивизм амбивалентно творит дуализм, ломая рамки привычных представлений. Реальность, как
                        принято считать, вырождена.</p>

                    <p>Гений порождает и обеспечивает сенсибельный даосизм, хотя в официозе принято обратное.
                        Позитивизм, конечно, рефлектирует гений, хотя в официозе принято обратное. Позитивизм
                        дискредитирует непредвиденный знак, при этом буквы А, В, I, О символизируют соответственно
                        общеутвердительное, общеотрицательное, частноутвердительное и частноотрицательное суждения.
                        Интеллект выводит здравый смысл, открывая новые горизонты. Гедонизм рефлектирует данный
                        гедонизм, при этом буквы А, В, I, О символизируют соответственно общеутвердительное,
                        общеотрицательное, частноутвердительное и частноотрицательное суждения. Врожденная интуиция,
                        как принято считать, контролирует непредвиденный закон исключённого третьего, открывая новые
                        горизонты.</p>

                    <p>Наряду с этим отношение к современности подчеркивает трансцендентальный закон внешнего мира,
                        учитывая опасность, которую представляли собой писания Дюринга для не окрепшего еще
                        немецкого рабочего движения. Априори, исчисление предикатов трансформирует мир, tertium nоn
                        datur. Боль нетривиальна. Искусство дискредитирует из ряда вон выходящий закон исключённого
                        третьего, при этом буквы А, В, I, О символизируют соответственно общеутвердительное,
                        общеотрицательное, частноутвердительное и частноотрицательное суждения.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="ua-week-modal modal2">
        <div class="ua-week-modal__in ua-week-modal__in_970">
            <div class="ua-week-modal__info">
                <div class="ua-week-modal__close"></div>
                <div class="ua-week-modal__title">Welcome-коктейль</div>
                <div class="ua-week-modal__date">22 июня в 19:00</div>
                <div class="ua-week-modal__text">
                    <p>Первый день программы Неделя с Премией Urban: начнем в неформальной обстановке с игристым и знакомством с коллегами из разных регионов России.
В программе: изысканный фуршет, шампанское, вино, коктейли, музыка.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="ua-week-modal modal3">
        <div class="ua-week-modal__in ua-week-modal__in_970">
            <div class="ua-week-modal__info">
                <div class="ua-week-modal__close"></div>
                <div class="ua-week-modal__title">Тур по знаковым общественным пространствам Санкт-Петербурга</div>
                <div class="ua-week-modal__date">24 июня</div>
                <div class="ua-week-modal__text">
                    <p>24 июня для участников 3-х дневного форума по градостроительству и недвижимости Urban Week
                     в преддверии церемонии награждения премии состоится тур по знаковым общественным пространствам
                     Санкт-Петербурга. Региональные застройщики смогут не только вместе вкусно позавтракать в популярном
                      среди петербуржцев месте, но и познакомиться с опытом Северной столицы в создании общественных пространств.</p>
                      <p>
                      Для посещения был выбран новый современный фудмаркет – «Василеостровский рынок», обозначающий себя как
                      «главная гастрономическая точка Петербурга». «Василеостровский рынок» - современный рынок с ресторанной зоной,
                      представленной гастрономическими корнерами. Выглядит это актуально и гармонично, а посетить это место интересно и вкусно!
                       С точки зрения истории интересно отметить, что на этом месте торговля велась начиная с первой половины XVIII века.
                       Гастрономическое пространство занимает два корпуса, которые в 1959 году были возведены под нужды колхозного рынка.
                       В первом корпусе располагается фуд-корт, а во втором рыночная торговля. Сводчатые потолки и гранитный пол, мраморные плиты на стенах.
                        Сейчас про это место можно смело сказать, что это модное и популярное пространство в Санкт-Петербурге.
                      </p>
                      <p>
                      Второе место было выбрано с учетом интересной истории его создания - Севкабель Порт. Находится на исторической части завода «Севкабель»
                      вблизи Финского залива, является результатом реконструкции первого завода в России. Различные мероприятия, спектакли, концерты, проходят
                      на самой популярной площадке, на которой изначально хранились барабаны с кабелем. Важное место отводится в проекте и гастрономической составляющей
                       – на его территории организован фуд-корт, проводятся фуд-маркеты. Эти места интересно посетить и отметить, как современно организованный фудкорт,
                       которые стали самыми популярными и модными общественными и гастрономическими пространствами в Северной столице.
                      </p>
                      <p>
                      Urban Week — масштабное недельное мероприятие в период Белых ночей в Санкт-Петербурге для лидеров рынка недвижимости. Полное погружение: лучшие
                      кейсы и практика девелопмента, стратегическая сессия, проперти-тур, неформальное общение и новые контакты. Максимальная польза в одном из самых
                      красивых городов России.
                      </p>
                </div>
            </div>
        </div>
    </div>
    <div class="ua-week-modal modal4">
        <div class="ua-week-modal__in ua-week-modal__in_970">
            <div class="ua-week-modal__info">
                <div class="ua-week-modal__close"></div>
                <div class="ua-week-modal__title">Премия Urban</div>
                <div class="ua-week-modal__date">24 июня</div>
                <div class="ua-week-modal__text">
                    <p>Церемония награждения Федеральной Премии Urban - ключевая часть программы Urban Week!
                      </p>
                </div>
            </div>
        </div>
    </div>
    <div class="ua-week-modal modal5">
        <div class="ua-week-modal__in ua-week-modal__in_970">
            <div class="ua-week-modal__info">
                <div class="ua-week-modal__close"></div>
                <div class="ua-week-modal__title">Деловое мероприятие Urban Space</div>
                <div class="ua-week-modal__date">10.00-10.30 Сбор гостей и welcome-кофе<br>10.30-12.30 Стратегическая сессия</div>
                <div class="ua-week-modal__text">
                    <p>
                    <b>Ключевые вопросы дискуссии:</b>
- Стратегии развития и региональной экспансии от лидеров отрасли. Инвестиционная привлекательность регионов для жилищного строительства.
- Ключевые риски отрасли жилищного строительства и подходы компаний к их преодолению
- Посткризисные стратегии городского развития. Чему пандемия и карантин научили города и какие форматы и сегменты недвижимости показали наибольшую устойчивость?
- Среда для инноваций. Как меняются компании и подход к девелоперскому продукту сегодня с прицелом на будущее?
                    </p>
                    <p>
                    Формат: экспертная дискуссия первых лиц и топ-менеджеров компаний.
                    </p>
                    <p>
                    Приглашены:
Никита Стасишин, Заместитель Министра, МИНСТРОЙ РФ
Александр Брега, Генеральный директор корпорации «Мегалит»
Геннадий Щербина, Президент Группа «Эталон»
Александр Рогатых, Президент Группа «Аквилон»
Хелпполайнен Теему Тапани, Генеральный директор АО «ЮИТ Санкт-Петербург»/«Жилищное строительство, Россия» концерна ЮИТ
Евгений Дячкин, Заместитель руководителя департамента розничного бизнеса – вице-президент Банка ВТБ (ПАО) г. Москва
Константин Бачкин, Директор управления финансирования недвижимости
"Северо-Западный банк ПАО "Сбербанк"
Валерия Малышева, Генеральный директор "Ленстройтрест"
Андрей Скосырский, Коммерческий директор "ЭНКО ГРУПП"
Андрианов Александр, Первый вице-президент Glorax
Артем Божедомов, Вице-президент, руководитель блока девелопмент "Страна Девелопмент"
Юлия Шевченко, Соучредитель ГК "Новый мир"
Делюс Сиразетдинов, Генеральный директор ООО «КамаСтройИнвест»
Глеб Витков, декан факультета городского и регионального развития НИУ ВШЭ
Ольга Нарт, Вице-президент по коммерции АВА-Сочи
Антон Финогенов, Заместитель генерального директора Фонд Дом.рф
Тимур Абдуллаев, Основатель Архитектурного бюро ARCHINFORM
Эдуард Тиктинский, Президент Группа RBI
Александр Шарапов, Президент Becar Asset Management
Всеволод Иванов, Директор «УралДомСтрой»
Юрий Захаров, генеральный директор Девелоперская компания «Железно»
                    </p>
                    <p>
                    12.30-13.20 Обед
13.20-16.00 Практика девелопмента. Актуальные тренды архитектуры и градостроительства при реализации проектов жилой недвижимости.
                    </p>
                    <ul>
                    <li>
                    Подписание соглашения по вопросам сотрудничества в области применения инновационных подходов в недвижимости между двумя крупнейшими IT компаниями.
                    </li>
                    <li>
                    Практическая часть будет состоять из нескольких успешных кейсов из практики девелопмента и дальнейшего их обсуждения, обмена опытом экспертами.
                    В рамках кейсов будут рассмотрены вопросы, актуальные для формирования качественного и конкурентоспособного продукта в условиях меняющегося
                    рынка и модели потребления.
                    </li>
                    </ul>
                    <p>
                    С кейсами выступят:
                    </p>
                    <p>
                    ГК ФСК (Москва)
Пётр Кирилловский, директор департамента развития продукта
                    </p>
                    <p>
                    Архитектурная студия «ИНТЕРКОЛУМНИУМ», Евгений Подгорнов, директор и главный архитектор, профессор Международной Академии Архитектуры,
                    член Союза Архитекторов России.
ГК «Еврострой» (Санкт-Петербург), Тенгиз Адамия, руководитель управления продаж
                    </p>
                    <p>«Атлас Девелопмент» (Екатеринбург)
Концепция жилых экосистем, преимущества такого подхода в проектировании, управлении и создании добавочной стоимости, перспективы развития формата и особенности его реализации.
Анастасия Стройкова, коммерческий директор и Мария Дружинина руководитель отдела рекламы и маркетинга
</p>
<p>*В программе могут быть изменения</p>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" id="thanks-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Сообщение</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="msg_text">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="/libs/bootstrap-input-spinner.js"></script>
<script src="/libs/fotorama/fotorama.js"></script>
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
<script src="/libs/icheck/icheck.min.js"></script>
<script src="/js/urbanweek.js?{{time()}}"></script>
<script>
    $(document).ready(function(){
        $(".addrfield").suggestions({
            token: "{{config('app.dadata_key')}}",
            type: "ADDRESS",
            onSelect: function(suggestion) {

            }
        });
        $("#bank").suggestions({
            token: "{{config('app.dadata_key')}}",
            type: "BANK",
            onSelect: function(suggestion) {
                $('#bik').val(suggestion.data.bic);
                $('#ks').val(suggestion.data.correspondent_account);
            }
        });
        $("#company").suggestions({
            token: "{{config('app.dadata_key')}}",
            type: "PARTY",
            onSelect: function(suggestion) {
                $('#inn').val(suggestion.data.inn);
                $('#ogrn').val(suggestion.data.ogrn);
                $('#addr').val(suggestion.data.address.unrestricted_value);
                if(suggestion.data.kpp){
                    $('#kpp').val(suggestion.data.kpp);
                } else{
                    $('#kpp').val('');
                }
                if(suggestion.data.management){
                    $('#gendir').val(suggestion.data.management.name);
                    $('#doljn').val(suggestion.data.management.post);
                }
                else{
                    $('#gendir').val('');
                    $('#doljn').val('');
                }
                if(suggestion.data.inn.length==12){
                    $('#osnovanie').val('-');//Для ИП не указываем основание
                    $('#doljn').val('ИП');
                    $('#gendir').val(suggestion.data.name.full);
                }
                $.ajax({
                    url:'/api/req-suggestions',
                    dataType:'json',
                    type:'get',
                    data:{
                        inn:suggestion.data.inn,
                        ogrn:suggestion.data.ogrn
                    },
                    success:function(resp){
                        if(typeof resp.error !=='undefined' && resp.error!=null)return false;
                        $('#rs').val(resp.rs);
                        $('#ks').val(resp.ks);
                        $('#bank').val(resp.bank);
                        $('#bik').val(resp.bik);
                        $('#phone').val(resp.phone);
                    }
                })
            }
        });
    });
</script>
<script>
    $(window).on('resize',function(){
        var width;
        var windowwidth=$(window).width();
        var contwidth=$('.container').width();
        if (contwidth<windowwidth){
            width=contwidth;
        }
        else{
            width=windowwidth-60;
        }
        var ratio=width/{{$award->scene_width??575}};
        if (ratio>1.5)ratio=1.5;
        $('.svgwrapper').css('zoom',ratio);
        $('.svgwrapper').css('-moz-transform','scale('+ratio+')');
        if(navigator.userAgent.toLowerCase().indexOf('firefox')>-1){
            $('.svgkostyl').css('height',{{$award->scene_height??300}}*ratio+'px');
        }
    });
    $(window).on('load',function () {
        $(window).trigger('resize');
    });
</script>
@if(!empty($selected) && is_numeric($selected))
<script>
    $(document).ready(function(){
        $('.js-package-btn[data-id="{{$selected}}"]').trigger('click');
    });
</script>
@endif
@endsection
@section('styles')
<link rel="stylesheet" href="/libs/icheck/skins/all.css">
<link rel="stylesheet" href="/css/week.css?{{time()}}">
<link rel="stylesheet" href="/libs/fotorama/fotorama.css">
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/css/svg.css?{{time()}}">
<style>
    .svgscene{
        background-image: url('{{$scene}}');
        background-size: {{$award->scene_width??575}}px {{$award->scene_height??300}}px;
    }
    .svgwrapper{
        zoom: {{870/($award->scene_width??575)}};
        width: {{$award->scene_width??575}}px;
        height: {{($award->scene_height??300)+40}}px;
    }
    .svgstoly svg{
        width: {{$award->scene_width??575}}px;
        height: {{$award->scene_height??300}}px;
    }
    .svglegend {
        top: {{($award->scene_height??300)+1}}px;
    }
    .svglegend svg {
        width: {{$award->scene_width??575}}px;
    }

    #zakaz-form .form-control{
        font-size: 14px;
    }
    .icheck-label{
        user-select:none;
    }
</style>
@endsection
