<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: artisanat.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.3 $
$Date: 2006/01/31 12:26:23 $

*/

include('../include/http_get_post.php');
//if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__HEADER.PHP")){include('../include/header.php');}


if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){

	if ($action=="Ebenisterie") {
		$sousTypeOutil ='Ebeniste';
		$SousTypeProduit ="Bois";

	}	
	elseif ($action=="Metallurgie") {
		$sousTypeOutil ='Forgeron';
		$SousTypeProduit ="Metal";

	}	
	elseif ($action=="Maconnerie") {
		$sousTypeOutil ='Macon';
		$SousTypeProduit ="Pierre";

	}	
	elseif ($action=="Tissage") {
		$sousTypeOutil ='Tisseur';
		$SousTypeProduit ="Vegetaux";

	}	
	elseif ($action=="Brasserie") {
		$sousTypeOutil ='Brasseur';
		$SousTypeProduit ="Vegetaux";

	}	
	elseif ($action=="Cuir") {
		$sousTypeOutil ='Tanneur';
		$SousTypeProduit ="Cuir";

	}	
	else {
		$template_main .= GetMessage("noparam");
		//set etape pour ne plus rien faire sauf include
		$etape="Erreur";
	}	
		
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";

	// $SQL = Liste des objets creables filtres par materiau
	$SQL= "Select id_objet as idselect, nom as labselect from ".NOM_TABLE_OBJET." where type = 'ObjetSimple' and composantes is null and sous_type = '".$SousTypeProduit."'";
	$var= faitSelect("id_objetAcreer",$SQL);	
	if ($var[0]>0) {
		$SQL =$PERSO->listeObjets(array('Outil'),$sousTypeOutil, 0,0, 0,-1);
		$var2= faitSelect("id_outil",$SQL);	
		if ($var2[0]>0) {
			$SQL=$PERSO->listeObjets(array("ProduitNaturel"), $SousTypeProduit,-1,0,0,-1);
			$var1= faitSelect("id_objetMateriau",$SQL);	
			if ($var1[0]>0) {
				$template_main .= "<br />Que voulez vous créer ?<br />";
				$template_main .= $var[1];		
				$template_main .= "<br />avec quel outil ?<br />";		
				$template_main .= $var2[1];		
				$template_main .= "<br />et à partir de quel matériau ?<br />";		
				$template_main .= $var1[1];
				$template_main .= "<br />".BOUTON_ENVOYER;
			}
			else $template_main .= "<br />Vous n'avez pas d'objets de ce type à utiliser. <br />";		
		}
		else $template_main .= "<br />Vous n'avez aucun outil pour pratiquer cet artisanat. <br />";		
	}
	else $template_main .= "<br />Aucun objet de ce matériau à créer. <br />";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='action' value='$action' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

	if($etape=="1"){
				
		$etape=0;
		artisanat($PERSO,$id_outil,$id_objetMateriau,$id_objetAcreer);
		$template_main .= "<br /><p>&nbsp;</p>";
	}

	if(!defined("__MENU.PHP")){include('../game/menu.php');}
	if(!defined("__FOOTER.PHP")){include('../include/footer.php');}
?>