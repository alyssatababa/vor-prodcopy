<?Php 	defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Rfq_Api extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			parent::__construct();
			$this->load->model('rfq_model');
			$this->load->model('common_model');
		}

		// Server's Get Method
		public function currency_get(){
			$data = $this->rfq_model->get_currency();
			$this->response($data);
		}

		public function rfq_list_get(){
			$data = $this->rfq_model->get_rfq_list();
			$this->response($data);
		}

		public function line_list_get(){
			$data = $this->rfq_model->get_line_list();
			$this->response($data);
		}

		public function description_list_get(){
			$data['query'] = $this->get('query');
			$data['suggestions'] = $this->rfq_model->get_descriptions_list($data['query']);
			// $this->response($data);
			$this->response($data['suggestions']);
		}

		public function requestor_get(){
			$data = $this->rfq_model->get_requestor();
			$this->response($data);
		}

		public function category_get(){
			$data = $this->rfq_model->get_category();
			$this->response($data);
		}

		public function unit_get(){
			$data = $this->rfq_model->get_unit();
			$this->response($data);
		}

		public function vendornames_get(){
			$data = $this->rfq_model->get_vendornames();
			$this->response($data);
		}

		public function vendorcategory_get(){

			$business_type = $this->get('business_type');
			$user_id = $this->get('user_id');
			$data = $this->rfq_model->get_vendorcategory($business_type, $user_id);
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

		public function city_get(){
			$data = $this->rfq_model->get_city();
			$this->response($data);
		}

		public function brands_get(){
			$data = $this->rfq_model->get_brands();
			$this->response($data);
		}

		public function vendor_list_get(){
			$user_id = $this->get('user_id');

			$data = $this->rfq_model->get_vendor_list($user_id);
			$this->response($data);
		}

		public function line_bom_attachments_get(){
			$data['rfq_id'] = $this->get('rfq_id');
			$data = $this->rfq_model->get_line_bom_attachment($data['rfq_id']);
			$this->response($data);
		}

		public function get_attachment_by_line_id_get(){
			$data['rfqrfb_line_id'] = $this->get('rfqrfb_line_id');
			$data = $this->rfq_model->get_attachment_by_line_id($data['rfqrfb_line_id']);
			$this->response($data);
		}

		public function line_bom_view_get(){
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data = $this->rfq_model->get_line_bom_view($data['line_attachment_id']);
			$this->response($data);
		}

		public function bom_quote_nums_get(){
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data = $this->rfq_model->get_bom_quote_nums($data['line_attachment_id']);
			$this->response($data);
		}

		public function line_bom_cost_post(){
			$data['vendor_id'] = $this->post('vendor_id');
			$data['line_attachment_id'] = $this->post('line_attachment_id');
			$data['row_no'] = $this->post('row_no');
			$data['quote_no'] = $this->post('bquote_no');
			$data['cost'] = $this->post('cost');
			$data['remarks'] = $this->post('remarks');
			$data = $this->rfq_model->update_insert_line_bom_cost($data);
			$this->response($data);
		}

		public function line_bom_cost_get(){
			$data['vendor_id'] = $this->get('vendor_id');
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data['quote_no'] = $this->get('bquote_no');
			$data['row_no'] = $this->get('row_no');
			$data = $this->rfq_model->get_line_bom_cost($data);
			$this->response($data);
		}

		public function vendors_bom_cost_get(){ // all vendors
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data['quote_no'] = $this->get('bquote_no');
			$data['row_no'] = $this->get('row_no');
			$data = $this->rfq_model->get_vendors_bom_cost($data);
			$this->response($data);
		}

		public function vendor_bom_cost_get(){ // single vendor
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data['vendor_id'] = $this->get('vendor_id');
			$data['quote_no'] = $this->get('bquote_no');
			$data['row_no'] = $this->get('row_no');
			$data = $this->rfq_model->get_vendor_bom_cost($data);
			$this->response($data);
		}

		public function vendor_line_bom_get(){ // single vendor
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data['quote_no'] = $this->get('bquote_no');
			$data['vendor_id'] = $this->get('vendor_id');
			$data = $this->rfq_model->get_vendor_line_bom($data);
			$this->response($data);
		}

		public function vendor_line_bom_file_get(){ // single vendor
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data['vendor_id'] = $this->get('vendor_id');
			$data = $this->rfq_model->get_vendor_line_bom_file($data);
			$this->response($data);
		}

		public function vendors_bom_cost_all_get(){
			$data['line_attachment_id'] = $this->get('line_attachment_id');
			$data = $this->rfq_model->get_vendors_bom_cost_all($data);
			$this->response($data);
		}


		public function submit_rfq_creation_post(){
			//header
			$data['draft_validation']	= $this->post('draft_validation');
			$data['rfq_id'] 			= $this->post('rfq_id');
			$data['type'] 				= $this->post('type');
			$data['userdata'] 			= $this->post('userdata');
			$data['title_txt'] 			= $this->post('title_txt');
			$data['type_radio'] 		= $this->post('type_radio');
			//currency
			$data['currency'] 			= $this->post('currency');
			$data['pref_delivery_date'] = $this->post('pref_delivery_date');
			$data['sub_deadline_date'] 	= $this->post('sub_deadline_date');

			//smview only
			$data['count_all_invited'] 	= $this->post('count_all_invited');
			$data['requestor'] 			= $this->post('requestor');
			$data['purpose'] 			= $this->post('purpose');
			$data['purpose_txt'] 		= $this->post('purpose_txt');
			$data['reason'] 			= $this->post('reason');
			$data['reason_txt']			= $this->post('reason_txt');
			$data['internal_note']		= $this->post('internal_note');

			$data['position_id']		= $this->post('position_id');

			$data['status_id']			= $this->post('status_id');
			//lines
			$data['total_lines']		= $this->post('total_lines');

			for($i = 1; $i <= $data['total_lines']; $i++)
			{
				$data['line_category'.$i] 	= $this->post('line_category'.$i);
				$data['line_description'.$i]	= $this->post('line_description'.$i);
				$data['line_measuring_unit'.$i]		= $this->post('line_measuring_unit'.$i);
				$data['quantity'.$i]	= $this->post('quantity'.$i);

				$data['specs'.$i.'_text']	= $this->input->post('specs'.$i.'_text');

				for($x=1; $x <= 8; $x++)
		        {

		            $data['hidden_path_'.$i.'_'.$x] 		= $this->post('hidden_path_'.$i.'_'.$x);
		            $data['attachment_desc_'.$i.'_'.$x] 	= $this->post('attachment_desc_'.$i.'_'.$x);
		            $data['attachment_type_'.$i.'_'.$x]		= $this->post('attachment_type_'.$i.'_'.$x);
		            $data['line_attachment_id_'.$i.'_'.$x]	= $this->post('line_attachment_id_'.$i.'_'.$x);

		        }
			}

			for($y=1;$y <= $data['count_all_invited']; $y++)
		    {
		        $data['vendorinvitefinal_id'.$y] 	= $this->post('vendorinvitefinal_id'.$y);
		        $data['vendorfinal_invite_id'.$y] 	= $this->post('vendorfinal_invite_id'.$y);
		    }

			$this->rfq_model->delete_tables($data);
			$data_new = $this->rfq_model->submit_rfq_creation($data);
			$data['id'] = $data_new;
			$this->rfq_model->insert_invited_vendors($data);

			$this->response($data_new);
		}

		function save_vendor_list_post()
		{
			$data['txt_input_vendor_list_name'] = $this->post('txt_input_vendor_list_name');
			$data['vendor_list_total'] = $this->post('vendor_list_total');
			$data['user_id'] = $this->post('user_id');

			for($i = 1; $i <= $data['vendor_list_total']; $i++)
			{
				$data['vendorinvitefinal_id'.$i] = $this->post('vendorinvitefinal_id'.$i);
				$data['vendorfinal_invite_id'.$i] = $this->post('vendorfinal_invite_id'.$i);
				$data['vendorname_finalinvited'.$i] = $this->post('vendorname_finalinvited'.$i);
			}

			$rs = $this->rfq_model->save_vendor_list($data);

			$this->response($rs);
		}

		function update_rfq_status_post()
		{
			$data['rfqrfb_id'] = $this->post('rfqrfb_id');
			$data['status_id'] = $this->post('status_id');
			$data['position_id'] = $this->post('position_id');
			$data['userdata'] = $this->post('userdata');

			$data['updated'] = $this->rfq_model->update_rfq_status($data['rfqrfb_id'], $data['status_id'], $data['position_id'], $data['userdata']);

			$this->response($data);
		}

		function find_similar_get()
		{
			$email_address = $this->get('txt_email');

			$data = $this->rfq_model->find_similar($email_address);

			$this->response($data);
		}

		function add_new_invite_post()
		{

			// save to SMNTP_VENDOR first
			$where_arr = array('TEMPLATE_ID' => 1,
								'ACTIVE'	 => 1);
			$data['email_template_id'] = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'TEMPLATE_ID', $where_arr);
			$old_data 					= array();
			$old_data['status'] 		=  1 ; // think as draft first to get the next position id
			$old_data['position_id']	=  $this->post('position_id') ; // 2 =  from rfq
			$old_data['type'] 			=  1 ;
			$new_data 					= $this->common_model->get_next_process($old_data);

			$data['status_id'] 			= $new_data['next_status'];
			$data['position_id']		= $new_data['next_position'];


			$data['user_id'] 			= $this->post('user_id');
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
			$data['user_id']				= $this->get('user_id');

			$new_data = $this->rfq_model->search_invite($data);

			$this->response($new_data);
		}

		function attachment_type_get()
		{
			$data = $this->rfq_model->get_attachment_type();
			$this->response($data);
		}


		//------------------------------------ APPROVAL -------------------------------------------------
		function load_approval_data_get()
		{
			$id								= $this->get('id');
			$rfq_title						= $this->get('rfq_title');
			$shortlisted					= $this->get('shortlisted');

			$new_data = $this->rfq_model->get_data_approval($id , $shortlisted);

			$this->response($new_data);
		}

		function load_attachment_data_get()
		{
			$lineid								= $this->get('lineid2');
			$rfx_id								= $this->get('id');

			$new_data = $this->rfq_model->get_attachment_data($lineid, $rfx_id);

			$this->response($new_data);
		}

		function response_creation_approval_put()
		{
			$data['rfx_id']					= $this->put('rfx_id');
			$data['status']					= $this->put('status');
			$data['nxt_position_id']		= $this->put('nxt_position_id');
			$data['reject_reason']			= $this->put('reject_reason');
			$data['user_id'] 				= $this->put('user_id');
			$data['current_status_id'] 		= $this->put('current_status_id');

			$new_data						= $this->rfq_model->response_creation_approval($data);

			$this->response($new_data);
		}

		function next_status_get()
		{
			$data['current_status_id'] 	= $this->get('current_status_id');
			$data['position_id'] 		= $this->get('position_id');
			$data['status_type'] 		= $this->get('status_type');
			$data['reg_type_id']		= $this->get('reg_type_id');

			$new_data					= $this->rfq_model->next_status_get($data);

			$this->response($new_data);
		}

		function mail_to_approver_get()
		{
			$template_id = $this->get('template_id');
			$type_id = $this->get('type_id');
			$status = $this->get('status');

			$query_filter = array('STATUS_ID' => $status,
								  'TYPE_ID' => $type_id);

			$rs = $this->rfq_model->get_mail_template($query_filter);
			//echo $this->db->last_query();
			$data['message'] = '';
			$data['subject'] = '';
			$data['topic'] = '';
			if($rs->num_rows() > 0)
			{
				$data['message'] = $rs->row()->MESSAGE;
				$data['subject'] = $rs->row()->SUBJECT;
				$data['topic'] = $rs->row()->TOPIC;
			}

			$this->response($data);
		}

		function approval_email_data_get()
		{
			$data['rfx_id']				= $this->get('rfx_id');
			$data['type']				= $this->get('type');

			$rs = $this->rfq_model->get_email_recipient($data);


			// save to SMNTP_VENDOR first
			$where_arr = array('RFQRFB_ID' => $data['rfx_id']);
			$data['rfq_title'] = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB', 'TITLE', $where_arr);

			if($data['type'] == 1) // approved  // for approval of rfq only
			{
				$where_arr1 = array('TEMPLATE_TYPE' => 6);
				$rs1 = $this->common_model->get_email_template($where_arr1);
				$email_data['subject'] = 'RFQ - Invitation  - #'.$data['rfx_id'].' '.$data['rfq_title'];
			}
			elseif($data['type'] == 11) // rfq approval of bid monitor of failed bid only
			{
				$where_arr1 = array('TEMPLATE_TYPE' => 10);
				$rs1 = $this->common_model->get_email_template($where_arr1);
				$email_data['subject'] = 'RFQ - Approved Failed Bid - #'.$data['rfx_id'].' '.$data['rfq_title'];
			}

			// put message in variable
			//$email_data['bcc'] = 'johnchristopher.santos@sandmansystems.com';



			if($rs->num_rows() > 0)
			{
				foreach($rs->result() as $row)
				{
					// change email datas
					$email_data['content'] = $rs1->row()->CONTENT;
					if($data['type'] == 1) // approved  // for approval of rfq only
					{
						$email_data['content'] = str_replace('[repname]', $row->VENDOR_NAME, $email_data['content']); // (what tofind, value change, whole sentence)
					}
					elseif($data['type'] == 11)
					{
						$email_data['content'] = str_replace('[RFQ_ID]', $data['rfx_id'], $email_data['content']); // (what tofind, value change, whole sentence)
						$email_data['content'] = str_replace('[RFQ_TITLE]', $data['rfq_title'], $email_data['content']); // (what tofind, value change, whole sentence)
						$email_data['content'] = str_replace('[vendor_name]', $row->VENDOR_NAME, $email_data['content']); // (what tofind, value change, whole sentence)
					}


					$email_data['to'] = $row->EMAIL;
					$this->common_model->send_email_notification($email_data);

					$email_data['content'] = '';
				}
			}

		}

		function get_messages_get()
		{
			$data['rfx_id']			= $this->get('rfx_id');
			$data['user_id']		= $this->get('user_id');

			$rs = $this->rfq_model->get_messages($data);
			$data['count'] =  $rs->num_rows();
			//echo $this->db->last_query();
			$this->response($data);
		}

		function get_rfq_invitation_status_get()
		{
			$rfx_id			= $this->get('rfx_id');
			$invite_id		= $this->get('invite_id');

			$array_where = array(
								 'RFQRFB_ID' => $rfx_id,
								 'INVITE_ID' => $invite_id
								 );
			$data['status'] = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_INVITE_STATUS', 'STATUS_ID', $array_where);
			$data['position_id'] = $this->common_model->get_from_table_where_array('SMNTP_RFQRFB_INVITE_STATUS', 'POSITION_ID', $array_where);

			$this->response($data);
		}

		function get_rfq_response_quote_get()
		{
			$data['rfx_id']			= $this->get('rfx_id');
			$data['invite_id']		= $this->get('invite_id');

			$array_where 			= array(
											'RFQRFB_ID'		=>		$data['rfx_id'],
											'INVITE_ID'		=>		$data['invite_id']
											);

			//$status 				= $this->rfq_model->get_response_status_version($array_where);
			$quote 					= $this->rfq_model->get_quotes($array_where);

			$new_data 				= $quote->result_array();
			$this->response($new_data);
		}

		//--------------------------------- END OF APPROVAL ---------------------------------------------
		// -------------------------------- INVITATION ---------------------------------------------

		function invitation_response_get()
		{
			$data['vendor_id'] 			= $this->get('vendor_id');
			$data['action'] 			= $this->get('action');
			$data['nxt_position_id']	= $this->get('nxt_position_id');
			$data['status']				= $this->get('status');
			$data['rfx_id'] 			= $this->get('rfx_id');
			$data['invite_id'] 			= $this->get('invite_id');

			$new_data = $this->rfq_model->invitation_response($data);

			$this->response($new_data);
		}

		function invitation_status_get()
		{
			$data['rfx_id'] 			= $this->get('rfx_id');

			$new_data = $this->rfq_model->invitation_status($data);

			$this->response($new_data);

		}

		// -------------------------------- END OF INVITATION ---------------------------------------
		// -------------------------------- RESPONSE CREATION ---------------------------------------
		function submit_response_creation_get()
		{
			$data['date_created']		= $this->get('date_created');
			$data['user_id'] 			= $this->get('user_id');
			$data['position_id'] 		= $this->get('position_id');
			$data['created_by']			= $this->get('created_by');
			$data['rfx_id'] 			= $this->get('rfx_id');
			$data['line_num_rows'] 		= $this->get('line_num_rows');
			$data['active'] 			= $this->get('active');
			$data['nxt_position_id']	= $this->get('nxt_position_id');
			$data['status']				= $this->get('status');
			$data['vendor_id']			= $this->get('vendor_id');
			$data['invite_id']			= $this->get('invite_id');
			$data['line_data_count']	= $this->get('line_data_count');
			$data['version']			= $this->get('version');

			// lines where loop begins

			for($i=1; $i <= $data['line_data_count']; $i++)
			{
				$data['rfqrfbline_id'.$i] 		= $this->get('rfqrfbline_id'.$i);
				$data['num_quote'.$i]			= $this->get('num_quote'.$i);

				for($x=1; $x<=$data['num_quote'.$i];$x++)
				{

					$data['txt_quote'.$i.'_'.$x] 			= $this->get('txt_quote'.$i.'_'.$x);
					$data['quoteischecked'.$i.'_'.$x]		= $this->get('quoteischecked'.$i.'_'.$x);
					$data['delivery_time'.$i.'_'.$x]		= $this->get('delivery_time'.$i.'_'.$x);
					$data['txt_counteroffer'.$i.'_'.$x]		= $this->get('txt_counteroffer'.$i.'_'.$x);
					$data['hidden_quote_path_'.$i.'_'.$x] 	= $this->get('hidden_quote_path_'.$i.'_'.$x);
				}
			}

			$data_result = $this->rfq_model->save_response_creation($data);

			$this->response($data_result);
		}
		// ---------------------------- END OF RESPONSE CREATION ------------------------------------
		// -------------------------------- BID MONITOR ---------------------------------------------
		function num_participants_get()
		{
			$data['rfx_id'] = $this->get('id');
			$data_result = $this->rfq_model->num_participants($data);

			$this->response($data_result);
		}

		function num_invited_get()
		{
			$data['rfx_id'] = $this->get('id');

			$data_result = $this->rfq_model->num_invited($data);

			$this->response($data_result);
		}

		function num_responses_get()
		{
			$data['rfx_id'] = $this->get('id');

			$data_result = $this->rfq_model->num_responses($data);

			$this->response($data_result);
		}

		// ------------------------------ END BID MONITOR -------------------------------------------

		// --------------------------------- LOAD MAIN ----------------------------------------------

		function load_main_data_get()
		{
			$data['title'] = $this->get('rfq_title');
			$data['rfq_id'] = $this->get('rfq_id');

			$main_data = $this->rfq_model->get_data_main_table($data);
			if($main_data->num_rows() > 0)
			{
				$data['rfx_id'] = $main_data->row(0)->RFQRFB_ID;
				$new_data['preferred_del_date']		= date('Y-m-d', strtotime($main_data->row(0)->DELIVERY_DATE));
				if($new_data['preferred_del_date'] == '1970-01-01' || $new_data['preferred_del_date'] == '2069-12-31')
					$new_data['preferred_del_date'] = '';
				$new_data['submission_date']		= date('Y-m-d', strtotime($main_data->row(0)->SUBMISSION_DEADLINE));
				if($new_data['submission_date'] == '1970-01-01' || $new_data['submission_date'] == '2069-12-31')
					$new_data['submission_date'] = '';
			}
			else
			{
				$data['rfx_id'] = 0;
				$new_data['preferred_del_date'] = '';
				$new_data['submission_date'] = '';
			}
			$lines_data = $this->rfq_model->get_lines_data_table($data);
			$attachment_data = $this->rfq_model->get_attachment_data_table($data);
			$invited_data = $this->rfq_model->get_invited_data_table($data);


			$new_data['main'] 					= $main_data->result_array();
			$new_data['line_data'] 				= $lines_data->result_array();
			$new_data['line_data_count'] 		= $lines_data->num_rows();
			$new_data['attachment_data']		= $attachment_data->result_array();
			$new_data['attachment_data_count'] 	= $attachment_data->num_rows();
			$new_data['invited_data']			= $invited_data->result_array();
			$new_data['invited_data_count'] 	= $invited_data->num_rows();

			$this->response($new_data);
		}

		function get_invite_vendors_get()
		{
			$data['rfx_id'] = $this->get('id');

			$invited_data = $this->rfq_model->get_invited_data_table($data);

			$new_data['result'] 		= $invited_data->result_array();
			$new_data['result_count'] 	= $invited_data->num_rows();

			$this->response($new_data);
		}

		function get_total_lines_get()
		{
			$data['rfx_id'] = $this->get('rfx_id');
			$lines_data = $this->rfq_model->get_lines_data_table($data);

			$new_data['line_data_count'] 		= $lines_data->num_rows();

			$this->response($new_data);
		}

		function get_draft_quotes_get()
		{
			$data['rfx_id'] 		= $this->get('rfx_id');
			$data['lineid'] 		= $this->get('lineid');
			$data['invite_id'] 		= $this->get('invite_id');
			$data['shortlisted'] 	= $this->get('shortlisted');
			$data['version'] 		= $this->get('version');

		    $quote_data = $this->rfq_model->get_response_draft_data($data);

		    $this->response($quote_data);
		}

		function get_response_quotes_get()
		{
			$data['rfx_id'] 		= $this->get('rfx_id');
			$data['lineid'] 		= $this->get('lineid');
			$data['invite_id'] 		= $this->get('invite_id');
			$data['shortlisted'] 	= $this->get('shortlisted');
			// $data['version'] 		= $this->get('version');

		    $quote_data = $this->rfq_model->get_response_data($data);

		    $this->response($quote_data);
		}

		// ------------------------------- END LOAD MAIN --------------------------------------------

		function get_doc_location_get()
		{
			$type = $this->get('attachment_type');

			$location = $this->rfq_model->get_attachment_location($type);

			$this->response($location);

		}


		public function rfq_history_tbl_get()
		{
			$var['user_id'] 	= $this->get('user_id');
			$var['position_id'] = $this->get('position_id');
			$var['rfqrfb_id'] = $this->get('rfqrfb_id');
			$var['rpp']			= 25;
			$var['page_num']	= $this->get('current_page');

			$rs = $this->rfq_model->get_rfq_history($var);
			// echo $this->db->last_query();

			if ($rs)
			{
				$data = $rs;
				$data['status'] = TRUE;
				$data['error'] = '';

				$this->response($data);
			}
			else
			{
				$data['status'] = FALSE;
				$data['error'] = 'Something went wrong!';
				$this->response($data);
			}
		}



		function validate_duplicate_get()
		{
			$data['table'] 		= $this->get('table');
			$data['field'] 		= $this->get('field');
			$data['value'] 		= $this->get('value');
			$data['id_field']	= $this->get('id_field');
			$data['id'] 		= $this->get('id');

			$rs = $this->rfq_model->validate_duplicate($data);
			$this->response($rs);

		}

		function appover_data_get()
		{
			$data['user_id'] 		= $this->get('user_id');

			$rs = $this->rfq_model->appover_data($data);

			$data['result'] = $rs->result();
			$data['num_rows'] = $rs->num_rows();


			$this->response($data);
		}

		function user_data_get()
		{
			$data['user_id'] 		= $this->get('user_id');

			$rs = $this->rfq_model->user_data($data['user_id']);
			// echo $this->db->last_query();
			$data['result'] = $rs->result();
			$data['num_rows'] = $rs->num_rows();


			$this->response($data);
		}

		function search_vendor_get()
		{
			$vendor_name = $this->get('search_value');
			$user_id = $this->get('user_id');

			$rs = $this->rfq_model->get_vendor_invite_list($vendor_name, $user_id);

			$this->response($rs);
		}

		function search_vendor_list_get()
		{
			$user_id 	= $this->get('user_id');
			$list_name 	= $this->get('list_name');

			$data = $this->rfq_model->get_vendor_list($user_id, $list_name);
			//echo $this->db->last_query();
			$this->response($data);
		}

		function search_vendor_list_participants_get()
		{
			$user_id 	= $this->get('user_id');
			$list_id 	= $this->get('list_id');

			$data = $this->rfq_model->get_vendor_list_participants($user_id, $list_id);
			//echo $this->db->last_query();
			$this->response($data);
		}


		function update_vendor_list_post()
		{
			$date_timestamp = date('m/d/Y h:i:s A');
			$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
			$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");

			$data['vendor_list_id']					= $this->post('vendor_list_id');
			$data['user_id']						= $this->post('user_id');
			$data['txt_input_vendor_list_name'] 	= $this->post('txt_input_vendor_list_name');
			$data['total_left_count'] 				= $this->post('total_left_count');

			// update name only
			$record_arr = array('VENDOR_LIST_NAME' => $data['txt_input_vendor_list_name']);
			$where_arr = array('VENDOR_LIST_ID' => $data['vendor_list_id']);
			$this->common_model->update_table('SMNTP_VENDOR_LIST', $record_arr, $where_arr);

			// delete old record of invited
			$where_arr = array('VENDOR_LIST_ID' => $data['vendor_list_id']);
			$this->common_model->delete_table('SMNTP_VENDOR_LIST_DEFN', $where_arr);

			// insert new invited
			for($i = 1; $i <= $data['total_left_count']; $i++)
			{
				$insert_array = array('VENDOR_LIST_ID' 	=> $data['vendor_list_id'],
									  'INVITE_ID'		=> $this->post('invite_id'.$i),
									  'CREATED_BY'		=> $data['user_id'],
									  'DATE_CREATED'	=> $date_timestamp,
									  'ACTIVE'			=> 1,
									  );
				$this->common_model->insert_table('SMNTP_VENDOR_LIST_DEFN', $insert_array);
			}

			//echo $this->db->last_query();
			$this->response('');
		}

		function delete_vendor_list_post()
		{
			$data['user_id']						= $this->post('user_id');
			$data['vendor_list_id']					= $this->post('vendor_list_id');

			// delete old record of invited
			$where_arr = array('VENDOR_LIST_ID' => $data['vendor_list_id']);
			$this->common_model->delete_table('SMNTP_VENDOR_LIST', $where_arr);

			// delete old record of invited
			$where_arr = array('VENDOR_LIST_ID' => $data['vendor_list_id']);
			$this->common_model->delete_table('SMNTP_VENDOR_LIST_DEFN', $where_arr);

		}

		function get_single_data_get()
		{
			$data['rfx_id'] = $this->get('rfb_id');
			$field = $this->get('field');
			$table = $this->get('table');

			// save to SMNTP_VENDOR first
			$where_arr = array('RFQRFB_ID' => $data['rfx_id']);
			$data['rfq_result'] = $this->common_model->get_from_table_where_array($table, $field, $where_arr);

			$this->response($data);
		}

	}

?>
