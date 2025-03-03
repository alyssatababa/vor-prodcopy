<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class mms_extract extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/mms_report');
		}
		
		public function data_get(){
			$data = $this->mms_report->get_data();
			
			$this->response($data);
		}
	}
?>