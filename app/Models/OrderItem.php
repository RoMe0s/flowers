<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'itemable_type',
        'itemable_id',
        'order_id',
        'price',
        'count'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function itemable() {
        return $this->morphTo();
    }

    public function getDates()
    {
        return array_merge(parent::getDates(), ['deleted_at']);
    }
}
