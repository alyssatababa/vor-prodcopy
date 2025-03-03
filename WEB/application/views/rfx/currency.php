<html>

<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/rfx/currency.js"></script>

	
	<div id="currency_container" class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Currency</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#modal_add_currency">Add New</button>
			</div>	
		</div>
		
		<div id="b_url" data-base-url="<?php echo base_url().'index.php/rfx/currency/'; ?>"></div>

		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<form id="frm_currency" class="form-horizontal" action="<?php //echo base_url().'index.php/rfx/currency/get_all_currency'?>" method="post">
						
						<div class="form-group">
							<div class="col-md-6">
								<div class="input-group">
								
										<input id="search_currency" type="text" class="form-control field-required" placeholder="Enter Currency, Abbreviation or Country..">
								
									<span class="input-group-btn">
										<button id="btn_search_currency" class="btn btn-primary" onclick="return false;">Search</button>
									</span>
								</div>
							</div>
						</div>
						
						<div class="panel panel-primary">
							<div class="panel-heading">
								<div class="row">
									<strong class="col-md-10"><h4>Currency</h4></strong>								
								</div>
							</div>
							<!--
							<div class="panel-body">					
								<label><?php //echo count($result_data)." Record(s)";?></label>
									<div class = "table-responsive">-->
							<table id="tbl_view" class="table table-hover">
								<thead>
									<tr>
										<th style="width:40px;">No.</th>
										<th>Currency</th>
										<th style="width:200px;">Abbreviation</th>
										<th style="width:200px;">Country</th>
										<th style="width:80px;">Selected</th>
										<th style="width:80px;">Action</th>
									</tr>	

								</thead>
								<tbody id="tbl_body">
								</tbody>
							</table>
						</div>	

						<center>
							<nav aria-label="Page navigation">
								<ul id = "currency_pagination" class="pagination">
									
								</ul>
							</nav>
						</center>	
					</form>

				</div>
			</div>
			
		</div>

	</div>
	
<!--ADD MODAL-->
	<div id="modal_add_currency" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Add New Currency</h4>
					</div>

				
					<div class="modal-body">
						
						<div id = "error_add_currency" class="alert alert-danger alert-dismissable" style="display:none">				
								<strong>Danger!</strong> 
						</div>
							
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Currency </span></label>
							<div class="col-md-7">	
								<input id="input_currency" type="text" class="form-control">
							</div>
						</div>
						
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Abbreviation </span></label>
							<div class="col-md-7">	
								<input id="input_abbreviation" type="text" class="form-control">
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right field-required">Country </span></label>
							<div class="col-md-7">	
								<input id="input_country" type="text" class="form-control">
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_add_currency" class="btn btn-primary">Save</a>
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
	<div id="modal_edit_currency" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Edit Currency</h4>
					</div>
					<div class="modal-body">
					
						<div id = "error_edit_currency" class="alert alert-danger alert-dismissable" style="display:none">				
								<strong>Danger!</strong> 
						</div>					
					
						<input type="hidden" id="edit_id" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Currency </span></label>
							<div class="col-md-7">	
								<input id="edit_currency" type="text" class="form-control field-required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Abbreviation </span></label>
							<div class="col-md-7">	
								<input id="edit_abbreviation" type="text" class="form-control field-required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Country </span></label>
							<div class="col-md-7">	
								<input id="edit_country" type="text" class="form-control field-required">
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_save_currency" class="btn btn-primary" >Save</a>
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

	<div id="edit_selected_currency" role="dialog" data-mval = "asdf234" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close btn_close_radio" data-dismiss="modal">&times;</button>
          				<h4 class="modal-title">Default Currency</h4>
					</div>
					<div class="modal-body">
					
						<span>Do you want to set selected as default Currency?      </span>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<a id="btn_save_radio" class="btn btn-primary">Save</a>
								<button class="btn btn-primary btn_close_radio" data-dismiss="modal">Cancel</button>
							</div>
						</div>						
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>

<!-- 	<div id="edit_selected_currency" role="dialog" data-mval = "asdf234" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close btn_close_radio" data-dismiss="modal">&times;</button>
          				<h4 class="modal-title">Default Currency</h4>
					</div>
					<div class="modal-body">

						<span>Do you want to set selected as default Currency?      </span>
			          
          				<button id = "btn_save_radio" type="button" class="btn btn-success btn-s_c">Save</button>
          				<button type="button" class="btn btn-alert btn-s_c btn_close_radio" data-dismiss="modal">Close</button>
						
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>	 -->
	

</html>