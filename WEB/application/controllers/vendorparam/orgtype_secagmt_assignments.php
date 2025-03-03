<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orgtype_secagmt_assignments extends CI_Controller {

	public function index()
	{
		
		$this->load->view('vendorparam/orgtype_secagmt_assignments');
	}

	public function get_orgtype(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_all_orgtype', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_all_secagmt(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_all_secagmt', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_secagmt(){
		$data['input_orgtype_id'] = $this->input->post('orgtype_id');
		$data['input_vendortype_id'] = $this->input->post('vendortype_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_orgtype_secagmt', $data, '');
		echo json_encode($data['result_data']);
	}

	public function get_secagmt_not_in(){
		$data['input_orgtype_id'] = $this->input->post('orgtype_id');
		$data['input_vendortype_id'] = $this->input->post('vendortype_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_orgtype_secagmt_not_in', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_agmt(){
		$data['itms'] = $this->input->post('items');
		$data['matrix'] = $this->input->post('matrix');
		$data['user_id'] = $this->session->userdata('user_id');
		$rs  = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/save_agmt', $data, '');
		/*echo json_encode($data['result_data']);	*/	

		/*var_dump( $rs);
		echo $rs;
	*/
	}

	public function get_category(){
		$data =array();
		$vendortype_id = $this->input->post('vendortype_id');
		if($vendortype_id == 1){
			$rs = $this->rest_app->get('index.php/vendorparam/orgtype_secagmt_assignments/categories_trade',$data,'');
		}else{
			$rs = $this->rest_app->get('index.php/vendorparam/orgtype_secagmt_assignments/categories_nontrade',$data,'');
		}
		
		echo json_encode($rs);

	}

	public function slct_secagmt()
	{


		$data['snd'] = $this->input->post('snd');


		$rs = $this->rest_app->get('index.php/vendorparam/orgtype_secagmt_assignments/view_all_sec',$data,'');

		//var_dump($rs);
		echo json_encode($rs);

	}

	public function restore_default()
	{
		$data['mtx'] = $this->input->post('data');
		$rs = $this->rest_app->get('index.php/vendorparam/orgtype_secagmt_assignments/restore_default',$data,'');
		var_dump($rs);
	}

	public function delete_agmt()
	{
		$data['mtx'] = $this->input->post('data');
		$rs = $this->rest_app->get('index.php/vendorparam/orgtype_secagmt_assignments/delete_agmt',$data,'');
		var_dump($rs);
	}

	// public function get_all_screens(){
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_all_screens', '', '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function get_screens(){
	// 	$data['input_orgtype_id'] = $this->input->post('orgtype_id');
	// 	// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_secagmt', $data, '');
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_screens', $data, '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function get_screens_not_in(){
	// 	$data['input_orgtype_id'] = $this->input->post('orgtype_id');
	// 	// $data['result_data'] = $this->rest_app->post('index.php/vendorparam/required_documents/view_secagmt', $data, '');
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/view_screens_not_in', $data, '');
	// 	echo json_encode($data['result_data']);
	// }

	// public function save_screens(){
	// 	$data['screens_data'] = $this->input->post('screens');
	// 	$data['orgtype_id'] = $this->input->post('orgtype_id');
		
	// 	$data['result_data'] = $this->rest_app->post('index.php/vendorparam/orgtype_secagmt_assignments/save_screens', $data, '');
	// 	echo json_encode($data['result_data']);		
	// 	// echo "1";
	// }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */