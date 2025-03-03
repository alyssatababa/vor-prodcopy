<!DOCTYPE html>
<html>
	<head>
		
		<p> ASDFASDFASK</p>

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
				font-size: 11px;

			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
		</style>
	</head>

	<?Php $results = json_decode($result); ?>

	<body>
		<!-- <h1><?=$title?></h1> -->
	
		<?Php 
			
			//echo json_decode(json_encode($result), true);
			if ($title == "EXPIRED INVITES"){
				
				$pagerow = 28;
				$pagenum = round(count($results) / $pagerow);
				if ($pagenum == 0)
					$pagenum = 1;

				for ($i=0; $i<$pagenum; $i++){
					echo '<h1>'.$title.'</h1>';

					$j = ($i * $pagerow) + $pagerow;
					echo '<table>'; 
						echo '<tr>';
							echo '<th style="width: 7%"></th>';
							echo '<th style="width: 30%";>VENDOR NAME</th>';
							echo '<th>CATEGORY NAME</th>';
							echo '<th style="width: 15%">INVITER</th>';
							echo '<th style="width: 15%">DATE EXPIRED</th>';		
						echo '</tr>';
					foreach ($results as $row) {
						
						//echo "rownum" . $row->ROWNUM;
						//echo "j" . $j;
						if ($row->ROWNUM <= $j && $row->ROWNUM > $k){
						    echo '<tr>';
						    	echo '<td>'. $row->ROWNUM .'</td>';
						    	echo '<td>'. $row->VENDOR_NAME .'</td>';
						  		echo '<td>'. $row->CATEGORY_NAME .'</td>';
						    	echo '<td>'. $row->INVITER .'</td>';
						    	echo '<td>'. $row->DATE_EXPIRED .'</td>';
						 	echo '</tr>';
						}
					} 
					
					
					echo '</table>';
					//echo '<br>';
					
					//$page = $i+1;
					//echo '<div align="center"><h3><strong>'.$page.' of '.$pagenum.'</strong></h3></span>';
					$k = $j;
				}

			}else if ($title == "DEACTIVATED ACCOUNTS"){
				

				$pagerow = 20;
				$pagenum = round(count($results) / $pagerow);
				if ($pagenum == 0)
					$pagenum = 1;

				for ($i=0; $i<$pagenum; $i++){
					echo '<h1>'.$title.'</h1>';
					$j = ($i * $pagerow) + $pagerow;
					echo '<table>'; 
						echo '<tr>';
							echo '<th></th>';
							echo '<th>VENDOR NAME</th>';
							echo '<th>VENDOR CODE</th>';
							echo '<th>CREATE DATE</th>';
							echo '<th>INVITER</th>';
							echo '<th>DEACTIVATION DATE</th>';
							echo '<th>REASON</th>';
						echo '</tr>';
					foreach ($results as $row) {
						
						//echo "rownum" . $row->ROWNUM;
						//echo "j" . $j;
						if ($row->ROWNUM <= $j && $row->ROWNUM > $k){
						    echo '<tr>';
						    	echo '<td>'. $row->ROWNUM .'</td>';
						    	echo '<td>'. $row->VENDOR_NAME .'</td>';
						    	echo '<td>'. $row->VENDOR_CODE .'</td>';
						    	echo '<td>'. $row->CREATE_DATE .'</td>';
						    	echo '<td>'. $row->INVITER .'</td>';
						    	echo '<td>'. $row->DEACTIVATION_DATE .'</td>';
						    	echo '<td>'. $row->REASON .'</td>';
						 	echo '</tr>';
						}
					} 
					echo '</table>';
					//echo '<br>';
					$k = $j;
				}
			}
		?>	
	</body>
</html>
