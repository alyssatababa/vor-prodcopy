<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Server_status extends CI_Controller {

	/*
    This class will contain a set of tests to be run for testing new updates/ deployment
    things to test:
    app + etl + web servers connectivity.
    database connectivity.
    config mismatch for app etl and web modules.

	*/
	private $web_server;
	private $app_server;
	private $etl_server;

	public function __construct() {
		parent::__construct();
			$this->load->library('unit_test'); // load the unit test library.

				$this->web_server = json_decode(json_encode(array(  //decode no jutsu
			  'server' => base_url(),
					'server_ip' => $_SERVER['SERVER_ADDR'],
					'server_port' => $_SERVER['SERVER_PORT'],
					'server_name' => $_SERVER['SERVER_NAME'],
					'server_time' => date('m/d/Y h:i:s a', time()),
					'app_server' => $this->config->item('app_server_api')['server'],
					'etl_server' => $this->config->item('etl_server_api')['server']
			)));
		// $this->web_server['server_time'] = date('m/d/Y h:i:s a', time());
	}

  	public function index() {
		
		
		$no_error_in_etl = $this->rest_etl->get('index.php/unit_tests/common/get_directories', null, 'application/json');
		
		$this->test_connection();
    	$test_data['result'] = $this->unit->result();
		$test_data['web_server']	= $this->web_server;
		$test_data['etl_server'] 	= $this->etl_server;
		$test_data['app_server'] 	= $this->app_server;
		$test_data['web_folders_is_writable'] = $this->check_web_directory();
		$test_data['etl_folders_is_writable'] = $no_error_in_etl;
		//echo "<pre>";
		//echo json_encode($test_data['result'], JSON_PRETTY_PRINT);
	
		$this->load->view('server_status/server_status_view', $test_data);
	}
	
	private function check_web_directory(){
			
		//Set the required documents
		$required_folders = array(
			'public',
			'rfx_upload_attachment',
			'vendor_upload_agreements',
			'vendor_upload_documents',
			'templates',
			'download_agreement'
		);
		//Get the folders in root
		$root_dir = glob (FCPATH .'*', GLOB_ONLYDIR );

		$filtered_dir = array();
		foreach($root_dir as $key => $rd){
			foreach($required_folders as $rf){
				if(strpos($rd, $rf) !== false){
					$filtered_dir[] = $root_dir[$key];
				}
			}
		}
		//If has sub directory variable
		$has_sub_dir = false;

		////remove \
		//foreach($filtered_dir as $key => $dir){
		//	$filtered_dir[$key] = explode('\\',$dir)[0];
		//}
		//remove duplicate
		$filtered_dir = array_unique($filtered_dir);

		//Get our total dir
		$total_dir = count($filtered_dir);
		for($x = 0; $x < $total_dir; $x++){
		//Check if the directory is not empty
			$ar = glob($filtered_dir[$x] . '/*', GLOB_ONLYDIR);
			$has_sub_dir = !empty($ar);
			
			//If not empty
			if($has_sub_dir){
				$is_included = true;
				foreach($ar as $a){
					if((strpos($a, 'templates')!== false || strpos($a, 'download_agreement') !== false )){
						$is_included = false;
					}
				}
				if($is_included){
				//Merge the sub directory to our filtered_directory
				$filtered_dir = array_merge($filtered_dir, glob($filtered_dir[$x] . '/*', GLOB_ONLYDIR));
				
				//Set our new total
				$total_dir = count($filtered_dir);
				
				//Reset boolean if has sub directory
				$has_sub_dir = false;
                                }
			}	
		}
		$no_error_in_web = true;
		foreach($filtered_dir as $dir){
			if(!is_writable($dir)){
				$no_error_in_web = false;
				log_message('error', 'NOT WRITABLE: ' . $dir);
			}
		}
		return $no_error_in_web;
	}

	public function test_connection(){
		#app connection
		$this->app_server = $this->rest_app->get('index.php/unit_tests/common/connect', null, 'application/json');
		$this->unit->run($this->app_server != null, true, 'Connect to App server', 'test if api result is not null');
		$this->unit->run(isset($this->app_server->status) ? $this->app_server->status : null, true, 'Connect to App server', 'test if api result status is connected');

		#etl connection
		$this->etl_server = $this->rest_etl->get('index.php/unit_tests/common/connect', null, 'application/json');
		$this->unit->run($this->etl_server!=null, true, 'Connect to ETL server', 'test if api result is not null');
		$this->unit->run(isset($this->etl_server->status) ? $this->etl_server->status : null, true, 'Connect to ETL server', 'test if api result status is connected');

		#db config
		$this->unit->run(isset($this->app_server->db_hostname) ? $this->app_server->db_hostname : null == isset($this->etl_server->db_hostname) ? $this->etl_server->db_hostname : null, true, 'compare api config', 'compare if app and etl are using the same tns.');
		$this->unit->run(isset($this->app_server->db_database) ? $this->app_server->db_database : null == isset($this->etl_server->db_database) ? $this->etl_server->db_database : null, true, 'compare api config', 'compare if app and etl are connected to the same database');
		$this->unit->run(isset($this->app_server->db_username) ? $this->app_server->db_username : null == isset($this->etl_server->db_username) ? $this->etl_server->db_username : null, true, 'compare api config', 'compare if app and etl are using the same user/schema');
	}
	
	public function refresh_connection(){
		$this->test_connection();
		
		$no_error_in_etl = $this->rest_etl->get('index.php/unit_tests/common/get_directories', null, 'application/json');
		$test_data['web_server'] = $this->web_server;
		$test_data['etl_server'] = $this->unit->result()[3]['Result'];
		$test_data['app_server'] = $this->unit->result()[1]['Result'];
		$test_data['web_folders_is_writable'] = $this->check_web_directory();
		$test_data['etl_folders_is_writable'] = $no_error_in_etl;
		echo json_encode($test_data);
	}

	public function test_mail(){
		// $config = array(
		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'ssl://mail.sandmansystems.com',
		// 	'smtp_port' => 465,
		// 	'smtp_user' => 'support@sandmansystems.com',
		// 	'smtp_pass' => 'sandman0198',
		// 	'mailtype' => 'html',
		// 	'charset' => 'iso-8859-1',
		// 	'newline' => '\r\n',
		// 	'wordwrap' => TRUE);
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => '10.111.121.203',
			'smtp_port' => 25,
			'smtp_user' => 'no-reply@smvendorportal.com',
			'smtp_pass' => 'sandman0198',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'newline' => '\r\n',
			'wordwrap' => TRUE);
			
		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");
 		$this->email->set_crlf("\r\n"); //uncomment for prod
		
		$this->email->clear(true);
		$this->email->from('support@sandmansystems.com');
		$this->email->to('jobert.beltran@sandmansystems.com');

		
		$this->email->subject('CABLE TECH INSTALLATION SERVICES - Additional Requirements Submission');
		$this->email->message('Dear CABLE TECH INSTALLATION SERVICES,

		Please submit your additional requirements on or before 15-Mar-2018.
		
		Thank you,
		SM Vendor Portal Admin ');

		$this->email->send(FALSE);
		echo $this->email->print_debugger();
		// if (!$this->email->send())
		// 	print_debugger();
		// else {
		// 	echo 'mail sent';
		// }
	}

}

?>
