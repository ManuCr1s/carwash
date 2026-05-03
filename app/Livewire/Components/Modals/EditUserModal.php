<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;

class EditUserModal extends Component
{
    public $userId;
    public $name;
    public $email;
    public $lastname;
    public $dni;
    public $phone;
    public $password;
    public $password_confirmation;
    public bool $show = false;

    protected function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'dni' => 'required|max:8|unique:users,dni,' . $this->userId,
            'phone'    => 'required|numeric|digits_between:7,15',
            'lastname' => 'required|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ];
    }

    protected function messages(): array
    {
        return [
            // Validación para Name y Lastname
            'name.required' => 'El nombre del usuario es obligatorio',
            'name.string' => 'El nombre debe ser un texto válido',
            'name.max' => 'El nombre debe tener como tamaño 255 caracteres',

            'lastname.required' => 'El apellido es obligatorio',
            'lastname.string' => 'El apellido debe ser un texto válido',
            'lastname.max' => 'El apellido debe tener como tamaño 255 caracteres',

            // Validación para Username
            'dni.required' => 'El número de DNI es obligatorio.',
            'dni.max'      => 'El DNI no puede tener 8 caracteres.',
            'dni.unique'   => 'Este número de DNI ya se encuentra registrado.',

            // Validacion para Phone
            'phone.required' => 'El número de teléfono es obligatorio.',
            'phone.numeric'   => 'El formato del teléfono no es válido.',
            'phone.digits_between'      => 'El teléfono debe tener entre 7 y 15 caracteres',

            // Validación para Email
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico no es valido',
            'email.unique' => 'Este correo electrónico ya pertenece a otro usuario',
        ];
    }

    // Escucha el evento que dispara la tabla
    #[On('openEditModal')]
    public function openEditModal($id)
    {
             
        $user = User::find($id);

        $this->userId = $user->id;
        $this->name   = $user->name;
        $this->email  = $user->email;
        $this->lastname   = $user->lastname;
        $this->dni  = $user->dni;
        $this->phone   = $user->phone;
        $this->show   = true;
    }

    public function update()
    {
        $this->password = trim($this->password);
        $this->password_confirmation = trim($this->password_confirmation);

        $this->validate();

        $data = [
            'name'     => strtoupper($this->name),
            'email'    => strtoupper($this->email),
            'phone'    => strtoupper($this->phone),
            'dni'      => strtoupper($this->dni),
            'lastname' => strtoupper($this->lastname),
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        User::where('id', $this->userId)->update($data);

        $this->show = false;
        $this->dispatch('tableRefresh');

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'showConfirmButton' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.components.modals.edit-user-modal');
    }
}
