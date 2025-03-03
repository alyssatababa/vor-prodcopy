/*
"use strict";*/

var burl = $('#asset_url').val();
var sort = 'DESC';
var sort_type = 'DATE_SENT';
var vendor_sort = 'ASC';
var vendor_sort_type = 'DATE_SORTING_FORMAT';
var rfq_sort = 'DESC';
var rfq_sort_type = 'SUBMISSION_DEADLINE';



$(document).ready(function() {


    let url  = 'messaging/mail/get_mail_table';
    let type = 'POST';
    let post_params = '';

		let status = $('#vendor_status').find(':selected').data('id');

		let post_params1 = {
		dashboard_status_id: status,
		page_no: 0,
		sort: vendor_sort,
		sort_type: vendor_sort_type
		};

		let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};

    	let post_params_rfq = {
    		page_no : 0
    	}


get_table_data(post_params_mail,'inbox_pagination','messagest');
//get_vendor_table(0,'vendor_registrations','vendor_reg_pagination1',post_params1);
get_status('vendor_status');
get_rfq_rfb_table(post_params_rfq,'rfbs_rfqs','rfqrfb_pagination');
get_rfq_status();

} );


$(document).on('click','#inbox_pagination .cl_pag',function(event){
	let n = this.dataset.pg;
	if($(this).attr('disabled') == 'disabled'){
		return;
	}

	let m = (n-1) * 10;


		let post_params_mail = {
    	start : m,
    	length : m  + 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};




get_table_data(post_params_mail,'inbox_pagination','messagest');
event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
});

$(document).on('click','#vendor_reg_pagination1 .cl_pag',function(event){
	let n = this.dataset.pg;
	if($(this).attr('disabled') == 'disabled'){
		return;
	}

	let status = $('#vendor_status').find(':selected').data('id');

	if($('#for_hats_val').val() == 1){
		status = '';
	}
	
	// Added MSF 20200106 (NA)
	if($('#for_vrdstaff_val').val() == 1){
		status = '';
	}

	let override_position_id = document.getElementById('override_position_id').value;
		
		let post_params1 = {
		dashboard_status_id: status,
		page_no: ((n-1) * 10),
		sort: vendor_sort,
		sort_type: vendor_sort_type,
		override_position_id: override_position_id
		};

	get_vendor_table(n-1,'vendor_registrations','vendor_reg_pagination1',post_params1);
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});






$(document).on('change','#msg_notification_type',function(event){




		let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};

	get_table_data(post_params_mail,'inbox_pagination','messagest');//
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

$(document).on('change','#msg_notif_status',function(event){

/*	get_table_data(0,document.getElementById('msg_notification_type').value,this.value);//*/

		let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};

    	get_table_data(post_params_mail,'inbox_pagination','messagest');//
    	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
});

$(document).on('click','#messagest .a_table_header a',function(e){

	e.preventDefault();
	if (e.handled !== true) { //Checking for the event whether it has occurred or not.
	    e.handled = true;

	 sort = $(this).data('sort');
	 sort_type = $(this).data('sort_type');
	 let n = this;
	 let m = $(this).closest('th');
	/* console.log(n);*/

	 $('#messagest .a_table_header').each(function(){

/*	 let x = $(this).find('th:first');*/
	let x = $(this);

	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');

/*	 	console.log(this);
	 	$(this).removeClass('glyphicon-sort-by-attributes-alt');
	 	$(this).addClass('glyphicon-sort-by-attributes');*/
	 });

	 if(n.dataset.sort == 'desc'){
	 	n.dataset.sort = 'asc';
	 	$(n).data('sort','asc');
/*	 	$(n).find('span:first').addClass('glyphicon-sort-by-attributes-alt');
	 	$(n).find('span:first').removeClass('glyphicon-sort-by-attributes');*/
	 	 $(m).removeClass('sort_column sort_default');
	 	 $(m).addClass('sort_column sort_asc');
	 }else{
	 	n.dataset.sort = 'desc';
	 	$(n).data('sort','desc');
/*	 	$(n).find('span:first').removeClass('glyphicon-sort-by-attributes-alt');
	 	$(n).find('span:first').addClass('glyphicon-sort-by-attributes');*/
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_desc');
	 }

	 sort = $(this).data('sort');

	 let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};


	get_table_data(post_params_mail,'inbox_pagination','messagest');//
	}
	e.stopImmediatePropagation();
});

$(document).on('click','#vendor_registrations .a_table_header a',function(e){

	e.preventDefault();
	 if (e.handled !== true) { //Checking for the event whether it has occurred or not.
	        e.handled = true;


	 vendor_sort = $(this).data('sort');
	 vendor_sort_type = $(this).data('sort_type');
	 let m = $(this).closest('th');
	 let n = this;
	 $('#vendor_registrations .a_table_header').each(function(){

	 let x = $(this);

	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');

	 });

	 if(n.dataset.sort == 'desc'){
	 	n.dataset.sort = 'ASC';
	 	$(n).data('sort','ASC');
		$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_asc');
	 }else{
	 	n.dataset.sort = 'desc';
	 	$(n).data('sort','desc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_desc');
	 }

	 	let status = $('#vendor_status').find(':selected').data('id');
	 	if($('#for_hats_val').val() == 1){
	 		status = '';
	 	}
	 	
	 	let override_position_id = document.getElementById('override_position_id').value;

		let post_params1 = {
		dashboard_status_id: status,
		page_no: 0,
		sort: vendor_sort,
		sort_type: vendor_sort_type,
		override_position_id: override_position_id
		};
	 get_vendor_table(0,'vendor_registrations','vendor_reg_pagination1',post_params1);
		}
	e.stopImmediatePropagation();

});



$(document).on('click','#messagest .user_mail',function(event){

	$(this).removeClass('info');
	let y = $(this).find('td:last');
	let n = $(this).find('td:first').find('input:first');
	let m = $(this).find('td:first').find('div:first').html();
	let content = '';
	let to = n.data('to');
	let topic = n.data('topic');
	let subject = n.data('subject');
	let datesent = n.data('sentdate');
	let recipient_id = n.data('recid');

	if(to == 'Portal'){
		$('#mail_recipient_label').html('From');
		$('#send_message').hide();
		$('#real_reply').attr('contenteditable',false);
		$('#title').html('Notification');
	}else{
		$('#mail_recipient_label').html('To');
		$('#send_message').show();
		$('#recipient_id').val(recipient_id);
		$('#message_id').val($(this).data('message-id'));	
		$('#real_reply').attr('contenteditable',true);
		$('#title').html('Reply To Message');
	}

	if(y.find('input:first').val() == 'mail_unread'){
		open_message($(this).data('message-id'));
		y.find('img:first').attr('src',burl+'img/mail_read.png');
		y.find('input:first').val('mal_read');
	}


	content += 'Sent On - ' + datesent +'<br><br>'
	content += m;
	$('#mail_subj').val(subject);
	$('#mail_topic').val(topic);
	$('#mail_recipient').val(to);
	$('#previous_mail').html(replaceNewLine(content));
	$('#reply_message').modal('show'); 
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
	
	//open_message();
});


function send_message_notif(){
/*	console.log(this);
	return;*/


	$('.btn-send').attr('disabled',true);

	let url  = 'messaging/mail/send_message';
    let type = 'POST';

	let message = {
			mail_body: document.getElementById('reply_box').innerText.replace(/(\n)+/g, '<br />'),
			mail_subj: document.getElementById('mail_subj').value,
			mail_topic: 'RE : '+document.getElementById('mail_topic').value,
			parent_message_id:document.getElementById('message_id').value,
			recipient_id: document.getElementById('recipient_id').value
	}

	let success_function =function(responseText){
		$('.btn-send').attr('disabled',false);
		reset_replybox();
		$('#reply_message').modal('toggle');
		notify('Reply Successfully Sent!.','success');

	}
	ajax_request(type, url, message, success_function);
}


function get_status(select_name)
	{
		let ajax_type = 'get';
		let url = 'common/common/get_status';
		let params = {};
		let success_function = function (responseText) {
			let obj = $.parseJSON(responseText);
			let temp_status = '';
				temp_status +=		'<option value="" selected="selected">All</option>';
				obj.forEach(function(val){
					if(val.STATUS_TYPE == 1){				
						temp_status +=		'<option value = "'+val.STATUS_NAME+'" data-id ="'+val.STATUS_ID+'">'+val.STATUS_NAME+'</option>';
					}
				});
				let temp_val = '';
				$('#'+select_name).html(temp_status);
				let pos_id = document.getElementById('position_id').value;
				if(pos_id == 6){//for hats approval :)
				$('#'+select_name +' option').each(function(){

					if($(this).data('id') == 17){
						temp_val = $(this).val();
					}
				});
				}else if(pos_id == 9){
					$('#'+select_name +' option').each(function(){

					if($(this).data('id') == 122){
						temp_val = $(this).val();
					}
				});

				}
				else if(pos_id == 3){
					$('#'+select_name +' option').each(function(){

					if($(this).data('id') == 13){
						temp_val = $(this).val();
					}
				});

				}// Added MSF - 20191105 (IJR-10612)
				else if(pos_id == 4){
					$('#'+select_name +' option').each(function(){

					if($(this).data('id') == 10){
						temp_val = $(this).val();
					}
				});
				}
				
				else if(pos_id == 5){
					$('#'+select_name +' option').each(function(){

					if($(this).data('id') == 15){
						temp_val = $(this).val();
					}
				});

				}

				$(document).ready(function(){
					$('#'+select_name).val(temp_val).change();
				});

			/*let DATA = {
				ResultSet: ''
			}

			DATA.ResultSet = (obj).filter(function(row) {
				return row.STATUS_TYPE == 2;
			});
			$('#rfb_rfq_status').html(Mustache.render($('#rfb_rfq_status').find('script').html(), DATA));

			DATA.ResultSet =  (obj).filter(function(row) {
				return row.STATUS_TYPE == 1;
			});
			$('#vendor_status').html(Mustache.render($('#vendor_status').find('script').html(), DATA));*/
		}

		return ajax_request(ajax_type, url, params, success_function);
	}

	$(document).on('change','#vendor_status',function(event){

		let status = $('#vendor_status').find(':selected').data('id');



		let post_params = {
            dashboard_status_id: status,
            page_no: 0,
            sort: vendor_sort,
            sort_type: vendor_sort_type
        };
		get_vendor_table(0,'vendor_registrations','vendor_reg_pagination1',post_params);
		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

	});


$(document).on('click','#rfqrfb_pagination .cl_pag',function(event){
	let n = this.dataset.pg;
	if($(this).attr('disabled') == 'disabled'){
		return;
	}

	let x = $('#rfb_rfq_status').find(':selected').data('id');
	if(x === undefined){
		x = '';
	}
	n = n-1;

	let params = {
		page_no :  n ,
		cbo_status : x,
		sort_type : rfq_sort_type,
		sort : rfq_sort
	}

	get_rfq_rfb_table(params,'rfbs_rfqs','rfqrfb_pagination');
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

$(document).on('change','#rfb_rfq_status',function(event){
	let n = $(this).find(':selected').data('id');
	if(n === undefined){
		n = '';
	}

	let params = {
		page_no :  0,
		cbo_status : n,
		sort_type : rfq_sort_type,
		sort : rfq_sort
	}
	get_rfq_rfb_table(params,'rfbs_rfqs','rfqrfb_pagination');
	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
});

$(document).on('click','#rfbs_rfqs .a_table_header a',function(e){


	e.preventDefault();
	 if (e.handled !== true) { //Checking for the event whether it has occurred or not.
	        e.handled = true;

	 rfq_sort = $(this).data('sort');
	 rfq_sort_type = $(this).data('sort_type');
	 let n = this;
	 let m = $(this).closest('th');

	 $('#rfbs_rfqs .a_table_header').each(function(){

	 let x = $(this);

	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');

/*	 	console.log(this);
	 	$(this).removeClass('glyphicon-sort-by-attributes-alt');
	 	$(this).addClass('glyphicon-sort-by-attributes');*/
	 });

	 if(n.dataset.sort == 'desc'){
	 	n.dataset.sort = 'asc';
	 	$(n).data('sort','asc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_asc');
	 }else{
	 	n.dataset.sort = 'desc';
	 	$(n).data('sort','desc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_desc');
	 }

	 rfq_sort = $(this).data('sort');

	let x = $('#rfb_rfq_status').find(':selected').data('id');
	if(x === undefined){
		x = '';
	}
	n = n-1;

	let params = {
		page_no :  0 ,
		cbo_status : x,
		sort: rfq_sort,
		sort_type : rfq_sort_type
	}

	get_rfq_rfb_table(params,'rfbs_rfqs','rfqrfb_pagination');
/*
	 let post_params_mail = {
    	start : 0,
    	length : 10,
    	message_type: $('#msg_notification_type').val(),
    	status : $('#msg_notif_status').val(),
    	sort : sort,
    	sort_type : sort_type
    	};*/

}
e.stopImmediatePropagation();
//	get_table_data(post_params_mail,'inbox_pagination','messagest');//
});

// Added MSF 20191129 (NA)
$(document).on('click','#btn_vrd_staff',function(event){
	let doc = document.getElementById('override_position_id');
	if(doc.value == 4){
		$('#btn_vrd_staff').val('For VRD Staff').delay(10);
		doc.value = 0;
		$('#vendor_status_exception_vrd').css({
			'display' : 'none',
			'min-width' : '310px'
		});
		$('#vendor_status').css({
			'display' : ''
		});
		$('#for_vrdstaff_val').val(0);
	}else{
		doc.value = 4;
		$('#btn_vrd_staff').val('Reset Filter').delay(10);
		$('#vendor_status_exception_vrd').css({
			'display' : '',
			'min-width' : '310px'
		});

		$('#vendor_status').css({
			'display' : 'none'
		});

		$('#for_vrdstaff_val').val(1);
	}

	let post_params = {     	
		page_no: 0,
		sort: vendor_sort,
		sort_type: vendor_sort_type,
		override_position_id : doc.value

	};

	if(doc.value != 4){
		$(document).ready(function(){
				$('#vendor_status').val("Submitted").change();
			});
	}else{
		get_vendor_table(0,'vendor_registrations','vendor_reg_pagination1',post_params);
	}

	return ajax_request(ajax_type, url, params, success_function);

	event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

});

$(document).on('click','#btn_hats_approval',function(event){


	let doc = document.getElementById('override_position_id');
	if(doc.value == 6){
		$('#btn_hats_approval').val('For HaTS Approval').delay(10);
			doc.value = 0;
		$('#vendor_status_exception').css({
			'display' : 'none',
			'min-width' : '310px'
		});
		$('#vendor_status').css({
			'display' : ''
		});
		$('#for_hats_val').val(0);
	}else{
		doc.value = 6;
		$('#btn_hats_approval').val('Reset Filter').delay(10);
		$('#vendor_status_exception').css({
			'display' : '',
			'min-width' : '310px'
		});

		$('#vendor_status').css({
			'display' : 'none'
		});

		$('#for_hats_val').val(1);
	}


		let ajax_type = 'get';
		let url = 'vendor/registration/for_hats_approval';
		let params = {};
		let success_function = function(responseText)
		{

		let post_params = {     	
            page_no: 0,
            sort: vendor_sort,
            sort_type: vendor_sort_type,
            override_position_id : doc.value

        };

        if(doc.value != 6){
        	$(document).ready(function(){
					$('#vendor_status').val("For VRD Head Approval").change();
				});
        }else{
        	get_vendor_table(0,'vendor_registrations','vendor_reg_pagination1',post_params);
        }
		

		};

		return ajax_request(ajax_type, url, params, success_function);

		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;

})


function reset_replybox(){

	$('#real_reply').html('');
}

