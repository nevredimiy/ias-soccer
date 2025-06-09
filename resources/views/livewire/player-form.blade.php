<div>
    @if (session()->has('message'))
        <div class="text-green-600 mb-4">{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="submit" enctype="multipart/form-data" class="formbase">
        
           <div class="formbase__group">
                <div class="formbase__field">
                    <div class="formbase__label">Прізвище</div>
                    <input 
                        type="text" 
                        placeholder="Прізвище" 
                        wire:model.defer="lastname" 
                        class="formbase__input input @error('lastname') error @enderror"
                    >
                    @error('lastname') <span class="error text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="formbase__field @error('firstname') error @enderror">
                    <div class="formbase__label">ІМ’Я</div>
                    <input 
                        type="text" 
                        placeholder="ІМ’Я" 
                        wire:model.defer="firstname" 
                        class="formbase__input input @error('firstname') error @enderror"
                    >
                    @error('firstname') <span class="error text-red-600">{{ $message }}</span> @enderror
                </div>
                <div class="formbase__field col-span-full">
                    <div class="formbase__label">ДАТА НАРОДЖЕННЯ</div>
                    <div class="formbase__date">
                        <input 
                            type="number" 
                            min="1" 
                            max="31" 
                            placeholder="01" 
                            wire:model.defer="day" 
                            class="formbase__input input @error('day') error @enderror"
                        >
                        <input 
                            type="number" 
                            min="1" 
                            max="12" 
                            placeholder="01" 
                            wire:model.defer="month" 
                            class="formbase__input input @error('month') error @enderror"
                        >
                        <input 
                            type="text" 
                            pattern="\d{4}" 
                            placeholder="1988" 
                            wire:model.defer="year" 
                            class="formbase__input input @error('year') error @enderror"
                        >
                    </div>
                    @error('day') <span class="error text-red-600">{{ $message }}</span> @enderror
                    @error('month') <span class="error text-red-600">{{ $message }}</span> @enderror
                    @error('year') <span class="error text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- <div class="account__field mt-4">
                <div class="account__label">Фото</div>
                <input type="file" wire:model="photo" accept="image/*">
                @error('photo') <span class="error text-red-600">{{ $message }}</span> @enderror

                @if ($photo)
                    <div id="photoPreviewContainer" style="margin-top: 10px;">
                        <img id="photoPreview" src="{{ $photo->temporaryUrl() }}" alt="Preview" style="max-width: 200px;">
                    </div>
                @endif
            </div> --}}
        

        <div class="account__rating-set">
            <div class="account__rating-block">
                <div class="formbase__label">
                    *ОБРАТИ СВІЙ РІВЕНЬ
                </div>
                <div class="account__rating rating">
                    
                   <div class="rating__items">
                        @for ($i = 1; $i <= 10; $i++)
                            <label class="rating__item {{ $rating >= $i ? 'rating__item--active' : '' }}" wire:click="setRating({{ $i }})">
                                <input class="rating__input" type="radio" name="rating" value="{{ $i }}" hidden>
                                <svg width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0.669408 7.82732C0.398804 7.57384 0.545797 7.11561 0.911812 7.07165L6.09806 6.44858C6.24723 6.43066 6.3768 6.33578 6.43972 6.19761L8.62721 1.39406C8.78159 1.05505 9.25739 1.05499 9.41177 1.39399L11.5993 6.19751C11.6622 6.33568 11.7909 6.43082 11.9401 6.44873L17.1266 7.07165C17.4926 7.11561 17.6392 7.57398 17.3686 7.82745L13.5347 11.4193C13.4244 11.5226 13.3753 11.6764 13.4046 11.8256L14.4221 17.0141C14.4939 17.3803 14.1092 17.664 13.7876 17.4816L9.23042 14.8972C9.09934 14.8228 8.94009 14.8232 8.80901 14.8975L4.25138 17.481C3.92976 17.6633 3.54432 17.3802 3.61615 17.0141L4.63382 11.8259C4.66309 11.6767 4.61412 11.5226 4.50383 11.4193L0.669408 7.82732Z" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </label>
                        @endfor
                    </div>

                </div>
                @error('rating') <span class="error text-red-600">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="formbase__group">
             <div class="formbase__field">
                <div class="formbase__label">НОМЕР ТЕЛЕФОНУ</div>
                <input 
                    type="text" 
                    placeholder="+380XXXXXXXXX" 
                    wire:model.defer="phone" 
                    class="formbase__input input @error('phone') error @enderror"
                >
                @error('phone') <span class="error text-red-600">{{ $message }}</span> @enderror
            </div>

            <div class="formbase__field">
                <div class="formbase__label">НІКНЕЙМ В TELEGRAM</div>
                <input 
                    type="text" 
                    placeholder="@MAMEDOV1988" 
                    wire:model.defer="tg" 
                    class="formbase__input input @error('tg') error @enderror"
                >
                @error('tg') <span class="error text-red-600">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="formbase__button button button--green col-span-full">
                <span>СТВОРИТИ КАБІНЕТ ГРАВЦЯ</span>
            </button>
        </div>
    </form>
</div>


 
