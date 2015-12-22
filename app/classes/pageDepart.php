<?php

include("app/classes/pageTemplate.php");
class pageDepart extends pageTemplate{

	private $department;
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

		echo '<h3 class="jumbotron">Department Summary</h3>';
		if(isset($_REQUEST['department'])){
			$this->department = $_REQUEST['department'];

			echo '<h3>' . $this->department . '</h3>';
			try{
				echo '<h3>Department Manager: </h3>';
				$stmt = $this->dbPage->prepare('
					select employees.first_name as firstName, employees.last_name as lastName
					from employees, titles, departments, dept_emp
					where employees.emp_no = titles.emp_no
						and titles.emp_no = dept_emp.emp_no
						and titles.title = "Manager"
						and titles.to_date = "9999-01-01"
						and dept_emp.dept_no = departments.dept_no
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept',$this->department);
				if($stmt->execute()){
					while($dName = $stmt->fetch()){
						echo '' . $dName[0] . ' ' . $dName[1] . '</br>';
					}
				}
				echo '<h3>Total Salary: </h3>';
				$stmt = $this->dbPage->prepare('
					select sum(salaries.salary) as total
					from salaries, dept_emp, departments
					where salaries.emp_no = dept_emp.emp_no
						and dept_emp.dept_no = departments.dept_no
						and salaries.to_date = "9999-01-01"
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept',$this->department);
				if($stmt->execute()){
					while($dName = $stmt->fetch()){
						echo '$' . $dName[0] . '</br>';
					}
				}
				echo '<h3>Number of Employees: </h3>';
				$stmt = $this->dbPage->prepare('
					select count(dept_emp.dept_no) as employees
					from dept_emp, departments
					where dept_emp.dept_no = departments.dept_no
						and dept_emp.to_date = "9999-01-01"
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept',$this->department);
				if($stmt->execute()){
					while($dName = $stmt->fetch()){
						echo '' . $dName[0] . '</br>';
					}
				}
				echo '<h3>Salary Average: </h3>';
				$stmt = $this->dbPage->prepare('
					select avg(salaries.salary) as average
					from salaries, dept_emp, departments
					where salaries.emp_no = dept_emp.emp_no
						and dept_emp.dept_no = departments.dept_no
						and salaries.to_date = "9999-01-01"
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept',$this->department);
				if($stmt->execute()){
					while($dName = $stmt->fetch()){
						echo '$' . number_format((float)$dName[0], 2, '.', '') . '</br></br>';
					}
				}
				$this->deptButton($this->department);
				$this->goBack();
			}
			catch (PDOException $e){

				echo $e->getMessage();

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
		echo '	<button type="submit" name="page" value="pageDeptList">';
		echo '		<h4>See Full Employee List</h4>';
		echo '	</button>';
		echo '</form>';

	}

	public function goBack(){
		echo '<form class"hoverButton" method="post">';
		echo '	<button type="submit" name="page" value="pageIndex">';
		echo '		<h4>Go Back to Menu</h4>';
		echo '	</button>';
		echo '</form>';
	}

}

?>