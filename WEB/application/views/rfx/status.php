<html>
<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/rfx/status.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.css"/>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.15/datatables.min.js"></script>


	<div class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Status For RFx</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_status"  onclick="return false;">Add New</button>
			</div>	
		</div>
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/rfx/status/'; ?>"></div>
		
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">				
					<form id="frm_status" class="form-horizontal">
					
						<div class="form-group">
							<div class="col-md-4">
								<div class="input-group">
									<input id="search_status" type="text" class="form-control field-required" placeholder="Enter Status..">
								
									<span class="input-group-btn">
										<button id="btn_search_status" class="btn btn-primary"   onclick="return false;">Search</button>
									</span>
								</div>
							</div>
						</div>
					

						<hr>

						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Status</h4></strong>	
							
								</div>
							</div>
							
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th style="width:40px;">No. </th>
										<th>Status</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
						


						</div>


						<center>
							<nav aria-label="Page navigation">
								<ul id = "status_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>
					</form>
					
				</div>
			</div>
			
		</div>

	</div>

<!--ADD MODAL-->
	<div id="modal_add_status" class="modal fade" data-backdrop="static" data-keyboard="false">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add New Status</h4>
					</div>
				
					<div class="modal-body">
						
						<div id = "error_add_status" class="alert alert-danger alert-dismissable" style="display:none">				
								<strong>Danger!</strong> 
						</div>
							
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Status </span></label>
							<div class="col-md-7">	
								<input id="input_status" type="text" class="form-control">
							</div>
						</div>
						
					
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_add_status" class="btn btn-primary" >Save</a>
								<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
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
	<div id="modal_edit_status" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Status</h4>
					</div>
					<div class="modal-body">
						<div id = "error_edit_status" class="alert alert-danger alert-dismissable" style="display:none">	
								<strong>Danger!</strong> 
						</div>
						<input type="hidden" id="edit_id" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Status </span></label>
							<div class="col-md-7">	
								<input id="edit_status" type="text" class="form-control field-required">
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_save_status" class="btn btn-primary" >Save</a>
								<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
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