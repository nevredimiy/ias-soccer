<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\TeamPlayerApplication;
use App\Models\Player;
use App\Models\PlayerTeam;

class JoinTeam extends Component
{

    public $teamId; 
    public $applied = false;
    public $isInTeam = false;

    public function mount($teamId)
    {
        $this->teamId = $teamId;

        $userId = Auth::id();

        // Проверка: есть ли уже заявка
        $hasApplication = TeamPlayerApplication::where('team_id', $teamId)
            ->where('user_id', $userId)
            ->exists();

        // Проверка: состоит ли уже в команде через player_teams
        $player = \App\Models\Player::where('user_id', $userId)->first();

        
        $isInTeam = $player && $player->teams()->where('team_id', $teamId)->exists();

        $this->applied = $hasApplication || $isInTeam;

     

        $isInTeam = PlayerTeam::where('team_id', $teamId)->where('player_id', $player->id)->first();
        if($isInTeam){
            $this->isInTeam = true;
        }      
        
    }


    public function apply()
    {
        $user = Auth::user();


    
        // Проверка: есть ли уже заявка от игрока в эту команду
        $existingApplication = TeamPlayerApplication::where('user_id', $user->id)
            ->where('team_id', $this->teamId)
            ->exists();
    
        // Проверка: состоит ли уже игрок в этой команде (через pivot)
        $player = Player::where('user_id', $user->id)->first();
    
        $isInTeam = $player && $player->teams()->where('team_id', $this->teamId)->exists();
    
        if ($existingApplication || $isInTeam) {
            session()->flash('message', 'Вы уже подали заявку в эту команду или уже состоите в ней.');
            return;
        }
    
        // Записываем заявку
        TeamPlayerApplication::create([
            'user_id' => $user->id,
            'team_id' => $this->teamId,
            'status' => 'pending',
        ]);
    
        // Делаем кнопку неактивной
        $this->applied = true;
    
        session()->flash('message', 'Заявка успешно отправлена.');
    }
    

    public function render()
    {
        return view('livewire.join-team');
    }
}
