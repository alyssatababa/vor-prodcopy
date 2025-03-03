<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sm_vendor_systems extends CI_Controller {
	
	public function index()
	{
		$this->load_smvendortypes();
	}
	
	public function load_smvendortypes(){
		
		$data['result_data'] = $this->rest_app->get('index.php/vendor/sm_vendor_systems/department', '', '');
		$this->load->view('vendor/sm_vendor_systems',$data);
	}
	
	
	
	public function get_all_data(){
		$data['result_data'] = $this->rest_app->get('index.php/vendor/sm_vendor_systems/view_all_data', '', '');
		echo json_encode($data['result_data']);
	}

	public function get_smvs(){
		$data['input_smvsdesc'] = $this->input->post('smvsdesc');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/view_smvs', $data, '');
		echo json_encode($data['result_data']);
	}

	public function save_smvs(){
		$data['department'] = $this->input->post('department');
		$data['smvs_name'] = $this->input->post('smvs_name');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['smvs_tool_tip'] = $this->input->post('smvs_tool_tip');
		$data['trade_vendor_type'] = $this->input->post('trade_vendor_type');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/save_smvs', $data, '');
		echo $data['result_data'];
	}

	public function update_smvs(){
		$data['smvs_id'] = $this->input->post('smvs_id');
		$data['department'] = $this->input->post('department');
		$data['smvs_name'] = $this->input->post('smvs_name');
		$data['smvs_tool_tip'] = $this->input->post('smvs_tool_tip');
		$data['trade_vendor_type'] = $this->input->post('trade_vendor_type');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/update_smvs', $data, '');
		echo $data['result_data'];
	}

	public function remove_smvs(){
		$data['smvs_id'] = $this->input->post('smvs_id');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/remove_smvs', $data, '');
		echo $data['result_data'];	
	}

	public function vendor_insert(){
		$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');
		$data['sm_system_ids'] = str_replace("'", "", $this->input->post('sm_system_ids'));
		$data['user_id'] = $this->session->userdata('user_id');
		$imploded_ids = str_replace("'", "", $this->input->post('sm_system_ids'));
		$e_ids = explode("|",$imploded_ids);
		foreach ($e_ids as $ids){
			$data['tvt_'.$ids] = $this->input->post('tvt_'.$ids);
			$data['fn_'.$ids] = $this->input->post('fn_'.$ids);
			$data['mi_'.$ids] = $this->input->post('mi_'.$ids);
			$data['ln_'.$ids] = $this->input->post('ln_'.$ids);
			$data['pos_'.$ids] = $this->input->post('pos_'.$ids);
			$data['ea_'.$ids] = $this->input->post('ea_'.$ids);
			$data['mn_'.$ids] = $this->input->post('mn_'.$ids);
		}

		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/vendor_insert', $data, '');
		echo($data['result_data']);
	}

	public function vendor_revert_smvs(){
		$data['vendor_invite_id'] = $this->input->post('vendor_invite_id');	
		$data['result_data'] = $this->rest_app->post('index.php/vendor/sm_vendor_systems/vendor_revert_smvs', $data, '');
		echo($data['result_data']);
	}
}