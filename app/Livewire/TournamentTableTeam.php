<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Services\TournamentService;
use App\Models\SeriesMeta;

class TournamentTableTeam extends Component
{

    public Collection $teams;
    public ?int $eventId = null;
    public array $roman = [];
    public $teamsForTable = [];


    public function mount(Collection|array $teams = [], $eventId): void
    {
        $this->teams = $teams;
        $this->roman = TournamentService::getRomanRounds($this->teams);
        $this->$eventId = $eventId;

        $this->teamsForTable = $this->getTeamsForTable($eventId);
    }
   
    #[On('eventSelected')]
    public function updateEventId($eventId): void
    {
        $this->eventId = $eventId;

        if ($eventId) {
            $this->teams = TournamentService::getTeamsByEvent($eventId);
            $this->roman = TournamentService::getRomanRounds($this->teams);
        } else {
            $this->teams = collect();
            $this->roman = [];
        }
        $this->teamsForTable = $this->getTeamsForTable($eventId);
    }

    protected function getTeamsForTable($eventId)
    {
         $seriesMetas = SeriesMeta::with('seriesResults.team.color')
            ->where('event_id', $eventId)
            ->get();

        $teamsForTable = [];

        foreach($this->teams as $team){
            $teamsForTable[$team->id] = [
                'id' => $team->id,
                'name' => $team->name,
                'logo' => $team->logo,
                'wins' => 0,
                'draw' => 0,
                'defeat' => 0,
                'goals_scored' => 0,
                'goals_conceded' => 0,
                'goal_difference' => 0,
                'points' => 0,
                'scores' => 0,
                'color' => $team->color->color_picker,
            ];
        }
        
        
        foreach($seriesMetas as $seriesMeta){
            foreach ($seriesMeta->seriesResults as $seriesResult) {
                $teamId = $seriesResult->team_id;
                if(isset($teamsForTable[$teamId])){
                    $teamsForTable[$teamId]['wins'] += $seriesResult->wins;
                    $teamsForTable[$teamId]['draw'] += $seriesResult->draw;
                    $teamsForTable[$teamId]['defeat'] += $seriesResult->defeat;
                    $teamsForTable[$teamId]['goals_scored'] += $seriesResult->goals_scored;
                    $teamsForTable[$teamId]['goals_conceded'] += $seriesResult->goals_conceded;
                    $teamsForTable[$teamId]['goal_difference'] += $seriesResult->goal_difference;
                    $teamsForTable[$teamId]['points'] += $seriesResult->points;
                    $teamsForTable[$teamId]['scores'] += $seriesResult->scores;
                }
            }
        }

        $teamsForTable = array_values($teamsForTable);

        usort($teamsForTable, function($a, $b){
            return $b['scores'] <=> $a['scores'];
        });        

        $this->dispatch('teamsForTable', $teamsForTable);
        session()->put('teamsForTable', $teamsForTable);

        return $teamsForTable;
    }

    public function render(): \Illuminate\View\View
    {   
        
        return view('livewire.tournament-table-team');
    }
}
