<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_reports extends CI_Controller {

	public function index()
	{
		$data['business_type'] = $this->session->userdata('business_type');
		$data['position_id'] = $this->session->userdata('position_id');
		$data['user_id'] = $this->session->userdata('user_id');
		$data['category_list'] = $this->rest_app->get('index.php/vendor/invitecreation_api/category_list', $data, 'application/json');
		
		
		$this->load->view('vendor/vendor_reports',$data);
	}
	
	//added JRM - June 21, 2021
	function get_usernames(){
		$result = $this->rest_app->get('index.php/vendor/vendor_reports/get_usernames');
		header("Content-Type:application/json");
		echo json_encode($result);
	}
	//added JRM - June 25, 2021
	function get_vendor_codes(){
		$result = $this->rest_app->get('index.php/vendor/vendor_reports/get_vendor_codes');
		header("Content-Type:application/json");
		echo json_encode($result);
	}
	// MSF 2022-12-22
	function get_active_inacvtive_user($user_type,$user_status){
		$data['user_type'] = $user_type;
		$data['user_status'] = $user_status;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_active_inacvtive_user', $data, '');
		header("Content-Type:application/json");
		echo json_encode($data['result_data']);
	}

	function get_contact_persons($date){
		$data['date'] = $date;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_contact_persons', $data, '');
		//print_r($data);
		header("Content-Type:application/json");
		echo json_encode($data['result_data']);
	}	

	function get_expired_invites($date_from, $date_to, $user_id, $cat_filter){
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['cat_filter'] = $cat_filter;
		$data['user_id'] = $user_id;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_expired_invites', $data, '');
		//return $data['date_from'];
		return $data['result_data'];
	}

	function get_buyer_senmer_email(){
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_buyer_senmer_email', '', '');
		//return $data['date_from'];
		return json_encode($data['result_data']);
	}

	function get_deactivated_account($date_from, $date_to, $user_id, $cat_filter){
		$data['cat_filter'] = $cat_filter;
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['user_id'] = $user_id;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_deactivated_account', $data, '');
		return $data['result_data'];
	}

	// Added MSF - 20191126 (IJR-10619)
	function get_completed_accounts($date_from, $date_to, $user_id, $cat_filter){
		$data['cat_filter'] = $cat_filter;
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['user_id'] = $user_id;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_completed_accounts', $data, '');
		return $data['result_data'];
	}

	// Added MSF - 20191126 (IJR-10619)
	function get_validation_schedule($date_from, $date_to, $user_id, $cat_filter){
		$data['cat_filter'] = $cat_filter;
		$data['date_from'] = $date_from;
		$data['date_to'] = $date_to;
		$data['user_id'] = $user_id;
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_validation_schedule', $data, '');
		return $data['result_data'];
	}

	function get_expired_config(){
		$data['result_data'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_expired_config', '', '');
		$this->rest_app->debug();
		return json_encode($data['result_data']);
	}

	public function generate_report_pdf($report, $date_from, $date_to, $cat_filter){
		$user_id = $this->session->userdata('user_id');
		$position_id = $this->session->userdata('position_id');

		if ($report == 1){
	        $data['title'] = urldecode ("EXPIRED INVITES");
	        $data['results'] = $this->get_expired_invites($date_from, $date_to, $user_id, $cat_filter);
	    }else if ($report == 2){
	        $data['title'] = urldecode ("DEACTIVATED ACCOUNTS");
	        $data['results'] = $this->get_deactivated_account($date_from, $date_to, $user_id, $cat_filter);
	    }



	    // echo $data['results'];

		$this->load->library('mpdf_gen');
		$pdf_filename = $data['title']." ".date('YmdHis').'.pdf';

		// $this->load->view('vendor/report_pdf_view',$data);

	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    // $mpdf->shrink_tables_to_fit = 1;
	    $mpdf->WriteHTML($this->load->view('vendor/report_pdf_view',$data,true),0); //Html template
	    $mpdf->Output($pdf_filename,'D');  // F = file creation, D = direct download 



		//$email['to'] = 'pagaraojustineprice@yahoo.com';
        //$email['subject'] = 'Vendor Reports';
        //$email['content'] = 'Please see attached for monthly vendor reports.';
        //$this->send_email_notification($email);

	}

	public function generate_report_excel($report, $date_from, $date_to, $cat_filter){
		$user_id = $this->session->userdata('user_id');
		$position_id = $this->session->userdata('position_id');

		$this->load->library("excel");
		$object = new PHPExcel();
		$object->setActiveSheetIndex(0);
		$column = 0;

		$style = array('font' => array('size' => 11,'bold' => true));
		$headerStyle = array('font' => array('size' => 16,'bold' => true));

		if ($report == 1){
	        $data['title'] = urldecode ("EXPIRED INVITES");
	        $data['result'] = $this->get_expired_invites($date_from, $date_to, $user_id, $cat_filter);

	        //$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $data['result']);

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "EXPIRED INVITES");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

	        if($position_id == 4 || $position_id == 5){
				// Modified MSF - 20191118 (IJR-10618)
	        	//$table_columns = array("", "Vendor Name", "Category Name", "Inviter", "Date Expired");
				
				//Modified MSF 20191128 (NA)
	        	//$table_columns = array("", "Vendor Name", "Category Name", "Sub-Category Name", "Inviter", "Date Expired");
	        	$table_columns = array("", "Vendor Name", "Department", "Sub-Department", "Inviter", "Date Expired");
				$object->getActiveSheet()->getStyle('A3:F3')->applyFromArray($style);
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(35);
				// Modified MSF - 20191118 (IJR-10618)
				//$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(18);
	        }else{
				// Modified MSF - 20191118 (IJR-10618)
	        	//$table_columns = array("", "Vendor Name", "Department", "Date Expired");
				
				//Modified MSF 20191128 (NA)
	        	//$table_columns = array("", "Vendor Name", "Department", "Sub-Department", "Date Expired");
	        	$table_columns = array("", "Vendor Name", "Department", "Sub-Department", "Date Expired");
				$object->getActiveSheet()->getStyle('A3:E3')->applyFromArray($style);
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(35);
				// Modified MSF - 20191118 (IJR-10618)
				//$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
	    	}

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					if($position_id == 4 || $position_id == 5){
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->CATEGORY_NAME);
						// Modified MSF - 20191118 (IJR-10618)
						//$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->INVITER);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->DATE_EXPIRED);
						$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->SUB_CATEGORY_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->INVITER);
						$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->DATE_EXPIRED);
					}else{
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->CATEGORY_NAME);
						// Modified MSF - 20191118 (IJR-10618)
						//$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->DATE_EXPIRED);		
						$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->SUB_CATEGORY);
						$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->DATE_EXPIRED);	
					}
					$excel_row++;					
				}
			}
	    }else if ($report == 2){
	        $data['title'] = urldecode ("DEACTIVATED ACCOUNTS");
	        $data['result'] = $this->get_deactivated_account($date_from, $date_to, $user_id, $cat_filter);

	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "DEACTIVATED ACCOUNTS");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

	        if($position_id == 4 || $position_id == 5){
				// Modified MSF - 20191118 (IJR-10618)
	        	//$table_columns = array("", "Vendor Name", "Vendor Code", "Create Date", "Inviter", "Deactivation Date", "Reason");
				
				//Modified MSF 20191128 (NA)
				//$table_columns = array("", "Vendor Name", "Vendor Code", "Vendor Type", "Company/Department", "Category", "Create Date", "Inviter", "Deactivation Date", "Reason");
				$table_columns = array("", "Vendor Name", "Vendor Code", "Vendor Type", "Department", "Sub-Department", "Create Date", "Inviter", "Deactivation Date", "Reason");
				$object->getActiveSheet()->getStyle('A3:J3')->applyFromArray($style);
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
				// Modified MSF - 20191118 (IJR-10618)
				//$object->getActiveSheet()->getColumnDimension('E')->setWidth(35);
				//$object->getActiveSheet()->getColumnDimension('F')->setWidth(18);
				//$object->getActiveSheet()->getColumnDimension('G')->setWidth(40);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('G')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('H')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('I')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('J')->setWidth(40);
	        }else{
				// Modified MSF - 20191118 (IJR-10618)
	        	//$table_columns = array("", "Vendor Name", "Vendor Code", "Create Date", "Deactivation Date", "Reason");
				
				//Modified MSF 20191128 (NA)
	        	//$table_columns = array("", "Vendor Name", "Vendor Code", "Vendor Type", "Company/Department", "Category", "Create Date", "Deactivation Date", "Reason");
	        	$table_columns = array("", "Vendor Name", "Vendor Code", "Vendor Type", "Department", "Sub-Department", "Create Date", "Deactivation Date", "Reason");
				$object->getActiveSheet()->getStyle('A3:I3')->applyFromArray($style);
				$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
				$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
				$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
				// Modified MSF - 20191118 (IJR-10618)
				//$object->getActiveSheet()->getColumnDimension('F')->setWidth(40);
				$object->getActiveSheet()->getColumnDimension('F')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('G')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('H')->setWidth(18);
				$object->getActiveSheet()->getColumnDimension('I')->setWidth(40);
	        }

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					if($position_id == 4 || $position_id == 5){
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
						// Modified MSF - 20191118 (IJR-10618)
						//$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->CREATE_DATE);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->INVITER);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->DEACTIVATION_DATE);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->REASON);
						$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->VENDOR_TYPE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->CATEGORY_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->SUB_CATEGORY_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->CREATE_DATE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->INVITER);
						$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->DEACTIVATION_DATE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->REASON);			
					}else{
						$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
						$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
						// Modified MSF - 20191118 (IJR-10618)
						//$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->CREATE_DATE);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->DEACTIVATION_DATE);
						//$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->REASON);
						$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->VENDOR_TYPE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->CATEGORY_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->SUB_CATEGORY_NAME);
						$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->CREATE_DATE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->DEACTIVATION_DATE);
						$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->REASON);
					}
					$excel_row++;	
				}
			}
	    }
		// Added MSF - 20191126 (IJR-10619)
		else if($report == 3){ // Completed Registration
	        $data['title'] = urldecode ("COMPLETED REGISTRATION");
	        $data['result'] = $this->get_completed_accounts($date_from, $date_to, $user_id, $cat_filter);

	        //$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $data['result']);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "COMPLETED REGISTRATION");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array("", "Vendor Name", "Vendor Code", "Department", "Sub Department", "Date Updated");
			$object->getActiveSheet()->getStyle('A3:F3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(40);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->CATEGORY_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->SUB_CATEGORY_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->DATE_UPDATED);
					$excel_row++;
				}
			}

		}else if($report == 4){ // Failed to Comply w/ Validation Schedule
	        $data['title'] = urldecode ("FAILED TO COMPLY WITH VALIDATION SCHEDULE");
	        $data['result'] = $this->get_validation_schedule($date_from, $date_to, $user_id, $cat_filter);

	        //$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, $data['result']);
	        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "FAILED TO COMPLY W/ VALIDATION SCHEDULE");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array("", "Vendor Name", "Vendor Code", "Department", "Sub Department", "Schedule of Validation");
			$object->getActiveSheet()->getStyle('A3:F3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(35);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(18);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(40);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $row->ROWNUM);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->CATEGORY_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->SUB_CATEGORY_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->DISPLAY_DATE);
					$excel_row++;
				}
			}

		}else if($report == 5){ //Added JRM - June 16 2021
			$data['title'] = urldecode ("LIST OF DELETED VENDOR INVITES");
			$data['cat_filter'] = $cat_filter;
			$data['delType'] = $date_from; //vendor or by user filter
			//$data['date_to'] = $date_to;
			$data['user_id'] = $user_id;
			$data['result'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_deleted_vendor_invites', $data, '');
			
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "LIST OF DELETED VENDOR INVITES");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array("", "VENDOR_NAME", "STATUS_NAME", "DATE AND TIME DELETED", "DELETED BY", "REASON FOR DELETION");
			$object->getActiveSheet()->getStyle('A3:F3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(50);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(50);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			$c = 1;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $c++);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->STATUS_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->REMOVE_DATE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->DELETED_BY);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->REASON_FOR_DELETION);
					$excel_row++;
				}
			}
			
		}else if($report == 6){
			$strreplace = str_replace("[","(",$_POST['postIDs']);
			$strreplace = str_replace("]",")",$strreplace);
			// $strreplace = str_replace("\"","",$strreplace);
			$data['title'] = urldecode ("LIST OF PENDING INVITES");
			$data['cat_filter'] = $cat_filter;
			$data['userIDs'] = $strreplace;
			$data['user_id'] = $user_id;
			$data['result'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_pending_invites', $data, '');
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "LIST OF PENDING INVITES");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array("", "USERNAME", "FULL_NAME", "VENDOR_NAME", "STATUS_ID", "STATUS_NAME", "REGISTRATION_TYPE");
			$object->getActiveSheet()->getStyle('A3:G3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(30);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(40);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			$c = 1;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $c++);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->USERNAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->FULL_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->STATUS_ID);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->STATUS_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->REGISTRATION_TYPE);
					$excel_row++;
				}
			}
			
		}else if($report == 7){ //Added JRM - June 25 2021
			
			$strreplace = str_replace("[","(",$_POST['postIDs']);
			$strreplace = str_replace("]",")",$strreplace);
			// $strreplace = str_replace("\"","'",$strreplace);
			$data['VCodes'] = $strreplace;
	        $data['title'] = urldecode ("LIST OF CONTACT PERSONNEL PER VENDOR");
	        $data['result'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_contacts_per_vendor', $data, '');
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "LIST OF CONTACT PERSONNEL PER VENDOR");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array("", "VENDOR NAME", "VENDOR CODE", "DETAILS", "FIRST NAME", "MIDDLE NAME", "LAST NAME", "POSITION");
			$object->getActiveSheet()->getStyle('A3:H3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(30);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(20);
			
			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}
			
			$excel_row = 4;
			$c = 1;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $c++);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->DETAILS);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->FIRST_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->MIDDLE_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->LAST_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->POSITION);
					$excel_row++;
				}
			}
		}else if($report == 8){ // MSF 2022-12-23
			$strreplace = str_replace("[","(",$_POST['postIDs']);
			$strreplace = str_replace("]",")",$strreplace);
			// $strreplace = str_replace("\"","",$strreplace);
			$data['title'] = urldecode ("List of Active and Inactive Users");
			$data['cat_filter'] = $cat_filter;
			$data['userIDs'] = $strreplace;
			$data['user_id'] = $user_id;
			$data['result'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_active_inactive_report', $data, '');
			//print_r($data['result']);
			//exit();
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "List of Active and Inactive Users");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array(" ", "Login ID", "Username", "Position", "User Type", "Status", "Effective Date");
			$object->getActiveSheet()->getStyle('A3:G3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			$c = 1;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $c++);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->LOGIN_ID);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->USER_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->POSITION_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->USER_TYPE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->USER_STATUS);
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->EFFECTIVE_DATE);
					$excel_row++;
				}
			}
			
		}else if($report == 9){ // MSF 2022-12-23
			$strreplace = str_replace("[","(",$_POST['postIDs']);
			$strreplace = str_replace("]",")",$strreplace);
			// $strreplace = str_replace("\"","",$strreplace);
			$data['title'] = urldecode ("List of Contact Person per Vendor");
			$data['cat_filter'] = $cat_filter;
			$data['vendorIDs'] = $strreplace;
			$data['user_id'] = $user_id;
			$data['date_from'] = $date_from;
			$data['date_to'] = $date_to;
			$data['result'] = $this->rest_app->post('index.php/vendor/vendor_reports/get_contact_person_per_vendor', $data, '');
			//print_r($data['result']);
			//exit();
			
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "List of Contact Person per Vendor");
	        $object->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerStyle);

			$table_columns = array(" ", "VENDOR NAME","VENDOR CODE","VENDOR TYPE","DEPARTMENT","ADDRESS","FIRST NAME","MIDDLE NAME","LAST NAME","POSITION","EMAIL","MOBILE NO","TELEPHONE NO","FAX NO","SM VENDOR SYSTEM");
			$object->getActiveSheet()->getStyle('A3:O3')->applyFromArray($style);
			$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
			$object->getActiveSheet()->getColumnDimension('B')->setWidth(40);
			$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('D')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('E')->setWidth(15);
			$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('G')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('H')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('I')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('J')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('K')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('L')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('M')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('N')->setWidth(25);
			$object->getActiveSheet()->getColumnDimension('O')->setWidth(25);

			foreach($table_columns as $field)
			{
				$object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
				$column++;
			}

			$excel_row = 4;
			$c = 1;
			if(is_array($data['result'])){
				foreach($data['result'] as $row)
				{
					$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $c++);
					$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row->VENDOR_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row->VENDOR_CODE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row->VENDOR_TYPE);
					$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row->DEPARTMENT);
					$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row->ADDRESS);
					$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row->FIRST_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row->MIDDLE_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(8, $excel_row, $row->LAST_NAME);
					$object->getActiveSheet()->setCellValueByColumnAndRow(9, $excel_row, $row->POSITION);
					$object->getActiveSheet()->setCellValueByColumnAndRow(10, $excel_row, $row->EMAIL);
					$object->getActiveSheet()->setCellValueByColumnAndRow(11, $excel_row, $row->MOBILE_NO);
					$object->getActiveSheet()->setCellValueByColumnAndRow(12, $excel_row, $row->TEL_NO);
					$object->getActiveSheet()->setCellValueByColumnAndRow(13, $excel_row, $row->FAX_NO);
					$object->getActiveSheet()->setCellValueByColumnAndRow(14, $excel_row, html_entity_decode($row->VENDOR_SYSTEM));
					$excel_row++;
				}
			}
			
		}

	    $excel_filename = $data['title']." ".date('YmdHis').'.xls';

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename=' . $excel_filename);
		$object_writer->save('php://output');

		// $email['to'] = 'pagaraojustineprice@yahoo.com';
  //       $email['subject'] = 'Vendor Reports';
  //       $email['content'] = 'Please see attached for monthly vendor reports.';
  //       $this->send_email_notification($email);

	}

	public function download_report(){

		//$sysdate = $this->vendor_reports_model->get_sysdate();
		$currdate = new DateTime('2018-01-31');
		//$currdate = new DateTime($sysdate[0]['CURRENT_DATE']);
		$sysday = $currdate->format('d');
		$sysdaynum = $currdate->format('t');

		$path = FCPATH.'vendor_reports\\';

		if (($sysdaynum == "28" && $sysday == "28") ||
		 		($sysdaynum == "29" && $sysday == "29") ||
		 		($sysdaynum == "30" && $sysday == "30") ||
		 		($sysdaynum == "31" && $sysday == "31")){


			$datefrom = $currdate->format('Y-m-01');
			$dateto = $currdate->format('Y-m-d');

	        $data1['title'] = urldecode ("EXPIRED INVITES");
	        $data1['result'] = $this->get_expired_invites($datefrom, $dateto, $cat_filter);

			$this->load->library('mpdf_gen');
			$pdf_filename_1 = $data1['title']." ".date('YmdHis').'.pdf';

		 	$mpdf = new mPDF();
		    $mpdf->useSubstitutions = FALSE;
		    $mpdf->simpleTables = TRUE; //Disable for complex table
		    $mpdf->packTableData = TRUE;
		    $mpdf->WriteHTML($this->load->view('vendor/report_pdf_view',$data1,true),0); //Html template
		    $mpdf->Output($path.'\\'.$pdf_filename_1,'F'); 

	        $data2['title'] = urldecode ("DEACTIVATED ACCOUNTS");
	        $data2['result'] = $this->get_deactivated_account($datefrom, $dateto, $cat_filter);

			$pdf_filename_2 = $data2['title']." ".date('YmdHis').'.pdf';

		 	$mpdf = new mPDF();
		    $mpdf->useSubstitutions = FALSE;
		    $mpdf->simpleTables = TRUE; //Disable for complex table
		    $mpdf->packTableData = TRUE;
		    $mpdf->WriteHTML($this->load->view('vendor/report_pdf_view',$data2,true),0); //Html template
		    $mpdf->Output($path.'\\'.$pdf_filename_2,'F'); 

		    $data['pdf1'] = $pdf_filename_1;
		    $data['pdf2'] = $pdf_filename_2;
		    $data['base_url'] = base_url();
		    echo json_encode($data);
		}else{
			echo "0";
		}
		//var_dump($path);
		//$this->response($path);
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */