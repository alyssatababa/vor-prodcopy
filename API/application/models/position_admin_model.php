<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class position_admin_model extends CI_Model{
		
		function save_position($data)
		{
		
			$res = $this->db->insert('SMNTP_POSITION',$data);			
			return $res;
			
		}
		

		function search_position($data)
		{
			$l = $data['s_type'];
			$s_item = $data['s_item'];

			$ss_item = substr($s_item, 0,1);
			$sss_item = substr($s_item, 1);		

			$ss_item = strtoupper($ss_item) ."". $sss_item;

			$dum  = array(
			'POSITION_NAME' =>  $s_item,
			'POSITION_CODE' =>  $s_item
						);

			$dum2  = array(
			'POSITION_NAME' =>  strtoupper(	$s_item),
			'POSITION_CODE' =>  strtoupper(	$s_item)
						);

					

			switch($l) {
				case 2:
				$l = 'POSITION_CODE';
				break;
				case 3:
				$l = 'POSITION_NAME';
				break;
				case 1:
				$l = 1;
				break;
				default:break;
			}
			
				
				if($l == 1){			
				$this->db->select('POSITION_NAME,POSITION_ID,POSITION_CODE');
				$this->db->from('SMNTP_POSITION');
				$this->db->where("ACTIVE = '1' AND (POSITION_NAME LIKE'%".$s_item."%' OR POSITION_CODE LIKE '%".$s_item."%' OR POSITION_NAME LIKE'%".strtoupper($s_item)."%' OR POSITION_CODE LIKE '%".strtoupper($s_item)."%' OR POSITION_CODE LIKE '%".$ss_item."%' OR POSITION_NAME LIKE '%".$ss_item."%')" );	
				//$this->db->or_like($dum,$dum2);
				$res = $this->db->get();											
				return $res->result_array();		
				}
				
				$this->db->select('POSITION_NAME,POSITION_ID,POSITION_CODE');
				$this->db->from('SMNTP_POSITION');	
				$this->db->where("ACTIVE = '1' AND (".$l." LIKE '%".$s_item."%' OR ".$l." LIKE '%".strtoupper($s_item)."%' OR ".$l." LIKE '%".$ss_item."%')" );		
				//$this->db->where("ACTIVEs = 1 AND (".$l." LIKE '%".$s_item."%' OR ".$l."' LIKE '%".strtoupper($s_item)."%')");	
				// $this->db->or_like(array($l => strtoupper($s_item)),array($l => $s_item));
				$res = $this->db->get();
				return $res->result_array();
				
			}
			
		
			function s_editpos($data,$data2)
			{
			$this->db->where($data2);
			$res = $this->db->update('SMNTP_POSITION',$data);		
			return $res;
			}


			function s_delpos($data2)
			{

				$data = array(
						'ACTIVE' => 0
					);

			$this->db->where($data2);
			$res = $this->db->update('SMNTP_POSITION',$data);		
			return $res;

			}
			
		
		
		
		
		
	}
	?>