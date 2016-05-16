<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<title>Crawler</title>
</head>
<body>
	
	<div class="container">

		<div class="header clearfix">
			<h1 class="text-muted">Crawler</h1>
		</div>

		<h2>Les produits</h2>

		<?php foreach ($products as $product): ?>

			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $product['title']; ?></h3>
				</div>
				<div class="panel-body">
					<ul>
						<li>Prix : <?php echo $product['price']; ?></li>
						<li>Url : <?php echo $product['url']; ?></li>
						<li><?php echo $product['stock']; ?></li>
					</ul>
				</div>
			</div>

		<?php endforeach ?>	
	</div>

	

</body>
</html>