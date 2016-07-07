<?php
  include_once "./src/php_funcs.php";
 
  include_once "./src/html_funcs.php";
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
		
		if ( $dbconn->connect_errno )
		{// Verbindung fehlgeschlagen!
			if ( defined(MYDEBUG) && MYDEBUG )
			{
				echo "<div><b>DB-Verbindung fehlgeschlagen:</b>".
						 $dbconn->connect_error."</div>";
			}

			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Das war's - CIAO");
		}
		else
		{ // geschwätzige Debugging-Ausgabe
			if ( defined(MYDEBUG) && MYDEBUG )
			{
				echo "<div><b>DB-Verbindung hergestellt:</b>".
						 $dbconn->host_info ."</div>";
			}
		}
        $dbconn->query("SET NAMES UTF8");
        $dbconn->set_charset("utf-8");
        
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
			" AND passwort = SHA2( ? , 512 )".  
            " AND Status = 'OK'";

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

     /*  ***************************************************
  function GetRouteByUID( $dbconn ); // , $UID, $RoutenID 

	Diese Funktion holt aus der DB 'hss' die Route die der Mitarbeiter xy ablaufen soll 
	Parameter:
	  $dbconn			: eine gültige DB-Verbindung der Klasse mysqli 
        $UID            : UID des Users     // hatt er automatishcn ach der Anmeldung
		$RoutenID       : Die ID der Route die Abgefragt wird 

		
        
        
	Rückgabewerte:
	  im Erfolgsfall : eien zweidimensionales assoziatives array mit der Reihenfolge der Routendetails (NFC Tags)
	
      NOCH NICHT
      im Erfolgsfall : wird in der Datenbank ein insert in eine Tabelle angelegt mit den Routentags und den dazugehörigen Status 	
        
        sonst					 : false 

	***************************************************** */
	function GetRouteByUID( $dbconn ) //, $login, $passwort, $select, $from, $wehre
	{	

		$SQLstring = "SELECT `NFC_ID`,`Stock`,`Raum`,`Position` FROM `nfc_trags` WHERE 1 ";
				//" AND passwd = MD5('".$passwort."')";

		// Schritt 4 : Abfrage abschicken und Ergebnis entgegennehmen
		$result = $dbconn->query($SQLstring);
		if ( $result == FALSE )
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Das war's - CIAO");
		}

		// Schritt 5 : DS fetch-en
		$ds = $result->fetch_assoc();
        $antwort="";
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
		
		return $antwort;
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
     function GetMitarbeiterDaten($dbconn, $kid) 
     
     Holt alle Daten Aller Mitarbeiter aus der Datenbanktabelle Mitarbeiter 
     
     Parameter:     $dbcon Eine Gültige Datenbankverbindung
                    $kid Die KundenID 
     Rückgabe:      ein Array mit den Mitarbeiter Daten für diesen Kunden 
    
    */###################################################################################################
    function GetMitarbeiterDaten($dbconn, $kid)
    {
        $SQLstring = "SELECT * FROM `mitarbeiter` WHERE KundenID = ".$kid."";
       // $dbconn->query("SET NAMES latin1");
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
        $SQLstring = "UPDATE `login_mitarbeiter` SET `Status`= 'Gesperrt' WHERE `MitarbeiterID`= ".$uid."";
        $dbconn->query($SQLstring);
        if ($dbconn->query($SQLstring) === TRUE) {
           // echo "Record updated successfully";
           // echo $SQLstring;
        } else {
            //echo "Error updating record: " . $conn->error;
            //echo $SQLstring;
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
        /*#####################################################################################################
    function InsertMitarbeiter($dbconn, )
    
    fügt einen MitarbeiterLogin für den Loginbereich der APP in der Datenbank
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $uid Eine Gültige User ID 
                     
    Rückgabewer:     Keiner                
    
    
    */####################################################################################################
    function InsertMitarbeiter($dbconn, $Nickname )
    {
        $SQLstring1 = "INSERT INTO `mitarbeiter`". 
                     "(`Vname`, `Nname`, `Dienst_E_Mail`, `Str_HN`, `Ort`, `PLZ`, `Geb_Datum`, `Einstell_ZS`, `Status_MA`)". 
                     " VALUES ([value-1],[value-2],[value-3],[value-4],[value-5],[value-6],[value-7],[value-8],[value-9],[value-10])";
        $dbconn->query($SQLstring1);               
        $SQLstring2 = "INSERT INTO `login_mitarbeiter`". 
                     "(`Nickname`, `Login_ZS`, `Status`)". 
                     " VALUES ($Nickname,NOW(),'Gesperrt'])";
        $dbconn->query($SQLstring2);
  
    }
	        /*#####################################################################################################
    function GetObjektNameByID($dbconn, $ObjejtID)
    
  	Gibt für eine ObjektID den Namen zurück
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $ObjektID Eine Gültige User ID 
                     
    Rückgabewer:     Der Objektname in einem zweidimensionalen assoziativen Array               
    
    
    */####################################################################################################
    function GetObjektNameByID($dbconn, $ObjektID)
    {
        $SQLstring = "SELECT `Name` FROM `objekt` WHERE `ObjektID` = ".$ObjektID."";
        $result = $dbconn->query($SQLstring);
      // if($result != FALSE)
         $ds = $result->fetch_all(MYSQLI_ASSOC);    

        return $ds;
    }
	/*#####################################################################################################
    function NFCVerwalten($dbconn, $ObjektID)
    
   Holt alle NFC Tags Eines Objektes mit der ID $ObjektID
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $ObjektID eine Gültige ObjektID
                     $Special ein optionaler Filter
                     
    Rückgabewer:     ein Zweidiemensionales array mit allen nfc tags von dem Objekt                
    
    
    */####################################################################################################
    function NFCVerwalten($dbconn, $ObjektID, $Special)
    {
        $SQLstring = "SELECT * FROM `NFC_tags`WHERE `ObjektID` = \"".$ObjektID."\"";
        If(isset($Special) && $Special != "Alle")
        {
            $SQLstring .=" AND `Status_NFC` = \"".$Special."\"";
        }
		//echo $SQLstring;
        $result = $dbconn->query($SQLstring);
        
        
        $ds = $result->fetch_all(MYSQLI_ASSOC);    
    
        return $ds;
    }
	/*#####################################################################################################
    function GetKIDIdByUID($dbconn,$chefid )
    
    holt eine KundenId by ChefID aus der DB
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $uid eine gültige uid
                     Exception $e
    Rückgabewert:    $ds eine assoziatives array mit der KID             
    
    
    */####################################################################################################
    function GetKIDIdByUID($uId)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQLstring = "SELECT `KundenID` FROM `chef`WHERE `ChefID` = ".$uId."";
		//echo $SQLstring;
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds['0']['KundenID'];
    }
    /*#####################################################################################################
    function GetObjekteByKid($dbconn,$kid )
    
    holt eine assoziatives array aus der db mit einer liste aller Objekte für diesen Kunden
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $kid eine gültige ChefIdS
                     
    Rückgabewer:     eine assoziatives array mit den Obekt ID             
    
    
    */####################################################################################################
    function GetObjekteByKid( $kid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $dbconn->query("SET NAMES UTF8");
        $SQLstring = "SELECT `ObjektID` FROM `objekt`WHERE `KundenID` = ".$kid." AND Status_OJ = \"OK\"";
		//echo $SQLstring;
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds;
    }
        function GetAllObjekteByKid( $kid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $dbconn->query("SET NAMES UTF8");
        $SQLstring = "SELECT `ObjektID` FROM `objekt`WHERE `KundenID` = ".$kid."";
		//echo $SQLstring;
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds;
    }
    /*#####################################################################################################
    function GetKundenIdByChefID($cid )
    
    holt eine assoziatives array aus der db mit einer liste aller Objekte für diesen Kunden
    
    Parameter:       $dbconn Eine Gültige Datenbankverbindung
                     $cid eine gültige ChefIdS
                     
    Rückgabewer:     Ein assoziatives array mit der KundenID               
    
    
    */####################################################################################################
    function GetKundenIdByChefID($cid)
    {
   	    $dbconn = dbconnect("HssUser","oss_test");
        $SQLstring = "SELECT `KundenID` FROM `chef`WHERE `ChefID` = ".$cid."";
		//echo $SQLstring;
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds['0']['KundenID'];
    }
   /*#####################################################################################################
    function UpdateNfcTagByID($Raumname,$Raumnummer,$Position, $Status,$ID)
    
    updatet ein NFC Tag in der Datenbank
    
    Parameter:           		$Raumname = Raumname als string 
    		                    $Raumnummer = Raumnummer als string 
   			                    $Position = Position im Raum als String 
   			                    $Status =  Der Status OK Gesperrt oder Defekt
    		                    $ID = die ID als String 
    Rückgabewer:                 
    
    
    */####################################################################################################
    function UpdateNfcTagByID($Raumname,$Raumnummer,$Position, $Status,$ID)
    {
            $dbconn = dbconnect("HssUser","oss_test");
            $sql = "UPDATE `oss_test`.`nfc_tags`".
              " SET `Raumname` = ?,". 
                 " `RaumNr` = ?,". 
                 " `Position` = ?,". 
                 " `Status_NFC` = ?". 
                 " WHERE `nfc_tags`.`NFC_ID` = ? ";   
             $prepstmt = $dbconn->stmt_init();
             $prepstmt->prepare($sql); 
             $prepstmt->bind_param("sssss", $Raumname,$Raumnummer,$Position, $Status,$ID);
             $prepstmt->execute();    
            // echo $sql;
    }
     /*#####################################################################################################
    function GetNachrichtenByKID($Kid)
    
    holt eine assoziatives array aus der db mit einer liste aller Nachrichten für diesen Kunden
    
    Parameter:       
                     $Kid eine gültige KundenID
                     
    Rückgabewer:     Ein assoziatives array mit der KundenID               
    
    
    */####################################################################################################
    function GetNachrichtenByKID($Kid)
    {
         $dbconn = dbconnect("HssUser","oss_test");
         $dbconn->query("SET NAMES UTF8");
         $sql="SELECT	Mitarbeiter.KundenID, Nachrichten.*
            FROM	Mitarbeiter INNER JOIN Nachrichten
			USING (MitarbeiterID)
            WHERE	Mitarbeiter.KundenID = ".$Kid."
            ORDER BY `Nachrichten`.`Nach_ZS` DESC";
                   
            $result = $dbconn->query($sql);
            $ds = $result->fetch_all(MYSQLI_ASSOC);
            return $ds;
    }
         /*#####################################################################################################
    function GetProtokolleByKid( $MitarbeiterID, $Limit)
    
    holt eine assoziatives array aus der db mit einer liste aller Protokolle für diesen Kunden
    
    Parameter:       
                     
                     $MitarbeiterID = Für welchen Mitarbeiter soll das PRotokoll geholt werden
                     $Limit = wieviel Einträge sollen geholt werden 
                                          
    Rückgabewer:     Ein assoziatives array mit der KundenID               
    
    
    */####################################################################################################
    function GetProtokolle($MitarbeiterID,$Limit)
    {   
        //DebugArr($MitarbeiterID);
        $dbconn = dbconnect("HssUser","oss_test");
        /*
        $Sql = "SELECT `NFC_ID`, `Scan_ZS` FROM `scant` WHERE `uid` = ? LIMIT ? ";
        */
        $Sql = "SELECT `Scant`.`ScanID`,
                `NFC_Tags`.`ObjektID`,
                `scant`.`NFC_ID`,
                `NFC_Tags`.`Raumname`,
                `NFC_Tags`.`RaumNr`,
                `NFC_Tags`.`Position`,
                `Routenplan`.`RoutenID`,
                DATE_FORMAT(`scant`.`Scan_ZS`,'%e.%m.%Y %H:%i:%s') AS Scan_ZS
                FROM `Routenplan` INNER JOIN `scant`
                USING (`PlanID`)
                INNER JOIN `eingeordnet`
                USING (`NFC_ID`)
                INNER JOIN `NFC_Tags`
                USING (`NFC_ID`)
                WHERE `Routenplan`.`MitarbeiterID` = ?
                GROUP BY `Scant`.`ScanID`
                ORDER BY `Scant`.`Scan_ZS` DESC
                LIMIT ?";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($Sql);
        $prepstmt->bind_param("ss",$MitarbeiterID['0']['MitarbeiterID'],$Limit);
        $prepstmt->execute();
        $prepstmt->bind_result( $Protokolle );
        $Protokolle = $prepstmt->get_result();
          
        // Schritt 7: fetchen
        //echo DebugArr($Protokolle);
        return  $Protokolle;
        
      

    }
    
   /*#######################################################################################################
   function   Insert_NFC_DB($ObjektNameNeu,$StockNeu,$RaumNeu,$RaumNrNeu,$PositionNeu,$Status_NFC_Neu)
              fügt ein NFC Tag in die Dateabank ein 
              
              SET @Objekt = 100000;
              INSERT INTO NFC_Tags (ObjektID,Stock,Raumname,RaumNr,Position) VALUES
              (@Objekt,'3.OG','Flur',0001,'Eingangstür');
   
   Parameter    $ObjektNameNeu          : Der Objektname
                $StockNeu               : Das Stockwerk
                $RaumNeu                : Der Raum
                $RaumNrNeu              : Raumnummer
                $PositionNeu            : Genaue Position im Raum
                $Status_NFC_Neu         : Der Status default Gesperrt
   */#######################################################################################################
    function   Insert_NFC_DB($ObjektNameNeu,$StockNeu,$RaumNeu,$RaumNrNeu,$PositionNeu)
    {
            $dbconn = dbconnect("HssUser","oss_test");
            $sql = " INSERT INTO NFC_Tags (ObjektID,Stock,Raumname,RaumNr,Position) VALUES(?,?,?,?,?)";   
             $prepstmt = $dbconn->stmt_init();
             $prepstmt->prepare($sql); 
             $prepstmt->bind_param("sssss",$ObjektNameNeu,$StockNeu,$RaumNeu,$RaumNrNeu,$PositionNeu);
             $prepstmt->execute();    
            // echo $sql;
        
    }
    /*######################################################################################################
    function GetObjektDetailsByObjektID($ObjektID)
    
    holt alle Objektdetails für das Objekt mit der ID $ObjektID['ObjektID'] aus der Datenbank
    
    Parameter $ObjektID Eine Gültige ObjektId
    
    Bsp.
    SELECT * FROM `objekt` WHERE `ObjektID` = 100000 
    
    #######################################################################################################*/
    function GetObjektDetailsByObjektID($ObjektID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $Sql = "SELECT * FROM `objekt` WHERE `ObjektID` = \"".$ObjektID."\";";
        $result = $dbconn->query($Sql);
        //$ds = $result->fetch_all(MYSQLI_ASSOC);
        $ds = $result->fetch_assoc();
        return $ds;
    }
    /*##########################################################################################################
    function UpdateObjektByObjektID($ObjektID,$Name,$Tel,$Adresse,$Ort,$Plz,$Status)
    
    Die übergabeparameter sollte bevor sie in diese Funktion gelangen einmal durch einen Eingabefilter. 
    
    Parameter:  $ObjektID=  Eindeutige ID des Objektes
                $Name =     Name des Objektes
                $Tel =      TelefonNummer
                $Adresse =  Adresse
                $Ort   =    Ort
                $Plz    =   Postleitzahl
                $Status =   Status OK oder Gesperrt
                
    Bsp: 
    UPDATE `oss_test`.`objekt` SET `Name` = 'FolterKeller', `Tel` = '030/390006002',
     `Str_HN` = 'Bochumer Straße 8x', `Ort` = 'Dresden', `PLZ` = '10553' WHERE `objekt`.`ObjektID` = 100000            
    
    ##########################################################################################################*/
    function UpdateObjektByObjektID($ObjektID,$Name,$Tel,$Adresse,$Ort,$Plz,$Status)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $Sql =  "UPDATE `oss_test`.`objekt`".
                "SET `Name` =  ?, ".
                "`Tel` = ?, ".
                "`Str_HN` = ?, ".
                "`Ort` = ?, ".
                "`PLZ` = ?, ".
                "`Status_OJ` = ? ".
                "WHERE `objekt`.`ObjektID` = ? ";
                $prepstmt = $dbconn->stmt_init();
                $prepstmt->prepare($Sql); 
                $prepstmt->bind_param("sssssss",$Name,$Tel,$Adresse,$Ort,$Plz,$Status,$ObjektID);
                $prepstmt->execute();   
        
        
    }
    /*###############################################################################################
    function SaveMitarbeiter($Vorname, $Nachname, $Email, $Adresse, $Ort, $PLZ , $Status , $ID)
    
    Speichert updates von Mitarbeiter in der Datenbank. Vorher müsen die Datensätze durch einen Eingabecheck
    
    Parameter:  $Vorname,           der Vorname 
                $Nachname,          der Nachname
                $Email,             die Email Adresse
                $Adresse,           die Adresse also Straße 
                $Ort,               der Ort
                $PLZ ,              die Postleitzahl
                $Status ,           der Status
                $ID                 die ID 
    
           /* 
       $sql = "UPDATE `oss_test`.`mitarbeiter`".
              "SET `Vname` = '".$Vorname."',". 
                 " `Nname` = '".$Nachname."',". 
                 " `Dienst_E_Mail` = '".$Email."',". 
                 " `Str_HN` = '".$Adresse."',". 
                 " `Ort` = '".$Ort."',". 
                 " `PLZ` = '".$PLZ."',". 
                 " `Status_MA` = '".$Status."'". 
                 " WHERE `mitarbeiter`.`MitarbeiterID` = '".$ID."';"; 
             $dbconn->query($sql); 
             */
    
    ################################################################################################*/
    function SaveMitarbeiter($Vorname, $Nachname, $Email, $Adresse, $Ort, $PLZ , $Status , $ID)
    {
            $dbconn = dbconnect("HssUser","oss_test");
            $sql = "UPDATE `oss_test`.`mitarbeiter`".
              "SET `Vname` = ?,". 
                 " `Nname` = ?,". 
                 " `Dienst_E_Mail` = ?,". 
                 " `Str_HN` = ?,". 
                 " `Ort` = ?,". 
                 " `PLZ` = ?,". 
                 " `Status_MA` = ?". 
                 " WHERE `mitarbeiter`.`MitarbeiterID` = ? ;";   
             $prepstmt = $dbconn->stmt_init();
             $prepstmt->prepare($sql); 
             $prepstmt->bind_param("ssssssss", $Vorname, $Nachname, $Email, $Adresse, $Ort, $PLZ , $Status , $ID);
             $prepstmt->execute();    
             
            if ($prepstmt->execute() === TRUE) 
            {
           // echo "Record updated successfully";
           // echo $sql;
            //$ziel="./intern.php?".session_name()."=".session_id();
		    $ziel="./intern.php?".SID;
			// Umlenkung nach inter.php mit einer Session
			header("Location: $ziel");
            }
    }            
    /*##################################################################################
    function GetMeineDaten();
    
    Parameter: Keine die übergeben werden jedoch muss im Superglobalen Array 
    $_SESSION['login]['uid'] die ID des Chef`s stehen
    
    eine überprüfung der uid muss nicht statfinden da sie aus der Datenbank in die Session 
    geschrieben wurde und von der Datenbank erstellt wurde
    
    ###################################################################################*/
    function GetMeineDaten()
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SqlMeineDaten = "SELECT * FROM `chef` WHERE `ChefID` = ".$_SESSION['login']['uid'].";";
        		// Schritt 4 : Abfrage abschicken und Ergebnis entgegennehmen
		$result = $dbconn->query($SqlMeineDaten);
		if ( $result == FALSE )
		{
			echo "<div><b>SQL-Fehler:</b> in : ".$SQLstring."<br />".$dbconn->error."</div>";
			
			// im Echtbetrieb: Umlenkung auf eine eigene Fehlerseite
			die("Das war's - CIAO");
		}

		// Schritt 5 : DS fetch-en
		$ds = $result->fetch_assoc();
        return $ds;
    }
    /*###################################################################################
    //function NachrichtenLöschen($NachrichtenID)
    
    Setzt die Nachricht mit der ID in der Datenbank auf Gelöscht. Die Nachricht verbleit in der Datenbank 
    und wird nun nicht mehr angezeigt. 
    
    Parameter: $NachrichtenID : Die Eindeutige ID der Nachricht in der Datenbank
    
    rückgabewert keiner
    
    */################################################################################## 
    function NachrichtenLöschen($NachrichtenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`nachrichten` SET `Status_Nach` = \"Gelöscht\" WHERE `nachrichten`.`NachrichtenID` = ?;";
         $prepstmt = $dbconn->stmt_init();
             $prepstmt->prepare($SQL); 
             $prepstmt->bind_param("s", $NachrichtenID);
             $prepstmt->execute();
    }
        /*###################################################################################
    //function NachrichtGelesen($NachrichtenID)
    
    Setzt die Nachricht mit der ID in der Datenbank auf Gelesen. 
    
    Parameter: $NachrichtenID : Die Eindeutige ID der Nachricht in der Datenbank
    
    rückgabewert keiner
    
    */################################################################################## 
    function NachrichtGelesen($NachrichtenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`nachrichten` SET `Status_Nach` = 'Gelesen' WHERE `nachrichten`.`NachrichtenID` = ?;";
         $prepstmt = $dbconn->stmt_init();
             $prepstmt->prepare($SQL); 
             $prepstmt->bind_param("s", $NachrichtenID);
             $prepstmt->execute();
    }
    /*###################################################################################
    function GetRoutenInBearbeitung($Kid)
    
    Diese Funktion holt alle Routen die Aktuell in Bearbeitung sind aus der Datenbank
    
    Parameter:      $Kid: Die KundenId aus der Session 
    
    ####################################################################################*/
    function GetRoutenInBearbeitung($Kid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL =" SELECT	Routenplan.RoutenID
                FROM	Routenplan INNER JOIN Mitarbeiter
			    USING (MitarbeiterID)
                WHERE	Mitarbeiter.KundenID = ? AND Routenplan.Status_RP = 'In Bearbeitung';";
         $prepstmt = $dbconn->stmt_init();
         $prepstmt->prepare($SQL); 
         $prepstmt->bind_param("s", $Kid);
         $prepstmt->execute();    
         $result = $prepstmt->get_result();

   
		 // Schritt 5 : DS fetch-en
		 $ds = $result->fetch_all(MYSQLI_ASSOC);

		 // Schritt 6: Datensatz bekommen? 
		 if ( !$ds )
			$ds = false;  

    return $ds;
    }
   /*########################################################################################
	function GetLoggedInID()
	
	diese funktion liefert die IDs aller mitarbeiter die gerade eingeloggt sind 
	
	Paramater : keine 
	
	Rückgabewert: Ein assoziatives array mit den mitarbeiter Ids
	
	*/#######################################################################################
    function GetLoggedInID()
	{
		$dbconn = dbconnect("HssUser","oss_test");
		$SQL ="SELECT `MitarbeiterID` FROM `login_mitarbeiter` WHERE `Status` = 'LoggedIn';";
		
        $result = $dbconn->query($SQL);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        return $ds;
    } 
    /*#####################################################################################
    function GetSIDbyUID($uid)

    holt die ScannSession des Mitarbeiters aus der DB

    Parameter :     $uid    Die UserID eines Eingeloggten Mitarbeiters
    
    Return:     Die SessionID des Scannenden Mitarbeiters 
    */#####################################################################################  
    function GetSIDbyUID($uid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
		$SQL ="SELECT `SID` FROM `login_mitarbeiter` WHERE `MitarbeiterID` = ".$uid." ;";
        $result = $dbconn->query($SQL);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        return $ds;
    }
    /*#####################################################################################
    function GetUserdatenByUID($uid)
    
    holt den vor und Nachname des Users mit der id $uid aus der Datenbank
    
    Parameter $uid      Eine Gültige uid eines Mitarbeiters
    
    rückgabe:           Ein Assoziatives Arra mit Vor und Nachname des Mitarbeiters 
    
    */####################################################################################
    function GetUserdatenByUID($uid)
    {
        
        $ds = "";
        $dbconn = dbconnect("HssUser","oss_test");
        $dbconn->query("SET NAMES latin1");
        //$dbconn->set_charset("utf-8");
        $SQL = "SELECT `Vname`,`Nname`,`Status_MA` FROM `mitarbeiter` WHERE `MitarbeiterID` = ".$uid.";";
        
        
        $result = $dbconn->query($SQL);
        $ds = $result->fetch_all(MYSQLI_ASSOC); 
        

        return $ds;
    }
    
    /*###################################################################################
    //function GetRoutenByKid($kid)
    
    holt alle Routen von diesem Kunden aus der Datenbank
    
    Parameter:  $kid    eine gültige KundenID
    
    ####################################################################################*/
    function GetRoutenByKid($kid, $ObjID = "", $Filter = "")
    {
         $dbconn = dbconnect("HssUser","oss_test");
         if($Filter == "Alle" || $Filter == "" )
         {
         $sql = "SELECT `RoutenID`, `Name`, `Beschreibung`, `Status_RO`, `ObjektID` FROM `routen` WHERE `kid` = ? AND  `ObjektID` = ?";
         }
         else
         {
         $sql = "SELECT `RoutenID`, `Name`, `Beschreibung`, `Status_RO`, `ObjektID` FROM `routen` WHERE `kid` = ? AND  `ObjektID` = ? AND  `Status_RO` = ?";    
         }
         if($Filter == "" && $ObjID == "")
         {
         $sql = "SELECT `RoutenID`, `Name`, `Beschreibung`, `Status_RO`, `ObjektID` FROM `routen` WHERE `kid` = ? ";    
         }
         $prepstmt = $dbconn->stmt_init();
         $prepstmt->prepare($sql);
         if(($Filter == "Alle" || $Filter == "") && $ObjID != "" )
         {
         $prepstmt->bind_param("ss", $kid, $ObjID);
         }
         if(($Filter == "Alle" || $Filter == "" )&& $ObjID == "")
         {
         $prepstmt->bind_param("s", $kid);    
         }
         if($Filter != "" && $ObjID != "")
         {
         $prepstmt->bind_param("sss", $kid, $ObjID, $Filter);    
         }
         $prepstmt->execute();    
         $result = $prepstmt->get_result();
         return $result;
    }
    /*#####################################################################################
    //function RoutenSperren($RoutenID)
    
    sperrt die Route mit der ID $RoutenID
    
    Parameter $RoutenID eine gültige Route
    
    #######################################################################################*/
    function RoutenSperren($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`routen` SET `Status_RO` = 'Gesperrt' WHERE `routen`.`RoutenID` = ?;";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute(); 
    }
    /*########################################################################################
    //function RoutenAktivieren($RoutenID)
    
    Aktiviert die Route mit der ID $RoutenID
    
    Parameter $RoutenID eine gültige Route
    
    #########################################################################################*/
    function RoutenAktivieren($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`routen` SET `Status_RO` = 'OK' WHERE `routen`.`RoutenID` = ?;";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute();    
    }
    /*######################################################################################
     function InsertRouten($Name, $Beschreibung, $uid, $kid)
     
     fügt eine Route in die Datenbank ein diese Route ist leer 
     
     parameter:     $Name, Der RoutenName
                    $Beschreibung, Die RoutenBeschreibung
                    $uid, Die UserID
                    $kid, DIe KundenID
    ######################################################################################*/
    function InsertRouten($Name, $Beschreibung, $ObjID, $kid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "INSERT INTO `oss_test`.`routen` (`RoutenID`, `Name`, `Beschreibung`, `Erstell_ZS`, `Status_RO`, `kid`, `ObjektID`) VALUES (NULL, ?, ?, CURRENT_TIMESTAMP, 'OK', ?, ?);";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("ssss", $Name, $Beschreibung, $ObjID, $kid);
        $prepstmt->execute();   
    }
    /*######################################################################################
    // function GetRoutenDetails($RoutenID)
    
    holt alle NFC Tags aus dieser route 
    
    parameter $RoutenID 
    
    rückgabe 2Dimensionales assoziatives array mit allen NFC Tags der Route
    
    #######################################################################################*/
    function GetRoutenDetails($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL ="SELECT `NFC_ID`, `RoutenID`, `Routenindex`, `Rotinenzeit` FROM `eingeordnet` WHERE `RoutenID` = ? ORDER BY `eingeordnet`.`Routenindex` ASC ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
         return $result;  
    }
    /*#########################################################################################
    // function GetNFCDetails($NFCID)
    
    holt alle details zu dem NFC Tag mit der ID $NFCID
    
    parameter $NFCID eine gültige NFC 
    
    rückgabewert: ein assoziatives arra mit den infos des Tags 
    #########################################################################################*/
    function GetNFCDetails($NFCID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL ="SELECT `NFC_ID`, `ObjektID`, `Stock`, `Raumname`, `RaumNr`, `Position`, `Erstell_ZS`, `Kartenbild`, `Status_NFC` FROM `nfc_tags` WHERE `NFC_ID` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $NFCID);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort; 
    }
    /*#########################################################################################
    // GetObjektIDByRoutenID($RoutenID)
    
    liefert die Objekt ID zu einer Route
    
    parameter $RoutenID eine gültige RoutenID
    
    rückgabewert : Die objektId in einem assoziativen array 
    ##########################################################################################*/
    function GetObjektIDByRoutenID($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL ="SELECT `ObjektID` FROM `routen` WHERE `RoutenID` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort; 
        
    }
    /*##############################################################################################
    // function DeletFromEingeordnet($RoutenID)
    
    löscht die Route mit der ID $RoutenID aus der tabelle Eingeordnet sofern diese noch nicht benutzt wurde 
    
    ###############################################################################################*/
    function DeletFromEingeordnet($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "DELETE FROM `eingeordnet` WHERE `RoutenID` = ?";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute();  
        
    }
    /*################################################################################################
    // function InsertIntoEingeordnet($NFCID,$RoutenID,$index,$zeit)
    
    fügt einen NFC Tag einer Route hinzu 
    
    parameter : $NFCID, ein gültiger NFC Tag
                $RoutenID, eine gültige RoutenID
                $index, der index
                $zeit,  die Zeit die Der mitarbeiter hat diesen Tag zu scannen 
    
    #################################################################################################*/
    function InsertIntoEingeordnet($NFCID,$RoutenID,$index,$zeit)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "INSERT INTO `eingeordnet`(`NFC_ID`, `RoutenID`, `Routenindex`, `Rotinenzeit`) VALUES ( ? , ? , ? , ? )";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("ssss",$NFCID, $RoutenID, $index, $zeit);
        $prepstmt->execute();  
        
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
    /*###################################################################################################
    //function InsertIntoRoutenPlan($MitarbeiterID,$RoutenID,$Startdatum,$Startzeit)
    
    fügt einen Planeintrag in die Tabelle Routenplan ein 
    
    parameter:  $MitarbeiterID,     eine gültige MitarbeiterID
                $RoutenID,          eine gültige RoutenID 
                $Startdatum,        ein gültiges Datum
                $Startzeit          eine gültige startzeit zwischen 00:00 und 23:59 
    
    ###################################################################################################*/
    function InsertIntoRoutenPlan($MitarbeiterID,$RoutenID,$Startdatum,$Startzeit,$Uniqe)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "INSERT INTO `routenplan`( `MitarbeiterID`, `RoutenID`, `Startdatum`, `Startzeit`, `Status_RP` , `Prüfvariable`) VALUES ( ? , ? , ? , ? , \"Zu Laufen\", SHA2( ? , 512 ) )";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("sssss",$MitarbeiterID,$RoutenID,$Startdatum,$Startzeit,$Uniqe);
        $prepstmt->execute();  
        
    }
    /*###################################################################################################
    //function GetMitarbeiterDatenOK($dbconn, $kid)
    
    holt alle mitarbeiter des Kunden $kid aus der Datenbank die den Staus OK haben  
    
    
    ###################################################################################################*/
    function GetMitarbeiterDatenOK($dbconn, $kid)
    {
        $SQLstring = "SELECT * FROM `mitarbeiter` WHERE KundenID = ".$kid." AND `Status_MA` = \"OK\"";
        $dbconn->query("SET NAMES UTF8");
        //$dbconn->set_charset("latin1");
        $result = $dbconn->query($SQLstring);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        
        return $ds;
    }
    /*###################################################################################################
    // function GetMitarbeiterIDbyName($Nname)
    
    holt die id des Mitarbeiter mit dem NAme $Nname aus der Datenabank
    
    parameter $Nname : ein gültiger NAme eines Mitarbeiters
    
    rückgabe: im erfolgsfall die uid des mitarbeiters 
    
    ####################################################################################################*/
    function GetMitarbeiterIDbyName($Nname)
    {
        $dbconn = dbconnect("HssUser","oss_test");
       
        $SQL ="SELECT `MitarbeiterID` FROM `mitarbeiter` WHERE `Nname` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $Nname);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort; 
        
    }
    /*###################################################################################################
    // function GetRoutenIDbyName($Name)
    
    holt mit dem NAmen einer Route ihre ID aus der Datenbank
    
    Parameter: $Name der NAme einer Route 
    
    rückgabe im erfolgsfall die id der Route  
    
    #####################################################################################################*/
    function GetRoutenIDbyName($Name)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL ="SELECT * FROM `routen` WHERE `Name` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $Name);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort; 
        
    }
    /*##################################################################################################
    // GetRoutenNameByID($RoutenID)
    
    holt den Routenname der $RoutenId aus der Datenbank
    ###################################################################################################*/
    function GetRoutenNameByID($RoutenID)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL ="SELECT `Name` FROM `routen` WHERE `RoutenID` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s", $RoutenID);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort;      
    }
    /*#####################################################################################################
    //function SpeicherMeineDaten()
    
    speichert die Nutzerdaten ab 
    
    parameter keine 
    
    rückgabewert keiner
    #####################################################################################################*/
    function SpeicherMeineDaten($Nachname,$Email,$Adresse,$plz,$Ort, $cid)
    {
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`chef` SET `Nname` = ?, `Dienst_E_Mail` = ?, `Str_HN` = ?, `PLZ` = ? , `Ort` = ? WHERE `chef`.`ChefID` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("ssssss",$Nachname,$Email,$Adresse,$plz,$Ort,$cid);
        $prepstmt->execute();  

    }
    /*##################################################################################################
    //function RoutenplanChecker()
    
    checkt ob alle Routen die zu laufen sind in bearbeitung sind sind sie es nicht markiert er sie als Ausgefallen 
    
    ####################################################################################################*/
    function RoutenPlanChecker()
    {
        $today = date("Y, n, j"); 
        //echo $today;
    }
    /*########################################################################################################
    //function UpdateRoutenPlan($PlanID,$Datum,$Uhrzeit,$MitarbeiterID)
    
    updatet einen Routenplan 
    
    ##########################################################################################################*/
    function UpdateRoutenPlan($PlanID,$Datum,$Uhrzeit,$MitarbeiterID)
    {
        
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`routenplan` SET `MitarbeiterID` = ? , `Startdatum` = ?, `Startzeit` = ? WHERE `routenplan`.`PlanID` = ?;";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("ssss",$MitarbeiterID,$Datum,$Uhrzeit,$PlanID);
        $prepstmt->execute();  
        
    }
    /*#########################################################################################################
    // function GetMitarbeiterDatenByID($ID)
    
   #############################################################################################################*/
   function GetMitarbeiterDatenByID($ID)
   {
        $dbconn = dbconnect("HssUser","oss_test");
        $sql = "SELECT * FROM `mitarbeiter` WHERE `MitarbeiterID` = ? ";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($sql);
        $prepstmt->bind_param("s", $ID);
        $prepstmt->execute(); 
        $result = $prepstmt->get_result();
        $antwort = $result->fetch_all(MYSQLI_ASSOC);
        return $antwort;    
       
   } 
   /*#########################################################################################################
   UpdateRoutenplanFail($RoutenplanID);	
   updatet eine plan als fehlgeschlagen wenn er in der vergangenheit liegt 
   
   
   ########################################################################################################*/
   function UpdateRoutenplanFail($RoutenplanID)
   {        
        $dbconn = dbconnect("HssUser","oss_test");
        $SQL = "UPDATE `oss_test`.`routenplan` SET `Status_RP` = 'Ausgefallen' WHERE `routenplan`.`PlanID` = ? ;";
        $prepstmt = $dbconn->stmt_init();
        $prepstmt->prepare($SQL);
        $prepstmt->bind_param("s",$RoutenplanID);
        $prepstmt->execute();  
        
       
   }
   /*#######################################################################################################
   //function LiveAnsichtLoggedInUser()

   holt aus der Datenbank alle User die Eingeloogt sind und ihren letzten Scan 

   #######################################################################################################*/	
   function LiveAnsichtLoggedInUser()
   {
        $dbconn = dbconnect("HssUser","oss_test");
        $sql = "SELECT * FROM `login_mitarbeiter` WHERE `Status` = \"LoggedIn\"";
        $dbconn->query("SET NAMES UTF8");
        //$dbconn->set_charset("latin1");
        $result = $dbconn->query($sql);
        $ds = $result->fetch_all(MYSQLI_ASSOC);
        //DebugArr($ds);
        return $ds;  
   }
    ?>