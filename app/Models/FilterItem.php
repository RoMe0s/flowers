<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;

class FilterItem extends Model
{

    use Translatable;
    use WithTranslationsTrait;
    
    public $timestamps = false;

    protected $with = ['translations'];

    public $translatedAttributes = [
        'title'
    ];

    protected $fillable = [
        'title',

        'value',
        'status',
        'position',
        'slug',
        'type'
    ];

    public function scopeVisible($query) {
        return $query->where('status', TRUE);
    }

    public function scopePositionSorted($query, $type = 'ASC') {
        return $query->orderBy('position', $type);
    }

    public static function getTypes() {
        $types = ['<', '>'];

        return array_combine($types, $types);
    }

}
