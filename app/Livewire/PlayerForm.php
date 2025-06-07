<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Player;
// use Livewire\WithFileUploads;

class PlayerForm extends Component
{

    // use WithFileUploads;

    public $lastname;
    public $firstname;
    public $day;
    public $month;
    public $year;
    public $rating = 0;
    public $phone;
    public $tg;
    //  public $photo; // для загрузки файла
    
     protected $rules = [
        'lastname' => 'required|string|max:255',
        'firstname' => 'required|string|max:255',
        'day' => 'required|integer|min:1|max:31',
        'month' => 'required|integer|min:1|max:12',
        'year' => 'required|digits:4',
        'rating' => 'required|integer|min:0|max:10',
        'phone' => 'nullable|string|max:20',
        'tg' => 'nullable|string|max:50',
        // 'photo' => 'nullable|image|max:1024', // max 1MB
    ];

    // public function updatedPhoto()
    // {
    //     $this->validateOnly('photo');
    // }

    public function submit()
    {
        $this->validate();

        $player = new Player();
        $player->lastname = $this->lastname;
        $player->firstname = $this->firstname;
        $player->day = $this->day;
        $player->month = $this->month;
        $player->year = $this->year;
        $player->rating = $this->rating;
        $player->phone = $this->phone;
        $player->tg = $this->tg;

        // if ($this->photo) {
        //     $path = $this->photo->store('players/photos', 'public');
        //     $player->photo_path = $path; // поле с фото в БД
        // }

        $player->save();

        session()->flash('message', 'Гравця успішно створено!');

        // Очистить форму
        $this->reset(['lastname', 'firstname', 'day', 'month', 'year', 'rating', 'phone', 'tg', 'photo']);
    }


    public function render()
    {
        return view('livewire.player-form');
    }
}
