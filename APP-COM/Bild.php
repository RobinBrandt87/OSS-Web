<?php
  
  $BildID = $_GET['ID'];
    $html = "<html>
<head>
<title>Bild auf ganzen Hintergrund aufspannen</title>

<style type=\"text/css\">
html, body {
  margin:0;
  padding:0;
  width:100%;
  height:100%;
  overflow:hidden;
}

#hintergrund {
  position:absolute;
  width:100%;
  height:100%;
  z-index:1;
}

</style>
</head>
<body>
<div>
<img id=\"hintergrund\" src=\"./src/Bilder/".$BildID.".png\" alt=\"".$BildID."\"></div></body>
</div></body>
</html>";

    echo $html;
  ?>