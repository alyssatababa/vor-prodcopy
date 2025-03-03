<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//use Tracy\Debugger;
//ini_set('max_execution_time', 600);
class Reports extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	// public function index(){ $this->load->view('businessdoc/reports_view'); }

	/**
	 * Purchase order page
	 * @return view Template of PO view
	 */
	public function purchase_order(){
		$data['header'] = 'headers/po_head_view';
		$data['columns'] = ['PO_NUMBER'=>'PO Number','PO_STATUS'=>'PO Status','ENTRY_DATE'=>'Entry Date','EXPECTED_RECEIPT_DATE'=>'Expected Receipt Date','CANCEL_DATE'=>'Cancel Date','TOTAL_AMOUNT'=>'Total Amount','DEPARTMENT_NAME'=>'Department Name','LOCATION'=>'Location','COMPANY_NAME'=>'Company Name','POST_DATE'=>'Post Date','READ_STATUS'=>'Status'];
		$data['years'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_POHEAD_VIEW','criteria'=>'YEAR','column'=>'POST_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['months'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_POHEAD_VIEW','criteria'=>'MONTH','column'=>'POST_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['type'] = ($this->check_data('po') === FALSE) ? 97 :(($this->check_permission()) ? 1 : 98);	
		$data['title'] = "Purchase Order";
		$data['vendor_code'] =  ($this->get_vend_code()) ? $this->get_vend_code() : '';
		$this->log_action(5);
		$this->reports_view($data);
	}

	/** PO Datatables */
	public function po_dtables(){
		$dtables['dt_post'] = $this->datatable_posts();	
		$result = $this->rest_app->post('index.php/business_doc/po_list/', $dtables, '');
		echo $result;
	}

	/** PO Archive Datatables */
	public function po_arc_dtables(){
		$dtables['dt_post'] = $this->datatable_posts();
		$dtables['archive'] = true;
		$result = $this->rest_app->post('index.php/business_doc/po_list/', $dtables, '');
		echo $result;
	}

  	/**
	 * Purchase order document details
	 * @param  integer $id 	PO_NUMBER of document
	 * @return view     	Details view of document
	 */
	public function po_details($id,$comp_id){
		$data['id'] = $id;
		$data['comp_id'] = $comp_id;
		$data['doctype'] = 1; //Purchase Order doctype
		$data['docname'] = 'Purchase Order';
		//Document history 
		$data['history'] =  $this->rest_app->post('index.php/business_doc/view_history/', ['doctype'=>1,'vendor_id'=>$this->session->userdata('vendor_id'),'id'=>$id], '');
		$data['result'] = $this->rest_app->get('index.php/business_doc/po_details/'.$id.'/'.$comp_id.'/'.$this->session->userdata('position_id').'/'.$this->session->userdata('vendor_id'), '', 'application/json');
		if(isset($data['result']->data)){
			$data['result'] = '';
		}
		$data['report_template'] = 'po_details_view';
		$this->log_action(34);
		$this->reports_details_view($data);
	}

	///////////////////
	// Credit Advice //
	///////////////////
	
	/**
	 * Credit advice page
	 * @return view Template of PO view
	 */
	public function credit_advice(){
		//Use for table header
		$data['columns'] = ['CM_DATE'=>'Crediting Date','CHECK_NO'=>'Payment Voucher Number','AMOUNT'=>'Amount','DATE_CREATED'=>'Post Date','STATUS'=>'Status'];
		//Template of filter
		$data['header'] = 'headers/ca_head_view';
		//Checking of data if no data in live or archive db return 97 (No documents available)
		$data['type'] = ($this->check_data('ca') === FALSE) ? 97 :(($this->check_permission()) ? 2 : 98); 
		//Available years in db
		$data['years'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_BDOCA','criteria'=>'YEAR','column'=>'CM_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['months'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_BDOCA','criteria'=>'MONTH','column'=>'CM_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['vendor_code'] =  ($this->get_vend_code()) ? $this->get_vend_code() : '';
		$data['title'] = "Credit Advice";
		$this->log_action(4);
		$this->reports_view($data);
	}

	/**
	 * Credit advice live data for datatable
	 * @return json Result of query
	 */
	public function ca_dtables(){
		$dtables['header'] 	= ['CM_DATE','DATE_CREATED','AMOUNT','CHECK_NO'];
		$dtables['dt_post'] = $this->datatable_posts();
		$result = $this->rest_app->post('index.php/business_doc/ca_list/', $dtables, '');
		echo $result;
	}

	/** CA Archive Datatables */
	public function ca_arc_dtables(){
		$dtables['dt_post'] = $this->datatable_posts();
		$dtables['archive'] = true;
		$result = $this->rest_app->post('index.php/business_doc/ca_list/', $dtables, '');
		echo $result;
	}

	/**
	 * Credit Advice document details
	 * @param  integer $id 	CHECK_NO of document
	 * @return view     	Details view of document
	 */
	public function ca_details($id){
		$data['id'] = $id;
		$data['doctype'] = 2; //Credit Advice doctype
		$data['docname'] = 'BDOCA';
		//Document history 
		$data['history'] =  $this->rest_app->post('index.php/business_doc/view_history/', ['doctype'=>2,'vendor_id'=>$this->session->userdata('vendor_id'),'id'=>$id], '');
		$data['result'] = $this->rest_app->get('index.php/business_doc/ca_details/'.$id.'/'.$this->session->userdata('position_id'), '', 'application/json');
		$data['report_template'] = 'ca_details_view';
		$this->log_action(32);
		$this->reports_details_view($data);
	}
	
	///////////////////
	// DMCM Document //
	///////////////////
	
	/**
	 * Debit/Credit Memo Listing
	 * 
	 */
	public function debit_credit_memo(){
		$data['columns'] = ['DOC_TYPE_ID'=>'Doctype','DOC_NO'=>'Document Number','COMP_NAME'=>'Company Name','STORE_NAME'=>'Store Name','PARTICULARS'=>'Particulars', 'AMOUNT'=>'Amount','APPLIED'=>'Applied','CHECK_NO'=>'Payment Voucher No.','DATE_CREATED'=> 'Post Date'];
		$data['header'] = 'headers/dmcm_head_view';
		$data['title'] = "Debit Memo / Credit Memo";
		$data['type'] = ($this->check_data('dmcm') === FALSE) ? 97 :(($this->check_permission()) ? 3 : 98);
		$data['years'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_DMCM_VIEWS','criteria'=>'YEAR','column'=>'PROC_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['months'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_DMCM_VIEWS','criteria'=>'MONTH','column'=>'PROC_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['vendor_code'] =  ($this->get_vend_code()) ? $this->get_vend_code() : '';
		$this->log_action(6);
		$this->reports_view($data);
	}

	/** DMCM Datatables */
	public function dmcm_dtables(){
		$dtables['dt_post'] = $this->datatable_posts();
		$result = $this->rest_app->post('index.php/business_doc/dmcm_list/', $dtables, '');
		echo $result;
	}

	////////////////////////////////
	// Remittance Advice Document //
	////////////////////////////////

	/** Remittance advice page */
	public function remittance_advice(){
		$data['columns'] = ['REF_NO'=> '','REF_NO'=>'Remittance Advice Number','PROC_DATE'=>'Processing Date','TOTAL_AMOUNT'=>'Total Amount','CHECK_DATE'=>'Payment Date','PAY_MODE_ID'=>'Payment Type','DATE_CREATED'=>'Post Date','STATUS'=>'Status'];
		$data['header'] = 'headers/ra_head_view';
		$data['type'] = ($this->check_data('ra') === FALSE) ? 97 :(($this->check_permission()) ? 5 : 98);
		$data['years'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_RA_HEAD_VIEW','criteria'=>'YEAR','column'=>'PROCESSING_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['months'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_RA_HEAD_VIEW','criteria'=>'MONTH','column'=>'PROCESSING_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['vendor_code'] =  ($this->get_vend_code()) ? $this->get_vend_code() : '';
		$data['title'] = "Remittance Advice";
		$this->log_action(7);
		$this->reports_view($data);
	}

	/** RA Datatables */
	public function ra_dtables(){
		$dtables['table'] 	= 'SMNTP_BD_RA';
		$dtables['dt_post'] = $this->datatable_posts();
		$result = $this->rest_app->post('index.php/business_doc/ra_list/', $dtables, '');
		echo $result;
	}

	/** RA Archive Datatables */
	public function ra_arc_dtables(){
		$dtables['dt_post'] = $this->datatable_posts();
		$dtables['archive'] = true;
		$result = $this->rest_app->post('index.php/business_doc/ra_list/', $dtables, '');
		echo $result;
	}

	/**RA Details **/
	public function ra_details($id){
		$data['id'] = $id;
		$data['doctype'] = 5; //Remittance Advice doctype
		$data['docname'] = 'Remittance Advice';
		$result = $this->rest_app->get('index.php/business_doc/ra_details/'.$id.'/'.$this->session->userdata('position_id'), '', 'application/json');
		$data['result'] = ($result) ? $result : '';
		//Document history 
		$data['history'] =  $this->rest_app->post('index.php/business_doc/view_history/', ['doctype'=>5,'vendor_id'=>$this->session->userdata('vendor_id'),'id'=>$id], '');
		$data['report_template'] = 'ra_details_view';
		$this->log_action(33);
		$this->reports_details_view($data);
	}

	/**
	 * SCPA page
	 * @return view Template of PO view
	 */
	public function scpa(){
		$data['header'] = 'headers/po_head_view';
		$data['columns'] = ['PO_NUMBER'=>'PO Number','PO_STATUS'=>'PO Status','ENTRY_DATE'=>'Entry Date','EXPECTED_RECEIPT_DATE'=>'Expected Receipt Date','CANCEL_DATE'=>'Cancel Date','TOTAL_AMOUNT'=>'Total Amount','DEPARTMENT_NAME'=>'Department Name','LOCATION'=>'Location','COMPANY_NAME'=>'Company Name','POST_DATE'=>'Post Date','READ_STATUS'=>'Status'];
		$data['years'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_POHEAD_VIEW','criteria'=>'YEAR','column'=>'POST_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['months'] = $this->rest_app->post('index.php/business_doc/avail_dates/', ['table'=>'SMNTP_BD_POHEAD_VIEW','criteria'=>'MONTH','column'=>'POST_DATE','vendor_id'=>$this->session->userdata('vendor_id')], '');
		$data['type'] = ($this->check_data('po') === FALSE) ? 97 :(($this->check_permission()) ? 1 : 98);	
		$data['title'] = "SCPA";
		$data['vendor_code'] =  ($this->get_vend_code()) ? $this->get_vend_code() : '';
		$this->log_action(5);
		$this->reports_view($data);
	}

	////////////////////
	//Other documents //
	////////////////////
	public function return_vendor(){
		$data['header'] = 'headers/po_head_view';
		$data['type'] = 99 ;
		$data['title'] = "Return to Vendor";
		$this->reports_view($data);
	}


	public function receive_confirmation_report(){
		$data['header'] = 'headers/po_head_view';
		$data['type'] = 99 ;
		$data['title'] = "Receiving Confirmation Report";
		$this->reports_view($data);
	}

	/**
	 * Function downloading file
	 * @return [json] [Status and path]
	 */
	public function dl_file(){
		$data = ['type'=> $this->input->post('dl'),'option'=> $this->input->post('dl-option'),'selected'=>$this->input->post('selected'),'vendor_id'=>$this->session->userdata('vendor_id'),'id_doc'=>$this->input->post('id_doc'),'user_id'=>$this->session->userdata('user_id')];
		$exploded_dl = explode('-', $this->input->post('dl'));
		if($this->input->post('selected')){ //Batch
			switch ($exploded_dl[0]) {
				case 'pdf':
					switch ($exploded_dl[2]) {
						case 1:	
							$this->log_action(44);
							break;
						case 2:
							$this->log_action(11);
							break;
						case 5:
							$this->log_action(25);
							break;
					}
					break;
				case 'csv':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(45);
							break;
						case 2:
							$this->log_action(12);
							break;
						case 5:
							$this->log_action(24);
							break;
					}
					break;	
				case 'xml':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(43);
							break;
						case 2:
							$this->log_action(13);
							break;
						case 5:
							$this->log_action(26);
							break;
					}
					break;
			}
		} else if($this->input->post('id_doc')){ //Detailed
			switch ($exploded_dl[0]) {
				case 'pdf':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(48);
							break;
						case 2:
							$this->log_action(17);
							break;
						case 5:
							$this->log_action(29);
							break;
					}
					break;
				case 'csv':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(47);
							break;
						case 2:
							$this->log_action(16);
							break;
						case 5:
							$this->log_action(28);
							break;
					}
					break;	
				case 'xml':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(46);
							break;
						case 2:
							$this->log_action(38);
							break;
						case 5:
							$this->log_action(30);
							break;
					}
					break;
			}

		} else{ //Summaries
			switch ($exploded_dl[0]) {
				case 'pdf':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(52);
							break;
						case 3:
							$this->log_action(18);
							break;	
						case 2:
							$this->log_action(9);
							break;
						case 5:
							$this->log_action(22);
							break;
					}
					break;
				case 'csv':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(51);
							break;
						case 3:
							$this->log_action(20);
							break;
						case 2:
							$this->log_action(10);
							break;
						case 5:
							$this->log_action(23);
							break;
					}
					break;	
				case 'xls':
					switch ($exploded_dl[2]) {
						case 1:
							$this->log_action(50);
							break;
						case 2:
							$this->log_action(8);
							break;
						case 3:
							$this->log_action(19);
							break;
						case 5:
							$this->log_action(21);
							break;
					}
					break;
			}

		}
		$result = $this->rest_etl->post('index.php/business_doc/dl_file/', $data, '');
		echo json_encode($result,JSON_PRETTY_PRINT);

	}
	/**
	 * Function downloading file
	 * @return [json] [Status and path]
	 */
	public function file_header(){
		$this->load->helper('file');
		
		$filename = substr($this->input->post('path'), strrpos($this->input->post('path'), '/') + 1);

		header('Set-Cookie: fileDownload=true; path=/');
		header('Cache-Control: max-age=60, must-revalidate');
		header('Content-type: '.get_mime_by_extension($filename).'');
		header('Content-Disposition: attachment; filename="'.$filename.'"');

		echo file_get_contents($this->input->post('path'));	
		exit;
	}
	/**
	 * File checker
	 * @return json Result of checking file
	 */
	public function file_checker(){
		$result = $this->rest_etl->get('index.php/business_doc/check_file/'.$this->session->userdata('user_id').'/'.$this->session->userdata('vendor_id'), '', '');
		echo json_encode($result,JSON_PRETTY_PRINT);

	}
	/**
	 * Archive or Unarchive option
	 * @return json True
	 */
	public function arc_option(){
		switch ($this->input->post('doctype')) {
			case 1:
				$this->log_action(37);
				break;
			case 2:
				$this->log_action(35);
				break;
			case 5:
				$this->log_action(36);
				break;
		}
		$data = ['selected'=>$this->input->post('selected'),'option'=>$this->input->post('option'),'doctype'=>$this->input->post('doctype')];
		$result = $this->rest_app->post('index.php/business_doc/delete_doc/', $data, '');
		echo json_encode($result,JSON_PRETTY_PRINT);
	}

	public function print_template($doctype = null,$id = null,$comp_id = null){
		$vendor_id = $this->session->userdata('vendor_id');
		if($this->input->post('selected')){
			switch ($this->input->post('doctype')) {
				case 1:
					$data['result'] = $this->rest_app->post('index.php/business_doc/po_details/', ['selected'=>$this->input->post('selected'),'vendor_id'=>$vendor_id,'companies'=>$this->input->post('comp')], '');
					$this->log_action(53);
					$data['report_template'] = 'po_details_view';
					break;
				case 2:
					$data['result'] = $this->rest_app->post('index.php/business_doc/ca_details/', ['selected'=>$this->input->post('selected'),'vendor_id'=>$vendor_id], '');
					$this->log_action(14);
					$data['report_template'] = 'ca_details_view';
					break;
				case 5:	
					$data['result'] = $this->rest_app->post('index.php/business_doc/ra_details/', ['selected'=>$this->input->post('selected'),'vendor_id'=>$vendor_id], '');
					$this->log_action(27);
					$data['report_template'] = 'ra_details_view';
					break;
				default:
					break;
			}

		} else{
			switch ($doctype) {
				case 1:
					$data['result'] = $this->rest_app->get('index.php/business_doc/po_details/'.$id.'/'.$comp_id.'/'.$this->session->userdata('position_id').'/'.$vendor_id, '', 'application/json');
					$this->log_action(54);
					$data['report_template'] = 'po_details_view';
					break;
				case 2:
					$data['result'] = $this->rest_app->get('index.php/business_doc/ca_details/'.$id.'/'.$this->session->userdata('position_id'), '', 'application/json');
					$this->log_action(15);
					$data['report_template'] = 'ca_details_view';
					break;
				case 5:
					$data['result'] = $this->rest_app->get('index.php/business_doc/ra_details/'.$id.'/'.$this->session->userdata('position_id'), '', 'application/json');
					$this->log_action(31);
					$data['report_template'] = 'ra_details_view';
					break;
				default:
					break;
			}
		}

		
		$data['id'] = ($this->input->post('id')) ? $this->input->post('id') : $id;
		$this->load->view('businessdoc/print_template',$data);
	}


	/**
	 * Company filter for po and dmcm
	 * @return json List of companies
	 */
	public function scompany_name(){
		if($this->input->get('doc') == 1){
			$data = $this->rest_app->get('index.php/business_doc/po_comp_list?q='.urlencode($this->input->get('q')).'&option='.$this->input->get('option').'&vendor_id='.$this->session->userdata('vendor_id'),'','application/json');
		} else {
			$data = $this->rest_app->get('index.php/business_doc/dmcm_comp_list?q='.urlencode($this->input->get('q')).'&option='.$this->input->get('option').'&vendor_id='.$this->session->userdata('vendor_id'),'','application/json');
		}
		
		echo json_encode($data,JSON_PRETTY_PRINT);
	}

	/**
	 * Store name filter for dmcm
	 * @return json List of stores
	 */
	public function dmcm_stName(){
		$data = $this->rest_app->get('index.php/business_doc/dmcm_stName_list?q='.urlencode($this->input->get('q')).'&vendor_id='.$this->session->userdata('vendor_id'),'','application/json');
		echo json_encode($data,JSON_PRETTY_PRINT);
	}

	/**
	 * Store name filter for dmcm
	 * @return json List of stores
	 */
	public function po_location(){
		$data = $this->rest_app->get('index.php/business_doc/po_loc_list?q='.urlencode($this->input->get('q')).'&option='.$this->input->get('option').'&vendor_id='.$this->session->userdata('vendor_id'),'','application/json');
		echo json_encode($data,JSON_PRETTY_PRINT);
	}

	/**
	 * Store name filter for dmcm
	 * @return json List of stores
	 */
	public function po_deptname(){
		$data = $this->rest_app->get('index.php/business_doc/po_dept_list?q='.urlencode($this->input->get('q')).'&option='.$this->input->get('option').'&vendor_id='.$this->session->userdata('vendor_id'),'','application/json');
		echo json_encode($data,JSON_PRETTY_PRINT);
	}



	public function vend_code(){
		$data = $this->rest_app->get('index.php/business_doc/vendor_list?q='.$this->input->get('q'),'','application/json');
		echo json_encode($data,JSON_PRETTY_PRINT);
	}

	public function email_pdf($id,$doctype){
		$data = ['type'=> 'pdf-details-'.$doctype,'id_doc'=>$id];
		$vendor_id = $this->session->userdata('vendor_id');
		if($vendor_id){
			$data['vendor_id'] = $vendor_id;
		}
		$pdf = $this->rest_etl->post('index.php/business_doc/dl_file/', $data, '');
		$post_data['pdf_path'] = $pdf->path;
		$post_data['user_email'] = $this->session->userdata('user_email');
		$result = $this->rest_app->post('index.php/business_doc/send_email/',$post_data,'');
		echo json_encode($data,JSON_PRETTY_PRINT);
	}

	//////////////////////
	// For Internal use //
	//////////////////////

	/**
	 * Load reports template
	 * @param  array $data Data for viewing
	 */
	private function reports_view($data){ $this->load->view('businessdoc/reports_view',$data); }

	/**
	 * Load reports details template
	 * @param  array $data Data for viewing
	 */
	private function reports_details_view($data){ 
		$this->session->set_userdata('bd_backses',@$data['result']->BACKSES);
		$this->load->view('businessdoc/reports_details_view',$data); 
	}

	/**
	 * Datatable posts and vendor session
	 * @return array Sets of datatable data
	 */
	private function datatable_posts(){
		// var_dump($this->input->post('cName'));
		// exit;
		$dtables['draw'] 	= $this->input->post('draw');
		$dtables['columns'] = $this->input->post('columns');
		$dtables['order'] 	= $this->input->post('order');
		$dtables['start'] 	= $this->input->post('start');
		$dtables['length'] 	= $this->input->post('length');
		$dtables['search'] 	= $this->input->post('search');
		$dtables['vendor_id'] = $this->session->userdata('vendor_id');
		$dtables['position_id'] = $this->session->userdata('position_id');
		$dtables['date'] = $this->input->post('date');
   		$dtables['vend_id'] = $this->input->post('vend_id');
   		$dtables['company_name'] = $this->input->post('company_name');
   		$dtables['store_name'] = $this->input->post('store_name');
   		$dtables['location'] = $this->input->post('location');
   		$dtables['dept_name'] = $this->input->post('dept_name');

		return $dtables;
	}

	/**
	 * Check for permission of page by vendor
	 * @return boolean Return true or false
	 */
	private function check_permission(){
		$vendor_code = $this->get_vend_code();
		//Show data base on position and vendor code. If position is admin return true.
		if($vendor_code || in_array($this->session->userdata('position_id'),[1]) ){
			return true;
		}
		return false;
	}

	/**
	 * Check data in live or archive
	 * @param  string $category Document type
	 * @return mixed           Return true or result
	 */
	private function check_data($category = 'ca'){
		$data['vendor_id'] = $this->session->userdata('vendor_id');
		$data['position_id'] = $this->session->userdata('position_id');
		$data['category'] = $category;
		$result = $this->rest_app->post('index.php/business_doc/check_data/',$data,'');
		//Show all data for admin user
		if($this->session->userdata('position_id') == 1){
			return true;
		}
		return $result;
	}

	/**
	 * Get vendor code by vendor id session
	 * @return [type] [description]
	 */
	private function get_vend_code(){
		return $this->rest_app->get('index.php/business_doc/vendor_code/'.$this->session->userdata('vendor_id'));
	}
	

	private function log_action($action){
		if($this->session->userdata('position_id') == 10){
			$data['user_id'] = $this->session->userdata('user_id');
			$data['action_id']  = $action;
			$result = $this->rest_app->post('index.php/business_doc/log_action/',$data,'');
			return true;
		} else {
			return false;
		}
		
	}
}
