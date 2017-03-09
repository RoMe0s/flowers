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


    public function products() {
        return $this->belongsToMany(Product::class, 'products_categories');
    }

    public function sets() {
        return $this->hasMany(Set::class);
    }
    
}
