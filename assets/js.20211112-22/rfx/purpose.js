var iCount = 0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	//alert("1");
	getPurpose("all");
});

$(document).on('click','#btn_add_purpose',function(e)
{	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var base_url = $('#b_url').attr('data-base-url');
		var purpose = $("#input_purpose").val();

		//alert(purpose);


		if(isFillUpError("add") == 0){
			addPurpose(purpose);
		}
	}
});

function isFillUpError(crud)
{
	var iError = 0;

	if(crud=="add"){
		$('#modal_add_purpose input').each(function()
		{
			if($(this).val().length == 0){
				$('#'+ this.id).parent('div').addClass('has-error');
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');
			}
		});

		if(iError > 0){
			$('#error_add_purpose').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_add_purpose').text('Please fill up all required fields!');
		}
	}else if(crud=="edit"){
		$('#modal_edit_purpose input').each(function()
		{
			if($(this).val().length == 0){
				$('#'+ this.id).parent('div').addClass('has-error');
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');
			}
		});

		if(iError > 0){
			$('#error_edit_purpose').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_edit_purpose').text('Please fill up all required fields!');
		}

	}
	return iError;
}

$(document).on('click','#btn_search_purpose',function()
{
	var base_url = $('#b_url').attr('data-base-url');
	var purpose = $("#search_purpose").val();

	getPurpose(purpose);
});

$(document).on('click','#btn_save_purpose',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		//var base_url = $('#b_url').attr('data-base-url');
		var purpose_id = $("#edit_id").val();
		var purpose = $("#edit_purpose").val();

		//alert(purpose_id);
		//alert(purpose);
		if(isFillUpError("edit") == 0){
			savePurpose(purpose_id, purpose);
		}
	}
});

$('#modal_add_purpose').on('show.bs.modal', function () {
	$('#modal_add_purpose input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');
		$('#'+ this.id).val('');
	});

	var x = document.getElementById('error_add_purpose');
	x.style.display = 'none';
})

$('#modal_edit_purpose').on('show.bs.modal', function () {
	$('#modal_edit_purpose input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');
	});

	var x = document.getElementById('error_edit_purpose');
	x.style.display = 'none';
})



//========== FUNCTIONS ==========

function getPurpose(purpose)
{
	var base_url = $('#b_url').attr('data-base-url');

	if(purpose == "all"){
		base_url = base_url + "get_all_purpose";
	}else{
		base_url = base_url + "get_purpose";
	}

	$.ajax({
		type:'POST',
		data:{purpose: purpose},
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
		}
	}).fail(function(){

	});
}

function fillArrayTemp(obj_result)
{
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
			tmpArr.push(obj_result[x].PURPOSE_ID+'/'+obj_result[x].PURPOSE);
			x++;
		}
		arr_tmp_tbl.push(tmpArr.join('|'));
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
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();


	for(x=0;x<z;x++){
		y=x+1;
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="purpose'+y+'">'+obj_result[x].PURPOSE+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].PURPOSE_ID+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].PURPOSE_ID+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
	}

}
*/

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

// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="purpose'+y+'">'+n[1]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
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

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="purpose'+y+'">'+obj_result[x].PURPOSE+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].PURPOSE_ID+');return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].PURPOSE_ID+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}

}

function addPurpose(purpose)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{purpose: purpose},
		url: base_url + "add_purpose",
		success: function(result){
			//alert(result);
			//var json_result = $.parseJSON(result);

			$('#modal_add_purpose').modal('hide');

			var data = $.parseJSON(result);

			var span_message = 'Purpose has been successfully saved!';
			var type = 'success';

			if (data) {
				if (!data.status) {
					type = 'danger';
					span_message = data.error;
				}
			}

			//=== NOTIFICATION ===
			notify(span_message, type);

			getPurpose("all");

		}
	}).fail(function(){

	});
}

function savePurpose(purpose_id, purpose)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{purpose_id: purpose_id, purpose: purpose},
		url: base_url + "save_purpose",
		success: function(result){
			$('#modal_edit_purpose').modal('hide');

			//=== NOTIFICATION ===
			var span_message = 'Purpose has been successfully saved!';
			var type = 'success';
			notify(span_message, type);
			getPurpose("all");
		}
	}).fail(function(){

	});
}

function editRow(y, purpose_id){
	var sel_purpose = document.getElementById("purpose"+y).innerHTML;

	$('#edit_id').val(purpose_id);
	$('#edit_purpose').val(sel_purpose);
	$('#modal_edit_purpose').modal('show')
}

function deactivateYesNo(y,purpose_id)
{
	disable_enable_frm('frm_purpose', true);
	$('#row'+y).addClass('danger').siblings().removeClass('danger');
	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate purpose? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+purpose_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);
}

function deactivateYes(y, purpose_id)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{purpose_id: purpose_id},
		url: base_url + "deactivate_purpose",
		success: function(result){
			//alert(result);
			//var json_result = $.parseJSON(result);
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')
			disable_enable_frm('frm_purpose', false);

			var span_message = 'Purpose has been deactivated!';
			var type = 'info';
			notify(span_message, type);

			getPurpose("all");
		}
	}).fail(function(){

	});
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')
	disable_enable_frm('frm_purpose', false);
}

//========== PAGINATION ==========

$(document).on('click','#pg_purpose1',function(){
	pagPg1();
});
$(document).on('click','#pg_purpose2',function(){
	pagPg2();
});
$(document).on('click','#pg_purpose3',function(){
	pagPg3();
});

$(document).on('click','#next_purpose',function(){
	pagNext();
});

$(document).on('click','#prev_purpose',function(){
	pagPrev();
});

function purposePagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;

	//alert(page_count);

	removePage();

	if(page_count>3){

			pageAddPrev();

			for(i=1;i<4;i++){
				$('<li id = "pg_purpose'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#purpose_pagination');
			}

			pageAddNext();

			iCount = page_count;
			return;
	}

	for(i=1;i<page_count+1;i++){
		$('<li id = "pg_purpose'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#purpose_pagination');
	}
	iCount = page_count;
}

function pageAddPrev()
{
	$('#prev_purpose').remove();
	$('<li id = "prev_purpose" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#purpose_pagination');
}

function pageAddNext()
{
	$('#next_purpose').remove();
	$('<li id = "next_purpose" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#purpose_pagination");
}

function pagPg1()
{
	var x = $('#pg_purpose1').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	if(x-1 == 0){
		$('#pg_purpose1').addClass('active');
		$('#pg_purpose2').removeClass('active');
		$('#pg_purpose3').removeClass('active');
		$('#prev_purpose').addClass('disabled');
		return;
	}

	if(x>0){
		var z = x -1;

		removePage();
		pageAddPrev();

		for(i=1;i<=3;i++){
				$('<li id = "pg_purpose'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#purpose_pagination');
				z++;
		}

		pageAddNext();
		pagPg2($('#pg_purpose2').val());
		return;
	}
}

function pagPg2()
{
	$('#prev_purpose').removeClass('disabled');
	$('#next_purpose').removeClass('disabled')

	var x = $('#pg_purpose2').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);

	$('#pg_purpose1').removeClass('active');
	$('#pg_purpose2').addClass('active');
	$('#pg_purpose3').removeClass('active');

}

function pagPg3()
{
	var x = $('#pg_purpose3').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);

	if(x<iCount){
		var z = x-1;

		removePage();
		pageAddPrev();

		for(i=1;i<=3;i++){
			$('<li id = "pg_purpose'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#purpose_pagination');
			z++;

		}

		pageAddNext();
		pagPg2($('#pg_purpose2').val());
		return;
	}

	$('#pg_purpose1').removeClass('active');
	$('#pg_purpose3').addClass('active');
	$('#pg_purpose2').removeClass('active');
	$('#next_purpose').addClass('disabled');
}

function pagNext()
{
	if($("#prev_purpose").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg3();
}
function pagPrev()
{
	if($("#next_purpose").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg1();
}
function removePage()
{
	$("#prev_purpose").remove();
	$("#next_purpose").remove();
	$('#pg_purpose1').remove();
	$('#pg_purpose3').remove();
	$('#pg_purpose2').remove();
}
