<div class="container">

	<form name="forgot_form" id="forgot_form" class="form-signin" method="POST">
	<div class="panel panel-primary">
	<div class="panel-heading">
	<h5 class="form-signin-heading">Change Password</h5>
	</div>
	<div id="no-script-panel" >
	<?php echo @$no_js_message; ?>
	</div>
	<div class="panel-body">
	<div class="form-group">
	<!-- Modified MSF 20200218 (IJR-10618) -->
	<center><span id="err_msg" style="display: none;"></span></center>
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $_ci_data['_ci_vars']['user_id']; ?>">
	<label for="c_password">Current E-mail</label>
	<input type="textbox" name="c_password" id="c_password" class="form-control" value="<?php echo $_ci_data['_ci_vars']['email']; ?>" readonly>
	<br/>
	<label for="n_password">New E-mail</label>
	<input type="textbox" name="n_password" id="n_password" class="form-control" placeholder="Enter New Email">
	<br/>
	<label for="cn_password">Confirm E-mail</label>
	<input type="textbox" name="cn_password" id="cn_password" class="form-control" placeholder="Confirm New Email">
	</div>
	<div class="form-group">
	<button id="submit_password" name="submit_password" class="btn btn-lg btn-primary btn-block" type="button" data-loading-svg="<?=base_url().'assets/img/loading_ring.svg'?>">Submit</button>
	</div>
	</div>
	</div>
	</form>
</div> <!-- /container -->

<script type="text/javascript">
	$(document).ready(function () {
		var user_id = $("#user_id").val();
		var $err_msg = $('#err_msg');
		var $submit_password = $("#submit_password");
		var loading_svg = '<img id="loading_ring" src="' + $submit_password.data('loading-svg') + '" alt="loading_ring.svg" style="height: 25px;">';
		
		function validate(){
			var c_password =  $("#c_password").val();
			var n_password =  $("#n_password").val();
			var cn_password = $("#cn_password").val();
			var user_id = $("#user_id").val();
			
			if (n_password === '') {
				$err_msg.text("Please Input New Email.").show();
			}else if (cn_password === '') {
				$err_msg.text("Please Input Confirm Email.").show();
			}else if (cn_password !== n_password) {
				$err_msg.text("New Email and Confirm Email Mismatched.").show();
			}else{
				var orig_btn_lbl = $(this).html();
				$(this).html(loading_svg).prop('disabled', true);

				var ajax_type = 'post';
				var url = 'change_email/validate_userdata';
				var params = { user_id:user_id, c_password: c_password, n_password: n_password };
				var success_function = function (responseText){
					var err_code = parseInt(responseText);
					
					if (err_code == 1) {
						$err_msg.text("Email successfully changed.").show();
				
						$("#c_password").val(n_password);
						$("#n_password").val('');
						$("#cn_password").val('');
					}else{
						$err_msg.text("User not found.").show();
					}
					
					$(this).prop('disabled', false);
					$(this).html(orig_btn_lbl);
					
				}.bind(this);

				ajax_request(ajax_type, url, params, success_function);
			}
		}
		
		$submit_password.on("click", validate);
		
		$(document).keypress(function(e) {
			if(e.which == 13) {
				e.preventDefault();
				$submit_password.trigger("click");
			}
		});
	});
</script>