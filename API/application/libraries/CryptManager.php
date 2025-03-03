<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CryptManager {

	private $iv = 'XXXXXXXXXXXXXXXX'; #Same as in JAVA
    private $key = 'IAmYourPasswordX'; #Same as in JAVA
	private $cipher = 'rijndael-128';
	private $mode = 'cbc';


	//CONSTANTS
	//dirs
	var $SAXPath = "system/application/db/SAX.xml";
	var $LOGPath = "system/application/db/log/";


	var $CI = null;

	public function __construct($params = null)
	{
		$this->CI = & get_instance();
	}

	public function CryptManager()
	{
		$this->CI = & get_instance();
	}


	public function init_padding($hash){

		$file_crypt_key = $hash;

		$pad = 16 - strlen($file_crypt_key);
		$pad_str = '';
		$pad_ctr = 0;

		if(strlen($file_crypt_key) < 16){
			while($pad_ctr != $pad){
				$pad_str .= 'X';
				$pad_ctr++;
			}
			$file_crypt_key = $file_crypt_key.$pad_str;
		} else {
			$file_crypt_key = substr($file_crypt_key, 0, 16);
		}

		return $file_crypt_key;
	}

	public function is_file_encrypted($file,$file_crypt_key){

		/* verify if file is not tampered */
		$file_array = explode("_", basename($file));
		$array_count = count($file_array);
		$data['hashcode_verifier'] = '';
		$data['hashcode'] = '';
		if($array_count >= 6){
			/* decrypt here */
			$ext = substr(basename($file),strpos(basename($file), "."));
			$hashcode = str_replace($ext, '', $file_array[5]);
			$string_data = $this->init_decrypt($file,$file_crypt_key);
			$hashcode_verifier = hash("md5",$string_data);

			if(strcmp($hashcode_verifier,$hashcode) == 0){
				$is_file_valid = true;
			} else {
				$is_file_valid = false;
			}
			$data['hashcode_verifier'] = $hashcode_verifier;
			$data['hashcode'] = $hashcode;
			$is_file_encrypted = true;

		} else {
			/* do not decrypt file */
			$string_data = file_get_contents($file);
			$is_file_valid = true;
			$is_file_encrypted = false;
		}

		$data['array_count'] = $array_count;
		$data['is_file_valid'] = $is_file_valid;
		$data['string_data'] = $string_data;
		$data['is_file_encrypted'] = $is_file_encrypted;

		return $data;
	}

	function init_decrypt($file_path,$file_crypt_key)
	{
		$f1 = fopen($file_path, "r");
		$str = fread($f1, filesize($file_path));
		fclose($f1);
		$decrypted_string = $this->decrypt($str, true, $file_crypt_key);

		return $decrypted_string;
	}

	/**
     * @param string $str
     * @param bool $isBinary whether to encrypt as binary or not. Default is: false
     * @return string Encrypted data
     */
	function encrypt($str, $isBinary = false, $file_crypt_key)
	{
		$iv = $this->iv;
		$str = $isBinary ? $str : base64_encode($str);

	   # Add PKCS7 padding.
	   $block = mcrypt_get_block_size($this->cipher, $this->mode);
	   $pad = $block - (strlen($str) % $block);
	   // echo 'pad: '.$pad.', block: '.$block;
	   if ($pad <= $block) {
		   $str .= str_repeat(chr($pad), $pad);
	   }

		$td = mcrypt_module_open($this->cipher, ' ', $this->mode, $iv);

		mcrypt_generic_init($td, $file_crypt_key, $iv);
		$encrypted = mcrypt_generic($td, $str);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	   //$encrypted = base64_encode($encrypted);
		return $isBinary ? $encrypted : bin2hex($encrypted);
	}

	/**
	 * @param string $code
	 * @param bool $isBinary whether to decrypt as binary or not. Default is: false
	 * @return string Decrypted data
	 */
	function decrypt($code, $isBinary = false, $file_crypt_key)
	{
		$code = $isBinary ? $code : hex2bin($code);
		$iv = $this->iv;

		$td = mcrypt_module_open($this->cipher, ' ', $this->mode, $iv);

		mcrypt_generic_init($td, $file_crypt_key, $iv);
		$decrypted = mdecrypt_generic($td, $code);

		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);

		//return $isBinary ? trim($decrypted) : utf8_encode(trim($decrypted));


	   # Strip padding out.
	   #$str = $isBinary ? trim($decrypted) : utf8_encode(trim($decrypted));
	   $str = $decrypted;
	   $block = mcrypt_get_block_size($this->cipher, $this->mode);
	   $pad = ord($str[($len = strlen($str)) - 1]);

	   //  preg_match('/' . chr($pad) . '{' . $pad . '}$/', $str)
	   if ($pad && $pad <= $block )
	   {
		   $str = substr($str, 0, strlen($str) - $pad);
	   }
	   return $str;
	}

}
