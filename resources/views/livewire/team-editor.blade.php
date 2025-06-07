<div>
    <button wire:click="toggleEditing" class="button button--blue">Редагувати {{ $team->name }}</button>
    @if($isEditing)
        <div class="w-full bg-gray-100 p-4 rounded-lg shadow-md relative">
            <button wire:click="toggleEditing" class="absolute top-2 right-2 text-red-500">✖</button>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
           
            <div class="team-card__logo">
                <img src="{{ asset('storage/' . $team->logo) }}" alt="Логотип" class="ibg ibg--contain">
            </div>

            <div class="flex gap-2 flex-col mb-4">
                <input class="block mb-2 relative p-3 w-full border border-gray-300 rounded" type="text" wire:model="name">
                <button wire:click="updateName" class="button button--blue">ЗМІНИТИ НАЗВУ</button>
            </div>


            <div class="mb-3">
                <label for="formFile" class="block mb-2 relative p-3 w-full border border-gray-300 rounded">
                    <input wire:model="logo" name="logo" class="absolute inset-0 opacity-0" type="file" id="formFile">
                    ВИБРАТИ ЛОГОТИП
                </label>

                @if($logoFileName)  
                    <div class="my-1 text-gray-600 text-center">{{ $logoFileName }}</div>  
                @endif  

                @error('logo')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <button wire:click="updateLogo" class="button button--green">ЗМІНИТИ ЛОГОТИП</button>
            </div>

            @if (session()->has('success'))
                <p class="bg-green-200 text-center py-4">{{ session('success') }}</p>
            @endif
            
        </div>
    @endif
    
</div>
