<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Invitecreation_model extends CI_Model
{
	function get_msg_template($user_id)
	{
		// $query = $this->db->query('select * from SMNTP_VENDOR_INVITE_TEMPLATE');
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_INVITE_TEMPLATE');
		$this->db->where('USER_ID', $user_id);
		$this->db->where('VEN_INV_STATUS', 1); // active records only

		$query = $this->db->get();

		return $query->result_array();
	}

	// Added MSF - 20191118 (IJR-10618)
	function get_sub_category($category_id){		
		return $this->db->query('SELECT * FROM SMNTP_SUB_CATEGORY WHERE STATUS = 1 AND CATEGORY_ID IN ('.$category_id.')')->result_array();
	}

	function save_invitecreate($data)
	{
		$etimestamp = date('Y-m-d H:i:s');

		$email_template = $this->get_email_template();
		$email_temp_id = $email_template->row()->TEMPLATE_ID;
		
		if($data['invite_type'] != 5){

			$record = array(
					'VENDOR_NAME' 		=> $data['vendorname'],
					'CONTACT_PERSON' 	=> $data['contact_person'],
					'EMAIL' 			=> $data['email'],
					'TEMPLATE_ID' 		=> $data['msg_template'],
					'MESSAGE' 			=> $data['template_msg'],
					'APPROVER_NOTE' 	=> $data['approver_note'],
					'DATE_CREATED' 		=> $etimestamp,
					'CREATED_BY' 		=> $data['user_id'],
					'ACTIVE' 			=> 1, // default always 1
					'EMAIL_TEMPLATE_ID' => $email_temp_id,
					'TRADE_VENDOR_TYPE' => $data['tv_type'],
					'CC_VENDOR_CODE' => $data['source_invite_id'],
					'REGISTRATION_TYPE' => $data['invite_type']
				);
			
		}else{

			$record = array(
					'VENDOR_NAME' 		=> $data['new_vendorname'],
					'CONTACT_PERSON' 	=> $data['contact_person'],
					'EMAIL' 			=> $data['email'],
					'TEMPLATE_ID' 		=> 0,
					'MESSAGE' 			=> $data['template_msg'],
					'APPROVER_NOTE' 	=> $data['approver_note'],
					'DATE_CREATED' 		=> $etimestamp,
					'CREATED_BY' 		=> $data['user_id'],
					'ACTIVE' 			=> 1, // default always 1
					'EMAIL_TEMPLATE_ID' => $email_temp_id,
					'TRADE_VENDOR_TYPE' => $data['tv_type'],
					'CC_VENDOR_CODE' => $data['source_invite_id'],
					'REGISTRATION_TYPE' => $data['invite_type']
				);
			
		}

		if ($data['status'] == 2) // ni submit na 
			$record['BUSINESS_TYPE'] = $data['business_type'];

		$this->db->insert('SMNTP_VENDOR_INVITE', $record);

		// since $this->db->insert_id(); is not working select and use $record as where
		$this->db->select('VENDOR_INVITE_ID');
		$this->db->from('SMNTP_VENDOR_INVITE');
		$this->db->where(array_filter($record));
		$this->db->order_by('VENDOR_INVITE_ID DESC')->limit(2); // 2 because they use < not <= 

		$query = $this->db->get();

		$invite_id = $query->row()->VENDOR_INVITE_ID;
		
		// Added MSF - 20191108 (IJR-10617)
		if($data['date_submitted'] != null){
			$file_record = array(
				'VENDOR_INVITE_ID' => $invite_id,
				'ORIGINAL_FILE_NAME' => $data['approved_items'],
				'ACTIVE' => 1,
				'FILE_PATH' => $data['file_location'],
				'DATE_CREATED' => $data['date_submitted'],
			);

			$this->db->insert('SMNTP_VENDOR_APPROVED_ITEMS', $file_record);
		}
		

		$record2 = array(
				'VENDOR_INVITE_ID'	=> $invite_id,
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['position_id'],
				'DATE_UPDATED'		=> $etimestamp,
				'TERMSPAYMENT'		=> $data['cbo_tp'],
				'ACTIVE'			=> 1,
			);

		$this->db->insert('SMNTP_VENDOR_STATUS', $record2);
		
		//LOGS FOR APPROVAL HISTORY
		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record2));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <= 
		
		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;
		
		$record3 = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $invite_id,
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['position_id'],
				'APPROVER_ID'		=> $data['user_id'],
				'APPROVER_REMARKS'	=> $data['approver_note'],
				'DATE_UPDATED'		=> $etimestamp,
				'ACTIVE'			=> 1,
			);
		
		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $record3);
		
		if ($data['cat_count'] > 0)
		{
			$cat_batch = array();
			for ($i=1; $i <= $data['cat_count']; $i++)
			{ 
				$cat_batch[] = array(
								'VENDOR_INVITE_ID' 	=> $invite_id,						
								'CATEGORY_ID' 		=> $data['category_id'.$i],
								'ACTIVE'			=> '1',
								'DATE_CREATED'		=> $etimestamp
							);
			}

			$this->db->insert_batch('SMNTP_VENDOR_CATEGORIES', $cat_batch);
		}
		
		// Added MSF - 20191108 (IJR-10617)
		if ($data['sub_cat_count'] > 0)
		{
			for ($x=1; $x <= $data['sub_cat_count']; $x++)
			{ 
				$record4 = array(
					'VENDOR_INVITE_ID' => $invite_id,
					'SUB_CATEGORY_ID' => $data['sub_category_id'.$x],
					'CATEGORY_ID' => $data['sub_category_source'.$x],
					'ACTIVE'			=> '1',
					'DATE_CREATED'		=> $etimestamp
				);

				$this->db->insert('SMNTP_VENDOR_SUB_CATEGORIES', $record4);
			}
		}

		// $record3 = array(
		// 		'VENDOR_INVITE_ID'	=> $invite_id,
		// 		'VENDOR_NAME'		=> $data['vendorname'],
		// 	);

		// $this->db->insert('SMNTP_VENDOR', $record3);

		return $invite_id;
	}

	function get_vendor_type($inviteid){
		return $this->db->query('SELECT BUSINESS_TYPE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID = ?', array($inviteid))->result_array();
	}
	
	// Added MSF - 20191108 (IJR-10617)
	function get_vendor_approved_items($inviteid){
		return $this->db->query('SELECT VENDOR_APPROVE_ITEMS_ID, ORIGINAL_FILE_NAME, ACTIVE, FILE_PATH, CAST(DATE_FORMAT(DATE_CREATED,"%Y-%m-%d %H:%i:%s") AS CHAR) AS DATE_CREATED  FROM SMNTP_VENDOR_APPROVED_ITEMS WHERE VENDOR_INVITE_ID = ?',array($inviteid))->result_array();
	}
	
	function get_avc_vendor_approved_items($inviteid){
		return $this->db->query('SELECT VENDOR_APPROVE_ITEMS_ID, ORIGINAL_FILE_NAME, ACTIVE, FILE_PATH, CAST(DATE_FORMAT(DATE_CREATED,"%Y-%m-%d %H:%i:%s") AS CHAR) AS DATE_CREATED  FROM SMNTP_VENDOR_AVC_AI WHERE VENDOR_INVITE_ID = ?',array($inviteid))->result_array();
	}
	
	function get_ad_vendor_approved_items($inviteid){
		return $this->db->query('SELECT ADAI.VENDOR_APPROVE_ITEMS_ID,ADAI. ORIGINAL_FILE_NAME, ADAI.ACTIVE, ADAI.FILE_PATH, ADAI.DATE_CREATED FROM SMNTP_VENDOR_ADH ADH
								JOIN SMNTP_VENDOR_AD_AI ADAI ON ADH.VENDOR_DEPT_ADH_ID = ADAI.VENDOR_DEPT_ADH_ID
								JOIN SMNTP_VENDOR_STATUS SVS ON ADH.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID AND ADH.STATUS_ID = SVS.STATUS_ID
								WHERE ADH.VENDOR_INVITE_ID = ?',array($inviteid))->result_array();
	}
	
	function get_all_ad_vendor_approved_items($inviteid){
		return $this->db->query('SELECT ADAI.VENDOR_APPROVE_ITEMS_ID,ADAI. ORIGINAL_FILE_NAME, ADAI.ACTIVE, ADAI.FILE_PATH, CAST(DATE_FORMAT(ADAI.DATE_CREATED,"%Y-%m-%d %H:%i:%s") AS CHAR), ADH.VENDOR_TYPE, ADH.MULTIPLE_VC, ADH.MAIN_VT
								FROM SMNTP_VENDOR_ADH ADH
								JOIN SMNTP_VENDOR_AD_AI ADAI ON ADH.VENDOR_DEPT_ADH_ID = ADAI.VENDOR_DEPT_ADH_ID
								JOIN SMNTP_VENDOR_STATUS SVS ON ADH.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID
								WHERE 1=1
								-- AND ADH.STATUS_ID = 256 
								AND ADH.`STATUS_ID` = CASE SVS.`STATUS_ID` WHEN 19 THEN 256 ELSE SVS.`STATUS_ID` END
								AND ADH.VENDOR_INVITE_ID = ?',array($inviteid))->result_array();
	}
	
	function get_invite_record($data)
	{
		$this->db->select('
						SVI.VENDOR_NAME,
						CASE WHEN SVI.CC_VENDOR_CODE IS NULL THEN NULL ELSE SVITT.VENDOR_NAME END as OLD_VENDOR_NAME, 
						SVI.CONTACT_PERSON,
						SVI.EMAIL,
						SVI.TEMPLATE_ID,
						SVI.MESSAGE,
						SVI.APPROVER_NOTE,
						SVI.REASON_FOR_EXTENSION,
						SVIT.VEN_INV_MESSAGE,
						SVS.STATUS_ID,
						SEDT.CONTENT,
						SVI.TRADE_VENDOR_TYPE,
						SVI.REGISTRATION_TYPE,
						SVI.CC_VENDOR_CODE,
						SV.VENDOR_CODE,
						SV_TWO.VENDOR_CODE AS ORIG_VENDOR_CODE
					',FALSE);
		$this->db->from('SMNTP_VENDOR_INVITE SVI');
		$this->db->join('SMNTP_VENDOR_INVITE_TEMPLATE SVIT', 'SVI.TEMPLATE_ID = SVIT.VEN_INV_ID', 'LEFT');
		$this->db->join('SMNTP_EMAIL_DEFAULT_TEMPLATE SEDT', 'SVI.EMAIL_TEMPLATE_ID = SEDT.TEMPLATE_ID');
		$this->db->join('SMNTP_VENDOR_STATUS SVS', 'SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_INVITE SVITT', 'SVI.CC_VENDOR_CODE = SVITT.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR SV', 'SVITT.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR SV_TWO', 'SVI.VENDOR_INVITE_ID = SV_TWO.VENDOR_INVITE_ID', 'LEFT');
		$this->db->where($data);

		$query = $this->db->get();
		// echo $this->db->last_query();

		$var['resultscount'] = $query->num_rows();
		$var['query'] =	$query->result_array();

		return $var;
	}

	function get_invite_categories($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_CATEGORIES SVC');
		$this->db->join('SMNTP_CATEGORY SC', 'SC.CATEGORY_ID = SVC.CATEGORY_ID');
		$this->db->where($data);
		$this->db->order_by('SC.CATEGORY_NAME');

		$query = $this->db->get();

		return $query;
	}

	function get_invite_all_category($data)
	{
		$this->db->distinct();
		$this->db->select('SCat.CATEGORY_ID, SCat.CATEGORY_NAME, SSCat.SUB_CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_CATEGORIES SVC');
		$this->db->join('SMNTP_CATEGORY SCat', 'SVC.CATEGORY_ID = SCat.CATEGORY_ID');
		$this->db->join('SMNTP_VENDOR_SUB_CATEGORIES SVBC', 'SVC.CATEGORY_ID = SVBC.CATEGORY_ID AND SVBC.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSCat', 'SVBC.SUB_CATEGORY_ID = SSCat.SUB_CATEGORY_ID', 'LEFT');
		$this->db->where($data);
		//$this->db->order_by('SCat.CATEGORY_NAME');
		$this->db->order_by('(CASE WHEN SVBC.DATE_CREATED IS NULL THEN SVC.DATE_CREATED ELSE SVBC.DATE_CREATED END)');
		$query = $this->db->get();

		return $query;
	}
	
	function get_invite_allavc($data){


		$this->db->distinct();
		$this->db->select('SVC.CATEGORY_ID, SCat.CATEGORY_NAME, SSCat.SUB_CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_AVC_CAT SVC');
		$this->db->join('SMNTP_CATEGORY SCat', 'SVC.CATEGORY_ID = SCat.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_AVC_SUB_CAT SVBC', 'SVC.CATEGORY_ID = SVBC.CATEGORY_ID AND SVBC.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSCat', 'SVBC.SUB_CATEGORY_ID = SSCat.SUB_CATEGORY_ID', 'LEFT');
		$this->db->where($data);
		//$this->db->order_by('SCat.CATEGORY_NAME');
		$this->db->order_by('(CASE WHEN SVBC.DATE_CREATED IS NULL THEN SVC.DATE_CREATED ELSE SVBC.DATE_CREATED END)');
		$query2 = $this->db->get();

		return $query2;
	}
	
	function get_invite_avc_categories($data){
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_AVC_CAT SVC');
		$this->db->join('SMNTP_CATEGORY SC', 'SC.CATEGORY_ID = SVC.CATEGORY_ID');
		$this->db->where($data);
		$this->db->order_by('SC.CATEGORY_NAME');

		$query = $this->db->get();

		return $query;
	}

	// Added MSF - 20191108 (IJR-10617)
	function get_invite_sub_categories($data){
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_SUB_CATEGORIES SVSC');
		$this->db->join('SMNTP_SUB_CATEGORY SBC', 'SVSC.SUB_CATEGORY_ID = SBC.SUB_CATEGORY_ID');
		$this->db->where($data);
		$this->db->order_by('SBC.SUB_CATEGORY_NAME');

		$query = $this->db->get();

		return $query;
	}

	// Added MSF - 20191108 (IJR-10617)
	function get_invite_avc_sub_categories($data){
		$this->db->select('*');
		$this->db->from('SMNTP_VENDOR_AVC_SUB_CAT SVSC');
		$this->db->join('SMNTP_SUB_CATEGORY SBC', 'SVSC.SUB_CATEGORY_ID = SBC.SUB_CATEGORY_ID');
		$this->db->where($data);
		$this->db->order_by('SBC.SUB_CATEGORY_NAME');

		$query = $this->db->get();

		return $query;
	}

	function update_invitecreate($data)
	{
		$etimestamp = date('Y-m-d H:i:s');
		
		if($data['invite_type'] != 4){
			$email_template = $this->get_email_template();
		}else{
			$email_template = $this->get_avc_email_template();
		}
		$email_temp_id = $email_template->row()->TEMPLATE_ID;
		
		$date_timestamp = date('Y-m-d H:i:s');



		if($data['invite_type'] != 5){
			$record = array(
					'VENDOR_NAME' 		=> $data['vendorname'],
					'CONTACT_PERSON' 	=> $data['contact_person'],
					'EMAIL' 			=> $data['email'],
					'TEMPLATE_ID' 		=> $data['msg_template'],
					'MESSAGE' 			=> $data['template_msg'],
					'APPROVER_NOTE' 	=> $data['approver_note'],
					'CREATED_BY' 		=> $data['user_id'],
					'EMAIL_TEMPLATE_ID' => $email_temp_id,
					'REGISTRATION_TYPE' => $data['invite_type'],
					'TRADE_VENDOR_TYPE' => $data['tv_type']
				);
		}else{
			$record = array(
					'VENDOR_NAME' 		=> $data['new_vendorname'],
					'CONTACT_PERSON' 	=> $data['contact_person'],
					'EMAIL' 			=> $data['email'],
					'TEMPLATE_ID' 		=> $data['msg_template'],
					'MESSAGE' 			=> $data['template_msg'],
					'APPROVER_NOTE' 	=> $data['approver_note'],
					'CREATED_BY' 		=> $data['user_id'],
					'EMAIL_TEMPLATE_ID' => $email_temp_id,
					'REGISTRATION_TYPE' => $data['invite_type'],
					'TRADE_VENDOR_TYPE' => $data['tv_type']
				);
		}

		if ($data['status'] == 2) // ni submit na 
			$record['BUSINESS_TYPE'] = $data['business_type'];

		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);
		
		
		if( ! empty($data['original_status']) && $data['original_status'] == 5){
			$this->db->update('SMNTP_VENDOR_INVITE', array('REASON_FOR_EXTENSION' => $data['reason_for_extension']));
		}else{
			$this->db->update('SMNTP_VENDOR_INVITE', $record);
		}
		
		// Added MSF - 20191108 (IJR-10617)
		if($data['invite_type'] != 4){
			$del_where = array('VENDOR_INVITE_ID'=> $data['invite_id']);
			$this->db->delete('SMNTP_VENDOR_APPROVED_ITEMS', $del_where);
			
			if($data['date_submitted'] != null){
				//$date_created = DateTime::createFromFormat('m/d/Y h:i:s A', $data['date_submitted']);
				//$date_created = $date_created->format("Y-m-d H:i:s");

				$file_record = array(
					'VENDOR_INVITE_ID' => $data['invite_id'],
					'ORIGINAL_FILE_NAME' => $data['approved_items'],
					'ACTIVE' => 1,
					'FILE_PATH' => $data['file_location'],
					'DATE_CREATED' => $data['date_submitted'],
				);

				$this->db->insert('SMNTP_VENDOR_APPROVED_ITEMS', $file_record);
			}
			
			$record2 = array(
					'STATUS_ID'			=> $data['status'],
					'POSITION_ID'		=> $data['position_id'],
					'DATE_UPDATED'		=> $etimestamp
				);
				
		}else{
			$del_where = array('VENDOR_INVITE_ID'=> $data['invite_id']);
			$this->db->delete('SMNTP_VENDOR_AVC_AI', $del_where);
			
			if($data['date_submitted'] != null){
				//$date_created = DateTime::createFromFormat('m/d/Y h:i:s A', $data['date_submitted']);
				//$date_created = $date_created->format("Y-m-d H:i:s");

				$file_record = array(
					'VENDOR_INVITE_ID' => $data['invite_id'],
					'ORIGINAL_FILE_NAME' => $data['approved_items'],
					'ACTIVE' => 1,
					'FILE_PATH' => $data['file_location'],
					'DATE_CREATED' => $data['date_submitted'],
				);

				$this->db->insert('SMNTP_VENDOR_AVC_AI', $file_record);
			}
			
			$record2 = array(
					'STATUS_ID'			=> $data['status'],
					'POSITION_ID'		=> $data['position_id'],
					'DATE_UPDATED'		=> $etimestamp,
					'AVC_TERMSPAYMENT'		=> $data['cbo_tp']
				);
		}

		$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);
		$this->db->update('SMNTP_VENDOR_STATUS', $record2);

		$affected_rows = $this->db->affected_rows();
	
		//LOGS FOR APPROVAL HISTORY
		$record_stat = array(
				'VENDOR_INVITE_ID'			=> $data['invite_id'],
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['position_id'],
				'DATE_UPDATED'		=> $etimestamp
			);
			
		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record_stat));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <= 
		
		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;
		
		$record3 = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $data['invite_id'],
				'STATUS_ID'			=> $data['status'],
				'POSITION_ID'		=> $data['position_id'],
				'APPROVER_ID'		=> $data['user_id'],
				'APPROVER_REMARKS'	=> $data['approver_note'],
				'DATE_UPDATED'		=> $date_timestamp,
				'ACTIVE'			=> 1,
			);
		
		if( ! empty($data['original_status']) && $data['original_status'] == 5){
			$record3['APPROVER_REMARKS'] = $data['reason_for_extension'];
		}
		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $record3);

		
		if(empty($data['original_status']) || @$data['original_status'] != 5){
			$record3 = array(
					'VENDOR_NAME'		=> $data['vendorname'],
				);

			$this->db->where('VENDOR_INVITE_ID', $data['invite_id']);
			$this->db->update('SMNTP_VENDOR', $record3);

			// delete first before insert new
			if ($data['cat_count'] > 0)
			{
				$del_where = array('VENDOR_INVITE_ID'=> $data['invite_id']);
				if($data['invite_type'] != 4){
					$this->db->delete('SMNTP_VENDOR_CATEGORIES', $del_where);
				}else{
					$this->db->delete('SMNTP_VENDOR_AVC_CAT', $del_where);
				}

				$cat_batch = array();
				for ($i=1; $i <= $data['cat_count']; $i++)
				{ 
					$cat_batch[] = array(
									'VENDOR_INVITE_ID' 	=> $data['invite_id'],						
									'CATEGORY_ID' 		=> $data['category_id'.$i]
								);
				}
				if($data['invite_type'] != 4){
					$this->db->insert_batch('SMNTP_VENDOR_CATEGORIES', $cat_batch);
				}else{
					$this->db->insert_batch('SMNTP_VENDOR_AVC_CAT', $cat_batch);
				}
			}
		
			// Added MSF - 20191108 (IJR-10617)
			$del_where = array('VENDOR_INVITE_ID'=> $data['invite_id']);
			
			if($data['invite_type'] != 4){
				$this->db->delete('SMNTP_VENDOR_SUB_CATEGORIES', $del_where);
			}else{
				$this->db->delete('SMNTP_VENDOR_AVC_SUB_CAT', $del_where);
			}
			
			if ($data['sub_cat_count'] > 0)
			{
				for ($x=1; $x <= $data['sub_cat_count']; $x++){
					$record4 = array(
						'VENDOR_INVITE_ID' => $data['invite_id'],
						'SUB_CATEGORY_ID' => $data['sub_category_idz'.$x],
						'CATEGORY_ID' => $data['sub_category_source'.$x]
					);
					if($data['invite_type'] != 4){
						$this->db->insert('SMNTP_VENDOR_SUB_CATEGORIES', $record4);
					}else{
						$this->db->insert('SMNTP_VENDOR_AVC_SUB_CAT', $record4);
					}
				}
			}
		}

		return $affected_rows;
	}

	function get_category_list($data)
	{
		$this->db->select('*');
		$this->db->from('SMNTP_CATEGORY SC');
		$this->db->join('SMNTP_USER_CATEGORY SUC', 'SC.CATEGORY_ID = SUC.CATEGORY_ID');

		//if($data['position_id'] != 11){
			$this->db->where('SC.BUSINESS_TYPE', $data['business_type']); //Jay tingal ko nag eerror sa NTS
		//}
		$this->db->where('SUC.USER_ID', $data['user_id']);

		if ($data['category_name'] != '' || $data['category_name'] != null)
			$this->db->where("upper(SC.CATEGORY_NAME) like upper('%" . $data['category_name'] . "%')");

		if ($data['use_not_in']) // php empty is not working i dont know why
			$this->db->where_not_in('SC.CATEGORY_ID', $data['selected_catid']);
		
		
		$this->db->where('SC.STATUS', 1);
		$this->db->order_by('SC.CATEGORY_NAME');
		$result = $this->db->get();
		//echo $this->db->last_query();
		return $result->result_array();
	}

	function get_email_template()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_EMAIL_DEFAULT_TEMPLATE');
		$this->db->where('TEMPLATE_TYPE', 1); // for registration
		$this->db->where('ACTIVE', 1);
		
		$result = $this->db->get();
		
		return $result;
	}

	function get_avc_email_template()
	{
		$this->db->select('*');
		$this->db->from('SMNTP_EMAIL_DEFAULT_TEMPLATE');
		$this->db->where('TEMPLATE_TYPE', 70); // for registration
		$this->db->where('ACTIVE', 1);
		
		$result = $this->db->get();
		
		return $result;
	}
	
	function get_vendor_info($vendor_name)
	{		
		$vendor_name = str_replace("'","\'",$vendor_name);
		$this->db->select('SVI.TRADE_VENDOR_TYPE, 
						SVI.VENDOR_NAME, 
						SVI.CONTACT_PERSON,
						SVI.EMAIL, 
						SVAI.ORIGINAL_FILE_NAME, 
						SVAI.FILE_PATH, 
						SCA.CATEGORY_NAME,
						SSCA.SUB_CATEGORY_NAME, 
						SVS.TERMSPAYMENT, 
						SVS.AVC_TERMSPAYMENT, 
						SVAI.DATE_CREATED, 
						SV.VENDOR_ID, 
						SV.VENDOR_CODE, 
						SV.VENDOR_TYPE, 
						SVI.VENDOR_INVITE_ID, 
						SV.VENDOR_CODE_02,
						SCA_TWO.CATEGORY_NAME AS AVC_CATEGORY_NAME, 
						SSCA_TWO.SUB_CATEGORY_NAME AS AVC_SUB_CATEGORY_NAME,
						SVI.REGISTRATION_TYPE, 
						SVI.PREV_REGISTRATION_TYPE, 
						NOW() AS CUR_DATE, 
						DATEDIFF(CONVERT_TZ(CURRENT_DATE(), "+00:00", "+08:00"), MAX(SVSL.DATE_UPDATED)) AS DATE_DIFF, 
						NOW() + INTERVAL 1 DAY AS TOM_DATE, 
						SVSL.DATE_UPDATED');
		//$this->db->select("TO_CHAR(SVAI.DATE_CREATED,'m/%d/%Y') AS DATE_CREATED");
		$this->db->_protect_identifiers=false;
		$this->db->from('SMNTP_VENDOR_INVITE SVI');
		$this->db->join('SMNTP_VENDOR SV', 'SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_STATUS SVS', 'SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_STATUS_LOGS SVSL', 'SVI.VENDOR_INVITE_ID = SVSL.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_APPROVED_ITEMS SVAI', 'SVI.VENDOR_INVITE_ID = SVAI.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_CATEGORIES SVC', 'SVI.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_CATEGORY SCA', 'SVC.CATEGORY_ID = SCA.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_SUB_CATEGORIES SVSC', 'SVI.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SVSC.CATEGORY_ID = SCA.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSCA', 'SVSC.SUB_CATEGORY_ID = SSCA.SUB_CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_AVC_CAT AVC_SVC', 'SVI.VENDOR_INVITE_ID = AVC_SVC.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_CATEGORY SCA_TWO', 'AVC_SVC.CATEGORY_ID = SCA_TWO.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_AVC_SUB_CAT AVC_SVSC', 'SVI.VENDOR_INVITE_ID = AVC_SVSC.VENDOR_INVITE_ID  AND AVC_SVSC.CATEGORY_ID = AVC_SVC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSCA_TWO', 'AVC_SVSC.SUB_CATEGORY_ID = SSCA_TWO.SUB_CATEGORY_ID', 'LEFT');
		$this->db->where('SVS.STATUS_ID', '19');
		$this->db->where("SVI.VENDOR_NAME = '".$vendor_name."'", NULL, FALSE);
		
		
		//$this->db->query("SELECT SVI.TRADE_VENDOR_TYPE, SVI.VENDOR_NAME, SVI.CONTACT_PERSON, SVI.EMAIL, SVAI.ORIGINAL_FILE_NAME, SVAI.FILE_PATH, SCA.CATEGORY_NAME, SSCA.SUB_CATEGORY_NAME, SVS.TERMSPAYMENT, SVAI.DATE_CREATED, SV.VENDOR_ID, SV.VENDOR_CODE, SV.VENDOR_TYPE, SVI.VENDOR_INVITE_ID FROM (SMNTP_VENDOR_INVITE SVI) JOIN SMNTP_VENDOR SV ON SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID JOIN SMNTP_VENDOR_STATUS SVS ON SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID LEFT JOIN SMNTP_VENDOR_APPROVED_ITEMS SVAI ON SVI.VENDOR_INVITE_ID = SVAI.VENDOR_INVITE_ID LEFT JOIN SMNTP_VENDOR_CATEGORIES SVC ON SVI.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID LEFT JOIN SMNTP_CATEGORY SCA ON SVC.CATEGORY_ID = SCA.CATEGORY_ID LEFT JOIN SMNTP_VENDOR_SUB_CATEGORIES SVSC ON SVI.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID LEFT JOIN SMNTP_SUB_CATEGORY SSCA ON SVSC.SUB_CATEGORY_ID = SSCA.SUB_CATEGORY_ID WHERE SVS.STATUS_ID =  19 AND SVI.VENDOR_NAME = ".$this->db->escape($vendor_name)."'")->result_array();
		
		$query = $this->db->get();

		return $query->result_array();
	}
	
	function get_vendor_info_dept($invite_id){
		$this->db->select('SVI.TRADE_VENDOR_TYPE, SVI.VENDOR_NAME, SVI.CONTACT_PERSON,
							SVI.EMAIL, SVAI.ORIGINAL_FILE_NAME, SVAI.FILE_PATH, SCA.CATEGORY_NAME,
							SSCA.SUB_CATEGORY_NAME, SVS.TERMSPAYMENT, SVAI.DATE_CREATED, SV.VENDOR_ID, SV.VENDOR_CODE, SV.VENDOR_TYPE, SVI.VENDOR_INVITE_ID');
		//$this->db->select("TO_CHAR(SVAI.DATE_CREATED,'m/%d/%Y') AS DATE_CREATED");
		$this->db->from('SMNTP_VENDOR_INVITE SVI');
		$this->db->join('SMNTP_VENDOR SV', 'SVI.VENDOR_INVITE_ID = SV.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_STATUS SVS', 'SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
		$this->db->join('SMNTP_VENDOR_APPROVED_ITEMS SVAI', 'SVI.VENDOR_INVITE_ID = SVAI.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_CATEGORIES SVC', 'SVI.VENDOR_INVITE_ID = SVC.VENDOR_INVITE_ID', 'LEFT');
		$this->db->join('SMNTP_CATEGORY SCA', 'SVC.CATEGORY_ID = SCA.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_SUB_CATEGORIES SVSC', 'SVI.VENDOR_INVITE_ID = SVSC.VENDOR_INVITE_ID AND SVC.CATEGORY_ID = SVSC.CATEGORY_ID', 'LEFT');
		$this->db->join('SMNTP_SUB_CATEGORY SSCA', 'SVSC.SUB_CATEGORY_ID = SSCA.SUB_CATEGORY_ID', 'LEFT');
		
		$this->db->where('SVS.STATUS_ID', '249');
		$this->db->where('SVI.VENDOR_INVITE_ID', $invite_id);

		$query = $this->db->get();

		return $query->result_array();
	}
	
	function get_vendor_draft_dept($invite_id){
		
		$this->db->select('SB.BRAND_ID, SB.BRAND_NAME, SC.CATEGORY_ID, SC.CATEGORY_NAME, SSC.SUB_CATEGORY_ID, SSC.SUB_CATEGORY_NAME, SVADH.APPROVER_NOTE');
		$this->db->from('SMNTP_VENDOR_ADH SVADH');
		$this->db->join('SMNTP_VENDOR_ADB SVADB', 'SVADH.VENDOR_DEPT_ADH_ID = SVADB.VENDOR_DEPT_ADH_ID', 'LEFT');
		$this->db->join('SMNTP_BRAND SB', 'SVADB.BRAND_ID = SB.BRAND_ID', 'LEFT');
		$this->db->join('SMNTP_VENDOR_ADC SVADC', 'SVADH.VENDOR_DEPT_ADH_ID = SVADC.VENDOR_DEPT_ADH_ID');
		$this->db->join('SMNTP_CATEGORY SC', 'SVADC.CATEGORY_ID = SC.CATEGORY_ID');
		$this->db->join('SMNTP_SUB_CATEGORY SSC', 'SVADC.CATEGORY_ID = SSC.CATEGORY_ID');
		$this->db->join('SMNTP_VENDOR_ADSC SVADSC', 'SVADH.VENDOR_DEPT_ADH_ID = SVADSC.VENDOR_DEPT_ADH_ID AND SSC.SUB_CATEGORY_ID = SVADSC.SUB_CATEGORY_ID');
		$this->db->where('SVADH.STATUS_ID', '249');
		$this->db->where('SVADH.VENDOR_INVITE_ID', $invite_id);
		
		$query = $this->db->get();
		
		return $query->result_array();
	}
}
?>