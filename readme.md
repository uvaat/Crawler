#Crawler robot

Récupérer les 20 premiers produits de la page http://www.twenga.fr/theiere.html


## Installer

```
git clone https://github.com/uvaat/Crawler.git
```
```
cd Crawler
```
```
composer update
```

## Todo

* [x] Installer Lumen
* [x] Mettre en place le controller
* [x] Appeller la page
* [x] Récupérer le nom du produit (sur la page de twenga)
* [x] Récupérer le prix du produit (sur la page de twenga)
* [ ] Aller sur le site extèrne (pb de redirection et d'accet au site)
* [*] L'url de la page de destination (sur le site du marchand)
* [*] Est-ce que le produit est en stock chez le marchand et/ou sa disponibilité (en se basant sur les infos de la page du marchand pour le cas ou l'information est présente) 

## Sources


https://github.com/brouberol/twenga-crawling/blob/master/crawler.py
