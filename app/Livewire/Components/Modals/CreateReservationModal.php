<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\Reservation;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\State;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use App\Models\Brand;
use App\Models\Models;

class CreateReservationModal extends Component
{
    public $date_reservation;
    public $time_reservation;
    public $placa;
    public $marca;
    public $modelo;
    public $marca_id = null; 
    public $modelo_id = null;
    public $resultadosMarca = [];
    public $resultadosModelo = [];
    public $user_id;
    public $service_id;
    public $state_id = 1;
    public bool $show = false;
    protected $messages = [];
    public $vehiculos = [];
    public $vehicle_id = '';
    public function buscar($tipo)
    {
        if ($tipo === 'marca') {
            if (strlen($this->marca) < 1) {
                $this->resultadosMarca = [];
                return;
            }

            $this->resultadosMarca = Brand::where('name', 'LIKE', $this->marca . '%')
                ->limit(5)
                ->pluck('name', 'id')
                ->toArray();
        }
        if ($tipo === 'modelo') {
            if (strlen($this->modelo) < 1) {
                $this->resultadosModelo = [];
                return;
            }

            $this->resultadosModelo = Models::where('name', 'LIKE', $this->modelo . '%')
                ->limit(5)
                ->pluck('name', 'id')
                ->toArray();
        }
    }
    public function seleccionar($tipo,$id,$valor)
    {
            if ($tipo === 'marca') {
                $this->marca = $valor;
                $this->marca_id = $id;    

                $this->modelo = '';
                $this->modelo_id = null;

                $this->resultadosMarca = [];
            }

            if ($tipo === 'modelo') {
                $this->modelo = $valor;
                $this->modelo_id = $id;

                $this->resultadosModelo = [];
            }
    }
    protected function rules(): array
    {
        return [
            'placa' => 'required|min:6',
            'modelo' => 'required',
            'marca' => 'required',
            'date_reservation'  => 'required|date|after_or_equal:today',
            'time_reservation' => [
                'required',
                'string',
                Rule::unique('reservations', 'time_reservation')
                    ->where(function ($query) {
                        return $query->where('date_reservation', $this->date_reservation);
                    }),
            ],
        ];
    }
    protected function messages(): array
    {
         return [
            'placa.required' => 'La placa es obligatoria',
            'placa.min' => 'La placa debe tener al menos 6 caracteres',

            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',

            'date_reservation.required' => 'La fecha es obligatoria',
            'date_reservation.after_or_equal' => 'La fecha debe ser hoy o posterior',

            'time_reservation.required' => 'La hora es obligatoria',
            'time_reservation.unique' => 'Este horario ya está reservado',
        ];
    }
    #[On('openReservationModal')]
    public function open($date = null, $slot = null, $service_id=null)
    {
        $this->vehiculos = auth()->user()
        ->vehicles()
        ->with('model.brand')
        ->get();

        if ($date) {
            $this->date_reservation = $date?? null;
            $this->time_reservation = $slot?? null;
            $this->service_id = $service_id?? null;
        }

        $this->show = true;
    }
    public function save()
    {
        $isSlotTaken = Reservation::where('date_reservation', $this->date_reservation)
        ->where('time_reservation', $this->time_reservation)
        ->exists();

        if ($isSlotTaken) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Ese horario ya está ocupado',
            ]);
            return;
        }
        $isNewVehicle = $this->vehicle_id === 'new';

        if ($isNewVehicle) {
            $this->validate();
        }
        $vehicle = $isNewVehicle
        ? Vehicle::firstOrCreate(
            ['placa'    => $this->placa],
            ['model_id' => $this->modelo_id, 'user_id' => auth()->id()]
        )
        : Vehicle::findOrFail($this->vehicle_id);     
        
        try {
            Reservation::create([
                'vehicle_id' => $vehicle->id,
                'user_id' => auth()->id(),
                'service_id' => $this->service_id,
                'state_id' => $this->state_id,
                'date_reservation' => $this->date_reservation,
                'time_reservation' => $this->time_reservation,    
                'created_by' => auth()->id(),    
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'El horario ya fue reservado'
                ]);
                return;
        }
        
        $this->dispatch('reservationCreated');
        
        $this->show = false;
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Reserva registrada correctamente'
        ]);
    }
    public function render()
    {
        return view('livewire.components.modals.create-reservation-modal');
    }
}
