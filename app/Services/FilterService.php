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

        return $filter;
    
    }

    public function addFilter(&$query, $slug = null, $field = 'price', $is_collection = false) {

        try {
        
            if(!isset($slug)) return;

            $filter = $this->_getFilters($slug);

            if($filter->type === ">") {

                if(!$is_collection) {

                    $query = $query->where($field, '>=', $filter->value);

                } else {

                    $query = $query->filter(function($value) use($filter, $field) {
                        return $value->{$field} >= $filter->value;
                    });

                }

            } else {

                if(!$is_collection) {

                    $query = $query->where($field, '<=', $filter->value);

                } else {

                    $query = $query->filter(function($value) use($filter, $field) {
                       return $value->{$field} <= $filter->value;
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
