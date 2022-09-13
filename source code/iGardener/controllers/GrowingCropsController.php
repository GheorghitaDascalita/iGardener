<?php

require_once("../models/Database.php");

class GrowingCropsController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {
			case 'Add crop':
				$this->addCrop($_POST['growingCrop'], $_POST['quantity']);
				break;

			case 'Logout':
				$this->logout();
				break;
			
			case 'watering':
				$this->watering();
				break;

			case 'increaseTemp':
				$this->increaseTemp();
				break;

			case 'harvesting':
				$this->harvesting();
				break;

			case 'First':
				$this->view();
				break;

			default:
				$this->view(true);
				break;
		}
	}

	private function view($redirect = false, $location = "GrowingCrops"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else{
			session_start();
			$this->sensorsService();
			$data = $this->db->growCrops($_SESSION['username']);
			require_once("../views/seller/growingCrops.php");
		}
	}

	private function addCrop($crop, $quantity){
		session_start();
		$this->db->addCrop($_SESSION['username'], $crop, $quantity);
		$this->view(true);
	}

	private function logout(){
		session_start();
		session_destroy();
		$this->view(true,'Login');
	}

	private function watering(){
		header("Content-type: text/xml");

		$id=$_GET["id"];

		$data = $this->db->watering($id);

		if($this->isReady($data)){
			$this->db->setReady($id);
			$data['ready'] = "true";
		}
		else $data['ready'] = "false";

		if($_GET['op'] == "watering") {
?>
	<response>
		<humidity><?= $data['humidity'] ?></humidity>
		<ready><?= $data['ready'] ?></ready>
		<image><?= $data['image'] . $data['growing_level'] . ".jpg" ?></image>
	</response>
<?php

	}

	}

	private function isReady($data){
		if((int)$data['growing_level'] === 4) return true;
		else return false;
	}

	private function increaseTemp(){
		header("Content-type: text/xml");

		$id=$_GET["id"];

		$data = $this->db->increaseTemp($id);

		if($_GET['op'] == "increaseTemp") {
?>
	<response>
		<temperature><?= $data['temperature'] ?></temperature>
	</response>
<?php

	}

	}

	private function harvesting(){
		header("Content-type: text/xml");

		$id=$_GET["id"];


		$data = $this->db->harvest($id);

		$emails = $this->db->updateStatus();
		foreach ($emails as $email) {
			$this->email($email);
		}

		if($_GET['op'] == "harvesting") {
?>
	<response>
		<harvested><?= $data['harvested'] ?></harvested>
	</response>
<?php

	}

	}


	private function email($email){
		$headers = "From: igardenerapp@gmail.com" . "\r\n" . "MIME-Version: 1.0" . "\r\n" . "Content-Type: text/html; charset=utf-8";
		mail($email,"Available order", "We harvested some awesome crops. Now you can buy the flowers you requested.", $headers);
	}


	private function sensorsService(){
		// define ('URL', '../SensorsService.php');

		// $res = file_get_contents(URL);

		// echo $res;

		try{
		$c = curl_init();
		// Check if initialization had gone wrong*    
	    if ($c === false) {
	        throw new Exception('failed to initialize');
	    }

		curl_setopt($c, CURLOPT_URL, '127.0.0.1/Sensors/SensorsService.php?user=' . $_SESSION['username']);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

		$res = curl_exec($c);

	    // Check the return value of curl_exec(), too
	    if ($res === false) {
	        throw new Exception(curl_error($c), curl_errno($c));
	    }

		curl_close($c);
		}
		catch(Exception $e) {

    		trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()),E_USER_ERROR);

		}


		$doc = new DOMDocument();
		$doc->loadXML($res);
		$id = $doc->getElementsByTagName('id');
		$humidity = $doc->getElementsByTagName('humidity');
		$temperature = $doc->getElementsByTagName('temperature');
		$nr=(int)$doc->getElementsByTagName('nr')[0]->nodeValue;
		for ($i=0; $i < $nr; $i++)
   			$this->db->updateCrop($id[$i]->nodeValue, $humidity[$i]->nodeValue, $temperature[$i]->nodeValue);

	}

}

$growingCropsController = new GrowingCropsController();

// $growingCropsController->isReady( array( "growing_level" => 4, "image" => "geraniums") );

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$growingCropsController->select($op);
}
else 
	$growingCropsController->select('First');

?>