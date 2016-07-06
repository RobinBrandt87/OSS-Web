<?php
  //error_reporting(E_ALL);
  error_reporting(0);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante
    ini_set( "session.use_cookies" , 0);
  ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
	session_name("OSS");
	session_start();
  $Input = "";
  $uid ="";
  $NfcID = $_POST['NfcID'];
  $uid = $_POST['uid'];

  $PlanID = $_POST['PlanID'];
  $RoutenID = $_POST['RoutenID'];
  $sid = SID;
  InsertScan($NfcID,$RoutenID,$PlanID,$uid);
  InsertSID($sid,$uid);
  
  $_SESSION['Live']['Mitarbeiter']=$uid;
  $_SESSION['Live']['SID']= SID;
  $_SESSION['Live']['letzterScan'] = date("d.m.y H:i:s");
  $_SESSION['Live']['letzterTag'] =  $NfcID;
  
  echo SID;

  ?>