<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function index()
	{
		if ($this->session->userdata('logged_in')!=1) {
			redirect('login','refresh');
		}
		
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_type_id'] = $this->session->userdata('user_type_id');
		$data['v_business_type'] = $this->session->userdata('v_business_type');
		
		if ($data['position_id'] == 10) // if vendor
		{
			$param2['user_id'] 	= $this->session->userdata('user_id');
			$vendor_status 		= $this->rest_app->get('index.php/vendor/registration_api/get_vendor_status', $param2, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//var_dump($vendor_status);
			//print_r($data);
			//die();
			$data['trade_vendor_type']  = $vendor_status->trade_vendor_type;
			$data['business_type']  = $vendor_status->business_type;
			if(empty($data['business_type'])){
				$data['business_type'] = 2;
			}
			
			$data['invite_id'] 			= $vendor_status->invite_id;
			$data['status_id'] 			= $vendor_status->status_id;
			$data['upload_complete'] 	= $vendor_status->upload_complete;
		}else{
			$data['business_type'] = null;
		}
		$data['in_method'] = 'GET';
		$data['in_uri'] = 'index.php/dashboard/header';
		$param['user_position_id'] = $this->session->userdata('position_id');
		$param['vendor_type_id'] = $data['business_type'];
		$data['result_data'] = '';
		//print_r($param);
		$data['result_data'] = $this->rest_app->get($data['in_uri'], $param, 'application/json');
		$data['dpa'] = $this->rest_app->get('index.php/logsplash_template/dpa_show_hide/', '', '');	
		$rs = $this->rest_app->get('index.php/common_api/srvDate','','application/json');
	


	//	$sDate = $rs[0]->NOW;
		//$rs = date('Y-m-d',$sDate);

/*		$sDate = explode(" ",($rs[0]->NOW));
		$sDate[0] = $sDate[0]."";
		$tmDate = explode("-",$sDate[0]);
		$tTime = explode(":",$sDate[1]);

		$fDate = implode("/", $tmDate);*/

		//$data['l_date'] = date('d M Y h:i:s',strtotime($fDate ." ". $sDate[1]));


		$data['l_date'] = $rs[0]->NOW;

		
		$this->load->view('common/header', $data);
		$this->load->view('common/body');
		$this->load->view('common/footer');
	}

	function home_page()
	{
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_type_id'] = $this->session->userdata('user_type_id');
		$data['v_business_type'] = $this->session->userdata('v_business_type');
		$data['hide_show_rfq'] = 0;


		$rfq = $this->rest_app->get('index.php/vendor/registration_api/hide_show_rfq', '', 'application/json');

		$data['hide_show_rfq'] = 0;
		if(isset($rfq[0]->CONFIG_VALUE)){
			$data['hide_show_rfq'] = $rfq[0]->CONFIG_VALUE;
		}

		

		//hide show rfq
		
		if ($data['position_id'] == 10) // if vendor
		{
			$param2['user_id'] 	= $this->session->userdata('user_id');
			$vendor_status 		= $this->rest_app->get('index.php/vendor/registration_api/get_vendor_status', $param2, 'application/json');
			//$this->rest_app->debug();
			//echo "<pre>";
			//var_dump($vendor_status);
			//print_r($data);
			//die();
			$data['trade_vendor_type']  = $vendor_status->trade_vendor_type;
			$data['invite_id'] 			= $vendor_status->invite_id;
			$data['status_id'] 			= $vendor_status->status_id;
			$data['upload_complete'] 	= $vendor_status->upload_complete;
		}
		//echo "<pre>";
		//var_dump($data);
		//die();
		$this->load->view('dashboard/home_page', $data);
	}
}
