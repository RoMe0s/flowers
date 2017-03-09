<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'image',
        'price',
        'text'
    ];
}
