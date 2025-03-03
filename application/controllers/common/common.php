<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
*
*/
class Common extends CI_Controller
{

	function find_similar()
	{
		$data['txt_email'] 			= $this->input->post('txt_email');
		$data['txt_contact_person'] = $this->input->post('txt_contact_person');
		$data['txt_vendorname'] 	= $this->input->post('txt_vendorname');

		$rs = $this->rest_app->get('index.php/common_api/find_similar', $data, 'application/json');
		// $this->rest_app->debug();

		echo json_encode($rs);
	}

	// Gets status of RFQ/RFB and Vendor
	function get_status()
	{
		$rs = $this->rest_app->get('index.php/common_api/status');

		echo json_encode($rs);
	}

	function get_srvDate()
	{
		
		$rs = $this->rest_app->get('index.php/common_api/srvDate');
	//	$sDate = $rs[0]->NOW;
		//$rs = date('Y-m-d',$sDate);

/*		$sDate = explode(" ",($rs[0]->NOW));
		$sDate[0] = $sDate[0]."";
		$tmDate = explode("-",$sDate[0]);
		$tTime = explode(":",$sDate[1]);

		$fDate = implode("/", $tmDate);*/

		echo $rs[0]->NOW;

		//echo date('d M Y h:i:s',strtotime($fDate ." ". $sDate[1]));


	//	echo date('m-d-Y',strtotime($sDate[0]));

	}
}
?>