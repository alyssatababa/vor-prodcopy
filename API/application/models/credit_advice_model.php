<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model
class Credit_advice_model extends MY_Model{
		
	public $_table = 'SMNTP_BD_BDOCA';
    public $_archive = 'SMNTP_BD_BDOCA_ARCHIVE';
    public $_table_vw = 'SMNTP_BD_BDOCA_VIEW';
    public $_table_dl = 'SMNTP_BD_BDOCA_DOWNLOAD';
    public $_details_view = 'SMNTP_BD_BDOCA_DETAILS_VIEW';
    public $_arc_details_view = 'SMNTP_BD_BDOCA_ARC_DTLS_VIEW';
	public $primary_key = 'CHECK_NO';
    private $ar_like = [];
	var $column_order = ['','CM_DATE','CHECK_NO','AMOUNT','DATE_CREATED','STATUS']; //set column field database for datatable orderable
	var $filter_column = ['CHECK_NO' => 4,'STATUS'=>5]; //Filter by column (index number of datatable )
	var $column_search = ['CHECK_NO','STATUS']; //set column field database for datatable searchable 
	var $order = ['STATUS' => 'desc']; // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /////////////////////////////
    // Internal function usage //
    /////////////////////////////
    
    /**
     * Datatables query builder
     * @param  string $archive Check if archive table
     * @return null
     */
    private function _get_datatables_query($archive)
    { 
        // if($archive){ //filter column for archive
        //     $this->filter_column['STATUS'] = $this->column_search[2] = $this->column_order[5] = 'FILE_QUEUE_ID';
        //     $this->order = ['DATE_CREATED' => 'desc'];
        // }
        
        $this->db->from( $archive ? $this->_archive : $this->_table );
        $i = 0;
        if(isset($this->input->post('dt_post')['order'])) // here order processing
        {
            if($this->column_order[0] == ''){ $this->column_order[0] = key($this->order); }
            $this->db->order_by($this->column_order[$this->input->post('dt_post')['order'][0]['column']], $this->input->post('dt_post')['order'][0]['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /**
     * Filter query builder
     * @return null
     */
 	private function _get_filter_by_column(){
        $or_like = "(";	
        $dtColumns = $this->input->post('dt_post')['columns'];
    	$a = 0;
    	foreach ($dtColumns as $key => $value) { //get column with values
        	if( $dtColumns[$key]['search']['value'] != NULL){ //Search value is not null
        		$wSearch = array_key_exists($dtColumns[$key]['name'], $this->filter_column);
                if($wSearch){ //Check for filtered column variable
                    $wLike = array_search($key, $this->filter_column);
                    if($a === $key) // first loop
                    {
                        $this->db->like($wLike, $dtColumns[$a]['search']['value']);
                    }
                    // else //Currently disabled
                    // {
                    //     $or_like .= "OR ".$wLike." = '".$dtColumns[$a]['search']['value']."' ";
                    // }
                }
        	}
        	$a++;
        }
        // $or_like .= ")";
        // echo $or_like;
        // $this->db->or_like($or_like);
        
 	}

    /**
     * Check vendor for documents permission
     * @return null
     */
    private function _check_permission(){
        if(in_array($this->input->post('dt_post')['position_id'],[10])){
            $this->db->where('VENDOR_ID',$this->input->post('dt_post')['vendor_id']);
        }
    }

    private function _vendcode(){
        if($this->input->post('dt_post')['vend_id'] != 0){
            $this->db->like('VENDOR_ID',$this->input->post('dt_post')['vend_id']);
        }    
    }   

    /**
     * Date filter
     * @return null
     */
    private function _date_filter(){
        $date = $this->input->post('dt_post')['date'];
        if($date === "0" || $date == ""){
            return;
        }
        if($date['date_type'] === "1" && $date['date_month'] === "" && $date['date_year'] === "")
            return;

        switch ($date['date_type']) {
            case 1: // Month & Year selected
                $this->db->where("EXTRACT(month from CM_DATE) =",$date['date_month']);
                if($date['date_year'])
                    $this->db->where('EXTRACT(year from CM_DATE) =',$date['date_year']);
                break;
            case 2: // MTD selected
                $this->db->where('CM_DATE >=',date('m/d/Y', strtotime($date['date_mtdFrom'])),false);
                $this->db->where('CM_DATE <=',date('m/d/Y', strtotime($date['date_mtdTo'])),false);
                break;
            default:
                $this->db->where('CM_DATE >=',date('m/d/Y', strtotime($date['date_from'])),false);
                $this->db->where('CM_DATE <=',date('m/d/Y', strtotime($date['date_to'])),false);
                break;
        }
    }


    /////////////////////
    // Public function //
    /////////////////////

    public function get_datatables($archive = false)
    {
        $this->_get_datatables_query($archive);
        $this->_get_filter_by_column();
        $this->_date_filter();
        $this->_vendcode();        
        $this->_check_permission();

        if($this->input->post('dt_post')['length'] != -1){
            // echo 1;
            $this->db->limit($this->input->post('dt_post')['length'], $this->input->post('dt_post')['start']+1);
        }
       
        $query = $this->db->get();
        return $query->result();
    }
 
    public function count_filtered($archive = false)
    {
    	$this->_get_datatables_query($archive);
        $this->_get_filter_by_column();
        $this->_date_filter();
 $this->_vendcode();        $this->_check_permission();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /**
     * Count all data in db
     * @param  boolean $archive Check for live or archive
     * @return string           Result of count all
     */
    public function count_all($archive = false)
    {
        $this->db->from($archive ? $this->_archive : $this->_table);
        $this->_check_permission();
        return $this->db->count_all_results();
    }

    /**
     * Function for document history including viewed and download
     * @param  string  $criteria   [description]
     * @param  string  $order      [description]
     * @param  integer $actionType [description]
     * @return array              [description]
     */
    public function ca_action($id,$criteria='DATE_VIEWED',$order = 'DESC',$actionType = 1){
        $query = $this->db->select($criteria)->where('CHECK_NO',$id)->order_by($criteria, $order)->get(($actionType == 1) ? $this->_table_vw : $this->_table_dl);
      
        return $query->row();
    }

    /**
     * Archive or Unarchive credit advice document
     * @param  string $option  Check for live or archive
     * @return boolean         True
     */
    public function delete_ca_doc($option = 'live'){
        $insert_table = ($option == "live") ? $this->_archive : $this->_table;
        $delete_table = ($option == "live") ? $this->_table : $this->_archive;
        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents
        $list = $this->db->from($delete_table)->where_in('CHECK_NO',$selected_arr)->get()->result();
        $a = 0;
        $arr = [];
        foreach($list as $l){

            $arr[] = [
                'VENDOR_ID'=>       $l->VENDOR_ID,
                'VENDOR_BANK_ID'=>  $l->VENDOR_BANK_ID,
                'PAYOR_ID'=>        $l->PAYOR_ID,
                'CM_DATE'=>         $l->CM_DATE,
                'BDO_BRANCH'=>      $l->BDO_BRANCH,
                'AMOUNT'=>          $l->AMOUNT,
                'CHECK_NO'=>        $l->CHECK_NO,
                'DATE_CREATED'=>    $l->DATE_CREATED,
                'FILE_QUEUE_ID'=>   $l->FILE_QUEUE_ID,
                'VIEWED'=>          $l->VIEWED,
                'DOWNLOADED'=>      $l->DOWNLOADED,
                'STATUS'=>          $l->STATUS
            ];
            $a++;
        }

        $this->db->where_in('CHECK_NO', $selected_arr);
        $this->db->delete($delete_table); 
        $this->db->insert_batch($insert_table,$arr);
        return true;
    }

    /**
     * Get details from view by check no
     * @param  integer $id  ID of credit advice document
     * @return object       Result of query     
     */
    public function vget_by($option,$id){
        $query = $this->db->get_where(($option == 1) ? $this->_details_view : $this->_arc_details_view,['CHECK_NO'=>$id])->row();
        return $query;
    }

    public function vget_by_many($option,$vendor_id){
        $this->db->from(($option == 1) ? $this->_details_view : $this->_arc_details_view);
        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents
        $this->db->where_in('CHECK_NO',$selected_arr);
        if($vendor_id){
            $this->db->where('VENDOR_ID',$vendor_id);
        }
        
        $query = $this->db->get();  
        return $query->result();

    }   

    public function ca_list($archive=false){
        $this->db->from($archive ? $this->_archive : $this->_table);
        $this->_check_permission();
        $query = $this->db->get();
        return $query->result();
    }


    public function viewed_proc($doc,$id,$option,$viewed = 0){
        $vendor_id = $this->vget_by($option,$id)->VENDOR_ID;
        $opData = ($option == 1) ? 'live' : 'arch';
        $viewed = ($viewed == null) ? 0 : $viewed; 
        @$this->db->query("call sp_viewed(".$doc.", '".$id."', ".$vendor_id.", ".$viewed.", '".$opData."')");
        return true;
    }

}
?>
