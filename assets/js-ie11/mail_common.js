'use strict';

var common_mail = function () {
	var $unread_count = $('nav span.unread_count');

	var MILLISECONDS = 1000;
	var SECONDS = 60;

	get_unread_count();

	function get_unread_count() {
		var ajax_type = 'get';
		var url = BASE_URL + 'messaging/mail/get_unread_count';
		var param = {
			check_unread: 1
		};
		var success_function = function success_function(responseText) {
			var obj = $.parseJSON(responseText);
			update_unread(obj.unread_count);
			setTimeout(get_unread_count, SECONDS * MILLISECONDS);
		};

		ajax_request(ajax_type, url, param, success_function);
	}

	function update_unread(unread_count) {
		var unread_badge_el = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

		unread_count = unread_count > 0 ? unread_count : '';

		if (unread_badge_el === '') {
			$unread_count.html(unread_count);
		} else {
			unread_badge_el.html(unread_count);
		}
	}

	function show_reply_modal(this_el, mail_type) {
		var message_id = cache.set('message_id', this_el.data('messageId'));

		if (event.target.className == 'mail_chkbx') {
			this_el.find(':checkbox').prop('checked', !this_el.find(':checkbox').prop('checked'));
			return;
		} else if (event.target.type == 'checkbox') {
			return;
		}

		var reply_box_default = cache.get('reply_box_default');
		var reply_message_data = cache.get(mail_type).filter(function (row) {
			return row.ID == message_id;
		})[0];

		cache.get('reply_mail_subj').val(reply_message_data.SUBJECT);
		cache.get('reply_mail_topic').val('RE: ' + reply_message_data.TOPIC);
		cache.get('reply_mail_recipient').val(reply_message_data.SENDER_RECIPIENT);
		cache.set('reply_recipient_id', reply_message_data.RECIPIENT_ID);
		cache.get('reply_box').html(reply_box_default).find('#previous_mail').html('Sent on - ' + reply_message_data.MAIL_DATE + '<br>' + reply_message_data.BODY + '<br><br>');

		if (this_el.hasClass('info')) {
			mark_as_read(message_id, this_el);
		}

		cache.get('modal_reply_message').modal('show');
		setInterval(function () {
			cache.get('reply_box').focus();
		}, 0);
	}

	function mark_as_read(message_id, mail_el) {
		// Update DB
		var ajax_type = 'post';
		var url = BASE_URL + 'messaging/mail/mark_as_read';
		var params = {
			message_id: message_id
		};
		var success_function = function success_function(responseText) {
			var obj = $.parseJSON(responseText);
			// Update UI
			update_unread(obj.unread_count, cache.get('badge_unread_count'));
			mail_el.removeClass('info');
		};

		ajax_request(ajax_type, url, params, success_function);
	}

	function reply_message() {
		var ajax_type = 'post';
		var url = BASE_URL + 'messaging/mail/send_message';
		var params = cache.get('form_reply_message').serialize();
		params += '&mail_body=' + cache.get('reply_box').prop('innerText').replace(/\n/g, "<br />");
		params += '&recipient=' + cache.get('reply_recipient_id');
		params += '&parent_message_id=' + cache.get('message_id');
		var success_function = function success_function(responseText) {};

		return ajax_request(ajax_type, url, params, success_function);
	}

	function sort_column(col_name, pagination) {
		var mail_type = cache.get('mail_type');
		var sort_type = get_col_sort_type(col_name, mail_type);
		var mail_formatted_type = void 0;
		switch (mail_type) {
			case 'inbox':
				mail_formatted_type = 'inbox_formatted';
				break;

			case 'outbox':
				mail_formatted_type = 'outbox_formatted';
				break;

			case 'home_inbox':
				mail_formatted_type = 'home_inbox_formatted';
				mail_type = 'inbox';
				break;
		}

		var ajax_type = 'get';
		var url = 'messaging/mail/get_messages';
		var params = {
			mail_type: mail_type,
			sort_column: col_name,
			sort_type: sort_type
		};
		var success_function = function success_function(responseText) {
			var obj = $.parseJSON(responseText);

			pagination.create(cache.set(mail_type, obj.messages));
			pagination.render();

			update_unread(obj.unread_count, cache.get('badge_unread_count'));
		};

		ajax_request(ajax_type, url, params, success_function);
	}

	function get_col_sort_type(col_name, mail_type) {
		var col_sort_cache_name = mail_type + '_' + col_name + '_sort_type';
		var sort_type = cache.get(col_sort_cache_name);

		if (sort_type !== undefined) {
			if (sort_type === 'ASC') {
				sort_type = cache.set(col_sort_cache_name, 'DESC');
			} else {
				// desc
				sort_type = cache.set(col_sort_cache_name, 'ASC');
			}
		} else {
			sort_type = cache.set(col_sort_cache_name, 'ASC'); //  if not sort is defined for the column, use asc as default
		}

		return sort_type;
	}

	/*
 	how to use. optional: vendor_id, invite_id, rfqrfb_id
 	1) use an obj
 		e.g. let notif_data = {
 			recipient: <data>,
 			mail_subj: <data>,
 			mail_topic: <data>,
 			mail_body: <data>,
 			vendor_id: <data>,
 			invite_id: <data>,
 			rfqrfb_id: <data>
 		}
 		common_mail.send_notification(notif_data);
 	OR
 	2) use direct object in param
 		common_mail.send_notification({recipient: <data>, mail_subj: <data>, mail_topic: <data>, mail_body: <data>, vendor_id: <data>, invite_id: <data>, rfqrfb_id: <data>})
 */
	function send_notification(_ref) {
		var recipient = _ref.recipient,
		    mail_subj = _ref.mail_subj,
		    mail_topic = _ref.mail_topic,
		    mail_body = _ref.mail_body,
		    _ref$vendor_id = _ref.vendor_id,
		    vendor_id = _ref$vendor_id === undefined ? null : _ref$vendor_id,
		    _ref$invite_id = _ref.invite_id,
		    invite_id = _ref$invite_id === undefined ? null : _ref$invite_id,
		    _ref$rfqrfb_id = _ref.rfqrfb_id,
		    rfqrfb_id = _ref$rfqrfb_id === undefined ? null : _ref$rfqrfb_id;

		var ajax_type = 'post';
		var url = 'messaging/mail/send_notification';
		var params = {
			recipient: recipient,
			mail_subj: mail_subj,
			mail_topic: mail_topic,
			mail_body: mail_body,
			vendor_id: vendor_id,
			invite_id: invite_id,
			rfqrfb_id: rfqrfb_id
		};
		var success_function = function success_function(responseText) {};

		ajax_request(ajax_type, url, params, success_function);
	}

	return {
		update_unread: update_unread,
		show_reply_modal: show_reply_modal,
		reply_message: reply_message,
		sort_column: sort_column,
		send_notification: send_notification
	};
}();