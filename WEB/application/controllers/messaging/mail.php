<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Mail extends CI_Controller
	{
		/*
			$type can be 'vendor', 'invite', 'rfqrfb'
			$id is the id
		*/
		function index($id = null, $type = null)
		{
			$data['data_id'] = $id;
			$data['data_type'] = $type;
			$data['user_type_id'] = $this->session->userdata('user_type_id');

			$this->load->view('messaging/mail', $data);
		}

		function get_unread_count()
		{
			$get_data['user_id'] = $this->session->userdata('user_id');

			$unread = $this->rest_app->get('index.php/mail/unread_count', $get_data);

			if ($this->input->get('check_unread')) {
				echo json_encode(['unread_count' => $this->security->xss_clean($unread->count)]);
			} else {
				return $unread->count;
			}
		}

		function get_messages_data($data_id = 0)
		{
			$get_data['user_id'] = $this->session->userdata('user_id');
			$get_data['user_type_id'] = $this->session->userdata('user_type_id');
			$get_data['position_id'] = $this->session->userdata('position_id'); 
			$get_data['data_id'] = $data_id;
		
			$vendor_subjects_data = $this->rest_app->get('index.php/mail/vendor_subjects', $get_data);
			$data['vendor_subjects'] = $vendor_subjects_data->subjects;
			//$this->rest_app->debug();
			$subjects_filter_data = $this->rest_app->get('index.php/mail/subjects_filter', $get_data);
			$data['subjects_filter'] = $subjects_filter_data->subjects;
			
			$from_data = $this->rest_app->get('index.php/mail/from', $get_data);
			$data['from'] = $from_data->from;
			
			$senders_data = $this->rest_app->get('index.php/mail/senders', $get_data);
			$data['senders'] = $senders_data->senders;

			$inbox = $this->get_messages('inbox', $get_data['data_id']);
			$data['inbox'] = $inbox['messages'];
			$data['inbox_count'] = $inbox['count'];

			$outbox = $this->get_messages('outbox', $get_data['data_id']);
			$data['outbox'] = $outbox['messages'];
			$data['outbox_count'] = $outbox['count'];

			$archive = $this->get_messages('archive', $get_data['data_id']);
			$data['archives'] = $archive['messages'];
			$data['archive_count'] = $archive['count'];
			//$this->rest_app->debug();
			$data['unread_count'] = $this->get_unread_count();

			echo json_encode($data);
		}

		function get_messages($mail_type = null, $data_id = 0)
		{
			$get_data['user_id'] = $this->session->userdata('user_id');

			// These wont have value if call was made from this controller. No sorting will be done
			# FROM filter_messages function JS
			$get_data['filter_type'] = $this->input->get('filter_type');
			$get_data['filter_value'] = $this->input->get('filter_value');
			# FROM sort_column function JS
			$get_data['sort_column'] = $this->input->get('sort_column');
			$get_data['sort_type'] = $this->input->get('sort_type');
			$get_data['data_id'] = $data_id;

			$mail_type = (!empty($mail_type)) ? $mail_type : $this->input->get('mail_type');

			$mail = $this->rest_app->get('index.php/mail/' . $mail_type, $get_data);
			$data['messages'] = $mail->messages;
			$data['count'] = $mail->count;
			//$data['last_query'] = $mail->last_query; // for debug
			//var_dump($get_data);
			//die();
			$data['unread_count'] = $this->get_unread_count();

			if (!empty($this->input->get('get_message_call'))) { // if call was made from a view
				echo json_encode($data);
			}
			else { // if call was made from this controller
				return $data;
			}
		}

		function filter_topic()
		{
			$get_data['user_id'] = $this->session->userdata('user_id');

			$get_data['senderid'] = $this->input->post('senderid');
			$get_data['subject_type'] = $this->input->post('subject_type');
			$get_data['vendor_id'] = $this->input->post('vendor_id');
			$get_data['invite_id'] = $this->input->post('invite_id');
			$get_data['rfqrfb_id'] = $this->input->post('rfqrfb_id');

			$topic_rs = $this->rest_app->get('index.php/mail/filter_topic', $get_data);
			//$this->rest_app->debug();
			$topic_result = urldecode($topic_rs->topic);

			echo $topic_result;
		}

		function filter_recipient()
		{
			$get_data['user_id'] = $this->session->userdata('user_id');

			$get_data['senderid'] = $this->input->post('senderid');
			$get_data['subject_type'] = $this->input->post('subject_type');
			$get_data['vendor_id'] = $this->input->post('vendor_id');
			$get_data['invite_id'] = $this->input->post('invite_id');
			$get_data['rfqrfb_id'] = $this->input->post('rfqrfb_id');

			$recipient_rs = $this->rest_app->get('index.php/mail/filter_recipient', $get_data);
			//$this->rest_app->debug();
			$data['recipient_result'] = $recipient_rs->recipient;
			$data['recipient_status'] = $recipient_rs->status;

			echo json_encode($data);
		}

		function send_message()
		{
			$post_data['user_id'] = $this->session->userdata('user_id');
			$post_data['user_type_id'] = $this->session->userdata('user_type_id');

			$post_data['type'] = 'message';
			$post_data['subject_type'] = $this->input->post('subject_type');
			$post_data['mail_subj'] = $this->input->post('mail_subj');
			$post_data['mail_topic'] = $this->input->post('mail_topic');
			$post_data['mail_body'] = $this->input->post('mail_body');
			$post_data['vendor_id'] = $this->input->post('vendor_id');
			$post_data['invite_id'] = $this->input->post('invite_id');
			$post_data['rfqrfb_id'] = $this->input->post('rfqrfb_id');
			$post_data['parent_message_id'] = $this->input->post('parent_message_id');
			$post_data['recipient_id'] = $this->input->post('recipient_id');

			if($post_data['recipient_id'] == 'all')
			{
				$recipient_rs = $this->rest_app->get('index.php/mail/filter_recipient', $post_data);

				foreach($recipient_rs->recipient as $row)
				{
					$post_data['recipient_id'] = $row->USER_ID;
					$send_data = $this->rest_app->post('index.php/mail/message', $post_data, null);			
				}
			}
			else
				$send_data = $this->rest_app->post('index.php/mail/message', $post_data, null);			

			echo json_encode($send_data);
		}

		function send_notification()
		{
			$post_data['user_id'] = $this->session->userdata('user_id');

			$post_data['type'] = 'notification';
			$post_data['recipient_id'] = $this->input->post('recipient');
			$post_data['mail_subj'] = $this->input->post('mail_subj');
			$post_data['mail_topic'] = $this->input->post('mail_topic');
			$post_data['mail_body'] = $this->input->post('mail_body');
			$post_data['vendor_id'] = $this->input->post('vendor_id');
			$post_data['invite_id'] = $this->input->post('invite_id');
			$post_data['rfqrfb_id'] = $this->input->post('rfqrfb_id');

			$send_data = $this->rest_app->post('index.php/mail/notification', $post_data);

			echo json_encode($post_data);
		}

		function mark_as_read()
		{
			$put_data['message_id'] = $this->input->post('message_id');

			$return_data = $this->rest_app->put('index.php/mail/message', $put_data, 'text');

			$data['unread_count'] = $this->get_unread_count();

			echo json_encode($data);
		}

		function archive_message()
		{
			$put_data['message_ids'] = $this->input->post('message_ids');

			$return_data = $this->rest_app->put('index.php/mail/archive_message', $put_data, 'text');
			$data['unread_count'] = $this->get_unread_count();

			echo json_encode($data);
		}

		function get_mail_table()
		{

			$rs = $_POST;

				$rs['start'] = $this->input->post('start');
				$rs['length'] = $this->input->post('length');
				$rs['type'] = $this->input->post('message_type');
				$rs['status'] = $this->input->post('status');
				$rs['sort'] = $this->input->post('sort');
				$rs['sort_type'] = $this->input->post('sort_type');
				$rs['from'] = $this->input->post('from');
				$rs['subject'] = $this->input->post('subject');
				$rs['search_topic'] = $this->input->post('search_topic');


			$rs['user_id'] = $this->session->userdata('user_id');
			
			$mail_data = $this->rest_app->get('index.php/mail/new_inbox', $rs, '');
/*			var_dump($mail_data);
*/
			$data = array();

						foreach ($mail_data[1] as $row) {
				$count = $row->COUNT;
			}
	
		foreach ($mail_data[0] as $row) {

				if($row->IS_READ == 1){
					$row->IS_READ = 'mail_read';
				}else{
					$row->IS_READ = 'mail_unread';
				}		

				$new_data = array();
				$new_data['SENDER_RECIPIENT'] = $row->SENDER_RECIPIENT;
				$new_data['MAIL_DATE_FORMATTED'] = $row->MAIL_DATE_FORMATTED;
				$new_data['TYPE'] = $row->TYPE;
				$new_data['SUBJECT'] = $row->SUBJECT;
				$new_data['IS_READ'] = $row->IS_READ;
				$new_data['TOPIC'] = str_replace('+', ' ', htmlspecialchars($row->TOPIC));
				$new_data['TOPIC'] = str_replace('%3A', ':', $new_data['TOPIC']);
				//$new_data['BODY'] = str_replace('+' ,' ',nl2br($row->BODY)); 
				$new_data['BODY'] = str_replace('+' ,' ',$row->BODY); 
				$new_data['ID'] = $row->ID;
				$new_data['RECIPIENT_ID'] = $row->RECIPIENT_ID;
				//$new_data['test'] = $sdate;
				if(isset($_POST)){
						//$new_data['test'] = json_encode($sdate);
				}
				
				$data[] = $new_data;
			}

			$last_data = array(
				'data' => $data,
				'recordsTotal' => intval($count),
				'recordsFiltered' => intval($count),
				'draw' => 100

			);
			echo json_encode( $last_data);

		}

		function get_from_subject(){

			$get_data['user_id'] = $this->session->userdata('user_id');
			$get_data['user_type_id'] = $this->session->userdata('user_type_id');
			$get_data['position_id'] = $this->session->userdata('position_id');

			//$vendor_subjects_data = $this->rest_app->get('index.php/mail/vendor_subjects', $get_data);
			//$data['vendor_subjects'] = $vendor_subjects_data->subjects; 

			$senders_data = $this->rest_app->get('index.php/mail/senders', $get_data);
			$data['senders'] = $senders_data->senders;
			
			$subjects_filter_data = $this->rest_app->get('index.php/mail/subjects_filter', $get_data);
			$data['subjects_filter'] = $subjects_filter_data->subjects;


			$from_data = $this->rest_app->get('index.php/mail/from', $get_data);
			$data['from'] = $from_data->from;


			echo json_encode($data);


		}

		function get_outbox_table(){

				$rs = $_POST;

				$rs['start'] = $this->input->post('start');
				$rs['length'] = $this->input->post('length');
				$rs['type'] = $this->input->post('message_type');
				$rs['status'] = $this->input->post('status');
				$rs['sort'] = $this->input->post('sort');
				$rs['sort_type'] = $this->input->post('sort_type');
				$rs['from'] = $this->input->post('from');
				$rs['subject'] = $this->input->post('subject');
				$rs['search_topic'] = $this->input->post('search_topic');


			$rs['user_id'] = $this->session->userdata('user_id');
			
			$mail_data = $this->rest_app->get('index.php/mail/new_outbox', $rs, '');
			//var_dump($mail_data);

			$data = array();

		foreach ($mail_data[1] as $row) {
				$count = $row->COUNT;
			}
	
		foreach ($mail_data[0] as $row) {

				if($row->IS_READ == 1){
					$row->IS_READ = 'mail_read';
				}else{
					$row->IS_READ = 'mail_unread';
				}		

				$new_data = array();
				$new_data['SENDER_RECIPIENT'] = $row->SENDER_RECIPIENT;
				$new_data['MAIL_DATE_FORMATTED'] = $row->MAIL_DATE_FORMATTED;
				$new_data['TYPE'] = $row->TYPE;
				$new_data['SUBJECT'] = $row->SUBJECT;
				$new_data['IS_READ'] = $row->IS_READ;
				$new_data['TOPIC'] = str_replace('+', ' ', htmlspecialchars($row->TOPIC));
				$new_data['TOPIC'] = str_replace('%3A', ':', $new_data['TOPIC']);
				$new_data['BODY'] = str_replace('+',' ',htmlspecialchars_decode($row->BODY));
				$new_data['ID'] = $row->ID;
				$new_data['RECIPIENT_ID'] = $row->RECIPIENT_ID;
				//$new_data['test'] = $sdate;
				if(isset($_POST)){
						//$new_data['test'] = json_encode($sdate);
				}
				
				$data[] = $new_data;
			}

			$last_data = array(
				'data' => $data,
				'recordsTotal' => intval($count),
				'recordsFiltered' => intval($count),
				'draw' => 100

			);
			echo json_encode( $last_data);
		}


		function get_inbox_outbox_archive(){


				$rs = $_POST;

				$rs['start'] = $this->input->post('start');
				$rs['length'] = $this->input->post('length');
				$rs['type'] = $this->input->post('message_type');
				$rs['status'] = $this->input->post('status');
				$rs['sort'] = $this->input->post('sort');
				$rs['sort_type'] = $this->input->post('sort_type');
				$rs['from'] = $this->input->post('from');
				$rs['subject'] = $this->input->post('subject');
				$rs['message_type'] = $this->input->post('message_type_ioa');
				$rs['search_topic'] = $this->input->post('search_topic');


			$rs['user_id'] = $this->session->userdata('user_id');
			
			$mail_data = $this->rest_app->get('index.php/mail/inbox_outbox_archive', $rs, '');
			//var_dump($mail_data);

			$data = array();

		foreach ($mail_data[1] as $row) {
				$count = $row->COUNT;
			}
	
		foreach ($mail_data[0] as $row) {

				if($row->IS_READ == 1){
					$row->IS_READ = 'mail_read';
				}else{
					$row->IS_READ = 'mail_unread';
				}		

				$new_data = array();
				$new_data['SENDER_RECIPIENT'] = $row->SENDER_RECIPIENT;
				$new_data['MAIL_DATE_FORMATTED'] = $row->MAIL_DATE_FORMATTED;
				$new_data['TYPE'] = $row->TYPE;
				$new_data['SUBJECT'] = $row->SUBJECT;
				$new_data['IS_READ'] = $row->IS_READ;
				$new_data['TOPIC'] = str_replace('+', ' ', htmlspecialchars($row->TOPIC));
				$new_data['TOPIC'] = str_replace('%3A', ':', $new_data['TOPIC']);
				$new_data['BODY'] = str_replace('+',' ',htmlspecialchars_decode($row->BODY));
				$new_data['ID'] = $row->ID;
				$new_data['RECIPIENT_ID'] = $row->RECIPIENT_ID;
				//$new_data['test'] = $sdate;
				if(isset($_POST)){
						//$new_data['test'] = json_encode($sdate);
				}
				
				$data[] = $new_data;
			}

			$last_data = array(
				'data' => $data,
				'recordsTotal' => intval($count),
				'recordsFiltered' => intval($count),
				'draw' => 100

			);
			echo json_encode( $last_data);

		}
	}
?>