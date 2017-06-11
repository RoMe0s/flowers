<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:40
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Banner
 * @package App\Models
 */
class Banner extends Model
{

    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'layout_position',
        'status',
        'template'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(BannerItem::class, 'banner_id')->positionSorted();
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->whereStatus(true);
    }
}
