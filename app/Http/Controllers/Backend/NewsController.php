<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\News\NewsRequest;
use App\Models\News;
use App\Models\User;
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
 * Class NewsController
 * @package App\Http\Controllers\Backend
 */
class NewsController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "news";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'news.read',
        'create'          => 'news.create',
        'store'           => 'news.create',
        'show'            => 'news.read',
        'edit'            => 'news.read',
        'update'          => 'news.write',
        'destroy'         => 'news.delete',
        'ajaxFieldChange' => 'news.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.news'));

        $this->breadcrumbs(trans('labels.news'), route('admin.news.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /news
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = News::withTranslations()->joinTranslations('news', 'news_translations')->select(
                'news.id',
                'news_translations.name',
                'news.publish_at',
                'status',
                'position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'news.id', '=', '$1')
                ->filterColumn('news_translations.name', 'where', 'news_translations.name', 'LIKE', '%$1%')
                ->editColumn(
                    'publish_at',
                    function($model) {
                        return $model->publish_at ? $model->publish_at : trans('labels.no');
                    }
                )
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
                    'position',
                    function ($model) {
                        return view(
                            'partials.datatables.text_input',
                            ['model' => $model, 'type' => $this->module, 'field' => 'position']
                        )->render();
                    }
                )
                ->editColumn(
                    'actions',
                    function ($model) {
                        return view(
                            'partials.datatables.control_buttons',
                            ['model' => $model, 'type' => $this->module]
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->removeColumn('content')
                ->removeColumn('translations')
                ->removeColumn('slug')
                ->make();
        }

        $this->data('page_title', trans('labels.news'));
        $this->breadcrumbs(trans('labels.news_list'));

        return $this->render('views.news.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /news/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new News);

        $this->data('page_title', trans('labels.news_create'));

        $this->breadcrumbs(trans('labels.news_create'));

        return $this->render('views.news.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /news
     *
     * @param NewsRequest $request
     *
     * @return \Response
     */
    public function store(NewsRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new News($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.news.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /news/{id}
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
     * GET /news/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = News::with('translations')->whereId($id)->firstOrFail();

            $this->data('page_title', '"'.$model->name.'"');

            $this->breadcrumbs(trans('labels.news_editing'));

            return $this->render('views.news.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.news.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /news/{id}
     *
     * @param  int              $id
     * @param NewsRequest $request
     *
     * @return \Response
     */
    public function update($id, NewsRequest $request)
    {
        try {
            $model = News::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.news.index');
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());
        }

        return Redirect::back()->withInput($input);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /news/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = News::findOrFail($id);

            if (!$model->delete()) {
                FlashMessages::add("error", trans("messages.destroy_error"));
            } else {
                FlashMessages::add('success', trans("messages.destroy_ok"));
            }
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));
        } catch (Exception $e) {
            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());
        }

        return Redirect::route('admin.news.index');
    }
}