<?php
//use app\classes as App;
include("inc/dbCred.php");
include("app/classes/main.php");

$db = NULL;

try{

	$db = new PDO("mysql:host=$host;dbname=$dbname",$user,$pass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$main = new main($db);

}catch( PDOException $e){

	echo $e->getMessage();

}

?>