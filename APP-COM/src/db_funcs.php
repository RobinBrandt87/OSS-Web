<?php
  include_once "./src/php_funcs.php";
   

  /*  ***************************************************
  function dbconnect( $user );

	Diese Funktion erstellt eine überprüfte Datenbankverbindung her:
	  DB-Server : localhost
		DB				: pmg_db

	Parameter:
	  $user			: ein erlaubter User für die DB pmg_db

	Rückgabewerte:
	  im ERfolgsfall : ein Objekt der Klasse mysqli
		sonst					 : die() 

	TODO: saubere Umlenkung auf eine eigene Fehlerseite bei Misserfolg

	***************************************************** */
	function dbconnect( $user , $Datenbank)
	{

		// Initialisierung aller lokalen Variablen
		$server		= "localhost";
		
		//$db				= "";
		//$login   	= "";
		$passwort = "123456";
		$dbconn		= false;
		$Datenbank ="";
		
		switch ( $Datenbank )
		{
			case "oss";
				$db = "oss";
				break;
			default:	
				$db = "oss_test";
		}

		switch ( $user )
		{
			case "HssUser";
				$login		= "root";
			  $passwort = "";
				break;
			case "pmg_dummyuser":
				$login		= "pmgdummy";
			  $passwort = "pmgdummy";
				break;
			case "pmg_adminuser":
				$login		= "pmgadmin";
			  $passwort = "pmgdummy";
				break;
			case "root":
			default:
				// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
				die("Never ever !");
				break;
		}

		// Schritt 1 und 2: Verbindung zur DB auf einem DB-Server
		$dbconn =new mysqli($server, $login, $passwort, $db );
		//echo "<div><b>DB-Verbindung:</b>".$dbconn."</div>";

        $dbconn->query("SET NAMES utf-8");
		return $dbconn;
	}
	
	  /*  ***************************************************
  function GetUidByLogin( $dbconn, $login, $passwort );

	Diese Funktion holt aus der DB 'pmg_db' die Pers_ID einer
	Lehrkraft mittels ihres Benutzernamens und Passwortes

	Parameter:
	  $dbconn			: eine gültige DB-Verbindung der Klasse mysqli 
		$login			: Anmeldename 
		$passwort		: Passwort
		$select			: Select SQL
		$from 			: From SQL
		$wehre			: Wehre SQL
		
	Rückgabewerte:
	  im Erfolgsfall : eine Pers_ID
		sonst					 : false 

	***************************************************** */
	function GetUidByLogin( $dbconn, $login, $passwort, $select, $from, $wehre )
	{			// Schritt 3 : SQL-Abfrage zusammenbasteln
  // Schritt 1: SQL-Abfrage
		$SQLstring = "SELECT ".$select."".
			" FROM ".$from."".
			" WHERE ".$wehre."= ? ".
			" AND Passwort =  ? ".  
            " AND Status = 'OK';";

    // Schritt 2: ein neues (leeres) Objekt der Klasse
    //            msqli_stmt erzeugen
    $prepstmt = $dbconn->stmt_init();

    // Schritt 3: Prepared Statement übergeben und ausführen
    if ( !$prepstmt->prepare( $SQLstring ) )
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Wir bedanken uns, und sagen Aufwiedersehen!");
		}

    // Schritt 4: Daten an die Parameter übergeben (binden)
    $prepstmt->bind_param( "ss", $login, $passwort);

    // Schritt 5: Jetzt mit diesen Daten filtern
    $prepstmt->execute();

    // Schritt 6: Ergebnis-Felder an Variablen binden
    $prepstmt->bind_result( $uid );

    // Schritt 7: fetchen
    if ( !$prepstmt->fetch() )
      $uid=false;

		return $uid;
    }
  /*******************************************************
  function GetUserdatenByUID($dbconn, $_GET('uid'));

Diese funktion holt alle userdaten aus der datenbakn pmg.de mittels der uid eines user 

Parameter
$dbconn			eine gültige db verbindung 
$uid			die userid einer lehrkraft

rückgabewerte
im erfolgsfall ein array mit allen userdaten ( Anrede, titel vorname nachname gebdatum)
sonst :fals

	***************************************************** */
	function GetUserdatenByUID($dbconn, $uid)
	{
		$SQLstring = "SELECT Anrede , Titel, Vorname, Nachname, Geb_Datum".
		" From lehrkraft WHERE Pers_ID =".$uid;
		$result = $dbconn->query($SQLstring);
		if(!$result)
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Das war's - CIAO");
		}
		
		// Schritt 5 : DS fetch-en
		$ds = $result->fetch_all(MYSQLI_ASSOC);
		
		// Datensatz bekommen ?
		if (!$ds)
		{
		 	$ds = false;
		}
		return $ds;
	}
     /*  ***************************************************
  function GetRouteByUID( $dbconn ); // , $UID, $RoutenID 

	Diese Funktion holt aus der DB 'hss' die Route die der Mitarbeiter xy ablaufen soll 
	Parameter:

        $UID            : UID des Users     // hatt er automatishcn ach der Anmeldung


		
        
        
	Rückgabewerte:
	  im Erfolgsfall : eien zweidimensionales assoziatives array mit der Reihenfolge der Routendetails (NFC Tags)
	
      NOCH NICHT
      im Erfolgsfall : wird in der Datenbank ein insert in eine Tabelle angelegt mit den Routentags und den dazugehörigen Status 	
        
        sonst					 : false 

	***************************************************** */
	function GetRouteByUID($MitarbeiterID,$Datum) //, $login, $passwort, $select, $from, $wehre
	{	
		$dbconn = dbconnect("HssUser","oss_test");
		$SQLstring = "SELECT  Routenplan.PlanID, ". 
							"Routenplan.RoutenID, ".
							"Startdatum, ".
							"Routenplan.Startzeit ".
						"FROM Routenplan ".
						"WHERE Routenplan.MitarbeiterID = ".$MitarbeiterID." AND Routenplan.Startdatum = STR_TO_DATE('".$Datum."','%Y,%m,%d') ". 
						//"WHERE Routenplan.MitarbeiterID = ".$MitarbeiterID." AND Routenplan.Startdatum = '2016-06-11' ". 
						"AND Routenplan.Status_RP = 'Zu Laufen' ".
						"ORDER BY Routenplan.Startzeit;";
				

		// Schritt 4 : Abfrage abschicken und Ergebnis entgegennehmen
		$result = $dbconn->query($SQLstring);
		if ( $result == FALSE )
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			//die("Das war's - CIAO");
		}

		// Schritt 5 : DS fetch-en
		$dsArr = $result->fetch_all(MYSQLI_ASSOC);
		
		
		$antwort="";
		//foreach ($dsArr as $ds) 
		//{
			$antwort .= "".$dsArr['0']['PlanID']." ".$dsArr['0']['RoutenID']." ".$dsArr['0']['Startzeit'];
			//$antwort .= "".$ds['PlanID']." ".$ds['RoutenID']." ".$ds['Startzeit']." U ";
			//$antwort .= GetRoutendetailsByRoute($ds['RoutenID']);
		//}
		/*
        foreach($result AS $row)
        {
            
            $antwort = $antwort.$row['NFC_ID']." ".$row['Stock']." ".$row['Raum']." ".$row['Position']." ".'@';
            
        }

		// Schritt 6: Datensatz bekommen? 
		if ( $ds )
			// Erolg
			
            ;
		else
			// Nein: Misserfolg ausgeben mit FALSE
			$antwort = false;
		*/
		RouteBearbeiten($dsArr['0']['PlanID']);
		return $antwort;
	}
	/*###################################################################################################
	function GetRoutendetailsByRoute($RoutenID)
		
	Holt die Routendetails für dir Route mit der $RoutenId heraus 
	
	Parameter: 		 $RotenID  		Eine Gültige RoutenID 
	###################################################################################################*/
	function GetRoutendetailsByRoute($RoutenID)
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQLRoutendetails = "SELECT	eingeordnet.NFC_ID, ". 
					"NFC_Tags.Kartenbild, ". 
					"eingeordnet.Rotinenzeit ". 
				"FROM	NFC_Tags INNER JOIN eingeordnet ".
				"USING (NFC_ID) ". 
				"WHERE	eingeordnet.RoutenID = ".$RoutenID." AND NFC_Tags.Status_NFC = 'OK' ". 
				"ORDER BY eingeordnet.Routenindex;";
		$result = $dbconn->query($SQLRoutendetails);
		if(!$result)
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Das war's - CIAO");
		}
		
		// Schritt 5 : DS fetch-en
		$dsArr = $result->fetch_all(MYSQLI_ASSOC);
		$Ergebnis = "";
		//echo $SQLRoutendetails;
		foreach ($dsArr as $ds) 
		{
			
			$Ergebnis .= "".$ds['NFC_ID']." ".$ds['Kartenbild']." ".$ds['Rotinenzeit']."X";
		}
		
		return $Ergebnis;
		
				
	}
    /*###################################################################################################
    function LogIN ($dbconn, $UID)
    
    Diese Funktion ändert den Loginstatus eines Mitarbeiters 
    
    Parameter: 
    $dbconn         eine gültige Datenbankverbindung der Klasse MySQLi
    $UID            ein gültiger UserID 
    
    Rückgabewert:   keine   
    
    
    
    */##################################################################################################
    function LogIN ($dbconn, $UID)
    {
        		$SQLstring2 = "UPDATE `login`".
			" SET `Stbz` = 'Ein'".
			" WHERE Mitarbeiter_ID = ".$uid."";
			$result2 = $dbconn->query($SQLstring2);
    }
    /*###################################################################################################
     function GetMitarbeiterDaten($dbconn) 
     
     Holt alle Daten Aller Mitarbeiter aus der Datenbanktabelle Mitarbeiter 
     
     Parameter:     $dbcon Eine Gültige Datenbankverbindung
     
     Rückgabe:      ein Array mit den Mitarbeiter Daten 
    
    */###################################################################################################
    function GetMitarbeiterDaten($dbconn)
    {
        $SQLstring = "SELECT * FROM `mitarbeiter`";
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds;
    }

    /*#####################################################################################################
    function DarfErDas($dbconn, $uid)
    
    Schaut in der Tabelle login Mitarbeiter ob der User gesperrtt ist 
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $uid Eine Gültige User ID 
                     
    Rückgabewer:     True: Wenn es die $uid gibt und Sie nicht gesperrt ist!
                     False: Wenn es die $uid nicht gibt oder sie gesperrt ist!                 
    
    
    */####################################################################################################
    function DarfErDas($dbconn, $uid)
    {
        $SQLstring = "SELECT `Status` FROM `login_mitarbeiter` WHERE `MitarbeiterID` = ".$uid."";
        $result = $dbconn->query($SQLstring);
        
        $ds = $result->fetch_all(MYSQLI_ASSOC);
  
		return $ds;
      
    }
    /*#####################################################################################################
    function SperreLogin($dbconn, $uid)
    
    Sperrt einen MitarbeiterLogin in der APP
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $uid Eine Gültige User ID 
                     
    Rückgabewer:     Keiner                
    
    
    */####################################################################################################
    function SperreLogin($dbconn, $uid)
    {
        $SQLstring = "UPDATE `login_mitarbeiter` SET `Status`= 'LoggedIn' WHERE `MitarbeiterID`= ".$uid."";
        $dbconn->query($SQLstring);
        if ($dbconn->query($SQLstring) === TRUE) {
           // echo "Record updated successfully";
           // echo $SQLstring;
        } else {
           // echo "Error updating record: " . $conn->error;
           // echo $SQLstring;
        }
  
    }
    /*#####################################################################################################
    function EntSperreLogin($dbconn, $uid)
    
    Sperrt einen MitarbeiterLogin in der APP
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $uid Eine Gültige User ID 
                     
    Rückgabewer:     Keiner                
    
    
    */####################################################################################################
    function EntSperreLogin($dbconn, $uid)
    {
        $SQLstring = "UPDATE `login_mitarbeiter` SET `Status`= 'OK' WHERE `MitarbeiterID`= ".$uid."";
        $dbconn->query($SQLstring);
  
    }
	/*######################################################################
	function NachrichtenInsert($Nachricht,$Betreff,$UserID)
	
	Parameter 	$Nachricht: String mit der Nachrichrt
				$Betreff: String mit dem Betreff
				$USerID: Die ID des Absenders
				
	*/######################################################################
    function NachrichtenInsert($Nachricht,$Betreff,$UserID)
	{
		$dbconn = dbconnect("HssUser","oss_test");
	
		
		$NachrichtenInsert = "	INSERT INTO nachrichten (MitarbeiterID,Inhalt,Betreff)". 
							  " VALUES ( ? , ? , ? )";
		$prepstmt = $dbconn->stmt_init();

		// Schritt 3: Prepared Statement übergeben und ausführen
		if ( !$prepstmt->prepare( $NachrichtenInsert ) )
			{
				echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
				
				// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
				die("Wir bedanken uns, und sagen Aufwiedersehen!");
			}

		// Schritt 4: Daten an die Parameter übergeben (binden)
		$prepstmt->bind_param( "sss", $UserID, $Nachricht, $Betreff);

		// Schritt 5: Jetzt mit diesen Daten filtern
		
		if(!$prepstmt->execute())
		{
				$Return = "False";
		}
		else
		{
				$Return = "OK";
		}
		
		
		return $Return;
	}
	/*#######################################################################################
	function InsertScan($NfcID,$RoutenID,$PlanID)
	
	fügt eine Erfolgreichen Scan in die Datenbank ein 
	
	Parameter 	$NfcID,				Die ID eines NFC Tags 
				$RoutenID,			Die aktuelle RoutenID
				$PlanID 			Die aktuelle Plan ID 
	
	#######################################################################################*/
	function InsertScan($NfcID,$RoutenID,$PlanID)
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQLInsertNFC = "INSERT INTO scant (RoutenID,NFC_ID,PlanID) VALUES (".$RoutenID.",".$NfcID.",".$PlanID.");";
		$dbconn->query($SQLInsertNFC);
	}
	/*#########################################################################################
	function InsertSID
	
	fügt die aktuelle session des Mitarbeiters in die datenbank ein 
	
	Parameter: 	$sid 	:die SessionID des Mitarbeiters der scannt
				$uid 	:die UserID des Mitarbeiters der scannt
	
	
	*/#########################################################################################
	function InsertSID($sid,$uid)
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQLSID = "UPDATE `oss_test`.`login_mitarbeiter` SET `SID` = '".$sid."' WHERE `login_mitarbeiter`.`MitarbeiterID` = ".$uid.";";
		$dbconn->query($SQLSID);
		
		
	}
	/*#########################################################################################
	//function RouteBearbeiten($PlanID)
	
	setzt den Status einer Route auf RouteBearbeiten
	
	parameter $PlanID eine gültige PlanID
	
	##########################################################################################*/
	function RouteBearbeiten($PlanID)
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQL ="UPDATE `oss_test`.`routenplan` SET `Status_RP` = 'In Bearbeitung' WHERE `routenplan`.`PlanID` = ".$PlanID.";";
		$dbconn->query($SQL);
	}
	function UpdatePlanStatus($PlanID)
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQL ="UPDATE `oss_test`.`routenplan` SET `Status_RP` = 'Abgelaufen' WHERE `routenplan`.`PlanID` = ".$PlanID.";";
		$dbconn->query($SQL);
		
	}
	 /*#################################################################################################
    // function GetRoutenPlan()
    
    holt den aktuellen RoutenPlan aus der Datenbank von allen Routen 
    
    Parameter: keine 
    
    Rückgabewert: ein assoziatives Array mit allen Routen 
    
    ###################################################################################################*/
    function GetRoutenPlan()
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $Sql = "SELECT `PlanID`, `MitarbeiterID`, `RoutenID`, `Startdatum`, `Startzeit`, `Status_RP` FROM `routenplan` WHERE 1 ORDER BY `Startdatum` DESC LIMIT 15";
        $result = $dbconn->query($Sql);
        //$ds = $result->fetch_all(MYSQLI_ASSOC);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        return $ds;
    }
	/*##################################################################################################
	Regestriert ausgefallene routen und speichert es ab 
	##################################################################################################*/
	function UpdateRoutenplanFail($RoutenplanID)
   {        
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`routenplan` SET `Status_RP` = 'Ausgefallen' WHERE `routenplan`.`PlanID` = ? ;";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s",$RoutenplanID);
        $prepstmt->execute();  
        
       
   }	
	?>