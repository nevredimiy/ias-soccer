<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MatcheEvent;
use App\Models\Matche;
use App\Models\SeriesMeta;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;


class TopScorersOfTournament extends Component
{

    public ?int $eventId = null;
    public $teams = [];
    public $topScorers = [];
    public $topAssists = [];
    public $topScorersLimit = 10;
    public $topScorersOffset = 0;
    public $playerOfSeries = [];
    

    public function mount($teams, $eventId = null): void
    {
        $this->eventId = $eventId ?? session('current_event', 0);        
        $this->teams = $teams;
        $this->loadTopStats();  

    }

    #[On('eventSelected')]
    public function updateTopPlayers($eventId): void
    {
        $this->eventId = $eventId;
        $this->loadTopStats();
    }

    private function loadTopStats(): void
    {
        $this->topScorers = $this->getTopScorers();
        $this->topAssists = $this->getTopAssists();
        $this->playerOfSeries = $this->getCountOfSeriesEachPlayers();
    }

    private function getMatchIds(): \Illuminate\Support\Collection
    {
        return Matche::where('event_id', $this->eventId)->pluck('id');
    }


    private function getTopScorers()
    {
        return MatcheEvent::query()
            ->select('player_id', 'team_id',
                DB::raw('COUNT(*) as goals_count'),
                DB::raw('COUNT(DISTINCT match_id) as matches_count')
            )
            ->with(['player', 'team.color', 'match.seriesMeta'])
            ->whereIn('match_id', $this->getMatchIds())
            ->where('type', 'goal')
            ->groupBy('player_id', 'team_id')
            ->orderByDesc('goals_count')
            ->limit($this->topScorersLimit)
            ->get();

    }

    private function getTopAssists()
    {
       return MatcheEvent::query()
            ->select('player_id', 'team_id',
                DB::raw('COUNT(*) as assists_count'),
                DB::raw('COUNT(DISTINCT match_id) as matches_count')
            )
            ->with(['player', 'team.color'])
            ->whereIn('match_id', $this->getMatchIds())
            ->where('type', 'assist')
            ->groupBy('player_id', 'team_id')
            ->orderByDesc('assists_count')
            ->limit($this->topScorersLimit)
            ->get();
      
    }

    protected function getCountOfSeriesEachPlayers()
    {
        $playerOfSeries = [];
        $seriesMeta = SeriesMeta::with('seriesPlayers')
            ->where('event_id', $this->eventId)
            ->get()
            ->groupBy('round');
        foreach ($seriesMeta as $meta) {
            foreach ($meta as $series) {
                foreach ($series->seriesPlayers as $player) {
                    if (!isset($playerOfSeries[$player->player_id])) {
                        $playerOfSeries[$player->player_id] = [
                            'id' => $player->player_id,
                            'name' => $player->player->full_name,
                            'series_count' => 0,
                        ];
                    }
                    $playerOfSeries[$player->player_id]['series_count']++;
                }
                
            }
            
        }
        
        return $playerOfSeries;
    }

    public function render()
    {
        return view('livewire.top-scorers-of-tournament');
    }
}
