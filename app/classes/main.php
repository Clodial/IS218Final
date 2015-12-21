<?php 

class main{

	/**
	* @brief 	The main controller that starts the app
	*/

	/**
	* @brief 	create the constructor for the web app
	* 		 	-> create the page array for the program
	*			-> figure out what page to load
	*/
	public function __construct($db){

		//include("app/classes/pageTemplate.php");
		$page_request = 'pageIndex';
		
		if(!empty($_REQUEST) && isset($_REQUEST['page'])){

			include('app/classes/'.$_REQUEST['page'].'.php');
			$page_request = $_REQUEST['page'];
			
		}else{
			include('app/classes/pageIndex.php');
		}

		$page = new $page_request($db);

		if($_SERVER['REQUEST_METHOD'] == "GET"){
			$page->get();
		}else{
			$page->post();
		}
		
	}

}
?>