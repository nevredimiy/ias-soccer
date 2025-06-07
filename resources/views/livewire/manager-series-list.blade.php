<div class="">
    <hr />
    <div class="flex justify-center mt-4 gap-2">
        <select class="bg-[#00539f] text-white py-1.5 px-2 min-w-44 rounded mb-2" wire:model.live="selectedEvent" name="events">
            <option value="0">Вибери подію</option>
            @foreach ($events as $key => $event)            
                <option wire:key="{{$key}}" value="{{ $event->id }}"
                    @if(session()->has('current_event') && session('current_event') == $event->id) selected @endif
                >
                    Подія {{ $event->id }} - {{ $event->name }} 
                </option>
            @endforeach   
        </select>
       
    </div>

    <ul class="grid grid-cols-1 justify-items-center md:grid-cols-2 lg:grid-cols-3 mb-4">
        @forelse ($seriesMetasGroup ?? [] as $round => $seriaMeta)
            <li class="border rounded p-2 mb-2 flex flex-col gap-2"  wire:key="{{$round}}">
              
                <p>Тур {{$round}}</p>
                @foreach ( $seriaMeta as $item )
                <a class="button button--blue" href="{{route('manager.series.show', ['id' => $item->id])}}" wire:key="{{$item->id}}" class="">
                    Серія {{$item->series}}-Початок {{\Carbon\Carbon::parse($item->start_date)->format('H:i - d.m.Y')}}                    
                </a>
                @endforeach

                
            </li>                    
        @empty
            <li>Немає серій</li>
        @endforelse      
    </ul>
    
</div>
