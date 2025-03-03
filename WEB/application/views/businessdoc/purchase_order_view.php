<div class="row">
	<div class="col-md-12">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Purchase Order</h3>
	  </div>
	  <div class="panel-body">
	  	<div class="row">
		    <div class="col-md-12">
		    	<div class="accordion" role="tablist" aria-multiselectable="true">
		    		<div class="panel panel-default">
					  <div class="panel-heading" role="tab" id="headingOne">
					  	<div class="panel-title">
					  		<div class="row">
		    				<div class="col-md-10">
							  	Current View: Live <img src="<?=base_url()?>/assets/img/live-icon.png" width="20"> [<a href="#"> View Archive <img src="<?=base_url()?>/assets/img/archive-icon.png" width="20"></a>]
							</div>  	
							<div class="col-md-2">  	
							  	<div>
							  		 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
							          Click here to minimize
							        </a>
							  	</div>
							</div>
						  	</div>
						  	</div>
						</div>
					  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
						  	<form class="form-inline">
						    	<div class="row">
						    		<div class="col-md-4">
						    			<div class="form-group">
										    <label for="">Vendor Code:</label>
										    <input type="text" class="form-control" id="" placeholder="00000" disabled>
										 </div>
						    		</div>
									  <div class="col-md-4">
									  	<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Month & Year
											</label>
											<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> MTD
											</label>
											<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> From-To
											</label>
									  </div>
									  <div class="col-md-4">
									  	<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> All
											</label>
											<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> Unread
											</label>
											<label class="radio-inline">
											  <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Read
											</label>
									  </div>
						    	</div>
						    </form>	
						  </div>
					  </div>
					</div>
		    	</div>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12">
		    	<div class="panel panel-default">
					<div class="panel-body">
						<label>75 Record(s)</label>
						<table class="table table-striped">
							<tr class="">
								<td><input type="checkbox" name="all_selected"></td>
								<td>Number</td>
								<td>Description</td>
								<td>Department</td>
								<td>Location</td>
								<td>Post Date</td>
								<td>Status</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="1"></td>
								<td>1</td>
								<td>Test</td>
								<td>Hardware</td>
								<td>Manila</td>
								<td>05/10/2017</td>
								<td>Read</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="2"></td>
								<td>2</td>
								<td>Test</td>
								<td>Workshop</td>
								<td>Quezon City</td>
								<td>05/16/2017</td>
								<td>Unread</td>
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
						<br>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Archiving & Download Options</h3>
				  </div>
				  <div class="panel-body">
				    <div class="row">
				    	<div class="col-md-6">
				    		<div class="btn-group " role="group" aria-label="">
							  <img src="<?=base_url()?>/assets/img/pdf-icon.png" width="50"> 
							  <img src="<?=base_url()?>/assets/img/csv-icon.png" width="50"> 
							  <img src="<?=base_url()?>/assets/img/xml-icon.png" width="50"> 
							</div>
							<div class="clearfix"></div>
							<p>Downloading of specific business documents, or batch of
							specific business documents. Use the checkboxes to make a 
							selection.</p>
				    	</div>
				    	<div class="col-md-6">
				    		<div class="btn-group pull-right" role="group" aria-label="">
							  <img src="<?=base_url()?>/assets/img/pdf-icon.png" width="50"> 
							  <img src="<?=base_url()?>/assets/img/csv-icon.png" width="50"> 
							  <img src="<?=base_url()?>/assets/img/excel-icon.png" width="50"> 
							</div>
							<div class="clearfix"></div>
							<p class="pull-right col-md-4">Downloading the summary table of all business documents/messages in
							these search results (list only).</p>
				    	</div>
				    </div>
				  </div>
				</div>
			</div>
		</div>
	  </div>
		 
	</div>
	</div>
 </div>
