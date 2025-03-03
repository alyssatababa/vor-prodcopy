

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/incomplete_documents_reasons.js"></script>

<div id="container_incdocreasons" class="container mycontainer">

	<div class="row">
		<div class="col-md-10"><h4>Incomplete Documents Reasons</h4></div>
		<div class="col-sm-2">
			<button class="btn btn-primary btn_add_incdocreasons pull-right">Add New</button>
		</div>	
	</div>
	
	<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendorparam/incomplete_documents_reasons/'; ?>"></div>

	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">

				<form id="frm_incdocreasons2" name="frm_incdocreasons2" method="post" class="form-horizontal">
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
							<input id="search_incdocreasons" type="text" class="form-control field-required" placeholder="Enter Incomplete Reason..">
						</div>
						<div class="col-md-1">	
							<button id="btn_search_incdocreasons" class="btn btn-primary pull-left" onclick="return false;" >Search</button>
						</div>
					</div>
				
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<strong class="col-md-10"><h4>Incomplete Documents Reasons</h4></strong>								
							</div>
						</div>

						<table id="tbl_view" class="table table-hover">
							<thead>
								<tr>
									<th style="width:30px;">No.</th>
									<th>Incomplete Reason</th>
									<th>Document Type Name</th>
									<th>Document Name</th>
									<th>Date Created</th>
									<th style="width:80px;">Action</th>
								</tr>	

							</thead>
							<tbody id="tbl_body">
							</tbody>
						</table>
					</div>	

				</form>
			</div>
		</div>
	</div>

	<!--ADD EDIT MODAL-->

	<div id="modal_edit_incdocreasons" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<span class="edit_incdocreasons" style="display:none">
							<h4 class="modal-title">Edit Incomplete Document Reason</h4>
						</span>

						<span class="add_incdocreasons" style="display:none">
							<h4 class="modal-title">Add Incomplete Document Reason</h4>
						</span>
					</div>
					<div class="modal-body">

						<input type="hidden" id="edit_incdocreasons_id" value="" class="hidden">
						<input type="hidden" id="edit_incdocreasons_type" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Incomplete Reason</span></label>
							<div class="col-md-7">	
								<textarea id="edit_incdocreasons_name" type="text" class="form-control  field-required" rows="5">
								</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Document Type Name</span></label>
							<div class="col-md-7">	
								<select id="select_document_type" class="form-control field-required">
									<option value="0">SELECT DOCUMENT TYPE NAME</option>
									<option value="1">PRIMARY REQUIREMENT</option>
									<option value="2">SECONDARY REQUIREMENT</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Document Name</span></label>
							<div class="col-md-7">	
								<select id="select_document_name" class="form-control field-required" disabled>
								</select>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_save_incdocreasons" class="btn btn-primary" onclick="return false;">Save</button>
								<button id="btn_close_incdocreasons" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>

	<!--END OF MODAL-->

</div>


