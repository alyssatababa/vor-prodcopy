<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class rfq_awarding_model extends CI_Model{

		function get_rfq_details($par)
		{

			$data = array(
				'RFQRFB_ID' => $par['RFQ_RFB_ID']
				);


		/*	$this->db->select("U.TITLE,
				U.DATE_CREATED,
				U.DELIVERY_DATE,
				R.REQUESTOR
				U.SUBMISSION_DEADLINE,
				S.USER_FIRST_NAME,
				S.USER_MIDDLE_NAME,
				S.USER_LAST_NAME,
				C.ABBREVIATION,
				A.STATUS_ID,
				A.POSITION_ID",
				false);
			$this->db->from('SMNTP_RFQRFB_STATUS A');
			$this->db->join('SMNTP_RFQRFB U', 'U.RFQRFB_ID = A.RFQRFB_ID','LEFT');
			$this->db->join('SMNTP_USERS S', 'S.USER_ID = U.CREATED_BY','LEFT');
			$this->db->join('SMNTP_RFX_REQUESTORs R', 'R.REQUESTOR_ID = U.','LEFT');
			$this->db->join('SMNTP_RFX_CURRENCY C', 'C.CURRENCY_ID = U.CURRENCY_ID','LEFT');
			
			// $this->db->limit(1);

			*/

			$this->db->select('*');
			$this->db->where($data);
			$this->db->from('SMNTP_RFQRFB_AWARDING');
			$result = $this->db->get();
			return $result->result_array();
		}

		function get_line($par)
		{

			$data = array(
			'B.RFQRFB_ID' => $par['RFQ_RFB_ID']
				);

			$this->db->select('B.RFQRFB_LINE_ID,A.CATEGORY_ID,A.CATEGORY_NAME,B.DESCRIPTION,B.UNIT_OF_MEASURE,B.QUANTITY,B.SPECIFICATION, AT.LINE_ATTACHMENT_ID, COUNT(AB.ROW_NO) AS BOM_ROWS', false);
			$this->db->from('SMNTP_RFQRFB_LINE B');
			$this->db->join('SMNTP_CATEGORY A','A.CATEGORY_ID = B.CATEGORY_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_LINE_ATTACHMENT AT','AT.RFQRFB_LINE_ID = B.RFQRFB_LINE_ID AND AT.ATTACHMENT_TYPE=1','LEFT');
			$this->db->join('SMNTP_RFQRFB_BOM_VIEW AB','AB.LINE_ATTACHMENT_ID = AT.LINE_ATTACHMENT_ID','LEFT');
			$this->db->where($data);
			$this->db->group_by('B.RFQRFB_LINE_ID,A.CATEGORY_ID,A.CATEGORY_NAME,B.DESCRIPTION,B.UNIT_OF_MEASURE,B.QUANTITY,B.SPECIFICATION,AT.LINE_ATTACHMENT_ID');
			$this->db->order_by('B.RFQRFB_LINE_ID,A.CATEGORY_ID,A.CATEGORY_NAME,B.DESCRIPTION,B.UNIT_OF_MEASURE,B.QUANTITY,B.SPECIFICATION,AT.LINE_ATTACHMENT_ID');
			$res = $this->db->get();

			return $res->result_array();

		}


		function get_participants($par)
		{

			$data = array(
				'A.RFQRFB_ID' => $par['RFQ_RFB_ID']
				);
				
			$this->db->select("A.VENDOR_ID,B.VENDOR_NAME,SRQ.QUOTE_AMOUNT,SRQ.LINE_ID,SRQ.LEAD_TIME,SRQ.COUNTER_OFFER,SRQ.ATTACHMENT_PATH,SRQ.RESPONSE_QUOTE_ID,SRQ.SHORTLISTED,SRQ.AWARDED,B.USER_ID,A.INVITE_ID, CAST(DATE_FORMAT(SRQ.DATE_CREATED,'%m/%d/%Y %h:%i:%s %p') AS CHAR) AS FORMATTED_DATE, SRQ.VERSION", FALSE);		
			$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS A');
			$this->db->join('SMNTP_VENDOR_INVITE B','B.VENDOR_INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_ACKNOWLEDGEMENT ASDF','ASDF.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE SRR','SRR.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE_QUOTE SRQ','SRQ.RESPONSE_ID = SRR.RESPONSE_ID','LEFT');
			$this->db->where('ASDF.PARTICIPATE = 1 AND ASDF.RFQRFB_ID ='.$par['RFQ_RFB_ID'].'');
			$this->db->where('SRQ.RFQRFB_ID = '.$par['RFQ_RFB_ID'].'');
			//$this->db->where('SRR.');
			$this->db->where($data);

			if (array_key_exists('rfq_line_id', $par))
				$this->db->where('SRQ.LINE_ID', $par['rfq_line_id']);
			//SRQ.LINE_ID ASC, 
			if (array_key_exists('rfq_line_id', $par)){
				$this->db->order_by('A.VENDOR_ID, SRQ.RESPONSE_QUOTE_ID DESC, SRQ.LINE_ID,SRQ.VERSION DESC');
			}else{
				$this->db->order_by('SRQ.LINE_ID ASC, A.VENDOR_ID, SRQ.RESPONSE_QUOTE_ID DESC,SRQ.VERSION DESC');
			}
			
			//Sort By Price
			//$this->db->order_by('A.VENDOR_ID, SRQ.QUOTE_AMOUNT DESC, SRQ.RESPONSE_QUOTE_ID DESC, SRQ.LINE_ID,SRQ.VERSION DESC');
			$res = $this->db->get();
			// echo $this->db->last_query();
			return $res->result_array();

		}

		function get_podetails($par)
		{

			$data = array(
				'A.RFQRFB_ID' => $par['RFQ_RFB_ID']
				);

			return $this->db->query(
				'SELECT PD.*, CAST(DATE_FORMAT(PD.DATE_CREATED,"%m-%d-%Y") AS CHAR) AS FORMATTED_DATE, 
				       U.USER_FIRST_NAME, 
				       U.USER_MIDDLE_NAME, 
				       U.USER_LAST_NAME 
					FROM SMNTP_RFQRFB_PO_DETAILS PD
					INNER JOIN SMNTP_USERS U ON PD.CREATED_BY = U.USER_ID
					WHERE RFQRFB_ID = ? AND ACTIVE = 1 
					ORDER BY PO_DETAIL_ID ASC',
					$data
			)->result_array();

		}

		function check_po_details_if_exists($data){
			return $this->db->query(
				'SELECT PO_DETAIL_ID 
					FROM SMNTP_RFQRFB_PO_DETAILS 
				 	WHERE RFQRFB_ID = ? AND 
				 			LINE_ID = ? AND 
				 			COMPANY = ? AND 
				 			PO_NUMBER = ? AND 
				 			QUANTITY = ? AND 
				 			CREATED_BY = ? AND ACTIVE = 0',
				array(
					$data['RFQRFB_ID'],
					$data['LINE_ID'],
					$data['COMPANY'],
					$data['PO_NUMBER'],
					$data['QUANTITY'],
					$data['CREATED_BY']
				)
			)->result();
		}

		function get_version_list($data)
		{
			$min_qoute = '';

			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
			$this->db->where('RESPONSE_QUOTE_ID', $data['qoute_id']);

			$query = $this->db->get();
			$row = $query->row();
			$min_quote_version = null;
			if ($data['order_list'] == 1) // lowest qoute amount
			{
				$this->db->select('MIN(QUOTE_AMOUNT) as QUOTE_AMOUNT, VERSION', false); //Add Version
				$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
				if(!empty($data['DROPDOWN']) && $data['DROPDOWN'] != true){
					$this->db->where('SHORTLISTED', 1); // get only shortlisted
				}
				$this->db->where('INVITE_ID', $row->INVITE_ID);
				$this->db->where('RFQRFB_ID', $row->RFQRFB_ID);
				$this->db->where('LINE_ID', $row->LINE_ID);
				$this->db->group_by(array('QUOTE_AMOUNT', 'VERSION'));  // group by since we have aggregate function
				$this->db->order_by('VERSION DESC, QUOTE_AMOUNT ASC');
				$rs_query = $this->db->get();
				$min_qoute = $rs_query->row()->QUOTE_AMOUNT;
				$min_quote_version = $rs_query->row()->VERSION; //get version
			}

			
			$this->db->select('	RESPONSE_QUOTE_ID,
								RESPONSE_ID,
								RFQRFB_ID,
								LINE_ID,
								QUOTE,
								QUOTE_AMOUNT,
								LEAD_TIME,
								COUNTER_OFFER,
								ATTACHMENT_PATH,
								CAST(DATE_FORMAT(DATE_CREATED,"%m/%d/%Y %h:%i:%s %p") AS CHAR) AS DATE_CREATED,
								ACTIVE,
								INVITE_ID,
								SHORTLISTED,
								AWARDED,
								VERSION', false);
			$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
			
			if(!empty($data['DROPDOWN']) && $data['DROPDOWN'] != true){
				$this->db->where('SHORTLISTED', 1); // get only shortlisted
			}
			
			$this->db->where('INVITE_ID', $row->INVITE_ID);
			$this->db->where('RFQRFB_ID', $row->RFQRFB_ID);
			$this->db->where('LINE_ID', $row->LINE_ID);
			//$this->db->order_by('RESPONSE_QUOTE_ID','DESC'); //Order by latest qoute

			if ($data['order_list'] == 1){
				//Get all the version 
				//$this->db->where('QUOTE_AMOUNT', $min_qoute);
				$this->db->where('VERSION', $min_quote_version);
			}

			$this->db->order_by('RESPONSE_QUOTE_ID DESC, VERSION DESC');
			
			//Sort by price
			//$this->db->order_by(' VERSION DESC, QUOTE_AMOUNT DESC , RESPONSE_QUOTE_ID DESC,LINE_ID');

			$query2 = $this->db->get();
			// echo $this->db->last_query();
			$data = [
						'RFQ_RFB_ID' => $row->RFQRFB_ID,
						'rfq_line_id' => $row->LINE_ID
					];
			$query3 = $this->get_participants($data);

			$rs['query2_arr'] = $query2->result_array();
			$rs['query3_arr'] = $query3;

			return $rs;
		}

		function get_unawarded($rfq_id)
		{
			$data = array(
				'A.RFQRFB_ID' => $rfq_id
				);

			$this->db->select('A.VENDOR_ID,B.VENDOR_NAME,SRQ.QUOTE_AMOUNT,SRQ.LINE_ID,SRQ.LEAD_TIME,SRQ.COUNTER_OFFER,SRQ.ATTACHMENT_PATH,SRQ.RESPONSE_QUOTE_ID,SRQ.SHORTLISTED,SRQ.AWARDED,SRQ.RFQRFB_ID,SRQ.INVITE_ID');		
			$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS A');
			$this->db->join('SMNTP_VENDOR_INVITE B','B.VENDOR_INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_ACKNOWLEDGEMENT ASDF','ASDF.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE SRR','SRR.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE_QUOTE SRQ','SRQ.RESPONSE_ID = SRR.RESPONSE_ID','LEFT');
			$this->db->where('ASDF.PARTICIPATE = 1 AND ASDF.RFQRFB_ID ='.$rfq_id.' AND SRQ.SHORTLISTED = 1 AND SRQ.AWARDED = 0');
			$this->db->where('SRQ.RFQRFB_ID = '.$rfq_id.'');
			//$this->db->where('SRR.');
			$this->db->where($data);
			$this->db->order_by('A.VENDOR_ID,SRQ.LINE_ID');
			$res = $this->db->get();
			return $res->result_array();
		}

		function disable_podetails($rfq_id){
			return $this->db->query(
				'UPDATE SMNTP_RFQRFB_PO_DETAILS SET Active = 0 WHERE RFQRFB_ID = ?', array($rfq_id)
			);
		}

		function enable_podetails($rfq_id){
			return $this->db->query(
				'UPDATE SMNTP_RFQRFB_PO_DETAILS SET Active = 1 WHERE PO_DETAIL_ID = ?', array($rfq_id)
			);
		}

		function select_query($table,$where,$search)
		{
			
			$this->db->select($search);
			$this->db->from($table);
			$res = $this->db->where($where)->get();
			return $res->result_array();

		}

		function select_vendors($rfq_id)
		{

				$data = array(
				'A.RFQRFB_ID' => $rfq_id
				);

			$this->db->select('A.VENDOR_ID,B.VENDOR_NAME');		
			$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS A');
			$this->db->join('SMNTP_VENDOR_INVITE B','B.VENDOR_INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_ACKNOWLEDGEMENT ASDF','ASDF.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE SRR','SRR.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE_QUOTE SRQ','SRQ.RESPONSE_ID = SRR.RESPONSE_ID','LEFT');
			$this->db->where('ASDF.PARTICIPATE = 1 AND ASDF.RFQRFB_ID ='.$rfq_id.' AND SRQ.SHORTLISTED = 1 AND SRQ.AWARDED = 1');
			$this->db->where('SRQ.RFQRFB_ID = '.$rfq_id.'');
			//$this->db->where('SRR.');
			$this->db->where($data);
			$this->db->order_by('A.VENDOR_ID,SRQ.LINE_ID');
			$res = $this->db->get();

			$vl = $res->result_array();
			$arr2 = array();
			// foreach ($vl['VENDOR_ID'] as $value) {
			// 	if(!in_array($arr2, $value)){
			// 		array_push($arr2, $value);
			// 	} 
			// }
			foreach ($vl as $key => $value) {
				if(!in_array($value, $arr2)){
					array_push($arr2, $value);
				}
				
			}



			// $new = array_unique($vl);

			return $arr2;
		}
}
?>