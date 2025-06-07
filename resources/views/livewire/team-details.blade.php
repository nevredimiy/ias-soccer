<div class="">
    <div class="">
        @if ($team->event->tournament->type == 'team')                 
        
        <livewire:places-of-series :team="$team" />

        @else

        @livewire('team-composition', ['team' => $team], key('team-composition-'.$team->id))
        @endif
     
    </div>

    @if($team && $team->owner_id == $userId)
    <div class="mt-4 p-4 border rounded bg-white">
    
        <div class="text-center mb-2">
            <h2 class="text-xl font-bold">Команда: {{ $team->name }}</h2>
            <p>Статус: {{ $team->status }}</p>
            <p>Цвет: {{ $team->color->name ?? 'Не указан' }}</p>
    
        </div>
    
       {{-- @livewire('team-players', ['team_id' => $team->id]) --}}
       <livewire:team-players :team_id="$team->id" />

        <div class="profile__prop prop-profile">
            <livewire:team-max-players :team="$team" />
            <livewire:join-team-list :team="$team" />
            <livewire:transfer-filter :team="$team" />    
            <livewire:team-application-settings :team="$team" />
        </div>

        <livewire:team-player-status :team="$team" wire:key="team-player-status-{{ $team->id }}"/>
    
   
    </div>
    @endif
</div>
