@extends('layouts.planshet')

@section('title', 'Дані матча')

<div class="flex justify-center mt-2">
   <a href="{{route('manager.series')}}" class="button button--blue mb-4 text-center">Повернутися до списку серій</a>
</div>

@livewire('manager-event-type')
@livewire('manager-event-actions', ['id' => $id])

@livewire('manager-event-players', [
        'teamIdsInSeries' => $teamIdsInSeries,
        'playersByNumberByTeamId' => $playersByNumberByTeamId,
        'teamColors' => $teamColors,
        'templateMatches' => $templateMatches,
    ]
)

@livewire('manager-event-matches', [
        'event' => $event, 
        'teamColors' => $teamColors,
        'templateMatches' => $templateMatches,
        'teamIdsInSeries' => $teamIdsInSeries,
        'seriesMeta' => $seriesMeta,
    ]
)

