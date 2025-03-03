<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Rfqrfb_main extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfq_model');
		}

		// Server's Get Method
		public function currency_get(){
			$data = $this->rfq_model->get_currency();
			$this->response($data);
		}

		public function requestor_get(){
			$data = $this->rfq_model->get_requestor();
			$this->response($data);
		}

		public function vendornames_get(){
			$data = $this->rfq_model->get_vendornames();
			$this->response($data);
		}

		public function vendorcategory_get(){
			$data = $this->rfq_model->get_vendorcategory();
			$this->response($data);
		}

		public function purpose_get(){
			$data = $this->rfq_model->get_purpose();
			$this->response($data);
		}

		public function reason_get(){
			$data = $this->rfq_model->get_reason();
			$this->response($data);
		}

		public function submit_rfq_creation_post(){
			//header
			$data['userdata'] 			= $this->post('userdata');
			$data['title_txt'] 			= $this->post('title_txt');
			$data['type_radio'] 		= $this->post('type_radio');
			//currency
			$data['currency'] 			= $this->post('currency');
			$data['pref_delivery_date'] = $this->post('pref_delivery_date');
			$data['sub_deadline_date'] 	= $this->post('sub_deadline_date');

			//smview only
			$data['requestor'] 			= $this->post('requestor');
			$data['purpose'] 			= $this->post('purpose');
			$data['purpose_txt'] 		= $this->post('purpose_txt');
			$data['reason'] 			= $this->post('reason');
			$data['reason_txt']			= $this->post('reason_txt');

			//lines
			$data['total_lines']		= $this->post('total_lines');

			for($i = 1; $i <= $data['total_lines']; $i++)
			{
				$data['line_category'.$i] 	= $this->post('line_category'.$i);
				$data['line_description'.$i]	= $this->post('line_description'.$i);
				$data['line_measuring_unit'.$i]		= $this->post('line_measuring_unit'.$i);
				$data['quantity'.$i]	= $this->post('quantity'.$i);

				$data['specs'.$i.'_text']	= $this->input->post('specs'.$i.'_text');
			}

			$data_new = $this->rfq_model->submit_rfq_creation($data);
			
			$this->response($data_new);
		}

		function find_similar_get()
		{
			$email_address = $this->get('txt_email');

			$data = $this->rfq_model->find_similar($email_address);

			$this->response($data);
		}

		function add_new_invite_post()
		{
			$data['vendorname'] 		= $this->post('vendorname');
			$data['vendorcontact'] 		= $this->post('vendorcontact');
			$data['email'] 				= $this->post('email');

			$data 						= $this->rfq_model->add_new_invite($data);

			$this->response($data);
		}

		function view_vendor_get()
		{
			$data['search_no'] 					= $this->get('search_no');
			$data['title_search'] 				= $this->get('title_search');
			$data['date_created'] 				= $this->get('date_created');
			$data['status'] 					= $this->get('status');
			$data['time_left'] 					= $this->get('time_left');

			$new_data							= $this->rfq_model->get_vendor($data);

			$this->response($new_data);

		}

		function search_invite_get()
		{
			$data['cbo_vendorname']  		= $this->get('cbo_vendorname');
			$data['cbo_vendorlist']  		= $this->get('cbo_vendorlist');
			$data['cbo_vendorcategory']  	= $this->get('cbo_vendorcategory');
			$data['cbo_vendorbrand']  		= $this->get('cbo_vendorbrand');
			$data['cbo_vendorlocation']  	= $this->get('cbo_vendorlocation');
			$data['cbo_vendorrfq']  		= $this->get('cbo_vendorrfq');
			

			$new_data = $this->rfq_model->search_invite($data);

			$this->response($new_data);
		}
		
		function rfqrfbtable_get()
	{
		$var['user_id'] 	= $this->get('user_id');
		$var['position_id'] = $this->get('position_id');
		$var['buyer_id'] 	= $this->get('buyer_id');
		$var['date_created'] 	= $this->get('date_created');
		$var['requestor_id'] 	= $this->get('requestor_id');
		$var['timeleft_filter'] 	= $this->get('timeleft_filter');
		$var['purpose_id'] 	= $this->get('purpose_id');
		$var['status_id'] 	= $this->get('status_id');
		$var['search_no'] 	= $this->get('search_no');
		$var['search_title'] 	= $this->get('search_title');
		$var['rpp']			= 25;
		$var['page_no']		 = $this->get('page_no');
		$var['sort']		 = $this->get('sort');
		$var['sort_type']		 = $this->get('sort_type');
		// $var['page_num']	= $this->get('current_page');

		$rs = $this->rfq_model->get_rfqrfb_main($var);
		// echo $this->db->last_query();
		 $data = $rs;
		 $this->response($data);
		
	 }
	 
	 function rfqrfbtable_vendor_get()
	{
		$var['user_id'] 	= $this->get('user_id');
		$var['vendor_id'] 	= $this->get('vendor_id');
		$var['vendor_invite_id'] 	= $this->get('vendor_invite_id');
		$var['position_id'] = $this->get('position_id');
		$var['buyer_id'] 	= $this->get('buyer_id');
		$var['date_created'] 	= $this->get('date_created');
		$var['requestor_id'] 	= $this->get('requestor_id');
		$var['timeleft_filter'] 	= $this->get('timeleft_filter');
		$var['purpose_id'] 	= $this->get('purpose_id');
		$var['status_id'] 	= $this->get('status_id');
		$var['search_no'] 	= $this->get('search_no');
		$var['search_title'] 	= $this->get('search_title');
		$var['rpp']			= 25;
		// $var['page_num']	= $this->get('current_page');

		$rs = $this->rfq_model->get_rfqrfb_main_vendor($var);
		//echo $this->db->last_query();
		 $data = $rs;
		 $this->response($data);
		
	 }
	 
	 function filter_status_get()
	{
		$var['status_type'] = $this->get('status_type');
		
		$rs = $this->rfq_model->get_status_filter($var);
		$data = $rs;
		
		$this->response($data);
		
	}
	
	function filter_buyer_get()
	{
		$var['buyer_position_id'] = $this->get('buyer_position_id');
		
		$rs = $this->rfq_model->get_buyer_filter($var);
		$data = $rs;
		
		$this->response($data);
		
	}
	
	function filter_requestor_get()
	{
		$var['buyer_position_id'] = $this->get('buyer_position_id');
		
		$rs = $this->rfq_model->get_requestor_filter($var);
		$data = $rs;
		
		$this->response($data);
		
	}
	
	function filter_purpose_get()
	{
		$var['buyer_position_id'] = $this->get('buyer_position_id');
		
		$rs = $this->rfq_model->get_purpose_filter($var);
		$data = $rs;
		
		$this->response($data);
		
	}


	
	

	}

?>
