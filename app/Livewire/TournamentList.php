<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SeriesMeta;
use App\Models\Event;
use App\Models\Team;
use App\Models\PlayerSeriesRegistration;
use App\Models\SeriesPlayer;
use App\Models\PlayerTeam;

class TournamentList extends Component
{

    public $events = null;
    public $activeEvent = null;
    public array $seriesMetas = [];
    public $activeEventId = 0;
    public bool $isPanel = false;
    public $playersRegistration = [];
    public $playersSeries = [];
    public $teams = null;
    public $selectedPlayer = null;
    public $activeSeries = null;

    public function mount()
    {
        $today = \Carbon\Carbon::now();
        $seriesMetas = SeriesMeta::with('event.tournament')->where('start_date', '>', $today)->get();
       
        $seriesMetasSolo = [];
        foreach($seriesMetas as $seriesMeta){
            if($seriesMeta->event->tournament->type == 'solo' || $seriesMeta->event->tournament->type == 'solo_private'){
                $seriesMetasSolo[$seriesMeta->event_id][] = $seriesMeta;
            }
        }
       
        $eventIdxs = array_keys($seriesMetasSolo);

        $events = Event::whereIn('id', $eventIdxs)->orderBy('id', 'desc')->get();

        $this->events = $events;
        $this->seriesMetas = $seriesMetasSolo; 
    }

    public function selectedEvent($eventId)
    {
        $this->activeEventId = $eventId;
        $this->loadSettingPanel($eventId);
        $this->getSeriesPlayers();
    }

    protected function loadSettingPanel($eventId)
    {
         
        $event = Event::with('seriesMeta.playerSeriesRegistration.player')->find($eventId);
        $this->activeEvent = $event; 
        
        $this->activeSeries = $this->seriesMetas[$this->activeEventId][0]->id;


        $this->getPlayersRegistration($this->activeSeries);

        $this->teams = Team::with(['color', 'players'])->where('event_id', $eventId)->get();
        $teamIds = $this->teams->pluck('id')->toArray();
        

        $playersTeams = PlayerTeam::whereIn('team_id', $teamIds)->get();


        
        if (!empty($this->playersRegistration) || $playersTeams->count() > 0){
            $this->isPanel = true;
        }
    }

    protected function getPlayersRegistration($seriesMetaId)
    {
        $playerSeriesRegistration = PlayerSeriesRegistration::where('series_meta_id', $seriesMetaId)->get();

        $this->playersRegistration = [];
        foreach($playerSeriesRegistration as $playersSR) {            
            $this->playersRegistration[] = $playersSR->player;            
        }
        
    }

    public function selectPlayer($playerId)
    {
        $this->selectedPlayer = $playerId;
 
    }

    public function selectPlaceOfTeam($teamId, $playerNumber)
    {
        if(!$this->selectedPlayer){
            return session()->flash('error', 'Спочатку виберіть гравця');
        }

        if (!is_numeric($playerNumber) || $playerNumber < 1 || $playerNumber > 10) {
            return session()->flash('error', 'Невірний номер гравця');
        }

        PlayerSeriesRegistration::query()
            ->where('player_id', $this->selectedPlayer)
            ->where('series_meta_id', $this->activeSeries)
            ->delete();
        
        SeriesPlayer::create([
            'series_meta_id' => $this->activeSeries,
            'player_id' => $this->selectedPlayer,
            'team_id' => $teamId,
            'player_number' => $playerNumber
        ]);

        PlayerTeam::create([
            'player_id' => $this->selectedPlayer,
            'team_id' => $teamId,
            'player_number' => $playerNumber,
            'status' => 'main'
        ]);
        
        
        $this->getPlayersRegistration($this->activeSeries);
        $this->getSeriesPlayers();
        
        $this->selectedPlayer = null;
    }

    protected function getSeriesPlayers()
    {
        $this->playersSeries = [];

        $seriesPlayers = SeriesPlayer::query()
            ->with('player')
            ->where('series_meta_id', $this->activeSeries)
            ->get();
        
        
        foreach($seriesPlayers as $seriesPlayer){
            $this->playersSeries[$seriesPlayer->team_id][$seriesPlayer->player_number] = $seriesPlayer->player;
        }

    }

    public function deletePlaceOfTeam($teamId, $playerid, $playerNumber)
    {
        SeriesPlayer::query()
            ->where('team_id', $teamId)
            ->where('player_id', $playerid)
            ->where('player_number', $playerNumber)
            ->delete();
        PlayerTeam::query()
            ->where('team_id', $teamId)
            ->where('player_id', $playerid)
            ->where('player_number', $playerNumber)
            ->delete();
        PlayerSeriesRegistration::create([
            'series_meta_id' => $this->activeSeries,
            'player_id' => $playerid,
        ]);

        $this->getPlayersRegistration($this->activeSeries);
        $this->getSeriesPlayers();        
        $this->selectedPlayer = null;
        session()->flash('notice', 'Гравця видалено з команди і переведено у загальний кошик');
    }

    public function updatedActiveSeries()
    {

        $this->getPlayersRegistration($this->activeSeries);
        $this->getSeriesPlayers();
    }


    public function render()
    {
        return view('livewire.tournament-list');
    }
}
