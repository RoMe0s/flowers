<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionTranslation extends Model
{
    protected $fillable = [
      'title',
      'content'
    ];

    public $timestamps = false;
}
