<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Event;
use App\Models\Player;
use App\Models\SeriesMeta;
use App\Models\SeriesPlayer;
use Livewire\Attributes\On;
use Illuminate\Database\Eloquent\Relations\Relation;


class TournamentInfo extends Component
{
    
    public $teams = [];
    public $eventId = null;
    public $event = null;
    public $topPlayersByVote = [];

    protected array $tourTemplate = [
        ['Червоний', 'Зелений', 'Жовтий'], 
        ['Червоний', 'Зелений', 'Рожевий'],
        ['Червоний', 'Жовтий', 'Рожевий'], 
        ['Зелений', 'Жовтий', 'Рожевий'], 
    ];

    public $shedule = [];
    public $currentRound = [];
    public $seriesPlayers = null;
    public bool $hasActiveEvent = true;

    public function mount()
    {
        $eventId = session('current_event', 0);
        $this->eventId = $eventId;
        $this->event = Event::with('tournament')->find($eventId);

   


        // if (!$this->event || !$this->event->tournament) {
        //     $this->hasActiveEvent = false;
        //     return;
        // }

        $this->teams = $this->getTeams($eventId);
        $this->shedule = $this->getScheduleProperty($this->teams);

        // что касаеться отображения игроков игры, т.е. выбранной серии
        $this->currentRound = [
            'round_number' => 1,
            'event_id' => $eventId,
            'series_number' => 1
        ];
        $this->getSeriesPlayers($this->currentRound);

        $this->topPlayersByVote = $this->getTopPlayersByVote();

    }

    private function getTopPlayersByVote()
    {
        if($this->event->tournament->type !== 'solo'){
            return array();
        }
        
        $eventId = $currentRound['event_id'] ?? 0;
        $seriesNumber = $currentRound['series_number'] ?? 0;
        $roundNumber = $currentRound['round_number'] ?? 0;
        
        $seriesMeta = SeriesMeta::query()
            ->with(['seriesPlayers.team.color', 'voters'])
            ->where('event_id', $eventId)
            ->where('series', $seriesNumber)
            ->where('round', $roundNumber)
            ->first();
        $teamColorPlayers = [];

        if(!$seriesMeta){
            return;
        }

        foreach ($seriesMeta->seriesPlayers as $seriesPlayer){
            $teamColorPlayers[$seriesPlayer->player_id] = $seriesPlayer->team->color->color_picker;
        }

        $topPlayersByVote = [];
        foreach ($seriesMeta->voters as $voter) {
            foreach ([$voter->best_player1, $voter->best_player2] as $playerId) {

                if (!isset($topPlayersByVote[$playerId])) {
                    $player = Player::find($playerId);
                    $topPlayersByVote[$playerId] = [
                        'player' => $player,
                        'votes' => 1,
                        'team_color' => $teamColorPlayers[$playerId] ?? '#ccc',
                    ];
                } else {
                    $topPlayersByVote[$playerId]['votes'] += 1;
                }
            }
        }

        return collect($topPlayersByVote)
            ->sortByDesc('votes')
            ->take(10)
            ->values()
            ->all();
        
    }

    #[On('currentRound')]
    public function getSeriesPlayers(array $currentRound)
    {
        $seriesNumber = $currentRound['series_number'] ?? 0;
        $roundNumber = $currentRound['round_number'] ?? 0;

        $series = SeriesMeta::query()
            ->with('seriesPlayers.player')
            ->where('event_id', $this->eventId)
            ->where('series', $seriesNumber)
            ->where('round', $roundNumber)
            ->first();
        
        if(!$series){
            return;
        }

        if(isset($series->seriesPlayers)){
            $this->seriesPlayers = $series->seriesPlayers->groupBy('team_id')->all();
        }else {
            $this->seriesPlayers = [];
        }

        $this->topPlayersByVote = $this->getTopPlayersByVote();
    }

    private function getTeams($eventId)
    {
        $teams = Team::where('event_id', $eventId)->with(['color', 'players'])->get();
        foreach($teams as $team){
            $totalRating = 0;
            $totalPlayers = 0;
            $players = $team->players;
            
            $totalRating = $players->sum('rating');
            $totalPlayers = $players->count();
            $team->rating = $totalPlayers > 0 ? round($totalRating / $totalPlayers) : 0;
        }

        return $teams;
    }

    // public function getBgClass($colorName): string
    // {
    //     return $this->colorClasses[$colorName] ?? 'default-bg';
    // }


    public function getScheduleProperty($teams)
    {
                
        // Преобразуем в удобный массив: "Красная" => Team
        $teamsByColor = $teams->mapWithKeys(function ($team) {
            return [$team->color->name => $team];
        });

        $schedule = [];

        // Всего 12 туров, шаблон повторяется каждые 4
        for ($i = 0; $i < 12; $i++) {
            $templateIndex = $i % 4;
            $playingColors = $this->tourTemplate[$templateIndex];

            $playingTeams = collect($playingColors)
                ->map(fn($color) => $teamsByColor[$color] ?? null)
                ->filter(); // на случай, если какой-то команды нет

            $restingTeam = $teams->diff($playingTeams)->first();

            $schedule[] = [
                'round' => $i + 1,
                'playing' => $playingTeams,
                'resting' => $restingTeam,
            ];
        }
        return $schedule;
    }


    #[On('eventSelected')]
    public function updateEventId($eventId)
    {
        if($eventId){
            $this->eventId = $eventId;
            $this->teams = $this->getTeams($eventId);
            $this->event = Event::with('tournament')->find($eventId);
        } else {
            $this->teams = [];
        }

        $this->getSeriesPlayers($this->currentRound);
    }



    public function render()
    {
        return view('livewire.tournament-info');
    }
}
