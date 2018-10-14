<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: barrestatus.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
//if(!defined("__CONST.PHP")){include('../include/const.'.$phpExtJeu);}

if(!defined("__BARRESTATUS.PHP") ) {
	Define("__BARRESTATUS.PHP",	0);
	if (AFFICHE_XP==1) 
		$XP_Affiche = span($PERSO->XP."/".$PERSO->GetXPMax(),"xp");
	else 
		$XP_Affiche = "&nbsp;";

	if (AFFICHE_PV==1) 
		if($PERSO->getRPV() < 0)
			$PV_Affiche = span($PERSO->PV."/".$PERSO->GetPVMax()." ".$PERSO->getRPV(),"pv");
		else	$PV_Affiche = span($PERSO->PV."/".$PERSO->GetPVMax()." +".$PERSO->getRPV(),"pv");
	else 
		$PV_Affiche = "&nbsp;";
	if($PERSO->getRPA() < 0)
		$PA_Affiche = span($PERSO->PA."/".$PERSO->GetPAMax()." ".$PERSO->getRPA(),"pa");
	else	$PA_Affiche = span($PERSO->PA."/".$PERSO->GetPAMax()." +".$PERSO->getRPA(),"pa");

	if($PERSO->getRPI() < 0)
		$PI_Affiche = span($PERSO->PI."/".$PERSO->GetPIMax()." ".$PERSO->getRPI(),"pi");
	else	$PI_Affiche = span($PERSO->PI."/".$PERSO->GetPIMax()." +".$PERSO->getRPI(),"pi");

	if($PERSO->getRPO() < 0)
		$PO_Affiche = span($PERSO->PO." ".$PERSO->getRPO(),"po");
	else	$PO_Affiche = span($PERSO->PO." +".$PERSO->getRPO(),"po");
		
	$a_afficher=array(
				$XP_Affiche,
				$PA_Affiche,
				$PI_Affiche,
				$PV_Affiche,
				span ($PERSO->Lieu->nom,"lieu"),
				$PO_Affiche,
				// Les barres qui vont avec ...
				DessineBarre($PERSO->XP,$PERSO->GetXPMax(),100,'FF1256',"Jauge d'XP",AFFICHE_XP),
				DessineBarre($PERSO->PA,$PERSO->GetPAMax()+$PERSO->getRPA(),100,'FF1256',"Jauge d'action",true),
				DessineBarre($PERSO->PI,$PERSO->GetPIMax()+$PERSO->getRPI(),100,'FF1256',"Jauge d'intellect",true),
				DessineBarre($PERSO->PV,$PERSO->GetPVMax()+$PERSO->getRPV(),100,'FF1256',"Jauge de vie",AFFICHE_PV),
				"&nbsp;",
				GetImage("Argent")
				
		);

	$barre = "\n<div id='barre_container'>";
	$barre .= "<div id='barre'>";
	$barre .= makeTableau(6,"center","",$a_afficher);
	$barre .= "</div>";
	//$barre .= "<img class='line' src='../templates/$template_name/images/bg_line.jpg' alt='bg_line.jpg' />";
	$barre .= "</div>\n";
	
}
?>