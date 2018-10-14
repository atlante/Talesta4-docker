<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: secacher.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.10 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}

if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $secacher;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if ((!isset($etape)) && (! $PERSO->Lieu->permet($liste_flags_lieux["SeCacher"]))) {
	$etape = "InterditLieu";
	$template_main .= GetMessage("noright");	
}	

if (!isset($etape)) {
	if ($PERSO->dissimule==0)
		$etape=1;
	else 	$etape=2;
}


if($etape=="1"){
	if (defined("SECACHER") &&  SECACHER==1 ) {
		if( $PERSO->ModPA($liste_pas_actions["SeCacher"]) && $PERSO->ModPI($liste_pis_actions["SeCacher"])	){
			$succes=secacher($PERSO);
			if ($succes>0)
				traceAction("SeCacher", $PERSO, "", null);
			
		} else {
			if ($PERSO->RIP())
				$template_main .= GetMessage("nopvs");
			else	
			if ($PERSO->Archive)
				$template_main .= GetMessage("archive");
			else	
				$template_main .= GetMessage("nopas");
		}
		$template_main .= "<br /><p>&nbsp;</p>";

	}
	else $template_main .= GetMessage("seCacherInterdit")."<br /><br />";
}

// on sort de sa cachette
if($etape=="2"){
	if ($PERSO->RIP())
		$template_main .= GetMessage("nopvs");
	else	
	if ($PERSO->Archive)
		$template_main .= GetMessage("archive");
	else {
		$PERSO->etrecache(0);
		$PERSO->OutPut(GetMessage("semontrer_01"));
		$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, false,1);
		$result = $db->sql_query($SQL);
		while(	$row = $db->sql_fetchrow($result)){
			$pjtemp = new Joueur($row["idselect"],false,false,false,false,false,false);
			$valeurs[0]=$PERSO->nom;	
			$pjtemp->OutPut(GetMessage("semontrer_spect",$valeurs),false);
		}	

	}	
	$template_main .= "<br /><p>&nbsp;</p>";

}
if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>