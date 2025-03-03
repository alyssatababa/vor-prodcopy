<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Price Comparison</title>
<script>
  setTimeout(function()
  {
    var css = '@page { size: landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet){
      style.styleSheet.cssText = css;
    } else {
      style.appendChild(document.createTextNode(css));
    }

    head.appendChild(style);

    window.print();

  } , 300);
</script>
</head>
<style>
body{
   font-family:Arial, Helvetica, sans-serif; font-size:12px; page-break-after:always;
   word-break: break-word;
}
table {
  width: 100%;    
}

.parent_table{
  border: solid 1px #454;
  border-collapse: collapse;
  border-spacing: 0;
}

p{
  font-size: 16px;
  margin: 0;
}

h1, h3, h2, p{
  padding: 5px;
}
h1, h3, h2 {
  margin: 5px;
}
@media print {
  tr.tr_head {
      background-color: #dcdcdc !important;
      -webkit-print-color-adjust: exact; 
  }
  td.awarded {
      background-color: yellow !important;
      -webkit-print-color-adjust: exact; 
  }
  td.label {
      background-color: #efeeee !important;
      -webkit-print-color-adjust: exact; 
  }
}  
tr.tr_head {
      background-color: #dcdcdc !important;
    }
td.awarded {
    background-color: yellow !important;
}
td.label {
   background-color: #efeeee;
}
@supports (zoom:2) {
	input[type=checkbox]{
	zoom: 2;
	}
}
@supports not (zoom:2) {
	input[type=checkbox]{
		transform: scale(2);
		margin: 1px;
	}
}
</style>
<body>

<?php 
	//echo "<pre>";
	//print_r($part);
	//echo "</pre>";
	// Build temporary array for array_unique
	//$tmp = array();
	//foreach($part as $k => $v)
	//	$tmp[$k] = $v->QUOTE_AMOUNT;
    //
	//// Find duplicates in temporary array
	//$tmp = array_unique($tmp);
    //
	//// Remove the duplicates from original array
	//foreach($part as $k => $v)
	//{
	//	if (!array_key_exists($k, $tmp))
	//		unset($part[$k]);
	//}
	//rsort($part);
?>
<table width="100%" class="parent_table" style="margin-bottom: 10px;">
    <tr class="tr_head">
        <td/ colspan=12>
          <h2>Award - RFQ/RFB# <?php echo $xz[0]->RFQRFB_ID . ' - ' .$xz[0]->TITLE; ?></h2>
        </td>
    </tr>
    <tr>
        <td colspan=8 style="border: 1px solid #454; width: 43%;">
            <p>Title: <strong><?php echo $xz[0]->TITLE; ?></strong></p>
        </td>
        <td colspan=2 style="border: 1px solid #454; width: 24%;">
            <p>Created By: <strong><?php echo $xz[0]->USER_FIRST_NAME . ( (empty($xz[0]->USER_MIDDLE_NAME)) ? ""  : " " . $xz[0]->USER_MIDDLE_NAME) . ( (empty($xz[0]->USER_LAST_NAME)) ? ""  : " " . $xz[0]->USER_LAST_NAME);?></strong></p>
        </td>
        <td colspan=2 style="border: 1px solid #454; width: 33%;">
            <p>Date Created: <strong><?php echo $xz[0]->DATECREATED; ?></strong></p>
        </td>
    </tr>

    <tr>
        <td colspan=3 style="border: 1px solid #454; width: 15%;">
            <p>Type: <strong><?php echo $xz[0]->RFQRFB_TYPE_NAME; ?></strong></p>
        </td>
        <td colspan=3 style="border: 1px solid #454; width: 15%;">
            <p>Currency: <strong><?php echo $xz[0]->ABBREVIATION; ?></strong></p>
        </td>
        <td colspan=3 style="border: 1px solid #454; width: 35%;">
            <p>Preferred Delivery Date: <strong><?php echo $xz[0]->DELIVERY_DATE; ?></strong></p>
        </td>
        <td colspan=3 style="border: 1px solid #454; width: 35%;">
            <p>Submission Deadline Date: <strong><?php echo $xz[0]->SUBMISSION_DEADLINE; ?></strong></p>
        </td>
    </tr>

    <tr>
        <td colspan=12 style="border: 1px solid #454; width: 100%;">
            <p>Requestor: <strong><?php echo $xz[0]->REQUESTOR; ?></strong></p>
        </td>
    </tr>
    <tr>
        <td colspan=12 style="border: 1px solid #454; width: 100%;">
            <p>Purpose of Request: <strong><?php echo $xz[0]->PURPOSE; ?></strong></p>
        </td>
    </tr>
    <tr>
        <td colspan=12 style="border: 1px solid #454; width: 100%;">
            <p>Reason for Request: <strong><?php echo $xz[0]->REASON; ?></strong></p>
        </td>
    </tr>
    <!-- <tr> -->
        <td colspan=12 style="border: 1px solid #454; width: 100%;">
            <p>Internal Note:  <strong><?php echo $xz[0]->INTERNAL_NOTE; ?></strong></p>
        </td>
    </tr>
</table>

<?php if(!empty($line)): ?>
<table width="100%" class="parent_table" >

    <!-- LINE -->
    <?php foreach($line as $line_item): ?>

        <table width="100%" class="parent_table"  style="margin-bottom: 10px;">
            <tr class="tr_head">
                <td colspan="12" style="border: 1px solid #454; width: 100%;">
                  <h2><?php echo $line_item->CATEGORY_NAME . ' - ' .$line_item->DESCRIPTION; ?></h2>
                </td>
            </tr>

            <?php 
				//Get Total Participants
				$total_participants = 0;
				$filtered_participants = array();
				foreach($part as $participant){
					if(($line_item->RFQRFB_LINE_ID == $participant->LINE_ID) && ($participant->SHORTLISTED == 1)){
						$filtered_participants[] = $participant;
					}
				}
				$prices = array();
				$vendors = array();
				$highest_version = null;
				$test = array();
				foreach($part as $participant){
					if($line_item->RFQRFB_LINE_ID ==  $participant->LINE_ID && !in_array($participant->VENDOR_ID, $vendors)){
						$vendors[$line_item->RFQRFB_LINE_ID][$participant->VENDOR_ID][] =  $participant->VERSION;
					}
				}
				
				$largest_vendor_version = array();
				foreach($part as $participant){
					if($line_item->RFQRFB_LINE_ID == $participant->LINE_ID && !in_array($participant->VENDOR_ID, $vendors)){
						$largest_vendor_version[$line_item->RFQRFB_LINE_ID][$participant->VENDOR_ID] =  max($vendors[$line_item->RFQRFB_LINE_ID][$participant->VENDOR_ID]);
					}
				}
				
				$lowest_price = array();
				$lowest = null;
				foreach($vendors as $v_val){
				  
					foreach($part as $participant){
						if($line_item->RFQRFB_LINE_ID == $participant->LINE_ID && $largest_vendor_version[$line_item->RFQRFB_LINE_ID][$participant->VENDOR_ID] == $participant->VERSION && $participant->SHORTLISTED == 1){
						
							if($lowest === null){
								$lowest = $participant->QUOTE_AMOUNT;
							}
							
							if($participant->QUOTE_AMOUNT < $lowest){
								$lowest = $participant->QUOTE_AMOUNT;
							}
						}
					}
					//echo "HV : " . $highest_version . ' = ' . $line_item->RFQRFB_LINE_ID . ' | LOWEST = ' . $lowest . '  | VID = ' . $v_val . '<br />';
					if($lowest !== null){
						$lowest_price[$line_item->RFQRFB_LINE_ID] = $lowest;
					}
					
				}
				//echo "<pre>";
				//print_r($lowest_price);
				//echo "</pre>";
				$total_participants = count($filtered_participants);
				
				$first_remainder = $total_participants % 3;
	
				if($first_remainder == 1){
					$total_participants += 2;
				}else if($first_remainder == 2){
					$total_participants++;
				} 
            ?>
            <?php for($i = 0; $i < $total_participants; $i += 3): ?>
			<tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p><strong>Version: <?php echo ( isset($filtered_participants[$i]->VERSION) ? $filtered_participants[$i]->VERSION : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i]->FORMATTED_DATE)) ? $filtered_participants[$i]->FORMATTED_DATE : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p><strong>Version: <?php echo ( isset($filtered_participants[$i + 1]->VERSION) ? $filtered_participants[$i + 1]->VERSION : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 1]->FORMATTED_DATE)) ? $filtered_participants[$i + 1]->FORMATTED_DATE : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p><strong>Version: <?php echo ( isset($filtered_participants[$i + 2]->VERSION) ? $filtered_participants[$i + 2]->VERSION : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 2]->FORMATTED_DATE)) ? $filtered_participants[$i + 2]->FORMATTED_DATE : ""); ?></p></td>
            </tr>
            
            <tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Vendor: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><strong><?php echo ( (isset($filtered_participants[$i]->VENDOR_NAME)) ? $filtered_participants[$i]->VENDOR_NAME : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Vendor: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><strong><?php echo ( (isset($filtered_participants[$i + 1]->VENDOR_NAME)) ? $filtered_participants[$i + 1]->VENDOR_NAME : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Vendor: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><strong><?php echo ( (isset($filtered_participants[$i + 2]->VENDOR_NAME)) ? $filtered_participants[$i + 2]->VENDOR_NAME : ""); ?></strong></p></td>
            </tr>
            <tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Price: </p></td>
              <td class="<?php echo ( (isset($filtered_participants[$i]->QUOTE_AMOUNT) && isset($filtered_participants[$i]->LINE_ID) && isset($lowest_price[$line_item->RFQRFB_LINE_ID])&& $filtered_participants[$i]->QUOTE_AMOUNT == $lowest_price[$filtered_participants[$i]->LINE_ID] && isset( $filtered_participants[$i]->VERSION) && $filtered_participants[$i]->VERSION == $largest_vendor_version[$line_item->RFQRFB_LINE_ID][$filtered_participants[$i]->VENDOR_ID]) ? 'awarded' : ''); ?>" colspan="2" style="border: 1px solid #454; width: 20%;"><?php echo ( (isset($filtered_participants[$i]->AWARDED) && $filtered_participants[$i]->AWARDED == 1) ? '<input type="checkbox" checked style="float:left;">' : ''); ?><p style="float:left; margin-top: 5px;"><strong><?php echo ( (isset($filtered_participants[$i]->QUOTE_AMOUNT)) ? number_format($filtered_participants[$i]->QUOTE_AMOUNT, 2, '.', ',') : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Price: </p></td>
              <td class="<?php echo ( (isset($filtered_participants[$i + 1]->QUOTE_AMOUNT) && isset($filtered_participants[$i + 1]->LINE_ID) && isset($lowest_price[$line_item->RFQRFB_LINE_ID]) && $filtered_participants[$i + 1]->QUOTE_AMOUNT == $lowest_price[$filtered_participants[$i + 1]->LINE_ID] && isset( $filtered_participants[$i + 1]->VERSION) && $filtered_participants[$i + 1]->VERSION ==$largest_vendor_version[$line_item->RFQRFB_LINE_ID][$filtered_participants[$i + 1]->VENDOR_ID]) ? 'awarded' : ''); ?>" colspan="2" style="border: 1px solid #454; width: 20%;"><?php echo ( (isset($filtered_participants[$i + 1]->AWARDED) && $filtered_participants[$i + 1]->AWARDED == 1) ? '<input type="checkbox" checked style="float:left;">' : ''); ?><p style="float:left; margin-top: 5px;"><strong><?php echo ( (isset($filtered_participants[$i + 1]->QUOTE_AMOUNT)) ? number_format($filtered_participants[$i + 1]->QUOTE_AMOUNT, 2, '.', ',') : ""); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Price: </p></td>
              <td class="<?php echo ( (isset($filtered_participants[$i + 2]->QUOTE_AMOUNT) && isset($filtered_participants[$i + 2]->LINE_ID) && isset($lowest_price[$line_item->RFQRFB_LINE_ID]) && $filtered_participants[$i + 2]->QUOTE_AMOUNT == $lowest_price[$filtered_participants[$i + 2]->LINE_ID] && isset( $filtered_participants[$i + 2]->VERSION) && $filtered_participants[$i + 2]->VERSION == $largest_vendor_version[$line_item->RFQRFB_LINE_ID][$filtered_participants[$i + 2]->VENDOR_ID]) ? 'awarded' : ''); ?>" colspan="2" style="border: 1px solid #454; width: 20%;"><?php echo ( (isset($filtered_participants[$i + 2]->AWARDED) && $filtered_participants[$i + 2]->AWARDED == 1) ? '<input type="checkbox" checked style="float:left;">' : ''); ?><p style="float:left; margin-top: 5px;"><strong><?php echo ( (isset($filtered_participants[$i + 2]->QUOTE_AMOUNT)) ? number_format($filtered_participants[$i + 2]->QUOTE_AMOUNT, 2, '.', ',') : ""); ?></strong></p></td>
              
            </tr>
            <tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Delivery Lead Time: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i]->LEAD_TIME)) ? $filtered_participants[$i]->LEAD_TIME : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Delivery Lead Time: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 1]->LEAD_TIME)) ? $filtered_participants[$i + 1]->LEAD_TIME : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Delivery Lead Time: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 2]->LEAD_TIME)) ? $filtered_participants[$i + 2]->LEAD_TIME : ""); ?></p></td>
              
            </tr>
            <tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Counter Offer: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i]->COUNTER_OFFER)) ? $filtered_participants[$i]->COUNTER_OFFER : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Counter Offer: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 1]->COUNTER_OFFER)) ? $filtered_participants[$i + 1]->COUNTER_OFFER : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Counter Offer: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 2]->COUNTER_OFFER)) ? $filtered_participants[$i + 2]->COUNTER_OFFER : ""); ?></p></td>
              
            </tr>
            <tr>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Attachments: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i]->ATTACHMENT_PATH)) ? $filtered_participants[$i]->ATTACHMENT_PATH : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Attachments: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 1]->ATTACHMENT_PATH)) ? $filtered_participants[$i + 1]->ATTACHMENT_PATH : ""); ?></p></td>
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label"><p>Attachments: </p></td>
              <td colspan="2" style="border: 1px solid #454; width: 20%;"><p><?php echo ( (isset($filtered_participants[$i + 2]->ATTACHMENT_PATH)) ? $filtered_participants[$i + 2]->ATTACHMENT_PATH : ""); ?></strong></p></td>
            </tr>

            <!-- Gap  -->
            <tr style="border: 1px solid white;"><td></td></tr>

            <?php endfor; ?>

            <!-- PO DETAILS -->
            <?php if(!empty($po_details)): ?>

            <tr style="margin-top: 10px; ">
              <td colspan="2" style="border: 1px solid #454; width: 10%;" class="label" ><p>PO Details: </p></td>
              <td colspan="2" style="border: 1px solid #454;" class="label" ><p>Company/Operating Unit</p></td>
              <td colspan="2" style="border: 1px solid #454;" class="label" ><p>PO Number</p></td>
              <td colspan="1" style="border: 1px solid #454;" class="label" ><p>Negotiated Amount</p></td>
              <td colspan="1" style="border: 1px solid #454;" class="label" ><p>Quantity</p></td>
              <td colspan="2" style="border: 1px solid #454;" class="label" ><p>Date Updated</p></td>
              <td colspan="2" style="border: 1px solid #454;" class="label" ><p>Updated by</p></td>
            </tr>

            <!-- PO DETAILS LOOP-->
            <?php foreach($po_details as $detail): ?>

            <?php if($detail->LINE_ID == $line_item->RFQRFB_LINE_ID): ?>
            
            <tr>
              <td colspan="2" style="border-right: 1px solid #454; width: 10%;"></td>
              <td colspan="2" style="border: 1px solid #454;"><p><strong><?php echo $detail->COMPANY; ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454;"><p><strong><?php echo $detail->PO_NUMBER; ?></strong></p></td>
              <td colspan="1" style="border: 1px solid #454;"><p><strong><?php echo number_format($detail->NEGO_AMOUNT); ?></strong></p></td>
              <td colspan="1" style="border: 1px solid #454;"><p><strong><?php echo number_format($detail->QUANTITY); ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454;"><p><strong><?php echo $detail->FORMATTED_DATE; ?></strong></p></td>
              <td colspan="2" style="border: 1px solid #454;"><p><strong><?php echo $detail->USER_FIRST_NAME . ( (empty($detail->USER_MIDDLE_NAME)) ? ""  : " " . $detail->USER_MIDDLE_NAME) . ( (empty($detail->USER_LAST_NAME)) ? ""  : " " . $detail->USER_LAST_NAME);; ?></strong></p></td>
            </tr>

            <?php endif;?>

            <?php endforeach; ?>

            <?php endif; ?>
        </table>
    <?php endforeach; ?>

</table>
<?php endif; ?>
