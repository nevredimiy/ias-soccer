 <div class="">
     @if (session()->has('error'))
            <p class="text-red-500 text-center my-4 ">{{session('error')}}</p>
    @endif
      @if (session()->has('success'))
            <p class="text-green-500 text-center my-4 ">{{session('success')}}</p>
    @endif

    

    <div class="flex justify-center gap-2 my-4">
        <button            
            wire:click="addEvent" 
            @class([
                'button button--black',
                'opacity-50' => $seriesMeta->status !== 'open',
            ])
            @disabled($seriesMeta->status !== 'open')
        >
            Додати подію
        </button>
        <button 
            wire:click="deleteEvent" 
            @class([
                'button button--black',
                'opacity-50' => $seriesMeta->status !== 'open',
            ])
            @disabled($seriesMeta->status !== 'open')
        >
            Крок назад
        </button>
        @if ($seriesMeta->status == 'open')
        <button wire:click="seriesClosed" wire:confirm="Ви впевнені, що хочете закрити серію?" class="button button--black">Завершити серію</button>            
        @else
        <button wire:click="seriesOpen" wire:confirm="Ви впевнені, що хочете відкрити серію?" class="button button--black">Відкрити серію</button>            
        @endif

        @if ($isActiveVoteButton && $seriesMeta->event->tournament->type != 'team')
            <a href="{{route('manager.series.vote', $seriesId)}}" class="button button--red outline outline-offset-2">Голосування</a>            
        @endif
        
    </div>
 </div>