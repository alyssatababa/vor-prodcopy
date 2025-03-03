<td width="380">
		    <label for="">Vendor Code:</label>
		    <?php if($this->session->userdata('position_id') == 1): ?>
		    <select class="form-control pull-right FilterNumeric" id="vend_code_field" placeholder="<?php echo (!$vendor_code)? '' : $vendor_code; ?>"  style="position:relative">
		    	<option value="" selected="selected">Select vendor code</option>
		    </select>
			<?php else: ?>
				<input type="text" class="form-control pull-right" id="" placeholder="<?php echo (!$vendor_code)? '' : $vendor_code; ?>" disabled style="position:relative;left:-11px;">
			<?php endif; ?>	
		</td>
		<td></td>
		<td>
			<label class="radio-inline date-radio">
			  <input type="radio" name="date_option" data-date-filter="m-y" value="1" checked class="header-dateFilter"> Month & Year
			</label>
			<label class="radio-inline date-radio">
			  <input type="radio" name="date_option" data-date-filter="mtd" value="2" class="header-dateFilter"> MTD
			</label>
			<label class="radio-inline date-radio">
			  <input type="radio" name="date_option" data-date-filter="from-to" value="3" class="header-dateFilter"> From-To
			</label>
		</td>