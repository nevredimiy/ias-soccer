<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\TeamPlayerApplication;
use Livewire\Attributes\On;

class JoinTeamList extends Component
{
    public $team_id = '';
    public ?Team $team = null;
    public array $playerApplications = [];
    
    public function mount($team)
    {
        $this->team = $team;
        $this->team_id = $team->id;
        $this->updatePlayerApplications($team->id);
        
    }
    
    #[On('team-selected')]
    public function updatePlayerApplications($team_id)
    {
        
        $this->playerApplications = TeamPlayerApplication::with('user.player')
            ->where('team_id', $team_id)->get()->toArray();

    }

    #[On('applicationsFiltered')]
    public function updateApplications($playerApplications)
    {
        $this->playerApplications = $playerApplications;
    }

    public function render()
    {
        return view('livewire.join-team-list');
    }
}
