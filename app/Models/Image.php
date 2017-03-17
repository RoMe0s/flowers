<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $timestamps = false;

    protected $fillable = [
      'imagable_id',
      'imagable_type',
      'images'
    ];

    public function imagable() {
        return $this->morphTo();
    }

    protected $casts = [
      'images' => 'array'
    ];
}
