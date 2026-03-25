<?php

namespace App\Livewire\Components\Modals;

use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\On;

class EditUserModal extends Component
{
    public $userId;
    public $name;
    public $email;
    public bool $show = false;

    protected function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->userId,
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
        $this->show   = true;
    }

    public function update()
    {
        $this->validate();

         User::where('id', $this->userId)
        ->update([               
            'name'  => strtoupper($this->name),
            'email' => strtoupper($this->email),
        ]);

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
