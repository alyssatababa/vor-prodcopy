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

</style>
<?=form_open_multipart('form1', array('name' => 'form1') );?>

<div class="container mycontainer">
	<div class="row">
		<div class="col-md-6">
			<h4>Bid Comparison - RFQ# 1008 - Laser and Dot Matrix Printers</h4>
		</div>
		<div class="col-md-offset-8">
			<button class="btn btn-primary btn-sm" id="btn_submit">Submit Shortlist For Approval</button>
			<button class="btn btn-primary btn-sm" id="btn_failed_bid">Failed Bid</button>
			<button class="btn btn-primary btn-sm" id="btn_close">Close</button>
		</div>
	</div>
	<br>
	<!-- TABLE -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="form-group indent_top indent_left">
					<div class="row">
						<div class="col-md-5">
							Title: 
							<label class="form-label">Laser and Dot Matrix Printers</label>
						</div>
						<div class="col-md-3">
							Created By: 
							<label class="form-label">Buyer 1</label>
						</div>
						<div class="col-md-3">
							Date Created: 
							<label class="form-label">February 24, 2016</label>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							Type: 
							<label class="form-label">Competitive</label>
						</div>
						<div class="col-md-2">
							Currency:
							<label class="form-label">PHP</label>
						</div>
						<div class="col-md-4">
							Preferred Delivery Date: 
							<label class="form-label">March 24, 2016</label>
						</div>
						<div class="col-md-4">
							Submission Deadline Date: 
							<label class="form-label">March 10, 2016</label>
						</div>
					</div>

				</div>

				<!-- SM VIEW ONLY -->
				<div class="row">
					<div class="form-horizontal">
						<div class="form-group">
							<label for="requestor" class="col-md-2 control-label">Requestor</label>
							<div class="col-md-9">
								<select name="requestor" id="requestor" class="form-control">
									<option value="it_dept">IT Department</option>
									<option value="another_dept">Another Department</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Purpose of Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control">
									<option value="replacement">Replacement</option>
									<option value="another_purp">Another Purpose</option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_purpose" id="other_purpose" class="form-control"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_purpose" class="col-md-2 control-label">Reason for Request</label>
							<div class="col-md-4">
								<select name="request_purpose" id="request_purpose" class="form-control">
									<option value="another_reason">Another Reason</option>
									<option value="others" selected="selected">Others</option>
								</select>
							</div>
							<div class="col-md-5">
								<textarea name="other_reason" id="other_reason" class="form-control" row="1">Upgrade in the system reuires these printers for compatibiliy</textarea>
							</div>
						</div>
						<div class="form-group">
							<label for="request_note" class="col-md-2 control-label">Internal Note</label>
							<div class="col-md-9">
								<textarea name="request_note" id="request_note" class="form-control" row="1">Laser Printers for executives. Dot Matrix printers for Accounting Dept.</textarea>
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
							<table class="table" style="margin-bottom: 0px"  align="center">
								<thead>
									<th style="width:15%"><center>Vendor</center></th>
									<th style="width:30%"><center>AAA Inc</center></th>
									<th style="width:25%"><center>Power Printers</center></th>
									<th style="width:30%"><center>Octagon</center></th>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					<div class="form-group indent_sides" style="margin: inherit;">
					<div class="panel panel-primary" style="margin-bottom: 0px;">
					<div class="panel-heading">
					<table style="width: 100%">
						<tr>
							<td>Laser Printer - Multi Function</td>
							<td><?=form_button('prices_bom', 'Compare Prices of BOM', ' class="btn btn-default pull-right" style="color: #000000"');?></td>
						</tr>
					</table>
					</div>
					</div>
						<div class="col-md-12 no-indent_sides">
							<table class="table">
								<thead>
									<th style="width:15%">No Shortlist<br><center><?=form_checkbox('chk_bx_1', '0', FALSE)?></center></th>
									<th style="width:30%"><center><?=form_checkbox('chk_bx_2', '0', FALSE)?></center></th>
									<th style="width:25%"><center><?=form_checkbox('chk_bx_3', '0', FALSE)?></center></th>
									<th style="width:30%"><center><?=form_checkbox('chk_bx_4', '0', FALSE)?></center></th>
								</thead>
								<tbody>
								<tr>
									<td>Price</td>
									<td style="text-align: center;">PHP 215000.00</td>
									<td style="text-align: center;">PHP 220000.00</td>
									<td style="text-align: center;">PHP 230000.00</td>
								</tr>
								<tr>
									<td>Delivery Lead Time</td>
									<td style="text-align: center;">10</td>
									<td style="text-align: center;">15</td>
									<td style="text-align: center;">5</td>
								</tr>
								<tr>
									<td>Counter Offer</td>
									<td style="text-align: center;">Brother MFC-L3700OW</td>
									<td style="text-align: center;">Borther MFC-L6700DW</td>
									<td style="text-align: center;">Dell H625cdw</td>
								</tr>
								<tr>
									<td>Attachment</td>
									<td style="text-align: center;">None</td>
									<td style="text-align: center;">None</td>
									<td style="text-align: center;">User Manual.pdf</td>
								</tr>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
				<?=br(2)?>
				<div class="row">
					<div class="col-md-12">
					<div class="form-group indent_sides" style="margin: inherit;">
					<div class="panel panel-primary" style="margin-bottom: 0px;">
					<div class="panel-heading">
					<table style="width: 100%">
						<tr>
							<td>EPSON LX 310 Dot Matrix Printer</td>
						</tr>
					</table>
					</div>
					</div>
						<div class="col-md-12 no-indent_sides">
							<table class="table">
								<thead>
									<th style="width:15%">No Shortlist<br><center><?=form_checkbox('chk_bx_1', '0', FALSE)?></center></th>
									<th style="width:30%"><center><?=form_checkbox('chk_bx_2', '0', FALSE)?></center></th>
									<th style="width:25%"><center><?=form_checkbox('chk_bx_3', '0', FALSE)?></center></th>
									<th style="width:30%"><center><?=form_checkbox('chk_bx_4', '0', FALSE)?></center></th>
								</thead>
								<tbody>
								<tr>
									<td>Price</td>
									<td style="text-align: center;">PHP 95000.00</td>
									<td style="text-align: center;">PHP 94000.00</td>
									<td style="text-align: center;">PHP 92500.00</td>
								</tr>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
				<?=br(2)?>
				<div class="row">
					<div class="col-md-12">
					<div class="form-group indent_sides" style="margin: inherit;">
					<div class="panel panel-primary" style="margin-bottom: 0px;">
					<div class="panel-heading">
					<table style="width: 100%">
						<tr>
							<td>Total Amount</td>
						</tr>
					</table>
					</div>
					</div>
						<div class="col-md-12 no-indent_sides">
							<table class="table" style="text-align: center">
								<tbody>
								<tr>
									<td>&nbsp;</td>
									<td style="text-align: center;">PHP 310000.00</td>
									<td style="text-align: center;">PHP 314000.00</td>
									<td style="text-align: center;">PHP 322500.00</td>
								</tr>
								</tbody>
							</table>
						</div>
						</div>
					</div>
				</div>
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