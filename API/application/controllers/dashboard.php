<?Php
defined('BASEPATH') OR exit('No direct script access allowed');

	// Including Phil Sturgeon's Rest Server Library in our Server file.
	require APPPATH . '/libraries/REST_Controller.php';
	class Dashboard extends REST_Controller {

		// Load model in constructor
		public function __construct() {
			//echo 'test';
			parent::__construct();
			$this->load->model('dashboard_model');
		}

		// Server's Get Method
		public function header_get(){
			$id = $this->get('user_position_id');
			$vendor_type_id = $this->get('vendor_type_id');
			$data = $this->dashboard_model->read($id, $vendor_type_id);
				// var_dump($data);
			if ($data)
			{
				// var_dump($data);
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}
		}


		// Server's Alternative Get Method, you can have as many Get Methods as you want, just remember to put a _get suffix
		public function menu_by_name_get(){
			$menu_name = $this->get('menu_name');
			$data = $this->dashboard_model->read_like_name($menu_name);
			// var_dump($data);
			if ($data)
			{
				$this->response($data);
			}
			else
			{
				$this->response([
					'status' => FALSE,
					'error' => 'Record could not be found'
					], 404);
			}
		}

		// Server's Put Method
		public function menu_put(){
			$menu_id = $this->put('menu_id');
			$menu_name = $this->put('menu_name');
			$description = $this->put('description');
			$sorting = $this->put('sorting');
			$menu_link = $this->put('menu_link');

			// check if name already exists.
			$data = $this->dashboard_model->read_by_name($menu_name);

			// if both queried data and menu id is not null and ids are not equal throw an error
			if ($menu_id && ($menu_name!=null && $menu_name!='')) {
				if ($data && isset($data->menu_id)) {
					if ($data->menu_id == $menu_id) {
						// do insert
						$this->dashboard_model->insert_menu($menu_name,$description,$sorting,$menu_link);
						$data = $this->dashboard_model->read_by_name($menu_name);

						if ($data) {
							$this->response($data);
						} else {
							$this->response([
								'status' => FALSE,
								'error' => 'Error! Insert Failed.'
								], 400);
						}
					} else {
						$this->response([
							'status' => FALSE,
							'error' => 'new menu_name already exists'
							], 400);
					}
				} else {
					$this->response([
						'status' => FALSE,
						'error' => 'unexpected error, menu_id was not found in data'
						], 400);
				}
			} else if ($data && ($menu_name!=null && $menu_name!='')) {
				$this->response([
							'status' => FALSE,
							'error' => 'menu_name already exists'
							], 400);
			} else if ($menu_name!=null && $menu_name!='') {
				// do insert
				$this->dashboard_model->insert_menu($menu_name,$description,$sorting,$menu_link);
				$data = $this->dashboard_model->read_by_name($menu_name);
				if ($data) {
					$this->response($data);
				} else {
					$this->response([
						'status' => FALSE,
						'error' => 'Error! Insert Failed.'
						], 400);
				}
			} else {
				$this->response([
					'status' => FALSE,
					'error' => 'Error! Please check your parameters'
					], 400);
			}
		}

		public function menu_delete(){
			$menu_id = $this->delete('menu_id');
			$data = $this->dashboard_model->read($menu_id);
			if ($menu_id!=null && $menu_id > 0 && $data) {
				$this->dashboard_model->delete_menu($menu_id);
				array_push($data, array('message'=>'record deleted!'));
				$this->response($data);
			} else {
				$this->response([
					'status' => FALSE,
					'error' => 'Error! Please check your parameters'
					], 400);
			}
		}
	}

?>
