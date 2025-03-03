<style>
textarea.form-control 
{
		resize: vertical;
		height: 100px;
		overflow-y: scroll;
}
</style>

<div class="modal-header">
	<h4 class="modal-title"><?php echo $categoryname?></h4>
	<h4 class="modal-title">Add Another Quote</h4>
</div>
<input type="hidden" name="row" id="row" value="<?=$row?>">
<div class="modal-body">
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12 pull-left">
					Quote
				</div>
			</div>
			<div class="row">
				<div class="col-sm-5">
					<input type="text" id="modal_txt_price" class="form-control numeric-decimal" oninput="change_border(this.id)" name="modal_txt_price" value="">
				</div>
			</div><br>
			<div class="row">
				<div class="col-xs-2">
					Delivery Date Time
				</div>
				<div class="col-sm-4 pull-left">
					<?=form_dropdown('delivery_time', $lead_time, '', 'id="delivery_time" onchange="change_border(this.id)" class="btn btn-default dropdown-toggle" style="width: 130px;"')?>
					<!-- <input type="date" name="modal_date_deliverytimelead" class="form-control" style="width: 150px;" id="modal_date_deliverytimelead"> -->
				</div>
			</div><?=br(3)?>
			<input id="<?='btn_view_modal_'.$line_attachment_id.'_'.$quote_num;?>" type="button" data-toggle="modal" data-target="#<?='view_bom_modal_'.$line_attachment_id.'_'.$quote_num;?>" class="btn btn-default" style="display: none" value="BOM"></input>
			<div class="row">
				<div class="col-md-12">
					Counter Offer (Must be different from existing quote) 
					<span id="modal_counter_offer_char_num"></span>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4">
					<textarea id="modal_counter_offer" name="modal_counter_offer" class="form-control counter_offer_txt_area" style="width: 100%; height:100px; min-height:100px; max-height:250px;" maxlength="200"></textarea>
				</div>
			</div><?=br(2)?>
			<div class="row">
				<div class="col-xs-2">
					Add Attachment (optional)
				</div>
				<div class="col-xs-2">
					<label class="btn btn-default"><input type="file" name="upload_attachment_new" id="upload_attachment_new" style="display: none;" accept="<?=$types?>">Browse...</label>
				</div>
			</div>
			
			<div class="row">
				<input type="hidden" class="form-control editable" id="modal_hidden_line_attachment_id" name="modal_hidden_line_attachment_id" value="<?=(isset($line_attachment_id) ? $line_attachment_id : '');?>"  />
				<input type="hidden" class="form-control editable" id="modal_hidden_attachment_type" name="modal_hidden_attachment_type" value="<?=(isset($attachment_type) ? $attachment_type : '');?>"  />
				<input type="hidden" class="form-control editable" id="modal_hidden_current_currency_data" name="modal_hidden_current_currency_data" value="<?=(isset($current_currency_data) ? $current_currency_data : '');?>"  />
			</div>
			
		</div>
			
	</div>
</div>
</div>
<script>
	jQuery('.numeric').keyup(function () { 
	this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	$(document).on('input', '.counter_offer_txt_area', function(){
		//console.log($(this).attr('id'));
		  var max = 200;
		  var len = $(this).val().length;
		  var specs_id = $(this).attr('id');
		  if (len >= max){
		    $('#' + specs_id +'_char_num').text('(You have reached the limit.)');
		  }else{
		    var char = max - len;
		    $('#' + specs_id +'_char_num').text('(' + char + ' characters left)');
		  }
	});
</script>