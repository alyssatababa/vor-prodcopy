<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Cron_email_reports extends REST_Controller {
	
	public function __construct() {
		parent::__construct();
			// $this->load->model('vendor/check_status_model');
		$this->load->model('vendor/vendor_reports_model');

		$this->load->model('mail_model');
		$this->load->model('common_model');
	}

	public function send_web_report_put(){

		$sysdate = $this->vendor_reports_model->get_sysdate();
	
		$currdate = new DateTime('2018-01-31');

		$sysday = $currdate->format('d');
		$sysdaynum = $currdate->format('t');

		$path = FCPATH.'vendor_reports/';

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, "http://localhost/SMNTP2/web/index.php/vendor/vendor_reports/download_report"); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch); 

        curl_close($ch);  
        $result = json_decode($output);

        $webfile1 = $result->base_url . "vendor_reports/" . str_replace(" ", "%20",$result->pdf1);
        $webfile2 = $result->base_url . "vendor_reports/" . str_replace(" ", "%20",$result->pdf2);
        $appfile1 = "vendor_reports/" . $result->pdf1;
        $appfile2 = "vendor_reports/" . $result->pdf2;
     
        file_put_contents($appfile1, fopen($webfile1, 'r'));
        file_put_contents($appfile2, fopen($webfile2, 'r'));

	    $recepients = $this->vendor_reports_model->get_buyer_senmer_email();

	    for ($i=0; $i<count($recepients); $i++){
	    	$email['to'][$i] = $recepients[0]['USER_EMAIL'];
	    }

		//$email['cc'] = 'pagaraojustineprice@yahoo.com';
        $email['subject'] = 'Vendor Reports';
        $email['content'] = 'Please see attached for monthly vendor reports.';
        $email['attach'][0] = $appfile1;
        $email['attach'][1] = $appfile2;
        $this->common_model->send_email_notification($email);     

        //$this->response($appfile1 . " " . $appfile2);   
	}

	public function send_report_put(){

		$sysdate = $this->vendor_reports_model->get_sysdate();
		//$endofmonth = $this->put('endofmonth');
		//$currdate = new DateTime($endofmonth);
		$currdate = new DateTime($sysdate[0]['CURRENT_DATE']);
		$sysday = $currdate->format('d');
		$sysdaynum = $currdate->format('t');

		$path = FCPATH.'vendor_reports';

		if (($sysdaynum == "28" && $sysday == "28") ||
		 		($sysdaynum == "29" && $sysday == "29") ||
		 		($sysdaynum == "30" && $sysday == "30") ||
		 		($sysdaynum == "31" && $sysday == "31")){


			$datefrom = $currdate->format('Y-m-01');
			$dateto = $currdate->format('Y-m-d');
			$this->generate_report_pdf($path, $datefrom, $dateto);
		}	

		$this->response($endofmonth . " " . $sysday . " " . $sysdaynum);	
	}

	function generate_report_pdf($path, $datefrom, $dateto){

        $data1['title'] = urldecode ("EXPIRED INVITES");
        $data1['result'] = json_encode($this->vendor_reports_model->get_expired_invites($datefrom, $dateto));

		$this->load->library('mpdf_gen');
		$pdf_filename_1 = $data1['title']." ".date('YmdHis').'.pdf';

	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    $mpdf->WriteHTML($this->load->view('report_pdf_view',$data1,true),0); //Html template
	    $mpdf->Output($path.'/'.$pdf_filename_1,'F'); 

        $data2['title'] = urldecode ("DEACTIVATED ACCOUNTS");
        $data2['result'] = json_encode($this->vendor_reports_model->get_deactivated_account($datefrom, $dateto));

		$this->load->library('mpdf_gen');
		$pdf_filename_2 = $data2['title']." ".date('YmdHis').'.pdf';

	 	$mpdf = new mPDF();
	    $mpdf->useSubstitutions = FALSE;
	    $mpdf->simpleTables = TRUE; //Disable for complex table
	    $mpdf->packTableData = TRUE;
	    $mpdf->WriteHTML($this->load->view('report_pdf_view',$data2,true),0); //Html template
	    $mpdf->Output($path.'/'.$pdf_filename_2,'F'); 

	    $recepients = $this->vendor_reports_model->get_buyer_senmer_email();

	    for ($i=0; $i<count($recepients); $i++){
	    	$email['to'][$i] = $recepients[0]['USER_EMAIL'];
	    }

		//$email['cc'] = 'pagaraojustineprice@yahoo.com';
        $email['subject'] = 'Vendor Reports';
        $email['content'] = 'Please see attached for monthly vendor reports.';
        $email['attach'][0] = $path.'/'.$pdf_filename_1;
        $email['attach'][1] = $path.'/'.$pdf_filename_2;
        $this->common_model->send_email_notification($email);	    
	}
}





