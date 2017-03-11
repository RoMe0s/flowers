<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;

class Box extends Model
{
    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'title'
    ];

    protected $fillable = [
        'category_id',
        'image',
        'length',
        'width',
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

    /**
     * @param $query
     * @param string $type
     * @return mixed
     */
    public function scopePositionSorted($query, $type = 'ASC') {
        return $query->orderBy('position', $type);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function sets() {
        return $this->hasMany(Set::class);
    }

    public function size($separator = 'x') {
        return $this->length.' '.$separator.' '.$this->width;
    }
}
