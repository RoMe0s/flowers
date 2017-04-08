<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;
use App\Models\Page;
use App\Services\CategoryService;
use App\Services\PageService;
use Response;
use View;

/**
 * Class PageController
 * @package App\Http\Controllers\Frontend
 */
class PageController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'page';

    /**
     * @var \App\Services\PageService
     */
    protected $pageService;

    protected $categoryService;

    /**
     * PageController constructor.
     * @param PageService $pageService
     * @param CategoryService $categoryService
     */
    public function __construct(PageService $pageService, CategoryService $categoryService)
    {
        parent::__construct();

        $this->pageService = $pageService;

        $this->categoryService = $categoryService;
    }

    /**
     * @return $this
     */
    public function getHome()
    {
        $model = Page::with(['translations'])->whereSlug('home')->first();

        abort_if(!$model || is_system_page($model->slug), 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        return $this->render($this->pageService->getPageTemplate($model));
    }

    /**
     * @return $this|\App\Http\Controllers\Frontend\PageController
     */
    public function getPage($slug)
    {
        if ($slug == 'home') {
            return redirect(route('home'), 301);
        }

        $model = Page::with(['translations'])->visible()->whereSlug($slug)->first();

        $model = !$model ? $this->categoryService->find($slug) : $model;

        abort_if(!$model || is_system_page($model->slug), 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        if($model instanceof Page) {
            $view = $this->pageService->getPageTemplate($model);
        } else {

            $this->categoryService->setBreadcrumbs($model, $this->breadcrumbs);

            $view = $this->categoryService->getView();
        }

        return $this->render($view);
    }

    /**
     * @return $this
     */
    public function notFound()
    {
        $view = View::make('errors.404')->render();

        return Response::make($view, 404);
    }
}