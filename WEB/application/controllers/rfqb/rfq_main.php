<?php
Class Rfq_Main extends CI_Controller{


/*
	// load rest api app server
	public function __construct() {
		parent::__construct();
		$this->load->library('rest', $this->config->item('app_server_api'));
	}*/

	function rfq_onload_modal_view()
	{
		$data['rfq_list'] = $this->rest_app->get('index.php/rfq_api/rfq_list/', '', 'application/json');
		//$this->rest_app->debug();

		$this->load->view('rfqb/rfq_onload_modal_view', $data);
	}

	function search_rfq()
	{
		$data['rfq_title'] = $this->input->post('find_rfq');
		$data['rfq_id'] = $this->input->post('rfq_id');

		$all_data 		= $this->rest_app->get('index.php/rfq_api/load_main_data/', $data, 'application/json');
		//$this->rest_app->debug();

		echo json_encode($all_data);
	}

	function index($rfq_id = 0)
	{
		$data['user_id'] 				= $this->session->userdata('user_id');
		$data['business_type']			= $this->session->userdata('business_type');

		$appover_data 					= $this->rest_app->get('index.php/rfq_api/appover_data/', $data, 'application/json');
		//$this->rest_app->debug();
		$data_select[''] = "-- Select --";
		$vendorcategory_data 			= $this->rest_app->get('index.php/rfq_api/vendorcategory/', $data, 'application/json');
		$vendornames_data				= $this->rest_app->get('index.php/rfq_api/search_vendor/', $data, 'application/json');
		$currency_data_array			= $this->rest_app->get('index.php/rfq_api/currency/', '', 'application/json');
		$requestor_data_array	 		= $this->rest_app->get('index.php/rfq_api/requestor/', '', 'application/json');
		$purpose_data_array 			= $this->rest_app->get('index.php/rfq_api/purpose/', '', 'application/json');
		$reason_data_array	 			= $this->rest_app->get('index.php/rfq_api/reason/', '', 'application/json');
		$unit_data_array	 			= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');
		$data['line_list']				= $this->rest_app->get('index.php/rfq_api/line_list/', '', 'application/json');
		$rfq_data						= $this->rest_app->get('index.php/rfq_api/rfq_list/', '', 'application/json');
		$location_data			 		= $this->rest_app->get('index.php/rfq_api/city/', '', 'application/json');
		$brand_data						= $this->rest_app->get('index.php/rfq_api/brands/', '', 'application/json');
		$vendorlist_data				= $this->rest_app->get('index.php/rfq_api/vendor_list/', '', 'application/json');

		$data['dafault_currency'] = '';
		$data['approvers_content'] = '';
		$data['bom_file_modals'] = '';

		if($appover_data->num_rows > 0)
		{
			foreach($appover_data->result as $row)
			{
				$data['approvers_content'] .= '<tr>';
				$data['approvers_content'] .= '<td>'.$row->USER_FIRST_NAME.' '.$row->USER_LAST_NAME.'</td>';
				$data['approvers_content'] .= '<td>'.$row->POSITION_NAME.'</td>';
				$data['approvers_content'] .= '<td>1</td>';
				$data['approvers_content'] .= '</tr>';
			}
		}



		$category_array = array();
		$category2_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
			$category2_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;
		$data['category2_array'] = $category2_array;

		$currency_data = array();
		$currency_data[''] = '-- Select --';
		foreach($currency_data_array as $row)
		{
			$currency_data[$row->CURRENCY_ID] = $row->ABBREVIATION;
			if($row->DEFAULT_FLAG == 1)
				$data['dafault_currency'] = $row->CURRENCY_ID;
		}
		$data['currency_data']	 = $currency_data;

		$requestor_data = array();
		$requestor_data[''] = '-- Select --';
		foreach($requestor_data_array as $row)
		{
			$requestor_data[$row->REQUESTOR_ID] = $row->REQUESTOR;
		}
		$data['requestor_data']	 = $requestor_data;

		$purpose_data = array();
		$purpose_data[''] = '-- Select --';
		foreach($purpose_data_array as $row)
		{
			$purpose_data[$row->PURPOSE_ID] = $row->PURPOSE;
		}
		$data['purpose_data']	 = $purpose_data;

		$reason_data = array();
		$reason_data[''] = '-- Select --';
		foreach($reason_data_array as $row)
		{
			$reason_data[$row->REASON_ID] = $row->REASON;
		}
		$data['reason_data']	 = $reason_data;

		$unit_array = array();
		$unit_array[''] = '-- Select --';
		foreach($unit_data_array as $row)
		{
			$unit_array[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
		}
		$data['unit_array']	 = $unit_array;

		$description_array = array();
		foreach($data['line_list'] as $row)
		{
			if (!in_array($row->DESCRIPTION,$description_array))
				$description_array[$row->RFQRFB_LINE_ID] = $row->DESCRIPTION;
		}
		$data['description_array']	 = $description_array;

		$vendornames_array = array();
		foreach($vendornames_data as $row)
		{
			if (!in_array($row->VENDOR_NAME,$vendornames_array))
				$vendornames_array[$row->VENDOR_INVITE_ID] = $row->VENDOR_NAME;
		}
		$data['vendornames_array']	 = $vendornames_array;

		$vendorlist_array = array();
		foreach($vendorlist_data as $row)
		{
			if (!in_array($row->VENDOR_LIST_NAME,$vendorlist_array))
				$vendorlist_array[$row->VENDOR_LIST_ID] = $row->VENDOR_LIST_NAME;
		}
		$data['vendorlist_array']	 = $vendorlist_array;

		$brand_array = array();
		foreach($brand_data as $row)
		{
			if (!in_array($row->BRAND_NAME,$brand_array))
				$brand_array[$row->BRAND_ID] = $row->BRAND_NAME;
		}
		$data['brand_array']	 = $brand_array;

		$location_array = array();
		foreach($location_data as $row)
		{
			if (!in_array($row->CITY_NAME,$location_array))
				$location_array[$row->CITY_ID] = $row->CITY_NAME;
		}
		$data['location_array']	 = $location_array;

		$rfq_array = array();
		foreach($rfq_data as $row)
		{
			if (!in_array($row->TITLE,$rfq_array))
				$rfq_array[$row->RFQRFB_ID] = $row->TITLE;
		}
		$data['rfq_array']	 = $rfq_array;



		$data['rfq_id'] = $rfq_id;
		$data['draft_validation'] = 0;
		$data['rfq_id_label'] = '(Auto Generate)';
		if($rfq_id != 0)
		{
			$data['draft_validation'] = 1;
			$data['rfq_id_label'] = $rfq_id;
		}

		$bid_array = array();
		$bid_array['1'] = '1';
		$data['bid_array'] = array_merge($data_select, $bid_array);
		$data['vendorcategory_data'] =  $vendorcategory_data;

		$this->load->view('rfqb/rfq_main_view', $data);
	}

	function validate_duplicate()
	{
		$table = $this->input->post('table');
		$data['value'] = $this->input->post('value');
		$data['id'] = $this->input->post('id');

		if($table == 'VENDOR')
		{
			$data['table'] = 'SMNTP_VENDOR';
			$data['field'] = 'VENDOR_NAME';
			$data['id_field'] = 'VENDOR_INVITE_ID';
		}
		elseif($table == 'RFQ')
		{
			$data['table'] = 'SMNTP_RFQRFB';
			$data['field'] = 'TITLE';
			$data['id_field'] = 'RFQRFB_ID';
		}

		$rs = $this->rest_app->get('index.php/rfq_api/validate_duplicate', $data, 'application/json');
		//$this->rest_app->debug();
		echo $rs;

	}

	function rfqrfb_main_view()
		{
			$param['user_position_id'] = $this->session->userdata('position_id');
			$param['status_type'] = 2;
			$param['buyer_position_id'] = 7;

			$time_array = array();
			$time_array[0] = 'All';
			$time_array[1] = 'Less than 1 Days';
			$time_array[3] = 'Less than 3 Days';
			$time_array[5] = 'Less than 5 Days';

			$data['time_array'] = $time_array;
			$data['filter_status'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_status', $param, 'application/json');
			$data['filter_buyer'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_buyer', $param, 'application/json');
			$data['filter_requestor'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_requestor', $param, 'application/json');
			$data['filter_purpose'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_purpose', $param, 'application/json');
			//$this->rest_app->debug();
			$this->load->view('rfqb/rfqrfb_main_view', $data);
		}

	function rfqrfb_main_vendor_view()
		{
			$param['user_position_id'] = $this->session->userdata('position_id');
			$param['status_type'] = 2;
			$param['buyer_position_id'] = 7;

			$time_array = array();
			$time_array[0] = 'All';
			$time_array[1] = 'Less than 1 Days';
			$time_array[3] = 'Less than 3 Days';
			$time_array[5] = 'Less than 5 Days';

			$data['time_array'] = $time_array;
			$data['filter_status'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_status', $param, 'application/json');
			$data['filter_buyer'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_buyer', $param, 'application/json');
			$data['filter_requestor'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_requestor', $param, 'application/json');
			$data['filter_purpose'] = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/filter_purpose', $param, 'application/json');
			//$this->rest_app->debug();
			$this->load->view('rfqb/rfqrfb_main_vendor_view', $data);
		}

	function rfqrfbmain_table()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['search_no'] 	= $this->input->post('search_no');
		$data['search_title'] 	= $this->input->post('search_title');
		$data['date_created'] 	= $this->input->post('date_created');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['timeleft_filter'] 	= $this->input->post('timeleft_filter');
		$data['buyer_id'] 	= $this->input->post('cbo_buyer');
		$data['requestor_id'] 	= $this->input->post('cbo_requestor');
		$data['purpose_id'] 	= $this->input->post('cbo_purpose');
		$data['status_id'] 		= $this->input->post('cbo_status');
		$data['page_no']		= $this->input->post('page_no');
		$data['sort']			= $this->input->post('sort');
		$data['sort_type']		= $this->input->post('sort_type');

/*		echo json_encode($data);
		return;*/

		$rs = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/rfqrfbtable', $data, 'application/json');
		// var_dump($rs);
	/*	$this->rest_app->debug();*/
		echo json_encode($rs);
	}

	function rfqrfbmain_vendor_table()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['vendor_id'] 		= $this->session->userdata('vendor_id');
		$data['vendor_invite_id'] = $this->session->userdata('vendor_invite_id');
		$data['timeleft_filter'] 	= $this->input->post('timeleft_filter');
		$data['date_created'] 	= $this->input->post('date_created');
		$data['search_no'] 		= $this->input->post('search_no');
		$data['search_title'] 	= $this->input->post('search_title');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['status_id'] 		= $this->input->post('cbo_status');

		$rs = $this->rest_app->get('index.php/rfqrfb/rfqrfb_main/rfqrfbtable_vendor', $data, 'application/json');
		//$this->rest_app->debug();

		echo json_encode($rs);
	}

	function submit_rfq_creation()
	{
		$data['userdata'] 			= $this->session->userdata('user_id');
		$data['position_id'] 			= $this->session->userdata('position_id');

		//header
		$data['draft_validation']	= $this->input->post('draft_validation');
		$data['type'] 				= $this->input->post('type');
		$data['rfq_id'] 			= $this->input->post('rfq_id');
		$data['status_id'] 			= $this->input->post('status_id');
		$data['title_txt'] 			= $this->input->post('title_txt');
		$data['type_radio'] 		= $this->input->post('type_radio');
		//currency
		$data['currency'] 			= $this->input->post('currency');
		$data['pref_delivery_date'] = date('d-M-Y', strtotime($this->input->post('pref_delivery_date')));
		$data['sub_deadline_date'] 	= date('d-M-Y', strtotime($this->input->post('sub_deadline_date')));

		//smview only
		$data['requestor'] 			= $this->input->post('requestor');
		$data['purpose'] 			= $this->input->post('purpose');
		$data['purpose_txt'] 		= $this->input->post('purpose_txt');
		$data['reason'] 			= $this->input->post('reason');
		$data['reason_txt']			= $this->input->post('reason_txt');
		$data['internal_note']		= $this->input->post('internal_note');

		//lines
		$data['total_lines']		= $this->input->post('total_lines');
		$data['count_all_invited']	= $this->input->post('count_all_invited');

		for($i = 1; $i <= $data['total_lines']; $i++)
		{
			$data['line_category'.$i] 	= $this->input->post('line_category'.$i);
			$data['line_description'.$i]	= $this->input->post('line_description'.$i);
			$data['line_measuring_unit'.$i]		= $this->input->post('line_measuring_unit'.$i);
			$data['quantity'.$i]	= str_replace(',', '', $this->input->post('quantity'.$i));
			//hides in default
			$data['specs'.$i.'_text']	= $this->input->post('specs'.$i.'_text');
			//add path of attachments

			for($x=1; $x <= 8; $x++)
	        {
	            $data['hidden_path_'.$i.'_'.$x] 		= $this->input->post('hidden_path_'.$i.'_'.$x);
	            $data['attachment_desc_'.$i.'_'.$x] 	= $this->input->post('attachment_desc_'.$i.'_'.$x);
	            $data['attachment_type_'.$i.'_'.$x]		= $this->input->post('attachment_type_'.$i.'_'.$x);
	            $data['line_attachment_id_'.$i.'_'.$x]	= $this->input->post('line_attachment_id_'.$i.'_'.$x);
	        }
		}

		for($y=1;$y <= $data['count_all_invited']; $y++)
	    {
	        $data['vendorinvitefinal_id'.$y] 	= $this->input->post('vendorinvitefinal_id'.$y);
	        $data['vendorfinal_invite_id'.$y] 	= $this->input->post('vendorfinal_invite_id'.$y);
	    }

		$data['rfq_id'] 		= $this->rest_app->post('index.php/rfq_api/submit_rfq_creation/', $data, '');

		$data_email['template_id'] = 41;
		$data_email['type_id'] = 2;
		$data_email['status'] = 21;

		$data['user_id'] 	= $this->session->userdata('user_id');
		$appover_data 		= (array)$this->rest_app->get('index.php/rfq_api/appover_data/', $data, 'application/json');

		$message_data	 	= (array)$this->rest_app->get('index.php/rfq_api/mail_to_approver/', $data_email, 'application/json');


		if($data['type'] == 1)// for submit only
		{
			$title = 'RFQ#'.$data['rfq_id'].' - '.$data['title_txt'];

			$msg = str_replace('[approvername]', $appover_data['result'][0]->USER_FIRST_NAME.' '.$appover_data['result'][0]->USER_LAST_NAME, $message_data['message']);
			$msg = str_replace('[rfqtile]', $title, $msg);
			$msg = str_replace('[sendername]', $this->session->userdata('user_first_name') .' '.$this->session->userdata('user_last_name'), $msg);

			$subject = str_replace('[rfqtitle]', $title, $message_data['subject']);
			$topic = str_replace('[rfqtitle]', $title, $message_data['topic']);

			$post_data['user_id'] = $this->session->userdata('user_id');

			$post_data['type'] = 'notification';
			$post_data['recipient_id'] = $appover_data['result'][0]->USER_ID;
			$post_data['mail_subj'] = $subject;
			$post_data['mail_topic'] = $topic;
			$post_data['mail_body'] = $msg;
			$post_data['rfqrfb_id'] = $data['rfq_id'];

			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
		}

		if ($data['rfq_id'] && $data['rfq_id'] >0) {
			$boms = $this->rest_app->get('index.php/rfq_api/line_bom_attachments/', array('rfq_id' => $data['rfq_id']), 'application/json');
			$data['boms'] = $boms;
			if ($boms && count($boms)>0) {
				foreach ($boms as $bom) {
					$params = array('file_path' => base_url($bom->FILE_PATH),
						'line_attachment_id' => $bom->LINE_ATTACHMENT_ID
					);
					$data['parse_bom'] = $this->rest_etl->post('index.php/uploader/parse_bom', $params, '');
					// $this->rest_etl->debug();
				}
			}
		}
		$bom_valid = true; //only on submit, update as draft if bom failed
		if ($data['type'] == 1 && isset($data['parse_bom']) && isset($data['parse_bom']->STATUS) && !$data['parse_bom']->STATUS) {
			$update_as_draft = array('rfqrfb_id' => $data['rfq_id'],
				'status_id' => 20, // draft
				'position_id' => $data['position_id'],
				'userdata' => $data['userdata']);
			$data['update_status']		= $this->rest_app->post('index.php/rfq_api/update_rfq_status/', $update_as_draft, '');
			// $this->rest_app->debug();
			$bom_valid = false;
		}

		$data['bom_valid'] = $bom_valid;

		echo json_encode($data); //['rfq_id']
	}

	function add_new_invite()
	{
		$data['user_id'] 			= $this->session->userdata('user_id');
		$data['position_id']		= $this->session->userdata('position_id');
		$data['vendorname'] 		= $this->input->post('vendorname');
		$data['vendorcontact'] 		= $this->input->post('vendorcontact');
		$data['email'] 				= $this->input->post('email');
		$count_all_invited			= $this->input->post('count_all_invited');
		$next_col = $count_all_invited + 1;


		$request					= (array)$this->rest_app->post('index.php/rfq_api/add_new_invite/', $data);
		//json_decode(json_encode($request), true);
		json_decode(json_encode($request), true);
		$data = '';
		$table = '';

		for($i=1; $i <= $count_all_invited; $i++)
		{
			$data  .= '<tr>
							<td><input type="checkbox" name="transfered_invited'.$i.'" id="transfered_invited'.$i.'" value="'.$i.'" onchange="invitecheck(this.checked, '.$i.', \'final_invited_chkbx\')"></td>
							<td>'.$this->input->post('vendorname_finalinvited'.$i)
								 .form_hidden('vendorinvitefinal_id'.$i, $this->input->post('vendorinvitefinal_id'.$i))
								 .form_hidden('vendorfinal_invite_id'.$i, $this->input->post('vendorfinal_invite_id'.$i))
								 .form_hidden('vendorname_finalinvited'.$i, $this->input->post('vendorname_finalinvited'.$i))
								 .form_hidden('final_invited_chkbx'.$i, $this->input->post('final_invited_chkbx'.$i)).
							'</td>
						</tr>';
		}

		$data .=	'<tr>
							<td><input type="checkbox" name="transfered_invited'.$next_col.'" id="transfered_invited'.$next_col.'" value="'.$next_col.'" onchange="invitecheck(this.checked, '.$i.', \'final_invited_chkbx\')"></td>
							<td>'.$request['result']->VENDOR_NAME
								 .form_hidden('vendorinvitefinal_id'.$next_col, 0)//$request['result']->VENDOR_ID) change to zero for new invite doesnt have insert in SMNTP_VENDOR meaning no VENDOR_ID
								 .form_hidden('vendorfinal_invite_id'.$next_col, $request['result']->VENDOR_INVITE_ID)
								 .form_hidden('vendorname_finalinvited'.$next_col,$request['result']->VENDOR_NAME)
								 .form_hidden('final_invited_chkbx'.$next_col, 0).
							'</td>
						</tr>';


		$table = 	'
					<input type="hidden" name="count_all_invited" id="count_all_invited" value="'.$next_col.'">
					<table class="table">
						<thead>
							<div class="col-md-2">
								<th>
									Select
											'.form_checkbox("check_all_vendor", "all", FALSE).'
								</th>
							</div>
							<div class="col-md-10">
								<th>Vendor</th>
							</div>
						</thead>
						<tbody>
							'.$data.'
						</tbody>
					</table>';

		echo $table;

/*
		if ($data['request'] == 1)
			echo 'success';
		else
			echo 'failed';*/
	}

	function search_invite_rfq()
	{
		//this is search vendor result
		$data['cbo_vendorname']			= 	$this->input->post('cbo_vendorname');
		$data['cbo_vendorlist']			= 	$this->input->post('cbo_vendorlist');
		$data['cbo_vendorcategory']		= 	$this->input->post('cbo_vendorcategory');
		$data['cbo_vendorbrand']		= 	$this->input->post('cbo_vendorbrand');
		$data['cbo_vendorlocation']		= 	$this->input->post('cbo_vendorlocation');
		$data['cbo_vendorrfq']			= 	$this->input->post('cbo_vendorrfq');
		$data['user_id']				=	$this->session->userdata['user_id'];
		$table		= (array)$this->rest_app->get('index.php/rfq_api/search_invite/', $data, 'application/json');
		//$this->rest_app->debug();

		$table_new = '';
		$i = 1;

		if ($table['num_rows'] > 0)
		{
			foreach($table['result'] as $row)
			{
				$table_new .= '<tr>';
				$table_new .= '<td><input type="checkbox" id="list_vendor'.$i.'" name="list_vendor'.$i.'" value="'.$i.'" onchange="invitecheck(this.checked, '.$i.', \'vendorischecked\')"></td>';
				$table_new .= '<td>'.form_hidden('vendorinvite_id'.$i ,$row->VENDOR_ID)
									.form_hidden('vendorinvite_invite_id'.$i ,$row->VENDOR_INVITE_ID)
									.form_hidden('vendorinvite_name'.$i ,$row->VENDOR_NAME)
									.form_hidden('vendorischecked'.$i ,0)
									.$row->VENDOR_NAME.'</td>';
				$table_new .= '<td>'.$row->CONTACT_PERSON.'</td>';
				$table_new .= '<tr>';

				$i++;
			}
		}

		$data['table_data']	= '		<input type="hidden" name="total_search_result" id="total_search_result" value="'.$table['num_rows'].'">
									<table class="table">
										<thead>
											<th><input type="checkbox" name="select_invite_all" id="select_invite_all" onchange="select_all_invite('.$table['num_rows'].', this.checked)">Select</th>
											<th>Vendor</th>
											<th>Contact</th>
										</thead>
										<tbody>
											'.$table_new.'
										</tbody>
									</table>';

		echo $data['table_data'];
	}

	function transfer_invited_vendor()
	{
		$total_search_result = $this->input->post('total_search_result');
		$count_all_invited = $this->input->post('count_all_invited');
		$next_col = $count_all_invited + 1;

		$count = $count_all_invited;

		$data = '';

		$vendorid = array();


			for($z=1; $z <= $count_all_invited; $z++)
			{
				$data  .= '<tr>
								<td><input type="checkbox" name="transfered_invited'.$z.'" id="transfered_invited'.$z.'" value="'.$z.'" onchange="invitecheck(this.checked, '.$z.', \'final_invited_chkbx\')"></td>
								<td>'.$this->input->post('vendorname_finalinvited'.$z)
									 .form_hidden('vendorinvitefinal_id'.$z, $this->input->post('vendorinvitefinal_id'.$z))
									 .form_hidden('vendorfinal_invite_id'.$z, $this->input->post('vendorfinal_invite_id'.$z))
									 .form_hidden('vendorname_finalinvited'.$z, $this->input->post('vendorname_finalinvited'.$z))
									 .form_hidden('final_invited_chkbx'.$z, $this->input->post('final_invited_chkbx'.$z)).
								'</td>
							</tr>';

				array_push($vendorid, $this->input->post('vendorinvitefinal_id'.$z));
			}

		for($i= 1; $i <= $total_search_result; $i++)
		{
			if ($this->input->post('vendorischecked'.$i) == 1)
			{

				$not_valid = 0;

				if (in_array($this->input->post('vendorinvite_id'.$i), $vendorid))
					$not_valid = 1;

				if ($not_valid == 0)
				{
					$count++;
					$data .= '
							<tr>
								<td><input type="checkbox" name="transfered_invited'.$count.'" id="transfered_invited'.$count.'" value="'.$count.'" onchange="invitecheck(this.checked, '.$i.', \'final_invited_chkbx\')"></td>
								<td>'.$this->input->post('vendorinvite_name'.$i)
									 .form_hidden('vendorinvitefinal_id'.$count, $this->input->post('vendorinvite_id'.$i))
									 .form_hidden('vendorfinal_invite_id'.$count, $this->input->post('vendorinvite_invite_id'.$i))
									 .form_hidden('vendorname_finalinvited'.$count, $this->input->post('vendorinvite_name'.$i))
									 .form_hidden('final_invited_chkbx'.$count, 0).
								'</td>
							</tr>';
				}
			}

		}

		$table = 	'
					<input type="hidden" name="count_all_invited" id="count_all_invited" value="'.$count.'">
					<table class="table">
						<thead>
							<div class="col-md-2">
								<th>
									<input type="checkbox" name="check_all_vendor" id="check_all_vendor" onchange="check_all_invited_vendor(this.checked)">
									Select
								</th>
							</div>
							<div class="col-md-10">
								<th>Vendor</th>
							</div>
						</thead>
						<tbody>
							'.$data.'
						</tbody>
					</table>';

		echo $table;

	}

	function delete_invited_vendor()
	{
		$count = $this->input->post('count');
		$deleted_check_count = $this->input->post('deleted_check_count');

		$data = '';
		$new_count = 0;

		for($i= 1; $i <= $count; $i++)
		{
			if ($this->input->post('final_invited_chkbx'.$i) == 0)//0 para ung mga walang check ang maiiwan
			{
				$new_count++;
				$data .= '
						<tr>
							<td><input type="checkbox" name="transfered_invited'.$new_count.'" id="transfered_invited'.$new_count.'" value="'.$new_count.'" onchange="invitecheck(this.checked, '.$new_count.', \'final_invited_chkbx\')"></td>
							<td>'.$this->input->post('vendorname_finalinvited'.$i)
								 .form_hidden('vendorinvitefinal_id'.$new_count, $this->input->post('vendorinvitefinal_id'.$i))
								 .form_hidden('vendorfinal_invite_id'.$new_count, $this->input->post('vendorfinal_invite_id'.$i))
								 .form_hidden('vendorname_finalinvited'.$new_count, $this->input->post('vendorname_finalinvited'.$i))
								 .form_hidden('final_invited_chkbx'.$new_count, 0).
							'</td>
						</tr>';
			}

		}
		$table = 	'
					<input type="hidden" name="count_all_invited" id="count_all_invited" value="'.$new_count.'">
					<table class="table">
						<thead>
							<div class="col-md-2">
								<th>
									<input type="checkbox" name="check_all_vendor" id="check_all_vendor" onchange="check_all_invited_vendor(this.checked)">
									Select
								</th>
							</div>
							<div class="col-md-10">
								<th>Vendor</th>
							</div>
						</thead>
						<tbody>
							'.$data.'
						</tbody>
					</table>';

		echo $table;

	}

	function clear_invented_vendor()
	{
		$count = 0;

		$table = 	'
					<input type="hidden" name="count_all_invited" id="count_all_invited" value="'.$count.'">
					<table class="table">
						<thead>
							<div class="col-md-2">
								<th>
									<input type="checkbox" name="check_all_vendor" id="check_all_vendor" onchange="check_all_invited_vendor(this.checked)">
									Select
								</th>
							</div>
							<div class="col-md-10">
								<th>Vendor</th>
							</div>
						</thead>
						<tbody>

						</tbody>
					</table>';

		echo $table;

	}

	function create_new_vendor_list()
	{
		$total_count = $this->input->post('count');

		$table = '<div class="panel panel-primary" style="width: 500px; height: 300px; overflow-y: scroll; overflow-x: hidden;">
					<div class="row">
						<div class="col-md-10">
							<input type="hidden" id="vendor_list_total" name="vendor_list_total" value='.$total_count.'>
							<table class="table" style="width: 500px;">
								<th class="btn-primary"><center>Vendor Name</center></th>';

		if($total_count > 0)
		{
			for($i = 1; $i <= $total_count; $i++)
		    {
	            $table .= '<tr style="padding-left: 10px;overflow-y: scroll;">
	            				<td>
			            			<input type="hidden" id="vendorinvitefinal_id'.$i.'" name="vendorinvitefinal_id'.$i.'" value="'.$this->input->post('vendorinvitefinal_id'.$i).'">
			            			<input type="hidden" id="vendorfinal_invite_id'.$i.'" name="vendorfinal_invite_id'.$i.'" value="'.$this->input->post('vendorfinal_invite_id'.$i).'">
			            			<input type="hidden" id="vendorname_finalinvited'.$i.'" name="vendorname_finalinvited'.$i.'" value="'.$this->input->post('vendorname_finalinvited'.$i).'">
			            		  	<center>'.$this->input->post('vendorname_finalinvited'.$i).'</center>
	            		  		</td>
	            		  </tr>';

		    }
		}

		$table .= '			</table>
						</div>
					</div>
				</div>';
		$data['vendor_list_name'] = '';
		$data['table'] = $table;
		$data['header'] = 'Create New Vendor List';

		$this->load->view('rfqb/rfq_new_vendor_list', $data);
	}

	function save_vendor_list()
	{
		$data = $_POST;
		$data['user_id'] = $this->session->userdata('user_id');

		$rs = $this->rest_app->post('index.php/rfq_api/save_vendor_list', $data, NULL);
		//$this->rest_app->debug();

		echo json_encode($rs);
	}

	function new_attachment()
	{
		$category = $this->input->post('category');
		$vendorcategory_data 			= $this->rest_app->get('index.php/rfq_api/vendorcategory/', '', 'application/json');

		$category_array = array();
		$category_array[''] = '';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		if(!empty($category))
			$data['category'] = 'Add attachment for '.$category_array[$category];
		else
			$data['category'] = 'No Category selected';

		$data['row'] = $this->input->post('row');
		$data['col'] = $this->input->post('col');
		$data_select = array();
		$data_select[''] = '-- Select --';

		$attachment_type 		= array_merge($data_select,(array)$this->rest_app->get('index.php/rfq_api/attachment_type/', '', 'application/json'));

		$data['attachment_type'] = $attachment_type;


		$this->load->view('rfqb/rfq_new_attachment', $data);
	}

	function change_attachment_type()
	{
		$value = $this->input->post('value');

		$extension = '';
		if($value == 1) // BOM
			$extension = '.xlsx';
		elseif($value == 2) // excel
			$extension = '.xlsx';
		elseif($value == 3) // jpg
			$extension = '.jpg';
		elseif($value == 4) // pdf
			$extension = '.pdf';
		elseif($value == 5) // word
			$extension = '.docx';

		echo '<input type="file" name="upload_attachment" id="upload_attachment" value="" accept="'.$extension.'">';

	}

	function delete_selected_attachment()
	{
		$row = $this->input->post('row');
		$table = '';

		$j = 1;
		for($i=1;$i<=8;$i++)// for with values
		{
			$data['attachment_type']	= $this->input->post('attachment_type_'.$row.'_'.$i);
			$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $data, 'application/json');

			if($data['attachment_type'] == 3)
				$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$row.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$this->input->post('hidden_path_'.$row.'_'.$i).'" id="image'.$row.'_'.$i.'" value="" class="dv_attachment"></a>';
			else
				$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$row.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$row.'_'.$i.'" value="" class="dv_attachment"></a>' ;

			$path = $this->input->post('hidden_path_'.$row.'_'.$i);
			if($this->input->post('checkbox_attachment_'.$row.'_'.$i) == 0) // 0 para maiwan ung mga hindi nakacheck
			{
				if($path != "0")
				{

					$table .= '

								<div id="attachment'.$row.'_'.$j.'" style="display:inline-block" class="dv_attachment">
									'.$image.'
									<input type="hidden" name="hidden_path_'.$row.'_'.$j.'" id="hidden_path_'.$row.'_'.$j.'" value="'.$this->input->post('hidden_path_'.$row.'_'.$i).'">
									<input type="hidden" name="line_attachment_id_'.$row.'_'.$j.'" id="line_attachment_id_'.$row.'_'.$j.'" value="'.$this->input->post('line_attachment_id_'.$row.'_'.$i).'">
        	 						<div id="chkbx_line'.$row.'_'.$j.'"><input type="checkbox" id="chkbox_'.$row.'_'.$j.'" name="chkbox_'.$row.'_'.$j.'" onchange="invitecheck(this.checked, \''.$row.'_'.$j.'\', \'checkbox_attachment_\')">'.$this->input->post('attachment_desc_'.$row.'_'.$j).'</input></div>
									<input type="hidden" name="attachment_desc_'.$row.'_'.$j.'" id="attachment_desc_'.$row.'_'.$j.'" value="'.$this->input->post('attachment_desc_'.$row.'_'.$i).'">
									<input type="hidden" name="attachment_type_'.$row.'_'.$j.'" id="attachment_type_'.$row.'_'.$j.'" value="'.$this->input->post('attachment_type_'.$row.'_'.$i).'">
									<input type="hidden" value="0" id="checkbox_attachment_'.$row.'_'.$j.'" name="checkbox_attachment_'.$row.'_'.$j.'">
								</div>

							';
							$j++;

				}
			}
		}
		for($i=$j;$i<=8;$i++)// for blank values
		{
			$table .= '

						<div id="attachment'.$row.'_'.$i.'" style="display:none">
							<img class="img-responsive image_min" src="#" id="image'.$row.'_'.$i.'" value="" class="dv_attachment">
							<div id="chkbx_line'.$row.'_'.$i.'"></div>
							<input type="hidden" name="hidden_path_'.$row.'_'.$i.'" id="hidden_path_'.$row.'_'.$i.'" value="0">
							<input type="hidden" name="line_attachment_id_'.$row.'_'.$i.'" id="line_attachment_id_'.$row.'_'.$i.'" value="0">
							<input type="hidden" name="attachment_desc_'.$row.'_'.$i.'" id="attachment_desc_'.$row.'_'.$i.'" value="0">
							<input type="hidden" name="attachment_type_'.$row.'_'.$i.'" id="attachment_type_'.$row.'_'.$i.'" value="0">
							<input type="hidden" value="0" id="checkbox_attachment_'.$row.'_'.$i.'" name="checkbox_attachment_'.$row.'_'.$i.'">
						</div>

					';
		}

		$table .= '';

		echo $table;

	}

	function select_attachment()
	{
		$row = $_POST['modal_row_attachment'];
		$description = $_POST['modal_txt_description'];
		$col = $_POST['col'];
		$attachment_type = $_POST['modal_cbo_attachmenttype'];
		$upload_attachment = $this->input->post('upload_attachment');

		$data['attachment_type'] = $attachment_type;

		$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $data, 'application/json');

		$data['upload_attachment'] = '';
		$data['hidden_file_path'] = '';

		if (isset($_FILES['upload_attachment']))
		{
			$data['upload_attachment'] = $_FILES['upload_attachment']['name'];
		}

	  	if($data['upload_attachment'] != '')
        {
	        $data['file_path'] = null;

			// if(base_url() == 'http://smntp.sandmansystems.com')
				// $web_upload_proof_path = base_url().'rfx_upload_attachment/';
			// elseif(base_url() == 'http://yogi/smntp/web/')
				// $web_upload_proof_path = 'D:\\\\inetpub\\smscoreonline\\SMNTP\\web\\rfx_upload_attachment\\';
			// elseif (base_url() == 'http://sm-webserver:8080/')
     				// $web_upload_proof_path = '/data/lampstack-5.4.14-0/apache2/htdocs/rfx_upload_attachment/';
			// else
				// $web_upload_proof_path = 'F:\\\\inetpub\\smscoreonline\\SMNTP\\web\\rfx_upload_attachment\\';

			/*if (base_url() == 'http://sinon/SMNTP/web/' || base_url() == 'http://piccolo/smntp/web/')
					$web_upload_path = 'F:\\\\inetpub\\smscoreonline\\SMNTP\\web\\rfx_upload_attachment\\';
				elseif(base_url() == 'http://yogi:8080/SMNTP/web/')
					$web_upload_path = 'D:\\\\inetpub\\smscoreonline\\SMNTP\\web\\rfx_upload_attachment\\';
				else
					$web_upload_path = '/data/lampstack-5.4.14-0/apache2/htdocs/rfx_upload_attachment/';*/

			$web_upload_path = FCPATH.'rfx_upload_attachment/';

	   		if(!is_dir($web_upload_path))
		    {
		    	mkdir($web_upload_path, 0777);
		    }

        	$config['upload_path'] = $web_upload_path;
            $config['allowed_types'] = '*';
            $config['max_size'] = '10000';
            $config['file_name'] = 'upload_attachment_'.time();

		
            $this->load->library('upload', $config, 'upload_attachment');
    		$this->upload_attachment->initialize($config);

            if ( !$this->upload_attachment->do_upload('upload_attachment', FALSE))
            {
                echo '<script>alert("'.$this->upload_attachment->display_errors().'");</script>';

            }
            else
            {
                $upload_data = $this->upload_attachment->data();

				$data['file_name'] = $config['file_name'].$upload_data['file_ext'];
				$data['file_path'] = base_url().'rfx_upload_attachment/'.$data['file_name'];
				$data['hidden_file_path'] = 'rfx_upload_attachment/'.$data['file_name'];

			}
        }
        //$url = str_replace('index.php/', '', FCPATH)


        if($attachment_type == 1){
			$bom_modal_data = array();
			$bom_modal_data['bom_file_lines'] = array();
			$bom_modal_data['line_attachment_id'] = $row;

			$params = array('file_path' => $data['file_path'],
				'parse_only' => true,
				'line_attachment_id' => $bom_modal_data['line_attachment_id']
			);
			$etl_response =$this->rest_etl->post('index.php/uploader/parse_bom', $params, '');

			$bom_modal_data['bom_file_lines'] = $etl_response->csv_data;
			$bom_modal_data['status'] = $etl_response->STATUS;
			$bom_modal_data['message'] = $etl_response->MESSAGE;
			// $this->rest_etl->debug();

			echo $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);

			echo '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$row.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" class="dv_attachment" ></a>';
		} elseif($attachment_type == 3) {
        	echo '<a href="#" onclick="load_attachment(\''.$data['hidden_file_path'].'\')"><img class="img-responsive image_min" src="'.$data['file_path'].'" class="dv_attachment" ></a>';
		} else {
        	echo '<a href="#" onclick="load_attachment(\''.$data['hidden_file_path'].'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" class="dv_attachment" ></a>';
		}

        echo '<input type="hidden" value="'.$data['hidden_file_path'].'" name="hidden_path_'.$row.'_'.$col.'" id="hidden_path_'.$row.'_'.$col.'">
        	 <div id="chkbx_line'.$row.'_'.$col.'"><input type="checkbox" id="chkbox_'.$row.'_'.$col.'" name="chkbox_'.$row.'_'.$col.'" onchange="invitecheck(this.checked, \''.$row.'_'.$col.'\', \'checkbox_attachment_\')">'.$description.'</input></div>
			 <input type="hidden" name="line_attachment_id_'.$row.'_'.$col.'" id="line_attachment_id_'.$row.'_'.$col.'" value="'.$this->input->post('line_attachment_id_'.$row.'_'.$col).'">
        	 <input type="hidden" id="attachment_desc_'.$row.'_'.$col.'" name="attachment_desc_'.$row.'_'.$col.'" value="'.$description.'">
        	 <input type="hidden" id="attachment_type_'.$row.'_'.$col.'" name="attachment_type_'.$row.'_'.$col.'" value="'.$attachment_type.'">
        	 <input type="hidden" value="0" id="checkbox_attachment_'.$row.'_'.$col.'" name="checkbox_attachment_'.$row.'_'.$col.'">';
	}

	function delete_lines()
	{
		$max_lines = $this->input->post('max_lines');
		$total_checked = $this->input->post('total_checked');
		$data['business_type']			= $this->session->userdata('business_type');

		$data_display = '';
		$new_count = 0;

		$data_select[''] = "-- Select --";
		$vendorcategory_data 	= $this->rest_app->get('index.php/rfq_api/vendorcategory/', $data, 'application/json');
		$unit_data_array	 	= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');
		$line_list				= $this->rest_app->get('index.php/rfq_api/line_list/', '', 'application/json');

		$unit_array = array();
		$unit_array[''] = '-- Select --';
		foreach($unit_data_array as $row)
		{
			$unit_array[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
		}
		$data['unit_array']	 = $unit_array;

		$category_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;

		$description_array = array();
		foreach($line_list as $row)
		{
			if (!in_array($row->DESCRIPTION,$description_array))
				$description_array[$row->RFQRFB_LINE_ID] = $row->DESCRIPTION;
		}
		$data['description_array']	 = $description_array;



		$delete = 'disabled';

		for($x= 1, $r = 1; $x <= $max_lines; $x++)
		{

			if ($this->input->post('lineischecked'.$x) == 0)//0 para ung mga walang check ang maiiwan
			{
				$attachment = '';

				$display = 'style="display:none;"';

				$j = 1;
				$t = 0;
				$bom_col = 0;
				for($i=1;$i<=8;$i++)
				{
					$data['attachment_type']	= $this->input->post('attachment_type_'.$x.'_'.$i);
					$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $data, 'application/json');

					if($data['attachment_type'] == 1) {
						$bom_col = $i;
						$bom_modal_data = array();
						$bom_modal_data['bom_file_lines'] = array();
						$bom_modal_data['line_attachment_id'] = $r;

						$params = array('file_path' => base_url($this->input->post('hidden_path_'.$r.'_'.$i)),
							'parse_only' => true,
							'line_attachment_id' => $bom_modal_data['line_attachment_id']
						);
						$etl_response =$this->rest_etl->post('index.php/uploader/parse_bom', $params, '');
						// $this->rest_etl->debug();
						$bom_modal_data['bom_file_lines'] = $etl_response->csv_data;
						$bom_modal_data['status'] = $etl_response->STATUS;
						$bom_modal_data['message'] = $etl_response->MESSAGE;


						$image = $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
						$image .= '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$r.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" class="dv_attachment"></a>' ;
					} elseif($data['attachment_type'] == 3)
						$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$r.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$this->input->post('hidden_path_'.$r.'_'.$i).'" id="image'.$r.'_'.$i.'" value="" class="dv_attachment"></a>';
					else
						$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$r.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$r.'_'.$i.'" value="" class="dv_attachment"></a>' ;

					if($this->input->post('hidden_path_'.$x.'_'.$i) != "0")
					{
						$display = 'style="display:inline-block;"';

						$attachment .= 		'
												<div id="attachment'.$r.'_'.$i.'" '.$display.' class="dv_attachment">
													'.$image.'
													<input type="hidden" name="hidden_path_'.$r.'_'.$i.'" id="hidden_path_'.$r.'_'.$i.'" value="'.$this->input->post('hidden_path_'.$x.'_'.$i).'">
													<input type="hidden" name="line_attachment_id_'.$r.'_'.$i.'" id="line_attachment_id_'.$r.'_'.$i.'" value="'.$this->input->post('line_attachment_id_'.$r.'_'.$i).'">
	        	 									<div id="chkbx_line'.$r.'_'.$i.'"><input type="checkbox" id="chkbox_'.$r.'_'.$i.'" name="chkbox_'.$r.'_'.$i.'" onchange="invitecheck(this.checked, \''.$r.'_'.$i.'\', \'checkbox_attachment_\')">'.$this->input->post('attachment_desc_'.$x.'_'.$i).'</input></div>
													<input type="hidden" name="attachment_desc_'.$r.'_'.$i.'" id="attachment_desc_'.$r.'_'.$i.'" value="'.$this->input->post('attachment_desc_'.$x.'_'.$i).'">
													<input type="hidden" name="attachment_type_'.$r.'_'.$i.'" id="attachment_type_'.$r.'_'.$i.'" value="'.$this->input->post('attachment_type_'.$x.'_'.$i).'">
												 	<input type="hidden" value="0" id="checkbox_attachment_'.$r.'_'.$i.'" name="checkbox_attachment_'.$r.'_'.$i.'">
												</div>
											 ';

						$j++;
						$t++;

						if($t > 0)
							$delete = '';

					}
				}

				for($z=$j; $z<=8; $z++)
				{
					$display = 'style="display:none;"';

					$attachment .= 		'
											<div id="attachment'.$r.'_'.$z.'" '.$display.'>
												<img class="img-responsive image_min" src="#" id="image'.$r.'_'.$z.'" value="" class="dv_attachment">
												<div id="chkbx_line'.$r.'_'.$z.'"></div>
												<input type="hidden" name="line_attachment_id_'.$r.'_'.$z.'" id="line_attachment_id_'.$r.'_'.$z.'" value="0">
												<input type="hidden" name="hidden_path_'.$r.'_'.$z.'" id="hidden_path_'.$r.'_'.$z.'" value="0">
												<input type="hidden" name="attachment_desc_'.$r.'_'.$z.'" id="attachment_desc_'.$r.'_'.$z.'" value="0">
												<input type="hidden" name="attachment_type_'.$r.'_'.$z.'" id="attachment_type_'.$r.'_'.$z.'" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$r.'_'.$z.'" name="checkbox_attachment_'.$r.'_'.$z.'">
											 </div>
										 ';
				}

				$attachment .= '';
				$quantity_value = $this->input->post('quantity'.$x);
				$quantity_value = str_replace(',', '', $quantity_value);
				$quantity_value = number_format((!empty($quantity_value) ? $quantity_value : 0), 2, '.', ',');
				$new_count++;
				$data_display .= '<div class="row">
							<div class="col-md-1">
								<div class="form-group">
									<input type="checkbox" class="select_line" data-line-id="'.$r.'" id="chkbx'.$r.'" onchange="change_border(this.id);invitecheck(this.checked, '.$r.', \'lineischecked\')">
									'.form_hidden('lineischecked'.$r, 0).
								'</div>
							</div>
							<div class="col-md-11">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="line_category'.$r.'">Category</label>
											'.form_dropdown('line_category'.$r, $data['category_array'], $this->input->post('line_category'.$x), 'onchange="change_border(this.id);" id="line_category'.$r.'" class="btn toggle-dropdown btn-default form-control field-required"').
										'</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="line_description'.$r.'">Description <span style="font-weight: 0 !important;"id="line_description' . $r . '_char_num"> '. ( ((300 - strlen($this->input->post('line_description'.$r)) == 0)) ? '(You have reached the limit.)': '(' . (300 - strlen($this->input->post('line_description'.$r))) . ' characters left)').'</span></label>
											<div class="input-group">
												<input type="text" class="form-control field-required line_description_input auto_suggest" list-container="line_description_list'.$r.'" oninput="change_border(this.id);" id="line_description'.$r.'" name="line_description'.$r.'" width="100%" value="' . $this->input->post('line_description'.$r) .'">
												<div class="input-group-btn">
													<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description'.$r.'" >
														<span class="caret"></span>
													</button>
												</div>
											</div>
											'.form_dropdown('line_description_list'.$r.'', $description_array, '', ' id="line_description_list'.$r.'" class="btn toggle-dropdown btn-default form-control " style="display:none"').'
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_measuring_unit'.$r.'">Unit of Measure</label>
											'.form_dropdown('line_measuring_unit'.$r, $data['unit_array'], $this->input->post('line_measuring_unit'.$x), ' oninput="change_border(this.id);" id="line_measuring_unit'.$r.'" class="btn btn-default toggle-dropdown form-control field-required"').
										'</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_quantity">Quantity</label>
											<input type="text" id="quantity'.$r.'" name="quantity'.$r.'" onchange="change_border(this.id);" class="form-control numeric-decimal field-required" value="'.$quantity_value.'">
										</div>
									</div>
								</div>

								<div class="row">
									<a style="cursor:pointer;" onclick="specsview('.$r.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
									<span id="specs'.$x.'_text_char_num"> '. ( ((3000 - strlen($this->input->post('specs'.$x.'_text')) == 0)) ? '(You have reached the limit.)': '(' . (3000 - strlen($this->input->post('specs'.$x.'_text'))) . ' characters left)'). '</span>
								</div>
								<input type="hidden" id="specs'.$r.'" name="specs'.$r.'" value="0">

								<div id="specifications'.$r.'" class="row" style="display: none;">
									<textarea class="form-control specs_txt_area" id="specs'.$r.'_text" maxlength="3000" oninput="change_border(this.id);" name="specs'.$r.'_text" style="width: 100%;height: 100px;">'.$this->input->post('specs'.$x.'_text').'</textarea>
								</div>

								<div class="row">
									<div class="col-md-4">
										<a style="cursor:pointer;" onclick="attachmentview('.$r.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count'.$x.'"><input type="hidden" name="att_cnt'.$x.'" id="att_cnt'.$x.'" value='.$t.'>'.$t.'</span> <span class="glyphicon glyphicon-modal-window"></span></a>
									</div>

									<div class="col-md-offset-9">
										<input onclick="add_attachment('.$r.')" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment'.$r.'" name="add_attachment'.$r.'">'.nbs(3).'
										<input onclick="delete_selected_attachment('.$r.')" type="button" '.$delete.' style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment'.$r .'" name="delete_attachment'.$r .'">
									</div>
								</div>
								<input type="hidden" name="attach'.$r.'" id="attach'.$r.'" value="0">
								<div id="attachment'.$r.'" class="row" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">
									<input type="hidden" name="hidden_bom_attach'.$r.'" id="hidden_bom_attach'.$r.'" value="'.$bom_col.'">
									'.$attachment.'
								</div>
							</div>
						</div>
						<hr>'
           				;

           				$r++;
			}

		}

		if($total_checked == $max_lines)
		{
			$data_display = '';
			$attachment = '';
			for($z=1; $z<=8; $z++)
			{
				$display = 'style="display:none;"';

				$attachment .= 		'
										<div id="attachment1_'.$z.'" '.$display.'>
											<img class="img-responsive image_min" src="#" id="image1_'.$z.'" value="" class="dv_attachment">
											<div id="chkbx_line1_'.$z.'"></div>
											<input type="hidden" name="line_attachment_id_1_'.$z.'" id="line_attachment_id_1_'.$z.'" value="0">
											<input type="hidden" name="hidden_path_1_'.$z.'" id="hidden_path_1_'.$z.'" value="0">
											<input type="hidden" name="attachment_desc_1_'.$z.'" id="attachment_desc_1_'.$z.'" value="0">
											<input type="hidden" name="attachment_type_1_'.$z.'" id="attachment_type_1_'.$z.'" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_'.$z.'" name="checkbox_attachment_1_'.$z.'">
										 </div>
									 ';
			}

			$data_display .= '<div class="row">
							<div class="col-md-1">
								<div class="form-group">
									<input type="checkbox" class="select_line" data-line-id="1" id="chkbx1" onchange="change_border(this.id);invitecheck(this.checked, 1, \'lineischecked\')">
									'.form_hidden('lineischecked1', 0).
								'</div>
							</div>
							<div class="col-md-11">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="line_category1">Category</label>
											'.form_dropdown('line_category1', $data['category_array'], "", 'onchange="change_border(this.id);" id="line_category1" class="btn toggle-dropdown btn-default form-control field-required"').
										'</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="line_description1">Description <span style="font-weight: 100;" id="line_description1_char_num"></span></label>
											<div class="input-group">
												<input type="text" class="form-control line_description_input field-required auto_suggest" list-container="line_description_list1" oninput="change_border(this.id);" id="line_description1" name="line_description1" width="100%">
												<div class="input-group-btn">
													<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description1" >
														<span class="caret"></span>
													</button>
												</div>
											</div>
											'.form_dropdown('line_description_list1', $description_array, '', ' id="line_description_list1" class="btn toggle-dropdown btn-default form-control " style="display:none"').'
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_measuring_unit1">Unit of Measure</label>
											'.form_dropdown('line_measuring_unit1', $data['unit_array'], "", ' oninput="change_border(this.id);" id="line_measuring_unit1" class="btn btn-default toggle-dropdown form-control field-required"').
										'</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_quantity">Quantity</label>
											<input type="text" id="quantity1" name="quantity1" onchange="change_border(this.id);" class="form-control numeric-decimal field-required" value="">
										</div>
									</div>
								</div>

								<div class="row">
									<a style="cursor:pointer;" onclick="specsview(1);">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
									<span id="specs1_text_char_num"></span>
								</div>
								<input type="hidden" id="specs1" name="specs1" value="0">

								<div id="specifications1" class="row" style="display: none;">
									<textarea class="form-control specs_txt_area" id="specs1_text" maxlength="3000" oninput="change_border(this.id);" name="specs1_text" style="width: 100%;height: 100px;"></textarea>
								</div>

								<div class="row">
									<div class="col-md-4">
										<a style="cursor:pointer;" onclick="attachmentview(1);">Attachments for Vendors Viewing <span class="badge" id="attachment_count1"><input type="hidden" name="att_cnt1" id="att_cnt1" value="0">0</span> <span class="glyphicon glyphicon-modal-window"></span></a>
									</div>

									<div class="col-md-offset-9">
										<input onclick="add_attachment(1)" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment1" name="add_attachment1">'.nbs(3).'
										<input onclick="delete_selected_attachment(1)" type="button" '.$delete.' style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment1" name="delete_attachment1">
									</div>
								</div>
								<input type="hidden" name="attach1" id="attach1" value="0">
								<div id="attachment1" class="row" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">
									<input type="hidden" name="hidden_bom_attach1" id="hidden_bom_attach1" value="0">
									'.$attachment.'
								</div>
							</div>
						</div>
						<hr>'
           				;
		}

		echo $data_display;

	}

	function add_lines()
	{
		$data['business_type']	= $this->session->userdata('business_type');
		$data['user_id']		= $this->session->userdata('user_id');
		$max 	= $this->input->post('max_lines');
		$count 	= $this->input->post('count');
		$type 	= $this->input->post('type');

		$data_select[''] = "-- Select --";
		$vendorcategory_data 	= $this->rest_app->get('index.php/rfq_api/vendorcategory/', $data, 'application/json');
		$unit_data_array	 	= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');
		$line_list				= $this->rest_app->get('index.php/rfq_api/line_list/', '', 'application/json');


		$unit_array = array();
		$unit_array[''] = '-- Select --';
		foreach($unit_data_array as $row)
		{
			$unit_array[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
		}
		$data['unit_array']	 = $unit_array;

		$category_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;

		$description_array = array();
		foreach($line_list as $row)
		{
			if (!in_array($row->DESCRIPTION,$description_array))
				$description_array[$row->RFQRFB_LINE_ID] = $row->DESCRIPTION;
		}
		$data['description_array']	 = $description_array;

		$delete = 'disabled';
		$a = 1;
		$table = '';

		$display = 'style="display:none;"';

		for($x = 1; $x <= $max; $x++)
        {
			$attachment = '';

			$j = 1;
			$t = 0;
			$bom_col = 0;
			for($i=1;$i<=8;$i++)
			{

				if($this->input->post('hidden_path_'.$x.'_'.$i) != "0")
				{
					$new_data['attachment_type']	= $this->input->post('attachment_type_'.$x.'_'.$i);
					$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $new_data, 'application/json');

					//$this->rest_app->debug();

					if($new_data['attachment_type'] == 1) {

						$bom_col = $i;
						$bom_modal_data = array();
						$bom_modal_data['bom_file_lines'] = array();
						$bom_modal_data['line_attachment_id'] = $x;

						$params = array('file_path' => base_url($this->input->post('hidden_path_'.$x.'_'.$i)),
							'parse_only' => true,
							'line_attachment_id' => $bom_modal_data['line_attachment_id']
						);
						$etl_response =$this->rest_etl->post('index.php/uploader/parse_bom', $params, '');
						// $this->rest_etl->debug();
						$bom_modal_data['bom_file_lines'] = $etl_response->csv_data;
						$bom_modal_data['status'] = $etl_response->STATUS;
						$bom_modal_data['message'] = $etl_response->MESSAGE;


						$image = $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
						$image .= '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$x.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" class="dv_attachment"></a>' ;
					} elseif($new_data['attachment_type'] == 3) {
						$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$x.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$this->input->post('hidden_path_'.$x.'_'.$i).'" id="image'.$x.'_'.$i.'" value="" class="dv_attachment"></a>';
					} else {
						$image = '<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_path_'.$x.'_'.$i).'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" class="dv_attachment"></a>' ;
					}
					$display = 'style="display:inline-block;"';


					$attachment .= 		'
											<div id="attachment'.$x.'_'.$i.'" '.$display.' class="dv_attachment">
												'.$image.'
	        	 								<div id="chkbx_line'.$x.'_'.$i.'"><input type="checkbox" id="chkbox_'.$x.'_'.$i.'" name="chkbox_'.$x.'_'.$i.'" onchange="invitecheck(this.checked, \''.$x.'_'.$i.'\', \'checkbox_attachment_\')">'.$this->input->post('attachment_desc_'.$x.'_'.$i).'</input></div>
												<input type="hidden" name="line_attachment_id_'.$x.'_'.$i.'" id="line_attachment_id_'.$x.'_'.$i.'" value="'.$this->input->post('line_attachment_id_'.$x.'_'.$i).'">
												<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="'.$this->input->post('hidden_path_'.$x.'_'.$i).'">
												<input type="hidden" name="attachment_desc_'.$x.'_'.$i.'" id="attachment_desc_'.$x.'_'.$i.'" value="'.$this->input->post('attachment_desc_'.$x.'_'.$i).'">
												<input type="hidden" name="attachment_type_'.$x.'_'.$i.'" id="attachment_type_'.$x.'_'.$i.'" value="'.$this->input->post('attachment_type_'.$x.'_'.$i).'">
												<input type="hidden" value="0" id="checkbox_attachment_'.$x.'_'.$i.'" name="checkbox_attachment_'.$x.'_'.$i.'">
											 </div>
										';

					$j++;
					$t++;

					if($t > 0)
						$delete = '';
				}
			}

			for($z=$j; $z<=8; $z++)
			{
				$display = 'style="display:none;"';

				$attachment .= 		'
										<div id="attachment'.$x.'_'.$z.'" '.$display.'>
											<img class="img-responsive image_min" src="#" id="image'.$x.'_'.$z.'" value="" class="dv_attachment">
											<div id="chkbx_line'.$x.'_'.$z.'"></div>
											<input type="hidden" name="hidden_path_'.$x.'_'.$z.'" id="hidden_path_'.$x.'_'.$z.'" value="0">
											<input type="hidden" name="line_attachment_id_'.$x.'_'.$z.'" id="line_attachment_id_'.$x.'_'.$z.'" value="0">
											<input type="hidden" name="attachment_desc_'.$x.'_'.$z.'" id="attachment_desc_'.$x.'_'.$z.'" value="0">
											<input type="hidden" name="attachment_type_'.$x.'_'.$z.'" id="attachment_type_'.$x.'_'.$z.'" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_'.$x.'_'.$z.'" name="checkbox_attachment_'.$x.'_'.$z.'">
										 </div>
									 ';
			}

			$attachment .= '';

			$quantity_value = $this->input->post('quantity'.$x);
			$quantity_value = str_replace(',', '', $quantity_value);
			$quantity_value = number_format((!empty($quantity_value) ? $quantity_value : 0), 2, '.', ',');

           	$table .=	'<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<input type="checkbox" class="select_line" data-line-id="'.$x.'" id="chkbx'.$x.'" onchange="invitecheck(this.checked, '.$x.', \'lineischecked\')">
										'.form_hidden('lineischecked'.$x, 0).
									'</div>
								</div>
								<div class="col-md-11">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_category'.$x.'">Category</label>
												'.form_dropdown('line_category'.$x, $data['category_array'], $this->input->post('line_category'.$x), 'onchange="change_border(this.id);" id="line_category'.$x.'" class="btn toggle-dropdown btn-default form-control field-required"').
											'</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_description'.$x.'">Description <span style="font-weight: 100;" id="line_description' . $x . '_char_num"> '. ( ((300 - strlen($this->input->post('line_description'.$x)) == 0)) ? '(You have reached the limit.)': '(' . (300 - strlen($this->input->post('line_description'.$x))) . ' characters left)').'</span></label>
												<div class="input-group">
													<input type="text" class="form-control field-required line_description_input auto_suggest" list-container="line_description_list'.$x.'" oninput="change_border(this.id);" id="line_description'.$x.'" name="line_description'.$x.'" width="100%" value="' . $this->input->post('line_description'.$x) .'">
													<div class="input-group-btn">
														<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description'.$x.'" >
															<span class="caret"></span>
														</button>
													</div>
												</div>
												'.form_dropdown('line_description_list'.$x.'', $description_array, '', ' id="line_description_list'.$x.'" class="btn toggle-dropdown btn-default form-control " style="display:none"').'
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_measuring_unit'.$x.'">Unit of Measure</label>
												'.form_dropdown('line_measuring_unit'.$x, $data['unit_array'], $this->input->post('line_measuring_unit'.$x), 'onchange="change_border(this.id);" id="line_measuring_unit'.$x.'" class="btn btn-default toggle-dropdown form-control field-required"').
											'</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_quantity">Quantity</label>
												<input type="text" id="quantity'.$x.'" name="quantity'.$x.'"onchange="change_border(this.id);" class="form-control numeric-decimal field-required" value="'.$quantity_value.'">
											</div>
										</div>
									</div>

									<div class="row">
										<a style="cursor:pointer;" onclick="specsview('.$x.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a> <span id="specs'.$x.'_text_char_num"> '. ( ((3000 - strlen($this->input->post('specs'.$x.'_text')) == 0)) ? '(You have reached the limit.)': '(' . (3000 - strlen($this->input->post('specs'.$x.'_text'))) . ' characters left)').'</span>
									</div>
									<input type="hidden" id="specs'.$x.'" name="specs'.$x.'" value="0">

									<div id="specifications'.$x.'" class="row" style="display: none;">
										<textarea class="form-control specs_txt_area" id="specs'.$x.'_text" name="specs'.$x.'_text" maxlength="3000" oninput="change_border(this.id);" style="width: 100%;height: 100px;">'.$this->input->post('specs'.$x.'_text').'</textarea>
									</div>

									<div class="row">
										<div class="col-md-4">
											<a style="cursor:pointer;" onclick="attachmentview('.$x.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count'.$x.'"><input type="hidden" name="att_cnt'.$x.'" id="att_cnt'.$x.'" value='.$t.'>'.$t.'</span> <span class="glyphicon glyphicon-modal-window"></span></a>
										</div>

										<div class="col-md-offset-9">
											<input onclick="add_attachment('.$x.')" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment'.$x.'" name="add_attachment'.$x.'">'.nbs(3).'
											<input onclick="delete_selected_attachment('.$x.')" '.$delete.' type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment'.$x .'" name="delete_attachment'.$x .'">
										</div>
									</div>
									<input type="hidden" name="attach'.$x.'" id="attach'.$x.'" value="0">
									<div id="attachment'.$x.'" class="row" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">
										<input type="hidden" name="hidden_bom_attach'.$x.'" id="hidden_bom_attach'.$x.'" value="'.$bom_col.'">
										'.$attachment.'
									</div>
								</div>
							</div>
							<hr>';

			$a++;
        }

        for($n = $a; $n <= $max; $n++)
        {
        	$table .=	'<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<input type="checkbox" class="select_line" data-line-id="'.$x.'" id="chkbx'.$x.'" onchange="invitecheck(this.checked, '.$n.', \'lineischecked\')">
										'.form_hidden('lineischecked'.$n, 0).
									'</div>
								</div>
								<div class="col-md-11">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_category'.$n.'">Category</label>
												'.form_dropdown('line_category'.$n, $data['category_array'], '', 'onchange="change_border(this.id);" id="line_category'.$n.'" class="btn toggle-dropdown btn-default form-control field-required"').
											'</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_description'.$n.'">Description <span style="font-weight: 100;" id="line_description' . $n . '_char_num"> '. ( ((300 - strlen($this->input->post('line_description'.$n)) == 0)) ? '(You have reached the limit.)': '(' . (300 - strlen($this->input->post('line_description'.$n))) . ' characters left)').'</span></label>
												<div class="input-group">
													<input type="text" class="form-control field-required auto_suggest line_description_input" list-container="line_description_list'.$n.'" oninput="change_border(this.id);" id="line_description'.$n.'" name="line_description'.$n.'" width="100%" value="' . $this->input->post('line_description'.$n) .'">
													<div class="input-group-btn">
														<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description'.$n.'" >
															<span class="caret"></span>
														</button>
													</div>
												</div>
												'.form_dropdown('line_description_list'.$n.'', $description_array, '', ' id="line_description_list'.$n.'" class="btn toggle-dropdown btn-default form-control " style="display:none"').'

											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_measuring_unit'.$n.'">Unit of Measure</label>
												'.form_dropdown('line_measuring_unit'.$n, $data['unit_array'], '', 'onchange="change_border(this.id);" id="line_measuring_unit'.$n.'" class="btn btn-default toggle-dropdown form-control field-required"').
											'</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_quantity">Quantity</label>
												<input type="text" id="quantity'.$n.'" oninput="change_border(this.id);" name="quantity'.$n.'" class="form-control numeric-decimal field-required" value="">
											</div>
										</div>
									</div>

									<div class="row">
										<a style="cursor:pointer;" onclick="specsview('.$n.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
										<span id="specs'.$n.'_text_char_num"> '. ( ((3000 - strlen($this->input->post('specs'.$n.'_text')) == 0)) ? '(You have reached the limit.)': '(' . (3000 - strlen($this->input->post('specs'.$n.'_text'))) . ' characters left)'). '</span>
									</div>
									<input type="hidden" id="specs'.$n.'" name="specs'.$n.'" value="0">

									<div id="specifications'.$n.'" class="row" style="display: none;">
										<textarea class="form-control specs_txt_area" maxlength="3000" oninput="change_border(this.id);" id="specs'.$n.'_text" name="specs'.$n.'_text" style="width: 100%;height: 100px;"></textarea>
									</div>

									<div class="row">
										<div class="col-md-4">
											<a style="cursor:pointer;" onclick="attachmentview('.$n.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count'.$n.'"><input type="hidden" name="att_cnt'.$n.'" id="att_cnt'.$n.'" value=0>0</span> <span class="glyphicon glyphicon-modal-window"></span></a>
										</div>

										<div class="col-md-offset-9">
											<input onclick="add_attachment('.$n.')" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment'.$n.'" name="add_attachment'.$n.'">'.nbs(3).'
											<input onclick="delete_selected_attachment('.$n.')" disabled type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment'.$n .'" name="delete_attachment'.$n .'">
										</div>
									</div>
									<input type="hidden" name="attach'.$n.'" id="attach'.$n.'" value="0">
									<div id="attachment'.$n.'" class="row" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">

											<div id="attachment'.$n.'_1" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_1" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_1"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_1" id="line_attachment_id_'.$n.'_1" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_1" id="hidden_path_'.$n.'_1" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_1" id="attachment_desc_'.$n.'_1" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_1" id="attachment_type_'.$n.'_1" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_1" name="checkbox_attachment_'.$n.'_1">
											</div>
											<div id="attachment'.$n.'_2" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_2" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_2"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_2" id="line_attachment_id_'.$n.'_2" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_2" id="hidden_path_'.$n.'_2" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_2" id="attachment_desc_'.$n.'_2" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_2" id="attachment_type_'.$n.'_2" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_2" name="checkbox_attachment_'.$n.'_2">
											</div>
											<div id="attachment'.$n.'_3" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_3" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_3"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_3" id="line_attachment_id_'.$n.'_3" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_3" id="hidden_path_'.$n.'_3" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_3" id="attachment_desc_'.$n.'_3" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_3" id="attachment_type_'.$n.'_3" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_3" name="checkbox_attachment_'.$n.'_3">
											</div>
											<div id="attachment'.$n.'_4" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_4" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_4"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_4" id="line_attachment_id_'.$n.'_4" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_4" id="hidden_path_'.$n.'_4" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_4" id="attachment_desc_'.$n.'_4" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_4" id="attachment_type_'.$n.'_4" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_4" name="checkbox_attachment_'.$n.'_4">
											</div>
											<div id="attachment'.$n.'_5" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_5" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_5"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_5" id="line_attachment_id_'.$n.'_5" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_5" id="hidden_path_'.$n.'_5" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_5" id="attachment_desc_'.$n.'_5" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_5" id="attachment_type_'.$n.'_5" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_5" name="checkbox_attachment_'.$n.'_5">
											</div>
											<div id="attachment'.$n.'_6" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_6" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_6"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_6" id="line_attachment_id_'.$n.'_6" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_6" id="hidden_path_'.$n.'_6" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_6" id="attachment_desc_'.$n.'_6" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_6" id="attachment_type_'.$n.'_6" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_6" name="checkbox_attachment_'.$n.'_6">
											</div>
											<div id="attachment'.$n.'_7" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_7" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_7"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_7" id="line_attachment_id_'.$n.'_7" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_7" id="hidden_path_'.$n.'_7" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_7" id="attachment_desc_'.$n.'_7" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_7" id="attachment_type_'.$n.'_7" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_7" name="checkbox_attachment_'.$n.'_7">
											</div>
											<div id="attachment'.$n.'_8" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_8" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_8"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_8" id="line_attachment_id_'.$n.'_8" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_8" id="hidden_path_'.$n.'_8" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_8" id="attachment_desc_'.$n.'_8" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_8" id="attachment_type_'.$n.'_8" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_8" name="checkbox_attachment_'.$n.'_8">
											</div>
									</div>
								</div>
							</div>
							<hr>';
        }

        echo $table;

	}

	function add_lines_generation()
	{
		$max 	= $this->input->post('max_lines');
		$data['business_type']			= $this->session->userdata('business_type');

		$data_select[''] = "-- Select --";
		$vendorcategory_data 	= $this->rest_app->get('index.php/rfq_api/vendorcategory/', $data, 'application/json');
		$unit_data_array	 	= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');
		$line_list				= $this->rest_app->get('index.php/rfq_api/line_list/', '', 'application/json');


		$unit_array = array();
		$unit_array[''] = '-- Select --';
		foreach($unit_data_array as $row)
		{
			$unit_array[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
		}
		$data['unit_array']	 = $unit_array;

		$category_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;

		$description_array = array();
		foreach($line_list as $row)
		{
			if (!in_array($row->DESCRIPTION,$description_array))
				$description_array[$row->RFQRFB_LINE_ID] = $row->DESCRIPTION;
		}
		$data['description_array']	 = $description_array;

		$delete = 'disabled';
		$a = 1;
		$table = '';

		$display = 'style="display:none;"';

        for($n = $a; $n <= $max; $n++)
        {
        	$table .=	'<div class="row">
								<div class="col-md-1">
									<div class="form-group">
										<input type="checkbox" class="select_line" data-line-id="'.$n.'" id="chkbx'.$n.'" onchange="invitecheck(this.checked, '.$n.', \'lineischecked\')">
										'.form_hidden('lineischecked'.$n, 0).
									'</div>
								</div>
								<div class="col-md-11">
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_category'.$n.'">Category</label>
												'.form_dropdown('line_category'.$n, $data['category_array'], '', 'onchange="change_border(this.id);" id="line_category'.$n.'" class="btn toggle-dropdown btn-default form-control field-required"').
											'</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label for="line_description'.$n.'">Description <span style="font-weight: 100;" id="line_description' . $n . '_char_num"> '. ( ((300 - strlen($this->input->post('line_description'.$n)) == 0)) ? '(You have reached the limit.)': '(' . (300 - strlen($this->input->post('line_description'.$n))) . ' characters left)').'</span></label>
												<div class="input-group">
													<input type="text" class="form-control field-required line_description_input auto_suggest" list-container="line_description_list'.$n.'" oninput="change_border(this.id);" id="line_description'.$n.'" name="line_description'.$n.'" width="100%" value="' . $this->input->post('line_description'.$n) .'">
													<div class="input-group-btn">
														<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description'.$n.'" >
															<span class="caret"></span>
														</button>
													</div>
												</div>
												'.form_dropdown('line_description_list'.$n.'', $description_array, '', ' id="line_description_list'.$n.'" class="btn toggle-dropdown btn-default form-control " style="display:none"').'

											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_measuring_unit'.$n.'">Unit of Measure</label>
												'.form_dropdown('line_measuring_unit'.$n, $data['unit_array'], '', 'onchange="change_border(this.id);" id="line_measuring_unit'.$n.'" class="btn btn-default toggle-dropdown form-control field-required"').
											'</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label for="line_quantity">Quantity</label>
												<input type="text" id="quantity'.$n.'" oninput="change_border(this.id);" name="quantity'.$n.'" class="form-control numeric-decimal field-required" value="">
											</div>
										</div>
									</div>

									<div class="row">
										<a style="cursor:pointer;" onclick="specsview('.$n.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
									</div>
									<input type="hidden" id="specs'.$n.'" name="specs'.$n.'" value="0">

									<div id="specifications'.$n.'" class="row" style="display: none;">
										<textarea class="form-control specs_txt_area" maxlength="3000" oninput="change_border(this.id);" id="specs'.$n.'_text" name="specs'.$n.'_text" style="width: 100%;height: 100px;"></textarea>
									</div>

									<div class="row">
										<div class="col-md-4">
											<a style="cursor:pointer;" onclick="attachmentview('.$n.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count'.$n.'"><input type="hidden" name="att_cnt'.$n.'" id="att_cnt'.$n.'" value=0>0</span> <span class="glyphicon glyphicon-modal-window"></span></a>
										</div>

										<div class="col-md-offset-9">
											<input onclick="add_attachment('.$n.')" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment'.$n.'" name="add_attachment'.$n.'">'.nbs(3).'
											<input onclick="delete_selected_attachment('.$n.')" disabled type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment'.$n .'" name="delete_attachment'.$n .'">
										</div>
									</div>
									<input type="hidden" name="attach'.$n.'" id="attach'.$n.'" value="0">
									<div id="attachment'.$n.'" class="row" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">
										<input type="hidden" name="hidden_bom_attach'.$n.'" id="hidden_bom_attach'.$n.'" value="0">
											<div id="attachment'.$n.'_1" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_1" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_1"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_1" id="line_attachment_id_'.$n.'_1" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_1" id="hidden_path_'.$n.'_1" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_1" id="attachment_desc_'.$n.'_1" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_1" id="attachment_type_'.$n.'_1" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_1" name="checkbox_attachment_'.$n.'_1">
											</div>
											<div id="attachment'.$n.'_2" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_2" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_2"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_2" id="line_attachment_id_'.$n.'_2" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_2" id="hidden_path_'.$n.'_2" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_2" id="attachment_desc_'.$n.'_2" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_2" id="attachment_type_'.$n.'_2" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_2" name="checkbox_attachment_'.$n.'_2">
											</div>
											<div id="attachment'.$n.'_3" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_3" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_3"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_3" id="line_attachment_id_'.$n.'_3" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_3" id="hidden_path_'.$n.'_3" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_3" id="attachment_desc_'.$n.'_3" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_3" id="attachment_type_'.$n.'_3" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_3" name="checkbox_attachment_'.$n.'_3">
											</div>
											<div id="attachment'.$n.'_4" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_4" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_4"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_4" id="line_attachment_id_'.$n.'_4" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_4" id="hidden_path_'.$n.'_4" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_4" id="attachment_desc_'.$n.'_4" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_4" id="attachment_type_'.$n.'_4" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_4" name="checkbox_attachment_'.$n.'_4">
											</div>
											<div id="attachment'.$n.'_5" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_5" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_5"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_5" id="line_attachment_id_'.$n.'_5" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_5" id="hidden_path_'.$n.'_5" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_5" id="attachment_desc_'.$n.'_5" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_5" id="attachment_type_'.$n.'_5" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_5" name="checkbox_attachment_'.$n.'_5">
											</div>
											<div id="attachment'.$n.'_6" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_6" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_6"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_6" id="line_attachment_id_'.$n.'_6" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_6" id="hidden_path_'.$n.'_6" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_6" id="attachment_desc_'.$n.'_6" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_6" id="attachment_type_'.$n.'_6" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_6" name="checkbox_attachment_'.$n.'_6">
											</div>
											<div id="attachment'.$n.'_7" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_7" value="" class="dv_attachment">
												<div id="chkbx_line'.$n.'_7"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_7" id="line_attachment_id_'.$n.'_7" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_7" id="hidden_path_'.$n.'_7" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_7" id="attachment_desc_'.$n.'_7" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_7" id="attachment_type_'.$n.'_7" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_7" name="checkbox_attachment_'.$n.'_7">
											</div>
											<div id="attachment'.$n.'_8" style="display:none">
												<img class="img-responsive image_min" src="#" id="image'.$n.'_8" value="" height="100px" width="100px">
												<div id="chkbx_line'.$n.'_8"></div>
												<input type="hidden" name="line_attachment_id_'.$n.'_8" id="line_attachment_id_'.$n.'_8" value="0">
												<input type="hidden" name="hidden_path_'.$n.'_8" id="hidden_path_'.$n.'_8" value="0">
												<input type="hidden" name="attachment_desc_'.$n.'_8" id="attachment_desc_'.$n.'_8" value="0">
												<input type="hidden" name="attachment_type_'.$n.'_8" id="attachment_type_'.$n.'_8" value="0">
												<input type="hidden" value="0" id="checkbox_attachment_'.$n.'_8" name="checkbox_attachment_'.$n.'_8">
											</div>
									</div>
								</div>
							</div>
							<hr>';
        }

        echo $table;

	}

	function sm_view()
	{
		$data_select[''] = "-- Select --";
		$data['purpose_data'] 			= array_merge($data_select,(array)$this->rest_app->get('index.php/rfq_api/purpose/', '', 'application/json'));
		$data['requestor_data'] 		= array_merge($data_select,(array)$this->rest_app->get('index.php/rfq_api/requestor/', '', 'application/json'));


		$this->load->view('rfqb/rfq_sm_view', $data);
	}

	function viewer_view()
	{
		//this is vendor view
		$data['search_no']			= 	'';
		$data['title_search']		= 	'';
		$data['date_created']		= 	'';
		$data['status']				= 	'';
		$data['time_left']			= 	'';

		$table						= (array)$this->rest_app->get('index.php/rfq_api/view_vendor/', $data, 'application/json');

		$status_dropdown = array();
		$status_dropdown[''] 		= 'All';
		$status_dropdown[1] 		= 'Open';
		$data['status_dropdown'] 	= $status_dropdown;

		$table_new = '';
		foreach($table['result'] as $row)
		{
			$table_new .= '<tr>';
			$table_new .= '<td>'.$row->RFQRFB_ID.'</td>';
			$table_new .= '<td>'.$row->TITLE.'</td>';
			$table_new .= '<td>0</td>';//leave it blank muna
			$table_new .= '<td>Open</td>';//leave it blank muna
			$table_new .= '<td>'.$row->DATE_CREATED.'</td>';//leave it blank muna
			$table_new .= '<td>&nbsp;</td>';//leave it blank muna
			$table_new .= '<tr>';

		}

		$data['table_data']	= '<table class="table" style="width: 100%;">
										<thead>
											<th style="width: 5%">No.</th>
											<th style="width: 20%">Title<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Time Left<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Status<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 10%">Date Created<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Action</th>
										</thead>
										<tbody>
											'.$table_new.'
										</tbody>
									</table>';
		$this->load->view('rfqb/rfq_viewer_view', $data);
	}


	function award_approval_view()
	{
		$this->load->view('rfqb/rfq_award_approval_view');
	}

	function invitation_view($id = 0, $inviteid = 0)
	{
		$data['user_position_id']			= $this->session->userdata('position_id');
		$data['rfx_id']						= $id;
		$data['id'] 						= $id;
		$data['invite_id'] 					= $inviteid;
		$data['title'] 						= '';
		$data['type'] 						= '';
		$data['currency'] 					= '';
		$data['delivery_date'] 				= '';
		$data['submission_deadline'] 		= '';
		$data['date_created'] 				= '';
		$data['requestor_id'] 				= '';
		$data['purpose_id']					= '';
		$data['other_purpose']				= '';
		$data['reason_id']					= '';
		$data['other_reason']				= '';
		$data['internal_note']				= '';
		$data['created_by']					= '';
		$data['lines']						= '';
		$data['rfqrfb_type_name']			= '';
		$data['position_id']				= 8;
		$count_blank 						= 0;
		$data['status_id']					= 21;
		$data['bom_file_modals']			= '';

		$data['is_open'] = '';

		$all_data 					= (array)$this->rest_app->get('index.php/rfq_api/load_approval_data/', $data);

		$result 					= (array)$this->rest_app->get('index.php/rfq_api/invitation_status/', $data);

		//if ($result['num_rows'] > 0)
			//$data['is_open'] = ' disabled';

		//$this->rest_app->debug();

		$vendorcategory_data 		= $this->rest_app->get('index.php/rfq_api/vendorcategory/', '', 'application/json');
		$currency_data_array		= $this->rest_app->get('index.php/rfq_api/currency/', '', 'application/json');
		$requestor_data_array	 	= $this->rest_app->get('index.php/rfq_api/requestor/', '', 'application/json');
		$purpose_data_array 		= $this->rest_app->get('index.php/rfq_api/purpose/', '', 'application/json');
		$reason_data_array	 		= $this->rest_app->get('index.php/rfq_api/reason/', '', 'application/json');
		$unit_data_array	 		= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');

		$category_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;

		$currency_data = array();
		$currency_data[''] = '-- Select --';
		foreach($currency_data_array as $row)
		{
			$currency_data[$row->CURRENCY_ID] = $row->ABBREVIATION;
		}
		$data['currency_data']	 = $currency_data;

		$requestor_data = array();
		$requestor_data[''] = '-- Select --';
		foreach($requestor_data_array as $row)
		{
			$requestor_data[$row->REQUESTOR_ID] = $row->REQUESTOR;
		}
		$data['requestor_data']	 = $requestor_data;

		$purpose_data = array();
		$purpose_data[''] = '-- Select --';
		foreach($purpose_data_array as $row)
		{
			$purpose_data[$row->PURPOSE_ID] = $row->PURPOSE;
		}
		$data['purpose_data']	 = $purpose_data;

		$reason_data = array();
		$reason_data[''] = '-- Select --';
		foreach($reason_data_array as $row)
		{
			$reason_data[$row->REASON_ID] = $row->REASON;
		}
		$data['reason_data']	 = $reason_data;

		$unit_array = array();
		$unit_array[''] = '-- Select --';
		foreach($unit_data_array as $row)
		{
			$unit_array[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
		}
		$data['unit_array']	 = $unit_array;


		if ($all_data['num_rows'] > 0)
		{
			$count_blank = $all_data['attachment_count'] + 1;
			//uppper part
			$data['title'] 					= $all_data['result'][0]->TITLE;
			$data['type'] 					= $all_data['result'][0]->RFQRFB_TYPE;
			$data['currency'] 				= $all_data['result'][0]->CURRENCY_ID;
			$data['delivery_date']			= date('Y-m-d', strtotime($all_data['result'][0]->DELIVERY_DATE));
			$data['submission_deadline']	= date('Y-m-d', strtotime($all_data['result'][0]->SUBMISSION_DEADLINE));
			$data['date_created']			= date('Y-m-d', strtotime($all_data['result'][0]->DATE_CREATED));
			$data['requestor_id']			= $all_data['result'][0]->REQUESTOR_ID;
			$data['purpose_id']				= $all_data['result'][0]->PURPOSE_ID;
			$data['other_purpose']			= $all_data['result'][0]->OTHER_PURPOSE;
			$data['reason_id']				= $all_data['result'][0]->REASON_ID;
			$data['other_reason']			= $all_data['result'][0]->OTHER_REASON;
			$data['internal_note']			= $all_data['result'][0]->INTERNAL_NOTE;
			$data['created_by']				= $all_data['result'][0]->CREATED_BY;
			$data['position_id']			= $all_data['result'][0]->POSITION_ID;
			$data['status_id']				= $all_data['result'][0]->STATUS_ID;
			$data['rfqrfb_type_name']		= $all_data['result'][0]->RFQRFB_TYPE_NAME;

			$x = 1;

			//lines

			$new_data = array();


			$new_data['lineid'] = 0;
			$new_data['lineid2'] = 0;
			$new_data['id'] = $id;

			$path = 0;
			$path2 = 0;
			foreach($all_data['result'] as $row)
			{

				/*if($data['user_position_id'] != $row->POSITION_ID)
					$data['is_open'] = ' disabled';*/

				$new_data['lineid2'] = $row->RFQRFB_LINE_ID;
				//echo $row->RFQRFB_LINE_ID;;
				if($new_data['lineid2'] != $new_data['lineid'])
				{
					$attachment_line	= (array)$this->rest_app->get('index.php/rfq_api/load_attachment_data/', $new_data);

					// $this->rest_app->debug();
					$attachment = '';
					$i = 1;
					$r = 0;
					foreach($attachment_line as $row2)
					{
						$new_data['attachment_type']	= $row2->ATTACHMENT_TYPE;
						$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $new_data, 'application/json');

						$bom_modal_data = array();
						$bom_modal_data['bom_file_lines'] = array();
						if ($row2->ATTACHMENT_TYPE == 1) { //BOM
							$bom_modal_data['line_attachment_id'] = $row2->LINE_ATTACHMENT_ID;
							$bom_modal_data['bom_file_lines'] = $this->get_bom_file_lines($row2->LINE_ATTACHMENT_ID, $this->session->userdata('vendor_id'));
							$bom_modal_data['user_type'] = $this->session->userdata('user_type');
							$data['bom_file_modals'] .= $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
						}

						//$this->rest_app->debug();
						if($new_data['attachment_type'] == 1)
							$image = '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$row2->LINE_ATTACHMENT_ID.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>' ;
						elseif($new_data['attachment_type'] == 3)
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$row2->FILE_PATH.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>';
						else
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>' ;


						$display = 'style="display:inline-block;"';

						$attachment .= 		'
												<div id="attachment'.$x.'_'.$i.'" '.$display.'  class="dv_attachment">
													'.$image.'
													<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="'.base_url().$row2->FILE_PATH.'">
													<input type="hidden" name="attachment_desc_'.$x.'_'.$i.'" id="attachment_desc_'.$x.'_'.$i.'" value="'.$row2->DESCRIPTION.'">
													<input type="hidden" name="attachment_type_'.$x.'_'.$i.'" id="attachment_type_'.$x.'_'.$i.'" value="'.$row2->ATTACHMENT_TYPE.'">
												 	<center>'.$row2->DESCRIPTION.'</center>
												 </div>
											';
						$r++;

					}



					for($z=$count_blank; $z<=8; $z++)
					{
						$display = 'style="display:none;"';

						$attachment .= 		'
												<div id="attachment'.$x.'_'.$i.'" '.$display.'  class="dv_attachment">
													<img class="img-responsive image_min" src="#" value="" height="100px" width="100px">
													<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="0">
													<input type="hidden" name="attachment_desc_'.$x.'_'.$i.'" id="attachment_desc_'.$x.'_'.$i.'" value="0">
													<input type="hidden" name="attachment_type_'.$x.'_'.$i.'" id="attachment_type_'.$x.'_'.$i.'" value="0">
												 </div>
											';
					}

					$attachment.= '';

					$data['lines'] .=
										'<div class="row">
									<div class="col-md-1">
										<div class="form-group">
										</div>
									</div>
									<div class="col-md-11">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label for="line_category'.$x.'">Category</label>
													'.form_dropdown('line_category'.$x, $data['category_array'], $row->CATEGORY_ID, 'id="line_category'.$x.'"class="btn toggle-dropdown btn-default form-control field-required"').
												'</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label for="line_description'.$x.'">Description <span style="font-weight: 100;" id="line_description' . $x . '_char_num"> '. ( ((300 - strlen($this->input->post('line_description'.$x)) == 0)) ? '(You have reached the limit.)': '(' . (300 - strlen($this->input->post('line_description'.$x))) . ' characters left)').'</span></label>
													<textarea class="form-control" id="line_description'.$x.'" value="'.$row->DESCRIPTION.'" name="line_description'.$x.'" style="width: 100%;">'.$row->DESCRIPTION.'</textarea>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="line_measuring_unit'.$x.'">Unit of Measure</label>
													'.form_dropdown('line_measuring_unit'.$x, $data['unit_array'], $row->UNIT_OF_MEASURE, 'id="line_measuring_unit'.$x.'" class="btn btn-default toggle-dropdown form-control field-required"').
												'</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<label for="line_quantity">Quantity</label>
													<input type="text" id="quantity'.$x.'" name="quantity'.$x.'" class="form-control field-required" value="'.number_format((isset($row->QUANTITY) ? $row->QUANTITY : 0), 2, '.', ',').'">
												</div>
											</div>
										</div>

										<div class="row">
											<a style="cursor:pointer;" onclick="specsview('.$x.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
										</div>
										<input type="hidden" id="specs'.$x.'" name="specs'.$x.'" value="1">

										<div id="specifications'.$x.'" class="row" style="display: inline;">
											<textarea class="form-control specs_txt_area" value="'.$row->SPECIFICATION.'" maxlength="3000" id="specs'.$x.'_text" name="specs'.$x.'_text" style="width: 100%;height: 100px;">'.$row->SPECIFICATION.'</textarea>
										</div>

										<div class="row">
											<div class="col-md-4">
												<a style="cursor:pointer;" onclick="attachmentview('.$x.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count">'.$r.'</span> <span class="glyphicon glyphicon-modal-window"></span></a>
											</div>

											<div class="col-md-offset-9" style="display: inline;">
												<input onclick="add_attachment('.$x.')" type="button" style="display: inline;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment'.$x.'" name="add_attachment'.$x.'">'.nbs(3).'
												<input type="button" style="display: inline;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment'.$x .'" name="delete_attachment'.$x .'">
											</div>
										</div>
										<input type="hidden" name="attach'.$x.'" id="attach'.$x.'" value="0">
										<div id="attachment'.$x.'" class="row" style="white-space: nowrap; display: inline; overflow-x: scroll; height: 200px; width: 95%">
											'.$attachment.'
										</div>
									</div>
								</div>
								<hr>';

					$x++;
					$new_data['lineid'] = $row->RFQRFB_LINE_ID;
				}
				$attachment = '';
			}

		}
		$this->load->view('rfqb/rfq_invitation_view', $data);
	}

	function get_bom_file_lines($line_attachment_id, $vendor_id){
		$line = $this->rest_app->get('index.php/rfq_api/vendor_line_bom_file/', array('line_attachment_id'=>$line_attachment_id, 'vendor_id'=>$vendor_id), 'application/json');
		// var_dump($line);
		return $line;
	}

	function participate_decline_invitation()
	{
		$data['vendor_id'] 			= $this->session->userdata('vendor_id');
		$data['action'] 			= $this->input->post('type');
		$data['rfx_id'] 			= $this->input->post('rfx_id');
		$data['invite_id'] 			= $this->input->post('invite_id');
		$data['current_status_id']	= 101; //$this->input->post('current_status_id');
		$data['position_id']		= 10; //$this->input->post('position_id');
		$data['status_type'] 		= 3;

		$next_status_data		 	= (array)$this->rest_app->get('index.php/rfq_api/next_status/', $data, 'application/json');
		//$this->rest_app->debug();

		if($data['action'] == 1)
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->NEXT_POSITION_ID; // 102=published or approved
			$data['status']				= $next_status_data['result'][0]->APPROVE_STATUS_ID; // 102=published or approved
		}
		else
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->CURRENT_POSITION_ID; // 103=published or approved
			$data['status']				= $next_status_data['result'][0]->REJECT_STATUS_ID; // 103=published or approved
		}

		//$data['reason_data'] 		= $this->rest_app->post('index.php/rfq_api/invitation_response/', $data);
		$table 						= $this->rest_app->get('index.php/rfq_api/invitation_response/', $data, 'application/json');
		//$this->rest_app->debug();

		if($table === 'success')
			echo 'success';
		else
			echo 'failed';

	}

	function search_rfq_vendor()
	{
		//this is vendor view
		$data['search_no']			= 	$this->input->post('search_no');
		$data['title_search']		= 	$this->input->post('search_title');
		$data['date_created']		= 	$this->input->post('date_created');
		$data['status']				= 	$this->input->post('status_filter');
		$data['time_left']			= 	$this->input->post('timeleft_filter');

		$table						= (array)$this->rest_app->get('index.php/rfq_api/view_vendor/', $data, 'application/json');

		$status_dropdown = array();
		$status_dropdown[''] 		= 'All';
		$status_dropdown[1] 		= 'Open';
		$data['status_dropdown'] 	= $status_dropdown;

		$table_new = '';
		if ($table['num_rows'] > 0)
		{
			foreach($table['result'] as $row)
			{
				$table_new .= '<tr>';
				$table_new .= '<td>'.$row->RFQRFB_ID.'</td>';
				$table_new .= '<td>'.$row->TITLE.'</td>';
				$table_new .= '<td>0</td>';//leave it blank muna
				$table_new .= '<td>Open</td>';//leave it blank muna
				$table_new .= '<td>'.$row->DATE_CREATED.'</td>';//leave it blank muna
				$table_new .= '<td>&nbsp;</td>';//leave it blank muna
				$table_new .= '<tr>';

			}
		}

		$data['table_data']	= '<table class="table" style="width: 100%;">
										<thead>
											<th style="width: 5%">No.</th>
											<th style="width: 20%">Title<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Time Left<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Status<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 10%">Date Created<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
											<th style="width: 15%">Action</th>
										</thead>
										<tbody>
											'.$table_new.'
										</tbody>
									</table>';


		echo $data['table_data'];

	}

	function get_descriptions(){
		$query = $this->input->get("term");

		$data = $this->rest_app->get('index.php/rfq_api/description_list/', array('query'=>$query), 'application/json');
		$list = array();
		foreach($data as $datum) {
			array_push($list,array('label'=>$datum->DESCRIPTION));
		}

		echo json_encode($list,JSON_PRETTY_PRINT);
	}

}
?>
