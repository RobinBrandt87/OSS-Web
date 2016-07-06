<?php
  //error_reporting(E_ALL);
  // $mydebug = true; besser als Konstante => global
	define( "MYDEBUG", True);

  ini_set( "session.use_cookies" , 0);
	ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
	session_name("OSS");
	session_start();

  include_once "./src/php_funcs.php";
  include_once "./src/db_funcs.php";
	include_once "./src/html_funcs.php";



// Beginn des Hauptprogrammes

  // leere Fehlermeldung initialisieren
	$errmsg = "";
  
	if ( isset($_SESSION['err'] ) )
	{
	  $errmsg=PrintErrorDiv( $_SESSION['err']['errno'] );
		unset($_SESSION['err']);
	}

echo index();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <title>OSS - Anmeldung</title>
  <meta http-equiv="content-type" 
        content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="OSS.css" />

</head>
<body>
 <?php 	//DebugArr( $_POST );
   // DebugArr( $_SESSION ); ?>
<div id="header">
  <h1>Objekt-Security-System</h1>
</div>
<div id="center">
  <h2>Um Ihre Daten zu verwalten Müssen Sie sich einloggen</h2>
  <?php echo $errmsg; ?>
  <form  action="<?php echo $_SERVER['PHP_SELF']; ?>"
	      method="post">
    <div>
      <input  type="hidden" 
              name="<?php echo session_name();?>" 
              value="<?php echo session_id();?>" />
    </div>
    <div class="input">
      <label for="login">Benutzername:</label> 
      <input class= "ExtraBreit" type="text" name="login" id="login" />
    </div>
    <div class="input">
      <label for="passwd">Passwort:</label> 
      <input class= "ExtraBreit" type="password" name="passwd" id="passwd" />
    </div>

    <div class="buttonrow">
      <input type="submit" name="submit" value="    Anmelden   " />
      <input class= "ExtraBreit" type="reset" value="       Zurücksetzen        " />
    </div>
  </form>

</div>
<div id="footer">
  <a href="">Infos</a>
  <a href="">Admin</a>
  <a href="">Startseite</a>
</div>
</body>
</html>