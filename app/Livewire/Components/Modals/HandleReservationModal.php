<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Reservation;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class HandleReservationModal extends Component
{
    use WithFileUploads;
    public $reservaId;
    public bool $show = false;
    public $service_id;
    public $photos = [];
    public $date_init;
    #[On('openHandleModal')]
    public function openHandleModal($id)
    {
        $this->reservaId = $id;

        $this->dispatch('init-filepond');   
         
        $this->show   = true;
    }
    public function processReservation()
    {
        //n$this->validate();

        // Guardar las fotos
        try {
            $photoPaths = [];
            foreach ($this->photos as $photo) {
                $path = $photo->store('carwash/reservas/' . $this->reservaId, 'public');
                $photoPaths[] = $path;
            }
            $user = auth()->user();
            DB::transaction(function () use ($user, $photoPaths) {
                $reservation = Reservation::findOrFail($this->reservaId);

                $order = $user->orders()->create([
                    'reserva_id' => $this->reservaId ,
                    'date_init' => now(),
                    'observations' => 'Cliente habitual',
                ]);

                foreach ($photoPaths as $path) {
                    $order->photos()->create([
                        'url_image'  => $path,
                        'tipo_photo' => true,
                    ]);
                }

                $reservation->update([
                    'state_id' => 2 
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
        return view('livewire.components.modals.handle-reservation-modal');
    }
}
