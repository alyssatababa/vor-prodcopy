
$(document).ready(function()
{
	clear_on();
	var n = "maintenance/city/select_city";
	var m = document.getElementById('inpt_search_city').value;
	loadingScreen('on');
	select_city(n,m);
});

function clear_on()
{
	 $div_notifications.stop().fadeOut("slow", clean_div_notif);
	 $('#input_name_city').removeClass('has-error');
	 $('#inpt_msg_desc_city').removeClass('has-error'); 
}

function reset_modal(){
	$('#inpt_msg_desc_city').parent('div').removeClass('has-error');
	$('#input_name_city').parent('div').removeClass('has-error');
	$('#inpt_msg_desc_city').val('');
	$('#input_name_city').val('');

	$('#inpt_edit_desc_city').parent('div').removeClass('has-error');
	$('#input_edit_city').parent('div').removeClass('has-error');
	$('#inpt_edit_desc_city').val('');
	$('#input_edit_city').val('');

	$('.modal-body .alert').css("display","none");
}

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		reset_modal();
	}
});

$(document).on("click", "#btn_save_new_city", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        			$('.alert').css("display","none");
        			$('#inpt_msg_desc_city').parent('div').removeClass('has-error');
					$('#input_name_city').parent('div').removeClass('has-error');


					var u_id = document.getElementById('input_name_city').value;				
					var re = new RegExp("(^[a-zA-Z0-9\\-\\sÑñ()]{1,50})$");
					if (re.test(u_id)) {
					} else {
						$('#input_name_city').parent('div').addClass('has-error');
						modal_notify('add_new_modal_city','<strong>Failed! </strong> Special characters are not allowed. City Name  must not be less than 1 character and not greater than 50 charactes.','danger',false);	
					    return;
					}

					var u_im = document.getElementById('inpt_msg_desc_city').value;			
					var rex = new RegExp("(^[a-zA-Z0-9\\-\\s.,Ññ()]{1,150})$");
					if (rex.test(u_im)) {
					} else {
						$('#inpt_msg_desc_city').parent('div').addClass('has-error');
						modal_notify('add_new_modal_city','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

					var n = "maintenance/city/add_city";
					var m = [ document.getElementById('input_name_city').value,document.getElementById('inpt_msg_desc_city').value];
add_city(m,n);
}
});

function add_city(data,url)
{
	var x = $('#bod').attr('data-base-url');
	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//modal_notify('add_new_modal_city','<strong>Success</strong>','success',false);
				setTimeout(function(){
					$('#add_new_modal_city').modal('hide');
					
					//=== NOTIFICATION ===
					var span_message = 'City has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
					
					var n = "maintenance/city/select_city";
					var m = document.getElementById('inpt_search_city').value;
					//loadingScreen('on');
					select_city(n,m);	
				},900);				
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

function select_city(url,data)
{	
	$("#tbl_city").DataTable().destroy();	
		$.ajax({
			type:'POST',
			data:{data: data},
			url: url,
			success: function(result){			
			var j = JSON.parse(result)
			loading('btn_search_city','');
			create_table(j);
			

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

$(document).on("click", "#btn_search_city", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		//clear_on();
		var n = "maintenance/city/select_city";
		var m = document.getElementById('inpt_search_city').value;
		loading('btn_search_city','in_progress');
		//loadingScreen('on');
		select_city(n,m);
	}
});
$("#inpt_search_city").on('keyup', function (e) {
    if (e.keyCode == 13) {
		var n = "maintenance/city/select_city";
		var m = document.getElementById('inpt_search_city').value;
		loading('btn_search_city','in_progress');
		//loadingScreen('on');
		select_city(n,m);
    }
});

function create_table(data)
{
$("#tbl_city").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){

	$('<tr><td>'+ (i+1) +'</td><td>'+data.data[i]['CITY_NAME']+'</td><td>'+data.data[i]['DESCRIPTION']+'</td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>'+data.data[i]['DATE_UPLOADED']+'</td><td><button rel = "'+ data.data[i]['CITY_ID'] + '/' + data.data[i]['CITY_NAME'] + '/' + data.data[i]['DESCRIPTION']+ '/' + data.data[i]['STATUS'] +'" class = "btn btn-default  btn_view_city_city" data-toggle="modal" data-target="#edit_city_new_modal"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" data-cid ="' + data.data[i]['CITY_ID'] + '" class = "btn btn-default c_del_city"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_city');

}
$("#tbl_city").DataTable({
dom:'<"top state"t<"clear">>rt<"bottom city"p<"clear">>',
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

$(document).on("click", ".btn_view_city_city", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        clear_on();
        var n = $(this).attr('rel').split('/');
        $('#inpt_edit_desc_city').attr('rel',n[0]);
        $('#inpt_edit_desc_city').val(n[2]);
        $('#input_edit_city').val(n[1]);
        $('#chk_sta').prop('checked',false);
        if(n[3] == 1){
        	$('#chk_sta').prop('checked',true);
        }
	}
});

$(document).on("click", ".c_del_city", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

      var n =  $(this).attr('data-cid');

        deactivateYesNo(n)

       }
});

$(document).on("click", "#del_city_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

      var n =  $(this).attr('data-city');
      var m = "maintenance/city/del_city";

      del_city(n,m);

    
       }
});

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this City from the list? <button id = "del_city_yes" type="button" class="btn btn-success" data-city = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#btn_save_edit_city", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        		$('.alert').css("display","none");
        		$('#inpt_edit_desc_city').parent('div').removeClass('has-error');
				$('#input_edit_city').parent('div').removeClass('has-error');

				var u_id = document.getElementById('input_edit_city').value;				
				var re = new RegExp("(^[a-zA-Z0-9\\-\\s()Ññ.,]{1,50})$");					
				if (re.test(u_id)) {
					} else {
						$('#input_edit_city').parent('div').addClass('has-error');
						modal_notify('edit_city_new_modal','<strong>Failed! </strong> Special characters are not allowed. City Name  must not be less than 1 character and not greater than 50 charactes.','danger',false);	
					    return;
					}

				var u_im = document.getElementById('inpt_edit_desc_city').value;				
				var rex = new RegExp("(^[a-zA-Z0-9\\-\\s()Ññ,.]{1,150})$");
				if (rex.test(u_im)) {
					} else {
						$('#inpt_edit_desc_city').parent('div').addClass('has-error');
						modal_notify('edit_city_new_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

		var l = 0;
		if($('#chk_sta').prop('checked') == true){

			l =1;

		}	

      
        var n = [$('#inpt_edit_desc_city').attr('rel'),$('#inpt_edit_desc_city').val(),$('#input_edit_city').val(),l];
   		var m = "maintenance/city/edit_city";
   		//loading('btn_save_edit_city','in_progress');
        edit_city(n,m)

        }
});



function edit_city(data,url)
{

	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
			var j = JSON.parse(result)
			if(j.data== true)
			{	
				//modal_notify('add_new_modal_city','<strong>Success</strong>','success',false);
				setTimeout(function(){
					$('#edit_city_new_modal').modal('hide');
					
					//=== NOTIFICATION ===
					var span_message = 'City has been successfully edited!';
					var type = 'success';
					notify(span_message, type);	
					
					var n = "maintenance/city/select_city";
					var m = document.getElementById('inpt_search_city').value;			

					//loading('btn_save_edit_city','')
					//loadingScreen('on');
					select_city(n,m);
					//modal_notify('edit_city_new_modal','<strong>Success</strong>','success',false);	
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


function del_city(data,url)
{
		$.ajax({
			type:'POST',
			data:{data:  data},
			url: url,
			success: function(result){

			var j = JSON.parse(result)
			if(j == true)
			{
						
				var m = document.getElementById('inpt_search_city').value;
				var n = "maintenance/city/select_city";
				//loadingScreen('on');
				select_city(n,m);

				notify('City Removed Successful','success',true);	
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

$('#add_new_modal_city').on('hidden.bs.modal', function (e) {
	reset_modal();
});



