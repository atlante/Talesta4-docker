<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: magasinmag.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.23 $
$Date: 2010/01/24 17:44:02 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $magasin_mag;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}


if(!isset($etape)){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.pointeur as idselect, ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat(concat(";	
	$SQL .= "concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(concat(T2.nom,'-  '),T2.type),'-'),T2.sous_type), ' - Degats : '),T2.degats_min),' à '),T2.degats_max), substring(T2.description,1,40)),'...  ('), case when T2.charges > 0 then concat(T2.charges,' charges, ') else 'charges infinies, ' end)";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= ",T2.prix_base),' POs, ')";
	$SQL .= ", T2.place),' pl)' ) as labselect FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_MAGIE." T2 WHERE T1.pointeur = T2.id_magie AND id_lieu =".$PERSO->Lieu->ID." AND T1.type = ".$liste_types_magasins["Magasin Magique"];
	$var= faitSelect("id_achat",$SQL,"");
	$radioChecked=false;
	if ($var[0]) {
		$template_main .= "<input type='radio' name='typeact' checked='checked' value='achat' />".GetMessage("AcheterSort")."<br />";
		$template_main .= $var[1]."<br />";
		$radioChecked=true;
	}
	else $template_main .= $PERSO->OutPut(GetMessage("MagasinRienAVendre",array()))."<br />";
		
	$SQL = "Select T1.id_clef as idselect, ";	
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat(concat(concat(";	
	$SQL .= "concat(concat(concat(concat(concat(concat(T1.id_clef,'-'),T2.nom),'  -'),T1.charges),'/'),T2.charges) ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= ",' ('),T2.prix_base),' POs de base)')";
	$SQL .= " as labselect from ".NOM_TABLE_PERSOMAGIE." T1, ".NOM_TABLE_MAGIE." T2, ".NOM_TABLE_MAGASIN." T3 WHERE T1.id_magie = T2.id_magie AND T1.id_perso = ".$PERSO->ID." AND T2.charges > -1 AND T1.charges < T2.charges AND T2.id_magie = T3.pointeur AND T3.type = ".$liste_types_magasins["Magasin Magique-Recharge"]." AND T3.id_lieu = ".$PERSO->Lieu->ID;
	$var=faitSelect("id_recharger",$SQL,"");
	if ($var[0]) {
		$template_main .= "<input type='radio' name='typeact' value='recharger'";
		if (!$radioChecked) {
			$radioChecked=true;
			$template_main .= " checked='checked'";
		}		
		$template_main .= " />".GetMessage("RechargerSort")."<br />";
		$template_main .= $var[1]."<br />";
	}	
	else $template_main .= $PERSO->OutPut(GetMessage("MagasinMagRienARecharger",array()))."<br />";
	
	if ($radioChecked)		
		$template_main .= "<br />".BOUTON_ENVOYER;
	//else $template_main .= $PERSO->OutPut(GetMessage("pasDeMagasin",array()));
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	
	
	$ok=false;
	if(isset($typeact)){
		switch($typeact){
				case 'achat':{if(isset($id_achat)){$ok=true;} break;}
				case 'recharger':{if(isset($id_recharger)){$ok=true;} break;}
		}
	}
	if( ($ok) && ($PERSO->ModPA($liste_pas_actions["MagasinMagique"])) && ($PERSO->ModPI($liste_pis_actions["MagasinMagique"]))){
		$sort_correct = true;
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
		
		if($typeact == "achat"){
			$Sort = null;
			$nb_mag = count($PERSO->Lieu->Magasins);
			$i=0;
			while( ($i<$nb_mag) && ($Sort == null)){
				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Magasin Magique"]){
					$nbItems = count($PERSO->Lieu->Magasins[$i]->Items);
					$j=0;
					while ( ($j< $nbItems) && ($Sort == null)){						
						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $id_achat){$Sort = $PERSO->Lieu->Magasins[$i]->Items[$j];}
						else $j++;
					}
				}
				$i++;
			}
			if ($Sort==null)
			        $sort_correct = false;
			else {        
        			$prix = $Sort->prix_base;
        			$prix = reussite_negociationprix($PERSO,"ACHAT",$prix);
        			$template_main .=demandeAccord($Sort->ID,$prix,$typeact);
			}
		}
		if($typeact == "recharger"){
			$Sort = null;
			$nbSorts = count($PERSO->Sorts);
			$i=0;
			while ( ($i< $nbSorts) && ($Sort == null)){
				if($PERSO->Sorts[$i]->id_clef == $id_recharger){$Sort = $PERSO->Sorts[$i];}
				else $i++;
			}
			$sort_correct = false;
			if ($Sort!=null) {  
        			$nb_mag = count($PERSO->Lieu->Magasins);
        			$i=0;
        			while( ($i<$nb_mag) && ($sort_correct == false)){
        				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Magasin Magique-Recharge"]){
        					$nbItems = count($PERSO->Lieu->Magasins[$i]->Items);
        					$j=0;
        					while ( ($j< $nbItems) && ($sort_correct == false)){						
        						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $Sort->ID){$sort_correct=true;}
        						else $j++;
        					}
        				}
        				$i++;
        			}
        			if($sort_correct){
        				$prix = ceil(ceil($Sort->prix_base / $Sort->charges)*($Sort->charges - $Sort->charges_actu));
        				$prix = reussite_negociationprix($PERSO,"VENTE",$prix);
        				$template_main .=demandeAccord($Sort,$prix,$typeact);
        			}
                        }
		}
		if(!$sort_correct){
			$template_main .= GetMessage("noparam");
			$template_main .= "<br /><p>&nbsp;</p>";
		}
	} else {
		if( !($ok) ){
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
		$template_main .= "<br /><p>&nbsp;</p>";
	}
}


if($etape==2){
	
	
	if ($accord==1) {
		$Sort = new Magie ($id_objet); 
		$valeurs[0] = $Sort->nom;
		$valeurs[1] = $prix;

		if($typeact == "achat"){

			if($PERSO->grimoirePeutContenir($Sort->place)){
				if($PERSO->ModPO(-$prix)){
					$reussite =reussite_apprentissageSort($PERSO, $Sort);
					if( $reussite> 0){						
						$SQL = "INSERT INTO ".NOM_TABLE_PERSOMAGIE." (id_perso,id_magie,charges) VALUES ('".$PERSO->ID."','".$Sort->ID."','".$Sort->charges."')";
						if($db->sql_query($SQL))
							$PERSO->OutPut(GetMessage("magasin_magie_acheter_01",$valeurs),true);
						else $template_main .= $db->erreur;	
					} else {
						$PERSO->OutPut(GetMessage("magasin_magie_acheter_02",$valeurs),true);
					}
				} else {
					$PERSO->OutPut(GetMessage("magasin_magie_acheter_nopos",$valeurs),true);
				}
			} else {
				$PERSO->OutPut(GetMessage("grimoireplein",$valeurs),true);
			}
			
		}

		if($typeact == "recharger"){
			if($PERSO->ModPO(-$prix)){
				$SQL = "UPDATE ".NOM_TABLE_PERSOMAGIE." SET charges = '".$Sort->charges."' WHERE id_clef = '".$Sort->id_clef."' AND id_perso = '".$PERSO->ID."'";
				if ($db->sql_query($SQL))
					$PERSO->OutPut(GetMessage("magasin_magie_recharger_01",$valeurs),true);
				else $template_main .= $db->erreur;	
			} else {
				$PERSO->OutPut(GetMessage("magasin_magie_recharger_nopos",$valeurs),true);
			}
		}
	}	
	else $PERSO->OutPut(GetMessage("magasin_abandonner_nego",array()),true);
	$template_main .= "<br /><p>&nbsp;</p>";

}



if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>