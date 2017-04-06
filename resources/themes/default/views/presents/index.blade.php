@extends('layouts.master')

@section('content')
    <section class="gifts">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-center">
                    {!! $model->name !!}
                    <hr class="gift"/>
                </h1>
                <br />
                {!! $model->getContent() !!}
                <br />
            </div>
        </div>
                <div class="selector-list row">
                    <ul class="col-sm-12 col-md-12 col-xs-12">
                        @php($product_count = 0)
                        @foreach($categories as $key => $category)
                            @php($product_count += $category->visible_directProducts->count())
                            <li class="col-md-2 col-sm-6 col-xs-6" data-category="{{$category->id}}" @if($key == 0) data-active="true" @else data-active="false" @endif>
                                <a title="{{$category->name}}">
                                    {{$category->name}}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12 filters-block">

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('price', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По цене')) !!}

                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 filter-item">

                        {!! Form::select('date', ['asc' => 'По возрастанию', 'desc' => 'По убыванию'], null, array('class' => 'form-control input-sm', 'placeholder' => 'По дате')) !!}

                    </div>

            </div>

        </div>

        <div class="col-sm-12 gifts-wrapper" data-loaded="false">
            @if(sizeof($categories))
                @foreach($categories as $category_number => $category)
                   @include('presents.partials.category')
                @endforeach
            @else
                <p class="text-center text-danger">
                    <i>По вашему запросу ничего не найдено</i>
                </p>
            @endif
            <br />
        </div>

        @widget__set(20)

    </section>
@endsection

@section('scripts')
    @parent
    <script src="{!! Theme::asset('js/presents.js') !!}"></script>
@endsection
