<?php

require_once("../models/Database.php");

class LoginController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {
			case 'Log in':
				$this->login();
				break;
			
			case 'First':
				$this->view();
				break;

			default:
				$this->view(true);
				break;
		}
	}

	private function login(){
		if(isset($_POST['userType']) && isset($_POST['username']) && isset($_POST['password'])){
			$userType = $_POST['userType'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			if($this->db->login($userType, $username, $password)){
				session_start();
				$_SESSION["username"] = $username;
				if($userType === "seller")
					$this->view(true, 'GrowingCrops');
				else
					$this->view(true, 'IGarden');
			}
			else $this->view(true);
		}
		else $this->view(true);
	}

	private function view($redirect = false, $location = "Login"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else require_once("../views/login/logIn.php");
	}

}


$loginController = new LoginController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$loginController->select($op);
}
else 
	$loginController->select('First');

?>