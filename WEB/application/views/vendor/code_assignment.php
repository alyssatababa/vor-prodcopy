<div class="container mycontainer">
	<div class="row">
		<div class="col-md-6">
			<h4>Vendor Code Assignment</h4>
		</div>
		<div class="col-md-6">
			<div class="pull-right">
				<div class="btn-group">
					<button type="buttton" class="btn btn-primary " id="btn_get_vendor_code">Reprocess</button>
				</div>
				<!-- <div class="btn-group">
					<button type="buttton" class="btn btn-primary " onclick="printJS()">Print</button>
				</div> -->
				<!-- <div class="btn-group">
					<button type="buttton" class="btn btn-primary " id="save_vendor_code">Save</button>
				</div> -->
				<div class="btn-group">
					<button type="buttton" class="btn btn-primary btn-exit">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="form_container">
	<div class="panel panel-default">
						<div class="panel-body">
		<div class="col-md-12">
			<form id="frm_code_assign" method="post">
				<input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>">
				<input type="hidden" id="registration_type" name="registration_type" value="<?php echo $registration_type; ?>">
				<input type="hidden" id="category_id" name="category_id" value="<?php echo $category_id; ?>">
				<div class="row">
					<div class="col-md-12">
						<!-- <div class="form-group">
							<label for="vendor_name" class="col-md-6 control-label">Vendor Name</label>

							<div class="col-md-6 ">
								 <p class="form-control-static" ><?php echo strtoupper($vendor_name); ?></p>
							</div>

						</div> -->
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<table class="table table-bordered">
								<tr>
									<td><label>Vendor Name</label></td>
									<td><input id="vendor_name" disabled="" value="<?php echo strtoupper($vendor_name); ?>"></input></td>
								</tr>
							<?php
								if($registration_type == 4){
							?>
								<tbody>
									<tr>
										<td>Vendor Type</td>
										<td><input id="vendor_type" name="vendor_type" disabled="" value="<?php echo $vendor_type; ?>"></input></td>
										<!-- <td><input type="text" class="form-control field-required  limit-chars" maxlength="100" id="txt_old_vendor_code" name="txt_old_vendor_code" value="<?php echo $vendor_code; ?>" readonly></td> -->
									</tr>
									<tr>
										<td><label id="vendor_type" name="vendor_type">Additional Vendor Code</label></td>
										<td><input type="text" class="form-control field-required  limit-chars" maxlength="100" id="txt_vendor_code" name="txt_vendor_code" value=""></td>
									</tr>
									<tr>
										<td><label>Tag as Watson Vendor</label></td>
										<td><input type="checkbox" id="chkbox_watson" name="chkbox_watson"></td>
									</tr>
								</tbody>
							<?php
								}else{
							?>
								<tbody>
									<tr>
										<td><label>TIN</label></td>
										<td><input id="tin_no" name="tin_no" disabled="" value="<?php echo $tin_no; ?>"></input></td>
									</tr>
									<tr>
										<td><label>Vendor Type</label></td>
										<td><input id="vendor_type" name="vendor_type" disabled="" value="<?php echo $vendor_type; ?>"></input></td>
										<!-- <td><input type="text" class="form-control field-required  limit-chars" maxlength="100" id="txt_vendor_code" name="txt_vendor_code" value=""></td> -->
									</tr>
									<tr>
										<td><label>Vendor Code</label></td>
										<td><input id="vendor_code" name="vendor_code" disabled=""></input></td>
									</tr>

									<?php
										if($category_id == 259 || $watson_vendor == true){
									?>
									<tr>
										<td><label>Tag as Watson Vendor</label></td>
										<td><input type="checkbox" id="chkbox_watson" name="chkbox_watson" checked="true" disabled></td>
									</tr>
									<?php
								}else{
							?>
									<tr>
										<td><label>Tag as Watson Vendor</label></td>
										<td><input type="checkbox" id="chkbox_watson" name="chkbox_watson" disabled></td>
									</tr>
							<?php
								}
							?>
								</tbody>
							<?php
								}
							?>
						</table>

					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
	</div>
	
	
</div>
<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js');?>");

	function printJS()
	{
		var id = $('#vendor_id').val();
		window.open(BASE_URL+'vendor/registrationreview/print_template/'+id);
	}

	function get_VendorCode(){

	
		/*var path_value = "http://sapipo2.smretailinc.com:50000/XISOAPAdapter/MessageServlet?senderService=Zycus&interface=OB059_SI_ZYCUS_REQ&interfaceNamespace=http://smretail.com/xi/SMRI/S4HANA/FI";

		parameter = 'senderService=Zycus'+
                '&interface=OB059_SI_ZYCUS_REQ'+
                '&interfaceNamespace=http://smretail.com/xi/SMRI/S4HANA/FI';

		xmlhttp = new XMLHttpRequest();

		xmlhttp.open("POST", path_value, ['zycus', 'smriAp1d3V']);
	    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	    xmlhttp.send(parameter);*/
	}

</script>