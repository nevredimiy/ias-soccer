<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Event;
use App\Models\Player;
use App\Models\EventTeamPrice;
use App\Models\SeriesTeam;

class TeamList extends Component
{

    public $teams;
    public $activeTeamId;
    public $userId;
    
   public function mount()
    {
        $this->userId = auth()->id();
        $today = now()->timezone(config('app.timezone'));

        // Получаем ID игрока
        $playerId = \DB::table('players')
            ->where('user_id', $this->userId)
            ->value('id');

        // Получаем ID команд, где игрок участвует
        $playerTeamIds = \DB::table('player_teams')
            ->where('player_id', $playerId)
            ->pluck('team_id')
            ->toArray();

        // Получаем команды с предзагрузкой, фильтруем по сериям > сегодня
        $this->teams = Team::with(['event.seriesMeta' => function($query) use ($today) {
                $query->where('start_date', '>', $today);
            }, 'event.seriesMeta.stadium', 'color', 'players'])
            ->where(function ($query) use ($playerTeamIds) {
                $query->where('owner_id', $this->userId)
                    ->orWhereIn('id', $playerTeamIds);
            })
            ->orderByDesc('id')
            ->get()
            // Оставляем только команды, у которых есть будущие серии
            ->filter(function ($team) {
                return $team->event && $team->event->seriesMeta->isNotEmpty();
            })
            ->values(); // переиндексация
        
        // Установка активной команды
        $this->activeTeamId = session('selected-team', optional($this->teams->first())->id);

        // Вычисление суммы для команд со статусом "awaiting_payment"
        $awaitingPaymentTeams = $this->teams->where('status', 'awaiting_payment')->sortBy('id');
        
        
        foreach ($awaitingPaymentTeams as $t) {
            $eventTeams = Team::where('event_id', $t->event_id)->pluck('id')->toArray();
            $keyTeam = array_search($t->id, $eventTeams);
            $eventTeamPrices = EventTeamPrice::where('event_id', $t->event_id)->get();
            if ($keyTeam) {
                $t->price = $eventTeamPrices[$keyTeam]->price;
            }
        }
    }

    

    public function selectTeam($teamId)
    {
        
        $this->activeTeamId = $teamId;
        $this->dispatch('team-selected', team_id: $teamId);
        session(['selected-team' => $teamId]);
    }

    public function render()
    {
        return view('livewire.team-list');
    }
}
