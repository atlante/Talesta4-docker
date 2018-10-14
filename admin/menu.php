<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: menu.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.10 $
$Date: 2006/01/31 12:26:18 $

*/

//if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
require_once("../include/extension.inc");
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $adminmenu;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

$template_main .= "<p>Bienvenue dans l'administration MJ, ".span($MJ->nom,"mj").". Ici, vous trouverez toutes les actions vous permettant de gerer le jeu. Le menu a gauche vous indique toutes les actions que vous pouvez effectuer.</p>";
$template_main .= "<br />";

$template_main .= "Je vous invite &agrave; consulter l'aide g&eacute;n&eacute;rale : <a href=\"javascript:a('../admin/gethelp.$phpExtJeu?page=index.htm')\">Aide g&eacute;n&eacute;rale</a>";
$template_main .= " qui peut &ecirc;tre contredite par la <a href=\"javascript:a('../admin/gethelp.$phpExtJeu?page=reglesSpeciales.htm')\">Liste des modifs apport&eacute;es par hixcks</a> ou bien <a href=\"javascript:a('../admin/gethelp.$phpExtJeu?page=aide.htm')\">l'utilisation des comp&eacute;tences</a>";


if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>