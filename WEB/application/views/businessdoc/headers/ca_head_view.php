<form class="form-inline" id="reports_headFilter">
<input type="hidden" value="<?php echo $type; ?>" name="bDocType" id="bDoc_type" />
<div id="external_filter_container"></div>
<table class="table tb-nbor">
	<tr>
	  	<?php $this->load->view('businessdoc/headers/venl_datel_view');?>
		<td class="arch-disable">
			<label>Status</label>
			<div id="filter_status"></div>
		</td>
	</tr>
	<tr>
		<td >
		    <label for="">Payment Voucher Number:</label>
		    <div id="filter_vCode" class="FilterNumeric yad-filters"></div>
		</td>
		<td><label>Crediting Date</label></td>
		<?php $this->load->view('businessdoc/headers/venf_datef_view');?>
		<td>
		</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>
			<label class="radio-inline">
			  <button type="button" class="btn  btn-primary" id="headFilter_submit" >Search</button>
			</label>
			<label class="radio-inline" id="reset-all-table-filter">
			  <button type="button" class="btn  btn-warning" id="headFilter_clear">Clear</button>
			</label>
		</td>
	</tr>	
</table>
</form>	