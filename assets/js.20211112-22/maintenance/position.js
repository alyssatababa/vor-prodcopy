
$('#spos_btn_search').click(function()
{	
	var n = $('#pos_sel_pos option:selected').attr('rel');
	var m = $('#txt_pos_search').val();
	loading('spos_btn_search','in_progress');
	get_post(n,m);	

});


function get_post(n,m)
{	
		$("#tbl_position").DataTable().destroy();
	var x = $('#bod').attr('data-base-url');
		$.ajax({
			type:'POST',
			data:{m: m,n: n},
			url: x +'index.php/maintenance/positions/search_position',
			cache:false,
			async:true,
			success: function(result,e){
				loading('spos_btn_search','');
				//	console.log(result);
					var das = JSON.parse(result);
					
				
		create_table(das);
	
		
			},
			error: function(xhr)
			{
					
			}	
			});						
}


$(document).ready(function(){

var n = $('#pos_sel_pos option:selected').attr('rel');
var m = $('#txt_pos_search').val();
get_post(n,m);

});

function create_table(data)
{
$("#tbl_position").find("tr:gt(0)").remove();	

for(i=0;i<data.data.length;i++){	
	$('<tr><td>' + data.data[i]['POSITION_ID'] + '</td><td>' + data.data[i]['POSITION_CODE'] + '</td><td>' + data.data[i]['POSITION_NAME'] + '</td><td><button class = "btn btn-default c_e_view" data-toggle="modal" data-target="#pos_edit_pos" rel = "'+ data.data[i]['POSITION_ID'] +'/' + data.data[i]['POSITION_CODE'] + '/' + data.data[i]['POSITION_NAME'] + '"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><button class = "btn btn-default c_pos_del" rel = "'+ data.data[i]['POSITION_ID'] 
		+'"><span class = "g_icon glyphicon glyphicon-trash"></span></button></td></tr>').appendTo('tbody');		
}

$("#tbl_position").DataTable({

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

$('#edit_save_pos').click(function()
{
	
	var m = $('#inpt_pos_name').val();
	var l = $('#inpt_pos_code').val();
	
	$div_notifications.stop().fadeOut("slow", clean_div_notif);
	if(check_empty('pos_add_pos') > 0)
	{
	modal_notify('pos_add_pos','<strong>Failed! </strong>Please Fill Required Fields.','danger');
	return;
	
	}
	
	var n = $('#add_sel_type option:selected').attr('rel');
	
	save_post(m,l,n);
});


function s_c(data)
{
	//alert(data);
	
}

function check_empty(data)
{	
	var iError = 0;	
	$('#'+data+' input').each(function()
	{
		if($(this).val().length == 0){			
			$('#'+ this.id).parent('div').addClass('has-error');	
			iError++;
		}	
	});	
	return iError;
}

function save_post(m,l,n)
{			
		var x = $('#bod').attr('data-base-url');
		loading('edit_save_pos','in_progress');
		$.ajax({
			type:'POST',
			data:{m: m,l: l,n: n},
			url: x +'index.php/maintenance/positions/save_position',
			success: function(result){
			$('#spos_btn_search').trigger('click');
			clear_add();
			notify('<strong>Success! </strong> Position Added Successfully.','success');
			$('#pos_add_pos').modal('toggle');	
			loading('edit_save_pos','');
			return;			
			},error: function(result)
			{
				modal_notify('pos_add_pos','<strong>Success! </strong> Position Added asdfasdf.','success');				
			}
			}).fail(function(){
				return;
		});													
}


$(document).on('click','.c_e_view', function() {
	$div_notifications.stop().fadeOut("slow", clean_div_notif);
	var n = $(this).attr('rel').split('/');	
	$('#edit_pos_id').val(n[0]);
	$('#edit_pos_name').val(n[2])
	$('#edit_pos_code').val(n[1])
    
});

$('#save_pos').click(function(){
	
	var m = $('#edit_pos_id').val();
	var l = $('#edit_pos_name').val();
	var n = $('#edit_pos_code').val();
	var o = $('#edit_sel_type option:selected').attr('rel');
	
	if(check_empty('pos_edit_pos') > 0)
	{
	modal_notify('pos_edit_pos','<strong>Failed! </strong>Please Fill Required Fields.','danger');
	return;
	
	}
	
	
	s_edit(m,l,n,o);


	
});

function s_edit(m,l,n,o)
{	
		var x = $('#bod').attr('data-base-url');		
		$.ajax({
			type:'POST',
			data:{m: m,l: l,n: n,o: o},
			url: x +'index.php/maintenance/positions/edit_pos',
			success: function(result){
			$('#spos_btn_search').trigger('click');
			$('#pos_edit_pos').modal('toggle');
			notify('<strong>Success! </strong> Change Saved Successfully.','success');	
			return;			
			},error: function(result)
			{			
			}
			}).fail(function(){
				modal_notify('pos_edit_pos','<strong>Failed! </strong> Connection Error.','danger');	
				return;
		});		
}

$('#btn_clear').click(function()
{
	$('#txt_pos_search').val('');	
	
});



$(document).on("click", ".c_pos_del", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        var n = $(this).attr('rel');
       deactivateYesNo(n)
}
});

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this position? <button id = "conf_yes" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#conf_yes", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        var n = $(this).attr('rel');

        $.ajax({
			type:'POST',
			data:{n: n},
			url: 'maintenance/positions/del_position',
			success: function(result){				
			var m = JSON.parse(result);
			//alert(result);

			
			if(m.data == true){	

			notify('<strong>Success! </strong> Position Update Successful.','success');
			
				var n = $('#pos_sel_pos option:selected').attr('rel');
				var m = $('#txt_pos_search').val();
				get_post(n,m);	
				

			}
			}	
			}).fail(function(){
			//	alert(result);
				notify('<strong>Failed! </strong> Position Update Failed.','danger',true);
			});	

}
});

function clear_add()
{

	$div_notifications.stop().fadeOut("slow", clean_div_notif);
	$('#inpt_pos_name').val('');
	$('#inpt_pos_code').val('');
	$('.alert').css("display", "none");
	rmv_input_error('pos_add_pos');	
}


function rmv_input_error(name){
		$div_notifications.stop().fadeOut("slow", clean_div_notif);
		$('#'+name+' input').each(function(){
		var id = ($(this).attr('id'))
		$('#'+id).closest('div').removeClass('has-error');
	})
}

$(document).on('click','#btn_add_position',function(){

$div_notifications.stop().fadeOut("slow", clean_div_notif);
});


