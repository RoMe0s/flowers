<p class="btn-mobile">
    <button class="btn btn-default btn-block" data-toggle="collapse" data-target=".filters-collapse">
        <i class="fa fa-bars"></i> Фильтры
    </button>
</p>

<div class="collapse navbar-collapse filters-collapse">
    <form action="{!! url()->current() !!}" method="get">
        {!! Form::hidden('is_request', '1') !!}
        <div class="form-card">
            <h4>Сортировка</h4>
            <p>
                <select class="form-control" name="sort" required>
                    <option value="asc" @if(isset($_GET['sort']) && $_GET['sort'] == 'asc') selected="selected" @endif>Цена: по возрастанию</option>
                    <option value="desc" @if(isset($_GET['sort']) && $_GET['sort'] == 'desc') selected="selected" @endif>Цена: по убыванию</option>
                    <option value="newness" @if(isset($_GET['sort']) && $_GET['sort'] == 'newness') selected="selected" @endif>По новизне</option>
                </select>
            </p>
        </div>
        <div class="form-card">
            <h4>Цена</h4>
            <div class="row">
                <div class="col-xs-6">
                    <p>
                        <input class="form-control" type="number" name="price_min" placeholder="от" value="{{ request('price_min', '') }}" min="0">
                    </p>
                </div>
                <div class="col-xs-6">
                    <p>
                        <input class="form-control" type="number" name="price_max" placeholder="до" value="{{ request('price_max', '') }}" min="0">
                    </p>
                </div>
            </div>
        </div>
        <div class="form-card">
            <h4>Размеры коробки</h4>
            <select class="form-control" name="boxes[]" lang="ru" data-width="100%" multiple>
                @foreach($boxes as $box)
                    <option @if(in_array($box->id, request('boxes', []))) selected="selected" @endif value="{{ $box->id }}">{{ $box->title }}, {{ $box->size() }} см.</option>
                @endforeach
            </select>
        </div>
        <div class="form-card">
            <h4>Цветы</h4>
            <select class="form-control" name="flowers[]" lang="ru" data-width="100%" multiple>
                @foreach($flowers as $flower)
                    <option @if(in_array($flower->id, request('flowers', []))) selected="selected" @endif value="{{ $flower->id }}">{{ $flower->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-card">
            <p class="text-center">
                @if(!empty($_GET))
                    <a class="btn btn-default" href="{{ url(strtok($_SERVER["REQUEST_URI"],'?')) }}">Отменить</a>
                @endif

                <input class="btn btn-purple" type="submit" value="Искать">
            </p>
        </div>
    </form>
</div>