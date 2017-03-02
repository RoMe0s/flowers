<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;

class Flower extends Model
{
    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'position',
        'title'
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

    public function colors() {
        return $this->belongsToMany(Color::class, 'flowers_colors');
    }

}
