<?php

namespace App\Livewire;

use Livewire\Component;

class ManagerEventType extends Component
{

    public ?string $activeKey = null; 
    public array $type = ['football' => 'goal', 'boots-icon' => 'assist', 'red-football' => 'autogoal', 'yellow-card-icon' => 'yellow_card', 'red-card-icon' => 'red_card'];

    public function selecteEventType($key)
    {
         $this->activeKey = $key;
         session(['selected-type' => $this->type[$key]]);

    }   

   

    public function render()
    {
        return view('livewire.manager-event-type');
    }
}
