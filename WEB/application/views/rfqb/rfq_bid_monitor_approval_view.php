<script src="<?=base_url();?>assets/js/rfqb/rfq_bid_monitor_approval.js"></script>
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

	<div class="row" action="">
		<div class="col-md-4">

			<h4 id="rfqb_id" class="rfqb_title" rel="<?php echo $rfb_id; ?>">Bid Monitor - <?php echo $rfb_id; ?></h4>

		</div>
		<div class="col-md-8">
			<span class="pull-right">
				<button class="btn btn-primary btn-sm approval_btn" id="btn_approve" onclick="btnAction('1'); return false;">Approve</button>
				<button class="btn btn-primary btn-sm approval_btn" id="btn_reject" data-toggle="modal" data-target="#modal_reject_bid">Reject</button>
				<button class="btn btn-primary btn-sm failed_btn" id="btn_close">Close</button>
			</span>
		</div>
	</div>


	<br>
	<div class="row">
		<div class="col-md-4">
			<div class="form-horizontal">
				<div class="form-group">

						<label class="col-md-6 main_label"><span class="pull-right">Bid Status :</span></label>
						<div class="col-md-6">	
							<label id="bid_status"></label>
							<input type="hidden" name="bid_type" id="bid_type" value="">
						</div>

				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="form-horizontal">
				<div class="form-group">
					<label id="lbl_deadline" class="col-md-5 main_label"><span class="pull-right">New Close Date :</span></label>
					<div class="col-md-7">	
						<label id="bid_deadline"></label>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="form-horizontal">


				<div class="form-group">
					<label class="col-md-2"><span class="pull-right main_label">Reason :</span></label>
					<div class="col-md-8">	
						<textarea id="approval_reason" class="form-control field-required" rows="3" readonly></textarea>
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
				<!-- <div class="row">
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
							<table id="tbl_view" class="table" style="width: 95%;" align="center">
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


<!--REJECT MODAL-->
	<div id="modal_reject_bid" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title">Reject - RFQ/RFB</h4>
					</div>
				
					<div class="modal-body">

						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Reason </span></label>
							<div class="col-md-7">	
								<textarea id="reject_bid_reason" class="form-control field-required" rows="5" ></textarea>
							</div>
						</div>
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_reject_bid" class="btn btn-primary" onclick="btnAction('2'); return false;">Ok</button>
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

