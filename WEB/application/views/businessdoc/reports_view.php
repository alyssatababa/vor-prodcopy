<div class="row" id="bd_reports">
	<div class="col-md-12">
	<div class="panel panel-primary">
	  <div class="panel-heading">
	    <h3 class="panel-title" style= "display: inline-block; ">  <?php echo $title;?></h3> 
	    <?php if($type < 90):?>
			<span onclick="refreshBut()" id="refresh-docu" data-option="live" class= "pull-right panel-title" style= "display: inline-block; float: right; cursor: pointer; cursor: hand;" >
					<img src="<?=base_url()?>/assets/img/refresh.png" width="25">
					Refresh
			</span>
		<?php endif; ?>
	  </div>
	  <div class="panel-body">
	  	<?php if($type < 90 ):?>
	  	<div class="row">
		    <div class="col-md-12">
		    	<div class="accordion" role="tablist" aria-multiselectable="true">
		    		<div class="panel panel-default">
					  <div class="panel-heading" role="tab" id="headingOne">
					  	<div class="panel-title">
					  		<div class="row">
		    				<div class="col-md-10">
							  	Current View: <span class="archive-link" data-crvt="live">Live</span> <img class="archive-link" data-crvi="live" src="<?=base_url()?>/assets/img/live-icon.png" width="20"> 
							  	<?php if($type != 3): ?>
							  	[<a href="#" class="archive-link" data-archive="<?php echo $type ; ?>"> View <span class="archive-link" data-vat="arc">Archive</span> <img class="archive-link" data-vai="arc" src="<?=base_url()?>/assets/img/archive-icon.png" width="20"></a>]
							  	<?php endif; ?>
							</div>  	
							<div class="col-md-2">  	
							  	<div>
							  		 <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" id="bd-filter-expand" >
							          Click here to minimize
							        </a>
							  	</div>
							</div>
						  	</div>
						  	</div>
						</div>
					  <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
						  <div class="panel-body">
						  	<?php $this->load->view('businessdoc/'.$header);?>
						  </div>
					  </div>
					</div>
		    	</div>
			</div>
		</div>
		<div class="row">
		    <div class="col-md-12">
		    	<div class="panel panel-default">
					<div class="panel-body" id="report_div">
						<table class="table table-striped" id="reports_table">
							<thead>
								<tr>
									<?php 
										if($type != 3){
											echo '<th class="text-center"><input type="checkbox" data-data="checkbox" id="checkAll" /></th>';
										}
										if(isset($archive)){
											unset($columns['STATUS']);
										}
										foreach($columns as $col): 
									?>
										<th class="text-center"><?php echo $col; ?></th>
									<?php endforeach;?>	
								</tr>
							</thead>
							<tbody></tbody>
						</table>
						<br>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" id="arch-dl-list">
				<div class="panel panel-default">
				  <div class="panel-heading">
				    <h3 class="panel-title">Archiving & Download Options</h3>
				  </div>
				  <div class="panel-body">
				    <div class="row">
				    	<div class="col-md-6">
				    		<?php if($type != 3): ?>
					    		<div class="btn-group " role="group" aria-label="">
					    		  <img src="<?=base_url()?>/assets/img/archive-icon.png" width="50" id="bd-arch" data-doctype="<?php echo $type; ?>" data-arc-option="live" alt="Archive">
								  <img src="<?=base_url()?>/assets/img/pdf-icon.png" width="50" class="bd-dl-button" data-dl="pdf-selected-<?php echo $type; ?>" data-dl-option="live" data-disable="">  
								  <img src="<?=base_url()?>/assets/img/csv-icon.png" width="50" class="bd-dl-button" data-dl="csv-selected-<?php echo $type; ?>" data-dl-option="live" data-disable=""> 
								  <img src="<?=base_url()?>/assets/img/xml-icon.png" width="50" class="bd-dl-button" data-dl="xml-selected-<?php echo $type; ?>" data-dl-option="live" data-disable=""> 
								  <img src="<?=base_url()?>/assets/img/print-icon.png" width="50"  onclick="printJS('<?php echo $type; ?>', 'selected')"> 
								</div>
								<div class="clearfix"></div>
								<p>Downloading of specific business documents, or batch of
								specific business documents. Use the checkboxes to make a 
								selection.</p>
							<?php endif; ?>
				    	</div>
				    	<div class="col-md-6">
				    		<div class="btn-group pull-right" role="group" aria-label="">
							  <img src="<?=base_url()?>/assets/img/pdf-icon.png" width="50" class="bd-dl-button" data-dl="pdf-summary-<?php echo $type; ?>" data-dl-option="live"> 
							  <img src="<?=base_url()?>/assets/img/csv-icon.png" width="50" class="bd-dl-button" data-dl="csv-summary-<?php echo $type; ?>" data-dl-option="live" > 
							  <img src="<?=base_url()?>/assets/img/excel-icon.png" width="38" class="bd-dl-button" data-dl="xls-summary-<?php echo $type; ?>" data-dl-option="live" > 
							</div>
							<div class="clearfix"></div>
							<p class="pull-right col-md-6">Downloading the summary table of all business documents/messages in
							these search results (list only).</p>
				    	</div>
				    </div>
				  </div>
				</div>
			</div>
		</div>
		<?php elseif($type == 98): ?>
			<div class="jumbotron">
			  <h1 class="text-center">You dont have permission to access this page!</h1>
			</div>
		<?php elseif($type == 97): ?>
			<div class="jumbotron">
			  <h1 class="text-center">No documents available!</h1>
			</div>
		<?php elseif($type == 96): ?>
			<div class="jumbotron">
				
			  <h1 class="text-center">Under maintenance of our developers.</h1>
			  <h2 class="text-center" id="demo"></h2>
			</div>			
		<?php else: ?>
			<div class="jumbotron">
			  <h1 class="text-center">Page is currently under construction.</h1>
			</div>
		<?php endif; ?>
	  </div>
		 
	</div>
	</div>
 </div>


<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="" data-backdrop="static" data-keyboard="false" id="bd_modal">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
        <div class="modal-body">
           <h4 class="text-center">Generating file. Please wait.</h4>
        </div>
       	<div class="modal-footer hidden" id="bd_modal_conf">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <a class="btn btn-danger btn-ok" id="bd_modal_yes">Yes</a>
	    </div>
    </div>

  </div>
</div>


<script type="text/javascript">
	$.getScript("<?php echo base_url().'assets/js/businessdoc/business_doc.js'?>");
	$(document).ready(function(){
		$('#reports_table').on('draw.dt', function (e, settings,data) {
			ajaxuri = settings.ajax;
			var result=ajaxuri.url.split("/").pop();
			setTimeout(function(){
				let cat;
				let sesop;
				switch ('<?php echo $type; ?>') {
					case '1':
						cat = 'po_dtables';
						sesop = 'arc-po';
						break;
					case '2':
						cat = 'ca_dtables';
						sesop = 'arc-ca';
						break;
					case '5':
						cat = 'ra_dtables';
						sesop = 'arc-ra';
						break;
				}
				if('<?php echo $this->session->userdata('bd_backses'); ?>' === sesop && result === cat){
					<?php $this->session->unset_userdata('bd_backses'); ?>
					$('a.archive-link').click();
					return;
				}
			},500);
	    	
		});
		
	});

</script>  