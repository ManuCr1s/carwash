<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
   protected $fillable = [
        'vehicle_id',
        'user_id',
        'service_id',
        'state_id',
        'date_reservation',
        'time_reservation'
    ];
}
