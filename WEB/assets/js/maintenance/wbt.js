var iCount=0;
var arr_tmp_tbl = [];
function isUrlValid(url) {
    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
}

$(document).ready(function(){
	var base_url = $('#b_url').attr('data-base-url');
	
	loadingScreen('on');
	getwbt("all");
});

$(document).on("click", ".btn_add_wbt", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

		$('#modal_edit_wbt').modal('show');
		$('#modal_edit_wbt .edit_wbt').hide();
		$('#modal_edit_wbt .add_wbt').show();
		// $('#myModal span').hide();
		// $('#myModal .add_document').show();
		// $('#myModal .document_details').show();
		// $('#myModal .save_document').show();

		$('#edit_wbt_type').val("1");
    }
	e.stopImmediatePropagation();
});


$("#btn_save_wbt").click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		//alert("check");
		var screen_name = $("#edit_screen_name").val();
		var menu_label = $("#edit_menu_label").val();
		var link = $("#edit_link").val();
		var wbt_type = $("#edit_wbt_type").val();
		
		
		//alert(wbt_type);
		if(isFillUpError(wbt_type) == 0){
			if( ! isUrlValid(link)){
				if(link.indexOf('file://') <= -1){		
					modal_notify('modal_edit_wbt','<strong>Failed! </strong> Invalid URL.','danger');
					return;
				}
			}
			if (wbt_type == "1"){
				addwbt(screen_name, menu_label, link);	
			}else if (wbt_type == "2"){
				var wbt_id = $("#edit_wbt_id").val();
				savewbt(wbt_id, screen_name, menu_label, link)
			}	
		}
	}
	e.stopImmediatePropagation();
});

$("#btn_search_wbt").click(function(e)
{	
	var wbt = $("#search_wbt").val();

	loading('btn_search_wbt','in_progress');
	if (wbt==""){
		getwbt("all");
	}else{
		getwbt(wbt, 1);
	}
	e.stopImmediatePropagation();	
});

$('#modal_edit_wbt').on('show.bs.modal', function () {
	$('#modal_edit_wbt').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	//$('#edit_link').val('');
	//var x = document.getElementById('error_add_wbt');
	//x.style.display = 'none';
})

function getwbt(wbt, search_mode = 0)
{
	//alert("he");
	var base_url = $('#b_url').attr('data-base-url');
	
	if(wbt == "all"){
		base_url = base_url + "get_all_wbt";
	}else{
		base_url = base_url + "get_wbt";
	}
	console.log("REL = " + wbt);
	$.ajax({
		type:'POST',
		data:{wbt: wbt},
		url: base_url,
		success: function(result){	
			//console.log(result);
			var json_result = $.parseJSON(result);
			
			if (json_result.status != false){
				$("#tbl_wbt").DataTable().destroy();
				createDataTable(json_result);
				$("#tbl_wbt").DataTable({
					dom:'<"top tbl_wbt"t<"clear">>rt<"bottom tbl_wbt"p<"clear">>',
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
				
				$("#tbl_wbt").DataTable().destroy();
				$("#tbl_body").html("");
				if(search_mode == 1){	
					var span_message = 'Record not found!';
					var type = 'danger';
					notify(span_message, type);	
				}			
			}
			loading('btn_search_wbt','');
			loadingScreen('off');
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
	$("#tbl_wbt").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="screen_name'+y+'">'+obj_result[x].SCREEN_NAME+'</td><td id="menu_label'+y+'" >'+obj_result[x].MENU_LABEL+'</td><td class="td_link" id="link'+y+'"><a href="' + obj_result[x].LINK + '" target="_blank">'+obj_result[x].LINK+'</a></td><td id="upload_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_SORTING_FORMAT +'</span>'+obj_result[x].WBT_DATE+'</td><td><button class = "btn btn-default btn_wbt_edit" rel = "'+obj_result[x].CHILD_SCREEN_ID+'|'+obj_result[x].SCREEN_NAME+'|'+obj_result[x].MENU_LABEL+'|'+obj_result[x].LINK+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-edit g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].CHILD_SCREEN_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_wbt_delete"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}	
}


function zoomimage()
{
	$('#imagepreview').addClass('zoom_in');
}

function zoomoutimage()
{
    $('#imagepreview').removeClass('zoom_in');
}

$(document).on("click", ".btn_wbt_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var wbt = ($(this).attr('rel')).split('|');

		$('#modal_edit_wbt').modal('show');
		$('#modal_edit_wbt .edit_wbt').show();
		$('#modal_edit_wbt .add_wbt').hide();


		$('#edit_wbt_type').val("2");
    	$('#edit_wbt_id').val(wbt[0]);

		$('#edit_screen_name').val(wbt[1]);
		$('#edit_menu_label').val(wbt[2]);
		$('#edit_link').val(wbt[3]);
    }
	e.stopImmediatePropagation();
});

$(document).on("click", ".btn_wbt_delete", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var wbt = $(this).attr('rel');

		//disable_enable_frm('frm_wbt', true);
		//document.getElementById("btn_full").disabled = true;
		
		//$('#row'+y).addClass('danger').siblings().removeClass('danger');

		//$('#edit'+y).addClass('disabled');
		//$('#deactivate'+y).addClass('disabled');

		var span_message = 'Are you sure you want to deactivate record? <button id = "btn_wbt_yes" type="button" class="btn btn-success" rel = "'+wbt+'">Yes</button>&nbsp;<button id = "btn_wbt_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		var type = 'info';
		notify(span_message, type, true);

		// var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+wbt_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
		// var type = 'info';
		// notify(span_message, type, true);	
    }
});

$(document).on("click", "#btn_wbt_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

		var wbt = $(this).attr('rel');

		deactivateYes(wbt);
	}
	e.stopImmediatePropagation();
});

$(document).on("click", "#btn_wbt_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 		$div_notifications.stop().fadeOut("slow", clean_div_notif);
        	
	}
	e.stopImmediatePropagation();
});

$(document).on("click", "#btn_close_wbt", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        			 $div_notifications.stop().fadeOut("slow", clean_div_notif);

					$('.alert').css("display","none");
}
});

function isFillUpError(crud)
{
	//alert("1");
	var iError = 0;
	
		$('#modal_edit_wbt').find('.field-required').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			modal_notify('modal_edit_wbt','<strong>Failed! </strong> Please fill up required fields.','danger');
		}

	return iError;
}

function addwbt(screen_name, menu_label, link)
{
	var base_url = $('#b_url').attr('data-base-url');

/*	console.log(screen_name + '/' + menu_label + '/' + link);
	return;*/

	$.ajax({
		type:'POST',
		data:{screen_name: screen_name, menu_label: menu_label, link: link},
		url: base_url + "add_wbt",
		success: function(result){	
			console.log(result);
			var json_result = $.parseJSON(result);
			
				
			setTimeout(function(){ 
				if (json_result.status != false){
					//modal_notify('modal_edit_wbt','<strong>Success! </strong> Record has been successfully saved.','success');
					$('#modal_edit_wbt').modal('hide');
					var span_message = 'Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_edit_wbt','<strong>Failed! </strong> Unable to save record.','danger');
				}
				getwbt("all");
			}, 900);
		}		
	}).fail(function(){
		
	});	
}

function savewbt(wbt_id, screen_name, menu_label, link)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{wbt_id: wbt_id, screen_name: screen_name, menu_label: menu_label, link: link},
		url: base_url + "save_wbt",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_wbt').modal('hide');
			
			// var span_message = 'Record has been successfully saved!';
			// var type = 'success';
			// notify(span_message, type);	
			var json_result = $.parseJSON(result);		
			
			setTimeout(function(){ 
				$('#modal_edit_wbt').modal('hide');
					
				if (json_result.status != false){
					//modal_notify('modal_edit_wbt','<strong>Success! </strong> Record has been successfully saved.','success');
					var span_message = '<strong>Success! </strong> Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					//modal_notify('modal_edit_wbt','<strong>Failed! </strong> Unable to save record.','danger');
					var span_message = '<strong>Failed! </strong> Unable to save record.';
					var type = 'danger';
					notify(span_message, type);	
				}
				getwbt("all");
			}, 900);
		}		
	}).fail(function(){
		
	});		
}

function editRow(y, wbt_id){
	var sel_screen_name = document.getElementById("screen_name"+y).innerHTML;
	var sel_menu_label = document.getElementById("menu_label"+y).innerHTML;
	var sel_link = document.getElementById("link"+y).innerHTML;

	//alert(wbt_id);
	$('#wbt_id').val(wbt_id);
	$('#edit_screen_name').val(sel_screen_name);
	$('#edit_menu_label').val(sel_menu_label);
	
	$('#edit_link').val(sel_link);
	/*if (sel_link == "TRADE"){
		$('#edit_link').val("1");
	}else if (sel_link == "NON-TRADE"){
		$('#edit_link').val("0");
	}*/
	$('#modal_edit_wbt').modal('show')
}

function uploadSample(y, wbt_id){
	// var sel_screen_name = document.getElementById("screen_name"+y).innerHTML;
	// var sel_menu_label = document.getElementById("menu_label"+y).innerHTML;
	// var sel_link = document.getElementById("link"+y).innerHTML;

	//alert(wbt_id);
	$('#wbt_id').val(wbt_id);

	$('#myModal').modal('show');
	$('#myModal span').hide();
	$('#myModal .upload_document').show();
	$('#myModalLabel').val();

}

function deactivateYesNo(y,wbt_id)
{
	disable_enable_frm('frm_wbt', true);
	document.getElementById("btn_full").disabled = true;
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+wbt_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(wbt_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	loadingScreen('on');
	$.ajax({
		type:'POST',
		data:{wbt_id: wbt_id},
		url: base_url + "deactivate_wbt",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			// $('#edit'+y).removeClass('disabled');
			// $('#deactivate'+y).removeClass('disabled');
			// $('#row'+y).removeClass('danger')

			disable_enable_frm('frm_wbt', false);
			
			var span_message = 'Record has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			getwbt("all");
		}		
	}).fail(function(){
		
	});	
}
