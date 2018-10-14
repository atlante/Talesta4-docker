<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: lire.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.16 $
$Date: 2006/02/23 07:35:43 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $lire;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if (((!isset($etape))||$etape!="Archive") && (! $PERSO->Lieu->permet($liste_flags_lieux["Lire"]))) {
	$etape = "InterditLieu";
	$template_main .= GetMessage("noright");	
}

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL =$PERSO->listeObjets(array('Divers'),'Livre');
	$var=faitSelect("id_obj",$SQL,"");
	if ($var[0]>0) {
		$template_main .= "Que voulez vous lire ?<br />".$var[1];	
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= "Vous n'avez rien &agrave; lire<br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	
	if(   ($PERSO->Lieu->permet($liste_flags_lieux["Lire"])) && (isset($id_obj)) && ($PERSO->ModPA($liste_pas_actions["Lire"]) && ($PERSO->ModPI($liste_pis_actions["Lire"]))
	)){
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
		
		$Livre = null;
		$nb_objets=count($PERSO->Objets);
		for($i=0;$i<$nb_objets;$i++){
			if($PERSO->Objets[$i]->id_clef == $id_obj){$Livre = $PERSO->Objets[$i];}
		}
		if ($Livre==null)
			$template_main .= GetMessage("noparam");
		else {	
			if ($PERSO->peutUtiliserObjet($Livre)) {	
				$valeurs[0] = $Livre->nom;
				$reussite=reussite_lire($PERSO,$Livre);
		
				if($reussite > 0){
					$retour = $PERSO->GererChaineEtatTemp($Livre->provoqueetat);
					$valeurs[1] = $retour["retirer"];$valeurs[2] = $retour["ajouter"];
					$PERSO->OutPut($mess.GetMessage("lire_01",$valeurs));
				} else {
					//pas l'etat si on ne reussit pas a lire
					$retour = $PERSO->GererChaineEtatTemp("");
					$valeurs[1] = $retour["retirer"];$valeurs[2] = $retour["ajouter"];
					$PERSO->OutPut($mess.GetMessage("lire_02",$valeurs));
				}
				$PERSO->EffacerObjet($id_obj);
			}
			else {
				$valeurs=array();
				$valeurs[0]= $Livre->nom;
				$valeurs[1]= $Livre->EtatTempSpecifique->nom;
				$PERSO->OutPut(GetMessage("objet_inutilisable",$valeurs));
				
			}
		}
	} else {
		if(!isset($id_obj)){
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