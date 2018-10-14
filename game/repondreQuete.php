<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: repondreQuete.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.1 $
$Date: 2006/09/04 20:47:30 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $repondreQuete;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}		
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL =$PERSO->listeQuetes(array(), null, array(1), " refuspossible=1");
	//$var=faitSelect("id_quete",$SQL,"",-1,array($PERSO->ID));
	$var=faitSelect("id_persoquete",$SQL);
	if ($var[0]>0) {
		$template_main .= GetMessage("queteArepondre")."<br />";
		$template_main .= $var[1];
		$template_main .= "<br />".BOUTON_ENVOYER;
	}
	else $template_main .= GetMessage("PasQueteRefusee")."<br />";		
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
                        	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
                        	$template_main .= "<a href=\"javascript:a('../bdc/quete.$phpExtJeu?num_quete=".$Quete->id_quete."')\">".span($Quete->nom_quete,"quete")."</a>";
                		$template_main .= GetMessage("queteQuestion")."<br />";
                		$template_main .="<select name='accepte'><option value='ChoixNonFait'>&nbsp;</option><option value='0'>Non</option><option value='1'>Oui</option></select>";
                		$template_main .= "<br />".BOUTON_ENVOYER;
                        	$template_main .= "<input type='hidden' name='etape' value='2' />";
                        	$template_main .= "<input type='hidden' name='id_persoquete' value='".$Quete->id_persoquete."' />";
                        	$template_main .= "</form></div>";
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
        } 
        
        if($etape=="2"){
        	
        	if( isset($id_persoquete) ){
        	        if ($accepte=="ChoixNonFait") {
        	                $template_main .= GetMessage("ChoixQueteNonFait");
        	        }               	          		
			else {
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
                			if ($Quete->etat==1) { 
                			        $valeurs[1] = $Quete->nom_quete;
                			        $valeurs[2] = $Quete->acteurProposant->nom;
                			        $valeurs[3] = $PERSO->nom;
                			        if (! $Quete->proposantAnonyme)
                				        $messAcceptation = GetMessage("queteAcceptee",$valeurs);
                				else $messAcceptation = GetMessage("queteAnonymeAcceptee",$valeurs);
                				if ($accepte==1) {
                					$Quete->EvolutionQuete($Quete->id_persoquete,2);
                					$PERSO->OutPut($messAcceptation,true,true);
                					$Quete->acteurProposant->OutPut(GetMessage("queteAccepteeProposant",$valeurs),false,true);
                				}
                				else {
                					$Quete->EvolutionQuete($Quete->id_persoquete,3);
                        			        if (! $Quete->proposantAnonyme)
                        				        $messRefus = GetMessage("queteRefusee",$valeurs);
                        				else $messRefus = GetMessage("queteAnonymeRefusee",$valeurs);
                					$PERSO->OutPut($messRefus,true,true);
                					$Quete->acteurProposant->OutPut(GetMessage("queteRefuseeProposant",$valeurs),false,true);
                				}	
                			}
                			else $PERSO->OutPut(GetMessage("queteMauvaisEtat",$valeurs));
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