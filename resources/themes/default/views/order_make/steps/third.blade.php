<div role="tabpanel" class="tab-pane fade" id="third">
    <h3 class="col-xs-12 col-sm-8 col-sm-offset-2 text-center">
        После подтверждения заказа модератором Вам придёт сообщение с ссылкой об оплате
    </h3>
        @include('order_make.partials.result')
    <div class="form-group col-xs-12 text-center">
        <input type="checkbox" name="agreement" required> Я ознакомился(-лась) и согласен(-сна) с <a
                href="{{ url('/offer') }}" target="_blank">условиями Публичной ОФЕРТЫ</a>.
    </div>
    <div class="form-group col-xs-12 col-sm-6 col-sm-offset-3">
        <button type="submit" class="btn btn-success btn-sm form-control">
            Оформить
        </button>
    </div>
</div>
