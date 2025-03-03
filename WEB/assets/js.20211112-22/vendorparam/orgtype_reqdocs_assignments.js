var _selRmv_vst = [];
var req_docs = [];

$(document).ready(function(){
	// alert("1");
	getOrgType();
	$('#orgtype_docs').hide();
	$('#select_tradevendortype').hide();
	//$("#tbl_view").DataTable().destroy();
	//getAllReqDocs();
});

	$("#select_vendortype").change(function(){
		var vendortype_id = $('#select_vendortype').val();
		if(vendortype_id == 1){
			$('#select_tradevendortype').show();
		}else{
			$('#select_tradevendortype').hide();
		}
	});

	$("#btn_search_org").click(function()
	{	
		var orgtype_id = $('#select_orgtype').val();
		var vendortype_id = $('#select_vendortype').val();
		var tradevendortype_id = $('#select_tradevendortype').val();
		
		$("#tbl_view").DataTable().destroy();
		$("#tbl_view").find("tr:gt(0)").remove();
		if (orgtype_id > 0 && vendortype_id == 2){
			$('#orgtype_docs').show();
			getReqDocs(orgtype_id, 0);// 0 = NON TRADE
		}else if (orgtype_id > 0 && vendortype_id == 1 && tradevendortype_id > 0){
			$('#orgtype_docs').show();
			getReqDocs(orgtype_id, tradevendortype_id);// tradevendortype_id = TRADE VENDOR TYPE
		}else if(orgtype_id >0 && vendortype_id == 3){
			$('#orgtype_docs').show();
			getReqDocs(orgtype_id, 3);// 0 = NON TRADE  || 3 = NON-TRADE SERVICE
		}
	});

	function getOrgType()
	{
		var base_url = $('#b_url').attr('data-base-url');
		$.ajax({
			type:'POST',
			url: base_url + "get_orgtype",
			success: function(result){	
				//alert(result);
				var json_result = $.parseJSON(result);			
				populateOrgType(json_result);
			}		
		}).fail(function(){
			
		});	
	}

	function populateOrgType(obj_result)
	{
		var x=0;
		var y=0;
		var z=obj_result.length;

		$('#select_orgtype').find('option').remove().end()	
		$('<option value = "0">SELECT OWNERSHIP</option>').appendTo('#select_orgtype');

		for(x=0;x<z;x++){		
			$('<option value = "'+obj_result[x].OWNERSHIP_ID+'">'+obj_result[x].OWNERSHIP_NAME +'</option>').appendTo('#select_orgtype');
		}
	}

	function getAllReqDocs(orgtype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_all_reqdocs";	

		$.ajax({
			type:'POST',
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

	function createDataTable(obj_result, checked)
	{
		var x=0;
		var y=0;
		var z=obj_result.length;
		
		for(x=0;x<z;x++){
			//alert(obj_result[x].REQUIRED_DOCUMENT_ID);
			y=x+1;
			$('<tr id="row'+obj_result[x].REQUIRED_DOCUMENT_ID+'" class="clickable-row" class="text-center"><td class="text-center"><input type="checkbox" id="reqdocs'+obj_result[x].REQUIRED_DOCUMENT_ID+'" rel="'+obj_result[x].REQUIRED_DOCUMENT_ID+'"><a href=""></a></input></td><td>'+obj_result[x].REQUIRED_DOCUMENT_NAME+'</td></tr>').appendTo('#tbl_body_orgtype_reqdocs');
			if (checked=="1"){
				$('#reqdocs'+obj_result[x].REQUIRED_DOCUMENT_ID).prop('checked', true)
			}
		}	
	}

	$("#btn_save_defn").click(function()
	{	
		req_docs = [];

		var orgtype_id = $('#select_orgtype').val();
		var vendortype_id = $('#select_vendortype').val();
		var tradevendortype_id = $('#select_tradevendortype').val();
		$("input:checkbox").each(function(){
		    var $this = $(this);

		    if($this.is(":checked")){
		    	//alert($this.attr("rel"));
		         req_docs.push($this.attr("rel"));
		    }else{
		       
		    }
		});
		if (orgtype_id > 0 && vendortype_id == 2){
			saveDocs(orgtype_id, req_docs, 0);
		}else if (orgtype_id > 0 && vendortype_id == 1 && tradevendortype_id > 0){
			saveDocs(orgtype_id, req_docs, tradevendortype_id);
		}else if(orgtype_id > 0 && vendortype_id == 3){
			saveDocs(orgtype_id, req_docs, 3);
		}
		//alert(orgtype_id);
	});

	function saveDocs(orgtype_id, req_docs, vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "save_docs";


		$.ajax({
			type:'POST',
			data:{orgtype_id: orgtype_id, req_docs: req_docs,vendortype_id:vendortype_id},
			url: base_url,
			success: function(result){	
				 //alert(result);

				var json_result = $.parseJSON(result);
			
			
				if (json_result.status != false){

					var span_message = 'Record success saved!';
					var type = 'success';
					notify(span_message, type);	
				}else{
					var span_message = 'Unable to save record!';
					var type = 'danger';
					notify(span_message, type);	
				}

			}		
		}).fail(function(){
			
		});	
	}


	function getReqDocs(orgtype_id,vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_reqdocs";

		$.ajax({
			type:'POST',
			data:{orgtype_id: orgtype_id, vendortype_id: vendortype_id},
			url: base_url,
			success: function(result){	
				var json_result = $.parseJSON(result);
					createDataTable(json_result, "1");
					getReqDocsNotIn(orgtype_id,vendortype_id);
			}		
		}).fail(function(){
			
		});	
	}

	function getReqDocsNotIn(orgtype_id,vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_reqdocs_not_in";

		//alert("1");

		$.ajax({
			type:'POST',
			data:{orgtype_id: orgtype_id,vendortype_id: vendortype_id},
			url: base_url,
			success: function(result){	
				// alert(result);
				
				var json_result = $.parseJSON(result);

				
				if (json_result.status != false){	
					createDataTable(json_result, "0");

				}
				//alert("check");
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


			}		
		}).fail(function(){
			
		});	
	}

	function check_select(x)
	{

		$div_notifications.stop().fadeOut("slow", clean_div_notif);  
		var n = x.getAttribute('data-sel');

		if(x.checked){
			reqdocs_arr.push(n);
			return;
		}

		for(i=0;i<reqdocs_arr.length;i++){
			if(n == reqdocs_arr[i]){
				reqdocs_arr.splice(i,1);
				break;
			}
		}
		return;

	}




	// getScreen("0");



	// $("#btn_save_reqdocss").click(function()
	// {	
	// 	var reqdocss = [];


	// 	var orgtype_id = $('#select_orgtype').val();
	// 	$("input:checkbox").each(function(){
	// 	    var $this = $(this);

	// 	    if($this.is(":checked")){
	// 	         reqdocss.push($this.attr("rel"));
	// 	    }else{
		       
	// 	    }
	// 	});

	// 	saveScreens(orgtype_id, reqdocss);
			
	// });

	//========== FUNCTIONS ==========

	// function getAllScreens(orgtype_id)
	// {
	// 	var base_url = $('#b_url').attr('data-base-url');

	// 	base_url = base_url + "get_all_reqdocss";	

	// 	//alert(base_url);
		
	// 	$.ajax({
	// 		type:'POST',
	// 		url: base_url,
	// 		success: function(result){	

	// 			var json_result = $.parseJSON(result);

	// 			if (json_result.status != false){
	// 				$("#tbl_view").DataTable().destroy();
	// 				createDataTable(json_result);
	// 				$("#tbl_view").DataTable({
	// 					"pageLength": 10
	// 				});

	// 			}else{
	// 				var span_message = 'Record not found!';
	// 				var type = 'danger';
	// 				notify(span_message, type);				
	// 			}
	// 		}		
	// 	}).fail(function(){
			
	// 	});	
	// }


// 	$(document).on('click','#checkAll',function(){
// 	    $('input:checkbox').not(this).prop('checked', this.checked);
// 	});


// 	function saveScreens(orgtype_id, reqdocss)
// 	{
// 		var base_url = $('#b_url').attr('data-base-url');

// 		base_url = base_url + "save_reqdocss";

// 		$.ajax({
// 			type:'POST',
// 			data:{orgtype_id: orgtype_id, reqdocss: reqdocss},
// 			url: base_url,
// 			success: function(result){	

// 			}		
// 		}).fail(function(){
			
// 		});	
// 	}

// 	function pushScreens(reqdocs_id){
// 		var orgtype_id = $('#select_orgtype').val();

// 		reqdocss.push({orgtype_id: orgtype_id, reqdocs_id: reqdocs_id});

// 		alert(reqdocss[0]['orgtype_id']);
// 	}

// });

//===========================END=================================

// $("#btn_add_role").click(function()
// {
// 	var role_name = $("#input_role_name").val();
// 	var description = $("#input_description").val();
// 	var bus_division = $("#select_bus_division").val();
	
// 	if(isFillUpError("add") == 0){
// 		addRole(role_name, description, bus_division);	
// 	}
	
// });

// $("#btn_save_role").click(function()
// {
// 	var role_id = $("#edit_role_id").val();
// 	var role_name = $("#edit_role_name").val();
// 	var description = $("#edit_description").val();
// 	var bus_division = $("#edit_bus_division").val();

// 	if(isFillUpError("edit") == 0){
// 		saveRole(role_id, role_name, description, bus_division);
// 	}	
// });



// $('#modal_add_role').on('show.bs.modal', function () {
// 	$('#modal_add_role').find('.field-required').each(function()
// 	{
// 		$('#'+ this.id).parent('div').removeClass('has-error');	
// 		$('#'+ this.id).val('');
// 	});	
	
// 	$('#select_bus_division').val('1');
// 	//var x = document.getElementById('error_add_role');
// 	//x.style.display = 'none';
// })

// $('#modal_edit_role').on('show.bs.modal', function () {
// 	$('#modal_edit_role').find('.field-required').each(function()
// 	{
// 		$('#'+ this.id).parent('div').removeClass('has-error');	
// 	});	
	
// 	//var x = document.getElementById('error_edit_role');
// 	//x.style.display = 'none';
// })

//========== FUNCTIONS ==========

// function getRole(role)
// {
// 	var base_url = $('#b_url').attr('data-base-url');
	
// 	if(role == "all"){
// 		base_url = base_url + "get_all_role";
// 	}else{
// 		base_url = base_url + "get_role";
// 	}
	
// 	$.ajax({
// 		type:'POST',
// 		data:{role: role},
// 		url: base_url,
// 		success: function(result){	
// 			//alert(result);
// 			var json_result = $.parseJSON(result);
			
// 			if (json_result.status != false){
// 				$("#tbl_view").DataTable().destroy();
// 				createDataTable(json_result);
// 				$("#tbl_view").DataTable({
// 					"pageLength": 10
// 				});
// 			}else{
// 				var span_message = 'Record not found!';
// 				var type = 'danger';
// 				notify(span_message, type);				
// 			}
// 		}		
// 	}).fail(function(){
		
// 	});	
// }


// function isFillUpError(crud)
// {
// 	var iError = 0;
	
// 	if (crud=="add"){
// 		$('#modal_add_role').find('.field-required').each(function()
// 		{
// 			if($(this).val().length == 0){			
// 				$('#'+ this.id).parent('div').addClass('has-error');	
// 				iError++;
// 			}else{
// 				$('#'+ this.id).parent('div').removeClass('has-error');	
// 			}	
// 		});	
		
// 		if(iError > 0){
// 			modal_notify('modal_add_role','<strong>Failed! </strong> Please fill up required fields.','danger');
// 		}
// 	}else if (crud=="edit"){
// 		$('#modal_edit_role').find('.field-required').each(function()
// 		{
// 			if($(this).val().length == 0){			
// 				$('#'+ this.id).parent('div').addClass('has-error');	
// 				iError++;
// 			}else{
// 				$('#'+ this.id).parent('div').removeClass('has-error');	
// 			}	
// 		});	
		
// 		if(iError > 0){
// 			modal_notify('modal_edit_role','<strong>Failed! </strong> Please fill up required fields.','danger');
// 		}		
// 	}
// 	return iError;
// }

// function addRole(role_name, description, bus_division)
// {
// 	var base_url = $('#b_url').attr('data-base-url');

// 	$.ajax({
// 		type:'POST',
// 		data:{role_name: role_name, description: description, bus_division: bus_division},
// 		url: base_url + "add_role",
// 		success: function(result){	
// 			alert(result);

// 			//$('#modal_add_role').modal('hide');
			
// 			modal_notify('modal_add_role','<strong>Success! </strong> Organization Type creation success.','success');

// 			//var span_message = 'Record has been successfully saved!';
// 			//var type = 'success';
// 			//notify(span_message, type);					
			
// 			getRole("all");
// 		}		
// 	}).fail(function(){
		
// 	});	
// }

// function saveRole(role_id, role_name, description, bus_division)
// {
// 	var base_url = $('#b_url').attr('data-base-url');
	
// 	$.ajax({
// 		type:'POST',
// 		data:{role_id: role_id, role_name: role_name, description: description, bus_division: bus_division},
// 		url: base_url + "save_role",
// 		success: function(result){	
// 			//alert(result);
// 			//$('#modal_edit_role').modal('hide');
			
// 			//var span_message = 'Currency has been successfully saved!';
// 			//var type = 'success';
// 			//notify(span_message, type);			
			
// 			modal_notify('modal_edit_role','<strong>Success! </strong> Organization Type update success.','success');

// 			getRole("all");
// 		}		
// 	}).fail(function(){
		
// 	});		
// }

// function editRow(y, role_id){
// 	var sel_role_name = document.getElementById("role_name"+y).innerHTML;
// 	var sel_description = document.getElementById("description"+y).innerHTML;
// 	var sel_bus_division = document.getElementById("bus_division"+y).innerHTML;

// 	//alert(role_id);
// 	$('#edit_role_id').val(role_id);
// 	$('#edit_role_name').val(sel_role_name);
// 	$('#edit_description').val(sel_description);
// 	if (sel_bus_division == "TRADE"){
// 		$('#edit_bus_division').val("1");
// 	}else if (sel_bus_division == "NON-TRADE"){
// 		$('#edit_bus_division').val("0");
// 	}
// 	$('#modal_edit_role').modal('show')
// }

// function deactivateYesNo(y,role_id)
// {
// 	disable_enable_frm('frm_role', true);
	
// 	$('#row'+y).addClass('danger').siblings().removeClass('danger');

// 	$('#edit'+y).addClass('disabled');
// 	$('#deactivate'+y).addClass('disabled');

// 	var span_message = 'Are you sure you want to deactivate record? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+role_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
// 	var type = 'info';
// 	notify(span_message, type, true);	
// }

// function deactivateYes(y, role_id)
// {
// 	var base_url = $('#b_url').attr('data-base-url');	
	
// 	$.ajax({
// 		type:'POST',
// 		data:{role_id: role_id},
// 		url: base_url + "deactivate_role",
// 		success: function(result){	

// 			var json_result = $.parseJSON(result);
			
// 			$('#edit'+y).removeClass('disabled');
// 			$('#deactivate'+y).removeClass('disabled');
// 			$('#row'+y).removeClass('danger')

// 			disable_enable_frm('frm_role', false);
			
// 			var span_message = 'Record has been deactivated!';
// 			var type = 'info';
// 			notify(span_message, type);		
// 			//alert("1");
// 			getRole("all");
// 		}		
// 	}).fail(function(){
		
// 	});	
// }

// function deactivateNo(y)
// {
// 	$('#edit'+y).removeClass('disabled');
// 	$('#deactivate'+y).removeClass('disabled');
// 	$('#row'+y).removeClass('danger')

// 	disable_enable_frm('frm_role', false);
// }
