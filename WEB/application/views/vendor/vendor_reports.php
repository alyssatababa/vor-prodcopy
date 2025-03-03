<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/report.js"></script>

<?=form_open_multipart('form1', array('name' => 'form1', 'id' => 'frm_shortlist') );?>
<style>
.select2-container{width:auto !important;}
</style>
<div id="container_role" class="container mycontainer">
		
	<div id="b_url" data-base-url="<?php echo base_url(); ?>"></div>

	<div class="row">
		<div class="col-md-10"><h4>Reports</h4></div>
		<div class="col-sm-2">
			<button id="btn_download" class="btn btn-primary btn_add_reqdocs pull-right" disabled">Download</button>
		</div>	
	</div>

	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">


				<div class="row">
					<div class="col-md-7">
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-12"><h4>Reports</h4></div>								
								</div>
							</div>
							<div class="panel-body">

								<div class="form-group">
								<div>
									<div class="col-md-9">
							<!-- 			<select id="select_orgtype" class="form-control" >
											<option value="1">POSITION 1</option>
											<option value="0">POSITION 2</option>
										</select>
										<br /> -->
										<select id="select_report" class="form-control" onChange="search_filter();">
											<option value="0">SELECT REPORT</option>
											<option value="1">EXPIRED INVITES</option>
											<option value="2">DEACTIVATED ACCOUNTS</option>
											<!-- Added MSF - 20191126 (IJR-10619) -->
											<option value="3">COMPLETED REGISTRATION</option>
											<option value="4">FAILED TO COMPLY WITH VALIDATION SCHEDULE</option>
											<!-- Added JRM - June 25, 2021 -->
											<option value="5">LIST OF DELETED VENDOR INVITES</option>
											<option value="6">PENDING INVITES</option>
											<option value="7">CONTACTS PER VENDOR</option>
											<!-- MSF 2022-12-22 -->
											<option value="8">ACTIVE AND INACTIVE USERS</option>
											<option value="9">CONTACT PERSON PER SM VENDOR SYSTEM</option>
										</select>
									</div>
									<div class="col-sm-2">	
										<!-- <button id="btn_search" class="btn btn-primary" onclick="return false;">   Search   </button> -->
									</div>
								</div>
								</div>
							</div>
						</div>	
					</div>
				</div>

				<div class="row">
					<div class="col-md-7">
						<div id="div_filter" class="panel panel-primary" style="display: none;">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-12"><h4>Filter By</h4></div>								
								</div>
							</div>
							<div class="panel-body">
								<div class="form-group">
									<div class="row">
										<div class="col-md-5">
											<span><h4 id="report_filter"></h4></span>
										</div>
										<div class="col-md-12" id="delFilter">
											<select class="form-control" id="delType">
												<option value="vn"> Vendor name </option>
												<option value="dbu"> Deleted by (Usernames)</option>
											</select>
										</div>
										
										<div id="reportEightFilter">
											<div class="row"></div>
											<div class="col-md-12">
												<div class="alert alert-info" id="userError" style="display:none"></div>
											</div>

											<div class="col-md-4">
												<label id="labelUserType">User Type:</label>
											</div>

											<div class="col-md-8">
												<select id="userType" onChange="generate_users();">
													<option value="0">All</option>
													<option value="1">Vendor</option>
													<option value="2">SM</option>
												</select>
											</div>
											
											<div class="row"></div>

											<div class="col-md-4">
												<label id="labelUserStatus">User Status:</label>
											</div>
											<div class="col-md-8">
												<select id="userStatus" onChange="generate_users();">
													<option value="0">All</option>
													<option value="1">Active</option>
													<option value="2">In-Active</option>
												</select>
											</div>
										</div>

										<div id="complete_date_filter"> 
											<div class="row"></div>
											<div class="col-md-12">
												<div style="display:none"></div>
											</div>
											<div class="col-md-3">
												<label>Completed Date</label>
											</div>
											<div class="col-md-3">	
												<span>Date From</span>			
												<input type="date" name="date_created" id="c_date_created_from" class="form-control field-required" style="width: 140px" max="9999-12-31">
											</div>
											<div class="col-md-3">	
												<span>Date To</span>				
												<input type="date" name="date_created" id="c_date_created_to" class="form-control field-required" style="width: 140px" max="9999-12-31">
											</div>
										</div>
										
										<div id="pendingFilter">
											<div class="row"></div>
											<div class="col-md-12">
												<div class="alert alert-info" id="userError" style="display:none"></div>
											</div>
											<div class="col-md-4">
												<label id="selectLabel"></label>
											</div>
											<div class="col-md-8" style="display:flex;">
												<select id="userFilter"></select>
												<input type="hidden" name="postIDs" id="postIDs">
												<button type="button" id="addUser" class="btn btn-primary">ADD</button>
											</div>
											
											<div class="row"></div>
											
											<div class="col-md-12">
												<div id="user-con">
													<label>List of Usernames</label>
													<ol id="lou">
														
													</ol>
												</div>
												<div id="vendor-con">
													<label>List of Vendors</label>
												<ol id="lov">
													
												</ol>
												</div>
											</div>
										</div>
										<div class="col-md-7" id="dateFilter"> 
											<div class="col-md-6">	
												<span>Date From</span>			
												<input type="date" name="date_created" id="date_created_from" class="form-control field-required" style="width: 140px" max="9999-12-31">
											</div>
											<div class="col-md-6">	
												<span>Date To</span>				
												<input type="date" name="date_created" id="date_created_to" class="form-control field-required" style="width: 140px" max="9999-12-31">
											</div>
										</div>
									</div>
									<br>
									<div class="row hidden">
										<div class="col-md-5">
											<span><h4>Filter Category/Dept:</h4></span>
										</div>
										<div class="col-md-7">
											<div class="col-md-12">
												<select id="cat_filter" class="form-control">
													<option value="0">NO FILTER</option>
													<?php 
														$n = 1;
														if (!empty($category_list)){
															foreach ($category_list as $row){
																	echo '<option value="'.$row->CATEGORY_ID.'">'.$row->CATEGORY_NAME.'</option>';
																}
														}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
			
					</div>
					
				</div>
			</div>	
		</div>

	</div>
</div>
<?=form_close();?>
	
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