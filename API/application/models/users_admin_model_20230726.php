<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
	class users_admin_model extends CI_Model{

		function m_create_user($data_var,$data2,$approver)
		{


			$sql = "INSERT INTO SMNTP_USERS(USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,USER_MOBILE,USER_STATUS,POSITION_ID,USER_EMAIL) VALUES('".$data_var['USER_FIRST_NAME']."','".$data_var['USER_MIDDLE_NAME']."','".$data_var['USER_LAST_NAME']."','".$data_var['USER_TYPE_ID']."','".$data_var['USER_MOBILE']."','".$data_var['USER_STATUS']."','".$data_var['POSITION_ID']."','".$data_var['USER_EMAIL']."')";
			$res = $this->db->query($sql);

			if(!$res){return;}

			$data = $this->m_select_last();
			$values = array(
			'USER_ID' => $data[0]['USER_ID'],
			'USERNAME' => $data2['l_id'],
			'PASSWORD' => $data2['l_pass']
			);

			$rests = $this->db->insert('SMNTP_CREDENTIALS',$values);

			if($approver['a_type'] == 2){
				$a_ids = explode("/",$approver['id']);
				$matrix = array(
					'USER_ID' => $data[0]['USER_ID'],
					'VRDHEAD_ID' => $a_ids[0],
					'VRDSTAFF_ID' => $a_ids[1],
					'BUHEAD_ID' =>$a_ids[2]
					);
				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$matrix);
			}

			if($approver['a_type'] == 7){
				$a_ids = explode("/",$approver['id']);

				$matrix = array(
					'USER_ID' => $data[0]['USER_ID'],
					'VRDHEAD_ID' => $a_ids[0],
					'VRDSTAFF_ID' => $a_ids[1],
					'GHEAD_ID' =>$a_ids[2],
					'FASHEAD_ID' =>$a_ids[3]
					);
				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$matrix);
			}

			return $rests;

		}

		function m_insert_logs($data)
		{


			//return var_dump($data);


			/*$sql = "INSERT INTO SMNTP_USERS_LOGS(USER_ID,USER_LOG_TYPE,INSERTED_BY) values('".$data['USER_ID']."','".$data['uType']."','".$data['insby']."')";
			$res = $this->db->query($sql);
			if(!$res){
			return 0;
			}
			return 1;
			*/


		}

		function get_vrdstaff()
		{

			$res = $this->db->select('CONFIG_VALUE')->from('SMNTP_SYSTEM_CONFIG')->where_in('CONFIG_NAME',array('max_vrdstaff','max_vrdhead'))->get()->result_array();

			return $res;

		}

		function m_search_data($data){

			//case sensitive

			$this->load->helper('date');
			$z  = $data['u_type'];
			$this->db->select('*');
			$this->db->from('SMNTP_USER_DETAILS');
			
		if($z == 1){
				 $s_data = array(
						'USERNAME' => $data['u_id'],
						'USER_FIRST_NAME' => $data['u_name'],
						'USER_MIDDLE_NAME' => $data['u_name'],
						'USER_LAST_NAME' => $data['u_name']
					);
				 $this->db->or_like($s_data);

		}


		if($z == 2){
				$s_data = array(
						'USERNAME' => $data['u_id']
					);

				$this->db->like($s_data);
				//$sql = $this->db->get();


		}
		if($z == 3){
				$s_data = array(
						'USER_FIRST_NAME' => $data['u_name'],
						'USER_MIDDLE_NAME' => $data['u_name'],
						'USER_LAST_NAME' => $data['u_name']
					);

				$this->db->or_like($s_data);

		}


			$this->db->limit(1000);
			$sql = $this->db->get();
			$s_table = $sql->result_array();


			return $s_table;



		}

		function m_get_user_type()
		{
			$this->db->select('USER_TYPE_ID,USER_TYPE');
			$this->db->from('SMNTP_USER_TYPES');
			$this->db->where(array('USER_TYPE_ID' => 1));
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;


		}



		function m_get_position($sID)
		{

/*			$data  = array(
			'USER_TYPE_ID' => $sID
			);
			$this->db->select('POSITION_ID,POSITION_NAME');
			$this->db->from('SMNTP_POSITION');
			$this->db->where($data);
			$sql = $this->db->get();
			$result = $sql->result_array();*/

			$result = $this->db->select('POSITION_ID,POSITION_NAME,USER_TYPE_ID')->from('SMNTP_POSITION')->where(array('ACTIVE' => 1))->order_by('POSITION_ID','asc')->get()->result_array();

			return $result;


		}

		function m_get_info($sID)
		{

			$data  = array(
			'USER_ID' => $sID
			);
			$this->db->select('PASSWORD');
			$this->db->from('SMNTP_CREDENTIALS');
			$this->db->where($data);
			$sql = $this->db->get();
			$result = $sql->result_array();
			return $result;


		}

		function m_update_user($data,$data2,$data3)
		{


			$dum = array(
				'USER_ID' => $data3
				);

			$this->db->select('count(USER_ID)');
			$this->db->from('SMNTP_USERS_MATRIX');
			$this->db->where();
			$res = $this->db->get();
			return $res->result_array();



			$this->db->where('USER_ID', $data3);
			$this->db->update('SMNTP_USERS', $data);
			$res = $this->m_update_user_cbf($data2,$data3);
			return $res;


		}
		function m_update_user_cbf($data2,$data3)
		{
			$this->db->where('USER_ID', $data3);
			$res = $this->db->update('SMNTP_CREDENTIALS', $data2);
			return $res;
		}

		function m_checkuser($data)
		{

				$this->db->where($data);
				$this->db->select('USER_ID');
				$sql = $this->db->get('SMNTP_CREDENTIALS');




				return $sql->result_array();

		}

		function m_del_user($d)
		{
			$data = array(
				'USER_ID' => $d
				);

			$data2 = array(
				'USER_STATUS' => 0
				);


			$this->db->where($data);
			$this->db->select('VENDOR_INVITE_ID');
			$sql = $this->db->get('SMNTP_VENDOR_INVITE')->result_array();

			if(count($sql) > 0){
				$vendor_invite_id = $sql[0]['VENDOR_INVITE_ID'];

				//Insert To User_Logs
				$insert_user_logs = "INSERT INTO SMNTP_USERS_SYS_LOGS(USER_ID,USER_FIRST_NAME, USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,POSITION_ID,USER_STATUS, USER_DATE_CREATED, USER_MOBILE, USER_EMAIL,VENDOR_ID, HEAD_ID)
										SELECT * FROM SMNTP_USERS WHERE USER_ID =".$d;
				$this->db->query($insert_user_logs);

				//Insert To Vendor Logs
				$insert_vendor_invite_logs = "INSERT INTO SMNTP_VENDOR_INVITE_SYS_LOGS(VENDOR_INVITE_ID, VENDOR_NAME, CONTACT_PERSON, EMAIL, TEMPLATE_ID, MESSAGE, APPROVER_NOTE, DATE_CREATED, CREATED_BY, ACTIVE, USER_ID, EMAIL_TEMPLATE_ID, BUSINESS_TYPE, TRADE_VENDOR_TYPE, REASON_FOR_EXTENSION, REMOVE_DATE)
												SELECT SVI.VENDOR_INVITE_ID,SVI.VENDOR_NAME,SVI.CONTACT_PERSON,SVI.EMAIL,SVI.TEMPLATE_ID,SVI.MESSAGE,SVI.APPROVER_NOTE,SVI.DATE_CREATED,SVI.CREATED_BY,SVI.ACTIVE,SVI.USER_ID,SVI.EMAIL_TEMPLATE_ID,SVI.BUSINESS_TYPE,SVI.TRADE_VENDOR_TYPE,SVI.REASON_FOR_EXTENSION, CURRENT_DATE FROM SMNTP_VENDOR_INVITE SVI WHERE SVI.VENDOR_INVITE_ID = ".$vendor_invite_id;
				$this->db->query($insert_vendor_invite_logs);

				//Delete Vendors
				$delete_vendors = "DELETE FROM SMNTP_VENDOR_INVITE WHERE VENDOR_INVITE_ID =".$vendor_invite_id;
				$this->db->query($delete_vendors);

			}else{

				//Insert To User_Logs
				$insert_user_logs = "INSERT INTO SMNTP_USERS_SYS_LOGS(USER_ID,USER_FIRST_NAME, USER_MIDDLE_NAME,USER_LAST_NAME,USER_TYPE_ID,POSITION_ID,USER_STATUS, USER_DATE_CREATED, USER_MOBILE, USER_EMAIL,VENDOR_ID, HEAD_ID)
										SELECT * FROM SMNTP_USERS WHERE USER_ID =".$d;
				$this->db->query($insert_user_logs);

			}
			
			//Delete Users
			$delete_users = "DELETE FROM SMNTP_USERS WHERE USER_ID =".$d;
			$this->db->query($delete_users);
			
			//Insert To Credentials
			$insert_cred_logs = "INSERT INTO SMNTP_CREDENTIALS_SYS_LOGS(CREDENTIAL_ID,USER_ID,USERNAME,PASSWORD,TIME_STAMP,DEACTIVATED_FLAG,REMOVE_DATE)
									SELECT *,NOW() FROM SMNTP_CREDENTIALS WHERE USER_ID =".$d;
			$this->db->query($insert_cred_logs);

			//Delete Credentials
			$delete_cred = "DELETE FROM SMNTP_CREDENTIALS WHERE USER_ID =".$d;
			$sql = $this->db->query($delete_cred);


			// $this->db->where($data);
			// $sql = $this->db->update('SMNTP_USERS',$data2);
			// $this->db->where($data);
			// $sql = $this->db->update('SMNTP_CREDENTIALS',array('DEACTIVATED_FLAG' => 1));


			return $sql;
		}

		function get_approvers($id)
		{
			$approver = '';

				switch($id['POSITION_ID']){
						case 2: $approver = $this->get_senmer();
								break;
						case 7: $approver = $this->get_buyer();
						default:
								break;
				}


				return $approver;

		}
		function  get_senmer()
		{
				$this->db->select('USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_ID,POSITION_ID');
				
				$this->db->where_in('POSITION_ID', array(3, 4, 5));
				$this->db->where('USER_STATUS', 1);
				$this->db->order_by('UPPER(USER_FIRST_NAME)');
				$res = $this->db->get('SMNTP_USERS');
				//log_message('debug', $this->db->last_query());
				$test = array();
				$res = $res->result_array();
				return $res;



		}
		function get_buyer()
		{
				$this->db->select('B.POSITION_NAME,A.USER_FIRST_NAME,A.USER_MIDDLE_NAME,A.USER_LAST_NAME,A.USER_ID,A.POSITION_ID');
				$this->db->from('SMNTP_USERS A');
				$this->db->join('SMNTP_POSITION B','A.POSITION_ID = B.POSITION_ID','LEFT');
				$this->db->where_in('A.POSITION_ID', array(8, 4, 9, 5));
				$this->db->where('A.USER_STATUS', 1);
				$this->db->order_by('UPPER(USER_FIRST_NAME)');
				$res = $this->db->get();
				//log_message('debug', $this->db->last_query());
				$test = array();
				$res = $res->result_array();
				return $res;

		}

		function get_category($data, $vendor_type = NULL)
		{

			$ds = "UPPER(CATEGORY_NAME) LIKE '%".strtoupper($data['CATEGORY_NAME'])."%'";
			$this->db->select('CATEGORY_NAME,CATEGORY_ID');
			$this->db->from('SMNTP_CATEGORY');
			$this->db->where(array('STATUS'=>'1'));
			
			if( ! empty($vendor_type)){
				$this->db->where(array('BUSINESS_TYPE'=>$vendor_type));
			}
			
			$this->db->where($ds);
			$this->db->order_by('UPPER(CATEGORY_NAME)','asc');
			$res = $this->db->get();
			return $res->result_array();
		}

		function m_select_last($FN,$MO,$PO,$EM)
		{
			/*$sql = "SELECT USER_ID FROM SMNTP_USERS WHERE USER_FIRST_NAME = '$FN' AND USER_MOBILE = '$MO' AND POSITION_ID = '$PO' AND USER_EMAIL = '$EM' ORDER BY USER_ID DESC";
			$res2 = $this->db->query($sql);	*/		

			$sql = $this->db->select('USER_ID')->from('SMNTP_USERS')->where(array('USER_FIRST_NAME' => $FN, 'USER_MOBILE' => $MO, 'POSITION_ID' => $PO, 'USER_EMAIL' => $EM))->get()->result_array();
			return $sql;		
		}
		
		function insert_user_category($data){
			$cate_length = count($data['CAT']);
			
			$user_ids = array();
			//return $data['user_names'];
			foreach($data['user_names'] as $key => $v){
				//return $v;
				$id = $this->db->query('SELECT USER_ID FROM SMNTP_CREDENTIALS 
											WHERE USERNAME = ? ', $v)->result_array();
				//$user_id = $id[0]['USER_ID'];
				if (isset($id[0]['USER_ID'])){
					array_push($user_ids, $id[0]['USER_ID']);
				}else{
					array_push($user_ids, '');
				}


			}
			//return $user_ids;
			
			for($i = 0; $i < $cate_length; $i++){
				$dt = explode('|',$data['CAT'][$i]);
				$where_array = array('USER_ID'=>$user_ids[$i]);
				$ret = $this->db->delete('SMNTP_USER_CATEGORY', $where_array);

				//return $ret;

				//$this->db->where(array('USER_ID'=>$user_ids[$i])
				//$this->db->query('DELETE SMNTP_USER_CATEGORY WHERE USER_ID = )


				//return $cat_id;
				foreach($dt as $key => $ci){
						$cat_id = $this->db->query('SELECT CATEGORY_ID FROM SMNTP_CATEGORY 
											WHERE CATEGORY_NAME = ? ', $ci)->result_array();
						if (isset($cat_id[0]['CATEGORY_ID'])){
							$category_id = $cat_id[0]['CATEGORY_ID'];
							$categ_id = array(
								'CATEGORY_ID' => $category_id,
								'USER_ID' => $user_ids[$i]
							);
							//return $categ_id;
							$this->db->insert('SMNTP_USER_CATEGORY',$categ_id);
						}
						

				}
			}

			//return true;
		}
		
		function insert_user_bulk_category($data){
			$cate_length = count($data['CAT']);
			
			$user_ids = array();
			foreach($data['user_names']  as $v){
				$id = $this->db->query('SELECT USER_ID FROM SMNTP_CREDENTIALS 
											WHERE USERNAME = ? ', array($v))->result_array()[0]['USER_ID'];
				$user_ids[] = $id;
			}
			
			for($i = 0; $i < $cate_length; $i++){
				$dt = explode('|',$data['CAT'][$i]);
				$this->db->query('DELETE SMNTP_USER_CATEGORY WHERE USER_ID = ?',
									array($user_ids[$i]));
				foreach($dt as $d){
					$category_id = $this->db->query('SELECT CATEGORY_ID 
														FROM SMNTP_CATEGORY 
														WHERE LOWER(CATEGORY_NAME) = ?',
														array(
															strtolower($d)
														))->result_array();
														
					if( ! empty($category_id)){
						$cat_data = array(
							'CATEGORY_ID' => $category_id[0]['CATEGORY_ID'],
							'USER_ID' => $user_ids[$i]
						);
						$this->db->insert('SMNTP_USER_CATEGORY',$cat_data);
					}
				}
			}
			return true;
		}

		function new_user($data,$vrd,$vrdhead)
		{
			$utype = $data['POSITION_ID'];

			$login_id = array(
				'USERNAME' => $data['USERNAME']
				);

			$this->db->select('USERNAME, USER_ID');
			$this->db->from('SMNTP_CREDENTIALS');
			$this->db->where($login_id);
			$sql = $this->db->get();
			$result = $sql->result_array();
			if(count($result) > 0){
				//$this->db->query("DELETE FROM SMNTP_USERS_TOKENS WHERE USER_ID= ?",  array($result[0]['USER_ID']));
				//$this->db->query('DELETE FROM SMNTP_USER_CATEGORY WHERE USER_ID = ?', array($result[0]['USER_ID']));
				//$this->db->query('DELETE FROM SMNTP_USERS WHERE USER_ID = ?', array($result[0]['USER_ID']));
				//$this->db->query('DELETE FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($data['USERNAME']));
				return 'exist';	
			}
			if($utype == 10){
				$utype = 2;
			}else{
				$utype = 1;
			}

			$_info = array(
				'USER_FIRST_NAME' =>$data['USER_FIRST_NAME'],
				'USER_MIDDLE_NAME' =>$data['USER_MIDDLE_NAME'],
				'USER_LAST_NAME' => $data['USER_LAST_NAME'],
				'USER_MOBILE' => $data['USER_MOBILE'],
				'USER_EMAIL' => $data['USER_EMAIL'],
				'POSITION_ID' => $data['POSITION_ID'],
				'USER_TYPE_ID' => $utype,
				'USER_STATUS' => '1'
			);

			$res = $this->db->insert('SMNTP_USERS',$_info);

/*
			$test['user_last_query'] = $this->db->last_query();*/

			// if($data['POSITION_ID'] == 10){
			// 		insert into vendors table
			// }

			if(!$res){
				return;
			}


			$last_id = $this->m_select_last($_info['USER_FIRST_NAME'],$data['USER_MOBILE'],$data['POSITION_ID'],$data['USER_EMAIL']);
			
			if(is_array($vrd)){	
				for($i = 0;$i<count($vrd);$i++){
					$umatrix = array(
						'USER_ID' => $last_id[0]['USER_ID'],
					//	'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
						'BUHEAD_ID'=> $data['BUHEAD_ID'],
						'GHEAD_ID'=> $data['GHEAD_ID'],
						'FASHEAD_ID'=> $data['FASHEAD_ID'],
						'VRDSTAFF_ID' => $vrd[$i]
						);
					$this->db->insert('SMNTP_USERS_MATRIX',$umatrix);
				}
			}


			if(is_array($vrdhead)){	
				for($i = 0;$i<count($vrdhead);$i++){
					$umatrix = array(
						'USER_ID' => $last_id[0]['USER_ID'],
						'BUHEAD_ID'=> $data['BUHEAD_ID'],
						'GHEAD_ID'=> $data['GHEAD_ID'],
						'FASHEAD_ID'=> $data['FASHEAD_ID'],
						'VRDHEAD_ID' => $vrdhead[$i]
						);
				$res = 	$this->db->insert('SMNTP_USERS_MATRIX',$umatrix);
				}
			}



			$values = array(
					'USERNAME' => $data['USERNAME'],
					//'PASSWORD' => $data['PASSWORD'],
					'USER_ID' =>$last_id[0]['USER_ID']
			);

			$rests = $this->db->insert('SMNTP_CREDENTIALS',$values);

			$dt = explode('|',$data['CAT']);

			for($i = 0; $i < count($dt); $i++){
				$cat_id = array(
				'CATEGORY_ID' => $dt[$i],
				'USER_ID' => $last_id[0]['USER_ID']
				);
				$this->db->insert('SMNTP_USER_CATEGORY',$cat_id);
			}

			//return($data['POSITION_ID']);

			/*if($data['POSITION_ID'] == 2){
				$_matrix = array(
					'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
					'VRDSTAFF_ID'=> $data['VRDSTAFF_ID'],
					'BUHEAD_ID'=> $data['BUHEAD_ID'],
					'USER_ID' =>$last_id[0]['USER_ID']
					);

				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$_matrix);
				return true;
			}
			if($data['POSITION_ID'] == 7){
				$_matrix = array(
					'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
					'VRDSTAFF_ID'=>$data['VRDSTAFF_ID'],
					'GHEAD_ID'=> $data['GHEAD_ID'],
					'FASHEAD_ID'=> $data['FASHEAD_ID'],
					'USER_ID' =>$last_id[0]['USER_ID']
					);
				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$_matrix);
				return true;
			}*/
		
			//Jay
			//SEND EMAIL PASSWORD SET HERE.
			
			//Create USER token
			$token = $this->inviteapproval_model->create_user_token(array('user_id' => $last_id[0]['USER_ID']));
			
			//Get expiration days
			$where_arr = array('CONFIG_NAME' => 'invite_expiration_days');
			$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
	
			//Get the user token
			$where_arr2 = array('TOKEN' => $token);
			$start_day = $this->common_model->get_from_table_where_array('SMNTP_USERS_TOKENS', 'DATE_CREATED', $where_arr2);

			//Expiration Date
			$expiry_date = date('F d, Y', strtotime($start_day. ' + '.$expire_day.' days'));
			
			//Password Reset Link with Token
			$token 	= '<a href="'.$data['surl'].'index.php/setpassword/index/'.$token.'" title="">CLICK HERE TO SET PASSWORD</a>';
			$surl 	= '<a href="'.$data['surl'].'" title="">'.$data['surl'].'</a>';
			
			//Get Message Template
			$where_arr = array('TEMPLATE_TYPE' => 44);
			$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			//Message Format
			$user_full_name = $_info['USER_FIRST_NAME'] . ' ' .  $_info['USER_MIDDLE_NAME']; 
			$user_full_name = trim($user_full_name) . ' ' . $_info['USER_LAST_NAME'];
			$user_full_name = trim($user_full_name);
			$var['message'] = str_replace('[vendorname]', $user_full_name, $email_template);
			$var['message'] = str_replace('[vendor_name]', $user_full_name, $var['message']);
			$var['message'] = str_replace('[username]', $data['USERNAME'], $var['message']);
			$var['message'] = str_replace('[expiryday]', $expire_day, $var['message']);
			$var['message'] = str_replace('[expirydate]', $expiry_date, $var['message']);
			$var['message'] = str_replace('[token]', $token, $var['message']);
			$var['message'] = str_replace('[base_url]', $surl, $var['message']);
			
			$email_data['subject'] = 'Set Account Password';
			$email_data['content'] = nl2br($var['message']);

			$email_data['to'] = $data['USER_EMAIL'];
			$this->common_model->send_email_notification($email_data);
			return true;
			//return $last_id[0]['USER_ID'];
		}



		function searchuser($data)
		{


			$this->load->helper('date');
			$dsearch = explode(" ", $data['SEARCH']);

			$data['count'] = $this->get_user_count($dsearch, $data['TYPE']);
			
			$this->db->select('*');
			$this->db->from('SMNTP_USER_DETAILS');
			foreach ($dsearch as $key => $value) {


			/* $value = str_replace("'", "''", $value);*/


			if($data['TYPE'] == 1){
					$this->db->or_like(array('UPPER(USERNAME)' => strtoupper(	$value)));
					$this->db->or_like(array('UPPER(USER_FIRST_NAME)' => strtoupper(	$value)));
					$this->db->or_like(array('UPPER(USER_MIDDLE_NAME)' => strtoupper(	$value)));
					$this->db->or_like(array('UPPER(USER_LAST_NAME)' =>  strtoupper(	$value)));
			}

			if($data['TYPE'] == 2){
					$this->db->or_like(array('UPPER(USERNAME)' => strtoupper(	$value)));
			}

			if($data['TYPE'] == 3){
					$this->db->or_like(array('UPPER(USER_FIRST_NAME)' => strtoupper(	$value)));
					$this->db->or_like(array('UPPER(USER_MIDDLE_NAME)' => strtoupper(	$value)));
					$this->db->or_like(array('UPPER(USER_LAST_NAME)' =>  strtoupper(	$value)));

			}

			}

			$data['START'] = $data['START'] * 10;
			if($data['START'] == 0){
				$offset = 11;
			}else{
				$offset = 10;
			}

			/*$this->db->limit(10,$data['START']);*/

			// Start Change by Nick 20220105 -- Old Code
			// $data['data'] = $this->db->order_by('UPPER('.$data['S_TYPE'].') '.$data['SORT'].' NULLS LAST')->order_by('USER_FIRST_NAME','asc')->limit(10,$data['START'])->get()->result_array();
			$data['data'] = $this->db->order_by('(CASE WHEN '.$data['S_TYPE'].' IS NULL THEN 1 ELSE 0 END)')->order_by('UPPER('.$data['S_TYPE'].') '.$data['SORT'].'  ')->order_by('USER_FIRST_NAME','asc')->limit(10,$data['START'])->get()->result_array();
			// End Change by Nick 20220105 -- Old Code

			$data['last_query'] = $this->db->last_query();
			//return $this->db->last_query();
			return 	$data;



		}

		function get_user_count($search_data = array(), $type = ""){


			$count = 0;

			if(!empty($search_data) && !empty($type)){
					$this->db->select('*');
					$this->db->from('SMNTP_USER_DETAILS');
				foreach ($search_data as $key => $value) {	

						/* $value = str_replace("'", "''", $value);*/

						if($type == 1){
							$this->db->or_like(array('UPPER(USERNAME)' => strtoupper(	$value)));
							$this->db->or_like(array('UPPER(USER_FIRST_NAME)' => strtoupper(	$value)));
							$this->db->or_like(array('UPPER(USER_MIDDLE_NAME)' => strtoupper(	$value)));
							$this->db->or_like(array('UPPER(USER_LAST_NAME)' =>  strtoupper(	$value)));
						}else if($type == 2){		
							$this->db->or_like(array('UPPER(USERNAME)' => strtoupper(	$value)));			
						}else if($type == 3){
							$this->db->or_like(array('UPPER(USER_FIRST_NAME)' => strtoupper(	$value)));
							$this->db->or_like(array('UPPER(USER_MIDDLE_NAME)' => strtoupper(	$value)));
							$this->db->or_like(array('UPPER(USER_LAST_NAME)' =>  strtoupper(	$value)));
						}else{
							//
						}
				}

				$query = $this->db->get();
				$count = $query->num_rows();
			}

			return $count;
		}


		function select_category_id($data)
		{

			$res = $this->db->select('*')->from('USER_CAT')->where($data)->get();;


			return $res->result_array();
		}

		function update_user_matrix($data,$uid)
		{



		}

		function update_user($data,$vrd,$vrdh)
		{

			$utype = $data['POSITION_ID'];
			$uid = array('USER_ID' => $data['USER_ID']);

			if($utype == 10){
				$utype = 2;
			}else{
				$utype = 1;
			}

			$check_current_status = $this->db->query('SELECT USER_STATUS FROM SMNTP_USERS WHERE USER_ID = ?', array($data['USER_ID']))->row_array();
			$cur_status = $check_current_status['USER_STATUS'];
			if($cur_status != $data['USER_STATUS']){
				$user_status_logs = array(
					'USER_ID' => $data['USER_ID'],
					'USER_STATUS_ID' => $data['USER_STATUS'],
					'DATE_MODIFIED' => date("Y-m-d h:i:s")
				);

				$res = $this->db->insert('SMNTP_USERS_STATUS_LOGS',$user_status_logs);

				if($data['USER_STATUS'] == 0 ){
					$deactivated_flag = 1;
				}else{
					$deactivated_flag = 0;
				}

				$deactivated_status = array(
					'DEACTIVATED_FLAG' => $deactivated_flag
				);
				$this->db->where($uid);
				$this->db->update('SMNTP_CREDENTIALS',$deactivated_status);
			}

			$info = array(
				'USER_FIRST_NAME' => $data['USER_FIRST_NAME'],
				'USER_MIDDLE_NAME' => $data['USER_MIDDLE_NAME'],
				'USER_LAST_NAME' => $data['USER_LAST_NAME'],
				'POSITION_ID' => $data['POSITION_ID'],
				'USER_STATUS' => $data['USER_STATUS'],
				'USER_TYPE_ID' => $utype,
				'USER_MOBILE' => $data['USER_MOBILE'],
				'USER_EMAIL' => $data['USER_EMAIL']
				);

			$this->db->where($uid);
			$res = $this->db->update('SMNTP_USERS',$info);
			$rdata = array($res,$this->db->last_query());
			//return $rdata;
			
			// Update SMNTP Vendor Invite Table
			if($utype == 2){
				$info2 = array(
					'VENDOR_NAME' => $data['USER_FIRST_NAME'],
					'EMAIL' => $data['USER_EMAIL']
				);

				$this->db->where($uid);
				$this->db->update('SMNTP_VENDOR_INVITE',$info2);
				$xdata = array($res,$this->db->last_query());
			}
		

			$this->load->library('CryptManager');
			$cred = $this->db->query('SELECT USERNAME, PASSWORD, TIME_STAMP FROM SMNTP_CREDENTIALS WHERE USER_ID = ?', array($data['USER_ID']))->row_array();
			$username = $cred['USERNAME'];
			$cred_password = $cred['PASSWORD'];
			
			//If password is not same then update and encrypt the password
			if($cred_password != $data['PASSWORD']){
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $cred['TIME_STAMP'] . $data['USER_ID'])),0,16);
				$enc_password = $this->cryptmanager->encrypt($data['PASSWORD'], FALSE, $file_crypt_key);
				//$decrypted = base64_decode($this->cryptmanager->decrypt($enc_password, FALSE, $file_crypt_key));
				//log_message('debug', 'Decrypted : ' . $decrypted);
				$info = array(
					'PASSWORD' => $enc_password
				);
				$this->db->where($uid);
				$this->db->update('SMNTP_CREDENTIALS',$info);
			}
			

			if($utype == 2){

				$this->db->delete('SMNTP_USER_CATEGORY',$uid);
				return $res;
			}

			$cat_id = explode('|',$data['CAT']);
			$this->db->delete('SMNTP_USER_CATEGORY',$uid);
			for($i=0;$i<count($cat_id);$i++){
				$tmp = array(
					'USER_ID' => $data['USER_ID'],
					'CATEGORY_ID' => $cat_id[$i]
					);
				$res = $this->db->insert('SMNTP_USER_CATEGORY',$tmp);
			}

			$matrix = array(
				//'VRDHEAD_ID' => $data['VRDHEAD_ID'],
				'USER_ID' => $data['USER_ID'],
				'BUHEAD_ID' => $data['BUHEAD_ID'],
				'GHEAD_ID'=> $data['GHEAD_ID'],
				'FASHEAD_ID'=> $data['FASHEAD_ID']
				);

			$this->db->delete('SMNTP_USERS_MATRIX',$uid);

			if(count($vrd)>0){
				for($i = 0;$i<count($vrd);$i++){
				$mx = array(
					'USER_ID' => $data['USER_ID'],
					'VRDSTAFF_ID' => $vrd[$i],
					//'VRDHEAD_ID' => $data['VRDHEAD_ID'],
					'USER_ID' => $data['USER_ID'],
					'BUHEAD_ID' => $data['BUHEAD_ID'],
					'GHEAD_ID'=> $data['GHEAD_ID'],
					'FASHEAD_ID'=> $data['FASHEAD_ID']
					);
				$rest = $this->db->insert('SMNTP_USERS_MATRIX',$mx);
			}

				for($i = 0;$i<count($vrdh);$i++){
					$mx = array(
						'USER_ID' => $data['USER_ID'],
						//'VRDSTAFF_ID' => $vrd[$i],
						'VRDHEAD_ID' => $vrdh[$i],
						'USER_ID' => $data['USER_ID'],
						'BUHEAD_ID' => $data['BUHEAD_ID'],
						'GHEAD_ID'=> $data['GHEAD_ID'],
						'FASHEAD_ID'=> $data['FASHEAD_ID']
						);
					$rest = $this->db->insert('SMNTP_USERS_MATRIX',$mx);
				}
			
			}




		$rest =	$this->db->insert('SMNTP_USERS_MATRIX',$matrix);

		return $rest;
		}

		function view_edit_user($uid)
		{

			$get_info = 'USER_ID,USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_MOBILE,USER_EMAIL,USER_TYPE_ID,POSITION_ID,USER_STATUS';
			$get_credentials = 'USERNAME,PASSWORD';
			$get_matrix ='BUHEAD_ID,GHEAD_ID,FASHEAD_ID,VRDHEAD_ID';
			$get_vrdstaff = 'VRDSTAFF_ID';
			$get_category ='B.CATEGORY_NAME';


			$wherevrdstaff = array(
				'USER_ID' => $uid['USER_ID']
				//'VRDHEAD_ID' => NULL
				);

			$wherematrix = array(
				'USER_ID' => $uid['USER_ID'],
				);


			$info = $this->select_query($get_info,$uid,'SMNTP_USERS');
			$user['INFORMATION'] = $info;
			$credentials = $this->select_query($get_credentials,$uid,'SMNTP_CREDENTIALS');
			$user['CREDENTIALS'] = $credentials;
			$user['LOGIN_ATTEMPTS'] = $this->db->select('UNLOCK_TIME, ATTEMPTS, LAST_ATTEMPT')
												->from('SMNTP_LOGIN_ATTEMPTS')
												->where('USER_ID', $uid['USER_ID'])
												->get()
												->row_array();
	
			if(!empty($user['LOGIN_ATTEMPTS'] ) && $user['LOGIN_ATTEMPTS']['UNLOCK_TIME']  > 0 && microtime(true) <  $user['LOGIN_ATTEMPTS']['UNLOCK_TIME']){
				$user['LOGIN_ATTEMPTS']['UNLOCK_TIME_FORMATTED'] = date("F d, Y h:i:sa", substr($user['LOGIN_ATTEMPTS']['UNLOCK_TIME'], 0, 10)); 
				$user['LOGIN_ATTEMPTS']['LAST_ATTEMPT_FORMATTED'] = date("F d, Y h:i:sa", substr($user['LOGIN_ATTEMPTS']['UNLOCK_TIME'], 0, 10)); 
			}else{
				$user['LOGIN_ATTEMPTS']['ATTEMPTS'] = 0;
			}
												
			if(($info[0]['POSITION_ID'] == '2')||($info[0]['POSITION_ID'] == '7')||($info[0]['POSITION_ID'] == '11')){
			$user['UMATRIX'] = $this->select_query($get_matrix,$wherematrix,'SMNTP_USERS_MATRIX');				
			$vrd = $this->select_query($get_vrdstaff,$wherevrdstaff,'SMNTP_USERS_MATRIX');	

			
			$vrdarr = array();
			foreach ($vrd as $key => $value) {
				array_push($vrdarr, $value['VRDSTAFF_ID']);	
			}

			//return $vrdarr;

			$x = 0;
			$arrvrdid = array();
			$vrd = array();
			for($x=0;$x<count($vrdarr);$x++){
				if($vrdarr[$x] != null){
					array_push($vrd,$vrdarr[$x]);
				}
			}



			//eturn $arrvrdid;

			$user['VRDSTAFF'] = $vrd;
			$this->db->select('B.CATEGORY_NAME,B.CATEGORY_ID');
			$this->db->from('SMNTP_USER_CATEGORY A');
			$this->db->join('SMNTP_CATEGORY B','B.CATEGORY_ID = A.CATEGORY_ID','LEFT');
			$this->db->order_by('UPPER(B.CATEGORY_NAME)');
			$user['CATEGORY'] = $this->db->where($uid)->get()->result_array();
					
			}

			return $user;
			
		}
		
		function view_pending_records($uid){
			$this->db->select("SVI.VENDOR_NAME,SVS.STATUS_ID,SS.STATUS_NAME");
			$this->db->from('SMNTP_VENDOR_INVITE SVI');
			$this->db->join('SMNTP_VENDOR_STATUS SVS', 'SVS.VENDOR_INVITE_ID = SVI.VENDOR_INVITE_ID');
			$this->db->join('SMNTP_STATUS SS', 'SVS.STATUS_ID = SS.STATUS_ID');
			$this->db->join('SMNTP_USERS SU', 'SU.USER_ID = SVI.CREATED_BY');
			$this->db->where($uid);
			$this->db->order_by('SVI.VENDOR_INVITE_ID DESC, SVS.VENDOR_INVITE_STATUS_ID');
			
			return $this->db->get()->result_array();;
		}

		function select_query($items,$where,$table_name)
		{
			$res = $this->db->select($items)->where($where)->from($table_name)->get()->result_array();
			return $res;
		}


		function get_user_id($username){
			$id = $this->db->query('SELECT USER_ID FROM SMNTP_CREDENTIALS WHERE USERNAME = ?',array($username))->result_array();
			if (isset($id[0]['USER_ID'])){
				return $id[0]['USER_ID'];
			}else{
				return "";
			}
			
		}

		function get_credentials($username){
			$id = $this->db->query('SELECT * FROM SMNTP_CREDENTIALS WHERE USERNAME = ?',array($username))->result_array();
			if (!empty($id)){
				$id = $this->db->query('SELECT * FROM SMNTP_USERS WHERE USER_ID = ?',array($id[0]['USER_ID']))->result_array();
				if (!empty($id)){
					return $id;
				}else{
					return '';
				}
			}else{
				return "";
			}
			
		}
		
		//jay
		function bulk_new_user($data,$vrd,$vrdhead)
		{
			$utype = $data['POSITION_ID'];

			$login_id = array(
				'USERNAME' => $data['USERNAME']
				);

			$this->db->select('USERNAME, USER_ID');
			$this->db->from('SMNTP_CREDENTIALS');
			$this->db->where($login_id);
			$sql = $this->db->get();
			$result = $sql->result_array();
			if(count($result) > 0){
				$this->db->query("DELETE FROM SMNTP_USERS_TOKENS WHERE USER_ID= ?",  array($result[0]['USER_ID']));
				$this->db->query('DELETE FROM SMNTP_USER_CATEGORY WHERE USER_ID = ?', array($result[0]['USER_ID']));
				$this->db->query('DELETE FROM SMNTP_USERS WHERE USER_ID = ?', array($result[0]['USER_ID']));
				$this->db->query('DELETE FROM SMNTP_USERS_MATRIX WHERE USER_ID = ?', array($result[0]['USER_ID']));
				$this->db->query('DELETE FROM SMNTP_CREDENTIALS WHERE USERNAME = ?', array($data['USERNAME']));
				//return 'exist';	
			}
			if($utype == 10){
				$utype = 2;
			}else{
				$utype = 1;
			}

			$_info = array(
				'USER_FIRST_NAME' => $data['USER_FIRST_NAME'],
				'USER_MIDDLE_NAME' => $data['USER_MIDDLE_NAME'],
				'USER_LAST_NAME' => $data['USER_LAST_NAME'],
				'USER_MOBILE' => $data['USER_MOBILE'],
				'USER_EMAIL' => $data['USER_EMAIL'],
				'POSITION_ID' => $data['POSITION_ID'],
				'USER_TYPE_ID' => $utype,
				'USER_STATUS' => '1'
			);

			$res = $this->db->insert('SMNTP_USERS',$_info);

			// if($data['POSITION_ID'] == 10){
			// 		insert into vendors table
			// }

			if(!$res){
				return;
			}


			$last_id = $this->m_select_last( $_info['USER_FIRST_NAME'],$data['USER_MOBILE'],$data['POSITION_ID'],$data['USER_EMAIL']);
			
			if(is_array($vrd)){	
				for($i = 0;$i<count($vrd);$i++){
					$umatrix = array(
						'USER_ID' => $last_id[0]['USER_ID'],
					//	'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
						'BUHEAD_ID'=> $data['BUHEAD_ID'],
						'GHEAD_ID'=> $data['GHEAD_ID'],
						'FASHEAD_ID'=> $data['FASHEAD_ID'],
						'VRDSTAFF_ID' => $vrd[$i]
						);
					$this->db->insert('SMNTP_USERS_MATRIX',$umatrix);
				}
			}


			if(is_array($vrdhead)){	
				for($i = 0;$i<count($vrdhead);$i++){
					$umatrix = array(
						'USER_ID' => $last_id[0]['USER_ID'],
						'BUHEAD_ID'=> $data['BUHEAD_ID'],
						'GHEAD_ID'=> $data['GHEAD_ID'],
						'FASHEAD_ID'=> $data['FASHEAD_ID'],
						'VRDHEAD_ID' => $vrdhead[$i]
						);
				$res = 	$this->db->insert('SMNTP_USERS_MATRIX',$umatrix);
				}
			}



			$values = array(
					'USERNAME' => $data['USERNAME'],
					'PASSWORD' => 'smntp123',
					//'PASSWORD' => $data['PASSWORD'],
					'USER_ID' =>$last_id[0]['USER_ID']
			);

			$rests = $this->db->insert('SMNTP_CREDENTIALS',$values);

			//return $values;

			// $dt = explode('|',$data['CAT']);

			// for($i = 0; $i < count($dt); $i++){
			// 	$cat_id = array(
			// 	'CATEGORY_ID' => $dt[$i],
			// 	'USER_ID' => $last_id[0]['USER_ID']
			// 	);
			// 	$this->db->insert('SMNTP_USER_CATEGORY',$cat_id);
			// }

			//return($data['POSITION_ID']);

			/*if($data['POSITION_ID'] == 2){
				$_matrix = array(
					'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
					'VRDSTAFF_ID'=> $data['VRDSTAFF_ID'],
					'BUHEAD_ID'=> $data['BUHEAD_ID'],
					'USER_ID' =>$last_id[0]['USER_ID']
					);

				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$_matrix);
				return true;
			}
			if($data['POSITION_ID'] == 7){
				$_matrix = array(
					'VRDHEAD_ID'=> $data['VRDHEAD_ID'],
					'VRDSTAFF_ID'=>$data['VRDSTAFF_ID'],
					'GHEAD_ID'=> $data['GHEAD_ID'],
					'FASHEAD_ID'=> $data['FASHEAD_ID'],
					'USER_ID' =>$last_id[0]['USER_ID']
					);
				$rt = $this->db->insert('SMNTP_USERS_MATRIX',$_matrix);
				return true;
			}*/
		
			//Jay
			//SEND EMAIL PASSWORD SET HERE.
			
			//Create USER token
			$token = $this->inviteapproval_model->create_user_token(array('user_id' => $last_id[0]['USER_ID']));
			
			//Get expiration days
			$where_arr = array('CONFIG_NAME' => 'invite_expiration_days');
			$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
	
			//Get the user token
			$where_arr2 = array('TOKEN' => $token);
			$start_day = $this->common_model->get_from_table_where_array('SMNTP_USERS_TOKENS', 'DATE_CREATED', $where_arr2);

			//Expiration Date
			$expiry_date = date('F d, Y', strtotime($start_day. ' + '.$expire_day.' days'));
			
			//Password Reset Link with Token
			$token 	= '<a href="'.$data['surl'].'index.php/setpassword/index/'.$token.'" title="">CLICK HERE TO SET PASSWORD</a>';
			$surl 	= '<a href="'.$data['surl'].'" title="">'.$data['surl'].'</a>';
			
			//Get Message Template
			$where_arr = array('TEMPLATE_TYPE' => 44);
			$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			//Message Format
			$var['message'] = str_replace('[username]', $data['USERNAME'], $email_template);
			$var['message'] = str_replace('[expiryday]', $expire_day, $var['message']);
			$var['message'] = str_replace('[expirydate]', $expiry_date, $var['message']);
			$var['message'] = str_replace('[token]', $token, $var['message']);
			$var['message'] = str_replace('[base_url]', $surl, $var['message']);
			
			$email_data['subject'] = 'Set Account Password';
			$email_data['content'] = nl2br($var['message']);

			// $email_data['to'] = $data['USER_EMAIL'];
			$email_data['to'] = 'pagaraojustineprice@yahoo.com';
			$res = $this->common_model->send_email_notification($email_data);
			return $var;
			//return $last_id[0]['USER_ID'];
		}

		function resend_email($user_id,$user_name,$burl){

				$this->load->model('common_model');
		
				$res = $this->db->query('SELECT SMNTP_USERS.USER_ID,SMNTP_USERS.USER_EMAIL,SMNTP_USERS.USER_FIRST_NAME || \' \' || SMNTP_USERS.USER_MIDDLE_NAME || \'\' || SMNTP_USERS.USER_LAST_NAME AS FULLNAME FROM SMNTP_USERS WHERE SMNTP_USERS.USER_ID = ' .$user_id);	
					
				$rest =  $res->row();


				$token = $this->inviteapproval_model->create_user_token(array('user_id' => $user_id));
				$orig_token = $token;
				$where_arr = array('CONFIG_NAME' => 'invite_expiration_days');
				$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);

				$where_arr2 = array('TOKEN' => $token);
				$start_day = $this->common_model->get_from_table_where_array('SMNTP_USERS_TOKENS', 'DATE_CREATED', $where_arr2);

				$expiry_date = date('F d, Y', strtotime($start_day. ' + '.$expire_day.' days'));
			
			//Password Reset Link with Token
				$token 	= '<a href="'.$burl.'setpassword/index/'.$token.'" title="">CLICK HERE TO SET PASSWORD</a>';
				$surl 	= '<a href="'.$burl.'" title="">'.$burl.'</a>';

				
			
			//Get Message Template
				$where_arr = array('TEMPLATE_TYPE' => 44);
				$email_template = $this->common_model->get_from_table_where_array('SMNTP_EMAIL_DEFAULT_TEMPLATE', 'CONTENT', $where_arr);

			$var['message'] = str_replace('[username]', $user_name, $email_template);
			$var['message'] = str_replace('[vendorname]', $rest->FULLNAME, $var['message']);
			$var['message'] = str_replace('[vendor_name]', $rest->FULLNAME, $var['message']);
			$var['message'] = str_replace('[expiryday]', $expire_day, $var['message']);
			$var['message'] = str_replace('[expirydate]', $expiry_date, $var['message']);
			$var['message'] = str_replace('[token]', $token, $var['message']);
			$var['message'] = str_replace('[base_url]', $surl, $var['message']);


			
			$email_data['subject'] = 'Set Account Password';
			$email_data['content'] = nl2br($var['message']);


			$email_data['to'] = $rest->USER_EMAIL;
			$res = $this->common_model->send_email_notification($email_data);

			if( ! is_bool($res)){
				$res = false;
			}
			
			//Insert Logs
			if($this->db->table_exists('SMNTP_USERS_TOKENS_LOGS')){
				$this->db->insert('SMNTP_USERS_TOKENS_LOGS', array(
					'USER_ID' => $user_id,
					'TOKEN'	  => $orig_token,
					'RESULT' => $res
				));
				//'RESEND_DATE' => $start_day,
			}
			
			return $res;
			//return $res->result();
		}
		
		public function unlock_account($data){
			if($this->db->table_exists('SMNTP_USER_UNLOCK_ACCOUNT_LOGS')){
				$this->db->insert('SMNTP_USER_UNLOCK_ACCOUNT_LOGS', array(
					'USER_ID'	=> $data['uid'],
					'REASON' 	=> $data['reason']
				));
			}
			$this->db->where('USER_ID', $data['uid']);
			$this->db->set(array(
				'ATTEMPTS' => 0,
				'UNLOCK_TIME' => 0
			));
			$this->db->update('SMNTP_LOGIN_ATTEMPTS');
			return true;
		}
		
		public function get_unlock_account_logs($id){
			if( ! $this->db->table_exists('SMNTP_USER_UNLOCK_ACCOUNT_LOGS')){
				return false;
			}
			return $this->db->select("A.*, CAST(DATE_FORMAT(DATE_UNLOCKED,'%M %d,%Y %h:%i:%s %p') AS CHAR) AS DATE_UNLOCKED_FORMATTED", FALSE)
						->order_by('DATE_UNLOCKED DESC')
						->get_where('SMNTP_USER_UNLOCK_ACCOUNT_LOGS A', array('USER_ID' => $id));
		}
		
		public function get_user_status_history_body($id){
			if( ! $this->db->table_exists('SMNTP_USERS_STATUS_LOGS')){
				return false;
			}
			return $this->db->select("A.USER_ID, B.USER_STATUS_DESC AS USER_STATUS, CAST(DATE_FORMAT(A.DATE_MODIFIED,'%M %d,%Y %h:%i:%s %p') AS CHAR) AS DATE_MODIFIED", FALSE)
						->order_by('A.DATE_MODIFIED DESC')
						->join('SMNTP_USERS_STATUS_ID B','A.USER_STATUS_ID = B.ID','LEFT')
						->get_where('SMNTP_USERS_STATUS_LOGS A', array('USER_ID' => $id));
		}
		
		public function get_resend_logs($id){
			if( ! $this->db->table_exists('SMNTP_USERS_TOKENS_LOGS')){
				return false;
			}
			return $this->db->select("A.*, CAST(DATE_FORMAT(RESEND_DATE,'%M %d,%Y %h:%i:%s %p') AS CHAR) AS RESEND_DATE_FORMATTED", FALSE)
						->order_by('RESEND_DATE DESC')
						->get_where('SMNTP_USERS_TOKENS_LOGS A', array('USER_ID' => $id));
		}
		
		/*public function encrypt_password(){
			$this->load->library('CryptManager');
			$result = $this->db->get('SMNTP_CREDENTIALS')->result_array();
			$output = array();
			foreach($result as $val){
				$file_crypt_key  = substr($this->cryptmanager->init_padding(hash('md5', $val['TIME_STAMP'] . $val['USER_ID'])),0,16);
				
				$enc_password = $this->cryptmanager->encrypt($val['PASSWORD'], FALSE, $file_crypt_key);
				$decrypted = base64_decode($this->cryptmanager->decrypt($enc_password, FALSE, $file_crypt_key));
				
				if(!empty($val['PASSWORD'])){
					$this->db->where('USER_ID', $val['USER_ID']);
					//$this->db->where('PASSWORD !=', 'NULL', FALSE);
					$this->db->set('PASSWORD', $enc_password);
					$this->db->update('SMNTP_CREDENTIALS');
					
					$output[$val['USER_ID']] = array(
						'USERNAME' => $val['USERNAME'],
						'PASSWORD' => $val['PASSWORD'],
						'ENCRYPTED_PASS' => $enc_password,
						'DECRYPTED_PASS' => $decrypted,
						'EMPTY_PASS' => empty($val['PASSWORD']),
						'AFFECTED_ROWS' => $this->db->affected_rows()
					);
				}
				
			}
			return $output;
		}*/
		
		public function have_assigned_users($id){
			$user_data = $this->db->get_where('SMNTP_USERS', array('USER_ID' => $id))->row_array();
			// VRDSTAFF, VRDHEAD, GROUP HEAD, BUHEAD, FASHEAD
			$check_position = array(4, 5, 8, 3, 9);
			$user_position_id = @$user_data['POSITION_ID'];
			if(!empty($user_position_id) && in_array($user_position_id, $check_position)){
				
				$position = '';
				switch($user_position_id){
					case 4:
						$position = 'VRDSTAFF_ID';
						break;
					case 5:
						$position = 'VRDHEAD_ID';
						break;
					case 8:
						$position = 'GHEAD_ID';
						break;
					case 3:
						$position = 'BUHEAD_ID';
						break;
					case 9:
						$position = 'FASHEAD_ID';
						break;
					
				}
				$result['user_data'] = $user_data;
				$result['data'] = $this->db->query('SELECT DISTINCT A.USER_ID, USER_FIRST_NAME, USER_MIDDLE_NAME, USER_LAST_NAME 
				FROM SMNTP_USERS A LEFT JOIN SMNTP_USERS_MATRIX B ON A.USER_ID = B.USER_ID WHERE 
				B.' . $position . ' = ? AND A.USER_STATUS = 1 ORDER BY USER_FIRST_NAME', array($id))->result_array();
				
				return $result;
				//return $this->db->get_where('SMNTP_USERS_MATRIX', array($position => $id))->result_array();
			}
			return false;
		}
	}