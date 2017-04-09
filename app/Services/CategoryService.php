<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11.03.17
 * Time: 17:21
 */

namespace App\Services;
use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Product;
use App\Models\Set;


class CategoryService
{

    public $module = 'category';

    public $_view = 'index';

    public function find($slug) {

        $model =  Category::with(['translations', 'visible_children', 'visible_parent'])->visible()->where('slug', $slug)->first();

        $category_ids = [$model->id];

        foreach ($model->getChildren(true) as $child) {

            $category_ids[] = $child->id;

        }

        if($model) {

            switch ($model->type) {
                case (string)Bouquet::class:
                    $bouquets = Bouquet::with(['translations', 'flowers', 'flowers'])
                        ->whereIn('category_id', $category_ids)
                        ->visible();
                    $this->_addFilters($bouquets);
                    view()->share('bouquets', $bouquets->paginate(12));
                    $this->_view = 'bouquets';
                    break;
                case (string)Set::class:
                    $sets = Set::with(['translations', 'flowers', 'box', 'flowers'])
                        ->visible()
                        ->whereHas('box', function ($query) use ($category_ids) {
                        return $query->whereIn('category_id', $category_ids);
                    });
                    $this->_addFilters($sets);
                    view()->share('sets', $sets->paginate(12));
                    break;
                case (string)Product::class:
                    $products = Product::with(['translations'])
                        ->visible()
                        ->whereIn('category_id', $category_ids);
                        $this->_addFilters($products);
                        view()->share('products', $products->paginate(12));
                        $this->_view = "presents";
                    break;
                default:
                    abort(404);
                    break;
            }

        }


        return $model;

    }

    private function _addFilters($queryBuilder) {
        $used = false;
        if(request()->has('price') || request()->has('date')) {
            $filters = array_filter(request()->all());

/*            if(isset($filters['sort'])) {
                switch ($filters['sort']) {
                    case 'asc':
                        $queryBuilder->orderBy('price', 'ASC');
                        break;
                    case 'desc':
                        $queryBuilder->orderBy('price', 'DESC');
                        break;
                    default:
                        $queryBuilder->orderBy('id', 'DESC');
                        break;
                }
            }
            if(isset($filters['price_min']) && is_numeric($filters['price_min'])) {
                $queryBuilder->where('price', '>=', $filters['price_min']);
            }
            if(isset($filters['price_max']) && is_numeric($filters['price_max'])) {
                $queryBuilder->where('price', '<=', $filters['price_max']);
            }
            if(isset($filters['boxes']) && is_array($filters['boxes'])) {
                $queryBuilder->whereIn('box_id', $filters['boxes']);
            }
            if(isset($filters['flowers']) && is_array($filters['flowers'])) {
                $queryBuilder->whereHas('flowers', function($query) use ($filters) {
                    return $query->whereIn('id', $filters['flowers']);
                });
            }*/
            if(isset($filters['date'])) {

                switch ($filters['date']) {

                    case 'asc':
                        $queryBuilder->orderBy('id', 'ASC');
                        $used = true;
                        break;

                    case 'desc':
                        $queryBuilder->orderBy('id', 'DESC');
                        $used = true;
                        break;
                }

            }
            if(isset($filters['price'])) {

                if(is_numeric($filters['price'])) {

                    $queryBuilder->where('price', '<=', $filters['price']);

                } else {

//                    $queryBuilder->where('price', '>', 5000);

                    switch ($filters['price']) {

                        case 'greater':
                            $queryBuilder->where('price', '>', 5000);
                            $used = true;
                            break;

                        case 'asc':
                            $queryBuilder->orderBy('price', 'ASC');
                            $used = true;
                            break;

                        case 'desc':
                            $queryBuilder->orderBy('price', 'DESC');
                            $used = true;
                            break;
                    }

                }

            }
        } /*else {
            $queryBuilder->positionSorted();
        }*/

        if(!$used) {

            $queryBuilder->positionSorted();

        }
    }

    public function getView() {
        return $this->module . '.' . $this->_view;
    }

    public function setBreadcrumbs(Category $model, &$breadcrumbs) {

        foreach($model->getParents(true) as $parent) {

            $breadcrumbs[] = [
                'name' => $parent->name,
                'url' => route('pages.show', ['slug' => $parent->slug])
            ];

        }

        $breadcrumbs[] = [
          'name' => $model->name
        ];

    }

}