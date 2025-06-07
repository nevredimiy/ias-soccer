<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Collection;
use App\Models\Matche;
use App\Models\SeriesMeta;
use App\Models\TeamColor;
use App\Services\SeriesTemplatesService;

class Shedule extends Component
{    
    public $seriesGroupedByRound = [];
    public $templateMatches = [];
    public $event;
    public $teams;
    public $teamColorClasses = [];
    public $series = [];
    public $colorClasses = [];
    public $colorNames = [];
 

    public function __construct(Collection $teams, $event)

    {

        $this->teams = $teams;
        $this->event = $event;
        
        $service = new SeriesTemplatesService();

        $this->series = $service->getTemplateShedule($event->tournament->count_teams);
        $this->templateMatches = $service->getMatchTemplate();
        $this->colorClasses = $service->getColorClasses();
        $this->colorNames = array_keys($this->colorClasses);

        // Получаем все серии данного турнира и группируем их по турам
        $seriesMetas = SeriesMeta::query()->where('event_id', $event->id)->get();
        $this->seriesGroupedByRound = $seriesMetas->groupBy('round');

        $this->seriesGroupedByRound = $seriesMetas->groupBy('round')->map(function ($group) {
            $first = $group->first();
            return $group->map(function ($item) use ($first) {
                return [
                    'start_date' => \Carbon\Carbon::parse($first->start_date)->locale('uk')->translatedFormat('d F'),
                    'start_day' => \Carbon\Carbon::parse($first->start_date)->locale('uk')->translatedFormat('l'),
                    'start_time' => \Carbon\Carbon::parse($item->start_time)->format('H:i'),
                    'series' => $item->series,
                ];
            });
        });

               

        // Получаем все матчи турнира
        $matches = Matche::with(['team1.color', 'team2.color'])->where('event_id', $event->id)->get();

        $countMatches = $event->tournament->count_series * $event->tournament->count_rounds * $event->tournament->count_matches;

        if($matches->count() == $countMatches){
            $this->colorNames = [];
            foreach($this->teams as $team){
                $this->colorNames[] = $team->color->name;
            }            
        }       
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.shedule', [
            'seriesGroupedByRound' => $this->seriesGroupedByRound,
            'templateMatches' => $this->templateMatches,
            'event' => $this->event,
            'teams' => $this->teams,
            'teamColorClasses' => $this->teamColorClasses,
            'series' => $this->series,
            'colorClasses' => $this->colorClasses,
            'colorNames' => $this->colorNames,
        ]);
        
    }
}
