<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'model_id',
        'placa',
    ];
    protected function placa(): Attribute
    {
        return Attribute::set(
                fn (string $value): string => strtoupper(trim($value)),
        );
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function model()
    {
        return $this->belongsTo(\App\Models\Models::class);
    }
  
}
