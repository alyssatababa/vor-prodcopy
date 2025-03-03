<!DOCTYPE html>
<html>
<head><link href="<?=base_url().'assets/css/pdf_award.css'?>" rel="stylesheet"></head>
<body>
<div class="container">
<h3 class="title">Award - <?php echo 'RFQ/RFB# '.$xz[0]->RFQRFB_ID.' - '.$xz[0]->TITLE;?></h3>
<table class="tbl_border" style="width:100%;">
	<tr>
		<td colspan="2">Title: <b><?php echo $xz[0]->TITLE;?></b></td>
		<td>Created By: <b><?php echo $xz[0]->USER_FIRST_NAME . ' ' .  $xz[0]->USER_MIDDLE_NAME .' ' . $xz[0]->USER_LAST_NAME ;?></b></td>
		<td>Date Created: <b><?php 
							$sDate = new  DateTime($xz[0]->DATECREATED);
							echo $sDate->format('M-d-Y');
							 ?></b>
		</td>
	</tr>
	<tr>
		<td>Type: <b><?php echo $xz[0]->RFQRFB_TYPE_NAME; ?></b></td>
		<td>Currency: <b><?php echo $xz[0]->ABBREVIATION; ?></b></td>
		<td>Preferred Delivery Date: <b><?php  $date = new DateTime($xz[0]->DELIVERY_DATE);
											echo $date->format('M-d-Y');
					 				?></b>
		</td>
		<td>Submission Deadline Date: <b><?php  $date = new DateTime($xz[0]->SUBMISSION_DEADLINE);
										echo $date->format('M-d-Y');
									 ?></b>
	 	</td>
	</tr>
	<tr>
		<td colspan="4">
			<table>
				<tr>
					<td>Requestor: </td>
					<td colspan="2"><b><?php echo $xz[0]->REQUESTOR;  ?></b></td>
				</tr>
				<tr>
					<td>Purpose of Request: </td>
					<td><b><?php echo $xz[0]->PURPOSE;  ?></b></td>
					<td><b><?php echo $xz[0]->OTHER_PURPOSE;  ?></b></td>
				</tr>
				<tr>
					<td>Reason for Request: </td>
					<td><b><?php echo $xz[0]->REASON;  ?></b></td>
					<td><b><?php echo $xz[0]->OTHER_REASON;  ?></b></td>
				</tr>
				<tr>
					<td>Internal Note: </td>
					<td colspan="2"><b><?php echo $xz[0]->INTERNAL_NOTE;  ?></b></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table class="tbl_border tbl_center" style="width:100%;">
	<?php
	$vendor_list = array();
	$qoute_amount = array();
	$lead_time = array();
	$counter_offer = array();
	$attach_arr = array();
	$qoute_id_arr = array();
	$shortlisted = array();
	$awarded_arr = array();
	$line_arr = array();
	for($i = 0 ; $i < count($part);$i++)
	{

		if (!array_key_exists($part[$i]->VENDOR_ID, $vendor_list))
			$vendor_list[$part[$i]->VENDOR_ID] = $part[$i]->VENDOR_NAME;

			if ($part[$i]->SHORTLISTED == 1)
			{
				$qoute_amount[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->QUOTE_AMOUNT;
				$lead_time[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->LEAD_TIME;
				$counter_offer[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->COUNTER_OFFER;
				$attach_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->ATTACHMENT_PATH;
				$qoute_id_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->RESPONSE_QUOTE_ID;
				$shortlisted[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->SHORTLISTED;
				$awarded_arr[$part[$i]->VENDOR_ID][$part[$i]->LINE_ID][] = $part[$i]->AWARDED;

				$vendor_count_arr[] = $part[$i]->VENDOR_ID.'-'.$part[$i]->LINE_ID;

				$lowest_arr[$part[$i]->LINE_ID][] = $part[$i]->QUOTE_AMOUNT;
			}

		if (!in_array($part[$i]->LINE_ID, $line_arr))
			$line_arr[] = $part[$i]->LINE_ID;
	}
	


	$vendor_count_arr = array_count_values($vendor_count_arr);
	$arr_count = array_keys($vendor_count_arr);
	$max_count_line = array();
	foreach ($arr_count as $key => $value)
	{
		$id_line = explode('-',$value); // $id_line[0] = VENDOR_ID ,$id_line[1] = LINE_ID

		if (array_key_exists($id_line[1], $max_count_line))
		{
			if ($max_count_line[$id_line[1]] < $vendor_count_arr[$value])
			$max_count_line[$id_line[1]] = $vendor_count_arr[$value];
		}
		else
			$max_count_line[$id_line[1]] = $vendor_count_arr[$value];		
	}

	echo '<tr>';
	for($l = 0 ; $l < count($line); $l++)
	{
		$low_quote = min($lowest_arr[$line_arr[$l]]); // lowest quote
		?>
		
			<td>
				<table class="tbl_border" style="width:100%;border: 1px solid black;">
					<thead>
						<tr>
							<td><?php echo $line[$l]->CATEGORY_NAME .'-'. $line[$l]->DESCRIPTION;  ?></td>
						</tr>
					</thead>
					<tbody>					
						<?php 
						for ($i=0; $i < $max_count_line[$line_arr[$l]]; $i++)
						{
							foreach ($vendor_list as $key => $value)
							{
								?>
										<?php 
										if (array_key_exists($i, $shortlisted[$key][$line_arr[$l]]))
										{
											if ($shortlisted[$key][$line_arr[$l]][$i] == 1)
												echo '<tr><td>Vendor: <b>'.$value.'</b></td></tr>';
										}
								?>
										<?php
										if (array_key_exists($i, $awarded_arr[$key][$line_arr[$l]]))
										{
											if ($awarded_arr[$key][$line_arr[$l]][$i] == 1)
												echo '<tr><td><input type = "radio" name = "rad_option'. $l .'" value="'.$qoute_id_arr[$key][$line_arr[$l]][$i].'" checked="checked"></td></tr>';
										}
										?>
										<?php
										if (array_key_exists($i, $shortlisted[$key][$line_arr[$l]]))
										{
											if ($low_quote == $qoute_amount[$key][$line_arr[$l]][$i])
												$td_bg = 'bgcolor="yellow"';
											else
												$td_bg = '';

											if ($shortlisted[$key][$line_arr[$l]][$i] == 1)
												echo '<tr><td '.$td_bg.'>Price: <b>'.$qoute_amount[$key][$line_arr[$l]][$i].'</b></td></tr>';
										}
										?>
										<?php
										if (array_key_exists($i, $shortlisted[$key][$line_arr[$l]]))
										{
											if ($shortlisted[$key][$line_arr[$l]][$i] == 1)
												echo '<tr><td>Delivery Lead Time: <b>'.$lead_time[$key][$line_arr[$l]][$i].'</b></td></tr>';
										}
										?>
										<?php
										if (array_key_exists($i, $shortlisted[$key][$line_arr[$l]]))
										{
											if ($shortlisted[$key][$line_arr[$l]][$i] == 1)
												echo '<tr><td>Counter Offer: <b>'.$counter_offer[$key][$line_arr[$l]][$i].'</b></td></tr>';
										}
										?>
										<?php
										if (array_key_exists($i, $shortlisted[$key][$line_arr[$l]]))
										{
											if ($shortlisted[$key][$line_arr[$l]][$i] == 1)
											{
												if (!empty($attach_arr[$key][$line_arr[$l]][$i]))
													echo '<tr><td>Attachments: <b>Attachment</b></td></tr>';
												else
													echo '<tr><td>Attachments: None</td></tr>';												
											}
										}
										?>
						<?php
							}
						}
						?>
						
					
					<tr>
					</tr>
					</tbody>
				</table>
			</td>
		
		<?php
	}
	?>
	</tr>
</table>
</div>
</body>
</html>