<section class="tournament__schedule schedule-tournament">
    <h2 class="schedule-tournament__title section-title section-title--margin">
        РОЗКЛАД
    </h2>

    @foreach ( $series as $round => $s )
     <div class="schedule-tournament__body">
        <div class="schedule-tournament__block">
            <div class="schedule-tournament__item schedule-tournament__item--center schedule-tournament__item--info">
                <div class="schedule-tournament__label uppercase">
                    {{\Carbon\Carbon::parse($s[0]->start_date)->locale('uk')->translatedFormat('j F')}}
                </div>
                <div class="schedule-tournament__label uppercase">
                    {{\Carbon\Carbon::parse($s[0]->start_date)->locale('uk')->translatedFormat('l')}}
                </div>
            </div>
            <div class="schedule-tournament__item schedule-tournament__item--info">
                <div class="schedule-tournament__label">
                    ЧАС
                </div>
                {{-- @for($i=0; $i<$event->tournament->count_series; $i++) --}}
                @foreach ($s as $seriesMeta)
                <div class="schedule-tournament__label">
                    {{\Carbon\Carbon::parse($seriesMeta->start_date)->translatedFormat('H:i')}}
                </div>                    
                @endforeach
                {{-- @endfor --}}
            </div>
            @foreach ($templateMatches as $key => $item)                
            
                <div wire:key="{{$key}}" class="schedule-tournament__item">
                    <div class="schedule-tournament__label">
                        МАТЧ {{$key + 1}}
                    </div>
                    <div class="schedule-tournament__colors">
                            @for($i=0; $i<$event->tournament->count_series; $i++)
                                <span style="background-color:{{$teamColorClass[$round][$i+1][$item[0]]}}"></span>
                                <span style="background-color:{{$teamColorClass[$round][$i+1][$item[1]]}}"></span>
                            @endfor
                        </div>                        
                </div>

            @endforeach
        </div>
    </div>
        
    @endforeach
</section>