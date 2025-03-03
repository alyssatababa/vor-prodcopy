<!-- Create New -->
<div class="modal fade" id="addNewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add New</h4>
      </div>
      <div class="modal-body">
       <div class="row">
      <div class="col-sm-4">
        <label>Name : </label><input type="text" class="form-control" name="name" required>
      </div>  
      <div class = "col-sm-8">
        <label>Description : </label><textarea class="form-control" rows="5" name="desc" required></textarea>
      </div>      
    </div>  
      </div>
      <div class="modal-footer">
     	 <button type="button" class="btn btn-success" id="addNewParam">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    $("#addNewParam").click(function(event) {
       $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>index.php/maintenance/vendorparameters/add_param",
          dataType: 'json',
          data: {name: $("input[name=desc]").val(), desc: $("input[name=desc]").val()},
          success: function(res) {
            console.log(res);
          }
        });
     });
  });


</script>
