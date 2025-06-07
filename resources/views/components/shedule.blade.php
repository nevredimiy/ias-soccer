<section class="tournament__schedule schedule-tournament schedule-tournament--super">
    <h2 class="schedule-tournament__title section-title section-title--margin">
        РОЗКЛАД
    </h2>
    <div class="schedule-tournament__body">
        @foreach ($series as $round => $item)
            <div class="schedule-tournament__block">                                
                <div class="schedule-tournament__item schedule-tournament__item--info schedule-tournament__item--center">
                    <div class="schedule-tournament__label uppercase">
                        {{ isset($seriesGroupedByRound[$round+1]) ? $seriesGroupedByRound[$round+1]->first()['start_date'] : '00' }}
                    </div>
                    <div class="schedule-tournament__label">
                        {{ isset($seriesGroupedByRound[$round+1]) ? $seriesGroupedByRound[$round+1]->first()['start_day'] : '00' }}
                    </div>
                </div>
                <div class="schedule-tournament__item schedule-tournament__item--info">
                    <div class="schedule-tournament__label">
                        ЧАС
                    </div>
                    @if(isset($seriesGroupedByRound[$round+1]))
                        @foreach ($seriesGroupedByRound[$round+1] as $v)
                            <div class="schedule-tournament__label">
                                {{ $seriesGroupedByRound[$round+1]->first()['start_time'] ?? '00' }}
                            </div>                            
                        @endforeach
                    @endif
                </div>
                <div class="schedule-tournament__item schedule-tournament__item--info">
                    <div class="schedule-tournament__label">
                        @if ($loop->last)
                            ФІНАЛ
                        @else
                            {{ $round + 1 }} ТУР
                        @endif
                    </div>
                    @if(isset($seriesGroupedByRound[$round+1]))
                        @foreach ($seriesGroupedByRound[$round+1] as $v)
                            <div class="schedule-tournament__label">
                                СЕРІЯ {{$v['series']}}
                            </div>                                        
                        @endforeach
                    @endif                                 
                </div>

                 @foreach ($templateMatches as $idxMatch => $matchTeams)
             
                 <div class="schedule-tournament__item">
                     <div class="schedule-tournament__label">
                         МАТЧ {{$idxMatch + 1}}
                     </div>
                     <div class="schedule-tournament__colors">
                            @foreach ($item as $value)
                                <span class="{{ $colorClasses[ $colorNames[ $value[ $matchTeams [ 0 ] ] ] ] }}"></span>
                                <span class="{{ $colorClasses[ $colorNames[ $value[ $matchTeams [ 1 ] ] ] ] }}"></span>
                            @endforeach                     
                     </div>         
                 </div>     
             @endforeach
            </div>  
        @endforeach
    </div>
</section>