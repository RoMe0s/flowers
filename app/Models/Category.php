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
        'parent_id',


        'name',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'content',
        'short_content',
        'content_two'
    ];

    public function parent() {

        return $this->belongsTo(Category::class, 'parent_id')->with(['parent', 'parent.translations']);

    }

    public function visible_parent() {

        return $this->parent()->visible();

    }


    public function getParents($visible = false) {

        $parents = array();

        $category = $this;

        if($visible) {

            while ($category->visible_parent) {

                $parent = $category->visible_parent;

                $parents[] = $parent;

                $category = $parent;

            }

        } else {

            while ($category->parent) {

                $parent = $category->parent;

                $parents[] = $parent;

                $category = $parent;

            }

        }

        return $parents;

    }

    public function children() {

        return $this->hasMany(Category::class, 'parent_id')->with(['children', 'children.translations']);

    }

    public function visible_children() {

        return $this->children()->visible();

    }

    private function _recursiveWalk($category, &$result, $method) {

        $result[] = $category;

        foreach ($category->{$method} as $child) {

            $this->_recursiveWalk($child, $result, $method);

        }

    }


    public function getChildren($visible = false) {

        $children = array();

        $category = $this;

        if($visible) {

            $this->_recursiveWalk($category, $children, 'visible_children');

        } else {

            $this->_recursiveWalk($category, $children, 'children');

        }

        return $children;


    }

    public static function getTypes($type = null) {
        $types = array(
            (string)Bouquet::class => 'Букеты',
            (string)Set::class => 'Наборы',
            (string)Product::class => 'Подарки'
        );

        return $type !== null ? $types[$type] : $types;
    }


    public function products() {
        return $this->belongsToMany(Product::class, 'products_categories');
    }

    public function directProducts() {

        return $this->hasMany(Product::class, 'category_id')->with(['translations']);

    }

    public function visible_directProducts() {

        return $this->directProducts()->visible();

    }

    public function boxes() {
        return $this->hasMany(Box::class);
    }

    public function bouquets() {

        return $this->hasMany(Bouquet::class)->with(['translations']);

    }

    public function visible_bouquets() {

        return $this->bouquets()->visible();

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
