<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Services\PageService;
use Illuminate\Http\Request;

class PresentsController extends FrontendController
{

    protected $pageService = null;

    public $module = 'presents';

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

        Category::visible()
            ->with(['translations', 'visible_directProducts', 'visible_children', 'visible_children.visible_directProducts'])
            ->has('visible_directProducts')
            ->whereNull('parent_id')
            ->where('type', (string)Product::class)
            ->chunk(100, function($categories) use (&$categories_data) {

                foreach ($categories as $category) {

                    $category->products = $category->visible_directProducts;

                    foreach($category->getChildren(true) as $child) {

                        $category->products = $category->products->merge($child->visible_directProducts);

                        session()->forget('category_' . $child->id);

                    }

                    session()->forget('category_' . $category->id);

                    $category->products = $category->products->sortBy('position');

                    $categories_data[] = $category;

                }

            });

        $this->data('categories', $categories_data);

        return $this->render($this->pageService->getPageTemplate($model));

    }

    public function reload(Request $request) {

        $filters = $request->get('filters', []);

        $page = $request->get('page');

        $category = null;

        Category::visible()
            ->where('id', $request->get('category'))
            ->with(['translations', 'visible_directProducts', 'visible_children', 'visible_children.visible_directProducts'])
            ->has('visible_directProducts')
            ->whereNull('parent_id')
            ->where('type', (string)Product::class)
            ->chunk(100, function($categories_data) use (&$category, $filters, $page) {

                    foreach($categories_data as $category_data) {

                        $category_data->products = $category_data->visible_directProducts;

                        foreach ($category_data->getChildren(true) as $child) {

                            $category_data->products = $category_data->products->merge($child->visible_directProducts);

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

                    }

            });

        $old_page = session('category_' . $request->get('category'), null);

        $type = "less";

        if($page ==1 || (($page > $old_page || !$old_page) && count($category->products) > ($page * 9)) ) {

            $type= "more";

        }

        session()->put('category_' . $request->get('category'), $page);

        return ['html' => view('presents.partials.category')->with(['page' => $page, 'category' => $category, 'type' => $type])->render()];

    }

}
