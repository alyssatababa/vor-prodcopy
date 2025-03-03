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
							</table>
						</div>
						<div class="col-md-3">
							<table>
								<tr>
									<td>Title</td>
									<td class="indent_left"><?=form_input('search_title', '', 'id="search_title" class="form-control" placeholder="Search Title" style="width:200px"');?></td>
								</tr>
							</table>
						</div>
						<div class="col-md-2">
							<table>
								<tr>
									<td>Date Created</td>
									<td class="indent_left"><input type="date" name="date_created" id="date_created" class="form-control" style="width: 130px"></td>
								</tr>
							</table>
						</div>
						<div class="col-md-2">
							<table>
								<tr class="indent_left">
									<td>Status</td>
									<td class="indent_left"><?=form_dropdown('status_filter', $status_dropdown, '', 'id="status_filter" class="btn btn-default dropdown-toggle" style="width:100px"');?></td>
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
							<?=form_button('search_find_btn', 'Search', 'class="btn btn-primary" onclick="search_filter_vendor()" style="width: 140px;" ')?>
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
						<div class="col-md-12" id="search_result">
							<?=$table_data?>
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