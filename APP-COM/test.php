<?php
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante

  define( "MYDEBUG", true);
  $select = "Mitarbeiter_ID";
  $from = "login";
  $dbconn = dbconnect("HssUser","hss");
  //echo $dbconn->host_info;
  $login=$_GET['login'];
  //echo $login;
  $passwd=$_GET['passwd'];
  $wehre=" Nickname";
  //echo $passwd;
  $uid=GetUidByLogin($dbconn,$login,$passwd,$select,$from,$wehre);
  //echo "Ihre UserID=".$uid;
  if ($uid=="")
  {
      $uid="False";
  }
  echo $uid;
  ?>