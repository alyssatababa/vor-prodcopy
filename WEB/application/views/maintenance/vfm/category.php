<link href="<?=base_url() . 'assets/css/maintenance.css' . filemtime('assets/css/maintenance.css');?>" rel="stylesheet">
<script src="<?=base_url() . 'assets/js/vfm/category.js?' . filemtime('assets/js/vfm/category.js');?>"></script>

<div class="container mycontainer">

<div class="row">
        <div class="col-md-6">
            <h4>Category</h4>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-sm-2">
            <button id ="btn_add_new" class="btn btn-primary btn-whole" data-toggle="modal" data-target="#add_new_category_modal" onClick = "clear_on()">Add New category</button>
        </div>
        </div>
    
  

<div class = "row">


<div class="panel panel-default	">
<div class = "container">
	<h4>Category Search</h4>
	<div class="form-inline">
		<div class="form-group">
			<div class="col-md-12">
				<label style="margin:0;">Name : </label>
				<input id = "inpt_search_category" type="text" class="form-control">
			</div>
		</div>
		<div class="form-group">
			<button id = "btn_search_category" class="btn btn-primary">Search</button>	
		</div>
	</div>

<hr>
<div id = "bot_body">
<div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>Category List</h4></strong>
                            </div>
                        </div>

	
	<table id = "tbl_category">
		<thead>
			<th>#</th>
			<th>Category</th>
			<th>Description</th>
			<th>Date Uploaded</th>
			<th>Action</th>
		</thead>

	</table>
    </div>
    </div>
    </div>
    </div>
 <form class="form-horizontal">

<div class = "container">
    <div class = "row">

  <div class="modal fade" id="add_new_category_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add New category</h4>
        </div>
        <div class="modal-body">
              
           
            
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Category Name : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm mg_left limit-chars" id="input_name_category" type="text"  maxlength="100">
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
                        <textarea id="inpt_msg_desc_category" class="form-control limit-chars" rows="5" maxlength="300"></textarea>
                    </div>      
                    <div class ="col-sm-1">
                            
                    </div> 
                    <div class ="col-sm-12">
                            
                    </div>  
           
            </div>

            <div class="form-group">   
            <div class ="col-sm-1"></div>  
                    <div class = "col-sm-2"><label for ="sel_btype">Business Type : </label></div>                
                    <div class ="col-sm-4">
                            <select class = "form-control" id = "new_sel_btype">
                              <option data-b_type = "1">Trade</option>
                              <option data-b_type = "2">Non-Trade</option>
                              <option data-b_type = "3">Non-Trade Service</option>
                            </select>
                    </div>  
                    </div>
        </div>
        <div class="modal-footer">
          <button id = "btn_save_new_category" type="button" class="btn btn-primary btn-s_c">Save</button>
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

 </form>

   <form class="form-horizontal">

  <div class = "container">
    <div class = "row">

  <div class="modal fade" id="edit_category_new_modal" role="dialog">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn_close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit category</h4>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <div class ="col-sm-1"></div>
                <label for="inpt_temp_name" class="col-sm-2 pull-left"><span>Category Name : </span></label>
                    <div class="col-sm-8 pull-left">    
                        <input class="form-control input-sm mg_left limit-chars" id="input_edit_category" type="text" maxlength="100">
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
						<textarea id="inpt_edit_desc_category" class="form-control limit-chars" rows="5" maxlength="300"></textarea>
					</div>      
					<div class ="col-sm-1">
				   
					</div>                      
				<div class ="col-sm-11">
						<label class = "pull-right"> &nbsp Active</label> 
						<input id = "chk_sta" type ="checkbox" class = "pull-right" disabled>   
					<div class ="col-sm-12"></div>
				</div>
			</div>
			<div class="form-group">   
				<div class ="col-sm-1"></div>  
				<div class = "col-sm-2"><label for ="sel_btype">Business Type : </label></div>                
				<div class ="col-sm-4">
						<select class = "form-control" id = "edit_sel_btype">
						  <option value = "1" data-b_type = "1">Trade</option>
						  <option value = "2" data-b_type = "2">Non-Trade</option>
               <option value = "3" data-b_type = "3">Non-Trade Service</option>
						</select>
				</div>  
			</div>
			<div class="modal-footer">
			  <button id = "btn_save_edit_category" type="button" class="btn btn-primary btn-s_c">Save</button>
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

 </form>
   

