<style>
.main_label
{
	color: #43A5CF;
}

.cursor_pointer
{
	cursor: default;
}

.btn_min_width
{
	min-width: 100px;
}

.indent_left
{
	padding-left: 10px;
}

.indent_right
{
	padding-right: 30px;
}

.indent_sides
{
	padding: 0 10px 0 10px;
}

.no-indent_sides
{
	padding: 0 0 0 0;
}

thead 
{
	background-color: #d8d8d8;
	width: 100% ;
	padding: 0 30px 0 30px;
}

tbody .body_color
{
	background-color: #d8d8d8;
	width: 100% ;
	padding: 0 30px 0 30px;
}

.btn_disabled
{
	padding: 20px 20px 20px 20px;
    width: 75%;
}

.indent_top
{
	padding-top: 20px;
}

.semi_head
{
	background-color: #337ab7;
	color: #FFFFFF;
}

.image_min
{
	width: 100px;
	height: 100px;
}

textarea.form-control 
{
		resize: vertical;
		height: 34px;
}

.pic_attachment
{
	padding: 0 5px 0 5px;
}

.dv_attachment
{
	padding: 15px 20px 0 20px;
	border: 1px solid #ccc;
}
</style>

<div class="container mycontainer" style="display: inherit;">
<?=form_open_multipart('form1', array('name' => 'form1') );?>
<?=form_hidden('rfq_id', $rfq_id)?>
<?=form_hidden('draft_validation', $draft_validation)?>
<div id="result_div"><!-- table_seach_invite -->
</div>
	<div class="row">
		<div class="form-group">
			<div class="col-md-8">
				<h4><?=nbs(3)?><b>RFQ/RFB Creation</b></h4>
			</div>
			<div class="col-md-offset-9">
				<input type="button" class="btn btn-default btn-sm btn-primary" id="submitbtn" onclick="submit_rfq_creation(1)" value="Submit for Approval">
				<input type="button" class="btn btn-default btn-sm btn-primary" id="draftbtn" onclick="submit_rfq_creation(0)" value="Save as Draft">
				<input type="button" class="btn btn-primary btn-sm" value="Close" onclick="go_to_homepage()">
				<!-- <input type="button" class="btn btn-default btn-sm btn-primary" value="Exit"> -->
			</div>
		</div>
	</div>
	<div class="form_container">
	<div class="panel panel-default">
	<div class="panel-body">
	<?=br(2)?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-sm-2">
					<label>Title</label>
				</div>
				<div class="col-sm-4">
					<?=form_input('title_txt', '', ' id="title_txt" class="form-control field-required" maxlength="100" style="width:100%" ')?>
				</div>
				<div class="col-sm-2">
					<label>RFQ : </label>
					<?=$rfq_id_label?>
				</div>
				<div class="col-sm-2">
					<label>Created By</label>
				</div>
				<div class="col-sm-2">
					<?= $this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name')?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-sm-2">
					<label>Type</label>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						
						<div class="col-sm-10">
							<input type="hidden" value="0" name="type_radio" id="type_radio">
							<label class="radio-inline">
								<input type="radio" name="type" id="qualified" value="qualified" onchange="type_change(1)"> <label id="type_select1" style="font-weight: normal;">Qualified</label>
							</label>
							<label class="radio-inline">
								<input type="radio" name="type" id="competitive" value="competitive" onchange="type_change(2)"> <label id="type_select2" style="font-weight: normal;">Competitive</label>
							</label>
					</div>
				</div>
				</div>
				<div class="col-sm-2">
					<label>Status</label>
							Draft
				</div>
				<div class="col-sm-2">
					<label>Date Created</label>
				</div>
				<div class="col-sm-2">
					<?=date('m/d/Y', now())?>
				</div>
			</div>
		</div>
	</div>
		<?=br(2)?>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-sm-2">
					<label>Currency</label>
				</div>
				<div class="col-sm-2">
					<?=form_dropdown('currency', $currency_data, $dafault_currency, 'onchange="change_border(this.id);" id="currency" class="form-control btn btn-default dropdown-toggle field-required" style="width:150px"')?>
				</div>
				<div class="col-sm-2">
					<label>Submission Deadline</label>
				</div>
				<div class="col-sm-2">
					<input type="date" value="" id="sub_deadline_date" onchange="change_border(this.id);change_preferred_max(this.value)" name="sub_deadline_date" class="form-control" max="9999-12-31">
				</div>
				<div class="col-sm-2">
					<label>Preferred Delivery Date</label>
				</div>
				<div class="col-sm-2">
					<input type="date" value="" id="pref_delivery_date" onchange="change_border(this.id);" name="pref_delivery_date" class="form-control" max="9999-12-31">
				</div>
			</div>
		</div>
	</div>
		<?=br(2)?><!-- SM VIEW ONLY -->
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-12">
						<h5><label style="color: #0000FF">SM View Only</label></h5>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-4">
						<label>Requestor</label>
					</div>
					<div class="col-md-8">
						<?=form_dropdown('requestor', $requestor_data, '', 'onchange="change_border(this.id);" id="requestor" class="form-control btn btn-default dropdown-toggle field-required" style="width: 100%;"');?>
					</div>
				</div>
			</div>
				<?=br(1)?>
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-4">
						<label>Purpose of Request</label>
					</div>
					<div class="col-md-4">
						<?=form_dropdown('purpose', $purpose_data, '', 'id="purpose" class="form-control btn btn-default dropdown-toggle field-required" onchange="change_border(this.id);purpose_select(this.value)" style="width:100%"');?>
					</div>
					<div class="col-md-4">
						<?=form_input('purpose_txt', '', 'onchange="change_border(this.id);" id="purpose_txt" disabled class="form-control" style="width:100%"');?>
					</div>
				</div>
			</div>
				<?=br(1)?>
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-4">
						<label>Reason of Request</label>
					</div>
					<div class="col-md-4">
						<?=form_dropdown('reason', $reason_data, '', 'id="reason" class="form-control btn btn-default dropdown-toggle field-required" onchange="change_border(this.id);reason_select(this.value)" style="width:100%"');?>
					</div>
					<div class="col-md-4">
						<?=form_input('reason_txt', '', 'onchange="change_border(this.id);" id="reason_txt" disabled class="form-control" style="width:100%"');?>
					</div>
				</div>
			</div>
				<?=br(1)?>
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-4">
						<label>Internal Note</label>
					</div>
					<div class="col-md-8">
						<div id="charNum"></div>
						<textarea class="form-control field-required" maxlength="300" id="internal_note" oninput="change_border(this.id)" name="internal_note" maxlength="300"style="width: 100%; height: 100px;"></textarea>
					</div>
				</div>
			</div>
		</div>
	</div><!-- END SM VIEW ONLY -->
		<?=br(2)?>

	<!-- APPROVERS AND APPROVAL HIERARCHY -->
	<div class="row indent_sides">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading"><strong>Approvers and Approval Hierarchy</strong></div>
				<table class="table">
					<thead>
						<tr>
							<th>Member</th>
							<th>Position</th>
							<th>Approval Hierarchy</th>
						</tr>
					</thead>
					<tbody>
						<?php echo $approvers_content?> 
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- END APPROVERS AND APPROVAL HIERARCHY -->

	<!-- START OF LINES RFQ CATEGORY AND SPECS -->
	<hr>	
	<div class="row indent_sides">
		<div class="col-md-12">

			<div class="panel panel-primary">

				<div class="panel-heading">
					<!-- <input type="checkbox" id="select_all" name="select_all" onchange="select_all_lines()"> --> <strong>Lines</strong>
					<div class="pull-right">
						<input type="hidden" id="max_lines" name="max_lines" value="1">
						<input type="button" value="Add" class="btn btn-default btn-xs btn_min_width" onclick="add_delete_lines(1)" id="add_btn">
						<input type="button" value="Delete" class="btn btn-default btn-xs btn_min_width" onclick="delete_lines(0)" id="del_btn" disabled>
					</div>
				</div>

				<div class="panel-body" id="lines_data">

					<!-- LINES DATA -->
					<div id="lines_data_rfx">

						<!-- SAMPLE LINE DATA 1 -->
						<div class="row">
							<div class="col-md-1">
								<div class="form-group">
									<input type="checkbox" class="select_line" data-line-id="1" id="chkbx1"  onchange="invitecheck(this.checked, 1, 'lineischecked')">
									<?=form_hidden('lineischecked1', 0)?>
								</div>
							</div>
							<div class="col-md-11">
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											<label for="line_category1">Category</label>
											<?=form_dropdown('line_category1', $category_array, '', 'onchange="change_border(this.id);" id="line_category1" class="btn toggle-dropdown btn-default form-control field-required"')?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">											
											<label for="line_description1">Description <span id="line_description1_char_num"><span></label>
												<div class="input-group">
												<input type="text" maxlength="300" class="form-control field-required auto_suggest line_description_input" list-container="line_description_list1" oninput="change_border(this.id);" id="line_description1" name="line_description1" width="100%">
												<div class="input-group-btn">
													<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="line_description1" >
														<span class="caret"></span>
													</button>
												</div>
											</div>
											<?=form_dropdown('line_description_list1', $description_array, '', ' id="line_description_list1" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
											<!-- <input type="text" class="form-control input-sm field-required auto_suggest" list-uri="<?=base_url("index.php/rfqb/rfq_main/get_descriptions");?>" oninput="change_border(this.id);" id="line_description1" name="line_description1" width="100%">
											<div class="input-group">
												<input type="text" class="form-control input-sm field-required auto_suggest" data-toggle="dropdown" list-container="line_description_list1" oninput="change_border(this.id);" id="line_description1" name="line_description1" width="100%">
												<span class="input-group-addon" style="width:10%;"><span class="caret"></span></span>
												
											</div>
											<div class="form-group">
												<input class="form-control" type="text" data-toggle="dropdown" />
												<div class="input-group-btn">
													<button tabindex="-1" class="btn btn-default" type="button">Action</button>
													<button tabindex="-1" data-toggle="dropdown" class="btn btn-default dropdown-toggle" type="button">
														<span class="caret"></span>
														<span class="sr-only">Toggle Dropdown</span>
													</button>
												</div>
											
											</div>
										-->
										</div>
										
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_measuring_unit1">Unit of Measure</label>
											<?=form_dropdown('line_measuring_unit1', $unit_array, '', 'onchange="change_border(this.id);" id="line_measuring_unit1" class="btn btn-default toggle-dropdown form-control field-required"')?>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="line_quantity">Quantity</label>
											<input type="text" id="quantity1" name="quantity1" onchange="change_border(this.id);" class="form-control field-required numeric-decimal">
										</div>
									</div>
								</div>

								<div class="row">
									<a style="cursor:pointer;" onclick="specsview(1);">Specifications <span class="glyphicon glyphicon-modal-window"></span></a> <span id="specs1_text_char_num"></span>
								</div>
								<?=form_hidden('specs1', 0)?>

								<div id="specifications1" class="row" style="display: none;">
									<textarea class="form-control specs_txt_area" id="specs1_text" name="specs1_text" maxlength="3000" onchange="change_border(this.id);" style="width: 100%;height: 100px;"></textarea>
								</div>

								<div class="row">
									<div class="col-md-4">
										<a style="cursor:pointer;" onclick="attachmentview(1);">Attachments for Vendors Viewing <span class="badge" id="attachment_count1"><input type="hidden" name="att_cnt1" id="att_cnt1" value=0>0</span> <span class="glyphicon glyphicon-modal-window"></span></a>
									</div>

									<div class="col-md-offset-9">
										<input onclick="add_attachment(1)" type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Add" id="add_attachment1" name="add_attachment1"><?=nbs(3)?>
										<input onclick="delete_selected_attachment(1)" disabled type="button" style="display: none;" class="btn btn-primary btn-xs btn_min_width" value="Delete" id="delete_attachment1" name="delete_attachment1">
									</div>
								</div>
								<?=form_hidden('attach1', 0)?>
								<div id="attachment1" class="row form-inline" style="white-space: nowrap; display: none; overflow-x: scroll; height: 200px; width: 95%;">
								
									<input type="hidden" name="hidden_bom_attach1" id="hidden_bom_attach1" value="0">
									
										<div id="attachment1_1" style="display:none">
											<img class="img-responsive image_min" id="image1_1" src="#" value="">
											<center><div id="chkbx_line1_1"></div></center>
											<input type="hidden" name="line_attachment_id_1_1" id="line_attachment_id_1_1" value="0">
											<input type="hidden" name="hidden_path_1_1" id="hidden_path_1_1" value="0">
											<input type="hidden" name="attachment_desc_1_1" id="attachment_desc_1_1" value="0">
											<input type="hidden" name="attachment_type_1_1" id="attachment_type_1_1" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_1" name="checkbox_attachment_1_1">
										</div>	
										<div id="attachment1_2" style="display:none">
											<img class="img-responsive image_min" id="image1_2" src="#" value="">
											<center><div id="chkbx_line1_2"></div></center>
											<input type="hidden" name="line_attachment_id_1_2" id="line_attachment_id_1_2" value="0">
											<input type="hidden" name="hidden_path_1_2" id="hidden_path_1_2" value="0">
											<input type="hidden" name="attachment_desc_1_2" id="attachment_desc_1_2" value="0">
											<input type="hidden" name="attachment_type_1_2" id="attachment_type_1_2" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_2" name="checkbox_attachment_1_2">
										</div>	
										<div id="attachment1_3" style="display:none">
											<img class="img-responsive image_min" id="image1_3" src="#" value="">
											<center><div id="chkbx_line1_3"></div></center>
											<input type="hidden" name="line_attachment_id_1_3" id="line_attachment_id_1_3" value="0">
											<input type="hidden" name="hidden_path_1_3" id="hidden_path_1_3" value="0">
											<input type="hidden" name="attachment_desc_1_3" id="attachment_desc_1_3" value="0">
											<input type="hidden" name="attachment_type_1_3" id="attachment_type_1_3" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_3" name="checkbox_attachment_1_3">
										</div>	
										<div id="attachment1_4" style="display:none">
											<img class="img-responsive image_min" id="image1_4" src="#" value="">
											<center><div id="chkbx_line1_4"></div></center>
											<input type="hidden" name="line_attachment_id_1_4" id="line_attachment_id_1_4" value="0">
											<input type="hidden" name="hidden_path_1_4" id="hidden_path_1_4" value="0">
											<input type="hidden" name="attachment_desc_1_4" id="attachment_desc_1_4" value="0">
											<input type="hidden" name="attachment_type_1_4" id="attachment_type_1_4" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_4" name="checkbox_attachment_1_4">
										</div>	
										<div id="attachment1_5" style="display:none">
											<img class="img-responsive image_min" id="image1_5" src="#" value="">
											<center><div id="chkbx_line1_5"></div></center>
											<input type="hidden" name="line_attachment_id_1_5" id="line_attachment_id_1_5" value="0">
											<input type="hidden" name="hidden_path_1_5" id="hidden_path_1_5" value="0">
											<input type="hidden" name="attachment_desc_1_5" id="attachment_desc_1_5" value="0">
											<input type="hidden" name="attachment_type_1_5" id="attachment_type_1_5" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_5" name="checkbox_attachment_1_5">
										</div>	
										<div id="attachment1_6" style="display:none">
											<img class="img-responsive image_min" id="image1_6" src="#" value="">
											<center><div id="chkbx_line1_6"></div></center>
											<input type="hidden" name="line_attachment_id_1_6" id="line_attachment_id_1_6" value="0">
											<input type="hidden" name="hidden_path_1_6" id="hidden_path_1_6" value="0">
											<input type="hidden" name="attachment_desc_1_6" id="attachment_desc_1_6" value="0">
											<input type="hidden" name="attachment_type_1_6" id="attachment_type_1_6" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_6" name="checkbox_attachment_1_6">
										</div>	
										<div id="attachment1_7" style="display:none">
											<img class="img-responsive image_min" id="image1_7" src="#" value="">
											<center><div id="chkbx_line1_7"></div></center>
											<input type="hidden" name="line_attachment_id_1_7" id="line_attachment_id_1_7" value="0">
											<input type="hidden" name="hidden_path_1_7" id="hidden_path_1_7" value="0">
											<input type="hidden" name="attachment_desc_1_7" id="attachment_desc_1_7" value="0">
											<input type="hidden" name="attachment_type_1_7" id="attachment_type_1_7" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_7" name="checkbox_attachment_1_7">
										</div>	
										<div id="attachment1_8" style="display:none">
											<img class="img-responsive image_min" id="image1_8" src="#" value="">
											<center><div id="chkbx_line1_8"></div><center>
											<input type="hidden" name="line_attachment_id_1_8" id="line_attachment_id_1_8" value="0">
											<input type="hidden" name="hidden_path_1_8" id="hidden_path_1_8" value="0">
											<input type="hidden" name="attachment_desc_1_8" id="attachment_desc_1_8" value="0">
											<input type="hidden" name="attachment_type_1_8" id="attachment_type_1_8" value="0">
											<input type="hidden" value="0" id="checkbox_attachment_1_8" name="checkbox_attachment_1_8">
										</div>	

								</div>
							</div>
						</div> 
						<hr>

				</div> <!-- END OF LINES DATA -->
			</div><!-- END OF PANEL BODY-->

		</div> <!-- END OF PANEL -->
	</div>
	</div>

	<!-- END OF LINES RFQ CATEGORY AND SPECS -->

	<!-- START OF INVITED VENDORS -->

	<div class="row indent_sides">
		<div class="col-md-12">
			<div class="panel panel-primary" id="invited_vendors_panel">

				<div class="panel-heading">
					<div class="form-group">
						<div class="col-sm-2">
							<strong>Invited Vendors</strong>
						</div>
						<div class="col-sm-2">
							<?=form_button('search_vendor_btn', 'Search Vendor', 'onclick="search_vendor_click()" class="btn btn-default btn-xs btn-default pull-right btn1 btn_min_width"')?>
						</div>
						<div class="col-sm-2">
							<?=form_button('new_vendor', 'New Vendor', 'onclick="new_vendor_click()" class="btn btn-default btn-xs btn-default pull-left btn1 btn_min_width" ')?>
						</div>
						<div class="col-sm-2">
							<?=form_button('delete_vendor', 'Delete', 'class="btn btn-default btn-xs btn-default pull-right btn1 btn_min_width" onclick="delete_invited_vendor()"')?>
						</div>
						<div class="col-sm-2">
							<?=form_button('clear_vendor', 'Clear', 'class="btn btn-default btn-xs btn-default btn1 btn_min_width" onclick="clear_invited()"')?>
						</div>
						<div class="col-sm-offset-10">
							<?=form_button('create_new_vendor', 'Create New Vendor List', 'id="create_new_vendor" onclick="create_new_vendor_list()" disabled class="btn btn-default btn-xs btn-default pull-left btn1"')?>
						</div>
					</div>
				</div>
				<?=form_hidden('search_vendor_hidden', 0);?>
				<?=form_hidden('new_vendor_hidden', 0);?>
				<?=form_hidden('num_selected_vendor', 0);?>
				<input type="hidden" name="count_check" id="count_check" value="0">
				<div id="selected_invited_vendor">
				<input type="hidden" name="count_all_invited" id="count_all_invited" count="0">
					<table class="table">
						<thead>
							<div class="col-md-2">
								<th>
									<input type="checkbox" name="check_all_vendor" id="check_all_vendor" onchange="check_all_invited_vendor(this.checked)">
									Select
								</th>
							</div>
							<div class="col-md-10">
								<th>Vendor</th>
							</div>
						</thead>
						<tbody>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						</tbody>
					</table>
				</div>

				<?=br(1);?>
				<!-- START OF VENDOR SEARCHING -->
				<div class="panel panel-default" style="margin: auto;width: 95%; align: center; padding: 20px 20px 20px 20px; display: none;" id="search_vendor_div">
					<div class="row">
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-4">
									Vendor Name
								</div>
								<div>
									<div class="col-md-8">
										<div class="input-group">
											<input type="text" class="form-control input-sm cls_brand auto_suggest" list-container="vendorname_list" id="cbo_vendorname" name="cbo_vendorname" placeholder="Vendor Name">
											<div class="input-group-btn">
												<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorname" >
													<span class="caret"></span>
												</button>
											</div>
										</div>
										
										<?=form_dropdown('vendorname_list', $vendornames_array, '', ' id="vendorname_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
									</div> 
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									Vendor List
								</div>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" class="form-control input-sm cls_brand auto_suggest"  list-container="vendorlist_list" id="cbo_vendorlist" name="cbo_vendorlist" placeholder="Vendor List" list="vendorlist">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorlist" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<?=form_dropdown('vendorlist_list', $vendorlist_array, '', ' id="vendorlist_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
									
								</div> 
							</div>
							<div class="row">
								<div class="col-md-4">
									Category
								</div>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" class="form-control input-sm cls_brand auto_suggest" list-container="category_list" id="cbo_vendorcategory" name="cbo_vendorcategory" placeholder="Category Name" list="vendorcategoryname">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorcategory" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<?=form_dropdown('category_list', $category2_array, '', ' id="category_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
									
								</div> 
							</div>
							<div class="row">
								<div class="col-md-4">
									Brand
								</div>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" class="form-control input-sm cls_brand auto_suggest" list-container="brand_list" id="cbo_vendorbrand" name="cbo_vendorbrand" placeholder="Brand Name" list="vendorbrandname">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorbrand" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<?=form_dropdown('brand_list', $brand_array, '', ' id="brand_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
									</div> 
							</div>
							<div class="row">
								<div class="col-md-4">
									Location/City
								</div>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" class="form-control input-sm cls_brand auto_suggest" list-container="location_list" id="cbo_vendorlocation" name="cbo_vendorlocation" placeholder="Location Name" list="vendorlocationname">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorlocation" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<?=form_dropdown('location_list', $location_array, '', ' id="location_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
								</div> 
							</div>
							<div class="row">
								<div class="col-md-4">
									RFQ/Bid
								</div>
								<div class="col-md-8">
									<div class="input-group">
										<input type="text" class="form-control input-sm cls_brand auto_suggest" list-container="rfq_list" id="cbo_vendorrfq" name="cbo_vendorrfq" placeholder="RFQ Name" list="vendorrfq">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default btn-sm autocomplete-toggle" type="button" input-toggle="cbo_vendorrfq" >
												<span class="caret"></span>
											</button>
										</div>
									</div>
									<?=form_dropdown('rfq_list', $rfq_array, '', ' id="rfq_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
								</div> 
							</div>
							<br>
							<div class="row">
								<div class="col-md-offset-6">
									<?=form_button('btn_search_vendor_clear', 'Clear', 'onclick="clear_search_vendor()" class="btn btn-default dropdown-toggle btn1"')?>
									<?=form_button('btn_search_vendor_search', 'Search', 'class="btn btn-default dropdown-toggle btn1" onclick="search_invite()"')?>
								</div>
								
							</div>
						</div>
						<input type="hidden" id="seach_result_view" name="seach_result_view" value="0">
						<div class="col-md-6" style="display: none;" id="search_view_list">
							<div class="panel panel-primary">
								<div class="panel-heading" style="height:40px;">
									<div class="col-md-6">
										<strong>Search Result</strong>
									</div>
									<div class="col-md-6">
										<?=form_button('invite_vendor_btn', 'Invite', 'onclick="invite_selected_vendor()" class="btn btn-default btn-xs btn-default btn1"')?>
										<?=form_button('invite_vendor', 'Close', 'onclick="close_invite_search()" class="btn btn-default btn-xs btn-default btn1" ')?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12" id="search_filter_table">
										
									</div>
								</div>


							</div>
						</div>
					</div>
				</div>
				<!-- END OF SEARCH VENDOR -->
				<!-- add new vendor -->
				<div class="panel panel-default" style="margin: auto;width: 95%; align: center; padding: 20px 20px 20px 20px; display: none;" id="new_vendor_div">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-primary">
								<div class="panel-heading">New Vendor</div>
								<div class="row indent_sides indent_top">
									<div class="col-md-4">
										Vendor
									</div>
									<div class="col-md-8">
										<?=form_input('txt_vendorname', '', 'id="txt_vendorname" class="form-control"')?>
									</div>
								</div>
								<div class="row indent_sides indent_top">
									<div class="col-md-4">
										Contact
									</div>
									<div class="col-md-8">
										<?=form_input('txt_contact_person', '', 'id="txt_contact_person" class="form-control" ')?>
									</div>
								</div>
								<div class="row indent_sides indent_top">
									<div class="col-md-4">
										Email
									</div>
									<div class="col-md-5">
										<?=form_input('txt_email', '', 'id="txt_email" class="form-control isEmail" oninput="change_border(this.id)"')?><br>
									</div>
									<div class="col-md-3">
										<?=form_button('btn_new_vendor_similar', 'Find Similar', 'id="findsimilar" class="btn btn-default" style="padding-left: 10px;"')?>
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
								<div class="row indent_sides indent_top">
									<div class="col-md-offset-5">
										<?=form_button('btn_new_vendor_clear_add', 'Clear', 'onclick="clear_new_vendors_add()" class="btn btn-default"')?>
										<?=form_button('btn_new_vendor_invite', 'Invite', 'onclick="add_new_invite()" class="btn btn-default"')?>
										<?=form_button('btn_new_vendor_close', 'Close', 'class="btn btn-default" onclick="close_div_rfq(\'new_vendor_div\', \'none\')"')?>
									</div>
								</div>
								<?=br(1)?>

							</div>
						</div>
					</div>
						
				</div>
				<?=br(2)?>
				<!-- end new vendor -->
			</div>
		</div>
	</div>

	<!-- END OF INVITED VENDORS -->

	<input type="hidden" name="newvendor_count" id="newvendor_count" value="0">

	<div id="asd">
		<input type="hidden" name="newvendor_id1" id="newvendor_id1" value="">
		<input type="hidden" name="newvendor_invite_id1" id="newvendor_invite_id1" value="">
	</div>

	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
             	<div class="modal-header">					
					
					<span class="document_preview" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Preview</h4>
						<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
						<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
					</span>				
				</div>
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal3">
							<span class="document_preview" style="display:none;">
								<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
							</span>
                       </div>
                  </div>
                  <div class="modal-footer">
					<span class="document_preview" style="display:none;">
						<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
					</span>
                  </div>
             </div>
        </div>
	</div>
	

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                    <button type="button" onclick="new_attachment_pic()" class="btn btn-default btn-xs btn_min_width" align="center" id="btn_ok_attach">OK</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width" align="center">Close</button>
                  </div>
             </div>
        </div>
	</div>

    <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding: 10% 0 0 0">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal2">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                  <center>
                    <button type="button" class="btn btn-default btn-xs btn_min_width" align="center" onclick="submit_load()">OK</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width" align="center" onclick="return_dashboard()">Cancel</button>
                  </center>
                  </div>
             </div>
        </div>
	</div>

    <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding: 10% 0 0 0">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal4">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                  <center>
                    <button type="button" class="btn btn-default btn-xs btn_min_width" align="center" onclick="validate_save_vendor_list()">Save</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width" align="center">Cancel</button>
                  </center>
                  </div>
             </div>
        </div>
	</div>

	</div>
	</div>
	</div>

<?=form_close();?>

<script>
$(document).ready(function() {
	if(document.getElementById('rfq_id').value == 0)
	{
	    $('#myModal2').modal({
	        backdrop: 'static',
	        keyboard: false
	    })
	   $('#myModal2').modal('show');          

	    var url = BASE_URL + 'rfqb/rfq_main/rfq_onload_modal_view';            

	    $.post(
	        url,
	        {
	        },
	        function(responseText) { 
	            $("#view_modal2").html(responseText);
	        },
	        "html"
	        );

	}else
	{
			submit_load_draft(document.getElementById('rfq_id').value); 
			
	}

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
         if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 

        today = yyyy+'-'+mm+'-'+dd;
        // document.getElementById("datefield").setAttribute("max", today);
        $('#pref_delivery_date').prop('readonly', true);
        $('#sub_deadline_date').prop('min', today);

});

	function load_attachment(path)
	{
		var url = BASE_URL.replace('index.php/','') + path;
        
        $('#imagepreview').attr('src', '');
        if (path != '')
        {
            $('#imagepreview').attr('src', url);
            $('#imagepreview').removeClass('zoom_in');
            $('.modal-dialog').addClass('modal-lg');    
            var filext = path.split('.').pop();
            // setting height of iframe according to window size
            var set_height  = '';
            var w_h         = '';
            var t_h         = '';

            if (filext.toLowerCase().match(/(jpeg|jpg|png|pdf)$/))
            {
            	$('#myModal3').modal('show');

			    $('#myModal3 span').hide();
			    $('.alert > span').show(); // dont include to hide these span
			    $('#myModal3 .document_preview').show();

                w_h = $(window).height() /2;
                t_h = $(this).height() /2;
                $('#imagepreview').css('display', 'inherit');
                $('#zoom_image').show();
                $('#zoom_out_image').show();
            }
            else
            {
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#imagepreview').css('display', 'none');
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();                
            }
            $('iframe').height(w_h);
            $(window).resize(function(){
                $('iframe').height(t_h);
            });
            //$('#imagepreview').attr('src', '');
        }
        else
        {
            $('#imagepreview').attr('src', '');
        }
	}

	function zoomimage()
	{
		$('#imagepreview').addClass('zoom_in');
	}

	function zoomoutimage()
	{
	    $('#imagepreview').removeClass('zoom_in');
	}

	function select_all_invite(countall, ischecked)
	{
		if (ischecked)
		{
			for(i=1; i <= countall; i++)
			{
				document.getElementById('list_vendor'+i).checked = true;
				invitecheck(true, i, 'vendorischecked');
			}
		}
		else
		{
			for(i=1; i <= countall; i++)
			{
				document.getElementById('list_vendor'+i).checked = false;
				invitecheck(false, i, 'vendorischecked');
			}
		}

		return;
	}

	function return_dashboard()
	{
		document.form1.action = BASE_URL + "dashboard";
		document.form1.target = "_self";
		document.form1.submit();
	}
	
	function specsview(row)
	{
		if (document.getElementById('specs'+row).value == 0)
		{
			document.getElementById('specs'+row).value = 1;

			document.getElementById('specifications'+row).style.display = 'inline';

			// add code here for show next tr
		}
		else	
		{
			document.getElementById('specs'+row).value = 0;

			document.getElementById('specifications'+row).style.display = 'none';

			// add code here for hide next tr
		}	
		return;
	}

	function change_preferred_max(submission_value)
	{
		var new_preferred = new Array();
		new_preferred = submission_value.split('-');

		var today = new Date();
	        var dd = Number(new_preferred[2]) + 1;
	        var mm = new_preferred[1]; //January is 0!
	        var yyyy = new_preferred[0];

	        total_days = get_total_days(mm, yyyy);

	        if(dd > total_days)
	        {
	        	mm++;
	        	dd = 1;
	        	
		        if(dd < 10){
	                dd='0'+dd
	            } 
	            if(mm<10){
	                mm='0'+mm
	            } 
	        }
	        
	        today = yyyy+'-'+mm+'-'+dd;
	        //console.log(today);
	        // document.getElementById("datefield").setAttribute("max", today);
	        $('#pref_delivery_date').prop('readonly', false);
	        $('#pref_delivery_date').prop('min', today);
	
	}

	function get_total_days(month, year)
	{
		days_in_month	= Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);

		if (month < 1 || month > 12)
		{
			return 0;
		}

		// Is the year a leap year?
		if (month == 2)
		{
			if (year % 400 == 0 || (year % 4 == 0 && year % 100 != 0))
			{
				return 29;
			}
		}

		return days_in_month[month - 1];
	}

	function check_all_invited_vendor(ischecked)
	{

		count_all_invited = $('#count_all_invited').val();
		
		if (ischecked)
		{
			for(i=1; i <= count_all_invited; i++)
			{
				document.getElementById('transfered_invited'+i).checked = true;
				invitecheck(true, i, 'final_invited_chkbx');
			}
		}
		else
		{
			for(i=1; i <= count_all_invited; i++)
			{
				document.getElementById('transfered_invited'+i).checked = false;
				invitecheck(false, i, 'final_invited_chkbx');
			}
		}

		return;

	}

function submit_load_draft()
{
	var selected = 1;
        if(window.XMLHttpRequest)
            xmlhttp_draft = new XMLHttpRequest();
        else
            xmlhttp_draft = new ActiveXObject("Microsoft.XMLHTTP");

        xmlhttp_draft.onreadystatechange = function(){

            if(xmlhttp_draft.readyState==4 && xmlhttp_draft.status==200)
            {
                //on 200=done, 
                var data = $.parseJSON(xmlhttp_draft.responseText);

                if(data.line_data_count > 0)
                {
                    if(data.line_data_count > 1)
                    {
                    	
	                            add_delete_lines_generation(1, data.line_data_count).done(function(){
	                                fill_rfq_draft(xmlhttp_draft.responseText); 
	                            });
	                    
                    }
                    else
                    {
                    	
                           fill_rfq_draft(xmlhttp_draft.responseText); 
                    	
                        
                    }
                    var span_message = 'Data loaded successfully';
                    var type = 'success';
                    notify(span_message, type);
                    
                }
                else
                {
                    var span_message = 'No Result Found';
                    var type = 'danger';
                    modal_notify("myModal2", span_message, type);
                    return;

                }
            }
        }

        path_value = BASE_URL + 'rfqb/rfq_main/search_rfq';

        parameter = 'selected='+selected+
                            '&find_rfq=""'+
                            '&rfq_id='+document.getElementById('rfq_id').value;

        xmlhttp_draft.open("POST", path_value, true);
        xmlhttp_draft.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp_draft.send(parameter);


}

function fill_rfq_draft(olddata)
{
    var data = $.parseJSON(olddata);
    //console.log(data);
    // upper part
    $('#title_txt').val(data.main[0].TITLE);
    $('#type_radio').val(data.main[0].RFQRFB_TYPE);
    $('#pref_delivery_date').val(data.preferred_del_date);
    $('#sub_deadline_date').val(data.submission_date);

    if(data.main[0].RFQRFB_TYPE == 1)
        $('#qualified').prop('checked', true);
    else
        $('#competitive').prop('checked', true);

    $('#currency').val(data.main[0].CURRENCY_ID);
    
    //this is for sm view only
    $('#requestor').val(data.main[0].REQUESTOR_ID);
    $('#purpose').val(data.main[0].PURPOSE_ID);
    $('#purpose_txt').val(data.main[0].OTHER_PURPOSE);
    $('#reason').val(data.main[0].REASON_ID);
    $('#reason_txt').val(data.main[0].OTHER_REASON);
    $('#internal_note').val(data.main[0].INTERNAL_NOTE);


    //lines displaying
    if(data.line_data_count > 0)
    {
        
        var a = '';
        var b = '';
        var path = '';
        var url = BASE_URL.replace("index.php/", "");

        for(x=0, j=1; x < data.line_data_count; x++,j++)
        {
            var c = 0;
			$('#hidden_bom_attach'+j).val(0);
            //display attachment
            if(data.attachment_data_count > 0)
            {
                a = data.line_data[x].RFQRFB_LINE_ID;
                // put data
                $('#line_category'+j).val(data.line_data[x].CATEGORY_ID);
                $('#line_description'+j).val(data.line_data[x].DESCRIPTION);
                $('#line_measuring_unit'+j).val(data.line_data[x].UNIT_OF_MEASURE);
                var quantity_value = numberWithCommas(data.line_data[x].QUANTITY);
                $('#quantity'+j).val(quantity_value);// $.formatNumber(number, {format:"#,###.00", locale:"us"})
                $('#specs'+j+'_text').val(data.line_data[x].SPECIFICATION);
                $('#specs'+j).val(1);
                $('#attach'+j).val(1);
                $('#specifications'+j).css('display', 'inline');
                $('#attachment'+j).css('display', 'inline');

                $('#add_attachment'+j).css('display', 'inline');
                $('#delete_attachment'+j).css('display', 'inline');

                if(a != b)// for 1 display per lineid query result only
                {
                    for(r=0, s=1; r < data.attachment_data_count; r++, s++)
                    {
                    	var doc_pic = '';						
						if(data.attachment_data[r].ATTACHMENT_TYPE == 3) {
							doc_pic= data.attachment_data[r].FILE_PATH;
                    	} else {
                    		doc_pic= data.attachment_data[r].LOGO_PATH;
						}
						
                        path =  data.attachment_data[r].RFQRFB_LINE_ID;
                        if(a == path) // to make sure that only attachment for this lineid will display only
                        {
                            c++; // c for for the count of attachment displayed
							if(data.attachment_data[r].ATTACHMENT_TYPE == 1) {
								$('#hidden_bom_attach'+j).val(s);
								
							}
								
							
                            $('#attachment'+j).css('display', 'inherit');
                            $('#attachment'+j+'_'+s).css('display', 'inline-block');
                            $('#attachment'+j+'_'+s).addClass('dv_attachment');

                            $('#image'+j+'_'+s).attr('src', url+doc_pic);
                            $('#image'+j+'_'+s).attr('onclick', "load_attachment('"+data.attachment_data[r].FILE_PATH+"')");
                            $('#line_attachment_id_'+j+'_'+s).val(data.attachment_data[r].LINE_ATTACHMENT_ID);
                            $('#hidden_path_'+j+'_'+s).val(data.attachment_data[r].FILE_PATH);
                            $('#attachment_desc_'+j+'_'+s).val(data.attachment_data[r].A_DESCRIPTION);
                            $('#attachment_type_'+j+'_'+s).val(data.attachment_data[r].ATTACHMENT_TYPE);
                            document.getElementById('attachment_count'+j).innerHTML = c;

                            if(c > 0)
                                $('#delete_attachment'+j).prop('disabled', false);
                            
                            //put checkbox and ddesctiption
                            document.getElementById('chkbx_line'+j+'_'+s).innerHTML = '<input type="checkbox" id="chkbox_'+j+'_'+s+'" name="chkbox_'+j+'_'+s+'" onchange="invitecheck(this.checked, \''+j+'_'+s+'\', \'checkbox_attachment_\')">'+data.attachment_data[r].A_DESCRIPTION+'</input>';
                        }
                        if(s >= 8)
                        	s = 0;
                    }
                    b = a;
                }
            }
        }
    }
    // end of lines

    // start of putting data in invited vendors
    if(data.invited_data_count > 0)
    {
        var cell = '';
        for(i=0, y=1; i < data.invited_data_count; i++, y++)
        {
            cell += ' <tr>'+
                        '<td><input type="checkbox" name="transfered_invited'+y+'" id="transfered_invited'+y+'" value="'+y+'" onchange="invitecheck(this.checked, '+y+', \'final_invited_chkbx\')"></td>'+
                        '<td>'+data.invited_data[i].VENDOR_NAME+
                            '<input type="hidden" name="vendorinvitefinal_id'+y+'" id="vendorinvitefinal_id'+y+'" value='+data.invited_data[i].VENDOR_ID+'>'+
                            '<input type="hidden" name="vendorfinal_invite_id'+y+'" id="vendorfinal_invite_id'+y+'" value='+data.invited_data[i].INVITE_ID+'>'+
                            '<input type="hidden" name="vendorname_finalinvited'+y+'" id="vendorname_finalinvited'+y+'" value="'+data.invited_data[i].VENDOR_NAME+'">'+
                            '<input type="hidden" name="final_invited_chkbx'+y+'" id="final_invited_chkbx'+y+'" value=0>'+
                        '</td>'+
                    '</tr>';
        }
        var table =    '<input type="hidden" name="count_all_invited" id="count_all_invited" value="'+data.invited_data_count+'">'+
                        '<table class="table">'+
                            '<thead>'+
                                '<div class="col-md-2">'+
                                    '<th>'+
                                        'Select'+
                                                '<input type="checkbox" name="check_all_vendor" id="check_all_vendor" value="all">'+
                                    '</th></div><div class="col-md-10"><th>Vendor</th>'+
                                '</div></thead><tbody>'+cell+'</tbody></table>';

        //document.getElementById("selected_invited_vendor").innerHTML = 'asd';
        document.getElementById('selected_invited_vendor').innerHTML = table;
    }
    //end of invited vendors

}

	function close_invite_search()
	{
		document.getElementById('search_view_list').style.display = "none";
	}


	function search_vendor_click()
	{
		document.getElementById('seach_result_view').value == 0;
		document.getElementById('search_view_list').style.display = 'none';

		if (document.getElementById('search_vendor_hidden').value == 0)
		{
			document.getElementById('search_vendor_hidden').value = 1;
			document.getElementById('search_vendor_div').style.display = 'inherit';

			if(document.getElementById('new_vendor_hidden').value = 1)
			{
				document.getElementById('new_vendor_hidden').value = 0;
				document.getElementById('new_vendor_div').style.display = 'none';
			}
		}
		else
		{
			document.getElementById('search_vendor_hidden').value = 0;
			document.getElementById('search_vendor_div').style.display = 'none';

			if(document.getElementById('new_vendor_hidden').value = 0)
			{
				document.getElementById('new_vendor_hidden').value = 1;
				document.getElementById('search_vendor_div').style.display = 'inherit';
			}
		}
		
	}

	function new_vendor_click()
	{
		document.getElementById('seach_result_view').value == 0;
		document.getElementById('search_view_list').style.display = 'none';
		if (document.getElementById('new_vendor_hidden').value == 0)
		{
			document.getElementById('new_vendor_hidden').value = 1;
			document.getElementById('new_vendor_div').style.display = 'inherit';

			if(document.getElementById('search_vendor_hidden').value = 1)
			{
				document.getElementById('search_vendor_hidden').value = 0;
				document.getElementById('search_view_list').style.display = 'none';
				document.getElementById('search_vendor_div').style.display = 'none';
			}
		}
		else
		{
			document.getElementById('new_vendor_hidden').value = 0;
			document.getElementById('new_vendor_div').style.display = 'none';

			if(document.getElementById('search_vendor_hidden').value = 0)
			{
				document.getElementById('search_vendor_hidden').value = 1;
				document.getElementById('search_view_list').style.display = 'inherit';
				document.getElementById('search_vendor_div').style.display = 'inherit';
			}
		}
		
	}

	function select_all_lines()
	{
		total_rows = document.getElementById('max_lines').value;

		if ($('[name="select_all"]').is(':checked'))
		{
			for(i = 1; i <= total_rows; i++)
			{
				document.getElementById('chkbx' + i).checked = true;
			}
		}
		else
		{
			for(i = 1; i <= total_rows; i++)
			{
				document.getElementById('chkbx' + i).checked = false;
			}
		}

	}
	$('#internal_note').keyup(function () {
		  var max = 300;
		  var len = $(this).val().length;
		  if (len >= max) {
		    $('#charNum').text('You have reached the limit.');
		  } else {
		    var char = max - len;
		    $('#charNum').text(char + ' characters left');
		  }
	});
	
	$(document).on('keyup', '.specs_txt_area', function(){
			
		  var max = 3000;
		  var len = $(this).val().length;
		  var specs_id = $(this).attr('id');
		  if (len >= max){
		    $('#' + specs_id +'_char_num').text('(You have reached the limit.)');
		  }else{
		    var char = max - len;
		    $('#' + specs_id +'_char_num').text('(' + char + ' characters left)');
		  }
	});
	
	$(document).on('input', '.line_description_input', function(){
			
		  var max = 300;
		  var len = $(this).val().length;
		  var specs_id = $(this).attr('id');
		  if (len >= max){
		    $('#' + specs_id +'_char_num').text('(You have reached the limit.)');
		  }else{
		    var char = max - len;
		    $('#' + specs_id +'_char_num').text('(' + char + ' characters left)');
		  }
	});
	
	$(document).on('input', '#modal_txt_description', function(){
			
		  var max = 300;
		  var len = $(this).val().length;
		  var specs_id = $(this).attr('id');
		  if (len >= max){
		    $('#' + specs_id +'_char_num').text('(You have reached the limit.)');
		  }else{
		    var char = max - len;
		    $('#' + specs_id +'_char_num').text('(' + char + ' characters left)');
		  }
	});
</script>

<!-- blank table
<table>
<tr>
<td></td>
</tr>
</table>
 -->

<!-- 
<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<div class="col-md-12">
					<h5>SM View Only</h5>
				</div>
			</div>
		</div>
	</div>
 -->
