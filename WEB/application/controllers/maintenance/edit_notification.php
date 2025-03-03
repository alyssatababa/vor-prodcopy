<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class edit_notification extends CI_Controller {


	public function index()
	{
		$this->load->view('maintenance/edit_notification');
	}

	public function get_all_template()
	{
		$result = $this->rest_app->get('index.php/edit_notification/get_notification/','','');
		// echo 1;
		echo json_encode($result);
	}

	public function save_edit_template()
	{
		$data =array(
			'MESSAGE_DEFAULT_ID' => $this->input->post('sid'),
			'TOPIC' => $this->input->post('_desc'),
			'DESCRIPTION' => $this->input->post('_desc_2'),
			'MESSAGE' => $this->input->post('_cont')
		);

		$result = $this->rest_app->post('index.php/edit_notification/save_edit/',$data,'');
		echo json_encode($result);
		
	}
	public function save_new_template()
	{
		$data =array(
			'TOPIC' => $this->input->post('_desc'),
			'MESSAGE' => $this->input->post('_cont')
		);

		$result = $this->rest_app->put('index.php/edit_notification/save_new/',$data,'');
		echo json_encode($result);
		
	}

	public function save_deac_template()
	{
		$data['TEMPLATE_ID'] = $this->input->post('sid');
		$result = $this->rest_app->post('index.php/edit_notification/deact_temp/',$data,'');
		echo json_encode($result);

	}


}