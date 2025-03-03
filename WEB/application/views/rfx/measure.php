<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/rfx/measure.js"></script>

	
	<div id="measure_container" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Unit of Measure</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_measure"  onclick="return false;">Add New</button>
			</div>	
		</div>
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/rfx/measure/'; ?>"></div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<form id="frm_measure" class="form-horizontal">
					
						<div class="form-group">
							<div class="col-md-6">
								<div class="input-group">
								
										<input id="search_measure" type="text" class="form-control field-required" placeholder="Enter Unit of Measure or Abbreviation..">
								
									<span class="input-group-btn">
										<button id="btn_search_measure" class="btn btn-primary"  onclick="return false;">Search</button>
									</span>
								</div>
							</div>
						</div>
						
						<div class="panel panel-primary">
						
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Unit of Measure</h4></strong>								
								</div>
							</div>
	
							<table id="tbl_view" class="table table-bordered">
								<thead>
									<tr>
										<th style="width:40px;">No. </th>
										<th>Unit of Measure </th>
										<th style="width:200px;">Abbreviation </th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
							
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "pagination_measure" class="pagination">
									
								</ul>
							</nav>
						</center>	

					<br>
						
					</form>
				</div>
			</div>
		</div>

	</div>
	
<!--ADD MODAL-->
	<div id="modal_add_measure" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add New Unit of Measure</h4>
					</div>

					<div class="modal-body">
					
						<div id = "error_add_measure" class="alert alert-danger alert-dismissable" style="display:none">			
								<strong>Danger!</strong> 
						</div>
							
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Unit of Measure </span></label>
							<div class="col-md-7">	
								<input id="input_measure" type="text" class="form-control field-required" autofocus>
							</div>
						</div>
				
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Abbreviation </span></label>
							<div class="col-md-7">	
								<input id="input_abbreviation" type="text" class="form-control field-required">
							</div>
						</div>
						
					</div>
					
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_add_measure" class="btn btn-primary" >Save</a>
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
	<div id="modal_edit_measure" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
				
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Unit of Measure</h4>
					</div>
					
					<div class="modal-body">
					
						<div id = "error_edit_measure" class="alert alert-danger alert-dismissable" style="display:none">			
								<strong>Danger!</strong> 
						</div>					
					
						<input type="hidden" id="edit_id" value="" class="hidden">
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Unit of Measure </span></label>
							<div class="col-md-7">	
								<input id="edit_measure" type="text" class="form-control field-required">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Abbreviation </span></label>
							<div class="col-md-7">	
								<input id="edit_abbreviation" type="text" class="form-control field-required">
							</div>
						</div>
						
					</div>
					
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_save_measure" class="btn btn-primary">Save</a>
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