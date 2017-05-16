<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;
use App\Contracts\MetaGettable;

class Product extends Model implements MetaGettable
{
    use Translatable;
    use WithTranslationsTrait;

    protected $with = ['translations', 'category'];

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

    /**
     * @var array
     */
    protected $fillable = [
        'position',
        'status',
        'size',
        'price',
        'image',
        'slug',
        'category_id',

        'name',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    public function scopeVisible($query) {
        return $query
            ->has('category')
            ->where('status', true);
    }

    public function scopePositionSorted($query, $type = 'ASC') {
        return $query->orderBy('position', $type);
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
        return route('product.show', ['category' => isset($this->category->slug) ? $this->category->slug : '', 'slug' => $this->slug]);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'products_categories')->with('translations');
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

    public function category() {

        return $this->belongsTo(Category::class,'category_id')->with(['translations']);

    }

    public function getDataForTable() {

        if($this->size) {

            return [
                'Количество' => $this->size
            ];

        }

        return [];

    }

    public function getShowName() {

        return $this->name . ' ' . ( !empty($this->size) ? '(' . $this->size . ')': '');

    }

}
