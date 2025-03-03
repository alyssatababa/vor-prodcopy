
loadingScreen('on');

try{
	var $bDoc_type = document.getElementById('bDoc_type').value;
	//Initialize content of mtd field
	document.getElementById('mtd-from').textContent = mtd();
	document.getElementById('mtd-to').textContent = mtd(1);

	var $formHead = $('#reports_headFilter'), 
		$reports_table = $('#reports_table'),
		$arch_disable = $('.arch-disable'),
		$footerModal = $('#bd_modal_conf'),
		$bdModal = $('#bd_modal'),
		$arch_option = {},
		$callAjax = true,
		$datatable,
		$bdLoadedjs = true,
		$linkingDataTable = false,
		$checkDataCount = 0,
		$dataTable_options = {'processing': true, 'serverSide': true, 'searching': true, 'deferRender': true, 'responsive': true,"destroy": true,
		  	'language': {
		      'paginate': {
		        'previous': '«',
		        'next': '»'
		      },
		      'emptyTable': "No records found.",
			},
			"scrollY": "400px",
			'lengthMenu': [ 10, 25, 50, 100, 500,1000],
			'dom':'<"top pull-right"i><"top pull-left"l>rt<"bottom reports_pagination"p>',
			'pageLength': 100,
			'fnDrawCallback': function(oSettings) {
		        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
		            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
		        } else{
		        	$(oSettings.nTableWrapper).find('.dataTables_paginate').show();
		        }
		    },
		    "fnRowCallback": function (row, data, index) {
		    	if(data[data.length-1] == 0 || data[data.length-1] == null){
				  $(row).css({ "font-weight": "bold" });
				}
		    }
		};
	
	
}catch(e){
	console.log(e);
}

var dTable = (function() {
    var daTable = {};
    daTable.init = function () {

	    var $read_img = '<img src="'+BASE_URL+'../assets/img/mail_read.png" width="25" data-status= "1" data-toggle="tooltip" data-placement="left" title="Read"/>',
		$unread_img = '<img src="'+BASE_URL+'../assets/img/mail_unread.png" width="25" data-status="0" data-toggle="tooltip" data-placement="left" title="Unread"/>';


    	//Datatable options by document type
    	if($bDoc_type == 2){ // For Credit Advice
	    	$dataTable_options['order'] = [[ 4, "desc" ]];
	    	$dataTable_options['ajax'] = {"url": BASE_URL+"businessdoc/reports/ca_dtables","type": "POST","datatype": "json"};
	    	$dataTable_options['columns'] = [
		        	{ "targets": 0, "searchable":false , "orderable": false, "render": function (data) { return '<input type="checkbox" name="selected[]" class="selected_docu" value="'+data+'" />'; } , "className": 'text-center'
					},
		        	{ "data": 1, "className": 'text-center' },
		        	{ "data": 4, "className": 'text-center', "render": function (data) { return '<a href="#" data-action-path="businessdoc/reports/ca_details/'+data+'" class="cls_action" data-crumb-text="">'+data+'</a>'; }
					},
		        	{ "data": 3, "className": 'text-right' },
		        	{ "data": 2, name: "CHECK_NO", "className": 'text-center' },
		        	{ "data": 5, name: "STATUS", "searchable":false,"render": function (data, type, row) { return (data > 0) ? $read_img : $unread_img; },
		        		"createdCell": function (td, cellData, rowData, row, col) {
					    	return (cellData == 0 ) ? $(td).addClass('text-center').css('color','black') : $(td).addClass('text-center');
					    }
					}
		    ];
	    } 
	    else if($bDoc_type == 1){ //For Purchase Order
	    	$dataTable_options['order'] = [[ 10, "desc" ]];
	    	$dataTable_options['ajax'] = {"url": BASE_URL+"businessdoc/reports/po_dtables","type": "POST","datatype": "json"};
	    	$dataTable_options['columns'] = [
	        	{ "data": 0, "searchable":false , "orderable": false, "render": function (data,type,full) { return '<input type="checkbox" name="selected[]" class="selected_docu" value="'+data+'" data-comp-id="'+full[12]+'" />'; } , "className": 'text-center' },
	        	{ "data": 1, name:"PO_NUMBER", "render": function (data,type,full) { return '<a href="#" data-action-path="businessdoc/reports/po_details/'+full[0]+'/'+full[12]+'" class="cls_action" data-crumb-text="">'+data+'</a>'; } },
	        	{ "data": 2,name:"PO_STATUS","render":function(data){ 
	        		if(data == 1){ return 'Received'; } else if(data == 2){return 'Pending'; } else if(data == 3) { return 'Extended'; } else if(data == 4) { return 'Cancelled'} else{ return 'Released'; }
	        	} },
	        	{ "data": 3,name:"ENTRY_DATE","render":function(data){ return dateFormater(data); } },
	        	{ "data": 4,name:"EXP_RECEIPT_DATE", "className": 'text-right',"render":function(data){ return dateFormater(data); } },
	        	{ "data": 5,name:"CANCEL_DATE","className": 'text-center',"render":function(data){ return dateFormater(data); } },
	        	{ "data": 6,name:"TOTAL_AMOUNT" },
	        	{ "data": 7,name:"DEP_NAME"},
	        	{ "data": 8,name:"LOCATION"},
	        	{ "data": 9,name:"COMPANY_NAME"},
	        	{ "data": 10,name:"POST_DATE","render":function(data){ return dateFormater(data); } },
	        	{ "data": 11,name:"READ_STATUS","render": function (data, type, row) { return (data > 0 && data != null) ? $read_img : $unread_img; },
	        		"createdCell": function (td, cellData, rowData, row, col) {
				    	return (cellData == 0 ) ? $(td).addClass('text-center') : $(td).addClass('text-center');
				    } 
				}
		    ];
	    }
	    else if($bDoc_type == 3){ // For Debit Credit Memo
	    	$dataTable_options['order'] = [[ 8, "desc" ]];
	    	$dataTable_options['ajax'] = {"url": BASE_URL+"businessdoc/reports/dmcm_dtables","type": "POST","datatype": "json"};
	    	$dataTable_options['columns'] = [
	        	{ "data": 0, name:"DOC_TYPE_NAME" },
	        	{ "data": 1, name:"DOC_NO" ,"className": 'text-center' },
	        	{ "data": 2, name:"COMPANY_NAME" },
	        	{ "data": 3, name:"BRANCH_NAME" },
	        	{ "data": 4, name:"NATURE_NAME" ,"className": 'text-center' },
	        	{ "data": 5, name:"AMOUNT" ,"className": 'text-right' },
	        	{ "data": 6, "orderable":false, "searchable":false, 
			        "render": function (data) {
				    	return (data == 1 ) ? '<img src="'+ASSET_URL+'img/check-mark.png" alt="check"/>' : '';
				    } , "className": 'text-center'
				},
	        	{ "data": 7, name:"CHECK_NO" ,"className": 'text-center' },
	        	{ "data": 8, "className": 'text-center'  }
		    ];
	    }
	    else if($bDoc_type == 5){ // For Remittance Advice
	    	$dataTable_options['order'] = [[ 6, "desc" ]];
	    	$dataTable_options['ajax'] = {"url": BASE_URL+"businessdoc/reports/ra_dtables","type": "POST","datatype": "json"};
	    	$dataTable_options['columns'] = [
	        	{ "data": 0, name:"REF_NO1", "searchable":false , "orderable": false, "render": function (data) { return '<input type="checkbox" name="selected[]" class="selected_docu" value="'+data+'" />'; } , "className": 'text-center' },
	        	{ "data": 1, name:"REF_NO", "render": function (data,type,full) { return '<a href="#" data-action-path="businessdoc/reports/ra_details/'+full[0]+'" class="cls_action" data-crumb-text="">'+data+'</a>'; } },
	        	{ "data": 2, name:"PROC_DATE","render":function(data){ return dateFormater(data); } },
	        	{ "data": 3, name:"TOTAL_AMOUNT","className": 'text-right'},
	        	{ "data": 4,"className": 'text-center',"render":function(data){ return dateFormater(data); } },
	        	{ "data": 5 },
	        	{ "data": 6,"searchable": false},
	        	{ "data": 7, name:"READ_STATUS","render": function (data, type, row) { return (data > 0) ? $read_img : $unread_img; },
	        		"createdCell": function (td, cellData, rowData, row, col) {
				    	return (cellData == 0 ) ? $(td).addClass('text-center') : $(td).addClass('text-center');
				    } 
				}
		    ];
	    }

	    if($.fn.dataTable.isDataTable("#reports_table")){ //Checking for initialization
			try {
				$reports_table.DataTable().destroy();
			} catch(e) {
				console.log(e);
			}
		}

		try {
			$reports_table.on('preInit.dt page.dt search.dt order.dt length.dt', function (e, settings) {
				$("#report_div").addClass('global_disable');
				// $("#arch-dl-list").addClass('global_disable');
				$(".accordion").addClass('global_disable');
				$linkingDataTable = false;
			});

			$datatable = $reports_table.DataTable($dataTable_options);
			$reports_table.on('draw.dt', function (e, settings,data) {
		    	$("#report_div").removeClass('global_disable');
		    	// $("#arch-dl-list").removeClass('global_disable');
		    	$(".accordion").removeClass('global_disable');
		    	$('[data-toggle="tooltip"]').tooltip();
		    	$linkingDataTable = true;
		    	loadingScreen('off');
			});

		} catch(e) {
			console.log(e);
		}
		

	    // $datatable = $reports_table.DataTable($dataTable_options);
	    


	     //Filteration
	    if($bDoc_type == 2){ // Credit Advice Filter
		    $datatable.on( 'init.dt', function () {
		       yadcf.init($datatable, [
		       		{ column_number : 4, filter_type: "text", style_class: "form-control limit-field", filter_container_id: "filter_vCode" },
		       		{ column_number: 5,  filter_type: "select", data: [{value: 1, label: 'Read'}, {value: 0, label: 'Unread'}], style_class: "form-control", filter_default_label: "All", filter_container_id: "filter_status" },
		       	]
		       	,{	externally_triggered: true}
		       	);
			});
		}	
		else if($bDoc_type == 1){ // PO Filter
		    $datatable.on( 'init.dt', function () {
		       yadcf.init($datatable, [
		       		{ column_number : 1, filter_type: "text", style_class: "form-control limit-field", filter_container_id: "filter_ponumber" },
		       		{ column_number: 2,  filter_type: "select", data: [{value: 1, label: 'Received'}, {value: 2, label: 'Pending'},{value: 3, label: 'Extended'},{value: 4, label: 'Cancelled'},{value:5, label: 'Released'}], style_class: "form-control", filter_default_label: "All", filter_container_id: "filter_postatus" },
		       		{ column_number: 11,  filter_type: "select", data: [{value: 1, label: 'Read'}, {value: 0, label: 'Unread'}], style_class: "form-control", filter_default_label: "All", filter_container_id: "filter_status" }
		       	]
		       	,{	externally_triggered: true}
		       	);
			});
		}	
		else if($bDoc_type == 3){ //DMCM FIlter
			$datatable.on( 'init.dt', function () {
		       yadcf.init($datatable, [
		       		{ column_number: 0, filter_type: "select", data: [{value: 'DM', label: 'DM'}, {value: 'CM', label: 'CM'}], style_class: "form-control", filter_container_id: "filter_dType",filter_default_label: "All"}, //Document Type
		       		{ column_number : 1, filter_type: "text", style_class: "form-control limit-field", filter_container_id: "filter_docNo" }
		       	]
		       	,{	externally_triggered: true}
		       	);
			});
		} 
		else if($bDoc_type == 5){ //Remittance Advice
			$datatable.on( 'init.dt', function () {
		        yadcf.init($datatable, [
		       		{ column_number : 1, filter_type: "text", style_class: "form-control limit-field", filter_container_id: "filter_raCode" },
		       		{ column_number: 7,  filter_type: "select", data: [{value: 1, label: 'Read'}, {value: 0, label: 'Unread'}], style_class: "form-control", filter_default_label: "All", filter_container_id: "filter_status" },
		       	]
		       	,{	externally_triggered: true}
		       	);
			});
		}


    }
    return daTable;

})();


$(document).ready(function(){
	dTable.init();
	loadingScreen('off');
	$('#history_tb').DataTable({
		'searching': false,
		'language': {
	      'paginate': {
	        'previous': '«',
	        'next': '»'
	      },
	      'emptyTable': "No records found.",
    	},
    	'lengthMenu': [ 10, 25, 50, 100],
    	'dom':'<"top pull-right"i><"top pull-left"l>rt<"bottom reports_pagination"p>',
    	'pageLength': 10,
    	'fnDrawCallback': function(oSettings) {
	        if (oSettings._iDisplayLength > oSettings.fnRecordsDisplay()) {
	            $(oSettings.nTableWrapper).find('.dataTables_paginate').hide();
	        } else{
	        	$(oSettings.nTableWrapper).find('.dataTables_paginate').show();
	        }
	    },
	    'order': [[ 0, 'desc' ]]
    });
	$(".date-from").mask("99/99/9999",{placeholder:"mm/dd/yyyy",
		completed:function(){
			var startDate = new Date(this.val());
			var endDate = new Date($(this).siblings('input').val());
			if (startDate > endDate ){
		    	modalMesssage('Invalid date range!');
		    	$(this).siblings('input').val('');
		    	this.val('');
			}
		}
	});
	$(".date-to").mask("99/99/9999",{placeholder:"mm/dd/yyyy",
		completed:function(){
			var startDate = new Date($(this).siblings('input').val());
			var endDate = new Date(this.val());
			if (endDate < startDate ){
				modalMesssage('Invalid date range!');
		    	$(this).siblings('input').val('');
		    	this.val('');
			}
		}
	});

    /*
    	Clear header filter
     */
    $('#headFilter_clear').on('click',function(){
    	$formHead[0].reset(); //Reset form
    	// $datatable.clear().draw(); //Redraw datatable
    	//Remove property in prexhr object
    	$datatable.on('preXhr', function ( e, settings, data) { 
    		data.date = '';
    		data.vend_id = '';
    		data.company_name = '';
    		data.store_name = '';
    		data.location = '';
    		data.dept_name = '';
    	});
    	$('#input_field_goes_here').datepicker('setDate', null);
    	//Show default date filter
    	$('.head-date[data-date-filter="m-y"]').removeClass('hide');
    	$('.head-date').not('.head-date[data-date-filter="m-y"]').addClass('hide');
    	$("#vend_code_field").val(null).trigger("change");
    	$("#filter_cName").val(null).trigger("change");
    	$("#filter_stName").val(null).trigger("change");
    	$("#filter_location").val(null).trigger("change");
    	$("#filter_deptname").val(null).trigger("change");

    	let fromClear = $('.date-from').val('').datepicker( "option" , {
		    maxDate: '0'}).on( "change", function() {
		  toClear.datepicker( "option", "minDate", getDate( this ) );
		}),
    	toClear = $('.date-to').val('').datepicker( "option" , {
		    maxDate: '0'} ).on( "change", function() {
			fromClear.datepicker( "option", "maxDate", getDate( this ) );
		});

    	yadcf.exResetAllFilters($datatable); 	
    });

    /*
    	Filter datatable
     */
    $('#headFilter_submit').on('click',function(){
    	let $dateFilter = $('input[name=date_option]:checked').val();
    	$hMonth = document.getElementsByName("hMonth")[0].value;
    	$hYear = document.getElementsByName("hYear")[0].value;
    	$hFrom = document.getElementsByName("hFrom")[0].value;
    	$hTo = document.getElementsByName("hTo")[0].value;
    			

    	//Month & year validation
    	if($hMonth != '' && $hYear == ''){
    		modalMesssage('No year selected!');
			return false;
    	}
    	if($hMonth == '' && $hYear != ''){
    		modalMesssage('No month selected!');
			return false;
    	}

    	//From & to validation
    	if($hFrom != '' && $hTo == ''){
    		modalMesssage('Please select to date!');
			return false;
    	}
    	else if($hFrom == '' && $hTo != ''){
    		modalMesssage('Please select to date!');
			return false;

    	} else if($hFrom == '' && $hTo == '' && $dateFilter == 3 ){
    		modalMesssage('Please select a date!');
			return false;
    	}

    	
    	//??????
    	$hmtdFrom = mtd();
    	$hmtdTo = mtd(1);

    	//Filter of date
    	$datatable.on('preXhr.dt', function ( e, settings, data ) {
    		data.date = {'date_type':$dateFilter,'date_month':$hMonth,'date_year':$hYear,'date_from':$hFrom,'date_to':$hTo,'date_mtdFrom':$hmtdFrom,'date_mtdTo':$hmtdTo};
    	});



    	//Vendor code filter
    	var $vendcode = $("#vend_code_field").val();
    	if($vendcode){ //Todo admin filter by vendor code
    		$datatable.on('preXhr.dt', function ( e, settings, data ) {
	    		data.vend_id = $vendcode;
	    	});
    	}	
    	//Payment date filter for remittance advice
    	if($bDoc_type == 5 ){
    		var $pdFrom = document.getElementsByName("pdFrom")[0].value;
   			var $pdTo = document.getElementsByName("pdTo")[0].value;
    		$datatable.on('preXhr.dt', function ( e, settings, data ) {
	    		data.date = {'date_type':$dateFilter,'date_month':$hMonth,'date_year':$hYear,'date_from':$hFrom,'date_to':$hTo,'date_mtdFrom':$hmtdFrom,'date_mtdTo':$hmtdTo,'pd_from':$pdFrom,'pd_to':$pdTo};
	    	});
    	}
    	//Receipt date && cancel date for purchase order
    	if($bDoc_type == 1 ){
    		var $erdFrom = document.getElementsByName("erd-from")[0].value;
	    	var $erdTo = document.getElementsByName("erd-to")[0].value;
	    	var $cdFrom = document.getElementsByName("cdate-from")[0].value;
	    	var $cdTo = document.getElementsByName("cdate-to")[0].value;
	    	var $compName = $('#filter_cName').val();
	    	var $location = $('#filter_location').val();
	    	var $deptName = $('#filter_deptname').val();

	    	//From & to validation of expected receipt date
	    	if($erdFrom != '' && $erdTo == ''){
	    		modalMesssage('Please select to date!');
				return false;
	    	}
	    	if($erdFrom == '' && $erdTo != ''){
	    		modalMesssage('Please select from date!');
				return false;
	    	}

	    	//From & to validation of cancel date
	    	if($cdFrom == '' && $cdTo != ''){
	    		modalMesssage('Please select from date!');
				return false;
	    	}
	    	if($cdFrom != '' && $cdTo == ''){
	    		modalMesssage('Please select to date!');
				return false;
	    	}

    		$datatable.on('preXhr.dt', function ( e, settings, data ) {
	    		data.date = {'date_type':$dateFilter,'date_month':$hMonth,'date_year':$hYear,'date_from':$hFrom,'date_to':$hTo,'date_mtdFrom':$hmtdFrom,'date_mtdTo':$hmtdTo,'exp_rep_date_from':$erdFrom,'exp_rep_date_to':$erdTo,'cancel_date_from':$cdFrom,'cancel_date_to':$cdTo};
	    		data.company_name = $compName;
	    		data.location = $location;
	    		data.dept_name = $deptName;
	    	});	
    	}

    	if($bDoc_type == 3){
    		var $compName = $('#filter_cName').val();
    		var $storeName = $('#filter_stName').val();
    		$datatable.on('preXhr.dt', function ( e, settings, data ) {
	    		data.company_name = $compName;
	    		data.store_name = $storeName;
	    	});	
    	}

    	yadcf.exFilterExternallyTriggered($datatable);
    });

	/**
	 * View live link
	 */
	$('.archive-link[data-archive]').on('click',function(e){ 
		e.preventDefault();
		let $this = $(this);
		$('.bd-dl-button').attr('data-dl-option','live');
		$('#bd-arch').attr('data-arc-option','live');
		if($this.data('archive') == 2 && $this.data('back') == 'live'){ //Credit Advice
			$main_container.html('').load(BASE_URL + 'businessdoc/reports/credit_advice');
		} else if($this.data('archive') == 5 && $this.data('back') == 'live'){ //Remittance Advice
			$main_container.html('').load(BASE_URL + 'businessdoc/reports/remittance_advice');
		} else if($this.data('archive') == 1 && $this.data('back') == 'live'){ //Purchase Order
			$main_container.html('').load(BASE_URL + 'businessdoc/reports/purchase_order');
		}
	});	

	/*
		Download function
	 */
	$('.bd-dl-button').on('click',function(){
		let $id_doc;
		try{
			$id_doc = document.getElementById('id-item').value; //Get ID of document
		}catch(e){
			$id_doc = '';
		}

		if($(this).attr('data-dl').split('-')[1] == 'details'){
			//Set datacount for details download
			$checkDataCount = 1;
		} else {
			//Get datatable data count
			$checkDataCount = $datatable.data().count();
		}
		

		if($(this).attr('data-disable') || $checkDataCount < 1){
			modalMesssage(($checkDataCount < 1) ? 'No data available.' : 'Under Construction. This pop-up will close in 3 seconds.');
			throw new Error('Stop');
			return false;
		}
		
		if($id_doc){
			$('#progress_bd').find('h4').text('Generating file. Please wait.');
			$('#progress_bd').modal('show');
		} else {
			$bdModal.find('h4').text('Generating file. Please wait.');
			$footerModal.hide();
			$bdModal.modal('show');
		}
		let other_config = {
			cache: false,
		    beforeSend: function () {
		    	$('#bd_modal').modal('show');
		    },
		    fail: function(xhr){
		    	console.log(xhr);
		    }
	    }

	    let success_function = function(responseText)
	    {

	    	let $stat = JSON.parse(responseText);
	    	if($stat == false){
	    		check_dl_file(true); 
	    	} else { //Download direct
	    		$.fileDownload(BASE_URL+'businessdoc/reports/file_header', {
		    		httpMethod: "POST",
		    		data: 'path='+$stat.path,
		    		successCallback: function(url) { 
		    			if($id_doc){
		    				$('#progress_bd').modal('hide'); 
		    			}else{
		    				$bdModal.modal('hide'); 	
		    			}
		    			
		    		}
				});
	    	}
	    };
	    
	    if($(this).attr('data-dl').split("-")[1] == "summary"){ //Summary Download
	    	ajax_request('POST', BASE_URL+'businessdoc/reports/dl_file','dl='+$(this).attr('data-dl')+'&dl-option='+$(this).attr('data-dl-option'),success_function,other_config);
	    } else { 
	    	if($id_doc){ //Details
		    	ajax_request('POST', BASE_URL+'businessdoc/reports/dl_file','dl='+$(this).attr('data-dl')+'&dl-option='+$(this).attr('data-dl-option')+'&id_doc='+$id_doc,success_function,other_config);
		    } else{ //Selected
		    	if($('#reports_table input[type=checkbox]:checked').length > 0){
		    		$('#bd_modal').find('h4').text('Generating file. Please wait.');
					let selected_docu = $('input:checkbox:checked.selected_docu').map(function () {
					  return this.value;
					}).get();
					ajax_request('POST', BASE_URL+'businessdoc/reports/dl_file','dl='+$(this).attr('data-dl')+'&dl-option='+$(this).attr('data-dl-option')+'&selected='+selected_docu,success_function,other_config);
				} else{
					modalMesssage('Please select atleast one document.');
					// $('#bd_modal').find('h4').text('');
					// $('#bd_modal').modal('show');
					// $footerModal.hide();
					// setTimeout(function() {$bdModal.modal('hide');}, 1500);
				}
		    }
	    }

  		return false;
	});

	/*
		Show history of specific document
	 */
	$(".docs-history").on('click',function(e){
		e.preventDefault();
		$doctype = $(this).attr('data-doctype');
		$doc_history = $(this).attr('data-history');
		$('#vDoc_history').modal('show');
	});

	/*
		Archive or unarchive click icon show modal
	 */
	$("#bd-arch").on("click",function(){
		var $checkLength = $('#reports_table input[type=checkbox]:checked').length == 0;
		$checkDataCount = $datatable.data().count();
		var msg_stat = $.map($datatable.rows('.selected').data(), function (item) {
			return item[item.length-1];
        });
		if($checkLength || $checkDataCount < 1){
			modalMesssage(($checkDataCount < 1) ? 'No data available.' : 'Please select atleast one document.');
			return false;
		} 
		else if(msg_stat.indexOf("0") != -1 || msg_stat.length == 0 || msg_stat.length != $('#reports_table input[type=checkbox]:checked').length ){
			modalMesssage('Cannot archive unread documents');
			// $('#bd_modal').find('h4').text();
			// $('#bd_modal').modal('show');
			// $footerModal.hide();
			// setTimeout(function() {$('#bd_modal').modal('hide');}, 1200);
		}
		else {
			$footerModal.removeClass('hidden').show();
			$('#bd_modal').modal('show');
			if($('#bd-arch').attr('data-arc-option') == "live"){
				$('#bd_modal').find('h4').text('Archive the selected document?');
			} else {
				$('#bd_modal').find('h4').text('Unarchive the selected document?');
			}
		}
	});

	/*
		Confirmation of archive or unarchive modal
	 */
	$("#bd_modal_yes").on("click",function(){
		//Get all selected documents
		let $selected_docu = $('input:checkbox:checked.selected_docu').map(function () {
			return this.value;
		}).get();
		let success_function = function(responseText)
	    {
	    	$datatable.clear().draw();
	    	modalMesssage(($('#bd-arch').attr('data-arc-option') == 'archive') ? 'Unarchive successful!' : 'Archive successful!');
	    };

		ajax_request('POST', BASE_URL+'businessdoc/reports/arc_option','doctype='+$('#bd-arch').attr('data-doctype')+'&selected='+$selected_docu+'&option='+$('#bd-arch').attr('data-arc-option'),success_function);
	});

	/*
		Hide or unhide selected date filter option
	 */
	$(".header-dateFilter").on("click",function(){
		$filterType = $(this).data('date-filter');
		$checker = $('.head-date[data-date-filter="'+$filterType+'"]').is(':hidden');
		if($checker){ //if true 
			$('.head-date[data-date-filter="'+$filterType+'"]').removeClass('hide');
			$('.head-date').not('.head-date[data-date-filter="'+$filterType+'"]').addClass('hide');
			//For clear
			if($filterType == 'm-y'){
				$('#dFrom-bd').val('');
				$('#dTo-bd').val('');
			} else if($filterType == 'from-to'){
				$('select[name="hMonth"]').val('');
				$('select[name="hYear"]').val('');
			} else{
				$('#dFrom-bd').val('');
				$('#dTo-bd').val('');
				$('select[name="hMonth"]').val('');
				$('select[name="hYear"]').val('');
			}
		}

	});

	/* 
		Filter for numeric ony
	*/
	$(".FilterNumeric").on('keydown',function (e) {
        // Allow: backspace, delete, tab, escape, enter
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13,]) !== -1 ||
             // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	    	modalMesssage('Numeric character only!');
            e.preventDefault(); 
        }
    });

    /* 
    	Filter Date 
    */  
	$('.date-checker').on('change',function(){
        if (!isValidDate(this.value)) {
        	this.value = '';
        	modalMesssage('Invalid date!');
	        // $('#bd_modal').modal('show');
	        // $('#bd_modal').find('h4').text('Invalid date!');
	        // setTimeout(function() {$('#bd_modal').modal('hide');}, 1200);
        }
	});

	/*
		Filter by vendor code
	 */
	$('#vend_code_field').select2({
		ajax: {
	    url: BASE_URL+"businessdoc/reports/vend_code",
	    dataType: 'json',
	    delay: 250,
	    data: function (params) {
	      return {
	        q: params.term, // search term
	        page: params.page
	      };
	    },
	    processResults: function (data, params) {
	      params.page = params.page || 1;

	      return {
	        results: data,
	        pagination: {
	          more: (params.page * 30) < data.total_count
	        }
	      };
	    },
	    cache: true
	  },
	  minimumInputLength: 1,
	  placeholder: "Select a vendor code",
  	  allowClear: true
	}).on('select2:open', function(e) {
	    $('.select2-search__field').attr('maxlength', 6).on('keydown',function (e) {
	        // Allow: backspace, delete, tab, escape, enter
	        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13,]) !== -1 ||
	             // Allow: Ctrl/cmd+A
	            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: Ctrl/cmd+C
	            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: Ctrl/cmd+X
	            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
	             // Allow: home, end, left, right
	            (e.keyCode >= 35 && e.keyCode <= 39)) {
	                
	                 return;
	        }
	        if(e.target.value.length >= 6){ //just a prompt subjective to user
	        	modalMesssage('Maximum of 6 numeric characters only');
	        }
	        // Ensure that it is a number and stop the keypress
	        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
	        	modalMesssage('Numeric characters only!');
	            e.preventDefault(); 
	        }
	    });
	});

	/*
		Filter by company name
	 */
	$('#filter_cName').select2({
		ajax: {
		    url: BASE_URL+"businessdoc/reports/scompany_name",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        q: params.term, // search term
		        page: params.page,
		        doc: $(this).data('doc'),
		        option: $(this).data('option')
		      };
		    },
		    processResults: function (data, params) {
		      params.page = params.page || 1;
		      return {
		        results: data,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
		    cache: true
	  },
	  minimumInputLength: 1,
	  placeholder: "Select a company name",
	  allowClear: true
	});

	/*
		Filter by store name
	 */
	$('#filter_stName').select2({
		ajax: {
		    url: BASE_URL+"businessdoc/reports/dmcm_stName",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        q: params.term, // search term
		        page: params.page
		      };
		    },
		    processResults: function (data, params) {
		      params.page = params.page || 1;
		      return {
		        results: data,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
		    cache: true
	  },
	  minimumInputLength: 1,
	  placeholder: "Select a store name",
	  allowClear: true
	});

	/*
		Filter by store name
	 */
	$('#filter_location').select2({
		ajax: {
		    url: BASE_URL+"businessdoc/reports/po_location",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        q: params.term, // search term
		        page: params.page,
		        option: $(this).data('option')
		      };
		    },
		    processResults: function (data, params) {
		      params.page = params.page || 1;
		      return {
		        results: data,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
		    cache: true
	  },
	  minimumInputLength: 1,
	  placeholder: "Select a location",
	  allowClear: true
	});

	/*
		Filter by store name
	 */
	$('#filter_deptname').select2({
		ajax: {
		    url: BASE_URL+"businessdoc/reports/po_deptname",
		    dataType: 'json',
		    delay: 250,
		    data: function (params) {
		      return {
		        q: params.term, // search term
		        page: params.page,
		        option: $(this).data('option')
		      };
		    },
		    processResults: function (data, params) {
		      params.page = params.page || 1;
		      return {
		        results: data,
		        pagination: {
		          more: (params.page * 30) < data.total_count
		        }
		      };
		    },
		    cache: true
	  },
	  minimumInputLength: 1,
	  placeholder: "Select a department",
	  allowClear: true
	});

	/* 
		Email function
	*/
	$('#email_func').on('click',function(e){
		$('#progress_bd').find('h4').text('Sending email. Please wait.');
		$footerModal.hide();
		$('#progress_bd').modal('show');
		let success_function = function(responseText)
	    {
	    	$('#progress_bd').find('h4').text('Email sent!');
	    	setTimeout(function() {$('#progress_bd').modal('hide');}, 1200);
	    };
		ajax_request('GET', BASE_URL+'businessdoc/reports/email_pdf/'+$(this).data('id')+'/'+$(this).data('doctype'), '', success_function);		
		e.preventDefault();		
	});

	/*
		Validation for month & year (just in case)
	 */
	$('.m-yFilter').on('change',function(e){
		option = $(this).data('option');
		val = $(this).val();
		currdate = new Date();
		if(option == 'm' && $('select[name="hYear"]').val() == ''){
			mfilter = new Date(val+'/01/'+currdate.getFullYear());
			if(mfilter > currdate){
		    	$(this).val('');
		    	modalMesssage('Invalid date range!');
	            e.preventDefault(); 
			}
		}
		else if($('select[name="hYear"]').val() == currdate.getFullYear()){
			yFilter = new Date($('select[name="hMonth"]').val()+'/01/'+$('select[name="hYear"]').val());
			if(yFilter > currdate){
		    	$(this).val('');
		    	modalMesssage('Invalid date range!');
			}
		}
		else{	
			return false;
		}
	});


	/*
	
 */
$("#bd-filter-expand").on('click',function () {
	console.log($(this).attr('aria-expanded'));
    if($(this).attr('aria-expanded') == false){
    	$(this).text('Click here to minimize');
    } else {
    	$(this).text('Click here to maximize');
    }
});


});

/*
	View archive data link
 */
$(document).on('click','a.archive-link',function(e){ //Checking for initialization
	e.preventDefault();
	if($bDoc_type == 3 || $linkingDataTable == false) //Disable archive data
		return;
	/** Change text and image*/
    $('.archive-link[data-crvt]').text('Archive');
	$('.archive-link[data-crvi]').attr('src',ASSET_URL+'/img/archive-icon.png');
	$('.archive-link[data-vat]').text('Live');
	$('.archive-link[data-vai]').attr('src',ASSET_URL+'/img/live-icon.png');
	/** End changing of text and image */

	$arch_disable.hide();
	let $this = $(this);
	let col;
	$('#reports_table th:last, #reports_table td:last-child').remove();
	$('.bd-dl-button').attr('data-dl-option','archive');
	$('#bd-arch').attr('data-arc-option','archive');
	$('#refresh-docu').attr('data-option','archive');
	$('.archive-link[data-archive]').attr('data-back','live');
	$('#filter_cName').attr('data-option','archive');
	$('#filter_location').attr('data-option','archive');
	$('#filter_deptname').attr('data-option','archive');
	if($this.data('archive') == 2){ // Credit Advice Options
		$datatable.ajax.url(BASE_URL+"businessdoc/reports/ca_arc_dtables").load();
		$datatable.column(5).visible(false);
		col = 1;
	} else if($this.attr('data-archive') == 5){ // Remittance Advice Options
		$datatable.ajax.url(BASE_URL+"businessdoc/reports/ra_arc_dtables").load();
		$datatable.column(7).visible(false);
		col = 2;
	} else if($this.attr('data-archive') == 1){ // Purchase Order options
		$datatable.ajax.url(BASE_URL+"businessdoc/reports/po_arc_dtables").load();
		$datatable.column(11).visible(false);
		col = 10;
		
	}
	$reports_table.on('draw.dt', function (e, settings,data) {
		extractDate($datatable.columns(col).data().eq(0));
	});
	$('#headFilter_clear').click(); //Trigger click for reset filter if filtered exists in live data
});

/*
	Limit field validation
 */
$(document).on('keyup','.limit-field',function(){
	if($bDoc_type == 5 || $bDoc_type == 2 || $bDoc_type == 1){ //RA ,CA, PO
		limitText(this, 10);
	} else {
		limitText(this,8);
	}
    
});

//Check all
$(document).on('change','#checkAll',function(){
    if(this.checked){
      $(".selected_docu").each(function(e){
      	$(this).closest('tr').addClass('selected');
        this.checked=true;
      })              
    }else{
      $(".selected_docu").each(function(e){
        this.checked=false;
        $(this).closest('tr').removeClass('selected');
      })              
    }
});

/*
	Check one unselected checkbox then uncheck checkall
 */
$(document).on('click',".selected_docu",function () {
    if ($(this).is(":checked")){
   		$(this).closest('tr').addClass('selected');
      let isAllChecked = 0;
      $(".selected_docu").each(function(){
        if(!this.checked)
           isAllChecked = 1;
      })              

      if(isAllChecked == 0){ 
      	$("#checkAll").prop("checked", true);
      }     
    }
    else {
    	$(this).closest('tr').removeClass('selected');
      $("#checkAll").prop("checked", false);
    }
});


/*
	Date picker for "from-to" field
 */
var dateFormat = "mm/dd/yy",
from = $( ".date-from" ).attr("placeholder", "mm/dd/yyyy").datepicker({
		dateFormat: dateFormat,
		showOn: "button",
		buttonImage: BASE_URL+"../assets/img/calendar-icon.png",
		buttonImageOnly: true,
		changeMonth: true,
		changeYear: true,
		// inline: true,
		numberOfMonths: 2,
		maxDate: '0'
	})
	.on( "change", function() {
	  to.datepicker( "option", "minDate", getDate( this ) );
	}),
to = $( ".date-to" ).attr("placeholder", "mm/dd/yyyy").datepicker({
	dateFormat: dateFormat,
	showOn: "button",
	buttonImage: BASE_URL+"../assets/img/calendar-icon.png",
	buttonImageOnly: true,
	changeMonth: true,
	changeYear: true,
	// inline: true,
	maxDate: '0',
	numberOfMonths: 2
	})
	.on( "change", function() {
	from.datepicker( "option", "maxDate", getDate( this ) );
});

/*
	Get date today
 */
function getDate( element ) {
	var date;
	try {
		date = $.datepicker.parseDate( dateFormat, element.value );
	} catch( error ) {
		date = null;
	}
	return date;
}

/*
	Get date today for validation
 */
function datetoday() {

	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();

	if(dd<10) {
	    dd = '0'+dd
	} 

	if(mm<10) {
	    mm = '0'+mm
	} 

	today = mm + '/' + dd + '/' + yyyy;
	return today;
}

/*
	MTD Filter
 */
function mtd(curr){
	var date = new Date();
	var first_day = new Date(date.getFullYear(), date.getMonth(), 1);
	return (curr) ?   (date.getMonth()+1) +'/'+date.getDate()+'/'+date.getFullYear() : (date.getMonth()+1) +'/'+ first_day.getDate()+'/'+date.getFullYear();
}

/*
	Checking download file from db
 */
function check_dl_file(trigger) {
	var MILLISECONDS = 1000;
    var SECONDS = 10;
    
    let loop_check = setTimeout(check_dl_file, SECONDS * MILLISECONDS);
    console.log("%cCall ajax for checking file. var callAjax:"+$callAjax, "color: blue; font-size: 20px");
    let success_function = function(responseText){
    	console.log("%cReturn ajax response. response:"+ responseText, "color: blue; font-size: 20px");
    	let $stat = JSON.parse(responseText);
    	try {
			if($stat.status == true){
	    		console.log("%cYesssssssss", "color: green; font-size: 40px");
	    		$.fileDownload(BASE_URL+'businessdoc/reports/file_header', {
					httpMethod: "POST",
					data: 'path='+$stat.path,
					successCallback: function(url) { $bdModal.modal('hide'); }
				});
				$callAjax = false;
				clearTimeout(loop_check);
	    	}
	    	if($stat.server_error == true){
	    		$callAjax = true;
	    		console.log("%cJust incase", "color: brown; font-size: 20px");
	    		// clearTimeout(loop_check);
	    	}
		} catch( error ) {
			$callAjax = true;
			console.log("%cIf response is false set call ajax again. var callAjax:"+$callAjax, "color: green; font-size: 20px");
		}
    	
    }

    let other_config = {
		cache: false,
	    beforeSend: function () {
	    	$callAjax = false;
	    	console.log("%cBefore making ajax call set callajax variable to false. var callAjax:"+$callAjax, "color: red; font-size: 20px");
	    },
	    complete: function(){
	    	$callAjax = true;
	    	console.log("%cIf response is false set call ajax again. var callAjax:"+$callAjax, "color: green; font-size: 20px");
	    }
    }


    if($callAjax == true) {
        ajax_request('GET', BASE_URL+'businessdoc/reports/file_checker' , '', success_function,other_config);
    } 
}

/*
	Check for json format
 */
function isJson(item) {
    item = typeof item !== "string"
        ? JSON.stringify(item)
        : item;

    try {
        item = JSON.parse(item);
    } catch (e) {
        return false;
    }

    if (typeof item === "object" && item !== null) {
        return true;
    }

    return false;
}

/*
	Printing documents
 */
function printJS(doctype,id,comp_id) {

	if(id == 'selected'){
		var $checkLength = $('#reports_table input[type=checkbox]:checked').length == 0;
		$checkDataCount = $datatable.data().count();
		
		if($checkLength || $checkDataCount < 1){
			$('#bd_modal').find('h4').text(($checkDataCount < 1) ? 'No data available.' : 'Please select atleast one document.');
			$('#bd_modal').modal('show');
			$footerModal.hide();
			setTimeout(function() {$('#bd_modal').modal('hide');}, 1200);
			return false;
		} 

		let $selected_docu = $('input:checkbox:checked.selected_docu').map(function (e) {
			return this.value;
		}).get();

		let $selected_comp = $('input:checkbox:checked.selected_docu').map(function (e) {
			return $(this).data('comp-id');
		}).get();

		let success_function = function(responseText)
	    {
	    	var newWindow = window.open();
			$(newWindow.document.body).html(responseText);
	    };

		ajax_request('POST', BASE_URL+'businessdoc/reports/print_template/','doctype='+doctype+'&selected='+$selected_docu+'&id='+id+'&comp='+$selected_comp,success_function);
	}else{
		window.open(BASE_URL+'businessdoc/reports/print_template/'+doctype+'/'+id+'/'+comp_id);
	}
}

/*
	Check for valid date
 */
function isValidDate(dateString){
    // First check for the pattern
    if(!/^\d{1,2}\/\d{1,2}\/\d{4}$/.test(dateString))
        return false;

    // Parse the date parts to integers
    var parts = dateString.split("/");
    var day = parseInt(parts[1], 10);
    var month = parseInt(parts[0], 10);
    var year = parseInt(parts[2], 10);

    // Check the ranges of month and year
    if(year < 1000 || year > 3000 || month == 0 || month > 12)
        return false;

    var monthLength = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];

    // Adjust for leap years
    if(year % 400 == 0 || (year % 100 != 0 && year % 4 == 0))
        monthLength[1] = 29;

    // Check the range of the day
    return day > 0 && day <= monthLength[month - 1];
}

/*
	Date formatter
 */
function dateFormater(datestring){
	if(datestring){
		var d = new Date(datestring);
		var datestring = d.getMonth()+1  + "/" + d.getDate() + "/" + d.getFullYear();
		return datestring;
	} else {
		return null;
	}
}

/*
	Limit text field
 */
function limitText(field, maxChar){
    var ref = $(field),
        val = ref.val();
    if ( val.length > maxChar ){
    	ref.attr('readonly',true);
    	$('#bd_modal_conf').addClass('hidden');
    	$('#bd_modal').find('h4').text('Maximum of '+maxChar+' numeric characters only!');
    	$('#bd_modal').modal('show');
    	 ref.val('');
    	setTimeout(function() {
    		$('#bd_modal').modal('hide');
    		ref.attr('readonly',false);
    	}, 1200);
    }
}

/*
	Refresh data from datatable
 */
function refreshBut(){
	$('#headFilter_clear').click(); //subjective to user
	$datatable.ajax.reload();
}

/*
	Extract date from string
 */
function extractDate($data){
	var months = { 1:"January", 2:"February", 3:"March", 4:"April", 5:"May", 6:"June", 7:"July", 8:"August", 9:"September", 10:"October", 11:"November", 12:"December" }
	    ,monthList = {}
	    ,yearList = [];
	//Month list    
	for (var i = $data.length - 1; i >= 0; i--) {
		arr = $data[i].split("/");

		value= months[parseInt(arr[0],10)];
		key = getKeyByValue(months,value);
		
		if(!monthList.hasOwnProperty(key)){
			monthList[key] = value;
		}
		
	}
	//Year list
	for (var i = $data.length - 1; i >= 0; i--) {
		arr = $data[i].split("/");
		if(!yearList.includes(arr[2])){
			yearList.push(arr[2]);
		}
		
	}

	$hMonth = $('.m-yFilter[name="hMonth"]');
	$hYear = $('.m-yFilter[name="hYear"]');
	$hMonth.empty();
	$hYear.empty();
	$hMonth.append($("<option value=''>--Month--</option>"));
	$.each(monthList, function(key,value) {
	  	$hMonth.append($("<option></option>")
	     .attr("value", key).text(value));
	});
	$hYear.append($("<option value=''>--Year--</option>"));
	$.each(yearList, function(key,value) {
	  	$hYear.append($("<option></option>")
	     .attr("value", value).text(value));
	});
}

/*
	Get key by value
 */
function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}

/*
	Global modal message
 */
function modalMesssage(message){
	$footerModal.addClass('hidden');
	$bdModal.find('h4').text(message);
	$bdModal.modal('show');
	setTimeout(function() {$bdModal.modal('hide');}, 1200);
}
