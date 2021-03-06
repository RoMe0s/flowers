<?php

namespace App\Models;

use App\Contracts\FrontLink;
use App\Traits\Models\FieldableTrait;
use Carbon;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;
use Request;

/**
 * Class User
 * @package App\Models
 */
class User extends SentryUser implements FrontLink
{
    use FieldableTrait;

    /**
     * @var string
     */
    protected $table = "users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'start_discount',
        'notifications',
        'discount',
        'login'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function info()
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'users_groups', 'user_id');
    }

    /**
     * @param      $q
     */
    public function scopeJoinInfo($q)
    {
        return $q->leftJoin('user_info', 'users.id', '=', 'user_info.user_id');
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->whereActivated(true);
    }

    /**
     * @param string $persistCode
     *
     * @return bool
     */
    public function checkPersistCode($persistCode)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        if (empty($this->info->name)) {
            return '';
        }

        return $this->info->name;
    }

    /**
     * @return string
     */
    public function getPhoneAttribute()
    {
        if (empty($this->info->phone)) {
            return '';
        }

        return $this->info->phone;
    }

    /**
     * @return string
     */
    public function getBirthdayAttribute()
    {
        if (empty($this->info->birthday)) {
            return '';
        }
        
        return $this->info->birthday;
    }

    /**
     * @return string
     */
    public function getGenderAttribute()
    {
        if (empty($this->info->gender)) {
            return null;
        }

        return $this->info->gender;
    }

    /**
     * @return string
     */
    public function getAvatarAttribute()
    {
        if (empty($this->info->avatar)) {
            return config('user.no_image');
        }

        return $this->info->avatar;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function exist()
    {
        return !empty($this->id);
    }

    /**
     * update user last login
     */
    public function updateActivity()
    {
        $this->ip_address = Request::getClientIp();
        $this->last_activity = Carbon::now()->toDateTimeString();

        $this->save();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return localize_route('profiles.show', $this->id);
    }

    public function codes() {
        return $this->belongsToMany(Code::class, 'users_codes');
    }

    public function subscriptions() {
        return $this->belongsToMany(Subscription::class, 'users_subscriptions')->withPivot(['paid_before']);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function addresses() {
        return $this->hasMany(Address::class);
    }

    public function hasSubscription($id) {

        $subscriptions = $this->subscriptions()->lists('id')->toArray();

        return in_array($id, $subscriptions) ? true : false;

    }

    public function getDiscount() {
        $start_discount = 0;
        if($this->hasAccess('vip')) {
            $start_discount = $this->start_discount;
        }
        return $this->discount > $start_discount ? $this->discount : $start_discount;
    }

    public function getTotalOrdersPrice() {

        $total = 0;

        $orders = $this->orders->filter(function($order) {
        
            return in_array($order->status, [3,6]);

        });

        foreach($orders as $order) {

            $total += $order->getTotal();

        }

        return $total;

    }

    public function addCode(Code $code) {
        $this->codes()->attach($code->id);
    }

    public function hasCode($code) {

        $code = $this->codes()->where('code', $code)->first();

        return isset($code) ? true : false;


    }

}
