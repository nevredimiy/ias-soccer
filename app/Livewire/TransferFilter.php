<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\TeamPlayerApplication;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TransferFilter extends Component
{
    public Team $team;
    public $team_id = null;
    public $minAge = 16;
    public $maxAge = null;
    public $minRating = 1;

    
    public function mount(Team $team)
    {
        $this->team = $team;

        $this->team_id = $team->id;
            
    }

    #[On('team-selected')]
    public function updateTeam($team_id){
        $this->team = TeamPlayerApplication::with('user.player')
        ->where('team_id', $team->id)->get();
    }


    public function updatedMaxAge()
    {
        $this->getFilteredApplicationsProperty();
    }

    public function updatedMinAge()
    {
        $this->getFilteredApplicationsProperty();
    }
   
    public function updatedMinRating()
    {
        $this->getFilteredApplicationsProperty();
    }

      
    public function getFilteredApplicationsProperty(): Collection
    {
        $query = TeamPlayerApplication::with('user.player')
            ->where('team_id', $this->team->id);

        if ($this->minAge) {
            $query->whereHas('user.player', function ($q) {
                $minBirthDate = Carbon::now()->subYears($this->minAge);
                $q->where('birth_date', '<=', $minBirthDate);
            });
        }            

        // if ($this->minAge) {
        //     $query->whereHas('user.player', function ($q) {
        //         $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, NOW()) >= ?', [$this->minAge]);
        //     });
        // }

        // if ($this->maxAge) {
        //     $query->whereHas('user.player', function ($q) {
        //         $q->whereRaw('TIMESTAMPDIFF(YEAR, birth_date, NOW()) <= ?', [$this->maxAge]);
        //     });
        // }

        if ($this->maxAge) {
            $query->whereHas('user.player', function ($q) {
                $maxBirthDate = Carbon::now()->subYears($this->maxAge + 1)->addDay(); // чтобы учесть день рождения
                $q->where('birth_date', '>=', $maxBirthDate);
            });
        }

        if ($this->minRating) {
            $query->whereHas('user.player', function ($q) {
                $q->where('rating', '>=', $this->minRating);
            });
        }

        $applications = $query->get();

        $this->dispatch('applicationsFiltered', playerApplications: $applications);
        return $applications;
    }

    

    public function render()
    {
        return view('livewire.transfer-filter');
    }
}
