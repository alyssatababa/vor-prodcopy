var iCount=0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	
	var base_url = $('#b_url').attr('data-base-url');
	//alert(base_url);
	getOrgType("all");
});



// $("#btn_add_orgtype").click(function()
// {
// 	var orgtype_name = $("#orgtype_name").val();
// 	var description = $("#orgtype_description").val();
// 	var bus_division = $("#orgtype_bus_division").val();
	
// 	//alert(name + " " + description + " " + bus_division);

	
// 	if(isFillUpError() == 0){
// 		addOrgType(orgtype_name, description, bus_division);	
// 	}
	
// });

$(document).on("click", ".btn_add_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

		$('#modal_orgtype').modal('show');
		$('#modal_orgtype .edit_orgtype').hide();
		$('#modal_orgtype .add_orgtype').show();

		$('#orgtype_type').val("1");
    }
});

$(document).on("click", "#btn_save_orgtype",function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var orgtype_id = $("#orgtype_id").val();
		var orgtype_name = $("#orgtype_name").val();
		var description = $("#orgtype_description").val();
		var bus_division = $("#orgtype_bus_division").val();
		var orgtype_type = $('#orgtype_type').val();

		if(isFillUpError_orgtype() == 0){
			if (orgtype_type == "1"){
				addOrgType(orgtype_name, description, bus_division);	
			}else if (orgtype_type == "2"){
				var orgtype_id = $("#orgtype_id").val();
				saveOrgType(orgtype_id, orgtype_name, description, bus_division)
			}	
		}
	}
});

function isFillUpError_orgtype()
{
	var iError = 0;
	
	$('#modal_orgtype').find('.field-required').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}else{
			$('#'+ this.id).parent('div').removeClass('has-error');	
		}	
	});	
	
	if(iError > 0){
		modal_notify('modal_orgtype','<strong>Failed! </strong> Please fill up required fields.','danger');
	}

	return iError;
}

function addOrgType(orgtype_name, description, bus_division)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{orgtype_name: orgtype_name, description: description, bus_division: bus_division},
		url: base_url + "add_orgtype",
		success: function(result){	
			//alert(result);

			//$('#modal_add_orgtype').modal('hide');
			
			var json_result = $.parseJSON(result);		
			setTimeout(function(){
				if (json_result.status != false){
					//modal_notify('modal_orgtype','<strong>Success! </strong> Record has been successfully saved.','success');
					$('#modal_orgtype').modal('hide');
					var span_message = 'Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_orgtype','<strong>Failed! </strong> Unable to save record.','danger');
				}

				//var span_message = 'Record has been successfully saved!';
				//var type = 'success';
				//notify(span_message, type);					
				
				getOrgType("all");
			}, 900);
		}		
	}).fail(function(){
		
	});	
}

function saveOrgType(orgtype_id, orgtype_name, description, bus_division)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	//alert(base_url);
	$.ajax({
		type:'POST',
		data:{orgtype_id: orgtype_id, orgtype_name: orgtype_name, description: description, bus_division: bus_division},
		url: base_url + "save_orgtype",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_orgtype').modal('hide');
			
			//var span_message = 'Currency has been successfully saved!';
			//var type = 'success';
			//notify(span_message, type);			
			var json_result = $.parseJSON(result);		
			//console.log($.parseJSON(result));
			setTimeout(function(){
				$('#modal_orgtype').modal('hide');
					
				if (json_result.status != false){
					//modal_notify('modal_orgtype','<strong>Success! </strong> Record has been successfully saved.','success');
					var span_message = '<strong>Success! </strong> Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					//modal_notify('modal_orgtype','<strong>Failed! </strong> Unable to save record.','danger');
					var span_message = '<strong>Failed! </strong> Unable to save record';
					var type = 'danger';
					notify(span_message, type);	
				}

				getOrgType("all");
			}, 900);
		}		
	}).fail(function(){
		
	});		
}

$('#modal_orgtype').on('show.bs.modal', function () {
	$('#modal_orgtype').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	$('#orgtype_bus_division').val('0');
})

$("#btn_search_orgtype").click(function()
{	
	var orgtype = $("#search_orgtype").val();

	if (orgtype==""){
		getOrgType("all");
	}else{
		getOrgType(orgtype);
	}
		
});

function getOrgType(orgtype)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(orgtype == "all"){
		base_url = base_url + "get_all_orgtype";
	}else{
		base_url = base_url + "get_orgtype";
	}
	
	$.ajax({
		type:'POST',
		data:{orgtype: orgtype},
		url: base_url,
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			//alert(json_result.status);
			
			if (json_result.status != false){
				$("#tbl_view").DataTable().destroy();
				createDataTable(json_result);
				$("#tbl_view").DataTable({
					dom:'<"top tbl_temp"t<"clear">>rt<"bottom tbl_temp"p<"clear">>',
				    language: {
				        paginate: {
				            previous: '«',
				            next:     '»'
				        },
				        aria: {
				            paginate: {
				                previous: 'Previous',
				                next:     'Next'
				            }
				        }
				    }
				});
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;
		// $('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="orgtype_name'+y+'">'+obj_result[x].OWNERSHIP_NAME+'</td><td id="description'+y+'">'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].BUS_DIVISION+'</td><td id="orgtype_date'+y+'">'+obj_result[x].OWNERSHIP_DATE+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].OWNERSHIP_ID+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].OWNERSHIP_ID+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="orgtype_name'+y+'">'+obj_result[x].OWNERSHIP_NAME+'</td><td id="description'+y+'">'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].BUS_DIVISION+'</td><td id="orgtype_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_SORTING_FORMAT +'</span>'+obj_result[x].OWNERSHIP_DATE+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default btn_edit_orgtype" rel = "'+obj_result[x].OWNERSHIP_ID+'|'+obj_result[x].OWNERSHIP_NAME+'|'+obj_result[x].DESCRIPTION+'|'+obj_result[x].BUS_DIVISION+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].OWNERSHIP_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_delete_orgtype"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}

$(document).on("click", ".btn_edit_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var orgtype = ($(this).attr('rel')).split('|');

		$('#modal_orgtype').modal('show');
		$('#modal_orgtype .edit_orgtype').show();
		$('#modal_orgtype .add_orgtype').hide();

		$('#orgtype_type').val("2");
    	$('#orgtype_id').val(orgtype[0]);

		$('#orgtype_name').val(orgtype[1]);
		$('#orgtype_description').val(orgtype[2]);
		if (orgtype[3] == "TRADE"){
			$('#orgtype_bus_division').val("1");
		}else if (orgtype[3] == "NON-TRADE"){
			$('#orgtype_bus_division').val("0");
		}
    }
});

$(document).on("click", ".btn_delete_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var orgtype = $(this).attr('rel');

		var span_message = 'Are you sure you want to deactivate record? <button id = "btn_yes_orgtype" type="button" class="btn btn-success" rel = "'+orgtype+'">Yes</button>&nbsp;<button id = "btn_no_orgtype" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		var type = 'info';
		notify(span_message, type, true);	
    }
});

$(document).on("click", "#btn_yes_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
    	var orgtype_id = $(this).attr('rel');
    	deactivateYes(orgtype_id);
	}
});

$(document).on("click", "#btn_no_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 		$div_notifications.stop().fadeOut("slow", clean_div_notif);   	
	}
});

$(document).on("click", "#btn_close_orgtype", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        			 $div_notifications.stop().fadeOut("slow", clean_div_notif);

					$('.alert').css("display","none");
	}
});


function deactivateYesNo(y,orgtype_id)
{
	disable_enable_frm('frm_orgtype', true);
	document.getElementById("btn_full").disabled = true;
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+orgtype_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(orgtype_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	
	$.ajax({
		type:'POST',
		data:{orgtype_id: orgtype_id},
		url: base_url + "deactivate_orgtype",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			// $('#edit'+y).removeClass('disabled');
			// $('#deactivate'+y).removeClass('disabled');
			// $('#row'+y).removeClass('danger')

			disable_enable_frm('frm_orgtype', false);
			
			var span_message = 'Record has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			getOrgType("all");
		}		
	}).fail(function(){
		
	});	
}