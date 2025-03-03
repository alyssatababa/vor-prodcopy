<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/vendoridpass_amount.js"></script>

	<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendor/vendor_id_pass_amount/'; ?>"></div>
	<div id="container_vendor_id_pass_amount" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Vendor ID/Pass Amount</h4></div>
			<!-- <div class="col-sm-2">
				<a class="btn btn-primary btn_add_vendor_id_pass_amount pull-right">Add New</a>
			</div>	 -->
		</div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">


					<form id="frm_vendor_id_pass_amount" class="form-horizontal" action="<?php //echo base_url().'index.php/vendorparam/organizationtypeget_all_vendor_id_pass_amount'?>" method="post">
						
						<!-- <div class="form-group">		
							<div class="col-md-4">
								<input id="search_vendor_id_pass_amount" type="text" class="form-control field-required" placeholder="Enter Vendor ID/Pass Request Type">
							</div>
							<div class="col-md-1">	
								<button id="btn_search_vendor_id_pass_amount" class="btn btn-primary pull-left" onclick="return false;">Search</button>
							</div>
						</div> -->
						
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Vendor ID/Pass Amount</h4></strong>								
								</div>
							</div>
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th>Amount</th>
										<th>Description</th>
										<th>Date Created</th>
										<th>Effectivity Date</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "vendor_id_pass_amount_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>	
					</form>

				</div>
			</div>
			
		</div>

	</div>
		
	<div id="modal_vendor_id_pass_amount" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal frm-vendor">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

						<span class="edit_vendor_id_pass_amount" style="display:none">
							<h4 class="modal-title">Edit Vendor ID/Pass Amount</h4>
						</span>

						<span class="add_vendor_id_pass_amount" style="display:none">
							<h4 class="modal-title">Add Vendor ID/Pass Amount</h4>
						</span>
					</div>
					<div class="modal-body">						
						<input type="hidden" id="amount_id" value="" class="hidden">
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Amount </span></label>
							<div class="col-md-7">	
								<input id="amount" type="text" class="form-control  field-required">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Description </span></label>
							<div class="col-md-7">	
								<!-- <input id="vendor_id_pass_amount_tool_tip" type="text" class="form-control  field-required"> -->
								<textarea id="description" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Effectivity Date </span></label>
							<div class="col-md-7">	
								<!-- <input id="vendor_id_pass_amount_tool_tip" type="text" class="form-control  field-required"> -->
								<input id="effectivity_date" type="date" class="form-control  field-required">
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">

								<span class="add_vendor_id_pass_amount" style="display:none">
									<a id="btn_save_vendor_id_pass_amount" class="btn btn-primary">Save</a>
								</span>
								<span class="edit_vendor_id_pass_amount" style="display:none">
									<a id="btn_update_vendor_id_pass_amount" class="btn btn-primary">Update</a>
								</span>
								<button id="btn_close_vendor_id_pass_amount" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>	

</html>