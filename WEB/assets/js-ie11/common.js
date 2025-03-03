'use strict';

$(document).ready(function () {

    $main_container.on('click', '#findsimilar_email', function () {

        var txt_email = $('#txt_email').val();

        var ajax_type = 'POST';
        var url = BASE_URL + "common/common/find_similar_email";
        var post_params = {
            'txt_email': txt_email
        };

        var success_function = function success_function(responseText) {
            var email_data = $.parseJSON(responseText);
            // $('.show_email').html(responseText);
            var DATA = {
                email_list: email_data
            };

            $('#email_list').html(Mustache.render(EMAIL_LIST_TEMPLATE, DATA));

            $('#email_show').collapse('show');
        };

        ajax_request(ajax_type, url, post_params, success_function);
    });

    $main_container.on('click', '#email_list .rd_email', function () {
        $('#txt_email').val($("input[name='email']:checked").val());
        $('#email_show').collapse('hide');
    });

    $main_container.on('click', '.cls_action', function () {

        var this_el = $(this);

        var action_path = BASE_URL + $(this).data('action-path');
        $main_container.html('').load(cache.set('refresh_path', action_path));

        create_breadcrumbs(this_el);
    });
});

function validateForm() {
    var isValid = true;

    $('.field-required').each(function () {

        if ($(this).is(':radio')) {
            var name = this.name;

            if ($('input[name=' + name + ']:checked').length == 0) {
                isValid = false;
                $('input[name=' + name + ']').closest('div').addClass('has-error');
            } else {
                $('input[name=' + name + ']').closest('div').removeClass('has-error');
            }
        } else {

            if ($.trim($(this).val()) === '' || $(this).val() === null) // added trim to remove whitespaces
                {
                    isValid = false;
                    $('#' + this.id).parent('div').addClass('has-error');
                } else {
                $('#' + this.id).parent('div').removeClass('has-error');
            }
        }
    });

    return isValid;
}

function notify(span_message, type) {
    var is_confirmation = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

    var notif_div = void 0;

    switch (type) {
        case 'success':
            notif_div = cache.get('alert_success');
            break;
        case 'info':
            notif_div = cache.get('alert_info');
            break;
        case 'warning':
            notif_div = cache.get('alert_warning');
            break;
        case 'danger':
            notif_div = cache.get('alert_danger');
            break;
    }

    $div_notifications.html(notif_div);
    $div_notifications.find('span').html(span_message);
    $div_notifications.stop();

    if (is_confirmation === null) {
        $div_notifications.fadeIn("slow").delay(3000).fadeOut('slow');
    } else {
        $div_notifications.fadeIn("slow");
    }
}

$div_notifications.on('click', '#close_alert', function () {
    $div_notifications.stop().fadeOut("slow");
});

function modal_notify(modal, message, type) {
    var is_confirmation = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;

    var $modal_body = void 0;
    var $notif_div = void 0;
    var $modal_alert = void 0;

    // if button is NOT an object (a jquery selector object)
    if (!(modal instanceof Object)) {
        $modal_body = $main_container.find('#' + modal + ' .modal-body');
    } else {
        // jquery selector object. more optimized if programmer knows how to take advantage of jquery selectors and caching
        $modal_body = modal.find('.modal-body');
    }

    switch (type) {
        case 'success':
            $notif_div = cache.get('alert_success');
            break;
        case 'info':
            $notif_div = cache.get('alert_info');
            break;
        case 'warning':
            $notif_div = cache.get('alert_warning');
            break;
        case 'danger':
            $notif_div = cache.get('alert_danger');
            break;
    }

    $notif_div.addClass('hidden_el');
    $notif_div.find('span').html(message);

    $modal_body.find('.alert').remove();
    $modal_body.prepend($notif_div);

    $modal_alert = cache.set('modal_alert', $modal_body.find('.alert'));
    $modal_alert.stop();

    if (is_confirmation === null) {
        $modal_alert.fadeIn("slow").delay(3000).fadeOut('slow');
    } else {
        $modal_alert.fadeIn("slow");
    }
}

$main_container.on('click', '.modal #close_alert', function () {
    cache.get('modal_alert').stop().fadeOut("slow");
});

function disable_enable_frm(frm_id, type) // type = true , false
{
    $('#' + frm_id + ' :input').prop('disabled', type);

    if (type == true) {
        // for clickable span
        $('#' + frm_id + ' span').css("pointer-events", "none");
    } else {
        $('#' + frm_id + ' span').css("pointer-events", "auto");
    }
}

// note: currently SVG loading's height is set to 15px. so other elements aside from button(class="form-control") will display an incorrect size
function loading(el, status) {
    var element = void 0;
    var orig_inner_html = void 0;
    var new_inner_html = void 0;

    // if element is NOT an object (a jquery selector object)
    if (!(el instanceof Object)) {
        element = $main_container.find('#' + el);
        orig_inner_html = el + '_label';
    } else {
        // jquery selector object. more optimized if programmer knows how to take advantage of jquery selectors and caching
        element = el;
        orig_inner_html = element.prop('id') + '_label';
    }

    if (status === 'in_progress') {
        element.addClass('disabled');
        element.prop('disabled', true);
        cache.set(orig_inner_html, element.html());
        new_inner_html = cache.get('loading_ring');
    } else {
        // done
        element.removeClass('disabled');
        element.prop('disabled', false);
        new_inner_html = cache.get(orig_inner_html);
    }

    element.html(new_inner_html);
}

function upload_ajax_modal(form_name, surl) {
    var formData = new FormData(form_name);

    var ajax_type = 'post';
    var url = surl; //BASE_URL + "vendor/registration/upload_file/1";
    var parameters = formData;
    var success_function = function success_function(responseText) {

        // cache.set('wanitindi', responseText);
    };
    var additional_configs = {
        processData: false,
        contentType: false
    };

    return ajax_request(ajax_type, url, parameters, success_function, additional_configs);
}