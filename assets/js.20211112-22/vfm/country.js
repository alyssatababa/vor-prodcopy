function clear_on()
{
	 $div_notifications.stop().fadeOut("slow", clean_div_notif);
	 $('#input_name_country').removeClass('has-error');
	 $('#inpt_msg_desc_country').removeClass('has-error'); 
}

function reset_modal(){
	$('#inpt_msg_desc_country').parent('div').removeClass('has-error');
	$('#input_name_country').parent('div').removeClass('has-error');
	$('#inpt_msg_desc_country').val('');
	$('#input_name_country').val('');

	$('#inpt_edit_desc_country').parent('div').removeClass('has-error');
	$('#input_edit_country').parent('div').removeClass('has-error');
	$('#inpt_edit_desc_country').val('');
	$('#input_edit_country').val('');

	$('.modal-body .alert').css("display","none");
}

$(document).ready(function()
{
	clear_on();
	var n = "maintenance/country/select_country";
	var m = '';
	loadingScreen('on');
	select_country(n,m);
});

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		reset_modal();
	}
});

$(document).on("click", "#btn_save_new_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        			$('.alert').css("display","none");
        			$('#inpt_msg_desc_country').parent('div').removeClass('has-error');
					$('#input_name_country').parent('div').removeClass('has-error');


					var u_id = document.getElementById('input_name_country').value;				
					var re = new RegExp("(^[a-zA-Z0-9\\-\\s]{5,50})$");
					if (re.test(u_id)) {
					} else {
						$('#input_name_country').parent('div').addClass('has-error');
						modal_notify('add_new_country_modal','<strong>Failed! </strong> Special characters are not allowed. country Name  must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
					    return;
					}

					var u_im = document.getElementById('inpt_msg_desc_country').value;				
					var rex = new RegExp("(^[a-zA-Z0-9\\-\\s]{1,150})$");
					if (rex.test(u_im)) {
					} else {
						$('#inpt_msg_desc_country').parent('div').addClass('has-error');
						modal_notify('add_new_country_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}
					var n = "maintenance/country/add_country";
					var m = [ document.getElementById('input_name_country').value,document.getElementById('inpt_msg_desc_country').value];
					//loading('btn_save_new_country','in_progress');
					add_country(m,n);
}
});

function add_country(data,url)
{
$.ajax({
	type:'POST',
	data:{data:  JSON.stringify(data)},
	url: url,
	success: function(result){
		var j = JSON.parse(result)
		//alert(result);
		if(j.data== true)
		{
			//$('#add_new_country_modal').modal('toggle');
			//$('#add_new_country_modal input:text').removeClass('has-error');
			//$('#inpt_msg_desc_country').removeClass('has-error')
			//loading('btn_save_new_country','in_progress');
			//notify('<strong>Success</strong> Country added successful.','success');	
			setTimeout(function(){
				$('#add_new_country_modal').modal('hide');
				
				//=== NOTIFICATION ===
				var span_message = 'Country has been successfully saved!';
				var type = 'success';
				notify(span_message, type);		

				var n = "maintenance/country/select_country";
				var m = document.getElementById('inpt_search_country').value;
				select_country(n,m);
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


function select_country(url,data)
{
$("#tbl_country").DataTable().destroy();	
		$.ajax({
			type:'POST',
			data:{data: data},
			url: url,
			success: function(result){
			//alert(result);
			var j = JSON.parse(result)
			create_table(j);
			$('')


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



$(document).on("click", "#btn_search_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		e.handled = true; // Basically setting value that the current event has occurred.
		var n = "maintenance/country/select_country";
		var m = document.getElementById('inpt_search_country').value;
		select_country(n,m);
	}
});
$("#inpt_search_country").on('keyup', function (e) {
    if (e.keyCode == 13) {
		var n = "maintenance/country/select_country";
		var m = document.getElementById('inpt_search_country').value;
		select_country(n,m);
    }
});
$(document).on('click',"input[type='radio'][name='rdodefault']",function()
{
	$('#btn_sel_save_country').data('mid',$(this).data('mid'));
})



function create_table(data)
{
$("#tbl_country").find("tr:gt(0)").remove();
var default_rdo = 0;
for(i=0;i<data.data.length;i++){
	if(data.data[i]['DEFAULT_FLAG'] == '1'){
		default_rdo = 1;
	}
	$('<tr><td>'+ (i+1) +'</td><td>'+data.data[i]['COUNTRY_NAME']+'</td><td>'+data.data[i]['DESCRIPTION']+'</td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>'+data.data[i]['DATE_UPLOADED']+'</td><td><span style="display:none;">' + default_rdo +'</span><input data-mid = "'+data.data[i]['COUNTRY_ID']+'" id = "rd'+i+'" type = "radio" name = "rdodefault"  data-toggle="modal" data-target="#edit_selected_country" onclick = "return false;"></td><td><button rel = "'+ data.data[i]['COUNTRY_ID'] + '/' + data.data[i]['COUNTRY_NAME'] + '/' + data.data[i]['DESCRIPTION']+ '/' + data.data[i]['STATUS'] +'" class = "btn btn-default btn_view_country" data-toggle="modal" data-target="#edit_country_new_modal"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" data-country ="' + data.data[i]['COUNTRY_ID'] + '" class = "btn btn-default c_del_country"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_country');
	if(data.data[i]['DEFAULT_FLAG'] == '1'){
		$("#rd"+i).prop('checked',true);
	}
	default_rdo = 0;
}
$("#tbl_country").DataTable({
dom:'<"top state"t<"clear">>rt<"bottom country"p<"clear">>',
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


$(document).on("click", ".btn_view_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        clear_on();
        var n = $(this).attr('rel').split('/');
        $('#inpt_edit_desc_country').attr('rel',n[0]);
        $('#inpt_edit_desc_country').val(n[2]);
        $('#input_edit_country').val(n[1]);
        $('#chk_sta').prop('checked',false);
        if(n[3] == 1){
        	$('#chk_sta').prop('checked',true);
        }
	}
});

$(document).on("click", ".c_del_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

      deactivateYesNo($(this).data('country'))
        }
});

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this State from the table? <button id = "confirm_country_yes" type="button" class="btn btn-success" data-country = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#confirm_country_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).data('country');
        loading('confirm_country_yes','in_progress');
         var m = "maintenance/country/del_country";
        del_country(n,m)
     	
        }
});



$(document).on("click", "#btn_save_edit_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.



        		$('.alert').css("display","none");
        		$('#inpt_edit_desc_country').parent('div').removeClass('has-error');
				$('#input_edit_country').parent('div').removeClass('has-error');


				var u_id = document.getElementById('input_edit_country').value;				
				var re = new RegExp("(^[a-zA-Z0-9\\-\\s]{5,50})$");					
				if (re.test(u_id)) {
					} else {
						$('#input_edit_country').parent('div').addClass('has-error');
						modal_notify('edit_country_new_modal','<strong>Failed! </strong> Special characters are not allowed. country Name  must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
					    return;
					}

				var u_im = document.getElementById('inpt_edit_desc_country').value;				
				var rex = new RegExp("(^[a-zA-Z0-9\\-\\s]{1,150})$");
				if (rex.test(u_im)) {
					} else {
						$('#inpt_edit_desc_country').parent('div').addClass('has-error');
						modal_notify('edit_country_new_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

		var l = 0;
		if($('#chk_sta').prop('checked') == true){

			l =1;

		}	

      
        var n = [$('#inpt_edit_desc_country').attr('rel'),$('#inpt_edit_desc_country').val(),$('#input_edit_country').val(),l];
   		var m = "maintenance/country/edit_country";

        edit_country(n,m)

        }
});

function edit_country(data,url)
{

	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
		
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//modal_notify('edit_country_new_modal','<strong>Success</strong>','success',false);	
				setTimeout(function(){
					$('#edit_country_new_modal').modal('hide');
					
					//=== NOTIFICATION ===
					var span_message = 'Country has been successfully saved!';
					var type = 'success';
					notify(span_message, type);	
					var n = "maintenance/country/select_country";
					var m = document.getElementById('inpt_search_country').value;
					select_country(n,m);
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

function del_country(data,url)
{

		$.ajax({
			type:'POST',
			data:{data:  JSON.stringify(data)},
			url: url,
			success: function(result){
			
			var j = JSON.parse(result)

			if(j.data== true)
			{
				loading('confirm_country_yes','');
				notify('<strong>Country removed successful.</strong>','success');	
				var n = "maintenance/country/select_country";
				var m = document.getElementById('inpt_search_country').value;
				select_country(n,m);

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







$(document).on("click", "#btn_sel_save_country", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
         var n = $(this).data('mid');
         var m = "maintenance/country/c_sel_country";
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
				$('#edit_selected_country').modal('toggle');
				let n = "maintenance/country/select_country";
				let m = document.getElementById('inpt_search_country').value;
				select_country(n,m);
				notify('Change default country successful.','success');				
				return;
				}
				$('#edit_selected_country').modal('toggle');
				notify('Error in connection','danger');
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

$('#add_new_country_modal').on('hidden.bs.modal', function (e) {
	reset_modal();
});