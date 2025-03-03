<script>

var lmx = "<?php echo $l_date;?>";



//check if firefox date format MM/DD/YYYY hh:ii:ss
 if (navigator.userAgent.indexOf("Firefox") > 0) {
                lmx = lmx.replace(/-/g,'/');          
     }


if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {

	 lmx = lmx.replace(/-/g,'/');  


}


//Check if IOS
//Date format for IOS 2018-02-09T00:00:00
var iOS = !!navigator.platform && /iPad|iPhone|iPod/.test(navigator.platform);
if(iOS){

	/*var lmx_temp = lmx.split('-');
	var lmx_year_temp = lmx_temp[2].split(' ');
	lmx = lmx_year_temp[0] + '-' + lmx_temp[0] + '-' + lmx_temp[1] + 'T' + lmx_year_temp[1];
	alert(lmx);*/


}
var tick = 0;

	
//console.log(localStorage);


</script>
<style type="text/css">
	
.disabledbutton {
    pointer-events: none;
    opacity: 0.4;
}

.a_table_header a{
	display: block;
	text-decoration: none;
	color:#000;
}




</style>

<div class="container">

		<div id="login_details">
			<div class="row">

				<div class="col-sm-3 col-md-2"><img src="<?=base_url()?>/assets/img/client_logo.png" width="160px"></div>

				<div class="col-sm-6">
					<h6>
						Welcome <?= $this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name').' - '.$this->session->userdata('position_name'); ?>
						<br>Last Login: <?= ($this->session->userdata('last_attempt') == null ? '' : date("D, j F Y - g:i:s A", strtotime($this->session->userdata('last_attempt'))) ); ?> <a href="#" data-toggle="modal" data-target="#view_logs"> [View Logs] </a>
						<br>System Date/Time: <span id ="sysdate" class="datetime"></span>
						<br>Your session will expire on <span id="session_expiry_date"></span>
					</h6>
				</div>

				<?php if(base_url() == 'http://114.108.234.234:8888/qa/' || base_url() == 'http://sm-webserver:8080/qa/') { ?>
					<div class="col-sm-4"><center><h3>QA WEBSITE</h3></center></div>
				<?php } ?>

				<?php if(base_url() == 'http://114.108.234.234:8888/' || base_url() == 'http://sm-webserver:8080/') { ?>
					<div class="col-sm-4"><center><h3>DEMO WEBSITE</h3></center></div>
				<?php } ?>
			</div>

			<ol class="breadcrumb">
				<li class="active"><a href="#" onclick="force_reload()">Home</a></li>
			</ol>
		</div>


		<div class="hidden_el" id="notifications">
			<div class="alert alert-success">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Success!</strong> This alert box could indicate a successful or positive action.</span>
			</div>
			<div class="alert" id="modal_alert_success">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Success!</strong> This alert box could indicate a successful or positive action.</span>
			</div>
			<div class="alert alert-info">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Info!</strong> This alert box could indicate a neutral informative change or action.</span>
			</div>
			<div class="alert" id="modal_alert_info">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Info!</strong> This alert box could indicate a neutral informative change or action.</span>
			</div>
			<div class="alert alert-warning">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Warning!</strong> This alert box could indicate a warning that might need attention.</span>
			</div>
			<div class="alert" id="modal_alert_warning">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Warning!</strong> This alert box could indicate a warning that might need attention.</span>
			</div>
			<div class="alert alert-danger">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Danger!</strong> This alert box could indicate a dangerous or potentially negative action.</span>
			</div>
			<div class="alert" id="modal_alert_danger">
				<a href="#" class="close" id="close_alert" aria-label="close">&times;</a>
				<span><strong>Danger!</strong> This alert box could indicate a dangerous or potentially negative action.</span>
			</div>
		</div>

		<div class="modal fade" id="session_expiry" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background-color: #f0ad4e; border-color: #eea236;">
						<h4 class="modal-title">Session has expired. Continue session?</h4>
					</div>
					<div class="modal-footer">
						<center>
							<button type="button" class="btn btn-default" align="center" onclick="continue_session()">Yes</button>
							<button type="button" data-dismiss="modal" class="btn btn-default" align="center" onclick="end_session()">No</button>
						</center>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="no_session" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background-color: #f0ad4e; border-color: #eea236;">
						<h4 class="modal-title"><center> You have been logout due to inactivity, You will now be redirected to the log in screen. Thank You </center></h4>
					</div>
					<div class="modal-footer">
						<center>
							<button type="button" class="btn btn-default" align="center" onclick="redirect_to_login();">OK</button>
						</center>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="view_logs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header" style="background-color: #337ab7; border-color: #337ab7;">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">User Session Logs</h4>
					</div>
					<div class="modal-body">
						<div class="row form-horizontal">
							<div class="form-group">
								<label class="col-sm-4 control-label">USER: </label>
								<div class="col-sm-8">
									<p class="form-control-static"><?=$this->session->userdata('user_first_name').' '.$this->session->userdata('user_middle_name').' '.$this->session->userdata('user_last_name')?></p>
								</div>
							</div>
							<!--<div class="form-group">
								<label class="col-sm-4 control-label">Company: </label>
								<div class="col-sm-8">
									<p class="form-control-static"></p>
								</div>
							</div>-->
							<div class="form-group">
								<label class="col-sm-4 control-label">System Date/Time: </label>
								<div class="col-sm-8">
									<p class="form-control-static"><span class="datetime"></span></p>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-primary">
									<div class="panel-heading">Session Data</div>
									<table class="table table-hover" id="actions_logs" data-user-id="<?=$this->session->userdata('user_id')?>">
										<thead>
											<tr><!--Old Data col: ACTION_DATE-->
												<th data-col="DATE_SORTING_FORMAT">Log Date</th>
												<th data-col="ACTION">Action</th>
											</tr>
										</thead>
										<tbody>
											<script id="session_tbl_template" type="text/template">
												{{#ResultSet}}
													<tr>
														<td>{{ACTION_DATE}}</td>
														<td>{{ACTION_NAME}}</td>
													</tr>
												{{/ResultSet}}
											</script>
										</tbody>
									</table>
									<div class="panel-footer"><center><div id="action_logs_pages"></div></center></div>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="modal-footer">
						<center>
							<button type="button" class="btn btn-default" align="center" onclick="continue_session()">Yes</button>
							<button type="button" data-dismiss="modal" class="btn btn-default" align="center" onclick="end_session()">No</button>
						</center>
					</div> -->
				</div>
			</div>
		</div>

		<div id="main_container"></div>

	</div>
