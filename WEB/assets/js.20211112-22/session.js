var $modal_session_expiry = $('.modal#session_expiry');
var refresh_session = store_session;
var prev_session_expiry_date;
var session_expired = false;

var get_remember_me = function () {
    var ajax_type = 'get';
    var url = 'login/get_remember_me';
    var params = {};
    var success_function = function (responseText) {
        var obj = $.parseJSON(responseText);

        var remember_me = obj.remember_me;
        var username = (remember_me) ? obj.username : '';
        var password = (remember_me) ? obj.password : '';

        localStorage.setItem("remember_me", remember_me);
        localStorage.setItem("username", username);
        localStorage.setItem("password", password);
    }

    ajax_request(ajax_type, url, params, success_function);
}

function store_session()
{
    var ajax_type = 'post';
    var url = 'login/get_session';
    var params = {};
    var success_function = function (responseText) {
        localStorage.setItem('user_session', responseText);
    }

    ajax_request(ajax_type, url, params, success_function);
}

function check_session_expiry() {
    if(is_session_expired()) {
        $modal_session_expiry.modal('show');
		session_expired = true;
		//console.log("session expired");
    }

    var MILLISECONDS = 1000;
    var SECONDS = 3;

    setTimeout(check_session_expiry, SECONDS * MILLISECONDS);
}

function is_session_expired() {
    var temp = lmx;
    var datetime_now = new Date(temp);
    var prev_session_expiry_date = (localStorage.getItem('session_expiry_date')) ? new Date(localStorage.getItem('session_expiry_date')) : new Date(set_session_expiry());

    return (datetime_now > prev_session_expiry_date);
}

// src: https://stackoverflow.com/questions/1197928/how-to-add-30-minutes-to-a-javascript-date-object/1197939#1197939
function set_session_expiry()
{

    // var SESSION_TIME_LIMIT_SEC = 10; // For TEST
    var SESSION_TIME_LIMIT_MINS = 30; // For Production

    // Set the unit values in milliseconds.
    var msecPerSec = 1000;
    var msecPerMinute = msecPerSec * 60;
    var msecPerHour = msecPerMinute * 60;
    var msecPerDay = msecPerHour * 24;;
    var temp = lmx;
    var datetime_now = new Date(lmx);
    var session_expiry_date = prev_session_expiry_date = localStorage.getItem('session_expiry_date');

    if (typeof prev_session_expiry_date) {
        // session_expiry_date = new Date(datetime_now.getTime() + SESSION_TIME_LIMIT_SEC * msecPerSec); // FOR TEST

        let tmpTime = datetime_now.getTime() + (SESSION_TIME_LIMIT_MINS * msecPerMinute);

        //console.log(new Date(tmpTime));
        //console.log(lmx.getDate())
        //console.log(tmpTime);

        session_expiry_date = new Date(tmpTime); // For Production
        localStorage.setItem('session_expiry_date', session_expiry_date);
       
    }

    var hr = session_expiry_date.getHours();
    var min = session_expiry_date.getMinutes();
    var sec = session_expiry_date.getSeconds();
    ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
    hr = (hr == 0) ? 12 : hr;
    hr = (hr > 12) ? hr - 12 : hr;

    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);

    var TIME = hr + ":" + min + ":" + sec + " " + ap;


    let _sdays = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];


    let sess_html = _sdays[session_expiry_date.getDay()] +", "+ session_expiry_date.getDate() + " "+ months[session_expiry_date.getMonth()] + " " +session_expiry_date.getFullYear() +" - " +TIME;

    $('#session_expiry_date').html(sess_html);

    return session_expiry_date;
}

function continue_session() {
    var ajax_type = 'post';
    var url = 'login/refresh_session';
    var params = $.param($.parseJSON(localStorage.getItem('user_session'))); // src: https://stackoverflow.com/questions/4239460/serializing-an-array-in-jquery
    var success_function = function (responseText) {
        localStorage.removeItem('session_expiry_date');
        set_session_expiry();
        $modal_session_expiry.modal('hide');
    }

    ajax_request(ajax_type, url, params, success_function);
	
	session_expired = false;
	//console.log('reset session');
}

function end_session($user_id) {
    log_action($user_id, 2).done(function() {
        localStorage.removeItem('session_expiry_date');
        localStorage.removeItem('user_session');
        location.assign("login/logout");
    });
    localStorage.removeItem('session_expiry_date');
}

if (!localStorage.getItem('user_session')) {
    store_session();
}

set_session_expiry();
check_session_expiry();
get_remember_me();

function update_session_time(){
	localStorage.removeItem('session_expiry_date');
    set_session_expiry();
}

$(document).ready(function () {
	
    $(this).mousemove(function (e) {
		if(!session_expired){
			update_session_time();
		//	console.log("mousemove");
		}
	});
	$(this).keypress(function (e) {
		if(!session_expired){
			update_session_time();
		//	console.log("keypress");
		}
	});
});
