<style>
	textarea.form-control {
		resize: vertical;
		height: 34px;
	}
	textarea.form-control[name="request_note"],
	textarea.form-control[name="reject_reason"] {
		height: 100px;
	}
	thead {
		background-color: #d8d8d8;
	}

td.pic_attachment
{
	padding: 0 5px 0 5px;
}

.dv_attachment
{
	padding: 15px 20px 15px 20px;
	border: 1px solid #ccc;
}
</style>
<?=form_open('form1', array('name' => 'form1'))?>
<?=(isset($bom_file_modals) ? $bom_file_modals : '');?>
<!-- Modal -->

<div class="modal fade" id="reject_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">Reject Reason</h3>
			</div>
			<div class="modal-body">
				<textarea name="reject_reason" id="reject_reason" class="form-control editable"></textarea>
			</div>
			<div class="modal-footer">
				<button id="reject_btn" onclick="approve_creation(0)" class="btn btn-primary" data-dismiss="modal">Ok</button>
				<button id="cancel_btn" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
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
<!-- END OF MODAL -->

	<div id="result_div">
	</div>
<div class="container mycontainer" id="mycontainer">
	<input type="hidden" name="rfx_id" id="rfx_id" value="<?=$id?>">
	<input type="hidden" name="position_id" id="position_id" value="<?=$position_id?>">
	<input type="hidden" name="current_status_id" id="current_status_id" value="<?=$status_id?>">
	<div class="row">
		<div class="col-md-4">
			<h4>RFQ/RFB Details</h4>
			<a href="#" id="rfq_approval_history">Approval History</a>

			<p id = "model_error"></p>
		</div>
		<div class="col-md-offset-10">
<!-- 			<input type="button" value="Approve" class="btn btn-primary btn-sm"<?=$is_open?> id="btn_approve" onclick="approve_creation(1)">
			<input type="button"  value="Reject" class="btn btn-primary btn-sm"<?=$is_open?> id="btn_reject" data-toggle="modal" data-target="#reject_modal"> -->
			<!-- <input type="button" value="Exit" class="btn btn-primary btn-sm" id="btn_exit"> -->
		</div>
	</div>

	<hr>
<div class="form_container">
<div class="panel panel-default">
<div class="panel-body">
	<!-- PRIMARY RFQ/RFB DATA -->
	<div class="row">

		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="title" class="col-sm-2 control-label">Title</label>
					<div class="col-sm-10">
						<input type="text" class="form-control field-required" id="title" placeholder="" value="<?=$title?>" disabled>
					</div>
				</div>
				<div class="form-group">
					<label for="type" class="col-sm-2 control-label">Type</label>
					<div class="col-sm-10">
						<input type="hidden" value="<?=$type?>" name="type_radio" id="type_radio">
						<label class="radio-inline">
							<input type="radio" name="type" id="qualified" value="qualified" disabled> Qualified
						</label>
						<label class="radio-inline">
							<input type="radio" name="type" id="competitive" value="competitive" disabled> Competitive
						</label>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<input type="hidden" id="rfqrfb_id" name="rfqrfb_id" value="<?php echo $id; ?>">
					<label for="type" class="col-sm-2 control-label">RFQ</label>
					<div class="col-sm-10"><span id="type" class="form-control"><?=$id?></span></div>
				</div>
				<div class="form-group">
					<label for="status" class="col-sm-2 control-label">Status</label>
					<div class="col-sm-10"><span id="status" class="form-control"><?=$status_name?></span></div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="created_by" class="col-sm-4 control-label">Created By</label>
					<div class="col-sm-8"><span id="created_by" class="form-control"><?=$created_by?><!-- Buyer 1 --></span></div>
				</div>
				<div class="form-group">
					<label for="date_created" class="col-sm-4 control-label">Date Created</label>
					<div class="col-sm-8"><input type="date" id="date_created" class="form-control" value="<?=$date_created?>"></div>
				</div>
			</div>
		</div>

	</div>
	<!-- END PRIMARY RFQ/RFB DATA -->

	<hr>

	<!-- SECONDARY RFQ/RFB DATA -->
	<div class="row">

		<div class="col-md-3">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="currency" class="col-md-4 control-label">Currency</label>
					<div class="col-md-5">
						<?=form_dropdown('currency', $currency_data, $currency, 'id="currency" class="form-control"')?>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-5">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="deadline_date" class="col-md-4">Submission Deadline Date</label>
					<div class="col-md-6"><input type="date" id="deadline_date" class="form-control" value="<?=$submission_deadline?>"></div>
				</div>
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="delivery_date" class="col-md-4">Preferred Delivery Date</label>
					<div class="col-md-6"><input type="date" id="delivery_date" class="form-control" value="<?=$delivery_date?>"></div>
				</div>
			</div>
		</div>

	</div>
	<!-- END SECONDARY RFQ/RFB DATA -->

	<hr>

	<!-- SM VIEW ONLY -->
			<div class="row">
				<div class="form-group indent_sides">
					<div class="col-md-4">
						<label>Requestor</label>
					</div>
					<div class="col-md-8">
						<?=form_dropdown('requestor', $requestor_data, $requestor_id, 'id="requestor" class="btn btn-default dropdown-toggle form-control" style="width: 100%;"');?>
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
						<?=form_dropdown('purpose', $purpose_data, $purpose_id, 'id="purpose" class="btn btn-default dropdown-toggle form-control" onchange="purpose_select(this.value)" style="width:100%"');?>
					</div>
					<div class="col-md-4">
						<?=form_input('purpose_txt', $other_purpose, 'id="purpose_txt" disabled class="form-control" style="width:100%"');?>
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
						<?=form_dropdown('reason', $reason_data, $reason_id, 'id="reason" class="btn btn-default dropdown-toggle form-control" onchange="reason_select(this.value)" style="width:100%"');?>
					</div>
					<div class="col-md-4">
						<?=form_input('reason_txt', $other_reason, 'id="reason_txt" disabled class="form-control" style="width:100%"');?>
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
						<textarea class="form-control" id="internal_note" name="internal_note" style="width: 100%; height: 100px;"><?=$internal_note?></textarea>
					</div>
				</div>
			</div>
	<!-- END SM VIEW ONLY -->
<br>
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

	<hr>

	<div class="row">
		<div class="col-md-12">

			<div class="panel panel-primary">

				<div class="panel-heading">
					<strong>Lines</strong>
					<div class="pull-right">
						<input type="hidden" id="max_lines" name="max_lines" value="1">
						<!-- <input type="button" value="Add" class="btn btn-default btn-xs" onclick="add_delete_lines(1)" id="add_btn">
						<input type="button" value="Delete" class="btn btn-default btn-xs" onclick="delete_lines(0)" id="del_btn" disabled> -->
					</div>
				</div>

				<div class="panel-body" id="lines_data">

					<!-- LINES DATA -->
					<div id="lines_data_rfx">

						<!-- SAMPLE LINE DATA 1 -->
							<?=$lines?>

					</div> <!-- END OF LINES DATA -->
				</div> <!-- END OF PANEL BODY-->
			</div> <!-- END OF PANEL -->

		</div>
	</div>
	<br>

	
	<div class="row indent_sides">
		<div class="col-md-12">
			<div class="panel panel-primary">

				<div class="panel-heading">
					<div class="form-group">
						<div class="col-md-12">
							<b>Invited Vendors</b>
						</div>
					</div>
				</div>
				<div id="selected_invited_vendor">
					<table class="table">
						<thead>
							<div class="col-md-10">
								<th>Vendor Name</th>
							</div>
						</thead>
						<tbody>
							<?php echo $invited_list?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>

	</div>
	</div>
	</div>

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
					<span class="submit" style="display:none;">
						<center><h4 class="modal-title" id="myModalLabel">Submit</h4></center>
					</span>
					<span class="incomplete" style="display:none;">
					<h4 class="modal-title" id="myModalLabel">Incomplete Reason</h4>
					</span>
					<span class="req_visit" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Request Visit</h4>
					</span>
					<span class="document_preview" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Preview</h4>
						<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
						<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
					</span>
					<span class="rfq_approval_history" style="display:none;">
						<h4 class="modal-title" id="myModalLabel">Approval History</h4>
					</span>
				</div>
				<div class="modal-body">
					<div class="container-fluid">
						<span class="submit" style="display:none;">
							<p>Registration Submitted...</p>
							<p>VRD will review the registration you have submitted.</p>
							<br>
							<p>Once Validated, an email notification will be sent when you can come to the VRD office with the original documents and electronically sign the regsitration.</p>
							<br>
							<p>Schedule: Monday to Friday from 1-5 PM</p>
						</span>

						<span class="incomplete" style="display:none;">
							<textarea class="form-control" id="rv_incomplete" name="rv_incomplete" placeholder="Enter Reason" ></textarea>
						</span>

						<span class="req_visit" style="display:none;">
							<div class="form-group col-sm-6">
								<label for="txt_from">From</label>
								<input type="date" class="form-control" id="rv_txt_from" name="rv_txt_from" placeholder="From">
							</div>
							<div class="form-group col-sm-6">
								<label for="txt_to">To</label>
								<input type="date" class="form-control" id="rv_txt_to" name="rv_txt_to" placeholder="To">
							</div>
						</span>
						<span class="document_preview" style="display:none;">
							<!-- <img src="" id="imagepreview" style="width: 400px; height: 264px;" > -->
							<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
						</span>
						<span class="rfq_approval_history" style="display:none;">
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
					</div>
				</div>
				<div class="modal-footer">
					<span class="submit" style="display:none;">
						<center><button type="button" class="btn btn-primary">OK</button></center>
					</span>
					<span class="incomplete" style="display:none;">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" id="btn_incomplete_reg_view">Ok</button>
					</span>

					<span class="req_visit" style="display:none;">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" id="btn_req_visit">Ok</button>
					</span>
					<span class="document_preview" style="display:none;">
						<center><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
					</span>
					<span class="rfq_approval_history" style="display:none;">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</span>
				</div>
			</div>
		</div>
	</div>

</div>

<?=form_close()?>
<script>
	$.getScript("<?php echo base_url().'assets/js/rfq.js'?>");
	$('.form-control:not(.editable)').prop('readonly', true);
	$('select.form-control').prop('disabled', true);
	check_type();

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

            if (filext.toLowerCase().match(/(jpeg|jpg|png)$/))
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

	function check_type()
	{
		if(document.getElementById('type_radio').value == 1)
		{
			document.getElementById('qualified').checked = true;
			document.getElementById('competitive').checked = false;
		}
		else if(document.getElementById('type_radio').value == 2)
		{
			document.getElementById('qualified').checked = false;
			document.getElementById('competitive').checked = true;
		}


	}
	
	function return_dashboard()
	{
		document.form1.action = BASE_URL + "dashboard";
		document.form1.target = "_self";
		document.form1.submit_rfq_creation();
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

	function attachmentview(row)
	{
		if (document.getElementById('attach'+row).value == 0)
		{
			document.getElementById('attach'+row).value = 1;

			// add code here for show next tr
			document.getElementById('attachment'+row).style.display = 'inherit';
			document.getElementById('add_attachment'+row).style.display = 'inline';
			document.getElementById('delete_attachment'+row).style.display = 'inline';
		}
		else	
		{
			document.getElementById('attach'+row).value = 0;

			// add code here for hide next tr
			document.getElementById('attachment'+row).style.display = 'none';
			document.getElementById('add_attachment'+row).style.display = 'none';
			document.getElementById('delete_attachment'+row).style.display = 'none';
		}	
		return;
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

	function search_invite()
	{
		document.getElementById('seach_result_view').value = 1;
		document.getElementById('search_view_list').style.display = 'inherit';

		return;
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
		total_rows = document.getElementById('total_lines').value;

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

</script>