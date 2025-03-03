<?php
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
					<?php if($position_id == 5 || $position_id == 6): ?> <!-- 5 = vrdhead , 6 = uhats -->
						<!-- Added MSF - 20191105 (IJR-10612) -->
						<button type="button" id="btn_download" onclick="downloadImg()">Download</button>
						<button type="button" id="btn_printimg" onclick="printImg()">Print</button>
					<?php endif; ?>
				</span>
							
				<span class ="vi_contact_details_per_system" data-label="contact_person_label" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Contact Person Per SM Vendor System</h4>
				</span>
                <span class ="vi_vendor_id_pass_vendor_buh" style="display:none;">
                	<h4 class="modal-title" id="myModalLabel">Vendor ID Request Form</h4>
                </span>
                <span class ="vi_vendor_req_history" style="display:none;">
                    <h4 class="modal-title" id="myModalLabel">Request History</h4>
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
												<td>{{{APPROVER_REMARKS}}}</td> <!-- Jay {{{ Triple curly braces means output Text to HTML}}}-->
											</tr>
										{{/table_history}}
									</script>
								</tbody>
							</table>
							
						</div>
					</span>

					<span class="via_reject" style="display:none;">
						<textarea class="form-control limit-chars" placeholder="Please specify reason here" id="via_remarks" style="height: 100px" maxlength="300"></textarea>
					</span>
					<span class="document_preview" style="display:none;">
						<!-- <img src="" id="imagepreview" style="width: 400px; height: 264px;" > -->
						<!-- Updated MSF - 20191105 (IJR-10612) -->
						<!-- <iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>-->
						<div id='content' style="max-width: 1200px; width: 100%;">
							<div class='frame' id='frame' style="border: 1px solid #ccc; padding: 5px;">
								<img id='image_preview' src='' style="display: none">
								<embed src="" id="pdf_preview" style="width: 100%; height: 100%; display: none;" >
							</div>
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
					<span class="vi_vendor_id_pass_vendor_buh" style="display:none;">
                        <div class="panel panel-primary" style="overflow-y: scroll; height: 450px">
                            <table id="tbl_vendorid1" class="table table-bordered">
	                            <thead>
                                    <tr>
                                    	<th style="text-align:center" colspan="7">REQUEST FOR VENDOR ID/PASS FORM</th>
                                    </tr>
	                            </thead>
                                <tbody id = "vendor_request_data_body_vendor">
	                                <!-- <tr>
	                                        <td style="width:100px;" colspan="4"><strong>Date:</strong></td>
	                                        <td colspan="3"><input type="date" id="approval_date" name="approval_date" value="<?php echo date("Y-m-d"); ?>" readonly disabled="disabled" min="1997-01-01" max="2030-12-31"></td>
	                                </tr>
	                                <tr>
	                                        <td style="width:100px;" colspan="4"><strong>Vendor Name:</strong></td>
	                                        <td colspan="3"><input type="text" id="vendorname" name="vendorname" value=<?php echo $vendorname ?> readonly disabled="disabled" style="width: 350px"></td>
	                                </tr>
	                                <tr>
	                                        <td style="width:100px;" colspan="4"><strong>Vendor Code:</strong></td>
	                                        <td colspan="3"><input type="text" id="vendor_code" name="vendor_code" readonly disabled="disabled" style="width: 350px"></td>
	                                </tr>
	                                <tr>
	                                        <td style="width:200px;" colspan="4"><strong>Requestor's Email Address:</strong></td>
	                                        <td colspan="3"><input type="text" id="req_emailadd" name="req_emailadd" value="" readonly disabled="disabled" style="width: 500px"></td>
	                                </tr> -->
	                                <tr>
                                        <td colspan="7"><strong>Request Details:</strong></td>
                                    </tr>
                                    <tr class="info">
                                        <td style="text-align:center" colspan="7" data-label="vendor_id"><strong>Vendor ID<strong>
                                    </tr>
                                    <tr>
                                        <input type="hidden" id="vendorid_max" name="vendorid_max" value="5">
                                        <input type="hidden" class="vendorid_count" id="vendorid_count" name="vendorid_count" value="1">
                                        <td style ="text-align: right" colspan="7"><button type="button" class="btn btn-primary btn-xs" id="btn_add_vendorid" disabled><i class="glyphicon glyphicon-plus" aria-hidden="true"></i> Add Authorized Personnel</button></td>
                                    </tr>
                                                                                        
	                                <table class="table table-bordered" id="tbl_vendorid" afflop="1">
                                        <thead>
                                            <tr class="info">
                                                <th>No.</th>
                                                <th>Vendor Type</th>
                                                <th>First Name</th>
                                                <th>Middle Initial</th>
                                                <th>Last Name</th>
                                                <th>Designation</th>
                                                <th>Request Type</th>
                                                <th></th>
                                            </tr>
                                        </thead>
	                                                        
                                    <tbody id = "vendor_id_request_history_body_vendor">
                                                                                                
									<script id="vendor_id_request_history_template_vendor" type="text/template">
						                <tr id="tr_vendorid0" class="cls_tr_vendorid" hidden>
											<td><input type="checkbox" id="vendorid_no0" name="vendorid_no0" class="form-control input-xs cls_vendorid field-required limit-chars" data-label="vendorid_no" checked="true" disabled readonly></td>
											<td>
                                            	<select style = "overflow-y: auto; text-transform:uppercase" name="vendorid_vendor_type0" id="vendorid_vendor_type0" data_label="vendorid_vendor_type" class="form-control-2 cls_vendorid reqfield">
												<option value="" selected="selected" disabled>SELECT TRADE VENDOR TYPE</option>
												<option value="BOTH">BOTH</option>
                                                <option value="OUTRIGHT">OUTRIGHT</option>
                                                <option value="STORE CONSIGNOR">STORE CONSIGNOR</option>
                                            </td>
											<td><input type="text" id="vendorid_fname0" name="vendorid_fname0"  style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_firstname" maxlength="100" ></td>
											<td><input type="text" id="vendorid_minitial0" name="vendorid_minitial0" style="text-transform:uppercase" class="form-control-2 input-sm cls_vendorid limit-chars" data-label="vendorid_middleinitial"  maxlength="5""></td>
											<td><input type="text" id="vendorid_lname0" name="vendorid_lname0" style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_lastname"  maxlength="100""></td>
											<td><input type="text" id="vendorid_pos0" name="vendorid_pos0" style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_pos"  maxlength="100"></td>
											<td>
											<select style = "overflow-y: auto" name="reqtype0" id="reqtype0" data_label="reqtype" class="form-control-2 cls_vendorid" disabled readonly>
											{{#request_type}}
											<option value={{{REQUEST_TYPE_NAME}}}>{{{REQUEST_TYPE_NAME}}}</option>
											{{/request_type}}
											</select>
					                        </td>
					                        <td><button type="button" class="btn btn-default btn-xs cls_del_vendorid"  id= "btn_del_vendorid0" ><i class="glyphicon glyphicon-trash"></i></button></td>
					                        <input type="hidden" id="vendorid_datafrom0" name="vendorid_datafrom0" value="">
						                </tr>
						                {{#vendor_id_request_table_vendor}}        
				                        <tr id="tr_vendorid{{COUNT}}" class="cls_tr_vendorid" authflop="{{COUNT}}">
				                                <?php if(IS_CHECKED == 1) : ?>
													<td><input type="checkbox" id="vendorid_no{{COUNT}}" name="vendorid_no{{COUNT}}" class="form-control input-xs cls_vendorid field-required limit-chars" data-label="vendorid_no{{COUNT}}" disabled readonly></td>
                                        		<?php else : ?>
													<td><input type="checkbox" id="vendorid_no{{COUNT}}" name="vendorid_no{{COUNT}}" class="form-control input-xs cls_vendorid field-required limit-chars" data-label="vendorid_no{{COUNT}}" checked="true" disabled readonly></td>
                                        		<?php endif; ?>
                                        		<td>
                                                    <select style = "overflow: auto; text-transform:uppercase" name="vendorid_vendor_type{{COUNT}}" id="vendorid_vendor_type{{COUNT}}" data_label="vendorid_vendor_type{{{COUNT}}}" class="form-control-2 cls_vendorid" value="{{TRADE_VENDOR_TYPE}}" disabled>
                                                            <option value="{{TRADE_VENDOR_TYPE}}">{{TRADE_VENDOR_TYPE}}</option>
                                                            
                                                    </select>
	                                            </td>
				                                <td><input type="text" id="vendorid_fname{{COUNT}}" name="vendorid_fname{{COUNT}}"  style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_firstname{{COUNT}}" maxlength="100" value="{{{FIRST_NAME}}}" readonly></td>
				                                <td><input type="text" id="vendorid_minitial{{COUNT}}" name="vendorid_minitial{{COUNT}}" style="text-transform:uppercase" class="form-control-2 input-sm cls_vendorid limit-chars" data-label="vendorid_middleinitial{{COUNT}}"  maxlength="5" value="{{{MIDDLE_INITIAL}}}" readonly></td>
				                                <td><input type="text" id="vendorid_lname{{COUNT}}" name="vendorid_lname{{COUNT}}" style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_lastname{{COUNT}}"  maxlength="100" value="{{{LAST_NAME}}}" readonly></td>
				                                <td><input type="text" id="vendorid_pos{{COUNT}}" name="vendorid_pos{{COUNT}}" style="text-transform:uppercase" class="form-control input-sm cls_vendorid field-required limit-chars" data-label="vendorid_pos{{COUNT}}"  maxlength="100" value="{{{DESIGNATION}}}" readonly></td>
				                                <td>
				                                        <select style = "overflow: auto" name="reqtype{{COUNT}}" id="reqtype{{COUNT}}" data_label="reqtype{{{COUNT}}}s" class="form-control-2 cls_vendorid" disabled readonly>
				                                                
				                                                <option value={{{REQUEST_TYPE}}}>{{{REQUEST_TYPE}}}</option>
				                                                
				                                        </select>
				                                </td>
				                                <td><button type="button" class="btn btn-default btn-xs cls_del_vendorid"  id= "btn_del_vendorid{{COUNT}}" disabled><i class="glyphicon glyphicon-trash"></i></button></td>
				                                <input type="hidden" id="vendorid_datafrom{{COUNT}}" name="vendorid_datafrom{{COUNT}}" value="">
			                        	</tr>
					        			{{/vendor_id_request_table_vendor}}
						        	</script>
								</tbody>        
                                                                                                                                
                                </table>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="info">
                                            <td style="text-align:center" colspan="7" data-label="vendor_pass"><strong>Vendor Pass<strong></td>
                                    </tr>
                                    </thead>
                                    <tbody id = "vendor_pass_request_history_body_vendor">        
                                    <script id="vendor_pass_request_history_template_vendor" type="text/template">
                            {{#vendor_pass_request_table_vendor}}       
                            		<tr>
										<td><strong>Vendor Type:<strong></td>
										<td><strong>Request Type:<strong></td>
										<td><strong>Qty of Vendor Pass Request:<strong></td>
										
									</tr>  

							{{#outright}} 
                                    <tr>
                                    		<td><input type="text" id="vendorpass_outright" name="vendorpass_outright" style="text-transform:uppercase" class="form-control input-sm cls_vendorid limit-chars reqfield" data-label="vendorpass_outright"  value="OUTRIGHT" disabled></td>
											<td>
											<select name="reqtype_pass_outright" id="reqtype_pass_outright" class="form-control-2 cls_vendorid" value="{{REQUEST_TYPE}}" disabled> 
											<option value="{{REQUEST_TYPE}}">{{REQUEST_TYPE}}</option>
                                            {{#request_type}}
											<option value="{{REQUEST_TYPE_NAME}}">{{{REQUEST_TYPE_NAME}}}</option>
                                            {{/request_type}}
											</select>
											</td>
											<td><input type="text" min="0" max="5" maxlength="4" id="qty_vendor_outright" name="qty_vendor_outright" class="form-control input-sm cls_yr_qty field-required numeric" oninput="computeFields()"  onkeydown="return event.keyCode !== 69" value="{{{QTY}}}" disabled></td>
                                    </tr>
                            {{/outright}}
                            {{#sc}} 
                                    <tr>
                                    		<td><input type="text" id="vendorpass_sc" name="vendorpass_sc" style="text-transform:uppercase" class="form-control input-sm cls_vendorid limit-chars reqfield" data-label="vendorpass_sc"  value="STORE CONSIGNOR" disabled></td>
                                            <td>
                                            <select name="reqtype_pass_sc" id="reqtype_pass_sc" class="form-control-2 cls_vendorid" value="{{REQUEST_TYPE}}" disabled readonly>
                                            <option value={{{REQUEST_TYPE}}}>{{{REQUEST_TYPE}}}</option>
                                            </select>
                                            </td>
                                            <td><input type="text" min="0" max="5" maxlength="4" id="qty_vendor_sc" name="qty_vendor_sc" class="form-control input-sm cls_yr_qty field-required numeric" oninput="computeFields()"  onkeydown="return event.keyCode !== 69" value="{{{QTY}}}" disabled></td>
                                    </tr>
                            {{/sc}}
                                    <tr class="info">
                                    	<td style="text-align:center" colspan="7" data-label="vendor_id"><strong> Total Vendor ID/Pass<strong>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center; width:35%;" colspan="1"><strong>Total ID/Pass Qty Requested:</strong></td>
                                        <td colspan="6"><input type="text" id="total_qty" name="total_qty" style="width:100%;box-sizing:border-box;" readonly disabled value={{{TOTAL_PASS_QTY}}}></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center; width:35%;" colspan="1"><strong>Total Amount for Deduction:</strong></td>
                                        <td colspan="6"><input type="text" id="total_amount" name="total_amount" style="width:100%;box-sizing:border-box;" readonly disabled value="{{{TOTAL_AMOUNT_DEDUCTION}}}""></td>
                                    </tr>
                                    {{#amount}}
                                    <tr>
                                    	<td colspan="6"><input type="hidden" id="amount" name="amount" style="width:100%;box-sizing:border-box;" value="{{{AMOUNT}}}""></td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px" colspan="7"><strong>Notes:<br>1. The cost of Vendor ID/Pass is Php {{{AMOUNT}}} each<br>2. For Vendor Pass requests, the maximum quantity is five (5) only. This card is transferrable.
                                        <br>3. For Vendor ID requests, vendors are required to visit SM Retail HQ for picture-taking once a vendor code is assigned. This card is non-transferrable.<strong>
                                    </td>
                                    </tr>
                                    
                                    <tr style="background-color: black; color: white" >
                                        <td style="font-size: 11px" colspan="7"><strong>Authority to Deduct:
                                        <br><input type="checkbox" id="auth_deduct" name="auth_deduct" value="1" onChange="enabled_auth()" checked="true" disabled readonly> I am authorizing SM to deduct the amount equivalent to the number of requested Vendor ID and Vendor Pass @ {{{AMOUNT}}} each for offset against my account via Debit Memo</strong>
                                        </td>
                                    </tr>
                                    {{/amount}}
                    				{{/vendor_pass_request_table_vendor}}
		                				</script>
		                            	</tbody>
		                                <tr>        
		                                    <td style="text-align: right" colspan='3'><a class="cls_mobno" href="#" id="vi_vendor_req_history" name="reqhist1">View Request History</a></td>
		                                </tr>
		                			</table>
		                    	</tbody>
		                	</table>
		            	</div>
		        		</span>
				        <span class="vi_vendor_req_history" style="display:none;">
				        <div class="panel panel-primary">
				                <div class="panel-heading">
				                <h3 class="panel-title">Vendor Request History</h3>
				                </div>
				                <table id="tbl_history" class="table table-bordered">
				                        <thead>
				                                <tr class="info">
				                                        <th>Date of Request</th>
				                                        <th>Vendor Type</th>
				                                        <th>Request Type</th>
				                                        <th>ID Type</th>
				                                        <th>First Name</th>
                                                        <th>Middle Initial</th>
                                                        <th>Last Name</th>
				                                        <th>Designation</th>
				                                        <th>Qty</th>
				                                </tr>
				                        </thead>
				                        <tbody id="vendor_request_history_body_vendor">
				                                <script id="vendor_request_history_template_vendor" type="text/template">
				                                        {{#vendor_request_history_table_vendor}}
				                                                <tr>
				                                                        <td>{{DATE_OF_REQUEST}}</td>
				                                                        <td>{{TRADE_VENDOR_TYPE}}</td>
				                                                        <td>{{REQUEST_TYPE}}</td>
				                                                        <td>{{ID_TYPE}}</td>
				                                                        <td>{{FIRST_NAME}}</td>
                                                                        <td>{{MIDDLE_INITIAL}}</td>
                                                                        <td>{{LAST_NAME}}</td>
				                                                        <td>{{DESIGNATION}}</td>
				                                                        <td>{{{QUANTITY}}}</td>
				                                                </tr>
				                                        {{/vendor_request_history_table_vendor}}
				                                </script>
				                        </tbody>
				                </table>
				                
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
				<span class="document_preview" style="display:none;">
					<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
				</span>
			</div>
		</div>
	</div>
</div>

	<div class="modal fade" id="myModalreject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
             	<div class="modal-header">					
					<span class="reject_shortlist" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Reject Reason</h4>
					</span>						
					<span class="suspend" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Suspend Reason</h4>
					</span>					
				</div>
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal">
								<textarea class="form-control field-required  limit-chars" placeholder="Please specify reason here" id="reject_remarks" maxlength="4000" style="resize: vertical;height: 100px"></textarea>
                       </div>
                  </div>
                  <div class="modal-footer">
                    <span class="reject_shortlist" style="display:none;">
						<button type="button" class="btn btn-primary" onclick="validate_approve_reject(0)">ok</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
					</span>
                    <span class="suspend" style="display:none;">
						<button type="button" class="btn btn-primary" onclick="validate_approve_reject(2)">ok</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">cancel</button>
					</span>
                  </div>
             </div>
        </div>
	</div>
<!-- END Modal -->

<div class="container mycontainer">


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
					
					if(in_array( $position_id ,$waive_permission_ids)){
						$has_full_waive_permission = true;
						$has_edit_waive_remark_permission = true;
					}else if(in_array( $position_id ,$waive_edit_remarks_permission_ids)){
						$has_full_waive_permission = false;
						$has_edit_waive_remark_permission = true;
					}
					//Jay end
				?>



	<div class="pull-right">
			<?php if($position_id == 5 || $position_id == 6): ?> <!-- 5 = vrdhead , 6 = uhats -->
				<div class="btn-group">
					<button type="buttton" class="btn btn-primary " onclick="printJS()">Print</button>
				</div>
			<?php endif; ?>
			<input type="button" class="btn btn-primary " id="btn_approval_approve"<?=$is_open?> value="Approve" onclick="validate_approve_reject(1);">
			<input type="button" class="btn btn-primary " id="btn_approval_reject"<?=$is_open?> value="Reject" onclick="reject_approval()">
			<?=$suspended?>
			<input type="button" class="btn btn-primary btn-exit" id="btn_approval_reject"<?=$is_open?> value="Exit">	
			<!-- <input type="button" class="btn btn-primary " value="Exit"> -->
	</div>

	<h4>Vendor Registration</h4>
	<small><a href="#" id="vi_approval_history">Approval History</a></small>
	
	<?php
		echo('<small><a href="#" id="vi_previous_company_name" class="cls_action"><span class="small"></a></small>');	
		echo('<small><a href="#" id="vi_new_company_name" class="cls_action"><span class="small"></a></small>');	
	?>
	
	<div class="form_container">
	<div class="panel panel-default">
						<div class="panel-body">
			
		<div class="col-sm-12">
			<div class="form-group">
				<div class="col-sm-2">
					<label for="vendor_name" class="control-label">  Type of Invite : </label>
				</div>
				<div class="col-sm-6">
					<p class="form-control-static no-padding" id="vendor_invite_type" name="vendor_invite_type">CHANGE IN COMPANY NAME</p>
				</div>

			</div>
			</div>
		<?php if($registration_type == 5){?>
		<div class="col-sm-12">
				<div class="col-sm-2">
					<label for="vendor_name" class="control-label">  Old Vendor Name : </label>
				</div>
				<div class="col-sm-4">
						 <p class="form-control-static no-padding" id="vendor_name"><?php echo $cc_vendor_name ?></p>
				</div>
				<div class="col-sm-2">
					<label for="vendor_name" class="control-label">  Old Vendor Code : </label>
				</div>
				<div class="col-sm-4">
						 <p class="form-control-static no-padding" id="vendor_name"><?php echo $cc_vendor_code ?></p>
				</div>
			</div>
		<?php } ?>
			<div class="col-sm-12">
			<div class="form-group">
				<label for="vendor_name" class="control-label col-md-2 col-sm-6 ">Vendor Name</label>

				<div class="col-md-8 col-sm-6">
					 <p class="form-control-static no-padding"><?=$vendor_name?></p>
				</div>
			
			</div>
		</div>
		<form id="frm_registration" name="frm_registration" method="post" enctype="multipart/form-data">
			<input type="hidden" id="view_only" name="view_only" value="1">
			<input type="hidden" id="last_click" name="last_click" value="">
			<input type="hidden" id="num_click" name="num_click" value="0">
			<input type="hidden" id="invite_id" name="invite_id" value="<?php echo $invite_id; ?>">
			<input type="hidden" id="vendor_id" name="vendor_id" value="<?php echo $vendor_id; ?>">
			<input type="hidden" id="vendor_name_01" name="vendor_name_01" value="<?php echo $vendor_name ?>">
			<input type="hidden" id="registration_type" name="registration_type" value="<?php echo $registration_type ?>">
			<!-- <input type="hidden" id="vendor_invite_type2" name="vendor_invite_type2" value="<?php echo $vendor_invite_type; ?>"> -->
			<input type="hidden" id="trade_vendor_type2" name="trade_vendor_type2" value="<?php echo $trade_vendor_type; ?>">
			<input type="hidden" id="vendor_type2" name="vendor_type2" value="<?php echo $vendor_type; ?>">
			<input type="hidden" id="reg_type_id" name="reg_type_id" value="<?php echo $reg_type_id; ?>">
			<input type="hidden" id="position_id" name="position_id" value="<?php echo $position_id; ?>">
			<input type="hidden" id="status_id" name="status_id" value="<?php echo $status_id; ?>">
			<div class="row">
				<div class="col-sm-12">
					<!-- Added & Modified MSF 20200924 -->
					<div class="col-sm-6" style="height:200px;">
						<div class="form-group">
							<label for="brand_name" class="col-sm-1 control-label"></label>
							<!-- <button type="button" id="btn_add_brand" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

							<div class="col-sm-12" id="div_brand">								
								<input type="hidden" id="brand_count" name="brand_count" value="1">
								<div class="input-group cls_div_brand" id="div_brandid1">
									<input type="hidden" class="form-control input-sm cls_brand" id="brand_id1" name="brand_id1" >
									<input type="text" class="form-control input-sm cls_brand" id="brand_name1" name="brand_name1" placeholder="Brand" list="brand_list">

									<!-- <span class="glyphicon glyphicon-trash input-group-addon remove_brand"></span> -->
								</div>
							</div>
							<datalist id="brand_list">
							  <?php 

							  if(!empty($filter_brand)){
							  	  foreach ($filter_brand as $row){
										echo '<option data-brandid="'.$row->BRAND_ID.'" value="'.$row->BRAND_NAME.'">';
								}

							  }
							?>	
							</datalist>
						</div>
					</div>
					
					<div class="col-sm-6">
						<div class="form-group">
							<label for="cbo_yr_business" class="col-sm-4 control-label">Years in Business</label>

							<div class="col-sm-8">
								<input type="text" id="cbo_yr_business" name="cbo_yr_business" class="form-control" readonly style="width:50px; ">
								<!-- <select id="cbo_yr_business" name="cbo_yr_business" class="form-control ">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
								</select> -->
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Ownership</label>
							<div class="col-sm-8" id="ownershipname">

							</div>
							<div class="col-sm-8" style="display: none;">
								<label class="radio-inline">
							      <input type="radio" name="ownership" value="1" class="" style="display: none;"><!-- Corporation -->
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="2" class="" style="display: none;"><!-- Partnership -->
							    </label>
							    <br>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="3" class="" style="display: none;"><!-- Sole Proprietorship -->
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="ownership" value="4" class="" style="display: none;"><!-- Free Lance -->
							    </label>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label">Vendor Type</label>
							<div class="col-sm-8" id="vendor_type_name">
							</div>
							<div class="col-sm-8" style="display: none;">
								<label class="radio-inline">
							      <input type="radio" name="vendor_type" value="1" class="" style="display: none;"><!-- Trade -->
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type" value="2" class="" style="display: none;"><!-- NonTrade -->
							    </label>
							    <label class="radio-inline">
							      <input type="radio" name="vendor_type" value="3" class="" style="display: none;"><!-- NonTrade -->
							    </label>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label">No. of Employees</label>
							<div class="col-sm-8" id="no_of_employee"></div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label class="col-sm-4 control-label">MSME Business Asset Classification</label>
							<div class="col-sm-8" id="business_asset"></div>
						</div>
					</div>
					<div class="col-sm-6">
						<?php if($vendor_type == 1):?>
						<div class="form-group">
							<label class="col-sm-4 control-label">Trade Vendor Type</label>

							<div class="col-sm-8">
								<label class="radio-inline">
							  <input type="checkbox" name="chk_trade_vendor_type" value="1" style="display: none;" disabled>
							      <input type="radio" name="trade_vendor_type" value="1" disabled>Outright
							    </label>
							    <label class="radio-inline">
							  <input type="checkbox" name="chk_trade_vendor_type" value="2" style="display: none;" disabled>
							      <input type="radio" name="trade_vendor_type" value="2" disabled>Consignor
							    </label>
							    <label class="radio-inline"> <!-- do not show this -->
								<input type="radio" name="trade_vendor_type" value="0" style="display: none;"> 
							    </label>
							</div>
						</div>
						<?php endif;?>
					</div>
					<div class="col-sm-6">
					<?php if($vendor_code_02 != ''){ ?>
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
					<?php } ?>
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
								<label for="tax_idno" class="col-sm-5 control-label">Tax Identification No</label>

								<div class="col-sm-7">
									<input type="text" class="form-control input-sm " id="tax_idno" name="tax_idno" readonly placeholder="Tax Identification No" >
								</div>
							</div>

							<br>
							<div class="form-group">
								<label class="col-sm-4 control-label">Tax Classification</label>
								<div id="tax_classifi">
								</div>
								<div class="col-sm-8">
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
								    <input type="text" id="txt_nob_others" name="txt_nob_others" value="" disabled class="form-control input-sm" placeholder="Others">
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
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_off_ad" name="btn_off_ad"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
						</label>
						<input type="hidden" id="office_addr_count" name="office_addr_count" value="1">
						<!-- <label class="control-label col-sm-offset-9">Primary</label> -->
					</div>
				</div>
			</div>

			<div class="row" id="div_row_offc_addr">	
				<div class="col-sm-12 cls_div_office_addr" id="div_office_addr1" name="div_office_addr1">
					<div class="form-group">                
		                <!-- <div class="col-sm-3">
		                	
		                    <input id="office_add1" name="office_add1" type="text" placeholder="Unit #/BLDG &#x09; Street"
		                    class="form-control input-sm cls_office_addr">                    
		                </div> -->
						<div id="address_table" class="col-sm-12" style="padding: 0 15px 0 15px;">
		                </div>

		                <!-- <div class="col-sm-2">
		                    <select name="office_brgy_cm1" id="office_brgy_cm1" class="form-control input-sm cls_office_addr">
		                    	<option value="" selected disabled>Brgy, City/Municipal</option>
								<option value="1">Makati</option>
								<option value="2">Pasay</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <select name="office_state_prov1" id="office_state_prov1" class="form-control input-sm cls_office_addr">
		                    	<option value="" selected disabled>State/Province</option>
								<option value="1">Metro Manila</option>
								<option value="2">EKEK</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <input id="office_zip_code1" name="office_zip_code1" type="text" placeholder="Zip Code"
		                    class="form-control input-sm cls_office_addr">                    
		                </div>

		                <div class="col-sm-2">
		                    <select name="office_country1" id="office_country1" class="form-control input-sm cls_office_addr">
		                    	<option value="" selected disabled>Country</option>
								<option value="1">Philippines</option>
								<option value="2">USA</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="office_primary" value="1">
							      <button type="button" class="btn btn-default btn-xs remove_offc_addr"><span class="glyphicon glyphicon-trash"></span></button>
							</label>
		                </div> -->

		            </div>
	            </div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<br>
						<label class="control-label col-sm-2">Factory Address
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_factory_addr"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
						</label>
						<input type="hidden" id="factory_addr_count" name="factory_addr_count" value="1">
					</div>
				</div>
			</div>

			<div class="row" id="div_row_factory_addr">
				<div class="col-sm-12 cls_div_factory_addr" id="div_factory_addr1" name="div_factory_addr1">
					<div class="form-group">       
						<div id="factory_address" class="col=sm-12" style="padding: 0 15px 0 15px;">
						</div>         
		                <!-- <div class="col-sm-3">
		                    <input id="factory_addr1" name="factory_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street"
		                    class="form-control input-sm cls_factory_addr">                    
		                </div>

		                <div class="col-sm-2">
		                    <select id="factory_brgy_cm1" name="factory_brgy_cm1" class="form-control input-sm cls_factory_addr">
		                    	<option value="" selected disabled>Brgy, City/Municipal</option>
								<option value="1">Makati</option>
								<option value="2">Pasay</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <select id="factory_state_prov1" name="factory_state_prov1" class="form-control input-sm cls_factory_addr">
		                    	<option value="" selected disabled>State/Province</option>
								<option value="1">Metro Manila</option>
								<option value="2">EKEK</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <input id="factory_zip_code1" name="factory_zip_code1" type="text" placeholder="Zip Code"
		                    class="form-control input-sm cls_factory_addr">                    
		                </div>

		                <div class="col-sm-2">
		                    <select id="factory_country1" name="factory_country1" class="form-control input-sm cls_factory_addr">
		                    	<option value="" selected disabled>Country</option>
								<option value="1">Philippines</option>
								<option value="2">USA</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="factory_primary" value="1">
							      <button type="button" class="btn btn-default btn-xs remove_factory_addr"><span class="glyphicon glyphicon-trash"></span></button>
							      
							</label>
		                </div> -->

		            </div>
		        </div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<br>
						<label class="control-label col-sm-3">Warehouse Address
						<!-- <button type="button" class="btn btn-primary btn-xs" id="btn_wh_addr"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
						</label>
						<input type="hidden" id="wh_addr_count" name="wh_addr_count" value="1">
					</div>
				</div>
			</div>

			<div class="row" id="div_row_wh_addr">
				<div class="col-sm-12 cls_div_wh_addr" id="div_wh_addr1" name="div_wh_addr1">
					<div class="form-group">    
						<div id="warehouse_address" class="col=sm-12" style="padding: 0 15px 0 15px;">
						</div>            
		                <!-- <div class="col-sm-3">
		                    <input id="ware_addr1" name="ware_addr1" type="text" placeholder="Unit #/BLDG &#x09; Street"
		                    class="form-control input-sm cls_wh_addr">                    
		                </div>

		                <div class="col-sm-2">
		                    <select id="ware_brgy_cm1" name="ware_brgy_cm1" class="form-control input-sm cls_wh_addr">
		                    	<option value="" selected disabled>Brgy, City/Municipal</option>
								<option value="1">Makati</option>
								<option value="2">Pasay</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <select id="ware_state_prov1" name="ware_state_prov1" class="form-control input-sm cls_wh_addr">
		                    	<option value="" selected disabled>State/Province</option>
								<option value="1">Metro Manila</option>
								<option value="2">EKEK</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-2">
		                    <input id="ware_zip_code1" name="ware_zip_code1" type="text" placeholder="Zip Code"
		                    class="form-control input-sm cls_wh_addr">                    
		                </div>

		                <div class="col-sm-2">
		                    <select id="ware_country1" name="ware_country1" class="form-control input-sm cls_wh_addr">
		                    	<option value="" selected disabled>Country</option>
								<option value="1">Philippines</option>
								<option value="2">USA</option>
								<option value="3">QC</option>
							</select>
		                </div>

		                <div class="col-sm-1">
			               <label class="radio-inline">
							      <input type="radio" name="ware_primary" value="1">
							      <button type="button" class="btn btn-default btn-xs remove_wh_addr"><span class="glyphicon glyphicon-trash"></span></button>
							</label>
		                </div> -->
		            </div>
	            </div>
			</div>


			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<br>
						<label class="control-label col-sm-3">Contact Details
						</label>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">    
						<div id="contact_details" class="col=sm-12" style="padding: 0 15px 0 15px;">
						</div>            
		                
		            </div>
	            </div>
			</div>

			<div class="row">
				<a href="#" id="vi_contact_details_per_system" style="margin-left: 40px;">* Contact Details per SM Vendor System</a>
			</div>
			<br/>
<!-- 
			<div class="row">
			<br><br>

				<div class="col-sm-12">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="tel_no" class="col-sm-2 control-label">Tel No.</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_telno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-9" id="div_telno">
								<input type="hidden" id="telno_count" name="telno_count" value="1">
								<div class="input-group">
									<input type="text" class="form-control input-sm cls_telno" id="tel_no1" name="tel_no1" placeholder="Tel No">
									<span class="glyphicon glyphicon-trash input-group-addon remove_telno"></span>
								</div>							
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label">Email</label>
							<button type="button" class="btn btn-primary btn-xs"  id="btn_add_email"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-9" id="div_email">
								<input type="hidden" id="email_count" name="email_count" value="1">
								<div class="input-group">
									<input type="text" class="form-control input-sm cls_email" id="email1" name="email1" placeholder="Email" >
									<span class="glyphicon glyphicon-trash input-group-addon remove_email"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<br>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="col-sm-6">
						<div class="form-group">
							<label for="fax_no" class="col-sm-2 control-label">Fax No.</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_faxno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-9" id="div_faxno">
								<input type="hidden" id="faxno_count" name="faxno_count" value="1">
								<div class="input-group">
									<input type="text" class="form-control input-sm cls_faxno" id="fax_no1" name="fax_no1" placeholder="Fax No." >
									<span class="glyphicon glyphicon-trash input-group-addon remove_faxno"></span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-6">
						<div class="form-group">
							<label for="mobile_no" class="col-sm-2 control-label">Mobile No</label>
							<button type="button" class="btn btn-primary btn-xs" id="btn_add_mobno"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>

							<div class="col-sm-9" id="div_mobno">
								<input type="hidden" id="mobno_count" name="mobno_count" value="1">
								<div class="input-group">
									<input type="text" class="form-control input-sm cls_mobno" id="mobile_no1" name="mobile_no1" placeholder="Mobile No">
									<span class="glyphicon glyphicon-trash input-group-addon remove_mobno"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<br>
					</div>
				</div>
			</div> -->

			<div class="row">
				<div class="col-sm-12">
					<div class="col-md-6 col-sm-12 no-padding">
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
											<td><input type="text" id="opd_fname1" name="opd_fname1" readonly placeholder="First Name" class="form-control input-sm cls_opd"></td>
											<td><input type="text" id="opd_mname1" name="opd_mname1" readonly placeholder="Middle Name" class="form-control input-sm cls_opd"></td>
											<td><input type="text" id="opd_lname1" name="opd_lname1" readonly placeholder="Last Name" class="form-control input-sm cls_opd"></td>
											<td><input type="text" id="opd_pos1" name="opd_pos1" readonly placeholder="Position" class="form-control input-sm cls_opd"></td>
											<td align="center"><input type="checkbox" id="opd_auth1" name="opd_auth1" class="input-sm cls_opd" disabled></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="col-md-6 col-sm-12 no-padding">
						<div class="form-group">
							<label for="" class="col-sm-7 control-label">Authorized Representatives <small>(Max <input type="text" id="authrep_max" name="authrep_max" value="5" style="background:rgba(0,0,0,0);border:none; width: 6px;height: 15px" readonly>)</small></label>
							<input type="hidden" id="authrep_count" name="authrep_count" value="1">
							<input type="hidden" id="is_watsons" name="is_watsons" value="0">
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
											<td><input type="text" id="authrep_fname1" name="authrep_fname1" readonly placeholder="Name" class="form-control input-sm cls_authrep"></td>
											<td><input type="text" id="authrep_mname1" name="authrep_mname1" readonly placeholder="Name" class="form-control input-sm cls_authrep"></td>
											<td><input type="text" id="authrep_lname1" name="authrep_lname1" readonly placeholder="Name" class="form-control input-sm cls_authrep"></td>
											<td><input type="text" id="authrep_pos1" name="authrep_pos1" readonly placeholder="Position" class="form-control input-sm cls_authrep"></td>
											<td align="center"><input type="checkbox" id="authrep_auth1" name="authrep_auth1" class="input-sm cls_authrep" disabled></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
                <a href="#" id="vi_vendor_id_pass_vendor_buh" style="margin-left: 40px;">* Request for Vendor ID/Pass</a>

                <p style="margin-left: 40px;" id="watsons_vendor_id_pass">* Request for Vendor ID/Pass</p>
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
			
			
		<div class="row">
			<div class="col-sm-12">
				<div class="col-md-12 col-sm-12 no-padding">
					<div class="form-group">
						<label for="" class="col-sm-4 control-label" name="dept" id="dept"></label>
						<div class="col-sm-12">
							<input type="hidden" id="cat_sup_count" name="cat_sup_count" value="0">
							<table class="table table-bordered" style="margin-bottom:0;" id="cat_sup">
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
											<td><input type="text" id="bankrep_name1" name="bankrep_name1" readonly placeholder="Name" class="form-control input-sm cls_bankrep"></td>
											<td><input type="text" id="bankrep_branch1" name="bankrep_branch1" readonly placeholder="Branch" class="form-control input-sm cls_bankrep"></td>
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
											<td><input type="text" id="orcc_compname1" name="orcc_compname1" readonly placeholder="Company Name" class="form-control input-sm cls_orcc"></td>
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
					<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

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
										<td><input type="text" id="ob_compname1" name="ob_compname1" readonly placeholder="Company Name" class="form-control input-sm cls_otherbusiness"></td>
										<td><input type="text" id="ob_pso1" name="ob_pso1" readonly placeholder="Products/Services Offered" class="form-control input-sm cls_otherbusiness"></td>
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
					<!-- <button class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->

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
										<td><input type="text" id="affiliates_fname1" name="affiliates_fname1" readonly placeholder="First Name" class="form-control input-sm cls_affiliates"></td>
										<td><input type="text" id="affiliates_lname1" name="affiliates_lname1" readonly placeholder="Last Name" class="form-control input-sm cls_affiliates"></td>
										<td><input type="text" id="affiliates_pos1" name="affiliates_pos1" readonly placeholder="Position" class="form-control input-sm cls_affiliates"></td>
										<td><input type="text" id="affiliates_comp_afw1" name="affiliates_comp_afw1" readonly placeholder="Company Affiliated With" class="form-control input-sm cls_affiliates"></td>
										<td><input type="text" id="affiliates_rel1" name="affiliates_rel1" readonly placeholder="Relationship" class="form-control input-sm cls_affiliates"></td>
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
						
						<?php if($has_full_waive_permission): ?>
							<!--<input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}">-->
						<?php elseif($has_edit_waive_remark_permission):?>
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
									<?php if( ! empty($waive_rsd_document_chk)): ?>
									
										<?php if($has_edit_waive_remark_permission): ?>
											<th>N/A</th>
										<?php endif; ?>
									
									<?php endif; ?>
									<th>View</th>
									<th>Date Uploaded</th>
									<th>File Name</th>
									<th>Reviewed</th>
									<th>Date Reviewed</th>
									<th>Verified</th>
									<th>Date Verified</th>
								</tr>
							</thead>
							<tbody id="rsd_body">
								<script id="tbl_rsd_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
									{{#rsd_table_template}}
										<tr>
											<td><input type="checkbox" id="rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled></td>
											<td>{{REQUIRED_DOCUMENT_NAME}}</td>
											<?php if( ! empty($waive_rsd_document_chk)): ?>
											
											<?php if($has_full_waive_permission): ?>
												<td><input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}"></td>
											<?php elseif($has_edit_waive_remark_permission):?>
												<td><input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}" disabled></td>
											<?php endif; ?>		
											
											<?php endif; ?>
											<td><button type="button" id="btn_rsd_preview{{REQUIRED_DOCUMENT_ID}}" name="btn_rsd_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td><input type="text" class="form-control input-sm" id="rsd_date_upload{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_upload{{COUNT}}" value="" readonly></td>
											<td><input type="text" class="form-control input-sm" id="rsd_orig_name{{REQUIRED_DOCUMENT_ID}}" name="rsd_orig_name{{COUNT}}" value="" readonly></td>
											<td><input type="checkbox" id="rsd_document_reviewed_chk{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_reviewed_chk{{COUNT}}" value="{{REVIEWED}}" disabled></td>
											<td><input type="text" class="form-control input-sm" id="rsd_date_review{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_review{{COUNT}}" value="" readonly></td>
											<td><input type="checkbox" id="rsd_document_verified_chk{{REQUIRED_DOCUMENT_ID}}" name="rsd_document_verified_chk{{COUNT}}" value="{{VERIFIED}}" disabled></td>
											<td><input type="text" class="form-control input-sm" id="rsd_date_verified{{REQUIRED_DOCUMENT_ID}}" name="rsd_date_verified{{COUNT}}" value="" readonly></td>
										</tr>
									{{/rsd_table_template}}
								</script>
							</tbody>
							</table>
								
							<?php if(( ! empty($rsd_remarks)) && ($has_full_waive_permission || $has_edit_waive_remark_permission)): ?>
								<strong id="rsd_waive_lbl">Waive Remarks:</strong>
								<textarea  placeholder="Enter text here..."  class="form-control" name="rsd_waive_remarks" id="rsd_waive_remarks"style="min-height: 150px; height:150px; 
										max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly><?php echo $rsd_remarks; ?></textarea>		
							<?php elseif($has_full_waive_permission):?>
								<strong id="rsd_waive_lbl">Waive Remarks:</strong>
								<textarea  placeholder="Enter text here..."  class="form-control" name="rsd_waive_remarks" id="rsd_waive_remarks"style="min-height: 150px; height:150px; 
										max-height:250px; width:100%; min-width:100%;max-width:100%;"><?php echo $rsd_remarks; ?></textarea>		
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
                			</h3>

						</div>
					</div>
					<div class="panel-body">
						<?php if( ! empty($waive_ad_document_chk)): ?>
						
						<?php if($has_full_waive_permission): ?>
							<!--<input type="checkbox" id="waive_rsd_document_chk{{REQUIRED_DOCUMENT_ID}}" name="waive_rsd_document_chk{{COUNT}}" value="{{REQUIRED_DOCUMENT_ID}}">-->
						<?php elseif($has_edit_waive_remark_permission):?>
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
									<?php if( ! empty($waive_ad_document_chk)): ?>
									
										<?php if($has_edit_waive_remark_permission): ?>
											<th>N/A</th>
										<?php endif; ?>
									
									<?php endif; ?>
									<th>View</th>
									<th>Date Uploaded</th>
									<th>File Name</th>
									<th>Reviewed</th>
									<th>Date Reviewed</th>
									<th>Verified</th>
									<th>Date Verified</th>
								</tr>
							</thead>
							<tbody id="ra_body"><!-- id has value of docment id from db for assigning value while name has a loop value (count) for post -->
								<script id="tbl_ra_template" type="text/template"> <!-- id has value of docment id from db while name has a loop value (count)  -->
									{{#ra_table_template}}
										<tr>
											<td><input type="checkbox" id="ra_document_chk{{REQUIRED_AGREEMENT_ID}}" name="ra_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled></td>
											<td>{{REQUIRED_AGREEMENT_NAME}}</td>
											<?php if( ! empty($waive_ad_document_chk)): ?>
											
											<?php if($has_full_waive_permission): ?>
												<td><input type="checkbox" id="waive_ad_document_chk{{REQUIRED_AGREEMENT_ID}}" name="waive_ad_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}"></td>
											<?php elseif($has_edit_waive_remark_permission):?>
												<td><input type="checkbox" id="waive_ad_document_chk{{REQUIRED_AGREEMENT_ID}}" name="waive_ad_document_chk{{COUNT}}" value="{{REQUIRED_AGREEMENT_ID}}" disabled></td>
											<?php endif; ?>		
											
											<?php endif; ?>
											
											<td><button type="button" id="btn_ra_preview{{REQUIRED_AGREEMENT_ID}}" name="btn_ra_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td><input type="text" class="form-control input-sm" id="ra_date_upload{{REQUIRED_AGREEMENT_ID}}" name="ra_date_upload{{COUNT}}" value="" readonly></td>
											<td><input type="text" class="form-control input-sm" id="ra_orig_name{{REQUIRED_AGREEMENT_ID}}" name="ra_orig_name{{COUNT}}" value="" readonly></td>
											<td><input type="checkbox" id="ra_document_reviewed_chk{{REQUIRED_AGREEMENT_ID}}" name="ra_document_reviewed_chk{{COUNT}}" value="{{REVIEWED}}" disabled></td>
											<td><input type="text" class="form-control input-sm" id="ra_date_review{{REQUIRED_AGREEMENT_ID}}" name="ra_date_review{{COUNT}}" value="" readonly></td>
											<td><input type="checkbox" id="ra_document_verified_chk{{REQUIRED_AGREEMENT_ID}}" name="ra_document_verified_chk{{COUNT}}" value="{{SUBMITTED}}" disabled></td>
											<td><input type="text" class="form-control input-sm" id="ra_date_verified{{REQUIRED_AGREEMENT_ID}}" name="ra_date_verified{{COUNT}}" value="" readonly></td>
										</tr>
									{{/ra_table_template}}
								</script>
								
							</tbody>
							
						</table>
						<?php if(( ! empty($ad_remarks)) && ($has_full_waive_permission || $has_edit_waive_remark_permission)): ?>
							<strong id="ad_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control" name="ad_waive_remarks" id="ad_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;" readonly><?php echo $ad_remarks; ?></textarea>		
						<?php elseif($has_full_waive_permission):?>
							<strong id="ad_waive_lbl">Waive Remarks:</strong>
							<textarea  placeholder="Enter text here..."  class="form-control" name="ad_waive_remarks" id="ad_waive_remarks"style="min-height: 150px; height:150px; 
									max-height:250px; width:100%; min-width:100%;max-width:100%;"><?php echo $ad_remarks; ?></textarea>		
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
									<th>Verified</th>
									<th>Date Verified</th>
								</tr>
							</thead>
							<tbody id="ccn_body"><!-- id has value of docment id from db for assigning value while name has a loop value (count) for post -->
								<!-- id has value of docment id from db while name has a loop value (count)  -->
								<script id="tbl_ccn_template" type="text/template"> 
									{{#ccn_table_template}}
										<tr>
											<!-- Added & Modified MSF 20200924 -->
											<td align="center"><input type="checkbox" id="ccn_document_chk{{REQUIRED_CCN_ID}}" name="ccn_document_chk{{COUNT}}" value="{{REQUIRED_CCN_ID}}" disabled></td>
											<td>{{REQUIRED_CCN_NAME}}</td>
											<td align="center"><button type="button" id="btn_ccn_preview{{REQUIRED_CCN_ID}}" name="btn_ccn_preview{{COUNT}}" class="btn btn-default btn-xs preview" disabled><span class="glyphicon glyphicon-sunglasses"></span></button></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_upload{{REQUIRED_CCN_ID}}" name="ccn_date_upload{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="text" class="form-control input-sm" id="ccn_orig_name{{REQUIRED_CCN_ID}}" name="ccn_orig_name{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="checkbox" class="reviewed" id="ccn_document_reviewed_chk{{REQUIRED_CCN_ID}}" name="ccn_document_reviewed_chk{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_review{{REQUIRED_CCN_ID}}" name="ccn_date_review{{COUNT}}" value="" readonly></td>
											<td align="center"><input type="checkbox" class="validated" id="ccn_document_verified_chk{{REQUIRED_CCN_ID}}" name="ccn_document_verified_chk{{COUNT}}" disabled></td>
											<td align="center"><input type="text" class="form-control input-sm resizeInput" id="ccn_date_verified{{REQUIRED_CCN_ID}}" name="ccn_date_verified{{COUNT}}" value="" readonly></td>
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
					<div class="col-sm-12">
						<!-- <button class="btn btn-default " type="button"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span>&nbspCapture Signature</button>
						<br>
						<br> -->
					</div>
					<div class="col-xs-6 col-md-3" style="display: none;">
					    <div class="thumbnail">
					      	<img src="<?=base_url();?>assets/img/devices.png" alt="..." style="height: 100px">
					    </div>
				  	</div>
				  	<div class="col-xs-6 col-md-3" style="display: none;">
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
						<label for="cbo_tp" class="col-sm-2 control-label">Terms of Payment</label>
						<div class="col-sm-3">
						<!-- <select name="cbo_tp" id="cbo_tp" class="col-sm-3 form-control">
							<option value="">None</option>
							<option value="1">10 days</option>
							<option value="2">15 days</option>
							<option value="3">20 days</option>
							<option value="4">30 days</option>
						</select> -->

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
							if(($registration_type == 4) && ($current_status == 19)){
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
						</div>
					</div>
				</div>
			</div>
			
			<!-- Added MSF 20191125 (NA) -->
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
						<br>
						<label for="txt_approve_items" class="col-sm-2 control-label"><span class="pull-right">Approved Items / Project</span></label>
						
						
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
						if(($registration_type == 4) && ($current_status == 19)){
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

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
							<label for="cbo_tp" class="col-sm-2 control-label"><?=$label?></label><br><br>
						<div class="col-md-10">
							<?=$note?>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
							<label for="cbo_tp" class="col-sm-2 control-label"><?=$vrdlabel?></label><br><br>
						<div class="col-md-10">
							<?=$vrdnote?>
						</div>
					</div>
				</div>
			</div>

	</div>
</div>

<script type="text/javascript">

	$.ajax({
		url: "<?php echo base_url().'assets/js/vendor.js?' . filemtime('assets/js/vendor.js');?>",
		dataType: 'script',
		async: false
	});


	var rsd_tbl_doc_template = $('#tbl_rsd_template').html();
	var rsd_cbo_template 	 = $('#cbo_rsd_template').html();
	var ra_tbl_agree_template = $('#tbl_ra_template').html();
	var ra_cbo_template 	 = $('#cbo_ra_template').html();
	var ccn_tbl_agree_template = $('#tbl_ccn_template').html();
	var ccn_cbo_template	 = $('#cbo_ccn_template').html();

	function get_list_docs(ownership = '', trade_vendor_type = '',category_id = '', vendor_type, registration_type = '')
	{
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/get_list_docs";
        var post_params = "ownership="+ownership+"&trade_vendor_type="+trade_vendor_type+"&cat_id="+category_id+"&invite_id="+$("#invite_id").val()+"&vendor_type="+vendor_type+"&registration_type="+registration_type;

        var success_function = function(responseText)
        {

        	//console.log(responseText);
        /*    var tbl_data = $.parseJSON(responseText);
            var counter = 1;
            var counter2 = 1;
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
            }

            $('#rsd_body').html(Mustache.render(rsd_tbl_doc_template, DATA));
            $('#ra_body').html(Mustache.render(ra_tbl_agree_template, DATA));
            

           // $('#tbl_pag').html(responseText);
           // load if has existing data
           // load_vendor_data();*/


        	//console.log(responseText)
            var tbl_data = $.parseJSON(responseText);



            let l = '';
            l = tbl_data.ra;



            var counter = 1;
            var counter2 = 1;
            var counter3 = 1;

               if(l != "-1"){

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
            }

            $('#rsd_body').html(Mustache.render(rsd_tbl_doc_template, DATA));
            $('#ra_body').html(Mustache.render(ra_tbl_agree_template, DATA));
            $('#ccn_body').html(Mustache.render(ccn_tbl_agree_template, DATA));
            
		   loadingScreen('off');
        };

        return ajax_request(ajax_type, url, post_params, success_function);
	}


	function load_vendor_data()
	{
		loadingScreen('on');
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registrationapproval/get_data";
        var post_params;

        post_params = 'vendor_id='+document.getElementById('vendor_id').value;

        var success_function = function(responseText)
        {
            // console.log(responseText);
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
			$('#cat_sup tbody').html(''); // reset
			// Added MSF - 20191118 (IJR-10618)
			$('#avc_cat_sup tbody').html(''); // reset

            if (responseText != 0)
            {
            	var data = $.parseJSON(responseText);

            	for(i=0; i < data.count_cat; i++){
            		if(data.rs_cat.includes(259)){
            			if($('#vendor_invite_type2').val() != 'Update Vendor Information'){
            				console.log('hrhr');
            				$("#is_watsons").val('1');
	            			$("#vi_vendor_id_pass_vendor_buh").hide();
	            			$("#watsons_vendor_id_pass").show();
            			}else{
            				$("#vi_vendor_id_pass_vendor_buh").show();
            				$("#watsons_vendor_id_pass").hide();
            			}
            		}else{
            			$("#vi_vendor_id_pass_vendor_buh").show();
            			$("#watsons_vendor_id_pass").hide();
            		}
            	}

            	for(i=0; i < data.count_cat_avc; i++){
            		if(data.rs_cat_avc.includes(259)){
            			if($('#vendor_invite_type2').val() != 'Update Vendor Information'){
            				$("#is_watsons").val('1');
	            			$("#vi_vendor_id_pass_vendor_buh").hide();
	            			$("#watsons_vendor_id_pass").show();
            			}else{
            				$("#vi_vendor_id_pass_vendor_buh").show();
            				$("#watsons_vendor_id_pass").hide();
            			}
            		}else{
            			$("#vi_vendor_id_pass_vendor_buh").show();
            			$("#watsons_vendor_id_pass").hide();
            		}
            	}
            	
				if (data.count_vreg > 0)
            	{
	            	// load vendor data
	            	$('#cbo_yr_business').val(data.rs_vreg[0].YEAR_IN_BUSINESS);
					$('input:radio[name=ownership][value=' + data.rs_vreg[0].OWNERSHIP_TYPE + ']').prop('checked', true);
					
					var vt = data.rs_vreg[0].VENDOR_TYPE;
					if(vt == 4){
						document.getElementById('vendor_type_name').innerHTML = 'NON TRADE SERVICE';
					}else{
						document.getElementById('vendor_type_name').innerHTML = ''+data.rs_vreg[0].VENDOR_TYPE_NAME+'';
					}
					if(vt == 4){
						vt = 3;
					}
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
					
					$('#tax_idno').val(data.rs_vreg[0].TAX_ID_NO);
					$('input:radio[name=tax_class][value=' + data.rs_vreg[0].TAX_CLASSIFICATION + ']').prop('checked', true);
					
					//if (data.rs_vreg[0].VENDOR_TYPE == 1) // always disable nalang sincer approval to
						$('input:radio[name=trade_vendor_type]').prop('disabled', true);
					//Added & Modified MSF 20200924
					var no_of_employee = data.rs_vreg[0].EMPLOYEE;
					switch (no_of_employee){
						case '0':
							document.getElementById('no_of_employee').innerHTML = 'MICRO (1 - 9)';
							break;
						case '1':
							document.getElementById('no_of_employee').innerHTML = 'SMALL (10 - 99)';
							break;
						case '2':
							document.getElementById('no_of_employee').innerHTML = 'MEDIUM (100 - 199)';
							break;
						case '3':
							document.getElementById('no_of_employee').innerHTML = 'LARGE (200 and above)';
							break;
					}
						
					var business_asset = data.rs_vreg[0].BUSINESS_ASSET;
					switch (business_asset){
						case '0':
							document.getElementById('business_asset').innerHTML = 'MICRO (Up to P3,000,000)';
							break;
						case '1':
							document.getElementById('business_asset').innerHTML = 'SMALL (P3,000,001 - P15,000,000)';
							break;
						case '2':
							document.getElementById('business_asset').innerHTML = 'MEDIUM (P15,000,001 - P100,000,000)';
							break;
						case '3':
							document.getElementById('business_asset').innerHTML = 'LARGE (P100,000,001 and above)';
							break;
					}
					
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

					document.getElementById('ownershipname').innerHTML = ''+data.rs_vreg[0].OWNERSHIP_NAME+'';
					
				}
				
				//if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 5){
				//	var anchor = document.getElementById('vi_previous_company_name');
				//	anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].VENDOR_CODE;  
				//	anchor.setAttribute("data-action-path",'vendor/registrationapproval/display_approval/'+data.rs_vendor_invite_dtl[0].VENDOR_ID);
				//}else{
				//	//vi_new_company_name
				//	if(data.rs_vendor_invite_dtl[0].F_VENDOR_ID !== null){
				//		var anchor = document.getElementById('vi_new_company_name');
				//		anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].F_VENDOR_ID;  
				//		anchor.setAttribute("data-action-path",'vendor/registrationapproval/display_approval/'+data.rs_vendor_invite_dtl[0].F_VENDOR_ID);
				//	}
				//}
				
				if(data.rs_vendor_invite_dtl[0].REGISTRATION_TYPE == 5){
					var anchor = document.getElementById('vi_previous_company_name');
					anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].VENDOR_CODE;  
					anchor.setAttribute("data-action-path",'vendor/registration/display_vendor_details/'+data.rs_vendor_invite_dtl[0].VENDOR_ID);
					
					//document.getElementById('test').style.display = "inherit";
					document.getElementById('vendor_invite_type').innerHTML = "Change in Company Name";
					//document.getElementById('old_vendor_code').innerHTML = data.xx_vendor_code;
					//document.getElementById('old_vendor_name').innerHTML = data.xx_vendor_name;
					
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
						anchor.innerText += "VOR-"+data.rs_vendor_invite_dtl[0].F_VENDOR_ID;  
						anchor.setAttribute("data-action-path",'vendor/registration/display_vendor_details/'+data.rs_vendor_invite_dtl[0].F_VENDOR_ID);
					}
				}
				
				// load brands
				if(data.count_vbrand > 0)
				{
					$('#brand_count').val(data.count_vbrand);

					var table_brands = '';
					table_brands = '<table class="table table-bordered" style="width: 400px;">';
					table_brands += '<thead>';
					table_brands += '<th class="info">Brand Name</th>';
					table_brands += '</thead>';
					table_brands += '<tbody>';
					for (var i = 0, j = 1; i < data.count_vbrand; i++, j++) // i is for object, j is for element
					{
						table_brands += '<tr>';
						table_brands += '<td>';
						table_brands += '<input type="hidden" name="brand_id'+j+'" id="brand_id'+j+'" value="'+data.rs_vbrand[i].BRAND_ID+'">';
						table_brands += ''+data.rs_vbrand[i].BRAND_NAME+'';
						table_brands += '</td>';

						table_brands += '</tr>';
						/*if ($('#div_brandid'+j).length) // if exists write value
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
						}*/
					}
					table_brands += '</tbody>';
					table_brands += '</table>';

					document.getElementById('div_brandid1').innerHTML = table_brands;
					// end load brand
				}
				// load address
				//console.log(data);
				if(data.count_vaddr_office > 0)
				{
					var table_address = '';
					table_address = '<table class="table table-bordered" style="width: 100%;">';
					table_address += '<thead>';
					table_address += '<th class="info">Address</th>';
					table_address += '<th class="info">Brgy/Municipality</th>';
					table_address += '<th class="info">State/Province</th>';
					table_address += '<th class="info">ZipCode</th>';
					//table_address += '<th class="info">Region</th>';
					table_address += '<th class="info">Country</th>';
					table_address += '<th class="info">Primary</th>';
					table_address += '</thead>';
					table_address += '<tbody>';
					$('#office_addr_count').val(data.count_vaddr_office);
					for (var i = 0, j = 1; i < data.count_vaddr_office; i++, j++) // i is for object, j is for element
					{
						table_address += '<tr>';
						table_address += '<td>';
						
						if(data.rs_vaddr_office[i].ADDRESS_LINE == null)
							table_address += '&nbsp;';
						else
							table_address += ''+data.rs_vaddr_office[i].ADDRESS_LINE+'';
						
						table_address += '</td>';
						table_address += '<td>';
						
						if(data.rs_vaddr_office[i].CITY_NAME == null)
							table_address += '&nbsp;';
						else
							table_address += ''+data.rs_vaddr_office[i].CITY_NAME+'';
						
						table_address += '</td>';
						table_address += '<td>';
						
						if(data.rs_vaddr_office[i].STATE_PROV_NAME == null)
							table_address += '&nbsp;';
						else
							table_address += ''+data.rs_vaddr_office[i].STATE_PROV_NAME+'';
						
						table_address += '</td>';
						table_address += '<td>';
						
						if(data.rs_vaddr_office[i].ZIP_CODE == null)
							table_address += '&nbsp;';
						else
							table_address += ''+data.rs_vaddr_office[i].ZIP_CODE+'';
						
						table_address += '</td>';
						table_address += '<td>';
						
						// if(data.rs_vaddr_office[i].REGION_DESC_TWO == null)
						// 	table_address += '&nbsp;';
						// else
						// 	table_address += ''+data.rs_vaddr_office[i].REGION_DESC_TWO+'';
						
						// table_address += '</td>';
						// table_address += '<td>';
						
						if(data.rs_vaddr_office[i].COUNTRY_NAME == null)
							table_address += '&nbsp;';
						else
							table_address += ''+data.rs_vaddr_office[i].COUNTRY_NAME.toUpperCase()+'';
						table_address += '</td>';
						table_address += '<td>';
	
						if (data.rs_vaddr_office[i].PRIMARY == 1)
							table_address += 'YES';
						else if(data.rs_vaddr_office[i].PRIMARY == 0)
							table_address += 'NO';
						else
							table_address += '&nbsp;';
	
						table_address += '</td>';
	
						table_address += '</tr>';
						/*if ($('#div_office_addr'+j).length) // if exists write value
						{
							$('#office_add'+j).val(data.rs_vaddr_office[i].ADDRESS_LINE);
							$('#office_brgy_cm'+j).val(data.rs_vaddr_office[i].BRGY_MUNICIPALITY_ID);
							$('#office_state_prov'+j).val(data.rs_vaddr_office[i].STATE_PROVINCE_ID);
							$('#office_zip_code'+j).val(data.rs_vaddr_office[i].ZIP_CODE);
							$('#office_country'+j).val(data.rs_vaddr_office[i].COUNTRY_ID);
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
							$('#office_brgy_cm'+j).val(data.rs_vaddr_office[i].BRGY_MUNICIPALITY_ID);
							$('#office_state_prov'+j).val(data.rs_vaddr_office[i].STATE_PROVINCE_ID);
							$('#office_zip_code'+j).val(data.rs_vaddr_office[i].ZIP_CODE);
							$('#office_country'+j).val(data.rs_vaddr_office[i].COUNTRY_ID);
						}
						// set primary 
						if (data.rs_vaddr_office[i].PRIMARY == 1)
							$('input:radio[name=office_primary][value=' + j + ']').prop('checked', true);*/
					}
					table_address += '</tbody>';
					table_address += '</table>';
	
					document.getElementById('address_table').innerHTML = table_address;
				}

				if(data.count_vaddr_factory > 0)
				{
					var table_factory_address = '';
					table_factory_address = '<table class="table table-bordered" style="width: 100%;">';
					table_factory_address += '<thead>';
					table_factory_address += '<th class="info">Address</th>';
					table_factory_address += '<th class="info">Brgy/Municipality</th>';
					table_factory_address += '<th class="info">State/Province</th>';
					table_factory_address += '<th class="info">ZipCode</th>';
					table_factory_address += '<th class="info">Region</th>';
					table_factory_address += '<th class="info">Country</th>';
					table_factory_address += '<th class="info">Primary</th>';
					table_factory_address += '</thead>';
					table_factory_address += '<tbody>';
					$('#factory_addr_count').val(data.count_vaddr_factory);
					for (var i = 0, j = 1; i < data.count_vaddr_factory; i++, j++) // i is for object, j is for element
					{
						table_factory_address += '<tr>';
						table_factory_address += '<td>';
						
						if(data.rs_vaddr_factory[i].ADDRESS_LINE == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].ADDRESS_LINE+'';
						
						table_factory_address += '</td>';
						table_factory_address += '<td>';

						if(data.rs_vaddr_factory[i].CITY_NAME == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].CITY_NAME+'';

						table_factory_address += '</td>';
						table_factory_address += '<td>';

						if(data.rs_vaddr_factory[i].STATE_PROV_NAME == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].STATE_PROV_NAME+'';

						table_factory_address += '</td>';
						table_factory_address += '<td>';

						if(data.rs_vaddr_factory[i].ZIP_CODE == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].ZIP_CODE+'';
						
						table_factory_address += '</td>';
						table_factory_address += '<td>';

						if(data.rs_vaddr_factory[i].REGION_DESC_TWO == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].REGION_DESC_TWO+'';
						
						table_factory_address += '</td>';
						table_factory_address += '<td>';
						
						if(data.rs_vaddr_factory[i].COUNTRY_NAME == null)
							table_factory_address += '&nbsp;';
						else
							table_factory_address += ''+data.rs_vaddr_factory[i].COUNTRY_NAME.toUpperCase()+'';
						
						table_factory_address += '</td>';
						table_factory_address += '<td>';
	
						if (data.rs_vaddr_factory[i].PRIMARY == 1)
							table_factory_address += 'YES';
						else if(data.rs_vaddr_factory[i].PRIMARY == 0)
							table_factory_address += 'NO';
						else
							table_factory_address += '&nbsp;';
	
						table_factory_address += '</td>';
	
						table_factory_address += '</tr>';
						/*if ($('#div_factory_addr'+j).length) // if exists write value
						{
							$('#factory_addr'+j).val(data.rs_vaddr_factory[i].ADDRESS_LINE);
							$('#factory_brgy_cm'+j).val(data.rs_vaddr_factory[i].CITY_NAME);
							$('#factory_state_prov'+j).val(data.rs_vaddr_factory[i].STATE_PROV_NAME);
							$('#factory_zip_code'+j).val(data.rs_vaddr_factory[i].ZIP_CODE);
							$('#factory_country'+j).val(data.rs_vaddr_factory[i].COUNTRY_ID);
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
							$('#factory_country'+j).val(data.rs_vaddr_factory[i].COUNTRY_ID);
						}
						// set primary 
						if (data.rs_vaddr_factory[i].PRIMARY == 1)
							$('input:radio[name=factory_primary][value=' + j + ']').prop('checked', true);*/
					}
					table_factory_address += '</tbody>';
					table_factory_address += '</table>';
	
					document.getElementById('factory_address').innerHTML = table_factory_address;
				}

				if(data.count_vaddr_warehouse > 0)
				{
					var table_warehouse_address = '';
					table_warehouse_address = '<table class="table table-bordered" style="width: 100%;">';
					table_warehouse_address += '<thead>';
					table_warehouse_address += '<th class="info">Address</th>';
					table_warehouse_address += '<th class="info">Brgy/Municipality</th>';
					table_warehouse_address += '<th class="info">State/Province</th>';
					table_warehouse_address += '<th class="info">ZipCode</th>';
					table_warehouse_address += '<th class="info">Region</th>';
					table_warehouse_address += '<th class="info">Country</th>';
					table_warehouse_address += '<th class="info">Primary</th>';
					table_warehouse_address += '</thead>';
					table_warehouse_address += '<tbody>';
	
					$('#wh_addr_count').val(data.count_vaddr_warehouse);
					for (var i = 0, j = 1; i < data.count_vaddr_warehouse; i++, j++) // i is for object, j is for element
					{
						table_warehouse_address += '<tr>';
						table_warehouse_address += '<td>';

						if(data.rs_vaddr_warehouse[i].ADDRESS_LINE == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].ADDRESS_LINE +'';

						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';

						if(data.rs_vaddr_warehouse[i].CITY_NAME == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].CITY_NAME+'';
						
						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';

						if(data.rs_vaddr_warehouse[i].STATE_PROV_NAME == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].STATE_PROV_NAME+'';
						
						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';

						if(data.rs_vaddr_warehouse[i].ZIP_CODE == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].ZIP_CODE+'';
						
						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';

						if(data.rs_vaddr_warehouse[i].REGION_DESC_TWO == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].REGION_DESC_TWO+'';
						
						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';


						if(data.rs_vaddr_warehouse[i].COUNTRY_NAME == null)
							table_warehouse_address += '&nbsp;';
						else
							table_warehouse_address += ''+data.rs_vaddr_warehouse[i].COUNTRY_NAME.toUpperCase()+'';
						
						table_warehouse_address += '</td>';
						table_warehouse_address += '<td>';
	
						if (data.rs_vaddr_warehouse[i].PRIMARY == 1)
							table_warehouse_address += 'YES';
						else if(data.rs_vaddr_warehouse[i].PRIMARY == 0)
							table_warehouse_address += 'NO';
						else
							table_warehouse_address += '&nbsp;';
	
						table_warehouse_address += '</td>';
	
						table_warehouse_address += '</tr>';
						/*if ($('#div_factory_addr'+j).length) // if exists write value
						{
							$('#ware_addr'+j).val(data.rs_vaddr_warehouse[i].ADDRESS_LINE);
							$('#ware_brgy_cm'+j).val(data.rs_vaddr_warehouse[i].CITY_NAME);
							$('#ware_state_prov'+j).val(data.rs_vaddr_warehouse[i].STATE_PROV_NAME);
							$('#ware_zip_code'+j).val(data.rs_vaddr_warehouse[i].ZIP_CODE);
							$('#ware_country'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_ID);
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
							$('#ware_country'+j).val(data.rs_vaddr_warehouse[i].COUNTRY_ID);
						}
						// set primary 
						if (data.rs_vaddr_warehouse[i].PRIMARY == 1)
							$('input:radio[name=ware_primary][value=' + j + ']').prop('checked', true);*/
					}
					table_warehouse_address += '</tbody>';
					table_warehouse_address += '</table>';
	
					document.getElementById('warehouse_address').innerHTML = table_warehouse_address;
				}
				// end load address

				// load contacts 
				//tel no

				var table_contact_details = '';
				table_contact_details = '<table class="table table-bordered" style="width: 100%;">';
				table_contact_details += '<tbody>';


				var telno_details = '';
				telno_details += '<b>Telephone No.</b> : <table class="table table-bordered" style="width: 100%;">';
				telno_details += '<theadh>';
				telno_details += '<th class="info">Country Code</th>';
				telno_details += '<th class="info">Area Code</th>';
				telno_details += '<th class="info">Contact Detail</th>';
				telno_details += '<th class="info">Extension/Local Number</th>';
				telno_details += '</thead>';
				telno_details += '<tbody>';
				
				$('#telno_count').val(data.count_vc_telno);
				for (var i = 0, j = 1; i < data.count_vc_telno; i++, j++) // i is for object, j is for element
				{
					telno_details += '<tr>';
					telno_details += '<td>';
					
					if(data.rs_vc_telno[i].COUNTRY_CODE == null)
						telno_details += '&nbsp;';
					else
						telno_details += ''+data.rs_vc_telno[i].COUNTRY_CODE+'';

					telno_details += '</td><td>';
					
					if(data.rs_vc_telno[i].AREA_CODE == null)
						telno_details += '&nbsp;';
					else
						telno_details += ''+data.rs_vc_telno[i].AREA_CODE+'';
					
					telno_details += '</td><td>';

					if(data.rs_vc_telno[i].CONTACT_DETAIL == null)
						telno_details += '&nbsp;';
					else
						telno_details += ''+data.rs_vc_telno[i].CONTACT_DETAIL+'';

					telno_details += '</td><td>';

					if(data.rs_vc_telno[i].EXTENSION_LOCAL_NUMBER == null)
						telno_details += '&nbsp;';
					else
						telno_details += ''+data.rs_vc_telno[i].EXTENSION_LOCAL_NUMBER+'';

					telno_details += '</td>';
					telno_details += '</tr>';
					/*
					if ($('#tel_no'+j).length) // if exists write value
					{
						$('#tel_no'+j).val(data.rs_vc_telno[i].CONTACT_DETAIL);
					}
					else // element not exists create element and write value
					{
						$('#div_telno').append(telno_template.clone()).find('input:last').attr({'id':'tel_no'+j, 'name':'tel_no'+j, 'value': ''}).val(''); // append and reset value
						// assign value to new rows
						$('#tel_no'+j).val(data.rs_vc_telno[i].CONTACT_DETAIL);
					}*/
					
				}

				telno_details += '</tbody>';
				telno_details += '</table>';
				// end tel no
				//email

				var email_details = '';
				email_details += '<b>&nbsp;</b><table class="table table-bordered" style="width: 100%;">';
				email_details += '<theadh>';
				email_details += '<th class="info">Email Address</th>';
				email_details += '</thead>';
				email_details += '<tbody>';
				$('#email_count').val(data.count_vc_email);
				for (var i = 0, j = 1; i < data.count_vc_email; i++, j++) // i is for object, j is for element
				{
					email_details += '<tr>';
					email_details += '<td>';
					email_details += ''+data.rs_vc_email[i].CONTACT_DETAIL+'';
					email_details += '</td>';
					email_details += '</tr>';
					/*if ($('#email'+j).length) // if exists write value
					{
						$('#email'+j).val(data.rs_vc_email[i].CONTACT_DETAIL);
					}
					else // element not exists create element and write value
					{
						$('#div_email').append(email_template.clone()).find('input:last').attr({'id':'email'+j, 'name':'email'+j, 'value': ''}).val(''); // append and reset value
						// assign value to new rows
						$('#email'+j).val(data.rs_vc_email[i].CONTACT_DETAIL);
					}*/
					
				}

				email_details += '</tbody>';
				email_details += '</table>';
				// end email
				//faxno

				var fax_details = '';
				fax_details += '<b>Fax No. :</b><table class="table table-bordered" style="width: 100%;">';
				fax_details += '<theadh>';
				fax_details += '<th class="info">Country Code</th>';
				fax_details += '<th class="info">Area Code</th>';
				fax_details += '<th class="info">Contact Detail</th>';
				fax_details += '<th class="info">Extension/Local Number</th>';
				fax_details += '</thead>';
				fax_details += '<tbody>';
				$('#faxno_count').val(data.count_vc_faxno);
				for (var i = 0, j = 1; i < data.count_vc_faxno; i++, j++) // i is for object, j is for element
				{
					fax_details += '<tr>';
					fax_details += '<td>';
					if(data.rs_vc_faxno[i].COUNTRY_CODE == null)
						fax_details += '&nbsp;';
					else
						fax_details += ''+data.rs_vc_faxno[i].COUNTRY_CODE+'';
					fax_details += '</td>';
					fax_details += '<td>';
					if(data.rs_vc_faxno[i].AREA_CODE == null)
						fax_details += '&nbsp;';
					else
						fax_details += ''+data.rs_vc_faxno[i].AREA_CODE+'';
					fax_details += '</td>';
					fax_details += '<td>';
					if(data.rs_vc_faxno[i].CONTACT_DETAIL == null)
						fax_details += '&nbsp;';
					else
						fax_details += ''+data.rs_vc_faxno[i].CONTACT_DETAIL+'';
					fax_details += '</td>';
					fax_details += '<td>';
					if(data.rs_vc_faxno[i].EXTENSION_LOCAL_NUMBER == null)
						fax_details += '&nbsp;';
					else
						fax_details += ''+data.rs_vc_faxno[i].EXTENSION_LOCAL_NUMBER+'';
					fax_details += '</td>';
					fax_details += '</tr>';
					
				}

				fax_details += '</tbody>';
				fax_details += '</table>';
				// end faxno
				//mobno

				var mobno_details = '';
				mobno_details += '<b>Mobile No. : </b><table class="table table-bordered" style="width: 100%;">';
				mobno_details += '<theadh>';
				mobno_details += '<th class="info">Country Code</th>';
				//mobno_details += '<th class="info">Area Code</th>';
				mobno_details += '<th class="info">Contact Detail</th>';
				mobno_details += '<th class="info">Extension/Local Number</th>';
				mobno_details += '</thead>';
				mobno_details += '<tbody>';
				$('#mobno_count').val(data.count_vc_mobno);
				for (var i = 0, j = 1; i < data.count_vc_mobno; i++, j++) // i is for object, j is for element
				{
					mobno_details += '<tr>';
					mobno_details += '<td>';

					if(data.rs_vc_mobno[i].COUNTRY_CODE == null)
						mobno_details += '&nbsp;';
					else
						mobno_details += data.rs_vc_mobno[i].COUNTRY_CODE;

					mobno_details += '</td>';
					/*mobno_details += '<td>';

					if(data.rs_vc_mobno[i].AREA_CODE == null)
						mobno_details += '&nbsp;';
					else
						mobno_details += data.rs_vc_mobno[i].AREA_CODE;

					mobno_details += '</td>';*/
					mobno_details += '<td>';

					if(data.rs_vc_mobno[i].CONTACT_DETAIL == null)
						mobno_details += '&nbsp;';
					else
						mobno_details += data.rs_vc_mobno[i].CONTACT_DETAIL;

					mobno_details += '</td>';
					mobno_details += '<td>';

				/*	if(data.rs_vc_mobno[i].EXTENSION_LOCAL_NUMBER == null)
						mobno_details += '&nbsp;';
					else
						mobno_details += data.rs_vc_mobno[i].EXTENSION_LOCAL_NUMBER;*/

					//mobile extension here. uncomment if else block if needed

					mobno_details += '</td>';
					mobno_details += '</tr>';

				}

				mobno_details += '</tbody>';
				mobno_details += '</table>';

				table_contact_details += '<tr>';
				table_contact_details += '<td>';
				table_contact_details += ''+telno_details+'';
				table_contact_details += '</td>';
				table_contact_details += '<td>';
				table_contact_details += ''+email_details+'';
				table_contact_details += '</td>';
				table_contact_details += '</tr>';

				table_contact_details += '<tr>';
				table_contact_details += '<td>';
				table_contact_details += ''+fax_details+'';
				table_contact_details += '</td>';
				table_contact_details += '<td>';
				table_contact_details += ''+mobno_details+'';
				table_contact_details += '</td>';
				table_contact_details += '</tr>';

				table_contact_details += '</tbody>';
				table_contact_details += '</table>';

				document.getElementById('contact_details').innerHTML = table_contact_details;

				// end mobno
				//Owners/Partners/Directors
				if(data.count_vowner > 0)
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
				if(data.count_vauthrep > 0)
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
				if(data.count_vbank > 0)
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
				if(data.count_vretcust > 0)
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
				if(data.count_vob > 0)
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
				if(data.count_vrel > 0)
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
	            var trade_vendor_type   = '0';
	            var vendor_type   = $("input[name='vendor_type']:checked").val();

	            if ($("input[name='ownership']:checked").length > 0)
	                ownership = $("input[name='ownership']:checked").val();

	            if (vendor_type == 1){
					trade_vendor_type = $("input[name='trade_vendor_type']:checked").val();
				}

	            // cetegories START
	            
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

								/*console.log('here');
								console.log($('#cat_sup tbody :input[value='+data.rs_cat[i].CATEGORY_ID+']').length);*/

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

				
				// sub-category end

			$(document).ready(function(){
	            let cat_sup = [];	
				$('#cat_sup input[type=hidden]').each(function(){
					
					if(!cat_sup.includes(this.value)){
							console.log(cat_sup);
							cat_sup.push(this.value);
						}
				});
				$("#cat_sup_count").val(cat_sup.length);
				$("#category_id").val(cat_sup);
				//console.log("TESTB = " + trade_vendor_type);
				//console.log($('#cat_sup input[type=hidden]').val());
	            get_list_docs(ownership, trade_vendor_type,cat_sup,vendor_type,data.rs_registration_type).done(function(){
					// Required Scanned Documents
					if(data.count_vreqdoc > 0)
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
							$('#rsd_document_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DOC_TYPE_ID);
							$('#rsd_date_upload'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_CREATED);
							$('#rsd_orig_name'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].ORIGINAL_FILENAME);
							$('#rsd_date_review'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_REVIEWED);
							$('#rsd_date_verified'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].DATE_VERIFIED);
							$('#btn_rsd_preview'+data.rs_vreqdoc[i].DOC_TYPE_ID).val(data.rs_vreqdoc[i].FILE_PATH).prop('disabled', false);

							if (data.rs_vreqdoc[i].DATE_REVIEWED != null)
								$('#rsd_document_reviewed_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);

							if (data.rs_vreqdoc[i].DATE_VERIFIED != null)
								$('#rsd_document_verified_chk'+data.rs_vreqdoc[i].DOC_TYPE_ID).prop('checked', true);
							
						}
					}
					// end Required Scanned Documents
					// Required Agreements
					if(data.count_vagree > 0)
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
							$('#ra_document_chk'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
							$('#ra_date_upload'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_CREATED);
							$('#ra_orig_name'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].ORIGINAL_FILENAME);
							$('#ra_date_review'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_REVIEWED);
							$('#ra_date_verified'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].DATE_SUBMITTED);
							$('#btn_ra_preview'+data.rs_vagree[i].DOC_TYPE_ID).val(data.rs_vagree[i].FILE_PATH).prop('disabled', false);

							if (data.rs_vagree[i].DATE_REVIEWED != null)
								$('#ra_document_reviewed_chk'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);

							if (data.rs_vagree[i].DATE_SUBMITTED != null)
								$('#ra_document_verified_chk'+data.rs_vagree[i].DOC_TYPE_ID).prop('checked', true);
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
							}
						});
					}
					// end Required Agreements
					
					
					// CCN Required
					if (data.count_ccn > 0)
					{
						for (var i = 0, j = 1; i < data.count_ccn; i++, j++)
						{
							$('#ccn_document_chk'+data.rs_ccn[i].DOC_TYPE_ID).prop('checked', true);
							$('#ccn_document_chk'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DOC_TYPE_ID);
							$('#ccn_date_upload'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_CREATED);
							$('#ccn_orig_name'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].ORIGINAL_FILENAME);
							$('#ccn_date_review'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_REVIEWED);
							$('#ccn_date_verified'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].DATE_VERIFIED);
							$('#btn_ccn_preview'+data.rs_ccn[i].DOC_TYPE_ID).val(data.rs_ccn[i].FILE_PATH).prop('disabled', false);

							if (data.rs_ccn[i].DATE_REVIEWED != null)
								$('#ccn_document_reviewed_chk'+data.rs_ccn[i].DOC_TYPE_ID).prop('checked', true);
						}
					}
					// end CCN Required
				});
				});




            }
			loadingScreen('off');
        };

        ajax_request(ajax_type, url, post_params, success_function);
		
		 
	}
	 
	load_vendor_data();
	//get_list_docs();
	
	function printJS()
	{
		var id = $('#vendor_id').val();
		window.open(BASE_URL+'vendor/registrationreview/print_template/'+id);
	}

	function printImg()
	{
		//update MSF - 20191105 (IJR-10612)
		//var url = $('#imagepreview').contents().find('img').attr('src');
		
		var url = $('#image_preview').attr('src');	

		var img_window = window.open(url,'Image');
		setTimeout(function(){
	        img_window.focus();
			img_window.print();
			img_window.close();
         } , 300);
		
	}

	// Added MSF - 20191105 (IJR-10612)
	function downloadImg(){
		var url = $('#image_preview').attr('src');	
		let n_url = url.split('/');
		let ncount = n_url.length;
		window.open(BASE_URL+'vendor/registration/download_img/'+n_url[ncount -1]);
	}
	
	//update MSF - 20191105 (IJR-10612)
	// setting height of iframe according to window size
	/*$(document).ready(function(){
		// if ($('#view_only').val() == 1)
		$(':input').removeAttr('placeholder'); // remove placeholders 
		
        $('iframe').height( $(window).height() );
        $(window).resize(function(){
            $('iframe').height( $(this).height() );
        });
    });*/
</script>