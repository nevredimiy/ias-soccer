<div>
    <div wire:modal.live='city' data-da=".header__images, 767.98, last" class="header__city">
        {{$city->name}}
        <img src="{{ asset($city->logo) }}" alt="Image" class="ibg ibg--contain">
    </div>
</div>
