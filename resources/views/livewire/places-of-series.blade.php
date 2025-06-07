<section class="team__latest-series latest-series">   
   
    <h2 class="latest-series__title section-title section-title--margin">
        ЗАЯВКА НА НАЙБЛИЖЧУ СЕРІЮ
    </h2>

    @if (empty($seriesMeta))
        <h3 class="text-center">Немає серій у цієї команди</h3>
    @else
    @php
        $formateDate = \Carbon\Carbon::parse($seriesMeta->start_date);
    @endphp
        <div class="latest-series__match-info match-info">
            <div class="match-info__details">
                <div class="match-info__label">
                    {{$formateDate->translatedFormat('d F') ?? ''}}
                </div>
                <div class="match-info__label">
                    {{$formateDate->translatedFormat('D') ?? ''}}
                </div>
                <div class="match-info__label">
                    {{$formateDate->translatedFormat('H:i') ?? ''}}
                </div>
                <div class="match-info__label">
                    {{$seriesMeta->round ?? ''}} ТУР

                </div>
                <div class="match-info__label">
                    СЕРІЯ {{$seriesMeta->series ?? ''}}
                </div>
            </div>
            <div class="match-info__teams">
                @foreach ($seriesColorTeams as $key => $seriesColorTeam)
                <div wire:key="{{$key}}" style="background-color: {{$seriesColorTeam['styleColor']}}" class="match-info__label">
                    {{$seriesColorTeam['name']}}
                </div>                        
                @endforeach
                
            </div>
        </div>   
    @endif


        
    @if (session()->has('message'))
        <div class="text-center bg-yellow-100 text-yellow-800 p-2 rounded mb-2">
            {{ session('message') }}
        </div>
    @endif

    @if ($isPlayerReserve)
        <h2 class="text-center text-red-400">Ви в резерві! Не можете приймати участь у цій серії.</h2>        
    @endif

     @if ($statusRegistration == 'closed')
        <h2 class="text-center bg-red-500 text-white py-4 rounded">Серія закрита</h2>
     @endif
    @php
        $blockClass = $isPlayerReserve ? 'block' : 'hidden';
    @endphp
    
    <div class="latest-series__items relative p-2">        
        <div class="{{$blockClass}} absolute inset-0 bg-gray-400 opacity-50 rounded z-10"></div>
       
        @if ($regPlayers)
            @php
                $count = $statusRegistration == 'closed' ? $regPlayers->count() : 9;
            @endphp
            @for ($i=0; $i<$count; $i++)
                @php
                    $playerNumber = $i + 1;
                    $regPlayer = $regPlayers->firstWhere('player_number', $playerNumber);
                @endphp
                <div wire:key="{{$i}}" class="latest-series__item">
                    @if ($regPlayer)
                        <article class="item-player item-player--stats">
                            <div class="item-player__image-link">
                                <img src="{{asset('storage/'. $regPlayer->player->photo)}}" alt="{{$regPlayer->player->full_name}}" class="ibg">
                            </div>
                            <div class="item-player__name">
                                <div>{{$regPlayer->player->last_name}} {{$regPlayer->player->first_name}}</div>
                            </div>
                            <div class="item-player__details">
                                <div class="item-player__info">
                                    0
                                    <img src="img/player/field.webp" alt="Image" class="ibg ibg--contain">
                                </div>
                                <div class="item-player__info">
                                    0
                                    <img src="img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                                </div>
                                <div class="item-player__info item-player__info--yellow-card">
                                    0
                                </div>
                                <div class="item-player__info item-player__info--red-card">
                                    0
                                </div>
                            </div>

                            <x-player-rating 
                                :rating="$regPlayer->player->rating" 
                                :verifyrating="$regPlayer->player->verify_rating"
                            />

                        </article>
                    @elseif ($statusRegistration !== 'closed')
                        <button 
                                @if (!$isPlayerReserve)
                                    wire:click="takePlace({{$i + 1}})"   
                                    wire:confirm="Ви дійсно хочете грати у цій серії?"                          
                                @endif
                                class="latest-series__empty"
                            >
                            ЗАЙНЯТИ МІСЦЕ
                        </button>
                    @endif

                    <div style="background-color: {{$team->color->color_picker}}" class="latest-series__number">{{$playerNumber}}</div>

                    @if ($statusRegistration !== 'closed')
                        @if ($regPlayer && $team->owner_id == $userId)
                            <button wire:click="dropRegPlayer({{$regPlayer->player_id}})" class="button button--yellow button--small">Відкликати</button>
                        @elseif($regPlayer && $regPlayer->player_id == $playerId)
                            <button wire:click="dropRegPlayer({{$regPlayer->player_id}})" class="button button--yellow button--small">Вийти</button>
                        @endif                        
                    @endif
                </div>              
            @endfor
        @else
            <p class="text-center text-red-500">Дані про зареєстрованих гравців недоступні</p>
        @endif

    </div>
    @if ($team->owner_id == $user->id)
        <div  class="">
            <button 
                type="button"
                wire:click="closeRegistrations"
                wire:confirm="Закрити заявки? \n\nПісля підтвердження у гравців спишуться кошти з балансу"
                class="button button--red button--big @if ($statusRegistration == 'closed')
                    disabled
                @endif"
                
            >
                @if ($statusRegistration == 'closed')
                    Заявка закрыта
                @else
                    Закрити заявки
                @endif
            </button>
        </div>        
    @endif
    @if($showModal)
    <div class="modal" wire:click="closeModal" wire:show="showModal">
        <div wire:click.stop class="modal__block">
            <button wire:click="closeModal" type="button" class="modal__btn-close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <div>Вам потрібно поповнити не менж ніж на {{ $desiredBalance }} грн</div>
            <p>Заброньована сума  {{ $reserved_balance }} </p>
            <div class=""><p>Перейти на сторінку поповнення балансу</p></div>
            <div><a class="button button--blue button--small" href="{{ route('balance.form', ['amount' => round($desiredBalance)]) }}">Перейти</a></div>
        </div>
    </div>
    @endif
</section>