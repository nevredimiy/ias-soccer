@extends('layouts.app')

@section('title', 'Створення команди')

@section('content')
<div class="page__container">
    <div class="page__wrapper">
        <div class="page__tournament tournament">
            <div class="tournament__block _block">
               
               @livewire('series-info', [
                                            'seriesMeta' => $seriesMeta,
                                            'event' => $event,
                                            'playerPrice' => $playerPrice,
                                            
                                        ], key($seriesMeta->id))

               
                <x-shedule
                    :event="$event"
                    :teams="$teams"
                />

                @if ($event->tournament->team_creator == 'player')
                    <x-team-registration-cost  :event="$event" />
                @endif
                

                <section class="tournament__reg reg-tournament">
                   
                    <h2 class="reg-tournament__title section-title section-title--margin">
                        ЗАЯВЛЕНІ КОМАНДИ
                    </h2>

                    @if (count($teams))
                    <div class="reg-tournament__body">
                        @foreach ($teams as $team)
                            <div style="--color: #F7E10E; --team-color: {{ $team->team_color }};" class="reg-tournament__item reg-tournament__item--join">
                                <div class="reg-tournament__label">
                                    {{$team->name}}
                                </div>
                                <div style="--color: #FFF" class="reg-tournament__rating">
                                    <div data-rating-size="10" data-rating-value="8" class="rating">
                                        <div class="rating__items">
                                            @for ($i=1; $i<=10; $i++)
                                            <label class="rating__item @if ($i <= $team->rating)
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
                                <div style="--color: {{ $team->status_color}}" class="reg-tournament__status">
                                    <span>
                                       {{ $team->status_text }}
                                    </span>
                                </div>
                               
                                @if ($team->player_request_status != 'closed' )
                                    @livewire('join-team', ['teamId' => $team->id])
                                @endif
                            </div>                            
                        @endforeach
                    </div>

                    @else
                    <div class="text-center mb-4">
                        <p>Ви перші. У цій події ще немає заявленних команд </p>
                    </div>
                    @endif
                   
                   
                    @if (count($teams) < $event->tournament->count_teams)
                    <div class="reg-tournament__actions">
                        <a href="{{ route('teams.request.create', ['id' => $event->id]) }}" class="reg-tournament__link button button--yellow"><span>ЗАЯВИТИ КОМАНДУ</span></a>
                    </div>                        
                    @endif
                </section>
               
            </div>
        </div>
    </div>
</div>
@endsection