<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invitecreation extends CI_Controller {

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

	public function index($invite_id = null)
	{
		
		$data['invite_id'] = $invite_id;
		
		$data['business_type'] = $this->session->userdata('business_type');
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_id'] = $this->session->userdata('user_id');
		$category_list = $this->rest_app->get('index.php/vendor/invitecreation_api/category_list', $data, 'application/json');
		//$this->rest_app->debug();
		$template_email = $this->rest_app->get('index.php/vendor/invitecreation_api/email_template', $data, 'application/json');
		$status_id = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $data, 'application/json');
		$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		if($invite_id == null){
			$data['termspayment'] = '5';
		}

		// Added MSF - 20191108 (IJR-10617)
		$data['original_file_name'] = '';
		$data['file_path'] = '';
		$data['date_created'] = '';
		$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');
		if(!empty($approved_items)){
			$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
			$data['file_path'] = $approved_items[0]->FILE_PATH;
			$data['date_created'] = date('m/d/Y h:i:s A',strtotime($approved_items[0]->DATE_CREATED));
		}


		$data['p_id'] = $this->session->userdata('position_id');		
		$data['category_list'] 		= $category_list;
		$data['template_email'] 	= $template_email;
		$data['status_id'] 			= $status_id;
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');

		$this->load->view('vendor/invitecreation', $data);
	}

	function invite_expired()
	{
		$this->load->view('vendor/invitecreation_expired');
	}

	function get_msg_template()
	{
		$data['user_id'] = $this->session->userdata('user_id');
		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/template', $data, 'application/json');
		// $this->rest_app->debug();
		echo json_encode($rs);
	}

	function add_invitecreation() // includes update
	{
		$data = $_POST;
		$data['user_id'] 			= $this->session->userdata('user_id');
		$data['position_id'] 		= $this->session->userdata('position_id');
		$data['cbo_tp'] = $this->input->post('cbo_tp');
		$data['rbtn_invite_type'] = $this->input->post('cbo_tp');
		$data['business_type'] = $this->session->userdata('business_type');

				

		// $data['invite_id'] 			= $this->input->post('invite_id');
		// $data['vendorname']	 		= $this->input->post('txt_vendorname');
		// $data['contact_person']	 	= $this->input->post('txt_contact_person');
		// $data['email']	 			= $this->input->post('txt_email');
		// $data['msg_template']	 	= $this->input->post('cbo_msg_template');
		// $data['vendor_msg']	 		= $this->input->post('txt_vendor_msg');
		// $data['template_msg']	 	= $this->input->post('txt_template_msg');
		// $data['approver_note']	 	= $this->input->post('txt_approver_note');
		// $data['status']	 			= $this->input->post('status');

		// $data['cat_count'] 			= $this->input->post('cat_sup_count');

		// for ($i=1; $i <= $data['cat_count']; $i++)
		// { 
		// 	$data['category_id'.$i] = $this->input->post('category_id'.$i);
		// }
		//echo  "<pre>"; print_r($data);die();


		if ($data['invite_id'] != null || $data['invite_id'] != ''){
			$rs = $this->rest_app->put('index.php/vendor/invitecreation_api/updateinvite', $data, 'text');
		}
		else
		{
			$rs = $this->rest_app->post('index.php/vendor/invitecreation_api/addinvite', $data);
		}	
		//echo  "<pre>"; print_r($rs);die();
		//$this->rest_app->debug();die();
		if (isset($rs->status) && $rs->status)
		{
			if ($data['status'] == 2 || $data['status'] == 5) // when subbmited sent notif to approver
			{


				if($data['invite_id'] == "" || $data['invite_id'] == null || $rs->status == 1){
				$post_data['user_id'] = $this->session->userdata('user_id');
				$post_data['type'] 			= 'notification';
				$post_data['recipient_id'] 	= $rs->recipient_id;
				$post_data['mail_subj'] 	= $rs->subject;
				$post_data['mail_topic'] 	= $rs->topic;
				$post_data['mail_body'] 	= $rs->message;
				$post_data['invite_id'] 	= $rs->invite_id;			
				// print_r($post_data);


				$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
				}else{

					$res = $this->rest_app->put('index.php/vendor/invitecreation_api/resubmit_portal', $data);	

				}
				// $this->rest_app->debug();
				
				//$email_data['to'] = $rs->sender_email;
				//$email_data['subject'] = $post_data['mail_topic'];
				//$email_data['content'] = $post_data['mail_body'];
				//$send_email = $this->rest_app->post('index.php/common_api/send_email_message', $email_data, '');
			}
			echo 1;
		}
		else
		{

			//var_dump($rs);
			echo $rs->error;
		}
	}

	function load_invite_draft()
	{
		$data['invite_id'] = $this->input->post('invite_id');

		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/load_records', $data, 'application/json');

		// $this->rest_app->debug();
		echo json_encode($rs);
		//print_r($rs);
	}

	function search_category()
	{
		$param = $_POST;
		$param['business_type'] = $this->session->userdata('business_type');
		$param['user_id'] 		= $this->session->userdata('user_id');
		$param['position_id'] 		= $this->session->userdata('position_id');
		// $param['category_name'] = $this->input->post('search_cat');

		$category_list = $this->rest_app->get('index.php/vendor/invitecreation_api/category_list', $param, 'application/json');
		// $this->rest_app->debug();
		
		$n = 1;
		if (!empty($category_list)){
			foreach ($category_list as $row)
			{
				echo '<tr>';
				echo '<td><input type="hidden" class="cls_cat" id="hid_deptcat'.$n.'" name="hid_deptcat'.$n.'" value="'.$row->CATEGORY_ID.'"><span id="deptcatname'.$n.'">'.$row->CATEGORY_NAME.'</span></td>';
				echo '</tr>';
				$n = $n + 1;
			}
		}else{
			echo '<tr><td>No Records found.</td></tr>';
		}
	}

	function validate_record()
	{
		$param['vendorname'] 		= $this->input->post('vendorname');
		$param['invite_id'] 		= $this->input->post('invite_id');
		$param['contact_person']	= $this->input->post('contact_person');
		$param['email'] 			= $this->input->post('email');

		$message = $this->rest_app->get('index.php/vendor/invitecreation_api/validate_record', $param, 'application/json');
		// $this->rest_app->debug();
		echo $message;
	}

	function extend_invite($invite_id)
	{
		$data['p_id'] = $this->session->userdata('position_id');	
		$data['invite_id'] = $invite_id;
		$data['business_type'] = $this->session->userdata('business_type');
		$category_list = $this->rest_app->get('index.php/vendor/invitecreation_api/category_list', $data, 'application/json');
		$template_email = $this->rest_app->get('index.php/vendor/invitecreation_api/email_template', $data, 'application/json');
		$status_id = $this->rest_app->get('index.php/vendor/invitecreation_api/invite_status', $data, 'application/json');
		$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		if($invite_id == null){
			$data['termspayment'] = '5';
		}

		// Added MSF - 20191108 (IJR-10617)
		$data['original_file_name'] = '';
		$data['file_path'] = '';
		$data['date_created'] = '';
		$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');
		if(!empty($approved_items)){
			$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
			$data['file_path'] = $approved_items[0]->FILE_PATH;
			$data['date_created'] = date('m/d/Y h:i:s A',strtotime($approved_items[0]->DATE_CREATED));
		}

		$data['category_list'] 		= $category_list;
		$data['template_email'] 	= $template_email;
		$data['status_id'] 			= $status_id;
		$data['invite_extend'] 		= 1; // if 1 for invite extend
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');

		$this->load->view('vendor/invitecreation', $data);
	}
	
	// Added MSF - 20191118 (IJR-10618)
	function get_sub_cat()
	{
		$data = $_POST;
		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/sub_cat', $data, 'application/json');
		//$this->rest_app->debug();
		echo json_encode($rs);
	}
	
	function get_completed_vendors(){
		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/completed_vendors', '', 'application/json');
		echo json_encode($rs);
	}
	
	function get_vendor_info(){
		$data = $_POST;
		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/vendor_info', $data, 'application/json');
		echo json_encode($rs);
	}
	
	function get_vendor_info_dept(){
		$data = $_POST;
		$rs = $this->rest_app->get('index.php/vendor/invitecreation_api/vendor_info_dept', $data, 'application/json');
		echo json_encode($rs);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */