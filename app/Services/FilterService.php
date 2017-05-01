<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\FilterItem;

class FilterService {

    private function _getFilters($slug) {

        $filters = Cache::remember('filter_items', 10, function() {

            return FilterItem::visible()->positionSorted()->get();
        
        });

        $filter = $filters->where('slug', $slug)->first();
        
        abort_if(!$filter, 404);

        $sort_type = $filter->type === '>' ? 'sortBy' : 'sortByDesc';

        $closest_filter = $filters
            ->{$sort_type}('value')
            ->where('type', $filter->type)
            ->filter(function($item) use ($filter) {

                if($filter->type === '>') {

                     return $item->value > $filter->value;

                } else {

                    return $item->value < $filter->value;

                }

            })->first();

        return ['current' => $filter, 'closest' => $closest_filter];
    
    }

    public function addFilter(&$query, $slug = null, $field = 'price', $is_collection = false) {

        try {
        
            if(!isset($slug)) return;

            $filters = $this->_getFilters($slug);

            $filter = $filters['current'];

            $closest = $filters['closest'];

            if($filter->type === ">") {

                if(!$is_collection) {

                    $query = $query->where($field, '>=', $filter->value);

                    if(isset($closest)) {

                        $query = $query->where($field, '<', $closest->value);

                    }

                } else {

                    $query = $query->filter(function($value) use($filter, $field, $closest) {

                        if(isset($closest)) {

                            return $value->{$field} >= $filter->value && $value->{$field} < $closest->value;

                        } else {

                            return $value->{$field} >= $filter->value;

                        }

                    });



                }

            } else {

                if(!$is_collection) {

                    $query = $query->where($field, '<=', $filter->value);

                    if(isset($closest)) {

                        $query = $query->where($field, '>', $closest->value);

                    }

                } else {

                    $query = $query->filter(function($value) use($filter, $field, $closest) {

                        if(isset($closest)) {

                            return $value->{$field} <= $filter->value && $value->{$field} > $closest->value;

                        } else {

                            return $value->{$field} <= $filter->value;

                        }

                    });

                }

            }

            return ['success' => true, 'result' => $query];

        } catch(\Exception $e) {}

        return ['status' => false];
    
    }

    public function addMeta($slug, &$model) {

        if(!isset($slug)) return;

        $filter = $this->_getFilters($slug);

        $fields = [
            'meta_title' => ' ',
            'meta_description' => ' ',
            'meta_keywords' => ', '
        ];

        if($filter) {

            foreach($fields as $field => $separator) {

                try {

                    if (!empty($model->{$field})) {

                        $model->{$field} .= $separator . $filter->title;

                    }

                } catch (\Exception $e) {

                    continue;

                }

            }

        }

    }

}
