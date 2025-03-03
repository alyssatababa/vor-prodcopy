<?Php

class DirectoryCrawler {

	public function __construct() {

	}

	public function getDirContents($dir, $root){
		$results = array();
		$files = scandir($dir);
	
		foreach($files as $key => $value){
			if ($value == '.svn') continue;
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			$data = array();
			if(!is_dir($path)) {
				$concat = str_replace($root,'',$path);
				$code = str_replace(DIRECTORY_SEPARATOR,'_',$concat);
				$data['full_path'] = $path;
				$data['clean_path'] = str_replace($root,'',$path);
				$data['type'] =  'file';

				
				$content = file_exists($path) ? file_get_contents($path) : '';

				$data['hash'] =  hash('md5',$content);
				$results[$code] = array();
				array_push($results[$code], $data);
			} else if($value != "." && $value != "..") {
				$data = $this->getDirContents($path,$root);
				$results = array_merge($results, $data); # array_push($results, $data);
			}	
		}	
		return $results;
	}
}
?>