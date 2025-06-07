<div class="page__home home">
    @if(!$hasActiveEvent)
        <div class="text-center text-gray-500 py-10">
            <h2 class="text-lg font-semibold">На даний момент немає активних турнірів</h2>
        </div>
    @else
    <div class="home__block _block">    
    
        @if ($event->tournament->type == 'team')
            @livewire('tournament-table-team', ['teams' => $teams, 'eventId' => $eventId])            
        @else
            @livewire('tournament-table-individual', ['teams' => $teams, 'eventId' => $eventId])
        @endif

        @livewire('top-scorers-of-tournament', ['teams' => $teams, 'eventId' => $eventId])

    </div>

    <div class="home__block _block">

         @if ($event->tournament->subtype == 'regular')
             @livewire('team-shedule', ['event' => $event])
         @endif

        @if ($event->status == 'finished')
        <section class="home__series-result table-section">
            <h2 class="table-section__title section-title section-title--margin">
                Результати серії
            </h2>
            <div class="table-section__body">
                <div class="table-section__table-wrapper">
                    <table class="table-section__table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="fz-big">M</span>
                                </th>
                                <th></th>
                                <th>
                                    <span class="team fz-big">Команда</span>
                                </th>
                                <th>
                                    <span>В</span>
                                </th>
                                <th>
                                    <span>Н</span>
                                </th>
                                <th>
                                    <span>П</span>
                                </th>
                                <th>
                                    <span>ЗМ</span>
                                </th>
                                <th>
                                    <span>ПМ</span>
                                </th>
                                <th>
                                    <span>РМ</span>
                                </th>
                                <th>
                                    <span>О</span>
                                </th>
                                <th>
                                    <span>Б</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="fz-big">1</span>
                                </td>
                                <td class="color">
                                    <span style="background-color: #F7E10E;">
                                    </span>
                                </td>
                                <td>
                                    <span class="team fz-big">ДП АНТОНОВ</span>
                                </td>
                                <td>
                                    <span>11</span>
                                </td>
                                <td>
                                    <span>4</span>
                                </td>
                                <td>
                                    <span>7</span>
                                </td>
                                <td>
                                    <span class="border">87</span>
                                </td>
                                <td>
                                    <span class="border">65</span>
                                </td>
                                <td>
                                    <span class="border">16</span>
                                </td>
                                <td>
                                    <span class="blue-bg">27</span>
                                </td>
                                <td>
                                    <span class="gray-bg">3</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fz-big">2</span>
                                </td>
                                <td class="color">
                                    <span style="background-color: #59C65D;">
                                    </span>
                                </td>
                                <td>
                                    <span class="team fz-big">ДП АНТОНОВ</span>
                                </td>
                                <td>
                                    <span>11</span>
                                </td>
                                <td>
                                    <span>4</span>
                                </td>
                                <td>
                                    <span>7</span>
                                </td>
                                <td>
                                    <span class="border">87</span>
                                </td>
                                <td>
                                    <span class="border">65</span>
                                </td>
                                <td>
                                    <span class="border">16</span>
                                </td>
                                <td>
                                    <span class="blue-bg">27</span>
                                </td>
                                <td>
                                    <span class="gray-bg">3</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="fz-big">3</span>
                                </td>
                                <td class="color">
                                    <span style="background-color: #ff0000;">
                                    </span>
                                </td>
                                <td>
                                    <span class="team fz-big">ДП АНТОНОВ</span>
                                </td>
                                <td>
                                    <span>11</span>
                                </td>
                                <td>
                                    <span>4</span>
                                </td>
                                <td>
                                    <span>7</span>
                                </td>
                                <td>
                                    <span class="border">87</span>
                                </td>
                                <td>
                                    <span class="border">65</span>
                                </td>
                                <td>
                                    <span class="border">16</span>
                                </td>
                                <td>
                                    <span class="blue-bg">27</span>
                                </td>
                                <td>
                                    <span class="gray-bg">3</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>            
        @endif

        @livewire('tournament-protocol', ['teams' => $teams, 'eventId' => $eventId])
       
        @if (!empty($topPlayersByVote))
            <section class="home__best-players players-section">
                <h2 class="players-section__title section-title section-title--margin">
                    НАЙКРАЩІ Гравці за результатами голосування
                </h2>
                <div class="players-section__body">
                    <div class="players-section__items">
                        @foreach ($topPlayersByVote as $topPlayer)
                            <article style="--color: {{$topPlayer['team_color']}}" class="players-section__item item-player">
                                <div class="item-player__position">
                                    {{$loop->iteration}} місце
                                </div>
                                <a href="#" class="item-player__image-link">
                                    <img src="{{asset('storage/' . $topPlayer['player']->photo)}}" alt="{{$topPlayer['player']->full_name}}" class="ibg">
                                </a>
                                <div class="item-player__details">
                                    <div class="item-player__info">
                                        {{-- {{$playerOfSeries[$topAssist->player->id]['series_count'] ?? 0}} --}}
                                        1
                                        <img src="img/player/field.webp" alt="Кільсть серій" class="ibg ibg--contain">
                                    </div>
                                    <div class="item-player__info">
                                        {{$topPlayer['votes']}}
                                        <img src="img/player/shoe.svg" alt="Кількість асистентів" class="ibg ibg--contain">
                                    </div>
                                </div>
                                <div class="item-player__name">
                                    <div>{{$topPlayer['player']->full_name}}</div>
                                </div>
                                
                                <x-player-rating 
                                    :rating="$topPlayer['player']->rating" 
                                    :verifyrating="$topPlayer['player']->verify_rating"
                                />

                            </article>
                            
                        @endforeach
                        
                    </div>
                </div>
            </section>
        @endif
    
        <section class="home__teams teams-section">
            <h2 class="teams-section__title section-title section-title--margin">
                Склади команд на гру
            </h2>
            <div class="teams-section__body">
                @foreach ($teams as $team)
                <div style="--color: {{$team->color->color_picker}}" class="teams-section__item item-team-home">

                    <x-player-rating :rating="$team->rating" class="item-team-home__team-rating rating" />

                    <div class="item-team-home__header">
                        <div class="item-team-home__title">
                            №
                        </div>
                        <div class="item-team-home__title">
                            ГРАВЕЦЬ
                        </div>
                        <div class="item-team-home__icon">
                            <img src="img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                        </div>
                        <div class="item-team-home__icon">
                            <img src="img/player/shoe.svg" alt="Image" class="ibg ibg--contain">
                        </div>
                    </div>
     
                    @if (isset($seriesPlayers[$team->id]))
                        <ol class="item-team-home__list">
                            @foreach ($seriesPlayers[$team->id] as $player)
                                <li class="item-team-home__member">
                                    <div class="item-team-home__circle">
                                        <img src="{{asset('storage/'. $player->player->photo)}}" alt="{{$player->player->full_name}}">
                                    </div>
                                    {{-- <span class="active">{{$player->player->first_name}} {{$player->player->last_name}}</span> --}}
                                    <span class="">{{$player->player->full_name}}</span>
                                    <div class="item-team-home__value">2</div>
                                    <div class="item-team-home__value">0</div>
                                </li>    
                            @endforeach
                        </ol>    
                                        
                    @endif
                </div>
                @endforeach
            </div>      
        </section>
        <section class="home__gallery gallery-home">
            <h2 class="gallery-home__title section-title section-title--margin">
                Фото команд
            </h2>
            <div class="gallery-home__items">
                <img src="img/gallery/01.webp" alt="Image">
                <img src="img/gallery/02.webp" alt="Image">
                <img src="img/gallery/03.webp" alt="Image">
            </div>
        </section>
    </div>
    @endif
</div>
