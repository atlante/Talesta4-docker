<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: fa_mj.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.7 $
$Date: 2006/01/31 12:26:17 $

*/

require_once("../include/extension.inc");if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_ADMIN")){Define("PAGE_ADMIN",1);}
if(!defined("SESSION_POUR_MJ"))
	define("SESSION_POUR_MJ", 1);
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fa_mj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}

if(!isset($etape)){$etape=0;}
if($etape=="1"){
	if($MJ->aDroit($liste_flags_mj["FAs"])){
		$MJcible = new MJ($id_cible,false);
		if(isset($del)){
			$MJcible->ArchiveFA(FALSE);
			$etape=0;
		}
		else {
			if ($MJ->ID==1)
				$contenu = $MJcible->LireFA(1);
			else
				$contenu = $MJcible->LireFA(0);
			$fagz = $MJcible->GetCheminFA();			
			if(file_exists($fagz)){		
				$temp=TAILLE_MAX_FA*1024;
				$template_main .= "<p align='right'>Taille du FA: ".filesize ($fagz)." octets sur ".$temp." autoriss</p>";
			}
			$template_main .= ($contenu);

		}	
	}
	else $template_main .= GetMessage("droitsinsuffisants");
}

if($etape===0){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$template_main .= "Qui voulez vous voir ?<br />";
	$SQL = "Select T1.ID_MJ as idselect, T1.nom as labselect from ".NOM_TABLE_MJ." T1 where ID_MJ <> ".$MJ->ID." ORDER BY T1.nom ASC";
	$var=faitSelect("id_cible",$SQL,"",-1);
	$template_main .= $var[1];
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
}
if(!defined("__MENU_ADMIN.PHP")){include('../admin/menu_admin.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>