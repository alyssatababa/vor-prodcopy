<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

/*	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}*/

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
	}

	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	/* #container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		display: inline-block;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	} */
</style>
<div class="container mycontainer">
	<h1><?=$app_server_url?></h1>

	<div id="body">
		<form id="form_api" name="form_api" action="<?php echo base_url().'index.php/test/api_tester/execute_api'?>" method="post" style="text-align: left;">
			<b>Method:</b> <?=form_dropdown('in_method', $method_array,'', ' id="in_method"');?>
			<br /><br />
			<b>URI:</b> <input name="in_uri" id = "in_uri" type="text" value="index.php/menus/menu"/>
			<br /><br />
			<b>Param key:</b> <input name="in_key1" id = "in_key1" type="text" /> <b>Param Val:</b> <input name="in_val1" id = "in_val1" type="text" />
			<br /><br />
			<b>Param key:</b> <input name="in_key2" id = "in_key2" type="text" /> <b>Param Val:</b> <input name="in_val2" id = "in_val2" type="text" />
			<br /><br />
			<b>Param key:</b> <input name="in_key3" id = "in_key3" type="text" /> <b>Param Val:</b> <input name="in_val3" id = "in_val3" type="text" />
			<br /><br />
			<b>Param key:</b> <input name="in_key4" id = "in_key4" type="text" /> <b>Param Val:</b> <input name="in_val4" id = "in_val4" type="text" />
			<br /><br />
			<input type="submit" value="Submit" />
		</form>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds, CI ver: <strong><?=CI_VERSION;?></strong></p>
</div>
