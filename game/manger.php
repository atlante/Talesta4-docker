<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: manger.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.19 $
$Date: 2010/01/24 17:44:03 $

*/

require_once("../include/extension.inc");
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $manger;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}

if ((!isset($etape)) && (! $PERSO->Lieu->permet($liste_flags_lieux["Manger"]))) {
	$etape = "InterditLieu";
	$template_main .= GetMessage("noright");
}	


if(!isset($etape)){
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	
    $temp = GetMessage("manger_question") . "<br />";
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Nourriture');
    $var= faitSelect("id_manger",$SQL,"");
    $radioChecked=false;
    if ($var[0]>0) {
        $temp .= "<input type='radio' name='typeact' value='manger'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        $radioChecked=true;
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Dopant');
    $var= faitSelect("id_dope",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='dope'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Stimulant');
    $var= faitSelect("id_stimule",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='stimule'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Consistant');
    $var= faitSelect("id_consiste",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='consiste'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Vitaminant');
    $var= faitSelect("id_vitamine",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='vitamine'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Revigorant');
    $var= faitSelect("id_revigore",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='revigore'   />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    $SQL =$PERSO->listeObjets(array('Nourriture','ProduitNaturel'),'Rare');
    $var= faitSelect("id_rare",$SQL,"");
    if ($var[0]>0) {
        
        $temp .= "<input type='radio' name='typeact' value='rare' />". GetMessage("manger");
        $temp .= $var[1]."<br />";
        if (!$radioChecked) {
            $radioChecked=true;
        }
        $temp .= "<br /><br /><br />";
    }
    if ($radioChecked)        
        $template_main .= $temp. "<br />".BOUTON_ENVOYER;
    else     $template_main .= GetMessage("manger_impossible") ."<br />";
	
	
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
    $ok=false;


    if(isset($typeact)){
        switch($typeact){
                case 'manger':{if(isset($id_manger)){$ok=true;$id_obj=$id_manger; } break;}
                case 'dope':{if(isset($id_dope)){$ok=true;$id_obj=$id_dope;} break;}
                case 'stimule':{if(isset($id_stimule)){$ok=true;$id_obj=$id_stimule;} break;}
                case 'consiste':{if(isset($id_consiste)){$ok=true;$id_obj=$id_consiste;} break;}
                case 'vitamine':{if(isset($id_vitamine)){$ok=true;$id_obj=$id_vitamine;} break;}
                case 'revigore':{if(isset($id_revigore)){$ok=true;$id_obj=$id_revigore;} break;}
                case 'rare':{if(isset($id_rare)){$ok=true;$id_obj=$id_rare;} break;}
        }
    }
	
    if( $ok && ($PERSO->Lieu->permet($liste_flags_lieux["Manger"])) && (isset($id_obj)) && ($PERSO->ModPA($liste_pas_actions["Manger"])) && ($PERSO->ModPI($liste_pis_actions["Manger"]))    ){

            $Nourriture = null;
            $i=0;
            $nbObjsPerso=count($PERSO->Objets);
            while ($Nourriture == null && $i<$nbObjsPerso) {
                if($PERSO->Objets[$i]->id_clef == $id_obj)
                     $Nourriture = $PERSO->Objets[$i];
                else $i++;
            }
            
            if ($Nourriture==null) 
               $template_main .= GetMessage("noparam");
            else {
            	$erreur=false;	
                if ($PERSO->peutUtiliserObjet($Nourriture)) {
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
        	    $degats1 = lancede($Nourriture->Degats[1]-$Nourriture->Degats[0])+$Nourriture->Degats[0];
        	    $degats2 = lancede($Nourriture->Degats[1]-$Nourriture->Degats[0])+$Nourriture->Degats[0];
        	    $degats3 = lancede($Nourriture->Degats[1]-$Nourriture->Degats[0])+$Nourriture->Degats[0];
                    $valeurs[0]=$Nourriture->nom;
                    $valeurs[1]=$degats1;
                    $valeurs[2]=$degats2;
                    $valeurs[3]=$degats3;
                    $valeurs[4]="";
                    $valeurs[5]="";                            
                    $valeurs[8]="";
                    $retour = $PERSO->GererChaineEtatTemp($Nourriture->provoqueetat);    
                    traceAction("Manger", $PERSO, $typeact, null, null, $Nourriture->nom);    
                    $valeurs[6] = $retour["retirer"];
                    $valeurs[7] = $retour["ajouter"];                    
                    $Nourriture->Decharge();
                    if( $Nourriture->Mun_actu==0)
                        $PERSO->EffacerObjet($id_obj);
                }
                else {
                    $erreur=true;	
                    $valeurs=array();
                    $valeurs[0]= $Nourriture->nom;
                    $valeurs[1]= $Nourriture->EtatTempSpecifique->nom;
                    $PERSO->OutPut(GetMessage("objet_inutilisable",$valeurs));
                    
                }
            

		//code specifique par type de nourriture
		//teste utilisabilite et le fait que nourriture existe, sinon, on n'a rien fait et on n'affiche rien
	        if ($erreur!==true && $Nourriture != null) {
	
		        if($typeact == "manger"){
		                    $PERSO->ModPV($valeurs[1], true);
		                    $PERSO->OutPut(GetMessage("manger_01",$valeurs));
		        }
		
		        if($typeact == "dope"){
		                    $PERSO->ModPA($valeurs[1], true);
		                    $PERSO->OutPut(GetMessage("manger_02",$valeurs));
		        }
		
		        if($typeact == "stimule"){
			            $PERSO->ModPI($valeurs[1], true);
			            $PERSO->OutPut(GetMessage("manger_03",$valeurs));
			            //$PERSO->OutPut("Consommer cela vous rveille, vous regagnez quelques PIs");
		        }
		
		        if($typeact == "consiste"){
		                    $PERSO->ModPV($valeurs[1]);                
		                    $PERSO->ModPI($valeurs[2],true);
		                    $PERSO->OutPut(GetMessage("manger_04",$valeurs));
		                    //$PERSO->OutPut("Consommer cela vous apaise, vous regagnez quelques PVs et quelques PIs");
		        }
		
		        if($typeact == "vitamine"){
		                    $PERSO->ModPA($valeurs[1]);                
		                    $PERSO->ModPI($valeurs[2],true);
		                    $PERSO->OutPut(GetMessage("manger_05",$valeurs));
		                    //$PERSO->OutPut("Consommer cela vous survolte, vous regagnez quelques PAs et quelques PIs");
		        }
		
		        if($typeact == "revigore"){
		                    $PERSO->ModPV($valeurs[1]);                
		                    $PERSO->ModPA($valeurs[2],true);
		                    $PERSO->OutPut(GetMessage("manger_06",$valeurs));
		                    //$PERSO->OutPut("Consommer cela vous revigore, vous regagnez quelques PVs et quelques PAs");
		        }
		
		        if($typeact == "rare"){
		                    $PERSO->ModPI($valeurs[1]);                
		                    $PERSO->ModPV($valeurs[2],true);
		                    $PERSO->ModPA($valeurs[3], true);
		                    $PERSO->OutPut(GetMessage("manger_07",$valeurs));
		                    //$PERSO->OutPut("Consommer cela vous exulte, vous regagnez des PVs, des PAs et des PIs");
		        }
		   } 
	      }	
	}
	else {
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