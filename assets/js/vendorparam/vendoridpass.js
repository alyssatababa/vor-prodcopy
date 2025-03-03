$(document).ready(function(){
	
	var base_url = $('#b_url').attr('data-base-url');
	getAllData();
});	

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	$("#tbl_view").find("tr:gt(0)").remove();
	
	for(x=0;x<z;x++){
		y=x+1;
		$('<tr id="row'+y+'" class="clickable-row"><td id="request_type_id'+y+'">'+obj_result[x].ID+'</td><td id="request_type_name'+y+'">'+obj_result[x].REQUEST_TYPE_NAME+'</td><td id="request_type_code'+y+'">'+obj_result[x].REQUEST_TYPE_CODE+'</td><td id="description'+y+'">'+obj_result[x].DESCRIPTION+'</td><td id="date_created'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_CREATED +'</span>'+obj_result[x].DATE_CREATED+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default btn_edit_vendor_id_pass" rel = "'+obj_result[x].ID+'|'+obj_result[x].REQUEST_TYPE_NAME+'|'+obj_result[x].REQUEST_TYPE_CODE+'|'+obj_result[x].DESCRIPTION+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_delete_vendor_id_pass"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}

$(".btn_add_vendor_id_pass").click(function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

        $("#id").val("");
        $("#request_type_name").val("");
        $("#request_type_code").val("");
        $("#description").val('');

		$('#modal_vendor_id_pass').modal('show');
		$('#modal_vendor_id_pass .edit_vendor_id_pass').hide();
		$('#modal_vendor_id_pass .add_vendor_id_pass').show();

		$('#modal_vendor_id_pass').val("1");
    }
});

$(".frm-vendor #btn_save_vendor_id_pass").click(function(){
	
	var btn_save = $(this);
	btn_save.prop('disabled',true);
	var request_type_name = $("#request_type_name").val();	
	var request_type_code = $("#request_type_code").val();	
	var description = $("#description").val();

	var base_url = $('#b_url').attr('data-base-url');
	if(isFillUpError_vendor_id_pass() == 0){
	    	$.ajax({
			type:'POST',
			data:{request_type_name: request_type_name, request_type_code: request_type_code, description:description},
			url: base_url + "save_vendor_id_pass",
			success: function(result){
				if(result == 'duplicate'){
						modal_notify('modal_vendor_id_pass','<strong>Failed! </strong> Vendor ID/Pass Request Type already exist!','danger');
				}else{
					var json_result = $.parseJSON(result);
					$('#modal_vendor_id_pass').modal('hide');
						
					if (json_result.status != false){
						var span_message = '<strong>Success! </strong> Record has been successfully saved!';
						var type = 'success';
						notify(span_message, type);	
					}else{
						var span_message = '<strong>Failed! </strong> Unable to save record';
						var type = 'danger';
						notify(span_message, type);	
					}
					getAllData();
					btn_save.prop('disabled',false);
				}
				
			}		
		}).fail(function(){
			
		});	
	}
});

$("#btn_search_vendor_id_pass").click(function()
{	
	var vendor_id_pass_desc = $("#search_vendor_id_pass").val();
	if (vendor_id_pass_desc==""){
		get_vendor_id_pass("all");
	}else{
		get_vendor_id_pass(vendor_id_pass_desc);
	}
		
});

function isFillUpError_vendor_id_pass()
{
	var iError = 0;
	
	$('#modal_vendor_id_pass .frm-vendor .field-required').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}else{
			$('#'+ this.id).parent('div').removeClass('has-error');	
		}	
	});	
	
	if(iError > 0){
		modal_notify('modal_vendor_id_pass','<strong>Failed! </strong> Please fill up required fields.','danger');
	}

	return iError;
}

function get_vendor_id_pass(vendor_id_pass_desc)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(vendor_id_pass_desc == "all"){
		base_url = base_url + "get_all_data";
	}else{
		base_url = base_url + "get_vendor_id_pass";
	}

	$.ajax({
		type:'POST',
		data:{vendor_id_pass_desc: vendor_id_pass_desc},
		url: base_url,
		success: function(result){
			var json_result = $.parseJSON(result);
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

function getAllData()
{
	var base_url = $('#b_url').attr('data-base-url');
	
	base_url = base_url + "get_all_data";
	
	$.ajax({
		type:'POST',
		url: base_url,
		success: function(result){	
			var json_result = $.parseJSON(result);
			
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

$(document).on("click", ".btn_edit_vendor_id_pass", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that thEEEEEEEe current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var sm_vendor_sys_dtl = ($(this).attr('rel')).split('|');

		$('#modal_vendor_id_pass').modal('show');
		$('#modal_vendor_id_pass .add_vendor_id_pass').hide();
		$('#modal_vendor_id_pass .edit_vendor_id_pass').show();

    	$('#request_type_id').val(sm_vendor_sys_dtl[0]);
    	$('#request_type_name').val(sm_vendor_sys_dtl[1]);
    	$('#request_type_code').val(sm_vendor_sys_dtl[2]);
    	$('#description').val(sm_vendor_sys_dtl[3]);
    	$('#date_created').val(sm_vendor_sys_dtl[4]);
    }
});


$(".frm-vendor #btn_update_vendor_id_pass").click(function(){
	var request_type_id = $("#request_type_id").val();
	var request_type_name = $("#request_type_name").val();
	var request_type_code = $("#request_type_code").val();
	var description = $("#description").val();	
	var date_created = $("#date_created").val();

	var base_url = $('#b_url').attr('data-base-url');
	$.ajax({
	type:'POST',
	data:{request_type_id:request_type_id, request_type_name: request_type_name, request_type_code:request_type_code, description:description, date_created:date_created},
	url: base_url + "update_vendor_id_pass",
	success: function(result){	
			if(result == 'duplicate'){
				modal_notify('modal_vendor_id_pass','<strong>Failed! </strong> Vendor ID/Pass Request Type already exist!','danger');
			}else{
				var json_result = $.parseJSON(result);
				$('#modal_vendor_id_pass').modal('hide');
					
				if (json_result.status != false){
					var span_message = '<strong>Success! </strong> Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					var span_message = '<strong>Failed! </strong> Unable to save record';
					var type = 'danger';
					notify(span_message, type);	
				}
				getAllData();
			}
		}		
	}).fail(function(){
		
	});	
});

$(document).on("click", ".btn_delete_vendor_id_pass", function (e) {
    $div_notifications.stop().fadeOut("slow", clean_div_notif);
    var request_type_id = $(this).attr('rel');

	var span_message = 'Are you sure you want to delete a record?';
		span_message = span_message + '&nbsp;<button type="button" class="btn btn-success btn_yes_vendor_id_pass" rel = "'+request_type_id+'">Yes</button>';
		span_message = span_message + '&nbsp;<button id = "btn_no_vendor_id_pass" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		span_message = span_message + "<script>$('.btn_yes_vendor_id_pass').click(function(e){ remove_vendor_id_pass($(this).attr('rel')); }); </script>";
	var type = 'info';

	// c = $(span_message);
	notify(span_message, type, true);	
});

// . = class,  # = id

function remove_vendor_id_pass(request_type_id){
	var base_url = $('#b_url').attr('data-base-url');
	$.ajax({
	type:'POST',
	data:{request_type_id:request_type_id},
	url: base_url + "remove_vendor_id_pass",
	success: function(result){	
		var json_result = $.parseJSON(result);				
			if (json_result.status != false){
				var span_message = '<strong>Success! </strong> Record has been successfully saved!';
				var type = 'success';
				notify(span_message, type);	
			}else{
				var span_message = '<strong>Failed! </strong> Unable to save record';
				var type = 'danger';
				notify(span_message, type);	
			}
			getAllData();
		}		
	}).fail(function(){
		
	});	
}