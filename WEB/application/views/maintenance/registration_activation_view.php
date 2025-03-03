<div class="container mycontainer"> 
	<div class="row">
		<div class="col-md-12">
			<form id="frm_registration_main" method="post">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">
							Filter By
						</h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-2">
								<label class="col-md-12">Vendor Name</label>
							</div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="vendorname" id="vendorname" list="vendornamelist">
								<datalist id="vendornamelist">
								  	<?php foreach ($vendor_data->query as $row){
										echo '<option data-vendorid="'.$row->VENDOR_INVITE_ID.'" value="'.$row->VENDOR_NAME.'">';
									}?>	
								</datalist>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="form-group col-md-2">
								<label class="col-md-12">Vendor Code</label>
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control" name="vendorcode" id="vendorcode" list="vendorcodelist">
								<datalist id="vendorcodelist">
								  	<?php foreach ($vendor_data->query as $row){
										echo '<option data-vendorid="'.$row->VENDOR_INVITE_ID.'" value="'.$row->VENDOR_CODE.'">';
									}?>	
								</datalist>
							</div>
							<div class="col-md-2">
								<button type="button" class="btn btn-primary" id="btn_search_rm"><span class="glyphicon glyphicon-search"></span> Search</button>
							</div>
						</div>

					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-4">
							<h3 class="panel-title">Vendor</h3>
						</div>
						<div class="col-md-8">
							<div class="pull-right">
								<input type="button" class="btn btn-default" id="activate_registrations" value="Activate" style="width: 130px;">
							</div>
						</div>
					</div>
				</div>
				<input type="hidden" name="numselected" id="numselected" value=0>
				<input type="hidden" name="total_results" id="total_results" value=0>
					<table id="main_table" class="table table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th data-col="VENDOR_NAME">Vendor</th>
								<th data-col="VENDOR_CODE">Code</th>
								<th data-col="DEACTIVATION_DATE">Deactivation Date</th>
								<th data-col="ACTIVATE">Activate</th>
							</tr>
						</thead>
						<tbody id="tbl_body">
							<script id="tbl_template" type="text/template">
								{{#table_template}}
									<tr>
										<td><input type="hidden" name="vendorinviteid{{ROWNUM}}" id="vendorinviteid{{ROWNUM}}" value="{{VENDOR_INVITE_ID}}">
											<input type="hidden" name="vendorchecked{{ROWNUM}}" id="vendorchecked{{ROWNUM}}" value="0">{{{VENDOR_NAME}}}</td>
										<td>{{VENDOR_CODE}}</td>
										<td>{{DEACTIVATION_DATE}}</td>
										<td><input type="checkbox" name="checkbox{{ROWNUM}}" id="checkbox{{ROWNUM}}" onchange="invitecheck(this.checked, {{ROWNUM}}, 'vendorchecked');countselected(this.checked);"></td>
									</tr>
								{{/table_template}}
								{{^table_template}}
									<tr>
										<td colspan="4">No Records found.</td>
									</tr>
								{{/table_template}}
							</script>
						</tbody>
					</table>

					<center><div id="vendor_activation_pagination"></div></center>

			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	//loadingScreen('on');  
	$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");

	var $main_table = $('#main_table');
	var $pages = $('#vendor_activation_pagination');


	var vendor_activation_pagination = new Pagination($pages, $main_table, 'sort_columns');
	function get_main_tbl()
	{
		var ajax_type = 'POST';
        var url = BASE_URL + "maintenance/registration_activation/activationmain_table";
        var post_params = $('#frm_registration_main').serialize();

        post_params += '&encoded_vendorname='+encodeURIComponent($('#vendorname').val());
        post_params += '&encoded_vendorcode='+encodeURIComponent($('#vendorcode').val());
        var success_function = function(responseText)
        {
  	
  			console.log(responseText);
  			//return;
            var tbl_data = $.parseJSON(responseText);

            if (tbl_data.resultscount > 0)
            {
            	$('#total_results').val(tbl_data.resultscount);
            	(tbl_data.query).map(function(row_obj) {
					if(row_obj.ISPROCESS === '1') {
						row_obj.ISPROCESS = true;
					}
					else if (row_obj.ISPROCESS === '0') {
						row_obj.ISPROCESS = false;
					}
				});
            }

			vendor_activation_pagination.create(tbl_data.query, 'table_template');
			vendor_activation_pagination.sort_rows((vendor_activation_pagination.get_sort_column() ? vendor_activation_pagination.get_sort_column() : 'DEACTIVATION_DATE'), vendor_activation_pagination.get_sort_type());
			//vendor_activation_pagination.render();

		   	loadingScreen('off');
        };

        ajax_request(ajax_type, url, post_params, success_function);
	}

	function countselected(ischecked)
	{
		if(ischecked)
			$('#numselected').val(parseInt($('#numselected').val()) + 1);
		else
			$('#numselected').val(parseInt($('#numselected').val()) - 1);
	}

	get_main_tbl();
	
</script>