<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\MatcheEvent;
use App\Models\SeriesMeta;
use App\Models\SeriesResult;
use App\Models\Team;
use App\Models\Matche;
use Livewire\Attributes\On; 
use Illuminate\Support\Facades\DB;


class ManagerEventActions extends Component
{

    public $seriesId = null;
    public $isActiveVoteButton = false;
    public $seriesMeta = null;

    public function mount($id)
    {
        $this->seriesId =  $id;
        $this->seriesMeta = SeriesMeta::with(['event.tournament', 'matches'])->find($this->seriesId);
    }

     public function addEvent()
    {
        
        $type = session('selected-type', '0');
        $matchId = session('selected-match', '0');
        $teamId = session('selected-teamId', '0');
        $playerId = session('selected-player', '0');

        if(!$type){
            return session()->flash('error', 'Тип події не вибрано');
        }

        if(!$matchId){
            return session()->flash('error', 'Матч не вибрано');
        }
      
        if(!$playerId){
            return session()->flash('error', 'Гравця не вибрано');
        }

        MatcheEvent::create([
            'match_id' => $matchId,
            'team_id' => $teamId,
            'player_id' => $playerId,
            'type' => $type,
        ]);

        $this->dispatch('add-event');
        
    }

    public function deleteEvent()
    {
        $matchId = session('selected-match', '0');
        $teamId = session('selected-teamId', '0');

        if(!$matchId){
            return session()->flash('error', 'Матч не вибрано');
        }

        $lastEvent = MatcheEvent::where('match_id', $matchId)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if ($lastEvent) {
            $lastEvent->delete();
        } else {
            session()->flash('error', 'Немає подій для видалення');
        }

        $this->dispatch('add-event');

    }

    // Нужно помнить, что в серии всегда три команды. 
    // При закрытии серии аписываем в таблицу результаты - три строчки.
    public function seriesClosed()
    {
        $seriesMeta = $this->seriesMeta;

        if (!$seriesMeta) {
            session()->flash('error', 'Немає серій у цьому турнірі');
            return;
        }

        $isFinalSeries = $this->isFinalSeries($seriesMeta);

        $summary = [];

        foreach ($seriesMeta->matches as $match) {
            $results = $this->getResult($match);
            foreach ($results as $teamId => $result) {
                foreach ($result as $key => $value) {
                    $summary[$teamId][$key] = ($summary[$teamId][$key] ?? 0) + $value;
                }
            }
        }

        // Для надежности пишем значение по умолчанию
        $defaults = [
            'wins' => 0,
            'draw' => 0,
            'defeat' => 0,
            'goals_scored' => 0,
            'goals_conceded' => 0,
            'goal_difference' => 0,
            'points' => 0,
            'scores' => 0,
        ];

        foreach ($summary as $teamId => &$stats) {
            $stats += $defaults;
            $stats['goal_difference'] = $stats['goals_scored'] - $stats['goals_conceded'];
            $stats['points'] = $stats['wins'] * 3 + $stats['draw'];
        }


        unset($stats);

        uasort($summary, fn($a, $b) =>
            [$b['points'], $b['goal_difference'], $b['wins'] ?? 0, $b['goals_scored'] ?? 0]
            <=>
            [$a['points'], $a['goal_difference'], $a['wins'] ?? 0, $a['goals_scored'] ?? 0]
        );

        // Назначение очков: 3 — 2 — 1
        $rank = 1;
        foreach ($summary as &$stats) {
            if($isFinalSeries){
                $stats['scores'] = match ($rank++) {
                    1 => 6,
                    2 => 4,
                    3 => 2,
                    default => 0,
                };
            }else { 
                $stats['scores'] = match ($rank++) {
                    1 => 3,
                    2 => 2,
                    3 => 1,
                    default => 0,
                };
            }
        }
        unset($stats);


        $seriesResult = [];

        if (in_array($seriesMeta->event->tournament->type, ['solo', 'solo_private'])) {
            $seriesMeta->load('seriesPlayers');
            foreach ($seriesMeta->seriesPlayers as $playerSP) {
                $teamId = $playerSP->team_id;
                $result = $summary[$teamId] ?? [];
                $seriesResult[] = array_merge($result, [
                    'series_meta_id' => $seriesMeta->id,
                    'team_id' => $teamId,
                    'player_id' => $playerSP->player_id,
                ]);
            }
        } else {
            foreach ($summary as $teamId => $result) {
                $seriesResult[] = array_merge($result, [
                    'series_meta_id' => $seriesMeta->id,
                    'team_id' => $teamId,
                    'player_id' => null,
                ]);
            }
        }

        if(!empty($seriesResult)){
            // Добавляем время записи и обнолвения 
            $now = now();
            foreach ($seriesResult as &$row) {
                $row['created_at'] = $now;
                $row['updated_at'] = $now;
            }
            unset($row);

            SeriesResult::insert($seriesResult);
            SeriesMeta::where('id', $this->seriesId)->update(['status' => 'closed']);
            Matche::where('series_meta_id', $this->seriesId)->update(['status' => 'finished']);
            
            session()->flash('success', 'Сесія успішно закрита!');
            $this->seriesMeta = $this->seriesMeta->fresh();

        }

        
    }

    protected function isFinalSeries($seriesMeta)
    {
        $seriesMetas = SeriesMeta::where('event_id', $seriesMeta->event_id)->get();
        $event = $seriesMeta->event;
       
        if ($seriesMetas->count() !== $event->tournament->count_rounds) {
            return false;
        }

        $lastSeries = $seriesMetas->sortByDesc(function ($item) {
            return \Carbon\Carbon::parse($item->start_date);
        })->first();

        return $lastSeries->id === $seriesMeta->id;
    }

    protected function getResult(Matche $match): array
    {
        $resultTeams = [];
        $goalsData = $this->extractGoals($match);

        $goals = $goalsData['goals'];
        $cleanGoals = $goalsData['clean_goals'];

        $team1 = $match->team1_id;
        $team2 = $match->team2_id;

        $goals1 = $goals[$team1] ?? 0;
        $goals2 = $goals[$team2] ?? 0;

        if ($goals1 === $goals2) {
            $resultTeams[$team1]['draw'] = 1;
            $resultTeams[$team2]['draw'] = 1;
        } elseif ($goals1 > $goals2) {
            $resultTeams[$team1]['wins'] = 1;
            $resultTeams[$team2]['defeat'] = 1;
        } else {
            $resultTeams[$team2]['wins'] = 1;
            $resultTeams[$team1]['defeat'] = 1;
        }

        $resultTeams[$team1]['goals_scored'] = $cleanGoals[$team1] ?? 0;
        $resultTeams[$team2]['goals_scored'] = $cleanGoals[$team2] ?? 0;

        $resultTeams[$team1]['goals_conceded'] = $cleanGoals[$team2] ?? 0;
        $resultTeams[$team2]['goals_conceded'] = $cleanGoals[$team1] ?? 0;

        return $resultTeams;
    }

    protected function extractGoals(Matche $match): array
    {
        $goals = [];
        $cleanGoals = [];

        $match->load('matchEvents');

        foreach ($match->matchEvents as $event) {
            if ($event->type === 'goal') {
                $cleanGoals[$event->team_id] = ($cleanGoals[$event->team_id] ?? 0) + 1;
                $goals[$event->team_id] = ($goals[$event->team_id] ?? 0) + 1;
            }

            if ($event->type === 'autogoal') {
                $goals[$event->team_id] = ($goals[$event->team_id] ?? 0) - 1;
            }
        }

        return ['goals' => $goals, 'clean_goals' => $cleanGoals];
    }

    public function seriesOpen()
    {
        SeriesMeta::where('id', $this->seriesId)->update(['status' => 'open']);
        SeriesResult::where('series_meta_id', $this->seriesId)->delete();
        Matche::where('series_meta_id', $this->seriesId)->update(['status' => 'scheduled']);
        
        session()->flash('success', 'Серію відкрито, результати очищено.');
    
        $this->seriesMeta = $this->seriesMeta->fresh();
    }

    #[On('selected-match')]
    public function selectMatch($matchIteration)
    {

        if($matchIteration > 9){
            $this->isActiveVoteButton = true;
        }
    }


    public function render()
    {
        return view('livewire.manager-event-actions');
    }
}
