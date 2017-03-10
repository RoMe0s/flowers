<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTranslation extends Model
{
    public $timestamps = false;

    public $fillable = [
        'name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'short_content'
    ];
}
