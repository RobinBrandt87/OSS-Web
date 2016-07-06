<?php
  //error_reporting(E_ALL);
  error_reporting(0);
  // $mydebug = true; besser als Konstante => global
	define( "MYDEBUG", True);

  ini_set( "session.use_cookies" , 0);
	ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
	session_name("OSS");
	session_start();
    
  include_once "./src/php_funcs.php";
  include_once "./src/db_funcs.php";
  include_once "./src/html_funcs.php";

if(!isset($_SESSION['login']['IP']))
{
 $_SESSION['login']['IP'] = $_SERVER['REMOTE_ADDR'];
}
else {
  if($_SESSION['login']['IP'] != $_SERVER['REMOTE_ADDR'])
  {
    $ziel="./LoginLive.php?";
        
		// umlenken
		header("Location: ".$ziel);
    
  }
  
}

if(!isset($_SESSION['login']) && !isset($_SESSION['Live']))
{
     		$ziel="./LoginLive.php?";
        
		// umlenken
		header("Location: ".$ziel);
    
}

$MyArr = GetLoggedInID();

$Content = "";

if (!isset($_SESSION['Live']))
{
    $Content .= "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">";
    $Content .= "<select name=\"Mitarbeiter\" >";
    foreach ($MyArr as $My)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $userdaten = GetUserdatenByUID($My['MitarbeiterID']);
        $Content .= "<option>".$userdaten['0']['Vname']." ".$userdaten['0']['Nname']."</option>";
        
    }
    $Content .= "</select><input  type=\"submit\" name=\"UserID\"value=\"".$My['MitarbeiterID']."\" /></form>";
}
if(isset($_POST['UserID']))
{
   $sid = GetSIDbyUID($_POST['UserID']);
   		$ziel="./LiveAnsicht.php?".$sid['0']['SID'];
       // DebugArr($sid);
		// umlenken
		header("Location: ".$ziel);
}
if(isset($_SESSION['Live']))
{
    $Content .= "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">";
    $Content .= "<select name=\"Mitarbeiter\" >";
    foreach ($MyArr as $My)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $userdaten = GetUserdatenByUID($My['MitarbeiterID']);
        $Content .= "<option>".$userdaten['0']['Vname']." ".$userdaten['0']['Nname']."</option>";
        
    }
    $Content .= "</select><input  type=\"submit\" name=\"UserID\"value=\"".$My['MitarbeiterID']."\" /></form>";
    $userdaten = GetUserdatenByUID($_SESSION['Live']['Mitarbeiter']);
    $html .= GetLiveAnsicht($userdaten);
}

// Beginn des Hauptprogrammes$htm

  // leere Fehlermeldung initialisieren



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <title>OSS - LiveAnsicht</title>
  <meta http-equiv="content-type" 
        content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="OSS.css" />

</head>
<body>
 <?php 	
  //  DebugArr( $MyArr );
  //  DebugArr( $_SESSION );
  //  DebugArr( $_POST);
     ?>
<div id="header">
  <h1>Objekt-Securety-System</h1>
</div>
<div id="center">
<?php echo $Content;
      echo $html;
?>

</div>
<div id="footer">
  <a href="">Infos</a>
  <a href="">Admin</a>
  <a href="">Startseite</a>
</div>
</body>
</html>