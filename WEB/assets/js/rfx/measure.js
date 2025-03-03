var iCount = 0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	//alert("1");
	getMeasure("all");
});

$('#btn_add_measure').click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var measure = $("#input_measure").val();
		var abbreviation = $("#input_abbreviation").val();
		var iError = 0;
			
		if(isFillUpError("add")==0){
			addMeasure(measure, abbreviation);	
		}
	}
});

$('#btn_save_measure').click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var measure_id = $("#edit_id").val();
		var measure = $("#edit_measure").val();
		var abbreviation = $("#edit_abbreviation").val();

		//alert(measure_id + " " +  measure + " " + abbreviation);


		if(isFillUpError("edit")==0){
			saveMeasure(measure_id, measure, abbreviation);	
		}
	}
});

$('#btn_search_measure').click(function()
{
	var measure = $("#search_measure").val();
	if (measure==""){
		getMeasure("all");
	}else{
		getMeasure(measure);	
	}
});

$('#modal_add_measure').on('show.bs.modal', function () {
	//alert("1");
	$('#modal_add_measure input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	var x = document.getElementById('error_add_measure');
	x.style.display = 'none';

	$("#input_measure").focus();
})



$('#modal_edit_measure').on('show.bs.modal', function () {
	$('#modal_edit_measure input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
	});	
	
	var x = document.getElementById('error_edit_measure');
	x.style.display = 'none';
})


//========== FUNCTIONS =================================================

function getMeasure(measure)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(measure == "all"){
		base_url = base_url + "get_all_measure";
	}else{
		base_url = base_url + "get_measure";
	}
	

	$.ajax({
		type:'POST',
		data:{measure: measure},
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
				var span_message = 'Unit of Measure record not found!';
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
			tmpArr.push(obj_result[x].UNIT_OF_MEASURE+'/'+obj_result[x].MEASURE_NAME+'/'+obj_result[x].MEASURE_ABBREV);	
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
		
// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="measure'+y+'">'+n[1]+'</td><td id="abbreviation'+y+'">'+n[2]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');		
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
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="measure'+y+'">'+obj_result[x].MEASURE_NAME+'</td><td id="abbreviation'+y+'">'+obj_result[x].MEASURE_ABBREV+'</td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].UNIT_OF_MEASURE+'); return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].UNIT_OF_MEASURE+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');
	}
	
}

// function createDataTable(obj_result)
// {
// 	var x=0;
// 	var y=0;
// 	var z=obj_result.length;
// 	//alert(z);
// 	$("#tbl_view").find("tr:gt(0)").remove();

	
// 	for(x=0;x<z;x++){
// 		y=x+1;
// 		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="measure'+y+'">'+obj_result[x].MEASURE_NAME+'</td><td id="abbreviation'+y+'">'+obj_result[x].MEASURE_ABBREV+'</td><td><a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+obj_result[x].UNIT_OF_MEASURE+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+obj_result[x].UNIT_OF_MEASURE+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');
// 	}
	
// }


function addMeasure(measure, abbreviation)
{	
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{measure: measure, abbreviation: abbreviation},
		url: base_url + "add_measure",
		success: function(result){						
			$('#modal_add_measure').modal('hide');
			
			//=== NOTIFICATION ===
			var span_message = 'Measure has been successfully saved!';
			var type = 'success';
			notify(span_message, type);					
			
			getMeasure("all");
		}		
	}).fail(function(){
		
	});	
}


function saveMeasure(measure_id, measure, abbreviation)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{measure_id: measure_id, measure: measure, abbreviation: abbreviation},
		url: base_url + "save_measure",
		success: function(result){	
			$('#modal_edit_measure').modal('hide');
			//alert(result);
			//=== NOTIFICATION ===
			var span_message = 'Measure has been successfully saved!';
			var type = 'success';
			notify(span_message, type);			
			
			getMeasure("all");
		}		
	}).fail(function(){
		
	});		
}

function isFillUpError(crud)
{
	var iError = 0;	

	if(crud=="add"){
		$('#modal_add_measure input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_add_measure').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_add_measure').text('Please fill up all required fields!');	
		}
	}else if(crud=="edit"){
		$('#modal_edit_measure input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_edit_measure').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_edit_measure').text('Please fill up all required fields!');	
		}		
	}

	return iError;
}


function editRow(y, measure_id){
	var sel_measure = document.getElementById("measure"+y).innerHTML;
	var sel_abbreviation = document.getElementById("abbreviation"+y).innerHTML;

	//alert(y + " " + measure_id);

	$('#edit_id').val(measure_id);
	$('#edit_measure').val(sel_measure);
	$('#edit_abbreviation').val(sel_abbreviation);
	$('#modal_edit_measure').modal('show')
}

function deactivateYesNo(y,measure_id)
{
	disable_enable_frm('frm_measure', true);
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');
	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	//=== NOTIFICATION ===
	var span_message = 'Are you sure you want to deactivate measure? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+measure_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(y, measure_id)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{measure_id: measure_id},
		url: base_url + "deactivate_measure",
		success: function(result){	
			
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')

			disable_enable_frm('frm_measure', false);
			
			//=== NOTIFICATION ===
			var span_message = 'Measure has been deactivated!';
			var type = 'info';
			notify(span_message, type);		

			
			getMeasure("all");
		}		
	}).fail(function(){
		
	});	
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')

	disable_enable_frm('frm_measure', false);
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

function measurePagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;

	removePage();
	
	if(page_count>3){
		
			pageAddPrev();
			
			for(i=1;i<4;i++){
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#measure_pagination');
			}
			
			pageAddNext();
			
			iCount = page_count;
			return;			
	}
		
	for(i=1;i<page_count+1;i++){
		$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#measure_pagination');
	}	
	iCount = page_count;		
}

function pageAddPrev()
{
	$('#pag_prev').remove();
	$('<li id = "pag_prev" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#measure_pagination');
}

function pageAddNext()
{
	$('#pag_next').remove();
	$('<li id = "pag_next" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#measure_pagination");
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
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#measure_pagination');
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
			$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#measure_pagination');
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



