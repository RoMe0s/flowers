<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Bouquet;
use App\Models\Category;
use App\Models\Page;
use App\Models\Set;
use App\Services\PageService;
use Illuminate\Http\Request;

class FlowerController extends FrontendController
{

    protected $pageService = null;

    public $module = 'flowers';

    function __construct(PageService $pageService)
    {

        parent::__construct();

        $this->pageService = $pageService;
    }

    public function index() {

        $model = Page::with(['translations'])->visible()->whereSlug($this->module)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->data('page', 1);

        $categories_data = array();

        $init_collection = collect();

        Category::visible()
            ->with(['translations',
                'boxes.visible_sets',
                'boxes.visible_sets.flowers',
                'visible_bouquets',
                'visible_bouquets.flowers',
                'visible_children',
                'visible_children.visible_bouquets',
                'visible_children.visible_bouquets.flowers',
                'visible_children.boxes',
                'visible_children.boxes.visible_sets',
                'visible_children.boxes.visible_sets.flowers'
            ])
            ->whereNull('parent_id')
            ->whereIn('type', [(string)Set::class, (string)Bouquet::class])
            ->chunk(100, function($categories) use (&$categories_data, &$init_collection) {

                foreach ($categories as $category) {

                    try {

                        if ($category->type == (string)Set::class) {

                            $category->products = collect();

                            foreach ($category->boxes as $box) {

                                $category->products = $category->products->merge($box->visible_sets);

                            }

                        } else {

                            $category->products = $category->visible_bouquets;

                        }

                        foreach ($category->getChildren(true) as $child) {

                            if ($child->type == (string)Set::class) {

                                $child->products = collect();

                                foreach ($child->boxes as $box) {

                                    $child->products = $child->products->merge($box->visible_sets);

                                }

                            } else {

                                $child->products = $child->visible_bouquets;

                            }

                            $category->products = $category->products->merge($child->products);

                            session()->forget('category_' . $child->id);

                            session()->forget('category_type_' . $child->id);

                        }

                        session()->forget('category_' . $category->id);

                        session()->forget('category_type_' . $category->id);

                        $randomize = count($category->products) > 12 ? 12 : count($category->products);

                        $init_collection = $init_collection->merge($category->products->random($randomize));

                        $category->products = $category->products->sortBy('position');

                        $categories_data[] = $category;

                    } catch (\Exception $e) {

                        continue;

                    }

                }

            });

        $this->data('categories', $categories_data);

        $this->data('init_collection', $init_collection);

        return $this->render($this->pageService->getPageTemplate($model));

    }

    public function reload(Request $request) {

        $filters = $request->get('filters', []);

        $page = $request->get('page');

        $category = null;

        Category::visible()
            ->where('id', $request->get('category'))
            ->with(['translations',
                'boxes.visible_sets',
                'boxes.visible_sets.flowers',
                'visible_bouquets',
                'visible_bouquets.flowers',
                'visible_children',
                'visible_children.visible_bouquets',
                'visible_children.visible_bouquets.flowers',
                'visible_children.boxes',
                'visible_children.boxes.visible_sets',
                'visible_children.boxes.visible_sets.flowers'
            ])
            ->whereNull('parent_id')
            ->whereIn('type', [(string)Set::class, (string)Bouquet::class])
            ->chunk(100, function($categories_data) use (&$category, $filters, $page) {

                foreach($categories_data as $category_data) {

                    try {

                        if ($category_data->type == (string)Set::class) {

                            $category_data->products = collect();

                            foreach ($category_data->boxes as $box) {

                                $category_data->products = $category_data->products->merge($box->visible_sets);

                            }

                        } else {

                            $category_data->products = $category_data->visible_bouquets;

                        }

                        foreach ($category_data->getChildren(true) as $child) {

                            if ($child->type == (string)Set::class) {

                                $child->products = collect();

                                foreach ($child->boxes as $box) {

                                    $child->products = $child->products->merge($box->visible_sets);

                                }

                            } else {

                                $child->products = $child->visible_bouquets;

                            }

                            $category_data->products = $category_data->products->merge($child->products);

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

        $old_page = session('category_' . $request->get('category'), null);

        $old_type = session('category_type_' . $request->get('category'), null);

        if(sizeof($filters) && $old_type && $old_page == $page) {

            $type = $old_type;

        } else {

            $type = "less";

            if ($page == 1 || (($page >= $old_page || !$old_page) && count($category->products) > ($page * 9))) {

                $type = "more";

            }

        }

        session()->put('category_type_' . $request->get('category'), $type);

        session()->put('category_' . $request->get('category'), $page);

        $html = view('single_page.partials.category')->with(['page' => $page, 'category' => $category, 'type' => $type, 'show' => true])->render();

        return ['html' => $html];

    }

}
