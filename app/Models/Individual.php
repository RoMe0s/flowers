<?php

namespace App\Models;

use App\Decorators\Phone;
use Illuminate\Database\Eloquent\Model;

class Individual extends Model
{
    protected $fillable = [
        'phone',
        'email',
        'image',
        'price',
        'text'
    ];

    public function setPhoneAttribute($value) {

        $phone = new Phone($value);

        $this->attributes['phone'] = $phone->getDecorated();

    }

}
