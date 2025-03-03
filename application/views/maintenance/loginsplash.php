<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/systemparams/loginsplash.js"></script>

 <?php 
 
		$dsb0 = '';
 		$dsb1 = '';

 	if($dpa[0]->CONFIG_VALUE == 1){
 		$dsb0 = 'disabled';
 		$dsb1 = '';
 	}else{
 		$dsb0 = '';
 		$dsb1 = 'disabled';

 	}
  ?>


<div class="container mycontainer">
    <div class="row">
        <div class="col-md-8">
            <h4>Login Splash Screen Template</h4>
        </div>
        <div class="container">
            <button style="margin-left:5px;" class="pull-right btn btn-primary btn-s_c" data-toggle="modal" data-target="#add_splash_new" onClick = "clear_notif();">ADD</button>
             <button id = "btn_show_dpa" class="pull-right btn btn-primary btn-s_c" style="margin-left:5px;" <?php echo $dsb0; ?> onClick = "hide_show_dpa(1)">Show DPA</button>
             <button id = "btn_hide_dpa" class="pull-right btn btn-primary btn-s_c" <?php echo $dsb1; ?> onClick = "hide_show_dpa(2)">Hide DPA</button>
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
                    <table id="tbl_login_splash" class="table">

					<thead>
						<th>Template</th>
						<th>Message</th>
						<th>Created By</th>
						<th style = "width:50px">Selected</th>
						<th>Date Created</th>
						<th class = "m_action">Action</th>
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

  <div class="modal fade" id="add_splash_new" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_splash" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">ADD Login Splash Screen Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_splash_new" type="text">
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
						<textarea class="form-control" rows="5" id="cmt_splash_new"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_splash_new" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button id = "btn_test" type="button" class="btn btn-primary btn-s_c btn_close_splash" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
	</div>
  </div>




  <div class = "container">
	<div class = "row">

  <div class="modal fade" id="edit_splash" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_splash" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT Login Splash Template</h4>
        </div>
        <div class="modal-body">
		
		
			<form class="form-horizontal">
			
			<div class="form-group">
				<div class ="col-sm-1"></div>
				<label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Template Name : </span></label>
					<div class="col-sm-8 pull-left">	
						<input class="form-control input-sm" id="inpt_splash_edit" type="text">
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
						<textarea id="cmt_splash_edit" class="form-control" rows="5"></textarea>
					</div>		
					<div class ="col-sm-1">
							
					</div>						
					<div class ="col-sm-12">
							
					</div>	
			</form>
			</div>
        </div>
        <div class="modal-footer">
          <button id = "btn_edit_save_splash" type="button" class="btn btn-primary btn-s_c">Save</button>
          <button type="button" class="btn btn-primary btn-s_c btn_close_splash" data-dismiss="modal">Close</button>
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

  <div class="modal fade" id="edit_selected_log" role="dialog" data-mval = "asdf234">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_sub" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT Selected</h4>
        </div>
        <div class="modal-body">
				
			<form class="form-horizontal">
						
			<span>Do you want to edit the selected Login Splash Template?</span>
			          
          <button id = "btn_sel_save_log" type="button" class="btn btn-success btn-s_c">Save</button>
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


