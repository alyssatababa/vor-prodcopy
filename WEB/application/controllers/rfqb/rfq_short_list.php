	<?php
Class Rfq_short_list extends CI_Controller{


	function index($rfq)
	{

		$data['rfq'] = $this->get_rfq_data($rfq);
		$data['id'] = $rfq;
		$data['line'] = $this->populate_line($rfq);
		$data['part'] = $this->populate_participants($rfq);

		$this->load->view('rfqb/rfq_short_list_view',$data);

	}


	function get_rfq_data($rfq)
	{
		$x = $this->session->userdata['user_id'];
		$data = array(
			'RFQRFB_ID' => $rfq,
			'CREATED_BY' =>$x
			);

		 $result = $this->rest_app->get('index.php/rfqrfb/rfqrfb_shortlist/details/', $data, '');
		 return $result;


	}

	function populate_line($par = 1096)
	{

		$x = $this->session->userdata['user_id'];
		$data = array(
				'CREATED_BY' => $x,
				'RFQ_RFB_ID' => $par
						);

		$result = $this->rest_app->get('index.php/rfqrfb/rfqrfb_shortlist/getline/', $data, 'application/json');
		$vendor_id = $this->session->userdata('vendor_id');


		// echo '<code>'.var_dump($result).'</code><br><br>';


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
								// $cur_bom[$cost->VENDOR_NAME] = '0.00';
								$costs = $this->rest_app->get('index.php/rfq_api/vendors_bom_cost/', array('line_attachment_id'=>$cur_row['LINE_ATTACHMENT_ID'], 'row_no'=>$cur_bom['ROW_NO'], 'bquote_no'=>$quote), 'application/json');
								if ($costs && ($costs)>0) {
										foreach($costs as $cost) {
											$cur_bom[$cost->VENDOR_NAME] = $cost->COST;
										// $cur_bom[$cost->VENDOR_NAME.'1'] = $cost->COST;
										// $cur_bom[$cost->VENDOR_NAME.'2'] = $cost->COST;
											$cur_bom[$cost->VENDOR_NAME.' - REMARKS'] = $cost->REMARKS;
										}
								}

								// var_dump($costs);
								// array_push($cur_bom, array($costs->VENDOR_NAME=>$costs->COST));

								// echo '<pre>'.json_encode($costs, JSON_PRETTY_PRINT).'</pre>';
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

		// echo '<pre>'.json_encode($result, JSON_PRETTY_PRINT).'</pre>';
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
							foreach($costs as $cost){
								$cur_bom[$cost->VENDOR_NAME] = $cost->COST;
								$cur_bom[$cost->VENDOR_NAME.' - REMARKS'] = $cost->REMARKS;
							}
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

	function generate_pdf($line_attachment_id = 0,$title = ''){
		$data['title'] = urldecode ($title);
		$data['line_attachment_id'] = $line_attachment_id;
		$data['bom_lines'] = $this->generate_bom($line_attachment_id);

		// echo '<pre>'.json_encode($data, JSON_PRETTY_PRINT).'</pre>';


		// $this->load->view('rfqb/bom_pdf_view', $data);


		$this->load->library('mpdf_gen');
		$pdf_filename = $data['title'].'-compare-'.date('YmdHis').'.pdf';


	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    $mpdf->WriteHTML($this->load->view('rfqb/bom_pdf_view',$data,true),0); //Html template
	    $mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download //'public/downloads/'.

		// $file_url = base_url().'public/downloads/'.rawurlencode($pdf_filename);
		// header('Content-Type: application/pdf');
		// header("Content-Transfer-Encoding: Binary");
		// header("Content-disposition: attachment; filename=\"".$pdf_filename."\"");
		// readfile($file_url);

		//$pdf_filename
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



		// $this->load->library('pdf');
		// $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		// $pdf->SetTitle('My Title');
		// $pdf->SetHeaderMargin(30);
		// $pdf->SetTopMargin(20);
		// $pdf->setFooterMargin(20);
		// $pdf->SetAutoPageBreak(true);
		// $pdf->SetAuthor('Author');
		// $pdf->SetDisplayMode('real', 'default');
		// $pdf->AddPage();

		// // $pdf->Write(0, $this->load->view('rfqb/bom_pdf_v2_view',$data,true));
		// $pdf->writeHTML($this->load->view('rfqb/bom_pdf_v2_view',$data,true), true, false, true, false, '');
		// ob_clean();
		// $pdf->Output('My-File-Name.pdf', 'I');


	}


	function populate_participants($par)
	{

		$x = $this->session->userdata['user_id'];
		$data = array(
				'CREATED_BY' => $x,
				'RFQ_RFB_ID' => $par
						);

		$result = $this->rest_app->get('index.php/rfqrfb/rfqrfb_shortlist/getparticipants/', $data, '');
		//$this->rest_app->debug();

		return $result;

	}

	function save_shortlisted()
	{
		$data 					= $_POST;
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');

		$rs = $this->rest_app->put('index.php/rfqrfb/rfqrfb_shortlist/save_shortlisted', $data, 'text');
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
		$data['rfq'] = $this->get_rfq_data($rfq);
		$data['id'] = $rfq;
		$data['line'] = $this->populate_line($rfq);
		$data['part'] = $this->populate_participants($rfq);
		$data['for_approval'] = true;

		$this->load->view('rfqb/rfq_short_list_view',$data);
	}

	function approve_reject_shortlisted()
	{
		$data['user_id'] 		= $this->session->userdata('user_id');
		$data['position_id'] 	= $this->session->userdata('position_id');
		$data['rfq_id'] 		= $this->input->post('rfq_id');
		$data['action'] 		= $this->input->post('action');
		$data['chk_shortlist'] 	= $this->input->post('chk_shortlist');
		$data['remarks'] 		= $this->input->post('remarks');

		$rs = $this->rest_app->put('index.php/rfqrfb/rfqrfb_shortlist/approve_reject_shortlisted', $data, 'text');
		// $this->rest_app->debug();
		if ($rs->send_notif == true)
		{
			$post_data['user_id'] = $this->session->userdata('user_id');

			$post_data['type'] 			= 'notification';
			$post_data['recipient_id'] 	= $rs->recipients;
			$post_data['mail_subj'] 	= $rs->notif_data->subject;
			$post_data['mail_topic'] 	= $rs->notif_data->topic;
			$post_data['mail_body'] 	= $rs->notif_data->message;
			$post_data['rfqrfb_id'] 	= $rs->notif_data->rfqrfb_id;
			// print_r($post_data);
			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data, '');
			// $this->rest_app->debug();
		}

		if ($rs->sent_notif_buyer == true)
		{
			$post_data2['user_id'] = $this->session->userdata('user_id');

			$post_data2['type'] 			= 'notification';
			$post_data2['recipient_id'] 	= $rs->notif_data_buyer->recipient_id;
			$post_data2['mail_subj'] 		= $rs->notif_data_buyer->subject;
			$post_data2['mail_topic'] 		= $rs->notif_data_buyer->topic;
			$post_data2['mail_body'] 		= $rs->notif_data_buyer->message;
			$post_data2['rfqrfb_id'] 		= $rs->notif_data_buyer->rfqrfb_id;
			// print_r($post_data2);
			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data2, '');
			// $this->rest_app->debug();
		}
	}



}
?>
