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
        return $this->hasMany(OrderItem::class)->with('itemable', 'itemable.translations');
    }

    public function getTotal() {
        $total_price = 0;

        foreach ($this->items as $item) {
            $total_price += $item->price * $item->count;
        }

        $total_price -= ($total_price * ($this->discount / 100));

        $total_price += $this->delivery_price;

        return $total_price;

    }

    public function totalPrepay() {

        return $this->getTotal() * ($this->prepay / 100);

    }

    public function images() {

        $result = [];

        try {
            $result = json_decode($this->result, true);
        } catch (\Exception $e) {}

        return $result;

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


    public static function getStatuses($key = null) {

        $statuses = [
            '1' => 'Ожидает подтверждения',
            '2' => 'Ожидает оплаты',
            '3' => 'Оплачен',
            '4' => 'Выполняется',
            '5' => 'Доставлен',
            '0' => 'Отменён'
        ];

        if($key !== null) return $statuses[$key];

        return $statuses;

    }

    public function getStringStatus() {
        return static::getStatuses($this->status);
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


    public function getAddress() {

        if(isset($this->address)) {
            return $this->address->address;
        }

    }
}
