<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Page;
use App\Services\PageService;
use Carbon\Carbon;
use View;
use App\Models\Sale;

class ShareController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'shares';

    /**
     * @var \App\Services\PageService
     */
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        parent::__construct();

        $this->pageService = $pageService;
    }

    public function index() {

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($this->module)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $sales = Sale::visible()->positionSorted()->whereDate('publish_at', '=', Carbon::now()->format('Y-m-d'))->get();

        $this->data('sales', $sales);

        return $this->render($this->pageService->getPageTemplate($model));

    }

}
