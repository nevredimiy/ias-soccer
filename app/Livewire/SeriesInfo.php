<?php

namespace App\Livewire;

use Livewire\Component;

class SeriesInfo extends Component
{

    public $seriesMeta;
    public $event;
    public $playerPrice;

    public function mount($seriesMeta, $event, $playerPrice = null)
    {
        $this->playerPrice = $playerPrice;
        $this->event = $event;
        $this->seriesMeta = $this->calculateRating($seriesMeta, $event->tournament->type);
    }

    protected function calculateRating($seriesMeta, $type)
    {
        $totalRating = 0;
        $totalPlayers = 0;

        if($type == 'solo'){
            foreach($seriesMeta->playerSeriesRegistration as $playerSR){
                $totalRating += $playerSR->player->rating;
                $totalPlayers += 1;
            }
        }else{
            $seriesMeta->load('seriesPlayers');
            
            foreach($seriesMeta->seriesPlayers as $playerSP){
                $totalRating += $playerSP->player->rating;
                $totalPlayers += 1;
            }
        }

        $seriesMeta->average_player_rating = $totalPlayers > 0
            ? round($totalRating / $totalPlayers)
            : 0;

        return $seriesMeta;
    }

    


    public function render()
    {
        return view('livewire.series-info');
    }
}
