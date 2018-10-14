<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: verifauth.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");
if(isset($id_joueur) && !isset($x0)){
		if(isset($Session) && !$Session->existe()){
			$template_main .= "Y'a un probleme dans l'authentification"."\n";
			$template_main .= "</body></html>"."\n";
			exit();
		}
}
?>