
function get_email_template()
{

		$.ajax({
				type:'POST',
				data:{},
				url:'maintenance/edit_email/get_all_template',
			success: function(result){				
				let n = JSON.parse(result);
				create_table(n);

			}	
			}).fail(function(){
				console.log(result);
		});	

}

$(document).ready(function(){

	get_email_template();

});

function create_table(_obj)
{
	if($.fn.dataTable.isDataTable("#table_email")){ //Checking for initialization
	$("#table_email").DataTable().destroy();
	}

	$("#table_email").find("tr:gt(0)").remove();	

	Object.keys(_obj).forEach(function(key){

		if(_obj[key]['DESCRIPTION'] == null){
			_obj[key]['DESCRIPTION'] = '';
		}
		
		if(_obj[key]['TEMPLATE_HEADER'] == null){
			_obj[key]['TEMPLATE_HEADER'] = '';
		}

		let _cell = '';
		_cell = _cell + '<tr>'
		_cell = _cell + '<td id="td_desc_'+ _obj[key]['TEMPLATE_ID']+'">'+ _obj[key]['DESCRIPTION'] +'</td>'
		_cell = _cell + '<td id="td_template_header_'+ _obj[key]['TEMPLATE_ID']+'">'+ _obj[key]['TEMPLATE_HEADER'] +'</td>'
		_cell = _cell + '<td id="td_cont_'+ _obj[key]['TEMPLATE_ID'] +'">'+ _obj[key]['CONTENT'] +'</td>'
		//_cell = _cell + '<td><button value="'+ _obj[key]['TEMPLATE_ID']+'" type="button" data-toggle="modal" data-target="#edit_email" class="btn btn-default c_edit"><span class= "glyphicon glyphicon-sunglasses g_icon"></span></button><button value="'+ _obj[key]['TEMPLATE_ID']+'" class="btn btn-default deact"><span class= "glyphicon glyphicon-trash g_icon" onclick = "return false"></span></button></td>'
		_cell = _cell + '<td><button value="'+ _obj[key]['TEMPLATE_ID']+'" type="button" data-toggle="modal" data-target="#edit_email" class="btn btn-default c_edit"><span class= "glyphicon glyphicon-sunglasses g_icon"></span></button></td>'
		_cell = _cell + '</tr>'
		$(_cell).appendTo('#table_email tbody');

	});

	$("#table_email").DataTable({
	dom:'<"top email"t<"clear">>rt<"bottom email"p<"clear">>',
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

$(document).on('click','.c_edit',function(){
	let _n = this.value;

	$('#btn_edit_save').val(_n);
	$('#inpt_edit_desc').val($('#td_desc_'+_n).text());
	$('#cmt_edit_cont').val($('#td_cont_'+_n).text());
	$('#inpt_edit_template_header').val($('#td_template_header_'+_n).text());
});



$(document).on('click','#btn_edit_save',function(e){
	e.preventDefault();
	loading('btn_edit_save','in_progress');
	save_edit(this.value);
	e.stopImmediatePropagation();
});

function close_edit()
{
	//$('.alert').hide();
	$('#btn_edit_save').val('');
	$('#inpt_edit_desc').val('');
	$('#cmt_edit_cont').val('');
	$('#inpt_edit_template_header').val('');
}

function close_new()
{
	//$('.alert').hide();
	$('#inpt_new_desc').val('');
	$('#cmt_new_cont').val('');
	$('#inpt_new_template_header').val('');
}


function save_edit(sid)
{
	let _desc = $('#inpt_edit_desc').val();
	let _cont = $('#cmt_edit_cont').val();
	let _template_header = $('#inpt_edit_template_header').val();


	let _err = 0;
	let _merr ='';

	if(_desc.length == 0){
		_err = _err+1;
		_merr = _merr + '<strong>Failed!</strong> E-Mail Description is required! <br>';
	}

	if(_cont.length == 0){
		_err = _err+1;
		_merr = _merr + '<strong>Failed!</strong> E-Mail Content is required!';
	}

	if(_err >0){
		modal_notify('edit_email',_merr,'danger');
		loading('btn_edit_save','');
		return;
	}



	$.ajax({
			type:'POST',
			data:{sid: sid,_desc: _desc,_cont: _cont, _template_header: _template_header},
			url:'maintenance/edit_email/save_edit_template',
		success: function(result){				
			get_email_template();
			$('#edit_email').modal('toggle');
			notify('<strong>Success! </strong>E-Mail Template updated successful!','success');
			loading('btn_edit_save','');
		}	
		}).fail(function(){
			console.log(result);
	});	
}

function save_new()
{
	let _desc = $('#inpt_new_desc').val();
	let _cont = $('#cmt_new_cont').val();
	let _template_header = $('#inpt_new_template_header').val();
	
	$.ajax({
			type:'POST',
			data:{_desc: _desc,_cont: _cont, _template_header: _template_header},
			url:'maintenance/edit_email/save_new_template',
		success: function(result){	

		console.log(result);			
			get_email_template();
			$('#add_email').modal('toggle');
			close_new();
			notify('<strong>Success! </strong>E-Mail Template added successful!','success');
			//$('.alert').show();
			loading('btn_new_save','');
		}	
		}).fail(function(){
			console.log(result);
	});	
}

function check_save()
{
	loading('btn_new_save','in_progress');
	//$('.alert').hide();

	let _err = 0;
	let _merr ='';

	if($('#inpt_new_desc').val().length == 0){
		_err = _err+1;
		_merr = _merr + '<strong>Failed!</strong> E-Mail Description is required! <br>';
	}

	if($('#cmt_new_cont').val().length == 0){
		_err = _err+1;
		_merr = _merr + '<strong>Failed!</strong> E-Mail Content is required!';
	}

	if(_err >0){
		modal_notify('add_email',_merr,'danger');
		loading('btn_new_save','');
		return;
	}
	save_new();
}

function deactivate_template(sid)
{
		$.ajax({
			type:'POST',
			data:{sid: sid},
			url:'maintenance/edit_email/save_deac_template',
		success: function(result){				
			get_email_template();
			notify('<strong>Success! </strong>E-Mail Template deactivated successful!','success');
		}	
		}).fail(function(){
			console.log(result);
	});	
}

function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this E-Mail Template? <button id = "conf_yes_email" type="button" class="btn btn-success" value="'+n+'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on('click','.deact',function(){
	deactivateYesNo(this.value);
});

$(document).on('click','#conf_yes_email',function(){

	deactivate_template(this.value);

})


