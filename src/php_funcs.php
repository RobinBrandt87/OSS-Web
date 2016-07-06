<?php
  include_once "./src/php_funcs.php";
  include_once "./src/html_funcs.php";


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
/*	Entfehnrt alle Tags die im Eingabestring Vorhanden sind. Eingaben die über diese 
    Funktion überprüft werden müssen mit einen Prepaird statmant hinzugefügt werden
													
													
	Übergabe Parameter:	$Eingabe der zu Prüfende Wert 
    
    Return:             $Eingabe der geprüfte Wert oder ein lehrstring  
*/##################################################################################
function Eingebecheck($Eingabe)
{
    //$RegulaererAusdruck = "/[A-Za-z0-9]{1,25}/";
   $Ausgabe = strip_tags($Eingabe); // doch lieber strip_tags 
 
 
	Return $Ausgabe;
}
###################################################################################
/*	Modul zum Ausloggen
													
													
	Übergabe Parameter:	Keiner 
    
    Return:            String mit PHP Code, zerstört die Session und leitet auf index.php um 
*/##################################################################################
function Logout()
{
	 if(isset($_POST['Logout']))  
    {       
            unset($_SESSION['login']);
            session_destroy();
            $ziel="./index.php";
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel"); 
    }
}
###################################################################################
/*	Modul zum Speichern von Mitarbeiter Änderungen bzw änderung Von OBjekten
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function Save()
{
	 if(isset($_POST['Save'])&& $_SESSION['intern'] == "Mitarbeiter")
    {
        $Vorname = Eingebecheck($_POST['Vorname']);
        $Nachname = Eingebecheck($_POST['Nachname']);
        $Email = Eingebecheck($_POST['Dienst_E_Mail']);
        $Adresse = Eingebecheck($_POST['Str_HN']);
        $Ort = Eingebecheck($_POST['Ort']);
        $PLZ = Eingebecheck($_POST['PLZ']);
        $Geb = Eingebecheck($_POST['Geb_Datum']);
        $Status = Eingebecheck($_POST['Status']);
        $dbconn = dbconnect("HssUser","oss_test");
        $ID = $_POST['ID'];

        SaveMitarbeiter($Vorname, $Nachname, $Email, $Adresse, $Ort, $PLZ , $Status , $ID);

        } 
        else 
        {
            //echo "Error updating record: " . $conn->error;
        }
        

        if(isset($_POST['Save'])&& $_SESSION['intern'] == "Objekte Verwalten")
        {
            $ID = Eingebecheck($_POST['ID']);
            $ObjekName = Eingebecheck($_POST['ObjektName']);
            $Tel = Eingebecheck($_POST['Tel']);
            $Adresse = Eingebecheck($_POST['Str_HN']);
            $Ort = Eingebecheck($_POST['Ort']);
            $PLZ = Eingebecheck($_POST['PLZ']);
            $Status = Eingebecheck($_POST['Status']);
            
            UpdateObjektByObjektID($ID,$ObjekName,$Tel,$Adresse,$Ort,$PLZ,$Status);
            $ziel="./intern.php?".SID;
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel");        
        }    
}
###################################################################################
/*	Modul zum Sperren von MitarbeiterLogins in der App 
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function Sperren()
{
	if(isset($_POST['Sperren']))   
    {
         $dbconn = dbconnect("HssUser","oss_test");
         SperreLogin($dbconn,$_POST['Sperren'] );
            //$ziel="./intern.php?".session_name()."=".session_id();
		    $ziel="./intern.php?".SID;
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel");        

    }
        if(isset($_POST['Aktivieren']))   
    {
         $dbconn = dbconnect("HssUser","oss_test");
         EntSperreLogin($dbconn,$_POST['Aktivieren'] );
            //$ziel="./intern.php?".session_name()."=".session_id();
		    $ziel="./intern.php?".SID;
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel");
    }
}
###################################################################################
/*	Modul zum Hinzufügen von Mitarbeitern in die Datenbank mit Login 
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function insert()
{
	 if(isset($_POST['Insert']))
    {
     $Vorname = Eingebecheck($_POST['Vorname2']);
        $Nachname = Eingebecheck($_POST['Nachname2']);
        $Email = Eingebecheck($_POST['Dienst_E_Mail2']);
        $Adresse = Eingebecheck($_POST['Str_HN2']);
        $Ort = Eingebecheck($_POST['Ort2']);
        $PLZ = Eingebecheck($_POST['PLZ2']);
        $Geb = Eingebecheck($_POST['Geb_Datum2']);
        $Status = Eingebecheck($_POST['Status2']);
        $Passwort = $_POST['Passwort2'];
        $ET = $_POST['ET2'];
        $kid = $_SESSION['login']['kid'];
        $Kid = $kid;
        $login = $_POST['userlogin'];
        $dbconn = dbconnect("HssUser","oss_test");
        
       $sql1 ="INSERT INTO `mitarbeiter`(". 
                                        "`KundenID`, `Vname`, `Nname`, ". 
                                        "`Dienst_E_Mail`, ". 
                                        "`Str_HN`, `Ort`, ". 
                                        "`PLZ`, `Geb_Datum`, ". 
                                        "`Einstell_ZS`, `Status_MA`) ". 
              "VALUES (?,?,?,?,?,?,?,?,?,?)"; 

             $prepstmt1 = $dbconn->stmt_init();
             $prepstmt1->prepare($sql1); 
             $prepstmt1->bind_param("isssssssss",$kid, $Vorname, $Nachname, $Email, $Adresse, $Ort, $PLZ, $Geb, $ET, $Status);
             $prepstmt1->execute();
             
             $sql3="SELECT LAST_INSERT_ID()";
             $result = $dbconn->query($sql3);
             $ds = $result->fetch_all(MYSQLI_ASSOC);
             // DebugArr($ds);
             $id = $ds['0']['LAST_INSERT_ID()'];
        $sql2 = "INSERT INTO `login_mitarbeiter`
             (`MitarbeiterID`, `Nickname`, `Passwort`, `Login_ZS`, `Status`)". 
             "VALUES ( ? ,? , SHA2( ? , 512 ) , ? , ? )"; 
             $prepstmt2 = $dbconn->stmt_init();
             $prepstmt2->prepare($sql2); 
             $Status = "Gesperrt";
             $prepstmt2->bind_param("issss",$id , $login, $Passwort, $ET , $Status);
             $prepstmt2->execute();
           // echo "Record updated successfully";
            //echo $prepstmt1;
            //$ziel="./intern.php?".session_name()."=".session_id();
		    $ziel="./intern.php?".SID;
			// Umlenkung nach inter.php mit einer Session
		    header("Location: $ziel"); 
   
        
    }
}
###################################################################################
/*	Modul was die MenuAuswahl Mitarbeiter bearbeitet
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function InternMitarbeiter()
{	$dbconn = dbconnect("HssUser","oss_test");
	if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Mitarbeiter")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $kid = $_SESSION['login']['kid'];
        $Mitarbeiter = GetMitarbeiterDaten($dbconn, $kid);
        if (isset ($_POST['Bearbeiten'])) {
           $option = $_POST['Bearbeiten'];
        }
        else {
           $option = "";
        }
       
        $Content = Print_MitarbeiterTable($Mitarbeiter , $option );
		return $Content;
    }
	
}
###################################################################################
/*	Modul was die MenuAuswahl NfcTags bearbeitet
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function NfcTagsVerwalten()
{
	    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="NFC Tags Verwalten")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $dbconn = dbconnect("HssUser","oss_test");
		if (isset($_POST['OBJID'])) {
			$ObjID = $_POST['OBJID'];
			$_SESSION['OBJID'] = $ObjID;
		}
		else
		{
			$ObjID = $_SESSION['OBJID'];
		} 
		if (isset($_POST['Speichern'])) {
			
    		$Raumname = $_POST['Raumname'];
    		$Raumnummer =  $_POST['Raumnummer'];
   			$Position = $_POST['Position'];
   			$Status =  $_POST['Status'];
    		$ID = $_POST['Speichern'];
           
			 UpdateNfcTagByID($Raumname,$Raumnummer,$Position, $Status,$ID);
		}
        $Filter = $_POST['Filter'];
        $NFC_Arr = NFCVerwalten($dbconn , $ObjID, $Filter );
        $Content = Print_NFCTable($NFC_Arr);
		return  $Content;
    }
	
}	
###################################################################################
/*	Modul welches die MenuMerker bearbeitet
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function MenuMerker()
{
	    if(isset($_POST['Menu0']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Mitarbeiter";
    } 
	        if(isset($_POST['Menu1']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Nachrichten";
    }
    	    if(isset($_POST['Menu2']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Routen";
    }  
        	    if(isset($_POST['Menu3']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Protokolle";
    }  
            	    if(isset($_POST['Menu4']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Meine Daten Verwalten";
    }  
            	    if(isset($_POST['Menu5']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "Objekte Verwalten";
    }  
          
        if(isset($_POST['Menu6']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "NFC Tags Verwalten";
    } 
            if(isset($_POST['Menu7']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
                        $ziel="./LoginLive.php?".SID;
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel"); 
    } 
            if(isset($_POST['Menu8']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "RoutenDetails";
    } 
            if(isset($_POST['Menu9']) )
    {//Wenn du eine Anfrage bekommst lösche den MenuMerker in der Session und setzte deinen 
         unset( $_SESSION['intern']);
         $_SESSION['intern'] = "RoutenPlan";
    } 
    
    
}
###################################################################################
/* baut ein OptionsMenu in NfcTagBearbeiten auf 
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function ForEachObjekt ()
{ 
	$SubMenu = "<select name=\"OBJID\" > ";
	$dbconn = dbconnect("HssUser","oss_test");
    $kid = GetKIDIdByUID($_SESSION['login']['uid']);
    $ObjArr = GetObjekteByKid( $kid );
	foreach ($ObjArr as $ObjektID) {
	$Name = GetObjektNameByID($dbconn, $ObjektID['ObjektID']);	
    $SubMenu .= "<option value =\"".$ObjektID['ObjektID']."\">".$Name['0']['Name']."</option>";
   
	}
	$SubMenu .= "</select>";
    $SubMenu .="<select name=\"Filter\" >";
    $SubMenu .="<option name=\"Status\" value =\"Alle\">Alle</option>";
	$SubMenu .="<option name=\"Status\" value =\"OK\">OK</option>";
	$SubMenu .="<option name=\"Status\" value =\"Gesperrt\">Gesperrt</option>";
	$SubMenu .="<option name=\"Status\" value =\"Defekt\">Defekt</option>";
		
	$SubMenu .="</select>";
	return $SubMenu;
}
/*###################################################################################
ForEachRouteInObjekt()

Holt alle Routen für objekt xyz 

Parameter: keine            
*/###################################################################################
function ForEachRouteInObjekt()
{ 
	$SubMenu = "<select name=\"OBJID\" > ";
	$dbconn = dbconnect("HssUser","oss_test");
    $kid = GetKIDIdByUID($_SESSION['login']['uid']);
    $ObjArr = GetObjekteByKid( $kid );
	foreach ($ObjArr as $ObjektID) {
	$Name = GetObjektNameByID($dbconn, $ObjektID['ObjektID']);	
    $SubMenu .= "<option value =\"".$ObjektID['ObjektID']."\">".$Name['0']['Name']."</option>"; 
 
   
	}
	$SubMenu .= "</select>";
	return $SubMenu;
}



###################################################################################
/* stellt den Nachrichten Content zur verfügung 
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function Nachrichten()
{	$Content ="";
	if (isset($_SESSION['intern'])&&$_SESSION['intern']== "Nachrichten")
    {// Wenn Dein Merker Im Menu ist Baue dich auf
		if(isset($_POST['Löschen']))
        {
            $IDLöschen = $_POST['Löschen'];
            if (is_numeric ($IDLöschen)) 
            {
             NachrichtenLöschen($IDLöschen);  
                
            }
        }
       if(isset($_POST['Gelesen']))
        {
            $IDLesen = $_POST['Gelesen'];
            if (is_numeric ($IDLesen)) 
            {
             NachrichtGelesen($IDLesen);  
                
            }
        }
        if(!isset($_SESSION['Nachrichtinhalt']))
		{
        $kid = $_SESSION['login']['kid'];      
        $NArr = GetNachrichtenByKID($kid);
       // DebugArr($NArr);
		$Content = NachrichtenTabelle($NArr);
		//$_SESSION['Nachrichtinhalt'] = $Content;
		
		return $Content;
		}
		else
		return $_SESSION['Nachrichtinhalt'];
	}
}
###################################################################################
/*  IPcheck() überprüft die in der Session gespeicherte IP Adresse mit der ankommenden 
          bei nicht übereinstimmung wird die Session zerstört und es geschieht eine umlenkung nach index.php 
          
													
													
	Übergabe Parameter:	Keiner 
    
           
*/##################################################################################
function IPcheck()
{
    if(isset($_SESSION['login']['IP']))
    {
        if($_SERVER['REMOTE_ADDR'] != $_SESSION['login']['IP']) 
        {
            session_destroy();
            $ziel="./index.php";
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel"); 
        }
    }
}
###################################################################################### 
/* funkcion index() der Ausgelagerte Teil der Index.php Anmeldung 
######################################################################################*/
function index()
{
    	// nicht zum 1. Mal hier?
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
				$_SESSION['login']['uid']= $uid;
				$_SESSION['login']['time']=date("d.m.y H:i:s");
				$_SESSION['login']['IP']=$_SERVER['REMOTE_ADDR'];
				$_SESSION['login']['kid'] = GetKIDIdByUID( $uid);
				//$cid = GetKIDIdByUID($uid);
				//$_SESSION['login']['kid'] = GetKundenIdByChefID($uid);
				//$Kid = $kid['0']['KundenID'];
				//$_SESSION['Objekte'] = GetObjekteByKid( $cid );
				//$ziel="./intern.php?".session_name()."=".session_id();
				$ziel="./intern.php?".SID;
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
}

/*####################################################################################
function RoutenVerwalten() Bindet den code zur Routenverwaltung in das hauptprogramm

Parameter: keine
*/####################################################################################
function RoutenVerwalten()
{
	    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Routen")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $dbconn = dbconnect("HssUser","oss_test");
		if (isset($_POST['OBJID'])) {
			$ObjID = $_POST['OBJID'];
			$_SESSION['OBJID'] = $ObjID;
		}
		else
		{
			$ObjID = $_SESSION['OBJID'];
		} 
		if (isset($_POST['Speichern'])) {
			

		}

        $Content = Print_RoutenTable();
		return  $Content;
    }
	
}
/*######################################################################################
function insertNFC() 

    fügt den Programcode für das insert eines NFC_tags in die Datenbank ein 

*/#####################################################################################
function insertNFC()
{
    if(isset($_POST['Insert_NFC']))
    {
        $ObjektNameNeu = Eingebecheck($_POST['ObjektNameNeu']);
        $StockNeu = Eingebecheck($_POST['Stock_Neu']);
        $RaumNeu = Eingebecheck($_POST['RaumNameNeu']);        
        $RaumNrNeu = Eingebecheck($_POST['RaumNrNeu']);
        $PositionNeu = Eingebecheck($_POST['PositionNeu']);   
          
        Insert_NFC_DB($ObjektNameNeu,$StockNeu,$RaumNeu,$RaumNrNeu,$PositionNeu);   
        
        $ziel="./intern.php?".SID;
		// Umlenkung nach intern.php mit einer Session
		header("Location: $ziel");    
            
    }
}
/*#####################################################################################
// function RoutrnPlan()

fügt den Programmcode für das insert eines Mitarbeiters in in die Datenbank ein 
#######################################################################################*/
function RoutrnPlan()
{
     if($_SESSION['intern'] == "RoutenPlan" && $_POST['Save'] == "Save" )   
     {
        // DebugArr($_SESSION);
        // DebugArr($_POST);
         $Name = $_POST['Route'];
         $RoutenID = GetRoutenIDbyName($Name);
         //DebugArr($RoutenID);
         $Nname = $_POST['Mitarbeiter'];       
         $MitarbeiterID = GetMitarbeiterIDbyName($Nname);
         //DebugArr($MitarbeiterID);
         	$TimeNow = time();
			//echo $TimeNow;
			//$Time = date('Y-m-d h:m:i',strtotime("2016-06-14 12:23:00"));
			$dateTime = new DateTime($_POST['Datum']." ".$_POST['Uhrzeit']); 
			$Time = $dateTime->format('U');
			//echo "   ";
			//echo $TimeNow;
			//$Time = date('Y-m-d h:i:m',strtotime($Prüfvariable));
		if($TimeNow < $Time )	
        {
            ;
         $Startdatum = $_POST['Datum'];
         $Startzeit = $_POST['Uhrzeit'];
         $uniqe = $MitarbeiterID['0']['MitarbeiterID'].$RoutenID['0']['RoutenID'].$Startdatum.$Startzeit;
         InsertIntoRoutenPlan($MitarbeiterID['0']['MitarbeiterID'],$RoutenID['0']['RoutenID'],$Startdatum,$Startzeit,$uniqe);  
         
        }

         $ziel="./intern.php?".SID;
		// Umlenkung nach intern.php mit einer Session
		header("Location: $ziel");    
         //echo "geschafft";
     }
    
}
/*#######################################################################################
function Protokoll()

    fügt den Programmcode für die Auswahl Protokoll hinzu 
    
*/#######################################################################################
function Protokoll()
{
 if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Protokolle")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $Content = ProtokollTabelle();
		return  $Content;
    }
    
} 
/*#######################################################################################
function Objekte()

    fügt den Programmcode für die Auswahl Objekte hinzu 
    
*/#######################################################################################
function Objekte()
{	$dbconn = dbconnect("HssUser","oss_test");
	if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Objekte Verwalten")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $kid = $_SESSION['login']['kid'];
        $Objekte = GetAllObjekteByKid( $kid);
        //$_SESSION['Objekte'] =  GetObjekteByKid( $kid);
        if (isset ($_POST['Bearbeiten'])) {
           $option = $_POST['Bearbeiten'];
        }
        else {
           $option = "";
        }
       
        $Content = Print_Objekte_Table($Objekte , $option );
		return $Content;	
    }
    
}  
/*########################################################################################
function MeineDaten()

fügt den Programmcode für die Auswahl MeineDaten hinzu 

########################################################################################*/ 
function MeineDaten()
{
    
        if ($_SESSION['intern']=="Meine Daten Verwalten" && isset($_POST['Save']))
    {
    $Name = Eingebecheck($_POST['Nachname']);
    $Email = Eingebecheck($_POST['Email']);
    $Ort = Eingebecheck($_POST['Ort']);
    $Straße = Eingebecheck($_POST['Straße']);
    $PLZ = Eingebecheck($_POST['PLZ']);
    $cid = $_SESSION['login']['uid'];
    SpeicherMeineDaten($Name,$Email,$Straße,$PLZ,$Ort, $cid);
    
    }
    $dbconn = dbconnect("HssUser","oss_test");
	if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Meine Daten Verwalten")
    {// Wenn Dein Merker Im Menu ist Baue dich auf 
        $kid = $_SESSION['login']['kid'];
        
        //$_SESSION['Objekte'] =  GetObjekteByKid( $kid);
        if (isset ($_POST['Bearbeiten'])) {
           $option = $_POST['Bearbeiten'];
        }
        else {
           $option = "";
        }
        $MeineDaten = GetMeineDaten();
        $Content = PrintMeineDaten($MeineDaten,$option);
		return $Content;	
    }

    

}
/*#######################################################################################
//function Routen()

lifert die Logik für Routen also Sperren Aktivieren und Speichern(Erstellen)

#######################################################################################*/
function Routen()
{
    $dbconn = dbconnect("HssUser","oss_test");
    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="Routen")
    {
        if(isset($_POST['RouteSperren']))
        {
            $RoutenID = $_POST['RouteSperren'];
            RoutenSperren($RoutenID);   
            $ziel="./intern.php?".SID;
		    // Umlenkung nach intern.php mit einer Session
		    header("Location: $ziel");   
        }
                if(isset($_POST['RouteAktivieren']))
        {
            $RoutenID = $_POST['RouteAktivieren'];
            RoutenAktivieren($RoutenID);   
            $ziel="./intern.php?".SID;
		    // Umlenkung nach intern.php mit einer Session
		    header("Location: $ziel");   
        }
                 if(isset($_POST['AddRoute']))
        {
            $Name = $_POST['Name'];
            $Beschreibung = $_POST['Beschreibung'];
            $ObjID = $_SESSION['OBJID'];
            $kid = $_SESSION['login']['kid'];
            InsertRouten($Name, $Beschreibung, $ObjID, $kid);
               
            $ziel="./intern.php?".SID;
		    // Umlenkung nach intern.php mit einer Session
		    header("Location: $ziel");   
        }
        
    }

}
/*##########################################################################
// function RotenDetails()

lifert die Logik für RoutenDetails also Bearbeiten 

##########################################################################*/
function RoutenDetails()
{
     $Content .="";
     $dbconn = dbconnect("HssUser","oss_test");
    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="RoutenDetails")
    {
        if(isset($_POST['RoutenDetailsBearbeiten']))
        {
            $RoutenID = $_POST['RoutenDetailsBearbeiten'];
            $RoutenDetails = GetRoutenDetails($RoutenID);
            //DebugArr($RoutenID);
            $Content .= PrintRoutenDetails($RoutenDetails,$RoutenID);
           
        }
    }
    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="RoutenDetails" && isset($_POST['Speichern']))
    {
        $RoutenID = $_POST['RoutenID'];
        DeletFromEingeordnet($RoutenID);
       
        for($i=0; $i < 51; $i++ )
        {
            if($_POST['Status'.$i] != "")
            {
            $index = $_POST['Status'.$i];
            $NFCID = $_POST['NFC'.$i];
            $RoutenID = $_POST['RoutenID'];
            $zeit =  $_POST['Minuten'.$i];
            
            InsertIntoEingeordnet($NFCID,$RoutenID,$index,$zeit);  
     
            }
        
        }
                    						$ziel="./intern.php?".SID;
						// Umlenkung nach intern.php mit einer Session
						header("Location: $ziel"); 
    }
    return $Content;
}
/*###########################################################################################
// function RutenPlanen()

Dieses Modul liefert die Logik für Routen Planen 

#############################################################################################*/
function RutenPlanen()
{
         $Content .="";
     $dbconn = dbconnect("HssUser","oss_test");
    if (isset($_SESSION['intern'])&&$_SESSION['intern']=="RoutenPlan")
    {
        $RoutenPlan = GetRoutenPlan();
        //DebugArr($RoutenPlan);
        $Content .= PrintRoutenPlan($RoutenPlan);
        
    }    
    return $Content;
}

?>