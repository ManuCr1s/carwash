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
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'placa' =>[
                'required',
                'min:6'
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
                    ->where(function ($query) {
                        return $query->where('date_reservation', $this->date_reservation);
                    }),
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

            'placa.required' => 'La placa es obligatoria',
            'placa.min' => 'La placa debe tener al menos 6 caracteres',

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
        $this->show   = true;     
    }

    public function save(){
        $validatedData = $this->validate();

        $horaFormateada = sprintf('%02d:00:00', $this->time_reservation);
        $isSlotTaken = Reservation::where('date_reservation', $this->date_reservation)
        ->where('time_reservation', $horaFormateada)
        ->exists();

        if ($isSlotTaken) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Ese horario ya está ocupado',
            ]);
            return;
        }

        try {
            \DB::transaction(function () use ($validatedData, $horaFormateada) {
                $user = User::create([
                    'name'     => $validatedData['name'],
                    'lastname' => $validatedData['lastname'],
                    'dni' => $validatedData['dni'],
                    'phone' => $validatedData['phone'],
                    'email'    => $validatedData['email'],
                    'password' => bcrypt($validatedData['dni']),
                ]);
                
                $role = Role::find(2);
                $user->assignRole($role); 

                $vehicle = $user->vehicles()->create([
                    'model_id' => $this->modelo_id,
                    'placa' =>$this->placa,
                ]);

                $reservation = $user->reservations()->create([
                    'vehicle_id' => $vehicle->id,
                    'service_id' => $validatedData['service_id'],
                    'state_id' => $this->state_id,
                    'date_reservation' => $this->date_reservation,
                    'time_reservation' => $horaFormateada, 
                    'created_by' => auth()->id 
                ]);
            });

            // 3. Feedback de éxito
            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Atencion Creada con exito!',
                'text'  => 'El registro se ha completado correctamente.'
            ]);

            $this->reset(); // Limpia el formulario
            $this->show = false; // Cierra el modal
            $this->dispatch('tableRefresh'); // Refresca la lista de usuarios

        } catch (\Exception $e) {
            // 4. Manejo de errores inesperados
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error de sistema',
                'text'  => $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.components.modals.create-reservation-user-modal',[
            'services' => Service::select('id','name','description')->get(),
        ]);
    }
}
