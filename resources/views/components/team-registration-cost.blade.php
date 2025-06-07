
<section class="tournament__price price-tournament">
    <h2 class="price-tournament__title section-title section-title section-title--margin">
        ВАРТІСТЬ РЕЄСТРАЦІЇ КОМАНДИ
    </h2>

   
    <div class="price-tournament__body">
        @for ($block = 0; $block < ceil($countTeams / 4); $block++)
            <div class="price-tournament__block">
                @for ($i = $block * 4; $i < min(($block + 1) * 4, $countTeams); $i++)
                    <div class="price-tournament__item">
                        <span>
                            КОМАНДА {{ $i + 1 }}
                        </span>
                        <span>
                            {{ $prices[$i]['price'] ?? 0 }} ГРН
                        </span>
                    </div>
                @endfor
            </div>
        @endfor
    </div>
    
</section>