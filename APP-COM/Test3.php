<?php
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante

  define( "MYDEBUG", FALSE);
  $dbconn = dbconnect("HssUser","hss");

 $Route = GetRouteByUID($dbconn);
  //echo "Ihre UserID=".$uid;
    if ($Route=="")
  {
      $Route="False";
  }
 // DebugArr($uid);

  echo $Route;
?>