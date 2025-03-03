<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Check_status extends REST_Controller {

	public function __construct() {
		parent::__construct();
			$this->load->model('vendor/check_status_model');
			$this->load->model('mail_model');
			$this->load->model('common_model');

	}

	function cron_check_put()
	{

		$res = $this->check_status_model->select_inprogress_vendor();
		$days = $this->check_status_model->select_number_of_days();
		$content = $this->check_status_model->select_query_or('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '17'),array('TEMPLATE_TYPE' => '18'),'TEMPLATE_TYPE,CONTENT');

		//$date_timestamp = date('m/d/Y h:i:s A');
		//$date_timestamp = DateTime::createFromFormat('m/d/Y h:i:s A', $date_timestamp);
		//$date_timestamp = $date_timestamp->format("d-M-y h.i.s.u A");
		$date_timestamp = date('Y-m-d H:i:s');

		$date_only = date('Y-m-d H:i:s');

		$datelist= array();
		$email_cnt = array();
		$adata = array();
		$total = 0;
		$x = date('y-m-d');//"18-02-04" format for testing to expired
		$yy =array();

		foreach ($content as $key => $value) {
			$email_cnt[$value['TEMPLATE_TYPE']] = $value['CONTENT'];
		}

		foreach ($days as $key => $value) {
			$datelist[$value['CONFIG_NAME']] = $value['CONFIG_VALUE'];
		}
		foreach ($res as $key => $value) {
			$total = (strtotime($x) - strtotime($value['PRIMARY_START_DATE']))/86400;

			//$extd = date("Y-m-d", strtotime($date_only. ' + '.$datelist['primary_requirement_extension'].' days'));
			if(empty($value['VENDOR_INVITE_ID'])){
				continue;
			}
			
			$vendor_status_logs = $this->check_status_model->select_query('SMNTP_VENDOR_STATUS_LOGS',array(
				'VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID'],
				'STATUS_ID'	=> 191
			),'STATUS_ID');
			
			
			if(count($vendor_status_logs) > 0){
				//Use primary_requirement_extension 7 Days
				$expiration_day = $datelist['primary_requirement_extension'];
			}else{
				//Use primary_requirement_deactivate 14 Days
				$expiration_day = $datelist['primary_requirement_deactivate'];
			}
			
			if($total >= $expiration_day){
				//scope
				/*if($total == ($datelist['primary_requirement_extension']) ){
					//$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('STATUS_ID' => '192'),array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']));
					//$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('EXTEND_FLAG' => '1', 'DATE_UPDATED'=> $date_only),array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']));
					//change status to primary extended
					$vendor_details = $this->check_status_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']),'EMAIL,VENDOR_NAME,USER_ID');
					$cnt = str_replace('[vendor_name]', $vendor_details[0]['VENDOR_NAME'], $email_cnt['17']);
					$cnt = str_replace('[add_date]', $extd, $cnt);

					$log_record1 = array(
						'VENDOR_INVITE_STATUS_ID' => $value['VENDOR_INVITE_STATUS_ID'],
						'VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID'],
						'STATUS_ID' => '192',
						'POSITION_ID' => $value['POSITION_ID'],
						'DATE_UPDATED' => $date_timestamp
						);

					$log_record2 = array(
						'USER_ID' => $vendor_details[0]['USER_ID'],
						'ACTION_ID' => '21',
						'DATE_CREATED' =>$date_timestamp
						);
					//$this->check_status_model->insert_query('SMNTP_VENDOR_STATUS_LOGS',$log_record1);
					//$this->check_status_model->insert_query('SMNTP_ACTION_LOGS',$log_record2);

					$send_data = array(
						'bcc' => '',
						'subject' => 'Failed to submit primary requirements.',
						'content' => nl2br($cnt),
						'to' =>  $vendor_details[0]['EMAIL']
						);

					//$this->common_model->send_email_notification($send_data); //Commented by Jay. No need na daw email kapag sa auto extension

					//--Jay Portal Message
					//Get message default for portal
					//192 = Primary Extended
					//$rs_msg = $this->common_model->get_message_default(array('TYPE_ID' => 1, 'STATUS_ID' => 192))->result_array()[0];
					
					$message = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $rs_msg['MESSAGE'] );
					$message = str_replace('[add_date]', $extd, $message);
					$topic =  str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $rs_msg['TOPIC']);
					$subject =  str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $rs_msg['SUBJECT']);
					
					$message_data['TYPE'] = 192; //192 = Primary Extended
					$message_data['SENDER_ID'] = $value['VENDOR_INVITE_ID'];
					$message_data['RFQRFB_ID'] = null; 
					$message_data['SUBJECT'] = $subject;
					$message_data['TOPIC'] = urlencode($topic);
					$message_data['BODY'] = urlencode($message);
					$message_data['DATE_SENT'] = date('d-M-Y h:i:s A');
					$message_data['VENDOR_ID'] = $vendor_details[0]['USER_ID'];
					$message_data['INVITE_ID'] = $value['VENDOR_INVITE_ID'];

					$message_data['RECIPIENT_ID'] = $vendor_details[0]['USER_ID'];
					//$model_data = $this->mail_model->send_message($message_data); //Commented by Jay. No need na daw email kapag sa auto extension
					//-------
				}*/
				$st = $expiration_day;
				// old $st = ($datelist['primary_requirement_extension'])+($datelist['primary_requirement_deactivate']);
				
				if($total >= $st) {
					$vendor_details = $this->check_status_model->select_query('SMNTP_VENDOR_INVITE',array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']),'EMAIL,VENDOR_NAME,USER_ID,CREATED_BY');
					
					//Jay nag uundefined offset
					if(!empty($vendor_details)){

						$this->common_model->expired_column('PRIMARY_EXPIRATION', date('m-d-Y'), $value['VENDOR_INVITE_ID']);

						$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('STATUS_ID' => '191'),array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']));
						//change status to primary deactivated
						$this->check_status_model->update_query('SMNTP_VENDOR_STATUS',array('PRIMARY_DEACTIVATED_FLAG' => '1', 'DATE_UPDATED'=> $date_only),array('VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID']));
						
						$head_details = $this->check_status_model->select_query('SMNTP_USERS',array('USER_ID' => $vendor_details[0]['CREATED_BY']),'USER_FIRST_NAME,USER_MIDDLE_NAME,USER_LAST_NAME,USER_EMAIL');
						$content2 = $this->check_status_model->select_query('SMNTP_EMAIL_DEFAULT_TEMPLATE',array('TEMPLATE_TYPE' => '25'),'TEMPLATE_TYPE,CONTENT');

						$recipient_full_name = $head_details[0]['USER_FIRST_NAME']
						. (!empty($head_details[0]['USER_MIDDLE_NAME']) ? ' ' . $head_details[0]['USER_MIDDLE_NAME'] : '') 
						. (!empty($head_details[0]['USER_LAST_NAME']) ? ' ' . $head_details[0]['USER_LAST_NAME'] : '') ;
				
						$content2 = str_replace('[head]', $recipient_full_name , $content2[0]['CONTENT']);
						$content2 = str_replace('[vendor_name]',  $vendor_details[0]['VENDOR_NAME'],$content2 );

						//$this->response($vendor_details);
						$log_record1 = array(
							'VENDOR_INVITE_STATUS_ID' => $value['VENDOR_INVITE_STATUS_ID'],
							'VENDOR_INVITE_ID' => $value['VENDOR_INVITE_ID'],
							'STATUS_ID' => '191',
							'POSITION_ID' => $value['POSITION_ID'],
							'DATE_UPDATED' => $date_timestamp
							);
						$log_record2 = array(
							'USER_ID' => $vendor_details[0]['USER_ID'],
							'ACTION_ID' => '26',
							'DATE_CREATED' =>$date_timestamp
							);
						$this->check_status_model->insert_query('SMNTP_VENDOR_STATUS_LOGS',$log_record1);
						$this->check_status_model->insert_query('SMNTP_ACTION_LOGS',$log_record2);
						$this->check_status_model->update_query('SMNTP_CREDENTIALS',array('DEACTIVATED_FLAG' => '1'),array('USER_ID' => $vendor_details[0]['USER_ID']));
						$cnt = str_replace('[vendor_name]', $vendor_details[0]['VENDOR_NAME'], $email_cnt['18']);

						$send_data = array(
							'subject' => 'Deactivated Due to Non-submission of Primary',
							'content' => nl2br($cnt) ,
							'to' =>  $vendor_details[0]['EMAIL']
							);

						$send_data2 = array(
							'subject' => $vendor_details[0]['VENDOR_NAME'] . ' - User Account Deactivated',
							'content' => nl2br($content2),
							'to' =>  $head_details[0]['USER_EMAIL']
							);
						//$this->response($send_data);
						$this->common_model->send_email_notification($send_data);
						$this->common_model->send_email_notification($send_data2);
						
						//--Jay Portal Message
						//Get message default for portal
						// 191 = Deactivated Primary
						$rs_msg = $this->common_model->get_message_default(array('TYPE_ID' => 1, 'STATUS_ID' => 191))->result_array()[0];
						
						$message = str_replace('[recipient]', $recipient_full_name, $rs_msg['MESSAGE'] );
						$message = str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $message );
						$topic =  str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $rs_msg['TOPIC']);
						$subject =  str_replace('[vendorname]', $vendor_details[0]['VENDOR_NAME'], $rs_msg['SUBJECT']);
						
						$message_data['TYPE'] = 'notification'; // 191 = Deactivated Primary
						$message_data['SENDER_ID'] = $vendor_details[0]['CREATED_BY'];
						$message_data['RFQRFB_ID'] = null; 
						$message_data['SUBJECT'] = $subject;
						$message_data['TOPIC'] = urlencode($topic);
						$message_data['BODY'] = urlencode($message);
						$message_data['DATE_SENT'] = date('Y-m-d H:i:s');
						$message_data['VENDOR_ID'] = $vendor_details[0]['USER_ID'];
						$message_data['INVITE_ID'] = $value['VENDOR_INVITE_ID'];

						$message_data['RECIPIENT_ID'] = $vendor_details[0]['CREATED_BY'];
						$model_data = $this->mail_model->send_message($message_data);
						//---
						
					}
					
				}
			}
		}

		//$this->response($datelist['61']);
	}
}
