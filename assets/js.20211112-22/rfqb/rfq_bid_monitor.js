var base_url = $('#b_url').attr('data-base-url');
var failed_status;
var continue_status;
var extend_status;
var next_position;
var reason;
var extension_date = "";
var iError=0,dateError=0;
var sysdate;

function btnAction(action){

	// alert(action);
	var action_modal="";
	var reason_id_error="";
	iError=0;
	dateError = 0;




	if (action == "1"){
		reason = $("#failed_bid_reason").val();
		action_type = "Failed Bid";
		action_modal = "modal_failed_bid";
		var reason_id = document.getElementById('failed_bid_reason').value;	
		reason_id_error = "failed_bid_reason";
	}else if (action == "2"){
		reason = $("#continue_bid_reason").val();
		action_status = continue_status
		action_type = "Continue Bid";
		action_modal = "modal_continue_bid";
		var reason_id = document.getElementById('continue_bid_reason').value;
		reason_id_error = "continue_bid_reason";	
	}else if (action == "3"){
		$('#date_created').parent('div').removeClass('has-error');

		reason = $("#extend_bid_reason").val();
		extension_date = $('#date_created').val();		
		action_type = "Extend Bid";
		action_modal = "modal_extend_bid";
		var reason_id = document.getElementById('extend_bid_reason').value;	
		reason_id_error = "extend_bid_reason";

		//alert(extension_date);
		//alert(sysdate);

		if(new Date(extension_date).getTime() <= new Date(sysdate).getTime())
		{//compare end <=, not >=

		    dateError++;
		    //return;
		}

	}

	$('#' + reason_id_error).parent('div').removeClass('has-error');

	//alert(extension_date);

	$('#'+action_modal).find('.field-required').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}else{
			$('#'+ this.id).parent('div').removeClass('has-error');	
		}	
	});	


	if (reason_id.trim() == ""){
		$('#' + reason_id_error).parent('div').addClass('has-error');
		//modal_notify(action_modal,'<strong>Failed! </strong> Special characters are not allowed.','danger');

		iError ++;			
	}

	//alert(iError + "+" + dateError);

		if(iError > 0){
			modal_notify(action_modal,'<strong>Failed! </strong> Please fill up required fields.','danger');
		}else{
			var regexp_reason = new RegExp("^([a-zA-Z0-9.-\\s]{1,50})$");
			if (regexp_reason.test(reason_id)) {
				if(dateError > 0){
				    modal_notify(action_modal,'<strong>Failed! </strong> Invalid date.','danger');
				    $('#date_created').parent('div').addClass('has-error');
				}else{
					$('#'+action_modal).modal('hide');

				    var span_message = 'Are you sure you want to '+action_type+'? <input type="button" class="btn btn-success" value="Yes" onclick="updateStatus('+action+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
				    var type = 'info';
				    notify(span_message, type, true);	
				}
			} else {
				//alert(reason_id_error);
				// removed because now it accepts special character
				/*$('#' + reason_id_error).parent('div').addClass('has-error');
				modal_notify(action_modal,'<strong>Failed! </strong> Special characters are not allowed.','danger');*/	
			    $('#'+action_modal).modal('hide');

			    var span_message = 'Are you sure you want to '+action_type+'? <input type="button" class="btn btn-success" value="Yes" onclick="updateStatus('+action+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
			    var type = 'info';
			    notify(span_message, type, true);	
				//loading('user_btn_save','');	
			    
			}		
		}
		
	
	



	// setRfqbStatus(id, action_status, reason, next_position);

}

function updateStatus(action){

	var id = $('#rfqb_id').attr('rel');
	var action_status = "", action_type = "";

	if (action == "1"){
		action_status = failed_status;
		action_type = "Failed Bid";
	}else if (action == "2"){
		action_status = continue_status
		action_type = "Continue Bid";
	}else if (action == "3"){
		action_status = extend_status
		action_type = "Extend Bid";
	}

	// alert(id + " " + action_status + " " + reason + " " + next_position + " " + extension_date);

	$.ajax({
		type:'POST',
		data:{rfb_id: id, rfb_status: action_status, action_type: action_type, reason: reason, position_id: next_position, extension_date: extension_date},
		url: base_url + "rfq_bid_monitor/set_rfqb_status",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);

			if (json_result.status != false){
				

				var span_message = action_type + ' success.';
				var type = 'success';
				notify(span_message, type);	

				$('.failed_btn').attr('disabled','disabled');

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

$(document).ready(function(){

	// var base_url = $('#b_url').attr('data-base-url');
	var countDownDate, distance;
	var days, hours, minutes, seconds;
	var id = $('#rfqb_id').attr('rel');

	// alert("btn hide");
	// $('.failed_btn').hide();

	getRfqb(id);
	getRfqbTable(id);
	getRfqbConfig(id);

	$("#btn_close").click(function()
	{
		var action_path = base_url + 'rfq_main/rfqrfb_main_view/';
		//alert(action_path);
		setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},500);				
	});

	function getRfqbConfig(rfb_id){

		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor/get_rfqb_config",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				if (json_result.status != false){
					var curr_position = json_result[0].POSITION_ID
					failed_status = json_result[0].APPROVE_STATUS_ID
					continue_status = json_result[0].REJECT_STATUS_ID
					extend_status = json_result[0].SUSPEND_STATUS_ID
					next_position = json_result[0].NEXT_POSITION_ID
				}else{
					var span_message = 'Record not found!';
					var type = 'danger';
					notify(span_message, type);				
				}
			}		
		}).fail(function(){
			
		});	
	}
	
	function getRfqb(rfb_id){

		// $('.failed_btn').hide();

		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor/get_rfqb",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				$('.rfqb_title').text("Bid Monitor - "+ json_result[0].TITLE);
				$('.rfqb_invited').text(json_result[0].RIV_COUNT);
				$('.rfqb_participants').text(json_result[0].RA_COUNT);
				$('.rfqb_responses').text(json_result[0].RR_COUNT);

				sysdate = new Date(json_result[0].SYS_DATE).getTime();
				countDownDate = new Date(json_result[0].SUB_DATE).getTime();
				distance = countDownDate - sysdate;

				if (distance > 0){
				    days = Math.floor(distance / (1000 * 60 * 60 * 24));
				    hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
				    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
				    seconds = Math.floor((distance % (1000 * 60)) / 1000);
				    
					$('#bid_timer').text(days + " days " + hours + " hours "
						    + minutes + " mins " + seconds + " secs ");

					bid_clock = setInterval(function() {

						distance=distance-3600000;

					    if (distance < 0) {
					        clearInterval(bid_clock);

					        if ((json_result[0].RFQRFB_TYPE == "1" && json_result[0].RR_COUNT < 1) || (json_result[0].RFQRFB_TYPE == "2" && json_result[0].RR_COUNT < 3)){
					        	// alert("btn show");
					        	$('#failed_bid').show();
					        }
					        // alert("btn show");
					        $('.failed_btn').show();	
					        $('#bid_timer').text("0 days 0 hours 0 mins 0 secs ");	
					    }else{
					    
					    days = Math.floor(distance / (1000 * 60 * 60 * 24));
					    hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
					    minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
					    seconds = Math.floor((distance % (1000 * 60)) / 1000);
					    
						$('#bid_timer').text(days + " days " + hours + " hours "
							    + minutes + " mins " + seconds + " secs ");
					    }
					}, 1000);

				}else{
			        if ((json_result[0].RFQRFB_TYPE == "1" && json_result[0].RR_COUNT < 1) || (json_result[0].RFQRFB_TYPE == "2" && json_result[0].RR_COUNT < 3)){
			        	$('#failed_bid').show();
			        }
			        // alert("btn show");
			        $('.failed_btn').show();	
			        $('#bid_timer').text("0 days 0 hours 0 mins 0 secs ");	
				}



				// alert(distance);

			    // days = Math.floor(distance / (1000 * 60 * 60 * 24));
			    // hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			    // minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
			    // seconds = Math.floor((distance % (1000 * 60)) / 1000);

			  	// if (distance < 0) {

			   //      clearInterval(bid_clock);

			   //      if ((json_result[0].RFQRFB_TYPE == "1" && json_result[0].RR_COUNT < 1) || (json_result[0].RFQRFB_TYPE == "2" && json_result[0].RR_COUNT < 3)){
			   //      	$('#failed_bid').show();
			   //      }
			   //      $('.failed_btn').show();	
			   //      $('#bid_timer').text("0 days 0 hours 0 mins 0 secs ");	        
			   //  }

			    $('#submission_deadline').text(json_result[0].SUB_DATE);
			    	
			}		
		}).fail(function(){
			
		});			
	}

	function getRfqbTable(rfb_id){
		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id},
			url: base_url + "rfq_bid_monitor/get_rfqb_table",
			success: function(result){	
				// alert(result);
				var json_result = $.parseJSON(result);

				if (json_result.status != false){
					$("#tbl_view").DataTable().destroy();
					createDataTable(json_result);
					$("#tbl_view").DataTable({
						"pageLength": 10
					});
					//createDataTable(json_result);
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
		$.ajax({
			type:'POST',
			data:{rfb_id: rfb_id, sub_date: sub_date},
			url: base_url + "rfq_bid_monitor/set_sub_deadline",
			success: function(result){	
				alert(result);
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

	// function loadRfqbMain(){

	// 	alert(base_url);
	// 	$.ajax({
	// 		type:'POST',
	// 		url: base_url + "rfq_main/rfqrfb_main_view",
	// 		success: function(result){	
	// 			$('#rfqb_id').text(result);
	// 			// var json_result = $.parseJSON(result);

	// 			// if (json_result.status != false){

	// 			// }else{
	// 			// 	var span_message = 'Record not found!';
	// 			// 	var type = 'danger';
	// 			// 	notify(span_message, type);				
	// 			// }
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
			var inv_status = ((obj_result[x].INVITE_STATUS == 0) ? "NO RESPONSE" : obj_result[x].INVITE_STATUS);
			var ra_date = ((obj_result[x].RA_DATE == 0) ? "" : obj_result[x].RA_DATE);
			var rr_date = ((obj_result[x].RR_DATE == 0) ? "" : obj_result[x].RR_DATE);
			$('<tr id="row'+y+'" class="clickable-row"><td id="reqdocs_name'+y+'">'+obj_result[x].VENDOR_NAME+'</td><td id="description'+y+'">'+inv_status+'</td><td id="bus_division'+y+'">'+ra_date+'</td><td id="reqdocs_date'+y+'">'+rr_date+'</td></tr>').appendTo('#tbl_body_rfqb_monitor');
		}
	}






});