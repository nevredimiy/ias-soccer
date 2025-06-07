 <section class="tournament__players players-tournament">
    <h2 class="players-tournament__title section-title section-title--margin">
        Гравців зареєстровано
    </h2>
    <div data-prgs="18" data-prgs-value="13" class="players-tournament__progress">
    </div>
    <div class="players-tournament__items">

        @if(session()->has('error'))
            <p class="text-red-500 text-center mb-2">{{ session('error') }}</p>
        @endif

        @if(session()->has('error_balance'))
        <div class="flex flex-col items-center mb-2">
            <p class="text-red-500 text-center mb-2">{{ session('error_balance') }}</p>
            <a class="button button--red botton--small" href="{{route('balance.form', ['amount' => $playerPrice])}}">Поповнити баланс</a>
        </div>
        @endif

        @if(session()->has('success'))
            <p class="text-green-500 text-center mb-2">{{ session('success') }}</p>
        @endif

        @if($isSeriesClosed)
            <p class="text-red-500 text-center mb-2">Серія закрита</p>
        @endif

        @foreach ($event->teams as $team)
        <div wire:key="{{$team->id}}" style="--color: {{$team->color->color_picker}}" class="players-tournament__team">
            @for ($i=0; $i < 6; $i++)
            @php
                $playerNumber = $i + 1;
                $regPlayer = collect($regPlayers[$team->id] ?? [])->firstWhere('player_number', $playerNumber);
            @endphp
            <div wire:key="{{$team->id}}_{{$i}}" class="players-tournament__item">
                @if ($regPlayer)
                    <article class="item-player item-player--stats">
                        <a href="#" class="item-player__image-link">
                            <img src="{{ asset('storage/'. $regPlayer['photo']) }}" alt="{{ $regPlayer['last_name'] }} {{ $regPlayer['first_name'] }}" class="ibg">
                        </a>
                        <div class="item-player__name">
                            <a href="#">{{ $regPlayer['last_name'] }} {{ $regPlayer['first_name'] }}</a>
                        </div>

                        <x-player-rating 
                            :rating="$regPlayer['rating']"
                            :verifyrating="$regPlayer['verify_rating']"
                        />
                        
                    </article>
                    @if ($currentPlayer->id == $regPlayer['id'] && !$isSeriesClosed)
                    <div class="flex justify-center">
                        <button 
                            wire:click="deletePlayer"
                            wire:confirm="Ви дійсно хочите вийти із команди?"
                            class="button button--yellow button--small"
                        >
                            Вийти
                        </button>
                    </div>                    
                    @endif
                @else
                    
                    <button 
                        wire:click="BookingPlace({{$team->id}}, {{$playerNumber}})" 
                        wire:confirm="Ви впевнені що хочете вступити в цю команду?"
                        class="players-tournament__empty"
                    >
                        <span>
                            ЗАЙНЯТИ МІСЦЕ
                        </span>
                    </button>                    
                @endif
            </div>
            @endfor           
        </div>
        @endforeach
    </div>
</section> 