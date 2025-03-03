<!-- <script src="<?=base_url();?>assets/js/rfqb/rfq_award.js"></script> -->
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
.sel_type{
width:300px;
text-align:center;
/*padding-left: 5px;
padding-right: 5px;*/
padding: 5px;
}
.c_sel{

text-align: center;


}

table tr td{

border:solid 1px #F2F2F2;
text-align: center;

}
.mg{

margin-top:30px;

}

.test_table{

	min-width: 1000px;

}

.tr_fst{

	width:100px;
	text-align:left;
	padding: 5px;
}


</style>

<?=form_open_multipart('form1', array('name' => 'form1', 'id' => 'frm_award') );?>
<input type="hidden" name="rfq_id" id="rfq_id" value="<?php echo $rfq; ?>">
<input type="hidden" name="user_login" id="user_login" value="<?php echo $user_login; ?>">
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-6">
			<h4>Award - <?php echo 'RFQ/RFB# '.$xz[0]->RFQRFB_ID.' - '.$xz[0]->TITLE;?></h4>
		</div>
		<?php if (isset($for_approval)): ?>	
			<?php if (isset($awarded_view)): ?>	
			<div class="col-md-offset-9">
				<button type="button" class="btn btn-primary btn-sm" onclick="printJS()">Price Comparison</button>
				<span>&nbsp;&nbsp;</span>
				<button type="button" class="btn btn-primary btn-sm" id="btn_save_pod">Save</button>
				<button type="button" class="btn btn-primary btn-sm btn-exit">Close</button>
			</div>
			<?php else: ?>
				<div class="col-md-offset-10">
					<button class="btn btn-primary btn-sm" id="btn_award_approve" onclick ="return false;">Approve</button>
					<button class="btn btn-primary btn-sm" id="btn_award_reject" onclick ="return false;">Reject</button>
					<button type="button" class="btn btn-primary btn-sm btn-exit">Close</button>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="col-md-offset-8">
				<button class="btn btn-primary btn-sm" id="btn_submit_approve" data-rfq = " <?php echo $rfq; ?> " onclick ="return false;" disabled>Submit Award For Approval</button>
				<button class="btn btn-primary btn-sm" id="btn_failed_bid" data-rfq = " <?php echo $rfq; ?> " onclick ="return false;">Failed Bid</button>
				<button type="button" class="btn btn-primary btn-sm btn-exit">Close</button>
				<!-- <button class="btn btn-primary btn-sm" id="btn_close">Close</button> -->
			</div>
		<?php endif; ?>	
	</div>
	<br>
	<!-- TABLE -->
	<div class="row form_container">
		<div class="col-md-12">
			<?Php
				for($j=0;$j<count($line);$j++){
					// $bom_modal_data['line_attachment_id'] = $line[$j]->LINE_ATTACHMENT_ID;
					// $bom_modal_data['bom_lines'] = $line[$j]->BOM_LINES;
					// $bom_modal_data['current_currency_data'] = $xz[0]->ABBREVIATION;
					// $bom_modal_data['user_type'] = $this->session->userdata('user_type');
					// $bom_modal_data['rfqrfb_id'] = $xz[0]->RFQRFB_ID;
					// $bom_modal_data['title'] = $xz[0]->TITLE;
					// $this->load->view('rfqb/bom_modal_compare_view',$line[$j]);
					
					$bom_modal_data['line_attachment_id'] = $line[$j]->LINE_ATTACHMENT_ID;
					$bom_modal_data['line'] = $line[$j];
					$bom_modal_data['current_currency_data'] = $xz[0]->ABBREVIATION;
					$bom_modal_data['user_type'] = $this->session->userdata('user_type');
					$bom_modal_data['rfqrfb_id'] = $xz[0]->RFQRFB_ID;
					$bom_modal_data['title'] = $xz[0]->TITLE;
					// $this->load->view('rfqb/bom_modal_view',$bom_modal_data);
					$this->load->view('rfqb/bom_modal_compare_view',$bom_modal_data);
				}		
			?>
			<div class="panel panel-primary">
				<div class="form-group indent_top indent_left">
					<div class="row">
						<div class="col-md-4">

							Title: 
							<label class="form-label"> <?php 
							echo $xz[0]->TITLE;
							 ?> 

							 </label>
						</div>
						<div class="col-md-4">
							Created By: 
							<label class="form-label">
																
								<?php

									echo $xz[0]->USER_FIRST_NAME . ' ' .  $xz[0]->USER_MIDDLE_NAME .' ' . $xz[0]->USER_LAST_NAME ;

								?>

							</label>
						</div>
						<div class="col-md-3">
							Date Created: 
							<label class="form-label">
						<?php 
							$sDate = new  DateTime($xz[0]->DATECREATED);
							echo $sDate->format('M-d-Y');
							 ?> 


							</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							Type: 
							<label class="form-label"><?php echo $xz[0]->RFQRFB_TYPE_NAME; ?></label>
						</div>
						<div class="col-md-2">
							Currency:
							<label class="form-label"><?php echo $xz[0]->ABBREVIATION; ?></label>
						</div>
						<div class="col-md-4">
							Preferred Delivery Date: 
							<label class="form-label">
							<?php  $date = new DateTime($xz[0]->DELIVERY_DATE);

									echo $date->format('M-d-Y');

							 ?></label>
						</div>
						<div class="col-md-4">
							Submission Deadline Date: 
							<label class="form-label">
								
							<?php  $date = new DateTime($xz[0]->SUBMISSION_DEADLINE);
								echo $date->format('M-d-Y');
							 ?>



							</label>
						</div>
					</div>

				</div>

				<!-- SM VIEW ONLY -->
				<div class="row">
					<div class="form-horizontal">
						<div class="form-group">
							<label for="requestor" class="col-md-2 control-label">Requestor</label>
							<div class="col-md-9">
								<select name="requestor" id="requestor" class="form-control" disabled>
									<option value="it_dept">  <?php echo $xz[0]->REQUESTOR;  ?> </option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Purpose of Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control" disabled>
									<option value="replacement"><?php echo $xz[0]->PURPOSE;  ?></option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_purpose" id="other_purpose" class="form-control" disabled><?php echo $xz[0]->OTHER_PURPOSE;  ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Reason for Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control" disabled>
									<option value="another_reason"><?php echo $xz[0]->REASON;  ?></option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_reason" id="other_reason" class="form-control" row="1" disabled><?php echo $xz[0]->OTHER_REASON;  ?></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_note" class="col-md-2 control-label">Internal Note</label>
							<div class="col-md-9">
								<textarea name="request_note" id="request_note" class="form-control" row="1" readonly><?php echo $xz[0]->INTERNAL_NOTE;  ?></textarea>
							</div>
						</div>
					</div>
				</div>
				<!-- END SM VIEW ONLY -->
				<?=br(1)?>
			</div><!-- end of panel -->

			<!-- start new panel -->
			<div class="panel panel-primary">
			
					<div class="form-group indent_sides indent_top">
						
							
						<?php

						$vendor_list = array();
						$qoute_amount = array();
						$lead_time = array();
						$counter_offer = array();
						$attach_arr = array();
						$qoute_id_arr = array();
						$shortlisted = array();
						$awarded_arr = array();
						$line_arr = array();
						for($i = 0 ; $i < count($part);$i++){

							if (!array_key_exists($part[$i]->VENDOR_ID, $vendor_list))
								$vendor_list[$part[$i]->VENDOR_ID] = $part[$i]->VENDOR_NAME;

								if ($part[$i]->SHORTLISTED == 1)
								{
									$qoute_amount[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->QUOTE_AMOUNT;
									$lead_time[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->LEAD_TIME;
									$counter_offer[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->COUNTER_OFFER;
									$attach_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->ATTACHMENT_PATH;
									$qoute_id_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->RESPONSE_QUOTE_ID;
									$shortlisted[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->SHORTLISTED;
									$awarded_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID] = $part[$i]->AWARDED;
								}
							if (!in_array($part[$i]->LINE_ID, $line_arr))
								$line_arr[] = $part[$i]->LINE_ID;
							
						}
						//Sort Line
						$line_attachment_ids = array();
						foreach($line as $lid){
							$line_attachment_ids[] = $lid->LINE_ATTACHMENT_ID;
						}
						//BOM Sort reverse
						//rsort($line_attachment_ids);
						
							for($l = 0 ; $l < count($line); $l++){
								$line_has_award = false;
								$qoute_arr = array(); // array init
								$all_qoute_amount = array();
								
								foreach ($vendor_list as $key => $value){
									if (array_key_exists($key, $shortlisted)){ // check if vendor id exist or shortlisted
										if (array_key_exists($line_arr[$l], $shortlisted[$key])){ // check if line id exists on shortlisted array
											if ($shortlisted[$key][$line_arr[$l]] == 1){
												$qoute_arr[] = $qoute_amount[$key][$line_arr[$l]]; // get all qoute amount per line 
											}
										}
									}
								}
								$lowest_qoute = (!empty($qoute_arr) ? min($qoute_arr) : ''); // use min to get lowest qoute amount per line
								
								//Latest Version
								//Latest Version of Vendor
								$latest_version = array();
								$count_values = array();
								$max = array();
								$latest_version_price_temp = array();
								foreach($vendor_list as $key => $v){
									foreach($part as $p){
										if($line_arr[$l] == $p->LINE_ID && $v == $p->VENDOR_NAME){
											$latest_version[$key][] = $p->VERSION;
										}
									}
								}
								//Get the total duplicate
								foreach($latest_version as $key => $v){
									$max[$key] = max($latest_version[$key]);
									$count_values[$key] = array_count_values($latest_version[$key]);
								}
								
								foreach($shortlisted as $key => $v){
									if(!array_key_exists($line_arr[$l], $v)){
										unset($count_values[$key]);
									}
								}
								
								//Get the highest duplicate
								$duplicates_total = array();
								foreach($count_values as $key => $val){
									$duplicates_total[] = $val[$max[$key]];
								}
								$max_dup = max($duplicates_total);
								
								
								//echo "<pre>";
								//print_r($duplicates_total);
								//echo "</pre>";
								
								echo '<div class = "panel panel-primary"> <div class = "panel-heading">' ?>  <?php echo $line[$l]->CATEGORY_NAME .'-'. $line[$l]->DESCRIPTION;  ?>   
								<?php echo '
									<div style="float:right; margin:0 auto; padding:0;">
										<input type="button" data-toggle="modal" data-target="#view_bom_modal_'.$line_attachment_ids[$l].'" class="btn btn-default btn-sm" style="display:'.($line[$l]->BOM_ROWS > 0 ? 'inline-block' : 'none').';" value="Compare BOM"></input>
									</div>
								</div>
								
								<div class="table-responsive">
								<table class = "test_table" id="po_details_table"> 
								<form id = "id'.$l.'">

								<thead></thead>

								<tbody>
								<tr>
								<td></td>';
								
								foreach($max as $key => $v){
									foreach($part as $key2 => $p){
										if( $key == $p->VENDOR_ID && $v == $p->VERSION 
											&& $p->SHORTLISTED == 1 
											&& isset($shortlisted[$p->VENDOR_ID ][$line_arr[$l]]) 
											&& $shortlisted[$p->VENDOR_ID ][$line_arr[$l]] == 1){
											echo '<td>Version: ' . $p->VERSION . ' - '. $p->FORMATTED_DATE .'</td>';
											break;
										}
									}
								}
								
								echo '</tr>
								<tr>
								<td class = "tr_fst" style="width:20%">Vendor<br></td>
								';

								 ?>


								<?php
								foreach ($vendor_list as $key => $value)
								{
									if (array_key_exists($key, $shortlisted)){
										if (array_key_exists($line_arr[$l], $shortlisted[$key])){
											if ($shortlisted[$key][$line_arr[$l]] == 1){
												echo '<td class = "sel_type" style="min-width:300px"><span style="padding: 5px;">' . $value . '</span>
														<select id = "'.$qoute_id_arr[$key][$line_arr[$l]].'" name="'.$line[$l]->CATEGORY_ID.'" class="form-control c_sel show_list">
														<option value="0">All</option>
														<option value="1">Lowest</option>
														<option value="2" selected>Latest</option>
														</select>
														</td>';
											}
											else{
												echo '<td style="min-width:300px"></td>';
											}
										}else{
											//echo '<td style="min-width:300px"></td>';
										}
									}else{
										//echo '<td style="min-width:300px"></td>';
									}
								}  
								?>

								<?php 

								echo '</tr>';
								echo '<tr>';
								if (isset($for_approval)){
									echo '<td></td>';
								}else{
									echo '<td class = "tr_fst">No Award <br>
									<input type = "checkbox" style="margin-left:5%;" onclick="chk_noaward(this)" value="'.$l.'">
									</td>'; 
								}
		
								?>


								<?php 
								$v = 1;
								/*foreach ($vendor_list as $key => $value){

									if (isset($for_approval))
									{
										if (array_key_exists($key, $awarded_arr)){
											if (array_key_exists($line_arr[$l], $awarded_arr[$key])){
												if ($awarded_arr[$key][$line_arr[$l]] == 1){

													echo '<td> <input type = "checkbox" name = "rad_option'. $v .'" value="'.$qoute_id_arr[$key][$line_arr[$l]].'" checked disabled></td>';
													$line_has_award = true;
													$v++;
												}
												else{
													echo '<td></td>';
												}
											}else{
												//echo '<td></td>';
											}
										}else{
											//echo '<td></td>';
										}
									}
									else
									{
										if (array_key_exists($key, $shortlisted)){
											if (array_key_exists($line_arr[$l], $shortlisted[$key])){
												if ($shortlisted[$key][$line_arr[$l]] == 1){
													echo '<td> <input type = "checkbox" name = "rad_option'. $v .'" value="'.$qoute_id_arr[$key][$line_arr[$l]].'"></td>';
													$v++;
												}else{
													echo '<td></td>';
												}
											}else{
												//echo '<td></td>';
											}
										}else{
											//echo '<td></td>';
										}
									}
									
								}*/


								?>
								<?php

								echo '</tr>';
								//echo '<tr>';
								//echo '<td class = "tr_fst">Price </td>';
								
								?>

								<?php
				
								//Get the Latest Price
								$latest_version_price = array();
								//Get the Latest Delivery Time
								$latest_version_lead_time = array();
								//Get the Latest Counter Offer
								$latest_version_counter_offer = array();
								//Get the Latest Attachment
								$latest_version_attachment = array();
								//Get the Latest Response Quote ID
								$latest_version_response_quote_id = array();
								//Get the Latest Awarded
								$latest_version_awarded = array();
								//Get the Latest Awarded
								$latest_version_shortlisted = array();
								foreach($vendor_list as $key => $v){
									foreach($part as $p){
										if($line_arr[$l] == $p->LINE_ID 
											&& $v == $p->VENDOR_NAME 
											&& $max[$p->VENDOR_ID] == $p->VERSION
											&& isset($shortlisted[$p->VENDOR_ID ][$line_arr[$l]]) 
											&& $shortlisted[$p->VENDOR_ID ][$line_arr[$l]] == 1){
											
											$latest_version_price[$key][] 				= $p->QUOTE_AMOUNT;
											$latest_version_lead_time[$key][] 			= $p->LEAD_TIME;
											$latest_version_counter_offer[$key][]		= $p->COUNTER_OFFER;
											$latest_version_attachment[$key][] 			= $p->ATTACHMENT_PATH;
											$latest_version_response_quote_id[$key][] 	= $p->RESPONSE_QUOTE_ID;
											$latest_version_awarded[$key][] 			= $p->AWARDED;
											$latest_version_shortlisted[$key][] 		= $p->SHORTLISTED;
											
										}
									}
								}
								
								//Debug
								////$line_arr[$l]
								//echo "<pre>";
								//print_r($latest_version_shortlisted);
								//echo "</pre>";
								
								//Vendor Rev Start
								$bgcolor = '';
								$rad_v = 1;
								for($ix = 0; $ix < $max_dup; $ix++){
								
									if(($ix + 1) % 2 == 0){
										$bgcolor = '';//style="background-color: #e6e5e5; /"
									}else{
										$bgcolor = '';
									}

									//Divider
									echo '<tr style="background-color:#d9edf7;"><td><br></td>';
									foreach($latest_version_price as $lvp){
										echo '<td><br></td>';
									}
									echo '</tr>';
									//Price
									echo '<tr '. $bgcolor.'>';
									echo '<td class = "tr_fst">Price </td>';
									foreach($latest_version_price as $k => $lvp){
										if(isset($lvp[$ix])){
											echo '<td class="'. $line_arr[$l].'">';
											
											if (isset($for_approval)){
												if($latest_version_awarded[$k][$ix] == 1){
													echo '<input type = "checkbox" id="rad_option'. $rad_v .'" name = "rad_option'. $l .'" value="'.$latest_version_response_quote_id[$k][$ix].'" checked disabled> ';
													$line_has_award = true;
												}
												else{
													echo '<input type = "checkbox" id="rad_option'. $rad_v .'" name = "rad_option'. $l .'" value="'.$latest_version_response_quote_id[$k][$ix].'" disabled> ';
												}
											}else{
												echo '<input type = "checkbox" id="rad_option'. $rad_v .'" name = "rad_option'. $l .'" value="'.$latest_version_response_quote_id[$k][$ix].'"> ';
											}
											$rad_v++;
											echo number_format((isset($lvp[$ix]) ? $lvp[$ix] : 0), 2, '.', ',') . '</td>';
										}else{
											echo '<td class="'. $line_arr[$l].'"></td>';
										}
									}

									echo '</tr>';
									
									//Delivery Time
									echo '<tr '. $bgcolor.'>';
									echo '<td class = "tr_fst">Delivery Lead Time </td>';
									foreach($latest_version_lead_time as $lvlt){
										if(!empty($lvlt[$ix])){
											echo '<td>' . $lvlt[$ix] . '</td>';
										}else{
											echo '<td></td>';
										}
									}
									echo '</tr>';
									
									//Counter Offer
									echo '<tr '. $bgcolor.'>';
									echo '<td class = "tr_fst">Counter Offer</td>';
									foreach($latest_version_counter_offer as $lvco){
										if(!empty($lvco[$ix])){
											echo '<td>' . $lvco[$ix] . '</td>';
										}else{
											echo '<td></td>';
										}
									}
									echo '</tr>';
									
									//Attachments
									echo '<tr '. $bgcolor.'>';
									echo '<td class = "tr_fst">Attachments</td>';
									foreach($latest_version_attachment as $lva){
										if(!empty($lva[$ix])){
											echo '<td>' . '<a href="#" onclick="load_attachment(\''.$attach_arr[$key][$line_arr[$l]].'\')">Attachment</a>'. '</td>';
										}else if(isset($lva[$ix])){
											echo '<td>None</td>';
										}else{
											echo '<td></td>';
										}
									}
									echo '</tr>';
									
								}
								//Divider
								echo '<tr style="background-color:#d9edf7;"><td><br></td>';
								foreach($latest_version_price as $lvp){
									echo '<td><br></td>';
								}
								echo '</tr>';
								?>
								<script type="text/javascript">
								//Price highlighting
								var nums = [];
								var tds = [];
								$('.<?php echo $line_arr[$l]; ?>').each(function(i, obj) {
									if(!isNaN(Number.parseFloat($(this).text()))){
										nums.push(Number($(this).text().replace(/[^0-9\.-]+/g,""))); //convert to number
										tds.push($(this));
									}
									//$(this).css({"background-color" :"yellow"});
								});
								var val = Math.min.apply(Math,nums);
								//console.log(nums);
								//console.log(val);
							    var indexes = [], i = -1;
								while ((i = nums.indexOf(val, i+1)) != -1){
									indexes.push(i);
								}
								//console.log(indexes);
								for(var x = 0; x < indexes.length; x++){
									tds[indexes[x]].css({'background-color' : 'yellow'});
								}
								</script>
								
								<?php
								/*foreach($part as $p){
									
									if(array_key_exists($p->VENDOR_ID, $max) && $p->VERSION == $max[$p->VENDOR_ID]){
										echo '<tr>';
										echo '<td class = "tr_fst">Price </td>';
											
											foreach($vendor_list as $key => $vl){
												foreach($latest_version[$key] as $lv){
													if( max($latest_version[$key] ) == $max[$key]){
														echo '<td>'.$p->VENDOR_ID.'</td>';
													}else{
													}
												}
											}
									}
								}
								foreach ($vendor_list as $key => $value){
									if (array_key_exists($key, $shortlisted)){
										if (array_key_exists($line_arr[$l], $shortlisted[$key])){
											if ($shortlisted[$key][$line_arr[$l]] == 1){
												if ($lowest_qoute == $qoute_amount[$key][$line_arr[$l]]){
													echo '<td bgcolor="yellow">'.number_format((isset($qoute_amount[$key][$line_arr[$l]]) ? $qoute_amount[$key][$line_arr[$l]] : 0), 2, '.', ',').'</td>';
												}else{
													//echo '<td>'.number_format($qoute_amount[$key][$line_arr[$l]],2).'</td>';
													echo '<td>'.number_format((isset($qoute_amount[$key][$line_arr[$l]]) ? $qoute_amount[$key][$line_arr[$l]] : 0), 2, '.', ',').'</td>';
												}	
											}else{
												echo '<td></td>';
											}
										}else{
											echo '<td></td>';
										}
									}else{
										echo '<td></td>';
									}
								}

								echo '</tr>';
								echo '<tr>';
								echo '<td class = "tr_fst">Delivery Lead Time </td>';
                                
								foreach ($vendor_list as $key => $value){
                                
										if (array_key_exists($key, $shortlisted)){
											if (array_key_exists($line_arr[$l], $shortlisted[$key])){
												if ($shortlisted[$key][$line_arr[$l]] == 1)
													echo '<td>'.$lead_time[$key][$line_arr[$l]].'</td>';
												else
													echo '<td></td>';
											}else
												echo '<td></td>';
										}else
											echo '<td></td>';
										
									}
                                
								echo '</tr>';
								echo '<tr>';
								echo '<td class = "tr_fst">Counter Offer </td>';
                                
								foreach ($vendor_list as $key => $value){
                                
										if (array_key_exists($key, $shortlisted)){
											if (array_key_exists($line_arr[$l], $shortlisted[$key])){
												if ($shortlisted[$key][$line_arr[$l]] == 1)
													echo '<td><textarea class="grow_text" style="max-height:150px; min-height: 33px !important; width: 272px; min-width: 272px !important;  max-width: 400px !important;" disabled>'.$counter_offer[$key][$line_arr[$l]].'</textarea></td>';
												else
													echo '<td></td>';
											}else
												echo '<td></td>';
										}else
											echo '<td></td>';
										
									}
                                
								echo '</tr>';
								echo '<tr>';
								echo '<td class = "tr_fst">Attachments </td>';

								foreach ($vendor_list as $key => $value){

										if (array_key_exists($key, $shortlisted)){
											if (array_key_exists($line_arr[$l], $shortlisted[$key])){
												if ($shortlisted[$key][$line_arr[$l]] == 1)
												{
													if (!empty($attach_arr[$key][$line_arr[$l]]))
														$attach = '<a href="#" onclick="load_attachment(\''.$attach_arr[$key][$line_arr[$l]].'\')">Attachment</a>';
													else
														$attach = 'None';			
												}
												else
													$attach = '';
											}
											else
												$attach = '';
										}
										else
											$attach = '';

										echo '<td>'.$attach.'</td>';
									}

								echo '</tr>';*/
								
								//Vendor Rev End
								echo '<tr style="border:none;">';
								echo '<td class = "tr_fst" style="border:none;"></td>';
								//$test =array();
								foreach ($vendor_list as $key => $value){
									if (array_key_exists($key, $shortlisted)){
										if (array_key_exists($line_arr[$l], $shortlisted[$key])){
											if ($shortlisted[$key][$line_arr[$l]] == 1)
											{
												echo '<td style="vertical-align:top; border:none;"><div id="list_'.$qoute_id_arr[$key][$line_arr[$l]].'_'.$line[$l]->CATEGORY_ID.'" style="display:none;"></div></td>';
											}
											else{
												echo '<td style="border:none;"></td>';
											}
										}
										else{
											//echo '<td></td>';
										}
									}
									else{
										//echo '<td></td>';
									}
								}
								for($ix = 0; $ix < $max_dup; $ix++){
									foreach($latest_version_response_quote_id as $vrq_k => $vrq){
										if(isset($vrq[$ix])){
											//$test[] = $vrq[$ix] .'_'.$line[$l]->CATEGORY_ID;
											echo '<td style="vertical-align:top; border:none;"><div id="list_'.$vrq[$ix] .'_'.$line[$l]->CATEGORY_ID.'" style="display:none;"></div></td>';
										}else{
											echo '<td style="border:none;"></td>';
										}
									}
								}
								echo '</tr>';
								
								//echo "<pre>";
								//print_r($latest_version_response_quote_id);
								//echo "</pre>";
								//echo "<pre>";
								//print_r($latest_version_awarded);
								//echo "</pre>";
								if (isset($awarded_view) && (isset($line_has_award) && $line_has_award == true))
								{
									$total_po_details = 1;
									foreach($po_details as $po_detail){

										if($po_detail->LINE_ID == $line_arr[$l]){
											$total_po_details++;
										}
									}

									echo '<tr class="tr_line_id" id="tr_line_id_'. $line_arr[$l] .'">';
									echo '<td class = "tr_fst" style="padding-bottom: 50px; position: relative;">PO Details   <input type="hidden" name="line_id[]" value="'.$line_arr[$l].'"><input type="hidden" id="pod_count_'.$line_arr[$l].'" name="pod_count_'.$line_arr[$l].'" value="' . $total_po_details . '"><input type="hidden" id="pod_count_orig_'.$line_arr[$l].'" name="pod_count_orig_'.$line_arr[$l].'" value="' . $total_po_details . '"><button type="button" class="btn btn-primary btn_add" value="'.$line_arr[$l].'" style="position: absolute; bottom: 27px; left: 51px;">+</button></td>';
									echo '<td colspan="50">'; //echo '<td colspan="'.count($vendor_list//).'">';
									echo '<table class="table table-bordered" id="tbl_pod_'.$line_arr[$l].'">';
									echo '<thead>';
									echo '<tr class="bg-primary">';
									echo '<th>Vendor Name</th>';
									echo '<th>Company/<br>Operating Unit</th>';
									echo '<th>PO Number</th>';
									echo '<th>Negotiated Amount</th>';
									echo '<th>Quantity</th>';
									echo '<th>Date Updated</th>';
									echo '<th>Updated by</th>';
									echo '<th></th>';
									echo '</tr>';
									echo '</thead>';
									echo '<tbody>';
									
									$count = 1;
									foreach($po_details as $po_detail){

										if($po_detail->LINE_ID == $line_arr[$l]){

											echo '<tr id="tr_pod_'.$line_arr[$l].'_' . $count .'" class="cls_tr_pod_'.$line_arr[$l].'">';
											echo '<td><div><select class="form-control" name="slt_'. $line_arr[$l] .'_'.$count.'" id="slt_'. $line_arr[$l] .'_'.$count.'"disabled>';

											$c=0;
											for($c = 0 ;$c < count($ven_list); $c++){

												$sel = '';

												if($ven_list[$c]->VENDOR_ID == $po_detail->VENDOR_ID){
													$sel = "selected";
												}
												foreach($latest_version_awarded as $va_k => $va){
													if($va_k == $ven_list[$c]->VENDOR_ID){
														foreach($va as $va_val){
															if($va_val == 1){
																echo '<option value="'. $ven_list[$c]->VENDOR_ID .'" '. $sel .'>' . $ven_list[$c]->VENDOR_NAME .'</option>';
																break;
															}
														}
													}
												} 
											}


											echo '</select></div></td>';

											echo '<td><input type="text" id="cou_'.$line_arr[$l].'_' . $count .'" name="cou_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->COMPANY .'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'" readonly></td>';
											echo '<td><div><input type="text" id="pon_'.$line_arr[$l].'_' . $count .'" name="pon_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->PO_NUMBER .'" placeholder="" class="form-control input-sm field-required cls_pod'.$line_arr[$l].'"readonly ></div></td>';
											echo '<td><input type="text" id="negam_'.$line_arr[$l].'_' . $count .'" name="negam_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->NEGO_AMOUNT .'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'"readonly></td>';
											echo '<td><input type="text" id="quantity_'.$line_arr[$l].'_' . $count .'" name="quantity_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->QUANTITY .'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'"readonly></td>';
											echo '<td><input type="text" id="date_upd_'.$line_arr[$l].'_' . $count .'" name="date_upd_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->FORMATTED_DATE  .'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'" readonly></td>';
											echo '<td><input type="text" id="update_by_'.$line_arr[$l].'_' . $count .'" name="update_by_'.$line_arr[$l].'_' . $count .'" value="'. $po_detail->USER_FIRST_NAME . ( (empty($pos_detail->USER_MIDDLE_NAME)) ? ""  : " " . $pos_detail->USER_MIDDLE_NAME) . ( (empty($pos_detail->USER_LAST_NAME)) ? ""  : " " . $pos_detail->USER_LAST_NAME) .'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'" readonly></td>';
											echo '<td><button type="button" class="btn btn-default btn-xs cls_del_pod" value="'.$line_arr[$l]. ' ' . $count . '"><span class="glyphicon glyphicon-trash"></span></button></td>';
											echo '</tr>';
											$count++;
										}
									}
										
									echo '<tr id="tr_pod_'.$line_arr[$l].'_' . $count .'" class="cls_tr_pod_'.$line_arr[$l].'">';
									echo '<td><div><select class="form-control" name="slt_'. $line_arr[$l] .'_'.$count.'" id="slt_'. $line_arr[$l] .'_'.$count.'">';

									$c=0;
									for($c = 0 ;$c < count($ven_list); $c++){
										
										//Check vendors who are awarded
										foreach($latest_version_awarded as $va_k => $va){
											//Check if vendor id match
											if($va_k == $ven_list[$c]->VENDOR_ID){
												//Check the quote if awarded
												foreach($va as $va_val){
													//If awarded, print vendor and break the loop
													if($va_val == 1){
														echo '<option value="'. $ven_list[$c]->VENDOR_ID .'">' . $ven_list[$c]->VENDOR_NAME .'</option>';
														break;
													}
												}
											}
										} 
									}


									echo '</select></div></td>';
									echo '<td><input type="text" id="cou_'.$line_arr[$l].'_' . $count .'" name="cou_'.$line_arr[$l].'_' . $count .'" value="" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'"></td>';
									echo '<td><div><input type="text" id="pon_'.$line_arr[$l].'_' . $count .'" name="pon_'.$line_arr[$l].'_' . $count .'" value="" placeholder="" class="form-control input-sm field-required cls_pod'.$line_arr[$l].'"></div></td>';
									echo '<td><input type="text" id="negam_'.$line_arr[$l].'_' . $count .'" name="negam_'.$line_arr[$l].'_' . $count .'" value="" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'"></td>';
									echo '<td><input type="text" id="quantity_'.$line_arr[$l].'_' . $count .'" name="quantity_'.$line_arr[$l].'_' . $count .'" value="" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'"></td>';
									echo '<td><input type="text" id="date_upd_'.$line_arr[$l].'_' . $count .'" name="date_upd_'.$line_arr[$l].'_' . $count .'" value="'.$date_today.'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'" readonly></td>';
									echo '<td><input type="text" id="update_by_'.$line_arr[$l].'_' . $count .'" name="update_by_'.$line_arr[$l].'_' . $count .'" value="'.$user_login.'" placeholder="" class="form-control input-sm cls_pod'.$line_arr[$l].'" readonly></td>';
									echo '<td><button type="button" class="btn btn-default btn-xs cls_del_pod" value="'.$line_arr[$l]. ' ' . $count . '"><span class="glyphicon glyphicon-trash"></span></button></td>';
									echo '</tr>';
									echo '</tbody>';
									echo '</table>';
									echo '</td>';
									echo '</tr>';
								}
								?>


								<?php echo '

								</tbody>
								 </table>
								 </div>
								 </div>
								</form>

								<div class = "mg"></div>
								 '
								 ;

							}

						?>

				
				</div>
			</div>

		</div>
	</div>
	<!-- END TABLE -->
	
</div>

<?=form_close();?>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
        	<div class="modal-header">					
				<span class="reject_award" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Reject</h4>
				</span>
				<span class="failedbid_award" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Failed Bid</h4>
				</span>		
				<span class="document_preview" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Preview</h4>
					<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
					<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
				</span>			
			</div>
             <div class="modal-content">
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal">
                       		<span class="reject_award" style="display:none;">
								<textarea class="form-control " placeholder="Please specify reason here" id="award_remarks" style="height: 100px"></textarea>
							</span>
							<span class="failedbid_award" style="display:none;">
								<textarea class="form-control " placeholder="Please specify reason here" id="failedbid_remarks" style="height: 100px"></textarea>
							</span>
							<span class="document_preview" style="display:none;">
								<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
							</span>
                       </div>
                  </div>
                  <div class="modal-footer">
                    <span class="reject_award" style="display:none;">
						<button type="button" class="btn btn-primary" id="award_reject">Ok</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</span>
					<span class="failedbid_award" style="display:none;">
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

<script type="text/javascript">

	function specs_show(row)
	{
		return 1;
	} 

	function save_award(action_type, btn)// type 1 = submt , 2 = failed bid
	{

		let ins = '';
		if(action_type == 1){
			$('#frm_award input[type=checkbox]').each(function(){
				if(this.checked){
					ins = ins + this.value + "|";
				}		
			});

		}

		loading($(btn), 'in_progress'); // loading
		var ajax_type = 'POST';
	    var url = BASE_URL + "rfqb/rfq_rfb_award/save_award/";
	    var post_params = $('#frm_award').serialize();
	    post_params += "&action=" + action_type + "&remarks=" + $('#failedbid_remarks').val() + "&ins=" +ins;


	    var success_function = function(responseText)
	    {
	        // console.log(responseText);
	        // return;
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

	function chk_noaward(chk)
	{
		if ($(chk).is(':checked')) // if checked disabled isang helera
		{
			$('input[name=rad_option'+chk.value).prop('disabled', true);
			$('input[name=rad_option'+chk.value).prop('checked', false);
		}
		else
			$('input[name=rad_option'+chk.value).prop('disabled', false);

		if ($("input[type=checkbox]:checked").length > 0)
			$('#btn_submit_approve').prop('disabled', false);
		else
			$('#btn_submit_approve').prop('disabled', true);
	}

	function approve_reject_awarded(action_type, btn) // action_type 3 = approve, 4 = reject
	{
		loading($(btn), 'in_progress'); // loading
		var ajax_type = 'POST';
	    var url = BASE_URL + "rfqb/rfq_rfb_award/approve_reject_awarded/";
	    var post_params = $('#frm_award').serialize();
	    post_params += "&action=" + action_type + "&remarks=" + $('#award_remarks').val();

	    var success_function = function(responseText)
	    {
	       // console.log(responseText);

	       	if (action_type == 4)
            	$('#myModal').modal('hide');
	       
	       	var span_message = 'Success!';
           	var type = 'success';
            notify(span_message, type);

            $('#btn_award_approve').prop('disabled', true);
            $('#btn_award_reject').prop('disabled', true);
            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
	    };

	    ajax_request(ajax_type, url, post_params, success_function);
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

	/*function generate_pdf()
	{
		document.form1.action = "<?=base_url();?>index.php/rfqb/rfq_rfb_award/generate_pdf/" ;				
		document.form1.target = "_self";
		document.form1.submit();
	}*/

	function printJS()
	{
		var rfq = $('#rfq_id').val();
		window.open(BASE_URL+'rfqb/rfq_rfb_award/generate_pdf/' + rfq);
	}

	function reset_ids(class_name, update_count, id_num = null, div_id = null, button_delete_id = null)
	{
	    var count = 0
	    if (div_id != null)
	        div_id = '#'+div_id+id_num;
	    else
	        div_id = '';

	    // console.log(div_id+' .'+class_name);
	    $(div_id+' .'+class_name).each(function(i) {

	        var id = $(this).attr('id');
	        var name = $(this).attr('name');
	       
	        if (id_num != null)
	        {
	            id = id.replace(/[0-9]+(?!.*[0-9])/, id_num);
	            if (name)
	                name = name.replace(/[0-9]+(?!.*[0-9])/, id_num);
	        }
	        else
	        {
	            id = id.replace(/[0-9]+(?!.*[0-9])/, i+1);
	            if (name)
	                name = name.replace(/[0-9]+(?!.*[0-9])/, i+1);
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

	function save_po_details(btn)
	{
		loading($(btn), 'in_progress'); // loading
		var ajax_type = 'POST';
	    var url = BASE_URL + "rfqb/rfq_rfb_award/save_po_details/";
	    var post_params = $('#frm_award').serialize();

	    var success_function = function(responseText)
	    {
	       var rfq = $('#rfq_id').val();

	       
	       	var span_message = 'Success!';
           	var type = 'success';
            notify(span_message, type);
            goto_refresh(rfq);
            // var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
            // setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
	    };

	    ajax_request(ajax_type, url, post_params, success_function);
	}

	$(document).ready(function(){
		$('.show_list').off().on('change', function()
		{
			var qoute_id = this.id;
			var line_id = this.name;
			var order_list = this.value;
			var ajax_type = 'POST';
		    var url = BASE_URL + "rfqb/rfq_rfb_award/get_version_list/";
		    var post_params = "&qoute_id=" + qoute_id + "&order_list=" + order_list ;

		    var success_function = function(responseText)
		    {
		       // console.log('#list_'+qoute_id);
		       if (order_list != 2)
		       {
		       		$('#list_'+qoute_id+'_'+line_id).slideUp('fast');
			    	$('#list_'+qoute_id+'_'+line_id).html(responseText);
			       	$('#list_'+qoute_id+'_'+line_id).slideDown('slow');

			       	$('.grow_text').each(function(){
						this.style.height = "35px";
			    		this.style.height = (this.scrollHeight)+"px";
					});
		       }
		       else
		       	$('#list_'+qoute_id+'_'+line_id).slideUp('slow');
		       
		    };

		    ajax_request(ajax_type, url, post_params, success_function);
		});

		$("input[type=checkbox]").on('click', function(){
			
			if ($("input[type=checkbox]:checked").length > 0)
				$('#btn_submit_approve').prop('disabled', false);
			else
				$('#btn_submit_approve').prop('disabled', true);
		});

		$('#btn_submit_approve').on('click', function(){
			var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="save_award(1, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
            var type = 'info';
            notify(span_message, type, true);
		});

		$('#btn_failed_bid').on('click', function(){
			$('#myModal').modal('show');

	        $('#myModal span').hide();
	        $('.alert > span').show(); // dont include to hide these span
	        $('#myModal .failedbid_award').show();
		});

		$('#m_failedbid').on('click', function(){

	        if ($('#failedbid_remarks').val() == '')
	        {
	            modal_notify($("#myModal"),'Remarks must not be empty!', 'danger');
	        }
	        else
	        {
	            var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="save_award(2, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
	            var type = 'info';
	            modal_notify($("#myModal"),span_message, type, true);
	        }
       	});

		$('#btn_award_approve').on('click', function(){
			var span_message = 'Are you sure you want to Approve? <button type="button" class="btn btn-success" onclick="approve_reject_awarded(3, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
            var type = 'info';
            notify(span_message, type, true);
		});

		$('#btn_award_reject').on('click', function(){
			$('#myModal').modal('show');

	        $('#myModal span').hide();
	        $('.alert > span').show(); // dont include to hide these span
	        $('#myModal .reject_award').show();
		});

		$('#award_reject').on('click', function(){		
			if ($('#award_remarks').val() == '')
	        {
	            modal_notify($("#myModal"),'Remarks must not be empty!', 'danger');
	        }
	        else
	        {		        
				var span_message = 'Are you sure you want to Reject? <button type="button" class="btn btn-success" onclick="approve_reject_awarded(4, this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
	            var type = 'info';
	            modal_notify($("#myModal"),span_message, type, true);
	        }
		});

		$('.grow_text').each(function(){

			this.style.height = "35px";
    		this.style.height = (this.scrollHeight)+"px";
		});

		$('.btn_add').off().on('click', function(){
			var line_id 		= this.value;
			var pod_template 	= $('#tr_pod_'+line_id+'_1');
			var count 			= $('#pod_count_'+line_id).val();
			var pod_template_clone = pod_template.clone();
			count++;
			$('#tbl_pod_'+line_id+' > tbody:last-child').append(pod_template_clone.attr({'id':'tr_pod_'+line_id+'_'+count})).find('#tr_pod_'+line_id+'_'+count+' :input:not(:button)').val('').removeAttr('readonly');
			reset_ids('cls_pod'+line_id, 'pod_count_'+line_id, count, 'tr_pod_'+line_id+'_');
			$('#pod_count_'+line_id).val(count);
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

	        today = mm+'-'+dd+'-'+yyyy;
	        $('#date_upd_'+line_id+'_'+count).val(today).attr('readonly','readonly');
	        $('#update_by_'+line_id+'_'+count).val($('#user_login').val()).attr('readonly','readonly');


			var pod_length = $(".cls_pod"  + line_id).length;
			var pod_counts = [];
			for(i = 0; i <  pod_length; i++){
				//console.log((pod_counts) + " === " + pod_counts);
				if(pod_counts.length == 0){
					var temp = $(".cls_pod"  + line_id).eq(i).attr("id").split("_");
					pod_counts.push(temp[temp.length - 1]);
				}else{
					//console.log("pod count length " + pod_counts.length);
					var temp = $(".cls_pod" + line_id).eq(i).attr("id").split("_");
					var have_duplicate = false;
					for(j = 0; j < pod_counts.length; j++){
						//console.log("INNER : " + (pod_counts[j] == temp[temp.length - 1]));
						if(pod_counts[j] == temp[temp.length - 1]){
							have_duplicate = true;
							break;
						}
					}
					if(!have_duplicate){
						pod_counts.push(temp[temp.length - 1]);
					}
				}
			}

			for(i = 0; i < pod_counts.length; i++){
				$("#tr_pod_" + line_id + "_" + pod_counts[i] + " .cls_del_pod").val(line_id + " " + pod_counts[i]);
				$("#tr_pod_" + line_id + "_" + pod_counts[i] + " td:first-child > div select").attr("id", "slt_"  + line_id + "_" + pod_counts[i]);
				$("#tr_pod_" + line_id + "_" + pod_counts[i] + " td:first-child > div select").attr("name", "slt_"  + line_id + "_" + pod_counts[i]);
				
				//console.log($("#tr_pod_" + line_id + "_" + pod_counts[i] + " td:first-child > div select"));
			}
			
			//console.log("LAST " + $("#slt_"  + line_id + "_" + pod_counts.length));
			$("#slt_"  + line_id + "_" + pod_counts.length).removeAttr('disabled');
			$("#slt_"  + line_id + "_" + pod_counts.length + ':first-child').val($("#slt_"+ line_id + "_" + pod_counts.length +" > option:first-child").val());
			//console.log("POD COUNTS: " + pod_counts);
		});
		
	

		$('table').off().on('click', '.cls_del_pod', function(){
			var value = this.value.split(" ");
			var line_id = value[0];
			var item_no = value[1];
			var count 	= $('#pod_count_' + line_id).val();
			var orig 	= $('#pod_count_orig_' + line_id).val();

			//console.log('Item no: ' + item_no);
			//console.log('Count: ' + count);
			//console.log('Orig: ' + orig);
			//console.log('Condition 1: ' + (item_no > count || item_no < count) );
			//console.log('Condition 2: ' + (item_no != orig) );
			//console.log((item_no >= count || item_no <= count && count != 1));
			var pon = $("#pon_" + line_id + "_" + item_no).attr('readonly');
			if ((item_no > count || item_no < count) )
			{

				$(this).closest('tr').remove(); 
				reset_ids('cls_tr_pod_'+line_id,'pod_count_'+line_id);

				//update ids of child
	            for (var i = 1; i <= count; i++)
	            {
	                reset_ids('cls_pod'+line_id, 'pod_count_'+line_id, i, 'tr_pod_'+line_id+'_');
	            } 

	            //For button delete
	            var pod_length = $(".cls_pod" + line_id).length;
				var pod_counts = [];
				for(i = 0; i <  pod_length; i++){
					//console.log((pod_counts) + " === " + pod_counts);
					if(pod_counts.length == 0){
						var temp = $(".cls_pod" + line_id).eq(i).attr("id").split("_");
						pod_counts.push(temp[temp.length - 1]);
					}else{
						//console.log("pod count length " + pod_counts.length);
						var temp = $(".cls_pod" + line_id).eq(i).attr("id").split("_");
						var have_duplicate = false;
						for(j = 0; j < pod_counts.length; j++){
							//console.log("INNER : " + (pod_counts[j] == temp[temp.length - 1]));
							if(pod_counts[j] == temp[temp.length - 1]){
								have_duplicate = true;
								break;
							}
						}
						if(!have_duplicate){
							pod_counts.push(temp[temp.length - 1]);
						}
					}
				}

				for(i = 0; i < pod_counts.length; i++){
					$("#tr_pod_" + line_id + "_" + pod_counts[i] + " .cls_del_pod").val(line_id + " " + pod_counts[i]);
				}

			}
		});

		$('#btn_save_pod').on('click', function(){
			if (!validateForm())
	        {
		          //pon_2281_4
		          //tbl_pod_2281

				  var tr_line_length = $(".tr_line_id").length;
				  var line_ids = [];
				  var counts = [];
				  
				  for(var i=0; i < tr_line_length; i++){
				      line_ids.push($(".tr_line_id").eq(i).attr("id").split("_")[3]);
				      counts.push($('#pod_count_' + line_ids[i]).val());
				  }	

				  var no_error = false;
				  for(var i=0; i < line_ids.length; i++){
				  		var pon = $("#pon_" + line_ids[i] + "_" + counts[i]).val().trim();
						$("#pon_" + line_ids[i] + "_" + counts[i]).closest(":has(div)").find('div').removeClass("has-error");
				  		
				  		if(pon){
				  			no_error = true;
				  		}else{
				  			no_error = false;
				  		}
				  }

				  if(no_error){
					  var span_message = 'Please fill up all fields!';
			          var type = 'danger';
			          notify(span_message, type);
			          return;
		          }
	        }

			var span_message = 'Are you sure you want to Save? <button type="button" class="btn btn-success" onclick="save_po_details(this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" >No</button>';
            var type = 'info';
            notify(span_message, type, true);
		});
	});

function goto_refresh(rid)
{
    var action_path = BASE_URL + 'rfqb/rfq_rfb_award/display_awarded/'+rid;
    $main_container.html('').load(cache.set('refresh_path', action_path));
}

</script>