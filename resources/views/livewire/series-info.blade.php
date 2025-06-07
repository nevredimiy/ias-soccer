<div class="tournament__hero hero-tournament">
    <div class="hero-tournament__location item-stadium">
        <h3 class="item-stadium__title">
            {{ $seriesMeta->stadium->name ?? 'Не указано' }}
        </h3>
        <div class="item-stadium__image">
            <img src="{{asset('img/stadium/preview.webp')}}" alt="Image" class="ibg">
        </div>
        <div class="item-stadium__body">
            <div class="item-stadium__location _icon-location">
                {{ $seriesMeta->stadium->location->address ?? 'Не указано' }} ({{ $seriesMeta->stadium->name ?? 'Не указано' }})
            </div>
            <div class="item-stadium__details">
                <div class="item-stadium__info info-stadium">
                    {{ $seriesMeta->stadium->fields_40x20 ?? 'Не указано' }}
                    <div class="info-stadium__icon">
                        <div class="info-stadium__field">
                            40х20
                            <img src="{{asset('img/stadium/01.webp')}}" alt="Image">
                        </div>
                    </div>
                </div>
                <div class="item-stadium__info info-stadium">
                    {{ $seriesMeta->stadium->fields_60x40 ?? 'Не указано' }}
                    <div class="info-stadium__icon">
                        <div class="info-stadium__field">
                            60x40
                            <img src="{{asset('img/stadium/01.webp')}}" alt="Image">
                        </div>
                    </div>
                </div>
                <div class="item-stadium__info info-stadium">
                    {{ $seriesMeta->stadium->parking_spots ?? 'Не указано' }}
                    <div class="info-stadium__icon">
                        <img src="{{asset('img/stadium/02.webp')}}" alt="Image">
                    </div>
                </div>
                <div class="item-stadium__info info-stadium {{ $seriesMeta->stadium->parking_spots ? '_icon-check' : '_icon-cross' }}">
                    <div class="info-stadium__icon">
                        <img src="{{asset('img/stadium/03.webp')}}" alt="Image">
                    </div>
                </div>
                <div class="item-stadium__info info-stadium {{ $seriesMeta->stadium->has_speaker_system ? '_icon-check' : '_icon-cross' }}">
                    <div class="info-stadium__icon">
                        <img src="{{asset('img/stadium/04.webp')}}" alt="Image">
                    </div>
                </div>
                <div class="item-stadium__info info-stadium {{ $seriesMeta->stadium->has_wardrobe ? '_icon-check' : '_icon-cross' }}">
                    <div class="info-stadium__icon">
                        <img src="{{asset('img/stadium/05.webp')}}" alt="Image">
                    </div>
                </div>
                <div class="item-stadium__info info-stadium {{ $seriesMeta->stadium->has_toilet ? '_icon-check' : '_icon-cross' }}">
                    <div class="info-stadium__icon">
                        <img src="{{asset('img/stadium/06.webp')}}" alt="Image">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-tournament__body">
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
                {{ $seriesMeta->stadium->name ?? 'Не вказано' }}
            </div>
        </div>
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
                {{$event->tournament->name ?? 'Не вказано'}}
            </div>
        </div>
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
                {{ \Carbon\Carbon::parse($seriesMeta->start_date)->locale('uk')->translatedFormat('j F') }} {{ \Carbon\Carbon::parse($seriesMeta->start_date)->format('H:i') }} - {{ \Carbon\Carbon::parse($seriesMeta->end_date)->format('H:i') }} {{ \Carbon\Carbon::parse($seriesMeta->start_date)->locale('uk')->translatedFormat('(D)') }}
            </div>
        </div>
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
                ФОРМАТ {{ $event->format ?? '5x5x5' }}
            </div>
        </div>
        <div class="hero-tournament__info">
            <div class="hero-tournament__label _icon-field">
                {{ $seriesMeta->size_field  }}м
            </div>
        </div>
        @if ($event->tournament->count_teams == 3)
            <div class="hero-tournament__info">
                <div class="hero-tournament__label hero-tournament__label--small">
                    <span>ЗАЯВКА НА СЕРІЮ:</span> 18 ГРАВЦІВ
                </div>
                <p class="text-xs text-gray-400">по 6 гравців на команду</p>
            </div>  
        @else
            <div class="hero-tournament__info">
                <div class="hero-tournament__label hero-tournament__label--small">
                    <span>ЗАЯВКА КОМАНДИ:</span> 6-20 ГРАВЦІВ
                </div>
                <div class="hero-tournament__label hero-tournament__label--small">
                    <span>ЗАЯВКА НА СЕРІЮ:</span> 5-6 ГРАВЦІВ
                </div>
            </div>  
        @endif
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
                Середній рівень гравців ЛІГИ
            </div>
            <div class="hero-tournament__rating rating">
                <div class="rating__items">
                    @for ($i=0; $i<10; $i++)
                    <label class="rating__item @if ($i < $seriesMeta->average_player_rating)
                        rating__item--active
                        @endif">
                        <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.669408 7.82732C0.398804 7.57384 0.545797 7.11561 0.911812 7.07165L6.09806 6.44858C6.24723 6.43066 6.3768 6.33578 6.43972 6.19761L8.62721 1.39406C8.78159 1.05505 9.25739 1.05499 9.41177 1.39399L11.5993 6.19751C11.6622 6.33568 11.7909 6.43082 11.9401 6.44873L17.1266 7.07165C17.4926 7.11561 17.6392 7.57398 17.3686 7.82745L13.5347 11.4193C13.4244 11.5226 13.3753 11.6764 13.4046 11.8256L14.4221 17.0141C14.4939 17.3803 14.1092 17.664 13.7876 17.4816L9.23042 14.8972C9.09934 14.8228 8.94009 14.8232 8.80901 14.8975L4.25138 17.481C3.92976 17.6633 3.54432 17.3802 3.61615 17.0141L4.63382 11.8259C4.66309 11.6767 4.61412 11.5226 4.50383 11.4193L0.669408 7.82732Z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </label>                                        
                    @endfor                                  
                </div>
            </div>
        </div>
        <div class="hero-tournament__info">
            <div class="hero-tournament__label">
               <span> 
                    {{ is_array($playerPrice) ? '≈ ' . round(($playerPrice[6] + $playerPrice[9])/2) : $playerPrice}}  ГРН / ГРАВЕЦЬ
               </span>
            </div>
            @if (is_array($playerPrice))
                <span class="text-xs text-gray-500">
                    Ціна залежить від кількості гравців у команді. Може коливатися від {{$playerPrice[9]}} до {{$playerPrice[6]}} грн
                </span>
            @endif
        </div>
    </div>
</div>