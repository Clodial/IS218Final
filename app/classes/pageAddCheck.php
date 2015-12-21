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

		if(isset($_REQUEST['empNo']) && isset($_REQUEST['toDate']) && isset($_REQUEST['fromDate']) && isset($_REQUEST['first']) && isset($_REQUEST['last']) &&isset($_REQUEST['gender']) && isset($_REQUEST['department']) && isset($_REQUEST['title'])){

			$this->empNo 	= $_REQUEST['empNo'];
			$this->toDate 	= $_REQUEST['toDate'];
			$this->fromDate = $_REQUEST['fromDate'];
			$this->dept 	= $_REQUEST['department'];
			$this->gender 	= $_REQUEST['gender'];
			$this->position = $_REQUEST['title'];

			try{

				echo "yo";
				//setting the salary of the new employee
				$stmt = $this->db->prepare('
					select avg(salaries.salary) as average
					from salaries, dept_emp, departments
					where salaries.emp_no = dept_emp.emp_no
						and dept_emp.dept_no = departments.dept_no
						and dept_emp.to_date = "9999-01-01"
						and departments.dept_name = :dept;
					');
				$stmt->bindParam(':dept', $this->dept);
				if($stmt->execute()){
					while($row = $stmt->fetch()){
						$this->salary = number_format((float)$row[0], 2, '.', '');
					}
				}
				

			}catch(PDOException $e){

				echo "SQL Error";
				$this->goBack();

			}

		}else{
			$this->goBack();
		}

	}

}
?>