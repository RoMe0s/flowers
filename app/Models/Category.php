<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;

class Category extends Model
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
        'image',
        'title'
    ];
    
}
