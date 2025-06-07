<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Team;
use App\Models\TeamPlayerApplication;
use Carbon\Carbon;  
use Illuminate\Support\Facades\DB;
use App\Services\SeriesTemplatesService;


class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Проверяем, есть ли пользователь в таблице players
        $player = Player::where('user_id', $user->id)->with('teams')->first();        
              
        // Если игрока нет, перенаправляем на создание профиля
        if (!$player) {
            return redirect()->route('players.create')->with('success', 'Ваш email було успішно підтверджено!. Заповніть, будь ласка, форму грвця для продовження роботи на сайті.');
        }


        $dateString = $player->birth_date;  
        if($dateString){
            $date = Carbon::createFromFormat('Y-m-d', substr($dateString, 0, 10));  
        }else {
            $date = Carbon::createFromFormat('Y-m-d', substr('1991-01-01', 0, 10));  
        }
        $formattedBithDate = $date->locale('uk')->translatedFormat('d F Y');  
    
        return view('profile.index', compact('player', 'formattedBithDate', 'user'));
    }

    public function updateRating(Request $request)
    {

        if($request->rating){
            $user = auth()->user();
            Player::where('user_id', $user->id)->update(['rating' => $request->rating]);
            return redirect()->route('profile')->with('success', 'Рейтинг змінено!');
        }
        return redirect()->route('profile')->with('notice', 'Рейтинг той самий!');
    }

    
    public function updatePlayerList(Request $request)
    {
        $playerId = $request->input('player_id');
        $teamId = $request->input('team_id');
        $userId = $request->input('user_id');
        $action = $request->input('action'); // Получаем, какая кнопка была нажата

        // Получаем игрока
        $player = Player::find($playerId);
        if (!$player) {
            return back()->with('error', 'Гравець не знайдений.');
        }

        // Если действие "accept"
        if ($action === 'accept') {
            // Добавляем игрока в команду с статусом 'reserve' через связующую таблицу player_teams
            $player->teams()->attach($teamId, ['status' => 'reserve']);

            // Удаляем заявку
            TeamPlayerApplication::where('user_id', $userId)->where('team_id', $teamId)->delete();

            // Получаем команду
            $team = Team::find($teamId);
            if (!$team) {
                return back()->with('error', 'Команда не знайдена.');
            }

            return back()->with('success', "Гравець {$player->last_name} підписаний у команду {$team->name}!");
        }

        // Если действие "reject"
        if ($action === 'reject') {
            // Удаляем заявку без добавления в команду
            TeamPlayerApplication::where('user_id', $userId)->where('team_id', $teamId)->delete();

            return back()->with('notice', "Заявку гравця {$player->last_name} відхилено.");
        }

        return back()->with('warning', 'Невідома дія.');
    }


    public function togglePlayerStatus(Request $request)
    {
        $teamId = $request->input('team_id');
        $playerId = $request->input('player_id');
        $newStatus = $request->input('action') === 'main' ? 'main' : 'reserve';
        $team = Team::findOrFail($teamId);
        $maxPlayers = $team->max_players;
        
        // Подсчёт "main"-игроков через player_teams
        $mainCount = DB::table('player_teams')
        ->where('team_id', $teamId)
        ->where('status', 'main')
        ->count();
        
        if ($mainCount < $maxPlayers || $newStatus === 'reserve') {
            DB::table('player_teams')
            ->where('team_id', $teamId)
            ->where('player_id', $playerId)
            ->update(['status' => $newStatus]);
            
            $player = Player::findOrFail($playerId);
            return back()->with('success', "Статус гравця {$player->last_name} оновлено.");
            // return route('profile')->with('success', "Статус гравця {$player->last_name} оновлено.");
        } else {
            return back()->with('error', "Статус не оновлено! Кількість основних гравців не повинна перевищувати {$maxPlayers}");
        }
    }
    
}
