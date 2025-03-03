var _selRmv_vst = [];
var req_agmt = [];
var items = [];
var matrix = [];


$(document).ready(function(){
	// alert("1");
	getOrgType();
	$('#orgtype_agmt').hide();
	$('#select_tradevendortype').hide();
	$('#slt_cat').hide();
	get_category();
	//$("#tbl_view").DataTable().destroy();
	//getAllSecondaryAgreement();
});

	$("#select_vendortype").change(function(){
		var vendortype_id = $('#select_vendortype').val();
		
		if(vendortype_id == 1){
			$('#select_tradevendortype').show();
			$('#tradevendortype').show();
			$('#slt_cat').hide();
			$('#slt_cat').val(0);
			get_category(vendortype_id);

		}else if(vendortype_id == 3){
			$('#select_tradevendortype').hide();
			$('#select_tradevendortype').val(0);
			$('#tradevendortype').hide();
			$('#slt_cat').show();
			get_category();
		}else{
			$('#select_tradevendortype').hide();
			$('#slt_cat').hide();
			$('#select_tradevendortype').val(0);
			$('#slt_cat').val(0);
		}



	});

	$("#select_tradevendortype").change(function(){
		var tradevendortype_id = $('#select_tradevendortype').val();
		$('#slt_cat').show();
	});

	$("#btn_search_org").click(function()
	{	
		$div_notifications.fadeOut('slow', clean_div_notif);
		var orgtype_id = $('#select_orgtype').val();
		var vendortype_id = $('#select_vendortype').val();
		var category_id = $('#slt_cat').val();
		var tradevendortype_id = $('#select_tradevendortype').val();
		let orgv = document.getElementById('orgtype_agmt').style = "display:none";
		orgv.visibility = false;
		
		$("#tbl_view").DataTable().destroy();
		$("#tbl_view").find("tr:gt(0)").remove();
		

		if(orgtype_id == 0 || vendortype_id == 0){
			let smess = "";
			if(orgtype_id == 0){
				smess = "<strong>Failed! </strong> Please Select Ownership Type!";
			}
			if(vendortype_id == 0){
				if(orgtype_id == 0){
					smess = smess + "<br>";
					}
				smess = smess + "<strong>Failed! </strong> Please Select Vendor Type!";
			}


		notify(smess, 'danger');	
		return;
		}

/*		if(vendortype_id == 2 && category_id == 0){
			smess = "<strong>Failed! </strong> Please Select Category!";
			notify(smess, 'danger');
			return;
		}
*/
		if(vendortype_id == 1 && tradevendortype_id == 0){
			smess = "<strong>Failed! </strong> Please Select Trade Vendor Type!";
			notify(smess, 'danger');
			return;
		}

		/*
		if (orgtype_id > 0 && vendortype_id == 2){
			$('#orgtype_agmt').show();
			getSecondaryAgreement(orgtype_id, 0);// 0 = NON TRADE
		}else if (orgtype_id > 0 && vendortype_id == 1 && tradevendortype_id > 0){
			$('#orgtype_agmt').show();
			getSecondaryAgreement(orgtype_id, tradevendortype_id);// tradevendortype_id = TRADE VENDOR TYPE
		}else{
			$('#orgtype_agmt').hide();
		}
		*/


	getSecondaryAgreementFiles(orgtype_id,vendortype_id,category_id,tradevendortype_id);

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

	function getAllSecondaryAgreement(orgtype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_all_secagmt";	

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
						pageLength: 30,
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
			//alert(obj_result[x].REQUIRED_AGREEMENT_ID);
			y=x+1;
			$('<tr id="row'+obj_result[x].REQUIRED_AGREEMENT_ID+'" class="clickable-row" class="text-center"><td class="text-center"><input type="checkbox" id="secagmt'+obj_result[x].REQUIRED_AGREEMENT_ID+'" rel="'+obj_result[x].REQUIRED_AGREEMENT_ID+'"><a href=""></a></input></td><td>'+obj_result[x].REQUIRED_AGREEMENT_NAME+'</td></tr>').appendTo('#tbl_body_orgtype_secagmt');
			if (checked=="1"){
				$('#secagmt'+obj_result[x].REQUIRED_AGREEMENT_ID).prop('checked', true)
			}
		}	
	}

	$("#btn_save_defn").click(function()
	{	


		req_agmt = [];
		items = [];
		matrix = [];

		let data = $('#slctd').val().split("/");


		 /*console.log($('#slctd').val());
		 return;*/

		let dname = document.getElementById('edit_info');


		if(document.getElementById('chk_no').checked == false){

				$("input:checkbox").each(function(){
				    var $this = $(this);

				    //console.log($this);

				    if($this.is(":checked")){
				    	//alert($this.attr("rel"));
				         req_agmt.push($this.val());
				    }else{
				       
				    }
				});

				if(req_agmt.length == 0){
					let smess = "";
					smess = "<strong>Failed! </strong> Please select atleast 1 Document.";
					notify(smess, 'danger');
					return;
				}

			var span_message = 'Are you sure you want to change Secondary Documents List for <strong>'+dname.textContent+'</strong> ? <button type="button" class="btn btn-success" onclick="saveAgmt()" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
			var type = 'info';
			items = req_agmt;
			matrix = data;
			console.log(matrix);
			notify(span_message, type, true);
			return;
		}


		matrix = data;
		var span_message = 'Are you sure you want to <strong>REMOVE</strong> all Secondary Documents for <strong>'+dname.textContent+'</strong> ? <button type="button" class="btn btn-success" onclick="removeAgmt()" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
		notify(span_message, 'info', true);

		//saveAgmt(req_agmt,data)
	


	});

	$(document).on('click','#btn_default',function(){

	let dname = document.getElementById('edit_info');
	var span_message = 'Are you sure you want to <strong>RESTORE DEFAULT</strong> Secondary Documents List for <strong>'+dname.textContent+'</strong> ? <button type="button" class="btn btn-success" onclick="restore_default()" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="disable_enable_frm(\'frm_invitecreation\', false);">No</button>';
	var type = 'info';
	notify(span_message, type, true);
	});

	function restore_default()
	{

	loading('btn_default','in_progress');
	let data = $('#slctd').val().split("/");
	var base_url = $('#b_url').attr('data-base-url');

			base_url = base_url + "restore_default";

			$.ajax({
				type:'POST',
				data:{data: data},
				url: base_url,
				success: function(result){	
					$('#btn_search_org').trigger('click');
					smess = "<strong>Success! </strong> Secondary Document List has been updated.";
					notify(smess, 'success');
					loading('btn_default','');
				//force_reload();
				}		
			}).fail(function(){
				
			});	
			//alert(1);
	}

	function removeAgmt()
	{
		var base_url = $('#b_url').attr('data-base-url');
		let l = document.getElementById('edit_info');
		loading('btn_save_defn','in_progress');

		base_url = base_url + "delete_agmt";

		$.ajax({
			type:'POST',
			data:{data: matrix},
			url: base_url,
			success: function(result){
	/*		console.log(result);	*/
			$('#btn_search_org').trigger('click');
			smess = "<strong>Success! </strong> Secondary Documents List for "+l.text+" been remove.";
			notify(smess, 'success');
			loading('btn_save_defn','');
			//force_reload();
			}		
		}).fail(function(){
			
		});	
	}




	function saveAgmt()
	{
		var base_url = $('#b_url').attr('data-base-url');
		loading('btn_save_defn','in_progress');
		base_url = base_url + "save_agmt";

		$.ajax({
			type:'POST',
			data:{items: items,matrix: matrix},
			url: base_url,
			success: function(result){
			//console.log(result);
			//loading('btn_save_defn','');
			//return;	
			$('#btn_search_org').trigger('click');
			smess = "<strong>Success! </strong> Secondary Documents List has been updated.";
			notify(smess, 'success');
			loading('btn_save_defn','');
			}		
		}).fail(function(){
			
		});	
	}



	function getSecondaryAgreement(orgtype_id,vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_secagmt";
		$.ajax({
			type:'POST',
			data:{orgtype_id: orgtype_id, vendortype_id: vendortype_id},
			url: base_url,
			success: function(result){	
				//console.log(result);
				var json_result = $.parseJSON(result);
					createDataTable(json_result, "1");
					getSecondaryAgreementNotIn(orgtype_id,vendortype_id);
			}		
		}).fail(function(){
			
		});	
	}


	function getSecondaryAgreementFiles(org = 0,vendor = 0,cat = 0,trade = 0)
	{

		snd = org + "/" + vendor + "/" + cat + "/" +trade

		//passed as string. mas mabilis "daw" pag string kesa object;

		console.log(snd);

		var base_url = $('#b_url').attr('data-base-url');
		base_url = base_url + "slct_secagmt";
		$.ajax({
			type:'POST',
			data:{snd: snd},
			url: base_url,
			success: function(result){	
				//console.log(result);
				let n = JSON.parse(result);
				//console.log(n);
				let dm = {ds : n}
				let tmpl = document.getElementById('secReq').innerHTML;
				let htmls = Mustache.render(tmpl,dm);
				let table = document.getElementById('tbl_body_orgtype_secagmt');
				let vt = '';
				let it = '';
				let ot ='';
				let at = '';
				let cat = '';
				let stype = '';
				let orgv = document.getElementById('orgtype_agmt').style = "display:block";
				table.innerHTML = htmls;
				orgv.visibility = 'visible';

				ot = $('#select_orgtype option:selected').text();

				let ots = document.getElementById('select_orgtype');
				ot = ots.options[ots.selectedIndex].text;
				ots = document.getElementById('select_vendortype');
				vt = ots.options[ots.selectedIndex].text;
			
				document.getElementById('btn_default').style.display = 'none';
				document.getElementById('chk').style.display = 'none';

				
				if(vendor == 1){
					let ats = document.getElementById('slt_cat');
					at = ats.options[ats.selectedIndex].value;

					let its = document.getElementById('select_tradevendortype');
					it = its.options[its.selectedIndex].text;

				}else if(vendor == 2){
					it = '';
				}else{
					let ats = document.getElementById('slt_cat');
					at = ats.options[ats.selectedIndex].value;
					if(at != 0){
						document.getElementById('btn_default').style.display = 'block';
						document.getElementById('chk').style.display = 'block';
					}
					it = ats.options[ats.selectedIndex].text;
			
				}

				let ats = document.getElementById('slt_cat');
				cat = ats.options[ats.selectedIndex].text;
			

				let i = 0;

				$('#tbl_view input[type=checkbox]').each(function(){
					//console.log(this);
					if(this.checked == true){
						i++;
					}
				})

				if(trade == 0){
					if((i == 0) && (cat != 0)){
						$('#tbl_view input[type=checkbox]').prop('disabled',true);	
						$('#chk_no').prop('checked',true);
					}else{
						$('#chk_no').prop('checked',false);
						$('#tbl_view input[type=checkbox]').prop('disabled',false);	
					}
				}

				if($('#select_vendortype').val() == 1){
					$('#edit_info').text(ot + " ⟶ " + vt + " ⟶ " + it + " ⟶ " + cat);
				}else{
					$('#edit_info').text(ot + " ⟶ " + vt + " ⟶ " + it);
				}


				$('#slctd').val( $('#select_orgtype option:selected').val() +"/"+ $('#select_vendortype option:selected').val() +"/" + $('#select_tradevendortype option:selected').val() +"/"+ at);
				
				
				
				



				$("#tbl_view").DataTable({
					pageLength: 30,
					dom:'<"top state"t<"clear">>rt<"bottom user"p<"clear">>',
					language: {
					paginate: {
					previous: '‹‹',
					next:     '››'
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



	function getSecondaryAgreementNotIn(orgtype_id,vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');

		base_url = base_url + "get_secagmt_not_in";

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
						pageLength: 30,
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
				// }else{
				// 	var span_message = 'Record not found!';
				// 	var type = 'danger';
				// 	notify(span_message, type);				
				
				// }

			}		
		}).fail(function(){
			
		});	
	}

	function check_select(x)
	{

		$div_notifications.stop().fadeOut("slow", clean_div_notif);  
		var n = x.getAttribute('data-sel');

		if(x.checked){
			secagmt_arr.push(n);
			return;
		}

		for(i=0;i<secagmt_arr.length;i++){
			if(n == secagmt_arr[i]){
				secagmt_arr.splice(i,1);
				break;
			}
		}
		return;

	}

	function get_category(vendortype_id)
	{
		var base_url = $('#b_url').attr('data-base-url');
		$.ajax({
			type:'POST',
			data:{vendortype_id: vendortype_id},
			url:base_url+"get_category",
			success:function(result){
		//	console.log(result);

				let n = JSON.parse(result);

				let dm = {ds : n}

				let tmpl = document.getElementById('category').innerHTML;
				let htmls = Mustache.render(tmpl,dm);
				let table = document.getElementById('slt_cat');

				//console.log(table);

				table.innerHTML = htmls;


			}

		}).fail(function(result){

		});


		//});

	}


	$(document).on('change','#chk_no',function(){

		if(this.checked == true){
			$("#tbl_view input[type='checkbox']").prop('disabled',true);
		}else{
			$("#tbl_view input[type='checkbox']").prop('disabled',false);
		}


	});




	// getScreen("0");



	// $("#btn_save_secagmts").click(function()
	// {	
	// 	var secagmts = [];


	// 	var orgtype_id = $('#select_orgtype').val();
	// 	$("input:checkbox").each(function(){
	// 	    var $this = $(this);

	// 	    if($this.is(":checked")){
	// 	         secagmts.push($this.attr("rel"));
	// 	    }else{
		       
	// 	    }
	// 	});

	// 	saveScreens(orgtype_id, secagmts);
			
	// });

	//========== FUNCTIONS ==========






	// function getAllScreens(orgtype_id)
	// {
	// 	var base_url = $('#b_url').attr('data-base-url');

	// 	base_url = base_url + "get_all_secagmts";	

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


// 	function saveScreens(orgtype_id, secagmts)
// 	{
// 		var base_url = $('#b_url').attr('data-base-url');

// 		base_url = base_url + "save_secagmts";

// 		$.ajax({
// 			type:'POST',
// 			data:{orgtype_id: orgtype_id, secagmts: secagmts},
// 			url: base_url,
// 			success: function(result){	

// 			}		
// 		}).fail(function(){
			
// 		});	
// 	}

// 	function pushScreens(secagmt_id){
// 		var orgtype_id = $('#select_orgtype').val();

// 		secagmts.push({orgtype_id: orgtype_id, secagmt_id: secagmt_id});

// 		alert(secagmts[0]['orgtype_id']);
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
