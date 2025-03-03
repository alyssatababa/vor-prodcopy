<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->db->select('USER_ID,USER_FIRST_NAME');
		$result = $this->db->get('SMNTP_USERS');
		//var_dump($result);
		//
		// foreach ($result as $row)
		// {
		//    echo $row->USER_NAME;
		// }

// 		$conn = oci_connect('system', 'welcome123', '192.168.11.119/SMNTPDB');
// if (!$conn) {
//     $e = oci_error();
//     trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
// }

// $stid = oci_parse($conn, 'SELECT * FROM employees');
// oci_execute($stid);

// echo "<table border='1'>\n";
// while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
//     echo "<tr>\n";
//     foreach ($row as $item) {
//         echo "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
//     }
//     echo "</tr>\n";
// }
// echo "</table>\n";

		echo "";
		//$this->load->view('welcome_message');
	}


	public function etl($table_name = null,$filename){
		$this->load->database();
		$fields = $this->db->list_fields(strtoupper($table_name)); //Insert field table
		$result = $this->process_file($filename);
		foreach ($result as $value) { // Loop result
			if(($key = array_search('id', $fields)) !== false) { //Remove Auto increment column
			    unset($fields[$key]);
			}

			if(is_array($value)) // Value is array (csv file)
			{
			    try{
			    	foreach (array_chunk($value, substr_count($value[0], '|') + 1, true) as $array) { //Breakdown per pipe
						$csv_value = explode('|', $value[0]);
					}
			    	if($fields == $csv_value)
			    		$this->db->insert(strtoupper($table_name), array_combine($fields, $csv_value));  // Insert into DB
			    	else
			    		throw new Exception("Fields and Data column does not match");
			    		break;
			    }catch(Exception $ex){
					echo "Error: ".$ex->getMessage()."<br>";
					exit;
				}
			}
			else {
				// Value is string (text file)
				$data = explode('|',str_replace(array("\r", "\n","null",'"'), '',str_replace("'","''",$this->db->escape_str($value)))); // Clean data
				$a = 0;
				foreach ($data as $values) { // Loop data for inspections
					$checkdate = $this->isValidDateTimeString($values, '/'); // Check string if date
					$this->db->set($fields[$a], ctype_digit($values) ? $values : "'".$values."'",false); // Set fields and value
					if($checkdate)
						// $this->db->set($fields[$a],"to_date('".$values."','DD/MM/YYYY')",false); // If data is date, cast to_date
						$this->db->set($fields[$a],"STR_TO_DATE('".$values."','%d/%m/%Y')",false); // If data is date, cast to_date
					$a++;
				}
			   	try{
			    	$this->db->insert(strtoupper($table_name));
			    }catch(Exception $ex){
					echo "Error:".$ex->getMessage()."<br>";
					die();
				}
			}
		}
	}

	private function process_file($filename){
		$mime = pathinfo($filename, PATHINFO_EXTENSION);

		if($mime == "csv"){ //Process csv file
			$this->load->library('csvreader');
			$result = $this->csvreader->parse_file(FCPATH.'/files/'.$filename);
		}
		elseif ($mime == "txt") { // Process text file
			$handle = fopen(FCPATH.'/files/'.$filename, "r");
			$data = [];
		    while (($line = fgets($handle)) !== false) {
		    	array_push($data,$line);
		    }
		    $result = $data;
		    fclose($handle);
		} else{
			echo 'File extension not supported.';
		}
		return $result;
	}


	private function isValidDateTimeString($date, $separator) {
	    if (count(explode($separator,$date)) == 3) {
			$pattern = "/^([0-9]{2})\\".$separator."([0-9]{2})\\".$separator."([0-9]{4})$/";
			if (preg_match ($pattern, $date, $parts))  {
				if (checkdate($parts[1],$parts[2],$parts[3]))
				return true; // This is a valid date
				else
				return false; // This is an invalid date
			}  else  {
				return false; // This is an invalid date in terms of format
				}
			} else {
			return false; // Day, Month, Year - either of them not present
		}

	}


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
