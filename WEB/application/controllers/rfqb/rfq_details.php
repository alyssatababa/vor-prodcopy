<?php
class Rfq_Details extends CI_Controller
{
	function index($id = 0)
	{
		ini_set("memory_limit", "10000M");
		ini_set('max_execution_time', 5000);

		$position = $this->session->userdata('position_id');
		$data['id'] 						= $id;
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
		$data['status_name']				= '';
		$data['approvers_content'] 			= '';
		$data['invited_list'] 				= '';
		$data['position_id']				= 8;
		$count_blank 						= 0;
		$data['status_id']					= 21;
		$data['bom_file_modals']			= '';

		$all_data 						= (array)$this->rest_app->get('index.php/rfq_api/load_approval_data/', $data); 

		$data['is_open'] = '';
		
		//$this->rest_app->debug();

		$currency_data_array			= $this->rest_app->get('index.php/rfq_api/currency/', '', 'application/json');
		$vendorcategory_data 			= $this->rest_app->get('index.php/rfq_api/vendorcategory/', '', 'application/json');
		$requestor_data_array	 		= $this->rest_app->get('index.php/rfq_api/requestor/', '', 'application/json');
		$purpose_data_array 			= $this->rest_app->get('index.php/rfq_api/purpose/', '', 'application/json');
		$reason_data_array	 			= $this->rest_app->get('index.php/rfq_api/reason/', '', 'application/json');
		$unit_data_array	 			= $this->rest_app->get('index.php/rfq_api/unit/', '', 'application/json');

		$invited_vendors_array	 			= $this->rest_app->get('index.php/rfq_api/get_invite_vendors/', $data, 'application/json');
		//$this->rest_app->debug();

		if($invited_vendors_array->result_count > 0)
		{
			foreach($invited_vendors_array->result as $row)
			{
				$data['invited_list'] .= '<tr>';

				$data['invited_list'] .= '<td>'.$row->VENDOR_NAME.'</td>';
				
				$data['invited_list'] .= '</tr>';
			}
		}

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
			if($position != $all_data['result'][0]->POSITION_ID)
				$data['is_open'] = ' disabled';

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
			$data['created_by']				= $all_data['result'][0]->CREATEDBY;
			$data['position_id']			= $all_data['result'][0]->POSITION_ID;
			$data['status_id']				= $all_data['result'][0]->STATUS_ID;
			$data['status_name']			= $all_data['result'][0]->STATUS_NAME;
			$data_id['user_id']				= $all_data['result'][0]->CREATED_BY;

			$appover_data 					= $this->rest_app->get('index.php/rfq_api/appover_data/', $data_id, 'application/json');

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
						$new_datas['attachment_type']	= $row2->ATTACHMENT_TYPE;
						$doc_pic = $this->rest_app->get('index.php/rfq_api/get_doc_location/', $new_datas, 'application/json');

						$bom_modal_data = array();
						$bom_modal_data['bom_file_lines'] = array();
						if ($row2->ATTACHMENT_TYPE == 1) { //BOM														
							$bom_modal_data['line_attachment_id'] = $row2->LINE_ATTACHMENT_ID;							
							$bom_modal_data['bom_file_lines'] = $this->get_bom_file_lines($row2->LINE_ATTACHMENT_ID, $this->session->userdata('vendor_id'));
							// var_dump($bom_modal_data['bom_file_lines']);
							// $btn_bom_file_view[$x] = '<input type="button" data-toggle="modal" data-target="#view_bom_file_modal_'.$bom_modal_data['line_attachment_id'].'" class="btn btn-default" style="display:'.(count($bom_modal_data['bom_file_lines']) > 0 ? 'inline-block' : 'none').';" value="BOM File">';
							$data['bom_file_modals'] .= $this->load->view('rfqb/bom_full_modal_view',$bom_modal_data, true);
						} 
						
						if($new_datas['attachment_type'] == 1){
							$image = '<a href="#" data-toggle="modal" data-target="#view_bom_file_modal_'.$row2->LINE_ATTACHMENT_ID.'"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>' ;
						} elseif($new_datas['attachment_type'] == 3)
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$row2->FILE_PATH.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>';
						else
							$image = '<a href="#" onclick="load_attachment(\''.$row2->FILE_PATH.'\')"><img class="img-responsive image_min" src="'.base_url().$doc_pic.'" id="image'.$x.'_'.$i.'" value="" height="120px" width="120px"></a>' ;

						$display = 'style="display:inline-block;"';
						
						$attachment .= 		'
												<div id="attachment'.$x.'_'.$i.'" '.$display.'  class="dv_attachment">
													'.$image.'
													<input type="hidden" name="hidden_path_'.$x.'_'.$i.'" id="hidden_path_'.$x.'_'.$i.'" value="'.$row2->FILE_PATH.'">
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
													<label for="line_description'.$x.'">Description</label>
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
													<input type="text" id="quantity'.$x.'" name="quantity'.$x.'" class="form-control field-required" value="'.$row->QUANTITY.'">
												</div>
											</div>
										</div>

										<div class="row">
											<a style="cursor:pointer;" onclick="specsview('.$x.');">Specifications <span class="glyphicon glyphicon-modal-window"></span></a>
										</div>
										<input type="hidden" id="specs'.$x.'" name="specs'.$x.'" value="1">

										<div id="specifications'.$x.'" class="row" style="display: inline;">
											<textarea class="form-control" value="'.$row->SPECIFICATION.'" id="specs'.$x.'_text" name="specs'.$x.'_text" style="width: 100%;height: 100px;">'.$row->SPECIFICATION.'</textarea>
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
										<div id="attachment'.$x.'" class="row" style="white-space: nowrap; display: inline; overflow-x: scroll; height:200px; width: 95%;">
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
		// $this->rest_app->debug();


		
		//print_r($data['reason_data']);
		$this->load->view('rfqb/rfq_details', $data);
	}
	
	function get_bom_file_lines($line_attachment_id, $vendor_id){
		$line = $this->rest_app->get('index.php/rfq_api/vendor_line_bom_file/', array('line_attachment_id'=>$line_attachment_id, 'vendor_id'=>$vendor_id), 'application/json');
		// var_dump($line);
		return $line;
	}

	function response_creation_approval()
	{
		$data['rfx_id'] 			= $this->input->post('rfx_id');
		$data['current_status_id'] 	= $this->input->post('current_status_id');
		$data['reject_reason'] 		= $this->input->post('reject_reason');
		$data['position_id'] 		= $this->input->post('position_id');
		$data['status_type'] 		= 2;
		$next_status_data		 	= (array)$this->rest_app->get('index.php/rfq_api/next_status/', $data, 'application/json');

		$data['type']				= $this->input->post('type');

		if($data['type'] == 1)
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->NEXT_POSITION_ID; // 22=published or approved
			$data['status']				= $next_status_data['result'][0]->APPROVE_STATUS_ID; // 22=published or approved
			$email_data				 	= $this->rest_app->get('index.php/rfq_api/approval_email_data/', $data, 'application/json');
		// $this->rest_app->debug();
		}
		else
		{
			$data['nxt_position_id']	= $next_status_data['result'][0]->CURRENT_POSITION_ID; // 22=published or approved
			$data['status']				= $next_status_data['result'][0]->REJECT_STATUS_ID; // 22=published or approved
		}
		//echo $data['nxt_position_id'];

		$status = (array)$this->rest_app->put('index.php/rfq_api/response_creation_approval', $data, 'text'); 
		// $this->rest_app->debug();

		if($status['response'] === 'success')
			echo 'success';
		else
			echo 'failed';

	}

	function rfq_history_table($rfqrfb_id = null)
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['rfqrfb_id'] 	= $rfqrfb_id;

		$rs = $this->rest_app->get('index.php/rfq_api/rfq_history_tbl', $data, 'application/json');
		// $this->rest_app->debug();
			
		echo json_encode($rs);
	}

}
?>