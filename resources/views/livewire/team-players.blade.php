<section class="profile__players players-tournament">
    <h2 class="players-tournament__title section-title section-title--margin">
        Гравців зареєстровано
    </h2>
    {{-- @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('notice'))
        <div class="alert alert-notice">
            {{ session('notice') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif --}}
    <div class="players-tournament__items">
        @forelse ($players as $player)
        
        <div wire:key="{{$player['id']}}" class="players-tournament__item">
            <article class="item-player item-player--stats">
                <a href="#" class="item-player__image-link">
                    <img src="{{ asset('storage/' . $player['photo']) }}" alt="Image" class="ibg">
                </a>
                <div class="item-player__name">
                    <a href="#"> {{$player['full_name']}}</a>
                </div>
                <div class="item-player__details">
                    <div class="item-player__info">
                        31
                        <img src="img/player/field.webp" alt="Image" class="ibg ibg--contain">
                    </div>
                    <div class="item-player__info">
                        28
                        <img src="img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                    </div>
                    <div class="item-player__info item-player__info--yellow-card">
                        1
                    </div>
                    <div class="item-player__info item-player__info--red-card">
                        0
                    </div>
                </div>
                <div class="item-player__rating rating rating__items">
                    @for ($i = 0; $i < 10; $i++)
                    <label class="rating__item @if($i < $player['rating']) rating__item--active @endif disable">
                    <input class="rating__input" type="radio" name="rating" value="{{$i}}">
                        <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.669408 7.82732C0.398804 7.57384 0.545797 7.11561 0.911812 7.07165L6.09806 6.44858C6.24723 6.43066 6.3768 6.33578 6.43972 6.19761L8.62721 1.39406C8.78159 1.05505 9.25739 1.05499 9.41177 1.39399L11.5993 6.19751C11.6622 6.33568 11.7909 6.43082 11.9401 6.44873L17.1266 7.07165C17.4926 7.11561 17.6392 7.57398 17.3686 7.82745L13.5347 11.4193C13.4244 11.5226 13.3753 11.6764 13.4046 11.8256L14.4221 17.0141C14.4939 17.3803 14.1092 17.664 13.7876 17.4816L9.23042 14.8972C9.09934 14.8228 8.94009 14.8232 8.80901 14.8975L4.25138 17.481C3.92976 17.6633 3.54432 17.3802 3.61615 17.0141L4.63382 11.8259C4.66309 11.6767 4.61412 11.5226 4.50383 11.4193L0.669408 7.82732Z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </label>
                    @endfor
                </div>
            </article>
            <div data-status="{{$player['status']}}" class="players-tournament__actions">
                @php
                    $statusClass = $player['status'] === 'main' ? 'text-green-600' : 'text-stone-600';
                @endphp
                <div class="text-center font-extrabold uppercase py-1 px-2 text-tiny transition w-full {{ $statusClass }}">
                    {{ $player['status'] === 'main' ? 'ОСНОВНИЙ' : 'РЕЗЕРВНИЙ' }}
                </div>
                
               
                    <button 
                        wire:click="save({{ $player['id'] }}, {{ $team->id }})"
                        type="button"
                        class="players-tournament__label text-white {{ $player['status'] === 'main' ? 'gray-bg' : 'green-bg' }}"
                    >
                        {{ $player['status'] === 'main' ? 'РЕЗЕРВНИЙ' : 'ОСНОВНИЙ' }}
                    </button>
               
                    

                <livewire:remove-player-from-team :player="$player" :key="$player['id']" :team="$team" />
                
            </div>
        </div>
            
        @empty
        <div class="text-gray-500">Немає жодного гравця у цій команді</div>
        @endforelse                
    </div>
    <div class="players-tournament__info">
        <div class="players-tournament__info-item _icon-star">
            основний ГРАВЕЦЬ - БУДЕ МАТИ ПРІОРИТЕТНУ МОЖЛИВІСТЬ ДОБАВИТИСЬ HA МАТЧ ЗА 7 ДНІВ ДО ЙОГО ПОЧАТКУ
        </div>
        <div class="players-tournament__info-item _icon-star">
            ВИДАЛИТИ ГРАВЦЯ МОЖЛИВО, ЯКЩО ВІН НЕ БУВ ЗАГРАННИЙ НА ТУРНІРІ (НЕЗІГРАВ ЖОДНОГО МАТЧУ)
        </div>
        <div class="players-tournament__info-item _icon-star">
            РЕЗЕРВНИЙ ГРАВЕЦЬ - БУДЕ МАТИ МОЖЛИВІСТЬ ДОБАВИТИСЬ НА МАТЧ ЗА 6 ДНІВ ДО ЙОГО ПОЧАТКУ
        </div>
    </div>
</section>