<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Required_documents extends REST_Controller {
		
		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('vendorparam/required_documents_model');
		}
		

		
		// Server's Get Method
		public function add_reqdocs_post(){
			$reqdocs_name = $this->post('reqdocs_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');
			$created_by = $this->post('created_by');
			$tool_tip = $this->post('tool_tip');

			$data = $this->required_documents_model->insert_reqdocs($reqdocs_name, $description, $bus_division, $created_by, $tool_tip);
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
		
		public function save_reqdocs_post(){
			$reqdocs_id = $this->post('reqdocs_id');
			$reqdocs_name = $this->post('reqdocs_name');
			$description = $this->post('description');
			$bus_division = $this->post('bus_division');
			$tool_tip = $this->post('tool_tip');

			$data = $this->required_documents_model->update_reqdocs($reqdocs_id, $reqdocs_name, $description, $bus_division, $tool_tip);
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
		
		public function deactivate_reqdocs_post(){
			$reqdocs_id = $this->post('reqdocs_id');

			$data = $this->required_documents_model->deactivate_reqdocs($reqdocs_id);
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
		
		
		public function view_all_reqdocs_post(){
			$data = $this->required_documents_model->select_all_reqdocs();
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
		
		public function view_reqdocs_post(){
			$reqdocs = $this->post('input_reqdocs');
			$data = $this->required_documents_model->select_reqdocs($reqdocs);
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
			$reqdocs_id = $this->post('reqdocs_id');
			$sample_file = $this->post('sample_file');

			$data = $this->required_documents_model->save_sample_file($reqdocs_id, $sample_file);
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
			$reqdocs_id = $this->post('reqdocs_id');

			$data = $this->required_documents_model->get_sample_file($reqdocs_id);
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
			// 						'DESCRIPTION' 				=> $rsd_document_chk,
			// 						'FILE_PATH' 				=> $btn_rsd_preview,
			// 						'DATE_CREATED' 				=> $rsd_date_upload, //'TO_DATE(\''.$rsd_date_upload.'\', \'MM/DD/YYYY HH12:MI:SS AM\')',
			// 						'CREATED_BY' 				=> $rsd_orig_name,
			// 						'ACTIVE'					=> "1",
			// 						'SAMPLE_FILE'				=> $btn_rsd_preview,
			// 						'BUS_DIVISION'				=> $bus_division
			// 					);	
			// 	}
				
			// }

			// if (!empty($rsd_batch))
			// 	$this->common_model->insert_table_batch('SMNTP_VP_REQUIRED_DOCUMENTS', $rsd_batch);

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