
<link href="<?php echo base_url().'assets/css/jquery.guillotine.css'; ?>" media='all' rel='stylesheet'>
<script src="<?php echo base_url().'assets/js/jquery.guillotine.js'; ?>"></script>

<!-- Modified MSF - 20191108 (IJR-10617) Moved Modal inside Form-->
<!-- Start Modal -->
<!--
<div class="modal fade" id="extend_invite_modal" tabindex="-1" role="dialog" aria-labelledby="extend_invite_modal_label">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<span class="vi_approval_history">
					<h4 class="modal-title" id="extend_invite_modal_label">Reason for extension:</h4>
				</span>
			</div>
			<div class="modal-body">
				<span class="vri_reason_for_extension">
					<textarea class="form-control" 
					placeholder="Please specify reason here" id="vi_reason_for_extension_remarks" 
					style="height: 100px; min-height:100px; width:100%; min-width:100%;" required></textarea>
				</span>
			</div>
			<div class="modal-footer">
			<span class="vri_reason_for_extension">
				<button type="button" class="btn btn-primary" id="vri_extend_invite">Ok</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</span>
			</div>
		</div>
	</div>
</div>-->
<!-- END Modal -->
	<div class="container mycontainer">
		
		
				
				<div class="row">
					<div class="col-md-6"><h4>Vendor Registration Invite <?php echo isset($invite_extend) ? '- <font color="red">Expired</font>' : ''; ?></h4></div>
					<div class="col-md-6">
						<span class="pull-right">
							<?php if (isset($invite_extend)) :?>
								<!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#extend_invite_modal">Extend Invite</button>-->
								<button type="button" class="btn btn-primary" id="vri_extend_invite">Extend Invite</button>
								<button type="button" class="btn btn-primary" id="vri_close_invite">Close Invite</button>
								<button type="button" class="btn btn-primary btn-exit">Exit</button>
							<?php else: ?>
								<button type="button" class="btn btn-primary" id="vri_submit_approval">Submit For Approval</button>
								<button type="button" class="btn btn-primary" id="vri_draftsave">Save As Draft</button>
								<button type="button" class="btn btn-primary btn-exit">Exit</button>
							<?php endif; ?>
						</span>
					</div>
					<!-- <div>test</div> -->					
				</div>
				<div class="row">
					<div class="col-md-6">
						<?php if ($status_id >= 4): ?>
							<a href="#" id="vi_approval_history"> <span class="small">Approval History</a>
						<?php endif; ?>
					</div>
				</div>
				<div class="spacer"></div>
				<div class="form_container">
					<div class="panel panel-default">
						<div class="panel-body">
						
							<!-- Modified MSF - 20191108 (IJR-10617) -->
							<!-- <form id="frm_invitecreation" method="post" class="form-horizontal"> -->
							<form id="frm_invitecreation" name="frm_invitecreation" method="post" class="form-horizontal" enctype="multipart/form-data">
							<!-- Moved and Modified MSF - 20191108 (IJR-10617) -->
							<!-- Start Modal -->
							<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
											<span class="vi_approval_history" style="display:none;">
												<h4 class="modal-title" id="myModalLabel">Approval History</h4>
											</span>
											
											<span class="search_vendor" style="display:none;">
												<h4 class="modal-title" id="myModalLabel">Select Vendor</h4>
											</span>
											
											<!-- Added MSF - 20191108 (IJR-10617) -->
											<span class="upload_documents" style="display:none;">
												<h4 class="modal-title" id="myModalLabel">Upload Scanned Documents</h4>
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
																			<td>{{APPROVER_REMARKS}}</td>
																		</tr>
																	{{/table_history}}
																</script>
															</tbody>
														</table>
														
													</div>
												</span>
												
												<!-- Added MSF - 20191108 (IJR-10617) -->
												<span class="search_vendor" style="display:none;">
													<!-- Style for autocompletion -->
													<style>
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
													
													<script type="text/javascript">
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
																  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
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

													</script>
													
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
											
											<!-- Added MSF - 20191108 (IJR-10617) -->
											<span class="upload_documents" style="display:none;">
												<button type="button" class="btn btn-primary" id="btn_upload" >Upload</button>
												<button type="button" class="btn btn-default" data-dismiss="modal" id="btn_upload_cancel">Cancel</button>
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
								<input type="hidden" id="invite_id" name="invite_id" value="<?php echo $invite_id; ?>">
								<input type="hidden" id="source_invite_id" name="source_invite_id" value="">
								<div class="form-group">
									<label for="rbtn_invite_type" class="small col-md-2"><span class="pull-right">Invite Type</span></label>
									<div class="col-md-6">	
										<label class="radio-inline">
									      <input type="radio" name="rad_invite_type" id="rad_invite_type" onclick="r_invite_type(this);" value="1" class="field-required" <?php echo isset($invite_extend) ? 'disabled' : '' ?> checked>New
									    </label>
									    <label class="radio-inline">
									      <input type="radio" name="rad_invite_type" id="rad_invite_type" onclick="r_invite_type(this);" value="4" class="field-required" <?php echo isset($invite_extend) ? 'disabled' : '' ?>>Add Vendor Code
									    </label>
									    <label class="radio-inline">
									      <input type="radio" name="rad_invite_type" id="rad_invite_type" onclick="r_invite_type(this);" value="5" class="field-required" <?php echo isset($invite_extend) ? 'disabled' : '' ?>>Change Company Name
									    </label>
									</div>
								</div>
								<div class="form-group">
									<label for="txt_vendorname" class="small col-md-2"><span class="pull-right">Vendor Name</span></label>
									<div class="col-md-6">	
										<input style = "text-transform:uppercase;" class="form-control input-sm field-required limit-chars" id="txt_vendorname" name="txt_vendorname" type="text" <?php echo (isset($invite_extend) ? 'readonly' : '')?> maxlength="300">
									</div>
									<div class="col-md-4">	
										<button type="button" class="btn btn-default" id="btnSearchVendor" disabled>Search</button>
									</div>
								</div>
								
								<div class="form-group">
									<label for="txt_vendorname" class="small col-md-2"><span class="pull-right">New Vendor Name</span></label>
									<div class="col-md-6">	
										<input style = "text-transform:uppercase;" class="form-control input-sm limit-chars" id="txt_nvendorname" name="txt_nvendorname" type="text" maxlength="300" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="txt_contact_person" class="small col-md-2"><span class="pull-right">Contact Person</span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm field-required limit-chars" id="txt_contact_person" name="txt_contact_person" type="text" <?php echo (isset($invite_extend) ? 'readonly' : '')?>  maxlength="300">
									</div>
								</div>
								
								<div class="form-group">
									<label for="txt_email" class="small col-md-2"><span class="pull-right">Email</span></label>
									<div class="col-md-6">
										<div class="">
											<input class="field-required form-control input-sm isEmail limit-chars" id="txt_email" name="txt_email" type="text" <?php echo (isset($invite_extend) ? 'readonly' : '')?>  maxlength="300">
										</div>
									</div>
									<div class="col-md-4">	
										<button type="button" class="btn btn-default" id="findsimilar" <?php echo (isset($invite_extend) ? 'disabled' : '')?>>Find Similar</button>
									</div>
								</div>
								
								<!-- Added MSF - 20191108 (IJR-10617) -->
								<div class="form-group">
									<label for="txt_approve_items" class="small col-md-2"><span class="pull-right">Approved Items / Project</span></label>
									<div class="col-md-3">
										<input class="form-control input-sm field-required limit-chars" id="txt_approve_items" name="txt_approve_items" type="text" readonly style="width:245px;" value = "<?php echo $original_file_name; ?>">
										<input id="txt_file_path" name="txt_file_path" type="hidden" value="<?php echo $file_path; ?>">
									</div>
									<div class="col-md-3">
										<input class="form-control input-sm field-required limit-chars" id="txt_date_upload" name="txt_date_upload" type="text" readonly style="width:245px;" value="<?php echo $date_created; ?>">
									</div>
									<div class="col-md-4">	
										<?php if($original_file_name != ''){ ?>
											<button class="btn btn-default " type="button" id="btn_invite_upload"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>									
											<button class="btn btn-default" type="button" id="btn_invite_view" name="btn_invite_view" value="<?php echo $file_path; ?>"><span class="glyphicon glyphicon-sunglasses" aria-hidden="true" ></span>&nbspView</button>
										<?php }else{?>
											<button class="btn btn-default " type="button" id="btn_invite_upload"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>&nbspUpload</button>									
										<?php } ?>
									</div>
								</div>
								
								<div class="form-group">
									<div class="col-md-offset-2">
										<div id="similar_show" class="collapse" style="overflow: auto;max-height: 250px;">
											<div class="well show_similar">
												<ul id="similar_list" class="list-unstyled">
												</ul>
											</div>
										</div>										
									</div>									
								</div>
								<div class="form-group">
									<label for="cbo_msg_template" class="small col-md-2"><span class="pull-right">Message Template</span></label>
									<div class="col-md-6">	
										  <select class="form-control" id="cbo_msg_template" name="cbo_msg_template" <?php echo isset($invite_extend) ? 'disabled' : '' ?>>
											<script id="msg_list_template" type="text/template">
										  		<option value="" disabled selected>-- Select --</option>
												{{#list_template}}
													<option value="{{VEN_INV_ID}}" data-message-template="{{VEN_INV_MESSAGE}}" >{{VEN_INV_TITLE}}</option>
												{{/list_template}}
											</script>
										  </select>
									</div>
								</div>
								
								<?php if($business_type == 1): ?>
								<div class="form-group" style="display: <?php echo ($business_type == 1 ? 'block' : 'none') ?>">
									<label for="txt_contact_person" class="small col-md-2"><span class="pull-right">Trade Vendor Type</span></label>
									<div class="col-md-2">	
										<center>
										<label class="radio-inline">
									      <input type="radio" name="rad_trade_vendor_type" value="1" class="<?php echo ($business_type == 1 ? 'field-required' : '') ?>" <?php echo isset($invite_extend) ? 'disabled' : ''; ?>>Outright
									    </label>
										</center>
									</div>
									<div class="col-md-2">	
										<center>
									    <label class="radio-inline">
									      <input type="radio" name="rad_trade_vendor_type" value="2" class="<?php echo ($business_type == 1 ? 'field-required' : '') ?>" <?php echo isset($invite_extend) ? 'disabled' : ''; ?>>Consignor
									    </label>
										</center>
									</div>
								</div>
								<?php endif; ?>
								<div class="form-group">
									<label for="txt_contact_person" class="small col-md-2"><span class="pull-right">Vendor Code</span></label>
									<div class="col-md-2">
										<div class="">
											<input type="hidden" id="multiple_vc" name="multiple_vc">
											<input type="hidden" id="main_vt" name="main_vt">
											<input type="hidden" id="sub_vt" name="sub_vt">
											<input class="form-control input-sm limit-chars" id="txt_vendor_code" name="txt_vendor_code" type="text" maxlength="300" readonly>
											<input class="form-control input-sm limit-chars" id="txt_vendor_code_02" name="txt_vendor_code_02" type="hidden" maxlength="300" readonly>
										</div>
									</div>
								</div>
								<?php //if($position_id == 11): ?>
								<div class="form-group">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<!-- Modified MSF 20191128 (NA) -->
												<!--<h3 class="panel-title">Category</h3>-->
												<h3 class="panel-title">Department</h3>
											</div>
											<div class="panel-body">
												<div class="col-md-5">
													<div class="form-group">
														<table class="table table-bordered">
															<thead>
																<tr class="info">
																	<th>
																		<div class="form-inline">
																			<!-- Modified MSF 20191128 (NA) -->
																			<!-- <label for="search_cat">Department/Category</label>-->
																			<label for="search_cat">Department</label>
																			<input type="text" class="form-control input-sm removeSpecialChar" id="search_cat" > 
																			<button type="button" onclick="search_category(this)"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>	
																		</div>
																	</th>
																</tr>
															</thead>
														</table>
														<div class="col-md-12" style="overflow: auto;max-height: 250px;">
															<table class="table table-bordered" id="dept_cat">																
																<tbody>
																<?php 
																$n = 1;
																if (!empty($category_list)){
																foreach ($category_list as $row){
																		echo '<tr>';
																		echo '<td><input type="hidden" class="cls_cat" id="hid_deptcat'.$n.'" name="hid_deptcat'.$n.'" value="'.$row->CATEGORY_ID.'"><span id="deptcatname'.$n.'">'.$row->CATEGORY_NAME.'</span></td>';
																		echo '</tr>';
																		$n += 1;
																	}
																}else{
																	echo '<tr><td><input type="hidden" class="cls_cat" id="hid_deptcat'.$n.'" name="hid_deptcat'.$n.'" value="0"><span id="deptcatname'.$n.'">No Records found.</span></td></tr>';
																}
																	?>
																
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="col-md-1">
													<div class="form-group">
														<br><br><br>
														<button type="button" class="btn btn-default btn-lg " id="btn_move_right" <?php echo (isset($invite_extend) ? 'disabled' : '')?>><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
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
														<div class="col-md-12" style="overflow: auto;max-height: 250px;">
															<input type="hidden" id="cat_sup_count" name="cat_sup_count" value="0">
															<table class="table table-bordered" id="cat_sup">																
																<tbody>
																</tbody>
															</table>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
								</div>

								<!-- Added MSF - 20191118 (IJR-10618) -->
								<div class="form-group" name="sub_category" id="sub_category" style="display: none;">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<!-- Modified MSF 20191128 (NA) -->
												<h3 class="panel-title">Sub - Department</h3>
											</div>
											<div class="panel-body">
												<div class="col-md-5">
													<div class="form-group">
														<table class="table table-bordered">
															<thead>
																<tr class="info">
																	<th>
																		<div class="form-inline">
																			<!-- Modified MSF 20191128 (NA) -->
																			<label for="search_cat">Sub-Department</label>
																		</div>
																	</th>
																</tr>
															</thead>
														</table>
														<div class="col-md-12" style="overflow: auto;max-height: 250px;">
															<table class="table table-bordered" id="dept_sub_cat">
																<tbody id="tbl_sub_category">
																</tbody>
															</table>
														</div>
													</div>
												</div>
												<div class="col-md-1">
													<div class="form-group">
														<br><br><br>
														<button type="button" class="btn btn-default btn-lg " id="btn_sub_cat_move_right" <?php echo (isset($invite_extend) ? 'disabled' : '')?>><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<table class="table table-bordered">
															<thead>
																<tr class="info">
																	<!-- Modified MSF 20191128 (NA) -->
																	<th>Sub-Department Supplied</th>
																	<th></th>
																</tr>
															</thead>
														</table>
														<div class="col-md-12" style="overflow: auto;max-height: 250px;">
															<input type="hidden" id="sub_cat_sup_count" name="sub_cat_sup_count" value="0">
															<table class="table table-bordered" id="sub_cat_sup">																
																<tbody>
																</tbody>
															</table>
														</div>
													</div>
												</div>	
											</div>
										</div>
									</div>
								</div>
								<?php //endif; ?>

								<div class="form-group">
									<label for="" class="small col-md-2">
										<span class="pull-right">Message to Vendor</span><br>
										<div class="pull-right" id="msg_vendor_counter"></div>
									</label>
									<div class="col-md-10">	
										<div>
										   <textarea class="form-control field-required" rows="8" id="txt_template_msg" name="txt_template_msg"  <?php echo (isset($invite_extend) ? 'readonly' : '')?>  maxlength="300"></textarea>
										</div>
										<div>
										   <textarea class="form-control field-required limit-chars" rows="12" id="txt_vendor_msg" name="txt_vendor_msg" readonly  maxlength="300"><?php echo $template_email; ?></textarea>
										</div>
										   <em class="small">*Please note that the login id and date of expiration may change due to the approval of invite</em>
									</div>
								</div>
								
								<div class="form-group">
									<label for="inputsm" class="small col-md-2">
								
										<span class="pull-right">Note to Approver</span>
										<div class="pull-right" id="note_counter"></div>
									</label>
									<div class="col-md-10">	
										<textarea class="form-control <?php echo isset($invite_extend) ? 'field-required' : '' ?>  limit-chars" rows="4" id="txt_approver_note" name="txt_approver_note"  <?php echo (isset($invite_extend) ? 'readonly' : '')?> maxlength="300"></textarea>
									</div>
								</div>
								<?php if(isset($invite_extend)):?>
								<div class="form-group">
									<label for="inputsm" class="small col-md-2">
								
										<span class="pull-right">Reason For Extension</span>
										<div class="pull-right" id="note_counter"></div>
									</label>
									<div class="col-md-10">	
										<textarea id="vi_reason_for_extension_remarks"class="form-control <?php echo isset($invite_extend) ? 'field-required' : '' ?>  limit-chars" rows="4" id="txt_approver_note" name="txt_approver_note" maxlength="300"></textarea>
									</div>
								</div>
								<?php endif; ?>

							<div class="row">
							<div class="col-sm-2"><label class="small pull-right"><span class="pull-right">Terms of Payment </span></label></div>
							<div class="col-sm-3">

								<?php
										$tmps = array();		
						foreach ($payment_terms as $key => $value) {
								$tmps[$key] = $value;
								}				
								asort($tmps);
								?>
							<?=form_dropdown('cbo_tp', $tmps,$termspayment, ' id="cbo_tp" class="col-sm-3 form-control"')?>
							<input type="hidden" id="sub_tp" name="sub_tp">
							</div>
							</div>
   
							</form>
					
						
						<div id="test"></div>
						</div>
					</div>
				</div>
			</div>
			
<script type="text/javascript">
	//$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");
	//Use sync instead of async which cause ReferenceError when trying to
	//call function from vendor.js which is not fully loaded.
	$.ajax({
		url: "<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js');?>",
		dataType: 'script',
		async: false
	});
	loadingScreen('on');
	var CBO_MSG_LIST_TEMPLATE = $('#msg_list_template').html();
	function get_msg_template()
	{
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/invitecreation/get_msg_template";
        var post_params;

        var success_function = function(responseText)
        {
            var msg_template_data;
			try {
				msg_template_data = $.parseJSON(responseText);

			} catch (e) {
				console.log(e);
			}
            // $('.show_email').html(responseText);
            var DATA = {
                list_template: msg_template_data
            }

            $('#cbo_msg_template').html(Mustache.render(CBO_MSG_LIST_TEMPLATE, DATA));
            load_records();
           
        };

        ajax_request(ajax_type, url, post_params, success_function);
	}

	function load_records()
	{
		let invite_id = $('#invite_id').val();

		if (invite_id != '')
		{
			let ajax_type = 'POST';
	        let url = BASE_URL + "vendor/invitecreation/load_invite_draft";
	        let post_params = 'invite_id='+ invite_id;

	        let success_function = function(responseText)
	        {
				//console.log(responseText);
				//return;
	            let records = $.parseJSON(responseText);
	            
	            if (records.resultscount > 0)
	            {
					//console.log(records);
	            	// console.log(records.query[0].TEMPLATE_ID);
	            	// assign value to element
					if(records.query[0].REGISTRATION_TYPE == 1){
						r_invite_type(1);
						$("input[name=rad_invite_type][value='1']").prop("checked",true);
						$('#txt_vendorname').val(records.query[0].VENDOR_NAME);
					}else if( records.query[0].REGISTRATION_TYPE == 4){
						r_invite_type(4);
						$("input[name=rad_invite_type][value='4']").prop("checked",true);
						$('#txt_vendorname').val(records.query[0].VENDOR_NAME);
						$('#txt_vendor_code').val(records.query[0].ORIG_VENDOR_CODE);
					}else {
						$("input[name=rad_invite_type][value='5']").prop("checked",true);
						r_invite_type(document.querySelector('input[name="rad_invite_type"]:checked'));
						$('#txt_nvendorname').val(records.query[0].VENDOR_NAME);
						$('#txt_vendorname').val(records.query[0].OLD_VENDOR_NAME);
						$('#txt_vendor_code').val(records.query[0].VENDOR_CODE);
					}
					$('#invite_id').val(invite_id);
					$('#txt_contact_person').val(records.query[0].CONTACT_PERSON);
					$('#txt_email').val(records.query[0].EMAIL);
					
					$('#cbo_msg_template').val(records.query[0].TEMPLATE_ID);
					// $('#txt_vendor_msg').val(records.query[0].MESSAGE);
					$('#txt_template_msg').val(records.query[0].MESSAGE);
					$('#txt_approver_note').val(records.query[0].APPROVER_NOTE);

					$('input:radio[name=rad_trade_vendor_type][value=' + records.query[0].TRADE_VENDOR_TYPE + ']').prop('checked', true);
					if(records.query[0].TRADE_VENDOR_TYPE == 1){
						$('input:radio[name=rad_trade_vendor_type][value=2]').prop('disabled', true);	
					}else{
						$('input:radio[name=rad_trade_vendor_type][value=1]').prop('disabled', true);	
					}

					let cat_template = $('#dept_cat tbody tr:first').clone();
					$('#cat_sup_count').val(records.rs_cat_count);
					
					var xcat_id = 0
					let table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_cat" <?php echo isset($invite_extend) ? 'disabled' : '' ?>><span class="glyphicon glyphicon-trash"></span></button></td>');
					for (let i = 0, j = 1; i < records.rs_cat_count; i++, j++) // i is for object, j is for element
					{
						let new_row = '';
						new_row = cat_template.attr({'id':'tr_catsup'+j,'class':'cls_tr_cat'});
						new_row.find(':input').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': records.rs_cat[i].CATEGORY_ID});
						new_row.find('span').attr('id','category_name'+j);
						
						if(i == 0){
							table_trash.find('button').attr({'id':'category_id'+j, 'name':'category_id'+j, 'value': records.rs_cat[i].CATEGORY_ID});
							table_trash.find('span').attr({'id':'category_id'+j});
						}

						new_row.append(table_trash);
						
						if (!$('#cat_sup tbody :input[value='+records.rs_cat[i].CATEGORY_ID+']').length)
				            $('#cat_sup tbody').append(new_row.clone());

						$('#category_name'+j).html(records.rs_cat[i].CATEGORY_NAME);
						
						if(i == 0){
							xcat_id = records.rs_cat[i].CATEGORY_ID;
						}else{
							xcat_id = xcat_id + ", " + records.rs_cat[i].CATEGORY_ID;
						}
						
						if(records.rs_sub_cat_count > 0){
							$('#sub_category').css('display','block');
							var sub_cat_id = '0';
							for(let i=0; i<records.rs_sub_cat_count; i++){
								sub_cat_id = sub_cat_id + '|' + records.rs_sub_cat[i].SUB_CATEGORY_ID;
							}
						}
					}
					
					var temp = load_get_sub_category(xcat_id,sub_cat_id);
	            }

	            // $('#test').html(responseText);

	            search_category().done(function(){
	            	loadingScreen('off');
	            });
	        };

	        ajax_request(ajax_type, url, post_params, success_function);

		}else{
			loadingScreen('off');
		}
	}

	// Added MSF - 20191118 (IJR-10618)

	var ar_category_id = [];
	var ar_sub_category_id = [];
	var ar_source = [];

	function load_get_sub_category(category_ids,sub_cat_id){
		
		var sub_category_checker = false;
		var loaded_sub_category_checker = false;
		var second_count = $('#sub_cat_sup_count').val();
		var table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_sub_cat"><span class="glyphicon glyphicon-trash"></span></button></td>');
		
		var ajax_type = 'POST';
		var url = BASE_URL + "vendor/invitecreation/get_sub_cat/";
		var post_params = "cat_id="+category_ids;

		var success_function = function(responseText)
		{				
			var sub_category_id = sub_cat_id.split('|');
			
			var test = JSON.parse(responseText);
			for(var counter = 0; counter < test.length; counter++){
				for( var i = 0; i < ar_source.length; i++){ 
					if(ar_source[i][1] != ''){
						if(test[counter].SUB_CATEGORY_ID == ar_source[i][1]){
							sub_category_checker = true;
						}
					}
				}
				
				for(var x = 0; x < sub_category_id.length; x++){
					if(sub_category_id[x] == test[counter].SUB_CATEGORY_ID){
						loaded_sub_category_checker = true;
					}
				}
				
				if(sub_category_checker === false && loaded_sub_category_checker === true){
					second_count++;
					$('#sub_cat_sup tbody').append($('<tr id="tr_sub_catsup'+second_count+'" class="cls_tr_sub_cat"><td><input type="hidden" class="cls_sub_cat" id="sub_category_id'+ second_count +'"name="sub_category_id'+ second_count +'"value="'+ test[counter].SUB_CATEGORY_ID +'|'+ test[counter].CATEGORY_ID +'"><span id="subcatname">'+ test[counter].SUB_CATEGORY_NAME +'</span></td><td><button type="button" class="btn btn-default btn-xs cls_del_sub_cat" id="sub_category_id'+second_count+'" name="sub_category_id'+second_count+'"><span class="glyphicon glyphicon-trash"></span></button></td></tr>'));
				}
				
				if(sub_category_checker === false && loaded_sub_category_checker === false){
					$('#dept_sub_cat tbody').append($('<tr id="hid_subcat'+test[counter].CATEGORY_ID+'"><td><input type="hidden" class="cls_sub_cat" id="hid_subcat'+ counter +'" name="hid_subcat'+ counter +'" value="'+ test[counter].SUB_CATEGORY_ID +'|'+ test[counter].CATEGORY_ID +'"><span id="subcatname">'+ test[counter].SUB_CATEGORY_NAME +'</span></td></tr>'));
					ar_source.push([test[counter].CATEGORY_ID,test[counter].SUB_CATEGORY_ID]);
				}
				sub_category_checker = false;
				loaded_sub_category_checker = false;
			}
			
			$('#sub_cat_sup_count').val(second_count);
		};    

		ajax_request(ajax_type, url, post_params, success_function);
	}
    	
   get_msg_template();
   $(document).ready(function(){

   		var max_length = 300;
   		$('#txt_template_msg').on('keyup paste', function(e){
   			if (this.value.length > max_length) {
	            // Maximum exceeded
	            this.value = this.value.substring(0, max_length);
	        }

   			var characters = $('#txt_template_msg').val().length;
   			$('#msg_vendor_counter').text('Characters left: ' + (max_length - characters));
   		});

   		$('#txt_approver_note').on('keyup paste', function(e){
   			if (this.value.length > max_length) {
	            // Maximum exceeded
	            this.value = this.value.substring(0, max_length);
	        }

   			var characters = $('<div/>').text($('#txt_approver_note').val()).html().toString().length;//$('#txt_approver_note').val().length;
   			$('#note_counter').text('Characters left: ' + (max_length - characters));
   		});

   });
	
</script>