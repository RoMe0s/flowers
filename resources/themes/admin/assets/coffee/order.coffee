hidePassworField = (hide)->
  _field = $(document).find("input#password")
  _div = _field.closest('.form-group')
  if hide == true
    _div.hide()
    _div.removeClass('required')
    _field.removeProp('required')
  else if hide == false
    _field.prop('required', 'required')
    _div.addClass('required')
    _div.show()

$(document).ready ()->
  _this = $('select.admin-order-user')
  if _this.val() > 0
    hidePassworField(true)
  else
    hidePassworField(false)  

$(document).on 'change', 'select.admin-order-user', (e)->
  _this = $(this)
  _form = _this.closest('form')
  _name = _form.find("input[name='recipient_name']")
  _email = _form.find("input[name='email']")
  _phone = _form.find("input[name='recipient_phone']")
  _name.val('')
  _email.val('')
  _form.find("input[name='password']").val('')
  _phone.val('')
  if _this.val() > 0
    _selected = _this.find(':selected')
    _name.val(_selected.data('name'))
    _phone.val(_selected.data('phone'))
    _email.val(_selected.data('email'))
    hidePassworField(true)
  else
    hidePassworField(false)
  return



$(document).on 'change', '.admin-order-address', (e)->
  _this = $(this)
  _form = _this.closest('form')
  _address = _form.find("textarea[name='address']")
  _code = _form.find("input[name='code']")
  _address.val('')
  _code.val('')
  if _this.val() > 0
    _selected = _this.find(':selected')
    _address.val(_selected.data('address'))
    _code.val(_selected.data('code'))

$(document).on 'click', '.add-basket-item-to-order', (e)->
  _select = $('.order-basket-items select')
  if _select.val() > 0
    _selected = _select.find(':selected')
    _id = _select.val()
    $.ajax({
      url: '/admin/order/items/add'
      type: 'GET'
      data: {
        count: _selected.data('count')
        itemable_id: _selected.data('itemable_id')
        itemable_type: _selected.data('itemable_type')
        price: _selected.data('price')
        discount: _selected.data('discount')
        id: _id
      }
    }).done((response) ->
      _selected.remove()
      message.show(response.message, response.status)
      $('.basket-count').html(response.count)
      if response.update != false
        $('tr.row-id-' + response.update).remove()
      $('table.duplication tbody').append(response.html)

    ).error(() ->
      message.show(window.lang_error, 'error')
    )

$(document).on 'change', '.order-status-changer', (e)->
  _this = $(this)
  _old = _this.data('old_status')
  $row = _this.closest('tr')
  if _this.val() != _old
    $.ajax({
      url: '/admin/order/status/change',
      type: 'GET'
      data: {
        status: _this.val()
        id: _this.data('id')
      }
    }).done((response)->
      $row.removeClass('order-status-' + _old)
      $row.addClass('order-status-' + _this.val())
      message.show(response.message, response.status)
      _this.data('old_status', _this.val())
    ).error(()->
      message.show(window.lang_error, 'error')
    )

