@extends('layouts.listable')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="sales-table table-responsive">
                        {!!
                            TablesBuilder::create(['id' => "datatable1", 'class' => "table table-bordered table-striped table-hover"], ['bStateSave' => true])
                            ->addHead([
                                ['text' => trans('labels.id')],
                                ['text' => trans('labels.name')],
                                ['text' => trans('labels.price')],
                                ['text' => trans('labels.publish_at')],
                                ['text' => trans('labels.status')],
                                ['text' => trans('labels.position')],
                                ['text' => trans('labels.actions')]
                            ])
                            ->addFoot([
                                ['attr' => ['colspan' => 7]]
                            ])
                             ->make()
                        !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop