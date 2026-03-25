<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Reservation;
use Livewire\Attributes\On;

class HandleReservationModal extends Component
{
    public bool $show = false;
    #[On('openHandleModal')]
    public function openHandleModal($id)
    {
        
        $reserva = Reservation::find($id);

        $this->reservaId = $reserva->id;
        $this->show   = true;
    }
    public function render()
    {
        return view('livewire.components.modals.handle-reservation-modal');
    }
}
