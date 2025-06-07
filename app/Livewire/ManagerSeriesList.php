<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Matche;
use App\Models\City;
use App\Models\District;
use App\Models\Location;
use App\Models\Stadium;
use App\Models\SeriesMeta;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class ManagerSeriesList extends Component
{

    public $stadiums = null;
    public $events = null;
    
    public $seriesMetas = null;
    public $seriesMetasGroup = null;


    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedLocation = null;
    public $selectedTournament = null;
    public $selectedLeague = null;
    public $selectedEvent = null;

    

    public function mount()
    {
        $this->selectedCity = session('current_city', 2);
        $this->selectedEvent = session('current_event', 0);
    
        $this->updateSeriesMetas();

        $eventIds = SeriesMeta::pluck('event_id')->unique();
    
        $this->events = Event::query()
            ->whereIn('id', $eventIds)
            ->get();

    }
    
    

    #[On('city-selected')]
    public function updateCityId($city_id)
    {
        $this->selectedCity = $city_id;
        $this->selectedDistrict = null;
        $this->selectedLocation = null;
        $this->selectedTournament = null;
        $this->selectedLeague = null;
       
        $this->updateSeriesMetas();
    }

    #[On('district-selected')]
    public function updateSelectedDistrict($district_id)
    {
        $this->selectedDistrict = $district_id;
        $this->selectedLocation = null;
        $this->selectedTournament = null;
        $this->selectedLeague = null;

        $this->updateSeriesMetas();
    }

    #[On('location-selected')]
    public function updateLocationId($location_id)
    {
        $this->selectedLocation = $location_id;
        $this->selectedTournament = null;
        $this->selectedLeague = null;

        $this->updateSeriesMetas();
    }

    #[On('typeTournamentSelected')]
    public function updateTournament($typeTournament)
    {

        $this->selectedTournament = $typeTournament;
        $this->updateSeriesMetas();
    }

    #[On('league-selected')]
    public function updateLeagueId($league_id)
    {
        $this->selectedLeague = $league_id;
        $this->updateSeriesMetas();
    }

    public function updatedSelectedEvent($event_id)
    {
        session(['current_event' => $event_id]); 
        $this->updateSeriesMetas();
    }

   

    protected function updateSeriesMetas()
    {
        $eventIds = $this->selectedEvent
            ? collect([$this->selectedEvent])
            : $this->buildSeriesMetaQuery()->pluck('event_id');
    
        $this->seriesMetas = SeriesMeta::query()
            ->whereIn('event_id', $eventIds)
            ->get();
    

        $this->seriesMetasGroup = collect($this->seriesMetas)->groupBy('round');

    

    }
    

    private function buildSeriesMetaQuery()
    {
        $query = SeriesMeta::query()->with('stadium.location.district');

        if ($this->selectedLocation) {
            $query->whereHas('stadium.location', function ($q) {
                $q->where('id', $this->selectedLocation);
            });
        } elseif ($this->selectedDistrict) {
            $query->whereHas('stadium.location', function ($q) {
                $q->where('district_id', $this->selectedDistrict);
            });
        } elseif ($this->selectedCity) {
            $query->whereHas('stadium.location.district', function ($q) {
                $q->where('city_id', $this->selectedCity);
            });
        }

        if ($this->selectedLeague) {
            $query->where('league_id', $this->selectedLeague);
        }

        return $query;
    }


   
    public function render()
    {
        
        return view('livewire.manager-series-list');
    }
}
