<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Currency extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfx/currency_model');
		}
		
		// Server's Get Method
		public function add_currency_post(){
			$currency = $this->post('currency');
			$abbreviation = $this->post('abbreviation');
			$country = $this->post('country');

			$data = $this->currency_model->insert_currency($currency, $abbreviation, $country);
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
		
		public function save_currency_post(){
			$currency_id = $this->post('currency_id');
			$currency = $this->post('currency');
			$abbreviation = $this->post('abbreviation');
			$country = $this->post('country');

			$data = $this->currency_model->update_currency($currency_id, $currency, $abbreviation, $country);
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
		
		public function deactivate_currency_post(){
			$currency_id = $this->post('currency_id');

			$data = $this->currency_model->deactivate_currency($currency_id);
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

		public function default_currency_post(){
			$currency_id = $this->post('currency_id');

			$data = $this->currency_model->default_currency($currency_id);
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
		
		public function view_all_currency_post(){
			$data = $this->currency_model->select_all_currency();
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

		public function get_default_post(){
			$data = $this->currency_model->get_default();
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
		
		public function view_currency_post(){
			$currency = $this->post('input_currency');
			$data = $this->currency_model->select_currency($currency);
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
		
		public function sort_currency_post(){
			$order_by = $this->post('order_by');
			$data = $this->currency_model->sort_currency($order_by);
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