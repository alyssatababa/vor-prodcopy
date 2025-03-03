<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contracts_and_agreements extends CI_Controller {

	/*
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}
	*/

	public function index()
	{
		$this->load->view('vendorparam/contracts_and_agreements');
		//$this->load_ca();
	}
	
	
	public function add_ca(){
		$data['ca_name'] = $this->input->post('ca_name');
		$data['description'] = $this->input->post('description');
		$data['bus_division'] = $this->input->post('bus_division');
		$data['downloadable'] = $this->input->post('downloadable');
		$data['viewable'] = $this->input->post('viewable');
		$data['tool_tip'] = $this->input->post('tool_tip');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/add_ca', $data, '');
		//var_dump($data['result_data']);
		echo $data['result_data'];
	}
	
	public function load_ca(){
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/view_all_ca', '', '');
		//var_dump($data['result_data']);
		//echo json_encode($data['result_data']);
		$this->load->view('vendorparam/contracts_and_agreements' , $data);
	}
	
	public function get_ca(){
		$data['input_ca'] = $this->input->post('ca');
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/view_ca', $data, '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/contracts_and_agreements' , $data);
	}
	
	public function get_all_ca(){
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/view_all_ca', '', '');
		//var_dump(json_encode($data['result_data']));
		echo json_encode($data['result_data']);
		//$this->load->view('vendorparam/contracts_and_agreements' , $data);
	}
	
	public function save_ca(){
		$data['ca_id'] = $this->input->post('ca_id');
		$data['ca_name'] = $this->input->post('ca_name');
		$data['description'] = $this->input->post('description');
		$data['bus_division'] = $this->input->post('bus_division');
		$data['downloadable'] = $this->input->post('downloadable');
		$data['viewable'] = $this->input->post('viewable');
		$data['tool_tip'] = $this->input->post('tool_tip');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/save_ca', $data, '');
		//$this->rest_app->debug();
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}

	public function deactivate_ca(){
		$data['ca_id'] = $this->input->post('ca_id');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/deactivate_ca', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	
	

	public function save_sample_file(){
		$data['ca_id'] = $this->input->post('ca_id');
		$data['sample_file'] = $this->input->post('sample_file');
		
		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/save_sample_file', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo $data['result_data'];
	}	

	public function get_sample_file(){
		$data['ca_id'] = $this->input->post('ca_id');

		$data['result_data'] = $this->rest_app->post('index.php/vendorparam/contracts_and_agreements/get_sample_file', $data, '');
		//var_dump($data['result_data']);
		//echo "1";
		echo json_encode($data['result_data']);
	}	

	public function upload_file($type) //$type = 1 Documents , 2 Agreements
	{
		$file_path  = '';
		$error 		= '';
		$orig_name	= '';

		if (isset($_FILES['fileupload']['name']))
		{
			$orig_name = $_FILES['fileupload']['name'];

			if ($type == 1) // documents
				$upload_type = 'documents';
			elseif ($type == 2) // 2 = agreements
				$upload_type = 'agreements';

			// if (base_url() == 'http://piccolo/smntp/web/')
			// 	$web_upload_path = 'F:\\\\inetpub\\smscoreonline\\SMNTP\\web\\'.'vendor_upload_'.$upload_type.'\\';
			// elseif(base_url() == 'http://yogi:8080/SMNTP/web/')
			// 	$web_upload_path = 'D:\\\\inetpub\\smscoreonline\\SMNTP\\web\\'.'vendor_upload_'.$upload_type.'\\';
			// else
			// 	$web_upload_path = '/data/lampstack-5.4.14-0/apache2/htdocs/'.'vendor_upload_'.$upload_type.'/';

			$web_upload_path = FCPATH.'vendor_upload_'.$upload_type.'/preview_documents';

			if(!is_dir($web_upload_path))
		    {
		    	mkdir($web_upload_path, 0777);
		    }

		    $config['upload_path'] = $web_upload_path;
            $config['allowed_types'] = 'png|jpg|jpeg|pdf|PNG|JPG|JPEG|PDF|octet-stream';
            $config['max_size'] = '10000';
            $config['file_name'] = 'upload_'.$upload_type.'_'.time();

            $this->load->library('upload', $config, 'fileupload');
    		$this->fileupload->initialize($config);

            if ( !$this->fileupload->do_upload('fileupload', FALSE))
            {
                $error = $this->fileupload->display_errors();
            }
            else
            {
                $error = '';
                $data = $this->fileupload->data();

	            // $file_name = $config['file_name'].$data['file_ext'];
	            $file_path = 'vendor_upload_'.$upload_type.'/preview_documents/'.$data['file_name'];
			}
		}
		$date_upload = date('m/d/Y h:i:s A');

		//print_r($_FILES);


		echo '<input type="hidden" id="file_path" name="file_path" value="'.$file_path.'">';
		echo '<input type="hidden" id="error" name="error" value="'.$error.'">';
		echo '<input type="hidden" id="upload_date" name="upload_date" value="'.$date_upload.'">';
		echo '<input type="hidden" id="orig_name" name="orig_name" value="'.$orig_name.'">';

	}	
}
