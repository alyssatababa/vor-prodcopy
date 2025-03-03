function clear_on()
{
	 $div_notifications.stop().fadeOut("slow", clean_div_notif);
	 $('#input_name_brand').removeClass('has-error');
	 $('#inpt_msg_desc_brand').removeClass('has-error'); 
}
function reset_modal(){
       
	$('#inpt_msg_desc_brand').parent('div').removeClass('has-error');
	$('#input_name_brand').parent('div').removeClass('has-error');
	$('#inpt_msg_desc_brand').val('');
	$('#input_name_brand').val('');
	$('#inpt_edit_desc_brand').parent('div').removeClass('has-error');
	$('#input_edit_brand').parent('div').removeClass('has-error');
	$('#inpt_edit_desc_brand').val('');
	$('#input_edit_brand').val('');

	$('.modal-body .alert').css("display","none");
}
$(document).ready(function()
{
	clear_on();
	var n = "maintenance/brand/select_brand";
	var m = '';
	loadingScreen('on');
	select_brand(n,m);
	//$div_notifications.stop().fadeOut("slow", clean_div_notif);
});

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		reset_modal();
	}
});

$(document).on("click", "#btn_save_new_brand", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        			$('.alert').css("display","none");
        			$('#inpt_msg_desc_brand').parent('div').removeClass('has-error');
					$('#input_name_brand').parent('div').removeClass('has-error');


					var u_id = $('#input_name_brand').val();
					var re = new RegExp("(^[a-zA-Z0-9\\-\\s.]{5,50})$");
					if (re.test(u_id)) {
					} else {
						$('#input_name_brand').parent('div').addClass('has-error');
						modal_notify('add_new_brand_modal','<strong>Failed! </strong> Special characters are not allowed. Brand Name must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
					    return;
					}

					var u_im = $('#inpt_msg_desc_brand').val();
					var rex = new RegExp("(^[a-zA-Z0-9\\-\\s.,]{1,150})$");
					if (rex.test(u_im)) {
					} else {
						$('#inpt_msg_desc_brand').parent('div').addClass('has-error');
						modal_notify('add_new_brand_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

					var n = "maintenance/brand/add_brand";
					var m = [ $('#input_name_brand').val(),$('#inpt_msg_desc_brand').val()];
					
					loading('btn_save_new_brand','in_progress');
					add_brand(m,n);
}
});

function add_brand(data,url)
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
				loading('btn_save_edit_brand','');
				//modal_notify('add_new_brand_modal','<strong>Success</strong>','success',false);
				setTimeout(function(){
					$('#add_new_brand_modal').modal('hide');
					loading('btn_save_new_brand','');
					//=== NOTIFICATION ===
					var span_message = 'Brand has been successfully saved!';
					var type = 'success';
					notify(span_message, type);		
					var n = "maintenance/brand/select_brand";
					var m = '';
					select_brand(n,m);		
				},900);
						
			}else{
				loading('btn_save_new_brand','');
				modal_notify('add_new_brand_modal','<strong>Failed! </strong> Brand name is already exists.','danger',false);	
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

function select_brand(url,data)
{
	$("#tbl_brand").DataTable().destroy();	
	$.ajax({
		type:'POST',
		data:{data: data},
		url: url,
		success: function(result){
		
			loading('btn_search_brand','');
			var j = JSON.parse(result)
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

$(document).on("click", "#btn_search_brand", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		var n = "maintenance/brand/select_brand";
		var m = $('#inpt_search').val();
		loading('btn_search_brand','in_progress');
		select_brand(n,m);
	}
});
$("#inpt_search").on('keyup', function (e) {
    if (e.keyCode == 13) {
		var n = "maintenance/brand/select_brand";
		var m = $('#inpt_search').val();
		loading('btn_search_brand','in_progress');
		select_brand(n,m);
    }
});

function create_table(data)
{
$("#tbl_brand").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){

	if(data.data[i]['DESCRIPTION'] == null){

		data.data[i]['DESCRIPTION'] = "No Description Available"
	}


	$('<tr><td>'+ (i+1) +'</td><td>'+data.data[i]['BRAND_NAME']+'</td><td>'+data.data[i]['DESCRIPTION']+'</td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>'+data.data[i]['DATE_UPLOADED']+'</td><td><button rel = "'+ data.data[i]['BRAND_ID'] + '/' + data.data[i]['BRAND_NAME'] + '/' + data.data[i]['DESCRIPTION']+ '/' + data.data[i]['STATUS'] +'" class = "btn btn-default btn_view" data-toggle="modal" data-target="#edit_brand_new_modal"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" data-att ="' + data.data[i]['BRAND_ID'] + '" class = "btn btn-default c_del_brand"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_brand');

}
$("#tbl_brand").DataTable({
	dom:'<"top brand"t<"clear">>rt<"bottom brand"p<"clear">>',
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

function clear_not()
{
 $div_notifications.stop().fadeOut("slow", clean_div_notif);

}


$(document).on("click", ".btn_view", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		//$div_notifications.stop().fadeOut("slow", clean_div_notif);
        clear_on();
        var n = $(this).attr('rel').split('/');
        $('#inpt_edit_desc_brand').attr('rel',n[0]);
        $('#inpt_edit_desc_brand').val(n[2]);
        $('#input_edit_brand').val(n[1]);
        $('#chk_sta').prop('checked',false);

        if(n[3] == 1){
        	$('#chk_sta').prop('checked',true);
        }
	}
});

$(document).on("click", "#btn_save_edit_brand", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        		$('.alert').css("display","none");
        		$('#inpt_edit_desc_brand').parent('div').removeClass('has-error');
				$('#input_edit_brand').parent('div').removeClass('has-error');


				var u_id = $('#input_edit_brand').val();			
				var re = new RegExp("(^[a-zA-Z0-9\\-\\s.]{5,50})$");					
				if (re.test(u_id)) {
					} else {
						$('#input_edit_brand').parent('div').addClass('has-error');
						modal_notify('edit_brand_new_modal','<strong>Failed! </strong> Special characters are not allowed. Brand Name must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
					    return;
					}

				var u_im = $('#inpt_edit_desc_brand').val();			
				var rex = new RegExp("(^[a-zA-Z0-9\\-\\s,.]{1,150})$");
				if (rex.test(u_im)) {
					} else {
						$('#inpt_edit_desc_brand').parent('div').addClass('has-error');
						modal_notify('edit_brand_new_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

		var l = 0;
		if($('#chk_sta').prop('checked') == true){

			l =1;

		}	
      	loading('btn_save_edit_brand','in_progress');
        var n = [$('#inpt_edit_desc_brand').attr('rel'),$('#inpt_edit_desc_brand').val(),$('#input_edit_brand').val(),l];
   		var m = "maintenance/brand/edit_brand";
        edit_brand(n,m)

        }
});

function edit_brand(data,url)
{

	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//loading('btn_save_edit_brand','');
				//modal_notify('edit_brand_new_modal','<strong>Success</strong>','success',false);	
				setTimeout(function(){
					loading('btn_save_edit_brand','');
					$('#edit_brand_new_modal').modal('hide');
					var span_message = 'Brand has been successfully edited!';
					var type = 'success';
					notify(span_message, type);	
					
					var n = "maintenance/brand/select_brand";
					var m = $('#inpt_search').val();
					select_brand(n,m);
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

$(document).on("click", ".c_del_brand", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).attr('data-att');
        deactivateYesNo(n);


        }


});

function del_brand(data,url)
{

		$.ajax({
			type:'POST',
			data:{data:  data},
			url: url,
			success: function(result){
			var j = JSON.parse(result)
			if(j == true)
			{
				notify('Brand Removed Successful','success');
				var n = "maintenance/brand/select_brand";
				var m = $('#inpt_search').val();
				select_brand(n,m);

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

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this Brand? <button id = "confirm_brand_yes" type="button" class="btn btn-success" data-yes = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#confirm_brand_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).attr('data-yes');
        var m = "maintenance/brand/del_brand";
        del_brand(n,m)
		
	}
});

$('#add_new_brand_modal').on('hidden.bs.modal', function (e) {
	reset_modal();
});
