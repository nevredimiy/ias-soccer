<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class UserBalance extends Component
{

    public $balance;

    public function mount()
    {
        $this->balance = auth()->user()->balance;
    }

    #[On('balanceUpdated')]
    public function updateBalance($balance)
    {
        $this->balance = $balance;
    }

    public function render()
    {
        return view('livewire.user-balance');
    }
}
