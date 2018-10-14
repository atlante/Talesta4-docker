<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: mettrearmure.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.20 $
$Date: 2010/01/24 17:44:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $revetir;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL=$PERSO->listeObjets(array('Armure', 'ArmeMelee', 'ArmeJet'), null,-1,0,0,-1);
	$var=faitSelect("id_obj",$SQL,"");
	if ($var[0]>0) {
		$template_main .= GetMessage("QuestionEquiper")."<br />".$var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}	
	else $template_main .= GetMessage("SEquiperKO"). "<br />";
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( 	(isset($id_obj)) && ($PERSO->ModPA($liste_pas_actions["MettreArmure"])) && ($PERSO->ModPI($liste_pis_actions["MettreArmure"]))){

		$deja = false;
		$deja1 = false;
		$Armure = null;
		$i=0;
		$nb_objets= count($PERSO->Objets);
		while ($Armure == null && $i< $nb_objets) {
			if($PERSO->Objets[$i]->id_clef == $id_obj) $Armure = $PERSO->Objets[$i];
			else $i++;
		}
		if ($Armure == null) {
			$template_main .= GetMessage("noparam");
		}	
		else {
			if ($PERSO->peutUtiliserObjet($Armure)) {	
		                $partieCorpsObjetID1 = "";
				for($i=0;$i<$nb_objets;$i++){
					if($PERSO->Objets[$i]->id_clef != $id_obj){
						// Bug vu par KiwiToast. Ce test n'est plus necessaire depuis qu'on indique les quantites max dans const.php
						//if(($PERSO->Objets[$i]->Soustype == $Armure->Soustype) && ($PERSO->Objets[$i]->equipe == 1)){$deja=true;}
						if(!$deja)
							if (array_key_exists ($Armure->PartieCorps,$PERSO->EquipOccupe)) 
								if ($PERSO->EquipOccupe[$Armure->PartieCorps]+$Armure->QteOccupee>$Armure->QteTotale){$deja1=true;}
					}
					if ($partieCorpsObjetID1=="" && $PERSO->Objets[$i]->ID==1 && $PERSO->Objets[$i]->equipe==1)
					        $partieCorpsObjetID1= $PERSO->Objets[$i]->PartieCorps;
				}
		
				if((!$deja)&&(!$deja1)) {
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
		
					$valeurs[0]=$Armure->nom;
				        /**
				        *   Modifs les armures ne declenchent plus systematiquement les etats temps 
				        */					
					$retour = $PERSO->GererChaineEtatTemp($Armure->provoqueetat,false);
					$valeurs[1] = $retour["ajouter"];
					if (array_key_exists ($Armure->PartieCorps,$PERSO->EquipOccupe)) 
						$PERSO->EquipOccupe[$Armure->PartieCorps]+=$Armure->QteOccupee;
					else $PERSO->EquipOccupe[$Armure->PartieCorps]=$Armure->QteOccupee;	
		
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 1 WHERE id_clef = ".$id_obj." AND id_perso = ".$PERSO->ID;
					if ($result=$db->sql_query($SQL)) {	
					        if ($partieCorpsObjetID1!="" && $Armure->PartieCorps==$partieCorpsObjetID1) {
        						//autosuppression du poing qui est autoequipe
        						$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 0 WHERE id_objet = 1 AND id_perso = ".$PERSO->ID;
        						$result=$db->sql_query($SQL);
                                                }
                                                if ($result)
						        $PERSO->OutPut($mess.GetMessage("mettre_armure_01",$valeurs));
					}			
					$template_main .= $db->erreur;
					
				} else {
					if ($deja) {
						$valeurs[0]=$Armure->Soustype;
						$template_main .= GetMessage("mettre_armure_02",$valeurs);
					} else {
						$valeurs[0]=$Armure->PartieCorps;
						$template_main .= GetMessage("mettre_armure_03",$valeurs);
					}
					
				}		
			}
			else {
				$valeurs=array();
				$valeurs[0]= $Armure->nom;
				$valeurs[1]= $Armure->EtatTempSpecifique->nom;
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