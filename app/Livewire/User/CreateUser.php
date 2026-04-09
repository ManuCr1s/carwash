<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
class CreateUser extends Component
{
    public function create()
    {
        $this->dispatch('openCreateModal');
    }
    public function render()
    {
        return view('livewire.user.create-user')->layout('layouts.app');
    }
}
