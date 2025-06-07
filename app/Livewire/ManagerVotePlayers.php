<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Voter;

class ManagerVotePlayers extends Component
{

    public $series = null;
    public $playersTeam = null;
    public $votingPlayer = 0;
    public array $bestPlayerIds = [];
    public array $worstPlayerIds = [];
    public $votedPlayers = null;

    public function mount($series, $playersTeam)
    {
        $this->series = $series;
        $this->playersTeam = $playersTeam;
        $this->updateVotedPlayers();


    }

    protected function updateVotedPlayers()
    {
        $this->votedPlayers = Voter::where('series_meta_id', $this->series->id)->pluck('voted_player')->toArray();
    }


    public function selectVotingPlayer($playerId)
    {
        $this->votingPlayer = $playerId;
        
    }

    public function selectBestPlayer($bestPlayerId)
    {
        if(count($this->bestPlayerIds) >= 2){
          $this->bestPlayerIds[0]  = $this->bestPlayerIds[1];  
          $this->bestPlayerIds[1]  = $bestPlayerId;
        } else {
            $this->bestPlayerIds[] = $bestPlayerId;
        }
        
    }

    public function selectWorstPlayer($worstPlayerId)
    {
        if(count($this->worstPlayerIds) >= 2){
          $this->worstPlayerIds[0]  = $this->worstPlayerIds[1];  
          $this->worstPlayerIds[1]  = $worstPlayerId;
        } else {
            $this->worstPlayerIds[] = $worstPlayerId;
        }       

    }

    public function votedPlayer()
    {
        
        if($this->votingPlayer == 0){
            session()->flash('error', 'Виберіть гравця який голосує');
            return;
        }

        if(count($this->bestPlayerIds) < 2 || !isset($this->bestPlayerIds[1])){
            $countPlayers = count($this->bestPlayerIds);
            session()->flash('error', "Потрібно вибрати двох найкращих гравців. Ви вибрали $countPlayers гравців");
            return;
        }

        if(count($this->worstPlayerIds) < 2 || !isset($this->worstPlayerIds[1])){
            $countPlayers = count($this->worstPlayerIds);
            session()->flash('error', "Потрібно вибрати двох найгірших гравців. Ви вибрали $countPlayers гравців");
            return;
        }

        Voter::create([
            'series_meta_id' => $this->series->id,
            'voted_player' => $this->votingPlayer,
            'best_player1' => $this->bestPlayerIds[0],
            'best_player2' => $this->bestPlayerIds[1],
            'worst_player1' => $this->worstPlayerIds[0],
            'worst_player2' => $this->worstPlayerIds[1],
        ]);

        session()->flash('success', "Дякую. Голосування записано");


        $this->votingPlayer = 0;
        $this->bestPlayerIds = [];
        $this->worstPlayerIds = [];
        $this->updateVotedPlayers();
    }


    public function render()
    {
        // Получаем количество игроков в команде в данной серии
        $seriesPlyayersOfTeam = $this->series->seriesPlayers->groupBy('team_id')->all();
        return view('livewire.manager-vote-players', [
            'seriesPlyayersOfTeam' => $seriesPlyayersOfTeam,
        ]);
    }
}
