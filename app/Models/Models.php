<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Models extends Model
{
    protected $table = 'models';
    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
