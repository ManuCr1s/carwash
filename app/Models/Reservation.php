<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public function order()
    {
        return $this->hasOne(Order::class, 'reserva_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(\App\Models\Vehicle::class);
    }

    protected $fillable = [
        'vehicle_id',
        'user_id',
        'service_id',
        'state_id',
        'date_reservation',
        'time_reservation'
    ];
}
