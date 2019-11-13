<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dtrmodel_Encode extends CI_Model {

	function __construct(){

		parent::__construct();
		
	}

	public function GPEncrypt($string,$key){

		$encryption_key = base64_decode($key);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
		$encrypted = openssl_encrypt($string, 'aes-256-cbc', $encryption_key, 0, $iv);

		return base64_encode($encrypted . '::' . $iv);

	}

	public function GPDecrypt($string,$key){

		$encryption_key = base64_decode($key);
		list($encrypted_data, $iv) = array_pad(explode('::', base64_decode($string), 2),2,null);

		return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);

	}


}