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

<div class="container mycontainer">
	<!-- TABLE -->
	<div class="row">
		<div class="col-md-12">
		<form id="frm_rfqrfb_vendor_main" method="post">
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
									<td class="indent_left">
									<select name="cbo_status" id="cbo_status" class="form-control">
										<option value="" selected disabled>--Select--</option>
				                    	<?php foreach ($filter_status as $row){
													echo '<option value="'.$row->STATUS_ID.'">'.$row->STATUS_NAME.'</option>';
													}?>
									</select>
									</td>
								</tr>
							</table>
						</div>
						<div class="col-md-3">
							<table>
								<tr class="indent_left">
									<td>Time Left</td>
									<td class="indent_left"><?=form_dropdown('timeleft_filter', array('All', 'Less than 1 Days', 'Less than 3 Days', 'Less Than 5 Days'), '', 'id="timeleft_filter" class="btn btn-default dropdown-toggle" style="width:150px"');?></td>
								</tr>
							</table>
						</div>
					</div>
				</div>
					<?=br(2)?>
					<div class="row">
						<div class="col-md-offset-9">
							<button type="button" class="btn btn-primary" id="btn_clear_rfqm"> Clear</button>
							<button type="button" class="btn btn-primary" id="btn_search_rfqm"><span class="glyphicon glyphicon-search"></span> Search</button>
						</div>
					</div>
					<?=br(2)?>
				</div><!-- end first panel -->
</form>
				<!-- 2nd table -->
				<div class="panel panel-primary">
				<div class="panel-heading">
				<div class="row">
					<div class="form-group">

						<div class="col-md-10">
							<h5><label class="form-label">RFQ/RFB</label></h5>
						</div>
						<div class="col-md-2">
						</div>
					</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<table id="rfqrfb_vendor" class="table table-hover" style="width: 100%;">
								<thead>
								<tr>
									<th data-col="RFQRFB_ID" style="width: 5%">No.</th>
									<th data-col="RFQ_TITLE" style="width: 20%">Title</th>
									<th data-col="SUBMISSION_DEADLINE" style="width: 15%">Time Left</th>
									<th data-col="STATUS_NAME" style="width: 15%">Status</th>
									<th data-col="DATE_CREATED" style="width: 10%">Date Created</th>
									<th data-col="ACTION_LABEL" style="width: 15%">Action</th>
								</tr>
								<thead>
								<tbody id="tbl_body">
							<script id="tbl_template" type="text/template">
								{{#table_template}}
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
										<td><a href="#" class="cls_action" data-action-path="{{ACTION_PATH}}/{{RFQRFB_ID}}/{{INVITE_ID}}">{{ACTION_LABEL}}</a></td>
										{{/ACTION_PATH}}

										{{^ACTION_PATH}}
										<td>{{ACTION_LABEL}}</td>
										{{/ACTION_PATH}}
								</tr>
								{{/table_template}}
								{{^table_template}}
									<tr>
										<td colspan="6">No Records found.</td>
									</tr>
								{{/table_template}}
							</script>
						</tbody>
							</table>
							<center><div id="rfq_pagination"></div></center>
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

<script type="text/javascript">
	loadingScreen('on');
	$.getScript("<?php echo base_url().'assets/js/rfq.js'?>");

	var $tbl_rfqrfb_vendor = $('#rfqrfb_vendor');
	var $div_rfq_pagination = $('#rfq_pagination');

	var rfq_pagination = new Pagination($div_rfq_pagination, $tbl_rfqrfb_vendor, 'sort_columns');

	function get_main_tbl()
	{

		var ajax_type = 'POST';
        var url = BASE_URL + "rfqb/rfq_main/rfqrfbmain_vendor_table";
        var post_params = $('#frm_rfqrfb_vendor_main').serialize();

        var success_function = function(responseText)
        {
            var tbl_data = $.parseJSON(responseText);

            if (tbl_data.resultscount > 0)
            {
            	(tbl_data.query).map(function(row_obj) {
					if(row_obj.ISPROCESS === '1') {
						row_obj.ISPROCESS = true;
					}
					else if (row_obj.ISPROCESS === '0') {
						row_obj.ISPROCESS = false;
					}
				});
            }

			rfq_pagination.create(tbl_data.query, 'table_template');
			rfq_pagination.sort_rows((rfq_pagination.get_sort_column() ? rfq_pagination.get_sort_column() : 'DATE_CREATED'), rfq_pagination.get_sort_type());
			//rfq_pagination.render();

            // var DATA = {
                // table_template: tbl_data.query
            // }

            // $('#tbl_body').html(Mustache.render(tbl_template, DATA));

           // $('#tbl_pag').html(responseText);
		   
		   loadingScreen('off');
        };

        ajax_request(ajax_type, url, post_params, success_function);
	}

	get_main_tbl();
</script>