<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: crier.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.4 $
$Date: 2006/04/18 11:09:17 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $crier;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}	


if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!$PERSO->Lieu->permet($liste_flags_lieux["Parler"])){
	$template_main .= GetMessage("noright");
}
else {	
	if(!isset($etape)) {
		$etape=0;
		$msg="";
	}	
		
	if($etape=="1"){
		if ($typeact=='lieu')  $pj=array();
		if ($typeact=='voisin')  $pj=$lx;
		if (!(isset($pj))) $pj=null;
		if (parler ($PERSO,$typeact,$msg,$pj,true,false))
			$template_main .= "<br /><p>&nbsp;</p>";
		else $etape=0;	
	}
	$ok=false;
	if($etape===0){
		$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	        /**
	        *     il faut pouvoir parler pour crier
	        */
	        if ($PERSO->Lieu->permet($liste_flags_lieux["Parler"]) && defined("DISTANCE_CRI") && $PERSO->Lieu->permet($liste_flags_lieux["EntendreCriExterieur"])) {
	                $i=0;	                
	                $chemins=array();
	                while ($i<count($PERSO->Lieu->Chemins)) {
	                    //recupere tous les lieux entrer ou les lieux non eloignes
	                    if (($PERSO->Lieu->Chemins[$i]->distance <= DISTANCE_CRI) || ($PERSO->Lieu->Chemins[$i]->type ==0)) {
	                    	$ok=true;	                    	
	                    	if (array_key_exists($PERSO->Lieu->Chemins[$i]->Arrivee->ID, $chemins)===FALSE) {
					$chemins[$PERSO->Lieu->Chemins[$i]->Arrivee->ID]= $PERSO->Lieu->Chemins[$i]->Arrivee->nom;
				}	
	                    }
	                    $i++;    
	
	        	}    
	        	if ($ok) {
	      			$template_main .= "<br /><input type='radio' name='typeact' value='voisin' />Crier pour toutes les personnes des lieux voisins<br />";    	
				foreach($chemins as $cle => $nom) {
		                      	$template_main .= "<input type='checkbox' name='lx[".$cle."]' />".span($nom,"lieu");
		                }            
			}
			else
	            		$template_main .= GetMessage("crier_troploin");

	            }

			if ($ok) {
				$template_main .= "<hr /><textarea name='msg' rows='20' cols='50'>".$msg."</textarea>";
				$template_main .= "<br />".BOUTON_ENVOYER;
				
				$template_main .= "<input type='hidden' name='etape' value='1' />";
				//$template_main .= "<input type='hidden' name='action' value='$action' />";
			}		
			$template_main .= "</form></div>";
		}	
	}	
	if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
	if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>