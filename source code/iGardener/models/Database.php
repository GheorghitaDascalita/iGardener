<?php

class Database{

	private $pdo;

	public function __construct(){
		// datele de conectare la serverul de baze de date
		$host = '127.0.0.1';
		$db   = 'iGardener';
		$user = 'root';
		$pass = '';
		$charset = 'UTF8';

		$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

		// optiuni vizand maniera de conectare
		$opt = [
			// erorile sunt raportate ca exceptii de tip PDOException
		    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		    // rezultatele vor fi disponibile in tablouri asociative
		    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		    // conexiunea e persistenta
		    PDO::ATTR_PERSISTENT 		 => TRUE
		];


		try {
			// instantiem un obiect PDO
		  	$this->pdo = new PDO ($dsn, $user, $pass, $opt);

		  } catch (PDOException $e) {
		  	echo "Eroare: " . $e->getMessage(); // mesajul exceptiei survenite
		};

	}

	public function login($type, $user, $pass){

		$pass = sha1($pass);

	  	// pregatim comanda SQL parametrizata
	  	if($type === "seller")
	  		$sql = $this->pdo->prepare ('SELECT username, password FROM sellers WHERE username=? and password=?');
	  	else 
	  		$sql = $this->pdo->prepare ('SELECT username, password FROM buyers WHERE username=? and password=?');

	  	// daca s-a putut executa...
		if ($sql->execute ([$user, $pass])) 
			if($row = $sql->fetch())
				if($row['username'] === $user && $row['password'] === $pass)
					return true;
		return false;

  	}

  	public function signup($type, $user, $email, $pass){

		$pass = sha1($pass);

	  	// pregatim comanda SQL parametrizata
	  	if($type === "seller")
	  		$sql = $this->pdo->prepare ('SELECT count(*) as total FROM sellers WHERE username=? or email=?');
	  	else 
	  		$sql = $this->pdo->prepare ('SELECT count(*) as total FROM buyers WHERE username=? or email=?');

	  	// daca s-a putut executa...
		if ($sql->execute ([$user, $email])) 
			if($row = $sql->fetch())
				if($row['total'] === "0"){
					// pregatim comanda SQL parametrizata
				  	if($type === "seller"){
				  		$sql = $this->pdo->prepare ('SELECT max(id) as max FROM sellers');
				  		if ($sql->execute()) 
							if($id = $sql->fetch()){
								$id = (string)(((int)$id['max']) + 1);
								$sql = $this->pdo->prepare ('INSERT into sellers values(?,?,?,?)');
								if ($sql->execute ([$id, $user, $pass, $email])) return true;
							}
				  	}
				  	else {
				  		$sql = $this->pdo->prepare ('SELECT max(id) as max FROM buyers');
				  		if ($sql->execute()) 
							if($id = $sql->fetch()){
								$id = (string)(((int)$id['max']) + 1);
								$sql = $this->pdo->prepare ('INSERT into buyers values(?,?,?,?,null)');
								if ($sql->execute ([$id, $user, $pass, $email])) return true;
							}
				  	}
				}
					
		return false;
				

  	}

  	public function igarden(){
  		$sql = $this->pdo->prepare ('SELECT * FROM crops');
  		if($sql->execute()) return $sql;
  		else return null;
  	}

  	public function growCrops($user){
  		$sql = $this->pdo->prepare ('SELECT g.id, c.name, g.image, g.humidity, g.temperature, g.growing_level, g.ready FROM sellers s join grow_crops g on (s.id = g.id_seller and s.username = ?) join crops c on g.id_crop = c.id where harvested = false');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}

  	public function growing($user){
  		$sql = $this->pdo->prepare ('SELECT c.name, g.owned_quantity FROM sellers s join grow_crops g on (s.id = g.id_seller and s.username = ?) join crops c on g.id_crop = c.id where harvested = false and sold=false');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}

  	public function harvested($user){
  		$sql = $this->pdo->prepare ('SELECT c.name, g.owned_quantity FROM sellers s join grow_crops g on (s.id = g.id_seller and s.username = ?) join crops c on g.id_crop = c.id where harvested=true and g.owned_quantity > 0');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}

   	public function sold($user){
  		$sql = $this->pdo->prepare ('SELECT c.name, c.price, g.sold_quantity FROM sellers s join grow_crops g on (s.id = g.id_seller and s.username = ?) join crops c on g.id_crop = c.id where sold=true');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}

  	public function updateCrop($id,$humidity,$temperature){
  		$sql = $this->pdo->prepare ('update grow_crops set humidity=?, temperature=?, recent_temp_checking=CURRENT_TIMESTAMP where id=?');
  		if($sql->execute([$humidity,$temperature, $id])) return true;
  		else return false;
  	}

  	public function cropsNr($user){
  		$sql = $this->pdo->prepare ('SELECT count(*) as total FROM grow_crops g join sellers s on g.id_seller = s.id where s.username=?');
  		if($sql->execute([$user])) {
  			$row=$sql->fetch();
  			return $row['total'];
  		}
  		return 0;
  	}

  	public function cropData($user){
  		$sql = $this->pdo->prepare ('SELECT g.id, recent_watering, recent_temp_checking, temperature, growing_level, g.image, humidity from grow_crops g join sellers s on g.id_seller=s.id where s.username=?');
  		if($sql->execute([$user]))
  			return $sql;
  		else return null;
	}

	public function addCrop($user, $crop, $quantity){
  		$sql = $this->pdo->prepare ('SELECT max(id) as max FROM grow_crops');
  		if ($sql->execute()) 
			if($id = $sql->fetch()){
				$id = (string)(((int)$id['max']) + 1);
				$sql = $this->pdo->prepare ('SELECT id FROM sellers where username=?');
				if ($sql->execute([$user]))
					if($row = $sql->fetch()){
						$id_seller=$row['id'];
						$sql = $this->pdo->prepare ('SELECT id FROM crops where name=?');
						if ($sql->execute([$crop]))
							if($row = $sql->fetch()){
								$id_crop=$row['id'];
								$sql = $this->pdo->prepare ('INSERT into grow_crops values(?,?,?,?,0,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP,0,lower(?),20,20,0,0,0)');
								if ($sql->execute([$id, $id_seller, $id_crop, $quantity, $crop])) return true;
							}
					}
			}
		return false;
	}

	public function wantFlowers($user, $crop, $quantity){
  		$sql = $this->pdo->prepare ('SELECT max(id) as max FROM orders');
  		if ($sql->execute()) 
			if($id = $sql->fetch()){
				$id = (string)(((int)$id['max']) + 1);
				$sql = $this->pdo->prepare ('SELECT id FROM buyers where username=?');
				if ($sql->execute([$user]))
					if($row = $sql->fetch()){
						$id_buyer=$row['id'];
						$sql = $this->pdo->prepare ('SELECT id FROM crops where id=?');
						if ($sql->execute([$crop]))
							if($row = $sql->fetch()){
								$id_crop=$row['id'];
								$sql = $this->pdo->prepare ('INSERT into orders values(?,?,?,?,CURRENT_TIMESTAMP,?,false)');
								if ($sql->execute([$id,$id_buyer,$id_crop,$quantity,"unavailable"])) return $id;
							}
					}
			}
		return null;
	}

	public function watering($id){
  		$sql = $this->pdo->prepare ('update grow_crops set humidity=20, recent_watering=CURRENT_TIMESTAMP where id=?');
  		if($sql->execute([$id])){
  			$sql = $this->pdo->prepare ('update grow_crops set growing_level = growing_level + 1 where id=? and growing_level < 4');
  			if($sql->execute([$id])){  			
	  			$sql = $this->pdo->prepare ('SELECT humidity, growing_level, image, ready FROM grow_crops where id=?');
	  				if($sql->execute([$id])){
	  					if($row = $sql->fetch()) return $row;
	  				}
  			}
  		}
  		return null;
	}

	public function setReady($id){
  		$sql = $this->pdo->prepare ('update grow_crops set ready=1 where id=?');
  		if($sql->execute([$id])) return true;
  		else return false;
	}

	public function increaseTemp($id){
  		$sql = $this->pdo->prepare ('update grow_crops set temperature=20, recent_temp_checking=CURRENT_TIMESTAMP where id=?');
  		if($sql->execute([$id])){
  			$sql = $this->pdo->prepare ('SELECT temperature FROM grow_crops where id=?');
  				if($sql->execute([$id]))
  					if($row = $sql->fetch()) return $row;			
  		}
  		return null;
	}

	public function harvest($id){
  		$sql = $this->pdo->prepare ('update grow_crops set harvested=true where id=?');
  		if($sql->execute([$id])){
  			$sql = $this->pdo->prepare ('SELECT harvested FROM grow_crops where id=?');
  				if($sql->execute([$id]))
  					if($row = $sql->fetch()) return $row;			
  		}
  		return null;
	}

  	public function desiredFlowers($user){
  		$sql = $this->pdo->prepare ('SELECT c.name, c.price, c.image, o.quantity, o.status, o.id FROM orders o join crops c on o.id_crop = c.id join buyers b on b.id = o.id_buyer where b.username=? and o.bought=false');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}
  	public function buy($idOrder){
  		$sql = $this->pdo->prepare('SELECT id_crop, quantity, bought from orders where id=?');
  		if($sql->execute([$idOrder])){
  			if($row = $sql->fetch()){
  				if($row['bought'] == "1") return false; 
  				$idCrop = $row['id_crop'];
  				$quantity = $row['quantity'];
  				$sql = $this->pdo->prepare('SELECT sum(owned_quantity) as sum from grow_crops where id_crop=? and harvested=true');
	  			if($sql->execute([$idCrop])){
	  				if($row = $sql->fetch()){
	  					if((int)$row['sum'] >= (int)$quantity){
			  				$sql3 = $this->pdo->prepare('update orders set bought=true where id=?');
			  				if(!$sql3->execute([$idOrder])) return false;	

					  		$sql = $this->pdo->prepare('SELECT * from grow_crops where id_crop=? and harvested=true and owned_quantity>0');
					  		if($sql->execute([$idCrop])){
					  			while($row = $sql->fetch()){	
					  				if($quantity <= $row['owned_quantity']) {
						  				$sql2 = $this->pdo->prepare('update grow_crops set owned_quantity=owned_quantity-?, sold_quantity=sold_quantity+?, sold=true where id=?');
						  				if($sql2->execute([$quantity, $quantity, $row['id']])) return true;
						  			}
						  			else{
						  				$sql2 = $this->pdo->prepare('update grow_crops set owned_quantity=0, sold_quantity=sold_quantity+?, sold=true where id=?');
						  				if(!$sql2->execute([$row['owned_quantity'], $row['id']]))	return false;
						  				$quantity = $quantity - $row['owned_quantity'];			
						  			}

					  			} 
					  		}
				  		
					  	}						
	  				}
	  			}
  			}

  		}
  		return false;
  	}

  	public function buyerData($user){
  		$sql = $this->pdo->prepare ('SELECT username, email, address from buyers where username = ?');
  		if($sql->execute([$user])) 
  			if($row = $sql->fetch()) return $row;
  		else return null; 		
  	}

  	public function boughtFlowers($user){
  		$sql = $this->pdo->prepare ('SELECT c.name, c.price, c.image, o.quantity, o.date, o.id FROM orders o join crops c on o.id_crop = c.id join buyers b on b.id = o.id_buyer where b.username=? and o.bought=true');
  		if($sql->execute([$user])) return $sql;
  		else return null;
  	}

  	public function updateThisStatus($idOrder, $idCrop, $quantity){
		$sql = $this->pdo->prepare('SELECT sum(owned_quantity) as sum from grow_crops where id_crop=? and harvested=true');
		if($sql->execute([$idCrop])){
			if($row = $sql->fetch()){
				if((int)$row['sum'] >= (int)$quantity){
	  				$sql = $this->pdo->prepare('update orders set status=? where id=?');
	  				if(!$sql->execute(["available", $idOrder])) return "unavailable";

			  		$sql = $this->pdo->prepare('SELECT image from crops where id=?');
			  		if($sql->execute([$idCrop])){
			  			if($row = $sql->fetch()){ 
			  				return $row['image'];
			  			}
			  		}
			  	}
			  	else{
	  				$sql = $this->pdo->prepare('update orders set status=? where id=?');
	  				$sql->execute(["unavailable", $idOrder]);	  		
			  	}  	
			}	
		}
		
		return "unavailable";
	}  	

  	public function updateStatus(){
  		$emails = array();
  		$sql1 = $this->pdo->prepare('SELECT id, id_crop, quantity, status from orders where bought=false');
  		if($sql1->execute()){
  			while($row1 = $sql1->fetch()){ 
				$idOrder = $row1['id'];
  				$idCrop = $row1['id_crop'];
  				$quantity = $row1['quantity'];
  				$status = $row1['status'];
  				$sql = $this->pdo->prepare('SELECT sum(owned_quantity) as sum from grow_crops where id_crop=? and harvested=true');
	  			if($sql->execute([$idCrop])){
	  				if($row = $sql->fetch()){
	  					if((int)$row['sum'] >= (int)$quantity){
	  						if($status === "unavailable"){
						  		$sql = $this->pdo->prepare('SELECT email from buyers b join orders o on b.id = o.id_buyer where o.id=?');
						  		if($sql->execute([$idOrder])){
						  			if($row = $sql->fetch()) array_push($emails, $row['email']);
						  			else return null;
						  		}
						  		else return null;

				  				$sql = $this->pdo->prepare('update orders set status=? where id=?');
			  					if(!$sql->execute(["available", $idOrder])) return null;
			  				}
					  	}
					  	else{
			  				$sql = $this->pdo->prepare('update orders set status=? where id=?');
			  				if(!$sql->execute(["unavailable", $idOrder])) return null;	  		
					  	}  	
					}
					else return null;		
				}
				else return null;		
			}		
		}
		else return null;

		return $emails;
	}

}

// $db=new Database();

			

?>