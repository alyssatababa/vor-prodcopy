


var iCount=0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	var base_url = $('#b_url').attr('data-base-url');
	getCA("all");
});


$('#btn_upload').on('click', function(){
    upload_file("1");
});

function upload_file(type) // type = 1 Documents , 2 Agreements 
{
	var base_url = $('#b_url').attr('data-base-url');

	var file_name;
	var file_path;
	var ca_id;
    var surl = BASE_URL + "vendorparam/contracts_and_agreements/upload_file/" + type;
    //alert(surl);
    upload_ajax_modal(document.frm_modal_ca, surl).done(function(responseText) {
    	//alert(responseText);
        $('#upload_result').html(responseText);

		file_name = $('#orig_name').val(); 
		file_path = $('#file_path').val();
		ca_id = $('#ca_id').val();
        if ($('#error').val() == '')
        {
			$.ajax({
				type:'POST',
				data:{ca_id: ca_id, sample_file: file_path},
				url: base_url + "save_sample_file",
				success: function(result){	
					//alert(result);

					$('#fileupload').val('');
					var json_result = $.parseJSON(result);
					
					if (json_result.status != false){
						
					}else{
						var span_message = 'Unable to save file!';
						var type = 'danger';
						notify(span_message, type);				
					}
				}		
			}).fail(function(){
				
			});
            $('#caModal').modal('hide');
            
        }
        else
        {
            modal_notify($("#caModal"), $('#error').val(), "danger");
        }
    });
}

function get_sample_file(ca_id){
	var base_url = $('#b_url').attr('data-base-url');
	//alert(ca_id);
	$.ajax({
		type:'POST',
		data:{ca_id: ca_id},
		url: base_url + "get_sample_file",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			
			if (json_result.status != false){
				var url = BASE_URL.replace('index.php/','') + json_result[0].SAMPLE_FILE;
				//alert(url);
				$('#caModal').modal('show');
				$('#caModal span').hide();
				$('#caModal .upload_document').show();
		        $('#imagepreview').attr('src', '');

		        if (json_result[0].SAMPLE_FILE != '')
		        {
		        	//alert("check");
		            $('#imagepreview').attr('src', url);
		            $('#imagepreview').removeClass('zoom_in');
		            $('.modal-dialog').addClass('modal-lg');    
		            var filext = json_result[0].SAMPLE_FILE.split('.').pop();
		            // setting height of iframe according to window size
		            var set_height  = '';
		            var w_h         = '';
		            var t_h         = '';

		            if (filext.toLowerCase() == 'pdf')
		            {
		                w_h = $(window).height() * 0.75;
		                t_h = $(this).height() * 0.75;
		                $('#zoom_image').hide();
		                $('#zoom_out_image').hide();
		            }
		            else
		            {
		                w_h = $(window).height() /2;
		                t_h = $(this).height() /2;
		                $('#zoom_image').show();
		                $('#zoom_out_image').show();
		            }
		            $('iframe').height(w_h);
		            $(window).resize(function(){
		                $('iframe').height(t_h);
		            });
		        }
		        else
		        {
		            $('#imagepreview').attr('src', '');
		        }				
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});
}

$(document).on("click", ".btn_add_ca", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

		$('#modal_edit_ca').modal('show');
		$('#modal_edit_ca .edit_ca').hide();
		$('#modal_edit_ca .add_ca').show();
		// $('#caModal span').hide();
		// $('#caModal .add_document').show();
		// $('#caModal .document_details').show();
		// $('#caModal .save_document').show();

		$('#edit_ca_type').val("1");
	
		$('input:checkbox[id=downloadable_cb]').removeAttr('checked');
		$('input:checkbox[id=viewable_cb]').removeAttr('checked');
    }
});

$(document).on("change", "#downloadable_cb", function(e){
	if($(this).attr("checked") == "checked"){
		$("input:checkbox[id=downloadable_cb]").removeAttr("checked");
		$("input:checkbox[id=downloadable_cb]").prop("checked", false);
	}else{
		$("input:checkbox[id=downloadable_cb]").attr("checked","checked");
		$("input:checkbox[id=downloadable_cb]").prop("checked", true);
	}
	e.stopImmediatePropagation();
});
$(document).on("change", "#viewable_cb", function(e){
	if($(this).attr("checked") == "checked"){
		$("input:checkbox[id=viewable_cb]").removeAttr("checked");
		$("input:checkbox[id=viewable_cb]").prop("checked", false);
	}else{
		 $("input:checkbox[id=viewable_cb]").attr("checked","checked");
		$("input:checkbox[id=viewable_cb]").prop("checked", true);
	}
	e.stopImmediatePropagation();
});
$(document).on("click", "#btn_save_ca",function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		//alert("check");
		var ca_name = $("#edit_ca_name").val();
		var description = $("#edit_description").val();
		var bus_division = $("#edit_bus_division").val();
		var ca_type = $("#edit_ca_type").val();
		var downloadable = $("input:checkbox[id=downloadable_cb]").attr("checked");
		var viewable = $("input:checkbox[id=viewable_cb]").attr("checked");
		
		if(downloadable == "checked"){
			downloadable = 1;
		}else{
			downloadable = 0;
		}
		
		if(viewable == "checked"){
			viewable = 1;
		}else{
			viewable = 0;
		}
		
		//alert(ca_type);
		if(isFillUpError(ca_type) == 0){
			if (ca_type == "1"){
				addCA(ca_name, description, bus_division, downloadable, viewable);	
			}else if (ca_type == "2"){
				var ca_id = $("#edit_ca_id").val();
				saveCA(ca_id, ca_name, description, bus_division, downloadable, viewable)
			}	
		}
	}
	e.stopImmediatePropagation();
});

$("#btn_search_ca").click(function()
{	
	var ca = $("#search_ca").val();

	if (ca==""){
		getCA("all");
	}else{
		getCA(ca);
	}
		
});

$('#modal_edit_ca').on('show.bs.modal', function () {
	$('#modal_edit_ca').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	$('#edit_bus_division').val('1');
	//var x = document.getElementById('error_add_ca');
	//x.style.display = 'none';
})

function getCA(ca)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(ca == "all"){
		base_url = base_url + "get_all_ca";
	}else{
		base_url = base_url + "get_ca";
	}
	
	$.ajax({
		type:'POST',
		data:{ca: ca},
		url: base_url,
		success: function(result){	
			console.log(result);
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

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;
		var d_checked = null;
		var v_checked = null;
		
		if(obj_result[x].DOWNLOADABLE == 1){
			d_checked = 'checked="checked"';
		}else{
			d_checked = '';
		}
		
		if(obj_result[x].VIEWABLE == 1){
			v_checked = 'checked="checked"';
		}else{
			v_checked = '';
		}
		
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="ca_name'+y+'">'+obj_result[x].REQUIRED_AGREEMENT_NAME+'</td><td id="description'+y+'" >'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].BUS_DIVISION+'</td><td id="ca_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_SORTING_FORMAT +'</span>' + obj_result[x].CA_DATE+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default btn_tbl_upload" rel = "'+obj_result[x].REQUIRED_AGREEMENT_ID+'|'+obj_result[x].REQUIRED_AGREEMENT_NAME+'" href = "" onclick = "return false;" ><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button></td><td><input type="checkbox" ' + d_checked + 'disabled></td><td><input type="checkbox" ' + v_checked + ' disabled></td><td><button class = "btn btn-default btn_tbl_edit" rel = "'+obj_result[x].REQUIRED_AGREEMENT_ID+'|'+obj_result[x].REQUIRED_AGREEMENT_NAME+'|'+obj_result[x].DESCRIPTION+'|'+obj_result[x].BUS_DIVISION +'|'+obj_result[x].DOWNLOADABLE +'|'+obj_result[x].VIEWABLE +'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-edit g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].REQUIRED_AGREEMENT_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_tbl_delete"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}	
}

$(document).on("click", ".btn_tbl_upload", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var ca = ($(this).attr('rel')).split('|');
        var ca_id = ca[0];
        var frame_theight = $(this).height();
        var frame_wheight = $(window).height();

    	$('#ca_id').val(ca_id);
    	
    	//alert(ca[1]);

		var base_url = $('#b_url').attr('data-base-url');
		//alert(ca_id);
		$.ajax({
			type:'POST',
			data:{ca_id: ca_id},
			url: base_url + "get_sample_file",
			success: function(result){	
				//alert(result);
				var json_result = $.parseJSON(result);
				
				//if (json_result.status != false){
			        //alert(n[2]);
					var url = BASE_URL.replace('index.php/','') + json_result[0].SAMPLE_FILE;
					//alert(url);
					//alert(json_result[0].SAMPLE_FILE);
					$('#caModal').modal('show');
					$('#caModal span').hide();
					$('#caModal .upload_document').show();
					$('#caModal .preview_document').show();
					$('#caModalLabel').text(ca[1]);
			        //$('#caModal .document_preview').show();
			        $('#imagepreview').attr('src', '');
			        if (json_result[0].SAMPLE_FILE != null)
			        {
			        	//alert("check");
			            $('#imagepreview').attr('src', url);
			            $('#imagepreview').removeClass('zoom_in');
			            $('.modal-dialog').addClass('modal-lg');    
			            var filext = json_result[0].SAMPLE_FILE.split('.').pop();
			            // setting height of iframe according to window size
			            var set_height  = '';
			            var w_h         = '';
			            var t_h         = '';

			            if (filext.toLowerCase() == 'pdf')
			            {
			            	//alert("check1");
			                w_h = frame_wheight * 0.65;
			                t_h = frame_theight * 0.65;
			                //alert(t_h);
			                $('#zoom_image').hide();
			                $('#zoom_out_image').hide();
			            }
			            else
			            {
			            	//alert("check2");
			                w_h = frame_wheight /2;
			                t_h = frame_theight /2;
			                $('#zoom_image').show();
			                $('#zoom_out_image').show();
			            }
			            
			            $('iframe').height(w_h);
			            $(window).resize(function(){
			                $('iframe').height(t_h);
			            });
			        }
			        else
			        {
			        	$('#zoom_image').hide();
			            $('#zoom_out_image').hide();
			            $('#imagepreview').attr('src', '');
			            $('#caModal .preview_document').hide();
			        }
					
				//}
				// else{
				// 	var span_message = 'Record not found!';
				// 	var type = 'danger';
				// 	notify(span_message, type);				
				// }
			}		
		}).fail(function(){
			
		});
    }
});

function zoomimage()
{
	$('#imagepreview').addClass('zoom_in');
}

function zoomoutimage()
{
    $('#imagepreview').removeClass('zoom_in');
}

$(document).on("click", ".btn_tbl_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var ca = ($(this).attr('rel')).split('|');

		$('#modal_edit_ca').modal('show');
		$('#modal_edit_ca .edit_ca').show();
		$('#modal_edit_ca .add_ca').hide();
		// $('#caModal span').hide();
		// $('#caModal .edit_document').show();
		// $('#caModal .document_details').show();
		// $('#caModal .save_document').show();

		$('#edit_ca_type').val("2");
    	$('#edit_ca_id').val(ca[0]);

		$('#edit_ca_name').val(ca[1]);
		$('#edit_description').val(ca[2]);
		if (ca[3] == "TRADE"){
			$('#edit_bus_division').val("1");
		}else if (ca[3] == "NON-TRADE"){
			$('#edit_bus_division').val("0");
		}else{
			$('#edit_bus_division').val("2");
		}

		
		$("input:checkbox[id=downloadable_cb]").removeAttr("checked");
		$("input:checkbox[id=downloadable_cb]").prop("checked", false);
		$("input:checkbox[id=viewable_cb]").removeAttr("checked");
		$("input:checkbox[id=viewable_cb]").prop("checked", false);
		if(ca[4] == 1){	
			//alert(ca[4]);
			$('input:checkbox[id=downloadable_cb]').attr('checked', "checked");
			$("input:checkbox[id=downloadable_cb]").prop("checked", true);
		}
		
		if(ca[5] == 1){
			//alert(ca[5]);
			$('input:checkbox[id=viewable_cb]').attr('checked', "checked");	
			$("input:checkbox[id=viewable_cb]").prop("checked", true);
		}
		


    }
});

$(document).on("click", ".btn_tbl_delete", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var ca = $(this).attr('rel');

		//disable_enable_frm('frm_ca', true);
		//document.getElementById("btn_full").disabled = true;
		
		//$('#row'+y).addClass('danger').siblings().removeClass('danger');

		//$('#edit'+y).addClass('disabled');
		//$('#deactivate'+y).addClass('disabled');

		var span_message = 'Are you sure you want to deactivate record? <button id = "btn_yes" type="button" class="btn btn-success" rel = "'+ca+'">Yes</button>&nbsp;<button id = "btn_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		var type = 'info';
		notify(span_message, type, true);

		// var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+ca_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
		// var type = 'info';
		// notify(span_message, type, true);	
    }
});

$(document).on("click", "#btn_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	var ca = $(this).attr('rel');

        	deactivateYes(ca);
}
});

$(document).on("click", "#btn_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 		$div_notifications.stop().fadeOut("slow", clean_div_notif);
        	
	}
});

$(document).on("click", "#btn_close_ca", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        			 $div_notifications.stop().fadeOut("slow", clean_div_notif);

					$('.alert').css("display","none");
}
});

function isFillUpError(crud)
{
	var iError = 0;
	
		$('#modal_edit_ca').find('.field-required').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			modal_notify('modal_edit_ca','<strong>Failed! </strong> Please fill up required fields.','danger');
		}

	return iError;
}

function addCA(ca_name, description, bus_division, downloadable, viewable)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{ca_name: ca_name, description: description, bus_division: bus_division, downloadable:downloadable, viewable:viewable},
		url: base_url + "add_ca",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			
			setTimeout(function(){
				if (json_result.status != false){
					//modal_notify('modal_edit_ca','<strong>Success! </strong> Record has been successfully saved.','success');
					$("#modal_edit_ca").modal('hide');
					var span_message = 'Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_edit_ca','<strong>Failed! </strong> Unable to save record.','danger');
				}

				//var span_message = 'Record has been successfully saved!';
				//var type = 'success';
				//notify(span_message, type);					
				
				$("input:checkbox[id=downloadable_cb]").removeAttr("checked");
				$("input:checkbox[id=viewable_cb]").removeAttr("checked");
				getCA("all");
			}, 900);
		}		
	}).fail(function(){
		
	});	
}

function saveCA(ca_id, ca_name, description, bus_division, downloadable, viewable)
{
	var base_url = $('#b_url').attr('data-base-url');
	//alert(downloadable);
	//alert(viewable);
	$.ajax({
		type:'POST',
		data:{ca_id: ca_id, ca_name: ca_name, description: description, bus_division: bus_division, downloadable: downloadable, viewable:viewable},
		url: base_url + "save_ca",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_ca').modal('hide');
			
			// var span_message = '<strong>Success! </strong> Record has been successfully saved!';
			// var type = 'success';
			//notify(span_message, type);	
			var json_result = $.parseJSON(result);	
			
			setTimeout(function(){
				$("#modal_edit_ca").modal('hide');
					
				if (json_result.status != false){
					//modal_notify('modal_edit_ca','<strong>Success! </strong> Record has been successfully saved.','success');
					var span_message = '<strong>Success! </strong> Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					//modal_notify('modal_edit_ca','<strong>Failed! </strong> Unable to save record.','danger');
					var span_message = '<strong>Failed! </strong> Unable to save record.';
					var type = 'danger';
					notify(span_message, type);	
				}


				//$('#caModal').modal('hide');


				$("input:checkbox[id=downloadable_cb]").removeAttr("checked");
				$("input:checkbox[id=viewable_cb]").removeAttr("checked");
				getCA("all");
			}, 900);
		}		
	}).fail(function(){
		
	});		
}

function editRow(y, ca_id){
	var sel_ca_name = document.getElementById("ca_name"+y).innerHTML;
	var sel_description = document.getElementById("description"+y).innerHTML;
	var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

	//alert(ca_id);
	$('#ca_id').val(ca_id);
	$('#edit_ca_name').val(sel_ca_name);
	$('#edit_description').val(sel_description);
	if (sel_bus_division == "TRADE"){
		$('#edit_bus_division').val("1");
	}else if (sel_bus_division == "NON-TRADE"){
		$('#edit_bus_division').val("0");
	}
	$('#modal_edit_ca').modal('show')
}

function uploadSample(y, ca_id){
	// var sel_ca_name = document.getElementById("ca_name"+y).innerHTML;
	// var sel_description = document.getElementById("description"+y).innerHTML;
	// var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

	//alert(ca_id);
	$('#ca_id').val(ca_id);

	$('#caModal').modal('show');
	$('#caModal span').hide();
	$('#caModal .upload_document').show();

}

function deactivateYesNo(y,ca_id)
{
	disable_enable_frm('frm_ca', true);
	document.getElementById("btn_full").disabled = true;
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+ca_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(ca_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	
	$.ajax({
		type:'POST',
		data:{ca_id: ca_id},
		url: base_url + "deactivate_ca",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			// $('#edit'+y).removeClass('disabled');
			// $('#deactivate'+y).removeClass('disabled');
			// $('#row'+y).removeClass('danger')

			disable_enable_frm('frm_ca', false);
			
			var span_message = 'Record has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			getCA("all");
		}		
	}).fail(function(){
		
	});	
}
