<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');
use Tracy\Debugger;
	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Vendor_param extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			Debugger::enable();
			$this->load->model('vendor_param_model','vparam_model');
		}

		
		public function param_list_get($vtype){

			$data = $this->vparam_model->get_many_by('VENDOR_PARAM_TYPE', $vtype);
			if($data)
			{
				$this->response(json_encode($data));
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'No records found'
					], 404);
			}			
		}


		public function add_param_post(){

			$data = $this->vparam_model->get_many_by('VENDOR_PARAM_TYPE', $vtype);
			if($data)
			{
				$this->response(json_encode($data));
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'No records found'
					], 404);
			}			
		}

	
	}

?>