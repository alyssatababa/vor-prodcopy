

<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/systemparams/vendorinvite.js?v=<?php echo date('Ymd'); ?>"></script>

<script type="text/javascript">
	
var e = document.getElementById('cmt_tmp');


</script>


<div class="container mycontainer">
    <div class="row">
        <div class="col-md-8">
            <h4>Registration Invite Template</h4>
        </div>
        <div class="container">
        	<button class="pull-right btn btn-primary btn-s_c" id = "btn_sel_ven_template" onClick ="asd">Delete</button>
            <button class="pull-right btn btn-primary btn-s_c" data-toggle="modal" data-target="#add_ven_temp" style = "margin-right:5px;" id = "btn_add_top_ven">Add</button>      
        </div>
        <hr>
    </div>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <strong class="col-md-4"><h4>Template List</h4></strong>
                </div>
            </div>
	        <div class="table-responsive">
	            <table id="tbl_vendor_invite" class="table table-bordered">
					<thead>
						<th class = "t_small">Select</th>
						<th>Template</th>
						<th class="class_s">Message</th>
						<th>Created By</th>
						<th>Date Created</th>
						<th class = "m_action">Action</th>
					</thead>
					<tbody>

					</tbody>                       
	            </table>
	        </div>


	<div class = "container">
	<div class = "row">

  <div class="modal fade" id="add_ven_temp" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD Registration Invite Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_temp_name" type="text">
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">							
					</div>
			</div>

			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Message : </span>			
				</label>
					<div class="col-sm-8 pull-left">	
						<textarea class="form-control" rows="5" id="cmt_tmp" maxlength="300"></textarea>
					</div>		
					<div class ="col-sm-1">
								
					</div>	
					<div class="col-sm-8 pull-left"></div>
					<div class ="col-sm-3">
					<span class = "h_char">Characters Left : </span><span class = "h_char" id ="txt_left"></span>		
					</div>			
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_message" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button id = "btn_test" type="button" class="btn btn-primary btn-s_c btn_close" data-dismiss="modal">Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>



  <div class = "container">
	<div class = "row">

  <div class="modal fade" id="edit_ven_temp" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal" onClick = "close_al()">&times;</button>
          <h4 class="modal-title">EDIT Registration Invite Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_edit_name" type="text">
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">							
					</div>
			</div>

			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Message : </span></label>
					<div class="col-sm-8 pull-left">	
						<textarea id="cmt_edit" class="form-control" rows="5" maxlength="300"></textarea>
					</div>		
					<div class ="col-sm-1">		
					</div>		
					<div class="col-sm-8 pull-left"></div>
					<div class ="col-sm-3">
					<span class = "h_char">Characters Left : </span><span class = "h_char" id ="txt_left_1"></span>		
					</div>							
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_edit_save" type="button" class="btn btn-primary btn-s_c btn_close">Save</button>
          <button type="button" class="btn btn_close btn-primary btn-s_c" data-dismiss="modal" onClick = "close_al()">Cancel</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>
  </form>
  </div>
  </div>

