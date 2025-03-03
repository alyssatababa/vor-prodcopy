<html>
	<head>
		<title>Server Status</title>
		<link href="<?=base_url().'assets/dist/css/bootstrap.min.css'?>" rel="stylesheet">
	</head>
	<body>
		<br/>
		<div class="container-fluid">
			
			<div class="panel panel-primary">
				<div class="panel-heading">Server Status</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-4">
							<div class="panel panel-info" id="web_div" <?php echo ((!empty($web_server) ? (($web_folders_is_writable) ? ' style="background-color:#00C853;"': ' style="background-color:#FFB74D;"') : 'style="background-color:#F44336;"'));?> >
								<div class="panel-heading">Web server</div>
								<div class="panel-body">
									<?php if(!empty($web_server) ):?>
									<h1 style="text-align:center; margin:0; color:white;" id="web_text">Connected <?php echo $web_folders_is_writable ? '' : ' but with error'; ?></h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" id="web_icon"></span></h1>
									<?php else: ?>
									<h1 style="text-align:center; margin:0; color:white;" id="web_text">Disconnected</h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" id="web_icon"></span></h1>
									<?php endif;?>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-info" id="app_div" <?php echo (((!empty($app_server) && $app_server->message == "Connected." && !empty($result[1]['Test Name']) && $result[1]['Test Name'] == 'Connect to App server' && $result[1]['Result'] == 'Passed') ? ' style="background-color:#00C853;"' : 'style="background-color:#F44336;"'));?>>
								<div class="panel-heading">App server</div>
								<div class="panel-body">
								
									<?php if(!empty($app_server) && $app_server->message == "Connected." && $result[1]['Test Name'] == 'Connect to App server' && $result[1]['Result'] == 'Passed'):?>
									<h1 style="text-align:center; margin:0; color:white;" id="app_text">Connected</h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" id="app_icon"> </span></h1>
									<?php else: ?>
									<h1 style="text-align:center; margin:0; color:white;" id="app_text">Disconnected</h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" id="app_icon"></span></h1>
									<?php endif;?>
								</div>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="panel panel-info"<?php echo (((!empty($etl_server) && $etl_server->message == "Connected." && !empty($result[3]['Test Name']) && $result[3]['Test Name'] == 'Connect to ETL server' && $result[3]['Result'] == 'Passed') ? (($etl_folders_is_writable) ? ' style="background-color:#00C853;"': ' style="background-color:#FFB74D;"') : 'style="background-color:#F44336;"'));?> id="etl_div">
								<div class="panel-heading">ETL server</div>
								<div class="panel-body">
									<?php if(!empty($etl_server) && $etl_server->message == "Connected." && $result[3]['Test Name'] == 'Connect to ETL server' && $result[3]['Result'] == 'Passed'):?>
									<h1 style="text-align:center; margin:0; color:white;" id="etl_text">Connected <?php echo $etl_folders_is_writable ? '' : ' but with error'; ?></h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true" id="etl_icon"></span></h1>
									<?php else: ?>
									<h1 style="text-align:center; margin:0; color:white;" id="etl_text">Disconnected</h1>
									<h1 style="text-align:center; margin:0; font-size:82px; color:#fff;"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true" id="etl_icon"></span></h1>
									<?php endif;?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script>
	function load() {
		setInterval(function () {
				$.ajax({
				  url: "<?php echo base_url() . 'index.php/server_status/refresh_connection'; ?>",
				  success: function(result){
					var parsed = JSON.parse(result);
					
					//ETL
					if(parsed.etl_server == "Passed"){
						//$("#etl_div").css("background-color","#00C853");
						//ETL Directory
						if(jQuery.isEmptyObject(parsed.etl_folders_is_writable)){
							$("#etl_div").css("background-color","#00C853");
							$("#etl_text").html("Connected");
							$("#etl_icon").removeClass("glyphicon-remove-sign");
							$("#etl_icon").addClass("glyphicon-ok-sign");
						}else{
							$("#etl_div").css("background-color","#FFB74D");
							
							$("#etl_text").html("Connected but with error");
							$("#etl_icon").removeClass("glyphicon-remove-sign");
							$("#etl_icon").addClass("glyphicon-ok-sign");
						}
					}else{
						$("#etl_div").css("background-color","#F44336");
						$("#etl_text").html("Disconnected");
						$("#etl_icon").removeClass("glyphicon-ok-sign");
						$("#etl_icon").addClass("glyphicon-remove-sign");
					}
					
					//App
					if(parsed.app_server == "Passed"){
						$("#app_div").css("background-color","#00C853");
					
						$("#app_text").html("Connected");
						$("#app_icon").removeClass("glyphicon-remove-sign");
						$("#app_icon").addClass("glyphicon-ok-sign");
					}else{
						$("#app_div").css("background-color","#F44336");
						
						$("#app_text").html("Disconnected");
						$("#app_icon").removeClass("glyphicon-ok-sign");
						$("#app_icon").addClass("glyphicon-remove-sign");
					}
					
					//Web
					if(!jQuery.isEmptyObject(parsed.web_server)){
						//$("#web_div").css("background-color","#00C853");
						//Web Directory
						if(jQuery.isEmptyObject(parsed.web_folders_is_writable)){
							$("#web_div").css("background-color","#00C853");
							
							$("#web_text").html("Connected");
							$("#web_icon").removeClass("glyphicon-remove-sign");
							$("#web_icon").addClass("glyphicon-ok-sign");
						}else{
							$("#web_div").css("background-color","#FFB74D");
							
							$("#web_text").html("Connected but with error");
							$("#web_icon").removeClass("glyphicon-remove-sign");
							$("#web_icon").addClass("glyphicon-ok-sign");
						}
					}else{
						$("#web_div").css("background-color","#F44336");
						
						$("#web_text").html("Disconnected");
						$("#web_icon").removeClass("glyphicon-ok-sign");
						$("#web_icon").addClass("glyphicon-remove-sign");
					}
					
				  }
				});
		}, 5000);
	}
	load();
	</script>
</html>
