<?php



/*###########################################################################
  // Debugging-Funktion für Arrays inherhalb von PHP
  debugging fuktion für arrays innerhalb von PHP Gibt als präformatierten Text ein Array
  in einem div der klasse debug aus 
  Ausgabe erfolgt nur bei einer gesestzten Globalen Konstante namens MYDEBUG
  
  Parameter $arr - ein Array
   
############################################################################*/  
	function DebugArr( $arr )
	{
		if ( defined('MYDEBUG') && MYDEBUG )
		{
			/* Testausgabe: was bekommen wir
				 Ausgabe aller GET-Daten           */
			echo "\n<div class=\"debug\"><pre>";
			print_r( $arr );
			echo "</pre></div>";
		}
		
	}
	// End of Function
//##############################################################################
/*###########################################################################
Überprüft bei der Anmeldung ob die Felder 'login' und 'passwd' ausgefüllt wurden

Parameter: 			:Keien	aber benötigt get als übergabemethode 

Rückgabewert:

true: 	alles ausgefüllt
false:	alles andere 
   
############################################################################*/  
	function PflichtfelderOK( )
	{
    if ( !empty($_POST['login']) &&  // && ist das logische UND (oder: AND)
			   !empty($_POST['passwd'])   )
		{
			return true;
		}
		else
		{
      return false;
		}
	}
    /*###########################################################################
Überprüft ob  $_SESSION['login']['uid'] vorhanden ist
Parameter: 			:Keien	aber benötigt get als übergabemethode 

Rückgabewert:   keiner 
                fals nicht vorhanden umleitung zum index


   
############################################################################*/  
    function Check_LoggedIn()
{
  // ist der User angemeldet? Wenn ja -> dann existiert in der
	// SESSION die uid
	if ( !isset( $_SESSION['login']['uid'] ))
	{
    // Fehler merken
		$_SESSION['err']['errno']=12;

		// SESSION-ID aufheben
		$ziel="./index.php?".SID;

		// umlenken
		header("Location: ".$ziel);
		/* alternativ:
		header("Location: ./index.php?".SID);
		*/
	}

}
###################################################################################
/*	Überprüft ob eine Gültige Anmeldung vorliegt	Wenn ja dann passiert Nichts
													Wenn Nein Umleitung zur Anmeldung
													Ein Fehlervariable errno wird auf 9 gesetzt
													
													
	Übergabe Parameter:	Session.ID fals vorhanden 
*/##################################################################################
function KennIchDich($_arr)
{
	$_uid=$_arr['Login']['uid'];
	if($_uid == "")
	{
		        $_ziel="./index.php?".SID;
        		// Umlenkung nach index.php mit eienr session
				ini_set("session.use_cookies",0);
  				session_name("pmg");
  				session_start();
				$_SESSION['Error']['errno'] = 9;
				$_POST[SID];
				header("Location: ".$_ziel);
				echo "Umlenkung nach welcome.php fehlgeschlagen!";
				
	}
	
}
###################################################################################
/*	Überprüft ob eine Gültige Eingabe vorliegt	Wenn ja gibt sie die Eingabe zurück
                                                Wenn nein einen lehren String
													
													
	Übergabe Parameter:	$Eingabe der zu Prüfende Wert 
    
    Return:             $Eingabe der geprüfte Wert oder ein lehrstring  
*/##################################################################################
function Eingebecheck($Eingabe)
{
    $RegulaererAusdruck = "/[A-Za-z0-9]{1,25}/";
  

    $Bool = (preg_match( $RegulaererAusdruck, $Eingabe)); 
    
    if(!$Bool){
        $Eingabe = "";
    }
	Return $Eingabe;
}
/*#####################################################################################
//function checktime()
######################################################################################*/
function checktime()
{
	$RoutenplanArr = GetRoutenPlan();
	$TimeNow = time();
	//echo $TimeNow;
	foreach($RoutenplanArr as $Routenplan)
	{
	$Prüfvariable = $Routenplan['Startdatum']." ".$Routenplan['Startzeit'];
	//$Time = date('Y-m-d h:m:i',strtotime("2016-06-14 12:23:00"));
	$dateTime = new DateTime($Routenplan['Startdatum']." ".$Routenplan['Startzeit']); 
	$Time = $dateTime->format('U');

	//$Time = date('Y-m-d h:i:m',strtotime($Prüfvariable));
	
	if($TimeNow > $Time && $Routenplan['Status_RP'] == "Zu Laufen")
	{
			UpdateRoutenplanFail($Routenplan['PlanID']);	
			
			
	}	
	}
	
}	
?>