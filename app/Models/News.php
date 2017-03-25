<?php

namespace App\Models;

use App\Traits\Models\WithTranslationsTrait;
use Carbon;
use Dimsav\Translatable\Translatable;
use Eloquent;
/**
 * Class News
 * @package App\Models
 */
class News extends Eloquent
{

    use Translatable;
    use WithTranslationsTrait;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'content'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'status',
        'position',
        'name',
        'image',
        'content',
        'publish_at',
    ];

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @param string $value
     *
     * @return string
     */
    public function setPublishAtAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['publish_at'] = Carbon::now();
        } else {
            $this->attributes['publish_at'] = Carbon::createFromFormat('d-m-Y', $value)->startOfDay()
                ->format('Y-m-d H:i:s');
        }
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getPublishAtAttribute($value)
    {
        if (empty($value) || $value == '0000-00-00 00:00:00') {
            return null;
        } else {
            return Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d-m-Y');
        }
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true)->whereRaw('publish_at <= NOW()');
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePublishAtSorted($query, $order = 'DESC')
    {
        return $query->orderBy('publish_at', $order);
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopePositionSorted($query, $order = 'ASC')
    {
        return $query->orderBy('position', $order);
    }

    /**
     * @param        $query
     * @param string $order
     *
     * @return mixed
     */
    public function scopeDateSorted($query, $order = 'ASC')
    {
        return $query->orderBy('created_at', $order);
    }

    /**
     * @param int|bool $limit
     *
     * @return string
     */
    public function getShortContent($limit = false)
    {
        $limit = $limit === true ? config('page.default_short_content_length') : $limit;

        return $limit ? str_limit(strip_tags($this->content), $limit) : $this->content;
    }
}