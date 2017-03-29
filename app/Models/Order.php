<?php

namespace App\Models;

use App\Http\Controllers\Frontend\CartController;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Kingpabel\Shoppingcart\Facades\Cart;

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
        'desc',
        'result'
    ];

    protected $casts = [
        'result' => 'array'
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
        return $this->hasMany(OrderItem::class)->with('itemable', 'itemable');
    }

    public function getTotal() {
        $total_price = 0;

        foreach ($this->items as $item) {

            $total_price += $item->getPrice($this->discount) * $item->count;

        }

        $total_price += $this->delivery_price;

        return $total_price;

    }

    public function getTotalWithoutDiscount() {

        $total_price = 0;

        foreach ($this->items as $item) {

            $total_price += $item->getPrice(0) * $item->count;

        }

        $total_price += $this->delivery_price;

        return $total_price;

    }

    public function totalPrepay() {

        return $this->getTotal() * ($this->prepay / 100);

    }

    public function images() {

        if(empty($this->result)) {
            return $this->result;
        }

        return array();

    }

    public static function getTimes() {

        return array(
            '1' => 'с 10:00 до 13:00',
            '2' => 'с 13:00 до 16:00',
            '3' => 'с 16:00 до 19:00',
            '4' => 'с 19:00 до 22:00',
            '5' => 'с 22:00 до 10:00'
        );

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
            '6' => 'Оплачен другим способом',
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

    /**
     * @param array $data
     * @return Order
     */
    public static function make(Array $data, User $user) {
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $data['address_id'],
                'prepay' => $data['prepay'] ?: 50,
                'recipient_name' => isset($data['recipient_name']) ? $data['recipient_name'] : "",
                'recipient_phone' => isset($data['recipient_phone']) ? $data['recipient_phone'] : "",
                'status' => 1,
                'date' => $data['date'],
                'time' => $data['time'],
                'card_text' => $data['card_text'],
                'desc' => $data['desc'],
                'discount' => CartController::_cartDiscount(true)
            ]);

            static::_proccessItemsFromCart($order);
        } catch (\Exception $e) {
            abort(404);
        }

        return $order;
    }


    private static function _proccessItemsFromCart(Order $order) {

        foreach(Cart::content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->fill([
                'itemable_id' => $item->id,
                'itemable_type' => $item->options['type'],
                'price' => $item->price,
                'count' => $item->qty
            ]);
            $order->items()->save($orderItem);
        }

    }

    public function isSubscriptionOrder() {
        if($this->items->count() == 1) {

            $item = $this->items->first();

            if($item->itemable_type == (string)Subscription::class) {

                return true;

            }

        }

        return false;

    }

}
