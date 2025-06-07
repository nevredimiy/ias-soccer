<div class="flex flex-col items-center gap-4 mt-6 mb-10">
    @foreach ($teamIdsInSeries as $idx => $teamId)
        @php
            $color = $teamColors[$teamId] ?? '#ccc';
            // Получаем массив игроков по номерам
            $playersByNumber = $playersByNumberByTeamId[$teamId] ?? [];
            $disabledClass = $teamsByMatch && !in_array($teamId, $teamsByMatch) ? ' opacity-40' : '';
        @endphp
        
        <div data-team-id="{{$teamId}}" class="flex gap-2{{$disabledClass}}">
            @for ($i = 1; $i <= 9; $i++)
                @php
                    $player = $playersByNumber[$i] ?? null;
                   
                @endphp
                <button
                    @if (empty($disabledClass) && $player)
                    wire:click="selectedPlayer({{$player}}, {{$teamId}})"                        
                    @endif
                    style="background: {{ $player ? $color : '#ccc' }}"
                    class="social__item ball{{ ($player && $player['id'] == $selectedPlayerId) ? ' __active' : '' }}"
                >
                    {{ $player ? $player->player_number : '' }}
                </button>
            @endfor
        </div>
    @endforeach
</div>