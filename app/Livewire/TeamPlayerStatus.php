<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Livewire\Attributes\On; 

class TeamPlayerStatus extends Component
{
    public ?Team $team = null;
    public $player_request_status;

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->player_request_status = $team->player_request_status;
    }

    public function updateStatus()
    {
        if ($this->team) {
            $this->team->update(['player_request_status' => $this->player_request_status]);
            $this->dispatch('statusUpdated');
        }
    }

    public function render()
    {
        return view('livewire.team-player-status');
    }
}
