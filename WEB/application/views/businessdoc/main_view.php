<div class="row">
	<div class="col-md-12">
	<div class="panel panel-default">
	  <div class="panel-heading">
	    <h3 class="panel-title">Business Documents</h3>
	  </div>
	  <div class="panel-body">
	  		<div class="row">
	  			<div class="col-md-12">
					<div class="col-xs-6 col-md-6">
						<a href="#" data-action-path="businessdoc/reports/purchase_order" class="cls_action" data-crumb-text="Purchase Order">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Purchase Order</b> <?php if(@$notifications->PO > 0): ?><span class="badge badge-error"><?php echo $notifications->PO; ?></span><?php endif; ?></h4> 
						  	</div>
						</div>
						</a>
					</div>
					<div class="col-xs-6 col-md-6">
						<a href="#" data-action-path="businessdoc/reports/credit_advice" class="cls_action" data-crumb-text="Credit Advice">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Credit Advice</b> <?php if(@$notifications->CA > 0): ?><span class="badge badge-error"><?php echo $notifications->CA; ?></span><?php endif; ?></h4> 
						  	</div>
						</div>
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="col-xs-6 col-md-6">
						<a href="#" data-action-path="businessdoc/reports/debit_credit_memo" class="cls_action" data-crumb-text="Debit Memo Credit Memo">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Debit Memo / Credit Memo</b> <?php #if($notifications->DMCM > 0): ?><span class="badge badge-error"><?php #echo $notifications->DMCM; ?></span><?php #endif; ?></h4> 
						  	</div>
						</div>
						</a>
					</div>
					<!-- <div class="col-xs-6 col-md-4">
						<a href="#" data-action-path="businessdoc/reports/purchase_order" class="cls_action" data-crumb-text="Return to Vendor">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Return to Vendor</b> <span class="badge badge-error"></span></h4> 
						  	</div>
						</div>
						</a>
					</div> -->
					<div class="col-xs-6 col-md-6">
						<a href="#" data-action-path="businessdoc/reports/remittance_advice" class="cls_action" data-crumb-text="Remittance Advice">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Remittance Advice</b> <?php if(@$notifications->RA > 0): ?><span class="badge badge-error"><?php echo $notifications->RA; ?></span><?php endif; ?></h4> 
						  	</div>
						</div>
						</a>
					</div>
					<!-- <div class="col-xs-6 col-md-4">
						<a href="#" data-action-path="businessdoc/reports/purchase_order" class="cls_action" data-crumb-text="Receiving Confirmation Report">
						<div class="card">
						  	<img src="<?=base_url()?>/assets/img/reports_icon.png" alt="Reports" width="50" class="center-block">
						  	<div class="card-details text-center">
						  		<h4><b>Receiving Confirmation Report</b> <span class="badge badge-error"></span></h4> 
						  	</div>
						</div>
						</a>
					</div> -->
				</div>
			</div>
	  </div>
	</div>
 	</div>
</div>



    