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
	<link rel="stylesheet" type="text/css" href="../views/buyer/iGarden.css">
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
			<h1>iGarden</h1>
		</div>
		<article id="flowers">
			<?php while($row = $data->fetch()) { ?>
				<div class="form">
					<div>
						<h2><?= $row['name'] ?></h2>
						<img src="../views/buyer/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
						<p>Item price: $<span id="p<?= $row['id'] ?>"><?= $row['price'] ?></span></p>
						<p>Total price: $<span  id="t<?= $row['id'] ?>">0</span></p>
					</div>
					<div>
						<label for="q<?= $row['id'] ?>">Quantity:</label>
						<input id="q<?= $row['id'] ?>" type="number" min="1" name="quantityT" onchange="totalPrice(this)">
					</div>
					<div>
						<button id="b<?= $row['id'] ?>" onclick="want(this.id)">I want to buy!</button>						
					</div>
				</div>

			<?php } ?>
		</article>
	</section>

	<footer>
		
	</footer>

	<script type="text/javascript">

		window.onload = function notifyMe() {
			if (("Notification" in window) && Notification.permission !== "granted" && Notification.permission !== 'denied') {
				Notification.requestPermission();
			}	
		}

		function notify(image) {
		// Let's check if the browser supports notifications
		if ("Notification" in window) 
			// If it's okay let's create a notification
			if (Notification.permission === "granted") {
				var notification = new Notification("Available order", { body: "Now you can buy the flowers you requested.", icon: "../views/buyer/" + image});
				setTimeout(notification.close.bind(notification), 4000);
			}
			// Otherwise, we need to ask the user for permission
			else 
				if (Notification.permission !== 'denied') {
					Notification.requestPermission(function (permission) {
						// If the user accepts, let's create a notification
						if (permission === "granted") {
						var notification = new Notification("Available order", { body: "Now you can buy the flowers you requested.", icon: "../views/buyer/" + image});
						setTimeout(notification.close.bind(notification), 4000);
			    		}
					});
				}	

			  // Finally, if the user has denied notifications and you 
			  // want to be respectful there is no need to bother them any more.
	}

		function totalPrice(values){
			id = values.id.slice(1, values.id.length);
			document.getElementById("t" + id).innerHTML = values.value * document.getElementById("p" + id).innerHTML;
		}

		function want(id){

			id = id.slice(1, id.length);
			quantity = document.getElementById("q"+id).value;
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
			 request.open ("GET", "iGardenController.php?op=want&id=" + id + "&quantity=" + quantity, true);
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

				 	var q = document.getElementById("q"+id);
				 	q.value=1;

					var b = document.getElementById("b"+id);
					b.innerHTML = "Sent request";
					setTimeout(function(){
    					b.innerHTML = 'I want to buy!';
					}, 1500);

				 	var response = request.responseXML.documentElement;
				 	var image = response.getElementsByTagName('image')[0].firstChild.data;
					if(image !== "unavailable") notify(image);

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