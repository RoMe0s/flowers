<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
