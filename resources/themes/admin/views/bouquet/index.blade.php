@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="bouquets-table table-responsive">
                        <script>
                            var datatableСallbacks = {
                                    fnRowCallback: function (row, data, displayIndex, displayIndexFull) {
                                        var _img = $(row).find('td:eq(1)').find('img');
                                        if(_img.attr('src')) {

                                            $(row).addClass('tr-with-image');

                                        }
                                    }
                                }
                        </script>
                            {!!
                                TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                                ->addHead([
                                    ['text' => trans('labels.id')],
                                    ['text' => trans('labels.image')],
                                    ['text' => trans('labels.name')],
                                    ['text' => trans('labels.price')],
                                    ['text' => trans('labels.type')],
                                    ['text' => trans('labels.status')],
                                    ['text' => trans('labels.position')],
                                    ['text' => trans('labels.actions')]
                                ])
                                ->addFoot([
                                    ['attr' => ['colspan' => 8]]
                                ])
                                 ->make()
                            !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop