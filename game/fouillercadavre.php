<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: fouillercadavre.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:02 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $fouiller_cad;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if (((!isset($etape))||$etape!="Archive") && (! $PERSO->Lieu->permet($liste_flags_lieux["FouillerCadavre"]))) {
	$etape = "InterditLieu";
	$template_main .= GetMessage("noright");	
}	

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = $PERSO->listePJsDuLieuDuPerso(-1, false, true,1);
	$var=faitSelect("id_cible",$SQL,"",-1,array($PERSO->ID));
	if ($var[0]>0) {
		$template_main .= "Qui voulez vous fouiller ?<br />".$var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= "Personne n'est mort ici. <br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( (isset($id_cible)) && ($PERSO->ModPA($liste_pas_actions["FouillerCadavre"])) && ($PERSO->ModPI($liste_pis_actions["FouillerCadavre"]))){
		$valeurs[0] = "";$valeurs[1] = "";$valeurs[2] = "";

		//$ADVERSAIRE = new Joueur($id_cible,true);
		//necessaire de charger inventaire, competence
		$ADVERSAIRE = new Joueur($id_cible,false,true,false,false,true,false);
		$valeurs[0] = $ADVERSAIRE->nom;
		$valeurs[1] = $PERSO->nom;
		if($ADVERSAIRE->PV <= 0){
			traceAction("FouillerCadavre", $PERSO, "", $ADVERSAIRE);
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
			$Objet = null;
			//teste la reussite auto si objet equipe puisqu'on a vu l'objet
			if(defined("FOUILLE_OBJETS_EQUIPES") && FOUILLE_OBJETS_EQUIPES==1
          && defined("REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES") && REUSSITE_AUTO_FOUILLE_OBJETS_EQUIPES==1) {
  				
  				$nbObjs = count($ADVERSAIRE->Objets);
  				$i=0;
  				while($i<$nbObjs && $Objet== null){
  					//modif du test de temporaire  0 suite discussion avec Uriel
  					if( ($ADVERSAIRE->Objets[$i]->permanent==0) && ($ADVERSAIRE->Objets[$i]->temporaire == 0) ){
  					     if ($ADVERSAIRE->Objets[$i]->equipe == 1)
  						        $Objet = $ADVERSAIRE->Objets[$i];
  					}
  					$i++;
  				}
			}
			if ($Objet !=null) {
  			$reussite = reussite_fouillercadavre($PERSO,$ADVERSAIRE);
  			if( $reussite > 0){
  				$Objet = null;
  				$nbObjs = count($ADVERSAIRE->Objets);
  				$i=0;
  				while($i<$nbObjs && $Objet== null){
  					//modif du test de temporaire  0 suite discussion avec Uriel
  					if( ($ADVERSAIRE->Objets[$i]->permanent==0) && ($ADVERSAIRE->Objets[$i]->temporaire == 0) ){
  					        if(defined("FOUILLE_OBJETS_EQUIPES") && FOUILLE_OBJETS_EQUIPES==1) {
  						        $Objet = $ADVERSAIRE->Objets[$i];
  						} else if ($ADVERSAIRE->Objets[$i]->equipe == 0)
  						        $Objet = $ADVERSAIRE->Objets[$i];
  					}
  					$i++;
  				}
  			}	
  		}	
				if($Objet == null){
					//$PERSO->OutPut($mess .GetMessage("fouiller_cadavre_02",$valeurs));
					//$ADVERSAIRE->OutPut(GetMessage("fouiller_cadavre_02_adv",$valeurs),false);
					affiche_resultat($PERSO,$ADVERSAIRE,$mess.GetMessage("fouiller_cadavre_02",$valeurs),GetMessage("fouiller_cadavre_02_adv",$valeurs),$mess_spect.GetMessage("fouiller_cadavre_02_spec",$valeurs),true);						
				} else {
					$valeurs[2]=$Objet->nom;
					if($PERSO->sacPeutContenir($Objet->poids)){
						if($Objet->changeProprio($PERSO->ID)) {
							//$PERSO->OutPut($mess.GetMessage("fouiller_cadavre_01",$valeurs));
							//$ADVERSAIRE->OutPut(GetMessage("fouiller_cadavre_01_adv",$valeurs),false);
							affiche_resultat($PERSO,$ADVERSAIRE,$mess.GetMessage("fouiller_cadavre_01",$valeurs),GetMessage("fouiller_cadavre_01_adv",$valeurs),$mess_spect.GetMessage("fouiller_cadavre_01_spec",$valeurs),true);						
						}	
						else $template_main .= GetMessage("noparam").": ChangeProprio Rat";
					} else {
						$temp= $mess.GetMessage("fouiller_cadavre_01",$valeurs);
						$valeurs2[0]=$valeurs[2];
						$temp .= GetMessage("sacplein",$valeurs2);
						//$PERSO->OutPut($temp,true);
						//$ADVERSAIRE->OutPut(GetMessage("fouiller_cadavre_02_adv",$valeurs),false);
						affiche_resultat($PERSO,$ADVERSAIRE,$temp,GetMessage("fouiller_cadavre_02_adv",$valeurs),$mess_spect.GetMessage("fouiller_cadavre_02_spec",$valeurs),true);
					}
				}

		} else {
			$template_main .= GetMessage("ennemipasmort",$valeurs);
		}		
	} else {
		if(!$PERSO->Lieu->permet($liste_flags_lieux["FouillerCadavre"])){
			$template_main .= GetMessage("noright");
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
	}
	$template_main .= "<br /><p>&nbsp;</p>";
	
}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>
