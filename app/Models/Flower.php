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

    public function colors() {
        return $this->belongsToMany(Color::class, 'flowers_colors');
    }

    public function sets() {
        return $this->belongsToMany(Set::class, 'sets_flowers');
    }

}
