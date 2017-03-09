@extends('layouts.editable')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="row box-footer buttons-top">
                <div class="col-md-3">
                    <a href="{!! empty($back_url) ? route('admin.home') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
                </div>

                @if ($user->hasAccess('order.write') || $user->hasAccess('order.create'))
                    <div class="col-md-4 pull-right ta-right">
                        {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat')) !!}
                    </div>
                @endif
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a aria-expanded="false" href="#general" data-toggle="tab">@lang('labels.basket')</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane in active basket-items-list" id="general">

                                @include('order.tabs.basket_items')

                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <div class="row box-footer">
                <div class="col-md-3">
                    <a href="{!! empty($back_url) ? route('admin.home') : $back_url !!}" class="btn btn-flat btn-sm btn-default">@lang('labels.cancel') </a>
                </div>

                @if ($user->hasAccess('order.write') || $user->hasAccess('order.create'))
                    <div class="col-md-4 pull-right ta-right">
                        {!! Form::submit(trans('labels.save'), array('class' => 'btn btn-success btn-flat')) !!}
                    </div>
                @endif
                
                @if(sizeof($orders))
                    <div class="pull-right col-md-2">
                        <a class="btn btn-info btn-flat btn-sm add-to-order-from-basket">@lang('labels.add to order')</a>
                    </div>
                    <div class="pull-right col-md-3 basket-basket-items">
                        {!! Form::select(null, $orders, null, array('class' => 'form-control select2 input-sm', 'placeholder' => trans('labels.order'))) !!}
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection