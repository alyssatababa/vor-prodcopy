<?php //echo "<pre>"; print_r($rs); echo "</pre>"; ?>
<?php 
	$new_vendor = '';
	$change_company_name = '';
	$add_vendor_code = '';
	$vendor_code = ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->VENDOR_CODE : null);;
	$vendor_code_v2 = '';
	$update_of_information = '';
	
	$terms_payment = $rs->rs_vreg[0]->TERMS_PAYMENT_NAME;
	
	if($rs->rs_vreg[0]->REGISTRATION_TYPE == 1){
		$new_vendor = 'checked';
	}else if($rs->rs_vreg[0]->REGISTRATION_TYPE == 4){
		$add_vendor_code = 'checked';
		$vendor_code_v2 = $vendor_code;
		$vendor_code = $rs->rs_vreg[0]->VENDOR_CODE_02;
		$terms_payment = $rs->rs_vreg[0]->AVC_TERMSPAYMENT_NAME;
	}else if($rs->rs_vreg[0]->REGISTRATION_TYPE == 5){
		$change_company_name = 'checked';
		$vendor_code_v2 = $rs->xx_vendor_code;
	}else if($rs->rs_vreg[0]->VENDOR_CODE_02 != ''){
		$update_of_information = 'checked';
		//$vendor_code_v2 = $rs->rs_vreg[0]->VENDOR_CODE_02;
		$vendor_code_v2 = '';
		$vendor_code = $vendor_code. ", ". $rs->rs_vreg[0]->VENDOR_CODE_02;
	}else if($rs->rs_vreg[0]->REGISTRATION_TYPE == 2){
		$update_of_information = 'checked';
	}

	if($rs->rs_vreg[0]->REGISTRATION_TYPE != 5){
		$rs->xx_vendor_name = '';
	}
	
	if($rs->rs_vreg[0]->REGISTRATION_TYPE == 2){
		$endorsed_by = $rs->vrd_head;
	}else if($rs->rs_vreg[0]->REGISTRATION_TYPE == 3){
		$update_of_information = 'checked';
		$endorsed_by = $rs->vrd_head;
	}else{
		$endorsed_by = $rs->division_head;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Vendor Information</title>
<script>
	setTimeout(function()
			 {
			   window.print();
			   self.close();
			 } , 300);
</script>
<style type="text/css">
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
</head>

<body>
<table width="876" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; page-break-after:always;">
  <tr>
    <td colspan="2" width="49.1%">
		<div style="font-size: 24px;font-weight: bold;">
			<img src="<?php echo base_url().'assets/img/sm-logo.gif'?>" style="width: 120px; margin-left:5px;"/>
			<p style="float: right; margin-right: 10px;margin-top: 52px;"><strong>VENDOR INFORMATION</strong></p>
		</div>
	</td>
    <td colspan="2" width="49.8%"><table width="100%" style="border:1px solid black; border-spacing:0px;">
      <tr>
        <td height="56" colspan="2" valign="top" style="border-bottom: 1px solid black; padding: 2px 5px 2px 7px;word-break: break-word;">
		<span style="font-size:16px;"><strong>Vendor Name:</strong></span> 
		<p style="margin: 10px 0px 10px 0px; text-align: center; font-size:22px;">
			<?php echo ($rs->count_vreg > 0 ? strtoupper($rs->rs_vreg[0]->VENDOR_NAME) : null); ?>
		</p>
		</td>
        </tr>
      
      <tr>
        <td width="40%" height="51" valign="top" style="border:none; border-right: 1px solid black; padding: 2px 5px 2px 7px;word-break: break-word;"><span style="font-size:16px;"><strong>Vendor Code:</strong></span>
		<p style="margin: 10px 0px 10px 0px; text-align: center;font-size:16px;"><?php echo $vendor_code; ?><p></td>
		<!-- Added MSF - 20191118 (IJR-10618) --> 
		<!-- <td valign="top" style="border:none; padding: 2px 5px 2px 7px;word-break: break-word;"><span style="font-size:16px;"><strong>Dept/Subdept/Class Codes:</strong></span> -->
		
		<!-- Modified MSF 20191128 (NA) -->
        <!--<td valign="top" style="border:none; padding: 2px 5px 2px 7px;word-break: break-word;"><span style="font-size:16px;"><strong>Company/Department:</strong></span>-->
        <td valign="top" style="border:none; padding: 2px 5px 2px 7px;word-break: break-word;"><span style="font-size:16px;"><strong>Business Unit/Department:</strong></span>
			<p style="margin: 10px 0px 10px 0px; text-align: center;font-size:16px;">
			     <?php 
				  $cat_arr = array();
				  $sub_cat_arr = array();
				  
				  if($rs->rs_vreg[0]->VENDOR_CODE_02 != '' || $rs->rs_vreg[0]->REGISTRATION_TYPE != 4 ){
					  if ($rs->count_cat > 0){               
						foreach ($rs->rs_cat as $row)
						{
							if($row->CATEGORY_NAME != ''){
								if(!in_array(strtoupper($row->CATEGORY_NAME), $cat_arr)){
									$cat_arr[] = strtoupper($row->CATEGORY_NAME);	
								}
							}
							if($row->SUB_CATEGORY_NAME != ''){
								if(!in_array(strtoupper($row->SUB_CATEGORY_NAME), $sub_cat_arr)){
									$sub_cat_arr[] = strtoupper($row->SUB_CATEGORY_NAME);	
								}
							}
						}
					  }
				  }
			  
				  if ($rs->rs_cat_avc > 0){               
					foreach ($rs->rs_cat_avc as $row)
					{
						if($row->CATEGORY_NAME != ''){
							if(!in_array(strtoupper($row->CATEGORY_NAME), $cat_arr)){
								$cat_arr[] = strtoupper($row->CATEGORY_NAME);
							}
						}
						if($row->SUB_CATEGORY_NAME != ''){
							if(!in_array(strtoupper($row->SUB_CATEGORY_NAME), $sub_cat_arr)){
								$sub_cat_arr[] = strtoupper($row->SUB_CATEGORY_NAME);	
							}
						}
					}
				  }
				  
				  if (count($cat_arr) > 0)
					echo implode(', ', $cat_arr);
				 ?>
			</p>
		</td>
      </tr>
      
    </table></td>
  </tr>
  
  <tr>
    <td colspan="4"><table width="100%" style="border:1px solid black; border-spacing:0px;">
      <tr>
        <?php 
			if ($rs->count_vbrand > 0)
              {
                foreach ($rs->rs_vbrand as $row){
                  $brands[] = strtoupper($row->BRAND_NAME);
                }
              }else{
				  $brands[] = '';
			  }
         ?>
	    <!-- 
		<td colspan="2" width="50%" style="border-right: 1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><label>
          <input type="checkbox" name="checkbox3" id="checkbox3" <?php echo $new_vendor; ?>/>
        <strong>New Vendor</strong></label></td>
		!-->
		<td width="25%" style="border-top: 1px solid black;  border-bottom: 1px solid black; border-right: 1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;"><input type="checkbox" name="checkbox3" id="checkbox3" <?php echo $new_vendor; ?> />
          <strong>New Vendor</strong></td>
        <td width="25%" style="border-top: 1px solid black;  border-bottom: 1px solid black; border-right: 1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;"><input type="checkbox" name="checkbox4" id="checkbox4" <?php echo $update_of_information; ?>/>
          <strong>Update of Information</strong></td>
        <td width="50%" style="border:none; padding: 2px 5px 2px 7px; word-break: break-word;"><span><strong>Brand: </strong></span><?php echo strtoupper(implode(', ', $brands)) ?></span></td>
        
      </tr>
	  <tr>
        <td width="25%" style="border-top: 1px solid black;  border-bottom: 1px solid black; border-right: 1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;"><input type="checkbox" name="checkbox4" id="checkbox4" <?php echo $change_company_name; ?> />
          <strong>Change in Company Name</strong></td>
        <td width="25%" style="border-top: 1px solid black;  border-bottom: 1px solid black; border-right: 1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;"><input type="checkbox" name="checkbox4" id="checkbox4" <?php echo $add_vendor_code; ?>/>
          <strong>Add Vendor Code</strong></td>
       <td colspan="2" width="50%" style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><span><strong>Years in Business: </strong></span> 
	   <?php echo ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->YEAR_IN_BUSINESS : null); ?></span></td>
     
      </tr>
      <tr>
        <td  colspan="2" width="50%"style="border-right: 1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Existing Vendor Code: &nbsp; <?php echo $vendor_code_v2;?></strong></td>
        <td colspan="2" width="50%" style="padding: 2px 0px 2px 0px;">
        <?php 
          $corp = '';
          $part = '';
          $sp   = '';
          $fl   = '';

          $ownership = ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->OWNERSHIP_TYPE : null);
            switch ($ownership)
            {
              case '1':
                $corp = 'checked';
                break;
              case '2':
                $part = 'checked';
                break;
              case '3':
                $sp = 'checked';
                break;
              case '4':
                $fl = 'checked';
                break;
            }
        ?>
		  <table width="100%" border="0">
			<tbody>
				<tr>
				  <td><strong><input type="checkbox" name="checkbox5" id="checkbox5" <?php echo $corp; ?>/> 
				  Corporation </strong></td>
				  <td ><strong><input type="checkbox" name="checkbox6" id="checkbox6" <?php echo $part; ?>/>
				  Partnership </strong></td>
				  <td><strong><input type="checkbox" name="checkbox7" id="checkbox7" <?php echo $sp; ?>/>
				  Sole Proprietorship</strong></td>
				  <td><strong><input type="checkbox" name="checkbox11" id="checkbox11" <?php echo $fl; ?>/>
				  Freelance</strong></td>
				</tr>
			</tbody>
		  </table>
        </td>
        <!--<td colspan="2" style="border:1px solid black; border-spacing:0px;">If change in Company Name</td>-->
        </tr>
      <tr>
        <td  colspan="2" width="50%" style="border-top:1px solid black; border-bottom: 1px solid black;  border-right:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Existing Vendor Name: &nbsp; <?php echo $rs->xx_vendor_name;?>
		 &nbsp;</strong>
		</td>
		<?php 
          $out  = '';
          $cons = '';
          $nt   = '';
          $nts = '';
          $vendor_type = ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->VENDOR_TYPE : null);
          if ($vendor_type == 1) // trade
          {
			if($rs->rs_vreg[0]->REGISTRATION_TYPE == 4){
				if($rs->rs_vreg[0]->STATUS_ID == 19){
					$out = 'checked';
					$cons = 'checked';
				}else{
					switch ($rs->rs_vreg[0]->TRADE_VENDOR_TYPE) {
					case '1':
						$out = 'checked';
						break;
					case '2':
						$cons = 'checked';
						break;
					}
				}
			}else if($rs->rs_vreg[0]->VENDOR_CODE_02 != ''){
				$out = 'checked';
				$cons = 'checked';
			}else{
				switch ($rs->rs_vreg[0]->TRADE_VENDOR_TYPE) {
				case '1':
					$out = 'checked';
					break;
				case '2':
					$cons = 'checked';
					break;
				}
			}
          }elseif($vendor_type == 3){
              $nts   = 'checked'; // non trade service
          }
          else
            $nt   = 'checked'; // non trade
        ?>
        <td colspan="2" width="50%" style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 2px 0px 2px 0px;">
			 <table width="100%" border="0">
				<tbody>
					<tr>
						<td width="20%"><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $out; ?>/> 
						  Outright</strong></td>
						<td width="20%"><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $cons; ?>/>
						  Consignor</strong></td>
						<td width="50%">
							   <table width="100%" border="0">
								<tbody>
									<tr>
										<td >
											<strong><input type="checkbox" name="checkbox10" id="checkbox10" <?php echo $nt; ?>/> Non Trade</strong> 
										</td>
                        <td>
                      <strong><input type="checkbox" name="checkbox10" id="checkbox10" <?php echo $nts; ?>/> Non Trade Service</strong>
                    </td>
										<td>
											
										</td>
									</tr>
								</tbody>
								</table>
						</td>
					</tr>
				</tbody>
			  </table>
		</td>
      </tr>
      
      <tr>
        <td colspan="4" style="padding: 0;"><table width="100%" style="border-spacing:0px;" border="0">
          <tr style="height:60px;">
            <td width="32.82%" height="36" valign="top" style="border-right: 1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Office Address <i>(where correspondences can be sent)</i></strong><br>
			
            <?php 
              if ($rs->count_vaddr_office > 0){               
                foreach ($rs->rs_vaddr_office as $row)
                {
                  if ($row->PRIMARY == 1){
                    $address = $row->ADDRESS_LINE .' '. $row->CITY_NAME.' '.$row->STATE_PROV_NAME.' '.$row->REGION_DESC_TWO.' '.$row->ZIP_CODE.' '.$row->COUNTRY_NAME.'<br>'; 
					echo strtoupper($address);
				  }
                }
              }
             ?>
            </td>
			<!-- Added & Modified MSF 20200924 -->
            <td width="32.82%" valign="top" style="border-right: 1px solid black;  padding: 2px 5px 2px 7px;  word-break: break-word;"><strong>E-mail Address</strong><br>

              <?php 
              $email_arr = array();
              if ($rs->count_vc_email > 0){               
                foreach ($rs->rs_vc_email as $row)
                {
                    $email_arr[] = $row->CONTACT_DETAIL;
                }
              }
              if (count($email_arr) > 0)
              echo implode(', ', $email_arr);
             ?>
            </td>
			<?php 
				$tax_classification = $rs->rs_vreg[0]->TAX_CLASSIFICATION;
				$vat ="";
				$nonvat ="";
				$zerorated ="";
				
				if($tax_classification == 1){
					$vat = 'checked';
				}else if($tax_classification == 2){
					$nonvat = 'checked';
				}else{
					$zerorated = 'checked';
				}
			?>
            <td width="34.24%" valign="top" style="padding: 2px 5px 2px 7px;"><strong>No. of Employees</strong>
			<?php 
				$no_of_employees = $rs->rs_vreg[0]->EMPLOYEE;
				$noe_micro = '';
				$noe_small = '';
				$noe_medium = '';
				$noe_large = '';
				switch ($no_of_employees){
					case '0':
						$noe_micro = 'checked';
						break;
					case '1':
						$noe_small = 'checked';
						break;
					case '2':
						$noe_medium = 'checked';
						break;
					case '3':
						$noe_large = 'checked';
						break;
				}
			?>
			<table width="100%" border="0" cellpadding=0 cellspacing=0>
				<tbody>
					<tr>
						<td width="40%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $noe_micro; ?>/>
						<font size="-2">MICRO (1 - 9)</font></strong></td>
						<td width="60%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $noe_medium; ?> />
						<font size="-2">MEDIUM (100 - 199)</font></strong></td>
					</tr>
					<tr>
						<td width="40%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $noe_small; ?> /> 
						<font size="-2">SMALL (10 - 99)</font></strong></td>
						<td width="60%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $noe_large; ?> />
						<font size="-2">LARGE (200 and above)</font></strong></td>
					</tr>
				</tbody>
			</table>
			
			</td>
          </tr>
          <tr>
            <td height="41" valign="top" style="border-top: 1px solid black; border-right: 1px solid black; height:60px; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Warehouse/Factory Address</strong><br>
              
            <?php 
              if ($rs->count_vaddr_factory > 0){               
                foreach ($rs->rs_vaddr_factory as $row)
                {
                  if ($row->PRIMARY == 1){
                    $address = $row->ADDRESS_LINE.' '.$row->CITY_NAME.' '.$row->STATE_PROV_NAME.' '.$row->ZIP_CODE.' '.$row->COUNTRY_NAME.'<br>';
					echo strtoupper($address);
				  }
                }
              }

              if ($rs->count_vaddr_warehouse > 0){               
                foreach ($rs->rs_vaddr_warehouse as $row)
                {
                  if ($row->PRIMARY == 1){
                    $address = $row->ADDRESS_LINE.' '.$row->CITY_NAME.' '.$row->STATE_PROV_NAME.' '.$row->ZIP_CODE.' '.$row->COUNTRY_NAME .'<br>';
					echo strtoupper($address);
				  }
                }
              }
             ?>
			 
            </td>
			<!-- Added & Modified MSF 20200924 -->
            <td valign="top" style="border-top: 1px solid black; border-right: 1px solid black;height:60px; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Tax Identification No. (TIN)</strong><br>

			<p style=" margin: 5px 0px 5px 2px; word-break: break-word;"><?php echo $rs->rs_vreg[0]->TAX_ID_NO?></p>
				<table width="100%" border="0" >
				<tbody>
					<tr>
						<!-- Added & Modified MSF 20200924 -->
						<td width="20%"><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $vat; ?>//> 
						  <font size="-2">VAT</font></strong></td>
						<td width="30%"><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $nonvat; ?>//>
						  <font size="-2">Non-VAT</font></strong></td>
						<td width="40%"><strong><input type="checkbox" name="checkbox10" id="checkbox10" <?php echo $zerorated; ?>/>
						  <font size="-2">Zero-rated</font></strong></td>
					</tr>
				</tbody>
			  </table>
			<!-- Added & Modified MSF 20200924 -->
            </td>
			
            <td valign="top" style="border-top: 1px solid black; height:60px; padding: 2px 0px 2px 7px; word-break: break-word;"><strong>MSME Business Asset Classification</strong><br>

			<?php 
				$business_asset = $rs->rs_vreg[0]->BUSINESS_ASSET;
				$ba_micro = '';
				$ba_small = '';
				$ba_medium = '';
				$ba_large = '';
				switch ($business_asset){
					case '0':
						$ba_micro = 'checked';
						break;
					case '1':
						$ba_small = 'checked';
						break;
					case '2':
						$ba_medium = 'checked';
						break;
					case '3':
						$ba_large = 'checked';
						break;
				}
			?>
			
				<table width="100%" border="0" cellpadding=0 cellspacing=0>
				<tbody>
					<tr>
						<td width="40%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $ba_micro; ?>/>
						<font size="-2">MICRO(Up to 3M)</font></strong></td>
						<td width="60%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $ba_medium; ?>/>
						<font size="-2">MEDIUM (15M - 100M)</font></strong></td>
					</tr>
					<tr>
						<td width="40%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $ba_small; ?>/> 
						<font size="-2">SMALL (3M - 15M)</font></strong></td>
						<td width="60%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $ba_large; ?>/>
						<font size="-2">LARGE (100M and above)</font></strong></td>
					</tr>
				</tbody>
			</table>
			
            </td>
          </tr>
          <tr>
            <td height="43" valign="top" style="border-right: 1px solid black; border-top: 1px solid black;height:60px; padding: 2px 5px 2px 7px; word-break:break-word;"><strong>Tel. No./Fax No./Mobile No.</strong><br>

			  <?php 
			  $telfax_arr = array();
              if ($rs->count_vc_telno > 0){               
                foreach ($rs->rs_vc_telno as $row)
                {
                    if ($row->CONTACT_DETAIL != null)
                    $telfax_arr[] = $row->COUNTRY_CODE.' '.$row->AREA_CODE.' '.$row->CONTACT_DETAIL.' '.$row->EXTENSION_LOCAL_NUMBER;
                }
              }
			
              if (count($telfax_arr) > 0)
               echo implode(', ', $telfax_arr) . ' <br/>';
				
			  $telfax_arr = array();
              if ($rs->count_vc_faxno > 0){               
                foreach ($rs->rs_vc_faxno as $row)
                {
                    if ($row->CONTACT_DETAIL != null)
                    $telfax_arr[] = $row->COUNTRY_CODE.' '.$row->AREA_CODE.' '.$row->CONTACT_DETAIL.' '.$row->EXTENSION_LOCAL_NUMBER;
                }
              }

              if (count($telfax_arr) > 0)
			  //Added & Modified MSF 20200924
               echo implode(', ', $telfax_arr) . ' <br/>';
				
			  $telfax_arr = array();
              if ($rs->count_vc_mobno > 0){               
                foreach ($rs->rs_vc_mobno as $row)
                {
                    $telfax_arr[] = $row->COUNTRY_CODE.' '.$row->AREA_CODE.' '.$row->CONTACT_DETAIL;
                }
              }

              if (count($telfax_arr) > 0)
               echo implode(', ', $telfax_arr) . ' <br/>';
             ?>

            </td>
            <td valign="top" style="border-top: 1px solid black; border-right: 1px solid black;height:60px; padding: 2px 5px 2px 7px; word-break:break-word;"><strong>Bank References</strong><br>

            <?php 
              $bank_arr = array();
              if ($rs->count_vbank > 0){               
                foreach ($rs->rs_vbank as $row)
                {
                    $bank_arr[] = $row->BANK_NAME.' '.$row->BANK_BRANCH;
                }
              }
              if (count($bank_arr) > 0)
              echo strtoupper(implode(', ', $bank_arr));
             ?>

            </td>
			
			<?php 
				$NOB_DISTRIBUTOR = $rs->rs_vreg[0]->NOB_DISTRIBUTOR;
				$NOB_MANUFACTURER = $rs->rs_vreg[0]->NOB_MANUFACTURER;
				$NOB_IMPORTER = $rs->rs_vreg[0]->NOB_IMPORTER;
				$NOB_WHOLESALER = $rs->rs_vreg[0]->NOB_WHOLESALER;
				$NOB_OTHERS = $rs->rs_vreg[0]->NOB_OTHERS;
				$NOB_OTHERS_TEXT = $rs->rs_vreg[0]->NOB_OTHERS_TEXT;
				$nob_distributor ="";
				$nob_manufacturer ="";
				$nob_importer ="";
				$nob_wholesaler ="";
				$nob_others ="";
				
				if($NOB_DISTRIBUTOR == 1){
					$nob_distributor = 'checked';
				}
				if($NOB_MANUFACTURER == 1){
					$nob_manufacturer = 'checked';
				}
				if($NOB_IMPORTER == 1){
					$nob_importer = 'checked';
				}
				if($NOB_WHOLESALER == 1){
					$nob_wholesaler = 'checked';
				}
				if($NOB_OTHERS == 1){
					$nob_others = 'checked';
				}
			?>
			<!-- Added & Modified MSF 20200924 -->
            <td  valign="top" style="border-top: 1px solid black; padding: 2px 5px 2px 7px;"><strong>Nature/Type of Business</strong>
			
				<!-- Modified MSF 20191128 (NA) -->
				<!--<table width="100%" border="0" style="margin-top: 10px;">-->
				<table width="100%" border="0" cellpadding=0 cellspacing=0>
					<tbody>
						<tr>
							<td width="50%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $nob_distributor; ?>/>
							  <font size="-2">Distributor/Licensee</font></strong></td>
							<td width="50%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $nob_importer; ?>/>
							  <font size="-2">Importer/Trader</font></strong></td>
						</tr>
						<tr>
							<td width="50%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox8" id="checkbox8" <?php echo $nob_manufacturer; ?>/> 
							  <font size="-2">Manufacturer</font></strong></td>
							<td width="50%" style="padding: 0px 0px 0px 0px;" ><strong><input type="checkbox" name="checkbox9" id="checkbox9" <?php echo $nob_wholesaler; ?>/>
							  <font size="-2">Wholesaler</font></strong></td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" name="checkbox10" id="checkbox10" <?php echo $nob_others; ?>/><strong><font size="-2">Others (Pls specify):</font></strong>
										
							</td>
							<td>
								<table width="100%" border="0" style="margin-top:10px; word-break: break-word;">
								<tbody>
									<tr>
										<td width="65%" style="border-bottom: 1px solid #636363;">
											<?php 
												if($NOB_OTHERS == 1){
													echo strtoupper($rs->rs_vreg[0]->NOB_OTHERS_TEXT);
												}
											?>
										</td>
									</tr>
								</tbody>
								</table>
							</td>
							
						</tr>
					</tbody>
				  </table>
			</td>
          </tr>
          <!--<tr>
            <td height="43" valign="top" style="border:1px solid black; border-spacing:0px;">&nbsp;</td>
            <td valign="top" style="border:1px solid black; border-spacing:0px;">&nbsp;</td>
            <td style="border:1px solid black; border-spacing:0px;">&nbsp;</td>
          </tr>-->
        </table></td>
      </tr>
	  <tr>
        <td colspan="4" style="border-top:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Owners/Partners/Directors</strong>
          <table width="100%" border="0" style="padding: 0px 10px 0px 10px;">
            <tr>
             <td colspan="2" width="50%"><div align="center"><strong>Name</strong></div></td>
			  <td></td>
              <td colspan="2" width="50%"><div align="center"><strong>Position</strong></div></td>
            </tr>
            <?php 

              if ($rs->count_vowner > 0):
                $n = 1;
                foreach ($rs->rs_vowner as $row)
                {
				  // Added MSF - 20191118 (IJR-10618)
                  if($n == 9){
                    break;
                  }
                  ?>
				  
					<tr>
					  <td colspan="2" width="50%" style="border-bottom: 1px 
					  solid #626262; text-align:center;padding-bottom: 0px;">
						<?php $temp_name = $row->FIRST_NAME . " " . (empty($row->MIDDLE_NAME) ? "" : $row->MIDDLE_NAME . " ") . $row->LAST_NAME; 
						echo strtoupper($temp_name);
						?>
					  </td>
					  <td style=" padding: 10px 17px 10px 17px;"></td>
					  <td colspan="2" width="50%" style="border-bottom: 1px solid #626262; text-align:center;padding-bottom: 0px;">
						<?php $temp_position = $row->POSITION;
						echo strtoupper($temp_position); ?>
					  </td>
					</tr>
           
                  <?php
                  $n++;
                }
				// Modified MSF - 20191118 (IJR-10618)
				//if($n < 10):
				   //for($i = $n; $i <= 10; $i++):
				if($n < 8):
				   for($i = $n; $i <= 8; $i++):
				   ?>
					
					<tr>
					  <td colspan="2" width="50%" style="border-bottom: 1px 
					  solid #626262; text-align:center;padding-bottom: 0px;">
						&nbsp;
					  </td>
					  <td style=" padding: 10px 17px 10px 17px;"></td>
					  <td colspan="2" width="50%" style="border-bottom: 1px solid #626262; text-align:center;padding-bottom: 0px;">
						&nbsp;
					  </td>
					</tr>
				   <?php
				   endfor;
				endif;
              
              else:
             ?>
            <tr>
              <td>1.</td>
              <td>&nbsp;</td>
            </tr>
            <?php endif; ?>
          </table></td>
        </tr>
		
		
      <tr>
        <td colspan="4" style="border-top:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Authorized Representative</strong>
          <table width="100%" border="0" style="padding: 0px 10px 0px 10px;">
		    <tr>
             <td colspan="2" width="50%"><div align="center"><strong>Name</strong></div></td>
			  <td></td>
              <td colspan="2" width="50%"><div align="center"><strong>Position</strong></div></td>
            </tr>
            <?php 
              if ($rs->count_vauthrep > 0):
                $n = 1;
                foreach ($rs->rs_vauthrep as $row)
                {
				  // Added MSF - 20191118 (IJR-10618)
                  if($n == 5){
                    break;
                  }
                  ?>
                  <tr>
					  <td colspan="2" width="50%" style="border-bottom: 1px 
					  solid #626262; text-align:center;padding-bottom: 0px;">
						<?php echo strtoupper($row->FIRST_NAME . " " . (empty($row->MIDDLE_NAME) ? "" : $row->MIDDLE_NAME . " ") . $row->LAST_NAME); ?>
					  </td>
					  <td style=" padding: 10px 17px 10px 17px;"></td>
					  <td colspan="2" width="50%" style="border-bottom: 1px solid #626262; text-align:center;padding-bottom: 0px;">
						<?php echo strtoupper($row->POSITION); ?>
					  </td>
					</tr>
                  <?php
                  $n++;
                }
				// Modified MSF - 20191118 (IJR-10618)
				//if($n < 5):
				   //for($i = $n; $i <= 5; $i++):
				if($n < 4):
				   for($i = $n; $i <= 4; $i++):
				   ?>
					
					<tr>
					  <td colspan="2" width="50%" style="border-bottom: 1px 
					  solid #626262; text-align:center;padding-bottom: 0px;">
						&nbsp;
					  </td>
					  <td style=" padding: 10px 17px 10px 17px;"></td>
					  <td colspan="2" width="50%" style="border-bottom: 1px solid #626262; text-align:center;padding-bottom: 0px;">
						&nbsp;
					  </td>
					</tr>
				   <?php
				   endfor;
				endif;
              else:
            ?>
            <tr>
              <td>1.</td>
              <td>&nbsp;</td>
            </tr>
          <?php endif; ?>
          </table></td>
        </tr>
		
		
      <tr>
        <td height="50" colspan="4" valign="top" style="border-top:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Categories Supplied</strong><br>
		
        <?php 
		/* Modified MSF - 20191118 (IJR-10618)
          $cat_arr = array();
          if ($rs->count_cat > 0){               
            foreach ($rs->rs_cat as $row)
            {
                $cat_arr[] = $row->CATEGORY_NAME;
            }
          }
          if (count($cat_arr) > 0)
          echo strtoupper(implode(', ', $cat_arr));
		*/
		
	  
		  if (count($sub_cat_arr) > 0)
			echo strtoupper(implode(', ', $sub_cat_arr));
         ?>
        </td>
        </tr>
      <tr>
        <td height="50" colspan="4" valign="top" style="border-top:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Other Retail Customer/Client</strong><br>
		
        <?php 
          $orcc_arr = array();
          if ($rs->count_vretcust > 0){               
            foreach ($rs->rs_vretcust as $row)
            {
                $orcc_arr[] = $row->COMPANY_NAME;
            }
          }
          if (count($orcc_arr) > 0)
          echo strtoupper(implode(', ', $orcc_arr));
         ?>

        </td>
      </tr>
      <tr>
        <td height="50" colspan="4" valign="top" style="border-top:1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Other Businesses <i>(indicate company name and products/services offered)</i></strong><br>
		
        <?php 
          $vob_arr = array();
          if ($rs->count_vob > 0){               
            foreach ($rs->rs_vob as $row)
            {
                $vob_arr[] = '<strong>Name: </strong>' . strtoupper($row->COMPANY_NAME) .  nbs(10) . '<strong>Products/Services Offered: </strong>'. strtoupper($row->SERVICE_OFFERED);
            }
          }
          if (count($vob_arr) > 0)
          echo implode('<br/>', $vob_arr);
         ?>

         </td>
      </tr>
      <tr>
        <td height="50" colspan="4" valign="top" style="border-top:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Disclosure of Relatives Working in SM or its Affiliates <i>(indicate name of employee, company affiliated with, and relationship)</i></strong><br>
		
        <?php
          if ($rs->count_vrel > 0){               
            foreach ($rs->rs_vrel as $row)
            {
                echo '<strong>Name: </strong>'. strtoupper($row->FIRST_NAME) . strtoupper($row->LAST_NAME) . nbs(10) . '<strong>Position: </strong>'. strtoupper($row->POSITION) . nbs(10) . '<strong>Company: </strong>'. strtoupper($row->COMPANY) . nbs(10) . '<strong>Relationship: </strong>'. strtoupper($row->RELATIONSHIP) . '<br>';
            }
          }
         ?>

         </td>
      </tr>
      <!--<tr>
        <td height="90" colspan="4" valign="top" style="border-top:1px solid black; padding: 2px 10px 2px 10px;">
		<p style="text-indent: 50px; text-align:justify;"><em>
		We certify that we have made a full disclosure of true and correct information provided herein and have not withheld any as specified in the official business
documents and supporting documents hereof, which we submitted to SM. We confirm that any misrepresentation in the information above may be ground for revocation or
denial of Vendor's accreditation.</em>
		</p>
          <p style="text-align:right; margin-bottom:0;"><u><?php //echo nbs(85);?></u> <br />
          <span style="margin-right: 50px;">AUTHORIZED SIGNATURE</span> <br />
          <span style="margin-right: 32px;">Signature over Printed Name/Date</span></p>          </td>
      </tr>-->
	  
	  <!-- Removed MSF 20191128 (NA) -->
      <!-- 
	  <tr>
        <td colspan="4" align="center" style="border-top:1px solid black;"><strong>For SM Use Only</strong></td>
      </tr>
	  -->
	  
	  <!-- Modified MSF - 20191118 (IJR-10618)
      <tr>
        <td height="50" width="50%" valign="top" style="border-top:1px solid black; padding: 2px 5px 2px 7px;"><strong>Terms of Payment:</strong><br> 
		<p style="text-align:center;margin:5px 0px 0px 0px; word-break:break-word;"><?php echo ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->TERMS_PAYMENT_NAME : null); ?></p></td>
        <td colspan="3" width="50%" valign="top" style="border-top:1px solid black; border-left:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word; ">
		<strong>Remarks:</strong>
		<p style="margin: 0px; word-break:break-word;"><?php echo ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->NOTE : null); ?></p>
	</td>
        </tr>
	   -->
		
    </table>
		<!-- Added & Modified MSF 20200924 -->
		<table width="100%" style="border: 1px solid black; margin-top: 7px;" cellspacing="0">
		  <tr>
			<td colspan="3" width="50%" valign="top" style="height:50px; padding: 2px 5px 2px 7px; word-break: break-word; ">
			  <strong>Remarks:</strong>
			  <p style="margin: 0px; word-break:break-word;"><?php echo ($rs->count_vreg > 0 ? $rs->rs_vreg[0]->NOTE : null); ?></p>
			</td>
		  </tr>
		</table>
		
		<table width="100%" style="border: 1px solid black; margin-top: 7px;" cellspacing="0">
          <tr>
			<!-- Added MSF - 20191118 (IJR-10618) -->
			<td height="50" width="33%" valign="top" style="border-right:1px solid black; padding: 2px 5px 2px 7px;"><strong>Terms of Payment:</strong><br> 
			  <p style="text-align:center;margin:5px 0px 0px 0px; word-break:break-word;"><?php echo ($rs->count_vreg > 0 ? $terms_payment : null); ?></p>
			</td>
			
			<!-- Modified MSF - 20191118 (IJR-10618) -->
            <!-- <td width="50%" height="50" valign="top" style="border-right:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;">-->
			<td width="34%" height="50" valign="top" style="border-right:1px solid black; padding: 2px 5px 2px 7px; word-break: break-word;">
			<strong>Endorsed by</strong><!--/Date (Signature over Printed Name)-->
			<p style="text-align:center; margin-bottom: 0; margin-top: 1em; word-break:break-word;"><?php echo $endorsed_by; ?></p>
			<p style="margin:0px; text-align:center;"><strong>Division Head</strong></p>
			</td>
            <!--<td width="33%" valign="top" style="border-right:1px solid black;padding: 2px 5px 2px 7px; word-break: break-word;">Interviewed by/Date
				<p>&nbsp;</p>
			<p style="margin:0px; text-align:center;">Vendor Relations Head</p>
			</td>-->
            <td colspan="3" width="50%" valign="top" style=" padding: 2px 5px 2px 7px; word-break: break-word;"><strong>Approved by</strong>
				<p  style="text-align:center; margin-bottom: 0; margin-top: 1em; word-break:break-word;">&nbsp;</p>
			<p style="margin:0px; text-align:center; margin-bottom:5px;"><strong>EVP-Merchandising</strong></p>
			</td>
          </tr>
        </table>
	</td>
  </tr>
  <!--<tr>
    <td colspan="4">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>