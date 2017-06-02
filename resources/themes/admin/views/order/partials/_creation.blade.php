@include('order.partials._buttons', ['class' => 'buttons-top'])

<div class="row">
    <div class="col-md-12">

        <div class="nav-tabs-custom">
            <div class="tab-content">
                <div class="tab-pane fade in active">
                    <h4 class="text-center">Клиент</h4>
                    <div class="input-group" id="find-user-for-order">
                        <input type="text" class="form-control" placeholder="Имя, телефон или email пользователя"
                               aria-describedby="basic-addon2">
                        <a href="#" class="input-group-addon" id="basic-addon2">Найти</a>
                    </div>
                    <h5 class="text-center">
                        Ничего не нашлось?
                        <a class="btn btn-primary btn-sm btn-flat" data-toggle="collapse"
                           data-target="#collapseUserCreation" aria-expanded="false"
                           aria-controls="collapseUserCreation">
                            Создать
                        </a>
                        нового пользователя
                    </h5>
                    <div class="collapse @if($errors->has('user.phone')) in @endif" id="collapseUserCreation">
                        <h4 class="text-danger text-center">
                            Будьте внимательны, предпочтение отдается созданому, а не выбраному пользователю
                        </h4>
                        <div class="form-group required @if($errors->has('user.phone')) has-error @endif">
                            {!! Form::label('phone', trans('labels.phone'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::text('user[phone]', null , ['class' => 'form-control input-sm inputmask-2', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.phone')]) !!}

                                {!! $errors->first('user.phone', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('name', trans('labels.fio'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::text('user[name]', null , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.fio')]) !!}

                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('email', trans('labels.email'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::text('user[email]', null, ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'placeholder' => trans('labels.email')]) !!}

                                {!! $errors->first('user.email', '<p recipient_name="help-block error">:message</p>') !!}

                            </div>
                        </div>
                    </div>

                    <div class="input-group" id="user_id" @if(old('user_id') === null || old('user_id') === "")style="display: none" @endif>
                        {!! Form::text('user_name', old('user_name'), array('class' => 'form-control input-sm', 'placeholder' => 'Пользователь', 'aria-describedby' => 'basic-addon3', 'readonly' => true)) !!}
                        {!! Form::hidden('user_id', null) !!}
                        <a href="#" class="input-group-addon btn btn-warning btn-sm btn-flat" id="basic-addon3">Удалить</a>
                    </div>

                    <br/>
                    <h4 class="text-center">Основная информация</h4>
                    @include('order.tabs.general')
                    <br/>
                    <h4 class="text-center">Данные получателя</h4>
                    <div class="form-group required @if ($errors->has('recipient_name')) has-error @endif">
                        {!! Form::label('recipient_name', trans('labels.fio'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                        <div class="col-xs-12 col-sm-9">
                            {!! Form::text('recipient_name', null , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.fio')]) !!}

                            {!! $errors->first('recipient_name', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group required @if ($errors->has('recipient_phone')) has-error @endif">
                        {!! Form::label('recipient_phone', trans('labels.phone'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                        <div class="col-xs-12 col-sm-9">
                            {!! Form::text('recipient_phone', null, ['placeholder' => trans('labels.phone'), 'class' => 'form-control input-sm inputmask-2', 'required' => true]) !!}

                            {!! $errors->first('recipient_phone', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </div>


                    <div class="form-group @if ($errors->has('anonymously')) has-error @endif">
                        {!! Form::label('anonymously', 'Анонимно', ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                        <div class="col-xs-12 col-sm-9">
                            {!! Form::select('anonymously', [0 => 'Нет', 1 => 'Да'], 0, ['class' => 'form-control input-sm']) !!}

                            {!! $errors->first('anonymously', '<p class="help-block error">:message</p>') !!}
                        </div>
                    </div>
                    <br/>
                    <h4 class="text-center">Адрес</h4>
                    @include('order.tabs.address')
                    <br/>
                    <h4 class="text-center">
                        Товары
                    </h4>
                    <div class="input-group" id="find-product-for-order">
                        <input type="text" class="form-control" placeholder="Название или #код"
                               aria-describedby="basic-addon2">
                        <a href="#" class="input-group-addon" id="basic-addon2">Найти</a>
                    </div>
                    <h5 class="text-center">
                        Ничего не нашлось?
                        <a class="btn btn-primary btn-sm btn-flat" data-toggle="collapse"
                           data-target="#collapseProductCreation" aria-expanded="false"
                           aria-controls="collapseProductCreation">
                            Создать
                        </a>
                        новый индивидуальный товар
                    </h5>
                    <div class="collapse @if ($errors->has('individual.price') || $errors->has('individual.text')) in @endif" id="collapseProductCreation">
                        <div class="form-group required @if ($errors->has('individual.price')) has-error @endif">
                            {!! Form::label('price', trans('labels.price'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::text('individual[price]', null , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.price')]) !!}

                                {!! $errors->first('individual.price', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group required @if ($errors->has('individual.text')) has-error @endif">
                            {!! Form::label('text', trans('labels.text'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::textarea('individual[text]', null , ['class' => 'form-control input-sm', 'aria-hidden' => 'true', 'required' => true, 'placeholder' => trans('labels.text')]) !!}

                                {!! $errors->first('individual.text', '<p class="help-block error">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('image', trans('labels.image'), ['class' => 'control-label col-xs-12 col-sm-2']) !!}

                            <div class="col-xs-12 col-sm-9">
                                {!! Form::imageInput('individual[image]', null) !!}
                            </div>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-success btn-sm btn-flat">
                                Добавить к заказу
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table products-table" @if(!sizeof(old('items', []))) style="display: none;" @endif>
                            <thead>
                            <tr>
                                <th class="col-xs-2">
                                    ID(тип)
                                </th>
                                <th class="col-xs-5">
                                    Название
                                </th>
                                <th class="col-xs-2">
                                    Количество
                                </th>
                                <th class="col-xs-2">
                                    Цена 1 шт.
                                </th>
                                <th class="col-xs-1">
                                    Удалить
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(old('items', []) as $key => $item)
                                <tr>
                                    {!! Form::hidden('items[' . $key . '][itemable_id]', $item['itemable_id'], array('class' => 'id')) !!}
                                    {!! Form::hidden('items[' . $key . '][itemable_type]', $item['itemable_type'], array('class' => 'type')) !!}
                                    <td data-name="id">
                                        {!! Form::text('items[' . $key . '][id]', $item['id'], array('class' => 'form-control input-sm', 'readonly' => true)) !!}

                                    </td>
                                    <td data-name="name">
                                        {!! Form::text('items[' . $key . '][name]', $item['name'], array('class' => 'form-control input-sm', 'readonly' => true)) !!}
                                    </td>
                                    <td data-name="count">
                                        {!! Form::text('items[' . $key . '][count]', $item['count'], array('class' => 'form-control input-sm')) !!}
                                    </td>
                                    <td data-name="price">
                                        {!! Form::text('items[' . $key . '][price]', $item['price'], array('class' => 'form-control input-sm')) !!}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-flat btn-warning">
                                            Удалить
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                                <tr class="duplicate">
                                    {!! Form::hidden(null, null, array('data-name' => 'items[replaceme][itemable_id]', 'class' => 'id')) !!}
                                    {!! Form::hidden(null, null, array('data-name' => 'items[replaceme][itemable_type]', 'class' => 'type')) !!}
                                    <td data-name="id">
                                        {!! Form::text(null, null, array('class' => 'form-control input-sm', 'data-name' => 'items[replaceme][id]', 'readonly' => true)) !!}

                                    </td>
                                    <td data-name="name">
                                        {!! Form::text(null, null, array('class' => 'form-control input-sm', 'data-name' => 'items[replaceme][name]', 'readonly' => true)) !!}
                                    </td>
                                    <td data-name="count">
                                        {!! Form::text(null, null, array('class' => 'form-control input-sm', 'data-name' => 'items[replaceme][count]')) !!}
                                    </td>
                                    <td data-name="price">
                                        {!! Form::text(null, null, array('class' => 'form-control input-sm', 'data-name' => 'items[replaceme][price]')) !!}
                                    </td>
                                    <td>
                                        <a class="btn btn-sm btn-flat btn-warning">
                                            Удалить
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                            <h4 class="col-xs-12 text-right total-price">
                            <b>
                                Итого:
                            </b>
                            <span>
                                0
                            </span>
                            </h4>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('order.partials._buttons')

<div class="modal fade user-find-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Результаты поиска</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-body table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade product-find-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Результаты поиска</h4>
            </div>
            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-body table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {

       refreshTotalPrice();

    });

    $('#find-user-for-order').find('a').on("click", function (e) {

        var $block = $(this).closest('#find-user-for-order'),
            $input = $block.find('input'),
            value = $input.val(),
            $modal = $('.user-find-modal');

        if (value.length) {

            $input.css('border-color', '');

            $.ajax({

                url: '/admin/users/find',
                type: 'GET',
                data: {
                    query: value
                }

            }).done(function(response) {

                if(response.status == "success") {

                    $modal.find('div.table-responsive').html(response.html);

                } else {

                    $modal.find('div.table-responsive').html('');

                }

            });

            $('.user-find-modal').modal();

        } else {

            $input.css('border-color', 'red');

        }

        return e.preventDefault();
    });

    $('.user-find-modal').on("click", "a.add-user", function(e) {

        var user_id = $(this).attr("data-user_id"),
            name = $(this).attr("data-name"),
            discount = $(this).attr("data-discount"),
            real_name = $(this).attr("data-real_name"),
            phone = $(this).attr("data-phone"),
            $user = $('div#user_id'),
            $discount = $('input#order-discount'),
            $find_block = $('#find-user-for-order'),

            $recipient_name = $('input#recipient_name'),
            $recipient_phone = $('input#recipient_phone');

        $user.find('input[name="user_id"]').val(user_id);

        $user.find('input[name!="user_id"]').val(name);

        $discount.val(discount);

        $find_block.find('input').val('');

        if(!$recipient_phone.val().length) {

            $recipient_phone.val(phone);

        }

        if(!$recipient_name.val().length) {

            $recipient_name.val(real_name);

        }

        $user.show();

        refreshTotalPrice();

    });

    $('div#user_id').on('click', 'a.btn-warning', function(e) {

        var $user = $(this).closest('div#user_id'),
            $discount = $('input#order-discount');

        $user.find('input').val('');

        $discount.val('0');

        $user.hide();

        refreshTotalPrice();

        return e.preventDefault();

    });

    //products

    $('#find-product-for-order').find('a').on("click", function (e) {

        var $block = $(this).closest('#find-product-for-order'),
            $input = $block.find('input'),
            value = $input.val(),
            $modal = $('.product-find-modal');

        if (value.length) {

            $input.css('border-color', '');

            $.ajax({

                url: '/admin/products/find',
                type: 'GET',
                data: {
                    query: value
                }

            }).done(function(response) {

                if(response.status == "success") {

                    $modal.find('div.table-responsive').html(response.html);

                } else {

                    $modal.find('div.table-responsive').html('');

                }

            });

            $modal.modal();

        } else {

            $input.css('border-color', 'red');

        }

        return e.preventDefault();
    });


    $('.product-find-modal').on("click", "a.add-product", function(e) {

        var product_id = $(this).attr("data-id"),
            type = $(this).attr('data-type'),
            name = $(this).attr("data-name"),
            count = $(this).closest('tr').find('input.product-count').val(),
            show_id = $(this).attr('data-show_id_type'),
            price = $(this).attr('data-price'),
            $table = $('table.products-table'),
            $row = $table.find('tr.duplicate').clone(true),
            replaceWith = $table.find('tr').not('.duplicate').length + 1;

        count = count === "" || count === 0 ? 1 : count;

        $isset_row = $(document).find('tr[data-item_id="' + product_id  + '"]');

        if($isset_row.length) {

            var $input = $isset_row.find('td[data-name="count"]').find('input'),
                isset_count = parseInt($input.val());

            isset_count++;

            $input.val(isset_count);

        } else {

            $row.removeClass('duplicate');

            $row[0].innerHTML = $row[0].innerHTML.replace(/replaceme/g, replaceWith);

            $row.attr('data-item_id', product_id);

            $row.find('input').each(function() {

                $(this).attr('name', $(this).attr('data-name'));

            });

            $row.find('td[data-name="name"]').find('input').val(name);

            $row.find('td[data-name="id"]').find('input').val(show_id);

            $row.find('td[data-name="price"]').find('input').val(price);

            $row.find('td[data-name="count"]').find('input').val(count);

            $row.find('input.id').val(product_id);

            $row.find('input.type').val(type);

            $table.find('tbody').append($row);

        }

        var length = $table.find('tr').not('.duplicate').length;

        if(length > 0) {

            $table.show();

        }

        refreshTotalPrice();

    });

    $('table.products-table').on('click', 'tr a.btn-warning', function(e) {

        var $row = $(this).closest('tr');

        $row.remove();

        var count = $(this).closest('tbody').find('tr').not('.duplicate').length;

        if(count === 0) {

            $(this).closest('table').hide();

        }

        refreshTotalPrice();

        return e.preventDefault();

    });

    function refreshTotalPrice() {

        var $table = $('table.products-table'),
            $span = $('h4.total-price span'),
            total = 0,
            delivery = $('input#delivery_price').val(),
            discount = $('input#order-discount').val(),
            individual_price = $('input[name="individual[price]"]').val();

        if(!individual_price.length) {

            individual_price = 0;

        }

        if(!delivery.length) {

            delivery = 0;

        }

        if(!discount.length) {

            discount = 0;

        }


        $table.find('tr').not('.duplicate').each(function() {

            var price = $(this).find('td[data-name="price"]').find('input').val(),
                count = $(this).find('td[data-name="count"]').find('input').val();

            if(price !== undefined && count !== undefined) {

                total += parseFloat(price) * parseInt(count);

            }

        });

        total += parseFloat(individual_price);

        total = parseFloat(total);

        if(total > 0 && discount > 0) {

            total = total - (total * (discount / 100));

        }

        total += parseFloat(delivery);

        $span.html(total);

    }

    $('table.products-table').on('change', 'td[data-name="count"] > input, td[data-name="price"] > input', function() {

        var val = $(this).val();

        if(!val.length) {

            $(this).val(1);

        }

        refreshTotalPrice();

    });

    $('input#delivery_price, input#order-discount, input[name="individual[price]"]').on("change", function() {

       refreshTotalPrice();

    });


</script>
