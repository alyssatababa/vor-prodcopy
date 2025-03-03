<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Registration_Activation extends CI_Controller
	{
		function index()
		{
			$data['user_id'] = $this->session->userdata('user_id');
			$data['user_type_id'] = $this->session->userdata('user_type_id');
			$data['position_id'] = $this->session->userdata('position_id');
			$data['vendor_data']		= $this->rest_app->get('index.php/maintenance/activationmain_api/vendordata/', $data, 'application/json');

			$this->load->view('maintenance/registration_activation_view', $data);
		}

		function activationmain_table()
		{
			$data['user_id'] = $this->session->userdata('user_id');
			$data['vendorname'] = $this->input->post('encoded_vendorname');
			$data['vendorcode'] = $this->input->post('encoded_vendorcode');
			$data['position_id'] = $this->session->userdata('position_id');
			
			$rs = $this->rest_app->get('index.php/maintenance/activationmain_api/activation_table', $data, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//var_dump($rs);
			echo json_encode($rs);
		}

		function activate_selected()
		{
			$data = $_POST;
			$data['user_id'] = $this->session->userdata('user_id');
			$data['position_id'] = $this->session->userdata('position_id');
			$data['next_position_id'] = 10;
			//echo "<pre>";
			//var_dump($data);
			//die();

/*			var_dump($data);
			return;
*/

			if($data['position_id'] == 7 || $data['position_id'] == 4 || $data['position_id'] == 2 || $data['position_id'] == 11)
			{
				switch($data['position_id'])
				{
					case 2:
						$data['next_position_id'] = 3;
						$data['next_status_id'] = 196;
						break;
					case 4:
						$data['next_position_id'] = 5;
						$data['next_status_id'] = 196;
						break;
					case 7:
						$data['next_position_id'] = 8;
						$data['next_status_id'] = 196;
						break;
					case 11:
						$data['next_position_id'] = 3;
						$data['next_status_id'] = 196;
						break;
				}
			
			}
			else
			{
				$data['next_status_id'] = 190;
			}
			$data['vendorids'] = array();
			foreach($data as $key => $value){
				if (strpos($key, 'vendorinviteid') !== false) {
					$data['vendorids'][] = $value;
				}
			}
			//Jay
			$result  = $this->rest_app->get('index.php/maintenance/activationmain_api/recipientdata', $data, NULL)->response;
			$vendorinfo = $result->vendorinfo;
			$approverinfo = $result->approverinfo;
			$emailmessage = $result->emailmessage->CONTENT;



			
			$rs = $this->rest_app->post('index.php/maintenance/activationmain_api/activate_selected', $data, NULL);

			
			if($data['position_id'] == 7 || $data['position_id'] == 2 || $data['position_id'] == 11){
				
				//196 - For Activation Approval
				$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => 196), 'application/json');
				
				$sendername = $this->session->userdata('user_first_name')
						. (!empty($this->session->userdata('user_middle_name')) ? ' ' . $this->session->userdata('user_middle_name'): '') 
						. (!empty($this->session->userdata('user_last_name')) ? ' ' . $this->session->userdata('user_last_name'): '') ;
				
				foreach($approverinfo as $approver){
					
					$approvername = $approver->USER_FIRST_NAME
						. (!empty($approver->USER_MIDDLE_NAME) ? ' ' . $approver->USER_MIDDLE_NAME : '') 
						. (!empty($approver->USER_LAST_NAME) ? ' ' . $approver->USER_LAST_NAME : '') ;
				
					foreach($vendorinfo as $vendor){
						
						//Send portal message
						$post_data['type'] 		= 'notification';
						$post_data['mail_subj'] = str_replace('[vendorname]', $vendor->vendorname, $gmd->SUBJECT);
						$post_data['mail_topic'] = str_replace('[vendorname]', $vendor->vendorname, $gmd->TOPIC);
						$post_data['invite_id'] 	= $vendor->vendorinviteid;
						$post_data['recipient_id'] 	= $approver->USER_ID;
						
						$post_data['mail_body']  = str_replace('[approvername]', $approvername, $gmd->MESSAGE);
						$post_data['mail_body']  = str_replace('[sendername]', $sendername, $post_data['mail_body'] );
						$post_data['mail_body']  = str_replace('[vendorname]', $vendor->vendorname, $post_data['mail_body'] );
						$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
						
						//Send Email Message
						
						$email_data['to'] = $approver->USER_EMAIL;
						$email_data['subject'] = $vendor->vendorname . ' - ' .$post_data['mail_topic'].' (Primary Requirements)';
						$email_data['content']  = str_replace('[approvername]', $approvername, $emailmessage);
						$email_data['content']  = str_replace('[sendername]', $sendername, $email_data['content'] );
						$email_data['content']  = str_replace('[vendorname]', $vendor->vendorname, $email_data['content']);
						
						$send_email = $this->rest_app->post('index.php/common_api/send_email_message', $email_data, '');
						
					}
				}
				//Send Portal Message End
				
			}
			//-
			//$this->rest_app->debug();
			echo json_encode($rs);
		}

		function approval()
		{
			$data['user_id'] = $this->session->userdata('user_id');
			$data['user_type_id'] = $this->session->userdata('user_type_id');
			$data['position_id'] = $this->session->userdata('position_id');
			$data['vendor_data']		= $this->rest_app->get('index.php/maintenance/activationmain_api/vendordata/', $data, 'application/json');

			$this->load->view('maintenance/activation_approval_view', $data);

		}

		function approval_table()
		{
			$data['vendorname'] = $this->input->post('vendorname');
			$data['vendorcode'] = $this->input->post('vendorcode');
			$data['user_id'] = $this->session->userdata('user_id');
			$data['position_id'] = $this->session->userdata('position_id');

			
			$rs = $this->rest_app->get('index.php/maintenance/activationmain_api/approval_table', $data, 'application/json');
			//$this->rest_app->debug();
			
			echo json_encode($rs);

		}

		function approve_selected()
		{
			$data = $_POST;
			$data['user_id'] = $this->session->userdata('user_id');
			$data['user_type_id'] = $this->session->userdata('user_type_id');
			$data['position_id'] = $this->session->userdata('position_id');
			$rs = $this->rest_app->post('index.php/maintenance/activationmain_api/approve_selected', $data, NULL);
			//$this->rest_app->debug();
			//echo "<pre>";
			//print_r($rs);
			//echo "</pre>";
			//die();
			
			echo json_encode($rs);
		}

		function set_termsofpayment()
		{
			$data['invite_id'] = $this->input->post('inv_id');	
			$data['termsofpayment'] = $this->input->post('t_op');
			$data['user_id'] = $this->input->post('user_id');

			$rs = $this->rest_app->post('index.php/maintenance/activationmain_api/change_termsofpayment', $data, NULL);

			echo json_encode($rs);
		}


	}
?>