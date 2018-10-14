<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: reparation.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.4 $
$Date: 2006/01/31 12:26:25 $

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

	if ($action=="ArmeMelee") {
		$ObjetsReparables = 'ArmeMelee';
		$sousTypeOutil ='ArtisanArmeMelee';
	}	
	elseif ($action=="ArmeJet") {
		$ObjetsReparables = 'ArmeJet';		
		$sousTypeOutil ='ArtisanArmeJet';
	}	
	elseif ($action=="Armure") {
		$ObjetsReparables = 'Armure';			
		$sousTypeOutil ='ArtisanArmure';
	}	
	elseif ($action=="Outil") {
		$ObjetsReparables = 'Outil';			
		$sousTypeOutil ='ArtisanOutil';
	}	
	else {
		$template_main .= GetMessage("noparam");
		//set etape pour ne plus rien faire sauf include
		$etape="Erreur";
	}	
		
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";

	$SQL =$PERSO->listeObjets(array('Outil'),$sousTypeOutil, 0,0, 0,-1);
	$var= faitSelect("id_outil",$SQL);	
	if ($var[0]>0) {
		$SQL=$PERSO->listeObjets(array($ObjetsReparables), null,-1,0,0,-1);
		$var1= faitSelect("id_objetAreparer",$SQL);	
		if ($var1[0]>0) {
			$template_main .= "<br />Que voulez vous reparer ?<br />";
			$template_main .= $var1[1];		
			$template_main .= "<br />avec quel outil ?<br />";		
			$template_main .= $var[1];		
			$template_main .= "<br />".BOUTON_ENVOYER;
		}
		else $template_main .= "<br />Vous n'avez pas d'objets de ce type à reparer. <br />";		
	}
	else $template_main .= "<br />Vous n'avez aucun objet pour pratiquer l'artisanat. <br />";		
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='action' value='$action' />";
	$template_main .= "<input type='hidden' name='ObjetsReparables' value='$ObjetsReparables' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

	if($etape=="1"){
				
		$etape=0;
		reparation($PERSO,$ObjetsReparables,$id_outil,$id_objetAreparer);
		$template_main .= "<br /><p>&nbsp;</p>";
	}

	if(!defined("__MENU.PHP")){include('../game/menu.php');}
	if(!defined("__FOOTER.PHP")){include('../include/footer.php');}
?>