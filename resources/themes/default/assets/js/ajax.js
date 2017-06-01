/**
 * Created by rome0s on 15.05.17.
 */
$(document).on("submit", 'form[ajax]', function(event) {

    var $form = $(this),
        formData = new FormData($form[0]),
        $submit_button = $form.find('[type="submit"]');

    if($submit_button.html() !== undefined && $submit_button.length) {

        $submit_button.attr('disabled', 'disabled');

        var timeout = setTimeout(function() {

            $submit_button.removeAttr('disabled');

            clearTimeout(timeout);

        },1000);

    }

    $.ajax({

        beforeSend: function() {

            if($form.attr('preAjax') !== undefined) {

                $(document).trigger($form.attr('preAjax'), [event]);

            }

        },
        url: $form.attr("action"),
        type: $form.attr("method"),
        data: formData,
        contentType: false,
        processData: false

    }).done(function(response) {

        if(response.status === "success") {

            if(response.redirect !== undefined &&
                response.redirect !== null &&
                response.redirect.length) {

                window.location.href = response.redirect;

            }

            if(response.refresh !== undefined &&
                response.refresh !== null &&
                response.refresh.length) {

                window.location.reload();

            }

        }

        if(response.message !== undefined &&
            response.message !== null &&
            response.message.length) {

            console.log(response.status, response.message);

        }

        if($form.attr('postAjax') !== undefined) {

            $(document).trigger($form.attr('postAjax'), [response]);

        }

    }).fail(function(response) {

        response = response !== undefined ? response.responseJSON : {};
        
        if($form.attr('errorAjax') !== undefined) {

            $(document).trigger($form.attr('errorAjax'), [response]);

        }

        var passed_errors = [];

        for(var field in response) {

            var error = response[field].constructor === Array ? response[field].pop() : response[field];

            if(passed_errors.indexOf(error) === -1) {

                console.log('error', error);

                passed_errors.push(error);

            }

            $form.find('[name="' + field + '"]').addClass('invalid');

        }

        if(!passed_errors.length) {

            console.log('error', 'Произошла ошибка, попробуйте пожалуйста позже');

        }

        passed_errors = [];

    });

    return event.preventDefault();

});

$(document).on("focus", "input.invalid", function() {

    $(this).removeClass('invalid');

});
