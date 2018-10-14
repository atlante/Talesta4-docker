<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: status.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/09/05 06:40:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $adm_status_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Qui voulez vous voir ?<br />";
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["Status"])){
		define("__MENU_JEU.PHP",1);
//		define("__FOOTER.PHP",1);
		$PERSO = new Joueur($id_cible,true,true,true,true,true,true,true);
		include('../game/status.'.$phpExtJeu);
		
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){ include('../include/footer.'.$phpExtJeu);}//else{$template_main .= "</div></body></html>";}
?>