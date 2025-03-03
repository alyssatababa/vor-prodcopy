<html>
	<head>
		<link href="<?=base_url().'assets/dist/css/bootstrap.min.css'?>" rel="stylesheet">
		<link href="<?=base_url().'assets/dist/css/style.css'?>" rel="stylesheet">
	</head>
	<body>
		<div class="container-fluid">


			<?Php
				$tables = '';
				if ($result && count($result)>0) {
					$row_no = 0;
					$failed_count = 0;
					$display_type = 1;
					if ($display_type==1) {
						$tables .= '<div class="row"><div class="col"><div class="table-responsive table-striped" style="height: 400px;">';
						$tables .=  '<table id="table_queue_'.$row_no.'" class="table table-hover table-striped"><thead>';
							
							//Hide columns 
							$hide_column = array('Test Datatype', 'Expected Datatype', 'Line Number', 'File Name');
							
							foreach ($result as $test_case){
								$row_no++;
								if ($row_no==1) {
									$tables .=  '<tr>';
									$tables .=  '<th>No</th>';
										foreach ($test_case as $key=>$item){
											if( ! in_array($key, $hide_column)){
												$tables .=  '<th>'.$key.'</th>';
											}
										}
									$tables .=  '</tr></thead><tbody>';
								}
								$tables .=  '<tr>';
								$tables .=  '<td>'.$row_no.'</td>';

								foreach ($test_case as $key=>$item){
									if(in_array($key, $hide_column)) continue;
									
									$item_formatted = $item;
									if ($key=='Result' && $item=='Failed') {$item_formatted = '<font color="red">'.$item.'</font>';$failed_count++;}
									if ($key=='Result' && $item=='Passed') {$item_formatted = '<font color="green">'.$item.'</font>';}

									// $tables .=  '<tr>';
									// $tables .=  '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$key.'</td>';
									$tables .=  '<td style="word-wrap:break-word; white-space: pre-wrap">'.$item_formatted.'</td>';
									// $tables .=  '</tr>';
								}

						}

						$tables .=  '</tr></tbody></table>';
						$tables .=  '</div></div></div>';

					} else {
						foreach ($result as $test_case){
							$row_no++;
							$tables .= '<div class="col"><div class="table-responsive">';
							$tables .=  '<table id="table_queue_'.$row_no.'" class="table table-hover table-bordered"><tbody>';
							$tables .=  '<tr><td>Number</td><td>'.$row_no.'</td></tr>';
							foreach ($test_case as $key=>$item){
								$item_formatted = $item;
								if ($key=='Result' && $item=='Failed') {$item_formatted = '<font color="red">'.$item.'</font>';$failed_count++;}

								$tables .=  '<tr>';
								$tables .=  '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$key.'</td>';
								$tables .=  '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item_formatted.'</td>';
								$tables .=  '</tr>';
							}
							$tables .=  '</tbody></table>';
							$tables .=  '</div></div>';
						}
					}

				}

				$test_case_count = count($result);
				$passed_count =  $test_case_count - $failed_count;
				$passed_percentage = floor(($passed_count / $test_case_count) * 100);
				$failed_percentage = 100 - $passed_percentage;

			?>

			<br><br>
			<div class="panel panel-primary">
				<div class="panel-heading">Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">Web server</div>
								<div class="panel-body">
									<b>Server name:</b> <?= (isset($web_server->server_name)) ? $web_server->server_name : 'N/A' ?> <br />
									<b>Server IP:</b> <?= (isset($web_server->server_ip)) ? $web_server->server_ip : 'N/A' ?> <br />
									<b>Server port:</b> <?= (isset($web_server->server_port)) ? $web_server->server_port : 'N/A' ?> <br />
									<b>Server time:</b> <?= (isset($web_server->server_time)) ? $web_server->server_time : 'N/A' ?> <br />
									<b>Server URL:</b> <?= (isset($web_server->server)) ? $web_server->server : 'N/A' ?> <br />
									<br />
									<b>App server:</b> <?= (isset($web_server->app_server)) ? $web_server->app_server : 'N/A' ?> <br />
									<b>ETL server:</b> <?= (isset($web_server->etl_server)) ? $web_server->etl_server : 'N/A' ?> <br />
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">App server</div>
								<div class="panel-body">
									<b>Server name:</b> <?= (isset($app_server->server_name)) ? $app_server->server_name : 'N/A' ?> <br />
									<b>Server IP:</b> <?= (isset($app_server->server_ip)) ? $app_server->server_ip : 'N/A' ?> <br />
									<b>Server port:</b> <?= (isset($app_server->server_port)) ? $app_server->server_port : 'N/A' ?> <br />
									<b>Server time:</b> <?= (isset($app_server->server_time)) ? $app_server->server_time : 'N/A' ?> <br />
									<b>Server URL:</b> <?= (isset($app_server->server)) ? $app_server->server : 'N/A' ?> <br />
									<br />
									<b>Database TNS:</b> <?= (isset($app_server->db_hostname)) ? $app_server->db_hostname : 'N/A' ?> <br />
									<b>Database user:</b> <?= (isset($app_server->db_username)) ? $app_server->db_username : 'N/A' ?> <br />
									<b>Database name:</b> <?= (isset($app_server->db_database)) ? $app_server->db_database : 'N/A' ?> <br />
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-info">
								<div class="panel-heading">ETL server</div>
								<div class="panel-body">
									<b>Server name:</b> <?= (isset($etl_server->server_name)) ? $etl_server->server_name : 'N/A' ?> <br />
									<b>Server IP:</b> <?= (isset($etl_server->server_ip)) ? $etl_server->server_ip : 'N/A' ?> <br />
									<b>Server port:</b> <?= (isset($etl_server->server_port)) ? $etl_server->server_port : 'N/A' ?> <br />
									<b>Server time:</b> <?= (isset($etl_server->server_time)) ? $etl_server->server_time : 'N/A' ?> <br />
									<b>Server URL:</b> <?= (isset($etl_server->server)) ? $etl_server->server : 'N/A' ?> <br />
									<br />
									<b>Database TNS:</b> <?= (isset($etl_server->db_hostname)) ? $etl_server->db_hostname : 'N/A' ?> <br />
									<b>Database user:</b> <?= (isset($etl_server->db_username)) ? $etl_server->db_username : 'N/A' ?> <br />
									<b>Database name:</b> <?= (isset($etl_server->db_database)) ? $etl_server->db_database : 'N/A' ?> <br />
								</div>
							</div>
						</div>
					</div>
					<p>
						Total number of test cases: <?=$test_case_count;?>
						<br/>
						Total Passed: <span class="badge" style="background-color: #5cb85c;"><?=$passed_percentage?>% (<?=$passed_count;?> of <?=$test_case_count;?>)</span>
						</br>
						Total Failed: <span class="badge" style="background-color: #d9534f;"><?=$failed_percentage?>% (<?=$failed_count;?> of <?=$test_case_count;?>)</span>
						<div class="progress">
						  <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?=$passed_percentage;?>%">
							<!--Passed <?=$passed_percentage;?>% ( <?=$passed_count;?> of <?=$test_case_count;?> )-->
						  </div>
						  <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?=$failed_percentage;?>%">
							<!--Failed <?=$failed_percentage;?>% ( <?=$failed_count;?> of <?=$test_case_count;?> )-->
						  </div>
						</div>
						Show:
						<select class="form-control" id="show-options">
							<option value="1">All</option>
							<option value="2">Passed</option>
							<option value="3">Failed</option>
						</select>
						<a id="export" class="btn btn-default">Download CSV</a>
					</p>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">Details</div>
				<div class="panel-body">
					<?=$tables;?>
				</div>
			</div>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		
		<script>
			$(document).ready(function(){
				function exportTableToCSV($table, filename) {

				var $rows = $table.find('tr:has(td),tr:has(th)'),

				  // Temporary delimiter characters unlikely to be typed by keyboard
				  // This is to avoid accidentally splitting the actual contents
				  tmpColDelim = String.fromCharCode(11), // vertical tab character
				  tmpRowDelim = String.fromCharCode(0), // null character

				  // actual delimiter characters for CSV format
				  colDelim = '","',
				  rowDelim = '"\r\n"',

				  // Grab text from table into CSV formatted string
				  csv = '"' + $rows.map(function(i, row) {
					//console.log($(row).css('display'));
					if( $(row).css('display') == 'none'){
						return;
					}
					var $row = $(row),
					  $cols = $row.find('td,th');

					return $cols.map(function(j, col) {
					  var $col = $(col),
						text = $col.text();

					  return text.replace(/"/g, '""'); // escape double quotes

					}).get().join(tmpColDelim);

				  }).get().join(tmpRowDelim)
				  .split(tmpRowDelim).join(rowDelim)
				  .split(tmpColDelim).join(colDelim) + '"';

				// Deliberate 'false', see comment below
				if (false && window.navigator.msSaveBlob) {

				  var blob = new Blob([decodeURIComponent(csv)], {
					type: 'text/csv;charset=utf8'
				  });

				  // Crashes in IE 10, IE 11 and Microsoft Edge
				  // See MS Edge Issue #10396033
				  // Hence, the deliberate 'false'
				  // This is here just for completeness
				  // Remove the 'false' at your own risk
				  window.navigator.msSaveBlob(blob, filename);

				} else if (window.Blob && window.URL) {
				  // HTML5 Blob        
				  var blob = new Blob([csv], {
					type: 'text/csv;charset=utf-8'
				  });
				  var csvUrl = URL.createObjectURL(blob);

				  $(this)
					.attr({
					  'download': filename,
					  'href': csvUrl
					});
				} else {
				  // Data URI
				  var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

				  $(this)
					.attr({
					  'download': filename,
					  'href': csvData,
					  'target': '_blank'
					});
				}
			  }

			  // This must be a hyperlink
			  $("#export").on('click', function(event) {
				// CSV
				var num = $("#show-options").val();
				var stat = '';
				switch(num){
					case 2:
						stat = '_passed';
						break;
					case 3:
						stat = '_failed';
						break;
				}
				console.log('export');
				var args = [$('#table_queue_0'), 'database_checking_result' + stat + '.csv'];

				exportTableToCSV.apply(this, args);

				// If CSV, don't do event.preventDefault() or return false
				// We actually need this to be a typical hyperlink
			  });
				$("#show-options").on('change', function(){
					console.log($(this).val());
					var num = Number($(this).val());
					switch(num){
						case 1:
							$('td').parent().show();
							break;
						case 2:
							$('td:contains("Passed")').parent().show();
							$('td:contains("Failed")').parent().hide();
							break;
						case 3:
							$('td:contains("Passed")').parent().hide();
							$('td:contains("Failed")').parent().show();
							break;
					}
				});
			});
		</script>
	</body>
</html>
