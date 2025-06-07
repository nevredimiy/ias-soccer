<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use App\Models\EventTeamPrice;

class TeamRegistrationCost extends Component
{
    public array $prices = [];
    public int $countTeams = 0;

    public function __construct($event)
    {
        $this->countTeams = $event->tournament->count_teams;
        $minPrice = $event->price * 0.7;
        $this->prices = EventTeamPrice::where('event_id', $event->id)->get()->toArray();
        // dump($prices);
        // $this->prices = $this->calculateTeamPrices($this->countTeams, $minPrice, $event->price);
       
    }

    // function calculateTeamPrices(int $teamsCount, int $minPrice = 400, int $maxPrice = 600): array
    // {
    //     $prices = [];
        
    //     if ($teamsCount === 1) {
    //         return [$maxPrice];
    //     }

    //     $step = ($maxPrice - $minPrice) / ($teamsCount - 1);

    //     for ($i = 0; $i < $teamsCount; $i++) {
    //         $price = $minPrice + $i * $step;
    //         $prices[] = round($price);
    //     }

    //     return $prices;
    // }



    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.team-registration-cost');
    }
}
