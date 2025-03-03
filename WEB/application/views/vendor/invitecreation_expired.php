
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-6"><h4>Vendor Registration Invite - <font color="red">Expired</font></h4></div>
		<div class="col-md-6">
			<span class="pull-right">
				<button type="button" class="btn btn-primary">Extend Invite</button>
				<button type="button" class="btn btn-primary">Close Invite</button>
				<button type="button" class="btn btn-primary">Exit</button>
			</span>
		</div>
	</div>
	<div class="spacer"></div>
	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">
			
				<form class="form-horizontal">
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Vendor Name</span></label>
						<div class="col-md-6">	
							<input class="form-control input-sm limit-chars" id="inputsm" type="text" value="AAA Inc.">
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Contact Person</span></label>
						<div class="col-md-6">	
							<input class="form-control input-sm limit-chars" id="inputsm" type="text">
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Email</span></label>
						<div class="col-md-6">	
							<input class="form-control input-sm limit-chars" id="inputsm" type="text">
						</div>
						<!-- <div class="col-md-4">	
							<button type="button" class="btn btn-default">Find Similar</button>
						</div> -->
					</div>
					
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Message Template</span></label>
						<div class="col-md-6">	
							  <select class="form-control" id="sel1">
								<option>Template 1</option>
								<option>Template 2</option>
								<option>Template 3</option>
								<option>Template 4</option>
							  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Message to Vendor</span></label>
						<div class="col-md-10">	
							   <textarea class="form-control" rows="5" id="comment">Dear John, </textarea>
							   <textarea class="form-control" rows="8" id="comment"></textarea>
							   <em class="small">*Please note that the login id and date of expiration may change due to the approval of invite</em>
						</div>
					</div>
					
					<div class="form-group">
						<label for="inputsm" class="small col-md-2"><span class="pull-right">Note to Approver</span></label>
						<div class="col-md-10">	
							   <textarea class="form-control limit-chars" rows="4" id="comment"></textarea>
						</div>
					</div>

				</form>
			
			</div>
		</div>
	</div>
</div>