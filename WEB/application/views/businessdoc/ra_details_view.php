<style>
.table > thead > tr>td {border:none;}
@media print {
	.table > thead > tr>td { border:none; width: 100%}
	#doc_title {text-align: center;margin-left:260px;}
	.row	{ display: flex; page-break-before: always!important; }
	#ra_details_template > table > thead > tr {
		border:none;
	}
	#ra_back{display:none;}
}

</style>

<div class="row breakme">
	<div class="col-md-12">
	  	<div class="row">
		    <div class="col-md-12" id="ra_details_template">
		    	<?php 
		    	if(!$result): ?>
		    	<div class="row">
		    		<div class="col-md-12"><h2 class="text-center text-danger">No data available</h2> </div>
		    	</div>
		    	<?php else: ?>
		    		<?php if(is_array($result)): ?>
					<?php foreach($result as $r):?>
					<div class="row">
				    	<div class="col-md-1"><img src="<?php echo base_url('assets/img/sm-logo.gif');?>" width="100"></div>
				    	<div class="col-md-11"><h2 class="text-center" id="doc_title">REMITTANCE ADVICE</h2>  </div>	
			    	</div>
			    	<br />
					<table class="table print-css">
						<thead>
					    	<tr >
					    		<td>DATE</td>
					    		<td>: <?php echo $r->DETAILS->PROCESSING_DATE; ?></td>
					   
					    		<td>RA Number</td>
					    		<td >: <?php echo $r->DETAILS->REF_NO; ?></td>
							</tr>

							<tr >
					    		<td>PAYEE</td>
					    		<td >: <?php echo $r->DETAILS->PAYEE; ?></td>

					    		<td>VENDOR CODE</td>
					    		<td>: <?php echo $r->DETAILS->VENDOR_CODE; ?></td>
					    	</tr>
					    	<tr >
					    		<td>BANK/Payment Voucher Number</td>
					    		<td>: <?php echo ($r->DETAILS->BANK_PVN)? $r->DETAILS->BANK_PVN : 'No Bank Name'; ?> <?php echo $r->DETAILS->CHECK_NO; ?></td>

					    		<td>AMOUNT</td>
					    		<td>: <?php echo number_format(@$r->DETAILS->TOTAL_AMOUNT,2); ?> </td>
					    	</tr>
					   		<tr>
					    		<td>PAYMENT DATE</td>
					    		<td>: <?php echo $r->DETAILS->PAYMENT_DATE; ?></td>
					    		<td></td>
							</tr>
						<thead>
			    	</table>
			    
					<table class="table ">
						<thead>
							<tr style="border-top:1px solid black;border-bottom:1px solid black;">
								<td class="text-left">COMPANY</td>
								<td class="text-left">BRANCH</td>
								<td class="text-left">PARTICULARS</td>
								<td class="text-right">AMOUNT</td>
								<td class="text-right">TOTAL</td>
							</tr>
						</thead>
 						<tbody>
						  <?php foreach($r->BODY as $body): ?>
						  	<tr>
						  		<?php if($body->DISPLAY_COMPANY == 1):?>
							  		<td><?php echo $body->COMPANY; ?></td>
							  		<td><?php echo ($body->DOC_TYPE_ID == 63) ? '' : $body->BRANCH_NAME; ?></td>
							  		<td><?php echo ($body->DOC_TYPE_ID == 63) ? '' : $body->PARTICULARS; ?></td>
							  		<td class="text-right" style="border-top:1px #000 solid;"><?php echo ($body->DOC_TYPE_ID == 0) ? '' : number_format($body->DOC_AMOUNT,2); ?></td>
                  					<td class="text-right"><?php echo ($body->DISPLAY_TOTAL == 1) ? number_format($body->TOTAL_DOC_AMT,2) : ''; ?></td>
						  		<?php else: ?>
						  			<td></td>
							  		<td><?php echo $body->BRANCH_NAME; ?></td>
							  		<td><?php echo $body->PARTICULARS;?></td>
							  		<td class="text-right"><?php echo ($body->DOC_TYPE_ID == 0) ? '' : number_format($body->DOC_AMOUNT,2); ?></td>
                  					<td class="text-right"><?php echo ($body->DISPLAY_TOTAL == 1) ? number_format($body->TOTAL_DOC_AMT,2) : ''; ?></td>
						  		<?php endif; ?>
						  	</tr>
						  	<?php endforeach; ?>
						  	<tr>
			                  <td></td>
			                  <td></td>
			                  <td></td>
			                  <td class="text-right" style="font-weight: bold;">TOTAL AMOUNT PAID</td>
			                  <td class="text-right" ><?php echo number_format($r->DETAILS->TOTAL_AMOUNT,2); ?></td>
			               </tr>
						</tbody>
					</table>				
					<?php endforeach; ?>	
					<?php else: ?>	
					<div class="row">
				    	<div class="col-md-1"><img src="<?php echo base_url('assets/img/sm-logo.gif');?>" width="100"></div>
				    	<div class="col-md-11"><h2 class="text-center" id="doc_title">REMITTANCE ADVICE</h2>  </div>	
			    	</div>
			    	<br /><br />
					<a href="#" id="ra_back" > << Back to RA Summary </a>
					<table class="table print-css">
	
						<thead>
							<tr>
					    		<td >DATE</td>
					    		<td >: <?php echo $result->PROCESSING_DATE; ?></td>
					    		<td >RA Number</td>
					    		<td >: <?php echo $result->REF_NO; ?></td>
					    	</tr>
					    	<tr>
					    		<td>PAYEE</td>
					    		<td>: <?php echo $result->PAYEE; ?></td>

					    		<td>VENDOR CODE</td>
					    		<td>: <?php echo $result->VENDOR_CODE; ?></td>
					    	</tr>
					    	<tr>
					    		<td>BANK/Payment Voucher Number</td>
					    		<td>: <?php echo ($result->BANK_PVN)? $result->BANK_PVN : 'No Bank Name'; ?> <a href="#" data-action-path="businessdoc/reports/ca_details/<?php echo $result->CHECK_NO; ?>" class="cls_action" data-crumb-text=""><?php echo $result->CHECK_NO; ?></a></td>

					    		<td>AMOUNT</td>
					    		<td >: <?php echo number_format($result->TOTAL_AMOUNT,2); ?> </td>
					    	</tr>
					    	<tr>
					    		<td>PAYMENT DATE</td>
					    		<td>: <?php echo $result->PAYMENT_DATE; ?></td>
					    		<td></td>
					    		<td></td>
					    	</tr>
			    		</thead>			
			    	</table>

			    	<br />
					<table class="table ">
						<thead>
							<tr style="border-top:1px solid black;border-bottom:1px solid black;">
								<td class="text-left">COMPANY</td>
								<td class="text-left">BRANCH</td>
								<td class="text-left">PARTICULARS</td>
								<td class="text-right" width="150">AMOUNT</td>
								<td class="text-right">TOTAL</td>
							</tr>
						</thead>
						  <tbody>
						  	<?php foreach($result->BODY as $body): ?>
						  	<tr>
						  		<?php if($body->DISPLAY_COMPANY == 1):?>
							  		<td><?php echo $body->COMPANY; ?></td>
							  		<td><?php echo ($body->DOC_TYPE_ID == 63) ? '' : $body->BRANCH_NAME; ?></td>
							  		<td><?php echo ($body->DOC_TYPE_ID == 63) ? '' : $body->PARTICULARS; ?></td>
							  		<td class="text-right" style="border-top:1px #000 solid;"><?php echo ($body->DOC_TYPE_ID == 0) ? '' : number_format($body->DOC_AMOUNT,2); ?></td>
                  					<td  class="text-right"><?php echo ($body->DISPLAY_TOTAL == 1) ? number_format($body->TOTAL_DOC_AMT,2) : ''; ?></td>
						  		<?php else: ?>
						  			<td></td>
							  		<td><?php echo $body->BRANCH_NAME; ?></td>
							  		<td><?php echo $body->PARTICULARS;?></td>
							  		<td class="text-right"><?php echo ($body->DOC_TYPE_ID == 0) ? '' : number_format($body->DOC_AMOUNT,2); ?></td>
                  					<td  class="text-right"><?php echo ($body->DISPLAY_TOTAL == 1) ? number_format($body->TOTAL_DOC_AMT,2) : ''; ?></td>
						  		<?php endif; ?>
						  	</tr>
						  	<?php endforeach; ?>
						  	<tr>
			                  <td></td>
			                  <td></td>
			                  <td></td>
			                  <td  class="text-right"style="font-weight: bold;">TOTAL AMOUNT PAID</td>
			                  <td  class="text-right"><?php echo number_format($result->TOTAL_AMOUNT,2); ?></td>
			               </tr>
						  </tbody>
					</table>
			    	<?php endif;?>	
				<?php endif; ?>	
			</div>
		</div>
 </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#ra_back').on('click',function(){
			$('.breadcrumb li:last-child').children().click();
		});
	});
</script>