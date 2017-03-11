<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11.03.17
 * Time: 14:32
 */

namespace App\Http\Controllers\Frontend;
use App\Http\Requests\Frontend\Subscription\SubscriptionCreateRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Models\Subscription;


class SubscriptionController extends FrontendController
{

    /**
     * @var string
     */
    public $module = 'subscriptions';

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

        $subscriptions = Subscription::with('users')->visible()->positionSorted()->get();

        $this->data('subscriptions', $subscriptions);

        return $this->render($this->pageService->getPageTemplate($model));

    }

}