

function save_reg_invite(status, btn) // draf = 1, submit = 2, 5 = extend invite, 6 = invite close
{
    loading($(btn), 'in_progress'); // loading
    // enable all first before saving
    disable_enable_frm('frm_invitecreation', false);

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/add_invitecreation/";
    var post_params = $('#frm_invitecreation').serialize();
	var reason_for_extension_note = $("#vi_reason_for_extension_remarks").val();
	post_params += "&status=" + status; // 1 draft, 2 pending for approval, 3 approve, 4 reject 
	if(reason_for_extension_note){
		post_params += "&reason_for_extension=" + encodeURIComponent(reason_for_extension_note.trim());
	}
    
    var success_function = function(responseText)
    {
/*       console.log(responseText);
       return;*/
       // $('#test').html(responseText);
       if (responseText == 1)
       {
            var status_name = '';
            if (status == 1)
                status_name = 'Invite approval successfully saved.';
            else if (status == 2)
                status_name = 'Invite approval successfully submitted.';
            else if (status == 5)
                status_name = 'Successfully extended!';
            else if (status == 6)
                status_name = 'Successfully closed.';

			//$('#extend_invite_modal').modal('hide');
            var span_message = status_name;
            var type = 'success';
            notify(span_message, type);
            $('#frm_invitecreation')[0].reset(); // reset fields after success
            $('#cat_sup tbody').empty();
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
			
			//For Modal invite exntension
			/*if(reason_for_extension_note.length > 0){
				var type = 'modal_warning';
				modal_notify($('#extend_invite_modal'), span_message, type);
			}else{
				var type = 'warning';
				notify(span_message, type);
			}*/
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function auto_sub_cat(x_sub_category){
	var sub_category = $('#sub_category');
	if(sub_category.css('display') == "none"){
		sub_category.css('display','block');
	}
    
	var count = $('#cat_sup_count').val();
	count++;
	var new_count;
    
	var row = $('.bg-primary').closest('tr').clone().attr({'id':'tr_catsup'+count,'class':'cls_tr_cat'});
	var table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_cat"><span class="glyphicon glyphicon-trash"></span></button></td>');
	table_trash.closest('button').attr({'id':'category_id'+count, 'name':'category_id'+count});
	row.append(table_trash);
	var cat_id = row.find(':input').attr('value');
    
	row.removeClass("bg-primary").find(':hidden:not(td,span)').attr({'id':'category_id'+count, 'name':'category_id'+count});
    
    
	if (!$('#cat_sup tbody :input[value='+cat_id+']').length)
	{
		$('#cat_sup tbody').append(row);
		$('#cat_sup_count').val(count);
    
		$('#dept_cat').find('.bg-primary').remove();
		reset_ids('cls_tr_cat','cat_sup_count');
		reset_ids('cls_del_cat','cat_sup_count');
		new_count = $('#cat_sup_count').val();
		for (var i = 1; i <= new_count; i++)
		{
			reset_ids('cls_cat','cat_sup_count',i,'tr_catsup');
			reset_ids('cls_del_cat','cat_sup_count',i,'category_id');
		}
	}
	else
		notify('Category already exists!', 'danger');
    
	var xcat_id = null;
	for(var c = 0; c < row.length + (count - 1); c++){
		//counter = +count + +c;
		var cat_num = $('#category_id'+(c+1)).closest(':hidden');
		if(c == 0){
			xcat_id = $('#category_id'+(c+1)).val();
		}else{
			xcat_id = xcat_id + "," + $('#category_id'+(c+1)).val();
		}
	}
    
	var lastChar = xcat_id[xcat_id.length -1];
	if(lastChar == ','){
		xcat_id = xcat_id.substring(0, xcat_id.length -1);
	}
	
	temp = auto_get_sub_cat(xcat_id,x_sub_category);
}

function auto_get_sub_cat(category_ids,x_sub_cat){
	//console.log(x_sub_cat);
	var sub_category_checker = false;
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/get_sub_cat/";
    var post_params = "cat_id="+category_ids;

    var success_function = function(responseText)
    {
        var test = JSON.parse(responseText);
		//console.log(test);
        for(var counter = 0; counter < test.length; counter++){
            for( var i = 0; i < ar_source.length; i++){ 
                if(ar_source[i][1] != ''){
                    if(test[counter].SUB_CATEGORY_ID == ar_source[i][1]){
                        sub_category_checker = true;
                    }
                }
            }
            
            if(sub_category_checker === false){
				if(x_sub_cat.includes(test[counter].SUB_CATEGORY_NAME)){
					$('#dept_sub_cat tbody').append($('<tr id="hid_subcat'+test[counter].CATEGORY_ID+'" class="bg-success"><td><input type="hidden" class="cls_sub_cat" id="hid_subcat'+ counter +'" name="hid_subcat'+ counter +'" value="'+ test[counter].SUB_CATEGORY_ID +'|'+ test[counter].CATEGORY_ID +'"><span id="subcatname">'+ test[counter].SUB_CATEGORY_NAME +'</span></td></tr>'));	
				}else{
					$('#dept_sub_cat tbody').append($('<tr id="hid_subcat'+test[counter].CATEGORY_ID+'"><td><input type="hidden" class="cls_sub_cat" id="hid_subcat'+ counter +'" name="hid_subcat'+ counter +'" value="'+ test[counter].SUB_CATEGORY_ID +'|'+ test[counter].CATEGORY_ID +'"><span id="subcatname">'+ test[counter].SUB_CATEGORY_NAME +'</span></td></tr>'));
				}
                ar_source.push([test[counter].CATEGORY_ID,test[counter].SUB_CATEGORY_ID]);
            }
            
            sub_category_checker = false;
        }
	
		btn_sub_cat_move_right.click();
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function selectVendor(){
	var button = document.getElementsByName('rad_invite_type');
	for(var i = 0; i < button.length; i++){
		if(button[i].checked){
			invite_type = button[i].value;
		}
	}
	
	var vendor_name = document.getElementById('search_vendor').value;
	
	var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/get_vendor_info/";
    var post_params = "vendor_name="+vendor_name;
    
    var success_function = function(responseText)
    {
		var rs = $.parseJSON(responseText);
		if(rs[0].status == true){
			var span_message = "Vendor Found.";
			var type = "modal_success";
			
			if(invite_type == 4){
				var counter = rs.length;
				document.getElementById('txt_vendorname').value = rs[0].VENDOR_NAME;
				document.getElementById('txt_contact_person').value = rs[0].CONTACT_PERSON;
				document.getElementById('txt_email').value = rs[0].EMAIL;
				//document.getElementById('txt_file_path').value = rs[0].FILE_PATH;
				//document.getElementById('txt_approve_items').value = rs[0].ORIGINAL_FILE_NAME;
				//document.getElementById('txt_date_upload').value = rs[0].DATE_CREATED;
				$('#txt_vendor_code').val(rs[0].VENDOR_CODE);
				
				//var category_name = [];
				//var sub_category_name = [];
				//
				//var numberOfInputs = $("#dept_cat").find("span").length;
				//for(var x = 0; x < counter; x++){
				//	var category = rs[x].CATEGORY_NAME;
				//	var sub_category = rs[x].SUB_CATEGORY_NAME;
				//	
				//	if(!category_name.includes(category)){
				//		for (var i = 1; i <= numberOfInputs; i++){
				//			if($('#deptcatname'+i).text() == category){
				//				$('#deptcatname'+i).parents('td').parents('tr').addClass('bg-primary');
				//				break;
				//			}
				//		}
				//		category_name.push(category);
				//	}
				//	
				//	sub_category_name.push(sub_category);
				//}
				//
				//auto_sub_cat(sub_category_name);
				
				if (rs[0].TRADE_VENDOR_TYPE == 1){
					$('input[name=rad_trade_vendor_type][value=1]').prop("disabled",false);
					$('input[name=rad_trade_vendor_type][value=2]').prop("disabled",true);
					$('input[name=rad_trade_vendor_type][value=1]').prop("checked",true);
					$('input[name=rad_trade_vendor_type][value=2]').prop("checked",false);
				}else if(rs[0].TRADE_VENDOR_TYPE == 2){
					$('input[name=rad_trade_vendor_type][value=1]').prop("checked",false);
					$('input[name=rad_trade_vendor_type][value=2]').prop("checked",true);
					$('input[name=rad_trade_vendor_type][value=2]').prop("disabled",false);
					$('input[name=rad_trade_vendor_type][value=1]').prop("disabled",true);
				}
				
				$("#invite_id").val(rs[0].VENDOR_INVITE_ID);
				
			}else if(invite_type == 5){
				$("#source_invite_id").val(rs[0].VENDOR_INVITE_ID);
				document.getElementById('txt_vendorname').value = rs[0].VENDOR_NAME;
				document.getElementById('txt_vendor_code').value = rs[0].VENDOR_CODE;
				
				if (rs[0].TRADE_VENDOR_TYPE == 1){
					$('input[name=rad_trade_vendor_type][value=1]').prop("checked",true);
					$('input[name=rad_trade_vendor_type][value=2]').prop("checked",false);
					$('input[name=rad_trade_vendor_type][value=2]').prop("disabled",true);
				}else if(rs[0].TRADE_VENDOR_TYPE == 2){
					$('input[name=rad_trade_vendor_type][value=1]').prop("checked",false);
					$('input[name=rad_trade_vendor_type][value=2]').prop("checked",true);
					$('input[name=rad_trade_vendor_type][value=1]').prop("disabled",true);
				}
			}
			
            $('#myModal').modal('hide');
			return;
		}else{
			var span_message = "Vendor does not exist.";
			var type = "modal_danger";
		}
		modal_notify($('#myModal'), span_message, type);
		return;
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function r_invite_type(myRadioButton){
	$('#frm_invitecreation')[0].reset(); // reset fields after success
	$('#cat_sup_count').val(0);
	$('.cls_del_cat').click();
	$("#invite_id").val("");
	
	var button = document.getElementsByName('rad_invite_type');
	for(var i = 0; i < button.length; i++){
		if(button[i].value == myRadioButton.value){
			button[i].checked = true;
		}
	}
	
	if(myRadioButton.value != 1 && myRadioButton != 1){
		$("#txt_vendorname").attr('readonly','readonly');
		$("#btnSearchVendor").removeAttr('disabled');
		if(myRadioButton.value == 5){
			$("#txt_nvendorname").removeAttr("readonly");
			$("#txt_approver_note").removeAttr('readonly');
			$("#txt_approver_note").val("For Change in Company Name");
			$("#txt_approver_note").attr('readonly','readonly');
			//$('input[type=radio][name=rad_trade_vendor_type]').attr('disabled', false);
		}else{
			$("#txt_nvendorname").attr('readonly','readonly');
			$("#txt_approver_note").val("For Add Vendor Code");
			$("#txt_approver_note").attr('readonly','readonly');
			
			//$('input[type=radio][name=rad_trade_vendor_type]').attr('disabled', true);
			//$("#txt_approver_note").attr('readonly','readonly');
		}
	}else{
		$("#btnSearchVendor").attr('disabled','disabled');
		$("#txt_vendorname").removeAttr('readonly');
		$("#txt_approver_note").removeAttr('readonly');
		$("#txt_approver_note").val("");
	}
}

function resend_email(action, btn) // approve = 3, reject = 4
{
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/inviteapproval/resend_email/";
    var post_params = $('#frm_inviteapproval').serialize();
    post_params += "&action=" + action + "&remarks=" + encodeURIComponent($('#via_remarks').val()); // artion = 1 draft, 2 pending for approval, 3 approve, 4 reject 

    var success_function = function(responseText)
    {
       // console.log($.parseJSON(responseText));
       var rs = $.parseJSON(responseText);
	  // console.log(rs);
       // $('#test').html(responseText);
       if (rs.status == true)
       {
             var span_message; 

            if (action == 3)
                span_message = 'Invitation resend successfully.';
            if (action == 4)
            {
                span_message = 'Invitation resend rejected.';
                $('#myModal').modal('hide');
            }
           
            var type = 'success';
            notify(span_message, type);
            $('#frm_inviteapproval')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

// Added MSF - 20191105 (IJR-10612)
function update_email(btn)
{
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/inviteapproval/update_email/";
    var post_params = $('#frm_inviteapproval').serialize();

    var success_function = function(responseText)
    {
       var rs = $.parseJSON(responseText);
       if (rs.status == true){
            var span_message = 'email successfully updated.';
           
            var type = 'success';
            notify(span_message, type);
       }else{
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}


function via_approve_reject(action, btn) // approve = 3, reject = 4
{
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/inviteapproval/invite_process/";
    var post_params = $('#frm_inviteapproval').serialize();
    post_params += "&action=" + action + "&remarks=" + encodeURIComponent($('#via_remarks').val()) + "&vendorname=" + encodeURIComponent($('#spn_vendorname').html()); // artion = 1 draft, 2 pending for approval, 3 approve, 4 reject 
	post_params += '&cbo_tp=' + $("#cbo_tp").val();
	var success_function = function(responseText)
    {
       //console.log(responseText);
	   //return;
       var rs = $.parseJSON(responseText);
       // $('#test').html(responseText);


       
       if (rs.status == true)
       {
             var span_message; 

            if (action == 3)
                span_message = 'Invite successfully approved.';
            if (action == 4)
            {
                span_message = 'Invite successfully rejected.';
                $('#myModal').modal('hide');
            }
           
            var type = 'success';
            notify(span_message, type);
            $('#frm_inviteapproval')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}


function reset_ids(class_name, update_count, id_num = null, div_id = null)
{
    var count = 0
    if (div_id != null)
        div_id = '#'+div_id+id_num;
    else
        div_id = '';

    //console.log(div_id+' .'+class_name);
    $(div_id+' .'+class_name).each(function(i) {

        var id = $(this).attr('id');
        var name = $(this).attr('name');
       
        if (id_num != null)
        {
			if (id)
				id = id.replace(/\d+/g, id_num);
            if (name)
                name = name.replace(/\d+/g, id_num);
        }
        else
        {
			if (id)
				id = id.replace(/\d+/g, i+1);
            if (name)
                name = name.replace(/\d+/g, i+1);
        }

        $(this).attr({
            'id': id,
            'name': name

        });
        count++;
    });

    if (id_num == null)
    $('#'+update_count).val(count);
}

function reset_attr(attr_name, class_name, update_count, id_num = null, div_id = null)
{
    var count = 0
    if (div_id != null)
        div_id = '#'+div_id+id_num;
    else
        div_id = '';

    //console.log(div_id+' .'+class_name);
    $(div_id+' .'+class_name).each(function(i) {

        var attr = $(this).attr(attr_name);
       
        if (id_num != null)
        {
            if (attr)
                attr = attr.replace(/\d+/g, id_num);
        }
        else
        {
            if (attr)
                attr = attr.replace(/\d+/g, i+1);
        }

        $(this).attr(attr_name,attr);
        count++;
    });

    if (id_num == null)
    $('#'+update_count).val(count);
}

function upload_file(type, upload_button) // type = 1 Documents , 2 Agreements , 3 Approved Items [Added MSF - 20191105 (IJR-10617)]
{
	loading($(upload_button), 'in_progress');
	$(".btn_upload_no").attr("disabled","disabled");
	$("#btn_upload").attr("disabled","disabled");
    var surl = BASE_URL + "vendor/registration/upload_file/" + type;
	
	// Added MSF - 20191105 (IJR-10617)
	
    var frm_document;

    if(type===3){
        frm_document = document.frm_invitecreation;
    }else{
        frm_document = document.frm_registration;
    }
	
	// Modified MSF - 20191105 (IJR-10617)
	//upload_ajax_modal(document.frm_registration, surl).done(function(responseText) {
	upload_ajax_modal(frm_document, surl).done(function(responseText) {
        $('#upload_result').html(responseText);
        var type_name = ''
        if ($('#error').val() == '')
        {	
			modal_notify($("#myModal"), 'File uploaded successfully.', "modal_success");
			
            if (type == 1)
                type_name = 'rsd';
            else if (type == 2)
                type_name = 'ra';
			// Added MSF - 20191105 (IJR-10617)
            else if (type == 3)
                type_name = 'ai';
			else if (type == 4)
				type_name = 'ccn';

            var sysdate = $('#sysdate').data("rel");
			
			var x = new Date(sysdate);
			var dateStr =
				  x.getFullYear() + "-" + ("00" + (x.getMonth() + 1)).slice(-2) + "-" + ("00" + x.getDate()).slice(-2)
				   + " " +
				  ("00" + x.getHours()).slice(-2) + ":" + ("00" + x.getMinutes()).slice(-2) + ":" + ("00" + x.getSeconds()).slice(-2);

            $("#upload_date").val(dateStr);
            //console.log("upload_date " + $("#upload_date").val());

            var id      = $('#cbo_'+type_name+'_list').val();
            var date    = $('#upload_date').val();
            var file    = $('#file_path').val();
            var orgname = $('#orig_name').val().toString();
			var ext 	= orgname.slice((Math.max(0, orgname.lastIndexOf(".")) || Infinity) + 1);
			
			if(orgname.length > 170){
				
				var re = orgname.length - (ext.length + 1);
				if(re >= 170){
					re = 170;
				}
				orgname = orgname.slice(0,re) + "." + ext;
							
				if(orgname.length > 200){
					orgname = orgname.slice(0,201);
				}
			}
			
			// Added MSF - 20191105 (IJR-10617)
            if(type != 3){
				// add 1 to counter
				if (!$('#'+type_name+'_document_chk'+id).is(':checked'))
				{
					var count = +$('#'+type_name+'_upload_count').val() + 1;
					$('#'+type_name+'_upload_count').val(count);
				}
				$('#'+type_name+'_document_chk'+id).prop('checked', true);
				$('#'+type_name+'_date_upload'+id).val(date);
				$('#'+type_name+'_orig_name'+id).val(orgname);
				//alert(orgname);
				$('#btn_'+type_name+'_preview'+id).data('path',file);// set data-path of button
				$('#btn_'+type_name+'_preview'+id).prop('disabled', false);
				$('#btn_'+type_name+'_preview'+id).val(file);
				$('#'+type_name+'_document_chk'+id).prop('checked', true);
				//$('#fileupload').val('');// set value to empty for new upload
			// Added MSF - 20191105 (IJR-10617)
            }else{
                $('#txt_approve_items').val(orgname);
                $('#txt_file_path').val(file);
                $('#txt_date_upload').val(date);

                $('#btn_invite_view').data('path',file);
                $('#btn_invite_view').val(file);
            }
			
			setTimeout(function(){
				loading($(upload_button), 'done');
				$(".btn_upload_no").removeAttr("disabled");
				$("#btn_upload").removeAttr("disabled");
				$("#fileupload").removeAttr("disabled");
				$("#btn_upload_cancel").removeAttr("disabled");
				
			// Added MSF - 20191105 (IJR-10617)
				
				$('#myModal').modal('hide');
			}, 2200);
        }
        else
        {
			loading($(upload_button), 'done');
			
			$("#btn_upload").removeAttr("disabled");
			$("#fileupload").removeAttr("disabled");
			
			
			$("#btn_upload_no").removeAttr("disabled");
            modal_notify($("#myModal"), $('#error').val(), "modal_danger");
        }

    });
}

function save_registration_vendor(status,btn) // draf = 1, submit = 2
{
    // enable all first before saving
     loading($(btn), 'in_progress');
    disable_enable_frm('frm_registration', false);

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/registration/add_registration/";
	
	var cat_sup = [];
	$('#cat_sup input[type=hidden]').each(function(){
        cat_sup.push(this.value);
	});
	$("#cat_sup_count").val(cat_sup.length);
	
    var post_params = $('#frm_registration').serialize();

    let ocount =  $('#opd_count').val();
    let tocount = '';
    tocount = 'opd_count='+ocount;
    let tcount = 0;
    tcount = ocount;

    $('#tbl_opd tbody tr').each(function(){
      let f = $(this).attr('flop');
        let n = '';
        n = $('#opd_fname'+f).val() + $('#opd_mname'+f).val() + $('#opd_lname'+f).val();
		
        if(n.length == 0){

            let tfname = '&opd_fname'+f+'=';
            let tmname = '&opd_mname'+f+'=';
            let tlname = '&opd_lname'+f+'=';
            let tpos = '&opd_pos'+f+'=';


    /*      console.log(tfname);
            console.log(tmname);
            console.log(tlname);
    */
            //direct name replace not working idk why.
            let tpost = post_params;

            tpost = tpost.replace(tfname,"");
            tpost = tpost.replace(tmname,"");
            tpost = tpost.replace(tlname,"");
            tpost = tpost.replace(tpos,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;
        }

    })

    ocount = tcount;
    post_params = post_params.replace(tocount,"opd_count="+ocount+"");
//fax
    tcount = 0;
    ocount = $('#faxno_count').val();
    let faxnocount = "faxno_count="+ocount;
    tcount = ocount;

    $('#div_faxno .cls_div_faxinline').each(function(){
       let f = $(this).attr('fflop');
       let n = '';
        n = $('#fax_acode'+f).val() + $('#fax_no'+f).val() + $('#fax_ccode'+f).val()+ $('#fax_elno'+f).val();

        if(n.length == 0){
            let facode = '&fax_acode'+f+'=';
            let fno = '&fax_no'+f+'=';
            let fcode ='&fax_ccode'+f+'=';
            let felno = '&fax_elno'+f+'=';

            let tpost = post_params;

            tpost = tpost.replace(facode,"");
            tpost = tpost.replace(fno,"");
            tpost = tpost.replace(fcode,"");
            tpost = tpost.replace(felno,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;

        }
       
    })

    ocount = tcount;
    post_params = post_params.replace(faxnocount,"faxno_count="+ocount+"");
    //fmob

    tcount = 0;
    ocount = $('#mobno_count').val();
    let mobnocount = "mobno_count="+ocount;
    tcount = ocount;

    $('#div_mobno .cls_div_mobnoinline').each(function(){

        let f = $(this).attr('mflop');
        let n = '';
        n = $('#mobile_ccode'+f).val() + $('#mobile_acode'+f).val() + $('#mobile_no'+f).val();

        if(n.length == 0){
            let mcc = '&mobile_ccode'+f+'=';
            let mac = '&mobile_acode'+f+'=';
            let mno ='&mobile_no'+f+'=';


            let tpost = post_params;

            tpost = tpost.replace(mcc,"");
            tpost = tpost.replace(mac,"");
            tpost = tpost.replace(mno,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;
        }

    });

    ocount = tcount;
    post_params = post_params.replace(mobnocount,"mobno_count="+ocount+"");
    //bank refer
    tcount = 0;
    ocount = $('#bankrep_count').val();
    let bankrep_count = "bankrep_count="+ocount;
    tcount = ocount;

    $('#tbl_bankrep .cls_tr_bankrep').each(function(){

        let f = $(this).attr('bflop');
        let n = '';
        n = $('#bankrep_name'+f).val() + $('#bankrep_branch'+f).val();

        if(n.length == 0){
            let mcc = '&bankrep_name'+f+'=';
            let mac = '&bankrep_branch'+f+'=';

            let tpost = post_params;

            tpost = tpost.replace(mcc,"");
            tpost = tpost.replace(mac,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;
        }

    });

    ocount = tcount;
    post_params = post_params.replace(bankrep_count,"bankrep_count="+ocount+"");
    //other retail

    tcount = 0;
    ocount = $('#orcc_count').val();
    let orcc_count = "orcc_count="+ocount;
    tcount = ocount;

    $('#tbl_orcc .cls_tr_orcc').each(function(){

        let f = $(this).attr('orflop');
        //console.log(f);
        let n = '';
        n = $('#orcc_compname'+f).val();

        if(n.length == 0){
            let mcc = '&orcc_compname'+f+'=';
            let tpost = post_params;

            tpost = tpost.replace(mcc,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;
        }
    });

    ocount = tcount;
    post_params = post_params.replace(orcc_count,"orcc_count="+ocount+"");


//other business
    tcount = 0;
    ocount = $('#otherbusiness_count').val();
    let otherbusiness_count = "otherbusiness_count="+ocount;
    tcount = ocount;

    $('#tbl_otherbusiness .cls_tr_ob').each(function(){

        let f = $(this).attr('otbflop');
        let n = '';
        n = $('#ob_compname'+f).val() + $('#ob_pso'+f).val();      

        if(n.length == 0){
            let mcc = '&ob_compname'+f+'=';
            let mac = '&ob_pso'+f+'=';
            let tpost = post_params;

            tpost = tpost.replace(mcc,"");
            tpost = tpost.replace(mac,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;
        }
    });

    ocount = tcount;
    post_params = post_params.replace(otherbusiness_count,"otherbusiness_count="+ocount+"");


    //disclosure

    tcount = 0;
    ocount = $('#affiliates_count').val();
    let affiliates_count = "affiliates_count="+ocount;
    tcount = ocount;

    $('#tbl_affiliates .cls_tr_affiliates').each(function(){
       let f = $(this).attr('afflop');
       let n = '';
        n = $('#affiliates_fname'+f).val() + $('#affiliates_lname'+f).val() + $('#affiliates_pos'+f).val()+ $('#affiliates_comp_afw'+f).val()+ $('#affiliates_rel'+f).val();

        if(n.length == 0){
            let facode = '&affiliates_fname'+f+'=';
            let fno = '&affiliates_lname'+f+'=';
            let fcode ='&affiliates_pos'+f+'=';
            let felno = '&affiliates_comp_afw'+f+'=';
            let felp = '&affiliates_rel'+f+'=';

            let tpost = post_params;

            tpost = tpost.replace(facode,"");
            tpost = tpost.replace(fno,"");
            tpost = tpost.replace(fcode,"");
            tpost = tpost.replace(felno,"");
            tpost = tpost.replace(felp,"");
            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;

        }
       
    })

    ocount = tcount;
    post_params = post_params.replace(affiliates_count,"affiliates_count="+ocount+"");

    //auth rep

    tcount = 0;
    ocount = $('#authrep_count').val();
    let authrep_count = "authrep_count="+ocount;
    tcount = ocount;

    $('#tbl_authrep .cls_tr_authrep').each(function(){
       let f = $(this).attr('authflop');
       let n = '';
        n = $('#authrep_fname'+f).val() + $('#authrep_mname'+f).val() + $('#authrep_lname'+f).val()+ $('#authrep_pos'+f).val();

        if(n.length == 0){
            let facode = '&authrep_fname'+f+'=';
            let fno = '&authrep_mname'+f+'=';
            let fcode ='&authrep_lname'+f+'=';
            let felno = '&authrep_pos'+f+'=';

            let tpost = post_params;

            tpost = tpost.replace(facode,"");
            tpost = tpost.replace(fno,"");
            tpost = tpost.replace(fcode,"");
            tpost = tpost.replace(felno,"");

            post_params = '';
            post_params = tpost;
            tcount = tcount - 1;

        }
       
    })

    ocount = tcount;
    post_params = post_params.replace(authrep_count,"authrep_count="+ocount+"");


    post_params += "&status=" + status; // 1 draft, 2 pending for approval, 3 approve, 4 reject 

    $("table :button").each(function(){ // get value of buttons from table        
        if($(this).val())
            post_params += "&"+this.name+"="+$(this).val();
    });

    var success_function = function(responseText)
    {
       //console.log(responseText);
       //return;
       // $('#test').html(responseText);
       if (responseText == 1)
       {
            refresh_session();

            var ra_count = $('#ra_count').html();
            var ra_upload_count = $('#ra_upload_count').val();

            var status_name = '';
            if (status == 1)
                status_name = 'Data successfully saved.';
            else if (status == 2)
                status_name = 'Data successfully submitted.';

            var span_message = status_name;
            var type = 'success';
            notify(span_message, type);
            $('#frm_registration')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';

            if (status == 2 && ( ($('#status_id').val() > 9 && $('#status_id').val() != 11) || (ra_count == ra_upload_count) ) )  // submitted onwards pde na mag submit ng addtional //($('#status_id').val() == 190 || $('#status_id').val() == 195)) // lalabas lang pag for additional review na nisubmit // aditional req disable all exclude #additional req //190 = additional req, 195 = incomplete additional req
            {   
                $('#myModal').modal({
                    backdrop: 'static', 
                    keyboard: false
                });

                $('#myModal').modal('show');
                
                $('#myModal span').hide();
                $('.alert > span').show(); // dont include to hide these span
                $('#myModal .submit').show();

                $("#myModal .submit").find('button').click(function(){ 
                    $('#myModal').on('hidden.bs.modal', function () {
                        $main_container.html('').load(cache.set('refresh_path', action_path));
                    });
                });
            }
            else
            {  
                loading($(btn), 'done');              
                setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
            }
       }else if(responseText == -1){
            var span_message = "Something went wrong. Please try to reload the page or login again to submit.";
            var type = 'danger';
            notify(span_message, type);
            loading($(btn), 'done');
	   }else if(responseText == -2){
            var span_message = "Uploaded file does not exists. Please try again.";
            var type = 'danger';
            notify(span_message, type);
            loading($(btn), 'done');
	   }
       else 
       {
		    var is_json = false;
			var json_file = '';
		    try {
			  json_file = JSON.parse(responseText);
			  is_json =  (typeof json_file === 'object');
			} catch (e) {
				//console.log('failed to parse . ' + e);
				is_json = false;
			}
			
		    if(is_json){
				var files =  '';
				var count = Object.keys(json_file).length;
				for (x in json_file) {
					if(count == 1){
						files += json_file[x];
					}else{
						files += ' - ' + json_file[x] + '<br/>';
					}
				}
				

				var span_message = '';
				
				if(count == 1){
					//files = files.replace(/(^[,\s]+)|([,\s]+$)/g, ', ');
					span_message = "Something went wrong. Can't upload the file or the file is missing. Please try to reupload " + files + ".";
				}else {
					span_message = "Something went wrong. Can't upload the following files or the files are missing: <br/>" + files + '<br/><br/>Please try to reupload the files. ';
				}
				
				var type = 'danger';
				notify(span_message, type, true);
				loading($(btn), 'done');
			}else{						
				var span_message = responseText;
				var type = 'warning';
				notify(span_message, type);
				loading($(btn), 'done');
			}
       }
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function validate_approve_reject(type)
{
    var t = '';
    var x = 0;

    document.getElementById('num_click').value  = parseInt(document.getElementById('num_click').value) + 1;
    document.getElementById('last_click').value = type;
    if (type == 1)
    {
        x = 1;
        t = 'Approve';
    }
    else if (type == 2)
    {
        x = 2;
        t = 'Suspend';

        if (validateForm() == false)
        {
            var span_message = 'Suspend reason required.';
            var type = 'modal_danger';
            modal_notify("myModalreject" ,span_message, type);
            return;
        }
        $('#myModalreject').modal('toggle');  
    }
    else
    {
        x = 0;
        t = 'Reject';

        if (validateForm() == false)
        {
            var span_message = 'Reject reason required.';
            var type = 'modal_danger';
            modal_notify("myModalreject" ,span_message, type);
            return;
        }
        $('#myModalreject').modal('toggle');      
    }

    if(type == 1 || type == 2)
    {
        $('#cbo_tp').prop('disabled', false);
        
        if (document.getElementById('cbo_tp').value == '')
        {
            document.getElementById('cbo_tp').style.border = "1px solid #FF0000";
            var span_message = 'Please Fill Up Terms of Payment';
            var type = 'danger';
            notify(span_message, type);
            return;
        }
        /*else if(document.getElementById('note_hts'))
        {
            if(document.getElementById('note_hts').value == '')
            {
                document.getElementById('note_hts').style.border = "1px solid #FF0000";
                var span_message = 'Please Fill Up Note';
                var type = 'danger';
                notify(span_message, type);
                return;
            }
        }*/
        else
            document.getElementById('cbo_tp').style.border = "1px solid #ccc";
    }

    if(hasSpecChar() == false)
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        notify(span_message, type);
        return;   
    }
    
    var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" value="Yes" onclick="approve_reject_vendor_registration('+x+',this)">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function approve_reject_vendor_registration(type,btn)
{
	//console.log('here');
	//return;
    loading($(btn), 'in_progress');
 //   disable_enable_frm('frm_registration', false);
    var t = '';
    var action = '';
    action = type;

    if (type == 1)
        t = 'approved';
    else if (type == 2)
        t = 'suspended';
    else
        t = 'rejected';

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {


			//console.log(xmlhttp.responseText);
            //return;

            var span_message = 'Registration successfully '+t+' ';
            var type = 'success';
            notify(span_message, type);
            loading($(btn), 'done');

            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
            
            $('#btn_approval_approve').prop('disabled', true);
            $('#btn_approval_reject').prop('disabled', true);
            $('#btn_approval_suspend').prop('disabled', true);
        }
    }
    path_value = BASE_URL + 'vendor/registrationapproval/save_approval_process';

    parameter = 'vendor_id='+document.getElementById('vendor_id').value+
                '&invite_id='+document.getElementById('invite_id').value+
                '&action='+action+
                '&status_id='+document.getElementById('status_id').value+
                '&cbo_tp='+encodeURIComponent(document.getElementById('cbo_tp').value)+
                '&reject_remarks='+encodeURIComponent(document.getElementById('reject_remarks').value)+
                '&position_id='+document.getElementById('position_id').value+
				'&reg_type_id='+document.getElementById('reg_type_id').value;

    if(document.getElementById('note_hts'))
        parameter += '&note_hts='+encodeURIComponent(document.getElementById('note_hts').value);
    else
        parameter += '&note_hts=';

    if(document.getElementById('note_vrd'))
        parameter += '&note_vrd='+encodeURIComponent(document.getElementById('note_vrd').value);
    else
        parameter += '&note_vrd=';

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function save_registration_review(status, btn)
{
    loading($(btn), 'in_progress');
    // enable all first before saving
    disable_enable_frm('frm_registration_review', false);
	$(".incomplete button").attr("disabled","disabled");

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/registrationreview/review_registration/";
    var post_params = $('#frm_registration_review').serialize();
    post_params += "&status=" + status + '&invite_id=' + $("#invite_id").val(); // 1 draft, 2 pending for approval, 3 approve, 4 reject 

    var success_function = function(responseText)
    {
	   //console.log(responseText);
       //return;
       if (responseText == 1)
       {
            $('#myModal').modal('hide');

            var span_message = 'Success!'; //default msg.

            if (status == 3) // req visit review
                span_message = 'Request for visit successfully submitted.';
            else if (status == 4) // incomplete review
                span_message = 'Incomplete reason successfully submitted.';
            else if (status == 1) // save as draft validate
                span_message = 'Data successfully saved.';
            else if (status == 2) // submit validate
                span_message = 'Data successfully submitted.';

            var type = 'success';
            notify(span_message, type);
            $('#frm_registration_review')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
/*
        console.log(responseText);
        return;*/
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function save_incomplete_reason(btn)
{
    loading($(btn), 'in_progress');
    // enable all first before saving
    disable_enable_frm('frm_registration_review', false);

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/registrationreview/save_incomplete_reason/";
    var post_params = $('#frm_registration_review').serialize();
    post_params += "&status=" + status; // 1 draft, 2 pending for approval, 3 approve, 4 reject 

    var success_function = function(responseText)
    {
       // console.log(responseText);
       if (responseText == 1)
       {
            // $('#close_alert').click();
            $('.close').alert('close');
            $('#myModal').modal('hide');

            var span_message = 'Data successfully saved.';
            var type = 'success';
            notify(span_message, type);
            // $('#frm_registration_review')[0].reset(); // reset fields after success
            // var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            // setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
            var span_message = responseText;
            var type = 'warning';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function save_codeassign(btn)
{
    loading($(btn), 'in_progress');
  //  loading('#btn_yes','in_progress');
    // enable all first before saving
    disable_enable_frm('frm_code_assign', false);

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/codeassignment/save_codeassign/";
    var post_params = $('#frm_code_assign').serialize();

    var success_function = function(responseText)
    {       

       if (responseText == 1)
       {

            var span_message = 'Vendor Code successfully saved.';
            var type = 'success';
            notify(span_message, type);
            $('#frm_code_assign')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
           //  loading('#btn_yes','');
       }
       else
       {

            var span_message = responseText;
            var type = 'danger';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function zoomimage()
{
	$('#imagepreview').addClass('zoom_in');
}

function zoomoutimage()
{
    $('#imagepreview').removeClass('zoom_in');
}

function search_category(btn) // approve = 3, reject = 4
{
    // event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/search_category/";
    var post_params = $('#frm_invitecreation').serialize();
    post_params += "&search_cat="+encodeURIComponent($('#search_cat').val());

    var success_function = function(responseText)
    {
       // console.log(responseText);
       $('#dept_cat > tbody').html(responseText);
       loading($(btn), 'done');
    };

    return ajax_request(ajax_type, url, post_params, success_function);
}

function validate_primary()
{
    var isValid     = true;
    var addrname    = '';
    if ($('input[name=office_primary]:checked').length == 0)
    {
        isValid = false;
        addrname += ' Office Address';
    }
    if ($('input[name=factory_primary]:checked').length == 0)
    {
        isValid = false;
        addrname += ' Factory Address';
    }
    if ($('input[name=ware_primary]:checked').length == 0)
    {
        isValid = false;
        addrname += ' Warehouse Address';
    }

    if (!isValid)
    {
        var span_message = 'Please select primary'+addrname;
        var type = 'danger';
        notify(span_message, type);
        return isValid;
    }

    return isValid;
}

function reject_approval()
{

    if($('#num_click').val() != 0 && $('#last_click').val() != 0)
        document.getElementById('reject_remarks').value = '';

    $('#myModalreject').modal('show');
    $('#myModalreject span').hide();
    $('.alert > span').show(); // dont include to hide these span
    $('#myModalreject .reject_shortlist').show(); 
}


function suspend_approval()
{

    if($('#num_click').val() != 0 && $('#last_click').val() != 2)
        document.getElementById('reject_remarks').value = '';

    $('#myModalreject').modal('show');
    $('#myModalreject span').hide();
    $('.alert > span').show(); // dont include to hide these span
    $('#myModalreject .suspend').show(); 
}

function check_existing_record()
{
	var button = document.getElementsByName('rad_invite_type');
	for(var i = 0; i < button.length; i++){
		if(button[i].checked){
			invite_type = button[i].value;
		}
	}
	
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/validate_record/";
	if(invite_type != 5){
		var post_params = "vendorname="+encodeURIComponent($('#txt_vendorname').val().trim());
	}else{
		var post_params = "vendorname="+encodeURIComponent($('#txt_nvendorname').val().trim());	
	}
	
		post_params += "&invite_id=" + $('#invite_id').val();
        post_params += "&contact_person=" + encodeURIComponent($('#txt_contact_person').val().trim()) + "&email="+$('#txt_email').val().trim();
		// Added MSF - 20191108 (IJR-10617)
        post_params += "&approve_items=" + encodeURIComponent($('#txt_approve_items').val().trim());
        post_params += "&file_path=" + encodeURIComponent($('#txt_file_path').val().trim());
        post_params += "&date_upload=" + encodeURIComponent($('#txt_date_upload').val().trim());

    var success_function = function(responseText)
    {
        // console.log(responseText);
        //console.log($('#invite_id').val());

        if (responseText != '')
        {
            var span_message = responseText + ' already exists!';
            var type = 'danger';
            notify(span_message, type);
            if ($('#venname').val() == 1)
                $('#txt_vendorname').parent('div').addClass('has-error');    
            if ($('#venemail').val() == 1)
                $('#txt_email').parent('div').addClass('has-error');
            if ($('#vencontact').val() == 1)
                $('#txt_contact_person').parent('div').addClass('has-error');
            
            $('#findsimilar').click();
        }
       
       
    };

    return ajax_request(ajax_type, url, post_params, success_function);
}

function download_file(file_url)
{	
	if(file_url.length > 0){
		file_url = BASE_URL.replace('index.php/','') + file_url;
		$.get(file_url).done(function() { 
			// exists code 
			//alert("Does exists");
			
			var get_name = file_url.split('/');
			var filename = get_name[get_name.length-1];

			var link = document.createElement("a");
			link.download = filename;
			link.href = file_url;
			link.click();
		}).fail(function() { 
			// not exists code
			//alert("Does not exists");
			var span_message = 'File does not exists.';
			var type = 'danger';
			notify(span_message, type);
		})
	}else{
		//alert("Does not exists");
		var span_message = 'File does not exists.';
		var type = 'danger';
		notify(span_message, type);
	}
}

function submit_review(btn)
{
    loading($(btn), 'in_progress');
    // enable all first before saving
    disable_enable_frm('frm_registration_review', false);

    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/registrationreview/submit_review/";
    var post_params = $('#frm_registration_review').serialize();
	post_params += '&invite_id=' + $("#invite_id").val();

    var success_function = function(responseText)
    {
        //console.log(responseText);
		//return;
       if (responseText == 1)
       {
            var span_message = 'Request for approval successfully submitted.';
            var type = 'success';
            notify(span_message, type);
            $('#frm_registration_review')[0].reset(); // reset fields after success
            var action_path = BASE_URL + 'vendor/registration/registrationmain/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
       }
       else
       {
            var span_message = responseText;
            var type = 'danger';
            notify(span_message, type);
       }
       loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function check_status()
{
    // submitted onwards pde na mag submit ng addtional
    if ($('#status_id').val() > 9 && $('#status_id').val() != 11 && $('#status_id').val() != 19) // ($('#status_id').val() == 190 || $('#status_id').val() == 195) // aditional req disable all exclude #additional req //190 = additional req, 195 = incomplete additional req
    {
        //$('.form_container :input').not('#addtional_req :input,#myModal :input, #chk_certify, [name="business_asset"], [name="no_of_employee"]').attr('disabled', true);
		$('.form_container :input').not('#addtional_req :input,#myModal :input, #chk_certify').attr('disabled', true);
    }
}


$('#activate_registrations').on('click', function(e){

    if($('#numselected').val() == 0)
    {
        var span_message = 'Please select record to activate.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    var span_message = 'Are you sure you want to activate the login account? <button type="button" class="btn btn-success" onclick="activate_selected(this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
    var type = 'info';
    notify(span_message, type, true);
});

$('#approval_registrations').on('click', function(e){

    if($('#numselected').val() == 0)
    {
        var span_message = 'Please select record to activate.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

   // console.log(this);

    var span_message = 'Are you sure you want to activate the login account? <button type="button" class="btn btn-success" onclick="approve_selected(this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
    var type = 'info';
    notify(span_message, type, true);
	e.stopImmediatePropagation();
});

function activate_selected(btn)
{
    // event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "maintenance/registration_activation/activate_selected/";
    var post_params = 'numselected='+$('#numselected').val();

    for(var i = 1, j = 1; i <= $('#total_results').val(); i++)
    {
        if($('#vendorchecked'+i).val() == 1)
        {
            post_params += "&vendorinviteid"+j+"="+$('#vendorinviteid'+i).val();
            j++;
        }
    }

    var success_function = function(responseText)
    {

        //console.log(responseText);
        //return;

        var span_message = 'Vendor login re-activation is successfully requested.';
        var type = 'success';
        notify(span_message, type);
        
        var action_path = BASE_URL + 'maintenance/registration_activation/index/';
        setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
        loading($(btn), 'done');
    };

    return ajax_request(ajax_type, url, post_params, success_function);
}

function approve_selected(btn)
{
    // event.stopPropagation? event.stopPropagation() : event.cancelBubble = true;
    loading($(btn), 'in_progress'); // loading
    var ajax_type = 'POST';
    var url = BASE_URL + "maintenance/registration_activation/approve_selected/";
    var post_params = 'numselected='+$('#numselected').val();

    for(var i = 1, j = 1; i <= $('#total_results').val(); i++)
    {
        if($('#vendorchecked'+i).val() == 1)
        {
            post_params += "&vendorinviteid"+j+"="+$('#vendorinviteid'+i).val();
            j++;
        }
    }


    var success_function = function(responseText)
    {
		//console.log((responseText));

        //return;
        var span_message = 'Vendor login successfully activated.';
        var type = 'success';
        notify(span_message, type);
        
        var action_path = BASE_URL + 'maintenance/registration_activation/approval/';
        setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
        loading($(btn), 'done');
    };

    return ajax_request(ajax_type, url, post_params, success_function);
}

$('#btn_clear_rmain').on('click', function(){
    
    var type = $('#default_vendor_type').val();
    
    // $('input:radio[name=vendor_type_filter][value="'+type+'"]').click();
    if($('#vendor_type_filter'))
        $('#vendor_type_filter').val(''); //default select

    if($('#txt_tinno'))
        $('#txt_tinno').val('');
        
    if($('#txt_vendorname'))
        $('#txt_vendorname').val('');

    if($('#txt_auth_rep'))
        $('#txt_auth_rep').val('');

    if($('#cbo_brand'))
        $('#cbo_brand').val('');

    if($('#cbo_brand_text'))
        $('#cbo_brand_text').val('');

    if($('#cbo_status'))
        $('#cbo_status').val(''); 

        get_main_tbl();
});

function save_temp(inv_id){


    var span_message = 'Are you sure you want to update payment terms? <button type="button" class="btn btn-success" onclick="save_temp_yes(' + inv_id + ')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
    var type = 'info';
    notify(span_message, type, true);
   // loading($('#btn_save_terms'), 'in_progress');



}

function save_temp_yes(inv_id)
{
       let t_op = $('#cbo_tp').val();
        $.ajax({
            type:'POST',
            data:{inv_id: inv_id,t_op: t_op},
            url: BASE_URL + "maintenance/registration_activation/set_termsofpayment/",
            success: function(result){              
                 notify('<strong>Success </strong>Terms of Payment changed successful!', 'success');
              //  loading($('#btn_save_terms'), '');
                return;
            }   
            }).fail(function(){
                
                return;
            }); 

}

// Added MSF - 20191118 (IJR-10618)

var ar_category_id = [];
var ar_sub_category_id = [];
var ar_source = [];

function get_sub_category(category_ids){
    var sub_category_checker = false;
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/get_sub_cat/";
    var post_params = "cat_id="+category_ids;

    var success_function = function(responseText)
    {
        var test = JSON.parse(responseText);
        for(var counter = 0; counter < test.length; counter++){
            for( var i = 0; i < ar_source.length; i++){ 
                if(ar_source[i][1] != ''){
                    if(test[counter].SUB_CATEGORY_ID == ar_source[i][1]){
                        sub_category_checker = true;
                    }
                }
            }
            
            if(sub_category_checker === false){
                $('#dept_sub_cat tbody').append($('<tr id="hid_subcat'+test[counter].CATEGORY_ID+'"><td><input type="hidden" class="cls_sub_cat" id="hid_subcat'+ counter +'" name="hid_subcat'+ counter +'" value="'+ test[counter].SUB_CATEGORY_ID +'|'+ test[counter].CATEGORY_ID +'"><span id="subcatname">'+ test[counter].SUB_CATEGORY_NAME +'</span></td></tr>'));
                ar_source.push([test[counter].CATEGORY_ID,test[counter].SUB_CATEGORY_ID]);
            }
            
            sub_category_checker = false;
        }
    };    

    ajax_request(ajax_type, url, post_params, success_function);
}

$(document).ready(function() {

    $('#dept_cat').on('click', 'tbody tr', function(event) {
        if ($('#dept_cat td').html() != 'No Records found.')
            $(this).toggleClass('bg-primary'); //.siblings().removeClass('bg-primary');
    });

	// Added MSF - 20191118 (IJR-10618)
    $('#dept_sub_cat').on('click', 'tbody tr', function(event) {
        if ($('#dept_sub_cat td').html() != 'No Records found.')
            $(this).toggleClass('bg-success');
    });

	/* Modified MSF - 20191118 (IJR-10618)
    $("#btn_move_right").on('click', function (e) {
     
        var count = $('#cat_sup_count').val();
        count++;
        var new_count;

        var row = $('.bg-primary').closest('tr').clone().attr({'id':'tr_catsup'+count,'class':'cls_tr_cat'});
        var table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_cat"><span class="glyphicon glyphicon-trash"></span></button></td>');
        row.append(table_trash);
        var cat_id = row.find(':input').attr('value');
        row.removeClass("bg-primary").find(':hidden:not(td,span)').attr({'id':'category_id'+count, 'name':'category_id'+count});

        if (!$('#cat_sup tbody :input[value='+cat_id+']').length)
        {
            
            $('#cat_sup tbody').append(row);
            $('#cat_sup_count').val(count);

            $('#dept_cat').find('.bg-primary').remove();
            reset_ids('cls_tr_cat','cat_sup_count');
            new_count = $('#cat_sup_count').val();
            for (var i = 1; i <= new_count; i++)
            {
                reset_ids('cls_cat','cat_sup_count',i,'tr_catsup');
            }
        }
        else
            notify('Category already exists!', 'danger');
        
		// Added MSF - 20191118 (IJR-10618)
        var xcat_id = null;
        for(var c = 0; c < row.length + (count - 1); c++){
            //counter = +count + +c;
            var cat_num = $('#category_id'+(c+1)).closest(':hidden');
            if(c == 0){
                xcat_id = $('#category_id'+(c+1)).val();
            }else{
                xcat_id = xcat_id + "," + $('#category_id'+(c+1)).val();
            }
        }

        var lastChar = xcat_id[xcat_id.length -1];
        if(lastChar == ','){
            xcat_id = xcat_id.substring(0, xcat_id.length -1);
        }
        
        temp = get_sub_category(xcat_id);
    });
	*/
	$("#btn_move_right").on('click', function (e) {
        var sub_category = $('#sub_category');
        if(sub_category.css('display') == "none"){
            sub_category.css('display','block');
        }
     
        var count = $('#cat_sup_count').val();
        count++;
        var new_count;

        var row = $('.bg-primary').closest('tr').clone().attr({'id':'tr_catsup'+count,'class':'cls_tr_cat'});
        var table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_cat"><span class="glyphicon glyphicon-trash"></span></button></td>');
        table_trash.closest('button').attr({'id':'category_id'+count, 'name':'category_id'+count});
        row.append(table_trash);
        var cat_id = row.find(':input').attr('value');

        row.removeClass("bg-primary").find(':hidden:not(td,span)').attr({'id':'category_id'+count, 'name':'category_id'+count});


        if (!$('#cat_sup tbody :input[value='+cat_id+']').length)
        {
            $('#cat_sup tbody').append(row);
            $('#cat_sup_count').val(count);

            $('#dept_cat').find('.bg-primary').remove();
            reset_ids('cls_tr_cat','cat_sup_count');
            reset_ids('cls_del_cat','cat_sup_count');
            new_count = $('#cat_sup_count').val();
            for (var i = 1; i <= new_count; i++)
            {
                reset_ids('cls_cat','cat_sup_count',i,'tr_catsup');
                reset_ids('cls_del_cat','cat_sup_count',i,'category_id');
            }
        }
        else
            notify('Category already exists!', 'danger');

        var xcat_id = null;
        for(var c = 0; c < row.length + (count - 1); c++){
            //counter = +count + +c;
            var cat_num = $('#category_id'+(c+1)).closest(':hidden');
            if(c == 0){
                xcat_id = $('#category_id'+(c+1)).val();
            }else{
                xcat_id = xcat_id + "," + $('#category_id'+(c+1)).val();
            }
        }

        var lastChar = xcat_id[xcat_id.length -1];
        if(lastChar == ','){
            xcat_id = xcat_id.substring(0, xcat_id.length -1);
        }
        
        temp = get_sub_category(xcat_id);
    });

    /* Modified 20191118 (IJR-10618)
	$('#cat_sup').on('click', '.cls_del_cat', function(){
        if ($('#dept_cat  td').html() == 'No Records found.')
             $('#dept_cat tr:last').remove();

        var count = $('#cat_sup_count').val();

        var backrow = $(this).closest('tr').clone().attr({'id':'','class':''});
        backrow.find(':hidden:not(span)').attr({'id':'hid_deptcat1', 'name':'hid_deptcat1'});
        backrow.find('td:nth-child(2)').remove();
        $('#dept_cat tbody').append(backrow);
        // if (count > 1)
        // {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_cat','cat_sup_count'); // reset id of tr        
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_cat','cat_sup_count',i,'tr_catsup');
            }
        // }
    });
	*/
	$('#cat_sup').on('click', '.cls_del_cat', function(){
        if ($('#dept_cat  td').html() == 'No Records found.')
             $('#dept_cat tr:last').remove();

        var count = $('#cat_sup_count').val();

        var sub_category = $('#sub_category');
        if(count == 1){
            sub_category.css('display','none');
        }
		
        sub_new_count = $('#sub_cat_sup_count').val();
        temp_sub_count = $('#sub_cat_sup_count').val();
		
		var temp_i = ar_source.length;
		var i = 0;
		var category = $(this)[0].id;
		var hid_subcat = 'hid_subcat' + $('#'+category).val();
		while(temp_i--){

			if($('#'+category).val() == ar_source[i][0]){
				ar_source.splice(i,1);
				i--;
			}

			$('#'+hid_subcat).remove();
			i++;
		}


		for (var i = 1; i <= sub_new_count; i++)
		{
			var sub_cat_value = ($('#sub_category_id'+i).val()).split("|");
			if(sub_cat_value[1] == $('#'+category).val()){
				temp_sub_count = temp_sub_count - 1;
				$('#tr_sub_catsup'+i).remove();
				$('#sub_cat_sup_count').val(temp_sub_count);  
			}

		}

		reset_ids('cls_tr_sub_cat','sub_cat_sup_count');

		sub_new_count = $('#sub_cat_sup_count').val();
		for (var i = 1; i <= sub_new_count; i++)
		{
			reset_ids('cls_sub_cat','sub_cat_sup_count',i,'tr_sub_catsup');
		}

        var backrow = $(this).closest('tr').clone().attr({'id':'','class':''});
        backrow.find(':hidden:not(span)').attr({'id':'hid_deptcat1', 'name':'hid_deptcat1'});
        backrow.find('td:nth-child(2)').remove();
        $('#dept_cat tbody').append(backrow);
        // if (count > 1)
        // {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_cat','cat_sup_count'); // reset id of tr     
            reset_ids('cls_del_cat','cat_sup_count');   
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_cat','cat_sup_count',i,'tr_catsup');
                reset_ids('cls_del_cat','cat_sup_count',i,'category_id');
            }
        // }
    });
	
	// Added MSF - 20191118 (IJR-10618)
	$("#btn_sub_cat_move_right").on('click', function (e) {
        var second_count = $('#sub_cat_sup_count').val();
        second_count++;
        var sub_new_count;

        var row = $('.bg-success').closest('tr').clone().attr({'id':'tr_sub_catsup'+second_count,'class':'cls_tr_sub_cat'});
        var table_trash = $('<td><button type="button" class="btn btn-default btn-xs cls_del_sub_cat"><span class="glyphicon glyphicon-trash"></span></button></td>');
        row.append(table_trash);
        var cat_id = row.find(':input').attr('value');
        row.removeClass("bg-success").find(':hidden:not(td,span)').attr({'id':'sub_category_id'+second_count, 'name':'sub_category_id'+second_count});

        $('#sub_cat_sup tbody').append(row);
        $('#sub_cat_sup_count').val(second_count);

        $('#dept_sub_cat').find('.bg-success').remove();
        
        reset_ids('cls_tr_sub_cat','sub_cat_sup_count');
        sub_new_count = $('#sub_cat_sup_count').val();
        for (var i = 1; i <= sub_new_count; i++)
        {
            reset_ids('cls_sub_cat','sub_cat_sup_count',i,'tr_sub_catsup');
        }
    });

	// Added MSF - 20191118 (IJR-10618)
    $('#sub_cat_sup').on('click', '.cls_del_sub_cat', function(){
        if ($('#dept_sub_cat td').html() == 'No Records found.')
             $('#dept_sub_cat tr:last').remove();
        
        var sub_new_count = $('#sub_cat_sup_count').val();

        var sub_category = $(this)[0].id;
        var sub_cat_val = ($('#'+sub_category).val()).split("|");

        var backrow = $(this).closest('tr').clone().attr({'id':'hid_subcat'+sub_cat_val[1],'class':''});
        backrow.find(':hidden:not(td)').attr({'id':'hid_subcat'+sub_cat_val[1], 'name':'hid_subcat'+sub_cat_val[1]});
        backrow.find('td:nth-child(2)').remove();
        $('#dept_sub_cat tbody').append(backrow);
        $(this).closest('tr').remove(); 

        $('#sub_cat_sup_count').val(sub_new_count - 1);
        
        reset_ids('cls_tr_sub_cat','sub_cat_sup_count');
        sub_new_count = $('#sub_cat_sup_count').val();
        for (var i = 1; i <= sub_new_count; i++)
        {
            reset_ids('cls_sub_cat','sub_cat_sup_count',i,'tr_sub_catsup');
            reset_ids('cls_sub_cat','sub_cat_sup_count',i,'sub_category_id');
        }
    });
    $('#btn_inc').on('click', function() {
		$('#myModal').modal({
			backdrop: 'static',
			keyboard: false
		});
		$(".incomplete button").removeAttr("disabled");
		
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .incomplete').show();
		var rsd = $("input[id^=waive_rsd_document_chk]:checked");
		var rsd_length = rsd.length;
		
		if(rsd_length > 0){
			rsd.each(function(ii, obj){
				//console.log($("select option[value='"+ $(obj).val() + "|1']"));
				if($("input[id=waive_rsd_document_chk" +  $(obj).val() +"]:checked").length > 0){
					$("select option[value='"+ $(obj).val() + "|1']").css("display","none");
				}else{
					$("select option[value='"+ $(obj).val() + "|1']").css("display","block");
				}
			});
		}
		var rsd = $("input[id^=waive_rsd_document_chk]");
		rsd.each(function(ii, obj){
			if($("input[id=waive_rsd_document_chk" +  $(obj).val() +"]:checked").length > 0){
				$("select option[value='"+ $(obj).val() + "|1']").css("display","none");
			}else{
				$("select option[value='"+ $(obj).val() + "|1']").css("display","block");
			}
		});
		
		var ad= $("input[id^=waive_ad_document_chk]:checked");
		var ad_length = ad.length;
		
		if(ad_length > 0){
			ad.each(function(ii, obj){
				//console.log($("select option[value='"+ $(obj).val() + "|2']"));
				if($("input[id=waive_ad_document_chk" +  $(obj).val() +"]:checked").length > 0){
					$("select option[value='"+ $(obj).val() + "|2']").css("display","none");
				}else{
					$("select option[value='"+ $(obj).val() + "|2']").css("display","block");
				}
			});
		}
	
		var ad = $("input[id^=waive_ad_document_chk]");
		ad.each(function(ii, obj){
			if($("input[id=waive_ad_document_chk" +  $(obj).val() +"]:checked").length > 0){
				$("select option[value='"+ $(obj).val() + "|2']").css("display","none");
			}else{
				$("select option[value='"+ $(obj).val() + "|2']").css("display","block");
			}
		});
    });

    $('#btn_rf_visit').on('click', function() {
		var sid_temp = parseInt($("#status_id").val());
		var checked_rsd = $("input[id^=waive_ad_document_chk]:checked").length;
		//alert(checked_rsd);
		//194 Review Additional documents
		//198 Request For Visit
		if(sid_temp == 194 || sid_temp == 198){
			if(checked_rsd > 0 && $("#ad_waive_remarks").val().trim().length <= 0){
				var span_message = 'Additional Requirement waive remarks is required.';
                var type = 'danger';
                notify(span_message, type);
				$("#ad_waive_remarks").css("border-color","#ec6565");
				return;
			}else{
				$("#ad_waive_remarks").css("border-color","");
			}
		}
        $('.close').alert('close'); // close alert if it has
        $('#myModal').modal({
                backdrop: 'static', 
                keyboard: false
            });
        
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .req_visit').show();

        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth()+1; //January is 0!
        var yyyy = today.getFullYear();
         if(dd<10){
                dd='0'+dd
            } 
            if(mm<10){
                mm='0'+mm
            } 

        today = yyyy+'-'+mm+'-'+dd;
        // document.getElementById("datefield").setAttribute("max", today);
        $('#rv_txt_from').prop('min', today);
        $('#rv_txt_to').prop('min', today);
    });

    $('#btn_add_reginv_template').on('click', function() {

        var saved = true;

        if (saved)
        {
            $('#myModal').modal('show');
            
            $('#myModal span').hide();
            $('.alert > span').show(); // dont include to hide these span
            $('#myModal .add_reginv_template').show();
        }
    });

   //####################################################### Vendor Registration Invite  START

    $('#cbo_msg_template').on('change', function(){
        var message = $('#cbo_msg_template option:selected').data('messageTemplate');

        $('#txt_template_msg').val(message);
    });

    $('#vri_submit_approval').on('click', function(){
		$('#txt_email').val($('#txt_email').val().trim());
        if (!validateForm())
        {
          var span_message = 'Please fill up all fields!';
          var type = 'danger';
          notify(span_message, type);
          return;
        }
        else
        {	
            if (!hasSpecChar())
            {
                var span_message = 'Only alphanumeric characters allowed!';
                var type = 'danger';
                notify(span_message, type);
                return;
            }

            var cat_count = $('#cat_sup_count').val();
            if (cat_count == 0)
            {
                var span_message = 'Categories Supplied must not be empty!';
                var type = 'danger';
                notify(span_message, type);
                return;
            }

            if (!isEmail())
            {
                var span_message = 'Invalid email format!';
                var type = 'danger';
                notify(span_message, type);
                return;  
            }

            check_existing_record().done(function(message){

                if (message != '')
                    return;
                disable_enable_frm('frm_invitecreation', true);
                var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_reg_invite(2,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
                var type = 'info';
                notify(span_message, type, true);

            });

            
        }

        // $('#frm_registration input').each(function(){
        //     if($(this).val().length == 0){          
        //         $('#'+ this.id).parent('div').addClass('has-error');    
        //         iCtr++;
        //     }   
        // }); 
    

    });

    $('#vri_draftsave').on('click', function(){
		if($("input[type='radio'][name='rad_invite_type']:checked").val() == 5){
			if ($('#txt_nvendorname').val().trim() == '')
			{
				var span_message = '<strong>New Vendor Name</strong> must not be empty!';
				var type = 'danger';
				notify(span_message, type);
				$('#txt_nvendorname').parent('div').addClass('has-error');
				return;
			}
		}

        if ($('#txt_vendorname').val().trim() == '')
        {
            var span_message = '<strong>Vendor Name</strong> must not be empty!';
            var type = 'danger';
            notify(span_message, type);
            $('#txt_vendorname').parent('div').addClass('has-error');
        }
        else
        {
            if (!hasSpecChar())
            {
                var span_message = 'Only alphanumeric characters allowed!';
                var type = 'danger';
                notify(span_message, type);
                return;
            }
            
            if (!isEmail())
            {
                var span_message = 'Invalid email format!';
                var type = 'danger';
                notify(span_message, type);
                return;  
            }
            
            check_existing_record().done(function(message){

            if (message != '')
                return;
        

            disable_enable_frm('frm_invitecreation', true);
            $('#txt_vendorname').parent('div').removeClass('has-error');
            var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_reg_invite(1,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
            var type = 'info';
            notify(span_message, type, true);

            });
        }
        
    });

    //####################################################### Vendor Registration Invite  END
    //####################################################### Vendor Registration EXPIRED  START
    $('#vri_extend_invite').on('click', function(){
	
		// For modal
		/*
		if ($("#vi_reason_for_extension_remarks").val().trim().length <= 0)
		{
			var span_message = 'Please fill up reason for extension!';
			var type = 'danger';
			modal_notify($("#extend_invite_modal"), span_message, type);
			return;
		}
		var span_message = 'Are you sure you want to extend invite? <button type="button" class="btn btn-success" onclick="save_reg_invite(5,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
		var type = 'modal_info';
		modal_notify($("#extend_invite_modal"), span_message, type, true);*/
		
		if ($("#vi_reason_for_extension_remarks").val().trim().length <= 0)
		{
			var span_message = 'Please fill up reason for extension!';
			var type = 'danger';
			notify(span_message, type);
			return;
		}
		var span_message = 'Are you sure you want to extend invite? <button type="button" class="btn btn-success" onclick="save_reg_invite(5,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
		var type = 'info';
		notify( span_message, type, true);

    });

    $('#vri_close_invite').on('click', function(){

        if ($('#txt_vendorname').val() == '')
        {
            var span_message = '<strong>Vendor Name</strong> must not be empty!';
            var type = 'danger';
            notify(span_message, type);
            $('#txt_vendorname').parent('div').addClass('has-error');
        }
        else
        {
            if (!hasSpecChar())
            {
                var span_message = 'Only alphanumeric characters allowed!';
                var type = 'danger';
                notify(span_message, type);
                return;
            }
            
            if (!isEmail())
            {
                var span_message = 'Invalid email format!';
                var type = 'danger';
                notify(span_message, type);
                return;  
            }
            
            check_existing_record().done(function(message){

            if (message != '')
                return;
        

            disable_enable_frm('frm_invitecreation', true);
            $('#txt_vendorname').parent('div').removeClass('has-error');
            var span_message = 'Are you sure you want to close invite? <button type="button" class="btn btn-success" onclick="save_reg_invite(6,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
            var type = 'info';
            notify(span_message, type, true);

            });
        }
        
    });
    //####################################################### Vendor Registration EXPIRED  END
    //####################################################### Vendor Registration MAIN  Start
    $('#btn_search_rm').on('click', function(e){


        get_main_tbl();

        e.stopImmediatePropagation();
    });

    // $('#main_table').on('click', '.cls_action', function(){        
    //     var action_path = BASE_URL + $(this).data('actionPath') + '/index/' + $(this).data('inviteId');
    //     $main_container.html('').load(action_path);
    // });
    //####################################################### Vendor Registration MAIN  END

    //####################################################### Vendor Invite Approval START
    $('#via_approve').on('click', function(){
        var span_message = 'Are you sure you want to approve? <button type="button" class="btn btn-success" onclick="via_approve_reject(3, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);        
    });
	
	$('#resend_email').on('click', function(){
        var span_message = 'Are you sure you want to resend an email invitation? <button type="button" class="btn btn-success" onclick="resend_email(3, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);        
    });
	
	// Added MSF - 20191105 (IJR-10612)
	$('#update_email').on('click', function(){
        var span_message = 'Are you sure you want to update vendor\'s email? <button type="button" class="btn btn-success" onclick="update_email(this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);        
    });
	
    $('#via_reject_m').on('click', function(){

        if ($('#via_remarks').val().trim() == '')
        {
            modal_notify($("#myModal"),'Remarks must not be empty!', 'modal_danger');
            return;
        }

        if (!hasSpecChar())
        {
            var span_message = 'Only alphanumeric characters allowed!';           
            modal_notify($("#myModal"),span_message, 'modal_danger');
            return;
        }
        
        var span_message = 'Are you sure you want to reject? <button type="button" class="btn btn-success" onclick="via_approve_reject(4, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'modal_info';
        modal_notify($("#myModal"),span_message, type, true);
        
    });

    $('#via_reject').on('click', function(){
        $('#myModal').modal('show');

         $('#myModal span').hide();
         $('.alert > span').show(); // dont include to hide these span
         $('#myModal .via_reject').show();
    });

    var history_template = $('#history_template').html();
    $('#vi_approval_history').on('click', function() {

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .vi_approval_history').show();

        var ajax_type = 'POST';
        var url = BASE_URL + "vendor/inviteapproval/history_table/" + document.getElementById('invite_id').value + "/";
        var post_params; // 1 draft, 2 pending for approval, 3 approve, 4 reject 

        var success_function = function(responseText)
        {
            var tbl_data = $.parseJSON(responseText);

            var DATA = {
                table_history: tbl_data.query
            }

            $('#tbl_history_body').html(Mustache.render(history_template, DATA));

           // $('#tbl_pag').html(responseText);
        };

        ajax_request(ajax_type, url, post_params, success_function);
    });

    var rev_history_template = $('#rev_history_template').html();
    $('#vi_revision_history').on('click', function() {

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .vi_revision_history').show();

        var ajax_type = 'POST';
        var url = BASE_URL + "vendor/inviteapproval/rev_history_table/" + document.getElementById('vendor_id').value + "/";
        var post_params; // 1 draft, 2 pending for approval, 3 approve, 4 reject 

        var success_function = function(responseText)
        {
            var tbl_data = $.parseJSON(responseText);

            var DATA = {
                rev_table_history: tbl_data.query
            }

            $('#rev_tbl_history_body').html(Mustache.render(rev_history_template, DATA));

           // $('#tbl_pag').html(responseText);
        };

        ajax_request(ajax_type, url, post_params, success_function);
    });
    

    //####################################################### Vendor Invite Approval END

    //####################################################### Vendor Registartion START



    $('#btn_accept_dpa').on('click', function(){
        loading($(this), 'in_progress');

        var ajax_type = 'POST';
        var url = BASE_URL + "vendor/registration/accept_dpa/";
        var post_params = "invite_id="+$('#invite_id').val();

        var success_function = function(responseText)
        {
           // console.log(responseText);
           var rs = $.parseJSON(responseText);
           // $('#test').html(responseText);
           if (rs.status == true)
           {
                loading($(this), 'done');
                $('#myModal').modal('hide');				
				$('#myModal > .modal-dialog').removeClass("modal-lg");
           }
        };

        ajax_request(ajax_type, url, post_params, success_function);
    });

    var brand_template = $('#div_brandid1'); // .closest('div');

    $('#btn_add_brand').off().on('click', function(){
        var count       = $('#brand_count').val();
        var max_count   = $('#brand_max_count').val();
        count++;

        if (count <= max_count)
        {
            var new_div = brand_template.clone().attr({'id':'div_brandid'+count, 'name':'div_brandid'+count, 'maxlength': '50'}).removeClass('has-error');
            $('#div_brand').append(new_div).find('#div_brandid'+count+' :input').val('');
            reset_ids('cls_brand','brand_count',count,'div_brandid');
            reset_attr('input-toggle','cls_brand','brand_count',count,'div_brandid');
			$('#div_brand').find("#" + "brand_name" + count).removeClass('ui-autocomplete-input');
			$('#div_brand').find("#" + "brand_name" + count).removeAttr('auto_suggest_flag');
            $('#brand_count').val(count);
        }
        else
        {
            var span_message = 'Brand max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });

    $('#div_brand').on('click','.remove_brand',function () {
        var count = $('#brand_count').val();
        if (count > 1)
        {
            $(this).parent().remove();

            reset_ids('cls_div_brand','brand_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_brand','brand_count',i,'div_brandid');
				reset_attr('input-toggle','cls_brand','brand_count',i,'div_brandid');
				$('#div_brand').find("#" + "brand_name" + i).removeClass('ui-autocomplete-input');
				$('#div_brand').find("#" + "brand_name" + i).removeAttr('auto_suggest_flag');
            } 
        }      
		
		var audit_logs = $('#audit_logs').val();
		if(audit_logs == ''){
			$('#audit_logs').val("brand");
		}else{
			$('#audit_logs').val(audit_logs + ",brand");
		}
    });

    $("#div_brand").on('input', '.cls_brand', function () {
        var val = this.value;
        if($('#brand_list option').filter(function(){
            return this.value === val;        
        }).length) {
            var new_val = $("#brand_list option[value='"+val+"']").data('brandid');
            var id_num = parseInt($(this).attr('id').replace(/[^\d]/g, ''), 10);
            $('#brand_id'+id_num).val(new_val);
        }
        else
        {
            var id_num = parseInt($(this).attr('id').replace(/[^\d]/g, ''), 10);
            $('#brand_id'+id_num).val('');
        }

    });

    $("#filter_brand_cbo").on('input', '.cls_brand_cbo', function () {
        var val = this.value;
        var new_val = "";
        // console.log(val);

        if ($("#cbo_brand_text").val() != ""){

            $("#brand_list option").each(function(){
                if ($(this).text() == val){
                    // console.log($(this).val());
                    new_val = $(this).val();
                    // $('#cbo_brand').attr('value',new_val);
                    $('#cbo_brand').val(new_val);
                    return false;

                }else{
                    $('#cbo_brand').val(-1);
                }
            })

            var brand_id = $('#cbo_brand').val();
            // console.log(brand_id);
        }else{
            $('#cbo_brand').val('');
        }
    });

    $("#filter_brand_cbo").on('change', '.cls_brand_cbo', function () {
        var val = this.value;
        var new_val = "";
        // console.log(val);

        if ($("#cbo_brand_text").val() != ""){

            $("#brand_list option").each(function(){
                if ($(this).text() == val){
                    // console.log($(this).val());
                    new_val = $(this).val();
                    // $('#cbo_brand').attr('value',new_val);
                    $('#cbo_brand').val(new_val);
                    return false;

                }else{
                    $('#cbo_brand').val(-1);
                }
            })

            var brand_id = $('#cbo_brand').val();
            // console.log(brand_id);
        }else{
            $('#cbo_brand').val('');
        }
    });

    $("#cbo_brand_text").on('click', function () {
        $("#cbo_brand_text").val('');
    });





    var offce_template = $('#div_office_addr1'); // closest('div .row').html();

    $('#btn_off_ad').off().on('click', function(){
        count = $('#office_addr_count').val();
        count++;
        var default_country_id  = $('#default_country_id').val();
        var default_country     = $('#default_country').val();
        
        var new_div = offce_template.clone().attr({'id':'div_office_addr'+count, 'name':'div_office_addr'+count});
        new_div.find(':radio').prop('checked',false);
        $('#div_row_offc_addr').append(new_div).find('#div_office_addr'+count+' :input').val('');
        $('#div_office_addr'+count+' :radio').prop('value',count);
        reset_ids('cls_office_addr','office_addr_count',count,'div_office_addr');
		reset_attr('input-toggle','cls_office_addr','office_addr_count',count,'div_office_addr');
		$("#" + "office_brgy_cm" + count).removeClass('ui-autocomplete-input');
        $("#" + "office_state_prov" + count).removeClass('ui-autocomplete-input');
        $("#" + "office_country" + count).removeClass('ui-autocomplete-input');
		
		$("#" + "office_brgy_cm" + count).removeAttr('auto_suggest_flag');
		$("#" + "office_state_prov" + count).removeAttr('auto_suggest_flag');
		$("#" + "office_country" + count).removeAttr('auto_suggest_flag');
            
		$('#office_country_id'+count).val(default_country_id);
        $('#office_country'+count).val(default_country);
        $('#office_addr_count').val(count);
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
    });

    $('#div_row_offc_addr').on('click','.remove_offc_addr',function () {
        var count = $('#office_addr_count').val();
        if (count > 1)
        {
            $(this).closest('.cls_div_office_addr').remove();

            reset_ids('cls_div_office_addr','office_addr_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                $('#div_office_addr'+i+' :radio').prop('value',i);
                reset_ids('cls_office_addr','office_addr_count',i,'div_office_addr');
				reset_attr('input-toggle','cls_office_addr','office_addr_count',i,'div_office_addr');
				$("#" + "office_brgy_cm" + i).removeClass('ui-autocomplete-input');
				$("#" + "office_state_prov" + i).removeClass('ui-autocomplete-input');
				$("#" + "office_country" + i).removeClass('ui-autocomplete-input');
				$("#" + "office_brgy_cm" + i).removeAttr('auto_suggest_flag');
				$("#" + "office_state_prov" + i).removeAttr('auto_suggest_flag');
				$("#" + "office_country" + i).removeAttr('auto_suggest_flag');
			  
            }
        }
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
        
    });

    var factory_addr_template = $('#div_factory_addr1'); // closest('div .row').html();

    $('#btn_factory_addr').off().on('click', function(){
        var count = $('#factory_addr_count').val();
        count++;
        
        var default_country_id  = $('#default_country_id').val();
        var default_country     = $('#default_country').val();

        var new_div = factory_addr_template.clone().attr({'id':'div_factory_addr'+count, 'name':'div_factory_addr'+count});
        new_div.find(':radio').prop('checked', false);
        $('#div_row_factory_addr').append(new_div).find('#div_factory_addr'+count+' :input').val('');
        $('#div_factory_addr'+count+' :radio').prop('value',count);
        reset_ids('cls_factory_addr','factory_addr_count',count,'div_factory_addr');
		reset_attr('input-toggle','cls_factory_addr','factory_addr_count',count,'div_factory_addr');
		$("#div_factory_addr").find("#" + "factory_brgy_cm" + count).removeClass('ui-autocomplete-input');
        $("#div_factory_addr").find("#" + "factory_state_prov" + count).removeClass('ui-autocomplete-input');
        $("#div_factory_addr").find("#" + "factory_country" + count).removeClass('ui-autocomplete-input');
		$("#" + "factory_brgy_cm" + count).removeAttr('auto_suggest_flag');
		$("#" + "factory_state_prov" + count).removeAttr('auto_suggest_flag');
		$("#" + "factory_country" + count).removeAttr('auto_suggest_flag');
        
        $('#factory_country_id'+count).val(default_country_id);
        $('#factory_country'+count).val(default_country);
        $('#factory_addr_count').val(count);
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
    });

    $('#div_row_factory_addr').on('click','.remove_factory_addr',function () {
        var count = $('#factory_addr_count').val();
        if (count > 1)
        {
            $(this).closest('.cls_div_factory_addr').remove();
        
            reset_ids('cls_div_factory_addr','factory_addr_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                $('#div_factory_addr'+i+' :radio').prop('value',i);
                reset_ids('cls_factory_addr','factory_addr_count',i,'div_factory_addr');
				reset_attr('input-toggle','cls_factory_addr','factory_addr_count',i,'div_factory_addr');
				$("#div_factory_addr").find("#" + "factory_brgy_cm" + i).removeClass('ui-autocomplete-input');
				$("#div_factory_addr").find("#" + "factory_state_prov" + i).removeClass('ui-autocomplete-input');
				$("#div_factory_addr").find("#" + "factory_country" + i).removeClass('ui-autocomplete-input');
				$("#" + "factory_brgy_cm" + i).removeAttr('auto_suggest_flag');
				$("#" + "factory_state_prov" + i).removeAttr('auto_suggest_flag');
				$("#" + "factory_country" + i).removeAttr('auto_suggest_flag');
            }
        }
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
        
    });

    var wh_addr_template = $('#div_wh_addr1'); // closest('div .row').html();

    $('#btn_wh_addr').off().on('click', function(){
        var count = $('#wh_addr_count').val();
        count++;

        var default_country_id  = $('#default_country_id').val();
        var default_country     = $('#default_country').val();
        
        var new_div = wh_addr_template.clone().attr({'id':'div_wh_addr'+count, 'name':'div_wh_addr'+count});
        new_div.find(':radio').prop('checked',false);
        $('#div_row_wh_addr').append(new_div).find('#div_wh_addr'+count+' :input').val('');
        $('#div_wh_addr'+count+' :radio').prop('value',count);
        reset_ids('cls_wh_addr','wh_addr_count',count,'div_wh_addr');
		reset_attr('input-toggle','cls_wh_addr','wh_addr_count',count,'div_wh_addr');
		$("#div_wh_addr").find("#" + "ware_brgy_cm" + count).removeClass('ui-autocomplete-input');
        $("#div_wh_addr").find("#" + "ware_state_prov" + count).removeClass('ui-autocomplete-input');
        $("#div_wh_addr").find("#" + "ware_country" + count).removeClass('ui-autocomplete-input');
		$("#" + "ware_brgy_cm" + count).removeAttr('auto_suggest_flag');
		$("#" + "ware_state_prov" + count).removeAttr('auto_suggest_flag');
		$("#" + "ware_country" + count).removeAttr('auto_suggest_flag');
        
		
        $('#ware_country_id'+count).val(default_country_id);
        $('#ware_country'+count).val(default_country);
        $('#wh_addr_count').val(count);
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
    });

    $('#div_row_wh_addr').on('click','.remove_wh_addr',function () {
        var count = $('#wh_addr_count').val();
        if (count > 1)
        {
            $(this).closest('.cls_div_wh_addr').remove();        

            reset_ids('cls_div_wh_addr','wh_addr_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                $('#div_wh_addr'+i+' :radio').prop('value',i);
                reset_ids('cls_wh_addr','wh_addr_count',i,'div_wh_addr');
				reset_attr('input-toggle','cls_wh_addr','wh_addr_count',i,'div_wh_addr');
				$("#div_wh_addr").find("#" + "ware_brgy_cm" + i).removeClass('ui-autocomplete-input');
				$("#div_wh_addr").find("#" + "ware_state_prov" + i).removeClass('ui-autocomplete-input');
				$("#div_wh_addr").find("#" + "ware_country" + i).removeClass('ui-autocomplete-input');
				$("#" + "ware_brgy_cm" + i).removeAttr('auto_suggest_flag');
				$("#" + "ware_state_prov" + i).removeAttr('auto_suggest_flag');
				$("#" + "ware_country" + i).removeAttr('auto_suggest_flag');
            }
        }
		
		registration_type = $('#registration_type').val();
		if(registration_type == 4){
			if($('#rsd_date_upload43').length > 0){
				if($('#rsd_date_upload43').val() != ''){
					$('#rsd_date_upload43').val("");	// BIR
					$('#rsd_orig_name43').val("");	// BIR
					$("#rsd_document_chk43").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload46').length > 0){
				if($('#rsd_date_upload46').val() != ''){
					$('#rsd_date_upload46').val("");	// BIR
					$('#rsd_orig_name46').val("");	// SI/CI
					$("#rsd_document_chk46").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload47').length > 0){
				if($('#rsd_date_upload47').val() != ''){
					$('#rsd_date_upload47').val("");	// BIR
					$('#rsd_orig_name47').val("");	// SI/CI
					$("#rsd_document_chk47").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
			if($('#rsd_date_upload162').length > 0){
				if($('#rsd_date_upload162').val() != ''){
					$(rsd_date_upload162).val("");	// BIR
					$('#rsd_orig_name162').val("");	// SI/CI
					$("#rsd_document_chk162").prop('checked', false); 
					count = count - 1;
					$('#rsd_upload_count').val(count);
				}
			}
		}
        
    });

    // for tel no start
    var telno_template = $('#tel_no1').closest('div');

    $('#btn_add_telno').off().on('click', function(){
        var count = $('#telno_count').val();
        count++;

        $('#div_telno').append(telno_template.clone().attr({'id':'div_telinline'+count, 'name':'div_telinline'+count})).find('#div_telinline'+count+' :input').val('');
        $('#telno_count').val(count);
        reset_ids('cls_telno','telno_count',count,'div_telinline');
		
		
		$("#" + $('#div_telno').last().attr("id") + " input").each(function() {
			$("#" + this.id).removeClass('has-error-input');
		});
        
    });

    $('#div_telno').on('click','.remove_telno',function () {
        var count = $('#telno_count').val();        
        if (count > 1)
        {
            // $(this).parent().remove();
            $(this).closest('.cls_div_telinline').remove();
            reset_ids('cls_div_telinline','telno_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_telno','telno_count',i,'div_telinline');
            }
        }
    });
    // for tel no end

    // for email start
    var email_template = $('#email1').closest('div');

    $('#btn_add_email').off().on('click', function(){
        var count = $('#email_count').val();
        count++;

        $('#div_email').append(email_template.clone()).find('input:last').attr({'id':'email'+count, 'name':'email'+count, 'value': ''}).val('');
        $('#email_count').val(count);
		
		$("#" + $('#div_email').last().attr("id") + " input").each(function() {
			$("#" + this.id).removeClass('has-error-input');
		});
    });

    $('#div_email').on('click','.remove_email',function () {
        var count = $('#email_count').val();
        if (count > 1)
        {
            $(this).parent().remove();   

            reset_ids('cls_email','email_count');
        }        
    });
    // for email end

    // for faxno start
    var faxno_template = $('#fax_no1').closest('div');

    $('#btn_add_faxno').off().on('click', function(){
        var count = $('#faxno_count').val();
        count++;

        $('#div_faxno').append(faxno_template.clone().attr({'id':'div_faxinline'+count, 'name':'div_faxinline'+count, 'fflop': count})).find('#div_faxinline'+count+' :input').val('');
        $('#faxno_count').val(count);
        reset_ids('cls_faxno','faxno_count',count,'div_faxinline');
		
		$("#" + $('#div_faxno').last().attr("id") + " input").each(function() {
			$("#" + this.id).removeClass('has-error-input');
		});
    });

    $('#div_faxno').on('click','.remove_faxno',function () {
        var count = $('#faxno_count').val();
        if (count > 1)
        {
            $(this).closest('.cls_div_faxinline').remove();
            reset_ids('cls_div_faxinline','faxno_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_faxno','faxno_count',i,'div_faxinline');
            }
        }
        
    });
    // for faxno end

    // for mobno start
    var mobno_template = $('#mobile_no1').closest('div');

    $('#btn_add_mobno').off().on('click', function(){
        var count = $('#mobno_count').val();
        count++;

        $('#div_mobno').append(mobno_template.clone().attr({'id':'div_mobnoinline'+count, 'name':'div_mobnoinline'+count, 'mflop' : count})).find('#div_mobnoinline'+count+' :input').val('');
        $('#mobno_count').val(count);
        reset_ids('cls_mobno','mobno_count',count,'div_mobnoinline');
		
		$("#" + $('#div_mobno').last().attr("id") + " input").each(function() {
			$("#" + this.id).removeClass('has-error-input');
		});
    });

    $('#div_mobno').on('click','.remove_mobno',function () {
        var count = $('#mobno_count').val();
        if (count > 1)
        {
            $(this).closest('.cls_div_mobnoinline').remove();
            reset_ids('cls_div_mobnoinline','mobno_count'); // update id's of parent
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_mobno','mobno_count',i,'div_mobnoinline');
            }
        }
        
    });
    // for mobno end

    // for Owners/Partners/Directors Start
    var opd_template = $('#tr_opd1');
    $('#btn_add_opd').off().on('click', function(){
        var count       = $('#opd_count').val();
        var max_count   = $('#opd_max').val();
        // console.log(count + '===' +max_count);
        count++;
        if (count <= max_count)
        {
            $('#tbl_opd > tbody:last-child').append(opd_template.clone().attr({'id':'tr_opd'+count, 'flop' : count})).find('#tr_opd'+count+' :input').val('');
            reset_ids('cls_opd','opd_count',count,'tr_opd');
            $('#opd_count').val(count);
			
			$("#" + $('#tbl_opd > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Owners/Partners/Directors max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });
    // for OPD END

    // for Authorized Representatives START
    var authrep_template = $('#tr_authrep1');
    $('#btn_add_authrep').off().on('click', function(){
        var count       = $('#authrep_count').val();
        var max_count   = $('#authrep_max').val();
        // console.log(count + '===' +max_count);
        count++;
        if (count <= max_count)
        {
            $('#tbl_authrep > tbody:last-child').append(authrep_template.clone().attr({'id':'tr_authrep'+count,'authflop':count})).find('#tr_authrep'+count+' :input').val('');
            reset_ids('cls_authrep','authrep_count',count,'tr_authrep');
            $('#authrep_count').val(count);
			
			$("#" + $('#tbl_authrep > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Authorized Representatives max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });
    // for Authorized Representatives END

    // for Bank References START
    var bankrep_template = $('#tr_bankrep1');
    $('#btn_add_bankrep').off().on('click', function(){
        var count       = $('#bankrep_count').val();
        var max_count   = $('#bankrep_max_count').val();
        count++;
        
        if (count <= max_count)
        {
            $('#tbl_bankrep > tbody:last-child').append(bankrep_template.clone().attr({'id':'tr_bankrep'+count,'bflop':count})).find('#tr_bankrep'+count+' :input').val('');
            reset_ids('cls_bankrep','bankrep_count',count,'tr_bankrep');
            $('#bankrep_count').val(count);
			
			$("#" + $('#tbl_bankrep > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Bank References max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });
    // for Bank References END

    // for Other Retail Customers/Clients START
    var orcc_template = $('#tr_orcc1');
    $('#btn_add_orcc').off().on('click', function(){
        var count       = $('#orcc_count').val();
        var max_count   = $('#max_orcc_count').val();
        count++;
        
        if (count <= max_count)
        {
            $('#tbl_orcc > tbody:last-child').append(orcc_template.clone().attr({'id':'tr_orcc'+count,'orflop':count})).find('#tr_orcc'+count+' :input').val('');
            reset_ids('cls_orcc','orcc_count',count,'tr_orcc');
            $('#orcc_count').val(count);
			
			$('#tbl_orcc > tbody:last-child tr').last().removeClass('has-error');
			$("#" + $('#tbl_orcc > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Other Retail Customers/Clients max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });
    // for Other Retail Customers/Clients END

    // for Other Other Business START
    var otherbusiness_template = $('#tr_otherbusiness1');
    $('#btn_add_otherbusiness').off().on('click', function(){
        var count = $('#otherbusiness_count').val();
        var max_count   = $('#max_orcc_count').val();
        count++;
        
        if (count <= max_count)
        {
            $('#tbl_otherbusiness > tbody:last-child').append(otherbusiness_template.clone().attr({'id':'tr_otherbusiness'+count,'otbflop':count})).find('#tr_otherbusiness'+count+' :input').val('');
            reset_ids('cls_otherbusiness','otherbusiness_count',count,'tr_otherbusiness');
            $('#otherbusiness_count').val(count);
			
			$("#" + $('#tbl_otherbusiness > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Other Business max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
        
    });
    // for Other Other Business END

    // for Disclosure of Relatives Working in SM or its Affiliates START
    var affiliates_template = $('#tr_affiliates1');
    $('#btn_add_affiliates').off().on('click', function(){
        var count       = $('#affiliates_count').val();
        var max_count   = $('#max_affiliates_count').val();
        count++;
        
        if (count <= max_count)
        {
            $('#tbl_affiliates > tbody:last-child').append(affiliates_template.clone().attr({'id':'tr_affiliates'+count, 'afflop':count})).find('#tr_affiliates'+count+' :input').val('');
            reset_ids('cls_affiliates','affiliates_count',count,'tr_affiliates');
            $('#affiliates_count').val(count);
			
			$("#" + $('#tbl_affiliates > tbody:last-child tr').last().attr("id") + " td input").each(function() {
				$("#" + this.id).removeClass('has-error-input');
			});
        }
        else
        {
            var span_message = 'Disclosure of Relatives Working in SM or its Affiliates max count reached!';
            var type = 'danger';
            notify(span_message, type);
        }
    });
    // for Disclosure of Relatives Working in SM or its Affiliates END

    $('#btnSearchVendor').unbind().on('click',function(){
        //$('#btn_upload').val('3');
        //$('#btn_upload').prop('disabled', false);

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .search_vendor').show();
    })

	// Added MSF - 20191108 (IJR-10617)
    // for invite - approve items
    $('#btn_invite_upload').unbind().on('click',function(){
        $('#btn_upload').val('3');
        $('#btn_upload').prop('disabled', false);

        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .upload_documents').show();
			
        $("#fileupload").val("");
    })

    // Required Scanned Documents START
    $('#btn_rsd_upload').unbind().on('click', function(){
        $('#btn_upload').val('1'); // set value to 1 upload documents
        $('#btn_upload').prop('disabled', false);

        if ($('#cbo_rsd_list').val())
        {
            $('#cbo_rsd_list').parent('div').removeClass('has-error');
            $('#myModal').modal('show');

            $('#myModal span').hide();
            $('.alert > span').show(); // dont include to hide these span
            $('#myModal .upload_documents').show();

            var id = $('#cbo_rsd_list').val();

            if ($('#rsd_document_chk'+id).is(':checked'))
            {
                // $('#btn_upload').prop('disabled', true);
                modal_notify($("#myModal"),'You already upload a file to this document', 'modal_warning');
            }
			
			$("#fileupload").val("");
			$("#btn_upload").removeAttr("disabled");
			$("#fileupload").removeAttr("disabled");
			$(".btn_upload_no").removeAttr("disabled");
			//cache.get('modal_alert').stop().fadeOut("slow");
        }
        else
        {
            var span_message = 'Please Select Document type!';
            var type = 'danger';
            notify(span_message, type);
            $('#cbo_rsd_list').parent('div').addClass('has-error');
        }
        
    });

	// Added MSF - 20191105 (IJR-10612)
    var picture = $('#image_preview');

    $('#myModal').on('hidden.bs.modal', function() {
        picture.guillotine('remove');
        picture.css('display', 'none');
        $('#pdf_preview').css('display', 'none');
    });

    $('table').on('click', '.preview', function(){
        
        $('.close').alert('close'); // close alert if it has
        var url = BASE_URL.replace('index.php/','') + $(this).val();
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .document_preview').show();
		// Updated by MSF - 20191105 (IJR-10612)
        //$('#imagepreview').attr('src', '');
        picture.attr('src', '');
        $('#pdf_preview').attr('src', '');
		
        if ($(this).val() != '')
        {
			// Updated by MSF - 20191105 (IJR-10612)
            //$('#imagepreview').attr('src', url);
            //$('#imagepreview').removeClass('zoom_in');
            $('.modal-dialog').addClass('modal-lg');    
            var filext = $(this).val().split('.').pop();
            // setting height of iframe according to window size
            var set_height  = '';
            var w_h         = '';
            var t_h         = '';

			// Updated by MSF - 20191105 (IJR-10612)
            /*if (filext.toLowerCase() == 'pdf')
            {
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();
                $('#btn_printimg').hide();
            }
            else
            {
                w_h = $(window).height() /2;
                t_h = $(this).height() /2;
                $('#zoom_image').show();
                $('#zoom_out_image').show();
                $('#btn_printimg').show();
            }
            $('iframe').height(w_h);
			*/
			if (filext.toLowerCase() == 'pdf'){
				
				var parent = $('#frame');
				var newElement = "<embed src="+url+" id='pdf_preview' style='width: 100%; height: 100%; display: none;'>";
				
				$('#pdf_preview').remove();
				parent.append(newElement);
				
				$('#pdf_preview').removeClass('zoom_in');
                $('#pdf_preview').css('display', 'inline')
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();
                $('#fit_to_screen').hide();
                $('#btn_printimg').hide();
                $('#btn_download').hide();
            }else{
				$('#pdf_preview').css('display', 'none');
                picture.attr('src', url);
                picture.removeClass('zoom_in');
                picture.css('display', 'inline');
                
                $('#zoom_image').show();
                $('#zoom_out_image').show();
                $('#fit_to_screen').show();
                $('#btn_printimg').show();
                $('#btn_download').show();

                w_h = $(window).height() /2;
                t_h = $(this).height() /2;
            }
			
            $('embed').height(w_h);
            $(window).resize(function(){
				// Modified MSF - 20191118 (IJR-10612)
                //$('iframe').height(t_h);
                $('embed').height(t_h);
            });
        }
        else
        {
            $('#imagepreview').attr('src', '');
        }
        var view_only = $('#view_only').val();
        
		//jay
		var status_id = $("#status_id").val();//end
		
        // check if element exists
		if (view_only != 1){
			$(this).closest('tr').find('.validated:checkbox:not(:checked, .mainchk)').prop('disabled', false);
			$(this).closest('tr').find('.reviewed:checkbox:not(:checked, .mainchk)').prop('disabled', false);
			$(this).closest('tr').find('input[id^=rsd_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
			
			// Disabled "IF" for MSF - 20191126 (IJR-10619)
			//if(status_id == 194){
				$(this).closest('tr').find('input[id^=ra_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
			//}
			
				$(this).closest('tr').find('input[id^=ccn_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
			
			if(status_id == 10 || status_id == 194){
				var wd = 0;
				
				if(status_id == 194){
					wd = $($(this).closest('tr').find('input[id^=waive_ad_document_ch]:checked')).length
				}else if(status_id == 10){
					wd = $($(this).closest('tr').find('input[id^=waive_rsd_document_ch]:checked')).length
				}
			
				var check_data = $(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').hasClass("reviewed_additional");
				if( ! check_data && wd <= 0){
					$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').addClass("reviewed_additional");
					$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
					$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').removeClass("na_reviewed");
					$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').removeClass("na_reviewed_additional");
				}else if( ! check_data){
					$(this).closest('tr').find('.na_reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', false);
				}
			}
		}
		
		// Disabled for MSF - 20191126 (IJR-10619)
		//jay
        //if(status_id == 10){
			//alert("test");
			//$(this).closest('tr').find('input[id^=ra_document_review].reviewed_additional:checkbox:not(:checked, .mainchk)').prop('disabled', true);
		//}
    });
	
	$('#btn_avc_invite_view').on('click', function(){
        $('.close').alert('close'); // close alert if it has
        var url = BASE_URL.replace('index.php/','') + $(this).val();
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .document_preview').show();
        $('#imagepreview').attr('src', '');
        if ($(this).val() != '')
        {
            $('.modal-dialog').addClass('modal-lg');
            var filext = $(this).val().split('.').pop();
            // setting height of iframe according to window size
            var set_height  = '';
            var w_h         = '';
            var t_h         = '';

            if (filext.toLowerCase() == 'pdf')
            {
				
				var parent = $('#frame');
				var newElement = "<embed src="+url+" id='pdf_preview' style='width: 100%; height: 100%; display: none;'>";
				
				$('#pdf_preview').remove();
				parent.append(newElement);
				
				$('#pdf_preview').removeClass('zoom_in');
                $('#pdf_preview').css('display', 'inline')
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();
                $('#fit_to_screen').hide();
                $('#btn_printimg').hide();
                $('#btn_download').hide();
            }
            else
            {
				$('#pdf_preview').css('display', 'none');
                picture.attr('src', url);
                picture.removeClass('zoom_in');
                picture.css('display', 'inline');

                $('#zoom_image').show();
                $('#zoom_out_image').show();
                $('#fit_to_screen').show();
                $('#btn_download').show();
                $('#btn_printimg').show();

                w_h = $(window).height()/2;
                t_h = $(this).height()/2;
            }
            
            $('embed').height(w_h);
            $(window).resize(function(){
                $('embed').height(t_h);
            });
        }
        else
        {
            $('#imagepreview').attr('src', '');
        }
        var view_only = $('#view_only').val();
        
		//jay
		var status_id = $("#status_id").val();//end
    
	});

	//Added MSF - 20191108 (IJR-10617)
    //inviteforapprovaldetails - view image
    $('#btn_invite_view').on('click', function(){
        $('.close').alert('close'); // close alert if it has
        var url = BASE_URL.replace('index.php/','') + $(this).val();
        $('#myModal').modal('show');

        $('#myModal span').hide();
        $('.alert > span').show(); // dont include to hide these span
        $('#myModal .document_preview').show();
        $('#imagepreview').attr('src', '');
        if ($(this).val() != '')
        {
            $('.modal-dialog').addClass('modal-lg');
            var filext = $(this).val().split('.').pop();
            // setting height of iframe according to window size
            var set_height  = '';
            var w_h         = '';
            var t_h         = '';

            if (filext.toLowerCase() == 'pdf')
            {
				
				var parent = $('#frame');
				var newElement = "<embed src="+url+" id='pdf_preview' style='width: 100%; height: 100%; display: none;'>";
				
				$('#pdf_preview').remove();
				parent.append(newElement);
				
				$('#pdf_preview').removeClass('zoom_in');
                $('#pdf_preview').css('display', 'inline')
                w_h = $(window).height() * 0.75;
                t_h = $(this).height() * 0.75;
                $('#zoom_image').hide();
                $('#zoom_out_image').hide();
                $('#fit_to_screen').hide();
                $('#btn_printimg').hide();
                $('#btn_download').hide();
            }
            else
            {
				$('#pdf_preview').css('display', 'none');
                picture.attr('src', url);
                picture.removeClass('zoom_in');
                picture.css('display', 'inline');

                $('#zoom_image').show();
                $('#zoom_out_image').show();
                $('#fit_to_screen').show();
                $('#btn_download').show();
                $('#btn_printimg').show();

                w_h = $(window).height()/2;
                t_h = $(this).height()/2;
            }
            
            $('embed').height(w_h);
            $(window).resize(function(){
                $('embed').height(t_h);
            });
        }
        else
        {
            $('#imagepreview').attr('src', '');
        }
        var view_only = $('#view_only').val();
        
		//jay
		var status_id = $("#status_id").val();//end
    });

	// Added MSF - 20191105 (IJR-10612)
    picture.on('load', function() {
        picture.guillotine({
            width: 400,
            height: 300
        });

        picture.guillotine('fit');

        // Initialize plugin (with custom event)
        picture.guillotine({eventOnChange: 'guillotinechange'});

        var data = picture.guillotine('getData');
        for(var key in data) { $('#'+key).html(data[key]); }
    });

	// Added MSF - 20191105 (IJR-10612)
    $('#fit_to_screen').click(function(){ picture.guillotine('fit'); });
    $('#zoom_image').click(function(){ picture.guillotine('zoomIn'); });
    $('#zoom_out_image').click(function(){ picture.guillotine('zoomOut'); });
	
    $('#myModal').on('hidden.bs.modal', function () {
        //$('.modal-dialog').removeClass('modal-lg'); //jay 
    });
	
	$(document).on("click", ".btn_upload_no, .close", function(){
		$("#btn_upload").removeAttr("disabled");
		$("#fileupload").removeAttr("disabled");
		$(".btn_upload_no").removeAttr("disabled");
	});
	
    $('#btn_upload').on('click', function(){
	    if ($('#fileupload').val() != ''){
		
            if($('#valid_file').val() == 1){
				//upload_button = this;
				//$(this).attr("disabled","disabled");
				//$("#fileupload").attr("disabled","disabled");
				//$("#btn_upload_cancel").attr("disabled","disabled");
				var val = this.value;
				var span_message = 'Are you sure you want to upload this file? <button type="button" class="btn btn-success" onclick="upload_file(' + val +',this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default btn_upload_no" id="close_alert">No</button>';
				var type = 'modal_info';
				modal_notify($("#myModal"), span_message, type, true);
			}
            else{
			    modal_notify($("#myModal"), 'The uploaded file exceeds the maximum allowed size of 5 MB', "modal_danger");
			}
        } else{
			modal_notify($("#myModal"), 'You did not select a file to upload.', "modal_danger");
		}
    });

    $('#fileupload').bind('change', function() {

      //this.files[0].size gets the size of your file.
		// Modified MSF - 20191105 (IJR-10617)
        //if (this.files[0].size <= 2000000){
        if (this.files[0].size <= 5242880){
            $('#valid_file').val('1');
        }else
            $('#valid_file').val('0');

    });

    // Required Agreements START
    $('#btn_ra_upload').on('click', function(){
        $('#btn_upload').val('2'); // set value to 2 upload agreements
        $('#btn_upload').prop('disabled', false);

        if ($('#cbo_ra_list').val())
        {
            $('#cbo_ra_list').parent('div').removeClass('has-error');
            $('#myModal').modal('show');

            $('#myModal span').hide();
            $('.alert > span').show(); // dont include to hide these span
            $('#myModal .upload_documents').show();

            var id = $('#cbo_ra_list').val();

            if ($('#ra_document_chk'+id).is(':checked'))
            {
                // $('#btn_upload').prop('disabled', true);
                modal_notify($("#myModal"),'You already upload a file to this document', 'modal_warning');
            }
			$("#fileupload").val("");
			$("#btn_upload").removeAttr("disabled");
			$("#fileupload").removeAttr("disabled");
			$(".btn_upload_no").removeAttr("disabled");
			//cache.get('modal_alert').stop().fadeOut("slow");
        }
        else
        {
            var span_message = 'Please Select Agreement type!';
            var type = 'danger';
            notify(span_message, type);
            $('#cbo_ra_list').parent('div').addClass('has-error');
        }
        
    });
    // Required Agreements END

    // Required Agreements START
    $('#btn_ccn_upload').on('click', function(){
        $('#btn_upload').val('4'); // set value to 4 Change Company Name Requirements
        $('#btn_upload').prop('disabled', false);

        if ($('#cbo_ccn_list').val())
        {
            $('#cbo_ccn_list').parent('div').removeClass('has-error');
            $('#myModal').modal('show');

            $('#myModal span').hide();
            $('.alert > span').show(); // dont include to hide these span
            $('#myModal .upload_documents').show();

            var id = $('#cbo_ccn_list').val();

            if ($('#ccn_document_chk'+id).is(':checked'))
            {
                // $('#btn_upload').prop('disabled', true);
                modal_notify($("#myModal"),'You already upload a file to this document', 'modal_warning');
            }
			$("#fileupload").val("");
			$("#btn_upload").removeAttr("disabled");
			$("#fileupload").removeAttr("disabled");
			$(".btn_upload_no").removeAttr("disabled");
			//cache.get('modal_alert').stop().fadeOut("slow");
        }
        else
        {
            var span_message = 'Please Select Requirements!';
            var type = 'danger';
            notify(span_message, type);
            $('#cbo_ccn_list').parent('div').addClass('has-error');
        }
        
    });
    // Required Agreements END

    $('input:radio[name=vendor_type]').on('click', function(){
        if (this.value == 1) // trade
            $('input:radio[name=trade_vendor_type]').prop('disabled', false);
        else
        {
            $('input:radio[name=trade_vendor_type]').prop({'disabled': true, 'checked': false});
            $('input[name=trade_vendor_type][value=0]').prop('checked', true);

            var ownership           = '';
            var trade_vendor_type   = '0';

            if ($("input[name='ownership']:checked").length > 0){
                ownership = $("input[name='ownership']:checked").val();
			}

            /*if ($("input[name='trade_vendor_type']:checked").length > 0){
                trade_vendor_type = 1; //$("input[name='trade_vendor_type']:checked").val();
			}else{
				trade_vendor_type = $("input[name='vendor_type']:checked").val();
				
				if(trade_vendor_type == 3){
					trade_vendor_type = 4; // 4 = NTS 
				}
			}*/

            get_list_docs(ownership, trade_vendor_type, '', this.value);
        }

    });
    // save or draft
    $('#btn_vr_sad').on('click', function(){
        // validate brand duplicates on draft save
        var inputs=[], flag=false
        var duplicate_val; 

        $('#div_brand input[type=text]').each(function(){
            if ($.inArray(this.value, inputs) != -1){ 
                flag=true;
                duplicate_val=$(this).val();
            }
            inputs.push(this.value);
        });

        if (flag==true) {
            $('#div_brand input[type=text]').each(function(){
                if ($(this).val()==duplicate_val){
                   $(this).parent('div').addClass('has-error');
                }
            });

            var span_message = 'Multiple Brands!';
            var type = 'warning';
            notify(span_message, type);
            return;  
        }
        
        /*var input_id_bool = true; // allowed na kahit wala sa list. ang ggwin isave then get the id parang sa brands
        $('.id-required').each(function() {
            if ($(this).next('input').val() != '') // if name is not empty dpat di din empty ung ID
            {
                if ( $.trim($(this).val()) === '' || $(this).val() === null) // added trim to remove whitespaces
                {
                    input_id_bool = false;
                    $('#'+this.id).parent('div').addClass('has-error');
                }
                else
                {
                    $('#'+this.id).parent('div').removeClass('has-error');
                }
            }            
        });

        if (!input_id_bool)
        {
            var span_message = 'Pease select an item from the list!';
            var type = 'danger';
            notify(span_message, type);
            return;  
        }*/

        if (!isEmail())
        {
            var span_message = 'Invalid email format!';
            var type = 'danger';
            notify(span_message, type);
            return;  
        }

        $('input[name=vendor_type]').addClass('exclude');
        $('#rsd_tbl :input').addClass('exclude');
        $('#ra_tbl :input').addClass('exclude');
        
        if ($("input[name='vendor_type']:checked").val() == 2) // exclude also the trade vendor type
            $('input[name=trade_vendor_type]').addClass('exclude');
        else
            $('input[name=trade_vendor_type]').removeClass('exclude');

        if (!$('#nob_others').is(':checked')) // if not checked exclude from enable txt_nob_others
            $('input[name=txt_nob_others]').addClass('exclude');
        else
            $('input[name=txt_nob_others]').removeClass('exclude');

        disable_enable_frm('frm_registration', true, '.exclude');

        var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_registration_vendor(1,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_registration\', false,\'.exclude\');check_status();">No</button>';
        var type = 'info';
        notify(span_message, type, true);
    });
    $('#btn_submit').on('click', function() {

        var inputs=[], flag=false
        var duplicate_val; 

        $('#div_brand input[type=text]').each(function(){
            if ($.inArray(this.value, inputs) != -1){ 
                flag=true;
                duplicate_val=$(this).val();
            }
            inputs.push(this.value);
        });

        if (flag==true) {
            $('#div_brand input[type=text]').each(function(){
                if ($(this).val()==duplicate_val){
                   $(this).parent('div').addClass('has-error');
                }
            });

            var span_message = 'Multiple Brands!';
            var type = 'warning';
            notify(span_message, type);
            return;  
        }

        var show_alert = false;
        if (!validateForm())
        {
            show_alert = true;
            // if (!validateOther()){

                //validate unique brand
                
                // var span_message = 'Please fill up all fields!';
                // var type = 'danger';
                // notify(span_message, type);
                // return;  
            // }
        }

        if (!validateOther())
            show_alert = true;

        if (show_alert)
        {
            var span_message = 'Please fill up all fields!';
            var type = 'danger';
            notify(span_message, type);
            return;  
        }

        /*var input_id_bool = true; // allowed na kahit wala sa list. ang ggwin isave then get the id parang sa brands
        $('.id-required').each(function() {
            if ($(this).next('input').val() != '') // if name is not empty dpat di din empty ung ID
            {
                if ( $.trim($(this).val()) === '' || $(this).val() === null) // added trim to remove whitespaces
                {
                    input_id_bool = false;
                    $('#'+this.id).parent('div').addClass('has-error');
                }
                else
                {
                    $('#'+this.id).parent('div').removeClass('has-error');
                }
            } 
        });

        if (!input_id_bool)
        {
            var span_message = 'Pease select an item from the list!';
            var type = 'danger';
            notify(span_message, type);
            return;  
        }*/

        if (!validate_primary())
            return;

        if (!isEmail())
        {
            var span_message = 'Invalid email format!';
            var type = 'danger';
            notify(span_message, type);
            return;  
        }

        $('input[name=vendor_type]').addClass('exclude');
        $('#rsd_tbl :input').addClass('exclude');
        $('#ra_tbl :input').addClass('exclude');
        
        if ($("input[name='vendor_type']:checked").val() == 2 || $("input[name='vendor_type']:checked").val() == 3) // exclude also the trade vendor type
            $('input[name=trade_vendor_type]').addClass('exclude');
        else
            $('input[name=trade_vendor_type]').removeClass('exclude');

        if (!$('#nob_others').is(':checked')) // if not checked exclude from enable txt_nob_others
            $('input[name=txt_nob_others]').addClass('exclude');
        else
            $('input[name=txt_nob_others]').removeClass('exclude');

        if (!$('#chk_certify').is(':checked'))
        {
            var span_message = 'Please tick certify checkbox';
            var type = 'danger';
			$('#div_chk_certify').addClass('div-error');
            notify(span_message, type);
            // $('#chk_certify').focus();
			// $('#chk_certify').addClass('has-error');
            return;
        }
        else
        {
            disable_enable_frm('frm_registration', true, '.exclude');
            var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_registration_vendor(2,this)" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_registration\', false, \'.exclude\');check_status();">No</button>';
            var type = 'info';
            notify(span_message, type, true);
        }
        
    });

    function validateOther(){
        var isValid = true;
        var rsd_flag = false;
        var ra_flag = false;
        var iCtr=0;

        removeHasError();

        $('#nature_of_business input').each(function () {
             if($(this).prop("checked") == false){          
                iCtr++
             }             
        });

        if (iCtr==6){
            $('#nature_of_business fieldset').each(function(){
                $(this).addClass('has-error');
            });
            isValid = false;
        }else{
            $('#nature_of_business fieldset').each(function(){
                $(this).removeClass('has-error');
            });
            $('#txt_nob_others').parent('div').removeClass('has-error');
        }

        if ($('#nob_others').prop("checked") == true){
            if ($('#txt_nob_others').val().length == 0){
                $('#txt_nob_others').parent('div').addClass('has-error');
                isValid = false;
            }else{
                $('#txt_nob_others').parent('div').removeClass('has-error');
            }
        }

        iCtr=0;

        $('#tbl_opd :input').each(function()
        {

            // alert($(this).val());
            if($(this).val().length > 0){          
                iCtr++;
            }           
        });

        if (iCtr > 0){
            // alert("check");
            $('#tbl_opd tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_opd tbody tr').each(function(i){
                // if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }

        iCtr=0;

        $('#tbl_authrep :input').each(function()
        {
            if($(this).val().length > 0){          
                iCtr++;
            }               
        });

        if (iCtr > 0){
            $('#tbl_authrep tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_authrep tr').each(function(i){
                if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }

        $('#rsd_tbl tr').each(function(){
            // $(this).parent('div').addClass('has-error');
            $(this).find('input[type=text]').each(function(){
                if ($(this).val().length==0){
                    rsd_flag=true;
                }else{
                    rsd_flag=false;
                }
            });

            var rsd_label = $(this).find('p').attr('id');

            if (rsd_label != null){

                if (rsd_flag==true){
                    document.getElementById(rsd_label).style.color = "#A94442";

                    $(this).addClass('has-error');
                    isValid = false;
                }else{
                    document.getElementById(rsd_label).style.color = "#333333";
                    $(this).removeClass('has-error');
                }
            }
        });

        $('#ccn_tbl tr').each(function(){
            // $(this).parent('div').addClass('has-error');
            $(this).find('input[type=text]').each(function(){
                if ($(this).val().length==0){
                    cnn_flag=true;
                }else{
                    cnn_flag=false;
                }
            });

            var cnn_label = $(this).find('p').attr('id');

            if (cnn_label != null){

                if (cnn_flag==true){
                    document.getElementById(cnn_label).style.color = "#A94442";

                    $(this).addClass('has-error');
                    isValid = false;
                }else{
                    document.getElementById(cnn_label).style.color = "#333333";
                    $(this).removeClass('has-error');
                }
            }
        });

        //tbl_bankrep
        iCtr=0;

        $('#tbl_bankrep :input').each(function()
        {
            // alert($(this).val());
            if($(this).val().length > 0){          
                iCtr++;
            }           
        });

        if (iCtr > 0){
            // alert("check");
            $('#tbl_bankrep tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_bankrep tbody tr').each(function(i){
                // if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }
		
		//tbl_orcc
		iCtr=0;

        $('#tbl_orcc :input').each(function()
        {
            // alert($(this).val());
            if($(this).val().length > 0){          
                iCtr++;
            }           
        });

        if (iCtr > 0){
            // alert("check");
            $('#tbl_orcc tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_orcc tbody tr').each(function(i){
                // if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }
		
		
		//tbl_otherbusiness
		iCtr=0;

        $('#tbl_otherbusiness :input').each(function()
        {
            // alert($(this).val());
            if($(this).val().length > 0){          
                iCtr++;
            }           
        });

        if (iCtr > 0){
            // alert("check");
            $('#tbl_otherbusiness tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_otherbusiness tbody tr').each(function(i){
                // if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }
		
		
		//tbl_affiliates
		iCtr=0;

        $('#tbl_affiliates :input').each(function()
        {
            // alert($(this).val());
            if($(this).val().length > 0){          
                iCtr++;
            }           
        });

        if (iCtr > 0){
            // alert("check");
            $('#tbl_affiliates tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_affiliates tbody tr').each(function(i){
                // if (i >0 )
                $(this).addClass('has-error');
            });
            isValid = false;
        }
		
        // required nalang pag additinao req na ang status
        // if ($('#status_id').val() == 190 || $('#status_id').val() == 195) // aditional req disable all exclude #additional req //190 = additional req, 195 = incomplete additional req
        if ($('#status_id').val() > 9 && $('#status_id').val() != 11) // submitted onwards pde na mag submit ng addtional
        {
            $('#ra_tbl tr').each(function(){
                // $(this).parent('div').addClass('has-error');
                $(this).find('input[type=text]').each(function(){
					//Jay added: && $(this).attr("data-required") != "checked"
                    if ($(this).val().length==0 && $(this).attr("data-required") != "checked"){
                        // alert($(this).val().length);
                        ra_flag=true;
                    }else{
                        ra_flag=false;
                    }
                });

                var ra_label = $(this).find('p').attr('id');

                if (ra_label != null){
                    if (ra_flag==true){
                        document.getElementById(ra_label).style.color = "#A94442";

                        $(this).addClass('has-error');
                        isValid = false;
                    }else{
                        document.getElementById(ra_label).style.color = "#333333";
                        $(this).removeClass('has-error');
                    }
                }
            });
        }    

		if($('#registration_type').val() != 1 && $('#registration_type').val() != 5){
			$('#ra_tbl tr').each(function(){
                // $(this).parent('div').addClass('has-error');
                $(this).find('input[type=text]').each(function(){
					//Jay added: && $(this).attr("data-required") != "checked"
                    if ($(this).val().length==0 && $(this).attr("data-required") != "checked"){
                        // alert($(this).val().length);
                        ra_flag=true;
                    }else{
                        ra_flag=false;
                    }
                });

                var ra_label = $(this).find('p').attr('id');

                if (ra_label != null){
                    if (ra_flag==true){
                        document.getElementById(ra_label).style.color = "#A94442";

                        $(this).addClass('has-error');
                        isValid = false;
                    }else{
                        document.getElementById(ra_label).style.color = "#333333";
                        $(this).removeClass('has-error');
                    }
                }
            });
		}

        /*iCtr=0;

        $('#tbl_bankrep input').each(function()
        {
            if($(this).val().length > 0){          
                iCtr++;
            }               
        });

        if (iCtr > 0){
            $('#tbl_bankrep tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_bankrep tr').each(function(){
                $(this).addClass('has-error');
            });
            isValid = false;
        }

        iCtr=0;

        $('#tbl_orcc input').each(function()
        {
            if($(this).val().length > 0){          
                iCtr++;
            }               
        });

        if (iCtr > 0){
            $('#tbl_orcc tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_orcc tr').each(function(){
                $(this).addClass('has-error');
            });
            isValid = false;
        }

        iCtr=0;

        $('#tbl_otherbusiness input').each(function()
        {
            if($(this).val().length > 0){          
                iCtr++;
            }               
        });

        if (iCtr > 0){
            $('#tbl_otherbusiness tr').each(function(){
                $(this).removeClass('has-error');
            });
        }else{
            $('#tbl_otherbusiness tr').each(function(){
                $(this).addClass('has-error');
            });
            isValid = false;
        }*/

        focus_first_elem_error();

        return isValid;
    }

    function removeHasError(){
        $('#nature_of_business fieldset').removeClass('has-error');
        $('#txt_nob_others').parent('div').removeClass('has-error');
        $('#tbl_opd tr').removeClass('has-error');
        $('#tbl_authrep tr').removeClass('has-error');
    }

    //####################################################### Vendor Registartion END

    //####################################################### Registration Review START
    $('table').on('click', '.reviewed, .validated, .na_reviewed_additional', function(){
        var m_names = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        var d = new Date();
        var curr_date = ('0' + d.getDate()).slice(-2);
        var mm = d.getMonth()+1; //January is 0!
        var curr_month = ('0' + mm).slice(-2);
        var curr_year = d.getFullYear(); //.toString().substr(-2);
        var curr_hour = (d.getHours()<10?'0':'') + d.getHours(); //('0' + d.getHours()).slice(-2);
        var curr_mins = (d.getMinutes()<10?'0':'') + d.getMinutes(); //('0' + d.getMinutes()).slice(-2);
        var curr_sec  = (d.getSeconds()<10?'0':'') + d.getSeconds(); //('0' + d.getSeconds()).slice(-2);
        // var new_date = curr_date + "-" + m_names[curr_month] + "-" + curr_year;
        var ampm = curr_hour >= 12 ? 'PM' : 'AM';
        curr_hour = curr_hour % 12;
        curr_hour = curr_hour ? curr_hour : 12; // the hour '0' should be '12'

        var new_date = curr_month + "/" + curr_date + "/" + curr_year + " " + curr_hour + ":" + curr_mins + ":" + curr_sec + " " + ampm;
        
 
        var sysdate = $('#sysdate').data("rel"); 
			
		var x = new Date(sysdate);
		var dateStr =
			  x.getFullYear() + "-" + ("00" + (x.getMonth() + 1)).slice(-2) + "-" + ("00" + x.getDate()).slice(-2)
			   + " " +
			  ("00" + x.getHours()).slice(-2) + ":" + ("00" + x.getMinutes()).slice(-2) + ":" + ("00" + x.getSeconds()).slice(-2);

        // set date to review date
        $(this).closest('td').next().find(':input').val(dateStr);
		var sid_temp = parseInt($("#status_id").val());
		//console.log("STATUS ID FROM VJS " + sid_temp);
		if(sid_temp != 194){
			// check if all is check and enable submit
			if ($('.reviewed:checked').length == $('.reviewed').length)
				$('#btn_sub_review').prop('disabled', false);
			else
				$('#btn_sub_review').prop('disabled', true);
		}

         // check if all is check and enable request visit
        if ($('.validated:checked').length == $('.validated').length)
            $('#btn_submit_valid').prop('disabled', false);
        else
            $('#btn_submit_valid').prop('disabled', true);

        $('.resizeInput').each(function(){
            if ($(this).val() != '')
                $(this).css('width', (($(this).val().length) * 8)+'px');
        });
    });
	
	//Commented by Jay:
	/*$('table').on('click', 'input[id^=waive_rsd_document_chk]', function(){
        var m_names = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		//alert("test");
        var d = new Date();
        var curr_date = ('0' + d.getDate()).slice(-2);
        var mm = d.getMonth()+1; //January is 0!
        var curr_month = ('0' + mm).slice(-2);
        var curr_year = d.getFullYear(); //.toString().substr(-2);
        var curr_hour = (d.getHours()<10?'0':'') + d.getHours(); //('0' + d.getHours()).slice(-2);
        var curr_mins = (d.getMinutes()<10?'0':'') + d.getMinutes(); //('0' + d.getMinutes()).slice(-2);
        var curr_sec  = (d.getSeconds()<10?'0':'') + d.getSeconds(); //('0' + d.getSeconds()).slice(-2);
        // var new_date = curr_date + "-" + m_names[curr_month] + "-" + curr_year;
        var ampm = curr_hour >= 12 ? 'PM' : 'AM';
        curr_hour = curr_hour % 12;
        curr_hour = curr_hour ? curr_hour : 12; // the hour '0' should be '12'

        var new_date = curr_month + "/" + curr_date + "/" + curr_year + " " + curr_hour + ":" + curr_mins + ":" + curr_sec + " " + ampm;
        
        
        // set date to review date
        $("#rsd_document_review" + $(this).val()).closest('td').next().find(':input').val(new_date);
	
        $('.resizeInput').each(function(){
            if ($(this).val() != '')
                $(this).css('width', (($(this).val().length) * 8)+'px');
        });
    });*/
	
	//Commented by Jay:
	/*$('table').on('click', 'input[id^=waive_ad_document_chk]', function(){
        var m_names = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
		//alert("test");
        var d = new Date();
        var curr_date = ('0' + d.getDate()).slice(-2);
        var mm = d.getMonth()+1; //January is 0!
        var curr_month = ('0' + mm).slice(-2);
        var curr_year = d.getFullYear(); //.toString().substr(-2);
        var curr_hour = (d.getHours()<10?'0':'') + d.getHours(); //('0' + d.getHours()).slice(-2);
        var curr_mins = (d.getMinutes()<10?'0':'') + d.getMinutes(); //('0' + d.getMinutes()).slice(-2);
        var curr_sec  = (d.getSeconds()<10?'0':'') + d.getSeconds(); //('0' + d.getSeconds()).slice(-2);
        // var new_date = curr_date + "-" + m_names[curr_month] + "-" + curr_year;
        var ampm = curr_hour >= 12 ? 'PM' : 'AM';
        curr_hour = curr_hour % 12;
        curr_hour = curr_hour ? curr_hour : 12; // the hour '0' should be '12'

        var new_date = curr_month + "/" + curr_date + "/" + curr_year + " " + curr_hour + ":" + curr_mins + ":" + curr_sec + " " + ampm;
        
        
        // set date to review date
        $("#ra_document_review" + $(this).val()).closest('td').next().find(':input').val(new_date);
	
        $('.resizeInput').each(function(){
            if ($(this).val() != '')
                $(this).css('width', (($(this).val().length) * 8)+'px');
        });
    });*/
	
    $('table').on('click', '.reviewed_additional', function(){
        var m_names = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

        var d = new Date();
        var curr_date = ('0' + d.getDate()).slice(-2);
        var mm = d.getMonth()+1; //January is 0!
        var curr_month = ('0' + mm).slice(-2);
        var curr_year = d.getFullYear(); //.toString().substr(-2);
        var curr_hour = (d.getHours()<10?'0':'') + d.getHours(); //('0' + d.getHours()).slice(-2);
        var curr_mins = (d.getMinutes()<10?'0':'') + d.getMinutes(); //('0' + d.getMinutes()).slice(-2);
        var curr_sec  = (d.getSeconds()<10?'0':'') + d.getSeconds(); //('0' + d.getSeconds()).slice(-2);
        // var new_date = curr_date + "-" + m_names[curr_month] + "-" + curr_year;
        var ampm = curr_hour >= 12 ? 'PM' : 'AM';
        curr_hour = curr_hour % 12;
        curr_hour = curr_hour ? curr_hour : 12; // the hour '0' should be '12'

        var new_date = curr_month + "/" + curr_date + "/" + curr_year + " " + curr_hour + ":" + curr_mins + ":" + curr_sec + " " + ampm;

        var sysdate = $('#sysdate').data("rel"); 
			
		var x = new Date(sysdate);
		var dateStr =
			  x.getFullYear() + "-" + ("00" + (x.getMonth() + 1)).slice(-2) + "-" + ("00" + x.getDate()).slice(-2)
			   + " " +
			  ("00" + x.getHours()).slice(-2) + ":" + ("00" + x.getMinutes()).slice(-2) + ":" + ("00" + x.getSeconds()).slice(-2);

        // set date to review date
        $(this).closest('td').next().find(':input').val(dateStr);

        // check if all is check and enable request visit
		var status_id = $("#status_id").val();
		if(status_id != 10){
			if ($('.reviewed_additional:checked').length == $('.reviewed_additional').length)
				$('#btn_rf_visit').prop('disabled', false);
			else
				$('#btn_rf_visit').prop('disabled', true);
		}

        $('.resizeInput').each(function(){
            if ($(this).val() != '')
                $(this).css('width', (($(this).val().length) * 8)+'px');
        });
    });

    $('#btn_incomplete_reg_view').on('click', function(){ // parang reject
        //this is other field optional na sya
        // if ($('#rv_incomplete').val().trim() == '')
        // {
        //     $('#rv_incomplete').parent('div').addClass('has-error');
        //     modal_notify($("#myModal"),'Reason must not be empty!', 'danger');
        //     return;
        // }

        if (!validateForm('.cls_div_ir'))
        {
          var span_message = 'Please fill up all fields!';
          var type = 'modal_danger';
          modal_notify($("#myModal"), span_message, type);
		  
          return;
        }
		
		var has_others = false;
		$("[id*='cbo_inc_reason']").each(function(){  
			if ($('#' + $(this).attr('id') +  ' option:selected').attr('title').toLowerCase() == 'others'){
				has_others = true;
			}
		});
		
		if(has_others && $("#rv_incomplete").val().trim().length <= 0){
			$("#rv_incomplete").addClass('has-error-input');
			  var span_message = 'Please fill up all fields!';
			  var type = 'modal_danger';
			  modal_notify($("#myModal"), span_message, type);
			return
		}
		$("#rv_incomplete").removeClass('has-error-input');
		
        // validate duplicates on draft save
        var inputs=[], flag=false
        var duplicate_val; 

        $('.validate_dupli option:selected').each(function(){
            if ($.inArray(this.value, inputs) != -1){ 
                flag=true;
                duplicate_val=$(this).val();
            }
            inputs.push(this.value);
        });

        if (flag==true) {
            $('.validate_dupli option:selected').each(function(){
                if ($(this).val()==duplicate_val){
                   $(this).parent().closest('div').addClass('has-error');
                }
            });

            var span_message = 'Multiple Reason!';
            var type = 'modal_danger';
            modal_notify($("#myModal"), span_message, type);
            return;  
        }

        if (!hasSpecChar())
        {
            $('#rv_incomplete').parent('div').addClass('has-error');
            var span_message = 'Only alphanumeric characters allowed!';           
            modal_notify($("#myModal"),span_message, 'modal_danger');
            return;
        }
		
        var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="save_registration_review(4, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'modal_info';
        modal_notify($("#myModal"),span_message, type, true);
    });

    $('#btn_incomplete_sad').on('click', function(){ // save as draft ng reject isasave lang ung reason.
        //this is other field optional na sya
        // if ($('#rv_incomplete').val().trim() == '')
        // {
        //     $('#rv_incomplete').parent('div').addClass('has-error');
        //     modal_notify($("#myModal"),'Reason must not be empty!', 'danger');
        //     return;
        // }        

        if (!validateForm('.cls_div_ir'))
        {
          var span_message = 'Please fill up all fields!';
          var type = 'modal_danger';
          modal_notify($("#myModal"), span_message, type);
          return;
        }

        // validate duplicates on draft save
        var inputs=[], flag=false
        var duplicate_val; 

        $('.validate_dupli option:selected').each(function(){
            if ($.inArray(this.value, inputs) != -1){ 
                if($(this).val() != '')
                {
                    flag=true;
                    duplicate_val=$(this).val();
                }
            }
            inputs.push(this.value);
        });

        if (flag==true) {
            $('.validate_dupli option:selected').each(function(){
                if ($(this).val()==duplicate_val){
                   $(this).closest('div').addClass('has-error');
                }
            });

            var span_message = 'Multiple Reason!';
            var type = 'modal_danger';
            modal_notify($("#myModal"), span_message, type);
            return;  
        }

        if (!hasSpecChar())
        {
            $('#rv_incomplete').parent('div').addClass('has-error');
            var span_message = 'Only alphanumeric characters allowed!';           
            modal_notify($("#myModal"),span_message, 'modal_danger');
            return;
        }

        var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_incomplete_reason(this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'modal_info';
        modal_notify($("#myModal"),span_message, type, true);
    });

    $('#btn_req_visit').on('click', function(){ // parang approve
		if($('#rv_checkbox').prop("checked") == false){
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();
			 if(dd<10){
					dd='0'+dd
				} 
				if(mm<10){
					mm='0'+mm
				} 

			today = yyyy+'-'+mm+'-'+dd;

			var start   = $("#rv_txt_from").val();
			var end     = $("#rv_txt_to").val();
			
			if (start == '' || end == '')
			{
				if (start == '')
					$('#rv_txt_from').parent('div').addClass('has-error');
				else
					$('#rv_txt_from').parent('div').removeClass('has-error');
				if(end == '')
					$('#rv_txt_to').parent('div').addClass('has-error');
				else
					$('#rv_txt_to').parent('div').removeClass('has-error');

				var span_message = 'Date must not be empty!';
				
				if(start == ''){
					$("#rv_txt_from").val("");
				}
				
				if(end == ''){
					$("#rv_txt_to").val("");
				}
				
				var type = 'modal_danger';
				modal_notify($("#myModal"),span_message, type);
				return
			}

			if(Date.parse(today) > Date.parse(start)){
				var span_message = 'Invalid date range!';
				var type = 'modal_danger';
				modal_notify($("#myModal"),span_message, type);
				return;
			}

			if(Date.parse(start) > Date.parse(end)){
				var span_message = 'Invalid date range!';
				var type = 'modal_danger';
				modal_notify($("#myModal"),span_message, type);
			}
			else
			{
				var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="save_registration_review(3, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
				var type = 'modal_info';
				modal_notify($("#myModal"),span_message, type, true);
			}        
		}else{
			var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="save_registration_review(3, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
			var type = 'modal_info';
			modal_notify($("#myModal"),span_message, type, true);
		}
    });
    //################################################## Validation
    $('#btn_sad_valid').on('click', function(){ // parang reject
        var span_message = 'Are you sure you want to save? <button type="button" class="btn btn-success" onclick="save_registration_review(1, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);
    });

    $('#btn_submit_valid').on('click', function(){ // parang reject
        var span_message = 'Are you sure you want to submit? <button type="button" class="btn btn-success" onclick="save_registration_review(2, this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);
    });
    //################################# SUBMIT review
    $('#btn_sub_review').on('click', function(){ // submit for approval

        if (document.getElementById('cbo_tp').value == '')
        {
            $('#cbo_tp').parent('div').addClass('has-error');
            $('#cbo_tp').focus();
            var span_message = 'Please Fill Up Terms of Payment';
            var type = 'danger';
            notify(span_message, type);
            return;
        }
		
		var sid_temp = parseInt($("#status_id").val());
		var rsd_length = $("input[id^=waive_rsd_document_chk]").length;
		var checked_rsd = $("input[id^=waive_rsd_document_chk]:checked").length;
		
		if(sid_temp == 10){
			if(checked_rsd > 0 && $("#rsd_waive_remarks").val().trim().length <= 0){
				var span_message = 'Primary Requirement waive remarks is required.';
                var type = 'danger';
                notify(span_message, type);
				$("#rsd_waive_remarks").css("border-color","#ec6565");
				return;
			}else{
				$("#rsd_waive_remarks").css("border-color","");
			}
		}
        var span_message = 'Are you sure you want to continue? <button type="button" class="btn btn-success" onclick="submit_review(this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
        var type = 'info';
        notify(span_message, type, true);
    });
    //####################################################### Registration Review END

    //####################################################### Vendor Code Assignment START
    $('#save_vendor_code').on('click', function(){
        if (!validateForm())
        {
          var span_message = 'Please fill up all fields!';
          var type = 'danger';
          notify(span_message, type);
          return;
        }
        else
        {
            var alphanumers = /^[a-zA-Z0-9]+$/;
            if(!alphanumers.test($("#txt_vendor_code").val())){
                var span_message = 'Vendor Code can have only alphabets and numbers.';
                var type = 'danger';
                notify(span_message, type);
                return;
            }

            var span_message = 'Are you sure you want to continue? <button type="button" id = "btn_yes" class="btn btn-success" onclick="save_codeassign(this);" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
            var type = 'info';
            notify(span_message, type, true);
        }
    });
    //####################################################### Vendor Code Assignment END
    
    //####################################################### Change set of uploads Start
	var prev_selected_ownership = null;
    $("input[name='ownership']").on('click', function(){
        var ownership = this.value;
		var vendor_type = $('input:radio[name=vendor_type]:checked').val();
        var trade_vendor_type = '0';
		var registration_type = $("#registration_type").val();
		if(prev_selected_ownership != this.value) {
			// Reset Primary and Addtional Upload Count
			$('#ra_upload_count').val(0);
			$('#rsd_upload_count').val(0);
		}
		
		if(vendor_type == 1){
			trade_vendor_type = $("input[name='trade_vendor_type']:checked").val();
		}
        /*if ($("input[name='trade_vendor_type']:checked").length > 0){
            trade_vendor_type = 1; //$("input[name='trade_vendor_type']:checked").val();
		}else{
			
            trade_vendor_type = $("input[name='vendor_type']:checked").val();
			
			if(trade_vendor_type == 3){
				trade_vendor_type = 4; // 4 = NTS 
			}
		}*/

        let cat_sup = [];
        $('#cat_sup input[type=hidden]').each(function(){
            cat_sup.push(this.value);
        });
			
		// Added MSF - 20191118 (IJR-10618)
        let sub_cat_sup = [];
        $('#cat_sup input[type=hidden]').each(function(){
            sub_cat_sup.push(this.value);
        });
/*        console.log(x);
        return;*/
        
        get_list_docs(ownership, trade_vendor_type,cat_sup, vendor_type, registration_type);
		
		prev_selected_ownership=this.value;
    });

    $("input[name='trade_vendor_type']").on('click', function(){
        var ownership = '';
		var vendor_type = $('input:radio[name=vendor_type]:checked').val();
        var trade_vendor_type = this.value;

        if ($("input[name='ownership']:checked").length > 0)
            ownership = $("input[name='ownership']:checked").val();
        
        get_list_docs(ownership, trade_vendor_type, '', vendor_type);
    });
    //####################################################### Change set of uploads END

    $('#tbl_opd').on('click', '.cls_del_opd', function(){
        var count = $('#opd_count').val();
        if (count > 1)
        {
           $(this).closest('tr').remove(); 
            reset_ids('cls_tr_opd','opd_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_opd','opd_count',i,'tr_opd');
            } 
        }
        
    });

    $('#tbl_authrep').on('click', '.cls_del_authrep', function(){
        var count = $('#authrep_count').val();
        if (count > 1)
        {
           $(this).closest('tr').remove(); 
            reset_ids('cls_tr_authrep','authrep_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_authrep','authrep_count',i,'tr_authrep');
            } 
        }
        
    });

    $('#tbl_bankrep').on('click', '.cls_del_bankrep', function(){
        var count = $('#bankrep_count').val();
        if (count > 1)
        {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_bankrep','bankrep_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_bankrep','bankrep_count',i,'tr_bankrep');
            }
        }
        
    });

    $('#tbl_orcc').on('click', '.cls_del_orcc', function(){
        var count = $('#orcc_count').val();
        if (count > 1)
        {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_orcc','orcc_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_orcc','orcc_count',i,'tr_orcc');
            }
        }
        
    });

    $('#tbl_otherbusiness').on('click', '.cls_del_otherbusiness', function(){
        var count = $('#otherbusiness_count').val();
        if (count > 1)
        {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_ob','otherbusiness_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_otherbusiness','otherbusiness_count',i,'tr_otherbusiness');
            }
        }
        
    });

    $('#tbl_affiliates').on('click', '.cls_del_affiliates', function(){
        var count = $('#affiliates_count').val();
        if (count > 1)
        {
            $(this).closest('tr').remove(); 
            reset_ids('cls_tr_affiliates','affiliates_count'); // reset id of tr
            
            //update ids of child
            for (var i = 1; i <= count; i++)
            {
                reset_ids('cls_affiliates','affiliates_count',i,'tr_affiliates');
            }
        }
        
    });

    $("div").on('input', '.cls_city, .cls_state, .cls_country', function () { // .cls_div_office_addr, .cls_div_factory_addr, .cls_div_wh_addr //.cls_city, .cls_state, .cls_country
        var val = this.value;
        var id_num = parseInt($(this).attr('id').replace(/[^\d]/g, ''), 10); // retain number
        var el_name = $(this).attr('name').replace(/\d+/g, ''); // remove numbers

        if ($( this ).hasClass( "cls_city" ))
        {
            if($('#city_list option').filter(function(){
                return this.value === val;        
            }).length) {
                var new_val = $("#city_list option[value='"+val+"']").data('cityid');               
                $('#'+el_name+'_id'+id_num).val(new_val);
            }
            else
            {
                $('#'+el_name+'_id'+id_num).val('');
            }
        }
        else if ($( this ).hasClass( "cls_state" ))
        {
            if($('#state_list option').filter(function(){
                return this.value === val;        
            }).length) {
                var new_val = $("#state_list option[value='"+val+"']").data('stateid');               
                $('#'+el_name+'_id'+id_num).val(new_val);
            }
            else
            {
                $('#'+el_name+'_id'+id_num).val('');
            }
        }
        else if ($( this ).hasClass( "cls_country" ))
        {
            if($('#country_list option').filter(function(){
                return this.value === val;        
            }).length) {
                var new_val = $("#country_list option[value='"+val+"']").data('countryid');               
                $('#'+el_name+'_id'+id_num).val(new_val);
            }
            else
            {
                $('#'+el_name+'_id'+id_num).val('');
            }
        }

    });

    $("div").on('focus', '.cls_country', function () {
        if ($( this ).val() != '')
            $( this ).val('');
    });

    $('#nob_others').on('click', function(){
        if ($('#nob_others').is(':checked'))
        {
            $('#txt_nob_others').prop('disabled', false);
            $('#txt_nob_others').select();
        }
        else
        {
            $('#txt_nob_others').prop('disabled', true);
            $('#txt_nob_others').val('');
        }
    });

    // $('#chk_certify').on('click', function(){
    //     if ($('#chk_certify').is(':checked'))
    //         $('#btn_submit').prop('disabled', false);
    //     else
    //         $('#btn_submit').prop('disabled', true);
    // });
   
});
