<?php
/*
Fichier de Talesta4 (cf. http://www.talesta.free.fr) version: $Name: V3_6 $ 

$RCSfile: recolte.php,v $
*/

/**
Brive Description  mettre ici
.\file
$Revision: 1.9 $
$Date: 2006/09/05 05:52:15 $

*/

require_once("../include/extension.inc");
include('../include/http_get_post.'.$phpExtJeu);
//if(!defined("PAS_DE_QUERY")){Define("PAS_DE_QUERY",1);}
if(!defined("PAGE_EN_JEU")){Define("PAGE_EN_JEU",1);}
if(!defined("__titre.'.$phpExtJeu")){include('../include/titre.'.$phpExtJeu);}
$titrepage = $recolte;
if(!defined("__HEADER.PHP")){include('../include/header.'.$phpExtJeu);}
if($PERSO->Archive){
	//pour ne rien faire de ce qu'il y a en dessous sauf les 2 includes
	$etape="Archive";	
}	

	$necessiteOutil = false;
	
	if ($action=="Miner") {
		$SousTypeProduit =array("Metal");
		$SousTypeOutil = 'Mineur';
		$necessiteOutil = true;
	}	
	elseif ($action=="Cueillir") {
		$SousTypeProduit =array("Vegetaux","Nourriture","Dopant","Stimulant","Consistant","Vitaminant","Revigorant","Rare");
		$SousTypeOutil = 'Cueilleur';		
	}	
	elseif ($action=="Scier") {
		$SousTypeProduit =array("Bois");
		$SousTypeOutil = 'Bucheron';			
		$necessiteOutil = true;
	}
	elseif ($action=="Pierre") {
		$SousTypeProduit =array("Pierre");
		$SousTypeOutil = 'Carriere';			
		$necessiteOutil = true;
	}
	else {
		$template_main .= GetMessage("noparam");
		//set etape pour ne plus rien faire sauf include
		$etape="Erreur";
	}	
	$competence = $SousTypeOutil;
	$InTypeProduit = implode("','", $SousTypeProduit);
	logDate("InTypeProduit".$InTypeProduit);

if ($PERSO->Lieu->aMagasin($liste_types_magasins["Produits Naturels"],$SousTypeProduit) ) {
if(!isset($etape)){

	$template_main .= "<div class ='centerSimple'><form action='".NOM_SCRIPT."' method='post'>";
	$SQL = "SELECT T1.pointeur as idselect, ";
	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
		$SQL .= "concat( concat( ";
	$SQL .= "concat( concat( concat( concat( concat( concat( concat( concat( concat( T2.nom, '- ' ) , T2.sous_type ) , 
 	'' ) , ' - ' ) ,  ' - ' ) , substring( T2.description, 1, 40 ) ) , '... ' ) ";
 	if(defined("AFFICHE_PRIX_OBJET_SORT") && AFFICHE_PRIX_OBJET_SORT==1)
 		$SQL .= ", T2.prix_base ) , ' POs - ' ) ";
 	$SQL .= ", T2.poids ) , ' kg' ) 
	 as labselect FROM ".NOM_TABLE_MAGASIN." T1, ".NOM_TABLE_OBJET." T2 WHERE T1.pointeur = T2.id_objet AND id_lieu =".$PERSO->Lieu->ID." AND T1.type = ".$liste_types_magasins["Produits Naturels"]." and T2.sous_type in  ('".$InTypeProduit."')";
	$var=faitSelect("id_achat",$SQL,"");
	$radioChecked=false;
	if ($var[0]>0) {
		$SQL=$PERSO->listeObjets(array("Outil"), $SousTypeOutil,-1,0,0);
		
		if ($necessiteOutil==false)
			$var1= faitSelect("id_outil",$SQL,"",-50,array(),array(array("",Getmessage("RecolteManuelle"))));	
		else $var1= faitSelect("id_outil",$SQL);		
		if ($var1[0]>0 || ($necessiteOutil==false)) {
			$template_main .= "<input type='radio' name='typeact' value='achat'  checked='checked' />".GetMessage("recolter")."<br />";
			$template_main .= $var[1]."<br />";
			$template_main .= "<br />".GetMessage("recolteAvecOutil");		
			$template_main .= $var1[1];			
			$radioChecked=true;
			if ($radioChecked)		
				$template_main .= "<br /><br />".BOUTON_ENVOYER;
			else $template_main .= $PERSO->OutPut(GetMessage("pasDeRecolte",array()));

		}	
		else $template_main .= GetMessage("pasDOutil");
	}
	else $template_main .= $PERSO->OutPut(GetMessage("pasDeRecolte01",array()));
	$template_main .= "<input type='hidden' name='etape' value='1' />";
	$template_main .= "<input type='hidden' name='necessiteOutil' value='$necessiteOutil' />";
	$template_main .= "<input type='hidden' name='action' value='$action' />";
	$template_main .= "</form></div>";
	$etape=0;
} 

if($etape=="1"){
	$ok=true;
	if ($necessiteOutil)
		if(!isset($id_outil)){$ok=false;}
	
	if(($ok) && (isset($typeact))){
		switch($typeact){
				case 'achat':{if(isset($id_achat)){$ok=true;} break;}
		}
	}
	if( ($ok) && ($PERSO->ModPA($liste_pas_actions[$action])) && ($PERSO->ModPI($liste_pis_actions[$action]))){
		$objet_correct = true;
		$sortir = $PERSO->etreCache(0);
		if ($sortir)
			$mess = GetMessage("semontrer_01");
		else $mess="";	

		if($typeact == "achat"){
			$Outil = null;
			$ok=true;
			if ($necessiteOutil==true || (isset($id_outil) && $id_outil<>'')) {				
				$i=0;		
				$nb_objets = count($PERSO->Objets);
				while ($Outil == null  && $i<$nb_objets) {
					if($PERSO->Objets[$i]->id_clef == $id_outil)
						 $Outil = $PERSO->Objets[$i];
					else
					$i++;
				}
		
				if ($Outil==null)  {
					logDate("Outil ==null dans actions/recolte");
					if ($necessiteOutil)
						$objet_correct=false;
				}			
				else
				if (! $PERSO->peutUtiliserObjet($Outil)) {	
					$valeurs=array();
					$valeurs[0]= $Outil->nom;
					$valeurs[1]= $Outil->EtatTempSpecifique->nom;
					$PERSO->OutPut(GetMessage("objet_inutilisable",$valeurs));					
					$ok=false;
				}	
			}
			
			if($objet_correct && $ok){
				$Objet = null;
				$nb_magasins = count($PERSO->Lieu->Magasins);
				for($i=0;$i<$nb_magasins;$i++){
					if($PERSO->Lieu->Magasins[$i]->type == $liste_types_magasins["Produits Naturels"]){
						for($j=0;$j<count($PERSO->Lieu->Magasins[$i]->Items);$j++){
							if($PERSO->Lieu->Magasins[$i]->Items[$j]->ID == $id_achat){$Objet = $PERSO->Lieu->Magasins[$i]->Items[$j];}
						}
					}
				}
				$reussite = reussite_recolte($PERSO,$competence, $Objet,$Outil);				
				if ($reussite >0) {
					if ($Objet->munitions<>-1) {
						$qteAEnleverMagasin=$Objet->munitions;
					}	
					else {
						$qteAEnleverMagasin=1;	
					}	
					if($reussite > 5) {
						$qteAEnleverMagasin=$qteAEnleverMagasin*2;
					}	
					if ($Objet->stockmax <>-1) {
						$qteAEnleverMagasin = min ($qteAEnleverMagasin, $Objet->quantite);
					}
					$nbObjetsAAjouter = max(1,$qteAEnleverMagasin/$Objet->munitions);						
				}	
				else $qteAEnleverMagasin=0;				
				if($qteAEnleverMagasin>0){
					$ajout=false;
					$valeurs[0] = $Objet->nom;
					$i=1;
					while (($i<= $nbObjetsAAjouter) && ($PERSO->AcquerirObjet($Objet))){
						$Objet->DiminueQuantite($qteAEnleverMagasin);
						$ajout=true;
						$i++;	
					}
					if ($ajout) {	
						$PERSO->OutPut($mess.GetMessage("recolte_01",$valeurs),true);
					} else {
						$PERSO->OutPut($mess.GetMessage("sacplein",$valeurs),true);
					}
				} else {
					$PERSO->OutPut($mess.GetMessage("recolte_02",array()),true);
				}
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



}
else $template_main .= $PERSO->OutPut(GetMessage("pasDeRecolte",array()));

if(!defined("__MENU.PHP")){include('../game/menu.'.$phpExtJeu);}
if(!defined("__FOOTER.PHP")){include('../include/footer.'.$phpExtJeu);}
?>