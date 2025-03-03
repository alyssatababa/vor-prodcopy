<style>
@media print {
  div.row{ 
  width:100%; 
  height:100%;
  page-break-after:always;
  }
  	#ca_back{display:none;}
  	
	}

</style>
<div class="row">
	<div class="col-md-12">
		<?php if(!$result): ?>
    	<div class="row">
    		<div class="col-md-12"><h2 class="text-center text-danger">No data available</h2> </div>
    	</div>
    	<?php else: ?>
    		<?php if(is_array($result)): ?>
		  		<?php foreach($result as $r): ?>
				
		  			<div class="row">
					    <div class="col-md-12" id="ca_details_template">	
					    	<h3 class="text-center">BDO CREDIT ACKNOWLEDGEMENT</h3>    	
							<table class="table">
							  <tbody>
							  	<tr>
							  		<td><h5>VENDOR NAME:</h5> </td>
							  		<td><?php echo $r->VENDOR_NAME; ?></td>
							  		<td></td>
							  		<td></td>
							  	</tr>
							  	<tr>
							  		<td><h5>PAYMENT VOUCHER NUMBER:</h5></td>
							  		<td><?php echo $r->CHECK_NO; ?></td>
							  		<td></td>
							  		<td></td>
							  	</tr>
							  	<tr>
							  		<td>We have credited your Account No.</td>
							  		<td><?php echo $r->ACCOUNT_NO; ?></td>
							  		<td></td>
							  		<td></td>
							  	</tr>
							  	<tr>
							  		<td>for payment of <b><?php echo $r->PAYOR_NAME;?></b></td>
							  		<td>for account/invoices due.</td>
							  		<td></td>
							  		<td></td>
							  	</tr>
							  	<tr>
							  		<td><h5>AMOUNT:</h5></td>
							  		<td><?php echo number_format($r->AMOUNT,2); ?></td>
							  		<td><h5>CREDITING DATE:</h5></td>
							  		<td><?php echo date_format(new DateTime($r->CM_DATE),"m/d/Y") ; ?></td>
							  	</tr>
							  </tbody>
							</table>
						</div>
					</div>
					
					<div class = "break"> </div>
					
				<?php endforeach; ?>
			<?php else:?>
				<div class="row">
				    <div class="col-md-12" id="ca_details_template">	
				    	<h3 class="text-center">BDO CREDIT ACKNOWLEDGEMENT</h3>
						<table class="table">
						  <tbody>
						  	<tr>
						  		<td><h5>VENDOR NAME:</h5> </td>
						  		<td><?php echo @$result->VENDOR_NAME; ?></td>
						  		<td></td>
						  		<td></td>
						  	</tr>
						  	<tr>
						  		<td><h5>PAYMENT VOUCHER NUMBER:</h5></td>
						  		<td><?php echo $id; ?></td>
						  		<td></td>
						  		<td></td>
						  	</tr>
						  	<tr>
						  		<td>We have credited your Account No.</td>
						  		<td><?php echo @$result->ACCOUNT_NO; ?></td>
						  		<td></td>
						  		<td></td>
						  	</tr>
						  	<tr>
						  		<td>for payment of <b><?php echo @$result->PAYOR_NAME;?></b></td>
						  		<td>for account/invoices due.</td>
						  		<td></td>
						  		<td></td>
						  	</tr>
						  	<tr>
						  		<td><h5>AMOUNT:</h5></td>
						  		<td><?php echo number_format(@$result->AMOUNT,2); ?></td>
						  		<td><h5>CREDITING DATE:</h5></td>
						  		<td><?php echo date_format(new DateTime(@$result->CM_DATE),"m/d/Y") ; ?></td>
						  	</tr>
						  </tbody>
						</table>
						<a href="#" id="ca_back" > << Back to CA Summary </a>
					</div>
				</div>
			<?php endif; ?>	
		<?php endif; ?>
 </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#ca_back').on('click',function(){
			$('.breadcrumb li:last-child').children().click();
		});
	});
</script>