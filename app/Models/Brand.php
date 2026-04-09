<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
    protected $fillable = [
        'name',
    ];
}
