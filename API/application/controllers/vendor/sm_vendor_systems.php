<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('user_agent', 'Mozilla/5.0');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Sm_vendor_systems extends REST_Controller
{
	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('vendor/sm_vendor_systems_model');
		$this->load->model('common_model');
		$this->load->model('mail_model');
		$this->load->helper('message_helper');
		$this->load->model('users_model');
	}
	
	function department_get()
	{
		$rs = $this->sm_vendor_systems_model->get_department();
		
		if ($rs)
		{
			$data = $rs;
			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	function view_all_data_get(){
		$rs = $this->sm_vendor_systems_model->get_all_data();
		
		if ($rs)
		{
			$data = $rs;
			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}


		
	public function view_smvs_post(){
		$smvs_data = $this->post('input_smvsdesc');
		$data = $this->sm_vendor_systems_model->search_smvs($smvs_data);
		if ($data)
		{
			$this->response($data);
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'error' => 'Record could not be found'
				], 404);
		}					
	}

	public function save_smvs_post(){
		$department = $this->post('department');
		$smvs_name = $this->post('smvs_name');
		$user_id = $this->post('user_id');
		$smvs_tool_tip = $this->post('smvs_tool_tip');
		$trade_vendor_type = $this->post('trade_vendor_type');

		$data = $this->sm_vendor_systems_model->insert_smvs($department, $smvs_name, $user_id, $smvs_tool_tip, $trade_vendor_type);
		if ($data)
		{
			$this->response($data);
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'error' => 'Invalid insert.'
				], 404);
		}	

	}

	public function update_smvs_post(){
		$department = $this->post('department');
		$smvs_name = $this->post('smvs_name');
		$smvs_id = $this->post('smvs_id');
		$smvs_tool_tip = $this->post('smvs_tool_tip');
		$trade_vendor_type = $this->post('trade_vendor_type');

		$data = $this->sm_vendor_systems_model->update_smvs($smvs_id, $department, $smvs_name, $smvs_tool_tip, $trade_vendor_type);
		if ($data)
		{
			$this->response($data);
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'error' => 'Invalid insert.'
				], 404);
		}	

	}

	public function remove_smvs_post(){
		$smvs_id = $this->post('smvs_id');
		$user_id = $this->post('user_id');

		$data = $this->sm_vendor_systems_model->remove_smvs($smvs_id, $user_id);
		if ($data)
		{
			$this->response($data);
		}
		else
		{
			$this->response([
				'status' => FALSE,
				'error' => 'Invalid insert.'
				], 404);
		}	
	}

	public function vendor_insert_post(){
		$vendor_invite_id = $this->input->post('vendor_invite_id');
		$user_id = $this->input->post('user_id');
		$sm_system_ids = $this->post('sm_system_ids');

		$e_ids = explode("|",$sm_system_ids);
		foreach ($e_ids as $ids){
			$sm_system = $ids;
			$tvt = $this->input->post('tvt_'.$ids);
			$fn = $this->input->post('fn_'.$ids);
			$mi = $this->input->post('mi_'.$ids);
			$ln = $this->input->post('ln_'.$ids);
			$pos = $this->input->post('pos_'.$ids);
			$ea = $this->input->post('ea_'.$ids);
			$mn = $this->input->post('mn_'.$ids);
			$data = $this->sm_vendor_systems_model->vendor_insert($vendor_invite_id, $sm_system, $tvt, $fn, $mi, $ln, $pos, $ea, $mn, $user_id);
		}
		$this->response($data);
	}

	public function vendor_revert_smvs_post(){
		$vendor_invite_id = $this->input->post('vendor_invite_id');
		$data = $this->sm_vendor_systems_model->vendor_revert_smvs($vendor_invite_id);
		$this->response($data);
	}

	public function check_smvs_post(){

		$vendor_invite_id = $this->input->post('vendor_invite_id');

		$data = $this->sm_vendor_systems_model->check_smvs($vendor_invite_id);
		$this->response($data);
	}
}