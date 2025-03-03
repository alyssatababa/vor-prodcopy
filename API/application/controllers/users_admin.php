<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class Users_admin extends REST_Controller {
	
		public function __construct() {
			parent::__construct();
			$this->load->model('users_admin_model');
			$this->load->model('vendor/inviteapproval_model');
		}
	
	
		public function adduser_post()
		{	

		$this->load->model('common_model');
		$send = $this->post('snd');
		$data = array(
		'USER_FIRST_NAME' => $this->post('f_name'),
		'USER_MIDDLE_NAME' => $this->post('m_name'),
		'USER_LAST_NAME' => $this->post('l_name'),
		'USER_MOBILE' =>  $this->post('mob'),
		'USER_TYPE_ID' =>  $this->post('u_type'),
		'POSITION_ID' =>  $this->post('p_type'),
		'USER_EMAIL' =>  $this->post('e_mails'),
		'USER_STATUS' => '1'
		);
		
		$x =  $this->post('e_mails');
		
		$data2 = array(
		'l_id' => $this->post('log_id'),
		'l_pass' => $this->post('l_pass')
		);
		
		$approver['a_type'] = $this->post('a_type');	
		$approver['id'] = $this->post('approver');		
		$result = $this->users_admin_model->m_create_user($data,$data2,$approver);

		$content = 'The login ID "'.$data2['l_id'].'" has been created and linked to this email address. This email will receive all notifications related to this login ID. <br> If you believe this is an error, please contact your system administrator..';

			if(($result == true)AND($this->post('snd') == 1)){

				$send_data = array(
					'subject' => 'SMNTP Account Creation Success',
					'content' => '1234',
					'to' => $x
					);

				$res = $this->common_model->send_email_notification($send_data);
			}



			
		
			$this->response([
			'data' => $result
			]);
	
		}
		
		public function searchuser_post()
		{

				$data = array();

		$z = $this->post('u_type');

		if($z == 1){
				$data = array(
						'u_name' => $this->post('u_name'),
						'u_id' => $this->post('u_id'),
						'u_type' => $z
					);
		}
		if($z == 2){
				$data = array(
						'u_id' => $this->post('u_id'),
						'u_type' => $z
					);
		}
		if($z == 3){
				$data = array(
						'u_name' => $this->post('u_name'),
						'u_type' => $z
					);
		}
		
			
			$result = $this->users_admin_model->m_search_data($data);
			$this->response(['data' => $result]);
		}
		
		public function get_user_type_post()
		{
			$result = $this->users_admin_model->m_get_user_type();		
			$this->response(['data' => $result]);
			
		}
		
		public function get_position_post()
		{
			
			$sID = $this->post('sID');
			$result = $this->users_admin_model->m_get_position($sID);		
			$this->response($result);
			
		}
			public function get_info_get()
		{
			
			$sID = $this->get('USER_ID');
			$result = $this->users_admin_model->m_get_info($sID);		
			$this->response(['data' => $result]);			
		}
		
			
		public function edit_user_post()
		{	
		
		$data = array(
		'USER_FIRST_NAME' => $this->post('f_name'),
		'USER_MIDDLE_NAME' => $this->post('m_name'),
		'USER_LAST_NAME' => $this->post('l_name'),
		'USER_MOBILE' =>  $this->post('mob'),
		'USER_TYPE_ID' =>  $this->post('u_type'),
		'POSITION_ID' =>  $this->post('p_type'),
		'USER_EMAIL' =>  $this->post('e_mails'),
		'USER_STATUS' => '1'
		);
		$approver['a_type'] = $this->post('a_type');	
		$approver['id'] = $this->post('approver');		
		
		$data2 = array(
		'PASSWORD' => $this->post('l_pass')
		);
		
		$data3 = $this->post('adat');
		
				
			$result = $this->users_admin_model->m_update_user($data,$data2,$data3,$approver);
			
	
			$this->response([
			'data' => $result
			]);
	
		}

		public function try_compare_get()
		{


			$data = array(
					'USERNAME' =>$this->get('USERNAME')
				);
			$result = $this->users_admin_model->m_checkuser($data);
			$this->response([
			'data' => $result
			]);
		}

		public function delete_user_post()
		{

			$d = $this->post('USER_ID');
			
			$check_inviters_result = $this->users_admin_model->have_assigned_users($d);
			if(is_array($check_inviters_result['data']) &&  !empty($check_inviters_result['data'])){
				$this->response([
					'user_data' => $check_inviters_result['user_data'],
					'data' => $check_inviters_result['data'],
					'total' => count($check_inviters_result['data'])
				]);
			}else{
					
				$result = $this->users_admin_model->m_del_user($d);
				$this->response([
					'data' => $result
				]);
			}
			

		}

		public function approver_get()
		{

			$data['POSITION_ID'] = $this->get('POSITION_ID');
			$result = $this->users_admin_model->get_approvers($data);
			$this->response($result);
		}

		public function category_get()
		{

			$data = array(
				'CATEGORY_NAME' => $this->get('CATEGORY_NAME')
				);
			
			$vendor_type = NULL;
			if(!empty( $this->get('VENDOR_TYPE'))){
				$vendor_type = $this->get('VENDOR_TYPE');
			}
			$result = $this->users_admin_model->get_category($data, $vendor_type);
			$this->response($result);
		}
		
		//old
		public function new_user_category_post(){
			$data['CAT'] = $this->post('CAT');
			$data['user_names'] = $this->post('user_names');
			$result = $this->users_admin_model->insert_user_category($data);
			
			$this->response($result);
		}
		//for bulk
		public function new_user_bulk_category_post(){
			$data['categories'] = $this->post('data');
			$data['CAT'] = $this->post('data');
			$data['user_names'] = $this->post('user_names');
			foreach($data['categories'] as $d){
				$data['CAT'][] 		= $d['CAT']; 
				$data['user_names'] = $d['USER_NAME']; 
			}
			$result = $this->users_admin_model->insert_user_bulk_category($data);
			
			$this->db->response($result);
		}
		
		public function new_user_post()
		{
			$vrd = $this->post('VRD');
			$this->load->model('common_model');
			$snd =  $this->post('snd');
			$vrdhead = $this->post('nvrdh');


		
			$data = array(
			'USER_FIRST_NAME' => strtoupper($this->post('USER_FIRST_NAME')),
			'USER_MIDDLE_NAME' => strtoupper($this->post('USER_MIDDLE_NAME')),
			'USER_LAST_NAME' => strtoupper($this->post('USER_LAST_NAME')),
			'USER_MOBILE' =>$this->post('USER_MOBILE'),
			'USER_EMAIL' =>$this->post('USER_EMAIL'),
			'USERNAME' =>$this->post('USER_NAME'),
			//'PASSWORD' => $this->post('PASSWORD'),
			'POSITION_ID' => $this->post('POSITION_ID'),
			'VRDHEAD_ID'=> $this->post('VRDHEAD_ID'),
			'VRDSTAFF_ID'=> $this->post('VRDSTAFF_ID'),
			'BUHEAD_ID'=> $this->post('BUHEAD_ID'),
			'GHEAD_ID'=> $this->post('GHEAD_ID'),
			'FASHEAD_ID'=> $this->post('FASHEAD_ID'),
			'CAT'=> $this->post('CAT'),
			'surl' => $this->post('surl')
			);


			$result = $this->users_admin_model->new_user($data,$vrd,$vrdhead);

			$this->response($result);

			if($result != 'exist' && $this->post('snd') == 1){
				$content = 'Hi, ' . $this->post('USER_FIRST_NAME') . ' ' . $this->post('USER_LAST_NAME') . '<br/><br/>
							The login ID "'.$data['USERNAME'].'" has been created and linked to 
							this email address. 
							<br/><br/>
							This email will receive all notifications related to 
							this login ID. 
							<br/><br/>
							If you believe this is an error, please contact your 
							system administrator.
							<br/><br/>
							Thank you,<br/>
							SM Vendor Portal Admin ';

				$send_data = array(
					'subject' => 'SMNTP Account Creation Success',
					'content' => $content,
					'to' => $data['USER_EMAIL']
				);

				$res = $this->common_model->send_email_notification($send_data);
			}
			$this->response($result);
		}
		
		
		/*public function new_user_batch_post()
		{
			$this->load->model('common_model');
			
			$data = $this->post('csv_array');
			$user_id = $this->post('user_id');
			$result['csv_array'] = $data;
			$result['failed'] = 0;
			$result['success'] = 0;
			$result['duplicate'] = 0;
			
			$surl = $this->post('surl');
			
			$test;
			foreach($data as $key => $value){
					
				$vrd = false;
				$vrdhead = false;
				$data = array(
					'USER_FIRST_NAME' => $value['NAME'],
					'USER_MIDDLE_NAME' => '',
					'USER_LAST_NAME' => '',
					'USER_MOBILE' => '123456789',
					'USER_EMAIL' => 'justine.jovero@novawaresystems.com',//$value['E-EMAIL_ADDRESS'],
					'USERNAME' => $value['ADID'],
					'POSITION_ID' => $value['position_id'],
					'VRDHEAD_ID'=> '',
					'VRDSTAFF_ID'=> '',
					'BUHEAD_ID'=> '',
					'GHEAD_ID'=> '',
					'FASHEAD_ID'=> '',
					'CAT'=>'',
					'surl' => $surl
				);
				
				//inviters
				if($value['position_id'] == 2 || $value['position_id'] == 7 || $value['position_id'] == 11){
					if(($value['position_id'] == 2 || ($value['position_id'] == 11){
						$data['BUHEAD_ID'];
					}else if(($value['position_id'] == 7){
						$data['FASHEAD_ID'];
						$data['GHEAD_ID'];
					}
					
					$data['CAT'] = join('|', $value['categories']);
					
					$vrd = $value['vrd_staffs'];
					$vrdhead = $value['vrd_heads'];
				}
				$test[] = $data;
				//$add_result = $this->users_admin_model->new_user($data,$vrd,$vrdhead);


				/*if($result != 'exist' && $this->post('snd') == 1){
					$content = 'Hi, ' . $this->post('USER_FIRST_NAME') . ' ' . $this->post('USER_LAST_NAME') . '<br/><br/>
								The login ID "'.$data['USERNAME'].'" has been created and linked to 
								this email address. 
								<br/><br/>
								This email will receive all notifications related to 
								this login ID. 
								<br/><br/>
								If you believe this is an error, please contact your 
								system administrator.
								<br/><br/>
								Thank you,<br/>
								SM Vendor Portal Admin ';

					$send_data = array(
						'subject' => 'SMNTP Account Creation Success',
						'content' => $content,
						'to' => $data['USER_EMAIL']
					);

					$res = $this->common_model->send_email_notification($send_data);
				}
			}

			$this->response($test);
		}**/
		
		
		public function get_user_id_get()
		{
			$username = $this->get('USER_NAME');
			//$this->response($username);
			$result = $this->users_admin_model->get_user_id($username);
			$this->response($result);
		}
		public function get_credentials_get()
		{
			$username = $this->get('USER_NAME');
			//$this->response($username);
			$result = $this->users_admin_model->get_credentials($username);
			$this->response($result);
		}
		
		public function max_vrd_get()
		{

			$result = $this->users_admin_model->get_vrdstaff();
			$this->response($result);


		}

		public function searchuser_get()
		{
			
			$data['SEARCH'] = $this->get('SEARCH');
			$data['TYPE'] = $this->get('TYPE');
			$data['START'] = $this->get('START');
			$data['S_TYPE'] = $this->get('S_TYPE');
			$data['SORT'] = $this->get('SORT');
			$result = $this->users_admin_model->searchuser($data);
			$this->response($result);
		}

		public function searchcat_get()
		{
			$data['USER_ID'] = $this->get('USER_ID');

			$result = $this->users_admin_model->select_category_id($data);
			$this->response($result);

		}
		

		public function edit_new_user_post()
		{
			$data = array(
			'USER_FIRST_NAME' => strtoupper($this->post('USER_FIRST_NAME')),
			'USER_MIDDLE_NAME' => strtoupper($this->post('USER_MIDDLE_NAME')),
			'USER_LAST_NAME' => strtoupper($this->post('USER_LAST_NAME')),
			'USER_MOBILE' =>$this->post('USER_MOBILE'),
			'USER_EMAIL' =>$this->post('USER_EMAIL'),
			'USER_ID' =>$this->post('USER_ID'),
			'PASSWORD' => $this->post('PASSWORD'),
			'POSITION_ID' => $this->post('POSITION_ID'),
			'USER_STATUS' => $this->post('USER_STATUS'),
			'VRDHEAD_ID'=> $this->post('VRDHEAD_ID'),
			'VRDSTAFF_ID'=> $this->post('VRDSTAFF_ID'),
			'BUHEAD_ID'=> $this->post('BUHEAD_ID'),
			'GHEAD_ID'=> $this->post('GHEAD_ID'),
			'FASHEAD_ID'=> $this->post('FASHEAD_ID'),
			'CAT'=> $this->post('CAT')
			);

			$vrd = $this->post('VRD');
			$vrdh = $this->post('nvrdh');

			$result = $this->users_admin_model->update_user($data,$vrd,$vrdh);
			$this->response($result);
		}

		public function view_user_info_get()
		{
			$uid = array('USER_ID' => $this->get('USER_ID')

			 );

			$result = $this->users_admin_model->view_edit_user($uid);

			$this->response($result);
		}
		
		public function view_pending_records_get(){	
			$uid = array('SVI.CREATED_BY' => $this->get('USER_ID'));
			$result = $this->users_admin_model->view_pending_records($uid);
			$this->response($result);
		}

		//jay bulk
		public function new_user2_post()
		{
			$vrd = $this->post('VRD');
			$this->load->model('common_model');
			$snd =  $this->post('snd');
			$vrdhead = $this->post('nvrdh');


		
			$data = array(
			'USER_FIRST_NAME' => $this->post('USER_FIRST_NAME'),
			'USER_MIDDLE_NAME' => $this->post('USER_MIDDLE_NAME'),
			'USER_LAST_NAME' => $this->post('USER_LAST_NAME'),
			'USER_MOBILE' =>$this->post('USER_MOBILE'),
			'USER_EMAIL' =>$this->post('USER_EMAIL'),
			'USERNAME' =>$this->post('USER_NAME'),
			//'PASSWORD' => $this->post('PASSWORD'),
			'POSITION_ID' => $this->post('POSITION_ID'),
			'VRDHEAD_ID'=> $this->post('VRDHEAD_ID'),
			'VRDSTAFF_ID'=> $this->post('VRDSTAFF_ID'),
			'BUHEAD_ID'=> $this->post('BUHEAD_ID'),
			'GHEAD_ID'=> $this->post('GHEAD_ID'),
			'FASHEAD_ID'=> $this->post('FASHEAD_ID'),
			'CAT'=> $this->post('CAT'),
			'surl' => $this->post('surl')
			);

			//$this->response($vrdhead);

			//$this->response($data);
			///return;

			$result = $this->users_admin_model->bulk_new_user($data,$vrd,$vrdhead);

			$this->response($result);

			if($result != 'exist' && $this->post('snd') == 1){
				$content = 'Hi, ' . $this->post('USER_FIRST_NAME') . ' ' . $this->post('USER_LAST_NAME') . '<br/><br/>
							The login ID "'.$data['USERNAME'].'" has been created and linked to 
							this email address. 
							<br/><br/>
							This email will receive all notifications related to 
							this login ID. 
							<br/><br/>
							If you believe this is an error, please contact your 
							system administrator.
							<br/><br/>
							Thank you,<br/>
							SM Vendor Portal Admin ';

				$send_data = array(
					'subject' => 'SMNTP Account Creation Success',
					'content' => $content,
					'to' => 'pagaraojustineprice@yahoo.com'
				);

				$res = $this->common_model->send_email_notification($send_data);
			}
			//$this->response($res);
		}
		
		

		public function insert_category(){
			
			$categories = $this->post('CAT');
			$result = $this->users_admin_model->add_categories($categories);

			$this->response($result);
			
		}
		
		public function get_uploader_data_id_get(){
			$this->load->model('vendor_form_model');
			$data = $this->get();
			
			//approver
			foreach($data['head'] as $key => $value){
				if( ! empty($value)){
					$data['head'][$key] = $this->users_admin_model->get_user_id($value);
				}
			}
			
			//vrd staffs
			foreach($data['vrd'] as $key => $value){
				if( ! empty($value)){
					$data['vrd'][$key] = $this->users_admin_model->get_user_id($value);
				}
			}
			
			//vrd head
			foreach($data['vrdhead'] as $key => $value){
				if( ! empty($value)){
					$data['vrdhead'][$key] = $this->users_admin_model->get_user_id($value);
				}
			}
			//categories
			foreach($data['category'] as $key => $value){
				if( ! empty($value)){
					$data['category'][$key] = $this->vendor_form_model->get_category(array(strtoupper($value), $data['vendor_type_id']));
					
					if(empty($data['category'][$key])){
						//add category
						$res = $this->vendor_form_model->uploader_create_new_category(array(
							'CATEGORY_NAME'	=> $value,
							'DESCRIPTION'	=> $value,
							'BUSINESS_TYPE'	=> $data['vendor_type_id'],
							'CREATED_BY'	=> $data['user_id'],
						));
						
						if($res){
							$data['category'][$key] = $res;
						}
					}else{
						$data['category'][$key] = $data['category'][$key][0]['CATEGORY_ID'];
					}
				}
			}
			$this->response($data);
		}
		

		public function resend_email_post(){

			$user_id = $this->post('USER_ID');
			$usern_name = $this->post('USER_NAME');
			$burl = $this->post('burl');

			$res = $this->users_admin_model->resend_email($user_id,$usern_name,$burl);



			$this->response($res);
		}
		
		public function unlock_account_post(){
			$data = $this->post();
			$res = $this->users_admin_model->unlock_account($data);

			$this->response($res);
		}

		public function get_unlock_account_logs_get(){
			$data = $this->get();
			$res = $this->users_admin_model->get_unlock_account_logs($data['uid']);
			if( ! is_bool($res)){
				$res = $res->result_array();
			}
			$this->response($res);
		}

		public function get_user_status_history_body_get(){
			$data = $this->get();
			$res = $this->users_admin_model->get_user_status_history_body($data['uid']);
			if( ! is_bool($res)){
				$res = $res->result_array();
			}
			$this->response($res);
		}
		
		public function get_resend_logs_get(){
			$data = $this->get();
			$res = $this->users_admin_model->get_resend_logs($data['uid']);
			if( ! is_bool($res)){
				$res = $res->result_array();
			}
			$this->response($res);
		}
		
		public function encrypt_password_post(){
			
			$this->response($this->users_admin_model->encrypt_password());
		}
}