<?php
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	if(empty($_SESSION['username']))
		header("Location:LoginController.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>iGardener</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../views/seller/cropsReport.css">
	<link rel="shortcut icon" type="image/x-icon" href="../views/seller/igardener.ico">
</head>

<body>
	<header>
		<nav>
			<ul>
				<li><a href="GrowingCropsController.php">Growing Crops</a></li>
				<li><a href="CropsReportController.php">Crops Report</a></li>
				<li><a id="logout" href="IGardenController.php?op=Logout">Log out</a></li>
			</ul>
		</nav>
	</header>

	<section>
		<div id="h1Center">		
			<h1>Crops Report</h1>
		</div>
		<article>
			<h2>Sold crops:</h2>
			<div id="sold">
				<?php while($rowS = $sold->fetch()) { ?>
					<div class="crop">
						<p><em><?= $rowS['name'] ?></em></p>
						<p>Quantity: <?= $rowS['sold_quantity'] ?></p>
						<p>Total revenue: $<?= ((int)$rowS['sold_quantity'])*((int)$rowS['price']) ?></p>
					</div>
				<?php } ?>
			</div>
		</article>
		<article>
			<h2>Harvested crops:</h2>
			<div id="harvested">
				<?php while($rowH = $harvested->fetch()) { ?>
					<div class="crop">
						<p><em><?= $rowH['name'] ?></em></p>
						<p>Quantity: <?= $rowH['owned_quantity'] ?></p>
					</div>
				<?php } ?>			
			</div>
		</article>
		<article>
			<h2>Growing crops:</h2>
			<div id="growing">
				<?php while($rowG = $growing->fetch()) { ?>
					<div class="crop">
						<p><em><?= $rowG['name'] ?></em></p>
						<p>Quantity: <?= $rowG['owned_quantity'] ?></p>
					</div>
				<?php } ?>	
			</div>
		</article>
	</section>

	<footer>
		
	</footer>

</body>

</html>