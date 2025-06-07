<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Matche;
use Livewire\Attributes\On;

class ManagerEventMatches extends Component
{

    public $event = null; 
    public $seriesMeta = null; 
    public array $teamColors = []; 
    public array $templateMatches = []; 
    public array $teamIdsInSeries = []; 
    public int $activeMatch = 0; 
    public array $matches = []; 
    public array $seriesPlayers = [];
    public array $goals = [];    


    public function mount($event, $teamColors, $templateMatches, $teamIdsInSeries, $seriesMeta)
    {

        $this->event = $event;
        $this->seriesMeta = $seriesMeta;
        $this->teamColors = $teamColors;
        $this->templateMatches = $templateMatches;
        $this->teamIdsInSeries = $teamIdsInSeries;

        foreach($seriesMeta->seriesPlayers->toArray() as $player){
            $this->seriesPlayers[$player['player_id']] = $player;
        }
        
        $this->getDataMatcheEvents();

        $this->goals = $this->getGoals();

        
     
    }

    protected function getGoals()
    {
        $goals = [];

        foreach ($this->matches as $i => $match) {
            $matchNumber = $i+1;
            foreach ($match['match_events'] as $event) {
                if ($event['type'] == 'goal') {
                    if ($event['team_id'] == $match['team1_id']) {
                        $goals[$matchNumber][$match['team1_id']] = ($goals[$matchNumber][$match['team1_id']] ?? 0) + 1;
                    } else {
                        $goals[$matchNumber][$match['team2_id']] = ($goals[$matchNumber][$match['team2_id']] ?? 0) + 1;
                    }
                }

                if ($event['type'] == 'autogoal') {
                    if ($event['team_id'] == $match['team1_id']) {
                        $goals[$matchNumber][$match['team2_id']] = ($goals[$matchNumber][$match['team2_id']] ?? 0) + 1;
                    } else {
                        $goals[$matchNumber][$match['team1_id']] = ($goals[$matchNumber][$match['team1_id']] ?? 0) + 1;
                    }
                }
            }
        }
        return $goals;
    }


    public function selectedMatch($matchIteration)
    {
        $match = $this->matches[$matchIteration-1];
        $this->activeMatch = $matchIteration;
        $this->dispatch('selected-match', matchIteration: $matchIteration);
        session(['selected-match' => $match['id']]);

    }

    #[On('add-event')]
    public function getDataMatcheEvents()
    {
         $this->matches = Matche::with('matchEvents.team.color')
            ->where('series_meta_id', $this->seriesMeta->id)
            ->get()
            ->toArray();
         $this->goals = $this->getGoals();
    }


    public function render()
    {
        $icons = [
            'goal' => 'football', 
            'assist' => 'boots-icon', 
            'autogoal' => 'red-football', 
            'yellow_card' => 'yellow-card-icon', 
            'red_card' => 'red-card-icon'
        ];

        return view('livewire.manager-event-matches', [
            'icons' => $icons
        ]);
    }
}
