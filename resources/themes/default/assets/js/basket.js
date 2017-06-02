$(document).on("click", "[data-show-basket]", function(e) {

    $(document).find('form#cart-popup-load').submit();

    e.preventDefault();

});

$(document).on("basket-popup-loaded", function(e, response) {

    if(response.html !== undefined &&
    response.html !== null &&
    response.html.length) {

        var $modal = $('div.modal#basket');

        $modal.find('div.modal-content').html(response.html);

        basket_modal_size($modal);

        $modal.modal();

    }

});

$(window).resize(function() {

    var $modal = $("div.modal#basket");

    if($modal.is(":visible")) {

        basket_modal_size($modal);

    }

});

function basket_modal_size($modal) {

    var $footer = $modal.find('div.modal-footer'),
        $header = $modal.find('div.modal-header'),
        $body = $modal.find('div.modal-body'),
        $ul = $body.find('ul.basket-list'),
        height = $(window).height() - $header.outerHeight();

        $body.css('height', height + 'px');

        $ul.css('height', (height - $footer.outerHeight()) + 'px');

}

$(document).on("click", "div.modal#basket form[ajax] button[data-method]", function(e) {

    var $form = $(this).closest("form[ajax]"),
        $method_input = $form.find('[name="method"]'),
        method = $(this).attr('data-method');

    $method_input.val(method);

    $form.submit();

});

$(document).on("basket-item-changed", function(e, response) {

    var $modal = $('div.modal#basket'),
        $basket_total = $modal.find("span#basket-subtotal"),
        $basket_count = $modal.find("b#basket-count-total"),
        $menu_count = $("nav.header-menu").find("span.cart-count"),

        //order
        
        $order = $("div#order-make"),
        $prices = $order.find("span.price-string"),
        $order_collapse = $order.find("div.collapse#order-collapse");

    if(response.total_count !== undefined && response.total_count > 0) {

        var $row = $modal.find("li[data-basket-row-id='" + response.id + "']"),
            $form = $row.find("form[ajax]");

        $basket_total.html(response.total_price + " руб.");

        $basket_count.html("(" + response.total_count + ")");

        $menu_count.html(response.total_count);

        if(response.method === "remove") {

            $row.fadeOut("fast", function() { $row.remove(); });

        } else {

            $form.find('input[name="count"]').val(response.item_count);

            $form.find('span.basket-item-price').html(response.item_price + ' руб.');

        }

        if($order.length) {
        
            $prices.html(response.total_price + " руб.");

            $order_collapse.collapse('hide');

            $order.find("[data-price]").each(function() {

                if($(this).is("select")) {

                    $(this).attr("data-used", "0").change();

                } else {

                    $(this).attr("data-used", "0").val(0).change();

                }

            });
        
        }

    } else {

        if($order.length) {
        
            window.location.href ="/";

            return;
        
        }

        $modal.modal('hide');

        $menu_count.html('0');

    }

});

$("div.modal#basket").on("click", "a[data-show-question]", function(e) {

    var $modal = $("div.modal#basket"),
        $question = $("li[data-question]");

    if($question.length) {

        $modal.find("li:not([data-question])").hide();

        $question.fadeIn("slow");

    }

    return e.preventDefault();

});

$("div.modal#basket").on("click", "a[data-answer]", function(e) {

    var answer = $(this).attr("data-answer"),
        $modal = $("div.modal#basket"),
        $list = $modal.find("ul.basket-list"),
        $element = $list.find('li[data-answer="' + answer + '"]'),
        $hide = $list.find('li[data-answer!="' + answer + '"]'),
        $submit_button = $modal.find("div.modal-footer").find("a[data-show-question]");

    if($element.length) {

        $hide.remove();

        $element.fadeIn("slow");

        window.phone_mask();

        $submit_button.attr("data-submit-answer", answer);

    }

});

$("div.modal#basket").on("submit", "form[answer-form]", function(e) {

    return e.preventDefault();

});

$("div.modal#basket").on("click", "a[data-show-question][data-submit-answer]", function(e) {

   var answer = $(this).attr("data-submit-answer"),
       $form = $("div.modal#basket").find('form[answer-form="' + answer + '"]'),
       $required = $form.find("[name][required]"),
       validated = true;

   $required.each(function() {

       if(!$(this).val().length || ($(this).is(":checkbox") && !$(this).is(':checked')) ) {

           if($(this).is(":checkbox")) {

               $(this).closest("label").addClass("has-error");

           } else {

               $(this).addClass("has-error");

           }

           validated = false;

       }

   });

    if(validated) {

        $form.submit();

    } else {

        e.preventDefault();

    }

});
