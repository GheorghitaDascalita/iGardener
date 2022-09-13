<?php

require_once("../models/Database.php");

class MyGardenController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {
			case 'Logout':
				$this->logout();
				break;
			
			case 'First':
				$this->view();
				break;

			default:
				$this->view(true);
				break;
		}
	}

	private function view($redirect = false, $location = "MyGarden"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else{
			session_start();
			$buyer = $this->db->buyerData($_SESSION['username']);
			$flowers = $this->db->boughtFlowers($_SESSION['username']);
			require_once("../views/buyer/myGarden.php");
		}
	}

}

$myGardenController = new MyGardenController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$myGardenController->select($op);
}
else 
	$myGardenController->select('First');

?>