<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model
ini_set("memory_limit", "100000M");
ini_set('max_execution_time', 60*60*2);
class Purchase_order_model extends MY_Model{
		
	public $_table = 'SMNTP_BD_POLINES';
    public $_archive = 'SMNTP_BD_POLINES_ARCHIVE';
    public $_head_tb = 'SMNTP_BD_POHEAD';
    public $_head_arc_tb = 'SMNTP_BD_POHEAD_ARCHIVE';

    public $_table_vw = 'SMNTP_BD_PO_VIEW';
    public $_table_dl = 'SMNTP_BD_PO_DOWNLOAD';

    public $_list_view = 'SMNTP_BD_POHEAD_VIEW';
    public $_arc_list_view = 'SMNTP_BD_POHEAD_ARC_VIEW';

    public $_dts_view = 'SMNTP_BD_POLINES_VIEW';
    public $_dts_hdr_view = 'SMNTP_BD_POLINES_HDR_VIEW';
    public $_arc_dts_hdr_view = 'SMNTP_BD_POLINES_ARC_HDR_VIEW';
    public $_arc_dts_view = 'SMNTP_BD_POLINES_ARC_VIEW';

	public $primary_key = 'PO_NUMBER';

	var $column_order = ['','PO_NUMBER','PO_STATUS','ENTRY_DATE','EXPECTED_RECEIPT_DATE','CANCEL_DATE','TOTAL_AMOUNT','DEPARTMENT_NAME','LOCATION','COMPANY_NAME','POST_DATE','READ_STATUS']; //set column field database for datatable orderable
	var $filter_column = ['PO_NUMBER' => 1,'PO_STATUS'=>2,'COMPANY_NAME'=>9,'LOCATION'=>8,'DEPARTMENT_NAME'=>7,'READ_STATUS'=>11]; //Filter by column (index number of datatable )
	var $column_search = ['PO_NUMBER','PO_STATUS','READ_STATUS']; //set column field database for datatable searchable 
	var $order = ['POST_DATE' => 'desc']; // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
 
    private function _get_datatables_query($archive){ 

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
                if($wSearch) //Check for filtered column variable
                { 
                    $wLike = array_search($key, $this->filter_column);
                    if($wLike == 'PO_STATUS') //Filter for status should be where not like
                    {   
                        $this->db->where($wLike,$dtColumns[$a]['search']['value']);
                    }else
                    {
                        $this->db->like($wLike, $dtColumns[$a]['search']['value']);
                    }
                    
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
            $this->db->where('VENDOR_ID',$this->input->post('dt_post')['vend_id']);
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
                $this->db->where("EXTRACT(month from POST_DATE) =",$date['date_month']);
                if($date['date_year'])
                    $this->db->where('EXTRACT(year from POST_DATE) =',$date['date_year']);
                break;
            case 2: // MTD selected
                $this->db->where('POST_DATE >=',date('m/d/Y', strtotime($date['date_mtdFrom'])),false);
                $this->db->where('POST_DATE <=',date('m/d/Y', strtotime($date['date_mtdTo'])),false);
                break;
            default:
                $this->db->where('POST_DATE >=',date('m/d/Y', strtotime($date['date_from'])),false);
                $this->db->where('POST_DATE <=',date('m/d/Y', strtotime($date['date_to'])),false);
                break;
        }
    }

    /**
     * Other date  filter
     * @return null
     */
    private function _other_date_filter(){
        $date = $this->input->post('dt_post')['date'];
        if($date === "0" || $date == ""){
            return;
        }

        if($date['exp_rep_date_from'] === "" && $date['exp_rep_date_to'] === "" && $date['cancel_date_from'] === "" && $date['cancel_date_to'] === "")
            return;


        $this->db->where('EXPECTED_RECEIPT_DATE >=',date('m/d/Y', strtotime($date['exp_rep_date_from'])),false);
        $this->db->where('EXPECTED_RECEIPT_DATE <=',date('m/d/Y', strtotime($date['exp_rep_date_to'])),false);

        $this->db->where('CANCEL_DATE >=',date('m/d/Y', strtotime($date['cancel_date_from'])),false);
        $this->db->where('CANCEL_DATE <=',date('m/d/Y', strtotime($date['cancel_date_to'])),false);
    }

    /**
     * Company filter
     * @return null
     */
    private function _company_filter(){
        $company_name = $this->input->post('dt_post')['company_name'];
        if($company_name === "0" || $company_name == ""){
            return;
        }

        $this->db->where('COMPANY_NAME',$company_name);
    }
    /**
     * Location filter
     * @return null
     */
    private function _location_filter(){
        $location = $this->input->post('dt_post')['location'];
        if($location === "0" || $location == ""){
            return;
        }

        $this->db->where('LOCATION',$location);
        
    }

     /**
     * Department filter
     * @return null
     */
    private function _deptname_filter(){
        $deptname = $this->input->post('dt_post')['dept_name'];
        if($deptname === "0" || $deptname == ""){
            return;
        }

        $this->db->where('DEPARTMENT_NAME',$deptname);
        
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
        $this->_other_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        $this->_company_filter();
        $this->_location_filter();
        $this->_deptname_filter();

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
        $this->_other_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        $this->_company_filter();
        $this->_location_filter();
        $this->_deptname_filter();
        // $this->db->where("ROWNUM <=",30000,false);
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
        // $this->db->where("ROWNUM <=",30000,false);
        return $this->db->count_all_results();
    }

    /**
     * Show document history
     * @param  string  $criteria   [description]
     * @param  string  $order      [description]
     * @param  integer $actionType [description]
     * @return               [description]
     */
    public function po_action($id,$criteria='DATE_VIEWED',$order = 'DESC',$actionType = 1,$vendor_id,$count_view = 0){
        if($vendor_id){
            $query = $this->db->select($criteria)->where('PO_NUMBER',$id)->where('VENDOR_ID',$vendor_id)->order_by($criteria, $order)->get(($actionType == 1) ? $this->_table_vw : $this->_table_dl);
        } else {
            $query = $this->db->select($criteria)->where('PO_NUMBER',$id)->order_by($criteria, $order)->get(($actionType == 1) ? $this->_table_vw : $this->_table_dl);    
        }
        if($count_view == 0){
            return $query->row();
        } else {
            return count($query->result());
        }
        
    }

    /**
     * Total amount of remittance advice document 
     * @param  integer $id CHECK_NO of ra docu
     * @return float      Total amount 
    */


    /**
     * Get details from view by po_number
     * @param  integer $id  ID of purchase order document
     * @return object       Result of query     
     */
    public function vget_by($option,$id,$vendor_id){
        $data = [];
        $data['PO_NUMBER'] = $id;
        if($vendor_id){
            $data['VENDOR_ID'] = $vendor_id;    
        }


        $query = $this->db->get_where(($option == 1) ? $this->_list_view : $this->_arc_list_view,$data);
        
        return $query->row();
    }


     /**
     * Get details from view by po_number
     * @param  integer $id  ID of purchase order document
     * @return object       Result of query     
     */
    public function vget_hdr_by($option,$id,$vendor_id){
        $data = [];
        $data['PO_NUMBER'] = $id;
        if($vendor_id){
            $data['VENDOR_ID'] = $vendor_id;    
        }

        $query = $this->db->get_where(($option == 1) ? $this->_dts_hdr_view : $this->_arc_dts_hdr_view,$data);
        return $query->row();
    }



    public function vget_by_many($option){
        $this->db->from(($option == 1) ? $this->_dts_view : $this->_arc_dts_view);
        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents
        $this->db->where_in('PO_NUMBER',$selected_arr);
        $query = $this->db->get();
        return $query->result();

    }

    /**
     * Get polines data
     * @param  integer $option    [description]
     * @param  [type]  $ref_no    [description]
     * @param  [type]  $vendor_id [description]
     * @return [type]             [description]
     */
    public function vget_body($option = 1 ,$ref_no= null,$vendor_id,$comp_id){
        $data = [];
        $data['PO_NUMBER'] = $ref_no;
        if($vendor_id){
            $data['VENDOR_ID'] = $vendor_id;    
        }
        $data['COMPANY_ID'] = $comp_id;
        
        $query = $this->db->get_where(($option == 1) ? $this->_dts_view : $this->_arc_dts_view,$data);
        return $query->result();
    }


    /**
     * Archive or Unarchive credit advice document
     * @param  string $option  Check for live or archive
     * @return boolean         True
     */
    public function delete_po_doc($option = 'live'){
        $insert_table_lines = ($option == "live") ? $this->_archive : $this->_table;
        $insert_table_head = ($option == "live") ? $this->_head_arc_tb : $this->_head_tb;
        $delete_table_lines = ($option == "live") ? $this->_table : $this->_archive;
        $delete_table_head = ($option == "live") ? $this->_head_tb : $this->_head_arc_tb;

        $selected_arr = explode(',', $this->input->post('selected')); //Selected documents

        ##################Todo get data from lines table
        $this->db->from($delete_table_lines);
        reset($selected_arr);
        $first = key($selected_arr);
        $to_string_lines = '';
        foreach($selected_arr as $key => $ids){
            if ($key === $first){
                $to_string_lines .= 'WHERE PO_NUMBER ='.$ids.'';
                $this->db->where(['PO_NUMBER'=>$ids]);
            }
            else{
                $to_string_lines .= "OR PO_NUMBER='".$ids."'";
                $this->db->or_where('PO_NUMBER',$ids);
            }    
        }

        $query_lines = $this->db->get();
        #########################################

        ##################Todo get data from head table
        $this->db->from($delete_table_head);
        $to_string_head = '';
        foreach($selected_arr as $key => $ids){
            if ($key === $first){
                $to_string_head .= 'WHERE PO_NUMBER ='.$ids.'';
                $this->db->where(['PO_NUMBER'=>$ids]);
            }
            else{
                $to_string_head .= " OR PO_NUMBER='".$ids."'";
                $this->db->or_where('PO_NUMBER',$ids);
            }    
        }

        $query_head = $this->db->get();
        #########################################
        
        
        ####################lines data for inserting
        $a = 0;
        $arr = [];
        foreach($query_lines->result() as $l){

            $arr[] = [
                "FILE_QUEUE_ID"                 => $l->FILE_QUEUE_ID,         
                "ROW_NO"                        => $l->ROW_NO,                
                "VENDOR_CODE"                   => $l->VENDOR_CODE,                   
                "PO_NUMBER"                     => $l->PO_NUMBER,
                "RECEIPT_DT_STR"                => $l->RECEIPT_DT_STR,        
                "CANCEL_DT_STR"                 => $l->CANCEL_DT_STR, 
                "ENTRY_DT_STR"                  => $l->ENTRY_DT_STR,  
                "TYPE_OF_FLAG"                  => $l->TYPE_OF_FLAG, 
                "PO_LABEL"                      => $l->PO_LABEL,  
                "PO_DEPT"                       => $l->PO_DEPT,  
                "PO_SUB"                        => $l->PO_SUB,  
                "ORDER_TYPE"                    => $l->ORDER_TYPE,  
                "VENDOR_NAME"                   => $l->VENDOR_NAME,  
                "DEPARTMENT"                    => $l->DEPARTMENT,  
                "LOCATION"                      => $l->LOCATION,  
                "SHIP_TO"                       => $l->SHIP_TO,  
                "PO_TERMS"                      => $l->PO_TERMS,  
                "PO_TAGGING"                    => $l->PO_TAGGING,  
                "SKU"                           => $l->SKU,  
                "SKU_DESCR"                     => $l->SKU_DESCR,  
                "SKU_CLASS"                     => $l->SKU_CLASS,  
                "UPC"                           => $l->UPC,  
                "BUY_QTY"                       => $l->BUY_QTY,  
                "BUY_COST"                      => $l->BUY_COST,  
                "BUY_UM"                        => $l->BUY_UM,  
                "UNIT_RETAIL"                   => $l->UNIT_RETAIL,  
                "SEL_UM"                        => $l->SEL_UM, 
                "DETAIL_STYLE"                  => $l->DETAIL_STYLE, 
                "STYLE_DESCR"                   => $l->STYLE_DESCR,  
                "GRID_SKU"                      => $l->GRID_SKU,  
                "SIZE1"                         => $l->SIZE1,  
                "SIZE2"                         => $l->SIZE2,  
                "SIZE3"                         => $l->SIZE3,  
                "SIZE4"                         => $l->SIZE4,  
                "SIZE5"                         => $l->SIZE5,  
                "SIZE6"                         => $l->SIZE6,  
                "SIZE7"                         => $l->SIZE7,  
                "SIZE8"                         => $l->SIZE8,  
                "SIZE9"                         => $l->SIZE9,  
                "SIZE10"                        => $l->SIZE10,  
                "SIZE11"                        => $l->SIZE11,  
                "SIZE12"                        => $l->SIZE12,  
                "SIZE13"                        => $l->SIZE13,  
                "SIZE14"                        => $l->SIZE14,  
                "SIZE15"                        => $l->SIZE15,  
                "SKU_COLOR1"                    => $l->SKU_COLOR1, 
                "SKU_COLOR2"                    => $l->SKU_COLOR2, 
                "SKU_COLOR3"                    => $l->SKU_COLOR3, 
                "SKU_COLOR4"                    => $l->SKU_COLOR4, 
                "SKU_COLOR5"                    => $l->SKU_COLOR5, 
                "SKU_COLOR6"                    => $l->SKU_COLOR6, 
                "SKU_COLOR7"                    => $l->SKU_COLOR7, 
                "SKU_QTY_1"                     => $l->SKU_QTY_1,  
                "SKU_QTY_2"                     => $l->SKU_QTY_2,  
                "SKU_QTY_3"                     => $l->SKU_QTY_3,  
                "SKU_QTY_4"                     => $l->SKU_QTY_4,  
                "SKU_QTY_5"                     => $l->SKU_QTY_5,  
                "SKU_QTY_6"                     => $l->SKU_QTY_6,  
                "SKU_QTY_7"                     => $l->SKU_QTY_7,  
                "SKU_QTY_8"                     => $l->SKU_QTY_8,  
                "SKU_QTY_9"                     => $l->SKU_QTY_9,  
                "SKU_QTY_10"                    => $l->SKU_QTY_10,  
                "SKU_QTY_11"                    => $l->SKU_QTY_11,  
                "SKU_QTY_12"                    => $l->SKU_QTY_12,  
                "SKU_QTY_13"                    => $l->SKU_QTY_13,  
                "SKU_QTY_14"                    => $l->SKU_QTY_14,  
                "SKU_QTY_15"                    => $l->SKU_QTY_15,  
                "SKU_QTY_16"                    => $l->SKU_QTY_16,  
                "SKU_QTY_17"                    => $l->SKU_QTY_17,  
                "SKU_QTY_18"                    => $l->SKU_QTY_18,  
                "SKU_QTY_19"                    => $l->SKU_QTY_19,  
                "SKU_QTY_20"                    => $l->SKU_QTY_20,  
                "SKU_QTY_21"                    => $l->SKU_QTY_21,  
                "SKU_QTY_22"                    => $l->SKU_QTY_22,  
                "SKU_QTY_23"                    => $l->SKU_QTY_23,  
                "SKU_QTY_24"                    => $l->SKU_QTY_24,  
                "SKU_QTY_25"                    => $l->SKU_QTY_25,  
                "SKU_QTY_26"                    => $l->SKU_QTY_26,  
                "SKU_QTY_27"                    => $l->SKU_QTY_27,  
                "SKU_QTY_28"                    => $l->SKU_QTY_28,  
                "SKU_QTY_29"                    => $l->SKU_QTY_29,  
                "SKU_QTY_30"                    => $l->SKU_QTY_30,  
                "SKU_QTY_31"                    => $l->SKU_QTY_31,  
                "SKU_QTY_32"                    => $l->SKU_QTY_32,  
                "SKU_QTY_33"                    => $l->SKU_QTY_33,  
                "SKU_QTY_34"                    => $l->SKU_QTY_34,  
                "SKU_QTY_35"                    => $l->SKU_QTY_35,  
                "SKU_QTY_36"                    => $l->SKU_QTY_36,  
                "SKU_QTY_37"                    => $l->SKU_QTY_37,  
                "SKU_QTY_38"                    => $l->SKU_QTY_38,  
                "SKU_QTY_39"                    => $l->SKU_QTY_39,  
                "SKU_QTY_40"                    => $l->SKU_QTY_40,  
                "SKU_QTY_41"                    => $l->SKU_QTY_41,  
                "SKU_QTY_42"                    => $l->SKU_QTY_42,  
                "SKU_QTY_43"                    => $l->SKU_QTY_43,  
                "SKU_QTY_44"                    => $l->SKU_QTY_44,  
                "SKU_QTY_45"                    => $l->SKU_QTY_45,  
                "SKU_QTY_46"                    => $l->SKU_QTY_46,  
                "SKU_QTY_47"                    => $l->SKU_QTY_47,  
                "SKU_QTY_48"                    => $l->SKU_QTY_48,  
                "SKU_QTY_49"                    => $l->SKU_QTY_49,  
                "SKU_QTY_50"                    => $l->SKU_QTY_50,  
                "SKU_QTY_51"                    => $l->SKU_QTY_51,  
                "SKU_QTY_52"                    => $l->SKU_QTY_52,  
                "SKU_QTY_53"                    => $l->SKU_QTY_53,  
                "SKU_QTY_54"                    => $l->SKU_QTY_54,  
                "SKU_QTY_55"                    => $l->SKU_QTY_55,  
                "SKU_QTY_56"                    => $l->SKU_QTY_56,  
                "SKU_QTY_57"                    => $l->SKU_QTY_57,  
                "SKU_QTY_58"                    => $l->SKU_QTY_58,  
                "SKU_QTY_59"                    => $l->SKU_QTY_59,  
                "SKU_QTY_60"                    => $l->SKU_QTY_60,  
                "SKU_QTY_61"                    => $l->SKU_QTY_61,  
                "SKU_QTY_62"                    => $l->SKU_QTY_62,  
                "SKU_QTY_63"                    => $l->SKU_QTY_63,  
                "SKU_QTY_64"                    => $l->SKU_QTY_64,  
                "SKU_QTY_65"                    => $l->SKU_QTY_65,  
                "SKU_QTY_66"                    => $l->SKU_QTY_66,  
                "SKU_QTY_67"                    => $l->SKU_QTY_67,  
                "SKU_QTY_68"                    => $l->SKU_QTY_68,  
                "SKU_QTY_69"                    => $l->SKU_QTY_69,  
                "SKU_QTY_70"                    => $l->SKU_QTY_70,  
                "SKU_QTY_71"                    => $l->SKU_QTY_71,  
                "SKU_QTY_72"                    => $l->SKU_QTY_72,  
                "SKU_QTY_73"                    => $l->SKU_QTY_73,  
                "SKU_QTY_74"                    => $l->SKU_QTY_74,  
                "SKU_QTY_75"                    => $l->SKU_QTY_75,  
                "SKU_QTY_76"                    => $l->SKU_QTY_76,  
                "SKU_QTY_77"                    => $l->SKU_QTY_77,  
                "SKU_QTY_78"                    => $l->SKU_QTY_78,  
                "SKU_QTY_79"                    => $l->SKU_QTY_79,  
                "SKU_QTY_80"                    => $l->SKU_QTY_80,  
                "SKU_QTY_81"                    => $l->SKU_QTY_81,  
                "SKU_QTY_82"                    => $l->SKU_QTY_82,  
                "SKU_QTY_83"                    => $l->SKU_QTY_83,  
                "SKU_QTY_84"                    => $l->SKU_QTY_84,  
                "SKU_QTY_85"                    => $l->SKU_QTY_85,  
                "SKU_QTY_86"                    => $l->SKU_QTY_86,  
                "SKU_QTY_87"                    => $l->SKU_QTY_87,  
                "SKU_QTY_88"                    => $l->SKU_QTY_88,  
                "SKU_QTY_89"                    => $l->SKU_QTY_89,  
                "SKU_QTY_90"                    => $l->SKU_QTY_90,  
                "SKU_QTY_91"                    => $l->SKU_QTY_91,  
                "SKU_QTY_92"                    => $l->SKU_QTY_92,  
                "SKU_QTY_93"                    => $l->SKU_QTY_93,  
                "SKU_QTY_94"                    => $l->SKU_QTY_94,  
                "SKU_QTY_95"                    => $l->SKU_QTY_95,  
                "SKU_QTY_96"                    => $l->SKU_QTY_96,  
                "SKU_QTY_97"                    => $l->SKU_QTY_97,  
                "SKU_QTY_98"                    => $l->SKU_QTY_98,  
                "SKU_QTY_99"                    => $l->SKU_QTY_99,  
                "SKU_QTY_100"                   => $l->SKU_QTY_100,  
                "SKU_QTY_101"                   => $l->SKU_QTY_101,  
                "SKU_QTY_102"                   => $l->SKU_QTY_102,  
                "SKU_QTY_103"                   => $l->SKU_QTY_103,  
                "SKU_QTY_104"                   => $l->SKU_QTY_104,  
                "SKU_QTY_105"                   => $l->SKU_QTY_105,  
                "NOTES1"                        => $l->NOTES1,  
                "NOTES2"                        => $l->NOTES2,  
                "ORDER_BY"                      => $l->ORDER_BY,  
                "BUYER_NAME"                    => $l->BUYER_NAME,
                "APPROVED_BY"                   => $l->APPROVED_BY,  
                "STATUS"                        => $l->STATUS, 
                "PO_TYPE"                       => $l->PO_TYPE, 
                "TOTAL_COST"                    => $l->TOTAL_COST,
                "TOTAL_RETAIL"                  => $l->TOTAL_RETAIL,  
                "COMMENT1"                      => $l->COMMENT1,  
                "COMMENT2"                      => $l->COMMENT2,  
                "COMMENT3"                      => $l->COMMENT3,  
                "NET_BUY_COST"                  => $l->NET_BUY_COST,
                "EXTENDED_COST"                 => $l->EXTENDED_COST,  
                "EXTENDED_RTL"                  => $l->EXTENDED_RTL, 
                "DISCOUNT1"                     => $l->DISCOUNT1,  
                "DISCOUNT2"                     => $l->DISCOUNT2,  
                "DISCOUNT3"                     => $l->DISCOUNT3,  
                "DISCOUNT4"                     => $l->DISCOUNT4,  
                "DISCOUNT5"                     => $l->DISCOUNT5,  
                "DISCOUNT6"                     => $l->DISCOUNT6,  
                "DISCOUNT7"                     => $l->DISCOUNT7,  
                "DISCOUNT8"                     => $l->DISCOUNT8,  
                "DISCOUNT9"                     => $l->DISCOUNT9,  
                "DISCOUNT10"                    => $l->DISCOUNT10,  
                "DISCOUNT11"                    => $l->DISCOUNT11,  
                "DISCOUNT12"                    => $l->DISCOUNT12,  
                "DISCOUNT13"                    => $l->DISCOUNT13,  
                "DISCOUNT14"                    => $l->DISCOUNT14,  
                "DISCOUNT15"                    => $l->DISCOUNT15,  
                "DISCOUNT16"                    => $l->DISCOUNT16,  
                "DISCOUNT17"                    => $l->DISCOUNT17,  
                "DISCOUNT18"                    => $l->DISCOUNT18,  
                "DISCOUNT19"                    => $l->DISCOUNT19,  
                "DISCOUNT20"                    => $l->DISCOUNT20,  
                "PO_TAG"                        => $l->PO_TAG,
                "SKU_SUB_CLASS"                 => $l->SKU_SUB_CLASS,
                "VENDORS_PART_NUMBER"           => $l->VENDORS_PART_NUMBER,
                "RECEIVERS_NOTE_1"              => $l->RECEIVERS_NOTE_1,  
                "REFERENCE_PO_NUMBER"           => $l->REFERENCE_PO_NUMBER,
                "COMPANY_NUMBER"                => $l->COMPANY_NUMBER,  
                "STORE_CODE"                    => $l->STORE_CODE, 
                "PO_TYPE_DESCRIPTION"           => $l->PO_TYPE_DESCRIPTION,
                "ERROR_MESSAGE"                 => $l->ERROR_MESSAGE,  
                "VENDOR_ID"                     => $l->VENDOR_ID,  
                "COMPANY_ID"                    => $l->COMPANY_ID,  
                "VENDOR_PART_NO"                => $l->VENDOR_PART_NO,
                "TYPE_OF_TAG"                   => $l->TYPE_OF_TAG,  
                "TOTAL_PO_RETAIL"               => $l->TOTAL_PO_RETAIL,
                "TOTAL_PO_AMOUNT"               => $l->TOTAL_PO_AMOUNT,  
                "TAG_PO"                        => $l->TAG_PO,  
                "SKU_COMMENT1"                  => $l->SKU_COMMENT1, 
                "SKU_COMMENT2"                  => $l->SKU_COMMENT2,  
                "SKU_COMMENT3"                  => $l->SKU_COMMENT3,  
                "SELL_UOM"                      => $l->SELL_UOM,  
                "RELEASE_DATE"                  => $l->RELEASE_DATE,
                "RECEIVING_NOTES"               => $l->RECEIVING_NOTES,  
                "QUANTITY"                      => $l->QUANTITY,  
                "PO_NOTES1"                     => $l->PO_NOTES1,  
                "PO_NOTES2"                     => $l->PO_NOTES2,  
                "PO_LAYOUT"                     => $l->PO_LAYOUT,  
                "PO_DATE"                       => $l->PO_DATE, 
                "ORDER_BY_ID"                   => $l->ORDER_BY_ID,
                "MARKET_PRICE"                  => $l->MARKET_PRICE,  
                "LABEL"                         => $l->LABEL, 
                "ITEM_DESC"                     => $l->ITEM_DESC,
                "FINAL_APPROVER_NAME"           => $l->FINAL_APPROVER_NAME,
                "APPROVER1_NAME"                => $l->APPROVER1_NAME,  
                "DISCOUNT"                      => $l->DISCOUNT,  
                "DESCRIPTION_2"                 => $l->DESCRIPTION_2,  
                "DEPT"                          => $l->DEPT,
                "DELIVER_TO_LOC"                => $l->DELIVER_TO_LOC,
                "DELIVER_DATE"                  => $l->DELIVER_DATE,
                "CURRENCY"                      => $l->CURRENCY,
                "CLASS"                         => $l->CLASS,
                "CANCEL_DATE"                   => $l->CANCEL_DATE,
                "BUY_UOM"                       => $l->BUY_UOM,
                "BRANCH"                        => $l->BRANCH, 
                "BRANCH_ID"                     => $l->BRANCH_ID,
                "DOWNLOADED"                    => $l->DOWNLOADED,
                "VIEWED"                        => $l->VIEWED,
                "READ_STATUS"                   => $l->READ_STATUS
            ];
            $a++;
        }
        
        $this->db->query('DELETE FROM '.$delete_table_lines.' '.$to_string_lines);
        $this->db->insert_batch($insert_table_lines,$arr);
        
        ############################################
        
        ####################head data for inserting
        $a = 0;
        $arr_head = [];
        foreach($query_head->result() as $l){

            $arr_head[] = [
                "VENDOR_ID"         =>  $l->VENDOR_ID,
                "VENDOR_NAME"       =>  $l->VENDOR_NAME,
                "PO_DATE"           =>  $l->PO_DATE,
                "DEPARTMENT"        =>  $l->DEPARTMENT,
                "COMPANY_NAME"      =>  $l->COMPANY_NAME,
                "PO_NUMBER"         =>  $l->PO_NUMBER,
                "DELIVER_TO_LOC"    =>  $l->DELIVER_TO_LOC,
                "DELIVERY_DATE"     =>  $l->DELIVERY_DATE,
                "CANCEL_DATE"       =>  $l->CANCEL_DATE,
                "STATUS"            =>  $l->STATUS,
                "TOTAL_AMOUNT"      =>  $l->TOTAL_AMOUNT,
                "APPROVED_DATE"     =>  $l->APPROVED_DATE,
                "BRANCH_ID"         =>  $l->BRANCH_ID,
                "BRANCH_NAME"       =>  $l->BRANCH_NAME,
                "VENDOR_CODE"       =>  $l->VENDOR_CODE,
                "ROW_NO"            =>  $l->ROW_NO,
                "FILE_QUEUE_ID"     =>  $l->FILE_QUEUE_ID,
                "LOCATION_ID"       =>  $l->LOCATION_ID,
                "DATE_CREATED"      =>  $l->DATE_CREATED,
                "COMPANY_ID"        =>  $l->COMPANY_ID,
                "DEPT_CODE"         =>  $l->DEPT_CODE,
                "STORE_NO"          =>  $l->STORE_NO,
                "COMPANY_NO"        =>  $l->COMPANY_NO,
                "REF_NO"            =>  $l->REF_NO,
                "RELEASE_DATE"      =>  $l->RELEASE_DATE,
                "CANCEL_DATE_STR"   =>  $l->CANCEL_DATE_STR,
                "RECEIPT_DATE_STR"  =>  $l->RECEIPT_DATE_STR,
                "LOCATION   "       =>  $l->LOCATION,
                "ENTRY_DATE_STR"    =>  $l->ENTRY_DATE_STR,
                "DOWNLOADED"        =>  $l->DOWNLOADED,
                "VIEWED"            =>  $l->VIEWED,
                "READ_STATUS"      =>  $l->READ_STATUS
            ];
            $a++;
        }
        $this->db->query('DELETE FROM '.$delete_table_head.' '.$to_string_head);
        $this->db->insert_batch($insert_table_head,$arr_head);
        ############################################

        return true;
    }


    public function viewed_proc($doc,$id,$option,$viewed,$vendor_id){
        // $vendor_id = $this->vget_by($option,$id)->VENDOR_ID;
        $opData = ($option == 1) ? 'live' : 'arch';
        // @$this->db->query("call sp_viewed(".$doc.", ".$id.", ".$vendor_id.", ".($viewed ? $viewed : 0).", '".$opData."')");
        return $vendor_id;
    }

    public function update_readstat($id){
        $data = ['READ_STATUS'=> 1];
        $this->db->where('PO_NUMBER',$id);
        $this->db->update($this->_table,$data); //PO Lines

        $this->db->where('PO_NUMBER',$id);
        $this->db->update($this->_head_tb,$data); //PO Head
    }


    public function search_company($id){
        $db = ($this->input->get('option') == 'archive') ? $this->_arc_list_view : $this->_list_view;
        $this->db->select('COMPANY_NAME');
        $this->db->like('UPPER(COMPANY_NAME)', strtoupper($this->input->get('q'))); 
        if($this->input->get('vendor_id')){
             $this->db->where('VENDOR_ID',$this->input->get('vendor_id'));
        }
        $this->db->group_by('COMPANY_NAME');
        $query = $this->db->get($db);

        // return $this->db->last_query(); 
        return $query->result(); //$this->db->last_query(); 
    }

    public function search_location($id){
        $db = ($this->input->get('option') == 'archive') ? $this->_arc_list_view : $this->_list_view;
        $this->db->select('LOCATION');
        $this->db->like('UPPER(LOCATION)', strtoupper($this->input->get('q'))); 
        if($this->input->get('vendor_id')){
             $this->db->where('VENDOR_ID',$this->input->get('vendor_id'));
        }
        $this->db->group_by('LOCATION');
        $query = $this->db->get($db);

        // return $this->db->last_query(); 
        return $query->result(); //$this->db->last_query(); 
    }

    public function search_deptname($id){
        // var_dump($this->input->get('option'));
        $db = ($this->input->get('option') == 'archive') ? $this->_arc_list_view : $this->_list_view;
        $this->db->select('DEPARTMENT_NAME');
        $this->db->like('UPPER(DEPARTMENT_NAME)', strtoupper($this->input->get('q'))); 
        if($this->input->get('vendor_id')){
             $this->db->where('VENDOR_ID',$this->input->get('vendor_id'));
        }
        $this->db->group_by('DEPARTMENT_NAME');
        $query = $this->db->get($db);

        // return $this->db->last_query(); 
        return $query->result(); //$this->db->last_query(); 
    }



}
?>
