<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
      'code',
      'discount',
      'date',
        'status'
    ];

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d-m-Y');
    }

    public function setDateAttribute($date) {
        return $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $date)->format('Y-m-d');
    }

    public function scopeVisible($query) {
        return $query->where('status', true);
    }
}
