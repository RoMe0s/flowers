
$(document).find('div.additional-inputs').find('a.collapse-button').on("click", function(e) {

    var $additional = $(this).closest('div.additional-inputs'),
        $i = $(this).find('i'),
        status = $additional.attr('data-active'),
        new_status = status === "false" ? "true" : "false";

    if($i.hasClass("fa-caret-right")) {

        $i.removeClass("fa-caret-right").addClass("fa-caret-down");

        $additional.find("input").each(function() {

            $(this).attr("name", $(this).attr("data-name"));

        });

    } else {

        $i.removeClass("fa-caret-down").addClass("fa-caret-right");

        $additional.find("input").removeAttr("required").removeAttr("name");

    }

    $additional.attr("data-active", new_status);

    return e.preventDefault();
});

$(document).find('div.after-register-popup').find('div.close-btn').on("click", "a", function() {

    var $additional = $(this).closest('div.after-register-popup');

    $additional.fadeOut("slow");

});

$("div.password-reset-popup form").find("input").on("click", function() {

    $(this).css("border-color", "");

});

$("div.password-reset-popup form").find("input").on("keyup", function (e) {

    var val = $(this).val(),
        name = $(this).attr("name"),
        key = parseInt(name.match(/\d/g)) + 1,
        selector = "input[name='" + name.replace(/\d/g, key) + "']",
        $form = $(this.closest("form")),
        $next = $form.find(selector);

    if(val.length) {

            var $empty = $form.find("input").filter(function() { return $(this).val() === ""; });

            if($empty.length) {

                $empty.first().focus();

            } else {

                $.ajax({
                    url: $form.attr('action'),
                    method: $form.attr('method'),
                    data: $form.serialize()
                }).done(function (response) {

                    if (response.status === "success") {

                        window.location.href = response.redirect;

                    } else {

                        $form.find("input").css("border-color", "red");

                    }

                });

            }

    }

    return e.preventDefault();

});
