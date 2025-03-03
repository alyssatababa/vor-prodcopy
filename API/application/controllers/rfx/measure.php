<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Measure extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/measure_model');
		}
		
		// Server's Get Method
		public function add_measure_post(){
			$measure = $this->post('measure');
			$abbreviation = $this->post('abbreviation');

			$data = $this->measure_model->insert_measure($measure, $abbreviation);
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
		
		public function save_measure_post(){
			$measure_id = $this->post('measure_id');
			$measure = $this->post('measure');
			$abbreviation = $this->post('abbreviation');

			$data = $this->measure_model->update_measure($measure_id, $measure, $abbreviation);
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
		
		public function deactivate_measure_post(){
			$measure_id = $this->post('measure_id');

			$data = $this->measure_model->deactivate_measure($measure_id);
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
		
		public function view_all_measure_post(){
			$data = $this->measure_model->select_all_measure();
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
		
		public function view_measure_post(){
			$measure = $this->post('input_measure');
			$data = $this->measure_model->select_measure($measure);
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
		
		public function sort_measure_post(){
			$order_by = $this->post('order_by');
			$data = $this->measure_model->sort_measure($order_by);
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