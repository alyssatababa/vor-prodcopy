<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model
class Vendor_model extends MY_Model{
		
	public $_table = 'SMNTP_VENDOR';
	public $primary_key = 'VENDOR_ID';


    public function __construct()
    {
        parent::__construct();
    }


}
?>
