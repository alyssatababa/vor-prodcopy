<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Rfq_model extends CI_Model{

		function get_line_list(){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_LINE');

			$result = $this->db->get();

			return $result->result();
		}

		function get_descriptions_list($query){
			$this->db->select('DESCRIPTION');
			$this->db->from('SMNTP_RFQRFB_LINE');
			if ($query) {
				$this->db->where("UPPER(DESCRIPTION) LIKE '%" .strtoupper($query). "%' ");
			}
			$this->db->group_by('DESCRIPTION');
			$result = $this->db->get();
			return $result->result();
		}


		function get_rfq_list(){
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB');

			$result = $this->db->get();

			return $result->result();
		}

		function get_currency(){
			/*
			$query = " select currency_id, currency, abbreviation, country from SMNTP_RFX_CURRENCY";
			$query .= " WHERE active = 1";

			$result = $this->db->query($query);*/
			$this->db->select('*');
			$this->db->from('SMNTP_RFX_CURRENCY');
			$this->db->where('ACTIVE', 1);

			$result = $this->db->get();
			$currency = array();
			/*if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$currency[$row->CURRENCY_ID] = $row->ABBREVIATION;
				}
			}*/

			return $result->result();

		}

		function get_city(){
			$this->db->select('*');
			$this->db->from('SMNTP_CITY');

			$result = $this->db->get();
			$city = array();
			/*if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$city[$row->CITY_ID] = $row->CITY_NAME;
				}
			}*/

			return $result->result_array();

		}

		function get_brands(){
			$this->db->select('*');
			$this->db->from('SMNTP_BRAND');

			$result = $this->db->get();
			/*$brands = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$brands[$row->BRAND_ID] = $row->BRAND_NAME;
				}
			}*/

			return $result->result_array();

		}

		function get_vendor_list($user_id, $list_name = ''){
			$this->db->select('(CURRENT_DATE - VL.DATE_CREATED) AS DATE_SORTING_FORMAT, VL.VENDOR_LIST_ID, VL.VENDOR_LIST_NAME, CAST(DATE_FORMAT(VL.DATE_CREATED,"%m/%d/%Y %h:%i:%s %p") AS CHAR) as DATE_CREATED, U.USER_LAST_NAME, U.USER_FIRST_NAME', FALSE);
			$this->db->from('SMNTP_VENDOR_LIST VL');
			$this->db->join('SMNTP_VENDOR_LIST_DEFN VLD', 'VL.VENDOR_LIST_ID=VLD.VENDOR_LIST_ID');
			$this->db->join('SMNTP_USERS U', 'U.USER_ID=VLD.CREATED_BY');
			$this->db->group_by('VL.VENDOR_LIST_ID, VL.VENDOR_LIST_NAME, VL.DATE_CREATED, U.USER_LAST_NAME, U.USER_FIRST_NAME');

			if(!empty($user_id) && $user_id != 0)
				$this->db->where('VL.CREATED_BY', $user_id);

			if(!empty($list_name))
				$this->db->where('upper(VL.VENDOR_LIST_NAME) LIKE \'%'.strtoupper($list_name).'%\'');

			$result = $this->db->get();

			return $result->result_array();
		}

		function get_vendor_list_participants($user_id, $list_id)
		{
			$this->db->select('VI.VENDOR_INVITE_ID, VI.VENDOR_NAME, VL.VENDOR_LIST_NAME', FALSE);
			$this->db->from('SMNTP_VENDOR_LIST VL');
			$this->db->join('SMNTP_VENDOR_LIST_DEFN VLD', 'VL.VENDOR_LIST_ID=VLD.VENDOR_LIST_ID');
			$this->db->join('SMNTP_VENDOR_INVITE VI', 'VI.VENDOR_INVITE_ID=VLD.INVITE_ID');
			$this->db->join('SMNTP_USERS U', 'U.USER_ID=VLD.CREATED_BY');

			if(!empty($user_id) && $user_id != 0)
				$this->db->where('VL.CREATED_BY', $user_id);

			$this->db->where('VL.VENDOR_LIST_ID', $list_id);


			$result = $this->db->get();

			return $result->result_array();
		}

		function get_requestor(){
			/*
			$query = " select requestor_id, requestor from SMNTP_RFX_REQUESTOR";
			$query .= " WHERE active = 1";*/

			$this->db->select('*');
			$this->db->from('SMNTP_RFX_REQUESTOR');
			$this->db->where('ACTIVE', 1);

			$result = $this->db->get();
			/*$requestor = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$requestor[$row->REQUESTOR_ID] = $row->REQUESTOR;
				}
			}*/

			return $result->result();
		}

		function get_category(){
			$this->db->select('*');
			$this->db->from('SMNTP_CATEGORY');
			$this->db->where('ACTIVE', 1);

			$result = $this->db->get();
			$category = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$category[$row->CATEGORY_ID] = $row->CATEGORY_NAME;
				}
			}

			return $category;
		}

		function get_unit(){
			$this->db->select('*');
			$this->db->from('SMNTP_UNIT_OF_MEASURE');
			$this->db->where('ACTIVE', 1);

			$result = $this->db->get();
			/*$unit = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$unit[$row->UNIT_OF_MEASURE] = $row->MEASURE_NAME;
				}
			}*/

			return $result->result();
		}

		function get_purpose(){

			/*
			$query = " select purpose_id, purpose from SMNTP_RFX_PURPOSE";
			$query .= " WHERE active = 1";*/

			$this->db->select('*');
			$this->db->from('SMNTP_RFX_PURPOSE');
			$this->db->where('ACTIVE', 1);
			$this->db->order_by('FLAG', 'asc');

			$result = $this->db->get();
			/*$purpose = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$purpose[$row->PURPOSE_ID] = $row->PURPOSE;
				}
			}*/

			return $result->result();
		}

		function get_reason(){
			/*
			$query = " select reason_id, reason from SMNTP_RFX_REASON";
			$query .= " WHERE active = 1";*/

			$this->db->select('*');
			$this->db->from('SMNTP_RFX_REASON');
			$this->db->where('ACTIVE', 1);
			$this->db->order_by('FLAG', 'asc');

			$result = $this->db->get();
			/*$reason = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$reason[$row->REASON_ID] = $row->REASON;
				}
			}*/

			return $result->result();
		}

		function get_vendornames()
		{
			/*
			$this->db->select('VI.VENDOR_INVITE_ID, VI.VENDOR_NAME');
			$this->db->from('SMNTP_VENDOR_INVITE VI');
			$this->db->join('SMNTP_VENDOR V', 'VI.VENDOR_INVITE_ID=V.VENDOR_INVITE_ID', 'LEFT');
			$this->db->group_by('VI.VENDOR_INVITE_ID, VI.VENDOR_NAME');
			$result = $this->db->get();
			*/


			$q = 'select VENDOR_INVITE_ID, VENDOR_NAME
				  FROM "SMNTP_VENDOR" V
				  UNION ALL
				  SELECT VENDOR_INVITE_ID, VENDOR_NAME
				  FROM "SMNTP_VENDOR_INVITE" VI';

			$result = $this->db->query($q);

			/*$vendornames = '';
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$vendornames .= '<option data-vendorid="'.$row->VENDOR_ID.'" value="'.$row->VENDOR_NAME.'">';
				}
			}*/

			return $result->result_array();
		}

		function get_vendorcategory($business_type = 0, $user_id ='')
		{

			$this->db->select('VC.CATEGORY_ID, VC.CATEGORY_NAME');
			$this->db->from('SMNTP_CATEGORY VC');
			if($user_id != ''){
				$this->db->join('SMNTP_USER_CATEGORY UC', 'VC.CATEGORY_ID = UC.CATEGORY_ID');
				$this->db->where('UC.USER_ID', $user_id);
			}

			if($business_type != 0){
				$this->db->where('VC.BUSINESS_TYPE', $business_type);
			}

			$result = $this->db->get();
			/*$vendorcategory = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$vendorcategory[$row->VENDOR_CAT_ID] = $row->VENDOR_NAME;
				}
			}*/

			return $result->result();
		}

		function get_line_bom_attachment($rfq_id)
		{
			$this->db->select('A.*');
			$this->db->from('SMNTP_RFQRFB M');
			$this->db->join('SMNTP_RFQRFB_LINE L', 'M.RFQRFB_ID = L.RFQRFB_ID', 'INNER');
			$this->db->join('SMNTP_RFQRFB_LINE_ATTACHMENT A', 'L.RFQRFB_LINE_ID = A.RFQRFB_LINE_ID', 'INNER');
			$this->db->where('M.RFQRFB_ID', $rfq_id);
			$this->db->where('A.ATTACHMENT_TYPE', 1); //BOM ONLY

			$result = $this->db->get();
			return $result->result();
		}

		function get_line_bom_view($line_attachment_id)
		{
			$this->db->select('ROW_NO, PRODUCT, DESCRIPTION, QUANTITY');
			$this->db->from('SMNTP_RFQRFB_BOM_VIEW');
			$this->db->where('LINE_ATTACHMENT_ID', $line_attachment_id);

			$result = $this->db->get();

			return $result->result();
		}

		function get_bom_quote_nums($line_attachment_id)
		{
			$this->db->distinct();
			$this->db->select('QUOTE_NO');
			$this->db->from('SMNTP_RFQRFB_LINE_BOM_COST');
			$this->db->where('LINE_ATTACHMENT_ID', $line_attachment_id);
			$this->db->order_by("QUOTE_NO", "DESC");

			$result = $this->db->get();

			return $result->result();
		}

		function get_attachment_by_line_id($rfqrfb_line_id)
		{
			$this->db->from('SMNTP_RFQRFB_LINE_ATTACHMENT');
			$this->db->where('RFQRFB_LINE_ID', $rfqrfb_line_id);
			$this->db->where('ATTACHMENT_TYPE', 1); //BOM ONLY
			$this->db->limit(2);

			$result = $this->db->get();

			return $result->result_array();
		}

		function validate_duplicate($data)
		{
			$array_where = array($data['field'] => $data['value']);

			$this->db->select('*');
			$this->db->from($data['table']);
			$this->db->where($array_where);
			$this->db->where($data['id_field'].' != '.$data['id'])->limit(2);

			$rs = $this->db->get();

			if($rs->num_rows() > 0)
				return 1;
			else
				return 0;
		}

		function update_insert_line_bom_cost($data){
			$array_where = array('VENDOR_ID' => $data['vendor_id'],
				'LINE_ATTACHMENT_ID' => $data['line_attachment_id'],
				'QUOTE_NO' => $data['quote_no'],
				'ROW_NO' => $data['row_no']
			);
			$this->db->delete('SMNTP_RFQRFB_LINE_BOM_COST', $array_where);

			$array_insert = array('VENDOR_ID' => $data['vendor_id'],
				'LINE_ATTACHMENT_ID' => $data['line_attachment_id'],
				'ROW_NO' => $data['row_no'],
				'QUOTE_NO' => $data['quote_no'],
				'COST' => $data['cost'],
				'REMARKS' => $data['remarks']
			);
			$ret = $this->db->insert('SMNTP_RFQRFB_LINE_BOM_COST', $array_insert);


			return $ret;
		}

		function get_line_bom_cost($data){
			$array_where = array('VENDOR_ID' => $data['vendor_id'],
				'LINE_ATTACHMENT_ID' => $data['line_attachment_id'],
				'QUOTE_NO' => $data['quote_no'],
				'ROW_NO' => $data['row_no']
			);

			$this->db->order_by('ROW_NO');
			$res = $this->db->get_where('SMNTP_RFQRFB_LINE_BOM_COST', $array_where);


			return $res->result_array();
		}

		function get_vendors_bom_cost($data){
			$array_where = array(
				'C.LINE_ATTACHMENT_ID' => $data['line_attachment_id'],
				'C.QUOTE_NO' => $data['quote_no'],
				'C.ROW_NO' => $data['row_no']
			);

			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_ID=C.VENDOR_ID', 'INNER');
			$this->db->order_by('V.VENDOR_ID');
			$res = $this->db->get_where('SMNTP_RFQRFB_LINE_BOM_COST C', $array_where);

			return $res->result_array();
		}

		function get_vendor_line_bom_file($data){
			$array_where = array(
				'LINE_ATTACHMENT_ID' => $data['line_attachment_id']
			);

			$this->db->select('ROW_NO, A, B, C, D, E');
			$this->db->order_by('ROW_NO');
			$res = $this->db->get_where('SMNTP_RFQRFB_LINE_BOM_FILE', $array_where);


			return $res->result_array();
		}

		function get_vendor_line_bom($data){
			$array_where = array(
				'B.LINE_ATTACHMENT_ID' => $data['line_attachment_id']
			);

			$this->db->join('SMNTP_RFQRFB_LINE_BOM_COST C', 'C.LINE_ATTACHMENT_ID=B.LINE_ATTACHMENT_ID AND C.ROW_NO=B.ROW_NO AND C.QUOTE_NO='.$data['quote_no'].' AND C.VENDOR_ID ='.$data['vendor_id'], 'LEFT');
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_ID=C.VENDOR_ID', 'LEFT');
			$this->db->select('B.ROW_NO, B.PRODUCT, B.DESCRIPTION, B.QUANTITY, C.REMARKS, C.COST');
			$this->db->order_by('B.ROW_NO');
			$res = $this->db->get_where('SMNTP_RFQRFB_BOM_VIEW B', $array_where);
			// echo $this->db->last_query();

			return $res->result_array();
		}

		function get_vendor_bom_cost($data){
			$array_where = array(
				'C.LINE_ATTACHMENT_ID' => $data['line_attachment_id'],
				'C.VENDOR_ID' => $data['vendor_id'],
				'C.ROW_NO' => $data['row_no']
			);

			// $this->db->where($array_where);
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_ID=C.VENDOR_ID', 'INNER');
			$this->db->order_by('ROW_NO');
			$res = $this->db->get_where('SMNTP_RFQRFB_LINE_BOM_COST C', $array_where);


			return $res->result_array();
		}

		function get_vendors_bom_cost_all($data){
			$array_where = array(
				'C.LINE_ATTACHMENT_ID' => $data['line_attachment_id']
			);

			$this->db->select('C.LINE_ATTACHMENT_ID,C.ROW_NO,C.COST,C.VENDOR_ID,C.REMARKS');
			$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_ID=C.VENDOR_ID', 'INNER');
			$this->db->order_by('C.ROW_NO');
			$res = $this->db->get_where('SMNTP_RFQRFB_LINE_BOM_COST C', $array_where);


			return $res->result_array();
		}

		function add_new_invite($data)
		{
			$array_insert = array(  'VENDOR_NAME' 		=> $data['vendorname'],
									'CONTACT_PERSON' 	=> $data['vendorcontact'],
									'EMAIL' 			=> $data['email'],
									'TEMPLATE_ID'		=> 0, // to change from this line when data is available
									'MESSAGE'			=> '',
									'APPROVER_NOTE'		=> 'NONE',
									'DATE_CREATED'		=> date("Y-m-d H:i:s"),
									'CREATED_BY'		=> $data['user_id'],
									'EMAIL_TEMPLATE_ID'	=> $data['email_template_id'],
									'BUSINESS_TYPE'		=> 2,
									'ACTIVE'			=> 1
									);

			$this->db->insert('SMNTP_VENDOR_INVITE', $array_insert);

			$this->db->select('*');
			$this->db->from('SMNTP_VENDOR_INVITE');
			$this->db->where(array_filter($array_insert));
			$this->db->order_by('VENDOR_INVITE_ID', 'desc')->limit(2);

			$query = $this->db->get();

			$row = $query->first_row();

			$new_data = array();
			$new_data['result'] = $row;
			$new_data['num_rows'] = $query->num_rows();

			$status_array = array(
									'VENDOR_INVITE_ID' 	=> $row->VENDOR_INVITE_ID,
									'STATUS_ID' 		=> $data['status_id'], // test data
									'DATE_UPDATED' 		=> date("Y-m-d H:i:s"),
									'APPROVAL_TYPE' 	=> 1,// default
									'POSITION_ID' 		=> $data['position_id'],
									'ACTIVE' 			=> 1
								);
			$this->db->insert('SMNTP_VENDOR_STATUS', $status_array);

			return $new_data;
		}

		function get_vendor($data)
		{
			$this->db->select('RFQRFB_ID, TITLE, DATE_CREATED');
			$this->db->from('SMNTP_RFQRFB');

			if ($data['search_no'] != '')
				$this->db->where('RFQRFB_ID', $data['search_no']);

			if ($data['title_search'] != '')
				$this->db->where('TITLE', $data['title_search']);

			if ($data['date_created'] != '')
				$this->db->where('RFQ_DATE_CREATED', $data['date_created']);

			/*if ($data['search_no'] != '')
				$this->db->where('RFQRFB_ID', $data['search_no']);

			if ($data['search_no'] != '')
				$this->db->where('RFQRFB_ID', $data['search_no']);*/

			$query = $this->db->get();

			$new_data['num_rows'] = $query->num_rows();
			$new_data['result']		= $query->result();

			return $new_data;
		}

		function search_invite($data)
		{

			$new_data['num_rows'] = 0;
			$new_data['result'] = '';
			$this->db->distinct();
			$this->db->select('A.VENDOR_ID, B.VENDOR_NAME, B.CONTACT_PERSON, B.VENDOR_INVITE_ID');
			$this->db->from('SMNTP_VENDOR_INVITE B');
			$this->db->join('SMNTP_VENDOR A', 'A.VENDOR_INVITE_ID=B.VENDOR_INVITE_ID');
			//$this->db->join('SMNTP_VENDOR_STATUS C', 'A.VENDOR_INVITE_ID=C.VENDOR_INVITE_ID AND C.STATUS_ID = 19');
			$this->db->join('SMNTP_VENDOR_CATEGORIES C', 'C.VENDOR_INVITE_ID=B.VENDOR_INVITE_ID');
			$this->db->join('SMNTP_USER_CATEGORY UC', 'C.CATEGORY_ID=UC.CATEGORY_ID');
			$this->db->where('UC.CATEGORY_ID IN (SELECT CATEGORY_ID FROM SMNTP_USER_CATEGORY WHERE USER_ID = '. $data['user_id'] .')', NULL, FALSE);

			if ($data['cbo_vendorname'] != '')
				$this->db->where('upper(B.VENDOR_NAME) LIKE \'%'.strtoupper($data['cbo_vendorname']).'%\' ');

			if ($data['cbo_vendorcategory'] != '')
			{
				//$this->db->join('SMNTP_VENDOR_CATEGORIES C', 'C.VENDOR_INVITE_ID=B.VENDOR_INVITE_ID', 'LEFT');
				$this->db->join('SMNTP_CATEGORY D', 'D.CATEGORY_ID=C.CATEGORY_ID', 'LEFT');
				$this->db->where('upper(D.CATEGORY_NAME) LIKE \'%'.strtoupper($data['cbo_vendorcategory']).'%\' ');
			}

			if ($data['cbo_vendorlist'] != '')
			{
				$this->db->join('SMNTP_VENDOR_LIST_DEFN VLD', 'VLD.INVITE_ID=B.VENDOR_INVITE_ID', 'LEFT');
				$this->db->join('SMNTP_VENDOR_LIST VL', 'VL.VENDOR_LIST_ID=VLD.VENDOR_LIST_ID', 'LEFT');
				$this->db->where('upper(VL.VENDOR_LIST_NAME) LIKE \'%'.strtoupper($data['cbo_vendorlist']).'%\' ');
			}

			if ($data['cbo_vendorbrand'] != '')
			{
				$this->db->join('SMNTP_VENDOR_BRAND E', 'E.VENDOR_ID=A.VENDOR_ID', 'LEFT');
				$this->db->join('SMNTP_BRAND F', 'F.BRAND_ID=E.BRAND_ID', 'LEFT');
				$this->db->where('upper(F.BRAND_NAME) LIKE \'%'.strtoupper($data['cbo_vendorbrand']).'%\'');
			}
			if ($data['cbo_vendorlocation'] != '')
			{
				$this->db->join('SMNTP_VENDOR_ADDRESSES G', 'G.VENDOR_ID=A.VENDOR_ID', 'LEFT');
				$this->db->join('SMNTP_CITY SMC', 'G.BRGY_MUNICIPALITY_ID=SMC.CITY_ID', 'LEFT');
				$this->db->where('upper(SMC.CITY_NAME) LIKE \'%'.strtoupper($data['cbo_vendorlocation']).'%\'');
			}

			if ($data['cbo_vendorrfq'] != '')
			{
				$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS RIV', 'RIV.INVITE_ID=B.VENDOR_INVITE_ID', 'LEFT');
				$this->db->join('SMNTP_RFQRFB R', 'R.RFQRFB_ID=RIV.RFQRFB_ID', 'LEFT');
				$this->db->where('upper(R.TITLE) LIKE \'%'.strtoupper($data['cbo_vendorrfq']).'%\'');
			}

//			$this->db->where('B.ACTIVE', 1);
			$this->db->limit(100);
			$query = $this->db->get();

			if($query->num_rows() > 0)
			{
				$new_data['num_rows'] = $query->num_rows();
				$new_data['result'] = $query->result();
			}

			return $new_data;
		}

		function insert_test($data){
			$this->db->insert('TEST_RFQ_CREATION', $data);
		}

		function delete_tables($data)
		{
			$array_where = array('RFQRFB_ID' => $data['rfq_id']);
			$this->db->delete('SMNTP_RFQRFB_LINE_ATTACHMENT', $array_where);
			$this->db->delete('SMNTP_RFQRFB_LINE', $array_where);
			$this->db->delete('SMNTP_RFQRFB_INVITED_VENDORS', $array_where);
			$this->db->delete('SMNTP_RFQRFB_STATUS', $array_where);
		}

		function save_vendor_list($data)
		{
			$date_timestamp = date('Y-m-d H:i:s');

			$insert_array = array('VENDOR_LIST_NAME' => $data['txt_input_vendor_list_name'],
								  'CREATED_BY'		 => $data['user_id'],
								  'DATE_CREATED'	 => $date_timestamp,
								  'ACTIVE'			 => 1
								  );
			$this->db->insert('SMNTP_VENDOR_LIST', $insert_array);

			$this->db->select('*');
			$this->db->from('SMNTP_VENDOR_LIST');
			$this->db->where(array_filter($insert_array));

			$query = $this->db->get();

			$vendor_list_id = $query->row(0)->VENDOR_LIST_ID;
			//echo $data['vendor_list_total'];
			for($i=1; $i <= $data['vendor_list_total']; $i++)
			{
				$new_insert = array('VENDOR_LIST_ID' => $vendor_list_id,
									'INVITE_ID' 	 => $data['vendorfinal_invite_id'.$i],
									'CREATED_BY' 	 => $data['user_id'],
									'DATE_CREATED' 	 => $date_timestamp,
									'ACTIVE' 	 	 => 1
									);

				$this->db->insert('SMNTP_VENDOR_LIST_DEFN', $new_insert);
			}

			return 'success';

		}

		function submit_rfq_creation($data)
		{
			$date_created = date('Y-m-d H:i:s');
			$date_timestamp = date('Y-m-d H:i:s');
			$data_insert = array(	'TITLE'					=> $data['title_txt'],
									'RFQRFB_TYPE' 			=> $data['type_radio'],
									'CURRENCY_ID' 			=> $data['currency'],
									'DELIVERY_DATE' 		=> $data['pref_delivery_date'],
									'SUBMISSION_DEADLINE' 	=> $data['sub_deadline_date'],
									'REQUESTOR_ID' 			=> $data['requestor'],
									'PURPOSE_ID' 			=> $data['purpose'],
									'OTHER_PURPOSE' 		=> $data['purpose_txt'],
									'REASON_ID' 			=> $data['reason'],
									'OTHER_REASON' 			=> $data['reason_txt'],
									'DATE_CREATED' 			=> $date_timestamp,
									'INTERNAL_NOTE' 		=> $data['internal_note'],
									'CREATED_BY' 			=> $data['userdata'],
									'ACTIVE' 				=> 1
									);

			if(($data['type'] == 1 && $data['draft_validation'] == 0) || ($data['type'] == 0 && $data['draft_validation'] == 0))
				$this->db->insert('SMNTP_RFQRFB', $data_insert);
			else
			{
				$this->db->where('RFQRFB_ID', $data['rfq_id']);
				$this->db->update('SMNTP_RFQRFB', $data_insert);
			}


			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB');
			$this->db->where('TITLE', $data['title_txt']);
			$this->db->where('CREATED_BY', $data['userdata']);
			$this->db->where('DELIVERY_DATE', $data['pref_delivery_date']);
			$this->db->where('SUBMISSION_DEADLINE', $data['sub_deadline_date']);
			$this->db->where('RFQRFB_TYPE', $data['type_radio']);
			$this->db->order_by('RFQRFB_ID', 'DESC')->limit(2);

			$query = $this->db->get();

			if($data['type'] == 1 || $data['draft_validation'] == 0)
				$id = $query->row()->RFQRFB_ID;
			else
				$id = $data['rfq_id'];


		    if($data['status_id'] == 20)
		    {
		    	$position = $data['position_id'];
		    }
		    else
		    {
		    	$position = 8;
		    }

			$status_insert = array(
									'RFQRFB_ID' 				=> $id,
									'STATUS_ID' 				=> $data['status_id'],
									'POSITION_ID'			 	=> $position,
									'APPROVER_REMARKS'			=> ''
									);

			$this->db->insert('SMNTP_RFQRFB_STATUS', $status_insert);

			//logs/approval history ======================================

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $id);

			$query3 = $this->db->get();

			if($query3->num_rows() > 0)
			{
				$rfq_status_id = $query3->first_row()->RFQRFB_STATUS_ID;
				$extension_date = $query3->first_row()->EXTENSION_DATE;
				$approver_remarks = $query3->first_row()->APPROVER_REMARKS;
			}
			else
			{
				$rfq_status_id = null;
				$extension_date = null;
				$approver_remarks = null;
			}

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $id,
									'STATUS_ID' 			=> $data['status_id'],
									'POSITION_ID' 			=> $position,
									'APPROVER_REMARKS'		=> $approver_remarks,
									'APPROVER_ID'			=> $data['userdata'],
									'DATE_UPDATED'			=> $date_timestamp,
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);

			//=============================================================







			for($i = 1; $i <= $data['total_lines']; $i++)
			{
				$line_array = array(	'RFQRFB_ID' 			=>		$id,
										'CATEGORY_ID' 			=>		$data['line_category'.$i],
										'DESCRIPTION' 			=>		$data['line_description'.$i],
										'UNIT_OF_MEASURE'		=>		$data['line_measuring_unit'.$i],
										'QUANTITY'				=>		$data['quantity'.$i],
										'SPECIFICATION'			=>		$data['specs'.$i.'_text'],
										'CREATED_BY'			=>		$data['userdata'],
										'DATE_CREATED' 			=> 		$date_created,
										'ACTIVE'				=>		1
										);

				$this->db->insert('SMNTP_RFQRFB_LINE', $line_array);


				$this->db->select('*');
				$this->db->from('SMNTP_RFQRFB_LINE');
				$this->db->where(array_filter($line_array));
				$this->db->order_by('RFQRFB_ID, RFQRFB_LINE_ID', 'DESC')->limit(100);

				if($data['type'] == 0 || $data['draft_validation'] == 1)
					$this->db->where('RFQRFB_ID', $id);

				$query2 = $this->db->get();

				$id2 = $query2->row(0)->RFQRFB_LINE_ID;

				for($x=1; $x <= 8; $x++)
		        {
		        	if(!empty($data['hidden_path_'.$i.'_'.$x]))
        			{
						$attachment_insert = array(
										'RFQRFB_LINE_ID'		=> $id2,
										'RFQRFB_ID'				=> $id,
										'DESCRIPTION'			=> $data['attachment_desc_'.$i.'_'.$x],
										'ATTACHMENT_TYPE'		=> $data['attachment_type_'.$i.'_'.$x],
										'FILE_PATH'				=> $data['hidden_path_'.$i.'_'.$x],
										'CREATED_BY'			=> $data['userdata'],
										'DATE_CREATED' 			=> $date_created,
										'ACTIVE'				=> 1
										);

							$this->db->insert('SMNTP_RFQRFB_LINE_ATTACHMENT', $attachment_insert);

					}
				}
			}

			return $id;
		}

		function update_rfq_status($rfqrfb_id, $status_id, $position_id, $userdata){

			$date_timestamp = date('Y-m-d H:i:s');

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, STATUS_ID, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $rfqrfb_id);

			$query3 = $this->db->get();

			if($query3->num_rows() > 0)
			{
				$rfq_status_id = $query3->first_row()->RFQRFB_STATUS_ID;
				$extension_date = $query3->first_row()->EXTENSION_DATE;
				$status_id2 = $query3->first_row()->STATUS_ID;
				$approver_remarks = $query3->first_row()->APPROVER_REMARKS;
			}
			else
			{
				$rfq_status_id = null;
				$extension_date = null;
				$status_id2 = null;
				$approver_remarks = null;
			}

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $rfqrfb_id,
									'STATUS_ID' 			=> $status_id2,
									'POSITION_ID' 			=> $position_id,
									'APPROVER_REMARKS'		=> $approver_remarks,
									'APPROVER_ID'			=> $userdata,
									'DATE_UPDATED'			=> $date_timestamp,
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);


			$status_update = array(
									'STATUS_ID' => $status_id,
									'POSITION_ID' => $position_id
									);

			$this->db->where('RFQRFB_ID', $rfqrfb_id);
			$this->db->update('SMNTP_RFQRFB_STATUS', $status_update);
			return true;
		}

		function insert_invited_vendors($data)
		{
			for($y=1;$y <= $data['count_all_invited']; $y++)
		    {
		    	$new_vendor = 0;
		    	if($data['vendorinvitefinal_id'.$y] != 0)
		    		$new_vendor = 1;

		    	$invited_insert = array(
									'RFQRFB_ID' 				=> $data['id'],
									'NEW_VENDOR'				=> $new_vendor, // change 1 if new vendor
									'VENDOR_ID'					=> $data['vendorinvitefinal_id'.$y], // zero pag new vendor
									'INVITE_ID'					=> $data['vendorfinal_invite_id'.$y], // palitan pag new vendor lang
									'DATE_CREATED'				=> date('Y-m-d H:i:s'),
									'CREATED_BY'				=> $data['userdata'],
									'ACTIVE'					=> 1
									);

			    $this->db->insert('SMNTP_RFQRFB_INVITED_VENDORS', $invited_insert);

		    }
		}

		function get_attachment_type()
		{
			$this->db->select('*');
			$this->db->from('SMNTP_ATTACHMENT_TYPES');
			$this->db->where('ACTIVE', 1);

			$result = $this->db->get();
			$attachment_type = array();
			if ($result->num_rows() > 0)
			{
				foreach($result->result() as $row)
				{
					$attachment_type[$row->ATTACHMENT_TYPE_ID] = $row->ATTACHMENT_TYPE_NAME;
				}
			}

			return $attachment_type;

		}

		function appover_data($data)
		{
			$this->db->select('U1.USER_FIRST_NAME, U1.USER_LAST_NAME, U1.USER_MIDDLE_NAME, P.POSITION_NAME, U1.USER_ID');
			$this->db->from('SMNTP_USERS U');
			$this->db->join('SMNTP_USERS_MATRIX UM','U.USER_ID=UM.USER_ID');
			$this->db->join('SMNTP_USERS U1', 'U1.USER_ID = UM.GHEAD_ID', 'LEFT');
			$this->db->join('SMNTP_POSITION P', 'P.POSITION_ID = U1.POSITION_ID', 'LEFT');
			$this->db->where('U.USER_ID', $data['user_id']);

			$query = $this->db->get();

			return $query;
		}

		// ----------------------------- APPROVAL --------------------------------
		function get_data_approval($id, $shortlisted = 0)
		{

			$this->db->select( 'R.RFQRFB_ID,
								R.TITLE,
								R.RFQRFB_TYPE,
								R.CURRENCY_ID,
								R.DELIVERY_DATE,
								R.SUBMISSION_DEADLINE,
								R.REQUESTOR_ID,
								R.PURPOSE_ID,
								R.OTHER_PURPOSE,
								R.REASON_ID,
								R.OTHER_REASON,
								R.INTERNAL_NOTE,
								R.CREATED_BY,
								CAST(R.DATE_CREATED AS DATE) AS DATE_CREATED,

								C.CATEGORY_NAME,

								RL.RFQRFB_LINE_ID,
								RL.CATEGORY_ID,
								RL.DESCRIPTION,
								RL.UNIT_OF_MEASURE,
								RL.QUANTITY,
								RL.SPECIFICATION,

								RLA.LINE_ATTACHMENT_ID,
								RLA.FILE_PATH,
								RLA.ATTACHMENT_TYPE,
								RLA.DESCRIPTION as A_DESCRIPTION,

								RS.POSITION_ID,
								RS.STATUS_ID,
								SS.STATUS_NAME,
								RT.RFQRFB_TYPE_NAME,
								U.USER_LAST_NAME || U.USER_FIRST_NAME AS CREATEDBY,
								RUM.MEASURE_NAME,
								RIV.INVITE_ID

								', FALSE);
			$this->db->from('SMNTP_RFQRFB R');
			$this->db->join('SMNTP_RFQRFB_STATUS RS', 'RS.RFQRFB_ID 	= R.RFQRFB_ID', 'LEFT');
			$this->db->join('SMNTP_STATUS SS', 'SS.STATUS_ID 	= RS.STATUS_ID', 'LEFT');
			$this->db->join('SMNTP_RFQRFB_TYPE RT', 'R.RFQRFB_TYPE 	= RT.RFQRFB_TYPE_ID', 'LEFT');
			$this->db->join('SMNTP_RFQRFB_LINE RL', 'R.RFQRFB_ID 	= RL.RFQRFB_ID', 'LEFT');
			$this->db->join('SMNTP_CATEGORY C', 'C.CATEGORY_ID 	= RL.CATEGORY_ID', 'LEFT');
			$this->db->join('SMNTP_UNIT_OF_MEASURE RUM', 'RUM.UNIT_OF_MEASURE 	= RL.UNIT_OF_MEASURE', 'LEFT');
			$this->db->join('SMNTP_RFQRFB_LINE_ATTACHMENT RLA', 'RL.RFQRFB_LINE_ID 	= RLA.RFQRFB_LINE_ID AND R.RFQRFB_ID=RLA.RFQRFB_ID', 'LEFT');
			$this->db->join('SMNTP_USERS U', 'U.USER_ID 	= R.CREATED_BY', 'LEFT');
			$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS RIV', 'R.RFQRFB_ID=RIV.RFQRFB_ID', 'LEFT');
			$this->db->where('R.RFQRFB_ID', $id);
			$this->db->order_by('RL.RFQRFB_LINE_ID', 'ASC');


			if(!empty($shortlisted))
			{
				$this->db->join('SMNTP_RFQRFB_RESPONSE_QUOTE RRQ', 'RRQ.LINE_ID = RL.RFQRFB_LINE_ID AND R.RFQRFB_ID=RRQ.RFQRFB_ID AND RRQ.INVITE_ID=RIV.INVITE_ID', 'LEFT');
				$this->db->where('RRQ.SHORTLISTED', $shortlisted);
			}

			$query = $this->db->get();

			$data['result'] = $query->result();
			$data['num_rows'] = $query->num_rows();
			$data['attachment_count'] = 0;

			$this->db->select( 'COUNT(R.RFQRFB_ID) as ATTACHMENTCOUNT');
			$this->db->from('SMNTP_RFQRFB R');
			$this->db->join('SMNTP_RFQRFB_LINE RL', 'R.RFQRFB_ID 	= RL.RFQRFB_ID', 'LEFT');
			$this->db->join('SMNTP_RFQRFB_LINE_ATTACHMENT RLA', 'RL.RFQRFB_LINE_ID 	= RLA.RFQRFB_LINE_ID AND R.RFQRFB_ID=RLA.RFQRFB_ID', 'LEFT');
			$this->db->where('R.RFQRFB_ID', $id);

			$query2 = $this->db->get();

			if($query2->num_rows() > 0)
				$data['attachment_count'] = $query2->row()->ATTACHMENTCOUNT;

			return $data;
		}

		function get_attachment_data($lineid, $rfx_id)
		{
			$this->db->select( '*');
			$this->db->from('SMNTP_RFQRFB_LINE_ATTACHMENT RLA');
			$this->db->where('RLA.RFQRFB_LINE_ID', $lineid);
			$this->db->where('RLA.RFQRFB_ID', $rfx_id);

			$query = $this->db->get();

			return $query->result();
		}

		function response_creation_approval($data)
		{
			$date_timestamp = date('Y-m-d H:i:s');

			$update_array = array(
									'STATUS_ID' 			=> $data['status'],
									'POSITION_ID' 			=> $data['nxt_position_id'],
									'APPROVER_REMARKS' 		=> $data['reject_reason']
									);

			$this->db->where('RFQRFB_ID', $data['rfx_id']);


			$this->db->update('SMNTP_RFQRFB_STATUS', $update_array);

			//logs/approval history ======================================

			$this->db->select('RFQRFB_STATUS_ID, EXTENSION_DATE, APPROVER_REMARKS');
			$this->db->from('SMNTP_RFQRFB_STATUS');
			$this->db->where('RFQRFB_ID', $data['rfx_id']);

			$query = $this->db->get();

			$rfq_status_id = $query->row()->RFQRFB_STATUS_ID;
			$extension_date = $query->row()->EXTENSION_DATE;
			$approver_remarks = $query->row()->APPROVER_REMARKS;

			$logs_insert = array(
									'RFQRFB_STATUS_ID'		=> $rfq_status_id,
									'RFQRFB_ID'				=> $data['rfx_id'],
									'STATUS_ID' 			=> $data['status'],
									'POSITION_ID' 			=> $data['nxt_position_id'],
									'APPROVER_REMARKS'		=> $data['reject_reason'],
									'APPROVER_ID'			=> $data['user_id'],
									'DATE_UPDATED'			=> $date_timestamp,
									'EXTENSION_DATE'		=> $extension_date

									);

			$this->db->insert('SMNTP_RFQRFB_STATUS_LOGS', $logs_insert);

			//=============================================================


			if ($data['status'] == 22 && $data['nxt_position_id'] == 7)
			{

				$this->db->select('*');
				$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS');
				$this->db->where('RFQRFB_ID', $data['rfx_id']);

				$query2 = $this->db->get();

				if ($query2->num_rows() > 0)
				{
					foreach($query2->result() as $row)
					{
						$status_insert = array(
												'RFQRFB_ID'				=> $data['rfx_id'],
												'STATUS_ID'				=> 101, // default
												'POSITION_ID'			=> 10, // default
												'DATE_CREATED' 			=> date('Y-m-d H:i:s'),
												'VENDOR_ID' 			=> $row->VENDOR_ID,
												'INVITE_ID' 			=> $row->INVITE_ID,
												'ACTIVE' 				=> 1
												);

						$this->db->insert('SMNTP_RFQRFB_INVITE_STATUS', $status_insert);
					}
				}
			}


			$data['response'] = 'success';
			return $data;
		}

		function next_status_get($data)
		{
			$query_filter = array(

									'TYPE_ID' 				=> $data['status_type'],
									'CURRENT_STATUS_ID' 	=> $data['current_status_id'],
									'CURRENT_POSITION_ID' 	=> $data['position_id'],
									'REGISTRATION_TYPE_ID' 	=> $data['reg_type_id'],
									);

			$this->db->select('APPROVE_STATUS_ID, REJECT_STATUS_ID, CURRENT_POSITION_ID, NEXT_POSITION_ID, NEXT_STATUS_ID, SUSPEND_STATUS_ID, REJECT_POSITION_ID');
			$this->db->from('SMNTP_STATUS_CONFIG');
			$this->db->where($query_filter)->limit(2);

			$query = $this->db->get();

			$new_data['result'] = $query->result();
			$new_data['num_rows'] = $query->num_rows();

			return $new_data;
		}

		function get_email_recipient($data)
		{
			$this->db->select('*');
			$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS RIV');
			$this->db->join('SMNTP_VENDOR V', 'RIV.VENDOR_ID=V.VENDOR_ID');
			$this->db->join('SMNTP_VENDOR_INVITE VI', 'V.VENDOR_INVITE_ID=VI.VENDOR_INVITE_ID');
			$this->db->where('RIV.RFQRFB_ID', $data['rfx_id']);

			$query = $this->db->get();

			return $query;
		}

		function get_messages($data)
		{
			$this->db->select('*');
			$this->db->from('SMNTP_MESSAGES');
			$this->db->where('RFQRFB_ID', $data['rfx_id']);
			$this->db->where('RECIPIENT_ID', $data['user_id']);

			$query = $this->db->get();

			return $query;
		}

		//---------------------------- END APPROVAL ------------------------------

function get_rfqrfb_main($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
        $this->db->select('RF.RFQRFB_ID');
        $this->db->from('SMNTP_RFQRFB RF');
		$this->db->join('SMNTP_RFQRFB_STATUS RFS', 'RF.RFQRFB_ID = RFS.RFQRFB_ID');
		$this->db->join('SMNTP_STATUS SS', 'RFS.STATUS_ID = SS.STATUS_ID');
		$this->db->join('SMNTP_RFQRFB_TYPE RT', 'RF.RFQRFB_TYPE = RT.RFQRFB_TYPE_ID', 'LEFT');
        $this->db->join('SMNTP_STATUS_CONFIG SC', 'RFS.STATUS_ID = SC.CURRENT_STATUS_ID AND SC.CURRENT_POSITION_ID =  RFS.POSITION_ID AND RFS.POSITION_ID = '.$data['position_id'].'', 'LEFT'); // left muna dipa completo config
        $this->db->join('SMNTP_MESSAGES MES', 'MES.RFQRFB_ID = RF.RFQRFB_ID AND MES.RECIPIENT_ID = '.$data['user_id'].' AND MES.IS_READ = -1', 'LEFT');
        $this->db->join('SMNTP_RFQRFB_INVITED_VENDORS IV', 'RF.RFQRFB_ID = IV.RFQRFB_ID', 'LEFT');
        $this->db->join('SMNTP_RFQRFB_ACKNOWLEDGEMENT RA', 'RF.RFQRFB_ID = RA.RFQRFB_ID AND RA.PARTICIPATE = 1', 'LEFT');
        $this->db->join('SMNTP_RFQRFB_RESPONSE RR', 'RF.RFQRFB_ID = RR.RFQRFB_ID', 'LEFT');
		$this->db->join('SMNTP_USERS_MATRIX UM', 'RF.CREATED_BY = UM.USER_ID');


		 if ($data['position_id'] == 7 || $data['position_id'] == 11)
        	 $this->db->where('RF.CREATED_BY', $data['user_id']);

		if ($data['position_id'] == 8)
        	 $this->db->where('UM.GHEAD_ID', $data['user_id']);

		if($data['position_id'] == 9)
			$this->db->where('UM.FASHEAD_ID', $data['user_id']);


		if (array_key_exists('search_no', $data))
        {
             if (!empty($data['search_no']))
                  $this->db->like('RF.RFQRFB_ID', $data['search_no']);
        }

		if (array_key_exists('search_title', $data))
        {
             if (!empty($data['search_title']))
                  $this->db->like('UPPER(RF.TITLE)', strtoupper($data['search_title']));
        }

		if (array_key_exists('date_created', $data))
        {
             if (!empty($data['date_created']))
                  $this->db->where('RF.DATE_CREATED >=', date_format(date_create($data['date_created']),'d-M-Y'));
        }

		if (array_key_exists('status_id', $data))
        {
             if (!empty($data['status_id']))
                  $this->db->where('RFS.STATUS_ID', $data['status_id']);
        }

		if (array_key_exists('timeleft_filter', $data))
        {
             if (!empty($data['timeleft_filter']))
                  $this->db->where('((CURRENT_DATE - RF.SUBMISSION_DEADLINE) <= '.$data['timeleft_filter'].' AND (CURRENT_DATE - RF.SUBMISSION_DEADLINE) > 0)');
        }

        if (array_key_exists('buyer_id', $data))
        {
             if (!empty($data['buyer_id']))
                  $this->db->where('RF.CREATED_BY', $data['buyer_id']);
        }

		if (array_key_exists('requestor_id', $data))
        {
             if (!empty($data['requestor_id']))
                  $this->db->where('RF.REQUESTOR_ID', $data['requestor_id']);
        }

		if (array_key_exists('purpose_id', $data))
        {
             if (!empty($data['purpose_id']))
                  $this->db->where('RF.PURPOSE_ID', $data['purpose_id']);
        }


        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();
        if ($totalcount > 0)
        {
             if ($page_num != 0 && $rpp != 0)
             {
                  $max = $rpp * $page_num;
                  $min = $max-$rpp;
             }

              $page = 0;

             if(!empty($data['page_no'])){
             	$page = $data['page_no'] * 10;
             }	


              $sort_type = "RF.RFQRFB_ID";
              $sort = "DESC";

             if(!empty($data['sort_type'])){
             	if($data['sort_type'] == 'RFQRFB_ID'){
             			$sort_type = "RF.RFQRFB_ID";
             	}elseif($data['sort_type'] == 'TITLE'){
             			$sort_type = "RF.TITLE";
             	}elseif($data['sort_type'] == 'STATUS_NAME'){
             			$sort_type = "SS.STATUS_NAME";
             	}elseif($data['sort_type'] == 'ACTION_LABEL'){
             			$sort_type = "SC.ACTION_LABEL";
             	}elseif($data['sort_type'] == 'DATE_CREATED'){
             			$sort_type ="DATE_SORTING_FORMAT";
             	}elseif($data['sort_type'] == 'SUBMISSION_DEADLINE'){
             			$sort_type = "RF.SUBMISSION_DEADLINE";
             	}elseif($data['sort_type'] == 'MESSAGE_INDEX_PARAM'){
             			$sort_type = "UNREAD_MESSAGES";
             	}elseif($data['sort_type'] == 'RESPONSES'){
             			$sort_type = "RESPONSES";
             	}elseif($data['sort_type'] == 'VENDORS_PARTICIPATION'){
             			$sort_type = "PARTICIPATED_VENDORS";

             	}else{
             		$sort_type = "RF.RFQRFB_ID";
             	}
             }

             if(!empty($data['sort'])){
             	$sort = $data['sort'];
             }




             $this->db->select('
								(CURRENT_DATE - RF.DATE_CREATED) AS DATE_SORTING_FORMAT,
								RF.RFQRFB_ID,
								RT.RFQRFB_LABEL || \'#\' || RF.RFQRFB_ID || \' - \' || RF.TITLE AS RFQ_TITLE,
								RF.TITLE,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								CAST(RF.DATE_CREATED AS DATE) AS DATE_CREATED,
								CAST(RF.SUBMISSION_DEADLINE AS DATE) AS SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH,
								COUNT(DISTINCT MES.ID) AS UNREAD_MESSAGES,
								COUNT(DISTINCT IV.INVITED_VENDOR_ID) AS INVITED_VENDORS,
								COUNT(DISTINCT RA.RFQRFB_ACKNOWLEDGE_ID) AS PARTICIPATED_VENDORS,
								COUNT(DISTINCT RR.RESPONSE_ID) AS RESPONSES,
								(COUNT(DISTINCT RA.RFQRFB_ACKNOWLEDGE_ID) || \' / \' || COUNT(DISTINCT IV.INVITED_VENDOR_ID)) AS VENDORS_PARTICIPATION,
								(CASE
									WHEN MES.RFQRFB_ID != 0 THEN (MES.RFQRFB_ID || \'/\' || \'rfqrfb\')
								END) AS MESSAGE_INDEX_PARAM,
                                   ', false);
			$this->db->group_by('RF.RFQRFB_ID,
								MES.RFQRFB_ID,
								RF.TITLE,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								RF.DATE_CREATED,
								RF.SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH');
             $this->db->order_by($sort_type, $sort);
             $this->db->limit(10,$page);

             // if ($page_num != 0 && $rpp != 0)
             // {
                  // $this->db->limit($rpp,$min);
             // }

             $query = $this->db->get();
             $rst = $this->db->last_query();
             $valid = TRUE;
             $finalquery = $query->result_array();


             //$resultscount     = $query->num_rows();

             $this->db->select('
								(CURRENT_DATE - RF.DATE_CREATED) AS DATE_SORTING_FORMAT,
								RF.RFQRFB_ID,
								RT.RFQRFB_LABEL || \'#\' || RF.RFQRFB_ID || \' - \' || RF.TITLE AS RFQ_TITLE,
								RF.TITLE,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								CAST(RF.DATE_CREATED AS DATE) AS DATE_CREATED,
								CAST(RF.SUBMISSION_DEADLINE AS DATE) AS SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH,
								COUNT(DISTINCT MES.ID) AS UNREAD_MESSAGES,
								COUNT(DISTINCT IV.INVITED_VENDOR_ID) AS INVITED_VENDORS,
								COUNT(DISTINCT RA.RFQRFB_ACKNOWLEDGE_ID) AS PARTICIPATED_VENDORS,
								COUNT(DISTINCT RR.RESPONSE_ID) AS RESPONSES,
								(COUNT(DISTINCT RA.RFQRFB_ACKNOWLEDGE_ID) || \' / \' || COUNT(DISTINCT IV.INVITED_VENDOR_ID)) AS VENDORS_PARTICIPATION,
								(CASE
									WHEN MES.RFQRFB_ID != 0 THEN (MES.RFQRFB_ID || \'/\' || \'rfqrfb\')
								END) AS MESSAGE_INDEX_PARAM,
                                   ', false);
			$this->db->group_by('RF.RFQRFB_ID,
								MES.RFQRFB_ID,
								RF.TITLE,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								RF.DATE_CREATED,
								RF.SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH');
            $this->db->order_by($sort_type, 'DESC');
            $query = $this->db->get();
            $resultscount    = $query->num_rows();




        }

        $this->db->flush_cache();

        $data['rst'] = isset($rst) ? $rst : '';
        $data['resultscount'] = $resultscount;
        $data['totalcount'] = $totalcount;
        $data['valid'] = $valid;
		 if($resultscount > 0)
        {
			foreach($finalquery as $key => $value)
			{
				if($value['DATE_CREATED'])
					$finalquery[$key]['DATE_CREATED'] = date('m/d/Y',strtotime($value['DATE_CREATED']));

			}

			foreach($finalquery as $key => $value)
			{
				$now = new DateTime();
				$future_date = new DateTime(date('Y-m-d',strtotime($value['SUBMISSION_DEADLINE'])));

				$interval = $future_date->diff($now);

				if($value['SUBMISSION_DEADLINE'])
				{
					$finalquery[$key]['SUBMISSION_DEADLINE'] = $interval->format("%a days %h hours");
					//$finalquery[$key]['SUBMISSION_DEADLINE'] = date('Y-m-d',strtotime($value['SUBMISSION_DEADLINE']));
				}
			}
		}



        $data['query'] = $finalquery;

        return $data;
	}

	function get_rfqrfb_main_vendor($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
        $this->db->from('SMNTP_RFQRFB RF');
		$this->db->join('SMNTP_RFQRFB_STATUS RFS', 'RF.RFQRFB_ID = RFS.RFQRFB_ID');
		$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS RIV', 'RF.RFQRFB_ID = RIV.RFQRFB_ID');
		$this->db->join('SMNTP_VENDOR_INVITE VI', 'RIV.INVITE_ID = VI.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR V', 'VI.VENDOR_INVITE_ID = V.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_RFQRFB_INVITE_STATUS RIS', 'RF.RFQRFB_ID = RIS.RFQRFB_ID AND RIV.INVITE_ID = RIS.INVITE_ID');
		$this->db->join('SMNTP_STATUS SS', 'RIS.STATUS_ID = SS.STATUS_ID');
		$this->db->join('SMNTP_RFQRFB_TYPE RT', 'RF.RFQRFB_TYPE = RT.RFQRFB_TYPE_ID', 'LEFT');
        $this->db->join('SMNTP_STATUS_CONFIG SC', 'RIS.STATUS_ID = SC.CURRENT_STATUS_ID AND SC.CURRENT_POSITION_ID =  RIS.POSITION_ID', 'LEFT'); // left muna dipa completo config


		if (array_key_exists('vendor_id', $data))
        {
             if (!empty($data['vendor_id']))
                  $this->db->where('V.VENDOR_ID', $data['vendor_id']);
        }

		if (array_key_exists('vendor_invite_id', $data))
        {
             if (!empty($data['vendor_invite_id']))
                  $this->db->where('VI.VENDOR_INVITE_ID', $data['vendor_invite_id']);
        }

		if (array_key_exists('search_no', $data))
        {
             if (!empty($data['search_no']))
                  $this->db->like('RF.RFQRFB_ID', $data['search_no']);
        }

		if (array_key_exists('search_title', $data))
        {
             if (!empty($data['search_title']))
                  $this->db->like('UPPER(RF.TITLE)', strtoupper($data['search_title']));
        }

		if (array_key_exists('date_created', $data))
        {
             if (!empty($data['date_created']))
                  $this->db->where('RF.DATE_CREATED >=', date_format(date_create($data['date_created']),'d-M-Y'));
        }

		if (array_key_exists('status_id', $data))
        {
             if (!empty($data['status_id']))
                  $this->db->where('RFS.STATUS_ID', $data['status_id']);
        }

		if (array_key_exists('timeleft_filter', $data))
        {
             if (!empty($data['timeleft_filter']))
                  $this->db->where('((CURRENT_DATE - RF.SUBMISSION_DEADLINE) <= '.$data['timeleft_filter'].' AND (CURRENT_DATE - RF.SUBMISSION_DEADLINE) > 0)');
        }



        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();

        if ($totalcount > 0)
        {
             if ($page_num != 0 && $rpp != 0)
             {
                  $max = $rpp * $page_num;
                  $min = $max-$rpp;
             }



             $this->db->select('RF.RFQRFB_ID,
								RIS.STATUS_ID,
								RT.RFQRFB_LABEL || \'#\' || RF.RFQRFB_ID || \' - \' || RF.TITLE AS RFQ_TITLE,
								(CASE
									WHEN RIS.STATUS_ID > 104 THEN 1
									ELSE NULL
								END) AS RESPONSE_FLAG,
								RF.TITLE,
								RIV.INVITE_ID,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								CAST(RF.DATE_CREATED AS DATE) AS DATE_CREATED,
								CAST(RF.SUBMISSION_DEADLINE AS DATE) AS SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH
                                   ', false);
			$this->db->group_by('RF.RFQRFB_ID,
								RIS.STATUS_ID,
								RF.TITLE,
								RIV.INVITE_ID,
								RT.RFQRFB_LABEL,
								SS.STATUS_NAME,
								RF.DATE_CREATED,
								RF.SUBMISSION_DEADLINE,
								SC.ACTION_LABEL,
								SC.ACTION_PATH');
             $this->db->order_by('RF.RFQRFB_ID', 'DESC');

             // if ($page_num != 0 && $rpp != 0)
             // {
                  // $this->db->limit($rpp,$min);
             // }

             $query = $this->db->get();
             //echo $this->db->last_query();
             $valid = TRUE;
             $finalquery = $query->result_array();
             $resultscount     = $query->num_rows();
        }

        $this->db->flush_cache();

        $data['resultscount'] = $resultscount;
        $data['totalcount'] = $totalcount;
        $data['valid'] = $valid;

        if($resultscount > 0)
        {
        	foreach($finalquery as $key => $value)
			{
				if($value['DATE_CREATED'])
					$finalquery[$key]['DATE_CREATED'] = date('m/d/Y',strtotime($value['DATE_CREATED']));

			}

			foreach($finalquery as $key => $value)
			{
				$now = new DateTime();
				$future_date = new DateTime(date('Y-m-d',strtotime($value['SUBMISSION_DEADLINE'])));

				$interval = $future_date->diff($now);

				if($value['SUBMISSION_DEADLINE'])
				{
					$finalquery[$key]['SUBMISSION_DEADLINE'] = $interval->format("%a days %h hours");
					//$finalquery[$key]['SUBMISSION_DEADLINE'] = date('Y-m-d',strtotime($value['SUBMISSION_DEADLINE']));
				}
			}
        }


        $data['query'] = $finalquery;

        return $data;
	}

	// ------------------------------------- INVITATION ---------------------------------------
	function invitation_response($data)
	{
		$date_timestamp = date('Y-m-d H:i:s');

		$acknowledge_insert = array(
									'RFQRFB_ID' 		=> $data['rfx_id'],
									'INVITE_ID' 		=> $data['invite_id'],
									'VENDOR_ID' 		=> $data['vendor_id'],
									'DATE_CREATED' 		=> $date_timestamp,
									'PARTICIPATE' 		=> $data['action']
									);

		$this->db->insert('SMNTP_RFQRFB_ACKNOWLEDGEMENT', $acknowledge_insert);

		$this->db->select('*');
		$this->db->from('SMNTP_RFQRFB_ACKNOWLEDGEMENT');
		$this->db->where($acknowledge_insert);

		$query = $this->db->get();

		$status_update = array(
								'STATUS_ID'				=> $data['status'],
								'POSITION_ID'			=> $data['nxt_position_id'],
								'DATE_CREATED' 			=> date('Y-m-d H:i:s')
								);
		$this->db->where('RFQRFB_ID', $data['rfx_id'] );

		$this->db->where('INVITE_ID', $data['invite_id'] );

		$this->db->update('SMNTP_RFQRFB_INVITE_STATUS', $status_update);

//echo $this->db->last_query();
		return 'success';
	}

	function invitation_status($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_RFQRFB_ACKNOWLEDGEMENT');
		$this->db->where('RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();

		$new_data['num_rows'] = $query->num_rows();

		return $new_data;

	}

	// ----------------------------------- END OF INVITATION ----------------------------------

	// ----------------------------------- RESPONSE CREATION ----------------------------------

	function save_response_creation($data)
	{
		//Will Delete this Start
		$where_array = array('RFQRFB_ID' => $data['rfx_id'],
							 'INVITE_ID' => $data['invite_id'],
							 'VERSION' 	 => $data['version']
							 );
		$this->db->delete('SMNTP_RFQRFB_RESPONSE', $where_array);
		$this->db->delete('SMNTP_RFQRFB_RESPONSE_QUOTE', $where_array);
		//Will Delete this End

		$date_timestamp = date('m/d/Y h:i:s A');
		$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
		$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");

		if($data['version'] > 1){
			$shortlist = 1;
		}
		else{
			$shortlist = 0;
		}

		$query = $this->db->query('SELECT RESPONSE_ID FROM SMNTP_RFQRFB_RESPONSE WHERE 
									RFQRFB_ID = ? AND VENDOR_ID = ? AND CREATED_BY = ? AND INVITE_ID = ? AND ACTIVE = ? 
									ORDER BY RESPONSE_ID DESC	', array(
																'RFQRFB_ID' 	=> $data['rfx_id'],
																'VENDOR_ID' 	=> $data['vendor_id'],
																'CREATED_BY' 	=> $data['created_by'],
																'INVITE_ID' 	=> $data['invite_id'],
																'ACTIVE' 		=> $data['active']
																));
	
		$response_id = $query->result();
		$response_qoute_vendors = array();
		//Multiple Response_id
		foreach($response_id as $resp_id){
			$response_qoute_vendors[] = $this->db->query('SELECT * FROM SMNTP_RFQRFB_RESPONSE_QUOTE WHERE RFQRFB_ID = ? AND SHORTLISTED = 1 AND RESPONSE_ID = ?',  array('RFQRFB_ID' 	=> $data['rfx_id'], $resp_id->RESPONSE_ID))->result();
		}
		//Check first if data has not been change.
	
		$list_of_unchangged_quote = array();
		
		//Keys that will be remove if the data has not been change.
		$will_remove_keys = array();
		
		//Number of removing quotes
		$quote_remaining = array();
		//$test_check = array();
		foreach($response_qoute_vendors as $rqv_data){
			foreach($rqv_data as $rqv){
				for($i=1; $i <= $data['line_data_count']; $i++)
				{
					if($rqv->LINE_ID == $data['rfqrfbline_id'.$i]){
							
						for($x=1; $x<=$data['num_quote'.$i];$x++)
						{
							$test_check[] = ( ($data['quoteischecked'.$i.'_'.$x] == $rqv->QUOTE) &&
								(str_replace(',', '', $data['txt_quote'.$i.'_'.$x]) == $rqv->QUOTE_AMOUNT) &&
								($data['delivery_time'.$i.'_'.$x] == $rqv->LEAD_TIME) &&
								($data['txt_counteroffer'.$i.'_'.$x] == $rqv->COUNTER_OFFER) &&
								($data['hidden_quote_path_'.$i.'_'.$x] == $rqv->ATTACHMENT_PATH)
								);
						
							if( ($data['quoteischecked'.$i.'_'.$x] == $rqv->QUOTE) &&
								(str_replace(',', '', $data['txt_quote'.$i.'_'.$x]) == $rqv->QUOTE_AMOUNT) &&
								($data['delivery_time'.$i.'_'.$x] == $rqv->LEAD_TIME) &&
								($data['txt_counteroffer'.$i.'_'.$x] == $rqv->COUNTER_OFFER) &&
								($data['hidden_quote_path_'.$i.'_'.$x] == $rqv->ATTACHMENT_PATH)
								){
								$will_remove_keys[] ='quoteischecked'.$i.'_'.$x;
								$will_remove_keys[] ='txt_quote'.$i.'_'.$x;
								$will_remove_keys[] ='delivery_time'.$i.'_'.$x;
								$will_remove_keys[] ='txt_counteroffer'.$i.'_'.$x;
								$will_remove_keys[] ='hidden_quote_path_'.$i.'_'.$x;
								
								if(!isset($quote_remaining['num_quote'.$i])){
									$quote_remaining['num_quote'.$i] = 0;
								}
								$quote_remaining['num_quote'.$i]++;			
							}
						}
					}
				}
			}
		}
		
		//Minus Total Quotes
		$haveSomeChanges = false;
		for($i=1; $i <= $data['line_data_count']; $i++)
		{		
			if(((!isset($quote_remaining['num_quote'.$i]) ? 0 : $quote_remaining['num_quote'.$i]) < $data['num_quote'.$i])){
				$haveSomeChanges = true;
				break;
			}
		}
		
		//Remove marked keys
		foreach($will_remove_keys as $key){
			if(isset($data[$key])){
				unset($data[$key]);
			}
		}
		
		
		$req_insert = array(
							'RFQRFB_ID' 	=> $data['rfx_id'],
							'VENDOR_ID' 	=> $data['vendor_id'],
							'DATE_CREATED' 	=> $date_timestamp,
							'CREATED_BY' 	=> $data['created_by'],
							'INVITE_ID' 	=> $data['invite_id'],
							'ACTIVE' 		=> $data['active'],
							'VERSION' 	 	=> $data['version']
							);
		//$this->db->insert('SMNTP_RFQRFB_RESPONSE', $req_insert);
		
		//If there are some changes insert this. start
		if($haveSomeChanges){
			$this->db->insert('SMNTP_RFQRFB_RESPONSE', $req_insert);	
			
			//Get response id and update the invite status
			$query = $this->db->query('SELECT RESPONSE_ID FROM SMNTP_RFQRFB_RESPONSE WHERE 
										RFQRFB_ID = ? AND VENDOR_ID = ? AND CREATED_BY = ? AND INVITE_ID = ? AND ACTIVE = ? AND VERSION = ? 
										ORDER BY RESPONSE_ID DESC FETCH FIRST 1 ROWS ONLY', array(
																		'RFQRFB_ID' 	=> $data['rfx_id'],
																		'VENDOR_ID' 	=> $data['vendor_id'],
																		//'DATE_CREATED' 	=> $date_timestamp,
																		'CREATED_BY' 	=> $data['created_by'],
																		'INVITE_ID' 	=> $data['invite_id'],
																		'ACTIVE' 		=> $data['active'],
																		'VERSION' 	 	=> $data['version']
																	));
			$response_id = $query->result()[0]->RESPONSE_ID;
		}
		
       
		//Update invite status
		$status_update = array(
								'STATUS_ID'				=> $data['status'],
								'POSITION_ID'			=> $data['nxt_position_id'],
								'DATE_CREATED' 			=> $date_timestamp
								);
		$this->db->where('RFQRFB_ID', $data['rfx_id'] );
		$this->db->where('INVITE_ID', $data['invite_id'] );
		$this->db->update('SMNTP_RFQRFB_INVITE_STATUS', $status_update);
		
	
		//Get list of Response IDs and their version
		$responses_version = $this->db->query('SELECT RESPONSE_ID, VERSION 
												FROM SMNTP_RFQRFB_RESPONSE 
												WHERE RFQRFB_ID = ? 
													AND VENDOR_ID = ? 
													AND CREATED_BY = ? 
													AND INVITE_ID = ? 
													AND ACTIVE = ?
												ORDER BY RESPONSE_ID DESC',
												array(
													$data['rfx_id'],
													$data['vendor_id'],
													$data['created_by'],
													$data['invite_id'],
													$data['active']
												))->result();
		
		//$test_rid = array();
		if($haveSomeChanges){
			//Insert response
			for($i=1; $i <= $data['line_data_count']; $i++)
			{
				if((!isset($quote_remaining['num_quote'.$i])) || ((!isset($quote_remaining['num_quote'.$i]) ? 0 : $quote_remaining['num_quote'.$i]) < $data['num_quote'.$i])){
					//$data['num_quote'.$i] = $data['num_quote'.$i] - $quote_remaining['num_quote'.$i];
					
					
					//Get Next Version
							$version = $this->db->query('SELECT SRRQ.VERSION 
															FROM SMNTP_RFQRFB_RESPONSE_QUOTE SRRQ LEFT JOIN
															SMNTP_RFQRFB_RESPONSE SRR ON SRRQ.RESPONSE_ID = SRR.RESPONSE_ID 
															
															WHERE SRRQ.RFQRFB_ID = ? 
																	AND SRRQ.LINE_ID = ? 
																	AND SRR.VENDOR_ID = ? 
																	AND SRRQ.SHORTLISTED = 1 
															ORDER BY VERSION DESC FETCH FIRST 1 ROWS ONLY',
															array(
																$data['rfx_id'],
																$data['rfqrfbline_id'.$i],
																$data['vendor_id']
															))->result();
					for($x=1; $x <= $data['num_quote'.$i];$x++)
					{
						//$quote_existed = false;
						//foreach($existing_quotes as $quote){
						//	if($quote['QUOTE_AMOUNT'] == str_replace(',', '', $data['txt_quote'.$i.'_'.$x]) && 
						//		$quote['LEAD_TIME'] == $data['delivery_time'.$i.'_'.$x] && 
						//		$quote['COUNTER_OFFER'] == $data['txt_counteroffer'.$i.'_'.$x] &&
						//		$quote['LINE_ID'] == $data['rfqrfbline_id'.$i]){
						//		$quote_existed = true;
						//		break;
						//	}
						//}
						//
						if((isset($data['quoteischecked'.$i.'_'.$x])) &&
								(isset($data['txt_quote'.$i.'_'.$x])) &&
								(isset($data['delivery_time'.$i.'_'.$x])) &&
								(isset($data['txt_counteroffer'.$i.'_'.$x])) &&
								(isset($data['hidden_quote_path_'.$i.'_'.$x]))
								){
												
							//Get response id according to version
							$rid = $response_id;
							foreach($responses_version as $rv){
								if($rv->VERSION == $version[0]->VERSION){
									$rid = $rv->RESPONSE_ID;
									$test_rid[] = $rid;
									break;
								}
							}				
							
							$quote_insert = array(
								'RESPONSE_ID' 			=> $rid,
								'RFQRFB_ID' 			=> $data['rfx_id'],
								'LINE_ID' 				=> $data['rfqrfbline_id'.$i],
								'QUOTE' 				=> $data['quoteischecked'.$i.'_'.$x],
								'QUOTE_AMOUNT'			=> str_replace(',', '', $data['txt_quote'.$i.'_'.$x]),
								'LEAD_TIME'				=> $data['delivery_time'.$i.'_'.$x],
								'COUNTER_OFFER'			=> $data['txt_counteroffer'.$i.'_'.$x],
								'ATTACHMENT_PATH'		=> $data['hidden_quote_path_'.$i.'_'.$x],
								'DATE_CREATED'			=> $data['date_created'],
								'INVITE_ID'				=> $data['invite_id'],
								'ACTIVE'				=> $data['active'],
								'DATE_CREATED'			=> $date_timestamp,
								'SHORTLISTED'			=> $shortlist,
								'VERSION' 	 			=> ((!empty($version) ? ($version[0]->VERSION + 1): 1))
							);
							
							
							$this->db->insert('SMNTP_RFQRFB_RESPONSE_QUOTE', $quote_insert);	
						}
					}
				}
			}
		}
		return 'success';
	}

	function get_response_draft_data($data)
	{



		if(!empty($data['shortlisted']))
		{
			///Get latest version 
			$array_where = array(
								'RFQRFB_ID' => $data['rfx_id'],
								'INVITE_ID' => $data['invite_id'],
								'LINE_ID' => $data['lineid'],
								'SHORTLISTED' => 1
								);
			$latest_version  = $this->db->query('SELECT VERSION
												FROM SMNTP_RFQRFB_RESPONSE_QUOTE 
												WHERE RFQRFB_ID = ? 
														AND INVITE_ID = ? 
														AND LINE_ID = ? 
														AND SHORTLISTED = 1 
												ORDER BY VERSION DESC FETCH FIRST 1 ROWS ONLY',
												array(
													'RFQRFB_ID' => $data['rfx_id'],
													'INVITE_ID' => $data['invite_id'],
													'LINE_ID' => $data['lineid']
												))->result();
			$array_where = array(
								'RFQRFB_ID' => $data['rfx_id'],
								'INVITE_ID' => $data['invite_id'],
								'LINE_ID' => $data['lineid'],
								'SHORTLISTED' => 1,
								'VERSION' => $latest_version[0]->VERSION //Removed since line has different version: $data['version']
								);
		}
		else
		{
			$array_where = array(
								'RFQRFB_ID' => $data['rfx_id'],
								'LINE_ID' => $data['lineid'],
								'INVITE_ID' => $data['invite_id']

								);
		}


		$this->db->select(
						  'QUOTE_AMOUNT,
						   QUOTE,
						   LEAD_TIME,
						   COUNTER_OFFER,
						   ATTACHMENT_PATH'
						  );
		$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
		$this->db->where($array_where);
		$this->db->order_by('RESPONSE_QUOTE_ID', 'ASC');

		$query = $this->db->get();

		$new_data['result'] = $query->result();
		$new_data['num_rows'] = $query->num_rows();

		return $new_data;
	}

	function get_response_data($data)
	{

		$this->db->select(
						  'VERSION'
						  );
		$this->db->from('SMNTP_RFQRFB_RESPONSE');
		$this->db->where('RFQRFB_ID', $data['rfx_id'], 1);
		$this->db->order_by('VERSION', 'DESC');

		$query = $this->db->get();
		$version = $query->row()->VERSION;

		// if(!empty($data['shortlisted']))
		// {
			$array_where = array(
								'RFQRFB_ID' => $data['rfx_id'],
								'INVITE_ID' => $data['invite_id'],
								'LINE_ID' => $data['lineid'],
								'VERSION' => $version
								// 'VERSION' => $data['version']
								);
		// }
		// else
		// {
		// 	$array_where = array(
		// 						'RFQRFB_ID' => $data['rfx_id'],
		// 						'LINE_ID' => $data['lineid'],
		// 						'INVITE_ID' => $data['invite_id']

		// 						);
		// }


		$this->db->select(
						  'QUOTE_AMOUNT,
						   LEAD_TIME,
						   COUNTER_OFFER,
						   ATTACHMENT_PATH'
						  );
		$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
		$this->db->where($array_where);
		$this->db->order_by('RESPONSE_QUOTE_ID', 'ASC');

		$query = $this->db->get();

		$new_data['result'] = $query->result();
		$new_data['num_rows'] = $query->num_rows();

		return $new_data;
	}

	function get_response_status_version($array_where)
	{
		$this->db->select('INVITE_ID, RFQRFB_ID, STATUS_ID, POSITION_ID');
		$this->db->from('SMNTP_RFQRFB_INVITE_STATUS');
		$this->db->where($array_where);

		$query = $this->db->get();

		return $query;
	}

	function get_quotes($array_where)
	{
		$this->db->select('INVITE_ID, RFQRFB_ID, VERSION');
		$this->db->from('SMNTP_RFQRFB_RESPONSE_QUOTE');
		$this->db->where($array_where);
		$this->db->group_by('INVITE_ID, RFQRFB_ID, VERSION');
		$this->db->order_by('VERSION', 'ASC');

		$query = $this->db->get();

		return $query;
	}

	// ------------------------------- END OF RESPONSE CREATION -------------------------------

	//------------------------------- RFQ MAIN VIEW -----------------------------------------
	function get_status_filter($var)
	{

		$this->db->select('*');
		$this->db->from('SMNTP_STATUS');
		$this->db->where('ACTIVE', 1);
		$this->db->where('STATUS_TYPE', $var['status_type']);

		$result = $this->db->get();

		return $result->result_array();
	}

	function get_buyer_filter($var)
	{

		$this->db->select('*');
		$this->db->from('SMNTP_USERS');
		$this->db->where('USER_STATUS', 1);
		$this->db->where('POSITION_ID', $var['buyer_position_id']);

		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result_array();
	}


	function get_requestor_filter($var)
	{

		$this->db->select('*');
		$this->db->from('SMNTP_RFX_REQUESTOR');
		$this->db->where('ACTIVE', 1);

		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result_array();
	}

	function get_purpose_filter($var)
	{

		$this->db->select('*');
		$this->db->from('SMNTP_RFX_PURPOSE');
		$this->db->where('ACTIVE', 1);

		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result_array();
	}

	//------------------------------- RFQ MAIN VIEW -----------------------------------------

	// ------------------------------- BID MONITOR ------------------------------------------
	function num_participants($data)
	{
		$this->db->select('COUNT(*) as TOTALCOUNT');
		$this->db->from('SMNTP_RFQRFB_ACKNOWLEDGEMENT');
		$this->db->where('RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();

		$new_data['result'] = $query->result();
		$new_data['num_rows'] = $query->num_rows();

		return $new_data;
	}

	function num_invited($data)
	{
		$this->db->select('COUNT(*) as TOTALCOUNT');
		$this->db->from('SMNTP_RFQRFB_INVITED_VENDORS');
		$this->db->where('RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();

		$new_data['result'] = $query->result();
		$new_data['num_rows'] = $query->num_rows();

		return $new_data;
	}

	function num_responses($data)
	{
		$this->db->select('COUNT(*) as TOTALCOUNT');
		$this->db->from('SMNTP_RFQRFB_RESPONSE');
		$this->db->where('RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();

		$new_data['result'] = $query->result();
		$new_data['num_rows'] = $query->num_rows();

		return $new_data;
	}

	function get_vendor_invite_list($value, $user_id)
	{
		$this->db->select('VI.VENDOR_NAME, VI.VENDOR_INVITE_ID,  V.VENDOR_ID');
		$this->db->from('SMNTP_VENDOR_INVITE VI');
		$this->db->join('SMNTP_VENDOR V', 'V.VENDOR_INVITE_ID=VI.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_CATEGORIES VC', 'VC.VENDOR_INVITE_ID=VI.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_USER_CATEGORY UC', 'VC.CATEGORY_ID=UC.CATEGORY_ID');
		$this->db->where('UC.CATEGORY_ID IN (SELECT CATEGORY_ID FROM SMNTP_USER_CATEGORY WHERE USER_ID = '. $user_id .')', NULL, FALSE);

		if(!empty($value))
			$this->db->where('UPPER(VI.VENDOR_NAME) LIKE \'%'.strtoupper($value).'%\'');

		$query = $this->db->get();
		return $query->result_array();
	}

	// ----------------------------- END BID MONITOR ----------------------------------------

	//--------------------------------
	function get_data_main_table($data)
	{

		$this->db->select( '
							(CURRENT_DATE - R.DATE_CREATED) AS DATE_SORTING_FORMAT,
							R.RFQRFB_ID,
							R.TITLE,
							R.RFQRFB_TYPE,
							R.CURRENCY_ID,
							R.DELIVERY_DATE,
							R.SUBMISSION_DEADLINE,
							R.REQUESTOR_ID,
							R.PURPOSE_ID,
							R.OTHER_PURPOSE,
							R.REASON_ID,
							R.OTHER_REASON,
							R.INTERNAL_NOTE,
							R.CREATED_BY,
							CAST(R.DATE_CREATED AS DATE) AS DATE_CREATED,

							RS.POSITION_ID,
							RS.STATUS_ID,
							RT.RFQRFB_TYPE_NAME,
							U.USER_LAST_NAME || U.USER_FIRST_NAME AS CREATEDBY

							', FALSE);
		$this->db->from('SMNTP_RFQRFB R');
		$this->db->join('SMNTP_RFQRFB_STATUS RS', 'RS.RFQRFB_ID 	= R.RFQRFB_ID', 'LEFT');
		$this->db->join('SMNTP_RFQRFB_TYPE RT', 'R.RFQRFB_TYPE 	= RT.RFQRFB_TYPE_ID', 'LEFT');
		$this->db->join('SMNTP_USERS U', 'U.USER_ID 	= R.CREATED_BY', 'LEFT');

		if($data['rfq_id'] == 0)
			$this->db->where('R.TITLE', $data['title']);
		else
			$this->db->where('R.RFQRFB_ID', $data['rfq_id']);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query;
	}

	function get_lines_data_table($data)
	{
		$this->db->select( 'R.RFQRFB_ID,
							RL.RFQRFB_LINE_ID,
							RL.CATEGORY_ID,
							RL.DESCRIPTION,
							RL.UNIT_OF_MEASURE,
							RL.QUANTITY,
							RL.SPECIFICATION,
							RL.UNIT_OF_MEASURE
							', FALSE);
		$this->db->from('SMNTP_RFQRFB R');
		$this->db->join('SMNTP_RFQRFB_LINE RL', 'R.RFQRFB_ID = RL.RFQRFB_ID', 'LEFT');
		$this->db->join('SMNTP_UNIT_OF_MEASURE RUM', 'RUM.UNIT_OF_MEASURE 	= RL.UNIT_OF_MEASURE', 'LEFT');
		$this->db->where('R.RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query;
	}

	function get_attachment_data_table($data)
	{
		$this->db->select( 'R.RFQRFB_ID,
							RLA.RFQRFB_LINE_ID,
							RLA.LINE_ATTACHMENT_ID,
							RLA.FILE_PATH,
							RLA.ATTACHMENT_TYPE,
							RLA.DESCRIPTION as A_DESCRIPTION,
							AT.LOGO_PATH
							', FALSE);
		$this->db->from('SMNTP_RFQRFB R');
		$this->db->join('SMNTP_RFQRFB_LINE RL', 'R.RFQRFB_ID = RL.RFQRFB_ID', 'LEFT');
		$this->db->join('SMNTP_RFQRFB_LINE_ATTACHMENT RLA', 'RL.RFQRFB_LINE_ID 	= RLA.RFQRFB_LINE_ID AND R.RFQRFB_ID=RLA.RFQRFB_ID', 'LEFT');
		$this->db->join('SMNTP_ATTACHMENT_TYPES AT', 'RLA.ATTACHMENT_TYPE 	= AT.ATTACHMENT_TYPE_ID', 'LEFT');
		$this->db->where('R.RFQRFB_ID', $data['rfx_id']);
		$this->db->order_by('RLA.LINE_ATTACHMENT_ID', 'asc');

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query;
	}

	function get_invited_data_table($data)
	{
		$this->db->select( 'R.RFQRFB_ID,
							VI.USER_ID,
							VI.VENDOR_NAME,
							RIV.VENDOR_ID,
							RIV.INVITE_ID,
							VI.VENDOR_INVITE_ID
							', FALSE);
		$this->db->from('SMNTP_RFQRFB R');
		$this->db->join('SMNTP_RFQRFB_INVITED_VENDORS RIV', 'R.RFQRFB_ID = RIV.RFQRFB_ID', 'INNER');
		$this->db->join('SMNTP_VENDOR_INVITE VI', 'VI.VENDOR_INVITE_ID = RIV.INVITE_ID', 'INNER');
		$this->db->where('R.RFQRFB_ID', $data['rfx_id']);

		$query = $this->db->get();
		//echo $this->db->last_query();
		return $query;
	}

	function get_attachment_location($type)
	{
		$this->db->select('LOGO_PATH');
		$this->db->from('SMNTP_ATTACHMENT_TYPES');
		$this->db->where('ATTACHMENT_TYPE_ID', $type);

		$query = $this->db->get();

		return $query->row(0)->LOGO_PATH;
	}


	function get_rfq_history($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
        $this->db->from('SMNTP_RFQRFB_STATUS_LOGS RSL');
        $this->db->join('SMNTP_STATUS SS', 'RSL.STATUS_ID = SS.STATUS_ID');
        $this->db->join('SMNTP_USERS U', 'RSL.APPROVER_ID = U.USER_ID');
        $this->db->join('SMNTP_POSITION P', 'U.POSITION_ID = P.POSITION_ID');
        $this->db->where('RSL.RFQRFB_ID', $data['rfqrfb_id']);

        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();

        if ($totalcount > 0)
        {
             if ($page_num != 0 && $rpp != 0)
             {
                  $max = $rpp * $page_num;
                  $min = $max-$rpp;
             }

             $this->db->select(' 
								(CURRENT_DATE - RSL.DATE_UPDATED) AS DATE_SORTING_FORMAT,
             					U.USER_FIRST_NAME,
             					U.USER_LAST_NAME,
                                SS.STATUS_NAME,
                                P.POSITION_NAME,
                                RSL.APPROVER_REMARKS,
								SS.STATUS_NAME,
								CAST(DATE_FORMAT(RSL.DATE_UPDATED,"%m/%d/%y %h:%i:%s %p") AS CHAR) AS DATE_UPDATED', FALSE);
             $this->db->order_by('RSL.RFQRFB_STATUS_LOG_ID', 'DESC');

             // if ($page_num != 0 && $rpp != 0)
             // {
                  // $this->db->limit($rpp,$min);
             // }

             $query = $this->db->get();
             // echo $this->db->last_query();
             $valid = TRUE;
             //$query->row()->DATE_UPDATED =
             $finalquery = $query->result_array();
             $resultscount     = $query->num_rows();
        }

        $this->db->flush_cache();

        $data['resultscount'] = $resultscount;
        $data['totalcount'] = $totalcount;
        $data['valid'] = $valid;
        $data['query'] = $finalquery;

        return $data;
	}

	function get_mail_template($query_filter)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_MESSAGE_DEFAULT');
		$this->db->where($query_filter);

		$query = $this->db->get();

		return $query;
	}

	function user_data($user_id)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_USERS');
		$this->db->where('USER_ID', $user_id);

		$query = $this->db->get();

		return $query;
	}

}

?>
