<style>
	#main_table tr td{
		word-break: break-all;
	}

</style>

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
						<div class="form-inline">
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">Vendor Name</label>
								<div class="col-md-7 no-padding">
									<input type="text" class="form-control w-100" id="txt_vendorname" name="txt_vendorname" value="" placeholder="Search Vendor Name">
								</div>
							</div>
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">Vendor Type</label>
								<div class="col-md-7 no-padding">
									<input type="hidden" name="default_vendor_type" id="default_vendor_type" value="<?=$business_type?>">
								    <select name="vendor_type_filter" id="vendor_type_filter" class="form-control"  style="width:100%;">
				                    	<option value="" selected>--Select--</option>
				                    	<option value="1.1" >Trade - Outright</option>
				                    	<option value="1.2" >Trade - Consignor</option>
				                    	<option value="2" >Non Trade</option>
				                    	<option value="3" >Non Trade Service</option>
									</select>
								</div>
							</div>
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">TIN</label>
								<div class="col-md-7 no-padding">
									<input type="text" class="form-control w-100" id="txt_tinno" name="txt_tinno" value="" placeholder="Search Tin No.">
								</div>
							</div>
						</div>
						<div class="form-inline">
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">Authorized Representative</label>
								<div class="col-md-7 no-padding">
									<input type="text" class="form-control" id="txt_auth_rep" name="txt_auth_rep" value="" placeholder="Search Authorized Rep" style="width:100%;">
								</div>
							</div>
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">Brand</label>
								<div class="col-md-7 no-padding">


									<div id="filter_brand_cbo" class="input-group w-100">
										<input type="hidden" class="form-control input-sm cls_brand" id="cbo_brand" name="cbo_brand" >
										<input type="text" class="form-control auto_suggest cls_brand_cbo w-100" list-container="brand_list" id="cbo_brand_text" name="cbo_brand_text">
								<!-- 		<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="cbo_brand_text" >
												<span class="glyphicon glyphicon-search"></span>
											</button>
										</div> -->
									</div>
									<?=form_dropdown('brand_list', $brand_array, '', ' id="brand_list" class="btn toggle-dropdown btn-default form-control " style="display:none"')?>
<!-- 

									<select name="cbo_brand" id="cbo_brand" class="form-control" style="max-width: 200px">
				                    	<option value="" selected>--Select--</option>
										<?php //foreach ($filter_brand as $row){
												//	echo '<option value="'.$row->BRAND_ID.'">'.$row->BRAND_NAME.'</option>';
													//}?>
									</select> -->
								</div>
							</div>
							<div class="form-group col-sm-6 col-lg-4 margin-inline">
								<label class="col-md-5 no-padding">Status</label>
								<div class="col-md-7 no-padding">
									<select name="cbo_status" id="cbo_status" class="form-control" style="width:100%;">
										<option value="" selected>--Select--</option>
				                    	<?php foreach ($filter_status as $row){
													echo '<option value="'.$row->STATUS_ID.'">'.$row->STATUS_NAME.'</option>';
													}?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="pull-right">
								<br>
								<button type="button" class="btn btn-primary" id="btn_clear_rmain">Clear</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-primary" id="btn_search_rm"><span class="glyphicon glyphicon-search"></span> Search</button>
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
						<div class="col-xs-10 col-sm-10">
							<h3 class="panel-title line-height-30">Vendor Registration</h3>
						</div>
						<div class="col-xs-2 col-sm-2">
							<div class="pull-right">
							<?php
								if($this->session->userdata('position_id') == 2 || $this->session->userdata('position_id') == 7){
							?>
								<button id="invite_btn" class="btn btn-default cls_action" data-action-path="vendor/invitecreation" data-crumb-text="Invite Creation">Invite</button>
							<?php
							}
							?>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive" id = "tbl_vendor_registration">
					<table id="main_table" class="table table-hover" style="width: 100%;">
						<thead>
													<tr>
							<th style="max-width: 300px;width: 300px;word-wrap: break-word;" data-col='VENDOR_NAME' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="VENDOR_NAME"><label>Vendor &nbsp;</label><span></span></a>
							</th>
							<th data-col='STATUS_NAME' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="STATUS_NAME"><label>Status &nbsp;</label><span></span></a></th>
							<th data-col='MESSAGE_COUNT' class = "a_table_header sort_column sort_default">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="MESSAGE_COUNT" style = "min-width:200px;"><label>Unread Messages &nbsp;</label><span></span></a>
							</th>
							<th data-col='DATE_SORTING_FORMAT' class = "a_table_header sort_column sort_default" style = "min-width:200px;">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="DATE_CREATED"><label>Date Invited &nbsp;</label><span></span></a>
							</th> <!-- Old data-col DATE_CREATED-->
							<th data-col='DATE_SORTING_REVIEWED_FORMAT' class = "a_table_header sort_column sort_default" style = "min-width:200px;">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="DATE_SUBMITTED"><label>Date Submitted &nbsp;</label><span></span></a>
							</th><!-- Old data-col DATE_SUBMITTED-->
							<th data-col='ACTION_LABEL' class = "a_table_header sort_column sort_default" style = "max-width:200px; min-width: 100px;">
								<a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type ="ACTION_LABEL"><label>Action &nbsp;</label><span></span></a>
							</th>
						</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
					<center><div id="vendor_reg_pagination"></div></center>
				</div>
					

			</div>
		</div>
	</div>
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

</div>



<script type="text/javascript">
	
	vendor_sort = 'ASC';
	vendor_sort_type = 'DATE_SORTING_FORMAT';
	var brand_id = '';

	$(document).on('click','#btn_search_rm',function(event){
		get_vendor_table(0,'main_table','vendor_reg_pagination',create_postparams());
		event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
	});

	$(document).on('change','#brand_list',function(){
		brand_id = this.value;
	});

	function create_postparams(){


		if(document.getElementById('cbo_brand_text').value == ''){
			brand_id = '';
		}

		let data = {
			txt_vendorname: document.getElementById('txt_vendorname').value.trim(),
			vendor_type_filter : document.getElementById('vendor_type_filter').value,
			txt_tinno : document.getElementById('txt_tinno').value,
			txt_auth_rep : document.getElementById('txt_auth_rep').value,
			cbo_brand :  brand_id,
			cbo_brand_text :  document.getElementById('cbo_brand_text').value,
			cbo_status :  document.getElementById('cbo_status').value,
			sort_type : vendor_sort_type,
			sort : vendor_sort
		}
		return data;
	}

	$(document).on('click','#vendor_reg_pagination .cl_pag',function(event){
		let n = this.dataset.pg;
	if($(this).attr('disabled') == 'disabled'){
		return;
	}
		get_vendor_table(n-1,'main_table','vendor_reg_pagination',create_postparams());
	});

	$(document).on('click','#main_table .a_table_header a',function(e){

		e.preventDefault();
		 if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		        e.handled = true;

			 vendor_sort = $(this).data('sort');
			 vendor_sort_type = $(this).data('sort_type');
			 let n = this;
			 let l = $(this).closest('th');

			 $('#main_table .a_table_header').each(function(){

			
	 		let x = $(this);

	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');

			 });

			 if(n.dataset.sort == 'desc'){
			 	n.dataset.sort = 'ASC';
			 	$(n).data('sort','ASC');
				$(l).removeClass('sort_column sort_default');
			 	$(l).addClass('sort_column sort_asc');
			 }else{
			 	n.dataset.sort = 'desc';
			 	$(n).data('sort','desc');
			 	$(l).removeClass('sort_column sort_default');
			 	$(l).addClass('sort_column sort_desc');
			 }

			 let m = create_postparams();

			 get_vendor_table(0,'main_table','vendor_reg_pagination',m);
			}
			e.stopImmediatePropagation();

});


	$(document).ready(function(){

	let mx = create_postparams();
	get_vendor_table(0,'main_table','vendor_reg_pagination',mx);

	});


</script>

<!-- <script type="text/javascript">
	loadingScreen('on');
	$.getScript("<?php echo base_url().'assets/js/vendor.js'?>");

	var $invite_btn = $('#invite_btn');
	var $main_table = $('#main_table');
	var $pages = $('#vendor_reg_pagination');

	if (<?=$user_type_id?> == 2) {
		$invite_btn.hide();
	}

	var vendor_reg_pagination = new Pagination($pages, $main_table, 'sort_columns');
	function get_main_tbl()
	{
		// var brand_id = $('#cbo_brand').val();
		// console.log(brand_id);
		var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/registrationmain_table";
        var post_params = $('#frm_registration_main').serialize();

        var success_function = function(responseText)
        {
        	console.log(responseText);
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

			vendor_reg_pagination.create(tbl_data.query, 'table_template');
			vendor_reg_pagination.sort_rows((vendor_reg_pagination.get_sort_column() ? vendor_reg_pagination.get_sort_column() : 'DATE_SORTING_FORMAT'), vendor_reg_pagination.get_sort_type());
			vendor_reg_pagination.render(); //DATE_CREATED = DATE_SORTING_FORMAT

            var DATA = {
                table_template: tbl_data.query
            }

            $('#tbl_body').html(Mustache.render(tbl_template, DATA));



           $('#tbl_pag').html(responseText);
		   loadingScreen('off');
        };

        ajax_request(ajax_type, url, post_params, success_function);
	}

	get_main_tbl();

</script>
 -->