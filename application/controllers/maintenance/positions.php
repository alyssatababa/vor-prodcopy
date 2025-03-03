<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Positions extends CI_Controller {


	public function index()
	{
		$data['user_type_list'] = $this->get_user_type();
		$this->load->view('maintenance/positions',$data);
	}
	
	public function search_position()
	{

		$data = array(
		's_type' => $this->input->post('n'),
		's_item' => $this->input->post('m')		
		);

		
		$result = $this->rest_app->get('index.php/position_admin/search_position/', $data, '');	
				
				
				echo json_encode($result);

	
	}
	public function save_position()
	{
		$x = $this->session->userdata['user_id'];
		
		 $data  = array(
		'POSITION_NAME' => $this->input->post('m'),
		'POSITION_CODE'	=> $this->input->post('l'),
		'USER_TYPE_ID' => $this->input->post('n'),
		'CREATED_BY' => $x
		
		);
			
		$result = $this->rest_app->post('index.php/position_admin/add_position/', $data, '');	
			
		echo var_dump($result);
		
	}
	
	public function get_user_type()
	{
		
		$result = $this->rest_app->post('index.php/users_admin/get_user_type/', '', '');	
		$res = json_encode($result);		
		return json_decode($res, true);		
	}
	
	public function edit_pos()
	{
		$x = $this->session->userdata['user_id'];
		
		 $data  = array(
		'POSITION_ID' => $this->input->post('m'),
		'POSITION_NAME' => $this->input->post('l'),
		'POSITION_CODE'	=> $this->input->post('n'),
		'USER_TYPE_ID' => $this->input->post('o')	
		);
			
		$result = $this->rest_app->post('index.php/position_admin/edit_position/', $data, '');	
			
		echo json_encode($result);
	}

	public function del_position()
	{

		$data['POSITION_ID'] = $this->input->post('n');
		$result = $this->rest_app->post('index.php/position_admin/del_position/', $data, '');				
		echo json_encode($result);

	}
	
	
	
}
