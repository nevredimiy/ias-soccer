<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;

class TeamLogoUploader extends Component
{
    use WithFileUploads;

    public $logo;
    public $team;

    public function updatedLogo()
    {
        // Проверяем, что загружен допустимый файл
        $this->validate([
            'logo' => 'image|mimes:webp,jpg,jpeg,png|max:2048', // Максимальный размер - 2MB
        ]);
    }

    public function render()
    {
        return view('livewire.team-logo-uploader');
    }
}
