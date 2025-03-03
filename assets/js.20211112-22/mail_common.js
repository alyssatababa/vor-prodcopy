var common_mail = (function ()
{
	let $unread_count = $('nav span.unread_count');

	const MILLISECONDS = 1000;
	const SECONDS = 60;

	get_unread_count();

	function get_unread_count()
	{
		let ajax_type = 'get';
		let url = BASE_URL + 'messaging/mail/get_unread_count';
		let param = {
			check_unread: 1
		}
		let success_function = function(responseText) {
			let obj = $.parseJSON(responseText);
			update_unread(obj.unread_count);

			setTimeout(get_unread_count, SECONDS * MILLISECONDS);
		}

		ajax_request(ajax_type, url, param, success_function);
	}

	function update_unread(unread_count, unread_badge_el = '')
	{
		unread_count = (unread_count > 0) ? unread_count : '';

		if (unread_badge_el === '') {
			$unread_count.html(unread_count);
		}
		else {
			unread_badge_el.html(unread_count);
		}
	}

	function show_reply_modal(this_el, mail_type, event)
	{
		let message_id = cache.set('message_id', this_el.data('messageId'));

		if (event.target.className == 'mail_chkbx') {
			this_el.find(':checkbox').prop('checked', !this_el.find(':checkbox').prop('checked'));
			return;
		}
		else if (event.target.type == 'checkbox') {
			return;
		}

		let reply_box_default = cache.get('reply_box_default');
		let reply_message_data = cache.get(mail_type).filter((row) => row.ID == message_id)[0];

		let message_type = reply_message_data.TYPE;
		cache.get('reply_mail_subj').val(reply_message_data.SUBJECT);
		cache.get('reply_mail_topic').val('RE: ' + reply_message_data.TOPIC);
		cache.get('reply_mail_recipient').val(reply_message_data.SENDER_RECIPIENT);
		cache.set('reply_recipient_id', reply_message_data.RECIPIENT_ID);
		cache.get('reply_box').html(reply_box_default).find('#previous_mail').html('Sent on - ' + reply_message_data.MAIL_DATE + '<br>' + reply_message_data.BODY + '<br><br>');

		if (this_el.hasClass('info')) {
			mark_as_read(message_id, this_el);
		}

		cache.get('modal_reply_message').modal('show');

		if (message_type.toLowerCase() === 'message') {
			cache.get('modal_reply_message').find('label[for="mail_recipient"]').html('To');
			cache.get('modal_reply_message').find('#title').html('Reply to Message');
			cache.get('modal_reply_message').find('#send_message').show();
			cache.get('reply_box').prop('contenteditable', true);

			setInterval(function() {
				cache.get('reply_box').focus();
			}, 0);
		}
		else if (message_type.toLowerCase() === 'notification') {
			cache.get('modal_reply_message').find('label[for="mail_recipient"]').html('From');
			cache.get('modal_reply_message').find('#title').html('Notification');
			cache.get('modal_reply_message').find('#send_message').hide();
			cache.get('reply_box').prop('contenteditable', false);
		}
	}

	function mark_as_read(message_id, mail_el)
	{
		// Update DB
		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/mark_as_read';
		let params = {
			message_id: message_id
		}
		let success_function = function (responseText) {
			let obj = $.parseJSON(responseText);
			// Update UI
			update_unread(obj.unread_count, cache.get('badge_unread_count'));
			let new_img_src = (mail_el.find('img').prop('src')).replace("mail_unread.png", "mail_read.png");
			mail_el.find('img').prop('src', new_img_src);
			mail_el.removeClass('info');
		}

		ajax_request(ajax_type, url, params, success_function);
	}

	function reply_message()
	{
		let ajax_type = 'post';
		let url = BASE_URL + 'messaging/mail/send_message'
		let params = cache.get('form_reply_message').serialize();
			params += '&mail_body=' + cache.get('reply_box').prop('innerText').replace(/\n/g, "<br />");
			params += '&recipient=' + cache.get('reply_recipient_id');
			params += '&parent_message_id=' + cache.get('message_id');
			params += '&type=message';
		let success_function = function(responseText) { };

		return ajax_request(ajax_type, url, params, success_function);
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
	function send_notification({recipient, mail_subj, mail_topic, mail_body, vendor_id = null, invite_id = null, rfqrfb_id = null})
	{
		let ajax_type = 'post';
		let url = 'messaging/mail/send_notification';
		let params = {
			recipient: recipient,
			mail_subj: mail_subj,
			mail_topic: mail_topic,
			mail_body: mail_body,
			vendor_id: vendor_id,
			invite_id: invite_id,
			rfqrfb_id: rfqrfb_id
		}
		let success_function = function (responseText) {}

		ajax_request(ajax_type, url, params, success_function);
	}

	return {
		update_unread: update_unread,
		show_reply_modal: show_reply_modal,
		reply_message: reply_message,
		send_notification: send_notification
	}
})();