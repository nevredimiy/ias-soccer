<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Player;
use App\Models\PlayerTeam;
use Livewire\Attributes\On;

class TeamPlayers extends Component
{

    public $players = null;
    public $team = null;
    public $player_id = '';
    public $team_id = '';
    public $status = 'reserve';

    public function mount($team_id)
    {
        $this->team_id = $team_id;
        $this->team = Team::find($team_id);
        $players = $this->getPlayersForTeam($team_id);
        

        $this->players = $players->map(function($player) use ($team_id) {
            return [
                'id' => $player->id,
                'full_name' => $player->full_name,
                'photo' => $player->photo,
                'rating' => $player->rating,
                'status' => $player->teams()->where('team_id', $team_id)->first()->pivot->status,
            ];
        })->toArray();

        
    }

    #[On('team-selected')]
    public function updateTeam($team_id)
    {    
        $this->team = Team::find($team_id);    
        $this->players = $this->getPlayersForTeam($team_id);
        // // $this->playerStatus = ;
        // dump($this->players);
        // foreach($this->players as $player){
        //     $this->playerStatus = $player->teams()->where('team_id', $team_id)->first()->pivot->status;
        // }
        $this->players = $this->players->map(function($player) use ($team_id) {
            return [
                'id' => $player->id,
                'full_name' => $player->full_name,
                'photo' => $player->photo,
                'rating' => $player->rating,
                'status' => $player->teams()->where('team_id', $team_id)->first()->pivot->status,
            ];
        })->toArray();

        
        $this->applications = \App\Models\TeamPlayerApplication::with('user.player')
        ->where('team_id', $team_id)
        ->get()
        ->toArray();
    }

    
    protected function getPlayersForTeam($team_id)  
    {  

        $playerIds = PlayerTeam::where('team_id', $team_id)->pluck('player_id');
        $players = Player::with('teams')->whereIn('id', $playerIds)->get()->map(function ($player) {  
            // Извлекаем статус игрока в команде  
            $teamPivot = $player->teams->first(); // Получаем первую команду, в которой игрок состоит (текущую команду)  
            
            $player->status = $teamPivot ? $teamPivot->pivot->status : null; // Добавляем статус в объект игрока  
            
            return $player; // Возвращаем игрока с добавленным статусом  
        });  
        
        return $players;  
    }  

    public function save($player_id, $team_id)
    {
        $playerTeam = PlayerTeam::where('player_id', $player_id)
                                ->where('team_id', $team_id)
                                ->first();
    
        if (!$playerTeam) {
            session()->flash('error', 'Гравець не знайдений у складі команди.');
            return;
        }
    
        $newStatus = $playerTeam->status === 'main' ? 'reserve' : 'main';
        $playerTeam->status = $newStatus;
        $playerTeam->save();
    
        // Обновляем статус в массиве players
        foreach($this->players as &$player){
            if($player['id'] == $player_id){
                $player['status'] = $newStatus;
                break;
            }
        }
    
        $this->dispatch('togglePlayerStatus', $newStatus);
    }
    
    
    

    public function render()
    {
        return view('livewire.team-players');
    }
}
