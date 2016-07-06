 <?php
 error_reporting(0);
  define( "MYDEBUG", false);
  include_once "./src/php_funcs.php";    // 1 Variante
  include_once "./src/db_funcs.php";     // 2 Variante
  ;
       $Absender=$_POST['NachrichtHead'];
       $Betreff=$_POST['Betreff'];
       $Inhalt=$_POST['inhalt'];
       $Inhalt = strip_tags($Inhalt);
	   $Betreff = strip_tags($Betreff);
       $ergebnis=NachrichtenInsert($Inhalt,$Betreff,$Absender);
       if(MYDEBUG)
       {
            echo $Betreff;
            echo $Inhalt;
            
       }
       echo $ergebnis;      
  ?>
