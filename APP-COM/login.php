<?php
  error_reporting(E_ALL);
  //error_reporting(0);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante

  define( "MYDEBUG", true);
 // $wert1 = $_POST['login'];
 // $wert2 = $_POST['passwd'];
  //echo $wert1." ".$wert2;
  $select = "MitarbeiterID";
  $from = "login_mitarbeiter";
  $dbconn = dbconnect("HssUser","oss_test");
  //echo $dbconn->host_info;
  $login=$_POST['login'];
  $passwd=$_POST['passwd'];
  //echo $login;
  //$passwd="1f9484788c1555079883dcbc49241c4c4187ea53c2caea014d95623df56f61cd142c11099d91030a7bdcb78f74ab3deb39c1ab1bbbf7ec459c643c35db39205";
  //$login= "Robin";
 
  $wehre="Nickname";
  //echo $passwd;

  $uid=GetUidByLogin($dbconn,$login,$passwd,$select,$from,$wehre);

  //echo "Ihre UserID=".$uid;

    if($uid != "False")
  {
  SperreLogin($dbconn, $uid);
  }

  echo $uid;
 //echo $passwd;
 //echo $login;
 
  ?>