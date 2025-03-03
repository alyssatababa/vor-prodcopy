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

textarea.form-control
{
		resize: vertical;
		height: 34px;
}


#tbl_shortlist .chk_sel{

		margin-left:20%;

}

/**@supports (zoom:2) {
	input[type=checkbox]{
	zoom: 2;
	}
}
@supports not (zoom:2) {
	input[type=checkbox]{
		transform: scale(2);
		margin: 1px;
	}
}**/
</style>
<?=form_open_multipart('form1', array('name' => 'form1', 'id' => 'frm_shortlist') );?>
<input type="hidden" name="rfq_id" id="rfq_id" value="<?php echo $id; ?>">
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-6">
			<h4>Short List - <?php echo 'RFQ/RFB# '.$rfq[0]->RFQRFB_ID.' - '.$rfq[0]->TITLE; ?></h4>
		</div>



			<?php if (isset($for_approval)): ?>
				<div class="col-md-offset-10">
				<button class="btn btn-primary btn-sm" id="btn_shortlist_approve" onclick ="return false;">Approve</button>
				<button class="btn btn-primary btn-sm" id="btn_shortlist_reject" onclick ="return false;">Reject</button>
				<button type="button" class="btn btn-primary btn-sm btn-exit">Close</button>
				</div>
			<?php else: ?>
				<div class="col-md-offset-8">
				<button class="btn btn-primary btn-sm" id="btn_submit_approve" onclick ="return false;" disabled>Submit Shortlist For Approval</button>
				<button class="btn btn-primary btn-sm" id="btn_failed_bid" onclick ="return false;">Failed Bid</button>
				<button type="button" class="btn btn-primary btn-sm btn-exit">Close</button>
				</div>
			<?php endif; ?>
			<!-- <button class="btn btn-primary btn-sm" id="btn_close">Close</button> -->

	</div>
	<br>
	<!-- TABLE -->
	<div class="row">
	<div class = "container form_container">
	<form>
			<div class="panel panel-primary">
				<div class="form-group indent_top indent_left">
					<div class="row">
						<div class="col-md-4">

							Title:
							<label class="form-label"> <?php
							echo $rfq[0]->TITLE;
							 ?>

							 </label>
						</div>
						<div class="col-md-4">
							Created By:
							<label class="form-label">

								<?php

									echo $rfq[0]->USER_FIRST_NAME . ' ' .  $rfq[0]->USER_MIDDLE_NAME .' ' . $rfq[0]->USER_LAST_NAME ;

								?>

							</label>
						</div>
						<div class="col-md-3">
							Date Created:
							<label class="form-label">
						<?php
							$sDate = new  DateTime($rfq[0]->DATECREATED);
							echo $sDate->format('M-d-Y');
							 ?>


							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							Type:
							<label class="form-label"><?php echo $rfq[0]->RFQRFB_TYPE_NAME; ?></label>
						</div>
						<div class="col-md-2">
							Currency:
							<label class="form-label"><?php echo $rfq[0]->ABBREVIATION; ?></label>
						</div>
						<div class="col-md-4">
							Preferred Delivery Date:
							<label class="form-label">
							<?php  $date = new DateTime($rfq[0]->DELIVERY_DATE);

									echo $date->format('M-d-Y');

							 ?></label>
						</div>
						<div class="col-md-4">
							Submission Deadline Date:
							<label class="form-label">

							<?php  $date = new DateTime($rfq[0]->SUBMISSION_DEADLINE);
								echo $date->format('M-d-Y');
							 ?>



							</label>
						</div>
					</div>

				</div>
	</form>
				<!-- SM VIEW ONLY -->
				<div class="row">
					<div class="form-horizontal">
						<div class="form-group">
							<label for="requestor" class="col-md-2 control-label">Requestor</label>
							<div class="col-md-9">
								<select name="requestor" id="requestor" class="form-control" disabled>
									<option value="it_dept">  <?php echo $rfq[0]->REQUESTOR;  ?> </option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Purpose of Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control" disabled>
									<option value="replacement"><?php echo $rfq[0]->PURPOSE;  ?></option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_purpose" id="other_purpose" class="form-control" disabled><?php echo $rfq[0]->OTHER_PURPOSE;  ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Reason for Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control" disabled>
									<option value="another_reason"><?php echo $rfq[0]->REASON;  ?></option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_reason" id="other_reason" class="form-control" row="1" disabled><?php echo $rfq[0]->OTHER_REASON;  ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_note" class="col-md-2 control-label">Internal Note</label>
							<div class="col-md-9">
								<textarea name="request_note" id="request_note" class="form-control" row="1" readonly><?php echo $rfq[0]->INTERNAL_NOTE;  ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- END SM VIEW ONLY -->
				<?=br(1)?>
			</div><!-- end of panel -->

			<!-- start new panel -->
			<div class="panel panel-primary">
				<div class="row" style="padding-bottom: 0px;">
					<div class="form-group indent_sides indent_top">
						<div class="col-md-12">
							<?Php
								for($j=0;$j<count($line);$j++){
									$bom_modal_data['line_attachment_id'] = $line[$j]->LINE_ATTACHMENT_ID;
									$bom_modal_data['line'] = $line[$j];
									$bom_modal_data['current_currency_data'] = $rfq[0]->ABBREVIATION;
									$bom_modal_data['user_type'] = $this->session->userdata('user_type');
									$bom_modal_data['rfqrfb_id'] = $rfq[0]->RFQRFB_ID;
									$bom_modal_data['title'] = $rfq[0]->TITLE;

									// $this->load->view('rfqb/bom_modal_view',$bom_modal_data);
									$this->load->view('rfqb/bom_modal_compare_view',$bom_modal_data);
								}
							?>
							<div class="table-responsive">
								<table id = "tbl_shortlist" class="table" style="margin-bottom: 0px"  align="center">
									<thead>
										<th style="width:15%">Vendor : </th>

										<?php
										$vendor_list = array();
										$date_created_list = array();
										$qoute_amount = array();
										$lead_time = array();
										$counter_offer = array();
										$attach_arr = array();
										$qoute_id_arr = array();
										$qoute_row_arr = array();
										$shortlisted = array();
										$vendor_count_arr = array();
										$line_arr = array();
										for($i = 0 ; $i < count($part);$i++){

											if (!array_key_exists($part[$i]->VENDOR_ID, $vendor_list))
												$vendor_list[$part[$i]->VENDOR_ID] = $part[$i]->VENDOR_NAME;
												$date_created_list[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->DATE_CREATED;
												$qoute_amount[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->QUOTE_AMOUNT;
												$lead_time[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->LEAD_TIME;
												$counter_offer[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->COUNTER_OFFER;
												$attach_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->ATTACHMENT_PATH;
												$qoute_id_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->RESPONSE_QUOTE_ID;
												$qoute_row_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $i;
												$shortlisted[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->SHORTLISTED;

												$vendor_count_arr[] = $part[$i]->VENDOR_ID.'-'.$part[$i]->LINE_ID;

											if (!in_array($part[$i]->LINE_ID, $line_arr))
												$line_arr[] = $part[$i]->LINE_ID;
										}

										$vendor_count_arr = array_count_values($vendor_count_arr);
										$arr_count = array_keys($vendor_count_arr);
										$max_count_line = array();
										foreach ($arr_count as $key => $value) {
											$id_line = explode('-',$value); // $id_line[0] = VENDOR_ID ,$id_line[1] = LINE_ID

											if (array_key_exists($id_line[1], $max_count_line))
											{
												if ($max_count_line[$id_line[1]] < $vendor_count_arr[$value])
												$max_count_line[$id_line[1]] = $vendor_count_arr[$value];
											}
											else
												$max_count_line[$id_line[1]] = $vendor_count_arr[$value];

										}
										// print_r($max_count_line);

										// $largest_arr = 0;
										// if (!empty($vendor_count_arr))
										// 	$largest_arr = max($vendor_count_arr);
										// $count = array_map('count', $vendor_count_arr);
										// $min = array_keys($count , max($count))[0];
										// $largest_arr = $vendor_count_arr[$min];

										// for($i = 0 ; $i < count($part);$i++){



										// 	echo '<th>'. $part[$i]->VENDOR_NAME .'</th>';
										// }
										foreach ($vendor_list as $key => $value) {
											echo '<th style="min-width:300px">'. $value .'</th>';
										}



										?>

									</thead>

									<?php
									for($j=0;$j<count($line);$j++){
										echo '<tr>';
										echo '<td colspan = "1000" class = "bg-primary text-white"> <table style="width:100%;"> <tr>';


										echo '<td colspan="2"align="left">';
										echo $line[$j]->CATEGORY_NAME .' - '.  $line[$j]->DESCRIPTION;
										echo '</td>';
										echo '<td align="right">';
										echo '<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$line[$j]->LINE_ATTACHMENT_ID.'" class="btn btn-default" style="display:'.($line[$j]->BOM_ROWS > 0 ? 'inline-block' : 'none').';" value="Compare BOM"></input>';
										echo '</td>';

										echo '</tr></table>';
										echo '</td>';
										echo '</tr>';


										if (isset($max_count_line) && count($max_count_line)>0) {
											echo '<tr>';
											if (isset($for_approval)){
												echo '<td class="info"></td>';
											}
											else{
												echo '<td class="info">No Shortlist <br><input style = "margin-left:20%;" type = "checkbox" onclick="chk_noshort(this)" value="'.$j.'">';
											}


											$chk_cnt= 0;
											for ($i=0; $i < $max_count_line[$line_arr[$j]]; $i++) {
												$isFirst = true;
												foreach ($vendor_list as $key => $value) {
														if ($isFirst && $i > 0) // add td on from 2nd row and so on to first array
														{
															echo '<td class="info"><br></td>';
														}
													// for ($i=0; $i < count($largest_arr); $i++) {
														if (isset($for_approval))
														{
															if (array_key_exists($i, $shortlisted[$key][$line_arr[$j]])){
																if ($shortlisted[$key][$line_arr[$j]][$i] == 1){
																	if($i == 0){
																		echo '<td class="info"><br><input class = "chk_sel'.$j. ' ' . $key. '-' . $line_arr[$j] .'" type = "checkbox" name="chk_shortlist[]" value="'.$qoute_id_arr[$key][$line_arr[$j]][$i].'"  onchange="update_hidden_chk(this,'.$chk_cnt.')" checked disabled><input class = "chk_sel_h[]'.$j.'" type = "checkbox" name="chk_shortlist_h[]" value="'.$qoute_row_arr[$key][$line_arr[$j]][$i].'" style="display:none;"></td>';
																	}else{
																		echo '<td class="info"><br><input style="display:none;" class = "chk_sel'.$j. ' ' . $key. '-' . $line_arr[$j] .'" type = "checkbox" name="chk_shortlist[]" value="'.$qoute_id_arr[$key][$line_arr[$j]][$i].'"  onchange="update_hidden_chk(this,'.$chk_cnt.')" checked disabled><input class = "chk_sel_h[]'.$j.'" type = "checkbox" name="chk_shortlist_h[]" value="'.$qoute_row_arr[$key][$line_arr[$j]][$i].'" style="display:none;"></td>';
																	}
																}else{
																	echo '<td class="info"><br></td>';
																}
															}else{
																echo '<td class="info"><br></td>';
															}
														}
														else
														{
															if (array_key_exists($i, $shortlisted[$key][$line_arr[$j]])){	
																//Hide Checkbox and show number one only
																//Added  $key. '-' . $line_arr[$j] .' to identify the quote versions
																if($i == 0){
																	echo '<td class="info"><br><input class="chk_sel'.$j. ' ' . $key. '-' . $line_arr[$j] .'" type="checkbox" name="chk_shortlist[]" value="'.$qoute_id_arr[$key][$line_arr[$j]][$i].'" onchange="update_hidden_chk(this,'.$chk_cnt.')" ><input class = "chk_sel_h[]'.$j.'" type = "checkbox" name="chk_shortlist_h[]" value="'.$qoute_row_arr[$key][$line_arr[$j]][$i].'" style="display:none;"></td>';
																}else{
																	echo '<td class="info"><input style="display:none;" class="chk_sel'.$j. ' ' . $key. '-' . $line_arr[$j] .'" type="checkbox" name="chk_shortlist[]" value="'.$qoute_id_arr[$key][$line_arr[$j]][$i].'" onchange="update_hidden_chk(this,'.$chk_cnt.')" ><input class = "chk_sel_h[]'.$j.'" type = "checkbox" name="chk_shortlist_h[]" value="'.$qoute_row_arr[$key][$line_arr[$j]][$i].'" style="display:none;"></td>';
																}
																
															}else{
																echo '<td class="info"><br></td>';
															}
														}
													// }
													$chk_cnt+=1;
													$isFirst = false;
												}

												echo '</tr>';
												
												$all_line_prices = array();
												$p = 0;			
												foreach ($vendor_list as $key => $value) {
													$all_line_prices[] =$qoute_amount[$key][$line_arr[$j]][0];	
												}
												
												echo '<tr>';
												echo '<td>Date Created : </td>';
											
												foreach ($vendor_list as $key => $value) {
													// for ($i=0; $i < count($largest_arr); $i++) {
													if (array_key_exists($i, $date_created_list[$key][$line_arr[$j]])){
														echo '<td>' . $date_created_list[$key][$line_arr[$j]][0].'</td>';
													}else{
														echo '<td></td>';
													}
													// }
												}
												
												echo '</tr>';
												echo '<tr>';
												echo '<td>Price : </td>';
												
												//echo "<pre>";
												//print_r($date_created_list);
												//echo "</pre>";
												
												
												foreach ($vendor_list as $key => $value) {
													// for ($i=0; $i < count($largest_arr); $i++) {
													if (array_key_exists($i, $qoute_amount[$key][$line_arr[$j]])){
														$p = number_format((isset($qoute_amount[$key][$line_arr[$j]][$i]) ? $qoute_amount[$key][$line_arr[$j]][$i] : 0), 2, '.', ',');
														
														if($qoute_amount[$key][$line_arr[$j]][$i] == min($all_line_prices)){
															echo '<td style="background-color:yellow;"><b>'. $p .'</b></td>';
														}else{
															echo '<td><b>'. $p .'</b></td>';
														}
													}else{
														echo '<td></td>';
													}
													// }
												}
												
												//echo "<pre>";
												//print_r($all_line_prices);
												//echo "</pre>";
												echo '</tr>';
												echo '<tr>';
												echo '<td>Delivery Lead Time : </td>';

												foreach ($vendor_list as $key => $value) {
													// for ($i=0; $i < count($largest_arr); $i++) {
														if (array_key_exists($i, $lead_time[$key][$line_arr[$j]]))
															echo '<td><b>'.$lead_time[$key][$line_arr[$j]][$i].'</b></td>';
														else
															echo '<td></td>';
													// }
												}

												echo '</tr>';
												echo '<tr>';
												echo '<td>Counter Offer : </td>';

												foreach ($vendor_list as $key => $value) {
													// for ($i=0; $i < count($largest_arr); $i++) {
														if (array_key_exists($i, $counter_offer[$key][$line_arr[$j]]))
															echo '<td><textarea class="grow_text" style="max-height:150px" disabled>'.$counter_offer[$key][$line_arr[$j]][$i].'</textarea></td>';
														else
															echo '<td></td>';
													// }
												}

												echo '</tr>';
												echo '<tr>';
												echo '<td>Attachments : </td>';

												foreach ($vendor_list as $key => $value) {

													// for ($i=0; $i < count($largest_arr); $i++) {
														if (array_key_exists($i, $attach_arr[$key][$line_arr[$j]]))
															if (!empty($attach_arr[$key][$line_arr[$j]][$i]))
																$attach = '<b><a href="#" onclick="load_attachment(\''.$attach_arr[$key][$line_arr[$j]][$i].'\')">Attachment</a></b>';
															else
																$attach = '<b>None</b>';
														else
															$attach = '';
													// }


												echo '<td>'.$attach.'</td>';

												}

												echo '</tr>';


											}


										} else {
											echo '<tr><td class="info">no response yet.</td></tr>';
										}




								}
								?>




								</table>
							</div>
							</div>
							</div>
							</div>
							</div>



</div>

<?=form_close();?>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
             	<div class="modal-header">
					<span class="reject_shortlist" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Reject</h4>
					</span>
					<span class="failedbid_shortlist" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Failed Bid</h4>
					</span>
					<span class="document_preview" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Preview</h4>
						<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
						<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
					</span>
				</div>
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal">
	                    	<span class="reject_shortlist" style="display:none;">
								<textarea class="form-control " placeholder="Please specify reason here" id="rejectshortlist_remarks" style="height: 100px"></textarea>
							</span>
							<span class="failedbid_shortlist" style="display:none;">
								<textarea class="form-control " placeholder="Please specify reason here" id="failedbid_remarks" style="height: 100px"></textarea>
							</span>
							<span class="document_preview" style="display:none;">
								<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
							</span>
                       </div>
                  </div>
                  <div class="modal-footer">
                    <span class="reject_shortlist" style="display:none;">
						<button type="button" class="btn btn-primary" id="sl_reject">Ok</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</span>
					<span class="failedbid_shortlist" style="display:none;">
						<button type="button" class="btn btn-primary" id="m_failedbid">Ok</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</span>
					<span class="document_preview" style="display:none;">
						<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
					</span>
                  </div>
             </div>
        </div>
	</div>
<!--<script src="<?=base_url();?>assets/js/rfqb/rfq_short.js"></script>-->
<script>
	function specs_show(row)
	{
		return 1;
	}

	function update_hidden_chk(chk,index){
		var splitted = $(chk).attr('class').split(" ");
		if($(chk).prop('checked')){
			$('.' + splitted[1]).prop('checked',true);
		}else{
			$('.' + splitted[1]).prop('checked',false);
		}
		// var checkboxes = $("input:checkbox[name='chk_shortlist[]']");
		var checkboxes_h = document.getElementsByName("chk_shortlist_h[]");
		// var curIndex = checkboxes.index(chk);

		checkboxes_h[index].checked = chk.checked;

	}

	function save_shortlisted(action_type, btn) // type 1 = submt , 2 = failed bid
	{
		loading($(btn), 'in_progress'); // loading
		var ajax_type = 'POST';
	    var url = BASE_URL + "rfqb/rfq_short_list/save_shortlisted/";
	    var post_params = $('#frm_shortlist').serialize();
	    post_params += "&action=" + action_type + "&remarks=" + $('#failedbid_remarks').val();

	    var success_function = function(responseText)
	    {
	       // console.log(responseText);
	       	if (action_type == 2)
	       		$('#myModal').modal('hide');

	       	var span_message = 'Successfully submitted!';
           	var type = 'success';
            notify(span_message, type);

            $('#btn_submit_approve').prop('disabled', true);
            $('#btn_failed_bid').prop('disabled', true);
            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
	    };

	    ajax_request(ajax_type, url, post_params, success_function);
	}

	function approve_reject_shortlisted(action_type, btn) // type 3 = Approve , 4 = Reject
	{
		$("input[name='chk_shortlist[]']").prop('disabled', false);
		loading($(btn), 'in_progress'); // loading
		var ajax_type = 'POST';
	    var url = BASE_URL + "rfqb/rfq_short_list/approve_reject_shortlisted/";
	    var post_params = $('#frm_shortlist').serialize();
	    post_params += "&action=" + action_type + "&remarks=" + $('#rejectshortlist_remarks').val();

	    var success_function = function(responseText)
	    {
	       // console.log(action_type);

	       if (action_type == 4)
            	$('#myModal').modal('hide');

	       	var span_message = 'Success!';
           	var type = 'success';
            notify(span_message, type);

            $('#btn_shortlist_approve').prop('disabled', true);
            $('#btn_shortlist_reject').prop('disabled', true);


            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
            $("input[name='chk_shortlist[]']").prop('disabled', true);
	    };

	    ajax_request(ajax_type, url, post_params, success_function);
	}

	function chk_noshort(chk)
	{
		if ($(chk).is(':checked')) // if checked disabled isang helera
		{
			$('.chk_sel'+chk.value).prop('disabled', true);
			$('.chk_sel'+chk.value).prop('checked', false);
		}
		else
			$('.chk_sel'+chk.value).prop('disabled', false);


		if ($("input[name='chk_shortlist[]']:checked").length > 0)
			$('#btn_submit_approve').prop('disabled', false);
		else
			$('#btn_submit_approve').prop('disabled', true);
	}

	function load_attachment(path)
	{
		var url = BASE_URL.replace('index.php/','') + path;
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .document_preview').show();
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

            if (filext.toLowerCase().match(/(jpeg|jpg|png)$/))
            {
                w_h = $(window).height() /2;
                t_h = $(this).height() /2;
                $('#zoom_image').show();
                $('#zoom_out_image').show();
            }
            else
            {
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();

                $('#imagepreview').css('display', 'none');
            }
            $('iframe').height(w_h);
            $(window).resize(function(){
                $('iframe').height(t_h);
            });
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

	$(document).ready(function() {

		$('#btn_submit_approve').on('click', function(){
			var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_shortlisted(1, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
            var type = 'info';
            notify(span_message, type, true);
		});

		$('#btn_failed_bid').on('click', function(){

			$('#myModal').modal('show');

	        $('#myModal span').hide();
	        $('.alert > span').show(); // dont include to hide these span
	        $('#myModal .failedbid_shortlist').show();
		});

		$('#m_failedbid').on('click', function(){

	        if ($('#failedbid_remarks').val() == '')
	        {
	            modal_notify($("#myModal"),'Remarks must not be empty!', 'danger');
	        }
	        else
	        {
	           	var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_shortlisted(2, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
	            var type = 'info';
	            modal_notify($("#myModal"),span_message, type, true);
	        }
       	});

		$("input[name='chk_shortlist[]']").on('click', function(){
			if ($("input[name='chk_shortlist[]']:checked").length > 0)
				$('#btn_submit_approve').prop('disabled', false);
			else
				$('#btn_submit_approve').prop('disabled', true);
		});

		$('#btn_shortlist_approve').on('click', function(){
			var span_message = 'Are you sure you want to Approve? <button type="button" class="btn btn-success" onclick="approve_reject_shortlisted(3, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
            var type = 'info';
            notify(span_message, type, true);
		});

		$('#btn_shortlist_reject').on('click', function(){

			 $('#myModal').modal('show');

	        $('#myModal span').hide();
	        $('.alert > span').show(); // dont include to hide these span
	        $('#myModal .reject_shortlist').show();
		});

		$('#sl_reject').on('click', function(){

	        if ($('#rejectshortlist_remarks').val() == '')
	        {
	            modal_notify($("#myModal"),'Remarks must not be empty!', 'danger');
	        }
	        else
	        {
	            var span_message = 'Are you sure you want to Reject? <button type="button" class="btn btn-success" onclick="approve_reject_shortlisted(4, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
	            var type = 'info';
	            modal_notify($("#myModal"),span_message, type, true);
	        }
	    });

		$('.grow_text').each(function(){
				this.style.height = "35px";
	    		this.style.height = (this.scrollHeight)+"px";
			});

		});
</script>
