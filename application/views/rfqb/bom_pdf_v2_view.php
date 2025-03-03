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
			}

			tr:nth-child(even) {
				background-color: #dddddd;
			}
		</style>
	</head>

	<?Php $results = json_decode(json_encode($result), true); ?>

	<body>
		<h1>BOM COMPARISON</h1>
		<h2><?=$title?></h2>
		<?Php for ($quote_i=0;$quote_i<count($results['QUOTES']);$quote_i++){ ?>
			<?Php $quote = $results['QUOTES'][$quote_i]['QUOTE_NO']; ?>
			<?Php  $bom_lines = $results[$quote]; ?>
			<?Php $totals = 0; $headers = 0; ?>
			<p>QUOTE NUM <?=$quote?></p>
			<table id="table_bom_pdf">
				<?Php if ($bom_lines && count($bom_lines)>0) {
					$brow = 0;
					$cost_sums = array();
					// $cost_sums['TOTAL'] = "<b>TOTAL</b>";

					foreach ($bom_lines as $bom_row){
						if ($brow==0) {
							echo '<thead><tr>';
							foreach ($bom_row as $bkey=>$bval){
								if ($bkey=='ROW_NO') {
									// dont output row no
								} else {
									echo '<td>'.$bkey.'</td>';
									$headers+=1;
								}

								if ($bkey!='PRODUCT' && $bkey!='QUANTITY' && $bkey!='DESCRIPTION' && $bkey!='ROW_NO' && $bkey!='COST' && strpos($bkey,' - REMARKS')<=0) {
									$cost_sums[$bkey] = 0;
								}
							}
							echo '</tr></thead>';
						}
						echo '<tr>';
						foreach ($bom_row as $col=>$item){
							if ($col=='ROW_NO') {
								echo '<input type="hidden" name="line_attachment_row_'.$line_attachment_id.'_'.$brow.'" id="line_attachment_row_'.$line_attachment_id.'_'.$brow.'" value="'.$item.'">';
								// echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item;
								// dont output row no
							} else if ($col!='PRODUCT' && $col!='QUANTITY' && $col!='ROW_NO' && $col!='COST' && $col!='DESCRIPTION' && strpos($col,' - REMARKS')<=0) {
								//$cost_sums[$col] += $item;
								if (isset($cost_sums[$col])) {
									$cost_sums[$col] += $item;
								}
								echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.number_format(((isset($item) && strlen(trim($item)) > 0 )? $item : 0), 2, '.', ',').'</td>';
							} else {
								echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item.'</td>';
							}

						}
						echo '</tr>';
						$brow++;
					}
				}
			?>

				<tr>
					<td style="width:100px; text-align:right;" colspan="<?=$headers - (sizeof($cost_sums) * 2);?>"><b>TOTAL <b></td>
					<?Php foreach ($cost_sums as $col=>$item){ ?>
							<td style="width:100px; ">
								<u><?= (strpos($col,' - REMARKS')<=0) ? number_format(((isset($item) && strlen(trim($item)) > 0)? $item : 0), 2, '.', ',') : '';?></u>
							</td>
							<td><u></u></td>
					<?Php } ?>
				</tr>
				<tr>
				</tr>
				</tbody>
			</table>
		<?Php } ?>
	</body>
</html>
