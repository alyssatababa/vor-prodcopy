<?php //echo "<pre>"; $var = get_defined_vars(); print_r($var); echo "</pre>"; ?>
<!-- Added MSF - 20191108 (IJR-10617) -->
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
<!-- Start Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<span class="vi_approval_history" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Approval History</h4>
				</span>
				<span class="via_reject" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Reason</h4>
				</span>
				
				<!-- Added MSF - 20191108 (IJR-10617) -->
				<span class="document_preview" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Preview</h4>
					<button type="button" id="zoom_image">Zoom In</button>
					<button type="button" id="zoom_out_image">Zoom Out</button>
					<button type="button" id="fit_to_screen" >Fit To Screen</button>
					<button type="button" id="btn_download" onclick="downloadImg()">Download</button>
				</span>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
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
												<td>{{{APPROVER_REMARKS}}}</td>
											</tr>
										{{/table_history}}
									</script>
								</tbody>
							</table>

						</div>
					</span>

					<span class="via_reject" style="display:none;">
						<textarea class="form-control limit-chars" placeholder="Please specify reason here" id="via_remarks" style="height: 100px" maxlength="3000"></textarea>
					</span>
					
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="document_preview" style="display:none;">
						<!-- <embed src="" id="imagepreview" style="width: 100%; height: 100%;" > -->
						<!-- <iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>-->
						
						<div id='content' style="max-width: 1200px; width: 100%;">
							<div class='frame' id='frame' style="border: 1px solid #ccc; padding: 5px;">
								<img id='image_preview' src='' style="display: none">
								<embed src="" id="pdf_preview" style="width: 100%; height: 100%; display: none;" >
							</div>
						</div>
					</span>
				</div>
			</div>
			<div class="modal-footer">
			<span class="vi_approval_history" style="display:none;">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</span>

			<span class="via_reject" style="display:none;">
				<button type="button" class="btn btn-primary" id="via_reject_m">Ok</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</span>
			
			<!-- Added MSF - 20191108 (IJR-10617) -->
			<span class="document_preview" style="display:none;">
				<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
			</span>
			</div>
		</div>
	</div>
</div>
<!-- END Modal -->


	<div class="container mycontainer">



				<div class="row">
					<div class="col-md-6"><h4>Vendor Invite Approval - <span id="spn_vendorname"></span></h4>
					<a href="#" id="vi_approval_history"> <span class="small">Approval History</a></a></div>
					<!-- <div class="col-md-6" style="display:<?= ($view_only == 1 && $status_id != 3 && $status_id != 6) ? 'none':'inline';?>;"> -->
					<div class="col-md-6">
						<span class="pull-right">
							<?php 
							$enabledID = [1, 3, 4, 5, 6, 7, 241, 246, 242, 244, 11, 191];
							if(($registration_type == 1) || ($registration_type == 5)){
								if(in_array($status_id,$enabledID)){
									if($position_id == 2 || $position_id == 11){
										echo('<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delModal">Delete</button>');
									}
								}
							} 
							?>
							<?php if(!($view_only == 1 && $status_id != 3 && $status_id != 6)): ?>
								<?php if($status_id != 3 && $status_id != 6):?>
									<button type="button" class="btn btn-primary" id="via_approve">Approve</button>
									<button type="button" class="btn btn-primary" id="via_reject">Reject</button>
									<button type="button" class="btn btn-primary btn-exit">Exit</button>
								<?php else: ?>
									<button type="button" class="btn btn-primary" id="resend_email">Resend Email</button>
								<?php endif; ?>
							<?php endif; ?>
						</span>
					</div>
					
					<?php if($position_id == 1 || $position_id == 11): ?>
					<div class="col-md-6" style="display:none;">
						<span class="pull-right">
							<button type="button" class="btn btn-danger" id="delete_vendor">Delete</button>
						</span>
					</div>
					<?php endif; ?>
					
					<?php

					$dsb = 'disabled';
					$_status = array(1,2,3,4,5,6,7,8,9,10,11,13,14,15,16,17,122);

					if(in_array($status, $_status)){

						$dsb = '';
					}

					?>





				</div>
				<div class="spacer"></div>
				<div class="form_container">
					<div class="panel panel-default">
						<div class="panel-body">

							<form id="frm_inviteapproval" class="form-horizontal">
								<input type="hidden" id="invite_id" name="invite_id" value="<?php echo $invite_id; ?>">
								<input type="hidden" id="reg_type_id" name="reg_type_id" value="">
								<input type="hidden" id="status_id" name="status_id" value="">
								<div class="form-group">
									<label for="txt_vendorname" class="small col-md-2"><span class="pull-right">Vendor Name</span></label>
									<div class="col-md-6">
										<input class="form-control input-sm" id="txt_vendorname" name="txt_vendorname" type="text" value="" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="txt_contact_person" class="small col-md-2"><span class="pull-right">Contact Person</span></label>
									<div class="col-md-6">
										<input class="form-control input-sm" id="txt_contact_person" name="txt_contact_person" type="text" value="" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="txt_email" class="small col-md-2"><span class="pull-right">Email</span></label>
									
									<!-- Update MSF - 20191105 (IJR-10612)
									<div class="col-md-6">
										<input class="form-control input-sm" id="txt_email" name="txt_email" type="text" value="" readonly>
									</div>
									-->
									<div class="col-md-6">
									<?php if(($status_id == 3 || $status_id == 6) && ($position_id == 2 || $position_id == 11)):?>
											<input class="form-control input-sm" id="txt_email" name="txt_email" type="text" value="">
										<?php else: ?>
											<input class="form-control input-sm" id="txt_email" name="txt_email" type="text" value="" readonly>
										<?php endif; ?>
									</div>
									<?php if(($status_id == 3 || $status_id == 6) && ($position_id == 2 || $position_id == 11)):?>
										<button type="button" class="btn btn-primary" id="update_email">Update Email</button>
									<?php endif; ?>
								</div>
								
								<!-- Added MSF - 20191108 (IJR-10617) -->
								<div class="form-group">
									<label for="txt_email" class="small col-md-2"><span class="pull-right">Approved Items / Project</span></label>
									<div class="col-md-6">
										<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $original_file_name; ?>">
										<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $file_path; ?>">
									</div>
									
									<?php if($file_path != ''): ?>
										<button class="btn btn-default" type="button" id="btn_invite_view" name="btn_invite_view" value="<?php echo $file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
									<?php endif; ?>
								</div>
								
								<?php if($business_type_vendor == 1 || $business_type_vendor == 2): ?>
								<div class="form-group" style="display: <?php echo ($business_type_vendor == 1 ? 'block' : 'none') ?>">
									<label for="txt_contact_person" class="small col-md-2"><span class="pull-right">Trade Vendor Type <?php echo $business_type_vendor; ?></span></label>
									<div class="col-md-6">
										<label class="radio-inline">
									      <input type="radio" name="rad_trade_vendor_type" value="1" class="<?php echo ($business_type == 1 ? 'field-required' : '') ?>" disabled>Outright
									    </label>
									    <label class="radio-inline">
									      <input type="radio" name="rad_trade_vendor_type" value="2" class="<?php echo ($business_type == 1 ? 'field-required' : '') ?>" disabled>Consignor
									    </label>
									</div>
								</div>
								<?php endif; ?>
								<?php //if($business_type_vendor == 3): ?>
								<div class="form-group">
									<div class="col-md-6 col-md-offset-2">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<!-- Modified MSF 20191128 (NA) -->
												<!--<h3 class="panel-title">Category</h3>-->
												<h3 class="panel-title">Department</h3>
											</div>
											<div class="panel-body">
												<table class="table table-bordered">
													<thead>
														<tr class="info">
															<!-- Modified MSF 20191128 (NA) -->
															<!--<th>Categories Supplied</th>-->
															<th>Department Supplied</th>
															<th></th>
														</tr>
													</thead>
												</table>
												<div style="overflow: auto;max-height: 250px;">
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
								</div>

								<!-- Added MSF - 20191118 (IJR-10618) -->
								<!-- Sub Category -->
								<div class="form-group">
									<div class="col-md-6 col-md-offset-2">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<!-- Modified MSF 20191128 (NA) -->
												<!--<h3 class="panel-title">Sub-Category</h3>-->
												<h3 class="panel-title">Sub-Department</h3>
											</div>
											<div class="panel-body">
												<table class="table table-bordered">
													<thead>
														<tr class="info">
															<!-- Modified MSF 20191128 (NA) -->
															<!--<th>Categories Supplied</th>-->
															<th>Sub-Department Supplied</th>
															<th></th>
														</tr>
													</thead>
												</table>
												<div style="overflow: auto;max-height: 250px;">
													<input type="hidden" id="sub_cat_sup_count" name="sub_cat_sup_count" value="0">
													<table class="table table-bordered" id="sub_cat_sup">
														<tbody>
															<tr id="tr_subcatsup1" class="cls_tr_cat">
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
								</div>
								<?php //endif; ?>

								<div class="form-group">
									<label for="txt_vendor_msg" class="small col-md-2"><span class="pull-right">Message to Vendor</span></label>
									<div class="col-md-10">
										   <textarea class="form-control" rows="14" id="txt_vendor_msg" name="txt_vendor_msg" readonly></textarea>

									</div>
								</div>

								<div class="form-group">
									<label for="txt_approver_note" class="small col-md-2"><span class="pull-right">Note to Approver</span></label>
									<div class="col-md-10">
										   <textarea class="form-control" rows="4" id="txt_approver_note" name="txt_approver_note" readonly></textarea>
									</div>
								</div>
								<div class="form-group" id="reason_for_extension_div">
									<label for="txt_reason_for_extension" class="small col-md-2"><span class="pull-right">Reason for Extension</span></label>
									<div class="col-md-10">
										   <textarea class="form-control" rows="4" id="txt_reason_for_extension" name="txt_reason_for_extension" readonly></textarea>
									</div>
								</div>



							<div class="row">
							<div class="col-sm-2"><label class="small pull-right"><span class="pull-right">Terms of Payment </span></label></div>
							<div class="col-sm-3">
							<?=form_dropdown('cbo_tp', $payment_terms,$termspayment, ' id="cbo_tp" class="col-sm-3 form-control" '.$dsb.'')?>
							</div>
							<?php
								if($view_only == 1){

									echo '<button type="button" class="btn btn-primary" onclick="save_temp('. $invite_id .','.$user_id.')" id="btn_save_terms"  '. $dsb .' >Update Payment Terms</button>';

								}
							?>


							</div>

							</form>


						<div id="test"></div>
						</div>
					</div>
				</div>

<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js');?>");
	loadingScreen('on');
	function load_records()
	{
		var invite_id = $('#invite_id').val();

		if (invite_id != '' || invite_id != null)
		{
			var ajax_type = 'POST';
	        var url = BASE_URL + "vendor/invitecreation/load_invite_draft"; // loading of values
	        var post_params = 'invite_id='+ invite_id;

	        var success_function = function(responseText)
	        {

	        	console.log(responseText);
	        	//return;
	            var records = $.parseJSON(responseText);
	            // console.log(responseText);
	            let cat_template = $('#tr_catsup1').clone();
				
				// Added MSF - 20191118 (IJR-10618)
	            let sub_cat_template = $('#tr_subcatsup1').clone();
				
	            $('#cat_sup tbody').html(''); // reset
				
				// Added MSF - 20191118 (IJR-10618)
	            $('#sub_cat_sup tbody').html(''); // reset
				
	            if (records.resultscount > 0)
	            {
	            	// console.log(records.query[0]);
	            	// assign value to element
					var vendor_msg = '';
					if(records.query[0].MESSAGE != null){
						if(records.query[0].MESSAGE.trim().length > 0){
							vendor_msg = records.query[0].CONTENT;// + '\n\n<b>Note:</b> ' + records.query[0].MESSAGE; 
							var message = '\n<b>Note:</b>' + records.query[0].MESSAGE + '\n';
							vendor_msg = vendor_msg.replace("[note]", message);
						}else{
							vendor_msg = records.query[0].CONTENT; 
							vendor_msg = vendor_msg.replace("[note]", '\n');
						}	
					}
					
	            	$('#spn_vendorname').text(records.query[0].VENDOR_NAME);
	            	$('#txt_vendorname').val(records.query[0].VENDOR_NAME);
	            	$('#status_id').val(records.query[0].STATUS_ID);
					$('#txt_contact_person').val(records.query[0].CONTACT_PERSON);
					$('#txt_email').val(records.query[0].EMAIL);
					$('#txt_vendor_msg').val(vendor_msg);
					$('#txt_approver_note').val(records.query[0].APPROVER_NOTE);
					$('#reg_type_id').val(records.query[0].REGISTRATION_TYPE);
					if(records.query[0].REASON_FOR_EXTENSION){
						$('#reason_for_extension_div').css('display','');
						$('#txt_reason_for_extension').val(records.query[0].REASON_FOR_EXTENSION);
					}else{
						$('#reason_for_extension_div').css('display','none');
					}
					
					var trade_vendor_type = 1;
						trade_vendor_type = records.query[0].TRADE_VENDOR_TYPE;
					
					console.log(records.query);
					$('input:radio[name=rad_trade_vendor_type][value=' + trade_vendor_type + ']').prop('checked', true);

					for (let i = 0, j = 1; i < records.rs_cat_count; i++, j++) // i is for object, j is for element
					{
						let new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
						new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': records.rs_cat[i].CATEGORY_ID});
						new_row.find('span').attr('id','category_name'+j);

						if (!$('#cat_sup tbody :input[value='+records.rs_cat[i].CATEGORY_ID+']').length)
				            $('#cat_sup tbody').append(new_row.clone());

						$('#category_name'+j).html(records.rs_cat[i].CATEGORY_NAME);
					}

					// Added MSF - 20191118 (IJR-10618)
					for (let i = 0, j = 1; i < records.rs_sub_cat_count; i++, j++) // i is for object, j is for element
					{
						let new_row = sub_cat_template.attr({'id':'tr_subcatsup'+j,'class':'cls_tr_cat'});
						new_row.find(':input').attr({'id':'sub_category_id'+j, 'name':'sub_category_id'+j, 'value': records.rs_sub_cat[i].SUB_CATEGORY_ID});
						new_row.find('span').attr('id','sub_category_name'+j);
						//console.log(new_row);

						if (!$('#sub_cat_sup tbody :input[value='+records.rs_sub_cat[i].SUB_CATEGORY_ID+']').length)
							$('#sub_cat_sup tbody').append(new_row.clone());

						$('#sub_category_name'+j).html(records.rs_sub_cat[i].SUB_CATEGORY_NAME);
					}

	            }

	            // $('#test').html(responseText);

	        };

	       	return ajax_request(ajax_type, url, post_params, success_function);

		}
	}

	load_records().done(function(){
    	loadingScreen('off');
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
		var d ={ 'vid': $('#invite_id').val(),'delReason':$('#delReason').val(),'sid':'<?=$status_id?>' };
		//var d ={ 'vid': $('#invite_id').val() };
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
					if(result == 1){
						alert('Vendor successfully deleted.');
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
	
	// Added MSF - 20191108 (IJR-10617)
	function downloadImg(){
		var url = $('#image_preview').attr('src');
		let n_url = url.split('/');
		let ncount = n_url.length;
		window.open(BASE_URL+'vendor/registration/download_img/'+n_url[ncount -1]);
	}
</script>
