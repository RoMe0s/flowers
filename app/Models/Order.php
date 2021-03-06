<?php

namespace App\Models;

use App\Decorators\Phone;
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
        'result',

        'specify',
        'neighbourhood',
        'accuracy',
        'night',
        'anonymously',
        'address_string',
        'distance',
        'static_discount'
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
        return $this->hasMany(OrderItem::class)->with('itemable');
    }

    public function getTotal() {
        $total_price = 0;

        foreach ($this->items as $item) {

            $total_price += $item->getPrice($this->discount) * $item->count;

        }

        $total_price += $this->delivery_price;

        $total_price -= $this->static_discount;

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

        $address = null;

        if(isset($this->address)) {

            $address = $this->address->address;

        } elseif(!empty($this->address_string)) {

            $address = $this->address_string;

        }

        return $address;

    }

    /**
     * @param array $data
     * @return Order
     */
    public static function make(Array $data, User $user) {
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => isset($data['address_id']) ? $data['address_id'] : null,
                'prepay' => $data['prepay'] ?: 50,
                'recipient_name' => isset($data['recipient_name']) ? $data['recipient_name'] : "",
                'recipient_phone' => isset($data['recipient_phone']) ? $data['recipient_phone'] : "",
                'status' => 1,
                'date' => $data['date'],
                'time' => $data['time'],
                'card_text' => $data['card_text'],
                'desc' => isset($data['desc']) ? $data['desc'] : null,
                'discount' => CartController::_cartDiscount(true),
                'specify' => isset($data['specify']) ? $data['specify'] : null,
                'neighbourhood' => isset($data['neighbourhood']) ? $data['neighbourhood'] : false,
                'accuracy' => isset($data['accuracy']) ? $data['accuracy'] : false,
                'night' => isset($data['night']) ? $data['night'] : false,
                'address_string' => isset($data['address_string']) ? $data['address_string'] : null,
                'delivery_price' => isset($data['delivery_price']) ? $data['delivery_price'] : 0,
                'anonymously' => isset($data['anonymously']) ? $data['anonymously'] : false,
                'distance' => isset($data['distance']) ? $data['distance'] : 0,
                'specify' => isset($data['specify']) ? $data['specify'] : false,
                'static_discount' => isset($data['static_discount']) ? $data['static_discount'] : 0
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

    public function setRecipientPhoneAttribute($value) {

        $phone = new Phone($value);

        $this->attributes['recipient_phone'] = $phone->getDecorated();

    }

}
