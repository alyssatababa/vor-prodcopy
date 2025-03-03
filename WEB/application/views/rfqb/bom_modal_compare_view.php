<style>
	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
	  -webkit-appearance: none;
	  margin: 0;
	}
</style>
<?Php $lines = json_decode(json_encode($line), true); ?>

<div class="modal fade" id="view_bom_modal_<?=$line_attachment_id?>" tabindex="-1" role="dialog" aria-labelledby="myViewModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:1051;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">BOM Template</h3>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
				<?Php if (isset($line->QUOTES)){ ?>
					<?Php for ($quote_i=0;$quote_i<count($line->QUOTES);$quote_i++){ ?>
						<?Php $quote = $line->QUOTES[$quote_i]->QUOTE_NO; ?>
						<?Php  $bom_lines = $lines['BOM_'.$quote]; ?>
						<?Php $totals = 0; $headers = 0; ?>
							<p>QUOTE NO <?=$quote;?></p>
							<table id="table_queue" class="table table-hover">
								<?Php if ($bom_lines && count($bom_lines)>0) {
									$brow = 0;
									$cost_sums = array();
									// $cost_sums['TOTAL'] = "<b>TOTAL</b>";

									foreach ($bom_lines as $bom_row){
										if ($brow==0) {
											echo '<thead>';
											foreach ($bom_row as $bkey=>$bval){
												if ($bkey=='ROW_NO' || $bkey=='COST') {
													// dont output row no
												} else {
													echo '<th>'.$bkey.'</th>';
													$headers+=1;
												}

												if ($user_type != 'VENDOR' && $bkey!='PRODUCT' && $bkey!='QUANTITY' && $bkey!='DESCRIPTION' && $bkey!='ROW_NO' && $bkey!='COST') {
													$cost_sums[$bkey] = 0;
												}
											}
											echo '</thead>';
										} else if ($brow==1) {
											echo '<tbody>';
										}
										echo '<tr>';
										foreach ($bom_row as $col=>$item){
											if ($col=='ROW_NO' || $col=='COST') {
													// dont output row no
											} else if ($col!='PRODUCT' && $col!='QUANTITY' && $col!='ROW_NO' && $col!='COST' && $col!='DESCRIPTION' && strpos($col,' - REMARKS')<=0) {
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
									<td style="width:100px; text-align:right;" colspan="<?=$headers-sizeof($cost_sums);?>"><b>TOTAL <b></td>
									<?Php foreach ($cost_sums as $col=>$item){ ?>
											<td style="width:100px; ">
												<u><?= (strpos($col,' - REMARKS')<=0) ? number_format(((isset($item) && strlen(trim($item)) > 0)? $item : 0), 2, '.', ',') : '';?></u>
											</td>
									<?Php } ?>
								</tr>
								</tbody>
							</table>
					<?Php } ?>
				<?Php } else { ?>
					<p>No BOM quote available</p>
				<?Php } ?>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" style="display:<?=($user_type!='VENDOR'? 'inline' : 'none')?>;" onClick="generate_bom_pdf_v2(&quot;<?=base_url();?>&quot;,<?=$line_attachment_id?>,&quot;<?= rawurlencode (isset($title) && isset($rfqrfb_id) ? 'RFQ RFB# '.$rfqrfb_id.' - '.$title : 'Could not get rfq rfb title.'); ?>&quot;);" value = "Download PDF" />
				<input type="button" class="btn btn-default" data-dismiss="modal" style="display:<?=($user_type!='VENDOR'? 'inline' : 'none')?>;" value = "Close" />
			</div>
		</div>
	</div>
</div>
