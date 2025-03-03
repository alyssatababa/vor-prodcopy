//create user

var sn = [];
var by = [];
var po1 = [];
var po2 = [];
var tcnt = 0;
var vcnt = 0;
var venCnt = 0;
var regCnt =0;
var _sort = 'DESC';
var s_type = 'USER_FIRST_NAME';


function trig()
{
	clear_alert();
	$('#slt_user_type_new').trigger('change');
}


$(document).on('change','#slt_user_type_new',function(){
	let data = $(this).find('option:selected').data('type');
	let tarray = [];
	if(data == 1){
		tarray = po2;
	}else{
	tarray = po1;
	}

	add_item_position(tarray,'slt_user_position_new');


});


$(document).on("click", "#btn_save_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        loading('btn_save_new','in_progress');
        $div_notifications.stop().fadeOut("slow", clean_div_notif);
        save_new_user();
    }
	e.stopImmediatePropagation();
});


$(document).on('change','#slt_user_position_new',function(){

	$('.a_senmer').hide();
	$('.a_buyer').hide();
	$('.category_css').hide();	

	$("#category_new").html(""); //reset
	$("#sel_category_new").html(""); //reset
	let n = ($(this).find('option:selected').data('pos'));
	if((n == 11) || (n == 2) || (n == 7)){
		if((n==2) || (n == 11)){
			$('.a_senmer').hide();
			$('.a_buyer').hide();	
			$('.a_buyer').show();	
			$('.category_css').hide();	
			$('.category_css').show();		
			fill_senmer(sn,1);
			$("#category_new").attr("multiple", "true");
			$("#sel_category_new").attr("multiple", "true");
		}
		if(n==7){
			$('.a_buyer').hide();
			$('.a_senmer').hide();
			$('.a_senmer').show();
			$('.category_css').hide();	
			$('.category_css').show();	
			fill_buyer(by,1);
			$("#category_new").removeAttr("multiple");
			$("#sel_category_new").removeAttr("multiple");
		}
	}


});


$(document).on("click", "#btn_search_category_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        let search = $('#search_cat_user').val();
        del_option('category_new');
        get_category(search,'category_new');
    }
});
$(document).on("click", "#btn_add_cat_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


      let cat =  $('#category_new').find('option:selected');//.data('cat');
      //let name =  $('#category_new').find('option:selected').val();

      if(cat == undefined){
      	return;
      }
      add_option_select('category_new','sel_category_new',cat, '#sel_category_new');


    }
});
$(document).on("click", "#btn_rmv_cat_new", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.


      let cat =  $('#sel_category_new').find('option:selected');//.data('cat');
      //let name =  $('#sel_category_new').find('option:selected').val();

      if(cat == undefined){
      	return;
      }
      add_option_select('sel_category_new','category_new',cat,'#category_new');


    }
});

function add_option_select(from,to,cat, btn_type = ''){
	$('#' + from).find('option:selected').each(function(e){
		console.log($(this).val());
		console.log($(this).val());
		let m = 0;
		var name = $(this).val();
		var cat_id = $(this).data('cat');
		$('#'+from).find('option:selected').remove();

		$('#'+to+ ' option').each(function(){
			let n = $(this).val();
			if(n == name){
				m = 1;
			}
		})

		if(m == 0){
		$('<option data-cat = "'+cat_id+'">'+name+'</option>').appendTo('#'+to);
		}

		if(btn_type){
			var sel = $(btn_type);
			var selected = sel.val(); // cache selected value, before reordering
			var opts_list = sel.find('option');
			opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
			sel.html('').append(opts_list);
		}//alert('test');
	});
}


// function get_option(data,name,type,rid)
// {
// 		$.ajax({
// 			type:'POST',
// 			data:{data: data},
// 			url:'maintenance/users/get_position',
// 			success: function(result){				
// 				add_item_position(result,name);
// 				if((name == 'slt_user_position_edit')&&(type == 2)||(type == 7)){
// 					$('#slt_user_position_edit').val(type);
// 					$('#slt_user_position_edit').trigger('change');
// 					$('#buyer_head_e').val(rid[0]);
// 					$('#g_head_e').val(rid[1]);
// 					$('#fas_head_e').val(rid[2]);
// 					$('#vrd_head_e').val(rid[3]);
// 					$('#vrd_staff_e').val(rid[4]);
					
// 				}
// 			}	
// 			}).fail(function(){
// 				});	
	
// }

function get_pos()
{
	data = 2;

		$.ajax({
			type:'POST',
			data:{data: data},
			url:'maintenance/users/get_position',
			success: function(result){				
				//console.log(result);
				po1 = result;
				//console.log(po1);
			}	
			}).fail(function(){
				console.log(result);
				});	
	
}

function get_pos2()
{
	data = 1;

		$.ajax({
			type:'POST',
			data:{data: data},
			url:'maintenance/users/get_position',
			success: function(result){				
			
				po2 = result;
			//console.log(po2);
			}	
			}).fail(function(){
		
				});	
	
}


function add_item_position(result,name)
{	

	var x = JSON.parse(result);	
	let dm = {ds : x}
	//console.log(dm);

		if(name == 'slt_user_position_new'){

			let tmpl = document.getElementById('new_temp').innerHTML;
			let htmls = Mustache.render(tmpl,dm);
			let table = document.getElementById(name);
			table.innerHTML = htmls;

		}

		if(name == 'slt_user_position_edit'){
			let tmpl = document.getElementById('new_temp').innerHTML;
			let htmls = Mustache.render(tmpl,dm);
			let table = document.getElementById(name);
			table.innerHTML = htmls;
		}
		$('#'+name).trigger('change');
}
$(document).ready(function()
{

get_pos();
get_senmer();
get_buyer();
get_pos();
get_pos2();

$('#btn_search_user').trigger('click');
});


// function get_approver(data,type,test,rid){

// 		$.ajax({
// 			type:'POST',
// 			data:{data: data},
// 			url:'maintenance/users/get_approver',
// 			success: function(result){				
// 				if(data == 2){
// 					del_option('category_new');
// 					del_option('sel_category_new');
// 					fill_senmer(result,type);

// 				}else{
// 					del_option('category_new');
// 					del_option('sel_category_new');
// 					fill_buyer(result,type);	
// 					}
// 				}
// 			}	
// 			}).fail(function(){
// 				alert(result)
// 			});	
// }

function get_senmer()
{
		$.ajax({
			type:'POST',
			data:{data: 2},
			url:'maintenance/users/get_approver',
			success: function(result){				
				sn = result;
				//console.log(sn);
			}	
			}).fail(function(){
				alert(result)
			});	
}
function get_buyer()
{
		$.ajax({
			type:'POST',
			data:{data: 7},
			url:'maintenance/users/get_approver',
			success: function(result){				
				by = result;
			}	
			}).fail(function(){
				alert(result)
			});	
}

function fill_vrd_staff(data)
{
	data = JSON.parse(data);
	let _obj =[];
	Object.keys(data).forEach(function(key){

		if(data[key]['POSITION_ID'] == '4'){
			_obj.push({vl:data[key]['USER_ID'],fn:data[key]['USER_FIRST_NAME'],mn:data[key]['USER_MIDDLE_NAME'],ln:data[key]['USER_LAST_NAME']});
		}
	});
	return _obj;
}

function fill_vrd_head(data)
{

	data = JSON.parse(data);
	let _obj =[];
	Object.keys(data).forEach(function(key){

		if(data[key]['POSITION_ID'] == '5'){
			_obj.push({vl:data[key]['USER_ID'],fn:data[key]['USER_FIRST_NAME'],mn:data[key]['USER_MIDDLE_NAME'],ln:data[key]['USER_LAST_NAME']});
		}
	});
	//console.log(_obj);
	return _obj;


}


$(document).on("click", "#add_vrd", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
	add_vrdstaff();
    }
});


$(document).on("click", "#add_vrdhead", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
	add_vrdhead();
    }
});



$(document).on("click", "#add_vrdhead_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
	add_vrdhead_e();
    }
});

$(document).on('click','#add_vrd_e',function(){

	add_vrdstaff_e();
});





function fill_senmer(data,type){
	data = JSON.parse(data);
	var name = [];
	 name[0] = 'buyer_head';
	 name[1] = 'vrd_staff';
	 name[2] = 'vrd_head';

	for(i=0;i<name.length;i++){
		del_option(name[i]);
		}	

	if(type == 2){
		name[0] = 'buyer_head_e';
		name[1] = 'vrd_staff_e';
		name[2] = 'vrd_head_e';
		for(i=0;i<name.length;i++){
		del_option(name[i]);
		}	
	}

	if(type == 1){
		$('<option data-id = "0" value ="0" disabled selected="selected">  -- Select BUHEAD -- </option>').appendTo('#buyer_head');
		$('<option data-id = "0" value ="0" disabled selected="selected"> -- Select VRDSTAFF -- </option>').appendTo('#vrd_staff');
		$('<option data-id = "0" value ="0" disabled selected="selected"> -- Select VRDHEAD -- </option>').appendTo('#vrd_head');
	}

	Object.keys(data).forEach(function(key){

		if(data[key]['USER_MIDDLE_NAME'] == null){
			data[key]['USER_MIDDLE_NAME']	= '';
		}
		if(data[key]['USER_LAST_NAME'] == null){
			data[key]['USER_LAST_NAME']	= '';
		}

		switch(data[key]['POSITION_ID']){
			case '3': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[0]);
			break;
			case '4': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[1]);
			break;
			case '5': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[2]);
			break;
			default:
			break;
		}
		
	});

}
function fill_buyer(data,type){

	data = JSON.parse(data);
	var name = [];
	 name[0] = 'g_head';
	 name[1] = 'fas_head';
	 name[2] = 'vrd_staff';
	 name[3] = 'vrd_head';

	for(i=0;i<name.length;i++){
			del_option(name[i]);
		}

	if(type == 2){

		name[0] = 'g_head_e';
		name[1] = 'fas_head_e';
		name[2] = 'vrd_staff_e';
		name[3] = 'vrd_head_e';

		for(i=0;i<name.length;i++){
			del_option(name[i]);
		}
	}

		if(type == 1){
		$('<option data-id = "0" value ="0" disabled selected="selected">  -- Select GHEAD -- </option>').appendTo('#g_head');
		$('<option data-id = "0" value ="0" disabled selected="selected"> -- Select FASHEAD -- </option>').appendTo('#fas_head');
		$('<option data-id = "0" value ="0" disabled selected="selected"> -- Select VRDSTAFF -- </option>').appendTo('#vrd_staff');
		$('<option data-id = "0" value ="0" disabled selected="selected"> -- Select VRDHEAD -- </option>').appendTo('#vrd_head');
	}




	Object.keys(data).forEach(function(key){

		if(data[key]['USER_MIDDLE_NAME'] == null){
			data[key]['USER_MIDDLE_NAME']	= '';
		}
		if(data[key]['USER_LAST_NAME'] == null){
			data[key]['USER_LAST_NAME']	= '';
		}

		switch(data[key]['POSITION_ID']){
			case '8': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[0]);
			break;
			case '9': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[1]);
			break;
			case '4': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[2]);
			break;
			case '5': 
					$('<option value = "'+data[key]['USER_ID']+'" data-id = "'+data[key]['USER_ID']+'">'+data[key]['USER_FIRST_NAME'] + ' ' + data[key]['USER_MIDDLE_NAME'] +' ' + data[key]['USER_LAST_NAME'] + '</option>').appendTo('#'+name[3]);
			break;
			default:
			break;
		}
		
	});


}

function del_option(name)
{
  $('#'+name)
    .find('option')
    .remove()
    .end();
}


function get_category(data,name){
		var pos_id;
		if($("#slt_user_position_edit").val()){
			pos_id = $("#slt_user_position_edit").val();
		}else if($("#slt_user_position_new").val()){
			pos_id = $("#slt_user_position_new").val();
		}
		$.ajax({
			type:'POST',
			data:{data: data, position_id : pos_id},
			url:'maintenance/users/get_category',
			async: false,
			success: function(result){				
					fil_category(result,name);
			}	
			}).fail(function(){
				alert(result)
			});	
}

function fil_category(data,name){
	data = JSON.parse(data);

	Object.keys(data).forEach(function(key){

		$('<option data-cat = "'+ data[key]['CATEGORY_ID']+'">'+ data[key]['CATEGORY_NAME']+'</option>').appendTo('#'+name);

	});
}

function save_new_user(){
	$('.alert').css("display", "none");
	let error_temp = [];
	let head = [];
	let error_msg = '';
	let a_type = 0;
	let cat_id = [];
	let smu = 0;
	$('#new_login_id').parent('div').removeClass('has-error');


	if($('#chk_send_email').prop('checked') == true){
		smu = 1;
	}
	let _data = {
		fn: $('#fn_new').val(),
		mn: $('#mn_new').val(),
		ln: $('#ln_new').val(),
		mo: $('#mo_new').val(),
		em: $('#email_new').val(),
		log: $('#new_login_id').val(),
		/*pw: $('#pass_new').val(),
		cpw: $('#confirm_pass_new').val(),*/
		se: smu
	}

		
	if (_data['log'] != "") {
		if ( /[^A-Za-z\d]/.test(_data['log'])) {
			error_msg = '<strong>Failed!</strong> Special Character is not allowed in Login ID!'
			modal_notify('add_user_mod',error_msg,'danger',true);	
			loading('btn_save_new','');
			$('#new_login_id').parent('div').addClass('has-error');
			return (false);
		}
	}

	if(_data['log'].length<5){
		error_msg = '<strong>Failed!</strong> Minimum number of character for Login ID is 5!'
		modal_notify('add_user_mod',error_msg,'danger',true);	
		loading('btn_save_new','');
		return;
	}

	error_temp = check_validation('add_user_mod');
	$('#sel_category_new').parent('div').removeClass('has-error');


	if(error_temp[0] != undefined){
		error_msg = '<strong>Failed!</strong> User creation failed. Please Fill All Required Fields.'
		modal_notify('add_user_mod',error_msg,'danger',true);	
		loading('btn_save_new','');
		return;
	}

	if(($('#slt_user_position_new option:selected').data('pos') == '2')||($('#slt_user_position_new option:selected').data('pos') == '7')||($('#slt_user_position_new option:selected').data('pos') == '11')){

		a_type = $('#slt_user_position_new option:selected').data('pos');

		$('#sel_category_new option').each(function(){
			cat_id.push($(this).data('cat'));
		});

		if(cat_id.length == 0){
			$('#sel_category_new').parent('div').addClass('has-error');
			modal_notify('add_user_mod','<strong>Failed!</strong> Select atleast 1 category.','danger',true);
			loading('btn_save_new','');
			return;
		}
	}else{
		a_type = $('#slt_user_position_new option:selected').data('pos');
	}


	 let n_email = $('#email_new').val();
	// console.log(n);
	

	if(valid_email(n_email) == false){
		error_msg = '<strong>Failed!</strong> User creation failed. Invalid E-mail Format.'
		modal_notify('add_user_mod',error_msg,'danger',true);	
		loading('btn_save_new','');
		$('#email_new').parent('div').addClass('has-error');
		return;
	}



	if(_data['mo'].length < 4){
		error_msg = '<strong>Failed!</strong> Minimum Mobile Number length is 4!'
		modal_notify('add_user_mod',error_msg,'danger',true);	
		loading('btn_save_new','');
		$('#mo_new').parent('div').addClass('has-error');
		return;
	}


	if(_data['mo'].length > 11){
		error_msg = '<strong>Failed!</strong> Maximum Mobile Number length is 11!'
		modal_notify('add_user_mod',error_msg,'danger',true);
		$('#mo_new').parent('div').addClass('has-error');	
		loading('btn_save_new','');
		return;
	}


	var reg = new RegExp('^[0-9]+$');

	if(reg.test(_data['mo']) == false){
		error_msg = '<strong>Failed!</strong>Mobile Number cannot contain alphabets and special character!'
		$('#mo_new').parent('div').addClass('has-error');
		modal_notify('add_user_mod',error_msg,'danger',true);		
		loading('btn_save_new','');
		return;
	}
	

		
	/*if(_data['pw'] != _data['cpw']){

		modal_notify('add_user_mod','<strong>Failed!</strong> Password and Confirm Password dont match.','danger',true);
		loading('btn_save_new','');
		return;
	}*/

	if((a_type == 11) || (a_type == 2) || (a_type == 7)){
		if(check_vrd_same() == false){

			modal_notify('add_user_mod','<strong>Failed!</strong> Duplicate VRD STAFF','danger',true);
			loading('btn_save_new','');
			return;
		}
	}

	if((a_type == 11) || (a_type == 2)){
		if($('#vrd_head option:selected').data('id') == 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Please select a VRDHEAD','danger',true);
			loading('btn_save_new','');
			return;
		}
		if($('#buyer_head option:selected').data('id') == 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Please select a BUHEAD','danger',true);
			loading('btn_save_new','');
			return;
		}

		head[0] = $('#vrd_head option:selected').data('id');
		head[1] = '';
		head[2] = $('#buyer_head option:selected').data('id');
		head[3] = ''
		head[4] = ''
	}
	if(a_type == 7){
		if($('#vrd_head option:selected').data('id') == 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Please select a VRDHEAD','danger',true);
			loading('btn_save_new','');
			return;
		}
		if($('#g_head option:selected').data('id') == 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Please select a GHEAD','danger',true);
			loading('btn_save_new','');
			return;
		}
		if($('#fas_head option:selected').data('id') == 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Please select a FASHEAD','danger',true);
			loading('btn_save_new','');
			return;
		}
		head[0] = $('#vrd_head option:selected').data('id');
		head[1] = '';	
		head[2] = '';
		head[3] = $('#g_head option:selected').data('id');
		head[4] = $('#fas_head option:selected').data('id');
	}

	save_new(_data,a_type,head,cat_id)
}

function check_validation(name){

	var iError = [];

	$('#'+name+' input').each(function()
	{	
		if($(this).data('exclude') == 'exc'){return true;}
		if($(this).is(':checkbox')){return true;}
		if($.trim($(this).val()).length == 0){	
			console.log($(this)[0].id);
			if(String($(this)[0].id) == "mn_new"){
							
			}else if(String($(this)[0].id) == "mn_edit"){

			}else{
				iError.push($(this).data('name'))
				$('#'+ this.id).parent('div').addClass('has-error');	
			}			
		}else{
			$(this).closest('div').removeClass('has-error');
		}
	});	
	return iError;


}


function valid_email(email)
{

	 var re = /\S+@\S+\.\S+/;
	  return re.test(email);

}

function clear_modal(name2){
	regCnt = 0;
	let name = [];

		name[0] = 'g_head_e';
		name[1] = 'fas_head_e';
		name[2] = 'vrd_staff_e';
		name[3] = 'vrd_head_e';
		name[4] = 'buyer_head';
		name[5] = 'sel_category_new';
		name[6] = 'slt_user_position_new';

		$('#sel_category_e').parent('div').removeClass('has-error');
		$('#sel_category_e').empty();
		for(i = 0;i<name.length;i++){
			del_option(name[i]);
		}

	$('.alert').css("display", "none");
	$('#'+name2+' input').each(function()
	{	
		$(this).parent('div').removeClass('has-error');
		$(this).val('');
	});

	$('.fd_minus_e').each(function(){
		$(this).trigger('click');
	});
	$('.d_minus').each(function(){

		$(this).trigger('click');
	});
	$('.d_minus_e').each(function(){
		$(this).trigger('click');
	});
	$('.d_minus_').each(function(){
		$(this).trigger('click');
	});

	$("#slt_user_position_edit option[value='-1']").remove();
	$("#slt_user_type_edit option[value='-1']").remove();
	$("#slt_user_position_edit option[value='10']").remove();
	vcnt = 0;
	venCnt = 0;
	tcnt = 0;
	regCnt = 0;


	$('#cont_vrdhead').html('');
	$('#hel').remove();

	

}



function save_new(data,type,head,category)
{

	let vrd = [];
	let vrdhead  = [];
	let vs = 0;
	let vh = 0;

	if((type == 11) || (type == 2) || (type == 7)){
		if(document.getElementById('vrd_staff').options[document.getElementById('vrd_staff').selectedIndex].value == 0){
			vs = vs+1; 
		}
		vrd.push(document.getElementById('vrd_staff').options[document.getElementById('vrd_staff').selectedIndex].value);
		
		$('#cont_vrd select').each(function(){
			if(this.options[this.selectedIndex].value == 0){
				vs = vs + 1;
				$(this).parent().addClass('has-error');
			}
			vrd.push(this.options[this.selectedIndex].value);
		});

		vrdhead.push(document.getElementById('vrd_head').options[document.getElementById('vrd_head').selectedIndex].value)

		$('#cont_vrdhead select').each(function(){
			if(this.options[this.selectedIndex].value == 0){
				vh = vh + 1;
				$(this).parent().addClass('has-error');
			}
			vrdhead.push(this.options[this.selectedIndex].value);
		});

		if(vrdhead.length>1){
			if(check_same(vrdhead) == false){
			modal_notify('add_user_mod','<strong>Failed!</strong> Duplicate VRD HEAD','danger',true);
			loading('btn_save_new','');
			return;
			}
		}


	if(vs>0 || vh >0){
		if(vs > 0 && vh>0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Select VRD HEAD <br> <strong>Failed!</strong> Select VRD STAFF ','danger',true);
		}
		if(vs > 0 && vh <= 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Select VRD STAFF','danger',true);
		}
		if(vh > 0 && vs <= 0){
			modal_notify('add_user_mod','<strong>Failed!</strong> Select VRD HEAD','danger',true);
		}
		loading('btn_save_new','');
		return;
	}



	}

	$.ajax({
		type:'POST',
		data:{data: data,head: head,category: category,type: type,vrd: vrd,vrdhead :vrdhead},
		//dataType: "json",
		url:'maintenance/users/save_user_new',		
		success: function(result){		
			res = JSON.parse(result)
			
			if(res == 'exist'){
				modal_notify('add_user_mod','<strong>Failed!</strong> Login ID already exist.','danger',true);
				$('#new_login_id').parent('div').addClass('has-error');
				loading('btn_save_new','');
				return;
			}
			if(res == true){

				loading('btn_save_new','');
				$('#add_user_mod').modal('toggle');
				clear_modal('add_user_mod');
				notify('<strong>Success!</strong> User creation success.','success');
				get_pos();
				get_senmer();
				get_buyer();
				get_pos();
				get_pos2();
				$('#btn_search_user').trigger('click');
				return;
			}
			else{
				//console.log(result);
				modal_notify('add_user_mod','<strong>Failed!</strong>User creation failed, please try again.','danger',true);
				loading('btn_save_new','');
				return;
			}
		}	
	}).fail(function(result){
		loading('btn_save_new','');
	});	
	
}

// $(window).load(function()
// {

// $('#btn_search_user').trigger('click');

// });







//------------------------------------------end create user----------------------------------------------

$(document).on("click", "#btn_search_user", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        loading('btn_search_user','in_progress');


       clear_alert();
       search_user(0);

    
       
    }
});



$(document).on('click','#users_pagination .cl_pag',function(){
	let n = this.dataset.pg;
	if($(this).attr('disabled') == 'disabled'){
		return;
	}
	search_user(n-1);

});

$(document).on('click','#tbl_search_user .a_table_header a',function(){

	 _sort = $(this).data('sort');
	 s_type = $(this).data('sort_type');
	 let n = this;
	 let m = $(this).closest('th');

	 $('#tbl_search_user .a_table_header').each(function(){

	 let x = $(this);

/*	 $(x).removeClass('glyphicon-sort-by-attributes');
	 $(x).removeClass('glyphicon-sort-by-attributes-alt');
	 $(x).addClass('glyphicon-sort-by-attributes');
*/


	 $(x).removeClass('sort_column sort_desc');
	 $(x).removeClass('sort_column sort_asc');
	 $(x).addClass('sort_column sort_default');
/*	 	console.log(this);
	 	$(this).removeClass('glyphicon-sort-by-attributes-alt');
	 	$(this).addClass('glyphicon-sort-by-attributes');*/
	 });

	 if(n.dataset.sort == 'desc'){
	 	n.dataset.sort = 'asc';
	 	$(n).data('sort','asc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_asc');
	 }else{
	 	n.dataset.sort = 'desc';
	 	$(n).data('sort','desc');
	 	$(m).removeClass('sort_column sort_default');
	 	$(m).addClass('sort_column sort_desc');
	 }

	 _sort = $(this).data('sort');
	 search_user(0);
	//get_table_data(0,$('#msg_notification_type').val());
});


function search_user(_start)
{

		$('#tbl_users_dis').addClass('disabledbutton');
       let _type = $('#user_select_type option:selected').data('stype');
       let _search = $.trim($('#search_user_type').val());


		$.ajax({
			type:'POST',
			data:{_search: _search,_type: _type,_start: _start,_sort: _sort, s_type: s_type},
			url:'maintenance/users/searchuser',		
			success: function(result){
				//console.log(result);
			//$('#tbl_search_user').DataTable().ajax.reload();
			create_table(result,'tbl_search_user');
			let data = JSON.parse(result);
			create_pagination_proto(_start,Math.ceil(data['count']/10),'users_pagination');
			loading('btn_search_user','');	
			$('#tbl_users_dis').removeClass('disabledbutton');
				
				
			}	
			}).fail(function(result){
				loading('btn_search_user','');
				});		
}


function create_table(data,name)
{
	//console.log(data);
	data = JSON.parse(data);
	//console.log(data);

 // /$("#"+name).find("tr:gt(0)").remove();	
 let dm = {ds : data['data']}


let tmpl = document.getElementById('user_table').innerHTML;
let htmls = Mustache.render(tmpl,dm);
let table = document.getElementById('tbl_search_user').tBodies[0];

//console.log(table);

table.innerHTML = htmls;


}


String.prototype.toProperCase = function () {
    return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
};



$(document).on("click", ".edit_user", function (e) {   
	   e.preventDefault();
		if (e.handled !== true) { //Checking for the event whether it has occurred or not.
			clear_alert();
			document.getElementById('fn_edit').setAttribute('data-ui','');
			let uid = this.getAttribute('data-ui');      
			document.getElementById('fn_edit').setAttribute('data-ui',uid);
			get_edit_data(uid);
			//fill_edit(this); 
		}
		e.stopImmediatePropagation();
});

$(function(){
	$("#deactivated-alert").css('display','none');
});
function get_edit_data(uid)
{
	loading('btn_save_e','in_progress');
	$('#slt_user_position_edit').prop('disabled',false);

	$.ajax({
		type:'POST',
		data:{uid: uid},
		//dataType: "json",
		url:'maintenance/users/edit_user_info',		
		success: function(result){		
		 	n = JSON.parse(result);
		 	//console.log(n);
		 	if(n['INFORMATION'][0]['POSITION_ID'] == '10'){ 		
		 		$('#slt_user_position_edit').prop('disabled',true);
		 		$('<option data-pos = "10" value = "-1">VENDOR</option>').appendTo('#slt_user_type_edit');		
		 		$('#slt_user_type_edit').val('-1');
		 		tarray = po1;
		 		add_item_position(tarray,'slt_user_position_edit');
		 		$('<option data-pos = "10" value = "-1">VENDOR</option>').appendTo('#slt_user_position_edit');
		 		$('#slt_user_type_edit').prop('disabled',true);		
		 		$('#slt_user_position_edit').val('-1');
		 		$("#slt_user_position_edit option[value='10']").remove();
		 		$('#btn_resend').prop('disabled',true);
		 		$('#btn_view_resend_log').prop('disabled',true);
		 	}else{
		 		$('#slt_user_position_edit').prop('disabled',false);
		 		$('#slt_user_type_edit').prop('disabled',false);
		 		tarray = po2;	 	
		 		$('#slt_user_type_edit').val('1');	 		
		 		add_item_position(tarray,'slt_user_position_edit'); 		
		 		$('#slt_user_position_edit').val(n['INFORMATION'][0]['POSITION_ID']);
		 		$('#slt_user_position_edit').trigger('change');
		 		$("#slt_user_position_edit option[value='10']").remove();
		 		$('#btn_resend').prop('disabled',false);
		 		$('#btn_view_resend_log').prop('disabled',false);
		 	}
		 	loadingScreen('off');

		uival_change('edit_login_id',n['CREDENTIALS'][0]['USERNAME']);
		uival_change('pass_edit',n['CREDENTIALS'][0]['PASSWORD']);
		uival_change('confirm_pass_edit',n['CREDENTIALS'][0]['PASSWORD']);

		document.getElementById('fn_edit').setAttribute('data-ui','');
        //let uid = this.getAttribute('data-ui');      
        document.getElementById('fn_edit').setAttribute('data-ui',n['INFORMATION'][0]['USER_ID']);

		if(n['INFORMATION'][0]['USER_FIRST_NAME'] == null){
		n['INFORMATION'][0]['USER_FIRST_NAME']  = '';
		}
		if(n['INFORMATION'][0]['USER_MIDDLE_NAME'] == null){
		n['INFORMATION'][0]['USER_MIDDLE_NAME']  = '';
		}
		if(n['INFORMATION'][0]['USER_LAST_NAME'] == null){
		n['INFORMATION'][0]['USER_LAST_NAME']  = '';
		}
		if(n['INFORMATION'][0]['USER_MOBILE'] == null){
		n['INFORMATION'][0]['USER_MOBILE']  = '';
		}
		if(n['INFORMATION'][0]['USER_EMAIL'] == null){
		n['INFORMATION'][0]['USER_EMAIL']  = '';
		}
		uival_change('fn_edit',n['INFORMATION'][0]['USER_FIRST_NAME']);
		uival_change('mn_edit',n['INFORMATION'][0]['USER_MIDDLE_NAME']);
		uival_change('ln_edit',n['INFORMATION'][0]['USER_LAST_NAME']);
		uival_change('mo_edit',n['INFORMATION'][0]['USER_MOBILE']);
		uival_change('email_edit',n['INFORMATION'][0]['USER_EMAIL']);


		
		if(n['INFORMATION'][0]['POSITION_ID'] == 2 || n['INFORMATION'][0]['POSITION_ID'] == 11){
			uival_change('vrd_head_e',n['UMATRIX'][0]['VRDHEAD_ID']);
			uival_change('buyer_head_e',n['UMATRIX'][0]['BUHEAD_ID']);
		}

		if(n['INFORMATION'][0]['POSITION_ID'] == 7){
			uival_change('vrd_head_e',n['UMATRIX'][0]['VRDHEAD_ID']);
			uival_change('fas_head_e',n['UMATRIX'][0]['FASHEAD_ID']);
			uival_change('g_head_e',n['UMATRIX'][0]['GHEAD_ID']);

		}

		$('<input type = "hidden" id="hel" value ="'+n['INFORMATION'][0]['USER_ID']+'">').appendTo('#dhs');
		let p =0 ;

		if(n['VRDSTAFF'] != null){
		

			Object.keys(n['VRDSTAFF']).forEach(function(key){
			if(p == 0){
			//hhss
			}else{
			add_vrdstaff_e(n['VRDSTAFF'][key]);	

			}				
			p++;
			});

			$('#vrd_staff_e').val(n['VRDSTAFF'][0]);
			if((n['VRDSTAFF'].length)>1){
			let i = 1;
			$('#cont_vrd_e select').each(function(){
				$(this).val(n['VRDSTAFF'][i]);
				i++;
			});

			}
		
		//If BUYER disable multiple selection in category
		if(n['INFORMATION'][0]['POSITION_ID'] == 7){
			$("#sel_category_e").removeAttr('multiple');
			$("#category_e").removeAttr('multiple');
		}else{
			//NTS and TRADE
			$("#sel_category_e").attr('multiple', 'true');
			$("#category_e").attr('multiple', 'true');
		}
		
		$('#btn_search_category_e').trigger('click');
			$(document).ready(function(){
				Object.keys(n['CATEGORY']).forEach(function(key){
					$('<option data-cat = "'+ n['CATEGORY'][key]['CATEGORY_ID'] +'">'+n['CATEGORY'][key]['CATEGORY_NAME']+'</option>').appendTo('#sel_category_e');
					$('#category_e option[data-cat='+ n['CATEGORY'][key]['CATEGORY_ID'] +']').remove();
					//console.log($('#category_e <option data-cat = "'+ n['CATEGORY'][key]['CATEGORY_ID'] +'"'));
				});

			});
		}

		let tmpVrd = [];
		let oT = 0;
		if(n['UMATRIX'] != null){
			n['UMATRIX'].forEach(function(key){
				if(key['VRDHEAD_ID'] != null){
					tmpVrd.push(key['VRDHEAD_ID']);

					if(oT != 0){
						add_vrdhead_e();
					}
					oT++;

				}
			});
		}

		if(tmpVrd.length >0 ){
		$('#vrd_head_e').val(tmpVrd[0]);
		let iT = 1;
			for(iT = 1 ;iT <tmpVrd.length; iT++){
				// console.log(tmpVrd[iT])
				$('#vrd_staff_e_'+iT).val(tmpVrd[iT]);
			}
		}
	
		if(n['LOGIN_ATTEMPTS']['ATTEMPTS'] > 0){
			$("#unlock_reason_txt").val("");
			$("#deactivated-alert").css('display','block');	
			$("#unlock_date").html(n['LOGIN_ATTEMPTS']['UNLOCK_TIME_FORMATTED']);
			$("#total_attempts").html(n['LOGIN_ATTEMPTS']['ATTEMPTS']);
			$("#btn_unlock_account").removeAttr('disabled');
		}else{
			$("#deactivated-alert").css('display','none');	
		}
		
		loading('btn_save_e','');
	
	}
		}).fail(function(result){
			});	


	

}

function uival_change(id,val)
{

	$('#'+id).val(val);

}



function fill_edit(el)
{


	let _name = [];
	let _rid = [];
	let _utype ='';
	let _pid = '';

	getcategory_user($(el).data('ui'));

	_utype = $(el).data('utid');
	_pid = $(el).data('pid');

	_rid = $(el).data('head').split('|');
	_name = $(el).data('name').split('/');

	$('#edit_login_id').val($(el).data('uid'));
	$('#pass_edit').val($(el).data('ps'));
	$('#confirm_pass_edit').val($(el).data('ps'));
	$('#fn_edit').val(_name[0]);
	$('#fn_edit').data('ui',$(el).data('ui'))
	$('#mn_edit').val(_name[1]);
	$('#ln_edit').val(_name[2]);
	$('#email_edit').val($(el).data('em'));
	$('#mo_edit').val($(el).data('mo'));

	if(_utype == 'SM'){
		$('#slt_user_type_edit').val('SM');
		$('#slt_user_type_edit').trigger('change');
	}else{
		$('#slt_user_type_edit').val('VENDOR');
		$('#slt_user_type_edit').trigger('change');
	}

	if((_pid == 2) || (_pid == 7)) {
	//	$('#slt_user_position_edit').attr('disabled');
		$('#slt_user_position_edit').val(_pid);
		$('#slt_user_position_edit').trigger('change');
					$('#buyer_head_e').val(_rid[0]);
					$('#g_head_e').val(_rid[1]);
					$('#fas_head_e').val(_rid[2]);
					$('#vrd_head_e').val(_rid[3]);
					$('#vrd_staff_e').val(_rid[4]);

	}else{
		$('#slt_user_position_edit').trigger('change');
		
	}




}

$(document).on('change','#slt_user_type_edit',function(){
	let data = $(this).find('option:selected').data('type');

	//alert(data);
	let tarray = [];
		if(data == 1){
		tarray = po2;
	}else{
	tarray = po1;
	}

	add_item_position(tarray,'slt_user_position_edit');

});

$(document).on('change','#slt_user_position_edit',function(a){
	$('.a_senmer_e').hide();
	$('.a_buyer_e').hide();
	$('.category_css_e').hide();	
	$("#category_new").html(""); //reset
	$("#sel_category_new").html(""); //reset
	let n = ($(this).find('option:selected').val());



$('#cont_vrd_e [href]').each(function(){
	let l = this.dataset.tr;
	$('.tr_e_'+l).remove();
});

tcnt = 0;
vcnt = 0;

	if((n == 11) || (n == 2 ) || (n == 7)){
		if((n==2)||n == 11){
				$('.a_senmer_e').hide();
				$('.a_buyer_e').show();	
				$('.category_css_e').show();
				fill_senmer(sn,2)	
				
			$("#category_new").attr("multiple", "true");
			$("#sel_category_new").attr("multiple", "true");
		}
		if(n==7){
			$('.a_buyer_e').hide();
			$('.a_senmer_e').show();
			$('.category_css_e').show();
			fill_buyer(by,2);	
			$("#category_new").removeAttr("multiple");
			$("#sel_category_new").removeAttr("multiple");
		}
		
	}

});


function getcategory_user(data)
{

		$.ajax({
			type:'POST',
			data:{data: data},
			//dataType: "json",
			url:'maintenance/users/get_catuser',		
			success: function(result){		
				fill_cat_edit(result);

			}	
			}).fail(function(result){
				});	
	
}

function fill_cat_edit(data){

	data = JSON.parse(data);


	$('#sel_category_e')
    .find('option')
    .remove()
    .end()	
	Object.keys(data).forEach(function(keys){

		$('<option data-cat = "'+data[keys]['CATEGORY_ID']+'">'+data[keys]['CATEGORY_NAME']+'</option>').appendTo('#sel_category_e');

	});

}

$(document).on("click", "#btn_rmv_cat_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
	
		$('#sel_category_e').find('option:selected').each(function(e){
			console.log($(this).val());
			console.log($(this).val());
			_data =  $(this).data('cat');
			name =   $(this).val();
			let t = true;

			if( name == 'undefined'){return;}



			$('#category_e').find('option').each(function(){
				if(_data == $(this).data('cat')){			
					t = false;
					$('#sel_category_e option:selected').remove();
					return false;
				}
			});

			if(t == true){
				$('#sel_category_e option:selected').remove();
				$('<option data-cat = "'+_data+'">'+name+'</option>').appendTo('#category_e')
			}
			var sel = $("#category_e");
			var selected = sel.val(); // cache selected value, before reordering
			var opts_list = sel.find('option');
			opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
			sel.html('').append(opts_list);
		});

    }
});

$(document).on("click", "#btn_add_cat_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		
		$('#category_e').find('option:selected').each(function(e){
			console.log($(this).val());
			console.log($(this).val());
			_data =  $(this).data('cat');
			name =   $(this).val();
			let t = true;

			if( name == 'undefined'){return;}



			$('#sel_category_e').find('option').each(function(){
				if(_data == $(this).data('cat')){			
						t = false;
						$('#category_e option:selected').remove();
						return false;
					}
			});

			if(t == true){
				$('#category_e option:selected').remove();
				$('<option data-cat = "'+_data+'">'+name+'</option>').appendTo('#sel_category_e')
			}
			
			var sel = $("#sel_category_e");
			var selected = sel.val(); // cache selected value, before reordering
			var opts_list = sel.find('option');
			opts_list.sort(function(a, b) { return $(a).text() > $(b).text() ? 1 : -1; });
			sel.html('').append(opts_list);
		});
    }
});

$(document).on("click", "#btn_search_category_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

        let search = $('#search_cat_user_e').val();
        del_option('category_e');
        get_category(search,'category_e');


    }
});

$(document).on("click", "#btn_save_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.

    
     $('.alert').css("display", "none");
	let error_temp = [];
	let head = [];
	let error_msg = '';
	let a_type = 0;
	let cat_id = [];
	let hel = '';
	hel = $('#hel').val();

	if(hel.length == 0){

		hel = $('#fn_edit').data('ui');

	}
	
	let _data = {};



_data = {
		fn: $('#fn_edit').val(),
		ui: hel,
		mn: $('#mn_edit').val(),
		ln: $('#ln_edit').val(),
		mo: $('#mo_edit').val(),
		em: $('#email_edit').val(),
		pw: $('#pass_edit').val(),
		cpw: $('#confirm_pass_edit').val()
	}

	//console.log(_data);

	   
	error_temp = check_validation('edit_user_mod');
	$('#sel_category_e').parent('div').removeClass('has-error');



	if(error_temp[0] != undefined){
		error_msg = '<strong>Failed!</strong> User creation failed. Please Fill All Required Fields.'
		modal_notify('edit_user_mod',error_msg,'danger',true);	
		return;
	}
	

	if(($('#slt_user_position_edit option:selected').data('pos') == '2')||($('#slt_user_position_edit option:selected').data('pos') == '7')||($('#slt_user_position_edit option:selected').data('pos') == '11')){

		a_type = $('#slt_user_position_edit option:selected').data('pos');

		$('#sel_category_e option').each(function(){
			cat_id.push($(this).data('cat'));
		});

		if(cat_id.length == 0){
			$('#sel_category_e').parent('div').addClass('has-error');
			modal_notify('edit_user_mod','<strong>Failed!</strong> Select atleast 1 category.','danger',true);
			return;
		}
	}else{
		a_type = $('#slt_user_position_edit option:selected').data('pos');
	}

		

	/*if(_data['pw'] != _data['cpw']){

		modal_notify('edit_user_mod','<strong>Failed!</strong> Password and Confirm Password dont match.','danger',true);
		return;
	}*/


	 let n_email = $('#email_edit').val();
	
	if(valid_email(n_email) == false){
		error_msg = '<strong>Failed!</strong> User creation failed. Invalid E-mail Format.'
		modal_notify('edit_user_mod',error_msg,'danger',true);	
		loading('btn_save_e','');
		$('#email_edit').parent('div').addClass('has-error');
		return;
	}



	if(_data['mo'].length < 4){
		error_msg = '<strong>Failed!</strong> Minimum Mobile Number length is 4!'
		modal_notify('edit_user_mod',error_msg,'danger',true);	
		loading('btn_save_e','');
		$('#mo_edit').parent('div').addClass('has-error');
		return;
	}


	if(_data['mo'].length > 11){
		error_msg = '<strong>Failed!</strong> Maximum Mobile Number length is 11!'
		modal_notify('edit_user_mod',error_msg,'danger',true);
		$('#mo_edit').parent('div').addClass('has-error');	
		loading('btn_save_e','');
		return;
	}


	var reg = new RegExp('^[0-9]+$');

	if(reg.test(_data['mo']) == false){
		error_msg = '<strong>Failed!</strong>Mobile Number cannot contain alphabets and special character!'
		$('#mo_edit').parent('div').addClass('has-error');
		modal_notify('edit_user_mod',error_msg,'danger',true);		
		loading('btn_save_e','');
		return;
	}
	


	if((a_type == 11) || (a_type == 2) || (a_type == 7)){
		if(check_vrd_e_same() == false){
	    	modal_notify('edit_user_mod','<strong>Failed!</strong> Duplicate VRD STAFF','danger',true);
	    	loading('btn_save_e','');
	        return;
	    }
	}

		

	if((a_type == 2) || (a_type == 11)){
		head[0] = $('#vrd_head_e option:selected').data('id');
		head[1] = '';
		head[2] = $('#buyer_head_e option:selected').data('id');
		head[3] = '';
		head[4] = '';
	}
	if(a_type == 7){
		head[0] = $('#vrd_head_e option:selected').data('id');
		head[1] = '';
		head[2] = '';
		head[3] = $('#g_head_e option:selected').data('id');
		head[4] = $('#fas_head_e option:selected').data('id');
	}	
save_edit(_data,a_type,head,cat_id);



    }
});


function save_edit(data,type,head,category)
{


	let vrd = [];
	let vrdhead = [];
	if((type == 11) || (type == 2) || (type == 7)){	
	vrd.push(document.getElementById('vrd_staff_e').options[document.getElementById('vrd_staff_e').selectedIndex].value);
	$('#cont_vrd_e select').each(function(){
		vrd.push(this.options[this.selectedIndex].value);
	});

	vrdhead.push(document.getElementById('vrd_head_e').options[document.getElementById('vrd_head_e').selectedIndex].value);	
	$('#cont_vrdhead_e select').each(function(){
		vrdhead.push(this.options[this.selectedIndex].value);
	});

	if(check_same(vrdhead) == false){
		modal_notify('edit_user_mod','<strong>Failed!</strong> Duplicate VRD HEAD','danger',true);
		loading('btn_save_e','');
		return;
	}

	}

		$.ajax({
			type:'POST',
			data:{data: data,head: head,category: category,type: type,vrd: vrd,vrdhead: vrdhead},
			//dataType: "json",
			url:'maintenance/users/save_user_edit',		
			success: function(result){
			document.getElementById('fn_edit').setAttribute('data-ui','');
			$('#fn_edit').removeData('ui');
				res = JSON.parse(result)
				//console.log(res);
				if(res == true){
					clear_modal('edit_user_mod');
					$('#edit_user_mod').modal('toggle');
					notify('<strong>Success</strong> Update user information successful.','success');
					$('#btn_search_user').trigger('click');	
				}					
			}	
			}).fail(function(result){
				//console.log(result);
				});	
	
}


function  del_user(m){

	let n =($(m).data('id'));
	deactivateYesNo(n);
}

function deactivateYesNo(n)
{
	clear_alert();
	var span_message = 'Do you want to delete this user? <button id = "conf_yes_user" type="button" class="btn btn-success" rel = "'+ n +'">Yes</button>&nbsp;<button id = "conf_no" type="button" data-dismiss="alert" class="btn btn-default">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

$(document).on('click','#conf_yes_user',function(){


	save_del($(this).attr('rel'));

})

function save_del(data)
{
	clear_alert()
		$.ajax({
			type:'POST',
			data:{data: data},
			//dataType: "json",
			url:'maintenance/users/del_user',		
			success: function(result){	
			//console.log(result);			
				res = JSON.parse(result)
				if(res.data == true){
					notify('<strong>Success</strong>  User information deleted successful.','success');	
					$('#btn_search_user').trigger('click');										
					get_senmer();
					get_buyer();
				}else{
					if (typeof res.total !== 'undefined' && res.total > 0) {
						var user_name = convert_null_to_string(res.user_data.USER_FIRST_NAME) +  ' ' + convert_null_to_string(res.user_data.USER_MIDDLE_NAME) + ' ' + convert_null_to_string(res.user_data.USER_LAST_NAME); 
						var message = '<strong>Failed!</strong> The following user/s are still assigned to this approver. Please re-assign before proceeding.<br/><br/>';
						message += '<ul class="list-group" style="max-height: 200px; overflow-y:auto; color: #000000;">';
						for(var x = 0; x < res.total; x++){
							var temp_name = convert_null_to_string(res.data[x].USER_FIRST_NAME) +  ' ' +  convert_null_to_string(res.data[x].USER_MIDDLE_NAME) + ' ' + convert_null_to_string(res.data[x].USER_LAST_NAME); 
							message += '<li class="list-group-item">' + temp_name.trim() + '</li>';
						}
						message += '</ul>';
						notify(message,'warning',false);	
					}
				}
			}	
			}).fail(function(result){
				//console.log(result);
				});	
	
}

function convert_null_to_string(value) {
    return (value == null) ? "" : value
}

function clear_alert()
{
	$div_notifications.stop().fadeOut("slow", clean_div_notif);
}


function add_vrdstaff()
{	

	if(tcnt >= mvrd -1){
		return;
	}

	tcnt = tcnt + 1;
	regCnt = regCnt + 1;

	var _opt =  fill_vrd_staff(sn);
	// /console.log(opt);
	let _topt = '';
	_opt.forEach(function(key){


   	if(key.fn == null){
   		key.fn = '';
   	}
   	if(key.mn == null){
   		key.mn = '';
   	}
   	if(key.ln == null){
   		key.ln = '';
   	}
   	_topt = _topt +'<option value = "'+key.vl+'">';
   	_topt = _topt +  key.fn + " " + key.mn + " " + key.ln;
   	_topt = _topt +'</option>';

   });

	let _vrd = '';
	_vrd = '<br class="tr_'+regCnt+'"><div class="col-sm-12 tr_'+regCnt+'"></div>';
    _vrd = _vrd +	'<br class="tr_'+regCnt+'"><div class = "a_buyer a_senmer col-sm-4 tr_'+regCnt+'" style="display: block;"> ';                     
    _vrd = _vrd +	'<label for = "users_login_id" style="margin-right:2px;" class="tr_'+regCnt+'">VRD STAFF : </label>';
    _vrd = _vrd +	'<div>';
    _vrd = _vrd + 	'<select style="min-width:280px;" class = "form-control" id = "vrd_staff">'
    _vrd = _vrd +   '<option value = "0"> -- Select VRD STAFF-- </option>';
    _vrd = _vrd + 	_topt;
    _vrd = _vrd + 	'</select>'; 
    _vrd = _vrd +	'<a href="#" onclick="return false;" class="tr_'+regCnt+' d_minus" data-tr = "'+regCnt+'"><span class="glyphicon glyphicon-minus" style="color:red;"></span></a>';
    _vrd = _vrd +	'</div>';
    _vrd = _vrd +	'</div>'; 
    $(_vrd).appendTo('#cont_vrd');
}

function add_vrdhead()
{	

	if(vcnt >= mvrd -1){
		return;
	}

	vcnt = vcnt + 1;
	venCnt = venCnt + 1;

	var _opt = fill_vrd_head(sn);
	// /console.log(opt);
	let _topt = '';
	_opt.forEach(function(key){


   	if(key.fn == null){
   		key.fn = '';
   	}
   	if(key.mn == null){
   		key.mn = '';
   	}
   	if(key.ln == null){
   		key.ln = '';
   	}
   	_topt = _topt +'<option value = "'+key.vl+'">';
   	_topt = _topt +  key.fn + " " + key.mn + " " + key.ln;
   	_topt = _topt +'</option>';

   });

	let _vrd = '';
	_vrd = '<br class="trls_'+venCnt+'"><div class="col-sm-12 tr_'+venCnt+'" ></div>';
    _vrd = _vrd +	'<br class="trls_'+venCnt+'"><div class = "a_buyer a_senmer col-sm-4 trls_'+venCnt+'" style="display: block;"> ';                     
    _vrd = _vrd +	'<label for = "users_login_id" style="margin-right:2px;" class="trls_'+venCnt+'">VRD HEAD : </label>';
    _vrd = _vrd +	'<div>';
    _vrd = _vrd + 	'<select style="min-width:280px;" class = "form-control" id = "vrd_head">'
    _vrd = _vrd +   '<option value = "0"> -- Select VRD HEAD -- </option>';
    _vrd = _vrd + 	_topt;
    _vrd = _vrd + 	'</select>'; 
    _vrd = _vrd +	'<a href="#" onclick="return false;" class="trls_'+venCnt+' fd_minus" data-tr = "'+venCnt+'"><span class="glyphicon glyphicon-minus" style="color:red;"></span></a>';
    _vrd = _vrd +	'</div>';
    _vrd = _vrd +	'</div>'; 
    $(_vrd).appendTo('#cont_vrdhead');
}

function add_vrdstaff_e(vls = 0)
{	

	//console.log(vls);

	if(tcnt >= mvrd -1){
		return;
	}

	tcnt = tcnt + 1;
	regCnt = regCnt + 1;
	let _topt = '';


	var _opt =  fill_vrd_staff(sn);

		_opt.forEach(function(key){
   	if(key.fn == null){
   		key.fn = '';
   	}
   	if(key.mn == null){
   		key.mn = '';
   	}
   	if(key.ln == null){
   		key.ln = '';
   	}

   	_topt = _topt +'<option value = "'+key.vl+'">';
   	_topt = _topt +  key.fn + " " + key.mn + " " + key.ln;
   	_topt = _topt +'</option>';

	});




	let _vrd = '';
	_vrd = '<br class="tr_e_'+regCnt+'"><div class="col-sm-12 tr_e_'+regCnt+'"></div>';
    _vrd = _vrd +	'<br class="tr_e_'+regCnt+'"><div class = "a_buyer a_senmer col-sm-4 tr_e_'+regCnt+'" style="display: block;"> ';                     
    _vrd = _vrd +	'<label for = "users_login_id" style="margin-right:2px;" class="tr_e_'+regCnt+'">VRD STAFF : </label>';
    _vrd = _vrd + 	'<select style="min-width:280px;" class = "form-control" id = " trs_'+regCnt+'">'
    _vrd = _vrd + _topt;
    _vrd = _vrd + 	'</select>';
    _vrd = _vrd +	'<a href="#" onclick="return false;" class="tr_e_'+regCnt+' d_minus_e" data-tr = "'+regCnt+'"><span class="glyphicon glyphicon-minus" style="color:red;"></span></a>';
    _vrd = _vrd +	'</div>'; 
    $(_vrd).appendTo('#cont_vrd_e');



	
/*
   
    	
    		$('.tr_e_'+regCnt + ' #tr_'+regCnt).value(vls);
    		$('.tr_e_'+regCnt + ' #tr_'+regCnt).trigger('change');*/


    

   // $('#tr_' + regCnt ).val(2);
    return regCnt;
   // alert(1);
}



function add_vrdhead_e()
{	

	if(vcnt >= mvrd -1){
		return;
	}

	vcnt = vcnt + 1;
	venCnt = venCnt + 1;

	var _opt = fill_vrd_head(sn);
	// /console.log(opt);
	let _topt = '';
	_opt.forEach(function(key){


   	if(key.fn == null){
   		key.fn = '';
   	}
   	if(key.mn == null){
   		key.mn = '';
   	}
   	if(key.ln == null){
   		key.ln = '';
   	}
   	_topt = _topt +'<option value = "'+key.vl+'">';
   	_topt = _topt +  key.fn + " " + key.mn + " " + key.ln;
   	_topt = _topt +'</option>';

   });

	let _vrd = '';
	_vrd = '<br class="trls_e_'+venCnt+'"><div class="col-sm-12 tr_'+venCnt+'"></div>';
    _vrd = _vrd +	'<br class="trls_e_'+venCnt+'"><div class = "a_buyer a_senmer col-sm-4 trls_e_'+venCnt+'" style="display: block;"> ';                     
    _vrd = _vrd +	'<label for = "users_login_id" style="margin-right:2px;" class="trls_e_'+venCnt+'">VRD HEAD : </label>';
    _vrd = _vrd + 	'<select style="min-width:280px;" class = "form-control" id = "vrd_staff_e_'+venCnt+'">'
    _vrd = _vrd + 	_topt;
    _vrd = _vrd + 	'</select>'; 
    _vrd = _vrd +	'<a href="#" onclick="return false;" class="trls_e_'+venCnt+' fd_minus_e" data-tr = "'+venCnt+'"><span class="glyphicon glyphicon-minus" style="color:red;"></span></a>';
    _vrd = _vrd +	'</div>'; 
    $(_vrd).appendTo('#cont_vrdhead_e');
}

$(document).on("click", ".d_minus", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basi
	 let l = this.dataset.tr;
	 tcnt = tcnt - 1;
	 $('.tr_'+l).remove();
	}
});

$(document).on("click", ".fd_minus", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basi
	 let l = this.dataset.tr;
	 vcnt = vcnt - 1;
	 $('.trls_'+l).remove();
	}
});



$(document).on("click", ".d_minus_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		 let l = this.dataset.tr;
		 tcnt = tcnt - 1;
		 $('.tr_e_'+l).remove();
		}
});

$(document).on("click", ".fd_minus_e", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
		 let l = this.dataset.tr;
		 vcnt = vcnt - 1;
		 $('.trls_e_'+l).remove();

		}
})


function check_vrd_same()
{

	let _arr = [];

	_arr.push(document.getElementById('vrd_staff').options[document.getElementById('vrd_staff').selectedIndex].value);

	$('#cont_vrd select').each(function(){
		if(this.options[this.selectedIndex].value != 0){
		_arr.push(this.options[this.selectedIndex].value);	
	}		
	});

	let i = 0;
	let c = 0;

	for(i=0; i < _arr.length;i++){
		//console.log(i);
		let l = _arr[i];
		c = i+1;
		while(c < _arr.length){
			if(l == _arr[c]){
				return false;
				}
			c++;
		}
	}
	return true;
}

function check_same(_arr)
{



	let i = 0;
	let c = 0;

	for(i=0; i < _arr.length;i++){
		let l = _arr[i];
		if(l == 0){
			continue;
		}
		c = i+1;
		while(c < _arr.length){
			if(l == _arr[c]){
				return false;
				}
			c++;
		}
	}
	return true;
}


function check_vrd_e_same()
{

	let _arr = [];

	_arr.push(document.getElementById('vrd_staff_e').options[document.getElementById('vrd_staff_e').selectedIndex].value);

	$('#cont_vrd_e select').each(function(){
		_arr.push(this.options[this.selectedIndex].value);
	});

	let i = 0;
	let c = 0;

	for(i=0; i < _arr.length;i++){
		//console.log(i);
		let l = _arr[i];
		c = i+1;
		while(c < _arr.length){
			if(l == _arr[c]){
				return false;
				}
			c++;
		}
	}
	return true;
}

function make_template(name)
{

	var x = JSON.parse(result);	
	let dm = {ds : x}
	//console.log(dm);

		if(name == 'slt_user_position_new'){

			let tmpl = document.getElementById('new_temp').innerHTML;
			let htmls = Mustache.render(tmpl,dm);
			let table = document.getElementById(name);
			table.innerHTML = htmls;
}
}



function resend_email(){
	 $('#hel').val();
let n = document.getElementById('hel').value;

	clear_alert();
	var span_message = 'Are you sure you want to resend email for this user? <button type="button" onclick = "confirm_yes('+n+')">Yes</button>&nbsp;<button type="button" id ="close_alert">No</button>';
	modal_notify('edit_user_mod',span_message,'info',true);	

}

function confirm_yes(uid)
{

	let username = document.getElementById('edit_login_id').value;
    var ajax_type = 'POST';
	var url = BASE_URL + "maintenance/users/resend_email/"+uid;




	$.ajax({
			type:'POST',
			data:{uid: uid,uname: username,burl:BASE_URL},
			url:'maintenance/users/resend_email/',
			success: function(result){		
						clear_alert();
						error_msg = '<strong>Success!</strong> E-mail resend successful!';
						modal_notify('edit_user_mod',error_msg,'success');

					
			}	
			}).fail(function(){
		
		});	


}

$(document).on('click', '#btn_unlock_account', function(e){
	e.preventDefault();
	
	$.ajax({
		type:'POST',
		data:{uid: $('#hel').val(),reason: $("#unlock_reason_txt").val()},
		url:'maintenance/users/unlock_account/',
		success: function(result){		
			if(result){
				clear_alert();
				error_msg = '<strong>Success!</strong> Account has unlocked.!';
				modal_notify('edit_user_mod',error_msg,'success');	
				$("#deactivated-alert").css('display','none');	
				
			}else{
				console.log(result);
				clear_alert();
				error_msg = '<strong>failed!</strong> Something went wrong. Please try again!';
				modal_notify('edit_user_mod',error_msg,'error');	
			}			
		}	
	}).fail(function(){
	
	});	
	e.stopImmediatePropagation();
});

$(document).on('click', '#btn_view_unlock_account_log', function(e){
	e.preventDefault();
	$.ajax({
		type:'GET',
		data:{uid: $('#hel').val()},
		url:'maintenance/users/get_unlock_account_logs/',
		success: function(result){		
			$('#unlock_account_log_modal #tbl_history_body').html(result);
		}	
	}).fail(function(){
		
	});	
	e.stopImmediatePropagation();
});


$(document).on('click', '#btn_view_resend_log', function(e){
	e.preventDefault();
	$.ajax({
		type:'GET',
		data:{uid: $('#hel').val()},
		url:'maintenance/users/get_resend_logs/',
		success: function(result){		
			try{				
				var data = JSON.parse(result);
				$('#resend_log_modal #tbl_history_body').html(data.table);
				$('#total-resend').html(data.total);
			}catch(e){
				$('#resend_log_modal #tbl_history_body').html('Something went wrong! ' + e);
			}
		}	
	}).fail(function(){
		
	});	
	e.stopImmediatePropagation();
});

$("#unlock_account_log_modal, #resend_log_modal").on("shown.bs.modal", function () { 
	$($('.modal-backdrop')[1]).css('z-index', '1050');
});
$('#unlock_account_log_modal, #resend_log_modal').on('hidden.bs.modal', function () {
	$('body').addClass('modal-open');
})