<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Order extends Model
{
    protected $fillable = [
        'reserva_id',
        'user_id',
        'date_init',
        'date_end',
        'observations_start',
        'observations_end',
        'price',
        'created_by',
        'updated_by'
    ];
    protected function observations_start(): Attribute
    {
        return Attribute::set(
                fn (string $value): string => strtoupper(trim($value)),
        );
    }
     protected function observations_end(): Attribute
    {
        return Attribute::set(
                fn (string $value): string => strtoupper(trim($value)),
        );
    }
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
}
