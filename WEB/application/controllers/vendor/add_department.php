<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Add_department extends CI_Controller {

	public function index($invite_id = null)
	{
		$data['invite_id'] = $invite_id;
		
		$data['business_type'] = $this->session->userdata('business_type');
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_id'] = $this->session->userdata('user_id');
		
		$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		if($invite_id == null){
			$data['termspayment'] = '5';
		}
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
			
		$category_list = $this->rest_app->get('index.php/vendor/invitecreation_api/category_list', $data, 'application/json');
		$data['category_list'] 		= $category_list;
			
		$this->load->view('vendor/add_department',$data);
	}
	
	function vendor_add_department(){
		$data = $_POST;
		$data['inviter'] = $this->session->userdata('user_id');
		$data['position_id'] = $this->session->userdata('position_id');
		$rs = $this->rest_app->get('index.php/vendor/add_department_api/add_vendor_department', $data, 'application/json');
		echo $rs;
		//print_r($rs);
	}
	
	public function approval($invite_id){
		$data['invite_id'] = $invite_id;
		
		$data['business_type'] = $this->session->userdata('business_type');
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_id'] = $this->session->userdata('user_id');
		
		if($data['position_id'] == 3){
			$data['status'] = '250';
		}else if($data['position_id'] == 5){
			$data['status'] = '252';
		}else if($data['position_id'] == 6){
			$data['status'] = '254';
		}
		
		//$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		
		$data['vendor_profile'] = $this->rest_app->get('index.php/vendor/add_department_api/vendor_profile', $data, 'application/json');
		if($data['vendor_profile']->main_vt == $data['vendor_profile']->vendor_type){
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		}else{
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/avc_termspayment', $data, 'application/json');
		}
		
		if($invite_id == null){
			$data['termspayment'] = '5';
		}
		
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
		
		$approved_items = $this->rest_app->get('index.php/vendor/invitecreation_api/get_ad_vendor_approved_items',array('invite_id' => $data['invite_id']),'application/json');



		if(!empty($approved_items)){
			$data['original_file_name'] = $approved_items[0]->ORIGINAL_FILE_NAME;
			$data['file_path'] = $approved_items[0]->FILE_PATH;

			if(count($approved_items) > 1){
				$data['original_file_name2'] = $approved_items[1]->ORIGINAL_FILE_NAME;
				$data['file_path2'] = $approved_items[1]->FILE_PATH;
			}else{
				$data['original_file_name2'] = '';
				$data['file_path2'] = '';
			}
		}

		

		//var_dump($data['vendor_profile']);
		//echo $data['vendor_profile']->add_sub_category[0]->SUB_CATEGORY_NAME;
		
		
		
			
		$this->load->view('vendor/add_department_approval',$data);
	}
	
	public function print_template($id = null){
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['invite_id'] 	= $id;

		$data['rs'] = $this->rest_app->get('index.php/vendor/add_department_api/vendor_profile', $data, 'application/json');
		//$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		
		if($data['rs']->main_vt == $data['rs']->vendor_type){
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		}else{
			$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/avc_termspayment', $data, 'application/json');
		}
		
		if($data['invite_id'] == null){
			$data['termspayment'] = '5';
		}
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
		$data['division_head'] = $this->rest_app->get('index.php/vendor/add_department_api/div_head', $data, 'application/json');

		// $this->load->view('vendor/vendor_details_1_pdf_view.php', $data);
		$this->load->view('vendor/add_department_pdf_view.php', $data);
		// $this->load->view('vendor/vendor_details_3_pdf_view.php', $data);

	}
	
	public function print_templatev2($id = null){
		$data['user_id'] 	= $this->session->userdata('user_id');
		$data['invite_id'] 	= $id;

		$data['rs'] = $this->rest_app->get('index.php/vendor/add_department_api/vendor_profile', $data, 'application/json');
		$data['termspayment'] = $this->rest_app->get('index.php/vendor/invitecreation_api/termspayment', $data, 'application/json');
		$data['payment_terms'] = $this->rest_app->get('index.php/vendor/registration_api/payment_terms', '', 'application/json');
		$data['division_head'] = $this->rest_app->get('index.php/vendor/add_department_api/div_head', $data, 'application/json');

		// $this->load->view('vendor/vendor_details_1_pdf_view.php', $data);
		$this->load->view('vendor/add_department_pdf_view_v2.php', $data);
		// $this->load->view('vendor/vendor_details_3_pdf_view.php', $data);

	}
	
	public function response_approval(){
		$data = $_POST;
		$data['user_id'] = $this->session->userdata('user_id');
		$rs = $this->rest_app->get('index.php/vendor/add_department_api/response_approval', $data, 'application/json');
		//print_r($rs);
		//die();
		//if(isset($rs)){
		//	echo 1;
		//}else{
		//	echo 2;
		//}
		echo 1;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
