<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Epaf_ims_extract extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/epaf_ims_report');
		}
		
		public function data_get(){
			$data = $this->epaf_ims_report->get_data();
			$this->response($data);
		}

		public function email_get(){
			$data = $this->epaf_ims_report->get_emails();
			$this->response($data);
		}
	}
?>