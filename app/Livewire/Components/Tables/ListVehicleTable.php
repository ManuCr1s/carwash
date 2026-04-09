<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListVehicleTable extends LivewireTable
{
    protected string $model = Vehicle::class;
      public function query(): Builder
    {
      return Vehicle::query()
            ->join('models','vehicles.model_id','=','models.id')
            ->join('brands','brands.id','=','models.brand_id')
            ->select('brands.name as brands_name','models.name as model_name','vehicles.placa','vehicles.created_at')
            ->where('vehicles.user_id','=',auth()->id());
    }
    protected function columns(): array{
        return [
            Column::make(__('Marca'), 'brands_name'),
            Column::make(__('Modelo'), 'model_name'),
            Column::make(__('Placa'), 'placa'),
            Column::make(__('Dia de Reservacion'), 'created_at'),
        ];
    }
/*     public function render()
    {
        return view('livewire.components.tables.list-vehicle-table');
    } */
}
