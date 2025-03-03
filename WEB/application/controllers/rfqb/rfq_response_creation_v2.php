<?php
Class Rfq_Response_Creation_V2 extends CI_Controller{

	function index($id = 0, $invite_id)
	{
		$data_select[''] 					= "-- Select --";
		$data['user_id'] 			= $this->session->userdata('user_id');
		$data['id'] 						= $id;
		$data['rfx_id']						= $id;

		$count_messages				= $this->rest_app->get('index.php/rfq_api/get_messages/', $data, 'application/json');
		//$this->rest_app->debug();
		$data['invite_id']					= $invite_id;
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
		$data['disabled_btn']				= '';
		$quote_amount						= '';
		$lead_time_default					= '';
		$counter_offer						= '';
		$attachment_path					= '';
		$image_quote						= '';
		$no_quote_properties				= '';
		$no_quote_delivery					= '';
		$quote_count						= 1;
		$data['shortlisted']				= 1;
		$data['position_id']				= 8;
		$data['total_quotes']				= 0;
		$count_blank 						= 0;
		$no_quote_count						= 0;
		$data['status_id']					= 21;

		$status 					= (array)$this->rest_app->get('index.php/rfq_api/get_rfq_invitation_status/', $data, 'application/json');
		$inserted 					= $this->rest_app->get('index.php/rfq_api/get_rfq_response_quote/', $data, 'application/json');

		if($count_messages->count > 0)
			$data['message_link'] = 'messaging/mail/index/'.$id.'/rfqrfb';
		else
			$data['message_link'] = 'messaging/mail/index/';

		$data['message_count'] = $count_messages->count;
		// $this->rest_app->debug();

		foreach($inserted as $row)
		{
			$version = $row->VERSION;
		}

 		if($version == 4 && $status['status'] == 106)
			$data['disabled_btn'] = 'disabled';

		$status 					= (array)$this->rest_app->get('index.php/rfq_api/get_rfq_invitation_status/', $data, 'application/json');
		$inserted 					= $this->rest_app->get('index.php/rfq_api/get_rfq_response_quote/', $data, 'application/json');

		// $this->rest_app->debug();

		foreach($inserted as $row)
		{
			$version = $row->VERSION;
		}


		if($version == 1 && $status['status'] == 106)
			$data['version'] = 2;
		else if($version == 2 && $status['status'] == 161)
			$data['version'] = 2;
		else if($version == 2 && $status['status'] == 106)
			$data['version'] = 3;
		else if($version == 3 && $status['status'] == 161)
			$data['version'] = 3;
		else if($version == 3 && $status['status'] == 106)
			$data['version'] = 4;
		else if($version == 4 && $status['status'] == 161)
			$data['version'] = 4;
		else
			$data['version'] = 2;


		$quote_list = '';

		$all_data 					= (array)$this->rest_app->get('index.php/rfq_api/load_approval_data/', $data);
		$data['table'] = '';
		//$this->rest_app->debug();
		$vendorcategory_data 			= $this->rest_app->get('index.php/rfq_api/vendorcategory/', '', 'application/json');
		$currency_data_array			= $this->rest_app->get('index.php/rfq_api/currency/', '', 'application/json');
		$requestor_data_array	 		= $this->rest_app->get('index.php/rfq_api/requestor/', '', 'application/json');
		$purpose_data_array 			= $this->rest_app->get('index.php/rfq_api/purpose/', '', 'application/json');
		$reason_data_array	 			= $this->rest_app->get('index.php/rfq_api/reason/', '', 'application/json');
		$unit_data_array	 			= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');

		$total_lines	 				= (array)$this->rest_app->get('index.php/rfq_api/get_total_lines/', $data, 'application/json');
		$data['total_lines']			= $total_lines['line_data_count'];

		$category_array = array();
		$category_array[''] = '-- Select --';
		foreach($vendorcategory_data as $row)
		{
			$category_array[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
		}
		$data['category_array'] = $category_array;

		$currency_data = array();
		$currency_data[''] = '-- Select --';
		$current_currency_data = '';
		foreach($currency_data_array as $row)
		{
			$currency_data[$row->CURRENCY_ID] = $row->ABBREVIATION;
			if ($row->CURRENCY_ID == $all_data['result'][0]->CURRENCY_ID) {
				$current_currency_data = $row->ABBREVIATION;
			}
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

		$lead_time = array();
		$lead_time[''] = '-- Select --';

		for($i = 1; $i<=100; $i++)
		{
			$lead_time[$i] = $i;
		}

		if ($all_data['num_rows'] > 0)
		{
			$count_blank = $all_data['attachment_count'] + 1;
			$data['title'] 					= $all_data['result'][0]->TITLE;
			$data['type'] 					= $all_data['result'][0]->RFQRFB_TYPE;
			$data['currency'] 				= $all_data['result'][0]->CURRENCY_ID;
			$data['delivery_date']			= date('Y-m-d', strtotime($all_data['result'][0]->DELIVERY_DATE));

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
			$data['bom_modals'] = '';
			$data['bom_file_modals'] = '';

			$value['SUBMISSION_DEADLINE']   = $all_data['result'][0]->SUBMISSION_DEADLINE;
			$now = new DateTime();
		    $future_date = new DateTime(date('Y-m-d',strtotime($value['SUBMISSION_DEADLINE'])));

		    $data['close_date'] = date('F d, Y',strtotime($value['SUBMISSION_DEADLINE']));
		    $interval = $future_date->diff($now);
		    $data['interval'] = $interval->format("%a days %h hours");



			$new_data['lineid'] = 0;
			$new_data['lineid2'] = 0;
			$new_data['id'] = $id;
			$x = 1;
			foreach($all_data['result'] as $row)
			{
				$new_data['lineid2'] = $row->RFQRFB_LINE_ID;

				if($new_data['lineid2'] != $new_data['lineid'] && $row->INVITE_ID == $invite_id)
				{
					$not_quote = 0;


					//get data if draft
					$draft_data['lineid'] = $row->RFQRFB_LINE_ID;
					$draft_data['rfx_id'] = $data['rfx_id'];
					$draft_data['invite_id'] = $data['invite_id'];
					$draft_data['shortlisted'] = 1;

					if($status['status'] == 161)
						$draft_data['version'] = $data['version'];
					else
						$draft_data['version'] = $version; // nasa taas ung bagong version kung paanu kinuha // 1 default but changed as what the qa says

					$draft				= (array)$this->rest_app->get('index.php/rfq_api/get_draft_quotes/', $draft_data, 'application/json');
					//echo "<pre>";
					//var_dump($draft_data);
					//echo "</pre>";
					//$this->rest_app->debug();

					if($draft['num_rows'] > 0)
					{
						if($draft['result'][0]->QUOTE == 1)
						{
							$no_quote_count++;
							$not_quote = 1;
							$no_quote_properties = ' readonly';
							$no_quote_delivery = ' disabled';
						}

						$quote_amount 		=	number_format((isset($draft['result'][0]->QUOTE_AMOUNT) ? $draft['result'][0]->QUOTE_AMOUNT : 0), 2, '.', ',');
						$lead_time_default	=	$draft['result'][0]->LEAD_TIME;
						$counter_offer 		=	$draft['result'][0]->COUNTER_OFFER;
						$attachment_path 	=	$draft['result'][0]->ATTACHMENT_PATH;

				        $first_extension = substr(base_url().$draft['result'][0]->ATTACHMENT_PATH, -5);

						if (strpos($first_extension, '.doc') !== false)
							$draft_image =  base_url().'rfx_upload_attachment/doc_word.png';
				        elseif(strpos($first_extension, '.xls') !== false)
				        	$draft_image =  base_url().'rfx_upload_attachment/doc_excel.png';
				        elseif(strpos($first_extension, '.pdf') !== false)
				        	$draft_image =  base_url().'rfx_upload_attachment/doc_pdf.png';
				        else
				        	$draft_image =  base_url().$draft['result'][0]->ATTACHMENT_PATH;
				        $display_image = '';
				        if(empty($draft['result'][0]->ATTACHMENT_PATH))
				        {
				        	$draft_image = '';
				        	$display_image = 'display: none;';
				        }

						$image_quote 		=	'<a href="#" onclick="load_attachment(\''.$draft['result'][0]->ATTACHMENT_PATH.'\')"><img class="img-responsive image_min" src="'.$draft_image.'" style="height: 100px; width:100px; '.$display_image.'" ></a>';



						if($draft['num_rows'] > 1)
						{
							$total_counter = $draft['num_rows'] - 1;
							for($o=1, $p=2; $o <= $total_counter; $o++, $p++)
							{
								$line_attachment = $this->rest_app->get('index.php/rfq_api/get_attachment_by_line_id/', array('rfqrfb_line_id'=>$draft_data['lineid']), 'application/json');
								$btn_bom_viewq = '';
								$bom_modals = '';
								$price_disabled = '';

								if ($line_attachment!=null) {

									if ($line_attachment[0]->LINE_ATTACHMENT_ID>0) {
										$bom_modal_data = array();
										$bom_modal_data['bom_lines'] = array();
										$bom_modal_data['user_type'] = $this->session->userdata('user_type');
										$bom_modal_data['line_attachment_id'] = $line_attachment[0]->LINE_ATTACHMENT_ID;
										$bom_modal_data['line_no'] = $x;
										$bom_modal_data['quote_no'] = $p;
										$bom_modal_data['current_currency_data'] = $current_currency_data;

										$bom_modal_data['bom_lines'] = $this->get_bom_lines($bom_modal_data['line_attachment_id'], $bom_modal_data['quote_no'], $this->session->userdata('vendor_id'));
										$btn_bom_viewq .= '<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$bom_modal_data['line_attachment_id'].'_'.$bom_modal_data['quote_no'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM"></input>';
										// $btn_bom_file_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_file_modal_'.$bom_modal_data['line_attachment_id'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_file_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM File">';

										$bom_modals .= $this->load->view('rfqb/bom_modal_view',$bom_modal_data, true);
										$bom_modals .= '
													<input type="hidden" name="attachment_type_'.$x.'_'.$p.'" id="attachment_type_'.$x.'_'.$p.'" value="'.$line_attachment[0]->ATTACHMENT_TYPE.'">
													<input type="hidden" name="line_attachment_id_'.$x.'_'.$p.'" id="line_attachment_id_'.$x.'_'.$p.'" value="'.$bom_modal_data['line_attachment_id'].'">
													<input type="hidden" name="bom_line_cnt_'.$x.'_'.$p.'" id="bom_line_cnt_'.$x.'_'.$p.'" value="'.count($bom_modal_data['bom_lines']).'">';
										if ($line_attachment[0]->ATTACHMENT_TYPE==1) {
											$price_disabled = 'disabled';
										}
									}
								}

								if($draft['result'][$o]->ATTACHMENT_PATH != null)
									$image_display = 'inline';
								else
									$image_display = 'none';

								$document_extension = substr(base_url().$draft['result'][$o]->ATTACHMENT_PATH, -5);

								if (strpos($document_extension, '.doc') !== false)
									$old_image =  base_url().'rfx_upload_attachment/doc_word.png';
								elseif(strpos($document_extension, '.xls') !== false)
									$old_image =  base_url().'rfx_upload_attachment/doc_excel.png';
								elseif(strpos($document_extension, '.pdf') !== false)
									$old_image =  base_url().'rfx_upload_attachment/doc_pdf.png';
								else
									$old_image =  base_url().$draft['result'][$o]->ATTACHMENT_PATH;

					        $draft_amount = 	$draft['result'][$o]->QUOTE_AMOUNT;
					        $draft_amount = 	number_format((isset($draft_amount) ? $draft_amount : 0), 2, '.', ',');

					        if(empty($draft['result'][$o]->ATTACHMENT_PATH))
				        		$old_image = '#';

									$quote_list .= '<input type="hidden" name="counter_offer_hidden'.$x.'_'.$p.'" id="counter_offer_hidden'.$x.'_'.$p.'" value='.$draft['num_rows'].'>
												   <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
											   <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$x.'_'.$p.'', $draft_amount , 'id="txt_quote'.$x.'_'.$p.'" maxlength="17" class="form-control numeric-decimal field-required" style="width: 150px" '.$price_disabled).'</div>
												   </div>

												   <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
												   <div class="col-md-8" style="padding-left: 0">'.form_dropdown('delivery_time'.$x.'_'.$p.'', $lead_time, $draft['result'][$o]->LEAD_TIME, 'id="delivery_time'.$x.'_'.$p.'" class="form-control btn btn-default dropdown-toggle" style="width: 130px;"').'</div>
												   </div>
												   '.$bom_modals.'
												   '.$btn_bom_viewq.'
												 	'.br(2).'<div>Counter Offer</div><br>

												   <div class="col-md-12" id="counter_offer_textarea'.$x.'_'.$p.'" style=" width: 100%;">
													<textarea class="form-control" id="txt_counteroffer'.$x.'_'.$p.'" name="txt_counteroffer'.$x.'_'.$p.'" >'.$draft['result'][$o]->COUNTER_OFFER.'</textarea>
												   </div>

												   <input type="hidden" name="attachment_value'.$x.'_'.$p.'" id="attachment_value'.$x.'_'.$p.'" value="0">

												   <div id="add_attachment'.$x.'_'.$p.'" >
													<div id="attachment_img'.$x.'_'.$p.'">
													<a href="#" onclick="load_attachment(\''.$draft['result'][$o]->ATTACHMENT_PATH.'\')"><img class="img-responsive image_min" src="'.$old_image.'" style="height: 100px; width:100px; display: '.$image_display.'" ></a>
													<input type="hidden" name="hidden_quote_path_'.$x.'_'.$p.'" id="hidden_quote_path_'.$x.'_'.$p.'" value="'.$draft['result'][$o]->ATTACHMENT_PATH.'">

													</div>
													<div>
													</div>

												   </div><br>';
												   $quote_count++;

								if($draft['result'][$o]->QUOTE == 1)
									$no_quote_count++;
							}
						}
					}

					$attachment_line	= (array)$this->rest_app->get('index.php/rfq_api/load_attachment_data/', $new_data);

					$attachment = '';
					$i = 1;
					$r = 0;
					$bom_modal_data = array();
					$price_disabled = '';
					foreach($attachment_line as $row2)
					{
						$r++;
						$bom_modal_data['bom_lines'] = array();
						$bom_modal_data['bom_file_lines'] = array();

						if ($row2->ATTACHMENT_TYPE == 1) { //BOM
							$bom_modal_data['line_attachment_id'] = $row2->LINE_ATTACHMENT_ID;
							$bom_modal_data['quote_no'] = 1;
							$bom_modal_data['line_no'] = $x;
							$bom_modal_data['bom_lines'] = $this->get_bom_lines($row2->LINE_ATTACHMENT_ID, $bom_modal_data['quote_no'], $this->session->userdata('vendor_id'));
							// var_dump($bom_modal_data['bom_lines']);
							$bom_modal_data['bom_file_lines'] = $this->get_bom_file_lines($row2->LINE_ATTACHMENT_ID, $this->session->userdata('vendor_id'));
							$bom_modal_data['current_currency_data'] = $current_currency_data;
							$bom_modal_data['user_type'] = $this->session->userdata('user_type');
							$btn_bom_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$bom_modal_data['line_attachment_id'].'_'.$bom_modal_data['quote_no'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM"></input>';
							// $btn_bom_file_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_file_modal_'.$bom_modal_data['line_attachment_id'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_file_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM File">';
							$data['bom_modals'] .= $this->load->view('rfqb/bom_modal_view',$bom_modal_data, true);
							$data['bom_file_modals'] .= $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
							$price_disabled = 'disabled';
						}

						$new_datas['attachment_type']	= $row2->ATTACHMENT_TYPE;
						$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $new_datas, 'application/json');

						if($new_datas['attachment_type'] == 1){
							$image = '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$row2->LINE_ATTACHMENT_ID.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="100px" width="100px"></a>' ;
						} elseif($new_datas['attachment_type'] == 3) {
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$row2->FILE_PATH.'" id="image'.$x.'_'.$i.'" value="" height="100px" width="100px"></a>';
						} else {
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="100px" width="100px"></a>' ;
						}

						$display = 'style="display:inline-block;"';

						$attachment .= 		'
												<div id="attachment'.$x.'_'.$i.'" '.$display.' class="dv_attachment">
													'.$image.'
													<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="'.$row2->FILE_PATH.'">
													<input type="hidden" name="attachment_desc_'.$x.'_'.$i.'" id="attachment_desc_'.$x.'_'.$i.'" value="'.$row2->DESCRIPTION.'">
													<input type="hidden" name="attachment_type_'.$x.'_'.$i.'" id="attachment_type_'.$x.'_'.$i.'" value="'.$row2->ATTACHMENT_TYPE.'">
													<input type="hidden" name="line_attachment_id_'.$x.'_'.$i.'" id="line_attachment_id_'.$x.'_'.$i.'" value="'.$row2->LINE_ATTACHMENT_ID.'">
													<input type="hidden" name="bom_line_cnt_'.$x.'_'.$i.'" id="bom_line_cnt_'.$x.'_'.$i.'" value="'.count($bom_modal_data['bom_lines']).'">
												 	<center>'.$row2->DESCRIPTION.'</center>
												 </div>
											';
						$i++;

					}

					for($z=$count_blank; $z<=8; $z++)
					{
						$display = 'style="display:none;"';

						$attachment .= 		'
												<div id="attachment'.$x.'_'.$z.'" '.$display.'>
													<img class="img-responsive image_min" src="#" value="" height="100px" width="100px">
													<input type="hidden" name="hidden_path_'.$x.'_'.$z.'" id="hidden_path_'.$x.'_'.$z.'" value="0">
													<input type="hidden" name="attachment_desc_'.$x.'_'.$z.'" id="attachment_desc_'.$x.'_'.$z.'" value="0">
													<input type="hidden" name="attachment_type_'.$x.'_'.$z.'" id="attachment_type_'.$x.'_'.$z.'" value="0">
												 </div>';
					}


					$attachment.= '';
					if($not_quote > 0)
						$ischeck = 'checked';
					else
						$ischeck = '';


					$data['table'].= '
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<input type="hidden" id="categoryname'.$x.'" name="categoryname'.$x.'" value="'.$row->CATEGORY_NAME.'">
											<div class="panel-heading"><strong>'.$row->CATEGORY_NAME.'</strong></div>
											<input type="hidden" name="num_quote'.$x.'" id="num_quote'.$x.'" value='.$quote_count.'>
											<input type="hidden" id="rfqrfbline_id'.$x.'" name="rfqrfbline_id'.$x.'" value="'.$row->RFQRFB_LINE_ID.'">
											<input type="hidden" id="line_num_rows" name="line_num_rows" value="'.$all_data['num_rows'].'">
											<div class="row">
												<div class="form-group indent_left">
													<div class="col-md-6 indent_top">Description</div>
													<div class="col-md-2 indent_top">Unit of Measure</div>
													<div class="col-md-1 indent_top">Quantity</div>
													<div class="col-md-3 indent_top">Quote</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group indent_left">
													<div class="col-md-6 indent_top">'.form_input('txt_description'.$x, $row->DESCRIPTION, 'id="txt_description'.$x.'" class="form-control" readonly').'</div>
													<div class="col-md-2 indent_top">'.form_input('txt_unit_measure'.$x, $row->MEASURE_NAME, 'id="txt_unit_measure'.$x.'" class="form-control" readonly').'</div>
													<div class="col-md-1 indent_top">'.form_input('quantity'.$x, $row->QUANTITY, 'id="quantity'.$x.'" class="form-control" readonly').'</div>
													<div class="col-md-3 indent_top">
														<div class="form-inline">
															<div class="form-group">


																'.form_input('txt_quote'.$x.'_1', $quote_amount, 'id="txt_quote'.$x.'_1"'.$no_quote_properties.' maxlength="17" class="form-control numeric-decimal field-required" onchange="quote_value(\''.$x.'_1\')" style="width: 150px" '.$price_disabled).'
																<input type="hidden" name="quoteischecked'.$x.'_1" id="quoteischecked'.$x.'_1" value="'.$not_quote.'">
																<input type="radio" name="radio_quote'.$x.'_1" id="radio_quote'.$x.'_1" '.$ischeck.' value="" onclick="no_quote(\''.$x.'_1\')">No Quote
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group indent_left">
													<input type="hidden" name="specs'.$x.'" id="specs'.$x.'" value="1">
													<div class="col-md-8 indent_top" id="specs'.$x.'"><a style="cursor:pointer;" onclick="specsview('.$x.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a></div>
													<div class="col-md-2 indent_top">Lead Time</div>
													<div class="col-md-2 indent_top">'.form_dropdown('delivery_time'.$x.'_1', $lead_time, $lead_time_default, $no_quote_delivery.' id="delivery_time'.$x.'_1" class="btn btn-default dropdown-toggle" style="width: 130px;"').'</div>
												</div>
											</div>
											<input type="hidden" name="attach'.$x.'" id="attach'.$x.'" value="1">
											<div class="row">
												<div class="form-group indent_left indent_right" style="padding-left: 20px;">
													<div class="col-md-6">
														<div class="col-md-12 indent_top" id="specifications'.$x.'" style="display: inline; height: 100px;"><textarea class="form-control" id="specs1_text" name="specs1_text" style="width: 100%; height: 50px;">'.$row->SPECIFICATION.'</textarea>
														</div>
														<br><br><br>
														<div class="col-md-6">
															<a style="cursor:pointer;" onclick="attachmentview_response('.$x.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count">'.$r.'</span> <span class="glyphicon glyphicon-modal-window"></span></a>
														</div>

															<div id="attachment'.$x.'" class="row" style="padding-left: 10px ;white-space: nowrap; display: inherit; overflow-x: scroll; height: 200px; width: 95%"><br><br>
																'.$attachment.'
															</div>
													</div>
													<div class="col-md-6">
														<label for="title" class="control-label cursor_pointer" onclick="#" style="display: none;">BOM</label><br>
														<input type="hidden" name="counter_offer_hidden'.$x.'_1" id="counter_offer_hidden'.$x.'_1" value=0>


														<div>
															'.(isset($btn_bom_view[$x]) ? $btn_bom_view[$x]: '').'
														</div>
				 										<div>Counter Offer <br></div>


														<div class="col-md-12" id="counter_offer_textarea'.$x.'_1" style=" width: 100%;">
															<textarea class="form-control" '.$no_quote_properties.' id="txt_counteroffer'.$x.'_1" name="txt_counteroffer'.$x.'_1" style="height: 100px;">'.$counter_offer.'</textarea>
														</div>

														<input type="hidden" name="attachment_value'.$x.'_1" id="attachment_value'.$x.'_1" value="0">



														<div id="add_attachment'.$x.'_1">
														<div id="attachment_img'.$x.'_1">'.$image_quote.'<input type="hidden" name="hidden_quote_path_'.$x.'_1" id="hidden_quote_path_'.$x.'_1" value="'.$attachment_path.'"></div>
														<input disabled type="button" name="add_attachmentbtn'.$x.'_1" '.$no_quote_delivery.' id="add_attachmentbtn'.$x.'_1" value="Select Attachment" onclick="attachment_response('.$x.', 1)">
														</div><br>

														<div id="quote_added'.$x.'">
														'.$quote_list.'
														</div>

														<div id="quote_bom_container_'.$x.'">
														</div>

														<input type="button" ' . ( (isset($quote_count) && $quote_count < 5) ? '' : 'disabled title="You have reached the quote limit."' ) . ' class="btn btn-default" id="add_another_quote_btn'.$x.'" value="Add Another Quote" onclick="add_another_bom_quote('.$x.','.(isset($bom_modal_data['line_attachment_id']) ? $bom_modal_data['line_attachment_id'].',1' : '0,0').',&quot;'.$current_currency_data.'&quot;)">
													</div>

												</div>
											</div>

											<br><br>
										</div>
									</div>
								</div>
								';
								$x++;
								$new_data['lineid'] = $row->RFQRFB_LINE_ID;

								$quote_list = '';

					}
					$quote_count = 1;
					$attachment = '';

			}
			$data['total_quotes'] = $x - 1;
			$data['no_quote_count'] = $no_quote_count;
		}

		$this->load->view('rfqb/rfq_response_creation_view_v2', $data);
	}

	function get_bom_lines($line_attachment_id, $quote_no, $vendor_id){
		$line = $this->rest_app->get('index.php/rfq_api/vendor_line_bom/', array('line_attachment_id'=>$line_attachment_id, 'bquote_no'=>$quote_no, 'vendor_id'=>$vendor_id), 'application/json');
		// var_dump($line);
		// $this->rest_app->debug();
		return $line;
	}

	function get_bom_file_lines($line_attachment_id, $vendor_id){
		$line = $this->rest_app->get('index.php/rfq_api/vendor_line_bom_file/', array('line_attachment_id'=>$line_attachment_id, 'vendor_id'=>$vendor_id), 'application/json');
		// var_dump($line);
		return $line;
	}

	function add_another_quote()
	{
		$data['row'] = $this->input->post('row');
		$data['categoryname'] = $this->input->post('categoryname');
		$data['line_attachment_id'] = $this->input->post('line_attachment_id');
		$data['attachment_type'] = $this->input->post('attachment_type');
		$data['current_currency_data'] = $this->input->post('current_currency_data');
		$data['quote_num'] = $this->input->post('quote_num') + 1;

		$lead_time = array();
		$lead_time[''] = '-- Select --';

		for($i = 1; $i<=100; $i++)
		{
			$lead_time[$i] = $i;
		}

		$data['lead_time'] = $lead_time;
		$data['types']	= ".xls, .xlsx, .jpg, .jpeg, .pdf, .doc, .docx";

		$this->load->view('rfqb/rfq_add_another_quote_view', $data);
	}

	function add_another_bom_quote()
	{
		$row = $this->input->post('row');
		$line_attachment_id = $this->input->post('line_attachment_id');
		$quote_num = $this->input->post('quote_num');
		$attachment_type = $this->input->post('attachment_type');
		$current_currency_data = $this->input->post('current_currency_data');
		$next_row = $quote_num + 1;

		// echo "quote_num: ".$quote_num.br(1);
		// echo "next_row: ".$next_row.br(1);

		$bom_modal_data = array();
		$bom_modal_data['bom_lines'] = array();
		if ($attachment_type == 1) { //BOM
			$bom_modal_data['line_attachment_id'] = $line_attachment_id;
			$bom_modal_data['quote_no'] = $next_row;
			$bom_modal_data['line_no'] = $row;
			$bom_modal_data['current_currency_data'] = $current_currency_data;
			$bom_modal_data['user_type'] = $this->session->userdata('user_type');

			$bom_modal_data['bom_lines'] = $this->get_bom_lines($bom_modal_data['line_attachment_id'], $bom_modal_data['quote_no'], $this->session->userdata('vendor_id'));
			// $btn_bom_view = '<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$bom_modal_data['line_attachment_id'].'_'.$bom_modal_data['quote_no'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM"></input>';
			// $btn_bom_file_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_file_modal_'.$bom_modal_data['line_attachment_id'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_file_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM File">';
			$this->load->view('rfqb/bom_modal_view',$bom_modal_data);
		}
	}

	function add_quotes()
	{
		$modal_txt_price = $this->input->post('modal_txt_price');
		$delivery_time = $this->input->post('delivery_time');
		$modal_counter_offer = $this->input->post('modal_counter_offer');
		$row = $this->input->post('row');
		$num_quote = $this->input->post('num_quote'.$row);
		$next_row = $num_quote + 1;

		$line_attachment_id = $this->input->post('modal_hidden_line_attachment_id');
		$attachment_type = $this->input->post('modal_hidden_attachment_type');
		$current_currency_data = $this->input->post('modal_hidden_current_currency_data');
		$bom_modals = '';
		$btn_bom_view = '';

		$lead_time = array();
		$lead_time[''] = '-- Select --';

		for($i = 1; $i<=100; $i++)
		{
			$lead_time[$i] = $i;
		}
		$data['hidden_file_path'] = '';


		$table = '';
		for ($i=2;$i <= $num_quote; $i++)
		{


        if($this->input->post('hidden_quote_path_'.$row.'_'.$i) != null)
        	$image_display = 'inline';
        else
        	$image_display = 'none';

        	$document_extension = substr($this->input->post('hidden_quote_path_'.$row.'_'.$i), -5);

			if (strpos($document_extension, '.doc') !== false)
				$old_image =  base_url().'rfx_upload_attachment/doc_word.png';
	        elseif(strpos($document_extension, '.xls') !== false)
	        	$old_image =  base_url().'rfx_upload_attachment/doc_excel.png';
	        elseif(strpos($document_extension, '.pdf') !== false)
	        	$old_image =  base_url().'rfx_upload_attachment/doc_pdf.png';
	        else
	        	$old_image =  base_url().$this->input->post('hidden_quote_path_'.$row.'_'.$i);


	        $draft_amount = 	$this->input->post('txt_quote'.$row.'_'.$i);
        	$draft_amount = 	str_replace(',', '', $draft_amount);
	        $draft_amount = 	number_format((isset($draft_amount) ? $draft_amount : 0), 2, '.', ',');


			$table .= '<input type="hidden" name="counter_offer_hidden'.$row.'_'.$i.'" id="counter_offer_hidden'.$row.'_'.$i.'" value=0>
					  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
					  <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$row.'_'.$i.'', $draft_amount, 'id="txt_quote'.$row.'_'.$i.'" maxlength="17" class="form-control numeric-decimal field-required" readonly style="width: 150px"').'</div>
					  </div>

					  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
					  <div class="col-md-8" style="padding-left: 0">'.form_dropdown('delivery_time'.$row.'_'.$i.'', $lead_time, $this->input->post('delivery_time'.$row.'_'.$i), 'id="delivery_time'.$row.'_'.$i.'" class="btn btn-default dropdown-toggle" style="width: 130px;"').'</div>
					  </div>
					  '.br(2).'<div>Counter Offer <br></div>

					  <div class="col-md-12" id="counter_offer_textarea'.$row.'_'.$i.'" style="width: 100%;">
					  	<textarea class="form-control" id="txt_counteroffer'.$row.'_'.$i.'" name="txt_counteroffer'.$row.'_'.$i.'" style="height: 100px;">'.$this->input->post('txt_counteroffer'.$row.'_'.$i).'</textarea>
					  </div>

					  <input type="hidden" name="attachment_value'.$row.'_'.$i.'" id="attachment_value'.$row.'_'.$i.'" value="0">

					  <div id="add_attachment'.$row.'_'.$i.'" style="display: inline;">
						<div id="attachment_img'.$row.'_'.$i.'">
				 		<a href="#" onclick="load_attachment(\''.$this->input->post('hidden_quote_path_'.$row.'_'.$i).'\')"><img class="img-responsive image_min" src="'.$old_image.'" style="height: 100px; width:100px; display: '.$image_display.'" ></a>
						<input type="hidden" name="hidden_quote_path_'.$row.'_'.$i.'" id="hidden_quote_path_'.$row.'_'.$i.'" value="'.$this->input->post('hidden_quote_path_'.$row.'_'.$i).'">
						</div>
						<div>
						</div>
					  </div><br>';
		}


		$bom_modal_data = array();
		$bom_modal_data['bom_lines'] = array();
		$price_disabled = '';
		if ($attachment_type == 1) { //BOM
			$bom_modal_data['line_attachment_id'] = $line_attachment_id;
			$bom_modal_data['line_no'] = $row;
			$bom_modal_data['quote_no'] = $next_row;
			$bom_modal_data['current_currency_data'] = $current_currency_data;
			$bom_modal_data['user_type'] = $this->session->userdata('user_type');

			$bom_modal_data['bom_lines'] = $this->get_bom_lines($bom_modal_data['line_attachment_id'], $bom_modal_data['quote_no'], $this->session->userdata('vendor_id'));
			$btn_bom_view = '<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$bom_modal_data['line_attachment_id'].'_'.$bom_modal_data['quote_no'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM"></input>';
			// $btn_bom_file_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_file_modal_'.$bom_modal_data['line_attachment_id'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_file_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM File">';
			// $bom_modals .= $this->load->view('rfqb/bom_modal_view',$bom_modal_data, true);
			$price_disabled = 'disabled';
		}

		$data['upload_attachment_new'] = '';
		$data['hidden_file_path'] = '';
	    $data['file_path'] = null;
	    $data['file_name'] = null;
	    $data['image_src'] = null;

		if (isset($_FILES['upload_attachment_new']))
		{
			$data['upload_attachment_new'] = $_FILES['upload_attachment_new']['name'];
		}

	  	if($data['upload_attachment_new'] != '')
        {
	        $data['file_path'] = null;

			$web_upload_path = FCPATH.'rfx_upload_attachment';

	   		if(!is_dir($web_upload_path))
		    {
		    	mkdir($web_upload_path, 0777);
		    }

        	$config['upload_path'] = $web_upload_path;
            $config['allowed_types'] = '*';
            $config['max_size'] = '10000';
            $config['file_name'] = 'upload_quote_attachment_'.time();

            $this->load->library('upload', $config, 'upload_attachment_new');
    		$this->upload_attachment_new->initialize($config);

            if ( !$this->upload_attachment_new->do_upload('upload_attachment_new', FALSE))
            {
                echo '<script>alert("'.$this->upload_attachment_new->display_errors().'");</script>';
            }
            else
            {
                $upload_data = $this->upload_attachment_new->data();

	            $data['file_name'] = $config['file_name'].$upload_data['file_ext'];
	            $data['file_path'] = base_url().'rfx_upload_attachment/'.$data['file_name'];
	            $data['hidden_file_path'] = 'rfx_upload_attachment/'.$data['file_name'];

	        if($upload_data['file_ext'] == '.docx' ||$upload_data['file_ext'] == '.doc')
	        	$image_src =  base_url().'rfx_upload_attachment/doc_word.png';
	        elseif($upload_data['file_ext'] == '.xlsx' ||$upload_data['file_ext'] == '.xls')
	        	$image_src =  base_url().'rfx_upload_attachment/doc_excel.png';
	        elseif($upload_data['file_ext'] == '.pdf')
	        	$image_src =  base_url().'rfx_upload_attachment/doc_pdf.png';
	        else
	        	$image_src =  $data['file_path'];

			}
        }

        if(empty($image_src))
        	$image_src = '';

        if($data['file_path'] != null)
        	$image_display = 'inline';
        else
        	$image_display = 'none';
        $modal_txt_price = str_replace(',', '', $modal_txt_price);
	    $modal_txt_price = 	number_format((isset($modal_txt_price) ? $modal_txt_price : 0), 2, '.', ',');

	   $table .= '<input type="hidden" name="counter_offer_hidden'.$row.'_'.$next_row.'" id="counter_offer_hidden'.$row.'_'.$next_row.'" value=0>
				<input type="hidden" name="attachment_type_'.$row.'_'.$next_row.'" id="attachment_type_'.$row.'_'.$next_row.'" value="'.$attachment_type.'">
				<input type="hidden" name="line_attachment_id_'.$row.'_'.$next_row.'" id="line_attachment_id_'.$row.'_'.$next_row.'" value="'.$line_attachment_id.'">
				<input type="hidden" name="bom_line_cnt_'.$row.'_'.$next_row.'" id="bom_line_cnt_'.$row.'_'.$next_row.'" value="'.count($bom_modal_data['bom_lines']).'">

				  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
				  <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$row.'_'.$next_row.'', $modal_txt_price, ' maxlength="17" id="txt_quote'.$row.'_'.$next_row.'" class="form-control numeric-decimal field-required" style="width: 150px" '.$price_disabled).'</div>
				  </div>

				  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
				  <div class="col-md-8" style="padding-left: 0">'.form_dropdown('delivery_time'.$row.'_'.$next_row.'', $lead_time, $delivery_time, 'id="delivery_time'.$row.'_'.$next_row.'" class="btn btn-default dropdown-toggle" style="width: 130px;"').'</div>

				  '.$btn_bom_view.'

				  </div>
				  '.br(2).'<div>Counter Offer <br></div>

				  <div class="col-md-12" id="counter_offer_textarea'.$row.'_'.$next_row.'" style=" width: 100%;">
				  	<textarea class="form-control" id="txt_counteroffer'.$row.'_'.$next_row.'" name="txt_counteroffer'.$row.'_'.$next_row.'">'.$modal_counter_offer.'</textarea>
				  </div>

				  <input type="hidden" name="attachment_value'.$row.'_'.$next_row.'" id="attachment_value'.$row.'_'.$next_row.'" value="0">

				  <div id="add_attachment'.$row.'_'.$next_row.'" style="display: inline;">
				  <div id="attachment_img'.$row.'_'.$next_row.'">
				  <input type="hidden" name="hidden_quote_path_'.$row.'_'.$next_row.'" id="hidden_quote_path_'.$row.'_'.$next_row.'" value="'.$data['hidden_file_path'].'">
				  </div>
				  <div>
					<a href="#" onclick="load_attachment(\''.$data['hidden_file_path'].'\')"><img class="img-responsive image_min" src="'.$image_src.'" style="height: 100px; width:100px; display: '.$image_display.'" ></a>
		        	<input type="hidden" value="'.$data['hidden_file_path'].'" name="hidden_quote_path_'.$row.'_'.$next_row.'" id="hidden_quote_path_'.$row.'_'.$next_row.'">
		          </div>
				  </div><br>';

		echo $table;

	}

	function new_attachment_response()
	{
		$data['row'] = $_POST['row'];
		$data['col'] = $_POST['col'];

		$data['types']	= ".xls, .xlsx, .jpg, .jpeg, .pdf, .doc, .docx";


		$this->load->view('rfqb/new_response_attachment', $data);
	}

	function new_attachment_upload($row, $col)
	{
		/*$row = $_POST['row'];
		$col = $_POST['col'];*/

		$data['upload_attachment'] = '';
		$data['hidden_file_path'] = '';
	    $data['file_path'] = null;
	    $data['file_name'] = null;

		if (isset($_FILES['upload_attachment']))
		{
			$data['upload_attachment'] = $_FILES['upload_attachment']['name'];
		}

	  	if($data['upload_attachment'] != '')
        {
	        $data['file_path'] = null;

			$web_upload_path = FCPATH.'rfx_upload_attachment';

	   		if(!is_dir($web_upload_path))
		    {
		    	mkdir($web_upload_path, 0777);
		    }

        	$config['upload_path'] = $web_upload_path;
            $config['allowed_types'] = '*';
            $config['max_size'] = '10000';
            $config['file_name'] = 'upload_quote_attachment_'.time();

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

        if($upload_data['file_ext'] == '.docx' ||$upload_data['file_ext'] == '.doc')
        	$image_src =  base_url().'rfx_upload_attachment/doc_word.png';
        elseif($upload_data['file_ext'] == '.xlsx' ||$upload_data['file_ext'] == '.xls')
        	$image_src =  base_url().'rfx_upload_attachment/doc_excel.png';
        elseif($upload_data['file_ext'] == '.pdf')
        	$image_src =  base_url().'rfx_upload_attachment/doc_pdf.png';
        else
        	$image_src =  $data['file_path'];

        echo '<a href="#" onclick="load_attachment(\''.$data['hidden_file_path'].'\')"><img class="img-responsive image_min" src="'.$image_src.'" style="height: 100px; width:100px;" ></a>
        	  <input type="hidden" value="'.$data['hidden_file_path'].'" name="hidden_quote_path_'.$row.'_'.$col.'" id="hidden_quote_path_'.$row.'_'.$col.'">';

	}

	function submit_response_creation()
	{
		$data['action'] 			= $this->input->post('action');
		$data['vendor_id'] 			= $this->session->userdata('vendor_id');
		$data['date_created']		= date('d-M-Y');
		$data['user_id'] 			= $this->session->userdata('user_id');
		$data['created_by']			= $this->session->userdata('user_id');
		$data['position_id'] 		= $this->session->userdata('position_id');
		$data['rfx_id'] 			= $this->input->post('rfx_id');
		$data['line_num_rows'] 		= $this->input->post('line_num_rows');
		$data['invite_id'] 			= $this->input->post('invite_id');
		$data['active'] 			= 1;
		$data['nxt_position_id']	= 10;
		$data['status']				= 103;
		$status 					= (array)$this->rest_app->get('index.php/rfq_api/get_rfq_invitation_status/', $data, 'application/json');
		$inserted 					= $this->rest_app->get('index.php/rfq_api/get_rfq_response_quote/', $data, 'application/json');

		// $this->rest_app->debug();

		foreach($inserted as $row)
		{
			$version = $row->VERSION;
		}


		if($version == 1 && $status['status'] == 106)
			$data['version'] = 2;
		else if($version == 2 && $status['status'] == 161)
			$data['version'] = 2;
		else if($version == 2 && $status['status'] == 106)
			$data['version'] = 3;
		else if($version == 3 && $status['status'] == 161)
			$data['version'] = 3;
		else if($version == 3 && $status['status'] == 106)
			$data['version'] = 4;
		else if($version == 4 && $status['status'] == 161)
			$data['version'] = 4;
		else
			$data['version'] = 2;


		$total_lines = (array)$this->rest_app->get('index.php/rfq_api/get_total_lines/', $data, 'application/json');
		// $this->rest_app->debug();
		// lines where loop begins
		$data['line_data_count'] = $total_lines['line_data_count'];

		for($i=1; $i <= $total_lines['line_data_count']; $i++)
		{
			$data['rfqrfbline_id'.$i] 		= $this->input->post('rfqrfbline_id'.$i);
			$data['num_quote'.$i]			= $this->input->post('num_quote'.$i);

			for($x=1; $x<=$data['num_quote'.$i];$x++)
			{
				$data['txt_quote'.$i.'_'.$x] 			= $this->input->post('txt_quote'.$i.'_'.$x);
				$data['quoteischecked'.$i.'_'.$x]		= $this->input->post('quoteischecked'.$i.'_'.$x);
				$data['delivery_time'.$i.'_'.$x]		= $this->input->post('delivery_time'.$i.'_'.$x);
				$data['txt_counteroffer'.$i.'_'.$x]		= $this->input->post('txt_counteroffer'.$i.'_'.$x);
				$data['hidden_quote_path_'.$i.'_'.$x] 	= $this->input->post('hidden_quote_path_'.$i.'_'.$x);


				#start: bom part
				$bom_line['vendor_id'] = $data['vendor_id'];
				$bom_line['line_attachment_id'] 	= $this->input->post('line_attachment_id_'.$i.'_'.$x);
				$bom_line['attachment_type'] 	= $this->input->post('attachment_type_'.$i.'_'.$x);
				$bom_line['bom_line_cnt'] 	= $this->input->post('bom_line_cnt_'.$i.'_'.$x);
				$bom_line['bquote_no'] = $x;

				if ($bom_line['attachment_type']==1 && $bom_line['bom_line_cnt'] && $bom_line['bom_line_cnt']>0 && $bom_line['line_attachment_id']> 0) {//bom_line
					for ($bom_line_cnt = 0; $bom_line_cnt < $bom_line['bom_line_cnt']; $bom_line_cnt++) {
						$bom_line['row_no'] 	= $this->input->post('line_attachment_row_'.$bom_line['line_attachment_id'].'_'.$bom_line_cnt.'_'.$bom_line['bquote_no']);
						$bom_line['cost'] 	= $this->input->post('hidden_line_bom_cost_'.$bom_line['line_attachment_id'].'_'.$bom_line_cnt.'_'.$bom_line['bquote_no']);
						$bom_line['remarks'] 	= $this->input->post('hidden_line_bom_remarks_'.$bom_line['line_attachment_id'].'_'.$bom_line_cnt.'_'.$bom_line['bquote_no']);
						$bom_line['cost'] = str_replace(',','',$bom_line['cost']);

						$post_response = $this->rest_app->post('index.php/rfq_api/line_bom_cost/', $bom_line, '');
						// $this->rest_app->debug();
						$get_response = $this->rest_app->get('index.php/rfq_api/line_bom_cost/', $bom_line, 'application/json');
						if ($get_response && ($get_response)>0) {
							// do nothing
						} else {
							$bom_line_success = false;
						}
					}
				}
				#end: bom part
			}

		}


		$data['current_status_id']	= $status['status']; //$this->input->post('current_status_id');
		$data['position_id']		= 10; // $status['position_id'];
		$data['status_type'] 		= 3;

		$next_status_data		 	= (array)$this->rest_app->get('index.php/rfq_api/next_status/', $data, 'application/json');
		//$this->rest_app->debug();

		if($data['action'] == 1)
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->NEXT_POSITION_ID; // 102=published or approved
			$data['status']				= $next_status_data['result'][0]->APPROVE_STATUS_ID; // 102=published or approved

			if($data['version'] == 4)
				$data['status'] = $next_status_data['result'][0]->NEXT_STATUS_ID;
		}
		else
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->NEXT_POSITION_ID; // 103=published or approved
			$data['status']				= $next_status_data['result'][0]->REJECT_STATUS_ID; // 103=published or approved
		}
		$data_result = (array)$this->rest_app->get('index.php/rfq_api/submit_response_creation', $data, 'application/json');

		echo "<pre>";
		print_r($data);
		echo "</pre>";
		$this->rest_app->debug();
		die();
		if ($data_result === 'success')
			echo 'success';
		else
			echo 'failed';


	}


}
?>
