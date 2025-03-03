<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Menus_model extends CI_Model{
		
		function read($id){
			$query = 'select * from SMNTP_MENUS';
			if($id!=null && $id!=''){
				$query.=' where menu_id = '.$id;
			} 
			
			$query.=' order by sorting,menu_name';
			$result = $this->db->query($query);
			// echo $this->db->last_query();
			// $result = $this->db->get();
			// var_dump($result->result_array());
			return $result->result_array();
			
		}	
		
		function read_like_name($menu_name){
			$query = 'select * from SMNTP_MENUS';
			if($menu_name!=null && $menu_name!=''){
				$query.=" where upper(menu_name) like '%".strtoupper($menu_name)."%'";
			}
			
			$query.=' order by sorting,menu_name';
			$result = $this->db->query($query);
			return $result->result_array();
			
		}	
		
		function read_by_name($menu_name){
			$query = 'select * from SMNTP_MENUS';
			$query.=" where upper(menu_name) = '".strtoupper($menu_name)."'";
			$query.=' order by sorting,menu_name';
			$result = $this->db->query($query);
			return $result->result_array();
			
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
