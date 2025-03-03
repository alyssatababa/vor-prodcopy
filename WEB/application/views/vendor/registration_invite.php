<!-- Start Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<span class="add_reginv_template" style="display:none;">
				<h4 class="modal-title" id="myModalLabel">Add Registration Invite Template</h4>
				</span>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<span class="add_reginv_template" style="display:none;">
						<div class="form-group">
							<label for="txt_templatename" class="col-sm-4 control-label">Template Name</label>
							<input type="text" id="txt_templatename" name="txt_templatename" class="form-control" value="Invite with Buyer 3 Contact Details">
						</div>
						<div class="form-group">
							<label for="txt_msg" class="col-sm-4 control-label">Message</label>
							<textarea class="form-control" style="height: 200px;" id="txt_msg" name="txt_msg">We would like to invite to your company to register at our Non Trade Vendor Portal. Please call Juan Dela Cruz at +632-8881234 loc 234 or +63917-88812345. You may also</textarea>
						</div>
					</span>
				</div>
			</div>
			<div class="modal-footer">
			<span class="add_reginv_template" style="display:none;">
				<button type="button" class="btn btn-primary">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</span>

			</div>
		</div>
	</div>
</div>
<!-- END Modal -->

<div class="container mycontainer">
	<div class="row">
		<div class="col-md-4">
			<h4>Registration Invite Template</h4>
		</div>
		<div class="col-md-8">
			<div class="pull-right">
				<div class="btn-group">
					<button type="buttton" class="btn btn-primary ">Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-4">
							<h4>Registration Invite Template</h4>
						</div>
						<div class="col-md-8">
							<div class="form-inline pull-right">
								<div class="btn-group">
									<button type="buttton" class="btn btn-default " id="btn_add_reginv_template">Add</button>
								</div>
								<div class="btn-group">
									<button type="buttton" class="btn btn-default ">Delete</button>
								</div>
							</div>
						</div>
					</div>
				</div>	
				<table class="table table-bordered">
					<tr class="info">
						<th>Select <br> &nbsp;<input type="checkbox" name="chk_all"></th>
						<th>Template</th>
						<th>Message</th>
						<th>Created By</th>
						<th>Date Created</th>
						<th></th>
					</tr>
					<tr>
						<td><input type="checkbox" name="chk_1"></td>
						<td>Template 1</td>
						<td><p>We would like to invite you to register as a vendor at our Non Trade Vendor Portal</p></td>
						<td>Buyer 1</td>
						<td>02/01/2016 10:31</td>
						<td> <a href="#">Edit</a>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="chk_1"></td>
						<td>Template 2</td>
						<td><p>We cordially invite you to register as a vendor at our Non Trade Vendor Portal. Please</p></td>
						<td>Buyer 2</td>
						<td>02/01/2016 16:23</td>
						<td> <a href="#">Edit</a>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="chk_1"></td>
						<td>Invite with Meeting</td>
						<td><p>We cordially invite you a meeting and to register as a vendor at our Non Trade Vendor</p></td>
						<td>Buyer 1</td>
						<td>02/01/2016 11:48</td>
						<td> <a href="#">Edit</a>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span></button></td>
					</tr>
				</table>			
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");
</script>