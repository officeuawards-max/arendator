@extends('layouts.new.ajax')
@section('content')

<div id="tickets_payment_form">
    @php
        $total_sum = $type->price * $count;
        $type_discount = $type->price_discounts{0}->percent ?? 0;
        $total_sum = $total_sum - $total_sum*($type_discount/100);

        $payment_type_checked[1] = "checked";
        $payment_type_checked[2] = "";

        if (isset($old_data['pay_type']) && $old_data['pay_type'] == 2) {
            $payment_type_checked[1] = "";
            $payment_type_checked[2] = "checked";
        }

    @endphp

        {{--
        @if (session('error'))
            {{ session('error') }}
        @endif

        @if($errors->any())
        {!! implode('<br />', $errors->all(':message')) !!}
        @endif
        --}}

                        <div class="n-popup__top">
                            <div class="n-popup__title">
                                {{ $type->name }}
                                @if(!is_null($type->summit_id) && isset($type->summit))
                                    {{ $type->summit->name }}
                                    {{--
                                    <div class="n-popup__date">{{ \App\Libs\Strings::monthToRus($type->summit->date->format('d M Y')) }}, {{ $type->summit->place }}</div>
                                    --}}
                                @elseif(!is_null($type->our_event_id) && isset($type->our_event))
                                    {{--
                                    <div class="n-popup__date">{{ \App\Libs\Strings::monthToRus($type->our_event->start->format('d M Y')) }}, {{ $type->our_event->location }}</div>
                                    --}}
                                @elseif(is_null($type->our_event_id) && isset($type->award))
                                    {{ $type->award->name }}
                                    <div class="n-popup__date">
                                    {{ \App\Libs\Strings::monthToRus($type->award->date->format('j M Y')) }} <br />
                                    {{ $type->award->place ?? '' }} <br />
                                    {{ $type->award->addr ?? '' }}
                                    </div>
                                @endif
                            </div>

                            @if(!empty($table))
                                <div class="n-popup__table">Стол: {{ $table->name ?? '' }}</div>
                            @endif

                            @if(isset($type->options) && count($type->options) > 0)
                                <div class="n-popup__section-info">
                                    <div class="ticket-item__info">
                                        <div class="n-popup__section-title">Что входит:</div>
                                        <ul class="ticket-item__info-list">
                                            @foreach($type->options as $option)
                                                @if(!empty($option->name))
                                                    <li class="ticket-item__info-item">{{ $option->name }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if(count($type->price_dynamics) > 0)
                                <div class="n-popup__section-info">
                                    <div class="ticket-item__price-up">
                                        <div class="n-popup__section-title">Повышение цен:</div>
                                        <div class="ticket-item__price-up-list">
                                            @foreach($type->price_dynamics as $next_price)
                                                <div class="ticket-item__price-up-item">
                                                    с {{ Carbon\Carbon::parse($next_price->from)->translatedFormat('d F') }} <svg width="20" height="11" viewBox="0 0 20 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M19 0.5L11.2273 8.41667L7.13636 4.25L1 10.5M19 0.5H14.0909M19 0.5V5.5" stroke="#0015C8" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    {{ number_format($next_price->price, 0, ',', ' ') }} ₽
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="n-popup__section-info">
                                <div class="n-popup__qt-wrapper">
                                    Количество билетов
                                    <div class="ticket-type-kolvo">
                                        <input class="ticket-type-kolvo-input" type="number" min="1" max="10" value="1" data-id="{{ $type->id }}" data-price="{{ $type->price }}" data-avail="{{ $type->total_quantity ?? '-' }}">
                                    </div>
                                    <div class="n-popup__summ">
                                        @if($type->is_prePriced)
                                            стоимость по запросу
                                        @else
                                            &mdash; <span class="n-popup__summ-value" data-summ-value>{{ number_format($total_sum, 0, ',', ' ') }}</span> ₽
                                        @endif
                                        {{--
                                        @if($type->old_price > 0 )
                                            <s class="text-muted fw-normal">{{ number_format($type->old_price * $count, 0, ',', ' ') }} ₽</s>
                                        @endif
                                        --}}
                                    </div>
                                </div>

                            </div>
                        </div>



            <div class="mt-4">
                <form class="pay-form" autocomplete="off" method="post" action="/tickets/buy" id="tickets_form_payment">
                    @csrf
                    <input name="award_id" value="{{ $award->id }}" type="hidden">
                    <input name="type_id" value="{{ $type->id }}" type="hidden">
                    <input name="amount" value="{{ $count }}" type="hidden" id="kolvo">



                    <div class="tickets-tabs tickets-tabs__two tickets-tabs__pay">
                        <label class="tickets-tabs__item">
                            <input type="radio" class="tickets-tabs__input" name="pay_type" value="1" {{ $payment_type_checked[1] }}/>
                            <div class="tickets-tabs__info">
                                <div class="tickets-tabs__title">Безналичная оплата по счету</div>
                            </div>
                        </label>
                        @if($total_sum < 500000 && $type->is_prePriced != 1)
                        <label class="tickets-tabs__item">
                            <input type="radio" class="tickets-tabs__input" name="pay_type" value="2" {{ $payment_type_checked[2] }}/>
                            <div class="tickets-tabs__info">
                                <div class="tickets-tabs__title">Банковской картой</div>
                            </div>
                        </label>
                        @endif
                    </div>

                    <div id="maindiv" class="main-form @if($errors->any()) main-form-with_errs @endif">

                        {{-- <div class="form-group">
                            <label class="redstar">Выберите тип оплаты</label>
                            <select class="form-control @error('pay_type') input_form_err @enderror" required id="pay_type" name="pay_type2">
                                <option value="1" @if(old('pay_type') == 1) selected @endif>Безналичная оплата по счёту</option>
                                <option value="2" id="card_method" @if($type->price * $count * 1.05 >= 600000) disabled @elseif(old('pay_type') == 2 || $pay_type == 2) selected @endif>Оплата картой РФ (+5%)</option>
                            </select>
                            @if($errors->has('pay_type'))
                                <div class="field_errors">{{ $errors->first('pay_type') }}</div>
                    @endif
            </div> --}}
                        <div  id="tickets-box" class="tickets-box"></div>
                        @if($errors->any())
                            @php
                            //dump($errors->first());
                            @endphp
                            <div class="field_errors">
                                @if($errors->has('pay_type'))
                                    Не выбран способ оплаты. Пожалуйста выберите способ оплаты выше <br />
                                @endif
                                Были допущены ошибки при заполнении. Пожалуйста проверьте анкету
                            </div>

                            <p>&nbsp</p>
                        @endif


                        @if($type->use_promo == 1)
                        <div class="row">

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="">Промокод</label>
                                    <div class="input-groupd">
                                        <input class="form-control @if($errors->has('promocode')) input_form_err @endif" name="promocode" maxlength="750" id="promocode" value="{{ $old_data['promocode'] ?? '' }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <a href="javascript:void(0)" onclick="checkpromocode();" style="font-size:14px; color: #fff;" id="checkpromo">
                                                    Применить
                                                </a>
                                            </span>
                                        </div>
                                    </div>
                                    @if($errors->has('promocode'))
                                        <div class="field_errors" id="promocode_errors">{{ str_replace('promocode', '', $errors->first('promocode')) }}</div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        @endif

                        <div class="row">
                            {{-- антиспам для жадных ботов --}}
                            <input name="name" class="form-control antishow_this" />
                            <input name="city" class="form-control antishow_this" />
                            <input name="state" class="form-control antishow_this" />
                            {{-- антиспам для жадных ботов --}}

                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="redstar">ФИО посетителя</label>
                                    <input name="fio" required class="form-control @if($errors->has('fio')) input_form_err @endif" value="{{ $old_data['fio'] ?? $user->name ?? '' }}" />
                                    @if($errors->has('fio'))
                                        <div class="field_errors">{{ str_replace('fio', '', $errors->first('fio')) }}</div>
                                    @endif

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="redstar">Контактный телефон</label>
                                    <input name="phone" required class="form-control @if($errors->has('phone')) input_form_err @endif" type="tel" value="{{ $old_data['phone'] ?? $user->phone ??  '' }}" />
                                    @if($errors->has('phone'))
                                        <div class="field_errors">{{ str_replace('phone', '', $errors->first('phone')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="redstar">Email</label>
                                    <input name="email" required class="form-control @if($errors->has('email')) input_form_err @endif" type="email" value="{{ $old_data['email'] ?? $user->email ?? '' }}" />
                                    @if($errors->has('email'))
                                        <div class="field_errors">{{ str_replace('email', '', $errors->first('email')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label>Компания (брендовое название)</label>
                                    <input name="company" class="form-control @if($errors->has('company')) input_form_err @endif" value="{{ $old_data['company'] ?? $user->company ?? '' }}" />
                                    @if($errors->has('company'))
                                        <div class="field_errors">{{ str_replace('company', '', $errors->first('company')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="">Должность</label>
                                    <input name="doljn" class="form-control @if($errors->has('doljn')) input_form_err @endif" value="{{ $old_data['doljn'] ?? $user->doljn ?? '' }}" />
                                    @if($errors->has('doljn'))
                                        <div class="field_errors">{{ str_replace('doljn', '', $errors->first('doljn')) }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div id="yur" class="yur-recs">

                            <div class="pay-form__title">Реквизиты для счёта</div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Юр.лицо</label>
                                        <input name="yur[company]" class="form-control @if($errors->has('yur.company')) input_form_err @endif" placeholder="Введите название для автозаполнения реквизитов" id="company" value="{{ $old_data['yur']['company'] ?? $req->company ?? '' }}">
                                        @if($errors->has('yur.company'))
                                            <div class="field_errors">{{ str_replace('yur.company', '', $errors->first('yur.company')) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Телефон компании</label>
                                        <input name="yur[phone]" class="form-control @if($errors->has('yur.phone')) input_form_err @endif" id="phone" type="tel" value="{{ $old_data['yur']['phone'] ?? $req->phone ?? '' }}">
                                        @if($errors->has('yur.phone'))
                                            <div class="field_errors">{{ str_replace('yur.phone', '', $errors->first('yur.phone')) }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="row">

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">ИНН</label>
                                        <input name="yur[inn]" class="form-control @if($errors->has('yur.inn')) input_form_err @endif" minlength="10" maxlength="12" placeholder="10-12 цифр" id="inn" value="{{ $old_data['yur']['inn'] ?? $req->inn ?? '' }}">
                                        @if($errors->has('yur.inn'))
                                            <div class="field_errors">{{ str_replace('yur.inn', '', $errors->first('yur.inn')) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label>КПП</label>
                                        <input name="yur[kpp]" class="form-control @if($errors->has('yur.kpp')) input_form_err @endif noreq" minlength="9" maxlength="9" placeholder="9 цифр (ИП не заполняют)" id="kpp" value="{{ $old_data['yur']['kpp'] ?? $req->kpp ?? '' }}">
                                        @if($errors->has('yur.kpp'))
                                            <div class="field_errors">{{ str_replace('yur.kpp', '', $errors->first('yur.kpp')) }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="redstar">ОГРН</label>
                                        <input name="yur[ogrn]" class="form-control @if($errors->has('yur.ogrn')) input_form_err @endif" minlength="13" maxlength="15" placeholder="13-15 цифр" id="ogrn" value="{{ $old_data['yur']['ogrn'] ?? $req->ogrn ?? '' }}">
                                        @if($errors->has('yur.ogrn'))
                                            <div class="field_errors">{{ str_replace('yur.ogrn', '', $errors->first('yur.ogrn')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Юр. адрес</label>
                                        <div class="input-groupd">
                                            <input class="form-control @if($errors->has('yur.addr')) input_form_err @endif addrfield" name="yur[addr]" maxlength="750" id="addr" value="{{ $old_data['yur']['addr'] ?? $req->addr ?? '' }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <a href="javascript:void(0)" title="Скопировать в почтовый адрес" onclick="copy_addr();" style="font-size:14px">
                                                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M20.6665 9.5H11.6665C10.5619 9.5 9.6665 10.3954 9.6665 11.5V20.5C9.6665 21.6046 10.5619 22.5 11.6665 22.5H20.6665C21.7711 22.5 22.6665 21.6046 22.6665 20.5V11.5C22.6665 10.3954 21.7711 9.5 20.6665 9.5Z" stroke="#171717" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M5.6665 15.5H4.6665C4.13607 15.5 3.62736 15.2893 3.25229 14.9142C2.87722 14.5391 2.6665 14.0304 2.6665 13.5V4.5C2.6665 3.96957 2.87722 3.46086 3.25229 3.08579C3.62736 2.71071 4.13607 2.5 4.6665 2.5H13.6665C14.1969 2.5 14.7056 2.71071 15.0807 3.08579C15.4558 3.46086 15.6665 3.96957 15.6665 4.5V5.5" stroke="#171717" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>

                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                        @if($errors->has('yur.addr'))
                                            <div class="field_errors">{{ str_replace('yur.addr', '', $errors->first('yur.addr')) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Почт. адрес</label>
                                        <input name="yur[postaddr]" class="form-control @if($errors->has('yur.postaddr')) input_form_err @endif addrfield" id="postaddr" value="{{ $old_data['yur']['postaddr'] ?? $req->postaddr ?? '' }}">
                                        @if($errors->has('yur.postaddr'))
                                            <div class="field_errors">{{ str_replace('yur.postaddr', '', $errors->first('yur.postaddr')) }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label class="redstar">Подписант</label>
                                        <input name="yur[gendir]" class="form-control @if($errors->has('yur.gendir')) input_form_err @endif" placeholder="ФИО руководителя организации" id="gendir" value="{{ $old_data['yur']['gendir'] ?? $req->gendir ?? '' }}">
                                        @if($errors->has('yur.gendir'))
                                            <div class="field_errors">{{ str_replace('yur.gendir', '', $errors->first('yur.gendir')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">


                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Должность подписанта</label>
                                        <input name="yur[doljn]" class="form-control @if($errors->has('yur.doljn')) input_form_err @endif" placeholder="Например, Генеральный директор" id="doljn" value="{{ $old_data['yur']['doljn'] ?? $req->doljn ?? '' }}">
                                        @if($errors->has('yur.doljn'))
                                            <div class="field_errors">{{ str_replace('yur.doljn', '', $errors->first('yur.doljn')) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Действует на основании</label>
                                        <input name="yur[osnovanie]" class="form-control @if($errors->has('yur.osnovanie')) input_form_err @endif" placeholder="Устава/доверенности" id="osnovanie" value="{{ $old_data['yur']['osnovanie'] ?? $req->osnovanie ?? '' }}">
                                        @if($errors->has('yur.osnovanie'))
                                            <div class="field_errors">{{ str_replace('yur.osnovanie', '', $errors->first('yur.osnovanie')) }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">Банк</label>
                                        <input name="yur[bank]" class="form-control @if($errors->has('yur.bank')) input_form_err @endif" id="bank" value="{{ $old_data['yur']['bank'] ?? $req->bank ?? '' }}" placeholder="Введите название для автозаполнения БИК и к/с">
                                        @if($errors->has('yur.bank'))
                                            <div class="field_errors">{{ str_replace('yur.bank', '', $errors->first('yur.bank')) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">р/с</label>
                                        <input name="yur[rs]" class="form-control @if($errors->has('yur.rs')) input_form_err @endif" minlength="20" maxlength="20" placeholder="20 цифр" id="rs" value="{{ $old_data['yur']['rs'] ?? $req->rs ?? '' }}">
                                        @if($errors->has('yur.rs'))
                                            <div class="field_errors">{{ str_replace('yur.rs', '', $errors->first('yur.rs')) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">БИК</label>
                                        <input name="yur[bik]" class="form-control @if($errors->has('yur.bik')) input_form_err @endif" minlength="9" maxlength="9" placeholder="9 цифр" id="bik" value="{{ $old_data['yur']['bik'] ?? $req->bik ?? '' }}">
                                        @if($errors->has('yur.bik'))
                                            <div class="field_errors">{{ str_replace('yur.bik', '', $errors->first('yur.bik')) }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="redstar">к/с</label>
                                        <input name="yur[ks]" class="form-control @if($errors->has('yur.ks')) input_form_err @endif" minlength="20" maxlength="20" placeholder="20 цифр" id="ks" value="{{ $old_data['yur']['ks'] ?? $req->ks ?? '' }}">
                                        @if($errors->has('yur.ks'))
                                            <div class="field_errors">{{ str_replace('yur.ks', '', $errors->first('yur.ks')) }}</div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{--
                        <label class="checkbox-text mail-space">
                            <input type="checkbox" class="checkbox-text__input @error('urbanspace') input_form_err @enderror nojs" name="urbanspace" value="1" id="urbanspace">
                            <i class="checkbox-text__pseudo-input checkbox-text__pseudo-input-radio"></i>
                            <span class="checkbox-text__text">Хотите получить информацию об участии в форуме Urban Space?</span>
                        </label>
                        --}}

                        <div class="row">
                            <div class="col-12 col-md-12">
                                <div class="form-group">
                                    <label class="checkbox-text checkbox-text__agree">
                                        <input type="checkbox" class="checkbox-text__input @if($errors->has('pol_agree')) input_form_err @endif" name="pol_agree" id="agree">
                                        <i class="checkbox-text__pseudo-input checkbox-text__pseudo-input-radio"></i>
                                        <span class="checkbox-text__text">Я согласен с положением об <a class="underline" href="/docs/privacy_policy.pdf" target="_blank">обработке персональных данных</a>.</span>
                                    </label>
                                    <div class="invalid-feedback">Вы должны согласиться с условиями</div>
                                    @if($errors->has('pol_agree'))
                                        <div class="field_errors">Вы должны согласиться с условиями</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- <div class="biletiItogo price-total">
                            Итоговая стоимость: <span class="purple price-total__value" id="price">{{number_format($type->price * $count, 2, ',', ' ')}} ₽</span>
                        </div> -->
                        <button class="buy-now-btn ticket-button--purple ticket-button" id="buybtn">Пойти на церемонию</button>
                        <input type="hidden" id="current_price" value="{{$type->price}}">
                        <input type="hidden" id="promo_discount" value="0">
                        <input type="hidden" id="price2" value="{{$type->price * $count}}">
                        <input type="hidden" name="table_id" id="table_id" value="{{$table_id}}">
                    </div>
                </form>
            </div>
        </div>
</div>

{{--
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
--}}

@endsection
@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/css/suggestions.min.css" rel="stylesheet" />
    <style>
        .biletiItogo {
            padding-bottom: 30px;
            font-size: 18px;
            font-weight: bold;
        }

        .red {
            color: #ea1831;
        }

        hr {
            border-top-style: dashed;
        }

        p a {
            color: #2c9dd7;
        }

        .checkbox-text__text {
            width: 100%;
        }

        .n-popup__summ {
            font-size: 20px;
            margin-top: 20px;
        }
        .n-popup__table {
            font-size: 20px;
        margin-top: 20px;
        font-weight: 600;
        }

        .form-control {
            font-size: 14px;
            height: 50px;
            border-radius: 0;
        }

        .form-group>label {
            font-weight: 400;
            color: #010101;
            font-size: 18px;
        }

        .no-p-margin,
        .no-p-margin p {
            margin-bottom: 0;
        }

        .antishow_this {
            display: none;
        }
        .field_errors {
            font-size: 14px;
            color: red;
        }
        .pay-form input.input_form_err {
            border: 1px solid red;
        }
        .input-groupd {
            display: flex;
        }
        .yur-recs {
            margin: 40px 0 20px;
        }
        .pay-form {
            margin-right: 10%;
        }
        .pay-form__title {
            font-size: 22px;
            margin-bottom: 20px;
        }
        .checkbox-text__agree .checkbox-text__text {
         font-size: 14px;
        }
        .checkbox-text__agree {
            display: flex;
        }
        .checkbox-text__agree a {
            display:inline;
            color: #010101;
            text-decoration: underline !important;
        }
        .tickets-tabs__item {
            display:flex;
            margin-right: 30px;
            align-items: center;
        }

        .tickets-tabs__info {
            margin-left: 12px;
        }
        .tickets-tabs {
            display: flex;
            margin-bottom: 50px;
            flex-wrap: wrap;
        }
        .input-group-text {
            background: #0015C8;
            border-radius: 0;
            margin-left: 2px;
            border:none;
        }
        .input-group-text:hover {
            background: #010101;
        }
        .input-group-text svg path {
            stroke: #fff;
        }
        .buy-now-btn {
            background: #0015C8;
            padding: 20px 40px;
            border: none;
            color: #fff;
            font-size: 18px;
            margin-top:30px;
        }
        .buy-now-btn:hover {
            background: #010101;
        }
        @media(max-width: 700px) {
            .pay-form {
                margin-right: 0;
                }
        }
    </style>
@endsection
@section('scripts')
    <script src="/templates/spb2020/srcjs/vendors/swiper.min.js"></script>
    <script src="/libs/bootstrap-input-spinner.js"></script>
    <script>
        function copy_addr() {
            $('#postaddr').val($('#addr').val());
        }

        function checkpromocode() {
            var price = $('#current_price').val();
            var cur_quantity = $('.ticket-type-kolvo-input').val();

            $.ajax({
                url: "{{route('check-promocode')}}",
                data: {
                    promocode: $("#promocode").val(),
                    price: price,
                    amount: $('#kolvo').val(),
                    cat_id: '{{$type->cat_id}}',
                    type_id: '{{$type->id}}',
                },
                method: 'post',
                success: function({
                                      price,
                                      sale,
                                      min
                                  }) {
                    var new_sum = cur_quantity*price;

                    $("#promo_discount").val(sale || 0);
                    $("#checkpromo").text('Скидка ' + sale + '%').prop('disabled', true);
                    $("#promocode").prop('readonly', true);
                    $('#kolvo').attr('min', min);
                    $('.ticket-type-kolvo-input').attr('data-price', price);
                    $('.n-popup__summ-value').empty().html(new_sum);

                    calcprice();
                },
                error: function(e) {
                    $("#promo_discount").val(0);
                    $("#checkpromo").text('Применить').prop('disabled', false);
                    $("#promocode").prop('readonly', false);
                    if (e.status == 404) {
                        $('#promocode_errors').text('Данный промокод недействителен или не выполняется условие по минимальному числу билетов!');
                    } else if (e.status == 429) {
                        $('#promocode_errors').text('Слишком много попыток подбора! Попробуйте через несколько минут');
                    } else if (e.status >= 500) {
                        $('#promocode_errors').text('Сайт недоступен!');
                    }
                    $('#promo-modal').modal();
                }
            });
        }

        $(document).ready(function() {

            $("input[type='number']").inputSpinner();

            $('.ticket-type-kolvo-input').on('change', function(e) {
                $('#kolvo').val(this.value)
                const price = this.getAttribute('data-price')
                const summ = new Intl.NumberFormat('ru-RU').format(+price*this.value)
                console.log($('[data-summ-value]'))
               $('[data-summ-value]').html(summ)
                calcprice();
            })

            if ($('.main-form').hasClass('main-form-with_errs')) {
                $('.main-form').css('display', 'block');

                document.getElementById('maindiv').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }




            function slideSelect() {
                const activeSlideIndex = +document.querySelector('.swiper-pagination-bullet-active').getAttribute('aria-label').replace(/\D/g, "") - 1
                if (document.querySelector('.tickets-tabs__swiper-slide.active')) {
                    document.querySelector('.tickets-tabs__swiper-slide.active').classList.remove('active')
                }
                const activeSlideEl = document.querySelectorAll('.tickets-tabs__swiper-slide')[activeSlideIndex]
                activeSlideEl.classList.add('active')
                activeSlideEl.closest('.tickets-tabs__swiper-container').classList.add('active')
                document.querySelector(`[name="pay_type"][value="${activeSlideEl.getAttribute('data-value')}"]`).closest('label').click()
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
                        if (this.clickedIndex !== undefined) {
                            this.slideTo(this.clickedIndex)
                            slideSelect()
                        }

                    },
                }

            })



            $('#inn,#kpp,#ogrn,#rs,#ks,#bik').on('input', function(e) {
                $(this).val($(this).val().replace(/[^0-9]/g, ''));
            });
            $('#pay_type').trigger('change');

            $('.custom-checkbox').each(function() {
                if ($(this).children('input').hasClass('input_form_err')) {
                    $(this).addClass('input_form_err');
                }
            });

            $('#tickets_form_payment').on('submit', function (e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var payType = $('[name="pay_type"]:checked').val();
                var btn = $('#buybtn');
                var originalText = btn.text();

                btn.prop('disabled', true);
                btn.html('<i class="fa fa-spinner fa-spin"></i> Обработка...');

                try {
                    ym(47656444, 'reachGoal', 'ticket', {
                        order_price: $('#price2').val(),
                        currency: "RUB"
                    });
                } catch (e) {}

                function resetButton() {
                    btn.prop('disabled', false);
                    btn.html(originalText);
                }

                if (payType == '2') {
                    $.ajax({
                        url: url,
                        method: 'post',
                        dataType: 'json',
                        data: form.serialize(),
                        success: function(resp) {
                            if (resp.redirect_url) {
                                window.location.href = resp.redirect_url;
                            } else {
                                resetButton();
                                alert('Не удалось получить ссылку для оплаты');
                            }
                        },
                        error: function(jqXHR) {
                            resetButton();
                            var msg = 'Ошибка при создании заказа';
                            if (jqXHR.status === 422) {
                                msg = 'Проверьте правильность заполнения полей';
                            } else if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                                msg = jqXHR.responseJSON.error;
                            }
                            alert(msg);
                        }
                    });
                } else {
                    $.ajax({
                        url: url,
                        method: 'post',
                        dataType: 'html',
                        data: form.serialize(),
                        success: function(data) {
                            $('#tickets_payment_form').empty().html(data);
                            resetButton();
                        },
                        error: function(jqXHR) {
                            resetButton();
                            var msg = 'Произошла ошибка, попробуйте позже';
                            if (jqXHR.responseText) {
                                var errorMatch = jqXHR.responseText.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
                                if (errorMatch) {
                                    msg = $(errorMatch[1]).text().trim() || msg;
                                }
                            }
                            alert(msg);
                        }
                    });
                }

                return false;
            });
        });

        function calcprice() {
            var bilprice = parseFloat($('#current_price').val());
            var kolvo = $('#kolvo').val();
            var discount = parseFloat($("#promo_discount").val() || 0);
            if (discount > 0 && discount <= 100) {
                bilprice = bilprice - (bilprice * discount / 100);
            }
            var nadbavka = 1;
            if ($('[name="pay_type"]:checked').val() == 2) nadbavka = 1; //поменять на 1.05 если комиссия за счет клиента
            $('#price').text(new Intl.NumberFormat('ru-RU', {
                style: 'currency',
                currency: 'RUB'
            }).format(bilprice * kolvo * nadbavka));
            $('#price2').val(bilprice * kolvo * nadbavka);
            if (bilprice * kolvo * nadbavka >= 600000 && $('#pay_type').val() == 2) {
                $('[name="pay_type"]').val(1).trigger('change');
                $('#card_method').prop('disabled', true);
            } else {
                $('#card_method').prop('disabled', false);
            }
        }
        // $('[name="pay_type"]').on('change',function() {
        //     alert(1)
        //     $('.main-form').show()
        //     var type=$(this).val();
        //     if(type==2){
        //         $('#yur').hide();
        //         $('input','#yur').prop('required',false);
        //     }
        //     else{
        //         $('#yur').show();
        //         $('input:not(.noreq)','#yur').prop('required',true);
        //     }
        //     calcprice();
        // });
    </script>
    @if(!config('app.block_dadata'))
        <script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@19.8.0/dist/js/jquery.suggestions.min.js"></script>
        <script>
            $(document).ready(function() {

                var base_type = $('.tickets-tabs__input:checked').val();

                if (base_type == 2) {
                    $('#yur').hide();
                    $('input', '#yur').prop('required', false);
                } else {
                    $('#yur').show();
                    $('input:not(.noreq)', '#yur').prop('required', true);
                }

                $('[name="pay_type"]').on('ifChecked', function() {
                    $('.main-form').show()
                    this.closest('.tickets-tabs').classList.add('active')
                    $('.tickets-tabs__item.active').removeClass('active')
                    this.closest('label').classList.add('active')
                    var type = $(this).val();
                    if (type == 2) {
                        $('#yur').hide();
                        $('input', '#yur').prop('required', false);
                    } else {
                        $('#yur').show();
                        $('input:not(.noreq)', '#yur').prop('required', true);
                    }
                    calcprice();
                });
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
                        if (suggestion.data.kpp) {
                            $('#kpp').val(suggestion.data.kpp);
                        } else {
                            $('#kpp').val('');
                        }
                        if (suggestion.data.management) {
                            $('#gendir').val(suggestion.data.management.name);
                            $('#doljn').val(suggestion.data.management.post);
                        } else {
                            $('#gendir').val('');
                            $('#doljn').val('');
                        }
                        $.ajax({
                            url: '/api/req-suggestions',
                            dataType: 'json',
                            type: 'get',
                            data: {
                                inn: suggestion.data.inn,
                                ogrn: suggestion.data.ogrn
                            },
                            success: function(resp) {
                                if (typeof resp.error !== 'undefined' && resp.error != null) return false;
                                $('#rs').val(resp.rs);
                                $('#ks').val(resp.ks);
                                $('#bank').val(resp.bank);
                                $('#bik').val(resp.bik);
                                $('#phone').val(resp.phone);
                            }
                        })
                    }
                });
                $('[data-scroll-to-tickets]').on('click', function(event) {
                    event.preventDefault()
                    document.getElementById('tickets-box').scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    })
                });
            });
        </script>
    @endif
@endsection
