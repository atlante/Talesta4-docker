<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: menu_site.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.11 $
$Date: 2010/01/24 10:30:07 $

*/

require_once("../include/extension.inc");
if(!defined("__MENU_SITE.PHP") ) {
	Define("__MENU_SITE.PHP",	0);
	$liens_menu=array();	
	$existesConfig = file_exists("../include/config.".$phpExtJeu);
	array_push ($liens_menu, array(1,"javascript:a('../admin/gethelp.$phpExtJeu?page=Install.htm')","Aide  l'installation","", !$existesConfig));
	array_push ($liens_menu, array(1,"../main/index.".$phpExtJeu,			"Accueil",			"",$existesConfig));
	array_push ($liens_menu, array(1,"../main/aide_interface.".$phpExtJeu,			"Aide Interface",			"",$existesConfig));
	array_push ($liens_menu, array(1,"../main/joueurs.".$phpExtJeu,			"Liste des participants",			"",$existesConfig));
	if(defined("IN_NEWS")&& IN_NEWS==1 ) 
		array_push ($liens_menu, array(1,"../news/index.".$phpExtJeu,			"L'actu de ".NOM_JEU,			"",$existesConfig));
	if(defined("IN_FORUM")&& IN_FORUM==1) 
		//array_push ($liens_menu, array(1,CHEMIN_FORUM,			"Forum",			"",$existesConfig));
		array_push ($liens_menu, array(1,"../main/forum.".$phpExtJeu,			"Forum",			"",$existesConfig));
	array_push ($liens_menu,	array(1,"../main/inscription.".$phpExtJeu,			"S'Inscrire",			"",$existesConfig));
	array_push ($liens_menu,	array(1,"../commun/login.".$phpExtJeu,		"Jouer",		"",$existesConfig));
	array_push ($liens_menu,	array(1,"../commun/login.$phpExtJeu?Admin=1",		"Admin",		"",$existesConfig));
	array_push ($liens_menu,	array(1,'../game/logout.'.$phpExtJeu,				"Deconnexion PJ",		"",$existesConfig));
	array_push ($liens_menu,	array(1,'../admin/logout.'.$phpExtJeu,				"Deconnexion MJ",		"",$existesConfig));
	array_push ($liens_menu,	array(1,"../main/about.".$phpExtJeu,			"A Propos",			"",$existesConfig));

	include('../include/menu.template.'.$phpExtJeu);

if(!defined("__BARREGENERALE.PHP")){include("../include/barregenerale.".$phpExtJeu);}

}?>
