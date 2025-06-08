<section class="home__protocol protocol">
    <h2 class="protocol__title section-title section-title--margin">Протокол серії</h2>

    <div class="protocol__body">
        @if ($matches->isEmpty())
            <div class="protocol__empty flex flex-col justify-center items-center">
                <img class="w-20" width="35" height="150" src="img/empty.png" alt="Image" class="ibg">
                <p>Протокол відсутній</p>
            </div>
            
        @endif
        @foreach ($matches as $key => $match)
            <div wire:key="{{$match->id}}" data-matche-id="{{$match->id}}" class="protocol__block">
                <div class="protocol__match match-protocol">
                    <div class="match-protocol__label">
                        МАТЧ {{$key + 1}}
                    </div>
                    <span class="{{$colorClasses[$match->team1->color->name]}}">
                        {{ $match->team1_goals_count }}
                    </span>
                    <span class="{{$colorClasses[$match->team2->color->name]}}">
                        {{ $match->team2_goals_count }}
                    </span>
                </div>
                <div class="protocol__content">
                    @foreach ($match->matchEvents as $event)
                    
                        @if ($event->type == 'goal')                            

                            <div class="protocol__item {{$colorClasses[$event->team->color->name]}}">
                                <span class="protocol__ball @if ($event->team_id == $match->team1_id)
                                    up
                                @endif">
                                    {{ $event->player->getPlayerNumber($match->series_meta_id,$event->team_id) }}
                                    <img src="img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                                </span>
                            </div>

                            @if ($event->assister_id)
                            <div class="protocol__item {{$colorClasses[$event->team->color->name]}}">
                                <span class="protocol__shoe @if ($event->team_id == $match->team1_id)
                                    up
                                @endif">
                                    {{ $event->player->getPlayerNumber($match->series_meta_id,$event->team_id) }}
                                    <img src="img/player/shoe.svg" alt="Image" class="ibg ibg--contain">
                                </span>
                            </div>                 
                            @endif
                            
                        
                        @elseif ($event->type == 'yellow_card')
                            <div class="protocol__item {{$colorClasses[$event->team->color->name]}}">
                                <span class="protocol__yellow @if ($event->team_id == $match->team1_id)
                                    up
                                @endif">
                                    {{ $event->player->getPlayerNumber($match->series_meta_id,$event->team_id) }}
                                </span>
                            </div>
                        @elseif ($event->type == 'red_card')
                            <div class="protocol__item {{$colorClasses[$event->team->color->name]}}">
                                <span class="protocol__red @if ($event->team_id == $match->team1_id)
                                    up
                                @endif">
                                    {{ $event->player->getPlayerNumber($match->series_meta_id,$event->team_id) }}
                                </span>
                            </div>
                        @elseif ($event->type == 'goal' && $event->assister_id)

                            <div class="protocol__item {{$colorClasses[$event->team->color->name]}}">
                                <span class="protocol__shoe @if ($event->team_id == $match->team1_id)
                                    up
                                @endif">
                                    {{ $event->player->getPlayerNumber($match->series_meta_id,$event->team_id) }}
                                    <img src="img/player/shoe.svg" alt="Image" class="ibg ibg--contain">
                                </span>
                            </div>

                        @endif

                    @endforeach
                </div>

            </div>            
        @endforeach                   
    </div>
</section>