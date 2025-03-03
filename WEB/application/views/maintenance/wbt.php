
<style>
.td_link{
	word-wrap: break-word;
	width:250px; 
	max-width:250px;
}
</style>
<link href="<?=base_url();?>assets/css/rfx.css" rel="stylesheet">
<script src="<?=base_url();?>assets/js/maintenance/wbt.js"></script>


<div id="container_wbt" class="container mycontainer">

	<div class="row">
		<div class="col-md-10"><h4>Web Development Training</h4></div>
		<div class="col-sm-2">
			<button class="btn btn-primary btn_add_wbt pull-right">Add New</button>
		</div>	
	</div>
	
	<div id="b_url" data-base-url="<?php echo base_url().'index.php/maintenance/wbt/'; ?>"></div>

	<div class="row">
		<div class="panel panel-default">
			<div class="panel-body">

				<form id="frm_wbt2" name="frm_wbt2" method="post" class="form-horizontal">
					<div class="form-group">

					<!--
					<span class="col-md-2"><span class="pull-right"><strong>Bus Division :</strong></span></span>
					<div class="col-md-2">	
						<select id="select_bus_division" class="form-control">
							<option value="1">TRADE</option>
							<option value="0">NON-TRADE</option>
						</select>
					</div>
					-->

						<div class="col-md-4">
							<input id="search_wbt" type="text" class="form-control field-required" placeholder="Enter Screen Name or Menu Label...">
						</div>
						<div class="col-md-1">	
							<button id="btn_search_wbt" class="btn btn-primary pull-left" onclick="return false;" >Search</button>
						</div>
					</div>
				
					<div class="panel panel-primary">
						<div class="panel-heading">
							<div class="row">
								<strong class="col-md-10"><h4>Web Development Training</h4></strong>								
							</div>
						</div>

						<table id="tbl_wbt" class="table table-hover">
							<thead>
								<tr>
									<th style="width:30px;">No.</th>
									<th style="width:220px;">Screen Name</th>
									<th>Menu Label</th>
									<th class="td_link">Link</th>
									<th style="width:80px; max-width:80px;">Date Created</th>
									<th style="width:80px;">Action</th>
								</tr>	

							</thead>
							<tbody id="tbl_body">
							</tbody>
						</table>
					</div>	

				</form>
			</div>
		</div>
	</div>

	<!--SAMPLE FILE MODAL-->

	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form id="frm_wbt" name="frm_wbt" method="post" class="form-horizontal" enctype="multipart/form-data">
			
					<div class="modal-header">

						<span class="upload_document" style="display:none;">
							<h4 class="modal-title" id="myModalLabel"></h4>
							<!-- <button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
							<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button> -->
						</span>

<!-- 						<span class="add_document" style="display:none;">
							<h4 class="modal-title" id="myModalLabel">Add Web Development Trainings</h4>
						</span>
						<span class="edit_document" style="display:none;">
							<h4 class="modal-title" id="myModalLabel">Edit Web Development Trainings</h4>
						</span> -->

						<span class="upload_document" style="display:none;">
							<button type="button" id="zoom_image" onclick="zoomimage()">Zoom In</button>
							<button type="button" id="zoom_out_image" onclick="zoomoutimage()">Zoom Out</button>
						</span>

					</div>
					<div class="modal-body">
						<div class="container-fluid">
							<span class="upload_document" style="display:none;">
								<div>
									<input type="file" id="fileupload" name="fileupload" value="upload" placeholder="Upload Scanned Documents" accept=".pdf,.jpg,.jpeg,.png">
									<i>PDF/Jpeg/PNG format max size 2 MB</i>
									<div id="upload_result_wbt"></div>
								</div>
							</span>

							
							<span class="preview_document" style="display:none;">
								<hr>
								<iframe id="imagepreview" class="thumbnail zoom" src="" style="position: relative; height: 100%; width: 100%;"></iframe>
							</span>

							
							
							<span class="document_details" style="display:none;">	
								<form class="form-horizontal">
								<div id = "error_add_wbt" class="alert alert-danger alert-dismissable" style="display:none">				
										<strong>Danger!</strong> 
								</div>

								<input type="hidden" id="wbt_id" value="" class="hidden">
								<input type="hidden" id="wbt_type" value="" class="hidden">
							
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Screen Name </span></label>
									<div class="col-md-7">	
										<input id="screen_name" type="text" class="form-control  field-required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Menu Label </span></label>
									<div class="col-md-7">	
										<input id="menu_label" type="text" class="form-control  field-required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3"><span class="pull-right">Link </span></label>
									<div class="col-md-7">	
										<input id="link" type="text" class="form-control  field-required">
									</div>
								</div>
							</span>


						</div>
					</div>
					<div class="modal-footer">

						<span class="upload_document" style="display:none;">
							<button type="button" class="btn btn-primary" id="btn_upload_wbt" >Upload</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						</span>

						<span class="save_document" style="display:none;">
							<button type="button" class="btn btn-primary" id="btn_save" >Save</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						</span>

					</div>
				</form>
			</div>

		</div>
	</div>

	<!--END OF MODAL-->

	<!--ADD EDIT MODAL-->

	<div id="modal_edit_wbt" class="modal fade">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">	
				<form class="form-horizontal">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<span class="edit_wbt" style="display:none">
							<h4 class="modal-title">Edit Web Development Trainings</h4>
						</span>

						<span class="add_wbt" style="display:none">
							<h4 class="modal-title">Add Web Development Trainings</h4>
						</span>
					</div>
					<div class="modal-body">

						<input type="hidden" id="edit_wbt_id" value="" class="hidden">
						<input type="hidden" id="edit_wbt_type" value="" class="hidden">
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Screen Name </span></label>
							<div class="col-md-7">	
								<input id="edit_screen_name" type="text" class="form-control  field-required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Menu Label </span></label>
							<div class="col-md-7">	
								<input id="edit_menu_label" type="text" class="form-control  field-required">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-3"><span class="pull-right">Link </span></label>
							<div class="col-md-7">	
								<input id="edit_link" type="text" class="form-control  field-required">
							</div>
						</div>
						
						
					</div>
					<div class="modal-footer">
						<div class = "row">				
							<div class="col-md-6"></div>
							<div class="col-md-6">
								<button id="btn_save_wbt" class="btn btn-primary" onclick="return false;">Save</button>
								<button id="btn_close_wbt" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>	
			</div>
		</div>
	</div>

	<!--END OF MODAL-->

</div>


