<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Bootstrap core CSS -->
		<link href="<?php echo base_url(); ?>assets/dist/css/bootstrap.min.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<style type="text/css">
			#PageHeader{position: running(pageHeader!important);}
			.print-css td {width:100px!important;}
			tr {width:100px!important;}
			#ca_back{display:none;}
			#ra_back{display:none;}
			#po_back{display:none;}
		</style>
		 <script>
			 setTimeout(function()
                 {
                   window.print();
                   self.close();

                 } , 300);
		  </script>
  </head>
  <body>
	<div class="container">
		<?php $this->load->view('businessdoc/'.$report_template); ?>
	</div>
  </body>
</html>
