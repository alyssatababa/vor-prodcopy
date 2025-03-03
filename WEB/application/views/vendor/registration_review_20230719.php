<?php
		//echo "<pre>"; $var = get_defined_vars(); print_r($var); echo "</pre>"; exit();
		//print_r($s);
		//print_r($ad_approved_items);
		//echo 'test-'.$vendor_code_02.'-ing';
		$view_only = (isset($view_only)? $view_only : '');
		$current_status = (isset($current_status)) ? $current_status : '';
		$avc_original_file_name = isset($avc_original_file_name) ? $avc_original_file_name : '';
		$avc_file_path = isset($avc_file_path) ? $avc_file_path : '';
		$ad_approved_items = isset($ad_approved_items) ? $ad_approved_items : array();
?>
<style>

.cls_brand,
.cls_opd,
.cls_authrep,
.cls_orcc,
.cls_otherbusiness,
.cls_affiliates,
.cls_office_addr,
.cls_factory_addr,
.cls_wh_addr,
.cls_bankrep,
#txt_nob_others{
	text-transform: uppercase;
}


</style>

<link href="<?php echo base_url().'assets/css/jquery.guillotine.css'; ?>" media='all' rel='stylesheet'>
<script src="<?php echo base_url().'assets/js/jquery.guillotine.js'; ?>"></script>
<div class="modal fade" id="delModal" tabindex="-1" role="dialog" aria-labelledby="delModal">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<label style="color:white;">Delete Vendor</label>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger" id="modal_alert_danger" style="display:none"></div>
				<label>Reason:</label>
				<textarea class="form-control" rows="5" id="delReason" style="resize:none"></textarea>
			</div>
			<div class="modal-footer">
				<button class="btn btn-danger" type="button" onclick="delete_vendor()">Confirm</button>
				<button class="btn btn-primary" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>

<div class="container mycontainer">
	<div class="pull-right">
				<?php


					$dsb = 'disabled';
					$_status = array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,122,128);
			
					if(in_array($status, $_status)){
						$dsb = '';
					}
					
					//Jay
					// Position ID
					
					// 1 = Admin
					// 3 = BU HEAD
					// 4 = VRD STAFF
					// 5 = VRD HEAD
					// 6 = HATS
					// 8 = GROUP HEAD
					// 9 = FAS HEAD
					
					// Option to waive the uploaded documents(Primary Documents)
					$waive_permission_ids = array(4);
					$waive_edit_remarks_permission_ids = array(1, 3, 4, 5, 6, 8, 9);
					$has_full_waive_permission = false;
					$has_edit_waive_remark_permission = false;
					
					if(in_array( $position_id ,$waive_permission_ids)){
						$has_full_waive_permission = true;
						$has_edit_waive_remark_permission = true;
					}else if(in_array( $position_id ,$waive_edit_remarks_permission_ids)){
						$has_full_waive_permission = false;
						$has_edit_waive_remark_permission = true;
					}
					//Jay end
				?>

			<?php //$enabledID = [1, 3, 4, 5, 6, 7, 241, 246, 242, 244, 11, 191]; ?>
			<?php //if(in_array($current_status,$enabledID)): ?>
				<?php if($position_id == 2): ?>
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal">Delete</button>
				<?php endif; ?>
			<?php //endif; ?>
							
			<?php if($position_id == 1 || $position_id == 11): ?>
			<div class="btn-group" style="display:none">
				<button type="button" class="btn btn-danger" id="delete_vendor">Delete</button>
			</div>
			<?php endif; ?>
		<?php
		if ($view_only != 1) :
			if (isset($validate)) :
		?>
			<div class="btn-group">
				<button type="button" class="btn btn-primary " onclick="printJS()">Print</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary " id="btn_sad_valid">Save As Draft</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary " id="btn_submit_valid" disabled>Submit</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary btn-exit">Exit</button>
			</div>
			<?php 
			else:
			?>
			<div class="btn-group">
				<button type="button" class="btn btn-primary " onclick="printJS()">Print</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary " id="btn_inc">Incomplete</button>
			</div>
			<div class="btn-group">
				<!-- <button type="button" class="btn btn-primary " id="btn_rf_visit" disabled>Request for Visit</button> -->
				<button type="button" class="btn btn-primary " id="btn_sub_review" disabled>Submit</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn btn-primary btn-exit">Exit</button>
			</div>
			<?php 
			endif;
		else:
		?>
		<?php if($position_id == 4 || $position_id == 5 || $position_id == 6): ?>
			<div class="">

				<?php 
					$add_dept = array(250,252,254);
					if(in_array($current_status,$add_dept)){
				?>
				<button type="button" class="btn btn-primary " onclick="print_department()">Print Department</button>
				<?php } ?>
				<button type="button" class="btn btn-primary " onclick="printJS()">Print Info Sheet</button>
				<!-- <div class="col-sm-3">
					<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $original_file_name; ?>">
					<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $file_path; ?>">
				</div>
				<?php if($file_path != ''): ?>
					<div class="col-sm-1">
						<button class="btn btn-default" type="button" id="btn_invite_view" name="btn_invite_view" value="<?php echo $file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
					</div>
				<?php endif; ?> -->

			</div>
		<?php endif; ?>
		<?php
		endif;
		?>

	</div>

	<h4>Vendor Registration &emsp;<!-- <small><a href="" >Messages</a></small> --></h4>
	<?php 
		//update MSF - 20191105 (IJR-10612)
		//if ($view_only == 1) : 
	?>
	<a href="#" id="vi_approval_history"> <span class="small">Approval History</a>
	<a href="#" id="vi_revision_history"> <span class="small">Revision History</a>
	<?php
		echo('<a href="#" id="vi_previous_company_name" class="cls_action"><span class="small"></a>');	
		echo('<a href="#" id="vi_new_company_name" class="cls_action"><span class="small"></a>');	
	?>
	<?php //endif; ?>
	<!-- jay Remove inside of if($view_only)-->
	<input type="hidden" id="invite_id" name="invite_id" value="<?php echo isset($invite_id)?$invite_id:''; ?>">
	<!-- jay end-->
	<div class="form_container">
	<div class="panel panel-default">
						<div class="panel-body">
		<form id="frm_registration_review" name="frm_registration_review" method="post">
		<input type="hidden" id="registration_type" name="registration_type" value="<?php echo isset($registration_type)?$registration_type:''; ?>">
		<input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>">
		<input type="hidden" id="view_only" name="view_only" value="<?php echo $view_only; ?>">
		<input type="hidden" id="status_id" name="status_id" value="">
		<input type="hidden" id="audit_logs" name="audit_logs" value="">
		<div class="row">

		
				<div class="col-sm-12" id="note_hts" style="display: <?=isset($display)? $display : 'none'?>;">
					<div class="row">
					<div class="col-sm-12">
						<div class="panel panel-primary">
							<div class="panel-body">
							<div class="form-group">
								<div class="col-sm-2">
									HaTS Remarks: 
								</div>
								<div class="col-sm-8">
									<textarea class="form-control limit-chars" id="note" name="note" placeholder="Note" 
									style="width: 100%; min-height: 150px; max-height: 300px; min-width: 100%;max-width: 100%;" maxlength="300"></textarea>
									<button id="edit_note_hts" class="btn btn-primary" disabled>Save Remarks</button>
								</div>
								<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
					<div class="col-sm-12" id="note_vrd" style="display: <?=isset($display_vrd)? $display_vrd : 'none'?>;">
						
						<div class="row">
						<div class="col-sm-12">
							<div class="panel panel-primary">
								<div class="panel-body">
								<div class="form-group">
									<div class="col-sm-2">
										VRD Head's Remarks: 
									</div>
									<div class="col-sm-8">
										<textarea class="form-control limit-chars" id="vrd_note" name="vrd_note" placeholder="Vrd Head Note" 
										style="width: 100%; min-height: 150px; max-height: 300px; min-width: 100%;max-width: 100%;" <?= (isset($display) && $display == 'inline')? 'readonly' : ''; ?>  maxlength="300"></textarea>
										<button id="edit_note_vrd" class="btn btn-primary" style="display:<?= (isset($display) && $display == 'inline')? 'none' : ''; ?>" disabled>Save Remarks</button>
									</div>
									<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
									</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="vendor_name" class="control-label">  Type of Invite : </label>
						</div>
						
						<div class="col-sm-6">
							<p class="form-control-static no-padding" id="vendor_invite_type" name="vendor_invite_type" ></p>
						</div>

					</div>
				</div>
				
			<div id="test" name="test" style="display: none;">
				<div class="col-sm-12">
					<div class="col-sm-2">
						<label for="vendor_name" class="control-label">  Old Vendor Name : </label>
					</div>
					<div class="col-sm-4">
						<p class="form-control-static no-padding" id="old_vendor_name"></p>
					</div>
					<div class="col-sm-2">
						<label for="vendor_name" class="control-label">  Old Vendor Code : </label>
					</div>
					<div class="col-sm-4">
						<p class="form-control-static no-padding" id="old_vendor_code"></p>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group">
					
					<label for="vendor_name" class="control-label col-md-2 col-sm-6 ">Vendor Name</label>
					
					<div class="col-md-8 col-sm-6">
						 <p class="form-control-static no-padding" id="vendor_name"></p>
					</div>

				</div>
			</div>
			
			<div class="col-sm-12">
				<div class="col-md-6 col-sm-12 no-padding">
					<div class="form-group">
						<label for="brand_name" class="col-md-2 col-sm-6 control-label">Brand</label>
						<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
							
							
						<div class="col-md-10 col-sm-6" id="div_brand">
							<input type="hidden" id="brand_count" name="brand_count" value="1">
							<div class="cls_div_brand" id="div_brandid1">
								<input type="hidden" class="form-control input-sm cls_brand" id="brand_id1" name="brand_id1" >
								<input type="text" class="form-control input-sm cls_brand field-required" id="brand_name1" name="brand_name1" placeholder="Brand" readonly>
							</div>
						</div>
					</div>
					<!-- Added & Modified MSF 20200924 -->
					<div class="form-group" id="div_no_employees">
						<label class="col-sm-3 control-label">No. of Employees</label>

						<div class="col-sm-9">
							<label class="radio-inline">
						      <input type="radio" name="no_of_employee" value="0" class="" disabled>MICRO (1 - 9)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="no_of_employee" value="1" class="" disabled>SMALL (10 - 99)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="no_of_employee" value="2" class="" disabled>MEDIUM (100 - 199)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="no_of_employee" value="3" class="" disabled>LARGE (200 and above)
						    </label>
						</div>
					</div>
					
					<div class="form-group" id="div_business_assets">
						<label class="col-sm-3 control-label">MSME Business Asset Classification</label>

						<div class="col-sm-9">
							<label class="radio-inline">
						      <input type="radio" name="business_asset" value="0" class="" disabled>MICRO (Up to P3,000,000)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="business_asset" value="1" class="" disabled>SMALL (P3,000,001 - P15,000,000)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="business_asset" value="2" class="" disabled>MEDIUM (P15,000,001 - P100,000,000)
						    </label>
							<br/>
						    <label class="radio-inline">
						      <input type="radio" name="business_asset" value="3" class="" disabled>LARGE (P100,000,001 and above)
						    </label>
						</div>
					</div>
				</div>
				
				<div class="col-md-6 col-sm-12 no-padding" id="div_years_business">
					<div class="form-group">
						<label for="yr_business" class="col-sm-4 control-label">Years in Business</label>
						<!-- Added & Modified MSF 20200924 -->
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm field-required" id="cbo_yr_business" name="cbo_yr_business" style="width:50px" readonly>							
						</div>
					</div>
					<div class="form-group">
						<!-- Added & Modified MSF 20200924 -->
						<label class="col-sm-4 control-label">Ownership</label>

						<div class="col-sm-8">
							<label class="radio-inline">
						      <input type="radio" name="ownership" value="1" class="" disabled>Corporation
						    </label>
						    <br>
						    <label class="radio-inline">
						      <input type="radio" name="ownership" value="2" class="" disabled>Partnership
						    </label>
						    <br>
						    <label class="radio-inline">
						      <input type="radio" name="ownership" value="3" class="" disabled>Sole Proprietorship
						    </label>
						    <br>
						    <label class="radio-inline">
						      <input type="radio" name="ownership" value="4" class="" disabled>Free Lance
						    </label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Vendor Type</label>

						<div class="col-sm-8">
							<label class="radio-inline">
						      <input type="radio" name="vendor_type" value="1" class="" disabled>Trade
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="vendor_type" value="2" class="" disabled>Non Trade
						    </label>
						    <label class="radio-inline">
						      <input type="radio" name="vendor_type" value="3" class="" disabled>Non Trade Service
						    </label>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12 no-padding">
					<?php if($vendor_type == 1):?>
					<div class="form-group">
						<label class="col-sm-4 control-label">Trade Vendor Type</label>

						<div class="col-sm-8">
							<label class="radio-inline">
						      <input type="radio" name="trade_vendor_type" value="1" disabled>
							  <input type="checkbox" name="chk_trade_vendor_type" value="1" style="display: none;" disabled>
							  Outright
						    </label>
						    <label class="radio-inline">
							  <input type="checkbox" name="chk_trade_vendor_type" value="2" style="display: none;" disabled>
						      <input type="radio" name="trade_vendor_type" value="2" disabled>
							  Consignor
						    </label>
						    <label class="radio-inline"> <!-- do not show this -->
						      <input type="radio" name="trade_vendor_type" value="0" style="display: none;" > <!-- Non Trade -->
						    </label>
						</div>
					</div>
					<?php endif;?>
				</div>
				<?php if($vendor_code_02 != ''){ ?>
					<div class="col-md-6 col-sm-12 no-padding">
						<?php if($vendor_type == 1):?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Vendor Code</label>

							<div class="col-sm-8">
						    <label class="radio-inline">
								<span id="outright_code" name="outright_code" class="radio-inline"></span>
						    </label>
						    <label class="radio-inline">
								<span id="sc_code" name="sc_code" class="radio-inline"></span>
						    </label>
								<label class="radio-inline"> <!-- do not show this -->
								  <input type="radio" name="trade_vendor_type" value="0" style="display: none;" > <!-- Non Trade -->
								</label>
							</div>
						</div>
						<?php endif;?>
					</div>
					<div class="col-sm-12">
						<br>
					</div>
				<?php } ?>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-6" id="div_tax">
					<div class="panel panel-default panel-body">
						<div class="form-group">
							<label for="tax_idno" class="col-sm-4 control-label">Tax Identification No</label>

							<div class="col-sm-7">
								<input type="text" class="form-control input-sm " id="tax_idno" name="tax_idno" placeholder="Tax Identification No" readonly>
							</div>
						</div>

						<br>
						<div class="form-group">
							<label for="tax_class" class="col-sm-4 control-label">Tax Classification</label>
							<div class="col-sm-7">
								<label class="radio-inline">
							      <input type="radio" name="tax_class" value="1" class="" disabled>VAT
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="tax_class" value="2" class="" disabled>Non VAT
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="tax_class" value="3" class="" disabled>Zero Rated
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
							      <small><input type="checkbox" name="nob_license_dist" disabled>Distributor/Licensee</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="nob_manufacturer" disabled>Manufacturer</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="nob_importer" disabled>Importer/Trader</small>
							    </label>
							    <label class="checkbox-inline">
							      <small><input type="checkbox" name="nob_wholesaler" disabled>Wholesaler</small>
							    </label>
							</div>
							<div class="col-sm-12 form-inline">
								<label class="checkbox-inline">
							      <small>
							      	<input type="checkbox" name="nob_others" disabled>Others (Pls. Specify)
							      </small>
							    </label>
							    <input type="text" id="txt_nob_others" name="txt_nob_others" value="" class="form-control input-sm" placeholder="Others" readonly>
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
					<!-- <button class="btn btn-primary btn-xs" id="btn_off_ad" name="btn_off_ad"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
					</label>
					<input type="hidden" id="office_addr_count" name="office_addr_count" value="1">
					<label class="control-label col-sm-offset-9">Primary</label>
				</div>
			</div>
		</div>

		<div class="row" id="div_row_offc_addr">	
			<div class="col-sm-12 cls_div_office_addr" id="div_office_addr1" name="div_office_addr1">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="office_add1" name="office_add1" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm cls_office_addr" readonly>                    
	                </div>

	                <div class="col-sm-3">
	                    <input type="text" name="office_brgy_cm1" id="office_brgy_cm1" class="form-control input-sm cls_office_addr" readonly>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" name="office_state_prov1" id="office_state_prov1" class="form-control input-sm cls_office_addr" readonly>
	                </div>

	                <div class="col-sm-1">
	                    <input id="office_zip_code1" name="office_zip_code1" type="text" placeholder="Zip Code" class="form-control input-sm cls_office_addr" readonly>                    
	                </div>

	                <div class="col-sm-2" style="display: none;">
						<input id="office_region1" name="office_region1" type="text" placeholder="Region" class="form-control input-sm cls_office_addr" data-label="oa_addrregion" maxlength="20" disabled>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" name="office_country1" id="office_country1" class="form-control input-sm cls_office_addr" readonly>
	                </div>

	                <div class="col-sm-1">
		               <label class="radio-inline">
						      <input type="radio" name="office_primary" value="1" disabled>
						      <!-- <button type="button" class="btn btn-default btn-xs remove_offc_addr"><span class="glyphicon glyphicon-trash"></span></button> -->
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
					<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
					</label>
					<input type="hidden" id="factory_addr_count" name="factory_addr_count" value="1">
				</div>
			</div>
		</div>

		<div class="row" id="div_row_factory_addr">
			<div class="col-sm-12 cls_div_factory_addr" id="div_factory_addr1" name="div_factory_addr1">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="factory_addr1" name="factory_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm cls_factory_addr" readonly>                    
	                </div>

	                <div class="col-sm-3">
	                    <input type="text" id="factory_brgy_cm1" name="factory_brgy_cm1" class="form-control input-sm cls_factory_addr" readonly>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" id="factory_state_prov1" name="factory_state_prov1" class="form-control input-sm cls_factory_addr" readonly>
	                </div>

	                <div class="col-sm-1">
	                    <input id="factory_zip_code1" name="factory_zip_code1" type="text" placeholder="Zip Code"
	                    class="form-control input-sm cls_factory_addr" readonly>                    
	                </div>

	                <div class="col-sm-2" style="display: none;">
						<input id="factory_region1" name="factory_region1" type="text" placeholder="Region" class="form-control input-sm cls_office_addr" data-label="fa_addrregion" maxlength="20" disabled>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" id="factory_country1" name="factory_country1" class="form-control input-sm cls_factory_addr" readonly>
	                </div>

	                <div class="col-sm-1">
		               <label class="radio-inline">
						      <input type="radio" name="factory_primary" value="1" disabled>
						      <!-- <button type="button" class="btn btn-default btn-xs remove_factory_addr"><span class="glyphicon glyphicon-trash"></span></button> -->
						      
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
					<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
					</label>
					<input type="hidden" id="wh_addr_count" name="wh_addr_count" value="1">
				</div>
			</div>
		</div>

		<div class="row" id="div_row_wh_addr">
			<div class="col-sm-12 cls_div_wh_addr" id="div_wh_addr1" name="div_wh_addr1">
				<div class="form-group">                
	                <div class="col-sm-3">
	                    <input id="ware_addr1" name="ware_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street"
	                    class="form-control input-sm cls_wh_addr" readonly>                    
	                </div>

	                <div class="col-sm-3">
	                    <input type="text" id="ware_brgy_cm1" name="ware_brgy_cm1" class="form-control input-sm cls_wh_addr" readonly>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" id="ware_state_prov1" name="ware_state_prov1" class="form-control input-sm cls_wh_addr" readonly>
	                </div>

	                <div class="col-sm-1">
	                    <input id="ware_zip_code1" name="ware_zip_code1" type="text" placeholder="Zip Code"
	                    class="form-control input-sm cls_wh_addr" readonly>                    
	                </div>

	                <div class="col-sm-2" style="display: none;">
						<input id="ware_region1" name="ware_region1" type="text" placeholder="Region" class="form-control input-sm cls_wh_addr" data-label="fa_addrregion" maxlength="20" disabled>
	                </div>

	                <div class="col-sm-2">
	                    <input type="text" id="ware_country1" name="ware_country1" class="form-control input-sm cls_wh_addr" readonly>
	                </div>

	                <div class="col-sm-1">
		               <label class="radio-inline">
						      <input type="radio" name="ware_primary" value="1" disabled>
						      <!-- <button type="button" class="btn btn-default btn-xs remove_wh_addr"><span class="glyphicon glyphicon-trash"></span></button> -->
						</label>
	                </div>
	            </div>
            </div>
		</div>

		<div class="row" id="div_row_tel_no">
		<br><br>
			<div class="col-sm-12">
				<!-- <div class="col-sm-6"> -->
					<div class="form-group">
						<label for="tel_no" class="col-sm-2 control-label">Tel No.</label>
						<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12" id="div_telno">
							<input type="hidden" id="telno_count" name="telno_count" value="1">
							<div class="form-inline cls_div_telinline" id="div_telinline1" name="div_telinline1">
								<input type="text" class="form-control input-sm cls_telno numeric" id="tel_ccode1" name="tel_ccode1" placeholder="Country Code" readonly>
								<input type="text" class="form-control input-sm cls_telno numeric" id="tel_acode1" name="tel_acode1" placeholder="Area Code" readonly>
								<input type="text" class="form-control input-sm cls_telno numeric" id="tel_no1" name="tel_no1" placeholder="Tel No" readonly>
								<input type="text" class="form-control input-sm cls_telno numeric" id="tel_elno1" name="tel_elno1" placeholder="Extension/Local Number" readonly>
							</div>
						</div>
					</div>
				<!-- </div> -->
			</div>
		</div>
		<div class="row" id="div_row_email">
			<br>
			<div class="col-sm-12">
				<!-- <div class="col-sm-6"> -->
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email</label>
						<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12" id="div_email">
							<input type="hidden" id="email_count" name="email_count" value="1">
							<div class="input-group col-sm-8">
								<input type="text" class="form-control input-sm cls_email" id="email1" name="email1" placeholder="Email" readonly>
							</div>
						</div>
					</div>
				<!-- </div> -->
				<div class="col-sm-12">
					<br>
				</div>
			</div>
		</div>

		<div class="row" id="div_row_fax">
			<div class="col-sm-12">
				<!-- <div class="col-sm-6"> -->
					<div class="form-group">
						<label for="fax_no" class="col-sm-2 control-label">Fax No.</label>
						<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12" id="div_faxno">
							<input type="hidden" id="faxno_count" name="faxno_count" value="1">
							<div class="form-inline cls_div_faxinline" id="div_faxinline1" name="div_faxinline1">
								<input type="text" class="form-control input-sm cls_faxno numeric" id="fax_ccode1" name="fax_ccode1" placeholder="Country Code" readonly>
								<input type="text" class="form-control input-sm cls_faxno numeric" id="fax_acode1" name="fax_acode1" placeholder="Area Code" readonly>
								<input type="text" class="form-control input-sm cls_faxno numeric" id="fax_no1" name="fax_no1" placeholder="Fax No." readonly>
								<input type="text" class="form-control input-sm cls_faxno numeric" id="fax_elno1" name="fax_elno1" placeholder="Extension/Local Number" readonly>
							</div>
						</div>
					</div>
				<!-- </div> -->
			</div>
		</div>
		<div class="row" id="div_row_mobile_no">
			<br>
			<div class="col-sm-12">
				<!-- <div class="col-sm-6"> -->
					<div class="form-group">
						<label for="mobile_no" class="col-sm-12 control-label">Mobile No</label>
						<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12" id="div_mobno">
							<input type="hidden" id="mobno_count" name="mobno_count" value="1">
							<div class="form-inline cls_div_mobnoinline" id="div_mobnoinline1" name="div_mobnoinline1">
									<input type="text" class="form-control input-sm cls_mobno numeric" id="mobile_ccode1" name="mobile_ccode1" placeholder="Country Code" readonly>
									<input type="hidden" class="form-control input-sm cls_mobno" id="mobile_acode1" value="" name="mobile_acode1" placeholder="Area Code" readonly>
									<input type="text" class="form-control input-sm cls_mobno numeric" id="mobile_no1" name="mobile_no1" placeholder="Mobile No" readonly>
									<!-- <input type="text" class="form-control input-sm cls_mobno numeric" id="mobile_elno1" name="mobile_elno1" placeholder="Extension/Local Number" readonly> -->
									<a class="cls_mobno" href="#" id="vi_contact_details_per_system" name="vcdpsv1" data-label="contact_person_label" style="margin-left: 40px;">* Contact Details per SM Vendor System</a>
							</div>
						</div>
					</div>
				<!-- </div> -->
				<div class="col-sm-12">
					<br>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="col-md-6 col-sm-12 no-padding" id="div_row_opd">
					<div class="form-group">
						<label for="" class="col-sm-7 control-label">Owners/Partners/Directors <small>(Max <input type="text" id="opd_max" name="opd_max" value="10" style="background:rgba(0,0,0,0);border:none; width: 13px;height: 15px" readonly>)</small></label>
						<input type="hidden" id="opd_count" name="opd_count" value="1">
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_opd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12">
							<table class="table table-bordered" id="tbl_opd">
								<thead>
									<tr class="info">
										<th>First Name</th>
										<th>Middle Name</th>
										<th>Last Name</th>
										<th>Position</th>
										<th>ASL</th>
									</tr>
								</thead>
								<tbody>
									<tr id="tr_opd1">
										<td><input type="text" id="opd_fname1" name="opd_fname1" placeholder="First Name" class="form-control input-sm cls_opd" readonly></td>
										<td><input type="text" id="opd_mname1" name="opd_mname1" placeholder="Middle Name" class="form-control input-sm cls_opd" readonly></td>
										<td><input type="text" id="opd_lname1" name="opd_lname1" placeholder="Last Name" class="form-control input-sm cls_opd" readonly></td>
										<td><input type="text" id="opd_pos1" name="opd_pos1" placeholder="Position" class="form-control input-sm cls_opd" readonly></td>
										<td align="center"><input type="checkbox" id="opd_auth1" name="opd_auth1" class="input-sm cls_opd" disabled></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-sm-12 no-padding" id="div_row_ar">
				<div class="form-group">
					<label for="" class="col-sm-7 control-label">Authorized Representatives <small>(Max <input type="text" id="authrep_max" name="authrep_max" value="5" style="background:rgba(0,0,0,0);border:none; width: 6px;height: 15px" readonly>)</small></label>
					<input type="hidden" id="authrep_count" name="authrep_count" value="1">
					<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_authrep"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

					<div class="col-sm-12">
						<table class="table table-bordered" id="tbl_authrep">
							<thead>
								<tr class="info">
									<th>First Name</th>
									<th>Middle Name</th>
									<th>Last Name</th>
									<th>Position</th>
									<th>ASL</th>
								</tr>
							</thead>
							<tbody>
								<tr id="tr_authrep1">
									<td><input type="text" id="authrep_fname1" name="authrep_fname1" placeholder="First Name" class="form-control input-sm cls_authrep" readonly></td>
									<td><input type="text" id="authrep_mname1" name="authrep_mname1" placeholder="Middle Name" class="form-control input-sm cls_authrep" readonly></td>
									<td><input type="text" id="authrep_lname1" name="authrep_lname1" placeholder="Last Name" class="form-control input-sm cls_authrep" readonly></td>
									<td><input type="text" id="authrep_pos1" name="authrep_pos1" placeholder="Position" class="form-control input-sm cls_authrep" readonly></td>
									<td align="center"><input type="checkbox" id="authrep_auth1" name="authrep_auth1" class="input-sm cls_authrep" disabled></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			</div>
		</div>

		<!-- hide daw -->
		<!-- <div class="row">
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
		</div> -->
		<?php //if($vendor_type == 3):?>
		<div class="row">
			<div class="col-sm-12">
				<div class="col-md-12 col-sm-12 no-padding">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label" name="dept" id="dept"></label>
						<div class="col-sm-12">
							<table class="table table-bordered" style="margin-bottom:0;" id="cat_sup">
							<input type="hidden" id="cat_sup_count" name="cat_sup_count" value="0">
								<thead style="width:50%">
									<tr class="info">
										<th>Department</th>
										<th>Sub Department</th>
									</tr>
								</thead>						
								<tbody>
									<tr id="tr_catsup1" class="cls_tr_cat">
											<td style="width:50%;">
											<input type="hidden" class="cls_cat" id="category_id1" name="category_id1" value="">
											<span id="category_name1"></span>
										</td>
											<td style="width:50%;">
											<input type="hidden" class="cls_cat" id="sub_category_name1" name="sub_category_name1" value="">
											<span id="sub_category_name1"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php if($vendor_code_02 != '' || ($registration_type == 4 && $vendor_code_02 != '')){ ?>
		
		<div class="row">
			<div class="col-sm-12">
				<br>
			</div>
		</div>
		
			<div class="row">
				<div class="col-sm-12">
					<div class="col-md-12 col-sm-12 no-padding">
						<div class="form-group">
						<label for="" class="col-sm-4 control-label" name="avc_dept" id="avc_dept"></label>
							<div class="col-sm-12">
								<table class="table table-bordered" style="margin-bottom:0;" id="avc_cat_sup">
								<input type="hidden" id="avc_cat_sup_count" name="avc_cat_sup_count" value="0">
									<thead>
										<tr class="info">
											<th>Department</th>
											<th>Sub Department</th>
										</tr>
									</thead>						
									<tbody>
										<tr id="tr_avc_catsup1" class="cls_tr_cat">
											<td style="width:50%;">
												<input type="hidden" class="cls_cat" id="avc_category_id1" name="avc_category_id1" value="">
												<span id="avc_category_name1"></span>
											</td>
											<td style="width:50%;">
												<input type="hidden" class="cls_cat" id="avc_sub_category_name1" name="avc_sub_category_name1" value="">
												<span id="avc_sub_category_name1"></span>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
		
		<div class="row">
			<div class="col-sm-12">
				<br>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="col-md-6 col-sm-12 no-padding">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label">Bank References</label>
						<input type="hidden" id="bankrep_count" name="bankrep_count" value="1">
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_bankrep"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12">
							<table class="table table-bordered" id="tbl_bankrep">
								<thead>
									<tr class="info">
										<th>Name</th>
										<th>Branch</th>
									</tr>
								</thead>
								<tbody>
									<tr id="tr_bankrep1">
										<td><input type="text" id="bankrep_name1" name="bankrep_name1" placeholder="Name" class="form-control input-sm cls_bankrep" readonly></td>
										<td><input type="text" id="bankrep_branch1" name="bankrep_branch1" placeholder="Branch" class="form-control input-sm cls_bankrep" readonly></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-sm-12 no-padding">
					<div class="form-group">
						<label for="" class="col-sm-6 control-label">Other Retail Customers/Clients</label>
						<input type="hidden" id="orcc_count" name="orcc_count" value="1">
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_orcc"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

						<div class="col-sm-12">
							<table class="table table-bordered" id="tbl_orcc">
								<thead>
									<tr class="info">
										<th>Company Name</th>
									</tr>
								</thead>
								<tbody>
									<tr id="tr_orcc1">
										<td><input type="text" id="orcc_compname1" name="orcc_compname1" placeholder="Company Name" class="form-control input-sm cls_orcc" readonly></td>
									</tr>
								</tbody>
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
					<input type="hidden" id="otherbusiness_count" name="otherbusiness_count" value="1">
					<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_otherbusiness"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

					<div class="col-sm-12">
						<table class="table table-bordered" id="tbl_otherbusiness">
							<thead>
								<tr class="info">
									<th>Company Name</th>
									<th>Products/Services Offered</th>
								</tr>
							</thead>
							<tbody>
								<tr id="tr_otherbusiness1">
									<td><input type="text" id="ob_compname1" name="ob_compname1" placeholder="Company Name" class="form-control input-sm cls_otherbusiness" readonly></td>
									<td><input type="text" id="ob_pso1" name="ob_pso1" placeholder="Products/Services Offered" class="form-control input-sm cls_otherbusiness" readonly></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="" class="col-sm-5 control-label">Disclosure of Relatives Working in SM or its Affiliates</label>
					<input type="hidden" id="affiliates_count" name="affiliates_count" value="1">
					<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_add_affiliates"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

					<div class="col-sm-12">
						<table class="table table-bordered" id="tbl_affiliates">
							<thead>
								<tr class="info">
									<th colspan="2">Employee Name</th>
									<th>Position</th>
									<th>Company Affiliated With</th>
									<th>Relationship</th>
								</tr>
							</thead>
							<tbody>
								<tr id="tr_affiliates1">
									<td><input type="text" id="affiliates_fname1" name="affiliates_fname1" placeholder="First Name" class="form-control input-sm cls_affiliates" readonly></td>
									<td><input type="text" id="affiliates_lname1" name="affiliates_lname1" placeholder="Last Name" class="form-control input-sm cls_affiliates" readonly></td>
									<td><input type="text" id="affiliates_pos1" name="affiliates_pos1" placeholder="Position" class="form-control input-sm cls_affiliates" readonly></td>
									<td><input type="text" id="affiliates_comp_afw1" name="affiliates_comp_afw1" placeholder="Company Affiliated With" class="form-control input-sm cls_affiliates" readonly></td>
									<td><input type="text" id="affiliates_rel1" name="affiliates_rel1" placeholder="Relationship" class="form-control input-sm cls_affiliates" readonly></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="row"> -->
			<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="form-group">
							<h3 class="panel-title">
								Primary Requirements <!-- (PDF/Jpeg format max size...) [3/6] -->
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
						<?php if( ! empty($waive_rsd_document_chk)): ?>
						
						<?php if($has_edit_waive_remark_permission):?>
							<?php foreach($waive_rsd_document_chk as $wv):?>
								<input type="hidden" id="rsd_waive_hidden_<?php echo $wv; ?>" name="rsd_waive_hidden_<?php echo $wv; ?>" value="<?php echo $wv; ?>">
							<?php endforeach; ?>
						<?php endif; ?>		
						
						<?php endif; ?>
						<table class="table table-bordered">
							<thead>
								<tr class="info">
									<th></th>
									<th>Document</th>
									<?php if($has_edit_waive_remark_permission): ?>
										<th>N/A</th>
									<?php endif; ?>
									<th>View</th>
									<th>Date Uploaded</th>
									<th>File Name</th>
									<th>Reviewed</th>
									<th>Date Reviewed</th>
									<?php 
										if (isset($validate)):
									?>
									<th>Verified</th>
									<th>Date Verified</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody id="rsd_body">
								<script id="tbl_rsd_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
									{{#rsd_table_template}}
										<tr>
											<!-- Added & Modified MSF 20200924 -->
											<td align="center"><input type="checkbox" id="rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled></td>
											<td>{{REQUIRED_DOCUMENT_NAME}}</td>		
											<?php if($has_full_waive_permission && empty($view_only) && $status == 10): ?>
												<td align="center"><input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}"></td>
											<?php elseif($has_edit_waive_remark_permission):?>
												<td align="center"><input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled></td>
											
											<?php endif; ?>											
											<td align="center"><button type="button" id="btn_rsd_preview{{REQUIRED_DOCUMENT_ID}}" name="btn_rsd_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="rsd_date_upload{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_upload{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="text" class="form-control input-sm" id="rsd_orig_name{{REQUIRED_DOCUMENT_ID}}" name="rsd_orig_name{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="checkbox" class="reviewed" id="rsd_document_review{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_review{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="rsd_date_reviewed{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_reviewed{{COUNT}}" value="" readonly></td>
											<?php 
												if (isset($validate)):
											?>
											<td align="center"><input type="checkbox" class="validated" id="rsd_document_validated{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_validated{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="rsd_date_validated{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_validated{{COUNT}}" value="" readonly></td>
											<?php endif; ?>
										</tr>
									{{/rsd_table_template}}
								</script>
							</tbody>
						</table>
	
						<?php if(( ! empty($remarks)) && ($has_full_waive_permission || $has_edit_waive_remark_permission) && (! empty($view_only) || $status != 10)): ?>
							<strong id="rsd_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="rsd_waive_remarks" id="rsd_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly maxlength="1000"><?php echo $rsd_remarks; ?></textarea>		
						<?php elseif($has_full_waive_permission && empty($view_only) && $status == 10):?>
							<strong id="rsd_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="rsd_waive_remarks" id="rsd_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;"  maxlength="1000"><?php echo $rsd_remarks; ?></textarea>		
						<?php elseif(isset($view_only) && $view_only && empty($rsd_remarks)):?>
							
						<?php else:?>
							<strong id="rsd_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="rsd_waive_remarks" id="rsd_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly  maxlength="1000"><?php echo ! empty($rsd_remarks) ? $rsd_remarks : ''; ?></textarea>		
						<?php endif; ?>
					
					</div>	
				</div>	
			</div>			
		<!-- </div> -->

		<div class="col-sm-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="form-group">
							<h3 class="panel-title">
								Additional Requirements <!-- (PDF/Jpeg format max size...) [2/5] -->
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
	                			<?php if ($view_only != 1 && !isset($validate)) : ?>
	                			<div class="pull-right col-sm-2">
	                				<button type="button" class="btn btn-default " id="btn_rf_visit" disabled>Request for Visit</button>
	                			</div>
	                		<?php endif; ?>
                			</h3>

						</div>
					</div>
					<div class="panel-body">
						<?php if( ! empty($waive_ad_document_chk)): ?>
						
						<?php if($has_edit_waive_remark_permission):?>
							<?php foreach($waive_ad_document_chk as $wv):?>
								<input type="hidden" id="ad_waive_hidden_<?php echo $wv; ?>" name="ad_waive_hidden_<?php echo $wv; ?>" value="<?php echo $wv; ?>">
							<?php endforeach; ?>
						<?php endif; ?>		
						
						<?php endif; ?>
						<table class="table table-bordered">
							<thead>
								<tr class="info">
									<th></th>
									<th>Document</th>
									<?php if($has_edit_waive_remark_permission): ?>
										<th>N/A</th>
									<?php endif; ?>
									<th>View</th>
									<th>Date Uploaded</th>
									<th>File Name</th>
									<th>Reviewed</th>
									<th>Date Reviewed</th>
									<?php 
										if (isset($validate)):
									?>
									<th>Verified</th>
									<th>Date Verified</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody id="ra_body"><!-- id has value of docment id from db for assigning value while name has a loop value (count) for post -->
								<script id="tbl_ra_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
									{{#ra_table_template}}
										<tr>
											<!-- Added & Modified MSF 20200924 -->
											<td align="center"><input type="checkbox" id="ra_document_chk{{REQUIRED_AGREEMENT_ID}}" name="ra_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled></td>
											<td>{{REQUIRED_AGREEMENT_NAME}}</td>
											
											<?php if($has_full_waive_permission && empty($view_only) && ($status == 10 )): // Remove status 10 to enabled ARD N/A|| $status == 194?>
												<td align="center"><input type="checkbox" id="waive_ad_document_chk{{REQUIRED_AGREEMENT_ID}}" name="waive_ad_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}"></td>
											<?php elseif($has_edit_waive_remark_permission):?>
												<td align="center"><input type="checkbox" id="waive_ad_document_chk{{REQUIRED_AGREEMENT_ID}}" name="waive_ad_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled></td>
											
											<?php endif; ?>		
											<td align="center"><button type="button" id="btn_ra_preview{{REQUIRED_AGREEMENT_ID}}" name="btn_ra_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ra_date_upload{{REQUIRED_AGREEMENT_ID}}" name="ra_date_upload{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="text" class="form-control input-sm" id="ra_orig_name{{REQUIRED_AGREEMENT_ID}}" name="ra_orig_name{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="checkbox" class="reviewed_additional" id="ra_document_review{{REQUIRED_AGREEMENT_ID}}" name="ra_document_review{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ra_date_reviewed{{REQUIRED_AGREEMENT_ID}}" name="ra_date_reviewed{{COUNT}}" value="" readonly></td>
											<?php 
												if (isset($validate)):
											?>
											<td align="center"><input type="checkbox" class="validated" id="ra_document_validated{{REQUIRED_AGREEMENT_ID}}" name="ra_document_validated{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ra_date_validated{{REQUIRED_AGREEMENT_ID}}" name="ra_date_validated{{COUNT}}" value="" readonly></td>
											<?php endif; ?>
										</tr>
									{{/ra_table_template}}
								</script>
							</tbody>
						</table>
						<?php if(( ! empty($remarks)) && ($has_full_waive_permission || $has_edit_waive_remark_permission) && (! empty($view_only) || $status != 10)): ?>
							<strong id="ad_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="ad_waive_remarks" id="ad_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly  maxlength="1000"><?php echo $ad_remarks; ?></textarea>		
						<?php elseif($has_full_waive_permission && empty($view_only) && ($status == 10))://status == 194?>
							<strong id="ad_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="ad_waive_remarks" id="ad_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;"  maxlength="1000"><?php echo $ad_remarks; ?></textarea>		
						<?php elseif(isset($view_only) && $view_only && empty($ad_remarks)):?>
							
						<?php else:?>
							<strong id="ad_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control limit-chars" name="ad_waive_remarks" id="ad_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly  maxlength="1000"><?php echo ! empty($ad_remarks) ? $ad_remarks : ''; ?></textarea>		
						<?php endif; ?>
					</div>
				</div>	
			</div>
			
		<div class="col-sm-12" id="ccn_area" name="ccn_area" style="display: none;">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<div class="form-group">
							<h3 class="panel-title">
								Change in Company Name Requirements
                			</h3>

						</div>
					</div>
					<div class="panel-body">
						<table class="table table-bordered">
							<thead>
								<tr class="info">
									<th></th>
									<th>Document</th>
									<th>View</th>
									<th>Date Uploaded</th>
									<th>File Name</th>
									<th>Reviewed</th>
									<th>Date Reviewed</th>
									<?php 
										if (isset($validate)):
									?>
									<th>Verified</th>
									<th>Date Verified</th>
									<?php endif; ?>
								</tr>
							</thead>
							<tbody id="ccn_body"><!-- id has value of docment id from db for assigning value while name has a loop value (count) for post -->
								<script id="tbl_ccn_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
									{{#ccn_table_template}}
										<tr>
											<!-- Added & Modified MSF 20200924 -->
											<td align="center"><input type="checkbox" id="ccn_document_chk{{REQUIRED_CCN_ID}}" name="ccn_document_chk{{COUNT}}" value="{{REQUIRED_CCN_ID}}" disabled></td>
											<td>{{REQUIRED_CCN_NAME}}</td>
											<td align="center"><button type="button" id="btn_ccn_preview{{REQUIRED_CCN_ID}}" name="btn_ccn_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_upload{{REQUIRED_CCN_ID}}" name="ccn_date_upload{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="text" class="form-control input-sm" id="ccn_orig_name{{REQUIRED_CCN_ID}}" name="ccn_orig_name{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="checkbox" class="reviewed" id="ccn_document_review{{REQUIRED_CCN_ID}}" name="ccn_document_review{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_reviewed{{REQUIRED_CCN_ID}}" name="ccn_date_reviewed{{COUNT}}" value="" readonly></td>
											<?php 
												if (isset($validate)):
											?>
											<td align="center"><input type="checkbox" class="validated" id="ccn_document_validated{{REQUIRED_CCN_ID}}" name="ccn_document_validated{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_validated{{REQUIRED_CCN_ID}}" name="ccn_date_validated{{COUNT}}" value="" readonly></td>
											<?php endif; ?>
										</tr>
									{{/ccn_table_template}}
								</script>
							</tbody>
						</table>
					</div>
				</div>	
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-sm-12 control-label">SM Internal View</label><br>
							<?php if($vendor_code_02 != ''){ ?>
								<div class="col-sm-6">
									<b><span name="terms" id="terms" style="margin-left: 240px;"></span></b>
								</div>
								<div class="col-sm-6">
									<b><span name="avc_terms" id="avc_terms" style="margin-left: 65px;"></span></b>
								</div>
								</br>
							<?php } ?>
						<label for="cbo_tp" class="col-sm-2 control-label">Terms of Payment</label>
						<div class="col-sm-3">
						
						<?php						
							// Patch
							//if(($registration_type == 4) && ($current_status != 19)){
							//	$termspayment = $s['avc_termspayment'];
							//}						
						?>

						<?php
							$tmps = array();		
							foreach ($payment_terms as $key => $value) {
								$tmps[$key] = $value;
							}				
							asort($tmps);
							
							echo form_dropdown('cbo_tp', $tmps,$termspayment, ' id="cbo_tp" class="col-sm-3 form-control" '.$dsb.'');
						?>
						</div>
						<?php 
							if($vendor_code_02 != '' && $registration_type != 5){
								echo('
								<div class="col-sm-1">
									&nbsp;
								</div>
								
								<div class="col-sm-3">
								');
							$tmps = array();
							foreach ($payment_terms as $key => $value) {
								$tmps[$key] = $value;
							}				
							asort($tmps);
                            
							echo form_dropdown('cbo_tp', $tmps,$avc_termspayment, ' id="cbo_tp" class="col-sm-3 form-control" '.$dsb.'');
							echo('</div>');
							}
						?>
						<?php
							if(isset($invite_id)){
								echo '<div class="col-sm-2"><button type="button" class="btn btn-primary " onclick="save_temp('.$invite_id.','.$user_id.')"  '.$dsb.'>Update Payment Terms</button></div>';
							}
						?>
						
					</div>
				</div>
			</div>
			
			<!-- Added MSF 20191125 (NA) -->
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<br>
						<label for="txt_approve_items" class="col-sm-2 control-label"><span class="pull-right">Approved Items / Project</span></label>
						<?php						
							// Patch
							//if(($registration_type == 4) && ($current_status != 19)){
							//	$original_file_name = $avc_original_file_name;
							//}						
						?>
						
						<div class="col-sm-3">
							<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $original_file_name; ?>">
							<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $file_path; ?>">
						</div>
						<?php if($file_path != ''){ ?>
							<div class="col-sm-1">
								<button class="btn btn-default" type="button" id="btn_invite_view" name="btn_invite_view" value="<?php echo $file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
							</div>
						<?php }else{ ?>
							<div class="col-sm-1">
								&nbsp;
							</div>
						<?php }?>
						
						
						<?php
						if($vendor_code_02 != '' && $registration_type != 5){
							echo ('<div class="col-sm-3">
								<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "'.$avc_original_file_name.'">
								<input id="txt_file_path" name="txt_file_path" type="hidden" value="'.$avc_file_path.'">
							</div>');
						?>
						<?php if($file_path != ''): ?>
							<div class="col-sm-1">
								<button class="btn btn-default" type="button" id="btn_avc_invite_view" name="btn_avc_invite_view" value="<?php echo $avc_file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
							</div>
						<?php endif; } ?>
						
					</div>
				</div>
			</div>
			
			<?php
			if(count($ad_approved_items) > 0){
				for($a=0; $a<count($ad_approved_items); $a++){
			?>
					<!-- Added MSF 20191125 (NA) -->
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<br>
								<label for="txt_approve_items" class="col-sm-2 control-label"><span class="pull-right">Add Department - Approved Items / Project</span></label>
								<?php if($ad_approved_items[$a]->VENDOR_TYPE == 1){?>
								<div class="col-sm-3">
									<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $ad_approved_items[$a]->ORIGINAL_FILE_NAME; ?>">
									<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $ad_approved_items[$a]->FILE_PATH; ?>">
								</div>
								<?php if($ad_approved_items[$a]->FILE_PATH != ''){ ?>
									<div class="col-sm-1">
										<button class="btn btn-default" type="button" id="btn_invite_view<?php echo $a; ?>" name="btn_invite_view<?php echo $a; ?>" value="<?php echo $ad_approved_items[$a]->FILE_PATH; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
									</div>
								<?php } ?>
								<?php }else{ ?>		
									<div class="col-sm-3"></div>
									<div class="col-sm-1">&nbsp;</div>
									<div class="col-sm-3">
										<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $ad_approved_items[$a]->ORIGINAL_FILE_NAME; ?>">
										<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $ad_approved_items[$a]->FILE_PATH; ?>">
									</div>
									<?php if($ad_approved_items[$a]->FILE_PATH != ''){ ?>
									<div class="col-sm-1">
										<button class="btn btn-default" type="button" id="btn_invite_view<?php echo $a; ?>" name="btn_invite_view<?php echo $a; ?>" value="<?php echo $ad_approved_items[$a]->FILE_PATH; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
									</div>
								<?php 
								  } 
								} 
								?>
								
								
							</div>
						</div>
					</div>
					<script>
						var picture = $('#image_preview');
						$('#btn_invite_view'+<?php echo $a; ?>).on('click', function(){
							//console.log($(this).val());
							$('.close').alert('close'); // close alert if it has
							var url = BASE_URL.replace('index.php/','') + $(this).val();
							$('#myModal').modal('show');

							$('#myModal span').hide();
							$('.alert > span').show(); // dont include to hide these span
							$('#myModal .document_preview').show();
							$('#imagepreview').attr('src', '');
							if ($(this).val() != '')
							{
								$('.modal-dialog').addClass('modal-lg');
								var filext = $(this).val().split('.').pop();
								// setting height of iframe according to window size
								var set_height  = '';
								var w_h         = '';
								var t_h         = '';

								if (filext.toLowerCase() == 'pdf')
								{
									
									var parent = $('#frame');
									var newElement = "<embed src="+url+" id='pdf_preview' style='width: 100%; height: 100%; display: none;'>";
									
									$('#pdf_preview').remove();
									parent.append(newElement);
									
									$('#pdf_preview').removeClass('zoom_in');
									$('#pdf_preview').css('display', 'inline')
									w_h = $(window).height() * 0.75;
									t_h = $(this).height() * 0.75;
									$('#zoom_image').hide();
									$('#zoom_out_image').hide();
									$('#fit_to_screen').hide();
									$('#btn_printimg').hide();
									$('#btn_download').hide();
								}
								else
								{
									$('#pdf_preview').css('display', 'none');
									picture.attr('src', url);
									picture.removeClass('zoom_in');
									picture.css('display', 'inline');

									$('#zoom_image').show();
									$('#zoom_out_image').show();
									$('#fit_to_screen').show();
									$('#btn_download').show();
									$('#btn_printimg').show();

									w_h = $(window).height()/2;
									t_h = $(this).height()/2;
								}
								
								$('embed').height(w_h);
								$(window).resize(function(){
									$('embed').height(t_h);
								});
							}
							else
							{
								$('#imagepreview').attr('src', '');
							}
							var view_only = $('#view_only').val();
							
							//jay
							var status_id = $("#status_id").val();//end
						
						});
					</script>
			<?php
					}
				}
			?>

			<!-- Start Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
							<span class="submit" style="display:none;">
								<center><h4 class="modal-title" id="myModalLabel">Submit</h4></center>
							</span>
							<span class="incomplete" style="display:none;">
							<h4 class="modal-title" id="myModalLabel">Incomplete Reason</h4>
							</span>
							<span class="req_visit" style="display:none;">
								<h4 class="modal-title" id="myModalLabel">Request Visit</h4>
							</span>
							<span class="document_preview" style="display:none;">
								<h4 class="modal-title" id="myModalLabel">Preview</h4>
								<!-- Updated MSF - 20191105 (IJR-10612) -->
								<!--
									<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
									<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
								-->
								<button type="button" id="zoom_image" >Zoom In</button>
								<button type="button" id="zoom_out_image" >Zoom Out</button>
								<button type="button" id="fit_to_screen" >Fit To Screen</button>
								<?php if($position_id == 4 || $position_id == 5 || $position_id == 6): ?>
									<!-- Added MSF - 20191105 (IJR-10612) -->
									<button type="button" id="btn_download" onclick="downloadImg()">Download</button>
									<button type="button" id="btn_printimg" onclick="printImg()">Print</button>
								<?php endif; ?>
							</span>
							
							<span class="vi_approval_history" style="display:none;">
								<h4 class="modal-title" id="myModalLabel">Approval History</h4>
							</span>
							
							<span class="vi_revision_history" style="display:none;">
								<h4 class="modal-title" id="myModalLabel">Revision History</h4>
							</span>
							
							<span class ="vi_contact_details_per_system" style="display:none;">
								<h4 class="modal-title" id="myModalLabel">Contact Person Per SM Vendor System</h4>
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
									<p>Schedule: Monday to Friday from 1:00 PM to 5:00 PM</p>
								</span>

								<span class="incomplete" style="display:none;">
									<div class="col-sm-offset-11">
										<input type="hidden" id="increason_count" name="increason_count" value="1">
										<button type="button" class="btn btn-default " id="btn_add_increason"><i class="glyphicon glyphicon-plus" aria-hidden="true"></i></button>
									</div>
									<div class="col-sm-6">
										<label for="txt_from">Form/Document</label>
									</div>
									<div class="col-sm-6">
										<label for="txt_to">Reason</label>
									</div>
									<div id="div_increason">
										<div class="cls_div_ir" id="div_ir1" name="div_ir1">
											<div class="form-group col-sm-6 pull-left">
												<div>											
													<select class="form-control cls_ir field-required" name="cbo_da1" id="cbo_da1" onchange="get_inc_reason(1)">
													  <option value="" disabled selected>-- Select --</option>
													</select>
												</div>
											</div>
											<div class="form-group col-sm-6 pull-right">
												<div class="input-group">
													<select class="form-control cls_ir field-required validate_dupli" name="cbo_inc_reason1" id="cbo_inc_reason1">
													  <option value="" disabled selected>-- Select --</option>
													</select>
													<i class="glyphicon glyphicon-trash input-group-addon remove_ir"></i>
												</div>
											</div>
											<div class="col-sm-12"></div>
										</div>
									</div>
									<div class="form-group col-sm-12">
										<label for="txt_to">Others</label>
										<textarea class="form-control limit-chars" id="rv_incomplete" name="rv_incomplete" placeholder="Enter Reason" rows="10"  maxlength="1000"></textarea>
									</div>
								</span>

								<span class="req_visit" style="display:none;">
									<div class="form-group col-sm-6">
										<label for="txt_from">From</label>
										<div><input type="date" max="9999-12-31" style="width:250px;" class="form-control" id="rv_txt_from" name="rv_txt_from" placeholder="From">
										</div>
									</div>
									<div class="form-group col-sm-6">
										<label for="txt_to">To</label>
										<div>
											<input type="date" max="9999-12-31" style="width:250px; display: inline-block;" class="form-control" id="rv_txt_to" name="rv_txt_to" placeholder="To">&nbsp;&nbsp;<input type="checkbox" name="rv_checkbox" id="rv_checkbox" style="display: inline-block;">N/A?
										</div>
									</div>
								</span>
								<span class="document_preview" style="display:none;">
									<!-- <img src="" id="imagepreview" style="width: 400px; height: 264px;" > -->
									<!-- Updated MSF - 20191105 (IJR-10612) -->
									<!-- <iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe> -->
									<div id='content' style="max-width: 1200px; width: 100%;">
										<div class='frame' id='frame' style="border: 1px solid #ccc; padding: 5px;">
											<img id='image_preview' src='' style="display: none">
											<embed src="" id="pdf_preview" style="width: 100%; height: 100%; display: none;" >
										</div>
									</div>
								</span>
								<span class="vi_approval_history" style="display:none;">
									<div class="panel panel-primary">
										<div class="panel-heading">
										<h3 class="panel-title">Approval History</h3>
										</div>
										<table id="tbl_history" class="table table-bordered">
											<thead>
												<tr class="info">
													<th>Member</th>
													<th>Action</th>
													<th>Date</th>
													<th>Note</th>
												</tr>
											</thead>
											<tbody id="tbl_history_body">
												<script id="history_template" type="text/template">
													{{#table_history}}
														<tr>
															<td>{{USER_FIRST_NAME}} {{USER_LAST_NAME}} ({{POSITION_NAME}})</td>
															<td>{{STATUS_NAME}}</td>
															<td>{{DATE_UPDATED}}</td>
															<td>{{{APPROVER_REMARKS}}}</td><!-- Jay{{{ Triple curly braces means output Text to HTML}}}-->
														</tr>
													{{/table_history}}
												</script>
											</tbody>
										</table>
										
									</div>
								</span>
								<span class="vi_contact_details_per_system" style="display:none;">
									<div class="panel panel-primary" style="overflow: scroll;">
										<table id="smvs_history" class="table table-bordered">
											<thead>
												<tr class="info">
													<th style="text-align: center; vertical-align: middle;">SM System</th>
													<th style="text-align: center; vertical-align: middle;">Trade Vendor Type</th>
													<th style="text-align: center; vertical-align: middle;">First Name</th>
													<th style="text-align: center; vertical-align: middle;">Middle Name</th>
													<th style="text-align: center; vertical-align: middle;">Last Name</th>
													<th style="text-align: center; vertical-align: middle;">Position</th>
													<th style="text-align: center; vertical-align: middle;">Email</th>
													<th style="text-align: center; vertical-align: middle;">Mobile No</th>
												</tr>
											</thead>
											<tbody id="smvs_history_body">
												
												<script id="smvs_history_template" type="text/template">
													{{#smvs_table}}
														<tr>
															<td>{{{DESCRIPTION}}}</td>
															<td>{{{TRADE_VENDOR_TYPE}}}</td>
															<td>{{{FIRST_NAME}}}</td>
															<td>{{{MIDDLE_NAME}}}</td>
															<td>{{{LAST_NAME}}}</td>
															<td>{{{POSITION}}}</td>
															<td>{{{EMAIL}}}</td>
															<td>{{{MOBILE_NO}}}</td>
														</tr>
													{{/smvs_table}}
												</script>
											</tbody>
										</table>
										
									</div>
								</span>
								
								<span class="vi_revision_history" style="display:none;">
									<div class="panel panel-primary">
										<div class="panel-heading">
										<h3 class="panel-title">Revision History</h3>
										</div>
										<table id="tbl_history" class="table table-bordered">
											<thead>
												<tr class="info">
													<th>DATE</th>
													<th>USERNAME</th>
													<th>FIELDS CHANGED</th>
													<th>FROM</th>
													<th>TO</th>
												</tr>
											</thead>
											<tbody id="rev_tbl_history_body">
												<script id="rev_history_template" type="text/template">
													{{#rev_table_history}}
														<tr>
															<td>{{MODIFIED_DATE}}</td>
															<td>{{USER_FIRST_NAME}} {{USER_LAST_NAME}}</td>
															<td>{{{MODIFIED_FIELD}}}</td>
															<td>{{{VAR_FROM}}}</td>
															<td>{{{VAR_TO}}}</td>
														</tr>
													{{/rev_table_history}}
												</script>
											</tbody>
										</table>
										
									</div>
								</span>
							</div>
						</div>
						<div class="modal-footer">
							<span class="submit" style="display:none;">
								<center><button type="button" class="btn btn-primary">OK</button></center>
							</span>
							<span class="incomplete" style="display:none;">
								<button type="button" class="btn btn-default" id="btn_incomplete_sad">Save as draft</button>
								<button type="button" class="btn btn-primary" id="btn_incomplete_reg_view">Ok</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
							</span>

							<span class="req_visit" style="display:none;">
								<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								<button type="button" class="btn btn-primary" id="btn_req_visit">Ok</button>
							</span>
							<span class="document_preview" style="display:none;">
								<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
							</span>
							<span class="vi_approval_history" style="display:none;">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</span>
							<span class="vi_revision_history" style="display:none;">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<!-- END Modal -->
		</form>
	</div>

</div>
</div>

<script type="text/javascript">



	var rlf = 0;

	$.ajax({
		url: "<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js');?>",
		dataType: 'script',
		async: false
	});
	
	<?php if(isset($validate)):?>
		
		if ($('.validated:checked').length == $('.validated').length){
		    $('#btn_submit_valid').prop('disabled',false); //false prop
		}else{
            $('#btn_submit_valid').prop('disabled', true); //true
		}
	<?php endif; ?>

	//Edit Remarks
	var vrd_orig_val = $('#vrd_note').val();
	var hats_orig_val = $('#note').val();
	$(document).on('input', '#note', function(){
		//console.log('OrigH: ' + hats_orig_val);
		//console.log('TestH: ' + $('#note').val());
		
		if(hats_orig_val != $('#note').val()){
			$("#edit_note_hts").removeAttr('disabled');
		}else{
			$("#edit_note_hts").attr('disabled','disabled');
		}
	});
	$(document).on('input', '#vrd_note', function(){
		//console.log('OrigV: ' + vrd_orig_val);
		//console.log('TestV: ' + $('#vrd_note').val());
		if(vrd_orig_val != $('#vrd_note').val()){
			$("#edit_note_vrd").removeAttr('disabled');
		}else{
			$("#edit_note_vrd").attr('disabled','disabled');
		}
	});
	
	//1 = HAT
	//2 = VRD
	$(document).on('click', '#edit_note_hts', function(e){
		e.preventDefault();
		if (e.handled !== true) { //Checking for the event whether it has occurred or not.
			//$('#txt_vendorname').parent('div').removeClass('has-error');
			var note = $('#note').val().replace(/'/g, "\\'").replace(/"/g, '&quot;');
            var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_remark(1,\'' + escape(note) + '\')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
            var type = 'info';
            notify(span_message, type, true);
		}
	});
	
	$(document).on('click', '#edit_note_vrd', function(e){
		e.preventDefault();
		if (e.handled !== true) { //Checking for the event whether it has occurred or not.
			//$('#txt_vendorname').parent('div').removeClass('has-error');
			var note = $('#vrd_note').val().replace(/'/g, "\\'").replace(/"/g, '&quot;');
            var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_remark(2,\''+ escape(note) + '\')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert">No</button>';
            var type = 'info';
            notify(span_message, type, true);
		}
	});
	
	$(document).on('click', '#delete_vendor', function(e){
		e.preventDefault();
		if (e.handled !== true) { 
            var span_message = 'Are you sure you want to delete this vendor? <button type="button" class="btn btn-success" onclick="delete_vendor()" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
            var type = 'info';
            notify(span_message, type, true);
		}
	});
	
	function delete_vendor(){
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/registrationreview/delete_vendor/";
		// var d ={ 'vid': $('#invite_id').val() };		
		var d ={ 'vid': $('#invite_id').val(),'delReason':$('#delReason').val() };
		//console.log(d);
		var base_url = window.location.origin + '/' + window.location.pathname.split ('/') [1] + '/';
	    
		if($('#delReason').val().length == 0){
			modal_notify('delModal','Reason is required before deleting','modal_danger');
			return false;
		}
		$.ajax({
				type:'POST',
				data:{data:  JSON.stringify(d)},
				url: url,
				success: function(result){
					//console.log(result);
					if(result == 1){
						window.location.href = base_url;
					}else{
						alert('here1');
					}
				},
				error: function(result)
				{
					alert(result + 'e');	
					return;		
				}
			}).fail(function(result){
        
					alert(result + 'f');
					return;
			});
	}
	
	function save_remark(remark_type, note){		
		note = unescape(note);
		// 1 = HATS
		// 2 = VRD
		if(remark_type == 1 || remark_type == 2){
			var ajax_type = 'POST';
			var url = BASE_URL + "vendor/registrationreview/save_remarks/";
			var d ={'vid': $('#invite_id').val(), 'remark_type' : remark_type, 'note' : note};
			$.ajax({
				type:'POST',
				data:{data:  JSON.stringify(d)},
				url: url,
				success: function(result){
					var j = result;
					if(j == true)
					{
						//=== NOTIFICATION ===
						var span_message = 'Remark has been successfully saved!';
						var type = 'success';
						notify(span_message, type);		
						$("#edit_note_vrd").attr('disabled','disabled');
						$("#edit_note_hts").attr('disabled','disabled');	
						vrd_orig_val = $('#vrd_note').val();
						hats_orig_val = $('#note').val();
					}		
					return;	
				},
				error: function(result)
				{
					alert(result + 'e');	
					return;		
				}
			}).fail(function(result){

					alert(result + 'f');
					return;
			});
				
		}
	}
	
	var rsd_tbl_doc_template = $('#tbl_rsd_template').html();	
	var ra_tbl_agree_template = $('#tbl_ra_template').html();
	var ccn_tbl_agree_template = $('#tbl_ccn_template').html();

	function get_list_docs(ownership = '', trade_vendor_type = '',category_id = '', vendor_type = '', registration_type = '')
	{
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/get_list_docs";
        var post_params = "ownership="+ownership+"&trade_vendor_type="+trade_vendor_type+"&cat_id="+category_id+"&invite_id="+$("#invite_id").val()+"&vendor_type="+vendor_type+"&registration_type="+registration_type;
		//console.log(post_params);
        var success_function = function(responseText)
        {

        	//console.log(responseText)
            var tbl_data = $.parseJSON(responseText);

			//console.log((tbl_data.ra).length);

            let l = '';
            if(tbl_data.ra != null){
            	 l = tbl_data.ra;
            }
            
            var counter = 0;
            var counter2 = 0;
            var counter3 = 0;

		   if(l != "-1" && l != ''){

            	(tbl_data.rsd).map(function(row_obj) {
					counter++
					row_obj.COUNT = counter;
				});
            	(tbl_data.ra).map(function(row_obj) {
					counter2++
					row_obj.COUNT = counter2;
				});
            	(tbl_data.ccn).map(function(row_obj) {
					counter3++
					row_obj.COUNT = counter3;
				});

				var DATA = {
					rsd_table_template: tbl_data.rsd,
					cbo_rsd_template: tbl_data.rsd,
					ra_table_template: tbl_data.ra,
					cbo_ra_template: tbl_data.ra,
				    ccn_table_template: tbl_data.ccn,
				    cbo_ccn_template: tbl_data.ccn 
            	}
            }else{
				(tbl_data.rsd).map(function(row_obj) {
				counter++
				row_obj.COUNT = counter;
				});
				var DATA = {
				rsd_table_template: tbl_data.rsd,
				cbo_rsd_template: tbl_data.rsd,      	
				}
				rlf = -1;
            }


/*            
            (tbl_data.rsd).map(function(row_obj) {
					row_obj.COUNT = counter;
					counter++
				});
            (tbl_data.ra).map(function(row_obj) {
					row_obj.COUNT = counter2;
					counter2++
				});
            
            var DATA = {
                rsd_table_template: tbl_data.rsd,
                ra_table_template: tbl_data.ra,
            }*/

            $('#rsd_body').html(Mustache.render(rsd_tbl_doc_template, DATA));
            $('#ra_body').html(Mustache.render(ra_tbl_agree_template, DATA));
            $('#ccn_body').html(Mustache.render(ccn_tbl_agree_template, DATA));
            

           // $('#tbl_pag').html(responseText);
           // load if has existing data
           // load_vendor_data();
		   loadingScreen('off');
        };

        return ajax_request(ajax_type, url, post_params, success_function);
	}
	
	
	//$main_container.on('click', '#vi_previous_company_name', function(){
    //    let this_el = $(this);
    //    
    //    var action_path = BASE_URL + $(this).data('action-path');
    //    $main_container.html('').load(cache.set('refresh_path', action_path));
    //    create_breadcrumbs(this_el);
    //});
	//
	//$main_container.on('click', '#vi_new_company_name', function(){
    //    let this_el = $(this);
    //    
    //    var action_path = BASE_URL + $(this).data('action-path');
    //    $main_container.html('').load(cache.set('refresh_path', action_path));
    //    
    //    create_breadcrumbs(this_el);
    //});

	function load_vendor_data()
	{
		loadingScreen('on');
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registrationreview/get_vendor_data";
        var post_params = "vendor_id=" + $('#vendor_id').val();

        var success_function = function(responseText)
        {
            //console.log(responseText);
			//return;
            var brand_template 			= $('#div_brandid1');
            var offce_template 			= $('#div_office_addr1');
            var factory_addr_template 	= $('#div_factory_addr1');
			var wh_addr_template 		= $('#div_wh_addr1');
			var telno_template 			= $('#tel_no1').closest('div');
			var email_template 			= $('#email1').closest('div');
			var faxno_template 			= $('#fax_no1').closest('div');
			var mobno_template 			= $('#mobile_no1').closest('div');
			var opd_template 			= $('#tr_opd1');
			var authrep_template 		= $('#tr_authrep1');
			var bankrep_template 		= $('#tr_bankrep1');
			var orcc_template 			= $('#tr_orcc1');
			var otherbusiness_template 	= $('#tr_otherbusiness1');
			var affiliates_template 	= $('#tr_affiliates1');
			var cat_template 			= $('#tr_catsup1').clone();
			var avc_cat_template 			= $('#tr_avc_catsup1').clone();
			// Added MSF - 20191118 (IJR-10618)
			//var sub_cat_template 		= $('#tr_sub_catsup1').clone();
			
			//var avc_cat_template 			= $('#tr_avc_catsup1').clone();
			//var avc_sub_cat_template 		= $('#tr_avc_sub_catsup1').clone();
			var ir_template 			= $('#div_ir1');
			// Added MSF - 20191118 (IJR-10618)
			$('#cat_sup tbody').html(''); // reset
			// Added MSF - 20191118 (IJR-10618)
			$('#avc_cat_sup tbody').html(''); // reset
			
			//$('#avc_cat_sup tbody').html(''); // reset
			//$('#avc_sub_cat_sup tbody').html(''); // reset

            if (responseText != 0)
            {
            	var data = $.parseJSON(responseText);
				//console.log(data);
            	// load vendor data
            	if (data.count_vreg > 0)
            	{
            		$('#note').val(data.rs_note);
            		hats_orig_val = $('#note').val();
            		$('#vrd_note').val(data.rs_vrdnote);
            		vrd_orig_val = $('#vrd_note').val();
					
            		$('#vendor_name').html(data.rs_vreg[0].VENDOR_NAME)
	            	$('#cbo_yr_business').val(data.rs_vreg[0].YEAR_IN_BUSINESS);
					$('input:radio[name=ownership][value=' + data.rs_vreg[0].OWNERSHIP_TYPE + ']').prop('checked', true);
					
					
					var vt = data.rs_vreg[0].VENDOR_TYPE;
					
					$('input:radio[name=vendor_type][value=' + vt + ']').prop('checked', true);
					
					var trade_vendor_type;
						trade_vendor_type = data.rs_vreg[0].TRADE_VENDOR_TYPE;
					
					if(data.rs_vreg[0].VENDOR_CODE_02 != '' && data.rs_vreg[0].VENDOR_CODE_02 != null){
						if(data.rs_registration_type == 4 || data.rs_vreg[0].PREV_REGISTRATION_TYPE == 4){
							if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
								$('#dept').text('Outright ['+data.rs_vreg[0].VENDOR_CODE+']');
								$('#avc_dept').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE_02+']');
							}else{
								$('#dept').text('Outright ['+data.rs_vreg[0].VENDOR_CODE_02+']');
								$('#avc_dept').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE+']');
							}
						}else{						
							if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
								$('#dept').text('Outright ['+data.rs_vreg[0].VENDOR_CODE_02+']');
								$('#avc_dept').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE+']');
							}else{
								$('#dept').text('Outright ['+data.rs_vreg[0].VENDOR_CODE+']');
								$('#avc_dept').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE_02+']');
							}
						}
					}
					
					if(data.rs_registration_type == 4){
						if(data.rs_vreg[0].STATUS_ID == 19){
							$('input:radio[name=trade_vendor_type][value=' + trade_vendor_type + ']').prop('checked', true);
							$('input:radio[name=trade_vendor_type][value=1]').css('display', "none");
							$('input:radio[name=trade_vendor_type][value=2]').css('display', "none");
							$('input:checkbox[name=chk_trade_vendor_type][value=1]').css('display', "inherit");
							$('input:checkbox[name=chk_trade_vendor_type][value=2]').css('display', "inherit");
							$('input:checkbox[name=chk_trade_vendor_type][value=1]').prop('checked', true);
							$('input:checkbox[name=chk_trade_vendor_type][value=2]').prop('checked', true);
						}else{
							$('input:radio[name=trade_vendor_type][value=' + trade_vendor_type + ']').prop('checked', true);
						}
					}else if(data.rs_vreg[0].VENDOR_CODE_02 != '' && data.rs_vreg[0].VENDOR_CODE_02 != null){
							$('input:radio[name=trade_vendor_type][value=' + trade_vendor_type + ']').prop('checked', true);
							$('input:radio[name=trade_vendor_type][value=1]').css('display', "none");
							$('input:radio[name=trade_vendor_type][value=2]').css('display', "none");
							$('input:checkbox[name=chk_trade_vendor_type][value=1]').css('display', "inherit");
							$('input:checkbox[name=chk_trade_vendor_type][value=2]').css('display', "inherit");
							$('input:checkbox[name=chk_trade_vendor_type][value=1]').prop('checked', true);
							$('input:checkbox[name=chk_trade_vendor_type][value=2]').prop('checked', true);
							//$('#outright_code').text(data.rs_vreg[0].VENDOR_CODE);
							//$('#sc_code').text(data.rs_vreg[0].VENDOR_CODE_02);
					}else{
						$('input:radio[name=trade_vendor_type][value=' + trade_vendor_type + ']').prop('checked', true);
					}

					if(data.rs_vreg[0].VENDOR_CODE_02 != null){
						if(data.rs_registration_type == 4 || data.rs_vreg[0].PREV_REGISTRATION_TYPE == 4){
							if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 1){
								$('#outright_code').text(data.rs_vreg[0].VENDOR_CODE_02);
								$('#sc_code').text(data.rs_vreg[0].VENDOR_CODE);
								
								$('#terms').text('Outright ['+data.rs_vreg[0].VENDOR_CODE_02+']');
								$('#avc_terms').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE+']');
							}else{
								$('#outright_code').text(data.rs_vreg[0].VENDOR_CODE);
								$('#sc_code').text(data.rs_vreg[0].VENDOR_CODE_02);
								
								$('#terms').text('Outright ['+data.rs_vreg[0].VENDOR_CODE+']');
								$('#avc_terms').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE_02+']');
							}
						}else{
							if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 1){
								$('#outright_code').text(data.rs_vreg[0].VENDOR_CODE);
								$('#sc_code').text(data.rs_vreg[0].VENDOR_CODE_02);
								
								$('#terms').text('Outright ['+data.rs_vreg[0].VENDOR_CODE+']');
								$('#avc_terms').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE_02+']');
							}else{
								$('#outright_code').text(data.rs_vreg[0].VENDOR_CODE_02);
								$('#sc_code').text(data.rs_vreg[0].VENDOR_CODE);
								
								$('#terms').text('Outright ['+data.rs_vreg[0].VENDOR_CODE_02+']');
								$('#avc_terms').text('Consignor ['+data.rs_vreg[0].VENDOR_CODE+']');
							}
						}
					}
					
					//Added & Modified MSF 20200924
					
					$('input:radio[name=no_of_employee][value=' + data.rs_vreg[0].EMPLOYEE + ']').prop('checked', true);
					$('input:radio[name=business_asset][value=' + data.rs_vreg[0].BUSINESS_ASSET + ']').prop('checked', true);
					$('#registration_type').val(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE);
					
					if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 5){
						var anchor = document.getElementById('vi_previous_company_name');
						anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].VENDOR_CODE;  
						anchor.setAttribute("data-action-path",'vendor/registration/display_vendor_details/'+data.rs_vendor_invite_dtl[0].VENDOR_ID);
						
						document.getElementById('test').style.display = "inherit";
						document.getElementById('vendor_invite_type').innerHTML = "Change in Company Name";
						document.getElementById('old_vendor_code').innerHTML = data.xx_vendor_code;
						document.getElementById('old_vendor_name').innerHTML = data.xx_vendor_name;
						
						document.getElementById('ccn_area').style.display = "inherit";
					}else{
						//vi_new_company_name
						if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 1){
							document.getElementById('vendor_invite_type').innerHTML = "New Vendor";
						}else if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 2){
							document.getElementById('vendor_invite_type').innerHTML = "Migration";
						}else if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 3){
							document.getElementById('vendor_invite_type').innerHTML = "Update Vendor Information";
						}else{
							document.getElementById('vendor_invite_type').innerHTML = "Add Vendor Code";
						}
						if(data.rs_vendor_invite_dtl[0].F_VENDOR_ID !== null){
							var anchor = document.getElementById('vi_new_company_name');
							console.log(data.rs_vendor_invite_dtl[0].F_VENDOR_CODE);
							if(data.rs_vendor_invite_dtl[0].F_VENDOR_CODE !== null){
								anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].F_VENDOR_CODE;  
							}else{
								anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].F_VENDOR_ID;  
							}
							anchor.setAttribute("data-action-path",'vendor/registration/display_vendor_details/'+data.rs_vendor_invite_dtl[0].F_VENDOR_ID);
						}
					}
					
					$('#tax_idno').val(data.rs_vreg[0].TAX_ID_NO);
					$('input:radio[name=tax_class][value=' + data.rs_vreg[0].TAX_CLASSIFICATION + ']').prop('checked', true);
					
					// if (data.rs_vreg[0].VENDOR_TYPE == 1)
					// 	$('input:radio[name=trade_vendor_type]').prop('disabled', false);

					if (data.rs_vreg[0].NOB_DISTRIBUTOR == 1)
						$('input:checkbox[name=nob_license_dist]').prop('checked', true);

					if (data.rs_vreg[0].NOB_MANUFACTURER == 1)
						$('input:checkbox[name=nob_manufacturer]').prop('checked', true);

					if (data.rs_vreg[0].NOB_IMPORTER == 1)
						$('input:checkbox[name=nob_importer]').prop('checked', true);

					if (data.rs_vreg[0].NOB_WHOLESALER == 1)
						$('input:checkbox[name=nob_wholesaler]').prop('checked', true);

					if (data.rs_vreg[0].NOB_OTHERS == 1)
						$('input:checkbox[name=nob_others]').prop('checked', true);
					
					$('#txt_nob_others').val(data.rs_vreg[0].NOB_OTHERS_TEXT);

					$('#status_id').val(data.rs_vreg[0].STATUS_ID);


					$(document).ready(function(){
	           		let cat_sup = [];	
					$('#cat_sup input[type=hidden]').each(function(){
						cat_sup.push(this.value);			
					});	
					
	           		let avc_cat_sup = [];	
					$('#avc_cat_sup input[type=hidden]').each(function(){
						avc_cat_sup.push(this.value);			
					});	
		
					var vt = data.rs_vreg[0].VENDOR_TYPE;
					
					//console.log("VTT= " + vt);
					get_document_agreement(data.rs_vreg[0].OWNERSHIP_TYPE, data.rs_vreg[0].TRADE_VENDOR_TYPE,cat_sup, vt).done(function(){	
						if (data.rs_vreg[0].APPROVER_ID == <?php echo $user_id ?>) // load draft incomplete
						{
							$('#rv_incomplete').val(data.rs_vreg[0].APPROVER_REMARKS);

							var ir_count = data.count_ir > 0 ? data.count_ir : '1';// default value is 1
							$('#increason_count').val(ir_count);													

							for (var i = 0, j = 1; i < data.count_ir; i++, j++) // i is for object, j is for element //for (var j = 1; j <= data.count_ir; j++) // i is for object, j is for element
							{
								cache.set('inc_reason'+j, data.rs_ir[i].REASON_ID); // cache muna iloload sa pag click ng incomplete na btn #btn_inc

								if ($('#div_ir'+j).length) // if exists write value
								{
									$('#cbo_da'+j).val(data.rs_ir[i].DOCUMENT_ID+'|'+data.rs_ir[i].DOCUMENT_TYPE);
									get_inc_reason(j, true);
								}
								else // element not exists create element and write value
								{
									$('#div_increason').append(ir_template.clone().attr({'id':'div_ir'+j, 'name':'div_ir'+j})).find('#div_ir'+j+' :input').val('');
							        reset_ids('cls_ir','increason_count',j,'div_ir');
							        document.getElementById('cbo_da'+j).onchange = undefined; // unbind first the old onchage so that it wont fire
							        $("#cbo_da"+j).attr("onchange", "get_inc_reason("+j+")");
							        // get_inc_reason(j);
							        $('#cbo_da'+j).val(data.rs_ir[i].DOCUMENT_ID+'|'+data.rs_ir[i].DOCUMENT_TYPE);
									get_inc_reason(j, true);
								}
								
							}

						}

					});

					});						
            	}

				// load brands
				if (data.count_vbrand > 0)
				{
					$('#brand_count').val(data.count_vbrand);
					for (var i = 0, j = 1; i < data.count_vbrand; i++, j++) // i is for object, j is for element
					{
						if ($('#div_brandid'+j).length) // if exists write value
						{
							$('#brand_id'+j).val(data.rs_vbrand[i].BRAND_ID);
							$('#brand_name'+j).val(data.rs_vbrand[i].BRAND_NAME);
						}
						else // element not exists create element and write value
						{
							var new_div = brand_template.clone().attr({'id':'div_brandid'+j, 'name':'div_brandid'+j});
							$('#div_brand').append(new_div).find('#div_brandid'+j+' :input').val(''); // append and reset value of clone
							reset_ids('cls_brand','brand_count',j,'div_brandid'); // reset id of elements inside div_brandid+j
							// assign value to new rows
							$('#brand_id'+j).val(data.rs_vbrand[i].BRAND_ID);
							$('#brand_name'+j).val(data.rs_vbrand[i].BRAND_NAME);
						}
					}
				}
				// end load brand
				
				// load address
				if (data.count_vaddr_office > 0)
				{
					$('#office_addr_count').val(data.count_vaddr_office);
					for (var i = 0, j = 1; i < data.count_vaddr_office; i++, j++) // i is for object, j is for element
					{
						if ($('#div_office_addr'+j).length) // if exists write value
						{
							$('#office_add'+j).val(data.rs_vaddr_office[i].ADDRESS_LINE);
							$('#office_brgy_cm'+j).val(data.rs_vaddr_office[i].CITY_NAME);
							$('#office_state_prov'+j).val(data.rs_vaddr_office[i].STATE_PROV_NAME);
							$('#office_zip_code'+j).val(data.rs_vaddr_office[i].ZIP_CODE);
							$('#office_country'+j).val(data.rs_vaddr_office[i].COUNTRY_NAME);
							$('#office_region'+j).val(data.rs_vaddr_office[i].REGION_DESC_TWO);
						}
						else // element not exists create element and write value
						{
							var new_div = offce_template.clone().attr({'id':'div_office_addr'+j, 'name':'div_office_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_offc_addr').append(new_div).find('#div_office_addr'+j+' :input').val(''); // append and reset value
					        $('#div_office_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_office_addr','office_addr_count',j,'div_office_addr'); // reset id of elements inside div_brandid+j
							// assign value to new rows
							$('#office_add'+j).val(data.rs_vaddr_office[i].ADDRESS_LINE);
							$('#office_brgy_cm'+j).val(data.rs_vaddr_office[i].CITY_NAME);
							$('#office_state_prov'+j).val(data.rs_vaddr_office[i].STATE_PROV_NAME);
							$('#office_zip_code'+j).val(data.rs_vaddr_office[i].ZIP_CODE);
							$('#office_country'+j).val(data.rs_vaddr_office[i].COUNTRY_NAME);
							$('#office_region'+j).val(data.rs_vaddr_office[i].REGION_DESC_TWO);
						}
						// set primary 
						if (data.rs_vaddr_office[i].PRIMARY == 1)
							$('input:radio[name=office_primary][value=' + j + ']').prop('checked', true);
					}
				}else{
					$('input:radio[name=office_primary][value=1]').prop('checked', true);
				}
				
				if (data.count_vaddr_factory > 0)
				{
					$('#factory_addr_count').val(data.count_vaddr_factory);
					for (var i = 0, j = 1; i < data.count_vaddr_factory; i++, j++) // i is for object, j is for element
					{
						if ($('#div_factory_addr'+j).length) // if exists write value
						{
							$('#factory_addr'+j).val(data.rs_vaddr_factory[i].ADDRESS_LINE);
							$('#factory_brgy_cm'+j).val(data.rs_vaddr_factory[i].CITY_NAME);
							$('#factory_state_prov'+j).val(data.rs_vaddr_factory[i].STATE_PROV_NAME);
							$('#factory_zip_code'+j).val(data.rs_vaddr_factory[i].ZIP_CODE);
							$('#factory_country'+j).val(data.rs_vaddr_factory[i].COUNTRY_NAME);
							$('#factory_region'+j).val(data.rs_vaddr_factory[i].REGION_DESC_TWO);
						}
						else // element not exists create element and write value
						{
							var new_div = factory_addr_template.clone().attr({'id':'div_factory_addr'+j, 'name':'div_factory_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_factory_addr').append(new_div).find('#div_factory_addr'+j+' :input').val(''); // append and reset value
					        $('#div_factory_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_factory_addr','factory_addr_count',j,'div_factory_addr'); // reset id of elements inside div_brandid+j
							// assign value to new rows
							$('#factory_addr'+j).val(data.rs_vaddr_factory[i].ADDRESS_LINE);
							$('#factory_brgy_cm'+j).val(data.rs_vaddr_factory[i].CITY_NAME);
							$('#factory_state_prov'+j).val(data.rs_vaddr_factory[i].STATE_PROV_NAME);
							$('#factory_zip_code'+j).val(data.rs_vaddr_factory[i].ZIP_CODE);
							$('#factory_country'+j).val(data.rs_vaddr_factory[i].COUNTRY_NAME);
							$('#factory_region'+j).val(data.rs_vaddr_factory[i].REGION_DESC_TWO);
						}
						// set primary 
						if (data.rs_vaddr_factory[i].PRIMARY == 1)
							$('input:radio[name=factory_primary][value=' + j + ']').prop('checked', true);
					}
				}else{
					$('input:radio[name=factory_primary][value=1]').prop('checked', true);
				}

				if (data.count_vaddr_warehouse > 0)
				{
					$('#wh_addr_count').val(data.count_vaddr_warehouse);
					for (var i = 0, j = 1; i < data.count_vaddr_warehouse; i++, j++) // i is for object, j is for element
					{
						if ($('#div_wh_addr'+j).length) // if exists write value
						{
							$('#ware_addr'+j).val(data.rs_vaddr_warehouse[i].ADDRESS_LINE);
							$('#ware_brgy_cm'+j).val(data.rs_vaddr_warehouse[i].CITY_NAME);
							$('#ware_state_prov'+j).val(data.rs_vaddr_warehouse[i].STATE_PROV_NAME);
							$('#ware_zip_code'+j).val(data.rs_vaddr_warehouse[i].ZIP_CODE);
							$('#ware_country'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_NAME);
							$('#ware_region'+j).val(data.rs_vaddr_warehouse[i].REGION_DESC_TWO);
						}
						else // element not exists create element and write value
						{
							var new_div = wh_addr_template.clone().attr({'id':'div_wh_addr'+j, 'name':'div_wh_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_wh_addr').append(new_div).find('#div_wh_addr'+j+' :input').val(''); // append and reset value
					        $('#div_wh_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_wh_addr','wh_addr_count',j,'div_wh_addr'); // reset id of elements inside div_brandid+j
							// assign value to new rows
							$('#ware_addr'+j).val(data.rs_vaddr_warehouse[i].ADDRESS_LINE);
							$('#ware_brgy_cm'+j).val(data.rs_vaddr_warehouse[i].CITY_NAME);
							$('#ware_state_prov'+j).val(data.rs_vaddr_warehouse[i].STATE_PROV_NAME);
							$('#ware_zip_code'+j).val(data.rs_vaddr_warehouse[i].ZIP_CODE);
							$('#ware_country'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_NAME);
							$('#ware_region'+j).val(data.rs_vaddr_warehouse[i].REGION_DESC_TWO);
						}
						// set primary 
						if (data.rs_vaddr_warehouse[i].PRIMARY == 1)
							$('input:radio[name=ware_primary][value=' + j + ']').prop('checked', true);
					}
				}else{
					$('input:radio[name=ware_primary][value=1]').prop('checked', true);
				}
				// end load address

				// load contacts 
				//tel no
				if (data.count_vc_telno > 0)
				{
					$('#telno_count').val(data.count_vc_telno);
					for (var i = 0, j = 1; i < data.count_vc_telno; i++, j++) // i is for object, j is for element
					{
						if ($('#tel_no'+j).length) // if exists write value
						{
							$('#tel_ccode'+j).val(data.rs_vc_telno[i].COUNTRY_CODE);
							$('#tel_acode'+j).val(data.rs_vc_telno[i].AREA_CODE);
							$('#tel_no'+j).val(data.rs_vc_telno[i].CONTACT_DETAIL);
							$('#tel_elno'+j).val(data.rs_vc_telno[i].EXTENSION_LOCAL_NUMBER);
						}
						else // element not exists create element and write value
						{
							// $('#div_telno').append(telno_template.clone()).find('input:last').attr({'id':'tel_no'+j, 'name':'tel_no'+j, 'value': ''}).val(''); // append and reset value
							$('#div_telno').append(telno_template.clone().attr({'id':'div_telinline'+j, 'name':'div_telinline'+j})).find('#div_telinline'+j+' :input').val(''); // append and reset value
							reset_ids('cls_telno','telno_count',j,'div_telinline');
							// assign value to new rows
							$('#tel_ccode'+j).val(data.rs_vc_telno[i].COUNTRY_CODE);
							$('#tel_acode'+j).val(data.rs_vc_telno[i].AREA_CODE);
							$('#tel_no'+j).val(data.rs_vc_telno[i].CONTACT_DETAIL);
							$('#tel_elno'+j).val(data.rs_vc_telno[i].EXTENSION_LOCAL_NUMBER);
						}
						
					}
				}
				// end tel no
				//email
				if (data.count_vc_email > 0)
				{
					$('#email_count').val(data.count_vc_email);
					for (var i = 0, j = 1; i < data.count_vc_email; i++, j++) // i is for object, j is for element
					{
						if ($('#email'+j).length) // if exists write value
						{
							$('#email'+j).val(data.rs_vc_email[i].CONTACT_DETAIL);
						}
						else // element not exists create element and write value
						{
							$('#div_email').append(email_template.clone()).find('input:last').attr({'id':'email'+j, 'name':'email'+j, 'value': ''}).val(''); // append and reset value
							// assign value to new rows
							$('#email'+j).val(data.rs_vc_email[i].CONTACT_DETAIL);
						}
						
					}
				}
				// end email
				//faxno
				if (data.count_vc_faxno > 0)
				{
					$('#faxno_count').val(data.count_vc_faxno);
					for (var i = 0, j = 1; i < data.count_vc_faxno; i++, j++) // i is for object, j is for element
					{
						if ($('#fax_no'+j).length) // if exists write value
						{
							$('#fax_ccode'+j).val(data.rs_vc_faxno[i].COUNTRY_CODE);
							$('#fax_acode'+j).val(data.rs_vc_faxno[i].AREA_CODE);
							$('#fax_no'+j).val(data.rs_vc_faxno[i].CONTACT_DETAIL);
							$('#fax_elno'+j).val(data.rs_vc_faxno[i].EXTENSION_LOCAL_NUMBER);
						}
						else // element not exists create element and write value
						{
							// $('#div_faxno').append(faxno_template.clone()).find('input:last').attr({'id':'fax_no'+j, 'name':'fax_no'+j, 'value': ''}).val(''); // append and reset value
							 $('#div_faxno').append(faxno_template.clone().attr({'id':'div_faxinline'+j, 'name':'div_faxinline'+j})).find('#div_faxinline'+j+' :input').val(''); // append and reset value
							 reset_ids('cls_faxno','faxno_count',j,'div_faxinline');
							// assign value to new rows
							$('#fax_ccode'+j).val(data.rs_vc_faxno[i].COUNTRY_CODE);
							$('#fax_acode'+j).val(data.rs_vc_faxno[i].AREA_CODE);
							$('#fax_no'+j).val(data.rs_vc_faxno[i].CONTACT_DETAIL);
							$('#fax_elno'+j).val(data.rs_vc_faxno[i].EXTENSION_LOCAL_NUMBER);
						}
						
					}
				}
				// end faxno
				//mobno
				if (data.count_vc_mobno > 0)
				{
					$('#mobno_count').val(data.count_vc_mobno);
					for (var i = 0, j = 1; i < data.count_vc_mobno; i++, j++) // i is for object, j is for element
					{
						if ($('#mobile_no'+j).length) // if exists write value
						{
							$('#mobile_ccode'+j).val(data.rs_vc_mobno[i].COUNTRY_CODE);
							$('#mobile_acode'+j).val(data.rs_vc_mobno[i].AREA_CODE);
							$('#mobile_no'+j).val(data.rs_vc_mobno[i].CONTACT_DETAIL);
							$('#mobile_elno'+j).val(data.rs_vc_mobno[i].EXTENSION_LOCAL_NUMBER);
						}
						else // element not exists create element and write value
						{
							//console.log('test');
							// $('#div_mobno').append(mobno_template.clone()).find('input:last').attr({'id':'mobile_no'+j, 'name':'mobile_no'+j, 'value': ''}).val(''); // append and reset value
							$('#div_mobno').append(mobno_template.clone().attr({'id':'div_mobnoinline'+j, 'name':'div_mobnoinline'+j})).find('#div_mobnoinline'+j+' :input').val(''); // append and reset value
							reset_ids('cls_mobno','mobno_count',j,'div_mobnoinline');
							
							$('#div_mobno [name=vcdpsv'+j+']').remove();
							
							// assign value to new rows
							$('#mobile_ccode'+j).val(data.rs_vc_mobno[i].COUNTRY_CODE);
							$('#mobile_acode'+j).val(data.rs_vc_mobno[i].AREA_CODE);
							$('#mobile_no'+j).val(data.rs_vc_mobno[i].CONTACT_DETAIL);
							$('#mobile_elno'+j).val(data.rs_vc_mobno[i].EXTENSION_LOCAL_NUMBER);
						}
						
					}
				}
				// end mobno
				//Owners/Partners/Directors
				if (data.count_vowner > 0)
				{
					$('#opd_count').val(data.count_vowner);
					for (var i = 0, j = 1; i < data.count_vowner; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_opd'+j).length) // if exists write value
						{
							$('#opd_fname'+j).val(data.rs_vowner[i].FIRST_NAME);
							$('#opd_mname'+j).val(data.rs_vowner[i].MIDDLE_NAME);
							$('#opd_lname'+j).val(data.rs_vowner[i].LAST_NAME);
							$('#opd_pos'+j).val(data.rs_vowner[i].POSITION);
							if(data.rs_vowner[i].AUTH_SIG == 'Y'){
								$('#opd_auth'+j).prop("checked",true);
							}else{
								$('#opd_auth'+j).prop("checked",false);
							}
						}
						else // element not exists create element and write value
						{
							$('#tbl_opd > tbody:last-child').append(opd_template.clone().attr({'id':'tr_opd'+j})).find('#tr_opd'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_opd','opd_count',j,'tr_opd');
							// assign value to new rows
							$('#opd_fname'+j).val(data.rs_vowner[i].FIRST_NAME);
							$('#opd_mname'+j).val(data.rs_vowner[i].MIDDLE_NAME);
							$('#opd_lname'+j).val(data.rs_vowner[i].LAST_NAME);
							$('#opd_pos'+j).val(data.rs_vowner[i].POSITION);
							if(data.rs_vowner[i].AUTH_SIG == 'Y'){
								$('#opd_auth'+j).prop("checked",true);
							}else{
								$('#opd_auth'+j).prop("checked",false);
							}
						}
						
					}
				}
				// end Owners/Partners/Directors
				//Authorized Representatives
				if (data.count_vauthrep > 0)
				{
					//console.log(data.rs_vauthrep);
					$('#authrep_count').val(data.count_vauthrep);
					for (var i = 0, j = 1; i < data.count_vauthrep; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_authrep'+j).length) // if exists write value
						{
							$('#authrep_fname'+j).val(data.rs_vauthrep[i].FIRST_NAME);
							$('#authrep_mname'+j).val(data.rs_vauthrep[i].MIDDLE_NAME);
							$('#authrep_lname'+j).val(data.rs_vauthrep[i].LAST_NAME);
							$('#authrep_pos'+j).val(data.rs_vauthrep[i].POSITION);
							if(data.rs_vauthrep[i].AUTH_SIG == 'Y'){
								$('#authrep_auth'+j).prop("checked",true);
							}else{
								$('#authrep_auth'+j).prop("checked",false);
							}
						}
						else // element not exists create element and write value
						{
							$('#tbl_authrep > tbody:last-child').append(authrep_template.clone().attr({'id':'tr_authrep'+j})).find('#tr_authrep'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_authrep','authrep_count',j,'tr_authrep');
							// assign value to new rows
							$('#authrep_fname'+j).val(data.rs_vauthrep[i].FIRST_NAME);
							$('#authrep_mname'+j).val(data.rs_vauthrep[i].MIDDLE_NAME);
							$('#authrep_lname'+j).val(data.rs_vauthrep[i].LAST_NAME);
							$('#authrep_pos'+j).val(data.rs_vauthrep[i].POSITION);
							if(data.rs_vauthrep[i].AUTH_SIG == 'Y'){
								$('#authrep_auth'+j).prop("checked",true);
							}else{
								$('#authrep_auth'+j).prop("checked",false);
							}
						}
						
					}
				}
				// end Authorized Representatives
				//Bank References
				if (data.count_vbank > 0)
				{
					$('#bankrep_count').val(data.count_vbank);
					for (var i = 0, j = 1; i < data.count_vbank; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_bankrep'+j).length) // if exists write value
						{
							$('#bankrep_name'+j).val(data.rs_vbank[i].BANK_NAME);
							$('#bankrep_branch'+j).val(data.rs_vbank[i].BANK_BRANCH);						
						}
						else // element not exists create element and write value
						{
							$('#tbl_bankrep > tbody:last-child').append(bankrep_template.clone().attr({'id':'tr_bankrep'+j})).find('#tr_bankrep'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_bankrep','bankrep_count',j,'tr_bankrep');
							// assign value to new rows
							$('#bankrep_name'+j).val(data.rs_vbank[i].BANK_NAME);
							$('#bankrep_branch'+j).val(data.rs_vbank[i].BANK_BRANCH);
						}
						
					}
				}
				// end Bank References
				//Other Retail Customers/Clients
				if (data.count_vretcust > 0)
				{
					$('#orcc_count').val(data.count_vretcust);
					for (var i = 0, j = 1; i < data.count_vretcust; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_orcc'+j).length) // if exists write value
						{
							$('#orcc_compname'+j).val(data.rs_vretcust[i].COMPANY_NAME);
						}
						else // element not exists create element and write value
						{
							$('#tbl_orcc > tbody:last-child').append(orcc_template.clone().attr({'id':'tr_orcc'+j})).find('#tr_orcc'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_orcc','orcc_count',j,'tr_orcc');
							// assign value to new rows
							$('#orcc_compname'+j).val(data.rs_vretcust[i].COMPANY_NAME);
						}
						
					}
				}
				// end Other Retail Customers/Clients
				//Other Business
				if (data.count_vob > 0)
				{
					$('#otherbusiness_count').val(data.count_vob);
					for (var i = 0, j = 1; i < data.count_vob; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_otherbusiness'+j).length) // if exists write value
						{
							$('#ob_compname'+j).val(data.rs_vob[i].COMPANY_NAME);
							$('#ob_pso'+j).val(data.rs_vob[i].SERVICE_OFFERED);
						}
						else // element not exists create element and write value
						{
							$('#tbl_otherbusiness > tbody:last-child').append(otherbusiness_template.clone().attr({'id':'tr_otherbusiness'+j})).find('#tr_otherbusiness'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_otherbusiness','otherbusiness_count',j,'tr_otherbusiness');
							// assign value to new rows
							$('#ob_compname'+j).val(data.rs_vob[i].COMPANY_NAME);
							$('#ob_pso'+j).val(data.rs_vob[i].SERVICE_OFFERED);
						}
						
					}
				}
				// end Other Business
				//Disclosure of Relatives Working in SM or its Affiliates
				if (data.count_vrel > 0)
				{
					$('#affiliates_count').val(data.count_vrel);
					for (var i = 0, j = 1; i < data.count_vrel; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_affiliates'+j).length) // if exists write value
						{
							$('#affiliates_fname'+j).val(data.rs_vrel[i].FIRST_NAME);
							$('#affiliates_lname'+j).val(data.rs_vrel[i].LAST_NAME);
							$('#affiliates_pos'+j).val(data.rs_vrel[i].POSITION);
							$('#affiliates_comp_afw'+j).val(data.rs_vrel[i].COMPANY);
							$('#affiliates_rel'+j).val(data.rs_vrel[i].RELATIONSHIP);
						}
						else // element not exists create element and write value
						{
							$('#tbl_affiliates > tbody:last-child').append(affiliates_template.clone().attr({'id':'tr_affiliates'+j})).find('#tr_affiliates'+j+' :input').val(''); // append and reset value
	            			reset_ids('cls_affiliates','affiliates_count',j,'tr_affiliates');
							// assign value to new rows
							$('#affiliates_fname'+j).val(data.rs_vrel[i].FIRST_NAME);
							$('#affiliates_lname'+j).val(data.rs_vrel[i].LAST_NAME);
							$('#affiliates_pos'+j).val(data.rs_vrel[i].POSITION);
							$('#affiliates_comp_afw'+j).val(data.rs_vrel[i].COMPANY);
							$('#affiliates_rel'+j).val(data.rs_vrel[i].RELATIONSHIP);
						}
						
					}
				}
				// end Disclosure of Relatives Working in SM or its Affiliates

				var ownership           = '';
	            var trade_vendor_type   = '';

	            if ($("input[name='ownership']:checked").length > 0)
	                ownership = $("input[name='ownership']:checked").val();

	            if ($("input[name='trade_vendor_type']:checked").length > 0){
	                trade_vendor_type = $("input[name='trade_vendor_type']:checked").val();
				}else{
					trade_vendor_type = $("input[name='vendor_type']:checked").val();
					
					if(trade_vendor_type == 3){
						trade_vendor_type = 4; // 4 = NTS 
					}
				}
				
				if(data.count_cat > 0){					
					for (var i = 0, j = 1; i < data.count_cat; i++, j++){ // i is for object, j is for element
						
						if(data.count_vreg > 0){
							if((data.rs_registration_type == 4 && (data.rs_vreg[0].VENDOR_CODE_02 != '' && data.rs_vreg[0].VENDOR_CODE_02 != null)) || data.rs_vreg[0].PREV_REGISTRATION_TYPE == 4){
								if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
									var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
									new_row.find('span#category_name'+i).attr('id','category_name'+j);
									new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
									
									if (!$('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
										$('#cat_sup tbody').append(new_row.clone());
									
									$('#category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
									$('#sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);	
								}else{
									var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
									new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
									new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
									
									if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
										$('#avc_cat_sup tbody').append(new_row.clone());
							
									$('#avc_category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
									$('#avc_sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);
								}
							}else if(data.rs_registration_type == 4 && (data.rs_vreg[0].VENDOR_CODE_02 == '' || data.rs_vreg[0].VENDOR_CODE_02 == null)){
							}else if(data.rs_vreg[0].VENDOR_CODE_02 != '' && data.rs_vreg[0].VENDOR_CODE_02 != null){
								if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
									var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
									new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
									new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
									
									if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
										$('#avc_cat_sup tbody').append(new_row.clone());
							
									$('#avc_category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
									$('#avc_sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);
								}else{
									var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
									new_row.find('span#category_name'+i).attr('id','category_name'+j);
									new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
									
									if (!$('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
										$('#cat_sup tbody').append(new_row.clone());
									
									$('#category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
									$('#sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);	
								}
							}else{
								var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
								new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
								new_row.find('span#category_name'+i).attr('id','category_name'+j);
								new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
								
								if (!$('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
									$('#cat_sup tbody').append(new_row.clone());
								
								$('#category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
								$('#sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);	
							}
						}else{
							var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
							new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
							new_row.find('span#category_name'+i).attr('id','category_name'+j);
							new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
							
							if (!$('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
								$('#cat_sup tbody').append(new_row.clone());
							
							$('#category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
							$('#sub_category_name'+j).html(data.rs_cat[i].SUB_CATEGORY_NAME);	
						}
					}
				}
				
				if(data.count_cat_avc > 0){	
					for (var i = 0, j = 1; i < data.count_cat_avc; i++, j++){ // i is for object, j is for element
						if(data.count_vreg > 0){
							if((data.rs_registration_type == 4 && (data.rs_vreg[0].VENDOR_CODE_02 != '' && data.rs_vreg[0].VENDOR_CODE_02 != null)) || data.rs_vreg[0].PREV_REGISTRATION_TYPE == 4){
								if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
									var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
									new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
									new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
									
									if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
										$('#avc_cat_sup tbody').append(new_row.clone());
							
									$('#avc_category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
									$('#avc_sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);
								}else{
									var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
									new_row.find('span#category_name'+i).attr('id','category_name'+j);
									new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
									
									if (!$('#cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
										$('#cat_sup tbody').append(new_row.clone());
									
									$('#category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
									$('#sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);	
								}
							}else if(data.rs_registration_type == 4 && (data.rs_vreg[0].VENDOR_CODE_02 == '' || data.rs_vreg[0].VENDOR_CODE_02 == null)){
								var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
								new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
								new_row.find('span#category_name'+i).attr('id','category_name'+j);
								new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
								
								if (!$('#cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
									$('#cat_sup tbody').append(new_row.clone());
								
								$('#category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
								$('#sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);	
							}else if(data.rs_vreg[0].VENDOR_CODE_02 != ''){
								if(data.rs_vreg[0].TRADE_VENDOR_TYPE == 2){
									var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
									new_row.find('span#category_name'+i).attr('id','category_name'+j);
									new_row.find('span#sub_category_name'+i).attr('id','sub_category_name'+j);
									
									if (!$('#cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
										$('#cat_sup tbody').append(new_row.clone());
									
									$('#category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
									$('#sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);	
								}else{
									var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
									new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
									new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
									new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
									
									if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
										$('#avc_cat_sup tbody').append(new_row.clone());
							
									$('#avc_category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
									$('#avc_sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);
								}
							}else{
								var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
								new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
								new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
								new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
								
								if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
									$('#avc_cat_sup tbody').append(new_row.clone());
						
								$('#avc_category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
								$('#avc_sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);
							}
						}else{
							var new_row = avc_cat_template.attr({'id':'tr_avc_catsup'+j,'class':'cls_tr_cat'});
							new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat_avc[i].CATEGORY_ID});
							new_row.find('span#avc_category_name'+i).attr('id','avc_category_name'+j);
							new_row.find('span#avc_sub_category_name'+i).attr('id','avc_sub_category_name'+j);
							
							if (!$('#avc_cat_sup tbody :input[value='+data.rs_cat_avc[i].CATEGORY_ID+']').length)
								$('#avc_cat_sup tbody').append(new_row.clone());
					
							$('#avc_category_name'+j).html(data.rs_cat_avc[i].CATEGORY_NAME);
							$('#avc_sub_category_name'+j).html(data.rs_cat_avc[i].SUB_CATEGORY_NAME);
						}
					}
				}
				
			if(data.rs_vreg[0].STATUS_ID == 10){
				review_form();
			}
				

			$(document).ready(function(){
	            let cat_sup = [];	
				$('#cat_sup input[type=hidden]').each(function(){
					cat_sup.push(this.value);
				});
				$("#cat_sup_count").val(cat_sup.length);
				
				var vendor_type = $('input:radio[name=vendor_type]:checked').val();
	            var trade_vendor_type   = '0';

	            if ($("input[name='ownership']:checked").length > 0)
	                ownership = $("input[name='ownership']:checked").val();

				if(vendor_type == 1){
					trade_vendor_type = $("input[name='trade_vendor_type']:checked").val();
				}
				
				// cetegories END
				get_list_docs(ownership, trade_vendor_type,cat_sup, vendor_type, data.rs_registration_type).done(function(){
				
					// Required Scanned Documents
					if (data.count_vreqdoc > 0)
					{
						var hidden_waive = $('*[id^="rsd_waive_hidden"]');
						var total_hw = hidden_waive.length;
						
						for (var i = 0, j = 1; i < data.count_vreqdoc; i++, j++)
						{
							$('*[id^="rsd_waive_hidden"]').each(function(ii, obj){
								if(data.rs_vreqdoc[i].DOC_TYPE_ID == $(obj).val()){
									//console.log(data.rs_vreqdoc[i].DOC_TYPE_ID+ " == "+ $(obj).val());
									$('#waive_rsd_document_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).attr('checked','');
									
								}
							});
							$('#rsd_document_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);
							$('#rsd_date_upload'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_CREATED);
							$('#rsd_orig_name'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].ORIGINAL_FILENAME);
							$('#btn_rsd_preview'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].FILE_PATH).prop('disabled', false);

							if (data.rs_vreqdoc[i].DATE_REVIEWED)
							{
								$('#rsd_document_review'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);
								$('#rsd_date_reviewed'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_REVIEWED)

							}
							if (data.rs_vreqdoc[i].DATE_VERIFIED)
							{
								$('#rsd_document_validated'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);
								$('#rsd_date_validated'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_VERIFIED)
							}
						}
					}
					// end Required Scanned Documents
					//Check waive checkbox
					var rsd_hidden_waive = $('*[id^="rsd_waive_hidden"]');
					var rsd_docs = $('*[id^="rsd_document_chk"]');
					var rsd_docs_total = rsd_docs.length;
					//console.log("AD LENGTH : " + $(rsd_docs[i]).val());
					var rsd_total_hw = rsd_hidden_waive.length;
					for (var i = 0, j = 1; i < rsd_docs_total; i++, j++)
					{
						// ad == ra - Additional requirement
						$('*[id^="rsd_waive_hidden"]').each(function(ii, obj){
							if($(rsd_docs[i]).val() == $(obj).val()){
							//	console.log($(rsd_docs[i]).val() + " == "+ $(obj).val());
								$('#waive_rsd_document_chk'+ $(rsd_docs[i]).val()).attr('checked','');
								
								//Commented by jay:
								$('#rsd_document_review'+ $(obj).val()).removeClass("reviewed_additional");
								$('#rsd_document_review'+  $(obj).val()).addClass("na_reviewed_additional");
								//$('#rsd_document_review'+  $(obj).val()).attr("checked", "");
								$('#rsd_document_validated'+ $(obj).val()).removeClass("validated");
								$('#rsd_document_validated'+  $(obj).val()).addClass("na_validated");
								//$('#rsd_document_validated'+  $(obj).val()).attr("checked", "");
								$('#rsd_document_review'+ $(obj).val()).removeClass("reviewed");
								$('#rsd_document_review'+  $(obj).val()).addClass("na_reviewed");
							}
						});
					}
					
					// Required Agreements
					if (data.count_vagree > 0)
					{
						
						var hidden_waive = $('*[id^="ad_waive_hidden"]');
						var total_hw = hidden_waive.length;
						for (var i = 0, j = 1; i < data.count_vagree; i++, j++)
						{
							// ad == ra - Additional requirement
							$('*[id^="ad_waive_hidden"]').each(function(ii, obj){
								if(data.rs_vagree[i].DOC_TYPE_ID == $(obj).val()){
									//console.log(data.rs_vagree[i].DOC_TYPE_ID+ " == "+ $(obj).val());
									$('#waive_ad_document_chk'+data.rs_vagree[i].DOC_TYPE_ID).attr('checked','');
								}
							});
							$('#ra_document_chk'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
							$('#ra_date_upload'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_CREATED);
							$('#ra_orig_name'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].ORIGINAL_FILENAME);
							$('#btn_ra_preview'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].FILE_PATH).prop('disabled', false);

							 // review primary requirements di dpat kasama ung addtional req
							 //Commented by jay
							/*if ($('#status_id').val() == 10){
								$('#btn_ra_preview'+data.rs_vagree[i].DOC_TYPE_ID).prop('disabled', true);
							}*/
								
							if( ! data.rs_vagree[i].ORIGINAL_FILENAME){
								//jay
								//if did not submitted, disable the view since there is no file uploaded.
								$('#btn_ra_preview'+data.rs_vagree[i].DOC_TYPE_ID).prop('disabled', true);
							}
							if (data.rs_vagree[i].DATE_REVIEWED)
							{
								$('#ra_document_review'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
								$('#ra_date_reviewed'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_REVIEWED);
							}
							
							if (data.rs_vagree[i].DATE_SUBMITTED)
							{
								$('#ra_document_validated'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
								$('#ra_date_validated'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_SUBMITTED);
							}
						}
					}
					//Check waive checkbox
					var hidden_waive = $('*[id^="ad_waive_hidden"]');
					var ad_docs = $('*[id^="ra_document_chk"]');
					var ad_docs_total = ad_docs.length;
					//console.log("AD LENGTH : " + $(ad_docs[i]).val());
					var total_hw = hidden_waive.length;
					for (var i = 0, j = 1; i < ad_docs_total; i++, j++)
					{
						// ad == ra - Additional requirement
						$('*[id^="ad_waive_hidden"]').each(function(ii, obj){
							if($(ad_docs[i]).val() == $(obj).val()){
								//console.log($(ad_docs[i]).val() + " == "+ $(obj).val());
								$('#waive_ad_document_chk'+ $(ad_docs[i]).val()).attr('checked','');
								
								//Commented by Jay:
								$('#ra_document_review'+ $(obj).val()).removeClass("reviewed_additional");
								$('#ra_document_review'+  $(obj).val()).addClass("na_reviewed_additional");
								//$('#ra_document_review'+  $(obj).val()).attr("checked", "");
								$('#ra_document_validated'+ $(obj).val()).removeClass("validated");
								$('#ra_document_validated'+  $(obj).val()).addClass("na_validated");
								//$('#ra_document_validated'+  $(obj).val()).attr("checked", "");
								$('#ra_document_review'+ $(obj).val()).removeClass("reviewed");
								$('#ra_document_review'+  $(obj).val()).addClass("na_reviewed");
							}
						});
					}
					// end Required Agreements
					
					// CCN Requirements
					if (data.count_ccn > 0)
					{
						
						for (var i = 0, j = 1; i < data.count_ccn; i++, j++)
						{
							$('#ccn_document_chk'+data.rs_ccn[i].DOC_TYPE_ID).prop('checked', true);
							$('#ccn_date_upload'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_CREATED);
							$('#ccn_orig_name'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].ORIGINAL_FILENAME);
							$('#btn_ccn_preview'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].FILE_PATH).prop('disabled', false);
								
							if( ! data.rs_ccn[i].ORIGINAL_FILENAME){
								$('#btn_ccn_preview'+data.rs_ccn[i].DOC_TYPE_ID).prop('disabled', true);
							}
							if (data.rs_ccn[i].DATE_REVIEWED)
							{
								$('#ccn_document_review'+data.rs_ccn[i].DOC_TYPE_ID).prop('checked', true);
								$('#ccn_date_reviewed'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_REVIEWED);
							}
							
							if (data.rs_ccn[i].DATE_SUBMITTED)
							{
								$('#ccn_document_validated'+data.rs_ccn[i].DOC_TYPE_ID).prop('checked', true);
								$('#ccn_date_validated'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_SUBMITTED);
							}
						}
					}
					// End CCN Requirements

					if ($('.validated:checked').length == $('.validated').length)
			            $('#btn_submit_valid').prop('disabled', false);
			        else
			            $('#btn_submit_valid').prop('disabled', true);

			        $('.resizeInput').each(function(){
			        	if ($(this).val() != '')
			        		$(this).css('width', (($(this).val().length) * 8)+'px');
			        });
								
					check_rsd_inc_button();
					check_ad_inc_button();
							
					var sid_temp = parseInt($("#status_id").val());
					<?php if ($view_only != 1 && !isset($validate)) : ?>
						//alert("SID TEMP : " + sid_temp);
						//alert("CHECKED: " + $('.reviewed_additional:checked').length );
						//alert("N: " + $('.reviewed_additional').length );
						//alert(($('.reviewed_additional:checked').length == $('.reviewed_additional').length));
						if ($('.reviewed_additional:checked').length == $('.reviewed_additional').length && sid_temp == 194 || sid_temp == 198){
							$('#btn_rf_visit').removeAttr('disabled');
						}else{
							$('#btn_rf_visit').attr('disabled', "disabled");
						}
						
					<?php endif; ?>	
					//console.log("SID TEMP = " + sid_temp);
					//194 = Review Additional documents
					//198 = Request For Visit
					if(sid_temp == 194 || sid_temp == 198){
						$('*[id^="waive_rsd_document_chk"]').each(function(ii, obj){
							$(obj).attr("disabled","disabled");
						});
						
						if($("#view_only").val() != 1){
							$('*[id^="waive_ad_document_chk"]').each(function(ii, obj){
								$(obj).removeAttr("disabled");
							});
							$("#ad_waive_remarks").removeAttr("readonly");
						}
					}else if(sid_temp == 10){
						//Jay para sa PRD reviewing na pwede mag N/A ng ARD na
						
						<!-- Modified MSF 20200210 (NA) -->
						/*$('*[id^="waive_ad_document_chk"]').each(function(ii, obj){
							$(obj).attr("disabled","disabled");
						});*/
						
						if($("#view_only").val() != 1){
							$('*[id^="waive_ad_document_chk"]').each(function(ii, obj){
								$(obj).removeAttr("disabled");
							});
							$("#ad_waive_remarks").removeAttr("readonly");
						}
					}else{
						$('*[id^="waive_rsd_document_chk"]').each(function(ii, obj){
							$(obj).attr("disabled","disabled");
						});
						$('*[id^="waive_ad_document_chk"]').each(function(ii, obj){
							$(obj).attr("disabled","disabled");
						});
					}
					
					
				});

	        });


            }
		
        };

        ajax_request(ajax_type, url, post_params, success_function).don;
	}
	

	function fill_reasons()
	{
		var count = $('#increason_count').val();
        for (var i = 1; i <= count; i++)
        {
             if ($('#cbo_inc_reason'+i))
                $('#cbo_inc_reason'+i).val(cache.get('inc_reason'+i));
        }
	}
	
	load_vendor_data();

	function get_document_agreement(ownership, trade_vendor_type,category, vendor_type)
	{

		let vid = $('#invite_id').val();


		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registrationreview/get_document_agreement";
        var post_params = "ownership="+ownership+"&trade_vendor_type="+trade_vendor_type+"&current_status_id="+$('#status_id').val()+"&category_id="+category+"&invite_id="+vid+"&vendor_type="+vendor_type;

        var success_function = function(responseText)
        {     

			//console.log(responseText);
           	
        	var data = $.parseJSON(responseText);
            var cbo_da = $('#cbo_da1');
            cbo_da.empty();
            cbo_da.append('<option value="" disabled selected>-- Select --</option>');
            $.each(data.rs_da, function (key,value) {
				//console.log(data.rs_da.length);
				//if(value.DOCUMENT_ID == 2 || !value.DOCUMENT_ID){
				if(value.AGREEMENT_ID){
					cbo_da.append($('<option>', { 
						value: value.AGREEMENT_ID+'|'+value.DOCUMENT_TYPE,
						text : value.REQUIRED_AGREEMENT_NAME 
					}));

				}else{
					let ltemp = "";
					let lname = "";

					if(typeof value.REQUIRED_DOCUMENT_ID != "undefined"){
						ltemp = value.REQUIRED_DOCUMENT_ID;
						lname = value.REQUIRED_DOCUMENT_NAME;
					}else{
						ltemp = value.REQUIRED_AGREEMENT_ID
						lname = value.REQUIRED_AGREEMENT_NAME;
					}


					cbo_da.append($('<option>', { 
					value: ltemp+'|'+value.DOCUMENT_TYPE,
					text : lname
					}));
				}


			});
        };

        return ajax_request(ajax_type, url, post_params, success_function);
	}

	function get_inc_reason(i, load_reason = false)
	{
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registrationreview/get_inc_reason";
        var post_params = "cbo_da="+$('#cbo_da'+i).val();

        var success_function = function(responseText)
        {        

			//console.log(responseText);	
        	var data = $.parseJSON(responseText);
            var ir = $('#cbo_inc_reason'+i);
            ir.empty(); // 
            ir.append('<option value="" disabled selected>-- Select --</option>');
            $.each(data.rs_inc_reason, function (key,value) {
            	var tx_val = value.INCOMPLETE_REASON.substring(0, 30);            	
            	if (value.INCOMPLETE_REASON.length > 30)
            		tx_val += '...';

			    ir.append($('<option>', { 
			        value: value.REASON_ID,
			        text : tx_val,
			        title: value.INCOMPLETE_REASON
			    }));
			});

			if (load_reason){
				fill_reasons();
			}

/*			if($('#cbo_da'+i+' option:selected').text().trim() == 'Others' || $('#cbo_da'+i+' option:selected').text().trim() == 'others'){
				$('#cbo_inc_reason'+i).prop('selectedIndex',1);
			}*/
			

        };

        return ajax_request(ajax_type, url, post_params, success_function);		
	}

	// function generate_pdf()
	// {
	// 	document.frm_registration_review.action = "<?=base_url();?>index.php/vendor/registrationreview/generate_pdf/" ;				
	// 	document.frm_registration_review.target = "_self";
	// 	document.frm_registration_review.submit();
	// }

	function printJS()
	{
		var id = $('#vendor_id').val();
		window.open(BASE_URL+'vendor/registrationreview/print_template/'+id);
	}

	// Added MSF - 20191105 (IJR-10612)
	function downloadImg(){
		var url = $('#image_preview').attr('src');	
		let n_url = url.split('/');
		let ncount = n_url.length;
		window.open(BASE_URL+'vendor/registration/download_img/'+n_url[ncount -1]);
	}

	function printImg()
	{
		
		//update MSF - 20191105 (IJR-10612)
		//var url = $('#imagepreview').contents().find('img').attr('src');		
		var url = $('#image_preview').attr('src');	
	//	surl = BASE_URL.replace('index.php/', "");
	//	file_name = url.replace(surl+'vendor_upload_documents/', "");		

		let n_url = url.split('/');
		let ncount = n_url.length;




		window.open(BASE_URL+'vendor/registration/print_image/'+n_url[ncount -1]);
	
  // var url = $('#imagepreview').contents().find('img').attr('src');

		// var img_window = window.open(url,'Image');
		// setTimeout(function(){
	 //        // img_window.focus();
		// 	img_window.print();
		// 	img_window.close();
  //        } , 300);

/*  var url = $('#imagepreview').contents().find('img').attr('src');		
		surl = BASE_URL.replace('index.php/', "");
		file_name = url.replace(surl+'vendor_upload_documents/', "");		
		window.open(BASE_URL+'vendor/registration/print_image/'+file_name);*/
		
	}
	
	function check_rsd_inc_button(bt){
		var rsd_length = "";
		var checked_rsd = "";
		var sid_temp = parseInt($("#status_id").val());
		var rsd_length_reviewed = "";
		var checked_rsd_reviewed = "";
		var registration_type = $("#registration_type").val();
		if(sid_temp == 10){
			
			/*if(checked_rsd_reviewed == 3){
				$('#btn_sub_review').prop('disabled', false);
			}else{
				$('#btn_sub_review').prop('disabled', true);
			}*/
			
			//alert($(bt).val());
			
			//Commented by jay:
			// gusto nila hindi kasama yung review macheck kapag chineck yung N/A
			var attr = $("#rsd_document_review" + $(bt).val()).hasClass("reviewed");
			
			if( ! attr){
				$("#rsd_document_review" + $(bt).val()).removeClass("reviewed_additional");
				$("#rsd_document_review" + $(bt).val()).removeClass("na_reviewed");
				$("#rsd_document_review" + $(bt).val()).addClass("reviewed");
				
				$("#ccn_document_review" + $(bt).val()).removeClass("reviewed_additional");
				$("#ccn_document_review" + $(bt).val()).removeClass("na_reviewed");
				$("#ccn_document_review" + $(bt).val()).addClass("reviewed");
				
				//$("#rsd_document_review" + $(bt).val()).removeAttr("checked");
				//$("#rsd_document_review" + $(bt).val()).prop("checked",false);
				//$("#rsd_document_review" + $(bt).val()).attr("disabled","");
				//$("#rsd_date_reviewed" + $(bt).val()).val("");
			}else{  
				$("#rsd_document_review" + $(bt).val()).addClass("reviewed_additional");
				$("#rsd_document_review" + $(bt).val()).addClass("na_reviewed");
				$("#rsd_document_review" + $(bt).val()).removeClass("reviewed");
				
				$("#ccn_document_review" + $(bt).val()).addClass("reviewed_additional");
				$("#ccn_document_review" + $(bt).val()).addClass("na_reviewed");
				$("#ccn_document_review" + $(bt).val()).removeClass("reviewed");
				
				//$("#rsd_document_review" + $(bt).val()).attr("checked","");
				//$("#rsd_document_review" + $(bt).val()).prop("checked",true);
				//$("#rsd_document_review" + $(bt).val()).attr("disabled","");
				/////$("#rsd_date_reviewed" + $(bt).val()).val("");
			}
			
			rsd_length = $("input[id^=waive_rsd_document_chk]").length;
			checked_rsd = $("input[id^=waive_rsd_document_chk]:checked").length;
			rsd_length_reviewed = $("input[id^=rsd_document_review]").length;
			checked_rsd_reviewed = $("input[id^=rsd_document_review]:checked").length;
			//alert(rsd_length_reviewed);
			//alert(checked_rsd_reviewed);
		
			var class_checked_ra_reviewed = $("input.reviewed:checked").length;
			var ra_class =$(".reviewed").length;
			
			var ccn_length_reviewed = $("input[id^=ccn_document_review]").length;
			var checked_ccn_reviewed = $("input[id^=ccn_document_review]:checked").length;
		
			if(checked_rsd_reviewed == rsd_length_reviewed || class_checked_ra_reviewed == ra_class){
				$('#btn_sub_review').prop('disabled', false);
			}else{
				$('#btn_sub_review').prop('disabled', true);
			}	
			
			if(rsd_length == checked_rsd){
				$("#btn_inc").attr("disabled","");
			}else{
				$("#btn_inc").removeAttr("disabled");
			}
			
		}else{
			rsd_length = $("input[id^=waive_rsd_document_chk]").length;
			checked_rsd = $("input[id^=waive_rsd_document_chk]:checked").length;
			rsd_length_reviewed = $("input[id^=rsd_document_review]").length;
			checked_rsd_reviewed = $("input[id^=rsd_document_review]:checked").length;
		}
		
		if(checked_rsd > 0){
			$("#rsd_waive_lbl").css("display","");
			$("#rsd_waive_remarks").css("display","");
		}else{
			$("#rsd_waive_lbl").css("display","none");
			$("#rsd_waive_remarks").css("display","none");
			$("#rsd_waive_remarks").val("");
		}
	}
	
	function check_ad_inc_button(bt){
		var ad_length = $("input[id^=waive_ad_document_chk]").length;
		var checked_ad = $("input[id^=waive_ad_document_chk]:checked").length;
		
		var ra_length_reviewed = "";
		var checked_ra_reviewed = "";
		
		var sid_temp = parseInt($("#status_id").val());
		if(sid_temp != 10){
			
			
			//alert($(bt).val());
			var attr = $("#ra_document_review" + $(bt).val()).hasClass("reviewed_additional");
			//alert(attr);
			//Commented by jay:
			// gusto nila hindi kasama yung review macheck kapag chineck yung N/A
			if( ! attr){
				$("#ra_document_review" + $(bt).val()).removeClass("na_reviewed_additional");
				$("#ra_document_review" + $(bt).val()).removeClass("na_reviewed");
				$("#ra_document_review" + $(bt).val()).addClass("reviewed_additional");
				//$("#ra_document_review" + $(bt).val()).attr("disabled","");
				
				//$("#ra_document_review" + $(bt).val()).attr("checked","");
				//$("#ra_document_review" + $(bt).val()).prop("checked",true);
				
				////////$("#ra_date_reviewed" + $(bt).val()).val("");
				
				
			}else{
				$("#ra_document_review" + $(bt).val()).addClass("na_reviewed_additional");
				$("#ra_document_review" + $(bt).val()).addClass("na_reviewed");
				$("#ra_document_review" + $(bt).val()).removeClass("reviewed_additional");
				//
				//$("#ra_document_review" + $(bt).val()).attr("disabled","");
				//$("#ra_date_reviewed" + $(bt).val()).val("");
				
				//$("#ra_document_review" + $(bt).val()).removeAttr("checked");
				//$("#ra_document_review" + $(bt).val()).prop("checked",false);
			}
			
			ra_length_reviewed = $("input[id^=ra_document_review]").length;
			checked_ra_reviewed = $("input[id^=ra_document_review]:checked").length;
			var class_checked_ra_reviewed = $("input.reviewed_additional:checked").length;
			var ra_class =$(".reviewed_additional").length;
			if(ad_length == checked_ad){
				$("#btn_inc").attr("disabled","");
				$('#btn_rf_visit').prop('disabled', false);
			}else{
				if(ra_class == class_checked_ra_reviewed){		
					$("#btn_inc").removeAttr("disabled");
					$('#btn_rf_visit').prop('disabled', false);
				}else{
					$("#btn_inc").removeAttr("disabled");
					$('#btn_rf_visit').prop('disabled', true);
				}
			}
		}
		
		if(checked_ad > 0){
			$("#ad_waive_lbl").css("display","");
			$("#ad_waive_remarks").css("display","");
		}else{
			$("#ad_waive_lbl").css("display","none");
			$("#ad_waive_remarks").css("display","none");
			$("#ad_waive_remarks").val("");
		}
	}

	// setting height of iframe according to window size
	$(document).ready(function(){
		// if ($('#view_only').val() == 1)
		$(':input').removeAttr('placeholder'); // remove placeholders 
		
        $('iframe').height( $(window).height() );
        $(window).resize(function(){
            $('iframe').height( $(this).height() );
        });
        var ir_template = $('#div_ir1');
        $('#btn_add_increason').off().on('click', function(){
        	var count = $('#increason_count').val();
        	count++;

        	$('#div_increason').append(ir_template.clone().attr({'id':'div_ir'+count, 'name':'div_ir'+count})).find('#div_ir'+count+' :input').val('');        	
	        $('#increason_count').val(count);
	        reset_ids('cls_ir','increason_count',count,'div_ir');
	        document.getElementById('cbo_da'+count).onchange = undefined; // unbind first the old onchage so that it wont fire
	        $("#cbo_da"+count).attr("onchange", "get_inc_reason("+count+")");
        });
        $('#div_increason').on('click', '.remove_ir', function(){
        	var count = $('#increason_count').val();        
	        if (count > 1)
	        {
	            $(this).closest('.cls_div_ir').remove();
	            reset_ids('cls_div_ir','increason_count'); // update id's of parent
	            //update ids of child
	            for (var i = 1; i <= count; i++)
	            {
	            	reset_ids('cls_ir','increason_count',i,'div_ir');
	            	// document.getElementById('cbo_da'+i).onchange = undefined; // unbind first the old onchage so that it wont fire
	        		$("#cbo_da"+i).attr("onchange", "get_inc_reason("+i+")");
	            }
	        }
	        else
	        {
	        	$('#cbo_da'+count).val('');
	        	$('#cbo_inc_reason'+count).val('');
	        }

        });
		
		//check_rsd_inc_button();
		//check_ad_inc_button();
		$(document).on("click","input[id^=waive_rsd_document_chk]", function(e){
			check_rsd_inc_button(this);
			e.stopImmediatePropagation();
		});
		
		$(document).on("click","input[id^=waive_ad_document_chk]", function(e){
			check_ad_inc_button(this);
			e.stopImmediatePropagation();
		});
    });
	
	function review_form(){
		var brand_count = $('#brand_count').val();
		for(i=1; i<=brand_count; i++){
			$('#brand_name'+i).prop('readonly',''); 
		}
		
		$('#cbo_yr_business').prop('readonly','');
		$('#tax_idno').prop('readonly','');
		$("").prop('disabled', false);
		$("").prop('disabled', false);
		$('input[name="tax_class"]:radio').prop('disabled', false);
	
	//For Radio Buttons
	$('input:radio[name=tax_class], input[name="business_asset"]:radio, input[name="no_of_employee"]:radio, #div_row_factory_addr :input, #div_row_offc_addr :input, #div_row_wh_addr :input, #tbl_authrep :input, #tbl_opd :input').prop('disabled', false);
	
	
	// For Textbox & Dropdown Box
	$('#cbo_yr_business, #tax_idno, #div_row_factory_addr :input, #div_row_offc_addr :input, #div_row_wh_addr :input, #div_telno :input, #div_email :input, #div_faxno :input, #div_mobno :input, #tbl_opd :input, #tbl_authrep :input').prop('readonly', '');
	
	}
	
	
	
	// For Audit Logs//

	$('#div_brand').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("brand");
		}else{
			$('#audit_logs').val(audit_logs + ",brand");
		}
	});
	
	$('#div_no_employees').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("employee");
		}else{
			$('#audit_logs').val(audit_logs + ",employee");
		}
	});
	
	$('#div_business_assets').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("business_asset");
		}else{
			$('#audit_logs').val(audit_logs + ",business_asset");
		}
	});
	
	$('#div_years_business').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("business_years");
		}else{
			$('#audit_logs').val(audit_logs + ",business_years");
		}
	});
	
	$('#div_tax').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("tax");
		}else{
			$('#audit_logs').val(audit_logs + ",tax");
		}
	});
	
	$('#div_row_offc_addr').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("offadd");
		}else{
			$('#audit_logs').val(audit_logs + ",offadd");
		}
	});
	
	$('#div_row_factory_addr').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("facadd");
		}else{
			$('#audit_logs').val(audit_logs + ",facadd");
		}
	});
	
	$('#div_row_wh_addr').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("whadd");
		}else{
			$('#audit_logs').val(audit_logs + ",whadd");
		}
	});
	
	$('#div_row_tel_no').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("telno");
		}else{
			$('#audit_logs').val(audit_logs + ",telno");
		}
	});
	
	$('#div_row_email').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("email");
		}else{
			$('#audit_logs').val(audit_logs + ",email");
		}
	});
	
	$('#div_row_fax').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("fax");
		}else{
			$('#audit_logs').val(audit_logs + ",fax");
		}
	});
	
	$('#div_row_mobile_no').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("cpno");
		}else{
			$('#audit_logs').val(audit_logs + ",cpno");
		}
	});
	
	$('#div_row_opd').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("opd");
		}else{
			$('#audit_logs').val(audit_logs + ",opd");
		}
	});

	$('#div_row_ar').on('change', function(){
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("ar");
		}else{
			$('#audit_logs').val(audit_logs + ",ar");
		}
	});
	
	
	function print_department()
	{
		var id = $('#invite_id').val();
		window.open(BASE_URL+'vendor/add_department/print_template/'+id);
	}
	
	// End of Audit Logs
</script>
