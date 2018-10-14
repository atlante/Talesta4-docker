<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: creer_groupe.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.15 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $creer_groupe;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(defined("GROUPE_PJS") && GROUPE_PJS==1) {

	if($PERSO->Archive){
		//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
		$etape="Archive";	
	}


	if(!isset($etape)){$etape=0;}
	if($etape=="1"){
		if(isset($nom_groupe)){
			if ($PERSO->CreerGroupe(ConvertAsHTML($nom_groupe)))
				$PERSO->OutPut("Groupe ".span(ConvertAsHTML($nom_groupe),"etattemp")." correctement cr&eacute;&eacute;",true);
			else 	$PERSO->OutPut("Groupe ".span(ConvertAsHTML($nom_groupe),"etattemp")." n'a pu tre cr&eacute;&eacute;. Dj existant ?",true);
		}
		unset($nom_groupe);
		$template_main .= "<br /><p>&nbsp;</p>";
		
	}
	if($etape===0){
		if ($PERSO->Groupe!="") {
			$template_main .= Getmessage("entrer_groupeKO");
			$template_main .= "<br /><p>&nbsp;</p>";
		}
		else {
			$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
			$template_main .= "nom du groupe : <input type='text' name='nom_groupe' value='' size='15' /><br />";
			$template_main .= "<br />". GetMessage("warningGroupe");
			$template_main .= "<br />".BOUTON_ENVOYER;
			$template_main .= "<input type='hidden' name='etape' value='1' />";
			$template_main .= "</form></div>";
		}
	}
}
else {
	$template_main .= GetMessage("gestionGroupeInterdit")."<br /><br />";
}	



if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>