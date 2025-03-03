
<link href="<?=base_url() . 'assets/css/maintenance.css?' . filemtime('assets/css/maintenance.css');?>" rel="stylesheet">
<script type="text/javascript">
    
mvrd = <?php echo $mvrd[0]->CONFIG_VALUE ;?> ;
mvrh = <?php echo $mvrd[1]->CONFIG_VALUE ;?> ;

</script>

<script src="<?=base_url() . 'assets/js/maintenance/users.js?' . filemtime('assets/js/maintenance/users.js'); ?>">
</script>

<style type="text/css">
    
    #tbl_search_user th{
        padding:10px;
        color:#000;

    }

    #tbl_search_user td{
        padding:7px;
    }

    #tbl_search_user th a{
        color:#000;
    }



</style>


<div class="container mycontainer"> 
        <div class = "row">
            <div class = "col-sm-1">
                    <h4>Users</h4>
            </div>
            <div class = "col-sm-1 pull-right">
                    <button id="btn_add_new" class="btn btn-primary btn_add pull-right" data-toggle="modal" data-target="#add_user_mod" onclick="trig()">Add New User</button>
            </div>
        </div>

        <div class = "row">
            <div class="panel panel-default">

    
        


     <form class="form-horizontal">



                <div class="row">

                    <div class="container">
                     <h4>User Search</h4>

                            <div class="form-group">
                                <span for="pos_sel_pos" class="col-sm-2 pull-left"><span class="pull-right"><strong>Search Type :</strong></span></span>


                                <div class="col-sm-2">
                                    <select id="user_select_type" class="form-control pull-left" name="color">
                                        <option data-stype = "1">All</option>
                                        <option  data-stype = "2">LOGIN ID</option>
                                        <option  data-stype = "3">USER NAME</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <input id="search_user_type" type="text" class="form-control">
                                </div>

                                <div class="col-md-1">
                                    <button id="btn_search_user" class="btn btn-primary btn-s_c pull-left" onclick="return false;" data-dismiss="alert">Search</button>
                                </div>
                            </div>
                         </div>
                    </form>
                    
                    <div class="row">

                    <div class="container">
                     <div class="panel panel-primary" id = "tbl_users_dis">
                        <div class="panel-heading">
                            <div class="row">
                                <strong class="col-md-4"><h4>Member List</h4></strong>
                            </div>
                        </div>
                    <div class="table-responsive">
                    <table id = "tbl_search_user">      
                    <thead>
			<th style="width:100px;" class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "USERNAME">
               LOGIN ID
                </a>
            </th>
            <th class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "USER_FIRST_NAME">
               USER NAME
                </a>
            </th>
            <th class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "POSITION_NAME">
              POSITION
                </a>
            </th>
            <th style="width:150px;" class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "USER_TYPE">
                USER TYPE
                </a>
            </th>
            <th class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "LAST_ATTEMPT">
              LAST LOGIN
                </a>
            </th>
            <th style="width:150px;" class = "a_table_header sort_column sort_default">
                <a href = "#" onclick = "return false;" data-sort = "desc" data-sort_type = "USERNAME">
                ACTION
                </a>
            </th>

                    <tbody>    
                    </tbody>
                    </table>            
                    </div>
                     <center><div id="users_pagination"></div>
                    </center>
                    </div>
                    </div>
                    </div>



            </div>
        </div>




</div>


    <script type="mustache/x-tmpl" id = "user_table">    
        {{#ds}}
        <tr>
            <td>
            {{USERNAME}}
            </td>
            <td>
            {{USER_FIRST_NAME}}
            {{USER_MIDDLE_NAME}}
            {{USER_LAST_NAME}}
            </td>
        <td>
            {{POSITION_NAME}}
        </td>
        <td>
            {{USER_TYPE}}
        </td>
        <td>
            {{LAST_ATTEMPT}}
        </td>
        <td>
		{{#IS_INVITER}}
			<button data-ui = "{{USER_ID}}" class = "btn btn-default view_pend" data-toggle="modal" data-target="#view_pending_records"><span class= "glyphicon glyphicon-th g_icon" onclick = "return false"></span></button>
		{{/IS_INVITER}}
            <button data-ui = "{{USER_ID}}" class = "btn btn-default edit_user" data-toggle="modal" data-target="#edit_user_mod"><span class= "glyphicon glyphicon-pencil g_icon" onclick = "return false"></span></button>
            <button type="button"class = "btn btn-default del_user" data-id ="{{USER_ID}}"  onclick ="del_user(this)"><span class = "g_icon glyphicon glyphicon-trash"></span></button>

        </td>
        </tr>
        {{/ds}}
        {{^ds}}
            <tr>
            <td colspan = "6">No Results Found!</td>
            </tr>
        {{/ds}}



    </script>   



<form class="form-inline">
    <div id="add_user_mod" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--  //header -->
                <div class="modal-header">
                    <button type="button" class="close btn_close" data-dismiss="modal" aria-hidden="true" onclick="clear_modal('add_user_mod')">&times;</button>
                    <h4 class="modal-title">Add New User</h4>
                </div>
                <!-- End Header -->
                <div class="modal-body">
                    <div class="container">
                        <!-- Start form group 1 -->
                        <div class="form-group">

                            <div class="col-sm-5">
                                <label for="users_login_id">Login ID : </label>
                                <input style="min-width:280px;" id="new_login_id" type="text" class="form-control limit-chars" data-name="Login ID" maxlength="100">
                            </div>
                            <br>
                            <div class="col-sm-12"></div>
                            <br>
                            <!--<div class="col-sm-5">
                                <label for="pass_new" class="pul-right">Password : </label>
                                <input style="min-width:280px;" id="pass_new" type="password" class="form-control" data-name="Password">
                            </div>
                            <div class="col-sm-5">
                                <label for="confirm_pass_new">Confirm Password : </label>
                                <input style="min-width:280px;" id="confirm_pass_new" type="password" class="form-control" data-name="Confirm Password">
                            </div>-->
                        </div>
                    </div>
                    <!-- End form group 1 -->
                    <br>
                    <!-- Start Form group 2 -->
                    <div class="container">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label style="font-size:25px;">User Information</label>
                            </div>
                            <br>
                            <div class="col-sm-5">
                                <label for="fn_new">First Name : </label>
                                <input style="min-width:280px; text-transform: uppercase;" id="fn_new" type="text" class="form-control limit-chars" data-name="First Name" maxlength="100">
                            </div>
                            <div class="col-sm-5">
                                <label for="mn_new">Middle Name : </label>
                                <input style="min-width:280px; text-transform: uppercase;" id="mn_new" type="text" class="form-control limit-chars" data-name="Middle Name" maxlength="100">
                            </div>

                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for="ln_new">Last Name : </label>
                                <input style="min-width:280px; text-transform: uppercase;" id="ln_new" type="text" class="form-control limit-chars" data-name="Last Name" maxlength="100">
                            </div>
                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for="mo_new">Mobile Number : </label>
                                <input style="min-width:280px;" id="mo_new" type="text" class="form-control limit-chars data-name="Mobile No." maxlength="13">
                            </div>
                            <div class="col-sm-5">
                                <label for="email_new">Email Address: </label>
                                <input style="min-width:280px;" id="email_new" type="text" class="form-control limit-chars" data-name="Email Address" maxlength="300">
                            </div>
                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for="slt_user_type_new" class="">User Type : </label>
                                <select style="min-width:280px;" class="form-control" id="slt_user_type_new">
                                    <?php for($i=0 ; $i < count($user_type_list[ 'data']);$i++){ echo '<option data-type = "'.$user_type_list[ 'data'][$i][ 'USER_TYPE_ID']. '">'.$user_type_list[ 'data'][$i][ 'USER_TYPE']. '</option>'; } ?>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label for="slt_user_position_new" class="">Position : </label>
                                <select style="min-width:280px;" class="form-control" id="slt_user_position_new">

                                </select>
                            </div>
                            <br/>
                            <div class="col-sm-5">
                                <label for = "user_status_add" class = "pul-right">User Status: </label>
                                <select style="min-width:280px;" class = "form-control" id = "user_status_add" disabled>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>     
                            </div>
                            <div class="col-sm-12"></div>
                            <br>
                            <div class="col-sm-5 a_senmer">
                                <label for="users_login_id" class="pul-right">GROUP HEAD : </label>
                                <select style="min-width:280px;" class="form-control" id="g_head">
                                </select>
                            </div>
                            <div class="col-sm-5 a_senmer">
                                <label for="users_login_id" class="pul-right">FAS HEAD : </label>
                                <select style="min-width:280px;" class="form-control" id="fas_head">
                                </select>
                            </div>
                            <div class="col-sm-12"></div>
                            <br>
                            <div class="col-sm-5 a_buyer">
                                <label for="users_login_id" class="pul-right">BUHEAD : </label>
                                <select style="min-width:280px;" class="form-control" id="buyer_head">
                                </select>
                            </div>
                        </div>
                        <!-- End form Group 2 -->
                    </div>
                    <div class="container">
                        <div class="col-sm-12"></div>
                        <br>
                        <div class="a_buyer a_senmer col-sm-4">
                            <label for="vrd_staff">VRD STAFF : </label>
                            <select style="min-width:280px;" class="form-control" id="vrd_staff">
                            </select>
                            <a href="#" onclick="return false;" id="add_vrd"><span class="glyphicon glyphicon-plus" style="color:green;"></span></a>
                        </div>
                        <div id="cont_vrd"></div>
                        <div class="col-sm-12"></div>
                        <div class="col-sm-4 a_buyer a_senmer pull-left">
                            <label for="users_login_id" class="pul-right">VRD HEAD : </label>
                            <select style="min-width:280px;" class="form-control" id="vrd_head">
                            </select>
                             <a href="#" onclick="return false;" id="add_vrdhead"><span class="glyphicon glyphicon-plus" style="color:green;"></span></a>
                        </div>
                        <div id="cont_vrdhead"></div>
                        <div class="col-sm-12"></div>
                    </div>
                    <div class="container">
                        <div class="col-sm-5 category_css">
                           <br class="category_css">
                            <label for="search_cat_user">Category : </label>
                            <input type="text" class="form-control" id="search_cat_user" data-exclude ="exc"> 
                            <button type="button" class = "btn btn-primary" onClick = "return false;" id ="btn_search_category_new"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>  
                        </div>         
                        <div class="col-sm-12"><br><br></div>   
                        <div class = "col-sm-4 category_css">    
                                <label>Category List : </label>                      
                                <select style="min-width: 300px" size="5" class = "form-control" id = "category_new"></select>
                        </div>
                                    <div class="col-sm-1 category_css">
                                        <label> -------- </label>
                                        <button class = "btn btn-default" id = "btn_add_cat_new"><span class = "glyphicon glyphicon-chevron-right g_icon" aria-hidden="false"></span></button>
                                        <button class = "btn btn-default" id = "btn_rmv_cat_new"><span class = "glyphicon glyphicon-chevron-left g_icon" aria-hidden="false"></span></button>
                                    </div>  
                        <div class = "col-sm-4 category_css">  
                                <label>Selected : </label>         
                                <select style="min-width: 300px" size="5" class = "form-control" id = "sel_category_new"></select>
                        </div>
                    </div>
                    <br class="category_css">
                    <div class="container">
                     <input id="chk_send_email" type="checkbox">
                        <label> &nbsp <small> Send login information to this users e-mail address</small>
                    </div>
                    </div>

            <!-- Footer Below -->

            <div class="modal-footer">                     
                    <button id="btn_save_new" class="btn btn-primary pull-left btn_add" onclick="return false;">Save</button> 
                    <button type="button" id="btn_cancel_new" class="btn btn-primary pull-left btn_add" data-toggle="modal" data-target="#add_user_mod" style = "margin-right:5px;" onClick = "clear_modal('add_user_mod')">Cancel</button>         
            </div>
            </div>
             </div>

        </div>
    </div>
</form>


 <form class="form-inline">
    <div id="edit_user_mod" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--  //header -->
                <div class="modal-header">
                    <button type="button" class="close btn_close" data-dismiss="modal" aria-hidden="true" onclick="clear_modal('edit_user_mod')">&times;</button>
                    <h4 class="modal-title">Edit User Details</h4>
                </div>
                <!-- End Header -->
                <div class="modal-body">
                    <div class="container" style="width:100%;">
                        <!-- Start form group 1 -->
                        <div class="form-group">

                            <div class="col-sm-5">
                                <label for = "users_login_id" class = "pul-right">Login ID : </label>
                                <input style="min-width:280px;" id="edit_login_id" type="text" class="form-control" data-name = "Login ID" disabled>
                            </div>
                            <br>
                            <div class="col-sm-12"></div>
                            <br>
                            <div class="col-sm-5">
                                <label for = "pass_edit" class = "pul-right">Password : </label>
                                <input style="min-width:280px;" id="pass_edit" type="password" class="form-control" data-name = "Password" data-exclude="exc">
                            </div>
                            <div class="col-sm-5">
                                <label for = "confirm_pass_edit" class = "pul-right">Confirm Password : </label>
                                <input style="min-width:280px;" id="confirm_pass_edit" type="password" class="form-control" data-name = "Confirm Password" data-exclude="exc">
                            </div>
                        </div>
                    </div>
                    <!-- End form group 1 -->
                    <br>
                    <!-- Start Form group 2 -->
                    <div class="container" style="width:100%;">
                        <div class="form-group">
                            <div class="col-lg-12">
                                <label style="font-size:25px;">User Information</label>
                            </div>
                            <div id = "dhs"></div>
                            <br>
                            <div class="col-sm-5">
                                <label for = "fn_edit" class = "pul-right">First Name/Vendor Name : </label>
                                <input style="min-width:280px; text-transform: uppercase;" id="fn_edit" type="text" class="form-control limit-chars" data-name = "First Name" maxlength="100">
                            </div>
                            <div class="col-sm-5">
                                <label for = "mn_edit" class = "pul-right">Middle Name : </label>
                                <input  id="mn_edit"  style ="text-transform: uppercase;"type="text" class="form-control limit-chars" data-name = "Middle Name" maxlength="100">
                            </div>

                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for = "ln_edit" class = "pul-right">Last Name : </label>
                                <input style="min-width:280px;text-transform: uppercase;" id="ln_edit" type="text" class="form-control limit-chars" data-name = "Last Name" maxlength="100">
                            </div>
                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for = "mo_edit" class = "pul-right">Mobile Number : </label>
                                <input style="min-width:280px;" id="mo_edit" type="text" class="form-control limit-chars" data-name = "Mobile No." maxlength="13">
                            </div>
                            <div class="col-sm-5">
                                <label for = "email_edit" class = "pul-right">Email Address: </label>
                                <input id="email_edit" type="text" class="form-control limit-chars" data-name = "Email Address" maxlength="300">
                            </div>
                            <div class="col-sm-12"></div>
                            <br>

                            <div class="col-sm-5">
                                <label for = "slt_user_type_edit" class = "pul-right">User Type : </label>
                                <select style="min-width:280px;" class = "form-control" id = "slt_user_type_edit">
                                <?php for($i=0 ; $i < count($user_type_list[ 'data']);$i++){ echo '<option data-type = "'.$user_type_list[ 'data'][$i][ 'USER_TYPE_ID']. '" value="'.$user_type_list[ 'data'][$i][ 'USER_TYPE_ID']. '">'.$user_type_list[ 'data'][$i][ 'USER_TYPE']. '</option>'; } ?>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label for = "slt_user_position_edit" class = "pul-right">Position : </label>
                                <select class = "form-control" id = "slt_user_position_edit">
                           
                                </select>     
                            </div>
                            <br/>
                            <div class="col-sm-5">
                                <label for = "user_status_edit" class = "pul-right">User Status: </label>
                                <select style="min-width:280px;" class = "form-control" id = "user_status_edit">
                                    <option data-type="1" value="1">Active</option>
                                    <option data-type="0" value="0">Inactive</option>
                                </select>     
                            </div>

                            <div class="col-sm-5">
                                <br/>
                                <button id="btn_vw_user_status_history" class="btn btn-primary" data-toggle="modal" data-target="#vw_user_status_history"  data-backdrop="static" data-keyboard="false"onclick="return false;">View User Status History</button>  
                            </div>

                            <div class="col-sm-12"></div>

                            <div class="col-sm-5 a_senmer_e">
                                <label for = "users_login_id" class = "pul-right">GROUP HEAD : </label>
                                <select style="min-width:280px;" class = "form-control"  id = "g_head_e">
                                </select>
                            </div>
     
                            <div class="col-sm-5 a_senmer_e">
                                <label for = "users_login_id" class = "pul-right">FAS HEAD : </label>
                                <select style="min-width:280px;" class = "form-control"  id = "fas_head_e">
                                </select>
                            </div>
                            <div class="col-sm-12"></div>
                            <br>
                            <div class="col-sm-5 a_buyer_e">         
                                <label for = "users_login_id" class = "pul-right" >BUHEAD : </label>
                                <select style="min-width:280px;" class = "form-control" id = "buyer_head_e">    
                                </select>
                            </div>

                        </div>
                        <!-- End form Group 2 -->
                    </div>
                    <div class="container" style="width:100%;">
                        <div class="col-sm-12"></div>
                        <br>
                        <div class="a_buyer_e a_senmer_e col-sm-4">
                            <label for = "users_login_id" class = "pul-right">VRD STAFF : </label>
                            <select style="min-width:280px;" class = "form-control" id = "vrd_staff_e"> 
                            <option> -- Select VRDSTAFF --</option>  
                            </select>
                            <a href="#" onclick="return false;" id="add_vrd_e"><span class="glyphicon glyphicon-plus" style="color:green;"></span></a>
                        </div>
                        <div id="cont_vrd_e"></div>
                        <div class="col-sm-12"></div>
                        <div class="col-sm-4 a_buyer_e a_senmer_e pull-left">
                            <label for="users_login_id" class="pul-right">VRD HEAD : </label>
                            <select style="min-width:280px;" class="form-control" id="vrd_head_e">
                            </select>
                            <a href="#" onclick="return false;" id="add_vrdhead_e"><span class="glyphicon glyphicon-plus" style="color:green;"></span></a>
                        </div>
                        <div id="cont_vrdhead_e"></div>
                        <div class="col-sm-12"></div>
                    </div>
                    <div class="container" style="width:100%;">
                        <div class="col-sm-5 category_css_e">
                           <br class="category_css_e">
                            <label for="search_cat_user_e">Category : </label>
                            <input type="text" class="form-control" id="search_cat_user_e" data-exclude ="exc"> 
                            <button type="button" class = "btn btn-primary" onClick = "return false;" id ="btn_search_category_e"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                        </div>         
                        <div class="col-sm-12"><br><br></div>   
                        <div class = "col-sm-4 category_css_e">    
                                <label>Category List : </label>                    
                                <select style="min-width: 300px" size="5" class = "form-control" id = "category_e"></select>
                        </div>
                                <div class="col-sm-1 category_css_e">
                                    <label> -------- </label>
                                    <button class = "btn btn-default" id = "btn_add_cat_e"><span class = "glyphicon glyphicon-chevron-right g_icon" aria-hidden="false"></span></button>
                                    <button class = "btn btn-default" id = "btn_rmv_cat_e"><span class = "glyphicon glyphicon-chevron-left g_icon" aria-hidden="false"></span></button>
                                </div>  
                        <div class = "col-sm-4 category_css_e">  
                                <label>Selected : </label>         
                                <select style="min-width: 300px" size="5" class = "form-control" id = "sel_category_e"></select>
                        </div>
                    </div>
                    <br class="category_css_e">
					<div class="container" style="width:100%;" id="deactivated-alert" style="display:none;">
						<div class="panel panel-danger"  style="display:block;">
							<h4 class="panel-heading" style="font-size:1.5em; margin: 0;"><strong>This account has been deactivated!</strong></h4>
							<div class="panel-body">
								<p>
								Total Login Attempts: <strong id="total_attempts"></strong></p>
								<br/>
								<p>This account have reached the maximum number of failed login attempts and will be unlocked on <strong id="unlock_date"></strong>.</p>
								<br/>
								<label for="unlock_reason_txt"><strong>Reason:</strong></label>
								<textarea id="unlock_reason_txt" class="form-control limit-chars" style="width:100%; min-width:100%; max-height:200px; min-height:100px; margin-bottom:20px;" maxlength="1000"></textarea>
								
								<button id="btn_unlock_account" class="btn btn-success" onclick="return false;">Unlock Account</button> 
						    </div>
						</div>
					</div>
                </div>

            <!-- Footer Below -->

            <div class="modal-footer">    
                    <button id="btn_resend" type = "button" class="btn btn-primary pull-left btn_add" onclick="resend_email();">Resend E-mail</button>     
					
					<button id="btn_view_resend_log" class="btn btn-primary" data-toggle="modal" data-target="#resend_log_modal"  data-backdrop="static" data-keyboard="false"onclick="return false;">Resend Log</button>  
					
                    <button id="btn_save_e" class="btn btn-primary pull-left btn_add" onclick="return false;">Save</button> 
                     <button type="button" id="btn_cancel_e" class="btn btn-primary pull-left btn_add" data-toggle="modal" data-target="#edit_user_mod" style = "margin-right:5px;" onClick = "clear_modal('edit_user_mod')">Cancel</button>       
					<button id="btn_view_unlock_account_log" class="btn btn-primary" data-toggle="modal" data-target="#unlock_account_log_modal"  data-backdrop="static" data-keyboard="false"onclick="return false;">Unlock Account Log</button>  
                   
            </div>
            </div>
             </div>

    </div>
	
	<div id="view_pending_records" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--  //header -->
                <div class="modal-header">
                    <button type="button" class="close btn_close" data-dismiss="modal" aria-hidden="true" onclick="clear_modal('view_pending_records')">&times;</button>
                    <h4 class="modal-title">View Pending Records</h4>
                </div>
                <!-- End Header -->
                <div class="modal-body" id="modal_body">
                    <div class="container" style="width:100%;">
						<table id="tbl_pending_record" class="table table-bordered">
							<thead>
								<tr class="info">
									<th class="text-center">VENDOR NAME</th>
									<th class="text-center">STATUS NAME</th>
								</tr>
							</thead>
							<tbody id="tbl_history_body" style="text-align: left;">  					
								<script type="mustache/x-tmpl" id = "pending_record">
									{{#ds}}
									<tr>
										<td>{{VENDOR_NAME}}</td>
										<td>{{STATUS_NAME}}</td>
									</tr>
									{{/ds}}
									{{^ds}}
										<tr>
										<td colspan = "6">No Results Found!</td>
										</tr>
									{{/ds}}
								</script> 
							</tbody>
						</table>
					</div>
                </div>
            </div>
        </div>
    </div>
</form>




<!-- Start Modal -->
<div class="modal fade" id="unlock_account_log_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index:1051;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Unlock Account History</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="panel panel-primary"  style="margin: 0;">
                        <div class="panel-heading">
                        <h3 class="panel-title">Unlock Account History</h3>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="height: 400px; overflow: auto;">
                        <table id="tbl_unlock_account_log" class="table table-bordered">
                            <thead>
                                <tr class="info">
                                    <th>Date</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_history_body" style="text-align: left;">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal -->

<!-- Start Modal -->
<div class="modal fade" id="vw_user_status_history" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index:1051;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User Status History</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="panel panel-primary"  style="margin: 0;">
                        <div class="panel-heading">
                        <h3 class="panel-title">User Status History</h3>
                        </div>
                    </div>
                    <div class="panel panel-primary" style="height: 400px; overflow: auto;">
                        <table id="tbl_user_status_history" class="table table-bordered">
                            <thead>
                                <tr class="info">
                                    <th>Status</th>
                                    <th>Date Modified</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_user_status_history_body" style="text-align: left;">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- END Modal -->



<!-- Start Modal -->
<div class="modal fade" id="resend_log_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="z-index:1051;">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Resend History</h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
				
					<div class="panel panel-primary" style="margin: 0;">
						<div class="panel-heading">
							<h2 class="panel-title">Total Resend: <span id="total-resend"></span></h2>
						</div>
					</div>
					<div class="panel panel-primary" style="height: 400px; overflow: auto;">
						<table id="tbl_resend_log" class="table table-bordered">
							<thead>
								<tr class="info">
									<th>Date</th>
									<th>Token</th>
									<th>Result</th>
								</tr>
							</thead>
							<tbody id="tbl_history_body" style="text-align: left;">
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- END Modal -->

<!-- <script type="text/template" id="edit_temp">
  {{#ds}}
    <option value = "{{POSITION_ID}}"> 
    {{POSITION_NAME}}
    </option>
  {{/ds}}
</script>   --> 

<script type="text/template" id="new_temp">
    {{#ds}}
        <option data-pos = "{{POSITION_ID}}" value="{{POSITION_ID}}"> 
        {{POSITION_NAME}}
        </option>
    {{/ds}}
</script>  


