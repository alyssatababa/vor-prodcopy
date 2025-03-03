<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';

class Mail extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('mail_model');
		$this->load->model('common_model');
	}

	function vendor_subjects_get() {
		$user_id = $this->get('user_id');
		$user_type_id = $this->get('user_type_id');

		$subjects_data = $this->mail_model->get_vendor_subjects($user_id, $user_type_id);

		$data['subjects'] = $subjects_data;
		$data['status'] = 'success';

		$this->response($data);
	}

	function filter_topic_get() {

		$get_data['user_id'] = $this->get('user_id');
		
		$get_data['senderid'] = $this->get('senderid');
		$get_data['subject_type'] = $this->get('subject_type');
		$get_data['vendor_id'] = $this->get('vendor_id');
		$get_data['invite_id'] = $this->get('invite_id');
		$get_data['rfqrfb_id'] = $this->get('rfqrfb_id');

		$topic_data = $this->mail_model->get_topic($get_data);

		$data['topic'] = $topic_data;
		$data['status'] = 'success';

		$this->response($data);
	}

	function filter_recipient_get() {

		$get_data['user_id'] = $this->get('user_id');
		
		$get_data['senderid'] = $this->get('senderid');
		$get_data['subject_type'] = $this->get('subject_type');
		$get_data['vendor_id'] = $this->get('vendor_id');
		$get_data['invite_id'] = $this->get('invite_id');
		$get_data['rfqrfb_id'] = $this->get('rfqrfb_id');

		$recipient_data = $this->mail_model->get_recipient($get_data);

		$data['recipient'] = $recipient_data;
		$data['status'] = 'success';

		$this->response($data);
	}

	function subjects_filter_get() {
		$user_id = $this->get('user_id');

		$subjects_data = $this->mail_model->get_subjects_filter($user_id);

		$data['subjects'] = $subjects_data;
		$data['status'] = 'success';

		$this->response($data);
	}

	function senders_get() {
		$user_id = $this->get('user_id');
		$user_type_id = $this->get('user_type_id');
		$posid = $this->get('position_id');

		$senders_data = $this->mail_model->get_senders($user_id, $user_type_id, $posid);
		$data['senders'] = $senders_data;
		$data['status'] = 'success';

		$this->response($data);
	}
	
	function from_get() {
		$user_id = $this->get('user_id');
		$user_type_id = $this->get('user_type_id');
		$posid = $this->get('position_id');

		$from_data = $this->mail_model->get_from($user_id, $user_type_id, $posid);
		$data['from'] = $from_data;
		$data['status'] = 'success';

		$this->response($data);
	}

	function unread_count_get() {
		$user_id = $this->get('user_id');

		$unread_count = $this->mail_model->count_unread($user_id);
		$data['count'] = $unread_count['num_rows'];

		$this->response($data);
	}

	function inbox_get() {
		$model_data['user_id'] = $this->get('user_id');
		$model_data['filter_type'] = $this->get('filter_type');
		$model_data['filter_value'] = $this->get('filter_value');
		$model_data['sort_column'] = $this->get('sort_column');
		$model_data['sort_type'] = $this->get('sort_type');
		$model_data['data_id'] = $this->get('data_id');

		$messages_data = $this->mail_model->get_inbox($model_data);
		$data['messages'] = $messages_data['rs'];
		$data['count'] = $messages_data['num_rows'];
		$data['last_query'] = $messages_data['last_query'];
		$data['status'] = 'success';

		$this->response($data);
	}

	function outbox_get() {
		$model_data['user_id'] = $this->get('user_id');
		$model_data['filter_type'] = $this->get('filter_type');
		$model_data['filter_value'] = $this->get('filter_value');
		$model_data['sort_column'] = $this->get('sort_column');
		$model_data['sort_type'] = $this->get('sort_type');
		$model_data['data_id'] = $this->get('data_id');
		
		$sent_messages_data = $this->mail_model->get_outbox($model_data);
		$data['messages'] = $sent_messages_data['rs'];
		$data['count'] = $sent_messages_data['num_rows'];
		$data['last_query'] = $sent_messages_data['last_query'];
		$data['status'] = 'success';

		$this->response($data);
	}

	function archive_get() {
		$model_data['user_id'] = $this->get('user_id');
		$model_data['filter_type'] = $this->get('filter_type');
		$model_data['filter_value'] = $this->get('filter_value');
		$model_data['sort_column'] = $this->get('sort_column');
		$model_data['sort_type'] = $this->get('sort_type');
		$model_data['data_id'] = $this->get('data_id');
		
		$sent_messages_data = $this->mail_model->get_archive($model_data);
		$data['messages'] = $sent_messages_data['rs'];
		$data['count'] = $sent_messages_data['num_rows'];
		$data['last_query'] = $sent_messages_data['last_query'];
		$data['status'] = 'success';
		//echo $this->db->last_query();
		$this->response($data);
	}

	function message_post() {

		if ($this->post('parent_message_id')) {
			$ids = $this->mail_model->get_ids($this->post('parent_message_id'));
		}

		$message_data['TYPE'] = $this->post('type');
		$message_data['PARENT_MESSAGE_ID'] = $this->post('parent_message_id');
		$message_data['SENDER_ID'] = $this->post('user_id');
		$message_data['SUBJECT'] = $this->post('mail_subj');
		$message_data['TOPIC'] = urlencode($this->post('mail_topic'));
		$message_data['BODY'] = urlencode($this->post('mail_body'));
		$message_data['DATE_SENT'] = date('Y-m-d H:i:s');
		$message_data['VENDOR_ID'] = ($message_data['PARENT_MESSAGE_ID']) ? $ids['VENDOR_ID'] : $this->post('vendor_id');
		$message_data['INVITE_ID'] = ($message_data['PARENT_MESSAGE_ID']) ? $ids['INVITE_ID'] : $this->post('invite_id');
		$message_data['RFQRFB_ID'] = ($message_data['PARENT_MESSAGE_ID']) ? $ids['RFQRFB_ID'] : $this->post('rfqrfb_id');

		$recipients = $this->post('recipient_id');
		$arr_recipients = explode('|', $recipients); // for multiple recipients

		foreach ($arr_recipients as $recipient) {
			$message_data['RECIPIENT_ID'] = $recipient;
			$model_data = $this->mail_model->send_message($message_data);
		}


		$this->response('');
	}

	function notification_post() {

		$message_data['TYPE'] = $this->post('type');
		$message_data['SENDER_ID'] = $this->post('user_id');
		$message_data['SUBJECT'] = $this->post('mail_subj');
		$message_data['TOPIC'] = urlencode($this->post('mail_topic'));
		$message_data['BODY'] = urlencode($this->post('mail_body'));
		$message_data['DATE_SENT'] = date('Y-m-d H:i:s');
		$message_data['VENDOR_ID'] = $this->post('vendor_id');
		$message_data['INVITE_ID'] = $this->post('invite_id');
		$message_data['RFQRFB_ID'] = $this->post('rfqrfb_id');


		$recipients = $this->post('recipient_id');
		$arr_recipients = explode('|', $recipients); // for multiple recipients

		foreach ($arr_recipients as $recipient) {
			$message_data['RECIPIENT_ID'] = $recipient;
			$model_data = $this->mail_model->send_message($message_data);
		}


		$this->response('');
	}

	function message_put() {
		$message_id = $this->put('message_id');

		$model_data = $this->mail_model->mark_as_read($message_id);

		$this->response('');
	}

	function archive_message_put() {
		$message_ids = $this->put('message_ids');

		$model_data = $this->mail_model->archive_message($message_ids);

		$this->response('');
	}
	
	function get_message_default_get(){
		$where_arr_def = array(
			'TYPE_ID' 		=> $this->get('type_id'),
			'STATUS_ID' 	=> $this->get('status_id')
		);
		
		$rs_msg = $this->common_model->get_message_default($where_arr_def)->result_array()[0];
		$this->response($rs_msg);
	}
	
	function get_email_template_get(){
		$template_id = $this->get('template_id');
		$rs = $this->common_model->get_email_template(['TEMPLATE_ID' => $template_id, 'ACTIVE' => 1])->result_array()[0];
		$this->response($rs);
	}

	function get_email_template2_get(){
		$template_id = $this->get('template_type');
		$rs = $this->common_model->get_email_template(['TEMPLATE_TYPE' => $template_id, 'ACTIVE' => 1])->result_array()[0];
		$this->response($rs);
	}

	function new_inbox_get(){


		$data['start'] = $this->get('start');
		$data['length'] = $this->get('length');
		$data['user_id'] = $this->get('user_id');
		$data['type'] = $this->get('type');
		$data['status'] = $this->get('status');
		$data['sort'] = $this->get('sort');
		$data['from'] = $this->get('from');
		$data['subject'] = $this->get('subject');
		$data['sort_type'] = $this->get('sort_type');
		$data['search_topic'] = $this->get('search_topic');
		$message[0] = $this->mail_model->get_new_inbox($data);
		$message[1] = $this->mail_model->get_all_inbox_count($data);
	
		$this->response($message);

	}


	function new_outbox_get(){

		$data['start'] = $this->get('start');
		$data['length'] = $this->get('length');
		$data['user_id'] = $this->get('user_id');
		$data['type'] = $this->get('type');
		$data['status'] = $this->get('status');
		$data['sort'] = $this->get('sort');
		$data['from'] = $this->get('from');
		$data['subject'] = $this->get('subject');
		$data['sort_type'] = $this->get('sort_type');
		$data['search_topic'] = $this->get('search_topic');
		$message[0] = $this->mail_model->get_new_outbox($data);
		$message[1] = $this->mail_model->get_all_outbox_count($data);	
		$this->response($message);
	}

	function inbox_outbox_archive_get(){


		$data['start'] = $this->get('start');
		$data['length'] = $this->get('length');
		$data['user_id'] = $this->get('user_id');
		$data['type'] = $this->get('type');
		$data['status'] = $this->get('status');
		$data['sort'] = $this->get('sort');
		$data['from'] = $this->get('from');
		$data['subject'] = $this->get('subject');
		$data['sort_type'] = $this->get('sort_type');
		$data['message_type'] = $this->get('message_type');
		$data['search_topic'] = $this->get('search_topic');
		$message[0] = $this->mail_model->get_inbox_outbox_archive($data);
		$message[1] = $this->mail_model->get_inbox_outbox_archive_count($data);	
		$this->response($message);


	}
}
?>