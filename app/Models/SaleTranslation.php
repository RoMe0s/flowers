<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];
}
