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
	<link rel="stylesheet" type="text/css" href="../views/seller/growingCrops.css">
	<link rel="shortcut icon" type="image/x-icon" href="../views/seller/igardener.ico">
</head>

<body>
	<header>
		<nav>
			<ul>
				<li>
					<button id="addCrop" onclick="addCrop();">
						Add Crop
					</button>
				</li>
				<li><a href="GrowingCropsController.php">Growing Crops</a></li>
				<li><a href="CropsReportController.php">Crops Report</a></li>
				<li><a id="logout" href="IGardenController.php?op=Logout">Log out</a></li>
			</ul>
		</nav>
	</header>

	<section>
		<div id="h1Center">
			<h1>Growing Crops</h1>		
		</div>
		<article id="addCropArticle">
			<form id="addCropForm" method="POST" action="GrowingCropsController.php">	
				<div>
					<h2>Add Crop</h2>
				</div>
				<div>
					<label>Growing crop:</label>
					<select id="crop" name="growingCrop">
						<option value="Tulips">Tulips</option>
						<option value="Daffodils">Daffodils</option>
						<option value="Hyacinths">Hyacinths</option>
						<option value="Snowdrops">Snowdrops</option>
						<option value="Anemone">Anemone</option>
						<option value="Geraniums">Geraniums</option>
						<option value="Freesias">Freesias</option>
					</select>
				</div>
				<div>
					<label for="quantity">Quantity:</label>
					<input id="quantity" type="number" min="1" name="quantity">
				</div>
				<div>
					<input id="submitButton" type="submit" name="op" value="Add crop">
				</div>
			</form>
		</article>
		<article id="crops">
			<?php while($row=$data->fetch()) { ?>
				<div id="f<?= $row['id'] ?>">
					<h2><em><?= $row['name'] ?></em></h2>
					<img id="img<?= $row['id'] ?>" class="flowers" src="../views/seller/<?= $row['image'] . $row['growing_level'] . ".jpg" ?>" alt="<?= $row['name'] ?>">
					<div>
						<p id="p<?= $row['id'] ?>">Humidity: <?= $row['humidity'] ?>%</p>
						<?php if((int)$row['humidity'] < 10) { ?>
							<button id="w<?= $row['id'] ?>" class="water" onClick="javascript:watering(this.id)">
								Water the <?= $row['name'] ?>!
							</button>
						<?php } ?>
					</div>
					<div>
						<p id="pTemp<?= $row['id'] ?>">Temperature: <?= $row['temperature'] ?>&#8451;</p>
						<?php if((int)$row['temperature'] < 10) { ?>
							<button id="t<?= $row['id'] ?>" class="temperature" onClick="javascript:increaseTemp(this.id)">Increase temperature!</button>
						<?php } ?>
					</div>
					<div>
						<button id="h<?= $row['id'] ?>" class="harvest" style="display: <?php if((boolean)$row['ready']) echo "inline-block"; else echo "none"; ?>" onClick="javascript:harvesting(this.id)">
								Harvest the <?= $row['name'] ?>!
						</button>
					</div>
				</div>
			<?php } ?>
		</article>
	</section>

	<footer>
		
	</footer>

	<script type="text/javascript">
var add = 1;
function addCrop(){
	if(add === 1){
		document.getElementById("addCropArticle").style.display="block";
		add = 0;
	}
	else{
		document.getElementById("addCropArticle").style.display="none";
		add = 1;		
	}
}

var request;
function watering(id){

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
	 request.open ("GET", "../controllers/GrowingCropsController.php?op=watering&id=" + id, true);
	 request.send (null);
	} else {
		 // nu exista suport pentru Ajax
		 console.log ('No Ajax support :(');
	}
}

// functia de tratare a schimbarii de stare a cererii
function handleResponse (id) {
	if(request.readyState == 1){
		var w = document.getElementById("w"+id);
		w.style.display = "none";		
	}
	else
		// verificam daca incarcarea s-a terminat cu succes
		if (request.readyState == 4) {
			 // verificam daca am obtinut codul de stare '200 Ok'
			 if (request.status == 200) {
			 	  // procesam datele receptionate prin DOM
			 	  // (preluam elementul radacina al documentului XML)
			 	  var response = request.responseXML.documentElement;
			 	  var humidity = response.getElementsByTagName('humidity')[0].firstChild.data;
			 	  var ready = response.getElementsByTagName('ready')[0].firstChild.data;
			 	  var image = response.getElementsByTagName('image')[0].firstChild.data;

			 	  var h = document.getElementById("p"+id);
			 	  h.innerHTML = "Humidity: " + humidity + "%";

			 	  var r = document.getElementById("h"+id);
			 	  if (ready == "true") r.style.display = "inline-block";
			 	  else r.style.display = "none";

			 	  var img = document.getElementById("img"+id);
			 	  img.src = "../views/seller/" + image;
			 }
			 // eventual, se pot trata si alte coduri HTTP (404, 500 etc.)
			 else { // eroare...
			 	  console.log ("A problem occurred (XML data transfer):\n" +
			 	    response.statusText);
			 }
		} // final de if
}

function increaseTemp(id){

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
	 	handleResponseTemp(id);
	 };
	 // preluam documentul prin metoda GET
	 request.open ("GET", "../controllers/GrowingCropsController.php?op=increaseTemp&id=" + id, true);
	 request.send (null);
	} else {
		 // nu exista suport pentru Ajax
		 console.log ('No Ajax support :(');
	}
}

// functia de tratare a schimbarii de stare a cererii
function handleResponseTemp (id) {
	// verificam daca incarcarea s-a terminat cu succes
	if (request.readyState == 4) {
		 // verificam daca am obtinut codul de stare '200 Ok'
		 if (request.status == 200) {
		 	  // procesam datele receptionate prin DOM
		 	  // (preluam elementul radacina al documentului XML)
		 	  var response = request.responseXML.documentElement;
		 	  var temperature = response.getElementsByTagName('temperature')[0].firstChild.data;

		 	  var pTemp = document.getElementById("pTemp"+id);
		 	  pTemp.innerHTML = "Temperature: " + temperature + '&#8451';
		 	  var t = document.getElementById("t"+id);
		 	  t.style.display = "none";

		 }
		 // eventual, se pot trata si alte coduri HTTP (404, 500 etc.)
		 else { // eroare...
		 	  console.log ("A problem occurred (XML data transfer):\n" +
		 	    response.statusText);
		 }
	} // final de if
}

function harvesting(id){

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
	 	handleResponseHarvest(id);
	 };
	 // preluam documentul prin metoda GET
	 request.open ("GET", "../controllers/GrowingCropsController.php?op=harvesting&id=" + id, true);
	 request.send (null);
	} else {
		 // nu exista suport pentru Ajax
		 console.log ('No Ajax support :(');
	}
}

// functia de tratare a schimbarii de stare a cererii
function handleResponseHarvest (id) {
	// verificam daca incarcarea s-a terminat cu succes
	if (request.readyState == 4) {
		 // verificam daca am obtinut codul de stare '200 Ok'
		 if (request.status == 200) {

		 	var f = document.getElementById("f"+id);
			while (f.firstChild) {
			    f.removeChild(f.firstChild);
			}

			var para = document.createElement("P");
			var textNode = document.createTextNode("Harvested " + "\u2713");
			para.appendChild(textNode);
			para.setAttribute("class","harvested");
			f.appendChild(para);

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