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

class PlayerSeriesController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'shedule'); // по умолчанию "shedule"        
        
        $tournaments = Tournament::with('events.seriesMeta')
            ->whereIn('type', ['solo', 'solo_private'])
            ->get(); 
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

        return view('players.series.index', compact('tournaments', 'status'));
    }

    public function show($id)
    {

        $userId = auth()->id();
        $player = Player::where('user_id', $userId)->first();

        $seriesMeta = SeriesMeta::with(['stadium.location.district.city', 'teams.players', 'playerSeriesRegistration.player'])
           ->where('id', $id)
           ->firstOrFail();
        $eventId = SeriesMeta::where('id', $id)->value('event_id');
        $event = Event::find($eventId);
        
        $priceSeries = $seriesMeta->price;
        
        
        $playerPrice = round($priceSeries / 18);
        
        // Если турнир приватный
        if (
            $event->tournament->type === 'solo_private' &&
            !session()->has("access_granted_series_{$id}")
        ) {
            return view('players.series.access-form', compact('event'));
        }

        return view('players.series.show', compact(
            'player',
            'event',
            'seriesMeta',
            'playerPrice',
        ));
    }

    public function checkAccessCode(Request $request, $id)
    {
        $event = Event::with('tournament')->findOrFail($id);
        $seriesMeta = SeriesMeta::where('event_id', $id)->firstOrFail();

        $request->validate([
            'access_code1' => 'required|numeric|digits:1',
            'access_code2' => 'required|numeric|digits:1',
            'access_code3' => 'required|numeric|digits:1',
            'access_code4' => 'required|numeric|digits:1',
        ]);

        $enteredCode = $request->access_code1
                    . $request->access_code2
                    . $request->access_code3
                    . $request->access_code4;

        // Тут должна быть проверка с сохранённым кодом доступа.
        // Для примера: допустим код хранится в $event->access_code
        if ($enteredCode === $event->access_code) {
            session()->put("access_granted_series_{$seriesMeta->id}", true);
            return redirect()->route('players.series.show', $seriesMeta->id);
        }

        return redirect()
            ->back()
            ->withErrors(['access_code' => 'Невірний код доступу'])
            ->withInput();
    }


}
