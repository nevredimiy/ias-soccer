<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\SeriesMeta;
use App\Models\Tournament;
use App\Models\Player;
use App\Models\Team;
use App\Models\TeamColor;
use App\Models\Matche;
use Illuminate\Http\Response;



class TeamSeriesController extends Controller
{
   public function index(Request $request)
   {
        $status = $request->query('status', 'shedule'); // по умолчанию "shedule"

        $tournaments = Tournament::with('events.seriesMeta')->where('type', 'team')->get(); 
        $today = \Carbon\Carbon::now()->timezone(config('app.timezone'))->format('Y-m-d H:i:s'); 

        foreach($tournaments as $tournament){
            foreach($tournament->events as $event){
                foreach($event->seriesMeta as $series){
                    
                    if(
                        \Carbon\Carbon::parse($series->start_date)->timezone(config('app.timezone'))->format('Y-m-d H:i:s') > $today
                        && $series->round == 1){
                        $tournament->isShedule = $series;   
                    }

                    if ($status === 'started' && $series->round > 1) {
                        $tournament->isStarted = $series;   
                    }
                }
            }
        }


       
        return view('teams.series.index', compact('tournaments', 'status'));
   }


   public function show($id)
   {
        $userId = auth()->id();
        $player = Player::where('user_id', $userId)->first();   

        $seriesMeta = SeriesMeta::with(['stadium.location.district.city', 'teams.players'])
           ->where('id', $id)
           ->firstOrFail();
        
        $eventId = $seriesMeta->event_id;
        
        $event = Event::with('tournament')->find($eventId);
       
       
        $teams = Team::where('event_id', $eventId)->with(['players', 'color'])->get()->map(function ($team) {
           $statusColors = [
               'urgent' => '#b5e61d',
               'needed' => '#22b14c',
               'closed' => '#ed1c24',
           ];
           $statusTexts = [
               'urgent' => 'Терміново потрібні гравці',
               'needed' => 'Потрібні гравці',
               'closed' => 'Заявка закрита',
           ];

           // Средний рейтинг игроков
           $totalRating = 0;
           $totalPlayers = 0;

           // Получаем рейтинг игроков
           $players = $team->players;
           $totalRating = $players->sum('rating');
           $totalPlayers = $players->count();
   
           // Добавляем подготовленные данные в объект команды
           $team->status_color = $statusColors[$team->player_request_status] ?? '#ed1c24';
           $team->status_text = $statusTexts[$team->player_request_status] ?? 'Невідомий статус';
           $team->team_color = $team->color->color_picker ?? '#F7E10E'; // Цвет команды
           $team->rating = $totalPlayers > 0 ? round($totalRating / $totalPlayers) : 0;

           return $team;
        });

        if($event->tournament->count_teams == 3){
            $playerPrice = round($seriesMeta->price / 18);

        }else {
            $playerPrice = [
                6 => round($seriesMeta->price / 6),
                9 => round($seriesMeta->price / 9),
            ];
        }

       return view('teams.series.show', compact(
                                            'teams', 
                                            'player', 
                                            'event', 
                                            'seriesMeta',
                                            'playerPrice',                                            
                                        )
                                    );
   }
}
