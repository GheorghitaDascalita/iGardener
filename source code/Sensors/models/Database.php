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

}

// $db=new Database();
// var_dump($db->cropData('seller1'));

?>