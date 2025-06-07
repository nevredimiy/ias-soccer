<section class="home__calendar calendar-section">
    <h2 class="calendar-section__title section-title section-title--margin">
        Календар
    </h2>
    <div class="calendar-section__body">
    
        <div class="calendar-section__block">
            <div class="calendar-section__series uppercase">
                СЕРІЯ I
                {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('D')}}                
                {{\Carbon\Carbon::parse($event->start_time)->format('H:i')}}
            </div>
            <div class="calendar-section__items">
                @foreach($series1 as $index => $triplet)
                <div class="calendar-section__item item-calendar item-calendar--gray-bg">
                    <div class="item-calendar__date">
                        {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('d.m')}}   
                    </div>
                    <div 
                        wire:click="selectedRound({{ $index + 1 }}, {{ session('current_event', 0) }}, 1)" 
                        class="item-calendar__wrapper @if ($currentRound['round_number'] == $index + 1 && $currentRound['series_number'] == 1)
                            item-calendar--active
                        @endif"
                    >
                        <div class="item-calendar__label">
                            {{$index + 1}} Тур
                        </div>
                        <div class="item-calendar__body">
                            @foreach($triplet as $teamId)
                            <span class="{{ $this->getColorClassByTeamId($teamId) }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
               
                <div class="calendar-section__item item-calendar ">
                    <div class="item-calendar__date">
                        {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('d.m')}}   
                    </div>
                    <div 
                        wire:click="selectedRound({{ $index + 1 }}, {{ session('current_event', 0) }}, 1)" 
                        class="item-calendar__wrapper"
                    >
                        <div class="item-calendar__label">
                            ФІНАЛ (I)
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
        <div class="calendar-section__block">
            <div class="calendar-section__series uppercase">
                СЕРІЯ II 
                {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('D')}}                
                {{\Carbon\Carbon::parse($event->end_time)->format('H:i')}}
            </div>
            <div class="calendar-section__items">
                @foreach($series2 as $index => $triplet)
                <div class="calendar-section__item item-calendar item-calendar--gray-bg">
                    <div class="item-calendar__date">
                        {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('d.m')}}                
                    </div>
                    <div 
                        wire:click="selectedRound({{ $index + 1 }}, {{ session('current_event', 0) }}, 2)" 
                        class="item-calendar__wrapper @if ($currentRound['round_number'] == $index + 1 && $currentRound['series_number'] == 2)
                            item-calendar--active
                        @endif"
                    >
                        <div class="item-calendar__label">
                            {{$index + 1}} Тур
                        </div>
                        <div class="item-calendar__body">
                            @foreach($triplet as $teamId)
                            <span class="{{ $this->getColorClassByTeamId($teamId) }}"></span>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="calendar-section__item item-calendar ">
                    <div class="item-calendar__date">
                        {{\Carbon\Carbon::parse($event->date)->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('d.m')}}   
                    </div>
                    <div 
                        wire:click="selectedRound(0, {{ session('current_event', 0) }}, 2)" 
                        class="item-calendar__wrapper"
                    >
                        <div class="item-calendar__label">
                            ФІНАЛ (II)
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