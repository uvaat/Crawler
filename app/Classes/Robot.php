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
		$this->logFile = storage_path() . '/logs/robots.log';

	}

	private function saveLog($msg){

		error_log('=> ' . date("Y-m-d H:i:s") . "\n", 3, $this->logFile);
		error_log($msg."\n", 3, $this->logFile);

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
	 * Récupérer le stock
	 * @param  String $content contenue de la page (du vendeur)
	 * @return String
	 */
	private function getStock($content){

		$crawler = new Crawler();
		$crawler->add($content);

		$words = array('indisponible', 'plus disponible', 'approvisionnement', 'en stock');
		
		$nodeText = $crawler->filter('div')->each(function ($node, $i) {
		    return $node->text();
		});

		$text = implode($nodeText);
		$text = strtolower(strip_tags($text));
	    foreach ($words as $word) {
	    	if(stripos($text, $word)) return $word;
		}

		return 'Inconnu';

	}

	/**
	 * Récupérer la page du produit
	 * @param  Crawler $crawlerProduct
	 * @return String                 
	 */
	private function getInfosSeller($crawlerProduct){

		// Récupérer la premiere url
		$dataErl = $crawlerProduct->filter('.clr9')->first()->filter('span')->attr('data-erl');

		// Décoder l'url
		$url = str_rot13($dataErl);

		// Récupération de la premiere page de redirection
		$datasFirst = Helpers::curl($url, true);

		$this->saveLog($datasFirst['content']);

		//Récupérer de la 2e url (dans le javascript)
		$regex = '/http?\:\/\/r.twenga.fr\/g2.php?[^\" ]+/i';
		preg_match($regex, $datasFirst['content'], $matches);

		// Récupération des datas de la deuxième page
		$datasSecond = Helpers::curl($matches[0], false);

		// Envoyer l'url final
		return $datasSecond;


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
			
			$infosSeller = $this->getInfosSeller($crawlerProduct);
			$product['url'] = $infosSeller['header']['url'];
			$product['stock'] = $this->getStock($infosSeller['content']);

			$this->saveProduct($product);

			sleep(5);
			
		});

		return $this->products;	
    	
    }

}