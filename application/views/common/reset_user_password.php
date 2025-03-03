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

    <title>Reset Password</title>

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
  <input type="hidden" name="base_url" id="base_url" value="<?=base_url()?>">
  <body class="login">

    <div class="container">

     <form id="reset_pass_form" class="form-signin" method="POST">

        <input type="hidden" name="token" id="token" value="<?=$token?>">
        <input type="hidden" name="user_id" value="<?=$user_id?>">

				<img class="signin-logo" src="<?=base_url()?>/assets/img/client_logo.png">

        <div class="panel panel-primary">

				  <div class="panel-heading">
				    <h5 class="form-signin-heading">Reset Password</h5>
				  </div>
					<div id="no-script-panel">
					<?php echo @$no_js_message; ?>
					</div>

					<div class="panel-body">
            Change password for username: <label><?=$username?></label>
            <div class="form-group">
              <label for="new_password" class="sr-only">New Password</label>
  						<input type="password" id="new_password" name="new_password" class="form-control" value="" pattern=".{6,12}" title="passwords must not be less than 6 characters and must not exceed 12 characters" required>
            </div>

            <div class="form-group">
              <label for="confirm_password" class="sr-only">Confirm Password</label>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control" value="" pattern=".{6,12}" title="passwords must not be less than 6 characters and must not exceed 12 characters" placeholder="Confirm Password" required>
            </div>

            <div class="form-group">
              <button id="submit_btn" class="btn btn-lg btn-primary btn-block" type="button" data-loading-svg="<?=base_url().'assets/img/loading_ring.svg'?>">CHANGE PASSWORD</button>
  						<input type="submit" style="display: none">
            </div>

            <center><span id="err_msg" style="display: none;" data-is-token-valid="<?=$is_token_valid?>"><?=$err_msg?></span></center>
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
        var base_url = document.getElementById('base_url').value;

        var password = document.getElementById("new_password");
        var confirm_password = document.getElementById("confirm_password");
        var $submit_btn = $("#submit_btn");
        var loading_svg = '<img id="loading_ring" src="' + $submit_btn.data('loading-svg') + '" alt="loading_ring.svg" style="height: 25px;">';

        var $form = $('#reset_pass_form');
        var $err_msg = $form.find("#err_msg");

        function passwords_match()
        {
          if(password.value != confirm_password.value) {
            $err_msg.text("Password Don't Match").show();
            return false;
          } else {
            $err_msg.hide();
            return true;
          }
        }

        function reset_password()
        {
          if (password.value == '' || confirm_password.value == '') {
            $err_msg.text("Please fill in both fields").show();
            return;
          }
          else if (!passwords_match()) {
            return;
          }

          $form
            .submit((e) => e.preventDefault()) // prevent submission and reloading of page
            .find('[type="submit"]').trigger('click');

          // if there are invalid inputs (blanks), do not proceed
          if($form.find(":invalid").length > 0) {
            return;
          }

          var orig_btn_lbl = $(this).html();
          $(this).html(loading_svg).prop('disabled', true);

          var ajax_type = 'post';
          var url = base_url + "index.php/forgot_password/reset_password";
          var params = $form.serialize();
          var success_params = function(responseText)
          {
            let obj = $.parseJSON(responseText);
            $err_msg.html(obj.message).show();

            if (obj.err_code !== 0) {
              $(this).prop('disabled', false);
            }

            $(this).html(orig_btn_lbl);

          }.bind($(this));

          ajax_request(ajax_type, url, params, success_params);
        }

        $submit_btn.on('click', reset_password);

        if ($err_msg.data('is-token-valid') == 'invalid') {
          $submit_btn.prop('disabled', true).off();
          $err_msg.show();
        }
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
