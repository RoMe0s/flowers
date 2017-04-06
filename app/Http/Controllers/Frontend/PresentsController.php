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

        $categories = Category::visible()
            ->with(['translations', 'visible_directProducts'])
            ->has('visible_directProducts')
            ->where('type', (string)Product::class)->get();

        $this->data('categories', $categories);

        return $this->render($this->pageService->getPageTemplate($model));

    }

    public function reload(Request $request) {

        $filters = $request->get('filters', []);

        $category = Category::where('id', $request->get('category'))
            ->visible()
            ->with(['translations'])
            ->where('type', (string)Product::class)
            ->first();

        foreach($filters as $key => $value) {

            $key = $key == "date" ? "created_at" : $key;

            $products = $category->visible_directProducts()->orderBy($key, $value)->get();

            $category->visible_directProducts = $products;

        }

        $page = $request->get('page');

        return ['html' => view('presents.partials.category')->with(['page' => $page, 'category' => $category])->render()];

    }

}
