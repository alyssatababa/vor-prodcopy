<?php
					$dsb = 'disabled';
					$_status = array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,122);
			
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
					if(! empty($position_id)){
						
						if(in_array( $position_id ,$waive_permission_ids)){
							$has_full_waive_permission = true;
							$has_edit_waive_remark_permission = true;
						}else if(in_array( $position_id ,$waive_edit_remarks_permission_ids)){
							$has_full_waive_permission = false;
							$has_edit_waive_remark_permission = true;
						}
					}else{
						$has_full_waive_permission = false;
						$has_edit_waive_remark_permission = false;
					}
					//Jay end
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

<!-- Added MSF - 20191105 (IJR-10612) -->
<link href="<?php echo base_url().'assets/css/jquery.guillotine.css'; ?>" media='all' rel='stylesheet'>
<script src="<?php echo base_url().'assets/js/jquery.guillotine.js'; ?>"></script>



<div class="container mycontainer">
	<div class="pull-right">
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_vr_sad">Save As Draft</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_submit">Submit</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary btn-exit">Exit</button>
		</div>
	</div>

	<h4>Vendor Registration &emsp;<small><a href="#" class="cls_action" data-action-path="<?php echo 'messaging/mail/index/'.$invite_id.'/invite'; ?>" data-crumb-text="Messages">Messages</a></small></h4>


	<div class="form_container">
	<div class="panel panel-default">
						<div class="panel-body">
		<div class="col-sm-12">
			<div class="form-group">
				<div class="col-sm-2">
					<label for="vendor_name" class="control-label">  Vendor Name : </label>
				</div>
				<div class="col-sm-6">
					<label style="font-weight: normal;" class="form-control-static"><?php echo $vendorname ?></label>
				</div>

			</div>
			</div>
		<form id="frm_registration" name="frm_registration" method="post" enctype="multipart/form-data">
			<input type="hidden" id="dpa_accept" name="dpa_accept" value="<?php echo $valid; ?>">
			<input type="hidden" id="invite_id" name="invite_id" value="<?php echo $invite_id; ?>">
			<input type="hidden" id="status_id" name="status_id" value="">
			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="brand_name" class="col-sm-2 control-label">* Brand</label>
							<button type="button" id="btn_add_brand" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-9" id="div_brand">
								<input type="hidden" id="brand_count" name="brand_count" value="1">
								<input type="hidden" id="brand_max_count" name="brand_max_count" value="<?php echo $defaults->max_brand; ?>">
								<div class="input-group cls_div_brand" id="div_brandid1">
									<input type="hidden" class="form-control input-sm cls_brand" id="brand_id1" name="brand_id1" >
									<div class="input-group" style="width:100%" >
										<input type="text" class="form-control input-sm cls_brand field-required auto_suggest limit-chars" list-container="brand_list" style="width:100%"  id="brand_name1" name="brand_name1" placeholder="Brand" data-label="brand" width="100%" maxlength="50">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default cls_brand btn-sm autocomplete-toggle" type="button" input-toggle="brand_name1" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<span class="glyphicon glyphicon-trash input-group-addon remove_brand"></span>
								</div>
							</div>
							<?=form_dropdown('brand_list', $brand_array, '', ' id="brand_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
						</div>
						<!-- Added & Modified MSF 20200924 -->
						<div class="form-group">
							<label class="col-sm-4 control-label">* No. of Employees</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="no_of_employee" id="no_of_employee" value="0" class="field-required">MICRO (1 - 9)
							    </label>
								<br/>
							    <label class="radio-inline">
							      <input type="radio" name="no_of_employee" id="no_of_employee" value="1" class="field-required">SMALL (10 - 99)
							    </label>
								<br>
							    <label class="radio-inline">
							      <input type="radio" name="no_of_employee" id="no_of_employee" value="2" class="field-required">MEDIUM (100 - 199)
							    </label>
								<br/>
							    <label class="radio-inline">
							      <input type="radio" name="no_of_employee" id="no_of_employee" value="3" class="field-required">LARGE (200 and above)
							    </label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">* MSME Business Asset Classification</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="business_asset" id="business_asset" value="0" class="field-required">MICRO (Up to P3,000,000)
							    </label>
								<br/>
							    <label class="radio-inline">
							      <input type="radio" name="business_asset" id="business_asset" value="1" class="field-required">SMALL (P3,000,001 - P15,000,000)
							    </label>
								<br/>
							    <label class="radio-inline">
							      <input type="radio" name="business_asset" id="business_asset" value="2" class="field-required">MEDIUM (P15,000,001 - P100,000,000)
							    </label>
								<br/>
							    <label class="radio-inline">
							      <input type="radio" name="business_asset" id="business_asset" value="3" class="field-required">LARGE (P100,000,001 and above)
							    </label>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="cbo_yr_business" class="col-sm-4 control-label">* Years in Business</label>

							<div class="col-sm-8">
								<input type="text" class="form-control input-sm cls_yr_business field-required numeric" id="cbo_yr_business" name="cbo_yr_business" autocomplete="off" data-label="yearsinbusiness" style="width:50px">
								<!-- 
								<select id="cbo_yr_business" name="cbo_yr_business" class="form-control field-required">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select>
								-->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">* Ownership</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="ownership" value="1" class="field-required">Corporation
							    </label>
								<br>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="2" class="field-required">Partnership
							    </label>
							    <br>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="3" class="field-required">Sole Proprietorship
							    </label>
								<br>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="4" class="field-required">Free Lance
							    </label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">* Vendor Type</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="vendor_type" value="1" class="field-required" <?php echo $check_trade ?> disabled>Trade
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type" value="2" class="field-required" <?php echo $check_nontrade ?> disabled>Non Trade
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type" value="3" class="field-required" <?php echo $check_nontradeservice ?> disabled>Non Trade Service
							    </label>
							</div>
						</div>
					</div>
					
					<div class="col-sm-6">
						<?php if($vendor_type == 1): ?>
						<div class="form-group">
							<label class="col-sm-4 control-label">* Trade Vendor Type</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							      <input type="radio" name="trade_vendor_type" value="1" class="field-required" <?php echo $disable_trade_vendor_type.' '.$check_out ?>>Outright
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="trade_vendor_type" value="2" class="field-required" <?php echo $disable_trade_vendor_type.' '.$check_con ?>>Consignor
							    </label>
							    <label class="radio-inline"> <!-- do not show this -->
							      <input type="radio" name="trade_vendor_type" value="0" style="display: none;" <?php echo $check_nontrade ?>> <!-- Non Trade -->
							    </label>
							</div>
						</div>
						<?php endif; ?>
					</div>
					<div class="col-sm-12">
						<br>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-6">
						<div class="panel panel-default panel-body">
							<div class="form-group">
								<label for="tax_idno" class="col-sm-5 control-label">* Tax Identification No</label>

								<div class="col-sm-7">
									<input type="text" class="form-control input-sm field-required numeric" id="tax_idno" name="tax_idno" placeholder="Tax Identification No" data-label="taxidno"  maxlength="50">
								</div>
							</div>

							<br>
							<div class="form-group">
								<label class="col-sm-4 control-label">* Tax Classification</label>

								<div class="col-sm-8">
									<label class="radio-inline">
								      <input type="radio" name="tax_class" value="1" class="field-required">VAT
								    </label>
								    <label class="radio-inline">
								      <input type="radio" name="tax_class" value="2" class="field-required">Non VAT
								    </label>
								    <label class="radio-inline">
								      <input type="radio" name="tax_class" value="3" class="field-required">Zero Rated
								    </label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="panel panel-default panel-body">
							<div id="nature_of_business" class="form-group">
								<label for="nobus" class="col-sm-12 control-label">* Nature of Business <small>(Multiple Selection Allowed)</small></label>

									<div class="col-sm-12">
										<fieldset id="checkArray1">
											<label class="checkbox-inline">
										      <small><input type="checkbox" name="nob_license_dist" >Distributor/Licensee</small>
										    </label>
										    <label class="checkbox-inline">
										      <small><input type="checkbox" name="nob_manufacturer" >Manufacturer</small>
										    </label>
										    <label class="checkbox-inline">
										      <small><input type="checkbox" name="nob_importer" >Importer/Trader</small>
										    </label>
										    <label class="checkbox-inline">
										      <small><input type="checkbox" name="nob_wholesaler" >Wholesaler</small>
										    </label>
										</fieldset>
									</div>
									<div class="col-sm-12 form-inline">
										<fieldset id="checkArray2">
											<label class="checkbox-inline">
										      <small>
										      	<input id="nob_others" type="checkbox" name="nob_others">Others (Pls. Specify)
										      </small>
										    </label>
									    </fieldset>
									    <input type="text" id="txt_nob_others" name="txt_nob_others" value="" class="form-control input-sm limit-chars" placeholder="Others" disabled  maxlength="100">
									</div>

							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- list of datalist -->
			<div>
				<?=form_dropdown('city_list', $city_array, '', ' id="city_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
				<?=form_dropdown('state_list', $state_array, '', ' id="state_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
				<?=form_dropdown('country_list', $country_array, '', ' id="country_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>

				<input type="hidden" name="default_country_id" id="default_country_id" value="<?php echo $default_country_id; ?>">
				<input type="hidden" name="default_country" id="default_country" value="<?php echo $default_country; ?>">
			</div>
			<!-- end of datalist -->

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<label for="btn_off_ad" class="control-label col-sm-2">* Office Address
						<button type="button" class="btn btn-primary btn-xs" id="btn_off_ad" name="btn_off_ad"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
						</label>
						<input type="hidden" id="office_addr_count" name="office_addr_count" value="1">
						<label class="control-label col-sm-offset-9">Primary</label>
					</div>
				</div>
			</div>

			<div class="row" id="div_row_offc_addr" class="cls_addr">
				<div class="col-sm-12 cls_div_office_addr" id="div_office_addr1" name="div_office_addr1">
					<div class="form-group">
		                <div class="col-sm-3">
		                    <input id="office_add1" name="office_add1" type="text" placeholder="Unit #/BLDG &#x09; Street" class="form-control input-sm cls_office_addr field-required" data-label="oa_addrstreet"  maxlength="500">
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_office_addr cls_city id-required" id="office_brgy_cm_id1" name="office_brgy_cm_id1" >
							<div class="input-group">
								<input type="text" class="form-control input-sm cls_office_addr cls_city auto_suggest field-required limit-chars" list-container="city_list" id="office_brgy_cm1" name="office_brgy_cm1" placeholder="Brgy, City/Municipal" width="100%" data-label="oa_addrcity" maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_office_addr btn-sm autocomplete-toggle" type="button" input-toggle="office_brgy_cm1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_office_addr cls_state id-required" id="office_state_prov_id1" name="office_state_prov_id1" >
							<div class="input-group">
								<input type="text" class="form-control input-sm cls_office_addr auto_suggest field-required limit-chars" list-container="state_list" id="office_state_prov1" name="office_state_prov1" placeholder="State/Province" data-label="oa_addrstate" width="100%" maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_office_addr btn-sm autocomplete-toggle" type="button" input-toggle="office_state_prov1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
		                </div>

		                <div class="col-sm-2">
		                    <input id="office_zip_code1" name="office_zip_code1" type="text" placeholder="Zip Code" class="form-control input-sm cls_office_addr field-required numeric" data-label="oa_addrzipcode" maxlength="20">
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_office_addr cls_country id-required" id="office_country_id1" name="office_country_id1" value="<?php echo $default_country_id; ?>" >
							<div class="input-group">
								<input type="text" class="form-control input-sm cls_office_addr cls_country auto_suggest field-required limit-chars" list-container="country_list" id="office_country1" name="office_country1" value="<?php echo $default_country; ?>" placeholder="Country" data-label="oa_addrcountry" maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_office_addr btn-sm autocomplete-toggle" type="button" input-toggle="office_country1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
		                </div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="office_primary" value="1" checked data-label="oa_addrprimary">
							      <button type="button" class="btn btn-default btn-xs remove_offc_addr"><span class="glyphicon glyphicon-trash"></span></button>
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
						<button type="button" class="btn btn-primary btn-xs" id="btn_factory_addr"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
						</label>
						<input type="hidden" id="factory_addr_count" name="factory_addr_count" value="1">
					</div>
				</div>
			</div>

			<div class="row" id="div_row_factory_addr" class="cls_addr">
				<div class="col-sm-12 cls_div_factory_addr" id="div_factory_addr1" name="div_factory_addr1">
					<div class="form-group">
		                <div class="col-sm-3">
		                    <input id="factory_addr1" name="factory_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street" class="form-control input-sm cls_factory_addr" data-label="fa_addrstreet"  maxlength="500">
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_factory_addr cls_city id-required" id="factory_brgy_cm_id1" name="factory_brgy_cm_id1" >
							<div class="input-group">
								<input type="text" class="form-control input-sm cls_factory_addr cls_city auto_suggest limit-chars" list-container="city_list" id="factory_brgy_cm1" name="factory_brgy_cm1" placeholder="Brgy, City/Municipal" width="100%" autocomplete="off"  data-label="fa_addrcity"   maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_factory_addr btn-sm autocomplete-toggle" type="button" input-toggle="factory_brgy_cm1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_factory_addr cls_state id-required" id="factory_state_prov_id1" name="factory_state_prov_id1" >
							<div class="input-group">
									<input type="text" class="form-control input-sm cls_factory_addr cls_state auto_suggest limit-chars" list-container="state_list" id="factory_state_prov1" name="factory_state_prov1" placeholder="State/Province" data-label="fa_addrstate" width="100%"  maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_factory_addr btn-sm autocomplete-toggle" type="button" input-toggle="factory_state_prov1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-2">
								<input id="factory_zip_code1" name="factory_zip_code1" type="text" placeholder="Zip Code" class="form-control input-sm cls_factory_addr numeric" data-label="fa_addrzipcode"  maxlength="20">
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_factory_addr cls_country id-required" id="factory_country_id1" name="factory_country_id1" value="<?php echo $default_country_id; ?>">
							<div class="input-group">
									<input type="text" class="form-control input-sm cls_factory_addr cls_country auto_suggest limit-chars" list-container="country_list" id="factory_country1" name="factory_country1" value="<?php echo $default_country; ?>" placeholder="Country" data-label="fa_addrcountry"  maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_factory_addr btn-sm autocomplete-toggle" type="button" input-toggle="factory_country1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="factory_primary" value="1" checked data-label="fa_addrprimary">
							      <button type="button" class="btn btn-default btn-xs remove_factory_addr"><span class="glyphicon glyphicon-trash"></span></button>

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
						<button type="button" class="btn btn-primary btn-xs" id="btn_wh_addr"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
						</label>
						<input type="hidden" id="wh_addr_count" name="wh_addr_count" value="1">
					</div>
				</div>
			</div>

			<div class="row" id="div_row_wh_addr" class="cls_addr">
				<div class="col-sm-12 cls_div_wh_addr" id="div_wh_addr1" name="div_wh_addr1">
					<div class="form-group">
		                <div class="col-sm-3">
								<input id="ware_addr1" name="ware_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street" class="form-control input-sm cls_wh_addr limit-chars" data-label="wa_addrstreet"  maxlength="500">
		                </div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_wh_addr cls_city id-required" id="ware_brgy_cm_id1" name="ware_brgy_cm_id1" >
							<div class="input-group">
									<input type="text" class="form-control input-sm cls_wh_addr cls_city auto_suggest limit-chars" list-container="city_list" id="ware_brgy_cm1" name="ware_brgy_cm1" placeholder="Brgy, City/Municipal" width="100%"  data-label="wa_addrcity"  maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_wh_addr btn-sm autocomplete-toggle" type="button" input-toggle="ware_brgy_cm1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-2">
		                	<input type="hidden" class="form-control input-sm cls_wh_addr cls_state id-required" id="ware_state_prov_id1" name="ware_state_prov_id1" >
							<div class="input-group">
								<input type="text" class="form-control input-sm cls_wh_addr cls_state auto_suggest limit-chars" list-container="state_list" id="ware_state_prov1" name="ware_state_prov1" placeholder="State/Province" data-label="wa_addrstate" width="100%"  maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_wh_addr btn-sm autocomplete-toggle" type="button" input-toggle="ware_state_prov1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-2">
								<input id="ware_zip_code1" name="ware_zip_code1" type="text" placeholder="Zip Code" class="form-control input-sm cls_wh_addr numeric" data-label="wa_addrzipcode"  maxlength="20">
		                </div>

		                <div class="col-sm-2">
								<input type="hidden" class="form-control input-sm cls_wh_addr cls_country id-required limit-chars" id="ware_country_id1" name="ware_country_id1" value="<?php echo $default_country_id; ?>" >
							<div class="input-group">
									<input type="text" class="form-control input-sm cls_wh_addr cls_country auto_suggest" list-container="country_list" id="ware_country1" name="ware_country1" value="<?php echo $default_country; ?>" placeholder="Country" data-label="wa_addrcountry"  maxlength="50">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_wh_addr btn-sm autocomplete-toggle" type="button" input-toggle="ware_country1" >
										<span class="caret"></span>
									</button>
								</div>
							</div>
						</div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="ware_primary" value="1" checked data-label="wa_addrprimary">
							      <button type="button" class="btn btn-default btn-xs remove_wh_addr"><span class="glyphicon glyphicon-trash"></span></button>
							</label>
		                </div>
		            </div>
	            </div>
			</div>

			<div class="row">
			<br><br>
				<div class="col-sm-12">
					<!-- <div class="col-sm-6"> -->
						<div class="form-group">
							<label for="tel_no" class="col-sm-2 control-label">* Tel No.</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_telno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12" id="div_telno">
								<input type="hidden" id="telno_count" name="telno_count" value="1">
								<!-- <div class="input-group"> -->
								<div class="form-inline cls_div_telinline" id="div_telinline1" name="div_telinline1">
										<input type="text" class="form-control input-sm cls_telno field-required numeric" id="tel_ccode1" name="tel_ccode1" placeholder="Country Code" data-label="tel_countrycode"  maxlength="100">
										<input type="text" class="form-control input-sm cls_telno field-required numeric" id="tel_acode1" name="tel_acode1" placeholder="Area Code" data-label="tel_areacode"  maxlength="100">
										<input type="text" class="form-control input-sm cls_telno field-required numeric" id="tel_no1" name="tel_no1" placeholder="Tel No" data-label="telno"  maxlength="100">
										<input type="text" class="form-control-2 input-sm cls_telno  numeric" id="tel_elno1" name="tel_elno1" placeholder="Extension/Local Number" data-label="tel_extensionlocalno"  maxlength="100">
									<button type="button" class="btn btn-default btn-xs remove_telno"><span class="glyphicon glyphicon-trash "></span></button>
								</div>
								<!-- </div> -->
							</div>
						</div>
					<!-- </div> -->
				</div>
			</div>
			<div class="row">
			<br>
				<div class="col-sm-12">
					<!-- <div class="col-sm-6"> -->
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label">* Email</label>
							<button type="button" class="btn btn-primary btn-xs"  id="btn_add_email"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12" id="div_email">
								<input type="hidden" id="email_count" name="email_count" value="1">
								<div class="input-group col-sm-9">
									<input type="text" class="form-control input-sm cls_email field-required isEmail limit-chars" id="email1" name="email1" placeholder="Email" data-label="email" maxlength="300">
									<span class="glyphicon glyphicon-trash input-group-addon remove_email"></span>
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
					<!-- <div class="col-sm-6"> -->
						<div class="form-group">
							<label for="fax_no" class="col-sm-2 control-label">* Fax No.</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_faxno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12" id="div_faxno">
								<input type="hidden" id="faxno_count" name="faxno_count" value="1">
								<!-- <div class="input-group"> -->
								<div class="form-inline cls_div_faxinline" id="div_faxinline1" name="div_faxinline1">
										<input type="text" class="form-control input-sm cls_faxno numeric field-required" id="fax_ccode1" name="fax_ccode1" placeholder="Country Code" data-label="fax_countrycode" maxlength="100">
										<input type="text" class="form-control input-sm cls_faxno numeric field-required" id="fax_acode1" name="fax_acode1" placeholder="Area Code" data-label="fax_areacode" maxlength="100">
										<input type="text" class="form-control input-sm cls_faxno numeric field-required" id="fax_no1" name="fax_no1" placeholder="Fax No." data-label="faxno" maxlength="100">
										<input type="text" class="form-control-2 input-sm cls_faxno numeric" id="fax_elno1" name="fax_elno1" placeholder="Extension/Local Number" data-label="fax_extensionlocalno" maxlength="100">
									<button type="button" class="btn btn-default btn-xs remove_faxno"><span class="glyphicon glyphicon-trash"></span></button>
								</div>
								<!-- </div> -->
							</div>
						</div>
					<!-- </div> -->
				</div>
			</div>
			<div class="row">
			<br>
				<div class="col-sm-12">
					<!-- <div class="col-sm-6"> -->
						<div class="form-group">
							<label for="mobile_no" class="col-sm-2 control-label">* Mobile No</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_mobno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12" id="div_mobno">
								<input type="hidden" id="mobno_count" name="mobno_count" value="1">
								<!-- <div class="input-group"> -->
								<div class="form-inline cls_div_mobnoinline" id="div_mobnoinline1" name="div_mobnoinline1">
										<input type="text" class="form-control input-sm cls_mobno numeric field-required" id="mobile_ccode1" name="mobile_ccode1" placeholder="Country Code" data-label="mob_countrycode" maxlength="100">
										<input type="hidden" class="form-control input-sm cls_mobno" id="mobile_acode1" value="" name="mobile_acode1" placeholder="Area Code" data-label="mob_areacode" maxlength="100">
										<input type="text" class="form-control input-sm cls_mobno numeric field-required" id="mobile_no1" name="mobile_no1" placeholder="Mobile No" data-label="mobno" maxlength="100">
									<button type="button" class="btn btn-default btn-xs remove_mobno"><span class="glyphicon glyphicon-trash"></span></button>
								</div>
								<!-- </div> -->
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
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="col-sm-7 control-label">* Owners/Partners/Directors <small>(Max <input type="text" id="opd_max" name="opd_max" value="<?php echo $defaults->max_opd; ?>" style="background:rgba(0,0,0,0);border:none; /*width: 13px;*/height: 15px" readonly>)</small></label>
							<input type="hidden" id="opd_count" name="opd_count" value="1">
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_opd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12">
								<table class="table table-bordered" id="tbl_opd">
									<thead>
										<tr class="info">
											<th>First Name</th>
											<th>Middle Name</th>
											<th>Last Name</th>
											<th>Position</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr id="tr_opd1" class="cls_tr_opd" flop ="1">
												<td><fieldset><input type="text" id="opd_fname1" name="opd_fname1" placeholder="First Name" class="form-control input-sm cls_opd field-required limit-chars" data-label="opd_firstname" maxlength="100"></fieldset></td>
												<td><fieldset><input type="text" id="opd_mname1" name="opd_mname1" placeholder="Middle Name" class="form-control-2 input-sm cls_opd limit-chars" data-label="opd_middlename"  maxlength="100"></fieldset></td>
												<td><fieldset><input type="text" id="opd_lname1" name="opd_lname1" placeholder="Last Name" class="form-control input-sm cls_opd field-required limit-chars" data-label="opd_lastname" maxlength="100"></fieldset></td>
												<td><fieldset><input type="text" id="opd_pos1" name="opd_pos1" placeholder="Position" class="form-control input-sm cls_opd field-required limit-chars" data-label="opd_pos"  maxlength="100"></fieldset></td>
											<td><button type="button" class="btn btn-default btn-xs cls_del_opd"><span class="glyphicon glyphicon-trash"></span></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="col-sm-7 control-label">* Authorized Representatives <small>(Max <input type="text" id="authrep_max" name="authrep_max" value="<?php echo $defaults->max_authrep; ?>" style="background:rgba(0,0,0,0);border:none; width: 6px;height: 15px" readonly>)</small></label>
							<input type="hidden" id="authrep_count" name="authrep_count" value="1">
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_authrep"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12">
								<table class="table table-bordered" id="tbl_authrep" afflop="1">
									<thead>
										<tr class="info">
											<th>First Name</th>
											<th>Middle Name</th>
											<th>Last Name</th>
											<th>Position</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr id="tr_authrep1" class="cls_tr_authrep">
												<td><input type="text" id="authrep_fname1" name="authrep_fname1" placeholder="First Name" class="form-control input-sm cls_authrep field-required limit-chars" data-label="authrep_firstname"  maxlength="100"></td>
												<td><input type="text" id="authrep_mname1" name="authrep_mname1" placeholder="Middle Name" class="form-control-2 input-sm cls_authrep limit-chars" data-label="authrep_middlename"  maxlength="100"></td>
												<td><input type="text" id="authrep_lname1" name="authrep_lname1" placeholder="Last Name" class="form-control input-sm cls_authrep field-required limit-chars" data-label="authrep_lastname"  maxlength="100"></td>
												<td><input type="text" id="authrep_pos1" name="authrep_pos1" placeholder="Position" class="form-control input-sm cls_authrep field-required limit-chars" data-label="authrep_pos"  maxlength="100"></td>
											<td><button type="button" class="btn btn-default btn-xs cls_del_authrep"><span class="glyphicon glyphicon-trash"></span></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- hide daw muna -->
			<!-- <div class="row" style="display: none;">
				<div class="col-sm-12">
					<div class="col-sm-5">
						<div class="form-group">
							<div class="col-sm-12">
								<table class="table table-bordered" id="dept_cat">
									<thead>
										<tr class="info">
											<th>Department/Category</th>
										</tr>
									</thead>
									<tbody>
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
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-sm-1">
						<div class="form-group">
							<br><br><br>
							<button type="button" class="btn btn-default btn-lg " id="btn_move_right"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
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
			<?php //if($vendor_type == 4):?>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-12"  style="overflow: auto;max-height: 250px;">
						
							<table class="table table-bordered" style="margin-bottom:0;">
								<thead>
									<tr class="info">
										<!-- Modified MSF 20191128 (NA) -->
										<!--<th>Categories Supplied</th>-->
										<th>Department Supplied</th>
										<th></th>
									</tr>
								</thead>
							</table>
							<input type="hidden" id="cat_sup_count" name="cat_sup_count" value="0">
							<table class="table table-bordered" id="cat_sup">
								<tbody>
									<tr id="tr_catsup1" class="cls_tr_cat">
										<td>
											<input type="hidden" class="cls_cat" id="category_id1" name="category_id1" value="">
											<span id="category_name1"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			
			<!-- Added MSF - 20191118 (IJR-10618) -->
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-12"  style="overflow: auto;max-height: 250px;">
						
							<table class="table table-bordered" style="margin-bottom:0;">
								<thead>
									<tr class="info">
										<!-- Modified MSF 20191128 (NA) -->
										<!--<th>Sub-Categories Supplied</th>-->
										<th>Sub-Department Supplied</th>
										<th></th>
									</tr>
								</thead>
							</table>
							<input type="hidden" id="sub_cat_sup_count" name="sub_cat_sup_count" value="0">
							<table class="table table-bordered" id="sub_cat_sup">
								<tbody>
									<tr id="tr_sub_catsup1" class="cls_tr_cat">
										<td>
											<input type="hidden" class="cls_cat" id="sub_category_id1" name="sub_category_id1" value="">
											<span id="sub_category_name1"></span>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php //endif;?>
			<div class="row">
				<div class="col-sm-12">
					<br>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="col-sm-4 control-label">* Bank References</label>
							<input type="hidden" id="bankrep_count" name="bankrep_count" value="1">
							<input type="hidden" id="bankrep_max_count" name="bankrep_max_count" value="<?php echo $defaults->max_bank_rep; ?>">
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_bankrep"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12">
								<table class="table table-bordered" id="tbl_bankrep">
									<thead>
										<tr class="info">
											<th>Name</th>
											<th>Branch</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr id="tr_bankrep1" class="cls_tr_bankrep" bflop ="1">
												<td><input type="text" id="bankrep_name1" name="bankrep_name1" placeholder="Name" class="form-control input-sm cls_bankrep field-required limit-chars" data-label="bankref_name"  maxlength="100"></td>
												<td><input type="text" id="bankrep_branch1" name="bankrep_branch1" placeholder="Branch" class="form-control input-sm cls_bankrep field-required limit-chars" data-label="bankref_branch"  maxlength="100"></td>
											<td><button type="button" class="btn btn-default btn-xs cls_del_bankrep"><span class="glyphicon glyphicon-trash"></span></button></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="" class="col-sm-6 control-label">* Other Retail Customers/Clients</label>
							<input type="hidden" id="orcc_count" name="orcc_count" value="1">
							<input type="hidden" id="max_orcc_count" name="max_orcc_count" value="<?php echo $defaults->max_orcc; ?>">
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_orcc"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-12">
								<table class="table table-bordered" id="tbl_orcc">
									<thead>
										<tr class="info">
											<th>Company Name</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr id="tr_orcc1" class="cls_tr_orcc" orflop ="1">
												<td><input type="text" id="orcc_compname1" name="orcc_compname1" placeholder="Company Name" class="form-control input-sm cls_orcc field-required limit-chars" data-label="orcc_compname"  maxlength="100"></td>
											<td><button type="button" class="btn btn-default btn-xs cls_del_orcc"><span class="glyphicon glyphicon-trash"></span></button></td>
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
						<label for="" class="col-sm-2 control-label">* Other Business</label>
						<input type="hidden" id="otherbusiness_count" name="otherbusiness_count" value="1">
						<input type="hidden" id="max_otherbusiness_count" name="max_otherbusiness_count" value="<?php echo $defaults->max_ob; ?>">
						<button type="button" class="btn btn-primary btn-xs" id="btn_add_otherbusiness"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered" id="tbl_otherbusiness">
								<thead>
									<tr class="info">
										<th>Company Name</th>
										<th>Products/Services Offered</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr id="tr_otherbusiness1" class="cls_tr_ob">
											<td><input type="text" id="ob_compname1" name="ob_compname1" placeholder="Company Name" class="form-control input-sm cls_otherbusiness field-required limit-chars" data-label="ob_compname"  maxlength="100"></td>
											<td><input type="text" id="ob_pso1" name="ob_pso1" placeholder="Products/Services Offered" class="form-control input-sm cls_otherbusiness field-required limit-chars" data-label="ob_pso"  maxlength="100"></td>
										<td><button type="button" class="btn btn-default btn-xs cls_del_otherbusiness"><span class="glyphicon glyphicon-trash"></span></button></td>
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
						<label for="" class="col-sm-5 control-label">* Disclosure of Relatives Working in SM or its Affiliates</label>
						<input type="hidden" id="affiliates_count" name="affiliates_count" value="1">
						<input type="hidden" id="max_affiliates_count" name="max_affiliates_count" value="<?php echo $defaults->max_rel; ?>">
						<button type="button" class="btn btn-primary btn-xs" id="btn_add_affiliates"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

						<div class="col-sm-12">
							<table class="table table-bordered" id="tbl_affiliates">
								<thead>
									<tr class="info">
										<th colspan="2">Employee Name</th>
										<th>Position</th>
										<th>Company Affiliated With</th>
										<th>Relationship</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<tr id="tr_affiliates1" class="cls_tr_affiliates" afflop ="1">
											<td><input type="text" id="affiliates_fname1" name="affiliates_fname1" placeholder="First Name" class="form-control input-sm cls_affiliates field-required limit-chars" data-label="affiliates_fname"  maxlength="100"></td>
											<td><input type="text" id="affiliates_lname1" name="affiliates_lname1" placeholder="Last Name" class="form-control input-sm cls_affiliates field-required limit-chars" data-label="affiliates_lname"  maxlength="100"></td>
											<td><input type="text" id="affiliates_pos1" name="affiliates_pos1" placeholder="Position" class="form-control input-sm cls_affiliates field-required limit-chars" data-label="affiliates_pos"  maxlength="100"></td>
											<td><input type="text" id="affiliates_comp_afw1" name="affiliates_comp_afw1" placeholder="Company Affiliated With" class="form-control input-sm cls_affiliates field-required limit-chars" data-label="affiliates_caw"  maxlength="100"></td>
											<td><input type="text" id="affiliates_rel1" name="affiliates_rel1" placeholder="Relationship" class="form-control input-sm cls_affiliates field-required limit-chars" data-label="affiliates_rel"  maxlength="100"></td>
										<td><button type="button" class="btn btn-default btn-xs cls_del_affiliates"><span class="glyphicon glyphicon-trash"></span></button></td>
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
									* Primary Requirements [<input type="text" id="rsd_upload_count" name="rsd_upload_count" value="0" style="background:rgba(0,0,0,0);border:none; width: 10px;height: 15px">/<span id="rsd_count"></span>]
									<div class="pull-right col-sm-5">
											<!--{{#NA}} this is inside of option
												style="display:none;"
											{{/NA}}-->
											<div class="col-sm-9">
												<select id="cbo_rsd_list" name="cbo_rsd_list" class="form-control " data-label="rsd_list">
													<script id="cbo_rsd_template" type="text/template">
														<option value="" disabled selected>-- Select --</option>
														{{#cbo_rsd_template}}
															<option value="{{REQUIRED_DOCUMENT_ID}}">{{REQUIRED_DOCUMENT_NAME}}</option>
														{{/cbo_rsd_template}}
													</script>
												</select>
											</div>

			                			<button class="btn btn-default " type="button" id="btn_rsd_upload"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
		                			</div>
	                			</h3>

							</div>
						</div>
						<div class="panel-body">
							<table id="rsd_tbl" class="table table-bordered">  <!-- id has value of docment id from db while name has a loop value  -->
								<thead>
									<tr class="info">
										<th></th>
										<th>Document</th>
										<?php if( ! empty($waive_rsd_document_chk)):?>
										<th>N/A</th>
										<?php endif;?>
										<th>Sample</th>
										<th>Date Uploaded</th>
										<th>File Name</th>
										<th>Preview</th>
									</tr>
								</thead>
								<tbody id="rsd_body">
									<script id="tbl_rsd_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
										{{#rsd_table_template}}
											<tr>
												<td><input type="checkbox" class="mainchk" id="rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled></td>
												<td><p id="rsd_label{{REQUIRED_DOCUMENT_ID}}">{{REQUIRED_DOCUMENT_NAME}}</p></td>
												<?php if( ! empty($waive_rsd_document_chk)):?>
												 <td><input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" 
													  name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled
														{{#NA}}
															Checked
														{{/NA}}>
													  </td>
												<?php endif; ?>
												<td><button type="button" class="btn btn-default btn-xs preview" value="{{SAMPLE_FILE}}" data-label="rsd_samplefile"><span class="glyphicon glyphicon-sunglasses"></span></button></td>
												<td><input type="text" class="form-control input-sm field-required"
												id="rsd_date_upload{{REQUIRED_DOCUMENT_ID}}" 
												name="rsd_date_upload{{COUNT}}" value="" 
												data-label="rsd_date_upload" readonly
												{{#NA}}
													data-required="checked"
												{{/NA}}></td>
												<td><input type="text" class="form-control input-sm field-required"
												id="rsd_orig_name{{REQUIRED_DOCUMENT_ID}}" name="rsd_orig_name{{COUNT}}" 
												value="" data-label="rsd_origname" readonly
												{{#NA}}
													data-required="checked"
												{{/NA}}></td>
												<td><button type="button" id="btn_rsd_preview{{REQUIRED_DOCUMENT_ID}}" name="btn_rsd_preview{{COUNT}}" class="btn btn-default btn-xs preview" data-label="rsd_file_preview"disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											</tr>
										{{/rsd_table_template}}
									</script>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<!-- </div> -->

			<div class="col-sm-12" id="addtional_req">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="form-group">
								<h3 class="panel-title">
									Additional Requirements [<input type="text" id="ra_upload_count" name="ra_upload_count" value="0" style="background:rgba(0,0,0,0);border:none; width: 10px;height: 15px">/<span id="ra_count"></span>]
									<div class="pull-right col-sm-5">
											<!--{{#NA}} this is inside of option
												style="display:none;"
											{{/NA}}-->
											<div class="col-sm-9">
												<select id="cbo_ra_list" name="cbo_ra_list" class="form-control " data-label="ra_list">
													<script id="cbo_ra_template" type="text/template">
														<option value="" disabled selected>-- Select --</option>
														{{#cbo_ra_template}}
															<option value="{{REQUIRED_AGREEMENT_ID}}">{{REQUIRED_AGREEMENT_NAME}}</option>
														{{/cbo_ra_template}}
													</script>
												</select>
											</div>

			                			<button class="btn btn-default " type="button" id="btn_ra_upload"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
		                			</div>
	                			</h3>

							</div>
						</div>
						<div class="panel-body">
							<table id="ra_tbl" class="table table-bordered"> <!-- id has value of docment id from db while name has a loop value  -->
								<thead>
									<tr class="info">
										<th></th>
										<th>Document</th>
										<?php if( ! empty($waive_ad_document_chk)):?>
										<th>N/A</th>
										<?php endif;?>
										<th>Download/Preview</th>
										<th>Date Uploaded</th>
										<th>File Name</th>
										<th>Preview</th>
									</tr>
								</thead>
								<tbody id="ra_body"><!-- id has value of docment id from db for assigning value while name has a loop value (count) for post -->
									<script id="tbl_ra_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
										{{#ra_table_template}}
											<tr>
												<td><input type="checkbox" class="mainchk" id="ra_document_chk{{REQUIRED_AGREEMENT_ID}}" name="ra_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled></td>
												<td><span><p id="ra_label{{REQUIRED_AGREEMENT_NAME}}">{{REQUIRED_AGREEMENT_NAME}}</p></span></td>
												<?php if( ! empty($waive_ad_document_chk)):?>
												{{#REQUIRED_AGREEMENT_NAME}}
													  <td><input type="checkbox" id="waive_ad_document_chk{{REQUIRED_AGREEMENT_ID}}" 
													  name="waive_ad_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled
														{{#NA}}
															Checked
														{{/NA}}>
													  </td>
												{{/REQUIRED_AGREEMENT_NAME}}
												<?php endif; ?>
												<td>
													<button type="button" class="btn btn-default btn-xs" 
													onclick="download_file('{{SAMPLE_FILE}}')" 
													data-label="ra_dlfile"
													{{#DOWNLOADABLE}}
													disabled
													{{/DOWNLOADABLE}}
													>
													<span class="glyphicon glyphicon-download-alt"></span></button>
													<button type="button" class="btn btn-default btn-xs preview" value="{{SAMPLE_FILE}}" 
													data-label="ra_samplefile"
													{{#VIEWABLE}}
													disabled
													{{/VIEWABLE}}
													><span class="glyphicon glyphicon-sunglasses"></span>
													</button>
												</td>
												<td>
												<input type="text" class="form-control input-sm " 
												id="ra_date_upload{{REQUIRED_AGREEMENT_ID}}" name="ra_date_upload{{COUNT}}" 
												value="" data-label="ra_date_upload" readonly 
												{{#NA}}
													data-required="checked"
												{{/NA}}></td>
												
												<td>
												<input type="text" class="form-control input-sm " 
												id="ra_orig_name{{REQUIRED_AGREEMENT_ID}}" 
												name="ra_orig_name{{COUNT}}" value="" data-label="ra_orig_name" readonly
												{{#NA}}
													data-required="checked"
												{{/NA}}>
												</td>
												<td><button type="button" id="btn_ra_preview{{REQUIRED_AGREEMENT_ID}}" name="btn_ra_preview{{COUNT}}" class="btn btn-default btn-xs preview" data-label="ra_file_preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											</tr>
										{{/ra_table_template}}
									</script>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			<div id="div_chk_certify" class="col-sm-12">
				<input type="checkbox" id="chk_certify" name="chk_certify" onChange="removeClass('#div_chk_certify','div-error');" data-label="certify"> I hereby certify that all the above information and documents are true, complete and correct as to the best of my knowledge.
			</div>
				<!-- Start Modal -->
				<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
								<span class="submit" style="display:none;">
									<center><h4 class="modal-title" id="myModalLabel"><?php echo $dn_title; ?></h4></center>
								</span>
								<span class="incomplete" style="display:none;">
									<h4 class="modal-title" id="myModalLabel">Incomplete Reason</h4>
								</span>
								<span class="dpa_agreement dpa_agreement_sections" style="display:none;">
									<h4 class="modal-title" id="myModalLabel"><?php echo $dpa_title; ?></h4>
								</span>
								<span class="upload_documents" style="display:none;">
									<h4 class="modal-title" id="myModalLabel">Upload Scanned Documents</h4>
								</span>
								<span class="document_preview" style="display:none;">
									<h4 class="modal-title" id="myModalLabel">Preview</h4>
									<!-- Updated MSF - 20191105 (IJR-10612)
									<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
									<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
									-->
									<button type="button" id="zoom_image" >Zoom In</button>
									<button type="button" id="zoom_out_image" >Zoom Out</button>
									<button type="button" id="fit_to_screen" >Fit To Screen</button>
								</span>
							</div>

							<div class="modal-body">
								<div class="container-fluid">
									<span class="submit" style="display:none;">
										<?php echo $dn_message; ?>
									</span>

									<span class="incomplete" style="display:none;">
										<textarea class="form-control" placeholder="Enter Reason" ></textarea>
									</span>
									<span class="dpa_agreement" style="display:none;">
										<div class="panel panel-default">
											<div class="panel-body">
										<?php echo trim($dpa_message).' <button type="button" class="btn btn-link" id="btn_view_dpa_sections" onclick="dpa_agreement_sections()">'.$dpa_link_label.'</button>' ; ?>
											</div>
										</div>
									</span>
									<span class="dpa_agreement_sections_1" style="display:none;">
										<div class="panel panel-default">
											<div class="panel-body">
											<p>
												<?php echo $this->load->view('vendor/dpa_sections'); ?>
											</p>
											</div>
										</div>
									</span>
									<span class="dpa_agreement_sections_2" style="display:none;">
										<div class="panel panel-default">
											<div class="panel-body">
											<p>
												<?php echo $this->load->view('vendor/dpa_sections2'); ?>
											</p>
											</div>
										</div>
									</span>
									<span class="upload_documents" style="display:none;">
										<div>
											<input type="file" id="fileupload" name="fileupload" value="upload" placeholder="Upload Scanned Documents" accept=".pdf,.jpg,.jpeg,.png">
											<input type="hidden" name="valid_file" id="valid_file" value="0">
											<!-- Modified MSF - 20191118 (IJR-10617) -->
											<!-- <i>PDF/Jpeg/PNG format max size 2 MB</i> -->
											<i>PDF/Jpeg/PNG format max size 5 MB</i>
											<div id="upload_result"></div>
										</div>
									</span>
									<span class="document_preview" style="display:none;">
										<!-- <img src="" id="imagepreview" style="width: 400px; height: 264px;" > -->
										<div id='content' style="max-width: 1200px; width: 100%;">
											<div class='frame' id='frame' style="border: 1px solid #ccc; padding: 5px;">
												<img id='image_preview' src='' style="display: none">
												<embed src="" id="pdf_preview" style="width: 100%; height: 100%; display: none;" >
											</div>
										</div>
										<!-- Added MSF - 20191105 (IJR-10612)
										<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
										 -->
									</span>
								</div>
							</div>
							<div class="modal-footer">
								<span class="submit" style="display:none;">
									<center><button type="button" class="btn btn-primary" data-dismiss="modal">OK</button></center>
								</span>
								<span class="incomplete" style="display:none;">
									<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									<button type="button" class="btn btn-primary">Ok</button>
								</span>
								<span class="dpa_agreement_sections_1" style="display:none;">
									<center><button type="button" class="btn btn-primary" onclick="dpa_agreement_show_first()" id="btn_accept_dpa_1" disabled>Continue</button></center>
								</span>
								<span class="dpa_agreement_sections_2" style="display:none;">
									<center><button type="button" class="btn btn-primary" id="btn_accept_dpa" disabled>Continue</button><button type="button" class="btn btn-primary" onclick="end_session('<?=$this->session->userdata('user_id')?>')">Cancel</button></center>

								</span>
								<span class="upload_documents" style="display:none;">
									<button type="button" class="btn btn-primary" id="btn_upload" >Upload</button>
									<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_upload_cancel">Cancel</button>
								</span>
								<span class="document_preview" style="display:none;">
									<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
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
</div>

<script type="text/javascript">



	//$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");
	
	//Use sync instead of async which cause ReferenceError when trying to
	//call function from vendor.js which is not fully loaded.
	$.ajax({
		url: "<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js'); ?>",
		dataType: 'script',
		async: false
	});
	
	$(document).ready(function(){

		//let x = <?php echo $dpa_title; ?>;

		//alert(x);


		$('body').tooltip({
		    selector: '[data-label]',
		    title: hoverGetData,
		    html: true,
		    container: 'body',
			trigger : 'hover'
		});

	});
	var cachedData = Array(); // cache
	function hoverGetData()
	{
		var element = $(this);
		var label 	= element.data('label');

		if (label in cachedData) // if existing in cache return data
		{
			return cachedData[label];
		}

		var local_data = '';

		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/get_tooltip";
        var post_params = "label="+label;

        var success_function = function(responseText)
        {
           local_data = responseText;
        };

        var add_config = {async: false};

        ajax_request(ajax_type, url, post_params, success_function, add_config);

        cachedData[label] = local_data;

        return local_data;
	}

	$(document).on('change','#check_fst',function(){

		if(this.checked){
			$("#btn_accept_dpa_1").prop("disabled",false);
		}else{
			$("#btn_accept_dpa_1").prop("disabled",true);
		}

	});

	function dpa_agreement()
	{



		if ($('#dpa_accept').val() == '' && $('#showdpa').val() == '1')
		{
			$('#myModal').modal({
				backdrop: 'static',
				keyboard: false
			});
			$('#myModal > .modal-dialog').addClass("modal-lg");
			$('#myModal').modal('show');

	        $('#myModal span').hide();
        	$('#myModal .dpa_agreement').show();
		}
	}
	
	function dpa_agreement_sections()
	{
		if ($('#dpa_accept').val() == '' && $('#showdpa').val() == '1')
		{
	        $('#myModal span').hide();
        	$('#myModal .dpa_agreement_sections_1').show();
		}
	}

	function dpa_agreement_show_first()
	{


		$('#myModal span').hide();
        $('#myModal .dpa_agreement_sections_1').hide();
        $('#myModal span').hide();
        $('#myModal .dpa_agreement_sections_2').show();

	}
	
	function dpa_sections_check_changed()
	{
		var count = $( ".dpa_sections_consent" ).length;
		var checked_count = $( ".dpa_sections_consent:checked" ).length;
		
		if (checked_count==count) {
			$("#btn_accept_dpa").prop("disabled",false);
		} else {
			$("#btn_accept_dpa").prop("disabled",true);
		}
	}

	$(window).on('beforeunload', unload);
    $(window).on('unload', unload);  
	function unload()
	{      
        if ($('#dpa_accept').val() == '' && $('#showdpa').val() == '1')
		{
			var ajax_type = 'GET';
			var url = BASE_URL + "login/logout";

			var success_function = function(responseText)
			{
				// do nothing
			};

			var add_config = {async: false};

			ajax_request(ajax_type, url, null, success_function, add_config);
		}
    }

	var rsd_tbl_doc_template = $('#tbl_rsd_template').html();
	var rsd_cbo_template 	 = $('#cbo_rsd_template').html();
	var ra_tbl_agree_template = $('#tbl_ra_template').html();
	var ra_cbo_template 	 = $('#cbo_ra_template').html();

	function get_list_docs(ownership = '', trade_vendor_type = '',category_id = '', vendor_type = '')
	{


		//console.log(catid);
		//return;

		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/get_list_docs";
        var post_params = "ownership="+ownership+"&trade_vendor_type="+trade_vendor_type+"&cat_id="+category_id+"&invite_id="+$("#invite_id").val()+"&vendor_type="+vendor_type;

        var success_function = function(responseText)
        {

     
        	//console.log(responseText);
        	//return;
            var tbl_data = $.parseJSON(responseText);

            let l = '';
            l = tbl_data.ra;

          

            var counter = 0;
            var counter2 = 0;

            if(l != "-1"){

            	(tbl_data.rsd).map(function(row_obj) {
					counter++
					row_obj.COUNT = counter;
				});
            	(tbl_data.ra).map(function(row_obj) {
					counter2++
					row_obj.COUNT = counter2;
				});

			var DATA = {
               rsd_table_template: tbl_data.rsd,
               cbo_rsd_template: tbl_data.rsd,
               ra_table_template: tbl_data.ra,
               cbo_ra_template: tbl_data.ra       	
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
            }


           
   



            $('#rsd_body').html(Mustache.render(rsd_tbl_doc_template, DATA));
            $('#cbo_rsd_list').html(Mustache.render(rsd_cbo_template, DATA));
            $('#ra_body').html(Mustache.render(ra_tbl_agree_template, DATA));
            $('#cbo_ra_list').html(Mustache.render(ra_cbo_template, DATA));

            //set count
            $('#rsd_count').html(counter);
            $('#ra_count').html(counter2);
           // $('#tbl_pag').html(responseText);
           // load if has existing data
           // load_vendor_data();
        };

        return ajax_request(ajax_type, url, post_params, success_function);
	}

	function load_vendor_data()
	{
		loadingScreen('on');
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/get_vendor_data";
        var post_params = "invite_id="+$('#invite_id').val();

        var success_function = function(responseText)
        {
            //console.log(responseText);
//            return;
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
			// Added MSF - 20191118 (IJR-10618)
			var sub_cat_template 			= $('#tr_sub_catsup1').clone();
			$('#cat_sup tbody').html(''); // reset
			// Added MSF - 20191118 (IJR-10618)
			$('#sub_cat_sup tbody').html(''); // reset

            if (responseText != 0)
            {
            	var data = $.parseJSON(responseText);

            	// load vendor data
            	if (data.count_vreg > 0)
            	{
            		$('#cbo_yr_business').val(data.rs_vreg[0].YEAR_IN_BUSINESS);
					$('input:radio[name=no_of_employee][value=' + data.rs_vreg[0].EMPLOYEE + ']').prop('checked', true);
					$('input:radio[name=business_asset][value=' + data.rs_vreg[0].BUSINESS_ASSET + ']').prop('checked', true);
					$('input:radio[name=ownership][value=' + data.rs_vreg[0].OWNERSHIP_TYPE + ']').prop('checked', true);
					$('input:radio[name=vendor_type][value=' + data.rs_vreg[0].VENDOR_TYPE + ']').prop('checked', true);
					$('input:radio[name=trade_vendor_type][value=' + data.rs_vreg[0].TRADE_VENDOR_TYPE + ']').prop('checked', true);
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
							reset_attr('input-toggle','cls_brand','brand_count',j,'div_brandid');
							$('#div_brand').find("#" + "brand_name" + j).removeClass('ui-autocomplete-input');
							$('#div_brand').find("#" + "brand_name" + j).removeAttr('auto_suggest_flag');

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
						if (!$('#div_office_addr'+j).length) // element not exists create element and write value
						{
							var new_div = offce_template.clone().attr({'id':'div_office_addr'+j, 'name':'div_office_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_offc_addr').append(new_div).find('#div_office_addr'+j+' :input').val(''); // append and reset value
					        $('#div_office_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_office_addr','office_addr_count',j,'div_office_addr'); // reset id of elements inside div_brandid+j

							reset_attr('input-toggle','cls_office_addr','office_addr_count',j,'div_office_addr');
							$("#" + "office_brgy_cm" + j).removeClass('ui-autocomplete-input');
							$("#" + "office_state_prov" + j).removeClass('ui-autocomplete-input');
							$("#" + "office_country" + j).removeClass('ui-autocomplete-input');
						}

						$('#office_add'+j).val(data.rs_vaddr_office[i].ADDRESS_LINE);
						$('#office_brgy_cm_id'+j).val(data.rs_vaddr_office[i].BRGY_MUNICIPALITY_ID);
						$('#office_brgy_cm'+j).val(data.rs_vaddr_office[i].CITY_NAME);
						//$('#office_state_prov_id'+j).val(data.rs_vaddr_office[i].STATE_PROVINCE_ID);
						//uncomment if may tinamaan
						if(data.rs_vreg[0].STATUS_ID != 9 &&
							data.rs_vreg[0].STATUS_ID != 11){
							$('#office_state_prov_id'+j).val(data.rs_vaddr_office[i].STATE_PROVINCE_ID);
						}

						$('#office_state_prov'+j).val(data.rs_vaddr_office[i].STATE_PROV_NAME);
						$('#office_zip_code'+j).val(data.rs_vaddr_office[i].ZIP_CODE);
						$('#office_country_id'+j).val(data.rs_vaddr_office[i].COUNTRY_ID);
						$('#office_country'+j).val(data.rs_vaddr_office[i].COUNTRY_NAME);

						// set primary
						if (data.rs_vaddr_office[i].PRIMARY == 1)
							$('input:radio[name=office_primary][value=' + j + ']').prop('checked', true);
					}
				}

				if (data.count_vaddr_factory > 0)
				{
					$('#factory_addr_count').val(data.count_vaddr_factory);
					for (var i = 0, j = 1; i < data.count_vaddr_factory; i++, j++) // i is for object, j is for element
					{
						if (!$('#div_factory_addr'+j).length) // element not exists create element and write value
						{
							var new_div = factory_addr_template.clone().attr({'id':'div_factory_addr'+j, 'name':'div_factory_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_factory_addr').append(new_div).find('#div_factory_addr'+j+' :input').val(''); // append and reset value
					        $('#div_factory_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_factory_addr','factory_addr_count',j,'div_factory_addr'); // reset id of elements inside div_brandid+j

							reset_attr('input-toggle','cls_factory_addr','factory_addr_count',j,'div_factory_addr');
							$("#div_factory_addr").find("#" + "factory_brgy_cm" + j).removeClass('ui-autocomplete-input');
							$("#div_factory_addr").find("#" + "factory_state_prov" + j).removeClass('ui-autocomplete-input');
							$("#div_factory_addr").find("#" + "factory_country" + j).removeClass('ui-autocomplete-input');
						}

						$('#factory_addr'+j).val(data.rs_vaddr_factory[i].ADDRESS_LINE);
						$('#factory_brgy_cm_id'+j).val(data.rs_vaddr_factory[i].BRGY_MUNICIPALITY_ID);
						$('#factory_brgy_cm'+j).val(data.rs_vaddr_factory[i].CITY_NAME);
						$('#factory_state_prov_id'+j).val(data.rs_vaddr_factory[i].STATE_PROVINCE_ID);
						$('#factory_state_prov'+j).val(data.rs_vaddr_factory[i].STATE_PROV_NAME);
						$('#factory_zip_code'+j).val(data.rs_vaddr_factory[i].ZIP_CODE);
						$('#factory_country_id'+j).val(data.rs_vaddr_factory[i].COUNTRY_ID);
						$('#factory_country'+j).val(data.rs_vaddr_factory[i].COUNTRY_NAME);

						// set primary
						if (data.rs_vaddr_factory[i].PRIMARY == 1)
							$('input:radio[name=factory_primary][value=' + j + ']').prop('checked', true);
					}
				}

				if (data.count_vaddr_warehouse > 0)
				{
					$('#wh_addr_count').val(data.count_vaddr_warehouse);
					for (var i = 0, j = 1; i < data.count_vaddr_warehouse; i++, j++) // i is for object, j is for element
					{
						if (!$('#div_wh_addr'+j).length) // element not exists create element and write value
						{
							var new_div = wh_addr_template.clone().attr({'id':'div_wh_addr'+j, 'name':'div_wh_addr'+j});
					        new_div.find(':radio').prop('checked',false); // un check clone
					        $('#div_row_wh_addr').append(new_div).find('#div_wh_addr'+j+' :input').val(''); // append and reset value
					        $('#div_wh_addr'+j+' :radio').prop('value',j);
					        reset_ids('cls_wh_addr','wh_addr_count',j,'div_wh_addr'); // reset id of elements inside div_brandid+j

							reset_attr('input-toggle','cls_wh_addr','wh_addr_count',j,'div_wh_addr');
							$("#div_wh_addr").find("#" + "ware_brgy_cm" + j).removeClass('ui-autocomplete-input');
							$("#div_wh_addr").find("#" + "ware_state_prov" + j).removeClass('ui-autocomplete-input');
							$("#div_wh_addr").find("#" + "ware_country" + j).removeClass('ui-autocomplete-input');
						}


						$('#ware_addr'+j).val(data.rs_vaddr_warehouse[i].ADDRESS_LINE);
						$('#ware_brgy_cm_id'+j).val(data.rs_vaddr_warehouse[i].BRGY_MUNICIPALITY_ID);
						$('#ware_brgy_cm'+j).val(data.rs_vaddr_warehouse[i].CITY_NAME);
						$('#ware_state_prov_id'+j).val(data.rs_vaddr_warehouse[i].STATE_PROVINCE_ID);
						$('#ware_state_prov'+j).val(data.rs_vaddr_warehouse[i].STATE_PROV_NAME);
						$('#ware_zip_code'+j).val(data.rs_vaddr_warehouse[i].ZIP_CODE);
						$('#ware_country_id'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_ID);
						$('#ware_country'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_NAME);
						// set primary
						if (data.rs_vaddr_warehouse[i].PRIMARY == 1)
							$('input:radio[name=ware_primary][value=' + j + ']').prop('checked', true);
					}
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
							// $('#div_mobno').append(mobno_template.clone()).find('input:last').attr({'id':'mobile_no'+j, 'name':'mobile_no'+j, 'value': ''}).val(''); // append and reset value
							$('#div_mobno').append(mobno_template.clone().attr({'id':'div_mobnoinline'+j, 'name':'div_mobnoinline'+j})).find('#div_mobnoinline'+j+' :input').val(''); // append and reset value
							reset_ids('cls_mobno','mobno_count',j,'div_mobnoinline');
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
						}

					}

				}
				// end Owners/Partners/Directors
				//Authorized Representatives
				if (data.count_vauthrep > 0)
				{
					$('#authrep_count').val(data.count_vauthrep);
					for (var i = 0, j = 1; i < data.count_vauthrep; i++, j++) // i is for object, j is for element
					{
						if ($('#tr_authrep'+j).length) // if exists write value
						{
							$('#authrep_fname'+j).val(data.rs_vauthrep[i].FIRST_NAME);
							$('#authrep_mname'+j).val(data.rs_vauthrep[i].MIDDLE_NAME);
							$('#authrep_lname'+j).val(data.rs_vauthrep[i].LAST_NAME);
							$('#authrep_pos'+j).val(data.rs_vauthrep[i].POSITION);
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
				
				var vendor_type = $('input:radio[name=vendor_type]:checked').val();
	            var trade_vendor_type   = '';

	            if ($("input[name='ownership']:checked").length > 0)
	                ownership = $("input[name='ownership']:checked").val();

				if(vendor_type == 1){
					trade_vendor_type = $("input[name='trade_vendor_type']:checked").val();
				}
	            /*if ($("input[name='trade_vendor_type']:checked").length > 0){
	                trade_vendor_type = 1; //$("input[name='trade_vendor_type']:checked").val();
				}else{
					trade_vendor_type = $("input[name='vendor_type']:checked").val();
					
					if(trade_vendor_type == 3){
						trade_vendor_type = 4; // 4 = NTS 
					}
				}*/


				//console.log(trade_vendor_type);
	            // cetegories START
				if (data.count_cat > 0)
				{
					for (var i = 0, j = 1; i < data.count_cat; i++, j++) // i is for object, j is for element
					{
						var new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
						new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': data.rs_cat[i].CATEGORY_ID});
						new_row.find('span').attr('id','category_name'+j);

						if (!$('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length)
				            $('#cat_sup tbody').append(new_row.clone());
						$('#category_name'+j).html(data.rs_cat[i].CATEGORY_NAME);
					}
				}

				// cetegories END

				// Added MSF - 20191118 (IJR-10618)
				// sub-category start
				
				if (data.count_sub_cat > 0)
				{
					for (var i = 0, j = 1; i < data.count_sub_cat; i++, j++) // i is for object, j is for element
					{
						var new_row = sub_cat_template.attr({'id':'tr_sub_catsup'+j,'class':'cls_tr_cat'});
						new_row.find(':input').attr({'id':'sub_category_id'+j, 'name':'sub_category_id'+j, 'value': data.rs_sub_cat[i].SUB_CATEGORY_ID});
						new_row.find('span').attr('id','sub_category_name'+j);

						if (!$('#sub_cat_sup tbody :input[value='+data.rs_sub_cat[i].SUB_CATEGORY_ID+']').length)
				            $('#sub_cat_sup tbody').append(new_row.clone());
						$('#sub_category_name'+j).html(data.rs_sub_cat[i].SUB_CATEGORY_NAME);
					}
				}

				// sub-category end


	            $(document).ready(function(){
	            let cat_sup = [];	
					$('#cat_sup input[type=hidden]').each(function(){
						cat_sup.push(this.value);
						//console.log(this);
					});

	          		
				
	            get_list_docs(ownership, trade_vendor_type,cat_sup, vendor_type).done(function(){


					// Required Scanned Documents
					if (data.count_vreqdoc > 0)
					{
						for (var i = 0, j = 1; i < data.count_vreqdoc; i++, j++)
						{
							$('#rsd_document_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);
							$('#rsd_date_upload'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_CREATED);
							$('#rsd_orig_name'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].ORIGINAL_FILENAME);
							$('#btn_rsd_preview'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].FILE_PATH).prop('disabled', false);
						}
						$('#rsd_upload_count').val(data.count_vreqdoc);
					}
					// end Required Scanned Documents
					// Required Agreements
					if (data.count_vagree > 0)
					{
						//console.log(data.rs_vagree);
						for (var i = 0, j = 1; i < data.count_vagree; i++, j++)
						{
							$('#ra_document_chk'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
							$('#ra_date_upload'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_CREATED);
							$('#ra_orig_name'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].ORIGINAL_FILENAME);
							$('#btn_ra_preview'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].FILE_PATH).prop('disabled', false);
						}
						$('#ra_upload_count').val(data.count_vagree);
					}
					// end Required Agreements
				});


				//adjust width of max values
    			$('#opd_max').css('width', (($('#opd_max').val().length) * 8)+'px');
    			$('#authrep_max').css('width', (($('#authrep_max').val().length) * 8)+'px');
    			$('#rsd_upload_count').css('width', (($('#rsd_upload_count').val().length) * 8)+'px');
    			$('#ra_upload_count').css('width', (($('#ra_upload_count').val().length) * 8)+'px');
             }) }


            check_status();
            loadingScreen('off');
        };

        ajax_request(ajax_type, url, post_params, success_function);
	}

	dpa_agreement();
	load_vendor_data();




/*var unloaded = false;
//test_return
$(window).on('beforeunload', unload);
$(window).on('unload', unload);  
function unload(){
alert(1);      
    if(!unloaded){
        $('body').css('cursor','wait');
        $.ajax({
            type: 'get',
            async: false,
            url: "<?php echo base_url().'users/test_return'?>",
            success:function(result){ 
            	alert(result);
                unloaded = true; 
                $('body').css('cursor','default');
            },
            timeout: 5000
        });
    }
}*/


</script>
