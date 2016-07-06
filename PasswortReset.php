<?php
  error_reporting(E_ALL);
  // $mydebug = true; besser als Konstante => global
	define( "MYDEBUG", true);

  ini_set( "session.use_cookies" , 0);
	ini_set( "session.use_only_cookies" , 0); // Für unsere Mac User only 4 you 
	session_name("HSS");
	session_start();

  include_once "./src/php_funcs.php";
  include_once "./src/db_funcs.php";
	include_once "./src/html_funcs.php";
	
	DebugArr( $_POST );
  DebugArr( $_SESSION );
  {
  // spricht der User eine gültige PasswdRestore SID an ? 
	// hat die session eine user ID
	if ( !isset( $_SESSION['user']['id'])
	{
    // Fehler merken
		$_SESSION['err']['errno']=12;

		// SESSION-ID aufheben
		$ziel="./index.php?";

		// umlenken
		header("Location: ".$ziel);
		/* alternativ:
		header("Location: ./index.php?".SID);
		*/
	}
  $date = new DateTime();
  if($date >= $_SESSION['user']['time'])
  {
    session_destroy();
    $ziel="./index.php";
  
		// umlenken
		header("Location: ".$ziel);
  }
}

// Beginn des Hauptprogrammes

  // leere Fehlermeldung initialisieren
	$errmsg = "";

	if ( isset($_SESSION['err'] ) )
	{
	  $errmsg=PrintErrorDiv( $_SESSION['err']['errno'] );
		unset($_SESSION['err']);
	}
  if(isset($_POST['submit']))
  {
    if($_POST['passwd1']==$_POST['passwd2'])
    {
      UpdatePasswdByUserID($_SESSION['user']['id'],$_POST['passwd1']);
    }
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <title>OSS - PasswortReset</title>
  <meta http-equiv="content-type" 
        content="text/html; charset=utf-8" />
  <link rel="stylesheet" type="text/css" href="OSS.css" />

</head>
<body>
<div id="header">
  <h1>Objekt-Securety-System</h1>
</div>
<div id="center">
  <h2>Passwort Resetten</h2>
  <p>Sie haben ihr Passwort vergessen und können es jetzt ändern.</p>
  
  <p>Nachdem Sie ihr Passwort geändert haben muss ihr Accaunt durch einen Chef erneut Aktieviert werden</p>
  <?php echo $errmsg; ?>
  <form  action="<?php echo $_SERVER['PHP_SELF']."?".SID; ?>"
	      method="post">
    <div>
      <input  type="hidden" 
              name="<?php echo session_name();?>" 
              value="<?php echo session_id();?>" />
    </div>
    <div class="input">
      <label for="login">Passwort:</label> 
      <input type="password" name="passwd1" id="passwd1" />
    </div>
    <div class="input">
      <label for="passwd">Bestätigen:</label> 
      <input type="password" name="passwd2" id="passwd2" />
    </div>

    <div class="buttonrow">
      <input type="submit" name="submit" value="  Passwort Ändern  " />
      <input type="reset" value="  Zurücksetzen  " />
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