<?php
  error_reporting(E_ALL);
  //error_reporting(0);
  // $mydebug = true; besser als Konstante => global
	define( "MYDEBUG", true);

  ini_set( "session.use_cookies" , 0);
  ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
	session_name("OSS");
	session_start();
  include_once "./src/php_funcs.php";
  include_once "./src/db_funcs.php";
  include_once "./src/html_funcs.php";
  IPcheck();
  Check_LoggedIn();
  DebugArr($_SESSION);
   //DebugArr(GetRoutenInBearbeitung($_SESSION['login']['kid']));
    $Content = "";

   // $dbconn = dbconnect("HssUser","oss_test");
   // Beginn des Hauptprogrammes

    MenuMerker();  //php function    
    $Content .= InternMitarbeiter();   // php function
    $Content .= RoutenVerwalten();
    $Content .= Nachrichten(); // php function
    $Content .= NfcTagsVerwalten();
    $Content .= Protokoll(); // php programm function    //DebugArr( Nachrichten() );
    $Content .= Objekte();
    $Content .= Routen();
    $Content .= MeineDaten();
    $Content .= RoutenDetails();
    $Content .= RutenPlanen();
    $Content .= LiveAnsicht();
    
    Logout();  // php programm function
    Save();    // php programm function 
    Sperren(); // php programm function 
    insert();  // php programm function
    insertNFC(); // php programm function
    RoutrnPlan(); 




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html>
<head>
  <title>OSS - Intern</title>
  <meta http-equiv="content-type" 
        content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="OSS.css" />
      <link rel="stylesheet" type="text/css" href="Menu.css" />
</head>
<body>
  <?php 	//DebugArr( $_POST );
    //DebugArr( $_SESSION ); ?>
<div id="header">
  <h1>Objekt-Security-System</h1>
</div>
<form  action="<?php echo $_SERVER['PHP_SELF'].'?'.SID; ?>"
	      method="post">
<div>
  
<?php echo HtmlHauptMenu();  ?>     
</div> 
</form>
<?php echo  HtmlSubMenu() ?>      
<div id="center">
<?php echo $Content;  ?>

</div>
<div id="footer">
  <a href="">Infos</a>
  <a href="">Admin</a>
  <a href="">Startseite</a>
</div>
</body>
</html>