<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class vrd_id_extract extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendor/vrd_id_report');
		}
		
		public function data_get(){
			$data = $this->vrd_id_report->get_data();
			
			$this->response($data);
		}
	}
?>