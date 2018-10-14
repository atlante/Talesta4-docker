<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name:  $ 

$RCSfile: index.php,v $
$Revision: 1.3 $
$Date: 2005/08/05 15:45:21 $

*/

require_once("../../../include/extension.inc");

if (file_exists("../../../include/config.".$phpExtJeu)) {
	include_once("../../../include/config.".$phpExtJeu);
}	
header("Location: ../../../main/index.".$phpExtJeu);


?>