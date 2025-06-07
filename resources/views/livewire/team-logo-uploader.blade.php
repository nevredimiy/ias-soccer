<div class="team-setup__logo">
    <div class="team-setup__image">
        @if ($logo)
            <img src="{{ $logo->temporaryUrl() }}" alt="Logo" class="ibg ibg--contain">
        @else
            <img src="{{ asset('img/header/logo.svg') }}" alt="Logo" class="ibg ibg--contain">
        @endif
    </div>
    <label class="team-setup__upload button button--blue upload-btn">
        <input name="logo" type="file" wire:model="logo" accept="image/webp, image/jpeg, image/png, image/gif, image/svg+xml">
        <span>Завантажити ЛОГОТИП</span>
    </label>

    @error('logo') 
        <span class="error">{{ $message }}</span> 
    @enderror
</div>
