<?php
Class Version_hash extends CI_Controller{
	const ROOT = '/opt/rh/httpd24/root/var/www/html'; #online prod
	// const ROOT = '/opt/rh/httpd24/root/var/www/html/dev'; #online dev
	// const ROOT = '/opt/rh/httpd24/root/var/www/html/qa'; #online qa
	// const ROOT = 'F:\inetpub\smscoreonline\SMNTP_DEV2\web'; #local
	const PROD_URL = "https://smvendorportal.com/index.php/unit_tests/version_hash/get_hashes";
	const DEV_URL = "http://uat.smvendorportal.com/dev/index.php/unit_tests/version_hash/get_hashes";
	const QA_URL = "http://uat.smvendorportal.com/qa/index.php/unit_tests/version_hash/get_hashes";
	const UAT_URL = "http://uat.smvendorportal.com/uat/index.php/unit_tests/version_hash/get_hashes";
	const APP = 'application'; 
	const ASSETS = 'assets'; 
	const JS = 'js'; 
		
	public function __construct() {
		parent::__construct();
	}

	private function get_result_from_url($url){
		$ch = curl_init();

		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			// CURLOPT_HEADER         => true,
			CURLOPT_FOLLOWLOCATION => true,
			// CURLOPT_HTTPGET		 => true,
			CURLOPT_ENCODING       => "",
			CURLOPT_AUTOREFERER    => true,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT        => 120,
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_SSL_VERIFYPEER => false,
		);
		curl_setopt_array( $ch, $options );
		$response = curl_exec($ch); 
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ( $httpCode != 200 ){
			return false;
		} else {
			return $response;
		}

		curl_close($ch);

		
	}

	function compare_hashes(){
		
		
		$data = $this->input->post();
		
		if(empty($data)){
			die();
		}
		$this->session->set_userdata('ch', $data);
		
		$result = array();
		$result['merged'] = array();
		

		foreach($data as $value){
			if($value == 'local'){				
				$json = $this->get_hashes(false);
				$result['local'] = json_decode($json, true);
				$result['merged'] = array_merge($result['merged'], $result['local']);
			}else{
				$url = '';
				if($value == 'dev'){
					$url = SELF::DEV_URL;
				}else if($value == 'qa'){
					$url = SELF::QA_URL;
				}else if($value == 'uat'){
					$url = SELF::UAT_URL;
				}else if($value == 'prod'){
					$url = SELF::PROD_URL;
				}
				$json = $this->get_result_from_url($url);
				$result[$value] = json_decode($json, true);	
				$result['merged'] = array_merge($result['merged'], $result[$value]);
			}
		}
		
		//echo json_encode($result);
		$table_str = '<div class="row"><div class="col-md-12"><div class="table-responsive table-striped">';
		$table_str .= '<table>';
		$table_str .=  '<table id="table_hashes" class="table table-hover table-striped"><thead>';
		$table_str .= '<tr>';
		$table['header'] = array(
			'Server',
			'Directory',
			'Path'
		);
		
		foreach($data as $val){
			$table['header'][] = strtoupper($val) . ' Hash';
		}
		
		foreach($table['header'] as $val){
			$table_str .= '<th>' . $val .'</th>';
		}
		
		$table_str .=  '</tr></thead><tbody>';
		
		if( ! empty($result['merged'])){
			foreach ($result['merged'] as $sev_key => $serv_item) {
				
				//app, web, etl
				$merge_server = $result['merged'][$sev_key];
				//Servers, dev uat qa prod local
				$servers = array();
				foreach($data as $val){
					$servers[$val] = $result[$val][$sev_key];
				}
				
				foreach($merge_server as $key => $item){
					
					$servers_item = array();
					//$val = Server , $key = App, etl, web
					foreach($data as $val){
						$servers_item[$val] = $servers[$val][$key];
					}
					
					if (is_array($item) && count($item)>0) {
						
						foreach ($item as $item_key => $item_array) {
							
							if (count($item_array)>0) {
								
								$item_content = $item_array[0];
								
								$server_item_array = array();
								
								//Server sub folder: controllers, views, etc...
								foreach($data as $val){
									$server_item_array[$val] = (isset($servers_item[$val][$item_key]) ? $servers_item[$val][$item_key] : null);
								}
								
								//Content
								$server_item_content_array = array();
								
								foreach($data as $val){
									$server_item_content_array[$val] = (count($server_item_array[$val]) > 0 ? $server_item_array[$val][0] : null);
								}

								//File Hash
								$server_hash_array = array();
								
								foreach($data as $val){
									$server_hash_array[$val] = (isset($server_item_content_array[$val]) ? $server_item_content_array[$val]['hash'] : ' ');
								}
								
								//Server file, uat dev prod qa local
								$server_file_array = array();
								
								foreach($data as $val){
									$server_file_array[$val] = (isset($server_item_content_array[$val]) ? $server_item_content_array[$val]['clean_path'] : ' ');
								}
								
								$warning_class = '';
								
								//Check hash 
								foreach($server_hash_array as $index => $val){
									
									//Get the first hash
									foreach($server_hash_array as $index2 => $val2){
								
										// Compare the first hash to all hashes
										// If not equal break the loop
										if($val !== $val2 && $index !== $index2){
											$warning_class = 'danger';
											break;
										}
									}
									
									if( ! empty($warning_class)){
										break;
									}
								}
					
								$table_str .=  '<tr class="' . $warning_class . '">';
								
								$table_str .= '<td>'.$sev_key.'</td>';
								$table_str .= '<td>'.$key.'</td>';
								$table_str .= '<td>'.$item_content['clean_path'].'</td>';
								
								//Loop hashes
								foreach($data as $val){
									$table_str .= '<td>' . $server_hash_array[$val] . '</td>';
								}
								
								$table_str .=  '</tr>';
							}
							
						}
						
					}
				}
			}
		}
		
		echo json_encode(array(
			'result'	=> $table_str
		));
		// $this->load->library('TreeWalker');
		// echo '<pre>'.$this->treewalker->getdiff($prod, $dev, false).'</pre>'; // false -> with slashs
	}


	function get_hashes($echo_result = true)
	{
		$this->load->library('DirectoryCrawler');
		$hashes['config'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'config',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['controllers'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'controllers',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['libraries'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'libraries',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['models'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'models',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['views'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'views',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['js'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::ASSETS.DIRECTORY_SEPARATOR.SELF::JS,SELF::ROOT.DIRECTORY_SEPARATOR.SELF::ASSETS.DIRECTORY_SEPARATOR.SELF::JS);

		$response['web'] = $hashes;
		$response['app'] = $this->rest_app->get('index.php/unit_tests/version_hash/hashes', null, 'application/json');
		$response['etl'] = $this->rest_etl->get('index.php/unit_tests/version_hash/hashes', null, 'application/json');


		// echo '<pre>'.json_encode($response,JSON_PRETTY_PRINT).'</pre>';
		if ($echo_result)
			echo json_encode($response,JSON_PRETTY_PRINT);
		else
			return json_encode($response,JSON_PRETTY_PRINT);
	}

	public function export_data($mode=0){
		$result = array();
		$result['merged'] = array();
		
		$data = $this->session->userdata('ch');
		
		if(empty($data)){
			die();
		}
		foreach($data as $value){
			if($value == 'local'){				
				$json = $this->get_hashes(false);
				$result['local'] = json_decode($json, true);
				$result['merged'] = array_merge($result['merged'], $result['local']);
			}else{
				$url = '';
				if($value == 'dev'){
					$url = SELF::DEV_URL;
				}else if($value == 'qa'){
					$url = SELF::QA_URL;
				}else if($value == 'uat'){
					$url = SELF::UAT_URL;
				}else if($value == 'prod'){
					$url = SELF::PROD_URL;
				}
				$json = $this->get_result_from_url($url);
				$result[$value] = json_decode($json, true);	
				$result['merged'] = array_merge($result['merged'], $result[$value]);
			}
		}
		
		
		// $data[] = array('x'=> $x, 'y'=> $y, 'z'=> $z, 'a'=> $a);
		header("Content-type: application/csv");
		header("Content-Disposition: attachment; filename=\"version_hashes".".csv\"");
		header("Pragma: no-cache");
		header("Expires: 0");

		$handle = fopen('php://output', 'w');
		
		$header = array(
			'Server',
			'Directory',
			'Path'
		);
		
		foreach($data as $val){
			$header[] = strtoupper($val) . ' Hash';
		}
		$header[] = 'Conclict'; 
		
		fputcsv($handle, $header);
		
		if( ! empty($result['merged'])){
			foreach ($result['merged'] as $sev_key => $serv_item) {
				
				//app, web, etl
				$merge_server = $result['merged'][$sev_key];
				//Servers, dev uat qa prod local
				$servers = array();
				foreach($data as $val){
					$servers[$val] = $result[$val][$sev_key];
				}
				
				foreach($merge_server as $key => $item){
					
					$servers_item = array();
					//$val = Server , $key = App, etl, web
					foreach($data as $val){
						$servers_item[$val] = $servers[$val][$key];
					}
					
					if (is_array($item) && count($item)>0) {
						
						foreach ($item as $item_key => $item_array) {
							
							if (count($item_array)>0) {
								
								$item_content = $item_array[0];
								
								$server_item_array = array();
								
								//Server sub folder: controllers, views, etc...
								foreach($data as $val){
									$server_item_array[$val] = (isset($servers_item[$val][$item_key]) ? $servers_item[$val][$item_key] : null);
								}
								
								//Content
								$server_item_content_array = array();
								
								foreach($data as $val){
									$server_item_content_array[$val] = (count($server_item_array[$val]) > 0 ? $server_item_array[$val][0] : null);
								}

								//File Hash
								$server_hash_array = array();
								
								foreach($data as $val){
									$server_hash_array[$val] = (isset($server_item_content_array[$val]) ? $server_item_content_array[$val]['hash'] : ' ');
								}
								
								//Server file, uat dev prod qa local
								$server_file_array = array();
								
								foreach($data as $val){
									$server_file_array[$val] = (isset($server_item_content_array[$val]) ? $server_item_content_array[$val]['clean_path'] : ' ');
								}
								
								$warning_class = '';
								
								//Check hash 
								foreach($server_hash_array as $index => $val){
									
									//Get the first hash
									foreach($server_hash_array as $index2 => $val2){
										
										// Compare the first hash to all hashes
										// If not equal break the loop
										if($val !== $val2){
											$warning_class = 'danger';
											break;
										}
									}
									
									if( ! empty($warning_class)){
										break;
									}
								}
								$export_res = TRUE;
								$mode_val = NULL;
								
								if ($mode==1) {
									if ( ! empty($warning_class)) {
										$mode_val = 1;
									}
								} else {
									if ( ! empty($warning_class)) {
										$mode_val = 1;
									} else {
										$mode_val = 0;
									}
								}
								
								if($mode_val !== NULL){
									
									$fput_csv_arg_array = array(
										$sev_key, 
										$key, 
										$item_content['clean_path'], 
									);
									
									foreach($data as $val){
										$fput_csv_arg_array[] = $server_hash_array[$val];
									}
									
									$fput_csv_arg_array[] = $mode_val;
									fputcsv($handle, $fput_csv_arg_array);
								}
							}
							
						}
						
					}
				}
			}
		}
		
		fclose($handle);
		exit;
	}
}
?>