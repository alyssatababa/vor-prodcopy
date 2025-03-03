<style>
	input[type=number]::-webkit-inner-spin-button,
	input[type=number]::-webkit-outer-spin-button {
	  -webkit-appearance: none;
	  margin: 0;
	}
</style>
<div class="modal fade" id="view_bom_modal_<?=$line_attachment_id?>_<?=$quote_no?>" tabindex="-1" role="dialog" aria-labelledby="myViewModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:1051;">
	<div class="modal-dialog modal-lg" >
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">BOM Template</h3>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
				<?Php $totals = 0; $headers = 0; ?>
					<table id="table_queue" class="table table-hover">
						<?Php if ($bom_lines && count($bom_lines)>0) {
							$brow = 0;
							$cost_sums = array();
							$cost_sums['TOTAL'] = "<b>TOTAL</b>";

							foreach ($bom_lines as $bom_row){
								if ($brow==0) {
									echo '<thead>';
									foreach ($bom_row as $bkey=>$bval){
										if ($bkey=='COST') {
											if ($user_type=='VENDOR') {
												echo '<th>'.$bkey.'</th>';
												$headers+=1;
											}
										} else if ($bkey=='ROW_NO') {
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
									if ($col=='COST') {
										if ($user_type=='VENDOR') {
											$totals += $item;
											echo '<td style="min-width:190px;">';
											echo '<div class="col-sm-4">';
											echo '<label for="line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'">'.$current_currency_data.'</label>';
											echo '</div>';
											echo '<div class="col-sm-8">';
											echo '<input type="hidden"  class="form-control numeric-decimal" id="hidden_line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.number_format((isset($item) ? $item : 0), 2, '.', '').'" name="hidden_line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'"  />';
											echo '<input type="text" maxlength="14" class="form-control numeric-decimal" onChange="computeTotal('.$line_attachment_id.','.$quote_no.')" id="line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.number_format((isset($item) ? $item : 0), 2, '.', ',').'" name="line_bom_cost_'.$line_attachment_id.'_q_'.$quote_no.'" style="min-width:120px;" />';
											echo '</div>';

											// echo '<div class="form-horizontal"><div class="form-group">';
											// echo '<label for="line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" class="col-md-4 control-label">'.$current_currency_data.'</label>';
											// echo '<input type="hidden"  class="form-control numeric-decimal" id="hidden_line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.number_format((isset($item) ? $item : 0), 2, '.', '').'" name="hidden_line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'"  />';
											// echo '<div class="col-md-4"><input type="text" maxlength="14" class="form-control numeric-decimal" onChange="computeTotal('.$line_attachment_id.','.$quote_no.')" id="line_bom_cost_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.number_format((isset($item) ? $item : 0), 2, '.', ',').'" name="line_bom_cost_'.$line_attachment_id.'_q_'.$quote_no.'" style="min-width:120px;" /> </div>';
											// echo '</div></div>';
											echo '</td>';
										}
									} else if ($col=='REMARKS') {
										if ($user_type=='VENDOR') {
											echo '<td>';
											echo '<input type="hidden" class="form-control" id="hidden_line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.(isset($item) ? $item : null ).'" name="hidden_line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'"  />';
											echo '<textarea maxlength="300" class="form-control limit-chars" id="line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.(isset($item) ? $item : null).'" name="line_bom_remarks_'.$line_attachment_id.'_q_'.$quote_no.'" style="min-width:190px;" />';
											echo '<div id="line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'_char_num"> </div>';

											// echo '<input type="hidden" class="form-control" id="hidden_line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.(isset($item) ? $item : null ).'" name="hidden_line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'"  />';
											// echo '<div>';
											// echo '<textarea type="text" maxlength="300" class="form-control limit-chars" id="line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.(isset($item) ? $item : null).'" name="line_bom_remarks_'.$line_attachment_id.'_q_'.$quote_no.'" style="min-width:120px;" />';
											// echo '<div id="line_bom_remarks_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'_char_num"> </div>';
											// echo '</div>';
											echo '</td>';
										}
									} else if ($col=='ROW_NO') {
										echo '<input type="hidden" name="line_attachment_row_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" id="line_attachment_row_'.$line_attachment_id.'_'.$brow.'_'.$quote_no.'" value="'.$item.'">';
										// echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item;
										// dont output row no
									} else {
										echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item;
										echo '</td>';
									}

									if ($user_type != 'VENDOR' && $col!='PRODUCT' && $col!='QUANTITY' && $col!='ROW_NO' && $col!='COST' && $col!='DESCRIPTION') {
										$cost_sums[$col] += $item;
									}
								}
								echo '</tr>';
								$brow++;
							}
						}
					?>
					<?Php if ($user_type=='VENDOR') { ?>
							<tr>
								<td colspan="<?=$headers-1;?>">
								</td>
								<td colspan="1">
									<div class="col-sm-4">
										<label for="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" class="col-md-4 control-label">TOTAL: </label>
									</div>
									<div class="col-sm-8">
										<input type="text" class="form-control numeric-decimal" id="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" value="<?=number_format((isset($totals) ? $totals : 0), 2, '.', ',');?>" name="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" style=" min-width:120px;" disabled/>
									</div>


									<!--div class="form-horizontal">
										<div class="form-group">
											<label for="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" class="col-md-4 control-label">TOTAL: </label>
											<div class="col-md-4">
												<input type="text" class="form-control numeric-decimal" id="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" value="<?=number_format((isset($totals) ? $totals : 0), 2, '.', ',');?>" name="line_total_<?=$line_attachment_id?>_<?=$quote_no?>" style="width: 100%; min-width:120px;" disabled/>
											</div>
										</div>
									</div-->

								</td>
							</tr>
					<?Php } else { ?>
							<tr>
								<td style="width:100px; " colspan="<?=$headers-sizeof($cost_sums);?>"></td>
							<?Php if (count($cost_sums)>0) { ?>
								<?Php foreach ($cost_sums as $col=>$item){ ?>
										<td style="width:100px; ">
											<u><?= number_format(((isset($item) && strlen(trim($item)) > 0 )? $item : 0), 2, '.', ',');?></u>
										</td>
								<?Php } ?>
							<?Php } ?>
							</tr>
					<?Php } ?>
						</tbody>
					</table>

				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-primary" style="display:<?=($user_type!='VENDOR'? 'inline' : 'none')?>;" onClick="generate_bom_pdf(<?=$line_attachment_id?>,<?=$quote_no?>,&quot;<?= rawurlencode (isset($title) && isset($rfqrfb_id) ? 'RFQ RFB# '.$rfqrfb_id.' - '.$title : 'Could not get rfq rfb title.'); ?>&quot;);" value = "Download PDF" />
				<input type="button" class="btn btn-primary" style="display:<?=($user_type=='VENDOR'? 'inline' : 'none')?>;" onClick="validateBOM(<?=$line_attachment_id?>,<?=$line_no?>,<?=$quote_no?>);" value = "Submit" />
				<input type="button" class="btn btn-default" data-dismiss="modal" style="display:<?=($user_type!='VENDOR'? 'inline' : 'none')?>;" value = "Close" />
				<input type="button" class="btn btn-default" data-dismiss="modal" style="display:<?=($user_type=='VENDOR'? 'inline' : 'none')?>;" onClick="cancel_bom_changes(<?=$line_attachment_id?>,<?=$quote_no?>);" value = "Close" />
			</div>
		</div>
	</div>
</div>
