<div class="cities__items">
    @foreach ($cities as $city)
        <a href="#" wire:click.prevent="setCity({{ $city->id }})" class="cities__item
            @if ($selectedCity == $city->id) _active @endif">
            <div class="cities__image">
                <img src="{{ asset($city->logo) }}" class="ibg ibg--contain" alt="Image">
            </div>
            <div class="cities__label">
                {{ $city->name }}
            </div>
        </a>
    @endforeach
</div>
