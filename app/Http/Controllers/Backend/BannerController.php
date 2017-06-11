<?php
/**
 * Created by Newway, info@newway.com.ua
 * User: ddiimmkkaass, ddiimmkkaass@gmail.com
 * Date: 10.06.15
 * Time: 17:50
 */

namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\Banner\BannerRequest;
use App\Models\Banner;
use App\Models\BannerItem;
use App\Traits\Controllers\AjaxFieldsChangerTrait;
use Datatables;
use DB;
use Exception;
use FlashMessages;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Meta;
use Redirect;
use Response;

/**
 * Class BannerController
 * @package App\Http\Controllers\Backend
 */
class BannerController extends BackendController
{

    use AjaxFieldsChangerTrait;

    /**
     * @var string
     */
    public $module = "banner";

    /**
     * @var array
     */
    public $accessMap = [
        'index'           => 'banner.read',
        'show'            => 'banner.read',
        'create'          => 'banner.write',
        'store'           => 'banner.write',
        'edit'            => 'banner.write',
        'update'          => 'banner.write',
        'destroy'         => 'banner.delete',
        'ajaxFieldChange' => 'banner.write',
    ];

    /**
     * @param \Illuminate\Routing\ResponseFactory $response
     */
    public function __construct(ResponseFactory $response)
    {
        parent::__construct($response);

        $this->breadcrumbs(trans('labels.banners'), route('admin.banner.index'));

        Meta::title(trans('labels.banner'));
    }

    /**
     * Display a listing of the resource.
     * GET /banner
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Response
     */
    public function index(Request $request)
    {
        if ($request->get('draw')) {
            $list = Banner::select(
                'id',
                'layout_position',
                'template',
                'status'
            );

            return $dataTables = Datatables::of($list)
                ->filterColumn('id', 'where', 'id', '=', '$1')
                ->filterColumn('layout_position', 'where', 'layout_position', '=', '$1')
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
                            ['model' => $model, 'type' => 'banner']
                        )->render();
                    }
                )
                ->setIndexColumn('id')
                ->make();
        }

        $this->data('page_title', trans('labels.banners'));
        $this->breadcrumbs(trans('labels.banners_list'));

        return $this->render('views.banner.index');
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * @return $this
     */
    public function create()
    {
        $this->data('model', new Banner());

        $this->data('page_title', trans('labels.banner_create'));

        $this->breadcrumbs(trans('labels.banner_create'));

        $this->_fillAdditionalTemplateData();

        return $this->render('banner.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /banner
     *
     * @param \App\Http\Requests\Backend\Banner\BannerRequest $request
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(BannerRequest $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();

            $model = new Banner($input);

            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.banner.index');

        } catch (Exception $e) {

            DB::rollBack();

            FlashMessages::add('error', trans('messages.save_failed'));

            return Redirect::back()->withInput();

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        try {

            $model = Banner::with('items')->findOrFail($id);

            $this->data('page_title', '"'. $model->layout_position .'"');

            $this->breadcrumbs(trans('labels.banner_editing'));

            $this->_fillAdditionalTemplateData();

            return $this->render('views.banner.edit', compact('model'));

        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.banner.index');

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int                                        $id
     * @param \App\Http\Requests\Backend\Banner\BannerRequest $request
     *
     * @return \Response
     */
    public function update($id, BannerRequest $request)
    {
        try {

            $model = Banner::findOrFail($id);

            DB::beginTransaction();

            $model->fill($request->all());

            $model->save();

            $this->_processItems($model);

            DB::commit();

            FlashMessages::add('success', trans('messages.save_ok'));

            return Redirect::route('admin.banner.index');

        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

            return Redirect::route('admin.banner.index');

        } catch (Exception $e) {

            DB::rollBack();

            FlashMessages::add("error", trans('messages.update_error').': '.$e->getMessage());

            return Redirect::back()->withInput();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        try {

            $model = Banner::findOrFail($id);

            $model->delete();

            FlashMessages::add('success', trans("messages.destroy_ok"));

        } catch (ModelNotFoundException $e) {

            FlashMessages::add('error', trans('messages.record_not_found'));

        } catch (Exception $e) {

            FlashMessages::add("error", trans('messages.delete_error').': '.$e->getMessage());

        }

        return Redirect::route('admin.banner.index');
    }

    /**
     * @param \App\Models\Banner $model
     */
    private function _processItems(Banner $model)
    {
        $data = request('items.remove', []);

        foreach ($data as $id) {

            try {

                $item = $model->items()->findOrFail($id);
                $item->delete();

            } catch (Exception $e) {

                FlashMessages::add("error", trans("messages.item destroy failure"." ".$id.". ".$e->getMessage()));
                continue;

            }

        }

        $data = request('items.old', []);
        foreach ($data as $key => $item) {
            try {

                $_item = BannerItem::findOrFail($key);

                $_item->update($item);

            } catch (Exception $e) {

                FlashMessages::add(
                    "error",
                    trans("messages.item update failure: ".$e->getMessage())
                );

                continue;

            }
        }

        $data = request('items.new', []);
        foreach ($data as $item) {
            try {

                $item = new BannerItem($item);

                $model->items()->save($item);

            } catch (Exception $e) {

                FlashMessages::add(
                    "error",
                    trans("messages.item save failure"." ".$item['name'].". ".$e->getMessage())
                );

                continue;

            }
        }
    }

    function _fillAdditionalTemplateData() {

        $this->data(
            'templates',
            get_templates(base_path('resources/themes/'.config('app.theme').'/widgets/banner/templates'))
        );

    }

}