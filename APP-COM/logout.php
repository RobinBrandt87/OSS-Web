<?php
error_reporting(0);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante

  define( "MYDEBUG", true);
 // $wert1 = $_POST['login'];
 // $wert2 = $_POST['passwd'];
  //echo $wert1." ".$wert2;
 
  $dbconn = dbconnect("HssUser","oss_test");
  //echo $dbconn->host_info;
  $uid = $_POST['uid'];
  //echo $passwd;
  EntSperreLogin($dbconn, $uid);
  echo "Ihre UserID=".$uid;

  ?>