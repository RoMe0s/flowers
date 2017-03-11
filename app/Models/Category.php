<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Models\WithTranslationsTrait;
use Dimsav\Translatable\Translatable;
use App\Contracts\MetaGettable;

class Category extends Model implements MetaGettable
{

    use Translatable;
    use WithTranslationsTrait;

    public $timestamps = false;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'short_content',
        'content_two'
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'image',
        'slug',
        'position',
        'status',
        'type',


        'name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'short_content',
        'content_two'
    ];

    public static function getTypes($type = null) {
        $types = array(
            (string)Bouquet::class => 'Букеты',
            (string)Set::class => 'Наборы'
        );

        return $type !== null ? $types[$type] : $types;
    }


    public function products() {
        return $this->belongsToMany(Product::class, 'products_categories');
    }

    public function sets() {
        return $this->hasMany(Set::class);
    }

    public function boxes() {
        return $this->hasMany(Box::class);
    }

    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        return $query->where('status', true);
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
     * @return string
     */
    public function getContent()
    {
        return empty($this->content) ? $this->short_content : $this->content;
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
        return localize_url(url($this->slug));
    }
    
}
