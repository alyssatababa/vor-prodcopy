
<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/systemparams/visitinvite.js"></script>

<div class="container mycontainer">
    <div class="row">
        <div class="col-md-8">
            <h4>Visit Invite Template</h4>
        </div>
        <div class="container">
        <button class="pull-right btn btn-primary btn-s_c" id = "btn_sel_vis_template">DELETE</button>
            <button class="pull-right btn btn-primary btn-s_c" data-toggle="modal" data-target="#add_vis_temp" style = "margin-right:5px;" onclick = "add_close()">ADD</button>
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
                    <table id="tbl_visit_invite" class="table">

					<thead>
						<th class = "t_small">Select</th>
						<th>Template</th>
						<th class="smess">Message</th>
						<th>Created By</th>
						<th>Date Created</th>
						<th>Modify</th>
					</thead>
					<tbody>

					</tbody>                       
                    </table>
                </div>

            </div>

</div>
	</div>

	<div class = "container">
	<div class = "row">

  <div class="modal fade" id="add_vis_temp" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD Visit Invite Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_visit_new" type="text">
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
						<textarea class="form-control" rows="5" id="cmt_visit_new" maxlength="300"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_visit_new" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button id = "btn_test" type="button" class="btn btn-primary btn-s_c btn_close" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>




  <div class = "container">
	<div class = "row">

  <div class="modal fade" id="edit_vis" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT visit Invite Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_visit_edit" type="text">
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
						<textarea id="cmt_visit_edit" class="form-control" rows="5" maxlength="300"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_visit_save_edit" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button type="button" class="btn btn-primary btn-s_c btn_close" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>
  </form>
  </div>
  </div>

