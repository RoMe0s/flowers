@extends('category.index')

@section('category-content')
    <section>
        <h1 class="text-center">
            {!! $model->name !!}
            <hr class="flower">
        </h1>
        <br />
        @if(!isset($bouquets) || !sizeof($bouquets))
            <p class="text-center">
                <i>Букетов в продаже нет</i>
            </p>
        @else
            <div class="row">
                <div class="col-sm-4 col-xs-12 col-sm-offset-8">
                    <form action="{!! url()->current() !!}" method="get">
                        {!! Form::hidden('is_request', true) !!}
                        <div class="input-group">
                            <select class="form-control" name="price">
                                <option value="2500" @if(isset($_GET['price']) && $_GET['price'] == '2500') selected="selected" @endif>до 2500 руб.</option>
                                <option value="5000" @if(isset($_GET['price']) && $_GET['price'] == '5000') selected="selected" @endif>до 5000 руб.</option>
                                <option value="greater" @if(isset($_GET['price']) && $_GET['price'] == 'greater') selected="selected" @endif>от 5000 руб.</option>
                            </select>
                            <div class="input-group-btn">
                                <input class="btn btn-purple" type="submit" value="Сортировать">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <br>

            <div class="row">
                <?php $i = 0; ?>

                @foreach($bouquets as $bouquet)
                    @if($i % 4 == 0 && $i != 0) {!! '</div><br><div class="row">' !!} @endif

                    <div class="col-lg-3  col-sm-3 col-xs-12">
                        <a href="{{ $bouquet->image }}" data-lightbox="bouquets">
                            <div class="photo" style="background-image: url('{{ $bouquet->image }}');">
                                <div class="layout">
                                    <p>
                                        Состав: {{ implode(", ", $bouquet->flowers->pluck('title')->all()) }}
                                    </p>
                                </div>
                            </div>
                        </a>

                        <p class="text-center">{{ $bouquet->title }}</p>
                        <p class="text-center">
                            <span class="price">{{ $bouquet->price }} руб.</span>
                        </p>
                        <p class="text-center">
                            @if($bouquet->hasInStock())
                                <span class="btn-group-vertical">
                                        <button class="btn btn-purple-outline" onclick="Cart.addBouquet('{{ $bouquet->id }}', '{{ csrf_token() }}')">
                                            Добавить в корзину <i class="fa fa-shopping-basket"></i>
                                        </button>
                                        <a class="btn btn-purple-outline" href="/api/cart/add/bouquet?id={{ $bouquet->id }}">
                                            Купить в один клик <i class="fa fa-shopping-basket"></i>
                                        </a>
                                    </span>
                            @else
                                <br><br><span class="label label-danger">Нет в наличии</span>
                            @endif
                        </p>
                    </div>

                    <?php $i++; ?>
                @endforeach
            </div>

            <br>

            <div class="text-center">{{ $bouquets->appends(request()->except('page'))->links() }}</div>
        @endif
    </section>
@endsection