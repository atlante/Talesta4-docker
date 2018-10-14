<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: terminer_quete.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.2 $
$Date: 2010/01/24 17:44:04 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $terminerQuete;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}		
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL =$PERSO->listeQuetes(array(), null, array(2), "");
	//$var=faitSelect("id_quete",$SQL,"",-1,array($PERSO->ID));
	$var=faitSelect("id_persoquete",$SQL);
	if ($var[0]>0) {
		$template_main .= GetMessage("queteAterminer")."<br />";
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= GetMessage("PasQueteEnCours")."<br />";	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 
else {

if($etape=="1"){
	if( isset($id_persoquete) ){
		$Quete = null;
		$valeurs=array();
		$nb_quetes =  count($PERSO->Quetes);
		$i=0;
        while (($i<=$nb_quetes) && ($Quete == null)) {
			if($PERSO->Quetes[$i]->id_persoquete == $id_persoquete){$Quete = $PERSO->Quetes[$i];}
			else $i++;
		}
		if ($Quete == null)
			$template_main .= GetMessage("noparam");
		else {
			$valeurs[1] = $Quete->nom_quete;
			$valeurs[2] = $Quete->acteurProposant->nom;
			$valeurs[3] = $PERSO->nom;
			if ($Quete->validationquete || $Quete->type_quete==5) {
        			$Quete->EvolutionQuete($Quete->id_persoquete,6);
        			$Quete->acteurProposant->OutPut(GetMessage("queteAccomplieAValider",$valeurs),false,true);
        			
        			if ($Quete->proposantAnonyme ) $mess= GetMessage("queteAnonymeEnAttente01",$valeurs);
        			else $mess= GetMessage("queteEnAttente01",$valeurs);
        			$PERSO->OutPut($mess,true,true);
			}
                        else {  
                                if ($PERSO->AutoValidation($Quete)) {
                			$Quete->EvolutionQuete($Quete->id_persoquete,7);
                			$PERSO->recevoirRecompensesQuete($Quete);
                			$Quete->acteurProposant->OutPut(GetMessage("queteReussie",$valeurs),false,true);
                			$PERSO->OutPut(GetMessage("queteAutoValidee",$valeurs),true,true);
                                }
                                else $PERSO->OutPut(GetMessage("queteAutoValideeKO",$valeurs),true,true);
                        }        
		}	
	} else {
		if( (!isset($id_persoquete)) ){
			$template_main .= GetMessage("noparam");
		} else {
			if ($PERSO->RIP())
				$template_main .= GetMessage("nopvs");
			else	
			if ($PERSO->Archive)
				$template_main .= GetMessage("archive");
		}
	}
	
	$template_main .= "<br /><p>&nbsp;</p>";
	
        }
}

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>