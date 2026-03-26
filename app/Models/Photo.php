<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'order_id',
        'url_image',
        'tipo_photo',
    ];
}
