<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Productable extends Model
{
    protected $fillable = [
    	'productable_id',
	    'productable_type',
	    'status',
	    'position'
   ];

	public function scopeVisible($query) {
		return $query->where('status', TRUE);
	}

    public function scopePositionSorted($query, $type = 'ASC') {
    	return $query->orderBy('position', $type);
    }

    public function productable() {
    	return $this->morphTo();
    }

    public static function getTypes() {

        return [
            (string)Product::class => 'Соп. товары',
            (string)Set::class => 'Наборы',
            (string)Bouquet::class => 'Букеты'
        ];

    }

}
