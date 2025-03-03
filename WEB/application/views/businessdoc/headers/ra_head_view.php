<form class="form-inline" id="reports_headFilter">
<input type="hidden" value="<?php echo $type; ?>" name="bDocType" id="bDoc_type" />
<div id="external_filter_container"></div>
<table class="table tb-nbor">
	<tr>
	  	<?php $this->load->view('businessdoc/headers/venl_datel_view');?>
		<td class="arch-disable">
			<label>Status
				<div id="filter_status"></div>
			</label>
		<!-- 	<label class="radio-inline">
			  <input type="radio" name="status" id="" value="2" checked> All
			</label>
			<label class="radio-inline">
			  <input type="radio" name="status" id="" value="0"> Unread
			</label>
			<label class="radio-inline">
			  <input type="radio" name="status" id="" value="1"> Read
			</label> -->
		</td>
	</tr>
	<tr>
		<td>
		    <label for="">RA Number:</label>
		    <div id="filter_raCode" class="FilterNumeric"></div>
		    <!-- <input type="text" class="form-control" name="voucher_code" placeholder=""> -->
		</td>
		<td><label>Processing Date</label></td>
		<?php $this->load->view('businessdoc/headers/venf_datef_view');?>
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<label>Payment Date</label>
		</td>
		<td class="payment-date">
			<input type="text" id="pdFrom-bd" name="pdFrom" class="form-control date-checker date-from" />
			<input type="text" id="pdTo-bd" name="pdTo" class="form-control date-checker date-to" />
		</td>
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