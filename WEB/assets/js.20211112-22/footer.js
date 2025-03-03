    /* MOVED from footer */

    // UNIVERSAL VARIABLES THAT CAN BE ACCESSED ANYWHERE
    var NEW_VENDOR = $('#new_vendor').val();
    var DPA = $('#showdpa').val();
    var VENDOR_INVITE_ID = $('#vendor_invite_id').val();
    var BASE_URL = $('#base_url').val();
    var ASSET_URL = $('#asset_url').val();
    var $navbar = $('#navbar');
    var $nav = $navbar.find(".nav");
    var $main_container = $('#main_container');

    var $breadcrumb = $('.breadcrumb');

    var $div_notifications = cache.set('div_alerts', $('#notifications'));
    var $alert_success = cache.set('alert_success', $div_notifications.find('.alert-success'));
    var $alert_info = cache.set('alert_info', $div_notifications.find('.alert-info'));
    var $alert_warning = cache.set('alert_warning', $div_notifications.find('.alert-warning'));
    var $alert_danger = cache.set('alert_danger', $div_notifications.find('.alert-danger'));
    var $modal_alert_success = cache.set('modal_alert_success', $div_notifications.find('#modal_alert_success'));
    var $modal_alert_success = cache.set('modal_alert_info', $div_notifications.find('#modal_alert_info'));
    var $modal_alert_success = cache.set('modal_alert_warning', $div_notifications.find('#modal_alert_warning'));
    var $modal_alert_success = cache.set('modal_alert_danger', $div_notifications.find('#modal_alert_danger'));

    var $hidden_elements = $('#hidden_elements');

    var $loading_ring = cache.set('loading_ring', $hidden_elements.find('#loading_ring'));

    var SIMILAR_LIST_TEMPLATE = $('#similar_list_template').html();

    //for bid monitor
    var bid_clock;

    //after getting all mustache templates empty div so that no one can see it :D juanitinde
    $('#m_templates').html('');

    // DEFAULT PAGE if no refresh path is set
    if((NEW_VENDOR == 1)&&(DPA == 1)) {
        $main_container.html('').load(BASE_URL + 'vendor/registration/index/' + VENDOR_INVITE_ID);
    }
    else if (cache.get('refresh_path') === undefined) {
        $nav.find('a[data-path="dashboard/home_page"]').parent().addClass('active');
        $main_container.html('').load(BASE_URL + 'dashboard/home_page');
        cache.set('refresh_path', 'dashboard/home_page');
    }

    // src: https://stackoverflow.com/questions/2482059/disable-f5-and-browser-refresh-using-javascript
    // src: https://developer.mozilla.org/en-US/docs/Web/API/MouseEvent/ctrlKey
    function overrideRefresh(e) {
        // F5 and ctrl + R
        if ((e.keyCode == 116) || (e.ctrlKey && e.keyCode == 82)) {
            e.preventDefault();
            // reload user scripts
            // $('#common_user_scripts').find('script').each(function() {
            //     $.getScript($(this).prop('src'));
            // });

            $main_container.html('').load(cache.get('refresh_path'));
        }
    };

    $(document).on("keydown", overrideRefresh);

    // HIGHLIGHT SELECTED MENU AND ITS TREE (sorry, wala maisip na name)
    $nav.find('a').on("click", function()
    {
        clear_timeouts();

        $div_notifications.stop().fadeOut("slow");

        var this_el = $(this);

        $nav.find(".active").removeClass("active");

        if (this_el.closest('li.dropdown').length > 0) // if main menu is dropdown, go thru the menu tree and highlight parents li
        {
            $.each(this_el.parents('li'), function() {
                $(this).addClass("active");
            });
        }
        else {
            this_el.parent().addClass("active"); // if main menu is not dropdown, only highlight the main menu item
        }

        if (this_el.data('path') == null) {
            return;
        }

        var functionPath = BASE_URL + this_el.data('path');
        $main_container.html('').load(cache.set('refresh_path', functionPath));

        create_breadcrumbs(this_el);
    });

    function create_breadcrumbs(this_el)
    {
        var breadcrumbs = ''; //Clean crumb
        if (this_el.find('span.menu_label').prop('innerText') !== 'Home') {
            breadcrumbs = '<li><a href="#" onclick="force_reload()">Home</a></li>';
        }

        var count = 1;
        var breadcrumb_length = $nav.find('li.active > a').length;
        $nav.find('li.active > a').each(function()
        {
            var link = link_end = b_tag = b_tag_end = '';
            if ($(this).data('path') && $(this).data('path') != 'dashboard/home_page') {
                var link = '<a href="#" data-path="' + $(this).data('path') + '">';
                var link_end = '</a>';
            }
            else if (this_el.data('action-path')) {
                var link = '<a href="#" data-path="' + this_el.data('action-path') + '">';
                var link_end = '</a>';
            }

            if (count++ == breadcrumb_length) {
                var b_tag = '<b>';
                var b_tag_end = '<b/>';
            }

            if (this_el.data('crumb-text')) {
                if ($(this).prop('innerText') !== 'Home') {
                    breadcrumbs += '<li>' + link + $(this).prop('innerText') + link_end + '</li>';
                }
                breadcrumbs += '<li>' + link + b_tag + this_el.data('crumb-text') + b_tag_end + link_end + '</li>';
            }
            else{
                breadcrumbs += '<li>' + link + b_tag + $(this).find('span.menu_label').prop('innerText') + b_tag_end + link_end + '</li>';
            }
        });

        $breadcrumb.html(breadcrumbs);
    }

    function clear_timeouts()
    {
        if (cache.get('get_rfq_rfb')) {
            clearTimeout(cache.get('get_rfq_rfb'));
            cache.set('get_rfq_rfb', '');
        }

        if (cache.get('get_vendor_registrations')) {
            clearTimeout(cache.get('get_vendor_registrations'));
            cache.set('get_vendor_registrations', '');
        }

        if (cache.get('get_messages')) {
            clearTimeout(cache.get('get_messages'));
            cache.set('get_messages', '');
        }

        clearInterval(bid_clock);
    }

    $breadcrumb.on('click', 'a', function()
    {
        $div_notifications.stop().fadeOut("slow");

        var this_el = $(this);

        if (this_el.data('path') == null) {
            return;
        }

        var functionPath = BASE_URL + this_el.data('path');
        $main_container.html('').load(cache.set('refresh_path', functionPath));
    });
/* END */