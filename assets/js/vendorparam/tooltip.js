var iCount=0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	var base_url = $('#b_url').attr('data-base-url');
	getTooltip("all");
});

$(document).on("click", "#btn_save_tooltip",function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		//alert("check");
		var tooltip_name = $("#edit_tooltip_name").val();
		var description = $("#edit_description").val();

		//alert(tooltip_type);
		if(isFillUpError() == 0){
			var tooltip_id = $("#edit_tooltip_id").val();
			saveTooltip(tooltip_id, tooltip_name, description)
		}
	}
});

$("#btn_search_tooltip").click(function()
{	
	var tooltip = $("#search_tooltip").val();

	if (tooltip==""){
		getTooltip("all");
	}else{
		getTooltip(tooltip);
	}
		
});

$('#modal_edit_tooltip').on('show.bs.modal', function () {
	$('#modal_edit_tooltip').find('.field-required').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	$('#edit_bus_division').val('1');
	//var x = document.getElementById('error_add_tooltip');
	//x.style.display = 'none';
})

function getTooltip(tooltip)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(tooltip == "all"){
		base_url = base_url + "get_all_tooltip";
	}else{
		base_url = base_url + "get_tooltip";
	}
	
	$.ajax({
		type:'POST',
		data:{tooltip: tooltip},
		url: base_url,
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			if (json_result.status != false){
				$("#tbl_view").DataTable().destroy();
				createDataTable(json_result);
				$("#tbl_view").DataTable({
					dom:'<"top tbl_temp"t<"clear">>rt<"bottom tbl_temp"p<"clear">>',
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
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="tooltip_name'+y+'">'+obj_result[x].SCREEN_NAME+'</td><td id="description'+y+'" >'+obj_result[x].TOOLTIP+'</td><td><button class = "btn btn-default btn_tooltip_edit" rel = "'+obj_result[x].TID+'|'+obj_result[x].SCREEN_NAME+'|'+obj_result[x].TOOLTIP+'|'+obj_result[x].ELEMENT_LABEL+'" href = "" onclick = "return false;"><span class= "glyphicon glyphicon-edit g_icon" onclick = "return false"></span></button></td></tr>').appendTo('#tbl_body');
	}	
}

$(document).on("click", ".btn_tooltip_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var tooltip = ($(this).attr('rel')).split('|');

		$('#modal_edit_tooltip').modal('show');
		$('#modal_edit_tooltip .edit_tooltip').show();
		$('#modal_edit_tooltip .add_tooltip').hide();


		$('#edit_tooltip_type').val("2");
    	$('#edit_tooltip_id').val(tooltip[0]);

		$('#edit_tooltip_name').val(tooltip[1]);
		$('#edit_description').val(tooltip[2]);
    }
});

function isFillUpError()
{
	//alert("1");
	var iError = 0;
	
		$('#modal_edit_tooltip').find('.field-required').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			modal_notify('modal_edit_tooltip','<strong>Failed! </strong> Please fill up required fields.','danger');
		}

	return iError;
}


function saveTooltip(tooltip_id, tooltip_name, description)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{tooltip_id: tooltip_id, tooltip_name: tooltip_name, description: description},
		url: base_url + "save_tooltip",
		success: function(result){	
			//alert(result);
			//$('#modal_edit_tooltip').modal('hide');
			
			// var span_message = 'Record has been successfully saved!';
			// var type = 'success';
			// notify(span_message, type);	
			var json_result = $.parseJSON(result);		
			$('#modal_edit_tooltip').modal('hide');
			
			if (json_result.status != false){
				//modal_notify('modal_edit_tooltip','<strong>Success! </strong> Record has been successfully saved.','success');
				var span_message = '<strong>Success! </strong> Record has been successfully saved.';
				var type = 'success';
				notify(span_message, type);	
			}else{
				//modal_notify('modal_edit_tooltip','<strong>Failed! </strong> Unable to save record.','danger');
				var span_message = '<strong>Failed! </strong> Unable to save record.';
				var type = 'danger';
				notify(span_message, type);	
			}
			
			getTooltip("all");
		}		
	}).fail(function(){
		
	});		
}

function editRow(y, tooltip_id){
	var sel_tooltip_name = document.getElementById("tooltip_name"+y).innerHTML;
	var sel_description = document.getElementById("description"+y).innerHTML;

	//alert(tooltip_id);
	$('#tooltip_id').val(tooltip_id);
	$('#edit_tooltip_name').val(sel_tooltip_name);
	$('#edit_description').val(sel_description);

	$('#modal_edit_tooltip').modal('show')
}
