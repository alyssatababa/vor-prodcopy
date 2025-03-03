<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model
class Dmcm_model extends MY_Model{
		
	public $_table = 'SMNTP_BD_DMCM';
    public $_view = 'SMNTP_BD_DMCM_VIEWS';
	public $primary_key = '';
	var $column_order = ['DOC_TYPE_NAME','DOC_NO','COMPANY_NAME','BRANCH_NAME','NATURE_NAME','AMOUNT','','CHECK_NO','DATE_CREATED']; //set column field database for datatable orderable
	var $filter_column = ['DOC_TYPE_NAME'=>0,'DOC_NO' => 1,'COMPANY_NAME'=>2,'BRANCH_NAME'=>3]; //Filter by column (index number of datatable )
	var $column_search = ['DOC_TYPE_NAME','DOC_NO','CHECK_NO','AMOUNT','NATURE_NAME','BRANCH_NAME','COMPANY_NAME']; //set column field database for datatable searchable 
	var $order = ['DATE_CREATED' => 'desc']; // default order 
 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * Datatables query builder
     * @return null
     */
    private function _get_datatables_query()
    { 
        $this->db->from($this->_view );
        if(isset($this->input->post('dt_post')['order'])) // here order processing
        {
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
          $value = ($dtColumns[$key]['name'] == "COMPANY_NAME" || $dtColumns[$key]['name'] == "BRANCH_NAME") ? strtoupper($dtColumns[$a]['search']['value']) : $dtColumns[$a]['search']['value'];
          $this->db->like($wLike, $value);
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
                $this->db->where("EXTRACT(month from PROC_DATE) =",$date['date_month']);
                if($date['date_year'])
                    $this->db->where('EXTRACT(year from PROC_DATE) =',$date['date_year']);
                break;
            case 2: // MTD selected
                $this->db->where('PROC_DATE >=',date('m/d/Y', strtotime($date['date_mtdFrom'])),false);
                $this->db->where('PROC_DATE <=',date('m/d/Y', strtotime($date['date_mtdTo'])),false);
                break;
            default:
                $this->db->where('PROC_DATE >=',date('m/d/Y', strtotime($date['date_from'])),false);
                $this->db->where('PROC_DATE <=',date('m/d/Y', strtotime($date['date_to'])),false);
                break;
        }
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

        $this->db->where('COMPANY_NAME',$this->input->post('dt_post')['company_name']);
        
    }

    /**
     * Store filter
     * @return null
     */
    private function _store_filter(){
        $storename = $this->input->post('dt_post')['store_name'];
        if($storename === "0" || $storename == ""){
            return;
        }
        $this->db->where('BRANCH_NAME',$this->input->post('dt_post')['store_name']);
    }



    /**
     * Return all query
     * @return array Result of query
     */
    public function get_datatables()
    {
        $this->_get_datatables_query();
        $this->_get_filter_by_column();
        $this->_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        $this->_company_filter();
        $this->_store_filter();
        if($this->input->post('dt_post')['length'] != -1)
        $this->db->limit($this->input->post('dt_post')['length'], $this->input->post('dt_post')['start']);
        $query = $this->db->get();
       
        return $query->result();
    }
    
    /**
     * Count of filtered result
     * @return integer Count of result
     */
    public function count_filtered()
    {
    	$this->_get_datatables_query();
        $this->_get_filter_by_column();
        $this->_date_filter();
        $this->_vendcode();
        $this->_check_permission();
        $this->_company_filter();
        $this->_store_filter();
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    /**
     * Count all result
     * @return integer Count of all result
     */
    public function count_all()
    {
        $this->db->from($this->_view);
        $this->_check_permission();
        return $this->db->count_all_results();
    }


    public function search_company(){
        // $db = ($this->input->get('option') == 'archive') ? $this->_view : $this->_view;
        $this->db->select('COMPANY_NAME');
        $this->db->like('UPPER(COMPANY_NAME)', strtoupper($this->input->get('q'))); 
        if($this->input->get('vendor_id')){
          $this->db->where('VENDOR_ID',$this->input->get('vendor_id'));
        }
        $this->db->group_by('COMPANY_NAME');
        $query = $this->db->get($this->_view);

        // return $this->db->last_query(); 
        return $query->result(); //$this->db->last_query(); 
    }

    public function search_store(){
        // $db = ($this->input->get('option') == 'archive') ? $this->_view : $this->_view;
        $this->db->select('BRANCH_NAME');
        $this->db->like('UPPER(BRANCH_NAME)', strtoupper($this->input->get('q'))); 
        if($this->input->get('vendor_id')){
          $this->db->where('VENDOR_ID',$this->input->get('vendor_id'));
        }
        $this->db->group_by('BRANCH_NAME');
        $query = $this->db->get($this->_view);

        // return $this->db->last_query(); 
        return $query->result(); //$this->db->last_query(); 
    }




}
?>
