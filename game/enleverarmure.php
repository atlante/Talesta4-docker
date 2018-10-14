<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: enleverarmure.php,v $
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
$titrepage = $enlever;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL=$PERSO->listeObjets(array('Armure', 'ArmeMelee', 'ArmeJet'), null,1,0,0,-1);
	$var=faitSelect("id_obj",$SQL,"");
	if ($var[0]>0) {
		$template_main .= "Que voulez vous enlever ?<br />".$var[1];	
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= " Vous n'avez rien &agrave; enlever";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( (isset($id_obj))  && ($PERSO->ModPA($liste_pas_actions["EnleverArmure"])) && ($PERSO->ModPI($liste_pis_actions["EnleverArmure"]))	){
		$Armure = null;
		$nbArmeequipe=0;
		$sortir = $PERSO->etreCache(0);
		$nbObjets= count($PERSO->Objets);
		for($i=0;$i<$nbObjets;$i++){
			if($PERSO->Objets[$i]->id_clef == $id_obj){$Armure = $PERSO->Objets[$i];}
			if ($PERSO->Objets[$i]->equipe && ($PERSO->Objets[$i]->type=='ArmeJet' || $PERSO->Objets[$i]->type=='ArmeMelee')) $nbArmeequipe++;
		}
	        if ($Armure== null) {
	                $template_main .= GetMessage("noparam");	                
	        }
	        else {
        		if ($Armure->competencespe == 'Maudit (ne peut etre enleve une fois mis)') {
        			$valeurs[0]=$Armure->nom;			
        			$PERSO->OutPut(GetMessage("enlever_armure_02",$valeurs));
        		}	
        		else {
        			$PERSO->EquipOccupe[$Armure->PartieCorps]-=$Armure->QteOccupee;
        			$valeurs[0]=$Armure->nom;
        			$retour = $PERSO->GererChaineEtatTemp($Armure->provoqueetat,true,true);
        			$valeurs[1] = $retour["retirer"];
        	
        			$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 0 WHERE id_clef = ".$id_obj." AND id_perso = ".$PERSO->ID;
        			$result=$db->sql_query($SQL);
        			//reequipe auto le poing
        			if ($nbArmeequipe==1 && $result!==false) {
        				$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET equipe = 1 WHERE id_objet = 1 AND id_perso = ".$PERSO->ID;
        				$result=$db->sql_query($SQL);
        			}	
        			if ($result!==false) {
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
        				$PERSO->OutPut($mess .GetMessage("enlever_armure_01",$valeurs));
        			}
        			else $template_main .= $db->erreur;
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