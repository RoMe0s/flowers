<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 29.08.15
 * Time: 15:53
 */

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Services\NewsService;
use App\Services\PageService;

/**
 * Class NewsController
 * @package App\Http\Controllers\Frontend
 */
class NewsController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'news';

    /**
     * @var \App\Services\NewsService
     */
    protected $newsService;

    protected $pageService;

    /**
     * NewsController constructor.
     *
     * @param \App\Services\NewsService $newsService
     */
    public function __construct(NewsService $newsService, PageService $pageService)
    {
        parent::__construct();

        $this->newsService = $newsService;

        $this->pageService = $pageService;
    }

    /**
     * @return $this|\App\Http\Controllers\Frontend\NewsController
     */
    public function index()
    {
        $model = Page::withTranslations()->whereSlug($this->module)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->data('news', $this->newsService->getList());

        return $this->render($this->pageService->getPageTemplate($model));
    }
}