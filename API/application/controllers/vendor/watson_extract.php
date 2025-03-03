<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Watson_extract extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/watson_report');
		}
		
		public function data_get(){
			$data = $this->watson_report->get_data();
			$this->response($data);
		}
		
		public function bdo_get($vendor_code){
			$data = $this->watson_report->get_bdo($vendor_code);
			$this->response($data);
		}
	}
?>