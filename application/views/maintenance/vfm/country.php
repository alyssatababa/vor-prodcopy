<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vfm/country.js"></script>

<div class="container mycontainer">

<div class="row">
        <div class="col-md-6">
            <h4>Country</h4>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-sm-2">
            <button id ="btn_add_new" class="btn btn-primary btn-whole" data-toggle="modal" data-target="#add_new_country_modal" onClick = "clear_on()">Add New country</button>
        </div>
        </div>
    
  

<div class = "row">


<div class="panel panel-default	">
<div class = "container">
	<h4>Country Search</h4>
	<div class="form-inline">
		<div class="form-group">
			<div class="col-md-12">
				<label style="margin:0;">Name : </label>
				<input id = "inpt_search_country" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button id = "btn_search_country" class="btn btn-primary">Search</button>	
		</div>
	</div>

<hr>
<div id = "bot_body">
<div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>Member List</h4></strong>
                            </div>
                        </div>
	
	<table id = "tbl_country">
		<thead>
			<th>#</th>
			<th>Country</th>
			<th>Description</th>
			<th>Date Uploaded</th>
      <th>Default</th>
			<th>Action</th>
		</thead>

	</table>
    </div>
    </div>
    </div>
    </div>


<div class = "container">
    <div class = "row">

  <div class="modal fade" id="add_new_country_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New Country</h4>
        </div>
        <div class="modal-body">
              
            <form class="form-horizontal">
            
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Country Name : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm mg_left" id="input_name_country" type="text">
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
                        <textarea id="inpt_msg_desc_country" class="form-control" rows="5"></textarea>
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div>                      
                    <div class ="col-sm-12">
                            
                    </div>  
            </form>
            </div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_new_country" type="button" class="btn btn-primary btn-s_c">Save</button>
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

  <div class="modal fade" id="edit_country_new_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit country</h4>
        </div>
        <div class="modal-body">
              
            <form class="form-horizontal">
            
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>country Name : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm mg_left" id="input_edit_country" type="text">
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
                        <textarea id="inpt_edit_desc_country" class="form-control" rows="5"></textarea>
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
          <button id = "btn_save_edit_country" type="button" class="btn btn-primary btn-s_c">Save</button>
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










 <div class = "container">
 <div class = "row">

  <div class="modal fade" id="edit_selected_country" role="dialog" data-mval = "asdf234">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close_country" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EDIT Selected</h4>
        </div>
        <div class="modal-body">
        
      <form class="form-horizontal">
            
      <span>Do you want to edit the default selected Country?</span>
                
          <button id = "btn_sel_save_country" type="button" class="btn btn-success btn-s_c">Save</button>
          <button type="button" class="btn btn-alert btn-s_c btn_close_country" data-dismiss="modal">Close</button>
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




   

