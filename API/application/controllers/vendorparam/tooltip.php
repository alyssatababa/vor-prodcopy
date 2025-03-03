<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Tooltip extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/tooltip_model');
		}
		

		
		// Server's Get Method
		
		public function save_tooltip_post(){
			$tid = $this->post('tooltip_id');
			$screen_name = $this->post('screen_name');
			$tooltip = $this->post('tooltip');

			$data = $this->tooltip_model->update_tooltip($tid, $screen_name, $tooltip);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Invalid insert.'
					], 404);
			}			
		}
		
		public function view_all_tooltip_post(){
			$data = $this->tooltip_model->select_all_tooltip();
			
			if ($data)
			{	
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}		
		}
		
		public function view_tooltip_post(){
			$tooltip = $this->post('input_tooltip');
			$data = $this->tooltip_model->select_tooltip($tooltip);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}					
		}

	}

?>