<td >
	<div class="head-date" data-date-filter="m-y">
  		<select name="hMonth" class="form-control m-yFilter" data-option="m">
		  <option value="">--Month--</option>
		  <?php foreach($months as $m):?>
		  	<option value="<?php echo $m->MONTH; ?>"><?php echo DateTime::createFromFormat('!m', $m->MONTH)->format('F');?></option>
		  <?php endforeach; ?>

		</select>
		<select name="hYear" class="form-control m-yFilter" data-option="y" style="margin-left:20px;">
		  	<option value="">--Year--</option>
			<?php foreach($years as $y): ?>
				<option value="<?php echo $y->YEAR; ?>"><?php echo $y->YEAR; ?></option>
			<?php endforeach;?>
		</select>
	</div>
	<div class="head-date hide" data-date-filter="mtd">
		<span id="mtd-from"></span>
		<span id="mtd-to"></span>
	</div>	
	<div class="head-date hide" data-date-filter="from-to">
		<input type="text" id="dFrom-bd" name="hFrom" class="form-control date-checker date-from">
		<input type="text" id="dTo-bd"  name="hTo" class="form-control date-checker date-to">
	</div>
</td>