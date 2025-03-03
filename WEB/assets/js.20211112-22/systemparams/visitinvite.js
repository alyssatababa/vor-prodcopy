var _selRmv_vst = [];


$(document).ready(function()
{

var m = "maintenance/visitinvite/get_all";
				ajax_rqst_get(m);
});


function ajax_rqst_get(url)
{		

	$("#tbl_visit_invite").DataTable().destroy();	
	setTimeout(function(){
		$.ajax({
			type:'POST',
			data:{},
			url: url,
			success: function(result){

				var das = JSON.parse(result)	
				create_table(das)
				
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
$("#tbl_visit_invite").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){	
	if(data.data[i]['USER_MIDDLE_NAME'] == null){
		data.data[i]['USER_MIDDLE_NAME'] = '';
	}
	$('<tr><td><input type = "checkbox" data-sel = "'+data.data[i]['VST_ID']+'" onclick = "check_select(this)"></td><td>' + data.data[i]['VST_INV_TITLE'] +'</td><td class="class_s">' + data.data[i]['VST_INV_MSG'] +'</td><td>' + toTitleCase(data.data[i]['USER_FIRST_NAME']) + ' '  + toTitleCase(data.data[i]['USER_MIDDLE_NAME']) + ' ' + toTitleCase(data.data[i]['USER_LAST_NAME']) +'</td><td>'+ '<span style="display:none;"> ' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>' + data.data[i]['VST_DATE_CREATED'] +'</td><td><button class = "btn btn-default c_edit_visit" rel = "'+ data.data[i]['VST_INV_TITLE'] + '/' + data.data[i]['VST_INV_MSG'] + '/' + data.data[i]['VST_ID'] + '" href = "" onclick = "add_close()" data-toggle="modal" data-target="#edit_vis"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><span>  </span><button href = "" onclick = "return false;" rel ="' + data.data[i]['VST_ID'] + '" class = "btn btn-default c_del"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></button></span></td>').appendTo('#tbl_visit_invite');	
}
$("#tbl_visit_invite").DataTable({
dom:'<"top visit_temp"t<"clear">>rt<"bottom visit_temp"p<"clear">>',
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

$(document).on("click", "#btn_save_visit_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


        	$('#inpt_visit_new').parent('div').removeClass('has-error');
        	$('#cmt_visit_new').parent('div').removeClass('has-error');
        //	$('.alert').css("display","none");
        	
        	let _lerror = [];

     //    var u_id = document.getElementById('inpt_visit_new').value;				
					// var re = new RegExp("(^[a-zA-Z0-9\\-\\s,.]{3,50})$");
					// if (re.test(u_id)) {
					// } else {
					// 	$('#inpt_visit_new').parent('div').addClass('has-error');
					// 	modal_notify('add_vis_temp','<strong>Failed! </strong> Special characters are not allowed. Template Name  must not be less than 3 characters and not greater than 20 charactes.','danger',false);	
					//     return;
					// }

					// var u_im = document.getElementById('cmt_visit_new').value;				
					// var rex = new RegExp("(^[a-zA-Z0-9\\-.,\\s()]{1,150})$");
					// if (rex.test(u_im)) {
					// } else {
					// 	$('#cmt_visit_new').parent('div').addClass('has-error');
					// 	modal_notify('add_vis_temp','<strong>Failed! </strong> Special characters are not allowed. Message must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					//     return;
					// }

					var u_id = document.getElementById('inpt_visit_new').value;	
        			let u_im = document.getElementById('cmt_visit_new').value;

        			if(u_id.length == 0){
        				_lerror.push("<strong>Error : </strong> Template Name is Empty!  <br>");
        				$('#inpt_visit_new').parent('div').addClass('has-error');
        			}
        			if(u_id.length > 30){
        				_lerror.push("<strong>Error : </strong> Max allowed number of character for Template Name is 30!  <br>");
        				$('#inpt_visit_new').parent('div').addClass('has-error');
        			}
  					var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{0,5000})$");
					if (re.test(u_id)){} 
						else {
							$('#inpt_visit_new').parent('div').addClass('has-error');
							_lerror.push('<strong>Error  : </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>');
					}
					if(u_im.length == 0){
					$('#cmt_visit_new').parent('div').addClass('has-error');
						_lerror.push("<strong>Error : </strong> Message is required! <br>");
					}

					if(u_im.length > 300){
						$('#cmt_visit_new').parent('div').addClass('has-error');
						_lerror.push("<strong>Error : </strong> Message is too Long. Max allowed number of character for Template Message is 300! <br>");
					}

					if(_lerror.length > 0){
						let tmp_error = "";
						for(i=0;i<_lerror.length;i++){
							tmp_error = tmp_error + _lerror[i];
						}

						modal_notify('add_vis_temp',tmp_error,'danger',false);
						return;
					}


	var n = [$('#cmt_visit_new').val(),$('#inpt_visit_new').val()];
	var m = "maintenance/visitinvite/save_visit_template";
	var z = ajax_rqsts(n,m);

}
});


function ajax_rqsts(arr,url)
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
				
        			$('#inpt_visit_new').parent('div').removeClass('has-error');
					$('#cmt_visit_new').parent('div').removeClass('has-error');
					$('#inpt_visit_new').val('');
					$('#cmt_visit_new').val('');

				$('#btn_close').trigger('click');
				$('#add_vis_temp').modal('toggle');
				//$('#').toggle();	
				notify('<strong>Success! </strong> Template creation success.','success');
				var m = "maintenance/visitinvite/get_all";
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


$(document).on("click", ".c_edit_visit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var n = ($(this).attr('rel')).split('/');
        $('#cmt_visit_edit').val(n[1]);
         $('#cmt_visit_edit').attr('rel',n[2]);
        $('#inpt_visit_edit').val(n[0]);


   		
    }
});


$(document).on("click", "#btn_visit_save_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

		
			$('#inpt_visit_edit').parent('div').removeClass('has-error');
        	$('#cmt_visit_edit').parent('div').removeClass('has-error');
        	//$('.alert').css("display","none");
        	

     //    var u_id = document.getElementById('inpt_visit_edit').value;				
					// var re = new RegExp("(^[a-zA-Z0-9\\-\\s,.]{3,50})$");
					// if (re.test(u_id)) {
					// } else {
					// 	$('#inpt_visit_edit').parent('div').addClass('has-error');
					// 	modal_notify('edit_vis','<strong>Failed! </strong> Special characters are not allowed. Template Name  must not be less than 3 characters and not greater than 50 charactes.','danger',false);	
					//     return;
					// }

					// var u_im = document.getElementById('cmt_visit_edit').value;				
					// var rex = new RegExp("(^[a-zA-Z0-9\\-.,\\s()]{1,150})$");
					// if (rex.test(u_im)) {
					// } else {
					// 	$('#cmt_visit_edit').parent('div').addClass('has-error');
					// 	modal_notify('edit_vis','<strong>Failed! </strong> Special characters are not allowed. Message must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					//     return;
					// }
				let _lerror = [];


					var u_id = document.getElementById('inpt_visit_edit').value;	
        			let u_im = document.getElementById('cmt_visit_edit').value;

        			if(u_id.length == 0){
        				_lerror.push("<strong>Error : </strong> Template Name is Empty!  <br>");
        				$('#inpt_visit_edit').parent('div').addClass('has-error');
        			}
        			if(u_id.length > 30){
        				_lerror.push("<strong>Error : </strong> Max allowed number of character for Template Name is 30!  <br>");
        				$('#inpt_visit_edit').parent('div').addClass('has-error');
        			}
  					var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{0,5000})$");
					if (re.test(u_id)){} 
						else {
							$('#inpt_visit_edit').parent('div').addClass('has-error');
							_lerror.push('<strong>Error  : </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>');
					}
					if(u_im.length == 0){
					$('#cmt_visit_edit').parent('div').addClass('has-error');
						_lerror.push("<strong>Error : </strong> Message is required! <br>");
					}

					if(u_im.length > 300){
						$('#cmt_visit_edit').parent('div').addClass('has-error');
						_lerror.push("<strong>Error : </strong> Message is too Long. Max allowed number of character for Template Message is 300! <br>");
					}

					if(_lerror.length > 0){
						let tmp_error = "";
						for(i=0;i<_lerror.length;i++){
							tmp_error = tmp_error + _lerror[i];
						}

						modal_notify('edit_vis',tmp_error,'danger',false);
						return;
					}


        
        var m = [$('#cmt_visit_edit').attr('rel'),$('#cmt_visit_edit').val(),$('#inpt_visit_edit').val()]
        var n = "maintenance/visitinvite/edit_visit_template";

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

				//console.log(result);
				var n = JSON.parse(result);
				if(n.data == true)
				{

				$('#edit_vis').modal('toggle');
				notify('<strong>Success! </strong> Template creation success.','success',false);
				var m = "maintenance/visitinvite/get_all";
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
				var m = "maintenance/visitinvite/get_all";
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
	var span_message = 'Do you want to delete this template? <button id = "conf_yes" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#conf_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        	var m = "maintenance/visitinvite/del_visit_template"
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



$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        

        			$('#inpt_visit_new').parent('div').removeClass('has-error');
					$('#cmt_visit_new').parent('div').removeClass('has-error');
					$('#inpt_visit_new').val('');
					$('#cmt_visit_new').val('');


					$('#inpt_visit_edit').parent('div').removeClass('has-error');
					$('#cmt_visit_edit').parent('div').removeClass('has-error');
					$('#inpt_visit_edit').val('');
					$('#cmt_visit_edit').val('');


					//$('.alert').css("display","none");
}
});


function check_select(x)
{

$div_notifications.stop().fadeOut("slow", clean_div_notif);  
var n = x.getAttribute('data-sel');

if(x.checked){
	_selRmv_vst.push(n);
	return;
}

	for(i=0;i<_selRmv_vst.length;i++){
		if(n == _selRmv_vst[i]){
		_selRmv_vst.splice(i,1);
		break;
		}
	}
return;

}


$(document).on("click", "#btn_sel_vis_template", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        	$div_notifications.stop().fadeOut("slow", clean_div_notif);  
        	if(_selRmv_vst.length == 0 ){
        		notify('No Selected Template','danger');
        		return;
        	}


        	deact_multi_vst('del_arr');


}
});

function deact_multi_vst(_bid)
{
	var span_message = 'Delete selected templates? <button id = "'+_bid+'" type="button" class="btn btn-success">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}


$(document).on("click", "#del_arr", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	del_mult_vis(_selRmv_vst,'maintenance/visitinvite/del_visit_template_mul');

}
});

function add_close(){

	//$div_notifications.stop().fadeOut("slow", clean_div_notif);  
}
//hhhhhhhh


function del_mult_vis(arr,url)
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
				var m = "maintenance/visitinvite/get_all";
				notify('Template Deleted Successful','success');

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

