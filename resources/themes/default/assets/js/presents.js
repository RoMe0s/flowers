var presents = {};

presents.not_found = function() {

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

presents.show = function(category, more) {

    var $list = $('.gifts').find('div.gifts-list');

    $list.attr('data-loaded', 'true');

    var timer = setTimeout(function(){

        var anti_selector = category === null ? null : '[data-category!="' + category + '"][data-active="true"]' ;

        if(anti_selector !== null) {

            $list.find(anti_selector).attr('data-active', 'false');

        }

        var selector = category === null ? '[data-category][data-active="false"]' : '[data-category="' + category + '"][data-active="false"]';

        if(more === undefined || more === null || !more) {

            $list.find(selector).attr('data-active', 'true');

        } else {

            var visible_counter = 0;

            $list.find(selector).each(function(){

                if(visible_counter > 8) {

                    return;

                }

                $(this).attr('data-active', 'true');

                visible_counter++;

            });

        }

        presents.not_found();

        presents.more_button(category);

        $list.attr('data-loaded', 'false');

        clearTimeout(timer);

    }, 300);

};

presents.filter = function() {

    var $gifts_wrapper = $('.gifts'),
        $filters = $gifts_wrapper.find('.filters-block'),
        $list_div = $gifts_wrapper.find('.gifts-list'),
        $list = $list_div.find('[data-category]'),
        $show_more_button = $gifts_wrapper.find('a.show-more').closest('div');

    $filters.find('select').on('change', function(e) {

        var sort_by = $(this).attr('name');

        $filters.find('select[name!="' + sort_by + '"]').find(':selected').removeAttr('selected');

        var by = $(this).val();

        if(by !== "") {

            window.location.hash = sort_by + "=" + by;

            $list_div.attr('data-loaded', 'true');

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

                $list_div.attr('data-loaded', 'false');

                clearTimeout(timer);

            }, 300);

        } else {

            window.location.hash = "no";

        }

        return e.preventDefault();

    });

};

presents.show_more = function() {

    var category = $('.gifts').find('.selector-list li[data-active="true"]').data("category");

    category = category === undefined ? null : category;

    presents.show(category, true);

};

$(document).find(".gifts a.show-more").on("click", function () {

    presents.show_more();

});


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

$(document).ready(function(){

    if($('.gifts').length) {

        presents.filter();

        $('.gifts').find('.selector-list li[data-category]').on('click', function (e) {

            var $this = $(this),
                state = $(this).attr("data-active"),
                category = $(this).attr("data-category"),
                $list = $('.gifts').find('.selector-list li[data-active="true"]');

            if (state === "false") {

                $list.each(function () {

                    $(this).attr('data-active', 'false');

                });

                $this.attr('data-active', 'true');


            } else {

                $this.attr('data-active', 'false');

                category = null;

            }

            presents.show(category);

        });

        presents.check_filters_on_load();

    }

});