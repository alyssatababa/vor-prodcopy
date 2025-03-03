<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
require APPPATH . '/libraries/REST_Controller.php';
class Common_api extends REST_Controller
{

	// Load model in constructor
	public function __construct() {
		parent::__construct();
		$this->load->model('common_model');
	}

	public function find_similar_get()
	{
		$email 			= $this->get('txt_email');
		$contact_person = $this->get('txt_contact_person');
		$vendorname 	= $this->get('txt_vendorname');

		$var = [
				'email' 			=> $email,
				'contact_person' 	=> $contact_person,
				'vendorname' 		=> $vendorname

			 ];

		$data = $this->common_model->get_similar_list($var);

		$this->response($data);
	}

	public function token_info_get()
	{
		$token = $this->get('token');

		$data = $this->common_model->token_info($token);
		$data['creatordata'] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['query'][0]['CREATED_BY']), '*');
		$where_arr = array('CONFIG_NAME' => 'invite_expiration_days');
		$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		$data['expire_day'] = $expire_day;

		$this->response($data);
	}
	
	public function user_token_info_get()
	{
		$token = $this->get('token');

		$data = $this->common_model->user_token_info($token);
		$data['user_id'] = $this->common_model->select_query('SMNTP_USERS_TOKENS',array('TOKEN' => $token), '*');
		
		if( ! empty($data['user_id'])){
			$data['user_id'] = $data['user_id'][0]['USER_ID'];
			$data['email'] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id']), 'USER_EMAIL')[0]['USER_EMAIL'];
		}
		
		
		$where_arr = array('CONFIG_NAME' => 'invite_expiration_days');
		$expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		$data['expire_day'] = $expire_day;

		$this->response($data);
	}
	
	public function set_password_put(){
		$data['password'] 	= $this->put('password');
		$data['token'] 		= $this->put('token');

		$where_arr2 = array('TOKEN' => $data['token']);
		$data['user_id'] = $this->common_model->get_from_table_where_array('SMNTP_USERS_TOKENS', 'USER_ID', $where_arr2);

		$data['username'] = $this->put('username');
		$rs = $this->common_model->reset_password($data);
		
		if($rs){
			$this->common_model->deactive_user_token($data['token']);
			$data['status'] = TRUE;
			$data['error'] = '';
			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}
	
	public function reset_pw_put()
	{
		$data['password'] 	= $this->put('password');
		$data['token'] 		= $this->put('token');
		$data['invite_id'] 	= $this->put('invite_id');

		$where_arr2 = array('TOKEN' => $data['token']);
		$data['user_id'] = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_TOKEN', 'USER_ID', $where_arr2);

		if(empty($data['invite_id'])){
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
		$data['username'] = $this->put('username');
		$rs = $this->common_model->reset_password($data);

		if ($rs)
		{
			$record = array(
					'STATUS_ID' 	=> 8, //balik ulet di nagkaunawaan //9, //In Process  //before 8 = invite accepted
					'PRIMARY_START_DATE' => date('Y-m-d H:i:s')
				);
			$where = array(
					'VENDOR_INVITE_ID' => $data['invite_id']
				);
			$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record, $where);
//-->Marc
			$this->load->model('vendor/check_status_model');
			$userdetails = $this->check_status_model->select_query('SMNTP_VENDOR_INVITE',$where,'EMAIL,VENDOR_NAME');
			$date_exp = $this->check_status_model->select_query('SMNTP_SYSTEM_CONFIG',array('CONFIG_NAME' => 'primary_requirement_deactivate'),'CONFIG_VALUE');
			$content = $this->check_status_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '20'),'CONTENT');

			$cnt = str_replace('[vendor_name]', $userdetails[0]['VENDOR_NAME'], $content[0]['CONTENT']);
			$edate = date('F d,Y',strtotime("+".$date_exp[0]['CONFIG_VALUE']. " days"));
			//$your_date = strtotime($edate);
			//$current_time = time(); 
			//$datediff = $your_date - $current_time;
			//$total_day = round($datediff / (60 * 60 * 24));
			//$td =  $total_day . ' days';
			$cnt = str_replace('[submission_deadline]', $edate, $cnt);
			$data['userdetails'] = $edate;
					$send_data = array(
					'bcc' => '',
					'subject' =>  'Primary Requirements Submission',
					'content' => nl2br($cnt),
					'to' =>  $userdetails[0]['EMAIL']
					);
					
			$this->common_model->send_email_notification($send_data);
//<-- end Marc

			//Jay- Send Portal Message
			$data['submission_deadline'] 	= $edate;
			$data['vendor_info'] = $this->common_model->select_query('SMNTP_USERS',array('USER_ID' => $data['user_id'] ),'*');
		
			
			//End
			$this->common_model->deactive_token($data['token']);

			$data['status'] = TRUE;
			$data['error'] = '';

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}

	}

	public function expired_invite_put() // expired na ung invitation
	{
		$invite_id  = $this->put('invite_id');
		$token 		= $this->put('token');

		//get id of invite creator then get its positionid start
		$where_arr = ['VENDOR_INVITE_ID' => $invite_id];
		$creator_id = $this->common_model->get_from_table_where_array('SMNTP_VENDOR_INVITE', 'CREATED_BY', $where_arr);
		$where_id = ['USER_ID' => $creator_id];
		$position_id = $this->common_model->get_from_table_where_array('SMNTP_USERS', 'POSITION_ID', $where_id);

		//get id of invite creator then get its positionid end

		$record = array(
				'STATUS_ID' 	=> 5, // expired invite
				'POSITION_ID' 	=> $position_id // send to creator positionid
			);
		$where = array(
				'VENDOR_INVITE_ID' => $invite_id
			);
		$rs = $this->common_model->update_table('SMNTP_VENDOR_STATUS', $record, $where);



		if ($rs)
		{
			$data['status'] = TRUE;
			$data['error'] = '';
			$this->common_model->deactive_token($token);

			$this->response($data);
		}
		else
		{
			$data['status'] = FALSE;
			$data['error'] = 'Something went wrong!';
			$this->response($data);
		}
	}

	public function position_name_get()
	{
		$position_id			= $this->get('position_id');

		$positionname = $this->common_model->get_position_name($position_id);

		$this->response($positionname);
	}

	public function userdata_validation_get()
	{
		$user_data = $this->get('user_data');
		$email_data['base_url'] = $this->get('base_url');

		$model_data = $this->common_model->get_userdata_validation($user_data);

		$email_data['users_name'] = $model_data['users_name'];
		$email_data['username'] = $model_data['username'];
		$email_data['user_email'] = $model_data['user_email'];
		$email_data['err_code'] = $model_data['err_code'];
		$email_data['user_id'] = $model_data['user_id'];

		if ($model_data['err_code'] === '1') {
			$this->generate_reset_pass_email($email_data);
		}

		$this->response($model_data['err_code']);
	}

	public function validate_update_password_get()
	{
		$user_id = $this->get('user_id');
		$c_password = $this->get('c_password');
		$n_password = $this->get('n_password');
		$email_data['base_url'] = $this->get('base_url');

		//$model_data = $this->common_model->get_userdata_validation($c_password, $n_password);
		$model_data = $this->common_model->get_change_pass_response($user_id, $c_password, $n_password);
		//$this->response($model_data['err_code']);
		$this->response($model_data);
	}

	public function validate_update_email_get()
	{
		$user_id = $this->get('user_id');
		$c_password = $this->get('c_password');
		$n_password = $this->get('n_password');
		$email_data['base_url'] = $this->get('base_url');

		//$model_data = $this->common_model->get_userdata_validation($c_password, $n_password);
		$model_data = $this->common_model->get_change_email_response($user_id, $c_password, $n_password);
		//$this->response($model_data['err_code']);
		$this->response($model_data);
	}

	public function generate_reset_pass_email($data)
	{
		$token = $this->common_model->create_token($data['user_id']);
		$rs = $this->common_model->get_email_template(['TEMPLATE_TYPE' => 7]);
		$email_data['content'] = $rs->row()->CONTENT;

		$reset_pass_url = $data['base_url'].'index.php/forgot_password/index/'.$data['user_id'].'/'.$token;
		$reset_pass_url = '<a href="' . $reset_pass_url . '">' . $reset_pass_url . '</a>';
		$email_data['content'] = str_replace('[name]', $data['users_name'], $email_data['content']);
		$email_data['content'] = str_replace('[username]', $data['username'], $email_data['content']);
		$email_data['content'] = str_replace('[reset_url]', $reset_pass_url, $email_data['content']);


		$email_data['to'] = $data['user_email'];
		//$email_data['bcc'] = '';
		$email_data['subject'] = 'Password Reset';
		$email_data['content'] = nl2br($email_data['content']);

		$this->common_model->send_email_notification($email_data);
	}

	public function users_name_get()
	{
		$user_id = $this->get('user_id');

		$users_name = $this->common_model->get_users_name($user_id);

		$this->response($users_name);
	}
	
	public function username_get()
	{
		$user_id = $this->get('user_id');

		$users_name = $this->common_model->get_username($user_id);

		$this->response($users_name);
	}

	public function valid_token_get()
	{
		$token = $this->get('token');

		$err_code = $this->common_model->is_token_active($token);

		$this->response($err_code);
	}

	public function reset_password_put()
	{
		$data['user_id'] = $this->put('user_id');
		$data['password'] = $this->put('new_password');
		$data['username'] = $this->common_model->get_username($data['user_id']);

		$this->common_model->reset_password($data);
		$this->common_model->deactivate_token($data['user_id']);

		$this->response('');
	}

	// Gets status of RFQ/RFB and Vendor
	public function status_get()
	{
		$rs_arr = $this->common_model->get_status();

		$this->response($rs_arr);
	}
	public function srvDate_get()
	{
		$sql ="SELECT NOW() AS NOW";

		$query = $this->db->query($sql);
//

		$this->response($query->result_array());

	}
	
	public function send_email_message_post(){
		$email_data['to'] 		= $this->post('to');
		//$email_data['bcc']  	= '';
		$email_data['subject'] 	= $this->post('subject');
		$email_data['content'] 	= nl2br($this->post('content'));
		$result = $this->common_model->send_email_notification($email_data);
		$this->response($result);
	}
	
	public function get_config_get(){
		$result = $this->common_model->get_config(array(
			'CONFIG_NAME'	=> 'no_js_message'
		))->row_array();
		
		$this->response($result);
	}
	
	public function log_user_post(){
		$data 		= $this->post();
		$result = $this->common_model->log_user($data);
		$this->response($result);
	}
}
?>
