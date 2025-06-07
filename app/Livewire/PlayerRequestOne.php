<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SeriesMeta;
use App\Models\Event;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\Team;
use App\Models\PlayerSeriesRegistration;
use Illuminate\Support\Facades\DB;

class PlayerRequestOne extends Component
{

    public ?Event $event = null;
    public ?SeriesMeta $seriesMeta = null;
    public $players = [];
    public $playerPrice = 0;
    public $user = null;
    public $currentPlayer = null;
    public $countPlayersInSeries = 18;
    public $isSeriesClosed = false;

    public $missingAmount = 0;
    

    public function mount($event, $playerPrice = 0, $seriesMeta = null)
    {

        $this->event = $event;
        $this->playerPrice = $playerPrice;
        $this->seriesMeta = $seriesMeta ? $seriesMeta : SeriesMeta::with('playerSeriesRegistration')->where('event_id', $this->event->id)->first();
        
        $this->user = auth()->user();
        $this->currentPlayer = Player::query()->where('user_id', $this->user->id)->first();

        $this->isSeriesClosed = SeriesMeta::query()
            ->where('id', $this->seriesMeta->id)
            ->where('status_registration', 'closed')
            ->exists();

        $this->loadPlayers();

        // $this->openSeries(); // для теста.
    }

    public function loadPlayers()
    {        
        $this->players = [];

        $playersSeriesRegistration = PlayerSeriesRegistration::with('player.user')
            ->where('series_meta_id', $this->seriesMeta->id)
            ->get();

        foreach ($playersSeriesRegistration as $item) {
            if ($item->player) {
                $this->players[] = $item->player;
            }
        }

        // Проверка - Закрывать серию или нет
        if (
            $this->seriesMeta->status_registration == 'open' &&
            $playersSeriesRegistration->count() >= $this->countPlayersInSeries
        ) {
            $this->closeRegistrations($playersSeriesRegistration);
        }
    }


    public function BookingPlace()
    {
        
        // if(in_array($this->currentPlayer->id, array_column($this->players, 'id'))){
        //     session()->flash('error', 'Ви вже в серії!');
        //     return;
        // }

        if (PlayerSeriesRegistration::where('series_meta_id', $this->seriesMeta->id)
            ->where('player_id', $this->currentPlayer->id)
            ->exists()) {
            session()->flash('error', 'Ви вже в серії!');
            return;
        }

        $balance = $this->user->balance;
        $reserved_balance = $this->user->reserved_balance;
        $notEnougth = $this->playerPrice - ($balance - $reserved_balance);
        $this->missingAmount = $notEnougth;
        
        // Проверка баланса
        if ($balance - $reserved_balance < $this->playerPrice){
            session()->flash('error_balance', __("Недостатньо коштів на балансі! Не вистачає :amount грн. <span class='text-sm text-red-300'>(Мінімальна сумма :min грн. Заброньовано :reserved грн.)</span>", [
                'amount' => $notEnougth,
                'min' => $this->playerPrice,
                'reserved' => $reserved_balance
            ]));

            return;
        }

        // записываем игрока в команду
        $this->addPlayer($this->currentPlayer->id);

        // обновляем компонент
        $this->loadPlayers();
       
    }

    protected function addPlayer($playerId)
    {
        DB::transaction(function () use ($playerId) {
            PlayerSeriesRegistration::create([
                'series_meta_id' => $this->seriesMeta->id,
                'player_id' => $playerId
            ]);

            $this->user->increment('reserved_balance', $this->playerPrice);
        });
    }


    public function deletePlayer()
    {
        DB::transaction(function () {
            $registration = PlayerSeriesRegistration::where('player_id', $this->currentPlayer->id)
                ->where('series_meta_id', $this->seriesMeta->id)
                ->first();

            if (!$registration) {
                session()->flash('error', 'Гравця не знайдено в складі.');
                return;
            }

            $registration->delete();

            if ($this->user->reserved_balance >= $this->playerPrice) {
                $this->user->decrement('reserved_balance', $this->playerPrice);
            }

            session()->flash('success', 'Гравця успішно видалено зі складу.');
            $this->loadPlayers();
        });
    }


    protected function closeRegistrations($playersSeriesRegistration)
    {
        DB::transaction(function () use ($playersSeriesRegistration) {
            $this->seriesMeta->update(['status_registration' => 'closed']);

            foreach ($playersSeriesRegistration as $playerSR) {
                if (!$playerSR->player) {
                    continue;
                }

                $user = $playerSR->player->user;

                if ($user) {
                    $user->decrement('balance', $this->playerPrice);
                    $user->decrement('reserved_balance', $this->playerPrice);
                }
            }

            $this->isSeriesClosed = true;
        });
    }


    protected function openSeries()
    {
        
        if($this->seriesMeta->status_registration == 'open'){
            return;
        }
        
        DB::transaction(function () {
            $this->seriesMeta->update(['status_registration' => 'open']);

            $playersSeriesRegistration = PlayerSeriesRegistration::with('player.user')
                ->where('series_meta_id', $this->seriesMeta->id)
                ->get();

            foreach ($playersSeriesRegistration as $playerSR) {
                $user = optional($playerSR->player)->user;

                if ($user) {
                    $user->increment('balance', $this->playerPrice);
                    $user->increment('reserved_balance', $this->playerPrice);
                }
            }

            $this->isSeriesClosed = false;
        });
    }

    public function render()
    {
        return view('livewire.player-request-one');
    }
}
