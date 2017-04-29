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
    public function getPage($slug, $sort = null)
    {
        if ($slug == 'home' && !$sort) {
            return redirect(route('home'), 301);
        } elseif($slug == 'home' && $sort) {
            abort(404); //we can't get sort type on main page
        }

        $model = Page::with(['translations'])->visible()->whereSlug($slug)->first();

        abort_if(isset($model) && isset($sort), 404); //we can't get sort type on page(except single_page)

        $model = !$model ? $this->categoryService->find($slug, $sort) : $model;

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
