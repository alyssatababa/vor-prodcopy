<html>
	<head>
		<link href="<?=base_url().'assets/dist/css/bootstrap.min.css'?>" rel="stylesheet">
		<link href="<?=base_url().'assets/dist/css/style.css'?>" rel="stylesheet">
		<script src="<?=base_url().'assets/js/jquery-3.2.1.min.js'?>"></script>
		<script src="<?=base_url();?>assets/dist/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container-fluid">


			<?Php
				$tables = '<div class="row"><div class="col-md-12"><div class="table-responsive table-striped">';
				$tables .= '<table>';
				$tables .=  '<table id="table_hashes" class="table table-hover table-striped"><thead>';
				$tables .=  '<tr>';
				// $tables .=  '<th>Full Path</th>';
				$tables .=  '<th>Server</th>';
				$tables .=  '<th>Directory</th>';
				$tables .=  '<th>Path</th>';
				// $tables .=  '<th>Local Path</th>';
				// $tables .=  '<th>Prod Path</th>';
				// $tables .=  '<th>Dev Path</th>';
				$tables .=  '<th>Local Hash</th>';
				$tables .=  '<th>Prod Hash</th>';
				$tables .=  '<th>Dev Hash</th>';
				$tables .=  '<th>QA Hash</th>';
				$tables .=  '</tr></thead><tbody>';
				$tables_errors =  $tables;

				if ($merged && count($merged)>0) {
					$row_no = 0;
					// echo json_encode($merged,JSON_PRETTY_PRINT);
					foreach ($merged as $sev_key => $serv_item) {
						$serv = $merged[$sev_key];
						$serv_prod = $prod[$sev_key];
						$serv_local = $local[$sev_key];
						$serv_dev = $dev[$sev_key];
						$serv_qa = $qa[$sev_key];

						foreach ($serv as $key => $item) {
							$prod_item = $serv_prod[$key];
							$local_item = $serv_local[$key];
							$dev_item = $serv_dev[$key];
							$qa_item = $serv_qa[$key];
							// echo $key;
							if (is_array($item) && count($item)>0) {
								foreach ($item as $item_key => $item_array) {
									if (count($item_array)>0) {
										$item_content = $item_array[0];
										$local_item_array = (isset($local_item[$item_key]) ? $local_item[$item_key] : null);
										$prod_item_array = (isset($prod_item[$item_key]) ? $prod_item[$item_key] : null);
										$dev_item_array = (isset($dev_item[$item_key]) ? $dev_item[$item_key] : null);
										$qa_item_array = (isset($qa_item[$item_key]) ? $qa_item[$item_key] : null);
	
										$local_item_content = (count($local_item_array)>0 ? $local_item_array[0] : null);
										$prod_item_content = (count($prod_item_array)>0 ? $prod_item_array[0] : null);
										$dev_item_content = (count($dev_item_array)>0 ? $dev_item_array[0] : null);
										$qa_item_content = (count($qa_item_array)>0 ? $qa_item_array[0] : null);
	
										// var_dump($local_item_content);
										
										$local_hash = (isset($local_item_content) ? $local_item_content['hash'] : ' ');
										$prod_hash = (isset($prod_item_content) ? $prod_item_content['hash'] : '');
										$dev_hash = (isset($dev_item_content) ? $dev_item_content['hash'] : '');
										$qa_hash = (isset($qa_item_content) ? $qa_item_content['hash'] : '');

										
										$local_file = (isset($local_item_content) ? $local_item_content['clean_path'] : ' ');
										$prod_file = (isset($prod_item_content) ? $prod_item_content['clean_path'] : '');
										$dev_file = (isset($dev_item_content) ? $dev_item_content['clean_path'] : '');
										$qa_file = (isset($qa_item_content) ? $qa_item_content['clean_path'] : '');
										
										$warning_class = '';
										// echo ($local_hash!==$dev_hash);
										if ($local_hash!==$dev_hash || $local_hash!==$prod_hash|| $local_hash!==$qa_hash || $dev_hash!==$prod_hash || $dev_hash!==$qa_hash|| $prod_hash!==$qa_hash) {
											$warning_class = 'danger';	
											$tables_errors .=  '<tr>';
											$tables_errors .= '<td>'.$sev_key.'</td>';
											$tables_errors .= '<td>'.$key.'</td>';
											$tables_errors .= '<td>'.$item_content['clean_path'].'</td>';
											$tables_errors .= '<td>'.$local_hash.'</td>';
											$tables_errors .= '<td>'.$prod_hash.'</td>';
											$tables_errors .= '<td>'.$dev_hash.'</td>';
											$tables_errors .= '<td>'.$qa_hash.'</td>';
											$tables_errors .=  '</tr>';
										}
										$tables .=  '<tr class="'.$warning_class.'">';
										// $tables .= '<td>'.$item_content['full_path'].'</td>';
										$tables .= '<td>'.$sev_key.'</td>';
										$tables .= '<td>'.$key.'</td>';
										$tables .= '<td>'.$item_content['clean_path'].'</td>';
										// $tables .= '<td>'.$local_file.'</td>';
										// $tables .= '<td>'.$prod_file.'</td>';
										// $tables .= '<td>'.$dev_file.'</td>';
										$tables .= '<td>'.$local_hash.'</td>';
										$tables .= '<td>'.$prod_hash.'</td>';
										$tables .= '<td>'.$dev_hash.'</td>';
										$tables .= '<td>'.$qa_hash.'</td>';
										$tables .=  '</tr>';
									}
								}
							}
						}
					}
				}
				
				$tables .=  '</tbody></table>';
				$tables .=  '</div></div></div>';
				$tables .= '';
				$tables_errors .=  '</tbody></table>';
				$tables_errors .=  '</div></div></div>';


				// $test_case_count = count($result);
				// $passed_count =  $test_case_count - $failed_count;
				// $passed_percentage = floor(($passed_count / $test_case_count) * 100);
				// $failed_percentage = 100 - $passed_percentage;

			?>

			<div class="panel panel-default">
				<div class="panel-heading"><a data-toggle="collapse" href="#collapse1">Current Verion Info: All (click to collapse/uncollapse)</a></div>
				<div class="panel-collapse collapse"  id="collapse1">
					<?=$tables;?>
				</div>
				<div class="panel-footer">
					<a href="<?=base_url("index.php/unit_tests/version_hash/export_data/0")?>" target="_blank" class="btn btn-primary">Download CSV</a>
				</div>
			</div>
			
			<div class="panel panel-danger">
				<div class="panel-heading"><a data-toggle="collapse" href="#collapse2">Current Verion Info: Errors Only (click to collapse/uncollapse)</a></div>
				<div class="panel-collapse collapse" id="collapse2">
					<?=$tables_errors;?>
				</div>
				<div class="panel-footer">
					<a href="<?=base_url("index.php/unit_tests/version_hash/export_data/1")?>" target="_blank" class="btn btn-primary">Download CSV</a>
				</div>
			</div>
			
		</div>
	</body>
</html>
