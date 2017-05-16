<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use App\Traits\Models\WithTranslationsTrait;
use App\Contracts\MetaGettable;

class Set extends Model implements MetaGettable
{
    use Translatable;
    use WithTranslationsTrait;

    protected $with = ['translations', 'box', 'box.category'];

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
        'image',
        'price',
        'box_id',
        'position',
        'status',
        'count',
        'slug',

        'name',
        'short_content',
        'content',
        'meta_keywords',
        'meta_title',
        'meta_description',
    ];

    public function scopeVisible($query) {
        return $query->where('status', true);
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
        return route('product.show', ['category' => $this->box->category->slug, 'slug' => $this->slug]);
    }

    public function flowers() {
        return $this->belongsToMany(Flower::class, 'sets_flowers');
    }

    public function visible_flowers() {
        return $this->flowers()->visible();
    }

    public function box() {
        return $this->belongsTo(Box::class)->with(['translations']);
    }

    public function category() {
        return $this->box->category;
    }

    public function getCategoryId() {
        return $this->box->category_id;
    }

    public function hasInStock() {
        $visible_flowers = $this->visible_flowers;

        return sizeof($visible_flowers) ? true : false;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return empty($this->content) ? $this->short_content : $this->content;
    }

    public function getDataForTable() {

        return [
            'Коробка' => $this->box->title,
            'Состав' => implode(", ", $this->flowers->pluck('title')->all()),
            'Количество цветов' => $this->count,
            'Размер' => $this->box->size() . ' см.'
        ];

    }


    public function getShowName() {

        return $this->name;

    }


}
