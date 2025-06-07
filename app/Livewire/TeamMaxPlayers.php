<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SeriesTeam; 
use App\Models\Team; 

class TeamMaxPlayers extends Component
{

    public ?Team $team = null;
    public $max_players = 6;
    public ?string $seriesTeamStatus = 'open';

    protected $rules = [
        'max_players' => 'required|integer|min:1|max:20',
    ];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->max_players = $this->team->max_players;
        $this->getSeriesTeamStatus();   
    }

    public function saveMaxPlayers()
    {
         $this->validate();

        $this->getSeriesTeamStatus();
        if (is_null($this->seriesTeamStatus)) {
            session()->flash('error', 'Не вдалося визначити статус команди в серії.');
            return;
        }

        if ($this->team && $this->seriesTeamStatus === 'open') {
            $this->team->update(['max_players' => $this->max_players]);
            $this->dispatch('updateMaxPlayer', $this->max_players);
            session()->flash('success', 'Кількість гравців оновлено!');
        }else{
            session()->flash('error', 'Неможна змінити кількість гравців. Серія вже закрита!');
        }
    }

    protected function getSeriesTeamStatus()
    {
        $this->seriesTeamStatus = SeriesTeam::query()
            ->where('series_meta_id', session('currentSeriesMeta', 0))
            ->where('team_id', $this->team->id)
            ->value('status');
    }

    public function render()
    {
        return view('livewire.team-max-players');
    }
    
}
