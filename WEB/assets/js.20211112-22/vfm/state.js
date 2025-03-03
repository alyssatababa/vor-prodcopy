function clear_on()
{
	 $div_notifications.stop().fadeOut("slow", clean_div_notif);
	 $('#input_name_state').removeClass('has-error');
	 $('#inpt_msg_desc_state').removeClass('has-error'); 
}

function reset_modal(){
	$('#inpt_msg_desc_state').parent('div').removeClass('has-error');
	$('#input_name_state').parent('div').removeClass('has-error');
	$('#inpt_msg_desc_state').val('');
	$('#input_name_state').val('');

	$('#inpt_edit_desc').parent('div').removeClass('has-error');
	$('#input_edit_state').parent('div').removeClass('has-error');
	$('#inpt_edit_desc').val('');
	$('#input_edit_state').val('');

	$('.modal-body .alert').css("display","none");
}

$(document).ready(function()
{
	clear_on();
	var n = "maintenance/state/select_state";
	var m = '';
	loadingScreen('on');
	select_state(n,m);
});

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        reset_modal();
	}
});

$(document).on("click", "#btn_save_new_state", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        			//loading('btn_save_new_state','in_progress');
        
        			$('.alert').css("display","none");
        			$('#inpt_msg_desc_state').parent('div').removeClass('has-error');
					$('#input_name_state').parent('div').removeClass('has-error');


					var u_id = document.getElementById('input_name_state').value;				
					var re = new RegExp("(^[a-zA-Z0-9\\-\\s().,]{1,50})$");
					if (re.test(u_id)) {
					} else {
						$('#input_name_state').parent('div').addClass('has-error');
						modal_notify('add_new_state_modal','<strong>Failed! </strong> Special characters are not allowed except ( (),-. ). State / Province Name  must not be less than 1 character and not greater than 50 charactes.','danger',false);	
					    loading('btn_save_new_state','');
					    return;
					}

					var u_im = document.getElementById('inpt_msg_desc_state').value;				
					var rex = new RegExp("(^[a-zA-Z0-9\\-\\s().,]{1,150})$");
					if (rex.test(u_im)) {
					} else {
						$('#inpt_msg_desc_state').parent('div').addClass('has-error');
						modal_notify('add_new_state_modal','<strong>Failed! </strong> Special characters are not allowed except ( (),-. ). Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    loading('btn_save_new_state','');
					    return;
					}

					var n = "maintenance/state/add_state";
					var m = [ document.getElementById('input_name_state').value,document.getElementById('inpt_msg_desc_state').value];
					add_state(m,n);
}
});

function add_state(data,url)
{

	var x = $('#bod').attr('data-base-url');
$.ajax({
	type:'POST',
	data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
			loading('btn_save_new_state','');
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//modal_notify('add_new_state_modal','<strong>Success</strong>','success',true);
				//=== NOTIFICATION ===
				setTimeout(function(){
					$('#add_new_state_modal').modal('hide');
					var span_message = 'State/Province has been successfully saved!';
					var type = 'success';
					notify(span_message, type, true);		
					
					var n = "maintenance/state/select_state";
					var m =  document.getElementById('inpt_search_state').value;
					select_state(n,m);
				}, 900);
			}		
			return;	

		},error: function(result)
		{
			alert(result + 'e');	
			return;		
		}
	}).fail(function(result){

		alert(result + 'f');
		return;
	});			
}

function select_state(url,data)
{
$("#tbl_state").DataTable().destroy();	
		$.ajax({
			type:'POST',
			data:{data: data},
			url: url,
			success: function(result){			
			var j = JSON.parse(result)
			create_table(j);
			loading('btn_search_state','');

			},error: function(result)
			{
				alert(result + 'e');	
				return;		
			}
			}).fail(function(result){

				alert(result + 'f');
				return;
			});			
}



$(document).on("click", "#btn_search_state", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		//clear_on();
		var m = document.getElementById('inpt_search_state').value;
		var n = "maintenance/state/select_state";
		loading('btn_search_state','in_progress');
		select_state(n,m);
	}
});

$("#inpt_search_state").on('keyup', function (e) {
    if (e.keyCode == 13) {
		var m = document.getElementById('inpt_search_state').value;
		var n = "maintenance/state/select_state";
		loading('btn_search_state','in_progress');
		select_state(n,m);
    }
});
function create_table(data)
{
$("#tbl_state").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){


	$('<tr><td>'+ (i+1) +'</td><td>'+data.data[i]['STATE_PROV_NAME']+'</td><td>'+data.data[i]['DESCRIPTION']+'</td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>'+data.data[i]['DATE_UPLOADED']+'</td><td><button rel = "'+ data.data[i]['STATE_PROV_ID'] + '/' + data.data[i]['STATE_PROV_NAME'] + '/' + data.data[i]['DESCRIPTION']+ '/' + data.data[i]['STATUS'] +'" class = "btn btn-default btn_view_state" data-toggle="modal" data-target="#edit_state_new_modal"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button></span><button href = "" onclick = "return false;" data-state ="' + data.data[i]['STATE_PROV_ID'] + '" class = "btn btn-default c_del_state"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_state');

}
$("#tbl_state").DataTable({
dom:'<"top state"t<"clear">>rt<"bottom state"p<"clear">>',
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
loadingScreen('off');
}




$(document).on("click", ".btn_view_state", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        clear_on();
        var n = $(this).attr('rel').split('/');
        $('#inpt_edit_desc').attr('rel',n[0]);
        $('#inpt_edit_desc').val(n[2]);
        $('#input_edit_state').val(n[1]);
        $('#chk_sta').prop('checked',false);
        if(n[3] == 1){
        	$('#chk_sta').prop('checked',true);
        }
	}
});


$(document).on("click", "#btn_save_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        		loading('btn_save_edit','in_progress');

        		$('.alert').css("display","none");
        		$('#inpt_edit_desc').parent('div').removeClass('has-error');
				$('#input_edit_state').parent('div').removeClass('has-error');


				var u_id = document.getElementById('input_edit_state').value;				
				var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{1,50})$");					
				if (re.test(u_id)) {
					} else {
						$('#input_edit_state').parent('div').addClass('has-error');
						modal_notify('edit_state_new_modal','<strong>Failed! </strong> Special characters are not allowed except ( (),-. ). State / Province Name  must not be less than 1 character1 and not greater than 50 charactes.','danger',false);	
					    loading('btn_save_edit','');
					    return;
					}

				var u_im = document.getElementById('inpt_edit_desc').value;				
				var rex = new RegExp("(^[a-zA-Z0-9\\-\\s().,]{1,150})$");
				if (rex.test(u_im)) {
					} else {
						$('#inpt_edit_desc').parent('div').addClass('has-error');
						modal_notify('edit_state_new_modal','<strong>Failed! </strong> Special characters are not allowed except ( (),-. ). Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    loading('btn_save_edit','');
					    return;
					}

		var l = 0;
		if($('#chk_sta').prop('checked') == true){

			l =1;

		}	

      
        var n = [$('#inpt_edit_desc').attr('rel'),$('#inpt_edit_desc').val(),$('#input_edit_state').val(),l];
   		var m = "maintenance/state/edit_state";
        edit_state(n,m)

        }
});



function edit_state(data,url)
{
	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//modal_notify('edit_state_new_modal','<strong>Success</strong>','success',false);	
				setTimeout(function(){
					$('#edit_state_new_modal').modal('hide');
					var span_message = 'State/Province has been successfully edited!';
					var type = 'success';
					notify(span_message, type);	
					
					
					var n = "maintenance/state/select_state";
					var m = document.getElementById('inpt_search_state').value;
					loading('btn_save_edit','');
					select_state(n,m);
				}, 900);
			}		
			return;	
		},error: function(result)
		{
			alert(result + 'e');	
			return;		
		}
	}).fail(function(result){

		alert(result + 'f');
		return;
	});			
}


$(document).on("click", ".c_del_state", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).attr('data-state');

        deactivateYesNo(n);


        }
});

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this State from the table? <button id = "confirm_state_yes" type="button" class="btn btn-success" data-state = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#confirm_state_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).attr('data-state');
        var m = "maintenance/state/del_state";

     	del_state(n,m);
	
	}
});

function del_state(data,url)
{
		$.ajax({
			type:'POST',
			data:{data:  data},
			url: url,
			success: function(result){
			var j = JSON.parse(result)
			if(j == true)
			{
				notify('State Removed Successful','success',true);			
				var m = $('#inpt_search_state').val();
				var n = "maintenance/state/select_state";
				select_state(n,m);
			}		
			return;	

			},error: function(result)
			{
				alert(result + 'e');	
				return;		
			}
			}).fail(function(result){
				alert(result + 'f');
				return;
			});			
}

$('#add_new_state_modal').on('hidden.bs.modal', function (e) {
	reset_modal();
});
