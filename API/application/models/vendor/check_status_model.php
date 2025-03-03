<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Check_status_model extends CI_Model
{
	function select_inprogress_vendor()
	{
		$this->db->select('SVS.VENDOR_INVITE_ID,SVS.STATUS_ID,SVS.PRIMARY_START_DATE,SVS.EXTEND_FLAG,SVS.VENDOR_INVITE_STATUS_ID,SVS.POSITION_ID,SVI.REGISTRATION_TYPE');
		$this->db->from('SMNTP_VENDOR_STATUS SVS');
		$this->db->join('SMNTP_VENDOR_INVITE SVI','SVI.VENDOR_INVITE_ID = SVS.VENDOR_INVITE_ID');
		$this->db->where('SVI.REGISTRATION_TYPE = 1', NULL, FALSE);
		$this->db->where('(SVS.STATUS_ID = 9', NULL, FALSE);
		$this->db->or_where('SVS.STATUS_ID = 8', NULL, FALSE);
		$this->db->or_where('SVS.STATUS_ID = 11)', NULL, FALSE);
		//$this->db->or_where(array('STATUS_ID'=>'192'));
		//$res = $this->db->get('SMNTP_VENDOR_STATUS');
		$res = $this->db->get();
		//return $last_query = $this->db->last_query();
		return $res->result_array();
	}

	function select_number_of_days()
	{
		$this->db->select('CONFIG_VALUE,SYSTEM_CONFIG_ID,CONFIG_NAME');
		$this->db->where(array('CONFIG_NAME' => 'primary_requirement_extension'));
		$this->db->or_where(array('CONFIG_NAME' => 'primary_requirement_deactivate'));
		$res = $this->db->get('SMNTP_SYSTEM_CONFIG');

		return $res->result_array();
	}

	function update_query($table,$record,$where)
	{
		$this->db->where($where);
		$this->db->update($table,$record);

	}
	function select_query_or($table,$where,$orwhere,$looking)
	{
		$this->db->select($looking);
		$this->db->where($where);
		$this->db->or_where($orwhere);
		$res = $this->db->get($table);

		return $res->result_array();
	}
	function select_query($table,$where,$looking)
	{
		$this->db->select($looking);
		$this->db->where($where);
		$res = $this->db->get($table);
		return $res->result_array();
	}
	function insert_query($table,$rec)
	{
		$this->db->insert($table,$rec);

	}

	function deactivate_creadentials()
	{
		$this->db->select();

	}

	function select_foradditional_vendor()
	{
		$this->db->select('VENDOR_INVITE_ID,STATUS_ID,ADDITIONAL_START_DATE,POSITION_ID,VENDOR_INVITE_STATUS_ID');
		$this->db->where(array('STATUS_ID'=>'190'));
		$this->db->or_where(array('STATUS_ID'=>'195'));
		$res = $this->db->get('SMNTP_VENDOR_STATUS');
		return $res->result_array();
	}

	function select_additional_days()
	{
		$this->db->select('CONFIG_VALUE,SYSTEM_CONFIG_ID,CONFIG_NAME');
		$this->db->where(array('CONFIG_NAME' => 'additional_requirement_deactivate'));
		$res = $this->db->get('SMNTP_SYSTEM_CONFIG');

		return $res->result_array();
	}

	function select_vendor_details($vendor_invite_id){
		$this->db->select('VENDOR_NAME,EMAIL,USER_ID');
		$this->db->where(array('VENDOR_INVITE_ID' => $vendor_invite_id));
		$res = $this->db->get('SMNTP_VENDOR_INVITE');

		return $res->result_array();
	}
}
?>
