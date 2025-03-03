<?php 
//echo '<pre>'; 
//print_r($this->session->all_userdata()); 
//var_dump($this->_ci_cached_vars);
//print_r($vendor_profile);

$terms = 0;
foreach ($payment_terms as $key => $value) {
	if($termspayment == $key){
		$terms = $value;
	}
}

$payment_terms = $terms;	

$vendor_type = '';
//if($vendor_profile->vendor_type == 1){
//	$vendor_type = 'Trade';
//}elseif($vendor_profile->vendor_type == 2){
//	$vendor_type = 'Non Trade';
//}else{
//	$vendor_type = 'Non Trade Services';
//}

if($vendor_profile->vendor_type == 1){
	$vendor_type = 'Outright';
}elseif($vendor_profile->vendor_type == 2){
	$vendor_type = 'Store Consignor';
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
					<span class="document_preview" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Preview</h4>
						<button type="button" id="zoom_image">Zoom In</button>
						<button type="button" id="zoom_out_image">Zoom Out</button>
						<button type="button" id="fit_to_screen" >Fit To Screen</button>
						<button type="button" id="btn_download" onclick="downloadImg()">Download</button>
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
				<div class="modal-footer">
					<!-- Added MSF - 20191108 (IJR-10617) -->
					<span class="document_preview" style="display:none;">
						<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="pull-right">
		
		<div class="btn-group">
			<button type="button" class="btn btn-primary " onclick="print_department()">Print</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_approve">Approve</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-primary " id="btn_reject">Reject</button>
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
							<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_invite_id" name="vendor_invite_id" value="<?php echo $invite_id; ?>" readonly>
							<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_id" name="vendor_id" value="<?php echo $vendor_profile->vendor_id; ?>" readonly>
							<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="position_id" name="position_id" value="<?php echo $position_id; ?>" readonly>
							<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="rec_id" name="rec_id" value="<?php echo $vendor_profile->add_dept_header_id; ?>" readonly>
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="vendor_name" name="vendor_name" width="100%" maxlength="50" value="<?php echo $vendor_profile->vendor_name; ?>" readonly>
						</div>
						<!--
						<div class="col-sm-8">
							<div class="input-group">
								<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_invite_id" name="vendor_invite_id" readonly>
								<input type="hidden" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_id" name="vendor_id" readonly>
								<input type="text" class="form-control input-sm cls_brand" list-container="brand_list" id="vendor_name" name="vendor_name" readonly>
							</div>
						</div>
						-->
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Vendor Code : </label>
						</div>
						
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="vendor_code" name="vendor_code" width="100%" maxlength="50" value="<?php echo $vendor_profile->vendor_code; ?>" readonly>
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
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="vendor_type" name="vendor_type" width="100%" maxlength="50" value="<?php echo $vendor_type; ?>" readonly>
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
							<?php
								$existing_category = '';
								for($a=0; $a<$vendor_profile->category_count; $a++){
									echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="'.$vendor_profile->category[$a]->CATEGORY_NAME.'" readonly>');
								}
							?>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="dept_name1" class="control-label">  Add Department : </label>
						</div>
						<div class="col-sm-8">
							<?php
								$add_category = '';
								for($b=0; $b<$vendor_profile->add_category_count; $b++){
									echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="'.$vendor_profile->add_category[$b]->CATEGORY_NAME.'" readonly>');
								}
							?>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="sub_dept_name1" class="control-label">  Add Sub Department : </label>
						</div>
						<div class="col-sm-8">
							<?php
								$add_sub_category = '';
								if($vendor_profile->add_sub_category_count > 0){
									for($c=0; $c<$vendor_profile->add_sub_category_count; $c++){
										echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="'.$vendor_profile->add_sub_category[$c]->SUB_CATEGORY_NAME.'" readonly>');
									}
								}else{
									echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="&nbsp;" readonly>');
								}
							?>
						</div>
					</div>
				</div>
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label for="brand_name1" class="control-label">  Add Brand : </label>
						</div>
						<div class="col-sm-8">
							<?php
								$brand = '';
								if($vendor_profile->brand_count > 0){
									for($c=0; $c<$vendor_profile->brand_count; $c++){
										echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="'.$vendor_profile->brand[$c]->BRAND_NAME.'" readonly>');
									}
								}else{
										echo('<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="&nbsp" readonly>');
								}
							?>
						</div>
					</div>
				</div>
				
				
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<!-- Added MSF - 20191108 (IJR-10617) -->
				<div class="col-sm-12">
					<div class="form-group">
						<label for="txt_email" class="col-md-2"><span class="pull-right">Approved Items / Project</span></label>
						<div class="col-md-6">
							<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $original_file_name; ?>">
							<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $file_path; ?>">
						</div>
						
						<?php if($file_path != ''): ?>
							<button class="btn btn-default" type="button" id="btn_invite_view" name="btn_invite_view" value="<?php echo $file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
						<?php endif; ?>
					</div>

				<!-- Additional upload field -->
				<div>
					<div class="form-group">
						<label for="txt_email" class="col-md-2"><span class="pull-right"></span></label>
						<div class="col-md-6">
							<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly value = "<?php echo $original_file_name2; ?>">
							<input id="txt_file_path2" name="txt_file_path2" type="hidden" value="<?php echo $file_path2; ?>">
						</div>
						
						<?php if($file_path2 != ''): ?>
							<button class="btn btn-default" type="button" id="btn_invite_view2" name="btn_invite_view2" value="<?php echo $file_path2; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
						<?php endif; ?>
					</div>
				</div>
				</div>
				
				<div class="col-sm-12"><div class="form-group"><div class="col-sm-12"></div></div></div>
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Payment Terms : </label>
						</div>
						<div class="col-sm-8">
							<input type="text" class="form-control input-sm field-required limit-chars" style="width:100%" id="e_dept_name" name="e_dept_name" width="100%" maxlength="50" value="<?php echo $payment_terms; ?>" readonly>
						</div>
					</div>
				</div>
				
				<div class="col-sm-12">
					<div class="form-group">
						<div class="col-sm-2">
							<label class="control-label">  Note to Approver : </label>
						</div>
						<div class="col-md-10">	
							<textarea class="form-control" rows="4" id="txt_approver_note" name="txt_approver_note" maxlength="300" readonly><?php echo $vendor_profile->approvers_note; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
</div>
</form>

<script>
	function print_department()
	{
		var id = $('#vendor_invite_id').val();
		window.open(BASE_URL+'vendor/add_department/print_template/'+id);
	}
	
    $('#btn_approve').on('click', function() {
			var pos_id = $('#position_id').val();
            disable_enable_frm('frm_adddepartment', true, '.exclude');
            var span_message = 'Are you sure you want to approve? <button type="button" class="btn btn-success" onclick="response(1,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_adddepartment\', false, \'.exclude\');">No</button>';
            var type = 'info';
            notify(span_message, type, true);
	});
	
    $('#btn_reject').on('click', function() {
            disable_enable_frm('frm_adddepartment', true, '.exclude');
            var span_message = 'Are you sure you want to reject? <button type="button" class="btn btn-success" onclick="response(2,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_adddepartment\', false, \'.exclude\');">No</button>';
            var type = 'info';
            notify(span_message, type, true);
	});

	function response(action, btn) // approve = 3, reject = 4
	{
		loading($(btn), 'in_progress');
		var pos_id = $('#position_id').val();
		var add_dept_header_id = $('#rec_id').val();
		var vendor_invite_id = $('#vendor_invite_id').val();
		var vendor_id = $('#vendor_id').val();
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/add_department/response_approval/";
		var post_params = "position_id="+pos_id+"&rec_id="+add_dept_header_id+"&vendor_invite_id="+vendor_invite_id+"&action="+action+"&vendor_id="+vendor_id;
		var success_function = function(responseText)
		{
		   //console.log(responseText);
		   //return;
		   var rs = $.parseJSON(responseText);
		   
		   if (rs == 1)
		   {
				 var span_message; 

				if (action == 1)
					span_message = 'Add department successfully approved.';
				if (action == 2)
				{
					span_message = 'Add department successfully rejected.';
					$('#myModal').modal('hide');
				}
			   
				var type = 'success';
				notify(span_message, type);
				$('#frm_adddepartment')[0].reset(); // reset fields after success
				var action_path = BASE_URL + 'vendor/registration/registrationmain/';
				setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
		   }
		   else
		   {
				var span_message = responseText;
				var type = 'warning';
				notify(span_message, type);
		   }
		   loading($(btn), 'done');
		};

		ajax_request(ajax_type, url, post_params, success_function);
	}
	
	
    var picture = $('#image_preview');

    $('#myModal').on('hidden.bs.modal', function() {
        picture.guillotine('remove');
        picture.css('display', 'none');
        $('#pdf_preview').css('display', 'none');
    });
		
		//Added MSF - 20191108 (IJR-10617)
		//inviteforapprovaldetails - view image
		$('#btn_invite_view').on('click', function(){
			//console.log('here');
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

		// second file
		
		 $('#btn_invite_view2').on('click', function(){
			//console.log('here');
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
			
			var status_id = $("#status_id").val();//end
		});


		// Added MSF - 20191105 (IJR-10612)
		picture.on('load', function() {
			picture.guillotine({
				width: 400,
				height: 300
			});

			picture.guillotine('fit');

			// Initialize plugin (with custom event)
			picture.guillotine({eventOnChange: 'guillotinechange'});

			var data = picture.guillotine('getData');
			for(var key in data) { $('#'+key).html(data[key]); }
		});

		// Added MSF - 20191105 (IJR-10612)
		$('#fit_to_screen').click(function(){ picture.guillotine('fit'); });
		$('#zoom_image').click(function(){ picture.guillotine('zoomIn'); });
		$('#zoom_out_image').click(function(){ picture.guillotine('zoomOut'); });
		
		$('#myModal').on('hidden.bs.modal', function () {
			//$('.modal-dialog').removeClass('modal-lg'); //jay 
		});
		

		$('table').on('click', '.preview', function(){
			
			$('.close').alert('close'); // close alert if it has
			var url = BASE_URL.replace('index.php/','') + $(this).val();
			$('#myModal').modal('show');

			$('#myModal span').hide();
			$('.alert > span').show(); // dont include to hide these span
			$('#myModal .document_preview').show();
			// Updated by MSF - 20191105 (IJR-10612)
			//$('#imagepreview').attr('src', '');
			picture.attr('src', '');
			$('#pdf_preview').attr('src', '');
			
			if ($(this).val() != '')
			{
				// Updated by MSF - 20191105 (IJR-10612)
				//$('#imagepreview').attr('src', url);
				//$('#imagepreview').removeClass('zoom_in');
				$('.modal-dialog').addClass('modal-lg');    
				var filext = $(this).val().split('.').pop();
				// setting height of iframe according to window size
				var set_height  = '';
				var w_h         = '';
				var t_h         = '';

				// Updated by MSF - 20191105 (IJR-10612)
				/*if (filext.toLowerCase() == 'pdf')
				{
					w_h = $(window).height() * 0.75;
					t_h = $(this).height() * 0.75;
					$('#zoom_image').hide();
					$('#zoom_out_image').hide();
					$('#btn_printimg').hide();
				}
				else
				{
					w_h = $(window).height() /2;
					t_h = $(this).height() /2;
					$('#zoom_image').show();
					$('#zoom_out_image').show();
					$('#btn_printimg').show();
				}
				$('iframe').height(w_h);
				*/
				if (filext.toLowerCase() == 'pdf'){
					
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
				}else{
					$('#pdf_preview').css('display', 'none');
					picture.attr('src', url);
					picture.removeClass('zoom_in');
					picture.css('display', 'inline');
					
					$('#zoom_image').show();
					$('#zoom_out_image').show();
					$('#fit_to_screen').show();
					$('#btn_printimg').show();
					$('#btn_download').show();

					w_h = $(window).height() /2;
					t_h = $(this).height() /2;
				}
				
				$('embed').height(w_h);
				$(window).resize(function(){
					// Modified MSF - 20191118 (IJR-10612)
					//$('iframe').height(t_h);
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
			
			// check if element exists
			if (view_only != 1){
				$(this).closest('tr').find('.validated:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				$(this).closest('tr').find('.reviewed:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				$(this).closest('tr').find('input[id^=rsd_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				
				// Disabled "IF" for MSF - 20191126 (IJR-10619)
				//if(status_id == 194){
					$(this).closest('tr').find('input[id^=ra_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				//}
				
					$(this).closest('tr').find('input[id^=ccn_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				
				if(status_id == 10 || status_id == 194){
					var wd = 0;
					
					if(status_id == 194){
						wd = $($(this).closest('tr').find('input[id^=waive_ad_document_ch]:checked')).length
					}else if(status_id == 10){
						wd = $($(this).closest('tr').find('input[id^=waive_rsd_document_ch]:checked')).length
					}
				
					var check_data = $(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').hasClass("reviewed_additional");
					if( ! check_data && wd <= 0){
						$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').addClass("reviewed_additional");
						$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
						$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').removeClass("na_reviewed");
						$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').removeClass("na_reviewed_additional");
					}else if( ! check_data){
						$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
					}
				}
			}
			
			// Disabled for MSF - 20191126 (IJR-10619)
			//jay
			//if(status_id == 10){
				//alert("test");
				//$(this).closest('tr').find('input[id^=ra_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', true);
			//}
		});

		function zoomimage()
		{
			$('#imagepreview').addClass('zoom_in');
		}

		function zoomoutimage()
		{
			$('#imagepreview').removeClass('zoom_in');
		}
</script>
