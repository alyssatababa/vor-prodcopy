<style type="text/css">
   #tbl_search tr {
   width: 100%;
   display: inline-table;
   table-layout: fixed;
   }
   #tbl_search{
   height:300px;  
   width:100%;             
   }
   #tbl_search tbody{
   overflow-y: scroll;      
   height: 200px;       
   width:90%; 
   position: absolute;
   }
   .blk_icon{
   color: #000000;
   }
   .td_view{
   width: 13%;
   }


#tbl_bulletin_search th{
text-align: center;
border-color:#D9EDF7;
background-color:#D9EDF7;
}
#tbl_bulletin_search{

border-bottom: 0px;
table-layout: fixed; 
word-wrap:break-word;
width:100%;
text-align:center;
}

.m-200{
   min-width: 200px;
}

.m-100{
    min-width: 100px;
}

.m-150{
    min-width: 150px;
}

.mleft-5{

   margin-left: 5px;
}


</style>
<div class="row">
   <div class="col-md-12">
      <div class="panel panel-primary">
         <div class="panel-heading">
            <h3 class="panel-title">Bulletin Dashboard</h3>
         </div>
         <div class="panel-body">
            <form class = "">
               <div class = "panel panel-primary">
                  <div class = "panel-heading">
                     <h3 class="panel-title">Search Bulletin</h3>
                  </div>
                  <div class = "panel-body">
            <!--      <label for ="reference_no" >Reference # : </label>
                     <input id = "reference_no" type = "text" class = "form-control"/>
                     <label style ="margin-left:10px;">Bulletin Title : </label>
                     <input type = "text" class = "form-control"/>
                     <label style ="margin-left:10px;">Status : </label>
                     <select class = "form-control" style="min-width: 200px;"> -->

                     <div style="padding:5px;">
                        <div class = "col-sm-3">
                           <label for ="reference_no" >Bulletin ID : </label>
                           <input id = "reference_no" type = "text" class = "form-control m-200"/>
                        </div>
                        <label for ="reference_no"  class ="mleft-5">Date From : </label>
                        <input id = "reference_no" type = "date" class = "form-control m-200"/>
                        <label for ="reference_no"  class ="mleft-5">Date To : </label>
                        <input id = "reference_no" type = "date" class = "form-control m-200"/>
                     </div>
                     <div style="padding:5px;">
                        <label for ="reference_no" >Reference No. : </label>
                        <input id = "reference_no" type = "text" class = "form-control m-200"/>
                        <label for ="reference_no" class ="mleft-5">Status : </label>
                        <select class = "form-control m-200">
                           <option>All</option>
                        </select>
                     </div>
                     <div style="padding:5px;">
                        <label for ="reference_no">Bulletin Title : </label>
                        <input id = "reference_no" type = "text" class = "form-control m-200"/>       
                        <button type ="button" class = "form-control btb btn-primary pull-right m-100 mleft-5" id = "btn_clear">Clear</button>
                        <button type ="button" class = "form-control btn btn-primary pull-right m-100" id = "btn_search">Search</button>
                     </div>


               <!--  <button type ="button" class = "form-control btn btn-primary" id = "btn_search">Search</button>
                     <button type ="button" class = "form-control btb btn-primary" id = "btn_clear">Clear</button> -->
                  </div>
               </div>


               <form class = "form-inline">
               <div class = "panel panel-primary">
                  <div class = "panel-heading">
                     <h3 class="panel-title">Search Results</h3>
                  </div>
                  <div class = "panel-body">
                     <div class="table-responsive">
                     <table id="tbl_bulletin_search">
                        <thead>
                           <th>Reference No.</th>
                           <th>Creator</th>
                           <th>Creation Date</th>
                           <th>Bulletin Title</th>
                           <th>Status</th>
                           <th>Action</th>
                        </thead>
                     </table>
                     </div>

                  </div>
               </div>


         </div>
         </form>
      </div>
   </div>
</div>
</div>
<div class="row">
   <div class="col-md-4">
      <button type="button" class="btn btn-primary" name="">First Bulletin</button>
      <button type="button" class="btn btn-primary" name="">Last Bulletin</button>
   </div>
   <div class="col-md-4">
      <button type="button" class="btn btn-primary" name="">Approve</button>
      <button type="button" class="btn btn-primary" name="">Reject</button>
   </div>
   <div class="col-md-4">
      <button type="button" class="btn btn-primary cls_action" data-action-path="bulletin/bulletin_creation/create_bulletin_view/">Create New</button>
      <button type="button" class="btn btn-primary">Go To Approvers Page</button>
   </div>
</div>
<script type="mustache/x-tmpl" id = "user_table"></script>
<script src="<?=base_url();?>assets/js/bulletin_dashboard.js"></script>