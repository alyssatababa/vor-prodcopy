<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>API Result</title>

	<style type="text/css">

		::selection{ background-color: #E13300; color: white; }
		::moz-selection{ background-color: #E13300; color: white; }
		::webkit-selection{ background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body{
			margin: 0 15px 0 15px;
			text-align:left;
		}
		
		p.footer{
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}
		
		#container{
			margin: 10px;
			border: 1px solid #D0D0D0;
			overflow-x:auto;
			-webkit-box-shadow: 0 0 8px #D0D0D0;
		}
		
		table {
			border-collapse: collapse;
			border: 1px solid black;
			width: 100%;
		}

		th, td {
			text-align: left;
			padding: 8px;
			border: 1px solid black;
		}
		
		th {
			background-color: #f0f0f5;
		}

		tr:nth-child(odd){background-color: #f2f2f2}
	</style>
</head>
	<body>

		<center>
			<div class="container mycontainer">
				<h1>Result</h1>

				<div id="body">
					<a href="<?php echo base_url().'index.php/test/api_tester'?>">&lt;-return</a><br /> 
					
					<strong>Method:</strong> <?=$in_method;?><br /> 
					<strong>URI:</strong> <?=$in_uri;?><br /> 
					<br /> 
					<?php if(count($params)>0) { ?>	
						<strong>params:</strong> <br /> 
						<?php foreach ($params as $pkey => $pval){ ?>						
							&nbsp;<strong><?=$pkey;?>: </strong> <?=$pval;?><br /> 					
						<?php } ?>
					<?php } ?>	
					<!-- load data fetched -->
					
					
					
					<?php if(isset($result_data->status) && !$result_data->status){ ?>
						<?Php echo $result_data->error; ?>
					<?php } else {; ?>
						<table>
							<?php $ctr = 0; ?>
							<?php foreach ($result_data as $row){ ?>							
								<?php if ($ctr<=0) { ?>
									<tr>
										<?php foreach ($row as $key=>$val){ ?>
											<th>
												<?php echo $key; ?>
											</th>
										<?php } ?> <!-- end header loop-->
									</tr>
								<?php } ?>
								
								<tr>
									<?php foreach ($row as $item){ ?>
										<td>
											<?php echo $item; ?>
										</td>
									<?php } ?> <!-- end row loop-->
								</tr>
								<?php $ctr++; ?>
							<?php } ?> <!-- end table loop-->
						</table>
					<?php } ?>
					
					
					
					<code><pre><?Php echo json_encode($result_data,JSON_PRETTY_PRINT); ?></pre></code>
				</div>
				
				
				<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds, CI ver: <strong><?=CI_VERSION;?></strong></p>
			</div>
		</center>
	</body>
</html>
