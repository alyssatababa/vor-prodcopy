<?php
Class Rfq_Response_Vendor_View extends CI_Controller{

	function index($id = 0, $invite_id)
	{
		$data_select[''] 					= "-- Select --";
		$data['id'] 						= $id;
		$data['rfx_id']						= $id;
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
		$quote_amount						= '';
		$lead_time_default					= '';
		$counter_offer						= '';
		$attachment_path					= '';
		$image_quote						= '';
		$quote_count						= 1;
		$data['position_id']				= 8;
		$count_blank 						= 0;
		$data['status_id']					= 21;
		$data['bom_file_modals']			= '';

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

				if($new_data['lineid2'] != $new_data['lineid'])
				{
					
					//get data if draft
					$draft_data['lineid'] = $row->RFQRFB_LINE_ID;
					$draft_data['rfx_id'] = $data['rfx_id'];
					$draft_data['invite_id'] = $data['invite_id'];
					$draft				= (array)$this->rest_app->get('index.php/rfq_api/get_response_quotes/', $draft_data, 'application/json');

					
					$data['draft_res'] = $draft['num_rows'];

					if($draft['num_rows'] > 0)
					{
						$quote_amount 		=	$draft['result'][0]->QUOTE_AMOUNT;
						$lead_time_default	=	$draft['result'][0]->LEAD_TIME;
						$counter_offer 		=	$draft['result'][0]->COUNTER_OFFER;
						$attachment_path 	=	$draft['result'][0]->ATTACHMENT_PATH;
						$image_quote 		=	'<img class="img-responsive image_min" src="'.base_url().$draft['result'][0]->ATTACHMENT_PATH.'" style="height: 120px; width:120px;" >';
											 	

						if($draft['num_rows'] > 1)
						{
							$total_counter = $draft['num_rows'] - 1;
							for($o=1, $p=2; $o <= $total_counter; $o++, $p++)
							{
								$quote_list .= '<input type="hidden" name="counter_offer_hidden'.$o.'_'.$p.'" id="counter_offer_hidden'.$o.'_'.$p.'" value='.$draft['num_rows'].'>
											   <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
											   <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$o.'_'.$p.'', $draft['result'][$o]->QUOTE_AMOUNT, 'id="txt_quote'.$o.'_'.$p.'" class="form-control" style="width: 150px" disabled').'</div>
											   </div>
											 			 									
											   <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
											   <div class="col-md-8" style="padding-left: 0">'.form_dropdown('delivery_time'.$o.'_'.$p.'', $lead_time, $draft['result'][$o]->LEAD_TIME, 'id="delivery_time'.$o.'_'.$p.'" class="btn btn-default dropdown-toggle" style="width: 130px;" disabled="disabled"').'</div>
											   </div>
											   '.br(2).'<a onclick="hide_counter_offer(\''.$o.'_'.$p.'\')" class="cursor_pointer"><div id="counter_offer_text'.$o.'_'.$p.'" >Counter Offer >> </div></a><br>
											   
											   <div class="col-md-12" id="counter_offer_textarea'.$o.'_'.$p.'" style="display: none; width: 100%;">
											   	<textarea class="form-control" id="txt_counteroffer'.$o.'_'.$p.'" name="txt_counteroffer'.$o.'_'.$p.'" readonly="readonly">'.$draft['result'][$o]->COUNTER_OFFER.'</textarea>
											   </div>
							  
											   <input type="hidden" name="attachment_value'.$o.'_'.$p.'" id="attachment_value'.$o.'_'.$p.'" value="0">
											   <a onclick="new_attachment(\''.$o.'_'.$p.'\')" class="cursor_pointer"> <div id="attachment_href'.$o.'_'.$p.'">Attachment >> </div></a>
											   <div id="add_attachment'.$o.'_'.$p.'" style="display: none;">
											 	<div id="attachment_img'.$o.'_'.$p.'">
											 	<img class="img-responsive image_min" src="'.base_url().$draft['result'][$o]->ATTACHMENT_PATH.'" style="height: 120px; width:120px;" >
											 	<input type="hidden" name="hidden_quote_path_'.$o.'_'.$p.'" id="hidden_quote_path_'.$o.'_'.$p.'" value="'.$draft['result'][$o]->ATTACHMENT_PATH.'">
											 	</div>
											 	<div>
											 	<input type="button" name="add_attachmentbtn'.$o.'_'.$p.'" id="add_attachmentbtn'.$o.'_'.$p.'" value="Select Attachment" onclick="attachment_response('.$o.', '.$p.')">
											 	</div>
											   
											   </div><br>';
											   $quote_count++;
							}
						}
					}

					$attachment_line	= (array)$this->rest_app->get('index.php/rfq_api/load_attachment_data/', $new_data); 

					$attachment = '';
					$i = 1;
					$r = 0;
					foreach($attachment_line as $row2)
					{
						$r++;

						$new_datas['attachment_type']	= $row2->ATTACHMENT_TYPE;
						$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $new_datas, 'application/json');

						$bom_modal_data = array();
						$bom_modal_data['bom_file_lines'] = array();
						if ($row2->ATTACHMENT_TYPE == 1) { //BOM														
							$bom_modal_data['line_attachment_id'] = $row2->LINE_ATTACHMENT_ID;							
							$bom_modal_data['bom_file_lines'] = $this->get_bom_file_lines($row2->LINE_ATTACHMENT_ID, $this->session->userdata('vendor_id'));
							$data['bom_file_modals'] .= $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
						} 
						
						if($new_datas['attachment_type'] == 1){
							$image = '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$row2->LINE_ATTACHMENT_ID.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>' ;
						} elseif($new_datas['attachment_type'] == 3)
							$image = '<img class="img-responsive image_min" src="'.base_url().$row2->FILE_PATH.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px">';
						else
							$image = '<img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px">' ;


						$display = 'style="display:inline-block;"';

						$attachment .= 		'
												<div id="attachment'.$x.'_'.$i.'" '.$display.' class="dv_attachment">
													'.$image.'
													<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="'.$row2->FILE_PATH.'">
													<input type="hidden" name="attachment_desc_'.$x.'_'.$i.'" id="attachment_desc_'.$x.'_'.$i.'" value="'.$row2->DESCRIPTION.'">
													<input type="hidden" name="attachment_type_'.$x.'_'.$i.'" id="attachment_type_'.$x.'_'.$i.'" value="'.$row2->ATTACHMENT_TYPE.'">
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
												 </div>
';
					}


					$attachment.= '';

					$data['table'].= '
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-primary">
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
													<div class="col-md-1 indent_top">'.form_input('quantity'.$x, $row->QUANTITY, 'id="quantity'.$x.'" class="btn btn-default form-control disabled"').'</div>
													<div class="col-md-3 indent_top">
														<div class="form-inline">
															<div class="form-group">
																'.form_input('txt_quote'.$x.'_1', $quote_amount, 'id="txt_quote'.$x.'_1" class="form-control" onchange="quote_value(\''.$x.'_1\')" style="width: 150px" disabled').'
																<input type="hidden" name="quoteischecked'.$x.'_1" id="quoteischecked'.$x.'_1" value="0">
																<input type="radio" name="radio_quote'.$x.'_1" id="radio_quote'.$x.'_1" value="" onclick="no_quote(\''.$x.'_1\')" disabled="disabled">No Quote
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="form-group indent_left">
													<input type="hidden" name="specs'.$x.'" id="specs'.$x.'" value="0">
													<div class="col-md-8 indent_top" id="specs'.$x.'"><a style="cursor:pointer;" onclick="specsview('.$x.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a></div>
													<div class="col-md-2 indent_top">Delivery Lead Time</div>
													<div class="col-md-2 indent_top" >'.form_dropdown('delivery_time'.$x.'_1', $lead_time, $lead_time_default, 'id="delivery_time'.$x.'_1" class="btn btn-default dropdown-toggle" style="width: 130px;" disabled="disabled"').'</div>
												</div>
											</div>
											<input type="hidden" name="attach'.$x.'" id="attach'.$x.'" value="0">
											<div class="row">
												<div class="form-group indent_left indent_right" style="padding-left: 20px;">
													<div class="col-md-6">
														<div class="col-md-6 indent_top" id="specifications'.$x.'" style="display: none;"><textarea class="form-control" id="specs1_text" name="specs1_text" style="width: 100%;" readonly="readonly">'.$row->SPECIFICATION.'</textarea>
														</div>
														<br><br><br>
														<div class="col-md-6">
															<a style="cursor:pointer;" onclick="attachmentview_response('.$x.');">Attachments for Vendors Viewing <span class="badge" id="attachment_count">'.$r.'</span> <span class="glyphicon glyphicon-modal-window"></span></a>
														</div>

															<div id="attachment'.$x.'" class="row" style="padding-left: 10px ;white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%"><br><br>
																'.$attachment.'
															</div>
													</div>
													<div class="col-md-6">
														<label for="title" class="control-label cursor_pointer" onclick="#" style="display: none;">BOM</label><br>
														<input type="hidden" name="counter_offer_hidden'.$x.'_1" id="counter_offer_hidden'.$x.'_1" value=0>
														<a onclick="hide_counter_offer(\''.$x.'_1\')" class="cursor_pointer"><div id="counter_offer_text'.$x.'_1" >Counter Offer >> </div></a><br>
														
														<div class="col-md-12" id="counter_offer_textarea'.$x.'_1" style="display: none; width: 100%;">
															<textarea class="form-control" id="txt_counteroffer'.$x.'_1" name="txt_counteroffer'.$x.'_1 " readonly="readonly">'.$counter_offer.'</textarea>
														</div>

														<input type="hidden" name="attachment_value'.$x.'_1" id="attachment_value'.$x.'_1" value="0">
														<a onclick="new_attachment(\''.$x.'_1\')" class="cursor_pointer"> <div id="attachment_href'.$x.'_1">Attachment >> </div></a>
														
														<div id="add_attachment'.$x.'_1" style="display: none;">
														<div id="attachment_img'.$x.'_1">'.$image_quote.'<input type="hidden" name="hidden_quote_path_'.$x.'_1" id="hidden_quote_path_'.$x.'_1" value="'.$attachment_path.'"></div></div><br>
														
					  									
														<div id="quote_added'.$x.'"></div>

														'.$quote_list.'
														<input type="button" class="btn btn-default" value="Add Another Quote" onclick="add_another_quote('.$x.')" style="display: none;">	
														<div>&nbsp;</div>
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

		}

		$this->load->view('rfqb/rfq_response_vendor_view', $data);
	}
	
	function get_bom_file_lines($line_attachment_id, $vendor_id){
		$line = $this->rest_app->get('index.php/rfq_api/vendor_line_bom_file/', array('line_attachment_id'=>$line_attachment_id, 'vendor_id'=>$vendor_id), 'application/json');
		// var_dump($line);
		return $line;
	}

	function add_another_quote()
	{
		$data['row'] = $this->input->post('row');
		$lead_time = array();
		$lead_time[''] = '-- Select --';

		for($i = 1; $i<=100; $i++)
		{
			$lead_time[$i] = $i;
		}

		$data['lead_time'] = $lead_time;

		$this->load->view('rfqb/rfq_add_another_quote_view', $data);
	}

	function add_quotes()
	{
		$modal_txt_price = $this->input->post('modal_txt_price');
		$delivery_time = $this->input->post('delivery_time');
		$modal_counter_offer = $this->input->post('modal_counter_offer');
		$row = $this->input->post('row');
		$num_quote = $this->input->post('num_quote'.$row);
		$next_row = $num_quote + 1;

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
			$table .= '<input type="hidden" name="counter_offer_hidden'.$row.'_'.$i.'" id="counter_offer_hidden'.$row.'_'.$i.'" value=0>
					  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
					  <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$row.'_'.$i.'', $this->input->post('txt_quote'.$row.'_'.$i), 'id="txt_quote'.$row.'_'.$i.'" class="form-control" style="width: 150px"').'</div>
					  </div>
								 									
					  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
					  <div class="col-md-8" style="padding-left: 0" >'.form_dropdown('delivery_time'.$row.'_'.$i.'', $lead_time, $this->input->post('delivery_time'.$row.'_'.$i), 'id="delivery_time'.$row.'_'.$i.'" class="btn btn-default dropdown-toggle" style="width: 130px;" disabled="disabled"').'</div>
					  </div>
					  '.br(2).'<a onclick="hide_counter_offer(\''.$row.'_'.$i.'\')" class="cursor_pointer"><div id="counter_offer_text'.$row.'_'.$i.'" >Counter Offer >> </div></a><br>
					  
					  <div class="col-md-12" id="counter_offer_textarea'.$row.'_'.$i.'" style="display: none; width: 100%;">
					  	<textarea class="form-control" id="txt_counteroffer'.$row.'_'.$i.'" name="txt_counteroffer'.$row.'_'.$i.'">'.$this->input->post('txt_counteroffer'.$row.'_'.$i).'</textarea>
					  </div>
	 
					  <input type="hidden" name="attachment_value'.$row.'_'.$i.'" id="attachment_value'.$row.'_'.$i.'" value="0">
					  <a onclick="new_attachment(\''.$row.'_'.$i.'\')" class="cursor_pointer"> <div id="attachment_href'.$row.'_'.$i.'">Attachment >> </div></a>
					  <div id="add_attachment'.$row.'_'.$i.'" style="display: none;">
						<div id="attachment_img'.$row.'_'.$i.'">
				 		<img class="img-responsive image_min" src="'.base_url().$this->input->post('hidden_quote_path_'.$row.'_'.$i).'" style="height: 120px; width:120px;" >
						<input type="hidden" name="hidden_quote_path_'.$row.'_'.$i.'" id="hidden_quote_path_'.$row.'_'.$i.'" value="'.$this->input->post('hidden_quote_path_'.$row.'_'.$i).'">
						</div>
						<div>
						<input type="button" name="add_attachmentbtn'.$row.'_'.$i.'" id="add_attachmentbtn'.$row.'_'.$i.'" value="Select Attachment" onclick="attachment_response('.$row.', '.$i.')">
						</div>
						
					  
					  </div><br>';
		}

	   $table .= '<input type="hidden" name="counter_offer_hidden'.$row.'_'.$next_row.'" id="counter_offer_hidden'.$row.'_'.$next_row.'" value=0>
				  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Quote</div>
				  <div class="col-md-8" style="padding-left: 0">'.form_input('txt_quote'.$row.'_'.$next_row.'', $modal_txt_price, 'id="txt_quote'.$row.'_'.$next_row.'" class="form-control" style="width: 150px"').'</div>
				  </div>
							 									
				  <div class="form-inline"><div class="col-md-4" style="padding-left: 0">Delivery Lead Time</div>
				  <div class="col-md-8" style="padding-left: 0" >'.form_dropdown('delivery_time'.$row.'_'.$next_row.'', $lead_time, $delivery_time, 'id="delivery_time'.$row.'_'.$next_row.'" class="btn btn-default dropdown-toggle" style="width: 130px;" disabled="disabled"').'</div>
				  </div>
				  '.br(2).'<a onclick="hide_counter_offer(\''.$row.'_'.$next_row.'\')" class="cursor_pointer"><div id="counter_offer_text'.$row.'_'.$next_row.'" >Counter Offer >> </div></a><br>
				  
				  <div class="col-md-12" id="counter_offer_textarea'.$row.'_'.$next_row.'" style="display: none; width: 100%;">
				  	<textarea class="form-control" id="txt_counteroffer'.$row.'_'.$next_row.'" name="txt_counteroffer'.$row.'_'.$next_row.'">'.$modal_counter_offer.'</textarea>
				  </div>
 
				  <input type="hidden" name="attachment_value'.$row.'_'.$next_row.'" id="attachment_value'.$row.'_'.$next_row.'" value="0">
				  <a onclick="new_attachment(\''.$row.'_'.$next_row.'\')" class="cursor_pointer"> <div id="attachment_href'.$row.'_'.$next_row.'">Attachment >> </div></a>
				  <div id="add_attachment'.$row.'_'.$next_row.'" style="display: none;">
				  <div id="attachment_img'.$row.'_'.$next_row.'">
				  <input type="hidden" name="hidden_quote_path_'.$row.'_'.$next_row.'" id="hidden_quote_path_'.$row.'_'.$next_row.'" value="'.$data['hidden_file_path'].'">
				  </div>
				  <div>
					<input type="button" name="add_attachmentbtn'.$row.'_'.$next_row.'" id="add_attachmentbtn'.$row.'_'.$next_row.'" value="Select Attachment" onclick="attachment_response('.$row.', '.$next_row.')">
				  </div>
				  </div><br>';

		echo $table;

	}

	function new_attachment_response()
	{
		$data['row'] = $_POST['row'];
		$data['col'] = $_POST['col'];

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
					
			$web_upload_path = FCPATH.'rfx_upload_attachment\\';

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

        echo '<img class="img-responsive image_min" src="'.$data['file_path'].'" style="height: 120px; width:120px;" >
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
		$data['version']			= 1;		
		$status 					= (array)$this->rest_app->get('index.php/rfq_api/get_rfq_invitation_status/', $data, 'application/json');

		$total_lines = (array)$this->rest_app->get('index.php/rfq_api/get_total_lines/', $data, 'application/json');	
		//$this->rest_app->debug();
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
			$data['status']				= $next_status_data['result'][0]->NEXT_STATUS_ID; // 102=published or approved
		}
		else
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->CURRENT_POSITION_ID; // 103=published or approved
			$data['status']				= $next_status_data['result'][0]->SUSPEND_STATUS_ID; // 103=published or approved
		}

		$data_result = (array)$this->rest_app->get('index.php/rfq_api/submit_response_creation', $data, 'application/json');

		//$this->rest_app->debug();
		if ($data_result === 'success')
			echo 'success';
		else
			echo 'failed';		


	}


}
?>