<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On; 


class TeamDetails extends Component
{
    public ?Team $team = null;
    public $teams;
    public $teamsWithApplications;    
    public $userId;    
    
   public function mount()
    {
        $this->userId = Auth::id();

        $playerId = Player::where('user_id', $this->userId)->value('id');

        $playerTeamIds = PlayerTeam::where('player_id', $playerId)->pluck('team_id');

        $this->teams = Team::with(['event.tournament', 'color'])
            ->where('owner_id', $this->userId)
            ->orWhereIn('id', $playerTeamIds)
            ->orderByDesc('id')
            ->get();

        $selectedTeamId = session('selected-team');

        if ($selectedTeamId) {
            $this->team = $this->teams->find($selectedTeamId);
        } else {
            $this->team = $this->teams->first();
        }

        $this->teamsWithApplications = $this->teams->load('applications.user.player');
    }


    #[On('team-selected')]
    public function updateTeam($team_id)
    {
        $this->team = Team::find($team_id);
    }   


    public function render()
    {
        return view('livewire.team-details');
    }
}
