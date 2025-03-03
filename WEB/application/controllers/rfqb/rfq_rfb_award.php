<?php
Class Rfq_rfb_award extends CI_Controller{

	function index($par)
	{
		$this->award_view($par);
	}

	function award_view($par)
	{

		$data['xz'] = $this->populate_data($par);
		$data['line'] = $this->populate_line($par);
		$data['rfq'] = $par;
		$data['part'] = $this->populate_participants($par);
		$data['response_quote_ids'] = array();
		//foreach($data['part'] as $id){
		//	$data['response_quote_ids'][] = $id->RESPONSE_QUOTE_ID;
		//}
		//echo "<pre>";
		//print_r($data['part']);
		//echo "</pre>";
		$data['user_login'] = $this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name');
		$data['date_today'] = date('m-d-Y');

		$this->load->view('rfqb/rfq_award_view',$data);
	}

	function populate_data($par)
	{

	$x = $this->session->userdata['user_id'];

	$data = array(
			'CREATED_BY' => $x,
			'RFQ_RFB_ID' => $par
		);

	$result = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/get_rfq_details', $data, '');
	// $this->rest_app->debug();
	return $result;
	}

	function populate_line($par)
	{

		$x = $this->session->userdata['user_id'];
		$data = array(
				'CREATED_BY' => $x,
				'RFQ_RFB_ID' => $par
						);

		$result = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/get_line_details', $data, '');
		$vendor_id = $this->session->userdata('vendor_id');
		if ($result && count($result)>0) {
			for($row=0;$row<count($result);$row++){
				$cur_row = (array) $result[$row];
				$quotes = $this->rest_app->get('index.php/rfq_api/bom_quote_nums/', array('line_attachment_id'=>$cur_row['LINE_ATTACHMENT_ID']), 'application/json');
				$cur_row['QUOTES'] = $quotes;
				if ($quotes && count($quotes)> 0 )
					for($quote_i=0;$quote_i<count($quotes);$quote_i++){
						$quote = $quotes[$quote_i]->QUOTE_NO;
						$bom_lines = $this->rest_app->get('index.php/rfq_api/line_bom_view/', array('line_attachment_id'=>$cur_row['LINE_ATTACHMENT_ID']), 'application/json');
						$cur_row['BOM_ROWS'.$quote] = count($bom_lines);


						if ($bom_lines && count($bom_lines)> 0 ) {
							for($bom_row=0;$bom_row<count($bom_lines);$bom_row++){
								$cur_bom = (array) $bom_lines[$bom_row];
								$costs = $this->rest_app->get('index.php/rfq_api/vendors_bom_cost/', array('line_attachment_id'=>$cur_row['LINE_ATTACHMENT_ID'], 'row_no'=>$cur_bom['ROW_NO'], 'bquote_no'=>$quote), 'application/json');
								if ($costs && ($costs)>0) {
									foreach($costs as $cost){
										$cur_bom[$cost->VENDOR_NAME] = $cost->COST;
										$cur_bom[$cost->VENDOR_NAME.' - REMARKS'] = $cost->REMARKS;
									}
								}

								// var_dump($costs);
								// array_push($cur_bom, array($costs->VENDOR_NAME=>$costs->COST));

								$json_bom = json_encode($cur_bom);
								$cur_row['BOM_'.$quote][$bom_row] = json_decode($json_bom);
							}
							// $costs = $this->rest_app->get('index.php/rfq_api/vendors_bom_cost_get/', array('line_attachment_id'=>$result[$row]->LINE_ATTACHMENT_ID, 'row_no'=>$result[$row]->ROW_NO), 'application/json');
							// if ($costs && count($costs)>0) {
								//
							// }
						}


						$json = json_encode($cur_row);
						$result[$row] = json_decode($json); //convert associative array back to object;
					}



				// $result[$row] = $cur_row;
			}
		}

		// echo '<code>'.var_dump($result).'</code>';
		// if ($echo) {
			// echo '<pre>'.json_encode($result, JSON_PRETTY_PRINT).'</pre>';
			// echo '<pre>'.json_encode($quotes, JSON_PRETTY_PRINT).'</pre>';
		// }

		return $result;


	}

	function generate_bom($line_attachment_id){
		$result = array();
		$quotes = $this->rest_app->get('index.php/rfq_api/bom_quote_nums/', array('line_attachment_id'=>$line_attachment_id), 'application/json');
		$result['QUOTES'] = $quotes;
		if ($quotes && count($quotes)> 0 ) {
			for($quote_i=0;$quote_i<count($quotes);$quote_i++){
				$quote = $quotes[$quote_i]->QUOTE_NO;
				$bom = $this->rest_app->get('index.php/rfq_api/line_bom_view/', array('line_attachment_id'=>$line_attachment_id), 'application/json');
				if ($bom && count($bom)> 0 ) {
					for($bom_row=0;$bom_row<count($bom);$bom_row++){
						$cur_bom = (array) $bom[$bom_row];
						$costs = $this->rest_app->get('index.php/rfq_api/vendors_bom_cost/', array('line_attachment_id'=>$line_attachment_id, 'row_no'=>$cur_bom['ROW_NO'], 'bquote_no'=>$quote), 'application/json');
						if ($costs && ($costs)>0) {
							foreach($costs as $cost)
								$cur_bom[$cost->VENDOR_NAME] = $cost->COST;
								$cur_bom[$cost->VENDOR_NAME.' - REMARKS'] = $cost->REMARKS;
						}

						$json_bom = json_encode($cur_bom);
						$result[$quote][$bom_row] = json_decode($json_bom);
					}
				}
			}
		}
		// echo '<pre>'.json_encode($result, JSON_PRETTY_PRINT).'</pre>';
		return $result;
	}

	function to_failed_bid($par){


	$x = $this->session->userdata['user_id'];
	$data = array(
			'CREATED_BY' => $x,
			'RFQ_RFB_ID' => $par
		);

	$result = $this->rest_app->put('index.php/rfqrfb/rfq_rfb_awarding_app/to_failed', $data, '');
	echo json_encode($result);

	}

	function populate_participants($par)
	{

		$x = $this->session->userdata['user_id'];
		$data = array(
				'CREATED_BY' => $x,
				'RFQ_RFB_ID' => $par
						);

		$result = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/getparticipants/', $data, '');
		// $this->rest_app->debug();
		//$this->rest_app->debug();
		
		return $result;

	}

	function populate_po_details($par)
	{

		$x = $this->session->userdata['user_id'];
		$data = array(
			'CREATED_BY' => $x,
			'RFQ_RFB_ID' => $par,
		);

		$result = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/getpodetails/', $data, '');
		//$this->rest_app->debug();

		return $result;

	}

	function get_version_list()
	{
		$data['qoute_id'] 	= $this->input->post('qoute_id');
		$data['order_list'] = $this->input->post('order_list');
		//$data['dropdown'] = TRUE;
		$rs = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/version_list/', $data, 'application/json');
		//$this->rest_app->debug();
		$dup = array(); 
		if($data['order_list'] == 0){
			///////////////////////////////////////////
			//foreach($rs->query2_arr as $key => $query){
			//	$dup[] = $query->QUOTE_AMOUNT;
			//}
			//$dup_num = array_count_values($dup);
			//foreach($rs->query2_arr as $key => $query){
			//	if($dup_num[$query->QUOTE_AMOUNT] > 1){
			//		$dup_num[$query->QUOTE_AMOUNT] --;
			//		unset($rs->query2_arr[$key]);
			//	}
			//}
			///////////////////////////////////////////
		}else if($data['order_list'] == 1){
			
		}
		
		/*$rs->query2_arr[1] = $rs->query2_arr[3];
		$rs->query2_arr[2] = $rs->query2_arr[4];
		unset($rs->query2_arr[3]);
		unset($rs->query2_arr[4]);
		
		$rs->query3_arr[2] = $rs->query3_arr[4];
		unset($rs->query3_arr[3]);
		unset($rs->query3_arr[4]);*/
		
		///////////////////////////////////////////
		//$this->rest_app->debug();
		//foreach($rs as &$q){
		//	// Build temporary array for array_unique
		//	$tmp = array();
		//	foreach($q as $k => $v)
		//		$tmp[$k] = $v->QUOTE_AMOUNT;
        //
		//	// Find duplicates in temporary array
		//	$tmp = array_unique($tmp);
        //
		//	// Remove the duplicates from original array
		//	foreach($q as $k => $v)
		//	{
		//		if (!array_key_exists($k, $tmp))
		//			unset($q[$k]);
		//	}
		//}
		///////////////////////////////////////////
		
		//Answer Code ends here 

		$table_list = '';
		$table_list .= '<table class="table table-bordered">';

		// foreach ($rs->query3_arr as $lowest) // get lowest quote amount
		// {
		// 	$lowest_arr[] = $lowest->QUOTE_AMOUNT;
		// }

		// $low_quote = min($lowest_arr); // lowest quote
		$quotes =array();
		$latest_version = 1;
		foreach($rs->query2_arr as $row){
			if($row->VERSION > $latest_version){
				$latest_version = $row->VERSION;
			}
		}
		//Get the lowest quote from all  in the latest version
		foreach($rs->query2_arr as $row){
			if($latest_version == $row->VERSION ){
				$quotes[] = $row->QUOTE_AMOUNT;
			}
		}
		$lowest_q = min($quotes);
		//echo "<pre>";
		//print_r($lowest_q);
		//echo "</pre>";
		foreach ($rs->query2_arr as $row)
		{
			if ($row->ATTACHMENT_PATH != null)
				$attach = '<a href="#" onclick="load_attachment(\''.$row->ATTACHMENT_PATH.'\')">Attachment</a>';
			else
				$attach = 'None';

			// if ($low_quote == $row->QUOTE_AMOUNT)
			// 	$td_bg = 'bgcolor="yellow"';
			// else
				$td_bg = '';

			$table_list .= '<tr>';
			$table_list .= '<td></td>';
			$table_list .= '<td>Version '.$row->VERSION.' - '.$row->DATE_CREATED.'</td>';
			$table_list .= '</tr>';
			$table_list .= '<tr>';
			$table_list .= '<td>Price:</td>';
			
			//Check if lowest
			if($lowest_q == $row->QUOTE_AMOUNT && $latest_version == $row->VERSION){
				$table_list .= '<td style="background-color:yellow;">'.number_format($row->QUOTE_AMOUNT,2).'</td>';
			}else{
				$table_list .= '<td '.$td_bg.'>'.number_format($row->QUOTE_AMOUNT,2).'</td>';
			}
			
			$table_list .= '</tr>';
			$table_list .= '<tr>';
			$table_list .= '<td>Delivery Lead Time:</td>';
			$table_list .= '<td>'.$row->LEAD_TIME.'</td>';
			$table_list .= '</tr>';
			$table_list .= '<tr>';
			$table_list .= '<td>Counter Offer:</td>';
			$table_list .= '<td><textarea class="grow_text" style="max-height:150px" disabled>'.$row->COUNTER_OFFER.'</textarea></td>';
			$table_list .= '</tr>';
			$table_list .= '<tr>';
			$table_list .= '<td>Attachments:</td>';
			$table_list .= '<td>'.$attach.'</td>';
			$table_list .= '</tr>';
			$table_list .= '<tr>';
			$table_list .= '<td></td>';
			$table_list .= '<td></td>';
			$table_list .= '</tr>';

		}
		$table_list .= '</table>';
		echo $table_list;
	}

	function save_award()
	{
		$data 					= $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');

		$rs = $this->rest_app->put('index.php/rfqrfb/rfq_rfb_awarding_app/save_award', $data, 'text');
	
		// $this->rest_app->debug();

		if ($rs->send_notif == true)
		{
			$post_data['user_id'] = $this->session->userdata('user_id');

			$post_data['type'] 			= 'notification';
			$post_data['recipient_id'] 	= $rs->notif_data->recipient_id;
			$post_data['mail_subj'] 	= $rs->notif_data->subject;
			$post_data['mail_topic'] 	= $rs->notif_data->topic;
			$post_data['mail_body'] 	= $rs->notif_data->message;
			$post_data['rfqrfb_id'] 	= $rs->notif_data->rfqrfb_id;
			// print_r($post_data);
			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
			// $this->rest_app->debug();
		}
	}

	function display_approval($rfq)
	{
		$data['xz'] = $this->populate_data($rfq);
		$data['line'] = $this->populate_line($rfq);
		$data['rfq'] = $rfq;
		$data['part'] = $this->populate_participants($rfq);
		$data['for_approval'] = true;


		$data['user_login'] = $this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name');
		$data['date_today'] = date('m-d-Y');

		$this->load->view('rfqb/rfq_award_view',$data);
	}

	function approve_reject_awarded()
	{
		$data 					= $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');

		$rs = $this->rest_app->put('index.php/rfqrfb/rfq_rfb_awarding_app/approve_reject_awarded', $data, 'text');
		// $this->rest_app->debug();

		if ($rs->send_notif == true)
		{
			$post_data['user_id'] = $this->session->userdata('user_id');

			$post_data['type'] 			= 'notification';
			$post_data['recipient_id'] 	= $rs->notif_data->recipient_id;
			$post_data['mail_subj'] 	= $rs->notif_data->subject;
			$post_data['mail_topic'] 	= $rs->notif_data->topic;
			$post_data['mail_body'] 	= $rs->notif_data->message;
			$post_data['rfqrfb_id'] 	= $rs->notif_data->rfqrfb_id;
			// print_r($post_data);
			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
			// $this->rest_app->debug();
		}

		if ($rs->send_notif_response == true)
		{
			$post_data2['user_id'] = $this->session->userdata('user_id');

			$post_data2['type'] 			= 'notification';
			$post_data2['recipient_id'] 	= $rs->notif_data_response->recipient_id;
			$post_data2['mail_subj'] 	= $rs->notif_data_response->subject;
			$post_data2['mail_topic'] 	= $rs->notif_data_response->topic;
			$post_data2['mail_body'] 	= $rs->notif_data_response->message;
			$post_data2['rfqrfb_id'] 	= $rs->notif_data_response->rfqrfb_id;
			// print_r($post_data2);
			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data2, '');
			// $this->rest_app->debug();
		}
	}

	function display_awarded($rfq)
	{
		$data['xz'] = $this->populate_data($rfq);
		$data['line'] = $this->populate_line($rfq);
		$data['po_details'] = $this->populate_po_details($rfq);
		$data['rfq'] = $rfq;
		$data['part'] = $this->populate_participants($rfq);
		$data['for_approval'] = true;
		$data['awarded_view'] = true;
		$data['ven_list'] = $this->get_all_awarded_vendor($rfq);
		$data['user_login'] = $this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name');
		$data['date_today'] = date('m-d-Y');
		//echo "<pre>";
		//print_r($data['part']);
		//echo "</pre>";
		$this->load->view('rfqb/rfq_award_view',$data);
	}

	function get_all_awarded_vendor($rfq)
	{


		$data['rfq'] = $rfq;
		$rs = $this->rest_app->get('index.php/rfqrfb/rfq_rfb_awarding_app/vendor_list/', $data, 'application/json');
	

		return $rs;
	}

	/*
	OLD GENERATE PDF
	function generate_pdf()
	{
		$rfq = $this->input->post('rfq_id');
		$data['xz'] = $this->populate_data($rfq);
		$data['line'] = $this->populate_line($rfq);
		$data['rfq'] = $rfq;
		$data['part'] = $this->populate_participants($rfq);
		$data['for_approval'] = true;
		$data['awarded_view'] = true;

		// $this->load->view('rfqb/award_pdf_view',$data);

		$this->load->library('mpdf_gen');
		$pdf_filename = 'pdf-price-compare-'.date('YmdHis').'-'.$rfq.'.pdf';
		$stylecss = file_get_contents(base_url().'assets/css/pdf_award.css'); //Style for pdf

	 	$mpdf = new mPDF('c', 'A4-L');
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    $mpdf->WriteHTML($stylecss,1); //Styling css
	    $mpdf->WriteHTML($this->load->view('rfqb/award_pdf_view',$data,true),0); //Html template
	    $mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download
	}

	*/
	function generate_pdf($id = null)
	{
		$rfq = $id;
		$data['xz'] = $this->populate_data($rfq);
		$data['line'] = $this->populate_line($rfq);
		$data['po_details'] = $this->populate_po_details($rfq);
		$data['rfq'] = $rfq;
		$data['part'] = $this->populate_participants($rfq);
		$data['for_approval'] = true;
		$data['awarded_view'] = true;

	   // $this->load->view('rfqb/award_pdf_view',$data);

	   //$this->load->library('mpdf_gen');
	   //$pdf_filename = 'pdf-price-compare-'.date('YmdHis').'-'.$rfq.'.pdf';
	   //$stylecss = file_get_contents(base_url().'assets/css/pdf_award.css'); //Style for pdf
	   //
	   //$mpdf = new mPDF('c', 'A4-L');
	   //$mpdf->useSubstitutions = FALSE;
	   //$mpdf->simpleTables = TRUE; //Disable for complex table
	   //$mpdf->packTableData = TRUE;
	   //$mpdf->WriteHTML($stylecss,1); //Styling css
	   //$mpdf->WriteHTML($this->load->view('rfqb/award_pdf_view',$data,true),0); //Html template
	   //$mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download
		$this->load->view('rfqb/price_comparison_pdf_view.php', $data);
	}

	function generate_pdf_v2($line_attachment_id = 0,$title = ''){
		$data['title'] = urldecode ($title);
		$data['line_attachment_id'] = $line_attachment_id;
		$data['result'] = $this->generate_bom($line_attachment_id);

		// echo '<pre>'.json_encode($data, JSON_PRETTY_PRINT).'</pre>';


		// $this->load->view('rfqb/bom_pdf_v2_view', $data);


		$this->load->library('mpdf_gen');
		$pdf_filename = $data['title'].'-compare-'.date('YmdHis').'.pdf';


	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    $mpdf->WriteHTML($this->load->view('rfqb/bom_pdf_v2_view',$data,true),0); //Html template
	    $mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download //'public/downloads/'.

		//$pdf_filename
	}

	function save_po_details()
	{
		$data 					= $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$rs = $this->rest_app->post('index.php/rfqrfb/rfq_rfb_awarding_app/save_po_details', $data, '');
		$this->rest_app->debug();

		var_dump($rs);

	}
}
?>
