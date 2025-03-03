<?php

/*
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => 'ssl://mail.sandmansystems.com',
			'smtp_port' => 465,
			'smtp_user' => 'support@sandmansystems.com',
			'smtp_pass' => 'sandman0198',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'newline' => '\r\n',
			'wordwrap' => TRUE);*/

		/* Old Email Config
		$config = Array(
			'protocol' => 'smtp',
			//'smtp_host' => '10.111.121.203',
			'smtp_host' => '172.17.224.11',
			//'smtp_host' => '10.143.246.59',
			//'smtp_host' => 'smretail-com.mail.protection.outlook.com',
			'smtp_port' => 25,
			//'smtp_port' => 8025,
			'smtp_user' => '',
			'smtp_pass' => 'sandman0198',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'newline' => '\r\n',
			'wordwrap' => TRUE);
			*/

		$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'email-smtp.ap-southeast-1.amazonaws.com',
			'smtp_port' => '587',
			'smtp_user' => 'AKIAZEFUQG3CXFWPXQZH',
			'smtp_pass' => 'BG9dYGETBn0LhYYjvWPjpD73XDxn1BGKMw0i/yYUmxGe',
			'mailtype' => 'html',
			'charset' => 'iso-8859-1',
			'newline' => '\r\n',
			'wordwrap' => TRUE,
			'smtp_crypto' => 'tls'
			);


	//	$this->email->initialize($config);