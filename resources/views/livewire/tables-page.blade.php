<div class="">
    <text class="uppercase block mb-4 text-center font-bold text-3xl text-gray-400">Командні турніри</text>
    @foreach($eventTeams as $eventTeam)
        @livewire('tournament-table-team', ['teams' => $eventTeam->teams, 'eventId' => $eventTeam->id])
    @endforeach

    <text class="uppercase block mb-4 text-center font-bold text-3xl text-gray-400">Індивідуальні турніри</text>
    @foreach($eventIndividuals as $eventIndividual)
         @livewire('tournament-table-individual', ['teams' => $eventIndividual->teams, 'eventId' => $eventIndividual->id])    
    @endforeach
</div>