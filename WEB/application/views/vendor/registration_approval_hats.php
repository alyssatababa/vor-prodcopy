<!-- Start Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<span class="submit" style="display:none;">
					<center><h4 class="modal-title" id="myModalLabel">Submit</h4></center>
				</span>
				<span class="incomplete" style="display:none;">
				<h4 class="modal-title" id="myModalLabel">Incomplete Reason</h4>
				</span>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<span class="submit" style="display:none;">
						<p>Registration Submitted...</p>
						<p>VRD will review the registration you have submitted.</p>
						<br>
						<p>Once validated, an email notification will be sent when you can come to the VRD office with the original documents and electronically sign the registration.</p>
						<br>
						<p>Schedule: Monday to Friday from 1-5 PM</p>
					</span>

					<span class="incomplete" style="display:none;">
						<textarea class="form-control" placeholder="Enter Reason" ></textarea>
					</span>
				</div>
			</div>
			<div class="modal-footer">
			<span class="submit" style="display:none;">
				<center><button type="button" class="btn btn-primary">OK</button></center>
			</span>
			<span class="incomplete" style="display:none;">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary">Ok</button>
			</span>

			</div>
		</div>
	</div>
</div>
<!-- END Modal -->

<div class="container mycontainer">


	<div class="pull-right">
		<div class="btn-group">
			<button type="buttton" class="btn btn-primary ">Approve</button>
		</div>
		<div class="btn-group">
			<button type="buttton" class="btn btn-primary " id="btn_reject" >Reject</button>
		</div>
		<div class="btn-group">
			<button type="buttton" class="btn btn-primary ">Suspend</button>
		</div>
		<div class="btn-group">
			<button type="buttton" class="btn btn-primary ">Exit</button>
		</div>
	</div>

	<h4>Vendor Registration</h4>
	<small><a href="#" >Approval History</a></small>
	<hr>

	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<label for="vendor_name" class="col-sm-6 control-label">Vendor Name</label>

				<div class="col-sm-6 ">
					 <p class="form-control-static">AAA Inc</p>
				</div>

			</div>
		</div>
	</div>
	<div class="row">

		<!-- <div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="brand_name" class="col-sm-1 control-label">Brand</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-10">
							<input type="text" class="form-control input-sm" id="brand_name" placeholder="Brand" readonly>
							<input type="text" class="form-control input-sm" id="brand_name" placeholder="Brand" readonly>
							<input type="text" class="form-control input-sm" id="brand_name" placeholder="Brand" readonly>
						</div>
					</div>
				</div>
				
				<div class="col-sm-6">
					<div class="form-group">
						<label for="yr_business" class="col-sm-4 control-label">* Years in Business</label>

						<div class="col-sm-2">
							<select name="yr_business" class="form-control">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
							</select>
						</div>
					</div>

					<br>
					<div class="form-group">
						<label class="col-sm-3 control-label">Ownership</label>

						<div class="col-sm-9">
							<label class="radio-inline">
						      <input type="radio" name="ownership">Corporation
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="ownership">Partnership
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="ownership">Sole Proprietorship
						    </label>
						</div>
					</div>

					<br>
					<div class="form-group">
						<label class="col-sm-3 control-label">Vendor Type</label>

						<div class="col-sm-9">
							<label class="radio-inline">
						      <input type="radio" name="vendor_type">Outright
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="vendor_type">Consignor
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="vendor_type">Non Trade
						    </label>
						</div>
					</div>
					<br>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="panel panel-default panel-body">
						<div class="form-group">
							<label for="tax_idno" class="col-sm-5 control-label">Tax Identification No</label>

							<div class="col-sm-7">
								<input type="text" class="form-control input-sm" id="tax_idno" placeholder="Tax Identification No" >
							</div>
						</div>

						<br>
						<div class="form-group">
							<label class="col-sm-4 control-label">Tax Classification</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="vendor_type">Vat
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type">Non Vat
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type">Zero Rated
							    </label>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="panel panel-default panel-body">				
						<div class="form-group">
							<label for="nobus" class="col-sm-12 control-label">Nature of Business <small>(Multiple Selection Allowed)</small></label>

							<div class="col-sm-12">
								<label class="checkbox-inline">
							      <small><input type="checkbox" name="vendor_type">Distributor/Licensee</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="vendor_type">Manufacturer</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="vendor_type">Importer/Trader</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="vendor_type">Wholesaler</small>
							    </label>
							</div>
							<div class="col-sm-12 form-inline">
								<label class="checkbox-inline">
							      <small>
							      	<input type="checkbox" name="vendor_type">Others (Pls. Specify)
							      </small>
							    </label>
							    <input type="text" id="nob_text" name="nob_text" value="" class="form-control input-sm" placeholder="Others">
							</div>
						</div>				
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="btn_off_ad" class="control-label col-sm-2">Office Address
					<button class="btn btn-primary btn-xs" id="btn_off_ad" name="btn_off_ad"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
					</label>
					<label class="control-label col-sm-offset-9">Primary</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="office_office_add" name="office_office_add" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="office_brgy_cm" class="form-control input-sm">
	                    	<option value="" selected disabled>Brgy, City/Municipal</option>
							<option value="1">Makati</option>
							<option value="2">Pasay</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <select name="office_state_prov" class="form-control input-sm">
	                    	<option value="" selected disabled>State/Province</option>
							<option value="1">Metro Manila</option>
							<option value="2">EKEK</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <input id="office_zip_code" name="office_zip_code" type="text" placeholder="Zip Code"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="office_country" class="form-control input-sm">
	                    	<option value="" selected disabled>Country</option>
							<option value="1">Philippines</option>
							<option value="2">USA</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-1">
		               <label class="checkbox-inline">
						      <input type="checkbox" name="office_primary">
						      <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></button>
						</label>
	                </div>

	            </div>
            </div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<br>
					<label class="control-label col-sm-2">Factory Address
					<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
					</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="factory_add" name="factory_add" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="factory_brgy_cm" class="form-control input-sm">
	                    	<option value="" selected disabled>Brgy, City/Municipal</option>
							<option value="1">Makati</option>
							<option value="2">Pasay</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <select name="factory_state_prov" class="form-control input-sm">
	                    	<option value="" selected disabled>State/Province</option>
							<option value="1">Metro Manila</option>
							<option value="2">EKEK</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <input id="factory_zip_code" name="zip_code" type="text" placeholder="Zip Code"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="factory_country" class="form-control input-sm">
	                    	<option value="" selected disabled>Country</option>
							<option value="1">Philippines</option>
							<option value="2">USA</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-1">
		               <label class="checkbox-inline">
						      <input type="checkbox" name="factory_primary">
						      <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></button>
						      
						</label>
	                </div>

	            </div>
	        </div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<br>
					<label class="control-label col-sm-3">Warehouse Address
					<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
					</label>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="ware_add" name="ware_add" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="ware_brgy_cm" class="form-control input-sm">
	                    	<option value="" selected disabled>Brgy, City/Municipal</option>
							<option value="1">Makati</option>
							<option value="2">Pasay</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <select name="ware_state_prov" class="form-control input-sm">
	                    	<option value="" selected disabled>State/Province</option>
							<option value="1">Metro Manila</option>
							<option value="2">EKEK</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-2">
	                    <input id="ware_zip_code" name="zip_code" type="text" placeholder="Zip Code"
	                    class="form-control input-sm">                    
	                </div>

	                <div class="col-sm-2">
	                    <select name="ware_country" class="form-control input-sm">
	                    	<option value="" selected disabled>Country</option>
							<option value="1">Philippines</option>
							<option value="2">USA</option>
							<option value="3">QC</option>
						</select>
	                </div>

	                <div class="col-sm-1">
		               <label class="checkbox-inline">
						      <input type="checkbox" name="ware_primary">
						      <button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span></button>
						      
						</label>
	                </div>

	            </div>
            <br><br>
            </div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="tel_no" class="col-sm-2 control-label">Tel No.</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="tel_no" placeholder="Tel No" readonly>
							<input type="text" class="form-control input-sm" id="tel_no" placeholder="Tel No" readonly>
							<input type="text" class="form-control input-sm" id="tel_no" placeholder="Tel No" readonly>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">* Email</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="email" placeholder="Email" readonly>
							<input type="text" class="form-control input-sm" id="email" placeholder="Email" readonly>
							<input type="text" class="form-control input-sm" id="email" placeholder="Email" readonly>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="fax_no" class="col-sm-2 control-label">Fax No.</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="fax_no" placeholder="Fax No." readonly>
							<input type="text" class="form-control input-sm" id="fax_no" placeholder="Fax No." readonly>
							<input type="text" class="form-control input-sm" id="fax_no" placeholder="Fax No." readonly>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="mobile_no" class="col-sm-2 control-label">Mobile No</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-9">
							<input type="text" class="form-control input-sm" id="mobile_no" placeholder="Mobile No" readonly>
							<input type="text" class="form-control input-sm" id="mobile_no" placeholder="Mobile No" readonly>
							<input type="text" class="form-control input-sm" id="mobile_no" placeholder="Mobile No" readonly>
							<br>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="" class="col-sm-6 control-label">Owners/Partners/Directors <small>(Max 10)</small></label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered">
								<tr class="info">
									<th>Name</th>
									<th>Position</th>
								</tr>
								<tr>
									<td><input type="text" id="opd_name_1" name="opd_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="opd_pos_1" name="opd_pos_1" placeholder="Position" class="form-control input-sm"></td>
								</tr>
								<tr>
									<td><input type="text" id="opd_name_1" name="opd_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="opd_pos_1" name="opd_pos_1" placeholder="Position" class="form-control input-sm"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="" class="col-sm-6 control-label">Authorized Representatives <small>(Max 5)</small></label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered">
								<tr class="info">
									<th>Name</th>
									<th>Position</th>
								</tr>
								<tr>
									<td><input type="text" id="ar_name_1" name="ar_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="ar_pos_1" name="ar_pos_1" placeholder="Position" class="form-control input-sm"></td>
								</tr>
								<tr>
									<td><input type="text" id="ar_name_1" name="ar_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="ar_pos_1" name="ar_pos_1" placeholder="Position" class="form-control input-sm"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-5">
					<div class="form-group">
						<div class="col-sm-12">
							<table class="table table-bordered" id="dept_cat">
								<tr class="info">
									<th>Department/Category</th>
								</tr>
								<tr>
									<td>1</td>
								</tr>
								<tr>
									<td>2</td>							
								</tr>
								<tr>
									<td>3</td>
								</tr>
								<tr>
									<td>4</td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-1">
					<div class="form-group">
						<br><br><br>
						<button class="btn btn-default btn-lg " id="btn_move_right"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<div class="col-sm-12">
							<table class="table table-bordered" id="cat_sup">
								<tr class="info">
									<th>Categories Supplied(Categories can added if not found in the list)</th>
								</tr>
								<tr>
									<td>1</td>
								</tr>
								
							</table>

						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Bank References</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered">
								<tr class="info">
									<th>Name</th>
									<th>Branch</th>
								</tr>
								<tr>
									<td><input type="text" id="br_name_1" name="br_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="br_branch_1" name="br_branch_1" placeholder="Branch" class="form-control input-sm"></td>
								</tr>
								<tr>
									<td><input type="text" id="br_name_1" name="br_name_1" placeholder="Name" class="form-control input-sm"></td>
									<td><input type="text" id="br_branch_1" name="br_branch_1" placeholder="Branch" class="form-control input-sm"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="" class="col-sm-6 control-label">Other Retail Customers/Clients</label>
						<button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered">
								<tr class="info">
									<th>Company Name</th>
								</tr>
								<tr>
									<td><input type="text" id="orc_compname" name="orc_compname" placeholder="Company Name" class="form-control input-sm"></td>
								</tr>
								<tr>
									<td><input type="text" id="orc_compname" name="orc_compname" placeholder="Company Name" class="form-control input-sm"></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>	
		</div> 

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="" class="col-sm-2 control-label">Other Business</label>
					

					<div class="col-sm-12">
						<table class="table table-bordered">
							<tr class="info">
								<th>Company Name</th>
								<th>Products/Services Offered</th>
							</tr>
							<tr>
								<td><input type="text" id="ob_compname" name="ob_compname" placeholder="Company Name" class="form-control input-sm"></td>
								<td><input type="text" id="ob_pso" name="ob_pso" placeholder="Products/Services Offered" class="form-control input-sm"></td>
							</tr>
							<tr>
								<td><input type="text" id="ob_compname" name="ob_compname" placeholder="Company Name" class="form-control input-sm"></td>
								<td><input type="text" id="ob_pso" name="ob_pso" placeholder="Products/Services Offered" class="form-control input-sm"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="" class="col-sm-5 control-label">Disclosure of Relatives Working in SM or its Affiliates</label>
					

					<div class="col-sm-12">
						<table class="table table-bordered">
							<tr class="info">
								<th colspan="2">Employee Name</th>
								<th>Position</th>
								<th>Company Affiliated With</th>
								<th>Relationship</th>
							</tr>
							<tr>
								<td><input type="text" id="fname_1" name="fname_1" placeholder="First Name" class="form-control input-sm"></td>
								<td><input type="text" id="lname_1" name="lname_1" placeholder="Last Name" class="form-control input-sm"></td>
								<td><input type="text" id="pos_1" name="pos_1" placeholder="Position" class="form-control input-sm"></td>
								<td><input type="text" id="comp_afw_1" name="comp_afw_1" placeholder="Company Affiliated With" class="form-control input-sm"></td>
								<td><input type="text" id="rel_1" name="rel_1" placeholder="Relationship" class="form-control input-sm"></td>
							</tr>
							<tr>
								<td><input type="text" id="fname_2" name="fname_2" placeholder="First Name" class="form-control input-sm"></td>
								<td><input type="text" id="lname_2" name="lname_2" placeholder="Last Name" class="form-control input-sm"></td>
								<td><input type="text" id="pos_2" name="pos_2" placeholder="Position" class="form-control input-sm"></td>
								<td><input type="text" id="comp_afw_2" name="comp_afw_2" placeholder="Company Affiliated With" class="form-control input-sm"></td>
								<td><input type="text" id="rel_2" name="rel_2" placeholder="Relationship" class="form-control input-sm"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div> -->

		<!-- <div class="row"> -->
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="form-group">
							<h3 class="panel-title">
								Required Scanned Documents <!-- (PDF/Jpeg format max size...) [3/6] -->
								<!-- <div class="pull-right col-sm-4">
									
										<div class="col-sm-7">
											<select name="yr_business" class="form-control ">
											<option value=""></option>
											<option value="2">SEC CERTIFICATE</option>
											<option value="3">General Information Sheet</option>
											<option value="4">BIR Certificate</option>
											<option value="5">Receipt</option>
											<option value="6">BDO</option>
											<option value="7">ID</option>
											<option value="8">Authorization</option>
										</select>
										</div>
										
		                			<button class="btn btn-default " type="button"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
	                			</div> -->
                			</h3>

						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<tr class="info">
								<th></th>
								<th>Document</th>
								<th>View</th>
								<th>Date Uploaded</th>
								<th>Reviewed</th>
								<th>Date Reviewed</th>
								<th>Verified</th>
								<th>Date Verified</th>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>Sec Certificate</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>General Information Sheet</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>BIR Certificate</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked=""></td>
								<td>Receipt</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>BDO</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>ID</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>Authorization</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							</table>
					</div>
				</div>	
			</div>			
		<!-- </div> -->

		<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="form-group">
							<h3 class="panel-title">
								Required Agreements <!-- (PDF/Jpeg format max size...) [2/5] -->
								<!-- <div class="pull-right col-sm-4">
									
										<div class="col-sm-7">
											<select name="yr_business" class="form-control ">
											<option value=""></option>
											<option value="2">Vendor Agreement</option>
											<option value="3">Vendor Guidelines</option>
											<option value="4">Merchandise Undertakings</option>
											<option value="5">Vendor ID and Pass</option>
											<option value="6">Vendor Portal Contract</option>
										</select>
										</div>
										
		                			<button class="btn btn-default " type="button"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
	                			</div> -->
                			</h3>

						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<tr class="info">
								<th></th>
								<th>Document</th>
								<th>View</th>
								<th>Date Uploaded</th>
								<th>Reviewed</th>
								<th>Date Reviewed</th>
								<th>Verified</th>
								<th>Date Verified</th>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked=""></td>
								<td>Vendor Agreement</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>Vendor Guidelines</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>Merchandise Undertakings</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="rsd_document_chk" checked=""></td>
								<td>Vendor ID and Pass</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr><tr>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>Vendor Portal Contract</td>
								<td><button class="btn btn-default btn-xs"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
								<td>2017-05-25</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-26</td>
								<td><input type="checkbox" name="rsd_document_chk" checked></td>
								<td>2017-05-27</td>
							</tr>
						</table>
					</div>
				</div>	
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<!-- <button class="btn btn-default " type="button"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span>&nbspCapture Signature</button>
						<br>
						<br> -->
					</div>
					<div class="col-xs-6 col-md-3">
					    <div class="thumbnail">
					      	<img src="<?=base_url();?>assets/img/devices.png" alt="..." style="height: 100px">
					    </div>
				  	</div>
				  	<div class="col-xs-6 col-md-3">
					    <div class="thumbnail">
					      	<img src="<?=base_url();?>assets/img/sass-less.png" alt="...">
					    </div>
				  	</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-sm-12 control-label">SM Internal View</label><br><br>
						<label for="txt_tp" class="col-sm-2 control-label">Terms of Payment</label>
						<div class="col-sm-3">
						<input type="text" id="txt_tp" name="txt_tp"  class="form-control" value="30 days">
						</div>
					</div>
					<br><br>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_pvremark" class="col-sm-2 control-label">Personal View-Remark</label>
						<div class="col-sm-12">
							<textarea id="txt_pvremark" class="form-control" placeholder="Enter Remark Here"></textarea>
							<br>
						</div>
					</div>

				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-12">
						<button class="btn btn-default " type="button"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span>&nbspCapture HTS Signature</button>
						<br>
						<br>
					</div>
					<div class="col-xs-6 col-md-3">
					    <div class="thumbnail">
					    	<a href="#"><span class="close glyphicon glyphicon-trash"></span></a>
					      	<img src="<?=base_url();?>assets/img/devices.png" alt="..." style="height: 100px">
					    </div>
				  	</div>
				</div>
			</div>

	</div>
</div>

<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");
</script>