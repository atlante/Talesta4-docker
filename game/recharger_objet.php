<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: recharger_objet.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.12 $
$Date: 2006/01/31 12:26:25 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $recharger;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL =$PERSO->listeObjets(array('ArmeMelee','ArmeJet'), null,0,0,0,0). " and T1.munitions<>-1";
	$var=faitSelect("id_arme",$SQL,"",-1);
	if ($var[0]>0) {
	$SQL =$PERSO->listeObjets(array('Munition'),null,0,0,0,0);
	$var1=faitSelect("id_munition",$SQL,"",-1);
		if ($var1[0]>0) {
			$template_main .= "Que voulez vous recharger ?<br />";
			$template_main .= $var[1]."<br />";
			$template_main .= "Avec quoi ?<br />";
			$template_main .= $var1[1];
			$template_main .= "<br />".BOUTON_ENVOYER;
		}
		else $template_main .= "Vous n'avez aucune munition. <br />";	
	}
	else $template_main .= "Vous ne poss&eacute;dez rien que vous pouvez recharger. <br />";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	if( !(isset($id_munition)) || ! (isset($id_arme)))
		$ok=false;
	else {	
		$Arme = null;
		$Munition = null;
		for($i=0;$i<count($PERSO->Objets);$i++){
			if($PERSO->Objets[$i]->id_clef == $id_arme){$Arme = $PERSO->Objets[$i];}
			else if($PERSO->Objets[$i]->id_clef == $id_munition){$Munition = $PERSO->Objets[$i];}
		}
		if ($Arme==null ||$Munition==null)
			$ok=false;					
		else {	
			if ( ! $PERSO->peutUtiliserObjet($Arme)) {
				$valeurs=array();
				$valeurs[0]= $Arme->nom;
				$valeurs[1]= $Arme->EtatTempSpecifique->nom;
				$PERSO->OutPut(GetMessage("objet_inutilisable",$valeurs));
			}
			else
			if ( ! $PERSO->peutUtiliserObjet($Munition)) {
				$valeurs=array();
				$valeurs[0]= $Munition->nom;
				$valeurs[1]= $Munition->EtatTempSpecifique->nom;
				$PERSO->OutPut(GetMessage("objet_inutilisable",$valeurs));
			}
			else
			if( ($PERSO->ModPA($liste_pas_actions["RechargerObjet"])) && ($PERSO->ModPI($liste_pis_actions["RechargerObjet"]))){
				$sortir=false;

				$valeurs[0]=$Arme->nom;
				$valeurs[1]=$Munition->nom;
				if($Munition->Soustype<>$Arme->Soustype){
						$PERSO->OutPut(GetMessage("recharger_objet_01",$valeurs),true,true);
				}	
				else {
					$valeurs[2]=$Munition->Mun_actu ;
					$valeurs[3]=$Munition->Mun_actu + $Arme->Mun_actu;
					if ($Arme->munitions <$Munition->Mun_actu + $Arme->Mun_actu)
						$PERSO->OutPut(GetMessage("recharger_objet_03",$valeurs),true,true);
					else {	
						/**
						 on peut recharger tout en restant cache
						$sortir = $PERSO->etreCache(0);
						*/
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


						$Arme->Recharge($Munition->munitions);
						$Munition->Detruire();
						$PERSO->OutPut($mess.GetMessage("recharger_objet_02",$valeurs),true,true);
					}
				}
				$ok=true;
			}
			else 
				$ok=false;					
		}
	}		
	if (! $ok) {
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