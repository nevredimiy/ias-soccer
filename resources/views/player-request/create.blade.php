@extends('layouts.app')

@section('title', 'Заявка гравця')

@section('content')

<div class="page__container">
    <div class="page__wrapper">
        <div class="page__tournament tournament">
            <div class="tournament__block _block">
                <div class="tournament__hero hero-tournament">
                    <div class="hero-tournament__location item-stadium">
                        <h3 class="item-stadium__title">
                            БЕРКОВЩИНА
                        </h3>
                        <div class="item-stadium__image">
                            <img src="{{ asset('img/stadium/preview.webp')}}" alt="Image" class="ibg">
                        </div>
                        <div class="item-stadium__body">
                            <div class="item-stadium__location _icon-location">
                                Пр-т Степана Бандери 19 (Парк Муромець)
                            </div>
                            <div class="item-stadium__details">
                                <div class="item-stadium__info info-stadium">
                                    4
                                    <div class="info-stadium__icon">
                                        <div class="info-stadium__field">
                                            40х20
                                            <img src="{{ asset('img/stadium/01.webp')}}" alt="Image">
                                        </div>
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium">
                                    1
                                    <div class="info-stadium__icon">
                                        <div class="info-stadium__field">
                                            60x40
                                            <img src="{{ asset('img/stadium/01.webp')}}" alt="Image">
                                        </div>
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium">
                                    50
                                    <div class="info-stadium__icon">
                                        <img src="{{ asset('img/stadium/02.webp')}}" alt="Image">
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium _icon-check">
                                    <div class="info-stadium__icon">
                                        <img src="{{ asset('img/stadium/03.webp')}}" alt="Image">
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium _icon-check">
                                    <div class="info-stadium__icon">
                                        <img src="{{ asset('img/stadium/04.webp')}}" alt="Image">
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium">
                                    3
                                    <div class="info-stadium__icon">
                                        <img src="{{ asset('img/stadium/05.webp')}}" alt="Image">
                                    </div>
                                </div>
                                <div class="item-stadium__info info-stadium _icon-cross">
                                    <div class="info-stadium__icon">
                                        <img src="{{ asset('img/stadium/06.webp')}}" alt="Image">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hero-tournament__body">
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                X-PARK
                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                ОдноденнИЙ турнір
                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                27 ЧЕРВНЯ
                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                ФОРМАТ 5Х5Х5
                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label _icon-field">
                                40х20м
                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                Середній рівень гравців ЛІГИ
                            </div>
                            <div data-rating data-rating-size="10" data-rating-value="8" class="hero-tournament__rating rating">

                            </div>
                        </div>
                        <div class="hero-tournament__info">
                            <div class="hero-tournament__label">
                                ₴ <span>100 ГРН / ГРАВЕЦЬ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="tournament__schedule schedule-tournament">
                    <h2 class="schedule-tournament__title section-title section-title--margin">
                        РОЗКЛАД
                    </h2>
                    <div class="schedule-tournament__body">
                        <div class="schedule-tournament__block">
                            <div class="schedule-tournament__item schedule-tournament__item--info schedule-tournament__item--center">
                                <div class="schedule-tournament__label">
                                    27 ЧЕРВНЯ
                                </div>
                                <div class="schedule-tournament__label">
                                    ВІВТОРОК
                                </div>
                            </div>
                            <div class="schedule-tournament__item schedule-tournament__item--info">
                                <div class="schedule-tournament__label">
                                    ЧАС
                                </div>
                                <div class="schedule-tournament__label">
                                    19:00
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 1
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="yellow-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 2
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 3
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="yellow-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 4
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="yellow-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 5
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 6
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="yellow-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 7
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="yellow-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 8
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="blue-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                            <div class="schedule-tournament__item">
                                <div class="schedule-tournament__label">
                                    МАТЧ 9
                                </div>
                                <div class="schedule-tournament__colors">
                                    <span class="yellow-bg"></span>
                                    <span class="green-bg"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="tournament__players players-tournament">
                    <h2 class="players-tournament__title section-title section-title--margin">
                        Гравців зареєстровано
                    </h2>
                    <div data-prgs="18" data-prgs-value="13" class="players-tournament__progress">
                    </div>
                    <div class="players-tournament__items">
                        <div style="--color: #0053A0" class="players-tournament__team">
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <button class="players-tournament__empty">
                                    <span>
                                        ЗАЙНЯТИ МІСЦЕ
                                    </span>
                                </button>

                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                        </div>
                        <div style="--color: #F7E10E" class="players-tournament__team">
                            <div class="players-tournament__item">
                                <button class="players-tournament__empty">
                                    <span>
                                        ЗАЙНЯТИ МІСЦЕ
                                    </span>
                                </button>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <button class="players-tournament__empty">
                                    <span>
                                        ЗАЙНЯТИ МІСЦЕ
                                    </span>
                                </button>

                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                        </div>
                        <div style="--color: #59C65D" class="players-tournament__team">
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <button class="players-tournament__empty">
                                    <span>
                                        ЗАЙНЯТИ МІСЦЕ
                                    </span>
                                </button>

                            </div>
                            <div class="players-tournament__item">
                                <button class="players-tournament__empty">
                                    <span>
                                        ЗАЙНЯТИ МІСЦЕ
                                    </span>
                                </button>

                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                            <div class="players-tournament__item">
                                <article class="item-player item-player--stats">
                                    <a href="#" class="item-player__image-link">
                                        <img src="{{asset('img/player/player.webp')}}" alt="Image" class="ibg">
                                    </a>
                                    <div class="item-player__name">
                                        <a href="#">МАКСИМ МАМЕДОВ</a>
                                    </div>
                                    <div data-rating data-rating-size="10" data-rating-value="8" class="item-player__rating rating">
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection