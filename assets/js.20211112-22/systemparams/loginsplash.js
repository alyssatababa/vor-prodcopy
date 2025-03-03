$(document).ready(function()
{

var m = "maintenance/loginsplash/get_all";

	ajax_rqst_get(m);


});


function ajax_rqst_get(url)
{		
	$("#tbl_login_splash").DataTable().destroy();	
	setTimeout(function(){
		$.ajax({
			type:'POST',
			data:{},
			url: url,
			success: function(result){
				var das = JSON.parse(result)	
				create_table(das);
		
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
	}, 700);												
}

function create_table(data)
{
	$("#tbl_login_splash").find("tr:gt(0)").remove();	
	var default_rdo = 0;
	for(i=0;i<data.data.length;i++){	
		if(data.data[i]['USER_MIDDLE_NAME'] == null){
			data.data[i]['USER_MIDDLE_NAME'] = '';
		}
		if(data.data[i]['USER_FIRST_NAME'] == null){
			data.data[i]['USER_FIRST_NAME'] = '';
		}
		
		if(data.data[i]['USER_LAST_NAME'] == null){
			data.data[i]['USER_LAST_NAME'] = '';
		}

		if(data.data[i]['SELECTED'] == '1'){
			default_rdo = 1;
		}
		$('<tr><td>' + data.data[i]['LST_TITLE'] +'</td><td>' + data.data[i]['LST_MESSAGE'] +'</td><td>' + toTitleCase(data.data[i]['USER_FIRST_NAME']) + ' '  + toTitleCase(data.data[i]['USER_MIDDLE_NAME']) + ' ' + toTitleCase(data.data[i]['USER_LAST_NAME']) +'</td><td><span style="display:none;">' + default_rdo +'</span><input type ="radio" name ="optradios" id = "rdo'+i+'" class = "c_sels" data-toggle="modal" data-target="#edit_selected_log" data-tid="' + data.data[i]['LST_ID'] + '"></td><td>'  + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>' + data.data[i]['LST_DATE_CREATED'] +'</td><td><button  class = "c_edit_splash btn btn-default" rel = "'+ data.data[i]['LST_TITLE'] + '/' + data.data[i]['LST_MESSAGE'] + '/' + data.data[i]['LST_ID'] + '" href = "" onClick = "clear_notif();" data-toggle="modal" data-target="#edit_splash"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" rel ="' + data.data[i]['LST_ID'] + '" class ="btn btn-default c_del_splash"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_login_splash');	
		if(data.data[i]['SELECTED'] == '1'){
			$("#rdo"+i).prop('checked',true);
		}

		default_rdo = 0;
	}


	$("#tbl_login_splash").DataTable({
		dom:'<"top log_splash"t<"clear">>rt<"bottom log_splash"p<"clear">>',
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
}

function toTitleCase(str) {
	if(str === null){
	   str = "";
	}
    return str.replace(/(?:^|\s)\w/g, function(match) {
        return match.toUpperCase();
    });
}

$(document).on("click", "#btn_save_splash_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

		let _error = [];
		let _eMsg = '';
		$('.alert').css("display","none");
		$('#inpt_splash_new').parent('div').removeClass('has-error');
		$('#cmt_splash_new').parent('div').removeClass('has-error');

		var u_id = document.getElementById('inpt_splash_new').value;				
		var re = new RegExp("(^[a-zA-Z0-9\\-\\s]{0,5000})$");
		if (re.test(u_id)) {
		} else {
			$('#inpt_splash_new').parent('div').addClass('has-error');
			_error.push('sp_name');
		}
		if(u_id.length > 100){
			$('#inpt_splash_new').parent('div').addClass('has-error');
			_error.push('ov_name');
		}
		if(u_id.length <= 0){
			$('#inpt_splash_new').parent('div').addClass('has-error');
			_error.push('r_name');
		}
		var u_im = document.getElementById('cmt_splash_new').value;				
		if(u_im.length > 3000){
			$('#cmt_splash_new').parent('div').addClass('has-error');
			_error.push('ov_cmt');
		}
		if(u_im.length <= 0){
			$('#cmt_splash_new').parent('div').addClass('has-error');
			_error.push('r_cmt');
		}

		if(_error.length > 0){

			for(i=0;i<_error.length;i++){
				let _tmpError = '';
				switch(_error[i]){
					case 'sp_name' : _tmpError = '<strong>Error  :  </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>';
									break;
					case 'ov_name' : _tmpError = '<strong>Error  :  </strong> Maximum length for Name is <Strong>100!</strong><br>';
									break;
					case 'r_name' : _tmpError = '<strong>Error  :  </strong>Template Name is <strong>REQUIRED!</strong> <br>';
									break;
					case 'ov_cmt' : _tmpError = '<strong>Error  :  </strong>Message is <strong>TOO LONG!</strong> <br>';
									break;
					case 'r_cmt' : _tmpError = '<strong>Error  :  </strong>Message is <strong>REQUIRED!</strong> <br>';
									break;
				}
					_eMsg = _eMsg + _tmpError;
			}

			modal_notify('add_splash_new',_eMsg,'danger',true);	
			return;
		}

		//	modal_notify('','<strong>Failed! </strong> Special characters are not allowed. country Name  must not be less than 5 characters and not greater than 100 charactes.','danger',false);

		var n = [$('#cmt_splash_new').val(),$('#inpt_splash_new').val()];
		var m = "maintenance/loginsplash/save_visit_template";
		var z = ajax_rqst(n,m);
	}
});

function ajax_rqst(arr,url)
{			
	var x = $('#bod').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(arr)},
		url: url,
		success: function(result){
			var n = JSON.parse(result);
			if(n.data == true)
			{
				//modal_notify('add_splash_new','<strong>Success! </strong> Template creation success.','success',false);
				$('#add_splash_new').modal('hide');
					
				//=== NOTIFICATION ===
				var span_message = 'Login Splash Screen Template has been successfully saved!';
				var type = 'success';
				notify(span_message, type);		
				var m = "maintenance/loginsplash/get_all";
				ajax_rqst_get(m);
				return;
			}
			return;	

		},error: function(result){
			alert(result + 'e');	
			return;		
		}
	}).fail(function(result){

		alert(result + 'f');
		return;
	});															
}

function val_input(name){

var iError = 0;	
	$('#'+name+' input').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}	
	});	
	return iError;
}

$(document).on("click", ".c_edit_splash", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = ($(this).attr('rel')).split('/');

        $('#cmt_splash_edit').val(n[1]);
         $('#cmt_splash_edit').attr('rel',n[2]);
        $('#inpt_splash_edit').val(n[0]);


   		
    }
});


$(document).on("click", "#btn_edit_save_splash", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.



        let _error = [];
        	let _eMsg = '';

			 		$('.alert').css("display","none");
        			$('#inpt_splash_edit').parent('div').removeClass('has-error');
					$('#cmt_splash_edit').parent('div').removeClass('has-error');

					
					var u_id = document.getElementById('inpt_splash_edit').value;				
					var re = new RegExp("(^[a-zA-Z0-9\\-\\s]{0,5000})$");
					if (re.test(u_id)) {
					} else {
						$('#inpt_splash_edit').parent('div').addClass('has-error');
						_error.push('sp_name');
					}
					if(u_id.length > 100){
						$('#inpt_splash_edit').parent('div').addClass('has-error');
						_error.push('ov_name');
					}
					if(u_id.length <= 0){
						$('#inpt_splash_edit').parent('div').addClass('has-error');
						_error.push('r_name');
					}
					var u_im = document.getElementById('cmt_splash_edit').value;				
					if(u_im.length > 3000){
						$('#cmt_splash_edit').parent('div').addClass('has-error');
						_error.push('ov_cmt');
					}
					if(u_im.length <= 0){
						$('#cmt_splash_edit').parent('div').addClass('has-error');
						_error.push('r_cmt');
					}

			if(_error.length > 0){

					for(i=0;i<_error.length;i++){
						let _tmpError = '';
						switch(_error[i]){
							case 'sp_name' : _tmpError = '<strong>Error  :  </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>';
											break;
							case 'ov_name' : _tmpError = '<strong>Error  :  </strong> Maximum length for Name is <Strong>100!</strong><br>';
											break;
							case 'r_name' : _tmpError = '<strong>Error  :  </strong>Template Name is <strong>REQUIRED!</strong> <br>';
											break;
							case 'ov_cmt' : _tmpError = '<strong>Error  :  </strong>Message is <strong>TOO LONG!</strong> <br>';
											break;
							case 'r_cmt' : _tmpError = '<strong>Error  :  </strong>Message is <strong>REQUIRED!</strong> <br>';
											break;
						}
							_eMsg = _eMsg + _tmpError;
					}

				modal_notify('edit_splash',_eMsg,'danger',true);	
				return;
			}




       


			// var u_id = document.getElementById('inpt_splash_edit').value;				
			// var re = new RegExp("(^[a-zA-Z0-9\\-\\s]{0,5000})$");
			// if (re.test(u_id)) {
			// } else {
			// 	$('#inpt_splash_edit').parent('div').addClass('has-error');
			// 	modal_notify('edit_splash','<strong>Failed! </strong> Special characters are not allowed. Template Name  must not be less than 3 characters and not greater than 100 charactes.','danger',false);	
			// 	return;
			// }
			// 		var u_im = document.getElementById('cmt_splash_edit').value;				
			// 		var rex = new RegExp("(^[a-zA-Z0-9,.\\-\\s]{1,3000})$");
			// 		if (rex.test(u_im)) {
			// 		} else {
			// 			$('#cmt_splash_edit').parent('div').addClass('has-error');
			// 			modal_notify('edit_splash','<strong>Failed! </strong> Special characters are not allowed. Mesaage must not be less than 1 character and not greater than 3000 charactes.','danger',false);	
			// 		    return;
			// 		}


      
        var m = [$('#cmt_splash_edit').attr('rel'),$('#cmt_splash_edit').val(),$('#inpt_splash_edit').val()]
        var n = "maintenance/loginsplash/edit_splash_template";
        save_edit(m,n);

}
});


function save_edit(arr,url)
{			
	var x = $('#bod').attr('data-base-url');
	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(arr)},
		url: url,
		success: function(result){

			var n = JSON.parse(result);
			if(n.data == true)
			{
				//modal_notify('edit_splash','<strong>Success! </strong> Template creation success.','success');
				$('#edit_splash').modal('hide');
				
				//=== NOTIFICATION ===
				var span_message = 'Login Splash Screen Template has been successfully saved!';
				var type = 'success';
				notify(span_message, type);		
			
				var m = "maintenance/loginsplash/get_all";
				ajax_rqst_get(m);
				return;
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


 $(document).on("click", ".c_del_splash", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = $(this).attr('rel');
        deactivateYesNo_splash(n);   

   		
    }
});


function del_edit(arr,url)
{			
		var x = $('#bod').attr('data-base-url');
		$.ajax({
			type:'POST',
			data:{data:  JSON.stringify(arr)},
			url: url,
			success: function(result){
				var n = JSON.parse(result);
				if(n.data == true)
				{
				var m = "maintenance/loginsplash/get_all";
				notify('Template Deleted Successful','success')
				ajax_rqst_get(m);
				return;
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


function deactivateYesNo_splash(n)
{
	var span_message = 'Do you want to delete this template? <button id = "conf_yes_splash" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no_splash" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, false);	
}

$(document).on("click", "#conf_yes_splash", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        	var m = "maintenance/loginsplash/del_login_template"
        	var n = $(this).attr('rel');
        	del_edit(n,m);
}
});


$(document).on("click", ".btn_close_splash", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        

   
        			$('#inpt_splash_new').parent('div').removeClass('has-error');
					$('#cmt_splash_new').parent('div').removeClass('has-error');
					$('#inpt_splash_new').val('');
					$('#cmt_splash_new').val('');


					
        			$('#inpt_splash_edit').parent('div').removeClass('has-error');
					$('#cmt_splash_edit').parent('div').removeClass('has-error');
					$('#inpt_splash_edit').val('');
					$('#cmt_splash_edit').val('');


					$('.alert').css("display","none");
}
});


$(document).on("click", ".c_sels", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.    
        var n =  $(this).attr('data-tid');
        $('#edit_selected_log').attr('data-mval',n);
   		
    }
});


$(document).on("click", "#btn_sel_save_log", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = $('#edit_selected_log').attr('data-mval');
        var m = "maintenance/loginsplash/c_selected_tmplt";
 		ajax_selected(n,m);



   		
    }
});



function ajax_selected(arr,url)
{			

		$.ajax({
			type:'POST',
			data:{data:  arr},
			url: url,
			success: function(result){

				if(result == 'true')
				{				
				
				var m = "maintenance/loginsplash/get_all";
				ajax_rqst_get(m);
				$('#edit_selected_log').modal('toggle');
				notify('Selected Login Splash Template changed successful.','success');
				return;
				}
			
			return;	
			},error: function(result)
			{
				//alert(result + 'e');	
				return;		
			}
			}).fail(function(result){

				//alert(result + 'f');
				return;
			});															
}

function clear_notif()
{
	$div_notifications.stop().fadeOut("slow", clean_div_notif);
}

function hide_show_dpa(n)
{
	let sta = 'hide';

	if(n == 1){

		sta = 'show';
	}

	var span_message = 'Do you want to '+ sta +' the DPA for all users? <button id = "conf_hide_show" type="button" onClick="yes_no_hideshow('+ n +')" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no_splash" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, false);	
}

function yes_no_hideshow(n)
{

	let _l = 'hidden';

	if(n == 1){

		_l = 'shown';
	}
	
		$.ajax({
			type:'POST',
			data:{data:  n},
			url: "maintenance/loginsplash/show_hide_dpa",
			success: function(result){
				console.log(result);
				notify('<strong>Success </strong>DPA will be '+_l+' to new users!.','success');
				if(n == 1){
					$('#btn_show_dpa').prop('disabled',true);
					$('#btn_hide_dpa').prop('disabled',false);
				}else{
					$('#btn_show_dpa').prop('disabled',false);
					$('#btn_hide_dpa').prop('disabled',true);

				}
				return;	
			},error: function(result)
			{
				//alert(result + 'e');	
				return;		
			}
			}).fail(function(result){

				//alert(result + 'f');
				return;
			});		

}
$('#add_splash_new').on('hidden.bs.modal', function (e) {
	$('#inpt_splash_new').parent('div').removeClass('has-error');
	$('#cmt_splash_new').parent('div').removeClass('has-error');
	$('#inpt_splash_new').val('');
	$('#cmt_splash_new').val('');


	
	$('#inpt_splash_edit').parent('div').removeClass('has-error');
	$('#cmt_splash_edit').parent('div').removeClass('has-error');
	$('#inpt_splash_edit').val('');
	$('#cmt_splash_edit').val('');
});
$('#edit_splash').on('hidden.bs.modal', function (e) {
	$('#inpt_splash_new').parent('div').removeClass('has-error');
	$('#cmt_splash_new').parent('div').removeClass('has-error');
	$('#inpt_splash_new').val('');
	$('#cmt_splash_new').val('');


	
	$('#inpt_splash_edit').parent('div').removeClass('has-error');
	$('#cmt_splash_edit').parent('div').removeClass('has-error');
	$('#inpt_splash_edit').val('');
	$('#cmt_splash_edit').val('');
});