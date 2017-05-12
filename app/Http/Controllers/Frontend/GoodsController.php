<?php
/**
 * Created by PhpStorm.
 * User: rome0s
 * Date: 11.03.17
 * Time: 14:32
 */

namespace App\Http\Controllers\Frontend;
use App\Decorators\Phone;
use App\Http\Requests\Frontend\Individual\IndividualCreateRequest;
use App\Models\Individual;
use App\Models\Page;
use App\Models\Product;
use App\Services\PageService;
use FlashMessages;
use Event;
use App\Events\Frontend\IndividualStored;
use App\Traits\Controllers\SaveImageTrait;


class GoodsController extends FrontendController
{

    use SaveImageTrait;

    /**
     * @var string
     */
    public $module = 'goods';

    protected $page = null;

    /**
     * @var \App\Services\PageService
     */
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        parent::__construct();

        $this->pageService = $pageService;

    }

    private function _init() {

        $type = explode('/', request()->path());

        $type = array_pop($type);

        $model = Page::with(['translations', 'parent', 'parent.translations'])->visible()->whereSlug($type)->first();

        abort_if(!$model, 404);

        $this->data('model', $model);

        $this->fillMeta($model, $this->module);

        $this->page = $model;

    }

    public function related() {

        $this->_init();

        $products = Product::visible()->positionSorted()->with(['translations'])->paginate(9);

        $this->data('products', $products);

        return $this->render($this->pageService->getPageTemplate($this->page));

    }

    public function individual() {

        $this->_init();

        return $this->render($this->pageService->getPageTemplate($this->page));
    }

    public function storeIndividual(IndividualCreateRequest $request) {

        $input = $request->all();

        $phone = new Phone($input['phone']);

        $input['phone'] = $phone;

        try {

            $this->validateImage('image');

            $model = new Individual();

            $model->fill($input);

            $model->save();

            $this->setImage($model, 'image', $this->module);

            $model->save();

            FlashMessages::add('success', 'Ваши пожелания переданы администратору. В ближайшее время с Вами свяжутся.');

            Event::fire(new IndividualStored($model));

            return redirect()->back();

        } catch (\Exception $e) {

            FlashMessages::add('error', 'Произошла ошибка, попробуйте пожалуйста позже');

            return redirect()->back()->withInput($input);
        }
    }
}