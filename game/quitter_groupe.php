<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: quitter_groupe.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.13 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $quitter_groupe;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(defined("GROUPE_PJS") && GROUPE_PJS==1) {
	if(!isset($etape)){$etape=0;}
	if($etape=="1"){
		if ($valider==1) {
			$PERSO->QuitterGroupe($PERSO->Groupe, $Nbgroupe);
			$PERSO->OutPut("Vous ne faites d&eacute;sormais plus partie du groupe ".span(ConvertAsHTML($nomgroupe),"etattemp").". ",true);
			if ($Nbgroupe==1) 
				$PERSO->OutPut("Vous &eacute;tiez le dernier membre de ce groupe. Il est donc supprim&eacute; ",true);	
		}
		$template_main .= "<br /><p>&nbsp;</p>";
	}
	if($etape===0){
		if ($PERSO->Groupe=="") {
			$template_main .= "Vous ne faites pas partie d'un groupe";
		}
		else {	
			$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
			$groupe= new Groupe($PERSO->Groupe, true);
			$template_main .= "Quitter le groupe ".  $groupe->nom." ? <select name='valider'><option value='0'>Non</option><option value='1'>Oui</option></select>";
			$template_main .= "<input type='hidden' name='nomgroupe' value='$groupe->nom' />";
			$template_main .= "<input type='hidden' name='Nbgroupe' value='$groupe->nb' />";
			$template_main .= "<br />";
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