<?php

namespace App\Livewire\Components\Tables;

use Livewire\Component;
use RamonRietdijk\LivewireTables\Livewire\LivewireTable;
use RamonRietdijk\LivewireTables\Columns\Column;
use RamonRietdijk\LivewireTables\Columns\ActionColumn;
use RamonRietdijk\LivewireTables\Columns\ViewColumn;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;

class ListReservationTable extends LivewireTable
{
    protected string $model = Reservation::class;
    public function query(): Builder
    {
      return Reservation::query()
            ->join('vehicles', 'reservations.vehicle_id', '=', 'vehicles.id')
            ->select('vehicles.marca', 'vehicles.modelo','vehicles.placa','reservations.date_reservation')
            ->where('reservations.user_id','=',auth()->id());
    }
    protected function columns(): array{
        return [
            Column::make(__('Marca'),'marca'),
            Column::make(__('Modelo'), 'modelo'),
            Column::make(__('Placa'), 'placa'),
            Column::make(__('Dia de Reservacion'), 'date_reservation'),
        ];
    }

   /*  public function render()
    {
        return view('livewire.components.tables.list-reservation-table');
    } */
}
