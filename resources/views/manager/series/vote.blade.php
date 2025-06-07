@extends('layouts.planshet')

@section('title', 'Дані матча')

<div class="flex flex-col items-center justify-center mt-2">
   <a href="{{route('manager.series')}}" class="button button--blue mb-4 text-center">Повернутися до списку серій</a>
   <a href="{{route('manager.series.show', $id)}}" class="button button--blue mb-4 text-center">Повернутися до протоколу матчів</a>
</div>

<div class="flex flex-col items-center justify-center">
    
    @livewire('manager-vote-players', [
            'series' => $series,
            'playersTeam' => $playersTeam
        ]
    )

</div>
