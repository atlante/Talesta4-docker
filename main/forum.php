<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: forum.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:27 $

*/

require_once("../include/extension.inc");
if(!defined("__HTTPGETPOST.PHP")) {include('../include/http_get_post.'.$phpExtJeu);}
	
if (isset($admin) && $admin==1) {
	if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
	if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
}	
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if (defined('IN_FORUM') && (IN_FORUM==1)) {
	//Affichage du forum - Kaeru
	if(!defined("SESSION_POUR_MJ")){
		$template_main .= "<iframe name='forum' src='".$forum->URLForum."'  height='800' width='99%'></iframe>";
	}
		else $template_main .= "<iframe name='forum' src='". $forum->URLadministrationForum."' height='800' width='99%'></iframe>";
	//Fin affichage du forum
}
else $template_main .= "Aucun forum n'est install avec ce jeu";

if(!defined("SESSION_POUR_MJ")) {
	if(!defined("__MENU_SITE.PHP")){include('../main/menu_site.'.$phpExtJeu);}
}
else 
if(!defined("__MENU_ADMIN.PHP")){@include('../admin/menu_admin.'.$phpExtJeu);}

if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>