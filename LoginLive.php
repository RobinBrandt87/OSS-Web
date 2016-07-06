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



// Beginn des Hauptprogrammes

  // leere Fehlermeldung initialisieren
	$errmsg = "";
  if ( isset($_POST['submit']) )
	{
    // Alle Felder ausgefüllt?
		if ( PflichtfelderOK() )
		{
		  // Datenbankzugriff
			$dbconn = dbconnect("HssUser","oss_test");
			$uid = GetUidByLogin( $dbconn, $_POST['login'], $_POST['passwd'],"ChefID","login_chef","Nickname" );

			// Anmeldung korrekt?
			if ( !($uid === false) ) // funktioniert jetzt auch bei der uid 0 !
			{ // uid in der SESSION aufheben
                //echo $uid;

				//$cid = GetKIDIdByUID($uid);
				//$_SESSION['login']['kid'] = GetKundenIdByChefID($uid);
				//$Kid = $kid['0']['KundenID'];
				//$_SESSION['Objekte'] = GetObjekteByKid( $cid );
				//$ziel="./intern.php?".session_name()."=".session_id();
        $_SESSION['login']['uid']= $uid;
        $_SESSION['login']['IP']=$_SERVER['REMOTE_ADDR'];
				$ziel="./LiveAnsicht.php?".SID;
				// Umlenkung nach intern.php mit einer Session
				header("Location: $ziel");
			}
			else
			{ // Fehlermeldung: Falsche Anmeldedaten
				$errmsg = PrintErrorDiv( 10 );
			}
		}
	  else
		{ // Fehlermeldung: Fehlende Anmeldedaten
			$errmsg = PrintErrorDiv( 11 );
		}
	}





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
 <?php// 	DebugArr( $_POST );
    //DebugArr( $_SESSION ); ?>
<div id="header">
  <h1>Objekt-Securety-System</h1>
</div>
<div id="center">
  <h2>Um in die LiveAnsicht zu gelangen müssen Sie sich einloggen</h2>
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
      <input  class= "ExtraBreit" type="password" name="passwd" id="passwd" />
    </div>

    <div class="buttonrow">
      <input type="submit" name="submit" value="    Anmelden   " />
      <input  class= "ExtraBreit" type="reset" value="       Zurücksetzen        " />
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