<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListUserTable extends LivewireTable
{
    protected string $model = User::class;
    public $userId;
    public function delete($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Usuario no encontrado',
            ]);
            return;
        }
        $user->delete();

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => '¡Eliminado!',
            'showConfirmButton' => true,
        ]);
    }
    public function edit($id)
    {
        $this->dispatch('openEditModal', id: $id);
    }
    #[On('tableRefresh')]
    public function tableRefresh(): void
    {
        // método vacío — Livewire re-renderiza automáticamente
    }

    public function query(): Builder
    {
      return User::query()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->select('users.*', 'roles.name as role_name')
            ->where('roles.id','!=',1);
    }
    protected function columns(): array{
        return [
            Column::make(__('ID'),'id'),
            Column::make(__('Name'), 'name'),
            Column::make(__('Email'), 'email'),
            Column::make('Rol', 'roles.name'),
            ViewColumn::make('Acciones','components.table-user-action'),
        ];
    }
    
    /*public function render()
    {
        return view('livewire.components.tables.list-user-table');
    }*/
}
