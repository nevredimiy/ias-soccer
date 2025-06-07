<section class="home__calendar calendar-section">
    <h2 class="calendar-section__title section-title section-title--margin">
        Календар
    </h2>
    <div class="calendar-section__body">
    
         <div class="calendar-section__block">
            <div class="calendar-section__series">
                СЕРІЯ I ЧТ 18:30
            </div>
            <div class="calendar-section__items">
                @foreach($series1 as $index => $triplet)
                <div wire:key="{{$index}}" class="calendar-section__item item-calendar item-calendar--gray-bg">
                    <div class="item-calendar__date">
                        30.03
                    </div>
                    <div    wire:click="selectedRound({{ $index + 1 }}, {{ session('current_event', 0) }}, 1)" 
                            class="item-calendar__wrapper @if ($currentRound['round_number'] == $index + 1)
                                item-calendar--active
                            @endif"
                    >
                        <div class="item-calendar__label">
                            {{$index + 1}} Тур
                        </div>
                        <div class="item-calendar__body">
                            @foreach($triplet as $idx => $teamId)
                            <span wire:key="{{$idx}}" class="{{ $this->getColorClassByTeamId($teamId) }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="calendar-section__item item-calendar item-calendar--border">
                    <div class="item-calendar__date">
                        30.03
                    </div>
                    <div class="item-calendar__wrapper">
                        <div class="item-calendar__label">
                            ФІНАЛ
                        </div>
                        <div class="item-calendar__body">
                            <span class="-bg"></span>
                            <span class="-bg"></span>
                            <span class="-bg"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>  
                        
    </div>

  
</section>