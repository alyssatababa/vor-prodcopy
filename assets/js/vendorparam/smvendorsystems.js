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
		$('<tr id="row'+y+'" class="clickable-row"><td id="smvs_name'+y+'">'+obj_result[x].SM_SYSTEM_ID+'</td><td id="description'+y+'">'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].TOOL_TIP+'</td><td id="bus_division'+y+'">'+obj_result[x].TRADE_VENDOR_TYPE_DESC+'</td><td id="smvs_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_CREATED +'</span>'+obj_result[x].DATE_CREATED+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default btn_edit_smvs" rel = "'+obj_result[x].SM_SYSTEM_ID+'|'+obj_result[x].DEPARTMENT_ID+'|'+obj_result[x].DESCRIPTION+'|'+obj_result[x].TOOL_TIP+'|'+obj_result[x].TRADE_VENDOR_TYPE+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].SM_SYSTEM_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_delete_smvs"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}

$(".btn_add_smvs").click(function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

        $("#department").val("0");
        $("#smvs_name").val("");
        $("#smvs_tool_tip").val("");
        $("#trade_vendor_type").val('');

		$('#modal_smvs').modal('show');
		$('#modal_smvs .edit_smvs').hide();
		$('#modal_smvs .add_smvs').show();

		$('#modal_smvs').val("1");
    }
});

$(".frm-vendor #btn_save_smvs").click(function(){
	
	var btn_save = $(this);
	btn_save.prop('disabled',true);
	var department = $("#department").val();
	var smvs_name = $("#smvs_name").val();	
	var smvs_tool_tip = $("#smvs_tool_tip").val();
	var trade_vendor_type = $("#trade_vendor_type").val();	

	var base_url = $('#b_url').attr('data-base-url');
	if(isFillUpError_smvs() == 0){
	    	$.ajax({
			type:'POST',
			data:{department: department, smvs_name:smvs_name, smvs_tool_tip:smvs_tool_tip, trade_vendor_type:trade_vendor_type},
			url: base_url + "save_smvs",
			success: function(result){
				if(result == 'duplicate'){
						modal_notify('modal_smvs','<strong>Failed! </strong> SM System already exist!','danger');
				}else{
					var json_result = $.parseJSON(result);
					$('#modal_smvs').modal('hide');
						
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

$("#btn_search_smvs").click(function()
{	
	var smvsdesc = $("#search_smvs").val();
	if (smvsdesc==""){
		getSMVS("all");
	}else{
		getSMVS(smvsdesc);
	}
		
});

function isFillUpError_smvs()
{
	var iError = 0;
	
	$('#modal_smvs .frm-vendor .field-required').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}else{
			$('#'+ this.id).parent('div').removeClass('has-error');	
		}	
	});	
	
	if(iError > 0){
		modal_notify('modal_smvs','<strong>Failed! </strong> Please fill up required fields.','danger');
	}

	return iError;
}

function getSMVS(smvsdesc)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(smvsdesc == "all"){
		base_url = base_url + "get_all_data";
	}else{
		base_url = base_url + "get_smvs";
	}

	$.ajax({
		type:'POST',
		data:{smvsdesc: smvsdesc},
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

$(document).on("click", ".btn_edit_smvs", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that thEEEEEEEe current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var sm_vendor_sys_dtl = ($(this).attr('rel')).split('|');

		$('#modal_smvs').modal('show');
		$('#modal_smvs .add_smvs').hide();
		$('#modal_smvs .edit_smvs').show();

    	$('#smvs_id').val(sm_vendor_sys_dtl[0]);
    	$('#department').val(sm_vendor_sys_dtl[1]);
    	$('#smvs_name').val(sm_vendor_sys_dtl[2]);
    	$('#smvs_tool_tip').val(sm_vendor_sys_dtl[3]);
    	$('#trade_vendor_type').val(sm_vendor_sys_dtl[4]);
    }
});


$(".frm-vendor #btn_update_smvs").click(function(){
	var smvs_id = $("#smvs_id").val();
	var department = $("#department").val();
	var smvs_name = $("#smvs_name").val();	
	var smvs_tool_tip = $("#smvs_tool_tip").val();
	var trade_vendor_type = $("#trade_vendor_type").val();	

	var base_url = $('#b_url').attr('data-base-url');
	$.ajax({
	type:'POST',
	data:{smvs_id:smvs_id, department: department, smvs_name:smvs_name, smvs_tool_tip:smvs_tool_tip, trade_vendor_type:trade_vendor_type },
	url: base_url + "update_smvs",
	success: function(result){	
			if(result == 'duplicate'){
				modal_notify('modal_smvs','<strong>Failed! </strong> SM System already exist!','danger');
			}else{
				var json_result = $.parseJSON(result);
				$('#modal_smvs').modal('hide');
					
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

$(document).on("click", ".btn_delete_smvs", function (e) {
    $div_notifications.stop().fadeOut("slow", clean_div_notif);
    var sm_vendor_id = $(this).attr('rel');

	var span_message = 'Are you sure you want to deactivate record?';
		span_message = span_message + '&nbsp;<button type="button" class="btn btn-success btn_yes_smvs" rel = "'+sm_vendor_id+'">Yes</button>';
		span_message = span_message + '&nbsp;<button id = "btn_no_smvs" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		span_message = span_message + "<script>$('.btn_yes_smvs').click(function(e){ remove_smvs($(this).attr('rel')); }); </script>";
	var type = 'info';

	// c = $(span_message);
	notify(span_message, type, true);	
});

// . = class,  # = id

function remove_smvs(smvs_id){
	var base_url = $('#b_url').attr('data-base-url');
	$.ajax({
	type:'POST',
	data:{smvs_id:smvs_id},
	url: base_url + "remove_smvs",
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