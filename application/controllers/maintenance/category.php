<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends CI_Controller {

	public function index()
	{		
		$this->load->view('maintenance/vfm/category');
	}

	public function add_category()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CATEGORY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'BUSINESS_TYPE' => $n[2],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/category_form/create_category/', $data, '');	
		echo json_encode($result);
	}
	
	public function uploader_add_category()
	{
		
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CATEGORY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'BUSINESS_TYPE' => $n[2],
			'USER_ID' => $x
		);

		$result = $this->rest_app->post('index.php/category_form/uploader_create_category/', $data, '');	
		echo json_encode($result);
	}

	public function select_category()
	{
		$data = array(
			'CATEGORY_NAME' => $this->input->post('data')
			);

		$result = $this->rest_app->get('index.php/category_form/select_category/', $data, '');
		echo json_encode(	$result);
	}

	public function edit_category()
	{
		$n = json_decode($this->input->post('data'));
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CATEGORY_ID' => $n[0],
			'CATEGORY_NAME' => $n[2],
			'DESCRIPTION' => $n[1],
			'STATUS' => $n[3],
			'BUSINESS_TYPE' => $n[4],
			'USER_ID' => $x
			);

		$result = $this->rest_app->post('index.php/category_form/edit_category/', $data, '');	
		echo json_encode(	$result);
	}
	public function del_category()
	{
		$n = $this->input->post('data');
		$x = $this->session->userdata['user_id'];
		$data = array(
			'CATEGORY_ID' => $n
			);

		$result = $this->rest_app->post('index.php/category_form/del_category/', $data, '');	
		echo json_encode($result);
	}


	public function add_category_trade()
	{
		//echo $id;
		//die();
		//42 max
		//$n = json_decode($this->input->post('data'));
		//$x = $this->session->userdata['user_id'];
		$data = array(
			array('CATEGORY_NAME' => "Men's Accessories"),
			array('CATEGORY_NAME' => "Ladies' Accessories"),
			array('CATEGORY_NAME' => "Children's Accessories"),
			array('CATEGORY_NAME' => "Character Shop"),
			array('CATEGORY_NAME' => "Branded Accessories"),
			array('CATEGORY_NAME' => "Children's Wear - Girls"),
			array('CATEGORY_NAME' => "Infants' Wear"),
			array('CATEGORY_NAME' => "Children's Wear - Boys"),
			array('CATEGORY_NAME' => "Ladies' Wear 1"),
			array('CATEGORY_NAME' => "Girls' Teens Wear"),
			array('CATEGORY_NAME' => "Ladies' Wear 2"),
			array('CATEGORY_NAME' => "Ladies' Wear 3"),
			array('CATEGORY_NAME' => "Men's Shoes"),
			array('CATEGORY_NAME' => "Ladies' Shoes"),
			array('CATEGORY_NAME' => "Children's Shoes"),
			array('CATEGORY_NAME' => "Bags"),
			array('CATEGORY_NAME' => "Luggage"),
			array('CATEGORY_NAME' => "Smart Buy"),
			array('CATEGORY_NAME' => "Snack Exchange"),
			array('CATEGORY_NAME' => "Men's Wear"),
			array('CATEGORY_NAME' => "Boys' Teens Wear"),
			array('CATEGORY_NAME' => "Men's Wear 2"),
			array('CATEGORY_NAME' => "Ace Hardware"),
			array('CATEGORY_NAME' => "Our Home"),
			array('CATEGORY_NAME' => "Crate and Barrel"),
			array('CATEGORY_NAME' => "Storage"),
			array('CATEGORY_NAME' => "Furniture"),
			array('CATEGORY_NAME' => "Kitchenware"),
			array('CATEGORY_NAME' => "Tableware"),
			array('CATEGORY_NAME' => "Linen"),
			array('CATEGORY_NAME' => "Décor"),
			array('CATEGORY_NAME' => "Pet Express"),
			array('CATEGORY_NAME' => "Toys"),
			array('CATEGORY_NAME' => "Kultura"),
			array('CATEGORY_NAME' => "Infants' Accs"),
			array('CATEGORY_NAME' => "Signature Lines"),
			array('CATEGORY_NAME' => "Sports Central"),
			array('CATEGORY_NAME' => "Star Appliance"),
			array('CATEGORY_NAME' => "Gadgets"),
			array('CATEGORY_NAME' => "Supplies Station"),
			array('CATEGORY_NAME' => "Surplus"),
			array('CATEGORY_NAME' => "Watsons")
		);
		
		$result = array();

		
		/*= array(
			'CATEGORY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'BUSINESS_TYPE' => $n[2],
			'USER_ID' => $x
		);*/
		foreach($data as $dd){
			
			$dd['DESCRIPITON'] = null;
			$dd['BUSINESS_TYPE'] = 1;
			$dd['USER_ID'] = 3113;
			$rs = $this->rest_app->post('index.php/category_form/create_category/', $dd, '');	
			
			$result[] = $rs;
		}
		
		echo "<pre>";
		print_r($result);
	}

	public function add_category_nontrade()
	{
		//42 max
		//$n = json_decode($this->input->post('data'));
		//$x = $this->session->userdata['user_id'];

		$data = array(
			array('CATEGORY_NAME' => "ACCESSORIES (AMC)"),
			array('CATEGORY_NAME' => "ACE HARDWARE"),
			array('CATEGORY_NAME' => "ADMIN"),
			array('CATEGORY_NAME' => "BCO/TREASURY"),
			array('CATEGORY_NAME' => "BEST SELECTION RETAIL"),
			array('CATEGORY_NAME' => "CASAMIA"),
			array('CATEGORY_NAME' => "CHILDREN'S FASHION (CFMC)"),
			array('CATEGORY_NAME' => "CRO / SECURITY"),
			array('CATEGORY_NAME' => "EDD"),
			array('CATEGORY_NAME' => "FAST RETAILING"),
			array('CATEGORY_NAME' => "HMS DEV"),
			array('CATEGORY_NAME' => "HO ACCTG"),
			array('CATEGORY_NAME' => "HOMEWORLD"),
			array('CATEGORY_NAME' => "HR"),
			array('CATEGORY_NAME' => "INTERNATIONAL TOYWORLD"),
			array('CATEGORY_NAME' => "KULTURA"),
			array('CATEGORY_NAME' => "LADIES' FASHION (LFMC)"),
			array('CATEGORY_NAME' => "MCG (MARKETING)"),
			array('CATEGORY_NAME' => "MCG (PUBLICITY)"),
			array('CATEGORY_NAME' => "MCLG"),
			array('CATEGORY_NAME' => "MEDICAL"),
			array('CATEGORY_NAME' => "MEN'S FASHION (MFMC)"),
			array('CATEGORY_NAME' => "MINISO / MINI DEPATO"),
			array('CATEGORY_NAME' => "MLC"),
			array('CATEGORY_NAME' => "PREMIERE SHOES DISTRIBUTION"),
			array('CATEGORY_NAME' => "PREMIUM FASHION"),
			array('CATEGORY_NAME' => "PREMIUM GLOBAL ESSENCE"),
			array('CATEGORY_NAME' => "SBU"),
			array('CATEGORY_NAME' => "SIGNATURE LINES"),
			array('CATEGORY_NAME' => "SPORTS CENTRAL"),
			array('CATEGORY_NAME' => "STAR APPLIANCE"),
			array('CATEGORY_NAME' => "SUPPLIES STATION"),
			array('CATEGORY_NAME' => "SURPLUS"),
			array('CATEGORY_NAME' => "WALK EZ"),
			array('CATEGORY_NAME' => "WAREHOUSE"),
			array('CATEGORY_NAME' => "SYSTEMS RETAFF"),
			array('CATEGORY_NAME' => "WATSONS HROD"),
			array('CATEGORY_NAME' => "WATSONS HR-Admin"),
			array('CATEGORY_NAME' => "WATSONS Finance"),
			array('CATEGORY_NAME' => "WATSONS IT"),
			array('CATEGORY_NAME' => "WATSONS Marketing"),
			array('CATEGORY_NAME' => "WATSONS Security"),
			array('CATEGORY_NAME' => "WATSONS Store Development"),
			array('CATEGORY_NAME' => "WATSONS Supply Chain"),
			array('CATEGORY_NAME' => "WATSONS Warehouse")
			
		);

		
		/*= array(
			'CATEGORY_NAME' => $n[0],
			'DESCRIPTION' => $n[1],
			'BUSINESS_TYPE' => $n[2],
			'USER_ID' => $x
		);*/
		$result = array();
		foreach($data as $dd){
			$dd['DESCRIPITON'] = null;
			$dd['BUSINESS_TYPE'] = 2;
			$dd['USER_ID'] = 3113;
			$rs = $this->rest_app->post('index.php/category_form/create_category/', $dd, '');	
			$result[] = $rs;
		}
		echo "<pre>";
		print_r($result);
	}
}