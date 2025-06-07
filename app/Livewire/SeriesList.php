<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Event;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\SeriesMeta;

class SeriesList extends Component
{
    public $selectedCity = null;
    public $selectedDistrict = null;
    public $selectedLocation = null;
    public $selectedLeague = null;
    public $events;
    public $tournament;  
    
    public $series = [];
    public $teams = [];
    public $status = 'shedule';
    
    public function mount($tournamentId, $status = 'shedule')
    {
        $this->tournament = Tournament::with('events.teams')->findOrFail($tournamentId);
        $this->status = $status;
    
        $this->selectedCity = session('current_city', 2);
        $events = $this->tournament->events;
    
        $eventIds = $events->pluck('id');
        $today = \Carbon\Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'); 
    
        $this->series = SeriesMeta::query()
            ->whereIn('event_id', $eventIds)
            ->where('start_date', '>', $today)
            ->where('status_registration', 'open')
            ->with([
                'stadium.location.district', 
                'teams.players', 
                'event.tournament', 
                'playerSeriesRegistration.player',
                'seriesPlayers.player'
                ])
            ->orderBy('start_date')
            ->get()
            ->groupBy('event_id')    // группируем по event_id
            ->map(function ($seriesGroup) {
                // берем первый элемент каждого agrupировки для получения уникального
                return $seriesGroup->first();
            })
            ->values(); // переиндексируем коллекцию
            
        $this->series = $this->calculateStats($this->series);
    }

    protected function calculateStats($seriesCollection)
    {
        foreach ($seriesCollection as $series) {
            $series->teams_count = $series->teams->count();
            if($this->tournament->type == 'solo_private'){
                $series->players_count += $series->seriesPlayers->count();
            } else {
                $series->players_count += $series->playerSeriesRegistration->count();
            }

            $totalRating = 0;
            $totalPlayers = 0;

            if($this->tournament->type == 'solo_private' || $this->tournament->type == 'team'){
                foreach($series->seriesPlayers as $playerSR){
                    $totalRating += $playerSR->player->rating;
                    $totalPlayers += 1;
                }
            }else {
                foreach($series->playerSeriesRegistration as $playerSR){
                    $totalRating += $playerSR->player->rating;
                    $totalPlayers += 1;
                }
            }

            $series->average_player_rating = $totalPlayers > 0
                ? round($totalRating / $totalPlayers)
                : 0;

        }

        return $seriesCollection;
    }

    

    #[On('city-selected')]
    public function updateCityId($city_id)
    {
        $this->selectedCity = $city_id;
        $this->selectedDistrict = null;
        $this->selectedLocation = null;
        $this->selectedLeague = null;
       
        $this->updateSeries();
    }
    
    
    #[On('district-selected')]
    public function updateSelectedDistrict($district_id)
    {
        $this->selectedDistrict = $district_id;
        $this->selectedLocation = null;
        $this->selectedLeague = null;
        $this->updateSeries();
    }
        
    #[On('location-selected')]
    public function updateLocationId($location_id)
    {
        $this->selectedLocation = $location_id;
        $this->selectedLeague = null;
        $this->updateSeries();
    }
        
    #[On('league-selected')]
    public function updateLeagueId($league_id)
    {
        $this->selectedLeague = $league_id;
        $this->updateSeries();
    }


    public function updateSeries()
    {
        $query = SeriesMeta::with('stadium.location.district.city', 'event')
            ->whereHas('event', function ($q) {
                $q->where('tournament_id', $this->tournament->id);
            });
    
        if ($this->selectedCity) {
            $query->whereHas('stadium.location.district.city', function ($q) {
                $q->where('id', $this->selectedCity);
            });
        }
    
        if ($this->selectedDistrict) {
            $query->whereHas('stadium.location.district', function ($q) {
                $q->where('id', $this->selectedDistrict);
            });
        }
    
        if ($this->selectedLocation) {
            $query->whereHas('stadium.location', function ($q) {
                $q->where('id', $this->selectedLocation);
            });
        }
    
        if ($this->selectedLeague) {
            $query->where('league_id', $this->selectedLeague);
        }

        // Получение данных и расчёт статистики (если нужно)
        $this->series = $this->calculateStats($query->get());
    }
    

    public function render()
    {
                
        return view('livewire.series-list');
    }
}
