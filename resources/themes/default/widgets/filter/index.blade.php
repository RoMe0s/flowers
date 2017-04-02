    <form method="GET" action="{!! url()->current() !!}">
        <ul>
            <li>
                <span class="title">
                    Фильтровать:
                </span>
            </li>
            <li data-name="price">
                <span class="text-muted">
                    По цене
                </span>
                <a data-value="asc" @if(!request('price') || strtolower(request('price')) == "desc") data-active="false" @endif data-toggle="tooltip" data-placement="bottom" title="По возрастанию">
                    <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
                </a>
                <a data-value="desc" data-toggle="tooltip" data-placement="top" title="По убыванию" @if(!request('price') || strtolower(request('price')) == "asc") data-active="false" @endif>
                    <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
                </a>
                <input type="hidden" @if(request('price') !== NULL) name="price" @endif value="{!! request('price') !!}">
            </li>
            <li data-name="date">
                <span class="text-muted">
                    По дате
                </span>
                <a @if(!request('date') || strtolower(request('date')) == "desc") data-active="false" @endif data-value="asc" data-toggle="tooltip" data-placement="bottom" title="По возрастанию">
                    <i class="fa fa-long-arrow-up" aria-hidden="true"></i>
                </a>
                <a @if(!request('date') || strtolower(request('date')) == "asc") data-active="false" @endif data-value="desc" data-toggle="tooltip" data-placement="top" title="По убыванию">
                    <i class="fa fa-long-arrow-down" aria-hidden="true"></i>
                </a>
                <input type="hidden" @if(request('date') !== NULL) name="date" @endif value="{!! request('date') !!}">
            </li>
        </ul>
    </form>
