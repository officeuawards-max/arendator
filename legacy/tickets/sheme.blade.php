<div class="svgkostyl">
    <div class="svgwrapper" style="
                                    zoom: {{ 1170/($aw_params['info']->scene_width??575) }};
                                    width: {{ $aw_params['info']->scene_width??575 }}px;
                                    {{--height: {{ ($aw_params['info']->scene_height??300)+40 }}px;--}}
                                    height: {{ ($aw_params['info']->scene_height??300)+40+50 }}px;
                                    ">
        <div class="svgscene" style="
                                    background-image: url('{{ $aw_params['scene']['img'] }}');
                                    background-size: {{ $aw_params['info']->scene_width ?? 575 }}px {{ $aw_params['info']->scene_height ?? 300 }}px;
                                    "></div>
        <div class="svgstoly">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="
                                        width: {{ $aw_params['info']->scene_width ?? 575 }}px;
                                        height: {{ $aw_params['info']->scene_height ?? 300 }}px;
                                        ">
                @foreach($aw_params['scene']['tables'] as $stol)
                    @php
                        $stollength = mb_strlen($stol->mark);
                        //$rectwidth = pow($stollength*6,0.7)*2;
                        $rectwidth = pow($stollength*6,0.8)*2;
                    @endphp
                    <g class="svg_g " data-id="{{ $stol->id }}" onclick="selectTable('{{ $stol->id }}')" data-selected="0" data-zone="{{ $stol->zone_id }}" data-status="{{ $stol->status2??$stol->status }}" data-name="{{ $stol->name }}">
                        <title>Стол {{ $stol->name }}</title>
                        {{--
                        <rect class="svgrect marking" style="display: none; z-index: 9999;" x="{{ $stol->pos_x+$aw_params['info']->scene_offset_x-($rectwidth/2) }}" y="{{ $stol->pos_y+$aw_params['info']->scene_offset_y+6 }}" width="{{ $rectwidth }}" height="14" rx="2" ry="2"></rect>

                        <text class="stolname" style="/*display: none;*/ z-index: 999999;" x="{{ $stol->pos_x+$aw_params['info']->scene_offset_x }}" y="{{ $stol->pos_y + $aw_params['info']->scene_offset_y + $rand }}" text-anchor="middle" alignment-baseline="middle">{{ $stol->mark }}</text>
                        --}}
                        <circle class="svgcircle {{ $busy[$stol->status2??$stol->status][0] }}" style="z-index: 777;" fill="{{ $stol->zone->color }}" cx="{{ $stol->pos_x+$aw_params['info']->scene_offset_x }}" cy="{{ $stol->pos_y+$aw_params['info']->scene_offset_y }}" r="{{ $aw_params['info']->scene_ball_diameter }}"></circle>

                        <text
                            class="stolnum"
                            x="{{ $stol->pos_x+$aw_params['info']->scene_offset_x }}"
                            y="{{ $stol->pos_y+$aw_params['info']->scene_offset_y }}"
                            text-anchor="middle"
                            alignment-baseline="middle"
                            style="font-size: {{ $aw_params['info']->scene_font_size }}px;"
                        >{{ $stol->name }}</text>
                    </g>
                @endforeach

                @foreach($aw_params['scene']['tables'] as $stol)
                    @php
                        $stollength = mb_strlen($stol->mark);
                        //$rectwidth = pow($stollength*6,0.7)*2;
                        $rectwidth = pow($stollength*6,0.8)*2;
                        $rand = 0;
                        $rand = rand(1, 15);
                    @endphp
                    <g class="svg_g ">
                        <rect class="svgrect marking" style="opacity: 0.8;" x="{{ $stol->pos_x+$aw_params['info']->scene_offset_x-($rectwidth/2) }}" y="{{ $stol->pos_y - 6 + $rand }}" width="{{ $rectwidth }}" height="10" rx="2" ry="2"></rect>
                        <text class="stolname" style="font-size: 8px; background: #fff;" x="{{ $stol->pos_x+$aw_params['info']->scene_offset_x }}" y="{{ $stol->pos_y + $aw_params['info']->scene_offset_y + $rand }}" text-anchor="middle" alignment-baseline="middle">{{ $stol->mark }}</text>
                    </g>
                @endforeach
            </svg>
        </div>

        <div class="svglegend" style="top: {{ ($aw_params['info']->scene_height ?? 300)+1 }}px;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: {{ $aw_params['info']->scene_width ?? 575 }}px;">
                @php
                    $legendx = 20;
                @endphp
                @foreach($aw_params['scene']['zones'] as $zone)
                    <g>
                        <circle fill="{{$zone->color}}" cx="{{$legendx}}" cy="20" r="10"></circle>
                        <text class="svglegendtext" x="{{$legendx + 15}}" y="20" alignment-baseline="middle"> {{$zone->name}}</text>
                    </g>
                    @php
                        $bbox = imageftbbox(15, 0, public_path('/fonts/Open-Sans/opensans.ttf') , $zone->name);
                        $legendx += abs($bbox[0]) + abs($bbox[2]) + 20;
                    @endphp
                @endforeach
                @foreach ($busy as $status)
                    @continue(!$status[2])
                    <g>
                        <circle class="{{$status[0]}}" fill="#fff" cx="{{$legendx}}" cy="20" r="10"></circle>
                        <text class="svglegendtext" x="{{$legendx + 15}}" y="20" alignment-baseline="middle"> {{$status[1]}}</text>
                    </g>
                    @php
                        $bbox = imageftbbox(15, 0, public_path('/fonts/Open-Sans/opensans.ttf') , $status[1]);
                        $legendx+= abs($bbox[0]) + abs($bbox[2]) + 20;
                    @endphp
                @endforeach
            </svg>
        </div>

        <div style="position: absolute; top: 435px; left: 10px;">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g>
                    <rect fill="#fff" x="0" y="10" r="10" width="15" height="15" style="stroke: #ccc; stroke-width: 1px;"></rect>
                    <text class="svglegendtext" x="23" y="20" alignment-baseline="middle"> КОЛОННА</text>
                </g>
            </svg>
        </div>

    </div>
</div>
<p><i>* Схема столов может быть изменена</i></p>


<button type="button" class="n-popup__close scheme-table__select-close btn secondary">Выбрать</button>
