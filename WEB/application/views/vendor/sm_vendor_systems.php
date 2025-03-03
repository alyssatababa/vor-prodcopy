<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/smvendorsystems.js"></script>

	<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendor/sm_vendor_systems/'; ?>"></div>
	<div id="container_smvs" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>SM Vendor System</h4></div>
			<div class="col-sm-2">
				<a class="btn btn-primary btn_add_smvs pull-right">Add New</a>
			</div>	
		</div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">


					<form id="frm_smvs" class="form-horizontal" action="<?php //echo base_url().'index.php/vendorparam/organizationtypeget_all_smvs'?>" method="post">
						
						<div class="form-group">		
							<div class="col-md-4">
								<input id="search_smvs" type="text" class="form-control field-required" placeholder="Enter SM Vendor System">
							</div>
							<div class="col-md-1">	
								<button id="btn_search_smvs" class="btn btn-primary pull-left" onclick="return false;">Search</button>
							</div>
						</div>
						
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>SM Vendor System</h4></strong>								
								</div>
							</div>
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th style="width:30px;">No.</th>
										<th style="width:220px;">SM Vendor System</th>
										<!-- <th>Department</th> -->
										<th>Description</th>
										<th>Trade Vendor Type</th>
										<th>Date Created</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "smvs_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>	
					</form>

				</div>
			</div>
			
		</div>

	</div>
		
	<div id="modal_smvs" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal frm-vendor">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

						<span class="edit_smvs" style="display:none">
							<h4 class="modal-title">Edit SM Vendor System</h4>
						</span>

						<span class="add_smvs" style="display:none">
							<h4 class="modal-title">Add SM Vendor System</h4>
						</span>
					</div>
					<div class="modal-body">						
						<input type="hidden" id="smvs_id" value="" class="hidden">
						<!-- <div class="form-group">
							<label class="col-md-3"><span class="pull-right">Department </span></label>
							<div class="col-md-7">	
								<select id="department" class="form-control">
									<?php
										// For All Departments
										echo "<option value='0'>-- ALL --</option>";
										$counter = count($result_data);
										for($i=0; $i<$counter; $i++){
											echo "<option value='".$result_data[$i]->CATEGORY_ID."'>".$result_data[$i]->CATEGORY_NAME."</option>";
										}
									?>
								</select>
							</div>
						</div> -->
						<input type="hidden" value="0" name="department" id="department"></input>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Name </span></label>
							<div class="col-md-7">	
								<input id="smvs_name" type="text" class="form-control  field-required">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Description </span></label>
							<div class="col-md-7">	
								<!-- <input id="smvs_tool_tip" type="text" class="form-control  field-required"> -->
								<textarea id="smvs_tool_tip" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Trade Vendor Type </span></label>
							<div class="col-md-7">	
								<select id="trade_vendor_type" class="form-control field-required">
									<?php
										// For All Departments
										echo "<option value=''></option>";
										echo "<option value='1'>Outright</option>";
										echo "<option value='2'>Store Consignor</option>";
									?>
								</select>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">

								<span class="add_smvs" style="display:none">
									<a id="btn_save_smvs" class="btn btn-primary">Save</a>
								</span>
								<span class="edit_smvs" style="display:none">
									<a id="btn_update_smvs" class="btn btn-primary">Update</a>
								</span>
								<button id="btn_close_smvs" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>	

</html>