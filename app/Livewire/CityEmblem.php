<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CIty;
use Livewire\Attributes\On; 

class CityEmblem extends Component
{

    public $city;
    public $cityId = null;

    public function mount()
    {
        $currentCity = session('current_city', 2);
        $this->city = City::where('id', '=', $currentCity)->first();
    }

    #[On('city-selected')] 
    public function updateCityId($city_id)
    {
        $this->city = City::where('id', $city_id)->first();
    }

    public function render()
    {
        return view('livewire.city-emblem');
    }
}
