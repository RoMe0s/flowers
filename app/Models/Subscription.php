<?php

namespace App\Models;

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

}
