<?php

namespace App\Livewire;

use Livewire\Component;

class TeamComposition extends Component
{
    
    public $team = null;
    public $players = null;

    public function mount($team)
    {
        $this->team = $team;
        $this->players = $team->players()->get();
    }
    
    public function render()
    {
        return view('livewire.team-composition');
    }
}
