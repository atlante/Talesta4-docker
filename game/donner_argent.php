<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: donner_argent.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.18 $
$Date: 2010/01/24 17:44:01 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $donner_arg;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,1);
	$var=faitSelect("id_cible",$SQL,"",-1,array($PERSO->ID));
	if ($var[0]>0) {
		$template_main .= "Combien voulez vous donner ?<br />";
		$template_main .= "<input type='text' name='somme' value='1' size='4' /><br />";
		$template_main .= "A qui voulez vous le donner ?<br />".$var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= "Il n'y a personne &agrave; qui donner.<br />";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( (isset($id_cible)) && (isset($somme)) && ($PERSO->ModPA($liste_pas_actions["DonnerArgent"])) && ($PERSO->ModPI($liste_pis_actions["DonnerArgent"]))){
		$somme = Max(1,abs($somme));
		$sortir = $PERSO->etreCache(0);
		if ($sortir) {
			$mess = GetMessage("semontrer_01");
				$valeursCache=array();
				$valeursCache[0]= $PERSO->nom;
				$mess_spect = GetMessage("semontrer_spect",$valeursCache);
		}	
		else {
			$mess="";	
			$mess_spect="";
		}	
		//$ADVERSAIRE = new Joueur($id_cible,true);		
		$ADVERSAIRE = new Joueur($id_cible,false, false,false,false,false,false);

		$valeurs[0] = $ADVERSAIRE->nom;
		$valeurs[1] = $PERSO->nom;
		$valeurs[2] = $somme;
		$reussite = reussite_donner_argent($PERSO, $somme);
		if($reussite ){
			$ADVERSAIRE->ModPO($somme);
			$PERSO->OutPut($mess.GetMessage("donner_argent_01",$valeurs),true,true);
			$ADVERSAIRE->OutPut($mess_spect.GetMessage("donner_argent_01_adv",$valeurs),false,true);
			traceAction("DonnerArgent", $PERSO, "", $ADVERSAIRE, $somme);
		} else {
			$PERSO->OutPut($mess.GetMessage("donner_argent_02",$valeurs),true,true);
		}
		
	} else {
		if( (!isset($id_cible)) ){
			$template_main .= GetMessage("noparam");
		} else {
			if ($PERSO->RIP())
				$template_main .= GetMessage("nopvs");
			else	
			if ($PERSO->Archive)
				$template_main .= GetMessage("archive");
			else	
			$template_main .= GetMessage("nopas");
		}
	}
	
	$template_main .= "<br /><p>&nbsp;</p>";
	
}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>