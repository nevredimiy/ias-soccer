@extends('layouts.app')

@section('title', 'Заявка гравця')

@section('content')

<div class="page__container">
    <div class="page__wrapper">
        <div class="page__tournament tournament">
            <div class="tournament__block _block">
                
                @livewire('series-info', [
                    'seriesMeta' => $seriesMeta,
                    'event' => $event,
                    'playerPrice' => $playerPrice,
                    
                ], key($seriesMeta->id))
                
                {{-- @livewire('shedule-matches-tournament-one-day', ['event' => $event]) --}}
                @livewire('shedule-matches-tournament', ['event' => $event])

                @if ($event->tournament->type == 'solo')
                    @livewire('player-request-one', [
                        'event' => $event, 
                        'playerPrice' => $playerPrice,
                        'seriesMeta' => $seriesMeta
                        ])
                @elseif ($event->tournament->type == 'solo_private')
                    @livewire('player-request-one-private', ['event' => $event, 'playerPrice' => $playerPrice])
                @endif                   
                
            </div>
        </div>
    </div>
</div>
@endsection