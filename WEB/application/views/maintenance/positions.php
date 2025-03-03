
<!-- active-->


<link href="<?=base_url();?>assets/css/dt.css" rel="stylesheet">
<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/maintenance/position.js"></script>
<div id="bod" data-base-url="<?php echo base_url(); ?>"></div>
<div class="container mycontainer">

<div class="row">
					<div class="col-md-6"><h4>Positions</h4></div>
					<div class="col-md-4">
					</div>
					<div class = "col-sm-2">
		<button class="btn btn-primary" data-toggle="modal" data-target="#pos_add_pos" id = "btn_add_position" >Add New Position</button>
		</div>
					
</div>
			
<div class="panel panel-default">
  <div class="panel-body">
  <h4>Search</h4>
  
  <div class = "row">					
					<form class="form-horizontal">
					
								<div class="form-group">
									<span for="pos_sel_pos" class="col-sm-2"><span class="pull-right"><strong>Search Type :</strong></span></span>
									<div class="col-md-3">	
										
										<select id = "pos_sel_pos" class="form-control" name="color">										
											<option rel = "1">All</option>
											<option rel = "2">Position Code</option>  
											<option rel = "3">Position Name</option>  										
										</select>																
									</div>
									<div class="col-md-3">	
										<input class="form-control input-sm" id="txt_pos_search" type="text">
									</div>
									
									<div class = "col-sm-4">
									<button onclick = "return false;" id  = "spos_btn_search" class = "btn btn-primary btn-half" >Search</button>						
									<button id = "btn_clear" class = "btn btn-primary btn-half" onclick = "return false;">Clear</button>
									</div>									
								</div>
					</form>
		  <div class="panel-body">


 <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>Member List</h4></strong>
                            </div>
                        </div>
<div class="table-responsive">
<table id = "tbl_position" class = "table table-hover">
<thead>
<th class = "m_action">Position ID</th>
<th>Position Code</th>
<th>Position Name</th>
<th id ="tAct" class = "m_action">Action</th>
</thead>
<tbody>

</tbody>
</table>
</div>



<div id="pos_add_pos" class="modal fade" data-backdrop="static" data-keyboard="false">


<div class="modal-dialog">
<div class="modal-content">

        <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick ="clear_add();">&times;</button>			
				<h4>Add New Position</h4>
		</div>
		
		
				<div class="modal-body">
					<div class="row">
					
					<form class="form-horizontal">
																		
														
								<div class="form-group">
									<label for="inputsm" class="col-md-3"><span class="pull-right">Position Name : </span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm" id="inpt_pos_name" type="text">
									</div>
									<div class = "col-sm-3"></div>							
								</div>
								
								<div class="form-group">
									<label for="inputsm" class="col-md-3"><span class="pull-right">Position Code : </span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm" id="inpt_pos_code" type="text">
									</div>
									<div class = "col-sm-3"></div>							
								</div>
								

							<div class="form-group">
							<label class="col-sm-3"><span class="pull-right">User Type : </label>
							<div class="col-sm-6">	
									<select id = "add_sel_type" class="form-control" name="color">
									<?php for($i=0 ; $i < count($user_type_list[ 'data']);$i++){ echo '<option rel = "'.$user_type_list[ 'data'][$i][ 'USER_TYPE_ID']. '">'.$user_type_list[ 'data'][$i][ 'USER_TYPE']. '</option>'; } ?>
									</select>
									</div>
									<div class="col-md-3"></div>
							</div>
							
								<div class="form-group">
								<div class = "col-sm-3"></div>
								<div class = "col-sm-3">
								<button id = "edit_save_pos" class = "btn btn-primary btn-whole" onclick ="return false;">Save Position</button></div>
								<div class = "col-sm-3">
								<button class = "btn btn-primary btn-whole" data-dismiss="modal" onClick ="clear_add();">Cancel</button></div>
																
								
								</div>
								
								
					</form>
				
				
				
				</div>
				</div>
				
		<div class="modal-footer">					
		</div>

</div>
</div>
</div>
		
		
</div>	


<div id="pos_edit_pos" class="modal fade" data-backdrop="static" data-keyboard="false">


<div class="modal-dialog">
<div class="modal-content">

        <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>			
				<h4>Edit Position</h4>
		</div>
		
		
				<div class="modal-body">
					<div class="row">
					
					<form class="form-horizontal">
					
					
								<div class="form-group">
									<label for="inputsm" class="col-md-3"><span class="pull-right" >Position ID : </span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm" id="edit_pos_id" type="text" disabled>
									</div>
									<div class = "col-sm-3"></div>							
								</div>
																															
								<div class="form-group">
									<label for="inputsm" class="col-md-3"><span class="pull-right">Position Name : </span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm" id="edit_pos_name" type="text">
									</div>
									<div class = "col-sm-3"></div>							
								</div>
								
								<div class="form-group">
									<label for="inputsm" class="col-md-3"><span class="pull-right">Position Code : </span></label>
									<div class="col-md-6">	
										<input class="form-control input-sm" id="edit_pos_code" type="text">
									</div>
									<div class = "col-sm-3"></div>							
								</div>
								

							<div class="form-group">
							<label class="col-sm-3"><span class="pull-right">User Type : </label>
							<div class="col-sm-6">	
									<select id = "edit_sel_type" class="form-control" name="color">
									<?php for($i=0 ; $i < count($user_type_list[ 'data']);$i++){ echo '<option rel = "'.$user_type_list[ 'data'][$i][ 'USER_TYPE_ID']. '">'.$user_type_list[ 'data'][$i][ 'USER_TYPE']. '</option>'; } ?>
									</select>
									</div>
									<div class="col-md-3"></div>
							</div>
							
								<div class="form-group">
								<div class = "col-sm-3"></div>
								<div class = "col-sm-3">
								<button id = "save_pos" class = "btn btn-primary btn-whole" onclick ="return false;">Save Position</button></div>
								<div class = "col-sm-3">
								<button class = "btn btn-primary btn-whole" data-dismiss="modal">Cancel</button></div>
																
								
								</div>
								
								
					</form>
				
				
				
				</div>
				</div>
				
		<div class="modal-footer">					
		</div>

</div>
</div>
</div>
		
		
</div>

		