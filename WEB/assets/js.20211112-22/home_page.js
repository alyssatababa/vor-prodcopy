var home_page = (function()
{
	let $div_my_container = $('.mycontainer');

	let $modal_reply_message = cache.set('modal_reply_message', $('#reply_message'));
	cache.set('form_reply_message', $modal_reply_message.find('#reply_message_form'));
	cache.set('reply_mail_subj', $modal_reply_message.find('#mail_subj'));
	cache.set('reply_mail_topic', $modal_reply_message.find('#mail_topic'));
	cache.set('reply_mail_recipient', $modal_reply_message.find('#mail_recipient'));
	cache.set('reply_box', $modal_reply_message.find('#reply_box'));
	cache.set('reply_box_default', cache.get('reply_box').html());
	let $btn_reply_message = $modal_reply_message.find('#send_message');

	let $div_messages = $div_my_container.find('#messages_div');
	let $table_messages = $div_messages.find('#messages');
	let $tbody_messages = $table_messages.find('tbody');
	let $inbox_pagination = $div_messages.find('#inbox_pagination');

	let $div_rfb_rfq = $div_my_container.find('#rfb_rfq_div');
	let $select_rfb_rfq_status = $div_rfb_rfq.find('#rfb_rfq_status');
	let $table_rfb_rfq = $div_rfb_rfq.find('#rfbs_rfqs');
	let $tbody_rfb_rfq = $table_rfb_rfq.find('tbody');
	let $rfqrfb_pagination = $div_rfb_rfq.find('#rfqrfb_pagination');

	let $div_vendor_regs = $div_my_container.find('#vendor_registrations_div');
	let $select_vendor_status = $div_vendor_regs.find('#vendor_status');
	let $table_vendor_regs = $div_vendor_regs.find('#vendor_registrations');
	let $tbody_vendor_regs = $table_vendor_regs.find('tbody');
	let $vendor_reg_pagination = $div_vendor_regs.find('#vendor_reg_pagination');

	let inbox_pagination = new Pagination($inbox_pagination, $table_messages, 'column_sort');
	let rfqrfb_pagination = new Pagination($rfqrfb_pagination, $table_rfb_rfq, 'column_sort');
	let vendor_reg_pagination = new Pagination($vendor_reg_pagination, $table_vendor_regs, 'column_sort');

	let RFB_RFQ_STATUS_TEMPLATE = $select_rfb_rfq_status.find('script').html();
	let VENDOR_STATUS_TEMPLATE = $select_vendor_status.find('script').html();

	let user_type_id = $div_my_container.find('#user_type_id').val();
	let position_id = $div_my_container.find('#position_id').val();
	const MILLISECONDS = 1000;
	const SECONDS = 60;

	cache.set('mail_type', 'home_inbox');

	$div_messages.on('change', '.messages_filter', function() {
		let type_val = '';
		let status_val = '';

		$div_messages.find('.messages_filter').each(function() {
			let this_val = $(this).val();
			if (this_val != 'all')
			{
				if ($(this).prop('id') == 'msg_notif_type') {
					type_val = this_val;
				}
				else if ($(this).prop('id') == 'msg_notif_status') {
					status_val = this_val;
				}
			}
		});

		let filtered_data = (cache.get('home_inbox')).filter(function(row) {
			if (type_val !== '' && status_val == '') {
				return row.TYPE === type_val;
			}
			else if (type_val == '' && status_val !== '') {
				return row.STATUS === status_val;
			}
			else if (type_val !== '' && status_val !== '') {
				return row.TYPE === type_val && row.STATUS === status_val;
			}
			else {
				return true;
			}
		});

		inbox_pagination.create(filtered_data, 'ResultSet');
		inbox_pagination.render();
	});

	$div_vendor_regs.on('click', '.cls_toggle_hats_override', function() {
		loadingScreen('on');
		// if hats override button clicked, set the override position to hats, so that the result set for the list will include results for hats.
		if ($('#override_position_id').val()==6) {
			$(this).val('For HaTS Approval');
			$select_vendor_status.prop('disabled','');
			$('#vendor_status_exception').css('display', 'none');
			$select_vendor_status.css('display', '');

			$('#override_position_id').val(0);
			cache.set('vendor_status2', '');
			cache.set('vendor_status3', '');
			set_default_status('vendor');
		} else {
			$(this).val('Reset filters');
			$select_vendor_status.prop('disabled','disabled');
			$select_vendor_status.find('option').filter('[data-id="17"]').prop('selected', 'selected'); // for hts approval
			extra_status1 = cache.set('vendor_status2', $select_vendor_status.find('option:selected').text());

			$select_vendor_status.find('option').filter('[data-id="124"]').prop('selected', 'selected'); // suspended by hts
			extra_status2 = cache.set('vendor_status3', $select_vendor_status.find('option:selected').text());

			$select_vendor_status.find('option').filter('[data-id="15"]').prop('selected', 'selected'); // for vrd approval
			default_status = cache.set('vendor_status', $select_vendor_status.find('option:selected').text());

			filter_data('vendor_status', default_status, extra_status1, extra_status2);
			$('#override_position_id').val(6); //hats

		$('#vendor_status_exception').val(default_status + ', ' + extra_status1);
			$('#vendor_status_exception').css('display', '');
			$select_vendor_status.css('display', 'none');
		}

		get_vendor_registrations();
		loadingScreen('off');
	});

	$tbody_messages.on('click', 'tr.user_mail', function(event) {

		let this_el = $(this);
		
		//jay
		var message_id = $(this).attr('data-message-id');
		//console.log(user_id);
		inbox_pagination.update_read_status(message_id);
		//end
		
		common_mail.show_reply_modal(this_el, 'home_inbox', event);
	});

	$btn_reply_message.on('click', function() {
		common_mail.reply_message().done(function() {
			$modal_reply_message.modal('hide');
			notify('Reply Successfully Sent!', 'success');
		})
	});

	$div_my_container.find('select.status_filter').on('change', function() {
		var filter_type = $(this).prop('id'); // use id of select element as filter type
		var filter_value = $(this).val();

		filter_data(filter_type, filter_value);
	});

	if (user_type_id == 2) {
		$div_my_container.find('.btn.btn-default.cls_action').hide();
	}

	if (['2', '3'].includes(position_id)) {
		$div_rfb_rfq.hide();
	}

	loadingScreen('on');
	get_status().done(function()
	{
		$.when(get_messages(), get_rfq_rfb(), get_for_hats_approval(), get_vendor_registrations()).done(function() {
			set_default_status('rfqrfb');
			set_default_status('vendor');
			loadingScreen('off');
		});
	});

	// Gets status of RFQ/RFB and Vendor
	function get_status()
	{
		let ajax_type = 'get';
		let url = 'common/common/get_status';
		let params = {};
		let success_function = function (responseText) {
			//console.log(responseText);
			let obj = $.parseJSON(responseText);
			let DATA = {
				ResultSet: ''
			}

			DATA.ResultSet = (obj).filter(function(row) {
				return row.STATUS_TYPE == 2;
			});
			$select_rfb_rfq_status.html(Mustache.render(RFB_RFQ_STATUS_TEMPLATE, DATA));

			DATA.ResultSet =  (obj).filter(function(row) {
				return row.STATUS_TYPE == 1;
			});
			$select_vendor_status.html(Mustache.render(VENDOR_STATUS_TEMPLATE, DATA));
		}

		return ajax_request(ajax_type, url, params, success_function);
	}

	function set_default_status(type)
	{
		var filter_type;
		var default_status;
		var extra_status;
		var extra_status2;

		if (type == 'rfqrfb') {
			filter_type = 'rfb_rfq_status';

			switch(position_id) {
				case '8': // GROUP HEAD
					var default_status_id = 21; // For Group Head Approval
				break;
				case '9': // FAS HEAD
					var default_status_id = 81; // For FAS Head Approval
				break;
				default:
					return;
				break;
			}

			$select_rfb_rfq_status.find('option').filter('[data-id="' + default_status_id + '"]').prop('selected', 'selected');
			default_status = cache.set('rfb_rfq_status', $select_rfb_rfq_status.find('option:selected').text());
		}
		else if (type == 'vendor') {
			filter_type = 'vendor_status';

			switch(position_id) {
				case '3': // BU/Merchandising Head
					var default_status_id = 13; // For BU Head Approval
				break;
				case '5': // VRD Head
					var default_status_id = 15; // For VRD Head Approval
				break;
				case '6': // HaTS
					var default_status_id = 17; // For HTS Approval
				break;
				case '9': // FAS HEAD
					var default_status_id = 122; // For FAS Head Approval
				break;
				default:
					return;
				break;
			}

			$select_vendor_status.find('option').filter('[data-id="' + default_status_id + '"]').prop('selected', 'selected');
			default_status = cache.set('vendor_status', $select_vendor_status.find('option:selected').text());
			extra_status = cache.get('vendor_status2');
			extra_status2 = cache.get('vendor_status3');
		}

		filter_data(filter_type, default_status, extra_status, extra_status2);
	}

	/* MESSAGES */
	function get_messages()
	{
		let ajax_type = 'get';
		let url = 'messaging/mail/get_messages';
		let params = {
			mail_type: 'inbox',
			get_message_call: true
		};
		let success_function = function(responseText)
		{
			let obj = $.parseJSON(responseText);
			let DATA = {
				ResultSet: ''
			};

			inbox_pagination.create(cache.set('home_inbox', obj.messages), 'ResultSet');
			//inbox_pagination.render();

			common_mail.update_unread(obj.unread_count);

			$div_messages.find('.messages_filter').val('all');

			$inbox_pagination.bootpag({page:inbox_pagination.current_page_num()});
			inbox_pagination.sort_rows((inbox_pagination.get_sort_column() ? inbox_pagination.get_sort_column() : 'MAIL_DATE_FORMATTED'), inbox_pagination.get_sort_type());
			cache.set('get_messages', setTimeout(get_messages, SECONDS * MILLISECONDS)); //MAIL_DATE = MAIL_DATE_FORMATTED
		};

		return ajax_request(ajax_type, url, params, success_function);
	}

	function get_rfq_rfb()
	{
		let ajax_type = 'get';
		let url = (user_type_id == 1) ? 'rfqb/rfq_main/rfqrfbmain_table' : 'rfqb/rfq_main/rfqrfbmain_vendor_table'; // if user type is SM then use main table query, if vendor, user vendor query
		let params = {};
		let success_function = function(responseText)
		{
			let obj = $.parseJSON(responseText);
			let rs = cache.set('rfqrfb_resultset', obj.query);

			if (obj.resultscount > 0)
			{
				(rs).map(function(row_obj)
				{
					if(row_obj.ISPROCESS === '1') {
						row_obj.ISPROCESS = true;
					}
					else if (row_obj.ISPROCESS === '0') {
						row_obj.ISPROCESS = false;
					}
				});
			}

			if (cache.get('rfb_rfq_status')) {
				filter_data('rfb_rfq_status', cache.get('rfb_rfq_status'));
			}
			else {
				if(obj.resultscount > 0)
				{
					rfqrfb_pagination.create(rs, 'ResultSet');
					rfqrfb_pagination.sort_rows((rfqrfb_pagination.get_sort_column() ? rfqrfb_pagination.get_sort_column() : 'DATE_SORTING_FORMAT'), rfqrfb_pagination.get_sort_type());
				}
				//rfqrfb_pagination.render(); DATE_CREATED = DATE_SORTING_FORMAT
			}

			$rfqrfb_pagination.bootpag({page:rfqrfb_pagination.current_page_num()});
			cache.set('get_rfq_rfb', setTimeout(get_rfq_rfb, SECONDS * MILLISECONDS));
		};

		return ajax_request(ajax_type, url, params, success_function);
	}

	function get_vendor_registrations()
	{
		let ajax_type = 'get';
		let url = 'vendor/registration/registrationmain_table';
		let params = {override_position_id:$('#override_position_id').val()};
		let success_function = function(responseText)
		{
			//console.log(responseText);
			let obj = $.parseJSON(responseText);
			let rs = cache.set('vendor_reg_resultset', obj.query);

			if (cache.get('vendor_status')) {
				filter_data('vendor_status', cache.get('vendor_status'), cache.get('vendor_status2'), cache.get('vendor_status3'));
			}
			else {
				if(obj.resultscount > 0)
				{
					vendor_reg_pagination.create(rs, 'ResultSet');
					vendor_reg_pagination.sort_rows((vendor_reg_pagination.get_sort_column() ? vendor_reg_pagination.get_sort_column() : 'DATE_SORTING_FORMAT') , vendor_reg_pagination.get_sort_type());
				}
				//vendor_reg_pagination.render(); DATE_CREATED = DATE_SORTING_FORMAT
			}

			$vendor_reg_pagination.bootpag({page:vendor_reg_pagination.current_page_num()});
			cache.set('get_vendor_registrations', setTimeout(get_vendor_registrations, SECONDS * MILLISECONDS));
		};

		return ajax_request(ajax_type, url, params, success_function);
	}

	function get_for_hats_approval()
	{
		let ajax_type = 'get';
		let url = 'vendor/registration/for_hats_approval';
		let params = {};
		let success_function = function(responseText)
		{
			let obj = $.parseJSON(responseText);

			if (obj.rows>0){
				$('#btn_hats_approval').prop('disabled', false);
			} else {
				$('#btn_hats_approval').prop('disabled', true);
			}

			cache.set('get_for_hats_approval', setTimeout(get_vendor_registrations, SECONDS * MILLISECONDS));
		};

		return ajax_request(ajax_type, url, params, success_function);
	}


	function filter_data(type, filter_val, filter_val2 = '', filter_val3 = '')
	{
		let rs;
		let pagination;
		let pages;
		let DATA;

		if (type == 'rfb_rfq_status') {
			cache.set('rfb_rfq_status', filter_val);

			rs = cache.get('rfqrfb_resultset');
			pages = $rfqrfb_pagination;
			pagination = rfqrfb_pagination;
		}
		else if (type == 'vendor_status') {
			cache.set('vendor_status', filter_val);
			cache.set('vendor_status2', filter_val2);
			cache.set('vendor_status3', filter_val3);

			rs = cache.get('vendor_reg_resultset');
			pages = $vendor_reg_pagination;
			pagination = vendor_reg_pagination;
		}

		pages.show();

		if (filter_val != '' && rs.length > 0)
		{
			DATA = rs.filter(function(row) {
				return (row.STATUS_NAME == filter_val || row.STATUS_NAME == filter_val2 || row.STATUS_NAME == filter_val3);
			});

			if (DATA.length == 0) {
				pages.hide();
			}
		}
		// ALL option is selected
		else {
			DATA = rs;
		}

		pagination.create(DATA, 'ResultSet');
		pagination.render();
	}

})();
