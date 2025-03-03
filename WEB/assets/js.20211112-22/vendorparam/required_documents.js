var iCount=0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	var base_url = $('#b_url').attr('data-base-url');
	getReqDocs("all");
});


$('#btn_upload_reqdocs').on('click', function(){
    upload_file("1");
});

function upload_file(type) // type = 1 Documents , 2 Agreements 
{
	var base_url = $('#b_url').attr('data-base-url');

	var file_name;
	var file_path;
	var reqdocs_id;
    var surl = BASE_URL + "vendorparam/required_documents/upload_file/" + type;
    //alert(surl);
    upload_ajax_modal(document.frm_reqdocs, surl).done(function(responseText) {
    	//alert(responseText);
        $('#upload_result_reqdocs').html(responseText);

		file_name = $('#orig_name').val(); 
		file_path = $('#file_path').val();
		reqdocs_id = $('#reqdocs_id').val();
        if ($('#error').val() == '')
        {
			$.ajax({
				type:'POST',
				data:{reqdocs_id: reqdocs_id, sample_file: file_path},
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
            $('#myModal').modal('hide');
            
        }
        else
        {
            modal_notify($("#myModal"), $('#error').val(), "danger");
        }
    });
}

function get_sample_file(reqdocs_id){
	var base_url = $('#b_url').attr('data-base-url');
	//alert(reqdocs_id);
	$.ajax({
		type:'POST',
		data:{reqdocs_id: reqdocs_id},
		url: base_url + "get_sample_file",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			
			//if (json_result.status != false){
				var url = BASE_URL.replace('index.php/','') + json_result[0].SAMPLE_FILE;
				//alert(url);
				$('#myModal').modal('show');
				$('#myModal span').hide();
				$('#myModal .upload_document').show();
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

$(document).on("click", ".btn_add_reqdocs", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

		$('#modal_edit_reqdocs').modal('show');
		$('#modal_edit_reqdocs .edit_reqdocs').hide();
		$('#modal_edit_reqdocs .add_reqdocs').show();
		// $('#myModal span').hide();
		// $('#myModal .add_document').show();
		// $('#myModal .document_details').show();
		// $('#myModal .save_document').show();

		$('#edit_reqdocs_type').val("1");
    }
});


$("#btn_save_reqdocs").click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		//alert("check");
		var reqdocs_name = $("#edit_reqdocs_name").val();
		var description = $("#edit_description").val();
		var bus_division = $("#edit_bus_division").val();
		var reqdocs_type = $("#edit_reqdocs_type").val();

		//alert(reqdocs_type);
		if(isFillUpError(reqdocs_type) == 0){
			if (reqdocs_type == "1"){
				loading('btn_save_reqdocs','in_progress');
				addReqDocs(reqdocs_name, description, bus_division);	
			}else if (reqdocs_type == "2"){
				loading('btn_save_reqdocs','in_progress');
				var reqdocs_id = $("#edit_reqdocs_id").val();
				saveReqDocs(reqdocs_id, reqdocs_name, description, bus_division)
			}	
		}
	}
	e.stopImmediatePropagation();
});

$("#btn_search_reqdocs").click(function()
{	
	var reqdocs = $("#search_reqdocs").val();

	if (reqdocs==""){
		getReqDocs("all");
	}else{
		getReqDocs(reqdocs);
	}
		
});

$('#modal_edit_reqdocs').on('show.bs.modal', function () {
	$('#modal_edit_reqdocs').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	$('#edit_bus_division').val('1');
	//var x = document.getElementById('error_add_reqdocs');
	//x.style.display = 'none';
})

function getReqDocs(reqdocs)
{
	//alert("he");
	var base_url = $('#b_url').attr('data-base-url');
	
	if(reqdocs == "all"){
		base_url = base_url + "get_all_reqdocs";
	}else{
		base_url = base_url + "get_reqdocs";
	}
	
	$.ajax({
		type:'POST',
		data:{reqdocs: reqdocs},
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
	$("#tbl_view").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="reqdocs_name'+y+'">'+obj_result[x].REQUIRED_DOCUMENT_NAME+'</td><td id="description'+y+'" >'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].BUS_DIVISION+'</td><td id="reqdocs_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_SORTING_FORMAT +'</span>'+obj_result[x].REQDOCS_DATE+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default btn_reqdocs_upload" rel = "'+obj_result[x].REQUIRED_DOCUMENT_ID+'|'+obj_result[x].REQUIRED_DOCUMENT_NAME+'" href = "" onclick = "return false;" ><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button></td><td><button class = "btn btn-default btn_reqdocs_edit" rel = "'+obj_result[x].REQUIRED_DOCUMENT_ID+'|'+obj_result[x].REQUIRED_DOCUMENT_NAME+'|'+obj_result[x].DESCRIPTION+'|'+obj_result[x].BUS_DIVISION+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-edit g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].REQUIRED_DOCUMENT_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_reqdocs_delete"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}	
}

$(document).on("click", ".btn_reqdocs_upload", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var reqdocs = ($(this).attr('rel')).split('|');
        var reqdocs_id = reqdocs[0];
        var frame_theight = $(this).height();
        var frame_wheight = $(window).height();

        //alert(reqdocs_id);

    	$('#reqdocs_id').val(reqdocs_id);
    	

		var base_url = $('#b_url').attr('data-base-url');
		//alert(reqdocs_id);
		$.ajax({
			type:'POST',
			data:{reqdocs_id: reqdocs_id},
			url: base_url + "get_sample_file",
			success: function(result){	
				//alert(result);
				var json_result = $.parseJSON(result);
				
				if (json_result.status != false){
			        //alert(n[2]);
					var url = BASE_URL.replace('index.php/','') + json_result[0].SAMPLE_FILE;
					//alert(url);
					//alert(json_result[0].SAMPLE_FILE);
					$('#myModal').modal('show');
					$('#myModal span').hide();
					$('#myModal .upload_document').show();
					$('#myModal .preview_document').show();
					//alert(reqdocs[1]);
					$('#myModalLabel').text(reqdocs[1]);
			        //$('#myModal .document_preview').show();
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
			            $('#myModal .preview_document').hide();
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
});

function zoomimage()
{
	$('#imagepreview').addClass('zoom_in');
}

function zoomoutimage()
{
    $('#imagepreview').removeClass('zoom_in');
}

$(document).on("click", ".btn_reqdocs_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var reqdocs = ($(this).attr('rel')).split('|');

		$('#modal_edit_reqdocs').modal('show');
		$('#modal_edit_reqdocs .edit_reqdocs').show();
		$('#modal_edit_reqdocs .add_reqdocs').hide();


		$('#edit_reqdocs_type').val("2");
    	$('#edit_reqdocs_id').val(reqdocs[0]);

		$('#edit_reqdocs_name').val(reqdocs[1]);
		$('#edit_description').val(reqdocs[2]);
		if (reqdocs[3] == "TRADE"){
			$('#edit_bus_division').val("1");
		}else if (reqdocs[3] == "NON-TRADE"){
			$('#edit_bus_division').val("0");
		}
    }
});

$(document).on("click", ".btn_reqdocs_delete", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var reqdocs = $(this).attr('rel');

		//disable_enable_frm('frm_reqdocs', true);
		//document.getElementById("btn_full").disabled = true;
		
		//$('#row'+y).addClass('danger').siblings().removeClass('danger');

		//$('#edit'+y).addClass('disabled');
		//$('#deactivate'+y).addClass('disabled');

		var span_message = 'Are you sure you want to deactivate record? <button id = "btn_reqdocs_yes" type="button" class="btn btn-success" rel = "'+reqdocs+'">Yes</button>&nbsp;<button id = "btn_reqdocs_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		var type = 'info';
		notify(span_message, type, true);

		// var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+reqdocs_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
		// var type = 'info';
		// notify(span_message, type, true);	
    }
});

$(document).on("click", "#btn_reqdocs_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	var reqdocs = $(this).attr('rel');

        	deactivateYes(reqdocs);
}
});

$(document).on("click", "#btn_reqdocs_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 		$div_notifications.stop().fadeOut("slow", clean_div_notif);
        	
	}
});

$(document).on("click", "#btn_close_reqdocs", function (e) {
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
	
		$('#modal_edit_reqdocs').find('.field-required').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			modal_notify('modal_edit_reqdocs','<strong>Failed! </strong> Please fill up required fields.','danger');
		}

	return iError;
}

function addReqDocs(reqdocs_name, description, bus_division)
{
	var base_url = $('#b_url').attr('data-base-url');

/*	console.log(reqdocs_name + '/' + description + '/' + bus_division);
	return;*/

	$.ajax({
		type:'POST',
		data:{reqdocs_name: reqdocs_name, description: description, bus_division: bus_division},
		url: base_url + "add_reqdocs",
		success: function(result){	
			console.log(result);
			var json_result = $.parseJSON(result);
			
				
			setTimeout(function(){ 
				loading('btn_save_reqdocs','');
				if (json_result.status != false){
					//modal_notify('modal_edit_reqdocs','<strong>Success! </strong> Record has been successfully saved.','success');
					$('#modal_edit_reqdocs').modal('hide');
					var span_message = 'Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_edit_reqdocs','<strong>Failed! </strong> Unable to save record.','danger');
				}
				getReqDocs("all");
			}, 900);
		}		
	}).fail(function(){
		loading('btn_save_reqdocs','');
	});	
}

function saveReqDocs(reqdocs_id, reqdocs_name, description, bus_division)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{reqdocs_id: reqdocs_id, reqdocs_name: reqdocs_name, description: description, bus_division: bus_division},
		url: base_url + "save_reqdocs",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_reqdocs').modal('hide');
			
			// var span_message = 'Record has been successfully saved!';
			// var type = 'success';
			// notify(span_message, type);	
			var json_result = $.parseJSON(result);		
			
			setTimeout(function(){ 
				loading('btn_save_reqdocs','');
				$('#modal_edit_reqdocs').modal('hide');
					
				if (json_result.status != false){
					//modal_notify('modal_edit_reqdocs','<strong>Success! </strong> Record has been successfully saved.','success');
					var span_message = '<strong>Success! </strong> Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					//modal_notify('modal_edit_reqdocs','<strong>Failed! </strong> Unable to save record.','danger');
					var span_message = '<strong>Failed! </strong> Unable to save record.';
					var type = 'danger';
					notify(span_message, type);	
				}
				getReqDocs("all");
			}, 900);
		}		
	}).fail(function(){
		loading('btn_save_reqdocs','');
	});		
}

function editRow(y, reqdocs_id){
	var sel_reqdocs_name = document.getElementById("reqdocs_name"+y).innerHTML;
	var sel_description = document.getElementById("description"+y).innerHTML;
	var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

	//alert(reqdocs_id);
	$('#reqdocs_id').val(reqdocs_id);
	$('#edit_reqdocs_name').val(sel_reqdocs_name);
	$('#edit_description').val(sel_description);
	if (sel_bus_division == "TRADE"){
		$('#edit_bus_division').val("1");
	}else if (sel_bus_division == "NON-TRADE"){
		$('#edit_bus_division').val("0");
	}
	$('#modal_edit_reqdocs').modal('show')
}

function uploadSample(y, reqdocs_id){
	// var sel_reqdocs_name = document.getElementById("reqdocs_name"+y).innerHTML;
	// var sel_description = document.getElementById("description"+y).innerHTML;
	// var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

	//alert(reqdocs_id);
	$('#reqdocs_id').val(reqdocs_id);

	$('#myModal').modal('show');
	$('#myModal span').hide();
	$('#myModal .upload_document').show();
	$('#myModalLabel').val();

}

function deactivateYesNo(y,reqdocs_id)
{
	disable_enable_frm('frm_reqdocs', true);
	document.getElementById("btn_full").disabled = true;
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+reqdocs_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(reqdocs_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	
	$.ajax({
		type:'POST',
		data:{reqdocs_id: reqdocs_id},
		url: base_url + "deactivate_reqdocs",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			// $('#edit'+y).removeClass('disabled');
			// $('#deactivate'+y).removeClass('disabled');
			// $('#row'+y).removeClass('danger')

			disable_enable_frm('frm_reqdocs', false);
			
			var span_message = 'Record has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			getReqDocs("all");
		}		
	}).fail(function(){
		
	});	
}
