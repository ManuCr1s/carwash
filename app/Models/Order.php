<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Photo;
class Order extends Model
{
    protected $fillable = [
        'reserva_id',
        'user_id',
        'date_init',
        'date_end',
        'observations_start',
        'observations_end',
        'price'
    ];
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
