<?php

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\MainPageMenu\MainPageMenuRequest;
use App\Models\MainPageMenu;
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
 * Class MainPageMenuController
 * @package App\Http\Controllers\Backend
 */
class MainPageMenuController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "mainpagemenu";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'mainpagemenu.read',
        'create'          => 'mainpagemenu.create',
        'store'           => 'mainpagemenu.create',
        'show'            => 'mainpagemenu.read',
        'edit'            => 'mainpagemenu.read',
        'update'          => 'mainpagemenu.write',
        'destroy'         => 'mainpagemenu.delete',
        'ajaxFieldChange' => 'mainpagemenu.write',
    ];

    /**
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        Meta::title(trans('labels.mainpagemenu'));

        $this->breadcrumbs(trans('labels.mainpagemenu'), route('admin.mainpagemenu.index'));

        $this->middleware('slug.set', ['only' => ['store', 'update']]);
    }

    /**
     * Display a listing of the resource.
     * GET /mainpagemenu
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = MainPageMenu::select(
                'id',
                'menuable_id',
                'menuable_type',
                'status',
                'position'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'id', '=', '$1')
                ->filterColumn('menuable_type', 'where', 'menuable_type', 'LIKE', '%$1%')
                ->editColumn(
                    'menuable_id',
                    function ($model) {

                        return isset($model->menuable->name) ? $model->menuable->name : null;

                    }
                )
                ->editColumn(
                    'menuable_type',
                    function ($model) {

                        $types = MainPageMenu::getTypes();

                        return $types[$model->menuable_type];

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
                ->make();
        }

        $this->data('page_title', trans('labels.mainpagemenu'));
        $this->breadcrumbs(trans('labels.mainpagemenu_list'));

        return $this->render('views.mainpagemenu.index');
    }

    /**
     * Show the form for creating a new resource.
     * GET /mainpagemenu/create
     *
     * @return Response
     */
    public function create()
    {
        $this->data('model', new MainPageMenu);

        $this->data('page_title', trans('labels.mainpagemenu_create'));

        $this->breadcrumbs(trans('labels.mainpagemenu_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('views.mainpagemenu.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /mainpagemenu
     *
     * @param MainPageMenuRequest $request
     *
     * @return \Response
     */
    public function store(MainPageMenuRequest $request)
    {
        $input = $request->all();

        DB::beginTransaction();

        try {

            $model = new MainPageMenu($input);

            $model->save();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.mainpagemenu.index');
        } catch (Exception $e) {
            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     * GET /mainpagemenu/{id}
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
     * GET /mainpagemenu/{id}/edit
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {
            $model = MainPageMenu::whereId($id)->firstOrFail();

            $this->data('page_title', trans('labels.mainpagemenu_editing'));

            $this->breadcrumbs(trans('labels.mainpagemenu_editing'));

            $this->_fillAdditionalTemplateData($model);

            return $this->render('views.mainpagemenu.edit', compact('model'));
        } catch (ModelNotFoundException $e) {
            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.mainpagemenu.index');
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /mainpagemenu/{id}
     *
     * @param  int              $id
     * @param MainPageMenuRequest $request
     *
     * @return \Response
     */
    public function update($id, MainPageMenuRequest $request)
    {
        try {
            $model = MainPageMenu::findOrFail($id);

            $input = $request->all();

            DB::beginTransaction();

            $model->fill($input);

            $model->update();

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.mainpagemenu.index');
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
     * DELETE /mainpagemenu/{id}
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {
            $model = MainPageMenu::findOrFail($id);

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

        return Redirect::route('admin.mainpagemenu.index');
    }

    private function _fillAdditionalTemplateData($model = null) {

        $types = MainPageMenu::getTypes();

        $this->data('types', $types);

        $mainpagemenus = array();

        if($model) {


            $class = $model->menuable_type;

            $key = explode("\\", $class);

            $key = array_pop($key);

            $key = strtolower($key) . '_id';

            $class .= 'Translation';

            $mainpagemenus = $class::lists('name', $key)->toArray();

        }

        $this->data('mainpagemenus', $mainpagemenus);

    }

    public function ajaxLoad(Request $request) {

        $class = $request->get('type', null);

        if($class) {

            $used_ids = MainPageMenu::where('menuable_type', $class)->lists('menuable_id')->toArray();

            $key = explode("\\", $class);

            $key = array_pop($key);

            $key = strtolower($key) . '_id';

            $class .= 'Translation';

            $mainpagemenus = $class::whereNotIn($key, $used_ids)->lists('name', $key)->toArray();

            $html = view('mainpagemenu.partials.menuable_id')->with(['mainpagemenus' => $mainpagemenus])->render();

            return ['html' => $html];

        }

    }

}
