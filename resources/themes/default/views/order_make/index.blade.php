@extends('layouts.master')

@section('content')
    <section>
        @include('errors.form')
        <div id="order-make">
            {!! Form::open(['method' => 'POST', 'route' => 'post.order', 'ajax']) !!}
            <!-- Nav tabs -->
            <ul class="nav">
                <li class="active col-xs-3 col-sm-4">
                    <a href="#first" role="tab" data-toggle="tab">
                        <i class="fa fa-user-circle-o" aria-hidden="true"></i>
                        <p>
                            Кому
                        </p>
                    </a>
                </li>
                <li class="col-xs-5 col-sm-4">
                    <a href="#second" role="tab" data-toggle="tab">
                        <i class="fa fa-taxi" aria-hidden="true"></i>
                        <p>
                            Куда и когда
                        </p>
                    </a>
                    <div class="arrowtest-east"></div>
                </li>
                <li class="col-xs-4">
                    <a href="#third" role="tab" data-toggle="tab">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        <p>
                            Завершение
                        </p>
                    </a>
                    <div class="arrowtest-east"></div>
                </li>
            </ul>
            <br/>
            <!-- Tab panes -->
            <div class="tab-content clearfix">
                @include('order_make.steps.first')
                @include('order_make.steps.second')
                @include('order_make.steps.third')
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@endsection

@section('scripts')
    @parent
    <script type="text/javascript" src="{!! Theme::asset("js/order.js") !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js"></script>
    <script>
        $(document).ready(function(){
            $.datetimepicker.setLocale('ru');
            $('input[name="date"]').datetimepicker({
                timepicker: false,
                format: 'd-m-Y',
                minDate: 0
            });
        });
    </script>
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css" />
@endsection
