
var messaging = (function ()
{
	
	let $container = $('.mycontainer');

	let $modal_new_message = $('#new_message');
	let $form_new_message = $modal_new_message.find('#new_message_form');
	let $select_recipient = $modal_new_message.find('#recipient');
	let $new_msg_subjects = $modal_new_message.find('#mail_subj');

	let $modal_reply_message = cache.set('modal_reply_message', $('#reply_message'));
	cache.set('form_reply_message', $modal_reply_message.find('#reply_message_form'));
	cache.set('reply_mail_subj', $modal_reply_message.find('#mail_subj'));
	cache.set('reply_mail_topic', $modal_reply_message.find('#mail_topic'));
	cache.set('reply_mail_recipient', $modal_reply_message.find('#mail_recipient'));
	cache.set('reply_box', $modal_reply_message.find('#reply_box'));
	cache.set('reply_box_default', cache.get('reply_box').html());

	let $btn_send_message = $modal_new_message.find('#send_message');
	let $btn_reply_message = $modal_reply_message.find('#send_message');

	let $filter_div = $container.find('#filter_div');
	let $span_sender_filter = $filter_div.find('#sender_filter');
	let $select_senders = $filter_div.find('#mail_sender');
	let $select_subject = $filter_div.find('#mail_subj');
	let $btn_search_topic = $filter_div.find('#search_topic');

	let $nav_message_actions = $container.find(".nav.message-actions");
	let $btn_archive_message = $nav_message_actions.find('#archive_message');
	let $badge_unread_count = cache.set('badge_unread_count', $('.unread_count')); // need to update menu as well

	let $tbl_messages = $container.find('#messages');

	let $thead_messages = $tbl_messages.find('thead');
	let $th_status = $thead_messages.find('th[data-col="STATUS"]');
	let $th_sender_type = $thead_messages.find('#sender_type');
	let $th_date_type = $thead_messages.find('#date_type');
	let $chk_select_all = $thead_messages.find('#select_all');
	let $tbody_messages = $tbl_messages.find('tbody');
	let $inbox_outbox_pagination = $('#inbox_outbox_pagination');

	// MUSTACHE TEMPLATES
	let SENDERS_TEMPLATE = $select_senders.find('#senders_template').html();
	let SUBJECTS_TEMPLATE = $select_subject.find('#subjects_template').html();

	// DATA
	let select_option = '<option value="">PLEASE SELECT</option>';
	let inbox_outbox_pagination = new Pagination($inbox_outbox_pagination, $tbl_messages, 'sort_columns');
	let data_id = $container.find('#data_id').val();
	let data_type = $container.find('#data_type').val();
	let user_type_id = $container.find('#user_type_id').val();
	cache.set('mail_type', 'inbox');

	$filter_div.on('change', '.messages_filter', filter_messages);
	
	$modal_new_message.on('change', '#mail_subj', function(){
		//filter_topic();
		filter_recipient();
	});

	$btn_search_topic.on('click', function() {
		loading($(this), 'in_progress');
		filter_messages();
	});

	$nav_message_actions.find('a.mailbox_action').on("click", function() {
		let this_el = $(this);

		$nav_message_actions.find(".active").removeClass("active");
		this_el.parent().addClass("active");
		$filter_div.find('select.messages_filter').val('all');
		$filter_div.find('input.messages_filter').val('');

		if (this_el.data('mailType') === 'outbox') {
			$th_sender_type.html('To');
			$span_sender_filter.html('To');
			$th_date_type.html('Date Sent');
			$th_status.hide();
			cache.set('mail_type', 'outbox');

			inbox_outbox_pagination.create(cache.get('outbox'), 'ResultSet');

		} else if(this_el.data('mailType') === 'archives') {
			$th_sender_type.html('From');
			$span_sender_filter.html('From');
			$th_date_type.html('Date Received');
			$th_status.show();
			cache.set('mail_type', 'archives');
			
			inbox_outbox_pagination.create(cache.get('archives'), 'ResultSet');
			
		}else {
			$th_sender_type.html('From');
			$span_sender_filter.html('From');
			$th_date_type.html('Date Received');
			$th_status.show();
			cache.set('mail_type', 'inbox');

			inbox_outbox_pagination.create(cache.get('inbox'), 'ResultSet');
		}

		inbox_outbox_pagination.render();
	});

	$btn_archive_message.on('click', function() {

		let $checked_messages = cache.set('checked_messages', $tbody_messages.find(':checkbox:checked'));
		if($checked_messages.length == 0){
			notify('Please Select Message(s) to be Archived.', 'info');
			return;
		}

		notify('Are you sure? <input type="button" id="confirm_archive" value="YES"> <input type="button" id="close_alert" value="NO">', 'warning', 1);
	});

	cache.get('div_alerts').on('click', '#confirm_archive', archive_message);

	$chk_select_all.on('change', function () {
		$tbody_messages.find(':checkbox').prop('checked', $(this).prop('checked'));
	});
	
	$tbody_messages.on('click', 'tr.user_mail', function(event)
	{
		let this_el = $(this);
		let mail_type = cache.get('mail_type');
		
		//jay
		var message_id = $(this).attr('data-message-id');
		//console.log(user_id);
		inbox_outbox_pagination.update_read_status(message_id);
		//end
		
		common_mail.show_reply_modal(this_el, mail_type, event);
	});

	$btn_send_message.on('click', send_message);
	$btn_reply_message.on('click', function() {
		common_mail.reply_message().done(function() {
			// change UI update from calling get_messages_data function to just adding the new data to DOM using Mustache template
			get_messages_data().done(function() {
				$modal_reply_message.modal('hide');
				notify('Reply Successfully Sent!', 'success');
			});
		})
	});

	get_messages_data().done(function() {
		if (data_id != '' && data_type != '') {
			$select_subject.find('option[data-subj-id="' + data_id + '"][data-subj-type="' + data_type + '"]').prop('selected', 'selected');
			filter_messages();
		}

		loadingScreen('off');
	});

	// CORE FUNCTIONS
	function get_messages_data()
	{
		loadingScreen('on');

		let url = BASE_URL + 'messaging/mail/get_messages_data';
		let ajax_type = 'get';
		let params = {};
		if($("#data_id").val() != ''){
			url = BASE_URL + 'messaging/mail/get_messages_data/' + $("#data_id").val();
		}
		
		let success_function = function (responseText) {
			console.log(responseText);
		
			let obj = $.parseJSON(responseText);
			let DATA = {
				ResultSet: ''
			}

			// for vendor employees only: admin employees do not have any vendors/rfb_frq
			if (user_type_id === '2') {
				DATA.ResultSet = obj.vendor_subjects;
				
				$new_msg_subjects.html(select_option + Mustache.render(SUBJECTS_TEMPLATE, DATA));
				$new_msg_subjects.find('option[value="all"]').remove();
			}

			if (user_type_id === '1') {
				DATA.ResultSet = obj.subjects_filter;
				
				$new_msg_subjects.html(select_option + Mustache.render(SUBJECTS_TEMPLATE, DATA));
				$new_msg_subjects.find('option[value="all"]').remove();
			}

			let tmpn = [];
			Object.keys(obj.subjects_filter).map(function(objectKey, index) {
			var value = obj.subjects_filter[objectKey].SUBJECT;
			if(tmpn.length == 0){
			tmpn.push(obj.subjects_filter[objectKey]);	
			}else{
				let i = 0;
				for(i = 0;i<tmpn.length;i++){
					if(test(tmpn[i].SUBJECT,value) == true){
						break;
					}
					if(i == (tmpn.length-1)){
						tmpn.push(obj.subjects_filter[objectKey]);
					}

				}
			}
	
			});
			DATA.ResultSet = tmpn;
		
			
			
			$select_subject.html(Mustache.render(SUBJECTS_TEMPLATE, DATA));

			DATA.ResultSet = obj.from;
			$select_senders.html(Mustache.render(SENDERS_TEMPLATE, DATA));
			DATA.ResultSet = obj.senders;
			$select_recipient.html(select_option + Mustache.render(SENDERS_TEMPLATE, DATA));
	//		$select_recipient.find('option[value="all"]').remove();*/
			
			cache.set('inbox', obj.inbox);
			cache.set('outbox', obj.outbox);
			cache.set('archives', obj.archives);
		
			if (cache.get('mail_type') === 'inbox') {
				inbox_outbox_pagination.create(obj.inbox, 'ResultSet');
			}
			else if (cache.get('mail_type') === 'archives') {
				inbox_outbox_pagination.create(obj.archives, 'ResultSet');
			}
			else { // outbox
				inbox_outbox_pagination.create(obj.outbox, 'ResultSet');
			}
			//console.log(obj.inbox[0].STATUS);
			inbox_outbox_pagination.render();
			common_mail.update_unread(obj.unread_count, $badge_unread_count);

			loadingScreen('off');
		}

		return ajax_request(ajax_type, url, params, success_function);
	}

	function test(a1,a2){

		if(a1 == a2){
			return true;
		}else{
			return false;
		}

	}

	function send_message()
	{
		let message_data_id = $new_msg_subjects.val();
		let $selected_option = $new_msg_subjects.find('option[value="' + message_data_id + '"]');
		let subj_option_data = $selected_option.data();

		$form_new_message
			.submit((e) => e.preventDefault()) // prevent submission and reloading of page
			.find('[type="submit"]').trigger('click');

		// if there are invalid inputs (blanks), do not proceed
		if($form_new_message.find(":invalid").length > 0) {
			return;
		}

		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/send_message';
		let params = $form_new_message.serialize();

			if (subj_option_data) {
				
				if (user_type_id === '2') 
					params += '&recipient=' 	+ subj_option_data.recipientId;

				params += '&subject_type=' 	+ encodeURIComponent(subj_option_data.subjType);
				
				switch (subj_option_data.subjType) {
					case 'VENDOR':
					case 'vendor':
						params += '&vendor_id=' + subj_option_data.subjId;
						break;
					case 'VENDOR_INVITE':
					case 'invite':
						params += '&invite_id=' + subj_option_data.subjId;
					case 'RFQ_RFB':
					case 'rfqrfb':
						params += '&rfqrfb_id=' + subj_option_data.subjId;
						break;
				}
			}

		let success_function = function (responseText)
		{
			$form_new_message.trigger("reset");

			// change UI update from calling get_messages_data function to just adding the new data to DOM using Mustache template
			get_messages_data().done(function() {
				$modal_new_message.modal('hide');
				notify('Message Successfully Sent!', 'success');
			});
		}

		ajax_request(ajax_type, url, params, success_function);
	}

	/* ---------- */

	function filter_topic()
	{
		let message_ids = '';
		
		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/filter_topic';

		let message_data_id = $new_msg_subjects.val();
		let $selected_option = $new_msg_subjects.find('option[value="' + message_data_id + '"]');
		let subj_option_data = $selected_option.data();

		let params = '';

		if (subj_option_data) {
			params += 'senderid=' + encodeURIComponent(subj_option_data.recipientId);
			params += '&subject_type=' + encodeURIComponent(subj_option_data.subjType);
			switch (subj_option_data.subjType) {
				case 'VENDOR':
					params += '&vendor_id=' + subj_option_data.subjId;
					break;
				case 'VENDOR_INVITE':
					params += '&invite_id=' + subj_option_data.subjId;
				case 'RFQ_RFB':
					params += '&rfqrfb_id=' + subj_option_data.subjId;
					break;
			}
		}
		//console.log(params);
		let success_function = function (responseText) {
			$modal_new_message.find('#mail_topic').val(responseText);
		}

		ajax_request(ajax_type, url, params, success_function);
	}

	function filter_recipient()
	{
		//alert(1);

		let message_ids = '';
		
		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/filter_recipient';

		let message_data_id = $new_msg_subjects.val();
		let $selected_option = $new_msg_subjects.find('option[value="' + message_data_id + '"]');
		let subj_option_data = $selected_option.data();

		let params = '';

		if (subj_option_data) {
			params += 'senderid=' + encodeURIComponent(subj_option_data.recipientId);
			params += '&subject_type=' + encodeURIComponent(subj_option_data.subjType);
			switch (subj_option_data.subjType) {
				case 'vendor':
					params += '&vendor_id=' + subj_option_data.subjId;
					break;
				case 'invite':
					params += '&invite_id=' + subj_option_data.subjId;
					break;
				case 'rfqrfb':
					params += '&rfqrfb_id=' + subj_option_data.subjId;
					break;
			}
		}
		
		let success_function = function (responseText) {
			let obj = $.parseJSON(responseText);

			//console.log(responseText);
			let DATA = {
				ResultSet: ''
			}
			
			//$modal_new_message.find('#mail_topic').val(responseText);
			DATA.ResultSet = obj.recipient_result;
			$select_senders.html(Mustache.render(SENDERS_TEMPLATE, DATA));
			$select_recipient.add('option[value="all"]');
			$select_recipient.html(Mustache.render(SENDERS_TEMPLATE, DATA));
		}

		ajax_request(ajax_type, url, params, success_function);
	}

	function archive_message()
	{
		let message_ids = '';
		cache.get('checked_messages').each(function() {
			message_ids += $(this).val() + ',';
		});
		message_ids = message_ids.slice(0, message_ids.length - 1); // remove comma at the end by only getting the right string

		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/archive_message';
		let params = {
			message_ids: message_ids
		};
		let success_function = function (responseText) {
			get_messages_data().done(function() {
				notify('Message(s) Archived.', 'info');
			});
		}

		ajax_request(ajax_type, url, params, success_function);
	}

	function filter_messages()
	{
		let rs = '';
		let mail_type = '';
		let mail_from = '';
		let mail_subj = '';
		let mail_topic = '';
		let filtered_data;
		
		if($(this).attr('id') == 'mail_type'){
			var mail_type_val = $("#mail_type").val();
			if(mail_type_val == 'message' || mail_type_val == 'all'){
				$("#mail_sender").val('all');
				$("#mail_sender").removeAttr('disabled');
			}else if (mail_type_val == 'notification'){
				$("#mail_sender").val('all');
				$("#mail_sender").attr('disabled', 'disabled');
			}
		}

		$filter_div.find('.messages_filter').each(function() {
			let this_val = $(this).val();

			if (this_val != 'all')
			{
				switch($(this).prop('id')) {
					case 'mail_type':
						mail_type = this_val;
					break;
					case 'mail_sender':
						mail_from = this_val;
					break;
					case 'mail_subj':
						mail_subj = this_val;
					break;
					case 'mail_topic':
						mail_topic = this_val;
				}
			}
		});

		if (cache.get('mail_type') === 'inbox') {
			filtered_data = cache.get('inbox');
		}
		else if (cache.get('mail_type') === 'outbox') {
			filtered_data = cache.get('outbox');
		}



		if (mail_from !== '') {
			filtered_data = (filtered_data).filter(function(row) {
				return row.MSG_USER_ID === mail_from;
			});
		}

		if (mail_type !== '') {
			filtered_data = (filtered_data).filter(function(row) {
				return row.TYPE === mail_type;
			});
		}


		if (mail_subj !== '') {
			filtered_data = (filtered_data).filter(function(row) {
				return row.SUBJECT === mail_subj;
			});
		}

		if (mail_topic !== '') {
			filtered_data = (filtered_data).filter(function(row) {
				return (row.TOPIC).toLowerCase().includes(mail_topic.toLowerCase());
			});
		}

		filtered_data = (filtered_data).filter(function(row) {
			return row.STATUS;
			//return row.STATUS == 'mail_unread';
		});

	

		loading($btn_search_topic, 'done');

		inbox_outbox_pagination.create(filtered_data, 'ResultSet');
		inbox_outbox_pagination.render();

	}

	return {
		get_messages_data: get_messages_data
	}
})();