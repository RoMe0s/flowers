<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Contracts\MetaGettable;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;

class Sale extends Model implements MetaGettable
{

    use Translatable;
    use WithTranslationsTrait;

    protected $with = ['images'];

    public function images() {
        return $this->morphOne(Image::class, 'imagable');
    }

    public function getImages() {

        return isset($this->images) ? $this->images->images : array();

    }

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'image',
        'price',
        'publish_at',
        'status',
        'position',
        'slug',


        'name',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    /**
     * @param string $value
     *
     * @return string
     */
    public function setPublishAtAttribute($value)
    {
        $this->attributes['publish_at'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getPublishAtAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('d-m-Y');
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


    public function getMetaDescription()
    {
        return str_limit(
            empty($this->meta_description) ? strip_tags($this->getContent()) : $this->meta_description,
            config('seo.share.meta_description_length')
        );
    }

    public function getMetaImage()
    {
        $img = config('seo.share.'.$this->slug.'.image');

        return url(
            empty($img) ?
                (empty($this->image) ? config('seo.share.default_image') : $this->image)
                : $img
        );
    }

    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    public function getMetaTitle()
    {
        return empty($this->meta_title) ? $this->name : $this->meta_title;
    }

    public function getUrl()
    {
        return route('product.show', ['category' => 'shares', 'slug' => $this->slug]);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return empty($this->content) ? $this->short_content : $this->content;
    }

    public function getCategoryId() {
        return -1;
    }

    public function getDataForTable() {

        return [
            'Дата действия' => $this->publish_at
        ];

    }
}
