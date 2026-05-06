<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Brand;
use App\Models\Models;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateReservationUserModal extends Component
{
    public bool $show = false;
    public $name;
    public $lastname;
    public $dni;
    public $phone;
    public $email;
    public $roleId;
    public $service_id;
    public $marca;
    public $modelo;
    public $marca_id = null; 
    public $modelo_id = null;
    public $resultadosMarca = [];
    public $resultadosModelo = [];
    public $placa;
    public $date_reservation;
    public $time_reservation;
    public $userId;
    public $state_id = 1;
    public string $mode = 'create'; 
    public $vehicleId;
    public ?int $reservationId = null;


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
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'dni' => [
                'required', 
                'string', 
                'max:8', 
                Rule::unique('users', 'dni')->ignore($this->userId)
            ],
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'email' => [
                'required', 
                'max:255', 
                'email',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'placa' =>[
                'required',
                'size:6',
                Rule::unique('vehicles', 'placa')->ignore($this->vehicleId)
            ],
            'marca' =>[
                'required'
            ],
            'modelo' =>[
                'required'
            ],
            'service_id'=>[
                'required',
                'exists:services,id',
            ],
            'date_reservation'=>[
                'required','date','after_or_equal:today',
            ],
            'time_reservation'=>[
                'required',
                'integer',
                'between:8,18',
                Rule::unique('reservations', 'time_reservation')
                ->where(fn($query) => $query->where('date_reservation', $this->date_reservation))
                ->ignore($this->reservationId),
                function ($attribute, $value, $fail) {
                    $horaFormateada = sprintf('%02d:00:00', $value);
                    $fechaHoraReserva = Carbon::parse("{$this->date_reservation} {$horaFormateada}");

                    if ($fechaHoraReserva->isPast()) {
                        $fail('La hora debe ser posterior a la actual.');
                    }
                },
            ]
        ];
    }
    protected function messages(): array
    {
        return [
            // Validación para Name y Lastname
            'name.required' => 'El nombre del cliente es obligatorio',
            'name.string' => 'El nombre debe ser un texto válido',
            'lastname.required' => 'El apellido del cliente es obligatorio',

            // Validación para Username
            'dni.required' => 'El número de DNI es obligatorio.',
            'dni.string'   => 'El DNI debe ser una cadena de texto válida.',
            'dni.max'      => 'El DNI no puede tener más de 8 caracteres.',
            'dni.unique'   => 'Este número de DNI ya se encuentra registrado.',

            // Validacion para Phone
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.string'   => 'El formato del teléfono no es válido.',
            'phone.min'      => 'El teléfono debe tener al menos :min caracteres.',
            'phone.max'      => 'El teléfono no puede superar los :max caracteres.',

            // Validación para Email
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo electrónico ya pertenece a otro usuario',
            'email.email'    => 'El formato del correo no es válido.',

            'placa.required' => 'La placa es obligatoria',
            'placa.size' => 'La placa debe tener 6 caracteres',

            'marca.required' => 'La marca es obligatoria',
            'modelo.required' => 'El modelo es obligatorio',

            'service_id.required' => 'El servicio es obligatorio.',
            'service_id.exists'   => 'El servicio seleccionado no es válido.',

            'date_reservation.required' => 'La fecha es obligatoria',
            'date_reservation.after_or_equal' => 'La fecha debe ser hoy o posterior',

            'time_reservation.required' => 'La hora es obligatoria',
            'time_reservation.unique' => 'Este horario ya está reservado',
            'time_reservation.between' => 'El tiempo de reservacion es de 8 a 18 horas'
        ];
    }

    #[On('openCreateReservaModal')]
    public function openCreateReservaModal()
    {
        $this->resetForm();
        $this->mode = 'create';
        $this->show = true;  
    }
    public function save(): void
    {
        $this->mode === 'create' ? $this->store() : $this->update();
    }
    public function store(): void
    {
        $validatedData = $this->validate();
        $horaFormateada = sprintf('%02d:00:00', $this->time_reservation);
        $service = Service::findOrFail($validatedData['service_id']);
        
        try {
            DB::transaction(function () use ($validatedData, $horaFormateada, $service) {
                
                // ✅ Overlap DENTRO de la transacción → atómico
                $interval = $this->buildTimeInterval((float) $service->duration, $horaFormateada);

                $isTaken = Reservation::with('service')
                    ->where('date_reservation', $this->date_reservation)
                    ->get()
                    ->contains(function (Reservation $r) use ($interval) {
                        if (!$r->service || is_null($r->service->duration)) return false;
                        return $this->overlaps($r, $interval);
                    });

                if ($isTaken) {
                    throw new \RuntimeException('Ese horario ya está ocupado');
                }

                $user = User::create([
                    'name'     => $validatedData['name'],
                    'lastname' => $validatedData['lastname'],
                    'dni'      => $validatedData['dni'],
                    'phone'    => $validatedData['phone'],
                    'email'    => $validatedData['email'],
                    'password' => bcrypt($validatedData['dni']),
                ]);

                $user->assignRole(Role::find(2));

                $vehicle = $user->vehicles()->create([
                    'model_id' => $this->modelo_id,
                    'placa'    => $this->placa,
                ]);

                $user->reservations()->create([
                    'vehicle_id'       => $vehicle->id,
                    'service_id'       => $validatedData['service_id'],
                    'state_id'         => $this->state_id,
                    'date_reservation' => $this->date_reservation,
                    'time_reservation' => $horaFormateada,
                    'created_by'       => auth()->id(),
                ]);
            });

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Atención creada con éxito!',
                'text'  => 'El registro se ha completado correctamente.',
            ]);
            $this->close();
            $this->dispatch('tableRefresh');

        } catch (\RuntimeException $e) {
            // ← Error de negocio (slot ocupado)
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => $e->getMessage(),
            ]);

        } catch (\Exception $e) {
            // ← Error de sistema (BD, etc)
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error de sistema',
                'text'  => $e->getMessage(),
            ]);
        }
    }
    public function update(): void
    {
        $validatedData = $this->validate();
        $horaFormateada = sprintf('%02d:00:00', $this->time_reservation);
        $service = Service::findOrFail($validatedData['service_id']);

        try {
            DB::transaction(function () use ($validatedData, $horaFormateada, $service) {

                $interval = $this->buildTimeInterval((float) $service->duration, $horaFormateada);

                $isTaken = Reservation::with('service')
                    ->where('date_reservation', $this->date_reservation)
                    ->where('id', '!=', $this->reservationId) // 👈 excluir la reserva actual
                    ->get()
                    ->contains(function (Reservation $r) use ($interval) {
                        if (!$r->service || is_null($r->service->duration)) return false;
                        return $this->overlaps($r, $interval);
                    });

                if ($isTaken) {
                    throw new \RuntimeException('Ese horario ya está ocupado');
                }

                $reservation = Reservation::findOrFail($this->reservationId);

                // Actualizar usuario
                $reservation->user->update([
                    'name'     => $validatedData['name'],
                    'lastname' => $validatedData['lastname'],
                    'dni'      => $validatedData['dni'],
                    'phone'    => $validatedData['phone'],
                    'email'    => $validatedData['email'],
                ]);

                // Actualizar vehículo
                $reservation->vehicle->update([
                    'model_id' => $this->modelo_id,
                    'placa'    => $this->placa,
                ]);

                // Actualizar reserva
                $reservation->update([
                    'service_id'       => $validatedData['service_id'],
                    'date_reservation' => $this->date_reservation,
                    'time_reservation' => $horaFormateada,
                    'updated_by'       => auth()->id(),
                ]);
            });

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Reserva actualizada con éxito!',
                'text'  => 'El registro se ha actualizado correctamente.',
            ]);

            $this->close();
            $this->dispatch('tableRefresh');

        } catch (\RuntimeException $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error de sistema',
                'text'  => $e->getMessage(),
            ]);
        }
    }
    private function buildTimeInterval(float $duration, string $time): array
    {
        $start = Carbon::createFromFormat('H:i:s', $time);

        return [
            'start' => $start,
            'end'   => $start->copy()->addHours($duration),
        ];
    }
    private function overlaps(Reservation $reservation, array $interval): bool
    {
        $rStart = Carbon::createFromFormat('H:i:s', $reservation->time_reservation);
        $rEnd   = $rStart->copy()->addHours((float) $reservation->service->duration);
        return $interval['start'] < $rEnd && $interval['end'] > $rStart;
    }
    private function ensureSlotIsAvailable(array $interval): void
    {
        $isTaken = Reservation::with('service')
            ->where('date_reservation', $this->date_reservation)
            ->get()
            ->contains(function (Reservation $r) use ($interval) {
                // Ignorar reservas sin servicio cargado o sin duration
                if (!$r->service || is_null($r->service->duration)) return false;
                
                return $this->overlaps($r, $interval);
            });

        if ($isTaken) {
            $this->dispatchError('Ese horario ya está ocupado');
            throw new \RuntimeException('Slot no disponible');
        }
    }
    #[On('openCreateModal')]
    public function openCreateModal($id = null)
    {
        $this->resetForm();
        $this->mode = 'edit';
        $this->reservationId = $id;
        if (blank($id)) {
            return $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => __('Reserva no encontrado'),
                'text'  => __('No se pudo localizar el registro en la base de datos.'),
            ]);
        }
        $reservation = Reservation::with([
            'vehicle.model',
            'service',   
            'user',         
        ])->findOrFail($id);

        $this->fill([
            'name'     => $reservation->user->name ?? null,
            'email'    => $reservation->user->email ?? null,
            'lastname' => $reservation->user->lastname ?? null,
            'dni'      => $reservation->user->dni ?? null,
            'phone'    => $reservation->user->phone ?? null,
            'placa'         => $reservation->vehicle->placa ?? null,
            'modelo_id' => $reservation->vehicle->model->id ?? null,
            'modelo'        => $reservation->vehicle->model->name ?? null,
            'marca'  => $reservation->vehicle->model?->brand?->name,
            'marca_id' => $reservation->vehicle->model?->brand?->id,
            'service_id' => $reservation->service_id ?? null,
            'userId' => $reservation->user_id ?? null,
            'vehicleId' => $reservation->vehicle->id ?? null,
            'date_reservation' => Carbon::parse($reservation->date_reservation)->format('Y-m-d'),
            'time_reservation' => Carbon::parse($reservation->time_reservation)->format('G')
        ]);  
        $this->show = true;
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'lastname',
            'dni',
            'phone',
            'placa',
            'modelo_id',
            'modelo',
            'marca',
            'marca_id' ,
            'service_id',
            'date_reservation',
            'time_reservation',
            'vehicleId'
        ]);
    }
    public function close(): void
    {
        $this->resetForm();
        $this->show = false;
    }
    public function render()
    {
        return view('livewire.components.modals.create-reservation-user-modal',[
            'services' => Service::select('id','name','description')->get(),
        ]);
    }
}
