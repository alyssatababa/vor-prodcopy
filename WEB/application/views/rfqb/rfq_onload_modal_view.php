<div class="modal-header">
	<h4 class="modal-title">Create RFQ/RFB</h4>
</div>
<div class="modal-body">
	<div class="container">
		<?=br(1);?>
		<div class="row">
			<div class="col-sm-12">
				<input type="hidden" name="rfq_rfq_id" id="rfq_rfq_id" value="0">
				<input type="hidden" name="rfq_main_hidden_selected" id="rfq_main_hidden_selected" value="1">
				<div class="row">	
			    	<div class="col-md-5">
			        	<input type="radio" name="rfq_main" id="rfq_main1" value="new_rfq" checked="true" onchange="select_rfq_main(1)"> New RFQ/RFB
			    	</div>
			    </div>
			    <div class="row">
			    	<div class="col-md-5">
			    		<table style="width:100%;">
							<tr>
								<td style="padding-right: 50px;"><input type="radio" name="rfq_main" id="rfq_main2" value="new_rfq" onchange="select_rfq_main(0)"> Copy From</td>
								<td>
									<div class="input-group">
										<input type="text" class="form-control auto_suggest" append-list-to="myModal2" list-container="rfq_list" oninput="change_border(this.id);" id="find_rfq" name="find_rfq" width="100%">
										<div class="input-group-btn">
											<button tabindex="-1" class="btn btn-default autocomplete-toggle" type="button" input-toggle="find_rfq" >
												<span class="glyphicon glyphicon-search"></span>
											</button>
										</div>
									</div>
									
								</td>
							</tr>
						</table>
				        
			       	</div>
			    </div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>
			        	
			       	</label>
			    </div>
			</div>
		</div>
	</div>
</div>

<script>
function select_rfq_main(type)
{
	if(type == 0)
	{
		$('#rfq_main_hidden_selected').val(0);
        $('#find_rfq').addClass('field-required');
	}
	else
	{
		$('#rfq_main_hidden_selected').val(1);
        $('#find_rfq').removeClass('field-required');
		
	}
}
</script>