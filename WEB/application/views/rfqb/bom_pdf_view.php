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
		<script>
			function computeTotal(line_attachment_id){
				var items = document.getElementsByName("line_bom_cost_" + line_attachment_id.toString());
				var sum = 0;
				var sum_box = document.getElementById("line_total_" + line_attachment_id.toString());
				if (items!=null) {
					for (i = 0; i < items.length; i++) {
						if (items[i].type == "number") {
							sum += parseInt(items[i].value);
						}
					}
					sum_box.value = sum;
				}
			}

			function generate_pdf(line_attachment_id)
			{
				document.form1.action = "<?=base_url();?>index.php/rfqb/rfq_short_list/generate_pdf/" + line_attachment_id.toString();
				document.form1.target = "_self";
				document.form1.submit();
			}
		</script>
	</head>


	<body>
		<h1>BOM COMPARISON</h1>
		<h2><?=$title?></h2>
		<?Php $totals = 0; $headers = 0; ?>
		<table id="table_bom_pdf">
			<?Php if ($bom_lines && count($bom_lines)>0) {
				$brow = 0;
				$cost_sums = array();
				$cost_sums['TOTAL'] = "<b>TOTAL</b>";

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
						} else {
							echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item;
							echo '</td>';
						}

						if ($col!='PRODUCT' && $col!='QUANTITY' && $col!='ROW_NO' && $col!='COST' && $col!='DESCRIPTION' && strpos($col,' - REMARKS')<=0) {
							$cost_sums[$col] += $item;
						}
					}
					echo '</tr>';
					$brow++;
				}
			}
		?>

			<tr>
				<td style="width:100px; " colspan="<?=$headers-sizeof($cost_sums);?>"></td>
			<?Php foreach ($cost_sums as $col=>$item){ ?>
					<td style="width:100px; ">
						<u><?=(strpos($col,' - REMARKS')<=0) ?  $item : '';?></u>
					</td>
			<?Php } ?>
			</tr>
			</tbody>
		</table>
	</body>
</html>
