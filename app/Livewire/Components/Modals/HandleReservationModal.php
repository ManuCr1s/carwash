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
    //Slots que se guardar en un array
    public $photos = [];
    public $date_init;
    public $price;
    //Slots nuevo para las 6 fotos
    public $photo1, $photo2, $photo3, $photo4, $photo5, $photo6, $observations;
    #[On('openHandleModal')]
    public function openHandleModal($id)
    {
        $this->reservaId = $id;

        //Se agrega para las 6 fotos $this->reset(['photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6'])
        $this->dispatch('init-filepond');   
         
        $this->show   = true;
    }
    protected function rules(): array
    {
        return [
            'observations' => 'max:255',
            'photo1' => 'required|image|max:2048',
            'price' =>  ['required', 'numeric', 'regex:/^\d{1,10}(\.\d{1,2})?$/']
        ];
    }
    protected function messages(): array
    {
        return [
            'observations.max' => 'Las observaciones no deben superar los 255 caracteres.',

            'photo1.required' => 'Debe subir una imagen.',
            'photo1.image' => 'El archivo debe ser una imagen válida (jpg, png, etc).',
            'photo1.max' => 'La imagen no debe superar los 2MB.',

            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.regex' => 'El precio debe tener máximo 10 enteros y 2 decimales.',
        ];
    }
    public function processReservation()
    {
        $this->validate();
        // Guardar las fotos
        try {
            DB::transaction(function () {
                $user = auth()->user();
                $reservation = Reservation::findOrFail($this->reservaId);

                // 1. Crear la Orden
                $order = $user->orders()->create([
                    'reserva_id'   => $this->reservaId,
                    'date_init'    => now(),
                    'observations' => $this->observations,
                    'price' => $this->price,
                ]);

                // 2. Procesar las 6 fotos
                for ($i = 1; $i <= 6; $i++) {
                    $property = "photo$i";
                    if ($this->$property) {
                        $path = $this->$property->store('carwash/reservas/' . $this->reservaId, 'public');
                        
                        $order->photos()->create([
                            'url_image'  => $path,
                            'type_photo' => true, // Antes del lavado
                        ]);
                    }
                }

                // 3. Actualizar estado de reserva
                $reservation->update(['state_id' => 2]);
            });

            $this->show = false;
            $this->dispatch('tableRefresh');
            $this->dispatch('swal', [
                'title' => '¡Completado!',
                'text' => 'Vehículo registrado, ¡a lavar!',
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['title' => 'Error', 'text' => $e->getMessage(), 'icon' => 'error']);
        }
        
    }
    public function render()
    {
        return view('livewire.components.modals.handle-reservation-modal');
    }
}
