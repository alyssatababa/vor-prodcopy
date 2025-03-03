<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Dashboard_model extends CI_Model{
		
		function read($id, $vendor_type_id = null){
			$query = 'SELECT SPD.USER_POSITION_ID, S.SCREEN_ID, S.MENU_LABEL, S.HREF_PATH, S.HAS_CHILD, SD.PARENT_ID, SD.MENU_LEVEL, SD.MENU_ORDER, S.ACTION_ID, S.OTHER_LINK FROM SMNTP_SCREENS S 
					INNER JOIN SMNTP_SCREEN_DEFN SD ON S.SCREEN_ID = SD.SCREEN_ID
					INNER JOIN SMNTP_SCREEN_POSITION_DEFN SPD ON S.SCREEN_ID = SPD.SCREEN_ID';
			
			if($id!=null && $id!=''){
				$query.=' WHERE SPD.USER_POSITION_ID = '.$id;
				
				if(!empty($vendor_type_id) && $vendor_type_id != -1){
					$query.=' AND SPD.VENDOR_TYPE_ID = '.$vendor_type_id;
				}
			}else if(!empty($vendor_type_id) && $vendor_type_id != -1){
				
				if(!empty($vendor_type_id) && $vendor_type_id != -1){
					$query.=' WHERE SPD.VENDOR_TYPE_ID = '.$vendor_type_id;
				}
				
			}
			
			$query.=' AND S.ACTIVE = 1'; //Active menus only
			$query.=' AND SPD.ACTIVE = 1'; //Active menus only
			
			// ADD ACTIVE FILTER LATER
			$query.=' ORDER BY SD.MENU_LEVEL, SD.MENU_ORDER';
			
			$result = $this->db->query($query);
			return $result->result_array();
			//return $query;
			
		}	
		
		
		function insert_menu($menu_name, $description, $sorting, $menu_link){
			$ins = " INSERT INTO SMNTP_MENUS(menu_name";
			$val = " VALUES ('".$menu_name."'";
			
			if ($description!=null){
				$ins .= ",description";
				$val .= ",'".$description."'";
			}
			if ($sorting!=null){
				$ins .= ",sorting";
				$val .= ",".$sorting."";
			}
			if ($menu_link!=null){
				$ins .= ",menu_link";
				$val .= ",'".$menu_link."'";
			}
			
			$ins .= ")";
			$val .= ")";
			
			$this->db->query($ins.$val);
			
			return true;
			
		}
		
		function delete_menu($menu_id){
			$query = 'DELETE FROM SMNTP_MENUS';
			$query.=' where menu_id = '.$menu_id;
			$result = $this->db->query($query);
			return true;
		}	
		
	}
?>
