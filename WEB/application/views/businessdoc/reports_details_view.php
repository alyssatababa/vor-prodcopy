<div class="row">
	<input type="hidden" id="id-item" value="<?php echo $id; ?>">
	<?php if($result): ?>
	<div class="col-md-12">
	  	<div class="row">
		    <div class="col-md-12" id="">		
				<?php $this->load->view('businessdoc/'.$report_template); ?>
			</div>
		</div>
		<div class="clearfix"></div>
		<br />
		<div class="row">
			<div class="col-md-12" id="arch-dl-list">
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Archiving & Download Options</h3>
				  </div>
				  <div class="panel-body">
				    <div class="row">
				    	<div class="col-md-6">
				    		<div class="btn-group " role="group" aria-label="">
				    			<img src="<?=base_url()?>assets/img/csv-icon.png" width="50" class="bd-dl-button" data-dl="csv-details-<?php echo $doctype; ?>"> 
							  	<img src="<?=base_url()?>assets/img/pdf-icon.png" width="50" class="bd-dl-button" data-dl="pdf-details-<?php echo $doctype; ?>"> 
							  	<img src="<?=base_url()?>assets/img/xml-icon.png" width="50" class="bd-dl-button" data-dl="xml-details-<?php echo $doctype; ?>"> 
							  	<img src="<?=base_url()?>assets/img/email-icon.png" width="50" id="email_func" data-doctype="<?php echo $doctype; ?>" data-id="<?php echo $id; ?>" > 
							</div>
				    	</div>
				    	<div class="col-md-6">
				    		<div class="btn-group pull-right" role="group" aria-label="">
							  <img src="<?=base_url()?>/assets/img/print-icon.png" width="50" onclick="printJS('<?php echo $doctype; ?>','<?php echo $id; ?>','<?php echo (isset($comp_id))? $comp_id : ''; ?>')"> 
							</div>
				    	</div>
				    </div>
				  </div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Document Statistics</h3>
				  </div>
				  <div class="panel-body">
					    <div class="col-md-4">
					    	<p>No. of views: <?php echo (@$result->VIEWED == null) ? 0 : $result->VIEWED; ?> &nbsp; <a href="#" class="docs-history" data-doctype="<?php echo $doctype; ?>" data-history="1" >View document history</a>   </p> 
					    	<p>First view: <?php echo @$result->FIRST_VIEW; ?></p>
					    	<p>Last view: <?php echo @$result->LAST_VIEW; ?></p>
					    </div>
					    <div class="col-md-4">
					    	<p>No. of downloads: <?php echo (@$result->DOWNLOADED == null) ? 0 : $result->DOWNLOADED; ?> </p>
					    	<p>First downloaded: <?php echo @$result->FIRST_DOWNLOAD; ?></p>
					    	<p>Last downloaded: <?php echo @$result->LAST_DOWNLOAD; ?></p>
					    </div>
					    <div class="col-md-4">
					    	<p>Views: </p>
					    	<p>Downloads: </p>
					    </div>
				  </div>
				</div>
			</div>
		</div>
 	</div>
 	<?php else: ?>
		<div class="jumbotron">
		  <h1 class="text-center">No details available.</h1>
		</div>
 	<?php endif; ?>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="vDoc_history">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Document History</h4>
      </div>
      <div class="modal-body">
      	<?php if(count($history) > 0): ?>
       	<table class="table" id="history_tb">
       		<thead>
       			<td>Date accessed</td>
       			<td>Accessed by</td>
       			<td>Description</td>
       		</thead>
       		<tbody>
				<span><?php echo 'Document History for <strong> '. $docname .' : ' . $id . '</strong>'; ?>
       			<?php if(is_array($history)):
	       			foreach($history as $ha) :?>
	       			<tr>
	       				<td><?php echo $ha->DATE; ?></td>
	       				<td><?php echo (@$ha->VENDOR_NAME) ? $ha->VENDOR_NAME : '' ; ?></td>
	       				<td><?php 
	       					if($ha->TYPE == 1){
	       						echo 'Viewed document';
	       					} else{
	       						switch ($ha->FILE_TYPE) {
	       							case 1:
	       								echo 'Downloaded CSV file';
	       								break;
	       							case 2:
	       								echo 'Downloaded PDF file';
	       								break;
	       							case 3:
	       								echo 'Downloaded XML file';
	       								break;
	       							case 4:
	       								echo 'Sent email';
	       								break;
	       							case 5:
	       								echo 'Print document';
	       								break;
	       							default:
	       								echo 'Other';
	       								break;
	       						}
	       					}?></td>
	       			</tr>
	       			<?php endforeach; 
	       			else: ?>
       			<tr>
       				<td></td>
       				<td></td>
       				<td></td>
       			</tr>
       		<?php endif; ?>
       		</tbody>
       	</table>
		<?php else: ?>
			<p class="bg-warning">No history yet.</p>
		<?php endif;?>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" id="progress_bd">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <h4 class="text-center">Generating file. Please wait.</h4>
    </div>
  </div>
</div>


<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/businessdoc/business_doc.js'?>");
	
</script>  


<!-- <script>
	$.getScript("<?php #echo base_url().'assets/js/businessdoc/business_doc.js'?>", function() {
     
    });

</script>  -->