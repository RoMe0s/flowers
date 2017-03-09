$(document).on 'click', '.add_to_basket', (e)->
  _this = $(this)
  $.ajax(
    url: '/admin/basket/add'
    type: 'GET'
    data: {
      id: _this.data('id')
      type: _this.data('type')
    }
  ).done((response)->
    $('.basket-count').html(response.count)
    message.show(response.message, response.status)
  ).error(() ->
    message.show(window.lang_error, 'error')
  )

$(document).on 'click', '.remove_from_basket', (e)->
  _this = $(this)
  $.ajax(
    url: '/admin/basket/remove'
    type: 'GET'
    data: {
      id: _this.data('id')
      type: _this.data('type')
    }
  ).done((response)->
    $('.basket-count').html(response.count)
    $('.basket-items-list').html(response.html)
    message.show(response.message, response.status)
  ).error(() ->
    message.show(window.lang_error, 'error')
  )


$(document).on 'click', '.add-to-order-from-basket', (e)->
  _select = $('.basket-basket-items select')
  if parseInt(_select.val()) > 0
    _id = _select.val()
    $.ajax({
      url: '/admin/basket/add/order'
      type: 'GET'
      data: {
        id: _id
      }
    }).done((response) ->
      $('.basket-count').html(0)
      $('.basket-items-list').html(response.html)
      message.show(response.message, response.status)

    ).error(() ->
      message.show(window.lang_error, 'error')
    )