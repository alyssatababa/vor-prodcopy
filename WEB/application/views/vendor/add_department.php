<?php 
//echo '<pre>'; 
//print_r($this->session->all_userdata()); 
//var_dump($this->_ci_cached_vars);
if($invite_id != ''){
	$draft = 'disabled';
	$txt_approver_note = "";
}else{
	$draft = '';
	$txt_approver_note = "For Add Department";
}
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

<!-- Style for autocompletion -->
.autocomplete {
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  background-color: #e9e9e9;
}
.autocomplete-active {
  background-color: DodgerBlue !important;
  color: #ffffff;
}

</style>

<link href="<?php echo base_url().'assets/css/jquery.guillotine.css'; ?>" media='all' rel='stylesheet'>
<script src="<?php echo base_url().'assets/js/jquery.guillotine.js'; ?>"></script>


<form id="frm_adddepartment" name="frm_adddepartment" method="post" class="form-horizontal" enctype="multipart/form-data">
<div class="container mycontainer">
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<span class="search_vendor" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Select Vendor</h4>
					</span>
											
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="upload_documents" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Upload Scanned Documents</h4>
					</span>
				</div>
				<div class="modal-body">
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="search_vendor" style="display:none;">						
						<center>
							<div>
								<input type="textbox" id="search_vendor" name="search_vendor" style="width:450px;">
								<button type="button" class="btn btn-default" name="btnSelectVendor" id="btnSelectVendor" onclick="selectVendor();">Submit</button>
								<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_select_cancel">Cancel</button>
							</div>
						</center>
					</span>
												
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="upload_documents" style="display:none;">
						<div>
							<input type="file" id="fileupload" name="fileupload" value="upload" placeholder="Upload Scanned Documents" accept=".pdf,.jpg,.jpeg,.png">
							<input type="hidden" name="valid_file" id="valid_file" value="0">
							<i>PDF/Jpeg/PNG format max size 5 MB</i>
							<div id="upload_result"></div>
						</div>
					</span>
				</div>
				<div class="modal-footer">				
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="upload_documents" style="display:none;">
						<button type="button" class="btn btn-primary" id="btn_upload" >Upload</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_upload_cancel">Cancel</button>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="pull-right">
		<!--
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_print">Print</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_approve">Approve</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_reject">Reject</button>
		</div>
		-->
		<!-- 
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_draft" name="btn_draft">Save as Draft</button>
		</div>
		-->
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_submit" name="btn_submit">Submit</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary btn-exit">Exit</button>
		</div>
	</div>
	
	<h4><span id="page_header" name="page_header">Add Department</span></h4>
	<div class="form_container">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="vendor_name" class="control-label">  Vendor Name : </label>
						</div>
						<div class="col-sm-8">
							<div class="input-group">
								<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_invite_id" name="vendor_invite_id" value="<?php echo $invite_id; ?>" readonly>
								<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_id" name="vendor_id" readonly>
								<input type="text" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_name" name="vendor_name" readonly>
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_brand btn-sm" type="button" input-toggle="vendor_name" id="btnSearchVendor" <?php echo $draft; ?>>
										<span class="glyphicon glyphicon-search"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Vendor Code : </label>
						</div>
						
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="vendor_code" name="vendor_code" width="100%" maxlength="50" readonly>
							<input type="hidden" class="form-control input-sm limit-chars" style="width:100%" id="sub_vendor_code" name="sub_vendor_code" width="100%" maxlength="50" readonly>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Vendor Type : </label>
						</div>
						
						<div class="col-sm-8">
							<!-- <input type="text" class="form-control input-sm limit-chars" style="width:100%" id="vendor_type" name="vendor_type" width="100%" maxlength="50" readonly> -->
							<input type="hidden" id="multiple_vc" name="multiple_vc">
							<input type="hidden" id="main_vt" name="main_vt">
							<input type="hidden" id="sub_vt" name="sub_vt">
							<input type="hidden" id="temp_dept" name="temp_dept">
							<input type="hidden" id="temp_sub_dept" name="temp_sub_dept">
							<select class="form-control" name="vendor_type" id="vendor_type" style="width:100%" width="100%">
								<option value="1">Outright</option>
								<option value="2">Store Consignor</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Existing Department : </label>
						</div>
						
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" readonly>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Existing Sub Dept : </label>
						</div>
						
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm limit-chars" style="width:100%" id="e_sdept_name" name="e_sdept_name" width="100%" maxlength="50" readonly>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="dept_name1" class="control-label">  Add Department : </label>
						</div>
						<div class="col-sm-8" id="div_a_dept">
							<input type="hidden" id="count_a_dept" name="count_a_dept" value="1">
							<div class="input-group cls_dept" id="div_a_dept1">
								<?php
								//print_r($category_list);
								$tmps_dept = array();		
								
								$counter = 0;
								foreach ($category_list as $key) {
									$tmps_dept[$key->CATEGORY_ID] = $key->CATEGORY_NAME;
									$counter += 1;
								}				
								
								asort($tmps_dept);
								?>
								
								<?=form_dropdown('a_dept1', $tmps_dept,'', ' id="a_dept1" class="col-sm-3 form-control field-required" onchange="add_s_dept()"')?>
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_brand btn-sm" type="button" input-toggle="dept_name1" id="btn_add_dept">
										<span class="glyphicon glyphicon-plus"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="sub_dept_name1" class="control-label">  Add Sub Department : </label>
						</div>
						<div class="col-sm-8" id="div_a_sub_dept">
							<input type="hidden" id="count_a_sub_dept" name="count_a_sub_dept" value="1">
							<div class="input-group cls_sub_dept" id="div_a_sub_dept1">
								<select id="a_sub_dept1" name="a_sub_dept1" class="col-sm-3 form-control">
								</select>
								
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_brand btn-sm" type="button" input-toggle="sub_dept_name1" id="btn_add_sub_dept">
										<span class="glyphicon glyphicon-plus"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="brand_name1" class="control-label">  Add Brand : </label>
						</div>
						<div class="col-sm-8" id="div_a_brand">
							<input type="hidden" id="count_a_brand" name="count_a_brand" value="1">
							<div class="input-group cls_brand_frm" id="div_a_brand1">
								<input type="text" class="form-control input-sm" list-container="brand_list" id="a_brand1" name="a_brand1">
								<div class="input-group-btn">
									<button tabindex="-1" class="btn btn-default cls_brand btn-sm" type="button" input-toggle="sub_dept_name1" id="btn_add_brand">
										<span class="glyphicon glyphicon-plus"></span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
								
				<div class="col-sm-12">
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<div class="form-group">
						<div class="col-sm-2">
							<label for="approved_items" class="control-label">  Approved Items : </label>
						</div>
						
						<div class="col-md-3">
							<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly style="width:245px;" value = "">
							<input id="txt_file_path" name="txt_file_path" type="hidden" value="">
						</div>
						<div class="col-md-3">
							<input class="form-control input-sm field-required limit-chars" id="txt_date_upload" name="txt_date_upload" type="text" readonly style="width:245px;" value="">
						</div>
						<div class="col-md-4">	
							<button class="btn btn-default " type="button" id="btn_invite_upload"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
						</div>
					</div>

					<!-- Additional upload file -->

					<div class="form-group">
						<div class="col-sm-2">
							<label for="approved_items" class="control-label"> </label>
						</div>
						
						<div class="col-md-3">
							<input class="form-control input-sm limit-chars" id="txt_approve_items2" name="txt_approve_items2" type="text" readonly style="width:245px;" value = "">
							<input id="txt_file_path2" name="txt_file_path2" type="hidden" value="">
						</div>
						<div class="col-md-3">
							<input class="form-control input-sm limit-chars" id="txt_date_upload2" name="txt_date_upload2" type="text" readonly style="width:245px;" value="">
						</div>
						<!-- <div class="col-md-4">	
							<button class="btn btn-default " type="button" id="btn_invite_upload2"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>
						</div> -->
					</div>
				</div>
				
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Payment Terms : </label>
						</div>
						
						<div class="col-sm-8">
						<?php
						$tmps = array();		
						
						foreach ($payment_terms as $key => $value) {
							$tmps[$key] = $value;
						}				
						
						asort($tmps);
						?>
						
						<?=form_dropdown('terms_payment', $tmps,$termspayment, ' id="terms_payment" class="col-sm-3 form-control" disabled')?>
						<input type="hidden" id="sub_tp" name="sub_tp">
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Note to Approver : </label>
						</div>
						<div class="col-md-10">	
							<textarea class="form-control" rows="4" id="txt_approver_note" name="txt_approver_note" maxlength="300"><?php echo $txt_approver_note; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
</form>

<script>

    $('#btnSearchVendor').unbind().on('click',function(){
        //$('#btn_upload').val('3');
        //$('#btn_upload').prop('disabled', false);

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .search_vendor').show();
    });
	
	$('#vendor_type').change(function(){
		var temp_data = $('#vendor_code').val();
		$('#vendor_code').val($('#sub_vendor_code').val());
		$('#sub_vendor_code').val(temp_data);
		
		var temp_tp = $('select[name=terms_payment] option').filter(':selected').val();
		$('#terms_payment').prop('disabled',false);
		$('#terms_payment option[value='+temp_tp+']').removeAttr("selected");
		$('#terms_payment option[value='+$('#sub_tp').val()+']').attr("selected", "selected");
		$('#terms_payment').prop('disabled',true);
		//$("terms_payment").val($('#sub_tp').val());
		$('#sub_tp').val(temp_tp);
		
		var temp_dept = $('#e_dept_name').val();
		$('#e_dept_name').val($('#temp_dept').val());
		$('#temp_dept').val(temp_dept);
		
		var temp_sub_dept = $('#e_sdept_name').val();
		$('#e_sdept_name').val($('#temp_sub_dept').val());
		$('#temp_sub_dept').val(temp_sub_dept);
		
	});
	
	function selectVendor(){
		var vendor_name = document.getElementById('search_vendor').value;
		
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/invitecreation/get_vendor_info/";
		var post_params = "vendor_name="+encodeURIComponent(vendor_name);
		
		var success_function = function(responseText)
		{
			var rs = $.parseJSON(responseText);
			if(rs[0].status == true){
				var category = [];
				var sub_category = [];
				var avc_category = [];
				var avc_sub_category = [];
				var span_message = "Vendor Found.";
				var type = "modal_success";
				var now = new Date();
				var hour = now.getHours();
				$('#vendor_name').val(rs[0].VENDOR_NAME);
				$('#vendor_type').prop('selected', false);
				
				var category_counter = 0;
				var sub_category_counter = 0;
				var avc_category_counter = 0;
				var avc_sub_category_counter = 0;

				if(rs[0].DATE_DIFF <= 0){
					var span_message = "Vendor has pending registration.";
					var type = "modal_danger";
					modal_notify($('#myModal'), span_message, type);
					return;
				}else if(rs[0].DATE_DIFF == 1){
					if(hour < 9){
						var span_message = "Vendor has pending registration.";
						var type = "modal_danger";
						modal_notify($('#myModal'), span_message, type);
						return;
					}
				}

				if(rs[0].REGISTRATION_TYPE == 4 || rs[0].PREV_REGISTRATION_TYPE == 4){
					$('#multiple_vc').val('Y');
					if(rs[0].TRADE_VENDOR_TYPE == 2){
						$('select[name^="vendor_type"] option[value="'+2+'"]').attr("selected","selected");
						$('#main_vt').val(1);
						$('#sub_vt').val(2);
					}else{
						$('select[name^="vendor_type"] option[value="'+1+'"]').attr("selected","selected");
						$('#main_vt').val(2);
						$('#sub_vt').val(1);
					}
					
					$('#vendor_code').val(rs[0].VENDOR_CODE_02);
					$('#sub_vendor_code').val(rs[0].VENDOR_CODE);
					
					var temp_tp = $('select[name=terms_payment] option').filter(':selected').val();
					$('#terms_payment option[value='+temp_tp+']').removeAttr("selected");
					$('#terms_payment option[value='+rs[0].AVC_TERMSPAYMENT+']').attr("selected","selected");
					$('#sub_tp').val(rs[0].TERMSPAYMENT);
				
					for (var i = 0; i<rs.length; i++){
						var category_name_checker = category.includes(rs[i].CATEGORY_NAME);
						if(category_name_checker == false){
							category.push(rs[i].CATEGORY_NAME);
							if(category_counter == 0){
								if(rs[i].CATEGORY_NAME != null){
									$('#temp_dept').val(rs[i].CATEGORY_NAME);
									category_counter += 1;
								}
							}else{
								if(rs[i].CATEGORY_NAME != null){
									$('#temp_dept').val($('#temp_dept').val() + ", " + rs[i].CATEGORY_NAME);
									category_counter += 1;
								}
							}
						}
					}
					
					for (var a = 0; a<rs.length; a++){
						var sub_category_name_checker = sub_category.includes(rs[a].SUB_CATEGORY_NAME);
						if(sub_category_name_checker == false){
							sub_category.push(rs[a].SUB_CATEGORY_NAME);
							if(sub_category_counter == 0){
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#temp_sub_dept').val(rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}else{
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#temp_sub_dept').val($('#temp_sub_dept').val() + ", " + rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}
						}
					}
					
					for (var i = 0; i<rs.length; i++){
						var avc_category_name_checker = avc_category.includes(rs[i].AVC_CATEGORY_NAME);
						if(avc_category_name_checker == false){
							avc_category.push(rs[i].AVC_CATEGORY_NAME);
							if(avc_category_counter == 0){
								if(rs[i].AVC_CATEGORY_NAME != null){
									$('#e_dept_name').val(rs[i].AVC_CATEGORY_NAME);	
									avc_category_counter += 1;
								}
							}else{
								if(rs[i].AVC_CATEGORY_NAME != null){
									$('#e_dept_name').val($('#e_dept_name').val() + ", " + rs[i].AVC_CATEGORY_NAME);
									avc_category_counter += 1;
								}
							}
						}
					}
					
					for (var a = 0; a<rs.length; a++){
						var avc_sub_category_name_checker = avc_sub_category.includes(rs[a].AVC_SUB_CATEGORY_NAME);
						if(avc_sub_category_name_checker == false){
							avc_sub_category.push(rs[a].AVC_SUB_CATEGORY_NAME);
							if(avc_sub_category_counter == 0){
								if(rs[a].AVC_SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val(rs[a].AVC_SUB_CATEGORY_NAME);
									avc_sub_category_counter += 1;
								}
							}else{
								if(rs[a].AVC_SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val($('#e_sdept_name').val() + ", " + rs[a].AVC_SUB_CATEGORY_NAME);
									avc_sub_category_counter += 1;
								}
							}
						}
					}

				}else if(rs[0].VENDOR_CODE_02 != null && rs[0].VENDOR_CODE_02 != ''){
					$('#multiple_vc').val('Y');
					if(rs[0].TRADE_VENDOR_TYPE == 2){
						//$('select[name^="vendor_type"] option[value="'+2+'"]').attr("selected","selected");
						$('#vendor_type').val(2);
						$('#main_vt').val(2);
						$('#sub_vt').val(1);
					}else{
						//$('select[name^="vendor_type"] option[value="'+1+'"]').attr("selected","selected");
						$('#vendor_type').val(1);
						$('#main_vt').val(1);
						$('#sub_vt').val(2);
					}
					
					$('#vendor_code').val(rs[0].VENDOR_CODE);
					$('#sub_vendor_code').val(rs[0].VENDOR_CODE_02);
					
					var temp_tp = $('select[name=terms_payment] option').filter(':selected').val();
					$('#terms_payment option[value='+temp_tp+']').removeAttr("selected");
					$('#terms_payment option[value='+rs[0].TERMSPAYMENT+']').attr("selected","selected");
					$('#sub_tp').val(rs[0].AVC_TERMSPAYMENT);
				
					for (var i = 0; i<rs.length; i++){
						var category_name_checker = category.includes(rs[i].CATEGORY_NAME);
						if(category_name_checker == false){
							category.push(rs[i].CATEGORY_NAME);
							if(category_counter == 0){
								if(rs[i].CATEGORY_NAME != null){
									$('#e_dept_name').val(rs[i].CATEGORY_NAME);
									category_counter += 1;
								}
							}else{
								if(rs[i].CATEGORY_NAME != null){
									$('#e_dept_name').val($('#e_dept_name').val() + ", " + rs[i].CATEGORY_NAME);
									category_counter += 1;
								}
							}
						}
					}
					
					for (var a = 0; a<rs.length; a++){
						var sub_category_name_checker = sub_category.includes(rs[a].SUB_CATEGORY_NAME);
						if(sub_category_name_checker == false){
							sub_category.push(rs[a].SUB_CATEGORY_NAME);
							if(sub_category_counter == 0){
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val(rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}else{
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val($('#e_sdept_name').val() + ", " + rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}
						}
					}
					
					for (var i = 0; i<rs.length; i++){
						var avc_category_name_checker = avc_category.includes(rs[i].AVC_CATEGORY_NAME);
						if(avc_category_name_checker == false){
							avc_category.push(rs[i].AVC_CATEGORY_NAME);
							if(avc_category_counter == 0){
								if(rs[i].AVC_CATEGORY_NAME != null){
									$('#temp_dept').val(rs[i].AVC_CATEGORY_NAME);	
									avc_category_counter += 1;
								}
							}else{
								if(rs[i].AVC_CATEGORY_NAME != null){
									$('#temp_dept').val($('#temp_dept').val() + ", " + rs[i].AVC_CATEGORY_NAME);
									avc_category_counter += 1;
								}
							}
						}
					}
					
					for (var a = 0; a<rs.length; a++){
						var avc_sub_category_name_checker = avc_sub_category.includes(rs[a].AVC_SUB_CATEGORY_NAME);
						if(avc_sub_category_name_checker == false){
							avc_sub_category.push(rs[a].AVC_SUB_CATEGORY_NAME);
							if(avc_sub_category_counter == 0){
								if(rs[a].AVC_SUB_CATEGORY_NAME != null){
									$('#temp_sub_dept').val(rs[a].AVC_SUB_CATEGORY_NAME);
									avc_sub_category_counter += 1;
								}
							}else{
								if(rs[a].AVC_SUB_CATEGORY_NAME != null){
									$('#temp_sub_dept').val($('#temp_sub_dept').val() + ", " + rs[a].AVC_SUB_CATEGORY_NAME);
									avc_sub_category_counter += 1;
								}
							}
						}
					}
				}else{
					$('select[name^="vendor_type"] option[value="'+rs[0].TRADE_VENDOR_TYPE+'"]').attr("selected","selected");
					$('#multiple_vc').val('N');
					$('#main_vt').val(rs[0].TRADE_VENDOR_TYPE);
					$('#terms_payment').val(rs[0].TERMSPAYMENT);
					$('#vendor_type option:not(:selected)').attr('disabled', true);		
					$('#vendor_code').val(rs[0].VENDOR_CODE);


					for (var i = 0; i<rs.length; i++){
						var category_name_checker = category.includes(rs[i].CATEGORY_NAME);
						if(category_name_checker == false){
							category.push(rs[i].CATEGORY_NAME);
							if(category_counter == 0){
								if(rs[i].CATEGORY_NAME != null){
									$('#e_dept_name').val(rs[i].CATEGORY_NAME);	
									category_counter += 1;
								}
							}else{
								if(rs[i].CATEGORY_NAME != null){
									$('#e_dept_name').val($('#e_dept_name').val() + ", " + rs[i].CATEGORY_NAME);
									category_counter += 1;
								}
							}
						}
					}
					
					for (var a = 0; a<rs.length; a++){
						var sub_category_name_checker = sub_category.includes(rs[a].SUB_CATEGORY_NAME);
						if(sub_category_name_checker == false){
							sub_category.push(rs[a].SUB_CATEGORY_NAME);
							if(sub_category_counter == 0){
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val(rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}else{
								if(rs[a].SUB_CATEGORY_NAME != null){
									$('#e_sdept_name').val($('#e_sdept_name').val() + ", " + rs[a].SUB_CATEGORY_NAME);
									sub_category_counter += 1;
								}
							}
						}
					}
				}
				
				$('#vendor_invite_id').val(rs[0].VENDOR_INVITE_ID);
				$('#vendor_id').val(rs[0].VENDOR_ID);
				
				$('#myModal').modal('hide');
				
			}else{
				var span_message = "Vendor does not exist.";
				var type = "modal_danger";
			}
			modal_notify($('#myModal'), span_message, type);
			return;
		};

		ajax_request(ajax_type, url, post_params, success_function);
	}
	
	var countries = [];
	
	$(document).ready(function(){
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/invitecreation/get_completed_vendors/";
		var post_params;
		
		var success_function = function(responseText)
		{
			var rs = $.parseJSON(responseText);
			
			for (var i = 0; i<rs.resultcount; i++){
				countries.push(rs.query[i].VENDOR_NAME);
			}
		};
		ajax_request(ajax_type, url, post_params, success_function);
	});
	
	autocomplete(document.getElementById("search_vendor"), countries);
	
	function autocomplete(inp, arr) {
		
		/*the autocomplete function takes two arguments,
		the text field element and an array of possible autocompleted values:*/
		var currentFocus;
		
		/*execute a function when someone writes in the text field:*/
		inp.addEventListener("input", function(e) {
			var a, b, i, val = this.value;
			
			/*close any already open lists of autocompleted values*/
			closeAllLists();
			if (!val) { return false;}
			currentFocus = -1;
			
			/*create a DIV element that will contain the items (values):*/
			a = document.createElement("DIV");
			a.setAttribute("id", this.id + "autocomplete-list");
			a.setAttribute("class", "autocomplete-items");
			
			/*append the DIV element as a child of the autocomplete container:*/
			this.parentNode.appendChild(a);
			
			/*for each item in the array...*/
			for (i = 0; i < arr.length; i++) {
				/*check if the item starts with the same letters as the text field value:*/
				if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					/*create a DIV element for each matching element:*/
					b = document.createElement("DIV");
					
					/*make the matching letters bold:*/
					b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					b.innerHTML += arr[i].substr(val.length);
					
					/*insert a input field that will hold the current array item's value:*/
					b.innerHTML += '<input type="hidden" value="' + arr[i].replace(/'/g, "\'") + '">';
					
					/*execute a function when someone clicks on the item value (DIV element):*/
					b.addEventListener("click", function(e) {
						/*insert the value for the autocomplete text field:*/
						inp.value = this.getElementsByTagName("input")[0].value;
						/*close the list of autocompleted values,
						(or any other open lists of autocompleted values:*/
						closeAllLists();
					});
					a.appendChild(b);
				}
			}
		});
	  
		/*execute a function presses a key on the keyboard:*/
		inp.addEventListener("keydown", function(e) {
			var x = document.getElementById(this.id + "autocomplete-list");
			if (x) x = x.getElementsByTagName("div");
			if (e.keyCode == 40) {
				/*If the arrow DOWN key is pressed,
				increase the currentFocus variable:*/
				currentFocus++;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 38) { //up
				/*If the arrow UP key is pressed,
				decrease the currentFocus variable:*/
				currentFocus--;
				/*and and make the current item more visible:*/
				addActive(x);
			} else if (e.keyCode == 13) {
			
				/*If the ENTER key is pressed, prevent the form from being submitted,*/
				e.preventDefault();
				
				if (currentFocus > -1) {
					/*and simulate a click on the "active" item:*/
					if (x) x[currentFocus].click();
				}
			}
		});
	  
		function addActive(x) {
			/*a function to classify an item as "active":*/
			if (!x) return false;
			
			/*start by removing the "active" class on all items:*/
			removeActive(x);
			if (currentFocus >= x.length) currentFocus = 0;
			if (currentFocus < 0) currentFocus = (x.length - 1);
			
			/*add class "autocomplete-active":*/
			x[currentFocus].classList.add("autocomplete-active");
		}
	  
		function removeActive(x) {
			/*a function to remove the "active" class from all autocomplete items:*/
			for (var i = 0; i < x.length; i++) {
				x[i].classList.remove("autocomplete-active");
			}
		}
		
		function closeAllLists(elmnt) {
			/*close all autocomplete lists in the document,
			except the one passed as an argument:*/
			var x = document.getElementsByClassName("autocomplete-items");
			for (var i = 0; i < x.length; i++) {
				if (elmnt != x[i] && elmnt != inp) {
					x[i].parentNode.removeChild(x[i]);
				}
			}
		}
	
		/*execute a function when someone clicks in the document:*/
		document.addEventListener("click", function (e) {
			closeAllLists(e.target);
		});
	}
	
	var brand_template = $('#div_a_brand1'); // .closest('div');

    $('#btn_add_brand').off().on('click', function(){
        var count       = $('#count_a_brand').val();
        count++;
		
		var brand_div = brand_template.clone().attr({'id':'div_a_brand'+count});
		$('#div_a_brand').append(brand_div);
		brand_div.find('input').attr('name','a_brand'+count);
		brand_div.find('input').attr('id','a_brand'+count);
		brand_div.find('span').attr('class','glyphicon glyphicon-minus');
		brand_div.find('button').attr('id','btn_delete_dept');
		brand_div.find('button').attr('name','btn_delete_dept');
		brand_div.find('button').attr('onclick','remove_div('+count+',"count_a_brand","div_a_brand","cls_brand_frm","a_brand",true);');
        reset_ids('cls_brand_frm','count_a_brand','a_brand','','',count,'div_a_brand');
		$('#count_a_brand').val(count);
    });
	
    var dept_template = $('#div_a_dept1'); // .closest('div');

    $('#btn_add_dept').off().on('click', function(){
        var count       = $('#count_a_dept').val();
        count++;
		
		var new_div = dept_template.clone().attr({'id':'div_a_dept'+count});
		$('#div_a_dept').append(new_div);
		new_div.find('select[id]').attr('name','a_dept'+count);
		new_div.find('select[id]').attr('id','a_dept'+count);
		new_div.find('span').attr('class','glyphicon glyphicon-minus');
		new_div.find('button').attr('id','btn_delete_dept');
		new_div.find('button').attr('name','btn_delete_dept');
		new_div.find('button').attr('onclick','remove_div('+count+',"count_a_dept","div_a_dept","cls_dept","a_dept");');
        reset_ids('cls_dept','count_a_dept','a_dept','','',count,'div_a_dept');
		$('#count_a_dept').val(count);
    });
	
    var sub_dept_template = $('#div_a_sub_dept1'); // .closest('div');
	
	$('#btn_add_sub_dept').off().on('click', function(){
        var count = $('#count_a_sub_dept').val();
        count++;
		
		var new_sub_div = sub_dept_template.clone().attr({'id':'div_a_sub_dept'+count});
		$('#div_a_sub_dept').append(new_sub_div);
		new_sub_div.find('select[id]').attr('name','a_sub_dept'+count);
		new_sub_div.find('select[id]').attr('id','a_sub_dept'+count);
		new_sub_div.find('span').attr('class','glyphicon glyphicon-minus');
		new_sub_div.find('button').attr('id','btn_delete_sub_dept');
		new_sub_div.find('button').attr('name','btn_delete_sub_dept');
		new_sub_div.find('button').attr('onclick','remove_div('+count+',"count_a_sub_dept","div_a_sub_dept","cls_sub_dept","a_sub_dept");');
        reset_ids('cls_sub_dept','count_a_sub_dept','a_sub_dept','','',count,'div_a_sub_dept');
		$('#count_a_sub_dept').val(count);
    });
	
	function remove_div(number,counter,div,cls,btn,is_textbox){
		$('#'+div+number).remove();
        var count = $('#'+counter).val();
        count = count - 1;
		$('#'+counter).val(count);
		
		for (var i = 1; i <= count; i++)
		{
			reset_ids(cls,counter,btn,div,cls,null,null,is_textbox);
		}
		
	}
	
	function reset_ids(class_name, update_count, btn, div = null , cls = null, id_num = null, div_id = null, is_textbox = false)
	{
		var count = 0;
		var counter = 0;
		if (div_id != null){
			div_id = '#'+div_id+id_num;
		}else{
			div_id = '';
		}
		

		$(div_id+' .'+class_name).each(function(i) {

			var id = $(this).attr('id');
			var name = $(this).attr('name');
		   
			if (id_num != null)
			{
				if (id)
					id = id.replace(/\d+/g, id_num);
				if (name)
					name = name.replace(/\d+/g, id_num);
			
				if(is_textbox == false){
					$(this).find('select[id]').attr('name',btn+id_num);
					$(this).find('select[id]').attr('id',btn+id_num);
					$(this).find('button').attr('onclick','remove_div('+id_num+',"'+update_count+'","'+div+'","'+cls+'","'+btn+'");');
				}else{
					$(this).find('input[id]').attr('name',btn+id_num);
					$(this).find('input[id]').attr('id',btn+id_num);
					$(this).find('button').attr('onclick','remove_div('+id_num+',"'+update_count+'","'+div+'","'+cls+'","'+btn+'");');
				}
			}
			else
			{
				counter = i+1;
				if (id)
					id = id.replace(/\d+/g, counter);
				if (name)
					name = name.replace(/\d+/g, counter);
			
				if(counter != 1){
					if(is_textbox == false){
						$(this).find('select[id]').attr('name',btn+counter);
						$(this).find('select[id]').attr('id',btn+counter);
						$(this).find('button').attr('onclick','remove_div('+counter+',"'+update_count+'","'+div+'","'+cls+'","'+btn+'");');	
					}else{
						$(this).find('input[id]').attr('name',btn+counter);
						$(this).find('input[id]').attr('id',btn+counter);
						$(this).find('button').attr('onclick','remove_div('+counter+',"'+update_count+'","'+div+'","'+cls+'","'+btn+'",true);');	
					}
				}
			}

			$(this).attr({
				'id': id,
				'name': name

			});
			
			count++;
		});		

		if (id_num == null)
		$('#'+update_count).val(count);
	}

	function add_s_dept(){
		//Get All Category ID
		var category_count = $('#count_a_dept').val();
		var category_ids = [];
		for(var x=1; x<=category_count; x++){
			var checker = category_ids.includes($('#a_dept'+x).val());
			if(checker){
				
			}else{
				category_ids.push($('#a_dept'+x).val());
			}
		}
		
		var sub_category_count = $('#count_a_sub_dept').val();
		var sub_category_ids = [];
		for(var x=1; x<=sub_category_count; x++){
			$('#a_sub_dept'+x).empty();
		}
		
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/invitecreation/get_sub_cat/";
		var post_params = "cat_id="+category_ids;

		var success_function = function(responseText)
		{
			var formoption = "";
			var rs = $.parseJSON(responseText);
			$.each(rs, function(v){
				var val = rs[v];
				formoption += "<option value='" + val.SUB_CATEGORY_ID + "'>" + val.SUB_CATEGORY_NAME + "</option>";
			});
			
			for(var x=1; x<=sub_category_count; x++){
				$('#a_sub_dept'+x).html(formoption);
			}
		};    

		ajax_request(ajax_type, url, post_params, success_function);
		
	}
	
	
    $('#btn_draft').on('click', function() {
            disable_enable_frm('frm_adddepartment', true, '.exclude');
            var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_add_department(2,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_adddepartment\', false, \'.exclude\');">No</button>';
            var type = 'info';
            notify(span_message, type, true);
	});
	
    $('#btn_submit').on('click', function() {
		if (!validateForm())
        {
          var span_message = 'Please fill up all fields!';
          var type = 'danger';
          notify(span_message, type);
          return;
        }else{
            disable_enable_frm('frm_adddepartment', true, '.exclude');
            var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_add_department(1,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_adddepartment\', false, \'.exclude\');">No</button>';
            var type = 'info';
            notify(span_message, type, true);
		}
	});
	
	function save_add_department(status,btn){
		loading($(btn), 'in_progress');
		disable_enable_frm('frm_adddepartment', false);

		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/add_department/vendor_add_department/";
		var post_params = $('#frm_adddepartment').serialize()+
                '&status='+status;
		var success_function = function(responseText)
		{
			//console.log(responseText);
			//return;
			
            refresh_session();

            var span_message = 'Additional department successfully saved.';
            var type = 'success';
            notify(span_message, type);
            $('#frm_adddepartment')[0].reset(); // reset fields after success
			
			//if(status == 1){
			//	var action_path = BASE_URL + 'vendor/add_department';
			//}else{
			//	var action_path = BASE_URL + 'vendor/add_department/index/'+$('#vendor_invite_id').val();
			//}
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
		}
		
		ajax_request(ajax_type, url, post_params, success_function);
	}
	
	add_s_dept();
	load_vendor_data();
	
	function load_vendor_data(){		
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/invitecreation/get_vendor_info_dept/";
        var post_params = "invite_id="+$('#vendor_invite_id').val();
		
		var success_function = function(responseText)
		{
			var rs = $.parseJSON(responseText);
			//console.log(rs);
			if(rs[0].status == true){
				var category = [];
				var sub_category = [];
				var span_message = "Vendor Found.";
				var type = "modal_success";
				$('#vendor_name').val(rs[0].VENDOR_NAME);
				
				if(rs[0].VENDOR_TYPE == 1){
					$('#vendor_type').val('Trade');
				}else if(rs[0].VENDOR_TYPE == 2){
					$('#vendor_type').val('Non Trade');
				}else if(rs[0].VENDOR_TYPE == 3){
					$('#vendor_type').val('Non Trade Services');
				}
				
				$('#vendor_invite_id').val(rs[0].VENDOR_INVITE_ID);
				$('#vendor_id').val(rs[0].VENDOR_ID);
				$('#vendor_code').val(rs[0].VENDOR_CODE);
				$('#terms_payment').val(rs[0].TERMSPAYMENT);
				
				var category_counter = 0;
				for (var i = 0; i<rs.length; i++){
					if(rs[i].VENDOR_TYPE == null){
						continue;
					}
					var category_name_checker = category.includes(rs[i].CATEGORY_NAME);
					if(category_name_checker == false){
						category.push(rs[i].CATEGORY_NAME)
						if(category_counter == 0){
							$('#e_dept_name').val(rs[i].CATEGORY_NAME);
						}else{
							$('#e_dept_name').val($('#e_dept_name').val() + ", " + rs[i].CATEGORY_NAME);
						}
					}
					category_counter += 1;
				}
				
				var sub_category_counter = 0;
				for (var a = 0; a<rs.length; a++){
					if(rs[a].VENDOR_TYPE == null){
						continue;
					}
					var sub_category_name_checker = sub_category.includes(rs[a].SUB_CATEGORY_NAME);
					if(sub_category_name_checker == false){
						sub_category.push(rs[a].SUB_CATEGORY_NAME)
						if(sub_category_counter == 0){
							$('#e_sdept_name').val(rs[a].SUB_CATEGORY_NAME);
						}else{
							if(rs[a].SUB_CATEGORY_NAME != null){
								$('#e_sdept_name').val($('#e_sdept_name').val() + ", " + rs[a].SUB_CATEGORY_NAME);
							}
						}
					}
					sub_category_counter += 1;
				}
				
				var first = 0;
				var second = 0;
				var third = 0;
				
				// Draft Brand
				var brand = [];
				for (var x = 0; x<rs.length; x++){
					if(rs[x].VENDOR_TYPE != null){
						continue;
					}
					
					// Approver's note
					$('#txt_approver_note').val(rs[x].APPROVER_NOTE);
					
					if((rs.length - x) > 0 && first == 0){
						$('#a_brand1').val(rs[x].BRAND_NAME);
						brand.push(rs[x].BRAND_NAME);
						first = 2;
					}else if((rs.length - x) > 0 ){
						if((brand.includes(rs[x].BRAND_NAME)) == false){
							$('#btn_add_brand').trigger('click');
							$('#a_brand'+first).val(rs[x].BRAND_NAME);
							first = first + 1;
							brand.push(rs[x].BRAND_NAME);
						}
					}
				}
				
				var dept = [];
				for (var x = 0; x<rs.length; x++){
					if(rs[x].VENDOR_TYPE != null){
						continue;
					}
					
					if((rs.length - x) > 0 && second == 0){
						$('#a_dept1').val(rs[x].CATEGORY_ID);
						dept.push(rs[x].CATEGORY_ID);
						second = 2;
					}else if((rs.length - x) > 0 ){
						if((dept.includes(rs[x].CATEGORY_ID)) == false){
							$('#btn_add_dept').trigger('click');
							$('#a_dept'+second).val(rs[x].CATEGORY_ID);
							dept.push(rs[x].CATEGORY_ID);
							second = second + 1;
						}
					}
				}
				
				var sub_dept = [];
				for (var x = 0; x<rs.length; x++){
					if(rs[x].VENDOR_TYPE != null){
						continue;
					}
					
					if((rs.length - x) > 0 && third == 0){
						sub_dept.push(rs[x].SUB_CATEGORY_ID);
						third = 2;
					}else if((rs.length - x) > 0 ){
						if((sub_dept.includes(rs[x].SUB_CATEGORY_ID)) == false){
							$('#btn_add_sub_dept').trigger('click');
							sub_dept.push(rs[x].SUB_CATEGORY_ID);
						}
					}
				}
					
				add_s_dept();
				
			}
		};

		ajax_request(ajax_type, url, post_params, success_function);
	}
	
	// Added MSF - 20191108 (IJR-10617)
    // for invite - approve items
    $('#btn_invite_upload').unbind().on('click',function(){
        $('#btn_upload').val('3');
        $('#btn_upload').prop('disabled', false);

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .upload_documents').show();
			
        $("#fileupload").val("");
    });
	
	$(document).on("click", ".btn_upload_no, .close", function(){
		$("#btn_upload").removeAttr("disabled");
		$("#fileupload").removeAttr("disabled");
		$(".btn_upload_no").removeAttr("disabled");
	});
	
    $('#btn_upload').on('click', function(){
	    if ($('#fileupload').val() != ''){
		
            if($('#valid_file').val() == 1){
				var val = this.value;
				var span_message = 'Are you sure you want to upload this file? <button type="button" class="btn btn-success" onclick="upload_file(' + val +',this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default btn_upload_no" id="close_alert">No</button>';
				var type = 'modal_info';
				modal_notify($("#myModal"), span_message, type, true);
			}
            else{
			    modal_notify($("#myModal"), 'The uploaded file exceeds the maximum allowed size of 5 MB', "modal_danger");
			}
        } else{
			modal_notify($("#myModal"), 'You did not select a file to upload.', "modal_danger");
		}
    });

    $('#fileupload').bind('change', function() {
        if (this.files[0].size <= 5242880){
            $('#valid_file').val('1');
        }else
            $('#valid_file').val('0');
    });
	
	function upload_file(type, upload_button) // type = 1 Documents , 2 Agreements , 3 Approved Items [Added MSF - 20191105 (IJR-10617)]
	{
		loading($(upload_button), 'in_progress');
		$(".btn_upload_no").attr("disabled","disabled");
		$("#btn_upload").attr("disabled","disabled");
		var surl = BASE_URL + "vendor/registration/upload_file/" + type;
		
		// Added MSF - 20191105 (IJR-10617)
		
		var frm_document;

		frm_document = document.frm_adddepartment;
		
		// Modified MSF - 20191105 (IJR-10617)
		//upload_ajax_modal(document.frm_registration, surl).done(function(responseText) {
		upload_ajax_modal(frm_document, surl).done(function(responseText) {
			$('#upload_result').html(responseText);
			var type_name = ''
			if ($('#error').val() == '')
			{	
				modal_notify($("#myModal"), 'File uploaded successfully.', "modal_success");
				
				if (type == 1)
					type_name = 'rsd';
				else if (type == 2)
					type_name = 'ra';
				// Added MSF - 20191105 (IJR-10617)
				else if (type == 3)
					type_name = 'ai';
				else if (type == 4)
					type_name = 'ccn';

				var sysdate = $('#sysdate').data("rel");
				$("#upload_date").val(sysdate);
				//console.log("upload_date " + $("#upload_date").val());

				var id      = $('#cbo_'+type_name+'_list').val();
				var date    = $('#upload_date').val();
				var file    = $('#file_path').val();
				var orgname = $('#orig_name').val().toString();
				var ext 	= orgname.slice((Math.max(0, orgname.lastIndexOf(".")) || Infinity) + 1);
				
				if(orgname.length > 170){
					
					var re = orgname.length - (ext.length + 1);
					if(re >= 170){
						re = 170;
					}
					orgname = orgname.slice(0,re) + "." + ext;
								
					if(orgname.length > 200){
						orgname = orgname.slice(0,201);
					}
				}
				
				// Added MSF - 20191105 (IJR-10617)
				if(type != 3){
					// add 1 to counter
					if (!$('#'+type_name+'_document_chk'+id).is(':checked'))
					{
						var count = +$('#'+type_name+'_upload_count').val() + 1;
						$('#'+type_name+'_upload_count').val(count);
					}
					$('#'+type_name+'_document_chk'+id).prop('checked', true);
					$('#'+type_name+'_date_upload'+id).val(date);
					$('#'+type_name+'_orig_name'+id).val(orgname);
					//alert(orgname);
					$('#btn_'+type_name+'_preview'+id).data('path',file);// set data-path of button
					$('#btn_'+type_name+'_preview'+id).prop('disabled', false);
					$('#btn_'+type_name+'_preview'+id).val(file);
					$('#'+type_name+'_document_chk'+id).prop('checked', true);
					//$('#fileupload').val('');// set value to empty for new upload
				// Added MSF - 20191105 (IJR-10617)
				}else{
					if($('#txt_approve_items').val() == ''){
						$('#txt_approve_items').val(orgname);
						$('#txt_file_path').val(file);
						$('#txt_date_upload').val(date);

						$('#btn_invite_view').data('path',file);
						$('#btn_invite_view').val(file);
					}else{
						$('#txt_approve_items2').val(orgname);
						$('#txt_file_path2').val(file);
						$('#txt_date_upload2').val(date);

						$('#btn_invite_view2').data('path',file);
						$('#btn_invite_view2').val(file);
					}
				}
				
				setTimeout(function(){
					loading($(upload_button), 'done');
					$(".btn_upload_no").removeAttr("disabled");
					$("#btn_upload").removeAttr("disabled");
					$("#fileupload").removeAttr("disabled");
					$("#btn_upload_cancel").removeAttr("disabled");
					
				// Added MSF - 20191105 (IJR-10617)
					
					$('#myModal').modal('hide');
				}, 2200);
			}
			else
			{
				loading($(upload_button), 'done');
				
				$("#btn_upload").removeAttr("disabled");
				$("#fileupload").removeAttr("disabled");
				
				
				$("#btn_upload_no").removeAttr("disabled");
				modal_notify($("#myModal"), $('#error').val(), "modal_danger");
			}

		});
	}
</script>
