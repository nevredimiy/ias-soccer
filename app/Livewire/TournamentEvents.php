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

class TournamentEvents extends Component
{
    public $events = null;
    public $event = null;
    public $activeEvent = null;
    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedLocation = null;
    public $selectedTournament = null;
    public $selectedTypeTournament = null;
    public $selectedLeague = null;

    public function mount()
    {
        $this->selectedCity = session('current_city', 2);
        $this->selectedDistrict = session('current_district', 0);
        $this->selectedLocation = session('current_location', 0);
        $this->selectedTypeTournament = session('current_type_tournament', 0);
        $this->selectedLeague = session('current_league', 0);
        $this->activeEvent = session('current_event', 0);

        if($this->activeEvent == 0){
            $firstEvent = Event::with(['tournament', 'seriesMeta'])->first();
            $this->activeEvent  = $firstEvent->id;
        }

        $this->selectEvent($this->activeEvent);
        $this->updateEvents();
    }
    
    #[On('city-selected')]
    public function updateCityId($city_id)
    {
        $this->selectedCity = $city_id;
        $this->selectedDistrict = null;
        $this->selectedLocation = null;
        $this->selectedTournament = null;
        $this->selectedTypeTournament = null;
        $this->selectedLeague = null;
        $this->updateEvents();
    }

    #[On('district-selected')]
    public function updateDistrictId($district_id)
    {
        $this->selectedDistrict = $district_id;
        $this->selectedLocation = null;
        $this->selectedTournament = null;
        $this->selectedTypeTournament = null;
        $this->selectedLeague = null;
        $this->updateEvents();
    }

    #[On('location-selected')]
    public function updateLocationId($location_id)
    {
        $this->selectedLocation = $location_id;
        $this->selectedTournament = null;
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


    public function selectEvent($eventId)
    {
        $this->activeEvent = $eventId;        
        $this->event = Event::find($eventId);
        $this->dispatch('eventSelected', eventId: $eventId);
        session(['current_event' => $eventId]); 
    }

    public function updateEvents()
    {
        if($this->selectedCity){
            $district_ids = District::where('city_id', $this->selectedCity)->pluck('id');
            $location_ids = Location::whereIn('district_id', $district_ids)->pluck('id');
            $stadiums = Stadium::whereIn('location_id', $location_ids)->pluck('id');
            $seriesMetaIds = SeriesMeta::whereIn('stadium_id', $stadiums)->pluck('event_id');
            
            $this->events = Event::whereIn('id', $seriesMetaIds)->orderByDesc('id')->get();
            if ($this->activeEvent && count($this->events) > 0) {
                $this->selectEvent($this->activeEvent);
            } elseif(count($this->events) > 0) {
                $this->selectEvent($this->events->first()->id);
            } else {
                session()->forget('current_event');
            }
        }

        if($this->selectedDistrict){
            $location_ids = Location::where('district_id', $this->selectedDistrict)->pluck('id');
            $stadiums = Stadium::whereIn('location_id', $location_ids)->pluck('id');
            
            $seriesMetaIds = SeriesMeta::whereIn('stadium_id', $stadiums)->pluck('event_id');
            
            $this->events = Event::whereIn('id', $seriesMetaIds)->orderByDesc('id')->get();
            
            $found = false;
            foreach ($this->events as $event) {
                if ($event->id == $this->activeEvent) {
                    $this->selectEvent($this->activeEvent);
                    $found = true;
                    break;
                }
            }
            if (!$found && count($this->events) > 0) {
                $this->selectEvent($this->events->first()->id);
            } elseif (count($this->events) === 0) {
                session()->forget('current_event');
            }

        }

        if($this->selectedLocation){
            $stadium = Stadium::where('location_id', $this->selectedLocation)->first();  
            if (!$stadium) {
                $this->events = [];
                session()->forget('current_event');
                return;
            }

            $seriesMetaIds = SeriesMeta::whereIn('stadium_id', $stadiums)->pluck('event_id');
            $this->events = Event::whereIn('id', $seriesMetaIds)->orderByDesc('id')->get();
            
            
            if ($this->activeEvent && count($this->events) > 0) {
                $this->selectEvent($this->activeEvent);
            } elseif(count($this->events) > 0) {
                $this->selectEvent($this->events->first()->id);
            } else {
                session()->forget('current_event');
            }
        }

        if($this->selectedTypeTournament && $this->selectedLocation){
            $tournament_ids = Tournament::where('type', $this->selectedTypeTournament)->pluck('id');
            $stadium = Stadium::where('location_id', $this->selectedLocation)->first();
           
            if (!$stadium) {
                $this->events = [];
                session()->forget('current_event');
                return;
            }

            $this->events = Event::where('stadium_id', $stadium->id)
                ->whereIn('tournament_id', $tournament_ids)
                ->with('tournament')
                ->orderByDesc('id')
                ->get();
            
            if ($this->activeEvent && count($this->events) > 0) {
                $this->selectEvent($this->activeEvent);
            } elseif(count($this->events) > 0) {
                $this->selectEvent($this->events->first()->id);
            } else {
                session()->forget('current_event');
            }
        }

        if($this->selectedLeague && $this->selectedLocation){
            $league = League::where('id', $this->selectedLeague)->first();
            $stadium = Stadium::where('location_id', $this->selectedLocation)->first();

            if (!$stadium) {
                $this->events = [];
                session()->forget('current_event');
                return;
            }

            $this->events = Event::where('stadium_id', $stadium->id)
                ->where('league_id', $league->id)
                ->with('tournament')
                ->orderByDesc('id')
                ->get();

            
                if ($this->activeEvent && count($this->events) > 0) {
                    $this->selectEvent($this->activeEvent);
                } elseif(count($this->events) > 0) {
                    $this->selectEvent($this->events->first()->id);
                } else {
                    session()->forget('current_event');
                }

        }

        if($this->selectedLeague && $this->selectedLocation && $this->selectedTypeTournament){
            $league = League::where('id', $this->selectedLeague)->first();
            $tournament_ids = Tournament::where('type', $this->selectedTypeTournament)->pluck('id');
            $stadium = Stadium::where('location_id', $this->selectedLocation)->first();

            if (!$stadium) {
                $this->events = [];
                session()->forget('current_event');
                return;
            }

            $this->events = Event::where('stadium_id', $stadium->id)
                ->where('league_id', $league->id)
                ->whereIn('tournament_id', $tournament_ids)
                ->with('tournament')
                ->orderByDesc('id')
                ->get();
            
            if ($this->activeEvent && count($this->events) > 0) {
                $this->selectEvent($this->activeEvent);
            } elseif(count($this->events) > 0) {
                $this->selectEvent($this->events->first()->id);
            } else {
                session()->forget('current_event');
            }
        }

       

    }

    public function render()
    {
        return view('livewire.tournament-events');
    }
}
