<!DOCTYPE html>
<html lang="en">
	<head>

	<?php

	$rsd = 0;
		if(isset($result_data->error))
		{
			if($result_data->error == "Record could not be found"){
			$rsd = 1;
			}
		}
	
	?>

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- NO ICON YET -->
		<link rel="icon" href="<?=base_url().'assets/img/sm_favicon.ico'?>">

		<!-- Modified MSF - 20200124 (NA) -->
		<!-- <title>Vendor Online Registration</title> -->
		<title>Vendor Online Registration</title>

		<!-- Bootstrap core CSS -->
		<link href="<?=base_url().'assets/dist/css/bootstrap.min.css'?>" rel="stylesheet">

		<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
		<link href="<?=base_url().'assets/css/ie10-viewport-bug-workaround.css'?>" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="<?=base_url().'assets/css/navbar-fixed-top.css?' . filemtime('assets/css/navbar-fixed-top.css')?>" rel="stylesheet">
		<link href="<?=base_url().'assets/css/style.css?' . filemtime('assets/css/style.css')?>" rel="stylesheet">
		<link href="<?=base_url().'assets/css/pagination.css?' . filemtime('assets/css/pagination.css')?>" rel="stylesheet">
		<link href="<?=base_url().'assets/css/jquery-ui.min.css'?>" rel="stylesheet">

		<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/jquery.dataTables.min.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/dataTables.jqueryui.min.css" rel="stylesheet">
		<!-- <link href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet"> -->
		<link href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" rel="stylesheet">
		<link href="//cdnjs.cloudflare.com/ajax/libs/yadcf/0.9.1/jquery.dataTables.yadcf.min.css" rel="stylesheet">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


		<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
		<script src="<?=base_url().'assets/js/ie-emulation-modes-warning.js'?>"></script>

		<script src="<?=base_url().'assets/js/jquery-3.2.1.min.js'?>"></script>
		<script src="<?=base_url().'assets/js/jquery-ui.min.js'?>"></script>


		<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/dataTables.jqueryui.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/dataTables.bootstrap.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/yadcf/0.9.1/jquery.dataTables.yadcf.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery.fileDownload/1.4.2/jquery.fileDownload.min.js"></script>

		<!--<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
		<script src="//cdn.datatables.net/plug-ins/1.10.16/sorting/datetime-moment.js"></script>
-->
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
  </head>

  <body>
	  <!-- Database Driven Menu -->


	  <nav class="navbar navbar-default navbar-fixed-top">
	      <div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div id="navbar" class="navbar-collapse collapse">
				<!-- MAIN NAV -->
				<ul class="nav navbar-nav">
			<?php

			
			
			if($rsd == 0){
			if(!empty($result_data) && is_array($result_data)){
				foreach ($result_data as $row)
				{
					//Jay
					//If Trade Vendor Type then hide the RFQ/RFB Menu
					//70 - RFQ/RFB
					/*if($row->SCREEN_ID == 70){
						if($position_id == 10){
							if(empty($trade_vendor_type)){
								continue;
							}
						}
					}*/
					
					//Hide business docs for empty vendor code
					if(empty($this->session->userdata('vendor_code')) && $position_id == 10){
						if($row->SCREEN_ID == 60){
							continue;
						}
					}
					$action_str = 'class="menu_item" data-screen-id="' . $row->SCREEN_ID . '" data-action-id="' . $row->ACTION_ID . '"';
					if($row->MENU_LEVEL == 1)
					{
						if($row->HAS_CHILD == 0) {
							if($row->MENU_LABEL == 'Home')
								echo '<li><a href="#" ' . $action_str . ' onclick="force_reload();"><span class="menu_label">'.$row->MENU_LABEL.'</span></a></li>';
							else
								echo '<li><a href="#" ' . $action_str . ' data-path="'.$row->HREF_PATH.'"><span class="menu_label">'.$row->MENU_LABEL.'</span></a></li>';
						}
						else
						{
							// link of parent
							if ($row->HREF_PATH != null || $row->HREF_PATH != '') {
							$datapath = 'data-path="'.$row->HREF_PATH.'"';
							}
							else
							{
								$datapath = '';
							}


							$counter_sub = 0;
							foreach ($result_data as $sub)
							{
								if($row->SCREEN_ID == $sub->PARENT_ID)
								{
									$counter_sub++;
								}
							}

							if($counter_sub > 0)
							{
								//Jay
								if(empty($datapath)){
										
									echo'<li class="dropdown">
											<a href="#" role="button" aria-haspopup="true" aria-expanded="false" '.$datapath.'><span class="menu_label">'.$row->MENU_LABEL.'</span><span class="caret"></span></a>
												<ul class="dropdown-menu">';
								}else{
									
									echo'<li class="dropdown">
											<a href="#" ' . $action_str . ' role="button" aria-haspopup="true" aria-expanded="false" '.$datapath.'><span class="menu_label">'.$row->MENU_LABEL.'</span><span class="caret"></span></a>
												<ul class="dropdown-menu menu_item_scroll">';
								}
								foreach ($result_data as $sub)
								{
									
									$action_str = 'class="menu_item" data-screen-id="' . $sub->SCREEN_ID . '" data-action-id="' . $sub->ACTION_ID . '"';
									if($row->SCREEN_ID == $sub->PARENT_ID)
									{
										$counter_sub++;

										if($sub->MENU_LEVEL == 2)
										{
											if($sub->HAS_CHILD == 0) {
												if( ! empty($sub->OTHER_LINK)){
													//echo '<li><a class="video_link_button" href="javascript:void(0);" data-video-link="' . $sub->HREF_PATH . '" data-video-title="' . $sub->MENU_LABEL . '"data-toggle="modal" data-target="#video_modal" ><span class="menu_label">'.$sub->MENU_LABEL.'</span></a></li>';
													echo '<li><a href="' . $sub->HREF_PATH . '" target="_blank" ><span class="menu_label">'.$sub->MENU_LABEL.'</span></a></li>';
												
												}else{
													echo '<li><a href="#" ' . $action_str . ' data-path="'.$sub->HREF_PATH.'"><span class="menu_label">'.$sub->MENU_LABEL.'</span></a></li>';
												}
											}
											else
											{
												$str = '';
												foreach ($result_data as $sub2)
												{
													
													$action_str = 'class="menu_item" data-screen-id="' . $sub2->SCREEN_ID . '" data-action-id="' . $sub2->ACTION_ID . '"';
													if($sub->SCREEN_ID == $sub2->PARENT_ID) {
														if($sub2->MENU_LEVEL == 3) {
															$str .= '<li><a href="#" ' . $action_str . ' data-path="'.$sub2->HREF_PATH.'"><span class="menu_label">'.$sub2->MENU_LABEL.'</span></a></li>';
														}
													}
												}
												
												//If admin			
												$link = '<a href="#" data-toggle="dropdown"' . ($position_id == 1 ? 'data-path="'.$sub->HREF_PATH.'"' : '') . '><span class="menu_label">'.$sub->MENU_LABEL.'</span></a>';
												
												//jay
												if( ! empty($str)){
													echo '<li class="dropdown-submenu">';
													echo $link;
													echo '<ul class="dropdown-menu">';
													echo $str;
													echo '</ul></li>';
												}else{
													echo '<li>' . $link . '</li>';
												}
											}
										}
									}
								}

								echo '</ul></li>';
						}
						else
						{
							echo '<li><a href="#" ' . $action_str . ' data-path="'.$row->HREF_PATH.'"><span class="menu_label">'.$row->MENU_LABEL.'</span></a></li>';
						}
						}
					}
				}
			}
		}
	  ?>

			  </ul>
												
			<ul class="nav navbar-nav navbar-right">
			
				<li class="dropdown">
					<a href="#" class="menu_item">
						<span class="menu_label">
							<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
						</span>
						<span class="caret"></span>
					</a>
					<ul class="dropdown-menu menu_item_scroll">
						<li>
							<a href="#" class="menu_item" data-screen-id="2" data-action-id="29" role="button" data-path="change_password">
								<span class="menu_label">Change Password</span>
							</a>
						</li>
						<li>
							<a href="#" class="menu_item" data-screen-id="2" data-action-id="29" role="button" data-path="change_email">
								<span class="menu_label">Change Email</span>
							</a>
						</li>
						<li><a href="#" id="logout" onclick="end_session('<?=$this->session->userdata('user_id')?>')">Logout</a></li>
					</ul>
				</li>
	        </ul>
	      </div><!--/.nav-collapse -->

		</div>
	  </nav>
	  <nav id="clone_nav" class="navbar navbar-default navbar-fixed-top" style="position:relative; visibility:hidden;"></nav>













