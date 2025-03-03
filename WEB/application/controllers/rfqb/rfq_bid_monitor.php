<?php
Class Rfq_bid_monitor extends CI_Controller{

	function index()
	{
		// $this->load->view('rfqb/rfq_bid_monitor_view');
	}

	function rfq_bid_monitor_failed()
	{
		$this->load->view('rfqb/rfq_bid_monitor_failed_view');
	}

	public function load_rfqb_main()
	{
		$this->load->view('rfqb/rfq_main');
	}

	public function get_rfqb_id($rfb_id){
		$data['rfb_id'] = $rfb_id;
		$this->load->view('rfqb/rfq_bid_monitor_view', $data);
	}

	public function get_rfqb(){
		$data['created_by'] = $this->session->userdata['user_id'];
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/view_rfqb', $data, '');

		echo json_encode($data['result_data']);
	}

	public function get_rfqb_table(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/view_rfqb_table', $data, '');

		echo json_encode($data['result_data']);
	}

	public function get_rfqb_position(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['user_id'] = $this->session->userdata['user_id'];
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/view_rfqb_position', $data, '');

		echo json_encode($data['result_data']);
	}

	public function set_rfqb_status(){

		$data['user_id'] 				= $this->session->userdata('user_id');
		$data['business_type']			= $this->session->userdata('business_type');

		$appover_data 					= $this->rest_app->get('index.php/rfq_api/appover_data/', $data, 'application/json');
		
		$data['user_id']			= $this->session->userdata('user_id');
		$data['rfb_id'] 			= $this->input->post('rfb_id');
		$data['rfb_status'] 		= $this->input->post('rfb_status');
		$data['reason'] 			= $this->input->post('reason');
		$data['position_id'] 		= $this->input->post('position_id');
		$data['extension_date'] 	= $this->input->post('extension_date');
		$data['action_type'] 		= $this->input->post('action_type');
		
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/set_rfqb_status', $data, '');

		$new_data['user_id'] 	= $data['user_id'];
		$buyer_data 			= (array)$this->rest_app->get('index.php/rfq_api/user_data/', $new_data, 'application/json');

		$new_data['rfb_id'] = $data['rfb_id'];
		$new_data['table'] = 'SMNTP_RFQRFB';
		$new_data['field'] = 'TITLE';
		$rs_data 			= $this->rest_app->get('index.php/rfq_api/get_single_data/', $new_data, 'application/json');

		$data_email['template_id'] = 0;
		$data_email['type_id'] = 3; 
		$data_email['status'] = 999; // on dev config  = 61  
		$message_data	 	= (array)$this->rest_app->get('index.php/rfq_api/mail_to_approver/', $data_email, 'application/json');

		$title = 'RFQ#'.$data['rfb_id'].' - '.$rs_data->rfq_result;

		$msg = str_replace('[approvername]', $appover_data->result[0]->USER_FIRST_NAME.' '.$appover_data->result[0]->USER_LAST_NAME, $message_data['message']);
		$msg = str_replace('[sendername]', $this->session->userdata('user_first_name') .' '.$this->session->userdata('user_last_name'), $msg);
		$msg = str_replace('[action]', $data['action_type'], $msg);

		$subject = str_replace('[rfqtitle]', $title, $message_data['subject']);
		$topic = str_replace('[rfqtitle]', $title, $message_data['topic']);

		$post_data['user_id'] = $this->session->userdata('user_id');

		$post_data['type'] = 'notification';
		$post_data['recipient_id'] = $appover_data->result[0]->USER_ID;
		$post_data['mail_subj'] = $subject;
		$post_data['mail_topic'] = $topic;
		$post_data['mail_body'] = $msg;
		$post_data['rfqrfb_id'] = $data['rfb_id'];

		$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');

		echo json_encode($data['result_data']);
	}

	public function set_sub_deadline(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['sub_date'] = $this->input->post('sub_date');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/set_sub_deadline', $data, '');

		echo json_encode($data['result_data']);
	}


	public function get_rfqb_config(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/get_rfqb_config', $data, '');

		echo json_encode($data['result_data']);
	}



}
?>