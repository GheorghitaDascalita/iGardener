<?php

require_once("models/Database.php");

class SensorsService{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function sensorsXML($user){
		date_default_timezone_set('Europe/Bucharest');
		$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"utf-8\" ?><sensors/>");

		$xml->addChild("nr", $this->db->cropsNr($user));

		$cropData = $this->db->cropData($user);

		while($row = $cropData->fetch()){
		    $sensor = $xml->addChild('sensor');
		    $sensor->addChild('id', $row['id']);

		    $time1 = new DateTime( $row['recent_temp_checking'] );
		    $time2 = new DateTime(date('m/d/Y H:i:s'));
		    $diff = $time2->getTimestamp() - $time1->getTimestamp();
		    if($diff > 10) $temperature = $row['temperature'] + rand(-5,5);
		    else $temperature = $row['temperature'] + rand(-2,2);
		    if($temperature < 0) $temperature = 5;
		    else if($temperature > 40) $temperature = 35;

		    $time3 = new DateTime( $row['recent_watering'] );		    
		    $diff = $time2->getTimestamp() - $time3->getTimestamp();
		    if($diff < 0) $diff = 0;
		    else if($diff > 10) $diff = 10;
		    $humidity = 20 - floor($diff * 2);

		    $sensor->addChild('temperature', $temperature);
		    $sensor->addChild('humidity', $humidity);
		    $sensor->addChild('image', $row['image'] . $row['growing_level'] . '.jpg');
		}

		header('Content-type: text/xml');
		echo($xml->asXML());
	}

}

$s = new SensorsService();
$s->sensorsXML($_GET['user']);
?>