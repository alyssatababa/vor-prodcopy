<?Php	defined('BASEPATH') OR exit('No direct script access allowed');
//For Reference https://github.com/jamierumbelow/codeigniter-base-model
class Payor_model extends MY_Model{
		
	public $_table = 'SMNTP_PAYOR';
	public $primary_key = 'PAYOR_ID';


    public function __construct()
    {
        parent::__construct();
    }


}
?>
