<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: gethelp.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/01/31 12:26:17 $

*/

	require_once("../include/extension.inc");
	if(!defined("__HTTPGETPOST.PHP"))
		{include('../include/http_get_post.'.$phpExtJeu);}
	if(isset($page)){
		include("../Docs/".$page);
		// ajoute cela pour la sauvegarde de l'HTML
		Define("__FOOTER.PHP",	0);
//		$nom_script = explode('/',$HTTP_SERVER_VARS['PHP_SELF']);
//		Define("NOM_SCRIPT",	$nom_script[count($nom_script)-1].$page);
		@include('../include/footer.'.$phpExtJeu);
	}

?>