<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">

	<div class="container mycontainer">
	
		<div class="row">
			<div class="col-md-10"><h4>Requestor</h4></div>
			<div class="col-sm-2">
				<button id="btn_full" class="btn btn-primary" data-toggle="modal" data-target="#requestorModal">Add New</button>
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
							<label class="col-md-2"><span class="pull-right">Requestor </span></label>
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
						<label>55 Record(s)</label>
						<table class="tbl_style">
							
							<tr class="tbl_header">
								<td> # </td>
								<td>Requestor</td>
								<td>Company</td>
								<td>Address</td>
								<td>Action</td>
							</tr>

							<tr>
								<td> 1 </td>
								<td>Requestor 1</td>
								<td>Company 1</td>
								<td>Address 1</td>
								<td>
									<button class="btn btn-primary">Edit</button>
									<button class="btn btn-primary">Deactivate</button>
								</td>
							</tr>
							<tr>
								<td> 2 </td>
								<td>Requestor 2</td>
								<td>Company 2</td>
								<td>Address 2</td>
								<td>
									<button class="btn btn-primary">Edit</button>
									<button class="btn btn-primary">Deactivate</button>
								</td>
							</tr>
							<tr>
								<td> 3 </td>
								<td>Requestor 3</td>
								<td>Company 3</td>
								<td>Address 3</td>
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
	
	<div id="requestorModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Add New Requestor</h4>
				</div>
				<div class="modal-body">
					<div class = "row">	
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-md-3"><span class="pull-right">Requestor </span></label>
								<div class="col-md-7">	
									<input type="text" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3"><span class="pull-right">Company </span></label>
								<div class="col-md-7">	
									<input type="text" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3"><span class="pull-right">Address </span></label>
								<div class="col-md-7">	
									<input type="text" class="form-control">
								</div>
							</div>
						</form>		
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
			</div>
		</div>
	</div>
