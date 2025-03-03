<html>

<head>
	<link href="<?=base_url().'assets/dist/css/bootstrap.min.css'?>" rel="stylesheet">
	<script src="<?=base_url().'assets/js/jquery-3.2.1.min.js'?>"></script>
	<script src="<?=base_url().'assets/js/jquery_ajax.js'?>"></script>
	<script src="<?=base_url();?>assets/dist/js/bootstrap.min.js"></script>
	<script src="//cdn.jsdelivr.net/bluebird/3.5.0/bluebird.min.js"></script>
	<script>
		jQuery.fn.table2CSV = function(options) {
			var options = jQuery.extend({
				separator: ',',
				header: [],
				headerSelector: 'th',
				columnSelector: 'td',
				delivery: 'popup', // popup, value, download
				// filename: 'powered_by_sinri.csv', // filename to download
				transform_gt_lt: true // make &gt; and &lt; to > and <
			},
			options);

			var csvData = [];
			var headerArr = [];
			var el = this;

			//header
			var numCols = options.header.length;
			var tmpRow = []; // construct header avalible array

			if (numCols > 0) {
				for (var i = 0; i < numCols; i++) {
					tmpRow[tmpRow.length] = formatData(options.header[i]);
				}
			} else {
				$(el).filter(':visible').find(options.headerSelector).each(function() {
					if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
				});
			}

			row2CSV(tmpRow);

			// actual data
			$(el).find('tr').each(function() {
				var tmpRow = [];
				$(this).filter(':visible').find(options.columnSelector).each(function() {
					if ($(this).css('display') != 'none') tmpRow[tmpRow.length] = formatData($(this).html());
				});
				row2CSV(tmpRow);
			});
			if (options.delivery == 'popup') {
				var mydata = csvData.join('\n');
				if(options.transform_gt_lt){
					mydata=sinri_recover_gt_and_lt(mydata);
				}
				return popup(mydata);
			}
			else if(options.delivery == 'download') {
				var mydata = csvData.join('\n');
				if(options.transform_gt_lt){
					mydata=sinri_recover_gt_and_lt(mydata);
				}
				var url='data:text/csv;charset=utf8,' + encodeURIComponent(mydata);
				window.open(url);
				return true;
			} 
			else {
				var mydata = csvData.join('\n');
				if(options.transform_gt_lt){
					mydata=sinri_recover_gt_and_lt(mydata);
				}
				return mydata;
			}

			function sinri_recover_gt_and_lt(input){
				var regexp=new RegExp(/&gt;/g);
				var input=input.replace(regexp,'>');
				var regexp=new RegExp(/&lt;/g);
				var input=input.replace(regexp,'<');
				return input;
			}

			function row2CSV(tmpRow) {
				var tmp = tmpRow.join('') // to remove any blank rows
				// alert(tmp);
				if (tmpRow.length > 0 && tmp != '') {
					var mystr = tmpRow.join(options.separator);
					csvData[csvData.length] = mystr;
				}
			}
			function formatData(input) {
				// replace " with “
				var regexp = new RegExp(/["]/g);
				var output = input.replace(regexp, "“");
				//HTML
				var regexp = new RegExp(/\<[^\<]+\>/g);
				var output = output.replace(regexp, "");
				output = output.replace(/&nbsp;/gi,' '); //replace &nbsp;
				if (output == "") return '';
				return '"' + output.trim() + '"';
			}
			function popup(data) {
				var generator = window.open('', 'csv', 'height=400,width=600');
				generator.document.write('<html><head><title>CSV</title>');
				generator.document.write('</head><body >');
				generator.document.write('<textArea cols=70 rows=15 wrap="off" >');
				generator.document.write(data);
				generator.document.write('</textArea>');
				generator.document.write('</body></html>');
				generator.document.close();
				return true;
			}
		};
	</script>
	<style>
		#result-container {
			display: none;
		}

		/**.header-fixed {
				width: 100% 
			}

			.header-fixed > thead,
			.header-fixed > tbody,
			.header-fixed > thead > tr,
			.header-fixed > tbody > tr,
			.header-fixed > thead > tr > th,
			.header-fixed > tbody > tr > td {
				display: block;
			}

			.header-fixed > tbody > tr:after,
			.header-fixed > thead > tr:after {
				content: ' ';
				display: block;
				visibility: hidden;
				clear: both;
			}

			.header-fixed > tbody {
				overflow-y: auto;
				height: 250px;
			}

			.header-fixed > tbody > tr > td,
			.header-fixed > thead > tr > th {
				padding-left: 0;
				width: 20%;
				float: left;
			}
			.table-container{
				margin: 10px;
			}**/

		.table-responsive {
			height: 250px;
			overflow: auto;
		}

		.badge {
			padding: 1px 9px 2px;
			font-size: 12.025px;
			font-weight: bold;
			white-space: nowrap;
			color: #ffffff;
			background-color: #999999;
			-webkit-border-radius: 9px;
			-moz-border-radius: 9px;
			border-radius: 9px;
		}

		.badge:hover {
			color: #ffffff;
			text-decoration: none;
			cursor: pointer;
		}

		.badge-error {
			background-color: #b94a48 !important;
		}

		.badge-error:hover {
			background-color: #953b39 !important;
		}

		.badge-warning {
			background-color: #f89406 !important;
		}

		.badge-warning:hover {
			background-color: #c67605 ! important;
		}

		.badge-success {
			background-color: #468847 ! important;
		}

		.badge-success:hover {
			background-color: #356635 ! important;
		}

		.badge-info {
			background-color: #3a87ad ! important;
		}

		.badge-info:hover {
			background-color: #2d6987 ! important;
		}

		.badge-inverse {
			background-color: #333333 ! important;
		}

		.badge-inverse:hover {
			background-color: #1a1a1a ! important;
		}

	</style>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<h1>Upload CSV</h1>
			</div>
			<div class="col-md-12">
				<form method="POST" action="<?php echo base_url() . 'index.php/maintenance/users/upload_file'?>" id="uploader_form">
					<div class="form-group">
						<label for="vrd_csv_file">VRD: </label>
						<input type="file" name="vrd_csv_file" class="form-control" accept=".csv" />

					</div>
					<div class="form-group">
						<label for="users_csv_file">SM Users(FASHEAD, BUHEAD, GHEAD and Inviters): </label>
						<input type="file" name="users_csv_file" class="form-control" accept=".csv" />

					</div>
					<div class="form-group">
						<label for="categories_csv_file">Categories: </label>
						<input type="file" name="categories_csv_file" class="form-control" accept=".csv" />

					</div>

					<input type="submit" value="Upload" class="btn btn-primary" />
				</form>
			</div>
		</div>

		<div class="col-md-12" id="debug-result">

		</div>
		<div class="row" id="result-container">
			<div class="col-md-12">
				<h1>Results:</h1>


				<div class="panel panel-default" id="vrd_panel">
					<div class="panel-heading">
						<a data-toggle="collapse" href="#vrd">VRD CSV Result</a>
						&nbsp;&nbsp;&nbsp;
						<span class="badge badge-success" id="total_vrd"></span>
						<span class="badge badge-error" id="vrd_total_failed"></span>
						<span class="badge badge-warning" id="vrd_total_uploading"></span>
					</div>
					<div class="panel-collapse collapse table-container" id="vrd">
						<div class="table-responsive table-striped header-fixed">
							<table class="table table-hover table-striped" id="vrd_table">
								<thead>
									<th>#</th>
									<th>Position</th>
									<th>ADID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Status</th>
								</thead>
								<tbody id="vrd_tbody">

								</tbody>
							</table>
						</div>
					</div>
					<div class="panel-footer">
						<a id="vrd_download_csv"href="" target="_blank" class="btn btn-primary">Download CSV</a>
					</div>
				</div>

				<div class="panel panel-default" id="users_panel">
					<div class="panel-heading">
						<a data-toggle="collapse" href="#sm_users">SM Users(FASHEAD, BUHEAD, GHEAD and Inviters) CSV Result</a>
						&nbsp;&nbsp;&nbsp;
						<span class="badge badge-success" id="total_users"></span>
						<span class="badge badge-error" id="users_total_failed"></span>
						<span class="badge badge-warning" id="users_total_uploading"></span>
					</div>
					<div class="panel-collapse collapse table-container" id="sm_users">

						<div class="table-responsive table-striped">
							<table class="table table-hover table-striped header-fixed" id="sm_users_table">
								<thead>
									<th>#</th>
									<th>ADID</th>
									<th>Name</th>
									<th>Email</th>
									<th>Categories</th>
									<th>Approver</th>
									<th>Group HEAD</th>
									<th>Status</th>
								</thead>
								<tbody id="users_tbody">
								</tbody>
							</table>
						</div>
					</div>
					<div class="panel-footer">
						<a id="sm_users_download_csv"href="" target="_blank" class="btn btn-primary">Download CSV</a>
					</div>
				</div>

				<div class="panel panel-default" id="categories_panel">
					<div class="panel-heading">
						<a data-toggle="collapse" href="#category">Categories CSV Result</a> &nbsp;&nbsp;&nbsp;
						<span class="badge badge-success" id="total_categories"></span>
						<span class="badge badge-error" id="categories_total_failed"></span>
						<span class="badge badge-warning" id="categories_total_uploading"></span>
					</div>
					<div class="panel-collapse collapse table-container" id="category">
						<div class="table-responsive table-striped">
							<table class="table table-hover table-striped header-fixed" id="category_table">
								<thead>
									<th>#</th>
									<th>VENDOR TYPE</th>
									<th>CATEGORY</th>
									<th>Status</th>
								</thead>
								<tbody id="categories_tbody">

								</tbody>
							</table>
						</div>
					</div>
					<div class="panel-footer">
						<a id="category_download_csv"href="" target="_blank" class="btn btn-primary">Download CSV</a>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
	
		function generate_tbody(data, type) {
			var tbody_str = '';
			var data_length = data.length;
			var counter = 1;
			$('#total_' + type).html('Total: ' + data_length);
			
			//View
			for (var x = 0; x < data_length; x++) {

				tbody_str += '<tr data-row-no="' + counter + '">';

				var keys = Object.keys(data[x]);
				var keys_length = keys.length;

				tbody_str += '<td>' + counter + '</td>';
				if(type != 'users'){		
					for (var y = 0; y < keys_length; y++) {
						tbody_str += '<td>' + data[x][keys[y]] + '</td>';
					}
				}else{
					tbody_str += '<td>' + data[x]['adid'] + '</td>';
					tbody_str += '<td>' + data[x]['name'] + '</td>';
					tbody_str += '<td>' + data[x]['email'] + '</td>';
					tbody_str += '<td>' + data[x]['categories'] + '</td>';
					tbody_str += '<td>' + data[x]['approver']['name'] + ' | ADID = ' + data[x]['approver']['adid'] + '</td>';
					if(data[x]['ghead']['name'] != undefined){
						tbody_str += '<td>' + data[x]['ghead']['name'] +  ' | ADID = ' + data[x]['ghead']['adid'] + '</td>';
					}else{
						tbody_str += '<td>N/A</td>';
					}
				}
				tbody_str += '<td class="status-td"><span class="badge badge-warning">Uploading...</span></td>';

				tbody_str += '</tr>';
				counter++;
			}
			
			return tbody_str;
		}
		
		function delayMyPromise(myPromise, myDelay){
		  return Promise.delay(myDelay).then(function() {
			return myPromise;
		  });
		}
	
		function add_data(data, type, url, fd_keys = [], vrd_head_array = [], vrd_staff_array = []) {
			var data_length = data.length;
			var counter = 1;
			var promises = [];
			var total_failed = 0;
			var total_uploading = data_length;
			$('#' + type + '_total_failed').html('Failed: ' + total_failed);
			$('#' + type + '_total_uploading').html('Uploading: ' + total_uploading);
			$.each(data, function (x, item) {
				var formData;
				
				if(!fd_keys.length){
					formData = [];
				}else{
					formData = new FormData();
				}

				var keys = Object.keys(data[x]);
				var keys_length = keys.length;
				var new_array;
				
				var id = 'body #' + type + '_tbody tr[data-row-no="' +  counter + '"] td[class="status-td"] span';
				
				var success = false;
				var default_bool = true;
				var callback = function(result){
					try{
						console.log(type + ' res = ' + result);
						var res = JSON.parse(result);
						if(type == 'categories'){
							if(res.data || res.data === undefined && res == 1){
								if(res.data == 'exist'){
									default_bool = false;
									$(id).html('Already Exist');
									$(id).attr('class', 'badge badge-success');
								}else{
									success =  true;
									default_bool = true;
								}
							}else if(res == 'exist'){
								default_bool = false;
									
								$(id).html('Already Exist');
								$(id).attr('class', 'badge badge-success');
							}
						}else if(type == 'vrd' || type == 'users'){
							if(res == 'exist'){
								$(id).html('Login ID already exist');
								$(id).attr('class', 'badge badge-success');
								default_bool = false;
							}else if(res == true){
								success =  true;
								default_bool = true;
							}
						}
						
					}catch(ex){
						if(result == 1){
							success = true;
							default_bool = true;
						}
					}
					
					if(default_bool){
						if(success){
							$(id).html('Success');
							$(id).attr('class', 'badge badge-success');
						}else{					
							if(type != 'users'){
								total_failed++;
								$('#' + type + '_total_failed').html('Failed: ' + total_failed);
							}
							$(id).html('Failed');
							$(id).attr('class', 'badge badge-error');
						}
					}
				};
				
				var callback2 = function(result){
					try{
						//console.log(type + ' res = ' + result);
						var res = JSON.parse(result);
						if(type == 'categories'){
							if(res.data || res.data === undefined && res == 1){
								if(res.data == 'exist'){
									default_bool = false;
									//$(id).html('Already Exist');
									//$(id).attr('class', 'badge badge-success');
								}else{
									success =  true;
									default_bool = true;
								}
							}else if(res == 'exist'){
								default_bool = false;
									
								//$(id).html('Already Exist');
								//$(id).attr('class', 'badge badge-success');
							}
						}else if(type == 'vrd' || type == 'users'){
							if(res == 'exist'){
								//$(id).html('Login ID already exist');
								//$(id).attr('class', 'badge badge-success');
								default_bool = false;
							}else if(res == true){
								success =  true;
								default_bool = true;
							}
						}
						
					}catch(ex){
						if(result == 1){
							success = true;
							default_bool = true;
						}
					}
					
					if(default_bool){
						if(success){
							console.log('success');
						}else{					
						
							console.log('failed');
						}
					}
				};
				var callback3 = function (result){
					var res;
					//console.log(result + ' ' + counter);
					try{
						res = JSON.parse(result);
						try{
							var er = res.error;
							if(er){
								total_failed++;
								$('#' + type + '_total_failed').html('Failed: ' + total_failed);
								$(id).html('Failed' + er);
								$(id).attr('class', 'badge badge-error');
								default_bool = false;
							}
						}catch(errx){
							//console.log('TRY ERR = ' + errx);
							$(id).html('Failed');
							$(id).attr('class', 'badge badge-error');
							default_bool = false;
						}
						if(res == 'exist'){
							$(id).html('Login ID already exist');
							$(id).attr('class', 'badge badge-success');
							default_bool = false;
						}else if(res == true){
							success =  true;
							default_bool = true;
						}else{
							
						}
						
					}catch(ex){
						if(result == 1){
							success = true;
							default_bool = true;
						}
					}
					
					if(default_bool){
						if(success){
							try{
								console.log('TRYSOMETEST1 = ' + res);
								console.log('TRYSOMETEST2 = ' + res.error);
							}catch(ex){
								console.log('SOMETEST ' + ex);
							}
							$(id).html('Success');
							$(id).attr('class', 'badge badge-success');
						}else{					
							if(type != 'users'){
								total_failed++;
								$('#' + type + '_total_failed').html('Failed: ' + total_failed);
							}
							$(id).html('Failed');
							$(id).attr('class', 'badge badge-error');
						}
					}
				};
				for (var y = 0; y < keys_length; y++) {
					if(!fd_keys.length){
						if(type == 'categories'){
							//Columns in CSV
							//0 - VENDOR TYPE
							//1 - CATEGORY
							
							//For Categories CSV only
							
							formData[0] = data[x][keys[y + 1]];
							formData[1] = data[x][keys[y + 1]];
							
							if(data[x][keys[y]].toUpperCase().trim() == 'NON-TRADE' || data[x][keys[y]].toUpperCase().trim() == 'NON TRADE'){
								if(data[x][keys[y + 1]].toUpperCase().trim() == 'FIXED ASSETS AND SUPPLIES (FAS)'){
									formData[2] = 1; //NTFAS
								}else{
									formData[2] = 3; //NTS 
								}
							}else if(data[x][keys[y]].toUpperCase() == 'TRADE'){
								formData[2] = 2; //TRADE 
							}
							break;
							
							
						}else if(type == 'users'){
							
							if(data[x]['error']){
								total_failed++;
								$('#' + type + '_total_failed').html('Failed: ' + total_failed);
								$(id).html('Failed. ' + data[x]['error']);
								$(id).attr('class', 'badge badge-error');
								total_uploading--;
								$('#' + type + '_total_uploading').html('Uploading: ' + total_uploading);
								break;
							}
							//Columns in CSV
							//ID POSITION
							//1	ADMIN
							//2	SENIOR MERCHANDISE MANAGER
							//3	BU/MERCHANDISING HEAD
							//4	VRD STAFF
							//5	VRD HEAD
							//6	HTS
							//7	BUYER/DEPARTMENT MANAGER
							//8	GROUP HEAD
							//9	FAS HEAD
							//10 VENDOR
							//11 NTS INVITER
							
							//BUHEAD, FASHEAD, COLUMN
							//Index - Name
							//1 	- APPROVER (NAME)
							//2 	- ADID (Login ID)
							//3 	- E-MAIL ADDRESS
							
							//GROUP HEAD
							//4 	- GROUP HEAD (NAME)
							//5 	- ADID (Login ID)
							//6 	- E-MAIL ADDRESS
							
							//INVITER(NTFAS, SENMER, NTS)
							//7 	- INVITER (NAME)
							//8 	- ADID (Login ID)
							//9 	- E-MAIL ADDRESS
							//10 	- VENDOR TYPE
										//ID Name (Note lng)
										//1 NTFAS (BUYER)
										//2 TRADE (SENMER)
										//3 NTS
							//11 	- DEPARTMENT / CATEGORY
							
							//Order of adding
							//1. APPROVER
							//2. GHEAD
							//3. Inviter's Assigned Categories
							//4. Inviter's Other data
							
							//based on users.js save_new_user()
							var position_type = data[x]['approver']['position_id']; //Position Type
							
							var _data = {
								fn:  data[x]['approver']['name'],
								mn: '',
								ln: '',
								mo: '1234567890',//mobile number
								em:  data[x]['approver']['email'],//'justine.jovero@novawaresystems.com', //email data[x][keys[3]] <- wag ilagay tong var,  email muna natin pang test
								log: data[x]['approver']['adid'], //login id
								se: 0
							}
					
							var head = [];
							var cat_id = [];
							var vrdhead = [];
							var vrd = [];
							formData = {
								data: _data, 
								head: head, 
								category: cat_id, 
								type: position_type, 
								vrd: vrd,  //vrd staff to
								vrdhead: vrdhead,
								uploader: false,
								vendor_type_id: ''
							};
							//For BUHEAD/FASHEAD
							promises.push(ajax_request('POST', "<?php echo base_url() . 'index.php/'; ?>" + url, formData, callback2, {
								}).fail(function() {
									$(id).html('Failed');
									$(id).attr('class', 'badge badge-error');
								})
							);
							
							
							//If fashead then add ghead
							if(position_type == 9){
								//FOR HEAD
								_data.fn = data[x]['ghead']['name']; // Name
								_data.log = data[x]['ghead']['adid']; // Login ID
								_data.em = data[x]['ghead']['email']; // Login ID
								formData.data = _data;
								formData.type = 8; //GROUP HEAD
								
								promises.push(ajax_request('POST', "<?php echo base_url() . 'index.php/'; ?>" + url, formData, callback2, {
									}).fail(function() {
										$(id).html('Failed');
										$(id).attr('class', 'badge badge-error');			
									})
								);
								
							}
							//---------------------------------------------------------
							
						
							//For User Assigned Categories CSV only
							var categories_array = data[x]['categories'];
							
							var categories_array_length = categories_array.length;
							
							
							var vendor_type_id = data[x]['vendor_type'];
							
							$.each(categories_array, function(z, categ){
								var formData2 = [];
								formData2[0] = categories_array[z];
								formData2[1] = categories_array[z];
								formData2[2] = vendor_type_id;
								
								formData2 = {data: JSON.stringify(formData2)};
								promises.push(ajax_request('POST', "<?php echo base_url() . 	'index.php/maintenance/category/uploader_add_category'; ?>", formData2, callback2,{
									}).fail(function() {
										$(id).html('Failed');
										$(id).attr('class', 'badge badge-error');
									})
								);
								
							});
							
							//---------------------------------------------------------
							
							//Need to get the id's of following variables:
							//head, cat_id, vrdhead, head
							//Sample Data
							/*
							  ["head"]=>
							  array(5) {
								[0]=>
								string(4) "3410"
								[1]=>
								string(0) ""
								[2]=>
								string(4) "2009"
								[3]=>
								string(0) ""
								[4]=>
								string(0) ""
							  }
							  ["category"]=>
							  array(2) {
								[0]=>
								string(4) "1145"
								[1]=>
								string(4) "1163"
							  }
							  ["type"]=>
							  string(1) "2"
							  ["vrd"]=>
							  array(2) {
								[0]=>
								string(4) "2006"
								[1]=>
								string(4) "2010"
							  }
							  ["vrdhead"]=>
							  array(2) {
								[0]=>
								string(4) "3410"
								[1]=>
								string(4) "2007"
							  }**/
							  
							
							var vrdhead = vrd_head_array;
							vrd  = vrd_staff_array;
							
							var vendor_type_id = data[x]['vendor_type'];
							var vendor_type = data[x]['vendor_type'];
							formData.type = data[x]['position_id'];
							head[0] = ''; //wala
							head[1] = ''; //wala
							head[2] = ''; //buhead
							head[3] = ''; //ghead
							head[4] = ''; //fashead
							
							cat_id = data[x]['categories'];
							
							
							if(vendor_type_id == 1 || vendor_type_id == 3){
								//For SENMER and NTS Inviter
								head[2] = data[x]['approver']['adid']; // id of buhead
							}else{
								//For NTFAS Inviter
								head[3] = data[x]['ghead']['adid']; // id of ghead 
								head[4] = data[x]['approver']['adid']; // id of fashead
							}
						
							_data.head = head;
							_data.vrd = vrd;
							_data.vrdhead = vrdhead;
							_data.fn = data[x]['name']; // Name
							_data.log = data[x]['adid']; // Login ID
							_data.em = data[x]['email']; // Email
							formData.data = _data;
							formData.category = categories_array;	
							formData.vrdhead = vrdhead;
							formData.vrd = vrd;
							formData.head = head;
							formData.uploader = true;
							formData.vendor_type_id = vendor_type_id;
							
							promises.push(ajax_request('POST', "<?php echo base_url() . 'index.php/'; ?>" + url, formData, callback3, {
							}).fail(function() {
								$(id).html('Failed');
								$(id).attr('class', 'badge badge-error');
							}).always(function(){
								
								if(total_uploading > 0){
									total_uploading--;
									$('#' + type + '_total_uploading').html('Uploading: ' + total_uploading);
								}
							}));
										
							
							/*if(users_type_mode[users_counter] == 'approvers'){
								
								
							}else if(users_type_mode[users_counter] == 'categories'){
								
							}else if(users_type_mode[users_counter] == 'inviters'){
								
							}*/
							break;
						}else if(type == 'vrd'){
							//Columns in CSV
							//0 - RESPONSIBILITY (POSITION)
								//ID POSITION
								//1	ADMIN
								//2	SENIOR MERCHANDISE MANAGER
								//3	BU/MERCHANDISING HEAD
								//4	VRD STAFF
								//5	VRD HEAD
								//6	HTS
								//7	BUYER/DEPARTMENT MANAGER
								//8	GROUP HEAD
								//9	FAS HEAD
							//1 - NAME
							//2 - ADID()
							//3 - E-MAIL ADDRESS
							//based on users.js
							var _data = {
								fn:  data[x][keys[1]],
								mn: '',
								ln: '',
								mo: '1234567890',//mobile number
								em: data[x][keys[3]],//'justine.jovero@novawaresystems.com', //email data[x][keys[3]] <- wag ilagay tong var,  email muna natin pang test
								log: data[x][keys[2]], //login id
								se: 0
							}
							
							var position_type;  //Position Type
							var position_name = data[x][keys[0]].toUpperCase().trim();
							
							if(position_name == 'VRD HEAD'){
								position_type = 5;
							}else if(position_name == 'VRD STAFF'){
								position_type = 4;
							}else if(position_name == 'HATS'){
								position_type = 6;
							}
							
							var head = [];
							var cat_id = [];
							var vrdhead = [];
							var vrd = [];
							formData = {
								data: _data, 
								head: head, 
								category: cat_id, 
								type: position_type, 
								vrd: vrd, 
								vrdhead: vrdhead
							};
							break;
						}else{
							formData[y] = data[x][keys[y]];
						}
					}else{
						formData.append(fd_keys[y], data[x][keys[y]]);
					}
					
				}
			
				if(!fd_keys.length && type == 'categories'){
					formData = {data: JSON.stringify(formData)};
				}
				
				if(type != 'users'){
					promises.push(ajax_request('POST', "<?php echo base_url() . 'index.php/'; ?>" + url, formData, callback, {
						}).fail(function() {
							total_failed++;
							$('#' + type + '_total_failed').html('Failed: ' + total_failed);
							$(id).html('Failed');
							$(id).attr('class', 'badge badge-error');
						}).always(function(){
							if(total_uploading > 0){
								total_uploading--;
								$('#' + type + '_total_uploading').html('Uploading: ' + total_uploading);
							}
						})
					);
				}
				
				counter++;
				console.log(type + ' = ' + counter);
			});
			
			/*Promise.all(promises).then(function(datas){
			   for(var y = 0; y < datas.length; y++){
					console.log(type + ' = Type = ' + y + ' Promise = ' + datas[y]);
			   }
			});*/
			//console.log(type + ' = ' + promises);
			return promises;
		}
		
		$(function () {
			$("#uploader_form").on("submit", function (e) {
				e.preventDefault();
				
				$('#result-container').css('display', 'none');
				if ($('input[name=vrd_csv_file]')[0].files[0] === undefined && $('input[name=users_csv_file]')[0].files[0] === undefined && $('input[name=categories_csv_file]')[0].files[0] === undefined) {
					alert('Please upload a CSV file.');
					return;
				}
				
				if ($('input[name=vrd_csv_file]')[0].files[0] !== undefined && $('input[name=users_csv_file]')[0].files[0] === undefined && $('input[name=categories_csv_file]')[0].files[0] === undefined) {
					alert('Please upload vrd and sm users wth categories file.');
					return;
				}
				
				if ($('input[name=vrd_csv_file]')[0].files[0] === undefined && $('input[name=users_csv_file]')[0].files[0] !== undefined && $('input[name=categories_csv_file]')[0].files[0] === undefined) {
					alert('Please upload vrd and sm users wth categories file.');
					return;
				}
				
				if ($('input[name=vrd_csv_file]')[0].files[0] !== undefined && $('input[name=users_csv_file]')[0].files[0] === undefined && $('input[name=categories_csv_file]')[0].files[0] !== undefined) {
					alert('Please upload vrd and sm users wth categories file.');
					return;
				}
				
				if ($('input[name=vrd_csv_file]')[0].files[0] === undefined && $('input[name=users_csv_file]')[0].files[0] !== undefined && $('input[name=categories_csv_file]')[0].files[0] !== undefined) {
					alert('Please upload vrd and sm users wth categories file.');
					return;
				}
				

				var formData = new FormData();
				formData.append('vrd', $('input[name=vrd_csv_file]')[0].files[0]);
				formData.append('users', $('input[name=users_csv_file]')[0].files[0]);
				formData.append('categories', $('input[name=categories_csv_file]')[0].files[0]);
				$('#debug-result').html("");
				$('body').css('cursor', 'wait');
				$('#debug-result').html('<h1>Please wait...</h1>');
				
				var res = function (result) {

					//console.log(result);
					var data = JSON.parse(result);
					//return;
					
					//Categories
					var cat_var = new Promise(function(resolve, reject) {
							try {
								if (data.categories.error) {
									//$('#debug-result').append(data.categories.output_message);
									$('#categories_panel').css("display", "none");
									resolve("Stuff worked!");
								} else {
									var categories = data.categories.csv_array;
									$('#categories_panel').css("display", "block");
									
									$("#categories_tbody").html(generate_tbody(categories, 'categories'));
									$('#category').collapse('show');
									
									add_data(categories, 'categories', 'maintenance/category/uploader_add_category');
									resolve("Stuff worked!");
								}
							} catch (ex) {
								$('#categories_panel').css("display", "none");
								console.log('Category CSV File does not exists. Err: ' + ex);
								reject('It broke ' + ex);
							}
						});
					
					//VRD
					var vrd_var = new Promise(function(resolve, reject) {
							try {
								if (data.vrd.error) {
									$('#debug-result').append(data.vrd.output_message);
									$('#vrd_panel').css("display", "none");
									resolve("Stuff worked!");
								} else {
									var vrd = data.vrd.csv_array;
									$('#vrd_panel').css("display", "block");
									$("#vrd_tbody").html(generate_tbody(vrd, 'vrd'));
									$('#vrd').collapse('show');
									
									vrd_res = add_data(vrd, 'vrd', 'maintenance/users/save_user_new');
									resolve("Stuff worked!");
								}
							} catch (ex) {
								$('#vrd_panel').css("display", "none");
								console.log('VRD CSV File does not exists. Err: ' + ex);
								reject('It broke ' + ex);
							}
						});
					
					//SM Users (Inviters and Approvers)
					var sm_var = new Promise(function(resolve, reject) {
							
							try {
								if (data.users.error) {
									$('#debug-result').append(data.users.output_message);
									$('#users_panel').css("display", "none");
										resolve("Stuff worked!");
								} else {
									var users = data.users.csv_array;
									var users_vrd_head = data.users.vrd_head;
									var users_vrd_staff= data.users.vrd_staff;
									$('#users_panel').css("display", "block");
									$("#users_tbody").html(generate_tbody(users, 'users'));
									$('#sm_users').collapse('show');
									
									add_data(users, 'users', 'maintenance/users/save_user_new', [], users_vrd_head, users_vrd_staff);
										resolve("Stuff worked!");
								}
							} catch (ex) {
								$('#users_panel').css("display", "none");
								console.log('User CSV File does not exists. Err: ' + ex);
								reject('It broke ' + ex);
							}
						});
						
					var promises = [];
					promises.push(cat_var);
					promises.push(vrd_var);
					promises.push(sm_var);
					Promise.all(promises).then(function(datas){
						/*var promises_sub = [];
					    for(var y = 0; y < datas.length; y++){
						   console.log('1FROM MAIN = ' + y + ' = ' + datas[y]);
						   promises_sub.push(delayMyPromise(datas[y]), 2000);
					    }
						
						Promise.all(promises_sub).then(function(datas2){
							for(var y = 0; y < datas2.length; y++){
							   console.log('FROM MAIN = ' + y + ' = ' + datas2[y]);
							}
						});*/
					})
						$('#result-container').css('display', 'block');
				};

				ajax_request('POST', $(this).attr('action'), formData, res, {
					processData: false,
					contentType: false,
				}).fail(function(res){
					$('#debug-result').html('<h1>Someting went wrong</h1>');
					$('body').css('cursor', 'default');
				}).done(function(res){
					$('#debug-result').html('');
					$('body').css('cursor', 'default');
				});

				e.stopImmediatePropagation();
			});
			
				
			$('#vrd_download_csv').on('click', function(e){
				var data = $("#vrd_table").table2CSV({delivery:'value'});
				$('#vrd_download_csv')
					.attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(data))
					.attr('download','vrd.csv');
			});
				
			$('#sm_users_download_csv').on('click', function(e){
				var data = $("#sm_users_table").table2CSV({delivery:'value'});
				$('#sm_users_download_csv')
					.attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(data))
					.attr('download','approvers and inviters.csv');
			});
				
			$('#category_download_csv').on('click', function(e){
				var data = $("#category_table").table2CSV({delivery:'value'});
				$('#category_download_csv')
					.attr('href','data:text/csv;charset=utf8,' + encodeURIComponent(data))
					.attr('download','category.csv');
			});
		});

	</script>
</body>

</html>
