<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Matche;
use App\Models\SeriesMeta;
use App\Services\SeriesTemplatesService;

class TournamentProtocol extends Component
{
    public array $currentRound = [];
    public $eventId = '';
    public $teams = null;
    public $matches = null;
    public array $colorClasses = [];

    public  function mount($teams, $eventId = 0)
    {
        
        if($eventId == 0){
            $this->eventId = session('current_event', 0);
        } else {
            $this->eventId = $eventId;
        }

        $this->currentRound = [
            'round_number' => 1,
            'event_id' => $this->eventId,
            'series_number' => 1
        ];

        $this->teams = $teams;

        $this->getMatches($this->eventId);
        $service = new SeriesTemplatesService();
        $this->colorClasses = $service->getColorClasses();

        // // dump($this->matches);
        // foreach($this->matches as $match){
        //     dump($match->event_id, $match->series_meta_id);
        // }
    }


    #[On('currentRound')]
    public function displayCurrentProtocol($currentRound)
    {
        $this->currentRound = $currentRound;
        
        $this->getMatches();
    }

    #[On('eventSelected')]
    public function getMatches($eventId = 0)
    {
        if ($eventId) {
            $this->eventId = $eventId;
        }

        $this->matches = Matche::query()
            ->with(['team1.color', 'team2.color', 'matchEvents'])
            ->withCount([
                'matchEvents as team1_goals_count' => function ($query) {
                    $query->where('type', 'goal')
                        ->whereColumn('team_id', 'matches.team1_id');
                },
                'matchEvents as team2_goals_count' => function ($query) {
                    $query->where('type', 'goal')
                        ->whereColumn('team_id', 'matches.team2_id');
                },
            ])
            ->where('event_id', $this->eventId)
            ->where('round', $this->currentRound['round_number'])
            ->where('series', $this->currentRound['series_number'])
            ->where('status', 'finished')
            ->get();
    }

    public function render()
    {
        return view('livewire.tournament-protocol');
    }
}
