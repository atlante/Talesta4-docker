<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: proposer.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/01/31 12:26:24 $

*/

require_once("../include/extension.inc");	
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}	
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $proposer;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	

if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

	
	if(!isset($etape)) {
		$etape=0;
		$msg="";
	}		

	if($etape=="1"){
		if ($sommePA<0 ||$sommePI<0) {
			$template_main .= "Vous ne pouvez mettre de points n&eacute;gatifs";
			$etape=0;
		}	
		else 	if (proposer($PERSO, $sommePA,$sommePI,$id_cible, $msg,true)===false)
				$etape=0;
			else $template_main .= "<br /><p>&nbsp;</p>";


	}
	
	if($etape===0){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "A quel MJ voulez vous proposer une action ?<br />";
		$SQL = "Select T1.ID_MJ as idselect, concat(concat(concat(T1.nom,'  ('),T1.titre),')') as labselect from ".NOM_TABLE_MJ." T1 where T1.dispo_pour_ppa=1 ORDER BY T1.nom ASC";
		$var=faitSelect("id_cible",$SQL,"",-1);
		$template_main .= $var[1];
		$template_main .= "<br />Pour <input type='text' name='sommePA' value='1' size='5' /> PAs";
		$template_main .= "<br />Pour <input type='text' name='sommePI' value='1' size='5' /> PIs";
		$template_main .= "<br />Message:<br />";
		$template_main .= "<textarea name='msg' cols='50' rows='20'>".$msg."</textarea>";
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
	}
	

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>