<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ListUserTable extends LivewireTable
{
    public $userId;

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
    /*
    public function render()
    {
        return view('livewire.components.tables.list-user-table');
    }*/
}
