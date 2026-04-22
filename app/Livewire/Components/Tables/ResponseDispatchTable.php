<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Reservation;
use Livewire\Attributes\On;


class ResponseDispatchTable extends LivewireTable
{
    protected string $model = Reservation::class;
    public $reservationId;
    public function query(): Builder
    {
      return Reservation::query()
            ->join('services', 'reservations.service_id', '=', 'services.id')
            ->join('vehicles', 'reservations.vehicle_id', '=', 'vehicles.id')
            ->join('models', 'vehicles.model_id', '=', 'models.id')
            ->join('brands','models.brand_id','=','brands.id')
            ->select('services.name', 'models.name as models_name','brands.name as brands_name','vehicles.placa','reservations.date_reservation','reservations.time_reservation')
            ->where('reservations.state_id','=',2);
    }
    public function handle($id)
    {
        $this->dispatch('openDispatchModal', id: $id);
    }
     #[On('tableRefresh')]
    public function tableRefresh(): void {}
    protected function columns(): array{
        return [
            Column::make(__('ID'),'id'),
            Column::make(__('Servicio'), 'name'),
            Column::make(__('Marca'), 'brands_name'),
            Column::make(__('Placa'), 'placa'),
            Column::make(__('Modelo'), 'models_name'),   
            Column::make(__('Dia'), 'date_reservation'),
            Column::make(__('Hora'), 'time_reservation'),
            ViewColumn::make('Acciones','components.table-dispatch-action'),
        ];
     }
   /* public function render()
    {
        return view('livewire.components.tables.response-dispatch-table');
    }*/
}
