<style>
	input[type=number]::-webkit-inner-spin-button, 
	input[type=number]::-webkit-outer-spin-button { 
	  -webkit-appearance: none; 
	  margin: 0; 
	}
</style>

<div class="modal fade" id="view_bom_file_modal_<?=$line_attachment_id?>" tabindex="-1" role="dialog" aria-labelledby="myViewModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title">BOM Template</h3>
			</div>
			<div class="modal-body">
				
				<?= isset($message) ? '<div class="alert alert-info">'.$message.'</div>' : ''; ?>
				<div class="table-responsive">
					<table id="table_queue" class="table table-hover table-bordered">
						<?Php if ($bom_file_lines && count($bom_file_lines)>0) {
							$brow = 0; 

							foreach ($bom_file_lines as $bom_row){
								if ($brow==0) {
									echo '<thead>';
									foreach ($bom_row as $bkey=>$bval){
												echo '<th>'.$bkey.'</th>';
									}								
									echo '</thead>';
								} else if ($brow==1) {
									echo '<tbody>';
								}
								echo '<tr>';
								foreach ($bom_row as $col=>$item){
									echo '<td style="width:100px; word-wrap:break-word; white-space: pre-wrap">'.$item;
									echo '</td>';
								}								
								echo '</tr>';
								$brow++;
							}
							echo '</tbody>';
						}
					?>
					</table>
				</div>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn btn-default" data-dismiss="modal" value="Close" />
			</div>
		</div>
	</div>
</div>