<section class="tournament__players players-tournament ">
    <div class="mb-4">
        @if(session()->has('error'))
            <p class="text-red-500 text-center mb-2">{{ session('error') }}</p>
        @endif
    
        @if(session()->has('error_balance'))
        <div class="flex flex-col items-center mb-2">
            <p class="text-red-500 text-center mb-2">{!! session('error_balance') !!}</p>
            <a class="button button--red botton--small" href="{{route('balance.form', ['amount' => $missingAmount])}}">Поповнити баланс</a>
        </div>
        @endif

        @if(session()->has('success'))
            <p class="text-green-500 text-center mb-2">{{ session('success') }}</p>
        @endif
    
        @if ($isSeriesClosed)
            <p class="text-red-500 text-center mb-2">Реєстрація закрита</p>
        @else
            <button 
                wire:click="BookingPlace" 
                wire:confirm="Ви дійстно хочете потрапити до турніру?"
                class="schedule-tournament__button button button--yellow"
            >
                <span>ЗАЯВИТИСЬ НА ТУРНІР</span>
            </button>
        @endif
    </div>
    

    <h2 class="players-tournament__title section-title section-title--margin">
        Гравців зареєстровано
    </h2>
    <div data-prgs="18" data-prgs-value="13" class="players-tournament__progress">
        @for ($i = 0; $i < 18; $i++)
            <span class="@if($i < count($players)) _active @endif"></span>
        @endfor
    </div>

   
    <div class="players-tournament__items">
        @foreach ($players as $player)
            <div wire:key="{{$player->id}}" data-player-id="{{$player->id}}" class="players-tournament__item">
                <article class="item-player item-player--stats">
                    <a href="#" class="item-player__image-link">
                        <img src="{{asset('storage/' . $player->photo)}}" alt="{{$player->full_name}}" class="ibg">
                    </a>
                    <div class="item-player__name">
                        <a href="#">{{$player->full_name}}</a>
                    </div>
                   
                    <x-player-rating 
                        :rating="$player->rating"
                        :verifyrating="$player->verify_rating" 
                    />
                    
                </article>
                @if ($currentPlayer->id == $player->id && !$isSeriesClosed)
                <div class="flex justify-center">
                    <button 
                        wire:click="deletePlayer"
                        wire:confirm="Ви дійсно хочите вийти із турніра?"
                        class="button button--yellow button--small"
                    >
                        Вийти
                    </button>
                </div>                    
                @endif
            </div>
            
        @endforeach
    </div>
    <div class="players-tournament__text">
        ПО ЗАВЕРШЕННЮ РЕЄСТРАЦІЇ ГРАВЦІВ, БУДЕ РОЗПОДІЛЕНО ПО КОМАНДАМ, ІГРОВИМ НОМЕРАМ ТА ПРИЗНАЧЕННО
        КАПІТАНІВ
    </div>
</section>