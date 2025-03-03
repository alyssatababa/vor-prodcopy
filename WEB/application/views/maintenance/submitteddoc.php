<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/systemparams/subdoc.js"></script>


<div class="container mycontainer">
    <div class="row">
        <div class="col-md-8">
            <h4>Submitted Doc Notification Template</h4>
        </div>
        <div class="container">
            <button class="pull-right btn btn-primary btn-s_c" data-toggle="modal" data-target="#add_new_sub" onClick="clear_notif();">ADD</button>
        </div>
        <hr>
    </div>

        <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>Template List</h4></strong>
                            </div>
                        </div>  
			<form>
                <div class="table-responsive">
                    <table id="tbl_sub_doc" class="table">

					<thead>
						<th>Template</th>
						<th>Message</th>
						<th>Created By</th>
						<th>Selected</th>
						<th>Date Created</th>
						<th class = "m_action">Modify</th>
					</thead>
					<tbody>

					</tbody>                       
                    </table>
                </div>
			</form>
            </div>

</div>
	</div>

	<div class = "container">
	<div class = "row">

  <div class="modal fade" id="add_new_sub" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_sub" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD Submitted Doc Notification Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_sub_name_new" type="text">
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
						<textarea class="form-control" rows="5" id="cmt_log_sub_new"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_message_sub" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button id = "btn_test" type="button" class="btn btn-primary btn-s_c btn_close_sub" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>




  <div class = "container">
	<div class = "row">

  <div class="modal fade" id="edit_sub_temp" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_sub" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT visit Invite Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_sub_name_edit" type="text">
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
						<textarea id="cmt_log_sub_edit" class="form-control" rows="5"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_edit_save_sub" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button type="button" class="btn btn-primary btn-s_c btn_close_sub" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>
  </form>
  </div>
  </div>





 <div class = "container">
 <div class = "row">

  <div class="modal fade" id="edit_selected_sub" role="dialog" data-mval = "asdf234">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_sub" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT Selected</h4>
        </div>
        <div class="modal-body">
				
			<form class="form-horizontal">
						
			<span>Do you want to edit the selected Submitted Doc Notification Template?</span>
			          
          <button id = "btn_sel_save" type="button" class="btn btn-success btn-s_c">Save</button>
          <button type="button" class="btn btn-alert btn-s_c btn_close_sub" data-dismiss="modal">Close</button>
          </form>
         </div>
      </div>
      </div>
      

  </div>
	</div>
  </div>
  </form>
  </div>
  </div>





