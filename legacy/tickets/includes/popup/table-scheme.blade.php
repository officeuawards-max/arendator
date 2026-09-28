<div class="n-popup" data-popup-table-scheme>
    <div class="n-popup__container">

        <div class="n-popup__loader">
            <div class="cssload-spinner"></div>
        </div>

        <button type="button" class="n-popup__close">
            <svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.83984 1.83887L10.6787 10.6777M10.6787 10.6777L19.5175 19.5165M10.6787 10.6777L19.5175 1.83887M10.6787 10.6777L1.83984 19.5165" stroke="#777777" stroke-width="3" />
            </svg>
        </button>

        <div class="n-popup__content">

            <div class="n-popup__top">
                <div class="n-popup__title">Схема зала</div>
            </div>

            @if(!empty($scene) && count($types) > 0)
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
                                <circle class="svgcircle {{$busy[$stol->status2??$stol->status][0]}}" fill="{{$stol->zone->color}}" cx="{{$stol->pos_x+$award->scene_offset_x}}" cy="{{$stol->pos_y+$award->scene_offset_y}}" r="{{$award->scene_ball_diameter}}"></circle>
                                <text class="stolnum" x="{{$stol->pos_x}}" y="{{$stol->pos_y}}" text-anchor="middle" alignment-baseline="middle" style="font-size: {{$award->scene_font_size}}px;">{{$stol->name}}</text>
                                <rect class="svgrect" x="{{$stol->pos_x-($rectwidth/2)}}" y="{{$stol->pos_y+6}}" width="{{$rectwidth}}" height="14" rx="2" ry="2"></rect>
                                <text class="stolname" x="{{$stol->pos_x}}" y="{{$stol->pos_y+13}}" text-anchor="middle" alignment-baseline="middle">{{$stol->mark}}</text>
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
            <p><i>* Схема столов может быть изменена</i></p>
            <hr>
            @endif

        </div>
    </div>
</div>
