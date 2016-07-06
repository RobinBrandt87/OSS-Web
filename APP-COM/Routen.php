<?php
//error_reporting(E_ALL);
error_reporting(0);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante
  //$MitarbeiterID = $_GET['MitarbeiterID'];
  $MitarbeiterID = $_POST['MitarbeiterID'];
  $Datum = $_POST['Startdatum'];
  define( "MYDEBUG", true);
  checktime();
  $Ergebnis = GetRouteByUID($MitarbeiterID,$Datum);
  echo $Ergebnis;
 // echo $MitarbeiterID;
 // echo "   ";
 // echo $Datum;
  ?>