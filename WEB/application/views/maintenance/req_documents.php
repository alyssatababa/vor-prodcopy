<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<div class="container mycontainer">
	<div class="row">
		<div class="col-md-10"><h4>Required Documents</h4></div>
		<div class="col-md-2">
			<button type="button" class="btn btn-primary" name="" data-toggle="modal" data-target="#addNewModal">Add New</button>
		</div>
	</div>
<div class = "row">				
	<div class="panel panel-default">
<div class="panel-body">
	<h4>Document Search</h4>
	<div class="form-inline">
		<div class="form-group">
			<div class="col-md-12">
				<label style="margin:0;">Name : </label>
				<input type="text" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button class="btn btn-primary">Search</button>	
		</div>
	</div>
</div>
<hr>
<div id = "bot_body">
<div class="panel-body">
	<label>75 Record(s)</label>
	<table>
		<tr class = "tbl_header">
			<td>#</td>
			<td>Name</td>
			<td>Description</td>
			<td>Date Uploaded</td>
			<td>Bus Division</td>
			<td>Active</td>
			<td>Edit</td>
		</tr>
		<?php 
		if(is_array($param_list)):
		foreach($param_list as $list):?>
		<tr>
			<td><?php echo $list->VENDOR_PARAM_ID;?></td>
			<td><?php echo $list->VENDOR_PARAM_NAME;?></td>
			<td><?php echo $list->DESCRIPTION;?></td>
			<td><?php echo $list->DATE_UPLOADED;?></td>
			<td><?php echo $list->BUS_DIVISION;?></td>
			<td><?php echo ($list->STATUS == 1) ? 'Yes': 'No';?></td>
			<td><button class = "btn btn-primary">Edit</button></td>
		</tr>
		<?php 
			endforeach; 
			else: 
		?>
		<tr>
			<td colspan="7"><?php echo $param_list; ?></td>
		</tr>
		<?php endif; ?>
		
	</table>
	<center>
	<nav aria-label="Page navigation">
	  <ul class="pagination">
	    <li>
	      <a href="#" aria-label="Previous">
	        <span aria-hidden="true">&laquo;</span>
	      </a>
	    </li>
	    <li class = "active"><a href="#">1</a></li>
	    <li><a href="#">2</a></li>
	    <li><a href="#">3</a></li>
	    <li>
	      <a href="#" aria-label="Next">
	        <span aria-hidden="true">&raquo;</span>
	      </a>
	    </li>
	  </ul>
	</nav>
	</center>
	<div class="row">
		<div class = "col-sm-8"></div>
		<div class = "col-md-2">
			<button class="btn btn-primary">Save Changes</button>
		</div>	
		<div class = "col-md-2">
			<button class="btn btn-primary">Cancel</button>
		</div>	
	</div>
</div>


<br>

</div>
</div>
</div>


</div>


<?php $this->load->view('maintenance/modal_vendor_param');?>
