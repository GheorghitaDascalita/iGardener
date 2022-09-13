<?php

require_once("../models/Database.php");

class CropsReportController{

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

	private function view($redirect = false, $location = "CropsReport"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else{
			session_start();
			$growing = $this->db->growing($_SESSION['username']);
			$harvested = $this->db->harvested($_SESSION['username']);
			$sold = $this->db->sold($_SESSION['username']);
			require_once("../views/seller/cropsReport.php");
		}
	}

}

$cropsReportController = new CropsReportController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$cropsReportController->select($op);
}
else 
	$cropsReportController->select('First');

?>