'use strict';

var $panel_header = document.querySelector('.panel-heading');
var $panel_body = document.querySelector('.panel-body');

check_browser();

function check_browser() {

    var ua = window.navigator.userAgent;
    var rv = -1;
    if (ua.indexOf("MSIE") > 0 || ua.indexOf("Edge") > 0 || !!navigator.userAgent.match(/Trident/) 
        ||(navigator.userAgent.toLowerCase().indexOf('firefox') > -1)
        || (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1)
    ) {
        $panel_body.querySelector('#unsupported_browser_panel').style.display = 'block';

    }
    else
     {
        $panel_body.querySelector('#login_panel').style.display = 'block';
    }
};
