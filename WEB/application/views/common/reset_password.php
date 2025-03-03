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

    <title>Set Password</title>

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

  <body class="login" onload="check_reset_valid(<?=$reset?>)">

    <div class="container">

     <form class="form-signin" action="<?=base_url();?>index.php/resetpassword/validate_password/<?php echo isset($token) ?  $token: ''; ?>" method="POST">
				
		<!-- Modified by MSF 20191115 -->
  		<!-- <img class="signin-logo" src="<?php //echo base_url(); ?>/assets/img/client_logo.png"> -->
  		<center><img class="signin-logo" src="<?=base_url()?>/assets/img/client_logo.png"></center>
			<div class="panel panel-primary">
				<div class="panel-heading">
				<h5 class="form-signin-heading">Set Password</h5>
				</div>

					<div id="no-script-panel">
					<?php echo @$no_js_message; ?>
					</div>
					<div class="panel-body">
                        <!--
                            add patern for allowed characters to avoid sql injection
                            // ' OR '1' = '1
                        -->
                        <label>Your temporary login id is <h4><?=isset($username) ?  $username: '';?></h4></label>
						<br>
                        <label id="label_new_password">Enter your new password</label>
						<br />
						<label for="new_password" class="sr-only">New Password</label>
						<input type="password" id="new_password" name="new_password" class="form-control" value="<?=isset($new_password) ?  $new_password: '';?>" pattern=".{6,12}" title="passwords must not be less than 6 characters and must not exceed 12 characters" placeholder="New Password" required>
						<label for="confirm_password" class="sr-only">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="<?=isset($confirm_password) ?  $confirm_password: '';?>" pattern=".{6,12}" title="passwords must not be less than 6 characters and must not exceed 12 characters" placeholder="Confirm Password" required>

						<input class="btn btn-lg btn-primary btn-block" id="submit_rest_btn" type="submit" <?php echo isset($btn_disable) ? $btn_disable : ''; ?>/>
            <div>
			  <!-- Modified by MSF 20200121 -->
              <!-- <center><a href="<?php echo base_url(); ?>" title="">www.smvendorportal.com</a></center> -->
              <center><a href="<?php echo base_url(); ?>" title="">www.smvendoronlineregistration.com</a></center>
            </div>
            <?= isset($message) && $message != '' ? '<div class="alert '.$alert_class.' alert-dismissable">'.$message.'</div>' : ''; ?>
					</div>
                        
				</div>
	</form>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo base_url(); ?>assets/js/ie10-viewport-bug-workaround.js"></script>
    <script type="text/javascript">
        var password = document.getElementById("new_password")
          , confirm_password = document.getElementById("confirm_password");

        function validatePassword(){
          if(password.value != confirm_password.value) {
            confirm_password.setCustomValidity("Password Don't Match");
          } else {
            confirm_password.setCustomValidity('');
          }
        }

        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;

        function check_reset_valid(value)
        {
          if(value == 1)
          {
              document.getElementById("label_new_password").style.display = 'none';
              document.getElementById("new_password").style.display = 'none';
              document.getElementById("confirm_password").style.display = 'none';
              document.getElementById("submit_rest_btn").style.display = 'none';
          }
        }
    </script>
  </body>
</html>
