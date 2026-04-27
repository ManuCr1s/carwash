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
    public $date_init;
    public $price;
    //Slots nuevo para las 6 fotos
    public $photo1, $photo2, $photo3, $photo4, $photo5, $photo6, $observations;
    #[On('openDispatchModal')]
    public function openDispatchModal($id)
    {
        $this->reservaId = $id;

        $this->reset(['photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6']);

        $this->dispatch('init-filepond');   
         
        $this->show   = true;
    }
    protected function rules(): array
    {
        return [
            'observations' => 'max:255',
            'photo1' => 'required|image|max:2048',
        ];
    }
    protected function messages(): array
    {
        return [
            'observations.max' => 'Las observaciones no deben superar los 255 caracteres.',

            'photo1.required' => 'Debe subir una imagen.',
            'photo1.image' => 'El archivo debe ser una imagen válida (jpg, png, etc).',
            'photo1.max' => 'La imagen no debe superar los 2MB.',
        ];
    }
    public function processDispatch()
    {
        $this->validate();
        try {
            DB::transaction(function () {
                $user = auth()->user();
                $reservation = Reservation::findOrFail($this->reservaId);
                $order = $user->orders()
                        ->where('reserva_id', $this->reservaId)
                        ->whereNull('date_end') 
                        ->first();
                if($order){
                    $order->update([
                        'date_end' => now(),
                        'observations_end' => $this->observations,
                    ]);
                }else {
                    $this->dispatch('swal', [
                        'title' => 'Error',
                        'text' => 'No se encontró una orden de inicio para esta reserva.',
                        'icon' => 'error'
                    ]);
                    return;
                }
                // 2. Procesar las 6 fotos
                for ($i = 1; $i <= 6; $i++) {
                    $property = "photo$i";
                    if ($this->$property) {
                        $path = $this->$property->store('carwash/reservas/' . $this->reservaId, 'public');
                        
                        $order->photos()->create([
                            'url_image'  => $path,
                            'type_photo' => false,
                        ]);
                    }
                }

                // 3. Actualizar estado de reserva
                $reservation->update(['state_id' => 3]);
            });

            $this->show = false;
            $this->dispatch('tableRefresh');
            $this->dispatch('swal', [
                'title' => '¡Completado!',
                'text' => 'Vehículo lavado, ¡Para su despacho!',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['title' => 'Error', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
    }
    public function render()
    {
        return view('livewire.components.modals.dispatch-reservation-modal');
    }
}
