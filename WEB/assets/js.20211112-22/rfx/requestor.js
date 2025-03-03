var iCount = 0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	//alert("1");
	getRequestor("all");
});


$(document).on('change','#select_requestor',function()
{
	var requestor_id = $("#select_requestor").val();
	var base_url = $('#b_url').attr('data-base-url');
	
	getRequestor(requestor_id);
});

$(document).on('click','#add_requestor',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var requestor = $("#input_requestor").val();
		var company = $("#input_company").val();
		var department = $("#input_department").val();
		var iError = 0;
			
		if(isFillUpError() == 0){
			addRequestor(requestor, company, department);	
		}
	}
});

function isFillUpError()
{
	var iError = 0;
	
	$('#add_modal_requestor input').each(function()
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

$(document).on('click','#save_requestor',function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var requestor_id = $("#edit_id").val();
		var requestor = $("#edit_requestor").val();
		var company = $("#edit_company").val();
		var department = $("#edit_department").val();
		var isValid = true;
		var iError = 0;

		$('#edit_modal_requestor input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#div_edit_error').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#div_edit_error').text('Please fill up all required fields!')
		}

		saveRequestor(requestor_id, requestor, company, department);
	}
});

$(document).on('click','#btn_search',function()
{
	var requestor = $("#search_requestor").val();
	
	getRequestor(requestor);	
});

$('#add_modal_requestor').on('show.bs.modal', function () {
	$('#add_modal_requestor input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	var x = document.getElementById('div_add_error');
	x.style.display = 'none';
})


//========== FUNCTIONS ==========

function getRequestor(requestor)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(requestor == "all"){
		base_url = base_url + "get_all_requestor";
	}else{
		base_url = base_url + "get_requestor";
	}
	

	$.ajax({
		type:'POST',
		data:{requestor: requestor},
		url: base_url,
		success: function(result){	
			//alert(result);
			var json_result = $.parseJSON(result);

			if (json_result.status != false){
				//currencyPagination(json_result.length);
				//fillArrayTemp(json_result);
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
				var span_message = 'Requestor record not found!';
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
			tmpArr.push(obj_result[x].REQUESTOR_ID+'/'+obj_result[x].REQUESTOR+'/'+obj_result[x].COMPANY+'/'+obj_result[x].DEPARTMENT);	
			x++;
		}		
		arr_tmp_tbl.push(tmpArr.join('|'));
		tmpArr = [];		
	}
	
	
	createTable(arr_tmp_tbl[0],1);
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
		
// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="requestor'+y+'">'+n[1]+'</td><td id="company'+y+'">'+n[2]+'</td><td id="department'+y+'">'+n[3]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');		
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
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="requestor'+y+'">'+obj_result[x].REQUESTOR+'</td><td id="company'+y+'">'+obj_result[x].COMPANY+'</td><td id="department'+y+'">'+obj_result[x].DEPARTMENT+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].REQUESTOR_ID+'); return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].REQUESTOR_ID+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}


function addRequestor(requestor, company, department)
{	
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{requestor: requestor, company: company, department: department},
		url: base_url + "add_requestor",
		success: function(result){			
			$('#add_modal_requestor').modal('hide');
			
			//=== NOTIFICATION ===
			var span_message = 'Requestor has been successfully saved!';
			var type = 'success';
			notify(span_message, type);					
			
			getRequestor("all");
		}		
	}).fail(function(){
		
	});	
}


function saveRequestor(requestor_id, requestor, company, department)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{requestor_id: requestor_id, requestor: requestor, company: company, department: department},
		url: base_url + "save_requestor",
		success: function(result){	
			$('#edit_modal_requestor').modal('hide');
			
			//=== NOTIFICATION ===
			var span_message = 'Requestor has been successfully saved!';
			var type = 'success';
			notify(span_message, type);			
			
			getRequestor("all");
		}		
	}).fail(function(){
		
	});		
}


function editRow(y, requestor_id){
	var sel_requestor = document.getElementById("requestor"+y).innerHTML;
	var sel_company = document.getElementById("company"+y).innerHTML;
	var sel_department = document.getElementById("department"+y).innerHTML;

	$('#edit_id').val(requestor_id);
	$('#edit_requestor').val(sel_requestor);
	$('#edit_company').val(sel_company);
	$('#edit_department').val(sel_department);
	$('#edit_modal_requestor').modal('show')
}

function deactivateYesNo(y,requestor_id)
{
	disable_enable_frm('frm_requestor', true);
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');
	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	//=== NOTIFICATION ===
	var span_message = 'Are you sure you want to deactivate requestor? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+requestor_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(y, requestor_id)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{requestor_id: requestor_id},
		url: base_url + "deactivate_requestor",
		success: function(result){	
			
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')

			disable_enable_frm('frm_requestor', false);
			
			//=== NOTIFICATION ===
			var span_message = 'Requestor has been deactivated!';
			var type = 'info';
			notify(span_message, type);		

			
			getRequestor("all");
		}		
	}).fail(function(){
		
	});	
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')

	disable_enable_frm('frm_requestor', false);
}



//========== PAGINATION ==========

$(document).on('click','#pag_pg1',function(){	
	pagPg1();	
});
$(document).on('click','#pag_pg2',function(){	
	pagPg2();	
});
$(document).on('click','#pag_pg3',function(){	
	pagPg3();	
});

$(document).on('click','#pag_next',function(){	
	pagNext();	
});

$(document).on('click','#pag_prev',function(){	
	pagPrev();	
});

function requestorPagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;

	removePage();
	
	if(page_count>3){
		
			pageAddPrev();
			
			for(i=1;i<4;i++){
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#requestor_pagination');
			}
			
			pageAddNext();
			
			iCount = page_count;
			return;			
	}
		
	for(i=1;i<page_count+1;i++){
		$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#requestor_pagination');
	}	
	iCount = page_count;		
}

function pageAddPrev()
{
	$('#pag_prev').remove();
	$('<li id = "pag_prev" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#requestor_pagination');
}

function pageAddNext()
{
	$('#pag_next').remove();
	$('<li id = "pag_next" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#requestor_pagination");
}

function pagPg1()
{	
	var x = $('#pag_pg1').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	if(x-1 == 0){	
		$('#pag_pg1').addClass('active');
		$('#pag_pg2').removeClass('active');
		$('#pag_pg3').removeClass('active');
		$('#pag_prev').addClass('disabled');
		return;
	}	
	
	if(x>0){		
		var z = x -1;
		
		removePage();
		pageAddPrev();
		
		for(i=1;i<=3;i++){	
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#requestor_pagination');
				z++;		
		}
		
		pageAddNext();
		pagPg2($('#pag_pg2').val());			 
		return;		
	}
}

function pagPg2()
{
	$('#pag_prev').removeClass('disabled');
	$('#pag_next').removeClass('disabled')
	
	var x = $('#pag_pg2').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);	
	
	$('#pag_pg1').removeClass('active');
	$('#pag_pg2').addClass('active');
	$('#pag_pg3').removeClass('active');
	
}

function pagPg3()
{
	var x = $('#pag_pg3').attr('rel');
	createTable(arr_tmp_tbl[x-1],x);
	
	if(x<iCount){		
		var z = x-1;
	
		removePage();
		pageAddPrev();
				
		for(i=1;i<=3;i++){	
			$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#requestor_pagination');
			z++;		
			
		}
		
		pageAddNext();
		pagPg2($('#pag_pg2').val());
		return;	
	}
	
	$('#pag_pg1').removeClass('active');
	$('#pag_pg3').addClass('active');
	$('#pag_pg2').removeClass('active');
	$('#pag_next').addClass('disabled');
}

function pagNext()
{
	if($("#pag_prev").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg3();
}
function pagPrev()
{
	if($("#pag_next").hasClass('disabled'))
	{
		pagPg2();;
		return;
	}
	pagPg1();
}
function removePage()
{
	$("#pag_prev").remove();
	$("#pag_next").remove();
	$('#pag_pg1').remove();
	$('#pag_pg3').remove();
	$('#pag_pg2').remove();
}



