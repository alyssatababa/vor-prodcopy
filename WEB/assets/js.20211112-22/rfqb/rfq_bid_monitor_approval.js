var base_url = $('#b_url').attr('data-base-url');
var id = $('#rfqb_id').attr('rel');
var failed_status;
var continue_status;
var extend_status;
var next_position;
var reject_reason = "";
var extension_date = "";
var status_id;
var iError=0;

var curr_status;

function btnAction(action){

	// alert(action);
	iError=0;

	if (action == "1"){
		action_type = "Approve Bid Status";
	}else if (action == "2"){
		action_type = "Reject Bid Status";
		// $('#modal_reject_bid').modal('hide');
		reject_reason = $("#reject_bid_reason").val();
	}
	// alert("2");	
	if(action == "2")
	{
		$('#modal_reject_bid').find('.field-required').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
	}
	
	if(iError > 0){
		modal_notify('modal_reject_bid','<strong>Failed! </strong> Please fill up required fields.','danger');
	}else{
		$('#modal_reject_bid').modal('hide');

	    var span_message = 'Are you sure you want to '+action_type+'? <input type="button" class="btn btn-success" value="Yes" onclick="updateApprovalStatus('+action+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
	    var type = 'info';
	    notify(span_message, type, true);	
	}

	// setRfqbStatus(id, action_status, reason, next_position);

}

function updateApprovalStatus(action){

	// alert("check");

	var action_status = "", action_modal = "", action_type = "";
	//hard coded, kelangan pa gawan ng config
	var invite_status = "162";

	if (action == "1"){
		// reason = $("#approve_bid_reason").val();
		action_status = approve_status;
		action_type = "Approve Bid Status";
		action_done = "Approve";
	}else if (action == "2"){
		
		action_status = reject_status
		action_type = "Reject Bid Status";
		action_done = "Reject";
	}

	// alert(base_url);
	// alert(id + " " + action_status + " " + reject_reason + " " + next_position + " " + extension_date);

	$.ajax({
		type:'POST',
		data:{	rfb_id: 			id, 
				rfb_status: 		action_status, 
				action_done:		action_done, 
				bid_type:			$('#bid_type').val(), 
				reason: 			reject_reason, 
				position_id: 		next_position, 
				extension_date: 	extension_date, 
				invite_status: 		invite_status,
				current_status: 	curr_status},
		url: base_url + "rfq_bid_monitor_approval/set_rfqb_status",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);

			if (json_result.status != false){

				//hard coded, kelangan pa gawan ng config
				if (status_id == "25" && action != "2"){
					if (setSubDeadline(id, extension_date) == false){
						var span_message = 'Unable to set new submission deadline!';
						var type = 'danger';
						notify(span_message, type);		
					}
				}
				
				var span_message = action_type + ' success.';
				var type = 'success';
				notify(span_message, type);	

				$('.approval_btn').attr('disabled','disabled');

				var action_path = base_url + 'rfq_main/rfqrfb_main_view/';
			    setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3500);	
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

function setSubDeadline(rfb_id, sub_date){
	// alert("1");
	$.ajax({
		type:'POST',
		data:{rfb_id: rfb_id, sub_date: sub_date},
		url: base_url + "rfq_bid_monitor_approval/set_sub_deadline",
		success: function(result){	
			// alert(result);
			var json_result = $.parseJSON(result);

			if (json_result.status != false){
				return json_result.status;
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

$(document).ready(function(){
	// var base_url = $('#b_url').attr('data-base-url');
	var sysdate, countDownDate, distance;
	// var days, hours, minutes, seconds;
	// var id = $('#rfqb_id').attr('rel');

	$('#bid_deadline').hide();
	$('#lbl_deadline').hide();

	$("#btn_close").click(function()
	{
		var action_path = base_url + 'rfq_main/rfqrfb_main_view/';
		setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},500);				
	});

	// var approve_status;
	// var reject_status;
	// var next_position

	// $('.failed_btn').hide();

	// alert(base_url);
	getRfqbApproval(id);
	getRfqbTable(id);
	getRfqbConfig(id);



	function getRfqbConfig(rfb_id){

		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor_approval/get_rfqb_config",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				if (json_result.status != false){
					var curr_position = json_result[0].POSITION_ID
					approve_status = json_result[0].APPROVE_STATUS_ID
					reject_status = json_result[0].REJECT_STATUS_ID
					next_position = json_result[0].NEXT_POSITION_ID

					curr_status = json_result[0].CURRENT_STATUS_ID

				}else{
					var span_message = 'Record not found!';
					var type = 'danger';
					notify(span_message, type);				
				}
			}		
		}).fail(function(){
			
		});	
	}
	
	function getRfqbApproval(rfb_id){
		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor_approval/get_rfqb_apprvl",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				$('.rfqb_title').text("Bid Monitor - " + json_result[0].TITLE);

				$('.rfqb_invited').text(json_result[0].RIV_COUNT);
				$('.rfqb_participants').text(json_result[0].RA_COUNT);
				$('.rfqb_responses').text(json_result[0].RR_COUNT);

				$('#bid_status').text(json_result[0].STATUS_NAME);
				$('#bid_type').val(json_result[0].STATUS_NAME);

				
				$('#approval_reason').text(json_result[0].REASON);
				status_id = json_result[0].STATUS_ID;
				extension_date = json_result[0].EXT_DATE;

				if (status_id == 25){
					$('#bid_deadline').show();
					$('#lbl_deadline').show();
					$('#bid_deadline').text(json_result[0].EXT_DATE);
				}

			}		
		}).fail(function(){
			
		});			
	}

	function getRfqbTable(rfb_id){
		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor_approval/get_rfqb_table",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				if (json_result.status != false){
					$("#tbl_view").DataTable().destroy();
					createDataTable(json_result);
					$("#tbl_view").DataTable({
						"pageLength": 10
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

	// $("#btn_approve").click(function()
	// {
	// 	var id = $('#rfqb_id').attr('rel');
	// 	var reason = $("#approval_reason").val();;
		
	// 	alert(approve_status);
	// 	setRfqbStatus(id, approve_status, reason, next_position);
	// 	// $('#modal_failed_bid').modal('hide');	

	// 	var span_message = 'Approve Bid Status success.';
	// 	var type = 'success';
	// 	notify(span_message, type);					
	// });

	// $("#btn_reject").click(function()
	// {
	// 	var id = $('#rfqb_id').attr('rel');
	// 	var reason = $("#approval_reason").val();;
		
	// 	alert(approve_status);
	// 	setRfqbStatus(id, reject_status, reason, next_position);
	// 	// $('#modal_failed_bid').modal('hide');	

	// 	var span_message = 'Reject Bid Status success.';
	// 	var type = 'success';
	// 	notify(span_message, type);					
	// });



	// function setRfqbStatus(rfb_id, rfb_status, reason, position_id){

	// 	$.ajax({
	// 		type:'POST',
	// 		data:{rfb_id: rfb_id, rfb_status: rfb_status, reason: reason, position_id: position_id},
	// 		url: base_url + "rfq_bid_monitor_approval/set_rfqb_status",
	// 		success: function(result){	
	// 			// alert(result);
	// 			var json_result = $.parseJSON(result);

	// 			if (json_result.status != false){
	// 				// $("#tbl_view").DataTable().destroy();
	// 				// createDataTable(json_result);
	// 				// $("#tbl_view").DataTable({
	// 					// "pageLength": 10
	// 				// });
	// 			}else{
	// 				var span_message = 'Record not found!';
	// 				var type = 'danger';
	// 				notify(span_message, type);				
	// 			}
	// 		}		
	// 	}).fail(function(){
			
	// 	});	
	// }



	function createDataTable(obj_result)
	{
		var x=0;
		var y=0;
		var z=obj_result.length;
		//alert(z);
		$("#tbl_view").find("tr:gt(0)").remove();

		
		for(x=0;x<z;x++){
			y=x+1;
			// alert(obj_result[x].INVITE_STATUS);
			var inv_status = ((obj_result[x].INVITE_STATUS == 0) ? "NO RESPONSE" : obj_result[x].INVITE_STATUS);
			var ra_date = ((obj_result[x].RA_DATE == 0) ? "" : obj_result[x].RA_DATE);
			var rr_date = ((obj_result[x].RR_DATE == 0) ? "" : obj_result[x].RR_DATE);
			$('<tr id="row'+y+'" class="clickable-row"><td id="reqdocs_name'+y+'">'+obj_result[x].VENDOR_NAME+'</td><td id="description'+y+'">'+inv_status+'</td><td id="bus_division'+y+'">'+ra_date+'</td><td id="reqdocs_date'+y+'">'+rr_date+'</td></tr>').appendTo('#tbl_body_rfqb_monitor');
		}
		
	}



	// bid_clock = setInterval(function() {
	
	//     distance=distance-3600000;

	//     days = Math.floor(distance / (1000 * 60 * 60 * 24));
	//     hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
	//     minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
	//     seconds = Math.floor((distance % (1000 * 60)) / 1000);
	    
	// 		    $('#bid_timer').text(days + " days " + hours + " hours "
	// 		    + minutes + " mins " + seconds + " secs ");
	    
	//     if (distance < 0) {
	//         clearInterval(bid_clock);
	//         $('#close_date').hide();
	//         $('#time_left').hide();
	//         $('#failed_bid').show();
	//         $('.failed_btn').show();
	//         // document.getElementById("bid_timer").innerHTML = "EXPIRED";
	//     }
	// }, 1000);
 

});

 


// $("#btn_add_reqdocs").click(function()
// {
// 	var reqdocs_name = $("#input_reqdocs_name").val();
// 	var description = $("#input_description").val();
// 	var bus_division = $("#select_bus_division").val();
	
// 	if(isFillUpError("add") == 0){
// 		addReqDocs(reqdocs_name, description, bus_division);	
// 	}
	
// });

// $("#btn_save_reqdocs").click(function()
// {
// 	var reqdocs_id = $("#edit_reqdocs_id").val();
// 	var reqdocs_name = $("#edit_reqdocs_name").val();
// 	var description = $("#edit_description").val();
// 	var bus_division = $("#edit_bus_division").val();

// 	if(isFillUpError("edit") == 0){
// 		saveReqDocs(reqdocs_id, reqdocs_name, description, bus_division);
// 	}	
// });

// $("#btn_search_reqdocs").click(function()
// {	
// 	var reqdocs = $("#search_reqdocs").val();

// 	if (reqdocs==""){
// 		getReqDocs("all");
// 	}else{
// 		getReqDocs(reqdocs);
// 	}
		
// });

// $('#modal_add_reqdocs').on('show.bs.modal', function () {
// 	$('#modal_add_reqdocs').find('.field-required').each(function()
// 	{
// 		$('#'+ this.id).parent('div').removeClass('has-error');	
// 		$('#'+ this.id).val('');
// 	});	
	
// 	$('#select_bus_division').val('1');
// 	//var x = document.getElementById('error_add_reqdocs');
// 	//x.style.display = 'none';
// })

// $('#modal_edit_reqdocs').on('show.bs.modal', function () {
// 	$('#modal_edit_reqdocs').find('.field-required').each(function()
// 	{
// 		$('#'+ this.id).parent('div').removeClass('has-error');	
// 	});	
	
// 	//var x = document.getElementById('error_edit_reqdocs');
// 	//x.style.display = 'none';
// })

// //========== FUNCTIONS ==========

// function getReqDocs(reqdocs)
// {
// 	var base_url = $('#b_url').attr('data-base-url');
	
// 	if(reqdocs == "all"){
// 		base_url = base_url + "get_all_reqdocs";
// 	}else{
// 		base_url = base_url + "get_reqdocs";
// 	}
	
// 	$.ajax({
// 		type:'POST',
// 		data:{reqdocs: reqdocs},
// 		url: base_url,
// 		success: function(result){	
// 			//alert(result);
// 			var json_result = $.parseJSON(result);
			
// 			if (json_result.status != false){
// 				$("#tbl_view").DataTable().destroy();
// 				createDataTable(json_result);
// 				$("#tbl_view").DataTable({
// 					"pageLength": 10
// 				});
// 			}else{
// 				var span_message = 'Record not found!';
// 				var type = 'danger';
// 				notify(span_message, type);				
// 			}
// 		}		
// 	}).fail(function(){
		
// 	});	
// }



// function isFillUpError(crud)
// {
// 	var iError = 0;
	
// 	if (crud=="add"){
// 		$('#modal_add_reqdocs').find('.field-required').each(function()
// 		{
// 			if($(this).val().length == 0){			
// 				$('#'+ this.id).parent('div').addClass('has-error');	
// 				iError++;
// 			}else{
// 				$('#'+ this.id).parent('div').removeClass('has-error');	
// 			}	
// 		});	
		
// 		if(iError > 0){
// 			modal_notify('modal_add_reqdocs','<strong>Failed! </strong> Please fill up required fields.','danger');
// 		}
// 	}else if (crud=="edit"){
// 		$('#modal_edit_reqdocs').find('.field-required').each(function()
// 		{
// 			if($(this).val().length == 0){			
// 				$('#'+ this.id).parent('div').addClass('has-error');	
// 				iError++;
// 			}else{
// 				$('#'+ this.id).parent('div').removeClass('has-error');	
// 			}	
// 		});	
		
// 		if(iError > 0){
// 			modal_notify('modal_edit_reqdocs','<strong>Failed! </strong> Please fill up required fields.','danger');
// 		}		
// 	}
// 	return iError;
// }

// function addReqDocs(reqdocs_name, description, bus_division)
// {
// 	var base_url = $('#b_url').attr('data-base-url');

// 	$.ajax({
// 		type:'POST',
// 		data:{reqdocs_name: reqdocs_name, description: description, bus_division: bus_division},
// 		url: base_url + "add_reqdocs",
// 		success: function(result){	
// 			//alert(result);

// 			//$('#modal_add_reqdocs').modal('hide');
			
// 			modal_notify('modal_add_reqdocs','<strong>Success! </strong> Organization Type creation success.','success');

// 			//var span_message = 'Record has been successfully saved!';
// 			//var type = 'success';
// 			//notify(span_message, type);					
			
// 			getReqDocs("all");
// 		}		
// 	}).fail(function(){
		
// 	});	
// }

// function saveReqDocs(reqdocs_id, reqdocs_name, description, bus_division)
// {
// 	var base_url = $('#b_url').attr('data-base-url');
	
// 	$.ajax({
// 		type:'POST',
// 		data:{reqdocs_id: reqdocs_id, reqdocs_name: reqdocs_name, description: description, bus_division: bus_division},
// 		url: base_url + "save_reqdocs",
// 		success: function(result){	
// 			//alert(result);
// 			//$('#modal_edit_reqdocs').modal('hide');
			
// 			//var span_message = 'Currency has been successfully saved!';
// 			//var type = 'success';
// 			//notify(span_message, type);			
			
// 			modal_notify('modal_edit_reqdocs','<strong>Success! </strong> Organization Type update success.','success');

// 			getReqDocs("all");
// 		}		
// 	}).fail(function(){
		
// 	});		
// }

// function editRow(y, reqdocs_id){
// 	var sel_reqdocs_name = document.getElementById("reqdocs_name"+y).innerHTML;
// 	var sel_description = document.getElementById("description"+y).innerHTML;
// 	var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

// 	//alert(reqdocs_id);
// 	$('#edit_reqdocs_id').val(reqdocs_id);
// 	$('#edit_reqdocs_name').val(sel_reqdocs_name);
// 	$('#edit_description').val(sel_description);
// 	if (sel_bus_division == "TRADE"){
// 		$('#edit_bus_division').val("1");
// 	}else if (sel_bus_division == "NON-TRADE"){
// 		$('#edit_bus_division').val("0");
// 	}
// 	$('#modal_edit_reqdocs').modal('show')
// }

// function deactivateYesNo(y,reqdocs_id)
// {
// 	disable_enable_frm('frm_reqdocs', true);
	
// 	$('#row'+y).addClass('danger').siblings().removeClass('danger');

// 	$('#edit'+y).addClass('disabled');
// 	$('#deactivate'+y).addClass('disabled');

// 	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+reqdocs_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
// 	var type = 'info';
// 	notify(span_message, type, true);	
// }

// function deactivateYes(y, reqdocs_id)
// {
// 	var base_url = $('#b_url').attr('data-base-url');	
	
// 	$.ajax({
// 		type:'POST',
// 		data:{reqdocs_id: reqdocs_id},
// 		url: base_url + "deactivate_reqdocs",
// 		success: function(result){	

// 			var json_result = $.parseJSON(result);
			
// 			$('#edit'+y).removeClass('disabled');
// 			$('#deactivate'+y).removeClass('disabled');
// 			$('#row'+y).removeClass('danger')

// 			disable_enable_frm('frm_reqdocs', false);
			
// 			var span_message = 'Record has been deactivated!';
// 			var type = 'info';
// 			notify(span_message, type);		
// 			//alert("1");
// 			getReqDocs("all");
// 		}		
// 	}).fail(function(){
		
// 	});	
// }

// function deactivateNo(y)
// {
// 	$('#edit'+y).removeClass('disabled');
// 	$('#deactivate'+y).removeClass('disabled');
// 	$('#row'+y).removeClass('danger')

// 	disable_enable_frm('frm_reqdocs', false);
// }
