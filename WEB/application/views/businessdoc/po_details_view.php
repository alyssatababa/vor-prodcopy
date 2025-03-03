<style>		
.table > thead > tr>td {border:none}		
.borders {border-bottom: 1px solid black; border-top: 1px solid black; border-collapse: collapse;}
#po_details_template > table > tbody > tr > td{border:none}

@media print {
	#doc_title {text-align: center;margin-left:260px;}
	.row{ display: flex; page-break-before: always!important; }
	#po_back{display:none;}

}	
</style>


<div class="row">
	<div class="col-md-12">
	  	<div class="row">
		    <div class="col-md-12" id="po_details_template">	
		    	<?php 
		    	if(!$result): ?>
		    	<div class="row">
		    		<div class="col-md-12"><h2 class="text-center text-danger">No data available</h2> </div>
		    	</div>
		    	<?php else: ?>
		    		<?php if(is_array($result)): ?>
		    		<!-- selected -->	
					<?php foreach($result as $r):?> 
						<!-- header -->
						<div class="row">
					    	<div class="col-md-1"><img src="<?php echo base_url('assets/img/sm-logo.gif');?>" width="100"></div>
					    	<div class="col-md-11"><h2 class="text-center" id="doc_title">PURCHASE ORDER</h2>  </div>	
			    		</div>
			    		<br /><br />
						<table class="table">
					    	<tr style="font-weight:bold;">
					    		<td>Vendor Name:</td>
					    		<td>Company:</td>
					    		<td>Department Name:</td>
					    		<td>PO Number:</td>
							</tr>
							<tr>
								<td><?php echo $r->DETAILS->VENDOR_NAME; ?></td>
								<td><?php echo $r->DETAILS->COMPANY_NAME; ?></td>
								<td><?php echo $r->DETAILS->DEPARTMENT_NAME; ?></td>
								<td><?php echo $r->DETAILS->PO_NUMBER; ?></td>
							</tr>
							<tr style="font-weight:bold;">
					    		<td>Vendor Code:</td>
					    		<td>Location:</td>
					    		<td>Dept/Subdept:</td>
					    		<td>Entry Date:</td>
							</tr>
							<tr>
								<td><?php echo $r->DETAILS->VENDOR_CODE; ?></td>
								<td><?php echo $r->DETAILS->LOCATION; ?></td>
								<td><?php echo $r->DETAILS->DEPT_SUBDEPT; ?></td>
								<td><?php echo $r->DETAILS->ENTRY_DATE; ?></td>
							</tr>
							<tr style="font-weight:bold;">
					    		<td>Type of Tag:</td>
					    		<td></td>
					    		<td>Order Type:</td>
					    		<td>Expected Receipt Date:</td>
							</tr>
							<tr>
								<td><?php echo $r->DETAILS->TYPE_OF_TAG; ?></td>
								<td></td>
								<td><?php echo $r->DETAILS->ORDER_TYPE; ?></td>
								<td><?php echo $r->DETAILS->EXPECTED_RECEIPT_DATE; ?></td>
							</tr>
							<tr style="font-weight:bold;">
					    		<td>Terms and Discount:</td>
					    		<td></td>
					    		<td>Label:</td>
					    		<td>Cancel Date:</td>
							</tr>
							<tr>
								<td><?php echo $r->DETAILS->TERMS_DISCOUNT; ?></td>
								<td></td>
								<td><?php echo $r->DETAILS->LABEL; ?></td>
								<td><?php echo $r->DETAILS->CANCEL_DATE; ?></td>
							</tr>
						</table>
						<table class="table">
							<thead>
								<tr class = "borders">
									<td class="text-left">Class/<br>S Class</td>
									<td class="text-center">SKU</td>
									<td class="text-center">Description <br>Discounts & Net Buy Cost</td>
									<td class="text-right">Vendor Part #</td>
									<td class="text-right">UPC</td>
									<td class="text-center">Buy <br>Qty</td>
									<td class="text-center">Buy Cost <br>Ext. Cost</td>
									<td class="text-center">Buy <br>U/M</td>
									<td class="text-center">Unit <br>Retail</td>
									<td class="text-center">Sell <br>U/M</td>
								</tr>
							</thead>
	 						<tbody>
							  <?php 
							  $total_unit_retail = 0; $total_amount = 0; $total_po_amount = 0;
							  foreach($r->BODY as $body): ?>
							  	<tr>
							  		<td><?php echo $body->CLASS_SCLASS; ?></td>
							  		<td><?php echo $body->SKU; ?></td>
							  		<td><?php echo $body->DESCRIPTION; ?></td>
							  		<td><?php echo $body->VENDOR_PART_NO; ?></td>
							  		<td><?php echo $body->UPC; ?></td>
							  		<td><?php $total_unit_retail = $total_unit_retail + $body->BUY_QTY; echo $body->BUY_QTY; ?></td>
							  		<td class="text-right"><?php $total_amount = $total_amount + $body->EXT_COST; echo ($body->BUY_COST) ? number_format($body->BUY_COST,2) : ''; ?><br /><?php echo ($body->EXT_COST) ?number_format($body->EXT_COST,2) : '';?></td>
							  		<td><?php echo $body->BUY_UM; ?></td>
							  		<td class="text-right"><?php echo $body->UNIT_RETAIL; ?></td>
							  		<td class="text-right"><?php echo $body->SEL_UM; ?></td>
							  	</tr>
							  	<?php endforeach; ?>
							  	<tr>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td style="border-bottom: 1px solid black;">* = This SKU is Discounted</td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                  	</tr>
			                  	<tr>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td>Total:</td>
			                      <td></td>
			                      <td style="border-bottom: 1px solid black;text-align:center"><?php echo number_format($r->DETAILS->TOTAL_PO_AMOUNT,2); ?></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;text-align:center;"><?php echo ($total_unit_retail == 0) ? '' : number_format($total_unit_retail,2); ?></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                  	</tr>
							  </tbody>
						</table>
						<table class="table">
							<tr style="font-weight:bold">
								<td>P.O Notes:</td>
								<td></td>
							</tr>
					    	<tr >
					    		<td></td>
					    		<td></td>
							</tr>
							<tr style="font-weight:bold">
								<td>Receiver Notes:</td>
								<td></td>
								<td>Box Counting:</td>
							</tr>
							<tr >
								<td><?php echo $r->DETAILS->RECEIVER_NOTES; ?></td>
								<td></td>
								<td></td>
							</tr>
							<tr style="font-weight:bold">
								<td>Ordered By:</td>
								<td>Approved By:</td>
								<td>Verified By:</td>
								<td>Received By:</td>
								<td>Date Received:</td>
								<td>Inv. No.:</td>
							</tr>
							<tr>
								<td><?php echo $r->DETAILS->ORDERED_BY; ?></td>
								<td><?php echo $r->DETAILS->APPROVED_BY; ?> </td>
								<td><?php echo $r->DETAILS->VERIFIED_BY; ?> </td>
								<td><?php echo $r->DETAILS->RECEIVED_BY; ?> </td>
								<td><?php echo $r->DETAILS->DATE_RECEIVED; ?> </td>
								<td><?php echo $r->DETAILS->INV_NO; ?> </td>
							</tr>
						</table>	

					<?php endforeach; ?>
					<!-- details -->	
					<?php else: ?>	
						<div class="row">
					    	<div class="col-md-1"><img src="<?php echo base_url('assets/img/sm-logo.gif');?>" width="100"></div>
					    	<div class="col-md-11" style="font-weight:bold!important" ><h2 class="text-center" id="doc_title">PURCHASE ORDER</h2>  </div>	
			    		</div>
			    		<br /><br />
			    		<a href="#" id="po_back" > << Back to PO Summary </a>
						<table class="table" style = "border:none!important;">
					    	<tr style="font-weight:bold">
					    		<td>Vendor Name:</td>
					    		<td>Company:</td>
					    		<td>Department Name:</td>
					    		<td>PO Number:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->VENDOR_NAME; ?></td>
								<td><?php echo $result->HDR->COMPANY_NAME; ?></td>
								<td><?php echo $result->HDR->DEPARTMENT_NAME; ?></td>
								<td><?php echo $result->HDR->PO_NUMBER; ?></td>
							</tr>
							<tr style="font-weight:bold">
					    		<td>Vendor Code:</td>
					    		<td>Location:</td>
					    		<td>Dept/Subdept:</td>
					    		<td>Entry Date:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->VENDOR_CODE; ?></td>
								<td><?php echo $result->HDR->LOCATION; ?></td>
								<td><?php echo $result->HDR->DEPT_SUBDEPT; ?></td>
								<td><?php echo $result->HDR->ENTRY_DATE; ?></td>
							</tr>
							<tr style="font-weight:bold">
					    		<td>Type of Tag:</td>
					    		<td></td>
					    		<td>Order Type:</td>
					    		<td>Expected Receipt Date:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->TYPE_OF_TAG; ?></td>
								<td></td>
								<td><?php echo $result->HDR->ORDER_TYPE; ?></td>
								<td><?php echo $result->HDR->EXPECTED_RECEIPT_DATE; ?></td>
							</tr>
							<tr style="font-weight:bold">
					    		<td>Terms and Discount:</td>
					    		<td></td>
					    		<td>Label:</td>
					    		<td>Cancel Date:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->TERMS_DISCOUNT; ?></td>
								<td></td>
								<td><?php echo $result->HDR->LABEL; ?></td>
								<td><?php echo $result->HDR->CANCEL_DATE; ?></td>
							</tr>
						</table>
						<table class="table ">
							<thead>
								<tr class = "borders">
									<td class="text-left">Class/<br>S Class</td>
									<td class="text-center">SKU</td>
									<td class="text-center">Description <br>Discounts & Net Buy Cost</td>
									<td class="text-right">Vendor Part #</td>
									<td class="text-right">UPC</td>
									<td class="text-center">Buy <br>Qty</td>
									<td class="text-center">Buy Cost <br>Ext. Cost</td>
									<td class="text-center">Buy <br>U/M</td>
									<td class="text-center">Unit <br>Retail</td>
									<td class="text-center">Sell <br>U/M</td>
								</tr>
							</thead>
		 					<tbody>
								<?php $total_unit_retail = 0; $total_amount = 0; $total_po_amount = 0;
								foreach($result->BODY as $body): ?>
								  	<tr>
								  		<td><?php echo $body->CLASS_SCLASS; ?></td>
								  		<td><?php echo $body->SKU; ?></td>
								  		<td><?php echo $body->DESCRIPTION; ?></td>
								  		<td><?php echo $body->VENDOR_PART_NO; ?></td>
								  		<td><?php echo $body->UPC; ?></td>
								  		<td><?php $total_unit_retail = $total_unit_retail + $body->BUY_QTY;  echo $body->BUY_QTY;?></td>
								  		<td class="text-right"><?php $total_amount = $total_amount + $body->EXT_COST; echo ($body->BUY_COST) ? number_format($body->BUY_COST,2) : '' ; ?><br /><?php echo ($body->EXT_COST) ? number_format($body->EXT_COST,2) : ''; ?></td>
								  		<td><?php echo $body->BUY_UM; ?></td>
								  		<td class="text-right"><?php echo $body->UNIT_RETAIL; ?></td>
								  		<td class="text-center"><?php echo $body->SEL_UM; ?></td>
							  		</tr>
							  	<?php endforeach; ?>
						  		<tr>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td style="border-bottom: 1px solid black;">* = This SKU is Discounted</td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                  	</tr>
			                  	<tr>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td></td>
			                      <td>Total:</td>
			                      <td></td>
			                      <td style="border-bottom: 1px solid black;text-align:center"><?php echo number_format($result->HDR->TOTAL_PO_AMOUNT,2); ?></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                      <td style="border-bottom: 1px solid black;text-align:center;"><?php echo ($total_unit_retail == 0) ? '' : number_format($total_unit_retail,2); ?></td>
			                      <td style="border-bottom: 1px solid black;"></td>
			                  	</tr>
							  </tbody>
						</table>

						<br /><br />
						<table class="table">
							<tr style="font-weight:bold">
								<td>P.O Notes:</td>
								<td></td>
							</tr>
					    	<tr >
					    		<td></td>
					    		<td></td>
							</tr>
							<tr style="font-weight:bold">
								<td>Receiver Notes:</td>
								<td></td>
								<td>Box Counting:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->RECEIVER_NOTES; ?></td>
								<td></td>
								<td></td>
							</tr>
							<tr style="font-weight:bold">
								<td>Ordered By:</td>
								<td>Approved By:</td>
								<td>Verified By:</td>
								<td>Received By:</td>
								<td>Date Received:</td>
								<td>Inv. No.:</td>
							</tr>
							<tr>
								<td><?php echo $result->HDR->ORDERED_BY; ?></td>
								<td><?php echo $result->HDR->APPROVED_BY; ?></td>
								<td><?php echo $result->HDR->VERIFIED_BY; ?></td>
								<td><?php echo $result->HDR->RECEIVED_BY; ?></td>
								<td><?php echo $result->HDR->DATE_RECEIVED; ?></td>
								<td><?php echo $result->HDR->INV_NO; ?></td>
							</tr>
						</table>
			    	<?php endif;?>	
				<?php endif; ?>	
			</div>
		</div>
 </div>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		$('#po_back').on('click',function(){
			$('.breadcrumb li:last-child').children().click();
		});
	});
</script>