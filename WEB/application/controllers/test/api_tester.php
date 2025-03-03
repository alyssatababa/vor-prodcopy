<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Api_tester extends CI_Controller {

		// load rest api app server
		public function __construct() {
			parent::__construct();
			$this->load->library('rest', $this->config->item('app_server_api'));
		}

		public function index()
		{
			$data['app_server_url'] =  $this->config->item('app_server_api')['server'];
			$data['method_array'] = array('GET'=>'GET','PUT'=>'PUT','DELETE'=>'DELETE');

			$this->load->view('test/api_tester_view', $data);
		}

		public function execute_api(){
			$data['in_method'] = $this->input->post('in_method');
			$data['in_uri'] = $this->input->post('in_uri');

			$data['in_key1'] = $this->input->post('in_key1');
			$data['in_val1'] = $this->input->post('in_val1');
			$data['in_key2'] = $this->input->post('in_key2');
			$data['in_val2'] = $this->input->post('in_val2');
			$data['in_key3'] = $this->input->post('in_key3');
			$data['in_val3'] = $this->input->post('in_val3');
			$data['in_key4'] = $this->input->post('in_key4');
			$data['in_val4'] = $this->input->post('in_val4');

			$data['params'] = array();

			if ($data['in_key1'] && $data['in_key1']!='') $data['params'][$data['in_key1']] = $data['in_val1'];
			if ($data['in_key2'] && $data['in_key2']!='') $data['params'][$data['in_key2']] = $data['in_val2'];
			if ($data['in_key3'] && $data['in_key3']!='') $data['params'][$data['in_key3']] = $data['in_val3'];
			if ($data['in_key4'] && $data['in_key4']!='') $data['params'][$data['in_key4']] = $data['in_val4'];

			// echo 'hahahaha';
			// echo $in_method.$in_uri;
			$data['result'] = '';
			$data['result_data'] = '';
			switch($data['in_method']){
				case 'GET';
					$data['result_data'] = $this->rest->get($data['in_uri'], $data['params'], 'application/json');
					// $data['result'] = $this->rest->debug();
					break;

				case 'PUT';
					$data['result_data'] = $this->rest->put($data['in_uri'], $data['params']);
					// $this->rest->debug();
					break;

				case 'DELETE';
					$data['result_data'] = $this->rest->delete($data['in_uri'], $data['params']);
					break;

				default:
					break;
			}
			// var_dump($data['result']);
			$this->load->view('test/api_result_view', $data);

		}

		public function test(){
			$data = array('menu_id'=>'1',
				'menu_name'=>'test_name',
				'description'=>'test description',
				'sorting'=>'0',
				'menu_link'=>'/link/link/link'
			);
			$data['result_data'] = $this->rest->put('index.php/menus/menu', $data);
			$this->rest->debug();
		}
	}

?>