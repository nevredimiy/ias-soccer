<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Livewire\Attributes\On;
use Carbon\Carbon;


class PlayerRequest extends Component
{
    public $application;
    public $player;
    public $team;

    public $applicationId;
    public $expiresAt;

    public function mount($application, $player)
    {
        
        $this->application = $application;
        $team = Team::findOrFail($application['team_id']);
        $this->team = $team;
        $this->player = $player;

        $this->expiresAt = Carbon::parse($application['created_at'])
        ->addDays($team->application_lifetime_days)
        ->addHours($team->application_lifetime_hours)
        ->addMinutes($team->application_lifetime_minutes);
    }

    
    #[On('applicationLifetimeUpdated')]
    public function updateApplicationLifetime()
    {
        $team = Team::find($this->application['team_id']);
    
        $this->expiresAt = Carbon::parse($this->application['created_at'])
            ->addDays($team->application_lifetime_days)
            ->addHours($team->application_lifetime_hours)
            ->addMinutes($team->application_lifetime_minutes);
    }
    

    
    public function render()
    {
        return view('livewire.player-request');
    }
}
