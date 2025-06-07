<section class="home__calendar calendar-section">
    <h2 class="calendar-section__title section-title section-title--margin">
        Календар
    </h2>

    @foreach ($templateCalendar as $series => $template)
        @php
            $seriesNumber = $series + 1;
            $seriesData = $seriesMetas[$seriesNumber] ?? [];
            $firstDate = isset($seriesData[0]['start_date']) ? \Carbon\Carbon::parse($seriesData[0]['start_date']) : null;
        @endphp

        <div wire:key="series-{{ $series }}" class="calendar-section__body">
            <div class="calendar-section__block">
                <div class="calendar-section__series uppercase">
                    СЕРІЯ {{ $seriesNumber }}
                    @if ($firstDate)
                        {{ $firstDate->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('D H:i') }}
                    @endif
                </div>

                <div class="calendar-section__items">
                    @foreach ($template as $idxRound => $round)
                        @php
                            $roundNumber = $idxRound + 1;
                            $meta = $seriesMetas[$roundNumber][$series] ?? $seriesMetas[1][0] ?? null;
                            $date = $meta ? \Carbon\Carbon::parse($meta['start_date'])->locale('uk')->settings(['formatFunction' => 'translatedFormat'])->format('d.m') : '';
                            $isFinal = $loop->last;
                            $isActive = $currentRound['round_number'] == $roundNumber && $currentRound['series_number'] == $seriesNumber;
                        @endphp

                        <div wire:key="round-{{ $idxRound }}" class="calendar-section__item item-calendar item-calendar--gray-bg">
                            <div class="item-calendar__date">
                                {{ $date }}
                            </div>

                            <div 
                                wire:click="selectedRound({{ $roundNumber }}, {{ session('current_event', 0) }}, {{ $seriesNumber }})"
                                class="item-calendar__wrapper {{ $isActive ? 'item-calendar--active' : '' }}"
                            >
                                <div class="item-calendar__label">
                                    {{ $isFinal ? 'ФІНАЛ' : $roundNumber . ' Тур' }}
                                </div>

                                <div class="item-calendar__body">
                                    @if ($isFinal)
                                        @if(count($teamsForTable) == $event->tournament->count_teams)
                                            @php $startTeam = $series * 3; @endphp
                                            @for ($i = $startTeam; $i < $startTeam + 3; $i++)
                                                <span data-series="{{ $series }}" data-id="333" style="background-color: {{ $teamsForTable[$i]['color'] }}"></span>                                    
                                            @endfor
                                        @else
                                            @foreach ($round as $item)
                                                <span data-series="{{ $series }}" data-id="222" class="{{ $colorClasses[$colorNames[$item]] }}"></span>                                    
                                            @endforeach
                                        @endif
                                    @else
                                        @if ($teams->count() == $event->tournament->count_teams && isset($seriesMetas[$roundNumber]))
                                            @foreach ($round as $item)
                                                <span data-id="111" style="background: {{ $teams[$item]->color->color_picker }}"></span>
                                            @endforeach
                                        @else
                                            @foreach ($round as $item)
                                                <span data-series="{{ $series }}" data-id="222" class="{{ $colorClasses[$colorNames[$item]] }}"></span>                                    
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</section>
