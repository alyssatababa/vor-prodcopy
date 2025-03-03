var iCount=0;
var arr_tmp_tbl = [];

$(document).ready(function(){
	
	//alert(base_url);
	getCurrency("all");
});

$("#btn_add_currency").click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var currency = $("#input_currency").val();
		var abbreviation = $("#input_abbreviation").val();
		var country = $("#input_country").val();
		
		if(isFillUpError("add") == 0){
			addCurrency(currency, abbreviation, country);	
		}
	}
});

$("#btn_save_currency").click(function(e)
{
	e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
		var currency_id = $("#edit_id").val();
		var currency = $("#edit_currency").val();
		var abbreviation = $("#edit_abbreviation").val();
		var country = $("#edit_country").val();

		if(isFillUpError("edit") == 0){
			saveCurrency(currency_id, currency, abbreviation, country);
		}
	}
});

$("#btn_search_currency").click(function()
{	
	var currency = $("#search_currency").val();

	//alert(currency);
	if (currency==""){
		getCurrency("all");
	}else{
		getCurrency(currency);
	}
		
});

$('#modal_add_currency').on('show.bs.modal', function () {
	$('#modal_add_currency input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
		$('#'+ this.id).val('');
	});	
	
	var x = document.getElementById('error_add_currency');
	x.style.display = 'none';
})

$('#modal_edit_currency').on('show.bs.modal', function () {
	$('#modal_edit_currency input').each(function()
	{
		$('#'+ this.id).parent('div').removeClass('has-error');	
	});	
	
	var x = document.getElementById('error_edit_currency');
	x.style.display = 'none';
})

//========== FUNCTIONS ==========

function getCurrency(currency)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	if(currency == "all"){
		base_url = base_url + "get_all_currency";
	}else{
		base_url = base_url + "get_currency";
	}
	
	$.ajax({
		type:'POST',
		data:{currency: currency},
		url: base_url,
		success: function(result){	
			
			var json_result = $.parseJSON(result);
			//alert(json_result.status);
			
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
				var span_message = 'Currency record not found!';
				var type = 'danger';
				notify(span_message, type);				
			}
		}		
	}).fail(function(){
		
	});	
}

function createDataTable(obj_result)
{
	var x=0;
	var y=0;
	var z=obj_result.length;
	//alert(z);
	$("#tbl_view").find("tr:gt(0)").remove();
	
	//alert("check");
	var default_rdo = 0;
	for(x=0;x<z;x++){
		y=x+1;
		if(obj_result[x].DEFAULT_FLAG == '1'){
			default_rdo = 1;
		}
		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="currency'+y+'">'+obj_result[x].CURRENCY+'</td><td id="abbreviation'+y+'">'+obj_result[x].ABBREVIATION+'</td><td id="country'+y+'">'+obj_result[x].COUNTRY+'</td><td><span style="display:none;">' + default_rdo +'</span><input type ="radio" name ="optradios" id = "rdo'+y+'" class = "radio_currency" data-toggle="modal" data-target="#edit_selected_currency" data-tid="' + obj_result[x].CURRENCY_ID + '"></td><td><button id="edit'+y+'" data-id="row-'+y+'" class = "btn btn-default" rel = "" onclick = "javascript:editRow('+y+','+obj_result[x].CURRENCY_ID+');return false;"><span class= "glyphicon glyphicon-sunglasses g_icon" onclick = "return false;"></span></button> <span>  </span> <button onclick = "javascript:deactivateYesNo('+y+','+obj_result[x].CURRENCY_ID+');" rel ="" class = "btn btn-default"><span class="glyphicon glyphicon-trash gl-black" aria-hidden="true"></span></button></td></tr>').appendTo('#tbl_body');

		if(obj_result[x].DEFAULT_FLAG == '1'){
			$("#rdo"+y).prop('checked',true);
		}
		default_rdo = 0;
	}
	
}

$(document).on("click", ".radio_currency", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.    
        var n =  $(this).attr('data-tid');
        //alert(n);
        //$('#edit_selected_currency').modal('show');
        $('#edit_selected_currency').attr('data-mval',n);
   		
    }
});

$(document).on("click", "#btn_save_radio", function (e) {
    e.preventDefault();
    if (e.handled !== true) { //Checking for the event whether it has occurred or not.
        e.handled = true; // Basically setting value that the current event has occurred.
        
        var currency_id = $('#edit_selected_currency').attr('data-mval');
        //alert(n);
        var base_url = $('#b_url').attr('data-base-url');
		
		$.ajax({
			type:'POST',
			data:{currency_id: currency_id},
			url: base_url + "default_currency",
			success: function(result){	
				//alert(result)
				var json_result = $.parseJSON(result);
				//alert(json_result.status);
				
				if (json_result.status != false){
					//$('#edit_selected_currecny').modal('toggle');
					$('#edit_selected_currency').modal('toggle');

					var span_message = 'Default currency successfully saved.';
					var type = 'success';
					notify(span_message, type);	
					
					getCurrency("all");
				}
			}		
		}).fail(function(){
			
		});	



   		
    }
});

// function getDefault(){
// 	var base_url = $('#b_url').attr('data-base-url');
		
// 	$.ajax({
// 		type:'POST',
// 		url: base_url + "get_default",
// 		success: function(result){	
// 			//alert(result)
// 			var json_result = $.parseJSON(result);
// 			//alert(json_result.status);
			
// 			if (json_result.status != false){
// 				//$('#edit_selected_currecny').modal('toggle');
// 				$('#edit_selected_currency').modal('toggle');

// 				var span_message = 'Default currency successfully saved.';
// 				var type = 'success';
// 				notify(span_message, type);	
				
// 			}
// 		}		
// 	}).fail(function(){
		
// 	});		
// }



/*
function createTable(obj, page_num)
{
	var x=0;
	var y=0;
	var i=0;
	var j=10*(page_num-1);

	var obj_tbl = obj.split('|');
	
	$("#tbl_view").find("tr:gt(0)").remove();
	
	for(x=0;x<obj_tbl.length;x++){
		var n = obj_tbl[x].split('/');
		y=x+1+j;

		$('<tr id="row'+y+'" class="clickable-row"><td>'+y+'</td><td id="currency'+y+'">'+n[1]+'</td><td id="abbreviation'+y+'">'+n[2]+'</td><td id="country'+y+'">'+n[3]+'</td><td>&nbsp;<a id="edit'+y+'" data-id="row-'+y+'" href="javascript:editRow('+y+','+n[0]+');">Edit</a>&nbsp;<a  id="deactivate'+y+'" href="javascript:deactivateYesNo('+y+','+n[0]+');"><span class="glyphicon glyphicon-trash"></span></a></td></tr>').appendTo('#tbl_body');		
	}

}
*/

function isFillUpError(crud)
{
	var iError = 0;
	
	if (crud=="add"){
		$('#modal_add_currency input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_add_currency').fadeIn('slow').delay(1000).fadeOut('slow');
			$('#error_add_currency').text('Please fill up all required fields!');	
		}
	}else if (crud=="edit"){
		$('#modal_edit_currency input').each(function()
		{
			if($(this).val().length == 0){			
				$('#'+ this.id).parent('div').addClass('has-error');	
				iError++;
			}else{
				$('#'+ this.id).parent('div').removeClass('has-error');	
			}	
		});	
		
		if(iError > 0){
			$('#error_edit_currency').fadeIn('slow').delay(5000).fadeOut('slow');
			$('#error_edit_currency').text('Please fill up all required fields!');	
		}		
	}
	return iError;
}

function addCurrency(currency, abbreviation, country)
{
	var base_url = $('#b_url').attr('data-base-url');

	$.ajax({
		type:'POST',
		data:{currency: currency, abbreviation: abbreviation, country: country},
		url: base_url + "add_currency",
		success: function(result){	
			$('#modal_add_currency').modal('hide');
			
			var span_message = 'Currency has been successfully saved!';
			var type = 'success';
			notify(span_message, type);					
			
			getCurrency("all");
		}		
	}).fail(function(){
		
	});	
}

function saveCurrency(currency_id, currency, abbreviation, country)
{
	var base_url = $('#b_url').attr('data-base-url');
	
	$.ajax({
		type:'POST',
		data:{currency_id: currency_id, currency: currency, abbreviation: abbreviation, country: country},
		url: base_url + "save_currency",
		success: function(result){	
			$('#modal_edit_currency').modal('hide');
			
			var span_message = 'Currency has been successfully saved!';
			var type = 'success';
			notify(span_message, type);			
			
			getCurrency("all");
		}		
	}).fail(function(){
		
	});		
}

function editRow(y, currency_id){
	var sel_currency = document.getElementById("currency"+y).innerHTML;
	var sel_abbreviation = document.getElementById("abbreviation"+y).innerHTML;
	var sel_country = document.getElementById("country"+y).innerHTML;

	$('#edit_id').val(currency_id);
	$('#edit_currency').val(sel_currency);
	$('#edit_abbreviation').val(sel_abbreviation);
	$('#edit_country').val(sel_country);
	$('#modal_edit_currency').modal('show')
}

function deactivateYesNo(y,currency_id)
{
	disable_enable_frm('frm_currency', true);
	
	$('#row'+y).addClass('danger').siblings().removeClass('danger');

	$('#edit'+y).addClass('disabled');
	$('#deactivate'+y).addClass('disabled');

	var span_message = 'Are you sure you want to deactivate currency? <button type="button" class="btn btn-success" onclick="deactivateYes('+y+','+currency_id+')" >Yes</button>&nbsp;<button type="button" class="btn btn-default" id="close_alert" onclick="deactivateNo('+y+')">No</button>';
	var type = 'info';
	notify(span_message, type, true);	
}

function deactivateYes(y, currency_id)
{
	var base_url = $('#b_url').attr('data-base-url');	
	
	$.ajax({
		type:'POST',
		data:{currency_id: currency_id},
		url: base_url + "deactivate_currency",
		success: function(result){	

			var json_result = $.parseJSON(result);
			
			$('#edit'+y).removeClass('disabled');
			$('#deactivate'+y).removeClass('disabled');
			$('#row'+y).removeClass('danger')

			disable_enable_frm('frm_currency', false);
			
			var span_message = 'Currency has been deactivated!';
			var type = 'info';
			notify(span_message, type);		
			//alert("1");
			getCurrency("all");
		}		
	}).fail(function(){
		
	});	
}

function deactivateNo(y)
{
	$('#edit'+y).removeClass('disabled');
	$('#deactivate'+y).removeClass('disabled');
	$('#row'+y).removeClass('danger')

	disable_enable_frm('frm_currency', false);
}

function fillArrayTemp(obj_result)
{
	var x=0;
	var i=0;
	var j=0;
	var tmpArr = [];

	arr_tmp_tbl	= [];
	//alert(obj_result.length);
	for(i=0;i<iCount;i++)
	{		
		if(x == obj_result.length){break;}
		for(j=0;j<10;j++)
		{
			
			if(x == obj_result.length){break;}
			//alert(obj_result[x].CURRENCY);
			tmpArr.push(obj_result[x].CURRENCY_ID+'/'+obj_result[x].CURRENCY+'/'+obj_result[x].ABBREVIATION+'/'+obj_result[x].COUNTRY);	
			x++;
		}		
		arr_tmp_tbl.push(tmpArr.join('|'));
		tmpArr = [];		
	}
	
	//alert(arr_tmp_tbl[0]);
	createTable(arr_tmp_tbl[0],1);
	
}

function currencyPagination(item_count)
{
	var page_count=Math.ceil(item_count/10);
	var i=0;
	
	//alert(item_count + ' ' + page_count);
	
	removePage();
	
	if(page_count>3){
			pageAddPrev();
			for(i=1;i<4;i++){
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#currency_pagination');
			}
			pageAddNext();
			iCount = page_count;
			return;			
	}
		
	for(i=1;i<page_count+1;i++){
		//alert(item_count + ' ' + page_count);
		$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+i+'"><a class = "page-link" href = "#" onclick = "return false;">'+i+'</a></li>').appendTo('#currency_pagination');
	}	
	iCount = page_count;
			
}

function pageAddPrev()
{
	//alert("prev");
	$('#pag_prev').remove();
	$('<li id = "pag_prev" class="page-item"><a class="page-link" href="#" aria-label="Previous" onclick = "return false;"><span aria-hidden="true">&laquo;</span><span class="sr-only">Previous</span></a></li>').appendTo('#currency_pagination');
}

function pageAddNext()
{
	//alert("next");
	$('#pag_next').remove();
	$('<li id = "pag_next" class="page-item"><a class="page-link" href="#" aria-label="Next" onclick = "return false;"><span aria-hidden="true">&raquo;</span><span class="sr-only">Next</span></a></li>').appendTo("#currency_pagination");
}

function pagPg1()
{	
	
	var x = $('#pag_pg1').attr('rel');
	//var new_tbl = sortAbbreviation();
	//alert(arr_tmp_tbl[0]);
	//alert(sortAbbreviation());
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
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#currency_pagination');
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
				$('<li id = "pag_pg'+i+'" class = "page-item" rel = "'+z+'"><a class = "page-link" href = "#" onclick = "return false;">'+z+'</a></li>').appendTo('#currency_pagination');
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
	
	$('#pag_pg1').remove();
	$('#pag_pg3').remove();
	$('#pag_pg2').remove();

}

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



