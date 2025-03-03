<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['post_controller'][] = array(
	'class' => 'system_maintenance',
	'function' => 'maintenance',
	'filename' => 'system_maintenance.php',
	'filepath' => 'hooks',
	'params' => array()
  );


$hook['post_controller_constructor'][] = array(
	'class' => 'Check_session',
	'function' => 'check_session_data',
	'filename' => 'check_session.php',
	'filepath' => 'hooks',
	'params' => array()
  );


/* End of file hooks.php */
/* Location: ./application/config/hooks.php */