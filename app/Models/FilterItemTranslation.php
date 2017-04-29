<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterItemTranslation extends Model
{

    protected $table = 'filter_item_translations';

    public $timestamps = false;

    protected $fillable = [
        'title'
    ];
}
