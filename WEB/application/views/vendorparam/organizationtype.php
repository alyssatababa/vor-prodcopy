<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/organizationtype.js"></script>

	
	<div id="container_orgtype" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Ownership</h4></div>
			<div class="col-sm-2">
				<a class="btn btn-primary btn_add_orgtype pull-right">Add New</a>
			</div>	
		</div>
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendorparam/organizationtype/'; ?>"></div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">


					<form id="frm_orgtype" class="form-horizontal" action="<?php //echo base_url().'index.php/vendorparam/organizationtypeget_all_orgtype'?>" method="post">
						
						<div class="form-group">
							<!--
							<span class="col-md-2"><span class="pull-right"><strong>Bus Division :</strong></span></span>
							<div class="col-md-2">	
								<select id="select_bus_division" class="form-control">
									<option value="1">TRADE</option>
									<option value="0">NON-TRADE</option>
								</select>
							</div>
							-->

		
							<div class="col-md-4">
								<input id="search_orgtype" type="text" class="form-control field-required" placeholder="Enter Name or Description..">
							</div>
							<div class="col-md-1">	
								<button id="btn_search_orgtype" class="btn btn-primary pull-left" onclick="return false;">Search</button>
							</div>
						</div>
						
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Ownership</h4></strong>								
								</div>
							</div>
							<!--
							<div class="panel-body">					
								<label><?php //echo count($result_data)." Record(s)";?></label>
									<div class = "table-responsive">-->
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th style="width:30px;">No.</th>
										<th style="width:220px;">Name</th>
										<th>Description</th>
										<th style="width:90px;">Bus Division</th>
										<th>Date Uploaded</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "orgtype_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>	
					</form>

				</div>
			</div>
			
		</div>

	</div>
		
	<div id="modal_orgtype" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<!-- <h4 class="modal-title">Edit Contracts And Agreements</h4> -->

						<span class="edit_orgtype" style="display:none">
							<h4 class="modal-title">Edit Ownership</h4>
						</span>

						<span class="add_orgtype" style="display:none">
							<h4 class="modal-title">Add Ownership</h4>
						</span>
					</div>
					<div class="modal-body">

						<input type="hidden" id="orgtype_id" value="" class="hidden">
						<input type="hidden" id="orgtype_type" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Name </span></label>
							<div class="col-md-7">	
								<input id="orgtype_name" type="text" class="form-control  field-required">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Description </span></label>
							<div class="col-md-7">	
								<textarea id="orgtype_description" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Bus Division </span></label>
							<div class="col-md-7">	
								<select id="orgtype_bus_division" class="form-control">
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
								<a id="btn_save_orgtype" class="btn btn-primary">Save</a>
								<button id="btn_close_orgtype" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>	

</html>