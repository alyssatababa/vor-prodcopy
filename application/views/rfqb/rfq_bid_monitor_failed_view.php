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
	<div class="row">
		<div class="col-md-6">
			<h4>Bid Monitor - RFQ# 1008 - Laser and Dot Matrix Printers</h4>
		</div>
		<div class="col-md-offset-9">
			<button class="btn btn-primary btn-sm" id="btn_close">Failed Bid</button>
			<button class="btn btn-primary btn-sm" id="btn_close">Continue</button>
			<button class="btn btn-primary btn-sm" id="btn_close">RFQ</button>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-md-12">
			<label class="pull-right" style="color: #FF0000;"><h4>FAILED</h4></label>		
		</div>
	</div>
	<br>
	<!-- TABLE -->
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="row">
					<div class="form-group indent_top" align="center">
						
						<div class="col-md-4"><?=form_button('no_of_invited', 'Number Of Invited', 'class="btn btn-primary btn-default cursor_pointer " readonly');?></div>
						<div class="col-md-4"><?=form_button('no_of_participants', 'Number Of Participants', 'class="btn btn-primary btn-default cursor_pointer " readonly');?></div>
						<div class="col-md-4"><?=form_button('no_of_responses', 'Number Of Responses', 'class="btn btn-primary btn-default cursor_pointer " readonly');?></div>
						
					</div>
				</div>
				<div class="row">
					<div class="form-group" align="center">
						<div class="col-md-4"><h1>6</h1></div>
						<div class="col-md-4"><h1>4</h1></div>
						<div class="col-md-4"><h1>2</h1></div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<table class="table" style="width: 95%;" align="center">
								<thead>
									<th style="width: 40%">Vendor<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 20%">Invite<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 20%">Date Acknowledge<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
									<th style="width: 20%">Date Submitted<span class="glyphicon glyphicon-sort sort_messages" data-sort-type="sender"></span></th>
								</thead>
								<tbody>
									<tr>
										<td>AAA Inc</td>
										<td>Accepted</td>
										<td>02/24/2016 10:31 AM</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>Octagon</td>
										<td>Accepted</td>
										<td>02/24/2016 9:15 AM</td>
										<td>02/25/2016 8:31 PM</td>
									</tr>
									<tr>
										<td>Office Hub</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>Power Printers</td>
										<td>Accepted</td>
										<td>02/24/2016 3:20 PM</td>
										<td>02/24/2016 5:15PM</td>
									</tr>
									<tr>
										<td>YYY Inc</td>
										<td>Accepted</td>
										<td>02/25/2016 7:23 AM</td>
										<td>&nbsp;</td>
									</tr>
									<tr>
										<td>ZZZ Inc</td>
										<td>Rejected</td>
										<td>02/24/2016 12:20 PM</td>
										<td>&nbsp;</td>
									</tr>
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