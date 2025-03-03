var iCount = 0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	getStatus("all");
});

$(document).on('click','#btn_add_status',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var base_url = $('#b_url').attr('data-base-url');
		var rfx_status = $("#input_status").val();
		var iError = 0;
		
		if(isFillUpError("add") == 0){
			addStatus(rfx_status, base_url);	
		}
	}
});

$(document).on('click','#btn_save_status',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var base_url = $('#b_url').attr('data-base-url');
		var status_id = $("#edit_id").val();
		var rfx_status = $("#edit_status").val();

		if(isFillUpError("edit") == 0){
			saveStatus(status_id, rfx_status, base_url);	
		}
	}
});

$(document).on('click','#btn_search_status',function()
{
	var base_url = $('#b_url').attr('data-base-url');
	var rfx_status = $("#search_status").val();
	
	getStatus(rfx_status);	
});

//========== FUNCTIONS ==========

function getStatus(rfx_status)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(rfx_status == "all"){
		var controller_func = "get_all_status";
	}else{
		var controller_func = "get_status";
	}	

	$.ajax({
		type:'POST',
		data:{rfx_status: rfx_status},
		url: base_url + controller_func,
		success: function(result){	
			var json_result = $.parseJSON(result);
			
			if(json_result.status != false){
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
				var span_message = 'Status not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
	
	});	
}

function fillArrayTemp(obj_result)
{
	//alert(iCount);
	var x=0;
	var i=0;
	var j=0;
	var tmpArr = [];
	
	arr_tmp_tbl	= [];
	for(i=0;i<iCount;i++)
	{		
		if(x == obj_result.length){break;}
		for(j=0;j<10;j++)
		{
			
			if(x == obj_result.length){break;}
			tmpArr.push(obj_result[x].STATUS_ID+'/'+obj_result[x].STATUS);	
			x++;
		}		
		
		arr_tmp_tbl.push(tmpArr.join('|'));
		//alert(arr_tmp_tbl[i]);
		tmpArr = [];		
	}
	
	
	createTable(arr_tmp_tbl[0],1);
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
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="status'+y+'">'+obj_result[x].STATUS+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].STATUS_ID+');return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].STATUS_ID+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}

// function createTable(obj, page_num)
// {
// 	var x=0;
// 	var y=0;
// 	var i=0;
// 	var j=10*(page_num-1);
	
// 	var obj_tbl = obj.split('|');
	
// 	$("#tbl_view").find("tr:gt(0)").remove();
	
// 	for(x=0;x<obj_tbl.length;x++){
// 		var n = obj_tbl[x].split('/');
// 		y=x+1+j;
		
// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="status'+y+'">'+n[1]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');		
// 	}
// }

/*
function createTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();

	
	for(x=0;x<z;x++){
		y=x+1;
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="status'+y+'">'+obj_result[x].STATUS+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].STATUS_ID+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].STATUS_ID+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
	}
	
}
*/

function addStatus(rfx_status)
{
	$("#input_status").val('');
	var base_url = $('#b_url').attr('data-base-url');
	//alert(rfx_status);
	$.ajax({
		type:'POST',
		data:{rfx_status: rfx_status},
		url: base_url + "add_status",
		success: function(result){	
			$('#modal_add_status').modal('hide');
			
			//=== NOTIFICATION ===
			var span_message = 'Status has been successfully saved!';
			var type = 'success';
			notify(span_message, type);					
			
			getStatus("all");
		}		
	}).fail(function(){
		
	});	
}

function saveStatus(status_id, rfx_status, base_url)
{
	//alert(status_id + " " + rfx_status + " " + base_url);
	$.ajax({
		type:'POST',
		data:{status_id: status_id, rfx_status: rfx_status},
		url: base_url + "save_status",
		success: function(result){	
			//alert(result);
			//var json_result = $.parseJSON(result);
			$('#modal_edit_status').modal('hide');
			
			var span_message = 'Status has been successfully saved!';
			var type = 'success';
			notify(span_message, type);		
			getStatus("all");
			//alert(json_result.length);
			//createTable(json_result);	
		}		
	}).fail(function(){
		});		
}

function editRow(y, status_id){
	var sel_status = document.getElementById("status"+y).innerHTML;

	$('#edit_id').val(status_id);
	$('#edit_status').val(sel_status);
	$('#modal_edit_status').modal('show')
}

function deactivateYesNo(y,status_id)
{
	disable_enable_frm('frm_status', true);
	$('#row'+y).addClass('danger').siblings().removeClass('danger');
	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate status? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+status_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(y, status_id)
{
	var base_url = $('#b_url').attr('data-base-url');	

	$.ajax({
		type:'POST',
		data:{status_id: status_id},
		url: base_url + "deactivate_status",
		success: function(result){	
			//alert(result);
			//var json_result = $.parseJSON(result);
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')
			disable_enable_frm('frm_status', false);
			
			var span_message = 'Status has been deactivated!';
			var type = 'info';
			notify(span_message, type);	
			
			getStatus("all");
		}		
	}).fail(function(){
		
	});	
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')
	disable_enable_frm('frm_status', false);
}

function isFillUpError(crud)
{
	var iError = 0;
	
	//alert(crud);
	
	if (crud=="add"){
		
		$('#modal_add_status input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_add_status').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_add_status').text('Please fill up all required fields!');	
		}
	}else if (crud=="edit"){
		$('#modal_edit_status input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_edit_status').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_edit_status').text('Please fill up all required fields!');	
		}		
	}
	
	//alert(iError);
	
	return iError;
}

//========== PAGINATION ==========

$(document).on('click','#pg_status1',function(){	
	pagPg1();	
});
$(document).on('click','#pg_status2',function(){	
	pagPg2();	
});
$(document).on('click','#pg_status3',function(){	
	pagPg3();	
});

$(document).on('click','#next_status',function(){	
	pagNext();	
});

$(document).on('click','#prev_status',function(){	
	pagPrev();	
});

function statusPagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;

	removePage();
	
	if(page_count>3){
		
			pageAddPrev();
			
			for(i=1;i<4;i++){
				$('<li id = "pg_status'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#status_pagination');
			}
			
			pageAddNext();
			
			iCount = page_count;
			return;			
	}
		
	for(i=1;i<page_count+1;i++){
		$('<li id = "pg_status'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#status_pagination');
	}	
	iCount = page_count;		
}

function pageAddPrev()
{
	$('#prev_status').remove();
	$('<li id = "prev_status" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#status_pagination');
}

function pageAddNext()
{
	$('#next_status').remove();
	$('<li id = "next_status" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#status_pagination");
}

function pagPg1()
{	
	var x = $('#pg_status1').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	if(x-1 == 0){	
		$('#pg_status1').addClass('active');
		$('#pg_status2').removeClass('active');
		$('#pg_status3').removeClass('active');
		$('#prev_status').addClass('disabled');
		return;
	}	
	
	if(x>0){		
		var z = x -1;
		
		removePage();
		pageAddPrev();
		
		for(i=1;i<=3;i++){	
				$('<li id = "pg_status'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#status_pagination');
				z++;		
		}
		
		pageAddNext();
		pagPg2($('#pg_status2').val());			 
		return;		
	}
}

function pagPg2()
{
	$('#prev_status').removeClass('disabled');
	$('#next_status').removeClass('disabled')
	
	var x = $('#pg_status2').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);	
	
	$('#pg_status1').removeClass('active');
	$('#pg_status2').addClass('active');
	$('#pg_status3').removeClass('active');
	
}

function pagPg3()
{
	var x = $('#pg_status3').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	
	if(x<iCount){		
		var z = x-1;
	
		removePage();
		pageAddPrev();
				
		for(i=1;i<=3;i++){	
			$('<li id = "pg_status'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#status_pagination');
			z++;		
			
		}
		
		pageAddNext();
		pagPg2($('#pg_status2').val());
		return;	
	}
	
	$('#pg_status1').removeClass('active');
	$('#pg_status3').addClass('active');
	$('#pg_status2').removeClass('active');
	$('#next_status').addClass('disabled');
}

function pagNext()
{
	if($("#prev_status").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg3();
}
function pagPrev()
{
	if($("#next_status").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg1();
}
function removePage()
{
	$("#prev_status").remove();
	$("#next_status").remove();
	$('#pg_status1').remove();
	$('#pg_status3').remove();
	$('#pg_status2').remove();
}

$('#modal_add_status').on('hidden.bs.modal', function (e) {
	$("#input_status").val('');
});
$('#modal_edit_status').on('hidden.bs.modal', function (e) {
	$("#input_status").val('');
});