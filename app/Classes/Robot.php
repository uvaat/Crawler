<?php
namespace App\Classes;
use Symfony\Component\DomCrawler\Crawler;

class Robot
{
    	
	private $target;
	private $crawler;
	private $infosProduct;

	public function __construct($target){

		$this->crawler = new Crawler();
		$this->target = $target;
		$this->products = array();

	}

	/**
	 * Sauvergarder les produits
	 * @param  Array $product
	 * @return Void          
	 */
	private function saveProduct($product){

		$this->products[] = $product;
		
	}

	/**
	 * Récupérer le produit
	 * @param  Int $maxProduct (nombre de produit)
	 * @return Array
	 */
	private function getProduct($maxProduct){

		return 
		$this->crawler->filter('.ctItem')
		->reduce(function (Crawler $node, $i) use ($maxProduct){

	       	return ($i < $maxProduct) == true;

	    });;

	}

	/**
	 * Récupérer le titre	
	 * @param  Crawler $crawlerProduct
	 * @return String
	 */
	private function getTitle($crawlerProduct){

		return $crawlerProduct->filter('.clr9')->first()->attr('title');

	}

	/**
	 * Récupérer le prix
	 * @param  Crawler $crawlerProduct
	 * @return String
	 */
	private function getPrice($crawlerProduct){

		$price = $crawlerProduct->filter('.price > .unique')->first()->text();
		return preg_replace("/[^0-9\,]/", "", $price);

	}

	/**
	 * Récupérer l'url du produit
	 * @param  Crawler $crawlerProduct
	 * @return String                 
	 */
	private function getUrl($crawlerProduct){

		$dataErl = $crawlerProduct->filter('.clr9')->first()->filter('span')->attr('data-erl');
		$url = str_rot13($dataErl);
		
		return Helpers::get_web_page($url);

	}

	/**
	 * Lancer le script
	 * @param  Int $maxProduct (nombre de produits à afficher)
	 * @return Array Tous les produits
	 */
    public function process($maxProduct = 20){

		$this->crawler->add(file_get_contents($this->target));
			
		$crawlerProducts = $this->getProduct($maxProduct);

		$crawlerProducts->each(function($crawlerProduct){
			
			$product = array();
			$product['title'] = $this->getTitle($crawlerProduct);
			$product['price'] = $this->getPrice($crawlerProduct);
			
			$product['url'] = $this->getUrl($crawlerProduct);

			$this->saveProduct($product);
			
		});

		return $this->products;	
    	
    }

}