<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Logsplash_template extends REST_Controller {

		public function __construct() {
			parent::__construct();
			$this->load->model('logsplash_model');
		}



	public function create_vit_post()
	{

		$data = array(
			'LST_TITLE' => $this->post('LST_TITLE'),
			'LST_MESSAGE' => $this->post('LST_MESSAGE'),
			'USER_ID' => $this->post('USER_ID')
			);

			$result = $this->logsplash_model->m_create_vist($data);


			$this->response([
			'data' => $result
			]);

	}

	public function get_all_get()
	{

		$result = $this->logsplash_model->m_get_all();

		$this->response([
			'data' => $result
			]);


	}



	public function edit_vit_post()
	{

		$data = array(
			'LST_TITLE' => $this->post('LST_TITLE'),
			'LST_MESSAGE' => $this->post('LST_MESSAGE'),
			);

		$data2 = array(
			'LST_ID' => $this->post('LST_ID'),
			);

			$result = $this->logsplash_model->m_edit_ven($data,$data2);


			$this->response([
			'data' => $result
			]);
	}


	public function del_vit_post()
	{

		$data2 = array(
			'LST_ID' => $this->post('LST_ID'),
			);
			$result = $this->logsplash_model->m_del_ven($data2);

			$this->response([
			'data' => $result
			]);

	}

	public function c_seltmplt_put()
	{

		$data = array(
			'LST_ID' => $this->put('LST_ID')
		);
			$result = $this->logsplash_model->m_sel_sel($data);

			$this->response(
			 $result
			);
	}

	public function dpa_show_hide_get()
	{

		$res = $this->logsplash_model->select_query('SMNTP_SYSTEM_CONFIG',array('CONFIG_NAME' => 'dpa_show_or_not'),'CONFIG_VALUE');
		$this->response($res);
	}
	public function showhide_dpa_put()
	{

		$x = $this->put('dpa');
		if($x == 2){
			$x = 0;
		}
		$val = array('CONFIG_VALUE' =>$x);
		$res = $this->logsplash_model->update_query('SMNTP_SYSTEM_CONFIG',$val,array('SYSTEM_CONFIG_ID' => 82));
		$this->response($res);
	}





}
