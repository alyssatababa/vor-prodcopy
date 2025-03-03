<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inviteapproval extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($invite_id = null, $view_only = 0, $status_id = 0)
	{	
		$data['user_id'] 	= $this->session->userdata('user_id');
		// Added MSF - 20191108 (IJR-10617)
		$data['original_file_name'] = '';
		$data['file_path'] = '';
		$data['invite_id'] = $invite_id;
		$data['business_type'] = $this->session->userdata('business_type');
		$data['view_only'] = $view_only;
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
		$data['status'] = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $data, 'application/json');
		$data['registration_type'] = $this->rest_app->get('index.php/vendor/invitecreation_api/registration_type', $data, 'application/json');
		
		if($data['registration_type'] != 4){
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		}else{
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/avc_termspayment', $data, 'application/json');
		}
		
		// Added MSF - 20191105 (IJR-10612)
		$data['position_id'] =  $this->session->userdata('position_id');
		
		if($data['termspayment'] == null){
			$data['termspayment'] = '5';
		}

		$data['status_id'] = $status_id;
		$data['business_type_vendor'] = $this->rest_app->get('index.php/vendor/invitecreation_api/get_vendor_type', array('invite_id' => $data['invite_id']), 'application/json');
		
		// Added MSF - 20191108 (IJR-10617)
		if($data['registration_type'] != 4){
			$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');
		}else{
			$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_avc_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');
		}
		
		if(!empty($approved_items)){
			$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
			$data['file_path'] = $approved_items[0]->FILE_PATH;
		}
		
		//print_r($data['business_type_vendor'][0]);die();
		if( ! empty($data['business_type_vendor'])){
			$data['business_type_vendor'] = $data['business_type_vendor'][0]->BUSINESS_TYPE;
		}	

		
		$this->load->view('vendor/inviteforapprovaldetails', $data);
	}
	
	public function invite_process()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['invite_id'] 		= $this->input->post('invite_id');
		$data['status'] 		= $this->input->post('status_id'); // current status
		$data['action'] 		= $this->input->post('action'); // 3 approve, 4 reject
		$data['remarks'] 		= $this->input->post('remarks');
		$data['vendor_msg'] 	= $this->input->post('txt_vendor_msg');
		$data['surl']	 		= base_url();
		$data['cbo_tp'] 		= $this->input->post('cbo_tp');
		$data['reg_type_id']    = $this->input->post('reg_type_id');
		//var_dump($data);
		//die();

		$rs = $this->rest_app->put('index.php/vendor/inviteapproval_api/process', $data, 'text');
		//var_dump($rs);
		//return;
/*		var_dump($rs);
		return;
*/
/**/
	/*	return;*/
/*		$this->rest_app->debug();
		die();*/
		/*var_dump($rs);
		die();*/
		if (isset($rs->status) && $rs->status)
		{
			$status_id = '';
			
			if($data['status'] == 197 || $data['status'] == 2){
				
				// Re-invite
				if($data['action'] == 3){
					$status_id = 101; //approved
				}
				else if ($data['action'] == 4){
					$status_id = 4; //rejected
				}

			}else{
				// 	extension		
				if($data['action'] == 3){
					$status_id = 6; //approved
				}
				else if ($data['action'] == 4){
					$status_id = 7; //rejected
				}
			}

			$tdata['status'] = $status_id;
			
					
			if($data['status'] == 197 || $data['status'] == 2){
				$user_inf = array(
					'status_id' => $data['status'],
					'next_status_id' => $rs->next_status,
					'vendor_invite_id' => $data['invite_id'],
					'inviter_id' => $rs->creator_id,
					'vendorname' => htmlspecialchars_decode($this->input->post('vendorname')),
					'approver_id' => $data['user_id'] ,
					'remarks' => $data['remarks']
			);



			$send_email = $this->rest_app->post('index.php/vendor/inviteapproval_api/send_email_invite_approval', $user_inf, '');


			}else{

					$user_inf = array(
					'status_id' => $data['status'],
					'next_status_id' => $rs->next_status,
					'vendor_invite_id' => $data['invite_id'],
					'inviter_id' => $rs->creator_id,
					'vendorname' => htmlspecialchars_decode($this->input->post('vendorname')),
					'approver_id' => $data['user_id'] ,
					'remarks' => $data['remarks']
					);


					$send_email = $this->rest_app->post('index.php/vendor/inviteapproval_api/send_email_invite_approval', $user_inf, '');


			}




		/*	$vendorname = $this->input->post('vendorname');
			$u_umn = $this->session->userdata('user_middle_name');
			$u_uln = $this->session->userdata('user_last_name');
			$u_ufn = $this->session->userdata('user_first_name');
			$approvername = $u_ufn 
			. (!empty($u_umn) ? ' ' . $u_umn : '') 
			. (!empty($u_uln) ? ' ' . $u_uln : '') ;

			$post_data['user_id'] = $this->session->userdata('user_id');
	
			$post_data['type'] = 'notification';
			
			$post_data['recipient_id'] = $rs->sender;
			$post_data['mail_subj'] = str_replace('[vendorname]', $vendorname, $gmd->SUBJECT);
			$post_data['mail_topic'] = str_replace('[vendorname]', $vendorname, $gmd->TOPIC);
			
			$message = str_replace('[sendername]', $rs->sender_name, $gmd->MESSAGE);
			$message = str_replace('[vendorname]', $vendorname, $message);
			$message = str_replace('[approvername]', $approvername, $message);
			$message = str_replace('[remarks]', $data['remarks'] , $message);
			
			$post_data['mail_body'] = $message;
			if ($rs->next_status == 4){
				$post_data['mail_body'] = $rs->message;
				$post_data['mail_subj'] = $rs->subject;
				$post_data['mail_topic'] = $rs->topic;
			}
			$post_data['invite_id'] = $data['invite_id'];

	
			//Portal Notif
		//	$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');

			if(!empty($data['user_id'] ) && $data['action'] == 3){
				//Invite extended message for BUYER/SENMER

				///Email Template Type 17 for extension

				$username =  $this->session->userdata('user_first_name') ." ".  $this->session->userdata('user_middle_name') ." " .  $this->session->userdata('user_last_name');
			
				$user_inf = array(
					'approvername' => $username,
					'vendorname' =>$this->input->post('vendorname'),
					'status' =>  $tdata['status'],
					'sendername' => $rs->sender_name,
					'remarks' => $data['remarks'],
					'email' => $rs->sender_email,
					'notif' => $gmd
				);

		
			$send_email = $this->rest_app->post('index.php/vendor/inviteapproval_api/send_email_invite_approval', $user_inf, '');

			var_dump($send_email);
			return;
*/

			
		}

		// $rs['status_id'] = $gmd->MESSAGE;
		
		echo json_encode($rs);
	}
	
		public function resend_email()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['invite_id'] 		= $this->input->post('invite_id');
		$data['status'] 		= $this->input->post('status_id'); // current status
		$data['action'] 		= $this->input->post('action'); // 3 approve, 4 reject
		$data['remarks'] 		= $this->input->post('remarks');
		$data['vendor_msg'] 	= $this->input->post('txt_vendor_msg');
		$data['surl']	 		= base_url();

		$rs = $this->rest_app->put('index.php/vendor/inviteapproval_api/resend_email', $data, 'text');
		// $this->rest_app->debug();
		if (isset($rs->status) && $rs->status)
		{
			if ($data['action'] == 4) // if reject send message to creator
			{
				$post_data['user_id'] = $this->session->userdata('user_id');

				$post_data['type'] = 'notification';
				$post_data['recipient_id'] = $rs->recipient_id;
				$post_data['mail_subj'] = $rs->subject;
				$post_data['mail_topic'] = $rs->topic;
				$post_data['mail_body'] = $rs->message;
				$post_data['invite_id'] = $data['invite_id'];

				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
				// $this->rest_app->debug();
			}
		}
		
		echo json_encode($rs);
	}
	
	
	// Added MSF - 20191105 (IJR-10612)
	public function update_email(){
		$data['invite_id'] = $this->input->post('invite_id');
		$data['email'] = $this->input->post('txt_email');
		
		$rs = $this->rest_app->put('index.php/vendor/inviteapproval_api/update_email', $data, '');
		
		echo json_encode($rs);
	}

	
	function history_table($invite_id = null)
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['invite_id'] 	= $invite_id;

		$rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/history_tbl', $data, 'application/json');
		// $this->rest_app->debug();
			
		echo json_encode($rs);
	}

	
	function rev_history_table($invite_id = null)
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['invite_id'] 	= $invite_id;

		$rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/rev_history_tbl', $data, 'application/json');
		
		echo json_encode($rs);
	}

	function smvs($invite_id = null){
		$data['invite_id'] 	= $invite_id;

		$rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/smvs', $data, 'application/json');
		
		echo json_encode($rs);
	}

	function smvs_vendor($invite_id = null){
		$data['invite_id'] 	= $invite_id;

		$rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/smvs_vendor', $data, 'application/json');
		
		echo json_encode($rs);
	}

	function vendor_id_request_vendor($invite_id = null){
        $data['invite_id']         = $invite_id;
        $rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/vendor_id_request_vendor', $data, 'application/json');
        
        /*print_r($rs);
        exit();*/
        echo json_encode($rs);
    }
    function vendor_pass_request_vendor($invite_id = null){
        $data['invite_id']         = $invite_id;
        $rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/vendor_pass_request_vendor', $data, 'application/json');
        
        //print_r($rs); return;
        echo json_encode($rs);
        // $this->rest_app->debug();
    }
    function vendor_request_history($invite_id = null){
        $data['invite_id']         = $invite_id;
        $rs = $this->rest_app->get('index.php/vendor/inviteapproval_api/vendor_request_history', $data, 'application/json');
        
        //print_r($rs); return;
        echo json_encode($rs);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */