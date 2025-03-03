<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Tracy\Debugger;

class VendorParameters extends CI_Controller {


	public function __construct(){
		parent::__construct();
		Debugger::enable();
	}
	public function index()
	{
		$data['param_list'] = $this->param_list(1);
		$this->load->view('maintenance/req_documents',$data);
	}

	public function agreement_contracts()
	{	
		$data['param_list'] = $this->param_list(2);
		$this->load->view('maintenance/agr_contracts',$data);
	}


	public function org_type()
	{
		$data['param_list'] = $this->param_list(3);
		$this->load->view('maintenance/org_type',$data);
	}

	public function org_type_documents()
	{
		$this->load->view('maintenance/org_type_doc');
	}


	public function add_param(){
		$data = array(
			'name' => $this->input->post('name'),
			'desc'=>$this->input->post('desc')
		);


		// $this->rest_app->post('index.php/vendor_param/'.$vtype, '$data', 'application/json');
		return $data;

	}

	/**
	 * [param_list get param list records]
	 * @param  [int] $vtype [1 = required docu, 2 = agreements & contracts , 3 = organization_type]
	 * @return [array]        [list of records]
	 */
	
	private function param_list($vtype){
		$records = $this->rest_app->get('index.php/vendor_param/param_list/'.$vtype, '', 'application/json');
		return isset($records->error) ? $records->error : json_decode($records);
	}
}
