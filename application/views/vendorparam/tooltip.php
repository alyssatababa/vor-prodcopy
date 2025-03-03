

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vendorparam/tooltip.js"></script>


<div id="container_tooltip" class="container mycontainer">

	<div class="row">
		<div class="col-md-10"><h4>Tooltips</h4></div>
	</div>
	
	<div id="b_url" data-base-url="<?php echo base_url().'index.php/vendorparam/tooltip/'; ?>"></div>

	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">

				<form id="frm_tooltip2" name="frm_tooltip2" method="post" class="form-horizontal">
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
							<input id="search_tooltip" type="text" class="form-control field-required" placeholder="Enter Name or Description...">
						</div>
						<div class="col-md-1">	
							<button id="btn_search_tooltip" class="btn btn-primary pull-left" onclick="return false;" >Search</button>
						</div>
					</div>
				
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<strong class="col-md-10"><h4>Tooltips</h4></strong>								
							</div>
						</div>

						<table id="tbl_view" class="table table-hover">
							<thead>
								<tr>
									<th style="width:30px;">No.</th>
									<th style="width:310px;">Field Name</th>
									<th style="width:441;">Tooltip</th>
									<th style="width:65px;">Action</th>
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

		<div id="modal_edit_tooltip" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<span class="edit_tooltip" style="display:none">
							<h4 class="modal-title">Edit Tooltip</h4>
						</span>
					</div>
					<div class="modal-body">

						<input type="hidden" id="edit_tooltip_id" value="" class="hidden">
						<input type="hidden" id="edit_tooltip_type" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Field Name</span></label>
							<div class="col-md-7">	
								<input id="edit_tooltip_name" type="text" class="form-control  field-required" disabled>
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Tooltip </span></label>
							<div class="col-md-7">	
								<textarea id="edit_description" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_save_tooltip" class="btn btn-primary" onclick="return false;">Save</button>
								<button id="btn_close_tooltip" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>

</div>


