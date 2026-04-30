<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class, 'reserva_id');
    }
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    protected $fillable = [
        'vehicle_id',
        'user_id',
        'service_id',
        'state_id',
        'date_reservation',
        'time_reservation',
        'created_by',
        'updated_by'
    ];
}
