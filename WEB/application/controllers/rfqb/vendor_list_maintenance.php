<?php
Class Vendor_List_Maintenance extends CI_Controller{

	function index()
	{
		$data['user_id'] = $this->session->userdata('user_id');

		$vendorlist_array		= $this->rest_app->get('index.php/rfq_api/vendor_list/', $data, 'application/json');
		//$this->rest_app->debug();
		$table = '<div class="panel panel-primary" id="table_vendor_list" style=" height: 400px; overflow-y: scroll; overflow-x: hidden;">
					<div class="row">
						<div class="col-md-12">
							<table class="table">
								<th class="label-primary" style="color: #ececec"><center>Name</center></th>
								<th class="label-primary" style="color: #ececec"><center>Created By</center></th>
								<th class="label-primary" style="color: #ececec"><center>Date Created</center></th>
								<th class="label-primary">&nbsp;</th>';

		$x = 0;
		if(sizeOf($vendorlist_array) > 0)
		{
			foreach($vendorlist_array as $row)
			{
				$x++;
				$table .= '<tr>
								<td align="center"><a href="#" onclick="edit_vendor_list('.$row->VENDOR_LIST_ID.')">'.$row->VENDOR_LIST_NAME.'</a></td>
								<td align="center">'.$row->USER_FIRST_NAME.' '.$row->USER_LAST_NAME.'</td>
								<td align="center">'.$row->DATE_CREATED.'</td>
								<td align="center"><span class="glyphicon glyphicon-trash btn btn-default" onclick="validate_delete_vendor_list('.$row->VENDOR_LIST_ID.', \''.$row->VENDOR_LIST_NAME.'\')"></td>
						   </tr>';
			}
		}

		
		$table .= '			</table>	
						</div>
					</div>
				</div>';

		$data['table'] = $table;

		$this->load->view('rfqb/vendor_list_management', $data);
	}

	function search_vendor_list()
	{
		$data['user_id']	= $this->session->userdata('user_id');
		$data['list_name'] 	= $this->input->post('list_name');
		//print_r($_POST);
		$vendorlist_array = $this->rest_app->get('index.php/rfq_api/search_vendor_list', $data, 'application/json');
		//$this->rest_app->debug();
		$table = '
					<div class="row">
						<div class="col-md-12">
							<table class="table">
								<th class="label-primary" style="color: #ececec"><center>Name</center></th>
								<th class="label-primary" style="color: #ececec"><center>Created By</center></th>
								<th class="label-primary" style="color: #ececec"><center>Date Created</center></th>
								<th class="label-primary">&nbsp;</th>';

		if(sizeOf($vendorlist_array) > 0)
		{
			foreach($vendorlist_array as $row)
			{
				$table .= '<tr>
								<td align="center">'.$row->VENDOR_LIST_NAME.'</td>
								<td align="center">'.$row->USER_FIRST_NAME.' '.$row->USER_LAST_NAME.'</td>
								<td align="center">'.$row->DATE_CREATED.'</td>
								<td align="center"><button class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></td>
						   </tr>';
			}
		}

		
		$table .= '			</table>	
						</div>
					</div>';


		echo $table;
	}

	function view_vendor_list()
	{
		$data['user_id']	= $this->session->userdata('user_id');
		$data['list_id'] = $this->input->post('value');
		$data['vendor_list_name'] = '';

		$vendorlist_array = $this->rest_app->get('index.php/rfq_api/search_vendor_list_participants', $data, 'application/json');

		$table = '
					<div class="panel panel-primary" style="width: 500px; height: 300px; overflow-y: scroll; overflow-x: hidden;">
					<div class="row">
						<div class="col-md-10">
							<table class="table" style="width: 500px;">
								<th class="label-primary" style="color: #ececec"><center>Vendors</th>
								<th class="label-primary" style="color: #ececec">&nbsp;</th>';
		
		$x = 0;
		if(sizeOf($vendorlist_array) > 0)
		{
			
			foreach($vendorlist_array as $row)
			{
				$x++;
				if($x == 1)
					$data['vendor_list_name'] = $row->VENDOR_LIST_NAME;

				$table .= '<tr style="padding-left: 10px;overflow-y: scroll;">
								<td align="center">
								<input type="hidden" name="invite_id'.$x.'" id="invite_id'.$x.'" value='.$row->VENDOR_INVITE_ID.'>
								<input type="hidden" name="invite_name'.$x.'" id="invite_name'.$x.'" value='.$row->VENDOR_NAME.'>
								'.$row->VENDOR_NAME.'
								</td>
								<td align="center"><span class="glyphicon glyphicon-trash btn btn-default" onclick="remove_vendor_invite('.$x.')"></span></td>
						   </tr>';
			}
		}

		
		$table .= '			</table>	
						</div>
					</div>';

		$table .= '<input type="hidden" name="total_vendor_count" id="total_vendor_count" value='.$x.'>';
		$table .= '<input type="hidden" name="total_left_count" id="total_left_count" value='.$x.'>';
		$table .= '<input type="hidden" name="vendor_list_id" id="vendor_list_id" value='.$data['list_id'].'>';

		$data['table'] = $table; 
		$data['header'] = 'View Vendor List'; 

		$this->load->view('rfqb/rfq_new_vendor_list', $data);
	}

	function remove_vendor_invite()
	{
		$row_value = $this->input->post('row');

		$data['user_id']	= $this->session->userdata('user_id');
		$data['list_id'] = $this->input->post('vendor_list_id');
		$data['total_vendor_count'] = $this->input->post('total_vendor_count');
		$data['total_left_count'] = $this->input->post('total_left_count');
		
		$vendorlist_array = $this->rest_app->get('index.php/rfq_api/search_vendor_list_participants', $data, 'application/json');

		$table = '
					<div class="panel panel-primary" style="width: 500px; height: 300px; overflow-y: scroll; overflow-x: hidden;">
					<div class="row">
						<div class="col-md-10">
							<table class="table" style="width: 500px;">
								<th class="label-primary" style="color: #ececec"><center>Vendors</th>
								<th class="label-primary" style="color: #ececec">&nbsp;</th>';

		$x = 0;
		if(sizeOf($vendorlist_array) > 0)
		{
			foreach($vendorlist_array as $row)
			{
				$data['vendor_list_name'] = $row->VENDOR_LIST_NAME;

				if($this->input->post('invite_id'.$row_value) != $row->VENDOR_INVITE_ID)
				{
					$x++;
					$table .= '<tr style="padding-left: 10px;overflow-y: scroll;">
									<td align="center">
									<input type="hidden" name="invite_id'.$x.'" id="invite_id'.$x.'" value='.$row->VENDOR_INVITE_ID.'>
									<input type="hidden" name="invite_name'.$x.'" id="invite_name'.$x.'" value='.$row->VENDOR_NAME.'>
									'.$row->VENDOR_NAME.'
									</td>
									<td align="center"><span class="glyphicon glyphicon-trash btn btn-default" onclick="remove_vendor_invite('.$x.')"></span></td>
							   </tr>';
				}

			}
		}

		$table .= '			</table>	
						</div>
					</div>';

		$table .= '<input type="hidden" name="total_vendor_count" id="total_vendor_count" value='.$data['total_vendor_count'].'>';
		$table .= '<input type="hidden" name="total_left_count" id="total_left_count" value='.$x.'>';
		$table .= '<input type="hidden" name="vendor_list_id" id="vendor_list_id" value='.$data['list_id'].'>';

		echo $table; 
	}

	function update_record()
	{
		$data = $_POST;
		$data['user_id']	= $this->session->userdata('user_id');

		$rs = $this->rest_app->post('index.php/rfq_api/update_vendor_list/', $data, NULL);
		$this->rest_app->debug();
	}

	function delete_record()
	{
		$data = $_POST;
		$data['user_id']	= $this->session->userdata('user_id');

		$rs = $this->rest_app->post('index.php/rfq_api/delete_vendor_list/', $data, NULL);
		$this->rest_app->debug();
	}

	function search_vendor_add_list()
	{
		$table = '<div class="panel panel-primary" style="width: 830px; height: 300px;">
					<div class="row">

						<div class="col-md-6" id="search_result_table">
							<table class="table" style="width: 100%; height: 30px">
								<th class="label-primary" style="color: #ececec; vertical-align: middle; width: 20%">Search: </th>
								<th class="label-primary" style="width: 60%"><input type="text" name="name_vendor" id="name_vendor" value="" class="form-control"></th>
								<th class="label-primary" style="width: 20%"><input type="button" value="Search" class="btn btn-default btn-sm" onclick="vendor_search()"></th>';


		$table .= '			</table>	
						</div>';

		$table .= '	<div class="col-md-1">
						<br><br><br>
						<button type="button" class="btn btn-default btn-lg" onclick="forward_selected()"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
					</div>';
		$table .= '		
						<div class="col-md-5" id="forwarded_result_table" style="">
							<input type="hidden" id="vendor_list_total" name="vendor_list_total" value="0">
							<table class="table" style="width: 100%;">
								<th class="label-primary" style="color: #ececec; vertical-align: middle; height: 50px;">Selected </th>';


		$table .= '			</table>	
						</div>			

					</div>
				</div>';


		$data['vendor_list_name'] = '';
		$data['table'] = $table;
		$data['header'] = 'Addd New Vendor List'; 

		$this->load->view('rfqb/rfq_new_vendor_list', $data);
	}

	function search_vendor()
	{
		$data['search_value'] = $this->input->post('name_vendor');
		$data['user_id'] = $this->session->userdata['user_id'];

		$vendor_array = $this->rest_app->get('index.php/rfq_api/search_vendor', $data, 'application/json');

		$table = '<div id="scroll" style="overflow-y: scroll; max-height: 290px;"><table class="table" style="width: 100%; max-height: 300px;">
					<th class="label-primary" style="color: #ececec; vertical-align: middle; width: 20%">Search: </th>
					<th class="label-primary" style="width: 60%"><input type="text" name="name_vendor" id="name_vendor" value="'.$data['search_value'].'" class="form-control"></th>
					<th class="label-primary" style="width: 20%"><input type="button" value="Search" class="btn btn-default btn-sm" onclick="vendor_search()"></th>
					<tbody>';
					$n = 0;
		if(sizeOf($vendor_array) > 0)
		{
			foreach($vendor_array as $row)
			{
				$n++;
				$table .= '<tr>';
				$table .= ' <td><input type="checkbox" onchange="invitecheck(this.checked, '.$n.', \'checkbox_invite_vendor\')"></td>
							<td width="100%" colspan="2">
							<input type="hidden" name="rs_invite_id'.$n.'" id="rs_invite_id'.$n.'" value="'.$row->VENDOR_INVITE_ID.'">
							<input type="hidden" name="rs_vendor_id'.$n.'" id="rs_vendor_id'.$n.'" value="'.$row->VENDOR_ID.'">
							<input type="hidden" name="rs_vendor_name'.$n.'" id="rs_vendor_name'.$n.'" value="'.$row->VENDOR_NAME.'">
							<input type="hidden" name="checkbox_invite_vendor'.$n.'" id="checkbox_invite_vendor'.$n.'" value="0">
							'.$row->VENDOR_NAME.'
						   </td>';
				$table .= '</tr>';
			}
		}

		$table .= '
					</tbody>
				</table>
				<input type="hidden" name="total_rows" id="total_rows" value="'.$n.'">
				</div>';

		echo $table;
	}

	function forward_selected()
	{
		$vendor_list_total = $this->input->post('vendor_list_total');
		$total_checked = $this->input->post('total_checked');
		$count = $vendor_list_total;
		$table ='
							<table class="table" style="width: 100%;">
								<th class="label-primary" style="color: #ececec; vertical-align: middle; height: 50px;">Selected </th>';

		$vendor_id = array();
		$n = 0;
		for($j=1; $j <= $vendor_list_total; $j++)
		{
			
			$table .= '<tr style="padding-left: 10px;overflow-y: scroll;">
	        				<td>
		            			<input type="hidden" id="vendorinvitefinal_id'.$j.'" name="vendorinvitefinal_id'.$j.'" value="'.$this->input->post('vendorinvitefinal_id'.$j).'">
		            			<input type="hidden" id="vendorfinal_invite_id'.$j.'" name="vendorfinal_invite_id'.$j.'" value="'.$this->input->post('vendorfinal_invite_id'.$j).'">
		            			<input type="hidden" id="vendorname_finalinvited'.$j.'" name="vendorname_finalinvited'.$j.'" value="'.$this->input->post('vendorname_finalinvited'.$j).'">
		            		  	<center>'.$this->input->post('vendorname_finalinvited'.$j).'</center>
	        		  		</td>
	        		  	</tr>';   
			
			array_push($vendor_id, $this->input->post('vendorinvitefinal_id'.$j));

		}

		$rownum = $j;

		for($x= 1; $x <= $total_checked; $x++)
		{
			$not_valid = 0;
			if (in_array($this->input->post('rs_vendor_id'.$x), $vendor_id))
				$not_valid = 1;

			if ($not_valid == 0)
			{
				$table .= '
						<tr>
							<td>
								<input type="hidden" id="vendorinvitefinal_id'.$rownum.'" name="vendorinvitefinal_id'.$rownum.'" value="'.$this->input->post('rs_vendor_id'.$x).'">
			            			<input type="hidden" id="vendorfinal_invite_id'.$rownum.'" name="vendorfinal_invite_id'.$rownum.'" value="'.$this->input->post('rs_invite_id'.$x).'">
			            			<input type="hidden" id="vendorname_finalinvited'.$rownum.'" name="vendorname_finalinvited'.$rownum.'" value="'.$this->input->post('rs_vendor_name'.$x).'">
			            		  	<center>'.$this->input->post('rs_vendor_name'.$x).'</center>
							</td>
						</tr>';
				$count++;
				$rownum++;
			}

		}

		$table .= '				</table>	
							<input type="hidden" id="vendor_list_total" name="vendor_list_total" value="'.$count.'">
						</div>';
		echo $table;

	}

}