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
	<link rel="stylesheet" type="text/css" href="../views/buyer/myGarden.css">
	<link rel="shortcut icon" type="image/x-icon" href="../views/buyer/igardener.ico">
</head>
<body>

	<header>
		<nav>
			<ul>
				<li><a href="IGardenController.php">iGarden</a></li>
				<li><a href="DesiredFlowersController.php">Desired Flowers</a></li>
				<li><a href="MyGardenController.php">My Garden</a></li>
				<li><a id="logout" href="IGardenController.php?op=Logout">Log out</a></li>
			</ul>
		</nav>
	</header>

	<section>
		<div id="h1Center">			
			<h1>My Garden</h1>
		</div>
		<article id="account">
			<h2>Account</h2>
			<p><strong>Username</strong>: <?= $buyer['username'] ?></p>
			<p><strong>Email</strong>: <?= $buyer['email'] ?></p>
			<address><strong>Address</strong>: <?= $buyer['address'] ?></address>
		</article>
		<article id="myGarden">
			<h2>My Flowers</h2>
			<div id="flowers">
				<?php while($row = $flowers->fetch()) { ?>
					<div class="flowerType">
						<p><strong><?= $row['name'] ?></strong></p>
						<img src="../views/buyer/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
						<p>Quantity: <?= $row['quantity'] ?></p>
						<p>Total price: $<?= ((int)$row['price'])*((int)$row['quantity']) ?></p>
						<label>Date: 
							<time datetime="<?= $row['date'] ?>"><?= $row['date'] ?></time>
						</label>
					</div>
				<?php } ?>
			</div>
		</article>
	</section>

	<footer>
		
	</footer>

</body>

</html>