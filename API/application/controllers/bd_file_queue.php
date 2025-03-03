<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Bd_file_queue extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('bd_file_queue_model');
		}

		// Server's Get Method
		public function queue_get(){
			$file_queue_id = $this->get('file_queue_id');

			$data['queue'] = $this->bd_file_queue_model->read($file_queue_id);

			if ($data['queue'])
			{
				$this->response(array_merge($data,[
					'status' => TRUE,
					'error' => ''
				]));
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'no record found.'
					], 404);
			}

		}

		public function queue_patch(){
			$file_queue_id = $this->patch('file_queue_id');

			$data['queue'] = $this->bd_file_queue_model->reset_queue($file_queue_id);
			// $data['queue'] = ['queue'=>1];
			if ($data['queue'])
			{
				$this->response(array_merge($data,[
					'status' => TRUE,
					'error' => ''
				]));
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'no record found.'
					], 404);
			}

		}

		// Server's Delete Method / archive
		public function queue_delete(){
			$file_queue_id = $this->delete('file_queue_id');

			$data['queue'] = $this->bd_file_queue_model->reset_queue($file_queue_id);
			// $data['queue'] = ['queue'=>1];
			if ($data['queue'])
			{
				$this->response(array_merge($data,[
					'status' => TRUE,
					'error' => ''
				]));
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'no record found.'
					], 404);
			}

		}
	}

?>
