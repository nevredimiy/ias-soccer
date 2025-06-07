<div class="flex justify-center gap-2">
   <section class="home__protocol protocol">
        <h2 class="protocol__title section-title section-title--margin">Протокол серії</h2>
        <div class="protocol__body">
            @foreach ($templateMatches as $i => $teamplateMatche)
                @php
                    $matchNumber = $i + 1;
                    $idxTeamId1 = $teamplateMatche[0] ?? null;
                    $idxTeamId2 = $teamplateMatche[1] ?? null;

                    $color1 = isset($idxTeamId1, $teamIdsInSeries, $teamColors)
                        ? $teamColors[$teamIdsInSeries[$idxTeamId1]] ?? '#ccc'
                        : '#ccc';

                    $color2 = isset($idxTeamId2, $teamIdsInSeries, $teamColors)
                        ? $teamColors[$teamIdsInSeries[$idxTeamId2]] ?? '#ccc'
                        : '#ccc';

                    // Активный класс для кнопки
                    $activeClass = ($i + 1) === $activeMatch ? '__active' : '';
                @endphp

                <div wire:key="matche_{{$i}}" class="protocol__block">
                    
                    <div class="protocol__match match-protocol">
                        <button wire:click="selectedMatch({{ $matchNumber }})" class="match-protocol__label {{ $activeClass }}">
                            МАТЧ {{ $matchNumber }}
                        </button>
                        <span style="background: {{ $color1 }}" class="blue-bg">{{$goals[$matchNumber][$teamIdsInSeries[$idxTeamId1]] ?? 0}}</span>
                        <span style="background: {{ $color2 }}" class="yellow-bg">{{$goals[$matchNumber][$teamIdsInSeries[$idxTeamId2]] ?? 0}}</span>
                    </div>
                    <div class="protocol__content">
                        @if (isset($matches[$i]['match_events']))
                                   
                        @foreach ($matches[$i]['match_events'] as $matchEvents)
                        
                        
                            <div 
                                wire:key="event_{{$matchEvents['id']}}"
                                @if ($matchEvents['team_id']==$teamIdsInSeries[$idxTeamId1])
                                style="background: {{$color1}}"
                                @else
                                style="background: {{$color2}}"
                                @endif
                                
                                data-team1-id="{{$matchEvents['team_id']}}" 
                                data-team2-id="{{$teamIdsInSeries[$idxTeamId1]}}"  
                                class="protocol__item">
                                
                                <span class="protocol__ball @if ( $matchEvents['team_id'] == $teamIdsInSeries[$idxTeamId1] ) up @endif">
                                    {{ $seriesPlayers[$matchEvents['player_id']]['player_number'] }}
                                    <img width="15" height="15" src="{{ asset('img/icons/' . $icons[$matchEvents['type']] . '.png') }}" alt="Image" class="ibg ibg--contain">
                                </span>
                            </div>
                        @endforeach                            
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
   </section>
</div>