<?Php   defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model

class Remittance_advice_model extends MY_Model{
        
    public $_table = 'SMNTP_BD_RA';
    public $_archive = 'SMNTP_BD_RA_ARCHIVE';
    public $_table_vw = 'SMNTP_BD_RA_VIEW';
    public $_table_dl = 'SMNTP_BD_RA_DOWNLOAD';
    public $_list_view = 'SMNTP_BD_RA_HEAD_VIEW';
    public $_arc_list_view = 'SMNTP_BD_RA_ARC_HEAD_VIEW';
    public $_dts_view = 'SMNTP_BD_RA_LINES_VIEW';
    public $_arc_dts_view = 'SMNTP_BD_RA_ARC_LINES_VIEW';
    public $primary_key = 'REF_NO';
    var $column_order = ['','REF_NO','PROCESSING_DATE','TOTAL_AMOUNT','PAYMENT_DATE','PAYMENT_TYPES','POST_DATE','READ_STATUS']; //set column field database for datatable orderable
    var $filter_column = ['REF_NO' => 1,'READ_STATUS'=>7]; //Filter by column (index number of datatable )
    var $column_search = ['REF_NO','READ_STATUS']; //set column field database for datatable searchable 
    var $order = ['READ_STATUS' => 'desc']; // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query($archive)
    { 
        if($archive){
            $this->filter_column['READ_STATUS'] = $this->column_search[1] = $this->column_order[8] = 'REF_NO';
            $this->order = ['POST_DATE' => 'desc'];
        }
        
        $this->db->from( $archive ? $this->_arc_list_view : $this->_list_view );
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
        $dtColumns = $this->input->post('dt_post')['columns']; 
        $a = 0;
        foreach ($dtColumns as $key => $value) { //get column with values
            if($dtColumns[$a]['search']['value'] != null){ //Check if not null
                $wSearch = array_key_exists($dtColumns[$key]['name'], $this->filter_column);
                if($wSearch){ //Check for filtered column variable
                    $wLike = array_search($key, $this->filter_column);
                    // if($a === 0) // first loop
                    // {
                    //     $this->db->like($wLike, $dtColumns[$a]['search']['value']);
                    // }
                    // else 
                    // {
                        $this->db->like($wLike, $dtColumns[$a]['search']['value']);
                    // }
                }
            }
            $a++;
        }
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

    /**
     * Filter by vendor code
     * @return null
     */
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
                $this->db->where("EXTRACT(month from PROCESSING_DATE) =",$date['date_month']);
                if($date['date_year'])
                    $this->db->where('EXTRACT(year from PROCESSING_DATE) =',$date['date_year']);
                break;
            case 2: // MTD selected
                $this->db->where('PROCESSING_DATE >=',date('m/d/Y', strtotime($date['date_mtdFrom'])),false);
                $this->db->where('PROCESSING_DATE <=',date('m/d/Y', strtotime($date['date_mtdTo'])),false);
                break;
            default:
                $this->db->where('PROCESSING_DATE >=',date('m/d/Y', strtotime($date['date_from'])),false);
                $this->db->where('PROCESSING_DATE <=',date('m/d/Y', strtotime($date['date_to'])),false);
                break;
        }

    }

    /**
     * Payment date  filter
     * @return null
     */
    private function _payment_date_filter(){
        $date = $this->input->post('dt_post')['date'];
        if($date === "0" || $date == ""){
            return;
        }
        if($date['pd_from'] === "" && $date['pd_to'] === "")
            return;


        $this->db->where('PAYMENT_DATE >=',date('m/d/Y', strtotime($date['pd_from'])),false);
        $this->db->where('PAYMENT_DATE <=',date('m/d/Y', strtotime($date['pd_to'])),false);

    }

    /**
     * Return all query
     * @param  boolean $archive Check if archive or live data
     * @return array           Result of query
     */
    public function get_datatables($archive = false){

        $this->_get_datatables_query($archive);
        $this->_get_filter_by_column();
        $this->_date_filter();
        $this->_payment_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        // $this->db->where("ROWNUM <=",100000,false);
        if($this->input->post('dt_post')['length'] != -1){
            $this->db->limit($this->input->post('dt_post')['length'], $this->input->post('dt_post')['start'] + 1);    
        }
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Count of filtered result
     * @param  boolean $archive Check if archive or live data
     * @return integer Count of result
     */
    public function count_filtered($archive = false){
        $this->_get_datatables_query($archive);
        $this->_get_filter_by_column();
        $this->_date_filter();
        $this->_payment_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        // $this->db->where("ROWNUM <=",100000,false);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /**
     * Count all result
     * @param  boolean $archive Check if archive or live data
     * @return integer Count of all result
     */
    public function count_all($archive = false){
        $this->db->from($archive ? $this->_arc_list_view : $this->_list_view);
        $this->_check_permission();
        $this->db->where("ROWNUM <=",100000,false);
        return $this->db->count_all_results();
    }

    /**
     * Show document history
     * @param  string  $criteria   [description]
     * @param  string  $order      [description]
     * @param  integer $actionType [description]
     * @return               [description]
     */
    public function ra_action($id,$criteria='DATE_VIEWED',$order = 'DESC',$actionType = 1){
       
        $query = $this->db->select($criteria)->where('REF_NO',$id)->order_by($criteria, $order)->get(($actionType == 1) ? $this->_table_vw : $this->_table_dl);
        return $query->row();
    }

    /**
     * Get details from view by pk
     * @param  integer $id  ID of credit advice document
     * @return object       Result of query     
     */
    public function vget_by($option,$id){
        $query = $this->db->get_where(($option == 1) ? $this->_list_view : $this->_arc_list_view,['REF_NO'=>$id])->row();
        return $query;
    }

    public function vget_by_many($option){
        $this->db->from(($option == 1) ? $this->_dts_view : $this->_arc_dts_view);
        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents
        $this->db->where_in('REF_NO',$selected_arr);
        $query = $this->db->get();
        return $query->result();

    }


    public function vget_body($option = 1 ,$ref_no= null){
        $query = $this->db->get_where(($option == 1) ? $this->_dts_view : $this->_arc_dts_view,['REF_NO'=>$ref_no]);
        return $query->result();
    }


    /**
     * Archive or Unarchive credit advice document
     * @param  string $option  Check for live or archive
     * @return boolean         True
     */
    public function delete_ra_doc($option = 'live'){
        $insert_table = ($option == "live") ? $this->_archive : $this->_table;
        $delete_table = ($option == "live") ? $this->_table : $this->_archive;
        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents
        $this->db->from($delete_table);
        reset($selected_arr);
        $first = key($selected_arr);
        $to_string = '';
        foreach($selected_arr as $key => $ids){
            if ($key === $first){
                $to_string .= 'WHERE REF_NO ='.$ids.'';
                $this->db->where(['REF_NO'=>$ids]);
            }
            else{
                $to_string .= "OR REF_NO='".$ids."'";
                $this->db->or_where('REF_NO',$ids);
            }    
        }

        $query = $this->db->get();
        $a = 0;
        $arr = [];
        foreach($query->result() as $l){

            $arr[] = [
                'DATE_CREATED'=>    $l->DATE_CREATED,
                'PROC_DATE'=>       $l->PROC_DATE,
                'CHECK_DATE'=>      $l->CHECK_DATE,
                'CHECK_NO'=>        $l->CHECK_NO,
                'SEQ_NO'=>          $l->SEQ_NO,
                'DOC_NO'=>          $l->DOC_NO,
                'AMOUNT'=>          $l->AMOUNT,
                'CMTAG'=>           $l->CMTAG,
                'SCOUNT'=>          $l->SCOUNT,
                'REF_NO'=>          $l->REF_NO,
                'VENDOR_ID'=>       $l->VENDOR_ID,
                'BANK_ID'=>         $l->BANK_ID,
                'BRANCH_ID'=>       $l->BRANCH_ID,
                'DOC_TYPE_ID'=>     $l->DOC_TYPE_ID,
                'PAY_MODE_ID'=>     $l->PAY_MODE_ID,
                'FILE_QUEUE_ID'=>   $l->FILE_QUEUE_ID,
                'VIEWED'=>          $l->VIEWED,
                'DOWNLOADED'=>      $l->DOWNLOADED,
                'STATUS'=>          $l->STATUS
            ];
            // if($option != 'live'){

            //     array_push($arr, ['STATUS'=>0]);    
            // }

            
            $a++;
        }
        // echo '<pre>';
        // print_r($arr);
        // exit;
       
        $this->db->query('DELETE FROM '.$delete_table.' '.$to_string);
        $this->db->insert_batch($insert_table,$arr);
        
         // var_dump($this->db->last_query());
        return true;
    }


     public function viewed_proc($doc,$id,$option,$viewed){
        $vendor_id = $this->vget_by($option,$id)->VENDOR_ID;
        $opData = ($option == 1) ? 'live' : 'arch';
        @$this->db->query("call sp_viewed(".$doc.", ".$id.", ".$vendor_id.", ".$viewed.", '".$opData."')");
        return true;
    }



}
?>
