<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/01/31 12:26:28 $

*/

require_once("../include/extension.inc");

if (file_exists("../include/config.".$phpExtJeu)) {
	include_once("../include/config.".$phpExtJeu);
}	
header("Location: ../main/index.".$phpExtJeu);


?>