<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainPageMenu extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'menuable_id',
        'menuable_type',
        'position',
        'status'
    ];

    public function scopeVisible($query) {

        return $query->where('status', true);

    }

    public function scopePositionSorted($query, $type = 'ASC') {

        return $query->orderBy('position', $type);

    }

    public function menuable() {

        return $this->morphTo();

    }

    public static function getTypes() {

        return [

            (string)Page::class => trans('labels.page'),
            (string)Category::class => trans('labels.category')

        ];

    }

}
