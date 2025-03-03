<style>
	.sort_columns:hover,
	.user_mail {
		cursor: pointer;
	}
	.table {
		table-layout: fixed;
	}
	.capitalize {
    text-transform: capitalize;
}

#messagest tr td{
	word-break: break-all;
}

	.unselectable {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}




</style>

<?php
  if(!empty($status_id)){
    echo '<input type="hidden" id="status_id" value="'.$status_id.'">';
  }else{
    echo '<input type="hidden" id="status_id" value="0">';
  }

  if(!empty($upload_complete)){
    echo '<input type="hidden" id="upload_complete" value="'.$upload_complete.'">';
  }else{
    echo '<input type="hidden" id="upload_complete" value="0">';
  }
 ?>


<!-- Video Modal -->
<div class="modal fade" id="video_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

			<div class="modal-header">
				<h3 class="modal-title">
					<span id="model_video_title">Title</span>
					<div class="pull-right">	
						<button class="btn btn-default" data-dismiss="modal">Back</button>
					</div>
				</h3>
			</div>

			<div class="modal-body">
				<iframe id="model_video_link" src="" style="width:100%; height:500px;" allowfullscreen></iframe>
			</div>
        </div>
    </div>
</div>
<!-- END OF MODAL -->
<!-- <button id="btn_add_new" class="btn btn-primary btn_add pull-right" data-toggle="modal" data-target="#reply_message">Add New User</button> -->
<!-- REPLY Message Modal -->
<div class="modal fade" id="reply_message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        		<div class="modal-header">
        			<h3 class="modal-title">
        				<span id="title"></span>
        				<div class="pull-right">
        					<button class="btn btn-default btn-send" id="send_message" type= "button" onClick = "send_message_notif();">Send</button>
        					<button class="btn btn-default btn-send" data-dismiss="modal" onclick = "reset_replybox();">Back</button>
        					<input type = "hidden" id = "message_id" />
        					<input type = "hidden" id = "recipient_id" />
        				</div>
        			</h3>
        		</div>

        		<div class="modal-body">

					<form id="reply_message_form" class="form-horizontal">

	        			<div class="row">
	        				<div class="col-md-5">

								<div class="form-group">
									<label for="mail_subj" class="col-sm-2 control-label">Subject</label>
									<div class="col-sm-10">
										<input type="text" name="mail_subj" id="mail_subj" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="mail_topic" class="col-sm-2 control-label">Topic</label>
									<div class="col-sm-10">
										<input type="text" name="mail_topic" id="mail_topic" class="form-control" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="mail_recipient" class="col-sm-2 control-label" id ="mail_recipient_label"></label>
									<div class="col-sm-10">
										<input type="text" name="mail_recipient" id="mail_recipient" class="form-control" readonly>
									</div>
								</div>

							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
								<!-- <div id="reply_box" class="form-control" contenteditable="true">
									<br class = "unselectable" />
									<span readonly contenteditable="false" disabled class = "unselectable">----------------------------------------------------------------------------------- </span>
									<br class = "unselectable" />
									<span id="previous_mail" readonly contenteditable="false" disabled class = "unselectable"></span>
								</div> -->

									<div id ="reply_box" class = "form-control">
									<div contenteditable="true" id = "real_reply">
										<br>
										<br>
									</div>
									<div contenteditable="false" class = "unselectable">
										<span readonly contenteditable="false" disabled class = "unselectable">-----------------------------------------------------------------------------------</span>
										<br>
										<br>
										<span id="previous_mail" readonly contenteditable="false" disabled class = "unselectable"></span>
									</div>

								</div>
							</div>
						</div>

  					</form>

        		</div>
        </div>
    </div>
</div>
<!-- END OF MODAL -->

<div class="container mycontainer">



	<!-- MESSAGES / NOTIFICATION PANEL -->
	<div class="row" id="messages_div">
		<div class="col-md-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<div class="row">
					
						<div class="col-xs-4 col-sm-4">
							<h3 class="panel-title line-height-30">Messages and Notifications</h3>
						</div>
						<div class="col-xs-8 col-sm-8">
							<div class="form-inline pull-right">
								<div class="form-group">
									<label for="msg_notification_type" class="control-label">Type</label>
								
									<select id="msg_notification_type" class="form-control messages_filter" data-filter-type="TYPE">
										<option value="all" selected="selected">All</option>
										<option value="message">Message</option>
										<option value="notification">Notification</option>
									</select>
								</div>
								
								<div class="form-group">
									<label for="msg_notif_status" class="control-label">Status</label>
									<select id="msg_notif_status" class="form-control messages_filter" data-filter-type="IS_READ">
										<option value="all" selected="selected">All</option>
										<option value="mail_read">Read</option>
										<option value="mail_unread">Unread</option>
									</select>
								</div>
								
							</div>
						</div>
					</div>
				</div>

				<div class="table-responsive" id = "message_div">
					<table class="table table-hover" id="messagest">
						<thead>
							<tr>
								<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "DATE_SENT"><label>Date Received </label><span></span><a/></th>
								<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "SENDER_RECIPIENT">From <span></span></a></th>
								<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "SUBJECT">Subject <span></span></a></th>
								<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "TOPIC">Topic <span></span></a></th>
								<th data-col="MAIL_DATE_FORMATTED" class ="a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "TYPE">Type <span></span></a></th>
								<th data-col="MAIL_DATE_FORMATTED" style ="width: 	90px;" class ="a_table_header sort_column sort_default"><center><a href = "#" onclick = "return false;" data-sort = "asc" data-sort_type = "IS_READ">Status <span></span></a></center></th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
					<center><div id="inbox_pagination"></div>
				</center>
				</div>

				
			</div>
		</div>
	</div>
	<!-- END OF MESSAGES / NOTIFICATION PANEL -->

	<!-- RFB / RFQ -->
	<?php 
	// 



	if($hide_show_rfq == 1){

	if( $position_id == 2 || $position_id == 3 || $position_id == 4 || $position_id == 5 || $position_id == 6 || $position_id == 11)
		$display= 'none';
	else
		$display= 'inherit';
	
	//10 = Vendor
	if($position_id == 10){
		// echo 'btype: '. $business_type.'  ';
		if(isset($v_business_type) && $v_business_type == 2){
			$display= 'inherit';
		}else{
			$display= 'none';
		}
	}
	}else{
		$display= 'none';
	}

	// $display= 'none';# HIDE FOR PROD MUNA
	?>
	<div class="row" id="rfb_rfq_div" style="display: <?=$display?>">
		<div class="col-md-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-4 col-sm-4">
							<h3 class="panel-title line-height-30">RFB / RFQ</h3>
						</div>
						<div class="col-xs-8 col-sm-8">
							<div class="form-inline pull-right">
								<div class="form-group">
									<label for="rfb_rfq_status" class="control-label">Status</label>
									<select id="rfb_rfq_status" class="form-control status_filter">
										<script type="text/template" id ="rfq_status_temp">
											<option value="" selected="selected">All</option>
											{{#data}}
												<option value="{{STATUS_NAME}}" data-id="{{STATUS_ID}}">{{STATUS_NAME}}</option>
											{{/data}}
										</script>
									</select>
								</div>
								<?php if($position_id == 7)
										$display_btn_create= 'inline';
									else
										$display_btn_create= 'none';
								?>
								<div class="form-group" style="display: <?=$display_btn_create?>">

										<button class="btn btn-default cls_action" data-action-path="rfqb/rfq_main" data-crumb-text="RFB Creation">Create</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id = "rfq_table">
				<table class="table table-hover" id="rfbs_rfqs">
					<thead>
					<?php if ($user_type_id === '1'): ?>
						<tr>
							<th data-col="RFQRFB_ID"  class = "a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "RFQRFB_ID">No. <span></span></a></th>
							<th data-col="RFQ_TITLE"  class = "a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "TITLE">Title <span></span></a></th>
							<th data-col="SUBMISSION_DEADLINE"  class = "a_table_header sort_column sort_default"> <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "SUBMISSION_DEADLINE">Time Left <span></span></a></th>
							<th data-col="VENDORS_PARTICIPATION"  class = "a_table_header sort_column sort_default"> <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "VENDORS_PARTICIPATION">Vendors Participation <span></span></a></th>
							<th data-col="RESPONSES" class = "a_table_header sort_column sort_default"> <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "RESPONSES">Responses<span></span></a></th>
							<th data-col="UNREAD_MESSAGES" class = "a_table_header sort_column sort_default"> <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "MESSAGE_INDEX_PARAM">Unread Messages <span></span></a></th>
							<th data-col="STATUS_NAME" class = "a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "STATUS_NAME">Status <span></span></a></th>
							<th data-col="DATE_SORTING_FORMAT" class = "a_table_header sort_column sort_default"> <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "DATE_CREATED">Date Created <span></span></a></th> <!-- Old data-col : DATE_CREATED-->
							<th data-col="ACTION_LABEL" class = "a_table_header sort_column sort_default"><a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "STATUS_NAME">Action <span></span></a></th>
						</tr>
					<?php elseif ($user_type_id === '2'): ?>
						<tr>
							<th data-col="RFQRFB_ID" class = "a_table_header sort_column sort_default">No. <span></span></th>
							<th data-col="RFQ_TITLE" class = "a_table_header sort_column sort_default">Title <span></span></th>
							<th data-col="SUBMISSION_DEADLINE" class = "a_table_header sort_column sort_default">Time Left <span></span></th>
							<th data-col="STATUS_NAME" class = "a_table_header sort_column sort_default">Status <span></span></th>
							<th data-col="DATE_SORTING_FORMAT" class = "a_table_header sort_column sort_default">Date Created <span></span></th> <!-- Old data-col : DATE_CREATED -->
							<th data-col="ACTION_LABEL" class = "a_table_header sort_column sort_default">Action <span></span></th>
						</tr>
					<?php endif; ?>
					</thead>
					<tbody>
					<?php if ($user_type_id === '1'): ?>

						


						<!-- <script id="admin_rfq_rfb_template" type="text/template">
							{{#ResultSet}}
								<tr>
									<td>{{RFQRFB_ID}}</td>
									<td><a href="#" class="cls_action" data-action-path="rfqb/rfq_details/index/{{RFQRFB_ID}}" data-crumb-text="RFQ/RFB Details">{{RFQ_TITLE}}</a></td>
									<td>{{SUBMISSION_DEADLINE}}</td>
									<td>{{VENDORS_PARTICIPATION}}</td>
									<td>{{RESPONSES}}</td>
									<td><a href="#" class="cls_action" data-action-path="messaging/mail/index/{{MESSAGE_INDEX_PARAM}}" data-crumb-text="Messages">{{UNREAD_MESSAGES}}</a></td>
									<td>{{STATUS_NAME}}</td>
									<td>{{DATE_CREATED}}</td>
									{{#ACTION_PATH}}
										<td><a href="#" class="cls_action" data-action-path="{{ACTION_PATH}}/{{RFQRFB_ID}}" data-crumb-text="RFQ/RFB {{ACTION_LABEL}}">{{ACTION_LABEL}}</a></td>
									{{/ACTION_PATH}}
									{{^ACTION_PATH}}
										<td>{{ACTION_LABEL}}</td>
									{{/ACTION_PATH}}
								</tr>
							{{/ResultSet}}
							{{^ResultSet}}
								<tr>
									<td colspan="6">No Records found.</td>
								</tr>
							{{/ResultSet}}
						</script> -->
					<?php elseif ($user_type_id === '2'): ?>
						<script id="vendor_rfq_rfb_template" type="text/template">
							{{#ResultSet}}
							<tr>
								<td>{{RFQRFB_ID}}</td>
								{{#RESPONSE_FLAG}}
									<td><a href="#" class="cls_action" data-action-path="rfqb/rfq_response_vendor_view/index/{{RFQRFB_ID}}/{{INVITE_ID}}">{{RFQ_TITLE}}</a></td>
								{{/RESPONSE_FLAG}}
								{{^RESPONSE_FLAG}}
									<td>{{RFQ_TITLE}}</td>
								{{/RESPONSE_FLAG}}
								<td>{{SUBMISSION_DEADLINE}}</td>
								<td>{{STATUS_NAME}}</td>
								<td>{{DATE_CREATED}}</td>
								{{#ACTION_PATH}}
									<td><a href="#" class="cls_action" data-action-path="{{ACTION_PATH}}/{{RFQRFB_ID}}/{{INVITE_ID}}" data-crumb-text="RFQ/RFB {{ACTION_LABEL}}">{{ACTION_LABEL}}</a></td>
								{{/ACTION_PATH}}
								{{^ACTION_PATH}}
									<td>{{ACTION_LABEL}}</td>
								{{/ACTION_PATH}}
							</tr>
							{{/ResultSet}}
							{{^ResultSet}}
								<tr>
									<td colspan="6">No Records found.</td>
								</tr>
							{{/ResultSet}}
						</script>
					<?php endif; ?>
					</tbody>
				</table>

				<center><div id="rfqrfb_pagination"></div></center>
			</div>
			</div>
		</div>
	</div>
	<!-- END OF RFB / RFQ -->

	<!-- Vendor Registration -->
	<div class="row" id="vendor_registrations_div">
		<div class="col-md-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-4 col-sm-4">
							<h3 class="panel-title line-height-30">Vendor Registration</h3>
						</div>
						<div class="col-xs-8 col-sm-8"> 
							<?php
								if($position_id == 7 || $position_id == 2 || $position_id == 11)
									$display_btn_invite= 'inline';
								else
									$display_btn_invite= 'none';

								if($position_id == 5) //vrdhead can view for hats approval
									$display_btn_for_hats= 'inline';
								else
									$display_btn_for_hats= 'none';
								
								// Added MSF 20191129 (NA)
								if($position_id == 4){ //vrd staff viewing
									$display_btn_for_vrd_staff = 'inline';
								}else{
									$display_btn_for_vrd_staff = 'none';
								}
							?>
							<!-- Added MSF 20191129 (NA) -->
							<div class="form-inline pull-right"  style="display: <?=$display_btn_for_vrd_staff?>">
								<div class="form-group" style="display: <?=$display_btn_for_vrd_staff?>">
									<input class="btn btn-default cls_toggle_vrd_staff_override" type="button" id="btn_vrd_staff" value="For VRD Staff" />
								</div>
							</div>
							
							<div class="form-inline pull-right"  style="display: <?=$display_btn_for_hats?>">
								<div class="form-group" style="display: <?=$display_btn_for_hats?>">
									<input class="btn btn-default cls_toggle_hats_override" type="button" id="btn_hats_approval" value="For HaTS Approval" />
								</div>
							</div>
							
							<div class="form-inline pull-right"  style="display: <?=$display_btn_invite?>">
								<div class="form-group" style="display: <?=$display_btn_invite?>">
									<button class="btn btn-default cls_action" data-action-path="vendor/add_department" data-crumb-text="Add Department">Add Department</button>
								</div>
							</div>
							
							<div class="form-inline pull-right"  style="display: <?=$display_btn_invite?>">
								<div class="form-group" style="display: <?=$display_btn_invite?>">
									<button class="btn btn-default cls_action" data-action-path="vendor/invitecreation" data-crumb-text="Invite Creation">Invite</button>
								</div>
							</div>
							<div class="form-inline pull-right">
								<div class="form-group">
									<label for="vendor_status" class="control-label">Status</label>
									<input type="text" id="vendor_status_exception" class="form-control" style="display:none;min-width:310px" disabled value = "For VRD Head Approval,For HaTS Approval"/>
									
									<!-- Added MSF 20191129 (NA) -->
									<input type="text" id="vendor_status_exception_vrd" class="form-control" style="display:none;min-width:310px" disabled value = "Submitted, Validation, Assignment"/>
									<input type = "hidden" id = "for_hats_val" value = "0" hidden />
									
									<!-- Added MSF 20191129 (NA) -->
									<input type = "hidden" id = "for_vrdstaff_val" value = "0" hidden />
									<select id="vendor_status" class="form-control status_filter">
										<option value="" selected="selected">All</option>
										<script type="text/template">
											<option value="">All</option>
											{{#ResultSet}}
												<option value="{{STATUS_NAME}}" data-id="{{STATUS_ID}}">{{STATUS_NAME}}</option>
											{{/ResultSet}}
										</script>
									</select>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div id = "div_vendor_reg">
				<table class="table table-hover" id="vendor_registrations">
					<thead>
						<tr>
							<th style="max-width: 300px;width: 300px;word-wrap: break-word;" data-col='VENDOR_NAME' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="VENDOR_NAME"><label>Vendor &nbsp;</label><span></span></a>
							</th>
							<th data-col='STATUS_NAME' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="STATUS_NAME"><label>Status &nbsp;</label><span></span></a></th>
							<th data-col='MESSAGE_COUNT' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="MESSAGE_COUNT"><label>Unread Messages &nbsp;</label><span></span></a>
							</th>
							<th data-col='DATE_SORTING_FORMAT' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="DATE_CREATED"><label>Date Invited &nbsp;</label><span></span></a>
							</th> <!-- Old data-col DATE_CREATED-->
							<th data-col='DATE_SORTING_REVIEWED_FORMAT' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="DATE_SUBMITTED"><label>Date Submitted &nbsp;</label><span></span></a>
							</th><!-- Old data-col DATE_SUBMITTED-->
							<th data-col='ACTION_LABEL' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="ACTION_LABEL"><label>Action &nbsp;</label><span></span></a>
							</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			

				<center><div id="vendor_reg_pagination1"></div></center>
				</div>
			</div>
		</div>
	</div>

	<!-- END OF Vendor Registration -->

</div>
<script type='text/javascript' >
	//$.getScript("<?=js_path('home_page.js')?>");
	$.getScript("<?=js_path('new_home_page.js')?>");
</script>
