<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Codeassignment extends CI_Controller
{

	function index($vendor_id = null)
	{
		$data['vendor_id'] = $vendor_id;
		$rs = $this->rest_app->get('index.php/vendor/codeassignment_api/vendor_data', $data, 'application/json');

		if ($rs && $rs->rs_vendor && sizeof($rs->rs_vendor)>0) {
			$data['vendor_type'] = $rs->rs_vendor[0]->VENDOR_TYPE_NAME;
			$data['vendor_name'] = $rs->rs_vendor[0]->VENDOR_NAME;
			$data['registration_type'] = $rs->rs_vendor[0]->REGISTRATION_TYPE;
			if($data['registration_type'] == 4){				
				if($rs->rs_vendor[0]->TRADE_VENDOR_TYPE == 1){
					$data['vendor_type'] .= ' - CONSIGNOR';
				}else if($rs->rs_vendor[0]->TRADE_VENDOR_TYPE == 2){
					$data['vendor_type'] .= ' - OUTRIGHT';
				}
			}else{
				if($rs->rs_vendor[0]->TRADE_VENDOR_TYPE == 1){
					$data['vendor_type'] .= ' - OUTRIGHT';
				}else if($rs->rs_vendor[0]->TRADE_VENDOR_TYPE == 2){
					$data['vendor_type'] .= ' - CONSIGNOR';
				}
			}
			$data['vendor_code'] = $rs->rs_vendor[0]->VENDOR_CODE;
		} else {
			$data['vendor_type'] = '[no data]';
			$data['vendor_name'] = '[no data]';
		}
		$this->load->view('vendor/code_assignment', $data);
	}

	function save_codeassign()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['code_assign'] 	= str_replace(' ', '', $this->input->post('txt_vendor_code')); //remove spaces
		$data['watson_vendor']	= $this->input->post('chkbox_watson');
		
		if($data['watson_vendor'] == "on"){
			$data['watson_vendor'] = true;
		}
		
		$data['vendor_id'] 		= $this->input->post('vendor_id');
		$data['registration_type'] 		= $this->input->post('registration_type');

		//var_dump($data);
		//die();
	
		$rs = $this->rest_app->post('index.php/vendor/codeassignment_api/save_codeassgin', $data);

		//$this->rest_app->debug();
		
		//var_dump($rs);
		//die();
		if (isset($rs->status) && $rs->status)
		{
			if($rs->next_status == 190){
				//Send Message Notification
				
				//If ARD is not submitted - jay
				if( ! $rs->ARD_already_submitted && ! $rs->check_waived_all_ad){
					//Send Portal Message
					$gmd = $this->rest_app->get('index.php/mail/get_message_default', array('type_id' => 1, 'status_id' => $rs->next_status), 'application/json');
					$vdata = $this->rest_app->get('index.php/vendor/codeassignment_api/getinfo', $data, 'text');
					$vendorname = $rs->vendor_info[0]->USER_FIRST_NAME
							. (!empty($rs->vendor_info[0]->USER_MIDDLE_NAME) ? ' ' . $rs->vendor_info[0]->USER_MIDDLE_NAME : '') 
							. (!empty($rs->vendor_info[0]->USER_LAST_NAME) ? ' ' . $rs->vendor_info[0]->USER_LAST_NAME : '') ;
					
					$post_data['type'] 		= 'notification';
					$post_data['mail_subj'] = str_replace('[vendor_name]', $vendorname, $gmd->SUBJECT);
					$post_data['mail_topic'] = str_replace('[vendor_name]', $vendorname, $gmd->TOPIC);
					$post_data['mail_subj'] = str_replace('[vendorname]', $vendorname, $post_data['mail_subj']);
					$post_data['mail_topic'] = str_replace('[vendorname]', $vendorname, $post_data['mail_topic']);
					//$post_data['invite_id'] 	= $rs->invite_id;
					$post_data['recipient_id'] 	= $vdata[0]->USER_ID;				
					$post_data['mail_body']  = str_replace('[vendor_name]', $vendorname, $gmd->MESSAGE );
					$post_data['mail_body']  = str_replace('[vendorname]', $vendorname,$post_data['mail_body']);
					$post_data['mail_body']  = str_replace('[submission_deadline]', $rs->submission_date, $post_data['mail_body'] );
					$vdata = $this->rest_app->get('index.php/vendor/codeassignment_api/getinfo', $data, 'text');
					$post_data['user_id'] = $vdata[0]->USER_ID;
					$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');					

					//Send Portal Message End				
				}
				
				if($rs->check_waived_all_ad || $rs->ARD_already_submitted){
					$vdata = $this->rest_app->get('index.php/vendor/codeassignment_api/getinfo', $data, 'text');
					$venname = $vdata[0]->USER_FIRST_NAME .' '.$vdata[0]->USER_MIDDLE_NAME .' '.$vdata[0]->USER_LAST_NAME;
					$data['SID'] = $vdata[0]->STATUS_ID;
					$data['UID'] = $vdata[0]->USER_ID;
					$data['email'] = $vdata[0]->USER_EMAIL;
					$data['VID'] = 	$data['vendor_id'];
					$data['vname'] =  trim($venname);
					$data['sub_date'] =  $rs->submission_date; // - jay
					$data['ARD_already_submitted'] = $rs->ARD_already_submitted; //Jay kung nasubmit na yung ARD
					$send_data = $this->rest_app->put('index.php/vendor/registration_api/send_emal_vrd',$data,'text');

					/*$rr = $this->rest_app->put('index.php/vendor/registration_api/portal_vendor_success', $data,'text');
					var_dump( $rr);
					return;*/

					//comment $send_data if something goes wrong/uncomment $rr;

				}
	
				
			}				
			//$this->rest_app->debug();
			//var_dump($data);
			//die();
			echo 1;
		}
		else
		{
			echo $rs->error;
		}
	}

}
?>
