<div>
   
    <div class="flex justify-center gap-4 mt-4">
   
        @foreach(['football' => 'Гол', 'boots-icon' => 'Асист', 'red-football' => 'Автогол', 'yellow-card-icon' => 'ЖК', 'red-card-icon' => 'ЧК'] as $key => $label)
            <label wire:key="{{$key}}" wire:click="selecteEventType('{{ $key }}')" wire:key="{{ $key }}">
                <div class="
                    bg-orange-100 
                    flex flex-col justify-end items-center 
                    gap-2 uppercase rounded p-2 w-30 h-40 cursor-pointer 
                    hover:border-black
                    {{ $activeKey === $key ? 'active-type' : '' }}
                ">
                    <img width="34" height="34" class="w-20 @if($key=='yellow-card-icon' || $key == 'red-card-icon') max-w-12 @endif" src="{{ asset('img/icons/' . $key . '.png') }}" alt="{{ $label }}">
                    {{ $label }}
                </div>
            </label>
        @endforeach
   
    </div>

   
</div>
