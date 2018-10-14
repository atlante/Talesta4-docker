<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: donner_objet.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/01/24 17:44:01 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $donner_obj;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}		
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL =$PERSO->listeObjets(array(), null, -1,-1, -1,-1);
	$var=faitSelect("id_obj",$SQL,"",-1,array($PERSO->ID));
	if ($var[0]>0) {
		$SQL = $PERSO->listePJsDuLieuDuPerso(1, false, true,1);
		
		$var1=faitSelect("id_cible",$SQL,"",-1,array($PERSO->ID));
		if ($var1[0]>0) {
			$template_main .= "Que voulez vous donner ?<br />";
			$template_main .= $var[1]."<br />";
			$template_main .= "A qui voulez vous le donner ?<br />";
			$template_main .= $var1[1];
			$template_main .= "<br />".BOUTON_ENVOYER;
		}
		else $template_main .= "Il n'y a personne ici. <br />";	
	}
	else $template_main .= "Vous ne poss&eacute;dez rien que vous pouvez c&eacute;der. <br />";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( (isset($id_cible)) && (isset($id_obj)) && ($PERSO->ModPA($liste_pas_actions["DonnerObjet"])) && ($PERSO->ModPI($liste_pis_actions["DonnerObjet"]))){
		//$ADVERSAIRE = new Joueur($id_cible,true);
		// necessaire de charger l'inventaire, les competences (pour savoir s'il peut porter le nouvel objet)
		$ADVERSAIRE = new Joueur($id_cible,false, true,false,false,true,false);
		$valeurs[0] = $ADVERSAIRE->nom;
		$valeurs[1]	= $PERSO->nom;
		$Objet = null;
		$nb_objets =  count($PERSO->Objets);
		$i=0;
		while (($i<$nb_objets) && ($Objet == null)) {
			if($PERSO->Objets[$i]->id_clef == $id_obj){$Objet = $PERSO->Objets[$i];}
			else $i++;
		}
		if ($Objet == null)
			$template_main .= GetMessage("noparam");
		else {
			$valeurs[2] = $Objet->nom;
			$reussite = reussite_donner_objet($PERSO, $ADVERSAIRE, $Objet) ;
			if($reussite){
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
				$Objet->changeProprio($ADVERSAIRE->ID);
				traceAction("DonnerObjet", $PERSO, "", $ADVERSAIRE, $Objet->nom);
				$PERSO->OutPut($mess .GetMessage("donner_objet_01",$valeurs),true,true);
				$ADVERSAIRE->OutPut($mess_spect. GetMessage("donner_objet_01_adv",$valeurs),false,true);
			} else {
				$PERSO->OutPut(GetMessage("donner_objet_02",$valeurs),true,true);
				$ADVERSAIRE->OutPut(GetMessage("donner_objet_02_adv",$valeurs),false,true);
			}
		}	
	} else {
		if( (!isset($id_obj)) ){
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