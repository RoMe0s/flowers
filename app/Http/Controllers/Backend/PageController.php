<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Page\PageCreateRequest;
use App\Http\Requests\Backend\Page\PageUpdateRequest;
use App\Models\Page;
use App\Services\PageService;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Meta;
use Redirect;
use Response;

/**
 * Class PageController
 * @package App\Http\Controllers\Backend
 */
class PageController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "page";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'page.read',
        'create'          => 'page.create',
        'store'           => 'page.create',
        'show'            => 'page.read',
        'edit'            => 'page.read',
        'update'          => 'page.write',
        'destroy'         => 'page.delete',
        'ajaxFieldChange' => 'page.write',
    ];

    /**
     * @var PageService
     */
    private $pageService;

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \App\Services\PageService                     $pageService
     */
    public function __construct(ResponseFactory $response, PageService $pageService)
    {
        parent::__construct($response);

        $this->pageService = $pageService;

        Meta::title(trans('labels.pages'));

        $this->breadcrumbs(trans('labels.pages'), route('admin.page.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Page::joinTranslations('pages', 'page_translations')->select(
                'pages.id',
                'page_translations.name',
                'pages.status',
                'pages.parent_id',
                'pages.slug'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'pages.id', '=', '$1')
                ->filterColumn('page_translations.name', 'where', 'page_translations.name', 'LIKE', '%$1%')
                ->editColumn(
                    'status',
                    function ($model) {
                        return view(
                            'partials.datatables.toggler',
                            ['model' => $model, 'type' => $this->module, 'field' => 'status']
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'front_link' => true, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('short_content')
                ->removeColumn('content')
                ->removeColumn('meta_keywords')
                ->removeColumn('meta_title')
                ->removeColumn('meta_description')
                ->removeColumn('parent_id')
                ->removeColumn('slug')
                ->removeColumn('translations')
                ->make();
        }

        $this->_fillAdditionTemplateData();

        $this->data('page_title', trans('labels.pages'));
        $this->breadcrumbs(trans('labels.pages_list'));

        return $this->render('views.page.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /page/create
     *
     * @return Response
     */
    public function create()
    {
        $this->_fillAdditionTemplateData();

        $this->data('model', new Page);

        $this->data('page_title', trans('labels.page_create'));

        $this->breadcrumbs(trans('labels.page_create'));

        return $this->render('views.page.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /page
     *
     * @param PageCreateRequest $request
     *
     * @return \Response
     */
    public function store(PageCreateRequest $request)
    {
        $input = $request->all();
        $input['parent_id'] = isset($input['parent_id']) ? $input['parent_id'] : null;

        DB::beginTransaction();

        try {
            $model = new Page($input);

            $model->save();

            $this->pageService->setExternalUrl($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.page.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /page/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     * GET /page/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = Page::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.page.index');
        }

        $this->data('page_title', '"'.$model->name.'"');

        $this->breadcrumbs(trans('labels.page_editing'));

        $this->_fillAdditionTemplateData($model);

        return $this->render('views.page.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /page/{id}
     *
     * @param  int              $id
     * @param PageUpdateRequest $request
     *
     * @return \Response
     */
    public function update($id, PageUpdateRequest $request)
    {

        $input = $request->all();

        try {
            $model = Page::findOrFail($id);

            if(is_system_page($model->slug)) {
                unset($input['slug']);
            }

        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.page.index');
        }

        $input['parent_id'] = isset($input['parent_id']) ? $input['parent_id'] : null;

        DB::beginTransaction();

        try {
            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.page.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput($input);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /page/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = Page::findOrFail($id);

            if(!is_system_page($model->slug)) {

                if (!$model->delete()) {
                    FlashMessages::add("error", trans("messages.destroy_error"));
                } else {
                    FlashMessages::add('success', trans("messages.destroy_ok"));
                }

            } else {

                FlashMessages::add("error", 'Системная страница');

            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        }

        return Redirect::route('admin.page.index');
    }

    /**
     * set to template addition variables for add\update page
     *
     * @param \App\Models\Page|null $model
     */
    private function _fillAdditionTemplateData($model = null)
    {
        if ($model) {
            $list = Page::with('translations')->where('id', '<>', $model->id)
                ->where(
                    function ($query) use ($model) {
                        $query->orWhere('parent_id', '<>', $model->id)
                            ->orWhere('parent_id', '=', null);
                    }
                )->get();
        } else {
            $list = Page::with('translations')->get();
        }

        $this->data(
            'templates',
            get_templates(base_path('resources/themes/'.config('app.theme').'/views'))
        );

        $parents = ['' => trans('labels.no')];
        foreach ($list as $item) {
            $parents[$item->id] = $item->name;
        }
        $this->data('parents', $parents);
    }
}