$(document).ready(function()
{

var m = "maintenance/submitteddoc/get_all";
ajax_rqst_get(m);
});


function ajax_rqst_get(url)
{		
	$("#tbl_sub_doc").DataTable().destroy();	
	setTimeout(function(){
		$.ajax({
			type:'POST',
			data:{},
			url: url,
			success: function(result){
				
				var das = JSON.parse(result);
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
	}, 800);
}

function create_table(data)
{
	$("#tbl_sub_doc").find("tr:gt(0)").remove();	
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
		
		$('<tr><td>' + data.data[i]['SDT_TITLE'] +'</td><td>' + data.data[i]['SDT_MSG'] +'</td><td>' + toTitleCase(data.data[i]['USER_FIRST_NAME']) + ' '  + toTitleCase(data.data[i]['USER_MIDDLE_NAME']) + ' ' + toTitleCase(data.data[i]['USER_LAST_NAME']) +'</td><td><span style="display:none;">' + default_rdo +'</span><input type ="radio" name ="optradio" id = "rdo'+i+'" class = "c_sel" data-toggle="modal" data-target="#edit_selected_sub" data-tid="' + data.data[i]['SDT_ID'] + '"></td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>' + data.data[i]['SDT_DATE_CREATED'] +'</td><td><button class = "btn btn-default c_edit" rel = "'+ data.data[i]['SDT_TITLE'] + '/' + data.data[i]['SDT_MSG'] + '/' + data.data[i]['SDT_ID'] + '" href = "" onClick = "clear_notif();" data-toggle="modal" data-target="#edit_sub_temp"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" rel ="' + data.data[i]['SDT_ID'] + '" class = "btn btn-default c_del"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td>').appendTo('#tbl_sub_doc');	
		
		if(data.data[i]['SELECTED'] == '1'){
			$("#rdo"+i).prop('checked',true);
		}
		default_rdo = 0;
	}

	$("#tbl_sub_doc").DataTable({
		dom:'<"top subdoc"t<"clear">>rt<"bottom subdoc"p<"clear">>',
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

$(document).on("click", "#btn_save_message_sub", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

		let _lerror = [];

		$('#inpt_sub_name_new').parent('div').removeClass('has-error');
		$('#cmt_log_sub_new').parent('div').removeClass('has-error');
		$('.alert').css("display","none");

		var u_id = document.getElementById('inpt_sub_name_new').value;	
		let u_im = document.getElementById('cmt_log_sub_new').value;

		if(u_id.length == 0){
			_lerror.push("<strong>Error : </strong> Template Name is Empty!  <br>");
			$('#inpt_sub_name_new').parent('div').addClass('has-error');
		}
		if(u_id.length > 30){
			_lerror.push("<strong>Error : </strong> Max allowed number of character for Template Name is 30!  <br>");
			$('#inpt_sub_name_new').parent('div').addClass('has-error');
		}
		var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{0,5000})$");
		if (re.test(u_id)){} 
			else {
				$('#inpt_sub_name_new').parent('div').addClass('has-error');
				_lerror.push('<strong>Error  : </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>');
		}
		if(u_im.length == 0){
		$('#cmt_log_sub_new').parent('div').addClass('has-error');
			_lerror.push("<strong>Error : </strong> Message is required! <br>");
		}

		if(u_im.length > 3000){
			$('#cmt_log_sub_new').parent('div').addClass('has-error');
			_lerror.push("<strong>Error : </strong> Message is too Long. Max allowed number of character for Template Message is 3000! <br>");
		}

		if(_lerror.length > 0){
			let tmp_error = "";
			for(i=0;i<_lerror.length;i++){
				tmp_error = tmp_error + _lerror[i];
			}

			modal_notify('add_new_sub',tmp_error,'danger',false);
			return;
		}

		//var u_id = document.getElementById('inpt_sub_name_new').value;				
		// var re = new RegExp("(^[a-zA-Z0-9\\-\\s,.()]{5,20})$");
		// if (re.test(u_id)) {
		// } else {
		// 	$('#inpt_sub_name_new').parent('div').addClass('has-error');
		// 	modal_notify('add_new_sub','<strong>Failed! </strong> Special characters are not allowed. Template Name  must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
		//     return;
		// }

		// var u_im = document.getElementById('cmt_log_sub_new').value;				
		// var rex = new RegExp("(^[a-zA-Z0-9\\-\\s(),.]{1,3000})$");
		// if (rex.test(u_im)) {
		// } else {
		// 	$('#cmt_log_sub_new').parent('div').addClass('has-error');
		// 	modal_notify('add_new_sub','<strong>Failed! </strong> Special characters are not allowed. Message must not be less than 1 character and not greater than 3000 charactes.','danger',false);	
		//     return;
		// }


		var n = [$('#cmt_log_sub_new').val(),$('#inpt_sub_name_new').val()];
		var m = "maintenance/submitteddoc/save_visit_template";
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
			console.log(n);
			if(n.data == true)
			{
				$(document).ready(function(){
					var m = "maintenance/submitteddoc/get_all";	
					ajax_rqst_get(m);
					$('.btn_close_sub').trigger('click');
					//notify('<strong>Success! </strong> Template creation success.','success');
					
					$('#add_new_sub').modal('hide');
				
					//=== NOTIFICATION ===
					var span_message = '<strong>Success! </strong> Template creation success.';
					var type = 'success';
					notify(span_message, type);		
					return;
				});
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


$(document).on("click", ".c_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = ($(this).attr('rel')).split('/');

        $('#cmt_log_sub_edit').val(n[1]);
         $('#cmt_log_sub_edit').attr('rel',n[2]);
        $('#inpt_sub_name_edit').val(n[0]);


   		
    }
});


$(document).on("click", "#btn_edit_save_sub", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


        	$('#inpt_sub_name_edit').parent('div').removeClass('has-error');
        	$('#cmt_log_sub_edit').parent('div').removeClass('has-error');
        	$('.alert').css("display","none");
        	

        var u_id = document.getElementById('inpt_sub_name_edit').value;				
					var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{5,30})$");
					if (re.test(u_id)) {
					} else {
						$('#inpt_sub_name_edit').parent('div').addClass('has-error');
						modal_notify('edit_sub_temp','<strong>Failed! </strong> Special characters are not allowed. Template Name  must not be less than 5 characters and not greater than 30 charactes.','danger',false);	
					    return;
					}

					var u_im = document.getElementById('cmt_log_sub_edit').value;				
					var rex = new RegExp("(^[a-zA-Z0-9\\-\\s().,]{1,3000})$");
					if (rex.test(u_im)) {
					} else {
						$('#cmt_log_sub_edit').parent('div').addClass('has-error');
						modal_notify('edit_sub_temp','<strong>Failed! </strong> Special characters are not allowed. Message must not be less than 1 character and not greater than 3000 charactes.','danger',false);	
					    return;
					}

        
        var m = [$('#cmt_log_sub_edit').attr('rel'),$('#cmt_log_sub_edit').val(),$('#inpt_sub_name_edit').val()]
        var n = "maintenance/submitteddoc/edit_visit_template";

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
					//modal_notify('edit_sub_temp','<strong>Success! </strong> Template creation success.','success',false);
					$('#edit_sub_temp').modal('hide');
				
					//=== NOTIFICATION ===
					var span_message = '<strong>Success! </strong> Template creation has been successfully saved.';
					var type = 'success';
					notify(span_message, type);		
					var m = "maintenance/submitteddoc/get_all";
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


$(document).on("click", ".c_del", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		var n = $(this).attr('rel');       	
		deactivateYesNo(n);        
		return;

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

				var m = "maintenance/submitteddoc/get_all";

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


function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this template? <button id = "conf_yes_sub" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#conf_yes_sub", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		var m = "maintenance/submitteddoc/del_visit_template"
		var n = $(this).attr('rel');
		del_edit(n,m);
	}
});

$(document).on("click", "#conf_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


}
});



$(document).on("click", ".btn_close_sub", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        

        			$('#inpt_sub_name_edit').parent('div').removeClass('has-error');
					$('#inpt_sub_name_new').parent('div').removeClass('has-error');
					$('#cmt_log_sub_edit').parent('div').removeClass('has-error');
					$('#cmt_log_sub_new').parent('div').removeClass('has-error');
					$('#cmt_log_sub_edit').val('');
					$('#cmt_log_sub_new').val('');
					$('#inpt_sub_name_edit').val('');
					$('#inpt_sub_name_new').val('');
					$('.alert').css("display","none");
}
});


$(document).on("click", ".c_sel", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.    
        var n =  $(this).attr('data-tid');
        $('#edit_selected_sub').attr('data-mval',n);
   		
    }
});


$(document).on("click", "#btn_sel_save", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = $('#edit_selected_sub').attr('data-mval');
        var m = "maintenance/submitteddoc/c_selected_tmplt";
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
				var m = "maintenance/submitteddoc/get_all";
				ajax_rqst_get(m);
				$('#edit_selected_sub').modal('toggle');
				notify('Selected Submitted Doc Notification Template changed successful.','success');
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

function clear_notif()
{
		$div_notifications.stop().fadeOut("slow", clean_div_notif);
}

