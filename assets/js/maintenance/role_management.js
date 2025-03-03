var iCount=0;
var arr_tmp_tbl;
var screens = [];


$(document).ready(function(){

	getPosition();
	getVendorType();
	//getScreen("0");
	$('#btn_save_screens').prop('disabled', true);
	
});

$(document).on("change", "#select_position", function(){
	
	var id = $(this).val();
	if(id  == 10){
		$("#vendor_type_container").css("display","");
		$("#select_vendor_type").val("-1");
		$("#btn_search_role").attr("disabled","disabled");
	}else{
		$("#vendor_type_container").css("display","none");
			
		if(id  != -1){
			$("#btn_search_role").removeAttr("disabled");
		}else{
			$("#btn_search_role").attr("disabled","disabled");
		}
	}
});

$(document).on("change", "#select_vendor_type", function(){
	
	var id = $(this).val();
	if(id  != -1){
		$("#btn_search_role").removeAttr("disabled");
	}else{
		$("#btn_search_role").attr("disabled","disabled");
	}
});

$("#btn_search_role").click(function()
{	
	var position_id = $('#select_position').val();
	//alert(position_id);
	$('#btn_save_screens').prop('disabled', false);
	
	screens = []
	$("#tbl_view").DataTable().destroy();
	$("#tbl_view").find("tr:gt(0)").remove();

	$("#selected_position").text($("#select_position option:selected").text());

	getScreen(position_id);
});


$(document).on("click", ".checkbox", function () {
	
	//alert(screens);

	if ($(this).is(':checked')){
        screens.push($(this).attr('rel'));
		//alert($(this).attr('rel'));
    }else{
        var index = screens.indexOf($(this).attr('rel'));
		if (index > -1) {
		    screens.splice(index, 1);
		}
    }

    //alert(screens);
});

$("#btn_save_screens").click(function()
{	
	var chkCounter = 0;

	var position_id = $('#select_position').val();

	$("input:checkbox").each(function(){
	    var $this = $(this);

	    if($this.is(":checked")){
	    	chkCounter++;

	    }else{
	       
	    }
	});

	//alert(screens);

	if (chkCounter == 0) {

	}else{
		saveScreens(position_id, screens);
	}	
});


function getVendorType()
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		url: base_url + "get_vendor_type",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			
			populateVendorType(json_result);
	
		}		
	}).fail(function(){
		
	});	
}

function getPosition()
{
	var base_url = $('#b_url').attr('data-base-url');
	
	// if(role == "all"){
	// 	base_url = base_url + "get_all_role";
	// }else{
	// 	base_url = base_url + "get_role";
	// }
	
	$.ajax({
		type:'POST',
		url: base_url + "get_position",
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);
			
			populatePosition(json_result);
			// if (json_result.status != false){
			// 	$("#tbl_view").DataTable().destroy();
			// 	createDataTable(json_result);
			// 	$("#tbl_view").DataTable({
			// 		"pageLength": 10
			// 	});
			//}else{
				// var span_message = 'Record not found!';
				// var type = 'danger';
				// notify(span_message, type);				
			//}
		}		
	}).fail(function(){
		
	});	
}

function populateVendorType(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;



	$('#select_vendor_type').find('option').remove().end()	
	$('<option value = "-1">Select Vendor Type...</option>').appendTo('#select_vendor_type');
	$("#btn_search_role").attr("disabled","disabled");
	for(x=0;x<z;x++){		
		$('<option value = "'+obj_result[x].VENDOR_TYPE_ID+'">'+obj_result[x].VENDOR_TYPE_NAME +'</option>').appendTo('#select_vendor_type');
	}
}

function populatePosition(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;



	$('#select_position').find('option').remove().end()	
	$('<option value = "-1">Select Position...</option>').appendTo('#select_position');
	for(x=0;x<z;x++){		
		$('<option value = "'+obj_result[x].POSITION_ID+'">'+obj_result[x].POSITION_NAME +'</option>').appendTo('#select_position');
	}
}

function getAllScreens()
{
	var base_url = $('#b_url').attr('data-base-url');

	base_url = base_url + "get_all_screens";	

	//alert(base_url);
	
	$.ajax({
		type:'POST',
		url: base_url,
		success: function(result){	
			// alert(getScreen(position_id));
			
			var json_result = $.parseJSON(result);



			//populatePosition(json_result);
			if (json_result.status != false){
				$("#tbl_view").DataTable().destroy();
				createCheckAllTable(json_result);
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

				//getScreen(position_id)
			}else{
				var span_message = 'Record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

function getScreen(position_id)
{
	var base_url = $('#b_url').attr('data-base-url');
	var vendor_type_id = '';
	if(position_id == 10){
		vendor_type_id = $("#select_vendor_type").val();
	}
	base_url = base_url + "get_screens";
	
	$.ajax({
		type:'POST',
		data:{position_id: position_id, vendor_type_id: vendor_type_id},
		url: base_url,
		success: function(result){	
			// alert(result);
			
			var json_result = $.parseJSON(result);

			// if (json_result.status != false){
				$("#tbl_view").DataTable().destroy();
				$("#tbl_view").find("tr:gt(0)").remove();
				screens = [];
				createDataTable(json_result, "1");
				//alert("getscreen " + screens);
				getScreenNotIn(position_id);
		}		
	}).fail(function(){
		
	});	
}

function getScreenNotIn(position_id)
{
	var base_url = $('#b_url').attr('data-base-url');
	var vendor_type_id = '';
	if(position_id == 10){
		vendor_type_id = $("#select_vendor_type").val();
	}
	
	base_url = base_url + "get_screens_not_in";
	
	$.ajax({
		type:'POST',
		data:{position_id: position_id, vendor_type_id:vendor_type_id},
		url: base_url,
		success: function(result){	

			var json_result = $.parseJSON(result);

			if (json_result.status != false){

				createDataTable(json_result, "0");
				//screens = [];
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
		y=x+1;
		$('<tr id="row'+obj_result[x].SCREEN_ID+'" class="clickable-row" class="text-center"><td class="text-center"><input type="checkbox" class="checkbox" id="screen'+obj_result[x].SCREEN_ID+'" rel="'+obj_result[x].SCREEN_ID+'"><a href="javascript:pushScreens('+obj_result[x].SCREEN_ID+')"></a></input></td><td>'+obj_result[x].SCREEN_NAME+'</td><td>'+((obj_result[x].DESCRIPTION == null) ? '<i>No desciption yet.</i>' : obj_result[x].DESCRIPTION)+'</td></tr>').appendTo('#tbl_body');
		if (checked=="1"){
			$('#screen'+obj_result[x].SCREEN_ID).prop('checked', true)
			screens.push(obj_result[x].SCREEN_ID);
		}
	}

	//alert(screens);
}

function createCheckAllTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;

	for(x=0;x<z;x++){
		y=x+1;
		$('<tr id="row'+obj_result[x].SCREEN_ID+'" class="clickable-row" class="text-center"><td class="text-center"><input type="checkbox" class="checkbox" id="screen'+obj_result[x].SCREEN_ID+'" rel="'+obj_result[x].SCREEN_ID+'"><a href="javascript:pushScreens('+obj_result[x].SCREEN_ID+')"></a></input></td><td>'+obj_result[x].SCREEN_NAME+'</td></tr>').appendTo('#tbl_body');
		
		$('#screen'+obj_result[x].SCREEN_ID).prop('checked', true)
		screens.push(obj_result[x].SCREEN_ID);
		
	}

}



$(document).on('click','#checkAll',function(){

	screens = [];
	// $("#tbl_view").DataTable().destroy();
	// $("#tbl_view").find("tr:gt(0)").remove();

	if($(this).is(":checked")){
		//alert("1");
		getAllScreens();
	}else{
		getScreen("0");
	}
	
});


function saveScreens(position_id, screens)
{
	var base_url = $('#b_url').attr('data-base-url');
	var vendor_type_id = '';
	if(position_id == 10){
		vendor_type_id = $("#select_vendor_type").val();
	}
	base_url = base_url + "save_screens";

	$.ajax({
		type:'POST',
		data:{position_id: position_id, vendor_type_id:vendor_type_id,screens: screens},
		url: base_url,
		success: function(result){	

			var span_message = 'Screens has been successfully saved!';
			var type = 'success';
			notify(span_message, type);	
			//getPosition();
			screens = [];
			getScreen(position_id);
		}		
	}).fail(function(){
		
	});	
}

function pushScreens(screen_id){
	var position_id = $('#select_position').val();

	screens.push({position_id: position_id, screen_id: screen_id});

	alert(screens[0]['position_id']);
}
