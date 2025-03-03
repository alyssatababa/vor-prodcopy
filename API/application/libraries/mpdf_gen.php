<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mpdf_gen {
		
	public function __construct() {
		
		require_once APPPATH.'third_party/mpdf/mpdf.php';
		
		$pdf = new mPDF('','A4',8,'calibri');
		
		$CI =& get_instance();

		$CI->mpdf = $pdf;
		
	}
	
}