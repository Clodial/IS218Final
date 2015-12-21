<?php

abstract class pageTemplate{

	//Already has a link to the database through $db
	// $db -> the database connection (has to check if its null or not, however);

	public function get(){}

	public function post(){}

	public function createBody($type){}

	public function createHeader(){}

	public function createFooter(){}

	//public function makeForm($type){}

	public function goBack(){

		echo '<form class"hoverButton" method="post">';
		echo '	<button type="submit" name="page" value="pageIndex">';
		echo '		<h4>Go to Menu</h4>';
		echo '	</button>';
		echo '</form></br>';
	
	}

}

?>