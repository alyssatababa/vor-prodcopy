<link href="<?=base_url();?>assets/css/maintenance.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/vfm/city.js"></script>

<div class="container mycontainer">


<div class="row">
        <div class="col-md-6">
            <h4>City Maintenance</h4>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-sm-2">
            <button id ="btn_add_new" class="btn btn-primary btn-whole" data-toggle="modal" data-target="#add_new_modal_city" onClick  = "clear_on()">Add City</button>
        </div>
        </div>
    
  

<div class = "row">
<div class="panel panel-default	">
<div class = "container">
	<h4>City Search</h4>
	<div class="form-inline">
		<div class="form-group">
			<div class="col-md-12">
				<label style="margin:0;"> Name :  </label>
				<input id = "inpt_search_city" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button id = "btn_search_city" class="btn btn-primary">Search</button>	
		</div>
	</div>

<hr>
<div id = "bot_body">

<div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>City List</h4></strong>
                            </div>
                        </div>


	<table id = "tbl_city">
		<thead>
			<th>#</th>
			<th>City Name</th>
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

  <div class="modal fade" id="add_new_modal_city" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New City</h4>
        </div>
        <div class="modal-body">
              
            <form class="form-horizontal">
            
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>City Name : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm pull-left" id="input_name_city" type="text">
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
                        <textarea id="inpt_msg_desc_city" class="form-control" rows="5"></textarea>
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div>                      
                    <div class ="col-sm-12">
                            
                    </div>  
            </form>
            </div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_new_city" type="button" class="btn btn-primary btn-s_c">Save</button>
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

  <div class="modal fade" id="edit_city_new_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit City Maintenance</h4>
        </div>
        <div class="modal-body">
              
            <form class="form-horizontal">
            
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>City Maintenance: </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm mg_left" id="input_edit_city" type="text">
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
                        <textarea id="inpt_edit_desc_city" class="form-control" rows="5"></textarea>
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
          <button id = "btn_save_edit_city" type="button" class="btn btn-primary btn-s_c">Save</button>
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


   

