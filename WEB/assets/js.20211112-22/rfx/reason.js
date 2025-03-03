var iCount = 0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	getReason("all");
});

$(document).on('click','#btn_add_reason',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var base_url = $('#b_url').attr('data-base-url');
		var reason = $("#input_reason").val();
		var iError = 0;

		if(isFillUpError() == 0){
			addReason(reason);
		}
	}
});


function isFillUpError()
{
	var iError = 0;

	$('#add_modal_reason input').each(function()
	{
		if($(this).val().length == 0){
			$('#'+ this.id).parent('div').addClass('has-error');
			iError++;
		}else{
			$('#'+ this.id).parent('div').removeClass('has-error');
		}
	});

	if(iError > 0){
		$('#div_add_error').fadeIn('slow').delay(5000).fadeOut('slow');
		$('#div_add_error').text('Please fill up all required fields!');
	}

	return iError;
}


$(document).on('click','#btn_save_reason',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var base_url = $('#b_url').attr('data-base-url');
		var reason_id = $("#edit_id").val();
		var reason = $("#edit_reason").val();

		saveReason(reason_id, reason);
	}
});

$(document).on('click','#btn_search_reason',function()
{
	var base_url = $('#b_url').attr('data-base-url');
	var reason = $("#search_reason").val();

	getReason(reason);
});

$('#add_modal_reason').on('show.bs.modal', function () {
	$('#add_modal_reason input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');
		$('#'+ this.id).val('');
	});

	var x = document.getElementById('div_add_error');
	x.style.display = 'none';
})

$('#edit_modal_reason').on('show.bs.modal', function () {
	$('#edit_modal_reason input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');
		//$('#'+ this.id).val('');
	});

	var x = document.getElementById('div_edit_error');
	x.style.display = 'none';
})

//========== FUNCTIONS ==========

function getReason(reason)
{
	var base_url = $('#b_url').attr('data-base-url');

	if(reason == "all"){
		base_url = base_url + "get_all_reason";
	}else{
		base_url = base_url + "get_reason";
	}

	$.ajax({
		type:'POST',
		data:{reason: reason},
		url: base_url,
		success: function(result){
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

			// reasonPagination(json_result.length);
			// fillArrayTemp(json_result);
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
			tmpArr.push(obj_result[x].REASON_ID+'/'+obj_result[x].REASON);
			x++;
		}

		arr_tmp_tbl.push(tmpArr.join('|'));
		//alert(arr_tmp_tbl[i]);
		tmpArr = [];
	}


	createTable(arr_tmp_tbl[0],1);
}

/*
function createTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;

	alert(obj_result);

	$("#tbl_view").find("tr:gt(0)").remove();

	for(x=0;x<z;x++){
		y=x+1;
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="reason'+y+'">'+obj_result[x].REASON+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].REASON_ID+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].REASON_ID+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
	}

}*/

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

// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="reason'+y+'">'+n[1]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
// 	}
// }

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();


	for(x=0;x<z;x++){
		y=x+1;
		// $('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="orgtype_name'+y+'">'+obj_result[x].ORGTYPE_NAME+'</td><td id="description'+y+'">'+obj_result[x].DESCRIPTION+'</td><td id="bus_division'+y+'">'+obj_result[x].BUS_DIVISION+'</td><td id="orgtype_date'+y+'">'+obj_result[x].ORGTYPE_DATE+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].ORGTYPE_ID+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].ORGTYPE_ID+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="reason'+y+'">'+obj_result[x].REASON+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].REASON_ID+'); return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].REASON_ID+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}

}

function addReason(reason)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{reason: reason},
		url: base_url + "add_reason",
		success: function(result){
			$('#add_modal_reason').modal('hide');
			var data = $.parseJSON(result);

			var span_message = 'Reason has been successfully saved!'; //'Reason has been successfully saved!'
			var type = 'success';

			if (data) {
				if (!data.status) {
					type = 'danger';
					span_message = data.error;
				}
			}
			//=== NOTIFICATION ===
			notify(span_message, type);
 
			getReason("all", base_url);
		}
	}).fail(function(){

	});
}

function saveReason(reason_id, reason)
{
	var base_url = $('#b_url').attr('data-base-url');

	//alert(reason_id + " " + reason + " " + base_url);
	$.ajax({
		type:'POST',
		data:{reason_id: reason_id, reason: reason},
		url: base_url + "save_reason",
		success: function(result){
			//$('#edit_modal_reason').modal('hide');
			$('#edit_modal_reason').modal('hide');

			//=== NOTIFICATION ===
			var span_message = 'Reason has been successfully saved!';
			var type = 'success';
			notify(span_message, type);

			getReason("all");
		}
	}).fail(function(){

	});
}

function editRow(y, reason_id){
	//alert(y + " " + reason_id);

	var sel_reason = document.getElementById("reason"+y).innerHTML;

	//alert(y + " " + reason_id + " " + sel_reason);

	$('#edit_id').val(reason_id);
	$('#edit_reason').val(sel_reason);
	$('#edit_modal_reason').modal('show')
}

function deactivateYesNo(y,reason_id)
{
	disable_enable_frm('frm_reason', true);
	$('#row'+y).addClass('danger').siblings().removeClass('danger');
	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	//=== NOTIFICATION ===
	var span_message = 'Are you sure you want to deactivate reason? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+reason_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);
}

function deactivateYes(y, reason_id)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{reason_id: reason_id},
		url: base_url + "deactivate_reason",
		success: function(result){
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')
			disable_enable_frm('frm_reason', false);

			//=== NOTIFICATION ===
			var span_message = 'Reason has been deactivated!';
			var type = 'info';
			notify(span_message, type);

			getReason("all");
		}
	}).fail(function(){

	});
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')
	disable_enable_frm('frm_reason', false);
}

//========== PAGINATION ==========

$(document).on('click','#pg_reason1',function(){
	pagPg1();
});
$(document).on('click','#pg_reason2',function(){
	pagPg2();
});
$(document).on('click','#pg_reason3',function(){
	pagPg3();
});

$(document).on('click','#next_reason',function(){
	pagNext();
});

$(document).on('click','#prev_reason',function(){
	pagPrev();
});

function reasonPagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;

	removePage();

	if(page_count>3){

			pageAddPrev();

			for(i=1;i<4;i++){
				$('<li id = "pg_reason'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#reason_pagination');
			}

			pageAddNext();

			iCount = page_count;
			return;
	}

	for(i=1;i<page_count+1;i++){
		$('<li id = "pg_reason'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#reason_pagination');
	}
	iCount = page_count;
}

function pageAddPrev()
{
	$('#prev_reason').remove();
	$('<li id = "prev_reason" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#reason_pagination');
}

function pageAddNext()
{
	$('#next_reason').remove();
	$('<li id = "next_reason" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#reason_pagination");
}

function pagPg1()
{
	var x = $('#pg_reason1').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	if(x-1 == 0){
		$('#pg_reason1').addClass('active');
		$('#pg_reason2').removeClass('active');
		$('#pg_reason3').removeClass('active');
		$('#prev_reason').addClass('disabled');
		return;
	}

	if(x>0){
		var z = x -1;

		removePage();
		pageAddPrev();

		for(i=1;i<=3;i++){
				$('<li id = "pg_reason'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#reason_pagination');
				z++;
		}

		pageAddNext();
		pagPg2($('#pg_reason2').val());
		return;
	}
}

function pagPg2()
{
	$('#prev_reason').removeClass('disabled');
	$('#next_reason').removeClass('disabled')

	var x = $('#pg_reason2').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);

	$('#pg_reason1').removeClass('active');
	$('#pg_reason2').addClass('active');
	$('#pg_reason3').removeClass('active');

}

function pagPg3()
{
	var x = $('#pg_reason3').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);

	if(x<iCount){
		var z = x-1;

		removePage();
		pageAddPrev();

		for(i=1;i<=3;i++){
			$('<li id = "pg_reason'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#reason_pagination');
			z++;

		}

		pageAddNext();
		pagPg2($('#pg_reason2').val());
		return;
	}

	$('#pg_reason1').removeClass('active');
	$('#pg_reason3').addClass('active');
	$('#pg_reason2').removeClass('active');
	$('#next_reason').addClass('disabled');
}

function pagNext()
{
	if($("#prev_reason").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg3();
}
function pagPrev()
{
	if($("#next_reason").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg1();
}
function removePage()
{
	$("#prev_reason").remove();
	$("#next_reason").remove();
	$('#pg_reason1').remove();
	$('#pg_reason3').remove();
	$('#pg_reason2').remove();
}
