<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use App\Models\User;

class ResponseRequestTable extends LivewireTable
{
     protected string $model = User::class;
     protected function columns(): array{
        return [
            Column::make(__('ID'),'id'),
            Column::make(__('Name'), 'name'),
            Column::make(__('Email'), 'email'),
            
        ];
     }

 /*   public function render()
    {
        return view('livewire.components.tables.response-request-table');
    }*/
}
