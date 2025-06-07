<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{


    public function index()
    {
        return view('teams.index');
    }  
    
    public function show($id)
    {
        $team = Team::with([
            'color', 
            'players', 
            'event.seriesMeta.seriesTeams.team.color',
            'event.seriesMeta.matches.matchEvents'
            ])->find($id);


        // foreach($team->players as $player){
        //     $this->getPlayerStatic($player->id, $team);
        // }

        $matchesEvents = [];
        foreach($team->event->seriesMeta as $seriesMeta){
            foreach($seriesMeta->matches as $match){
                foreach($match->matchEvents as $event){
                    if(!isset($matchesEvents[$event->player_id])){
                        $matchesEvents[$event->player_id] = [
                            'goal' => 0,
                            'yellow_card' => 0,
                            'red_card' => 0,
                            

                        ];
                    }
                    if(!isset($matchesEvents[$event->player_id][$event->type])){
                        $matchesEvents[$event->player_id][$event->type] = 0;
                    }
                    $matchesEvents[$event->player_id][$event->type] += 1;
                    

                }
            }
        }

        $rating = 0;
        $totalRating = 0;
        $totalPlayers = 0;

        foreach($team->players as $player){
            $totalRating += $player->rating;
            $totalPlayers += 1;
        }

        $players = [];
        

        $rating = round($totalRating / $totalPlayers);

        return view('teams.show', compact('team', 'rating', 'matchesEvents'));
    }

    private function getPlayerStatic($playerId, $team)
    {
        $matchesEvents = $team->event->seriesMeta->load('matchesEvents');
        // $matchesEvents = $team->event->seriesMeta->matchesEvents()
        //     ->where('player_id', $playerId)
        //     ->get();
        // $goals = $matchesEvents->where('type', 'goal')->count();
        dump($matchesEvents);

    }
    
}
