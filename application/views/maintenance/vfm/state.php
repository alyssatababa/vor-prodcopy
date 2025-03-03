<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vfm/state.js"></script>
<div class="container mycontainer">
   <div class="row">
      <div class="col-md-6">
         <h4>State / Province</h4>
      </div>
      <div class="col-md-4">
      </div>
      <div class="col-sm-2">
         <button id ="btn_add_new_state" class="btn btn-primary btn-whole" data-toggle="modal" data-target="#add_new_state_modal" onClick = "clear_on()">ADD State/Province</button>
      </div>
   </div>
   <div class = "row">
      <div class="panel panel-default ">
         <div class = "container">
            <h4>State/Province Search</h4>
            <div class="form-inline">
               <div class="form-group">
                  <div class="col-md-12">
                     <label style="margin:0;"> Name :  </label>
                     <input id = "inpt_search_state" type="text" class="form-control">
                  </div>
               </div>
               <div class="form-group">
                  <button id = "btn_search_state" class="btn btn-primary">Search</button> 
               </div>
            </div>
            <hr>
            <div id = "bot_body">
               <div class="panel panel-primary">
                  <div class="panel-heading">
                     <div class="row">
                        <strong class="col-md-4">
                           <h4>State Province List</h4>
                        </strong>
                     </div>
                  </div>
                  <table id = "tbl_state">
                     <thead>
                        <th>#</th>
                        <th>State / Province Name</th>
                        <th>Description</th>
                        <th>Date Uploaded</th>
                        <th>Action</th>
                     </thead>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <div class = "container">
         <div class = "row">
            <div class="modal fade" id="add_new_state_modal" role="dialog">
               <div class="modal-dialog modal-lg">
                  <!-- Modal content-->
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add New State / Province</h4>
                     </div>
                     <div class="modal-body">
                        <form class="form-horizontal">
                           <div class="form-group">
                              <div class ="col-sm-1"></div>
                              <label for="inpt_temp_name" class="col-sm-3 pull-left"><span>State / Province Name : </span></label>
                              <div class="col-sm-7 pull-left">    
                                 <input class="form-control input-sm pull-left" id="input_name_state" type="text">
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
                                 <textarea id="inpt_msg_desc_state" class="form-control" rows="5"></textarea>
                              </div>
                              <div class ="col-sm-1">
                              </div>
                              <div class ="col-sm-12">
                              </div>
                        </form>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button id = "btn_save_new_state" type="button" class="btn btn-primary btn-s_c">Save</button>
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
</div>
</div>
</div>
<div class = "container">
   <div class = "row">
      <div class="modal fade" id="edit_state_new_modal" role="dialog">
         <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Edit State / Province</h4>
               </div>
               <div class="modal-body">
                  <form class="form-horizontal">
                     <div class="form-group">
                        <div class ="col-sm-1"></div>
                        <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>State / Province Name : </span></label>
                        <div class="col-sm-8 pull-left">    
                           <input class="form-control input-sm mg_left" id="input_edit_state" type="text">
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
                           <textarea id="inpt_edit_desc" class="form-control" rows="5"></textarea>
                        </div>
                        <div class ="col-sm-1">
                        </div>
                        <div class ="col-sm-11">
                           <label class = "pull-right"> &nbsp Active</label> 
                           <input id = "chk_sta" type ="checkbox" class = "pull-right" disabled>   
                        </div>
                  </form>
                  </div>
               </div>
               <div class="modal-footer">
                  <button id = "btn_save_edit" type="button" class="btn btn-primary btn-s_c">Save</button>
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
</div>
</div>