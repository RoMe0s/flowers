<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
      'user_id',
        'courier_id',
        'address_id',
        'delivery_price',
        'discount',
        'prepay',
        'recipient_name',
        'recipient_phone',
        'date',
        'time',
        'card_text',
        'status',
        'desc'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

    public function courier() {
        return $this->belongsTo(User::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class)->with('itemable');
    }

    public function getTotal() {
        $total_price = 0;

        foreach ($this->items as $item) {
            $total_price += ($item->price - $item->discount) * $item->count;
        }

        $total_price += $this->delivery_price;

        $total_price /= ($this->discount == 0 ? 1 : ($this->discount / 100) );

        return $total_price;

    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function setDateAttribute($value)
    {
        if($value) {
            $this->attributes['date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getDateAttribute($value)
    {
        if($value) {
            return Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y');
        }
    }
}
