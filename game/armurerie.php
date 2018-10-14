<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $  

$RCSfile: armurerie.php,v $
*/

/**
Briève Description à mettre ici
.\file
$Revision: 1.24 $
$Date: 2010/01/24 17:44:00 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);
if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $armurerie;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

if ($PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie"]) 
	|| $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Repare"]) 
	|| $PERSO->Lieu->aMagasin($liste_types_magasins["Armurerie-Recharge"])) {
if(!isset($etape)){
	
	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.pointeur as idselect, ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat( concat( ";
	$SQL .= "concat(concat( concat( concat( concat( concat( concat( concat( concat( concat( concat( concat( T2.nom, '- ' ) , T2.sous_type ) , 
	CASE WHEN T2.type = 'Armure' THEN concat( concat( ' (Protege de ', T2.competence ) , ')' ) 
	ELSE '' END ) , ' - ' ) , CASE WHEN T2.type = 'Armure' THEN concat( T2.degats_min, ' pts de protect' ) 
	ELSE concat( 'Degats :', T2.degats_min ) END ) , ' à ' ) , T2.degats_max ) , ' - ' ) , substring( T2.description, 1, 40 ) ), '... ' )";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "  , T2.prix_base ) , ' POs - ' )";
	$SQL .= ", T2.poids ) , ' kg' ) 
	 as labselect FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND (stockmax = -1 or quantite >0 ) AND id_lieu =".$PERSO->Lieu->ID." AND T1.type = ".$liste_types_magasins["Armurerie"];
	$var=faitSelect("id_achat",$SQL,"");
	$radioChecked=false;
	if ($var[0]>0) {
		$template_main .= "<input type='radio' name='typeact' value='achat'  checked='checked' />".GetMessage("acheterObj")."<br />";
		$template_main .= $var[1]."<br />";
		$radioChecked=true;
		
	}
	else $template_main .= $PERSO->OutPut(GetMessage("MagasinRienAVendre",array()))."<br />";
	
	$SQL = $PERSO->listeObjets($type=array('ArmeJet','ArmeMelee','Armure','Munition'), $sous_type = null, -1,-1,-1, -1, true);
	$var=faitSelect("id_vendre",$SQL,"");
	if ($var[0]>0) {
		$template_main .= "<input type='radio' name='typeact' value='vendre'";
		if (!$radioChecked) {
			$radioChecked=true;
			$template_main .= " checked='checked'";
		}		
	
		$template_main .= " />".GetMessage("vendreObj")."<br />";
		$template_main .= $var[1]."<br />";
	}
  else $template_main .= $PERSO->OutPut(GetMessage("MagasinRienAAcheter",array()))."<br />";
	
	$SQL = "Select T1.id_clef as idselect, ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat( concat( concat(";	
	$SQL .= "concat(concat(concat(concat(concat(concat(T1.id_clef,'-'),T2.nom),'  -'),T1.durabilite),'/'),T2.durabilite)";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= " ,' ('),T2.prix_base),' POs de base)')";
	$SQL .= " as labselect from ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_OBJET." T2, ".NOM_TABLE_MAGASIN." T3 WHERE T1.id_objet = T2.id_objet AND T1.id_perso = ".$PERSO->ID."  AND T2.durabilite > -1 AND T1.durabilite < T2.durabilite AND (T2.type = 'ArmeMelee' OR T2.type = 'ArmeJet' OR T2.type='Armure') AND T2.id_objet = T3.pointeur AND T3.type = ".$liste_types_magasins["Armurerie-Repare"]." AND T3.id_lieu = ".$PERSO->Lieu->ID;
	$var= faitSelect("id_reparer",$SQL,"");
	if ($var[0]>0) {
		$template_main .= "<input type='radio' name='typeact' value='reparer'";
		if (!$radioChecked) {
			$radioChecked=true;
			$template_main .= " checked='checked'";
		}		
	
		 $template_main .= " />".GetMessage("ReparerObjet")."<br />";
		$template_main .= $var[1]."<br />";
	}
	else $template_main .= $PERSO->OutPut(GetMessage("MagasinRienAReparer",array()))."<br />";
	
	$SQL = "Select T1.id_clef as idselect, ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat( concat( concat(";
	$SQL .= "concat(concat(concat(concat(concat(concat(T1.id_clef,'-'),T2.nom),'  -'),T1.munitions),'/'),T2.munitions)";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= ",' ('),T2.prix_base),' POs de base)')";
	$SQL .= "as labselect from ".NOM_TABLE_PERSOOBJET." T1, ".NOM_TABLE_OBJET." T2, ".NOM_TABLE_MAGASIN." T3 WHERE T1.id_objet = T2.id_objet AND T1.id_perso = ".$PERSO->ID." AND  T2.munitions > -1 AND T1.munitions < T2.munitions AND (T2.type = 'ArmeMelee' OR T2.type = 'ArmeJet') AND T2.id_objet = T3.pointeur AND T3.type = ".$liste_types_magasins["Armurerie-Recharge"]." AND T3.id_lieu = ".$PERSO->Lieu->ID;
	$var=faitSelect("id_recharger",$SQL,"");
	if ($var[0]>0) {

		$template_main .= "<input type='radio' name='typeact' value='recharger'";
		if (!$radioChecked) {
			$radioChecked=true;
			$template_main .= " checked='checked'";
		}		
 		$template_main .= " />".GetMessage("RechargerObjet")."<br />";
		$template_main .= $var[1]."<br />";
	}	
	else $template_main .= $PERSO->OutPut(GetMessage("MagasinRienARecharger",array()))."<br />";
	
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
				case 'vendre':{if(isset($id_vendre)){$ok=true;} break;}
				case 'reparer':{if(isset($id_reparer)){$ok=true;} break;}
				case 'recharger':{if(isset($id_recharger)){$ok=true;} break;}
		}
	}
	if( ($ok) && ($PERSO->ModPA($liste_pas_actions["Armurerie"])) && ($PERSO->ModPI($liste_pis_actions["Armurerie"]))){
		$objet_correct = true;
		if($typeact == "achat"){
			$Objet = null;
			$nb_mags = count($PERSO->Lieu->Magasins);
			for($i=0;$i<$nb_mags;$i++){
				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Armurerie"]){
					$j=0;
					$nbItems = count($PERSO->Lieu->Magasins[$i]->Items);
					while ( ($j< $nbItems) && ($Objet == null)){						
						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $id_achat){$Objet = $PERSO->Lieu->Magasins[$i]->Items[$j];}
						else $j++;
					}					
				}
			}
			if ($Objet <> null) {
				$prix = $Objet->prix_base;
				$prix = reussite_negociationprix($PERSO,"ACHAT",$prix);			
				$template_main .=demandeAccord($Objet->ID,$prix,$typeact);
				$objet_correct =true;
			}
			else $template_main .= GetMessage("noparam");
		
		}
		if($typeact == "vendre"){
			$Objet = null;
			$objet_correct =true;
			$nb_objets = count($PERSO->Objets);
			$i=0;
			while (($i<$nb_objets) && ($Objet == null)) {
				if($PERSO->Objets[$i]->id_clef == $id_vendre){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
			if ($Objet <> null) {
				$prix = $Objet->GetPrixModifie();
				$prix = reussite_negociationprix($PERSO,"VENTE",$prix);
				$param = array();
				array_push ($param,$Objet->id_clef);				
				$template_main .=demandeAccord($Objet->ID,$prix,$typeact,$param);			
			}
			else $template_main .= GetMessage("noparam");
		}
		
		if($typeact == "reparer"){
			$Objet = null;
			$nb_objets = count($PERSO->Objets);
			for($i=0;$i<$nb_objets;$i++){
				if($PERSO->Objets[$i]->id_clef == $id_reparer){$Objet = $PERSO->Objets[$i];}
			}
			//huhu, la on test si on essaye pas de nous passer un objet pas reparable ici
			$objet_correct = false;
			$nb_mags = count($PERSO->Lieu->Magasins);
			for($i=0;$i<$nb_mags;$i++){
				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Armurerie-Repare"]){
					for($j=0;$j<count($PERSO->Lieu->Magasins[$i]->Items);$j++){
						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $Objet->ID){$objet_correct=true;}
					}
				}
			}
			if($objet_correct){
				$prix = ceil(ceil($Objet->prix_base / $Objet->durabilite)*($Objet->durabilite - $Objet->Dur_actu));
				$prix = reussite_negociationprix($PERSO,"ACHAT",$prix);
			  $param = array();
			  array_push ($param,$Objet->id_clef);				
			  $template_main .=demandeAccord($Objet->ID,$prix,$typeact,$param);			

				//$template_main .=demandeAccord($Objet->id_clef,$prix,$typeact);
			}
		}
		if($typeact == "recharger"){
			$Objet = null;
			$nb_objets = count($PERSO->Objets);
			$i=0;
			while (($i<$nb_objets) && ($Objet == null)) {			
				if($PERSO->Objets[$i]->id_clef == $id_recharger){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
			//huhu, la on test si on essaye pas de nous passer un objet pas reparable ici
			$objet_correct = false;
			$nb_mags = count($PERSO->Lieu->Magasins);
			for($i=0;$i<$nb_mags;$i++){
				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Armurerie-Recharge"]){
					for($j=0;$j<count($PERSO->Lieu->Magasins[$i]->Items);$j++){
						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $Objet->ID){$objet_correct=true;}
					}
				}
			}
			if($objet_correct){
				$prix = ceil(ceil($Objet->prix_base / $Objet->munitions)*($Objet->munitions - $Objet->Mun_actu));
				$prix = reussite_negociationprix($PERSO,"ACHAT",$prix);
			  $param = array();
			  array_push ($param,$Objet->id_clef);				
			  $template_main .=demandeAccord($Objet->ID,$prix,$typeact,$param);							
			}
		}	
		if(!$objet_correct){
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




if($etape=="2"){
		
	$sortir = $PERSO->etreCache(0);
	if ($sortir)
		$mess = GetMessage("semontrer_01");
	else $mess="";	

	if ($accord==1) {

		if($typeact == "achat"){
			//$Objet = new Objet ($id_objet); 
			$Objet = null;
			$nb_mags = count($PERSO->Lieu->Magasins);
			$i=0;
			while($i<$nb_mags && ($Objet == null)){
				if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Armurerie"]){
					$j=0;
					$nbItems = count($PERSO->Lieu->Magasins[$i]->Items);
					while ( ($j< $nbItems) && ($Objet == null)){						
						if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $id_objet){$Objet = $PERSO->Lieu->Magasins[$i]->Items[$j];}
						else $j++;
					}					
				}
			 	$i++;
			}
			if ($Objet <> null) {
        			$valeurs[0] = $Objet->nom;
        			$valeurs[1] = $prix;
        
        			if($PERSO->ModPO(-$prix)){
        				if($PERSO->AcquerirObjet($Objet)){
        				        $Objet->DiminueQuantite(1);
        					$PERSO->OutPut($mess.GetMessage("magasin_objet_acheter_01",$valeurs),true);
        				} else {
        					$PERSO->OutPut($mess.GetMessage("sacplein",$valeurs),true);
        				}
        			} else {
        				$PERSO->OutPut($mess.GetMessage("magasin_objet_acheter_nopos",$valeurs),true);
        			}
        		}
			else $template_main .= GetMessage("noparam");	
		}
		if($typeact == "vendre"){
			$nb_objets = count($PERSO->Objets);
			$i=0;
			$Objet = null;
			while (($i<$nb_objets) && ($Objet == null)) {			
				if($PERSO->Objets[$i]->id_clef == $p0){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
			if ($Objet <> null) {
				$valeurs[0] = $Objet->nom;
				$valeurs[1] = $prix;
				//ajoute l'objet au magasin
				$Objet->ajouteMagasin($PERSO->Lieu,$liste_types_magasins["Armurerie"]);
	
				if ($PERSO->ModPO($prix)) 
					if ($Objet->Detruire())
						$PERSO->OutPut($mess.GetMessage("magasin_objet_vendre_01",$valeurs),true);
			}
			else $template_main .= GetMessage("noparam");
		}
		
		if($typeact == "reparer"){
			$nb_objets = count($PERSO->Objets);
			$i=0;
			$Objet = null;
			while (($i<$nb_objets) && ($Objet == null)) {			
				if($PERSO->Objets[$i]->id_clef == $p0){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
			if ($Objet <> null) {
				$valeurs[0] = $Objet->nom;
				$valeurs[1] = $prix;
	
				if($PERSO->ModPO(-$prix)){
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET durabilite = '".$Objet->durabilite."' WHERE id_clef = '".$Objet->id_clef."' AND id_perso = '".$PERSO->ID."'";
					if ($db->sql_query($SQL))
						$PERSO->OutPut($mess.GetMessage("magasin_objet_reparer_01",$valeurs),true);
					else $template_main .= $db->erreur;	
				} else {
					if ($db->erreur!="")
						$template_main .= $db->erreur;
					else $PERSO->OutPut($mess.GetMessage("magasin_objet_acheter_nopos",$valeurs),true);
				}
			}
			else $template_main .= GetMessage("noparam");
		}
		if($typeact == "recharger"){
			$nb_objets = count($PERSO->Objets);
			$i=0;
			$Objet = null;
			while (($i<$nb_objets) && ($Objet == null)) {			
				if($PERSO->Objets[$i]->id_clef == $p0){$Objet = $PERSO->Objets[$i];}
				else $i++;
			}
			if ($Objet <> null) {
				$valeurs[0] = $Objet->nom;
				$valeurs[1] = $prix;
	
				if($PERSO->ModPO(-$prix)){
					$SQL = "UPDATE ".NOM_TABLE_PERSOOBJET." SET munitions = '".$Objet->munitions."' WHERE id_clef = '".$Objet->id_clef."' AND id_perso = '".$PERSO->ID."'";
					if ($db->sql_query($SQL))
						$PERSO->OutPut($mess.GetMessage("magasin_objet_recharger_01",$valeurs),true);
					else $template_main .= $db->erreur;	
				} else {
					if ($db->erreur!="")
						$template_main .= $db->erreur;
					else 	$PERSO->OutPut($mess.GetMessage("magasin_objet_reparer_nopos",$valeurs),true);
				}
			}	
			else $template_main .= GetMessage("noparam");		
		}
	}	
	else $PERSO->OutPut($mess.GetMessage("magasin_abandonner_nego",array()),true);
	
	$template_main .= "<br /><p>&nbsp;</p>";
}
}
else $template_main .= $PERSO->OutPut(GetMessage("pasDeMagasin",array()));

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>