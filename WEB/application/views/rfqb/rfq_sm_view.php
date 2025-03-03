<style>
.main_label
{
	color: #43A5CF;
}

.cursor_pointer
{
	cursor: pointer;
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

textarea.form-control 
{
		resize: vertical;
		height: 34px;
}

</style>
<?=form_open_multipart('form1', array('name' => 'form1') );?>

<div class="container mycontainer">
	<!-- TABLE -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading"><label class="form-label">Filter By</label></div>
				<div class="row">
					<div class="form-group indent_top indent_left">
						<div class="col-md-2">
							<table>
								<tr>
									<td>No.</td>
									<td class="indent_left"><?=form_input('search_no', '', 'id="search_no" class="form-control" placeholder="Search No."');?></td>
								</tr>
								<tr>
									<td>Buyer</td>
									<td class="indent_left"><?=form_dropdown('buyer_filter', array('Buyer 1'), '', 'id="buyer_filter" class="btn btn-default dropdown-toggle" style="width:100px"');?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<table>
								<tr>
									<td>Title</td>
									<td class="indent_left"><?=form_input('search_title', '', 'id="search_title" class="form-control" placeholder="Search Title" style="width:200px"');?></td>
								</tr>
								<tr>
									<td>Requestor</td>
									<td class="indent_left"><?=form_dropdown('requestor_filter', $requestor_data, '', 'id="requestor_filter" class="btn btn-default dropdown-toggle" style="width:200px"');?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-2">
							<table>
								<tr>
									<td>Date Created</td>
									<td class="indent_left"><input type="date" name="date_created" id="date_created" class="form-control" style="width: 130px"></td>
								</tr>
								<tr>
									<td>Purpose</td>
									<td class="indent_left"><?=form_dropdown('purpose_filter', $purpose_data, '', 'id="purpose_filter" class="btn btn-default dropdown-toggle" style="width:100px"');?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-2">
							<table>
								<tr class="indent_left">
									<td>Status</td>
									<td class="indent_left"><?=form_dropdown('status_filter', array('Open'), '', 'id="status_filter" class="btn btn-default dropdown-toggle" style="width:100px"');?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<table>
								<tr class="indent_left">
									<td>Time Left</td>
									<td class="indent_left"><?=form_dropdown('timeleft_filter', array('Less than 1 Days', 'Less than 3 Days', 'Less Than 5 Dats'), '', 'id="timeleft_filter" class="btn btn-default dropdown-toggle" style="width:150px"');?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
					<?=br(3)?>
					<div class="row">
						<div class="col-md-offset-10">
							<?=form_button('search_find_btn', 'Search', 'class="btn btn-primary" onclick="#" style="width: 140px;" ')?>
						</div>
					</div>
					<?=br(2)?>
				</div><!-- end first panel -->

				<!-- 2nd table -->
				<div class="panel panel-primary">
				<div class="panel-heading">
				<div class="row">
					<div class="form-group">

						<div class="col-md-10">
							<h5><label class="form-label">RFQ/RFB</label></h5>
						</div>
						<div class="col-md-2">
							<?=form_button('btn_create', 'Create', ' class="btn btn-default" onclick="#"');?>
						</div>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<table class="table" style="width: 100%;">
								<thead>
									<th style="width: 5%">No.</th>
									<th style="width: 20%">Title<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 15%">Time Left<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 5%">Vendors Participation</th>
									<th style="width: 5%">Responses</th>
									<th style="width: 10%">Unread Messages<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 15%">Status<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 10%">Date Created<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 15%">Action</th>
								</thead>
								<tbody>
									<tr>
										<td>1008</td>
										<td>RFQ#1008 - Bid Laser Printer</td>
										<td>3 days 3 hourS</td>
										<td>3/10</td>
										<td>2</td>
										<td>1</td>
										<td>Open</td>
										<td>11/02/2016</td>
										<td><a onclick="open_approval(4)" class="cursor_pointer">Monitor Bid</a></td>
									</tr>
									<tr>
										<td>1006</td>
										<td>RFQ#1006 - Bid Black Ballpen</td>
										<td>0</td>
										<td>5/8</td>
										<td>2</td>
										<td>2</td>
										<td>Failed Bid</td>
										<td>10/28/2016</td>
										<td>Failed Bid Approval</td>
									</tr>
									<tr>
										<td>1023</td>
										<td>RFQ#1023 - RFQ for Bond Paper</td>
										<td>0</td>
										<td>3/3</td>
										<td>3</td>
										<td>0</td>
										<td>Award for Approval</td>
										<td>10/27/2016</td>
										<td></td>
									</tr>
									<tr>
										<td>1003</td>
										<td>RFQ#1003 - Bid For Epson LX800</td>
										<td>0</td>
										<td>3/5</td>
										<td>3</td>
										<td>0</td>
										<td>Award Rejected</td>
										<td>10/25/2016</td>
										<td>Award</td>
									</tr>
									<tr>
										<td>1020</td>
										<td>RFQ#1020 - Bid For Epson LX800</td>
										<td>0</td>
										<td>7/7</td>
										<td>5</td>
										<td>0</td>
										<td>Closed</td>
										<td>10/20/2016</td>
										<td>Shortlist</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				</div>
				</div>
				<?=br(3)?>
			</div>
		</div>
	</div>
	<!-- END TABLE -->
	
</div>

<?=form_close();?>

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

<script>
	function specs_show(row)
	{
		return 1;
	} 
</script>