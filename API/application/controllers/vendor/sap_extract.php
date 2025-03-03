<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Sap_extract extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/sap_report');
		}
		
		public function data_get(){
			$data = $this->sap_report->get_data();
			
			$this->response($data);
		}
		
		public function vtrad_get($vendor_code, $company_code){
			$data = $this->sap_report->vtrad($vendor_code, $company_code);
			$this->response($data);
		}
	}
?>