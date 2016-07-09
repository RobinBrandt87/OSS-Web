<?php
  include_once "./src/php_funcs.php";
  include_once "./src/db_funcs.php";
/*##########################################################################################
function PrintErrorDiv( $errno )

Diese Funktion erzeugt ein gültiges html Div der Klasse error mit Formatierter 
Fehlerbeschreibung

Parameter:			$errno eien Fehlernummer bisher vergeben siehe switch-case  
 					$error

Rückgabewert:		das Fertige html div als string 

#########################################################################################*/
function PrintErrorDiv( $errno )
{
	switch ($errno)
	{
		case 10:	
			$title = "Falsche Anmeldedaten";
			$desc  = "Diese Kombination aus Benutzername und Passwort ist ungültig. Bitte geben Sie Ihre korrekten Daten erneut ein.";
			break;
			
		case 11:
			$title = "Falsche Anmeldedaten";
			$desc  = "Diese Kombination aus Benutzername und Passwort ist ungültig. Bitte geben Sie Ihre korrekten Daten erneut ein.";
			break;
		
		case 9:
			$title = "Sie sind NICHT Angemeldet";
			$desc  = "Bitte Melden SIE sich mit ihren Nutzerdaten AN";
			break; 	
		
		default:
			$title = "Unbekannter Fehler";
			$desc  = "Sorry das hätte nicht passieren dürfen";
		
					
	}
	
        // Fehlermeldung: Falsche Anmeldedaten
				$errmsg = 
					"\n<div class=\"error\">".
					"\n\t<div class=\"title\">".$title."</div>".
				  "\n\t<div class=\"desc\">".$desc."</div></div>";
return $errmsg;				  
}
/*#####################################################################################
	Print_MitarbeiterTable($Mitarbeiterrarr);
	
	Diese Funktion erzeugt ein gültiges XHTML DIV mit einer gültigen HTML Tabelle mit allen MitarbeiterDaten
	
	Parameter 
    
	$lehrerarr		 Ein Zweidiemensianalen assioziatives array aller Mitarbeiter
	$OptionalerParm  Ein Optionaler Parameter der zur Bearbeiten ausgabe 
    
	rückgabe 		 das fertige html Div als String 
	
	#####################################################################################*/
	function Print_MitarbeiterTable($Mitarbeiterarr, $OptionalerParm)
	{
     
		// der tabenlenkopf
		$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">
	 \n<table id=\"Mitarbeiter\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>ID</th>
	 \n			<th>Vorname</th>
	 \n			<th>Nachname</th>
     \n			<th>Dienst E-mail</th>
     \n			<th>Straße</th>
     \n			<th>Ort</th>
     \n			<th>PLZ</th>
     \n			<th>Geburtstag</th>
     \n			<th>Einstell-Datum</th>
	 \n			<th>Staus</th>
     \n			<th></th>
     \n			<th>Login </th>
	 \n			<th>Aktiv</th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 // Hier kommen in einer Schleife alle tabelenzeilen hin n mal 
     foreach($Mitarbeiterarr AS $Mitarbeiter)
	{
 
	// $lehrer ist immernoch ein eindimensionale assoziatives array
	if ($Mitarbeiter['MitarbeiterID'] != $OptionalerParm) {
            $dbconn = dbconnect("HssUser","oss_test");
			$dbconn->query("SET NAMES latin1");
            $Status = DarfErDas($dbconn, $Mitarbeiter['MitarbeiterID']);
            if ($Status['0']['Status']=='OK') {
               $option="\n	<td class=\"extra\"><input  type=\"submit\" class=\"Sperren\" name=\"Sperren\" value=\"".$Mitarbeiter['MitarbeiterID']."\" /></td>";
            }
            else {
               $option="\n	<td class=\"extra\"><input  type=\"submit\" class=\"Aktivieren\" name=\"Aktivieren\" value=\"".$Mitarbeiter['MitarbeiterID']."\" /></td>"; 
            }
           
        $table.=
			"\n<tr>".
			"\n	<td>".$Mitarbeiter['MitarbeiterID']."</td>".
			"\n	<td>".$Mitarbeiter['Vname']."</td>".
			"\n	<td>".$Mitarbeiter['Nname']."</td>".
            "\n	<td>".$Mitarbeiter['Dienst_E_Mail']."</td>".
            "\n	<td>".$Mitarbeiter['Str_HN']."</td>".
            "\n	<td>".$Mitarbeiter['Ort']."</td>".
            "\n	<td>".$Mitarbeiter['PLZ']."</td>".
            "\n	<td>".$Mitarbeiter['Geb_Datum']."</td>".
            "\n	<td>".$Mitarbeiter['Einstell_ZS']."</td>".
			"\n	<td>".$Mitarbeiter['Status_MA']."</td>".
            "\n	<td><input  type=\"submit\" name=\"Bearbeiten\"". 
			" class=\"Bearbeiten\" value=\"".$Mitarbeiter['MitarbeiterID'].
			"\" /></td>".
            $option.
			"\n	<td>".$Status['0']['Status']."</td>".
			"\n  </tr>";
    }
    else {

        $table.=
			"\n<tr>".
			"\n	<td><input type=\"hidden\" name=\"ID\" value=\"".$Mitarbeiter['MitarbeiterID']."\" readonly = \"readonly\" /></td>".
			"\n	<td><input type=\"text\" name=\"Vorname\" value=\"".$Mitarbeiter['Vname']."\" /></td>".
			"\n	<td><input type=\"text\" name=\"Nachname\" value=\"".$Mitarbeiter['Nname']."\" /></td>".
            "\n	<td><input type=\"text\"  id =\"Eingabe\" class= \"ExtraBreit\"name=\"Dienst_E_Mail\" value=\"".$Mitarbeiter['Dienst_E_Mail']."\" /></td>".
            "\n	<td><input type=\"text\" class= \"ExtraBreit\" name=\"Str_HN\" value=\"".$Mitarbeiter['Str_HN']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"Ort\" value=\"".$Mitarbeiter['Ort']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"PLZ\" value=\"".$Mitarbeiter['PLZ']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"Geb_Datum\" value=\"".$Mitarbeiter['Geb_Datum']."\" /></td>".
            "\n	<td>".$Mitarbeiter['Einstell_ZS']."</td>".
			"\n	<td><select name=\"Status\" > 
        <option>OK</option> 
        <option>Urlaub</option> 
        <option>Krank</option>  
      </select> </td>".
            "\n	<td>
              <input  class=\"Save\" type=\"submit\" name=\"Save\" value=\"Save\" /></td>".
			  "<td></td>
			   <td></td>".
			"\n  </tr>";
    }
	}
	
	// der tabellenfuß 1 mal 
    $table .="\n<tr>".
			"\n	<td></td>".
			"\n	<td><input type=\"text\" name=\"Vorname2\" value=\"\" /></td>".
			"\n	<td><input type=\"text\" name=\"Nachname2\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" class =\"ExtraBreit\" id =\"Eingabe1\" name=\"Dienst_E_Mail2\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" class= \"ExtraBreit\" name=\"Str_HN2\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" name=\"Ort2\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" name=\"PLZ2\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" name=\"Geb_Datum2\" value=\"\" /></td>".
            "\n	<td><input type=\"hidden\" id =\"Eingabe2\" name=\"ET2\" value=\"".date("Y")."-".date("m")."-".date("m")." ".date("G").":".date("i").":".date("s")."\" readonly = \"readonly\" /></td>".
			"\n	<td><select name=\"Status2\" > 
        <option value=\"OK\">OK</option> 
        <option value=\"Urlaub\">Urlaub</option> 
        <option value=\"Krank\">Krank</option>  
      </select> </td>".
            "\n	<td>
              <input  type=\"submit\" name=\"Insert\" value=\"Insert\" /></td>".
			  "<td><input  type=\"password\" name=\"Passwort2\" placeholder=\"Passwort\" id=\"pswd\" /></td>".
			  "<td><input  type=\"text\" name=\"userlogin\" placeholder=\"LoginName\" id=\"LgIn\" /></td>".
			"\n  </tr>";
	$table .="\n	</tbody> \n</table></form>";
	return $table;
    }
	/*#####################################################################################
	Print_MitarbeiterTable($NFC_Arr);
	
	Diese Funktion erzeugt ein gültiges XHTML DIV mit einer gültigen HTML Tabelle mit allen NFC Tags
	Parameter 
    
	$NFC_Arr		 Ein Zweidiemensianalen assioziatives array aller NFC Tags 
    
	rückgabe 		 das fertige html Div als String 
	
	#####################################################################################*/
	function Print_NFCTable($NFC_Arr)
	{
    $parameter = $_POST['Bearbeiten'];
		// der tabenlenkopf
		$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">
	 \n<table id=\"NFC_Tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>NFC_ID</th>
	 \n			<th>Objekt Name</th>
	 \n			<th>Stockwerk</th>
     \n			<th>Raum Bezeichnung</th>
     \n			<th>Nr</th>
     \n			<th>Position des Tags</th>
     \n			<th>Status</th>
	 \n			<th></th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 // Hier kommen in einer Schleife alle tabelenzeilen hinzufügt n mal 
     foreach($NFC_Arr AS $NFC)
	{
 
	// $NFC ist immernoch ein eindimensionale assoziatives array
		$dbconn = dbconnect("HssUser","oss_test");
        $ObjektID = $NFC['ObjektID'];
		$Name = GetObjektNameByID($dbconn, $ObjektID);
		if ($parameter == $NFC['NFC_ID'] ) {
			        $table.=
			"\n<tr>".
			"\n	<td>".$NFC['NFC_ID']."</td>".
			"\n	<td>".$Name['0']['Name']."</td>".
			"\n	<td>".$NFC['Stock']."</td>".
            "\n	<td><input type=\"text\" name=\"Raumname\" value=\"".$NFC['Raumname']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"Raumnummer\" value=\"".$NFC['RaumNr']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"Position\" value=\"".$NFC['Position']."\" /></td>".
            "\n	<td><select name=\"Status\" > 
        <option>OK</option> 
        <option>Gesperrt</option> 
        <option>Defekt</option>  
      </select> </td>".
			"\n <td><input  type=\"submit\" name=\"Speichern\" class=\"Speichern\" value=\"".$NFC['NFC_ID']."\" /></td> ".
			"\n  </tr>";
		}
		else
		{
						        $table.=
			"\n<tr>".
			"\n	<td>".$NFC['NFC_ID']."</td>".
			"\n	<td>".$Name['0']['Name']."</td>".
			"\n	<td>".$NFC['Stock']."</td>".
            "\n	<td>".$NFC['Raumname']."</td>".
            "\n	<td>".$NFC['RaumNr']."</td>".
            "\n	<td>".$NFC['Position']."</td>".
            "\n	<td>".$NFC['Status_NFC']."</td>".
			"\n <td><input  type=\"submit\" name=\"Bearbeiten\" class=\"Bearbeiten\" value=\"".$NFC['NFC_ID']."\" /></td> ".
			"\n  </tr>";
		}

	}
	// der tabellenfuß 1 mal 
	if(isset($_POST['Filter']) && $_POST['Filter'] == "Alle")
	{
	$table .="\n<tr>".
			"\n	<td><input type=\"hidden\" name=\"NFC_ID_Neu\" value=\"\" readonly = \"readonly\" /></td>".
			"\n	<td><input type=\"hidden\" name=\"ObjektNameNeu\" value=\"".$ObjektID."\" readonly = \"readonly\"  /></td>".
			"\n	<td><input type=\"text\" name=\"Stock_Neu\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" name=\"RaumNameNeu\" value=\"\" class= \"Breit\"  /></td>".
            "\n	<td><input type=\"text\" name=\"RaumNrNeu\" value=\"\" /></td>".
            "\n	<td><input type=\"text\" name=\"PositionNeu\" value=\"\" class= \"Breit\" /></td>".
            "\n	<td><input type=\"text\" name=\"Status_NFC_Neu\" value=\"Gesperrt\" readonly = \"readonly\" /></td>".
            "\n <td><input  type=\"submit\" name=\"Insert_NFC\" value=\"Insert\" /></td>".
			"\n  </tr>";
	}		
	$table .="\n	</tbody> \n</table>\n</form>";
	return $table;
    }
	###################################################################################
/*	Modul das das ferige htmlMenu ausgiebt
													
													
	Übergabe Parameter:	Keiner 
    
    rückgabewert: das Html Menu als String in einem div    
*/##################################################################################
	function HtmlHauptMenu()
	{
	$html =	"
	<div id=\"Menu\">
    <div class=\"MenuButton\">
      <input type=\"submit\" name=\"Menu0\" value=\"Mitarbeiter\" />
      <input type=\"submit\" name=\"Menu1\" value=\"Nachrichten\" />
      <input type=\"submit\" name=\"Menu6\" value=\"NFC Tags Verwalten\" />
      <input type=\"submit\" name=\"Menu2\" value=\"Routen\" />
	  <input type=\"submit\" name=\"Menu8\" value=\"RoutenDetails\" />
	  <input type=\"submit\" name=\"Menu9\" value=\"RoutenPlan\" />
      <input type=\"submit\" name=\"Menu3\" value=\"Protokolle\" />
      <input type=\"submit\" name=\"Menu4\" value=\"Meine Daten Verwalten\" />
      <input type=\"submit\" name=\"Menu5\" value=\"Objekte Verwalten\" />
	  <input type=\"submit\" name=\"Menu7\" value=\"LiveAnsicht\" />".
	  
	 
      "<input type=\"submit\" name=\"Logout\" value=\"Logout\" id = \"Logout\" />
    </div>       
</div>";
return $html;
	}
###################################################################################
/*	Modul das das fertige HtmlSubMenu ausgiebt
													
													
	Übergabe Parameter:	Keiner 
    
    rückgabewert: das Html Menu als String in einem div    
*/##################################################################################
	function HtmlSubmenu()
	{
		$Html = "<div>";
		if (isset( $_SESSION['intern'])&& $_SESSION['intern']=='NFC Tags Verwalten') 
		{
			$Html .= "<form class = \"SubMenu\" action=\"".$_SERVER['PHP_SELF'].'?'.SID."\" method=\"post\">";
			$Html .= ForEachObjekt();
			$Html .="<input  type=\"submit\" name=\"Auswahl\"  value=\"Auswahl\"/></form>";  


		}
		if(isset($_SESSION['intern']) && $_SESSION['intern']=="Routen")
		{
			$Html .= "<form class = \"SubMenu\" action=\"".$_SERVER['PHP_SELF'].'?'.SID."\" method=\"post\">";
			$Html .= "<select name=\"OBJID\" > ";
			$dbconn = dbconnect("HssUser","oss_test");
   			$kid = GetKIDIdByUID($_SESSION['login']['uid']);
    		$ObjArr = GetObjekteByKid( $kid );
			foreach ($ObjArr as $ObjektID) 
			{
			$Name = GetObjektNameByID($dbconn, $ObjektID['ObjektID']);	
   			$Html .= "<option value =\"".$ObjektID['ObjektID']."\">".$Name['0']['Name']."</option>";
			}   
			$Html .= "</select>";
			
			$Html .="<select name=\"Filter\" >";
			$Html .="<option  value =\"Alle\">Alle</option>";
			$Html .="<option  value =\"OK\">OK</option>";
			$Html .="<option  value =\"Gesperrt\">Gesperrt</option>";

		
			$Html .="</select>";
			$Html .="<input  type=\"submit\" name=\"Auswahl\"  value=\"Auswahl\"/></form>"; 	
		}
			
		if (isset( $_SESSION['intern'])&& $_SESSION['intern']=='Protokolle')
		{
			
			$dbconn = dbconnect("HssUser","oss_test");
			$MitarbeiterArr = GetMitarbeiterDaten($dbconn, $_SESSION['login']['kid']);
			//DebugArr($MitarbeiterArr);
			$Html .= "<form class = \"SubMenu\" action=\"".$_SERVER['PHP_SELF'].'?'.SID."\" method=\"post\">";
			
		    $Html .="<select name=\"Filter\" >";
			foreach ($MitarbeiterArr as $Mitarbeiter) 
			{
				$Html .="<option >".$Mitarbeiter['Nname']."</option>";	
			}
		//	$Html .="<option name=\"Objekt\" value =\"\">OBJEKTE</option>";
			$Html .="</select>";
			
		/*	$Html .="<select name=\"Filter\" >";
			$ObjektArr = GetObjekteByKid($_SESSION['login']['kid']);	
			foreach ($ObjektArr as $Objekt)
			{
				
				$Objektname = GetObjektNameByID($dbconn, $Objekt['ObjektID']);
				$Html .="<option name=\"Objekt\" value =\"".$Objekt['ObjektID']."\">".$Objektname['0']['Name']."</option>";
			}
						
			$Html .="</select>";
		*/

			$Html .="<select name=\"Anazahl\" >";
			
			$Html .="<option  >10</option>";
			$Html .="<option  >25</option>";
			$Html .="<option  >50</option>";
			$Html .="<option  >100</option>";
			$Html .="<option >150</option>";				
			$Html .="</select>";
			$Html .="<input  type=\"submit\" name=\"Route\"  value=\"OK\"/>"; 
			$Html .="</form>";
		}	
		if (isset( $_SESSION['intern'])&& $_SESSION['intern']=='Objekte Verwalten')
  		{

			  
		}
		if (isset( $_SESSION['intern'])&& $_SESSION['intern']=='RoutenDetails')
		{
			$kid = $_SESSION['login']['kid'];
			$Routen = GetRoutenByKid($kid);

			$Html .="<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">
					\n<table id=\"RoutenDetailsSub\">
					\n	<thead>
					\n		<tr>";
					foreach($Routen as $Route)
					{
						$Html .= "<th>".$Route['Name']."</th>";	 
					}
					$Html .="
						
					\n		</tr>
					\n	</thead>
					\n	<tbody><tr>";
					foreach($Routen as $Route)
					{
					$Html .="<td><input  type=\"submit\" name=\"RoutenDetailsBearbeiten\" class=\"Bearbeiten\"  value=\"".$Route['RoutenID']."\"/></td>";	
					}
					$Html .="</tr></tbody></table></form>";
		}
		$Html .= "</div>";
		return $Html;
	}
###################################################################################
/*	Modul das die fertige html formatierten Nachrichten  ausgiebt
													
													
	Übergabe Parameter:	$NArr
    
    rückgabewert: das Html Nachrichten als Tabellen - String in einem div    
*/##################################################################################	
	function NachrichtenTabelle($NArr)
	{
		
	$n=0;
			 foreach($NArr as $Message)
	 {
		 $n = $n+1;
	 }
		$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">
	 \n<table name=\"Nachrichtentab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>ID</th>
	 \n 		<th>von Mitarbeiter</th>
	 \n			<th>EingangsZeit</th>
	 \n			<th>Betreff</th>
	 \n 		<th>Status</th>
	 \n 		<th>Aktion</th>
	 \n 		<th >Inhalt</th>
		 
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";

	if(!isset($_SESSION['NachrichtenID']))
	{
		 
		foreach($NArr as $Message)
		{
			$Mitarbeiter = GetMitarbeiterDatenByID($Message['NachrichtenID']);
			//DebugArr($Mitarbeiter);
			if ($Message['Status_Nach'] == "nicht gelesen") 
			{

			$table .=   "\n<tr>".
						"\n	<td>".$Message['NachrichtenID']."</td>".
						"\n	<td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>".
						"\n	<td>".$Message['Nach_ZS']."</td>".
					  	"\n	<td>".$Message['Betreff']."</td>".
						"\n	<td>".$Message['Status_Nach']."</td>".
						
						"\n	<td><input  type=\"submit\" name=\"Gelesen\" class =\"Gelesen\"  value=\"".$Message['NachrichtenID']."\"/></td>";

						$table .= "\n	<td>".$Message['Inhalt']."</td>";
						
				$table .= "\n  </tr>";
			}
			else if($Message['Status_Nach'] == "Gelöscht") 
			{

			}
			else 
			{
										
				
			$table .=   "\n<tr>".
						"\n	<td>".$Message['NachrichtenID']."</td>".
						"\n	<td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>".
						"\n	<td>".$Message['Nach_ZS']."</td>".
					  	"\n	<td>".$Message['Betreff']."</td>".
						"\n	<td>".$Message['Status_Nach']."</td>".
						
						"\n	<td><input  type=\"submit\" name=\"Löschen\" class =\"Löschen\"  value=\"".$Message['NachrichtenID']."\" /></td>";

						$table .= "\n	<td>".$Message['Inhalt']."</td>";
						
				$table .= "\n  </tr>";		
			}
		}
	}
	 
	 $table .= 	  "\n	</tbody> \n</table></form>";
	 return $table;
	}
/*#####################################################################
Print_RoutenTable()

bindet den code für die routentabelle ein 

Parameter Keine 
*/#####################################################################	
function Print_RoutenTable()
{

		$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\"><div></div>
	 \n<table id=\"Routen_Tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>Objekt</th>
     \n			<th>Routen Name</th>
     \n			<th>Routen BeschrreibungTags</th>
     \n			<th>Status</th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 $kid = $_SESSION['login']['kid'];
	 if(isset($_POST['OBJID']))
	 {
	 $ObjID = $_POST['OBJID'];
	 }
	 else 
	 {
	 $ObjID = $_SESSION['OBJID'];		 
	 }
	 if(isset($_POST['Filter']))
	 {
	 $Filter = $_POST['Filter'];		 
	 }

	 $Routen = GetRoutenByKid($kid, $ObjID, $Filter);
	 foreach($Routen as $Route)
	 {
		 if($Route['Status_RO']=="OK")
		 {
		 $table .= "<tr>
		 			\n 	<td>".$Route['ObjektID']."</td>
					\n 	<td>".$Route['Name']."</td>
					\n 	<td>".$Route['Beschreibung']."</td>
					\n 	<td><input  type=\"Submit\" name=\"RouteSperren\" class =\"Sperren\"  value=\"".$Route['RoutenID']."\" /></td></tr>";
					
		 }
		 else 
		 {
		 $table .= "<tr>
		 			\n 	<td>".$Route['ObjektID']."</td>
					\n 	<td>".$Route['Name']."</td>
					\n 	<td>".$Route['Beschreibung']."</td>
					\n 	<td><input  type=\"Submit\" name=\"RouteAktivieren\" class =\"Aktivieren\"  value=\"".$Route['RoutenID']."\" /></td></tr>";
					 
			 
		 }
	 }
	 
	 if(isset($_POST['OBJID'])||isset($_SESSION['OBJID']))
	 {
	 $table .= "<tr><td><input type=\"text\" name=\"OBJKID\" value =\"".$_SESSION['OBJID']."\"  readonly = \"readonly\"/></td>
	 			<td><input type=\"text\" class = \"ExtraBreit\" name=\"Name\"  /></td>
				<td><input type=\"text\" class = \"ExtraExtraBreit\" name=\"Beschreibung\"  /></td>  
				<td><input  type=\"Submit\" name=\"AddRoute\" class =\"Speichern\"  value=\"OK\" /></td></tr>";
	 
	 $table .="\n </tbody>\n</table></form>";
	 }
	 return $table;
}
/*########################################################################
//PrintRoutenDetails($RoutenDetails);

macht aus einen 2 Diemsionalen Array eine html tabelle 

parameter $RoutenDetails

rückgabewert der html code als string 
#########################################################################*/
function PrintRoutenDetails($RoutenDetails,$RoutenID)
{
		$dbconn = dbconnect("HssUser","oss_test");
			$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\"><input  type=\"submit\" class=\"Speichern\" name=\"Speichern\" value=\"\" />
				   <input type=\"hidden\" name=\"RoutenID\" value =\"".$RoutenID."\"  /><div></div>
	 \n<table id=\"RoutenDetailsAlt\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>Stock</th>
     \n			<th>Raum Name</th>
     \n			<th>Raum Nummer</th>
     \n			<th>Position</th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 foreach($RoutenDetails as $RoutenDetail)
	 {
		 $NFC = GetNFCDetails($RoutenDetail['NFC_ID']);
		 
		 $table .="	<tr><td>".$NFC['0']['Stock']."</td>
		 				<td>".$NFC['0']['Raumname']."</td>
						<td>".$NFC['0']['RaumNr']."</td>
						<td>".$NFC['0']['Position']."</td></tr>";
	 }
	 $table .="<tbody></table>";
	 $ObjID = GetObjektIDByRoutenID($RoutenID);
	
	 $NFC_Arr = NFCVerwalten($dbconn , $ObjID['0']['ObjektID'], "OK" );
	 //$table .= Print_NFCTable($NFC_Arr);
	 $table .= Print_NFCTableRoutenDetails($RoutenDetails,$RoutenID);
	 return $table;
	
}


/*#########################################################################
function ProtokollTabelle($Protokollar)

Baut die Tabelle mit dem Scanprotokoll auf und gibt es als String zurück 

parameter: $Protokollar das Protkolarr mit allen Daten 

*/#########################################################################
function ProtokollTabelle()
{	

		$table =        "
	 \n<table id=\"Protokoll_Tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>NFC_ID</th>
	 \n			<th>gescant von Mitarbeiter</th>
	 \n			<th>scan Zeitpunk</th>
	 \n			<th>Raum Nr</th>
	 \n 		<th>Raum Name</th>
	 \n 		<th>Position</th>
	 \n 		<th>Gebäude</th>	 
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 if(isset($_POST['Filter']))
	 {
		
		$MitarbeiterName = $_POST['Filter'];
		$MitarbeiterID = GetMitarbeiterIDbyName($MitarbeiterName);
		//DebugArr($MitarbeiterID);
		$Name = GetUserdatenByUID($MitarbeiterID['0']['MitarbeiterID']);
		$Limit = $_POST['Anazahl'];
		$ScanArr = GetProtokolle( $MitarbeiterID, $Limit);
			 
	 foreach($ScanArr as $Scan)
	 {
		$table .= 	"<tr>
					\n 	<td>".$Scan['NFC_ID']."</td>
					\n 	<td>".$Name['0']['Vname']." ".$Name['0']['Nname']."</td>
					\n 	<td>".$Scan['Scan_ZS']."</td>
					\n 	<td>".$Scan['RaumNr']."</td>
					\n  <td>".$Scan['Raumname']."</td>
					\n  <td>".$Scan['Position']."</td>";
					$dbconn = dbconnect("HssUser","oss_test");
					$ObjektName = GetObjektNameByID($dbconn, $Scan['ObjektID']);
					//DebugArr ($ObjektName);
				$table .="\n  <td>".$ObjektName['0']['Name']."</td></tr>"; 
	 }
	 }
	 $table .=
	 "\n </tbody>
	 \n </table>";
	 
	 return $table;
}
/*#####################################################################################
function Print_Objekt_Table($Objektarr, $OptionalerParm)

#####################################################################################*/
function Print_Objekte_Table($Objektarr, $OptionalerParm)
{
     
		// der tabenlenkopf
		$table = 
        "<form method=\"post\"".
                   "action= \"".$_SERVER['PHP_SELF']."?".SID 
                   ."\">
	 \n<table id=\"Objek_tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>Name</th>
	 \n			<th>TelefonNr.</th>
	 \n			<th>Adresse</th>
     \n			<th>Ort</th>
     \n			<th>Postleitzahl</th>
     \n			<th>Status</th>
     \n			<th></th>

	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 // Hier kommen in einer Schleife alle tabelenzeilen hin n mal 
     foreach($Objektarr AS $Objekt)
	{
 	        $dbconn = dbconnect("HssUser","oss_test");
			$ID =$Objekt['ObjektID'];
            $Objektdetails = GetObjektDetailsByObjektID($ID);
	// $lehrer ist immernoch ein eindimensionale assoziatives array
	if ($Objekt['ObjektID'] != $OptionalerParm) {




        $table.=
			"\n<tr>".
			"\n	<td>".$Objektdetails['Name']."</td>".
			"\n	<td>".$Objektdetails['Tel']."</td>".
			"\n	<td>".$Objektdetails['Str_HN']."</td>".
            "\n	<td>".$Objektdetails['Ort']."</td>".
            "\n	<td>".$Objektdetails['PLZ']."</td>".
            "\n	<td>".$Objektdetails['Status_OJ']."</td>".
            "\n	<td><input  type=\"submit\" name=\"Bearbeiten\" class=\"Bearbeiten\" value=\"".$Objekt['ObjektID']."\" /></td>".
            $option.
			"\n  </tr>";
			
    }
    else {

        $table.=
			"\n<tr>".
			"\n	<input type=\"hidden\" name=\"ID\" value =\"".$Objekt['ObjektID']."\"  />".
			"\n	<td><input type=\"text\" name=\"ObjektName\" value =\"".$Objektdetails['Name']."\" /></td>".
			"\n	<td><input type=\"text\" class =\"Breit\" name=\"Tel\" value=\"".$Objektdetails['Tel']."\" /></td>".
            "\n	<td><input type=\"text\" class =\"Breit\" name=\"Str_HN\" value=\"".$Objektdetails['Str_HN']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"Ort\" value=\"".$Objektdetails['Ort']."\" /></td>".
            "\n	<td><input type=\"text\" name=\"PLZ\" value=\"".$Objektdetails['PLZ']."\" /></td>".
			"\n	<td><select name=\"Status\" > 
        <option>OK</option> 
        <option>Gesperrt</option> 
      </select> </td>".
            "\n	<td>
              <input  type=\"submit\" name=\"Save\" value=\"Save\" /></td>".
			"\n  </tr>";
    }
	}
	
	$table .="\n	</tbody> \n</table></form>";
	return $table;
    }
/*#############################################################################################################
function PrintMeineDaten($MeineDaten)

diese Funktion gibt ein gültigen html string zurück mit einer tabelle der eigenene Daten 

##############################################################################################################*/	
function PrintMeineDaten($MeineDatenArr,$option)
{
	$MeineDatenHtml = 	"<div><h2>Verwaltung Ihrer Daten</h2>". 
						"<p> Wilkommen ".$MeineDatenArr['Vname']." ".$MeineDatenArr['Nname']." hier können Sie ihre Daten Verwalten </p></div>". 
						       "<form method=\"post\"".
                   		"action= \"".$_SERVER['PHP_SELF']."?".SID."\">".
						"<div>\n 	<table><thead>
								\n		<tr>
								\n			<th></th>								
								\n			<th>Aktuelle Daten</th>
								
								\n			<th><input  type=\"submit\" class = \"left\" name=\"Save\" value=\"Save\" /></th>
								
								\n		</tr>
								\n	</thead>
								\n  <tbody>
								\n 		<tr>
								\n			<td> Email </td>".
								"\n			<td>".$MeineDatenArr['Dienst_E_Mail']."</td>".
								"\n			<td><input type=\"text\" name=\"Email\" class = \"Breit\" value=\"".$MeineDatenArr['Dienst_E_Mail']."\" /></td>".
								
								"\n 	</tr>".
								"\n 	<tr>
								 \n			<td> Nachname </td>". 
								"\n			<td> ".$MeineDatenArr['Nname']."</td>".
								"\n			<td><input type=\"text\" name=\"Nachname\" class = \"Breit\" value=\"".$MeineDatenArr['Nname']."\" /></td>".
								
								"\n 	</tr>".
																"\n 	<tr>
								 \n			<td> Ort </td>". 
								"\n			<td> ".$MeineDatenArr['Ort']."</td>".
								"\n			<td><input type=\"text\" name=\"Ort\" class = \"Breit\" value=\"".$MeineDatenArr['Ort']."\" /></td>".
								
								"\n 	</tr>".
																"\n 	<tr>
								 \n			<td> Straße </td>". 
								"\n			<td>".$MeineDatenArr['Str_HN']."</td>".
								"\n			<td><input type=\"text\" name=\"Straße\" class = \"Breit\" value=\"".$MeineDatenArr['Str_HN']."\" /></td>".
								
								"\n 	</tr>".
								
								"\n 	</tr>".
																"\n 	<tr>
								 \n			<td> Straße </td>". 
								"\n			<td>".$MeineDatenArr['PLZ']."</td>".
								"\n			<td><input type=\"text\" name=\"PLZ\" class = \"Breit\" value=\"".$MeineDatenArr['PLZ']."\" /></td>".
								
								"\n 	</tr>".

								"\n 	</tbody></table>
									</div></form>";
	return $MeineDatenHtml;
}
/*#####################################################################################
function GetLiveAnsicht($userdaten)

bereitet die daten für die Live Ansicht tabelle vor 

parameter : $userdaten  ein Array mit Vor und NAchname des Users der rest ist schon in der Session

rückgabewert :  Die fertige Ansicht für den ausgewählten User als String in xhtml 

*/#####################################################################################
function GetLiveAnsicht()
{
	    $html .= "	 
	 \n<table id=\"Routen_Tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>Mitarbeiter</th>
	 \n			<th>Objekt</th>
	 \n			<th>Zeitpunkt des letzten Scan</th>
     \n			<th>Standort des NFC Tags</th>	
	\n			<th>Standort des NFC Tags</th>	
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	$LoggArr = LiveAnsichtLoggedInUser();
	foreach( $LoggArr as $Logg)
	{
			$dbconn = dbconnect("HssUser","oss_test");
			$Name['0']['MitarbeiterID'] = $Logg['MitarbeiterID'];
			$ProtokllArr = GetProtokolle($Name,"1");
			//echo  $Name['0']['MitarbeiterID'];
			$Protokoll = $ProtokllArr->fetch_all(MYSQLI_ASSOC);
			DebugArr($Protokoll);
			$Objekt = GetObjektNameByID($dbconn, $Protokoll['0']['ObjektID']);
			DebugArr($Objekt);
		    $html .= "	 
			\n	<tbody>
			\n <tr>
			\n <td>".$Logg['Nickname']."</td>
			\n <td>".$Protokoll['0']['Scan_ZS']."</td>
			\n <td>".$Objekt['0']['Name']."</td>
			\n <td>".$Protokoll['0']['Raumname']." ".$Protokoll['0']['RaumNr']." ".$Protokoll['0']['Position']."</td>
			\n <td><img id=\"hintergrund\" src=\"./APP-COM/src/Bilder/".$Protokoll['0']['NFC_ID'].".PNG\" alt=\"".$Protokoll['0']['NFC_ID']."\"></td>
			\n </tr>";
	}
 	$html .="</tbody>\n</table>";
    
    return $html;
}
/*#####################################################################################
	Print_MitarbeiterTable($NFC_Arr);
	
	Diese Funktion erzeugt ein gültiges XHTML DIV mit einer gültigen HTML Tabelle mit allen NFC Tags
	Parameter 
    
	$NFC_Arr		 Ein Zweidiemensianalen assioziatives array aller NFC Tags 
    
	rückgabe 		 das fertige html Div als String 
	
	#####################################################################################*/
	function Print_NFCTableRoutenDetails($NFC_Arr,$RoutenID)
	{
	$dbconn = dbconnect("HssUser","oss_test");	
    $parameter = $_POST['Bearbeiten'];
		// der tabenlenkopf
		$table = 
        "
	 \n<table id=\"Auswahl_Tab\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>NFCID</th>
	 \n			<th>Objekt Name</th>
	 \n			<th>Stockwerk</th>
     \n			<th>Raum Bezeichnung</th>
     \n			<th>Nr</th>
     \n			<th>Position des Tags</th>
     \n			<th>Status</th>
	 \n			<th>Index</th>
	 \n 		<th>Strecke in m</th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 // Hier kommen in einer Schleife alle tabelenzeilen hinzufügt n mal 
	  //$ObjektID = $NFC['0']['ObjektID'];
	  $ObjID = GetObjektIDByRoutenID($RoutenID);

	  $Name = GetObjektNameByID($dbconn, $ObjID['0']['ObjektID']);
	  //DebugArr($ObjID);
	  if($ObjID['0']['ObjektID'] != "")
	  {
		$NFC_Arr = NFCVerwalten($dbconn , $ObjID['0']['ObjektID'], "OK" );    
	  }


	  

	  

	  	  $x= 0;
     foreach($NFC_Arr AS $NFC)
	{
 
	// $NFC ist immernoch ein eindimensionale assoziatives array
		
       
		$nfc = GetNFCDetails($NFC['NFC_ID']);
		//DebugArr($Name);
		 $table.=
			"\n<tr>".
			"<td>".$nfc['0']['NFC_ID']."</td>".
			"\n	<td><input type=\"hidden\" name=\"NFC".$x."\" value=\"".$nfc['0']['NFC_ID']."\" />".$Name['0']['Name']."</td>".
			"\n	<td>".$nfc['0']['Stock']."</td>".
            "\n	<td>".$nfc['0']['Raumname']."</td>".
            "\n	<td>".$nfc['0']['RaumNr']."</td>".
            "\n	<td>".$nfc['0']['Position']."</td>".
            "\n	<td>".$nfc['0']['Status_NFC']."</td>".
			"\n <td><select name=\"Status".$x."\" ><option></option>";
        	for ($i=1; $i < 50; $i++) 
			{ 
			 $table.=	"<option>".$i."</option>";  
			}
			
     
	  $table.="</select></td>".
			"\n  ";
			if($x == 0)
			{
			$table .="\n <td><select name=\"Minuten".$x."\" >";	
			 $table.=	"<option>0</option>";
			 
			}
			else
			{
			$table .="\n <td><select name=\"Minuten".$x."\" >";
        	for ($i=1; $i < 10; $i++) 
			{ 
			 $table.=	"<option>".$i."</option>";  
			}
			}
			$x++;
			
     
	  $table.="</select></td>".
			"\n  </tr>";
			
		

	}
	// der tabellenfuß 1 mal 		
	$table .="\n	</tbody> \n</table>\n</form>";
	return $table;
    }
	/*###############################################################
	// function PrintRoutenPlan($Routenplan)
	
	liefert den html String mit der tabelle für den Routenplan.
	
	Parameter: $Routenplan ein assoziatives array mit allen routenplänen 
	
	rückgabewert: den html String für die Tabelle 
	
	###############################################################*/
	function PrintRoutenPlan($RoutenplanArr)
	{$dbconn = dbconnect("HssUser","oss_test");	
	
    $parameter = $_POST['Bearbeiten'];
	
	$MitarbeiterArr = GetMitarbeiterDatenOK($dbconn, $_SESSION['login']['kid']);
	$table = "";
	$table.="<form method=\"post\"".
                   		"action= \"".$_SERVER['PHP_SELF']."?".SID."\">".
						"<div>\n	<table><thead>
								\n		<tr>								
								\n			<th>Mitarbeiter</th>
								\n			<th>Route</th>
								\n 			<th>Datum</th>
								\n 			<th>StartZeit</th>
								\n 			<th>Eingabe</th>
								\n		</tr>
								\n 		</thead>
								\n 		<tbody>
								\n 			<tr>
								\n 				<td><select name=\"Mitarbeiter\" >";
								foreach($MitarbeiterArr as $Mitarbeiter)
								{
									$table .= "<option>".$Mitarbeiter['Nname']."</option>";
								}
								$table .="</select></td>";
								$table .= "<td><select name =\"Route\">";
								$RoutenArr =  GetRoutenByKidAndStatus($_SESSION['login']['kid'],"OK");
								foreach($RoutenArr as $Route)
								{
								$table .="<option>".$Route['Name']."</option>";
								}
								$table .="</select></td>";
								$table .= "<td><select name =\"Datum\">";
								
								for ($i=0; $i < 7; $i++)
								{
								$datum = Date("Y-m-d");
								$date=date_create($datum);
								date_add($date,date_interval_create_from_date_string("".$i."days"));

								$table .="<option>".date_format($date,"Y-m-d")."</option>";
								}
								$table .="</select></td>";
																$table .= "<td><select name =\"Uhrzeit\">";
								
								for ($i=0; $i < 24; $i++)
								{
								$table .="<option>".$i.":00 </option>";
								$table .="<option>".$i.":05 </option>";
								$table .="<option>".$i.":10 </option>";
								$table .="<option>".$i.":15 </option>";
								$table .="<option>".$i.":20 </option>";
								$table .="<option>".$i.":25 </option>";
								$table .="<option>".$i.":30 </option>";
								$table .="<option>".$i.":35 </option>";
								$table .="<option>".$i.":40 </option>";
								$table .="<option>".$i.":45 </option>";
								$table .="<option>".$i.":50 </option>";
								$table .="<option>".$i.":55 </option>";
								}
								$table .="\n </select></td>";
								$table .="\n <td><input  type=\"submit\" name=\"Save\" value=\"Save\" /> \n </td> \n </tr> \n </tbody> \n</table>";
								
	
		// der tabenlenkopf
		$table .= 
        "
	 \n<table id=\"Dienstplan\">
	 \n	<thead>
	 \n		<tr>
	 \n			<th>Mitarbeiter</th>
	 \n			<th>Startdatum</th>
	 \n			<th>Start Zeit</th>
     \n			<th>Status</th>
	 \n 		<th>Route</th>
	 \n 		<th>Option</th>
	 \n		</tr>
	 \n	</thead>
	 \n	<tbody>";
	 // Hier kommen in einer Schleife alle tabelenzeilen hinzufügt n mal 

     foreach($RoutenplanArr AS $Routenplan)
	{
 	$Mitarbeiter = GetUserdatenByUID($Routenplan['MitarbeiterID']);
	// $NFC ist immernoch ein eindimensionale assoziatives array
		
		//DebugArr($Mitarbeiter);
		if($Routenplan['PlanID'] != $_POST['Bearbeiten'])
		{

			$TimeNow = time();
			//echo $TimeNow;
			$Prüfvariable = $Routenplan['Startdatum']." ".$Routenplan['Startzeit'];
			//$Time = date('Y-m-d h:m:i',strtotime("2016-06-14 12:23:00"));
			$dateTime = new DateTime($Routenplan['Startdatum']." ".$Routenplan['Startzeit']); 
			$Time = $dateTime->format('U');

			//$Time = date('Y-m-d h:i:m',strtotime($Prüfvariable));
			if($TimeNow > $Time && $Routenplan['Status_RP'] == "Zu Laufen")
			{
			UpdateRoutenplanFail($Routenplan['PlanID']);	
			
			
			}
			elseif($Routenplan['Status_RP'] == "In Bearbeitung")
			{
			$table .="<tr class =\"green\"><td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>";
			

			}elseif ($Routenplan['Status_RP'] == "Ausgefallen") 
			{
			$table .="<tr class =\"gelb\"><td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>";	
			}
				
			else if($Mitarbeiter['0']['Status_MA'] == "OK" || $Routenplan['Status_RP'] != "Zu Laufen" )
			{

				
				
			$table .="<tr><td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>";
				
				

			}
			else 
			{
			$table .="<tr class =\"red\"><td>".$Mitarbeiter['0']['Vname']." ".$Mitarbeiter['0']['Nname']."</td>";	
			}
			$table .="\n	<td>".$Routenplan['Startdatum']."</td>".
			"\n	<td>".$Routenplan['Startzeit']."</td>".
            "\n	<td>".$Routenplan['Status_RP']."</td>";
			$Name =GetRoutenNameByID($Routenplan['RoutenID']);
			//DebugArr($Name);
			$table.= "\n	<td>".$Name['0']['Name']."</td>";
			if($TimeNow > $Time && $Routenplan['Status_RP'] == "Zu Laufen")
			{
			$table .= "\n  <td>Check?</td> ";
			

			}
			elseif($Routenplan['Status_RP'] == "Zu Laufen")
			{
				$table .= "\n  <td><input  type=\"submit\" class =\"Bearbeiten\" name=\"Bearbeiten\" value=".$Routenplan['PlanID']." /></td> ";
			}
			else 
			{	
				$table .= "\n  <td> Keine </td> ";
			}
			$table .="\n  </tr>";
			
		
		}
		else
		{
			
		}
		
	}
	// der tabellenfuß 1 mal 		
	$table .="\n	</tbody> \n</table></div></form>\n";
	//echo RoutenPlanChecker();
	if(isset($_POST['Speichern']))
	{	
			$TimeNow = time();
			//echo $TimeNow;
			//$Time = date('Y-m-d h:m:i',strtotime("2016-06-14 12:23:00"));
			$dateTime = new DateTime($_POST['DatumNeu']." ".$_POST['UhrzeitNeu']); 
			$Time = $dateTime->format('U');
			//echo "   ";
			//echo $TimeNow;
			//$Time = date('Y-m-d h:i:m',strtotime($Prüfvariable));
			if($TimeNow < $Time && $Routenplan['Status_RP'] == "Zu Laufen")
			{
						$MitarbeiterID =  GetMitarbeiterIDbyName($_POST['MitarbeiterNeu']);
						//$MitarbeiterID['0']['MitarbeiterID']
						$PlanID = $_POST['Speichern'];
						$Uhrzeit = $_POST['UhrzeitNeu'];
						$Datum = $_POST['DatumNeu'];
						UpdateRoutenPlan($PlanID,$Datum,$Uhrzeit,$MitarbeiterID['0']['MitarbeiterID']);
						$ziel="./intern.php?".SID;
						// Umlenkung nach intern.php mit einer Session
						header("Location: $ziel");  
				
			}

	}

	return $table;
		
	}
?>