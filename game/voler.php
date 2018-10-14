<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: voler.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.17 $
$Date: 2010/01/24 17:44:05 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $voler;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
	if($PERSO->Archive){
		//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
		$etape="Archive";	
	}
	
	if(!isset($etape)){	
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
		/**
		*  Le premier parametre est mis  0 => on peut voler les morts aussi     
		*/
		$SQL = $PERSO->listePJsDuLieuDuPerso(0, false, true,1);
		$var=faitSelect("id_cible",$SQL,"",-1,array($PERSO->ID));
		if ($var[0]>0) {
			$template_main .= GetMessage("questionVoler")."<br />".$var[1];
			$template_main .= "<br />".BOUTON_ENVOYER;
		}	else $template_main .= GetMessage("personneAVoler")."<br />";
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
		$etape=0;
	} 

	if($etape=="1"){
		$etape=0;
		$ADVERSAIRE = new Joueur($id_cible,true,true,true,true,true,true);
		$sommeVolee=voler ( $ADVERSAIRE,$PERSO,false,true,false);	
		if ($sommeVolee>=0)
			traceAction("VolerPJ", $PERSO, "", $ADVERSAIRE, $sommeVolee);
		$template_main .= "<br /><p>&nbsp;</p>";		
	}	

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>