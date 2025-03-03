<form class="form-inline" id="reports_headFilter">
<input type="hidden" value="<?php echo $type; ?>" name="bDocType" id="bDoc_type" />
<div id="external_filter_container"></div>
<table class="table tb-nbor">
	<tr>
	  	<?php $this->load->view('businessdoc/headers/venl_datel_view');?>
	  	<td class="">
			<label>PO Status
				<div id="filter_postatus"></div>
			</label>
		</td>
	</tr>
	<tr>
		<td>
		    <label for="">Company Name:</label>
		    <select id="filter_cName" data-doc="1" class="select2-field"></select>
		</td>
		<td><label>Post Date:</label></td>
		<?php $this->load->view('businessdoc/headers/venf_datef_view');?>
		<td class="arch-disable">
			<label>Status
				<div id="filter_status"></div>
			</label>
		</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<label for="">Location:</label>
		    <select id="filter_location" class="select2-field"></select>
		</td>
		<td>
			<label>Expected Receipt Date:</label>
		</td>
		<td class="payment-date">
			<input type="text" id="" name="erd-from" class="form-control date-checker date-from" />
			<input type="text" id="" name="erd-to" class="form-control date-checker date-to" />
		</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td> 
			<label for="">Department Name:</label>
		    <select id="filter_deptname" class="select2-field"></select>
		</td>
		<td>
			<label>Cancel Date:</label>
		</td>
		<td class="payment-date">
			<input type="text" id="" name="cdate-from" class="form-control date-checker date-from" />
			<input type="text" id="" name="cdate-to" class="form-control date-checker date-to" />
		</td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td> 
			<label for="">PO Number:</label>
		    <div id="filter_ponumber" class="yad-filters FilterNumeric"></div>
		</td>
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