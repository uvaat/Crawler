<?php
namespace App\Classes;

class Helpers
{
	
	/**
	 * Récupérer un proxy (via http://gimmeproxy.com/)
	 * @return [type] [description]
	 */
	public static function getProxy(){

		$data = json_decode(file_get_contents('http://gimmeproxy.com/api/getProxy'), 1);
		
		// if(isset($data['error'])){
		// 	echo $data['error']."\n";
		// } 
		
		return isset($data['error']) ? false : $data['curl'];

	}

	public static function curl($url, $refreshProxy = false){

		$options = array( 
			CURLOPT_CONNECTTIMEOUT => 0,
			CURLOPT_TIMEOUT => 1000,
			CURLOPT_URL => $url,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HEADER => 0,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36",
			CURLINFO_HEADER_OUT  => true,
	    ); 
	 
	    $curl      = curl_init($url); 
	    curl_setopt_array($curl, $options); 

	    if($refreshProxy){
	    	// Aller chercher une nouvelle Ip
			$proxy = self::getProxy();
			if($proxy) curl_setopt($curl, CURLOPT_PROXY, $proxy);
			else return false;
	    }

	    $response = array();

	    $response['content'] = curl_exec($curl); 
	    $response['err'] = curl_errno($curl); 
	    $response['errmsg'] = curl_error($curl); 
	    $response['header'] = curl_getinfo($curl);

	    
	    curl_close($curl); 
	 
	    return $response; 

	}

}