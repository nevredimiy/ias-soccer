<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\TournamentService;
use Livewire\Attributes\On;
use Illuminate\Support\Collection;
use App\Models\SeriesMeta;

class TournamentTableIndividual extends Component
{
    public Collection $teams;
    public array $players = [];

    public function mount(array|Collection $teams = [], $eventId): void
    {
        $this->teams = TournamentService::getTeamsByEvent($eventId);
        $this->players = $this->getPlayers($eventId);
        // $this->roman = TournamentService::getRomanRounds($this->teams);ers($eventId);
    }

    #[On('eventSelected')]
    public function updateEventId($eventId): void
    {
        if ($eventId) {
            $this->teams = TournamentService::getTeamsByEvent($eventId);
            $this->players = $this->getPlayers($eventId);
            // $this->roman = TournamentService::getRomanRounds($this->teams);
        } else {
            $this->teams = collect();
            $this->players = [];
            // $this->roman = [];
        }
    }

    protected function getPlayers($eventId)
    {
        $seriesMetas = SeriesMeta::with(['seriesResults', 'seriesPlayers.player'])
            ->where('event_id', $eventId)
            ->get();

        $players = [];

        foreach ($seriesMetas as $seriesMeta) {
            foreach ($seriesMeta->seriesPlayers as $seriesPlayer) {
                $playerId = $seriesPlayer->player->id;

                if (!isset($players[$playerId])) {
                    $players[$playerId] = [
                        'id' => $playerId,
                        'name' => $seriesPlayer->player->full_name,
                        'photo' => $seriesPlayer->player->photo,
                        'rating' => $seriesPlayer->player->rating,
                        'wins' => 0,
                        'draw' => 0,
                        'defeat' => 0,
                        'goals_scored' => 0,
                        'goals_conceded' => 0,
                        'goal_difference' => 0,
                        'points' => 0,
                        'scores' => 0,
                        'place' => null,
                        'player_number' => $seriesPlayer->player_number,
                    ];
                }
            }

            foreach ($seriesMeta->seriesResults as $seriesResult) {
                $playerId = $seriesResult->player_id;

                if (isset($players[$playerId])) {
                    $players[$playerId]['wins'] += $seriesResult->wins;
                    $players[$playerId]['draw'] += $seriesResult->draw;
                    $players[$playerId]['defeat'] += $seriesResult->defeat;
                    $players[$playerId]['goals_scored'] += $seriesResult->goals_scored;
                    $players[$playerId]['goals_conceded'] += $seriesResult->goals_conceded;
                    $players[$playerId]['goal_difference'] += $seriesResult->goal_difference;
                    $players[$playerId]['points'] += $seriesResult->points;
                    $players[$playerId]['scores'] += $seriesResult->scores;
                }
            }
        }

        // Преобразуем в обычный массив и сортируем по scores
        $players = array_values($players);

        usort($players, function ($a, $b) {
            return $b['scores'] <=> $a['scores'];
        });

        return $players;
    }

    
    public function render()
    {
        return view('livewire.tournament-table-individual', [
            'roman' => TournamentService::getRomanRounds($this->teams),
        ]);
    }
}
