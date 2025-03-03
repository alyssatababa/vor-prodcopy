<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Edit_email extends CI_Controller {


	public function index()
	{
		$this->load->view('maintenance/edit_email');
	}

	public function get_all_template()
	{
		$result = $this->rest_app->get('index.php/edit_email_rest/get_email/','','');
		echo json_encode($result);
	}

	public function save_edit_template()
	{
		$data =array(
			'TEMPLATE_ID' => $this->input->post('sid'),
			'DESCRIPTION' => $this->input->post('_desc'),
			'CONTENT' => $this->input->post('_cont'),
			'TEMPLATE_HEADER' => $this->input->post('_template_header')
		);

		$result = $this->rest_app->post('index.php/edit_email_rest/save_edit/',$data,'');
		echo json_encode($result);
		
	}
	public function save_new_template()
	{
		$data =array(
			'DESCRIPTION' => $this->input->post('_desc'),
			'CONTENT' => $this->input->post('_cont'),
			'TEMPLATE_HEADER' => $this->input->post('_template_header')
		);

		$result = $this->rest_app->put('index.php/edit_email_rest/save_new/',$data,'');
		echo json_encode($result);
		
	}

	public function save_deac_template()
	{
		$data['TEMPLATE_ID'] = $this->input->post('sid');
		$result = $this->rest_app->post('index.php/edit_email_rest/deact_temp/',$data,'');
		echo json_encode($result);

	}


}