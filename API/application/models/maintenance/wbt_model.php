<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Wbt_model extends CI_Model{

		function select_all_wbt(){
			//83 = WBT Screen ID
			$query = "SELECT SSD.SCREEN_ID AS CHILD_SCREEN_ID, SCREEN_NAME, 
							MENU_LABEL, 
							HREF_PATH AS LINK,
							(CURRENT_DATE -( UPLOAD_DATE - interval '2' hour)) AS DATE_SORTING_FORMAT,
							CAST(DATE_FORMAT(UPLOAD_DATE,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS WBT_DATE
						FROM SMNTP_SCREENS SS 
						INNER JOIN SMNTP_SCREEN_DEFN SSD 
						ON SSD.SCREEN_ID = SS.SCREEN_ID
						WHERE SSD.PARENT_ID = 2 AND SS.ACTIVE = 1 AND SS.OTHER_LINK = 1 ORDER BY SCREEN_NAME";

			$result = $this->db->query($query);
			//return $this->db->last_query();
			return $result->result_array();
			//return $query;
		}
		
		function select_wbt($search_text){
			$search_text = strtolower(trim($search_text));
			$query = "SELECT (CURRENT_DATE -( UPLOAD_DATE - interval '2' hour)) AS DATE_SORTING_FORMAT, SCREEN_ID, SCREEN_NAME, MENU_LABEL, HREF_PATH AS LINK, CAST(DATE_FORMAT(UPLOAD_DATE,'%m/%d/%y %h:%i:%s %p') AS CHAR) AS WBT_DATE FROM SMNTP_SCREENS ";
			$query.= "WHERE (LOWER(SCREEN_NAME) LIKE LOWER('%".$search_text."%') OR LOWER(MENU_LABEL) LIKE LOWER('%".$search_text."%') OR LOWER(HREF_PATH) LIKE LOWER('%".$search_text."%')) ";
			$query.= "AND ACTIVE = 1 AND OTHER_LINK = 1 ";
			$query.= "ORDER BY SCREEN_NAME ASC";

			$result = $this->db->query($query);
			//return $this->db->last_query();
			return $result->result_array();
			//return $query;
		}
		
		function get_all_video_url(){
			return $this->db->query('SELECT SCREEN_ID, SCREEN_NAME, MENU_LABEL,HREF_PATH FROM SMNTP_SCREENS WHERE ACTIVE = 1 AND OTHER_LINK = 1 ORDER BY SCREEN_NAME')->result_array();
		}
		
		function get_selected_video_url($id){
			return $this->db->query('SELECT VIDEO_SCREEN_IDS FROM SMNTP_EMAIL_DEFAULT_TEMPLATE WHERE ACTIVE = 1 AND TEMPLATE_ID = ?', array($id))->result_array();
		}
		
		function select_query($from,$where,$get)
		{
			$this->db->select($get);
			$this->db->from($from);
			$this->db->where($where);
			$result = $this->db->get();
			return $result->result_array();
		}
		function insert_wbt($screen_name, $menu_label, $link, $created_by){
			
			$query = 'INSERT INTO SMNTP_SCREENS(SCREEN_ID, SCREEN_NAME, MENU_LABEL, HREF_PATH, CREATED_BY, HAS_CHILD, OTHER_LINK) VALUES(?, ?, ? ,?, ?, 0, 1)';
			$next_screen_id = $this->get_next_screen_id();
			if( ! empty($next_screen_id)){
				$next_screen_id = $next_screen_id[0]['SCREEN_ID'] + 1;
			}
			$result = $this->db->query($query, array($next_screen_id, $screen_name, $menu_label, $link, $created_by));
			
			if($result){
				$data = array(
				   array(
					  'USER_POSITION_ID' => 10,
					  'SCREEN_ID' => $next_screen_id ,
					  'VENDOR_TYPE_ID' => 1,
					  'ACTIVE' => 1
				   ),array(
					  'USER_POSITION_ID' => 10,
					  'SCREEN_ID' => $next_screen_id ,
					  'VENDOR_TYPE_ID' => 2,
					  'ACTIVE' => 1
				   ),array(
					  'USER_POSITION_ID' => 10,
					  'SCREEN_ID' =>  $next_screen_id ,
					  'VENDOR_TYPE_ID' => 3,
					  'ACTIVE' => 1
				   ),array(
					  'USER_POSITION_ID' => 1,
					  'SCREEN_ID' =>  $next_screen_id ,
					  'VENDOR_TYPE_ID' => NULL,
					  'ACTIVE' => 1
				   )
				);

				$result = $this->db->insert_batch('SMNTP_SCREEN_POSITION_DEFN', $data);

				if($result){
					//2 = Registration - Parent ID
					
					//Get highest menu order
					$highest_menu_order = $this->db->query('SELECT MENU_ORDER FROM SMNTP_SCREEN_DEFN WHERE PARENT_ID = 2 ORDER BY MENU_ORDER DESC')->result_array();
					
					if( ! empty($highest_menu_order)){
						$highest_menu_order = $highest_menu_order[0]['MENU_ORDER'] + 1;
					}else{
						$highest_menu_order = 1;
					}
					
					$query = 'INSERT INTO SMNTP_SCREEN_DEFN (PARENT_ID, CHILD_ID, MENU_ORDER, MENU_LEVEL, ACTIVE, SCREEN_ID) 
VALUES (2, 0, ?, 2, 1, ?)';
					$result = $this->db->query($query, array($highest_menu_order, $next_screen_id));
				}
			}
			
			return $result;
			//return $query;
		}
		
		function get_next_screen_id(){
			return $this->db->query('SELECT SCREEN_ID FROM SMNTP_SCREENS ORDER BY SCREEN_ID DESC FETCH NEXT 1 ROWS ONLY')->result_array();
		}
		// CASE 
		
		function update_wbt($wbt_id, $screen_name, $menu_label, $link){
			$query = "UPDATE SMNTP_SCREENS SET SCREEN_NAME = ?, ";
			$query.= "MENU_LABEL = ?, ";
			$query.= "HREF_PATH = ? ";
			$query.= "WHERE SCREEN_ID = ?";
			
			$result = $this->db->query($query, array($screen_name, $menu_label, $link, $wbt_id));
			return $result;
			//return $query;
		}
		
		function deactivate_wbt($wbt_id){
			$query = "UPDATE SMNTP_SCREENS SET ACTIVE = 0 WHERE SCREEN_ID = ?";
			$result = $this->db->query($query, array($wbt_id));
			
			$query = "UPDATE SMNTP_SCREEN_POSITION_DEFN SET ACTIVE = 0 WHERE SCREEN_ID = ?";
			$result = $this->db->query($query, array($wbt_id));
			
			$query = "UPDATE SMNTP_SCREEN_DEFN SET ACTIVE = 0 WHERE SCREEN_ID = ?";
			$result = $this->db->query($query, array($wbt_id));
			return $result;
		}
	}
?>
