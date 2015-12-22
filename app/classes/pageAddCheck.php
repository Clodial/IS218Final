<?php

include("app/classes/pageTemplate.php");
class pageAddCheck extends pageTemplate{

	private $db = NULL;
	private $empNo;
	private $toDate;
	private $fromDate;
	private $salary;
	private $dept;
	private $deptNo;
	private $gender;
	private $position;
	private $birth;
	private $fName;
	private $lName;

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

		if(isset($_REQUEST['empNo']) && isset($_REQUEST['toDate']) && isset($_REQUEST['fromDate']) && isset($_REQUEST['first']) && isset($_REQUEST['last']) &&isset($_REQUEST['gender']) && isset($_REQUEST['department']) && isset($_REQUEST['title']) && isset($_REQUEST['year']) && isset($_REQUEST['month']) && isset($_REQUEST['day'])){

			$this->empNo 	= $_REQUEST['empNo'];
			$this->toDate 	= $_REQUEST['toDate'];
			$this->fromDate = $_REQUEST['fromDate'];
			$this->dept 	= $_REQUEST['department'];
			$this->gender 	= $_REQUEST['gender'];
			$this->position = $_REQUEST['title'];
			$this->birth 	= $_REQUEST['year'] . '-' . $_REQUEST['month'] . '-' . $_REQUEST['day'];
			$this->fName 	= $_REQUEST['first'];
			$this->lName 	= $_REQUEST['last'];

			try{

				echo '<h3 class="jumbotron">Employee Verification</h3>';
				//setting the salary of the new employee
				$stmt = $this->db->prepare('
					select avg(salaries.salary) as average
					from salaries, dept_emp, departments
					where salaries.emp_no = dept_emp.emp_no
						and dept_emp.dept_no = departments.dept_no
						and salaries.to_date = "9999-01-01"
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept', $this->dept);
				if($stmt->execute()){
					while($row = $stmt->fetch()){
						$this->salary = number_format((float)$row[0], 2, '.', '');
					}
				}
				$stmt = $this->db->prepare('
					select dept_no from departments where dept_name = :dept;
					');
				$stmt->bindParam(':dept', $this->dept);
				if($stmt->execute()){
					while($row = $stmt->fetch()){
						$this->deptNo = $row[0];
					}
				}
				
				try{

					//We're gonna start inserting the data via transaction
					$this->db->beginTransaction();

					//adding to employees
					$stmt = $this->db->prepare('insert into employees values(
							:eNo,
							:bDay,
							:fNm,
							:lNm,
							:gen,
							:hDay);');
					$stmt->bindParam(':eNo', $this->empNo);
					$stmt->bindParam(':bDay', $this->birth);
					$stmt->bindParam(':fNm', $this->fName);
					$stmt->bindParam(':lNm', $this->lName);
					$stmt->bindParam(':gen', $this->gender);
					$stmt->bindParam(':hDay', $this->fromDate);
					$stmt->execute();

					//adding to dept_emp
					$stmt = $this->db->prepare('insert into dept_emp values(
							:eNo,
							:dNo,
							:fDay,
							:tDay);');
					$stmt->bindParam(':eNo', $this->empNo);
					$stmt->bindParam(':dNo', $this->deptNo);
					$stmt->bindParam(':fDay', $this->fromDate);
					$stmt->bindParam(':tDay', $this->toDate);
					$stmt->execute();

					//adding to salaries
					$stmt = $this->db->prepare('insert into salaries values(
							:eNo,
							:sal,
							:fDay,
							:tDay);');
					$stmt->bindParam(':eNo', $this->empNo);
					$stmt->bindParam(':sal', $this->salary);
					$stmt->bindParam(':fDay', $this->fromDate);
					$stmt->bindParam(':tDay', $this->toDate);
					$stmt->execute();

					//adding to titles
					$stmt = $this->db->prepare('insert into titles values(
							:eNo,
							:title,
							:fDay,
							:tDay);');
					$stmt->bindParam(':eNo', $this->empNo);
					$stmt->bindParam(':title', $this->position);
					$stmt->bindParam(':fDay', $this->fromDate);
					$stmt->bindParam(':tDay', $this->toDate);
					$stmt->execute();

					//checking if this is a manager
					if($this->position = "Manager"){
						$stmt = $this->db->prepare('insert into dept_manager values(
							:dNo,
							:eNo,
							:fDate,
							:eDate);');
						$stmt->bindParam(':dNo',$this->deptNo);
						$stmt->bindParam(':eNo',$this->empNo);
						$stmt->bindParam(':fDate',$this->fromDate);
						$stmt->bindParam(':eDate',$this->toDate);
						$stmt->execute();
					}

					$this->db->commit();

					echo "Addition Successful";
					$this->deptButton($this->dept);

				}catch(PDOException $e){

					$this->db->rollBack();
					echo $e->getMessage();

				}

				$this->goBack();

			}catch(PDOException $e){

				echo "SQL Error";
				$this->goBack();

			}

		}else{
			$this->goBack();
		}

	}

	public function deptButton($deptName){

		echo '<form class"hoverButton" method="post">';
		echo '	<input type="hidden" value="' . $deptName . '" name="department">';
		echo '	<button type="submit" name="page" value="pageDeptList">';
		echo '		<h4>See Full Employee List With New Addition</h4>';
		echo '	</button>';
		echo '</form>';

	}
}
?>