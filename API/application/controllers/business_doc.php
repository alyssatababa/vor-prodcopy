<?Php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// use Nette\Caching\Cache;
// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Business_doc extends REST_Controller {
	private $_vendor_code; 
    private $cache;
    private $cache_storage;
	
    // Load model in constructor
	public function __construct() {
		parent::__construct();
        $this->load->model('business_doc_model','bd_model');
        $this->load->model('credit_advice_model','ca_model');
        $this->load->model('remittance_advice_model','ra_model');
		$this->load->model('purchase_order_model','po_model');
        $this->load->model('vendor_model','venmodel');
        // $this->cache_storage = new Nette\Caching\Storages\FileStorage(APPPATH.'cache');
        // $this->cache = new Cache($this->cache_storage);
       
	}

    ////////////////////////////
    // Credit advice documents//
    ////////////////////////////
    
    /**
     * Credit advice list
     * @return json Json list of credit advice document
     */
	public function ca_list_post(){
        if($this->post('archive')){
            $list = $this->ca_model->get_datatables(true);
            $recordsTotal = $this->ca_model->count_all(true);
            $recordsFiltered = $this->ca_model->count_filtered(true);
        } else{
            $list = $this->ca_model->get_datatables(null);
            $recordsTotal = $this->ca_model->count_all(null);
            $recordsFiltered = $this->ca_model->count_filtered(null);
        }
        $data = [];
        foreach ($list as $ca) {
        	$cm_date = new DateTime($ca->CM_DATE);
            $post_date = new DateTime(explode(" ",$ca->DATE_CREATED)[0]);
            $row = [];
            $row[] = $ca->CHECK_NO;
            $row[] = $cm_date->format('m/d/Y');
            $row[] = $post_date->format('m/d/Y');
            $row[] = number_format($ca->AMOUNT,2);
            $row[] = $ca->CHECK_NO;
            $row[] = $ca->STATUS ;
            $data[] = $row;
        }

        $output = array(
                "draw" => $this->post('dt_post')['draw'],
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data,
        );


        $this->response(json_encode($output));
	}

    /**
     * Details of credit advice document
     * @param  integer $id  ID of credit advice document
     * @return object       Object details of document
     */
	public function ca_details_get($id,$position_id){
        $this->load->helper('date');
        $ca_details = $this->ca_model->vget_by(1,$id); //Get details from live db
        if($ca_details){
            if($position_id == 10){
                // $this->ca_model->update($id,['VIEWED' => $ca_details->VIEWED + 1]); //Trigger last view and update view
                $this->ca_model->viewed_proc(2,$id,1,$ca_details->VIEWED);
                if($ca_details->STATUS == 0){
                    $this->ca_model->update($id, ['STATUS' => 1]);
                }
            }
            $ca_details->BACKSES = 'live-ca';    
        } else {
            $ca_details = $this->ca_model->vget_by(2,$id); //Get details from archive db
            $ca_details->BACKSES = 'arc-ca';

            if($position_id == 10){
                $this->ca_model->viewed_proc(2,$id,2,$ca_details->VIEWED);
            } 
        }

        //Inject view data from details object
        $vw_desc = $this->ca_model->ca_action($id,'DATE_VIEWED','DESC',1);
        $vw_asc = $this->ca_model->ca_action($id,'DATE_VIEWED','ASC',1);
        if($ca_details->VIEWED != null){
            if(count($vw_desc) != 0 || count($vw_asc) != 0){
                $last_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_desc->DATE_VIEWED);
                $first_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_asc->DATE_VIEWED);
                $ca_details->LAST_VIEW = $last_view->format('m/d/Y H:i:s');  
                $ca_details->FIRST_VIEW = $first_view->format('m/d/Y H:i:s');
            } else {
                $ca_details->LAST_VIEW  = null;
                $ca_details->FIRST_VIEW = null;
            }   
        } else {
            $ca_details->LAST_VIEW  = null;
            $ca_details->FIRST_VIEW = null;
        }
        

        //Inject download data from details object
        $dl_desc = $this->ca_model->ca_action($id,'DATE_DOWNLOADED','DESC',2);
        $dl_asc = $this->ca_model->ca_action($id,'DATE_DOWNLOADED','ASC',2);

        if($ca_details->DOWNLOADED != NULL){
            if(count($dl_desc) != 0 || count($dl_asc) != 0){
                $last_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_desc->DATE_DOWNLOADED);
                $first_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_asc->DATE_DOWNLOADED);
                $ca_details->LAST_DOWNLOAD = ($last_download) ? $last_download->format('m/d/Y H:i:s') : '';  
                $ca_details->FIRST_DOWNLOAD = $first_download->format('m/d/Y H:i:s');
            } else {
                $ca_details->LAST_DOWNLOAD  = null;
                $ca_details->FIRST_DOWNLOAD = null;
            }
        } else {
            $ca_details->LAST_DOWNLOAD  = null;
            $ca_details->FIRST_DOWNLOAD = null;
        }
		return $this->response($ca_details);  
	}


    public function ca_details_post(){
        $ca_details = $this->ca_model->vget_by_many(1,$this->post('vendor_id'));
        if($ca_details == null){
            $ca_details = $this->ca_model->vget_by_many(2,$this->post('vendor_id'));
        }
        $this->response($ca_details);
    }


    ////////////////////
    // DMCM documents //
    ////////////////////
    
    /**
     * DMCM datatable list
     * @return json Json list of dmcm document
     */
    public function dmcm_list_post(){
        $this->load->model('dmcm_model');

        $list = $this->dmcm_model->get_datatables();
        $data = [];
        foreach ($list as $dmcm) {
            $post_date = new DateTime(explode(" ",$dmcm->DATE_CREATED)[0]);
            $row = [];
            $row[] = $dmcm->DOC_TYPE_NAME;
            $row[] = $dmcm->DOC_NO;
            $row[] = $dmcm->COMPANY_NAME;
            $row[] = $dmcm->BRANCH_NAME;
            $row[] = $dmcm->NATURE_NAME;
            $row[] = number_format($dmcm->AMOUNT,2);
            $row[] = 1;
            $row[] = $dmcm->CHECK_NO;
            $row[] = $post_date->format('m/d/Y');

            $data[] = $row;
        }
        $output = array(
                "draw" => $this->post('dt_post')['draw'],
                "recordsTotal" => $this->dmcm_model->count_all(),
                "recordsFiltered" => $this->dmcm_model->count_filtered(),
                "data" => $data,
         );
        $this->response(json_encode($output));
    }

    /**
     * List of company for filter
     * @return array List of vendor code
     */
    public function dmcm_comp_list_get(){
        $this->load->model('dmcm_model');
        $comp_list = $this->dmcm_model->search_company(urldecode($this->get('q')),$this->get('option'),$this->get('vendor_id'));
        // $this->response($comp_list);
        $data = [];
        $c = 0;
        foreach ($comp_list as $key => $value) {
            $data[$c]['id'] = $value->COMPANY_NAME; 
            $data[$c]['text'] = $value->COMPANY_NAME; 
            $c++;
        }
        $this->response($data);
    }

    /**
     * List of stores for filter
     * @return array List of vendor code
     */
    public function dmcm_stName_list_get(){
        $this->load->model('dmcm_model');
        $comp_list = $this->dmcm_model->search_store(urldecode($this->get('q')),$this->get('vendor_id'));
        // $this->response($comp_list);
        $data = [];
        $c = 0;
        foreach ($comp_list as $key => $value) {
            $data[$c]['id'] = $value->BRANCH_NAME; 
            $data[$c]['text'] = $value->BRANCH_NAME; 
            $c++;
        }
        $this->response($data);
    }


    ///////////////////////
    // Remittance Advice //
    ///////////////////////
    
    /**
     * List of remittance advice documents
     * @return json datatable format
     */
    public function ra_list_post(){
        if($this->post('archive')){
            $list = $this->ra_model->get_datatables(true);
            $recordsTotal = $this->ra_model->count_all(true);
            $recordsFiltered = $this->ra_model->count_filtered(true);
        } else{
            $list = $this->ra_model->get_datatables(null);
            $recordsTotal = $this->ra_model->count_all(null);
            $recordsFiltered = $this->ra_model->count_filtered(null);       
        }
        $data = [];
        foreach ($list as $ra) {
            $row = [];
            $row[] = $ra->REF_NO;
            $row[] = $ra->REF_NO;
            $row[] = date("m/d/Y",strtotime($ra->PROCESSING_DATE)); 
            $row[] = number_format($ra->TOTAL_AMOUNT,2);
            $row[] = $ra->PAYMENT_DATE;
            $row[] = $ra->PAYMENT_TYPES;
            $row[] = date("m/d/Y",strtotime($ra->POST_DATE));
            $row[] = ($this->post('archive')) ? 'arch' : $ra->READ_STATUS ;
            $data[] = $row;
        }
        $output = array(
                "draw" => $this->post('dt_post')['draw'],
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data,
         );
        $this->response(json_encode($output));
    }

    public function ra_details_get($id,$position_id){
        $this->load->helper('date');
        $ra_details = $this->ra_model->vget_by(1,$id);
      
        if($ra_details){
            $ra_details->BODY = $this->ra_model->vget_body(1,$id);
            if($position_id == 10){
                $this->ra_model->viewed_proc(5,$id,1,($ra_details->VIEWED != NULL) ? $ra_details->VIEWED : 0);
                // $this->ra_model->update($id,['VIEWED' => $ra_details->VIEWED + 1]); //Trigger last view and update view
                if($ra_details->READ_STATUS == 0){
                  $this->ra_model->update($id, ['STATUS' => 1]);
                }
            }
            $ra_details->BACKSES = 'live-ra';
        } else {
            $ra_details = $this->ra_model->vget_by(2,$id); //Get details from archive db
            if($position_id == 10){
                 $this->ra_model->viewed_proc(5,$id,2,($ra_details->VIEWED != NULL) ? $ra_details->VIEWED : 0);
            }
            if($ra_details){
                $ra_details->BODY = $this->ra_model->vget_body(2,$id);
            } else {
                return $this->response(null);
            }
            $ra_details->BACKSES = 'arc-ra';
            
        }

       
        $vw_desc = $this->ra_model->ra_action($id,'DATE_VIEWED','DESC',1);
        $vw_asc = $this->ra_model->ra_action($id,'DATE_VIEWED','ASC',1); 
        if($ra_details->VIEWED != NULL) {
            if(count($vw_desc) != 0 || count($vw_asc) != 0){
                $last_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_desc->DATE_VIEWED);
                $first_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_asc->DATE_VIEWED);
                $ra_details->LAST_VIEW = $last_view->format('m/d/Y H:i:s');  
                $ra_details->FIRST_VIEW = $first_view->format('m/d/Y H:i:s'); 
            } else {
                $ra_details->LAST_VIEW = null;  
                $ra_details->FIRST_VIEW = null;
            }
        }   
        else {
            $ra_details->LAST_VIEW = null;  
            $ra_details->FIRST_VIEW = null;
        }    

        //Inject download data from details object
        $dl_desc = $this->ra_model->ra_action($id,'DATE_DOWNLOADED','DESC',2);
        $dl_asc = $this->ra_model->ra_action($id,'DATE_DOWNLOADED','ASC',2);
        if($ra_details->DOWNLOADED != NULL) {
            if(count($dl_desc) > 0 || count($dl_asc) > 0){
                $last_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_desc->DATE_DOWNLOADED);
                $first_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_asc->DATE_DOWNLOADED);
                $ra_details->LAST_DOWNLOAD = $last_download->format('m/d/Y H:i:s');  
                $ra_details->FIRST_DOWNLOAD = $first_download->format('m/d/Y H:i:s');
            } else{
                $ra_details->LAST_DOWNLOAD = null;  
                $ra_details->FIRST_DOWNLOAD = null;
            }
            
        } else {
            $ra_details->LAST_DOWNLOAD = null;  
            $ra_details->FIRST_DOWNLOAD = null;
        }
        
        return $this->response($ra_details);
    }

    /** Details of selected ra document */
    public function ra_details_post(){
        $data = [];
        $c = 0;
        $exploded_ra = explode(',', $this->post('selected'));
        $ra_details = $this->ra_model->vget_by(1,$exploded_ra[0]);
        if($ra_details){ #Todo check if existing in live db
            foreach ( $exploded_ra as $key => $value) {
                $ra_details = $this->ra_model->vget_by(1,$value);
                $row = [];
                $row['BODY'] = $this->ra_model->vget_body(1,$value);
                $row['DETAILS'] = $ra_details;
                $data[] = $row;
            }
        } else{ #Todo check now in archive db
            foreach ($exploded_ra as $key => $value) {
                $ra_arc_details = $this->ra_model->vget_by(2,$value);
                $row = [];
                $row['DETAILS'] = $ra_arc_details;
                $row['BODY'] = $this->ra_model->vget_body(2,$value);
                $data[] = $row;
            }
        }
        $this->response($data);
    }


    ////////////////////
    // Purchase Order //
    ////////////////////
    
    /**
     * List of purchase order documents
     * @return json datatable format
     */
    public function po_list_post(){

        if($this->post('archive')){
            $list = $this->po_model->get_datatables(true);
            $recordsTotal = $this->po_model->count_all(true);
            $recordsFiltered = $this->po_model->count_filtered(true);
        } else{
            $list = $this->po_model->get_datatables(null);
            $recordsTotal = $this->po_model->count_all(null);
            $recordsFiltered = $this->po_model->count_filtered(null); 
        }
        $data = [];
        foreach ($list as $po) {
            $row = [];
            $row[] = $po->PO_NUMBER;
            $row[] = $po->PO_NUMBER;
            $row[] = $po->PO_STATUS;
            $row[] = $po->ENTRY_DATE;
            $row[] = $po->EXPECTED_RECEIPT_DATE; 
            $row[] = $po->CANCEL_DATE; 
            $row[] = number_format($po->TOTAL_AMOUNT,2); 
            $row[] = $po->DEPARTMENT_NAME; 
            $row[] = $po->LOCATION; 
            $row[] = $po->COMPANY_NAME; 
            $row[] = date("m/d/Y",strtotime($po->POST_DATE)); 
            $row[] = ($this->post('archive')) ? 'arch' : $po->READ_STATUS ;
            $row[] = $po->COMPANY_ID ;
            $data[] = $row;
        }
        $output = array(
                "draw" => $this->post('dt_post')['draw'],
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data,
         );

        $this->response(json_encode($output));
    }

     /**
     * Details of purchase order document
     * @param  integer $id  ID of purchase order document
     * @return object       Object details of document
     */
    public function po_details_get($id,$comp_id,$position_id,$vendor_id){
        $this->load->helper('date');
        $po_details = $this->po_model->vget_by(1,$id,$vendor_id); //Get details from live db

        if($po_details){
            $po_details->BODY = $this->po_model->vget_body(1,$id,$vendor_id,$comp_id);
            $po_details->HDR = $this->po_model->vget_hdr_by(1,$id,$vendor_id);
            $po_details->BACKSES = 'live-po';
            if($position_id == 10){
                $this->po_model->viewed_proc(1,$id,1,$po_details->VIEWED,$vendor_id);
                // $this->po_model->update($id,['VIEWED' => $po_details->READ_STATUS + 1]); //Trigger last view and update view
                if($po_details->READ_STATUS == 0){
                   $this->po_model->update_readstat($id);
                   $po_details->READ_STATUS = 1;
                }
            }
        } else {
            $po_details = $this->po_model->vget_by(2,$id,$vendor_id); //Get details from archive db
           
            if($position_id == 10){ 
                $this->po_model->viewed_proc(1,$id,2,$po_details->VIEWED,$vendor_id);
            }
            $po_details->BODY = $this->po_model->vget_body(2,$id,$vendor_id,$comp_id);
            $po_details->HDR = $this->po_model->vget_hdr_by(2,$id,$vendor_id);
            $po_details->BACKSES = 'arc-po';
        }
        

        if(count($po_details->HDR) == 0 || count($po_details->BODY) == 0  || $po_details == "" ){ //catching inconsitent data
            return $this->response(['data' => false]);
        }

        //Inject view data from details object
        $vw_desc = $this->po_model->po_action($id,'DATE_VIEWED','DESC',1,($position_id = 10 ? $vendor_id : Null),0);
        $vw_asc = $this->po_model->po_action($id,'DATE_VIEWED','ASC',1,($position_id = 10 ? $vendor_id : Null),0);
        $po_details->VIEWED = $this->po_model->po_action($id,'DATE_VIEWED','DESC',1,($position_id = 10 ? $vendor_id : Null),1);
        if($po_details->VIEWED != null){
            if(count($vw_desc) != 0 || count($vw_asc) != 0){
                $last_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_desc->DATE_VIEWED);
                $first_view = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw_asc->DATE_VIEWED);
                $po_details->LAST_VIEW = $last_view->format('m/d/Y H:i:s');  
                $po_details->FIRST_VIEW = $first_view->format('m/d/Y H:i:s');  

            } else {
                $po_details->LAST_VIEW  = null;
                $po_details->FIRST_VIEW = null;
            }  
        } else {
            $po_details->LAST_VIEW  = null;
            $po_details->FIRST_VIEW = null;
        }
       

        //Inject download data from details object
        $dl_desc = $this->po_model->po_action($id,'DATE_DOWNLOADED','DESC',2,($position_id = 10 ? $vendor_id : Null),0);
        $dl_asc = $this->po_model->po_action($id,'DATE_DOWNLOADED','ASC',2,($position_id = 10 ? $vendor_id : Null),0);
        $po_details->DOWNLOADED = $this->po_model->po_action($id,'DATE_DOWNLOADED','DESC',2,($position_id = 10 ? $vendor_id : Null),1);
        if($po_details->DOWNLOADED != null){
            if(count($dl_desc) > 0 || count($dl_asc) > 0){
                $last_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_desc->DATE_DOWNLOADED);
                $first_download = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl_asc->DATE_DOWNLOADED);
                $po_details->LAST_DOWNLOAD = $last_download->format('m/d/Y H:i:s');  
                $po_details->FIRST_DOWNLOAD = $first_download->format('m/d/Y H:i:s');
            } else {
                $po_details->LAST_DOWNLOAD  = null;
                $po_details->FIRST_DOWNLOAD = null;
            } 
         } else {
            $po_details->LAST_DOWNLOAD  = null;
            $po_details->FIRST_DOWNLOAD = null;
        }     

        return $this->response($po_details);
    }

    /** Details of selected po document */
    public function po_details_post(){
        $data = [];
        $c = 0;
        $exploded_po = explode(',', $this->post('selected'));
        $exploded_company = explode(',', $this->post('companies'));
        $po_details = $this->po_model->vget_by(1,$exploded_po[0],$this->post('vendor_id'));
        if($po_details){ #Todo check if existing in live db
            $x = 0;
            foreach ( $exploded_po as $key => $value) {
                $po_details = $this->po_model->vget_hdr_by(1,$value,$this->post('vendor_id'));
                $row = [];
                $row['BODY'] = $this->po_model->vget_body(1,$value,$this->post('vendor_id'),$exploded_company[$x]);
                $row['DETAILS'] = $po_details;
                $data[] = $row;
                $x++;
            }
        } else{ #Todo check now in archive db
            $x = 0;
            foreach ($exploded_po as $key => $value) {
                $po_arc_details = $this->po_model->vget_hdr_by(2,$value,$this->post('vendor_id'));
                $row = [];
                $row['DETAILS'] = $po_arc_details;
                $row['BODY'] = $this->po_model->vget_body(2,$value,$this->post('vendor_id'),$exploded_company[$x]);
                $data[] = $row;
                $x++;
            }
        }
        $this->response($data);
    }

    /**
     * List of company for filter
     * @return array List of companies
     */
    public function po_comp_list_get(){
        $comp_list = $this->po_model->search_company(urldecode($this->get('q')),$this->get('option'),$this->get('vendor_id'));
        // $this->response($comp_list);
        $data = [];
        $c = 0;
        foreach ($comp_list as $key => $value) {
            $data[$c]['id'] = $value->COMPANY_NAME; 
            $data[$c]['text'] = $value->COMPANY_NAME; 
            $c++;
        }
        $this->response($data);
    }

    /**
     * List of location for filter
     * @return array List of locations
     */
    public function po_loc_list_get(){
        $comp_list = $this->po_model->search_location(urldecode($this->get('q')),$this->get('option'),$this->get('vendor_id'));
        // $this->response($comp_list);
        $data = [];
        $c = 0;
        foreach ($comp_list as $key => $value) {
            $data[$c]['id'] = $value->LOCATION; 
            $data[$c]['text'] = $value->LOCATION; 
            $c++;
        }
        $this->response($data);
    }

    /**
     * List of location for filter
     * @return array List of locations
     */
    public function po_dept_list_get(){
        $comp_list = $this->po_model->search_deptname(urldecode($this->get('q')),$this->get('option'),$this->get('vendor_id'));
        // $this->response($comp_list);
        $data = [];
        $c = 0;
        foreach ($comp_list as $key => $value) {
            $data[$c]['id'] = $value->DEPARTMENT_NAME; 
            $data[$c]['text'] = $value->DEPARTMENT_NAME; 
            $c++;
        }
        $this->response($data);
    }


	public function notifications_get($vendor_id = null){
        if($vendor_id){
            $notif = $this->bd_model->notifications($vendor_id);
        } else{
            $notif = $this->bd_model->notifications(null);
        }

		return $this->response($notif);
	}

    public function avail_dates_post(){
        $dates = $this->bd_model->available_dates();
        return $this->response($dates);
    }

    /**
     * Get vendor code
     * @param  number $vendor_id Vendor ID
     * @return number            Vendor code from db
     */
    public function vendor_code_get($vendor_id){
        $vendor_code = $this->venmodel->get_by('VENDOR_ID',$vendor_id);
        return $this->response((count($vendor_code) != 0) ? $vendor_code->VENDOR_CODE : '');
    }

    /**
     * Check for live or archive data in database
     * @return mixed Result of api
     */
    public function check_data_post(){
        $check = $this->bd_model->check_reports($this->post('category'),$this->post('vendor_id'));
        return $this->response($check);
    }

    /**
     * Document history including viewed and downloaded.
     * @return array  Sets of viewed or download list
     */
    public function view_history_post(){
        $history = $this->bd_model->view_downloads($this->post('doctype'),$this->post('vendor_id'),$this->post('id'));
        $merge =  array_merge((isset($history['vw']) ? $history['vw'] : []), (isset($history['dl'])? $history['dl'] : []));
        return $this->response($merge);
    }

    /**
     * Archiving or Unarchive selected document
     * @return array Status of process
     */
    public function delete_doc_post(){
        $doctype = $this->post('doctype');
        $option = $this->post('option');

        switch ($doctype) {
            case 1: //Purchase Order
                $this->po_model->delete_po_doc($option);
                break;
            case 2: //Credit Advice
                $this->ca_model->delete_ca_doc($option);
                break;
            case 5: //Remittance Advice
                $this->ra_model->delete_ra_doc($option);
                break;
            default:
                $this->response(['status'=>FALSE]);        
                break;
        }
        
        $this->response(['status'=>TRUE]);
    }   

    /**
     * List of vendor for admin vendor code search
     * @return array List of vendor code
     */
    public function vendor_list_get(){
        $vend_list = $this->venmodel->search_code($this->get('q'));

        $data = [];
        $c = 0;
        foreach ($vend_list as $key => $value) {
            $data[$c]['id'] = $value->VENDOR_ID; 
            $data[$c]['text'] = $value->VENDOR_CODE; 
            $c++;
        }
        $this->response($data);
    }

    public function log_action_post(){
        $this->load->model('users_model');
        $post_data['USER_ID'] = $this->post('user_id');
        $post_data['ACTION_ID'] = $this->post('action_id');

        $model_data = $this->users_model->log_action($post_data);

        $this->response($post_data);
    }


    public function send_email_post(){
        $pos = strrpos($this->post('pdf_path'), '/');
        $filename = $pos === false ? $this->post('pdf_path') : substr($this->post('pdf_path'), $pos + 1);
        $filepath = FCPATH.'attachment/'.$filename;
        copy($this->post('pdf_path'),$filepath);
        $this->load->model('common_model');
        $data['to'] = 'igo.robles@yahoo.com';
        // $data['to'] = $this->post('user_email');
        $data['subject'] = 'Requested document file';
        $data['content'] = 'Please see attached for your requested document.';
        $data['attach'][0] = $filepath;
        $this->common_model->send_email_notification($data);
        unlink($filepath);
        $this->response(['status'=>true]);
    }

}

?>