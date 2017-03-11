<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;

class Subscription extends Model
{

    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title',
        'content'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'price',
        'image',
        'position',
        'status',
        'title',
        'content'
    ];


    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true);
    }


    public function scopePositionSorted($query, $type = 'ASC') {
        return $query->orderBy('position', $type);
    }

    public function users() {
        return $this->belongsToMany(User::class, 'users_subscriptions')->withPivot(['paid_before']);
    }

    public function isPaid() {
        $now = strtotime(Carbon::now()->toDateString());

        return $now <= strtotime($this->pivot->paid_before);
    }

    public function getPaidBefore() {
        return Carbon::createFromFormat('Y-m-d', $this->pivot->paid_before)->format('d-m-Y');
    }

    public function hasSubscriber($id) {
        $users = $this->users->lists('id')->toArray();

        return in_array($id, $users) ? true : false;

    }

}
