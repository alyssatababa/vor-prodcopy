<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Registration_model extends CI_Model
{

	function get_registration_main($data)
	{
		$rpp 			= $data['rpp'];
		$page_num 		= 1; //$data['page_num'];

		$valid 			= FALSE;
        $query 			= '';
        $resultscount 	= '';
        $finalquery 	= '';

        $this->db->start_cache();
		$this->db->select('SVI.VENDOR_INVITE_ID');
        $this->db->from('SMNTP_VENDOR_INVITE SVI');
        $this->db->join('SMNTP_VENDOR SV', 'SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID', 'LEFT');
        $this->db->join('SMNTP_VENDOR_TOKEN SVT', 'SVT.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID', 'LEFT');
        $this->db->join('SMNTP_VENDOR_BRAND SVB', 'SVB.VENDOR_ID = SV.VENDOR_ID', 'LEFT');
        $this->db->join('SMNTP_VENDOR_REP SVR', 'SVR.VENDOR_ID = SV.VENDOR_ID', 'LEFT');
        $this->db->join('SMNTP_VENDOR_STATUS SVS', 'SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
        //$this->db->join('SMNTP_VENDOR_STATUS_LOGS SVSL', 'SVI.VENDOR_INVITE_ID = SVSL.VENDOR_INVITE_ID','LEFT');
        $this->db->join('SMNTP_STATUS SS', 'SVS.STATUS_ID = SS.STATUS_ID');
        $this->db->join('SMNTP_USERS_MATRIX UM', 'SVI.CREATED_BY = UM.USER_ID');
        $this->db->join('(SELECT B1.VENDOR_INVITE_ID,DATE_UPDATED FROM SMNTP_VENDOR_STATUS_LOGS B1 WHERE B1.STATUS_ID = 19 OR B1.STATUS_ID = 190 ORDER BY B1.VENDOR_STATUS_LOGS_ID DESC) SVSL', 'SVSL.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID', 'LEFT');
        
		//jay
		if (!empty($data['brand_name'])){
			$this->db->join('SMNTP_BRAND SB', 'SB.BRAND_ID = SVB.BRAND_ID');
		}
		
        $this->db->join('SMNTP_STATUS_CONFIG SSC', 'SVS.STATUS_ID = SSC.CURRENT_STATUS_ID AND SSC.CURRENT_POSITION_ID =  SVS.POSITION_ID AND (SVS.POSITION_ID = '.$data['position_id'].' OR SVS.POSITION_ID ='.$data['override_position_id'].') AND SVI.REGISTRATION_TYPE = SSC.REGISTRATION_TYPE_ID', 'LEFT');
        $this->db->join('SMNTP_MESSAGES MES', 'MES.INVITE_ID = SVI.VENDOR_INVITE_ID AND MES.RECIPIENT_ID = '.$data['user_id'].' AND MES.IS_READ = -1', 'LEFT');
        $this->db->join('(SELECT COUNT(*) AS CNT, INVITE_ID FROM SMNTP_MESSAGES WHERE RECIPIENT_ID = '.$data['user_id'].' AND IS_READ = -1 GROUP BY INVITE_ID) AS MSG_CNT', 'MSG_CNT.INVITE_ID = MES.INVITE_ID', 'LEFT');

        if(!empty($data['dashboard_status_id']) || $data['dashboard_status_id'] != 0){
        	 $this->db->where('SVS.STATUS_ID',$data['dashboard_status_id']);
        }

       

        if ($data['position_id'] == 2 || $data['position_id'] == 7 || $data['position_id'] == 11) // sen mer
        {
        		$this->db->where('SVI.CREATED_BY', $data['user_id']);
				//$this->db->where_not_in('SVI.REGISTRATION_TYPE', array(2,3))
        		/*$this->db->where('SVS.STATUS_ID',11);*/
        	}      	
        else if ($data['position_id'] == 10) // vendor
        	$this->db->where('SVI.USER_ID', $data['user_id']);

		if($data['position_id'] == 2)
		{
			//$this->db->where_in('SVI.REGISTRATION_TYPE', array(1,3,4,5));
			$where = "SVI.REGISTRATION_TYPE IN (1, 3, 4, 5) OR (SVI.REGISTRATION_TYPE = 2 AND SVS.STATUS_ID IN (250,251,252,253,254,255) AND SVI.CREATED_BY = '".$data['user_id']."')";
			$this->db->where($where, NULL, FALSE);
		}
		
		if($data['position_id'] == 3)
		{
			$this->db->where('UM.BUHEAD_ID', $data['user_id']);
			//$this->db->where_in('SVI.REGISTRATION_TYPE', array(1,3,4,5));
			$where = "SVI.REGISTRATION_TYPE IN (1, 3, 4, 5) OR (SVI.REGISTRATION_TYPE = 2 AND SVS.STATUS_ID IN (250,251,252,253,254,255) AND UM.BUHEAD_ID = '".$data['user_id']."')";
			$this->db->where($where, NULL, FALSE);
		}

		if($data['position_id'] == 8)
		{
			$this->db->where('UM.GHEAD_ID', $data['user_id']);
		}

		if($data['position_id'] == 4)
		{
			$this->db->where('UM.VRDSTAFF_ID', $data['user_id']);
		}

		if($data['position_id'] == 5)
		{
			$this->db->where('UM.VRDHEAD_ID', $data['user_id']);
		}

		if($data['position_id'] == 9)
		{
			$this->db->where('UM.FASHEAD_ID', $data['user_id']);
		}

        // else
        	// $this->db->where('SVS.POSITION_ID', $data['position_id']);

		  if ($data['position_id'] == 3){
			$this->db->where_in('SVI.BUSINESS_TYPE', array(1,3));
			//$this->db->or_where('SVI.BUSINESS_TYPE', 3);
		  }
		 if ($data['position_id'] == 8)
			$this->db->where('SVI.BUSINESS_TYPE', 2);

		 if ($data['position_id'] == 9)
			$this->db->where('SVI.BUSINESS_TYPE', 2);
		
		/* if ($data['position_id'] == 11)
			$this->db->where('SVI.BUSINESS_TYPE', 3);*/
		
        if (array_key_exists('vendorname', $data))
        {
             if (!empty($data['vendorname']))
                  $this->db->where('UPPER(SVI.VENDOR_NAME) like upper(\'%'.$data['vendorname'].'%\')');
        }

        if (array_key_exists('vendor_type', $data))
        {
             if (!empty($data['vendor_type']))
                  $this->db->where('SVI.BUSINESS_TYPE', $data['vendor_type']);
        }
		
        if (array_key_exists('trade_vendor_type', $data))
        {
             if (!empty($data['trade_vendor_type']))
                  $this->db->where('SVI.TRADE_VENDOR_TYPE', $data['trade_vendor_type']);
        }

        if (array_key_exists('tin_no', $data))
        {
             if (!empty($data['tin_no']))
                  $this->db->where('SV.TAX_ID_NO', $data['tin_no']);
        }

       	if (array_key_exists('auth_rep', $data))
		{
			//jay
			if (!empty($data['auth_rep'])){
				$auth_array = explode(' ', $data['auth_rep']);
				$auth_array = array_map('strtoupper', $auth_array);
				$auth_array = array_map('trim', $auth_array);
				$auth_array = array_map(function($item){
					return trim($item,'.');
				}, $auth_array);
				$auth_str = '(';
				
				$valid = FALSE;
				foreach($auth_array as $value){
					if(count($auth_array) > 1 && strlen($value) > 1){
						$auth_str .= 'UPPER(SVR.NAME) like upper(\'%'.$value.'%\') ';
						$auth_str .= 'OR ';
						$valid = TRUE;
					}else if(count($auth_array == 1)){
						$auth_str .= 'UPPER(SVR.NAME) like upper(\'%'.$value.'%\') ';
						$valid = TRUE;
					}
				}
				
				$auth_str = trim($auth_str, 'OR ');
				$auth_str .= ')';
				if($valid){
					$this->db->where($auth_str);		
				}
            }
        }

        if (array_key_exists('brand_id', $data))
        {
        	if (!empty($data['brand_name'])){
        		$this->db->like('upper(SB.BRAND_NAME)', strtoupper($data['brand_name']));
        	}else if (!empty($data['brand_id']) || ($data['brand_id'] !== '' && $data['brand_id'] == '-1')){
             	$this->db->where('SVB.BRAND_ID', $data['brand_id']);
            }
        }

        if(!empty($data['override_position_id']) && $data['override_position_id'] == 6){

        	$this->db->where_in('SVS.STATUS_ID', array('15','17','124','254'));

        }
		// Added MSF 20191129 (NA)
		else if($data['override_position_id'] == 4){
        	$this->db->where_in('SVS.STATUS_ID', array('10','12','121','194','198'));
		}else{
        if (array_key_exists('status_id', $data))
        {
             if (!empty($data['status_id']))
                  $this->db->where('SVS.STATUS_ID', $data['status_id']);
        }
   		}

        /*$this->db->where('SVS.STATUS_ID',11);*/


        $this->db->stop_cache();
        $totalcount= $this->db->get()->num_rows();
        // $data['brand'] = $data['brand_id'];

        $data['last_query1'] = $this->db->last_query();


        if ($totalcount > 0)
        {
             // if ($page_num != 0 && $rpp != 0)
             // {
                  // $max = $rpp * $page_num;
                  // $min = $max-$rpp;s
             // }
			//SELECT TO_DATE('2015/05/15 8:30:25', 'YYYY/MM/DD HH:MI:SS')

			switch ($data['sort_type']) {
            	case 'VENDOR_NAME' : 
            					$data['sort_type'] = 'SVI.'.$data['sort_type'];
            		break;
            	case 'STATUS_NAME' : 
            					$data['sort_type'] = 'SS.'.$data['sort_type'];
            		break;
            	case 'DATE_CREATED' : 
            					$data['sort_type'] = 'SVT.'.$data['sort_type'].'';
            		break;
            	case 'ACTION_LABEL' : 
            					$data['sort_type'] = 'SSC.'.$data['sort_type'];
            		break;  
            	case 'DATE_SUBMITTED' : 
            					$data['sort_type'] = 'SV.DATE_CREATED';
            		break;     

            	default:
            		# code...
            		break;
            }


			 // update MSF - 20191105 (IJR-10612), Additional SV.VENDOR_CODE
			 // additional DATEDIFF(SVS.DATE_UPDATED, NOW()) AS DATE_DIFF
			 $this->db->distinct();
             $this->db->select('
								(CURRENT_DATE -( SVI.DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_FORMAT, 
								(CURRENT_DATE -( SV.DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_REVIEWED_FORMAT, 
								SV.VENDOR_ID, SV.VENDOR_CODE,
             					SVI.VENDOR_INVITE_ID,
                                SVI.VENDOR_NAME,
                                SVI.APPROVER_NOTE,
								SS.STATUS_ID,
								SS.STATUS_NAME,
								DATEDIFF(CONVERT_TZ(CURRENT_DATE(), "UTC", "Asia/Manila"), MAX(SVSL.DATE_UPDATED)) AS DATE_DIFF,
								CONVERT_TZ(NOW(), "+00:00", "+08:00") AS CUR_DATE,
								CURDATE() + INTERVAL 1 DAY AS TOM_DATE,
								MAX(SVSL.DATE_UPDATED) AS LATEST_DATE_UPDATED,
								CAST(SVT.DATE_CREATED AS DATE) AS DATE_CREATED,
								SSC.ACTION_LABEL,
								(CASE
									WHEN SVS.STATUS_ID = 1 OR SVS.STATUS_ID = 2 OR SVS.STATUS_ID = 4 OR SVS.STATUS_ID = 8 OR SVS.STATUS_ID = 9 OR SVS.STATUS_ID = 14 OR SVS.STATUS_ID = 14 OR SVS.STATUS_ID = 16 OR SVS.STATUS_ID = 123 OR SVS.STATUS_ID = 124 OR SVS.STATUS_ID = 11 OR SVS.STATUS_ID = 122 OR SVS.STATUS_ID = 13 OR SVS.STATUS_ID = 15 OR SVS.STATUS_ID = 17 OR SVS.STATUS_ID = 5 OR SVS.STATUS_ID = 41 OR SVS.STATUS_ID = 190 OR SVS.STATUS_ID = 195 OR SVS.STATUS_ID = 197 
										THEN CONCAT(SSC.ACTION_PATH, SVI.`VENDOR_INVITE_ID`, "/'.$data['override_position_id'].'")
									WHEN SVS.STATUS_ID = 10 OR SVS.STATUS_ID = 12 OR SVS.STATUS_ID = 121 OR SVS.STATUS_ID = 198 OR SVS.STATUS_ID = 194
										THEN CONCAT(SSC.ACTION_PATH,SV.VENDOR_ID)
									WHEN SVS.STATUS_ID = 249 OR SVS.STATUS_ID = 250 OR SVS.STATUS_ID = 252 OR SVS.STATUS_ID = 254 
										THEN CONCAT(SSC.ACTION_PATH,SVI.VENDOR_INVITE_ID)
									ELSE SSC.ACTION_PATH
								END) AS ACTION_PATH,
								CAST(SV.DATE_CREATED AS DATE) AS DATE_SUBMITTED,
								(CASE
									WHEN MES.INVITE_ID != 0 THEN CONCAT(MES.INVITE_ID,\'/\',\'invite\')
									WHEN MAX(MES.VENDOR_ID) != 0 THEN CONCAT(MAX(MES.VENDOR_ID), \'/\' ,\'vendor\')
								END) AS MESSAGE_INDEX_PARAM, IFNULL(MSG_CNT.CNT,0) AS MESSAGE_COUNT
                                   ', false); //Replaced COUNT(MES.ID) to SELECT COUNT(*)
			$this->db->_protect_identifiers=false;
			$this->db->group_by('SVI.VENDOR_INVITE_ID,SV.VENDOR_ID,
             					SVI.VENDOR_INVITE_ID,
                                SVI.VENDOR_NAME,
                                SVI.APPROVER_NOTE,
								SS.STATUS_ID,
								SS.STATUS_NAME,
								SVI.DATE_CREATED,
								SSC.ACTION_LABEL,
								SSC.ACTION_PATH,
								SVS.STATUS_ID,
								SV.DATE_CREATED,
								SVT.DATE_CREATED,
								MES.INVITE_ID,
								SV.VENDOR_CODE');
             $this->db->order_by($data['sort_type'] .' '. $data['sort']);
	     $this->db->order_by('SVI.VENDOR_NAME','ASC');


             if($data['sort_type'] == "MESSAGE_COUNT"){
             	$this->db->order_by('SVI.DATE_CREATED',$data['sort']);
             }



             $temp_limit = 10;

             if($data['page_no'] !== 0){
             	$data['page_no']  = $data['page_no'];
             }

             $this->db->limit($temp_limit,$data['page_no']);


             $query = $this->db->get();
              $data['lstss'] = $data['page_no'];
             // echo $this->db->last_query();
             $valid = TRUE;
             $finalquery = $query->result_array();
             $resultscount     = $query->num_rows();



				// update MSF - 20191105 (IJR-10612), Additional SV.VENDOR_CODE
				// additional DATEDIFF(SVS.DATE_UPDATED, NOW()) AS DATE_DIFF
                $this->db->select('
								(CURRENT_DATE -( SVI.DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_FORMAT, 
								(CURRENT_DATE -( SV.DATE_CREATED - interval \'2\' hour)) AS DATE_SORTING_REVIEWED_FORMAT, 
								SV.VENDOR_ID,
             					SVI.VENDOR_INVITE_ID,
                                SVI.VENDOR_NAME,
                                SVI.APPROVER_NOTE,
								SS.STATUS_ID,
								SS.STATUS_NAME,
								DATEDIFF(MAX(SVSL.DATE_UPDATED), CONVERT_TZ(CURRENT_DATE(), "UTC", "Asia/Manila")) AS DATE_DIFF,
								CONVERT_TZ(NOW(), "+00:00", "+08:00") AS CUR_DATE,
								CURDATE() + INTERVAL 1 DAY AS TOM_DATE,
								MAX(SVSL.DATE_UPDATED) AS LATEST_DATE_UPDATED,
								CAST(SVI.DATE_CREATED AS DATE) AS DATE_CREATED,
								SSC.ACTION_LABEL,
								(CASE
									WHEN SVS.STATUS_ID = 1 OR SVS.STATUS_ID = 2 OR SVS.STATUS_ID = 4 OR SVS.STATUS_ID = 8 OR SVS.STATUS_ID = 9 OR SVS.STATUS_ID = 14 OR SVS.STATUS_ID = 14 OR SVS.STATUS_ID = 16 OR SVS.STATUS_ID = 123 OR SVS.STATUS_ID = 124 OR SVS.STATUS_ID = 11 OR SVS.STATUS_ID = 122 OR SVS.STATUS_ID = 13 OR SVS.STATUS_ID = 15 OR SVS.STATUS_ID = 17 OR SVS.STATUS_ID = 5 OR SVS.STATUS_ID = 41 OR SVS.STATUS_ID = 190 OR SVS.STATUS_ID = 195 OR SVS.STATUS_ID = 197
										THEN CONCAT(SSC.ACTION_PATH, SVI.`VENDOR_INVITE_ID`, "/'.$data['override_position_id'].'")
									WHEN SVS.STATUS_ID = 10 OR SVS.STATUS_ID = 12 OR SVS.STATUS_ID = 121 OR SVS.STATUS_ID = 198 OR SVS.STATUS_ID = 194 
										THEN CONCAT(SSC.ACTION_PATH,SV.VENDOR_ID)
									WHEN SVS.STATUS_ID = 250 OR SVS.STATUS_ID = 251 
										THEN CONCAT(SSC.ACTION_PATH,SVI.VENDOR_INVITE_ID)
									ELSE SSC.ACTION_PATH
								END) AS ACTION_PATH,
								CAST(SV.DATE_CREATED AS DATE) AS DATE_SUBMITTED,
								(CASE
									WHEN MES.INVITE_ID != 0 THEN CONCAT(MES.INVITE_ID, \'/\', \'invite\')
									WHEN MAX(MES.VENDOR_ID) != 0 THEN CONCAT(MAX(MES.VENDOR_ID), \'/\',\'vendor\')
								END) AS MESSAGE_INDEX_PARAM, IFNULL(MSG_CNT.CNT,0) AS MESSAGE_COUNT
                                   ', false); //Replaced COUNT(MES.ID) to SELECT COUNT(*)
			$this->db->_protect_identifiers=false;
			$this->db->group_by('SVI.VENDOR_INVITE_ID,SV.VENDOR_ID,
             					SVI.VENDOR_INVITE_ID,
                                SVI.VENDOR_NAME,
                                SVI.APPROVER_NOTE,
								SS.STATUS_ID,
								SS.STATUS_NAME,
								SVI.DATE_CREATED,
								SSC.ACTION_LABEL,
								SSC.ACTION_PATH,
								SVS.STATUS_ID,
								SV.DATE_CREATED,
								SVT.DATE_CREATED,
								MES.INVITE_ID,
								SV.VENDOR_CODE');
             $this->db->order_by($data['sort_type'] .' '. $data['sort']);

             $rst = $this->db->get();
             $rst = $rst->num_rows();
             $data['total_page'] = $rst;

             
        }

        $this->db->flush_cache();

		if ($resultscount > 0){
			foreach($finalquery as $key => $value)
			{
				if($value['DATE_CREATED']){

					$finalquery[$key]['DATE_CREATED'] = date('m/d/Y',strtotime($value['DATE_CREATED']));
				}

				if($value['DATE_SUBMITTED']){
					$finalquery[$key]['DATE_SUBMITTED'] = date('m/d/Y',strtotime($value['DATE_SUBMITTED']));
				}


				// if($value['STATUS_ID'] == 190){
				// 	//category
				// 	$this->db->select('SVC.CATEGORY_ID');
				// 	$this->db->from('SMNTP_VENDOR_CATEGORIES SVC');
				// 	$this->db->where(array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']));
				// 	$res = $this->db->get()->result_array;
				// 	//ownership && vendor_type
				// 	$data = array('VENDOR_ID'=> $value['VENDOR_ID']);
				// 	//$this->db->select('A.OWNERSHIP_ID,E.TRADE_VENDOR_TYPE');
				// 	$this->db->select('*');
				// 	$this->db->from('SMNTP_VENDOR A');
				// 	$this->db->join('SMNTP_OWNERSHIP B', 'A.OWNERSHIP_TYPE=B.OWNERSHIP_ID', 'left');
				// 	$this->db->join('SMNTP_VENDOR_TYPE C', 'A.VENDOR_TYPE=C.VENDOR_TYPE_ID', 'left');
				// 	$this->db->join('SMNTP_VENDOR_STATUS D', 'A.VENDOR_INVITE_ID=D.VENDOR_INVITE_ID', 'left');
				// 	$this->db->join('SMNTP_TERMS_PAYMENT E', 'D.TERMSPAYMENT = E.TERMS_PAYMENT_ID', 'LEFT');
				// 	$this->db->where($data);

				// 	$owner = $this->db->get()->result_array();

				// //	return array('s'=>$key);
				// 	if(isset($owner[0]['OWNERSHIP_ID'])){

				// 	$cat = array();

				// 	foreach ($res as $keys => $val) {
				// 		array_push($cat,$val['CATEGORY_ID']);
				// 	}

				// 	$data = array(
				// 		'ownership' => $owner[0]['OWNERSHIP_ID'],
				// 		'trade_vendor_type' => $owner[0]['TRADE_VENDOR_TYPE'],
				// 		'category_id' => $cat
				// 		);


				// 	$rf = $this->get_ra_docs2($data);

				// /*	return array('a'=>$rf);*/
				// //	return array('sval' => $value,'k'=>$key);
				// 	if($rf == '-1'){
				// 		$finalquery[$key]['ACTION_LABEL'] = 'Schedule Request for visit';
				// 		$finalquery[$key]['STATUS_NAME'] = 'Schedule Request for visit';
				// 		$finalquery[$key]['ACTION_PATH'] = 'vendor\/registrationreview\/registration_details\/'.$value['VENDOR_ID'];
				// 	}	
				// 	}
				// }

			}
		}
		$data['resultscount'] = $resultscount;
		$data['query'] = $finalquery;

       return $data;
	}

	function get_for_hats_approval()
	{

		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where_in('STATUS_ID', array(15, 17, 124, 254)); //for hats approval & suspended by hats

		$result = $this->db->get();

		return $result->result_array();
	}

	function get_brand_filter($var)
	{

		$this->db->select('*');
		$this->db->where('STATUS', 1); // its like active
		$this->db->from('SMNTP_BRAND');
		$this->db->order_by('BRAND_NAME');
		$this->db->DISTINCT();

		$result = $this->db->get();

		return $result->result_array();
	}

	function get_status_filter($var)
	{

		$this->db->select('*');
		$this->db->from('SMNTP_STATUS');
		$this->db->where('ACTIVE', 1);
		$this->db->where('STATUS_TYPE', $var['status_type']);
		$this->db->order_by('STATUS_SORT');

		$result = $this->db->get();

		return $result->result_array();
	}

	function check_dpa($data)
	{
		$valid = false;

		$this->db->select('USER_ID');
		$this->db->from('SMNTP_VENDOR_DPA');
		$this->db->where($data);

		$query = $this->db->get();

		if ($query->num_rows() > 0) // if record found it means nag accept na sya
			$valid = true;

		return $valid;
	}

	function get_rsd_docs($data = array())
	{

		// Jay
		// For Non Trade and NTS
		
		// Vendor TYPE
		// 1 = Trade
		// 2 = Non trade
		// 3 = Non Trade Service(NTS)
		
		// Trade Vendor TYPE
		// 1 = Outright
		// 2 = Consignor
		
		// SMNTP_VP_REQUIRED_DOCS_DEFN
		// VENDOR_TYPE_ID Meaning
		// NULL = Non Trade
		// 1 = Outright
		// 2 = Consignor
		// 3 = Non Trade Service(NTS)
		
		$vendor_type = NULL;
		if(empty($data['trade_vendor_type']) OR $data['trade_vendor_type'] == 'null'){
			if($data['vendor_type'] == 3){
				$vendor_type = 3;
			}
			
		}else{
			$vendor_type = $data['trade_vendor_type'];
		}
		
		$join_info1 = $join_info2 = '';
		
		if( ! empty($vendor_type)){
			//Trade(Outright or Consignor) or NTS Vendor Type ID
			$join_info1 = '(SVRAD2.VENDOR_TYPE_ID = '. $vendor_type .') ';
			$join_info2 = '(SVRAD.VENDOR_TYPE_ID = '. $vendor_type .') ';
		}else{
			// Non Trade
			$join_info1 = '(SVRAD2.VENDOR_TYPE_ID IS NULL) ';
			$join_info2 = '(SVRAD.VENDOR_TYPE_ID IS NULL) ';
		}
		$result = $this->db->query('SELECT DISTINCT SVRA.*,SVRAD.*, C.DOCUMENT_TYPE,
						CASE 
							WHEN (SELECT COUNT(*) FROM SMNTP_VP_REQUIRED_DOCUMENTS SVRA2
							JOIN SMNTP_VP_REQUIRED_DOCS_DEFN SVRAD2 
							ON SVRA2.REQUIRED_DOCUMENT_ID = SVRAD2.DOC_ID
							JOIN SMNTP_VENDOR_WAIVE_PD_FLAG D 
							ON SVRA.REQUIRED_DOCUMENT_ID = D.REQDOCS_ID AND
							SVRAD2.OWNERSHIP_ID = ? AND ' . $join_info1 . '
							WHERE SVRA2.ACTIVE =  1 AND D.VENDOR_INVITE_ID = ?) > 0 THEN \'checked\'
							ELSE NULL
						END AS NA 
						FROM SMNTP_VP_REQUIRED_DOCUMENTS SVRA
						INNER JOIN SMNTP_VP_REQUIRED_DOCS_DEFN SVRAD 
						ON SVRA.REQUIRED_DOCUMENT_ID = SVRAD.DOC_ID AND
						SVRAD.OWNERSHIP_ID = ? AND ' . $join_info2 . ' 
						LEFT JOIN SMNTP_INCOMPLETE_DOC_REASONS C ON 
						SVRAD.DOC_ID = C.DOCUMENT_ID 
						WHERE SVRA.ACTIVE =  1 ORDER BY SVRA.REQUIRED_DOCUMENT_NAME', 
						array(
							$data['ownership'],
							$data['invite_id'],
							$data['ownership']))->result_array();
		//return $this->db->last_query();
		return $result;		
		
		// Old Code
		/*if (!empty($data['trade_vendor_type']) && !empty($data['ownership'])&& $data['trade_vendor_type'] != 4){
			$join_info1 ='';
			$join_info2 ='';
			if($data['trade_vendor_type'] == 3){
				$join_info1 = '(SVRAD2.VENDOR_TYPE_ID IS NULL) ';
				$join_info2 = '(SVRAD.VENDOR_TYPE_ID IS NULL) ';
			}else{
				if($data['trade_vendor_type'] == 1){
					//Get the Trade Type
					if(!empty($data['invite_id'])){
						$trade_type = $this->db->query('SELECT TRADE_VENDOR_TYPE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ?', array($data['invite_id']))->result_array();
						
						if(!empty($trade_type)){
							$join_info1 = '(SVRAD2.VENDOR_TYPE_ID = '.$trade_type[0]['TRADE_VENDOR_TYPE'].') ';
							$join_info2 = '(SVRAD.VENDOR_TYPE_ID = '.$trade_type[0]['TRADE_VENDOR_TYPE'].') ';
						}
					}
				}else{
					$join_info1 = '(SVRAD2.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].') ';
					$join_info2 = '(SVRAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].') ';
				}
			}
			$result = $this->db->query('SELECT SVRA.*,SVRAD.*, 
							CASE 
								WHEN (SELECT COUNT(*) FROM SMNTP_VP_REQUIRED_DOCUMENTS SVRA2
								JOIN SMNTP_VP_REQUIRED_DOCS_DEFN SVRAD2 
								ON SVRA2.REQUIRED_DOCUMENT_ID = SVRAD2.DOC_ID
								JOIN SMNTP_VENDOR_WAIVE_PD_FLAG D 
								ON SVRA.REQUIRED_DOCUMENT_ID = D.REQDOCS_ID AND
								SVRAD2.OWNERSHIP_ID = ? AND ' . $join_info1 . '
								WHERE SVRA2.ACTIVE =  1 AND D.VENDOR_INVITE_ID = ?) > 0 THEN \'checked\'
								ELSE NULL
							END AS NA 
							FROM SMNTP_VP_REQUIRED_DOCUMENTS SVRA
							JOIN SMNTP_VP_REQUIRED_DOCS_DEFN SVRAD 
							ON SVRA.REQUIRED_DOCUMENT_ID = SVRAD.DOC_ID AND
							SVRAD.OWNERSHIP_ID = ? AND ' . $join_info2 . '
							WHERE SVRA.ACTIVE =  1', 
							array(
								$data['ownership'],
								$data['invite_id'],
								$data['ownership']))->result_array();
			return $result;		
		}else{
			$this->db->select('*');
			$this->db->from('SMNTP_VP_REQUIRED_DOCUMENTS SVRD');

			//jay
			if (!empty($data['trade_vendor_type'])){
				if($data['trade_vendor_type'] == 4){
					$join_info = ' AND (SVRDD.VENDOR_TYPE_ID = 3) ';
				}elseif ($data['trade_vendor_type'] == 3){
					$join_info = ' AND (SVRDD.VENDOR_TYPE_ID IS NULL) ';
					
				}else{
					$join_info = ' AND (SVRDD.VENDOR_TYPE_ID = 3) '; // 3 = NTS
				}
			}
			else{
				$join_info = '';
			}

			if (!empty($data['ownership']))
				$this->db->join('SMNTP_VP_REQUIRED_DOCS_DEFN SVRDD', 'SVRD.REQUIRED_DOCUMENT_ID = SVRDD.DOC_ID AND SVRDD.OWNERSHIP_ID = '.$data['ownership'].$join_info);

			$this->db->where('SVRD.ACTIVE',1);

			$result = $this->db->get();

			// return $this->db->last_query();
			return $result->result_array();
		}*/

	}
	
	function get_primary_others_docs(){
		return $this->db->query('SELECT DISTINCT A.*, B.DOCUMENT_TYPE FROM SMNTP_VP_REQUIRED_DOCUMENTS A INNER JOIN SMNTP_INCOMPLETE_DOC_REASONS B ON A.REQUIRED_DOCUMENT_ID = B.DOCUMENT_ID WHERE LOWER(REQUIRED_DOCUMENT_NAME) = ? AND A.ACTIVE = 1 AND B.ACTIVE = 1', array('others'))->result_array();
	}
	
	function get_additional_others_docs(){
		return $this->db->query('SELECT DISTINCT A.*, B.DOCUMENT_TYPE FROM SMNTP_VP_REQUIRED_AGREEMENTS A INNER JOIN SMNTP_INCOMPLETE_DOC_REASONS B ON A.REQUIRED_AGREEMENT_ID = B.DOCUMENT_ID WHERE LOWER(REQUIRED_AGREEMENT_NAME) = ? AND A.ACTIVE = 1 AND B.ACTIVE = 1', array('others'))->result_array();
	}
	
	function get_ra_docs($data = array())
	{
		// Jay
		// For Non Trade and NTS
		
		// Vendor TYPE
		// 1 = Trade
		// 2 = Non trade
		// 3 = Non Trade Service(NTS)
		
		// Trade Vendor TYPE
		// 1 = Outright
		// 2 = Consignor
		
		// SMNTP_VP_REQUIRED_AGMT_DEFN
		// VENDOR_TYPE_ID Meaning
		// NULL = Non Trade
		// 1 = Outright
		// 2 = Consignor
		// 3 = Non Trade Service(NTS)

		if(empty($data['ownership'])){
			return -1;
		}
		
		$vendor_type = NULL;
		if(empty($data['trade_vendor_type']) OR $data['trade_vendor_type'] == 'null'){
			if($data['vendor_type'] == 3){
				$vendor_type = 3;
			}
		}else{
			$vendor_type = $data['trade_vendor_type'];
		}
		
		$join_info1 = $join_info2 = '';
		$result = NULL;
		// Get ARD BY:
		/*if($data['vendor_type'] == 1){
			// Trade
			// OWNERSHIP_ID, VENDOR_TYPE, TRADE_VENDOR_TYPE
			// VENDOR TYPE 1 = Outright , 2 = Consignor
			
			$query = 'SELECT DISTINCT A.*, B.*, C.DOCUMENT_TYPE FROM SMNTP_VP_REQUIRED_AGMT_DEFN A
						LEFT JOIN SMNTP_VP_REQUIRED_AGREEMENTS B ON A.AGREEMENT_ID = B.REQUIRED_AGREEMENT_ID 
						LEFT JOIN SMNTP_INCOMPLETE_DOC_REASONS C ON 
						A.AGREEMENT_ID = C.DOCUMENT_ID  
						WHERE A.VENDOR_TYPE_ID = ? AND A.OWNERSHIP_ID = ? AND A.ACTIVE = 1 AND B.ACTIVE = 1 
						ORDER BY B.REQUIRED_AGREEMENT_NAME';
			$result = $this->db->query($query, array($data['vendor_type'], $data['ownership']))->result_array();

		//var_dump(implode($data['category_id']) ? implode($data['category_id']) : NULL);die();
		
		}else */if($data['vendor_type'] == 2){
			// Non trade
			// OWNERSHIP_ID, VENDOR_TYPE
			// VENDOR TYPE ID IS NULL
			
			$query = 'SELECT DISTINCT A.*, B.*, C.DOCUMENT_TYPE FROM SMNTP_VP_REQUIRED_AGMT_DEFN A
						LEFT JOIN SMNTP_VP_REQUIRED_AGREEMENTS B ON A.AGREEMENT_ID = B.REQUIRED_AGREEMENT_ID 
						LEFT JOIN SMNTP_INCOMPLETE_DOC_REASONS C ON 
						A.AGREEMENT_ID = C.DOCUMENT_ID  
						WHERE A.VENDOR_TYPE_ID IS NULL AND A.OWNERSHIP_ID = ? AND A.ACTIVE = 1 AND B.ACTIVE = 1 
						ORDER BY B.REQUIRED_AGREEMENT_NAME';
			$result  = $this->db->query($query, array($data['ownership']))->result_array();
		
		}else if($data['vendor_type'] == 3 || $data['vendor_type'] == 1){

			// Non Trade Service(NTS)
			// Ownership id, vendor_type, category
			// VENDOR TYPE ID = 3
			
			$has_no_secondary_document = FALSE;
			if( ! empty($data['category_id'])){
				
				$total_cat = 0;
				if(is_array($data['category_id'])){
					$total_cat = count($data['category_id']);
				}else{
					$total_cat = 1;
				}

				$categories_str = (is_array($data['category_id']) ? implode(',', array_filter($data['category_id'])) : $data['category_id']);
				if(isset($data['trade_vendor_type_array'])){
					$trade_vendor_type_str = (is_array($data['trade_vendor_type_array']) ? implode(',', $data['trade_vendor_type_array']) : $data['trade_vendor_type_array']);
				}else{
					$trade_vendor_type_str = (isset($data['trade_vendor_type']) ? $data['trade_vendor_type'] : '0');
				}

				
				$query = 'SELECT DISTINCT CATEGORY_ID FROM SMNTP_VP_REQUIRED_AGMT_DEFN 
						WHERE VENDOR_TYPE_ID = ? AND 
						OWNERSHIP_ID = ? AND 
						TRADE_VENDOR_TYPE_ID IN (' . $trade_vendor_type_str .') AND 
						CATEGORY_ID IN (' . $categories_str .') ';
				$total_no_secondary_document = $this->db->query($query, array($data['vendor_type'], $data['ownership']));

				//return $this->db->last_query();
				if(empty($total_no_secondary_document) || count($total_no_secondary_document) < $total_cat){
					$has_no_secondary_document = TRUE;
				}

			}
			

			$query = 'SELECT DISTINCT A.VENDOR_TYPE_ID,
										A.AGREEMENT_ID, 
										A.ACTIVE, 
										A.OWNERSHIP_ID, 
										B.*, 
										C.DOCUMENT_TYPE 
						FROM SMNTP_VP_REQUIRED_AGMT_DEFN A
						LEFT JOIN SMNTP_VP_REQUIRED_AGREEMENTS B ON A.AGREEMENT_ID = B.REQUIRED_AGREEMENT_ID 
						LEFT JOIN SMNTP_INCOMPLETE_DOC_REASONS C ON 
						A.AGREEMENT_ID = C.DOCUMENT_ID  
						WHERE A.VENDOR_TYPE_ID = ?  AND A.OWNERSHIP_ID = ? AND A.ACTIVE = 1 AND B.ACTIVE = 1';
			if(!empty($data['category_id'])){
				$query .= ' AND ( CATEGORY_ID IN (' . implode(',', array_filter($data['category_id'])) . ') ';
					
				if($vendor_type != 3){	
					if($has_no_secondary_document){
						$query .= ' OR (CATEGORY_ID IS NULL)';
					}else{
						$query .= ' AND A.TRADE_VENDOR_TYPE_ID IN (' . $trade_vendor_type_str . ') ';
					}
				}
				
				$query .= ' AND A.AGREEMENT_ID != -1) ';
			}
						
			//$query .= (!empty($data['category_id']) ? 'AND CATEGORY_ID IN (' . implode(',', $data['category_id']) . ') ' : '');
			//$query .= (($has_no_secondary_document) ? ' OR (CATEGORY_ID IS NULL )' : '');
			//$query .= ' ) AND A.AGREEMENT_ID != -1) '; 
			//$query .= "!";
			if($vendor_type != 3){				
				if($has_no_secondary_document){
					$query .= ' AND A.TRADE_VENDOR_TYPE_ID IN (' . $trade_vendor_type_str . ')';
				}
			}
			
			$query .= ' ORDER BY B.REQUIRED_AGREEMENT_NAME';
			

			$result = $this->db->query($query, array($data['vendor_type'], $data['ownership']))->result_array();
			//=return $this->db->last_query();

		}
		
		// If invite ID is not empty then check the waived ARD Documents
		if( ! empty($data['invite_id']) && ! empty($result)){	
			$res_na_count = array();
			
			foreach($result as  $key => $value){
				$res_na_count[$key] =  $this->db->query('SELECT COUNT(*) AS TOTAL
																				FROM SMNTP_VENDOR_WAIVE_AD_FLAG 
																				WHERE VENDOR_INVITE_ID = ? 
																				AND ADDDOCS_ID = ?', array($data['invite_id'], $value['AGREEMENT_ID']))->result_array()[0]['TOTAL'];
				if($res_na_count[$key] > 0){
					$result[$key]['NA'] = $res_na_count[$key];
				}else{
					$result[$key]['NA'] = NULL;
				}
			}
		}

		
		return $result;

		/*if(isset($data['trade_vendor_type'])){
			if(!isset($data['ownership']) || $data['ownership'] == ""){
				return "-1";
			}
			//if($data['trade_vendor_type'] == 3){
			if($data['trade_vendor_type'] == 3 || $data['trade_vendor_type'] == 4 || empty($data['trade_vendor_type'])){
				$c_id = $data['category_id'];
				$ccount = count($c_id);

			
				$this->db->select('CATEGORY_ID');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN');
				if($data['trade_vendor_type'] == 4){
					$this->db->where(array('VENDOR_TYPE_ID' => 3));
				}else{
					$this->db->where(array('VENDOR_TYPE_ID' => null));
				}
				$this->db->where(array('AGREEMENT_ID' => '-1'));
				$this->db->where(array('OWNERSHIP_ID' =>$data['ownership']));
				if($ccount > 1){
					$this->db->where_in('CATEGORY_ID',$c_id);
				}

				if($ccount == 1){
					if(is_array($c_id)){
						$this->db->where(array('CATEGORY_ID' => $c_id[0]));
					}else{
						$this->db->where(array('CATEGORY_ID' => $c_id));
					}		
				}
				
				
				$tmp_neg_count = $this->db->get()->result_array();
				$neg_count = array();

				foreach ($tmp_neg_count as $key => $value) {
					array_push($neg_count,$value['CATEGORY_ID']);
				}
			
				if(count($c_id) == count($neg_count)){
					return "-1"; // same count category sa category from query na -1
				}

				$c_id = array_diff($c_id, $neg_count); //remove all cat_id -> agreement_id = -1;


				$this->db->select('*');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
				$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
				$this->db->where(array('A.OWNERSHIP_ID' => $data['ownership']));
				$this->db->where(array('A.ACTIVE'=> 1));
				$this->db->where(array('B.ACTIVE'=> 1));
				
				$new_cid_index = array();
				foreach($c_id as $key => $value){
					$new_cid_index[] = $value;
				}
				$c_id = $new_cid_index;
				
				if(count($c_id) == 1){
					if(is_array($c_id)){
						$this->db->where(array('CATEGORY_ID' => $c_id[0]));
					}else{
						$this->db->where(array('CATEGORY_ID' => $c_id));
					}
				}
				if(count($c_id) > 1){
					$this->db->where_in('CATEGORY_ID',$c_id);
					
				}

				


				
				$result = $this->db->get()->result_array();

				//return $this->db->last_query();

				$dsm = array();


				for($i =0 ; $i <count($result);$i++){

					 $dsm[$i] = $result[$i]['CATEGORY_ID'];
				}

				$dsm = array_unique($dsm);
		/*		return $dsm;
*/
		//		return $dsm;

		/*		if(count($dsm) != count($c_id)){


					$resp = $this->select_ra_defn($data['ownership'], $data['invite_id'],  $data['trade_vendor_type']);
				}

				$tempArr = array_unique(array_column($result, 'AGREEMENT_ID'));
				$tempArr = (array_intersect_key($result, $tempArr));

				$new_array = array();

				foreach ($tempArr as $key => $value) {
					array_push($new_array, $value);
				}

			

				if(isset($resp)){


					$rest = array_merge($resp,$new_array);
			

					$tempArr = array_unique(array_column($rest, 'AGREEMENT_ID'));
					$tempArr = (array_intersect_key($rest, $tempArr));

					$new_array = array();

					foreach ($tempArr as $key => $value) {
					array_push($new_array, $value);
					}

					return $new_array;

				}
				
				if(count($result) == 0 ){
					return $this->select_ra_defn($data['ownership'], $data['invite_id'],  $data['trade_vendor_type']);
				}else{

					return $new_array;
				}

			}else{
				//for trade -> where(ownership id & vendortypeid)

				//JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RD.REQUIRED_DOCUMENT_ID AND IDR.DOCUMENT_TYPE = 1
				
				$this->db->select('*');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
				$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
				//$this->db->join('SMNTP_INCOMPLETE_DOC_REASONS C','C.DOCUMENT_ID = B.REQUIRED_AGREEMENT_ID','LEFT');
				//$this->db->where(array('C.DOCUMENT_TYPE' => 1));
				$this->db->where(array('A.OWNERSHIP_ID' => $data['ownership']));
				$this->db->where(array('A.ACTIVE'=> 1));
				$this->db->where(array('B.ACTIVE'=> 1));
				$this->db->where(array('A.CATEGORY_ID'=>null));
				$this->db->where(array('A.VENDOR_TYPE_ID'=>$data['trade_vendor_type']));
				$res = $this->db->get()->result_array();
				//jay
				//return $this->db->last_query();
				if( ! empty($invite_id)){	
					$res_na_count = array();
					
					foreach($res as  $key => $value){
						$res_na_count[$key] =  $this->db->query('SELECT COUNT(*) AS TOTAL
																						FROM SMNTP_VENDOR_WAIVE_AD_FLAG 
																						WHERE VENDOR_INVITE_ID = ? 
																						AND ADDDOCS_ID = ?', array($invite_id, $value['AGREEMENT_ID']))->result_array()[0]['TOTAL'];
						
						
						if($res_na_count[$key] > 0){
							$res[$key]['NA'] = $res_na_count[$key];
						}else{
							$res[$key]['NA'] = null;
						}
					}
				}
				
				return $res;
			}
		}else{
			//jay
			$this->db->select('*');
			$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS SVRA');

			// if (!empty($data['trade_vendor_type'])) // ownership na din sya
			// 	$this->db->join('SMNTP_VP_REQUIRED_AGMT_DEFN SVRAD', 'SVRA.REQUIRED_AGREEMENT_ID = SVRAD.AGREEMENT_ID AND SVRAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type']);

			if (!empty($data['trade_vendor_type'])){
				if($data['trade_vendor_type'] == 3){
					$join_info = ' AND (SVRAD.VENDOR_TYPE_ID IS NULL) ';
				}else{
					$join_info = ' AND (SVRAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].') ';
				}
			}
			else{
				$join_info = '';
			}

			if (!empty($data['ownership']))
				$this->db->join('SMNTP_VP_REQUIRED_AGMT_DEFN SVRAD', 'SVRA.REQUIRED_AGREEMENT_ID = SVRAD.AGREEMENT_ID AND SVRAD.OWNERSHIP_ID = '.$data['ownership'].$join_info);

			$this->db->where('SVRA.ACTIVE',1);

			$result = $this->db->get();

			return $result->result_array();
		}*/

	}
	
	function get_ccn_docs(){
		$result = $this->db->query('SELECT * FROM SMNTP_VP_REQUIRED_DOCS_CCN WHERE ACTIVE = 1')->result_array();
		return $result;
	}

	//Call function

	function select_ra_defn($own, $invite_id, $trade_vendor_type = null)
	{

		//$this->db->select('A.AGREEMENT_ID,B.REQUIRED_AGREEMENT_NAME,A.CATEGORY_ID');
		$this->db->select('*');
		$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
		$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
		//nageerror dito wlang result na lumalbas
		//$this->db->join('SMNTP_INCOMPLETE_DOC_REASONS C','C.DOCUMENT_ID = B.REQUIRED_AGREEMENT_ID','LEFT');
		//$this->db->where(array('C.DOCUMENT_TYPE' => 1));
		$this->db->where(array('A.OWNERSHIP_ID' => $own));
		$this->db->where(array('A.ACTIVE'=> 1));
		$this->db->where(array('B.ACTIVE'=> 1));
		$this->db->where(array('A.CATEGORY_ID'=>null));
		if(!empty($trade_vendor_type) && $trade_vendor_type == 4){
			$this->db->where(array('A.VENDOR_TYPE_ID'=> 3));
		}else{
			$this->db->where(array('A.VENDOR_TYPE_ID'=>null));
		}
		$this->db->where('A.AGREEMENT_ID !=','-1',false);
		$res = $this->db->get()->result_array();
		
		//jay
		if( ! empty($invite_id)){	
			$res_na_count = array();
			
			foreach($res as  $key => $value){
				$res_na_count[$key] =  $this->db->query('SELECT COUNT(*) AS TOTAL
																				FROM SMNTP_VENDOR_WAIVE_AD_FLAG 
																				WHERE VENDOR_INVITE_ID = ? 
																				AND ADDDOCS_ID = ?', array($invite_id, $value['AGREEMENT_ID']))->result_array()[0]['TOTAL'];
				if($res_na_count[$key] > 0){
					$res[$key]['NA'] = $res_na_count[$key];
				}else{
					$res[$key]['NA'] = null;
				}
			}
		}
		
		return $res;

	}

	function arrayUnique($array, $preserveKeys = false)
	{
	    // Unique Array for return
	    $arrayRewrite = array();
	    // Array with the md5 hashes
	    $arrayHashes = array();
	    foreach($array as $key => $item) {
	        // Serialize the current element and create a md5 hash
	        $hash = md5(serialize($item));
	        // If the md5 didn't come up yet, add the element to
	        // to arrayRewrite, otherwise drop it
	        if (!isset($arrayHashes[$hash])) {
	            // Save the current element hash
	            $arrayHashes[$hash] = $hash;
	            // Add element to the unique Array
	            if ($preserveKeys) {
	                $arrayRewrite[$key] = $item;
	            } else {
	                $arrayRewrite[] = $item;
	            }
	        }
	    }
	    return $arrayRewrite;
	}

	function insert_vendor($data) // insert then get last inserted id using $data as where
	{
		$this->db->insert('SMNTP_VENDOR', $data);

		$this->db->select('VENDOR_ID');
		$this->db->from('SMNTP_VENDOR');
		$this->db->where(array(
			'VENDOR_INVITE_ID' => $data['VENDOR_INVITE_ID']
		));

		$query = $this->db->get();

		$vendor_id = $query->row()->VENDOR_ID;

		return $vendor_id;
	}

	function insert_brand($data)
	{
		$this->db->insert('SMNTP_BRAND', $data);

		$this->db->select('BRAND_ID');
		$this->db->from('SMNTP_BRAND');
		$this->db->where(array_filter($data)); // filter remove empty arrays
		$this->db->order_by('BRAND_ID DESC')->limit(2); // 2 because they use < not <=

		$query = $this->db->get();

		$brand_id = $query->row()->BRAND_ID;

		return $brand_id;
	}

	function get_vendor_data($data)
	{
		$this->db->select('A.*, B.*, C.*, D.*, E.*, F.*, G.TERMS_PAYMENT_NAME AVC_TERMSPAYMENT_NAME');
		//$this->db->select('*');
		$this->db->from('SMNTP_VENDOR A');
		$this->db->join('SMNTP_OWNERSHIP B', 'A.OWNERSHIP_TYPE=B.OWNERSHIP_ID', 'left');
		$this->db->join('SMNTP_VENDOR_TYPE C', 'A.VENDOR_TYPE=C.VENDOR_TYPE_ID', 'left');
		$this->db->join('SMNTP_VENDOR_STATUS D', 'A.VENDOR_INVITE_ID=D.VENDOR_INVITE_ID', 'left');
		$this->db->join('SMNTP_TERMS_PAYMENT E', 'D.TERMSPAYMENT = E.TERMS_PAYMENT_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_INVITE F', 'A.VENDOR_INVITE_ID = F.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_TERMS_PAYMENT G', 'D.AVC_TERMSPAYMENT = G.TERMS_PAYMENT_ID', 'LEFT');
		/*$this->db->join('SMNTP_VENDOR_CATEGORIES H', 'A.VENDOR_INVITE_ID=H.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_AVC_CAT I', 'A.VENDOR_INVITE_ID=I.VENDOR_INVITE_ID', 'LEFT');*/
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_category_id($data)
	{
		$this->db->select('B.CATEGORY_ID');
		$this->db->from('SMNTP_VENDOR A');
		$this->db->join('SMNTP_VENDOR_CATEGORIES B', 'A.VENDOR_INVITE_ID=B.VENDOR_INVITE_ID', 'left');

		$this->db->where($data);
		
		$query = $this->db->get();

		return $query;
	}

	function get_vendor_brand($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_BRAND SVB');
		$this->db->join('SMNTP_BRAND SB', 'SB.BRAND_ID = SVB.BRAND_ID');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_addr($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_ADDRESSES SVA');
		$this->db->join('SMNTP_CITY SC', 'SC.CITY_ID = SVA.BRGY_MUNICIPALITY_ID','LEFT');
		$this->db->join('SMNTP_STATE_PROVINCE SSP', 'SSP.STATE_PROV_ID = SVA.STATE_PROVINCE_ID','LEFT');
		$this->db->join('SMNTP_COUNTRY SCB', 'SCB.COUNTRY_ID = SVA.COUNTRY_ID','LEFT');
		$this->db->join('SMNTP_REGIONS SRB', 'SRB.REGION_ID = SVA.REGION_ID','LEFT');
		$this->db->order_by('SVA.VENDOR_ADDRESS_ID desc');
		//$this->db->limit(1);
		$this->db->where($data);

	

/*
		$sql = "SELECT * FROM(SELECT * FROM SMNTP_VENDOR_ADDRESSES SVA 
		LEFT OUTER JOIN SMNTP_CITY SC ON SC.CITY_ID = SVA.BRGY_MUNICIPALITY_ID
		LEFT OUTER JOIN SMNTP_STATE_PROVINCE SSP ON SSP.STATE_PROV_ID = SVA.STATE_PROVINCE_ID
		WHERE VENDOR_ID = ".$data['VENDOR_ID']." AND sva.ADDRESS_TYPE =".$data['ADDRESS_TYPE']." ORDER BY SVA.VENDOR_ADDRESS_ID ASC) WHERE ROWNUM = 1";*/

		$query = $this->db->get();
		return $query;
	}

	function get_vendor_contact($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_CONTACT_DETAILS');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_invite_details($data)
	{
		$this->db->select('SVI.*, SVI_TWO.VENDOR_ID, SVI_TWO.VENDOR_CODE, SVI_FOUR.VENDOR_ID F_VENDOR_ID, SVI_FOUR.VENDOR_CODE F_VENDOR_CODE');
		$this->db->from('SMNTP_VENDOR_INVITE SVI');
		$this->db->join('SMNTP_VENDOR SVI_TWO', 'SVI.CC_VENDOR_CODE = SVI_TWO.VENDOR_INVITE_ID','LEFT');
		$this->db->join('SMNTP_VENDOR_INVITE SVI_THREE', 'SVI.CC_VENDOR_CODE = SVI_THREE.VENDOR_INVITE_ID','LEFT');
		$this->db->join('SMNTP_VENDOR SVI_FOUR', 'SVI_THREE.VENDOR_INVITE_ID = SVI_FOUR.VENDOR_INVITE_ID','LEFT');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_owner($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_OWNERS');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_authrep($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_REP');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_bank($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_BANK');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_retcust($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_OTHER_RETCUST');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_other_business($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_OTHER_BUSINESS');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_relatives($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_RELATIVES');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_req_doc($data)
	{
		$this->db->select('REQUIRED_DOC_ID, VENDOR_ID, DOC_TYPE_ID, FILE_PATH, DATE_CREATED, DATE_REVIEWED, DATE_VERIFIED,
						   CASE WHEN DATE_VERIFIED IS NULL THEN \'0\' ELSE \'1\' END AS VERIFIED,
						   CASE WHEN DATE_REVIEWED IS NULL THEN \'0\' ELSE \'1\' END AS REVIEWED,
						   ORIGINAL_FILENAME  ', false);
		$this->db->from('SMNTP_VENDOR_REQUIRED_DOC');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_agreements($data)
	{
		$this->db->select('VENDOR_AGREEMENT_ID, VENDOR_ID, DOC_TYPE_ID, FILE_PATH, DATE_CREATED, DATE_REVIEWED, DATE_SUBMITTED,
						   CASE WHEN DATE_REVIEWED IS NULL THEN \'false\' ELSE \'true\' END AS REVIEWED,
						   CASE WHEN DATE_SUBMITTED IS NULL THEN \'false\' ELSE \'true\' END AS SUBMITTED,
						   ORIGINAL_FILENAME ', false);
		$this->db->from('SMNTP_VENDOR_AGREEMENTS');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function get_vendor_ccn($data)
	{
		$this->db->select('VENDOR_CCN_ID, VENDOR_ID, DOC_TYPE_ID, FILE_PATH, DATE_CREATED, DATE_REVIEWED, DATE_SUBMITTED,
						   CASE WHEN DATE_REVIEWED IS NULL THEN \'false\' ELSE \'true\' END AS REVIEWED,
						   CASE WHEN DATE_SUBMITTED IS NULL THEN \'false\' ELSE \'true\' END AS SUBMITTED,
						   ORIGINAL_FILENAME ', false);
		$this->db->from('SMNTP_VENDOR_CCN');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function check_vendor_id($data){
		return $this->db->query('SELECT VENDOR_ID,REGISTRATION_TYPE,PREV_REGISTRATION_TYPE FROM SMNTP_VENDOR SV JOIN SMNTP_VENDOR_INVITE SVI ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID WHERE SVI.VENDOR_INVITE_ID = ?', array($data['invite_id']))->result_array();
	}

	function check_vendor_id_v2($data){
		return $this->db->query('SELECT VENDOR_ID,REGISTRATION_TYPE,PREV_REGISTRATION_TYPE FROM SMNTP_VENDOR SV JOIN SMNTP_VENDOR_INVITE SVI ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID WHERE SV.VENDOR_ID = ?', array($data['vendor_id']))->result_array();
	}

	//-------------- topher function
	function get_vendor_id($data)
	{
		$this->db->select('VENDOR_ID');
		$this->db->from('SMNTP_VENDOR');
		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);

		$query = $this->db->get();

		$rs['vendor_id'] = 0;
		$rs['status_id'] = 0;
		$rs['position_id'] = 0;
		$rs['termspayment'] = 0;
		$rs['note'] = '';
		$rs['vrdnote'] = '';

		if($query->num_rows() > 0)
		{
			$rs['vendor_id'] = $query->row()->VENDOR_ID;
				
		}
		$this->db->select('CREATED_BY, REGISTRATION_TYPE');
		$this->db->from('SMNTP_VENDOR_INVITE');
		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);
		$query = $this->db->get();
		$rs['reg_type_id'] = $query->row()->REGISTRATION_TYPE;
		
		if($query->num_rows() > 0){
			$created_by = $query->row()->CREATED_BY;
				
			$this->db->select('POSITION_ID');
			$this->db->from('SMNTP_USERS');
			$this->db->where('USER_ID', $created_by);
			$query = $this->db->get();
			$rs['creator_position_id'] = $query->row()->POSITION_ID;
		}
		$this->db->select('STATUS_ID, POSITION_ID, TERMSPAYMENT, NOTE, VRDNOTE');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);

		$query2 = $this->db->get();

		if($query2->num_rows() > 0)
		{
			$rs['status_id'] = $query2->row()->STATUS_ID;
			$rs['position_id'] = $query2->row()->POSITION_ID;
			$rs['termspayment'] = $query2->row()->TERMSPAYMENT;
			$rs['note'] = $query2->row()->NOTE;
			$rs['vrdnote'] = $query2->row()->VRDNOTE;
		}

		return $rs;
	}

	function update_approval_status($data)
	{
		$is_finished_reviewed_ra = $this->check_reviewed_ra_upload($data['vendor_id']);
		
		$vendor_data = $this->users_model->get_vendor_data($data['vendor_id'])->row_array();
		$where_arr_vendor = array('VENDOR_ID' => $data['vendor_id']);
		$invite_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR', 'VENDOR_INVITE_ID', $where_arr_vendor);
		
		$docs_var['ownership'] 			= $vendor_data['OWNERSHIP_TYPE'];
		$docs_var['trade_vendor_type'] 	= $vendor_data['TRADE_VENDOR_TYPE'];
		$docs_var['vendor_type'] 		= $vendor_data['VENDOR_TYPE'];
		$docs_var['category_id'] 		= '';
		$docs_var['invite_id'] 			= $invite_id;

		$vendor_categories = $this->users_model->get_vendor_assigned_categories($invite_id)->result_array();
				
		$str_categories = '';
		$cat_array = '';
		foreach($vendor_categories as $category){
			$cat_array .=  $category['CATEGORY_ID'] . ',';
		}
		
		$docs_var['category_id']		= explode(',', rtrim($cat_array, ','));
		
		$is_finished_reviewed_waived = $this->check_ra_waive($docs_var);
		$ard_available = $this->registration_model->get_ra_docs($docs_var);  //FALSE = NO AVAILABLE ARD
		$no_of_ra = count($ard_available);
		
		$total_files = $is_finished_reviewed_waived[1] + $is_finished_reviewed_ra[1];
		
		if(($data['reg_type_id'] == 2) || ($data['reg_type_id'] == 3)){
			if($data['action'] == 1){
				if ($no_of_ra == $total_files){
					$data['status'] = 198;//no category
					$data['nxt_position_id'] = 4;
				}else if($this->check_additional_requirements_upload($data['vendor_id']) == true){
					$data['status'] = 194;//no category
					$data['nxt_position_id'] = 4;
				}else{
					$data['status'] = 190;//no category
					$data['nxt_position_id'] = 4;
				}
			}
		}
		
		$update_array = array(
								'STATUS_ID'		=> $data['status'],
								'POSITION_ID'	=> $data['nxt_position_id'],
								'APPROVER_ID'	=> $data['user_id'],
								'APPROVER_REMARKS'=> $data['reject_remarks'],
								'NOTE'	=> $data['note_hts'],
								'VRDNOTE'	=> $data['note_vrd']
								);	

		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);

		$this->db->update('SMNTP_VENDOR_STATUS', $update_array);

			//LOGS FOR APPROVAL HISTORY
		$record_stat = array(
				'VENDOR_INVITE_ID'			=> $data['invite_id']
			);

		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record_stat));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <=

		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;
		$etimestamp = date('d-M-Y');

		//$date_timestamp = date('m/d/Y h:i:s A');
		//$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
		//$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");

		$record3 = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $data['invite_id'],
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['nxt_position_id'],
				'APPROVER_ID'		=> $data['user_id'],
				'APPROVER_REMARKS'	=> $data['reject_remarks'],
				'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
				'ACTIVE'			=> 1,
			);

		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $record3);

		//END




		//echo $this->db->last_query();
	}
	
	//For vendor registration review( VRDSTAFF logs)
	public function update_review_status($data){
		$record_stat = array(
			'VENDOR_INVITE_ID'			=> $data['invite_id']
		);

		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record_stat));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <=

		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;

		$date_timestamp = date('Y-m-d H:i:s');

		$record3 = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $data['invite_id'],
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['nxt_position_id'],
				'APPROVER_ID'		=> $data['user_id'],
				'APPROVER_REMARKS'	=> $data['reject_remarks'],
				'DATE_UPDATED'		=> $date_timestamp,
				'ACTIVE'			=> 1,
			);

		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $record3);
	}

	function delete_table($table_name, $where_arr)
	{
		$this->db->delete($table_name, $where_arr);

		return $this->db->affected_rows();
	}

	function backup_table($vendor_code)
	{
		//Main Tables for vendor
		$backup_smntp_vendor_invite = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REGISTRATION_TYPE_ID, PREV_REGISTRATION_TYPE_ID, CC_VENDOR_CODE, BACKUP_DATE) SELECT SVI.*, CURRENT_DATE FROM SMNTP_VENDOR_INVITE SVI JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID WHERE SV.VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor = "INSERT INTO SMNTP_VENDOR_SYS_LOGS(VENDOR_ID,VENDOR_INVITE_ID,VENDOR_NAME,YEAR_IN_BUSINESS,OWNERSHIP_TYPE,VENDOR_TYPE,TAX_ID_NO,TAX_CLASSIFICATION,NATURE_OF_BUSINESS,OTHER_BUSINESS,DATE_CREATED,VENDOR_CODE,FLOATING,NOB_DISTRIBUTOR,NOB_MANUFACTURER,NOB_IMPORTER,NOB_WHOLESALER,NOB_OTHERS,NOB_OTHERS_TEXT,TRADE_VENDOR_TYPE,EMPLOYEE,BUSINESS_ASSET,VENDOR_CODE_02,REMOVE_DATE,BACKUP_DATE) SELECT SV.*,NULL ,CURRENT_DATE FROM SMNTP_VENDOR SV WHERE SV.VENDOR_ID = ".$vendor_code;
		$backup_smntp_user = "INSERT INTO SMNTP_USERS_SYS_LOGS(USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,POSITION_ID,USER_STATUS,USER_DATE_CREATED,USER_MOBILE,USER_EMAIL,VENDOR_ID,HEAD_ID,BACKUP_DATE) SELECT SUs.*, CURRENT_DATE FROM SMNTP_VENDOR SV JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID= SVI.VENDOR_INVITE_ID JOIN SMNTP_USERS SUs ON SVI.USER_ID = SUs.USER_ID WHERE SV.VENDOR_ID = ".$vendor_code;
		$backup_smntp_credential = "INSERT INTO SMNTP_CREDENTIALS_SYS_LOGS(CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,TIME_STAMP,DEACTIVATED_FLAG,BACKUP_DATE) SELECT SCr.*, CURRENT_DATE FROM SMNTP_VENDOR SV JOIN SMNTP_VENDOR_INVITE SVI ON SV.VENDOR_INVITE_ID= SVI.VENDOR_INVITE_ID JOIN SMNTP_USERS SUs ON SVI.USER_ID = SUs.USER_ID JOIN SMNTP_CREDENTIALS SCr ON SUs.USER_ID = SCr.USER_ID WHERE SV.VENDOR_ID = ".$vendor_code;
		
		//Sub Tables
		$backup_smntp_vendor_brand = "INSERT INTO SMNTP_VENDOR_BRAND_SYS_LOGS(VENDOR_BRAND_ID,VENDOR_ID,BRAND_ID,DATE_CREATED,BACKUP_DATE) SELECT SVB.*, CURRENT_DATE FROM SMNTP_VENDOR_BRAND SVB WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_addresses = "INSERT INTO SMNTP_VENDOR_ADD_SYS_LOGS(VENDOR_ADDRESS_ID,VENDOR_ID,ADDRESS_TYPE,ADDRESS_LINE,BRGY_MUNICIPALITY_ID,STATE_PROVINCE_ID,ZIP_CODE,COUNTRY_ID,`PRIMARY`,DATE_CREATED,ACTIVE,REGION_ID,BACKUP_DATE) SELECT SVA.*, CURRENT_DATE FROM SMNTP_VENDOR_ADDRESSES SVA WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_contact_details = "INSERT INTO SMNTP_VENDOR_CONT_DET_SYS_LOGS(VENDOR_CONTACT_DETAIL_ID,VENDOR_ID,CONTACT_DETAIL_TYPE,CONTACT_DETAIL,DATE_CREATED,ACTIVE,COUNTRY_CODE,AREA_CODE,EXTENSION_LOCAL_NUMBER,BACKUP_DATE) SELECT SVCD.*, CURRENT_DATE FROM SMNTP_VENDOR_CONTACT_DETAILS SVCD WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_owners = "INSERT INTO SMNTP_VENDOR_OWNERS_SYS_LOGS(VENDOR_OWNER_ID,VENDOR_ID,NAME,POSITION,DATE_CREATED,ACTIVE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,AUTH_SIG,BACKUP_DATE) SELECT SVCD.*, CURRENT_DATE FROM SMNTP_VENDOR_OWNERS SVCD WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_rep = "INSERT INTO SMNTP_VENDOR_REP_SYS_LOGS(VENDOR_REP_ID,VENDOR_ID,NAME,POSITION,DATE_CREATED,ACTIVE,FIRST_NAME,LAST_NAME,MIDDLE_NAME,AUTH_SIG,BACKUP_DATE) SELECT SVRSL.*, CURRENT_DATE FROM SMNTP_VENDOR_REP SVRSL WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_bank = "INSERT INTO SMNTP_VENDOR_BANK_SYS_LOGS(VENDOR_BANK_ID,VENDOR_ID,BANK_NAME,ACCOUNT_NO,DATE_CREATED,ACTIVE,FLOATING,BANK_BRANCH,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_BANK S WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_other_retcust = "INSERT INTO SMNTP_VENDOR_OT_RET_SYS_LOGS(VENDOR_OTHER_RETCUST_ID,VENDOR_ID,COMPANY_NAME,DATE_CREATED,ACTIVE,BACKUP_DATE) SELECT SVCD.*, CURRENT_DATE FROM SMNTP_VENDOR_OTHER_RETCUST SVCD WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_other_business = "INSERT INTO SMNTP_VENDOR_OT_BUS_SYS_LOGS(OTHER_BUSINESS_ID,VENDOR_ID,COMPANY_NAME,SERVICE_OFFERED,DATE_CREATED,ACTIVE,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_OTHER_BUSINESS S WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_relatives = "INSERT INTO SMNTP_VENDOR_REL_SYS_LOGS(RELATIVE_ID,VENDOR_ID,FIRST_NAME,LAST_NAME,POSITION,COMPANY,RELATIONSHIP,DATE_CREATED,ACTIVE,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_RELATIVES S WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_required_doc = "INSERT INTO SMNTP_VENDOR_REQ_DOC_SYS_LOGS(REQUIRED_DOC_ID,VENDOR_ID,DOC_TYPE_ID,FILE_PATH,DATE_CREATED,ACTIVE,DATE_REVIEWED,DATE_VERIFIED,ORIGINAL_FILENAME,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_REQUIRED_DOC S WHERE VENDOR_ID = ".$vendor_code;
		$backup_smntp_vendor_agreements = "INSERT INTO SMNTP_VENDOR_AGRE_SYS_LOGS(VENDOR_AGREEMENT_ID,VENDOR_ID,DOC_TYPE_ID,FILE_PATH,DATE_CREATED,ACTIVE,DATE_REVIEWED,DATE_SUBMITTED,ORIGINAL_FILENAME,DOC_STATUS,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_AGREEMENTS S WHERE VENDOR_ID = ".$vendor_code;
		
		$backup_smntp_vendor_approved_items = "INSERT INTO SMNTP_VENDOR_AP_IT_SYS_LOGS(VENDOR_APPROVE_ITEMS_ID,VENDOR_INVITE_ID,ORIGINAL_FILE_NAME,ACTIVE,FILE_PATH,DATE_CREATED,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_APPROVED_ITEMS S JOIN SMNTP_VENDOR SV ON S.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID WHERE SV.VENDOR_ID = ".$vendor_code;
		
		//Execute Backup
		$result_smntp_vendor_invite = $this->db->query($backup_smntp_vendor_invite);
		$result_smntp_vendor = $this->db->query($backup_smntp_vendor);
		$result_smntp_user = $this->db->query($backup_smntp_user);
		$result_smntp_credential = $this->db->query($backup_smntp_credential);
		
		$result_smntp_vendor_brand = $this->db->query($backup_smntp_vendor_brand);
		$result_smntp_vendor_addresses = $this->db->query($backup_smntp_vendor_addresses);
		$result_smntp_vendor_contact_details = $this->db->query($backup_smntp_vendor_contact_details);
		$result_smntp_vendor_owners = $this->db->query($backup_smntp_vendor_owners);
		$result_smntp_vendor_rep = $this->db->query($backup_smntp_vendor_rep);
		$result_smntp_vendor_bank = $this->db->query($backup_smntp_vendor_bank);
		$result_smntp_vendor_other_retcust = $this->db->query($backup_smntp_vendor_other_retcust);
		$result_smntp_vendor_other_business = $this->db->query($backup_smntp_vendor_other_business);
		$result_smntp_vendor_relatives = $this->db->query($backup_smntp_vendor_relatives);
		$result_smntp_vendor_required_doc = $this->db->query($backup_smntp_vendor_required_doc);
		$result_smntp_vendor_agreements = $this->db->query($backup_smntp_vendor_agreements);
		
		$result_smntp_vendor_approved_items = $this->db->query($backup_smntp_vendor_approved_items);

		return 1;
	}

	function backup_invite_only($invite_id)
	{
		//Main Tables for vendor
		$backup_smntp_vendor_invite = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REGISTRATION_TYPE_ID, PREV_REGISTRATION_TYPE_ID, CC_VENDOR_CODE, BACKUP_DATE) SELECT SVI.*, CURRENT_DATE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ".$invite_id;
		$backup_smntp_vendor_approved_items = "INSERT INTO SMNTP_VENDOR_AP_IT_SYS_LOGS(VENDOR_APPROVE_ITEMS_ID,VENDOR_INVITE_ID,ORIGINAL_FILE_NAME,ACTIVE,FILE_PATH,DATE_CREATED,BACKUP_DATE) SELECT S.*, CURRENT_DATE FROM SMNTP_VENDOR_APPROVED_ITEMS WHERE VENDOR_INVITE_ID = ".$invite_id;
		return 1;
	}

	function get_splash()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_LOGIN_SPLASHSCREEN_TMPLT');
		$this->db->where('SELECTED', 1);

		$result = $this->db->get();

		return $result;
	}

	function get_doc_notification()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_SUBMITTED_DOCS_TMPLT');
		$this->db->where('SELECTED', 1);

		$result = $this->db->get();

		return $result;
	}

	function get_terms_of_payment()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_TERMS_PAYMENT');
		$this->db->where('ACTIVE', 1);
		$result = $this->db->get();

		//-->marc
		$res = $this->db->select('CONFIG_VALUE')->from('SMNTP_SYSTEM_CONFIG')->where(array('CONFIG_NAME' => 'default_terms_of_payment'))->get()->result_array();
		//get defauld terms of payment ->
		//<-- end
		$terms_payment = array();
		if ($result->num_rows() > 0)
		{
			foreach($result->result() as $row)
			{
				$terms_payment[$row->TERMS_PAYMENT_ID] = $row->TERMS_PAYMENT_NAME;
			}
		}
		//m
		//$terms_payment = array_merge($terms_payment,$res);
		//em
		//$terms_payment['86'] = $res[0]['CONFIG_VALUE'];

		return $terms_payment;
	}

	function approval_email_data($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_INVITE');
		$this->db->where('VENDOR_INVITE_ID', $data['invite_id'])->limit(2);

		$query = $this->db->get();

		$rs['to'] = $query->row()->EMAIL;
		$rs['vendor_name'] = $query->row(0)->VENDOR_NAME;

		$this->db->select('*');
		$this->db->from('SMNTP_EMAIL_DEFAULT_TEMPLATE');;
		$this->db->where('TEMPLATE_TYPE', 3)->limit(2); // limit type 2 for oracle limit 1  == limit < 1

		$query2 = $this->db->get();

		//for the mean time
		$this->db->select('*');
		$this->db->from('SMNTP_USERS');
		$this->db->where('USER_ID', $query->row(0)->CREATED_BY);

		$query3 = $this->db->get();

		$rs['creator_position'] = $query3->row(0)->POSITION_ID;
		$rs['creator_name'] = $query3->row(0)->USER_LAST_NAME.', '.$query3->row(0)->USER_FIRST_NAME;
		$rs['creator_id'] = $query3->row(0)->USER_ID;

		$cc_array = array();
		array_push($cc_array ,$query3->row()->USER_EMAIL);

		$this->db->select('*');
		$this->db->from('SMNTP_USERS_MATRIX');
		$this->db->where('USER_ID', $query3->row()->USER_ID);

		$query4 = $this->db->get();

		$rs['query_result'] = $query4;

		$rs['cc'] = array();//Jay wag daw i CC yung creator//$cc_array;
		$rs['content'] = $query2->row()->CONTENT;

		//change data in email template
		$rs['content'] = str_replace('[rep_name]', $rs['vendor_name'], $rs['content']); // (what tofind, value change, whole sentence)
		$rs['content'] = str_replace('[remarks]', $data['reject_remarks'], $rs['content']); // (what tofind, value change, whole sentence)
		$rs['content'] = str_replace('[userposition]', $data['positionname'], $rs['content']); // (what tofind, value change, whole sentence)
		$rs['content'] = nl2br($rs['content']);

		$rs['subject'] = 'Vendor Registration Approval - Rejected';

		return $rs;
	}

	function get_filter_city()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_CITY');
		$this->db->where('STATUS', 1); // its like active
		$this->db->order_by('CITY_NAME');

		$result = $this->db->get();

		return $result->result_array();
	}
	function get_filter_state()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_STATE_PROVINCE');
		$this->db->where('STATUS', 1); // its like active
		$this->db->order_by('STATE_PROV_NAME');

		$result = $this->db->get();

		return $result->result_array();
	}
	function get_filter_country()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_COUNTRY');
		$this->db->where('STATUS', 1); // its like active
		$this->db->order_by('COUNTRY_NAME');

		$result = $this->db->get();

		return $result->result_array();
	}
	function get_filter_region()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_REGIONS');
		$this->db->where('STATUS', 1); // its like active
		$this->db->order_by('REGION_DESC_TWO');

		$result = $this->db->get();

		return $result->result_array();
	}

	function get_category_exception()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_ID_CATEGORY_EXCEPTION');

		$result = $this->db->get();

		return $result;
	}

	function insert_city($data)
	{
		$this->db->insert('SMNTP_CITY', $data);

		$this->db->select('CITY_ID');
		$this->db->from('SMNTP_CITY');
		$this->db->where(array_filter($data)); // filter remove empty arrays
		$this->db->order_by('CITY_ID DESC')->limit(2); // 2 because oracle use < not <=

		$query = $this->db->get();

		$city_id = $query->row()->CITY_ID;

		return $city_id;
	}

	function insert_region($data)
	{
		$this->db->insert('SMNTP_REGIONS', $data);

		$this->db->select('REGION_ID');
		$this->db->from('SMNTP_REGIONS');
		$this->db->where(array_filter($data)); // filter remove empty arrays
		$this->db->order_by('REGION_ID DESC')->limit(2); // 2 because oracle use < not <=

		$query = $this->db->get();

		$region_id = $query->row()->REGION_ID;

		return $region_id;
	}

	function insert_state($data)
	{
		$this->db->insert('SMNTP_STATE_PROVINCE', $data);

		$this->db->select('STATE_PROV_ID');
		$this->db->from('SMNTP_STATE_PROVINCE');
		$this->db->where(array_filter($data)); // filter remove empty arrays
		$this->db->order_by('STATE_PROV_ID DESC')->limit(2); // 2 because oracle use < not <=

		$query = $this->db->get();

		$state_id = $query->row()->STATE_PROV_ID;

		return $state_id;
	}

	function insert_country($data)
	{
		$this->db->insert('SMNTP_COUNTRY', $data);

		$this->db->select('COUNTRY_ID');
		$this->db->from('SMNTP_COUNTRY');
		$this->db->where(array_filter($data)); // filter remove empty arrays
		$this->db->order_by('COUNTRY_ID DESC')->limit(2); // 2 because oracle use < not <=

		$query = $this->db->get();

		$country_id = $query->row()->COUNTRY_ID;

		return $country_id;
	}

	function get_document_agreement($data)
	{
		//DA stands for Document Agreement :)
		$query = $this->db->query('
							SELECT * FROM
							(
								SELECT
								RD.REQUIRED_DOCUMENT_ID AS DA_ID,
								RD.REQUIRED_DOCUMENT_NAME AS DA_NAME,
								1 AS DOCUMENT_TYPE
								FROM SMNTP_VP_REQUIRED_DOCUMENTS RD
								JOIN SMNTP_VP_REQUIRED_DOCS_DEFN RDD ON RD.REQUIRED_DOCUMENT_ID = RDD.DOC_ID AND RDD.OWNERSHIP_ID = '.$data['ownership'].'
								JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RD.REQUIRED_DOCUMENT_ID AND IDR.DOCUMENT_TYPE = 1
								WHERE RD.ACTIVE = 1
								GROUP BY RD.REQUIRED_DOCUMENT_ID, RD.REQUIRED_DOCUMENT_NAME

								UNION ALL

								SELECT
								RA.REQUIRED_AGREEMENT_ID AS DA_ID,
								RA.REQUIRED_AGREEMENT_NAME AS DA_NAME,
								2 AS DOCUMENT_TYPE
								FROM SMNTP_VP_REQUIRED_AGREEMENTS RA
								JOIN SMNTP_VP_REQUIRED_AGMT_DEFN RAD ON RA.REQUIRED_AGREEMENT_ID = RAD.AGREEMENT_ID AND RAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].'
								JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RA.REQUIRED_AGREEMENT_ID AND IDR.DOCUMENT_TYPE = 2
								WHERE RA.ACTIVE = 1
								GROUP BY RA.REQUIRED_AGREEMENT_ID, RA.REQUIRED_AGREEMENT_NAME
							)x
							ORDER BY DA_NAME
					');

		return $query->result_array();
	}

	function get_documents($data)
	{
		//DA stands for Document Agreement :)
		if($data['current_status_id'] == 10)
		{
			if (!empty($data['trade_vendor_type']))
			{
				if ($data['trade_vendor_type'] == 3)
					$join_tvt = ' AND RDD.VENDOR_TYPE_ID IS NULL ';
				else
					$join_tvt = ' AND RDD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'];

			}
			else
				$join_tvt = '';

			$query = $this->db->query('
								SELECT * FROM
								(
									SELECT
									RD.REQUIRED_DOCUMENT_ID AS DA_ID,
									RD.REQUIRED_DOCUMENT_NAME AS DA_NAME,
									1 AS DOCUMENT_TYPE
									FROM SMNTP_VP_REQUIRED_DOCUMENTS RD
									JOIN SMNTP_VP_REQUIRED_DOCS_DEFN RDD ON RD.REQUIRED_DOCUMENT_ID = RDD.DOC_ID AND RDD.OWNERSHIP_ID = '.$data['ownership'].$join_tvt.'
									JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RD.REQUIRED_DOCUMENT_ID AND IDR.DOCUMENT_TYPE = 1
									WHERE RD.ACTIVE = 1
									GROUP BY RD.REQUIRED_DOCUMENT_ID, RD.REQUIRED_DOCUMENT_NAME
								)x
								ORDER BY DA_NAME
						');
		}
		else
		{
			// $query = $this->db->query('
			// 				SELECT * FROM
			// 				(
			// 					SELECT
			// 					RA.REQUIRED_AGREEMENT_ID AS DA_ID,
			// 					RA.REQUIRED_AGREEMENT_NAME AS DA_NAME,
			// 					2 AS DOCUMENT_TYPE
			// 					FROM SMNTP_VP_REQUIRED_AGREEMENTS RA
			// 					JOIN SMNTP_VP_REQUIRED_AGMT_DEFN RAD ON RA.REQUIRED_AGREEMENT_ID = RAD.AGREEMENT_ID AND RAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].'
			// 					JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RA.REQUIRED_AGREEMENT_ID AND IDR.DOCUMENT_TYPE = 2
			// 					WHERE RA.ACTIVE = 1
			// 					GROUP BY RA.REQUIRED_AGREEMENT_ID, RA.REQUIRED_AGREEMENT_NAME
			// 				)x
			// 				ORDER BY DA_NAME
			// 		');

			if (!empty($data['trade_vendor_type']))
			{
				if ($data['trade_vendor_type'] == 3)
					$join_tvt = ' AND RAD.VENDOR_TYPE_ID IS NULL ';
				else
					$join_tvt = ' AND RAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'];

			}
			else
				$join_tvt = '';

			$query = $this->db->query('
							SELECT * FROM
							(
								SELECT
								RA.REQUIRED_AGREEMENT_ID AS DA_ID,
								RA.REQUIRED_AGREEMENT_NAME AS DA_NAME,
								2 AS DOCUMENT_TYPE
								FROM SMNTP_VP_REQUIRED_AGREEMENTS RA
								JOIN SMNTP_VP_REQUIRED_AGMT_DEFN RAD ON RA.REQUIRED_AGREEMENT_ID = RAD.AGREEMENT_ID AND RAD.OWNERSHIP_ID = '.$data['ownership'].$join_tvt.'
								JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RA.REQUIRED_AGREEMENT_ID AND IDR.DOCUMENT_TYPE = 2
								WHERE RA.ACTIVE = 1
								GROUP BY RA.REQUIRED_AGREEMENT_ID, RA.REQUIRED_AGREEMENT_NAME
							)x
							ORDER BY DA_NAME
					');
		}

		return $query->result_array();
	}

	function get_incomplete_reason($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_INCOMPLETE_DOC_REASONS');
		$this->db->where($data);
		$this->db->where(array('ACTIVE' => '1'));

		//return  $data;

		$query = $this->db->get();

	//	return $this->db->last_query();

/*		if ($query->num_rows() == 0)
		{
			$this->db->select('*');
			$this->db->from('SMNTP_INCOMPLETE_DOC_REASONS');

			$query = $this->db->get();
		}
*/
		return $query;
	}

	function get_ir($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_INCOMPLETE_REASON');
		$this->db->where($data);

		$query = $this->db->get();

		return $query;
	}

	function check_additional_requirements_upload($vendor_id)
	{
		$req_upload_count 	= 0;
		$uploaded_count 	= 0;
		$upload_complete 	= false;
		
		//NEW SOLUTION - JAY
		//GET VENDOR ID and OWNERSHIP TYPE
		$result_vendor = $this->db->query('SELECT VENDOR_TYPE, VENDOR_INVITE_ID, OWNERSHIP_TYPE, TRADE_VENDOR_TYPE FROM SMNTP_VENDOR WHERE VENDOR_ID = ?', array($vendor_id))->row();
		
		if( ! empty($result_vendor)){
			
			$vendor_invite_id = $result_vendor->VENDOR_INVITE_ID;
			$ownership_id = $result_vendor->OWNERSHIP_TYPE;
			$vendor_type = $result_vendor->VENDOR_TYPE;
			$trade_vendor_type = $result_vendor->TRADE_VENDOR_TYPE;
			

			//CATEGORY ID's
			$categories = $this->db->query('SELECT CATEGORY_ID FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ?', array($vendor_invite_id))->result_array();
			
			$cat_array = array();
			
			if($vendor_type == 1 || $vendor_type == 3){
				foreach($categories as $category){
					if( ! empty($category['CATEGORY_ID']) && ! in_array($category['CATEGORY_ID'],$cat_array)){
						$cat_array[] = $category['CATEGORY_ID'];
					}
				}
				
				$ra_docs = $this->get_ra_docs(array(
					'vendor_type' => $vendor_type,
					'trade_vendor_type' => $trade_vendor_type,
					'ownership' => $ownership_id,
					'category_id' => $cat_array,
					'invite_id' => $vendor_invite_id
				));
			}else{
				
				$ra_docs = $this->get_ra_docs(array(
					'vendor_type' => $vendor_type,
					'trade_vendor_type' => $trade_vendor_type,
					'ownership' => $ownership_id,
					'invite_id' => $vendor_invite_id
				));
			}

			if(is_array($ra_docs)){
				$req_upload_count = count($ra_docs);
			}else if($ra_docs <= -1){
				$req_upload_count = 0;
			}

			$uploaded_count = $this->db->query('SELECT COUNT(*) AS TOTAL
												FROM SMNTP_VENDOR_AGREEMENTS 
												WHERE DOC_STATUS = 1 AND VENDOR_ID = ?', array($vendor_id))->row()->TOTAL;
			
			if ($req_upload_count == $uploaded_count){
				$upload_complete = true;
			}
		}
		
        return $upload_complete;
	}

	function check_reviewed_ra_upload($vendor_id)
	{
		$req_upload_count 	= 0;
		$uploaded_count 	= 0;
		$upload_complete 	= false;
		
		//NEW SOLUTION - JAY
		//GET VENDOR ID and OWNERSHIP TYPE
		$result_vendor = $this->db->query('SELECT VENDOR_TYPE, VENDOR_INVITE_ID, OWNERSHIP_TYPE, TRADE_VENDOR_TYPE FROM SMNTP_VENDOR WHERE VENDOR_ID = ?', array($vendor_id))->row();
		
		if( ! empty($result_vendor)){
			
			$vendor_invite_id = $result_vendor->VENDOR_INVITE_ID;
			$ownership_id = $result_vendor->OWNERSHIP_TYPE;
			$vendor_type = $result_vendor->VENDOR_TYPE;
			$trade_vendor_type = $result_vendor->TRADE_VENDOR_TYPE;
			

			//CATEGORY ID's
			$categories = $this->db->query('SELECT CATEGORY_ID FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ?', array($vendor_invite_id))->result_array();
			
			if($vendor_type == 3){
				$cat_array = array();
				foreach($categories as $category){
					if( ! empty($category['CATEGORY_ID']) && ! in_array($category['CATEGORY_ID'],$cat_array)){
						$cat_array[] = $category['CATEGORY_ID'];
					}
				}
				$ra_docs = $this->get_ra_docs(array(
					'vendor_type' => $vendor_type,
					'trade_vendor_type' => $trade_vendor_type,
					'ownership' => $ownership_id,
					'category_id' => $cat_array,
					'invite_id' => $vendor_invite_id
				));
			}else{
				$ra_docs = $this->get_ra_docs(array(
					'vendor_type' => $vendor_type,
					'trade_vendor_type' => $trade_vendor_type,
					'ownership' => $ownership_id,
					'invite_id' => $vendor_invite_id
				));
			}
			
			
			//return count($ra_docs);
			if(is_array($ra_docs)){
				$req_upload_count = count($ra_docs);
			}else if($ra_docs <= -1){
				$req_upload_count = 0;
			}

			$uploaded_count = $this->db->query('SELECT COUNT(*) AS TOTAL
												FROM SMNTP_VENDOR_AGREEMENTS 
												WHERE DOC_STATUS = 1 AND DATE_REVIEWED IS NOT NULL AND VENDOR_ID = ?', array($vendor_id))->row()->TOTAL;
			
			if ($req_upload_count == $uploaded_count){
				$upload_complete[0] = true;
				$upload_complete[1] = $uploaded_count;
			}else{
				$upload_complete[0] = false;
				$upload_complete[1] = $uploaded_count;
			}
		}
		
        return $upload_complete;
	}
	
	//jay
	 function check_ra_waive($data){
		
		$total_additional_documents = 0;
		if(is_array($data)){	
			$ra_docs = $this->get_ra_docs($data);
				
				//return count($ra_docs);
			if(is_array($ra_docs)){
				$total_additional_documents = count($ra_docs);
			}else if($ra_docs <= -1){
				$total_additional_documents = 0;
			}
		}else{
			$total_additional_documents = $data;
		}
		
		$total_waived_additional_documents = $this->db->query('SELECT COUNT(*) AS TOTAL FROM SMNTP_VENDOR_WAIVE_AD_FLAG 
																	WHERE VENDOR_INVITE_ID = ?', array($data['invite_id']))->row_array()['TOTAL'];
																
		if($total_additional_documents == $total_waived_additional_documents){
			$waive[0] = true;
			$waive[1] = $total_waived_additional_documents;
		}else{
			$waive[0] = false;
			$waive[1] = $total_waived_additional_documents;
		}
		
		return $waive;
	}

	function get_usermatrix_data($user_id)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_USERS_MATRIX');
		$this->db->where('USER_ID', $user_id);

		$query = $this->db->get();

		$rs['result'] = $query->result_array();
		$rs['query'] = $query;
		$rs['total_rows'] = $query->num_rows();

		return $rs;
	}
	
	function save_remarks($data){
		//1 = hat
		//2 = vrd
		$query = 'UPDATE SMNTP_VENDOR_STATUS SET ' . (($data['remark_type'] == 1) ? 'NOTE' : 'VRDNOTE') . ' = ? WHERE VENDOR_INVITE_ID = ?';
		return $this->db->query($query, array( $data['note'], $data['vid']));
	}
	
	function delete_vendor($data){
		

		//Insert To User_Logs
		$insert_user_logs = "INSERT INTO SMNTP_USERS_SYS_LOGS(USER_ID,USER_FIRST_NAME, USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,POSITION_ID,USER_STATUS, USER_DATE_CREATED, USER_MOBILE, USER_EMAIL,VENDOR_ID, HEAD_ID, REMOVE_DATE)
							 SELECT SU.*, CURRENT_DATE FROM SMNTP_VENDOR_INVITE SVI JOIN SMNTP_USERS SU ON SVI.USER_ID = SU.USER_ID WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'];
		$result_user_logs = $this->db->query($insert_user_logs);
		
		//Insert To Vendor Invite Logs
		//$insert_vendor_invite_logs = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REGISTRATION_TYPE_ID, PREV_REGISTRATION_TYPE_ID, CC_VENDOR_CODE, REMOVE_DATE, REASON_FOR_DELETION, STATUS_ID) SELECT SVI.*, CURRENT_DATE,'".$data['delReason']."', '".$data['sid']."' FROM SMNTP_VENDOR_INVITE SVI WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'];
		$insert_vendor_invite_logs = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REGISTRATION_TYPE_ID, PREV_REGISTRATION_TYPE_ID, CC_VENDOR_CODE, REMOVE_DATE, REASON_FOR_DELETION)
									  SELECT SVI.*, CURRENT_DATE,'".$data['delReason']."' FROM SMNTP_VENDOR_INVITE SVI WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'];
		$result_vendor_invite_logs = $this->db->query($insert_vendor_invite_logs);
		
		//Insert To Vendor Logs
		$insert_vendor_logs = "INSERT INTO SMNTP_VENDOR_SYS_LOGS(VENDOR_ID,VENDOR_INVITE_ID,VENDOR_NAME,YEAR_IN_BUSINESS,OWNERSHIP_TYPE,VENDOR_TYPE,TAX_ID_NO,TAX_CLASSIFICATION,NATURE_OF_BUSINESS,OTHER_BUSINESS,DATE_CREATED,VENDOR_CODE,FLOATING,NOB_DISTRIBUTOR,NOB_MANUFACTURER,NOB_IMPORTER,NOB_WHOLESALER,NOB_OTHERS,NOB_OTHERS_TEXT,TRADE_VENDOR_TYPE,EMPLOYEE,BUSINESS_ASSET,VENDOR_CODE_02,REMOVE_DATE)
							   SELECT SV.*, CURRENT_DATE FROM SMNTP_VENDOR SV WHERE SV.VENDOR_INVITE_ID = ".$data['vid'];
		$result_vendor_logs = $this->db->query($insert_vendor_logs);
		
		//Insert To CREDENTIALS Logs
		$insert_credential_logs = "INSERT INTO SMNTP_CREDENTIALS_SYS_LOGS(CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,TIME_STAMP,DEACTIVATED_FLAG,REMOVE_DATE)
								  SELECT SC.*, CURRENT_DATE FROM SMNTP_VENDOR_INVITE SVI JOIN SMNTP_USERS SU ON SVI.USER_ID = SU.USER_ID JOIN SMNTP_CREDENTIALS SC ON SC.USER_ID = SU.USER_ID WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'];
		$result_credential_logs = $this->db->query($insert_credential_logs);
		
		if($result_user_logs == 1 && $result_vendor_invite_logs == 1){
			
			$delete_credentials = "DELETE FROM SMNTP_CREDENTIALS WHERE USER_ID IN (SELECT SU.USER_ID FROM SMNTP_VENDOR_INVITE SVI JOIN SMNTP_USERS SU ON SVI.USER_ID = SU.USER_ID WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'].")";
			$result_delete_credentials = $this->db->query($delete_credentials);
			
			$delete_user = "DELETE FROM SMNTP_USERS where USER_ID IN (SELECT USER_ID FROM (SELECT SU.USER_ID FROM SMNTP_VENDOR_INVITE SVI JOIN SMNTP_USERS SU ON SVI.USER_ID = SU.USER_ID WHERE SVI.VENDOR_INVITE_ID = ".$data['vid'].") AS t)";
			$result_del_user = $this->db->query($delete_user);
			
			$delete_vendor_invite = "DELETE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ".$data['vid'];
			$result_delete_vendor_invite = $this->db->query($delete_vendor_invite);
			
			$delete_vendor = "DELETE FROM SMNTP_VENDOR WHERE VENDOR_INVITE_ID = ".$data['vid'];
			$result_delete_vendor = $this->db->query($delete_vendor);
			
			if($result_del_user == 1 && $result_delete_vendor_invite == 1){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	function get_sender_email($data){
		return $this->db->query('SELECT USER_EMAIL FROM SMNTP_USERS WHERE USER_ID = ? ', array($data))->result();
	}
	
	function get_vendor_invite_id($vendor_id){
		return $this->db->query('SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR WHERE VENDOR_ID = ?', array($vendor_id))->result_array();
	}
	
	//Primary Document Waived
	function get_rd_waive_data($id){
		return $this->db->query('SELECT * FROM SMNTP_VENDOR_WAIVE_PD A 
									LEFT JOIN SMNTP_VENDOR_WAIVE_PD_FLAG B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID 
									LEFT JOIN SMNTP_VENDOR_WAIVE_PD_REMARKS C ON A.VENDOR_INVITE_ID = C.VENDOR_INVITE_ID 
									WHERE A.VENDOR_INVITE_ID = ?', array($id))->result_array();
	}
	
	// Additional Document Waived
	function get_ad_waive_data($id){
		return $this->db->query('SELECT * FROM SMNTP_VENDOR_WAIVE_AD A 
									LEFT JOIN SMNTP_VENDOR_WAIVE_AD_FLAG B ON A.VENDOR_INVITE_ID = B.VENDOR_INVITE_ID 
									LEFT JOIN SMNTP_VENDOR_WAIVE_AD_REMARKS C ON A.VENDOR_INVITE_ID = C.VENDOR_INVITE_ID 
									WHERE A.VENDOR_INVITE_ID = ?', array($id))->result_array();
	}
	//jay
	function set_waive($data){
		//Primary Documents
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_PD_FLAG WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		foreach($data['rsd_waive'] as $key => $reqdocs_id){
			$next_waive_flag_id = $this->get_next_rd_waive_flag_id();
			if(empty($next_waive_flag_id)){
				$next_waive_flag_id = 1;
			}else{
				$next_waive_flag_id = $next_waive_flag_id[0]['WAIVE_FLAG_ID'] + 1;
			}
			$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_PD_FLAG
								(WAIVE_FLAG_ID,
									VENDOR_INVITE_ID,
									REQDOCS_ID,
									WAIVE) 
								VALUES(?, ?, ?, 1)', array(
									$next_waive_flag_id,
									$data['vendor_invite_id'] ,
									$reqdocs_id
								));
		}
		
		//INSERT remarks
		$next_waive_remark_id = $this->get_next_rd_waive_remark_id();
		if(empty($next_waive_remark_id)){
			$next_waive_remark_id = 1;
		}else{
			$next_waive_remark_id = $next_waive_remark_id[0]['WAIVE_REMARK_ID'] + 1;
		}
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_PD_REMARKS WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_PD_REMARKS
							(WAIVE_REMARK_ID,
								VENDOR_INVITE_ID,
								REMARK) 
							VALUES(?, ?, ?)', array(
								$next_waive_remark_id,
								$data['vendor_invite_id'],
								$data['rsd_waive_remarks']
							));
		
		//INSERT WAIVE
		$next_waive_id = $this->get_next_ad_waive_id();
		if(empty($next_waive_id)){
			$next_waive_id = 1;
		}else{
			$next_waive_id = $next_waive_id[0]['WAIVE_ID'] + 1;
		}
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_PD WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_PD
							(WAIVE_ID,
								VENDOR_INVITE_ID,
								VRD_STAFF_USER_ID) 
							VALUES(?, ?, ?)', array(
								$next_waive_id,
								$data['vendor_invite_id'],
								$data['user_id']
							));
		
		//Additional Documents
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_AD_FLAG WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		foreach($data['ad_waive'] as $key => $adddocs_id){
			$next_waive_flag_id = $this->get_next_ad_waive_flag_id();
			if(empty($next_waive_flag_id)){
				$next_waive_flag_id = 1;
			}else{
				$next_waive_flag_id = $next_waive_flag_id[0]['WAIVE_FLAG_ID'] + 1;
			}
			$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_AD_FLAG
								(WAIVE_FLAG_ID,
									VENDOR_INVITE_ID,
									ADDDOCS_ID,
									WAIVE) 
								VALUES(?, ?, ?, 1)', array(
									$next_waive_flag_id,
									$data['vendor_invite_id'] ,
									$adddocs_id
								));
		}
		
		//INSERT remarks
		$next_waive_remark_id = $this->get_next_ad_waive_remark_id();
		if(empty($next_waive_remark_id)){
			$next_waive_remark_id = 1;
		}else{
			$next_waive_remark_id = $next_waive_remark_id[0]['WAIVE_REMARK_ID'] + 1;
		}
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_AD_REMARKS WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_AD_REMARKS
							(WAIVE_REMARK_ID,
								VENDOR_INVITE_ID,
								REMARK) 
							VALUES(?, ?, ?)', array(
								$next_waive_remark_id,
								$data['vendor_invite_id'],
								$data['ad_waive_remarks']
							));
		
		//INSERT WAIVE
		$next_waive_id = $this->get_next_ad_waive_id();
		if(empty($next_waive_id)){
			$next_waive_id = 1;
		}else{
			$next_waive_id = $next_waive_id[0]['WAIVE_ID'] + 1;
		}
		$this->db->query('DELETE FROM SMNTP_VENDOR_WAIVE_AD WHERE VENDOR_INVITE_ID = ? ', array($data['vendor_invite_id']));
		$this->db->query('INSERT INTO SMNTP_VENDOR_WAIVE_AD
							(WAIVE_ID,
								VENDOR_INVITE_ID,
								VRD_STAFF_USER_ID) 
							VALUES(?, ?, ?)', array(
								$next_waive_id,
								$data['vendor_invite_id'],
								$data['user_id']
							));
	}
	
	//jay
	 function check_additional_requirements_waive($data){
		
		$total_additional_documents = 0;
		if(is_array($data)){	
			$ra_docs = $this->get_ra_docs($data);
				
				//return count($ra_docs);
			if(is_array($ra_docs)){
				$total_additional_documents = count($ra_docs);
			}else if($ra_docs <= -1){
				$total_additional_documents = 0;
			}
		}else{
			$total_additional_documents = $data;
		}
		
		$total_waived_additional_documents = $this->db->query('SELECT COUNT(*) AS TOTAL FROM SMNTP_VENDOR_WAIVE_AD_FLAG 
																	WHERE VENDOR_INVITE_ID = ?', array($data['invite_id']))->row_array()['TOTAL'];
																
		if($total_additional_documents == $total_waived_additional_documents){
			return true;
		}else{
			return false;
		}															
	}
	
	function get_ra_docs3($data){
		$result = $this->db->query('SELECT VENDOR_TYPE, OWNERSHIP_TYPE, TRADE_VENDOR_TYPE FROM SMNTP_VENDOR WHERE VENDOR_INVITE_ID = ?',
											array($data['invite_id']))->result_array()[0];
		$ownership_id = $result['OWNERSHIP_TYPE'];
		$vendor_type = $result['VENDOR_TYPE'];
		$total_additional_documents = 0;

		$categories = $this->db->query('SELECT CATEGORY_ID FROM SMNTP_VENDOR_CATEGORIES WHERE VENDOR_INVITE_ID = ?', array($data['invite_id']))->result_array();
			
		$category_where_in = 'CATEGORY_ID IN (';
		$temp = '';
		$cat_array = array();
		foreach($categories as $r){
			if( ! empty($r['CATEGORY_ID'])){
				$temp .= $r['CATEGORY_ID'];
				$cat_array[] = $r['CATEGORY_ID'];
			}
		}

		$vendor_type = $result['TRADE_VENDOR_TYPE'];
		if($result['VENDOR_TYPE'] == 3 || $result['VENDOR_TYPE'] == 4){
			$vendor_type_id = 4;
		}else if($vendor_type == 0){
			$vendor_type_id = 3;
		}else{
			$vendor_type_id = $vendor_type;
		}
		if($vendor_type == 3){
			$vendor_type = 'VENDOR_TYPE_ID IS NULL';
		}else{
			$vendor_type = 'VENDOR_TYPE_ID = ' . $vendor_type;
		}

		$ra_docs = $this->get_ra_docs(array(
			'trade_vendor_type' => $vendor_type_id,
			'ownership' => $ownership_id,
			'category_id' => $cat_array,
			'invite_id' => $data['invite_id']
		));
			
		//return count($ra_docs);
		if(is_array($ra_docs)){
			$total_additional_documents = count($ra_docs);
			if($total_additional_documents > 0){
				return true;
			}
		}else if($ra_docs <= -1){
			$total_additional_documents = 0;
		}	
		
		return false;														
	}
	
	//Primary Documents
	//jay
	function get_next_rd_waive_flag_id(){
		return $this->db->query('SELECT MAX(WAIVE_FLAG_ID) + 1 AS WAIVE_FLAG_ID FROM SMNTP_VENDOR_WAIVE_PD_FLAG')->result_array();
	}
	
	//jay
	function get_next_rd_waive_remark_id(){
		return $this->db->query('SELECT MAX(WAIVE_REMARK_ID) + 1 AS WAIVE_REMARK_ID FROM SMNTP_VENDOR_WAIVE_PD_REMARKS')->result_array();
	}
	
	//jay
	function get_next_rd_waive_id(){
		return $this->db->query('SELECT MAX(WAIVE_ID) + 1 AS WAIVE_ID FROM SMNTP_VENDOR_WAIVE_PD')->result_array();
	}
	
	//Additional Documents
	//jay
	function get_next_ad_waive_flag_id(){
		return $this->db->query('SELECT MAX(WAIVE_FLAG_ID) + 1 AS WAIVE_FLAG_ID FROM SMNTP_VENDOR_WAIVE_AD_FLAG')->result_array();
	}
	
	//jay
	function get_next_ad_waive_remark_id(){
		return $this->db->query('SELECT MAX(WAIVE_REMARK_ID) + 1 AS WAIVE_REMARK_ID FROM SMNTP_VENDOR_WAIVE_AD_REMARKS')->result_array();
	}
	
	//jay
	function get_next_ad_waive_id(){
		return $this->db->query('SELECT MAX(WAIVE_ID) + 1 AS WAIVE_ID FROM SMNTP_VENDOR_WAIVE_AD')->result_array();
	}

	function get_ra_docs2($data = array())
	{
		$query = $this->db->get_where('SMNTP_VP_REQUIRED_AGREEMENTS',array('LOWER(REQUIRED_AGREEMENT_NAME)'=>'others', 'ACTIVE'=>'1'));
		
		if(isset($data['trade_vendor_type'])){
			if(!isset($data['ownership']) || $data['ownership'] == ""){
				return "-1";
			}
			
			if($data['trade_vendor_type'] == 3 || $data['trade_vendor_type'] == 4 || empty($data['trade_vendor_type'] )){
				$c_id = $data['category_id'];
				$ccount = count($c_id);

			
				$this->db->select('CATEGORY_ID');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN');
				
				if($data['trade_vendor_type'] == 4){
					$this->db->where(array('VENDOR_TYPE_ID' => 3));
				}else{
					$this->db->where(array('VENDOR_TYPE_ID' => null));
				}

				$this->db->where(array('AGREEMENT_ID' => '-1'));
				$this->db->where(array('OWNERSHIP_ID' =>$data['ownership']));
				if($ccount > 1){
					$this->db->where_in('CATEGORY_ID',$c_id);
				}


				if($ccount == 1){
					if(is_array($c_id)){
						$this->db->where(array('CATEGORY_ID' => $c_id[0]));
					}else{
						$this->db->where(array('CATEGORY_ID' => $c_id));
					}		
				}
				
				$tmp_neg_count = $this->db->get()->result_array();
				$neg_count = array();

				foreach ($tmp_neg_count as $key => $value) {
					array_push($neg_count,$value['CATEGORY_ID']);
				}
			
				if(count($c_id) == count($neg_count)){
					return "-1"; // same count category sa category from query na -1
				}

				$c_id = array_diff($c_id, $neg_count); //remove all cat_id -> agreement_id = -1;


				$this->db->select('*');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
				$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
				$this->db->where(array('A.OWNERSHIP_ID' => $data['ownership']));
				$this->db->where(array('A.ACTIVE'=> 1));
				$this->db->where(array('B.ACTIVE'=> 1));
				
				$new_cid_index = array();
				foreach($c_id as $key => $value){
					$new_cid_index[] = $value;
				}
				$c_id = $new_cid_index;
				
				if(count($c_id) == 1){
					if(is_array($c_id)){
						$this->db->where(array('CATEGORY_ID' => $c_id[0]));
					}else{
						$this->db->where(array('CATEGORY_ID' => $c_id));
					}
				}
				if(count($c_id) > 1){
					$this->db->where_in('CATEGORY_ID',$c_id);
				}

				


				
				$result = $this->db->get()->result_array();

				//return $result;
				//return $result;

				$tempArr = array_unique(array_column($result, 'AGREEMENT_ID'));
				$tempArr = (array_intersect_key($result, $tempArr));

				$new_array = array();

				foreach ($tempArr as $key => $value) {
					array_push($new_array, $value);
				}
				
				if(count($result) == 0 ){
					return $this->select_ra_defn2($data['ownership'], $data['trade_vendor_type']);
				}else{

					// merge array of "others" documents to result if found
					if ($query->num_rows()){
						$other_doc = $query->result_array();
						$new_array = array_merge($new_array, $other_doc);
					} else {
						$new_array = $new_array;				
					}
				
					$i = 0;
					for($i = 0; $i < count($new_array);$i++){
						$new_array[$i]['DOCUMENT_TYPE'] = 2;
					}

					return $new_array;
				}

			}else{
				//for trade -> where(ownership id & vendortypeid)

				//JOIN SMNTP_INCOMPLETE_DOC_REASONS IDR ON IDR.DOCUMENT_ID = RD.REQUIRED_DOCUMENT_ID AND IDR.DOCUMENT_TYPE = 1
				$this->db->select('*');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
				$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
				$this->db->where(array('A.OWNERSHIP_ID' => $data['ownership']));
				$this->db->where(array('A.ACTIVE'=> 1));
				$this->db->where(array('B.ACTIVE'=> 1));
				$this->db->where(array('A.CATEGORY_ID'=>null));
				$this->db->where(array('A.VENDOR_TYPE_ID'=>$data['trade_vendor_type']));
				$docs = $this->db->get()->result_array();
				
				// merge array of "others" documents to result if found
				if ($query->num_rows()){
					$other_doc = $query->result_array();
					$res = array_merge($docs, $other_doc);
				} else {
					$res = $docs;				
				}

				$i = 0;
				for($i = 0; $i < count($res);$i++){
					$res[$i]['DOCUMENT_TYPE'] = 2;
				}
				
				//jay
				return $res;
			}
		}else{
			//jay
			$this->db->select('*');
			$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS SVRA');

			// if (!empty($data['trade_vendor_type'])) // ownership na din sya
			// 	$this->db->join('SMNTP_VP_REQUIRED_AGMT_DEFN SVRAD', 'SVRA.REQUIRED_AGREEMENT_ID = SVRAD.AGREEMENT_ID AND SVRAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type']);

			if (!empty($data['trade_vendor_type'])){
				if($data['trade_vendor_type'] == 3){
					$join_info = ' AND (SVRAD.VENDOR_TYPE_ID IS NULL) ';
				}else{
					$join_info = ' AND (SVRAD.VENDOR_TYPE_ID = '.$data['trade_vendor_type'].') ';
				}
			}
			else{
				$join_info = '';
			}

			if (!empty($data['ownership']))
				$this->db->join('SMNTP_VP_REQUIRED_AGMT_DEFN SVRAD', 'SVRA.REQUIRED_AGREEMENT_ID = SVRAD.AGREEMENT_ID AND SVRAD.OWNERSHIP_ID = '.$data['ownership'].$join_info);
			$this->db->where('SVRA.ACTIVE',1);
			$docs = $this->db->get();
			
			// merge array of "others" documents to result if found
			if ($query->num_rows()){
				$other_doc = $query;
				$res = array_merge($docs, $other_doc);
			} else {
				$res = $docs;				
			}

			$i = 0;
			for($i = 0; $i < count($result);$i++){
			$result[$i]['DOCUMENT_TYPE'] = 2;
			}
			return $result->result_array();
		}

	}
	function select_ra_defn2($own,  $trade_vendor_type = null)
	{
		$query = $this->db->get_where('SMNTP_VP_REQUIRED_AGREEMENTS',array('LOWER(REQUIRED_AGREEMENT_NAME)'=>'others', 'ACTIVE'=>'1'));

		$this->db->select('*');
		$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
		$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS B','B.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
		$this->db->where(array('A.OWNERSHIP_ID' => $own));
		$this->db->where(array('A.ACTIVE'=> 1));
		$this->db->where(array('B.ACTIVE'=> 1));
		$this->db->where(array('A.CATEGORY_ID'=>null));
		//$this->db->where(array('A.VENDOR_TYPE_ID'=>null));
		
		if(!empty($trade_vendor_type) && $trade_vendor_type == 4){
			$this->db->where(array('VENDOR_TYPE_ID' => 3));
		}else{
			$this->db->where(array('VENDOR_TYPE_ID' => null));
		}

		$this->db->where('A.AGREEMENT_ID !=','-1',false);
		$docs = $this->db->get()->result_array();
		
		// merge array of "others" documents to result if found
		if ($query->num_rows()){
			$other_doc = $query->result_array();
			$res = array_merge($docs, $other_doc);
		} else {
			$res = $docs;				
		}
		
		$i = 0;
		for($i = 0; $i < count($res);$i++){
			$res[$i]['DOCUMENT_TYPE'] = 2;
		}

		return $res;

	}

	function get_primary_docs($data)
	{

		$query = $this->db->get_where('SMNTP_VP_REQUIRED_DOCUMENTS',array('LOWER(REQUIRED_DOCUMENT_NAME)'=>'others', 'ACTIVE'=>'1'));
		
		if($data['trade_vendor_type'] == 3 || $data['trade_vendor_type'] == 4){

			$this->db->select('*');
			$this->db->from('SMNTP_VP_REQUIRED_DOCS_DEFN A');
			$this->db->join('SMNTP_VP_REQUIRED_DOCUMENTS B','B.REQUIRED_DOCUMENT_ID = A.DOC_ID','LEFT');
			$this->db->where(array('A.OWNERSHIP_ID' =>  $data['ownership']));
			$this->db->where(array('A.ACTIVE'=> 1));
			$this->db->where(array('B.ACTIVE'=> 1));
			
			if($data['trade_vendor_type'] == 3){
				$this->db->where(array('A.VENDOR_TYPE_ID'=>null));
			}else{
				$this->db->where(array('A.VENDOR_TYPE_ID'=> '3'));
			}
			$docs = $this->db->get()->result_array();
			
			if ($query->num_rows()){
				$other_doc = $query->result_array();
				$res = array_merge($docs, $other_doc);
			} else {
				$res = $docs;				
			}
		
			$i = 0;
			for($i = 0; $i < count($res);$i++){
				$res[$i]['DOCUMENT_TYPE'] = 1;
			}

			return $res;

		}else{

			$this->db->select('*');
			$this->db->from('SMNTP_VP_REQUIRED_DOCS_DEFN A');
			$this->db->join('SMNTP_VP_REQUIRED_DOCUMENTS B','B.REQUIRED_DOCUMENT_ID = A.DOC_ID','LEFT');
			$this->db->where(array('A.OWNERSHIP_ID' => $data['ownership']));
			$this->db->where(array('A.ACTIVE'=> 1));
			$this->db->where(array('B.ACTIVE'=> 1));
			$this->db->where(array('A.VENDOR_TYPE_ID'=>$data['trade_vendor_type']));
			// $res = $this->db->get()->result_array();

			$docs = $this->db->get()->result_array();
			
			// merge array of "others" documents to result if found
			if ($query->num_rows()){
				$other_doc = $query->result_array();
				$res = array_merge($docs, $other_doc);
			} else {
				$res = $docs;				
			}
		


			$i = 0;
			for($i = 0; $i < count($res);$i++){
				$res[$i]['DOCUMENT_TYPE'] = 1;
			}

			return $res;

		}

	}
	
	
	public function get_users_in_matrix($user_id){
		return $this->db->query('SELECT * FROM SMNTP_USERS_MATRIX WHERE USER_ID = ?', array($user_id))->result_array();
	}
	
	public function get_vrdhead_in_matrix($user_id){
		return $this->db->query('SELECT * FROM SMNTP_USERS_MATRIX WHERE USER_ID = ? AND VRDHEAD_ID IS NOT NULL', array($user_id))->result_array();
	}
	
	public function update_vendor_submitted_date($vendor_id){
		return $this->db->query('UPDATE SMNTP_VENDOR SET DATE_CREATED = CURRENT_TIMESTAMP WHERE VENDOR_ID = ?', array($vendor_id), FALSE);
	}
	
	public function get_vendor_invite_logs($data){
		return $this->db->get_where('SMNTP_VENDOR_STATUS_LOGS', $data);
	}
	
	public function get_user($data){
		return $this->db->get_where('SMNTP_USERS', $data);
	}
	
	public function get_vendor_invite($data){
		return $this->db->get_where('SMNTP_VENDOR_INVITE', $data);
	}
	
	public function al_brand($vendor_id){
		// get existing brand details
		$this->db->select('SVB.VENDOR_ID, SVB.BRAND_ID, SB.BRAND_NAME');
		$this->db->from('SMNTP_VENDOR_BRAND SVB');
		$this->db->join('SMNTP_BRAND SB','SVB.BRAND_ID = SB.BRAND_ID');
		$this->db->where('SVB.VENDOR_ID',$vendor_id);
		$query = $this->db->get();
		$var['result'] = $query->result_array();
		$var['num_row'] = $query->num_rows();
		
		return $var;
	}
	
	public function al_tax_basset_nemployee_years($vendor_id){
		$this->db->select('TAX_ID_NO, TAX_CLASSIFICATION, YEAR_IN_BUSINESS, EMPLOYEE, BUSINESS_ASSET');
		$this->db->from('SMNTP_VENDOR');
		$this->db->where('VENDOR_ID',$vendor_id);
		$query = $this->db->get();
		$var['result'] = $query->result_array();
		$var['num_row'] = $query->num_rows();
		
		return $var;
	}
	
	public function al_contact_details($vendor_id, $contact_type_details){
		$this->db->select('CONTACT_DETAIL, COUNTRY_CODE, AREA_CODE, EXTENSION_LOCAL_NUMBER');
		$this->db->from('SMNTP_VENDOR_CONTACT_DETAILS');
		$this->db->where('VENDOR_ID',$vendor_id);
		$this->db->where('CONTACT_DETAIL_TYPE',$contact_type_details);
		$query = $this->db->get();
		$var['result'] = $query->result_array();
		$var['num_row'] = $query->num_rows();
		
		return $var;
	}
	
	public function al_address_details($vendor_id, $address_type){
		$this->db->select('SVA.VENDOR_ID, SVA.ADDRESS_TYPE, SVA.ADDRESS_LINE, SVA.BRGY_MUNICIPALITY_ID, SCY.CITY_NAME, SVA.STATE_PROVINCE_ID, SSP.STATE_PROV_NAME, SVA.COUNTRY_ID, SCTY.COUNTRY_NAME, SVA.ZIP_CODE, SVA.REGION_ID, SRS.REGION_DESC_TWO');
		$this->db->from('SMNTP_VENDOR_ADDRESSES SVA');
		$this->db->join('SMNTP_CITY SCY','SVA.BRGY_MUNICIPALITY_ID = SCY.CITY_ID');
		$this->db->join('SMNTP_STATE_PROVINCE SSP','SVA.STATE_PROVINCE_ID = SSP.STATE_PROV_ID');
		$this->db->join('SMNTP_COUNTRY SCTY','SVA.COUNTRY_ID = SCTY.COUNTRY_ID');
		$this->db->join('SMNTP_REGIONS SRS','SVA.REGION_ID = SRS.REGION_ID', 'LEFT');
		$this->db->where('SVA.VENDOR_ID',$vendor_id);
		$this->db->where('SVA.ADDRESS_TYPE',$address_type);
		$query = $this->db->get();
		$var['result'] = $query->result_array();
		$var['num_row'] = $query->num_rows();
		
		return $var;
	}
	
	public function al_o_ar($vendor_id, $table_name){
		$this->db->select('FIRST_NAME, MIDDLE_NAME, LAST_NAME, POSITION');
		$this->db->from($table_name);
		$this->db->where('VENDOR_ID',$vendor_id);
		$query = $this->db->get();
		$var['result'] = $query->result_array();
		$var['num_row'] = $query->num_rows();
		
		return $var;
	}

	function insert_vendor_request_logs($invite_id, $request_date, $request_type, $vendor_pass_qty){
        /*$check_exists = $this->db->query("SELECT * FROM SMNTP_VENDOR_REQUEST_HISTORY WHERE VENDOR_INVITE_ID = ".$invite_id." AND QUANTITY= '".$quantity."' AND REQUEST_TYPE = '".$request_type."'")->result_array();
        if(count($check_exists > 0)){*/
                $date_modified = date('Y-m-d H:i:s');        
                $insert_vrpass_log = "INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, REQUEST_TYPE, ID_TYPE, QUANTITY) ";
                $insert_vrpass_log .= "VALUES(".$invite_id.", NOW(), '".$request_date."', '".$request_type."', 'PASS', '".$vendor_pass_qty."')";
                $result_insert_vrpass_logs = $this->db->query($insert_vrpass_log);
                return $insert_vrpass_log;
        /*}else{
                return 1;
        }*/
	}

    function insert_vendor_id_request_logs($invite_id, $request_date, $request_type, $last_name, $middle_initial, $first_name, $position){
        /*$check_exists = $this->db->query("SELECT * FROM SMNTP_VENDOR_REQUEST_HISTORY WHERE VENDOR_INVITE_ID = ".$invite_id." AND FIRST_NAME = '".$first_name."' AND MIDDLE_INITIAL = '".$middle_initial."' AND LAST_NAME = '".$last_name."' AND REQUEST_TYPE = '".$request_type."'")->result_array();
        if(count($check_exists > 0)){*/
                $date_modified = date('Y-m-d H:i:s');        
                $insert_vrid_log = "INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, REQUEST_TYPE, ID_TYPE, LAST_NAME, MIDDLE_INITIAL, FIRST_NAME, DESIGNATION, QUANTITY) ";
                $insert_vrid_log .= "VALUES(".$invite_id.", NOW(), '".$request_date."', '".$request_type."', 'ID', '".$last_name."', '".$middle_initial."', '".$first_name."', '".$position."', 1)";
                $result_insert_vrid_logs = $this->db->query($insert_vrid_log);
                return $insert_vrid_log;
        /*}else{
                return 1;
        }*/
    }
	
	function insert_logs($vendor_id, $user_id, $var_from, $var_to, $modified_field){
		
		$date_modified = date('Y-m-d H:i:s');	
		$insert_log = "INSERT INTO SMNTP_VENDOR_AUDIT_LOGS(VENDOR_ID, USER_ID, VAR_FROM, VAR_TO, MODIFIED_FIELD, MODIFIED_DATE) VALUES(".$vendor_id.",".$user_id.",'".addslashes($var_from)."','".addslashes($var_to)."','".$modified_field."','".$date_modified."')";
		$result_insert_logs = $this->db->query($insert_log);
		return $insert_log;
	}
	
	function checked_na_rsd($vendor_id){
		$update_rsd = "UPDATE SMNTP_VENDOR_REQUIRED_DOC
						SET DATE_VERIFIED = NOW() 
						WHERE VENDOR_ID = ".$vendor_id."
						AND REQUIRED_DOC_ID NOT IN 
						(SELECT REQUIRED_DOC_ID FROM (SELECT REQUIRED_DOC_ID FROM SMNTP_VENDOR_REQUIRED_DOC WHERE DATE_REVIEWED IS NULL AND VENDOR_ID = ".$vendor_id.") AS t)";
		$result_rsd = $this->db->query($update_rsd);
		return $result_rsd;
		
	}
	
	function checked_na_ra($vendor_id){
		$update_ra = "UPDATE SMNTP_VENDOR_AGREEMENTS
						SET DATE_SUBMITTED = NOW() 
						WHERE VENDOR_ID = ".$vendor_id."
						AND VENDOR_AGREEMENT_ID NOT IN 
						(SELECT VENDOR_AGREEMENT_ID FROM (SELECT VENDOR_AGREEMENT_ID FROM SMNTP_VENDOR_AGREEMENTS WHERE DATE_REVIEWED IS NULL AND VENDOR_ID = ".$vendor_id.") AS t)";
		$result_ra = $this->db->query($update_ra);
		return $result_ra;
		
	}
	
	function checked_na_ccn($vendor_id){
		$update_ccn = "UPDATE SMNTP_VENDOR_CCN
						SET DATE_SUBMITTED = NOW() 
						WHERE VENDOR_ID = ".$vendor_id;
		$result_ccn = $this->db->query($update_ccn);
		return $result_ccn;
		
	}

	function al_sm_vendor_system($vendor_id){
		$get_vendor_info = $this->db->query("SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR WHERE VENDOR_ID = ".$vendor_id)->result_array();
		$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];


		$finalquery = $this->db->query('
		SELECT 
		SVSST.VENDOR_INVITE_ID, SSS.SM_SYSTEM_ID, SSS.TRADE_VENDOR_TYPE, CONCAT(SSS.DESCRIPTION, " - ", CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" ELSE "STORE CONSIGNOR" END) AS SM_SYSTEM_DESC,
		CONCAT(SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME,SVSS.POSITION) AS FN1, SVSS.EMAIL AS EA1, SVSS.MOBILE_NO AS MN1,
		SVSST.FIRST_NAME, SVSST.MIDDLE_NAME, SVSST.LAST_NAME,SVSST.POSITION,
		CONCAT(SVSST.FIRST_NAME, SVSST.MIDDLE_NAME, SVSST.LAST_NAME,SVSST.POSITION) AS FN2, SVSST.EMAIL AS EA2, SVSST.MOBILE_NO AS MN2
		FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSST
		JOIN SMNTP_SM_SYSTEMS SSS ON SVSST.SM_SYSTEM_ID = SSS.SM_SYSTEM_ID
		LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS ON SVSS.VENDOR_INVITE_ID = SVSST.VENDOR_INVITE_ID AND SVSS.TRADE_VENDOR_TYPE = SVSST.TRADE_VENDOR_TYPE AND SVSST.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID
		WHERE SVSST.VENDOR_INVITE_ID = '.$vendor_invite_id)->result_array();

        return $finalquery;
	}

	function al_vendor_request($vendor_id){
        //$get_vendor_info = $this->db->query("SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR WHERE VENDOR_ID = ".$vendor_id)->result_array();
        //$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];
        //return $vendor_invite_id;
        $query = $this->db->query('SELECT VENDOR_REQUEST_ID, VENDOR_INVITE_ID, DATE_CREATED, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION, USER_ID 
        	FROM SMNTP_VENDOR_REQUESTS_TEMP 
        	WHERE VENDOR_INVITE_ID = '.$vendor_id)->result_array();
        return $query;
    }

    function al_vendor_id_request($vendor_id){
        //$get_vendor_info = $this->db->query("SELECT VENDOR_INVITE_ID FROM SMNTP_VENDOR WHERE VENDOR_ID = ".$vendor_id)->result_array();
        //var_dump($vendor_id);die();
        //$vendor_invite_id = $get_vendor_info[0]['VENDOR_INVITE_ID'];
        $vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_id."")->row()->VENDOR_REQUEST_ID;
        $finalquery = $this->db->query("
        SELECT VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, FIRST_NAME, MIDDLE_INITIAL, LAST_NAME, DESIGNATION, REQUEST_TYPE, DATE_CREATED FROM SMNTP_VENDOR_ID_REQUESTS_TEMP
        WHERE VENDOR_REQUEST_ID = ".$vendor_request_id)->result_array();
    	return $finalquery;
    }

    function al_vendor_request_two($vendor_invite_id){
        $finalquery = $this->db->query("
        SELECT VENDOR_REQUEST_ID, VENDOR_INVITE_ID, DATE_CREATED, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION, USER_ID FROM SMNTP_VENDOR_REQUESTS_TEMP
        WHERE VENDOR_INVITE_ID = ".$vendor_invite_id)->result_array();
    	return $finalquery;
    }

    function al_vendor_id_request_two($vendor_invite_id){
        $vendor_request_id = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

        $count = count($vendor_request_id);


       	if($count != 0){
       		 $row_vendor_request = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->row()->VENDOR_REQUEST_ID;

       
        	$finalquery = $this->db->query("
	        SELECT VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, FIRST_NAME, MIDDLE_INITIAL, LAST_NAME, DESIGNATION, REQUEST_TYPE, DATE_CREATED FROM SMNTP_VENDOR_ID_REQUESTS_TEMP
	        WHERE VENDOR_REQUEST_ID = ".$row_vendor_request)->result_array();

	        return $finalquery;
       
       	}
       	
       
        
        //var_dump($this->db->last_query());die();
    	
    }

	function al_sm_vendor_system_two($vendor_invite_id){
		$finalquery = $this->db->query('
		SELECT 
		SVSST.VENDOR_INVITE_ID, SSS.SM_SYSTEM_ID, SSS.TRADE_VENDOR_TYPE, CONCAT(SSS.DESCRIPTION, " - ", CASE SSS.TRADE_VENDOR_TYPE WHEN 1 THEN "OUTRIGHT" ELSE "STORE CONSIGNOR" END) AS SM_SYSTEM_DESC,
		CONCAT(SVSS.FIRST_NAME, SVSS.MIDDLE_NAME, SVSS.LAST_NAME,SVSS.POSITION) AS FN1, SVSS.EMAIL AS EA1, SVSS.MOBILE_NO AS MN1,
		SVSST.FIRST_NAME, SVSST.MIDDLE_NAME, SVSST.LAST_NAME,SVSST.POSITION,
		CONCAT(SVSST.FIRST_NAME, SVSST.MIDDLE_NAME, SVSST.LAST_NAME,SVSST.POSITION) AS FN2, SVSST.EMAIL AS EA2, SVSST.MOBILE_NO AS MN2
		FROM SMNTP_VENDOR_SM_SYSTEMS_TEMP SVSST
		JOIN SMNTP_SM_SYSTEMS SSS ON SVSST.SM_SYSTEM_ID = SSS.SM_SYSTEM_ID
		LEFT JOIN SMNTP_VENDOR_SM_SYSTEMS SVSS ON SVSS.VENDOR_INVITE_ID = SVSST.VENDOR_INVITE_ID AND SVSS.TRADE_VENDOR_TYPE = SVSST.TRADE_VENDOR_TYPE AND SVSST.SM_SYSTEM_ID = SVSS.SM_SYSTEM_ID
		WHERE SVSST.VENDOR_INVITE_ID = '.$vendor_invite_id)->result_array();

        return $finalquery;
	}

	function insert_vrpass($vendor_request_id, $vendor_invite_id, $date_created, $approval_date, $vendorname, $total_pass_qty, $total_amount_deduction, $user_id){

        $get_old_pass = $this->db->query("SELECT VENDOR_REQUEST_ID FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."")->result_array();

        if(count($get_old_pass) > 0){
        	$delete_old_pass = "DELETE FROM SMNTP_VENDOR_PASS WHERE VENDOR_REQUEST_ID = ".$get_old_pass[0]['VENDOR_REQUEST_ID']."";
        	$result_old_pass = $this->db->query($delete_old_pass);
        }
        
        
        $delete_vrpass = "DELETE FROM SMNTP_VENDOR_REQUESTS WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."";
        $result_delete_vrpass = $this->db->query($delete_vrpass);

        $date_created = date('Y-m-d H:i:s');
        $insert_vrpass = "INSERT INTO SMNTP_VENDOR_REQUESTS (VENDOR_REQUEST_ID, VENDOR_INVITE_ID, APPROVAL_DATE, VENDOR_NAME, TOTAL_PASS_QTY, TOTAL_AMOUNT_DEDUCTION, USER_ID, DATE_CREATED) ";
        $insert_vrpass .= 'VALUES('.$vendor_request_id.', '.$vendor_invite_id.', "'.$approval_date.'", "'.$vendorname.'", '.$total_pass_qty.', "'.$total_amount_deduction.'", '.$user_id.', NOW())';
        $result_insert_vrpass = $this->db->query($insert_vrpass);

        //var_dump($insert_vrpass);die;

        $get_new_pass = $this->db->query("SELECT * FROM SMNTP_VENDOR_PASS_TEMP WHERE VENDOR_REQUEST_ID = ".$vendor_request_id."")->result_array();

        for($x=0; $x<count($get_new_pass); $x++){
        	$insert_vrpass = "INSERT INTO SMNTP_VENDOR_PASS (VENDOR_REQUEST_ID, VENDOR_CODE, VENDOR_CODE_02, EMAIL_ADD_OUTRIGHT, EMAIL_ADD_SC, TRADE_VENDOR_TYPE, QTY, REQUEST_TYPE) ";
	        $insert_vrpass .= "VALUES (".$vendor_request_id.", '".$get_new_pass[$x]['VENDOR_CODE']."', '".$get_new_pass[$x]['VENDOR_CODE_02']."', '".$get_new_pass[$x]['EMAIL_ADD_OUTRIGHT']."', '".$get_new_pass[$x]['EMAIL_ADD_SC']."', '".$get_new_pass[$x]['TRADE_VENDOR_TYPE']."',  '".$get_new_pass[$x]['QTY']."', '".$get_new_pass[$x]['REQUEST_TYPE']."')";
	        $result_insert_vrpass = $this->db->query($insert_vrpass);

	        //var_dump($result_insert_vrpass);die();
        }
/*
        $insert_vrpass_log = "INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, REQUEST_TYPE, ID_TYPE, QUANTITY) ";
        $insert_vrpass_log .= "VALUES(".$vendor_invite_id.", NOW(), '".$request_date."', '".$request_type."', 'PASS', '".$vendor_pass_qty."')";
        $result_insert_vrpass_logs = $this->db->query($insert_vrpass_log);
*/

        /*$delete_vrpass_temp = "DELETE FROM SMNTP_VENDOR_REQUESTS_TEMP WHERE VENDOR_INVITE_ID = ".$vendor_invite_id."";
        $result_delete_vrpass_temp = $this->db->query($delete_vrpass_temp);*/

        return $result_insert_vrpass;
    }
    
    function insert_vrid($vendor_request_id, $trade_vendor_type, $first_name, $middle_initial, $last_name, $position, $request_type){
        
        $delete_vrid = "DELETE FROM SMNTP_VENDOR_ID_REQUESTS WHERE FIRST_NAME = '".$first_name."' AND LAST_NAME = '".$last_name."' AND DESIGNATION = '".$position."' AND VENDOR_REQUEST_ID = ".$vendor_request_id."";
        $result_delete_vrid = $this->db->query($delete_vrid);
        $date_created = date('Y-m-d H:i:s');
        $insert_vrid = "INSERT INTO SMNTP_VENDOR_ID_REQUESTS (VENDOR_REQUEST_ID, TRADE_VENDOR_TYPE, FIRST_NAME, MIDDLE_INITIAL, LAST_NAME, DESIGNATION, REQUEST_TYPE, DATE_CREATED) ";
        $insert_vrid .= "VALUES(".$vendor_request_id.", '".$trade_vendor_type."', '".$first_name."', '".$middle_initial."', '".$last_name."',  '".$position."',  '".$request_type."', NOW())";
        $result_insert_vrid = $this->db->query($insert_vrid);
        /*$insert_vrid_log = "INSERT INTO SMNTP_VENDOR_REQUEST_HISTORY (VENDOR_INVITE_ID, DATE_CREATED, DATE_OF_REQUEST, REQUEST_TYPE, ID_TYPE, LAST_NAME, MIDDLE_INITIAL, FIRST_NAME, DESIGNATION, QUANTITY) ";
        $insert_vrid_log .= "VALUES(".$vendor_invite_id.", NOW(), '".$request_date."', '".$request_type."', 'ID', '".$last_name."', '".$middle_initial."', '".$first_name."', '".$position."', 1)";
        $result_insert_vrid_logs = $this->db->query($insert_vrid_log);*/

        /*$delete_vrid_temp = "DELETE FROM SMNTP_VENDOR_ID_REQUESTS_TEMP WHERE FIRST_NAME = '".$first_name."' AND LAST_NAME = '".$last_name."' AND DESIGNATION = '".$position."' AND VENDOR_REQUEST_ID = ".$vendor_request_id."";
        $result_delete_vrid_temp = $this->db->query($delete_vrid_temp);*/
        return $result_insert_vrid;
    }
	
	function insert_smvs($vendor_invite_id, $sm_system_id, $trade_type, $first_name, $middle_name, $last_name, $position, $email, $mobile_no, $user_id){
		
		$delete_smvs = "DELETE FROM SMNTP_VENDOR_SM_SYSTEMS WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." AND SM_SYSTEM_ID = ".$sm_system_id." AND TRADE_VENDOR_TYPE = ".$trade_type;
		$result_delete_smvs = $this->db->query($delete_smvs);

		$date_created = date('Y-m-d H:i:s');
		$insert_smvs = "INSERT INTO SMNTP_VENDOR_SM_SYSTEMS (VENDOR_INVITE_ID,SM_SYSTEM_ID,TRADE_VENDOR_TYPE,FIRST_NAME,MIDDLE_NAME,LAST_NAME,POSITION,EMAIL,MOBILE_NO,DATE_CREATED,CREATED_BY) ";
		$insert_smvs .= "VALUES(".$vendor_invite_id.",".$sm_system_id.",".$trade_type.",'".$first_name."','".$middle_name."','".$last_name."','".$position."','".$email."','".$mobile_no."','".$date_created."',".$user_id.")";
		$result_insert_smvs = $this->db->query($insert_smvs);
		return $insert_smvs;
	}
	
	function update_smvs($vendor_invite_id, $sm_system_id, $trade_type, $first_name, $middle_name, $last_name, $position, $email, $mobile_no, $user_id){
			
		/*
		$update_smvs = "UPDATE SMNTP_VENDOR_SM_SYSTEMS ";
		$update_smvs .= "SET FIRST_NAME = '".$first_name."'";
		$update_smvs .= ", MIDDLE_NAME = '".$middle_name."'";
		$update_smvs .= ", LAST_NAME = '".$last_name."'";
		$update_smvs .= ", POSITION = '".$position."'";
		$update_smvs .= ", EMAIL = '".$email."'";
		$update_smvs .= ", MOBILE_NO = '".$mobile_no."' ";
		$update_smvs .= "WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." AND SM_SYSTEM_ID = ".$sm_system_id." AND TRADE_VENDOR_TYPE = ".$trade_type;
		$result_update_smvs = $this->db->query($update_smvs);
		*/

		$delete_smvs = "DELETE FROM SMNTP_VENDOR_SM_SYSTEMS WHERE VENDOR_INVITE_ID = ".$vendor_invite_id." AND SM_SYSTEM_ID = ".$sm_system_id." AND TRADE_VENDOR_TYPE = ".$trade_type;
		$result_delete_smvs = $this->db->query($delete_smvs);

		$date_created = date('Y-m-d H:i:s');
		$insert_smvs = "INSERT INTO SMNTP_VENDOR_SM_SYSTEMS (VENDOR_INVITE_ID,SM_SYSTEM_ID,TRADE_VENDOR_TYPE,FIRST_NAME,MIDDLE_NAME,LAST_NAME,POSITION,EMAIL,MOBILE_NO,DATE_CREATED,CREATED_BY) ";
		$insert_smvs .= "VALUES(".$vendor_invite_id.",".$sm_system_id.",".$trade_type.",'".$first_name."','".$middle_name."','".$last_name."','".$position."','".$email."','".$mobile_no."','".$date_created."',".$user_id.")";
		$result_insert_smvs = $this->db->query($insert_smvs);
		return $result_insert_smvs;
	}
}
?>