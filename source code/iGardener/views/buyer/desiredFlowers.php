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
	<link rel="stylesheet" type="text/css" href="../views/buyer/desiredFlowers.css">
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
			<h1>Desired Flowers</h1>
		</div>
		<article id="flowers">
			<?php while($row = $data->fetch()) { ?>
			<div id="f<?= $row['id'] ?>" class="form">
				<div>
					<h2><?= $row['name'] ?></h2>
					<img src="../views/buyer/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
					<p>Item price: $<?= $row['price'] ?> </p>
					<p>Total price: $<?= ((int)$row['price'])*((int)$row['quantity']) ?></p>
					<p>Quantity: <?= $row['quantity'] ?></p>
					<p><em>Status: <?= $row['status'] ?></em></p>
				</div>
				<div>
					<button id="o<?= $row['id'] ?>" style="display: <?php if($row['status'] == 'available') echo 'inline-block'; else echo 'none'; ?>" onclick="buy(this.id);">
					Buy!
					</button>
				</div>
			</div>
			<?php } ?>
		</article>
	</section>

	<footer>
		
	</footer>

	<script type="text/javascript">
	function buy(id){
			id = id.slice(1, id.length);

			  // verificam existenta obiectului XMLHttpRequest
			if (window.XMLHttpRequest) { 
				 // exista suport nativ
			 request = new XMLHttpRequest ();
			}
			else 
			 if (window.ActiveXObject) {   
				 	// se poate folosi obiectul ActiveX din vechiul MSIE
				 	request = new ActiveXObject ("Microsoft.XMLHTTP");
				 }

			if (request) {	
			 // stabilim functia de tratare a starii incarcarii
			 request.onreadystatechange = function(){
			 	handleResponse(id);
			 };
			 // preluam documentul prin metoda GET
			 request.open ("GET", "DesiredFlowersController.php?op=buy&id=" + id, true);
			 request.send (null);
			} else {
				 // nu exista suport pentru Ajax
				 console.log ('No Ajax support :(');
			}
		}

		// functia de tratare a schimbarii de stare a cererii
		function handleResponse (id) {
			// verificam daca incarcarea s-a terminat cu succes
			if (request.readyState == 4) {
				 // verificam daca am obtinut codul de stare '200 Ok'
				 if (request.status == 200) {

				 	var response = request.responseXML.documentElement;
				 	var message = response.getElementsByTagName('response')[0].firstChild.data;


				 	var f = document.getElementById("f"+id);
					while (f.firstChild) {
					    f.removeChild(f.firstChild);
					}
				 	
					var para = document.createElement("P");
					var textNode = document.createTextNode(message);
					para.appendChild(textNode);
					para.setAttribute("class","bought");
					f.appendChild(para);				 	

					setTimeout(function(){
    					f.style.display = 'none';
					}, 3000);

				 }
				 // eventual, se pot trata si alte coduri HTTP (404, 500 etc.)
				 else { // eroare...
				 	  console.log ("A problem occurred (XML data transfer):\n" +
				 	    response.statusText);
				 }
			} // final de if
		}
	</script>
</body>

</html>