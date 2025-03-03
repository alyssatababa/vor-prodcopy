var _selRmv = [];


$(document).on("click", "#btn_save_message", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


        let _error = [];
        let _eMsg = '';



			$('#inpt_temp_name').parent('div').removeClass('has-error');
        	$('#cmt_tmp').parent('div').removeClass('has-error');
        	$('.alert').css("display","none");
        	

        let u_id = document.getElementById('inpt_temp_name').value;	
        let u_im = document.getElementById('cmt_tmp').value;


        			if(u_id.length != 0){

/*        				var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{0,5000})$");
							if (re.test(u_id)){
							} 
							else {
								$('#inpt_temp_name').parent('div').addClass('has-error');
								_error.push('name_sc');
								}*/

        			}
        			else if(u_id.length > 50){
        					_error.push('name_fv');
        					$('#inpt_temp_name').parent('div').addClass('has-error');								
        			}else{

        				_error.push('name_em');
        				$('#inpt_temp_name').parent('div').addClass('has-error');
        			}					

					if(u_im.length == 0){
						_error.push('msg_em');
						$('#cmt_tmp').parent('div').addClass('has-error');
					}else if(u_im.length > 3000){
						_error.push('msg_3');
						$('#cmt_tmp').parent('div').addClass('has-error');
					}	
	
			if(_error.length != 0){
				for(i=0; i<=_error.length;i++){

					let _tmpError = '';

					switch(_error[i])
					{
						case 'name_sc': _tmpError = '<strong>Error  :  </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>';
							break;
						case 'name_fv': _tmpError = '<strong>Error  :  </strong> Maximum length for Name is <Strong>50!</strong><br>';
							break;
						case 'name_em': _tmpError = '<strong>Error  :  </strong>Template Name is <strong>REQUIRED!</strong> <br>';
							break;
						case 'msg_em':  _tmpError = '<strong>Error  :  </strong>Message is <strong>REQUIRED!</strong> <br>';
							break;
						case 'msg_3':  _tmpError = '<strong>Error  :  </strong>Message is <strong>TOO LONG!</strong> <br>';
							break;
						default:
							break;
					}
					_eMsg = _eMsg + _tmpError;
				}				
				modal_notify('add_ven_temp',_eMsg,'danger',true);	
				return;
			}



				var n = [$('#cmt_tmp').val(),$('#inpt_temp_name').val()];
				var m = "maintenance/vendorinvite/save_vendor_template";
				loading('btn_save_message','in_progress');
				ajax_rsqst(n,m);

	
}
});

$(document).on("change keydown paste input", "#cmt_tmp", function() {
   
	count_left();

});
$(document).on("change keydown paste input", "#cmt_edit", function() {
  
	count_left_1();

});

function count_left()
{

	 $('.h_car').fadeIn('slow');
	let _tlength = 300;
    _tlength = _tlength - document.getElementById('cmt_tmp').value.length;  
    if(_tlength < 0 ){
    	$('#txt_left').text('0');
    	return;
    }
    $('#txt_left').text(_tlength);
}
function count_left_1()
{
	 $('.h_car').fadeIn('slow');
	let _tlength = 300;
    _tlength = _tlength - document.getElementById('cmt_edit').value.length; 
    if(_tlength < 0 ){
    	$('#txt_left_1').text('0');
    	return;
    } 
    $('#txt_left_1').text(_tlength);
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


function ajax_rsqst(arr,url)
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
				close_modal('add_ven_temp');
				notify('<strong>Success! </strong> Template creation success.','success');
				var m = "maintenance/vendorinvite/get_all";
				ajax_rqst_get(m);
				return;
				}
				if(n.data == false)
				{
				$('#inpt_temp_name').parent('div').addClass('has-error');
				modal_notify('add_ven_temp','<strong>Error : </strong>Template Name already <strong>EXIST</strong>!','danger',true);	
				loading('btn_save_message','');
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

function close_modal(sname){
var n = sname;
$('#'+sname).modal('toggle');
document.getElementById("inpt_temp_name").value = "";
document.getElementById("cmt_tmp").value = "";
loading('btn_save_message','');

}



function ajax_rqst_get(url)
{		
	$("#tbl_vendor_invite").DataTable().destroy();
	setTimeout(function(){
		$.ajax({
			type:'POST',
			data:{},
			url: url,
			success: function(result){
				var das = JSON.parse(result)
				create_table_reg(das)			
			
			return;	

			},error: function(result){
				//alert(result + 'e');	
				return;		
			}
		}).fail(function(result){

	//	alert(result + 'f');
		return;
		});
	}, 700);
}
function create_table_reg(data)
{

if($.fn.dataTable.isDataTable("#tbl_vendor_invite")){ //Checking for initialization
	$("#tbl_vendor_invite").DataTable().destroy();
}

$("#tbl_vendor_invite").find("tr:gt(0)").remove();	
for(i=0;i<data.data.length;i++){	
	if(data.data[i]['USER_MIDDLE_NAME'] == null){
		data.data[i]['USER_MIDDLE_NAME'] = '';
		}		
	if(data.data[i]['USER_LAST_NAME'] == null){
		data.data[i]['USER_LAST_NAME'] = '';
		}
	if(data.data[i]['USER_FIRST_NAME'] == null){
		data.data[i]['USER_FIRST_NAME'] = '';
		}
		$('<tr><td><input type = "checkbox" data-val = "'+data.data[i]['VEN_INV_ID']+'" class = "chk_sel_ven" onClick = "asd(this)"></td><td>' + toTitleCase(data.data[i]['VEN_INV_TITLE']) +'</td><td class="class_s">' + data.data[i]['VEN_INV_MESSAGE'] +'</td><td>' + toTitleCase(data.data[i]['USER_FIRST_NAME']) + ' '  + toTitleCase(data.data[i]['USER_MIDDLE_NAME']) + ' ' + toTitleCase(data.data[i]['USER_LAST_NAME']) +'</td><td>' + '<span style="display:none;"> ' + data.data[i]['DATE_SORTING_FORMAT'] +'</span>' + data.data[i]['VEN_INV_DATE_CREATED'] +'</td><td><button class = "btn btn-default c_edit" rel = "'+ data.data[i]['VEN_INV_TITLE'] + '/' + data.data[i]['VEN_INV_MESSAGE'] + '/' + data.data[i]['VEN_INV_ID'] + '" href = "" onclick = "return false;" data-toggle="modal" data-target="#edit_ven_temp"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button><span>  </span><button href = "" onclick = "return false;" rel ="' + data.data[i]['VEN_INV_ID'] + '" class = "btn btn-default c_del"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td>').appendTo('#tbl_vendor_invite');	
}
$("#tbl_vendor_invite").DataTable({
	dom:'<"top vendor_temp"t<"clear">>rt<"bottom vendor_temp"p<"clear">>',
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

$(document).ready(function()
{

var m = "maintenance/vendorinvite/get_all";
ajax_rqst_get(m);
	

})


$(document).on("click", ".c_edit", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        var n = ($(this).attr('rel')).split('/');

        $('#cmt_edit').val(n[1]);
         $('#cmt_edit').attr('rel',n[2]);
        $('#inpt_edit_name').val(n[0]);

        count_left_1();
   		
    }
});

$(document).on("click", "#btn_edit_save", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

			$('#inpt_edit_name').parent('div').removeClass('has-error');
        	$('#cmt_edit').parent('div').removeClass('has-error');
        	$('.alert').css("display","none");
    	 	let _error = [];
   		 	let _eMsg = '';

        	let u_id = document.getElementById('inpt_edit_name').value;
        	let u_im = document.getElementById('cmt_edit').value;


        		if(u_id.length != 0){

/*        				var re = new RegExp("(^[a-zA-Z0-9\\-\\s()]{0,5000})$");
							if (re.test(u_id)){
							} 
							else {
								$('#inpt_edit_name').parent('div').addClass('has-error');
								_error.push('name_sc');
								}*/

        			}
        			else if(u_id.length > 50){
        					_error.push('name_fv');
        					$('#inpt_edit_name').parent('div').addClass('has-error');								
        			}else{

        				_error.push('name_em');
        				$('#inpt_edit_name').parent('div').addClass('has-error');
        			}					

					if(u_im.length == 0){
						_error.push('msg_em');
						$('#cmt_edit').parent('div').addClass('has-error');
					}else if(u_im.length > 3000){
						_error.push('msg_3');
						$('#cmt_edit').parent('div').addClass('has-error');
					}	
	
			if(_error.length != 0){
				for(i=0; i<=_error.length;i++){

					let _tmpError = '';

					switch(_error[i])
					{
						case 'name_sc': _tmpError = '<strong>Error  :  </strong> Special Character is not <Strong>ALLOWED</strong> in Template Name! <br>';
							break;
						case 'name_fv': _tmpError = '<strong>Error  :  </strong> Maximum length for Name is <Strong>50!</strong><br>';
							break;
						case 'name_em': _tmpError = '<strong>Error  :  </strong>Template Name is <strong>REQUIRED!</strong> <br>';
							break;
						case 'msg_em':  _tmpError = '<strong>Error  :  </strong>Message is <strong>REQUIRED!</strong> <br>';
							break;
						case 'msg_3':  _tmpError = '<strong>Error  :  </strong>Message is <strong>TOO LONG!</strong> <br>';
							break;
						default:
							break;
					}
					_eMsg = _eMsg + _tmpError;
				}				
				modal_notify('edit_ven_temp',_eMsg,'danger',true);	
				return;
			}
        	


        
        var m = [$('#cmt_edit').attr('rel'),$('#cmt_edit').val(),$('#inpt_edit_name').val()]
        var n = "maintenance/vendorinvite/edit_vendor_template";
        loading('btn_edit_save','in_progress');
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
				console.log(n);
				if(n.data == true)
				{
					$('#edit_ven_temp').modal('toggle');
					loading('btn_edit_save','');
					notify('<strong>Success! </strong> Update Template successful.','success');
					var m = "maintenance/vendorinvite/get_all";
					ajax_rqst_get(m);
					return;
				}

				if(n.data == false)
				{
					$('#inpt_edit_name').parent('div').addClass('has-error');
					modal_notify('edit_ven_temp','<strong>Error : </strong>Template Name already <strong>EXIST</strong>!','danger',true);	
					loading('btn_edit_save','');
					return;
				}

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


$(document).on("click", ".c_del", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	$div_notifications.stop().fadeOut("slow", clean_div_notif);
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
				var m = "maintenance/vendorinvite/get_all";
				notify("Template Deleted Successful","success")

				ajax_rqst_get(m);
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


function deactivateYesNo(n)
{
	var span_message = 'Do you want to delete this template? <button id = "conf_yes_vt" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on("click", "#conf_yes_vt", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        	var m = "maintenance/vendorinvite/del_vendor_template"
        	var n = $(this).attr('rel');
        	del_edit(n,m);
}
});

$(document).on("click", "#conf_no", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

 $div_notifications.stop().fadeOut("slow", clean_div_notif);
        	
}
});

$(document).on("click", ".btn_close", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        			 $div_notifications.stop().fadeOut("slow", clean_div_notif);

        			$('#inpt_temp_name').parent('div').removeClass('has-error');
					$('#cmt_tmp').parent('div').removeClass('has-error');
					$('#inpt_temp_name').val('');
					$('#cmt_tmp').val('');


					$('#inpt_edit_name').parent('div').removeClass('has-error');
					$('#cmt_edit').parent('div').removeClass('has-error');
					$('#inpt_edit_name').val('');
					$('#cmt_edit').val('');


					$('.alert').css("display","none");
}
});


$(document).on("click", "#btn_sel_ven_template", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
         $div_notifications.stop().fadeOut("slow", clean_div_notif);
        if(_selRmv.length == 0 ){
        	notify('No Selected Template','danger');
        	return;
        }
   			deact_multi('del_multi',)
        }
    
});



function asd(x)
{	
 $div_notifications.stop().fadeOut("slow", clean_div_notif);
art = x.getAttribute('data-val');
var n = (art);

if(x.checked){
	_selRmv.push(n);
	return;
}
	
	for(i=0;i<_selRmv.length;i++){
		if(n == _selRmv[i]){
			_selRmv.splice(i,1);
			break;
		}
	}


	

return;

}


function del_mul(arr,url)
{			

		$.ajax({
			type:'POST',
			data:{data:  JSON.stringify(arr)},
			url: url,
			success: function(result){	
			//console.log(result);		
			_selRmv = [];
				if(result == 'true')
				{
				var m = "maintenance/vendorinvite/get_all";
				notify('Template Deleted Successful','success')
				ajax_rqst_get(m);
				return;
				}

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

function deact_multi(_bid)
{
	var span_message = 'Delete selected templates? <button id = "'+_bid+'" type="button" class="btn btn-success">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}


$(document).on("click", "#del_multi", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        var m = "maintenance/vendorinvite/del_vendor_template_multi"
        
   			del_mul(_selRmv,m);


    }
});



$(document).on("click", "#btn_add_top_ven", function () {
   
        $div_notifications.stop().fadeOut("slow", clean_div_notif);  
        count_left();

});

function close_al()
{
		$('.alert').fadeOut("slow");
}