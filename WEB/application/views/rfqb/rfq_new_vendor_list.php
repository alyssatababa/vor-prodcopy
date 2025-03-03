<div class="modal-header">
	<h4 class="modal-title"><?=$header?></h4>
</div>
<div class="modal-body">
	<div class="container">
		<?=br(1);?>
		<div class="row">
			<div class="col-sm-12">
			    <div class="row">
			    	<div class="col-sm-2">
			    		Vendor List Name:    
			       	</div>
			    	<div class="col-md-3">
			    		<input type="text" class="form-control" id="txt_input_vendor_list_name" name="txt_input_vendor_list_name" value="<?=$vendor_list_name?>">    
			       	</div>
			    </div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-sm-12">
			    <div class="row">
			    	<div class="col-md-10" id="modal_table_vendor_list">
			    		<?=$table?>    
			       	</div>
			    </div>
			</div>
		</div>
	</div>
</div>