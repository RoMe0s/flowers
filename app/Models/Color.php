<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;

class Color extends Model
{
    use Translatable;
    use WithTranslationsTrait;

    public $timestamps = false;

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
        'hex',
        'title'
    ];

    public function flowers() {
        return $this->belongsToMany(Flower::class, 'flowers_colors');
    }
}
