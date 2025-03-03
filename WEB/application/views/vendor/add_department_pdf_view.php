<!DOCTYPE html>
<html>
<head>
	<title>Add Department View</title>
	<link href="<?=base_url()?>assets/dist/css/bootstrap.min.css" rel="stylesheet">
	<script>
		setTimeout(function()
				 {
				   window.print();
				   self.close();
				 } , 300);
	</script>
	<style type="text/css">
		table tr td{
			padding: 10px !important;
		}
		@page  
		{ 
			margin-top: 0.5in;  
			margin-right: 0.5in;  
			margin-left: 0.5in;  
			margin-bottom: 0.5in;  
		}
		body{
			margin:0;
		}
	</style>
	<style>
		.border{border:1px solid gray;padding:10px 15px;}
		.lbl{font-size:12px;}
	</style>
</head>
<body>
	<?php 
		$vendor_type = '';
		if($rs->vendor_type == 1){
			$vendor_type = 'Outright';
		}elseif($rs->vendor_type == 2){
			$vendor_type = 'Store Consignor';
		}
		
		$terms = 0;
		foreach ($payment_terms as $key => $value) {
			if($termspayment == $key){
				$terms = $value;
			}
		}
		
		$existing_category = '';
		for($a=0;$a<$rs->category_count; $a++){
			if($a==0){
				$existing_category = $rs->category[$a]->CATEGORY_NAME;
			}else{
				$existing_category .= ", ".$rs->category[$a]->CATEGORY_NAME;
			}
		}
		
		$add_category = '';
		if($rs->add_category_count > 0){
			for($b=0;$b<$rs->add_category_count; $b++){
				if($b==0){
					$add_category = $rs->add_category[$b]->CATEGORY_NAME;
				}else{
					$add_category .= ", ".$rs->add_category[$b]->CATEGORY_NAME;
				}
			}
		}else{
			$add_category = "&nbsp";
		}
		
		$add_sub_category = '';
		if($rs->add_sub_category_count > 0){
			for($c=0;$c<$rs->add_sub_category_count; $c++){
				if($c==0){
					$add_sub_category = $rs->add_sub_category[$c]->SUB_CATEGORY_NAME;
				}else{
					$add_sub_category .= ", ".$rs->add_sub_category[$c]->SUB_CATEGORY_NAME;
				}
			}
		}else{
			$add_sub_category = "&nbsp";
		}
		
		$brand = '';
		if($rs->brand_count > 0){
			//print_r($rs->brand);
			for($d=0;$d<$rs->brand_count; $d++){
				if($rs->brand[$d]->BRAND_NAME == ''){
					$brand_name = "&nbsp;";
				}else{
					$brand_name = $rs->brand[$d]->BRAND_NAME;
				}
				
				if($d==0){
					$brand = $brand_name;
				}else{
					$brand .= ", ".$brand_name;
				}
			}
		}else{
			$brand = "&nbsp";
		}
		
		$arr = [
			"VENDOR NAME" => $rs->vendor_name,
			"VENDOR CODE" => $rs->vendor_code,
			"VENDOR TYPE" => $vendor_type,
			"EXISTING DEPARTMENT" => $existing_category,
			"ADD TO DEPARTMENT" => $add_category,
			"SUB-DEPARTMENT" => $add_sub_category,
			"BRAND" => $brand,
		];
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-12 text-center" style="margin:20px 0px;">
			<img src="<?php echo base_url().'assets/img/sm-logo.gif'?>" style="width: 120px;"/>
				<h3>REQUEST FOR ADD DEPARTMENT</h3>
			</div>
			
		</div>
		<div class="row" style="font-size:15px;">
			<table border="1">
			<?php foreach($arr as $key => $value){ ?>
				<tr>
					<td style="width: 34%;"><b><?=$key?></b></td>
					<td colspan="2"><?=$value?></td>
				</tr>
			<?php } ?>
				<tfoot>
				<tr>
					<td style="height: 125px;"><br><br><center><?php echo $terms; ?></center><br><center><b>PAYMENT TERMS</b></center></td>
					<td style="height: 125px;"><b class="lbl">Endorsed By:</b><br><br><center><?php echo $division_head; ?></center><br/><center><b>DIVISION HEAD</b></center></td>
					<td style="height: 125px;"><b class="lbl">Approved By:</b><br><br><br><br><center><b>EVP - MERCHANDISING</b></center></td>
				<tr/>
			</tfoot>
			</table>
		</div>
	</div>
</body>
</html>