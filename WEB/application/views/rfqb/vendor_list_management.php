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

.btn_min_width
{
	min-width: 100px;
}
</style>
<?=form_open('form1', array('name' => 'form1'))?>
<!-- Modal -->

 	<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="padding: 10% 0 0 0">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal4">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                  <center>
                    <button type="button" class="btn btn-default btn-xs btn_min_width" align="center" onclick="validate_update_vendor_list()">Save</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width" align="center">Cancel</button>
                  </center>
                  </div>
             </div>
        </div>
	</div>

 	<div class="modal fade" id="myModal3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="padding: 10% 0 0 0">
             <div class="modal-content">
                  <!-- <div class="modal-body" style="padding: 0 0 0 0"> -->
                       <div id="view_modal3">                                     
                       </div>
                  <!-- </div> -->
                  <div class="modal-footer">
                  <center>
                    <button type="button" class="btn btn-default btn-xs btn_min_width" align="center" onclick="validate_save_vendor_list(3)">Save</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width" align="center">Cancel</button>
                  </center>
                  </div>
             </div>
        </div>
	</div>


<!-- END OF MODAL -->

	<div class="container mycontainer" id="mycontainer">
	<div class="row">
		<div class="col-md-4">
			<h4>Vendor List</h4>
		</div>
		<div class="col-md-offset-10">
			<input type="button" class="btn btn-primary btn-sm" value="Close" onclick="go_to_homepage()">
		</div>
	</div>

	<hr>
<div class="form_container">
<div class="panel panel-default" style="overflow-x: hidden;">
<div class="panel-body">
	<!-- VENDOR LIST NAME SEARCHING -->
	<div class="row">

		<div class="col-md-10">
			<div class="form-horizontal">
				<div class="form-group">
					<label for="title" class="col-md-4 control-label">Vendor List Name: </label>
					<div class="col-md-8">
						<input type="text" class="form-control field-required" id="vendor_list_name" name="vendor_list_name" placeholder="">
					</div>
				</div>
			</div>
		</div>
	</div>
	<br>
	<!-- button for search and clear -->
	<div class="row">

		<div class="col-md-12">
			<div class="form-horizontal">
				<div class="form-group" align="center">
					<input type="button" value="Search" class="btn btn-primary" style="width: 100px;" onclick="search_vendor_list()">
					<input type="button" value="Clear" class="btn btn-warning" style="width: 100px;" onclick="clear_name()">
				</div>
			</div>
		</div>
	</div>
	<!-- end of button -->
	<br>
	<br>


	<div class="row">

		<div class="col-md-offset-10">
			<div class="form-horizontal">
				<div class="form-group" align="center">
					<input type="button" value="Add" class="btn btn-primary" onclick="add_vendor_list()" style="width: 100px;">
				</div>
			</div>
		</div>
	</div>

	<hr>

	<!-- table -->
		<?=$table?>
	<!-- end of table -->
	<hr>

	</div>
	</div>
	</div>


</div>

<?=form_close()?>
<script>
function vendor_search()
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
			document.getElementById('search_result_table').innerHTML = xmlhttp.responseText;
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/search_vendor';
	parameter = 'name_vendor='+encodeURIComponent(document.getElementById('name_vendor').value);

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function clear_name()
{
	document.getElementById('vendor_list_name').value = '';
}

function search_vendor_list()
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
			document.getElementById('table_vendor_list').innerHTML = xmlhttp.responseText;
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/search_vendor_list';

	parameter = 'list_name='+document.getElementById('vendor_list_name').value;

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function edit_vendor_list(value)
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			$('#myModal4').modal('show');   
            $("#view_modal4").html(xmlhttp.responseText);
		}
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/view_vendor_list';

	parameter = 'value='+value;

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function add_vendor_list()
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			$('#myModal3').modal('show');   
            $("#view_modal3").html(xmlhttp.responseText);
		}
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/search_vendor_add_list';

	parameter = '';

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function remove_vendor_invite(row)
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById("modal_table_vendor_list").innerHTML = xmlhttp.responseText;
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/remove_vendor_invite';

	parameter = 'row='+row;
	parameter += '&total_vendor_count='+$('#total_vendor_count').val();
	parameter += '&total_left_count='+$('#total_left_count').val();
	parameter += '&vendor_list_id='+$('#vendor_list_id').val();

	for(i=1; i <= $('#total_vendor_count').val();i++)
	{
		parameter += '&invite_id'+i+'='+$('#invite_id'+i).val();
		parameter += '&invite_name'+i+'='+$('#invite_name'+i).val();
	}

	//console.log(parameter);

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function forward_selected()
{
	if(window.XMLHttpRequest)
		xmlhttp = new XMLHttpRequest();
	else
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

	xmlhttp.onreadystatechange = function()
	{
		if(xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById("forwarded_result_table").innerHTML = xmlhttp.responseText;
	}

	path_value = BASE_URL + 'rfqb/vendor_list_maintenance/forward_selected';

	parameter = 'total_rows='+$('#total_rows').val();

	var j =0;

	for(i=1; i <= $('#total_rows').val();i++)
	{
		if(parseInt($('#checkbox_invite_vendor'+i).val()) == 1)
		{
			j++;
			parameter += '&rs_invite_id'+j+'='+$('#rs_invite_id'+i).val();
			parameter += '&rs_vendor_id'+j+'='+$('#rs_vendor_id'+i).val();
			parameter += '&rs_vendor_name'+j+'='+encodeURIComponent($('#rs_vendor_name'+i).val());
		}
	}

	if(parseInt($('#vendor_list_total').val()) > 0)
	{
		for(x=1; x <= $('#vendor_list_total').val();x++)
		{
			parameter += '&vendorinvitefinal_id'+x+'='+$('#vendorinvitefinal_id'+x).val();
			parameter += '&vendorfinal_invite_id'+x+'='+$('#vendorfinal_invite_id'+x).val();
			parameter += '&vendorname_finalinvited'+x+'='+encodeURIComponent($('#vendorname_finalinvited'+x).val());
		}
	}

	parameter += '&vendor_list_total='+$('#vendor_list_total').val();
	parameter += '&total_checked='+j;

	//console.log(parameter);

	xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

</script>
