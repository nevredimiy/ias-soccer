<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use App\Models\Event;
use App\Models\SeriesMeta;
use App\Services\SeriesTemplatesService;
use Livewire\Attributes\On;

class TeamShedule extends Component
{
    public ?Event $event = null;
    public $teams = null;
    public array $colorClasses = [];
    public array $colorNames = [];
    public array $templateCalendar = [];
    public $seriesMetas = null;
    public array $currentRound = [];
    public array $teamsForTable = [];

    public function mount($event)
    {
        $this->event = $event;
        $this->getTemplateCalendar();
        $this->teams = Team::with('color')->where('event_id', $event->id)->get();
        $this->teamsForTable = session()->get('teamsForTable', []);
    }

    #[On('eventSelected')]
    public function updateEvent($eventId)
    {
        $this->event = Event::find($eventId);
       
        $this->getTemplateCalendar();
    }

    #[On('teamsForTable')]
    public function refreshTeamsForTable($teamsForTable)
    {
        
        $this->teamsForTable = $teamsForTable;
    }

    protected function getTemplateCalendar()
    {
        $event = $this->event;
        $count_teams = $event->tournament->count_teams;
        $service = new SeriesTemplatesService();
        $this->colorClasses = $service->getColorClasses();
        $this->colorNames = array_keys($this->colorClasses);

        $this->templateCalendar = $service->getTemplateCalendar($count_teams);
        if($event->tournament->type == 'solo' && $event->tournament->subtype == 'regular' && $event->tournament->count_rounds > 1){
            $teamplate = [];
            for($i=0; $i<$event->tournament->count_rounds; $i++){
                $teamplate[0][$i] = $this->templateCalendar[0][0];
            }
            
            $this->templateCalendar = $teamplate;
        }      
        
        $this->seriesMetas = SeriesMeta::select('series', 'round', 'start_date')
            ->where('event_id', $event->id)
            ->orderBy('series')
            ->orderBy('round')
            ->get()
            ->groupBy('round')
            ->toArray();
        

        $this->currentRound = [
                'round_number' => 1,
                'event_id' => $event->id,
                'series_number' => 1
            ];
        
    }

    public function selectedRound($roundNumber, $eventId, $seriesNumber)
    {
        
        $this->currentRound = [
            'round_number' => $roundNumber,
            'event_id' => $this->event->id,
            'series_number' => $seriesNumber
        ];

        $this->dispatch('currentRound', $this->currentRound);
    }

    public function render()
    {
        return view('livewire.team-shedule');
    }
}
