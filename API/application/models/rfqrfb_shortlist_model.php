<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rfqrfb_shortlist_model extends CI_Model{

		function rfq_getdetails($par)
		{


			$data = array(
				'RFQRFB_ID' => $par['RFQRFB_ID']
				);


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

			$this->db->select('B.RFQRFB_LINE_ID,A.CATEGORY_ID,A.CATEGORY_NAME,B.DESCRIPTION,B.UNIT_OF_MEASURE,B.QUANTITY,B.SPECIFICATION, AT.LINE_ATTACHMENT_ID AS LINE_ATTACHMENT_ID, COUNT(AB.ROW_NO) AS BOM_ROWS', false);
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

			$this->db->select('A.VENDOR_ID,B.VENDOR_NAME,SRQ.QUOTE_AMOUNT,SRQ.LINE_ID,SRQ.LEAD_TIME,SRQ.COUNTER_OFFER,SRQ.ATTACHMENT_PATH,SRQ.RESPONSE_QUOTE_ID,SRQ.SHORTLISTED, CAST(DATE_FORMAT(SRQ.DATE_CREATED,"%m/%d/%Y %h:%i:%s %p") AS CHAR) AS DATE_CREATED',false);		
			$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS A');
			$this->db->join('SMNTP_VENDOR_INVITE B','B.VENDOR_INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_ACKNOWLEDGEMENT ASDF','ASDF.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->where('ASDF.PARTICIPATE = 1 AND ASDF.RFQRFB_ID ='.$par['RFQ_RFB_ID'].'');
			$this->db->join('SMNTP_RFQRFB_RESPONSE SRR','SRR.INVITE_ID = A.INVITE_ID','LEFT');
			$this->db->join('SMNTP_RFQRFB_RESPONSE_QUOTE SRQ','SRQ.RESPONSE_ID = SRR.RESPONSE_ID','LEFT');
			$this->db->where('SRQ.RFQRFB_ID = '.$par['RFQ_RFB_ID'].'');
			//$this->db->where('SRR.');
			$this->db->where($data);
			$this->db->order_by('A.VENDOR_ID,SRQ.LINE_ID, SRQ.RESPONSE_QUOTE_ID DESC'); //Sort By Latest SRQ.RESPONSE_QUOTE_ID DESC
			$res = $this->db->get();
			return $res->result_array();

		}

		function count_distinct()
		{
				 $this->db->select('COUNT(LINE_ID)');
				 $this->db->where('RFQRFB_ID = 642 AND PARTICIPATE = 1');
				 $this->db->distinct();
				 $res = $this->db->get('LINE_LIST');
				 return $res->result_array();



		}

		function get_notshortlisted($rfq_id)
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
			$this->db->where('ASDF.PARTICIPATE = 1 AND ASDF.RFQRFB_ID ='.$rfq_id.' AND SRQ.SHORTLISTED = 0');
			$this->db->where('SRQ.RFQRFB_ID = '.$rfq_id.'');
			//$this->db->where('SRR.');
			$this->db->where($data);
			$this->db->order_by('A.VENDOR_ID,SRQ.LINE_ID');
			$res = $this->db->get();
			return $res->result_array();
		}




}