var presents = {};

presents.init_autoloader = "more";

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

        if(category === undefined) {

            category = $gifts_wrapper.find('.gifts-list[data-active="true"]').data('category');

            if(category === undefined || category === null) {

                return false;

            }

        }

        var sort_by = $(this).attr('name');

        $filters.find('select[name!="' + sort_by + '"]').find(':selected').removeAttr('selected');

        var by = $(this).val();

        if(by !== "") {

            window.location.hash = sort_by + "=" + by;

        } else {

            window.location.hash = "no";

        }

        if(category !== "init") {

            presents.real_reload(category);

        } else {

            presents.sort_initial(sort_by, by);

        }

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

presents.sort_initial = function(sort_by, by) {

    var $gifts = $(document).find('.gifts'),
    $wrapper = $gifts.find('.gifts-wrapper'),
    $items_wrapper = $wrapper.find('.gifts-list[data-category="init"]'),
    $list = $items_wrapper.find('div.item'),
    $show_more_button = $items_wrapper.find('a.show-more').closest('div');

    if(by === "" || sort_by === "") {

        by = "asc";

        sort_by = "position";

    }

    if(by !== "") {

        var timer = setTimeout(function() {

            $list.sort(function (a, b) {

                var an = $(a).data(sort_by),
                    bn = $(b).data(sort_by);

                if (by === 'asc') {

                    return +an - +bn;

                }

                if (by === 'desc') {

                    return +bn - +an;

                }

            });

            $list.detach().insertBefore($show_more_button);

            $wrapper.attr('data-loaded', 'false');

            clearTimeout(timer);

        }, 300);

    }

};

presents.show_more_initial = function() {

    var $gifts = $(document).find('.gifts'),
        $wrapper = $gifts.find('.gifts-wrapper'),
        $initial_wrapper = $gifts.find('.gifts-list[data-category="init"]');

    $initial_wrapper.on("click", "a.show-more", function (e) {

        $this = $(this);

        $wrapper.attr("data-loaded", "true");

        var $visibles = $initial_wrapper.find('div.item[data-active="false"]');

        if($visibles.length > 0 && presents.init_autoloader === "more") {

            var counter = 1;

            $visibles.each(function(){

                if(counter > 9) return;

                $(this).attr("data-active", "true");

                counter++;

            });

            $visibles = $initial_wrapper.find('div.item[data-active="false"]');

            if($visibles.length <= 0) {

                presents.init_autoloader = "less";

                $this.html("Скрыть");

            }

        } else {

            $visibles = $($initial_wrapper.find('div.item[data-active="true"]').get().reverse());

            var counter = 1;

            var max_counter = 9;

            if($visibles.length - max_counter < 9) {

                max_counter = $visibles.length - 9;

            }

            $visibles.each(function(){

                if(counter > max_counter) return;

                $(this).attr("data-active", "false");

                counter++;

            });

            $visibles = $initial_wrapper.find('div.item[data-active="true"]');

            if($visibles.length <= 9) {

                presents.init_autoloader = "more";

                $this.html("Показать еще");

            }


        }


        $visibles = $initial_wrapper.find('div.item[data-active="true"]');

        $this.find('span').html($visibles.length);

        $wrapper.attr("data-loaded", "false");

        return e.preventDefault();

    });

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

        presents.show_more_initial();

    }

});