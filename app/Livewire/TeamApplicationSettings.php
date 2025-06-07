<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Carbon\Carbon;  
use Illuminate\Support\Facades\Auth;

class TeamApplicationSettings extends Component
{

    public $team;
    public $days;
    public $hours;
    public $minutes;


    public function mount(Team $team)
    {
        $this->team = $team;
        $this->days = $team->application_lifetime_days;
        $this->hours = $team->application_lifetime_hours;
        $this->minutes = $team->application_lifetime_minutes;

    }

    public function save()
    {
        $this->validate([
            'days' => 'required|integer|min:0',
            'hours' => 'required|integer|min:0|max:23',
            'minutes' => 'required|integer|min:0|max:59',
        ]);

        $this->team->update([
            'application_lifetime_days' => $this->days,
            'application_lifetime_hours' => $this->hours,
            'application_lifetime_minutes' => $this->minutes,
        ]);

        $this->dispatch('applicationLifetimeUpdated');

        session()->flash('success', 'Налаштування часу заявки оновлено.');
    }

    
    public function render()
    {
        return view('livewire.team-application-settings');
    }
}
