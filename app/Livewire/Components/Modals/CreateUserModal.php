<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Models\User;

class CreateUserModal extends Component
{
    public bool $show = false;
    public $name;
    public $lastname;
    public $dni;
    public $phone;
    public $email;
    public $roleId;
    public $userId;

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
            'roleId' => 'required|exists:roles,id',
        ];
    }
    protected function messages(): array
    {
        return [
            // Validación para Name y Lastname
            'name.required' => 'El nombre del usuario es obligatorio',
            'name.string' => 'El nombre debe ser un texto válido',
            'lastname.required' => 'El apellido es obligatorio',

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

            'roleId.required' => 'El rol es obligatorio.',
            'roleId.exists'   => 'El rol seleccionado no es válido.',

            'max' => 'Este campo no puede superar los :max caracteres',
        ];
    }


    #[On('openCreateModal')]
    public function openCreateModal($id = null)
    {
        $this->show   = true;
        if($id !== null){
            $user = User::find($id);
            $this->userId = $user->id;
            $this->name   = $user->name;
            $this->email  = $user->email;
        }
     
    }

    public function save(){
        if($this->userId === null){
            $validatedData = $this->validate();
            try {
                \DB::transaction(function () use ($validatedData) {
                    $user = User::create([
                        'name'     => $validatedData['name'],
                        'lastname' => $validatedData['lastname'],
                        'dni' => $validatedData['dni'],
                        'phone' => $validatedData['phone'],
                        'email'    => $validatedData['email'],
                        'password' => bcrypt('123456'),
                    ]);
                    $role = Role::find($validatedData['roleId']);
                    $user->assignRole($role); 
                });

                // 3. Feedback de éxito
                $this->dispatch('swal', [
                    'icon'  => 'success',
                    'title' => '¡Usuario Creado!',
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
                    'text'  => 'No se pudo guardar el usuario. Intente más tarde.'
                ]);
            }
        }else{
            $this->validate([
                'dni' => [
                    'required', 
                    'string', 
                    'max:8', 
                    Rule::unique('users', 'dni')->ignore($this->userId)
                ],
                'phone' => ['required', 'string', 'min:7', 'max:20'],
                'name' => ['string'],
                'lastname' => ['string']
            ], [
                'dni.required' => 'El número de DNI es obligatorio.',
                'dni.string'   => 'El DNI debe ser una cadena de texto válida.',
                'dni.max'      => 'El DNI no puede tener más de 8 caracteres.',
                'dni.unique'   => 'Este número de DNI ya se encuentra registrado.',
                
                'phone.required' => 'El número de teléfono es obligatorio.',
                'phone.string'   => 'El formato del teléfono no es válido.',
                'phone.min'      => 'El teléfono debe tener al menos :min caracteres.',
                'phone.max'      => 'El teléfono no puede superar los :max caracteres.',

                'name.string' => 'El nombre debe ser un cadana de texto valida',

                'lastname.string' => 'EL apellido debe ser una cadena de texto valida',
            ]);
            User::where('id', $this->userId)
            ->update([               
                'dni'  => strtoupper($this->dni),
                'name'  => strtoupper($this->name),
                'lastname'  => strtoupper($this->lastname),
                'phone' => strtoupper($this->phone),
            ]);

            $this->show = false;
            $this->dispatch('tableRefresh');

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Actualizado!',
                'showConfirmButton' => true,
            ]);
        }
    }
    public function render()
    {
        return view('livewire.components.modals.create-user-modal',[
            'roles' => Role::where('id', '!=', 1)
            ->select('id', 'name')
            ->get()
        ]);
    }
}
