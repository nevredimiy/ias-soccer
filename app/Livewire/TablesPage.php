<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\District;
use App\Models\Location;
use App\Models\Stadium;
use App\Models\Tournament;
use App\Models\League;
use App\Models\Event;
use App\Models\SeriesMeta;
use Livewire\Attributes\On;

class TablesPage extends Component
{

    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedLocation = null;
    public $selectedTypeTournament = null;
    public $selectedLeague = null;
    public $events = null;

    public function mount()
    {
        $this->selectedCity = session('current_city', 2);
        $this->selectedDistrict = session('current_district', 0);
        $this->selectedLocation = session('current_location', 0);
        $this->selectedTypeTournament = session('current_type_tournament', 0);
        $this->selectedLeague = session('current_league', 0);

        $this->updateEvents();

    }

    #[On('city-selected')]
    public function updateCityId($city_id)
    {
        $this->selectedCity = $city_id;
        $this->selectedDistrict = null;
        $this->selectedLocation = null;
        $this->selectedTypeTournament = null;
        $this->selectedLeague = null;
        $this->updateEvents();
    }

    #[On('district-selected')]
    public function updateDistrictId($district_id)
    {
        $this->selectedDistrict = $district_id;
        $this->selectedLocation = null;
        $this->selectedTypeTournament = null;
        $this->selectedLeague = null;
        $this->updateEvents();
    }

    #[On('location-selected')]
    public function updateLocationId($location_id)
    {
        $this->selectedLocation = $location_id;
        $this->selectedTypeTournament = null;
        $this->selectedLeague = null;
        $this->updateEvents();       
    }

    #[On('typeTournamentSelected')]
    public function updateTypeTournament($typeTournament)
    {
        $this->selectedTypeTournament = $typeTournament;
        $this->updateEvents();       
    }

    #[On('league-selected')]
    public function updateLeague($league_id)
    {
        $this->selectedLeague = $league_id;
        $this->updateEvents();       
    }

    public function updateEvents()
    {
        $eventQuery = Event::query()->orderByDesc('id');

        $stadiumIds = collect();

        if ($this->selectedCity) {
            $districtIds = District::where('city_id', $this->selectedCity)->pluck('id');
            $locationIds = Location::whereIn('district_id', $districtIds)->pluck('id');
            $stadiumIds = Stadium::whereIn('location_id', $locationIds)->pluck('id');
        }

        if ($this->selectedDistrict) {
            $locationIds = Location::where('district_id', $this->selectedDistrict)->pluck('id');
            $stadiumIds = Stadium::whereIn('location_id', $locationIds)->pluck('id');
        }

        if ($this->selectedLocation) {
            $stadiumIds = Stadium::where('location_id', $this->selectedLocation)->pluck('id');
        }

        if ($stadiumIds->isNotEmpty()) {
            $eventIds = SeriesMeta::whereIn('stadium_id', $stadiumIds);

            if ($this->selectedLeague) {
                $eventIds->where('league_id', $this->selectedLeague);
            }

            $eventIds = $eventIds->pluck('event_id');

            $eventQuery->whereIn('id', $eventIds);
        }

        if ($this->selectedTypeTournament) {
            $tournamentIds = Tournament::where('type', $this->selectedTypeTournament)->pluck('id');
            $eventQuery->whereIn('tournament_id', $tournamentIds);
        }

        $this->events = $eventQuery->with(['tournament', 'teams'])->get();
    }

 

    public function render()
    {
        $eventTeams = [];
        $eventIndividuals = [];
        foreach($this->events as $event){
            if($event->tournament->type === 'team'){
               $eventTeams[]  = $event;
            }else {
                $eventIndividuals[]  = $event;                
            }
        }
        
        return view('livewire.tables-page', [
            'eventTeams' => $eventTeams,
            'eventIndividuals' => $eventIndividuals,
        ]);
    }
    
}