$('button[data-input]').click(function (e) {

    var input_name = $(this).attr('data-input'),
        value = $(this).attr('data-value'),
        $input = $('input[name="' + input_name + '"]'),
        block_name = $(this).attr('data-block'),
        block_action = $(this).attr('data-block-action'),
        parent_selector = $(this).attr('data-parent'),
        $parent = $(this).closest(parent_selector),
        $block = null,
        change_required_selector = $(this).attr('data-change-required'),
        $change_required = $("div#order-make").find(change_required_selector),
        except_selector = $(this).attr('data-except');

    if (block_name !== undefined && block_name !== null && block_name.length) {

        $block = $(block_name);

    }

    $parent.find('button[data-input]').each(function() {
    
        $(this).removeClass('selected');

        var tmp_parent_selector = $(this).attr('data-parent'),
            tmp_input_name = $(this).attr('data-input');

        if(tmp_parent_selector !== undefined && tmp_parent_selector.length && tmp_input_name !== undefined && tmp_input_name.length) {

            $(this).closest(tmp_parent_selector).find('input[name="' + tmp_input_name + '"]').val('');

        }
    
    });

    if(input_name.length) {

        $(this).addClass('selected');

    }

    if($block && block_action == 'both') {

        if($block.is(":visible")) {

            block_action = 'hide';

        } else {

            block_action = 'show';

        }

    }

    $change_required.each(function() {

        if ($(this)[0].hasAttribute('name')) {

            $(this).removeAttr('required');

        } else {

            $(this).attr('required', 'required');

        }

    });

    if ($block && block_action == 'show') {

        $block.fadeIn('fast', function () {

            $block.find("[data-name]").each(function() {

                if (except_selector && $(this)[0].hasAttribute('data-except')) {

                    if($(this).attr('data-except') != except_selector) {

                        $(this).attr("name", $(this).attr("data-name"));

                        if($(this)[0].hasAttribute("data-required")) {

                            $(this).attr("required", "required");

                        }

                    }

                } else {

                    $(this).attr("name", $(this).attr("data-name"));

                    if($(this)[0].hasAttribute("data-required")) {

                        $(this).attr("required", "required");

                    }

                }
            
            });
        });

    } else if ($block) {

        $block.find("[data-price]:checked").each(function() {
        
            $(this).prop("checked", false).change();
        
        });

        $block.fadeOut('fast', function () {

            $block.find("[data-name]").each(function() {

                if (except_selector && $(this)[0].hasAttribute('data-except')) {

                    if ($(this).attr('data-except') != except_selector) {

                        $(this).removeAttr("name");

                        if($(this)[0].hasAttribute("required")) {

                            $(this).removeAttr("required");

                        }

                    }

                } else {
            
                    $(this).removeAttr("name");

                    if($(this)[0].hasAttribute("required")) {

                        $(this).removeAttr("required");

                    }

                }
            
            });

        });

    }

    $input.val(value).change();

});

$("[data-toggle='popover']").popover();

$("div#order-make").on("change", "[data-price]", function(e) {

    var used = parseInt($(this).attr("data-used")),
        value = $(this).is(":checked"),
        change = parseInt($(this).attr('data-price')),
        $prices = $("div#order-make").find("span.price-string"),
        price = parseInt($prices.html().replace(/\D/g, "")),
        $parent = $(this).closest(".parent-for-inputs"),
        $this = $(this);

    if($parent.length) {
    
        $parent.find("[type='checkbox']:checked").each(function() {

            var tmp_used = parseInt($(this).attr("data-used")),
                tmp_change = parseInt($(this).attr("data-price"));

            if(tmp_change != change) {
                
                $(this).prop("checked", false);

                if(tmp_used != 0) {
                
                    $(this).attr("data-used", "0");

                    price -= tmp_change;
                
                }

            }

        });
    
    }

    if (value && used == 0) {

        $this.attr("data-used", "1");

        price += change;

    } else if (!value && used != 0) {

        $this.attr("data-used", "0");

        price -= change;

    }

    $prices.html(price + " руб.");

});

$("div#order-make").on("change", "select[name='time']", function(e) {

    var $order_make = $("div#order-make"),
        $night_select = $order_make.find("input[data-name='night']"),
        $night_select_block = $night_select.closest('div.checkbox'),
        $accuracy_select = $order_make.find("input[data-name='accuracy']"),
        $accuracy_select_block = $accuracy_select.closest("div.checkbox"),
        $prices = $order_make.find("span.price-string"),
        price = parseInt($prices.html().replace(/\D/, ""));

    if($(this).val() == '5') {

        $accuracy_select_block.fadeOut('fast', function() {

            $accuracy_select.removeAttr('name');

            if($accuracy_select.attr("data-used") == "1") {

                price -= parseInt($accuracy_select.attr("data-price"));

                $accuracy_select.attr("data-used", "0");

                $prices.html(price + " руб.");

            }

            $accuracy_select.prop('checked', false);

            $night_select.attr('name', $night_select.attr('data-name')).prop("checked", true).change();

            $night_select_block.fadeIn('fast');

        });

    } else {

        $night_select_block.fadeOut('fast', function() {

            $accuracy_select_block.fadeIn('fast');

            $accuracy_select.attr('name', $accuracy_select.attr('data-name'));

        });

        $night_select.removeAttr('name');

        if($night_select.attr("data-used") == "1") {

            price -= parseInt($night_select.attr("data-price"));

            $night_select.attr("data-used", "0");

            $prices.html(price + " руб.");

        }

        $night_select.prop('checked', false);

    }

});

$("div#order-make").on("click", "[data-next-step]", function () {

    $(".has-error").removeClass("has-error");

    var $tab = $(this).closest("div.tab-pane"),
        id = $tab.attr('id'),
        $order_make = $('#order-make'),
        $href = $order_make.find('a[href="#' + id + '"]').closest('li'),
        next = $(this).attr('data-next-step'),
        $next_href = $order_make.find('a[href="' + next + '"]'),
        has_error = false,
        $collapse = $(document).find("div#order-collapse");

    $tab.find("[name][required]").each(function () {

        if (!$(this).val().length || ($(this).is(":checkbox") && !$(this).is(":checked"))) {

            has_error = true;

            var $button = $tab.find("[data-input='" + $(this).attr('name') + "']");

            if(!$(this).is(":checkbox")) {

                $(this).addClass('has-error');

            } else {
            
                $(this).closest("label").addClass('has-error');
            
            }

            if($button.length) {

                $button.addClass('has-error');

            }

        }

    });

    if(!has_error) {

        $href.addClass('done');

        $next_href.tab('show');

    } else {

        $href.removeClass('done');

    }

    $collapse.collapse('hide');

});


$("div#order-make").on("change", "input#specify_field", function() {

    var $order_make = $("div#order-make"),
        $address_field = $order_make.find("input#address_field"),
        $courier_block = $order_make.find("button[data-block='#is-courier-delivery'][data-block-action='show']");

    if($(this).val().length) {

        $address_field.val('').removeAttr('required').change();

    } else {

        $address_field.attr('required', 'required');

    }

});

$("div#order-make").on("change", "input#address_field", function() {

    var $specify_field = $("div#order-make").find("input#specify_field");

    if($(this).val().length) {

        $specify_field.val('').removeAttr('required').change();

    } else {

        $specify_field.attr('required', 'required');

    }

});

$("div#order-make").on("submit", "form", function(e) {

    $(".has-error").removeClass("has-error");

    var validated = true,
        $fields = $(this).find("[name][required]");

    $fields.each(function() {

        if(!$(this).val().length) {

            validated = false;

        }

    });

    if(!validated) {

        return e.preventDefault();

    } else {
    
        yaCounter29938144.reachGoal('addOrder'); 
    
    }



});

$("div#order-make").on("click", "[type='submit']", function(e) {

    $(".has-error").removeClass("has-error");

    var $order_make = $("div#order-make"),
        $nav = $order_make.find("ul.nav"),
        $links = $nav.find("li:not(:last-child)"),
        validated = true;

    $links.each(function() {

        if(!$(this).hasClass('done')) {

            var $href = $(this).find('a');

            $order_make.find( $href.attr("href") ).find("[data-next-step]").addClass("has-error");

            $href.tab('show');

            validated = false;

            return false;

        }

    });

    if(!validated) {

        return e.preventDefault();

    }

    $order_make.find('form').submit();

});

$("div#order-make").on("click", "ul.nav li a[role='tab']", function(e) {

   $(this).closest('li').removeClass('done');

    var $collapse = $(document).find("div#order-collapse");

    $collapse.collapse('hide');

});

$(document).on("click", "a[href='#order-collapse']", function(e) {

    var $collapse = $(document).find("div#order-collapse"),
        $form = $(this).closest("form");

    if(!$collapse.is(":visible")) {

        $.ajax({
            type: "GET",
            url: "/cart/order/preview",
            data: $form.serialize()
        }).done(function(response) {

            if(response.html !== undefined &&
                response.html !== null &&
                response.html.length) {

                $collapse.html(response.html).collapse('show');

            }

        });

    } else {

        $collapse.collapse('hide');

    }

    e.preventDefault();

});

$("div#order-make").on("click", "[data-username], [data-userphone]", function(e) {

    var $order_make = $(this).closest("#order-make"),
        $name_input = $order_make.find("input[name='recipient_name']"),
        $phone_input = $order_make.find("input[name='recipient_phone']"),
        name = $(this).attr('data-username'),
        phone = $(this).attr('data-userphone');

    $name_input.val(name);

    $phone_input.val(phone);

});
