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

    <title>Login</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?php echo base_url(); ?>assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url() . 'assets/css/signin.css?' . filemtime('assets/css/signin.css'); ?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="<?php echo js_path('ie-emulation-modes-warning.js'); ?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        #forgot_password {
            margin-top: 5px;
            font-size: 12px;
            color: #286090;
            font-weight: lighter;
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
			
			#login_panel{
				display:none;
			}
		</style>
	</noscript>
  </head>

  <body class="login">
    <div class="container">

      <?Php echo form_open('/login/validate_credentials', 'name="login_form" id="login_form" class="form-signin"'); ?>
      <!-- <form name="login_form" id="login_form" class="form-signin" action="<?=base_url();?>index.php/login/validate_credentials" method="post" enctype="application/x-www-form-urlencoded"> -->

  		<center><img class="signin-logo" src="<?=base_url()?>/assets/img/client_logo.png"></center>

			<?php if(base_url() == 'http://114.108.234.234:8888/qa/' || base_url() == 'http://sm-webserver:8080/qa/') { ?>
						<center><h3>QA WEBSITE</h3></center>
			<?php } ?>

			<?php if(base_url() == 'http://114.108.234.234:8888/' || base_url() == 'http://sm-webserver:8080/') { ?>
						<center><h3>DEMO WEBSITE</h3></center>
			<?php } ?>

    		<div class="panel panel-primary">
    			<div class="panel-heading">
    				<h5 class="form-signin-heading">Login</h5>
    			</div>

    			<div class="panel-body">
					<div id="no-script-panel" >
					<?php echo @$no_js_message; ?>
					</div>

                    <div id="login_panel" style="display: none;">
                        <!--
                        add patern for allowed characters to avoid sql injection
                        // ' OR '1' = '1
                        -->
                        <label for="input_username" class="sr-only">User Name</label>
                        <input type="text" id="input_username" name="input_username" class="form-control" value="<?=isset($username) ?  $username: '';?>" placeholder="Username" pattern="[a-zA-Z0-9\-]{5,}" title="special characters are not allowed, usernames must not be less than 5 characters" required autofocus>
                        <input type="hidden" id="hidden_destroy_local_storage" name="hidden_destroy_local_storage" class="form-control" value="<?=isset($destroy_local_storage) ?  $destroy_local_storage: '';?>" />

                        <br />

                        <label for="input_password" class="sr-only">Password</label>
                        <input type="password" id="input_password" name="input_password" class="form-control" autocomplete="off" value="<?=isset($password) ?  $password: '';?>" pattern=".{6,12}" title="passwords must not be less than 6 characters and must not exceed 12 characters" placeholder="Password" required>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="input_remember_me" name="input_remember_me" value="1" <?=isset($remember_me)&& $remember_me ?  'checked': '';?>> Remember me
                            </label>
                        </div>

                        <input class="btn btn-lg btn-primary btn-block" type="submit" value="Login" />
                        <div id="forgot_password"><center><a href="<?=base_url().'index.php/forgot_password'?>"> Forgot Password </a></center></div>
                    </div>

                    <div id="unsupported_browser_panel" style="display: none;">
                        You are using a browser that is currently not compatible with the system you are trying to access. To continue working, please use Google Chrome on Microsoft Windows. If you need further assistance, please contact your system administrator.
                    </div>

    			</div>

                <?= isset($message) ? '<div class="alert alert-success alert-dismissable">'.$message.'</div>' : ''; ?>

    		</div>

    	<!-- </form> -->
      <?Php echo form_close(); ?>


      <div class="row" style="text-align:center">
          <span id="siteseal"><script async type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=nuQEHeuOP2ZP3dN0MDiXblLiDsOCOlu0SenocyBlZTI6KSgHT8TYfZYldnFw"></script></span>
      </div>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="<?php echo js_path('ie10-viewport-bug-workaround.js'); ?>"></script>

    <script src="<?=base_url().'assets/js/jquery-3.2.1.min.js'?>"></script>
    <script src="<?php echo js_path('jquery_ajax.js') . '?' . filemtime('assets/js/jquery_ajax.js'); ?>"></script>

    <!-- Broswerchecking -->
    <script src="<?php echo base_url() . 'assets/js/browser_checker.js?' . filemtime('assets/js/browser_checker.js'); ?>"></script>

    <script src="<?php echo base_url() . 'assets/js/login.js?' . filemtime('assets/js/login.js') ; ?>"></script>
  </body>
</html>
