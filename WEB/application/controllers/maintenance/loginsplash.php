<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginsplash extends CI_Controller {

	public function index()
	{		
		$data['dpa'] = $this->rest_app->get('index.php/logsplash_template/dpa_show_hide/', '', '');	
		$this->load->view('maintenance/loginsplash',$data);
	}

		
	public function save_visit_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'LST_TITLE' => $n[1],
			'LST_MESSAGE' => $n[0],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/logsplash_template/create_vit/', $data, '');	
		echo json_encode($result);
	}

	public function get_all()
	{

	$result = $this->rest_app->get('index.php/logsplash_template/get_all/', '', '');	
	echo json_encode($result);

	}

	

	public function edit_splash_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'LST_ID' => $n[0],
			'LST_TITLE' => $n[2],
			'LST_MESSAGE' => $n[1],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/logsplash_template/edit_vit/', $data, '');	
		echo json_encode($result);
	}

	public function del_login_template()
	{

		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];

		$data = array(
			'LST_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/logsplash_template/del_vit/', $data, '');	
		echo json_encode($result);
	}

	public function c_selected_tmplt()
	{
		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];

		$data = array(
			'LST_ID' => $n,
			'USER_ID' => $x
			);

		$result = $this->rest_app->put('index.php/logsplash_template/c_seltmplt/', $data, '');	
		echo json_encode($result);
	}

	public function show_hide_dpa()
	{
		
		$data['dpa'] = $this->input->post('data');
		$result = $this->rest_app->put('index.php/logsplash_template/showhide_dpa/', $data, '');	
		//echo $result;
		var_dump($result);




	}
	
	

}
