<?php
Class Rfq_bid_monitor_approval extends CI_Controller{

	function index()
	{

		$this->load->view('rfqb/rfq_bid_monitor_approval_view', $data);
	}

	// function rfq_bid_monitor_failed()
	// {
	// 	$this->load->view('rfqb/rfq_bid_monitor_failed_view');
	// }

	public function get_rfqb_apprvl_id($rfb_id){
		$data['rfb_id'] = $rfb_id;
		$this->load->view('rfqb/rfq_bid_monitor_approval_view', $data);

	}

	public function get_rfqb_apprvl(){
		// $data['created_by'] = $this->session->userdata['user_id'];
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor_approval/view_rfqb_apprvl', $data, '');

		echo json_encode($data['result_data']);
	}

	public function get_rfqb_table(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor_approval/view_rfqb_table', $data, '');

		echo json_encode($data['result_data']);
	}

	// public function get_rfqb_apprvl_position(){
	// 	$data['rfb_id'] = $this->input->post('rfb_id');
	// 	$data['user_id'] = $this->session->userdata['user_id'];
	// 	$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor/view_rfqb_apprvl_position', $data, '');

	// 	echo json_encode($data['result_data']);
	// }

	public function set_rfqb_status(){
		$data['user_id']			= $this->session->userdata('user_id');
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['action_done'] = $this->input->post('action_done');
		$data['rfb_status'] = $this->input->post('rfb_status');
		$data['invite_status'] = $this->input->post('invite_status');
		$data['reason'] = $this->input->post('reason');
		$data['position_id'] = $this->input->post('position_id');
		$data['extension_id'] = $this->input->post('extension_id');
		$data['current_status'] = $this->input->post('current_status');
		$data['bid_type'] = $this->input->post('bid_type');

		if($data['current_status'] == 23)
		{
			if($data['rfb_status'] != 22)
			{
				$data['rfx_id'] = $data['rfb_id'];
				$data['type'] = 11;
				$email_data 	= $this->rest_app->get('index.php/rfq_api/approval_email_data/', $data, 'application/json');
			}
		}
		
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor_approval/set_rfqb_status', $data, '');

		$new_data['rfb_id'] = $data['rfb_id'];
		$new_data['table'] = 'SMNTP_RFQRFB';
		$new_data['field'] = 'CREATED_BY';
		$rs_createdby 			= $this->rest_app->get('index.php/rfq_api/get_single_data/', $new_data, 'application/json');

		$new_data['user_id'] 	= $rs_createdby->rfq_result;
		$buyer_data 			= (array)$this->rest_app->get('index.php/rfq_api/user_data/', $new_data, 'application/json');

		$new_data['rfb_id'] = $data['rfb_id'];
		$new_data['table'] = 'SMNTP_RFQRFB';
		$new_data['field'] = 'TITLE';
		$rs_data 			= $this->rest_app->get('index.php/rfq_api/get_single_data/', $new_data, 'application/json');
		
		$data_email['template_id'] = 0;
		$data_email['type_id'] = 3; 
		$data_email['status'] = 22; // on dev config  = 22  
		$message_data	 	= (array)$this->rest_app->get('index.php/rfq_api/mail_to_approver/', $data_email, 'application/json');

		$title = 'RFQ#'.$data['rfb_id'].' - '.$rs_data->rfq_result;

		$msg = str_replace('[buyername]', $buyer_data['result'][0]->USER_FIRST_NAME.' '.$buyer_data['result'][0]->USER_LAST_NAME, $message_data['message']);
		$msg = str_replace('[request]', $data['bid_type'], $msg);
		$msg = str_replace('[action]', $data['action_done'], $msg);

		$subject = str_replace('[rfqtitle]', $title, $message_data['subject']);
		$topic = str_replace('[rfqtitle]', $title, $message_data['topic']);

		$post_data['user_id'] = $this->session->userdata('user_id');

		$post_data['type'] = 'notification';
		$post_data['recipient_id'] = $rs_createdby->rfq_result;
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
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor_approval/set_sub_deadline', $data, '');

		echo json_encode($data['result_data']);
	}


	public function get_rfqb_config(){
		$data['rfb_id'] = $this->input->post('rfb_id');
		$data['result_data'] = $this->rest_app->post('index.php/rfqb/rfq_bid_monitor_approval/get_rfqb_config', $data, '');

		echo json_encode($data['result_data']);
	}



}
?>