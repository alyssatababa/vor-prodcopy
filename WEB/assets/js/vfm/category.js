function clear_on()
{
	 $div_notifications.stop().fadeOut("slow", clean_div_notif);
	 $('#input_name_category').removeClass('has-error');
	 $('#inpt_msg_desc_category').removeClass('has-error'); 
}

function reset_modal(){
	$('#inpt_msg_desc_category').parent('div').removeClass('has-error');
	$('#input_name_category').parent('div').removeClass('has-error');
	$('#inpt_msg_desc_category').val('');
	$('#input_name_category').val('');

	$('#inpt_edit_desc_category').parent('div').removeClass('has-error');
	$('#input_edit_category').parent('div').removeClass('has-error');
	$('#inpt_edit_desc_category').val('');
	$('#input_edit_category').val('');

	$('.modal-body .alert').css("display","none");
}

$(document).ready(function()
{
	clear_on();
	var n = "maintenance/category/select_category";
	var m = '';
	loadingScreen('on');
	select_category(n,m);

});

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.     			
		reset_modal();
	}
});

$(document).on("click", "#btn_save_new_category", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred./
		$('.alert').css("display","none");
		$('#inpt_msg_desc_category').parent('div').removeClass('has-error');
		$('#input_name_category').parent('div').removeClass('has-error');

		var u_id = document.getElementById('input_name_category').value;				
		var re = new RegExp("(^[a-zA-Z0-9&'\\/\\\",\\-\\s]{5,50})$");
		if (re.test(u_id)) {
		} else {
			$('#input_name_category').parent('div').addClass('has-error');
			modal_notify('add_new_category_modal','<strong>Failed! </strong> Special characters are not allowed. category Name  must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
			return;
		}

		var u_im = document.getElementById('inpt_msg_desc_category').value;				
		var rex = new RegExp("(^[a-zA-Z0-9&'\\\",\\/\\-\\s]{1,150})$");
		if (rex.test(u_im)) {
		} else {
			$('#inpt_msg_desc_category').parent('div').addClass('has-error');
			modal_notify('add_new_category_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
			return;
		}

		var n = "maintenance/category/add_category";
		var m = [ document.getElementById('input_name_category').value,document.getElementById('inpt_msg_desc_category').value,$('#new_sel_btype option:selected').data('b_type')];
		add_category(m,n);
	}
});

function add_category(data,url)
{

	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){
		var j = JSON.parse(result)
		// if(j == "exist"){
		// 	modal_notify('add_new_category_modal','<strong>Failed! </strong> Category name already exist!','danger',false);	
		// 	return;
		// }
		if(j.data== true)
		{
			//modal_notify('add_new_category_modal','<strong>Success</strong>','success',false);
			setTimeout(function(){			
				$('#add_new_category_modal').modal('hide');
				
				//=== NOTIFICATION ===
				var span_message = 'Category has been successfully saved!';
				var type = 'success';
				notify(span_message, type);	
				var n = "maintenance/category/select_category";
				var m = '';
				select_category(n,m);
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


function select_category(url,data)
{
$("#tbl_category").DataTable().destroy();	
		$.ajax({
			type:'POST',
			data:{data: data},
			url: url,
			success: function(result){
			//alert(result);
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



$(document).on("click", "#btn_search_category", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        //clear_on();
		var n = "maintenance/category/select_category";
		var m = document.getElementById('inpt_search_category').value;
		select_category(n,m);

}
});

$("#inpt_search_category").on('keyup', function (e) {
    if (e.keyCode == 13) {
		var n = "maintenance/category/select_category";
		var m = document.getElementById('inpt_search_category').value;
		select_category(n,m);
    }
});

$(document).on('click','.ui-button',function()
{


$div_notifications.stop().fadeOut("slow", clean_div_notif);

});

function create_table(data)
{
$("#tbl_category").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){

	var sta = 'NO';

	if((data.data[i]['STATUS']) == 1)
	{
		sta = 'YES';
	}

	$('<tr><td>'+ (i+1) +'</td><td>'+data.data[i]['CATEGORY_NAME']+'</td><td>'+data.data[i]['DESCRIPTION']+'</td><td>' + '<span style="display:none;">' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>'+data.data[i]['DATE_UPLOADED']+'</td><td><button data-category-id="' + data.data[i]['CATEGORY_ID'] + '" data-category-name="' + escapeHtml(data.data[i]['CATEGORY_NAME']) +'" data-description="' + escapeHtml(data.data[i]['DESCRIPTION']) + '" data-status="' + data.data[i]['STATUS'] + '" data-business-type="' + data.data[i]['BUSINESS_TYPE'] + '" class = "btn btn-default btn_view_category" data-toggle="modal" data-target="#edit_category_new_modal"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button href = "" onclick = "return false;" data-category ="' + data.data[i]['CATEGORY_ID'] + '" class = "btn btn-default c_del_category"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_category');

}
$("#tbl_category").DataTable({
dom:'<"top state"t<"clear">>rt<"bottom category"p<"clear">>',
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

$(document).on("click", ".btn_view_category", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        //$div_notifications.stop().fadeOut("slow", clean_div_notif);
		clear_on();
        $('#inpt_edit_desc_category').attr('rel',$(this).data('category-id'));
        $('#inpt_edit_desc_category').val($(this).data('description'));
        $('#input_edit_category').val($(this).data('category-name'));
        $('#chk_sta').prop('checked',false);
        $('#edit_sel_btype').val($(this).data('business-type'));
        if($(this).data('status') == 1){
        	$('#chk_sta').prop('checked',true);
        }
    }
});


$(document).on("click", "#btn_save_edit_category", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        		$('.alert').css("display","none");
        		$('#inpt_edit_desc_category').parent('div').removeClass('has-error');
				$('#input_edit_category').parent('div').removeClass('has-error');


				var u_id = document.getElementById('input_edit_category').value;				
				var re = new RegExp("(^[a-zA-Z0-9&'\\/\\\",\\-\\s]{5,50})$");					
				if (re.test(u_id)) {
					} else {
						$('#input_edit_category').parent('div').addClass('has-error');
						modal_notify('edit_category_new_modal','<strong>Failed! </strong> Special characters are not allowed. category Name  must not be less than 5 characters and not greater than 50 charactes.','danger',false);	
					    return;
					}

				var u_im = document.getElementById('inpt_edit_desc_category').value;				
				var rex = new RegExp("(^[a-zA-Z0-9&'\\\",\\/\\-\\s]{1,150})$");
				if (rex.test(u_im)) {
					} else {
						$('#inpt_edit_desc_category').parent('div').addClass('has-error');
						modal_notify('edit_category_new_modal','<strong>Failed! </strong> Special characters are not allowed. Description must not be less than 1 character and not greater than 150 charactes.','danger',false);	
					    return;
					}

		var l = 0;
		if($('#chk_sta').prop('checked') == true){

			l =1;

		}	

      
        var n = [$('#inpt_edit_desc_category').attr('rel'),$('#inpt_edit_desc_category').val(),$('#input_edit_category').val(),l,$('#edit_sel_btype option:selected').data('b_type')];
   		var m = "maintenance/category/edit_category";
        edit_category(n,m)

        }
});



function edit_category(data,url)
{
	$.ajax({
		type:'POST',
		data:{data:  JSON.stringify(data)},
		url: url,
		success: function(result){	
			var j = JSON.parse(result)
			if(j.data== true)
			{
				//modal_notify('edit_category_new_modal','<strong>Success</strong>','success',false);
				setTimeout(function(){
					//=== NOTIFICATION ===
					$('#edit_category_new_modal').modal('hide');
					var span_message = 'Category has been successfully edited!';
					var type = 'success';
					notify(span_message, type);	
					var n = "maintenance/category/select_category";
					var m = document.getElementById('inpt_search_category').value;
					select_category(n,m);
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



$(document).on("click", ".c_del_category", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
       var n =  $(this).data('category')
        deactivateYesNo(n);

      


        }
});

$(document).on("click", "#confirm_category_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
       var n =  $(this).data('category')
       del_category(n,'maintenance/category/del_category')
        }
});

function del_category(data,url)
{

		$.ajax({
			type:'POST',
			data:{data:  JSON.stringify(data)},
			url: url,
			success: function(result){		
			var j = result
			if(j == 'true')
			{
				notify('<strong>Category removed successful.</strong>','success');	
				var n = "maintenance/category/select_category";
				var m = document.getElementById('inpt_search_category').value;
				select_category(n,m);

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
	var span_message = 'Do you want to delete this Category from the table? <button id = "confirm_category_yes" type="button" class="btn btn-success" data-category = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$('#add_new_category_modal').on('hidden.bs.modal', function (e) {
	reset_modal();
});