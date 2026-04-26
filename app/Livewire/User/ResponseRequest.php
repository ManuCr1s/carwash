<?php

namespace App\Livewire\User;

use Livewire\Component;

class ResponseRequest extends Component
{
    public function create()
    {
        $this->dispatch('openCreateReservaModal');
    }
    public function render()
    {
        return view('livewire.user.response-request')->layout('layouts.app');
    }
}
