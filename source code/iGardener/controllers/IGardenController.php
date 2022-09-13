<?php

require_once("../models/Database.php");

class IGardenController{

	private $db;

	public function __construct(){
		$this->db = new Database();
	}

	public function select($op){
		switch ($op) {
			case 'want':
				$this->want();
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

	private function want(){
		header("Content-type: text/xml");

		$idCrop=$_GET["id"];
		$quantity=$_GET["quantity"];
		session_start();

		if($idOrder = $this->db->wantFlowers($_SESSION['username'], $idCrop, $quantity)) {

			$image = $this->db->updateThisStatus($idOrder, $idCrop, $quantity);

		}
		else $image="unavailable";

		if($_GET['op'] == "want") {
?>
	<response>
		<image><?= $image ?></image>
	</response>
<?php

	}

	}


	private function view($redirect = false, $location = "iGarden"){
		if($redirect){
			header("Location:" . $location . "Controller.php");
		}
		else{
			$data = $this->db->igarden();
			require_once("../views/buyer/iGarden.php");
		}
	}

	private function logout(){
		session_start();
		session_destroy();
		$this->view(true,'Login');
	}

}


$iGardenController = new IGardenController();

if(isset($_REQUEST['op'])){
	$op = $_REQUEST['op'];
	$iGardenController->select($op);
}
else 
	$iGardenController->select('First');

?>