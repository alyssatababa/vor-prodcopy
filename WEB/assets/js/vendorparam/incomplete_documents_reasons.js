var iCount=0;
var arr_tmp_tbl = [];
var req_list = [];
var sec_list = [];
var req_list_option = '';
var sec_list_option = '';

$(document).ready(function(){
	var base_url = $('#b_url').attr('data-base-url');
	incdocreasons("all");
	loaddocs('req');
	loaddocs('sec');
});

function loaddocs(type){
	var base_url = $('#b_url').attr('data-base-url');

	if(type == "req"){
		base_url = base_url.replace("incomplete_documents_reasons", "required_documents");
		base_url = base_url + "get_all_reqdocs";
	}else if(type == "sec"){
		base_url = base_url.replace("incomplete_documents_reasons", "contracts_and_agreements");
		base_url = base_url + "get_all_ca";
	}
	
	$.ajax({
		type:'POST',
		data:{},
		url: base_url,
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			if(type == "req"){
				req_list = json_result;
				for(var i = 0; i < req_list.length; i++){
					req_list_option += "<option value='" + req_list[i].REQUIRED_DOCUMENT_ID + "'>" + req_list[i].REQUIRED_DOCUMENT_NAME + "</option>";
				}
			}else if(type == "sec"){
				sec_list = json_result;
				for(var i = 0; i < sec_list.length; i++){
					sec_list_option += "<option value='" + sec_list[i].REQUIRED_AGREEMENT_ID + "'>" + sec_list[i].REQUIRED_AGREEMENT_NAME + "</option>";
				}
			}
		}		
	}).fail(function(){
		
	});	
}


$(document).on("click", ".btn_add_incdocreasons", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);

		$('#modal_edit_incdocreasons').modal('show');
		$('#modal_edit_incdocreasons .edit_incdocreasons').hide();
		$('#modal_edit_incdocreasons .add_incdocreasons').show();
		// $('#myModal span').hide();
		// $('#myModal .add_document').show();
		// $('#myModal .document_details').show();
		// $('#myModal .save_document').show();
	
		$('#edit_incdocreasons_type').val("1");
		$('#select_document_name').append("<option value = 0>SELECT DOCUMENT NAME</option>");
		$("#select_document_name").attr("disabled","disabled");
		$('#select_document_name').val(0);
		$('#select_document_type').val(0);
    }
});


$(document).on("click", "#btn_save_incdocreasons",function(e)
{ 
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		e.handled = true; // Basically setting value that the current event has occurred.
		//alert("check");
		var edit_incdocreasons_type = $('#edit_incdocreasons_type').val();
		var incdocreasons_name = $("#edit_incdocreasons_name").val();
		var document_type_id = ( $("#select_document_type").val() == 0 ? '' : $("#select_document_type").val());
		var document_name = ( $("#select_document_name").val() == 0 ? '' : $("#select_document_name").val() );
		//alert(edit_incdocreasons_type);
		if(isFillUpError(edit_incdocreasons_type) == 0){
			if (edit_incdocreasons_type == "1"){
				addIncdocreasons(incdocreasons_name, document_type_id, document_name);	
			}else if (edit_incdocreasons_type == "2"){
				var incdocreasons_id = $("#edit_incdocreasons_id").val();
				saveIncdocreasons(incdocreasons_id, incdocreasons_name, document_type_id, document_name)
			}	
		}
	}
});

$("#btn_search_incdocreasons").click(function()
{	
	var search_incdocreasons = $("#search_incdocreasons").val();
	
	if (search_incdocreasons == ""){
		incdocreasons("all");
	}else{
		incdocreasons(search_incdocreasons);
	}
		
});

$('#modal_edit_incdocreasons').on('show.bs.modal', function () {
	$('#modal_edit_incdocreasons').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	$('#edit_bus_division').val('1');
	//var x = document.getElementById('error_add_incdocreasons');
	//x.style.display = 'none';
})

function incdocreasons(search_incdocreasons)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(search_incdocreasons == "all"){
		base_url = base_url + "get_all_incdocreasons";
	}else{
		base_url = base_url + "get_incdocreasons";
	}
	
	$.ajax({
		type:'POST',
		data:{search_incdocreasons: search_incdocreasons},
		url: base_url,
		success: function(result){	
			//alert(result);
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
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="incdocreasons_name'+y+'">'+obj_result[x].INCOMPLETE_REASON+'</td><td id="document_type_name'+y+'" >'+obj_result[x].DOCUMENT_TYPE_NAME+'</td><td id="document_name'+y+'">'+obj_result[x].DOCUMENT_NAME+'</td><td id="incdocreasons_date'+y+'">'+ '<span style="display:none;">' + obj_result[x].DATE_SORTING_FORMAT +'</span>' + obj_result[x].DATE_CREATED+'</td><td><button class = "btn btn-default btn_incdocreasons_edit" rel = "'+obj_result[x].REASON_ID +'|'+obj_result[x].INCOMPLETE_REASON+'|'+obj_result[x].DOCUMENT_TYPE+'|'+obj_result[x].DOCUMENT_TYPE_NAME+'|'+obj_result[x].DOCUMENT_ID+'|'+obj_result[x].DOCUMENT_NAME+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-edit g_icon" onclick = "return false"></span></button> <span>  </span> <button rel = "'+obj_result[x].REASON_ID+'" href = "" onclick = "return false;" class = "btn btn-default btn_incdocreasons_delete"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
}

$(document).on("click", ".btn_incdocreasons_upload", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var incdocreasons = ($(this).attr('rel')).split('|');
        var incdocreasons_id = incdocreasons[0];
        var frame_theight = $(this).height();
        var frame_wheight = $(window).height();

        //alert(incdocreasons_id);

    	$('#incdocreasons_id').val(incdocreasons_id);
    	

		var base_url = $('#b_url').attr('data-base-url');
		//alert(incdocreasons_id);
		$.ajax({
			type:'POST',
			data:{incdocreasons_id: incdocreasons_id},
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
					//alert(incdocreasons[1]);
					$('#myModalLabel').text(incdocreasons[1]);
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



$(document).on("change", "#select_document_type", function(e){
	var doc_type_id = $("#select_document_type").val();
	if(doc_type_id == 1){
		$('#select_document_name').empty();
		$('#select_document_name').removeAttr("disabled");
		$('#select_document_name').append("<option value = 0>SELECT DOCUMENT NAME</option>");
		$('#select_document_name').append(req_list_option);
	}else if(doc_type_id == 2){
		$('#select_document_name').empty();
		$('#select_document_name').removeAttr("disabled");
		$('#select_document_name').append("<option value = 0>SELECT DOCUMENT NAME</option>");
		$('#select_document_name').append(sec_list_option);
	}else{
		$("#select_document_name").attr("disabled","disabled");
		$('#select_document_name').val(0);
	}
});

$(document).on("click", ".btn_incdocreasons_edit", function (e) {

    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var incdocreasons = ($(this).attr('rel')).split('|');

		$('#modal_edit_incdocreasons').modal('show');
		$('#modal_edit_incdocreasons .edit_incdocreasons').show();
		$('#modal_edit_incdocreasons .add_incdocreasons').hide();

		$('#edit_incdocreasons_type').val("2");
    	$('#edit_incdocreasons_id').val(incdocreasons[0]);
    	$('#edit_incdocreasons_name').val(incdocreasons[1]);
		$('#select_document_type').val(incdocreasons[2]);
		
		$('#select_document_name').empty();
		$('#select_document_name').append("<option value = 0>SELECT DOCUMENT NAME</option>");

		if(incdocreasons[2] == 1){
			$('#select_document_name').append(req_list_option);
		}else if(incdocreasons[2] == 2){
			$('#select_document_name').append(sec_list_option);
		}
		
		$('#select_document_name').removeAttr("disabled");
		$('#select_document_name').val(incdocreasons[4]);
    }
});

$(document).on("click", ".btn_incdocreasons_delete", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var incdocreasons = $(this).attr('rel');

		//disable_enable_frm('frm_incdocreasons', true);
		//document.getElementById("btn_full").disabled = true;
		
		//$('#row'+y).addClass('danger').siblings().removeClass('danger');

		//$('#edit'+y).addClass('disabled');
		//$('#deactivate'+y).addClass('disabled');

		var span_message = 'Are you sure you want to deactivate record? <button id = "btn_incdocreasons_yes" type="button" class="btn btn-success" rel = "'+incdocreasons+'">Yes</button>&nbsp;<button id = "btn_incdocreasons_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
		var type = 'info';
		notify(span_message, type, true);

		// var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+incdocreasons_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
		// var type = 'info';
		// notify(span_message, type, true);	
    }
});

$(document).on("click", "#btn_incdocreasons_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	var incdocreasons = $(this).attr('rel');

        	deactivateYes(incdocreasons);
}
});

$(document).on("click", "#btn_incdocreasons_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 		$div_notifications.stop().fadeOut("slow", clean_div_notif);
        	
	}
});

$(document).on("click", "#btn_close_incdocreasons", function (e) {
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
	
		$('#modal_edit_incdocreasons').find('.field-required').each(function()
		{
			if($(this).val().length == 0  || $(this).val() == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			modal_notify('modal_edit_incdocreasons','<strong>Failed! </strong> Please fill up required fields.','danger');
		}

	return iError;
}

function addIncdocreasons(incdocreasons_name, document_type_id, document_name)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{incdocreasons_name: incdocreasons_name, document_type_id: document_type_id, document_name: document_name},
		url: base_url + "add_incdocreasons",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			setTimeout(function(){
				if (json_result.status != false){
					//modal_notify('modal_edit_incdocreasons','<strong>Success! </strong> Record has been successfully saved.','success');
					$('#modal_edit_incdocreasons').modal('hide');
					var span_message = 'Record has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_edit_incdocreasons','<strong>Failed! </strong> Unable to save record.','danger');
				}

				//var span_message = 'Record has been successfully saved!';
				//var type = 'success';
				//notify(span_message, type);					
				
				incdocreasons("all");
			},900);
		}		
	}).fail(function(){
		
	});	
}

function saveIncdocreasons(incdocreasons_id, incdocreasons_name, document_type_id, document_name)
{
	var base_url = $('#b_url').attr('data-base-url');
		
	$.ajax({
		type:'POST',
		data:{incdocreasons_id: incdocreasons_id, incdocreasons_name: incdocreasons_name, document_type_id: document_type_id, document_name: document_name},
		url: base_url + "save_incdocreasons",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_incdocreasons').modal('hide');
			
			// var span_message = 'Record has been successfully saved!';
			// var type = 'success';
			// notify(span_message, type);	
			var json_result = $.parseJSON(result);
			setTimeout(function(){
				$('#modal_edit_incdocreasons').modal('hide');
				
				if (json_result.status != false){
					//modal_notify('modal_edit_incdocreasons','<strong>Success! </strong> Record has been successfully saved.','success');
					var span_message = '<strong>Success! </strong> Record has been successfully saved.';
					var type = 'success';
					notify(span_message, type);	
				}else{
					modal_notify('modal_edit_incdocreasons','<strong>Failed! </strong> Unable to save record.','danger');
					var span_message = '<strong>Failed! </strong> Unable to save record.';
					var type = 'danger';
					notify(span_message, type);	
				}

				incdocreasons("all");
			},900);
		}		
	}).fail(function(){
		
	});		

}

function editRow(y, incdocreasons_id){
	var sel_incdocreasons_name = document.getElementById("incdocreasons_name"+y).innerHTML;
	var sel_description = document.getElementById("description"+y).innerHTML;
	var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

	//alert(incdocreasons_id);
	$('#incdocreasons_id').val(incdocreasons_id);
	$('#edit_incdocreasons_name').val(sel_incdocreasons_name);
	$('#edit_description').val(sel_description);
	if (sel_bus_division == "TRADE"){
		$('#edit_bus_division').val("1");
	}else if (sel_bus_division == "NON-TRADE"){
		$('#edit_bus_division').val("0");
	}
	$('#modal_edit_incdocreasons').modal('show')
}

function deactivateYesNo(y,incdocreasons_id)
{
	disable_enable_frm('frm_incdocreasons', true);
	document.getElementById("btn_full").disabled = true;
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+incdocreasons_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(incdocreasons_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	
	$.ajax({
		type:'POST',
		data:{incdocreasons_id: incdocreasons_id},
		url: base_url + "deactivate_incdocreasons",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			// $('#edit'+y).removeClass('disabled');
			// $('#deactivate'+y).removeClass('disabled');
			// $('#row'+y).removeClass('danger')

			disable_enable_frm('frm_incdocreasons', false);
			
			var span_message = 'Record has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			incdocreasons("all");
		}		
	}).fail(function(){
		
	});	
}
