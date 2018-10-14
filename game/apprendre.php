<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: apprendre.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/01/31 12:26:23 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $apprendre;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	
	
if(!isset($etape)){

	$template_main .= "<div class ='centerSimple'>";
	
	$SQL = "SELECT * FROM ".NOM_TABLE_MAGASIN." T1 WHERE T1.id_lieu = ".$PERSO->Lieu->ID." AND T1.type = ".$liste_types_magasins["Lieu d'apprentissage"];
	$result = $db->sql_query($SQL);
	
	if($db->sql_numrows($result) > 0){
		$template_main .= "<form action='".NOM_SCRIPT."' method='post'>";
		$template_main .= "Dans quelle comp&eacute;tence voulez-vous progressez ? <br />";
		$template_main .= "competence : <select name='competenceID'>";
		$ListeObj = null;
		$compteur=0;
		//for($i=0;$i<$db->sql_numrows($result);$i++){
		while(		$row = $db->sql_fetchrow($result)){
				$ListeObj[$i]= array_search($row["pointeur"], $liste_comp_full); 
				$template_main .= "<option value='".$row["pointeur"]."'";
				$template_main .= ">".$ListeObj[$i]."</option>\n";	
				
		}
		$template_main .= "</select>";
		$template_main .= "<br />Pour <input type='text' name='sommePA' value='1' size='5' /> PAs";
		$template_main .= "<br />Pour <input type='text' name='sommePI' value='1' size='5' /> PIs";
		$template_main .= "<br />".BOUTON_ENVOYER;
		$template_main .= "<input type='hidden' name='etape' value='1' />";
		$template_main .= "</form>";
	} else {
		$template_main .= "Vous ne pouvez rien apprendre ici. <br />";
	}
	
	$template_main .= "</div>";
	$etape=0;
} 

if($etape=="1"){

	if ($sommePA<0 ||$sommePI<0)
		$template_main .= "Vous ne pouvez mettre de points n&eacute;gatifs";
	else {
			
		if(isset($competenceID))
			$ok=true;
		else 	$ok=false;
		if( !($ok) ){
			$template_main .= GetMessage("noparam");
			$template_main .= "<br /><p>&nbsp;</p>";
		}
		else {
			$competence=array_search($competenceID, $liste_comp_full); 
			// Nego du prix
			$prix = (($PERSO->GetNiveauComp($liste_comp_full[$competence],true))+1)*100;
			$prix = reussite_negociationprix($PERSO,"ACHAT",$prix);
	
			$param = array();
			array_push ($param,$sommePA);
			array_push ($param,$sommePI);
			$template_main .=demandeAccord($competenceID,$prix,"",$param);		
		} 
	}
}



if($etape==2){
		
	$sortir = $PERSO->etreCache(0);
	if ($sortir) {
			$mess = GetMessage("semontrer_01");
			$valeurs[0]	= $PERSO->nom;
			$mess_spect = GetMessage("semontrer_spect",$valeurs);
	}	
	else {
		$mess="";	
		$mess_spect="";
	}		
	if ($accord==1) {
		if(  ($PERSO->ModPA(-$p0)) && ($PERSO->ModPI(-$p1))){ 
			$valeurs[0] = array_search($id_objet, $liste_comp_full); 
			if (($PERSO->ModPO(-$prix))){
				// Reussite de l'apprentissage
				$reussite = reussite_apprentissagecompetence($PERSO, $valeurs[0],$p0, $p1);
				if($reussite > 0){
						$PERSO->OutPut($mess.GetMessage("apprentissage_01",$valeurs),true);
						$PERSO->AugmenterNiveau($id_objet);
				} else {
						$PERSO->OutPut($mess.GetMessage("apprentissage_02",$valeurs),true);
				}
			} else {
				$valeurs[0] = " l'apprentissage de la compétence " . $valeurs[0];
				$valeurs[1] = $prix;		
				$PERSO->OutPut($mess.GetMessage("magasin_objet_acheter_nopos",$valeurs),true);
			}		
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
	else $PERSO->OutPut($mess.GetMessage("magasin_abandonner_nego",array()),true);
	$template_main .= "<br /><p>&nbsp;</p>";
}


if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>