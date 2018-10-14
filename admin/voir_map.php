<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: voir_map.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.4 $
$Date: 2006/01/31 12:26:20 $

*/
 
require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $map_jeu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}



if($MJ->aDroit($liste_flags_mj["VoirCarte"])){
 	if (file_exists("../lieux/vues/map.cmap")) { 	
	 	 if(file_exists("../lieux/vues/map.gif")) {
			$template_main .= "<map name='webdot0'>";
			//include_once("../lieux/vues/map.cmap");
			$template_main .= file_get_contents("../lieux/vues/map.cmap");
			$template_main .= "</map><img src='../lieux/vues/map.gif' border='0' usemap='#webdot0' alt='Graph by WebDot' />";
		}
		else $template_main .= "Fichier '../lieux/vues/map.gif' inexistant";
	} 
	else $template_main .= "Fichier '../lieux/vues/map.cmap' inexistant";
}
else $template_main .= GetMessage("droitsinsuffisants");




if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
