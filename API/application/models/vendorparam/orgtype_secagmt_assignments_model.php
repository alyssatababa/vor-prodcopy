<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class Orgtype_secagmt_assignments_model extends CI_Model{

		function select_orgtype_secagmt_not_in($orgtype_id,$vendortype_id){
			$query = "SELECT REQUIRED_AGREEMENT_ID, REQUIRED_AGREEMENT_NAME FROM SMNTP_VP_REQUIRED_AGREEMENTS WHERE REQUIRED_AGREEMENT_ID NOT IN ( ";
			$query.= "SELECT AGREEMENT_ID FROM SMNTP_VP_REQUIRED_AGMT_DEFN ";
			$query.= "WHERE OWNERSHIP_ID = '".$orgtype_id."' AND ";
			if($vendortype_id == 0){
				$query.= "VENDOR_TYPE_ID IS NULL) AND ";
			}else{
				$query.= "VENDOR_TYPE_ID = '".$vendortype_id."') AND ";
			}
			$query.= " ACTIVE = '1' ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";
			
			$result = $this->db->query($query);
			return $result->result_array();

		}

		function select_orgtype_secagmt($orgtype_id,$vendortype_id){
			$query = "SELECT A.OWNERSHIP_ID, C.REQUIRED_AGREEMENT_ID, C.REQUIRED_AGREEMENT_NAME FROM SMNTP_VP_REQUIRED_AGMT_DEFN A ";
			$query.= "LEFT JOIN SMNTP_OWNERSHIP B ON A.OWNERSHIP_ID = B.OWNERSHIP_ID ";
			$query.= "LEFT JOIN SMNTP_VP_REQUIRED_AGREEMENTS C ON A.AGREEMENT_ID = C.REQUIRED_AGREEMENT_ID ";
			$query.= "WHERE B.OWNERSHIP_ID = '".$orgtype_id."' AND ";
			if($vendortype_id == 0){
				$query.= "VENDOR_TYPE_ID IS NULL AND ";
			}elseif($vendortype_id == 3){
				$query.= "VENDOR_TYPE_ID = '".$vendortype_id."' AND ";
			}else{
				$query.= "VENDOR_TYPE_ID = '".$vendortype_id."' AND ";
			}
			$query.= " A.ACTIVE = '1' ";
			$query.= "ORDER BY REQUIRED_AGREEMENT_NAME ASC";

			$result = $this->db->query($query);

			return $result->result_array();			
		}

		function save_agmt($items,$mtx){
			if(!isset($items) || !isset($mtx)){

				return 1;
			}

			$vtype = "";
/*
			if($mtx['type'] == 2){
				$vtype = null;
				$cat = $mtx['vtoc'];
			}else{
				$vtype = $mtx['vtoc'];
				$cat = null;
			}

			if($cat != null){

			$this->db->where(array('OWNERSHIP_ID' => $mtx['org']));
			if($cat == 0){
			$this->db->where(array('CATEGORY_ID' => null));
			$cat = null;
			}else{
			$this->db->where(array('CATEGORY_ID' => $cat));	
			}

			$this->db->delete('SMNTP_VP_REQUIRED_AGMT_DEFN');

			}else{

			$this->db->where(array('OWNERSHIP_ID' => $mtx['org']));
			$this->db->where(array('VENDOR_TYPE_ID' => $vtype));
			$this->db->delete('SMNTP_VP_REQUIRED_AGMT_DEFN');

			}*/

			//var_dump($mtx); exit();

			if($mtx['type'] == 1){
				if($mtx['vtoc'] == 0){
					$vtype = 1;
					$cat = null;
				}else{
					$vtype = 1;
					$cat = $mtx['vtoc'];
				}
			}elseif($mtx['type'] == 3){
				if($mtx['vtoc'] == 0){
					$vtype = 3;
					$cat = null;
				}else{
					$vtype = 3;
					$cat = $mtx['vtoc'];
				}
			}
			elseif($mtx['type'] == 2) {			
					$vtype = null;
			}
			else{
				$vtype = $mtx['vtoc'];
				$cat = null;

			}

			$this->db->where(array('OWNERSHIP_ID' 	=> $mtx['org']));
			$this->db->where(array('CATEGORY_ID' 	=> $cat));
			$this->db->where(array('VENDOR_TYPE_ID' => $vtype));
			$this->db->where(array('TRADE_VENDOR_TYPE_ID' => $mtx['tradevendortype']));
			$this->db->delete('SMNTP_VP_REQUIRED_AGMT_DEFN');

	
		
			foreach ($items as $key => $value) {
				$tmp = array(
					'OWNERSHIP_ID' =>$mtx['org'],
					'AGREEMENT_ID' => $value,
					'VENDOR_TYPE_ID' => $vtype,
					'CREATED_BY' => $mtx['user_id'],
					'ACTIVE' => 1,
					'TRADE_VENDOR_TYPE_ID' => $mtx['tradevendortype'],
					'CATEGORY_ID' => $cat
					);

				$res = $this->db->insert('SMNTP_VP_REQUIRED_AGMT_DEFN',$tmp);//insert muna isa isa
				//insert_batch is not working. need to update table to implement insert_batch function

				//return $this->db->last_query();
			}		
			return 0;
		}

		function select_category(){

			$rs = $this->db->select('*')->from('SMNTP_CATEGORY')->where(array('STATUS' => 1,'BUSINESS_TYPE' => 3))->order_by('CATEGORY_NAME')->get()->result_array();// all category
			return $rs;

		}

		function select_category_trade(){

			$rs = $this->db->select('*')->from('SMNTP_CATEGORY')->where(array('STATUS' => 1,'BUSINESS_TYPE' => 1))->order_by('CATEGORY_NAME')->get()->result_array();// all category
			return $rs;

		}

		function select_files($inf2 = null){		
			if(!isset($inf2) || $inf2 == null){
				return "Error!";
			}
			$info = explode("/", $inf2);
			$org = $info[0];
			$vtype = $info[1];
			$cat = $info[2];
			$trade = $info[3];
			$vs = '';


/*return $cat;*/
			if($vtype == 1){
				if($cat != "0"){
				$this->db->select('CATEGORY_ID');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN');
				$this->db->where(array('VENDOR_TYPE_ID' => 1));
				$this->db->where(array('OWNERSHIP_ID' => $org));
				$this->db->where(array('CATEGORY_ID' => $cat));
				$this->db->where(array('AGREEMENT_ID' => '-1')); //-1 pag wala laman
				//$this->db->limit(1);
				$res = $this->db->get()->result_array();	

				//var_dump($this->db->last_query()); return;		
				if(count($res) > 0){
					$this->db->select('REQUIRED_AGREEMENT_ID,REQUIRED_AGREEMENT_NAME');
					$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS');
					$this->db->where(array('ACTIVE'=> 1));
					$allRequired = $this->db->get()->result_array();
					return $allRequired;
				}
				}
			}

			if($vtype == 3){
				if($cat != "0"){
				$this->db->select('CATEGORY_ID');
				$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN');
				$this->db->where(array('VENDOR_TYPE_ID' => 3));
				$this->db->where(array('OWNERSHIP_ID' => $org));
				$this->db->where(array('CATEGORY_ID' => $cat));
				$this->db->where(array('AGREEMENT_ID' => '-1')); //-1 pag wala laman
				//$this->db->limit(1);
				$res = $this->db->get()->result_array();			
				if(count($res) > 0){
					$this->db->select('REQUIRED_AGREEMENT_ID,REQUIRED_AGREEMENT_NAME');
					$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS');
					$this->db->where(array('ACTIVE'=> 1));
					$allRequired = $this->db->get()->result_array();
					return $allRequired;
				}
				}
			}

			$this->db->select('C.REQUIRED_AGREEMENT_ID,A.CATEGORY_ID');
			$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
			$this->db->join('SMNTP_OWNERSHIP B','B.OWNERSHIP_ID = A.OWNERSHIP_ID','LEFT');
			$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS C','C.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
			$this->db->where(array('A.OWNERSHIP_ID' => $org));
			//$this->db->where('A.CATEGORY_ID' => $cat);


				if($vtype == 1){
					$this->db->where(array('A.TRADE_VENDOR_TYPE_ID' => $trade));
					$vs = $trade;
					if($cat == "0"){
						$this->db->where(array('A.CATEGORY_ID' => null));
					}else{
						$this->db->where(array('A.CATEGORY_ID'=> $cat));
					}			
				}

				//return $cat;

				if($vtype == 3){		
					$this->db->where(array('TRADE_VENDOR_TYPE_ID' => 0));
					$this->db->where(array('A.VENDOR_TYPE_ID' => 3));	
					if($cat == "0"){
							$this->db->where(array('A.CATEGORY_ID' => null));
					}else{
						$this->db->where(array('A.CATEGORY_ID'=> $cat));
					}			
					$vs  = null;
				}
				if($vtype == 2){
					$this->db->where(array('A.VENDOR_TYPE_ID' => null));
				}

			$this->db->where(array('A.ACTIVE'=> 1));
			$res = $this->db->get()->result_array();

			//return $cat;
			//return $this->db->last_query();

			//return count($res);
			//return $this->db->last_query();


				if(count($res) == 0){
					return $this->insert_nullcat($org,$cat,$inf2);
				}
			

			$dps = array();

			foreach ($res as $key => $value) {
				array_push($dps, $value['REQUIRED_AGREEMENT_ID']);
			}

			$this->db->select('REQUIRED_AGREEMENT_ID,REQUIRED_AGREEMENT_NAME');
			$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS');
			$this->db->where(array('ACTIVE'=> 1));
			$allRequired = $this->db->get()->result_array();

			$z = 0;
			for($z =0;$z < count($allRequired); $z++){
					$allRequired[$z]['VTYPE'] = $vs;
					if(in_array($allRequired[$z]['REQUIRED_AGREEMENT_ID'], $dps)){
					   $allRequired[$z]['CHECKED'] = 'checked';
					}
			}
			
			
			return $allRequired;
		}

		function insert_nullcat($org_id = null,$cat_id = null,$inf2 = null)
		{

		if(!isset($org_id) || $org_id == null && !isset($cat_id) || $cat_id == null ){
				return "0";
		}

		$this->db->select('AGREEMENT_ID');
		$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN');
		$this->db->where(array('OWNERSHIP_ID' => $org_id));
		$this->db->where(array('CATEGORY_ID' => null));
		$this->db->where(array('VENDOR_TYPE_ID' => null));
		$res = $this->db->get()->result_array();
		//return $inf2;
			$info = explode("/", $inf2);
			$org = $info[0];
			$vtype = $info[1];
			$cat =0;
			$trade = $info[3];
			$vs = '';
			
			
			

			$this->db->select('C.REQUIRED_AGREEMENT_ID');
			$this->db->from('SMNTP_VP_REQUIRED_AGMT_DEFN A');
			$this->db->join('SMNTP_OWNERSHIP B','B.OWNERSHIP_ID = A.OWNERSHIP_ID','LEFT');
			$this->db->join('SMNTP_VP_REQUIRED_AGREEMENTS C','C.REQUIRED_AGREEMENT_ID = A.AGREEMENT_ID','LEFT');
			$this->db->where(array('B.OWNERSHIP_ID' => $org));

				if($vtype == 1){
					$this->db->where(array('A.TRADE_VENDOR_TYPE_ID' => $trade));
					$vs = $trade;
					if($cat == "0"){
						$this->db->where(array('A.CATEGORY_ID' => null));
					}else{
						$this->db->where(array('A.CATEGORY_ID'=> $cat));
					}	
				}
				if($vtype == 3){		
					$this->db->where(array('VENDOR_TYPE_ID' => 3));
					if($cat == 0){
						$this->db->where(array('CATEGORY_ID'=> null));
					}else{
						$this->db->where(array('CATEGORY_ID'=> $cat));
					}
					
					$vs  = null;
				}

				if($vtype == 2){
					$this->db->where(array('VENDOR_TYPE_ID' => null));
				}

			$this->db->where(array('A.ACTIVE'=> 1));
			$res = $this->db->get()->result_array();

			//return $this->db->last_query();

			$dps = array();

			foreach ($res as $key => $value) {
				array_push($dps, $value['REQUIRED_AGREEMENT_ID']);
			}

			$this->db->select('REQUIRED_AGREEMENT_ID,REQUIRED_AGREEMENT_NAME');
			$this->db->from('SMNTP_VP_REQUIRED_AGREEMENTS');
			$this->db->where(array('ACTIVE'=> 1));
			$allRequired = $this->db->get()->result_array();

			//return $allRequired;


			$z = 0;
			for($z =0;$z < count($allRequired); $z++){
					$allRequired[$z]['VTYPE'] = $vs;
					if(in_array($allRequired[$z]['REQUIRED_AGREEMENT_ID'], $dps)){
					   $allRequired[$z]['CHECKED'] = 'checked';
					}
			}
				
			return $allRequired;
		}

		function restore_def($cat,$owner = null)
		{

			$this->db->where($cat);
			$this->db->where(array('VENDOR_TYPE_ID' => 3));
			if($owner['OWNERSHIP_ID'] != null){
			$this->db->where($owner);	
			}		
			$res = $this->db->delete('SMNTP_VP_REQUIRED_AGMT_DEFN');
			return $res;
		}

		function delete_def($cat,$owner)
		{


			$this->restore_def($cat,$owner);

			$drdef = array(
				'VENDOR_TYPE_ID' => 3,
				'AGREEMENT_ID' => '-1', //set to -1 para pag search mabilis
				'CATEGORY_ID' => $cat['CATEGORY_ID'],
				'OWNERSHIP_ID' => $owner['OWNERSHIP_ID'],
				'CREATED_BY' => null
				);

			$this->db->insert('SMNTP_VP_REQUIRED_AGMT_DEFN',$drdef);


			return $drdef;

		}





		// function save_screens($orgtype_id, $screens){
		// 	$this->db->query("DELETE SMNTP_SCREEN_POSITION_DEFN WHERE USER_ORGTYPE_ID = '".$orgtype_id."'");
		// 	for ($x=0; $x<count($screens); $x++ ){
		// 		$query = "INSERT INTO SMNTP_SCREEN_POSITION_DEFN (USER_ORGTYPE_ID, SCREEN_ID, ACTIVE) ";
		// 		$query.= "VALUES ('".$orgtype_id."','".$screens[$x]."','1') ";
				
		// 		$result = $this->db->query($query);
		// 	}
		// 	return $result;
		// }

	}
?>
