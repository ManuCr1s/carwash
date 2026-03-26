<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation;

class DispatchReservationModal extends Component
{
    use WithFileUploads;
    public bool $show = false;
    public $reservaId;
    public $photos = [];
    #[On('openDispatchModal')]
    public function openDispatchModal($id)
    {
        $this->reservaId = $id;

        $this->dispatch('init-filepond');   
         
        $this->show   = true;
    }
    public function processDispatch(){
        try {
            $photoPaths = [];
            foreach ($this->photos as $photo) {
                $path = $photo->store('carwash/reservas/' . $this->reservaId, 'public');
                $photoPaths[] = $path;
            }
            $user = auth()->user();
            DB::transaction(function () use ($user, $photoPaths) {
                $reservation = Reservation::findOrFail($this->reservaId);
                $order = $user->orders()
                        ->where('reserva_id', $this->reservaId)
                        ->whereNull('date_end') 
                        ->first();
                if($order){
                    $order->update([
                        'date_end' => now(),
                    ]);
                }else {
                    $this->dispatch('swal', [
                        'title' => 'Error',
                        'text' => 'No se encontró una orden de inicio para esta reserva.',
                        'icon' => 'error'
                    ]);
                    return;
                }
                foreach ($photoPaths as $path) {
                    $order->photos()->create([
                        'url_image'  => $path,
                        'tipo_photo' => false,
                    ]);
                }
                $reservation->update([
                    'state_id' => 3 
                ]);
            });
            $this->show = false;
            $this->photos = [];
            // Evento para limpiar FilePond en JS
            $this->dispatch('tableRefresh');
            $this->dispatch('swal', [
                'title' => '¡Completado!',
                'text' => 'La reserva ha sido atendida con éxito.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.components.modals.dispatch-reservation-modal');
    }
}
