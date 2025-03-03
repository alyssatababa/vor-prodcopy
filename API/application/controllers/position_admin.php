<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Position_admin extends REST_Controller {
	
	public function __construct() {
			parent::__construct();
			$this->load->model('position_admin_model');
		}
	
	public function add_position_post()
	{		
		$data = array(
		'POSITION_NAME' => $this->post('POSITION_NAME'),
		'POSITION_CODE' => $this->post('POSITION_CODE'),
		'CREATED_BY' => $this->post('CREATED_BY'),
		'USER_TYPE_ID' => $this->post('USER_TYPE_ID')
		);		
		$result = $this->position_admin_model->save_position($data);
		
			$this->response([
			'data' => $result
			]);	
	}
	
	public function search_position_get()
	{
		
		$data = array(
		's_type' => $this->get('s_type'),
		's_item' => $this->get('s_item')		
		);
		
		
	$result = $this->position_admin_model->search_position($data);
	
	$this->response([
	'data' => $result]);	
	}
	
	public function edit_position_post()
	{		
		$data = array(
		'POSITION_NAME' => $this->post('POSITION_NAME'),
		'POSITION_CODE' => $this->post('POSITION_CODE'),
		'USER_TYPE_ID' => $this->post('USER_TYPE_ID')
		);	

		$data2 = array('POSITION_ID' => $this->post('POSITION_ID'));
		$result = $this->position_admin_model->s_editpos($data,$data2);
		
			$this->response([
			'data' => $result
			]);	
	}

	public function del_position_post()
	{		
	
		$data2 = array('POSITION_ID' => $this->post('POSITION_ID'));
		$result = $this->position_admin_model->s_delpos($data2);
		
			$this->response([
			'data' => $result
			]);	
	}

	
	
	
	
}