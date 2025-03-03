$(document).ready(function() {

    $main_container.on('click', '#findsimilar', function(){

        var txt_email           = $('#txt_email').val();
        var txt_contact_person  = $('#txt_contact_person').val();
        var txt_vendorname      = $('#txt_vendorname').val();

        var ajax_type = 'POST';
        var url = BASE_URL + "common/common/find_similar";
        var post_params = {
            'txt_email'             : txt_email,
            'txt_contact_person'    : txt_contact_person,
            'txt_vendorname'        : txt_vendorname
        };

        var success_function = function(responseText)
        {
            var similar_data = $.parseJSON(responseText);
            // $('.show_email').html(responseText);
            var DATA = {
                similar_list: similar_data
            }

            $('#similar_list').html(Mustache.render(SIMILAR_LIST_TEMPLATE, DATA));

            $('#similar_show').collapse('show');
        };

        ajax_request(ajax_type, url, post_params, success_function);
    });

    $main_container.on('click', '#email_list .rd_email', function(){
        $('#txt_email').val($("input[name='email']:checked").val());
        $('#similar_show').collapse('hide');
    });


    $main_container.on('click', '.cls_action', function(){

        let this_el = $(this);

        var action_path = BASE_URL + $(this).data('action-path');
        $main_container.html('').load(cache.set('refresh_path', action_path));

        create_breadcrumbs(this_el);
    });
	//console.log('Execute');
    generateDateTime(null);

    $main_container.on('keyup', '.numeric', function(){
        this.value = this.value.replace(/[^0-9]/g,'');
    });


   $main_container.on('focus', '.numeric-decimal', function(){
        //this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ",") ;
        //this.value =  this.value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,") ;
        if (!$("#"+this.id).data('numFormat')) {
            $("#"+this.id).number( true, 2 );
        }

        //this.value = $.number(this.value,0);

    });

	$main_container.on('focus', '.auto_suggest', function(){ //define the function for auto suggest class
        if ($("#"+this.id).attr('auto_suggest_flag')!=1) { // checks if the function has been created.
			$(this).attr('auto_suggest_flag',1);
			var src;
			var max_rows = 50;
			var append_to = null;
			if ($(this).attr('list-max-row')) { //add this attribue to alter the default limit of list, too many items can cause input lags.
				max_rows = $(this).attr('list-max-row');
			}

			if ($(this).attr('list-container')) { // if the list-container attribute is define, then get the idspecified and convert it to array.
				var list_array = $('#' + $(this).attr('list-container') +' > option').map(function() { return this.label; }).get();
				src = function(request, response) {
					var results = $.ui.autocomplete.filter(list_array, request.term);

					response(results.slice(0, max_rows));
				};
			} else if ($(this).attr('list-uri')) { // this attribute specifies a link to an function that returns the suggestions as json.
				src = $(this).attr('list-uri');
			}

			if (!src) {
				src = ["could not load auto complete list."];
			}

			if ($(this).attr('append-list-to')){
				append_to = "#" + $(this).attr('append-list-to');
			}


			$(this).autocomplete({
				   source: src,
				   minLength: 0,
				   appendTo: append_to,
				   select: $(this).trigger('input')
				});
        }
    });

	$main_container.on('click', '.autocomplete-toggle', function(){
        if ($(this).attr('input-toggle')) { // id of input text to activate autocomplete
			$( "#" + $(this).attr('input-toggle')).focus();
			$( "#" + $(this).attr('input-toggle')).autocomplete( "search", $("#" + $(this).attr('input-toggle')).value );
		}
    });

    $main_container.on('keyup', '.removeSpecialChar', function(){
        this.value = this.value.replace(/[^a-zA-Z0-9 ]/g,'');
    });

    $main_container.on('click', '.btn-exit', function(){
        var span_message = 'Are you sure you want to return to homepage? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="goto_homepage()">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
        var type = 'info';
        notify(span_message, type, true);
    });

    // limit characters class based on max_length
    $main_container.on('input', '.limit-chars', function(e){
      if ($(this).attr('maxlength')){
        var max = $(this).attr('maxlength');
        var len = $(this).val().length;
        var elem_id = $(this).attr('id');
        var char = max - len;
        $('#' + elem_id +'_char_num').text('(' + char + '/' + max+ ')');
		
		//Jay
		var htmlencoded = $('<div/>').text($(this).val()).html();
		//console.log(htmlencoded.length);
		if(htmlencoded.length > max){
			var input_data = $(this).val().toString();
			
			var len = input_data.length;
			var htmlencoded_2;
			for (var x = 0; x < len; x++) {	
				htmlencoded_2 = $('<div/>').text(input_data).html();
				if(htmlencoded_2.length > max){
					input_data = input_data.slice(0, -1);
				}else{
					break;
				}
			}
			$(this).val(input_data);
		}
      }
    });
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function validateForm(el = '') // el is for specific element to validate for field-required
{
    var isValid = true;
	var count = 0;

    $(el+' .field-required').each(function() {

        if ($(this).is(':radio'))
        {
            var name = this.name;

            if ($('input[name='+ name +']:checked').length == 0)
            {
                isValid = false;
                $('input[name='+ name +']').closest('div').addClass('has-error');

            }
            else
            {
                $('input[name='+ name +']').closest('div').removeClass('has-error');
            }
        }
        else
        {
			count = 0;

            if ( $.trim($(this).val()) === '' || $(this).val() === null) // added trim to remove whitespaces
            {
                isValid = false;
				
                $('#'+this.id).parent('div').addClass('has-error');
                $('#'+this.id).addClass('has-error-input');
				
				$("#" + $('#'+this.id).parent('div').attr("id") + " .field-required").each(function() {
					if($("#" + this.id).hasClass('has-error-input')){
						count++;
					}
				});
				
				
				$("#" + $('#'+this.id).parent().parent().attr("id") + " td .field-required").each(function() {
					if($("#" + this.id).hasClass('has-error-input')){
						count++;
					}
				});
				if(count > 0){
					$('#'+this.id).parent('div').removeClass('has-error');
				}
            }
            else
            {
				
                $('#'+this.id).removeClass('has-error-input');
				
				
				$($('#'+this.id).parent('div').attr("id") + " .field-required").each(function() {
					if($("#" + this.id).hasClass('has-error-input')){
						count++;
					}
				});
				
				$("#" + $('#'+this.id).parent().parent().attr("id") + " td .field-required").each(function() {
					if($("#" + this.id).hasClass('has-error-input')){
						count++;
					}
				});
				
				$('#'+this.id).parent('div').removeClass('has-error');
            }
        }

    });

    focus_first_elem_error();

    return isValid;
}

function focus_first_elem_error()
{
     $('.has-error').each(function(i) {
        if (i == 0){
           $(this).find(':input[type!=hidden]:first').focus();
        }
    });
}
var inDelay = false;
function notify(span_message, type, is_confirmation = null)
{
    let notif_div;
	$('html, body').animate({ scrollTop: 0 }, 'fast');

    switch(type) {
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
    $div_notifications.clearQueue(); //Prevent fading out the next notification
    $div_notifications.stop();
    $div_notifications.css('opacity', '');
	// $('body').scrollTo($div_notifications);
	// $('html, body').animate({
        // scrollTop: $($div_notifications).offset().top
    // }, 500);
	
	//$div_notifications.stop(true, true);
    if (is_confirmation === null) {
		$div_notifications.fadeIn("slow").delay(3000).fadeOut('slow', clean_div_notif);
    } else {
        $div_notifications.fadeIn("slow");
    }

}

$div_notifications.on('click', '#close_alert', function () {
    $div_notifications.stop().fadeOut("slow", clean_div_notif);
});

function clean_div_notif() {
    $div_notifications.html('');
}

function removeClass(elem, class_name){
	if (elem)
		$(elem).removeClass(class_name);
}

function modal_notify(modal, message, type, is_confirmation = null)
{
    let $modal_body;
    let $notif_div;
    let $modal_alert;

    // if button is NOT an object (a jquery selector object)
    if (!(modal instanceof Object)) {
        $modal_body = $main_container.find(`#${modal} .modal-body`);
    }
    else { // jquery selector object. more optimized if programmer knows how to take advantage of jquery selectors and caching
        $modal_body = modal.find('.modal-body');
    }

    switch(type) {
        case 'success':
            $notif_div = cache.get('alert_success');
        break;
        case 'modal_success':
            $notif_div = cache.get('modal_alert_success');
        break;
        case 'info':
            $notif_div = cache.get('alert_info');
        break;
        case 'modal_info':
            $notif_div = cache.get('modal_alert_info');
        break;
        case 'warning':
            $notif_div = cache.get('alert_warning');
        break;
        case 'modal_warning':
            $notif_div = cache.get('modal_alert_warning');
        break;
        case 'danger':
            $notif_div = cache.get('alert_danger');
        break;
        case 'modal_danger':
            $notif_div = cache.get('modal_alert_danger');
        break;
    }

    $notif_div.addClass('hidden_el');
    $notif_div.find('span').html(message);

    $modal_body.find('.alert').remove();
    $modal_body.prepend($notif_div);

    $modal_alert = cache.set('modal_alert', $modal_body.find('.alert'));    
	$modal_alert.clearQueue(); //Prevent fading out the next notification
    $modal_alert.stop();
    $modal_alert.css('opacity', '');

    if (is_confirmation === null) {
        $modal_alert.fadeIn("slow").delay(3000).fadeOut('slow');
    } else {
        $modal_alert.fadeIn("slow");
    }

    $('#modal_alert_success').addClass('alert-success');
    $('#modal_alert_info').addClass('alert-info');
    $('#modal_alert_warning').addClass('alert-warning');
    $('#modal_alert_danger').addClass('alert-danger');

}

$main_container.on('click', '.modal #close_alert', function () {
    cache.get('modal_alert').stop().fadeOut("slow");
});

function disable_enable_frm(frm_id, type, exclude = null) // type = true , false
{
    $('#'+frm_id+' :input:not('+ exclude +')').prop('disabled', type);

    if (type == true) // for clickable span
        $('#'+frm_id+' span').css("pointer-events", "none");
    else
        $('#'+frm_id+' span').css("pointer-events", "auto");
}

// note: currently SVG loading's height is set to 15px. so other elements aside from button(class="form-control") will display an incorrect size
function loading(el, status)
{
    let element;
    let orig_inner_html;
    let new_inner_html;

    // if element is NOT an object (a jquery selector object)
    if (!(el instanceof Object)) {
        element = $main_container.find(`#${el}`);
        orig_inner_html = `${el}_label`;
    }
    else { // jquery selector object. more optimized if programmer knows how to take advantage of jquery selectors and caching
        element = el;
        orig_inner_html = `${element.prop('id')}_label`;
    }

    if (status === 'in_progress') {
        element.addClass('disabled');
        element.prop('disabled', true);
        cache.set(orig_inner_html, element.html());
        new_inner_html = cache.get('loading_ring');
    }
    else { // done
        element.removeClass('disabled');
        element.prop('disabled', false);
        new_inner_html = cache.get(orig_inner_html);
    }

    element.html(new_inner_html);
}

function upload_ajax_modal(form_name, surl)
{
   var formData = new FormData(form_name);

    let ajax_type = 'post';
    let url = surl; //BASE_URL + "vendor/registration/upload_file/1";
    let parameters = formData;
    let success_function = function(responseText) {

        // cache.set('wanitindi', responseText);
    };
    let additional_configs = {
        processData: false,
        contentType: false
    };

    return ajax_request(ajax_type, url, parameters, success_function, additional_configs);
}

function isEmail()
{
    var isValid = true;
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    $('.isEmail').each(function() {
        var email_val = this.value;

        if ($(this) && $(this).val())
        {
           if (!regex.test(email_val))
            {
                isValid = false;
                $('#'+this.id).parent('div').addClass('has-error');
            }
            else
            {
                $('#'+this.id).parent('div').removeClass('has-error');
            }
        }
    });

    return isValid;
}

function hasSpecChar()
{
    var isValid = true;
    var alphanumers = /^[a-zA-Z0-9\s]+$/;
    var alphanumers_note = /^[a-zA-Z0-9.,-\s]+$/;
    $('.no-specialchar').each(function() {

        if($(this) && $(this).val())
        {
            if($('#'+this.id).val() == '' || $('#'+this.id).val() == "")
            {
                $('#'+this.id).parent('div').removeClass('has-error');
            }
            else
            {
                if(!alphanumers.test($(this).val())){
                    isValid = false;

                    $('#'+this.id).parent('div').addClass('has-error');
                }
                else
                {
                    $('#'+this.id).parent('div').removeClass('has-error');
                }
            }

        }
        else
        {
            $('#'+this.id).parent('div').removeClass('has-error');
        }

    });

    $('.no-specialchar_note').each(function() {

        if($(this) && $(this).val())
        {
            if($('#'+this.id).val() == '' || $('#'+this.id).val() == "")
            {
                $('#'+this.id).parent('div').removeClass('has-error');
            }
            else
            {
                if(!alphanumers_note.test($(this).val())){
                    isValid = false;

                    $('#'+this.id).parent('div').addClass('has-error');
                }
                else
                {
                    $('#'+this.id).parent('div').removeClass('has-error');
                }
            }

        }
        else
        {
            $('#'+this.id).parent('div').removeClass('has-error');
        }

    });

    return isValid;
}

function hasSpecChar_single(id)
{
    var isValid = true;
    var alphanumers = /^[a-zA-Z0-9\s]+$/;


        if($('#'+id) && $('#'+id).val())
        {
            if($('#'+id).val() == '' || $('#'+id).val() == "")
            {
                $('#'+id).parent('div').removeClass('has-error');
            }
            else
            {
                if(!alphanumers.test($('#'+id).val())){
                    isValid = false;

                    $('#'+id).parent('div').addClass('has-error');
                }
                else
                {
                    $('#'+id).parent('div').removeClass('has-error');
                }
            }

        }
        else
        {
            $('#'+id).parent('div').removeClass('has-error');
        }

    return isValid;
}
// SRC: https://www.codexworld.com/create-digital-clock-with-date-javascript/
/*function generateDateTime(date)
{
    var st = srvTime();
    var this_date = date || new Date(st); // get server time

    var hr = this_date.getHours();
    var min = this_date.getMinutes();
    var sec = this_date.getSeconds();
    ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
    hr = (hr == 0) ? 12 : hr;
    hr = (hr > 12) ? hr - 12 : hr;
    //Add a zero in front of numbers<10
    hr = checkTime(hr);
    min = checkTime(min);
    sec = checkTime(sec);
    var TIME = hr + ":" + min + ":" + sec + " " + ap;

    var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    var curWeekDay = days[this_date.getDay()];
    var curDay = this_date.getDate();
    var curMonth = months[this_date.getMonth()];
    var curYear = this_date.getFullYear();
    var DATE = curWeekDay+", "+curDay+" "+curMonth+" "+curYear;
    var DATETIME = DATE + ' - ' + TIME;

    if (!date) {
        $('.datetime').html(DATETIME);
        var datetime = setTimeout(function(){ generateDateTime() }, 1000);
    }
    else {
        return DATETIME;
    }
}

function checkTime(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}*/

function loadingScreen(load_switch) {

    if (load_switch == 'on') {
        var html = '<div id="loading-overlay"></div>';
        $main_container.append(html);
    }
    else if (load_switch == 'off') {
        $main_container.find('#loading-overlay').remove();
    }

}

function goto_homepage()
{
    $('#approve_btn').prop('disabled', true);
    $('#close_alert').prop('disabled', true);
    $div_notifications.stop().fadeOut("slow");
    var action_path = BASE_URL + 'dashboard/home_page/';
    $main_container.html('').load(cache.set('refresh_path', action_path));

}


function goto_refresh(rid)
{
    var action_path = BASE_URL + 'rfqb/rfq_rfb_award/display_awarded';
    $main_container.html('').load(cache.set('refresh_path', action_path));
}

function force_reload()
{
    location.reload();
}

// function toggle_dropdown(div_name) {
	// document.getElementById(div_name).classList.toggle("show");
// }

// function filter_dropdown(input, div_name) {
	// var input, filter, ul, li, a, i;
	// filter = input.value.toUpperCase();
	// div = document.getElementById(div_name);
	// // div.focus();
	// a = div.getElementsByTagName("span");
	// for (i = 0; i < a.length; i++) {
		// if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
			// a[i].style.display = "";
		// } else {
			// a[i].style.display = "none";
		// }
	// }
// }

// function dropdown_list_select(input_name, selected_value) {
	// document.getElementById(input_name).value = selected_value;
// }
//ref: https://stackoverflow.com/questions/20269657/right-way-to-get-web-server-time-and-display-it-on-web-pages
var xmlHttp;
// function srvTime(){
//     try {
//         //FF, Opera, Safari, Chrome
//         xmlHttp = new XMLHttpRequest();
//     }
//     catch (err1) {
//         //IE
//         try {
//             xmlHttp = new ActiveXObject('Msxml2.XMLHTTP');
//         }
//         catch (err2) {
//             try {
//                 xmlHttp = new ActiveXObject('Microsoft.XMLHTTP');
//             }
//             catch (eerr3) {
//                 //AJAX not supported, use CPU time.
//                 alert("AJAX not supported");
//             }
//         }
//     }
//     xmlHttp.open('HEAD',window.location.href.toString(),false);
//     xmlHttp.setRequestHeader("Content-Type", "text/html");
//     xmlHttp.send('');

//     console.log(xmlHttp.getResponseHeader("Date"));
//     return xmlHttp.getResponseHeader("Date");


//     lmx = lmx + 1;

//     return lmx;

// /*        $.ajax({
//             type:'POST',
//             data:{},
//             url: BASE_URL+'common/common/get_srvDate',
//             success: function(result){
//            // let n = JSON.parse(result)
//             console.log(result);
//             return result; 

//             },error: function(result)
//             {
//                 //alert(result + 'e');  
//                 return;     
//             }
//             }).fail(function(result){

//                 //alert(result + 'f');
//                 return;
//             });  */  

// }


function get_vendor_table(page_number = 0,table_name,pagination_name,postparams){

    $('#'+table_name).parent('div').addClass('disabledbutton');

    let url  = 'vendor/registration/registrationmain_table';
    let type = 'POST';
    if($('#for_hats_val').val() != 0){
         let status = $('#vendor_status').find(':selected').data('id');
    }
	
	// Added MSF 20191129 (NA)
    if($('#for_vrdstaff_val').val() != 0){
         let status = $('#vendor_status').find(':selected').data('id');
    }
   

    page_number = page_number * 10;
    postparams.page_no = page_number;
    let success_function = function(responseText){
        console.log(responseText);
        let n = JSON.parse(responseText);
        let data = n.query; //vendor_information+
        create_vendor_table(data,table_name);
        if (n.total_page == undefined){
            n.total_page = 0;
        }

        create_pagination_proto(page_number/10,Math.ceil(n.total_page/10),pagination_name);

        $('#'+table_name).parent('div').removeClass('disabledbutton');


    }
     ajax_request(type, url, postparams, success_function);
}


function replaceNewLine(myString) {
    var regX = /\r\n|\r|\n/g;
    var replaceString = '<br />';
    return myString.replace(regX, replaceString);   
    
}


function create_vendor_table(data,table_name){

/*    console.log(data);*/

    let _gtd = '';
    let _positionid = document.getElementById('position_id').value;
    let _statusid = document.getElementById('status_id').value;
    let _vendorinviteid = document.getElementById('vendor_invite_id').value;
    let _uploadcomplete = document.getElementById('upload_complete').value;

    let _vrd = [1,4,5,6];
    let _nonvrd = [2,3,7,8,9,11];
    let _vendor = [10];
    let _vendorname_data_action_path = '';

    if(data.length >0){
    data.forEach(function(_val){

    if(_positionid == 10){

        let _status = [11,12,18,19,194,195,192];
        let _statusresult = in_array(_statusid,_status);

        if(_val.DATE_CREATED == null || _val.DATE_CREATED == 'null'){
            _val.DATE_CREATED = '';
        }

        if(_val.DATE_SUBMITTED == null || _val.DATE_SUBMITTED == 'null'){
            _val.DATE_SUBMITTED = '';
        }

        _gtd += '<tr>';
        _gtd += '<td>'+ _val.VENDOR_NAME +'</td>';
        _gtd += '<td>'+ _val.STATUS_NAME +'</td>';
        _gtd += '<td><a href = "#" class="cls_action" data-action-path="messaging/mail/index/'+ _val.MESSAGE_INDEX_PARAM +'" data-crumb-text="Messages">'+ _val.MESSAGE_COUNT +'</a></td>';
        _gtd += '<td>'+ _val.DATE_CREATED +'</td>';
        _gtd += '<td>'+ _val.DATE_SUBMITTED +'</td>';

        if(_uploadcomplete == undefined){
            _uploadcomplete = 0;
        }

        if(_statusid > 9 && _statusresult == false &&  _uploadcomplete != 1){
            _gtd += '<td><a href = "#" data-action-path="vendor/registration/index/'+_vendorinviteid+'" data-crumb-text="Vendor Submit Additional Requirements" data-invite-id ="'+_vendorinviteid+'" class = "cls_action">Submit Additional Requirements</a></td>';
        }else{

            if(_val.ACTION_LABEL == null || _val.ACTION_LABEL == 'null'){
                _val.ACTION_LABEL = '';
            }
            _gtd += '<td><a href = "#" data-action-path="'+_val.ACTION_PATH+'" data-crumb-text="Vendor '+_val.ACTION_LABEL+'" data-invite-id ="'+_val.VENDOR_INVITE_ID+'" class = "cls_action">'+ _val.ACTION_LABEL +'</a></td>';
        }
        _gtd += '</tr>';

    }else{

    if(in_array(_positionid,_vrd)){
        if(_val.VENDOR_ID == null || _val.VENDOR_ID == 'null'){
            _vendorname_data_action_path = 'vendor/inviteapproval/index/'+ _val.VENDOR_INVITE_ID + '/1';
        }else{
            _vendorname_data_action_path = 'vendor/registration/display_vendor_details/'+_val.VENDOR_ID;
        }
    }else if(in_array(_positionid,_nonvrd)){
        _vendorname_data_action_path = 'vendor/inviteapproval/index/'+_val.VENDOR_INVITE_ID+'/1/'+_val.STATUS_ID;
    }else if(in_array(_positionid,_vendor)){

    }


    if(_val.ACTION_LABEL == null || _val.ACTION_LABEL == 'null' || _val.ACTION_PATH == null || _val.ACTION_PATH == 'null'){
        _val.ACTION_LABEL = '';
    }

    if(_val.DATE_SUBMITTED == null || _val.DATE_SUBMITTED == 'null'){
        _val.DATE_SUBMITTED = '';
    }
       if(_val.DATE_CREATED == null || _val.DATE_CREATED == 'null'){
        _val.DATE_CREATED = '';
    }

    if(_val.VENDOR_NAME == null){
        _val.VENDOR_NAME = '';
    }
	
	// Update MSF - 20191105 (IJR-10612)
	if(_val.STATUS_NAME == "Completed"){
		vendor_code = " [" + _val.VENDOR_CODE + "]";
	}else{
		vendor_code = "";
	}
	
        _gtd += '<tr>';
		// Update MSF - 20191105 (IJR-10612)
        //_gtd += '<td><a href = "#" data-action-path = "'+_vendorname_data_action_path+'" data-crumb-text ="Vendor Registration" class = "cls_action">'+ _val.VENDOR_NAME.toUpperCase() +'</a></td>';
        _gtd += '<td><a href = "#" data-action-path = "'+_vendorname_data_action_path+'" data-crumb-text ="Vendor Registration" class = "cls_action">'+ _val.VENDOR_NAME.toUpperCase() + vendor_code +'</a></td>';
        _gtd += '<td>'+ _val.STATUS_NAME +'</td>';
        _gtd += '<td><a href = "#" class="cls_action" data-action-path="messaging/mail/index/'+ _val.MESSAGE_INDEX_PARAM +'" data-crumb-text="Messages">'+ _val.MESSAGE_COUNT +'</a></td>';
        _gtd += '<td>'+ _val.DATE_CREATED +'</td>';
        _gtd += '<td>'+ _val.DATE_SUBMITTED +'</td>';
        _gtd += '<td><a href = "#" data-action-path="'+_val.ACTION_PATH+'" data-crumb-text="Vendor '+_val.ACTION_LABEL+'" data-invite-id ="'+_val.VENDOR_INVITE_ID+'" class = "cls_action">'+ _val.ACTION_LABEL +'</a></td>';
        _gtd += '</tr>';
    }
    });
    }else{
        _gtd = '<td>No Records Found!</td>';
    }
    
    document.getElementById(''+table_name+'').tBodies[0].innerHTML = _gtd;

}

function in_array(_needle,_array){

    for(let i = 0; i < _array.length ; i++){
        if(_needle == _array[i]){
            return true;
        }
    }
    return false;
}

function create_pagination_proto(current_page = 1,total_number_of_page = 0,pagination_name){
    let previous_disable = '';
    let next_disable = '';
    let pagination = '';
    let maximum_page = 5;

    total_number_of_page = parseInt(total_number_of_page);
    current_page = parseInt(current_page);

    pagination = '<nav aria-label="Page navigation">';
    pagination += '<ul class="pagination">';

            if(total_number_of_page == 0 || current_page == 0){
            previous_disable = 'disabled';
            }
            pagination += '<li '+previous_disable+' class = "'+previous_disable+'" ><a '+previous_disable+' href="#" aria-label="Previous" onclick = "return false;" class = "cl_pag" data-pg = "'+(parseInt(current_page))+'"><span aria-hidden="true">&laquo;</span></a></li>';

            let i = 1;
            let iteration_number = current_page -2;
            let max_iteration = 0;
            max_iteration = total_number_of_page;

            if(current_page == total_number_of_page || current_page > total_number_of_page - maximum_page){
                iteration_number = max_iteration - maximum_page;
                if(iteration_number == 0){
                    iteration_number = 1;
                }
            }

            if(current_page >= 4 && total_number_of_page > 6){
            pagination += '<li><a href="#" class = "cl_pag" onclick = "return false;" data-pg = "1">1</a></li>';
            pagination += '<li><span>...</span></li>';
            }
            
            let tmp = iteration_number;

            if(current_page < 4){
                tmp = 1;
            }


            for(i = tmp; i <=  iteration_number + maximum_page ; i++){

                let page_active = '';
                let page_dsb = '';

                if(i == current_page + 1){
                    page_active = 'active';
                    page_dsb = 'disabled';

                }   

                pagination += '<li class="'+page_active+'" '+page_dsb+'><a href="#"  class = "cl_pag" onclick = "return false;" data-pg = "'+i+'" '+page_dsb+'>'+i+'</a></li>';
            }
            if(i < (total_number_of_page)){


                pagination += '<li><span>...</span></li>';
                pagination += '<li><a href="#" class = "cl_pag" onclick = "return false;" data-pg = "'+total_number_of_page+'">'+total_number_of_page+'</a></li>';

            }

            if(total_number_of_page == 0){
            pagination += '<li class= "active" disabled><a href="#" class = "cl_pag" onclick = "return false;" data-pg = "1" disabled>1</a></li>';
            next_disable = 'disabled';
            }

            if(total_number_of_page == (current_page+1)){
                 next_disable = 'disabled';
            }

        pagination += '<li '+next_disable+' class = "'+next_disable+'"><a '+next_disable+' href="#" aria-label="Next" data-pg = "'+(parseInt(current_page) + 2)+'" class = "cl_pag" onclick="return false;"><span aria-hidden="true"  onclick = "return false;">&raquo;</span></a></li>';
    pagination += '</ul>';
    pagination += '</nav>';
    document.getElementById(''+pagination_name +'').innerHTML = pagination;
}


function get_table_data(post_params_mail,pag_name,table_name,is_checked = 0){

    $('#message_div').addClass('disabledbutton');
    let url  = 'messaging/mail/get_mail_table';
    let type = 'POST';

    let success_function = function(responseText){
       // console.log(responseText);
        let n = JSON.parse(responseText);
        let header = [];
        if(is_checked == 0){

        header = [
            'MAIL_DATE_FORMATTED',
            'SENDER_RECIPIENT',
            'SUBJECT',
            'TOPIC',
            'TYPE',
            'IS_READ'
        ];

        }else{

            header = [          
            'SENDER_RECIPIENT',
            'SUBJECT',
            'TOPIC',
            'MAIL_DATE_FORMATTED',
            'IS_READ'
        ];

        }


        create_table(n.data,header,10,table_name,'IS_READ',is_checked);
        create_pagination_proto(post_params_mail.start/10,Math.ceil(n.recordsTotal/10),pag_name);
        $('#message_div').removeClass('disabledbutton');
    }
     ajax_request(type, url, post_params_mail, success_function);

}


function create_table(data,header,length = 10,table_name,is_image = "",is_checked = 0){

    let i = 0;
    
    let _globaltr = '';
    let _globaltd = '';
        Object.keys(data).forEach(function(value){
            let is_read = data[value]['IS_READ'];
            let bod = '';

            try { 
            bod = decodeURIComponent(data[value]['BODY']);
            } catch(e) { 
            bod = data[value]['BODY'];
            }


            let read_data = '';
            if(is_read == 'mail_unread'){
                read_data = 'info';
            }

            _globaltd +=  '<tr class= "'+read_data+' user_mail" data-message-id ="'+data[value]['ID']+'">';
            _globaltd += '<td hidden>';
            _globaltd += '<input type ="hidden" value = "" data-to ="'+data[value]['SENDER_RECIPIENT']+'" data-subject="'+data[value]['SUBJECT']+'" data-topic = "'+ data[value]['TOPIC'] +'" data-sentdate = "'+data[value]['MAIL_DATE_FORMATTED']+'" data-recid="'+data[value]['RECIPIENT_ID']+'" />';
            _globaltd +=  '<div hidden>'+  bod +'</div>'
            _globaltd += '</td>';

            if(is_checked == 1){
                    _globaltd += '<td><input type = "checkbox" class = "mail_check">';
                    _globaltd += '</td>';
            }


            for(i=0;i<header.length;i++){
                let n = header[i];

                if(n == is_image){
                    _globaltd +=  '<td style="width:30px;"><center><image src = "'+burl+'img/'+ data[value][n] +'.png"></image></center><input type = "hidden" value = "'+data[value][n]+'"/></td>';
                }else{
                    _globaltd +=  '<td>' + data[value][n] + '</td>';
                }           
            }
            _globaltd +=  '</tr>';

        });

    if(_globaltd.length == 0){
        _globaltd = "No Records Found!";

    }
    document.getElementById(table_name).tBodies[0].innerHTML = _globaltd;
}

function open_message(message_id){


    let url  = 'messaging/mail/mark_as_read';
    let type = 'POST';

    let status = $('#msg_notif_status').val();
    let post_params = {message_id:message_id};

    let success_function = function(responseText){

    }
     ajax_request(type, url, post_params, success_function);

}

function create_rfq_table(data,table_name,pagination_name,page_no){

    let tmp_table = "";
    let user_type_id = document.getElementById('user_type_id').value;



    let ndata = JSON.parse(data);

    if(ndata == null){
        let tmp_table = '<tr><td colspan = "3">No Results Found!</td></tr>';
        $('#'+table_name+' tbody').html(tmp_table);
        create_pagination_proto(0,1,pagination_name);
        $('#'+table_name).parent('div').removeClass('disabledbutton');
        return;
    }

    if(user_type_id == 1){
        rfq_table_smuser(ndata,table_name,pagination_name,page_no);
    }else{
        /*alert(123);*/
       /* console.log(ndata);*/
       rfq_table_vendor(ndata,table_name,pagination_name,page_no);
    }
}

function rfq_table_vendor(ndata,table_name,pagination_name,page_no){
    let tmp_table = "";

    if(ndata.query.length == 0){
    tmp_table = '<tr><td colspan = "3">No Results Found!</td></tr>';
    $('#'+table_name+' tbody').html(tmp_table);
    create_pagination_proto(0,1,pagination_name);
    $('#'+table_name).parent('div').removeClass('disabledbutton');
    return;
    }

     ndata.query.forEach(function(val){
        /*console.log(val.RFQRFB_ID);*/

        tmp_table += "<tr>";
        tmp_table += '<td>'+val.RFQRFB_ID+'</td>';
        tmp_table += '<td>'+val.RFQ_TITLE+'</td>';
        tmp_table += '<td>'+val.SUBMISSION_DEADLINE+'</td>';
        tmp_table += '<td>'+val.STATUS_NAME+'</td>';
        tmp_table += '<td>'+val.DATE_CREATED+'</td>';
        tmp_table += '<td><a href = "#" data-action-path = "'+val.ACTION_PATH+'/'+val.RFQRFB_ID+'" class = "cls_action">'+val.ACTION_LABEL+'<a/></td>';
        tmp_table += "</tr>";

     });

    $('#'+table_name+' tbody').html(tmp_table);
    create_pagination_proto(page_no,Math.ceil(ndata.resultscount/10),pagination_name);
    $('#'+table_name).parent('div').removeClass('disabledbutton');

}


function rfq_table_smuser(ndata,table_name,pagination_name,page_no){


let tmp_table = "";

if(ndata.resultscount != 0 ||  ndata.resultscount != ""){

    ndata.query.forEach(function(val){
        if(val.ACTION_LABEL == null){
            val.ACTION_LABEL = '';
        }
        tmp_table += "<tr>";
        tmp_table += '<td>'+val.RFQRFB_ID+'</td>';
        tmp_table += '<td><a href = "#" data-action-path = "rfqb/rfq_details/index/'+val.RFQRFB_ID+'" class = "cls_action" >'+val.RFQ_TITLE+'</a></td>';
        tmp_table += '<td>'+val.SUBMISSION_DEADLINE+'</td>';
        tmp_table += '<td>'+val.VENDORS_PARTICIPATION+'</td>';
        tmp_table += '<td>'+val.RESPONSES+'</td>';
        tmp_table += '<td><a href = "#" data-action-path="messaging/mail/index/'+ val.MESSAGE_INDEX_PARAM +'" class = "cls_action" data-crumb-text="Messages">'+val.UNREAD_MESSAGES+'</a></td>';
        tmp_table += '<td>'+val.STATUS_NAME+'</td>';
        tmp_table += '<td>'+val.DATE_CREATED+'</td>';
        tmp_table += '<td><a href = "#" data-action-path = "'+val.ACTION_PATH+'/'+val.RFQRFB_ID+'" class = "cls_action">'+val.ACTION_LABEL+'<a/></td>';
        tmp_table += "</tr>";
    });

    $('#'+table_name+' tbody').html(tmp_table);
    create_pagination_proto(page_no,Math.ceil(ndata.resultscount/10),pagination_name);
    $('#'+table_name).parent('div').removeClass('disabledbutton');
    }else{
     let tmp_table = '<tr><td colspan = "3">No Results Found!</td></tr>';
    $('#'+table_name+' tbody').html(tmp_table);
    create_pagination_proto(page_no,Math.ceil(ndata.resultscount/10),pagination_name);
    $('#'+table_name).parent('div').removeClass('disabledbutton');

    }
}

function get_rfq_status(){

let ajax_type = 'get';
        let url = 'common/common/get_status';
        let params = {};
        let success_function = function (responseText) {
            let obj = $.parseJSON(responseText);
            let rfq_status =  [];
            let temp_option = '<option value selected="selected">All</option>';
            obj.forEach(function(val){
                if(val.STATUS_TYPE == 2){
                   // rfq_status.push(val)
                   temp_option += '<option value = "'+val.STATUS_NAME+'" data-id = "'+val.STATUS_ID+'">'+ val.STATUS_NAME +'</option>'
                }
            });     
            rfq_status = {
                data: rfq_status
            }
        // create_mustache('rfq_status_temp','rfb_rfq_status',rfq_status);not working idk why.

        $('#rfb_rfq_status').html(temp_option);

        }
        return ajax_request(ajax_type, url, params, success_function);
}

function create_mustache(template_name,element_name,data){
        let template = document.getElementById('' + template_name + '').innerHTML;
        let htmls = Mustache.render(template,data);
        let element = document.getElementById('' + element_name + '');
        element.innerHTML = htmls;
}

function get_rfq_rfb_table(params,table_name,pag_name,pag_no = 0){
    let user_type_id = document.getElementById('user_type_id').value;
    //let url = (user_type_id == 1) ? 'rfqb/rfq_main/rfqrfbmain_table' : 'rfqb/rfq_main/rfqrfbmain_vendor_table'; // if user type is SM then use main table query, if vendor, user vendor query
    $('#'+table_name).parent('div').addClass('disabledbutton');
    /*alert(user_type_id);*/
    let url = 'rfqb/rfq_main/rfqrfbmain_table';


    if(user_type_id != 1){
        url = 'rfqb/rfq_main/rfqrfbmain_vendor_table';
    }


    let success_function = function(result){
      let page_num = 0;

      if(typeof(params) == 'string' && pag_no == 0){
        page_num = 0;
      }else if(pag_no != 0){
        page_num = pag_no;
      }else{
        page_num = params.page_no; 
      }
        create_rfq_table(result,table_name,pag_name,page_num);
    }

    return ajax_request('POST', url, params, success_function);
}

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  if(text == null){
	  return "";
  }
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function redirect_to_login(){

    location.href = document.getElementById('base_url').value +'login';

}






