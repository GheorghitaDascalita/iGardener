<?php

require_once("../models/Database.php");

class DesiredFlowersController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {
			case 'buy':
				$this->buy();
				break;

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

	private function buy(){
		header("Content-type: text/xml");

		$id=$_GET["id"];

		if($this->db->buy($id)) {
			$data="Bought";

			$this->db->updateStatus();
			
		}
		else $data="Try again later";

		if($_GET['op'] == "buy") {
?>
	<responses>
		<response><?= $data ?></response>
	</responses>
<?php

	}

	}

	private function view($redirect = false, $location = "DesiredFlowers"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else{
			session_start();
			$data = $this->db->desiredFlowers($_SESSION['username']);
			require_once("../views/buyer/desiredFlowers.php");
		}
	}

	private function logout(){
		session_start();
		session_destroy();
		$this->view(true,'Login');
	}

}


$desiredFlowersController = new DesiredFlowersController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$desiredFlowersController->select($op);
}
else 
	$desiredFlowersController->select('First');

?>