function change_border(id)
{
    $('#'+id).parent('div').removeClass('has-error');
    $('#'+id).css('border', '');
}

function submit_load()
{
    var selected = document.getElementById('rfq_main_hidden_selected').value;

    if(selected == "1")
    {
        $('#myModal2').modal('toggle');
    }
    else
    {

        if(document.getElementById('find_rfq').value == '' || document.getElementById('find_rfq').value == "")
        {
            var span_message = 'Please type the complete title of the RFQ that you want to copy.';
            var type = 'modal_danger';
            modal_notify("myModal2", span_message, type);
            return;
        }

        if(window.XMLHttpRequest)
            xmlhttp = new XMLHttpRequest();
        else
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        xmlhttp.onreadystatechange = function(){

            if(xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                //on 200=done,
                var data = $.parseJSON(xmlhttp.responseText);

                if(data.line_data_count > 0)
                {
                    if(data.line_data_count > 1)
                    {

                            add_delete_lines_generation(1, data.line_data_count).done(function(){
                                fill_rfq(xmlhttp.responseText);
                            });

                    }
                    else
                    {
                                fill_rfq(xmlhttp.responseText);

                    }
                }
                else
                {
                    var span_message = 'No result found';
                    var type = 'modal_danger';
                    modal_notify("myModal2", span_message, type);
                    return;

                }
            }
        }

        path_value = BASE_URL + 'rfqb/rfq_main/search_rfq';

        parameter = 'selected='+selected+
                            '&find_rfq='+encodeURIComponent(document.getElementById('find_rfq').value);

        xmlhttp.open("POST", path_value, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.send(parameter);
    }

}

function fill_rfq(olddata)
{
    var data = $.parseJSON(olddata);
    //console.log(data);
    // upper part
    //$('#title_txt').val(data.main[0].TITLE);
    $('#type_radio').val(data.main[0].RFQRFB_TYPE);

    if(data.main[0].RFQRFB_TYPE == 1)
        $('#qualified').prop('checked', true);
    else
        $('#competitive').prop('checked', true);

    $('#currency').val(data.main[0].CURRENCY_ID);

    //this is for sm view only
    $('#requestor').val(data.main[0].REQUESTOR_ID);
    $('#purpose').val(data.main[0].PURPOSE_ID);
    $('#purpose_txt').val(data.main[0].OTHER_PURPOSE);
    $('#reason').val(data.main[0].REASON_ID);
    $('#reason_txt').val(data.main[0].OTHER_REASON);
    $('#internal_note').val(data.main[0].INTERNAL_NOTE);

    //change color border of upper part
    $('#currency').css('border', '1px solid #0000ff');
    $('#requestor').css('border', '1px solid #0000ff');
    $('#purpose').css('border', '1px solid #0000ff');
    $('#internal_note').css('border', '1px solid #0000ff');

    if(data.main[0].PURPOSE_ID == -1)
        $('#purpose_txt').css('border', '1px solid #0000ff');

    $('#reason').css('border', '1px solid #0000ff');

    if(data.main[0].OTHER_REASON == -1)
        $('#reason_txt').css('border', '1px solid #0000ff');

    //lines displaying
    if(data.line_data_count > 0)
    {

        var a = '';
        var b = '';
        var path = '';
        var url = BASE_URL.replace("index.php/", "");

        for(x=0, j=1; x < data.line_data_count; x++,j++)
        {
            var c = 0;
            //display attachment
            if(data.attachment_data_count > 0)
            {
                a = data.line_data[x].RFQRFB_LINE_ID;
                // put data
                $('#line_category'+j).val(data.line_data[x].CATEGORY_ID);
                $('#line_description'+j).val(data.line_data[x].DESCRIPTION);
                $('#line_measuring_unit'+j).val(data.line_data[x].UNIT_OF_MEASURE);
                var quantity_value = numberWithCommas(data.line_data[x].QUANTITY);
                $('#quantity'+j).val(quantity_value);
                $('#specs'+j+'_text').val(data.line_data[x].SPECIFICATION);

                //change color
                $('#line_category'+j).css('border', '1px solid #0000ff');
                $('#line_description'+j).css('border', '1px solid #0000ff');
                $('#line_measuring_unit'+j).css('border', '1px solid #0000ff');
                $('#quantity'+j).css('border', '1px solid #0000ff');
                $('#specs'+j+'_text').css('border', '1px solid #0000ff');

                if(a != b)// for 1 display per lineid query result only
                {
                    for(r=0, s=1; r < data.attachment_data_count; r++, s++)
                    {
                        var doc_pic = '';
                        if(data.attachment_data[r].ATTACHMENT_TYPE == 3)
                            doc_pic= data.attachment_data[r].FILE_PATH;
                        else
                            doc_pic= data.attachment_data[r].LOGO_PATH;

                        path =  data.attachment_data[r].RFQRFB_LINE_ID;
                        if(a == path) // to make sure that only attachment for this lineid will display only
                        {
                            c++; // c for for the count of attachment displayed
                            $('#attachment'+j).css('display', 'inherit');
                            $('#attachment'+j+'_'+s).css('display', 'inline-block');
                            $('#attachment'+j+'_'+s).addClass('dv_attachment');

                            $('#image'+j+'_'+s).attr('src', url+doc_pic);
                            $('#line_attachment_id_'+j+'_'+s).val(data.attachment_data[r].LINE_ATTACHMENT_ID);
                            $('#hidden_path_'+j+'_'+s).val(data.attachment_data[r].FILE_PATH);
                            $('#attachment_desc_'+j+'_'+s).val(data.attachment_data[r].A_DESCRIPTION);
                            $('#attachment_type_'+j+'_'+s).val(data.attachment_data[r].ATTACHMENT_TYPE);
                            document.getElementById('attachment_count'+j).innerHTML = c;

                            if(c > 0)
                                $('#delete_attachment'+j).prop('disabled', false);

                            //put checkbox and ddesctiption
                            document.getElementById('chkbx_line'+j+'_'+s).innerHTML = '<input type="checkbox" id="chkbox_'+j+'_'+s+'" name="chkbox_'+j+'_'+s+'" onchange="invitecheck(this.checked, \''+j+'_'+s+'\', \'checkbox_attachment_\')">'+data.attachment_data[r].A_DESCRIPTION+'</input>';
                        }
                         if(s >= 8)
                                s = 1;
                    }
                    b = a;
                }
            }
        }
    }
    // end of lines

    // start of putting data in invited vendors
    if(data.invited_data_count > 0)
    {
        var cell = '';
        for(i=0, y=1; i < data.invited_data_count; i++, y++)
        {
            cell += ' <tr>'+
                        '<td><input type="checkbox" name="transfered_invited'+y+'" id="transfered_invited'+y+'" value="'+y+'" onchange="invitecheck(this.checked, '+y+', \'final_invited_chkbx\')"></td>'+
                        '<td>'+data.invited_data[i].VENDOR_NAME+
                            '<input type="hidden" name="vendorinvitefinal_id'+y+'" id="vendorinvitefinal_id'+y+'" value='+data.invited_data[i].VENDOR_ID+'>'+
                            '<input type="hidden" name="vendorfinal_invite_id'+y+'" id="vendorfinal_invite_id'+y+'" value='+data.invited_data[i].INVITE_ID+'>'+
                            '<input type="hidden" name="vendorname_finalinvited'+y+'" id="vendorname_finalinvited'+y+'" value="'+data.invited_data[i].VENDOR_NAME+'">'+
                            '<input type="hidden" name="final_invited_chkbx'+y+'" id="final_invited_chkbx'+y+'" value=0>'+
                        '</td>'+
                    '</tr>';
        }
        var table =    '<input type="hidden" name="count_all_invited" id="count_all_invited" value="'+data.invited_data_count+'">'+
                        '<table class="table">'+
                            '<thead>'+
                                '<div class="col-md-2">'+
                                    '<th>'+
                                        '<input type="checkbox" name="check_all_vendor" id="check_all_vendor" value="all" onchange="check_all_invited_vendor(this.checked)">'+
                                            'Select'+
                                    '</th></div><div class="col-md-10"><th>Vendor</th>'+
                                '</div></thead><tbody>'+cell+'</tbody></table>';

        //document.getElementById("selected_invited_vendor").innerHTML = 'asd';
        document.getElementById('selected_invited_vendor').innerHTML = table;
    }
    //end of invited vendors
    $('#myModal2').modal('toggle');

}

function new_attachment_pic()
{

    if ((document.getElementById('modal_row_attachment').value == '')||(document.getElementById('modal_txt_description').value == ''))
    {
        var span_message = 'Please fill up all fields.';
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        return;
    }

    var message = '';
    if(hasSpecChar_single('modal_txt_description') == false)
    {
        message += 'Description';
    }

    // if(message != '')
    // {
        // var span_message = 'Special characters is not allowed.';
        // var type = 'danger';
        // modal_notify("myModal", span_message, type);
        // return;
    // }

    if(document.getElementById('upload_attachment').length == 0)
    {
        var span_message = 'Please fill up all fields.';
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        return;
    }

    row = document.getElementById('modal_row_attachment').value;

    var count = 8;
    var col=0;

    /*for(i=1; i<=count; i++)
    {
        if(document.getElementById('hidden_path_'+row+'_'+i).value == 0)
        {
            col = i;
            break;
        }
    }  */
    col = document.getElementById('col').value;

    if(document.getElementById("upload_attachment").files.length == 0)
    {
        var span_message = 'Please upload an attachment.';
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        document.getElementById("modal_alert_warning").style.display = "inline";

        return;
    }

    if (validate_attachment_rfq() == -1)
    {
        var span_message = 'Please select the correct file format.';
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        document.getElementById("modal_alert_warning").style.display = "inline";

        return;
    }

    surl = BASE_URL + 'rfqb/rfq_main/select_attachment';
    file_data = $('#upload_attachment').prop('files')[0];
    upload_ajax_modal(document.form1, surl).done(function(responseText) {
        document.getElementById('attachment'+row+"_"+col).innerHTML = responseText;

		var attachment_type = document.getElementById('attachment_type_'+row+"_"+col);
		if (attachment_type) {
			if (attachment_type.value=="1"){
				var hidden_bom_attach = document.getElementById('hidden_bom_attach'+row);
				if (hidden_bom_attach){
					hidden_bom_attach.value = col;
				}
			}
		}

        document.getElementById('attachment_count'+row).innerHTML = '<input type="hidden" name="att_cnt'+row+'" id="att_cnt'+row+'" value='+col+'>'+col;

        if(col == 8)
            $('#add_attachment'+row).prop('disabled', true);

        var span_message = 'Attachment successfully uploaded.';
        var type = 'modal_success';
        modal_notify("myModal",span_message, type);

        setTimeout(function(){
        $('#myModal').modal('toggle');
        }, 2000);
    });
    //$('#myModal').modal('toggle'); // display response from the PHP script, if any

    $('#delete_attachment'+row).prop('disabled', false);

    document.getElementById('attachment'+row+"_"+col).style.display = 'inline-block';
    $('#attachment'+row+"_"+col).addClass('dv_attachment');

    return;

}

function validate_attachment_rfq()
{

    var extension = '';

    if(document.getElementById('modal_cbo_attachmenttype').value == 1) // BOM
        extension = 'xlsx';
    else if(document.getElementById('modal_cbo_attachmenttype').value == 2) // excel
        extension = 'xlsx';
    else if(document.getElementById('modal_cbo_attachmenttype').value == 3) // jpg
        extension = 'jpg';
    else if(document.getElementById('modal_cbo_attachmenttype').value == 4) // pdf
        extension = 'pdf';
    else if(document.getElementById('modal_cbo_attachmenttype').value == 5) // word
        extension = 'docx';

    full_path = document.getElementById('upload_attachment').value;

    var filename = full_path.substr(full_path.lastIndexOf('\\')+1); // get upload file name
    var str_file_ext = filename.substr(-4, 4);

    filename = filename.toLowerCase();
    str_file_ext = str_file_ext.toLowerCase();

    isfound = filename.lastIndexOf(extension.toLowerCase()); // if -1 is the return meaning the word is not found in filename

    return isfound;
}

function attachmentview(row)
{
    if (document.getElementById('attach'+row).value == 0)
    {
        document.getElementById('attach'+row).value = 1;

        // add code here for show next tr
        document.getElementById('attachment'+row).style.display = 'inherit';
        document.getElementById('add_attachment'+row).style.display = 'inline';
        document.getElementById('delete_attachment'+row).style.display = 'inline';
    }
    else
    {
        document.getElementById('attach'+row).value = 0;

        // add code here for hide next tr
        document.getElementById('attachment'+row).style.display = 'none';
        document.getElementById('add_attachment'+row).style.display = 'none';
        document.getElementById('delete_attachment'+row).style.display = 'none';
    }
    return;
}

function add_delete_lines(type)
{
    var max_lines = document.getElementById('max_lines').value;

    if (type == 1){// add
        next_col = Number(max_lines) + 1;
    }else if(type == 0){ // minus
        next_col = Number(max_lines) - 1;
    }
    else{
        next_col = Number(max_lines)
    }

    document.getElementById("max_lines").value = next_col;

   if (next_col == 1) // minimun
        document.getElementById('del_btn').disabled = true;
    else
        document.getElementById('del_btn').disabled = false;

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("lines_data_rfx").innerHTML = xmlhttp.responseText;
        }
    }
    path_value = BASE_URL + 'rfqb/rfq_main/add_lines';

    parameter = 'max_lines='+document.getElementById('max_lines').value+
                '&count='+max_lines;

    for(i=1;i <= max_lines; i++)
    {
        parameter += '&line_category'+i+'='+document.getElementById('line_category'+i).value+
                    '&line_description'+i+'='+document.getElementById('line_description'+i).value+
                    '&line_measuring_unit'+i+'='+document.getElementById('line_measuring_unit'+i).value+
                    '&quantity'+i+'='+document.getElementById('quantity'+i).value+
                    '&specs'+i+'_text='+document.getElementById('specs'+i+'_text').value;

        for(x=1; x <= 8; x++)
        {
            parameter += '&hidden_path_'+i+'_'+x+'='+document.getElementById('hidden_path_'+i+'_'+x).value+
                         '&attachment_desc_'+i+'_'+x+'='+document.getElementById('attachment_desc_'+i+'_'+x).value+
                         '&line_attachment_id_'+i+'_'+x+'='+document.getElementById('line_attachment_id_'+i+'_'+x).value+
                         '&attachment_type_'+i+'_'+x+'='+document.getElementById('attachment_type_'+i+'_'+x).value;
        }
    }

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function add_attachment(row)
{
    var category     = document.getElementById("line_category"+row).value;

    var col=0;
    var count = 8;

    for(i=1; i<=count; i++)
    {
        if(document.getElementById('hidden_path_'+row+'_'+i).value == 0)
        {
            col = i;
            break;
        }
    }

   $('#myModal').modal({
        backdrop: 'static',
        keyboard: false
    })
   $('#myModal').modal('show');

    var url = BASE_URL + 'rfqb/rfq_main/new_attachment';

    $.post(
        url,
        {
            category            : category,
            row                 : row,
            col                 : col
        },
        function(responseText) {
            $("#view_modal").html(responseText);

        },
        "html"
    );
}

function add_delete_lines_generation(type, max_lines)
{

    document.getElementById("max_lines").value = max_lines;

   if (max_lines == 1) // minimun
        document.getElementById('del_btn').disabled = true;
    else
        document.getElementById('del_btn').disabled = false;

    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/rfq_main/add_lines_generation";

    var post_params = 'max_lines='+max_lines;


    var success_function = function(responseText)
    {
        document.getElementById("lines_data_rfx").innerHTML = responseText;
    };

    return ajax_request(ajax_type, url, post_params, success_function);

}

function add_another_bom_quote(row,line_attachment_id,attachment_type,current_currency_data)
{

    var url = BASE_URL + 'rfqb/rfq_response_creation/add_another_bom_quote';
    categoryname = $('#categoryname'+row).val();
	var quote_num = 1;
	if ($("#num_quote"+row.length)) {
		quote_num = parseInt($("#num_quote"+row).val());
	}

    $.post(
        url,
        {
            categoryname     		: categoryname,
            line_attachment_id  	: line_attachment_id,
            attachment_type     	: attachment_type,
            current_currency_data	: current_currency_data,
            row 	         		: row,
            quote_num 	         	: quote_num
        },
        function(responseText) {
			if (!$("#view_bom_modal_"+line_attachment_id+"_"+(quote_num+1)).length) {
				$("#quote_bom_container_"+row).append(responseText);
			}

			add_another_quote(row,line_attachment_id,attachment_type,current_currency_data);
        },
        "html"
    );
}

function add_another_quote(row,line_attachment_id,attachment_type,current_currency_data)
{
	$('#myModal').modal({
		backdrop: 'static',
		keyboard: false
	})

	$('#myModal').modal('show');
	var quote_num = 1;
	if ($("#num_quote"+row.length)) {
		quote_num = parseInt($("#num_quote"+row).val());
	}

    var url = BASE_URL + 'rfqb/rfq_response_creation/add_another_quote';
    categoryname = $('#categoryname'+row).val();
    $.post(
        url,
        {
            categoryname     		: categoryname,
            line_attachment_id  	: line_attachment_id,
            attachment_type     	: attachment_type,
            current_currency_data	: current_currency_data,
            row 	         		: row,
            quote_num 	         	: quote_num
        },
        function(responseText) {
            $("#view_modal").html(responseText);
			if ($("#view_bom_modal_"+line_attachment_id+"_"+(quote_num+1)).length) {
				$("#btn_view_modal_"+line_attachment_id+"_"+(quote_num+1)).css("display", "inline");
				if ($("#modal_txt_price").length) {
					$("#modal_txt_price").attr("disabled", true);
				}
			}

        },
        "html"
    );
}

function purpose_select(id)
{
    if (id == '-1')
    {
        $('#purpose_txt').addClass('field-required');
        document.getElementById('purpose_txt').disabled = false;
    }
    else if(id != '-1')
    {
        $('#purpose_txt').removeClass('field-required');
        $('#purpose_txt').parent('div').removeClass('has-error');
        document.getElementById('purpose_txt').disabled = true;
    }
    else
    {
        $('#purpose_txt').removeClass('field-required');
        document.getElementById('purpose_txt').disabled = true;
    }
}

function reason_select(id)
{
    if (id == '-1')
    {
        $('#reason_txt').addClass('field-required');
        document.getElementById('reason_txt').disabled = false;
    }
    else if(id != '-1')
    {
        $('#reason_txt').removeClass('field-required');
        $('#reason_txt').parent('div').removeClass('has-error');
        document.getElementById('reason_txt').disabled = true;
    }
    else
    {
        $('#reason_txt').removeClass('field-required');
        document.getElementById('reason_txt').disabled = true;
    }
}

function add_new_invite()
{
    var count_invited;
    count_invited = document.getElementById('count_all_invited').value;
    var message = '';

    if($('#txt_vendorname').val() == '')
    {
        $('#txt_vendorname').parent('div').addClass('has-error');
        message += 'Vendor Name ';
    }
    else
        $('#txt_vendorname').removeClass('field-required');

    if($('#txt_contact_person').val() == '')
    {
        if (message != '')
            message += 'and '

        $('#txt_contact_person').parent('div').addClass('has-error');
        message += 'Contact Name ';
    }
    else
        $('#txt_contact_person').removeClass('field-required');


    if($('#txt_email').val() == '')
    {
        if (message != '')
            message += 'and '

        $('#txt_email').parent('div').addClass('has-error');
        message += 'Email Address ';
    }
    else
        $('#txt_email').removeClass('field-required');


    if (message != '')
    {
        var span_message = 'Please Fill Up '+message;
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    if(hasSpecChar() == false)
    {
        var span_message = 'Special characters is not allowed.';
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

    validate_duplicate('VENDOR', document.getElementById('txt_vendorname').value).done(function(data){


        if(data == 1)
        {
            $('#txt_vendorname').parent('div').addClass('has-error');
            var span_message = 'Duplicate found.';
            var type = 'danger';
            notify(span_message, type);
            return;

        }
        else
        {
            $('#txt_vendorname').parent('div').removeClass('has-error');
            if (XMLHttpRequest)
                xmlhttprequest = new XMLHttpRequest();
            else
                xmlhttprequest = new ActiveXObject("Microsoft.HMLHTTP");

            xmlhttprequest.onreadystatechange = function()
            {
                if (xmlhttprequest.readyState == 4 && xmlhttprequest.status == 200)
                {
                    document.getElementById("selected_invited_vendor").innerHTML = xmlhttprequest.responseText;
                    document.getElementById("new_vendor_div").style.display = 'none';
                    $('#txt_vendorname').removeClass('field-required');
                    $('#txt_contact_person').removeClass('field-required');
                    $('#txt_email').removeClass('field-required');

                    document.getElementById("txt_vendorname").value = '';
                    document.getElementById("txt_contact_person").value = '';
                    document.getElementById("txt_email").value = '';

                }
            }
            parameter = 'vendorname='+document.getElementById('txt_vendorname').value+
                        '&vendorcontact='+ document.getElementById('txt_contact_person').value+
                        '&email='+ document.getElementById('txt_email').value+
                        '&count_all_invited='+document.getElementById('count_all_invited').value;

            for(i=1; i<=count_invited; i++)
            {
                parameter += '&vendorinvitefinal_id'+i+'='+document.getElementById('vendorinvitefinal_id'+i).value+
                             '&vendorname_finalinvited'+i+'='+document.getElementById('vendorname_finalinvited'+i).value+
                             '&vendorfinal_invite_id'+i+'='+document.getElementById('vendorfinal_invite_id'+i).value+
                             '&final_invited_chkbx'+i+'='+document.getElementById('final_invited_chkbx'+i).checked;
            }

            path_value = BASE_URL + 'rfqb/rfq_main/add_new_invite';

            xmlhttprequest.open("POST", path_value, true);
            xmlhttprequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttprequest.send(parameter);
        }

    });


}

function validate_duplicate(table, value, id = 0)
{
    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/rfq_main/validate_duplicate/";

    var post_params =   'table='+table+
                        '&value='+encodeURIComponent(value)+
                        '&id='+id;

    var success_function = function(responseText)
    {
        return responseText;
    };

    return ajax_request(ajax_type, url, post_params, success_function);
}


function submit_creation(type)
{

    var status_id = 21;

    if (type == 1)
        status_id = 21;
    else
        status_id = 20;

    $('#submitbtn').prop('disabled', true);
    $('#draftbtn').prop('disabled', true);

    if (window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
			var responseJson = null;

			try {
				responseJson = JSON.parse(xmlhttp.responseText);
			} catch (err) {
				console.log(err);
			}

            var span_message = 'Saving failed';
            var res_type = 'danger';

			if (responseJson != null &&  responseJson.parse_bom !=null ){
                if (responseJson.parse_bom.STATUS){
                    span_message = 'Data succesfully submitted';
                    res_type = 'success';
					notify(span_message, res_type);

					setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

                } else {
                    span_message = 'Data saved as draft, failed to parse BOM Template ' + responseJson.parse_bom.MESSAGE;
                    res_type = 'danger';
					notify(span_message, res_type);

					$('#submitbtn').prop('disabled', false);
					$('#draftbtn').prop('disabled', false);
			    }
			} else {
				span_message = 'Data saved succesfully';
				res_type = 'success';
				notify(span_message, res_type);
				setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
			}

			// parse_bom_templates();

        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/submit_rfq_creation';

    parameter = 'title_txt='+encodeURIComponent(document.getElementById('title_txt').value)+
                '&type_radio='+document.getElementById('type_radio').value+
                '&currency='+document.getElementById('currency').value+
                '&pref_delivery_date='+document.getElementById('pref_delivery_date').value+
                '&sub_deadline_date='+document.getElementById('sub_deadline_date').value+
                '&requestor='+document.getElementById('requestor').value+
                '&purpose='+document.getElementById('purpose').value+
                '&purpose_txt='+encodeURIComponent(document.getElementById('purpose_txt').value)+
                '&reason='+document.getElementById('reason').value+
                '&reason_txt='+encodeURIComponent(document.getElementById('reason_txt').value)+
                '&internal_note='+encodeURIComponent(document.getElementById('internal_note').value)+
                '&rfq_id='+document.getElementById('rfq_id').value+
                '&draft_validation='+document.getElementById('draft_validation').value+
                '&status_id='+status_id+
                '&type='+type+
                '&total_lines='+document.getElementById('max_lines').value;

	var cnt_bom = 0;
	var cnt_bom_lines = 0;
    for(i=1;i<=document.getElementById('max_lines').value; i++)
    {
        parameter += '&line_category'+i+'='+document.getElementById('line_category'+i).value+
                    '&line_description'+i+'='+encodeURIComponent(document.getElementById('line_description'+i).value)+
                    '&line_measuring_unit'+i+'='+document.getElementById('line_measuring_unit'+i).value+
                    '&quantity'+i+'='+document.getElementById('quantity'+i).value+
                    '&specs'+i+'_text='+encodeURIComponent(document.getElementById('specs'+i+'_text').value);

        for(x=1; x <= 8; x++)
        {
            parameter += '&hidden_path_'+i+'_'+x+'='+encodeURIComponent(document.getElementById('hidden_path_'+i+'_'+x).value)+
                         '&attachment_desc_'+i+'_'+x+'='+encodeURIComponent(document.getElementById('attachment_desc_'+i+'_'+x).value)+
                         '&line_attachment_id_'+i+'_'+x+'='+document.getElementById('line_attachment_id_'+i+'_'+x).value+
                         '&attachment_type_'+i+'_'+x+'='+document.getElementById('attachment_type_'+i+'_'+x).value;

        }
    }

    for(i=1;i<=document.getElementById('count_all_invited').value; i++)
    {
        parameter += '&count_all_invited='+document.getElementById('count_all_invited').value+
                     '&vendorinvitefinal_id'+i+'='+document.getElementById('vendorinvitefinal_id'+i).value+
                     '&vendorfinal_invite_id'+i+'='+document.getElementById('vendorfinal_invite_id'+i).value;
    }

    //console.log(parameter);

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function cancel_bom_changes(line_attachment_id, quote_no){
	var bom_lines_cnt = document.getElementsByName('line_bom_cost_'+line_attachment_id+'_q_'+quote_no).length;
	for(line=0;line<bom_lines_cnt; line++) {
		var hidden = document.getElementById('hidden_line_bom_cost_'+line_attachment_id+'_'+line+'_'+quote_no);
		var cost = document.getElementById('line_bom_cost_'+line_attachment_id+'_'+line+'_'+quote_no);
		if (hidden!=null && cost!=null) {
			cost.value = hidden.value;
		}
		computeTotal(line_attachment_id,  quote_no);
	}
}

function computeTotal(line_attachment_id, quote_no){
	var items = document.getElementsByName("line_bom_cost_" + line_attachment_id.toString() + "_q_" + quote_no.toString());
	var sum = 0.00;
	var sum_box = document.getElementById("line_total_" + line_attachment_id.toString() + "_" + quote_no.toString());
	if (items!=null) {
		for (i = 0; i < items.length; i++) {
			var val = $("#"+items[i].id).val();
			if (val!=null)
				sum += parseFloat(val.replace(/,/i, ""));
		}
		sum_box.value = $.number(sum,2,'.',',');
	}

}
function apply_bom_changes(line_attachment_id, line_no, quote_no){
	var bom_lines_cnt = document.getElementsByName('line_bom_cost_'+line_attachment_id+'_q_'+quote_no).length;
	var sum_box = document.getElementById("line_total_" + line_attachment_id.toString() + "_" + quote_no.toString());
	var price_box = document.getElementById("txt_quote" + line_no.toString() + "_" + quote_no.toString());
	var price_modal = document.getElementById("modal_txt_price");
	for(line=0;line<bom_lines_cnt; line++) {
    var hidden_remarks = document.getElementById('hidden_line_bom_remarks_'+line_attachment_id+'_'+line+'_'+quote_no);
    var hidden = document.getElementById('hidden_line_bom_cost_'+line_attachment_id+'_'+line+'_'+quote_no);
    var cost = document.getElementById('line_bom_cost_'+line_attachment_id+'_'+line+'_'+quote_no);
    var remarks = document.getElementById('line_bom_remarks_'+line_attachment_id+'_'+line+'_'+quote_no);
    if (hidden!=null && cost!=null) {
			hidden.value = cost.value;
		}
    if (hidden_remarks!=null && remarks!=null) {
			hidden_remarks.value = remarks.value;
		}
	}
	computeTotal(line_attachment_id, quote_no);
	if (price_box!=null) {
		price_box.value = sum_box.value;
	}
	if (price_modal!=null) {
		price_modal.value = sum_box.value;
	}
	quote_value(line_no+'_'+quote_no);
	// no_quote(line_no+'_'+quote_no);
}


function generate_bom_pdf_v2(url, line_attachment_id, title){
	document.form1.action = url + "index.php/rfqb/rfq_short_list/generate_pdf_v2/" + line_attachment_id.toString() + "/" + title;
	document.form1.target = "_self";
	document.form1.submit();
}

function validateBOM(line_attachment_id, line_no, quote_no){
	if (!validateDecimal()) {
		var span_message = 'Invalid input.';
		var res_type = 'modal_danger';
		modal_notify("view_bom_modal_"+line_attachment_id+"_"+quote_no,span_message, res_type);
	} else {
		apply_bom_changes(line_attachment_id, line_no, quote_no);
		$("#view_bom_modal_"+line_attachment_id+"_"+quote_no).modal('toggle');
	}
}

function submit_line_bom_cost(x, i)
{


    btn =$("#approve_btn");
    loading($(btn), 'in_progress');

    // $('#submit_cost_btn').prop('disabled', true);
    // $('#cancel_btn').prop('disabled', true);

    if (window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
			var responseJson = JSON.parse(xmlhttp.responseText);


            var span_message = 'Saving failed';
            var res_type = 'success';


            notify(span_message, res_type);

			// parse_bom_templates();


			setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
            loading($(btn), 'done');

        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/submit_line_bom_cost';

    parameter = '';


	// parameter += '&hidden_path_'+x+'_'+i+'='+document.getElementById('hidden_path_'+x+'_'+i).value+
				 // '&attachment_desc_'+x+'_'+i+'='+document.getElementById('attachment_desc_'+x+'_'+i).value+
				 // '&line_attachment_id_'+x+'_'+i+'='+document.getElementById('line_attachment_id_'+x+'_'+i).value+
				 // '&attachment_type_'+x+'_'+i+'='+document.getElementById('attachment_type_'+x+'_'+i).value;
	var bom_lines_cnt = document.getElementsByName('line_bom_cost_'+x+'_'+i).length;
	parameter += '&bom_lines_cnt='+bom_lines_cnt;
	for(line=0;line<bom_lines_cnt; line++)
    {
        parameter += '&line_attachment_id_'+line+'='+document.getElementById('line_attachment_id_'+x+'_'+i+'_'+line).value+
					 '&line_attachment_row_'+line+'='+document.getElementById('line_attachment_row_'+x+'_'+i+'_'+line).value+
                     '&line_bom_cost_'+line+'='+document.getElementById('line_bom_cost_'+x+'_'+i+'_'+line).value;
    }


    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function validateDecimal()    {
    var isValid = true;

    $('.numeric').each(function() {
        var number_val = this.value;
        var num_id = this.id;
        var arr = new Array();

        arr = number_val.split (".");
        if(arr.length > 2)
        {
            isValid = false;
            $('#'+num_id).parent('div').addClass('has-error');
        }
        else
        {
            if($('#'+num_id).length > 2)
            {
                isValid = false;
                $('#'+num_id).parent('div').addClass('has-error');
            }
            else
            {
                $('#'+num_id).parent('div').removeClass('has-error');
            }

            if(arr.length == 2)
            {
                var count_decimal = arr[1].split('');
                if(count_decimal.length > 2)
                {
                    isValid = false;
                    $('#'+num_id).parent('div').addClass('has-error');
                    // console.log(num_id);
                    //console.log($('#'+num_id).parent('div'));
                }
            }

        }
    });

    return isValid;
}


function submit_rfq_creation(submit_type)
{
    document.getElementById('invited_vendors_panel').style.border = '1px solid #6a90e3';
    $('#sub_deadline_date').prop('border', '');// = '1px solid #ccc';

    var t = '';
    var x = 0;

    var status_id = 21;

    validate_duplicate('RFQ', document.getElementById('title_txt').value, document.getElementById('rfq_id').value).done(function(data){

        if(data == 1)
        {
            $('#title_txt').parent('div').addClass('has-error');
            var span_message = 'Duplicate found.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }
        else
        {
            if (submit_type == 1)
            {
                x = 1;
                status_id = 21;
                t = 'Submit';



                if (validateForm() == false)
                {
                    var span_message = 'Please fill up all fields.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                if($('#type_radio').val() == 0)
                {
                    $('#type_select1').css('color', 'red');
                    $('#type_select2').css('color', 'red');

                    var span_message = 'Please select Type.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                var submission_value = document.getElementById('sub_deadline_date').value;
                var new_sub = new Array();
                new_sub = submission_value.split('-');

                var ssyy = new_sub[0];

                if(ssyy > 9999 || submission_value == "")
                {
                    $('#sub_deadline_date').parent('div').addClass('has-error');
                    var span_message = 'Invalid submission deadline.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                var pref_dedline_value = document.getElementById('pref_delivery_date').value;
                var new_preferred = new Array();
                new_preferred = pref_dedline_value.split('-');

                var ppyy = new_preferred[0];
                //console.log(ppyy);
                if(ppyy > 9999 || pref_dedline_value == "")
                {
                    $('#pref_delivery_date').parent('div').addClass('has-error');
                    var span_message = 'Invalid Preferred Delivery Date.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }


                if(document.getElementById('sub_deadline_date').value >= document.getElementById('pref_delivery_date').value)
                {
                    $('#pref_delivery_date').parent('div').addClass('has-error');
                    var span_message = 'Invalid preferred delivery date.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

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

                if($('#sub_deadline_date').val() < today)
                {
                    $('#sub_deadline_date').parent('div').addClass('has-error');
                    var span_message = 'Invalid submission deadline.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                if(validateDecimal() == false)
                {
                    var span_message = 'Invalid quantity found.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                if (document.getElementById('count_all_invited').value == 0)
                {

                    $('#invited_vendors_panel').parent('div').addClass('has-error');
                    var span_message = 'Please select at least 1 vendor.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }

                /*if(hasSpecChar() == false)
                {
                    var span_message = 'Special characters is not allowed.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }*/
            }
            else
            {
                x = 0;
                status_id = 20;
                t = 'Save as Draft';

                if (document.getElementById('title_txt').value == '')
                {
                    $('#title_txt').parent('div').addClass('has-error');
                    return;
                }

                /*if(hasSpecChar() == false)
                {
                    var span_message = 'Special characters is not allowed.';
                    var type = 'danger';
                    notify(span_message, type);
                    return;
                }*/
            }

            var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="submit_creation('+x+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
            var type = 'info';
            notify(span_message, type, true);
        }

    });

}

function delete_selected_attachment(row)
{
    var count = 0;
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('attachment'+row).innerHTML = xmlhttp.responseText;
            $('#add_attachment'+row).prop('disabled', false);

            for(i=1, j=0;i<=8;i++)
            {
                if (document.getElementById('hidden_path_'+row+'_'+i).value != "0")
                {
                    j++;
                    document.getElementById('attachment_count'+row).value = j;
                }

                if (document.getElementById('hidden_path_'+row+'_1').value == "0")
                    $('#delete_attachment'+row).prop('disabled', true);
                else
                    $('#delete_attachment'+row).prop('disabled', false);

            }

            document.getElementById('attachment_count'+row).innerHTML = '<input type="hidden" name="att_cnt'+row+'" id="att_cnt'+row+'" value='+j+'>'+j;

            var span_message = 'File deleted successfully.';
            var type = 'success';
            notify(span_message, type);

        }
    }
    path_value = BASE_URL + 'rfqb/rfq_main/delete_selected_attachment/';

    parameter = 'row='+row;

    for(i=1;i<=8;i++)
    {
        parameter += '&hidden_path_'+row+'_'+i+'='+document.getElementById('hidden_path_'+row+'_'+i).value+
                     '&attachment_desc_'+row+'_'+i+'='+document.getElementById('attachment_desc_'+row+'_'+i).value+
                     '&line_attachment_id_'+row+'_'+i+'='+document.getElementById('line_attachment_id_'+row+'_'+i).value+
                     '&attachment_type_'+row+'_'+i+'='+document.getElementById('attachment_type_'+row+'_'+i).value+
                     '&checkbox_attachment_'+row+'_'+i+'='+document.getElementById('checkbox_attachment_'+row+'_'+i).value;

        if($('#checkbox_attachment_'+row+'_'+i).val() == 1)
            count++;
    }

    if(count == 0)
    {
        var span_message = 'Please check at least 1 attachment to delete.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function field_is_empty(control, sControlName)
{
    var iValue = 0;

    var strToSearch = control.value;

    if (strToSearch == "" || strToSearch == '' || strToSearch == 0 || strToSearch == "_empty_")
    {
        return 1;
    }
    else
    {
        return 0; //no invalid char
    }
}

function type_change(value)
{

    $('#type_select1').css('color', '');
    $('#type_select2').css('color', '');
    document.getElementById('type_radio').value = value; // value=1: qualified  // value=2: competitive
}

function clear_search_vendor()
{
    document.getElementById('cbo_vendorname').value = '';
    document.getElementById('cbo_vendorname').value = '';
    document.getElementById('cbo_vendorlist').value = '';
    document.getElementById('cbo_vendorcategory').value = '';
    document.getElementById('cbo_vendorbrand').value = '';
    document.getElementById('cbo_vendorlocation').value = '';
    document.getElementById('cbo_vendorrfq').value = '';
}

function close_div_rfq(div_name, div_style)
{
    document.getElementById(div_name).style.display = div_style;

    if (document.getElementById('new_vendor_hidden'))
        document.getElementById('new_vendor_hidden').value=0;
}

function find_similar()
{
    if (window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById('similar_result').innerHTML = xmlhttp.responseText;
        }
    }

    txt_email = document.getElementById('txt_email').value;
    parameter = 'txt_email=' + txt_email;


    path_value = BASE_URL + "rfqb/rfq_main/find_similar";

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);

}

function clear_new_vendors_add()
{
    document.getElementById('txt_vendorname').value = "";
    document.getElementById('txt_contact_person').value = "";
    document.getElementById('txt_email').value = "";
}

function search_filter_vendor()
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function()
    {
        if (xmlhttp.readyState== 4 && xmlhttp.status==200)
            document.getElementById('search_result').innerHTML = xmlhttp.responseText;
    }

    path_value = BASE_URL + 'rfqb/rfq_main/search_rfq_vendor';

    parameter = 'search_no='+document.getElementById('search_no').value+
                '&search_title='+document.getElementById('search_title').value+
                '&date_created='+document.getElementById('date_created').value+
                '&status_filter='+document.getElementById('status_filter').value+
                '&timeleft_filter='+document.getElementById('timeleft_filter').value;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function search_invite()
{

    var message = '';
    /*if(hasSpecChar_single('cbo_vendorname') == false)
    {
        message += 'Vendor Name';
    }
    if(hasSpecChar_single('cbo_vendorlist') == false)
    {
        message += 'Vendor List';
    }
    if(hasSpecChar_single('cbo_vendorcategory') == false)
    {
        message += 'Vendor Category';
    }
    if(hasSpecChar_single('cbo_vendorbrand') == false)
    {
        message += 'Vendor Brand';
    }
    if(hasSpecChar_single('cbo_vendorlocation') == false)
    {
        message += 'Vendor Location';
    }
    if(hasSpecChar_single('cbo_vendorrfq') == false)
    {
        message += 'Vendor RFQ';
    }*/

    if(message != '')
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    /*if(hasSpecChar() == false)
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }*/

    document.getElementById('seach_result_view').value = 1;
    document.getElementById('search_view_list').style.display = 'inherit';
    if (window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById('search_filter_table').innerHTML = xmlhttp.responseText;
    }

    path_value = BASE_URL + 'rfqb/rfq_main/search_invite_rfq';


    parameter = 'cbo_vendorname='+encodeURIComponent(document.getElementById('cbo_vendorname').value)+
                '&cbo_vendorlist='+encodeURIComponent(document.getElementById('cbo_vendorlist').value)+
                '&cbo_vendorcategory='+encodeURIComponent(document.getElementById('cbo_vendorcategory').value)+
                '&cbo_vendorbrand='+encodeURIComponent(document.getElementById('cbo_vendorbrand').value)+
                '&cbo_vendorlocation='+encodeURIComponent(document.getElementById('cbo_vendorlocation').value)+
                '&cbo_vendorrfq='+encodeURIComponent(document.getElementById('cbo_vendorrfq').value);

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function invite_selected_vendor()
{
    var count_invited;
    count_invited = document.getElementById('count_all_invited').value;
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xml = new ActiveXObject("Microsoft.XMLHTTP");

    var count = 0;
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("selected_invited_vendor").innerHTML = xmlhttp.responseText;
            document.getElementById('count_check').value = 0;
            $('#create_new_vendor').prop('disabled', true);
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/transfer_invited_vendor';

    parameter = 'total_search_result='+document.getElementById('total_search_result').value+
                '&count_all_invited='+document.getElementById('count_all_invited').value;

    for(i=1;i <= document.getElementById('total_search_result').value; i++)
    {

            parameter += '&vendorinvite_id'+i+'='+  document.getElementById("vendorinvite_id"+i).value+
                         '&vendorinvite_invite_id'+i+'='+  document.getElementById("vendorinvite_invite_id"+i).value+
                         '&vendorischecked'+i+'='+  document.getElementById("vendorischecked"+i).value+
                         '&vendorinvite_name'+i+'='+  encodeURIComponent(document.getElementById("vendorinvite_name"+i).value);

            if (document.getElementById('vendorischecked'+i).value == 1)
                count++;
    }

    if(count == 0)
    {
        var span_message = 'Please check at least 1 invited vendor.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    for(x=1; x<=count_invited; x++)
    {
        parameter += '&vendorinvitefinal_id'+x+'='+document.getElementById('vendorinvitefinal_id'+x).value+
                     '&vendorname_finalinvited'+x+'='+document.getElementById('vendorname_finalinvited'+x).value+
                     '&vendorfinal_invite_id'+x+'='+document.getElementById('vendorfinal_invite_id'+x).value+
                     '&final_invited_chkbx'+x+'='+document.getElementById('final_invited_chkbx'+x).checked;
    }

    parameter += '&count='+count;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function invitecheck(a, row, element)
{
    if (a)
        document.getElementById(element+row).value = 1;
    else
        document.getElementById(element+row).value = 0;

    if(element == 'final_invited_chkbx')
    {
        enable_vendorlist(a);
    }
}

function enable_vendorlist(type)
{
    if(type)
    {
        document.getElementById('count_check').value = parseInt(document.getElementById('count_check').value) + 1;
    }
    else
    {
        document.getElementById('count_check').value = parseInt(document.getElementById('count_check').value) - 1;
    }

    if($('#count_check').val() < 1)
        $('#create_new_vendor').prop('disabled', true);
    else
        $('#create_new_vendor').prop('disabled', false);
}

function clear_invited()
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xml = new ActiveXObject("Microsoft.XMLHTTP");

    var count = 0;
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("selected_invited_vendor").innerHTML = xmlhttp.responseText;

            if($('#count_all_invited').val() > 1)
                $('#create_new_vendor').prop('disabled', false);
            else
                $('#create_new_vendor').prop('disabled', true);
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/clear_invented_vendor';

    parameter = 'count='+document.getElementById('count_all_invited').value;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function delete_invited_vendor()
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    var deleted_check_count = 0;
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("selected_invited_vendor").innerHTML = xmlhttp.responseText;

            if($('#count_all_invited').val() > 1)
                $('#create_new_vendor').prop('disabled', false);
            else
                $('#create_new_vendor').prop('disabled', true);
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/delete_invited_vendor';

    parameter = 'count='+document.getElementById('count_all_invited').value;

    for(i=1; i<=document.getElementById('count_all_invited').value; i++)
    {

            parameter += '&final_invited_chkbx'+i+'='+document.getElementById('final_invited_chkbx'+i).value+
                         '&vendorname_finalinvited'+i+'='+document.getElementById('vendorname_finalinvited'+i).value+
                         '&vendorfinal_invite_id'+i+'='+document.getElementById('vendorfinal_invite_id'+i).value+
                         '&transfered_invited'+i+'='+document.getElementById('transfered_invited'+i).value+
                         '&vendorinvitefinal_id'+i+'='+document.getElementById('vendorinvitefinal_id'+i).value;

             if (document.getElementById('final_invited_chkbx'+i).value == 1)
                deleted_check_count++;
    }

    if(deleted_check_count == 0)
    {
        var span_message = 'Please check at least 1 invited vendor to delete.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function create_new_vendor_list()
{
    console.log('pumasok dito');

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            $('#myModal4').modal('show');
            $("#view_modal4").html(xmlhttp.responseText);
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/create_new_vendor_list';

    parameter = 'count_all_invited='+$('#count_all_invited').val();

    for(i = 1, j = 0; i <= $('#count_all_invited').val(); i++)
    {
        if($('#final_invited_chkbx'+i).val() == 1)
        {
            j++;
            parameter += '&vendorinvitefinal_id'+j+'='+$('#vendorinvitefinal_id'+i).val();
            parameter += '&vendorfinal_invite_id'+j+'='+$('#vendorfinal_invite_id'+i).val();
            parameter += '&vendorname_finalinvited'+j+'='+encodeURIComponent($('#vendorname_finalinvited'+i).val());
        }
    }
    parameter += '&count='+j;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", 'application/x-www-form-urlencoded');
    xmlhttp.send(parameter);

}

function validate_save_vendor_list(idval = 4)
{

    if(document.getElementById("txt_input_vendor_list_name").value == '')
    {
        var span_message = 'Please fill up vendor list name';
        var type = 'modal_danger';
        modal_notify("myModal"+idval, span_message, type);
        return;
    }

    if(parseInt($('#vendor_list_total').val()) == 0)
    {
        var span_message = 'Please select at least 1 vendor';
        var type = 'modal_danger';
        modal_notify("myModal"+idval, span_message, type);
        return;
    }

    var span_message = 'Are you sure you want to save this vendor list? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="save_vendor_list('+idval+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'modal_info';
    modal_notify("myModal"+idval, span_message, type, true);
}

function save_vendor_list(idval)
{

    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/rfq_main/save_vendor_list/";
    var post_params = 'vendor_list_total='+$('#vendor_list_total').val();
    post_params += '&txt_input_vendor_list_name='+$('#txt_input_vendor_list_name').val();


    for(i = 1; i <= $('#vendor_list_total').val(); i++)
    {
        post_params += '&vendorinvitefinal_id'+i+'='+$('#vendorinvitefinal_id'+i).val();
        post_params += '&vendorfinal_invite_id'+i+'='+$('#vendorfinal_invite_id'+i).val();
        post_params += '&vendorname_finalinvited'+i+'='+encodeURIComponent($('#vendorname_finalinvited'+i).val());
    }

    var success_function = function(responseText)
    {
        var span_message = 'Vendor List successfully saved.';
        var type = 'modal_success';
        modal_notify("myModal"+idval,span_message, type);

        setTimeout(function(){
        $('#myModal'+idval).modal('toggle');
        }, 2000);

        if(idval == 3)
        {
            var action_path = BASE_URL + 'rfqb/vendor_list_maintenance/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
        }


    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function validate_update_vendor_list()
{

    if(document.getElementById("txt_input_vendor_list_name").value == '')
    {
        var span_message = 'Please fill up vendor list name';
        var type = 'modal_danger';
        modal_notify("myModal4", span_message, type);
        return;
    }

    var span_message = 'Are you sure you want to save this vendor list? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="update_vendor_list(this)">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'modal_info';
    modal_notify("myModal4", span_message, type, true);
}

function update_vendor_list(btn)
{

    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/vendor_list_maintenance/update_record/";
    var post_params = 'total_vendor_count='+$('#total_vendor_count').val();
    post_params += '&txt_input_vendor_list_name='+encodeURIComponent($('#txt_input_vendor_list_name').val());
    post_params += '&total_left_count='+$('#total_left_count').val();
    post_params += '&vendor_list_id='+$('#vendor_list_id').val();

    for(i = 1; i <= $('#total_vendor_count').val(); i++)
    {
        post_params += '&invite_id'+i+'='+$('#invite_id'+i).val();
    }

    var success_function = function(responseText)
    {
        var span_message = 'Vendor List successfully updated.';
        var type = 'modal_success';
        modal_notify("myModal4",span_message, type);


        setTimeout(function(){
        $('#myModal4').modal('toggle');
        }, 2000);

        var action_path = BASE_URL + 'rfqb/vendor_list_maintenance/';

        setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function validate_delete_vendor_list(id, name)
{
    var span_message = 'Are you sure you want to delete '+name+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="delete_vendor_list('+id+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function delete_vendor_list(id)
{
    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/vendor_list_maintenance/delete_record/";
    var post_params = 'vendor_list_id='+id;

    var success_function = function(responseText)
    {
        var span_message = 'Vendor List successfully deleted.';
        var type = 'success';
        notify(span_message, type);

        var action_path = BASE_URL + 'rfqb/vendor_list_maintenance/';
        setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

    };

    ajax_request(ajax_type, url, post_params, success_function);
}

function delete_lines(type)
{
    var max_lines = document.getElementById("max_lines").value;
    var count = 0
    var total_checked = 0

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");


    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("max_lines").value = parseInt(document.getElementById("max_lines").value) - parseInt(total_checked);
            document.getElementById("lines_data_rfx").innerHTML = xmlhttp.responseText;

            if(parseInt($('#max_lines').val()) == 0)
                $('#max_lines').val(1);

            var span_message = 'Lines selected successfully deleted.';
            var type = 'success';
            notify(span_message, type);
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_main/delete_lines';

    parameter = 'max_lines='+max_lines;

    for(i=1; i<=max_lines; i++)
    {
        parameter += '&lineischecked'+i+'='+document.getElementById('lineischecked'+i).value;

    if (document.getElementById('lineischecked'+i).value == 1)
        count++;
    }

    if(count == 0)
    {
        var span_message = 'Please check at least 1 line to delete.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    for(i=1;i <= max_lines; i++)
    {
        parameter += '&line_category'+i+'='+document.getElementById('line_category'+i).value+
                    '&line_description'+i+'='+document.getElementById('line_description'+i).value+
                    '&line_measuring_unit'+i+'='+document.getElementById('line_measuring_unit'+i).value+
                    '&quantity'+i+'='+document.getElementById('quantity'+i).value+
                    '&specs'+i+'_text='+document.getElementById('specs'+i+'_text').value;

        for(x=1; x <= 8; x++)
        {
            parameter += '&hidden_path_'+i+'_'+x+'='+document.getElementById('hidden_path_'+i+'_'+x).value+
                         '&attachment_desc_'+i+'_'+x+'='+document.getElementById('attachment_desc_'+i+'_'+x).value+
                         '&line_attachment_id_'+i+'_'+x+'='+document.getElementById('line_attachment_id_'+i+'_'+x).value+
                         '&attachment_type_'+i+'_'+x+'='+document.getElementById('attachment_type_'+i+'_'+x).value;
        }

        if(parseInt(document.getElementById('lineischecked'+i).value) == 1)
            total_checked++;
    }

    parameter += '&total_checked='+total_checked;
    var next_col = Number(max_lines) - count;

   // document.getElementById("max_lines").value = next_col;

    if (next_col == 1) // minimun
        document.getElementById('del_btn').disabled = true;
    else
        document.getElementById('del_btn').disabled = false;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}

function change_type_allowed(value, row, col)
{
    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
            document.getElementById("upload_attachment_div").innerHTML = xmlhttp.responseText;
    }

    path_value = BASE_URL + 'rfqb/rfq_main/change_attachment_type';
	var bom_link = document.getElementById("modal_bom_template_link");
	$('#btn_ok_attach').prop('disabled', false);
	if(bom_link!=null && value == 1) {
		bom_link.style.display="block";
		var hidden_bom_attach = document.getElementById('hidden_bom_attach'+row);
		if (hidden_bom_attach) {
			if (parseInt(hidden_bom_attach.value)>0) {
				$('#btn_ok_attach').prop('disabled', true);
				var span_message = 'Only one BOM Template can be uploaded per RFQ line.';
				var type = 'modal_warning';
				modal_notify("myModal", span_message, type);
			}
			// document.getElementById("modal_alert_warning").style.display = "inline";
		}
	} else if (bom_link!=null && value != 1) {
		bom_link.style.display="none";
	}

    parameter = 'value='+value;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}


/*
function asd()
{
    var ajax_type = 'POST';
    var url = BASE_URL + "vendor/invitecreation/add_invitecreation";
    var post_params = $('#frm_invitecreation').serialize();
    post_params += "&status=1"; // 1 draft, 2 pending for approval, 3 approve, 4 reject

    var success_function = function(responseText)
    {
       console.log(responseText);
       $('.spacer').html(responseText);
       // $('#frm_invitecreation')[0].reset(); // reset fields after success
    };

    ajax_request(ajax_type, url, post_params, success_function);
}
*/




// ------------------------------ RFQ SM View ------------------------------------------

// ------------------------------ END SM View ------------------------------------------


// ------------------------------ RFQ APPROVAL ------------------------------------------
function approve_creation(type) // 1= approve 2=reject
{
    var t = '';
    var x = 1;
    var message = '';

    if (type == 1)
    {
        t = 'Submit';
        x = 1;
    }
    else
    {
        t = 'Reject';
        x = 0;

        if (validateForm() == false)
        {
            var span_message = 'Please fill up reject reason.';
            var type = 'modal_danger';
            modal_notify("reject_modal", span_message, type);
            return;
        }


    }/*

    if(hasSpecChar() == false)
    {
        message += 'Vendor RFQ';
    }

    if(message != '')
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        modal_notify("reject_modal", span_message, type);
        return;
    }*/

    if(x == 0)
        $('#reject_modal').modal('toggle');

    if (document.getElementById('rfx_id').value == 0)
        return;

    var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="approval_rfq_process('+x+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function approval_rfq_process(type)
{
    var t = '';

    if (type == 1)
        t = 'Saved';
    else
    {
        if(document.getElementById('reject_reason').value == '' || document.getElementById('reject_reason').value == null)
            return;
        t = 'Rejected';
    }
    btn =$("#approve_btn");
    loading($(btn), 'in_progress');
    // enable all first before saving

    $('#btn_approve').prop('disabled', true);
    $('#btn_reject').prop('disabled', true);

    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/approval/response_creation_approval/";

    var post_params = 'rfx_id='+document.getElementById('rfx_id').value+
                      '&type='+type+
                      '&position_id='+document.getElementById('position_id').value+
                      '&reject_reason='+document.getElementById('reject_reason').value+
                      '&title='+document.getElementById('title').value+
                      '&createdbyid='+document.getElementById('createdbyid').value+
                      '&current_status_id='+document.getElementById('current_status_id').value;

    var success_function = function(responseText)
    {
       // console.log(responseText);
        var span_message = 'Data '+t+' succesfully';
        var type = 'success';
        notify(span_message, type);

        var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
        setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

        loading($(btn), 'done');
    };

    ajax_request(ajax_type, url, post_params, success_function);

   /* if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xml = new ActiveXObject("Microsoft.XMLHTTP");

    var count = 0;
    xmlhttp.onreadystatechange = function()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
          //  document.getElementById("result_div").innerHTML = xmlhttp.responseText;

            var span_message = 'Data '+t+' succesfully';
            var type = 'success';
            notify(span_message, type);

            $('#btn_approve').prop('disabled', true);
            $('#btn_reject').prop('disabled', true);

            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
        }
    }

    path_value = BASE_URL + 'rfqb/approval/response_creation_approval';

    parameter = 'rfx_id='+document.getElementById('rfx_id').value+
                '&type='+type+
                '&position_id='+document.getElementById('position_id').value+
                '&reject_reason='+document.getElementById('reject_reason').value+
                '&current_status_id='+document.getElementById('current_status_id').value;
    //console.log(parameter);
    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);*/
}


// ------------------------------ END APPROVAL ------------------------------------------

$('#btn_search_rfq').on('click', function(){
        get_main_tbl();
    });



// ------------------------------ INVITATION -----------------------------------------
function participate_decline_invitation(type)
{
    var t = '';
    var x = 1;

    var message = '';


    if (type == 1)
    {
        t = 'participate';
        x = 1;
    }
    else
    {
        t = 'decline';
        x = 0;

        if(document.getElementById("reject_reason").value == '')
        {
            message += 'Please fill up note to buyer.'
            $('#reject_reason').parent('div').addClass('has-error');

        }

        /*if(hasSpecChar() == false)
        {
            var span_message = 'Special characters is not allowed.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }*/


        if(message != '')
        {
            var span_message = 'Please fill up '+message;
            var type = 'danger';
            notify(span_message, type);
            return;
        }
    }

    if (document.getElementById('rfx_id').value == 0)
        return;

    var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="invitation_response('+x+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);

}

function invitation_response(type)
{
    var x = '';

    var t = '';
    var x = 1;

    if (type == 1)
    {
        t = 'participated';
        x = 1;
    }
    else
    {
        t = 'declined participating';
        x = 0;
    }

    btn =$("#approve_btn");
    loading($(btn), 'in_progress');

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if(xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            //document.getElementById('result_div').innerHTML = xmlhttp.responseText;

            if (type == 0)
                $('#myModal').modal('toggle');

            $('#btn_participate').prop('disabled', true);
            $('#btn_decline').prop('disabled', true);

            var span_message = 'You have successfully '+t+' in this request.';
            var type = 'success';
            notify(span_message, type);

            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_vendor_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
        }

    }

    path_value = BASE_URL + 'rfqb/rfq_main/participate_decline_invitation';

    parameter = 'type='+type+
                '&rfx_id='+document.getElementById('rfx_id').value+
                '&current_status_id='+document.getElementById('status_id').value+
                '&invite_id='+document.getElementById('invite_id').value+
                '&position_id='+document.getElementById('position_id').value;

    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);
}
// ------------------------------ END OF INVITATION -----------------------------------

// ------------------------------ RESPONSE CREATION -----------------------------------

function validateDecimal()    {
    var isValid = true;

    $('.numeric-decimal').each(function() {
        var number_val = this.value;
        var num_id = this.id;
        var arr = new Array();

        arr = number_val.split (".");
        if(arr.length > 2)
        {
            isValid = false;
            $('#'+num_id).parent('div').addClass('has-error');
        }
        else
        {
            if($('#'+num_id).length > 2)
            {
                isValid = false;
                $('#'+num_id).parent('div').addClass('has-error');
            }
            else
            {
                $('#'+num_id).parent('div').removeClass('has-error');
            }

            if(arr.length == 2)
            {
                var count_decimal = arr[1].split('');
                if(count_decimal.length > 2)
                {
                    isValid = false;
                    $('#'+num_id).parent('div').addClass('has-error');
                    // console.log(num_id);
                    //console.log($('#'+num_id).parent('div'));
                }
            }

        }
    });

    return isValid;
}

function validate_response(type)
{
    var t = '';
    var x = 0;
    if (type == 1)
    {
        x = 1;
        t = 'Submit';


        if (validateForm() == false)
        {
            var span_message = 'Please fill up all fields.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }

        if(validateDecimal() == false)
        {
            var span_message = 'Invalid quote amount found.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }

    }
    else
    {
        x = 0;
        t = 'Save as Draft';
    }



    /*if(hasSpecChar() == false)
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }*/

    if(validate_quote_duplicates('txt_quote') == false)
    {
        var span_message = 'Duplicate Quote.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    if(validate_quote_duplicates('txt_counteroffer') == false)
    {
        var span_message = 'Duplicate Counter Offer.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="submit_response_creation('+x+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function submit_response_creation(type)
{
    var action;
    action = type;

    btn =$("#approve_btn");
    loading($(btn), 'in_progress');

    $('#btn_submit').prop('disabled', true);
    $('#btn_draft').prop('disabled', true);

    var line_data_count = $('#total_line_quotes').val();

    for(i=1; i <= line_data_count; i++)
    {
        var num_quote             = $('#num_quote'+i).val();

        if($('#quoteischecked'+i+'_1').val() == 1)
        {
            $('#delivery_time'+i+'_1').prop('disabled', false);
        }

    }

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {

            var span_message = 'Response have been saved succesfully.';
            var type = 'success';
            notify(span_message, type);

            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_vendor_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');
        }
    }

    path_value = BASE_URL + 'rfqb/rfq_response_creation/submit_response_creation';
	var form = $('#form1');
	 // Find disabled inputs, and remove the "disabled" attribute
	var disabled = form.find(':input:disabled').removeAttr('disabled');
    parameter = $('#form1').serialize();
    parameter+='&action='+action;


    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);

	disabled.attr('disabled','disabled');

}

function validate_quote_duplicates(element)
{
    var line_data_count = $('#total_line_quotes').val();
    var isValid = true;
    for(i=1; i <= line_data_count; i++)
    {
        var num_quote             = $('#num_quote'+i).val();

        for(x=1; x <= num_quote; x++)
        {
            for(y=1; y <= num_quote; y++)
            {
                if (y != x)
                    {
                        first_item = $('#'+element+i+'_'+x).val();
                        second_item = $('#'+element+i+'_'+y).val();

                        if (first_item != '' && second_item != '')
                        {
                            if (first_item == second_item)
                            {
                                    second_item = document.getElementById(element+i+'_'+y).focus();
                                    $('#'+element+i+'_'+y).parent('div').addClass('has-error');
                                    isValid = false;

                            }
                        }
                    }
            }
        }

    }

    return isValid;
}

function validate_response_v2(type)
{
    var t = '';
    var x = 0;
    if (type == 1)
    {
        x = 1;
        t = 'Submit';

        if (validateForm() == false)
        {
            var span_message = 'Please fill up all fields.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }

        if(validateDecimal() == false)
        {
            var span_message = 'Invalid quote amount found.';
            var type = 'danger';
            notify(span_message, type);
            return;
        }

    }
    else
    {
        x = 0;
        t = 'Save as Draft';
    }

    if(hasSpecChar() == false)
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    if(validate_quote_duplicates('txt_quote') == false)
    {
        var span_message = 'Duplicate Quote.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    if(validate_quote_duplicates('txt_counteroffer') == false)
    {
        var span_message = 'Duplicate Counter Offer.';
        var type = 'danger';
        notify(span_message, type);
        return;
    }

    var span_message = 'Are you sure you want to '+t+'? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="submit_response_creation_v2('+x+')">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function submit_response_creation_v2(type)
{
    var action;
    action = type;

    btn =$("#approve_btn");
    loading($(btn), 'in_progress');

    $('#btn_submit').prop('disabled', true);
    $('#btn_draft').prop('disabled', true);

    var line_data_count = $('#total_line_quotes').val();

    for(i=1; i <= line_data_count; i++)
    {
        var num_quote             = $('#num_quote'+i).val();

        if($('#quoteischecked'+i+'_1').val() == 1)
        {
            $('#delivery_time'+i+'_1').prop('disabled', false);
        }

    }

    if(window.XMLHttpRequest)
        xmlhttp = new XMLHttpRequest();
    else
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function ()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {

            var span_message = 'Response have been saved succesfully.';
            var type = 'success';
            notify(span_message, type);

            var action_path = BASE_URL + 'rfqb/rfq_main/rfqrfb_main_vendor_view/';
            setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);

            loading($(btn), 'done');

        }
    }

    path_value = BASE_URL + 'rfqb/rfq_response_creation_v2/submit_response_creation';

	var form = $('#form1');
	 // Find disabled inputs, and remove the "disabled" attribute
	var disabled = form.find(':input:disabled').removeAttr('disabled');

    parameter = $('#form1').serialize();
    parameter+='&action='+action;


    xmlhttp.open("POST", path_value, true);
    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlhttp.send(parameter);


	disabled.attr('disabled','disabled');

}

function add_new_quote(row, col)
{
    var message = '';

    if(document.getElementById("upload_attachment").files.length == 0)
    {
        message += 'Please Upload An Attachment'
    }

    if(message != '')
    {
        var span_message = message;
        var type = 'modal_danger';
        modal_notify("myModal2", span_message, type);
        return;
    }

    var extension = new Array('', 'xlsx', '.xls', '.jpg', '.png', 'jpeg', '.pdf', 'docx', '.doc');

    if (!isInArray('upload_attachment', extension))
    {
        var span_message = 'Please select the correct file format.';
        var type = 'modal_danger';
        modal_notify("myModal2", span_message, type);

        return;
    }


    surl = BASE_URL + 'rfqb/rfq_response_creation/new_attachment_upload/'+row+'/'+col;
    file_data = $('#upload_attachment').prop('files')[0];

    upload_ajax_modal(document.form1, surl).done(function(responseText) {
        document.getElementById('attachment_img'+row+"_"+col).innerHTML = responseText;
    });
    $('#myModal2').modal('toggle'); // display response from the PHP script, if any

   // $('#delete_attachment'+row).prop('disabled', false);

    //document.getElementById('attachment'+row+"_"+col).style.display = 'inline-block';
    //$('#attachment'+row+"_"+col).addClass('dv_attachment');

    return;



}

function attachment_response(row, col)
{
    $('#myModal2').modal({
        backdrop: 'static',
        keyboard: false
    })
    $('#myModal2').modal('show');

    var url = BASE_URL + 'rfqb/rfq_response_creation/new_attachment_response';

    $.post(
        url,
        {
            row                 : row,
            col                 : col
        },
        function(responseText) {
            $("#view_modal2").html(responseText);

        },
        "html"
    );
}

function add_quote()
{
    var row = document.getElementById('row').value;
    var message = '';

    if($('#modal_txt_price').val() == '')
    {
        document.getElementById('modal_txt_price').style.border = '1px solid #FF0000';
        message += 'Quote Amount ';
    }


    if (message != '')
    {
        var span_message = 'Please fill up '+message;
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        return;
    }

    /*if(hasSpecChar_single('modal_counter_offer') == false)
    {
        var span_message = 'Special characters is not allowed.';
        var type = 'danger';
        modal_notify('myModal', span_message, type);
        return;
    }*/


    var extension = new Array('', 'xlsx', '.xls', '.jpg', '.png', 'jpeg', '.pdf', 'docx', '.doc');

    if (!isInArray('upload_attachment_new', extension))
    {
        var span_message = 'Please select the correct file format.';
        var type = 'modal_danger';
        modal_notify("myModal", span_message, type);
        document.getElementById("modal_alert_warning").style.display = "inline";

        return;
    }


    var ajax_type = 'POST';
    file_data = $('#upload_attachment_new').prop('files')[0];

    var post_params = 'modal_txt_price='+document.getElementById('modal_txt_price').value+
                      'delivery_time='+document.getElementById('delivery_time').value+
                      'modal_counter_offer='+document.getElementById('modal_counter_offer').value;


    surl = BASE_URL + 'rfqb/rfq_response_creation/add_quotes';
	var quote_num = 1;
	//Remove length() in row
	if ($("#num_quote"+row)) {
		quote_num = parseInt($("#num_quote"+row).val());
	}
	var form = $('#form1');
	 // Find disabled inputs, and remove the "disabled" attribute
	var disabled = form.find(':input:disabled').removeAttr('disabled');
	//console.log(disabled);
    upload_ajax_modal(document.form1, surl).done(function(responseText) {

        $('#num_quote'+row).val(parseInt($('#num_quote'+row).val()) + 1);
		//Append instead of innerHTML.
		//$('#quote_added'+row).append(responseText);
        document.getElementById('quote_added'+row).innerHTML = responseText;
		
		if($('#num_quote'+row).val() == 5){
			$("#add_another_quote_btn" + row).attr('disabled','disabled');
		}else{
			$("#add_another_quote_btn" + row).removeAttr('disabled');
		}
    });
	// re-disabled the set of inputs that you previously enabled
	disabled.attr('disabled','disabled');
    $('#myModal').modal('toggle'); // display response from the PHP script, if any


    return;

}

function isInArray(element, array) {

    full_path = document.getElementById(element).value;

    var filename = full_path.substr(full_path.lastIndexOf('\\')+1); // get upload file name
    var str_file_ext = filename.substr(-4, 4);

    filename = filename.toLowerCase();
    str_file_ext = str_file_ext.toLowerCase();
    //isfound = filename.lastIndexOf(extension.toLowerCase()); // if -1 is the return meaning the word is not found in filename
    //console.log(str_file_ext);
    return array.indexOf(str_file_ext.toLowerCase()) > -1;
}



// ------------------------------- END OF RESPONSE ------------------------------------

function go_to_homepage()
{
    var span_message = 'Are you sure you want to return to homepage? <input type="button" class="btn btn-success" id="approve_btn" value="Yes" onclick="redirect_to_homepage()">&nbsp;<input type="button" class="btn btn-default" id="close_alert" value="No">';
    var type = 'info';
    notify(span_message, type, true);
}

function redirect_to_homepage()
{

    $('#approve_btn').prop('disabled', true);
    $('#close_alert').prop('disabled', true);
    $div_notifications.stop().fadeOut("slow");
    var action_path = BASE_URL + 'dashboard/home_page/';
    setTimeout(function(){$main_container.html('').load(cache.set('refresh_path', action_path))},3000);
}

$('#btn_search_rfqm').on('click', function(){

        get_main_tbl();
});

$('#btn_clear_rfqm').on('click', function(){

        if($('#search_no'))
            $('#search_no').val('');

        if($('#search_title'))
            $('#search_title').val('');

        if($('#date_created'))
            $('#date_created').val('');

        if($('#cbo_status'))
            $('#cbo_status').val('');

        if($('#timeleft_filter'))
            $('#timeleft_filter').val(0);

        if($('#cbo_buyer'))
            $('#cbo_buyer').val('');

        if($('#cbo_requestor'))
            $('#cbo_requestor').val('');

        if($('#cbo_purpose'))
            $('#cbo_purpose').val('');

        get_main_tbl();
});

var history_template = $('#history_template').html();
$('#rfq_approval_history').on('click', function() {

    //alert("test");

    $('#myModal').modal('show');

    $('#myModal span').hide();
    $('.alert > span').show(); // dont include to hide these span
    $('#myModal .rfq_approval_history').show();

    var ajax_type = 'POST';
    var url = BASE_URL + "rfqb/rfq_details/rfq_history_table/" + document.getElementById('rfqrfb_id').value + "/";
    var post_params; // 1 draft, 2 pending for approval, 3 approve, 4 reject

    var success_function = function(responseText)
    {
        //alert(responseText);
        //$('#model_error').text(responseText);
        var tbl_data = $.parseJSON(responseText);

        var DATA = {
            table_history: tbl_data.query
        }

        $('#tbl_history_body').html(Mustache.render(history_template, DATA));

       // $('#tbl_pag').html(responseText);
    };

    ajax_request(ajax_type, url, post_params, success_function);
});
