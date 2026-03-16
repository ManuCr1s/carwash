<?php

namespace App\Livewire\Components\Buttons;

use Livewire\Component;

class Social extends Component
{
    public $provider;
    public $label;

    public function mount($provider,$label = null){
        $this->provider = $provider;
        $this->label = $label ?? "Iniciar sesion con ". ucfirst($provider);
    }
    public function redirectToProvider(){
        return redirect()->route('auth.social.provider',['provider' => $this->provider]);
    }
    public function render()
    {
        return view('livewire.components.buttons.social');
    }
}
