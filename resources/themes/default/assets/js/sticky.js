window.sticky_init = function () {

    var window_width = document.body.clientWidth;

    if(window_width > 976) {

        var $mainpage_menu_content = $(".mainpage_menu .content");

        $('.sticky-wrapper').css('height', $mainpage_menu_content.height() + 'px');

        $('.sidebar').stick_in_parent({offset_top: 50, inner_scrolling: true});

    } else {

        $('.sidebar').trigger("sticky_kit:detach");

        $('.sticky-wrapper').css('height', '');

    }

};

$(window).resize(function () {

    sticky_init();

});

sticky_init();