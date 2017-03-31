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

            Alert.success(data.msg);
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

            Alert.success(data.msg);
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

            Alert.success(data.msg);
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

            Alert.success(data.msg);
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
                .attr('href', '/cart')
                .text('Оформить заказ');
        }

        $('<div>')
            .addClass('notify ' + css_class)
            .html(message + ' ' + link[0].outerHTML)
            .fadeIn(300)
            .delay(10000)
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
    $('select[multiple]').select2();

    $('.slick').slick({
        autoplay: true,
        autoplaySpeed: 3000,
        nextArrow: '<button><i class="fa fa-arrow-right"></i></button>',
        prevArrow: '<button><i class="fa fa-arrow-left"></i></button>',
        arrows: false,
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
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 1072,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }
        ]
    });
});