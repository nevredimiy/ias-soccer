<?php

namespace App\Livewire;

use Livewire\Component;

class CitySelector extends Component
{
    public $cities;
    public $selectedCity;

    public function mount()
    {
        $this->cities = \App\Models\City::all(); // Получаем список городов
        $this->selectedCity = session('current_city', 2); // Загружаем текущий город из сессии
    }

    public function setCity($city_id)
    {
        session(['current_city' => $city_id]); 
        $this->selectedCity = $city_id;
        // $this->emit('cityUpdated'); // Можно использовать для других компонентов, если нужно
        $this->dispatch('city-selected', city_id: $city_id);
    }

    

    public function render()
    {
        return view('livewire.city-selector');
    }
}
