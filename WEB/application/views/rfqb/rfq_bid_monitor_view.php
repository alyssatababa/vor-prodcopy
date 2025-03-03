<script src="<?=base_url();?>assets/js/rfqb/rfq_bid_monitor.js"></script>
<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">

<style>
.ui-datatable * {
     /*border : 0px !important;*/
     border-style: none;
     /*border-bottom: 0;*/
     /*border-top: 0;*/
}


.main_label
{
	color: #43A5CF;
}

.cursor_pointer
{
	cursor: default;
}

.btn.nohover
{
	cursor: default;
	background-position: 0;
	background-color: #DCEFF3;
	color: #000000;
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

thead 
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

/*textarea.form-control 
{
		resize: vertical;
		height: 34px;
}*/
</style>

<div class="container mycontainer">
	<div id="b_url" data-base-url="<?php echo base_url().'index.php/rfqb/'; ?>"></div>

	<div class="row">
		<div class="col-md-4">

			<h4 id="rfqb_id" class="rfqb_title" rel="<?php echo $rfb_id; ?>">Bid Monitor - <?php echo $rfb_id; ?></h4>

		</div>
		<div class="col-md-8">
<!-- 			<button class="btn btn-primary btn-sm" id="approve" data-toggle="modal" data-target="#modal_approve_bid">Approve</button>
			<button class="btn btn-primary btn-sm" id="reject" data-toggle="modal" data-target="#modal_reject_bid">Reject</button> -->
			<span class="pull-right">
				<button class="btn btn-primary btn-sm failed_btn" id="failed" data-toggle="modal" data-target="#modal_failed_bid" style="display: none;">Failed Bid</button>
				<button class="btn btn-primary btn-sm failed_btn" id="continue" data-toggle="modal" data-target="#modal_continue_bid" style="display: none;">Continue RFQ/RFB</button>
				<button class="btn btn-primary btn-sm failed_btn" id="extend" data-toggle="modal" data-target="#modal_extend_bid" style="display: none;">Extend RFQ/RFB</button>
				<button class="btn btn-primary btn-sm failed_btn" id="btn_close">Close</button>
			</span>
		</div>
	</div>


	<br>
	<div class="row">
		<div class="col-md-1">
			&nbsp;
		</div>
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<div id="time_left" class="col-sm-12">
						<label class="main_label">Time Left:</label>
						<label id="bid_timer" style="color: red;"></label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">
					<div id="close_date" class="col-sm-10">
						<label class="main_label">Close Date:</label>
						<label id="submission_deadline" ></label>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-sm-12">
						<label id="failed_bid" style="color: red; font-size: 30px; display:none;">FAILED BID</label>
				
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- TABLE -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				
				<div class="panel col-md-4" align="center"> 
					<div class="row">
						<div class="form-group indent_top" align="center">
							<button class="btn nohover">Number Of Invited</button>
						</div>
			
						<div class="form-group" align="center">
							<h1 class="rfqb_invited"></h1>
						</div>
					</div>
				</div>

				<div class="panel col-md-4" align="center">
					<div class="row">
						<div class="form-group indent_top" align="center">
							<button class="btn nohover">Number Of Participants</button>
						</div>
			
						<div class="form-group" align="center">
							<h1 class="rfqb_participants"></h1>
						</div>
					</div>
				</div>

				<div class="panel col-md-4" align="center">
					<div class="row">
						<div class="form-group indent_top" align="center">
							<button class="btn nohover">Number Of Responses</button>
						</div>
				
						<div class="form-group" align="center">
							<h1 class="rfqb_responses"></h1>
						</div>
					</div>
				</div>

<!-- 				<div class="row">
					<div class="form-group indent_top" align="center">
						<div class="col-md-4"><button class="btn nohover">Number Of Invited</button></div>
						<div class="col-md-4"><button class="btn nohover">Number Of Participants</button></div>
						<div class="col-md-4"><button class="btn nohover">Number Of Responses</button></div>
					</div>
				</div>
				<div class="row">
					<div class="form-group" align="center">
						<div class="col-md-4"><h1 class="rfqb_invited"></h1></div>
						<div class="col-md-4"><h1 class="rfqb_participants"></h1></div>
						<div class="col-md-4"><h1 class="rfqb_responses"></h1 ></div>
					</div>
				</div> -->


				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<table id="tbl_view" class="table ui-datatable" style="width: 95%;" align="center">
								<thead>
									<th style="width: 40%">Vendor</th>
									<th style="width: 20%">Invite</th>
									<th style="width: 20%">Date Acknowledge</th>
									<th style="width: 20%">Date Submitted</th>
								</thead>
								<tbody id="tbl_body_rfqb_monitor">
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?=br(3)?>
			</div>
		</div>
	</div>
	<!-- END TABLE -->
	
</div>



    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding-top: 100px;">
             <div class="modal-content">
                  <div class="modal-body">
                       <div class="container-fluid" id="view_modal">                                     
                       </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-xs btn_min_width">OK</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width">Close</button>
                  </div>
             </div>
        </div>
	</div>

<!--FAILED MODAL-->
	<div id="modal_failed_bid" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal" method="post">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Failed Bid - RFQ/RFB</h4>
					</div>
				
					<div class="modal-body">

						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Reason </span></label>
							<div class="col-md-7">	
								<textarea id="failed_bid_reason" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_failed_bid" class="btn btn-primary" onclick="btnAction('1'); return false;">Ok</button>
								<button class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
		</div>
	</div>
<!--END OF MODAL-->

<!--CONTINUE MODAL-->
	<div id="modal_continue_bid" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Continue Bid - RFQ/RFB</h4>
					</div>
				
					<div class="modal-body">

						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Reason </span></label>
							<div class="col-md-7">	
								<textarea id="continue_bid_reason" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_continue_bid" class="btn btn-primary" onclick="btnAction('2'); return false;">Ok</button>
								<button class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
		</div>
	</div>
<!--END OF MODAL-->

<!--EXTEND MODAL-->
	<div id="modal_extend_bid" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Extend Bid - RFQ/RFB</h4>
					</div>
				
					<div class="modal-body">
						<div class="form-group">
									<div class="col-md-1">&nbsp;</div>
									<label class="col-md-5"><span class="pull-right">New Submission Deadline Date :</span></label>
									<div class="col-md-6">	
										<input type="date" name="date_created" id="date_created" class="form-control pull-left field-required" style="width: 170px">
									</div>
				
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Reason :</span></label>
							<div class="col-md-7">	
								<textarea id="extend_bid_reason" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_extend_bid" class="btn btn-primary" onclick="btnAction('3'); return false;">Ok</button>
								<button class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
		</div>
	</div>
<!--END OF MODAL-->

<script>
	function specs_show(row)
	{
		return 1;
	} 
</script>


