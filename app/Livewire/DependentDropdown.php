<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\City;
use App\Models\District;
use App\Models\Location;
use App\Models\Tournament;
use App\Models\League;
use App\Models\Event;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Team;
use Livewire\Attributes\On; 

class DependentDropdown extends Component
{

    public $cities;
    public $city;
    public $districts;
    public $locations;
    public $tournaments;
    public $typeTournaments;
    public $leagues;
    public $myEvents = null;

    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedLocation = null;
    public $selectedTournament = null;
    public $selectedTypeTournament = null;
    public $selectedLeague = null;
    public $selectedMyEvent = null;


    public function mount()
    {
        $this->selectedCity = session('current_city', 2);
        $this->selectedDistrict = session('current_district', 0);
        $this->selectedLocation = session('current_location', 0);
        $this->selectedTypeTournament = session('current_type_tournament', 0);

        $this->selectedLeague = session('current_league', 0);
        $this->cities = City::all(); 
        $this->districts = District::where('city_id', $this->selectedCity)->orderBy('name')->get();
        $this->locations = Location::where('district_id', $this->selectedDistrict)->orderBy('address')->get();
        $this->tournaments = Tournament::all();
        $this->typeTournaments = $this->tournaments
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();

        $this->leagues = League::all();

        // $this->displayMyEvents();
    }

    public function updatedSelectedCity($city_id) 
    {
        session(['current_city' => $city_id]);        
        session(['current_district' => 0]);        
        session(['current_location' => 0]);   
        session(['current_type_tournament' => 0]);   
    
        $this->selectedCity = $city_id;
        $this->selectedDistrict = null;
        $this->selectedLocation = null;
        $this->selectedTournament = null;
        $this->selectedLeague = null;
        $this->districts = District::where('city_id', $city_id)->orderBy('name')->get();
        $this->locations = [];
        $this->tournaments = [];
        $this->typeTournaments = [];
        $this->leagues = [];
        $this->dispatch('city-selected', city_id: $city_id);
    }

    public function updatedSelectedDistrict($district_id) 
    {
        session(['current_district' => $district_id]);     
        session(['current_location' => 0]);   
        session(['current_type_tournament' => 0]);     
        $this->selectedLocation = null;
        $this->selectedLeague = null;
        $this->selectedTournament = null;
        $this->locations = Location::where('district_id', $district_id)->get();
        $this->tournaments = [];
        $this->typeTournaments = [];
        $this->leagues = [];
        $this->dispatch('district-selected', district_id: $district_id);
    }

    public function updatedSelectedLocation($location_id) 
    {
        session(['current_location' => $location_id]);
        session(['current_type_tournament' => 0]);   
        $this->selectedLeague = null;
        $this->selectedTournament = null;
        $this->tournaments = Tournament::all();
        $this->typeTournaments = $this->tournaments
            ->pluck('type')
            ->unique()
            ->values()
            ->toArray();
        $this->leagues = League::all();
        $this->dispatch('location-selected', location_id: $location_id);
    }
    
    // public function updatedSelectedTournament($tournament_id)
    // {
    //     session(['current_tournament' => $tournament_id]);
    //     $this->dispatch('tournament-selected', tournament_id: $tournament_id);
        
    // }
    
    public function updatedSelectedTypeTournament($typeTournament)
    {
        session(['current_type_tournament' => $typeTournament]);
        $this->dispatch('typeTournamentSelected', typeTournament: $typeTournament);
        
    }

    public function updatedSelectedLeague($league_id)
    {
        session(['current_league' => $league_id]);
        $this->dispatch('league-selected', league_id: $league_id);
        
    }

    public function updatedSelectedMyEvent($event_id)
    {
        $event = Event::with(['stadium.location.district.city', 'tournament', 'league'])->find($event_id);

        if($event){
            $city_id = $event->stadium->location->district->city->id;
            $district_id = $event->stadium->location->district->id;
            $location_id = $event->stadium->location->id;
            $typeTournament = $event->tournament->type;
            $league_id = $event->league->id ?? 0;
            
            $this->updatedSelectedCity($city_id);
            $this->updatedSelectedDistrict($district_id);
            $this->updatedSelectedLocation($location_id);
            $this->updatedSelectedTypeTournament($typeTournament);
            $this->updatedSelectedLeague($league_id);
        }        
    }

    // #[On('city-selected')]
    // #[On('district-selected')]
    // #[On('location-selected')]
    // #[On('typeTournamentSelected')]
    // #[On('league-selected')]
    public function displayMyEvents()
    {
        if(auth()->id()){
                      
            $player = Player::where('user_id', auth()->id())->first();
            if($player){
                $playerTeamIds = PlayerTeam::where('player_id', $player->id)
                    ->pluck('team_id');
                $teams = Team::with('event.stadium')
                    ->where('owner_id', auth()->id())
                    ->orWhereIn('id', $playerTeamIds)
                    ->orderByDesc('id')
                    ->pluck('event_id');
                $this->myEvents = Event::whereIn('id', $teams)->with(['tournament', 'stadium.location'])->get();
            }           
        }
    }

 
    public function render()
    {
        return view('livewire.dependent-dropdown');
    }
}
