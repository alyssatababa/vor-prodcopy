<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
class Business_doc_model extends MY_Model{
	
    private $_ca_table = 'SMNTP_BD_BDOCA';
    private $_ca_arc_table = 'SMNTP_BD_BDOCA_ARCHIVE';
    private $_ca_vw_table = 'SMNTP_BD_BDOCA_VIEW';
    private $_ca_view_cred = 'SMNTP_VIEW_CREDIT';
    private $_ca_dl_ca = 'SMNTP_BD_DL_CA';

    private $_dmcm_table = 'SMNTP_BD_DMCM';

    private $_ra_table = 'SMNTP_BD_RA_HEAD_VIEW';
    private $_ra_arc_table = 'SMNTP_BD_RA_ARCHIVE';
    private $_ra_vw_table = 'SMNTP_BD_RA_VIEW';
    private $_ra_dl_vw = 'SMNTP_BD_DL_RA';
    private $_ra_view = 'SMNTP_VIEW_RA';

    private $_po_table = 'SMNTP_BD_POHEAD_VIEW';
    private $_po_arc_table = 'SMNTP_BD_POHEAD_ARC_VIEW';
    private $_po_vw_table = 'SMNTP_BD_PO_VIEW';
    private $_po_dl_vw = 'SMNTP_BD_DL_PO';
    private $_po_view = 'SMNTP_VIEW_PO';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /**
     * Show notification for unread document
     * @param  [number] $vendor_id ID of vendor
     * @return [array]            Array of each business doc counts
     */
    public function notifications($vendor_id = null){
        $tables = [$this->_ca_table,$this->_dmcm_table,$this->_ra_table,$this->_po_table];
        $this->db->select('STATUS');
        $this->db->where('STATUS',0);
        if($vendor_id){
            $this->db->where('VENDOR_ID',$vendor_id);
            $ca = $this->db->from($tables[0])->count_all_results();
            $this->db->where('VENDOR_ID',$vendor_id);
            $dmcm = $this->db->from($tables[1])->count_all_results();
            $this->db->where('VENDOR_ID',$vendor_id);
            $this->db->where('READ_STATUS',0);
            $ra = $this->db->from($tables[2])->count_all_results();
            $this->db->where('VENDOR_ID',$vendor_id);
            $po = $this->db->from($tables[3])->count_all_results();
        } else{
            $ca = $this->db->from($tables[0])->count_all_results();
            $dmcm = $this->db->from($tables[1])->count_all_results();
            $this->db->where('READ_STATUS',0);
            $ra = $this->db->from($tables[2])->count_all_results();
            $po = $this->db->from($tables[3])->count_all_results();
        }
        $notif = ['CA'=>$ca,'DMCM'=>$dmcm,'RA'=>$ra,'PO'=>$po];
        return $notif;
    }

    /**
     * For data filter. Show available dates only
     * @return [array] Set of dates available in db
     */
    public function available_dates(){
        if($this->input->post('vendor_id') > 0){
            $vendorsql = 'WHERE VENDOR_ID = '.$this->input->post('vendor_id');    
        } else{
            $vendorsql = '';
        }
        
        $query =  $this->db->query('SELECT EXTRACT('.$this->input->post('criteria').' FROM '.$this->input->post('column').') "'.$this->input->post('criteria').'" FROM '.$this->input->post('table').' '.$vendorsql.' GROUP BY EXTRACT('.$this->input->post('criteria').' FROM '.$this->input->post('column').') ORDER BY EXTRACT('.$this->input->post('criteria').' FROM '.$this->input->post('column').')');
       return $query->result_array();
    }

    /**
     * Function for check data in the business doc table and archive
     * @param  string $category  Type of business doc
     * @param  number $vendor_id ID of vendor
     * @return boolean           True or false
     */
    public function check_reports($category,$vendor_id){
        if($category == "ca"){ //Credit Advice
            $ca = $this->db->get_where($this->_ca_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            $ca_arc = $this->db->get_where($this->_ca_arc_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            if($ca == 0 && $ca_arc == 0){
                return false;
            }
            continue;
        } else if($category == "dmcm"){
            $dmcm = $this->db->get_where($this->_dmcm_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            if($dmcm == 0){
                return false;
            }
            continue;
        } else if($category == "ra"){
            $ra = $this->db->get_where($this->_ra_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            $ra_arc = $this->db->get_where($this->_ra_arc_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            if($ra == 0 && $ra_arc == 0){
                return false;
            }
            continue;
        } else if($category == "po"){
            $po = $this->db->get_where($this->_po_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            $po_arc = $this->db->get_where($this->_po_arc_table,['VENDOR_ID'=>$vendor_id])->num_rows();
            if($po == 0 && $po_arc == 0){
                return false;
            }
            continue;  
        } else{

            return false;
        }
        return true;
    }

    /**
     * Get view and download of document
     * @param  integer  $doctype   Document type 
     * @param  integer  $vendor_id ID of vendor
     * @param  integer  $id        ID of document
     * @return array               List of views and downloads
     */
    public function view_downloads($doctype = 2,$vendor_id = 0,$id = 0){
        $data = [];
        if($vendor_id){
            $vendsql = 'WHERE VENDOR_ID = '. $vendor_id. ' AND';
            // $where['VENDOR_ID'] = $vendor_id;
        } else {
            $vendsql = 'WHERE';
        }
        
        switch ($doctype) {
            case 1: //Purchase Order
                $where['PO_NUMBER'] = $id;
                $vwc = 0;
                $dlc = 0;
                $viewedsql = "SELECT * FROM ".$this->_po_view." ".$vendsql." PO_NUMBER = ".$where['PO_NUMBER']." AND LIMIT 100 ORDER BY DATE_VIEWED DESC";
                $viewed = $this->db->query($viewedsql)->result();
                foreach($viewed as $vw){
                    $date_vw = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw->DATE_VIEWED);
                    $data['vw'][$vwc]['VENDOR_ID'] = $vw->VENDOR_ID;
                    $data['vw'][$vwc]['VENDOR_NAME'] = $vw->VENDOR_NAME;
                    $data['vw'][$vwc]['PO_NUMBER'] = $vw->PO_NUMBER;
                    $data['vw'][$vwc]['DATE'] = $date_vw->format('m/d/Y H:i:s');
                    $data['vw'][$vwc]['TYPE'] = 1;
                    $data['vw'][$vwc]['FILE_TYPE'] = '';
                    $vwc++;
                }
                $downloadedsql = "SELECT * FROM ".$this->_po_dl_vw." ".$vendsql." PO_NUMBER = ".$where['PO_NUMBER']." AND LIMIT 100 ORDER BY DATE_DOWNLOADED DESC";
                $downloaded = $this->db->query($downloadedsql)->result();
                // $downloaded = $this->db->where($where)->order_by('DATE_DOWNLOADED','DESC')->get($this->_po_dl_vw,100)->result();
                foreach($downloaded as $dl){
                    $date_dl = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl->DATE_DOWNLOADED);
                    $data['dl'][$dlc]['VENDOR_ID'] = $dl->VENDOR_ID;
                    $data['dl'][$dlc]['VENDOR_NAME'] = $dl->VENDOR_NAME;
                    $data['dl'][$dlc]['PO_NUMBER'] = $dl->PO_NUMBER;
                    $data['dl'][$dlc]['DATE'] = $date_dl->format('m/d/Y H:i:s');
                    $data['dl'][$dlc]['TYPE'] = 2;
                    $data['dl'][$dlc]['FILE_TYPE'] = $dl->FILE_TYPE;
                    $dlc++;
                }
                break;
            case 2: //Credit Advice
                $where['CHECK_NO'] = $id;
                $vwc = 0;
                $dlc = 0;
                $viewedsql = "SELECT * FROM ".$this->_ca_view_cred." ".$vendsql." CHECK_NO = ".$where['CHECK_NO']." AND LIMIT 100 ORDER BY DATE_VIEWED DESC";
                $viewed = $this->db->query($viewedsql)->result();

                // ->result();
                // $viewed = $this->db->where($where)->order_by('DATE_VIEWED','DESC')->get($this->_ca_view_cred,100)->result();
                foreach($viewed as $vw){
                    $date_vw = DateTime::createFromFormat('j-M-y h.i.s.u A',$vw->DATE_VIEWED);
                    $data['vw'][$vwc]['VENDOR_ID'] = $vw->VENDOR_ID;
                    $data['vw'][$vwc]['VENDOR_NAME'] = $vw->VENDOR_NAME;
                    $data['vw'][$vwc]['CHECK_NO'] = $vw->CHECK_NO;
                    $data['vw'][$vwc]['DATE'] = $date_vw->format('m/d/Y H:i:s');
                    $data['vw'][$vwc]['TYPE'] = 1;
                    $data['vw'][$vwc]['FILE_TYPE'] = '';
                    $vwc++;
                }
                $downloadedsql = "SELECT * FROM ".$this->_ca_dl_ca." ".$vendsql." CHECK_NO = ".$where['CHECK_NO']." AND LIMIT 100 ORDER BY DATE_DOWNLOADED DESC";
                $downloaded = $this->db->query($downloadedsql)->result();
                // $downloaded = $this->db->where($where)->order_by('DATE_DOWNLOADED','DESC')->get($this->_ca_dl_ca,100)->result();

                foreach($downloaded as $dl){
                    $date_dl = DateTime::createFromFormat('j-M-y h.i.s.u A',$dl->DATE_DOWNLOADED);
                    $data['dl'][$dlc]['VENDOR_ID'] = $dl->VENDOR_ID;
                    $data['dl'][$dlc]['VENDOR_NAME'] = $dl->VENDOR_NAME;
                    $data['dl'][$dlc]['CHECK_NO'] = $dl->CHECK_NO;
                    $data['dl'][$dlc]['DATE'] = $date_dl->format('m/d/Y H:i:s');
                    $data['dl'][$dlc]['TYPE'] = 2;
                    $data['dl'][$dlc]['FILE_TYPE'] = $dl->FILE_TYPE;
                    $dlc++;
                }

                break;
            case 5: //Remittance Advice
                $where['REF_NO'] = $id;
                $vwc = 0;
                $dlc = 0;
                $viewedsql = "SELECT * FROM ".$this->_ra_view." ".$vendsql." REF_NO = ".$where['REF_NO']." AND LIMIT 100 ORDER BY DATE_VIEWED DESC";
                $viewed = $this->db->query($viewedsql)->result();
                // $viewed = $this->db->where($where)->order_by('DATE_VIEWED','DESC')->get($this->_ra_view,100)->result();
                foreach($viewed as $vw){
                    $date_vw =DateTime::createFromFormat('j-M-y h.i.s.u A',$vw->DATE_VIEWED);
                    $data['vw'][$vwc]['VENDOR_ID'] = $vw->VENDOR_ID;
                    $data['vw'][$vwc]['VENDOR_NAME'] = $vw->VENDOR_NAME;
                    $data['vw'][$vwc]['REF_NO'] = $vw->REF_NO;
                    $data['vw'][$vwc]['DATE'] = $date_vw->format('m/d/Y H:i:s');
                    $data['vw'][$vwc]['TYPE'] = 1;
                    $data['vw'][$vwc]['FILE_TYPE'] = '';
                    $vwc++;
                }
                $downloadedsql = "SELECT * FROM ".$this->_ra_dl_vw." ".$vendsql." REF_NO = ".$where['REF_NO']." AND LIMIT 100 ORDER BY DATE_DOWNLOADED DESC";
                $downloaded = $this->db->query($downloadedsql)->result();
                // $downloaded = $this->db->where($where)->order_by('DATE_DOWNLOADED','DESC')->get($this->_ra_dl_vw,100)->result();
                foreach($downloaded as $dl){
                    $date_dl =DateTime::createFromFormat('j-M-y h.i.s.u A',$dl->DATE_DOWNLOADED);
                    $data['dl'][$dlc]['VENDOR_ID'] = $dl->VENDOR_ID;
                    $data['dl'][$dlc]['VENDOR_NAME'] = $dl->VENDOR_NAME;
                    $data['dl'][$dlc]['REF_NO'] = $dl->REF_NO;
                    $data['dl'][$dlc]['DATE'] = $date_dl->format('m/d/Y H:i:s');
                    $data['dl'][$dlc]['TYPE'] = 2;
                    $data['dl'][$dlc]['FILE_TYPE'] = $dl->FILE_TYPE;
                    $dlc++;
                }
                break;
        }


        return $data;
    }
}
?>
