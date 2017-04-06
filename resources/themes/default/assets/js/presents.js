var presents = {};

presents.real_reload = function(category) {


    var $gifts = $(document).find('.gifts'),

    $wrapper = $gifts.find('.gifts-wrapper'),
    $filters_block = $gifts.find('.filters-block'),
    $selected_category = $gifts.find('.gifts-list[data-active="true"]'),
    page = $selected_category.find('a.show-more').attr('data-page');

    var filterData = {};

    $filters_block.find('select').each(function(){

        var name = $(this).attr('name');
        var value = $(this).val();

        if(value !== "") {

            filterData[name] = value;

        }

    });

    $.ajax({
        url: '/presents-reload',
        type: 'GET',
        data: {
            category: category,
            page: page,
            filters: filterData
        },
        beforeSend: function(){
            $wrapper.attr('data-loaded', 'true');
        }
    }).done(function(response) {

        $selected_category.replaceWith(response.html);

        $wrapper.attr('data-loaded', 'false');

    }).error(function(){

        $wrapper.attr('data-loaded', 'false');

    });

};

/*presents.not_found = function() {

    var $list = $('.gifts').find('div.gifts-list');

    var visibles = $list.find('[data-category][data-active="true"]').length;

    var $message = $('.gifts-wrapper').find('.text-danger');

    var $count_message = $('.gifts-wrapper').find('.text-muted');

    if(visibles < 1) {

        $message.attr('data-active', 'true');

        $count_message.attr('data-active', 'false');

    } else {

        $message.attr('data-active', 'false');

        $count_message.attr('data-active', 'true');

        $count_message.find('span').html(visibles);

    }

};

presents.more_button = function(category) {

    var $list = $('.gifts').find('div.gifts-list');

    var $show_more_button = $('.gifts a.show-more');

    var visibles = 0;

    if(category === null) {

        visibles = $list.find('[data-category][data-active="false"]').length;

    } else {

        visibles = $list.find('[data-category="' + category + '"][data-active="false"]').length;
    }

    if(visibles > 0) {

        $show_more_button.attr('data-active', 'true');

        $show_more_button.html('Показать еще');

    } else {

        $show_more_button.attr('data-active', 'false');

    }

};

presents.show_more = function() {

    var category = $('.gifts').find('.selector-list li[data-active="true"]').data("category");

    category = category === undefined ? null : category;

    presents.show(category, true);

};

$(document).find(".gifts a.show-more").on("click", function () {

    presents.show_more();

});
*/

presents.check_filters_on_load = function() {

    var filters = window.location.hash;

    if(filters !== "") {

        filters = filters.substr(1);

        filters = filters.split('=');

        if(filters[1] === "asc" || filters[1] === "desc") {

            $('.gifts').find('.filters-block').find('select[name="' + filters[0] + '"]').val(filters[1]).change();

        }

    }

};

presents.filter = function() {

    var $gifts_wrapper = $('.gifts'),
        $filters = $gifts_wrapper.find('.filters-block');

    $filters.find('select').on('change', function(e) {

        var category = $gifts_wrapper.find('.selector-list').find('li[data-active="true"]').data("category");

        var sort_by = $(this).attr('name');

        $filters.find('select[name!="' + sort_by + '"]').find(':selected').removeAttr('selected');

        var by = $(this).val();

        if(by !== "") {

            window.location.hash = sort_by + "=" + by;

        } else {

            window.location.hash = "no";

        }

        presents.real_reload(category);

        return e.preventDefault();

    });

};

presents.show = function(category) {
    var $gifts = $('.gifts'),
    $wrapper = $gifts.find('.gifts-wrapper');

    var anti_selector = category === null ? null : 'div.gifts-list[data-category!="' + category + '"][data-active="true"]' ;

    if(anti_selector !== null) {

        $wrapper.find(anti_selector).attr('data-active', 'false');

    }

    var selector = category === null ? null : 'div.gifts-list[data-category="' + category + '"][data-active="false"]';

    if(selector !== null) {

        $wrapper.find(selector).attr('data-active', 'true');

    }

};

presents.show_more = function() {

    var category = $('.gifts').find('.selector-list li[data-active="true"]').data("category");

    if(category !== undefined && category !== null) {

        presents.real_reload(category);

    }

};

$(document).ready(function(){

    if($('.gifts').length) {

        presents.filter();

        $('.gifts').find('.selector-list li[data-category]').on('click', function (e) {

            var $this = $(this),
                state = $(this).attr("data-active"),
                category = $(this).attr("data-category"),
                $wrapper = $('.gifts').find('.gifts-wrapper'),
                $list = $('.gifts').find('.selector-list li[data-active="true"]');

            if (state === "false") {

                $list.each(function () {

                    $(this).attr('data-active', 'false');

                });

                $this.attr('data-active', 'true');


            }

            $wrapper.attr('data-loaded', 'true');

            var timer = setTimeout(function() {

                presents.show(category);

                $wrapper.attr('data-loaded', 'false');

                clearTimeout(timer);

            }, 300);

        });

        $(document).find('.gifts').on("click", '.gifts-list[data-active="true"] .show-more[data-page]', function () {

            var type = $(this).data('type');

            if(type === "less") {

                $(this).attr('data-page', parseInt($(this).data('page'), 10) - 1);

            }

            if(type === "more") {

                $(this).attr('data-page', parseInt($(this).data('page'), 10) + 1);

            }

            presents.show_more();

        });

        presents.check_filters_on_load();

    }

});