<?php
  error_reporting(E_ALL);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante
	ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
 	session_name("OSS");
	session_start();
    if($_SESSION['APP']['IP'] == $_SERVER['REMOTE_ADDR'])
  {}
  $PlanID = $_POST['PlanID'];
  
  UpdatePlanStatus($PlanID);
  
  

?>