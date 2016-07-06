<?php
//error_reporting(E_ALL);
error_reporting(0);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante

  $Ergebnis ="";
  $RoutenID = $_POST['RoutenID'];
  define( "MYDEBUG", true);
  $Ergebnis = GetRoutendetailsByRoute($RoutenID);
  echo $Ergebnis
?>