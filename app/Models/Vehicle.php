<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function model()
    {
        return $this->belongsTo(\App\Models\Models::class);
    }
    protected $fillable = [
        'user_id',
        'model_id',
        'placa',
    ];
}
