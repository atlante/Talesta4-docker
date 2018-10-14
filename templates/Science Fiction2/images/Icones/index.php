<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: index.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/01/24 10:29:42 $

*/

require_once("../../../../include/extension.inc");

if (file_exists("../../../../include/config.".$phpExtJeu)) {
	include_once("../../../../include/config.".$phpExtJeu);
}	
header("Location: ../../../../main/index.".$phpExtJeu);


?>