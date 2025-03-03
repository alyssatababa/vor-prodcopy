<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/rfx/requestor.js"></script>
	
	<div id="requestor_container" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Requestor</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#add_modal_requestor"  onclick="return false;">Add New</button>
			</div>	
		</div>
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/rfx/requestor/'; ?>"></div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<form id="frm_requestor" class="form-horizontal">
					
						<div class="form-group">
							<div class="col-md-6">
								<div class="input-group">
								
										<input id="search_requestor" type="text" class="form-control field-required" placeholder="Enter Requestor, Company or Department..">
								
									<span class="input-group-btn">
										<button id="btn_search" class="btn btn-primary"  onclick="return false;">Search</button>
									</span>
								</div>
							</div>
						</div>
	
						<hr>
						
						<div class="panel panel-primary">
						
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Requestor</h4></strong>								
								</div>
							</div>
	
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th style="width:40px;">No.</th>
										<th>Requestor</th>
										<th style="width:200px;">Company</th>
										<th style="width:200px;">Department</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
							
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "requestor_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>	
						
					</form>
				</div>
			</div>
		</div>

	</div>
	
<!--ADD MODAL-->
	<div id="add_modal_requestor" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add New Requestor</h4>
					</div>

					<div class="modal-body">
					
						<div id = "div_add_error" class="alert alert-danger alert-dismissable" style="display:none">			
								<strong>Danger!</strong> 
						</div>
							
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Requestor </span></label>
							<div class="col-md-7">	
								<input id="input_requestor" type="text" class="form-control">
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Company </span></label>
							<div class="col-md-7">	
								<input id="input_company" type="text" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Department </span></label>
							<div class="col-md-7">	
								<input id="input_department" type="text" class="form-control">
							</div>
						</div>
						
					</div>
					
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="add_requestor" class="btn btn-primary">Save</a>
								<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
					
				</form>
			</div>		
		</div>
	</div>
<!--END OF MODAL-->

<!--EDIT MODAL-->
	<div id="edit_modal_requestor" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Requestor</h4>
					</div>
					
					<div class="modal-body">
					
						<div id = "div_edit_error" class="alert alert-danger alert-dismissable" style="display:none">			
								<strong>Danger!</strong> 
						</div>					
					
						<input type="hidden" id="edit_id" value="" class="hidden">
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Requestor </span></label>
							<div class="col-md-7">	
								<input id="edit_requestor" type="text" class="form-control field-required">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Company </span></label>
							<div class="col-md-7">	
								<input id="edit_company" type="text" class="form-control field-required">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Department </span></label>
							<div class="col-md-7">	
								<input id="edit_department" type="text" class="form-control field-required">
							</div>
						</div>
						
					</div>
					
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="save_requestor" class="btn btn-primary">Save</a>
								<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
					
				</form>	
			</div>
		</div>
	</div>
<!--END OF MODAL-->	

</html>