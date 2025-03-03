<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 
*/
require APPPATH . '/libraries/REST_Controller.php';
class Cron_notification extends REST_Controller{	
    
	public $debug;
	// Load model in constructor
	public function __construct() {
		parent::__construct();		
		$this->debug = FALSE;
		$this->load->model('common_model');
		$this->load->model('mail_model');
    }

	function cron_archive_messages_get(){
		$this->load->helper('cron_helper');
		$where_arr 	= array('CONFIG_NAME' => 'message_retension_month(s)');
        $expire_day = $this->common_model->get_from_table_where_array('SMNTP_SYSTEM_CONFIG', 'CONFIG_VALUE', $where_arr);
		
		$start_date = "2018-01-01";

		$expiry = subtract_date(date('Y-m-d'),$expire_day);

        $record = array(
            'ACTIVE' => 0
            );
		$where = "ACTIVE = 1 AND DATE_CREATED BETWEEN DATE '". $start_date ."' AND DATE '".$expiry->format('Y-m-d')."'";
        $rs = $this->common_model->update_table('SMNTP_MESSAGES', $record, $where);
		$this->response($rs);
    }
}