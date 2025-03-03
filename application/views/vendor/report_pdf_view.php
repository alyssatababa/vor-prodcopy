<!DOCTYPE html>
<html>
	<head>

		<style>
			table {
				font-family: arial, sans-serif;
				border-collapse: collapse;
				width: 100%;
			}

			td, th {
				border: 1px solid #dddddd;
				text-align: left;
				padding: 8px;
				font-size: 10px;

			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}

/*			table, tr, td, th, tbody, thead, tfoot {
			     page-break-inside: avoid;
			}*/
		</style>
	</head>

	<!-- <?Php $results //= json_decode($result); ?> -->



	<body>
		<!-- <h1><?=$title?></h1> -->

		<?Php 
			
			//echo json_decode(json_encode($result), true);
			if ($title == "EXPIRED INVITES"){

				
				// $pagerow = 28;
				// $pagenum = round(count($results) / $pagerow);
				// if ($pagenum == 0)
				// 	$pagenum = 1;

				// for ($i=0; $i<$pagenum; $i++){
					echo '<h1>'.$title.'</h1>';

					// $j = ($i * $pagerow) + $pagerow;


					


					$x = 0;


					foreach ($results as $row) {
				    	if ($last_vendor != $row->VENDOR_NAME){

				    		$newrecord[$x]['VENDOR_NAME'] = $row->VENDOR_NAME;
				    		$newrecord[$x]['ROWNUM'] =  $row->ROWNUM;
					  		$newrecord[$x]['CATEGORY_NAME'] =  $row->CATEGORY_NAME;
					    	$newrecord[$x]['INVITER'] =  $row->INVITER;
					    	$newrecord[$x]['DATE_EXPIRED'] =  $row->DATE_EXPIRED;
					    	$x++;
				    	}else{
							$newrecord[$x-1]['CATEGORY_NAME'] = $newrecord[$x-1]['CATEGORY_NAME'] . ", " . $row->CATEGORY_NAME;
				    	}

					 	
					 	$last_vendor = $row->VENDOR_NAME;
					} 

					// var_dump($newrecord);

					echo '<table>'; 
						echo '<tr>';
							echo '<th style="width: 6%"></th>';
							echo '<th style="width: 25%";>VENDOR NAME</th>';
							echo '<th>CATEGORY NAME</th>';
							echo '<th style="width: 23%">INVITER</th>';
							echo '<th style="width: 12%">DATE EXPIRED</th>';		
						echo '</tr>';

					foreach ($newrecord as $key => $row) {
					    echo '<tr>';
				    		echo '<td>'. $row['ROWNUM'] .'</td>';
					    	echo '<td>'. $row['VENDOR_NAME'] .'</td>';
					  		echo '<td>'. $row['CATEGORY_NAME'] .'</td>';
					    	echo '<td>'. $row['INVITER'] .'</td>';
					    	echo '<td>'. $row['DATE_EXPIRED'] .'</td>';

					 	echo '</tr>';
						// var_dump($row);
					} 
					
					echo '</table>';
					
					
					//echo '<br>';
					
					//$page = $i+1;
					//echo '<div align="center"><h3><strong>'.$page.' of '.$pagenum.'</strong></h3></span>';
					// $k = $j;
				// }

			}else if ($title == "DEACTIVATED ACCOUNTS"){
				

				// $pagerow = 16;
				// $pagenum = round(count($results) / $pagerow);
				// if ($pagenum == 0)
				// 	$pagenum = 1;

				// for ($i=0; $i<$pagenum; $i++){
					echo '<h1>'.$title.'</h1>';
					// $j = ($i * $pagerow) + $pagerow;
					echo '<table>'; 
						echo '<tr>';
							echo '<th style="width: 6%"></th>';
							echo '<th >VENDOR NAME</th>';
							echo '<th style="width: 12%">VENDOR CODE</th>';
							echo '<th style="width: 12%">CREATE DATE</th>';
							echo '<th style="width: 22%">INVITER</th>';
							echo '<th style="width: 13%">DEACTIVATION DATE</th>';
							echo '<th style="width: 13%">REASON</th>';
						echo '</tr>';
					foreach ($results as $row) {
						
						//echo "rownum" . $row->ROWNUM;
						//echo "j" . $j;
						// if ($row->ROWNUM <= $j && $row->ROWNUM > $k){
						    echo '<tr>';
						    	echo '<td>'. $row->ROWNUM .'</td>';
						    	echo '<td>'. $row->VENDOR_NAME .'</td>';
						    	echo '<td>'. $row->VENDOR_CODE .'</td>';
						    	echo '<td>'. $row->CREATE_DATE .'</td>';
						    	echo '<td>'. $row->INVITER .'</td>';
						    	echo '<td>'. $row->DEACTIVATION_DATE .'</td>';
						    	echo '<td>'. $row->REASON .'</td>';
						 	echo '</tr>';
						// }
					} 
					echo '</table>';
					//echo '<br>';
					// $k = $j;
				// }
			}
		?>	
	</body>
</html>
