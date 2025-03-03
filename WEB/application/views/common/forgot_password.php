<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=base_url().'assets/img/sm_favicon.ico'?>">

    <title>Forgot Password</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url(); ?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url() . 'assets/css/signin.css?' . filemtime('assets/css/signin.css'); ?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo base_url(); ?>assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
      .form-signin-heading {
        font-size: 18px;
      }
      .help-block {
        font-size: 13px;
        font-weight: normal;
      }
	#no-script-panel{
		display:none;
	}
    </style>
	
			
	<noscript>
		<style>		
			#no-script-panel{
				display:block;
			}
			
			.panel-body{
				display:none;
			}
		</style>
	</noscript>
  </head>

  <body class="login">

    <div class="container">

    <form name="forgot_form" id="forgot_form" class="form-signin" method="POST">
	  <!-- Modified MSF 20200218 (IJR-10618) -->
      <!-- <img class="signin-logo" src="<?=base_url()?>/assets/img/client_logo.png">-->
      <center><img class="signin-logo" src="<?=base_url()?>/assets/img/client_logo.png">
      <div class="panel panel-primary">
        <div class="panel-heading">
				  <h5 class="form-signin-heading">Forgot your Password?</h5>
				</div>
<div id="no-script-panel" >
					<?php echo @$no_js_message; ?>
					</div>
				<div class="panel-body">
					<div class="form-group">
			<!-- Modified MSF 20200218 (IJR-10618) -->
            <!-- <span class="help-block">Enter your username to reset your password. Link to change your password will be sent to the email associated with your account. You may need to check your email spam folder or unblock no-reply@smvendorportal.com.</span> -->
            <span class="help-block">Enter your username to reset your password. Link to change your password will be sent to the email associated with your account. You may need to check your email spam folder or unblock smvendoronlineregistration@smretail.com.</span>
            <!-- <span class="help-block">Enter your username or email address to reset your password. You may need to check your email spam folder or unblock support@sandmansystems.com.</span> -->
            <input type="text" name="user_data" id="user_data" class="form-control" placeholder="Enter Username">
            <!-- <input type="text" name="user_data" id="user_data" class="form-control" placeholder="Enter Username or Email"> -->
          </div>
					<div class="form-group">
            <button id="submit_btn" class="btn btn-lg btn-primary btn-block" type="button" data-loading-svg="<?=base_url().'assets/img/loading_ring.svg'?>">Submit</button>
          </div>
          <center><span id="err_msg" style="display: none;"></span></center>
				</div>
			</div>
    </form>

    </div> <!-- /container -->

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="<?php echo js_path('jquery_ajax.js') . '?' . filemtime('assets/js/jquery_ajax.js'); ?>"></script>
    <script type="text/javascript">
	$(document).ready(function () {
		var $user_data = $("#user_data");
		var $err_msg = $('#err_msg');
		var $submit_btn = $("#submit_btn");
		var loading_svg = '<img id="loading_ring" src="' + $submit_btn.data('loading-svg') + '" alt="loading_ring.svg" style="height: 25px;">';

		// function is_email_valid(email) {
		//   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		//   return re.test(email);
		// }

		function validate()
		{
		var user_data = ($user_data.val()).trim();
		var is_email = user_data.includes('@');

		if (user_data === '') {
		  $err_msg.text("Please Input Username.").show();
		  // $err_msg.text("Please Input Username or Email.").show();
		}
		/*else if (is_email && !is_email_valid(user_data)) {
		  $err_msg.text("Email is not valid").show();
		}*/
		else {
		  var orig_btn_lbl = $(this).html();
		  $(this).html(loading_svg).prop('disabled', true);

		  var ajax_type = 'post';
		  var url = 'forgot_password/validate_userdata';
		  var params = {
			user_data: user_data
		  };
		  var success_function = function (responseText)
		  {
			var err_code = parseInt(responseText);

			if (err_code == 1) {
			  $err_msg.text("Change Password Link sent to Account Email.").show();
			}
			else if (err_code == 2) {
			  $(this).prop('disabled', false);
			  $err_msg.text("Username does not exist.").show();
			  // $err_msg.text("Username/Email is not associated with any account.").show();
			}else if (err_code == 3){
			  $(this).prop('disabled', false);
			  $err_msg.text("User Account is INACTIVE.").show();
			}

			$(this).html(orig_btn_lbl);

		  }.bind(this);

		  ajax_request(ajax_type, url, params, success_function);
		}
		}

		$submit_btn.on("click", validate);
		$(document).keypress(function(e) {
			if(e.which == 13) {
				e.preventDefault();
				$submit_btn.trigger("click");
			}
		});
	});
      

    </script>
  </body>
</html>
