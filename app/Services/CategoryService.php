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
use App\Models\Set;


class CategoryService
{

    public $module = 'category';

    public $_view = 'index';

    public function find($slug) {
        $model =  Category::with(['translations'])->visible()->where('slug', $slug)->first();

        if($model) {

            switch ($model->type) {
                case (string)Bouquet::class:
                    $bouquets = Bouquet::with(['translations', 'flowers', 'visible_flowers'])
                        ->where('category_id', $model->id)
                        ->visible();
                    $this->_addFilters($bouquets);
                    view()->share('bouquets', $bouquets->paginate(12));
                    $this->_view = 'bouquets';
                    break;
                case (string)Set::class:
                    $sets = Set::with(['translations', 'flowers', 'box', 'visible_flowers'])
                        ->visible()
                        ->whereHas('box', function ($query) use ($model) {
                        return $query->where('category_id', $model->id);
                    });
                    $this->_addFilters($sets);
                    view()->share('sets', $sets->paginate(12));
                    break;
                default:
                    abort(404);
                    break;
            }

        }


        return $model;

    }

    private function _addFilters($queryBuilder) {
        if(request()->has('is_request')) {
            $filters = array_filter(request()->all());

            if(isset($filters['sort'])) {
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
            }
            if(isset($filters['price'])) {

                if(is_numeric($filters['price'])) {

                    $queryBuilder->where('price', '<=', $filters['price']);

                } else {

                    $queryBuilder->where('price', '>', 5000);

                }

            }
        } else {
            $queryBuilder->positionSorted();
        }
    }

    public function getView() {
        return $this->module . '.' . $this->_view;
    }

}