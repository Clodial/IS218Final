<?php
//namespace app\classes;

//Creates the form that users can add employees to
include("app/classes/pageTemplate.php");
class pageAddEmp extends pageTemplate{

	private $db = NULL;

	public function __construct($db){

		$this->db = $db;

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

		$empNo = 0;

		echo '<h3 class="jumbotron">Add Employee</h3>';
		try{
			$stmt = $this->db->prepare("select emp_no from dept_emp order by emp_no limit 1;");
			if($stmt->execute()){
				while($row = $stmt->fetch()){
					$empNo = $row[0]+1; 
				}
			}
			$this->makeForm($empNo);
		}catch(PDOException $e){

		}

	}

	public function makeForm($num){

		$timeArr = getdate();
		$timeStr = date('Y-m-d');
		echo $timeStr;

		try{

			echo '<form method="get">';
			echo '	<input type="hidden" name="empNo" value-"' . $num . '">';
			echo '	<input type="hidden" name="toDate" value="9999-01-01">';
			echo '  <input type="hidden" name="fromDate" value"' . $timeStr . '">';
			echo ' 	First Name<input type="text" name="first" required><br/>';
			echo '	Last Name<input type="text" name="last" required></br>';
			echo '	Gender<select name="gender">';
			echo '		<option value="M">Male</option>';
			echo '		<option value="F">Female</option>';
			echo '		<option value="N">Opt-out</option>';
			echo '	</select></br>';
			echo ' 	Department<select name="department">';
			$stmt = $this->db->prepare("select dept_name from departments");
			if($stmt->execute()){
				while($row = $stmt->fetch()){
					echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
				}
			}
			echo ' 	</select></br>';
			echo ' 	Position<select name="title">';
			$stmt = $this->db->prepare("select title from titles group by title;");
			if($stmt->execute()){
				while($row = $stmt->fetch()){
					echo '<option value "' . $row[0] . '">' . $row[0] . '</option>';
				}
			}
			echo ' 	</select></br>';
			echo '	<button type="submit" name="page" value="pageAddCheck">';
			echo '		<h4>Add New Employee</h4>';
			echo '	</button>';
			echo '</form>';

			$this->goBack();

		}catch(PDOException $e){
			$this->goBack();
		}

	}

}

?>