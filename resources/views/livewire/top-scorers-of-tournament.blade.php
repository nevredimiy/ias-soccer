<section class="home__best-players players-section">
    <h2 class="players-section__title section-title section-title--margin">
        НАЙКРАЩІ БОМБАРДИРИ ЛІГИ
    </h2>
    <div class="players-section__body">
        <div class="players-section__items">
            @foreach ($topScorers as $topScorer)
                <article style="--color: {{$topScorer->team->color->color_picker}}" class="players-section__item item-player">
                    <div class="item-player__position">
                        {{$loop->iteration}} місце
                    </div>
                    <a href="#" class="item-player__image-link">
                        <img src="{{asset('storage/' . $topScorer->player->photo)}}" alt="{{$topScorer->player->full_name}}" class="ibg">
                    </a>
                    <div class="item-player__details">
                        <div class="item-player__info">
                            {{$playerOfSeries[$topScorer->player->id]['series_count'] ?? 0}}
                            <img src="img/player/field.webp" alt="Кількість серій" class="ibg ibg--contain">
                        </div>
                        <div class="item-player__info">
                            {{$topScorer->goals_count}}
                            
                            <img src="img/player/ball.webp" alt="Кількість абитих м'ячів" class="ibg ibg--contain">
                        </div>
                    </div>
                    <div class="item-player__name">
                        <div>{{$topScorer->player->full_name}}</div>
                    </div>
                    
                    <x-player-rating 
                        :rating="$topScorer->player->rating"
                        :verifyrating="$topScorer->player->verify_rating"
                    />

                </article>
                
            @endforeach
            
        </div>
    </div>

    <h2 class="players-section__title section-title section-title--margin">
        НАЙКРАЩІ АСИСТЕНТИ ЛІГИ
    </h2>
    <div class="players-section__body">
        <div class="players-section__items">
            @foreach ($topAssists as $topAssist)
                <article style="--color: {{$topAssist->team->color->color_picker}}" class="players-section__item item-player">
                    <div class="item-player__position">
                        {{$loop->iteration}} місце
                    </div>
                    <a href="#" class="item-player__image-link">
                        <img src="{{asset('storage/' . $topAssist->player->photo)}}" alt="{{$topAssist->player->full_name}}" class="ibg">
                    </a>
                    <div class="item-player__details">
                        <div class="item-player__info">
                            {{$playerOfSeries[$topAssist->player->id]['series_count'] ?? 0}}
                            <img src="img/player/field.webp" alt="Кільсть серій" class="ibg ibg--contain">
                        </div>
                        <div class="item-player__info">
                            {{$topAssist->assists_count}}
                            <img src="img/player/shoe.svg" alt="Кількість асистентів" class="ibg ibg--contain">
                        </div>
                    </div>
                    <div class="item-player__name">
                        <div>{{$topAssist->player->full_name}}</div>
                    </div>

                    <x-player-rating 
                        :rating="$topAssist->player->rating" 
                        :verifyrating="$topAssist->player->verify_rating"
                    />

                </article>
                
            @endforeach
            
        </div>
    </div>

</section>