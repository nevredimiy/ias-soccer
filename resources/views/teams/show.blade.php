@extends('layouts.app')

@section('title', 'Створення команди')

@section('content')

    <div class="page__container">
        <div class="page__wrapper">
            <div class="page__team team">
                <div class="team__block _block">
                    <section class="team__hero hero-team">
                        <h2 class="hero-team__title section-title section-title--margin">
                            Меридіан - Суперліга
                        </h2>
                        <div class="hero-team__body">
                            <div class="hero-team__card team-card">
                                <h3 class="team-card__name">{{$team->name ?? ''}}</h3>
                                <div class="team-card__logo">
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{$team->name ?? 'Логотип команди'}}" class="ibg ibg--contain">
                                </div>
                                
                                <div style="--color: {{$team->color->color_picker}}" data-rating data-rating-size="10" data-rating-value="{{$rating}}" class="team-card__rating rating">
                                </div>

                            </div>
                            <div class="hero-team__image">
                                {{-- <img src="{{ asset($team->group_photo ? 'storage/'.$team->group_photo : 'img/gallery/01.webp') }}" alt="{{$team->name ?? 'Групове фото команди'}}" class="ibg"> --}}
                                <img 
                                    src="{{ asset($team->group_photo ? 'storage/'.$team->group_photo : 'storage/img/team_group_photo/group_placeholder.jpg') }}" 
                                    alt="{{$team->name ?? 'Групове фото команди'}}" 
                                    class="ibg"
                                >
                            </div>
                        </div>
                    </section>
                    <section class="team__calendar calendar-team">
                        <h2 class="calendar-team__title section-title section-title--margin">
                            КАЛЕНДАР
                        </h2>
                        <div class="calendar-team__items">

                            @foreach ($team->event->seriesMeta as $seriesMeta)
                                <div class="calendar-team__item match-info">
                                    <div class="match-info__details">
                                        <div class="match-info__label">
                                            {{\Carbon\Carbon::parse($seriesMeta->start_date)->locale('uk')->translatedFormat('d F')}}
                                        </div>
                                        <div class="match-info__label">
                                            {{\Carbon\Carbon::parse($seriesMeta->start_date)->locale('uk')->translatedFormat('l')}}
                                        </div>
                                        <div class="match-info__label">
                                            {{\Carbon\Carbon::parse($seriesMeta->start_date)->format('H:i')}}
                                        </div>
                                        <div class="match-info__label">
                                            {{$seriesMeta->round}} ТУР
                                        </div>
                                        <div class="match-info__label">
                                            СЕРІЯ {{$seriesMeta->series}}
                                        </div>
                                    </div>
                                    <div class="match-info__teams">
                                        @if($seriesMeta->seriesTeams->count() < $team->event->tournament->count_teams)
                                          <div class="match-info__team">
                                          </div>
                                          <div class="match-info__team">
                                          </div>
                                          <div class="match-info__team">
                                          </div>
                                        @endif
                                        @foreach ($seriesMeta->seriesTeams as $teamSeries)
                                        <div style="background-color: {{$teamSeries->team->color->color_picker}}" class="match-info__label blue-bg">
                                            {{$teamSeries->team->name}}
                                        </div>
                                            
                                        @endforeach
                                        
                                    </div>
                                </div>                                
                            @endforeach
                           
                        </div>
                    </section>

                    <section class="team__members members-team">
                        <h2 class="members-team__title section-title section-title--margin">
                            СКЛАД КОМАНДИ
                        </h2>
                        <div class="members-team__items">
                            @foreach ($team->players as $player)
                            <article class="item-player item-player--stats">
                                <a href="#" class="item-player__image-link">
                                    <img src="{{ asset($player->photo ? 'storage/'.$player->photo : '/img/player/player.webp') }}" alt="{{$player->full_name ?? 'фото гравця'}}" class="ibg">
                                </a>
                                <div class="item-player__name">
                                    <a href="#">{{$player->full_name}}</a>
                                </div>
                                <div class="item-player__details">
                                    <div class="item-player__info">
                                        31
                                        <img src="/img/player/field.webp" alt="Image" class="ibg ibg--contain">
                                    </div>
                                    <div class="item-player__info">
                                        {{$matchesEvents[$player->id]['goal'] ?? 0}}
                                        <img src="/img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                                    </div>
                                    <div class="item-player__info item-player__info--yellow-card">
                                         {{$matchesEvents[$player->id]['yellow_card'] ?? 0}}
                                    </div>
                                    <div class="item-player__info item-player__info--red-card">
                                        {{$matchesEvents[$player->id]['red_card'] ?? 0}}
                                    </div>
                                </div>
                                <div data-rating data-rating-size="10" data-rating-value="{{$player->rating}}" class="item-player__rating rating">
                                </div>
                            </article>                                
                            @endforeach
                           
                        </div>
                    </section>
                    <section class="team__members members-team">
                        <h2 class="members-team__title section-title section-title--margin">
                            ДИСКВАЛІФІКАЦІЇ
                        </h2>
                        <div class="members-team__items">
                            <article class="item-player item-player--stats">
                                <a href="#" class="item-player__image-link">
                                    <img src="/img/player/player.webp" alt="Image" class="ibg">
                                </a>
                                <div class="item-player__name">
                                    <a href="#">МАКСИМ МАМЕДОВ</a>
                                </div>
                                <div class="item-player__details">
                                    <div class="item-player__info">
                                        31
                                        <img src="/img/player/field.webp" alt="Image" class="ibg ibg--contain">
                                    </div>
                                    <div class="item-player__info">
                                        28
                                        <img src="/img/player/ball.webp" alt="Image" class="ibg ibg--contain">
                                    </div>
                                    <div class="item-player__info item-player__info--yellow-card">
                                        1
                                    </div>
                                    <div class="item-player__info item-player__info--red-card">
                                        0
                                    </div>
                                </div>
                                <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                </div>
                            </article>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>



@endsection