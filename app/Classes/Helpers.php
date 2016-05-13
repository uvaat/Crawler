<?php
namespace App\Classes;

class Helpers
{

	public static function get_web_page($url){

		$options = array( 
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HEADER         => true,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_ENCODING       => "",
	        CURLOPT_USERAGENT      => "Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)", // who am i 
	        CURLOPT_AUTOREFERER    => true,
	        CURLOPT_CONNECTTIMEOUT => 120,
	        CURLOPT_TIMEOUT        => 120,
	        CURLOPT_MAXREDIRS      => 10,
	    ); 
	 
	    $ch      = curl_init( $url ); 
	    curl_setopt_array( $ch, $options ); 
	    $content = curl_exec( $ch ); 
	    $err     = curl_errno( $ch ); 
	    $errmsg  = curl_error( $ch); 
	    $header  = curl_getinfo($ch); 
	    curl_close( $ch ); 
	 
	    //$header['errno']   = $err; 
	   	//$header['errmsg']  = $errmsg; 
	    //$header['content'] = $content; 
	    // print($header[0]); 
	    return $header; 

	}

}