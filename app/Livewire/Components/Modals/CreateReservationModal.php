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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
    public function updatedMarca($value)
    {
        $marca = Brand::where('name', $value)->first();

        if ($marca) {
            $this->marca_id = $marca->id;

        } else {
            $this->marca_id = null;
        }

        // limpiar modelo
        $this->modelo = '';
        $this->modelo_id = null;
        $this->resultadosModelo = [];
    }
    
    public function buscar($tipo)
    {
        if ($tipo === 'marca') {
            if (!empty($this->marca_id)) {
                $this->resultadosMarca = [];
                return;
            }

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
            if (empty($this->marca_id) || strlen($this->modelo) < 1) {
                $this->resultadosModelo = [];
                return;
            }
 
            $modeloExacto = Models::where('brand_id', $this->marca_id)
                ->where('name', trim($this->modelo))
                ->first();

            if ($modeloExacto) {
                $this->modelo_id = $modeloExacto->id;
                $this->resultadosModelo = []; 
                return;
            }

            $this->resultadosModelo = Models::where('brand_id', $this->marca_id)
                ->where('name', 'LIKE', $this->modelo . '%')
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
            'placa' => 'required|size:6',
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
            'placa.size' => 'La placa debe tener 6 caracteres',

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
   
    public function save(): void
    {
        $this->validateReservation();

        $service = Service::findOrFail($this->service_id);

        try {
            DB::transaction(function () use ($service) {
                $vehicle  = $this->resolveVehicle();
                $interval = $this->buildTimeInterval((float) $service->duration);

                $this->ensureSlotIsAvailable($interval);

                Reservation::create([
                    'vehicle_id'       => $vehicle->id,
                    'user_id'          => auth()->id(),
                    'service_id'       => $this->service_id,
                    'state_id'         => $this->state_id,
                    'date_reservation' => $this->date_reservation,
                    'time_reservation' => $this->time_reservation,
                    'created_by'       => auth()->id(),
                ]);
            });

            $this->onReservationCreated();

        } catch (\RuntimeException $e) {
            // Error de negocio: slot ocupado
            $this->dispatchError($e->getMessage());

        } catch (\Exception $e) {
            // Error de sistema
            $this->dispatchError('Error inesperado: ' . $e->getMessage());
        }
    }

    // ─── Helpers privados ────────────────────────────────────────────────────────

    private function validateReservation(): void
    {
        if ($this->isNewVehicle()) {
            $this->validate();
        }
    }

    private function isNewVehicle(): bool
    {
        return $this->vehicle_id === 'new';
    }

    private function resolveVehicle(): Vehicle
    {
        return $this->isNewVehicle()
            ? Vehicle::firstOrCreate(
                ['placa'    => $this->placa],
                ['model_id' => $this->modelo_id, 'user_id' => auth()->id()]
            )
            : Vehicle::findOrFail($this->vehicle_id);
    }

    private function buildTimeInterval(float $duration): array
    {
        $start = Carbon::createFromFormat('H:i', $this->time_reservation);

        return [
            'start' => $start,
            'end'   => $start->copy()->addHours((float) $duration), // ← cast explícito
        ];
    }

    private function ensureSlotIsAvailable(array $interval): void  // ← AQUÍ
    {
        $isTaken = Reservation::with('service')
            ->where('date_reservation', $this->date_reservation)
            ->get()
            ->contains(function (Reservation $r) use ($interval) {
                if (!$r->service || is_null($r->service->duration)) return false;
                return $this->overlaps($r, $interval);
            });

        if ($isTaken) {
            $this->dispatchError('Ese horario ya está ocupado');
            throw new \RuntimeException('Slot no disponible');
        }
    }

    private function overlaps(Reservation $reservation, array $interval): bool
    {
        $rStart = Carbon::createFromFormat('H:i:s', $reservation->time_reservation);
        $rEnd   = $rStart->copy()->addHours((float) $reservation->service->duration); // ← cast explícito

        return $interval['start'] < $rEnd && $interval['end'] > $rStart;
    }
    
    private function onReservationCreated(): void
    {
        $this->dispatch('reservationCreated');
        $this->reset();
        $this->show = false;
        $this->dispatchSuccess('Reserva registrada correctamente');
    }

    private function dispatchError(string $title): void
    {
        $this->dispatch('swal', ['icon' => 'error', 'title' => $title]);
    }

    private function dispatchSuccess(string $title): void
    {
        $this->dispatch('swal', ['icon' => 'success', 'title' => $title]);
    }
    
    public function render()
    {
        return view('livewire.components.modals.create-reservation-modal');
    }
}
