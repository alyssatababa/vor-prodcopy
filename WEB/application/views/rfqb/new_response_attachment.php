<style>
textarea.form-control 
{
		resize: vertical;
		height: 100px;
		overflow-y: scroll;
}
</style>
<div class="modal-header">
	<h4 class="modal-title">New Attachment</h4>
</div>


<div class="modal-body">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div>
						Upload File Attachment: 
						<label class="btn btn-default pull"><input type="file" name="upload_attachment" id="upload_attachment" style="display: none;" accept="<?=$types?>">Browse...</label>

				</div>
			</div>
	
		</div>
			
	</div>
</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn btn-default btn-xs btn_min_width" onclick="add_new_quote(<?=$row?>, <?=$col?>)">OK</button>
	<button type="button" data-dismiss="modal" class="btn btn-default btn-xs btn_min_width">Close</button>
</div>