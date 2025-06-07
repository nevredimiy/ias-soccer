<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Team;

class TeamEditor extends Component
{
    use WithFileUploads;

    public $team;
    public $name;
    public $logo;
    public $isEditing = false;
    public $logoFileName;
    protected $listeners = ['editTeam' => 'openEditor'];

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->name = $team->name;

        $this->isEditing = false;

        $this->listeners = [
            'editTeam' => 'openEditor'
        ];
    }

    public function toggleEditing()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function openEditor($teamId)
    {
        $this->isEditing = $this->team->id == $teamId;
    }

    public function updateName()
    {
        $this->validate(['name' => 'required|string|max:255']);
        $this->team->update(['name' => $this->name]);
        session()->flash('success', 'Назва команди оновлена!');
    }

    // Метод, который вызывается при изменении переменной $logo  
    public function updatedLogo()  
    {  
        if ($this->logo) {  
            $this->logoFileName = $this->logo->getClientOriginalName(); // Получаем имя файла  
        } else {  
            $this->logoFileName = null; // Сбрасываем, если файл не выбран  
        }  
    }  

    public function updateLogo()
    {
        $this->validate(['logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048']);
        if ($this->logo) {

            // Удаляем старый логотип, если он существует
            if ($this->team->logo && file_exists(storage_path('app/public/' . $this->team->logo))) {
                unlink(storage_path('app/public/' . $this->team->logo));
            }
            
            $path = $this->logo->store('img/team_logo', 'public');
            $this->team->update(['logo' => $path]);
        }

        session()->flash('success', 'Логотип оновлений!');
    }

    public function render()
    {
        return view('livewire.team-editor');
    }
}
