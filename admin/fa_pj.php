<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: fa_pj.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.18 $
$Date: 2006/09/05 06:41:20 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ")) define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fa_pj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["FAs"])){
		$PERSO = new Joueur($id_cible,false,false,false,false,false,false,false);
		if(isset($del)){
			$PERSO->ArchiveFA(FALSE);
			$etape=0;
		}
		else {
			if ($MJ->ID==1)
				$contenu = $PERSO->LireFA(1);
			else
				$contenu = $PERSO->LireFA(0);
			$fagz = $PERSO->GetCheminFA();			
			if(file_exists($fagz)){		
				$temp=TAILLE_MAX_FA*1024;
				$template_main .= "<p align='right'>Taille du FA: ".filesize ($fagz)." octets sur ".$temp." autorisés</p>";
			}
			$template_main .= ($contenu);
		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}
if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Qui voulez vous voir ?<br />";
	$SQL = "Select T1.id_perso as idselect, T1.nom as labselect from ".NOM_TABLE_REGISTRE." T1 where T1.pnj<>2 ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>