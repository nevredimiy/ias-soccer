<section class="tournament__players players-tournament">
    <h2 class="players-tournament__title section-title section-title--margin">
        Гравців зареєстровано
    </h2>
  
    <div class="players-tournament__items">
        @if ($players->isEmpty())
            <div class="text-center text-xl font-bold text-red-500">
                В цій команді немає жодного гравця
            </div>
        @else
            <div style="--color: {{$team->color->color_picker}}" class="players-tournament__team">
                @foreach ($players as $player)
                    <div wire:key="{{$player['id']}}" data-player-id="{{$player['id']}}" class="players-tournament__item">
                        <article class="item-player item-player--stats">
                            <a href="#" class="item-player__image-link">
                                <img src="{{asset('storage/' . $player['photo'])}}" alt="{{$player['last_name']}} {{$player['first_name']}}" class="ibg">
                            </a>
                            <div class="item-player__name">
                                <a href="#">{{$player['last_name']}} {{$player['first_name']}}</a>
                            </div>

                            <x-player-rating 
                                :rating="$player['rating']"
                                :verifyrating="$player->verify_rating"
                            />
                            
                        </article>
                    </div>                
                @endforeach
                
            </div>
        @endif
    </div>
</section>