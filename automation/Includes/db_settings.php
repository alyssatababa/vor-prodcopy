<?php
class DatabaseSettings
{
	var $settings;

	function getSettings()
	{
		
		$settings['dbhost'] = '10.143.0.141';
		$settings['dbname'] = 'smrivor1';
		$settings['dbusername'] = 'smriapps1';
		$settings['dbpassword'] = 'smriapps1$$';
		
		return $settings;
	}
}
?>