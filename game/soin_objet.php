<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: soin_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.8 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $soin;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
	
	if($PERSO->Archive){
		//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
		$etape="Archive";	
	}
	
	if(!isset($etape)){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
		$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,0);
		$var= faitSelect("id_cible",$SQL,"");
		if ($var[0]>0) {
			$SQL =  $PERSO->listeObjets(array('Soins','SoinsPI'), null,0,0,0,0);
			$var2= faitSelect("id_objet_soin",$SQL,"");	
			if ($var2[0]>0) {
				$template_main .= "Qui voulez vous soigner ?<br />";
				$template_main .= $var[1];		
				$template_main .= "<br />Avec quel objet ?<br />";
				$template_main .= $var2[1];
				$template_main .= "<br />".BOUTON_ENVOYER;
			}
			else $template_main .= "<br />Vous n'avez aucun objet soin. <br />";		
		}
		else $template_main .= "Il n'y a personne ici. <br />";
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form></div>";
		$etape=0;
	} 

	if($etape=="1"){

		$etape=0;
		//$template_main .= "competence avant " .$PERSO->GetNiveauComp($liste_comp_full["Soin"],true);
		soin_vegetal($PERSO,$id_cible,$id_objet_soin);
		
		//$template_main .= "competence apres " .$PERSO->GetNiveauComp($liste_comp_full["Soin"],true);
		$template_main .= "<br /><p>&nbsp;</p>";
	}

	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>