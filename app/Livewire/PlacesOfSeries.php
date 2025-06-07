<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Player;
use App\Models\SeriesPlayer;
use App\Models\User;
use App\Models\Matche;
use App\Models\Event;
use App\Models\SeriesMeta;
use App\Services\SeriesTemplatesService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PlayerTeam;
use App\Models\SeriesTeam;

class PlacesOfSeries extends Component
{
    public ?Matche $matche = null;
    public ?Team $team = null;
    public ?SeriesMeta $seriesMeta = null;

    public ?Event $event = null;
    public $user = null;
    public $playerId = null;
    public $seriesTeam = null;
    public $seriesColorTeams = [];
    public $formatDate = '';
    public $maxPlayer = 6;
    public $showModal = false;
    public $isPlayerReserve = false;
    public $regPlayers = null;
    public $minBalance = 0;
    public $desiredBalance = 0;
    public $statusRegistration = 'open';

    public function mount($team)
    {
        $this->team = $team;
        $this->user = Auth::user();
        $this->playerId = Player::where('user_id', $this->user->id)->value('id');
        $this->event = Event::with('tournament')->find($team->event_id);

        $this->loadSeriesMetaAndTeam();

        if ($this->team) {
            $this->maxPlayer = $this->team->max_players;
        }

        if ($this->playerId) {
            $this->checkStatusPlayer();
        }

        $this->getPlayerSeries();

        $this->initSeriesColorTeams();

    }

    #[On('team-selected')]
    public function updateSelectedTeam($team_id)
    {
        $this->team = Team::find($team_id);
        $this->event = Event::with('tournament')->find($this->team->event_id);

        $this->loadSeriesMetaAndTeam();
        $this->getPlayerSeries();
    }

    protected function loadSeriesMetaAndTeam()
    {
        if (!$this->team) {
            return;
        }

        $today = now()->timezone(config('app.timezone'));

        // Получаем ID всех series_meta, где участвует команда
        $seriesMetaIds = SeriesTeam::where('team_id', $this->team->id)->pluck('series_meta_id');
        
        if($seriesMetaIds->isEmpty()){
            $seriesMetaIds = SeriesMeta::where('event_id', $this->team->event_id)->pluck('id');
        }

        // Получаем ближайшую серию, которая еще не началась
        $this->seriesMeta = SeriesMeta::whereIn('id', $seriesMetaIds)
            ->where('start_date', '>', $today)
            ->where('status_registration', 'open')
            ->orderBy('start_date')
            ->first();

        if ($this->seriesMeta) {
            session(['currentSeriesMeta' => $this->seriesMeta->id]);

            // Находим участие команды в найденной серии
            $this->seriesTeam = SeriesTeam::where('series_meta_id', $this->seriesMeta->id)
                ->where('team_id', $this->team->id)
                ->first();

            $this->statusRegistration = $this->seriesTeam?->status;
        } else {
            $this->seriesTeam = null;
            $this->statusRegistration = null;
        }
    }


    protected function initSeriesColorTeams()
    {
        if (!$this->seriesMeta) {
            return;
        }

        $service = new SeriesTemplatesService();
        $templateSeries = $service->getTemplateShedule($this->event->tournament->count_teams);
        $teams = Team::with('color')->where('event_id', $this->team->event->id)->get()->toArray();

        foreach ($templateSeries[$this->seriesMeta->round - 1][$this->seriesMeta->series - 1] as $key => $teamIndex) {
            if (isset($teams[$teamIndex])) {
                $this->seriesColorTeams[$key]['name'] = $teams[$teamIndex]['name'];
                $this->seriesColorTeams[$key]['classColor'] = $service->getColorClass($teams[$teamIndex]['color']['name']);
                $this->seriesColorTeams[$key]['styleColor'] = $teams[$teamIndex]['color']['color_picker'];
            } else {
                $this->seriesColorTeams[$key]['name'] = 'Очікуємо';
                $this->seriesColorTeams[$key]['classColor'] = 'default-bg';
                $this->seriesColorTeams[$key]['styleColor'] = '#c9c9c9';
            }
        }

    }


    public function dropRegPlayer($player_id)
    {
        if (!$this->team || !$this->seriesMeta) return;

        SeriesPlayer::where('player_id', $player_id)
            ->where('team_id', $this->team->id)
            ->where('series_meta_id', $this->seriesMeta->id)
            ->delete();
        $this->user->decrement('reserved_balance', round($this->seriesMeta->price / 6));
        $this->getPlayerSeries();
    }

    #[On('togglePlayerStatus')]
    public function updatePlayerStatus($playerStatus)
    {
        if (!$this->team) return;

        if ($playerStatus == 'reserve') {
            $this->isPlayerReserve = true;
        } else {
            $this->isPlayerReserve = false;
        }

        if($this->user->id == $this->team->owner_id){
            $this->isPlayerReserve = false;
        }
    }

    protected function checkStatusPlayer()
    {
        $statusPlayer = PlayerTeam::where('player_id', $this->playerId)->where('team_id', $this->team->id)->first()?->status;

        if ($statusPlayer == 'reserve') {
            $this->isPlayerReserve = true;
            return;
        }
    }


    public function closeModal()
    {
        $this->showModal = false;
    }

    protected function playerIsReserve(): bool
    {
        return PlayerTeam::where('player_id', $this->playerId)
            ->where('team_id', $this->team->id)
            ->value('status') === 'reserve';
    }

    protected function hasEnoughBalance($price): bool
    {
        if (!$this->team || !$this->team->event || !$this->team->event->tournament) return false;
        
        if (!$this->user) return false;
        $balance = $this->user->balance;
        $reserved_balance = $this->user->reserved_balance;
        $netBalance = $balance - $reserved_balance;

        $required = $this->team->event->tournament->team_creator === 'admin'
            ? ceil($price / 18)
            : ceil($price / 6);

        $this->minBalance = $required;
        $this->desiredBalance = $required - $netBalance;

        return $netBalance < $required;
    }

    public function takePlace($playerNumber = 0)
    {
         if (!$this->team || !$this->seriesMeta) return;

        $eventId = $this->team->event->id;
        $teamId = $this->team->id;
        $playerId = $this->playerId;
       
        $event = Event::with('tournament')->find($eventId);

        // 1. Проверка: резервный игрок
        if ($this->playerIsReserve()) {
            return redirect()->to('/profile');
        }

        // 2. Проверка: баланс
        if ($this->hasEnoughBalance($this->seriesMeta->price)) {
            $this->showModal = true;
            return;
        }

        // 3. Проверка: не зарегистрирован ли уже
        $alreadyRegistered = SeriesPlayer::where('series_meta_id', $this->seriesMeta->id)->where('player_id', $this->playerId)->where('team_id', $this->team->id)->exists();

        if ($alreadyRegistered) {
            session()->flash('message', 'Ви вже зареєстровані в цій серії.');
            return;
        }

        if($playerNumber){
            SeriesPlayer::create([
                'player_id' => $playerId,
                'series_meta_id' => $this->seriesMeta->id,
                'player_number' => $playerNumber,
                'team_id' => $teamId
            ]);

            $this->user->increment('reserved_balance', round($this->seriesMeta->price / 6));
        }

        $this->getPlayerSeries();

    }

    protected function getPlayerSeries()
    {

        if (!$this->team || !$this->seriesMeta) return;
        
        $this->regPlayers = SeriesPlayer::with('player')
            ->where('series_meta_id', $this->seriesMeta->id)
            ->where('team_id', $this->team->id)
            ->get(); 
    
            
        // Проверка - Закрывать заявку или нет
        if($this->statusRegistration == 'open' &&  $this->regPlayers){
            $maxPlayers = $this->team->max_players;
            
            if($maxPlayers <= $this->regPlayers->count())
            {
                $this->closeRegistrations();
            }
        }
    }


    public function closeRegistrations()
    {        
        if (!$this->team || !$this->seriesMeta || !$this->seriesTeam) return;

        if ($this->seriesTeam->status === 'closed') return;

        // списание баланса
        $seriesPlayers = SeriesPlayer::query()
            ->where('series_meta_id', '=', $this->seriesMeta->id)
            ->where('team_id', '=', $this->team->id)
            ->get();

        $playerCount = $seriesPlayers->count();
        
        $seriesPrice = SeriesMeta::query()
            ->where('event_id', '=', $this->team->event_id)
            ->where('series', '=',  $this->seriesMeta->series)
            ->value('price');
        
        if ($playerCount === 0 || $seriesPrice === 0) return;

        $writeOffAmount = round($seriesPrice / $playerCount);

        $reserved_balance = round($seriesPrice / 6);
        
        // Списание баланса у игроков
        foreach ($seriesPlayers as $player) {
            if ($player->player && $player->player->user_id) {
                if ($this->user) {
                    $this->user->decrement('balance', $writeOffAmount);
                    $this->user->decrement('reserved_balance', $reserved_balance);
                }

            }
        }

        // Статус приема заявок закрывам для команды
        $this->seriesTeam->update(['status' => 'closed']);

        $this->statusRegistration = 'closed';

        // Проверка на закрытие всей серии
        $seriesTeamsWithCloseStatus = SeriesTeam::where('status', 'closed')->where('series_meta_id', $this->seriesMeta->id)->get();
        if ($seriesTeamsWithCloseStatus->count() == 3) {
            $this->seriesMeta->update(['status_registration' => 'closed']);
        }
        
    }

    #[On('updateMaxPlayer')]
    public function updateMaxPlayer($maxPlayer)
    {
        $this->maxPlayer = $maxPlayer;   
    
    }

    public function checkBalance()
    {
      $this->showModal = true;   
    }

    public function save()
    {
        $this->showModal = true;
    }

    public function render()
    {
        $reserved_balance = $this->user->reserved_balance;
        return view('livewire.places-of-series', ['reserved_balance' => $reserved_balance]);
    }
}
