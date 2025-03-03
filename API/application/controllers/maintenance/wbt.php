<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Wbt extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('maintenance/wbt_model');
		}
		

		
		// Server's Get Method
		public function add_wbt_post(){
			$screen_name = $this->post('screen_name');
			$menu_label = $this->post('menu_label');
			$link = $this->encode_url($this->post('link'));
			$created_by = $this->post('created_by');

			$data = $this->wbt_model->insert_wbt($screen_name, $menu_label, $link, $created_by);
			if ($data)
			{
				//Insert to SCREEN_DEFN and SCREEN_DEFN_POSITION
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
		
		public function get_all_video_url_get()
		{
			$res = $this->wbt_model->get_all_video_url();

			$this->response($res);
		}
		
		public function get_selected_video_url_get(){
			$res = $this->wbt_model->get_selected_video_url($this->get('ID'));
			
			$this->response($res);
		}
		
		public function encode_url($unencoded_url){
			return preg_replace_callback('#://([^/]+)/([^?]+)#', function ($match) {
                return '://' . $match[1] . '/' . join('/', array_map('rawurlencode', explode('/', $match[2])));
            }, $unencoded_url);
		}
		
		public function save_wbt_post(){
			$wbt_id = $this->post('wbt_id');
			$screen_name = $this->post('screen_name');
			$menu_label = $this->post('menu_label');
			$link = $this->encode_url($this->post('link'));
			
			$data = $this->wbt_model->update_wbt($wbt_id, $screen_name, $menu_label, $link);
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
		
		public function deactivate_wbt_post(){
			$wbt_id = $this->post('wbt_id');

			$data = $this->wbt_model->deactivate_wbt($wbt_id);
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
		
		
		public function view_all_wbt_post(){
			$data = $this->wbt_model->select_all_wbt();
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
		
		public function view_wbt_post(){
			$wbt = $this->post('input_wbt');
			$data = $this->wbt_model->select_wbt($wbt);
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

		public function save_sample_file_post()
		{
			$wbt_id = $this->post('wbt_id');
			$sample_file = $this->post('sample_file');

			$data = $this->wbt_model->save_sample_file($wbt_id, $sample_file);
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

		public function get_sample_file_post()
		{
			$wbt_id = $this->post('wbt_id');

			$data = $this->wbt_model->get_sample_file($wbt_id);
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


			//Required Scanned Documents
			//$rsd_upload_count	= $this->post('rsd_upload_count');
			//$rsd = $this->registration_model->get_rsd_docs();		

			// $rsd_batch = array();
			// for ($i=1; $i <= count($rsd); $i++)
			// { 
			// 	$rsd_document_chk 		= $this->post('rsd_document_chk'.$i); // get id
			// 	$rsd_date_upload 		= $this->post('rsd_date_upload'.$i);
			// 	$rsd_orig_name 			= $this->post('rsd_orig_name'.$i);
			// 	$btn_rsd_preview 		= $this->post('btn_rsd_preview'.$i); // file path

			// 	if ($rsd_date_upload != '')
			// 	{
			// 		$rsd_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $rsd_date_upload);
			// 		$rsd_date_upload = $rsd_date_upload->format("d-M-y h.i.s.u A");
			// 	}			

			// 	if (!empty($rsd_document_chk))
			// 	{
			// 		// $rsd_batch[] = array(
			// 		// 				'VENDOR_ID' 		=> $var['vendor_id'],
			// 		// 				'DOC_TYPE_ID' 		=> $rsd_document_chk,
			// 		// 				'FILE_PATH' 		=> $btn_rsd_preview,
			// 		// 				'DATE_CREATED' 		=> $rsd_date_upload, //'TO_DATE(\''.$rsd_date_upload.'\', \'MM/DD/YYYY HH12:MI:SS AM\')',
			// 		// 				'ORIGINAL_FILENAME' => $rsd_orig_name
			// 		// 			);	
			// 		$rsd_batch[] = array(
			// 						'REQUIRED_DOCUMENT_NAME' 	=> $var['vendor_id'],
			// 						'menu_label' 				=> $rsd_document_chk,
			// 						'FILE_PATH' 				=> $btn_rsd_preview,
			// 						'DATE_CREATED' 				=> $rsd_date_upload, //'TO_DATE(\''.$rsd_date_upload.'\', \'MM/DD/YYYY HH12:MI:SS AM\')',
			// 						'CREATED_BY' 				=> $rsd_orig_name,
			// 						'ACTIVE'					=> "1",
			// 						'SAMPLE_FILE'				=> $btn_rsd_preview,
			// 						'link'				=> $link
			// 					);	
			// 	}
				
			// }

			// if (!empty($rsd_batch))
			// 	$this->common_model->insert_table_batch('SMNTP_VP_wbt', $rsd_batch);

			// //Required Agreements
			// $ra_upload_count	= $this->post('ra_upload_count');
			// $ra = $this->registration_model->get_ra_docs();

			// $ra_batch = array();
			// for ($i=1; $i <= count($ra); $i++)
			// { 
			// 	$ra_document_chk 		= $this->post('ra_document_chk'.$i); // get id
			// 	$ra_date_upload 		= $this->post('ra_date_upload'.$i);
			// 	$ra_orig_name 			= $this->post('ra_orig_name'.$i);
			// 	$btn_ra_preview 		= $this->post('btn_ra_preview'.$i); // file path

			// 	if ($ra_date_upload != '')
			// 	{
			// 		$ra_date_upload = DateTime::createFromFormat('m/d/Y h:i:s A', $ra_date_upload);
			// 		$ra_date_upload = $ra_date_upload->format('d-M-y h.i.s.u A');
			// 	}

			// 	if (!empty($ra_document_chk))
			// 	{
			// 		$ra_batch[] = array(
			// 						'VENDOR_ID' 		=> $var['vendor_id'],
			// 						'DOC_TYPE_ID' 		=> $ra_document_chk,
			// 						'FILE_PATH' 		=> $btn_ra_preview,
			// 						'DATE_CREATED' 		=> $ra_date_upload,//'TO_DATE('.$ra_date_upload.', \'MM/DD/YYYY HH12:MI AM\')',
			// 						'ORIGINAL_FILENAME' => $ra_orig_name
			// 					);
			// 	}
				
			// }

			// if (!empty($ra_batch))
			// 	$this->common_model->insert_table_batch('SMNTP_VENDOR_AGREEMENTS', $ra_batch);
		//}
		
	}

?>