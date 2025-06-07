<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Player;
use App\Models\SeriesPlayer;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;

class RemovePlayerFromTeam extends Component
{
    public $player;
    public $team;
    public $confirming = false;

    public function mount($player, $team)
    {
        $this->player = $player;
        $this->team = $team;
    }

    public function confirm()
    {
        $this->confirming = true;
    }

    public function cancel()
    {
        $this->confirming = false;
    }

    public function remove()
    {
        if (!$this->team) {
            session()->flash('error', 'Гравець не належить до жодної команди.');
            return;
        }

        if (Auth::id() !== $this->team->owner_id) {
            session()->flash('error', 'У вас немає права видалити цього гравця.');
            return;
        }

        $playerId = data_get($this->player, 'id');
        $player = Player::find($playerId);

        if (!$player) {
            session()->flash('error', 'Гравця не знайдено.');
            return;
        }

         $seriesPlayer = SeriesPlayer::where('player_id', $playerId)
            ->where('team_id', $this->team->id)
            ->first();
        
        if($seriesPlayer){
            $seriesPlayer->delete();
        }
        
        // Удаляем игрока из команды (из pivot-таблицы)
        $player->teams()->detach($this->team->id);

        // Закрываем модалку
        $this->confirming = false;

        // Успешное сообщение
        session()->flash('success', "Гравця {$player->full_name} успішно видалено з команди {$this->team->name}.");
        
        // Если нужно скрыть карточку игрока
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.remove-player-from-team');
    }
}
