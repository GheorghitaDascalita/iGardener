<?php

require_once("../models/Database.php");

class SignupController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {

			case 'Sign up':
				$this->signup();
				break;
			
			case 'First':
				$this->view();
				break;

			default:
				$this->view(true);
				break;
		}
	}

	private function signup(){
		if(isset($_POST['userType']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
			$userType = $_POST['userType'];
			$username = $_POST['username'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			if($this->db->signup($userType, $username, $email, $password))
				$this->view(true, "Login");
			else $this->view(true);
		}
		else $this->view(true);
	}

	private function view($redirect = false, $location = "Signup"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else require_once("../views/signup/signUp.php");
	}

}


$signupController = new SignupController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$signupController->select($op);
}
else 
	$signupController->select('First');

?>