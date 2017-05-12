window.initInputMask = () ->
  $(".inputmask-birthday").each () ->
    $(this).inputmask(birthday_format, {'placeholder': birthday_format})

  #mobile phone
  $(".inputmask-2").each () ->
    $(this).inputmask
      mask: "+7 (999) 999-99-99"
      placeholder: ""

  $(".inputmask-3").each () ->
    $(this).inputmask
      mask: '999 999 999 999'
      placeholder: ' '
      numericInput: true
      rightAlign: false


$(document).on "ready", () ->
  initInputMask()