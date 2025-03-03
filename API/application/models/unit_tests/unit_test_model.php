<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
*
*/
class Unit_test_model extends CI_Model
{
	public function get_table_info($table_name){
		// $query = $this->db->get('ALL_TAB_COLS');
		// $query = $this->db->query('SELECT * FROM USER_TAB_COLUMNS');
		$this->db->select('TABLE_NAME,COLUMN_NAME,DATA_TYPE,DATA_LENGTH,DATA_PRECISION,DATA_SCALE,NULLABLE,DATA_DEFAULT, CHAR_USED, CHAR_LENGTH');
		$query = $this->db->get_where('USER_TAB_COLS', array('TABLE_NAME'=> strtoupper($table_name)));
		//log_message('debug', $this->db->last_query());
		// var_dump($query);
		return $query->result_array();
	}

	public function get_table_content($table_name, $select, $where){
		$this->db->select($select);
		$this->db->where($where);
		$query = $this->db->get_where($table_name);
		return $query->result_array();
	}

	function send_email_notification($data)
	{

		//-----> config for testing in local (comment on live)
		// $config = array(
		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'ssl://mail.sandmansystems.com',
		// 	'smtp_port' => 465,
		// 	'smtp_user' => 'support@sandmansystems.com',
		// 	'smtp_pass' => 'sandman0198',
		// 	'mailtype' => 'html',
		// 	'charset' => 'iso-8859-1',
		// 	'newline' => '\r\n',
		// 	'wordwrap' => TRUE);

//		 PORDUCTION config
		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => '10.111.121.203',
			'smtp_port' => 25,
			'smtp_user' => 'no-reply@smvendorportal.com',
			'smtp_pass' => 'sandman0198',
			'mailtype' => 'html',
			'charset' => 'utf-8',
			'newline' => '\r\n',
			'wordwrap' => TRUE);

			
		// $config = Array(
		// 	'protocol' => 'smtp',
		// 	'smtp_host' => 'ssl://smtp.googlemail.com',
		// 	'smtp_port' => 465,
		// 	'smtp_user' => 'joebrt.beltran@gmail.com',
		// 	'smtp_pass' => '3pkbzfze7og7',
		// 	'mailtype' => 'html',
		// 	'charset' => 'utf-8',
		// 	'newline' => '\r\n',
		// 	'wordwrap' => TRUE);



		$this->load->library('email', $config);
		$this->email->clear(true);
		// $this->email->from('support@sandmansystems.com');
		$this->email->from('no-reply@smvendorportal.com'); //for prod by jay
		// $this->email->from('joebrt.beltran@gmail.com'); 
		$this->email->set_newline("\r\n");
		$this->email->set_crlf("\r\n"); //uncomment for prod
		$this->email->to($data['to']);

		$has_gmail = false;
		if (array_key_exists('cc', $data)) {
			$this->email->cc($data['cc']);			
			if (strpos($data['cc'], 'gmail.com') !== false)
				$has_gmail = true;
		}
		if (array_key_exists('bcc', $data)){
			$this->email->bcc($data['bcc']);
			if (strpos($data['bcc'], 'gmail.com') !== false)
				$has_gmail = true;
		}

		if (array_key_exists('attach', $data))
		{
			for ($i=0; $i < count($data['attach']); $i++)
			{
				$this->email->attach($data['attach'][$i]);
			}
		}

		if (array_key_exists('to', $data)) {
			if (strpos($data['to'], 'gmail.com') !== false)
				$has_gmail = true;

			// trim subject if gmail exists as a recepient
			if ($has_gmail==true) {
				$this->email->subject(substr($data['subject'],0,57)."...");
			} else {
				$this->email->subject($data['subject']);
			}
			$this->email->message($data['content']);
			$this->email->send();
		}

		$result = 'test';
		$result = $this->email->print_debugger();
		// $this->email->clear(true);
		$data['attach'] = null;
		$data['attach'] = array();

		return $result;
	}
}
?>
