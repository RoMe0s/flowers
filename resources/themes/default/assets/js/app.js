var Cart = {
    open: function () {
        var $cart = $('#cart');

        if ($cart.is(':hidden')) {
            $cart.fadeIn(500).find('.cart-body').animate({
                right: 0,
                opacity: 1
            });
        } else {
            $cart.find('.cart-body').animate({
                right: '-400px',
                opacity: 1
            });
            $cart.fadeOut(500);
        }
    },

    addBouquet: function (id, token) {
        $.post('/api/cart/add/bouquet', {
            'id' : id,
            '_token' : token
        }).success(function (data) {
            Cart.updateInfo(data.count);

            // Alert.success(data.msg);
            $("a.mobile-cart[data-show-basket]").click();
        }).error(function (data) {
            Alert.error(data.responseJSON);
        });

        yaCounter29938144.reachGoal('addItem');
    },

    addSet: function (id, token) {
        $.post('/api/cart/add/set', {
            'id' : id,
            '_token' : token
        }).success(function (data) {
            Cart.updateInfo(data.count);

            // Alert.success(data.msg);
            $("a.mobile-cart[data-show-basket]").click();
        }).error(function (data) {
            Alert.error(data.responseJSON);
        });

        yaCounter29938144.reachGoal('addItem');
    },

    addSale: function (id, token) {
        $.post('/api/cart/add/sale', {
            'id' : id,
            '_token' : token
        }).success(function (data) {
            Cart.updateInfo(data.count);

            // Alert.success(data.msg);
            $("a.mobile-cart[data-show-basket]").click();
        }).error(function (data) {
            Alert.error(data.responseJSON);
        });

        yaCounter29938144.reachGoal('addItem');
    },

    addProduct: function (id, token) {
        $.post('/api/cart/add/product', {
            'id' : id,
            '_token' : token
        }).success(function (data) {
            Cart.updateInfo(data.count);

            // Alert.success(data.msg);
            $("a.mobile-cart[data-show-basket]").click();
        }).error(function (data) {
            Alert.error(data.responseJSON);
        });

        yaCounter29938144.reachGoal('addItem');
    },

    updateInfo: function (count) {
        $('nav .cart-count').text(count);
    }
};

var Good = {
    boxShowPopup: function (id, token) {
        $.post('/api/get/box/sets', {
            'id': id,
            '_token': token
        }).success(function (data) {
            var popup = Popup.show();

            $(data).prependTo(popup);
        }).error(function (data) {
            console.log(data);
        });
    }
};

var Popup = {
    show: function () {
        var popup = $('<div>').addClass('popup');

        var section = $('<section>').prependTo(popup);

        $('body').css({overflow:'hidden'}).prepend(popup);

        return section;
    },

    close: function () {
        $('body').css({overflow:'auto'});

        $('div.popup').remove();
    }
};

var Alert = {
    create: function (message, css_class) {

        if ($('#notifications .notify').size() > 0) {
            $('#notifications .notify').first().remove();
        }

        if (css_class == 'notify-success') {
            var link = $('<a>')
                .addClass('btn btn-outline btn-xs')
                .attr('data-show-basket', 'data-show-basket')
                .text('Оформить заказ');
        }

        var content = message;

        if(link != undefined) {
            
            content += link[0].outerHTML;
        
        }

        $('<div>')
            .addClass('notify ' + css_class)
            .html(content)
            .fadeIn(300)
            .delay(5000)
            .fadeOut(300)
            .appendTo($('#notifications'));
    },

    success: function (message) {
        Alert.create(message, 'notify-success');
    },

    error: function (message) {
        Alert.create(message, 'notify-danger');
    }
};

$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('select[multiple], .select2').select2({
        minimumResultsForSearch: 10
    });

    $('.slick').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        nextArrow: '<button data-direction="next"><i class="fa fa-arrow-right"></i></button>',
        prevArrow: '<button data-direction="previous"><i class="fa fa-arrow-left"></i></button>',
        arrows: true,
        dots: true,
        customPaging : function() {
            // return '<i class="fa fa-circle-o" aria-hidden="true">' +
            //         '<span class="check-mark">&#10003;</span>' +
            //     '</i>';
            return '<i class="fa fa-circle-o" aria-hidden="true"></i>';
        },
        infinite: true,
        speed: 300,
        cssEase: 'linear',
        slidesToShow: 4,
        slidesToScroll:4,
        rows: 1,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    arrows: false
                }
            },
            {
                breakpoint: 1072,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    arrows: false
                }
            },
            {
                breakpoint: 1150,
                settings: {
                    arrows: false
                }
            }
        ]
    });


    $('.filters').on("click", "a[data-value]", function(e) {

        e.preventDefault();

        var $this = $(this),
            old_state = $this.attr('data-active'),
            value = $this.data('value'),
            $list_item = $this.closest('li'),
            type = $list_item.data('name'),
            $input = $list_item.find('input'),
            $form = $list_item.closest('form');

        $list_item.find('a').attr('data-active', 'false');

        if(old_state !== undefined) {

            $input.val(value);
            $input.attr('name', type);

        } else {

            $input.val('');
            $input.removeAttr('name');

        }

        $form.submit();

    });

/*    if ( !Modernizr.objectfit ) {
        $('.photo img').each(function(){

            if($(this).attr('src') !== undefined && $(this).attr('src') !== null && $(this).attr('src').length)             {

                $(this).closest('div.photo').css('background-image', "url('" + $(this).attr('src') +"')");

            }

        });
    }*/

});

$(document).on("focus", ".has-error", function(e) {

    $(document).find('.has-error').removeClass('has-error');

});


var toTopButton = {
    $button: $("button.to-top"),
    $scroll: $("html, body"),
    resizeFunction: function() {

        var offset = window.pageYOffset,
            windowh = $(window).height(),
            is_visible = toTopButton.$button.is(":visible"),
            windoww = $(window).width();

        if(windoww >= 768 && !is_visible) return;

        if(windoww < 768 && offset > windowh && offset < (windowh * 2)) {

            var opacity = 1 - ((windowh * 2) - offset) / windowh;

            opacity = opacity.toString().substring(0,4);

            if(!is_visible) {

                toTopButton.$button.show();

            }

            toTopButton.$button.css("opacity", opacity);

        } else if(offset >= (windowh * 2) && windoww < 768) {

            if(!is_visible) {

                toTopButton.$button.show();

            } 

            toTopButton.$button.css("opacity", "1");

        } else if(offset <= windowh || windoww >= 768) {

            if(is_visible) {

                toTopButton.$button.hide();

            }

            toTopButton.$button.css("opacity", "0");

        }

    },
    init: function() {

        $(document).on("scroll", function(e) {

            toTopButton.resizeFunction();

        });

        toTopButton.$button.on("click", function(e) {

            toTopButton.$scroll.stop().animate({scrollTop:0}, 500, 'swing');

        });

        $(document).scroll();

    }
};

toTopButton.init();
