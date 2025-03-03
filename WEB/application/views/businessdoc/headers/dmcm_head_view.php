<form class="form-inline" id="reports_headFilter">
<input type="hidden" value="<?php echo $type; ?>" name="bDocType" id="bDoc_type" />
<div id="external_filter_container"></div>
<table class="table tb-nbor">
	<tr>
	  	<?php $this->load->view('businessdoc/headers/venl_datel_view');?>
		<td>
			<label class="radio-inline">
			  <button type="button" class="btn  btn-primary" id="headFilter_submit" >Search</button>
			</label>
			<label class="radio-inline" id="reset-all-table-filter">
			  <button type="button" class="btn  btn-warning" id="headFilter_clear">Clear</button>
			</label>
		</td>
	</tr>
	<tr>
		<td>
		    <label for="">Company Name:</label>
		    <select id="filter_cName" data-doc="3" class="select2-field"></select>
		</td>
		<td><label>Processing Date</label></td>
		<?php $this->load->view('businessdoc/headers/venf_datef_view');?>
		<td></td>
	</tr>
	<tr>
		<td>
			<label for="">Store Name:</label>
		    <select id="filter_stName" class="select2-field"></select>
		</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td> 
			<label for="">Document Number:</label>
		    <div id="filter_docNo" class="yad-filters FilterNumeric"></div>
		</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>	
	<tr>
		<td> 
			<label for="">Document Type:</label>
		    <div id="filter_dType" class="yad-filters"></div>
		</td>
		<td></td>
		<td></td>
		<td></td>
	</tr>		
</table>
</form>	