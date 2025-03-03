<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Tooltip_model extends CI_Model{

		function select_all_tooltip(){
			$query = "SELECT TID, SCREEN_NAME, ELEMENT_LABEL, TOOLTIP FROM SMNTP_VENDOR_TOOLTIPS ";
			$query.= "ORDER BY SCREEN_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function select_tooltip($search_text){
			$query = "SELECT TID, SCREEN_NAME, ELEMENT_LABEL, TOOLTIP FROM SMNTP_VENDOR_TOOLTIPS ";
			$query.= "WHERE LOWER(SCREEN_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(TOOLTIP) LIKE LOWER('%".$search_text."%')";
			$query.= "ORDER BY SCREEN_NAME ASC";

			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
		}
		
		function update_tooltip($tid, $screen_name, $tooltip){
			$submodule_checker = $this->db->query("SELECT ELEMENT_LABEL, SUBMODULE, SUBMODULE_NAME FROM SMNTP_VENDOR_TOOLTIPS WHERE TID = ".$tid)->result_array();
			if($submodule_checker[0]['SUBMODULE'] == 'Y'){
				if($submodule_checker[0]['SUBMODULE_NAME'] == 'SMNTP_SM_SYSTEMS'){
					$sm_system_id = str_replace("smvs_", "", $submodule_checker[0]['ELEMENT_LABEL']);
					$where = "SM_SYSTEM_ID";

				}else if($submodule_checker[0]['SUBMODULE_NAME'] == 'SMNTP_VP_REQUIRED_AGREEMENTS'){
					$sm_system_id = str_replace("ra_", "", $submodule_checker[0]['ELEMENT_LABEL']);
					$where = "REQUIRED_AGREEMENT_ID";

				}else if($submodule_checker[0]['SUBMODULE_NAME'] == 'SMNTP_VP_REQUIRED_DOCUMENTS'){
					$sm_system_id = str_replace("rsd_", "", $submodule_checker[0]['ELEMENT_LABEL']);
					$where = "REQUIRED_DOCUMENT_ID";

				}else if($submodule_checker[0]['SUBMODULE_NAME'] == 'SMNTP_VP_REQUIRED_DOCS_CCN'){
					$sm_system_id = str_replace("ccn_", "", $submodule_checker[0]['ELEMENT_LABEL']);
					$where = "REQUIRED_CCN_ID";
				}

				//$sm_system_id = str_replace("smvs_", "", $submodule_checker[0]['ELEMENT_LABEL']);
				$update_submodule  = "UPDATE ".$submodule_checker[0]['SUBMODULE_NAME']." ";
				$update_submodule .= "SET TOOL_TIP = '".$tooltip."' ";
				$update_submodule .= "WHERE ".$where." = ".$sm_system_id;
				$this->db->query($update_submodule);
			}

			$query = "UPDATE SMNTP_VENDOR_TOOLTIPS SET ";
			$query.= "TOOLTIP = '".$tooltip."' ";
			$query.= "WHERE TID = '".$tid."'";
			
			$result = $this->db->query($query);
			return $result;
			//return $query;
		}
		
	}
?>
