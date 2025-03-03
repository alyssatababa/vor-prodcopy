<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* 
*/
class Add_department_model extends CI_Model
{
	function save_add_vendor_department($var){
		
		$date_timestamp = date('Y-m-d H:i:s');
		
		
		$temp_records = array(
			'VENDOR_INVITE_ID' => $var['vendor_invite_id'],
			'VENDOR_ID' => $var['vendor_id'],
			'STATUS_ID' => 249 //Draft
		);
		
		if($var['position_id'] == 2){
			$header_records = array(
				'VENDOR_INVITE_ID' => $var['vendor_invite_id'],
				'VENDOR_ID' => $var['vendor_id'],
				'STATUS_ID' => 249, //Draft
				'DATE_CREATED' => $date_timestamp,
				'CREATED_BY' => $var['inviter'],
				'APPROVER_NOTE' => $var['approver_note'],
				'VENDOR_TYPE' => $var['vendor_type'],
				'MULTIPLE_VC' => $var['multiple_vc'],
				'MAIN_VT' => $var['main_vt']
			);
		}else{
			$header_records = array(
				'VENDOR_INVITE_ID' => $var['vendor_invite_id'],
				'VENDOR_ID' => $var['vendor_id'],
				'STATUS_ID' => 250, //For Approval Add Department
				'DATE_CREATED' => $date_timestamp,
				'CREATED_BY' => $var['inviter'],
				'APPROVER_NOTE' => $var['approver_note'],
				'VENDOR_TYPE' => $var['vendor_type'],
				'MULTIPLE_VC' => $var['multiple_vc'],
				'MAIN_VT' => $var['main_vt']
			);
		}
		
		$this->db->select('VENDOR_DEPT_ADH_ID');
		$this->db->from('SMNTP_VENDOR_ADH');
		$this->db->where(array_filter($temp_records));
		$this->db->order_by('VENDOR_DEPT_ADH_ID DESC')->limit(2);
		$query = $this->db->get();
		$ada_id = $query->row()->VENDOR_DEPT_ADH_ID;
		
		if($ada_id == '' || $ada_id == null){
			$this->db->insert('SMNTP_VENDOR_ADH', $header_records);
			
			$this->db->select('VENDOR_DEPT_ADH_ID');
			$this->db->from('SMNTP_VENDOR_ADH');
			$this->db->where(array_filter($header_records));
			$this->db->order_by('VENDOR_DEPT_ADH_ID DESC')->limit(2);
			$query = $this->db->get();
			$ada_id = $query->row()->VENDOR_DEPT_ADH_ID;
		}else{
			// DELETE DATA HERE
			$del_where = array('VENDOR_DEPT_ADH_ID' => $ada_id);
			$this->db->delete('SMNTP_VENDOR_ADB', $del_where);
			$this->db->delete('SMNTP_VENDOR_ADC', $del_where);
			$this->db->delete('SMNTP_VENDOR_ADSC', $del_where);
		}
		
		for($a=1; $a<=$var['brand_count']; $a++){
			$brand_name = $var['brand_'.$a];
			
			//Search If Brand is Existing
		
			$this->db->select('BRAND_ID');
			$this->db->from('SMNTP_BRAND');
			$this->db->where('BRAND_NAME', $brand_name);
			$query = $this->db->get();
			$brand_id = isset($query->row()->BRAND_ID) ? $query->row()->BRAND_ID : '';
			
			if($brand_id == ''){
				// Insert
				$save_brand = array(
								'BRAND_NAME' => $brand_name,
								'CREATED_BY' => $var['inviter'],
								'DATE_UPLOADED' => date('Y-m-d H:i:s'),
								'STATUS' => '1'
							);
							
				$this->db->insert('SMNTP_BRAND', $save_brand);
				
				// Select
		
				$this->db->select('BRAND_ID');
				$this->db->from('SMNTP_BRAND');
				$this->db->where('BRAND_NAME', $brand_name);
				$query = $this->db->get();
				$brand_id = $query->row()->BRAND_ID;
				
			}
				
			$brand_records = array(
				'VENDOR_DEPT_ADH_ID' => $ada_id,
				'BRAND_ID' => $brand_id,
				'DATE_CREATED' => $date_timestamp
			);	
			
			$this->db->insert('SMNTP_VENDOR_ADB', $brand_records);
		}
		
		for($b=1; $b<=$var['category_count']; $b++){
			$dept_id = $var['dept_'.$b];
			
			$dept_records = array(
				'VENDOR_DEPT_ADH_ID' => $ada_id,
				'CATEGORY_ID' => $dept_id,
				'DATE_CREATED' => $date_timestamp
			);
			
			$this->db->insert('SMNTP_VENDOR_ADC', $dept_records);
		}
		
		for($c=1; $c<=$var['sub_category_count']; $c++){
			$sub_dept_id = $var['sub_dept_'.$c];
			
			$sub_dept_records = array(
				'VENDOR_DEPT_ADH_ID' => $ada_id,
				'SUB_CATEGORY_ID' => $sub_dept_id,
				'DATE_CREATED' => $date_timestamp
			);
			
			$this->db->insert('SMNTP_VENDOR_ADSC', $sub_dept_records);
		}
		
		if($var['position_id'] == 2){
			$s_status_id = 249;
			$update_array = array(
								'STATUS_ID'		=> $s_status_id,
								'POSITION_ID'	=> $var['position_id'],
								'APPROVER_ID'	=> $var['inviter'],
								'DATE_UPDATED'		=> date('Y-m-d H:i:s')
								);	
		}else{
			$s_status_id = 250;
			$update_array = array(
								'STATUS_ID'		=> $s_status_id,
								'POSITION_ID'	=> $var['position_id'],
								'APPROVER_ID'	=> $var['inviter'],
								'DATE_UPDATED'		=> date('Y-m-d H:i:s')
								);
								
			$update_vendor_invite = array('CREATED_BY' => $var['inviter']);
			$this->db->where('VENDOR_INVITE_ID', $var['vendor_invite_id']);
			$this->db->update('SMNTP_VENDOR_INVITE', $update_vendor_invite);
		}

		$this->db->where('VENDOR_INVITE_ID', $var['vendor_invite_id']);

		$record_stat = array(
			'VENDOR_INVITE_ID'			=> $var['vendor_invite_id']
		);
		
		$this->db->update('SMNTP_VENDOR_STATUS', $update_array);
		
		$this->db->select('VENDOR_INVITE_STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where(array_filter($record_stat));
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <=

		$query = $this->db->get();

		$vendor_invite_status_id = $query->row()->VENDOR_INVITE_STATUS_ID;
		
		$add_vendor_status_logs = array(
				'VENDOR_INVITE_STATUS_ID'	=> $vendor_invite_status_id,
				'VENDOR_INVITE_ID'	=> $var['vendor_invite_id'],
				'STATUS_ID'			=> $s_status_id,
				'POSITION_ID'		=> $var['position_id'],
				'APPROVER_ID'		=> $var['inviter'],
				'DATE_UPDATED'		=> date('Y-m-d H:i:s'),
				'ACTIVE'			=> 1,
			);
			
		$this->db->insert('SMNTP_VENDOR_STATUS_LOGS', $add_vendor_status_logs);		
		
		$dt = DateTime::createFromFormat("d/m/Y h:i:s A", $var['date_submitted']);

		$file_record = array(
			'VENDOR_INVITE_ID' => $var['vendor_invite_id'],
			'ORIGINAL_FILE_NAME' => $var['approved_items'],
			'ACTIVE' => 1,
			'FILE_PATH' => $var['file_location'],
			'DATE_CREATED' => $dt->format('Y-m-d H:i:s'),
			'VENDOR_DEPT_ADH_ID' => $ada_id
		);

		$this->db->insert('SMNTP_VENDOR_AD_AI', $file_record);

		// second file
		
		if($var['approved_items2'] != NULL){
			$file_record = array(
			'VENDOR_INVITE_ID' => $var['vendor_invite_id'],
			'ORIGINAL_FILE_NAME' => $var['approved_items2'],
			'ACTIVE' => 1,
			'FILE_PATH' => $var['file_location2'],
			'DATE_CREATED' => $dt->format('Y-m-d H:i:s'),
			'VENDOR_DEPT_ADH_ID' => $ada_id
			);

			$this->db->insert('SMNTP_VENDOR_AD_AI', $file_record);
		}
		
		
		return $ada_id;
	}
	
	function get_vendor_info($var){
		// Vendor Name, Vendor Type, Vendor Code
		$this->db->select('VENDOR_INVITE_ID, VENDOR_NAME, VENDOR_TYPE, TRADE_VENDOR_TYPE, VENDOR_CODE, VENDOR_ID');
		$this->db->from('SMNTP_VENDOR');
		$this->db->where('VENDOR_INVITE_ID', $var['invite_id']);
		$query = $this->db->get();
		
		$data['vendor_name'] = $query->row()->VENDOR_NAME;
		//$data['vendor_type'] = $query->row()->VENDOR_TYPE;
		//$data['vendor_code'] = $query->row()->VENDOR_CODE;
		$data['vendor_id'] = $query->row()->VENDOR_ID;
		$data['trade_vendor_type'] = $query->row()->TRADE_VENDOR_TYPE;
		
		// Invite Creator
		$this->db->select('CREATED_BY');
		$this->db->from('SMNTP_VENDOR_INVITE');
		$this->db->where('VENDOR_INVITE_ID', $var['invite_id']);
		$get_creator = $this->db->get();
		
		$data['CREATED_BY'] = $get_creator->row()->CREATED_BY;
		
		// Get Status ID
		$this->db->select('STATUS_ID');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where('VENDOR_INVITE_ID', $var['invite_id']);
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <= 
		$query_two = $this->db->get();
		$data['status'] = $query_two->row()->STATUS_ID;
		
		if($data['status'] == 19){
			$data['status'] = 256;
		}
		
		//if(COUNT($query->row()->CATEGORY_ID) > 0){
		//	$data['category_count'] = COUNT($query->row()->CATEGORY_ID);
		//	$data['category_id'] = $query->row()->CATEGORY_ID;
		//	$data['category_name'] = $query->row()->CATEGORY_NAME;
		//}
		
		// Get Vendor ADH ID
		$this->db->select('SVADH.VENDOR_DEPT_ADH_ID, SVADH.APPROVER_NOTE, SVADH.VENDOR_TYPE, SVADH.MAIN_VT, SV.VENDOR_CODE, SV.VENDOR_CODE_02');
		$this->db->from('SMNTP_VENDOR_ADH SVADH');
		$this->db->join('SMNTP_VENDOR SV','SV.VENDOR_INVITE_ID = SVADH.VENDOR_INVITE_ID');
		$this->db->where('SVADH.VENDOR_INVITE_ID', $var['invite_id']);
		$this->db->where('SVADH.STATUS_ID', $data['status']);
		$query = $this->db->get();
		
		$data['vendor_type'] = $query->row()->VENDOR_TYPE;
		$data['main_vt'] = $query->row()->MAIN_VT;
		
		if($data['main_vt'] != $data['vendor_type']){
			$data['vendor_code'] = $query->row()->VENDOR_CODE_02;
			
			// Existing Category ID, Category Name
			$this->db->distinct();
			$this->db->select('SVC.CATEGORY_ID, SC.CATEGORY_NAME');
			$this->db->from('SMNTP_VENDOR_AVC_CAT SVC');
			$this->db->join('SMNTP_CATEGORY SC','SVC.CATEGORY_ID = SC.CATEGORY_ID');
			$this->db->where('SVC.VENDOR_INVITE_ID', $var['invite_id']);
			$query2 = $this->db->get();
			
		}else{
			$data['vendor_code'] = $query->row()->VENDOR_CODE;
		
			// Existing Category ID, Category Name
			$this->db->distinct();
			$this->db->select('SVC.CATEGORY_ID, SC.CATEGORY_NAME');
			$this->db->from('SMNTP_VENDOR_CATEGORIES SVC');
			$this->db->join('SMNTP_CATEGORY SC','SVC.CATEGORY_ID = SC.CATEGORY_ID');
			$this->db->where('SVC.VENDOR_INVITE_ID', $var['invite_id']);
			$query2 = $this->db->get();
		}
		
		$data['category'] = $query2->result_array();
		$data['category_count'] = $query2->num_rows();
		
		$vendor_dept_adh_id = $query->row()->VENDOR_DEPT_ADH_ID;
		$data['add_dept_header_id'] = $vendor_dept_adh_id;
		$data['approvers_note'] = $query->row()->APPROVER_NOTE;
		
		
		// Add Category ID and Category Name
		$this->db->select('ADC.CATEGORY_ID, SC.CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_ADC ADC');
		$this->db->join('SMNTP_CATEGORY SC','ADC.CATEGORY_ID = SC.CATEGORY_ID');
		$this->db->where('VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$query = $this->db->get();
		$data['add_category'] = $query->result_array();
		$data['add_category_count'] = $query->num_rows();
		
		// Add Sub Category ID and Sub Category Name
		$this->db->select('ADSC.SUB_CATEGORY_ID, SSC.SUB_CATEGORY_NAME');
		$this->db->from('SMNTP_VENDOR_ADSC ADSC');
		$this->db->join('SMNTP_SUB_CATEGORY SSC','ADSC.SUB_CATEGORY_ID = SSC.SUB_CATEGORY_ID');
		$this->db->where('VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$query = $this->db->get();
		$data['add_sub_category'] = $query->result_array();
		$data['add_sub_category_count'] = $query->num_rows();
		
		// Add Branch
		$this->db->select('ADB.BRAND_ID, SB.BRAND_NAME');
		$this->db->from('SMNTP_VENDOR_ADB ADB');
		$this->db->join('SMNTP_BRAND SB','ADB.BRAND_ID = SB.BRAND_ID');
		$this->db->where('VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$query = $this->db->get();
		$data['brand'] = $query->result_array();
		$data['brand_count'] = $query->num_rows();
		
		return $data;
	}
	
	function get_vendor_status($vendor_invite_id){
		$this->db->select('VENDOR_INVITE_STATUS_ID, STATUS_ID, POSITION_ID, APPROVER_ID, APPROVER_REMARKS, DATE_UPDATED,TERMSPAYMENT');
		$this->db->from('SMNTP_VENDOR_STATUS');
		$this->db->where('VENDOR_INVITE_ID', $vendor_invite_id);
		$this->db->order_by('VENDOR_INVITE_STATUS_ID DESC')->limit(2); // 2 because they use < not <= 
		
		$query = $this->db->get();
		
		return $query->row();
	}
	
	function update_dsdb($vendor_dept_adh_id){
		// Get All Category to be inserted
		$date_timestamp = date('Y-m-d H:i:s');
		$this->db->select('ADH.VENDOR_INVITE_ID, ADC.CATEGORY_ID, ADH.VENDOR_TYPE, ADH.MULTIPLE_VC, ADH.MAIN_VT');
		$this->db->from('SMNTP_VENDOR_ADC ADC');
		$this->db->join('SMNTP_VENDOR_ADH ADH', 'ADC.VENDOR_DEPT_ADH_ID = ADH.VENDOR_DEPT_ADH_ID');
		$this->db->where('ADH.VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$first_query = $this->db->get();
		
		for($a=0; $a<$first_query->num_rows(); $a++){
			$vendor_type = $first_query->result_array()[$a]['VENDOR_TYPE'];
			$multiple_vc = $first_query->result_array()[$a]['MULTIPLE_VC'];
			$main_vt = $first_query->result_array()[$a]['MAIN_VT'];

			if($multiple_vc == 'Y'){
				if($vendor_type != $main_vt){
					$cat_table = "SMNTP_VENDOR_AVC_CAT";	
				}else{
					$cat_table = "SMNTP_VENDOR_CATEGORIES";	
				}
			}else{
				$cat_table = "SMNTP_VENDOR_CATEGORIES";
			}

			$this->db->select('CATEGORY_ID');
			$this->db->from($cat_table);
			$this->db->where('CATEGORY_ID',$first_query->result_array()[$a]['CATEGORY_ID']);
			$this->db->where('VENDOR_INVITE_ID',$first_query->result_array()[$a]['VENDOR_INVITE_ID']);
			
			$cat_checker_query = $this->db->get();
			if($cat_checker_query->num_rows() == 0){				
				$categories = array(
					'VENDOR_INVITE_ID' => $first_query->result_array()[$a]['VENDOR_INVITE_ID'],
					'CATEGORY_ID' => $first_query->result_array()[$a]['CATEGORY_ID'],
					'DATE_CREATED' => $date_timestamp,
					'ACTIVE' => 1
				);	
				
				$this->db->insert($cat_table, $categories);
			}
		}
		
		// Get All Sub Categories to be inserted
		$this->db->select('ADH.VENDOR_INVITE_ID, ADSC.SUB_CATEGORY_ID, SSC.CATEGORY_ID, ADH.VENDOR_TYPE, ADH.MULTIPLE_VC, ADH.MAIN_VT');
		$this->db->from('SMNTP_VENDOR_ADSC ADSC');
		$this->db->join('SMNTP_VENDOR_ADH ADH', 'ADSC.VENDOR_DEPT_ADH_ID = ADH.VENDOR_DEPT_ADH_ID');
		$this->db->join('SMNTP_SUB_CATEGORY SSC', 'ADSC.SUB_CATEGORY_ID = SSC.SUB_CATEGORY_ID');
		$this->db->where('ADH.VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$second_query = $this->db->get();
		
		for($b=0; $b<$second_query->num_rows(); $b++){
			$vendor_type = $second_query->result_array()[$b]['VENDOR_TYPE'];
			$multiple_vc = $second_query->result_array()[$b]['MULTIPLE_VC'];
			$main_vt = $second_query->result_array()[$b]['MAIN_VT'];

			if($multiple_vc == 'Y'){
				if($vendor_type != $main_vt){
					$cat_table = "SMNTP_VENDOR_AVC_SUB_CAT";	
				}else{
					$cat_table = "SMNTP_VENDOR_SUB_CATEGORIES";	
				}
			}else{
				$cat_table = "SMNTP_VENDOR_SUB_CATEGORIES";
			}

			$this->db->select('SUB_CATEGORY_ID');
			$this->db->from($cat_table);
			$this->db->where('SUB_CATEGORY_ID',$second_query->result_array()[$b]['SUB_CATEGORY_ID']);
			$this->db->where('CATEGORY_ID',$second_query->result_array()[$b]['CATEGORY_ID']);
			$this->db->where('VENDOR_INVITE_ID',$second_query->result_array()[$b]['VENDOR_INVITE_ID']);
			$sub_cat_checker_query = $this->db->get();
			
			if($sub_cat_checker_query->num_rows() == 0){
				$sub_categories = array(
					'VENDOR_INVITE_ID' => $second_query->result_array()[$b]['VENDOR_INVITE_ID'],
					'CATEGORY_ID' => $second_query->result_array()[$b]['CATEGORY_ID'],
					'SUB_CATEGORY_ID' => $second_query->result_array()[$b]['SUB_CATEGORY_ID'],
					'DATE_CREATED' => $date_timestamp,
					'ACTIVE' => 1
				);	
				
				$this->db->insert($cat_table, $sub_categories);
			}
		}
		
		// Get All Brand to be inserted
		$this->db->select('ADH.VENDOR_INVITE_ID, ADH.VENDOR_ID, ADB.BRAND_ID');
		$this->db->from('SMNTP_VENDOR_ADB ADB');
		$this->db->join('SMNTP_VENDOR_ADH ADH', 'ADB.VENDOR_DEPT_ADH_ID = ADH.VENDOR_DEPT_ADH_ID');
		$this->db->where('ADH.VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id);
		$third_query = $this->db->get();
		
		for($c=0; $c<$third_query->num_rows(); $c++){
			$this->db->select('BRAND_ID');
			$this->db->from('SMNTP_VENDOR_BRAND');
			$this->db->where('BRAND_ID',$third_query->result_array()[$c]['BRAND_ID']);
			$this->db->where('VENDOR_ID',$third_query->result_array()[$c]['VENDOR_ID']);
			$brand_checker_query = $this->db->get();
			if($brand_checker_query->num_rows() == 0){
				$brand = array(
					'VENDOR_ID' => $third_query->result_array()[$c]['VENDOR_ID'],
					'BRAND_ID' => $third_query->result_array()[$c]['BRAND_ID'],
					'DATE_CREATED' => $date_timestamp
				);	
				
				$this->db->insert('SMNTP_VENDOR_BRAND', $brand);
			}
		}
		
		return 1;
	}
	
	function get_dept_list($vendor_dept_adh_id){
		$this->db->select('DESCRIPTION');
		$this->db->where('SVA.VENDOR_DEPT_ADH_ID', $vendor_dept_adh_id); // its like active
		$this->db->from('SMNTP_VENDOR_ADC SVA');
		$this->db->join('SMNTP_CATEGORY SC','SVA.CATEGORY_ID = SC.CATEGORY_ID');
		$this->db->DISTINCT();

		$result = $this->db->get();

		return $result->result_array();
	}
}
?>