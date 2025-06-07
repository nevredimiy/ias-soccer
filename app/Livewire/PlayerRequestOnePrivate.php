<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use App\Models\Player;
use App\Models\PlayerTeam;
use App\Models\SeriesMeta;
use App\Models\SeriesPlayer;
use App\Models\Team;
use Illuminate\Support\Facades\DB;

class PlayerRequestOnePrivate extends Component
{

    public ?Event $event = null;
    public ?SeriesMeta $seriesMeta = null;
    public $regPlayers = [];
    public $playerPrice = 0;
    public $user = null;
    public $currentPlayer = null;
    public $countPlayersInSeries = 18;
    public $isSeriesClosed = false;

    public function mount($event, $playerPrice = 0)
    {
        //Непаримся. Берем первую серию. Так как у данного турнира всегда всего одна серия.
        $this->seriesMeta = SeriesMeta::with('seriesPlayers')->where('event_id', $this->event->id)->first();
        $this->event = $event;
        $this->playerPrice = $playerPrice == 0 ? round($this->seriesMeta->price / $this->countPlayersInSeries) : $playerPrice;
        $this->user = auth()->user();
        $this->currentPlayer = Player::query()->where('user_id', $this->user->id)->first();

        $this->isSeriesClosed = SeriesMeta::query()
            ->where('id', $this->seriesMeta->id)
            ->where('status_registration', 'closed')
            ->exists();

        $this->loadPlayers();

        // $this->openSeries(); // делал для теста.
       
    }

    public function BookingPlace($teamId, $playerNumber)
    {
       
        if (SeriesPlayer::where('series_meta_id', $this->seriesMeta->id)->where('player_id', $this->currentPlayer->id)->exists()) {
            session()->flash('error', 'Ви вже в цій серії!');
            return;
        }

        $balance = $this->user->balance;
        $reserved_balance = $this->user->reserved_balance;
        $notEnougth = $this->playerPrice - ($balance - $reserved_balance);
        
        if ($balance - $reserved_balance < $this->playerPrice){
            session()->flash('error_balance', "Недостатньо коштів на балансі! Мінімальна сумма $this->playerPrice грн.");
            return;
        }

        // Проверка баланса
        if ($balance - $reserved_balance < $this->playerPrice){
            session()->flash('error_balance', __("Недостатньо коштів на балансі! Не вистачає :amount грн. <span class='text-sm text-red-300'>(Мінімальна сумма :min грн. Заброньовано :reserved грн.)</span>", [
                'amount' => $notEnougth,
                'min' => $this->playerPrice,
                'reserved' => $reserved_balance
            ]));

            return;
        }

        try{

            SeriesPlayer::create([
                'series_meta_id' => $this->seriesMeta->id,
                'player_id' => $this->currentPlayer->id,
                'team_id' => $teamId,
                'player_number' => $playerNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // записываем игрока в команду
            PlayerTeam::query()->create([
                'player_id' => $this->currentPlayer->id,
                'team_id' => $teamId,
                'status' => 'main',
                'player_number' => $playerNumber,
            ]);

            // Бронируем сумму
            $this->user->increment('reserved_balance', $this->playerPrice);

            $this->loadPlayers();

        } catch (\Exception $e) {
            session()->flash('error', 'Сталася помилка під час бронювання місця.');
            Log::error($e->getMessage());
        }

    }

    public function deletePlayer()
    {
        $teamIds = Team::where('event_id', $this->event->id)->pluck('id');

        SeriesPlayer::where('player_id', $this->currentPlayer->id)
            ->where('series_meta_id', $this->seriesMeta?->id)
            ->first()?->delete();

        PlayerTeam::where('player_id', $this->currentPlayer->id)
            ->whereIn('team_id', $teamIds)
            ->first()?->delete(); // безопасный вызов
        
        $this->user->decrement('reserved_balance',  $this->playerPrice);

        session()->flash('success', 'Гравця успішно видалено зі складу команды.');

        // обновляем компонент
        $this->loadPlayers();
    }

    public function loadPlayers()
    {
        $this->regPlayers = [];

        $seriesPlayers = SeriesPlayer::with(['player', 'team'])
            ->where('series_meta_id', $this->seriesMeta->id)
            ->get();
        foreach($seriesPlayers as $seriesPlayer){

            if (!$seriesPlayer->player) {
                continue;
            }

            $this->regPlayers[$seriesPlayer->team_id][] = [
                'id' => $seriesPlayer->player_id,
                'first_name' => $seriesPlayer->player->first_name,
                'last_name' => $seriesPlayer->player->last_name,
                'photo' => $seriesPlayer->player->photo,
                'rating' => $seriesPlayer->player->rating,
                'verify_rating' => $seriesPlayer->player->verify_rating,
                'player_number' => $seriesPlayer->player_number ?? null,                
            ];
        }

        // Проверка - Закрывать серию или нет
        if($this->seriesMeta->status_registration == 'open' && $seriesPlayers->count() >= $this->countPlayersInSeries){
            $this->closeRegistrations($seriesPlayers);
        }
        
    }

    protected function closeRegistrations($seriesPlayers)
    {
        DB::transaction(function () use ($seriesPlayers) {
            $this->seriesMeta->update(['status_registration' => 'closed']);

            $seriesPlayers->load('player.user');

            foreach ($seriesPlayers as $seriesPlayer) {

                $user = optional($seriesPlayer->player)->user;

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
       DB::transaction(function () {
            $this->seriesMeta->update(['status_registration' => 'open']);

            $seriesPlayers = SeriesPlayer::with(['player', 'team'])
                ->where('series_meta_id', $this->seriesMeta->id)
                ->get();

            foreach ($seriesPlayers as $seriesPlayer) {
                $user = optional($seriesPlayer->player)->user;

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
        return view('livewire.player-request-one-private');
    }
}
