<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Main extends CI_Controller {

		public function __construct() {
	 		parent::__construct();
	 	}

		public function index()
		{
			$data['notifications'] = $this->rest_app->get('index.php/business_doc/notifications/'.$this->session->userdata('vendor_id'),'' , 'application/json');
			$post_data['user_id'] = $this->session->userdata('user_id');
			$post_data['action_id']  = 3;
			$this->rest_app->post('index.php/business_doc/log_action/',$post_data,'');
			$this->load->view('businessdoc/main_view',$data);
		}


		public function test(){
			// $path = base_url('assets/js/ie-emulation-modes-warning.js');
			// $headers = get_http_response_code($path).'<br>';
			// echo $headers;
			// if ($headers){
			// 	echo 'exists';
			// } else {
			// 	echo 'nope';
			// }

			// echo js_path('ie-emulation-modes-warning.js');
			echo $_SERVER['HTTP_USER_AGENT'];
		}


		public function activity_monitor(){
			$this->load->view('businessdoc/activity_monitor_view');
		}

		public function get_file_queue(){
			$result_data = $this->rest_etl->get('index.php/bd_file_queue/queue/', array('status_ids'=>'0,5'), 'application/json');
			echo json_encode($result_data,JSON_PRETTY_PRINT);
		}
		
		public function get_running_file(){
			$result_data = $this->rest_etl->get('index.php/bd_file_queue/queue/', array('status_ids'=>'1,2,3,4'), 'application/json');
			echo json_encode($result_data,JSON_PRETTY_PRINT);
		}

		public function reset_file_queue(){
			$file_queue_id = $this->input->post('file_queue_id');
			$result_data = $this->rest_etl->patch('index.php/bd_file_queue/queue/', array('file_queue_id'=>$file_queue_id), 'text');//array('file_queue_id' => $file_queue_id),
			$this->rest_etl->debug();
			echo json_encode($result_data,JSON_PRETTY_PRINT);
		}
		
		public function archive_file_queue(){
			$file_queue_id = $this->input->post('file_queue_id');
			$result_data = $this->rest_etl->post('index.php/bd_file_queue/archive_queue/', array('file_queue_id'=>$file_queue_id), 'text');//array('file_queue_id' => $file_queue_id),
			$this->rest_etl->debug();
			echo json_encode($result_data,JSON_PRETTY_PRINT);
		}
		
		public function run_etl_command(){
			$uri = $this->input->post("uri");
			$result_data = $this->rest_etl->get($uri, null, 'application/json');
			echo json_encode($result_data,JSON_PRETTY_PRINT);
			
		}
	}
?>
