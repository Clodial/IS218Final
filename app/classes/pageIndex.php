<?php
//namespace app\classes;

include("app/classes/pageTemplate.php");
class pageIndex extends pageTemplate{

	private $dbPage = NULL;

	public function __construct($db){

		$this->dbPage = $db;

	}

	public function get(){
		$this->createHeader();
		$this->makeBody();
		$this->createFooter();
	}
	
	public function post(){
		$this->createHeader();
		$this->makeBody();
		$this->createFooter();
	}

	public function makeBody(){
		echo '<h3 class="jumbotron">Employee Database</h3>';
		try{

			$stmt = $this->dbPage->prepare("select dept_name from departments");
			if($stmt->execute()){
				while($dName = $stmt->fetch()){
					$this->deptButton($dName[0]);
				}
			}

			$this->createEmp();

		}catch( PDOException $e){

			echo $e->getMessage();

		}


	}

	public function deptButton($deptName){

		echo '<form class"hoverButton" method="post">';
		echo '	<input type="hidden" value="' . $deptName . '" name="department">';
		echo '	<button type="submit" name="page" value="pageDepart">';
		echo '		<div>' . $deptName . '</div>';
		echo '	</button>';
		echo '</form>';

	}

	public function createEmp(){

		echo '<form class"hoverButton" method="post">';
		echo '	<button type="submit" name="page" value="pageAddEmp">';
		echo '		<h4>Add Employee</h4>';
		echo '	</button>';
		echo '</form>';

	}

}


?>