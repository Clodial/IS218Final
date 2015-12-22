<?php

include("app/classes/pageTemplate.php");
class pageDeptList extends pageTemplate{

	private $dept;
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

		$stmt = NULL;

		echo '<h3 class="jumbotron">Department Employee List</h3>';
		if(isset($_REQUEST['department'])){

			$this->dept = $_REQUEST['department'];
			try{
				$this->deptButton($this->dept);
				$this->goBack();
				$stmt = $this->dbPage->prepare('
					select employees.first_name, employees.last_name, dept_emp.from_date, dept_emp.to_date, salaries.salary
					from employees, dept_emp, departments, salaries
					where employees.emp_no = dept_emp.emp_no
						and dept_emp.emp_no = salaries.emp_no
						and dept_emp.dept_no = departments.dept_no
						and salaries.to_date = "9999-01-01"	
						and departments.dept_name = :dept
					order by employees.emp_no desc;
					');
				$stmt->bindParam(':dept',$this->dept);
				if($stmt->execute()){
					while($dName = $stmt->fetch()){
						echo '<div>';
						echo ''. $dName[0] . ' ' . $dName[1] . ' From: ' . $dName[2] . ' To: ' . $dName[3] . ' Salary: ' . $dName[4];
						echo '</div></br>';
					}
				}
				$this->deptButton($this->dept);
				$this->goBack();
			}catch(PDOException $e){
				echo $e->getMessage();
				echo "yolo";
			}

		}else{
			$this->errorDisplay();
		}

	}

	public function errorDisplay(){
		$this->goBack();
	}

	public function deptButton($deptName){

		echo '<form class"hoverButton" method="post">';
		echo '	<input type="hidden" value="' . $deptName . '" name="department">';
		echo '	<button type="submit" name="page" value="pageDepart">';
		echo '		<div>' . $deptName . '</div>';
		echo '	</button>';
		echo '</form></br>';

	}

}

?>