<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: banque.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.18 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $banque;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

if ((!isset($etape)) && (! $PERSO->Lieu->permet($liste_flags_lieux["Banque"]))) {
	$etape = "InterditLieu";
	$template_main .= GetMessage("noright");	
}	

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$valeurs[0] = $PERSO->banque;
	$template_main  .=  GetMessage("SituationBanque",$valeurs)."<br /><br />";
	if ($PERSO->PO>0) {
		$template_main .= "<input type='radio' checked='checked' name='typeact' value='depot' />Deposer <input type='text' size='5' name='somme_depot' /> ".span(" POs","po")."<br />";
		$template_main .= "<hr />";
	}
	if ($PERSO->banque>0) {
		$template_main .= "<input type='radio' name='typeact' value='retirer' />Retirer <input type='text' size='5' name='somme_retirer' /> ".span(" POs","po")." de votre compte<br />";
		$template_main .= "<hr />";
	}
	$template_main .= "<br />".BOUTON_ENVOYER;
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
} 
else 
if($etape=="1"){
	$ok=false;
	if(isset($typeact)){
		switch($typeact){
				case 'depot':{if(isset($somme_depot) && $somme_depot>0){$ok=true;} break;}
				case 'retirer':{if(isset($somme_retirer) && $somme_retirer>0){$ok=true;} break;}
		}
	}
	if( ($ok) && ($PERSO->ModPA($liste_pas_actions["Banque"])) && ($PERSO->ModPI($liste_pis_actions["Banque"])) ){
		$sortir = $PERSO->etreCache(0);
		if ($sortir)
			$mess = GetMessage("semontrer_01");
		else $mess="";	
		if($typeact == "depot"){
			$somme_depot = Max(0,Min($somme_depot,$PERSO->PO));
			$PERSO->ModPO(-$somme_depot);
			$PERSO->ModBanque($somme_depot);
			$valeurs[0]=$somme_depot;
			$PERSO->OutPut($mess.GetMessage("banque_01",$valeurs));
		}
		if($typeact == "retirer"){
			$somme_retirer = Max(0,Min($somme_retirer,$PERSO->banque));
			$PERSO->ModPO($somme_retirer);
			$PERSO->ModBanque(-$somme_retirer);
			$valeurs[0]=$somme_retirer;
			$PERSO->OutPut($mess.GetMessage("banque_02",$valeurs));
		}
	} else {
		if( !($ok) ){
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