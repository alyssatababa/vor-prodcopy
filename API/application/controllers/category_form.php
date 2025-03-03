<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
class category_form extends REST_Controller {

	public function __construct() {
			parent::__construct();
			$this->load->model('vendor_form_model');
			$this->load->model('common_model');
		}

		public function create_category_post()
		{

			$data = array(
				'CATEGORY_NAME' => $this->post('CATEGORY_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'BUSINESS_TYPE' => $this->post('BUSINESS_TYPE'),
				'CREATED_BY' => $this->post('USER_ID')
				);

		
			// $res = $this->common_model->select_query('SMNTP_CATEGORY',array('CATEGORY_NAME' => $data['CATEGORY_NAME']),'CATEGORY_ID');
			// if(count($res) > 0){

			// 	$this->response('exist');
			// }
				
			$result = $this->vendor_form_model->create_new_category($data);

			$this->response([
			'data' => $result
			]);
		}
		
		public function uploader_create_category_batch_post()
		{
			$data = $this->post('csv_array');
			$user_id = $this->post('user_id');
			$result['csv_array'] = $data;
			$result['failed'] = 0;
			$result['success'] = 0;
			$result['duplicate'] = 0;
			foreach($data as $key => $value){
				$vendor_type;
				
				if(strtoupper(trim($value['VENDOR_TYPE'])) == 'NON-TRADE'){
					if(strtoupper(trim($value['CATEGORY'] ))== 'FIXED ASSETS AND SUPPLIES (FAS)'){
						$vendor_type = 2;
					}else{
						$vendor_type = 3;
					}
				}else{
					$vendor_type = 1;
				}
				
				$param = array(
					'CATEGORY_NAME' => strtoupper(trim($value['CATEGORY'])),
					'DESCRIPTION' =>  strtoupper(trim($value['CATEGORY'] )),
					'BUSINESS_TYPE' => $vendor_type,
					'CREATED_BY' => $user_id
				);

				$duplicate_res = $this->vendor_form_model->get_category(
					array(
						$param['CATEGORY_NAME'], 
						$param['BUSINESS_TYPE']
					)
				);
				
				if( ! empty($duplicate_res)){
					$result['csv_array'] [$key]['result'] = 'duplicate';
					$result['duplicate']++;
				}else{
					$cat_insert_res = $this->vendor_form_model->create_new_category($param);
					if($cat_insert_res){
						$result['success']++;
						$result['csv_array'] [$key]['result'] = 'success';
					}else{
						$result['csv_array'] [$key]['result'] = 'failed';
						$result['failed']++;
					}
				}			
			}
			$this->response($result);
		}
		
		public function uploader_create_category_post()
		{

			$data = array(
				'CATEGORY_NAME' => $this->post('CATEGORY_NAME'),
				'DESCRIPTION' => $this->post('DESCRIPTION'),
				'BUSINESS_TYPE' => $this->post('BUSINESS_TYPE'),
				'CREATED_BY' => $this->post('USER_ID')
			);

			$result = $this->vendor_form_model->get_category(array(strtoupper($data['CATEGORY_NAME']), $data['BUSINESS_TYPE']));
			
			if( ! empty($result)){
				$this->response([
					'data' => 'exist'
				]);
			}
			
			$result = $this->vendor_form_model->create_new_category($data);

			$this->response([
				'data' => $result
			]);
		}

		public function select_category_get()
		{

			$data = array(
				'LOWER(CATEGORY_NAME)' => strtolower($this->get('CATEGORY_NAME'))
				);

		$result = $this->vendor_form_model->select_all_category($data);

			$this->response([
			'data' => $result
			]);

		}

		public function edit_category_post()
		{

			$data = array(
				'CATEGORY_NAME' => $this->post('CATEGORY_NAME'),
				'STATUS' => $this->post('STATUS'),
				'BUSINESS_TYPE' => $this->post('BUSINESS_TYPE'),
				'DESCRIPTION' => $this->post('DESCRIPTION')
				);

			$data2 = array(
				'CATEGORY_ID' => $this->post('CATEGORY_ID')
				);
				
			$result = $this->vendor_form_model->edit_category($data,$data2);

			$this->response([
			'data' => $result
			]);
		}
		public function del_category_post()
		{


			$data = array(
				'CATEGORY_ID' => $this->post('CATEGORY_ID')
				);
				
			$result = $this->vendor_form_model->del_category($data);

			$this->response($result);
		}

	}
