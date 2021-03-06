<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Page;
use App\Models\Set;
use App\Services\FilterService;
use App\Services\PageService;
use Illuminate\Http\Request;

class FlowerController extends FrontendController
{

    protected $pageService = null;

    protected $filterService;

    public $module = 'flowers';

    function __construct(PageService $pageService, FilterService $filterService)
    {

        parent::__construct();

        $this->pageService = $pageService;

        $this->filterService = $filterService;

    }

    public function index($sort = null) {

        $model = Page::with(['translations'])->visible()->whereSlug($this->module)->first();

        abort_if(!$model, 404);

        $this->filterService->addMeta($sort, $model);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->data('page', 1);

        $categories_data = array();

        $init_collection = collect();

        Category::visible()
            ->with([
                'translations',
                'boxes.visible_sets',
                'boxes.visible_sets.visible_flowers',
                'visible_bouquets',
                'visible_bouquets.visible_flowers',
                'visible_children',
                'visible_children.visible_bouquets',
                'visible_children.visible_bouquets.visible_flowers',
                'visible_children.boxes',
                'visible_children.boxes.visible_sets',
                'visible_children.boxes.visible_sets.visible_flowers'
            ])
            ->whereNull('parent_id')
            ->whereIn('type', [(string)Set::class, (string)Bouquet::class])
            ->chunk(100, function($categories) use (&$categories_data, &$init_collection, $sort) {

                foreach ($categories as $category) {

                    $category->products = collect();

                    try {

                        foreach ($category->getChildren(true) as $child) {

                            if ($child->type == (string)Set::class) {

                                foreach ($child->boxes as $box) {

                                    $category->products = $category->products->merge($box->visible_sets);

                                }

                            } else {

                                $category->products = $category->visible_bouquets;

                            }

                            session()->forget('category_' . $child->id);

                            session()->forget('category_type_' . $child->id);

                        }

                        session()->forget('category_' . $category->id);

                        session()->forget('category_type_' . $category->id);

                        if(!sizeof($category->products)) continue;

                        $res = $this->filterService->addFilter($category->products, $sort, 'price', true);

                        if($res['success']) {

                            $category->products = $res['result'];

                        }

                        $init_collection = $init_collection->merge($category->products);

                        $category->products = $category->products->sortBy('position');

                        $categories_data[] = $category;

                    } catch (\Exception $e) {

                        continue;

                    }

                }

            });

        $randomize = count($init_collection) > 54 ? 54 : count($init_collection);

        $init_collection = $init_collection->shuffle();

        if(count($init_collection) > 1) {

            $init_collection = $init_collection->slice(0, $randomize);

        }

        $this->data('categories', $categories_data);

        $this->data('init_collection', $init_collection);

        return $this->render($this->pageService->getPageTemplate($model));

    }

    public function reload($sort = null) {

        $filters = request('filters', []);

        $page = request('page');

        $category = null;

        Category::visible()
            ->where('id', request('category', null))
            ->with(['translations',
                'translations',
                'boxes.visible_sets',
                'boxes.visible_sets.visible_flowers',
                'visible_bouquets',
                'visible_bouquets.visible_flowers',
                'visible_children',
                'visible_children.visible_bouquets',
                'visible_children.visible_bouquets.visible_flowers',
                'visible_children.boxes',
                'visible_children.boxes.visible_sets',
                'visible_children.boxes.visible_sets.visible_flowers'
            ])
            ->whereNull('parent_id')
            ->whereIn('type', [(string)Set::class, (string)Bouquet::class])
            ->chunk(100, function($categories_data) use (&$category, $filters, $page, $sort) {

                foreach($categories_data as $category_data) {

                    $category_data->products = collect();

                    try {

                        foreach ($category_data->getChildren(true) as $child) {

                            if ($child->type == (string)Set::class) {

                                foreach ($child->boxes as $box) {

                                    $category_data->products = $category_data->products->merge($box->visible_sets);

                                }

                            } else {

                                $category_data->products = $category_data->visible_bouquets;

                            }

                        }

                        $res = $this->filterService->addFilter($category_data->products, $sort, 'price', true);

                        if($res['success']) {

                            $category_data->products = $res['result'];

                        }

                        if (sizeof($filters)) {
                            foreach ($filters as $key => $value) {

                                $key = $key == "date" ? "created_at" : $key;

                                if ($value == 'asc') {

                                    $category_data->products = $category_data->products->sortBy($key);


                                } else {

                                    $category_data->products = $category_data->products->sortByDesc($key);

                                }


                            }
                        } else {

                            $category_data->products = $category_data->products->sortBy('position');

                        }

                        $category = $category_data;

                    } catch (\Exception $e) {

                        continue;

                    }

                }

            });

        $old_page = session('category_' . request('category'), null);

        $old_type = session('category_type_' . request('category'), null);

        if(sizeof($filters) && $old_type && $old_page == $page) {

            $type = $old_type;

        } else {

            $type = "less";

            if ($page == 1 || (($page >= $old_page || !$old_page) && count($category->products) > ($page * 9))) {

                $type = "more";

            }

        }

        session()->put('category_type_' . request('category'), $type);

        session()->put('category_' . request('category'), $page);

        $html = view('single_page.partials.category')->with(['page' => $page, 'category' => $category, 'type' => $type, 'show' => true])->render();

        return ['html' => $html];

    }

}
