<link href="<?=base_url() . 'assets/css/edit_email.css?' . filemtime('assets/css/edit_email.css');?>" rel="stylesheet">
<div class="container mycontainer">
<div class="row">
   <div class="col-md-8">
      <h4>Notification Template</h4>
   </div>
   <div class="container">
      <!-- <button class="pull-right btn btn-primary" data-toggle="modal" data-target="#add_notification" style = "margin-right:5px;">Add New Notification Template</button>       -->
   </div>
   <hr>
</div>
<div class="panel panel-primary">
<div class="panel-heading">
   <div class="row">
      <strong class="col-md-4">
         <h4>Template List</h4>
      </strong>
   </div>
</div>
<div class="table-responsive">
   <table id="table_notification" class="table table-bordered">
      <thead>
         <th style="width:200px;">Description</th>
         <th style="width:200px;">Status</th>
         <th style="width:200px;">Topic</th>
         <th class="class_s">Message</th>
         <th style="width:50px;" class = "m_action">Action</th>
      </thead>
      <tbody>
      </tbody>
   </table>
</div>



<div class = "container">
    <div class = "row">
  <div class="modal fade" id="edit_notification" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal" onclick="close_edit();">&times;</button>
          <h4 class="modal-title">Edit Notification Template</h4>
        </div>
        <div class="modal-body">
        
        
            <form class="form-horizontal">
 
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>STATUS : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <label id="inpt_edit_status"></label>
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div>                      
                    <div class ="col-sm-12">                            
                    </div>
            </div>           
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>TOPIC : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm" maxlength="100" id="inpt_edit_desc" type="text">
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div>                      
                    <div class ="col-sm-12">                            
                    </div>
            </div>
			
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Description : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <textarea class="form-control limit-chars" id="inpt_edit_desc_2"  rows="5" maxlength="1000"></textarea>
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div>                      
                    <div class ="col-sm-12">                            
                    </div>
            </div>

            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>MESSAGE : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <textarea id="cmt_edit_cont" class="form-control" rows="15" maxlength="3000"></textarea>
                    </div>      
                    <div class ="col-sm-1">     
                    </div>      
                    <div class="col-sm-8 pull-left"></div>
                    <div class ="col-sm-3">
      
            </form>
            </div>
        </div>
        <div class="modal-footer">
          <button id = "btn_edit_save" type="button" class="btn btn-primary g_buttons">Save</button>
          <button type="button" class="btn btn_close btn-primary g_buttons" data-dismiss="modal" onclick="close_edit();">Cancel</button>
        </div>
      </div>
      </form>
      </div>
      </div>
      </div>




    <div class="container">
    <div class="row">
        <div class="modal fade" id="add_notification" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-lg">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close btn_close" data-dismiss="modal" onclick="close_new();">&times;</button>
                        <h4 class="modal-title">Add Notification Template</h4>
                    </div>
                    <div class="modal-body">

                        <form class="form-horizontal">

                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Description : </span></label>
                                <div class="col-sm-8 pull-left">
                                    <input class="form-control input-sm" maxlength="100" id="inpt_new_desc" type="text">
                                </div>
                                <div class="col-sm-1">

                                </div>
                                <div class="col-sm-12">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-1"></div>
                                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Content : </span></label>
                                <div class="col-sm-8 pull-left">
                                    <textarea id="cmt_new_cont" class="form-control" rows="5" maxlength="3000"></textarea>
                                </div>
                                <div class="col-sm-1">
                                </div>
                                <div class="col-sm-8 pull-left"></div>
                                <div class="col-sm-3">

                        </form>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn_new_save" type="button" class="btn btn-primary g_buttons" onclick="check_save();">Save</button>
                            <button type="button" class="btn btn_close btn-primary g_buttons" data-dismiss="modal" onclick="close_new();">Cancel</button>
                        </div>
                    </div>




<script src="<?=base_url() . 'assets/js/systemparams/edit_notification.js?' . filemtime('assets/js/systemparams/edit_notification.js');?>"></script>
