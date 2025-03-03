<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/orgtype_reqdocs_assignments.js"></script>

	
	<div id="container_role" class="container mycontainer">
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendorparam/orgtype_reqdocs_assignments/'; ?>"></div>

		<div class="row">
			<div class="col-md-10"><h4>Ownership Primary Documents Assignment</h4></div>
		</div>
		
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">


					<form  id="frm_role" class="form-horizontal">
						<form  class="form-group">
							<div class="col-md-5">
								<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-md-12"><h4>Ownership</h4></div>								
										</div>
									</div>
									<div class="panel-body">

										<div class="form-group">
										<div>
											<div class="col-md-9">
												<select id="select_orgtype" class="form-control" >
							<!-- 						<option value="1">POSITION 1</option>
													<option value="0">POSITION 2</option> -->
												</select>
												<br />
												<select id="select_vendortype" class="form-control">
													<option value="0">SELECT VENDOR TYPE</option>
													<option value="1">TRADE</option>
													<option value="2">NON-TRADE</option>
													<option value="3">NON-TRADE SERVICE</option>
												</select>
												<br />
												<select id="select_tradevendortype" class="form-control" >
													<option value="0">SELECT TRADE VENDOR TYPE</option>
													<option value="1">OUTRIGHT</option>
													<option value="2">CONSIGNOR</option>
												</select>
											</div>
											<div class="col-sm-2">	
												<button id="btn_search_org" class="btn btn-primary" onclick="return false;">   Search   </button>
											</div>
										</div>
										</div>
									</div>
								</div>	
							</div>


							<div class="col-md-7">
								<div id="orgtype_docs" class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<div class="col-md-10"><h4>Select Primary Documents</h4></div>
											<div class="col-md-2">
												<span class="pull-right">		
													<button id="btn_save_defn" class="btn btn-default" onclick="return false;">Save</button>	
												</span>
											</div>
										</div>

									</div>

									<table id="tbl_view" class="table table-hover">
										<thead>
											<tr>
												<th>Select</th>
												<th>Name</th>
											</tr>	

										</thead>
										<tbody id="tbl_body_orgtype_reqdocs">

										</tbody>
									</table>
								</div>
	<!-- 							<div class="panel panel-primary">
									<div class="panel-heading">
										<div class="row">
											<strong class="col-md-10">Screens</strong>								
										</div>
									</div> -->
									<!--
									<div class="panel-body">					
										<label><?php //echo count($result_data)." Record(s)";?></label>
											<div class = "table-responsive">-->

<!-- 										<table id="tbl_view" class="table table-hover">
											<thead>
												<tr>
													<th style="width:80px;"><input type="checkbox" data-data="checkbox" id="checkAll" /></th>
													<th>Screen</th>
												</tr>	

											</thead>
											<tbody id="tbl_body_orgtype_reqdocs">
												<tr>
													<td>Select</td>
													<td>Screen</td>
												</tr>	
												<tr>
													<td>Select</td>
													<td>Screen</td>
												</tr>	
												<tr>
													<td>Select</td>
													<td>Screen</td>
												</tr>	
											</tbody>
										</table>
								
								</div> -->	
							</div>
						</form>
					</form>

				</div>
			</div>
			
		</div>

	</div>
	
<!--ADD MODAL-->
	<div id="modal_add_role" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add New Role Management</h4>
					</div>

				
					<div class="modal-body">
						
						<div id = "error_add_role" class="alert alert-danger alert-dismissable" style="display:none">				
								<strong>Danger!</strong> 
						</div>
							
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Name </span></label>
							<div class="col-md-7">	
								<input id="input_role_name" type="text" class="form-control field-required">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Description </span></label>
							<div class="col-md-7">	
								<textarea id="input_description" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Bus Division </span></label>
							<div class="col-md-7">	
								<select id="select_bus_division" class="form-control">
									<option value="1">TRADE</option>
									<option value="0">NON-TRADE</option>
								</select>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_add_role" class="btn btn-primary" onclick="return false;">Save</button>
								<button class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
			
		</div>
	</div>
<!--END OF MODAL-->

<!--EDIT MODAL-->
	<div id="modal_edit_role" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Role Management</h4>
					</div>
					<div class="modal-body">
					
						<div id = "error_edit_role" class="alert alert-danger alert-dismissable" style="display:none">				
								<strong>Danger!</strong> 
						</div>					
					
						<input type="hidden" id="edit_role_id" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Name </span></label>
							<div class="col-md-7">	
								<input id="edit_role_name" type="text" class="form-control  field-required">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Description </span></label>
							<div class="col-md-7">	
								<textarea id="edit_description" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Bus Division </span></label>
							<div class="col-md-7">	
								<select id="edit_bus_division" class="form-control">
									<option value="1">TRADE</option>
									<option value="0">NON-TRADE</option>
								</select>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_save_role" class="btn btn-primary" onclick="return false;">Save</button>
								<button class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>
<!--END OF MODAL-->	
		
	

</html>