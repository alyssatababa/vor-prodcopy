<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Including Phil Sturgeon's Rest Server Library in our Server file.
require APPPATH . '/libraries/REST_Controller.php';
class Version_hash extends REST_Controller {
	const ROOT = '/opt/rh/httpd24/root/var/www/html'; #online prod
	// const ROOT = '/opt/rh/httpd24/root/var/www/html/dev'; #online dev
	// const ROOT = '/opt/rh/httpd24/root/var/www/html/qa'; #online qa
	// const ROOT = 'F:\inetpub\SMNTP_DEV\app'; #local
	const APP = 'application'; 
	const ASSETS = 'assets'; 
	const JS = 'js'; 
	
	public function __construct() {
		parent::__construct();
	}

	function hashes_get()
	{
		$this->load->library('DirectoryCrawler');
		$hashes['config'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'config',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['controllers'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'controllers',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['libraries'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'libraries',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['models'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'models',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		$hashes['views'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP.DIRECTORY_SEPARATOR.'views',SELF::ROOT.DIRECTORY_SEPARATOR.SELF::APP);
		// $hashes['js'] = $this->directorycrawler->getDirContents(SELF::ROOT.DIRECTORY_SEPARATOR.SELF::ASSETS.DIRECTORY_SEPARATOR.SELF::JS,SELF::ROOT.DIRECTORY_SEPARATOR.SELF::ASSETS.DIRECTORY_SEPARATOR.SELF::JS);
		$this->response($hashes);
	}


}
?>