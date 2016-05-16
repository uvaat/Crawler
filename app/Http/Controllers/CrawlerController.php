<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Classes\Robot;

class CrawlerController extends BaseController
{
    
    public function get(){

		$robot = new Robot('http://www.twenga.fr/theiere.html');
        $products = $robot->process(1);
        return view('crawler', array('products' => $products));
    	
    }

}