<div class="prop-profile__block">
    <h2 class="prop-profile__title section-title section-title--margin">
        ТРАНСФЕРНІ ФІЛЬТРИ
    </h2>
    <div class="prop-profile__filters filters-prop">
        <div class="filters-prop__block">
            <h3 class="filters-prop__title label-prop">
                ВІК ГРАВЦЯ
            </h3>
            <div class="filters-prop__body">
                <div class="filters-prop__label">ВІД</div>
                <div class="filters-prop__field">
                    <input type="number" wire:model.live="minAge" min="16" class="input">
                </div>
                <div class="filters-prop__label ">ДО</div>
                <div class="filters-prop__field">
                    <input type="number" wire:model.live="maxAge"  min="16" placeholder="немає" class="input">
                </div>
            </div>
        </div>
        <div class="filters-prop__block">
            <h3 class="filters-prop__title label-prop">
                РІВЕНЬ
            </h3>
            <div class="filters-prop__body">
                <div class="filters-prop__label">ВІД</div>
                <div class="filters-prop__field">
                    <input type="number" wire:model.live="minRating" min="1" max="10" class="input">
                </div>
            </div>
        </div>
        
    </div>

    
</div>
