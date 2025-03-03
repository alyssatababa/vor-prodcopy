<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">

	<div class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Currency</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#currencyModal">Add New</button>
			</div>
		</div>
		
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label for="inputsm" class="col-md-2"><span class="pull-right"><h4>Search</h4></span></label>
						</div>
						
						<div class="form-group">
							<label class="col-md-2"><span class="pull-right">Currency </span></label>
							<div class="col-md-4">	
								<select class="form-control" id="sel1">
									<option>All</option>
									<option>Iz</option>
									<option>Well</option>
								</select>
							</div>
						</div>
					</form>
				
			
					<hr>
					
					
						
						<div class="panel-body">					
							<label>33 Record(s)</label>
								
								<table class="tbl_style">
									<tr class="tbl_header">
										<td> # </td>
										<td>Currency</td>
										<td>Abbreviation</td>
										<td>Country</td>
										<td>Action</td>
									</tr>								
									
																
									<tr>
										<?php foreach ($result_data as $row){ ?>
											<?php $i = 1; ?>
											<td>
												<?php echo $i;?>
											</td>
											<td>
												<?php echo $row->CURRENCY; ?>
											</td>
											<td>
												<?php echo $row->ABBREVIATION; ?>
											</td>
											<td>
												<?php echo $row->COUNTRY; ?>
											</td>
											<?php $i++;?>
										<?php } ?> <!-- end row loop-->
										
										<td>
											<button class="btn btn-primary">Edit</button>
											<button class="btn btn-primary">Deactivate</button>
										</td>
									</tr>
									
								</table>



							<center>
								<nav aria-label="Page navigation">
								  <ul class="pagination">
									<li>
									  <a href="#" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
									  </a>
									</li>
									<li class = "active"><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li>
									  <a href="#" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
									  </a>
									</li>
								  </ul>
								</nav>
							</center>	
						</div>

				</div>
			</div>
			
		</div>

	</div>
	
	<div id="currencyModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Currency</h4>
				</div>
				<form class="form-horizontal" action="<?php echo base_url().'index.php/maintenance/currency/add_currency'?>" method="post">
					<div class="modal-body">
						<div class = "row">	
							
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Currency </span></label>
									<div class="col-md-7">	
										<input type="text" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Abbreviation </span></label>
									<div class="col-md-7">	
										<input type="text" class="form-control">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Country </span></label>
									<div class="col-md-7">	
										<input type="text" class="form-control">
									</div>
								</div>
								
						</div>
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button class="btn btn-primary">Save</button>
								<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
